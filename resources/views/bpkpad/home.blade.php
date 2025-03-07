@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css"
  rel="stylesheet" />
@endpush
@section('title')
{{strtoupper(Auth::user()->name)}}
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">

    <div class="row">
      <div class="col-12 text-center">
        <h3>Rekap Konsolidasi TPP</h2>
          <br>
          <form method="post" action="bpkpad">
            @csrf
            <select class="form-control" name="bulan">
              <option value="01">Januari</option>
              <option value="02">Februari</option>
              <option value="03">Maret</option>
              <option value="04">April</option>
              <option value="05">Mei</option>
              <option value="06">Juni</option>
              <option value="07">Juli</option>
              <option value="08">Agustus</option>
              <option value="09">September</option>
              <option value="10">Oktober</option>
              <option value="11">November</option>
              <option value="12">Desember</option>
            </select>
            <br />
            <select class="form-control" name="tahun">
              <option value="2025">2025</option>
            </select>
            <br />
            <button type="submit" class="btn btn-primary btn-block">Export</button>
          </form>
      </div>
    </div>

  </div>
</div>
@endsection

@push('js')

@endpush