<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminProdukInovasiController extends Controller
{
    public function pageProduk()
    {
        return view('admin.produk.index');
    }
}
