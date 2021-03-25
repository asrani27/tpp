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
        <h4>Data ASN</h4>
        
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card card-widget widget-user-2">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-info">
                      <div class="widget-user-image">
                        <img class="img-circle elevation-2" src="/login_tpp/images/icons/logo.png" alt="User Avatar">
                      </div>
                      <!-- /.widget-user-image -->
                      <h3 class="widget-user-username">{{detailSkpd($skpd_id)->nama}}</h3>
                      <h5 class="widget-user-desc">File Excel Harus Sesuai Format Yang telah ditentukan</h5>
                    </div>
                    
                  </div>
            </div>
        </div>
        <a href="/superadmin/skpd/pegawai/{{$skpd_id}}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-alt-circle-left"></i> Kembali</a><br /><br />
        <div class="row">
            <div class="col-12">
                <div class="card">
                    
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <form class="form-horizontal" method="POST" action="/superadmin/skpd/pegawai/{{$skpd_id}}/import" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">File Excel</label>
                                    <div class="col-sm-10">
                                        <input type="file" name="file" class="form-control">
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
                    
                    <!-- /.card-body -->
                  </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

@endpush