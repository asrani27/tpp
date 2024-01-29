<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('login', function () {
//     return response()->json('test');
// });

Route::post('/login', 'API\LoginController@login');
Route::post('/login_m', 'API\LoginController@login_m');
Route::get('/pegawai', 'API\PegawaiController@allpegawai');
Route::get('/pegawai/{nip}', 'API\PegawaiController@pegawai');
Route::get('/pegawai/aktivitas/{nip}/{bulan}/{tahun}', 'API\PegawaiController@aktivitaspegawai');
Route::get('/pegawai/skpd/{id}', 'API\PegawaiController@pegawaiSkpd');
Route::get('/skpd', 'API\SkpdController@all');
Route::get('/pegawaibanjarmasinkotagoid', 'API\PegawaiController@pegawaibanjarmasin');
Route::get('/pegawaibynip/{nip}', 'API\PegawaiController@pegawaibynip');
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
