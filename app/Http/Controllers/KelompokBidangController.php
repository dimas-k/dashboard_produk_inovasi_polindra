<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelompokKeahlian;

class KelompokBidangController extends Controller
{
    public function pageKelompokBidang()
    {
        $kbk = KelompokKeahlian::all();
        return view('admin.kbk.index', compact('kbk'));
    }

    public function storeKelompokKeahlian(Request $request)
    {
        $validasidata = $request->validate([
            'nama_kbk' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
        ]);

        $kbk = new KelompokKeahlian();
        $kbk->nama_kbk = $request->nama_kbk;
        $kbk->jurusan = $request->jurusan;
        $kbk->save($validasidata);

        return redirect('/admin/kelompok-bidang-keahlian')->with('success', 'Kelompok Keahlian berhasil ditambahkan');
    }
    public function hapusKbk(string $id)
    {
        
    }
}
