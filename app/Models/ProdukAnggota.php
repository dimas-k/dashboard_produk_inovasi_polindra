<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukAnggota extends Model
{
    use HasFactory;

    protected $table = 'produks_anggotas';
    protected $fillable = [
        'produk_id',
        'anggota_id'
    ];

    public function detail()
    {
        return $this->belongsTo(AnggotaKelompokKeahlian::class, 'anggota_id', '');
    }
}
