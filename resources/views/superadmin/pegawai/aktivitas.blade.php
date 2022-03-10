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
        <h4>AKTIVITAS PEGAWAI {{pegawaiByNip($id)->nama}} | {{pegawaiByNip($id)->nip}}</h4>
        <a href="/superadmin/pegawai" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        <br /><br />
        <div class="card">
            <div class="card-body">
                <table class="table table-hover table-striped text-nowrap table-sm">
                    <thead>
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif"
                            class="bg-gradient-primary">
                            <th>#</th>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    @php
                    $no =1;
                    @endphp
                    <tbody>

                        @foreach (bulanTahun() as $key => $item)
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                            <td>{{$no++}}</td>
                            <td>{{\Carbon\Carbon::createFromFormat('m',$item->bulan)->translatedFormat('F')}}</td>
                            <td>{{$item->tahun}}</td>
                            <td><a href="/superadmin/pegawai/aktivitas/{{$id}}/{{$item->bulan}}/{{$item->tahun}}"
                                    class="btn btn-xs btn-success"><i class="fas fa-eye"></i> Detail</a></td>
                        </tr>
                        @endforeach
                        {{-- <tr>
                            <td></td>
                            <td>Total Terlambat</td>
                            <td>Total Lebih awal</td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')


@endpush