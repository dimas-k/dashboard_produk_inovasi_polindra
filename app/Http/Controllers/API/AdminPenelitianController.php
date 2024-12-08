<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KelompokKeahlian;
use App\Models\Penelitian;
use App\Http\Resources\PenelitianResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AdminPenelitianController extends BaseController
{
    // API untuk mendapatkan data penelitian berdasarkan id KBK
    public function pagePenelitian($id): JsonResponse
    {
        $kbk_navigasi = KelompokKeahlian::select('id', 'nama_kbk')->get();
        $kbk_navigasi1 = KelompokKeahlian::select('id', 'nama_kbk')->where('id', $id)->first();

        if (!$kbk_navigasi1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kelompok Keahlian tidak ditemukan'
            ], 404);
        }

        $data_penelitian = Penelitian::with('kelompokKeahlian')
            ->where('kbk_id', $id)
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'kbk_navigasi' => $kbk_navigasi,
            'kbk_navigasi1' => $kbk_navigasi1,
            'data_penelitian' => PenelitianResource::collection($data_penelitian)
        ], 200);
    }

    // API untuk mendapatkan detail penelitian berdasarkan ID
    public function showPenelitian($id): JsonResponse
    {
        $penelitian = Penelitian::with(['kelompokKeahlian', 'penulisKorespondensi'])->find($id);

        if (!$penelitian) {
            return response()->json([
                'status' => 'error',
                'message' => 'Penelitian tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => new PenelitianResource($penelitian)
        ], 200);
    }

    // API untuk memvalidasi penelitian
    public function validatePenelitian(Request $request, $id): JsonResponse
    {
        $penelitian = Penelitian::findOrFail($id);

        $penelitian->status = $request->status === 'Tervalidasi' ? 'Tervalidasi' : 'Belum Divalidasi';
        $penelitian->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Penelitian berhasil divalidasi',
            'data' => new PenelitianResource($penelitian)
        ], 200);
    }
}
