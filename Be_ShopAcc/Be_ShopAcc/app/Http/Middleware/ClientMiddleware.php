<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\KhachHang;

class ClientMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Lấy client ID từ header hoặc query parameter
        $clientId = $request->header('X-Client-Id') ?? $request->get('client_id');
        
        // Nếu có token, có thể verify token ở đây (hiện tại chưa implement token system)
        $token = $request->bearerToken();
        
        // Nếu có client ID, kiểm tra xem client có tồn tại và đang hoạt động không
        if ($clientId) {
            $client = KhachHang::find($clientId);
            if ($client && $client->trang_thai) {
                // Gán client vào request để sử dụng trong controller
                $request->merge(['authenticated_client_id' => $clientId]);
                return $next($request);
            }
        }
        
        // Nếu không có client ID hoặc token, trả về 401
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Vui lòng đăng nhập.'
        ], 401);
    }
}


