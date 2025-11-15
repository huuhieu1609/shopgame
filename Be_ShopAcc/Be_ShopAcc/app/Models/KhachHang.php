<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class KhachHang extends Model
{
    use HasFactory;

    protected $table = 'khach_hang';

    protected $fillable = [
        'ten',
        'tai_khoan',
        'email',
        'password',
        'sdt',
        'dia_chi',
        'so_du',
        'tong_chi',
        'trang_thai',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'so_du' => 'decimal:2',
        'tong_chi' => 'decimal:2',
        'trang_thai' => 'boolean',
    ];

    /**
     * Quan hệ với đơn hàng
     */
    public function donHangs()
    {
        return $this->hasMany(DonHang::class, 'khach_hang_id');
    }

    /**
     * Quan hệ với giao dịch nạp tiền
     */
    public function giaoDichNapTiens()
    {
        return $this->hasMany(GiaoDichNapTien::class, 'khach_hang_id');
    }

    /**
     * Quan hệ với giao dịch ví
     */
    public function giaoDichVis()
    {
        return $this->hasMany(GiaoDichVi::class, 'khach_hang_id');
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

