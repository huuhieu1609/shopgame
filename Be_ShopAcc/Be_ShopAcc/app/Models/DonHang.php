<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonHang extends Model
{
    use HasFactory;

    protected $table = 'don_hang';

    protected $fillable = [
        'khach_hang_id',
        'ma_giam_gia_id',
        'tong_tien',
        'trang_thai',
        'ghi_chu',
    ];

    protected $casts = [
        'tong_tien' => 'decimal:2',
        'trang_thai' => 'string',
    ];

    /**
     * Quan hệ với khách hàng
     */
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id');
    }

    /**
     * Quan hệ với mã giảm giá
     */
    public function maGiamGia()
    {
        return $this->belongsTo(MaGiamGia::class, 'ma_giam_gia_id');
    }

    /**
     * Quan hệ với chi tiết đơn hàng
     */
    public function chiTietDonHangs()
    {
        return $this->hasMany(ChiTietDonHang::class, 'don_hang_id');
    }
}


