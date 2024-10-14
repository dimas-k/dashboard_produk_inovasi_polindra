<?php

namespace App\Http\Controllers;

use App\Models\KelompokKeahlian;
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

    public function storeAdmin(Request $request)
    {
        $validasi = $request->validate([
            'nama_lengkap' => 'required|string',
            'nip' => 'required|numeric|digits_between:1,20',
            'no_hp' => 'required',
            'email' => 'required|email|unique:users',
            'jabatan' => 'required',
            'username' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);
        $admin = new User();
        $admin->nama_lengkap = $request->nama_lengkap;
        $admin->nip = $request->nip;
        $admin->kbk_id = $request->kbk_id;
        $admin->no_hp = $request->no_hp;
        $admin->email = $request->email;
        $admin->jabatan = $request->jabatan;
        $admin->username = $request->username;
        $admin->password = Hash::make($request->password); // Hashing password
        $admin->role = $request->role;
        $admin->save($validasi);

    }
    public function ketuaKBK()
    {
        $kbk = User::with('kbk')->where('role', 'ketua_kbk')->paginate(10);
        $jenis_kbk = KelompokKeahlian::all();
        return view('admin.ketua-kbk.index', compact('kbk', 'jenis_kbk'));
    }

    public function showDataKetuaKbk(string $id)
    {
        $k_kbk = User::with('kbk')->find($id);
        return view('admin.ketua-kbk.show.index', compact('k_kbk'));
    }

    public function storeDataKetuaKbk(Request $request)
    {
        $validasi = $request->validate([
            'nama_lengkap' => 'required|string',
            'nip' => 'required|numeric|digits_between:1,20',
            'kbk_id' => 'required|exists:kelompok_keahlians,id',
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
        $k_kbk->kbk_id = $request->kbk_id;
        $k_kbk->no_hp = $request->no_hp;
        $k_kbk->email = $request->email;
        $k_kbk->jabatan = $request->jabatan;
        $k_kbk->username = $request->username;
        $k_kbk->password = Hash::make($request->password); // Hashing password
        $k_kbk->role = $request->role;
        
        $k_kbk->save($validasi);

        // dd($k_kbk);

        return redirect('/admin/ketua-kbk')->with('success', 'Data ketua Kelompok Keahlian berhasil ditambahkan!');
    }

    public function updateKetuaKbk(Request $request, $id)
    {
        $validasi = $request->validate([
            'nama_lengkap' => 'required|string',
            'nip' => 'required|numeric|digits_between:1,20',
            'kbk_id' => 'required|exists:kelompok_keahlians,id',
            'no_hp' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'jabatan' => 'required',
            'username' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);

        $k_kbk = User::find($id);
        $k_kbk->nama_lengkap = $request->nama_lengkap;
        $k_kbk->nip = $request->nip;
        $k_kbk->kbk_id = $request->kbk_id;
        $k_kbk->no_hp = $request->no_hp;
        $k_kbk->email = $request->email;
        $k_kbk->jabatan = $request->jabatan;
        $k_kbk->username = $request->username;
        $k_kbk->password = Hash::make($request->password); // Hashing password
        $k_kbk->role = $request->role;
        
        $k_kbk->save($validasi);
        return redirect('/admin/ketua-kbk')->with('success', 'Data ketua Kelompok Keahlian berhasil diupdate!');
    }

    public function hapusKetuaKbk(string $id)
    {
        $k_kbk = User::findOrFail($id);
        $k_kbk->delete();
        return redirect('/admin/ketua-kbk')->with('success', 'Data ketua Kelompok Keahlian berhasil dihapus');

    }
}
