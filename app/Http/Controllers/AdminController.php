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

            // Pastikan tahun_awal dan tahun_akhir tidak kosong
            if ($tahun_awal && $tahun_akhir) {
                $queryProduk->whereYear('tanggal_granted', '>=', $tahun_awal)
                    ->whereYear('tanggal_granted', '<=', $tahun_akhir);

                $queryPenelitian->whereYear('tanggal_publikasi', '>=', $tahun_awal)
                    ->whereYear('tanggal_publikasi', '<=', $tahun_akhir);
            }
        } elseif ($request->has('tahun_awal')) {
            $tahun_awal = $request->input('tahun_awal');

            // Jika hanya tahun_awal yang ada
            $queryProduk->whereYear('tanggal_granted', '>=', $tahun_awal);
            $queryPenelitian->whereYear('tanggal_publikasi', '>=', $tahun_awal);
        } elseif ($request->has('tahun_akhir')) {
            $tahun_akhir = $request->input('tahun_akhir');

            // Jika hanya tahun_akhir yang ada
            $queryProduk->whereYear('tanggal_granted', '<=', $tahun_akhir);
            $queryPenelitian->whereYear('tanggal_publikasi', '<=', $tahun_akhir);
        }

        $produk = $queryProduk->get();
        $penelitian = $queryPenelitian->get();
        // dd($penelitian);

        // Cek apakah pengguna ingin mengunduh CSV
        if ($request->has('download') && $request->input('download') == 'csv') {
            return $this->downloadCSV($produk, $penelitian);
        }


        return view('admin.index', compact('kbk_navigasi', 'pnltan_valid', 'pnltan_nonvalid', 'prdk_valid', 'prdk_nonvalid', 'prdk_tahun', 'plt_tahun', 'data_plt', 'data_prdk', 'produk', 'penelitian'));
    }
    protected function downloadCSV($produk, $penelitian)
    {
        $filename = "report_" . now()->format('YmdHis') . ".csv";
        $columnsProduk = ['Nama KBK', 'Nama Produk', 'Inventor', 'Tanggal Submit', 'Tanggal Granted', 'Status'];
        $columnsPenelitian = ['Nama KBK', 'Judul Penelitian', 'Penulis', 'Penulis Korespondensi', 'Tanggal Publikasi', 'Status'];

        $callback = function () use ($produk, $penelitian, $columnsProduk, $columnsPenelitian) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_merge(['Produk'], $columnsProduk));

            foreach ($produk as $item) {
                fputcsv($file, [
                    $item->kelompokKeahlian->nama_kbk ?? '-', // Nama KBK
                    $item->nama_produk,
                    $item->inventor,
                    $item->tanggal_submit,
                    $item->tanggal_granted,
                    $item->status
                ]);
            }

            fputcsv($file, []);
            fputcsv($file, array_merge(['Penelitian'], $columnsPenelitian));

            foreach ($penelitian as $item) {
                fputcsv($file, [
                    $item->kelompokKeahlian->nama_kbk ?? '-', // Nama KBK
                    $item->judul,
                    $item->penulis,
                    $item->penulis_koresponden,
                    $item->tanggal_publikasi,
                    $item->status
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ]);
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

        $validasi = $request->validate([
            'nama_lengkap' => 'required|string',
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

        return redirect('/admin/ketua-kbk')->with('success', 'Data ketua Kelompok Keahlian berhasil ditambahkan!');
    }

    public function updateKetuaKbk(Request $request, $id)
    {
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

        return redirect('/admin/ketua-kbk')->with('success', 'Data ketua Kelompok Keahlian berhasil diupdate!');
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
