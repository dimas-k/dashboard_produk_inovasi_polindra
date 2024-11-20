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
        'anggota_penulis_lainnya',
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

    public function anggota1()
    {
        return $this->morphToMany(AnggotaKelompokKeahlian::class, 'anggota', 'penelitians_anggotas', 'penelitian_id', 'pene_id')
            ->withPivot('anggota_type');
    }

    public function anggota_penulis()
    {
        return $this->morphToMany(
            PenelitianAnggota::class,
            'anggota',
            'penelitians_anggotas',
            'penelitian_id',
            'anggota_id'
        );
    }
    // public function penulisKorespondensi()
    // {
    //     return $this->belongsTo(AnggotaKelompokKeahlian::class, 'penulis_korespondensi');
    // }
}
