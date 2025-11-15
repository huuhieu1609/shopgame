<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\NhanVien;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Lấy admin ID từ header hoặc query parameter
        $adminId = $request->header('X-Admin-Id') ?? $request->get('admin_id');
        
        // Nếu có token, có thể verify token ở đây (hiện tại chưa implement token system)
        $token = $request->bearerToken();
        
        // Nếu có admin ID, kiểm tra xem admin có tồn tại và đang hoạt động không
        if ($adminId) {
            $admin = NhanVien::find($adminId);
            if ($admin && $admin->trang_thai) {
                // Gán admin vào request để sử dụng trong controller
                $request->merge(['authenticated_admin_id' => $adminId]);
                return $next($request);
            }
        }
        
        // Nếu không có admin ID hoặc token, trả về 401
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Vui lòng đăng nhập.'
        ], 401);
    }
}


