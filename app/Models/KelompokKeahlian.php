<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KelompokKeahlian extends Model
{
    use HasFactory;

    protected $table = 'kelompok_keahlians';

    public function user()
    {
        return $this->hasOne(User::class, 'kelompok_keahlian_id');
    }

    public function produk()
    {
        return $this->hasMany(Produk::class, 'produk_id');
    }
}
