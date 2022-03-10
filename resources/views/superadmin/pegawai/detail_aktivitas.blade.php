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
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Aktivitas Disetujui</th>
                            <th>Menit</th>
                            <th>Penilai</th>
                        </tr>
                    </thead>
                    @php
                    $no =1;
                    @endphp
                    <tbody>

                        @foreach ($aktivitas_disetujui as $key => $item)
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                            <td>{{$no++}}</td>
                            <td>{{$item->tanggal}}</td>
                            <td>{{$item->jam_mulai}} - {{$item->jam_selesai}}</td>
                            <td>{!!wordwrap($item->deskripsi,100,"<br>")!!}</td>
                            <td>{{$item->menit}}</td>
                            <td>{{$item->penilai->nama}}</td>
                        </tr>
                        @endforeach

                        <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif; font-weight:bold">
                            <td colspan="4" class="text-right">Total Aktivitas Di Setujui</td>
                            <td>{{$aktivitas_disetujui->sum('menit')}} Menit</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                <br />
                <br />

                <table class="table table-hover table-striped text-nowrap table-sm">
                    <thead>
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif" class="bg-gradient-danger">
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Aktivitas Ditolak</th>
                            <th>Menit</th>
                            <th>Penilai</th>
                        </tr>
                    </thead>
                    @php
                    $no =1;
                    @endphp
                    <tbody>

                        @foreach ($aktivitas_ditolak as $key => $item)
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                            <td>{{$no++}}</td>
                            <td>{{$item->tanggal}}</td>
                            <td>{{$item->jam_mulai}} - {{$item->jam_selesai}}</td>
                            <td>{!!wordwrap($item->deskripsi,100,"<br>")!!}</td>
                            <td>{{$item->menit}}</td>
                            <td>{{$item->validator}}</td>
                        </tr>
                        @endforeach
                        <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif; font-weight:bold">
                            <td colspan="4" class="text-right">Total</td>
                            <td>{{$aktivitas_ditolak->sum('menit')}} Menit</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')


@endpush