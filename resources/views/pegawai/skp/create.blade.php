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
                    <h3 class="card-title"><i class="fas fa-graduation-cap"></i> Tambah Rencana Kegiatan</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form class="form-horizontal" method="POST" action="/pegawai/skp/rencana-kegiatan/add">
                        @csrf
                    <div class="card-body">
                        <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Tahun</label>
                        <div class="col-sm-10">
                            <select name="tahun" class="form-control">
                                <option value="2021" {{\Carbon\Carbon::now()->format('Y') == '2021' ? 'selected' :''}}>2021</option>
                                <option value="2022" {{\Carbon\Carbon::now()->format('Y') == '2022' ? 'selected' :''}}>2022</option>
                                <option value="2023" {{\Carbon\Carbon::now()->format('Y') == '2023' ? 'selected' :''}}>2023</option>
                                <option value="2024" {{\Carbon\Carbon::now()->format('Y') == '2024' ? 'selected' :''}}>2024</option>
                                <option value="2025" {{\Carbon\Carbon::now()->format('Y') == '2025' ? 'selected' :''}}>2025</option>
                            </select>
                        </div>
                        </div>
                        <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Deskripsi</label>
                        <div class="col-sm-10">
                            <textarea class="form-control"  name="deskripsi" rows="3" required></textarea>
                        </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">Simpan</button>
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