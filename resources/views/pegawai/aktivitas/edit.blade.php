@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    
@endpush

@section('title')
    TAMBAH AKTIVITAS HARIAN
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
      <a href="/pegawai/aktivitas/harian" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-alt-circle-left"></i> Kembali</a><br/><br/>
      <div class="card card-info">
        <div class="card-header">
        <h3 class="card-title"><i class="fas fa-graduation-cap"></i> Edit Aktivitas</h3>
        </div>
        <!-- form start -->
        <form class="form-horizontal" method="POST" action="/pegawai/aktivitas/harian/edit/{{$data->id}}">
        @csrf
          <div class="card-body">
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Tanggal</label>
                <div class="col-sm-10">
                  
                  <input type="date" class="form-control" name="tanggal" placeholder="" value="{{$data->tanggal}}">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Kegiatan</label>
                <div class="col-sm-10">
                  <select name="skp_id" class="form-control select2" required>
                    <option value="">-kegiatan-</option>
                    @foreach ($skp as $item)
                    <option value="{{$item->id}}" {{$item->id == $data->skp_id ? 'selected':''}}>{{$item->deskripsi}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nama Aktivitas</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="deskripsi" placeholder="Nama Aktivitas" value="{{$data->deskripsi}}">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Jam Mulai</label>
                <div class="col-sm-2">
                  <input type="time" class="form-control" placeholder="0" name="jam_mulai" required value="{{$data->jam_mulai}}">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Jam Selesai</label>
                <div class="col-sm-2">
                  <input type="time" class="form-control" placeholder="0" name="jam_selesai" required value="{{$data->jam_selesai}}">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Dokumen Pendukung</label>
                <div class="col-sm-10 custom-file">
                    <input type="file" class="custom-file-input" name="file" id="customFile">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
              </div>
          </div>
          
          <div class="card-footer">
              <button type="submit" class="btn btn-block btn-info">Update</button>
          </div>
        </form>
    </div>
    
    </div>
</div>
@endsection

@push('js')

<!-- Select2 -->
<script src="/theme/plugins/select2/js/select2.full.min.js"></script>
<script>
    $(function () {
      //Initialize Select2 Elements
      $('.select2').select2()
  
      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })
    })
</script>
<!-- bs-custom-file-input -->
<script src="/theme/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
      bsCustomFileInput.init();
    });
</script>

@include('helper.hanya_angka')
@endpush