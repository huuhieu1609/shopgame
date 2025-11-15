<?php

namespace Database\Seeders;

use App\Models\DanhMuc;
use Illuminate\Database\Seeder;

class DanhMucSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $danhMucs = [

            [
                'ten' => 'Acc Random',
                'mo_ta' => 'Danh mục tài khoản random',
                'trang_thai' => true,
            ],
            [
                'ten' => 'ACC REG LÀM SỰ KIỆN',
                'mo_ta' => 'Danh mục tài khoản reg làm sự kiện',
                'trang_thai' => true,
            ],
            [
                'ten' => 'Tài Khoản Garena',
                'mo_ta' => 'Danh mục tài khoản Garena',
                'trang_thai' => false,
            ],


        ];

        foreach ($danhMucs as $danhMuc) {
            DanhMuc::create($danhMuc);
        }
    }
}

