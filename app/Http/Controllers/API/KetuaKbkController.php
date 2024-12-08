<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\AnggotaKelompokKeahlianResource;
use App\Http\Resources\KelompokKeahlianResource;
use App\Http\Resources\PenelitianResource;
use App\Http\Resources\ProdukResource;
use App\Models\User;
use App\Models\Produk;
use App\Models\Penelitian;
use Illuminate\Http\Request;
use App\Models\ProdukAnggota;
use App\Models\KelompokKeahlian;
use App\Models\PenelitianAnggota;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Models\AnggotaKelompokKeahlian;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KetuaKbkController extends BaseController
{
    public function dashboardPage()
    {
        // Ambil ID Kelompok Keahlian dari user yang sedang login
        $kelompokKeahlianId = Auth::user()->kelompokKeahlian->id ?? null;

        // Data Produk per Tahun
        $prdk_tahun = Produk::whereNotNull('tanggal_granted')
            ->where('status', 'Tervalidasi')
            ->where('kbk_id', $kelompokKeahlianId)
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal_granted->format('Y');
            })
            ->map(function ($group) {
                return $group->count();
            });

        // Data Penelitian per Tahun
        $plt_tahun = Penelitian::whereNotNull('tanggal_publikasi')
            ->where('status', 'Tervalidasi')
            ->where('kbk_id', $kelompokKeahlianId)
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal_publikasi->format('Y');
            })
            ->map(function ($group) {
                return $group->count();
            });

        // Distribusi Status Produk
        $prdk_valid = Produk::where('kbk_id', $kelompokKeahlianId)->where('status', 'Tervalidasi')->count();
        $prdk_nonvalid = Produk::where('kbk_id', $kelompokKeahlianId)->where('status', 'Belum Divalidasi')->count();

        // Distribusi Status Penelitian
        $pnltan_valid = Penelitian::where('kbk_id', $kelompokKeahlianId)->where('status', 'Tervalidasi')->count();
        $pnltan_nonvalid = Penelitian::where('kbk_id', $kelompokKeahlianId)->where('status', 'Belum Divalidasi')->count();

        // Data Kelompok Keahlian
        $kelompokKeahlian = Auth::user()->kelompokKeahlian;

        // Format Data menggunakan Resource
        return response()->json([
            'kelompok_keahlian' => new KelompokKeahlianResource($kelompokKeahlian),
            'produk_per_tahun' => $prdk_tahun,
            'penelitian_per_tahun' => $plt_tahun,
            'produk_valid' => $prdk_valid,
            'produk_nonvalid' => $prdk_nonvalid,
            'penelitian_valid' => $pnltan_valid,
            'penelitian_nonvalid' => $pnltan_nonvalid
        ], 200);
    }

    public function anggotaPage()
    {
        // Ambil ID Kelompok Keahlian dari user yang sedang login
        $kelompokKeahlianId = Auth::user()->kelompokKeahlian->id ?? null;

        // Jika user tidak memiliki Kelompok Keahlian, kembalikan error
        if (!$kelompokKeahlianId) {
            return response()->json([
                'message' => 'User tidak memiliki Kelompok Keahlian yang terkait.'
            ], 404);
        }

        // Ambil data anggota dari Kelompok Keahlian yang sesuai dengan paginasi
        $anggotas = AnggotaKelompokKeahlian::with('kelompokKeahlian')
            ->where('kbk_id', $kelompokKeahlianId)
            ->paginate(10);

        // Ambil informasi user dan Kelompok Keahlian terkait
        $userId = Auth::id();
        $kkbk = DB::table('users')
            ->join('kelompok_keahlians', 'users.kbk_id', '=', 'kelompok_keahlians.id')
            ->select(
                'kelompok_keahlians.id',
                'kelompok_keahlians.nama_kbk',
                'users.nama_lengkap'
            )
            ->where('users.id', '=', $userId)
            ->first();

        // Format data dengan Resource
        return response()->json([
            'kelompok_keahlian' => [
                'id' => $kkbk->id ?? null,
                'nama_kbk' => $kkbk->nama_kbk ?? null,
                'ketua' => $kkbk->nama_lengkap ?? null,
            ],
            'anggotas' => AnggotaKelompokKeahlianResource::collection($anggotas)
                ->response()
                ->getData(true), // Menggunakan paginasi bawaan Laravel
        ], 200);
    }

    public function storeAnggota(Request $request)
    {
        try {
            // Validasi input
            $validatedData = $request->validate(
                [
                    'nama_lengkap' => 'required|string|max:255',
                    'jabatan' => 'required|string|max:255',
                    'kbk_id' => 'required|exists:kelompok_keahlians,id',
                    'email' => 'required|email',
                ],
                [
                    'nama_lengkap.required' => 'Nama Lengkap anggota harus diisi.',
                    'jabatan.required' => 'Jabatan anggota harus diisi.',
                    'kbk_id.required' => 'Kelompok Keahlian harus diisi.',
                    'kbk_id.exists' => 'Kelompok Keahlian yang dipilih tidak valid.',
                    'email.required' => 'Email harus diisi.',
                    'email.email' => 'Masukkan email yang valid.',
                ]
            );

            // Simpan data anggota baru
            $anggota = AnggotaKelompokKeahlian::create($validatedData);

            // Kembalikan response dengan resource
            return response()->json([
                'success' => true,
                'message' => 'Data anggota berhasil disimpan.',
                'data' => new AnggotaKelompokKeahlianResource($anggota)
            ], 201);
        } catch (\Exception $e) {
            // Tangani error lainnya
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateAnggota(Request $request, $id)
    {
        try {
            // Validasi input
            $request->validate(
                [
                    'nama_lengkap' => 'required|string|max:255',
                    'jabatan' => 'required|string|max:255',
                    'email' => 'required|email',
                ],
                [
                    'nama_lengkap.required' => 'Nama Lengkap anggota harus diisi',
                    'jabatan.required' => 'Jabatan anggota harus diisi',
                    'email.required' => 'Email harus diisi',
                    'email.email' => 'Masukkan email yang valid'
                ]
            );

            // Cari data anggota berdasarkan ID
            $anggota = AnggotaKelompokKeahlian::findOrFail($id);

            // Update data anggota
            $anggota->update([
                'nama_lengkap' => $request->input('nama_lengkap'),
                'jabatan' => $request->input('jabatan'),
                'email' => $request->input('email'),
            ]);

            // Return response dengan resource
            return response()->json([
                'success' => true,
                'message' => 'Data anggota berhasil diupdate.',
                'data' => new AnggotaKelompokKeahlianResource($anggota)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan : ' . $e->getMessage()
            ], 500);
        }
    }


    public function hapusAnggota($id)
    {
        try {
            // Cari data anggota berdasarkan ID
            $anggota = AnggotaKelompokKeahlian::findOrFail($id);

            // Hapus data anggota
            $anggota->delete();

            // Return response JSON
            return response()->json([
                'success' => true,
                'message' => 'Data anggota berhasil dihapus.'
            ], 200);
        } catch (\Exception $e) {
            // Tangani error jika terjadi
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function produkInovasi()
    {
        try {
            // Ambil ID Kelompok Keahlian dari pengguna yang sedang login
            $kelompokKeahlianId = Auth::user()->kelompokKeahlian->id ?? null;

            // Ambil data produk berdasarkan kelompok keahlian ID
            $produks = Produk::with('kelompokKeahlian')
                ->where('kbk_id', $kelompokKeahlianId)
                ->paginate(10);

            // Ambil data user terkait KBK
            $userId = Auth::id();
            $kkbk = DB::table('users')
                ->join('kelompok_keahlians', 'users.kbk_id', '=', 'kelompok_keahlians.id')
                ->select(
                    'kelompok_keahlians.id',
                    'kelompok_keahlians.nama_kbk',
                    'users.nama_lengkap'
                )
                ->where('users.id', '=', $userId)
                ->first();

            // Data anggota kelompok
            $anggotaKelompok = AnggotaKelompokKeahlian::where('kbk_id', $kelompokKeahlianId)->get();

            // Inventor Ketua KBK
            $inventorK = DB::table('users')
                ->select('id', 'nama_lengkap', 'jabatan')
                ->where('role', '=', 'ketua_kbk')
                ->get();

            // Inventor Anggota KBK
            $inventorA = DB::table('anggota_kelompok_keahlians')
                ->join('kelompok_keahlians', 'anggota_kelompok_keahlians.kbk_id', '=', 'kelompok_keahlians.id')
                ->select(
                    'anggota_kelompok_keahlians.id as id',
                    'anggota_kelompok_keahlians.nama_lengkap as nama_lengkap',
                    'anggota_kelompok_keahlians.jabatan as jabatan',
                    'kelompok_keahlians.nama_kbk as nama_kbk'
                )
                ->where('anggota_kelompok_keahlians.kbk_id', $kelompokKeahlianId)
                ->get();

            // Gabungkan data inventor
            $inventors = $inventorK->merge($inventorA);

            // Response JSON
            return response()->json([
                'success' => true,
                'data' => [
                    'produks' => ProdukResource::collection($produks), // Gunakan resource untuk format produk
                    'kkbk' => $kkbk,
                    'anggota_kelompok' => AnggotaKelompokKeahlianResource::collection($anggotaKelompok), // Gunakan resource untuk format anggota
                    'inventors' => $inventors
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showProduk($id)
    {
        try {
            // Ambil data produk berdasarkan ID, termasuk relasi yang dibutuhkan
            $produk = Produk::with(['kelompokKeahlian', 'anggota.detail'])->findOrFail($id);

            // Format data menggunakan Resource
            return response()->json([
                'success' => true,
                'data' => new ProdukResource($produk)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeProduk(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'nama_produk' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'kbk_id' => 'required|exists:kelompok_keahlians,id',
                'inventor' => 'nullable|string|max:255',
                'inventor_lainnya' => 'nullable|string|max:255',
                'anggota_inventor' => 'array',
                'anggota_inventor.*' => 'string',
                'anggota_inventor_lainnya' => 'nullable|string|max:255',
                'email_inventor' => 'required|email',
                'gambar' => 'required|file|mimes:jpeg,png,jpg|max:10240',
                'lampiran' => 'nullable|file|mimes:jpeg,png,jpg,pdf,docx|max:10240',
                'tanggal_submit' => 'nullable|date',
                'tanggal_granted' => 'nullable|date',
            ]);

            DB::beginTransaction();

            // Buat entitas produk
            $produk = new Produk();
            $produk->fill($request->only([
                'kbk_id',
                'nama_produk',
                'deskripsi',
                'inventor',
                'inventor_lainnya',
                'anggota_inventor_lainnya',
                'email_inventor',
                'tanggal_submit',
                'tanggal_granted',
            ]));

            // Proses upload gambar
            if ($request->hasFile('gambar')) {
                $fileName = time() . '_' . $request->file('gambar')->getClientOriginalName();
                $path = $request->file('gambar')->storeAs('dokumen-produk', $fileName);
                $produk->gambar = $path;
            }

            // Proses upload lampiran
            if ($request->hasFile('lampiran')) {
                $fileName = time() . '_' . $request->file('lampiran')->getClientOriginalName();
                $path = $request->file('lampiran')->storeAs('dokumen-produk', $fileName);
                $produk->lampiran = $path;
            }

            $produk->save();

            // Proses anggota inventor
            if ($request->has('anggota_inventor') && is_array($request->anggota_inventor)) {
                foreach ($request->anggota_inventor as $anggota) {
                    $anggotaId = null;
                    $table = null;

                    if (str_starts_with($anggota, 'user_')) {
                        $anggotaId = str_replace('user_', '', $anggota);
                        $table = 'users';
                    } elseif (str_starts_with($anggota, 'anggota_')) {
                        $anggotaId = str_replace('anggota_', '', $anggota);
                        $table = 'anggota_kelompok_keahlians';
                    }

                    if ($anggotaId && $table) {
                        ProdukAnggota::create([
                            'produk_id' => $produk->id,
                            'anggota_id' => $anggotaId,
                            'anggota_type' => $table,
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data produk berhasil ditambahkan!',
                'data' => new ProdukResource($produk)
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function updateProdukInovasi(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'inventor' => 'nullable|string|max:255',
            'inventor_lainnya' => 'nullable|string|max:255',
            'anggota_inventor' => 'nullable|array',
            'anggota_inventor.*' => 'string',
            'anggota_inventor_lainnya' => 'nullable|string|max:255',
            'email_inventor' => 'required|email',
            'gambar' => 'nullable|file|mimes:jpeg,png,jpg|max:10240',
            'lampiran' => 'nullable|file|mimes:jpeg,png,jpg,pdf,docx|max:10240',
            'tanggal_submit' => 'nullable|date',
            'tanggal_granted' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();

            $produk = Produk::findOrFail($id);

            $produk->nama_produk = $request->nama_produk;
            $produk->deskripsi = $request->deskripsi;
            $produk->inventor = $request->inventor;
            $produk->inventor_lainnya = $request->inventor_lainnya;
            $produk->anggota_inventor_lainnya = $request->anggota_inventor_lainnya;
            $produk->email_inventor = $request->email_inventor;
            $produk->tanggal_submit = $request->tanggal_submit;
            $produk->tanggal_granted = $request->tanggal_granted;

            if ($request->hasFile('gambar')) {
                if ($produk->gambar && Storage::exists($produk->gambar)) {
                    Storage::delete($produk->gambar);
                }

                $originalName = $request->file('gambar')->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);
                $path = $request->file('gambar')->storeAs('dokumen-produk', $fileName);
                $produk->gambar = $path;
            }

            if ($request->hasFile('lampiran')) {
                if ($produk->lampiran && Storage::exists($produk->lampiran)) {
                    Storage::delete($produk->lampiran);
                }

                $originalName = $request->file('lampiran')->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);
                $path = $request->file('lampiran')->storeAs('dokumen-produk', $fileName);
                $produk->lampiran = $path;
            }

            $produk->save();

            if ($request->filled('anggota_inventor')) {
                ProdukAnggota::where('produk_id', $produk->id)->delete();

                foreach ($request->anggota_inventor as $anggota) {
                    if (str_starts_with($anggota, 'user_')) {
                        $anggotaId = str_replace('user_', '', $anggota);
                        $table = 'users';
                    } elseif (str_starts_with($anggota, 'anggota_')) {
                        $anggotaId = str_replace('anggota_', '', $anggota);
                        $table = 'anggota_kelompok_keahlians';
                    } else {
                        continue;
                    }

                    ProdukAnggota::create([
                        'produk_id' => $produk->id,
                        'anggota_id' => $anggotaId,
                        'anggota_type' => $table,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data produk berhasil diupdate!',
                'data' => new ProdukResource($produk->load('kelompokKeahlian', 'anggota'))
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }



    public function hapusProduk($id)
    {
        try {
            $produk = Produk::findOrFail($id);

            if ($produk->gambar && Storage::exists($produk->gambar)) {
                Storage::delete($produk->gambar);
            }

            if ($produk->lampiran && Storage::exists($produk->lampiran)) {
                Storage::delete($produk->lampiran);
            }

            $produk->delete();

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Data Produk berhasil dihapus!'
            ], 200);

        } catch (\Exception $e) {
            // Handle errors and return a failure response
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    //penelitian method
    public function penelitian()
    {
        try {

            $kelompokKeahlianId = Auth::user()->kelompokKeahlian->id ?? null;

            $penelitians = Penelitian::with('kelompokKeahlian')
            ->where('kbk_id', $kelompokKeahlianId)
            ->get();

            $userId = Auth::id();
            $kkbk = DB::table('users')
                ->join('kelompok_keahlians', 'users.kbk_id', '=', 'kelompok_keahlians.id')
                ->select(
                    'kelompok_keahlians.id',
                    'kelompok_keahlians.nama_kbk',
                    'users.nama_lengkap'
                )
                ->where('users.id', '=', $userId)
                ->first();

            $anggotaKelompok = AnggotaKelompokKeahlian::where('kbk_id', $kelompokKeahlianId)->get();

            $penelitianAnggota = AnggotaKelompokKeahlian::all();

            return response()->json([
                'success' => true,
                'data' => [
                    'penelitians' => PenelitianResource::collection($penelitians), // Menggunakan resource untuk format penelitian
                    'kkbk' => $kkbk,
                    'anggota_kelompok' => AnggotaKelompokKeahlianResource::collection($anggotaKelompok), // Menggunakan resource untuk format anggota
                    'penelitianAnggota' => $penelitianAnggota
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showPenelitian($id)
    {
        try {
            $penelitian = Penelitian::with(['kelompokKeahlian', 'anggotaPenelitian.detailAnggota'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => new PenelitianResource($penelitian) // Menggunakan resource PenelitianResource
            ], 200);
        } catch (\Exception $e) {
            // Menangani error jika data tidak ditemukan atau terjadi kesalahan lainnya
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storePenelitian(Request $request)
    {
        try {
            // Validasi input

            $request->validate([
                'judul' => 'required|string|max:255',
                'abstrak' => 'required|string',
                'kbk_id' => 'required',
                'penulis' => 'required|string|max:255',
                'email_penulis' => 'required|email',
                'penulis_korespondensi' => 'required|string',
                'gambar' => 'required|file|mimes:jpeg,png,jpg|max:10240', // Sesuaikan dengan format file yang diperbolehkan
                'lampiran' => 'required|file|mimes:jpeg,png,jpg,pdf,docx|max:10240',
                'tanggal_publikasi' => 'date'
            ], [
                'judul.required' => 'Judul penelitian wajib diisi.',
                'kbk_id.required' => 'Harap Isi Kbk',
                'penulis.required' => 'Nama penulis wajib diisi.',
                'email_penulis.required' => 'Email penulis wajib diisi.',
                'email_penulis.email' => 'Email penulis tidak valid.',
                'abstrak.required' => 'Abstrak penelitian wajib diisi.',
                'gambar.required' => 'Gambar penelitian penelitian wajib diisi.',
                'lampiran.required' => 'Lampiran penelitian wajib diisi.',
                'gambar.max' => 'Ukuran file gambar maksimal 10MB.',
                'lampiran.max' => 'Ukuran file lampiran maksimal 10MB.',
                'gambar.mimes' => 'File gambar harus berupa JPG, JPEG, atau PNG.',
                'lampiran.mimes' => 'File lampiran harus berupa JPG, JPEG, PNG, PDF, atau DOCX.',
            ]);

            DB::beginTransaction();

            // Membuat data penelitian
            $penelitian = new Penelitian();
            $penelitian->judul = $request->judul;
            $penelitian->kbk_id = $request->kbk_id;
            $penelitian->abstrak = $request->abstrak;
            $penelitian->penulis = $request->penulis;
            $penelitian->email_penulis = $request->email_penulis;
            $penelitian->penulis_korespondensi = $request->penulis_korespondensi;
            $penelitian->tanggal_publikasi = $request->tanggal_publikasi;

            // Proses upload gambar
            if ($request->hasFile('gambar')) {
                $originalName = $request->file('gambar')->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);
                // Simpan file gambar
                $path = $request->file('gambar')->storeAs('dokumen-penelitian', $fileName);
                // Simpan path gambar ke database
                $penelitian->gambar = $path;
            }

            // Proses upload lampiran
            if ($request->hasFile('lampiran')) {
                $originalName = $request->file('lampiran')->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);
                // Simpan file lampiran
                $path = $request->file('lampiran')->storeAs('dokumen-penelitian', $fileName);
                // Simpan path lampiran ke database
                $penelitian->lampiran = $path;
            }

            // Menyimpan penelitian
            $penelitian->save();

            // Menyimpan anggota penulis
            foreach ($request->anggota_penulis as $anggota) {
                PenelitianAnggota::create([
                    'penelitian_id' => $penelitian->id,
                    'anggota_id' => $anggota
                ]);
            }

            DB::commit();

            // Response JSON sukses dengan menggunakan resource
            return response()->json([
                'success' => true,
                'message' => 'Penelitian berhasil disimpan.',
                'data' => new PenelitianResource($penelitian) // Menggunakan Resource untuk response yang rapi
            ], 201);

        } catch (\Exception $e) {
            // Rollback jika ada kesalahan
            DB::rollBack();

            // Response JSON gagal dengan pesan kesalahan
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePenelitian(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'judul' => 'required|string|max:255',
                'abstrak' => 'required|string',
                'penulis' => 'required|string|max:255',
                'email_penulis' => 'required|email',
                'gambar' => 'file|mimes:jpeg,png,jpg|max:10240',
                'lampiran' => 'file|mimes:jpeg,png,jpg,pdf,docx|max:10240',
                'tanggal_publikasi' => 'date',
            ], [
                'judul.required' => 'Judul penelitian wajib diisi.',
                'penulis.required' => 'Nama penulis wajib diisi.',
                'email_penulis.required' => 'Email penulis wajib diisi.',
                'email_penulis.email' => 'Email penulis tidak valid.',
                'abstrak.required' => 'Abstrak penelitian wajib diisi.',
                'gambar.max' => 'Ukuran file gambar maksimal 10MB.',
                'lampiran.max' => 'Ukuran file lampiran maksimal 10MB.',
                'gambar.mimes' => 'File gambar harus berupa JPG, JPEG, atau PNG.',
                'lampiran.mimes' => 'File lampiran harus berupa JPG, JPEG, PNG, PDF, atau DOCX.',
                'tanggal_publikasi.date' => 'Tanggal Publikasi Harus Valid',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $penelitian = Penelitian::findOrFail($id);
            $penelitian->judul = $request->judul;
            $penelitian->abstrak = $request->abstrak;
            $penelitian->penulis = $request->penulis;
            $penelitian->email_penulis = $request->email_penulis;
            $penelitian->tanggal_publikasi = $request->tanggal_publikasi;

            if ($request->hasFile('gambar')) {
                if ($penelitian->gambar && Storage::exists($penelitian->gambar)) {
                    Storage::delete($penelitian->gambar);
                }
                $path = $request->file('gambar')->storeAs(
                    'dokumen-penelitian',
                    time() . '_' . str_replace(' ', '_', $request->file('gambar')->getClientOriginalName())
                );
                $penelitian->gambar = $path;
            }

            if ($request->hasFile('lampiran')) {
                if ($penelitian->lampiran && Storage::exists($penelitian->lampiran)) {
                    Storage::delete($penelitian->lampiran);
                }
                $path = $request->file('lampiran')->storeAs(
                    'dokumen-penelitian',
                    time() . '_' . str_replace(' ', '_', $request->file('lampiran')->getClientOriginalName())
                );
                $penelitian->lampiran = $path;
            }

            if ($request->filled('penulis_korespondensi')) {
                $penelitian->penulis_korespondensi = $request->penulis_korespondensi;
            }

            $penelitian->save();

            if ($request->filled('anggota_penulis')) {
                $penelitian->anggotaPenelitian()->delete();
                foreach ($request->anggota_penulis as $anggotaId) {
                    $penelitian->anggotaPenelitian()->create([
                        'anggota_id' => $anggotaId,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Penelitian berhasil diupdate.',
                'data' => new PenelitianResource($penelitian),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function hapusPenelitian($id)
    {
        try {
            $penelitian = Penelitian::findOrFail($id);

            if ($penelitian->gambar && Storage::exists($penelitian->gambar)) {
                Storage::delete($penelitian->gambar);
            }

            if ($penelitian->lampiran && Storage::exists($penelitian->lampiran)) {
                Storage::delete($penelitian->lampiran);
            }

            $penelitian->delete();

            return response()->json([
                'success' => true,
                'message' => 'Penelitian berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function profil()
    {
        // Ambil data pengguna yang sedang login
        $user = Auth::user();
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function editProfil()
    {
        // Ambil data pengguna yang sedang login
        $user = Auth::user();
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function updateProfil(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'nip' => 'required|string|max:20',
                'jabatan' => 'required|string|max:100',
                'email' => 'required|email|max:255',
                'no_hp' => 'required|max:15',
                'pas_foto' => 'file|mimes:jpg,jpeg,png|max:2048',
                'username' => 'required|string|max:255',
                'password_last' => 'required|string', // Validasi password terakhir
            ]);

            $user = User::findOrFail($id);

            // Cek apakah password terakhir yang dimasukkan sesuai
            if (!Hash::check($request->password_last, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password terakhir salah.'
                ], 400);
            }

            // Cek perubahan data
            if ($request->filled('nama_lengkap')) {
                $user->nama_lengkap = $request->nama_lengkap;
            }
            if ($request->filled('nip')) {
                $user->nip = $request->nip;
            }
            if ($request->filled('jabatan')) {
                $user->jabatan = $request->jabatan;
            }
            if ($request->filled('email')) {
                $user->email = $request->email;
            }
            if ($request->filled('no_hp')) {
                $user->no_hp = $request->no_hp;
            }
            if ($request->filled('username')) {
                $user->username = $request->username;
            }
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }
            if ($request->hasFile('pas_foto')) {
                if ($user->pas_foto && Storage::exists($user->pas_foto)) {
                    Storage::delete($user->pas_foto);
                }
                $originalName = $request->file('pas_foto')->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);

                // Simpan file dengan nama kustom
                $path = $request->file('pas_foto')->storeAs('dokumen-user', $fileName);

                // Simpan path tanpa 'public/' di database
                $user->pas_foto = $path;
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Data ketua KBK berhasil diperbaharui',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function ubahPasswordUser(Request $request, $id)
    {
        // Menampilkan form ubah password
        return response()->json([
            'success' => true,
            'message' => 'Form untuk ubah password berhasil dimuat'
        ]);
    }

    public function prosesUbahPassword(Request $request, $id)
    {
        try {
            $request->validate([
                'password_lama' => 'required|string',
                'password_baru' => 'required|string|min:5|confirmed', // Pastikan ada konfirmasi
            ]);

            $user = User::findOrFail($id);

            // Cek apakah password lama sesuai
            if (!Hash::check($request->password_lama, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password lama salah.'
                ], 400);
            }

            // Update password baru
            $user->password = bcrypt($request->password_baru);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
