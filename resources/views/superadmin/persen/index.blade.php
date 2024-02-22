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
    <h4>SKPD PEMERINTAH KOTA BANJARMASIN</h4>
    <div class="row">
      <div class="col-12">

        <div class="card">
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap table-sm">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Kode</th>
                  <th>Nama</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              @php
              $no =1;
              @endphp
              <tbody>
                @foreach ($skpd as $item)
                @if ($item->verifikasi == 1)
                <tr style="background-color: beige">
                @else
                <tr>
                @endif
                  <td>{{$no++}}</td>
                  <td><strong>{{$item->kode_skpd}}</strong></td>
                  <td>{{$item->nama}}</td>
                  <td>
                    @if ($item->verifikasi == 0)
                    <a href="/superadmin/persentase/skpd/{{$item->id}}/verifikasi" class="btn btn-xs btn-success"><i
                        class="fas fa-check"></i> verifikasi</a>
                      @else
                      <a href="/superadmin/persentase/skpd/{{$item->id}}/unverifikasi" class="btn btn-xs btn-danger"><i
                          class="fas fa-times"></i> unverifikasi</a>

                    @endif
                    <a href="/superadmin/persentase/skpd/{{$item->id}}" class="btn btn-xs btn-primary"><i
                        class="fas fa-percent"></i></a>
                  </td>
                </tr>
                @endforeach

                @foreach ($puskesmas as $item)
                <tr>
                  <td>{{$no++}}</td>
                  <td><strong>1.02.01.{{$item->id}}</strong></td>
                  <td>{{$item->nama}}</td>
                  <td>
                    <a href="/superadmin/persentase/puskesmas/{{$item->id}}" class="btn btn-xs btn-primary"><i
                        class="fas fa-percent"></i></a>
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