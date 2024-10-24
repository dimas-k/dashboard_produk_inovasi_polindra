<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Produk;
use App\Models\Penelitian;
use Illuminate\Http\Request;
use App\Models\KelompokKeahlian;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $kbk = KelompokKeahlian::all();
        $jumlah_kbk = KelompokKeahlian::all()->count();
        $jumlah_produk = Produk::where('status', 'Tervalidasi')->count();
        $jumlah_pusat_penelitian = Penelitian::where('status', 'Tervalidasi')->count();
        $produk = Produk::where('status', 'Tervalidasi')->get();
        $pusat_penelitian = Penelitian::where('status', 'Tervalidasi')->get();
        // dd($jumlah_kbk);
        return view('dashboard.index', compact('kbk','jumlah_kbk','jumlah_produk','jumlah_pusat_penelitian', 'produk', 'pusat_penelitian'));
    }

    public function contact()
    {
        $kbk = KelompokKeahlian::all();
        return view('dashboard.contact.index', compact('kbk'));
    }

    public function penelitian($id)
    {
        $kbk = KelompokKeahlian::all();
        $kbk_nama = KelompokKeahlian::find($id);
        $kkbk = DB::table('users')
            ->join('kelompok_keahlians', 'users.kbk_id', '=', 'kelompok_keahlians.id')
            ->select(
                'users.id',
                'users.nama_lengkap',
                'users.pas_foto',
                'kelompok_keahlians.nama_kbk',
                'kelompok_keahlians.deskripsi'
            )
            ->where('users.kbk_id','=',$id)
            ->first();

        // dd($kelompokKeahlian);
        // Mengambil dan menampilkan nama_lengkap dan nama_kbk
        // foreach ($kkbk as $item) {
        //     $namaLengkap = $item->nama_lengkap;  // Mengambil nama_lengkap
        //     $namaKBK = $item->nama_kbk;    // Mengambil nama_kbk
        //     echo "Nama: " . $namaLengkap . ", KBK: " . $namaKBK . "\n";
        // }

        // $data_produk = Produk::where('kbk_id', $id)->get();
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
        ->paginate(5);

        // dd($data_produk);
        
        return view('dashboard.penelitian.index', compact('kbk', 'kbk_nama', 'kkbk','data_produk'));
    }

    public function detail_Penelitian()
    {
        $kbk = KelompokKeahlian::all();
        return view('dashboard.detail-penelitian.index', compact('kbk'));
    }
}
