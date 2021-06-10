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
            <input type="hidden" class="form-control"  value="{{Auth::user()->id}}">
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
            <form class="form-horizontal" method="POST" action="/pegawai/profil/edit">
                @csrf
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">Nama</label>
                <div class="col-sm-9">
                <input type="text" class="form-control" name="nama"  value="{{$data->nama}}" required>
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
                    <select name="pangkat_id" class="form-control" required>
                        <option value="">-pilih-</option>
                        @foreach ($pangkat as $item)
                            <option value="{{$item->id}}" {{$data->pangkat->id == $item->id ? 'selected':''}}>{{$item->nama}} ({{$item->golongan}})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">Eselon</label>
                <div class="col-sm-9">
                    <select name="eselon_id" class="form-control">
                        <option value="">-pilih-</option>
                        @foreach ($eselon as $item)
                            <option value="{{$item->id}}" {{$data->eselon_id == $item->id ? 'selected':''}}>{{$item->nama}}</option>
                        @endforeach
                    </select>
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
                <input type="text" class="form-control" name="no_rek" value="{{$data->no_rek == null ? '' : $data->no_rek}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">NPWP</label>
                <div class="col-sm-9">
                <input type="text" class="form-control" name="npwp" value="{{$data->npwp == null ? '' : $data->npwp}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-9">
                <input type="email" class="form-control" name="email" value="{{$data->user == null ? '' : $data->user->email}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">Alamat Rumah</label>
                <div class="col-sm-9">
                <input type="text" class="form-control" name="alamat" value="{{$data->alamat == null ? '' : $data->alamat}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">Jenis Kelamin</label>
                <div class="col-sm-9">
                    <select name="jkel" class="form-control" required>
                        <option value="L" {{$data->jkel == 'L' ? 'selected':''}}>Laki-Laki</option>
                        <option value="P" {{$data->jkel == 'P' ? 'selected':''}}>Perempuan</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">Pendidikan Terakhir</label>
                <div class="col-sm-2">
                    <select name="jenjang_pendidikan" class="form-control">
                        <option value="">-pilih-</option>
                        <option value="SMA" {{$data->jenjang_pendidikan == 'SMA' ? 'selected':''}}>SMA</option>
                        <option value="D3" {{$data->jenjang_pendidikan == 'D3' ? 'selected':''}}>D3</option>
                        <option value="S1" {{$data->jenjang_pendidikan == 'S1' ? 'selected':''}}>S1</option>
                        <option value="S2" {{$data->jenjang_pendidikan == 'S2' ? 'selected':''}}>S2</option>
                        <option value="S3" {{$data->jenjang_pendidikan == 'S3' ? 'selected':''}}>S3</option>
                    </select>
                </div>
                <div class="col-sm-7">
                    <input type="text" class="form-control" name="jurusan" value="{{$data->jurusan}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">Tanggal Lahir</label>
                <div class="col-sm-9">
                <input type="date" class="form-control" name="tanggal_lahir" value="{{$data->tanggal_lahir == null ? '' : $data->tanggal_lahir}}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="offset-sm-3 col-sm-9">
                <button type="submit" class="btn btn-success btn-block"><i class="fas fa-save"></i> <b>Update</b></button>
                </div>
            </div>
            <div class="form-group row">
                <div class="offset-sm-3 col-sm-9">
                <a href="/pegawai/profil" class="btn btn-secondary btn-block"><i class="fas fa-arrow-alt-circle-left"></i> <b>Kembali</b></a>
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