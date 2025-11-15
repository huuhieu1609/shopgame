<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\GiaoDichNapTien;
use App\Models\GiaoDichVi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class GiaoDichNapTienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = GiaoDichNapTien::with('khachHang');

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
        $giaoDichNapTiens = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $giaoDichNapTiens
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'khach_hang_id' => 'required|exists:khach_hang,id',
            'so_tien' => 'required|numeric|min:0',
            'phuong_thuc' => 'required|string|max:255',
            'ma_giao_dich' => 'nullable|string|max:255|unique:giao_dich_nap_tien',
            'trang_thai' => 'required|string|in:cho_xu_ly,dang_xu_ly,thanh_cong,that_bai',
            'ghi_chu' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        if (!isset($data['ma_giao_dich'])) {
            $data['ma_giao_dich'] = 'GD' . time() . rand(1000, 9999);
        }

        $giaoDichNapTien = GiaoDichNapTien::create($data);

        // Cập nhật số dư khách hàng nếu giao dịch thành công
        if ($giaoDichNapTien->trang_thai === 'thanh_cong') {
            $khachHang = $giaoDichNapTien->khachHang;
            $khachHang->so_du += $giaoDichNapTien->so_tien;
            $khachHang->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Giao dịch nạp tiền đã được tạo thành công',
            'data' => $giaoDichNapTien->load('khachHang')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $giaoDichNapTien = GiaoDichNapTien::with('khachHang')->find($id);

        if (!$giaoDichNapTien) {
            return response()->json([
                'success' => false,
                'message' => 'Giao dịch nạp tiền không tồn tại'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $giaoDichNapTien
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $giaoDichNapTien = GiaoDichNapTien::find($id);

        if (!$giaoDichNapTien) {
            return response()->json([
                'success' => false,
                'message' => 'Giao dịch nạp tiền không tồn tại'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'khach_hang_id' => 'sometimes|required|exists:khach_hang,id',
            'so_tien' => 'sometimes|required|numeric|min:0',
            'phuong_thuc' => 'sometimes|required|string|max:255',
            'ma_giao_dich' => 'nullable|string|max:255|unique:giao_dich_nap_tien,ma_giao_dich,' . $id,
            'trang_thai' => 'sometimes|required|string|in:cho_xu_ly,dang_xu_ly,thanh_cong,that_bai',
            'ghi_chu' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $oldStatus = $giaoDichNapTien->trang_thai;
        $oldSoTien = $giaoDichNapTien->so_tien;
        $giaoDichNapTien->update($request->all());
        $giaoDichNapTien->refresh();

        // Cập nhật số dư khách hàng nếu trạng thái thay đổi
        if ($oldStatus !== $giaoDichNapTien->trang_thai) {
            $khachHang = $giaoDichNapTien->khachHang;
            if ($oldStatus === 'thanh_cong') {
                $khachHang->so_du -= $oldSoTien;
            }
            if ($giaoDichNapTien->trang_thai === 'thanh_cong') {
                $khachHang->so_du += $giaoDichNapTien->so_tien;
            }
            $khachHang->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Giao dịch nạp tiền đã được cập nhật thành công',
            'data' => $giaoDichNapTien->load('khachHang')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $giaoDichNapTien = GiaoDichNapTien::find($id);

        if (!$giaoDichNapTien) {
            return response()->json([
                'success' => false,
                'message' => 'Giao dịch nạp tiền không tồn tại'
            ], 404);
        }

        $giaoDichNapTien->delete();

        return response()->json([
            'success' => true,
            'message' => 'Giao dịch nạp tiền đã được xóa thành công'
        ]);
    }

    /**
     * Get my transactions (for client)
     */
    public function myTransactions(Request $request): JsonResponse
    {
        // TODO: Get authenticated client ID from request
        $clientId = $request->user()->id ?? 1; // Temporary
        
        $query = GiaoDichNapTien::where('khach_hang_id', $clientId);

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
        $giaoDichNapTiens = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $giaoDichNapTiens
        ]);
    }

    /**
     * Show my transaction (for client)
     */
    public function showMyTransaction(Request $request, int $id): JsonResponse
    {
        // TODO: Get authenticated client ID from request
        $clientId = $request->user()->id ?? 1; // Temporary
        
        $giaoDichNapTien = GiaoDichNapTien::where('id', $id)
            ->where('khach_hang_id', $clientId)
            ->first();

        if (!$giaoDichNapTien) {
            return response()->json([
                'success' => false,
                'message' => 'Giao dịch không tồn tại hoặc không thuộc về bạn'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $giaoDichNapTien
        ]);
    }

    /**
     * Get my wallet transactions (for client)
     */
    public function myWalletTransactions(Request $request): JsonResponse
    {
        // TODO: Get authenticated client ID from request
        $clientId = $request->user()->id ?? 1; // Temporary
        
        $query = GiaoDichVi::where('khach_hang_id', $clientId);

        // Lọc theo loại
        if ($request->has('loai')) {
            $query->where('loai', $request->get('loai'));
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Phân trang
        $perPage = $request->get('per_page', 10);
        $giaoDichVis = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $giaoDichVis
        ]);
    }
}


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\GiaoDichNapTien;
use App\Models\GiaoDichVi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class GiaoDichNapTienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = GiaoDichNapTien::with('khachHang');

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
        $giaoDichNapTiens = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $giaoDichNapTiens
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'khach_hang_id' => 'required|exists:khach_hang,id',
            'so_tien' => 'required|numeric|min:0',
            'phuong_thuc' => 'required|string|max:255',
            'ma_giao_dich' => 'nullable|string|max:255|unique:giao_dich_nap_tien',
            'trang_thai' => 'required|string|in:cho_xu_ly,dang_xu_ly,thanh_cong,that_bai',
            'ghi_chu' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        if (!isset($data['ma_giao_dich'])) {
            $data['ma_giao_dich'] = 'GD' . time() . rand(1000, 9999);
        }

        $giaoDichNapTien = GiaoDichNapTien::create($data);

        // Cập nhật số dư khách hàng nếu giao dịch thành công
        if ($giaoDichNapTien->trang_thai === 'thanh_cong') {
            $khachHang = $giaoDichNapTien->khachHang;
            $khachHang->so_du += $giaoDichNapTien->so_tien;
            $khachHang->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Giao dịch nạp tiền đã được tạo thành công',
            'data' => $giaoDichNapTien->load('khachHang')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $giaoDichNapTien = GiaoDichNapTien::with('khachHang')->find($id);

        if (!$giaoDichNapTien) {
            return response()->json([
                'success' => false,
                'message' => 'Giao dịch nạp tiền không tồn tại'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $giaoDichNapTien
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $giaoDichNapTien = GiaoDichNapTien::find($id);

        if (!$giaoDichNapTien) {
            return response()->json([
                'success' => false,
                'message' => 'Giao dịch nạp tiền không tồn tại'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'khach_hang_id' => 'sometimes|required|exists:khach_hang,id',
            'so_tien' => 'sometimes|required|numeric|min:0',
            'phuong_thuc' => 'sometimes|required|string|max:255',
            'ma_giao_dich' => 'nullable|string|max:255|unique:giao_dich_nap_tien,ma_giao_dich,' . $id,
            'trang_thai' => 'sometimes|required|string|in:cho_xu_ly,dang_xu_ly,thanh_cong,that_bai',
            'ghi_chu' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $oldStatus = $giaoDichNapTien->trang_thai;
        $oldSoTien = $giaoDichNapTien->so_tien;
        $giaoDichNapTien->update($request->all());
        $giaoDichNapTien->refresh();

        // Cập nhật số dư khách hàng nếu trạng thái thay đổi
        if ($oldStatus !== $giaoDichNapTien->trang_thai) {
            $khachHang = $giaoDichNapTien->khachHang;
            if ($oldStatus === 'thanh_cong') {
                $khachHang->so_du -= $oldSoTien;
            }
            if ($giaoDichNapTien->trang_thai === 'thanh_cong') {
                $khachHang->so_du += $giaoDichNapTien->so_tien;
            }
            $khachHang->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Giao dịch nạp tiền đã được cập nhật thành công',
            'data' => $giaoDichNapTien->load('khachHang')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $giaoDichNapTien = GiaoDichNapTien::find($id);

        if (!$giaoDichNapTien) {
            return response()->json([
                'success' => false,
                'message' => 'Giao dịch nạp tiền không tồn tại'
            ], 404);
        }

        $giaoDichNapTien->delete();

        return response()->json([
            'success' => true,
            'message' => 'Giao dịch nạp tiền đã được xóa thành công'
        ]);
    }

    /**
     * Get my transactions (for client)
     */
    public function myTransactions(Request $request): JsonResponse
    {
        // TODO: Get authenticated client ID from request
        $clientId = $request->user()->id ?? 1; // Temporary
        
        $query = GiaoDichNapTien::where('khach_hang_id', $clientId);

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
        $giaoDichNapTiens = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $giaoDichNapTiens
        ]);
    }

    /**
     * Show my transaction (for client)
     */
    public function showMyTransaction(Request $request, int $id): JsonResponse
    {
        // TODO: Get authenticated client ID from request
        $clientId = $request->user()->id ?? 1; // Temporary
        
        $giaoDichNapTien = GiaoDichNapTien::where('id', $id)
            ->where('khach_hang_id', $clientId)
            ->first();

        if (!$giaoDichNapTien) {
            return response()->json([
                'success' => false,
                'message' => 'Giao dịch không tồn tại hoặc không thuộc về bạn'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $giaoDichNapTien
        ]);
    }

    /**
     * Get my wallet transactions (for client)
     */
    public function myWalletTransactions(Request $request): JsonResponse
    {
        // TODO: Get authenticated client ID from request
        $clientId = $request->user()->id ?? 1; // Temporary
        
        $query = GiaoDichVi::where('khach_hang_id', $clientId);

        // Lọc theo loại
        if ($request->has('loai')) {
            $query->where('loai', $request->get('loai'));
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Phân trang
        $perPage = $request->get('per_page', 10);
        $giaoDichVis = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $giaoDichVis
        ]);
    }
}
