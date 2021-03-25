@extends('layouts.app')

@push('css')

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
                      <h5 class="widget-user-desc">Kode Skpd: {{detailSkpd($skpd_id)->kode_skpd}}</h5>
                    </div>
                    
                  </div>
            </div>
        </div>
        <a href="/superadmin/skpd" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-alt-circle-left"></i> Kembali</a>
        <a href="/superadmin/skpd/pegawai/{{$skpd_id}}/add" class="btn btn-sm btn-primary"><i class="fas fa-users"></i> Tambah ASN</a>
        <a href="/superadmin/skpd/pegawai/createuser/{{$skpd_id}}" class="btn btn-sm bg-purple"><i class="fas fa-key"></i> Create User & Pass ASN</a>
        <a href="/superadmin/skpd/pegawai/{{$skpd_id}}/import" class="btn btn-sm btn-info"><i class="fas fa-file-excel"></i> Import Data</a><br/><br/>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title"></h3>
      
                      <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 300px;">
                          <input type="text" name="table_search" class="form-control float-right" placeholder="Cari NIP / Nama">
      
                          <div class="input-group-append">
                            <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                      <table class="table table-hover text-nowrap">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th></th>
                            <th>NIP / Username</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        @php
                            $no =1;
                        @endphp
                        <tbody>
                        @foreach ($data as $key => $item)
                              <tr>
                                <td>{{$no++}}</td>
                                <td>
                                  @if ($item->foto == null)
                                      
                                  <img src="https://p.kindpng.com/picc/s/78-786207_user-avatar-png-user-avatar-icon-png-transparent.png" alt="User" width="30px" class="brand-image img-circle elevation-3"
                                  style="opacity: .8">
                                  @else
                                  <img src="/storage/pegawai/{{$item->foto}}" alt="User" width="30px" class="brand-image img-circle elevation-3"
                                  style="opacity: .8">
                                      
                                  @endif
                                </td>
                                <td>{{$item->nip}}<br />
                                </td>
                                <td>{{$item->nama}}</td>
                                <td>{{$item->jabatan == null ? '-': $item->jabatan->nama}}</td>
                                <td>
                                  @if ($item->user_id == null)
                                    <a href="/superadmin/pegawai/createuser/{{$item->id}}" class="btn btn-xs btn-success" data-toggle="tooltip" title="Buat Akun User"><i class="fas fa-key"></i></a>
                                  @else
                                    <a href="/superadmin/pegawai/resetpassword/{{$item->id}}" class="btn btn-xs btn-secondary"  data-toggle="tooltip" title="Reset Password" onclick="return confirm('Yakin ingin di reset?');"><i class="fas fa-lock"></i></a>
                                      
                                  @endif
                                <a href="/superadmin/skpd/pegawai/{{$skpd_id}}/edit/{{$item->id}}" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit Data"><i class="fas fa-edit"></i></a>
                                <a href="/superadmin/skpd/pegawai/{{$skpd_id}}/delete/{{$item->id}}" class="btn btn-xs btn-danger"  data-toggle="tooltip" title="Hapus Data" onclick="return confirm('Yakin ingin di hapus?');"><i class="fas fa-trash"></i></a>
                                </td>
                              </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  {{$data->links()}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

@endpush