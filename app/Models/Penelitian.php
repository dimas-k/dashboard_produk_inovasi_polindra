<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penelitian extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'deskripsi',
        'gambar',
        'penulis',
        'penulis_korespondensi',
        'anggota_penulis',
        'email_penulis',
        'lampiran',
        'status'
    ];

    protected $casts = [
        'tanggal_publikasi' => 'date',
    ];

    public function kelompokKeahlian()
    {
        return $this->belongsTo(KelompokKeahlian::class,  'kbk_id');
    }

    public function anggotaPenelitian()
    {
        return $this->hasMany(PenelitianAnggota::class, 'penelitian_id');
    }
    public function penulisKorespondensi()
    {
        return $this->belongsTo(AnggotaKelompokKeahlian::class, 'penulis_korespondensi');
    }
}
