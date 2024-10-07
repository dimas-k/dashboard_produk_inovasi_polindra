<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index() {
        return view('dashboard.index');
    }

    public function contact() {
        return view('dashboard.contact.index');
    }

    public function penelitian() {
        return view('dashboard.penelitian.index');
    }

    public function detail_Penelitian() {
        return view('dashboard.detail-penelitian.index');
    }

}
