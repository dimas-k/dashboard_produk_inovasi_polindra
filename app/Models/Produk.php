<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_produk', 'deskripsi', 'gambar', 'inventor', 
        'anggota_inventor', 'no_hp_inventor', 'lampiran', 
        'status'
    ];

    public function kelompokKeahlian()
    {
        return $this->hasOne(KelompokKeahlian::class,  'id');
    }
}
