<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DanhMuc;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class DanhMucController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = DanhMuc::query();

        // Tìm kiếm
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('ten', 'like', "%{$search}%")
                  ->orWhere('mo_ta', 'like', "%{$search}%");
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
        $danhMucs = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $danhMucs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ten' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $danhMuc = DanhMuc::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Danh mục đã được tạo thành công',
            'data' => $danhMuc
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $danhMuc = DanhMuc::with('sanPhams')->find($id);

        if (!$danhMuc) {
            return response()->json([
                'success' => false,
                'message' => 'Danh mục không tồn tại'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $danhMuc
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $danhMuc = DanhMuc::find($id);

        if (!$danhMuc) {
            return response()->json([
                'success' => false,
                'message' => 'Danh mục không tồn tại'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'ten' => 'sometimes|required|string|max:255',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $danhMuc->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Danh mục đã được cập nhật thành công',
            'data' => $danhMuc
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $danhMuc = DanhMuc::find($id);

        if (!$danhMuc) {
            return response()->json([
                'success' => false,
                'message' => 'Danh mục không tồn tại'
            ], 404);
        }

        $danhMuc->delete();

        return response()->json([
            'success' => true,
            'message' => 'Danh mục đã được xóa thành công'
        ]);
    }

    /**
     * Get data for open/active categories
     */
    public function dataOpen(): JsonResponse
    {
        $danhMucs = DanhMuc::where('trang_thai', true)->get();
        
        return response()->json([
            'success' => true,
            'data' => $danhMucs
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
     * Check slug (if you have slug field)
     */
    public function checkSlug(Request $request): JsonResponse
    {
        $slug = $request->get('slug');
        $exists = DanhMuc::where('ten', $slug)->exists();
        
        return response()->json([
            'success' => true,
            'exists' => $exists
        ]);
    }

    /**
     * Export to Excel
     */
    public function xuatExcelDanhMuc(): JsonResponse
    {
        // TODO: Implement Excel export
        $danhMucs = DanhMuc::all();
        
        return response()->json([
            'success' => true,
            'message' => 'Export Excel chức năng sẽ được triển khai',
            'data' => $danhMucs
        ]);
    }

    /**
     * Change status
     */
    public function changeStatus(Request $request, int $id): JsonResponse
    {
        $danhMuc = DanhMuc::find($id);
        
        if (!$danhMuc) {
            return response()->json([
                'success' => false,
                'message' => 'Danh mục không tồn tại'
            ], 404);
        }

        $danhMuc->trang_thai = !$danhMuc->trang_thai;
        $danhMuc->save();

        return response()->json([
            'success' => true,
            'message' => 'Trạng thái đã được cập nhật',
            'data' => $danhMuc
        ]);
    }
}


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DanhMuc;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class DanhMucController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = DanhMuc::query();

        // Tìm kiếm
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('ten', 'like', "%{$search}%")
                  ->orWhere('mo_ta', 'like', "%{$search}%");
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
        $danhMucs = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $danhMucs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ten' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $danhMuc = DanhMuc::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Danh mục đã được tạo thành công',
            'data' => $danhMuc
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $danhMuc = DanhMuc::with('sanPhams')->find($id);

        if (!$danhMuc) {
            return response()->json([
                'success' => false,
                'message' => 'Danh mục không tồn tại'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $danhMuc
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $danhMuc = DanhMuc::find($id);

        if (!$danhMuc) {
            return response()->json([
                'success' => false,
                'message' => 'Danh mục không tồn tại'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'ten' => 'sometimes|required|string|max:255',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $danhMuc->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Danh mục đã được cập nhật thành công',
            'data' => $danhMuc
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $danhMuc = DanhMuc::find($id);

        if (!$danhMuc) {
            return response()->json([
                'success' => false,
                'message' => 'Danh mục không tồn tại'
            ], 404);
        }

        $danhMuc->delete();

        return response()->json([
            'success' => true,
            'message' => 'Danh mục đã được xóa thành công'
        ]);
    }

    /**
     * Get data for open/active categories
     */
    public function dataOpen(): JsonResponse
    {
        $danhMucs = DanhMuc::where('trang_thai', true)->get();
        
        return response()->json([
            'success' => true,
            'data' => $danhMucs
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
     * Check slug (if you have slug field)
     */
    public function checkSlug(Request $request): JsonResponse
    {
        $slug = $request->get('slug');
        $exists = DanhMuc::where('ten', $slug)->exists();
        
        return response()->json([
            'success' => true,
            'exists' => $exists
        ]);
    }

    /**
     * Export to Excel
     */
    public function xuatExcelDanhMuc(): JsonResponse
    {
        // TODO: Implement Excel export
        $danhMucs = DanhMuc::all();
        
        return response()->json([
            'success' => true,
            'message' => 'Export Excel chức năng sẽ được triển khai',
            'data' => $danhMucs
        ]);
    }

    /**
     * Change status
     */
    public function changeStatus(Request $request, int $id): JsonResponse
    {
        $danhMuc = DanhMuc::find($id);
        
        if (!$danhMuc) {
            return response()->json([
                'success' => false,
                'message' => 'Danh mục không tồn tại'
            ], 404);
        }

        $danhMuc->trang_thai = !$danhMuc->trang_thai;
        $danhMuc->save();

        return response()->json([
            'success' => true,
            'message' => 'Trạng thái đã được cập nhật',
            'data' => $danhMuc
        ]);
    }
}
