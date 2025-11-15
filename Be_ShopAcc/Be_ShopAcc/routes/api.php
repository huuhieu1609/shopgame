<?php

use App\Http\Controllers\API\DanhMucController;
use App\Http\Controllers\API\SanPhamController;
use App\Http\Controllers\API\KhachHangController;
use App\Http\Controllers\API\DonHangController;
use App\Http\Controllers\API\MaGiamGiaController;
use App\Http\Controllers\API\NhanVienController;
use App\Http\Controllers\API\ChiTietTaiKhoanController;
use App\Http\Controllers\API\GiaoDichNapTienController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// ==================== CLIENT PUBLIC ROUTES (Công khai - không cần đăng nhập) ====================
Route::prefix('client')->group(function () {
    // Sản phẩm (xem công khai)
    Route::prefix('san-pham')->group(function () {
        Route::get('/data-flash-sale', [SanPhamController::class, 'dataFlashSale']);
        Route::get('/data-noi-bat', [SanPhamController::class, 'dataNoiBat']);
        Route::get('/data-new', [SanPhamController::class, 'dataNew']);
        Route::get('/search', [SanPhamController::class, 'search']);
        Route::get('/{id}', [SanPhamController::class, 'show']);
    });

    // Danh mục (xem công khai)
    Route::prefix('danh-muc')->group(function () {
        Route::get('/data-open', [DanhMucController::class, 'dataOpen']);
        Route::get('/{id}', [DanhMucController::class, 'show']);
    });
});

// ==================== CLIENT AUTHENTICATED ROUTES (Cần đăng nhập) ====================
Route::prefix('client')->middleware(['client'])->group(function () {
    // Khách hàng tự quản lý thông tin
    Route::prefix('khach-hang')->group(function () {
        Route::get('/profile', [KhachHangController::class, 'profile']);
        Route::put('/profile', [KhachHangController::class, 'updateProfile']);
        Route::post('/dang-xuat', [KhachHangController::class, 'logout']);
        Route::post('/dang-xuat-all', [KhachHangController::class, 'logoutAll']);
    });

    // Đơn hàng của khách hàng
    Route::prefix('don-hang')->group(function () {
        Route::get('/', [DonHangController::class, 'myOrders']);
        Route::post('/', [DonHangController::class, 'store']);
        Route::get('/{id}', [DonHangController::class, 'showMyOrder']);
        Route::get('/{orderId}/san-pham/{productId}/tai-khoan', [DonHangController::class, 'getProductAccounts']);
    });

    // Giao dịch nạp tiền của khách hàng
    Route::prefix('giao-dich-nap-tien')->group(function () {
        Route::get('/', [GiaoDichNapTienController::class, 'myTransactions']);
        Route::post('/', [GiaoDichNapTienController::class, 'store']);
        Route::get('/{id}', [GiaoDichNapTienController::class, 'showMyTransaction']);
    });

    // Giao dịch ví của khách hàng
    Route::prefix('giao-dich-vi')->group(function () {
        Route::get('/', [GiaoDichNapTienController::class, 'myWalletTransactions']);
    });

    // Mã giảm giá (validate)
    Route::prefix('ma-giam-gia')->group(function () {
        Route::post('/validate', [MaGiamGiaController::class, 'validate']);
    });
});

// ==================== ADMIN ROUTES (Quản trị viên) ====================
Route::prefix('admin')->middleware(['admin'])->group(function () {
    // Quản lý danh mục
    Route::prefix('danh-muc')->group(function () {
        Route::get('/getData', [DanhMucController::class, 'getData']);
        Route::get('/data-open', [DanhMucController::class, 'dataOpen']);
        Route::post('/', [DanhMucController::class, 'store']);
        Route::delete('/{id}', [DanhMucController::class, 'destroy']);
        Route::post('/check-slug', [DanhMucController::class, 'checkSlug']);
        Route::get('/xuat-excel-danh-muc', [DanhMucController::class, 'xuatExcelDanhMuc']);
        Route::put('/{id}', [DanhMucController::class, 'update']);
        Route::patch('/{id}', [DanhMucController::class, 'update']);
        Route::post('/change-status/{id}', [DanhMucController::class, 'changeStatus']);
        Route::get('/{id}', [DanhMucController::class, 'show']);
    });

    // Quản lý sản phẩm
    Route::prefix('san-pham')->group(function () {
        Route::get('/data-flash-sale', [SanPhamController::class, 'dataFlashSale']);
        Route::get('/data-noi-bat', [SanPhamController::class, 'dataNoiBat']);
        Route::get('/data-new', [SanPhamController::class, 'dataNew']);
        Route::get('/getData', [SanPhamController::class, 'getData']);
        Route::get('/create', [SanPhamController::class, 'create']);
        Route::get('/search', [SanPhamController::class, 'search']);
        Route::delete('/xoaSP/{id}', [SanPhamController::class, 'xoaSP']);
        Route::put('/{id}', [SanPhamController::class, 'update']);
        Route::patch('/{id}', [SanPhamController::class, 'update']);
        Route::get('/xuat-excel-san-pham', [SanPhamController::class, 'xuatExcelSanPham']);
        Route::post('/check-slug', [SanPhamController::class, 'checkSlug']);
        Route::post('/chuyen-trang-thai-ban/{id}', [SanPhamController::class, 'chuyenTrangThaiBan']);
        Route::post('/chuyen-noi-bat/{id}', [SanPhamController::class, 'chuyenNoiBat']);
        Route::post('/chuyen-flash-sale/{id}', [SanPhamController::class, 'chuyenFlashSale']);
        Route::post('/', [SanPhamController::class, 'store']);
        Route::get('/{id}', [SanPhamController::class, 'show']);
    });

    // Quản lý khách hàng
    Route::prefix('khach-hang')->group(function () {
        Route::get('/', [KhachHangController::class, 'index']);
        Route::post('/', [KhachHangController::class, 'store']);
        Route::get('/{id}', [KhachHangController::class, 'show']);
        Route::put('/{id}', [KhachHangController::class, 'update']);
        Route::patch('/{id}', [KhachHangController::class, 'update']);
        Route::delete('/{id}', [KhachHangController::class, 'destroy']);
    });

    // Quản lý đơn hàng
    Route::prefix('don-hang')->group(function () {
        Route::get('/', [DonHangController::class, 'index']);
        Route::get('/{id}', [DonHangController::class, 'show']);
        Route::put('/{id}', [DonHangController::class, 'update']);
        Route::patch('/{id}', [DonHangController::class, 'update']);
        Route::delete('/{id}', [DonHangController::class, 'destroy']);
    });

    // Quản lý mã giảm giá
    Route::prefix('ma-giam-gia')->group(function () {
        Route::get('/', [MaGiamGiaController::class, 'index']);
        Route::post('/', [MaGiamGiaController::class, 'store']);
        Route::get('/{id}', [MaGiamGiaController::class, 'show']);
        Route::put('/{id}', [MaGiamGiaController::class, 'update']);
        Route::patch('/{id}', [MaGiamGiaController::class, 'update']);
        Route::delete('/{id}', [MaGiamGiaController::class, 'destroy']);
        Route::post('/validate', [MaGiamGiaController::class, 'validate']);
    });

    // Quản lý nhân viên (admin)
    Route::prefix('nhan-vien')->group(function () {
        Route::get('/data', [NhanVienController::class, 'index']);
        Route::post('/create', [NhanVienController::class, 'store']);
        Route::delete('/delete/{id}', [NhanVienController::class, 'destroy']);
        Route::post('/check-mail', [NhanVienController::class, 'checkMail']);
        Route::put('/update/{id}', [NhanVienController::class, 'update']);
        Route::patch('/update/{id}', [NhanVienController::class, 'update']);
        Route::get('/xuat-excel-nhan-vien', [NhanVienController::class, 'xuatExcelNhanVien']);
        Route::post('/doi-trang-thai/{id}', [NhanVienController::class, 'doiTrangThai']);
        Route::get('/{id}', [NhanVienController::class, 'show']);
    });

    // Quản lý chi tiết tài khoản
    Route::prefix('chi-tiet-tai-khoan')->group(function () {
        Route::get('/', [ChiTietTaiKhoanController::class, 'index']);
        Route::post('/', [ChiTietTaiKhoanController::class, 'store']);
        Route::post('/them-nhieu', [ChiTietTaiKhoanController::class, 'storeMultiple']);
        Route::get('/{id}', [ChiTietTaiKhoanController::class, 'show']);
        Route::put('/{id}', [ChiTietTaiKhoanController::class, 'update']);
        Route::patch('/{id}', [ChiTietTaiKhoanController::class, 'update']);
        Route::delete('/{id}', [ChiTietTaiKhoanController::class, 'destroy']);
    });

    // Quản lý giao dịch nạp tiền
    Route::prefix('giao-dich-nap-tien')->group(function () {
        Route::get('/', [GiaoDichNapTienController::class, 'index']);
        Route::get('/{id}', [GiaoDichNapTienController::class, 'show']);
        Route::put('/{id}', [GiaoDichNapTienController::class, 'update']);
        Route::patch('/{id}', [GiaoDichNapTienController::class, 'update']);
        Route::delete('/{id}', [GiaoDichNapTienController::class, 'destroy']);
    });

    // Giao dịch
    Route::prefix('giao-dich')->group(function () {
        Route::get('/', [GiaoDichNapTienController::class, 'index']);
    });
});

// ==================== PUBLIC ROUTES (Không cần authentication) ====================
// Đăng ký khách hàng
Route::post('/khach-hang/dang-ky', [KhachHangController::class, 'store']);

// Đăng nhập khách hàng
Route::post('/khach-hang/dang-nhap', [KhachHangController::class, 'dangNhap']);

// Quên mật khẩu khách hàng
Route::post('/khach-hang/quen-mat-khau', [KhachHangController::class, 'quenMatKhau']);

// Đăng nhập admin
Route::post('/admin/dang-nhap', [NhanVienController::class, 'dangNhap']);

// Xem sản phẩm công khai
Route::prefix('san-pham')->group(function () {
    Route::get('/data-flash-sale', [SanPhamController::class, 'dataFlashSale']);
    Route::get('/data-noi-bat', [SanPhamController::class, 'dataNoiBat']);
    Route::get('/data-new', [SanPhamController::class, 'dataNew']);
    Route::get('/search', [SanPhamController::class, 'search']);
    Route::get('/{id}', [SanPhamController::class, 'show']);
});

// Xem danh mục công khai
Route::prefix('danh-muc')->group(function () {
    Route::get('/data-open', [DanhMucController::class, 'dataOpen']);
    Route::get('/{id}', [DanhMucController::class, 'show']);
});