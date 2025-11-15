<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed theo thứ tự để đảm bảo foreign key constraints
        $this->call([
            // Bảng cơ bản không phụ thuộc
            PhanQuyenSeeder::class,
            ChucNangSeeder::class,
            DanhMucSeeder::class,
            
            // Bảng phụ thuộc vào PhanQuyen
            NhanVienSeeder::class,
            
            // Bảng phụ thuộc vào DanhMuc
            SanPhamSeeder::class,
            
            // Bảng khách hàng (không phụ thuộc)
            KhachHangSeeder::class,
            
            // Bảng mã giảm giá (không phụ thuộc)
            MaGiamGiaSeeder::class,
            
            // Bảng phụ thuộc vào KhachHang và MaGiamGia
            DonHangSeeder::class,
            
            // Bảng phụ thuộc vào DonHang và SanPham
            ChiTietDonHangSeeder::class,
            
            // Bảng phụ thuộc vào KhachHang
            GiaoDichNapTienSeeder::class,
            GiaoDichViSeeder::class,
            
            // Bảng phụ thuộc vào SanPham
            ChiTietTaiKhoanSeeder::class,
            
            // Bảng phụ thuộc vào PhanQuyen và ChucNang
            PhanQuyenChiTietSeeder::class,
            
            // Bảng phụ thuộc vào NhanVien và KhachHang
            LogHeThongSeeder::class,
        ]);

        // Tạo user mặc định
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}

            
            // Bảng phụ thuộc vào DanhMuc
            SanPhamSeeder::class,
            
            // Bảng khách hàng (không phụ thuộc)
            KhachHangSeeder::class,
            
            // Bảng mã giảm giá (không phụ thuộc)
            MaGiamGiaSeeder::class,
            
            // Bảng phụ thuộc vào KhachHang và MaGiamGia
            DonHangSeeder::class,
            
            // Bảng phụ thuộc vào DonHang và SanPham
            ChiTietDonHangSeeder::class,
            
            // Bảng phụ thuộc vào KhachHang
            GiaoDichNapTienSeeder::class,
            GiaoDichViSeeder::class,
            
            // Bảng phụ thuộc vào SanPham
            ChiTietTaiKhoanSeeder::class,
            
            // Bảng phụ thuộc vào PhanQuyen và ChucNang
            PhanQuyenChiTietSeeder::class,
            
            // Bảng phụ thuộc vào NhanVien và KhachHang
            LogHeThongSeeder::class,
        ]);

        // Tạo user mặc định
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}