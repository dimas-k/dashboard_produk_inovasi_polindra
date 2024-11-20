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
        'anggota_id',
        'anggota_type', // Polimorfisme
    ];

    public function detail()
    {
        return $this->morphTo(null, 'anggota_type', 'anggota_id');
    }
    
    public function anggota()
    {
        return $this->morphTo('anggota', 'anggota_type', 'anggota_id');
    }

}
