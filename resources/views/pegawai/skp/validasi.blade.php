@extends('layouts.app')

@push('css')
    
@endpush

@section('title')
    VALIDASI SKP PEGAWAI
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Data SKP Validasi</h3>
                </div>
                <div class="card-body table-responsive p-0">
                  <table class="table table-striped table-valign-middle table-sm">
                    <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama</th>
                      <th>SKP</th>
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
                        {{$item->pegawai == null ? '-': $item->pegawai->nama}} <br />
                        {{$item->nama}}
                      </td>
                      <td>
                        @if ($item->pegawai == null)
                            
                        @else
                        <button href="#" class="btn btn-xs bg-purple">
                          <i class="fas fa-user-edit"></i> <strong>{{$item->skp_baru}} SKP Baru </strong>
                        </button>
                        @endif
                      </td>
                      <td>
                        @if ($item->pegawai == null)
                            
                        @else
                          <a href="/pegawai/skp/validasi/view/{{$item->pegawai->id}}" class="btn btn-xs btn-info">
                            <i class="fas fa-eye"></i> VIEW
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