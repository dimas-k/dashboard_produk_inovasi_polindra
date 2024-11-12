<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenelitianAnggota extends Model
{
    use HasFactory;

    protected $table = 'penelitians_anggotas';

    protected $fillable = [
        'penelitian_id',
        'anggota_id'
    ];

    public function detailAnggota()
    {
        return $this->belongsTo(AnggotaKelompokKeahlian::class, 'anggota_id');
    }
    
}
