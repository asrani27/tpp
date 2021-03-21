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
    Route::get('/superadmin/skpd/pegawai/{skpd_id}', 'SuperadminController@pegawaiSkpd');
    Route::get('/superadmin/skpd/pegawai/{skpd_id}/add', 'SuperadminController@addPegawaiSkpd');
    Route::post('/superadmin/skpd/pegawai/{skpd_id}/add', 'SuperadminController@storePegawaiSkpd');
    Route::get('/superadmin/skpd/pegawai/{skpd_id}/edit/{id}', 'SuperadminController@editPegawaiSkpd');
    Route::post('/superadmin/skpd/pegawai/{skpd_id}/edit/{id}', 'SuperadminController@updatePegawaiSkpd');
    Route::get('/superadmin/skpd/pegawai/{skpd_id}/delete/{id}', 'SuperadminController@deletePegawaiSkpd');
    Route::get('/superadmin/skpd/jabatan/{skpd_id}', 'SuperadminController@jabatan');
    Route::post('/superadmin/skpd/jabatan/{skpd_id}', 'SuperadminController@storeJabatan');
    Route::get('/superadmin/skpd/jabatan/{skpd_id}/edit/{id}', 'SuperadminController@editJabatan');
    Route::post('/superadmin/skpd/jabatan/{skpd_id}/edit/{id}', 'SuperadminController@updateJabatan');
    Route::get('/superadmin/skpd/jabatan/{skpd_id}/delete/{id}', 'SuperadminController@deleteJabatan');
    Route::get('/superadmin/skpd/createuser', 'SuperadminController@userSkpd');
    Route::get('/superadmin/skpd/createuser/{skpd_id}', 'SuperadminController@userSkpdId');
    Route::get('/superadmin/skpd/resetpassword/{skpd_id}', 'SuperadminController@resetPassUserSkpdId');
    Route::get('/superadmin/skpd/deleteuser', 'SuperadminController@deleteUserSkpd');
    Route::get('/superadmin/skpd/add', 'SuperadminController@addSkpd');
    Route::post('/superadmin/skpd/add', 'SuperadminController@storeSkpd');
    Route::get('/superadmin/skpd/edit/{skpd_id}', 'SuperadminController@editSkpd');
    Route::post('/superadmin/skpd/edit/{skpd_id}', 'SuperadminController@updateSkpd');
    Route::get('/superadmin/skpd/delete/{skpd_id}', 'SuperadminController@deleteSkpd');
    
    Route::get('/superadmin/mutasi', 'SuperadminController@mutasi');
    
    Route::get('/superadmin/parameter', 'SuperadminController@parameter');
    Route::get('/superadmin/parameter/edit/{id}', 'SuperadminController@editParameter');
    Route::post('/superadmin/parameter/edit/{id}', 'SuperadminController@updateParameter');

    Route::get('/superadmin/pegawai', 'SuperadminController@pegawai');
    Route::get('/superadmin/pegawai/search', 'SuperadminController@searchPegawai');
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
    Route::get('/superadmin/pangkat/add', 'SuperadminController@addPangkat');
    Route::get('/superadmin/pangkat/edit/{id}', 'SuperadminController@editPangkat');
    Route::post('/superadmin/pangkat/edit/{id}', 'SuperadminController@updatePangkat');
    Route::post('/superadmin/pangkat/add', 'SuperadminController@storePangkat');
    Route::get('/superadmin/pangkat/delete/{id}', 'SuperadminController@deletePangkat');

    Route::get('/superadmin/eselon', 'SuperadminController@eselon');
    Route::get('/superadmin/eselon/add', 'SuperadminController@addEselon');
    Route::get('/superadmin/eselon/edit/{id}', 'SuperadminController@editEselon');
    Route::post('/superadmin/eselon/edit/{id}', 'SuperadminController@updateEselon');
    Route::post('/superadmin/eselon/add', 'SuperadminController@storeEselon');
    Route::get('/superadmin/eselon/delete/{id}', 'SuperadminController@deleteEselon');

});

Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::get('/home/admin', 'HomeController@admin');
    
    Route::get('/home/admin/up/{id}/{urutan}', 'HomeController@adminUp');
    Route::get('/home/admin/down/{id}/{urutan}', 'HomeController@adminDown');

    Route::get('/admin/profil', 'ProfilController@admin');
    Route::get('/admin/pegawai', 'AdminController@pegawai');
    Route::get('/admin/pegawai/add', 'AdminController@addPegawai');
    Route::post('/admin/pegawai/add', 'AdminController@storePegawai');
    Route::get('/admin/pegawai/edit/{id}', 'AdminController@editPegawai');
    Route::post('/admin/pegawai/edit/{id}', 'AdminController@updatePegawai');
    Route::get('/admin/pegawai/delete/{id}', 'AdminController@deletePegawai');
    Route::get('/admin/pegawai/createuser/{id}', 'AdminController@createUser');
    Route::get('/admin/pegawai/resetpass/{id}', 'AdminController@resetPass');
    
    Route::get('/admin/jabatan', 'AdminController@jabatan');
    Route::post('/admin/jabatan', 'AdminController@storeJabatan');
    Route::get('/admin/jabatan/edit/{id}', 'AdminController@editJabatan');
    Route::post('/admin/jabatan/edit/{id}', 'AdminController@updateJabatan');
    Route::get('/admin/jabatan/delete/{id}', 'AdminController@deleteJabatan');
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
