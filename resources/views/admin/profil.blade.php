@extends('layouts.app')

@push('css')
  <link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
    ADMIN SKPD
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <h4>Profil</h4>
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card card-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-info">
                    <h3 class="widget-user-username">{{Auth::user()->name}}</h3>
                    <h5 class="widget-user-desc">PEMKO BANJARMASIN</h5>
                    </div>
                    <div class="widget-user-image">
                    <img class="img-circle elevation-2" src="/login_tpp/images/icons/logo.png" alt="User Avatar">
                    </div>
                    <div class="card-footer">
                    <div class="row">
                        <div class="col-4 border-right">
                        <div class="description-block">
                            <h5 class="description-header">E-MAIL</h5>
                            <span class="description-text">admin@banjarmasinkota.go.id</span>
                        </div>
                        <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-4 border-right">
                        <div class="description-block">
                            <h5 class="description-header">CONTACT</h5>
                            <span class="description-text">0878 1234 1234</span>
                        </div>
                        <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                        <div class="description-block">
                            <h5 class="description-header">ALAMAT</h5>
                            <span class="description-text">JL RE MARTADINATA NO 1</span>
                        </div>
                        <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    </div>
                </div>
              </div>
              
        </div>
        <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Ganti Password</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" method="POST" action="/admin/profil">
                @csrf
              <div class="card-body">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">Username</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="{{Auth::user()->username}}" readonly>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword3" class="col-sm-2 col-form-label">Masukkan Password</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" name="password" placeholder="Password Baru">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword3" class="col-sm-2 col-form-label">Masukkan Password Lagi</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" name="password2" placeholder="Password Baru">
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <button type="submit" class="btn btn-info">Simpan</button>
              </div>
              <!-- /.card-footer -->
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')


@endpush