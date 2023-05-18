@extends('layouts.app')

@push('css')

@endpush
@section('title')
<strong>Daftar Bapintar</strong>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12 text-center">
    <h2>DAFTAR BAPINTAR TERLEBIH DAHULU SEBELUM ISI AKTIVITAS</h2>
    Pengguna Android bisa download dan install di <a href="https://play.google.com/store/apps/details?id=com.banjarmasinkota.app_bapintar_diskominfotik&hl=id">https://play.google.com/store/apps/details?id=com.banjarmasinkota.app_bapintar_diskominfotik&hl=id</a><br/>
    Pengguna Iphone dan Komputer bisa daftar di link <a href="https://bapintar.banjarmasinkota.go.id/register">https://bapintar.banjarmasinkota.go.id/register</a><br/>
    <img src="/logo/bapintar.png" width="30%" height="250px">
    <h3>SUDAH DAFTAR DI BANJARMASIN PINTAR ? <BR/>SILAHKAN ISI EMAIL YANG TELAH DI DAFTARKAN DI BANJARMASIN PINTAR DAN KLIK CHECK :</h3>
   
  </div>
</div>
<form method="post" action="/checkbapintar">
    @csrf
    <div class="row">
        <div class="col-md-12 text-center">
          <input type="email" class="form-control" name="email" required><br/>
          <button class="btn btn-lg btn-block btn-primary">CHECK</button>
        </div>
      </div>
</form>
@endsection

@push('js')

@endpush