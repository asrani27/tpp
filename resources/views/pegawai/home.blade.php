@extends('layouts.app')

@push('css')

@endpush
@section('title')
<strong>TPP Pegawai</strong>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="row">
      <div class="col-lg-12 col-12">
        <div class="card card-widget widget-user">
          <!-- Add the bg color to the header using any of the bg-* classes -->
          <div class="widget-user-header bg-info">
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

      <div class="col-lg-6 col-12">

        <div class="row">
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
        </div>

        <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>

      </div>
      <div class="col-lg-6 col-12">
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
      </div>
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