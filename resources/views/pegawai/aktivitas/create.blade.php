@extends('layouts.app')

@push('css')
    
@endpush

@section('title')
    TAMBAH JURNAL AKTIVITAS
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-12">
                <form class="form-horizontal">
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                          <input type="date" class="form-control" placeholder="">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nama Aktivitas</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" placeholder="Nama Aktivitas">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Beban Kerja</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" placeholder="Beban Kerja">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Dokumen Pendukung</label>
                        <div class="col-sm-10 custom-file">
                            <input type="file" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Deskripsi</label>
                        <div class="col-sm-10">
                            <textarea rows=3 class="form-control"></textarea>
                        </div>
                      </div>
                      
                      
                      <button type="submit" class="btn btn-info btn-block">SIMPAN</button>
                      
                </form>
            </div>
        </div>
        
    </div>
</div>
@endsection

@push('js')
<!-- bs-custom-file-input -->
<script src="/theme/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
      bsCustomFileInput.init();
    });
</script>
@endpush