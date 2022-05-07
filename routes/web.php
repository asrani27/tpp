<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'LoginController@index');
Route::post('/login', 'LoginController@login');
Route::get('/login', 'LoginController@redirectLogin')->name('login');

Route::get('/logout', 'LoginController@logout');

Route::group(['middleware' => ['auth', 'role:superadmin']], function () {
    Route::get('/home/superadmin', 'HomeController@superadmin');
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

    Route::get('/admin/rekapitulasi', 'RekapitulasiController@index');
    Route::get('/admin/rekapitulasi/cetaktpp', 'RekapitulasiController@cetaktpp');
    Route::get('/admin/rekapitulasi/{bulan}/{tahun}', 'RekapitulasiController@bulanTahun');
    Route::get('/admin/rekapitulasi/tu/{bulan}/{tahun}', 'RekapitulasiController@bulanTahunTU');
    Route::get('/admin/rekapitulasi/{bulan}/{tahun}/pdf', 'RekapitulasiController@pdf');
    Route::get('/admin/rekapitulasi/{bulan}/{tahun}/excel', 'RekapitulasiController@excel');
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


    Route::get('/admin/rekapitulasi/{bulan}/{tahun}/perhitungan', 'RekapitulasiController@perhitungan');
    Route::get('/admin/rekapitulasi/{bulan}/{tahun}/pembayaran', 'RekapitulasiController@pembayaran');
    Route::get('/admin/rekapitulasi/{bulan}/{tahun}/perhitungantu', 'RekapitulasiController@perhitungantu');
    Route::get('/admin/rekapitulasi/{bulan}/{tahun}/pembayarantu', 'RekapitulasiController@pembayarantu');
    Route::get('/admin/rekapitulasi/{bulan}/{tahun}/bpjs', 'RekapitulasiController@bpjs');
    Route::post('/admin/rekapitulasi/{bulan}/{tahun}/bpjs', 'RekapitulasiController@uploadBpjs');
    Route::get('/admin/rekapitulasi/{bulan}/{tahun}/excelpagu', 'RekapitulasiController@paguExcel');

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
    Route::get('/home/pegawai/submonth', 'HomeController@pegawaiSubMonth');

    Route::post('/pegawai/profil/gantipass', 'ProfilController@gantiPassPegawai');

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
});

Route::group(['middleware' => ['auth', 'role:puskesmas']], function () {
    Route::get('/home/puskesmas', 'HomeController@puskesmas');
    Route::get('/puskesmas/dinkes/{uuid}', 'PuskesmasController@loginDinkes');
    Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}', 'PuskesmasController@bulanTahun');
    Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/masukkanpegawai', 'PuskesmasController@masukkanPegawai');
    Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/perhitungan', 'PuskesmasController@perhitungan');
    Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/pembayaran', 'PuskesmasController@pembayaran');
    Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/excel', 'PuskesmasController@excel');
    Route::post('/puskesmas/rekapitulasi/tambahpegawai', 'PuskesmasController@tambahPegawai');
    Route::get('/puskesmas/rekapitulasi/{bulan}/{tahun}/{id}/delete', 'PuskesmasController@delete');
});
