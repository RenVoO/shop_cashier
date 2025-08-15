<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PelangganController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserControler;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiskonController;

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

Route::get('/', [DashboardController::class, 'index'])->name('home')->middleware('auth');

Route::view('login', 'auth.login')->name('login')->middleware('guest');
Route::post('login',[AuthController::class, 'login'])->middleware('guest');
Route::post('logout',[AuthController::class,'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function(){
    Route::singleton('profile', ProfileController::class);
    Route::resource('user' ,UserControler::class)->middleware('can:admin');
    Route::resource('pelanggan', PelangganController::class);
    Route::resource('kategori', KategoriController::class)->middleware('can:admin');
    Route::resource('produk', ProdukController::class);
    Route::get('stok/produk',[StokController::class,'produk'])->name('stok.produk');
    Route::resource('stok',StokController::class)->only('index','create','store','destroy');
    Route::get('transaksi/produk', [TransaksiController::class, 'produk'])
    ->name('transaksi.produk');
    Route::get('transaksi/pelanggan', [TransaksiController::class, 'pelanggan'])
    ->name('transaksi.pelanggan');
    Route::get('transaksi/{transaksi}/cetak', [TransaksiController::class, 'cetak'])
    ->name('transaksi.cetak');
    Route::post('transaksi/pelanggan', [TransaksiController::class, 'addPelanggan'])
    ->name('transaksi.pelanggan.add');
    Route::resource('transaksi', TransaksiController::class)->except('edit', 'update');
    Route::get('cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::resource('cart', CartController::class)->except('create', 'show', 'edit')
    ->parameters(['cart' => 'hash']);
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/harian', [LaporanController::class, 'harian'])->name('laporan.harian');
    Route::get('laporan/bulanan', [LaporanController::class, 'bulanan'])->name('laporan.bulanan');
    Route::resource('diskon', DiskonController::class);
    Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
});