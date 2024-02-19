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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('admin/home',
    [App\Http\Controllers\AdminController::class, 'index'])->name('admin.home')->middleware('admin');
Route::get('admin/ruangan',
    [App\Http\Controllers\AdminController::class, 'ruangan'])->name('admin.ruangan')->middleware('admin');
Route::post('admin/ruangan', 
    [App\Http\Controllers\AdminController::class, 'submit_ruangan'])->name('admin.ruangan.submit')->middleware('admin');
Route::patch('admin/ruangan', 
    [App\Http\Controllers\AdminController::class, 'update_ruangan'])->name('admin.ruangan.update')->middleware('admin');
 Route::get('admin/ruangan/ajaxadmin/dataRuangan/{id}', 
    [App\Http\Controllers\AdminController::class, 'getDataRuangan']);
Route::post('admin/ruangan/delete/{id}', 
    [App\Http\Controllers\AdminController::class, 'delete_ruangan'])->name('admin.ruangan.delete')->middleware('admin');

Route::get('admin/prasarana',
    [App\Http\Controllers\AdminController::class, 'prasarana'])->name('admin.prasarana')->middleware('admin');
Route::post('admin/prasarana', 
    [App\Http\Controllers\AdminController::class, 'submit_prasarana'])->name('admin.prasarana.submit')->middleware('admin');
Route::patch('admin/prasarana', 
    [App\Http\Controllers\AdminController::class, 'update_prasarana'])->name('admin.prasarana.update')->middleware('admin');
 Route::get('admin/prasarana/ajaxadmin/dataPrasarana/{id}', 
    [App\Http\Controllers\AdminController::class, 'getDataPrasarana']);
Route::post('admin/prasarana/delete/{id}', 
    [App\Http\Controllers\AdminController::class, 'delete_prasarana'])->name('admin.prasarana.delete')->middleware('admin');
Route::get('admin/prasarana/fetch',
    [App\Http\Controllers\AdminController::class, 'fetch_prasarana'])->name('admin.prasarana.fetch');

Route::get('admin/sarana',
    [App\Http\Controllers\AdminController::class, 'sarana'])->name('admin.sarana')->middleware('admin');
Route::post('admin/sarana', 
    [App\Http\Controllers\AdminController::class, 'submit_sarana'])->name('admin.sarana.submit')->middleware('admin');
Route::patch('admin/sarana', 
    [App\Http\Controllers\AdminController::class, 'update_sarana'])->name('admin.sarana.update')->middleware('admin');
 Route::get('admin/sarana/ajaxadmin/dataSarana/{id}', 
    [App\Http\Controllers\AdminController::class, 'getDataSarana']);
Route::post('admin/sarana/delete/{id}', 
    [App\Http\Controllers\AdminController::class, 'delete_sarana'])->name('admin.sarana.delete')->middleware('admin');

Route::get('admin/barang_masuk',
    [App\Http\Controllers\AdminController::class, 'barang_masuk'])->name('admin.barangm')->middleware('admin');
Route::post('admin/barang_masuk', 
    [App\Http\Controllers\AdminController::class, 'submit_barang_masuk'])->name('admin.barangm.submit')->middleware('admin');
Route::patch('admin/barang_masuk', 
    [App\Http\Controllers\AdminController::class, 'update_barang_masuk'])->name('admin.barangm.update')->middleware('admin');
 Route::get('admin/barang_masuk/ajaxadmin/dataBarangM/{id}', 
    [App\Http\Controllers\AdminController::class, 'getDataBarangMasuk']);
Route::post('admin/barang_masuk/delete/{id}', 
    [App\Http\Controllers\AdminController::class, 'delete_barang_masuk'])->name('admin.barangm.delete')->middleware('admin');
Route::get('admin/barang_masuk/fetch',
    [App\Http\Controllers\AdminController::class, 'fetch_barang_masuk'])->name('admin.barangm.fetch');

Route::get('admin/barang_keluar',
    [App\Http\Controllers\AdminController::class, 'barang_keluar'])->name('admin.barangk')->middleware('admin');
Route::post('admin/barang_keluar', 
    [App\Http\Controllers\AdminController::class, 'submit_barang_keluar'])->name('admin.barangk.submit')->middleware('admin');
Route::patch('admin/barang_keluar', 
    [App\Http\Controllers\AdminController::class, 'update_barang_keluar'])->name('admin.barangk.update')->middleware('admin');
 Route::get('admin/barang_keluar/ajaxadmin/dataBarangK/{id}', 
    [App\Http\Controllers\AdminController::class, 'getDataBarangKeluar']);
Route::post('admin/barang_keluar/delete/{id}', 
    [App\Http\Controllers\AdminController::class, 'delete_barang_keluar'])->name('admin.barangk.delete')->middleware('admin');

Route::get('admin/peminjaman_sarana',
    [App\Http\Controllers\AdminController::class, 'peminjaman'])->name('admin.peminjaman')->middleware('admin');
Route::post('admin/peminjaman_sarana', 
    [App\Http\Controllers\AdminController::class, 'submit_peminjaman'])->name('admin.peminjaman.submit')->middleware('admin');
Route::patch('admin/peminjaman_sarana', 
    [App\Http\Controllers\AdminController::class, 'update_peminjaman'])->name('admin.peminjaman.update')->middleware('admin');
 Route::get('admin/peminjaman_sarana/ajaxadmin/dataPeminjaman/{id}', 
    [App\Http\Controllers\AdminController::class, 'getDataPeminjaman']);
Route::post('admin/peminjaman_sarana/delete/{id}', 
    [App\Http\Controllers\AdminController::class, 'delete_peminjaman'])->name('admin.peminjaman.delete')->middleware('admin');
Route::get('admin/peminjaman_sarana/terima/{id}',
    [App\Http\Controllers\AdminController::class, 'terima_peminjaman'])->name('admin.peminjaman.terima')->middleware('admin');
Route::get('admin/peminjaman_sarana/tolak/{id}',
    [App\Http\Controllers\AdminController::class, 'tolak_peminjaman'])->name('admin.peminjaman.tolak')->middleware('admin');

Route::get('admin/pengembalian_sarana',
    [App\Http\Controllers\AdminController::class, 'pengembalian'])->name('admin.pengembalian')->middleware('admin');
Route::post('admin/pengembalian_sarana', 
    [App\Http\Controllers\AdminController::class, 'submit_pengembalian'])->name('admin.pengembalian.submit')->middleware('admin');
Route::patch('admin/pengembalian_sarana', 
    [App\Http\Controllers\AdminController::class, 'update_pengembalian'])->name('admin.pengembalian.update')->middleware('admin');
 Route::get('admin/pengembalian_sarana/ajaxadmin/dataPengembalian/{id}', 
    [App\Http\Controllers\AdminController::class, 'getDataPengembalian']);
Route::post('admin/pengembalian_sarana/delete/{id}', 
    [App\Http\Controllers\AdminController::class, 'delete_pengembalian'])->name('admin.pengembalian.delete')->middleware('admin');
Route::get('admin/pengembalian_sarana/fetch',
    [App\Http\Controllers\AdminController::class, 'fetch_pengembalian'])->name('admin.pengembalian.fetch');

Route::get('admin/peminjaman_ruangan',
    [App\Http\Controllers\AdminController::class, 'peminjaman_ruangan'])->name('admin.peminjaman.ruangan')->middleware('admin');
Route::post('admin/peminjaman_ruangan', 
    [App\Http\Controllers\AdminController::class, 'submit_peminjaman_ruangan'])->name('admin.peminjaman.ruangan.submit')->middleware('admin');
Route::patch('admin/peminjaman_ruangan', 
    [App\Http\Controllers\AdminController::class, 'update_peminjaman_ruangan'])->name('admin.peminjaman.ruangan.update')->middleware('admin');
 Route::get('admin/peminjaman_ruangan/ajaxadmin/dataPeminjaman/{id}', 
    [App\Http\Controllers\AdminController::class, 'getDataPeminjamanRuangan']);
Route::post('admin/peminjaman_ruangan/delete/{id}', 
    [App\Http\Controllers\AdminController::class, 'delete_peminjaman_ruangan'])->name('admin.peminjaman.ruangan.delete')->middleware('admin');
Route::get('admin/peminjaman_ruangan/terima/{id}',
    [App\Http\Controllers\AdminController::class, 'terima_peminjaman_ruangan'])->name('admin.peminjaman.ruangan.terima')->middleware('admin');
Route::get('admin/peminjaman_ruangan/tolak/{id}',
    [App\Http\Controllers\AdminController::class, 'tolak_peminjaman_ruangan'])->name('admin.peminjaman.ruangan.tolak')->middleware('admin');

Route::get('admin/pengembalian_ruangan',
    [App\Http\Controllers\AdminController::class, 'pengembalian_ruangan'])->name('admin.pengembalian.ruangan')->middleware('admin');
Route::post('admin/pengembalian_ruangan', 
    [App\Http\Controllers\AdminController::class, 'submit_pengembalian_ruangan'])->name('admin.pengembalian.ruangan.submit')->middleware('admin');
Route::patch('admin/pengembalian_ruangan', 
    [App\Http\Controllers\AdminController::class, 'update_pengembalian_ruangan'])->name('admin.pengembalian.ruangan.update')->middleware('admin');
 Route::get('admin/pengembalian_ruangan/ajaxadmin/dataPengembalian/{id}', 
    [App\Http\Controllers\AdminController::class, 'getDataPengembalianRuangan']);
Route::post('admin/pengembalian_ruangan/delete/{id}', 
    [App\Http\Controllers\AdminController::class, 'delete_pengembalian_ruangan'])->name('admin.pengembalian.ruangan.delete')->middleware('admin');
Route::get('admin/pengembalian_ruangan/fetch',
    [App\Http\Controllers\AdminController::class, 'fetch_pengembalian_ruangan'])->name('admin.pengembalian.ruangan.fetch');
Route::get('admin/ruangan/fetch',
    [App\Http\Controllers\AdminController::class, 'fetch_ruangan'])->name('admin.ruangan.fetch');

Route::get('admin/riwayat_denda',
    [App\Http\Controllers\AdminController::class, 'riwayat_denda'])->name('admin.denda')->middleware('admin');

Route::get('admin/laporan',
    [App\Http\Controllers\AdminController::class, 'laporan'])->name('admin.laporan')->middleware('admin');
Route::post('admin/laporan', 
    [App\Http\Controllers\AdminController::class, 'submit_laporan'])->name('admin.laporan.submit')->middleware('admin');
Route::patch('admin/laporan', 
    [App\Http\Controllers\AdminController::class, 'update_laporan'])->name('admin.laporan.update')->middleware('admin');
Route::get('admin/laporan/ajaxadmin/dataLaporan/{id}', 
    [App\Http\Controllers\AdminController::class, 'getDataLaporan']);
Route::post('admin/laporan/delete/{id}', 
    [App\Http\Controllers\AdminController::class, 'delete_laporan'])->name('admin.laporan.delete')->middleware('admin');
Route::get('admin/laporan/print/{dari}/{sampai}',
    [App\Http\Controllers\AdminController::class, 'print'])->name('admin.laporan.print')->middleware('admin');

Route::get('admin/user',
    [App\Http\Controllers\AdminController::class, 'data_user'])->name('admin.pengguna')->middleware('admin');
Route::post('admin/user', 
    [App\Http\Controllers\AdminController::class, 'submit_user'])->name('admin.pengguna.submit')->middleware('admin');
Route::patch('admin/user/update', 
    [App\Http\Controllers\AdminController::class, 'update_user'])->name('admin.pengguna.update')->middleware('admin');
Route::post('admin/user/delete/{id}',
    [App\Http\Controllers\AdminController::class, 'delete_user'])->name('admin.pengguna.delete')->middleware('admin');
Route::get('admin/ajaxadmin/dataUser/{id}', 
    [App\Http\Controllers\AdminController::class, 'getDataUser']);

Route::get('user/home',
    [App\Http\Controllers\HomeController::class, 'index'])->name('user.home')->middleware('user');
Route::get('user/riwayat_denda',
    [App\Http\Controllers\HomeController::class, 'riwayat_denda'])->name('user.denda')->middleware('user');
Route::get('user/pengembalian',
    [App\Http\Controllers\HomeController::class, 'pengembalian'])->name('user.pengembalian')->middleware('user');
Route::get('user/peminjaman',
    [App\Http\Controllers\HomeController::class, 'peminjaman'])->name('user.peminjaman')->middleware('user');
Route::patch('user/peminjaman', 
    [App\Http\Controllers\HomeController::class, 'peminjaman_submit'])->name('user.peminjaman.submit')->middleware('user');
 Route::get('user/peminjaman/ajaxadmin/dataPeminjaman/{id}', 
    [App\Http\Controllers\HomeController::class, 'getDataPeminjaman']);
Route::post('user/peminjaman/delete/{id}', 
    [App\Http\Controllers\HomeController::class, 'delete_peminjaman'])->name('user.peminjaman.delete')->middleware('user');

Route::get('user/pinjam_prasarana',
    [App\Http\Controllers\HomeController::class, 'pinjam_prasarana'])->name('user.pinjam.prasarana')->middleware('user');
Route::post('user/pinjam_prasarana/delete/{id}', 
    [App\Http\Controllers\HomeController::class, 'delete_pinjam_prasarana'])->name('user.peminjaman.prasarana.delete')->middleware('user');
Route::get('user/pengembalian_prasarana',
    [App\Http\Controllers\HomeController::class, 'pengembalian_prasarana'])->name('user.pengembalian.prasarana')->middleware('user');
Route::get('user/sarana',
    [App\Http\Controllers\HomeController::class, 'sarana'])->name('user.sarana')->middleware('user');
Route::patch('user/sarana', 
    [App\Http\Controllers\HomeController::class, 'submit_peminjaman_ruangan'])->name('user.peminjam.ruangan.submit')->middleware('user');
Route::get('user/prasarana',
    [App\Http\Controllers\HomeController::class, 'prasarana'])->name('user.prasarana')->middleware('user');
