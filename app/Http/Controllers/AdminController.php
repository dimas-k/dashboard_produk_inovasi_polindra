<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.index');
    }

    public function admin()
    {
        $admin = User::all();
        return view('admin.admin-page.index', compact('admin'));
    }
    public function showAdmin(string $id)
    {
        $admin = User::find($id);
        return view('admin.admin-page.show.index', compact('admin'));
    }
}
