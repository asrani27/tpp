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

            <div class="card-body table-responsive p-2">

                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/excel" target="_blank"
                    class="btn btn-xs btn-danger">Export Excel</a>
                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/pdf" target="_blank"
                    class="btn btn-xs btn-danger">Export PDF</a>
                <a href="/home/admin/persen" class="btn btn-xs btn-danger">Edit Persen</a>
                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/masukkanpegawai" class="btn btn-xs btn-primary"
                    onclick="return confirm('Yakin Ingin Memasukkan Semua Pegawai Pada Bulan Ini?');">Masukkan
                    Semua Pegawai</a>
                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/updatejabatan" class="btn btn-xs btn-primary"
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
                    PPH 21</a>
                <br /><br />

                <table class="table table-hover table-striped text-nowrap table-sm">
                    <thead>
                        <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif"
                            class="text-center bg-gradient-primary">
                            <th rowspan=2 style="width: 10px">#</th>
                            <th rowspan=2>Nama /NIP/Pangkat/Golongan</th>
                            <th rowspan=2>Jabatan</th>
                            <th rowspan=2>Kelas</th>
                            <th rowspan=2>Basic TPP</th>
                            <th colspan=4>Beban Kerja</th>
                            <th colspan=2>Disiplin 40%</th>
                            <th colspan=2>Produktivitas 60%</th>
                            <th rowspan=2>TPP ASN</th>
                            <th rowspan=2>PPH 21</th>
                            <th rowspan=2>TPP DIterima</th>
                            <th rowspan=2>Aksi</th>
                        </tr>
                        <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif"
                            class="text-center bg-gradient-primary">
                            <th>Persentase<br /> TPP</th>
                            <th>Tambahan <br />Persentase<br /> TPP</th>
                            <th>Jumlah<br /> Persentase</th>
                            <th>Total<br /> Pagu</th>
                            <th>%</th>
                            <th>Rp.</th>
                            <th>menit</th>
                            <th>Rp.</th>
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
                                {{$item->kelas}}
                            </td>
                            <td class="text-right">
                                {{currency($item->basic_tpp)}}
                            </td>
                            <td class="text-center">
                                {{$item->persen}} %
                            </td>
                            <td class="text-center">
                                {{$item->tambahan_persen == null ? 0: $item->tambahan_persen}} %
                            </td>
                            <td class="text-center">
                                {{$item->jumlah_persen}} %
                            </td>
                            <td class="text-right">
                                {{currency($item->total_pagu)}}
                            </td>
                            <td>{{$item->absensi}} %</td>
                            <td class="text-right">
                                {{currency($item->total_absensi)}}
                            </td>
                            <td>{{$item->aktivitas}} m</td>
                            <td class="text-right">
                                {{currency($item->total_aktivitas)}}
                            </td>

                            <td class="text-right">
                                {{currency($item->total_absensi + $item->total_aktivitas)}}
                            </td>
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