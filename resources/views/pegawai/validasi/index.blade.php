@extends('layouts.app')

@push('css')
    
@endpush

@section('title')
    VALIDASI AKTIVITAS
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header border-0">
                  <h3 class="card-title">Data ASN</h3>
                  <div class="card-tools">
                    <a href="#" class="btn btn-tool btn-sm">
                      <i class="fas fa-download"></i>
                    </a>
                    <a href="#" class="btn btn-tool btn-sm">
                      <i class="fas fa-bars"></i>
                    </a>
                  </div>
                </div>
                <div class="card-body table-responsive p-0">
                  <table class="table table-striped table-valign-middle">
                    <thead>
                    <tr>
                      <th>No</th>
                      <th></th>
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
                        <img src="https://p.kindpng.com/picc/s/78-786207_user-avatar-png-user-avatar-icon-png-transparent.png" alt="Product 1" class="img-circle img-size-64 mr-2">
                        
                      </td>
                      <td>
                        {{$item->pegawai == null ? '-': $item->pegawai->nama}} <br />
                        {{$item->nama}}
                      </td>
                      <td>
                        @if ($item->pegawai == null)
                            
                        @else
                        <button href="#" class="btn btn-sm bg-purple">
                          <i class="fas fa-user-edit"></i> <strong>{{$item->aktivitas_baru}} Aktivitas </strong>
                        </button>
                        @endif
                      </td>
                      <td>
                        @if ($item->pegawai == null)
                            
                        @else
                          <a href="/pegawai/validasi/harian/view/{{$item->id}}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> VIEW
                          </a>
                          <a href="/pegawai/validasi/harian/acc/{{$item->id}}" class="btn btn-sm btn-success">
                            <i class="fas fa-check"></i> ACC SEMUA
                          </a>
                        @endif
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