@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
LIST PRESENSI
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
            <h3 class="widget-user-username">{{detailSkpd(Auth::user()->skpd->id)->nama}}</h3>
            <h5 class="widget-user-desc">Berikut ini data Rekap Presensi</h5>
          </div>

        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        {{-- <a href="/admin/presensi" class="btn btn-sm btn-secondary" class="fas fa"></i> Kembali</a>
        <br /> <br /> --}}

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Data Presensi </h3>

            <div class="card-tools">
              {{-- <form method="get" action="/admin/pegawai/search">
                <div class="input-group input-group-sm" style="width: 300px;">
                  <input type="text" name="search" class="form-control input-sm float-right" value="{{old('search')}}"
                    placeholder="Cari NIP / Nama">

                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                  </div>
                </div>
              </form> --}}

            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap table-sm">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Bulan</th>
                  <th>Tahun</th>
                  <th></th>
                </tr>
              </thead>
              @php
              $no =1;
              @endphp
              <tbody>
                @foreach ($presensiSkpd as $key => $item)
                <tr>
                  <td>{{$no++}}</td>
                  <td>{{\Carbon\Carbon::createFromFormat('m',$item->bulan)->isoFormat('MMMM')}}</td>
                  <td>{{$item->tahun}}</td>
                  <td>
                    <a href="/admin/presensi/{{$item->bulan}}/{{$item->tahun}}"
                      class="btn btn-xs btn-success">Detail</a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
        {{-- {{$data->links()}} --}}
      </div>
    </div>

  </div>
</div>

@endsection

@push('js')

@endpush