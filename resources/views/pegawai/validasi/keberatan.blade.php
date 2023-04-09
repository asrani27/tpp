@extends('layouts.app')

@push('css')
    
@endpush

@section('title')
    VALIDASI KEBERATAN
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Data Pengajuan Keberatan</h3>
                </div>
                <div class="card-body table-responsive p-0">
                  <table class="table table-sm">
                    <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama</th>
                      <th>Aktivitas</th>
                      <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                      @php
                          $no=1;
                      @endphp
                    @foreach ($data as $item)
                        
                    <tr>
                      <td width="10px">{{$no++}}</td>
                      <td>
                        {{$item->pegawai == null ? '-': $item->pegawai->nama}}
                      </td>
                      <td>
                        {{$item->deskripsi}}
                      </td>
                      
                      <td>

                        <a href="/pegawai/validasi/keberatan/setujui/{{$item->id}}" class="btn btn-xs btn-success">
                          <i class="fas fa-check"></i> Setujui </a>
                        <a href="/pegawai/validasi/keberatan/tolak/{{$item->id}}" class="btn btn-xs btn-danger">
                          <i class="fas fa-times"></i> Tolak</a>
                      </td>
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