<?php

namespace Database\Seeders;

use App\Models\ChiTietTaiKhoan;
use App\Models\SanPham;
use Illuminate\Database\Seeder;

class ChiTietTaiKhoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy tất cả sản phẩm
        $sanPhams = SanPham::all();

        foreach ($sanPhams as $sanPham) {
            // Tạo 10-20 tài khoản mẫu cho mỗi sản phẩm
            $soLuong = rand(10, 20);
            
            for ($i = 1; $i <= $soLuong; $i++) {
                ChiTietTaiKhoan::create([
                    'san_pham_id' => $sanPham->id,
                    'username' => $this->generateUsername($sanPham->ten, $i),
                    'password' => $this->generatePassword(),
                    'thong_tin_bo_sung' => $this->generateThongTinBoSung(),
                    'trang_thai' => true, // Tài khoản còn hàng
                ]);
            }
        }
    }

    /**
     * Tạo username mẫu
     */
    private function generateUsername($tenSanPham, $index): string
    {
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $tenSanPham), 0, 5));
        $random = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
        return $prefix . $random . $index;
    }

    /**
     * Tạo password mẫu
     */
    private function generatePassword(): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        return substr(str_shuffle($chars), 0, rand(8, 12));
    }

    /**
     * Tạo thông tin bổ sung mẫu
     */
    private function generateThongTinBoSung(): ?string
    {
        $thongTin = [
            'Email: test' . rand(100, 999) . '@gmail.com',
            'SĐT: 0' . rand(900000000, 999999999),
            'UID: ' . rand(100000, 999999),
            null,
        ];
        return $thongTin[array_rand($thongTin)];
    }
}

