<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class NhanVien extends Model
{
    use HasFactory;

    protected $table = 'nhan_vien';

    protected $fillable = [
        'ten',
        'email',
        'password',
        'sdt',
        'chuc_vu',
        'phan_quyen_id',
        'trang_thai',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'trang_thai' => 'boolean',
    ];

    /**
     * Quan hệ với phân quyền
     */
    public function phanQuyen()
    {
        return $this->belongsTo(PhanQuyen::class, 'phan_quyen_id');
    }

    /**
     * Hash password khi set (chỉ hash nếu chưa được hash)
     */
    public function setPasswordAttribute($value)
    {
        // Chỉ hash nếu giá trị chưa được hash (không bắt đầu bằng $2y$)
        if (!empty($value) && !str_starts_with($value, '$2y$')) {
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }
}


