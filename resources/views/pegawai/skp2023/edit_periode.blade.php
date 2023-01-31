@extends('layouts.app')

@push('css')
  <link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush
@section('title')
    PEGAWAI
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <h4>EDIT PERIODE SKP</h4>
        <div class="row">
            <div class="col-12">
              <form method="POST" action="/pegawai/new-skp/periode/edit/{{$data->id}}">
                @csrf
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-3 col-12">
                      <label class="col-form-label" for="inputWarning"><i class="far fa-calendar"></i> Periode Mulai</label>
                      
                      <input type="text" name="mulai" class="form-control" id="periodemulai" required autocomplete="off" value="{{\Carbon\Carbon::parse($data->mulai)->format('d/m/Y')}}">
                      {{-- <input type="date" name="mulai" class="form-control" placeholder=".col-3" value="{{$data->mulai}}" required> --}}
                    </div>
                    <div class="col-md-3 col-12">
                      <label class="col-form-label" for="inputWarning"><i class="far fa-calendar"></i> Periode Selesai</label>
                      <input type="text" name="sampai" class="form-control" id="periodesampai" required autocomplete="off" value="{{\Carbon\Carbon::parse($data->sampai)->format('d/m/Y')}}">
                    </div>
                    <div class="col-md-2 col-12">
                      <label class="col-form-label" for="inputWarning"><i class="far fa-calendar"></i> Jenis</label>
                      <select name="jenis" class="form-control">
                        <option value="">-pilih-</option>
                        <option value="JPT" {{$data->jenis == 'JPT' ? 'selected':''}}>JPT</option>
                        <option value="JF" {{$data->jenis == 'JF' ? 'selected':''}}>JF</option>
                        <option value="JA" {{$data->jenis == 'JA' ? 'selected':''}}>JA</option>
                      </select>
                    </div>
                    <div class="col-md-4 col-12">
                      <label class="col-form-label" for="inputWarning"><i class="far fa-user"></i> </label><br />
                      <button class="btn btn-primary" type="submit">Update Periode</button>
                      <a href="/pegawai/new-skp" class="btn btn-secondary" type="submit">Kembali</a>
                    </div>
                  </div>
                </div>
              </div>
              </form>
              {{-- <a href="/pegawai/skp/rencana-kegiatan/add" class="btn btn-sm btn-primary"><i class="fas fa-users"></i> Tambah SKP</a> --}}
              
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $( function() {
    $( "#periodemulai" ).datepicker(
      { dateFormat: 'dd/mm/yy' }
    );
  } );
  $( function() {
    $( "#periodesampai" ).datepicker(
      { dateFormat: 'dd/mm/yy' }
    );
  } );
</script>

@endpush