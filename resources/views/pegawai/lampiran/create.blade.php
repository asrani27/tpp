@extends('layouts.app')

@push('css')
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

<!-- Datepicker -->
<script src="/theme/plugins/moment/moment.min.js"></script>
<link rel="stylesheet" href="/theme/plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="/theme/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

@endpush

@section('title')
TAMBAH LAMPIRAN WFH
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <a href="/pegawai/lampiran/wfh" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-alt-circle-left"></i>
            Kembali</a><br /><br />
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-paperclip"></i> Tambah Lampiran WFH</h3>
            </div>
            <!-- form start -->
            <form id="lampiran" class="form-horizontal" method="POST" action="/pegawai/lampiran/wfh">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tanggal"
                                value="{{ old('tanggal', Carbon\Carbon::today()->format('Y-m-d')) }}"
                                max="{{ Carbon\Carbon::today()->format('Y-m-d') }}" required>
                            @error('tanggal')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">URL Google Drive</label>
                        <div class="col-sm-10">
                            <input type="url" class="form-control" name="url_google_drive"
                                placeholder="https://drive.google.com/..."
                                value="{{ old('url_google_drive') }}" required>
                            <small class="text-muted">Masukkan link folder Google Drive yang berisi lampiran WFH</small>
                            @error('url_google_drive')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-block btn-info btnSubmit"><i class="fas fa-save"></i>
                        Simpan</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function () {
        $("#lampiran").submit(function () {
            var selectedDate = new Date($("input[name='tanggal']").val());
            // 5 = Friday (0 = Sunday, 1 = Monday, ..., 5 = Friday, 6 = Saturday)
            if (selectedDate.getDay() !== 5) {
                alert('Hanya dapat memilih hari Jumat!');
                return false;
            }
            $(".btnSubmit").attr("disabled", true);
            return true;
        });
    });
</script>

<!-- Datepicker -->
<script src="/theme/plugins/moment/moment.min.js"></script>
<script src="/theme/plugins/daterangepicker/daterangepicker.js"></script>
<script src="/theme/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
@endpush
