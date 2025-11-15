<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietTaiKhoan extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_tai_khoan';

    protected $fillable = [
        'san_pham_id',
        'username',
        'password',
        'thong_tin_bo_sung',
        'trang_thai',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'trang_thai' => 'boolean',
    ];

    /**
     * Quan hệ với sản phẩm
     */
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'san_pham_id');
    }
}

