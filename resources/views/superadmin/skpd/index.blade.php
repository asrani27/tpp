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
        <h4>SKPD PEMERINTAH KOTA BANJARMASIN</h4>
        <div class="row">
            <div class="col-12">
              <a href="/superadmin/skpd/add" class="btn btn-sm btn-primary"><i class="fas fa-university"></i> Tambah SKPD</a>
              <a href="/superadmin/skpd/createuser" class="btn btn-sm bg-purple" onclick="return confirm('Yakin Mengcreate User Dan Pass?');"><i class="fas fa-key"></i> Create User & Pass SKPD</a>
              <a href="/superadmin/skpd/deleteuser" class="btn btn-sm btn-danger" onclick="return confirm('Akan menghapus Semua User AdminSKPD, Yakin?');"><i class="fas fa-trash"></i> Delete User & Pass SKPD</a> <br/><br/>
              <div class="card">
                {{-- <div class="card-header">
                  <h3 class="card-title">Responsive Hover Table</h3>
  
                  <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                      <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
  
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div> --}}
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th></th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    @php
                        $no =1;
                    @endphp
                    <tbody>
                      @foreach (skpd() as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td><img src="/login_tpp/images/icons/logo.png" width="40px"></td>
                            <td><strong>{{$item->kode_skpd}}</strong>
                              <br/>
                              @if ($item->user_id == null)
                              <a href="/superadmin/skpd/createuser/{{$item->id}}" class="btn btn-sm btn-secondary"><i class="fas fa-key"></i> Create User</a>
                              @else
                              <a href="/superadmin/skpd/resetpassword/{{$item->id}}" class="btn btn-sm btn-secondary" onclick="return confirm('Yakin ingin reset Password?');"><i class="fas fa-key"></i> Reset Password</a>
                                  
                              @endif
                            </td>
                            <td>
                            <strong>{{$item->nama}}</strong><br/>
                            <a href="/superadmin/skpd/pegawai/{{$item->id}}" class="btn btn-sm btn-info"><i class="fas fa-users"></i> ASN : {{$item->pegawai->count()}}</a>
                            <a href="/superadmin/skpd/tpp" class="btn btn-sm btn-success"><i class="fas fa-money-bill"></i> TPP JANUARI 2021: Rp. 45.000.000.,</a>
                            <a href="/superadmin/skpd/jabatan/{{$item->id}}" class="btn btn-sm bg-purple"><i class="fas fa-university"></i> PETA JABATAN : {{$item->jabatan->count()}}</a>
                            <a href="/superadmin/skpd/kelas/{{$item->id}}" class="btn btn-sm bg-primary"><i class="fas fa-th"></i> KELAS : {{$item->jabatan->count()}}</a>
                            </td>
                            <td>
                              
                            <a href="/superadmin/skpd/edit/{{$item->id}}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <a href="/superadmin/skpd/delete/{{$item->id}}" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin di hapus?');"><i class="fas fa-trash"></i></a>
                            </td>
                          </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
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