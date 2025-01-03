<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Produk;
use App\Models\Penelitian;
use Illuminate\Http\Request;
use App\Models\KelompokKeahlian;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;
use Carbon\Carbon;




class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $kbk_navigasi = KelompokKeahlian::select('id', 'nama_kbk')->get();
        $prdk_valid = Produk::with('kelompokKeahlian')->where('status', 'Tervalidasi')->count();
        $prdk_nonvalid = Produk::with('kelompokKeahlian')->where('status', 'Belum Divalidasi')->count();

        $pnltan_valid = Penelitian::with('kelompokKeahlian')->where('status', 'Tervalidasi')->count();
        $pnltan_nonvalid = Penelitian::with('kelompokKeahlian')->where('status', 'Belum Divalidasi')->count();

        $prdk_tahun = Produk::whereNotNull('tanggal_granted')
            ->where('status', 'Tervalidasi')
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal_granted->format('Y');
            })
            ->map(function ($group) {
                return $group->count();
            })->sortKeys();

        $plt_tahun = Penelitian::whereNotNull('tanggal_publikasi')
            ->where('status', 'Tervalidasi')
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal_publikasi->format('Y');
            })
            ->map(function ($group) {
                return $group->count();
            })->sortKeys();

        $penelitians = Penelitian::query();

        if ($request->has('start_date') && $request->has('end_date')) {
            $penelitians->whereBetween('tanggal_publikasi', [$request->start_date, $request->end_date]);
        }

        $data_plt = $penelitians->selectRaw('DATE(tanggal_publikasi) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $produks = Produk::query();

        if ($request->has('start_date') && $request->has('end_date')) {
            $produks->whereBetween('tanggal_submit', [$request->start_date, $request->end_date]);
        }

        $data_prdk = $produks->selectRaw('DATE(tanggal_submit) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();


        $queryProduk = Produk::query();
        $queryPenelitian = Penelitian::query();

        // Filter berdasarkan tahun
        if ($request->has('tahun_awal') && $request->has('tahun_akhir')) {
            $tahun_awal = $request->input('tahun_awal');
            $tahun_akhir = $request->input('tahun_akhir');

            if ($tahun_awal && $tahun_akhir) {
                $queryProduk->whereYear('tanggal_granted', '>=', $tahun_awal)
                    ->whereYear('tanggal_granted', '<=', $tahun_akhir);

                $queryPenelitian->whereYear('tanggal_publikasi', '>=', $tahun_awal)
                    ->whereYear('tanggal_publikasi', '<=', $tahun_akhir);
            }
        } elseif ($request->has('tahun_awal')) {
            $tahun_awal = $request->input('tahun_awal');

            $queryProduk->whereYear('tanggal_granted', '>=', $tahun_awal);
            $queryPenelitian->whereYear('tanggal_publikasi', '>=', $tahun_awal);
        } elseif ($request->has('tahun_akhir')) {
            $tahun_akhir = $request->input('tahun_akhir');

            $queryProduk->whereYear('tanggal_granted', '<=', $tahun_akhir);
            $queryPenelitian->whereYear('tanggal_publikasi', '<=', $tahun_akhir);
        }

        // Filter berdasarkan status validasi
        if ($request->has('status_validasi') && $request->status_validasi != '') {
            $status_validasi = $request->input('status_validasi');
            $queryProduk->where('status', $status_validasi);
            $queryPenelitian->where('status', $status_validasi);
        }

        $produk_paginate = $queryProduk->paginate(5);
        $penelitian_paginate = $queryPenelitian->paginate(5);
        $produk = $queryProduk->get();
        $penelitian = $queryPenelitian->get();

        // Cek apakah pengguna ingin mengunduh EXCEL
        if ($request->has('download') && $request->input('download') == 'Excel') {
            return $this->downloadExcel($produk, $penelitian);
        }
        return view('admin.index', compact('kbk_navigasi', 'pnltan_valid', 'pnltan_nonvalid', 'prdk_valid', 'prdk_nonvalid', 'prdk_tahun', 'plt_tahun', 'data_plt', 'data_prdk', 'produk', 'penelitian', 'produk_paginate', 'penelitian_paginate'));
    }

    protected function downloadExcel($produk, $penelitian)
    {
        $filename = "report_" . now()->format('YmdHis') . ".xlsx";

        $produk = $produk->sortBy('nama_produk');
        $penelitian = $penelitian->sortBy('judul');

        // Tambahkan kolom 'No.' untuk nomor urut
        $produkData = collect([
            ['Produk'],
            ['No', 'No Id',  'Nama KBK', 'Nama Produk', 'Inventor', 'Tanggal Submit', 'Tanggal Granted', 'Status']
        ]);

        $no = 1;
        foreach ($produk as $item) {
            $inventor = $item->inventor ?: $item->inventor_lainnya;
            $produkData->push([
                $no++,  // Menambah nomor urut secara manual
                $item->id,
                $item->kelompokKeahlian->nama_kbk ?? '-',
                $item->nama_produk,
                $inventor,
                $item->tanggal_submit ? Carbon::parse($item->tanggal_submit)->format('d-m-Y') : '-',
                $item->tanggal_granted ? Carbon::parse($item->tanggal_granted)->format('d-m-Y') : '-',
                $item->status,
            ]);
        }

        // Penelitian dengan nomor urut
        $penelitianData = collect([
            [],
            ['Penelitian'],
            ['No', 'No Id', 'Nama KBK', 'Judul Penelitian', 'Penulis', 'Penulis Korespondensi', 'Tanggal Publikasi', 'Status']
        ]);

        $no = 1; // Reset nomor urut untuk data penelitian
        foreach ($penelitian as $item) {
            $penulis = $item->penulis ?: $item->penulis_lainnya;
            $penelitianData->push([
                $no++,  // Menambah nomor urut secara manual
                $item->id,
                $item->kelompokKeahlian->nama_kbk ?? '-',
                $item->judul,
                $penulis,
                $item->penulis_korespondensi,
                $item->tanggal_publikasi ? Carbon::parse($item->tanggal_publikasi)->format('d-m-Y') : '-',
                $item->status,
            ]);
        }

        $data = $produkData->merge($penelitianData);

        return (new FastExcel($data))->download($filename);
    }



    // Admin
    public function admin()
    {
        $kbk_navigasi = DB::table('kelompok_keahlians')
            ->select(
                'kelompok_keahlians.id',
                'kelompok_keahlians.nama_kbk'
            )
            ->get();
        $admin = User::where('role', 'admin')->get();
        return view('admin.admin-page.index', compact('admin', 'kbk_navigasi'));
    }
    public function showAdmin(string $id)
    {
        $admin = User::find($id);
        $kbk_navigasi = DB::table('kelompok_keahlians')
            ->select(
                'kelompok_keahlians.id',
                'kelompok_keahlians.nama_kbk'
            )
            ->get();
        return view('admin.admin-page.show.index', compact('admin', 'kbk_navigasi'));
    }

    public function storeAdmin(Request $request)
    {
        $validasi = $request->validate([
            'nama_lengkap' => 'required|string',
            'nip' => 'required|numeric|digits_between:1,20',
            'no_hp' => 'required',
            'pas_foto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'email' => 'required|email|unique:users',
            'jabatan' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);
        $admin = new User();
        $admin->nama_lengkap = $request->nama_lengkap;
        $admin->nip = $request->nip;
        $admin->no_hp = $request->no_hp;
        $admin->email = $request->email;
        if ($request->hasFile('pas_foto')) {
            $originalName = $request->file('pas_foto')->getClientOriginalName();
            $fileName = time() . '_' . str_replace(' ', '_', $originalName);

            // Simpan file ke storage/app/public/dokumen-user
            $path = $request->file('pas_foto')->storeAs('dokumen-user', $fileName);

            // Simpan path utuh di database
            $admin->pas_foto = $path;
        }
        $admin->jabatan = $request->jabatan;
        $admin->username = $request->username;
        $admin->password = Hash::make($request->password); // Hashing password
        $admin->save($validasi);

        return redirect('/admin/admin-page')->with('success', 'Data admin berhasil ditambahkan!');
    }

    public function updateAdmin(Request $request, $id)
    {
        $validasi = $request->validate([
            'nama_lengkap' => 'required|string',
            'nip' => 'required|numeric|digits_between:1,20',
            'no_hp' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'jabatan' => 'required',
            'username' => 'required',
            'password' => 'nullable|min:3',
            'pas_foto' => 'file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $admin = User::find($id);
        $admin->nama_lengkap = $request->nama_lengkap;
        $admin->nip = $request->nip;
        $admin->no_hp = $request->no_hp;
        $admin->email = $request->email;
        $admin->jabatan = $request->jabatan;
        $admin->username = $request->username;
        if ($request->hasFile('pas_foto')) {
            // Hapus foto lama jika ada
            if ($admin->pas_foto && Storage::exists($admin->pas_foto)) {
                Storage::delete($admin->pas_foto);
            }

            // Simpan foto baru
            $originalName = $request->file('pas_foto')->getClientOriginalName();
            $fileName = time() . '_' . str_replace(' ', '_', $originalName);
            $path = $request->file('pas_foto')->storeAs('dokumen-user', $fileName);

            // Simpan path ke database
            $admin->pas_foto = $path;
        }
        if ($request->password) {
            $admin->password = Hash::make($request->password);
        } // Hashing password
        $admin->save($validasi);

        return redirect('/admin/admin-page')->with('success', 'Data admin berhasil diupdate!');
    }

    public function deleteAdmin($id)
    {
        $admin = User::findOrFail($id);
        $admin->delete();
        return redirect('/admin/admin-page')->with('success', 'Data admin berhasil dihapus');
    }


    // Ketua KBK

    public function ketuaKBK()
    {
        $kbk = User::with('kelompokKeahlian')->where('role', 'ketua_kbk')->paginate(10);
        $jenis_kbk = KelompokKeahlian::all();

        $kbk_navigasi = DB::table('kelompok_keahlians')
            ->select(
                'kelompok_keahlians.id',
                'kelompok_keahlians.nama_kbk'
            )
            ->get();
        return view('admin.ketua-kbk.index', compact('kbk', 'jenis_kbk', 'kbk_navigasi'));
    }

    public function showDataKetuaKbk(string $id)
    {
        $kbk_navigasi = DB::table('kelompok_keahlians')
            ->select(
                'kelompok_keahlians.id',
                'kelompok_keahlians.nama_kbk'
            )
            ->get();

        $k_kbk = User::with('kelompokKeahlian')->find($id);
        // dd($k_kbk->toSql());
        // dd($k_kbk);
        return view('admin.ketua-kbk.show.index', compact('k_kbk', 'kbk_navigasi'));
    }

    public function storeDataKetuaKbk(Request $request)
    {
        if ($request->ajax() && $request->has('check_unique')) {
            $field = $request->field; // Nama kolom yang dicek
            $value = $request->value; // Nilai yang dicek
    
            $exists = User::where($field, $value)->exists();
    
            return response()->json(['exists' => $exists]);
        }

        $validasi = $request->validate([
            'nama_lengkap' => 'required|string|unique:users,nama_lengkap',
            'nip' => 'required|numeric|digits_between:1,20|unique:users',
            'kbk_id' => 'required|exists:kelompok_keahlians,id',
            'no_hp' => 'required',
            'email' => 'required|email|unique:users',
            'jabatan' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:5', // Minimum length for password
            'confirm_password' => 'required|same:password', // Ensure passwords match
            'pas_foto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'role' => 'required',
        ]);

        $k_kbk = new User();
        $k_kbk->nama_lengkap = $request->nama_lengkap;
        $k_kbk->nip = $request->nip;
        $k_kbk->kbk_id = $request->kbk_id;
        $k_kbk->no_hp = $request->no_hp;
        $k_kbk->email = $request->email;
        $k_kbk->jabatan = $request->jabatan;
        if ($request->hasFile('pas_foto')) {
            $originalName = $request->file('pas_foto')->getClientOriginalName();
            $fileName = time() . '_' . str_replace(' ', '_', $originalName);

            // Simpan file ke storage/app/public/dokumen-user
            $path = $request->file('pas_foto')->storeAs('dokumen-user', $fileName);

            // Simpan path utuh di database
            $k_kbk->pas_foto = $path;
        }
        $k_kbk->username = $request->username;
        $k_kbk->password = Hash::make($request->password); // Hashing password
        $k_kbk->role = $request->role;

        $k_kbk->save($validasi);

        return response()->json([
            'success' => true,
            'message' => 'Data ketua Kelompok Keahlian berhasil ditambahkan!',
        ]);
    }

    public function updateKetuaKbk(Request $request, $id)
    {
        if ($request->ajax() && $request->has('check_unique')) {
            $field = $request->field; // Nama kolom yang dicek
            $value = $request->value; // Nilai yang dicek
    
            $exists = User::where($field, $value)
                ->where('id', '!=', $id) 
                ->exists();
    
            return response()->json(['exists' => $exists]);
        }

        $validasi = $request->validate([
            'nama_lengkap' => 'required|string',
            'nip' => 'required|numeric|digits_between:1,20|unique:users,email,' . $id,
            'kbk_id' => 'required|exists:kelompok_keahlians,id',
            'no_hp' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'jabatan' => 'required',
            'username' => 'required',
            'pas_foto' => 'file|mimes:jpg,jpeg,png|max:2048',
            'role' => 'required',
        ]);

        $k_kbk = User::find($id);
        $k_kbk->nama_lengkap = $request->nama_lengkap;
        $k_kbk->nip = $request->nip;
        $k_kbk->kbk_id = $request->kbk_id;
        $k_kbk->no_hp = $request->no_hp;
        $k_kbk->email = $request->email;
        $k_kbk->jabatan = $request->jabatan;
        if ($request->hasFile('pas_foto')) {
            // Hapus foto lama jika ada
            if ($k_kbk->pas_foto && Storage::exists($k_kbk->pas_foto)) {
                Storage::delete($k_kbk->pas_foto);
            }

            // Simpan foto baru
            $originalName = $request->file('pas_foto')->getClientOriginalName();
            $fileName = time() . '_' . str_replace(' ', '_', $originalName);
            $path = $request->file('pas_foto')->storeAs('dokumen-user', $fileName);

            // Simpan path ke database
            $k_kbk->pas_foto = $path;
        }
        $k_kbk->username = $request->username;

        $k_kbk->role = $request->role;

        $k_kbk->save($validasi);

        return response()->json([
            'success' => true,
            'message' => 'Data ketua Kelompok Keahlian berhasil diupdate!',
        ]);
    }

    public function hapusKetuaKbk(string $id)
    {
        $k_kbk = User::findOrFail($id);
        $k_kbk->delete();
        return redirect('/admin/ketua-kbk')->with('success', 'Data ketua Kelompok Keahlian berhasil dihapus');
    }

    public function resetPassword(Request $request, $id)
    {
        // Temukan user berdasarkan ID
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // Reset password menjadi "@Polindra123"
        $user->password = bcrypt('@Polindra123'); // Atau Anda bisa menggunakan Hash::make('@Polindra123');
        $user->save();

        return response()->json(['message' => 'Password berhasil direset menjadi "@Polindra123".']);
    }
}
