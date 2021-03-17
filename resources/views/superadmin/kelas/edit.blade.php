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
        <a href="/superadmin/kelas" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-alt-circle-left"></i> Kembali</a><br/><br/>
        <div class="row">
            <div class="col-lg-12 col-12">             
                <div class="card card-info">
                    <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-graduation-cap"></i> Edit Kelas Jabatan</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form class="form-horizontal" method="POST" action="/superadmin/kelas/edit/{{$data->id}}">
                        @csrf
                    <div class="card-body">
                        <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Kelas</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama" value="{{$data->nama}}" required>
                        </div>
                        </div>
                        <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Nilai</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nilai"  value="{{$data->nilai}}"  required>
                        </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">Update</button>
                    </div>
                    <!-- /.card-footer -->
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection

@push('js')


@endpush