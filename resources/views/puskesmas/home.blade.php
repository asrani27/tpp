@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css"
    rel="stylesheet" />
@endpush
@section('title')
ADMIN {{strtoupper(Auth::user()->name)}}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="row">
        </div>
        <div class="row">
            <div class="col-12 text-center">

                <a href="/admin/rekapitulasi" class="btn btn-info"><i class="fas fa-file"></i>Rekap Data</a>
                <a href="/home/admin/persen" class="btn btn-primary"><i class="fas fa-percent"></i> Edit Persen TPP</a>
                {{-- <a href="/admin/presensi" class="btn btn-primary"><i class="fas fa-clock"></i> Edit Presensi</a>
                --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

@endpush