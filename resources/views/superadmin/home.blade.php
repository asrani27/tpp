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
            <h3>{{countSkpd()}}</h3>

            <p>SKPD</p>
          </div>
          <div class="icon">
            <i class="fas fa-university"></i>
          </div>
          <a href="#" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-purple">
          <div class="inner">
            <h3>{{countPegawai()}}</h3>

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
            <h3>{{\Carbon\Carbon::now()->format('d M Y')}}</h3>

            <p>Tanggal</p>

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
            <table class="table table-hover table-striped text-nowrap table-sm">
              <thead>
                <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif" class="bg-gradient-primary">
                  <th>#</th>
                  <th>Bulan</th>
                  <th>Tahun</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              @php
              $no =1;
              @endphp
              <tbody>

                @foreach (bulanTahun() as $key => $item)
                <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                  <td>{{$no++}}</td>
                  <td>{{\Carbon\Carbon::createFromFormat('m',$item->bulan)->translatedFormat('F')}}</td>
                  <td>{{$item->tahun}}</td>
                  <td><a href="/superadmin/tpp/{{$item->bulan}}/{{$item->tahun}}" class="btn btn-xs btn-success"><i
                        class="fas fa-eye"></i> Detail</a></td>
                </tr>
                @endforeach
                {{-- <tr>
                  <td></td>
                  <td>Total Terlambat</td>
                  <td>Total Lebih awal</td>
                </tr> --}}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        Export Pegawai
        <form method="post" action="/home/superadmin/exportpegawai">

          @csrf
          <select name="skpd_id" class="form-control">
            @foreach ($dataskpd as $item)
            <option value="{{$item->id}}">{{$item->nama}}</option>
            @endforeach
          </select>
          <button type="submit" class="btn btn-primary btn-sm">Export</button>
        </form>

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

<script>
  $(function () {
//---------------------
  //- STACKED BAR CHART -
  //---------------------
  var grafikSkpd = {!!grafikSkpd()!!}
  
  var barChartData = {
    labels  : grafikSkpd,
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
  var stackedBarChartCanvas = $('#stackedBarChart2').get(0).getContext('2d')
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