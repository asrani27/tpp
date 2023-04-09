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
        <a href="/pegawai/nilai-skp" class="btn btn-sm btn-secondary" type="submit"><i class="fas fa-arrow-left"></i>  Kembali</a><br/><br/>
        <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body p-2 text-center text-sm">
                    <strong>
                    SASARAN KINERJA PEGAWAI<br/>
                    JA<br/>
                    PENDEKATAN HASIL KERJA KUANTITATIF<br/>
                    TRIWULAN : {{$triwulan}}</strong>
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
                            <th colspan="2">PEGAWAI YG DINILAI</th>
                            <th>NO</th>
                            <th colspan="2">PEJABAT PENILAI KINERJA</th>
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
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px; background-color:rgb(218, 236, 249)">
                            
                            <th colspan="8">HASIL KERJA</th>
                        </tr>
                        <tr class="text-center" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;background-color:rgb(218, 236, 249)">
                            <th>NO</th>
                            <th>RENCANA HASIL KERJA ATASAN YANG DIINTERVENSI</th>
                            <th>RENCANA HASIL KERJA</th>
                            <th>ASPEK</th>
                            <th>INDIKATOR KINERJA INDIVIDU</th>
                            <th>TARGET</th>
                            <th>REALISASI BERDASARKAN BUKTI DUKUNG</th>
                            <th>UMPAN BALIK BERKELANJUTAN BERDASARKAN BUKTI DUKUNG</th>
                        </tr>
                        <tr class="text-center" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:8px;background-color:rgb(218, 236, 249);">
                            <th>(1)</th>
                            <th>(2)</th>
                            <th>(3)</th>
                            <th>(4)</th>
                            <th>(5)</th>
                            <th>(6)</th>
                            <th>(7)</th>
                            <th>(8)</th>
                        </tr>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;background-color:rgb(218, 236, 249);">
                            <th colspan="8">A.UTAMA </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($skp_utama as $key => $item)

                            @php ($first = true) @endphp

                            @foreach ($item->indikator as $key2 => $item2) 
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                @if($first == true)
                                <td rowspan="{{$item->indikator->count()}}" class="text-center">{{$key+1}}</td>
                                <td rowspan="{{$item->indikator->count()}}">{{$item->rhk_intervensi}}</td>

                                <td rowspan="{{$item->indikator->count()}}">{{$item->rhk}} </td>
                                @php ($first = false) @endphp
                                @endif
                                <td>{{$item2->aspek}}
                                </td>
                                <td class="text-center">{{$item2->indikator}}</td>
                                <td class="text-center">{{$item2->target}}</td>
                                <td>{{$item2['real_tw'. $triwulan]}}</td>
                                <td>Pimpinan : {{$item2['ub_tw'. $triwulan]}}
                                    <a href="#" data-id="{{$item2->id}}" data-umpan_balik="{{$item2['ub_tw'. $triwulan]}}" class="edit-umpan-balik"><i class="fas fa-edit"></i></a></td>
                            </tr>
                            @endforeach
                        @endforeach
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;background-color:rgb(218, 236, 249);">
                            <th colspan="8">B.TAMBAHAN </th>
                        </tr>

                        @foreach ($skp_tambahan as $key => $item)

                            @php ($first = true) @endphp

                            @foreach ($item->indikator as $key2 => $item2) 
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
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
                                <td>{{$item2['real_tw'. $triwulan]}}</td>
                                <td>Pimpinan : {{$item2['ub_tw'. $triwulan]}}
                                    <a href="#" data-id="{{$item2->id}}" data-umpan_balik="{{$item2['ub_tw'. $triwulan]}}" class="edit-umpan-balik"><i class="fas fa-edit"></i></a></td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                    </table>

                    <table class="table table-sm table-bordered">
                        <thead>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px; background-color:rgb(218, 236, 249)">
                            <th>RATING HASIL KERJA</th>
                        </tr>
                        <tr>
                            <th>
                                <form method="post" action="/pegawai/nilai-rhk/triwulan/{{$triwulan}}/{{$u->id}}">
                                    @csrf
                                    
                                <select name="rating" class="form-control text-sm" required>
                                    <option value="">-pilih-</option>
                                    <option value="DIATAS EKSPEKTASI" {{$u['rhk_tw'.$triwulan] == 'DIATAS EKSPEKTASI' ? 'selected':''}}>DIATAS EKSPEKTASI</option>
                                    <option value="SESUAI EKSPEKTASI" {{$u['rhk_tw'.$triwulan] == 'SESUAI EKSPEKTASI' ? 'selected':''}}>SESUAI EKSPEKTASI</option>
                                    <option value="DIBAWAH EKSPEKTASI" {{$u['rhk_tw'.$triwulan] == 'DIBAWAH EKSPEKTASI' ? 'selected':''}}>DIBAWAH EKSPEKTASI</option>
                                </select>
                                <button type="submit" class='btn btn-sm btn-primary btn-block'>Update Rating Hasil Kerja</button>
                                </form>
                            </th>
                        </tr>
                        </thead>
                    </table>

                    <table class="table table-sm table-bordered">
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;background-color:rgb(218, 236, 249);">
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
                                -Memahami dan memenuhi kebutuhan masyarakat<br/>
                                -Ramah, cekatan, solutif, dan dapat diandalkan <br/>
                                -melakukan perbaikan tiada henti
                            </td>
                            <td>Ekspektasi Khusus Pimpinan : <br/>
                                @foreach ($u->ekspektasi1 as $item)
                                    
                                <a href="/pegawai/nilai-skp/ekspektasi/delete/{{$item->id}}" onclick="return confirm('Yakin Ingin Di hapus?');"><i class="fas fa-trash"></i></a> -{{$item->ekspektasi}} <br/>
                                @endforeach
                                <a href="#" class="ekspektasi" data-id="{{$u->id}}" data-tw="{{$triwulan}}" data-pkid="1" data-namapk="Berorientasi Pelayanan"><i class="fas fa-plus-circle"></i></a>
                            </td>
                            <td>Pimpinan/Stakeholder (Nama) : </td>
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
                            <td>Ekspektasi Khusus Pimpinan : <br/>
                                @foreach ($u->ekspektasi2 as $item)
                                    
                                <a href="/pegawai/nilai-skp/ekspektasi/delete/{{$item->id}}" onclick="return confirm('Yakin Ingin Di hapus?');"><i class="fas fa-trash"></i></a> -{{$item->ekspektasi}} <br/>
                                @endforeach
                                <a href="#" class="ekspektasi" data-id="{{$u->id}}" data-tw="{{$triwulan}}" data-pkid="2" data-namapk="Akuntabel"><i class="fas fa-plus-circle"></i></a>
                            </td>
                            <td>Pimpinan/Stakeholder (Nama) : </td>
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
                            <td>Ekspektasi Khusus Pimpinan : <br/>
                                @foreach ($u->ekspektasi3 as $item)
                                    
                                <a href="/pegawai/nilai-skp/ekspektasi/delete/{{$item->id}}" onclick="return confirm('Yakin Ingin Di hapus?');"><i class="fas fa-trash"></i></a> -{{$item->ekspektasi}} <br/>
                                @endforeach
                                <a href="#" class="ekspektasi" data-id="{{$u->id}}" data-tw="{{$triwulan}}" data-pkid="3" data-namapk="Kompeten"><i class="fas fa-plus-circle"></i></a>
                            </td>
                            <td>Pimpinan/Stakeholder (Nama) : </td>
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
                            <td>Ekspektasi Khusus Pimpinan : <br/>
                                @foreach ($u->ekspektasi4 as $item)
                                    
                                <a href="/pegawai/nilai-skp/ekspektasi/delete/{{$item->id}}" onclick="return confirm('Yakin Ingin Di hapus?');"><i class="fas fa-trash"></i></a> -{{$item->ekspektasi}} <br/>
                                @endforeach
                                <a href="#" class="ekspektasi" data-id="{{$u->id}}" data-tw="{{$triwulan}}" data-pkid="4" data-namapk="Harmonis"><i class="fas fa-plus-circle"></i></a>
                            </td>
                            <td>Pimpinan/Stakeholder (Nama) : </td>
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
                            <td>Ekspektasi Khusus Pimpinan : <br/>
                                @foreach ($u->ekspektasi5 as $item)
                                    
                                <a href="/pegawai/nilai-skp/ekspektasi/delete/{{$item->id}}" onclick="return confirm('Yakin Ingin Di hapus?');"><i class="fas fa-trash"></i></a> -{{$item->ekspektasi}} <br/>
                                @endforeach
                                <a href="#" class="ekspektasi" data-id="{{$u->id}}" data-tw="{{$triwulan}}" data-pkid="5" data-namapk="Loyal"><i class="fas fa-plus-circle"></i></a>
                            </td>
                            <td>Pimpinan/Stakeholder (Nama) : </td>
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
                            <td>Ekspektasi Khusus Pimpinan : <br/>
                                @foreach ($u->ekspektasi6 as $item)
                                    
                                <a href="/pegawai/nilai-skp/ekspektasi/delete/{{$item->id}}" onclick="return confirm('Yakin Ingin Di hapus?');"><i class="fas fa-trash"></i></a> -{{$item->ekspektasi}} <br/>
                                @endforeach
                                <a href="#" class="ekspektasi" data-id="{{$u->id}}" data-tw="{{$triwulan}}" data-pkid="6" data-namapk="Adaptif"><i class="fas fa-plus-circle"></i></a>
                            </td>
                            <td>Pimpinan/Stakeholder (Nama) : </td>
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
                            <td>Ekspektasi Khusus Pimpinan : <br/>
                                @foreach ($u->ekspektasi7 as $item)
                                    
                                <a href="/pegawai/nilai-skp/ekspektasi/delete/{{$item->id}}" onclick="return confirm('Yakin Ingin Di hapus?');"><i class="fas fa-trash"></i></a> -{{$item->ekspektasi}} <br/>
                                @endforeach
                                <a href="#" class="ekspektasi" data-id="{{$u->id}}" data-tw="{{$triwulan}}" data-pkid="7" data-namapk="Kolaboratif"><i class="fas fa-plus-circle"></i></a>
                            </td>
                            <td>Pimpinan/Stakeholder (Nama) : </td>
                        </tr>
                    </table>
                    <table class="table table-sm table-bordered">
                        <thead>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px; background-color:rgb(218, 236, 249)">
                            <th>RATING PERILAKU KERJA</th>
                        </tr>
                        <tr>
                            <th>
                                <form method="post" action="/pegawai/nilai-rpk/triwulan/{{$triwulan}}/{{$u->id}}">
                                    @csrf
                                    
                                <select name="rating" class="form-control text-sm" required>
                                    <option value="">-pilih-</option>
                                    <option value="DIATAS EKSPEKTASI" {{$u['rpk_tw'.$triwulan] == 'DIATAS EKSPEKTASI' ? 'selected':''}}>DIATAS EKSPEKTASI</option>
                                    <option value="SESUAI EKSPEKTASI" {{$u['rpk_tw'.$triwulan] == 'SESUAI EKSPEKTASI' ? 'selected':''}}>SESUAI EKSPEKTASI</option>
                                    <option value="DIBAWAH EKSPEKTASI" {{$u['rpk_tw'.$triwulan] == 'DIBAWAH EKSPEKTASI' ? 'selected':''}}>DIBAWAH EKSPEKTASI</option>
                                </select>
                                <button type="submit" class='btn btn-sm btn-primary btn-block'>Update Rating Perilaku Kerja</button>
                                </form>
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
            <form method="post" action="/pegawai/nilai-skp/triwulan/{{$triwulan}}/{{$u->id}}/jf" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-gradient-primary" style="padding:10px">
                    <h4 class="modal-title text-sm">UMPAN BALIK</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>UMPAN BALIK</label>
                        <input type="text" class="form-control" id="umpan_balik" name="umpan_balik">
                        <input type="hidden" class="form-control" id="umpan_balik_id" name="umpan_balik_id">
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
<div class="modal fade" id="modal-tambah" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="post" action="/pegawai/nilai-skp/ekspektasi/{{$u->id}}/triwulan" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-gradient-primary" style="padding:10px">
                    <h4 class="modal-title text-sm">EKSPEKTASI</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>JENIS</label>
                        <input type="text" class="form-control" id="namapk" name="namapk" readonly>
                        <input type="hidden" class="form-control" id="pk" name="pkid" readonly>
                    </div>
                    <div class="form-group">
                        <label>TRIWULAN</label>
                        <input type="text" class="form-control" id="triwulan" name="triwulan" readonly>
                    </div>
                    <div class="form-group">
                        <label>EKSPEKTASI</label>
                        <input type="text" class="form-control" name="ekspektasi" placeholder="ekspektasi" required>
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
@endsection

@push('js')

<script>

$(document).on('click', '.ekspektasi', function() {
   $('#skp2023_id').val($(this).data('id'));
   $('#pk').val($(this).data('pkid'));
   $('#namapk').val($(this).data('namapk'));
   $('#triwulan').val($(this).data('tw'));
   $("#modal-tambah").modal();
});

    $(document).on('click', '.edit-umpan-balik', function() {
        $('#umpan_balik').val($(this).data('umpan_balik'));
        $('#umpan_balik_id').val($(this).data('id'));
        $("#modal-edit").modal();
    });
</script>

@endpush