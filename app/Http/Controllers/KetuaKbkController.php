<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Produk;
use Illuminate\Http\Request;
use App\Models\KelompokKeahlian;
use App\Models\Penelitian;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class KetuaKbkController extends Controller
{
    public function dashboardPage()
    {
        return view('k_kbk.index');
    }
    public function produkInovasi()
    {
        // $jenis_kbk = KelompokKeahlian::all();
        $kelompokKeahlianId = Auth::user()->kelompokKeahlian->id ?? null;

        // Query produk berdasarkan kelompok keahlian ID
        $produks = Produk::with('kelompokKeahlian')->where('kbk_id', $kelompokKeahlianId)->paginate(10);

        $userId = Auth::id();
        $kkbk = DB::table('users')
            ->join('kelompok_keahlians', 'users.kbk_id', '=', 'kelompok_keahlians.id')
            ->select(
                'kelompok_keahlians.id',
                'kelompok_keahlians.nama_kbk',
                'users.nama_lengkap'
            )
            ->where('users.id', '=', $userId)
            ->get();


        // dd($produks);
        return view('k_kbk.produk.index', compact('produks', 'kkbk'));
    }
    public function showProduk($id)
    {
        $produk = Produk::with('KelompokKeahlian')->findOrFail($id);
        // dd($produk->lampiran);
        return view('k_kbk.produk.show.index', compact('produk'));
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
            'gambar' => 'required|file|mimes:jpeg,png,jpg|max:2048', // Sesuaikan dengan format file yang diperbolehkan
            'lampiran' => 'nullable|file|mimes:jpeg,png,jpg,pdf,docx|max:2048',
        ]);

        // dd($request->all());

        // Buat instance baru dari Produk
        $produk = new Produk();
        $produk->kbk_id = $request->kbk_id;
        $produk->nama_produk = $request->nama_produk;
        $produk->deskripsi = $request->deskripsi;
        $produk->inventor = $request->inventor;
        $produk->anggota_inventor = $request->anggota_inventor;
        $produk->email_inventor = $request->email_inventor;
        if ($request->hasFile('gambar')) {

            $originalName = $request->file('gambar')->getClientOriginalName();
            $fileName = time() . '_' . str_replace(' ', '_', $originalName); // Ganti spasi dengan underscore
        
            // Simpan file ke dalam storage/app/public/dokumen-produk
            $path = $request->file('gambar')->storeAs('public/dokumen-produk', $fileName);
        
            // Simpan path tanpa 'public/' di database
            $produk->gambar = str_replace('public/', '', $path);
        }
        
        if ($request->hasFile('lampiran')) {
        
            $originalName = $request->file('lampiran')->getClientOriginalName();
            $fileName = time() . '_' . str_replace(' ', '_', $originalName); // Ganti spasi dengan underscore
        
            // Simpan file ke dalam storage/app/public/dokumen-produk
            $path = $request->file('lampiran')->storeAs('public/dokumen-produk', $fileName);
        
            // Simpan path tanpa 'public/' di database
            $produk->lampiran = str_replace('public/', '', $path);
        }
        $produk->save();

        return redirect('/k-kbk/produk')->with('success', 'Data Produk berhasil ditambahkan!');
    }

    public function updateProdukInovasi(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'inventor' => 'required|string|max:255',
            'anggota_inventor' => 'nullable|string',
            'email_inventor' => 'required|email',
            'gambar' => 'required|file|mimes:jpeg,png,jpg|max:2048', // Sesuaikan dengan format file yang diperbolehkan
            'lampiran' => 'nullable|file|mimes:jpeg,png,jpg,pdf,docx|max:2048',
        ]);
        $produk = Produk::findOrFail($id);

        // Update data produk
        $produk->nama_produk = $request->nama_produk;
        $produk->deskripsi = $request->deskripsi;
        $produk->inventor = $request->inventor;
        $produk->anggota_inventor = $request->anggota_inventor;
        $produk->email_inventor = $request->email_inventor;

        if ($request->hasFile('gambar')) {

            if ($produk->gambar && file_exists(public_path($produk->gambar))) {
                unlink(public_path($produk->gambar));
            }

            $originalName = $request->file('gambar')->getClientOriginalName();

            $fileName = time() . '_' . $originalName;

            $request->file('gambar')->move(public_path('dokumen_produk'), $fileName);

            $produk->gambar = 'dokumen_produk/' . $fileName;
        }


        if ($request->hasFile('lampiran')) {

            if ($produk->lampiran && file_exists(public_path($produk->lampiran))) {
                unlink(public_path($produk->lampiran));
            }

            $originalLampiranName = $request->file('lampiran')->getClientOriginalName();

            $lampiranFileName = time() . '_' . $originalLampiranName;

            $request->file('lampiran')->move(public_path('dokumen_produk'), $lampiranFileName);

            $produk->lampiran = 'dokumen_produk/' . $lampiranFileName;
        }
        $produk->save();
        return redirect('/k-kbk/produk')->with('success', 'Data Produk berhasil diupdate!');
    }

    public function hapusProduk($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->gambar && File::exists(public_path($produk->gambar))) {
            File::delete(public_path($produk->gambar));
        }
        if ($produk->lampiran && File::exists(public_path($produk->lampiran))) {
            File::delete(public_path($produk->lampiran));
        }
        $produk->delete();
        return redirect('/k-kbk/produk')->with('success', 'Data Produk berhasil dihapus!');
    }

    //penelitian method
    public function penelitian()
    {

        $kelompokKeahlianId = Auth::user()->kelompokKeahlian->id ?? null;
        $penelitians = Penelitian::with('kelompokKeahlian')->where('kbk_id', $kelompokKeahlianId)->paginate(10);

        $userId = Auth::id();
        $kkbk = DB::table('users')
            ->join('kelompok_keahlians', 'users.kbk_id', '=', 'kelompok_keahlians.id')
            ->select(
                'kelompok_keahlians.id',
                'kelompok_keahlians.nama_kbk',
                'users.nama_lengkap'
            )
            ->where('users.id', '=', $userId)
            ->get();
        
        Return view('k_kbk.penelitian.index', compact('penelitians', 'kkbk'));
    }

    public function showPenelitian($id)
    {
        $penelitian = Penelitian::with('kelompokKeahlian')->findOrFail($id);
        return view('k_kbk.penelitian.show.index', compact('penelitian'));
    }

    public function storePenelitian(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'abstrak' => 'required|file|mimes:pdf|max:2048',
            'kbk_id' => 'required|exists:kelompok_keahlians,id',
            'penulis' => 'required|string|max:255',
            'anggota_penulis' => 'nullable|string',
            'email_penulis' => 'required|email',
            'gambar' => 'required|file|mimes:jpeg,png,jpg|max:2048', // Sesuaikan dengan format file yang diperbolehkan
            'lampiran' => 'nullable|file|mimes:jpeg,png,jpg,pdf,docx|max:2048',
        ]);

        $penelitian = new Penelitian();
        $penelitian->kbk_id = $request->kbk_id;
        $penelitian->judul = $request->judul;
        $penelitian->penulis = $request->penulis;
        $penelitian->anggota_penulis = $request->anggota_penulis;
        $penelitian->email_penulis = $request->email_penulis;

        if ($request->hasFile('abstrak')) {

            $originalName = $request->file('abstrak')->getClientOriginalName();

            $fileName = time() . '_' . $originalName;

            $request->file('abstrak')->move(public_path('dokumen-penelitian'), $fileName);

            $penelitian->abstrak = 'dokumen-penelitian/' . $fileName;
        }

        if ($request->hasFile('gambar')) {

            $originalName = $request->file('gambar')->getClientOriginalName();

            $fileName = time() . '_' . $originalName;

            $request->file('gambar')->move(public_path('dokumen-penelitian'), $fileName);

            $penelitian->gambar = 'dokumen-penelitian/' . $fileName;
        }

        if ($request->hasFile('lampiran')) {

            $originalName = $request->file('lampiran')->getClientOriginalName();

            $fileName = time() . '_' . $originalName;

            $request->file('lampiran')->move(public_path('dokumen-penelitian'), $fileName);

            $penelitian->lampiran = 'dokumen-penelitian/' . $fileName;
        }
        $penelitian->save();

        return redirect('/k-kbk/penelitian')->with('success', 'Data Produk berhasil ditambahkan!');
    }

    public function updatePenelitian(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'abstrak' => 'required|file|mimes:pdf|max:2048',
            'penulis' => 'required|string|max:255',
            'anggota_penulis' => 'nullable|string',
            'email_penulis' => 'required|email',
            'gambar' => 'required|file|mimes:jpeg,png,jpg|max:2048', // Sesuaikan dengan format file yang diperbolehkan
            'lampiran' => 'nullable|file|mimes:jpeg,png,jpg,pdf,docx|max:2048',
        ]);
        $penelitian = Penelitian::findOrFail($id);
        $penelitian->judul = $request->judul;
        $penelitian->penulis = $request->penulis;
        $penelitian->anggota_penulis = $request->anggota_penulis;
        $penelitian->email_penulis = $request->email_penulis;
        if ($request->hasFile('gambar')) {

            if ($penelitian->gambar && file_exists(public_path($penelitian->gambar))) {
                unlink(public_path($penelitian->gambar));
            }

            $originalName = $request->file('gambar')->getClientOriginalName();

            $fileName = time() . '_' . $originalName;

            $request->file('gambar')->move(public_path('dokumen_penelitian'), $fileName);

            $penelitian->gambar = 'dokumen_penelitian/' . $fileName;
        }


        if ($request->hasFile('lampiran')) {

            if ($penelitian->lampiran && file_exists(public_path($penelitian->lampiran))) {
                unlink(public_path($penelitian->lampiran));
            }

            $originalLampiranName = $request->file('lampiran')->getClientOriginalName();

            $lampiranFileName = time() . '_' . $originalLampiranName;

            $request->file('lampiran')->move(public_path('dokumen_penelitian'), $lampiranFileName);

            $penelitian->lampiran = 'dokumen_penelitian/' . $lampiranFileName;
        }
        $penelitian->save();
        return redirect('/k-kbk/penelitian')->with('success', 'Data Penelitian berhasil diupdate');
    }
    public function hapusPenelitian($id)
    {
        $penelitian = Penelitian::findOrFail($id);

        if ($penelitian->gambar && File::exists(public_path($penelitian->gambar))) {
            File::delete(public_path($penelitian->gambar));
        }
        if ($penelitian->lampiran && File::exists(public_path($penelitian->lampiran))) {
            File::delete(public_path($penelitian->lampiran));
        }
        $penelitian->delete();
        return redirect('/k-kbk/penelitian')->with('success', 'Data Penelitian berhasil dihapus');
    }
}
