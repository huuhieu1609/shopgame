<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ChiTietTaiKhoan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ChiTietTaiKhoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = ChiTietTaiKhoan::with('sanPham');

        // Lọc theo sản phẩm
        if ($request->has('san_pham_id')) {
            $query->where('san_pham_id', $request->get('san_pham_id'));
        }

        // Lọc theo trạng thái
        if ($request->has('trang_thai')) {
            $query->where('trang_thai', $request->get('trang_thai'));
        }

        // Phân trang
        $perPage = $request->get('per_page', 10);
        $chiTietTaiKhoans = $query->paginate($perPage);

        // Make password visible for admin
        $chiTietTaiKhoans->getCollection()->transform(function ($item) {
            return $item->makeVisible(['password']);
        });

        return response()->json([
            'success' => true,
            'data' => $chiTietTaiKhoans
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'san_pham_id' => 'required|exists:san_pham,id',
            'username' => 'required|string|max:255',
            'password' => 'required|string',
            'thong_tin_bo_sung' => 'nullable|string',
            'trang_thai' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $chiTietTaiKhoan = ChiTietTaiKhoan::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Chi tiết tài khoản đã được tạo thành công',
            'data' => $chiTietTaiKhoan->load('sanPham')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $chiTietTaiKhoan = ChiTietTaiKhoan::with('sanPham')->find($id);

        if (!$chiTietTaiKhoan) {
            return response()->json([
                'success' => false,
                'message' => 'Chi tiết tài khoản không tồn tại'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $chiTietTaiKhoan
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $chiTietTaiKhoan = ChiTietTaiKhoan::find($id);

        if (!$chiTietTaiKhoan) {
            return response()->json([
                'success' => false,
                'message' => 'Chi tiết tài khoản không tồn tại'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'san_pham_id' => 'sometimes|required|exists:san_pham,id',
            'username' => 'sometimes|required|string|max:255',
            'password' => 'sometimes|required|string',
            'thong_tin_bo_sung' => 'nullable|string',
            'trang_thai' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $chiTietTaiKhoan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Chi tiết tài khoản đã được cập nhật thành công',
            'data' => $chiTietTaiKhoan->load('sanPham')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $chiTietTaiKhoan = ChiTietTaiKhoan::find($id);

        if (!$chiTietTaiKhoan) {
            return response()->json([
                'success' => false,
                'message' => 'Chi tiết tài khoản không tồn tại'
            ], 404);
        }

        $chiTietTaiKhoan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Chi tiết tài khoản đã được xóa thành công'
        ]);
    }

    /**
     * Thêm nhiều tài khoản cùng lúc
     */
    public function storeMultiple(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'san_pham_id' => 'required|exists:san_pham,id',
            'accounts' => 'required|string', // Chuỗi text, mỗi dòng là 1 tài khoản
            'chi_them_chua_ban' => 'boolean', // Chỉ thêm tài khoản chưa bán
            'khong_loc_trung' => 'boolean', // Không lọc trùng nick
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $sanPhamId = $request->get('san_pham_id');
        $accountsText = $request->get('accounts');
        $chiThemChuaBan = $request->get('chi_them_chua_ban', false);
        $khongLocTrung = $request->get('khong_loc_trung', false);

        // Parse accounts từ textarea (mỗi dòng = 1 tài khoản)
        $lines = explode("\n", $accountsText);
        $accounts = [];
        $successCount = 0;
        $errorCount = 0;
        $skippedCount = 0;
        $errors = [];

        foreach ($lines as $lineIndex => $line) {
            $line = trim($line);
            if (empty($line)) {
                continue; // Bỏ qua dòng trống
            }

            // Parse format: username|password hoặc username password hoặc username|password|thong_tin_bo_sung
            $parts = [];
            if (strpos($line, '|') !== false) {
                $parts = explode('|', $line);
            } else {
                // Nếu không có dấu |, tách bằng khoảng trắng
                $parts = preg_split('/\s+/', $line, 3);
            }

            if (count($parts) < 2) {
                $errors[] = "Dòng " . ($lineIndex + 1) . ": Thiếu username hoặc password";
                $errorCount++;
                continue;
            }

            $username = trim($parts[0]);
            $password = trim($parts[1]);
            $thongTinBoSung = isset($parts[2]) ? trim($parts[2]) : null;

            if (empty($username) || empty($password)) {
                $errors[] = "Dòng " . ($lineIndex + 1) . ": Username hoặc password không được để trống";
                $errorCount++;
                continue;
            }

            // Kiểm tra trùng lặp nếu không bỏ qua
            if (!$khongLocTrung) {
                $existing = ChiTietTaiKhoan::where('san_pham_id', $sanPhamId)
                    ->where('username', $username)
                    ->first();

                if ($existing) {
                    $skippedCount++;
                    continue; // Bỏ qua tài khoản trùng
                }
            }

            // Kiểm tra chỉ thêm tài khoản chưa bán (trạng thái = true)
            if ($chiThemChuaBan) {
                $existing = ChiTietTaiKhoan::where('san_pham_id', $sanPhamId)
                    ->where('username', $username)
                    ->where('trang_thai', true)
                    ->first();

                if ($existing) {
                    $skippedCount++;
                    continue; // Bỏ qua tài khoản đã bán
                }
            }

            // Tạo tài khoản
            try {
                ChiTietTaiKhoan::create([
                    'san_pham_id' => $sanPhamId,
                    'username' => $username,
                    'password' => $password,
                    'thong_tin_bo_sung' => $thongTinBoSung,
                    'trang_thai' => true,
                ]);
                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Dòng " . ($lineIndex + 1) . ": " . $e->getMessage();
                $errorCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Đã thêm {$successCount} tài khoản thành công",
            'data' => [
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'skipped_count' => $skippedCount,
                'errors' => $errors,
            ]
        ], 201);
    }
}


        ]);
    }

    /**
     * Thêm nhiều tài khoản cùng lúc
     */
    public function storeMultiple(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'san_pham_id' => 'required|exists:san_pham,id',
            'accounts' => 'required|string', // Chuỗi text, mỗi dòng là 1 tài khoản
            'chi_them_chua_ban' => 'boolean', // Chỉ thêm tài khoản chưa bán
            'khong_loc_trung' => 'boolean', // Không lọc trùng nick
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $sanPhamId = $request->get('san_pham_id');
        $accountsText = $request->get('accounts');
        $chiThemChuaBan = $request->get('chi_them_chua_ban', false);
        $khongLocTrung = $request->get('khong_loc_trung', false);

        // Parse accounts từ textarea (mỗi dòng = 1 tài khoản)
        $lines = explode("\n", $accountsText);
        $accounts = [];
        $successCount = 0;
        $errorCount = 0;
        $skippedCount = 0;
        $errors = [];

        foreach ($lines as $lineIndex => $line) {
            $line = trim($line);
            if (empty($line)) {
                continue; // Bỏ qua dòng trống
            }

            // Parse format: username|password hoặc username password hoặc username|password|thong_tin_bo_sung
            $parts = [];
            if (strpos($line, '|') !== false) {
                $parts = explode('|', $line);
            } else {
                // Nếu không có dấu |, tách bằng khoảng trắng
                $parts = preg_split('/\s+/', $line, 3);
            }

            if (count($parts) < 2) {
                $errors[] = "Dòng " . ($lineIndex + 1) . ": Thiếu username hoặc password";
                $errorCount++;
                continue;
            }

            $username = trim($parts[0]);
            $password = trim($parts[1]);
            $thongTinBoSung = isset($parts[2]) ? trim($parts[2]) : null;

            if (empty($username) || empty($password)) {
                $errors[] = "Dòng " . ($lineIndex + 1) . ": Username hoặc password không được để trống";
                $errorCount++;
                continue;
            }

            // Kiểm tra trùng lặp nếu không bỏ qua
            if (!$khongLocTrung) {
                $existing = ChiTietTaiKhoan::where('san_pham_id', $sanPhamId)
                    ->where('username', $username)
                    ->first();

                if ($existing) {
                    $skippedCount++;
                    continue; // Bỏ qua tài khoản trùng
                }
            }

            // Kiểm tra chỉ thêm tài khoản chưa bán (trạng thái = true)
            if ($chiThemChuaBan) {
                $existing = ChiTietTaiKhoan::where('san_pham_id', $sanPhamId)
                    ->where('username', $username)
                    ->where('trang_thai', true)
                    ->first();

                if ($existing) {
                    $skippedCount++;
                    continue; // Bỏ qua tài khoản đã bán
                }
            }

            // Tạo tài khoản
            try {
                ChiTietTaiKhoan::create([
                    'san_pham_id' => $sanPhamId,
                    'username' => $username,
                    'password' => $password,
                    'thong_tin_bo_sung' => $thongTinBoSung,
                    'trang_thai' => true,
                ]);
                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Dòng " . ($lineIndex + 1) . ": " . $e->getMessage();
                $errorCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Đã thêm {$successCount} tài khoản thành công",
            'data' => [
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'skipped_count' => $skippedCount,
                'errors' => $errors,
            ]
        ], 201);
    }
}
