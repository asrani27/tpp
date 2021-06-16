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
            <div class="col-lg-6 col-12">
                <div class="card card-widget widget-user-2">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-gradient-blue">
                      <div class="widget-user-image">
                        <img class="img-circle elevation-2" src="/login_tpp/images/icons/logo.png" alt="User Avatar">
                      </div>
                      <!-- /.widget-user-image -->
                      @if (Auth::user()->pegawai->jabatan->sekda == 1)
                          
                        <h3 class="widget-user-username">WALIKOTA</h3>
                        <h5 class="widget-user-desc">KOTA BANJARMASIN</h5>
                      @else
                        <h3 class="widget-user-username">
                            @if ($atasan->pegawai == null)
                                @if ($atasan->pegawaiplt == null)
                                    -
                                @else
                                    {{$atasan->pegawaiplt->nama}}
                                @endif
                            @else
                                {{$atasan->pegawai->nama}}
                            @endif
                        </h3>                          
                        {{-- <h3 class="widget-user-username">{{$atasan->pegawai == null ? '-': $atasan->pegawai->nama}}</h3> --}}
                        <h5 class="widget-user-desc">{{$atasan->nama}}</h5>
                      @endif
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="card card-widget widget-user-2">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-gradient-purple">
                      <div class="widget-user-image">
                        <img class="img-circle elevation-2" src="/login_tpp/images/icons/logo.png" alt="User Avatar">
                      </div>
                      <!-- /.widget-user-image -->
                      <h3 class="widget-user-username">{{Auth::user()->pegawai->nama}}</h3>
                      <h5 class="widget-user-desc">{{Auth::user()->pegawai->jabatan->nama}}</h5>
                    </div>
                    
                </div>
            </div>
            
        </div>
        <div class="row">
            <div class="col-12">
                <a href="/pegawai/aktivitas/add" class="btn btn-success btn-block"><i class="fas fa-plus"></i> Tambah Aktivitas</a>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Total : {{$data->total()}} Aktivitas</h3>
        
                        <div class="card-tools">
                          {{-- <form method="get" action="/pegawai/skp/rencana-kegiatan/search">
                          <div class="input-group input-group-sm" style="width: 300px;">
                            <input type="text" name="search" class="form-control input-sm float-right" value="{{old('search')}}" placeholder="Cari">
        
                            <div class="input-group-append">
                              <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                          </div>
                          </form> --}}
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
                            <th></th>
                            </tr>
                        </thead>
                        @php
                            $no =1;
                        @endphp
                        <tbody>
                        @foreach ($data as $key => $item)
                                <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif">
                                <td>{{$key+ $data->firstItem()}}</td>
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
                                <td>
                                    @if ($item->validasi == 0)
                                    <a href="/pegawai/aktivitas/harian/edit/{{$item->id}}" class="btn btn-xs btn-success text-white" data-toggle="tooltip" title="edit data"><i class="fas fa-edit"></i></a>
                                    <a href="/pegawai/aktivitas/harian/delete/{{$item->id}}" class="btn btn-xs btn-danger text-white" data-toggle="tooltip" title="hapus data"  onclick="return confirm('Yakin ingin di hapus?');"><i class="fas fa-trash"></i></a>
                                    @else
                                    
                                    @endif
                                </td>
                                </tr>
                        @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
                {{-- @foreach ($data as $item)
                    <div class="callout callout-info">
                        <div class="row">
                            <div class="col-8 text-xs">Menit Kerja : {{$item->menit == null ? 0 : $item->menit}}</div>
                            <div class="col-4 text-xs"><i class="fas fa-calendar-alt"></i> {{\Carbon\Carbon::parse($item->tanggal)->format('d M Y')}} {{\Carbon\Carbon::createFromFormat('H:i:s',$item->jam_mulai)->format('H:i')}} - {{\Carbon\Carbon::createFromFormat('H:i:s',$item->jam_selesai)->format('H:i')}}</div>
                        </div>
                    
                    <h5><b>{{$item->deskripsi}}</b></h5>
                    
                    <div class="row">
                        <div class="col-8 text-xs">
                            Status : 
                            @if ($item->validasi == 0)
                            <span data-toggle="tooltip" title="3 New Messages" class="badge badge-info"><i class="fas fa-clock"></i></span>  Belum Di Validasi
                            @elseif($item->validasi == 1)
                            <span data-toggle="tooltip" title="3 New Messages" class="badge badge-success"><i class="fas fa-check"></i></span> Di setujui
                            @else
                                
                            <span data-toggle="tooltip" title="3 New Messages" class="badge badge-danger"><i class="fas fa-times"></i></span> Di Tolak    
                            @endif
                        </div>
                        <div class="col-4 text-xs">
                            
                            @if ($item->validasi == 0)
                            <a href="/pegawai/aktivitas/harian/edit/{{$item->id}}" class="btn btn-xs btn-success text-white" data-toggle="tooltip" title="edit data"><i class="fas fa-edit"></i></a>
                            <a href="/pegawai/aktivitas/harian/delete/{{$item->id}}" class="btn btn-xs btn-danger text-white" data-toggle="tooltip" title="hapus data"  onclick="return confirm('Yakin ingin di hapus?');"><i class="fas fa-trash"></i></a>
                            @else
                              
                            @endif
                        </div>
                        </div>
                    </div>
                @endforeach --}}
                {{$data->links()}}
            </div>
        </div>
        <br />
        
    </div>
</div>
@endsection

@push('js')

@endpush