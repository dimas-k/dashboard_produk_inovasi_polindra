<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'gambar',
        'inventor',
        'inventor_lainnya',
        'anggota_lainnya',
        'email_inventor', // Sesuaikan dengan nama kolom yang benar di database
        'lampiran',
        'tanggal_submit',
        'tanggal_granted',
        'status',
        'kbk_id',
    ];

    protected $casts = [
        'tanggal_granted' => 'date',
        'tanggal_submit' => 'date',
    ];

    // Relasi ke Kelompok Keahlian
    public function kelompokKeahlian()
    {
        return $this->belongsTo(KelompokKeahlian::class, 'kbk_id');
    }

    // Relasi ke anggota inventor (ProdukAnggota)

    public function anggota()
    {
        return $this->hasMany(ProdukAnggota::class, 'produk_id', 'id');
    }
    // public function anggota()
    // {
    //     return $this->hasMany(ProdukAnggota::class, 'produk_id');
    // }
    public function anggota1()
    {
        return $this->morphToMany(AnggotaKelompokKeahlian::class, 'anggota', 'produk_anggotas', 'produk_id', 'anggota_id')
            ->withPivot('anggota_type');
    }

    public function getAnggotaInventorLainnyaArrayAttribute()
    {
        return $this->anggota_inventor_lainnya ? explode(',', $this->anggota_inventor_lainnya) : [];
    }

    public function anggota_inventor()
    {
        return $this->morphToMany(
            ProdukAnggota::class,
            'anggota',
            'produks_anggotas',
            'produk_id',
            'anggota_id'
        );
    }
}
