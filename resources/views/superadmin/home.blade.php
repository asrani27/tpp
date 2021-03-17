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
        <h4>Dashboard</h4>
        <div class="row">
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>32</h3>
  
                  <p>SKPD</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-purple">
                <div class="inner">
                  <h3>6000</h3>
  
                  <p>ASN</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="#" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-6 col-12">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>Rp. 6.342.123.456,-</h3>
  
                  <p>Total TPP Bulan Juni 2021</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    
                    <div class="card-body">
                        Grafik TPP Kota Banjarmasin Tahun 2021
                        
                        <div class="chart">
                            <canvas id="stackedBarChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-12">
                <div class="card">
                    <div class="card-body">
                        
                        <div class="form-group">
                            <select class="form-control select2" style="width: 100%;">
                            <option selected="selected">Top Level</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="jabatan" placeholder="nama jabatan">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-12">
                
            <ul>
                <li>
                    <div class="callout callout-info text-sm" style="padding:5px;">
                        <strong>Kepala Dinas Komunikasi Dan Informatika</strong> 
                            <a href="#" class="btn btn-tool" data-tooltip="remove"><i class="fas fa-edit"></i></a>
                            <a href="#" class="btn btn-tool" data-tooltip="remove"><i class="fas fa-times"></i></a>
                            
                    </div>
                    
                    <ul>
                        <li>
                            <div class="callout callout-warning text-sm" style="padding:5px;">
                                <strong>Sekretaris</strong>
                            </div>
                        </li>
                        <li>
                            <div class="callout callout-warning text-sm" style="padding:5px;">
                                <strong>Kabid 1</strong>
                            </div>
                            <ul>
                                <li>
                                    <div class="callout callout-danger text-sm" style="padding:5px;">
                                        <strong>Kasi 1</strong>
                                    </div>
                                </li>
                                <li>
                                    <div class="callout callout-danger text-sm" style="padding:5px;">
                                        <strong>Kasi 2</strong>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <div class="callout callout-warning text-sm" style="padding:5px;">
                                <strong>Kabid 2</strong>
                            </div>
                        </li>
                    </ul>
                </li>
                
            </ul>
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
  //---------------------
    //- STACKED BAR CHART -
    //---------------------
    var barChartData = {
      labels  : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sept', 'Okt', 'Nov', 'Des'],
      datasets: [
        {
          label               : 'TPP',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [4000000, 5000000, 4500000, 3900000, 5100000, 4300000, 5000000]
        }
      ]
    }
    var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
    var stackedBarChartData = jQuery.extend(true, {}, barChartData)

    var stackedBarChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      tooltips: { 
           callbacks: { 
               label: function(tooltipItem, data) { 
                   return tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); 
                }, 
            }, 
        },
      
      scales: {
        xAxes: [{
          stacked: true,
        }],
        yAxes: [{
          stacked: true,
          ticks: {
                    callback: function(label, index, labels) {
                        return label/1000000+'JT';
                    }
                },   
            scaleLabel: {
                display: true,
                labelString: '1JT = 1.000.000'
            }
        }]
      }
    }

    var stackedBarChart = new Chart(stackedBarChartCanvas, {
      type: 'bar', 
      data: stackedBarChartData,
      options: stackedBarChartOptions
    })
    });
</script>
<!-- Select2 -->
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