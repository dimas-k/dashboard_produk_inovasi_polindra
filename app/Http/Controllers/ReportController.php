<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use App\Models\Produk;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function getPenelitianReport(Request $request)
    {
        // Filter berdasarkan tanggal jika tersedia
        $penelitians = Penelitian::query();

        if ($request->has('start_date') && $request->has('end_date')) {
            $penelitians->whereBetween('tanggal_publikasi', [$request->start_date, $request->end_date]);
        }

        $data = $penelitians->selectRaw('DATE(tanggal_publikasi) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        return response()->json($data);
    }

    public function getProdukReport(Request $request)
    {
        // Filter berdasarkan tanggal jika tersedia
        $produks = Produk::query();

        if ($request->has('start_date') && $request->has('end_date')) {
            $produks->whereBetween('tanggal_submit', [$request->start_date, $request->end_date]);
        }

        $data = $produks->selectRaw('DATE(tanggal_submit) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        return response()->json($data);
    }
}
