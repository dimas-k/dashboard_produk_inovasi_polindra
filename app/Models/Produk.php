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
        'anggota_inventor',
        'email_penulis',
        'lampiran',
        'status'
    ];

    public function kelompokKeahlian()
    {
        return $this->belongsTo(KelompokKeahlian::class,  'kbk_id');
    }

    public function anggota()
    {
        return $this->hasMany(ProdukAnggota::class, 'produk_id', 'id');
    }
}
