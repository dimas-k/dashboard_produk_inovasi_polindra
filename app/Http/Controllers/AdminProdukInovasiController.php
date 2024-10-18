<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use App\Models\KelompokKeahlian;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;


class AdminProdukInovasiController extends Controller
{
    public function pageProduk($id)
    {

        // $produk = KelompokKeahlian::with('produk')->paginate(10);

        $kbk_navigasi = DB::table('kelompok_keahlians')
        ->select(
            'kelompok_keahlians.id',
            'kelompok_keahlians.nama_kbk'
        )
        ->get();

        $kbk_navigasi1 = DB::table('kelompok_keahlians')
        ->select(
            'kelompok_keahlians.id',
            'kelompok_keahlians.nama_kbk'
        )
        ->where('kelompok_keahlians.id','=',$id)
        ->first();

        $data_produk = DB::table('users')
        ->join('kelompok_keahlians', 'users.kbk_id', '=', 'kelompok_keahlians.id')
        ->join('produks', 'produks.kbk_id', '=', 'kelompok_keahlians.id')
        ->select(
            'produks.id as id_produks',
            'users.nama_lengkap', 
            'users.nip', 
            'users.jabatan', 
            'users.no_hp', 
            'users.email', 
            'kelompok_keahlians.nama_kbk', 
            'kelompok_keahlians.jurusan', 
            'produks.nama_produk', 
            'produks.deskripsi as produk_deskripsi',
            'produks.gambar',
            'produks.inventor',
            'produks.anggota_inventor',
            'produks.email_inventor',
            'produks.lampiran',
            'produks.status'
        )
        ->where('kelompok_keahlians.id','=',$id)
        ->paginate(10);
        // ->get();
        // dd($data_produk);
        
        return view('admin.produk.index', compact('kbk_navigasi','kbk_navigasi1', 'data_produk'));
    }

    public function ShowPageProduk($id)
    {
        $produk = Produk::with('KelompokKeahlian')->findOrFail($id);
        $kbk_navigasi = DB::table('kelompok_keahlians')
        ->select(
            'kelompok_keahlians.id',
            'kelompok_keahlians.nama_kbk'
        )
        ->get();
        return view('admin.produk.show.index' , compact('produk','kbk_navigasi'));
    }
    
}
