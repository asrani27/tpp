@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
SUPERADMIN
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card card-widget widget-user-2">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-info">
                        <div class="widget-user-image">
                            <img class="img-circle elevation-2" src="/login_tpp/images/icons/logo.png"
                                alt="User Avatar">
                        </div>
                        <!-- /.widget-user-image -->
                        <h3 class="widget-user-username">Laporan TPP PLT {{Auth::user()->skpd->nama}}</h3>
                        <h5 class="widget-user-desc">Menampilkan data Laporan TPP PLT</h5>
                    </div>

                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar TPP Bulan
                    {{convertBulan($bulan)}} {{$tahun}}</h3>
            </div>
            <div class="card-body p-2">

                <div class="alert alert-default alert-dismissible text-sm">
                    <h5><i class="icon fas fa-info"></i> Informasi</h5>
                    Format Laporan TPP PLT, langkah-langkahnya adalah<br />
                    1. Klik tambah Data PLT<br />

                    2. Melakukan perhitungan dengan mengklik tombol <span class="badge badge-warning">perhitungan</span>
                    akan menghitung di kolom perhitungan<br />

                    3. Hitung pembayaran dengan mengklik tombol <span class="badge badge-success">pembayaran</span>
                    akan menghitung di kolom pembayaran<br />

                </div>
                <a href="/admin/rekapitulasi/plt/{{$bulan}}/{{$tahun}}/excel" target="_blank"
                    class="btn btn-xs btn-primary">Export Excel</a>

                <a href="/admin/rekapitulasi/plt/{{$bulan}}/{{$tahun}}/create" class="btn btn-xs btn-primary">Tambah
                    Data PLT</a>

                <a href="/admin/rekapitulasi/plt/{{$bulan}}/{{$tahun}}/perhitungan" class="btn btn-xs btn-warning"
                    onclick="return confirm('Proses ini memakan beberapa waktu, harap di tunggu?');">Perhitungan</a>
                <a href="/admin/rekapitulasi/plt/{{$bulan}}/{{$tahun}}/pembayaran" class="btn btn-xs btn-success"
                    onclick="return confirm('Proses ini memakan beberapa waktu, harap di tunggu?');">Pembayaran</a>

                {{-- <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/bpjs" class="btn btn-xs btn-info">BPJS</a>
                --}}
                <br /><br />

                <table class="table table-hover table-striped text-nowrap table-sm table-responsive ">
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
                            <th style="border:1px solid silver" colspan=7 class="bg-warning">Perhitungan</th>
                            <th colspan=16 style="border:1px solid silver" class="bg-success">Pembayaran</th>
                            <th rowspan=4 style="border:1px solid silver" class="bg-info">PPH 21</th>
                            <th rowspan=4 style="border:1px solid silver" class="bg-info">BPJS 1%</th>
                            <th rowspan=4 style="border:1px solid silver" class="bg-info">BPJS 4%</th>
                            <th rowspan=4 style="border:1px solid silver" class="bg-info">TPP Diterima <br /> Transfer
                            </th>
                            <th rowspan=4 style="border:1px solid silver">Aksi</th>
                        </tr>
                        <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif" class="text-center">
                            <th rowspan=3 style="border:1px solid silver" class="bg-warning">Basic<br /> TPP</th>
                            <th rowspan=3 style="border:1px solid silver" class="bg-warning">Pagu Disiplin<br />
                                Kerja dan<br /> Produktivitas</th>
                            <th colspan=2 style="border:1px solid silver" class="bg-warning">Penilaian TPP</th>
                            <th rowspan=3 style="border:1px solid silver" class="bg-warning">Kondisi <br /> Kerja</th>
                            <th rowspan=3 style="border:1px solid silver" class="bg-warning">Kelangkaan <br />Profesi
                            </th>
                            <th rowspan=3 style="border:1px solid silver" class="bg-warning">Pagu <br />TPP<br /> ASN
                            </th>
                            <th class="bg-success" style="border:1px solid silver" colspan=6>Menit Aktivitas + Menit
                                Cuti</th>
                            <th class="bg-success" style="border:1px solid silver" rowspan=3>Total<br /> Menit</th>
                            <th class="bg-info" style="border:1px solid silver" colspan=2>Beban Kerja</th>
                            <th rowspan=3 class="bg-info" style="border:1px solid silver">Jumlah Beban Kerja
                                <br />5.1.01.02.01.0001
                            </th>
                            <th colspan=2 style="background-color:bisque; border:1px solid silver" colspan=2>
                                Prestasi Kerja</th>
                            <th rowspan=3 style="background-color:bisque; border:1px solid silver">Jumlah Prestasi Kerja
                                <br />5.1.01.02.05.0001
                            </th>
                            <th rowspan=3 style="border:1px solid silver" class='bg-secondary'>
                                Kondisi Kerja</th>
                            <th rowspan=3 style="border:1px solid silver" class='bg-secondary'>
                                Jumlah Kondisi Kerja <br /> 5.1.01.02.03.0001</th>
                            <th rowspan=3 style="border:1px solid silver" class='bg-success'>
                                Jumlah Pembayaran <br />
                                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/excelpagu"><i
                                        class="icon fas fa-download"></i> Download</a>
                            </th>
                        </tr>
                        <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif" class="text-center">
                            <th style="border:1px solid silver" class="bg-warning">Disiplin</th>
                            <th style="border:1px solid silver" class="bg-warning">Produktivitas</th>
                            <th style="border:1px solid silver" class="bg-success">Aktivitas</th>
                            <th style="border:1px solid silver" class="bg-success">Cuti Tahunan</th>
                            <th style="border:1px solid silver" class="bg-success">Tugas Luar</th>
                            <th style="border:1px solid silver" class="bg-success">Covid</th>
                            <th style="border:1px solid silver" class="bg-success">Diklat</th>
                            <th style="border:1px solid silver" class="bg-success">Cuti Bersama</th>
                            <th style="border:1px solid silver" class="bg-info">Disiplin</th>
                            <th style="border:1px solid silver" class="bg-info">Produktivitas</th>
                            <th style="background-color:bisque; border:1px solid silver">Disiplin</th>
                            <th style="background-color:bisque; border:1px solid silver">Produktivitas</th>
                        </tr>
                        <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif" class="text-center">
                            <th style="border:1px solid silver" class="bg-warning">40 %</th>
                            <th style="border:1px solid silver" class="bg-warning">60 %</th>
                            <th style="border:1px solid silver" class="bg-success">menit</th>
                            <th style="border:1px solid silver" class="bg-success">@420</th>
                            <th style="border:1px solid silver" class="bg-success">@420</th>
                            <th style="border:1px solid silver" class="bg-success">@360</th>
                            <th style="border:1px solid silver" class="bg-success">@420</th>
                            <th style="border:1px solid silver" class="bg-success">@420</th>
                            <th style="border:1px solid silver" class="bg-info">40 %</th>
                            <th style="border:1px solid silver" class="bg-info">60 %</th>
                            <th style="background-color:bisque; border:1px solid silver">40 %</th>
                            <th style="background-color:bisque; border:1px solid silver">60 %</th>
                        </tr>
                    </thead>
                    @php
                    $no =1;
                    @endphp
                    <tbody>
                        @foreach ($data as $key => $item)
                        <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif">
                            <td>{{$no++}}</td>
                            <td>
                                {{$item->nama}} <br />
                                {{$item->pangkat}} {{$item->golongan}} <br />
                                NIP.{{$item->nip}}
                            </td>
                            <td class="text-center">
                                {!!wordwrap($item->jabatan,50,"<br>")!!}<br /><br />
                                (PLT) {!!wordwrap($item->plt->jabatan,50,"<br>")!!}
                            </td>
                            <td class="text-center">
                                {{$item->jenis_jabatan}}<br /><br />
                                {{$item->plt->jenis_jabatan}}
                            </td>
                            <td class="text-center">
                                {{$item->kelas}}<br /><br />
                                {{$item->plt->kelas}}
                            </td>
                            <td class="text-right">
                                {{currency($item->perhitungan_basic_tpp)}}
                            </td>
                            <td class="text-right">
                                {{currency($item->perhitungan_pagu)}}<br />
                                {{$item->persenJabatan == null ? 0 : $item->persenJabatan->persen_prestasi_kerja +
                                $item->persenJabatan->persen_beban_kerja +
                                $item->persenJabatan->persen_tambahan_beban_kerja }} %
                            </td>
                            <td class="text-right">
                                {{currency($item->perhitungan_disiplin)}}
                            </td>
                            <td class="text-right">
                                {{currency($item->perhitungan_produktivitas)}}
                            </td>
                            <td class="text-center">
                                {{currency($item->perhitungan_kondisi_kerja)}} <br />
                                {{$item->persenJabatan == null ? 0 : $item->persenJabatan->persen_kondisi_kerja}} %
                            </td>
                            <td class="text-right">
                                {{currency($item->perhitungan_kelangkaan_profesi)}} <br />
                                {{$item->persenjabatan == null ? 0 : $item->persenJabatan->persen_kelangkaan_profesi}} %
                            </td>
                            <td class="text-right">
                                {{currency($item->perhitungan_pagu_tpp_asn)}} <br />
                                {{($item->persenjabatan == null ? 0 : $item->persenJabatan->persentase_tpp)
                                +($item->persenjabatan == null ? 0 : $item->persenJabatan->persen_kondisi_kerja)
                                +($item->persenjabatan == null ? 0 : $item->persenJabatan->persen_kelangkaan_profesi)}}
                                %
                            </td>
                            <td class="text-right">
                                {{$item->pembayaran_at}}
                            </td>
                            <td class="text-right">
                                {{$item->pembayaran_cutitahunan}}
                            </td>
                            <td class="text-right">
                                {{$item->pembayaran_tugasluar}}
                            </td>
                            <td class="text-right">
                                {{$item->pembayaran_covid}}
                            </td>
                            <td class="text-right">
                                {{$item->pembayaran_diklat}}
                            </td>
                            <td class="text-right">
                                {{$item->pembayaran_cuti_bersama}}
                            </td>
                            <td class="text-right">
                                {{$item->pembayaran_aktivitas}}
                            </td>
                            <td class="text-right">
                                {{currency($item->pembayaran_bk_disiplin)}}<br />
                                {{$item->pembayaran_absensi}} %
                            </td>
                            <td class="text-right">
                                {{currency($item->pembayaran_bk_produktivitas)}}<br />
                                {{$item->pembayaran_aktivitas}} Menit
                            </td>
                            <td>{{currency($item->pembayaran_beban_kerja)}}</td>
                            <td class="text-right">
                                {{currency($item->pembayaran_pk_disiplin)}}<br />
                                {{$item->pembayaran_absensi}} %
                            </td>

                            <td class="text-right">
                                {{currency($item->pembayaran_pk_produktivitas)}}<br />
                                {{$item->pembayaran_aktivitas}} Menit
                            </td>
                            <td class="text-right">{{currency($item->pembayaran_prestasi_kerja)}}</td>
                            <td class="text-right">{{currency($item->pembayaran_kondisi_kerja)}}</td>
                            <td class="text-right">{{currency($item->pembayaran_kondisi_kerja)}}</td>
                            <td class="text-right">{{currency($item->pembayaran)}}</td>
                            <td class="text-right">
                                {{currency($item->potongan_pph21)}} <br />
                                {{$item->potonganPPH21->pph}} %
                            </td>
                            <td class="text-right">
                                {{currency($item->potongan_bpjs_1persen)}}<br />
                                <button type="button" class=" btn btn-xs editbpjs" data-id="{{$item->id}}"
                                    data-nama="{{$item->nama}}" data-1persen="{{$item->potongan_bpjs_1persen}}"
                                    data-4persen="{{$item->potongan_bpjs_4persen}}"><i class="fas fa-edit"></i></button>
                            </td>
                            <td class="text-right">
                                {{currency($item->potongan_bpjs_4persen)}}
                            </td>
                            <td class="text-right">
                                {{currency($item->tpp_diterima)}}
                            </td>
                            <td>
                                <a href="/admin/rekapitulasi/plt/{{$bulan}}/{{$tahun}}/{{$item->id}}/delete"
                                    onclick="return confirm('Yakin Ingin Dihapus?');"><span
                                        class="badge badge-danger">Hapus</span></a>
                            </td>
                        </tr>
                        @endforeach
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif; font-weight:bold">
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
                            <td>{{currency($data->sum('tpp_diterima'))}}</td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-bpjs" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="/admin/rekapitulasi/bpjs/" enctype="multipart/form-data">
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
@endpush