<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginAdminController extends Controller
{
    public function loginPage()
    {
        return view('login-admin.index');
    }
}
