<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginPage()
    {
        return view('login-admin.index');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required|min:3',
        ]);

        if(Auth::attempt($credentials))
        {
            $request->session()->regenerate();
           if(Auth::user()->role =='admin')
           {
             return redirect('/admin/dashboard');
           }
           elseif(Auth::user()->role =='ketua_kbk')
           {
                return redirect('/k-kbk/dashboard');
           }
        }
        return back()->with('loginError', 'Username atau password salah!');
        
    }
    public function logout(request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/login');
    }
}
