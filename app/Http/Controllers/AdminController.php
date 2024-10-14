<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.index');
    }

    public function admin()
    {
        $admin = User::where('role', 'admin')->get();
        return view('admin.admin-page.index', compact('admin'));
    }
    public function showAdmin(string $id)
    {
        $admin = User::find($id);
        return view('admin.admin-page.show.index', compact('admin'));
    }
    public function ketuaKBK()
    {
        $kbk = User::where('role', 'ketua_kbk')->paginate(10);
        return view('admin.ketua-kbk.index', compact('kbk'));
    }

    public function showDataKetuaKbk(string $id)
    {
        $k_kbk = User::find($id);
        return view('admin.ketua-kbk.show.index', compact('k_kbk'));
    }

    public function storeDataKetuaKbk(Request $request)
    {
        $validasi = $request->validate([
            'nama_lengkap' => 'required|string',
            'nip' => 'required|numeric|digits_between:1,20',
            'kbk' => 'required',
            'no_hp' => 'required',
            'email' => 'required|email|unique:users',
            'jabatan' => 'required',
            'username' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);
        
        $k_kbk = new User();
        $k_kbk->nama_lengkap = $request->nama_lengkap;
        $k_kbk->nip = $request->nip;
        $k_kbk->kbk = $request->kbk;
        $k_kbk->no_hp = $request->no_hp;
        $k_kbk->email = $request->email;
        $k_kbk->jabatan = $request->jabatan;
        $k_kbk->username = $request->username;
        $k_kbk->password = Hash::make($request->password); // Hashing password
        $k_kbk->role = $request->role;
        
        $k_kbk->save();

        // dd($k_kbk);

        return redirect('/admin/ketua-kbk')->with('success', 'Data ketua Kelompok Keahlian berhasil ditambahkan!');
    }

    public function updateKetuaKbk(Request $request, string $id)
    {
        $validasi = $request->validate([
            'nama_lengkap' => 'required|string',
            'nip' => 'required|numeric|digits_between:1,20',
            'kbk' => 'required',
            'no_hp' => 'required',
            'email' => 'required|email|',
            'jabatan' => 'required',
            'username' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);

        $k_kbk = User::find($id);
        $k_kbk->nama_lengkap = $request->nama_lengkap;
        $k_kbk->nip = $request->nip;
        $k_kbk->kbk = $request->kbk;
        $k_kbk->no_hp = $request->no_hp;
        $k_kbk->email = $request->email;
        $k_kbk->jabatan = $request->jabatan;
        $k_kbk->username = $request->username;
        $k_kbk->password = Hash::make($request->password); // Hashing password
        $k_kbk->role = $request->role;
        
        $k_kbk->save();
        return redirect('/admin/ketua-kbk')->with('success', 'Data ketua Kelompok Keahlian berhasil diupdate!');
    }

    public function hapusKetuaKbk(string $id)
    {
        $k_kbk = User::findOrFail($id);
        $k_kbk->delete();
        return redirect('/admin/ketua-kbk')->with('success', 'Data ketua Kelompok Keahlian berhasil dihapus');

    }
}
