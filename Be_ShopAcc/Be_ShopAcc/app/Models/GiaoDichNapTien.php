<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiaoDichNapTien extends Model
{
    use HasFactory;

    protected $table = 'giao_dich_nap_tien';

    protected $fillable = [
        'khach_hang_id',
        'so_tien',
        'phuong_thuc',
        'trang_thai',
        'ghi_chu',
        'ma_giao_dich',
    ];

    protected $casts = [
        'so_tien' => 'decimal:2',
        'trang_thai' => 'string',
    ];

    /**
     * Quan hệ với khách hàng
     */
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id');
    }
}


