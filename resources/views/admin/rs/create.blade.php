@extends('layouts.app')

@push('css')

  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
    ADMIN SKPD
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
        <a href="/admin/rspuskesmas" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-alt-circle-left"></i> Kembali</a><br /><br />
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title"></h3>
                        Tambah Data RS / Puskesmas
                      <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 300px;">
                          {{-- <input type="text" name="table_search" class="form-control float-right" placeholder="Cari NIP / Nama">
                          <div class="input-group-append">
                            <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                          </div> --}}
                        </div>
                      </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <form class="form-horizontal" method="POST" action="/admin/rspuskesmas/add">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-2 col-form-label">Nama RS / Puskemas</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nama" required>
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