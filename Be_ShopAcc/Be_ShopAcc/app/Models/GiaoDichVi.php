<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiaoDichVi extends Model
{
    use HasFactory;

    protected $table = 'giao_dich_vi';

    protected $fillable = [
        'khach_hang_id',
        'loai',
        'so_tien',
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


