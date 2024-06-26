@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

<!-- Timepicker -->
<script src="/theme/plugins/moment/moment.min.js"></script>
<link rel="stylesheet" href="/theme/plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="/theme/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

@endpush

@section('title')
TAMBAH AKTIVITAS HARIAN
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <a href="/pegawai/aktivitas/harian" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-alt-circle-left"></i>
      Kembali</a><br /><br />
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-graduation-cap"></i> Tambah Aktivitas</h3>
      </div>
      <!-- form start -->
      <form id="aktivitas" class="form-horizontal" method="POST" action="/pegawai/aktivitas/add">
        @csrf
        <div class="card-body">
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tanggal</label>
            <div class="col-sm-10">

              @if (Auth::user()->username == '197508312010011005' || Auth::user()->username == '198707242010011009' || Auth::user()->username == '197901192011012002')
              <input type="date" class="form-control" name="tanggal" placeholder="" value="{{$tanggal}}"
                max="{{\Carbon\Carbon::today()->format('Y-m-d')}}">
              @else
              {{-- <input type="date" class="form-control" name="tanggal" placeholder="" value="{{$tanggal}}"
              min="2023-12-01"
              max="2024-01-31"> --}}

              <input type="date" class="form-control" name="tanggal" placeholder="" value="{{$tanggal}}"
                min="{{\Carbon\Carbon::today()->subdays(1)->format('Y-m-d')}}"
                max="{{\Carbon\Carbon::today()->format('Y-m-d')}}">
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Sasaran kinerja</label>
            <div class="col-sm-10">
              <select name="skp2023_id" class="form-control select2" required>
                <option value="">-</option>
                @foreach ($skp as $item)
                <option value="{{$item->id}}" {{$item->id == old('skp_id') ? 'selected':''}}>{{$item->rhk}}
                </option>
                @endforeach
              </select>
            </div>
          </div>
          @if ($eselon == 'IV' || $eselon == 'III' || $eselon == 'II')
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Rencana Aksi</label>
            <div class="col-sm-10">
              <select name="rencana_aksi" class="form-control select2">
                <option value="">-</option>
                @foreach ($rencana_aksi as $item)
                <option value="{{$item->keterangan}}" {{$item->keterangan == old('rencana_aksi') ? 'selected':''}}>TW : {{$item->triwulan}} - {{$item->keterangan}}
                </option>
                
                </option>
                @endforeach
              </select>
            </div>
          </div>
          @endif
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nama Aktivitas</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="deskripsi" placeholder="Nama Aktivitas"
                value="{{old('deskripsi')}}" required>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Jam Mulai</label>

            <div class="col-sm-2">
              <div class="input-group date" id="timepicker" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#timepicker" name="jam_mulai"
                  required value="{{$jam_mulai}}">
                <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                </div>
              </div>
            </div>
            <!-- /.input group -->
          </div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Jam Selesai</label>

            <div class="col-sm-2">
              <div class="input-group date" id="timepicker2" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#timepicker2"
                  name="jam_selesai" required value="{{$jam_selesai}}">
                <div class="input-group-append" data-target="#timepicker2" data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                </div>
              </div>
            </div>
            <!-- /.input group -->
          </div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Dokumen Pendukung</label>
            <div class="col-sm-10 custom-file">
              <input type="file" class="custom-file-input" name="file" id="customFile" disabled>
              <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-block btn-info btnSubmit"><i class="fas fa-save"></i> Simpan</button>
        </div>
      </form>
    </div>

  </div>
</div>
@endsection

@push('js')
<script>
  $(document).ready(function () {
        $("#aktivitas").submit(function () {
        $(".btnSubmit").attr("disabled", true);
        return true;
        });
    });
</script>

<!-- Timepicker -->
<script src="/theme/plugins/moment/moment.min.js"></script>
<script src="/theme/plugins/daterangepicker/daterangepicker.js"></script>
<script src="/theme/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

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

    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'HH:mm',
      //format: 'hh:mm',
    })
    $('#timepicker2').datetimepicker({
      format: 'HH:mm',
      //format: 'hh:mm',
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