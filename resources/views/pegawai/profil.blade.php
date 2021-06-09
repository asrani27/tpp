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

          <a href="#" class="btn btn-info btn-block"><i class="fas fa-upload"></i> Upload Foto</a>
          <a href="/pegawai/profil/edit" class="btn btn-success btn-block"><i class="fas fa-user"></i>Edit Profil</a>
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
            <form method="POST" action="/pegawai/profil/gantipass">
              @csrf
            <input type="hidden" class="form-control" readonly value="{{Auth::user()->id}}">
            <input type="text" class="form-control" name="password1" required placeholder="masukkan password"><br />
            <input type="text" class="form-control" name="password2" required placeholder="masukkan password Lagi"><br />
            <button type="submit" class="btn btn-success btn-block"><i class="fas fa-key"></i> Ganti Password</button>
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
                <label for="inputName" class="col-sm-3 col-form-label">Nama</label>
                <div class="col-sm-9">
                <input type="text" class="form-control" value="{{$data->nama}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">NIP</label>
                <div class="col-sm-9">
                <input type="text" class="form-control" value="{{$data->nip}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">Jabatan</label>
                <div class="col-sm-9">
                <input type="text" class="form-control" value="{{$data->jabatan == null ? '' : $data->jabatan->nama}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">Pangkat / Golongan</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" value="{{$data->pangkat == null ? '' : $data->pangkat->nama.'('.$data->pangkat->golongan.')'}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">Eselon</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" value="{{$data->eselon == null ? '' : $data->eselon->nama}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">SKPD</label>
                <div class="col-sm-9">
                <input type="text" class="form-control" value="{{$data->skpd == null ? '' : $data->skpd->nama}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">Pejabat Penilai</label>
                <div class="col-sm-9">
                <input type="text" class="form-control" value="{{($data->jabatan == null ? '' : $data->jabatan->atasan) == null ? 'Sekda' : $data->jabatan->atasan->pegawai->nama}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">No Rek. BPPD</label>
                <div class="col-sm-9">
                <input type="text" class="form-control" name="no_rek" value="{{$data->no_rek == null ? '' : $data->no_rek}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">NPWP</label>
                <div class="col-sm-9">
                <input type="text" class="form-control" name="npwp" value="{{$data->npwp == null ? '' : $data->npwp}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-9">
                <input type="text" class="form-control" name="email" value="{{$data->user == null ? '' : $data->user->email}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">Alamat Rumah</label>
                <div class="col-sm-9">
                <input type="text" class="form-control" name="alamat" value="{{$data->alamat == null ? '' : $data->alamat}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">Jenis Kelamin</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" value="{{$data->jkel == null ? '' : $data->jkel}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">Pendidikan Terakhir</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" value="{{$data->jenjang_pendidikan == null ? '' : $data->jenjang_pendidikan}}" readonly>
                </div>
                <div class="col-sm-7">
                    <input type="text" class="form-control" value="{{$data->jurusan == null ? '' : $data->jurusan}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">Tanggal Lahir / Umur</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" value="{{$data->tanggal_lahir == null ? '' : $data->tanggal_lahir}}" readonly>
                </div>
                <div class="col-sm-7">
                    <input type="text" class="form-control" value="{{$data->tanggal_lahir == null ? '' : \Carbon\Carbon::parse($data->tanggal_lahir)->diff(\Carbon\Carbon::now())->format('%y Tahun, %m Bulan and %d Hari')}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <div class="offset-sm-2 col-sm-9">
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