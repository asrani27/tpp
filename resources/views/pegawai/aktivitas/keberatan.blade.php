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
            <div class="col-lg-12 col-12">
                <div class="card card-widget widget-user-2">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-info">
                      <div class="widget-user-image">
                        <img class="img-circle elevation-2" src="/login_tpp/images/icons/logo.png" alt="User Avatar">
                      </div>
                      <!-- /.widget-user-image -->
                      <h3 class="widget-user-username">Daftar Aktivitas Yang Di Tolak</h3>
                      <h5 class="widget-user-desc">Anda Dapat Mengajukan Keberatan</h5>
                    </div>
                    
                  </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @foreach ($data as $item)
                    <div class="callout callout-danger">
                        <div class="row">
                            <div class="col-8 text-xs">Menit Kerja : {{$item->menit == null ? 0 : $item->menit}}</div>
                            <div class="col-4 text-xs"><i class="fas fa-calendar-alt"></i> {{\Carbon\Carbon::parse($item->tanggal)->format('d M Y')}} {{\Carbon\Carbon::createFromFormat('H:i:s',$item->jam_mulai)->format('H:i')}} - {{\Carbon\Carbon::createFromFormat('H:i:s',$item->jam_selesai)->format('H:i')}}</div>
                        </div>
                    
                    <h5><b>{{$item->deskripsi}}</b></h5>
                    
                    <div class="row">
                        <div class="col-4 text-xs">
                            <a href="/pegawai/aktivitas/keberatan/{{$item->id}}" class="btn btn-xs btn-danger text-white" data-toggle="tooltip" title="Ajukan Keberatan"  onclick="return confirm('Yakin?');"><i class="fas fa-hand-paper"></i> Ajukan Keberatan</a>
                        </div>
                        </div>
                    </div>
                @endforeach
                {{$data->links()}}
            </div>
        </div>
        <br />
        
    </div>
</div>
@endsection

@push('js')

@endpush