@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
PRESENSI
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
            <h5 class="widget-user-desc">Berikut ini data presensi pegawai, Presensi bulan sebelumnya akan di rekap dan
              di kunci oleh sistem setiap tanggal 5 awal bulan</h5>
          </div>

        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <a href="/admin/presensi/list" class="btn btn-sm btn-secondary" class="fas fa"></i> Kembali</a>
        <a href="#" class="btn btn-sm bg-gradient-purple"><i class="fas fa-refresh"></i> Sinkron
          Presensi</a>
        <br /> <br />

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Data Presensi {{$bulanTahun->isoFormat('MMMM Y')}}</h3>

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
                  <th>Nama / Nip / Jabatan</th>
                  <th>Presensi Final (%)</th>
                  <th>Hukuman Disiplin</th>
                </tr>
              </thead>
              @php
              $no =1;
              @endphp
              <tbody>
                @foreach ($pegawai as $key => $item)
                <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif">
                  <td>{{$no++}}</td>
                  <td>{{$item->nama}}<br />
                    {{$item->jabatan == null ? '-' : $item->jabatan->nama}}<br />
                    {{$item->nip}}<br />
                  </td>
                  <td>
                    {{-- {{$item->persen == null ? 0:$item->persen}} --}}
                    <font size="4">0 %</font>
                  </td>
                  <td>-</td>
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