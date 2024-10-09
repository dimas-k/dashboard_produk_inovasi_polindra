<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelompokBidangController;
use App\Http\Controllers\LoginAdminController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('dashboard/index');
});

Route::get('/login', [LoginAdminController::class, 'loginPage'])->name('login');
Route::post('/login-admin/autentikasi', [LoginAdminController::class, 'authenticate']);

Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/dashboard/penelitian', [DashboardController::class, 'penelitian']);
Route::get('/dashboard/contact', [DashboardController::class, 'contact']);
Route::get('/dashboard/detail-penelitian', [DashboardController::class, 'detail_Penelitian']);

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);

    Route::get('/admin/kelompok-bidang-keahlian', [KelompokBidangController::class, 'pageKelompokBidang']);
    Route::post('/admin/kelompok-bidang-keahlian/create', [KelompokBidangController::class, 'storeKelompokKeahlian']);
    Route::delete('/admin/kelompok-bidang-keahlian/{id}', [KelompokBidangController::class, 'hapuskbk'])->name('hapusKbk');
    Route::put('/admin/kelompok-bidang-keahlian/update', [KelompokBidangController::class, 'update'])->name('updateKelompokBidang');
    Route::get('/admin/kelompok-bidang-keahlian/edit/{id}', [KelompokBidangController::class, 'edit'])->name('editBidang');

    Route::get('/logout', [LoginAdminController::class, 'logout']);
});
