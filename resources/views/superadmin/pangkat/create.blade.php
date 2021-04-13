@extends('layouts.app')

@push('css')

@endpush
@section('title')
    SUPERADMIN
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">        
        <a href="/superadmin/pangkat" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-alt-circle-left"></i> Kembali</a><br/><br/>
        <div class="row">
            <div class="col-lg-12 col-12">             
                <div class="card card-info">
                    <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-graduation-cap"></i> Tambah Pangkat / Golongan</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form class="form-horizontal" method="POST" action="/superadmin/pangkat/add">
                        @csrf
                    <div class="card-body">
                        <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Nama Pangkat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama" value="{{ old('nama') }}" placeholder="Nama Pangkat" required>
                            @if ($errors->has('nama'))
                            <span class="text-danger">{{ $errors->first('nama') }}</span>
                            @endif
                        </div>
                        </div>
                        <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Golongan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="golongan"  value="{{ old('golongan') }}"placeholder="IV/b" required>
                            @if ($errors->has('golongan'))
                            <span class="text-danger">{{ $errors->first('golongan') }}</span>
                            @endif
                        </div>
                        </div>
                        <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">PPH 21</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="pph" required onkeypress="return hanyaAngka(event)" >
                            @if ($errors->has('pph'))
                            <span class="text-danger">{{ $errors->first('pph') }}</span>
                            @endif
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
@include('helper.hanya_angka')
@endpush