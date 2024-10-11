<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KetuaKbkController extends Controller
{
    public function dashboardPage()
    {
        return view('k_kbk.index');
    } 
}
