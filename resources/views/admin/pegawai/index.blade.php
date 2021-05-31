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
        <div class="row">
            <div class="col-12">
              <a href="/admin/pegawai/add" class="btn btn-sm btn-primary"><i class="fas fa-users"></i> Tambah ASN</a>
              {{-- <a href="/admin/pegawai/createuser" class="btn btn-sm bg-purple"><i class="fas fa-key"></i> Create User & Pass ASN</a> --}}
              <a href="/admin/pegawai" class="btn btn-sm bg-info"><i class="fas fa-recycle"></i> Refresh</a>
              <br/><br/>
              
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Total ASN : {{$data->total()}} Orang</h3>
  
                  <div class="card-tools">
                    <form method="get" action="/admin/pegawai/search">
                    <div class="input-group input-group-sm" style="width: 300px;">
                      <input type="text" name="search" class="form-control input-sm float-right" value="{{old('search')}}" placeholder="Cari NIP / Nama">
  
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                      </div>
                    </div>
                    </form>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap table-sm">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th></th>
                        <th>NIP / Username</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        @if (Auth::user()->username == '1.02.01.')
                          <th>SKPD/RS/Puskesmas</th>
                        @endif
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    @php
                        $no =1;
                    @endphp
                    <tbody>
                    @foreach ($data as $key => $item)
                          <tr>
                            <td>{{$key+ $data->firstItem()}}</td>
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
                            <td>{{$item->jabatan == null ? '-' : $item->jabatan->nama}}</td>
                            
                            @if (Auth::user()->username == '1.02.01.')
                              <td>{{$item->jabatan->rs_puskesmas_id == null ? 'Dinas Kesehatan' : $item->jabatan->rs->nama}}</td>
                            @endif
                            <td>
                                @if ($item->user_id == null)
                                  <a href="/admin/pegawai/createuser/{{$item->id}}" class="btn btn-xs btn-success"><i class="fas fa-key"></i> Create User</a>
                                @else
                                  <a href="/admin/pegawai/resetpass/{{$item->id}}" class="btn btn-xs btn-secondary"><i class="fas fa-key"></i> Reset Pass</a>  
                                @endif
                            {{-- <a href="/admin/pegawai/detail/{{$item->id}}" class="btn btn-xs btn-info" data-toggle="tooltip" title='lihat data'><i class="fas fa-eye"></i></a> --}}
                            <a href="/admin/pegawai/edit/{{$item->id}}" class="btn btn-xs btn-warning" data-toggle="tooltip" title='Edit data'><i class="fas fa-edit"></i></a>
                            <a href="/admin/pegawai/delete/{{$item->id}}" class="btn btn-xs btn-danger" data-toggle="tooltip" title='Hapus data' onclick="return confirm('Yakin ingin di hapus?');"><i class="fas fa-trash"></i></a>
                            </td>
                          </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            {{$data->links()}} 
            </div>
        </div>
        
    </div>
</div>

@endsection

@push('js')
@endpush