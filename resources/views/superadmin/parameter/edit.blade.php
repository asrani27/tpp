@extends('layouts.app')

@push('css')

@endpush
@section('title')
    SUPERADMIN
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">        
        <a href="/superadmin/parameter" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-alt-circle-left"></i> Kembali</a><br/><br/>
        <div class="row">
            <div class="col-lg-12 col-12">             
                <div class="card card-info">
                    <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-graduation-cap"></i> Edit Parameter</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form class="form-horizontal" method="POST" action="/superadmin/parameter/edit/{{$data->id}}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-2 col-form-label">Nama Pangkat</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="persentase_tpp" value="{{ $data->persentase_tpp }}" required>
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