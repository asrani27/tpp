@extends('layouts.app')

@push('css')
  <link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
    PEGAWAI
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <h4>SKP PEGAWAI</h4>
        <div class="row">
            <div class="col-12">
              <form method="POST" action="/pegawai/skp/rencana-kegiatan/periode/view/{{$id}}">
                @csrf
              <div class="card">
                <div class="card-body">
                  
                  <div class="form-group row">
                      <label for="inputPassword3" class="col-sm-2 col-form-label text-right"> Deskripsi Kegiatan</label>
                      <div class="col-sm-10">
                        <textarea class="form-control" rows="3" name="deskripsi"></textarea>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label for="inputPassword3" class="col-sm-2 col-form-label text-right">AK</label>
                      <div class="col-sm-10">
                      <input type="text" class="form-control" name="ak">
                      </div>
                  </div>
                  
                  <div class="form-group row">
                      <label for="inputPassword3" class="col-sm-2 col-form-label text-right">Kuant/Output</label>
                      <div class="col-sm-10">
                      <input type="text" class="form-control" name="output">
                      </div>
                  </div>
                  
                  <div class="form-group row">
                      <label for="inputPassword3" class="col-sm-2 col-form-label text-right">Kual/Mutu</label>
                      <div class="col-sm-10">
                      <input type="text" class="form-control" name="mutu">
                      </div>
                  </div>
                  
                  <div class="form-group row">
                      <label for="inputPassword3" class="col-sm-2 col-form-label text-right">Waktu</label>
                      <div class="col-sm-10">
                      <input type="text" class="form-control" name="waktu">
                      </div>
                  </div>
                  
                  <div class="form-group row">
                      <label for="inputPassword3" class="col-sm-2 col-form-label text-right">Biaya</label>
                      <div class="col-sm-10">
                      <input type="text" class="form-control" name="biaya">
                      </div>
                  </div>
                  
                  <div class="form-group row">
                      <label for="inputPassword3" class="col-sm-2 col-form-label text-right"></label>
                      <div class="col-sm-10">
                        <a href="/pegawai/skp/rencana-kegiatan" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-alt-circle-left"></i> Kembali</a>
                        <button class="btn btn-sm btn-primary" type="submit"><i class="fas fa-save"></i>  Simpan SKP</button>
                      </div>
                  </div>
                </div>
              </div>
              </form>
              {{-- <a href="/pegawai/skp/rencana-kegiatan/add" class="btn btn-sm btn-primary"><i class="fas fa-users"></i> Tambah SKP</a> --}}
              
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Daftar SKP Pegawai Periode : </h3>
  
                  <div class="card-tools">
                    {{-- <form method="get" action="/pegawai/skp/rencana-kegiatan/search">
                    <div class="input-group input-group-sm" style="width: 300px;">
                      <input type="text" name="search" class="form-control input-sm float-right" value="{{old('search')}}" placeholder="Cari NIP / Nama">
  
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                      </div>
                    </div>
                    </form> --}}
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap table-sm">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Deskripsi Kegiatan/Tugas</th>
                        <th>AK</th>
                        <th>Kuant/Output</th>
                        <th>Kual/Mutu</th>
                        <th>Waktu</th>
                        <th>Biaya</th>
                        <th>Status</th>
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
                            <td>{{$item->deskripsi}}</td>
                            <td>{{$item->ak}}</td>
                            <td>{{$item->output}}</td>
                            <td>{{$item->mutu}}</td>
                            <td>{{$item->waktu}}</td>
                            <td>{{$item->biaya}}</td>
                            <td>
                                @if ($item->validasi == null)
                                    <span class="badge badge-info">diproses</span>
                                @elseif ($item->validasi == 1)
                                <span class="badge badge-success">disetujui</span>

                                @else
                                    
                                <span class="badge badge-danger">ditolak</span>
                                @endif
                            </td>
                            <td>
                          
                            <a href="/pegawai/skp/rencana-kegiatan/edit/{{$item->id}}/{{$id}}" class="btn btn-xs btn-warning" data-toggle="tooltip" title='Edit data'><i class="fas fa-edit"></i></a>
                            <a href="/pegawai/skp/rencana-kegiatan/delete/{{$item->id}}/{{$id}}" class="btn btn-xs btn-danger" data-toggle="tooltip" title='Hapus data' onclick="return confirm('Yakin ingin di hapus?');"><i class="fas fa-trash"></i></a>
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