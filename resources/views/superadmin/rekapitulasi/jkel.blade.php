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
        <a href="/superadmin/pegawai">
            <div class="card card-widget widget-user">
                <div class="widget-user-header bg-info">
                    <h3 class="widget-user-username">TOTAL PEGAWAI</h3>
                    <h2 class="widget-user-desc">{{$total}}<br />
                        <i class="fas fa-users"></i>
                    </h2>
                </div>
            </div>
        </a>
        <h4 class="mt-4 mb-2">BERDASARKAN JENIS KELAMIN</h4>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data ASN</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>SKPD</th>
                            <th>Jenis Kelamin</th>
                        </tr>
                    </thead>
                    @php
                    $no=1;
                    @endphp
                    <tbody>
                        @foreach ($data as $item)
                        <tr>
                            <td>{{$no++}}</td>
                            <td>{{$item->nip}}</td>
                            <td>{{$item->nama}}</td>
                            <td>{{$item->skpd == null ? '-': $item->skpd->nama}}</td>
                            <td>{{$item->jkel}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>

@endsection

@push('js')


@endpush