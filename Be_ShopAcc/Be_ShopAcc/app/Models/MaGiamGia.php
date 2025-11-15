<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaGiamGia extends Model
{
    use HasFactory;

    protected $table = 'ma_giam_gia';

    protected $fillable = [
        'ma',
        'gia_tri',
        'loai',
        'so_tien_toi_thieu',
        'so_tien_giam_toi_da',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'so_luong',
        'da_su_dung',
        'trang_thai',
    ];

    protected $casts = [
        'gia_tri' => 'decimal:2',
        'so_tien_toi_thieu' => 'decimal:2',
        'so_tien_giam_toi_da' => 'decimal:2',
        'so_luong' => 'integer',
        'da_su_dung' => 'integer',
        'ngay_bat_dau' => 'datetime',
        'ngay_ket_thuc' => 'datetime',
        'trang_thai' => 'boolean',
    ];

    /**
     * Quan hệ với đơn hàng
     */
    public function donHangs()
    {
        return $this->hasMany(DonHang::class, 'ma_giam_gia_id');
    }
}


