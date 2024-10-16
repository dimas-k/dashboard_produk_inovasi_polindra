<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\KelompokKeahlian;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $kbk = KelompokKeahlian::all();
        return view('dashboard.index', compact('kbk'));
    }

    public function contact()
    {
        $kbk = KelompokKeahlian::all();
        return view('dashboard.contact.index', compact('kbk'));
    }

    public function penelitian($id)
    {
        $kbk = KelompokKeahlian::all();
        $kbk_nama = KelompokKeahlian::find($id);
        $kkbk = DB::table('users')
            ->join('kelompok_keahlians', 'users.kbk_id', '=', 'kelompok_keahlians.id')
            ->select(
                'users.id',
                'users.nama_lengkap',
                'kelompok_keahlians.nama_kbk'
            )
            ->where('users.kbk_id','=',$id)
            ->first();

        // dd($kelompokKeahlian);
        // Mengambil dan menampilkan nama_lengkap dan nama_kbk
        // foreach ($kkbk as $item) {
        //     $namaLengkap = $item->nama_lengkap;  // Mengambil nama_lengkap
        //     $namaKBK = $item->nama_kbk;    // Mengambil nama_kbk
        //     echo "Nama: " . $namaLengkap . ", KBK: " . $namaKBK . "\n";
        // }

        return view('dashboard.penelitian.index', compact('kbk', 'kbk_nama', 'kkbk'));
    }

    public function detail_Penelitian()
    {
        $kbk = KelompokKeahlian::all();
        return view('dashboard.detail-penelitian.index', compact('kbk'));
    }
}
