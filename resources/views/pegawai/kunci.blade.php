@extends('layouts.app')

@push('css')

@endpush
@section('title')
<strong>KUNCI AKTIVITAS</strong>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Penguncian Aktivitas</h3>
            </div>
            <div class="card-body">
                <i class="fas fa-lock"></i>: Terkunci, tidak bisa mengubah data<br />
                <i class="fas fa-unlock"></i>: Terbuka, dapat mengubah data
                <table class="table table-hover table-striped text-nowrap table-sm">
                    <thead>
                        <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif"
                            class="bg-gradient-primary">
                            <th>#</th>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    @php
                    $no =1;
                    @endphp
                    <tbody>
                        @foreach (bulanTahun() as $key => $item)
                        <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif">
                            <td>{{$no++}}</td>
                            <td>{{\Carbon\Carbon::createFromFormat('m',$item->bulan)->translatedFormat('F')}}</td>
                            <td>{{$item->tahun}}</td>
                            <td>{{lockBy(Auth::user()->pegawai->skpd_id,$item->bulan,$item->tahun)}}</td>
                            <td style="font-size: 16px">
                                @if (lockSkpd(Auth::user()->pegawai->skpd_id,$item->bulan,$item->tahun) == null)
                                <a href="/pegawai/penguncian/{{$item->bulan}}/{{$item->tahun}}/lock" style="color:black"
                                    onclick="return confirm('Yakin ingin dikunci?');"><i class="fas fa-unlock"></i></a>
                                @else
                                <a href="/pegawai/penguncian/{{$item->bulan}}/{{$item->tahun}}/unlock"><i
                                        class="fas fa-lock" onclick="return confirm('Yakin ingin dibuka?');"></i></a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
</div>
@endsection

@push('js')

@endpush