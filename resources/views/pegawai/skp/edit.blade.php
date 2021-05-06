@extends('layouts.app')

@push('css')
  <link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
    PEGAWAI
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">        
        <a href="/pegawai/skp/rencana-kegiatan" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-alt-circle-left"></i> Kembali</a><br/><br/>
        <div class="row">
            <div class="col-lg-12 col-12">             
                <div class="card card-info">
                    <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-graduation-cap"></i> Edit Rencana Kegiatan</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form class="form-horizontal" method="POST" action="/pegawai/skp/rencana-kegiatan/edit/{{$data->id}}/{{$periode_id}}">
                        @csrf
                    <div class="card-body">
                        <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label text-right">Tahun</label>
                        <div class="col-sm-10">
                            <select name="tahun" class="form-control">
                                <option value="2021" {{$data->tahun == '2021' ? 'selected' :''}}>2021</option>
                                <option value="2022" {{$data->tahun == '2022' ? 'selected' :''}}>2022</option>
                                <option value="2023" {{$data->tahun == '2023' ? 'selected' :''}}>2023</option>
                                <option value="2024" {{$data->tahun == '2024' ? 'selected' :''}}>2024</option>
                                <option value="2025" {{$data->tahun == '2025' ? 'selected' :''}}>2025</option>
                            </select>
                        </div>
                        </div>
                        <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label text-right">Deskripsi Kegiatan</label>
                        <div class="col-sm-10">
                            <textarea class="form-control"  name="deskripsi" rows="3" required>{{$data->deskripsi}}</textarea>
                        </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label text-right">AK</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" name="ak" value="{{$data->ak}}">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label text-right">Kuant/Output</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" name="output" value="{{$data->output}}">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label text-right">Kual/Mutu</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" name="mutu" value="{{$data->mutu}}">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label text-right">Waktu</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" name="waktu" value="{{$data->waktu}}">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label text-right">Biaya</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" name="biaya" value="{{$data->biaya}}">
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