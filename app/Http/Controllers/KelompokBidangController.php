<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelompokKeahlian;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class KelompokBidangController extends Controller
{
    public function pageKelompokBidang()
    {
        $kbk = KelompokKeahlian::all();

        $kbk_navigasi = DB::table('kelompok_keahlians')
        ->select(
            'kelompok_keahlians.id',
            'kelompok_keahlians.nama_kbk'
        )
        ->get();
        return view('admin.kbk.index', compact('kbk','kbk_navigasi'));
    }

    public function storeKelompokKeahlian(Request $request)
    {
        $validasidata = $request->validate([
            'nama_kbk' => 'required|string|max:255',
            'jurusan' => 'string|max:255',
        ]);

        $kbk = new KelompokKeahlian();
        $kbk->nama_kbk = $request->nama_kbk;
        $kbk->jurusan = $request->jurusan;
        $kbk->deskripsi = $request->deskripsi;
        $kbk->save($validasidata);

        return redirect('/admin/kelompok-bidang-keahlian')->with('success', 'Kelompok Keahlian berhasil ditambahkan');
    }
    public function hapusKbk(string $id){
        $kbk = KelompokKeahlian::findOrFail($id);
        $kbk->delete();
        return redirect('/admin/kelompok-bidang-keahlian')->with('success', 'product Deleted!');
    }

    public function edit(string $id) {
        $kbk = KelompokKeahlian::find($id);
        return view('/admin/kelompok-bidang-keahlian/kbk', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        $kbk = KelompokKeahlian::find($id);
        $kbk->nama_kbk = $request->nama_kbk;
        $kbk->jurusan = $request->jurusan;
        $kbk->deskripsi = $request->deskripsi;
        $kbk->save();

    

        return redirect('/admin/kelompok-bidang-keahlian')->with('success', 'Data admin berhasil di update');
    }
}
