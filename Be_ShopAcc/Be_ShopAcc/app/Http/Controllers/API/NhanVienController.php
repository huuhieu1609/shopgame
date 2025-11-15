<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class NhanVienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = NhanVien::with('phanQuyen');

        // Tìm kiếm
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('ten', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('sdt', 'like', "%{$search}%");
            });
        }

        // Lọc theo phân quyền
        if ($request->has('phan_quyen_id')) {
            $query->where('phan_quyen_id', $request->get('phan_quyen_id'));
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
        $nhanViens = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $nhanViens
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ten' => 'required|string|max:255',
            'email' => 'required|email|unique:nhan_vien',
            'password' => 'required|string|min:6',
            'sdt' => 'nullable|string|max:20',
            'dia_chi' => 'nullable|string',
            'chuc_vu' => 'nullable|string|max:255',
            'phan_quyen_id' => 'required|exists:phan_quyen,id',
            'trang_thai' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $nhanVien = NhanVien::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Nhân viên đã được tạo thành công',
            'data' => $nhanVien->load('phanQuyen')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $nhanVien = NhanVien::with('phanQuyen')->find($id);

        if (!$nhanVien) {
            return response()->json([
                'success' => false,
                'message' => 'Nhân viên không tồn tại'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $nhanVien
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $nhanVien = NhanVien::find($id);

        if (!$nhanVien) {
            return response()->json([
                'success' => false,
                'message' => 'Nhân viên không tồn tại'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'ten' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:nhan_vien,email,' . $id,
            'password' => 'sometimes|string|min:6',
            'sdt' => 'nullable|string|max:20',
            'dia_chi' => 'nullable|string',
            'chuc_vu' => 'nullable|string|max:255',
            'phan_quyen_id' => 'sometimes|required|exists:phan_quyen,id',
            'trang_thai' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $nhanVien->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Nhân viên đã được cập nhật thành công',
            'data' => $nhanVien->load('phanQuyen')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $nhanVien = NhanVien::find($id);

        if (!$nhanVien) {
            return response()->json([
                'success' => false,
                'message' => 'Nhân viên không tồn tại'
            ], 404);
        }

        $nhanVien->delete();

        return response()->json([
            'success' => true,
            'message' => 'Nhân viên đã được xóa thành công'
        ]);
    }

    /**
     * Check if email exists
     */
    public function checkMail(Request $request): JsonResponse
    {
        $email = $request->get('email');
        $id = $request->get('id');
        
        $query = NhanVien::where('email', $email);
        if ($id) {
            $query->where('id', '!=', $id);
        }
        
        $exists = $query->exists();
        
        return response()->json([
            'success' => true,
            'exists' => $exists
        ]);
    }

    /**
     * Export to Excel
     */
    public function xuatExcelNhanVien(): JsonResponse
    {
        // TODO: Implement Excel export
        $nhanViens = NhanVien::with('phanQuyen')->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Export Excel chức năng sẽ được triển khai',
            'data' => $nhanViens
        ]);
    }

    /**
     * Change status
     */
    public function doiTrangThai(int $id): JsonResponse
    {
        $nhanVien = NhanVien::find($id);
        
        if (!$nhanVien) {
            return response()->json([
                'success' => false,
                'message' => 'Nhân viên không tồn tại'
            ], 404);
        }

        $nhanVien->trang_thai = !$nhanVien->trang_thai;
        $nhanVien->save();

        return response()->json([
            'success' => true,
            'message' => 'Trạng thái đã được cập nhật',
            'data' => $nhanVien
        ]);
    }

    /**
     * Đăng nhập admin
     */
    public function dangNhap(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->get('email');
        $password = $request->get('password');

        $nhanVien = NhanVien::where('email', $email)->first();

        if (!$nhanVien) {
            return response()->json([
                'success' => false,
                'message' => 'Email không tồn tại'
            ], 401);
        }

        if (!Hash::check($password, $nhanVien->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu không đúng'
            ], 401);
        }

        if (!$nhanVien->trang_thai) {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản đã bị khóa'
            ], 403);
        }

        // Load phân quyền
        $nhanVien->load('phanQuyen');

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'data' => $nhanVien,
            'token' => null // TODO: Generate token if using Sanctum/Passport
        ]);
    }
}


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class NhanVienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = NhanVien::with('phanQuyen');

        // Tìm kiếm
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('ten', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('sdt', 'like', "%{$search}%");
            });
        }

        // Lọc theo phân quyền
        if ($request->has('phan_quyen_id')) {
            $query->where('phan_quyen_id', $request->get('phan_quyen_id'));
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
        $nhanViens = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $nhanViens
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ten' => 'required|string|max:255',
            'email' => 'required|email|unique:nhan_vien',
            'password' => 'required|string|min:6',
            'sdt' => 'nullable|string|max:20',
            'dia_chi' => 'nullable|string',
            'chuc_vu' => 'nullable|string|max:255',
            'phan_quyen_id' => 'required|exists:phan_quyen,id',
            'trang_thai' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $nhanVien = NhanVien::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Nhân viên đã được tạo thành công',
            'data' => $nhanVien->load('phanQuyen')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $nhanVien = NhanVien::with('phanQuyen')->find($id);

        if (!$nhanVien) {
            return response()->json([
                'success' => false,
                'message' => 'Nhân viên không tồn tại'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $nhanVien
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $nhanVien = NhanVien::find($id);

        if (!$nhanVien) {
            return response()->json([
                'success' => false,
                'message' => 'Nhân viên không tồn tại'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'ten' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:nhan_vien,email,' . $id,
            'password' => 'sometimes|string|min:6',
            'sdt' => 'nullable|string|max:20',
            'dia_chi' => 'nullable|string',
            'chuc_vu' => 'nullable|string|max:255',
            'phan_quyen_id' => 'sometimes|required|exists:phan_quyen,id',
            'trang_thai' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $nhanVien->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Nhân viên đã được cập nhật thành công',
            'data' => $nhanVien->load('phanQuyen')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $nhanVien = NhanVien::find($id);

        if (!$nhanVien) {
            return response()->json([
                'success' => false,
                'message' => 'Nhân viên không tồn tại'
            ], 404);
        }

        $nhanVien->delete();

        return response()->json([
            'success' => true,
            'message' => 'Nhân viên đã được xóa thành công'
        ]);
    }

    /**
     * Check if email exists
     */
    public function checkMail(Request $request): JsonResponse
    {
        $email = $request->get('email');
        $id = $request->get('id');
        
        $query = NhanVien::where('email', $email);
        if ($id) {
            $query->where('id', '!=', $id);
        }
        
        $exists = $query->exists();
        
        return response()->json([
            'success' => true,
            'exists' => $exists
        ]);
    }

    /**
     * Export to Excel
     */
    public function xuatExcelNhanVien(): JsonResponse
    {
        // TODO: Implement Excel export
        $nhanViens = NhanVien::with('phanQuyen')->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Export Excel chức năng sẽ được triển khai',
            'data' => $nhanViens
        ]);
    }

    /**
     * Change status
     */
    public function doiTrangThai(int $id): JsonResponse
    {
        $nhanVien = NhanVien::find($id);
        
        if (!$nhanVien) {
            return response()->json([
                'success' => false,
                'message' => 'Nhân viên không tồn tại'
            ], 404);
        }

        $nhanVien->trang_thai = !$nhanVien->trang_thai;
        $nhanVien->save();

        return response()->json([
            'success' => true,
            'message' => 'Trạng thái đã được cập nhật',
            'data' => $nhanVien
        ]);
    }

    /**
     * Đăng nhập admin
     */
    public function dangNhap(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->get('email');
        $password = $request->get('password');

        $nhanVien = NhanVien::where('email', $email)->first();

        if (!$nhanVien) {
            return response()->json([
                'success' => false,
                'message' => 'Email không tồn tại'
            ], 401);
        }

        if (!Hash::check($password, $nhanVien->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu không đúng'
            ], 401);
        }

        if (!$nhanVien->trang_thai) {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản đã bị khóa'
            ], 403);
        }

        // Load phân quyền
        $nhanVien->load('phanQuyen');

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'data' => $nhanVien,
            'token' => null // TODO: Generate token if using Sanctum/Passport
        ]);
    }
}
