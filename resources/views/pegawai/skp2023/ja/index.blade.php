@extends('layouts.app')

@push('css')
  <link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Ionicons -->
  
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush
@section('title')
    PEGAWAI
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <a href="/pegawai/new-skp" class="btn btn-sm btn-secondary" type="submit"><i class="fas fa-arrow-left"></i>  Kembali</a><br/><br/>
        <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body p-2 text-center text-sm">
                    <strong>
                    SASARAN KINERJA PEGAWAI<br/>
                    JA<br/>
                    PENDEKATAN HASIL KERJA KUANTITATIF</strong>
                </div>
              </div>
              <div class="card">
                <div class="card-body p-1">
                    <table class="table table-sm">
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                            <td>{{Auth::user()->pegawai->skpd->nama}}</td>
                            <td>{{\Carbon\Carbon::parse($u->mulai)->translatedFormat('d F Y')}} sd {{\Carbon\Carbon::parse($u->sampai)->translatedFormat('d F Y')}}</td>
                        </tr>
                    </table>
                    <table class="table table-sm table-bordered">
                    <thead>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px; background-color:rgb(218, 236, 249)">
                            <th>NO</th>
                            <th colspan="2">PEGAWAI YG DINILAI <a href="/pegawai/new-skp/updatepegawai/{{$u->id}}" onclick="return confirm('Yakin ingin diupdate');"><i class="fa fa-refresh"></i> update</a></th>
                            <th>NO</th>
                            <th colspan="2">PEJABAT PENILAI KINERJA <a href="#" class="penilai"><i class="fas fa-edit"></i></a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                            <td>1</td>
                            <td>NAMA</td>
                            <td>{{$pn->nama}}</td>
                            <td>1</td>
                            <td>NAMA</td>
                            <td>{{$pp->nama}}</td>
                        </tr>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                            <td>2</td>
                            <td>NIP</td>
                            <td>{{$pn->nip}}</td>
                            <td>2</td>
                            <td>NIP</td>
                            <td>{{$pp->nip}}</td>
                        </tr>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                            <td>3</td>
                            <td>PANGKAT/GOL.RUANG</td>
                            <td>{{$pn->pangkat}} / {{$pn->gol}}</td>
                            <td>3</td>
                            <td>PANGKAT/GOL.RUANG</td>
                            <td>{{$pp->pangkat}} / {{$pp->gol}}</td>
                        </tr>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                            <td>4</td>
                            <td>JABATAN</td>
                            <td>{{$pn->jabatan == null ? '-': $pn->jabatan}}</td>
                            <td>4</td>
                            <td>JABATAN</td>
                            <td>{{$pp->jabatan == null ? '-': $pp->jabatan}}</td>
                        </tr>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                            <td>5</td>
                            <td>UNIT KERJA</td>
                            <td>{{$pn->unit_kerja}} <a href="#" class="unit-kerja" data-unitkerja="{{$pn->unit_kerja}}"><i class="fas fa-edit"></i></a></td>
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
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px; background-color:rgb(218, 236, 249)">
                            
                            <th colspan="6">HASIL KERJA</th>
                        </tr>
                        <tr class="text-center" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;background-color:rgb(218, 236, 249)">
                            <th>NO</th>
                            <th>RENCANA HASIL KERJA ATASAN YANG DIINTERVENSI</th>
                            <th>RENCANA HASIL KERJA</th>
                            <th>ASPEK</th>
                            <th>INDIKATOR KINERJA INDIVIDU</th>
                            <th>TARGET</th>
                        </tr>
                        <tr class="text-center" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:8px;background-color:rgb(218, 236, 249);">
                            <th>(1)</th>
                            <th>(2)</th>
                            <th>(3)</th>
                            <th>(4)</th>
                            <th>(5)</th>
                            <th>(6)</th>
                        </tr>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;background-color:rgb(218, 236, 249);">
                            <th colspan="6">A.UTAMA </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($skp_utama as $key => $item)

                            @php ($first = true) @endphp

                            @foreach ($item->indikator as $key2 => $item2) 
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                @if($first == true)
                                <td rowspan="{{$item->indikator->count() + 1}}" class="text-center">{{$key+1}}</td>
                                <td rowspan="{{$item->indikator->count() + 1}}">{{$item->rhk_intervensi}} 
                                    <a href="#" data-id="{{$item->id}}"
                                        data-rhk="{{$item->rhk}}" data-rhk_intervensi="{{$item->rhk_intervensi}}" class="edit-skp-utama"><i class="fas fa-edit"></i></a>
                                    <a href="/pegawai/new-skp/jf/utama/rhk/{{$item->id}}/delete" onclick="return confirm('Yakin ingin dihapus');"><i class="fas fa-trash"></i></a></td>

                                <td rowspan="{{$item->indikator->count() + 1}}">{{$item->rhk}}</td>
                                @php ($first = false) @endphp
                                @endif
                                <td>{{$item2->aspek}} 
                                    <a href="#" data-id="{{$item2->id}}"
                                        data-indikator="{{$item2->indikator}}" data-target="{{$item2->target}}" data-aspek="{{$item2->aspek}}" class="edit-indikator-utama"><i class="fas fa-edit"></i></a>
                                    <a href="/pegawai/new-skp/jf/utama/rhk/{{$item->id}}/indikator/{{$item2->id}}/delete" onclick="return confirm('Yakin ingin dihapus');"><i class="fas fa-trash"></i></a>
                                </td>
                                <td class="text-center">{{$item2->indikator}}</td>
                                <td class="text-center">{{$item2->target}}</td>
                            </tr>
                            @endforeach
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                <td><a href="#" class="indikator-skp-utama" data-id="{{$item->id}}"><i class="fas fa-plus-circle"></i></a></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td><a href="#" class="btn btn-xs btn-primary skp-utama"><i class="fas fa-plus-circle"> tambah skp</a></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;background-color:rgb(218, 236, 249);">
                            <th colspan="6">RENCANA AKSI (Tarik Dari Kayuh Baimbai) </th>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <a href="/pegawai/new-skp/periode/view/{{$u->id}}/tarik-rencana-aksi" class="btn btn-xs btn-primary "><i class="fas fa-sync"></i> Tarik Rencana Aksi</a>
                            </td>
                        </tr>

                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                            <td colspan="6">
                                <table>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Triwulan</th>
                                        <th>Keterangan</th>
                                        <th>Satuan</th>
                                        <th>Target</th>
                                        <th>Realisasi</th>
                                        <th>Bukti Dukung</th>
                                        <th>Masalah</th>
                                        <th>Umpan Balik Atasan</th>
                                    </tr>

                                @foreach ($u->rencana_aksi as $key => $item)

                                <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                    <td>{{$key + 1}}</td>
                                    <td>{{$item->tahun}}</td>
                                    <td>{{$item->triwulan}}</td>
                                    <td>{{$item->keterangan}}</td>
                                    <td>{{$item->satuan}}</td>
                                    <td>{{$item->target_kinerja}}</td>
                                    <td><a href="#" class="btn btn-xs btn-primary skp-utama"><i class="fas fa-plus-circle"> </a></td>
                                    <td><a href="#" class="btn btn-xs btn-primary skp-utama"><i class="fas fa-plus-circle"> </a></td>
                                        <td><a href="#" class="btn btn-xs btn-primary skp-utama"><i class="fas fa-plus-circle"> </a></td>
                                            <td><a href="#" class="btn btn-xs btn-primary skp-utama"><i class="fas fa-plus-circle"> </a></td>
                                </tr>
                                @endforeach
                                </table>
                            </td>
                        </tr>

                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;background-color:rgb(218, 236, 249);">
                            <th colspan="6">B.TAMBAHAN </th>
                        </tr>

                        @foreach ($skp_tambahan as $key => $item)

                            @php ($first = true) @endphp

                            @foreach ($item->indikator as $key2 => $item2) 
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                @if($first == true)
                                <td rowspan="{{$item->indikator->count() + 1}}" class="text-center">{{$key+1}}</td>
                                <td rowspan="{{$item->indikator->count() + 1}}">{{$item->rhk_intervensi}} 
                                    <a href="#" data-id="{{$item->id}}"
                                        data-rhk="{{$item->rhk}}" data-rhk_intervensi="{{$item->rhk_intervensi}}" class="edit-skp-tambahan"><i class="fas fa-edit"></i></a>
                                    <a href="/pegawai/new-skp/jf/tambahan/rhk/{{$item->id}}/delete" onclick="return confirm('Yakin ingin dihapus');"><i class="fas fa-trash"></i></a></td>
                                <td rowspan="{{$item->indikator->count() + 1}}">{{$item->rhk}} </td>
                                @php ($first = false) @endphp
                                @endif
                                <td class="text-center">{{$item2->aspek}}
                                    <a href="#" data-id="{{$item2->id}}"
                                        data-indikator="{{$item2->indikator}}" data-target="{{$item2->target}}" data-aspek="{{$item2->aspek}}" class="edit-indikator-tambahan"><i class="fas fa-edit"></i></a>
                                    <a href="/pegawai/new-skp/jf/tambahan/rhk/{{$item->id}}/indikator/{{$item2->id}}/delete" onclick="return confirm('Yakin ingin dihapus');"><i class="fas fa-trash"></i></a>
                                </td>
                                <td>{{$item2->indikator}} </td>
                                <td class="text-center">{{$item2->target}}</td>
                            </tr>
                            @endforeach
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                <td><a href="#" class="t-indikator-skp-tambahan" data-id="{{$item->id}}"><i class="fas fa-plus-circle"></i></a></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td><a href="#" class="btn btn-xs btn-primary skp-tambahan"><i class="fas fa-plus-circle"> tambah skp</a></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                    </table>
                    <table class="table table-sm table-bordered">
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;background-color:rgb(218, 236, 249);">
                            <th colspan="5">PERILAKU KERJA </th>
                        </tr>
                        
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                            <td>1</td>
                            <td colspan="2">Berorientasi Pelayanan</td>
                        </tr>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                            <td></td>
                            <td width="50%">
                                -Memahami dan memenuhi kebutuhan masyarakat<br/>
                                -Ramah, cekatan, solutif, dan dapat diandalkan <br/>
                                -melakukan perbaikan tiada henti

                            </td>
                            <td>Ekspektasi Khusus Pimpinan :</td>
                        </tr>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                            <td>2</td>
                            <td colspan="2">Akuntabel</td>
                        </tr>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                            <td></td>
                            <td>
                                -Melaksanakan tugas dengan jujur, bertanggungjawab, cermat, disiplin dan berintegritas tinggi<br/>
                                -Menggunakan kekayaan dan barang milik negara secara bertanggungjawab, efektif, dan efisien <br/>
                                -Tidak menyalahgunakan kewenangan jabatan

                            </td>
                            <td>Ekspektasi Khusus Pimpinan :</td>
                        </tr>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                            <td>3</td>
                            <td colspan="2">Kompeten</td>
                        </tr>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                            <td></td>
                            <td>
                                -Meningkatkan kompetensi diri untuk menjawab tantangan yang selalu berubah<br/>
                                -Membantu orang lain belajar <br/>
                                -Melaksanakan tugas dengan kualitas terbaik

                            </td>
                            <td>Ekspektasi Khusus Pimpinan :</td>
                        </tr>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                            <td>4</td>
                            <td colspan="2">Harmonis</td>
                        </tr>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                            <td></td>
                            <td>
                                -Menghargai setiap orang apapun latar belakangnya<br/>
                                -Suka menolong orang lain <br/>
                                -Membangun lingkungan kerja yang kondusif

                            </td>
                            <td>Ekspektasi Khusus Pimpinan :</td>
                        </tr>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                            <td>5</td>
                            <td colspan="2">Loyal</td>
                        </tr>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                            <td></td>
                            <td>
                                -Memegang teguh ideologi Pancasila, Undang-Undang Dasar Negara Republik Indonesia Tahun 1945, setia kepada Negara Kesatuan Republik Indonesia serta pemerintahan yang sah<br/>
                                -Menjaga nama baik sesama ASN, Pimpinan, Instansi, dan Negara <br/>
                                -Menjaga rahasia jabatan dan negara

                            </td>
                            <td>Ekspektasi Khusus Pimpinan :</td>
                        </tr>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                            <td>6</td>
                            <td colspan="2">Adaptif</td>
                        </tr>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                            <td></td>
                            <td>
                                -Cepat menyesuaikan diri menghadapi perubahan<br/>
                                -Terus berinovasi dan mengembangkan kreativitas <br/>
                                -Bertindak proaktif

                            </td>
                            <td>Ekspektasi Khusus Pimpinan :</td>
                        </tr>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                            <td>7</td>
                            <td colspan="2">Kolaboratif</td>
                        </tr>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                            <td></td>
                            <td>
                                -Memberi kesempatan kepada berbagai pihak untuk berkontribusi<br/>
                                -Terbuka dalam bekerja sama untuk menghasilkan nilai tambah <br/>
                                -Menggerakkan pemanfaatan berbagai sumberdaya untuk tujuan bersama

                            </td>
                            <td>Ekspektasi Khusus Pimpinan :</td>
                        </tr>
                    </table>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>

{{-- SKP UTAMA --}}
<div class="modal fade" id="modal-tambah" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="post" action="/pegawai/new-skp/jf/utama/rhk/{{$u->id}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-gradient-primary" style="padding:10px">
                    <h4 class="modal-title text-sm">SKP UTAMA</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>RENCANA HASIL KERJA ATASAN YG DI INTERVENSI</label>
                        <input type="text" class="form-control" name="rhk_intervensi" placeholder="rencana hasil kerja" required>
                    </div>
                    <div class="form-group">
                        <label>RENCANA HASIL KERJA</label>
                        <input type="text" class="form-control" name="rhk" placeholder="rencana hasil kerja" required>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-block btn-primary"><i class="fas fa-paper-plane"></i>
                        Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="post" action="/pegawai/new-skp/jf/utama/rhk/{{$u->id}}/edit" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-gradient-primary" style="padding:10px">
                    <h4 class="modal-title text-sm">EDIT SKP UTAMA</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>RENCANA HASIL KERJA ATASAN YG DI INTERVENSI</label>
                        <input type="text" class="form-control" id="rhk_intervensi" name="rhk_intervensi">
                        <input type="hidden" class="form-control" id="skp2023_id" name="skp2023_id">
                    </div>
                    <div class="form-group">
                        <label>RENCANA HASIL KERJA </label>
                        <input type="text" class="form-control" id="rhk" name="rhk" required>
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

<div class="modal fade" id="modal-tambah-indikator" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="post" action="/pegawai/new-skp/jf/utama/rhk/{{$u->id}}/indikator" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-gradient-primary" style="padding:10px">
                    <h4 class="modal-title text-sm">INDIKATOR SKP UTAMA</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>ASPEK</label>
                        <input type="text" class="form-control" name="aspek" placeholder="aspek" required>
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="skp2023_jf_id" id="skp2023_jf_id" required>
                        <label>INDIKATOR</label>
                        <input type="text" class="form-control" name="indikator" placeholder="indikator" required>
                    </div>
                    <div class="form-group">
                        <label>TARGET</label>
                        <input type="text" class="form-control" name="target" placeholder="target" required>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-block btn-primary"><i class="fas fa-paper-plane"></i>
                        Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit-indikator" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="post" action="/pegawai/new-skp/jf/utama/rhk/{{$u->id}}/indikator/edit" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-gradient-primary" style="padding:10px">
                    <h4 class="modal-title text-sm">INDIKATOR SKP UTAMA</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>ASPEK</label>
                        <input type="text" class="form-control" name="aspek" id="aspek" required>
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="skp2023_jf_indikator_id" id="skp2023_jf_indikator_id">
                        <label>INDIKATOR</label>
                        <input type="text" class="form-control" name="indikator" id="indikator" required>
                    </div>
                    <div class="form-group">
                        <label>TARGET</label>
                        <input type="text" class="form-control" name="target" id="target" required>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-block btn-primary"><i class="fas fa-paper-plane"></i>
                        Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SKP TAMBAHAN --}}
<div class="modal fade" id="t-modal-tambah" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="post" action="/pegawai/new-skp/jf/tambahan/rhk/{{$u->id}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-gradient-primary" style="padding:10px">
                    <h4 class="modal-title text-sm">SKP TAMBAHAN</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>RENCANA HASIL KERJA ATASAN YG DIINTERVENSI</label>
                        <input type="text" class="form-control" name="rhk_intervensi_tambahan" required>
                    </div>
                    <div class="form-group">
                        <label>RENCANA HASIL KERJA</label>
                        <input type="text" class="form-control" name="rhk_tambahan" required>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-block btn-primary"><i class="fas fa-paper-plane"></i>
                        Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="t-modal-edit" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="post" action="/pegawai/new-skp/jf/tambahan/rhk/{{$u->id}}/edit" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-gradient-primary" style="padding:10px">
                    <h4 class="modal-title text-sm">EDIT SKP TAMBAHAN</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>RENCANA HASIL KERJA ATASAN YG DIINTERVENSI</label>
                        <input type="text" class="form-control" name="rhk_intervensi" id="rhk_intervensi_tambahan" required>
                    </div>
                    <div class="form-group">
                        <label>RENCANA HASIL KERJA</label>
                        <input type="text" class="form-control" id="rhk_tambahan" name="rhk">
                        <input type="hidden" class="form-control" id="skp2023_id_tambahan" name="skp2023_id_tambahan">
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

<div class="modal fade" id="t-modal-tambah-indikator" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="post" action="/pegawai/new-skp/jf/tambahan/rhk/{{$u->id}}/indikator" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-gradient-primary" style="padding:10px">
                    <h4 class="modal-title text-sm">INDIKATOR SKP TAMBAHAN</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>ASPEK</label>
                        <input type="text" class="form-control" name="aspek" placeholder="aspek" required>
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="skp2023_jf_id_tambahan" id="skp2023_jf_id_tambahan" required>
                        <label>INDIKATOR</label>
                        <input type="text" class="form-control" name="indikator" placeholder="indikator" required>
                    </div>
                    <div class="form-group">
                        <label>TARGET</label>
                        <input type="text" class="form-control" name="target" placeholder="target" required>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-block btn-primary"><i class="fas fa-paper-plane"></i>
                        Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="t-modal-edit-indikator" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="post" action="/pegawai/new-skp/jf/tambahan/rhk/{{$u->id}}/indikator/edit" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-gradient-primary" style="padding:10px">
                    <h4 class="modal-title text-sm">EDIT INDIKATOR SKP TAMBAHAN</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>ASPEK</label>
                        <input type="text" class="form-control" name="aspek" id="aspek_tambahan" required>
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="skp2023_jf_indikator_id_tambahan" id="skp2023_jf_indikator_id_tambahan">
                        <label>INDIKATOR</label>
                        <input type="text" class="form-control" name="indikator" id="indikator_tambahan" required>
                    </div>
                    <div class="form-group">
                        <label>TARGET</label>
                        <input type="text" class="form-control" name="target" id="target_tambahan" required>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-block btn-primary"><i class="fas fa-paper-plane"></i>
                        Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-unit-kerja" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="post" action="/pegawai/new-skp/unitkerja/{{$u->id}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-gradient-primary" style="padding:10px">
                    <h4 class="modal-title text-sm">UNIT KERJA</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>UNIT KERJA</label>
                        <input type="text" class="form-control" name="unit kerja" id="unit_kerja" placeholder="unit kerja" required>
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

<div class="modal fade" id="modal-penilai" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="post" action="/pegawai/new-skp/penilai/{{$u->id}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-gradient-primary" style="padding:10px">
                    <h4 class="modal-title text-sm">PENILAI</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>PENILAI</label>
                        
                        <select id="selPenilai" class="form-control form-control-sm select2 selPenilai" name="nip">
                              
                        </select>
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
@endsection

@push('js')

<script>
$(document).on('click', '.skp-utama', function() {
   $("#modal-tambah").modal();
});

$(document).on('click', '.edit-skp-utama', function() {
   $('#skp2023_id').val($(this).data('id'));
   $('#rhk').val($(this).data('rhk'));
   $('#rhk_intervensi').val($(this).data('rhk_intervensi'));
   $("#modal-edit").modal();
});

$(document).on('click', '.indikator-skp-utama', function() {
   $('#skp2023_jf_id').val($(this).data('id'));
   $("#modal-tambah-indikator").modal();
});

$(document).on('click', '.edit-indikator-utama', function() {
   $('#skp2023_jf_indikator_id').val($(this).data('id'));
   $('#indikator').val($(this).data('indikator'));
   $('#target').val($(this).data('target'));
   $('#aspek').val($(this).data('aspek'));
   $("#modal-edit-indikator").modal();
});
</script>

<script>
$(document).on('click', '.skp-tambahan', function() {
    $("#t-modal-tambah").modal();
 });
 
 $(document).on('click', '.edit-skp-tambahan', function() {
    $('#skp2023_id_tambahan').val($(this).data('id'));
    $('#rhk_tambahan').val($(this).data('rhk'));
    $('#rhk_intervensi_tambahan').val($(this).data('rhk_intervensi'));
    $("#t-modal-edit").modal();
 });
 
 $(document).on('click', '.t-indikator-skp-tambahan', function() {
    $('#skp2023_jf_id_tambahan').val($(this).data('id'));
    $("#t-modal-tambah-indikator").modal();
 });
 
 $(document).on('click', '.edit-indikator-tambahan', function() {
    $('#skp2023_jf_indikator_id_tambahan').val($(this).data('id'));
    $('#indikator_tambahan').val($(this).data('indikator'));
    $('#target_tambahan').val($(this).data('target'));
    $('#aspek_tambahan').val($(this).data('aspek'));
    $("#t-modal-edit-indikator").modal();
 });


 $(document).on('click', '.unit-kerja', function() {
    $('#unit_kerja').val($(this).data('unitkerja'));
    $("#modal-unit-kerja").modal();
 });
 
 $(document).on('click', '.penilai', function() {
    $("#modal-penilai").modal();
 });
 
 </script>
 
<script src="/theme/plugins/select2/js/select2.full.min.js"></script>
<script>
    $(function () {
      //Initialize Select2 Elements
      $('.select2').select2()
    })
</script>
<script>
    $(document).ready(function(){
         $("#selPenilai").select2({
            placeholder: '-Pilih-',
            ajax: { 
            url: '/pegawai/new-skp/getPenilai',
            type: "post",
            dataType: 'json',
            delay: 250,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: function (params) {
                return {
                searchTerm: params.term // search term
                };
            },
            processResults: function (response) {
              console.log(response);
                var data_array = [];
                        response.forEach(function(value,key){
                    data_array.push({id:value.nip,text:value.nip+' - '+value.nama})
                });
                return {
                    results: data_array
                };
            },
            cache: true
            }
        });
    });
</script>
@endpush