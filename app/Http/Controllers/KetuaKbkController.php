<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use App\Models\KelompokKeahlian;
use Illuminate\Support\Facades\Auth;

class KetuaKbkController extends Controller
{
    public function dashboardPage()
    {
        return view('k_kbk.index');
    }
    public function produkInovasi()
    {
        $jenis_kbk = KelompokKeahlian::all();
        $user = Auth::user();

        // Mengambil kelompok keahlian berdasarkan kbk_id
        $kelompokKeahlianId = $user->kbk_id; // Mengambil kbk_id dari user

        // Mengambil produk berdasarkan kelompok keahlian
        $produks = Produk::where('kbk_id', $kelompokKeahlianId)->paginate(10);


        return view('k_kbk.produk.index', compact('produks', 'jenis_kbk',));
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
            // Ambil nama asli file
            $originalName = $request->file('gambar')->getClientOriginalName();
            // Tambahkan timestamp untuk memastikan nama file unik
            $fileName = time() . '_' . $originalName;
            // Pindahkan file ke folder 'uploads' di public
            $request->file('gambar')->move(public_path('dokumen-produk'), $fileName);
            // Simpan path file ke kolom 'gambar' di database
            $produk->gambar = 'dokumen-produk/' . $fileName;
        }
        $produk->inventor = $request->inventor;
        $produk->anggota_inventor = $request->anggota_inventor;
        $produk->email_inventor = $request->email_inventor;
        if ($request->hasFile('lampiran')) {
            // Ambil nama asli file
            $originalName = $request->file('lampiran')->getClientOriginalName();
            // Tambahkan timestamp untuk memastikan nama file unik
            $fileName = time() . '_' . $originalName;
            // Pindahkan file ke folder 'uploads' di public
            $request->file('lampiran')->move(public_path('dokumen-produk'), $fileName);
            // Simpan path file ke kolom 'lampiran' di database
            $produk->lampiran = 'dokumen-produk/' . $fileName;
        }
        $produk->save();


        return redirect('/k-kbk/produk')->with('success', 'Data Produk berhasil ditambahkan!');
    }

    public function updateProdukInovasi(Request $request, $id)
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
        $produk = Produk::findOrFail($id);

        // Update data produk
        $produk->nama_produk = $request->nama_produk;
        $produk->deskripsi = $request->deskripsi;
        $produk->inventor = $request->inventor;
        $produk->anggota_inventor = $request->anggota_inventor;
        $produk->email_inventor = $request->email_inventor;
        $produk->status = $request->status;

        // Cek apakah ada file gambar yang diupload
        if ($request->hasFile('gambar')) {
            // Ambil nama asli file
            $originalName = $request->file('gambar')->getClientOriginalName();
            // Tambahkan timestamp untuk memastikan nama file unik
            $fileName = time() . '_' . $originalName;
            // Pindahkan file ke folder 'uploads' di public
            $request->file('gambar')->move(public_path('dokumen_produk'), $fileName);
            // Simpan path file ke kolom 'gambar' di database
            $produk->gambar = 'dokumen_produk/' . $fileName;
        }
        if ($request->hasFile('lampiran')) {
            // Ambil nama asli file
            $originalName = $request->file('lampiran')->getClientOriginalName();
            // Tambahkan timestamp untuk memastikan nama file unik
            $fileName = time() . '_' . $originalName;
            // Pindahkan file ke folder 'uploads' di public
            $request->file('lampiran')->move(public_path('dokumen_produk'), $fileName);
            // Simpan path file ke kolom 'lampiran' di database
            $produk->lampiran = 'dokumen_produk/' . $fileName;
        }
        $produk->save();
        return redirect('/k-kbk/produk')->with('success', 'Data Produk berhasil diupdate!');
    }
}
