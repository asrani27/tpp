@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
SUPERADMIN
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">UPLOAD BPJS BULAN {{convertBulan($bulan)}} {{$tahun}} </h3>
            </div>
            <div class="card-body p-2">
                <div class="alert alert-default alert-dismissible text-sm">
                    <h5><i class="icon fas fa-info"></i> Informasi</h5>
                    1. File harus berupa Excel<br />
                    2. File Excel Berasal Dari Keuangan<br />
                    3. Data yang di ambil dari CELL T8 (untuk Bpjs 1%)<br />
                    4. Data yang di ambil dari CELL U8 (untuk Bpjs 4%)<br />
                    5. Data Yang di ambil dari sheet pertama
                </div>
                <form method="post" action="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/bpjs"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" class="form-control" required><br />
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}" class="btn btn-sm btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')

@endpush