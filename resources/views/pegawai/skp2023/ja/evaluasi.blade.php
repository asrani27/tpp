@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush
@section('title')
PEGAWAI
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <a href="/pegawai/new-skp" class="btn btn-sm btn-secondary" type="submit"><i class="fas fa-arrow-left"></i>
            Kembali</a><br /><br />
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-2 text-center text-sm">
                        <strong>
                            SASARAN KINERJA PEGAWAI.<br />
                            JA<br />
                            PENDEKATAN HASIL KERJA KUANTITATIF<br />
                            TRIWULAN : {{$triwulan}}</strong>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-1">
                        <table class="table table-sm">
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                <td>{{Auth::user()->pegawai->skpd->nama}}</td>
                                <td>{{\Carbon\Carbon::parse($u->mulai)->translatedFormat('d F Y')}} sd
                                    {{\Carbon\Carbon::parse($u->sampai)->translatedFormat('d F Y')}}</td>
                            </tr>
                        </table>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr
                                    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px; background-color:rgb(218, 236, 249)">
                                    <th>NO</th>
                                    <th colspan="2">PEGAWAI YG DINILAI</th>
                                    <th>NO</th>
                                    <th colspan="2">PEJABAT PENILAI KINERJA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                    <td>1</td>
                                    <td>NAMA</td>
                                    <td>{{$pn->nama}}</td>
                                    <td>1</td>
                                    <td>NAMA</td>
                                    <td>{{$pp->nama}}</td>
                                </tr>
                                <tr
                                    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                    <td>2</td>
                                    <td>NIP</td>
                                    <td>{{$pn->nip}}</td>
                                    <td>2</td>
                                    <td>NIP</td>
                                    <td>{{$pp->nip}}</td>
                                </tr>
                                <tr
                                    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                    <td>3</td>
                                    <td>PANGKAT/GOL.RUANG</td>
                                    <td>{{$pn->pangkat}} / {{$pn->gol}}</td>
                                    <td>3</td>
                                    <td>PANGKAT/GOL.RUANG</td>
                                    <td>{{$pp->pangkat}} / {{$pp->gol}}</td>
                                </tr>
                                <tr
                                    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                    <td>4</td>
                                    <td>JABATAN</td>
                                    <td>{{$pn->jabatan == null ? '-': $pn->jabatan}}</td>
                                    <td>4</td>
                                    <td>JABATAN</td>
                                    <td>{{$pp->jabatan == null ? '-': $pp->jabatan}}</td>
                                </tr>
                                <tr
                                    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                    <td>5</td>
                                    <td>UNIT KERJA</td>
                                    <td>{{$pn->unit_kerja}}</td>
                                    <td>5</td>
                                    <td>INSTANSI</td>
                                    <td>{{$pp->skpd == null ? '-': $pp->skpd}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-1">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr
                                    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px; background-color:rgb(218, 236, 249)">

                                    <th colspan="8">HASIL KERJA</th>
                                </tr>
                                <tr class="text-center"
                                    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;background-color:rgb(218, 236, 249)">
                                    <th>NO</th>
                                    <th>RENCANA HASIL KERJA ATASAN YANG DIINTERVENSI</th>
                                    <th>RENCANA HASIL KERJA</th>
                                    <th>ASPEK</th>
                                    <th>INDIKATOR KINERJA INDIVIDU</th>
                                    <th>TARGET</th>
                                    <th>REALISASI BERDASARKAN BUKTI DUKUNG</th>
                                    <th>UMPAN BALIK BERKELANJUTAN BERDASARKAN BUKTI DUKUNG</th>
                                </tr>
                                <tr class="text-center"
                                    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:8px;background-color:rgb(218, 236, 249);">
                                    <th>(1)</th>
                                    <th>(2)</th>
                                    <th>(3)</th>
                                    <th>(4)</th>
                                    <th>(5)</th>
                                    <th>(6)</th>
                                    <th>(7)</th>
                                    <th>(8)</th>
                                </tr>
                                <tr
                                    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;background-color:rgb(218, 236, 249);">
                                    <th colspan="8">A.UTAMA </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($skp_utama as $key => $item)

                                @php ($first = true) @endphp

                                @foreach ($item->indikator as $key2 => $item2)
                                <tr
                                    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                    @if($first == true)
                                    <td rowspan="{{$item->indikator->count()}}" class="text-center">{{$key+1}}</td>
                                    <td rowspan="{{$item->indikator->count()}}">{{$item->rhk_intervensi}}</td>

                                    <td rowspan="{{$item->indikator->count()}}">{{$item->rhk}}

                                        <br />
                                    </td>
                                    @php ($first = false) @endphp
                                    @endif
                                    <td>{{$item2->aspek}}
                                    </td>
                                    <td class="text-center">{{$item2->indikator}}</td>
                                    <td class="text-center">{{$item2->target}}</td>
                                    <td>{{$item2['real_tw'. $triwulan]}}
                                        <a href="#" data-id="{{$item2->id}}"
                                            data-realisasi="{{$item2['real_tw'. $triwulan]}}" class="edit-realisasi"><i
                                                class="fas fa-edit"></i></a>
                                    </td>
                                    <td>Pimpinan : {{$item2['ub_tw'. $triwulan]}}</td>
                                </tr>
                                @endforeach
                                @endforeach
                                <tr
                                    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;background-color:rgb(218, 236, 249);">
                                    <th colspan="8">RENCANA AKSI TRIWULAN {{$triwulan}} </th>
                                </tr>
                                <tr>
                                    <td colspan="8">
                                        <a href="/pegawai/new-skp/periode/view/{{$u->id}}/tarik-rencana-aksi"
                                            class="btn btn-xs btn-primary "><i class="fas fa-sync"></i> Tarik Rencana
                                            Aksi</a>
                                    </td>
                                </tr>

                                <tr
                                    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                    <td colspan="8">
                                        <table width="100%">
                                            <tr>
                                                <th>No</th>
                                                <th>Rencana Hasil Kerja (RHK)</th>
                                                <th>Rencana Aksi</th>
                                            </tr>
                                            @foreach ($skp_utama as $key => $item)
                                            <tr
                                                style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                                <td>{{$key + 1}}</td>
                                                <td>{{$item->id}} - {{$item->rhk}}</td>
                                                <td>
                                                    <table>
                                                        <tr
                                                            style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;background-color:rgb(218, 236, 249);">
                                                            <td>Keterangan</td>
                                                            <td>Target</td>
                                                            <td>Realisasi</td>
                                                            <td>Bukti Dukung</td>
                                                            <td>Masalah</td>
                                                            <td>Umpan Balik Atasan</td>
                                                            <td></td>
                                                        </tr>

                                                        @foreach($u->rencana_aksi->where('rhk_id',$item->id)->where('triwulan',
                                                        $triwulan) as $rencana)
                                                        <tr>
                                                            <td>{!!wordwrap($rencana->keterangan, 100,
                                                                '<br />')!!}</td>
                                                            <td>{{$rencana->target_kinerja}} {{$rencana->satuan}}</td>
                                                            <td>{{$rencana->realisasi}}</td>
                                                            <td>
                                                                @if ($rencana->bukti_dukung == null)

                                                                @else

                                                                <a href="{{$rencana->bukti_dukung}}"
                                                                    target="_blank"><strong>Google Drive</strong></a>
                                                                @endif
                                                            </td>
                                                            <td>{{$rencana->masalah}}</td>
                                                            <td>{{$rencana->umpan_balik}}</td>
                                                            <td>
                                                                <a href="#" class="btn btn-xs btn-primary rencana-aksi"
                                                                    data-id="{{$rencana->id}}"
                                                                    data-realisasi="{{$rencana->realisasi}}"
                                                                    data-link="{{$rencana->bukti_dukung}}"
                                                                    data-masalah="{{$rencana->masalah}}"><i
                                                                        class="fas fa-edit"></i></a>
                                                            </td>

                                                        </tr>
                                                        @endforeach
                                                    </table>

                                                </td>
                                            </tr>

                                            @endforeach
                                        </table>
                                    </td>
                                </tr>

                                <tr
                                    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;background-color:rgb(218, 236, 249);">
                                    <th colspan="8">B.TAMBAHAN </th>
                                </tr>

                                @foreach ($skp_tambahan as $key => $item)

                                @php ($first = true) @endphp

                                @foreach ($item->indikator as $key2 => $item2)
                                <tr
                                    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                    @if($first == true)
                                    <td rowspan="{{$item->indikator->count()}}" class="text-center">{{$key+1}}</td>
                                    <td rowspan="{{$item->indikator->count()}}">{{$item->rhk_intervensi}}</td>
                                    <td rowspan="{{$item->indikator->count()}}">{{$item->rhk}} </td>
                                    @php ($first = false) @endphp
                                    @endif
                                    <td class="text-center">{{$item2->aspek}}
                                    </td>
                                    <td>{{$item2->indikator}} </td>
                                    <td class="text-center">{{$item2->target}}</td>
                                    <td>{{$item2['real_tw'. $triwulan]}}
                                        <a href="#" data-id="{{$item2->id}}"
                                            data-realisasi="{{$item2['real_tw'. $triwulan]}}" class="edit-realisasi"><i
                                                class="fas fa-edit"></i></a>
                                    </td>
                                    <td>Pimpinan : {{$item2['ub_tw'. $triwulan]}}</td>
                                </tr>
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr
                                    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px; background-color:rgb(218, 236, 249)">
                                    <th>RATING HASIL KERJA</th>
                                </tr>
                                <tr>
                                    <th>
                                        {{$u['rhk_tw'.$triwulan]}}
                                    </th>
                                </tr>
                            </thead>
                        </table>
                        <table class="table table-sm table-bordered">
                            <tr
                                style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;background-color:rgb(218, 236, 249);">
                                <th colspan="3">PERILAKU KERJA </th>
                                <th class="text-center">UMPAN BALIK BERKELANJUTAN BERDASARKAN BUKTI DUKUNG</th>
                            </tr>

                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                <td>1</td>
                                <td colspan="3">Berorientasi Pelayanan</td>
                            </tr>
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                <td></td>
                                <td width="50%">
                                    -Memahami dan memenuhi kebutuhan masyarakat<br />
                                    -Ramah, cekatan, solutif, dan dapat diandalkan <br />
                                    -melakukan perbaikan tiada henti

                                </td>
                                <td>Ekspektasi Khusus Pimpinan :<br />
                                    @foreach ($u->ekspektasi1 as $item)
                                    -{{$item->ekspektasi}} <br />
                                    @endforeach
                                </td>
                                <td>Pimpinan/Stakeholder (Nama) : </td>
                            </tr>
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                <td>2</td>
                                <td colspan="3">Akuntabel</td>
                            </tr>
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                <td></td>
                                <td>
                                    -Melaksanakan tugas dengan jujur, bertanggungjawab, cermat, disiplin dan
                                    berintegritas tinggi<br />
                                    -Menggunakan kekayaan dan barang milik negara secara bertanggungjawab, efektif, dan
                                    efisien <br />
                                    -Tidak menyalahgunakan kewenangan jabatan

                                </td>
                                <td>Ekspektasi Khusus Pimpinan :<br />
                                    @foreach ($u->ekspektasi2 as $item)
                                    -{{$item->ekspektasi}} <br />
                                    @endforeach
                                </td>
                                <td>Pimpinan/Stakeholder (Nama) : </td>
                            </tr>
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                <td>3</td>
                                <td colspan="3">Kompeten</td>
                            </tr>
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                <td></td>
                                <td>
                                    -Meningkatkan kompetensi diri untuk menjawab tantangan yang selalu berubah<br />
                                    -Membantu orang lain belajar <br />
                                    -Melaksanakan tugas dengan kualitas terbaik

                                </td>
                                <td>Ekspektasi Khusus Pimpinan :<br />
                                    @foreach ($u->ekspektasi3 as $item)
                                    -{{$item->ekspektasi}} <br />
                                    @endforeach
                                </td>
                                <td>Pimpinan/Stakeholder (Nama) : </td>
                            </tr>
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                <td>4</td>
                                <td colspan="3">Harmonis</td>
                            </tr>
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                <td></td>
                                <td>
                                    -Menghargai setiap orang apapun latar belakangnya<br />
                                    -Suka menolong orang lain <br />
                                    -Membangun lingkungan kerja yang kondusif

                                </td>
                                <td>Ekspektasi Khusus Pimpinan :<br />
                                    @foreach ($u->ekspektasi4 as $item)
                                    -{{$item->ekspektasi}} <br />
                                    @endforeach
                                </td>
                                <td>Pimpinan/Stakeholder (Nama) : </td>
                            </tr>
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                <td>5</td>
                                <td colspan="3">Loyal</td>
                            </tr>
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                <td></td>
                                <td>
                                    -Memegang teguh ideologi Pancasila, Undang-Undang Dasar Negara Republik Indonesia
                                    Tahun 1945, setia kepada Negara Kesatuan Republik Indonesia serta pemerintahan yang
                                    sah<br />
                                    -Menjaga nama baik sesama ASN, Pimpinan, Instansi, dan Negara <br />
                                    -Menjaga rahasia jabatan dan negara

                                </td>
                                <td>Ekspektasi Khusus Pimpinan :<br />
                                    @foreach ($u->ekspektasi5 as $item)
                                    -{{$item->ekspektasi}} <br />
                                    @endforeach
                                </td>
                                <td>Pimpinan/Stakeholder (Nama) : </td>
                            </tr>
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                <td>6</td>
                                <td colspan="3">Adaptif</td>
                            </tr>
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                <td></td>
                                <td>
                                    -Cepat menyesuaikan diri menghadapi perubahan<br />
                                    -Terus berinovasi dan mengembangkan kreativitas <br />
                                    -Bertindak proaktif

                                </td>
                                <td>Ekspektasi Khusus Pimpinan :<br />
                                    @foreach ($u->ekspektasi6 as $item)
                                    -{{$item->ekspektasi}} <br />
                                    @endforeach
                                </td>
                                <td>Pimpinan/Stakeholder (Nama) : </td>
                            </tr>
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                <td>7</td>
                                <td colspan="3">Kolaboratif</td>
                            </tr>
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                <td></td>
                                <td>
                                    -Memberi kesempatan kepada berbagai pihak untuk berkontribusi<br />
                                    -Terbuka dalam bekerja sama untuk menghasilkan nilai tambah <br />
                                    -Menggerakkan pemanfaatan berbagai sumberdaya untuk tujuan bersama

                                </td>
                                <td>Ekspektasi Khusus Pimpinan :<br />
                                    @foreach ($u->ekspektasi7 as $item)
                                    -{{$item->ekspektasi}} <br />
                                    @endforeach
                                </td>
                                <td>Pimpinan/Stakeholder (Nama) : </td>
                            </tr>
                        </table>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr
                                    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px; background-color:rgb(218, 236, 249)">
                                    <th>RATING PERILAKU KERJA</th>
                                </tr>
                                <tr>
                                    <th>
                                        {{$u['rpk_tw'.$triwulan]}}
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- REALISASI UTAMA --}}

<div class="modal fade" id="modal-edit" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="post" action="/pegawai/new-skp/periode/evaluasi/{{$u->id}}/triwulan/{{$triwulan}}/realisasijf"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-gradient-primary" style="padding:10px">
                    <h4 class="modal-title text-sm">REALISASI</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>REALISASI</label>
                        <input type="text" class="form-control" id="realisasi" name="realisasi">
                        <input type="hidden" class="form-control" id="realisasi_id" name="realisasi_id">
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-block btn-primary"><i class="fas fa-paper-plane"></i>
                        Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SKP TAMBAHAN --}}

{{-- RENCANA AKSI --}}
<div class="modal fade" id="modal-ra" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="post" action="/pegawai/rencana-aksi" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-gradient-primary" style="padding:10px">
                    <h4 class="modal-title text-sm">Rencana Aksi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Realisasi</label>
                        <input type="text" class="form-control" id="realisasi_rencana_aksi" name="realisasi"
                            placeholder="realisasi" required>
                        <input type="hidden" class="form-control" id="ra_id" name="ra_id">
                    </div>
                    <div class="form-group">
                        <label>Bukti Dukung (link google drive)</label>
                        <input type="text" class="form-control" id="link" name="bukti_dukung"
                            placeholder="Link google drive" required>
                    </div>
                    <div class="form-group">
                        <label>Permasalahan / Faktor Keberhasilan</label>
                        <input type="text" class="form-control" id="masalah" name="masalah"
                            placeholder="tidak ada masalah" required>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-block btn-primary"><i class="fas fa-save"></i>
                        Simpan Rencana Aksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- rencana aksi --}}
@endsection

@push('js')

<script>
    $(document).on('click', '.edit-realisasi', function() {
    $('#realisasi').val($(this).data('realisasi'));
    $('#realisasi_id').val($(this).data('id'));
    $("#modal-edit").modal();
});
</script>


<script>
    $(document).on('click', '.rencana-aksi', function() {
        $('#ra_id').val($(this).data('id'));
        $('#realisasi_rencana_aksi').val($(this).data('realisasi'));
        $('#link').val($(this).data('link'));
        $('#masalah').val($(this).data('masalah'));
        $("#modal-ra").modal();
    });
</script>
@endpush