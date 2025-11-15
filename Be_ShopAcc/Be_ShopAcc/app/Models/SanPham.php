<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanPham extends Model
{
    use HasFactory;

    protected $table = 'san_pham';

    protected $fillable = [
        'ten',
        'username',
        'danh_muc_id',
        'mo_ta',
        'noi_dung',
        'gia',
        'so_luong',
        'co',
        'check_live',
        'trang_thai',
    ];

    protected $casts = [
        'gia' => 'decimal:2',
        'so_luong' => 'integer',
        'check_live' => 'boolean',
        'trang_thai' => 'boolean',
    ];

    /**
     * Quan hệ với danh mục
     */
    public function danhMuc()
    {
        return $this->belongsTo(DanhMuc::class, 'danh_muc_id');
    }

    /**
     * Quan hệ với chi tiết tài khoản
     */
    public function chiTietTaiKhoans()
    {
        return $this->hasMany(ChiTietTaiKhoan::class, 'san_pham_id');
    }

    /**
     * Quan hệ với chi tiết đơn hàng
     */
    public function chiTietDonHangs()
    {
        return $this->hasMany(ChiTietDonHang::class, 'san_pham_id');
    }
}


