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
              <img class="img-circle elevation-2" src="/login_tpp/images/icons/logo.png" alt="User Avatar">
            </div>
            <!-- /.widget-user-image -->
            <h3 class="widget-user-username">Riwayat TPP {{Auth::user()->skpd->nama}}</h3>
            <h5 class="widget-user-desc">Menampilkan data riwayat tpp PLT</h5>
          </div>

        </div>
      </div>
    </div>
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Pilih Tahun Dan Bulan</h3>
      </div>
      <div class="card-body">
        <table class="table table-hover table-striped text-nowrap table-sm">
          <thead>
            <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif" class="bg-gradient-info">
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
            <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif">
              <td>{{$no++}}</td>
              <td>{{convertBulan($item->bulan)}}</td>
              <td>{{$item->tahun}}</td>
              <td><a href="/admin/rekapitulasi/plt/{{$item->bulan}}/{{$item->tahun}}" class="btn btn-xs btn-primary"><i
                    class="fas fa-eye"></i> Detail</a></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@endsection

@push('js')

@endpush