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
        <a href="/admin/rspuskesmas/add" class="btn btn-sm btn-primary"><i class="fas fa-hospital"></i> Tambah</a>
        <a href="/admin/rspuskesmas/createuserpuskesmas" class="btn btn-sm btn-primary"><i class="fas fa-key"></i>
          Create User</a>
        <br /><br />

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Data Rumah Sakit Dan Puskesmas</h3>

            <div class="card-tools">
              {{-- <form method="get" action="/admin/rspuskesmas/search">
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
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Kode (user for login)</th>
                  <th>Nama RS/Puskemas</th>
                  <th></th>
                </tr>
              </thead>
              @php
              $no =1;
              @endphp
              <tbody>
                @foreach ($data as $key => $item)
                <tr>
                  <td>{{$no++}}</td>
                  <td>{{Auth::user()->username.$item->id}}</td>
                  <td>{{$item->nama}}</td>
                  <td>
                    <a href="/admin/rspuskesmas/{{$item->id}}/edit" class="btn btn-sm btn-warning" data-toggle="tooltip"
                      title='Reset Password' onclick="return confirm('Yakin ingin di reset password?');"><i
                        class="fas fa-key"></i></a>
                    <a href="/admin/rspuskesmas/{{$item->id}}/petajabatan" class="btn btn-sm bg-purple"
                      data-toggle="tooltip" title='Peta Jabatan'><i class="fas fa-sitemap"></i> PETA JABATAN</a>
                    <a href="/admin/rspuskesmas/{{$item->id}}/edit" class="btn btn-sm btn-success" data-toggle="tooltip"
                      title='Edit'><i class="fas fa-edit"></i></a>
                    <a href="/admin/rspuskesmas/{{$item->id}}/delete" class="btn btn-sm btn-danger"
                      data-toggle="tooltip" title='Hapus data' onclick="return confirm('Yakin ingin di hapus?');"><i
                        class="fas fa-trash"></i></a>


                    <a href="/admin/rspuskesmas/{{$item->id}}/login" class="btn btn-sm btn-danger" data-toggle="tooltip"
                      title='Login Ke Puskesmas'>Login <i class="fas fa-arrow-right"></i></a>


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

@endpush