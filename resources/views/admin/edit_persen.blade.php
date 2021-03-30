@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
@endpush
@section('title')
    ADMIN SKPD {{strtoupper(Auth::user()->name)}}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <a href="/home/admin" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-alt-circle-left"></i> Kembali</a>
        <br/><br/>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- /.card-header -->
                    <form method="POST" action="/home/admin/persen">
                      @csrf
                    <div class="card-body table-responsive p-0">
                      <table class="table table-sm table-bordered">
                        <thead>
                          <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif" class="text-center bg-gradient-primary">
                            <th style="width: 10px">#</th>
                            <th>Nama /NIP/Pangkat/Golongan</th>
                            <th>Jenis Jabatan</th>
                            <th>Tambahan Persentase TPP</th>
                          </tr>
                        </thead>
                        @php
                            $no=1;
                             $count = $data->count();
                        @endphp
                        
                        <tbody>
                          @foreach ($data as $key => $item)
                          <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                            <td>{{$no++}}</td>
                            <td>
                              <input type="hidden" name="jabatan_id[]" value="{{$item->id}}">
                                {{$item->nama}}
                            </td>
                            <td class="text-center">
                              <select name="jenis_jabatan[]" class="form-control form-control-sm">
                                  <option value="">-pilih-</option>
                                  <option value="struktural" {{$item->jenis_jabatan == 'struktural' ? 'selected':''}}>Struktural</option>
                                  <option value="jfu" {{$item->jenis_jabatan == 'jfu' ? 'selected':''}}>JFU</option>
                                  <option value="jft" {{$item->jenis_jabatan == 'jft' ? 'selected':''}}>JFT</option>
                              </select>
                            </td>
                            <td class="text-center">
                              <input type="text" name="tambahan_persen_tpp[]" class="form-control form-control-sm" value="{{$item->tambahan_persen_tpp}}">
                            </td>
                          </tr>
                          @endforeach
                          
                        </tbody>
                        
                      </table>
                    </div>
                    <button type="submit" class="btn btn-block btn-success"><i class="fas fa-save"></i> Simpan</button>
                    <!-- /.card-body -->
                    </form>
                  </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

@endpush