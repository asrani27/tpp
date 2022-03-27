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
                                <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif"
                                    class="bg-gradient-primary">
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
                                    <td>{{\Carbon\Carbon::createFromFormat('m',$item->bulan)->translatedFormat('F')}}
                                    </td>
                                    <td>{{$item->tahun}}</td>
                                    <td><a href="/superadmin/tpp/{{$item->bulan}}/{{$item->tahun}}"
                                            class="btn btn-xs btn-success"><i class="fas fa-eye"></i> Detail</a></td>
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
<script>
    function hanyaAngka(event) {
        var angka = (event.which) ? event.which : event.keyCode
        if (angka != 46 && angka > 31 && (angka < 48 || angka > 57))
            return false;
        return true;
    }
</script>
@endpush