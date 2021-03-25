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
        <h4>SETTING JABATAN TERTINGGI</h4>
        <div class="row">
            <div class="col-12">
              <a href="/superadmin/parameter" class="btn btn-sm btn-secondary"><i class="fas fa-user-cog"></i> Kembali</a><br/><br/>
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title"></h3>
  
                  <div class="card-tools">
                    <form method="get" action="/superadmin/parameter/jabatan/search">
                    <div class="input-group input-group-sm" style="width: 300px;">
                      <input type="text" name="search" class="form-control input-sm float-right" value="{{old('search')}}" placeholder="Cari Nama Jabatan">
  
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
                        <th>Nama Jabatan</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    @php
                        $no =1;
                    @endphp
                    <tbody>
                    @foreach ($data as $key => $item)
                          <tr>
                            <td>{{$key + $data->firstItem()}}</td>
                            <td>{{$item->nama}}</td>
                            <td>
                            <a href="/superadmin/parameter/sekda/{{$item->id}}" class="btn btn-xs btn-success" onclick="return confirm('Yakin?');"><i class="fas fa-check"></i> Top Level</a>
                            </td>
                          </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              {{$data->links()}}
              <!-- /.card -->
            </div>
          </div>
    </div>
</div>
@endsection

@push('js')


@endpush