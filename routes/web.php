<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/barang', [App\Http\Controllers\BarangController::class, 'index']);
Route::post('/barang', [App\Http\Controllers\BarangController::class, 'store']);
Route::put('/barang', [App\Http\Controllers\BarangController::class, 'update']);
Route::delete('/barang', [App\Http\Controllers\BarangController::class, 'destroy']);

Route::get('/', [App\Http\Controllers\TransaksiController::class, 'index']);
Route::post('/transaksi/tambahBarang', [App\Http\Controllers\TransaksiController::class, 'tambahkanKeranjang']);
Route::post('/transaksi/hapusBarang', [App\Http\Controllers\TransaksiController::class, 'hapusDariKeranjang']);
Route::post('/transaksi/bayar', [App\Http\Controllers\TransaksiController::class, 'bayar']);
Route::post('/cetak', [App\Http\Controllers\TransaksiController::class, 'cetak']);

Route::get('/penjualan', [App\Http\Controllers\PenjualanController::class, 'index']);
