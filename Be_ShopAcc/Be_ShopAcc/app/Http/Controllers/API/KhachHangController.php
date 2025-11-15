<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\KhachHang;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class KhachHangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = KhachHang::query();

        // Tìm kiếm
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('ten', 'like', "%{$search}%")
                  ->orWhere('tai_khoan', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('sdt', 'like', "%{$search}%");
            });
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
        $khachHangs = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $khachHangs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ten' => 'required|string|max:255',
            'tai_khoan' => 'required|string|max:255|unique:khach_hang',
            'email' => 'required|email|unique:khach_hang',
            'password' => 'required|string|min:6',
            'sdt' => 'nullable|string|max:20',
            'dia_chi' => 'nullable|string',
            'so_du' => 'numeric|min:0',
            'tong_chi' => 'numeric|min:0',
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
        $khachHang = KhachHang::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Khách hàng đã được tạo thành công',
            'data' => $khachHang
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $khachHang = KhachHang::with(['donHangs', 'giaoDichNapTiens', 'giaoDichVis'])->find($id);

        if (!$khachHang) {
            return response()->json([
                'success' => false,
                'message' => 'Khách hàng không tồn tại'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $khachHang
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $khachHang = KhachHang::find($id);

        if (!$khachHang) {
            return response()->json([
                'success' => false,
                'message' => 'Khách hàng không tồn tại'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'ten' => 'sometimes|required|string|max:255',
            'tai_khoan' => 'sometimes|required|string|max:255|unique:khach_hang,tai_khoan,' . $id,
            'email' => 'sometimes|required|email|unique:khach_hang,email,' . $id,
            'password' => 'sometimes|string|min:6',
            'sdt' => 'nullable|string|max:20',
            'dia_chi' => 'nullable|string',
            'so_du' => 'numeric|min:0',
            'tong_chi' => 'numeric|min:0',
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

        $khachHang->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Khách hàng đã được cập nhật thành công',
            'data' => $khachHang
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $khachHang = KhachHang::find($id);

        if (!$khachHang) {
            return response()->json([
                'success' => false,
                'message' => 'Khách hàng không tồn tại'
            ], 404);
        }

        $khachHang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Khách hàng đã được xóa thành công'
        ]);
    }

    /**
     * Login customer
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

        // Tìm khách hàng theo email hoặc tài khoản
        $khachHang = KhachHang::where(function($query) use ($email) {
            $query->where('email', $email)
                  ->orWhere('tai_khoan', $email);
        })->first();

        if (!$khachHang) {
            return response()->json([
                'success' => false,
                'message' => 'Email hoặc tài khoản không tồn tại'
            ], 401);
        }

        // Kiểm tra mật khẩu
        if (!Hash::check($password, $khachHang->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu không đúng'
            ], 401);
        }

        // Kiểm tra trạng thái tài khoản
        if (!$khachHang->trang_thai) {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản đã bị khóa'
            ], 403);
        }

        // Tạo token (nếu sử dụng Sanctum/Passport)
        // Tạm thời trả về thông tin khách hàng
        // TODO: Implement token generation nếu cần

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'data' => $khachHang,
            'token' => null // TODO: Generate token if using Sanctum/Passport
        ]);
    }

    /**
     * Logout customer
     */
    public function logout(Request $request): JsonResponse
    {
        // TODO: Implement logout logic (revoke tokens, clear sessions, etc.)
        
        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công'
        ]);
    }

    /**
     * Logout from all devices
     */
    public function logoutAll(Request $request): JsonResponse
    {
        // TODO: Implement logout from all devices logic
        
        return response()->json([
            'success' => true,
            'message' => 'Đã đăng xuất khỏi tất cả thiết bị'
        ]);
    }

    /**
     * Get client profile
     */
    public function profile(Request $request): JsonResponse
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
        
        $khachHang = KhachHang::with(['donHangs', 'giaoDichNapTiens', 'giaoDichVis'])
            ->find($clientId);

        if (!$khachHang) {
            return response()->json([
                'success' => false,
                'message' => 'Khách hàng không tồn tại'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $khachHang
        ]);
    }

    /**
     * Update client profile
     */
    public function updateProfile(Request $request): JsonResponse
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
        
        $khachHang = KhachHang::find($clientId);

        if (!$khachHang) {
            return response()->json([
                'success' => false,
                'message' => 'Khách hàng không tồn tại'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'ten' => 'sometimes|required|string|max:255',
            'sdt' => 'nullable|string|max:20',
            'dia_chi' => 'nullable|string',
            'password' => 'sometimes|string|min:6',
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

        $khachHang->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Thông tin đã được cập nhật thành công',
            'data' => $khachHang
        ]);
    }

    /**
     * Quên mật khẩu - Gửi email reset password
     */
    public function quenMatKhau(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->get('email');

        // Tìm khách hàng theo email hoặc tài khoản
        $khachHang = KhachHang::where(function($query) use ($email) {
            $query->where('email', $email)
                  ->orWhere('tai_khoan', $email);
        })->first();

        if (!$khachHang) {
            return response()->json([
                'success' => false,
                'message' => 'Email hoặc tài khoản không tồn tại trong hệ thống'
            ], 404);
        }

        // Kiểm tra trạng thái tài khoản
        if (!$khachHang->trang_thai) {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản đã bị khóa. Vui lòng liên hệ admin để được hỗ trợ.'
            ], 403);
        }

        // TODO: Implement email sending logic here
        // For now, just return success message
        // In production, you should:
        // 1. Generate reset token
        // 2. Save token to database with expiry time
        // 3. Send email with reset link
        // 4. Return success message

        return response()->json([
            'success' => true,
            'message' => 'Chúng tôi đã gửi hướng dẫn đặt lại mật khẩu đến email của bạn. Vui lòng kiểm tra hộp thư!'
        ]);
    }
}
