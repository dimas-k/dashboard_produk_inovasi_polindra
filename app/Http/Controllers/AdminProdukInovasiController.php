<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelompokKeahlian;

class AdminProdukInovasiController extends Controller
{
    public function pageProduk()
    {
        $produk = KelompokKeahlian::with('produk')->paginate(10);
        return view('admin.produk.index', compact('produk'));
    }
}
