<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DonHang;
use App\Models\KhachHang;
use App\Models\ChiTietTaiKhoan;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DonHangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = DonHang::with(['khachHang', 'maGiamGia', 'chiTietDonHangs.sanPham']);

        // Lọc theo khách hàng
        if ($request->has('khach_hang_id')) {
            $query->where('khach_hang_id', $request->get('khach_hang_id'));
        }

        // Lọc theo trạng thái
        if ($request->has('trang_thai')) {
            $query->where('trang_thai', $request->get('trang_thai'));
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Phân trang
        $perPage = $request->get('per_page', 10);
        $donHangs = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $donHangs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'khach_hang_id' => 'required|exists:khach_hang,id',
            'ma_giam_gia_id' => 'nullable|exists:ma_giam_gia,id',
            'tong_tien' => 'required|numeric|min:0',
            'trang_thai' => 'required|string|in:cho_xu_ly,dang_xu_ly,dang_giao,hoan_thanh,da_huy',
            'ghi_chu' => 'nullable|string',
            'chi_tiet' => 'required|array|min:1',
            'chi_tiet.*.san_pham_id' => 'required|exists:san_pham,id',
            'chi_tiet.*.so_luong' => 'required|integer|min:1',
            'chi_tiet.*.gia' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Lấy thông tin khách hàng và kiểm tra số dư
            $khachHang = KhachHang::findOrFail($request->khach_hang_id);
            $tongTien = $request->tong_tien;

            // Kiểm tra số dư khách hàng
            if ($khachHang->so_du < $tongTien) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Số dư tài khoản không đủ để thanh toán đơn hàng',
                    'errors' => [
                        'so_du' => ['Số dư hiện tại: ' . number_format($khachHang->so_du, 0, ',', '.') . ' ₫. Số tiền cần thanh toán: ' . number_format($tongTien, 0, ',', '.') . ' ₫']
                    ]
                ], 400);
            }

            // Tạo đơn hàng
            $donHang = DonHang::create($request->except('chi_tiet'));

            // Tạo chi tiết đơn hàng và gán tài khoản
            foreach ($request->chi_tiet as $chiTiet) {
                $chiTietDonHang = $donHang->chiTietDonHangs()->create([
                    'san_pham_id' => $chiTiet['san_pham_id'],
                    'so_luong' => $chiTiet['so_luong'],
                    'gia' => $chiTiet['gia'],
                    'thanh_tien' => $chiTiet['so_luong'] * $chiTiet['gia'],
                ]);

                // Tự động lấy và gán tài khoản cho đơn hàng
                $soLuongCanLay = $chiTiet['so_luong'];
                $taiKhoans = ChiTietTaiKhoan::where('san_pham_id', $chiTiet['san_pham_id'])
                    ->where('trang_thai', true) // Chỉ lấy tài khoản còn hàng
                    ->limit($soLuongCanLay)
                    ->lockForUpdate() // Lock để tránh race condition
                    ->get();

                if ($taiKhoans->count() < $soLuongCanLay) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Sản phẩm không đủ số lượng tài khoản. Số lượng còn lại: ' . $taiKhoans->count(),
                        'errors' => [
                            'so_luong' => ['Sản phẩm chỉ còn ' . $taiKhoans->count() . ' tài khoản']
                        ]
                    ], 400);
                }

                // Đánh dấu tài khoản đã được bán (trạng thái = false) và lưu don_hang_id
                foreach ($taiKhoans as $taiKhoan) {
                    $taiKhoan->trang_thai = false;
                    $taiKhoan->don_hang_id = $donHang->id;
                    $taiKhoan->save();
                }

                // Cập nhật số lượng sản phẩm (giảm số lượng tài khoản còn lại)
                $sanPham = SanPham::find($chiTiet['san_pham_id']);
                if ($sanPham) {
                    $sanPham->so_luong = max(0, $sanPham->so_luong - $soLuongCanLay);
                    $sanPham->save();
                }
            }

            // Trừ tiền từ số dư khách hàng
            $khachHang->so_du -= $tongTien;
            
            // Cập nhật tổng chi tiêu
            $khachHang->tong_chi = ($khachHang->tong_chi ?? 0) + $tongTien;
            
            $khachHang->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đơn hàng đã được tạo thành công',
                'data' => $donHang->load(['khachHang', 'maGiamGia', 'chiTietDonHangs.sanPham'])
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo đơn hàng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $donHang = DonHang::with(['khachHang', 'maGiamGia', 'chiTietDonHangs.sanPham'])->find($id);

        if (!$donHang) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng không tồn tại'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $donHang
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $donHang = DonHang::find($id);

        if (!$donHang) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng không tồn tại'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'khach_hang_id' => 'sometimes|required|exists:khach_hang,id',
            'ma_giam_gia_id' => 'nullable|exists:ma_giam_gia,id',
            'tong_tien' => 'sometimes|required|numeric|min:0',
            'trang_thai' => 'sometimes|required|string|in:cho_xu_ly,dang_xu_ly,dang_giao,hoan_thanh,da_huy',
            'ghi_chu' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $donHang->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Đơn hàng đã được cập nhật thành công',
            'data' => $donHang->load(['khachHang', 'maGiamGia', 'chiTietDonHangs.sanPham'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $donHang = DonHang::find($id);

        if (!$donHang) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng không tồn tại'
            ], 404);
        }

        $donHang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đơn hàng đã được xóa thành công'
        ]);
    }

    /**
     * Get my orders (for client)
     */
    public function myOrders(Request $request): JsonResponse
    {
        // Get client ID from request header or query param
        $clientId = $request->header('X-Client-Id')
            ?? $request->get('client_id')
            ?? $request->user()->id
            ?? null;

        if (!$clientId) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin khách hàng'
            ], 401);
        }

        $query = DonHang::with(['maGiamGia', 'chiTietDonHangs.sanPham'])
            ->where('khach_hang_id', $clientId);

        // Lọc theo trạng thái
        if ($request->has('trang_thai')) {
            $query->where('trang_thai', $request->get('trang_thai'));
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Phân trang
        $perPage = $request->get('per_page', 10);
        $donHangs = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $donHangs
        ]);
    }

    /**
     * Show my order (for client)
     */
    public function showMyOrder(Request $request, int $id): JsonResponse
    {
        // Get client ID from request header or query param
        $clientId = $request->header('X-Client-Id')
            ?? $request->get('client_id')
            ?? $request->user()->id
            ?? null;

        if (!$clientId) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin khách hàng'
            ], 401);
        }

        $donHang = DonHang::with([
            'maGiamGia',
            'chiTietDonHangs.sanPham.danhMuc',
            'chiTietDonHangs.sanPham.chiTietTaiKhoans' => function($query) {
                $query->where('trang_thai', true)->limit(100); // Limit để tránh quá nhiều dữ liệu
            }
        ])
            ->where('id', $id)
            ->where('khach_hang_id', $clientId)
            ->first();

        if (!$donHang) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng không tồn tại hoặc không thuộc về bạn'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $donHang
        ]);
    }

    /**
     * Get account details for a product in order
     */
    public function getProductAccounts(Request $request, int $orderId, int $productId): JsonResponse
    {
        // Get client ID from request header or query param
        $clientId = $request->header('X-Client-Id')
            ?? $request->get('client_id')
            ?? $request->user()->id
            ?? null;

        if (!$clientId) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin khách hàng'
            ], 401);
        }

        // Kiểm tra đơn hàng thuộc về khách hàng
        $donHang = DonHang::where('id', $orderId)
            ->where('khach_hang_id', $clientId)
            ->first();

        if (!$donHang) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng không tồn tại hoặc không thuộc về bạn'
            ], 404);
        }

        // Kiểm tra sản phẩm có trong đơn hàng
        $chiTiet = $donHang->chiTietDonHangs()
            ->where('san_pham_id', $productId)
            ->first();

        if (!$chiTiet) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không có trong đơn hàng này'
            ], 404);
        }

        // Lấy chi tiết tài khoản đã được gán cho đơn hàng này
        $chiTietTaiKhoans = ChiTietTaiKhoan::where('don_hang_id', $orderId)
            ->where('san_pham_id', $productId)
            ->get()
            ->makeVisible(['password']); // Hiển thị password cho client

        return response()->json([
            'success' => true,
            'data' => $chiTietTaiKhoans,
            'san_pham' => $chiTiet->sanPham,
            'so_luong' => $chiTiet->so_luong
        ]);
    }
}
