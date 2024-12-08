<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\KelompokKeahlian;
use Illuminate\Http\Request;
use App\Http\Resources\ProdukResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AdminProdukInovasiController extends BaseController
{
    // API untuk mendapatkan daftar produk berdasarkan id KBK
    public function pageProduk($id): JsonResponse
    {
        $kbk_navigasi = KelompokKeahlian::select('id', 'nama_kbk')->get();
        $kbk_navigasi1 = KelompokKeahlian::select('id', 'nama_kbk')->where('id', $id)->first();

        if (!$kbk_navigasi1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kelompok Keahlian tidak ditemukan'
            ], 404);
        }

        $data_produk = Produk::with('kelompokKeahlian')
            ->where('kbk_id', $id)
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'kbk_navigasi' => $kbk_navigasi,
            'kbk_navigasi1' => $kbk_navigasi1,
            'data_produk' => ProdukResource::collection($data_produk)
        ], 200);
    }

    // API untuk mendapatkan detail produk berdasarkan ID
    public function showPageProduk($id): JsonResponse
    {
        $produk = Produk::with('kelompokKeahlian')->find($id);

        if (!$produk) {
            return response()->json([
                'status' => 'error',
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        $kbk_navigasi = KelompokKeahlian::select('id', 'nama_kbk')->get();

        return response()->json([
            'status' => 'success',
            'produk' => new ProdukResource($produk),
            'kbk_navigasi' => $kbk_navigasi
        ], 200);
    }

    // API untuk memvalidasi produk
    public function validateProduk(Request $request, $id): JsonResponse
    {
        $produk = Produk::findOrFail($id);

        $produk->status = $request->status === 'Tervalidasi' ? 'Tervalidasi' : 'Belum Divalidasi';
        $produk->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil divalidasi',
            'data' => new ProdukResource($produk)
        ], 200);
    }
}
