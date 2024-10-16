<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penelitian extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul', 'deskripsi', 'gambar', 'penulis', 
        'anggota_penulis', 'email_penulis', 'lampiran', 
        'status'
    ];

    public function kelompokKeahlian()
    {
        return $this->belongsTo(KelompokKeahlian::class,  'kbk_id');
    }
}
