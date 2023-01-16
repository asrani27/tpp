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
        <h4>PENILAIAN SKP PEGAWAI</h4>
        <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">List Pejabat Di Nilai</h3>

                  <div class="card-tools">
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap table-sm">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nama, NIP</th>
                        <th>Periode</th>
                        <th>Penilaian</th>
                      </tr>
                    </thead>
                    @php
                        $no =1;
                    @endphp
                    <tbody>
                    @foreach ($data as $key => $item)
                          <tr>
                            <td>{{$key+ 1}}</td>
                            <td>
                              {{json_decode($item->pn)->nama}}<br/>
                              {{json_decode($item->pn)->jabatan}}
                            </td>
                            <td>{{\Carbon\Carbon::parse($item->mulai)->isoFormat('D MMMM Y')}} s/d {{\Carbon\Carbon::parse($item->sampai)->isoFormat('D MMMM Y')}}</td>
                            
                            <td>
                              <a href="/pegawai/nilai-skp/ekspektasi/{{$item->id}}" class="btn btn-xs btn-success">EKSPEKTASI</a>
                              <a href="/pegawai/nilai-skp/triwulan/1/{{$item->id}}" class="btn btn-xs btn-success">TRIWULAN 1</a>
                              <a href="/pegawai/nilai-skp/triwulan/2/{{$item->id}}" class="btn btn-xs btn-success">TRIWULAN 2</a>
                              <a href="/pegawai/nilai-skp/triwulan/3/{{$item->id}}" class="btn btn-xs btn-success">TRIWULAN 3</a>
                              <a href="/pegawai/nilai-skp/triwulan/4/{{$item->id}}" class="btn btn-xs btn-success">TRIWULAN 4</a>
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