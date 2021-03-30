@extends('layouts.app')

@push('css')
    
@endpush
@section('title')
  <strong>PROFIL</strong>
@endsection
@section('content')
<div class="row">
    <div class="col-md-3">

      <!-- Profile Image -->
      <div class="card card-info card-outline">
        <div class="card-body box-profile">
          <div class="text-center">
            <img class="profile-user-img img-fluid img-circle" src="https://p.kindpng.com/picc/s/78-786207_user-avatar-png-user-avatar-icon-png-transparent.png" alt="User profile picture">
          </div>

          <h3 class="profile-username text-center">{{Auth::user()->name}}</h3>

          <p class="text-muted text-center">NIP. {{Auth::user()->username}}</p>

          <a href="#" class="btn btn-info btn-block"><b>Upload Foto</b></a>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

      <!-- About Me Box -->
      <div class="card card-info">
        <div class="card-header">
          <h3 class="card-title">Ganti Password</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form method="POST" action="">
            <input type="hidden" class="form-control" readonly value="{{Auth::user()->id}}">
            <input type="text" class="form-control" required placeholder="masukkan password"><br />
            <input type="text" class="form-control" required placeholder="masukkan password Lagi"><br />
            <button type="submit" class="btn btn-success btn-block"><b>Ganti Password</b></button>
            </form>
            
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Detail Pegawai</h3>
        </div>
        <div class="card-body">
            <form class="form-horizontal">
            <div class="form-group row">
                <label for="inputName" class="col-sm-2 col-form-label">Nama</label>
                <div class="col-sm-10">
                <input type="text" class="form-control" value="{{$data->nama}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-2 col-form-label">NIP</label>
                <div class="col-sm-10">
                <input type="text" class="form-control" value="{{$data->nip}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-2 col-form-label">Jabatan</label>
                <div class="col-sm-10">
                <input type="text" class="form-control" value="{{$data->jabatan == null ? '' : $data->jabatan->nama}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-2 col-form-label">SKPD</label>
                <div class="col-sm-10">
                <input type="text" class="form-control" value="{{$data->skpd == null ? '' : $data->skpd->nama}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-2 col-form-label">Pejabat Penilai</label>
                <div class="col-sm-10">
                <input type="text" class="form-control" value="{{$data->jabatan == null ? '' : $data->jabatan->atasan->pegawai->nama}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <div class="offset-sm-2 col-sm-10">
                <button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> <b>Edit</b></button>
                </div>
            </div>
            </form>
        </div><!-- /.card-body -->
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
</div>
@endsection

@push('js')

@endpush