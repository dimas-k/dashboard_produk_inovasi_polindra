<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use App\Models\KelompokKeahlian;

class KetuaKbkController extends Controller
{
    public function dashboardPage()
    {
        return view('k_kbk.index');
    }
    public function produkInovasi()
    {
        $jenis_kbk = KelompokKeahlian::all();
        $kelompokKeahlianId = auth()->user()->kelompokKeahlian->id ?? null;

        $produks = Produk::when($kelompokKeahlianId, function ($query) use ($kelompokKeahlianId) {
            return $query->whereHas('kelompokKeahlian', function ($q) use ($kelompokKeahlianId) {
                $q->where('id', $kelompokKeahlianId);
            });
        })->paginate(10);

        return view('k_kbk.produk.index', compact('produks', 'jenis_kbk'));
    }

    public function storeProduk(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kelompok_keahlian_id' => 'required|exists:kelompok_keahlians,id', // Pastikan kelompok_keahlian_id ada
            'inventor' => 'required|string|max:255',
        ]);

        // Buat instance baru dari Produk
        $produk = new Produk();
        $produk->nama_produk = $request->nama_produk;
        $produk->deskripsi = $request->deskripsi;
        if ($request->hasFile('gambar')) {
            $produk->gambar = $request->file('gambar')->store('dokumen-produk');
        }
        $produk->inventor = $request->inventor;
        $produk->anggota_inventor = $request->anggota_inventor;
        
        
        $produk->save();

        $kelompokKeahlian = KelompokKeahlian::find($request->kelompok_keahlian_id);
        $kelompokKeahlian->produk_id = $produk->id;
        $kelompokKeahlian->save();

        return redirect('/k-kbk/produk')->with('success', 'Data Produk berhasil ditambahkan!');
    }
}
