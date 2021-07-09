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
        <h4>AKTIVITAS PEGAWAI</h4>
        <div class="row">
            <div class="col-12">
              <a href="/superadmin/aktivitas/setuju" class="btn btn-sm btn-primary"><i class="fas fa-th"></i> Aktivitas Setuju</a>
              <a href="/superadmin/aktivitas/tolak" class="btn btn-sm bg-danger"><i class="fas fa-th"></i> Aktivitas Ditolak</a>
              <br/><br/>
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Total Aktivitas</h3>
                  <div class="card-tools">
                    <form method="get" action="/superadmin/aktivitas/search">
                        
                    <div class="input-group input-group-sm" style="width: 300px;">
                      <input type="text" name="search" class="form-control input-sm float-right" value="{{old('search')}}" placeholder="Cari NIP / Nama">
                      <input type="hidden" name="validasi" class="form-control input-sm float-right" value="{{$validasi}}">
  
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
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Aktivitas</th>
                        <th>status</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    @php
                        $no =1;
                    @endphp
                    <tbody>
                    @foreach ($data as $key => $item)
                          <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif" >
                            <td>{{$key+ $data->firstItem()}}</td>
                            <td>{{$item->pegawai->nip}}</td>
                            <td>{{$item->pegawai->nama}}</td>
                            <td>{{$item->deskripsi}}</td>
                            <td>
                                @if ($item->validasi == 0)
                                    <span class="badge badge-info">diproses</span>
                                @elseif($item->validasi == 1)
                                    <span class="badge badge-success">disetujui</span>
                                @elseif($item->validasi == 2)
                                    <span class="badge badge-danger">ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if ($item->validasi == 2)
                                <a href="/superadmin/aktivitas/setujui/{{$item->id}}" class="btn btn-xs btn-success" data-toggle="tooltip" title='Setujui data' onclick="return confirm('Yakin ingin di setujui?');"><strong>setuju</strong></a>
                                @endif
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