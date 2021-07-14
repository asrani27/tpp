<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('login',function(){
    return response()->json('test');
});

Route::post('/login','API\LoginController@login');
Route::get('/pegawai/{nip}','API\PegawaiController@pegawai');
Route::get('/pegawai/skpd/{id}','API\PegawaiController@pegawaiSkpd');
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
