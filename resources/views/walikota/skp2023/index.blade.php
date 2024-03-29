@extends('layouts.app')

@push('css')
  <link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush
@section('title')
    PEGAWAI
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <h4>SKP PEGAWAI (Format Baru)</h4>
        <div class="row">
            <div class="col-12">
              <form method="POST" action="/pegawai/new-skp/periode">
                @csrf
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-3 col-12">
                      <label class="col-form-label" for="inputWarning"><i class="far fa-calendar"></i> Periode Mulai</label>
                       <input type="text" name="mulai" class="form-control" id="periodemulai" required autocomplete="off">
                    </div>
                    <div class="col-md-3 col-12">
                      <label class="col-form-label" for="inputWarning"><i class="far fa-calendar"></i> Periode Selesai</label>
                      <input type="text" name="sampai" class="form-control" id="periodesampai" required autocomplete="off">
                    </div>
                    <div class="col-md-3 col-12">
                      <label class="col-form-label" for="inputWarning"><i class="fas fa-list"></i> Jenis</label>
                      <select class="form-control" name="jenis" required>
                        <option value="">-pilih-</option>
                        <option value="JPT">JPT</option>
                        <option value="JF">JF</option>
                        <option value="JA">JA</option>
                      </select>
                    </div>
                    <div class="col-md-3 col-12">
                      <label class="col-form-label" for="inputWarning"><i class="far fa-user"></i> </label><br />
                      <button class="btn btn-primary" type="submit">Tambah Periode</button>
                    </div>
                  </div>
                </div>
              </div>
              </form>
              {{-- <a href="/pegawai/skp/rencana-kegiatan/add" class="btn btn-sm btn-primary"><i class="fas fa-users"></i> Tambah SKP</a> --}}
              
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Periode SKP Pegawai</h3>
  
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
                        <th>Periode</th>
                        <th>Penilai</th>
                        <th>Jenis</th>
                        <th>Aktif?</th>
                        <th>Aksi</th>
                        <th>Evaluasi</th>
                      </tr>
                    </thead>
                    @php
                        $no =1;
                    @endphp
                    <tbody>
                    @foreach ($data as $key => $item)
                          <tr>
                            <td>{{$key+ $data->firstItem()}}</td>
                            <td>{{\Carbon\Carbon::parse($item->mulai)->isoFormat('D MMMM Y')}} s/d {{\Carbon\Carbon::parse($item->sampai)->isoFormat('D MMMM Y')}}</td>
                            <td>
                              @if ($item->pp == null)
                                  -
                              @else
                              <a href="/pegawai/new-skp/atasan/{{json_decode($item->pp)->nip}}" target="_blank">
                              {{json_decode($item->pp)->nama}}
                              </a>
                              @endif
                              </td>
                            <td>{{$item->jenis}}</td>
                            <td>
                              @if ($item->is_aktif == 1)
                                <span class="badge badge-success"><i class="fas fa-check-circle"></i></span>
                              @else
                                <a href="/pegawai/new-skp/periode/aktifkan/{{$item->id}}" class="btn btn-xs btn-primary" data-toggle="tooltip" title='Aktifkan'>Aktifkan</a>
                              @endif
                            </td>
                            <td>
                              <a href="/pegawai/new-skp/periode/view/{{$item->id}}" class="btn btn-xs btn-success">Rencana SKP</a>
                            <a href="/pegawai/new-skp/periode/edit/{{$item->id}}" class="btn btn-xs btn-warning" data-toggle="tooltip" title='Edit data'><i class="fas fa-edit"></i></a>
                            <a href="/pegawai/new-skp/periode/delete/{{$item->id}}" class="btn btn-xs btn-danger" data-toggle="tooltip" title='Hapus data' onclick="return confirm('Yakin ingin di hapus?');"><i class="fas fa-trash"></i></a>
                            </td>
                            <td>
                              <a href="/pegawai/new-skp/periode/evaluasi/{{$item->id}}/triwulan/1" class="btn btn-xs btn-success">TW1</a>
                              <a href="/pegawai/new-skp/periode/evaluasi/{{$item->id}}/triwulan/2" class="btn btn-xs btn-success">TW2</a>
                              <a href="/pegawai/new-skp/periode/evaluasi/{{$item->id}}/triwulan/3" class="btn btn-xs btn-success">TW3</a>
                              <a href="/pegawai/new-skp/periode/evaluasi/{{$item->id}}/triwulan/4" class="btn btn-xs btn-success">TW4</a>
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

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $( function() {
    $( "#periodemulai" ).datepicker(
      { dateFormat: 'dd/mm/yy' }
    );
  } );
  $( function() {
    $( "#periodesampai" ).datepicker(
      { dateFormat: 'dd/mm/yy' }
    );
  } );
</script>
@endpush