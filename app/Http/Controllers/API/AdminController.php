<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\KelompokKeahlianResource;
use App\Http\Resources\PenelitianResource;
use App\Http\Resources\ProdukResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Produk;
use App\Models\Penelitian;
use Illuminate\Http\Request;
use App\Models\KelompokKeahlian;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AdminController extends BaseController
{
    public function dashboard(Request $request): JsonResponse
    {
        // Mendapatkan navigasi KBK
        $kbk_navigasi = KelompokKeahlian::select('id', 'nama_kbk')->get();

        // Menghitung produk dan penelitian tervalidasi dan tidak tervalidasi
        $prdk_valid = Produk::where('status', 'Tervalidasi')->count();
        $prdk_nonvalid = Produk::where('status', 'Belum Divalidasi')->count();
        $pnltan_valid = Penelitian::where('status', 'Tervalidasi')->count();
        $pnltan_nonvalid = Penelitian::where('status', 'Belum Divalidasi')->count();

        // Menghitung produk per tahun granted
        $prdk_tahun = Produk::whereNotNull('tanggal_granted')
            ->where('status', 'Tervalidasi')
            ->get()
            ->groupBy(fn ($item) => $item->tanggal_granted->format('Y'))
            ->map(fn ($group) => $group->count())
            ->sortKeys();

        // Menghitung penelitian per tahun publikasi
        $plt_tahun = Penelitian::whereNotNull('tanggal_publikasi')
            ->where('status', 'Tervalidasi')
            ->get()
            ->groupBy(fn ($item) => $item->tanggal_publikasi->format('Y'))
            ->map(fn ($group) => $group->count())
            ->sortKeys();

        // Filter data berdasarkan rentang tanggal
        $penelitians = Penelitian::query();
        $produks = Produk::query();

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $penelitians->whereBetween('tanggal_publikasi', [$startDate, $endDate]);
            $produks->whereBetween('tanggal_submit', [$startDate, $endDate]);
        }

        // Data publikasi penelitian per hari
        $data_plt = $penelitians->selectRaw('DATE(tanggal_publikasi) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // Data submit produk per hari
        $data_prdk = $produks->selectRaw('DATE(tanggal_submit) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // Filter berdasarkan tahun dan status validasi
        if ($request->has('tahun_awal') && $request->has('tahun_akhir')) {
            $tahun_awal = $request->input('tahun_awal');
            $tahun_akhir = $request->input('tahun_akhir');

            $produks->whereYear('tanggal_granted', '>=', $tahun_awal)
                ->whereYear('tanggal_granted', '<=', $tahun_akhir);

            $penelitians->whereYear('tanggal_publikasi', '>=', $tahun_awal)
                ->whereYear('tanggal_publikasi', '<=', $tahun_akhir);
        }

        if ($request->has('status_validasi') && $request->input('status_validasi') != '') {
            $status_validasi = $request->input('status_validasi');
            $produks->where('status', $status_validasi);
            $penelitians->where('status', $status_validasi);
        }

        // Paginate produk dan penelitian
        $produk_paginate = $produks->paginate(5);
        $penelitian_paginate = $penelitians->paginate(5);

        // Cek apakah pengguna ingin mengunduh dalam format Excel
        if ($request->has('download') && $request->input('download') == 'Excel') {
            return $this->downloadExcel($produks->get(), $penelitians->get());
        }

        // Mengembalikan data dalam format JSON menggunakan resource
        return response()->json([
            'status' => 'success',
            'message' => 'Data dashboard berhasil diambil.',
            'data' => [
                'kbk_navigasi' => KelompokKeahlianResource::collection($kbk_navigasi),
                'produk_tervalidasi' => $prdk_valid,
                'produk_nonvalid' => $prdk_nonvalid,
                'penelitian_tervalidasi' => $pnltan_valid,
                'penelitian_nonvalid' => $pnltan_nonvalid,
                'produk_per_tahun' => $prdk_tahun,
                'penelitian_per_tahun' => $plt_tahun,
                'produk_paginate' => ProdukResource::collection($produk_paginate),
                'penelitian_paginate' => PenelitianResource::collection($penelitian_paginate),
            ],
        ], 200);
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
                $item->PenulisKorespondensi->nama_lengkap,
                $item->tanggal_publikasi ? Carbon::parse($item->tanggal_publikasi)->format('d-m-Y') : '-',
                $item->status,
            ]);
        }

        $data = $produkData->merge($penelitianData);

        return (new FastExcel($data))->download($filename);
    }



    // Admin
    public function admin(): JsonResponse
    {
        // Mengambil semua data admin dengan role 'admin'
        $admins = User::where('role', 'admin')->get();

        // Mengambil data KBK navigasi
        $kbk_navigasi = KelompokKeahlian::select('id', 'nama_kbk')->get();

        // Mengembalikan data dalam format JSON
        return response()->json([
            'status' => 'success',
            'message' => 'Data admin dan KBK navigasi berhasil diambil.',
            'data' => [
                'admins' => UserResource::collection($admins),
                'kbk_navigasi' => KelompokKeahlianResource::collection($kbk_navigasi),
            ]
        ], 200);
    }

    public function showAdmin(string $id): JsonResponse
    {
        // Mengambil data admin berdasarkan ID
        $admin = User::find($id);

        // Mengambil data KBK navigasi
        $kbk_navigasi = KelompokKeahlian::select('id', 'nama_kbk')->get();

        // Cek jika admin tidak ditemukan
        if (!$admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Admin tidak ditemukan.'
            ], 404);
        }

        // Mengembalikan data dalam format JSON
        return response()->json([
            'status' => 'success',
            'message' => 'Data admin berhasil diambil.',
            'data' => [
                'admin' => new UserResource($admin),
                'kbk_navigasi' => KelompokKeahlianResource::collection($kbk_navigasi),
            ]
        ], 200);
    }

    public function storeAdmin(Request $request): JsonResponse
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'required|numeric|digits_between:1,20|unique:users,nip',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|unique:users,email',
            'jabatan' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:5',
            'pas_foto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Cek jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Menyimpan data input
        $input = $request->only([
            'nama_lengkap',
            'nip',
            'no_hp',
            'email',
            'jabatan',
            'username'
        ]);
        $input['password'] = Hash::make($request->password);

        // Proses upload file pas_foto
        if ($request->hasFile('pas_foto')) {
            $originalName = $request->file('pas_foto')->getClientOriginalName();
            $fileName = time() . '_' . str_replace(' ', '_', $originalName);

            // Simpan file ke storage/app/public/dokumen-user
            $path = $request->file('pas_foto')->storeAs('dokumen-user', $fileName);
            $input['pas_foto'] = $path; // Menyimpan path di database
        }

        // Buat pengguna baru
        $admin = User::create($input);

        // Buat token akses
        $token = $admin->createToken('AdminRegisterToken')->plainTextToken;

        // Mengembalikan respons sukses dengan data admin dan token
        return response()->json([
            'status' => 'success',
            'message' => 'Admin berhasil ditambahkan.',
            'data' => [
                'token' => $token,
                'user' => new UserResource($admin),
            ],
        ], 201);
    }


    public function updateAdmin(Request $request, $id): JsonResponse
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'required|numeric|digits_between:1,20|unique:users,nip,' . $id,
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|unique:users,email,' . $id,
            'jabatan' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|min:3',
            'pas_foto' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Cek jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Cari data admin berdasarkan ID
        $admin = User::find($id);

        if (!$admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Admin tidak ditemukan.',
            ], 404);
        }

        // Update data admin
        $admin->nama_lengkap = $request->nama_lengkap;
        $admin->nip = $request->nip;
        $admin->no_hp = $request->no_hp;
        $admin->email = $request->email;
        $admin->jabatan = $request->jabatan;
        $admin->username = $request->username;

        // Update foto jika ada file baru
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

        // Update password jika diisi
        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }

        // Simpan perubahan
        $admin->save();

        // Berikan respons sukses menggunakan UserResource
        return response()->json([
            'status' => 'success',
            'message' => 'Data admin berhasil diupdate.',
            'data' => new UserResource($admin),
        ], 200);
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

    public function storeDataKetuaKbk(Request $request): JsonResponse
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'required|numeric|digits_between:1,20|unique:users',
            'kbk_id' => 'required|exists:kelompok_keahlians,id',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|unique:users,email',
            'jabatan' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:5',
            'confirm_password' => 'required|same:password',
            'pas_foto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'role' => 'required|string',
        ]);

        // Cek jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Menyimpan data
        $input = $request->all();
        $input['password'] = Hash::make($request->password);

        // Proses upload file pas_foto
        if ($request->hasFile('pas_foto')) {
            $originalName = $request->file('pas_foto')->getClientOriginalName();
            $fileName = time() . '_' . str_replace(' ', '_', $originalName);

            // Simpan file ke storage/app/public/dokumen-user
            $path = $request->file('pas_foto')->storeAs('dokumen-user', $fileName);
            $input['pas_foto'] = $path; // Menyimpan path di database
        }

        // Buat pengguna baru
        $user = User::create($input);

        // Buat token akses
        $token = $user->createToken('KetuaKbkRegisterToken')->plainTextToken;

        // Menggunakan UserResource untuk mengembalikan respons dengan token
        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully.',
            'data' => [
                'token' => $token,
                'user' => new UserResource($user),
            ],
        ], 201);
    }

    public function updateKetuaKbk(Request $request, $id)
    {
        // Validasi request
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string',
            'nip' => 'required|numeric|digits_between:1,20|unique:users,nip,' . $id, // Perbaikan unik ke kolom nip
            'kbk_id' => 'required|exists:kelompok_keahlians,id',
            'no_hp' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'jabatan' => 'required|string',
            'username' => 'required|string',
            'pas_foto' => 'file|mimes:jpg,jpeg,png|max:2048',
            'role' => 'required|string',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Error.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Temukan user berdasarkan ID
        $k_kbk = User::find($id);

        if (!$k_kbk) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.',
            ], 404);
        }

        // Update data
        $k_kbk->nama_lengkap = $request->nama_lengkap;
        $k_kbk->nip = $request->nip;
        $k_kbk->kbk_id = $request->kbk_id;
        $k_kbk->no_hp = $request->no_hp;
        $k_kbk->email = $request->email;
        $k_kbk->jabatan = $request->jabatan;
        $k_kbk->username = $request->username;
        $k_kbk->role = $request->role;

        // Jika ada file pas_foto yang diunggah
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

        // Simpan data yang diperbarui ke database
        $k_kbk->save();

        // Kembalikan respons JSON menggunakan UserResource
        return response()->json([
            'status' => 'success',
            'message' => 'Data Ketua KBK berhasil diperbarui.',
            'data' => new UserResource($k_kbk)
        ], 200);
    }


    public function hapusKetuaKbk(string $id)
    {
        // Cari user berdasarkan ID
        $k_kbk = User::find($id);

        // Jika user tidak ditemukan, kembalikan respons 404
        if (!$k_kbk) {
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        // Hapus user dari database
        $k_kbk->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data Ketua KBK berhasil dihapus.',
        ], 200);
    }

    public function resetPassword(Request $request, string $id)
    {
        // Cari user berdasarkan ID
        $user = User::find($id);

        // Jika user tidak ditemukan, kembalikan respons 404
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        // Reset password menjadi "@Polindra123"
        $user->password = Hash::make('@Polindra123');
        $user->save();

        // Kembalikan data user yang telah direset dengan UserResource
        return response()->json([
            'status' => 'success',
            'message' => 'Password berhasil direset menjadi "@Polindra123".',
            'data' => new UserResource($user)
        ], 200);
    }
}
