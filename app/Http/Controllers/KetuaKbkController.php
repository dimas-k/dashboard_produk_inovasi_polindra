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
            'kbk_id' => 'required|exists:kelompok_keahlians,id',
            'inventor' => 'required|string|max:255',
            'anggota_inventor' => 'nullable|string',
            'email_inventor' => 'required|email',
            'gambar' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048', // Sesuaikan dengan format file yang diperbolehkan
            'lampiran' => 'nullable|file|mimes:jpeg,png,jpg,pdf,docx|max:2048',
        ]);

        // dd($request->all());

        // Buat instance baru dari Produk
        $produk = new Produk();
        $produk->kbk_id = $request->kbk_id;
        $produk->nama_produk = $request->nama_produk;
        $produk->deskripsi = $request->deskripsi;
        if ($request->hasFile('gambar')) {
            $produk->gambar = $request->file('gambar')->store('dokumen-produk');
        }
        $produk->inventor = $request->inventor;
        $produk->anggota_inventor = $request->anggota_inventor;
        $produk->email_inventor = $request->email_inventor;
        if ($request->hasFile('lampiran')) {
            $produk->lampiran = $request->file('lampiran')->store('dokumen-produk');
        }
        $produk->save();


        return redirect('/k-kbk/produk')->with('success', 'Data Produk berhasil ditambahkan!');
    }
}
