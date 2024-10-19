<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use App\Models\KelompokKeahlian;
use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;


class AdminProdukInovasiController extends Controller
{
    public function pageProduk($id)
    {

        // Mendapatkan navigasi kelompok keahlian
        $kbk_navigasi = KelompokKeahlian::select('id', 'nama_kbk')->get();

        // Mendapatkan detail kelompok keahlian berdasarkan ID
        $kbk_navigasi1 = KelompokKeahlian::select('id', 'nama_kbk')
            ->where('id', $id)
            ->first();

        // Mengambil produk berdasarkan kbk_id dan melakukan paginasi
        $data_produk = Produk::with('kelompokKeahlian') // Mengambil relasi kelompok keahlian jika diperlukan
            ->where('kbk_id', $id) // Mengambil produk berdasarkan kelompok keahlian
            ->paginate(10); // Paginasi produk, 10 produk per halaman


        return view('admin.produk.index', compact('kbk_navigasi', 'kbk_navigasi1', 'data_produk'));
    }

    public function ShowPageProduk($id)
    {
        $produk = Produk::with('KelompokKeahlian')->findOrFail($id);
        $kbk_navigasi = KelompokKeahlian::select(
            'kelompok_keahlians.id',
            'kelompok_keahlians.nama_kbk'
        )
            ->get();
        return view('admin.produk.show.index', compact('produk', 'kbk_navigasi'));
    }

    public function validateProduk(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $produk->status = $request->has('status') ? 'Tervalidasi' : 'Belum Divalidasi';
        $produk->save();

        return redirect()->route('admin.produk', ['id' => $produk->kbk_id])->with('success', 'Produk berhasil di validasi');
    }
}
