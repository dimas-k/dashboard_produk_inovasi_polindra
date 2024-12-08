<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\KelompokKeahlian;
use App\Http\Resources\KelompokKeahlianResource;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

class KelompokBidangController extends BaseController
{
    public function pageKelompokBidang()
    {
        // Ambil semua data KelompokKeahlian
        $kbk = KelompokKeahlian::all();

        // Ambil data untuk navigasi
        $kbk_navigasi = DB::table('kelompok_keahlians')
            ->select('id', 'nama_kbk')
            ->get();

        // Kembalikan data dalam format JSON menggunakan KelompokKeahlianResource
        return response()->json([
            'kbk' => KelompokKeahlianResource::collection($kbk),
            'kbk_navigasi' => $kbk_navigasi
        ], 200);
    }

    public function storeKelompokKeahlian(Request $request)
    {
        // Validasi input
        $validasidata = $request->validate([
            'nama_kbk' => 'required|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        // Simpan data KBK baru
        $kbk = new KelompokKeahlian();
        $kbk->nama_kbk = $request->nama_kbk;
        $kbk->jurusan = $request->jurusan;
        $kbk->deskripsi = $request->deskripsi;
        $kbk->save();

        // Kembalikan response sukses dalam format JSON menggunakan KelompokKeahlianResource
        return response()->json([
            'message' => 'Kelompok Keahlian berhasil ditambahkan',
            'data' => new KelompokKeahlianResource($kbk)
        ], 201);
    }

    public function hapusKbk(int $id): JsonResponse
    {
        try {
            // Temukan KBK berdasarkan ID
            $kbk = KelompokKeahlian::findOrFail($id);

            // Hapus KBK jika ditemukan
            $kbk->delete();

            return response()->json(['message' => 'KBK berhasil dihapus.'], 200);
        } catch (QueryException $e) {
            // Jika terjadi error integrity constraint
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'KBK tidak bisa dihapus karena masih terhubung dengan data lain. Silakan hapus data terkait terlebih dahulu.'
                ], 400);
            }
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus KBK.'], 500);
        } catch (\Exception $e) {
            // Tangani error jika ID tidak ditemukan
            return response()->json(['message' => 'KBK tidak ditemukan.'], 404);
        }
    }

    public function edit(string $id)
    {
        // Ambil data berdasarkan ID
        $kbk = KelompokKeahlian::find($id);

        // Jika data tidak ditemukan, kembalikan error
        if (!$kbk) {
            return response()->json(['message' => 'Kelompok Keahlian tidak ditemukan.'], 404);
        }

        // Kembalikan data KBK dalam format JSON menggunakan KelompokKeahlianResource
        return response()->json(['data' => new KelompokKeahlianResource($kbk)], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input
        $validasidata = $request->validate([
            'nama_kbk' => 'required|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        // Cari KBK berdasarkan ID
        $kbk = KelompokKeahlian::find($id);

        // Jika data tidak ditemukan, kembalikan error
        if (!$kbk) {
            return response()->json(['message' => 'Kelompok Keahlian tidak ditemukan.'], 404);
        }

        // Update data KBK
        $kbk->nama_kbk = $request->nama_kbk;
        $kbk->jurusan = $request->jurusan;
        $kbk->deskripsi = $request->deskripsi;
        $kbk->save();

        // Kembalikan response sukses dalam format JSON menggunakan KelompokKeahlianResource
        return response()->json([
            'message' => 'Kelompok Keahlian berhasil diupdate',
            'data' => new KelompokKeahlianResource($kbk)
        ], 200);
    }
}
