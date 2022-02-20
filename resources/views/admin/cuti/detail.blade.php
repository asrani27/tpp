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
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card card-widget widget-user-2">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-info">
                        <div class="widget-user-image">
                            <img class="img-circle elevation-2" src="/login_tpp/images/icons/logo.png"
                                alt="User Avatar">
                        </div>
                        <!-- /.widget-user-image -->
                        <h3 class="widget-user-username">{{detailSkpd(Auth::user()->skpd->id)->nama}}</h3>
                        <h5 class="widget-user-desc">Kode Skpd: {{detailSkpd(Auth::user()->skpd->id)->kode_skpd}}</h5>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <a href="/admin/cuti" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                {{-- <a href="/admin/cuti/tarik" class="btn btn-sm btn-primary"><i class="fas fa-recycle"></i> Tarik
                    Cuti</a> --}}
                <br /><br />

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{$pegawai->nama}}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-2">
                        <table class="table table-hover text-nowrap table-sm">
                            <thead>
                                <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif"
                                    class="text-center bg-gradient-primary">
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Hari</th>
                                    <th>Jenis Cuti</th>
                                    <th>Menit Di Akui</th>
                                </tr>
                            </thead>
                            @php
                            $no =1;
                            @endphp
                            <tbody>
                                @foreach ($data as $key => $item)
                                <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif">
                                    <td>{{$no++}}</td>
                                    <td>{{$item->tanggal}}</td>
                                    <td>{{\Carbon\Carbon::parse($item->tanggal)->translatedFormat('l')}}</td>
                                    <td>{{$item->jenisketerangan->keterangan}}</td>
                                    <td>{{$item->menit}}</td>
                                </tr>
                                @endforeach
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