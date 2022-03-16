@extends('layouts.app')

@push('css')

@endpush

@section('title')
JURNAL AKTIVITAS
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">

        <div class="row">
            <div class="col-12">
                <a href="/pegawai/aktivitas/harian" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i>
                    Kembali</a>
            </div>
        </div>
        <br />

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Aktivitas Bulan {{convertBulan($bulan)}} {{$tahun}}</h3>

                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Menit</th>
                                    <th>Aktivitas</th>
                                    <th>Status</th>
                                    <th>Di nilai Oleh</th>
                                    <th></th>
                                </tr>
                            </thead>
                            @php
                            $no =1;
                            @endphp
                            <tbody>
                                @foreach ($data as $key => $item)
                                <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif">
                                    <td>{{$no++}}</td>
                                    <td>{{\Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMMM Y')}}</td>
                                    <td>{{\Carbon\Carbon::parse($item->jam_mulai)->format('H:i')}} -
                                        {{\Carbon\Carbon::parse($item->jam_selesai)->format('H:i')}}</td>
                                    <td>{{$item->menit}}</td>
                                    <td>{!!wordwrap($item->deskripsi,100,"<br>")!!}</td>
                                    <td>
                                        @if ($item->validasi == 0)
                                        <span class="badge bg-info"><i class="fas fa-clock"></i> Diproses</span>
                                        @elseif ($item->validasi == 1)
                                        <span class="badge bg-success"><i class="fas fa-check"></i> Disetujui</span>

                                        @else
                                        <span class="badge bg-danger"><i class="fas fa-times"></i> Ditolak</span>

                                        @endif
                                    </td>
                                    <td>{{$item->validator == null ? null : $item->penilai->nama}}</td>
                                    <td>
                                        @if ($item->validasi == 0)
                                        <a href="/pegawai/aktivitas/harian/edit/{{$item->id}}"
                                            class="btn btn-xs btn-success text-white" data-toggle="tooltip"
                                            title="edit data"><i class="fas fa-edit"></i></a>
                                        <a href="/pegawai/aktivitas/harian/delete/{{$item->id}}"
                                            class="btn btn-xs btn-danger text-white" data-toggle="tooltip"
                                            title="hapus data" onclick="return confirm('Yakin ingin di hapus?');"><i
                                                class="fas fa-trash"></i></a>
                                        @else
                                        @if (Auth::user()->username == '198709162010012005')
                                        <a href="/pegawai/aktivitas/harian/delete/{{$item->id}}"
                                            class="btn btn-xs btn-danger text-white" data-toggle="tooltip"
                                            title="hapus data" onclick="return confirm('Yakin ingin di hapus?');"><i
                                                class="fas fa-trash"></i></a>
                                        @endif
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
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