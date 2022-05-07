@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
GANTI PASSWORD
@endsection
@section('content')
<div class="row">
    <div class="col-md-3">

        <!-- Profile Image -->
        <div class="card card-info card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                        src="https://p.kindpng.com/picc/s/78-786207_user-avatar-png-user-avatar-icon-png-transparent.png"
                        alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">{{Auth::user()->name}}</h3>

                <p class="text-muted text-center">NIP. {{Auth::user()->username}}</p>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ganti Password</h3>
            </div>
            <div class="card-body text-sm">
                <form method="POST" action="/pegawai/gantipass">
                    @csrf
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            Untuk meningkatkan keamanan dari sistem aplikasi, setiap pegawai di wajibkan mengganti
                            password
                            yang baru. Syarat Password Baru :<br />
                            1. Minimal 8 karakter<br />
                            2. kombinasi huruf dan angka
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 col-form-label">Masukkan Password Lama</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="old_password" id="password" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 col-form-label">Masukkan Password Baru</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="password" id="password" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 col-form-label">Masukkan Password Baru Lagi</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="password_confirmation" id="password"
                                required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <button type="submit" class="btn btn-success btn-block"><i class="fas fa-save"></i>
                                Simpan</button>
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
<script>
    $(document).ready(function() {
  $(document).on('keypress', '#password', function(e){
     return !(e.keyCode == 32);
  });
});
</script>


@endpush