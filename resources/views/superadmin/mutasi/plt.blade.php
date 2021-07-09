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
        <h4>DAFTAR PEGAWAI PLT</h4>
        <div class="row">
            <div class="col-12">
                
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Total</h3>
  
                  {{-- <div class="card-tools">
                    <form method="post" action="/superadmin/aktivitas/search">
                        @csrf
                    <div class="input-group input-group-sm" style="width: 300px;">
                      <input type="text" name="search" class="form-control input-sm float-right" value="{{old('search')}}" placeholder="Cari NIP / Nama">
  
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                      </div>
                    </div>
                    </form>
                  </div> --}}
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap table-sm">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>NIP/Nama/SKPD</th>
                        <th>Jabatan Definitif</th>
                        <th>Jabatan PLT/PLH/PJ</th>
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