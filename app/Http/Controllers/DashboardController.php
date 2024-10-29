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

        $produk_terbaru = Produk::where('status', 'Tervalidasi')->latest()->take(10)->get();
        $penelitian_terbaru = Penelitian::where('status', 'Tervalidasi')->latest()->take(10)->get();
        // dd($jumlah_kbk);
        return view('dashboard.index', compact('kbk', 'jumlah_kbk', 'jumlah_produk', 'jumlah_pusat_penelitian', 'produk', 'pusat_penelitian', 'produk_terbaru', 'penelitian_terbaru'));
    }

    public function contact()
    {
        $kbk = KelompokKeahlian::all();
        return view('dashboard.contact.index', compact('kbk'));
    }

    public function penelitian($nama_kbk)
    {
        $kbk = KelompokKeahlian::all();
        $kbk_nama = KelompokKeahlian::find($nama_kbk);
        $kkbk = DB::table('users')
            ->join('kelompok_keahlians', 'users.kbk_id', '=', 'kelompok_keahlians.id')

            ->select(
                'users.id',
                'users.nama_lengkap',
                'users.pas_foto',
                'users.email',
                'kelompok_keahlians.nama_kbk',
                'kelompok_keahlians.deskripsi',
            )
            ->where('kelompok_keahlians.nama_kbk', '=', $nama_kbk)
            ->first();
        $anggota_kbk = DB::table('anggota_kelompok_keahlians')
            ->join('kelompok_keahlians', 'anggota_kelompok_keahlians.kbk_id', '=', 'kelompok_keahlians.id')
            ->where('kelompok_keahlians.nama_kbk', '=', $nama_kbk)
            ->select(
                'anggota_kelompok_keahlians.id',
                'anggota_kelompok_keahlians.nama_lengkap'
            )->get();

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
                'produks.nama_produk as nama_produks',
                'produks.deskripsi as produk_deskripsi',
                'produks.gambar',
                'produks.inventor',
                'produks.anggota_inventor',
                'produks.email_inventor',
                'produks.lampiran',
            )->where('kelompok_keahlians.nama_kbk', '=', $nama_kbk)->where('status', 'Tervalidasi')->latest('produks.created_at')->get();

        $data_penelitian = DB::table('users')
            ->join('kelompok_keahlians', 'users.kbk_id', '=', 'kelompok_keahlians.id')
            ->join('penelitians', 'penelitians.kbk_id', '=', 'kelompok_keahlians.id')
            ->select(
                'penelitians.id as id_penelitian',
                'users.nama_lengkap',
                'users.nip',
                'users.jabatan',
                'users.no_hp',
                'users.email',
                'kelompok_keahlians.nama_kbk',
                'kelompok_keahlians.jurusan',
                'penelitians.judul',
                'penelitians.abstrak',
                'penelitians.gambar',
                'penelitians.penulis',
                'penelitians.anggota_penulis',
                'penelitians.email_penulis',
                'penelitians.lampiran',
            )->where('kelompok_keahlians.nama_kbk', '=', $nama_kbk)->where('status', 'Tervalidasi')->latest('penelitians.created_at')->get();
        // dd($data_produk);

        return view('dashboard.penelitian.index', compact('kbk', 'kbk_nama', 'kkbk', 'data_produk', 'data_penelitian', 'anggota_kbk'));
    }

    public function detailProduk($nama_produk)
    {
        $kbk = KelompokKeahlian::all();
        $kbk_nama = KelompokKeahlian::where('nama_kbk', $nama_produk)->first();

        $produk = Produk::with('kelompokKeahlian')
            ->where('nama_produk', $nama_produk)
            ->firstOrFail();

        return view('dashboard.detail-penelitian.index', compact('produk', 'kbk', 'kbk_nama'));
    }

    public function dosenProduk($inventor)
    {
        $kbk = KelompokKeahlian::all();
        $kbk_nama = KelompokKeahlian::where('nama_kbk', $inventor)->first();
        $p_dosen = Produk::with('kelompokKeahlian')->where('inventor', $inventor)->paginate(7);
        return view('dashboard.dosen-produk.index', compact('kbk', 'p_dosen', 'kbk_nama'));
    }
}
