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
            <h3 class="widget-user-username">{{detailSkpd(Auth::user()->skpd->id)->nama}}</h3>
            <h5 class="widget-user-desc">Kode Skpd: {{detailSkpd(Auth::user()->skpd->id)->kode_skpd}}</h5>
          </div>

        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <a href="/admin/cuti/tarik" class="btn btn-sm btn-primary"><i class="fas fa-recycle"></i> Tarik Cuti</a>
        <br /><br />

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Total ASN : {{$data->total()}} Orang</h3>
            <div class="card-tools">
              <form method="get" action="/admin/cuti/search">
                <div class="input-group input-group-sm" style="width: 300px;">
                  <input type="text" name="search" class="form-control input-sm float-right" value="{{old('search')}}"
                    placeholder="Cari NIP / Nama">

                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-2">
            <table class="table table-hover text-nowrap table-sm">
              <thead>
                <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif"
                  class="text-center bg-gradient-primary">
                  <th>#</th>
                  <th>Nama/NIP</th>
                  <th>Jabatan</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              @php
              $no =1;
              @endphp
              <tbody>
                @foreach ($data as $key => $item)
                <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif">
                  <td>{{$key+ $data->firstItem()}}</td>
                  <td>{{$item->nama}}<br />{{$item->nip}}</td>
                  <td>{{$item->jabatan == null ? '-' : $item->jabatan->nama}}<br />
                    {{$item->plt == null ? '': $item->jenis_plt.'., '.$item->plt->nama}}
                  </td>
                  <td>
                    <a href="/admin/cuti/{{$item->nip}}/detail" class="btn btn-xs btn-danger" data-toggle="tooltip"
                      title='Edit data'>Daftar Cuti</a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        {{$data->links()}}
      </div>
    </div>

  </div>
</div>

@endsection

@push('js')
@endpush