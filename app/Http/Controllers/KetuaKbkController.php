<?php

namespace App\Http\Controllers;

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


class KetuaKbkController extends Controller
{
    public function dashboardPage()
    {
        $kelompokKeahlianId = Auth::user()->kelompokKeahlian->id ?? null;
        $prdk_valid = Produk::with('kelompokKeahlian')->where('kbk_id', $kelompokKeahlianId)->where('status', 'Tervalidasi')->count();
        $prdk_nonvalid = Produk::with('kelompokKeahlian')->where('kbk_id', $kelompokKeahlianId)->where('status', 'Belum Divalidasi')->count();

        $pnltan_valid = Penelitian::with('kelompokKeahlian')->where('kbk_id', $kelompokKeahlianId)->where('status', 'Tervalidasi')->count();
        $pnltan_nonvalid = Penelitian::with('kelompokKeahlian')->where('kbk_id', $kelompokKeahlianId)->where('status', 'Belum Divalidasi')->count();
        return view('k_kbk.index', compact('pnltan_valid', 'pnltan_nonvalid', 'pnltan_nonvalid', 'prdk_valid', 'prdk_nonvalid'));
    }

    public function anggotaPage()
    {
        $kelompokKeahlianId = Auth::user()->kelompokKeahlian->id ?? null;
        $anggotas = AnggotaKelompokKeahlian::with('kelompokKeahlian')
            ->where('kbk_id', $kelompokKeahlianId) // Ganti dengan ID KBK yang sesuai
            ->paginate(10);

        $userId = Auth::id();
        $kkbk = DB::table('users')->join('kelompok_keahlians', 'users.kbk_id', '=', 'kelompok_keahlians.id')
            ->select(
                'kelompok_keahlians.id',
                'kelompok_keahlians.nama_kbk',
                'users.nama_lengkap'
            )->where('users.id', '=', $userId)->get();

        return view('k_kbk.anggota.index', compact('anggotas', 'kkbk'));
    }

    public function storeAnggota(Request $request)
    {
        try {
            $request->validate(
                [
                    'nama_lengkap' => 'required|string|max:255',
                    'jabatan' => 'required|string|max:255',
                    'kbk_id' => 'required|exists:kelompok_keahlians,id',
                    'email' => 'required|email',
                ],
                [
                    'nama_lengkap.required' => 'Nama Lengkap anggota harus diisi',
                    'jabatan.required' => 'Jabatan anggota harus diisi',
                    'kbk_id.required' => 'KBK harus diisi',
                    'email.required' => 'Email harus diisi',
                    'email.email' => 'Masukkan email yang valid'
                ]
            );

            $anggota = new AnggotaKelompokKeahlian();
            $anggota->kbk_id = $request->input('kbk_id');
            $anggota->nama_lengkap = $request->input('nama_lengkap');
            $anggota->jabatan = $request->input('jabatan');
            $anggota->email = $request->input('email');
            $anggota->save();

            return response()->json([
                'success' => true,
                'message' => 'Data anggota berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan : ' . $e->getMessage()
            ], 500);
        }
    }
    public function updateAnggota(Request $request, $id)
    {
        try {
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

            $anggota = AnggotaKelompokKeahlian::findOrFail($id);
            $anggota->nama_lengkap = $request->input('nama_lengkap');
            $anggota->jabatan = $request->input('jabatan');
            $anggota->email = $request->input('email');
            $anggota->save();
            return response()->json([
                'success' => true,
                'message' => 'Data anggota berhasil diupdate.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan : ' . $e->getMessage()
            ], 500);
        }
    }

    public function hapusAnggota($id)
    {
        $anggota = AnggotaKelompokKeahlian::findOrFail($id);
        $anggota->delete();

        return redirect('/k-kbk/anggota-kbk')->with('success', 'Data anggota berhasil dihapus!');
    }

    public function produkInovasi()
    {
        // $jenis_kbk = KelompokKeahlian::all();
        $kelompokKeahlianId = Auth::user()->kelompokKeahlian->id ?? null;

        // Query produk berdasarkan kelompok keahlian ID
        $produks = Produk::with('kelompokKeahlian')->where('kbk_id', $kelompokKeahlianId)->paginate(10);

        $userId = Auth::id();
        $kkbk = DB::table('users')
            ->join('kelompok_keahlians', 'users.kbk_id', '=', 'kelompok_keahlians.id')
            ->select(
                'kelompok_keahlians.id',
                'kelompok_keahlians.nama_kbk',
                'users.nama_lengkap'
            )
            ->where('users.id', '=', $userId)
            ->get();


        $user = auth()->user();
        $anggotaKelompok = AnggotaKelompokKeahlian::where('kbk_id', $user->kbk_id)->get();
        $produkAnggota = AnggotaKelompokKeahlian::all();



        // dd($produks);
        return view('k_kbk.produk.index', compact('produks', 'kkbk', 'anggotaKelompok', 'produkAnggota'));
    }
    public function showProduk($id)
    {
        $produk = Produk::with(['KelompokKeahlian', 'anggota.detail'])->findOrFail($id);
        // dd($produk->anggota);
        return view('k_kbk.produk.show.index', compact('produk'));
    }

    public function storeProduk(Request $request)
    {
        // dd($request);
        try {
            // Validasi input
            $request->validate([
                'nama_produk' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'kbk_id' => 'required|exists:kelompok_keahlians,id',
                'inventor' => 'required|string|max:255',
                'email_inventor' => 'required|email',
                'gambar' => 'required|file|mimes:jpeg,png,jpg|max:10240',
                'lampiran' => 'nullable|file|mimes:jpeg,png,jpg,pdf,docx|max:10240',
                'tanggal_submit' => 'date',
                'tanggal_granted' => 'date'
            ]);
            DB::beginTransaction();
            $produk = new Produk();
            $produk->kbk_id = $request->kbk_id;
            $produk->nama_produk = $request->nama_produk;
            $produk->deskripsi = $request->deskripsi;
            $produk->inventor = $request->inventor;
            $produk->email_inventor = $request->email_inventor;
            $produk->tanggal_submit = $request->tanggal_submit;
            $produk->tanggal_granted = $request->tanggal_granted;



            // Proses upload gambar
            if ($request->hasFile('gambar')) {
                $originalName = $request->file('gambar')->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);
                $path = $request->file('gambar')->storeAs('dokumen-produk', $fileName);
                $produk->gambar = $path;
            }
            // Proses upload lampiran
            if ($request->hasFile('lampiran')) {
                $originalName = $request->file('lampiran')->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);
                $path = $request->file('lampiran')->storeAs('dokumen-produk', $fileName);
                $produk->lampiran = $path;
            }
            // Simpan produk ke database
            $produk->save();

            foreach ($request->anggota_inventor as $anggota) {
                ProdukAnggota::create([
                    'produk_id' => $produk->id,
                    'anggota_id' => $anggota
                ]);
            }
            DB::commit();
            // Return response success dalam format JSON
            return response()->json(['success' => true, 'message' => 'Data Produk berhasil ditambahkan!']);
        } catch (\Exception $e) {
            // Jika ada error, return response error dengan pesan
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan : ' . $e->getMessage()
            ], 500);
        }
    }


    public function updateProdukInovasi(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'inventor' => 'required|string|max:255',
            // 'anggota_inventor' => 'nullable|string',
            'email_inventor' => 'required|email',
            'gambar' => 'file|mimes:jpeg,png,jpg|max:10240',
            'lampiran' => 'file|mimes:jpeg,png,jpg,pdf,docx|max:10240',
            'tanggal_submit' => 'date',
            'tanggal_granted' => 'date'
        ]);
        $produk = Produk::findOrFail($id);
        $produk->nama_produk = $request->nama_produk;
        $produk->deskripsi = $request->deskripsi;
        $produk->inventor = $request->inventor;
        $produk->email_inventor = $request->email_inventor;
        $produk->tanggal_submit = $request->tanggal_submit;
        $produk->tanggal_granted = $request->tanggal_granted;

        if ($request->hasFile('gambar')) {
            if ($produk->gambar && Storage::exists($produk->gambar)) {
                Storage::delete($produk->gambar);
            }
            $originalName = $request->file('gambar')->getClientOriginalName();
            $fileName = time() . '_' . str_replace(' ', '_', $originalName);

            // Simpan file dengan nama kustom
            $path = $request->file('gambar')->storeAs('dokumen-produk', $fileName);

            // Simpan path tanpa 'public/' di database
            $produk->gambar = $path;
        }

        if ($request->hasFile('lampiran')) {
            if ($produk->lampiran && Storage::exists($produk->lampiran)) {
                Storage::delete($produk->lampiran);
            }
            $originalName = $request->file('lampiran')->getClientOriginalName();
            $fileName = time() . '_' . str_replace(' ', '_', $originalName);

            // Simpan file dengan nama kustom
            $path = $request->file('lampiran')->storeAs('dokumen-produk', $fileName);

            // Simpan path tanpa 'public/' di database
            $produk->lampiran = $path;
        }
        $produk->save();

        // Update anggota inventor only if input is provided, otherwise keep existing data
        if ($request->filled('anggota_inventor')) {
            $produk->anggota_inventor()->sync($request->anggota_inventor);
        }

        return redirect('/k-kbk/produk')->with('success', 'Data Produk berhasil diupdate!');
    }

    public function hapusProduk($id)
    {
        $produk = Produk::findOrFail($id);

        // Hapus gambar jika ada
        if ($produk->gambar && Storage::exists($produk->gambar)) {
            Storage::delete($produk->gambar);
        }

        // Hapus lampiran jika ada
        if ($produk->lampiran && Storage::exists($produk->lampiran)) {
            Storage::delete($produk->lampiran);
        }
        $produk->delete();
        return redirect('/k-kbk/produk')->with('success', 'Data Produk berhasil dihapus!');
    }

    //penelitian method
    public function penelitian()
    {

        $kelompokKeahlianId = Auth::user()->kelompokKeahlian->id ?? null;
        $penelitians = Penelitian::with('kelompokKeahlian')->where('kbk_id', $kelompokKeahlianId)->paginate(10);

        $userId = Auth::id();
        $kkbk = DB::table('users')
            ->join('kelompok_keahlians', 'users.kbk_id', '=', 'kelompok_keahlians.id')
            ->select(
                'kelompok_keahlians.id',
                'kelompok_keahlians.nama_kbk',
                'users.nama_lengkap'
            )
            ->where('users.id', '=', $userId)
            ->get();

        $user = auth()->user();
        $anggotaKelompok = AnggotaKelompokKeahlian::where('kbk_id', $user->kbk_id)->get();
        $penelitianAnggota = AnggotaKelompokKeahlian::all();


        return view('k_kbk.penelitian.index', compact('penelitians', 'kkbk', 'anggotaKelompok', 'penelitianAnggota'));
    }

    public function showPenelitian($id)
    {
        // $user = auth()->user();
        // $anggotaKelompok = AnggotaKelompokKeahlian::where('kbk_id', $user->kbk_id)->get();
        // $produkAnggota = AnggotaKelompokKeahlian::all();
        $penelitian = Penelitian::with(['kelompokKeahlian', 'anggotaPenelitian.detailAnggota'])->findOrFail($id);


        return view('k_kbk.penelitian.show.index', compact('penelitian'));
    }

    public function storePenelitian(Request $request)
    {
        try {
            $request->validate([
                'judul' => 'required|string|max:255',
                // 'abstrak' => 'required|file|mimes:pdf|max:10240',
                'abstrak' => 'required|string',
                'kbk_id' => 'required|exists:kelompok_keahlians,id',
                'penulis' => 'required|string|max:255',
                'email_penulis' => 'required|email',
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

                // 'abstrak.max' => 'Ukuran file abstrak maksimal 10MB.',
                // 'abstrak.max' => 'Abstrak Tidak Boleh Lebih Dari 250 Kata.',
                'gambar.max' => 'Ukuran file gambar maksimal 10MB.',
                'lampiran.max' => 'Ukuran file lampiran maksimal 10MB.',

                // 'abstrak.mimes' => 'File abstrak harus berupa PDF.',
                'gambar.mimes' => 'File gambar harus berupa JPG, JPEG, atau PNG.',
                'lampiran.mimes' => 'File lampiran harus berupa JPG, JPEG, PNG, PDF, atau DOCX.',
            ]);
            DB::beginTransaction();
            $penelitian = new Penelitian();
            $penelitian->kbk_id = $request->kbk_id;
            $penelitian->judul = $request->judul;
            $penelitian->abstrak = $request->abstrak;
            $penelitian->penulis = $request->penulis;
            // $penelitian->anggota_penulis = $request->anggota_penulis;
            $penelitian->email_penulis = $request->email_penulis;
            $penelitian->tanggal_publikasi = $request->tanggal_publikasi;

            // if ($request->hasFile('abstrak')) {
            //     $originalName = $request->file('abstrak')->getClientOriginalName();
            //     $fileName = time() . '_' . str_replace(' ', '_', $originalName);

            //     // Simpan file ke folder 'storage/app/public/dokumen-penelitian'
            //     $path = $request->file('abstrak')->storeAs('dokumen-penelitian', $fileName);

            //     // Simpan path utuh ke database
            //     $penelitian->abstrak = $path;
            // }

            if ($request->hasFile('gambar')) {
                $originalName = $request->file('gambar')->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);

                // Simpan file ke folder 'storage/app/public/dokumen-penelitian'
                $path = $request->file('gambar')->storeAs('dokumen-penelitian', $fileName);

                // Simpan path utuh ke database
                $penelitian->gambar = $path;
            }

            if ($request->hasFile('lampiran')) {
                $originalName = $request->file('lampiran')->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);

                // Simpan file ke folder 'storage/app/public/dokumen-penelitian'
                $path = $request->file('lampiran')->storeAs('dokumen-penelitian', $fileName);

                // Simpan path utuh ke database
                $penelitian->lampiran = $path;
            }

            $penelitian->save();

            foreach ($request->anggota_penulis as $anggota) {
                PenelitianAnggota::create([
                    'penelitian_id' => $penelitian->id,
                    'anggota_id' => $anggota
                ]);
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Penelitian berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            // Jika ada error, return response error dengan pesan
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan : ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePenelitian(Request $request, $id)
    {
        try {
            $request->validate([
                'judul' => 'required|string|max:255',
                // 'abstrak' => 'required|file|mimes:pdf|max:10240',
                'abstrak' => 'required|string',
                
                'penulis' => 'required|string|max:255',
                'email_penulis' => 'required|email',
                'gambar' => 'file|mimes:jpeg,png,jpg|max:10240', // Sesuaikan dengan format file yang diperbolehkan
                'lampiran' => 'file|mimes:jpeg,png,jpg,pdf,docx|max:10240',
                'tanggal_publikasi' => 'date'
            ], [
                'judul.required' => 'Judul penelitian wajib diisi.',
                'penulis.required' => 'Nama penulis wajib diisi.',
                'email_penulis.required' => 'Email penulis wajib diisi.',
                'email_penulis.email' => 'Email penulis tidak valid.',

                'abstrak.required' => 'Abstrak penelitian wajib diisi.',

                // 'abstrak.max' => 'Ukuran file abstrak maksimal 10MB.',
                // 'abstrak.max' => 'Abstrak Tidak Boleh Lebih Dari 250 Kata.',
                'gambar.max' => 'Ukuran file gambar maksimal 10MB.',
                'lampiran.max' => 'Ukuran file lampiran maksimal 10MB.',

                // 'abstrak.mimes' => 'File abstrak harus berupa PDF.',
                'gambar.mimes' => 'File gambar harus berupa JPG, JPEG, atau PNG.',
                'lampiran.mimes' => 'File lampiran harus berupa JPG, JPEG, PNG, PDF, atau DOCX.',
                'tanggal_publikasi.date'=>'Tanggal Publikasi Harus Valid'
            ]);
            $penelitian = Penelitian::findOrFail($id);
            $penelitian->judul = $request->judul;
            $penelitian->abstrak = $request->abstrak;
            $penelitian->penulis = $request->penulis;
            // $penelitian->anggota_penulis = $request->anggota_penulis;
            $penelitian->email_penulis = $request->email_penulis;
            $penelitian->tanggal_publikasi = $request->tanggal_publikasi;

            // if ($request->hasFile('abstrak')) {
            //     if ($penelitian->abstrak && Storage::exists($penelitian->abstrak)) {
            //         Storage::delete($penelitian->abstrak);
            //     }
            //     $originalName = $request->file('abstrak')->getClientOriginalName();
            //     $fileName = time() . '_' . str_replace(' ', '_', $originalName);

            //     // Simpan file dengan nama kustom
            //     $path = $request->file('abstrak')->storeAs('dokumen-penelitian', $fileName);

            //     // Simpan path tanpa 'public/' di database
            //     $penelitian->gambar = $path;
            // }
            if ($request->hasFile('gambar')) {
                if ($penelitian->gambar && Storage::exists($penelitian->gambar)) {
                    Storage::delete($penelitian->gambar);
                }
                $originalName = $request->file('gambar')->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);

                // Simpan file dengan nama kustom
                $path = $request->file('gambar')->storeAs('dokumen-penelitian', $fileName);

                // Simpan path tanpa 'public/' di database
                $penelitian->gambar = $path;
            }
            if ($request->hasFile('lampiran')) {
                if ($penelitian->lampiran && Storage::exists($penelitian->lampiran)) {
                    Storage::delete($penelitian->lampiran);
                }
                $originalName = $request->file('lampiran')->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);

                // Simpan file dengan nama kustom
                $path = $request->file('lampiran')->storeAs('dokumen-penelitian', $fileName);

                // Simpan path tanpa 'public/' di database
                $penelitian->lampiran = $path;
            }

            $penelitian->save();

            if ($request->filled('anggota_penulis')) {
                // Hapus semua anggota terkait sebelumnya
                $penelitian->anggotaPenelitian()->delete();

                // Tambahkan data baru
                foreach ($request->anggota_penulis as $anggotaId) {
                    $penelitian->anggotaPenelitian()->create([
                        'anggota_id' => $anggotaId,
                    ]);
                }
            }
            return response()->json([
                'success' => true,
                'message' => 'Penelitian berhasil diupdate.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan : ' . $e->getMessage()
            ], 500);
        }
    }
    public function hapusPenelitian($id)
    {
        $penelitian = Penelitian::findOrFail($id);

        if ($penelitian->gambar && Storage::exists($penelitian->gambar)) {
            Storage::delete($penelitian->gambar);
        }

        // Hapus lampiran jika ada
        if ($penelitian->lampiran && Storage::exists($penelitian->lampiran)) {
            Storage::delete($penelitian->lampiran);
        }
        $penelitian->delete();
        return redirect('/k-kbk/penelitian')->with('success', 'Data Penelitian berhasil dihapus');
    }

    public function profil()
    {

        return view('k_kbk.profil.index');
    }

    public function editProfil()
    {
        return view('k_kbk.profil.edit.index');
    }

    // public function updateProfil(Request $request, $id)
    // {
    //     $request->validate([
    //         'nama_lengkap' => 'required|string|max:255',
    //         'nip' => 'required|string|max:20',
    //         'jabatan' => 'required|string|max:100',
    //         'email' => 'required|email|max:255',
    //         'no_hp' => 'required|max:15',
    //         'username' => 'required|string|max:255',

    //     ]);

    //     $user = User::find($id);
    //     $user->nama_lengkap = $request->nama_lengkap;
    //     $user->nip = $request->nip;
    //     $user->jabatan = $request->jabatan;
    //     $user->email = $request->email;
    //     $user->no_hp = $request->no_hp;
    //     $user->username = $request->username;
    //     if ($request->filled('password')) {
    //         $user->password = bcrypt($request->password);
    //     }
    //     $user->save();

    // return redirect('/k-kbk/profil')->with('success', 'Data ketua kbk berhasil diperbaharui');
    // }

    public function updateProfil(Request $request, $id)
    {
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

        $user = User::find($id);

        // Cek apakah password terakhir yang dimasukkan sesuai
        if (!Hash::check($request->password_last, $user->password)) {
            return redirect()->back()->withErrors(['password_last' => 'Password terakhir salah.']);
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

        return redirect('/k-kbk/profil')->with('success', 'Data ketua kbk berhasil diperbaharui');
    }

    public function ubahPasswordUser(Request $request, $id)
    {
        // Menampilkan view form ubah password
        return view('k_kbk.profil.ubahPassword.index');
    }

    public function prosesUbahPassword(Request $request, $id)
    {
        $request->validate([
            'password_lama' => 'required|string',
            'password_baru' => 'required|string|min:5|confirmed', // Pastikan ada konfirmasi
        ]);

        $user = User::find($id);

        // Cek apakah password lama sesuai
        if (!Hash::check($request->password_lama, $user->password)) {
            return redirect()->back()->withErrors(['password_lama' => 'Password lama salah.']);
        }

        // Update password baru
        $user->password = bcrypt($request->password_baru);
        $user->save();

        return redirect('/k-kbk/profil')->with('success', 'Password berhasil diubah.');
    }
}
