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
        <h4>Parameter</h4>
        <div class="row">
            <div class="col-12">
              
              <div class="card">
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap table-striped table-sm">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Persentase TPP</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    @php
                        $no =1;
                    @endphp
                    <tbody>
                    @foreach (parameter() as $key => $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>{{$item->persentase_tpp}}</td>
                            <td>
                            <a href="/superadmin/parameter/edit/{{$item->id}}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            </td>
                          </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              
              <div class="card">
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap table-striped table-sm">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Jabatan Tertinggi</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    @php
                        $no =1;
                    @endphp
                    <tbody>
                          <tr>
                            <td>1</td>
                            <td>{{$toplevel == null ? '-': $toplevel->nama}}</td>
                            <td>
                            <a href="/superadmin/parameter/jabatan/edit" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            </td>
                          </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
    </div>
</div>
@endsection

@push('js')


@endpush