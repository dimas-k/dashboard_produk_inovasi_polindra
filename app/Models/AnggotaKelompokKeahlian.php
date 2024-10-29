<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaKelompokKeahlian extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'jabatan',
    ];

    public function produk()
    {
        return $this->belongsToMany(Produk::class, 'produks_anggotas', 'anggota_id', 'produk_id');
    }
    public function kelompokKeahlian()
    {
        return $this->belongsTo(KelompokKeahlian::class, 'kbk_id');
    }
}
