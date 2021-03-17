<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'LoginController@index');
Route::post('/login', 'LoginController@login');
Route::get('/login', function(){
    return redirect('/');
})->name('login');
Route::get('/logout', 'LoginController@logout');

Route::group(['middleware' => ['auth', 'role:superadmin']], function () {
    Route::get('/home/superadmin', 'HomeController@superadmin');
    Route::get('/superadmin/profil', 'ProfilController@superadmin');
    Route::post('/superadmin/profil', 'ProfilController@changeSuperadmin');
    Route::get('/superadmin/skpd', 'SuperadminController@skpd');
    Route::get('/superadmin/skpd/jabatan/{skpd_id}', 'SuperadminController@jabatan');
    Route::post('/superadmin/skpd/jabatan/{skpd_id}', 'SuperadminController@storeJabatan');
    Route::get('/superadmin/skpd/jabatan/{skpd_id}/edit/{id}', 'SuperadminController@editJabatan');
    Route::post('/superadmin/skpd/jabatan/{skpd_id}/edit/{id}', 'SuperadminController@updateJabatan');
    Route::get('/superadmin/skpd/jabatan/{skpd_id}/delete/{id}', 'SuperadminController@deleteJabatan');
    Route::get('/superadmin/skpd/createuser', 'SuperadminController@userSkpd');
    Route::get('/superadmin/skpd/createuser/{skpd_id}', 'SuperadminController@userSkpdId');
    Route::get('/superadmin/skpd/deleteuser', 'SuperadminController@deleteUserSkpd');
    Route::get('/superadmin/skpd/add', 'SuperadminController@addSkpd');
    Route::post('/superadmin/skpd/add', 'SuperadminController@storeSkpd');
    Route::get('/superadmin/skpd/edit/{skpd_id}', 'SuperadminController@editSkpd');
    Route::post('/superadmin/skpd/edit/{skpd_id}', 'SuperadminController@updateSkpd');
    Route::get('/superadmin/skpd/delete/{skpd_id}', 'SuperadminController@deleteSkpd');
    
    Route::get('/superadmin/pegawai', 'SuperadminController@pegawai');
    Route::get('/superadmin/pegawai/add', 'SuperadminController@addPegawai');
    Route::post('/superadmin/pegawai/add', 'SuperadminController@storePegawai');
    Route::get('/superadmin/pegawai/delete/{id}', 'SuperadminController@deletePegawai');
    Route::get('/superadmin/pegawai/edit/{id}', 'SuperadminController@editPegawai');
    Route::post('/superadmin/pegawai/edit/{id}', 'SuperadminController@updatePegawai');
    Route::get('/superadmin/pegawai/createuser/{id}', 'SuperadminController@userPegawaiId');
    
    Route::get('/superadmin/kelas', 'SuperadminController@kelas');
    Route::get('/superadmin/kelas/add', 'SuperadminController@addKelas');
    Route::post('/superadmin/kelas/add', 'SuperadminController@storeKelas');
    Route::get('/superadmin/kelas/edit/{id}', 'SuperadminController@editKelas');
    Route::post('/superadmin/kelas/edit/{id}', 'SuperadminController@updateKelas');
    Route::get('/superadmin/kelas/delete/{id}', 'SuperadminController@deleteKelas');

    Route::get('/superadmin/pangkat', 'SuperadminController@pangkat');
    Route::get('/superadmin/eselon', 'SuperadminController@eselon');

});

Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::get('/home/admin', 'HomeController@admin');
});

Route::group(['middleware' => ['auth', 'role:pegawai']], function () {
    Route::get('/home/pegawai', 'HomeController@pegawai');
    Route::get('/pegawai/aktivitas', 'AktivitasController@index');
    Route::get('/pegawai/aktivitas/add', 'AktivitasController@add');
    Route::get('/pegawai/verifikasi', 'VerifikasiController@index');
    Route::get('/pegawai/verifikasi/detail', 'VerifikasiController@detail');
    Route::get('/pegawai/verifikasi/jurnal', 'VerifikasiController@jurnal');
    Route::get('/pegawai/riwayat', 'RiwayatController@index');
    Route::get('/pegawai/tpp', 'TppController@index');
    Route::get('/pegawai/tpp/grafik', 'TppController@grafik');
});

Route::group(['middleware' => ['auth', 'role:walikota']], function () {
    Route::get('/home/walikota', 'HomeController@walikota');
});
