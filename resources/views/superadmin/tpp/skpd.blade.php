@extends('layouts.app')

@push('css')

<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
SUPERADMIN
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <h4>Laporan TPP Bulan {{convertBulan($bulan)}} {{$tahun}}</h4>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover table-striped text-nowrap table-sm">
                            <thead>
                                <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif"
                                    class="bg-gradient-primary">
                                    <th>#</th>
                                    <th>Kode</th>
                                    <th>SKPD</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            @php
                            $no =1;
                            @endphp
                            <tbody>
                                @foreach (skpd() as $key => $item)
                                <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                                    <td>{{$no++}}</td>
                                    <td>{{$item->kode_skpd}}</td>
                                    <td>{{$item->nama}}</td>
                                    <td><a href="/superadmin/tpp/{{$bulan}}/{{$tahun}}/laporan/{id}"
                                            class="btn btn-xs btn-success"><i class="fas fa-eye"></i> Laporan</a></td>
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