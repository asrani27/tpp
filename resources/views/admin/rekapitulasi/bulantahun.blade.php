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
                        <h3 class="widget-user-username">Riwayat TPP {{Auth::user()->skpd->nama}}</h3>
                        <h5 class="widget-user-desc">Menampilkan data riwayat tpp</h5>
                    </div>

                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar TPP Bulan
                    {{\Carbon\Carbon::createFromFormat('m',$bulan)->translatedFormat('F')}} {{$tahun}}</h3>
            </div>

            <div class="card-body p-2">

                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/excel" target="_blank"
                    class="btn btn-xs btn-danger">Export Excel</a>
                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/pdf" target="_blank"
                    class="btn btn-xs btn-danger">Export PDF</a>
                <a href="/home/admin/persen" class="btn btn-xs btn-danger">Edit Persen</a>
                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/masukkanpegawai" class="btn btn-xs btn-primary"
                    onclick="return confirm('Yakin Ingin Memasukkan Semua Pegawai Pada Bulan Ini?');">Masukkan
                    Semua Pegawai</a>

                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/perhitungan" class="btn btn-xs btn-warning"
                    onclick="return confirm('Proses ini memakan beberapa waktu, harap di tunggu?');">Perhitungan</a>
                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/pembayaran" class="btn btn-xs btn-success"
                    onclick="return confirm('Proses ini memakan beberapa waktu, harap di tunggu?');">Pembayaran</a>

                {{-- <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/updatejabatan" class="btn btn-xs btn-primary"
                    onclick="return confirm('Update Jabatan Pegawai?');">Update Jabatan</a>
                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/hitungpersen" class="btn btn-xs btn-primary"
                    onclick="return confirm('Proses ini memakan beberapa waktu, harap di tunggu?');">
                    Hitung Persen</a>

                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/totalpagu" class="btn btn-xs btn-primary"
                    onclick="return confirm('Proses ini memakan beberapa waktu, harap di tunggu?');">Hitung Total
                    Pagu</a>

                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/presensi" class="btn btn-xs btn-primary"
                    onclick="return confirm('Proses ini memakan beberapa waktu, harap di tunggu?');">Tarik & Hitung
                    Presensi</a>

                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/aktivitas" class="btn btn-xs btn-primary"
                    onclick="return confirm('Proses ini memakan beberapa waktu, harap di tunggu?');">Hitung Cuti &
                    Aktivitas</a>

                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/pph21" class="btn btn-xs btn-primary"
                    onclick="return confirm('Proses ini memakan beberapa waktu, harap di tunggu?');">Hitung
                    PPH 21</a> --}}
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
                            <th style="border:1px solid silver" colspan=6 class="bg-warning">Perhitungan</th>
                            <th colspan=9 style="border:1px solid silver" class="bg-success">Pembayaran</th>
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
                            <th rowspan=3 style="border:1px solid silver" class="bg-warning">Kondisi Kerja</th>
                            <th rowspan=3 style="border:1px solid silver" class="bg-warning">Pagu TPP ASN</th>
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
                                Jumlah Pembayaran</th>
                        </tr>
                        <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif" class="text-center">
                            <th style="border:1px solid silver" class="bg-warning">Disiplin</th>
                            <th style="border:1px solid silver" class="bg-warning">Produktivitas</th>
                            <th style="border:1px solid silver" class="bg-info">Disiplin</th>
                            <th style="border:1px solid silver" class="bg-info">Produktivitas</th>
                            <th style="background-color:bisque; border:1px solid silver">Disiplin</th>
                            <th style="background-color:bisque; border:1px solid silver">Produktivitas</th>
                        </tr>
                        <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif" class="text-center">
                            <th style="border:1px solid silver" class="bg-warning">40 %</th>
                            <th style="border:1px solid silver" class="bg-warning">60 %</th>
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
                                {!!wordwrap($item->jabatan,50,"<br>")!!}
                                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/editjabatan/{{$item->id}}"><i
                                        class="fas fa-edit"></i></a>
                            </td>
                            <td class="text-center">
                                {{$item->jenis_jabatan}}
                            </td>
                            <td class="text-center">
                                {{$item->kelas}}
                            </td>
                            <td class="text-right">
                                {{currency($item->perhitungan_basic_tpp)}}
                            </td>
                            <td class="text-right">
                                {{currency($item->perhitungan_pagu)}}<br />
                                {{$item->persenJabatan->persentase_tpp}} %
                            </td>
                            <td class="text-right">
                                {{currency($item->perhitungan_disiplin)}}
                            </td>
                            <td class="text-right">
                                {{currency($item->perhitungan_produktivitas)}}
                            </td>
                            <td class="text-center">
                                {{currency($item->perhitungan_kondisi_kerja)}} <br />
                                {{$item->persenJabatan->tambahan_persen_tpp}} %
                            </td>
                            <td class="text-right">
                                {{currency($item->perhitungan_pagu_tpp_asn)}}
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
                            <td>{{currency($item->pembayaran_prestasi_kerja)}}</td>
                            <td>{{currency($item->pembayaran_kondisi_kerja)}}</td>
                            <td>{{currency($item->pembayaran_kondisi_kerja)}}</td>
                            <td>{{currency($item->pembayaran)}}</td>
                            <td class="text-right">
                                {{$item->pph21}} % <br>
                                {{currency($item->total_pph21)}}
                            </td>
                            <td class="text-right">
                                {{currency($item->total_absensi + $item->total_aktivitas - $item->total_pph21)}}
                            </td>
                            <td>
                                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/{{$item->id}}/delete"
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
                            <td>{{currency($data->sum('total_absensi') + $data->sum('total_aktivitas'))}}</td>
                            <td>{{currency($data->sum('total_pph21'))}}</td>
                            <td>{{currency($data->sum('total_absensi') + $data->sum('total_aktivitas') -
                                $data->sum('total_pph21'))}}</td>
                        </tr>
                    </tbody>
                </table><br />
                Isi NIP dan Jabatan Lama di bawah ini, Jika Yang bersangkutan sudah pindah/promosi ke skpd lain dan yang
                membayarkan SKPD lama
                <form method="post" action="/admin/rekapitulasi/tambahpegawai">
                    @csrf
                    <input type="text" name="nip" class="form-control-sm" placeholder="nip" required>
                    <select name="jabatan" class="form-control-sm select2" required>
                        <option value="">-Pilih Kelas | jabatan (Sebelum Pindah)-</option>
                        @foreach (jabatan(Auth::user()->skpd->id) as $item)
                        <option value="{{$item->id}}">{{$item->kelas->nama}} | {{$item->nama}}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="bulan" value="{{$bulan}}" class="form-control-sm" placeholder="bulan">
                    <input type="hidden" name="tahun" value="{{$tahun}}" class="form-control-sm" placeholder="tahun">
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')

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
@endpush