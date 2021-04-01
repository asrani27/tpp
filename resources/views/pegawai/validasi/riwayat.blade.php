@extends('layouts.app')

@push('css')
    
@endpush

@section('title')
    RIWAYAT VALIDASI AKTIVITAS
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Total : {{$data->total()}} Validasi Aktivitas</h3>
        
                        <div class="card-tools">
                          <form method="get" action="/pegawai/validasi/riwayat">
                          <div class="input-group input-group-sm" style="width: 300px;">
                            <input type="text" name="search" class="form-control input-sm float-right" value="{{old('search')}}" placeholder="Cari">
        
                            <div class="input-group-append">
                              <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                          </div>
                          </form>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap table-sm">
                        <thead>
                            <tr>
                            <th>#</th>
                            <th>Nama ASN</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Menit</th>
                            <th>Aktivitas</th>
                            <th>Status</th>
                            </tr>
                        </thead>
                        @php
                            $no =1;
                        @endphp
                        <tbody>
                        @foreach ($data as $key => $item)
                                <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif">
                                <td>{{$key+ $data->firstItem()}}</td>
                                <td>{{$item->pegawai->nama}}</td>
                                <td>{{\Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMMM Y')}}</td>
                                <td>{{\Carbon\Carbon::parse($item->jam_mulai)->format('H:i')}} - {{\Carbon\Carbon::parse($item->jam_selesai)->format('H:i')}}</td>
                                <td>{{$item->menit}}</td>
                                <td>{{$item->deskripsi}}</td>
                                <td>
                                    @if ($item->validasi == 0)
                                    <span class="badge bg-info"><i class="fas fa-clock"></i> Diproses</span>
                                    @elseif ($item->validasi == 1)
                                    <span class="badge bg-success"><i class="fas fa-check"></i> Disetujui</span>
                                    
                                    @else
                                    <span class="badge bg-danger"><i class="fas fa-times"></i> Ditolak</span>
                                        
                                    @endif
                                </td>
                                </tr>
                        @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
                {{$data->links()}}
            </div>
        </div>
        <br />
        
    </div>
</div>
@endsection

@push('js')

@endpush