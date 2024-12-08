<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\Api\AdminPenelitianController;
use App\Http\Controllers\Api\AdminProdukInovasiController;
use App\Http\Controllers\API\KelompokBidangController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\KetuaKbkController;
use App\Http\Controllers\API\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(LoginController::class)->group(function () {
    Route::post('authenticate', 'authenticate');
    Route::get('index-login', 'index');
});
Route::controller(AdminController::class)->group(function () {
    Route::post('storeAdmin', 'storeAdmin');
});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [LoginController::class, 'logout']);

    Route::controller(KelompokBidangController::class)->group(function () {
        Route::get('pageKelompokBidang', 'pageKelompokBidang');
        Route::get('edit/{id}', 'edit');
        Route::post('storeKelompokKeahlian', 'storeKelompokKeahlian');
        Route::delete('hapusKbk/{id}', 'hapusKbk');
        Route::put('update/{id}', 'update');
    });

    Route::controller(AdminController::class)->group(function () {
        Route::post('storeDataKetuaKbk', 'storeDataKetuaKbk');
        Route::get('dashboard', 'dashboard');
        Route::get('admin', 'admin');
        Route::get('showAdmin/{id}', 'showAdmin');
        Route::put('updateAdmin/{id}', 'updateAdmin');
        Route::put('updateKetuaKbk/{id}', 'updateKetuaKbk');
        Route::put('reset-password/{id}', 'resetPassword');
        Route::delete('hapusKetuaKbk/{id}', 'hapusKetuaKbk');
    });

    Route::controller(AdminPenelitianController::class)->group(function () {
        Route::get('pagePenelitian/{id}/list', 'pagePenelitian');
        Route::get('showPenelitian/{id}', 'showPenelitian');
        Route::post('validatePenelitian/{id}/validate', 'validatePenelitian');
    });

    Route::controller(AdminProdukInovasiController::class)->group(function () {
        Route::get('pageProduk/{id}/list', 'pageProduk');
        Route::get('showPageProduk/{id}', 'showPageProduk');
        Route::post('validateProduk/{id}/validate', 'validateProduk');
    });

    Route::controller(DashboardController::class)->group(function () {
        Route::get('index', 'index');
        Route::get('contact', 'contact');
        Route::get('penelitian/{nama_kbk}/penelitian', 'penelitian');
        Route::get('detailProduk/{nama_produk}', 'detailProduk');
        Route::get('detailPenelitian/{judul}', 'detailPenelitian');
        Route::get('dosenProduk/{dosen}', 'dosenProduk');
        Route::get('katalogProduk', 'katalogProduk');
        Route::get('katalogProdukCari/cari', 'katalogProdukCari');
        Route::get('katalogPenelitian', 'katalogPenelitian');
        Route::get('katalogPenelitianCari/cari', 'katalogPenelitianCari');
    });

    Route::controller(KetuaKbkController::class)->group(function () {
        Route::get('dashboardPage', 'dashboardPage');
        Route::get('anggotaPage', 'anggotaPage');
        Route::post('storeAnggota', 'storeAnggota');
        Route::put('updateAnggota/{id}', 'updateAnggota');
        Route::delete('hapusAnggota/{id}', 'hapusAnggota');
        Route::get('produkInovasi', 'produkInovasi');
        Route::get('showProduk/{id}', 'showProduk');
        Route::post('storeProduk', 'storeProduk');
        Route::put('updateProdukInovasi/{id}', 'updateProdukInovasi');
        Route::delete('hapusProduk/{id}', 'hapusProduk');
        Route::get('penelitian', 'penelitian');
        Route::get('showPenelitian{id}', 'showPenelitian');
        Route::post('storePenelitian', 'storePenelitian');
        Route::put('updatePenelitian/{id}', 'updatePenelitian');
        Route::delete('hapusPenelitian/{id}', 'hapusPenelitian');
        Route::put('updateProfil/{id}', 'updateProfil');
        Route::post('prosesUbahPassword/{id}', 'prosesUbahPassword');
    });

    Route::controller(ReportController::class)->group(function () {
        Route::get('getPenelitianReport', 'getPenelitianReport');
        Route::get('getProdukReport', 'getProdukReport');
    });
});

Route::get('kelompok_keahlians', [KelompokBidangController::class, 'pageKelompokBidang']);
