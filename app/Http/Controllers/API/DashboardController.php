<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController;
use App\Models\User;
use App\Models\Produk;
use App\Models\Penelitian;
use Illuminate\Http\Request;
use App\Models\KelompokKeahlian;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\AnggotaKelompokKeahlianResource;
use App\Http\Resources\KelompokKeahlianResource;
use App\Http\Resources\PenelitianResource;
use App\Http\Resources\ProdukResource;
use App\Models\AnggotaKelompokKeahlian;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class DashboardController extends BaseController
{
    //
    public function index(): JsonResponse
    {
        $kbk = KelompokKeahlian::with(['produk', 'penelitian', 'user'])->get();

        $jumlah_kbk = $kbk->count();
        $jumlah_produk = Produk::where('status', 'Tervalidasi')->count();
        $jumlah_pusat_penelitian = Penelitian::where('status', 'Tervalidasi')->count();

        $produk_terbaru = Produk::where('status', 'Tervalidasi')->latest()->take(10)->get();
        $penelitian_terbaru = Penelitian::where('status', 'Tervalidasi')->latest()->take(10)->get();

        return response()->json([
            'kbk' => KelompokKeahlianResource::collection($kbk),
            'jumlah_kbk' => $jumlah_kbk,
            'jumlah_produk' => $jumlah_produk,
            'jumlah_pusat_penelitian' => $jumlah_pusat_penelitian,
            'produk_terbaru' => ProdukResource::collection($produk_terbaru),
            'penelitian_terbaru' => PenelitianResource::collection($penelitian_terbaru),
        ]);
    }

    public function contact(): JsonResponse
    {
        $kbk = KelompokKeahlian::all();

        return response()->json([
            'data' => KelompokKeahlianResource::collection($kbk)
        ]);
    }


    public function penelitian($nama_kbk): JsonResponse
    {
        // Mengambil semua data KBK
        $kbk = KelompokKeahlian::all();

        // Mengambil data KBK berdasarkan nama KBK
        $kbk_nama = KelompokKeahlian::where('nama_kbk', $nama_kbk)->firstOrFail();

        // Mengambil data anggota KBK
        $anggota_kbk = DB::table('anggota_kelompok_keahlians')
            ->join('kelompok_keahlians', 'anggota_kelompok_keahlians.kbk_id', '=', 'kelompok_keahlians.id')
            ->where('kelompok_keahlians.nama_kbk', '=', $nama_kbk)
            ->whereIn('anggota_kelompok_keahlians.jabatan', ['Dosen Lektor', 'Dosen Asisten Ahli', 'Dosen Lektor Kepala', 'Dosen', 'Lektor'])
            ->select(
                'anggota_kelompok_keahlians.id',
                'anggota_kelompok_keahlians.nama_lengkap',
                'anggota_kelompok_keahlians.jabatan'
            )
            ->get();

        // Mengambil data produk berdasarkan nama KBK
        $data_produk = DB::table('produks')
            ->join('kelompok_keahlians', 'produks.kbk_id', '=', 'kelompok_keahlians.id')
            ->select(
                'produks.nama_produk',
                DB::raw('COUNT(produks.id) as produk_count')
            )
            ->where('kelompok_keahlians.nama_kbk', '=', $nama_kbk)
            ->where('produks.status', 'Tervalidasi')
            ->groupBy('produks.nama_produk')
            ->latest('produks.created_at')
            ->get();


        // Mengambil data penelitian berdasarkan nama KBK
        $data_penelitian = DB::table('penelitians')
            ->join('kelompok_keahlians', 'penelitians.kbk_id', '=', 'kelompok_keahlians.id')
            ->select(
                'penelitians.id',
                'penelitians.judul',
                'penelitians.abstrak',
                'penelitians.gambar',
                'penelitians.penulis',
                'penelitians.email_penulis',
                'penelitians.penulis_korespondensi',
                'penelitians.penulis_bersama',
                'penelitians.lampiran'
            )
            ->where('kelompok_keahlians.nama_kbk', '=', $nama_kbk)
            ->where('penelitians.status', 'Tervalidasi')
            ->latest('penelitians.created_at')
            ->get();

        return response()->json([
            'kbk' => new KelompokKeahlianResource($kbk_nama),
            'anggota_kbk' => AnggotaKelompokKeahlianResource::collection($anggota_kbk),
            'produk' => ProdukResource::collection($data_produk),
            'penelitian' => PenelitianResource::collection($data_penelitian),
        ]);
    }

    public function detailProduk($nama_produk): JsonResponse
    {
        $produk = Produk::with(['kelompokKeahlian', 'anggota.anggota'])
            ->where('nama_produk', $nama_produk)
            ->first();

        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        $kbk = KelompokKeahlian::all();
        $kbk_nama = KelompokKeahlian::where('nama_kbk', $nama_produk)->first();

        return response()->json([
            'produk' => new ProdukResource($produk),
            'kbk' => KelompokKeahlianResource::collection($kbk),
            'kbk_nama' => $kbk_nama ? new KelompokKeahlianResource($kbk_nama) : null,
        ]);
    }



    public function detailPenelitian($judul): JsonResponse
    {
        try {
            // Mengambil semua data KBK
            $kbk = KelompokKeahlian::all();

            // Mengambil data KBK berdasarkan nama KBK
            $kbk_nama = KelompokKeahlian::where('nama_kbk', $judul)->first();

            // Mengambil data penelitian berdasarkan judul
            $penelitian = Penelitian::with(['kelompokKeahlian', 'anggotaPenelitian.detailAnggota'])
                ->where('judul', $judul)
                ->first();

            // Jika data penelitian tidak ditemukan, lempar error
            if (!$penelitian) {
                return response()->json([
                    'success' => false,
                    'message' => 'Penelitian dengan judul "' . $judul . '" tidak ditemukan.',
                ], 404);
            }

            // Mengembalikan response JSON dengan resource
            return response()->json([
                'success' => true,
                'penelitian' => new PenelitianResource($penelitian),
                'kbk' => KelompokKeahlianResource::collection($kbk),
                'kbk_nama' => $kbk_nama ? new KelompokKeahlianResource($kbk_nama) : null,
            ]);
        } catch (\Exception $e) {
            // Menangkap error yang tidak terduga
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function dosenProduk($dosen): JsonResponse
    {
        // Mengambil semua data KBK
        $kbk = KelompokKeahlian::all();

        // Mencari anggota berdasarkan nama dosen
        $anggota = AnggotaKelompokKeahlian::where('nama_lengkap', $dosen)->first();

        // Jika anggota tidak ditemukan
        if (!$anggota) {
            return response()->json([
                'message' => "Dosen dengan nama '{$dosen}' tidak ditemukan.",
                'kbk' => KelompokKeahlianResource::collection($kbk),
                'p_dosen' => [],
                'plt_dosen' => [],
                'dosen' => $dosen,
                'anggota' => null
            ]);
        }

        // Mencari produk yang terkait dengan dosen
        $p_dosen = Produk::where(function ($query) use ($anggota) {
            // Produk di mana dosen adalah anggota
            $query->whereHas('anggota', function ($subQuery) use ($anggota) {
                $subQuery->where('anggota_id', $anggota->id)
                    ->where('anggota_type', AnggotaKelompokKeahlian::class);
            });
        })
        ->orWhere(function ($query) use ($dosen) {
            // Produk yang diketuai oleh dosen
            $query->where('inventor', $dosen);
        })
        ->where('status', 'Tervalidasi')
        ->with(['kelompokKeahlian', 'anggota.anggota'])
        ->paginate(7);

        // Mencari penelitian yang terkait dengan dosen
        $plt_dosen = Penelitian::whereHas('anggotaPenelitian', function ($query) use ($anggota) {
            $query->where('anggota_id', $anggota->id);
        })
        ->with(['kelompokKeahlian', 'anggotaPenelitian.detailAnggota'])
        ->paginate(7);

        // Mengembalikan data dalam format JSON
        return response()->json([
            'kbk' => KelompokKeahlianResource::collection($kbk),
            'p_dosen' => ProdukResource::collection($p_dosen),
            'plt_dosen' => PenelitianResource::collection($plt_dosen),
            'dosen' => $dosen,
            'anggota' => new AnggotaKelompokKeahlianResource($anggota),
        ]);
    }

    public function katalogProduk(): JsonResponse
    {
        // Mengambil semua data KBK
        $kbk = KelompokKeahlian::all();

        // Mengambil produk yang statusnya Tervalidasi dengan pagination
        $produk = Produk::with('kelompokKeahlian')->where('status', 'Tervalidasi')->paginate(10);

        // Mengembalikan data produk dan KBK dalam format JSON
        return response()->json([
            'kbk' => KelompokKeahlianResource::collection($kbk),
            'produk' => ProdukResource::collection($produk)
        ]);
    }

    public function katalogProdukCari(Request $request): JsonResponse
    {
        // Mengambil semua data KBK
        $kbk = KelompokKeahlian::all();

        // Mendapatkan input pencarian dari request
        $cari = $request->input('cari_produk');

        // Mengambil produk yang statusnya Tervalidasi dan sesuai dengan pencarian nama produk
        $produk = Produk::with('kelompokKeahlian')
            ->where('nama_produk', 'LIKE', "%" . $cari . "%")
            ->where('status', 'Tervalidasi')
            ->paginate(10);

        // Mengembalikan data produk dan KBK dalam format JSON
        return response()->json([
            'kbk' => KelompokKeahlianResource::collection($kbk),
            'produk' => ProdukResource::collection($produk)
        ]);
    }

    public function katalogPenelitian(): JsonResponse
    {
        // Mengambil semua data KBK
        $kbk = KelompokKeahlian::all();

        // Mengambil penelitian yang statusnya Tervalidasi dengan pagination
        $penelitian = Penelitian::with('kelompokKeahlian')->where('status', 'Tervalidasi')->paginate(10);

        // Mengembalikan data penelitian dan KBK dalam format JSON
        return response()->json([
            'kbk' => KelompokKeahlianResource::collection($kbk),
            'penelitian' => PenelitianResource::collection($penelitian)
        ]);
    }
    public function katalogPenelitianCari(Request $request): JsonResponse
    {
        // Mengambil semua data KBK
        $kbk = KelompokKeahlian::all();

        // Mendapatkan input pencarian dari request
        $cari = $request->input('cari_penelitian');

        // Mengambil penelitian yang statusnya Tervalidasi dan sesuai dengan pencarian judul penelitian
        $penelitian = Penelitian::with('kelompokKeahlian')
            ->where('judul', 'LIKE', "%" . $cari . "%")
            ->where('status', 'Tervalidasi')
            ->paginate(10);

        // Mengembalikan data penelitian dan KBK dalam format JSON
        return response()->json([
            'kbk' => KelompokKeahlianResource::collection($kbk),
            'penelitian' => PenelitianResource::collection($penelitian)
        ]);
    }
}
