@extends('layouts.app')

@push('css')
  <link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
    ADMIN SKPD
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">        
        <a href="/admin/pegawai" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-alt-circle-left"></i> Kembali</a><br/><br/>
        <div class="row">
            <div class="col-lg-12 col-12">             
                <div class="card card-info">
                    <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-graduation-cap"></i> Edit ASN</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form class="form-horizontal" method="POST" action="/admin/pegawai/edit/{{$data->id}}">
                        @csrf
                    <div class="card-body">
                        <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">NIP</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nip" value="{{$data->nip}}" placeholder="NIP" required  minlength="18" maxlength="18">
                        
                            @if ($errors->has('nip'))
                                <span class="text-danger">{{ $errors->first('nip') }}</span>
                            @endif
                        </div>
                        </div>

                        <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Nama ASN</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama" value="{{$data->nama}}" placeholder="Agung Saptoto, M.Kom" required>
                        </div>
                        </div>
                        

                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label">Tanggal lahir</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" name="tanggal_lahir"  value="{{$data->tanggal_lahir}}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label">Eselon</label>
                            <div class="col-sm-10">
                                <select name="eselon_id" class="form-control">
                                    <option value="">-pilih-</option>
                                    @foreach (eselon() as $item)
                                        <option value="{{$item->id}}" {{$item->id == $data->eselon_id ? 'selected': ''}}>{{$item->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label">Pangkat</label>
                            <div class="col-sm-10">
                                <select name="pangkat_id" class="form-control">
                                    <option value="">-pilih-</option>
                                    @foreach (pangkat() as $item)
                                        <option value="{{$item->id}}" {{$item->id == $data->pangkat_id ? 'selected': ''}}>{{$item->nama}} ({{$item->golongan}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label">Jabatan</label>
                            <div class="col-sm-10">
                                <select name="jabatan_id" class="form-control" required>
                                    <option value="">-pilih-</option>
                                    @foreach (jabatan(Auth::user()->skpd->id) as $item)
                                        <option value="{{$item->id}}" {{$item->id == $data->jabatan_id ? 'selected':''}}>{{$item->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">Update</button>
                    </div>
                    <!-- /.card-footer -->
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection

@push('js')


@endpush