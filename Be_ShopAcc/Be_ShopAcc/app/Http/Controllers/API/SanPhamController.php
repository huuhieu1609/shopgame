<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class SanPhamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = SanPham::with('danhMuc');

        // Tìm kiếm
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('ten', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('mo_ta', 'like', "%{$search}%");
            });
        }

        // Lọc theo danh mục
        if ($request->has('danh_muc_id')) {
            $query->where('danh_muc_id', $request->get('danh_muc_id'));
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
        $sanPhams = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $sanPhams
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ten' => 'required|string|max:255',
            'username' => 'nullable|string|max:255',
            'danh_muc_id' => 'required|exists:danh_muc,id',
            'mo_ta' => 'nullable|string',
            'noi_dung' => 'nullable|string',
            'gia' => 'required|numeric|min:0',
            'so_luong' => 'required|integer|min:0',
            'co' => 'nullable|string|max:10',
            'check_live' => 'boolean',
            'trang_thai' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $sanPham = SanPham::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được tạo thành công',
            'data' => $sanPham->load('danhMuc')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $sanPham = SanPham::with(['danhMuc', 'chiTietTaiKhoans'])->find($id);

        if (!$sanPham) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $sanPham
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $sanPham = SanPham::find($id);

        if (!$sanPham) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'ten' => 'sometimes|required|string|max:255',
            'username' => 'nullable|string|max:255',
            'danh_muc_id' => 'sometimes|required|exists:danh_muc,id',
            'mo_ta' => 'nullable|string',
            'noi_dung' => 'nullable|string',
            'gia' => 'sometimes|required|numeric|min:0',
            'so_luong' => 'sometimes|required|integer|min:0',
            'co' => 'nullable|string|max:10',
            'check_live' => 'boolean',
            'trang_thai' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $sanPham->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được cập nhật thành công',
            'data' => $sanPham->load('danhMuc')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $sanPham = SanPham::find($id);

        if (!$sanPham) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại'
            ], 404);
        }

        $sanPham->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được xóa thành công'
        ]);
    }

    /**
     * Get flash sale products
     */
    public function dataFlashSale(): JsonResponse
    {
        // TODO: Add flash_sale field to products if needed
        $sanPhams = SanPham::where('trang_thai', true)
            ->orderBy('gia', 'asc')
            ->limit(10)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $sanPhams
        ]);
    }

    /**
     * Get featured products
     */
    public function dataNoiBat(): JsonResponse
    {
        // TODO: Add noi_bat field to products if needed
        $sanPhams = SanPham::where('trang_thai', true)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $sanPhams
        ]);
    }

    /**
     * Get new products
     */
    public function dataNew(): JsonResponse
    {
        $sanPhams = SanPham::where('trang_thai', true)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $sanPhams
        ]);
    }

    /**
     * Get data with pagination
     */
    public function getData(Request $request): JsonResponse
    {
        return $this->index($request);
    }

    /**
     * Create product
     */
    public function create(): JsonResponse
    {
        // Return form data or empty resource
        return response()->json([
            'success' => true,
            'data' => []
        ]);
    }

    /**
     * Search products
     */
    public function search(Request $request): JsonResponse
    {
        $search = $request->get('q', '');
        $sanPhams = SanPham::where('ten', 'like', "%{$search}%")
            ->orWhere('username', 'like', "%{$search}%")
            ->where('trang_thai', true)
            ->limit(20)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $sanPhams
        ]);
    }

    /**
     * Delete product (alias for destroy)
     */
    public function xoaSP(int $id): JsonResponse
    {
        return $this->destroy($id);
    }

    /**
     * Export to Excel
     */
    public function xuatExcelSanPham(): JsonResponse
    {
        // TODO: Implement Excel export
        $sanPhams = SanPham::with('danhMuc')->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Export Excel chức năng sẽ được triển khai',
            'data' => $sanPhams
        ]);
    }

    /**
     * Check slug
     */
    public function checkSlug(Request $request): JsonResponse
    {
        $slug = $request->get('slug');
        $exists = SanPham::where('ten', $slug)->exists();
        
        return response()->json([
            'success' => true,
            'exists' => $exists
        ]);
    }

    /**
     * Change sale status
     */
    public function chuyenTrangThaiBan(int $id): JsonResponse
    {
        $sanPham = SanPham::find($id);
        
        if (!$sanPham) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại'
            ], 404);
        }

        $sanPham->trang_thai = !$sanPham->trang_thai;
        $sanPham->save();

        return response()->json([
            'success' => true,
            'message' => 'Trạng thái bán đã được cập nhật',
            'data' => $sanPham
        ]);
    }

    /**
     * Change featured status
     */
    public function chuyenNoiBat(int $id): JsonResponse
    {
        // TODO: Add noi_bat field if needed
        return response()->json([
            'success' => true,
            'message' => 'Chức năng sẽ được triển khai'
        ]);
    }

    /**
     * Change flash sale status
     */
    public function chuyenFlashSale(int $id): JsonResponse
    {
        // TODO: Add flash_sale field if needed
        return response()->json([
            'success' => true,
            'message' => 'Chức năng sẽ được triển khai'
        ]);
    }
}
