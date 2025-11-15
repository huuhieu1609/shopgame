<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChucNang extends Model
{
    use HasFactory;

    protected $table = 'chuc_nang';

    protected $fillable = [
        'ten',
        'mo_ta',
        'duong_dan',
        'icon',
        'trang_thai',
    ];

    protected $casts = [
        'trang_thai' => 'boolean',
    ];

    /**
     * Quan hệ với phân quyền chi tiết
     */
    public function phanQuyenChiTiets()
    {
        return $this->hasMany(PhanQuyenChiTiet::class, 'chuc_nang_id');
    }
}

