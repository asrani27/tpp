@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

<meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush
@section('title')
SUPERADMIN
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-2">
                        @include('admin.rekap2023.menu')
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar TPP REGULER Bulan
                    {{convertBulan($bulan)}} {{$tahun}}</h3>
            </div>
            <div class="card-body p-2">
                <div class="alert alert-default alert-dismissible text-sm">
                    <h5><i class="icon fas fa-info"></i> Informasi</h5>
                    Format Laporan TPP terbaru, langkah-langkahnya adalah<br />
                    1. Memasukkan pegawai terlebih dahulu, klik <span class="badge badge-primary">
                        masukkan pegawai</span>, jika
                        ada pegawai baru dan yang membayarkan SKPD yang lama, klik tombol <span
                            class="badge badge-danger">hapus</span> paling kanan di daftar
                        laporan. Jika ada pegawai yang sudah mutasi/keluar namun masih di bayarkan TPP nya, klik <span class="badge badge-primary">tambah pegawai</span>, pilih pegawai dan jabatan sebelum pindah<br />


                    2. Klik Tombol <span class="badge badge-secondary">Tarik PSA</span> (Presensi, SKP, dan Aktivitas), untuk menarik data presensi, data nilai SKP dan menit aktivitas<br />
                        
                    3. Melakukan perhitungan dengan mengklik tombol <span class="badge badge-warning">perhitungan</span>
                    akan menghitung di kolom perhitungan<br />
                    
                    4. Bila basic tpp = 0, dan kolomnya berwarna <span class="badge badge-danger">MERAH</span>, maka
                    kemungkinan besar, ASN tsb pensiun / tidak memiliki jabatan/persen jabatan 0, Solusinya klik tombol
                    hapus di paling kanan
                    jika pensiun, jika tidak pensiun hapus terlebih dahulu, kemudian masukkan/input kembali di kolom
                    paling bawah.
                </div>
                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/reguler/mp" class="btn btn-flat btn-xs btn-primary"
                    onclick="return confirm('Yakin Ingin Memasukkan Semua Pegawai Pada Bulan Ini?');">Masukkan
                    Semua Pegawai</a>
                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/reguler/psa" class="btn btn-flat btn-xs btn-secondary"
                    onclick="return confirm('Proses ini memakan beberapa waktu, harap di tunggu?');">Tarik PSA</a>
                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/reguler/perhitungan" class="btn btn-flat btn-xs btn-warning"
                    onclick="return confirm('Proses ini memakan beberapa waktu, harap di tunggu?');">Perhitungan</a>

                    <a href="#" class="btn btn-xs btn-flat btn-primary tambahpegawai"><i class="fas fa-user-plus"></i>Tambah Pegawai</a>
                    {{-- <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/reguler/excel" class="btn btn-xs btn-flat btn-primary"><i class="fas fa-file-excel"></i> Export To Excel</a> --}}
                <br /><br />

                <table class="table table-hover text-nowrap table-sm table-responsive ">
                    <thead>
                        <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif" class="text-center">
                            <th style="background-color:antiquewhite; border:1px solid silver" rowspan=4
                                style="width: 10px">#</th>
                            <th style="background-color:antiquewhite; border:1px solid silver" rowspan=4>Nama
                                <br />NIP<br />Pangkat<br />Golongan
                            </th>
                            <th style="background-color:antiquewhite; border:1px solid silver" rowspan=4>Jabatan</th>
                            <th style="background-color:antiquewhite; border:1px solid silver" rowspan=4>Jenis
                                <br />Jabatan
                            </th>
                            <th style="background-color:antiquewhite; border:1px solid silver" rowspan=4>Kelas</th>
                            
                            <th style="background-color:#d6fdf9; border:1px solid silver" colspan=9>Disiplin Dan Produktivitas</th>
                            <th style="background-color:#f9cb9c; border:1px solid silver" rowspan=4>Basic<br/> TPP</th>
                            <th style="background-color:#f9cb9c; border:1px solid silver" colspan=4>Persentase</th>
                            <th style="background-color:#f9cb9c; border:1px solid silver" rowspan=4>Pagu<br/> TPP ASN</th>
                            <th style="background-color:#bbfac6; border:1px solid silver" colspan=13>Pembayaran</th>
                            <th style="background-color:#7ef8f8; border:1px solid silver" rowspan=4>PPH 21</th>
                            <th style="background-color:#7ef8f8; border:1px solid silver" rowspan=4>BPJS 1%</th>
                            <th style="background-color:#7ef8f8; border:1px solid silver" rowspan=4>BPJS 4%</th>
                            <th style="background-color:#7ef8f8; border:1px solid silver" rowspan=4>TPP Diterima</th>
                            
                            <th rowspan=4 style="border:1px solid silver">Aksi</th>
                        </tr>
                        <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif" class="text-center">
                            <th colspan=6 style="background-color:#d6fdf9; border:1px solid silver">Aktivitas (menit)</th>
                            <th style="background-color:#d6fdf9; border:1px solid silver" rowspan=2>Total<br/> Aktivitas</th>
                            <th style="background-color:#d6fdf9; border:1px solid silver" rowspan=2>Absensi</th>
                            <th style="background-color:#d6fdf9; border:1px solid silver" rowspan=2>Penilaian<br/> Kinerja</th>
                            <th style="background-color:#f9cb9c; border:1px solid silver" rowspan=3>Beban<br/> Kerja</th>
                            <th style="background-color:#f9cb9c; border:1px solid silver" rowspan=3>Prestasi<br/> Kerja</th>
                            <th style="background-color:#f9cb9c; border:1px solid silver" rowspan=3>Kondisi<br/> Kerja</th>
                            <th style="background-color:#f9cb9c; border:1px solid silver" rowspan=3>Kelangkaan<br/> Profesi</th>
                            <th style="background-color:#bbfac6; border:1px solid silver" colspan=3>Beban Kerja</th>
                            <th style="background-color:#bbfac6; border:1px solid silver" rowspan=3>Jumlah<br/> Beban Kerja</th>
                            <th style="background-color:#bbfac6; border:1px solid silver" colspan=3>Prestasi Kerja</th>
                            <th style="background-color:#bbfac6; border:1px solid silver" rowspan=3>Jumlah <br/>Prestasi Kerja</th>
                            <th style="background-color:#bbfac6; border:1px solid silver" rowspan=3>Kondisi Kerja</th>
                            <th style="background-color:#bbfac6; border:1px solid silver" rowspan=3>Jumlah <br/>Kondisi Kerja</th>
                            <th style="background-color:#bbfac6; border:1px solid silver" rowspan=3>Kelangkaan<br/> Profesi</th>
                            <th style="background-color:#bbfac6; border:1px solid silver" rowspan=3>Jumlah <br/>Kelangkaan <br/>Profesi</th>
                            <th style="background-color:#bbfac6; border:1px solid silver" rowspan=3>Jumlah <br/>Pembayaran</th>
                        </tr>
                        <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif" class="text-center">
                            <th style="background-color:#d6fdf9; border:1px solid silver">Aktivitas</th>
                            <th style="background-color:#d6fdf9; border:1px solid silver">CT</th>
                            <th style="background-color:#d6fdf9; border:1px solid silver">TL</th>
                            <th style="background-color:#d6fdf9; border:1px solid silver">Covid</th>
                            <th style="background-color:#d6fdf9; border:1px solid silver">Diklat</th>
                            <th style="background-color:#d6fdf9; border:1px solid silver">CB</th>
                            <th style="background-color:#bbfac6; border:1px solid silver">Absensi</th>
                            <th style="background-color:#bbfac6; border:1px solid silver">Aktivitas</th>
                            <th style="background-color:#bbfac6; border:1px solid silver">Kinerja</th>
                            <th style="background-color:#bbfac6; border:1px solid silver">Absensi</th>
                            <th style="background-color:#bbfac6; border:1px solid silver">Aktivitas</th>
                            <th style="background-color:#bbfac6; border:1px solid silver">Kinerja</th>
                        </tr>
                        
                    </thead>
                    @php
                    $no =1;
                    @endphp
                    <tbody>
                        @foreach ($data as $item)
                            <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif;
                            @if($item->dp_ta < 6750)
                            background-color:#f2dede
                            @endif
                            @if($item->puskesmas_id == 37)
                            background-color:#b0fffe
                            @endif">
                                <td>{{$no++}}</td>
                                <td>
                                    {{$item->nama}} <br />
                                    {{$item->pangkat}} {{$item->golongan}} <br />
                                    NIP.{{$item->nip}}
                                </td>
                                <td>
                                    {!!wordwrap($item->jabatan,40,"<br>")!!}

                                <a href="#" class="btn btn-xs editjabatan" data-id="{{$item->id}}"
                                    data-nama="{{$item->nama}}" data-jabatan="{{$item->jabatan}}"><i class="fas fa-edit"></i></a>

                                </td>
                                <td>{{$item->jenis_jabatan}}</td>
                                <td>{{$item->kelas}}</td>
                                <td class="text-right">{{$item->dp_aktivitas}}</td>
                                <td class="text-right">{{$item->dp_ct}}</td>
                                <td class="text-right">{{$item->dp_tl}}</td>
                                <td class="text-right">{{$item->dp_covid}}</td>
                                <td class="text-right">{{$item->dp_diklat}}</td>
                                <td class="text-right">{{$item->dp_cb}}</td>
                                <td class="text-right">{{$item->dp_ta}}</td>
                                <td class="text-right">{{$item->dp_absensi}} %</td>
                                <td class="text-right">{{$item->dp_skp}}</td>
                                <td class="text-right">{{number_format($item->basic)}}</td>
                                <td class="text-right">{{$item->p_bk + $item->p_tbk}}</td>
                                <td class="text-right">{{$item->p_pk}}</td>
                                <td class="text-right">{{$item->p_kk}}</td>
                                <td class="text-right">{{$item->p_kp}}</td>
                                <td class="text-right">{{number_format($item->pagu)}}</td>
                                <td class="text-right">{{number_format($item->pbk_absensi)}}</td>
                                <td class="text-right">{{number_format($item->pbk_aktivitas)}}</td>
                                <td class="text-right">{{number_format($item->pbk_skp)}}</td>
                                <td class="text-right">{{number_format($item->pbk_jumlah)}}</td>
                                <td class="text-right">{{number_format($item->ppk_absensi)}}</td>
                                <td class="text-right">{{number_format($item->ppk_aktivitas)}}</td>
                                <td class="text-right">{{number_format($item->ppk_skp)}}</td>
                                <td class="text-right">{{number_format($item->ppk_jumlah)}}</td>
                                <td class="text-right">{{number_format($item->pkk)}}</td>
                                <td class="text-right">{{number_format($item->pkk_jumlah)}}</td>
                                <td class="text-right">{{number_format($item->pkp)}}</td>
                                <td class="text-right">{{number_format($item->pkp_jumlah)}}</td>
                                <td class="text-right">{{number_format($item->jumlah_pembayaran)}}</td>
                                <td class="text-right">{{number_format($item->pph21)}}</td>
                                <td class="text-right">{{number_format($item->bpjs1)}}<br/>
                                <button type="button" class=" btn btn-xs editbpjs" data-id="{{$item->id}}"
                                        data-nama="{{$item->nama}}" data-1persen="{{$item->bpjs1}}"
                                        data-4persen="{{$item->bpjs4}}"><i class="fas fa-edit"></i></button>
                                </td>
                                <td class="text-right">{{number_format($item->bpjs4)}}</td>
                                <td class="text-right">{{number_format($item->tpp_diterima)}}</td>

                                <td>
                                    <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/reguler/{{$item->id}}/delete"
                                        onclick="return confirm('Yakin Ingin Dihapus?');"><span
                                            class="badge badge-danger">Hapus</span></a>
                                </td>
                            </tr>
                        @endforeach
                        
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif; font-weight:bold; background-color:#bbfac6">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{currency($data->sum('jumlah_pembayaran'))}}</td>
                            <td>{{currency($data->sum('pph21'))}}</td>
                            <td></td>
                            <td></td>
                            <td>{{currency($data->sum('tpp_diterima'))}}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                
                <br />
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-bpjs" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="/admin/rekapitulasi/bpjs/reguler" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-gradient-success" style="padding:10px">
                    <h4 class="modal-title text-sm">BPJS</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    Nama<input type="text" id="id_nama" class="form-control" readonly><br />
                    BPJS 1% <input type="text" id="id_1persen" class="form-control" name="satu_persen"
                        onkeypress="return hanyaAngka(event)" required><br />
                    BPJS 4% <input type="text" id="id_4persen" class="form-control" name="empat_persen"
                        onkeypress="return hanyaAngka(event)" required>
                    <input type="hidden" id="id_rekap" name="id_rekap">
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-block btn-success"><i class="fas fa-paper-plane"></i>
                        Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-editjabatan" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="/admin/rekapitulasi/editjabatan/reguler" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-gradient-success" style="padding:10px">
                    <h4 class="modal-title text-sm">EDIT JABATAN DI REKAPITULASI</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    Nama<input type="text" id="j_nama" class="form-control" readonly><br />
                    Pilih Jabatan 
                    <select id="selJabatan" class="form-control form-control-sm select2 selJabatan" name="jabatan">
                          
                    </select>
                    <br />
                        <small>Note : Tidak akan mengubah jabatan yg ada</small>
                    <input type="hidden" id="j_rekap" name="j_rekap">
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-block btn-success"><i class="fas fa-paper-plane"></i>
                        Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-tambahpegawai" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/tambahpegawai/reguler" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-gradient-success" style="padding:10px">
                    <h4 class="modal-title text-sm">TAMBAH PEGAWAI DI REKAPITULASI</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    Pilih Pegawai 
                    <select id="selectPegawai" class="form-control form-control-sm select2 selectPegawai" name="pegawai">
                          
                    </select><br />
                    Pilih Jabatan 
                    <select id="selectJabatan" class="form-control form-control-sm select2 selectJabatan" name="jabatan">
                          
                    </select>
                    <br />
                        <small>Note : Menambahkan pegawai yang telah mutasi namun tpp nya di bayarkan di skpd lama</small>
                    
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-block btn-success"><i class="fas fa-paper-plane"></i>
                        Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')

<script>
    $(document).on('click', '.editbpjs', function() {
       $('#id_nama').val($(this).data('nama'));
       $('#id_rekap').val($(this).data('id'));
       $('#id_1persen').val($(this).data('1persen'));
       $('#id_4persen').val($(this).data('4persen'));
       $("#modal-bpjs").modal();
    });
</script>

<script>
    $(document).on('click', '.editjabatan', function() {
       $('#j_nama').val($(this).data('nama'));
       $('#j_rekap').val($(this).data('id'));
       $("#modal-editjabatan").modal();
    });
</script>
<script>
    $(document).on('click', '.tambahpegawai', function() {
       $("#modal-tambahpegawai").modal();
    });
</script>
<script src="/theme/plugins/select2/js/select2.full.min.js"></script>
<script>
    $(function () {
      //Initialize Select2 Elements
      $('.select2').select2()
  
      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })
    })
</script>
<script>
    function hanyaAngka(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
 
    return false;
    return true;
    }
</script>
<script>
    $(document).ready(function(){
         $("#selJabatan").select2({
            placeholder: '-Pilih-',
            ajax: { 
            url: '/admin/rekapitulasi/getJabatan',
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
                    data_array.push({id:value.id,text:value.nama+' - Kelas:'+value.kelas.nama+' (KK : '+value.persen_kondisi_kerja+')'})
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

<script>
    $(document).ready(function(){
         $("#selectPegawai").select2({
            placeholder: '-Cari NIP/Nama-',
            ajax: { 
            url: '/admin/rekapitulasi/getPegawai',
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
                    data_array.push({id:value.id,text:value.nip+' - '+value.nama})
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

<script>
    $(document).ready(function(){
         $("#selectJabatan").select2({
            placeholder: '-Cari Jabatan-',
            ajax: { 
            url: '/admin/rekapitulasi/getJabatan',
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
                    data_array.push({id:value.id,text:value.nama+' - Kelas:'+value.kelas.nama})
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