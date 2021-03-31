@extends('layouts.app')

@push('css')
  <link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
    LAPORAN TPP
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <h4>LAPORAN TPP</h4>
        <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Riwayat TPP</h3>
  
                  <div class="card-tools">
                    <form method="get" action="/pegawai/skp/rencana-kegiatan/search">
                    <div class="input-group input-group-sm" style="width: 300px;">
                      <input type="text" name="search" class="form-control input-sm float-right" value="{{old('search')}}" placeholder="Bulan / Tahun">
  
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
                        <th>Bulan</th>
                        <th>Tahun</th>
                        <th>TPP Diterima</th>
                      </tr>
                    </thead>
                    @php
                        $no =1;
                    @endphp
                    <tbody>
                    @foreach ($data as $key => $item)
                          <tr>
                            <td>{{$key+ $data->firstItem()}}</td>
                            <td>{{\Carbon\Carbon::createFromFormat('m',$item->bulan)->isoFormat('MMMM')}}</td>
                            <td>{{$item->tahun}}</td>
                            <td>@currency(($item->total_pagu * $item->disiplin / 100) + ($item->total_pagu * $item->produktivitas / 100))</td>
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