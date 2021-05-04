@extends('layouts.app')

@push('css')
    
@endpush
@section('title')
  <strong>GAJI</strong>
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
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Gaji Pegawai</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">Gaji</label>
                <div class="col-sm-9">
                <input type="text" class="form-control" value="Rp. 0,-" readonly>
                </div>
            </div>
        </div><!-- /.card-body -->
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
</div>
@endsection

@push('js')

@endpush