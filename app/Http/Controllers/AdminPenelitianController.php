<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelompokKeahlian;
use App\Models\Penelitian;

class AdminPenelitianController extends Controller
{
    public function pagePenelitian($id)
    {
        $kbk_navigasi = KelompokKeahlian::select('id', 'nama_kbk')->get();
        $kbk_navigasi1 = KelompokKeahlian::select('id', 'nama_kbk')->where('id', $id)->first();

        $data_penelitian = Penelitian::with('kelompokKeahlian') ->where('kbk_id', $id) ->paginate(10);

        return view('admin.penelitian.index', compact('kbk_navigasi', 'kbk_navigasi1', 'data_penelitian'));
    }
    public function showPenelitian($id)
    {
        $kbk_navigasi = KelompokKeahlian::select('kelompok_keahlians.id', 'kelompok_keahlians.nama_kbk')->get();
        $penelitian = Penelitian::with(['kelompokKeahlian', 'penulisKorespondensi'])->find($id);
        dd($penelitian->penulisKorespondensi->jabatan);
        return view('admin.penelitian.show.index', compact('penelitian', 'kbk_navigasi'));
    }

    public function validatePenelitian(Request $request, $id)
    {
        $penelitian = Penelitian::findOrFail($id);
        $penelitian->status = $request->has('status') ? 'Tervalidasi' : 'Belum Divalidasi';
        $penelitian->save();
        return redirect()->route('admin.penelitian', ['id' => $penelitian->kbk_id])->with('success', 'Penelitian tervalidasi');

    }
}
