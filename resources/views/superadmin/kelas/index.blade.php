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
        <h4>KELAS JABATAN</h4>
        <div class="row">
            <div class="col-12">
              <a href="/superadmin/kelas/add" class="btn btn-sm btn-primary"><i class="fas fa-graduation-cap"></i> Tambah Kelas</a><br/><br/>
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title"></h3>
  
                  <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 300px;">
                      <input type="text" name="table_search" class="form-control float-right" placeholder="Cari">
  
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap table-striped table-sm">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Kelas</th>
                        <th>Nilai</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    @php
                        $no =1;
                    @endphp
                    <tbody>
                    @foreach (kelas() as $key => $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>{{$item->nama}}</td>
                            <td>@currency($item->nilai)</td>
                            <td>
                            <a href="/superadmin/kelas/edit/{{$item->id}}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <a href="/superadmin/kelas/delete/{{$item->id}}" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin di hapus?');"><i class="fas fa-trash"></i></a>
                            </td>
                          </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
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