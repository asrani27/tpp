@extends('layouts.app')

@push('css')

@endpush
@section('title')
<strong>TPP Pegawai</strong>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">

    @if ($data->pangkat_id == null)
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h5><i class="icon fas fa-ban"></i> Alert!</h5>
      Pangkat Belum di isi!, hubungi admin SKPD anda.
    </div>
    @endif

    @if ($data->telp == null || $data->npwp == null || $data->no_rek == null || $data->gol_darah == null || $data->jkel
    == null || $data->jenjang_pendidikan == null)
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h5><i class="icon fas fa-ban"></i> Alert!</h5>
      Harap Lengkapi Form Di Bawah Ini:
    </div>
    <div class="card card-info">
      <!-- /.card-header -->
      <!-- form start -->
      <form class="form-horizontal" method="POST" action="/pegawai/profil/bio">
        @csrf
        <div class="card-body">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label">Telp</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="telp" value="{{$data->telp}}" placeholder="Telp" required
                onkeypress="return event.charCode >= 48 && event.charCode <=57" maxlength="16">
            </div>
          </div>
          <div class="form-group row">
            <label for="inputPassword3" class="col-sm-2 col-form-label">NPWP</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="npwp" placeholder="NPWP" required
                onkeypress="return event.charCode >= 48 && event.charCode <=57" maxlength="20">
            </div>
          </div>
          <div class="form-group row">
            <label for="inputPassword3" class="col-sm-2 col-form-label">No Rek Bank Kalsel</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="no_rek" placeholder="No Rek" required
                onkeypress="return event.charCode >= 48 && event.charCode <=57" maxlength="20">
            </div>
          </div>
          <div class="form-group row">
            <label for="inputPassword3" class="col-sm-2 col-form-label">Gol Darah</label>
            <div class="col-sm-10">
              <select name="gol_darah" class="form-control">
                <option value="">-pilih-</option>
                <option value='A' {{$data->gol_darah == 'A' ? 'selected':''}}>A</option>
                <option value='B' {{$data->gol_darah == 'B' ? 'selected':''}}>B</option>
                <option value='AB' {{$data->gol_darah == 'AB' ? 'selected':''}}>AB</option>
                <option value='O' {{$data->gol_darah == 'O' ? 'selected':''}}>O</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputPassword3" class="col-sm-2 col-form-label">Jenis Kelamin</label>
            <div class="col-sm-10">
              <select name="jkel" class="form-control">
                <option value="">-pilih-</option>
                <option value='L' {{$data->jkel == 'L' ? 'selected':''}}>Laki Laki</option>
                <option value='P' {{$data->jkel == 'P' ? 'selected':''}}>Perempuan</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputPassword3" class="col-sm-2 col-form-label">Jenjang Pendidikan</label>
            <div class="col-sm-10">
              <select name="jenjang_pendidikan" class="form-control">
                <option value="">-pilih-</option>
                <option value='SMA' {{$data->jenjang_pendidikan == 'SMA' ? 'selected':''}}>SMA</option>
                <option value='D3' {{$data->jenjang_pendidikan == 'D3' ? 'selected':''}}>D3</option>
                <option value='S1' {{$data->jenjang_pendidikan == 'S1' ? 'selected':''}}>S1</option>
                <option value='S2' {{$data->jenjang_pendidikan == 'S2' ? 'selected':''}}>S2</option>
                <option value='S3' {{$data->jenjang_pendidikan == 'S3' ? 'selected':''}}>S3</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 col-form-label"></label>
            <div class="col-sm-10">
              <button type="submit" class="btn btn-block btn-info">SIMPAN</button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <!-- /.card-footer -->
    @endif
    <div class="row">
      <div class="col-lg-12 col-12">
        <div class="card card-widget widget-user">
          <!-- Add the bg color to the header using any of the bg-* classes -->
          <div class="widget-user-header bg-gradient-primary">
            <h3 class="widget-user-username">{{$data->nama}}</h3>
            <h5 class="widget-user-desc">Rp. {{currency(($data->total_tpp - $data->pph_angka))}},-</h5>
          </div>
          <div class="widget-user-image">
            <img class="img-circle elevation-2"
              src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/User_icon_2.svg/2048px-User_icon_2.svg.png"
              alt="User Avatar">
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-4 border-right">
                <div class="description-block">
                  <h5 class="description-header">{{$acc}}</h5>
                  <span class="description-text"><i class="fas fa-check-circle"></i> DISETUJUI</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-4 border-right">
                <div class="description-block">
                  <h5 class="description-header">{{$tolak}}</h5>
                  <span class="description-text"><i class="fas fa-times-circle"></i> DITOLAK</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-4">
                <div class="description-block">
                  <h5 class="description-header">{{$pending}}</h5>
                  <span class="description-text"><i class="fas fa-list-alt"></i> DIPROSES</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 col-12">
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h5><i class="icon fas fa-info"></i> Informasi!</h5>
          Tampilan baru yang lebih informatif tentang riwayat TPP Pegawai.
          Jika masih 0, maka admin belum melakukan rekap laporan bulan tersebut.
        </div>
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar Riwayat TPP</h3>
          </div>

          <div class="card-body table-responsive p-2">
            <table class="table table-hover text-nowrap table-sm">
              <thead>
                <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif"
                  class="text-center bg-gradient-primary">
                  <th rowspan=2 style="width: 10px">#</th>
                  <th rowspan=2>Bulan<br />Tahun</th>
                  <th rowspan=2>Nama <br />NIP<br />Pangkat<br />Golongan</th>
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
                <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif"
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
                @foreach ($riwayatTpp as $key => $item)
                <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                  <td>{{$no++}}</td>
                  <td>{{\Carbon\Carbon::createFromFormat('m',$item->bulan)->translatedFormat('F')}} <br />
                    {{$item->tahun}}</td>
                  <td>
                    {{$item->nama}} <br />
                    {{$item->pangkat}} {{$item->golongan}} <br />
                    NIP.{{$item->nip}}
                  </td>

                  <td class="text-center">
                    {!!wordwrap($item->jabatan,50,"<br>")!!}
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
                    <a href="/admin/rekapitulasi/{{$item->bulan}}/{{$item->tahun}}/{{$item->id}}/delete"
                      onclick="return confirm('Yakin Ingin Dihapus?');"><span
                        class="badge badge-primary">Hitung</span></a>
                  </td>
                </tr>
                @endforeach

              </tbody>
            </table>
          </div>
        </div>
        {{-- <div class="row">
          <div class="col-lg-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-success"><i class="far fa-clock"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Jumlah Menit Bekerja</span>
                <span class="info-box-number">{{$jmlmenit}} / 6750 Menit</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-lg-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-warning"><i class="far fa-calendar-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Presensi</span>
                <span class="info-box-number">{{$data->persen_disiplin}} %</span>

              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </div> --}}

        {{-- <canvas id="donutChart"
          style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> --}}

      </div>
      {{-- <div class="col-lg-6 col-12">
        <div class="card">
          <div class="card-header border-transparent bg-gradient-primary">
            <h3 class="card-title">Detail Perhitungan TPP Bulan </h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool">
                <i class="fas fa-print"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table m-0 table-sm">
                {{'sad'}}
                <tbody>
                  <tr>
                    <td class="text-sm" width="120px">Kelas Jabatan</td>
                    <td class="text-right">{{$data->nama_kelas}}</td>
                  </tr>
                  <tr>
                    <td class="text-sm" width="120px">Basic TPP</td>
                    <td class="text-right">{{currency($data->basic_tpp)}}</td>
                  </tr>
                  <tr>
                    <td class="text-sm" width="120px">Persentase TPP</td>
                    <td class="text-right">{{$data->persentase_tpp}} %</td>
                  </tr>
                  <tr>
                    <td class="text-sm" width="170px">Tambahan Persen TPP</td>
                    <td class="text-right">{{$data->tambahan_persen_tpp}} %</td>
                  </tr>
                  <tr>
                    <td class="text-sm" width="170px">Jumlah Persentase</td>
                    <td class="text-right">{{$data->jumlah_persentase}} %</td>
                  </tr>
                  <tr>
                    <td class="text-sm" width="170px">Total Pagu</td>
                    <td class="text-right">{{currency($data->total_pagu)}}</td>
                  </tr>
                  <tr>
                    <td class="text-sm" width="250px">Disiplin 40% dari Total Pagu (Jika Presensi 100%)</td>
                    <td class="text-right">{{currency($data->total_disiplin)}}</td>
                  </tr>
                  <tr>
                    <td class="text-sm" width="170px">Produktivitas 60% dari Total Pagu (Jika Aktivitas Mencapai 6750
                      Menit)</td>
                    <td class="text-right">{{currency($data->total_produktivitas)}}</td>
                  </tr>
                  <tr>
                    <td class="text-sm" width="170px">Total TPP Bruto</td>
                    <td class="text-right">{{currency($data->total_tpp)}}</td>
                  </tr>
                  <tr>
                    <td class="text-sm" width="170px">PPH 21 {{$data->pph}} %</td>
                    <td class="text-right">{{currency($data->pph_angka)}}</td>
                  </tr>
                  <tr>
                    <td class="text-sm" width="170px">BPJS</td>
                    <td class="text-right">0</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- /.table-responsive -->
          </div>
          <!-- /.card-body -->
          <div class="card-footer clearfix">
            <strong>TOTAL TPP DITERIMA</strong>
            <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">Rp. {{currency(($data->total_tpp -
              $data->pph_angka))}}</a>
          </div>
          <!-- /.card-footer -->
        </div>
      </div> --}}
    </div>
  </div>
</div>
@endsection

@push('js')
<!-- ChartJS -->
<script src="/theme/plugins/chart.js/Chart.min.js"></script>

<script>
  $(function () {
//-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    tolak = {!!json_decode($tolak)!!}
    acc = {!!json_decode($acc)!!}
    proses = {!!json_decode($pending)!!}
    console.log(tolak, acc,proses);
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    var donutData        = {
      labels: [
          'Aktivitas Tolak', 
          'Aktivitas ACC',
          'Aktivitas Belum ACC',
      ],
      datasets: [
        {
          data: [tolak,acc, proses],
          backgroundColor : ['#f56954', '#00a65a', '#f39c12'],
        }
      ]
    }
    var donutOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var donutChart = new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions      
    })
    });
</script>
@endpush