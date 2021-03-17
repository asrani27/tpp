@extends('layouts.app')

@push('css')
    
@endpush
@section('title')
    BERANDA
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-6">Bulan
                <select name="bulan" class="form-control">
                    <option value="">Januari</option>
                    <option value="">Februari</option>
                    <option value="">Maret</option>
                    <option value="">April</option>
                    <option value="">Mei</option>
                    <option value="">Juni</option>
                    <option value="">Juli</option>
                    <option value="">Agustus</option>
                    <option value="">September</option>
                    <option value="">Oktober</option>
                    <option value="">November</option>
                    <option value="">Desember</option>
                </select>
            </div>
            <div class="col-6">
                Tahun
                <select name="tahun" class="form-control">
                    <option value="">2021</option>
                    <option value="">2022</option>
                    <option value="">2023</option>
                </select>
            </div>            
        </div>
        <br />
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card card-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-info">
                    <h3 class="widget-user-username">TPP Agung Saptoto M.Kom</h3>
                    <h5 class="widget-user-desc">Rp. 5.670.341,-</h5>
                    </div>
                    <div class="widget-user-image">
                    <img class="img-circle elevation-2" src="/theme/dist/img/user1-128x128.jpg" alt="User Avatar">
                    </div>
                    <div class="card-footer">
                    <div class="row">
                        <div class="col-4 border-right">
                        <div class="description-block">
                            <h5 class="description-header">3,200</h5>
                            <span class="description-text">DISETUJUI</span>
                        </div>
                        <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-4 border-right">
                        <div class="description-block">
                            <h5 class="description-header">13,000</h5>
                            <span class="description-text">DITOLAK</span>
                        </div>
                        <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                        <div class="description-block">
                            <h5 class="description-header">35</h5>
                            <span class="description-text">BELUM ACC</span>
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
            <div class="col-12">Beban Aktivitas
                
                <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    var donutData        = {
      labels: [
          'Aktivitas ACC', 
          'Aktivitas Belum ACC',
          'Aktivitas Tolak',
      ],
      datasets: [
        {
          data: [700,500,400],
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