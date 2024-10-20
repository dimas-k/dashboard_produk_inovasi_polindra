<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProdukInovasiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelompokBidangController;
use App\Http\Controllers\KetuaKbkController;
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

Route::get('/', [DashboardController::class, 'index']);


Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/dashboard/contact', [DashboardController::class, 'contact']);

// Route::get('dashboard/penelitian/{id}', function($id) {
//     return view('dashboard.penelitian.index', [$id]);
// });

Route::get('dashboard/penelitian/{id}', [DashboardController::class,'penelitian'])->name('dashboard/penelitian/');
Route::get('/dashboard/penelitian', [DashboardController::class, 'penelitian']);


Route::get('/dashboard/detail-penelitian', [DashboardController::class, 'detail_Penelitian']);

Route::get('/login', [LoginController::class, 'loginPage'])->name('login');
Route::post('/login-admin/autentikasi', [LoginController::class, 'authenticate']);
Route::get('/logout', [LoginController::class, 'logout']);


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    
    Route::get('/admin/kelompok-bidang-keahlian', [KelompokBidangController::class, 'pageKelompokBidang']);
    Route::post('/admin/kelompok-bidang-keahlian/create', [KelompokBidangController::class, 'storeKelompokKeahlian']);
    Route::post('/admin/kelompok-bidang-keahlian/update/{id}', [KelompokBidangController::class, 'update'])->name('updateKelompokBidang');
    Route::delete('/admin/kelompok-bidang-keahlian/delete/{id}', [KelompokBidangController::class, 'hapusKbk'])->name('hapusKbk');
    
    Route::get('/admin/admin-page', [AdminController::class, 'admin']);
    route::post('/admin/admin-page/tambah', [AdminController::class, 'storeAdmin']);
    Route::get('/admin/admin-page/show/{id}', [AdminController::class, 'showAdmin'])->name('admin.show');
    Route::post('/admin/admin-page/update/{id}', [AdminController::class, 'updateAdmin'])->name('upate.admin');
    Route::delete('/admin/admin-page/delete/{id}', [AdminController::class, 'deleteAdmin'])->name('hapus.admin');

    Route::get('/admin/ketua-kbk', [AdminController::class, 'ketuaKBK']);
    Route::get('/admin/k-kbk/show/{id}', [AdminController::class, 'showDataKetuaKbk'])->name('show.k-kbk');
    Route::post('/admin/ketua-kbk/store', [AdminController::class, 'storeDataKetuaKbk']);
    Route::post('/admin/ketua-kbk/update/{id}', [AdminController::class, 'updateKetuaKbk'])->name('update.k-kbk');
    Route::delete('/admin/ketua-kbk/delete/{id}', [AdminController::class, 'hapusKetuaKbk'])->name('hapus.k-kbk');

    Route::get('/admin/produk-inovasi/{id}', [AdminProdukInovasiController::class, 'pageProduk'])->name('admin.produk');
    Route::get('/admin/produk-inovasi/show/{id}', [AdminProdukInovasiController::class, 'ShowPageProduk'])->name('show.produk');
    Route::put('/admin/produk-inovasi/edit-status/{id}', [AdminProdukInovasiController::class, 'validateProduk'])->name('validate.produk');
    
});

Route::middleware(['auth', 'role:ketua_kbk'])->group(function () {
    Route::get('/k-kbk/dashboard', [KetuaKbkController::class, 'dashboardPage']);

    Route::get('/k-kbk/produk', [KetuaKbkController::class, 'produkInovasi']);
    Route::get('/k-kbk/produk/lihat/{id}', [KetuaKbkController::class, 'showProduk'])->name('lihat.produk');
    Route::post('/k-kbk/produk/store', [KetuaKbkController::class, 'storeProduk']);
    Route::put('/k-kbk/produk/update/{id}', [KetuaKbkController::class, 'updateProdukInovasi'])->name('update.produk');
    Route::delete('/k-kbk/produk/hapus/{id}', [KetuaKbkController::class, 'hapusProduk'])->name('hapus.produk');

    Route::get('/k-kbk/penelitian', [KetuaKbkController::class, 'penelitian']);
    Route::get('/k-kbk/penelitian/{id}', [KetuaKbkController::class, 'showPenelitian'])->name('show.penelitian');
    Route::post('/k-kbk/penelitian/store', [KetuaKbkController::class, 'storePenelitian']);
    Route::put('/k-kbk/penelitian/update/{id}', [KetuaKbkController::class, 'updatePenelitian'])->name('edit.penelitian');
    Route::delete('/k-kbk/penelitian/hapus/{id}', [KetuaKbkController::class, 'hapusPenelitian'])->name('hapus.penelitian');

    Route::get('/k-kbk/profil', [KetuaKbkController::class, 'profil']);
    Route::get('/k-kbk/profil/edit', [KetuaKbkController::class, 'editProfil']);
    Route::put('/k-kbk/profil/update/{id}', [KetuaKbkController::class, 'updateProfil'])->name('update.profil');
    Route::get('/k-kbk/profil/ubah_password/{id}', [KetuaKbkController::class, 'ubahPasswordUser'])->name('ubah.password');
    Route::post('/k-kbk/profil/ubah_password/{id}', [KetuaKbkController::class, 'prosesUbahPassword'])->name('proses.ubah.password');

});
