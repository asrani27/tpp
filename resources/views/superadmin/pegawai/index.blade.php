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
        <h4>ASN PEMERINTAH KOTA BANJARMASIN</h4>
        <div class="row">
            <div class="col-12">
              <a href="/superadmin/pegawai/add" class="btn btn-sm btn-primary"><i class="fas fa-users"></i> Tambah ASN</a>
              <a href="/superadmin/pegawai/createuser" class="btn btn-sm bg-purple"><i class="fas fa-key"></i> Create User & Pass ASN</a>
              <br/><br/>
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
                        <th>NIP / Username</th>
                        <th>Nama</th>
                        <th>SKPD</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    @php
                        $no =1;
                    @endphp
                    <tbody>
                    @foreach (pegawai() as $key => $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>{{$item->nip}}<br />
                            @if ($item->user_id == null)
                              <a href="/superadmin/pegawai/createuser/{{$item->id}}" class="btn btn-xs btn-secondary"><i class="fas fa-key"></i> Create User</a>
                            @else
                                
                            @endif
                            </td>
                            <td>{{$item->nama}}<br />
                                Jabatan : 
                            </td>
                            <td>{{$item->skpd->nama}}</td>
                            <td>
                            <a href="/superadmin/pegawai/edit/{{$item->id}}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <a href="/superadmin/pegawai/delete/{{$item->id}}" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin di hapus?');"><i class="fas fa-trash"></i></a>
                            </td>
                          </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                {{pegawai()->links()}}
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>
    </div>
</div>
@endsection

@push('js')


@endpush