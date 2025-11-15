<?php

namespace Database\Seeders;

use App\Models\KhachHang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KhachHangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $khachHangs = [
            [
                'ten' => 'Nguyễn Văn A',
                'tai_khoan' => 'NguyễnVănA',
                'email' => 'nguyenvana@email.com',
                'password' => Hash::make('password123'),
                'sdt' => '0912345678',
                'dia_chi' => '123 Đường ABC, Quận 1, TP.HCM',
                'so_du' => 500000,
                'tong_chi' => 1500000,
                'trang_thai' => true,
            ],
            [
                'ten' => 'Trần Thị B',
                'tai_khoan' => 'TrầnThịB',
                'email' => 'tranthib@email.com',
                'password' => Hash::make('password123'),
                'sdt' => '0923456789',
                'dia_chi' => '456 Đường XYZ, Quận 2, TP.HCM',
                'so_du' => 250000,
                'tong_chi' => 800000,
                'trang_thai' => true,
            ],
            [
                'ten' => 'Lê Văn C',
                'tai_khoan' => 'LêVănC',
                'email' => 'levanc@email.com',
                'password' => Hash::make('password123'),
                'sdt' => '0934567890',
                'dia_chi' => '789 Đường DEF, Quận 3, TP.HCM',
                'so_du' => 0,
                'tong_chi' => 500000,
                'trang_thai' => false,
            ],
            [
                'ten' => 'Phạm Thị D',
                'tai_khoan' => 'PhạmThịD',
                'email' => 'phamthid@email.com',
                'password' => Hash::make('password123'),
                'sdt' => '0945678901',
                'dia_chi' => '321 Đường GHI, Quận 4, TP.HCM',
                'so_du' => 1000000,
                'tong_chi' => 2500000,
                'trang_thai' => true,
            ],
            [
                'ten' => 'Hoàng Văn E',
                'tai_khoan' => 'HoàngVănE',
                'email' => 'hoangvane@email.com',
                'password' => Hash::make('password123'),
                'sdt' => '0956789012',
                'dia_chi' => '654 Đường JKL, Quận 5, TP.HCM',
                'so_du' => 75000,
                'tong_chi' => 300000,
                'trang_thai' => true,
            ],
            [
                'ten' => 'Võ Thị F',
                'tai_khoan' => 'VõThịF',
                'email' => 'vothif@email.com',
                'password' => Hash::make('password123'),
                'sdt' => '0967890123',
                'dia_chi' => '987 Đường MNO, Quận 6, TP.HCM',
                'so_du' => 300000,
                'tong_chi' => 600000,
                'trang_thai' => true,
            ],
            [
                'ten' => 'Đỗ Văn G',
                'tai_khoan' => 'ĐỗVănG',
                'email' => 'dovang@email.com',
                'password' => Hash::make('password123'),
                'sdt' => '0978901234',
                'dia_chi' => '147 Đường PQR, Quận 7, TP.HCM',
                'so_du' => 450000,
                'tong_chi' => 1200000,
                'trang_thai' => true,
            ],
        ];

        foreach ($khachHangs as $khachHang) {
            KhachHang::create($khachHang);
        }
    }
}
