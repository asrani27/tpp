<?php

use Illuminate\Support\Facades\Route;

// Route::get('/dataasnte', 'HomeController@upload');
// Route::post('/testinguploadfiletonas', 'HomeController@storeUpload');

Route::group(['middleware' => ['XSS']], function () {
    Route::get('/', 'LoginController@index');
    Route::get('/export', 'HomeController@export');
    Route::post('/login', 'LoginController@login');
    Route::get('/login', 'LoginController@redirectLogin')->name('login');

    Route::get('/logout', 'LoginController@logout');

    Route::group(['middleware' => ['auth', 'role:superadmin']], function () {
        Route::get('/home/superadmin', 'HomeController@superadmin');
        Route::get('/home/superadmin/parametertpp', 'HomeController@parametertpp');
        Route::get('/home/superadmin/parametertpppuskesmas', 'HomeController@parametertpppuskesmas');
        Route::get('/home/superadmin/allpegawai', 'HomeController@allpegawai');
        Route::post('/home/superadmin/exportpegawai', 'HomeController@exportPegawai');

        Route::get('/superadmin/tpp/{bulan}/{tahun}', 'TppController@tppBulanTahun');
        Route::get('/superadmin/tpp/{bulan}/{tahun}/laporan/{id}', 'TppController@tppSkpd');
        Route::post('/superadmin/profil', 'ProfilController@changeSuperadmin');
        Route::get('/superadmin/skpd', 'SuperadminController@skpd');
        Route::get('/superadmin/skpd/login/{id}', 'SuperadminController@loginSkpd');
        Route::get('/superadmin/skpd/createsuperadmin', 'SuperadminController@createSuperadmin');

        Route::get('/superadmin/skpd/pegawai/{skpd_id}/import', 'SuperadminController@addImport');
        Route::post('/superadmin/skpd/pegawai/{skpd_id}/import', 'SuperadminController@importPegawai');

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
        Route::get('/superadmin/skpd/pegawai/createuser/{skpd_id}', 'SuperadminController@userPegawaiSkpdId');

        Route::get('/superadmin/mutasi', 'MutasiController@mutasi');
        Route::get('/superadmin/mutasi/plt', 'MutasiController@plt');
        Route::get('/superadmin/mutasi/history/plt', 'MutasiController@historyPlt');

        Route::get('/superadmin/parameter', 'SuperadminController@parameter');
        Route::get('/superadmin/parameter/edit/{id}', 'SuperadminController@editParameter');
        Route::post('/superadmin/parameter/edit/{id}', 'SuperadminController@updateParameter');

        Route::get('/superadmin/pegawai', 'SuperadminController@pegawai');
        Route::get('/superadmin/pegawai/search', 'SuperadminController@searchPegawai');
        Route::get('/superadmin/pegawai/add', 'SuperadminController@addPegawai');
        Route::post('/superadmin/pegawai/add', 'SuperadminController@storePegawai');
        Route::get('/superadmin/pegawai/delete/{id}', 'SuperadminController@deletePegawai');
        Route::get('/superadmin/pegawai/edit/{id}', 'SuperadminController@editPegawai');
        Route::get('/superadmin/pegawai/aktivitas/{id}', 'SuperadminController@aktivitasPegawai');
        Route::get('/superadmin/pegawai/aktivitas/{id}/{bulan}/{tahun}', 'SuperadminController@detailAktivitasPegawai');
        Route::post('/superadmin/pegawai/edit/{id}', 'SuperadminController@updatePegawai');
        Route::get('/superadmin/pegawai/createuser/{id}', 'SuperadminController@userPegawaiId');
        Route::get('/superadmin/pegawai/resetpassword/{id}', 'SuperadminController@resetPassPegawaiId');

        Route::get('/superadmin/kelas', 'SuperadminController@kelas');
        Route::get('/superadmin/kelas/add', 'SuperadminController@addKelas');
        Route::post('/superadmin/kelas/add', 'SuperadminController@storeKelas');
        Route::get('/superadmin/kelas/edit/{id}', 'SuperadminController@editKelas');
        Route::post('/superadmin/kelas/edit/{id}', 'SuperadminController@updateKelas');
        Route::get('/superadmin/kelas/delete/{id}', 'SuperadminController@deleteKelas');

        Route::get('/superadmin/aktivitas', 'SuperadminController@aktivitas');
        Route::get('/superadmin/aktivitas/setuju', 'SuperadminController@aktivitasSetuju');
        Route::get('/superadmin/aktivitas/tolak', 'SuperadminController@aktivitasTolak');
        Route::get('/superadmin/aktivitas/proses', 'SuperadminController@aktivitasProses');
        Route::get('/superadmin/aktivitas/sistem', 'SuperadminController@aktivitasSistem');
        Route::get('/superadmin/aktivitas/search', 'SuperadminController@aktivitasSearch');
        Route::get('/superadmin/aktivitas/setujui/{id}', 'SuperadminController@aktivitasSetujui');

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
        Route::get('/superadmin/parameter/jabatan/edit', 'SuperadminController@topLevel');
        Route::get('/superadmin/parameter/sekda/{id}', 'SuperadminController@sekda');
        Route::get('/superadmin/parameter/jabatan/search', 'SuperadminController@searchSekda');

        Route::get('/superadmin/rekapitulasi/pns', 'SuperadminController@rekapASN');
        Route::get('/superadmin/rekapitulasi/pns/data/{param}', 'SuperadminController@rekapData');
        Route::get('/superadmin/rekapitulasi/pns/eselon/{param}', 'SuperadminController@rekapDataEselon');
        Route::get('/superadmin/rekapitulasi/pns/golongan/{param}', 'SuperadminController@rekapDataGolongan');
        Route::get('/superadmin/rekapitulasi/pns/jkel', 'SuperadminController@rekapASNjkel');
        Route::get('/superadmin/rekapitulasi/pns/kelas-jabatan', 'SuperadminController@rekapASNkelas');
        Route::get('/superadmin/rekapitulasi/pns/kelas-jabatan/search', 'SuperadminController@searchRekapASNkelas');
        Route::get('/superadmin/rekapitulasi/pns/tingkat-pendidikan', 'SuperadminController@rekapASNpendidikan');
        Route::get('/superadmin/rekapitulasi/pns/tingkat-pendidikan/search', 'SuperadminController@searchRekapASNpendidikan');


        Route::get('/superadmin/persentase', 'PersenController@index');
        Route::get('/superadmin/persentase/skpd/{id}', 'PersenController@detailSkpd');
        Route::get('/superadmin/persentase/skpd/{id}/verifikasi', 'PersenController@verifikasi');
        Route::get('/superadmin/persentase/skpd/{id}/unverifikasi', 'PersenController@unverifikasi');
        Route::get('/superadmin/persentase/puskesmas/{id}', 'PersenController@detailPuskesmas');
        Route::get('/superadmin/persentase/skpd/{skpd_id}/edit/{id}', 'PersenController@editPersen');
        Route::post('/superadmin/persentase/skpd/{skpd_id}/edit/{id}', 'PersenController@updatePersen');
        Route::get('/superadmin/persentase/puskesmas/{puskesmas_id}/edit/{id}', 'PersenController@editPersenPuskesmas');
        Route::post('/superadmin/persentase/puskesmas/{puskesmas_id}/edit/{id}', 'PersenController@updatePersenPuskesmas');
        Route::get('/superadmin/persentase/subkoordinator/ya/{id}', 'PersenController@subK');
        Route::get('/superadmin/persentase/subkoordinator/tidak/{id}', 'PersenController@nonSubK');
    });

    Route::group(['middleware' => ['auth', 'role:admin']], function () {
        Route::get('/home/admin', 'HomeController@admin');
        Route::get('/home/excel', 'ExcelController@index');

        Route::get('/home/admin/persen', 'AdminController@editPersen');
        Route::get('/home/admin/persen/edit/{id}', 'AdminController@editPersentase');
        Route::post('/home/admin/persen/edit/{id}', 'AdminController@updatePersentase');
        Route::post('/home/admin/persen', 'AdminController@updatePersen');
        Route::get('/home/admin/up/{id}/{urutan}', 'HomeController@adminUp');
        Route::get('/home/admin/down/{id}/{urutan}', 'HomeController@adminDown');
        Route::get('/home/admin/tpp', 'AdminController@tpp');

        Route::get('/admin/profil', 'ProfilController@admin');
        Route::post('/admin/profil', 'ProfilController@changeAdmin');
        Route::get('/admin/org', 'AdminController@org');
        Route::get('/admin/org2', 'AdminController@org2');
        Route::get('/admin/pegawai', 'AdminController@pegawai');
        Route::get('/admin/pegawai/checktobkd', 'AdminController@checktobkd');
        Route::get('/admin/pegawai/search', 'AdminController@searchPegawai');
        Route::get('/admin/pegawai/add', 'AdminController@addPegawai');
        Route::post('/admin/pegawai/add', 'AdminController@storePegawai');
        Route::get('/admin/pegawai/edit/{id}', 'AdminController@editPegawai');
        Route::post('/admin/pegawai/edit/{id}', 'AdminController@updatePegawai');
        Route::get('/admin/pegawai/delete/{id}', 'AdminController@deletePegawai');
        Route::get('/admin/pegawai/createuser/{id}', 'AdminController@createUser');
        Route::get('/admin/pegawai/resetpass/{id}', 'AdminController@resetPass');

        Route::get('/admin/presensi', 'PresensiController@index');
        Route::get('/admin/presensi/edit', 'PresensiController@edit');
        Route::post('/admin/presensi/edit', 'PresensiController@update');
        Route::get('/admin/presensi/list', 'PresensiController@list');
        Route::get('/admin/presensi/{bulan}/{tahun}', 'PresensiController@editBulanTahun');
        Route::post('/admin/presensi/{bulan}/{tahun}', 'PresensiController@updateBulanTahun');

        Route::get('/admin/plt', 'PltController@admin');
        Route::post('/admin/plt/add', 'PltController@adminStorePlt');
        Route::get('/admin/plt/delete/{id}', 'PltController@adminDeletePlt');

        Route::get('/admin/plh', 'PlhController@admin');
        Route::post('/admin/plh/add', 'PlhController@adminStorePlh');
        Route::get('/admin/plh/delete/{id}', 'PlhController@adminDeletePlh');

        Route::get('/admin/transfer', 'TransferController@admin');
        Route::post('/admin/transfer/add', 'TransferController@adminStoreTransfer');
        Route::get('/admin/transfer/delete/{id}', 'TransferController@adminDeleteTransfer');

        Route::get('/admin/pensiun', 'PensiunController@admin');
        Route::post('/admin/pensiun/add', 'PensiunController@adminStorePensiun');
        Route::get('/admin/pensiun/delete/{id}', 'PensiunController@adminDeletePensiun');

        Route::get('/admin/mutasikeluar', 'MutasiKeluarController@index');
        Route::post('/admin/mutasikeluar/add', 'MutasiKeluarController@store');
        Route::get('/admin/mutasikeluar/delete/{id}', 'MutasiKeluarController@delete');

        Route::get('/admin/rspuskesmas', 'RsController@index');
        Route::get('/admin/rspuskesmas/add', 'RsController@create');
        Route::post('/admin/rspuskesmas/add', 'RsController@store');
        Route::get('/admin/rspuskesmas/{id}/edit', 'RsController@edit');
        Route::post('/admin/rspuskesmas/{id}/edit', 'RsController@update');
        Route::get('/admin/rspuskesmas/{id}/delete', 'RsController@destroy');
        Route::get('/admin/rspuskesmas/{id}/petajabatan', 'RsController@jabatan');
        Route::get('/admin/rspuskesmas/createuserpuskesmas', 'RsController@createuserpuskesmas');

        Route::post('/admin/rspuskesmas/{id}/petajabatan', 'RsController@storeJabatan');
        Route::get('/admin/rspuskesmas/{id}/petajabatan/{idJab}/delete', 'RsController@deleteJabatan');
        Route::get('/admin/rspuskesmas/{id}/petajabatan/{idJab}/edit', 'RsController@editJabatan');
        Route::post('/admin/rspuskesmas/{id}/petajabatan/{idJab}/edit', 'RsController@updateJabatan');

        Route::get('/admin/jabatan', 'AdminController@jabatan');
        Route::post('/admin/jabatan', 'AdminController@storeJabatan');
        Route::get('/admin/jabatan/edit/{id}', 'AdminController@editJabatan');
        Route::post('/admin/jabatan/edit/{id}', 'AdminController@updateJabatan');
        Route::get('/admin/jabatan/delete/{id}', 'AdminController@deleteJabatan');

        Route::get('/admin/rekapitulasi/plt', 'RekapitulasiPltController@index');
        Route::get('/admin/rekapitulasi/plt/{bulan}/{tahun}', 'RekapitulasiPltController@bulanTahun');
        Route::get('/admin/rekapitulasi/plt/{bulan}/{tahun}/create', 'RekapitulasiPltController@create');
        Route::post('/admin/rekapitulasi/plt/{bulan}/{tahun}/create', 'RekapitulasiPltController@store');
        Route::get('/admin/rekapitulasi/plt/{bulan}/{tahun}/perhitungan', 'RekapitulasiPltController@perhitungan');
        Route::get('/admin/rekapitulasi/plt/{bulan}/{tahun}/pembayaran', 'RekapitulasiPltController@pembayaran');
        Route::get('/admin/rekapitulasi/plt/{bulan}/{tahun}/{id}/delete', 'RekapitulasiPltController@delete');

        Route::get('/admin/rekapitulasi', 'RekapitulasiController@index');
        Route::get('/admin/rekapitulasi/cetaktpp', 'RekapitulasiController@cetaktpp');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}', 'RekapitulasiController@bulanTahun');
        //-------new route rekap tpp 2023--------//

        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/tarikter', 'RekapitulasiController@tarikter');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/tarikterlabkes', 'RekapitulasiController@tarikterlabkes');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/tarikterregulerrs', 'RekapitulasiController@tarikterregulerrs');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/tu/tarikter', 'RekapitulasiController@tariktertu');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/kuncitpp', 'RekapitulasiController@kuncitpp');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/kuncitpptu', 'RekapitulasiController@kuncitpptu');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/kuncitpp/regulerrs', 'RekapitulasiController@kuncitppregulerrs');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/kuncitpp/puskes', 'RekapitulasiController@kuncitpppuskes');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/kuncitpp/ifk', 'RekapitulasiController@kuncitppifk');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/kuncitpp/labkes', 'RekapitulasiController@kuncitpplabkes');

        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/reguler', 'RekapitulasiController@reguler');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/reguler/mp', 'RekapitulasiController@reguler_mp');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/reguler/psa', 'RekapitulasiController@reguler_psa');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/reguler/perhitungan', 'RekapitulasiController@reguler_perhitungan');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/reguler/{id}/delete', 'RekapitulasiController@reguler_delete');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/reguler/excel', 'RekapitulasiController@reguler_excel');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/reguler/excel/setda', 'RekapitulasiController@reguler_excel_setda');
        Route::post('/admin/rekapitulasi/bpjs/reguler', 'RekapitulasiController@reguler_bpjs');

        Route::post('/admin/rekapitulasi/editjabatan/reguler', 'RekapitulasiController@reguler_editjabatan');
        Route::post('/admin/rekapitulasi/{bulan}/{tahun}/tambahpegawai/reguler', 'RekapitulasiController@reguler_tambahpegawai');
        Route::post('admin/rekapitulasi/getJabatan', 'RekapitulasiController@getJabatan');
        Route::post('admin/rekapitulasi/getPegawai', 'RekapitulasiController@getPegawai');

        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/tu', 'RekapitulasiController@tu');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/tu/mp', 'RekapitulasiController@tu_mp');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/tu/psa', 'RekapitulasiController@tu_psa');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/tu/perhitungan', 'RekapitulasiController@tu_perhitungan');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/tu/{id}/delete', 'RekapitulasiController@reguler_delete');
        Route::post('/admin/rekapitulasi/bpjs/tu', 'RekapitulasiController@tu_bpjs');

        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/cpns', 'RekapitulasiController@cpns');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/cpns/mp', 'RekapitulasiController@cpns_mp');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/cpns/psa', 'RekapitulasiController@cpns_psa');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/cpns/perhitungan', 'RekapitulasiController@cpns_perhitungan');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/cpns/{id}/delete', 'RekapitulasiController@cpns_delete');
        Route::post('/admin/rekapitulasi/bpjs/cpns', 'RekapitulasiController@cpns_bpjs');

        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/plt', 'RekapitulasiController@plt');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/plt/psa', 'RekapitulasiController@plt_psa');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/plt/kuncitpp', 'RekapitulasiController@kuncitpp_plt');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/plt/perhitungan', 'RekapitulasiController@plt_perhitungan');
        Route::post('/admin/rekapitulasi/{bulan}/{tahun}/tambahpegawai/plt', 'RekapitulasiController@plt_tambahpegawai');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/plt/{id}/delete', 'RekapitulasiController@plt_delete');
        Route::post('/admin/rekapitulasi/bpjs/plt', 'RekapitulasiController@plt_bpjs');

        //puskesmas
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/puskes/reguler', 'RekapitulasiController@puskes_reguler');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/puskes/reguler/mp', 'RekapitulasiController@puskes_reguler_mp');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/puskes/reguler/psa', 'RekapitulasiController@puskes_reguler_psa');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/puskes/reguler/perhitungan', 'RekapitulasiController@puskes_reguler_perhitungan');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/puskes/reguler/{id}/delete', 'RekapitulasiController@puskes_reguler_delete');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/puskes/reguler/excel', 'RekapitulasiController@puskes_reguler_excel');

        //IFK
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/ifk/reguler', 'RekapitulasiController@ifk_reguler');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/ifk/reguler/mp', 'RekapitulasiController@ifk_reguler_mp');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/ifk/reguler/psa', 'RekapitulasiController@ifk_reguler_psa');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/ifk/reguler/perhitungan', 'RekapitulasiController@ifk_reguler_perhitungan');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/ifk/reguler/{id}/delete', 'RekapitulasiController@ifk_reguler_delete');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/ifk/reguler/excel', 'RekapitulasiController@ifk_reguler_excel');

        //LABKES
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/labkes/reguler', 'RekapitulasiController@labkes_reguler');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/labkes/reguler/mp', 'RekapitulasiController@labkes_reguler_mp');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/labkes/reguler/psa', 'RekapitulasiController@labkes_reguler_psa');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/labkes/reguler/perhitungan', 'RekapitulasiController@labkes_reguler_perhitungan');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/labkes/reguler/{id}/delete', 'RekapitulasiController@labkes_reguler_delete');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/labkes/reguler/excel', 'RekapitulasiController@labkes_reguler_excel');

        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/rs/reguler', 'RekapitulasiController@rs_reguler');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/rs/reguler/mp', 'RekapitulasiController@rs_reguler_mp');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/rs/reguler/psa', 'RekapitulasiController@rs_reguler_psa');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/rs/reguler/perhitungan', 'RekapitulasiController@rs_reguler_perhitungan');

        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/puskes/cpns', 'RekapitulasiController@puskes_cpns');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/puskes/cpns/mp', 'RekapitulasiController@puskes_cpns_mp');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/puskes/cpns/psa', 'RekapitulasiController@puskes_cpns_psa');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/puskes/cpns/perhitungan', 'RekapitulasiController@puskes_cpns_perhitungan');

        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/rs/cpns', 'RekapitulasiController@rs_cpns');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/rs/cpns/mp', 'RekapitulasiController@rs_cpns_mp');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/rs/cpns/psa', 'RekapitulasiController@rs_cpns_psa');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/rs/cpns/perhitungan', 'RekapitulasiController@rs_cpns_perhitungan');

        //----------------------------------------//
        Route::get('/admin/rekapitulasi/tu/{bulan}/{tahun}', 'RekapitulasiController@bulanTahunTU');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/pdf', 'RekapitulasiController@pdf');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/excel', 'RekapitulasiController@excel');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/excelPhpSpreadsheet', 'RekapitulasiController@excelPhpSpreadsheet');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/exceltu', 'RekapitulasiController@exceltu');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/plt/{id}', 'RekapitulasiController@plt');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/editjabatan/{id}', 'RekapitulasiController@editJabatan');
        Route::post('/admin/rekapitulasi/{bulan}/{tahun}/editjabatan/{id}', 'RekapitulasiController@editJabatanLaporan');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/masukkanpegawai', 'RekapitulasiController@masukkanPegawai');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/masukkanpegawaitu', 'RekapitulasiController@masukkanPegawaiTU');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/updatejabatan', 'RekapitulasiController@updateJabatan');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/hitungpersen', 'RekapitulasiController@hitungPersen');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/totalpagu', 'RekapitulasiController@totalPagu');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/presensi', 'RekapitulasiController@tarikPresensi');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/aktivitas', 'RekapitulasiController@aktivitas');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/pph21', 'RekapitulasiController@pph21');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/{id}/delete', 'RekapitulasiController@delete');
        Route::post('/admin/rekapitulasi/tambahpegawai', 'RekapitulasiController@tambahPegawai');

        Route::get('/admin/rekapitulasi/cpns', 'RekapitulasiCpnsController@index');
        Route::get('/admin/rekapitulasi/cpns/{bulan}/{tahun}', 'RekapitulasiCpnsController@bulanTahun');
        Route::get('/admin/rekapitulasi/cpns/{bulan}/{tahun}/masukkanpegawai', 'RekapitulasiCpnsController@masukkanPegawai');
        Route::get('/admin/rekapitulasi/cpns/{bulan}/{tahun}/perhitungan', 'RekapitulasiCpnsController@perhitungan');
        Route::get('/admin/rekapitulasi/cpns/{bulan}/{tahun}/pembayaran', 'RekapitulasiCpnsController@pembayaran');
        Route::get('/admin/rekapitulasi/cpns/{bulan}/{tahun}/excel', 'RekapitulasiCpnsController@excel');
        Route::post('/admin/rekapitulasi/cpns/editkelas', 'RekapitulasiCpnsController@editkelas');

        Route::get('/admin/rekapitulasi-puskesmas-gabungan', 'RekapitulasiController@puskesmasGabungan');
        Route::get('/admin/rekapitulasi-puskesmas-gabungan/{bulan}/{tahun}', 'RekapitulasiController@PGbulanTahun');
        Route::get('/admin/rekapitulasi-puskesmas-gabungan/{bulan}/{tahun}/excel', 'RekapitulasiController@PGexcel');

        Route::get('/admin/rekapitulasi-cpns-puskesmas', 'RekapitulasiCpnsController@cpnsPuskesmas');
        Route::post('/admin/rekapitulasi-cpns-puskesmas/editkelas', 'RekapitulasiCpnsController@editkelasCpnsPuskesmas');
        Route::get('/admin/rekapitulasi-cpns-puskesmas/{bulan}/{tahun}', 'RekapitulasiCpnsController@cpnsPuskesmasBulanTahun');
        Route::get('/admin/rekapitulasi-cpns-puskesmas/{bulan}/{tahun}/masukkanpegawai', 'RekapitulasiCpnsController@masukkanCpnsPuskesmas');
        Route::get('/admin/rekapitulasi-cpns-puskesmas/{bulan}/{tahun}/excel', 'RekapitulasiCpnsController@excelCpnsPuskesmas');
        Route::get('/admin/rekapitulasi-cpns-puskesmas/{bulan}/{tahun}/perhitungan', 'RekapitulasiCpnsController@perhitunganCpnsPuskesmas');
        Route::get('/admin/rekapitulasi-cpns-puskesmas/{bulan}/{tahun}/pembayaran', 'RekapitulasiCpnsController@pembayaranCpnsPuskesmas');

        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/perhitungan', 'RekapitulasiController@perhitungan');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/pembayaran', 'RekapitulasiController@pembayaran');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/perhitungantu', 'RekapitulasiController@perhitungantu');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/pembayarantu', 'RekapitulasiController@pembayarantu');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/bpjs', 'RekapitulasiController@bpjs');
        Route::post('/admin/rekapitulasi/{bulan}/{tahun}/bpjs', 'RekapitulasiController@uploadBpjs');
        Route::get('/admin/rekapitulasi/{bulan}/{tahun}/excelpagu', 'RekapitulasiController@paguExcel');

        //TPP PNS PUSKESMAS
        Route::get('/admin/rekapitulasi/puskesmas', 'RekapitulasiPnsPuskesmas@index');
        Route::get('/admin/rekapitulasi/puskesmas/{bulan}/{tahun}', 'RekapitulasiPnsPuskesmas@puskesmas');
        Route::get('/admin/rekapitulasi/puskesmas/{bulan}/{tahun}/{puskesmas_id}/tpp', 'RekapitulasiPnsPuskesmas@bulanTahun');

        Route::post('/admin/rekapitulasi/bpjs', 'RekapitulasiController@updatebpjs');

        Route::get('/admin/sekolah', 'SekolahController@index');
        Route::get('/admin/sekolah/add', 'SekolahController@create');
        Route::post('/admin/sekolah/add', 'SekolahController@store');
        Route::get('/admin/sekolah/{id}/edit', 'SekolahController@edit');
        Route::post('/admin/sekolah/{id}/edit', 'SekolahController@update');
        Route::get('/admin/sekolah/{id}/delete', 'SekolahController@destroy');

        Route::get('/admin/sekolah/{id}/petajabatan', 'SekolahController@jabatan');
        Route::post('/admin/sekolah/{id}/petajabatan', 'SekolahController@storeJabatan');

        Route::get('/admin/sekolah/{id}/petajabatan/{idJab}/delete', 'SekolahController@deleteJabatan');
        Route::get('/admin/sekolah/{id}/petajabatan/{idJab}/edit', 'SekolahController@editJabatan');
        Route::post('/admin/sekolah/{id}/petajabatan/{idJab}/edit', 'SekolahController@updateJabatan');

        Route::get('/admin/cuti', 'CutiController@admin');
        Route::get('/admin/cuti/search', 'CutiController@search');
        Route::get('/admin/cuti/{nip}/detail', 'CutiController@detail');
        Route::get('/admin/cuti/tarik', 'CutiController@tarik');

        Route::get('/admin/superadmin/{uuid}', 'AdminController@loginSuperadmin');

        Route::get('/admin/rspuskesmas/{id}/login', 'AdminController@loginPuskesmas');
    });

    Route::group(['middleware' => ['auth', 'checksinglesession', 'role:pegawai']], function () {

        Route::get('/home/pegawai', 'HomeController@pegawai');
        Route::post('/checkbapintar', 'HomeController@checkbapintar');
        Route::get('/home/pegawai/submonth', 'HomeController@pegawaiSubMonth');

        //Route::post('/pegawai/profil/gantipass', 'ProfilController@gantiPassPegawai');

        Route::post('/pegawai/rencana-aksi', 'SKP2023Controller@storeRencanaAksi');
        Route::get('/pegawai/new-skp', 'SKP2023Controller@index');
        Route::get('/pegawai/new-skp/updatepegawai/{id}', 'SKP2023Controller@updatePegawaiSKP');

        Route::post('pegawai/new-skp/getPenilai', 'SKP2023Controller@getPenilai');
        Route::post('pegawai/new-skp/penilai/{id}', 'SKP2023Controller@updatePenilai');

        Route::post('/pegawai/new-skp/unitkerja/{id}', 'SKP2023Controller@updateUnitkerjaSKP');
        Route::get('/pegawai/new-skp/atasan/{nip}', 'SKP2023Controller@skpAtasan');
        Route::post('/pegawai/new-skp/periode', 'SKP2023Controller@storePeriode');
        Route::get('/pegawai/new-skp/periode/aktifkan/{id}', 'SKP2023Controller@aktifkan');
        Route::get('/pegawai/new-skp/periode/edit/{id}', 'SKP2023Controller@editPeriode');
        Route::post('/pegawai/new-skp/periode/edit/{id}', 'SKP2023Controller@updatePeriode');
        Route::get('/pegawai/new-skp/periode/delete/{id}', 'SKP2023Controller@deletePeriode');
        Route::get('/pegawai/new-skp/periode/view/{id}', 'SKP2023Controller@viewPeriode');
        Route::get('/pegawai/new-skp/periode/view/{id}/tarik-rencana-aksi', 'SKP2023Controller@tarikRencanaAksi');
        Route::get('/pegawai/new-skp/periode/evaluasi/{id}/triwulan/{triwulan}', 'SKP2023Controller@viewEvaluasi');
        Route::post('/pegawai/new-skp/periode/evaluasi/{id}/triwulan/{triwulan}/realisasijpt', 'SKP2023Controller@realJPT');
        Route::post('/pegawai/new-skp/periode/evaluasi/{id}/triwulan/{triwulan}/realisasijf', 'SKP2023Controller@realJF');
        Route::post('/pegawai/new-skp/periode/evaluasi/{id}/triwulan/{triwulan}/realisasija', 'SKP2023Controller@realJA');

        Route::post('/pegawai/new-skp/utama/rhk/{id}', 'SKP2023Controller@jptRhk');
        Route::get('/pegawai/new-skp/utama/rhk/{id}/delete', 'SKP2023Controller@deleteJptRhk');
        Route::post('/pegawai/new-skp/utama/rhk/{id}/edit', 'SKP2023Controller@updateJptRhk');
        Route::post('/pegawai/new-skp/utama/rhk/{id}/indikator', 'SKP2023Controller@indikatorJptRhk');
        Route::post('/pegawai/new-skp/utama/rhk/{id}/indikator/edit', 'SKP2023Controller@updateIndikatorJptRhk');
        Route::get('/pegawai/new-skp/utama/rhk/{id}/indikator/{indikator_id}/delete', 'SKP2023Controller@deleteIndikatorJptRhk');

        Route::post('/pegawai/new-skp/tambahan/rhk/{id}', 'SKP2023Controller@t_jptRhk');
        Route::get('/pegawai/new-skp/tambahan/rhk/{id}/delete', 'SKP2023Controller@t_deleteJptRhk');
        Route::post('/pegawai/new-skp/tambahan/rhk/{id}/edit', 'SKP2023Controller@t_updateJptRhk');
        Route::post('/pegawai/new-skp/tambahan/rhk/{id}/indikator', 'SKP2023Controller@t_indikatorJptRhk');
        Route::post('/pegawai/new-skp/tambahan/rhk/{id}/indikator/edit', 'SKP2023Controller@t_updateIndikatorJptRhk');
        Route::get('/pegawai/new-skp/tambahan/rhk/{id}/indikator/{indikator_id}/delete', 'SKP2023Controller@t_deleteIndikatorJptRhk');

        Route::post('/pegawai/new-skp/jf/utama/rhk/{id}', 'JFController@jptRhk');
        Route::get('/pegawai/new-skp/jf/utama/rhk/{id}/delete', 'JFController@deleteJptRhk');
        Route::post('/pegawai/new-skp/jf/utama/rhk/{id}/edit', 'JFController@updateJptRhk');
        Route::post('/pegawai/new-skp/jf/utama/rhk/{id}/indikator', 'JFController@indikatorJptRhk');
        Route::post('/pegawai/new-skp/jf/utama/rhk/{id}/indikator/edit', 'JFController@updateIndikatorJptRhk');
        Route::get('/pegawai/new-skp/jf/utama/rhk/{id}/indikator/{indikator_id}/delete', 'JFController@deleteIndikatorJptRhk');

        Route::post('/pegawai/new-skp/jf/tambahan/rhk/{id}', 'JFController@t_jptRhk');
        Route::get('/pegawai/new-skp/jf/tambahan/rhk/{id}/delete', 'JFController@t_deleteJptRhk');
        Route::post('/pegawai/new-skp/jf/tambahan/rhk/{id}/edit', 'JFController@t_updateJptRhk');
        Route::post('/pegawai/new-skp/jf/tambahan/rhk/{id}/indikator', 'JFController@t_indikatorJptRhk');
        Route::post('/pegawai/new-skp/jf/tambahan/rhk/{id}/indikator/edit', 'JFController@t_updateIndikatorJptRhk');
        Route::get('/pegawai/new-skp/jf/tambahan/rhk/{id}/indikator/{indikator_id}/delete', 'JFController@t_deleteIndikatorJptRhk');

        Route::get('/pegawai/nilai-skp', 'NilaiSKPController@index');
        Route::get('/pegawai/nilai-skp/ekspektasi/{id}', 'NilaiSKPController@ekspektasi');
        Route::get('/pegawai/nilai-skp/ekspektasi/delete/{id}', 'NilaiSKPController@deleteEkspektasi');
        Route::post('/pegawai/nilai-skp/ekspektasi/{id}', 'NilaiSKPController@simpanEkspektasi');
        Route::post('/pegawai/nilai-skp/ekspektasi/{id}/triwulan', 'NilaiSKPController@simpanEkspektasiTriwulan');
        Route::get('/pegawai/nilai-skp/triwulan/{triwulan}/{id}', 'NilaiSKPController@evaluasi');
        Route::post('/pegawai/nilai-skp/triwulan/{triwulan}/{id}/komentarja', 'NilaiSKPController@komentarJa');
        Route::post('/pegawai/nilai-skp/triwulan/{triwulan}/{id}/jpt', 'NilaiSKPController@umpanBalikJPT');
        Route::post('/pegawai/nilai-skp/triwulan/{triwulan}/{id}/jf', 'NilaiSKPController@umpanBalikJF');
        Route::post('/pegawai/nilai-skp/triwulan/{triwulan}/{id}/ja', 'NilaiSKPController@umpanBalikJA');
        Route::post('/pegawai/nilai-rhk/triwulan/{triwulan}/{id}', 'NilaiSKPController@nilaiRHK');
        Route::post('/pegawai/nilai-rpk/triwulan/{triwulan}/{id}', 'NilaiSKPController@nilaiRPK');

        Route::get('/pegawai/gantipass', 'ProfilController@gantiPassPegawaiView');
        Route::post('/pegawai/gantipass', 'ProfilController@updatePassPegawai');

        Route::get('/pegawai/skp/rencana-kegiatan', 'SkpController@index');
        Route::get('/pegawai/skp/validasi', 'SkpController@validasiSkp');
        Route::get('/pegawai/skp/plt/validasi', 'SkpController@validasiSkpPLT');
        Route::get('/pegawai/skp/validasi/view/{id}', 'SkpController@viewSkp');
        Route::get('/pegawai/skp/validasi/acc/{id}', 'SkpController@setujuiSkp');
        Route::get('/pegawai/skp/validasi/acc_semua/{id}', 'SkpController@accSemuaSkp');
        Route::get('/pegawai/skp/validasi/tolak/{id}', 'SkpController@tolakSkp');
        Route::post('/pegawai/skp/rencana-kegiatan', 'SkpController@storePeriode');
        Route::get('/pegawai/skp/rencana-kegiatan/edit/{id}/{periode_id}', 'SkpController@edit');
        Route::get('/pegawai/skp/rencana-kegiatan/delete/{id_kegiatan}/{id_skp}', 'SkpController@delete');
        Route::get('/pegawai/skp/rencana-kegiatan/periode/edit/{id}', 'SkpController@editPeriode');
        Route::post('/pegawai/skp/rencana-kegiatan/periode/edit/{id}', 'SkpController@updatePeriode');
        Route::get('/pegawai/skp/rencana-kegiatan/periode/delete/{id}', 'SkpController@deletePeriode');
        Route::get('/pegawai/skp/rencana-kegiatan/periode/view/{id}', 'SkpController@viewPeriode');
        Route::post('/pegawai/skp/rencana-kegiatan/periode/view/{id}', 'SkpController@storeSkp');
        Route::post('/pegawai/skp/rencana-kegiatan/edit/{id}/{periode_id}', 'SkpController@updateSkp');

        Route::get('/pegawai/skp/rencana-kegiatan/periode/aktifkan/{id}', 'SkpController@aktifkan');

        Route::get('/pegawai/aktivitas/harian', 'AktivitasController@index');
        Route::get('/pegawai/aktivitas/harian/detail/{bulan}/{tahun}', 'AktivitasController@detail');
        Route::get('/pegawai/aktivitas/add', 'AktivitasController@add');
        Route::post('/pegawai/aktivitas/add', 'AktivitasController@store');
        Route::get('/pegawai/aktivitas/harian/edit/{id}', 'AktivitasController@edit');
        Route::post('/pegawai/aktivitas/harian/edit/{id}', 'AktivitasController@update');
        Route::get('/pegawai/aktivitas/harian/delete/{id}', 'AktivitasController@delete');

        Route::get('/pegawai/aktivitas/keberatan', 'AktivitasController@keberatan');
        Route::get('/pegawai/aktivitas/keberatan/{id}/{penilai_id}', 'AktivitasController@ajukanKeberatan');

        Route::get('/pegawai/validasi/harian', 'ValidasiController@index');
        Route::get('/pegawai/validasi/riwayat', 'ValidasiController@riwayat');
        Route::get('/pegawai/validasi/harian/acc/{id}', 'ValidasiController@accSemua');
        Route::get('/pegawai/validasi/harian/acc_aktivitas/{id}', 'ValidasiController@accAktivitas');
        Route::get('/pegawai/validasi/harian/tolak/{id}', 'ValidasiController@tolakAktivitas');
        Route::get('/pegawai/validasi/harian/view/{id}', 'ValidasiController@view');

        //route PLT
        Route::get('/pegawai/plt/validasi/harian', 'ValidasiPltController@index');
        Route::get('/pegawai/plt/validasi/harian/acc/{id}', 'ValidasiPltController@accSemua');
        Route::get('/pegawai/plt/validasi/harian/acc_aktivitas/{id}', 'ValidasiPltController@accAktivitas');
        Route::get('/pegawai/plt/validasi/harian/tolak/{id}', 'ValidasiPltController@tolakAktivitas');
        Route::get('/pegawai/plt/validasi/harian/view/{id}', 'ValidasiPltController@view');
        //Route::get('/pegawai/plt/validasi/harian/acc_aktivitas/{id}', 'ValidasiPltController@accAktivitas');
        //Route::get('/pegawai/plt/validasi/harian/tolak/{id}', 'ValidasiPltController@tolakAktivitas');

        //route PLH
        Route::get('/pegawai/plh/validasi/harian', 'ValidasiPlhController@index');
        Route::get('/pegawai/plh/validasi/harian/acc/{id}', 'ValidasiPlhController@accSemua');
        Route::get('/pegawai/plh/validasi/harian/acc_aktivitas/{id}', 'ValidasiPlhController@accAktivitas');
        Route::get('/pegawai/plh/validasi/harian/tolak/{id}', 'ValidasiPlhController@tolakAktivitas');
        Route::get('/pegawai/plh/validasi/harian/view/{id}', 'ValidasiPlhController@view');
        //Route::get('/pegawai/plh/validasi/harian/acc_aktivitas/{id}', 'ValidasiPlhController@accAktivitas');
        //Route::get('/pegawai/plh/validasi/harian/tolak/{id}', 'ValidasiPlhController@tolakAktivitas');

        Route::get('/pegawai/validasi/keberatan', 'ValidasiController@keberatan');
        Route::get('/pegawai/validasi/keberatan/setujui/{id}', 'ValidasiController@setujuiKeberatan');
        Route::get('/pegawai/validasi/keberatan/tolak/{id}', 'ValidasiController@tolakKeberatan');

        Route::get('/pegawai/verifikasi', 'VerifikasiController@index');
        Route::get('/pegawai/verifikasi/detail', 'VerifikasiController@detail');
        Route::get('/pegawai/verifikasi/jurnal', 'VerifikasiController@jurnal');
        Route::get('/pegawai/riwayat', 'RiwayatController@index');
        Route::get('/pegawai/tpp', 'TppController@index');
        Route::get('/pegawai/tpp/grafik', 'TppController@grafik');
        Route::get('/pegawai/profil', 'ProfilController@pegawai');
        Route::get('/pegawai/profil/edit', 'ProfilController@editPegawai');
        Route::post('/pegawai/profil/edit', 'ProfilController@updatePegawai');
        Route::post('/pegawai/profil/bio', 'ProfilController@updateDataPegawai');

        Route::get('/pegawai/gaji', 'GajiController@index');

        Route::get('/pegawai/laporan/tpp', 'LaporanController@tpp');
        Route::get('/pegawai/laporan/aktivitas', 'LaporanController@aktivitas');
        Route::get('/pegawai/laporan/aktivitas/{bulan}/{tahun}', 'LaporanController@detailAktivitas');
        Route::get('/pegawai/laporan/penghasilan', 'LaporanController@penghasilan');


        Route::get('/pegawai/penguncian', 'LockController@kadis');
        Route::get('/pegawai/penguncian/{bulan}/{tahun}/lock', 'LockController@kadisLock');
        Route::get('/pegawai/penguncian/{bulan}/{tahun}/unlock', 'LockController@kadisUnlock');
    });

    Route::group(['middleware' => ['auth', 'role:walikota']], function () {
        Route::get('/home/walikota', 'HomeController@walikota');
        Route::get('/walikota/nilai-skp/ekspektasi/{id}', 'WalikotaController@ekspektasi');
        Route::get('/walikota/nilai-skp/ekspektasi/delete/{id}', 'WalikotaController@deleteEkspektasi');
        Route::post('/walikota/nilai-skp/ekspektasi/{id}', 'WalikotaController@simpanEkspektasi');
        Route::post('/walikota/nilai-skp/ekspektasi/{id}/triwulan', 'WalikotaController@simpanEkspektasiTriwulan');
        Route::get('/walikota/nilai-skp/triwulan/{triwulan}/{id}', 'WalikotaController@evaluasi');
        Route::post('/walikota/nilai-skp/triwulan/{triwulan}/{id}/jpt', 'WalikotaController@umpanBalikJPT');
        Route::post('/walikota/nilai-skp/triwulan/{triwulan}/{id}/jf', 'WalikotaController@umpanBalikJF');
        Route::post('/walikota/nilai-skp/triwulan/{triwulan}/{id}/ja', 'WalikotaController@umpanBalikJA');
        Route::post('/walikota/nilai-rhk/triwulan/{triwulan}/{id}', 'WalikotaController@nilaiRHK');
        Route::post('/walikota/nilai-rpk/triwulan/{triwulan}/{id}', 'WalikotaController@nilaiRPK');

        Route::get('/walikota/ganti-password', 'ProfilController@gantiPassWalikota');
        Route::post('/walikota/ganti-password', 'ProfilController@updatePassWalikota');
    });

    Route::group(['middleware' => ['auth', 'role:puskesmas']], function () {
        Route::post('/puskesmas/rekapitulasi/bpjs/reguler', 'PuskesmasController@reguler_bpjs');
        Route::get('/home/puskesmas', 'HomeController@puskesmas');
        Route::get('/puskesmas/dinkes/{uuid}', 'PuskesmasController@loginDinkes');
        Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}', 'PuskesmasController@rekapitulasi');
        Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/reguler', 'PuskesmasController@reguler');
        Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/masukkanpegawai', 'PuskesmasController@masukkanPegawai');
        Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/perhitungan', 'PuskesmasController@perhitungan');
        Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/pembayaran', 'PuskesmasController@pembayaran');
        Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/excel', 'PuskesmasController@excel');
        Route::post('/puskesmas/rekapitulasi/tambahpegawai', 'PuskesmasController@tambahPegawai');
        Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/reguler/{id}/delete', 'PuskesmasController@delete');
        Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/kuncitpp/reguler', 'PuskesmasController@kuncitpp');

        Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/tarikter', 'PuskesmasController@tarikter');
        Route::post('/puskesmas/rekapitulasi/editjabatan/reguler', 'PuskesmasController@reguler_editjabatan');
        Route::post('/puskesmas/rekapitulasi/{bulan}/{tahun}/tambahpegawai/reguler', 'PuskesmasController@reguler_tambahpegawai');
        Route::post('puskesmas/rekapitulasi/getJabatan', 'PuskesmasController@getJabatan');
        Route::post('puskesmas/rekapitulasi/getPegawai', 'PuskesmasController@getPegawai');
        Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/reguler/mp', 'PuskesmasController@puskes_reguler_mp');
        Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/reguler/psa', 'PuskesmasController@puskes_reguler_psa');
        Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/reguler/perhitungan', 'PuskesmasController@puskes_reguler_perhitungan');

        Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/cpns', 'PuskesmasController@cpns');
        Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/cpns/mp', 'PuskesmasController@cpns_mp');
        Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/cpns/psa', 'PuskesmasController@cpns_psa');
        Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/cpns/perhitungan', 'PuskesmasController@cpns_perhitungan');
        Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/cpns/{id}/delete', 'PuskesmasController@cpns_delete');
        Route::post('/puskesmas/rekapitulasi/bpjs/cpns', 'PuskesmasController@cpns_bpjs');
        Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/reguler/excel', 'PuskesmasController@puskes_reguler_excel');
    });
});
