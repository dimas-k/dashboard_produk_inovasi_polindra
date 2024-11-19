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
use App\Models\AnggotaKelompokKeahlian;

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
        // dd($produk_terbaru);
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
        // $anggota_kbk = DB::table('anggota_kelompok_keahlians')
        //     ->join('kelompok_keahlians', 'anggota_kelompok_keahlians.kbk_id', '=', 'kelompok_keahlians.id')
        //     ->where('kelompok_keahlians.nama_kbk', '=', $nama_kbk)
        //     ->select(
        //         'anggota_kelompok_keahlians.nama_lengkap',
        //         'anggota_kelompok_keahlians.nama_lengkap'
        //     )->get();
        $anggota_kbk = DB::table('anggota_kelompok_keahlians')
            ->join('kelompok_keahlians', 'anggota_kelompok_keahlians.kbk_id', '=', 'kelompok_keahlians.id')
            ->where('kelompok_keahlians.nama_kbk', '=', $nama_kbk)
            ->whereIn('anggota_kelompok_keahlians.jabatan', ['Dosen Lektor', 'Dosen Asisten Ahli', 'Dosen Lektor Kepala', 'Dosen', 'Lektor'])
            ->select(
                'anggota_kelompok_keahlians.nama_lengkap',
                'anggota_kelompok_keahlians.jabatan'
            )
            ->get();


        // dd($kelompokKeahlian);
        // Mengambil dan menampilkan nama_lengkap dan nama_kbk
        // foreach ($kkbk as $item) {
        //     $namaLengkap = $item->nama_lengkap;  // Mengambil nama_lengkap
        //     $namaKBK = $item->nama_kbk;    // Mengambil nama_kbk
        //     echo "Nama: " . $namaLengkap . ", KBK: " . $namaKBK . "\n";
        // }

        // $data_produk = Produk::where('kbk_id', $id)->get();
        $data_produk = DB::table('produks')
            ->join('kelompok_keahlians', 'produks.kbk_id', '=', 'kelompok_keahlians.id')
            ->join('users', 'users.kbk_id', '=', 'kelompok_keahlians.id')
            ->select(
                'produks.id as id_produks',
                'produks.nama_produk as nama_produks',
                'produks.deskripsi as produk_deskripsi',
                'produks.gambar'
            )
            ->where('kelompok_keahlians.nama_kbk', '=', $nama_kbk)
            ->where('status', 'Tervalidasi')
            ->groupBy('produks.nama_produk')  // Group by product ID to remove duplicates
            ->latest('produks.created_at')
            ->get();


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
                'penelitians.email_penulis',
                'penelitians.penulis_korespondensi',
                'penelitians.penulis_bersama',
                'penelitians.lampiran',
            )->where('kelompok_keahlians.nama_kbk', '=', $nama_kbk)->where('status', 'Tervalidasi')->latest('penelitians.created_at')->get();
        // dd($data_produk);

        return view('dashboard.kelompok_keahlian.index', compact('kbk', 'kbk_nama', 'kkbk', 'data_produk', 'data_penelitian', 'anggota_kbk'));
    }

    public function detailProduk($nama_produk)
    {
        $kbk = KelompokKeahlian::all();
        $kbk_nama = KelompokKeahlian::where('nama_kbk', $nama_produk)->first();

        $produk = Produk::with(['kelompokKeahlian', 'anggota.anggota'])
            ->where('nama_produk', $nama_produk)
            ->firstOrFail();

        // dd($produk->anggota);

        return view('dashboard.detail-produk.index', compact('produk', 'kbk', 'kbk_nama'));
    }

    public function detailPenelitian($judul)
    {
        $kbk = KelompokKeahlian::all();
        $kbk_nama = KelompokKeahlian::where('nama_kbk', $judul)->first();

        $penelitian = Penelitian::with(['kelompokKeahlian', 'anggotaPenelitian.detailAnggota'])
            ->where('judul', $judul)
            ->firstOrFail();
        return view('dashboard.detail-penelitian.index', compact('penelitian', 'kbk'));
    }

    public function dosenProduk($dosen)
    {
        $kbk = KelompokKeahlian::all();
        $anggota = AnggotaKelompokKeahlian::where('nama_lengkap', $dosen)->first();
        if ($anggota) {
            $p_dosen = Produk::where(function ($query) use ($anggota) {
                // Produk di mana Yana adalah anggota
                $query->whereHas('anggota', function ($subQuery) use ($anggota) {
                    $subQuery->where('anggota_id', $anggota->id)
                        ->where('anggota_type', AnggotaKelompokKeahlian::class);
                });
            })
                ->orWhere(function ($query) use ($dosen) {
                    // Produk yang diketuai oleh Yano
                    $query->where('inventor', $dosen);
                })
                ->where('status', 'Tervalidasi') // Status tervalidasi
                ->with(['kelompokKeahlian', 'anggota.anggota'])
                ->paginate(7);
        } else {
            $p_dosen = Produk::with(['kelompokKeahlian', 'anggota'])
                ->where('inventor', $dosen)
                ->paginate(7);
        }
        $plt_dosen = null;
        if ($anggota) {
            $plt_dosen = Penelitian::whereHas('anggotaPenelitian', function ($query) use ($anggota) {
                $query->where('anggota_id', $anggota->id); // Menyaring berdasarkan anggota di penelitian
            })->with(['kelompokKeahlian', 'anggotaPenelitian.detailAnggota'])->paginate(7);
        } else {
            $plt_dosen = Penelitian::with('kelompokKeahlian')->where('penulis', $dosen)->paginate(7);
        }

        if ($plt_dosen->isEmpty()) {
            $plt_dosen = null;
        }
        // dd($dosen);
        // dd($p_dosen);

        return view('dashboard.dosen-produk.index', [
            'kbk' => $kbk,
            'p_dosen' => $p_dosen,
            'plt_dosen' => $plt_dosen,
            'dosen' => $dosen,
            'anggota' => $anggota
        ]);
    }

    public function katalogProduk()
    {
        $kbk = KelompokKeahlian::all();
        $produk = Produk::with('KelompokKeahlian')->where('status', 'Tervalidasi')->paginate(10);

        return view('dashboard.katalog-produk.index', compact('produk', 'kbk'));
    }

    public function katalogProdukCari(Request $request)
    {
        $kbk = KelompokKeahlian::all();

        $cari = $request->input('cari_produk');
        $produk = Produk::with('KelompokKeahlian')->where('nama_produk', 'LIKE', "%" . $cari . "%")->where('status', 'Tervalidasi')->paginate(10);
        return view('dashboard.katalog-produk.index', compact('produk', 'kbk'));
    }

    public function katalogPenelitian()
    {
        $kbk = KelompokKeahlian::all();
        $penelitian = Penelitian::with('KelompokKeahlian')->where('status', 'Tervalidasi')->paginate(10);

        return view('dashboard.katalog-penelitian.index', compact('penelitian', 'kbk'));
    }
    public function katalogPenelitianCari(Request $request)
    {
        $kbk = KelompokKeahlian::all();
        $cari = $request->input('cari_penelitian');
        $penelitian = Penelitian::with('KelompokKeahlian')->where('judul', 'LIKE', "%" . $cari . "%")->where('status', 'Tervalidasi')->paginate(10);

        return view('dashboard.katalog-penelitian.index', compact('penelitian', 'kbk'));
    }
}
