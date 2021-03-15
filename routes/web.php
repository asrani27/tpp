<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'LoginController@index');
Route::post('/login', 'LoginController@login');
Route::get('/logout', 'LoginController@logout');

Route::get('/home', 'HomeController@home');
Route::get('/pegawai/aktivitas', 'AktivitasController@index');
Route::get('/pegawai/aktivitas/add', 'AktivitasController@add');
Route::get('/pegawai/verifikasi', 'VerifikasiController@index');
Route::get('/pegawai/verifikasi/detail', 'VerifikasiController@detail');
Route::get('/pegawai/verifikasi/jurnal', 'VerifikasiController@jurnal');
Route::get('/pegawai/riwayat', 'RiwayatController@index');
Route::get('/pegawai/tpp', 'TppController@index');
Route::get('/pegawai/tpp/grafik', 'TppController@grafik');

