<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'nama_lengkap',
        'email',
        'username',
        'nip',
        'role',
        'jabatan',
        'kbk_id', // Penyesuaian nama kolom
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relasi ke Kelompok Keahlian
    public function kelompokKeahlian()
    {
        return $this->belongsTo(KelompokKeahlian::class, 'kbk_id');
    }

    // Relasi ke Produk
    public function produk()
    {
        return $this->hasMany(Produk::class, 'kbk_id', 'kbk_id');
    }

    public function penelitian()
    {
        return $this->hasMany(Penelitian::class, 'kbk_id', 'kbk_id');
    }
}

