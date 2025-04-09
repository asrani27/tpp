@extends('layouts.app')

@push('css')

@endpush
@section('title')
<strong>TPP ASN</strong>
@endsection
@section('content')
<div class="row mb-2">
  <div class="col-sm-12">
    <h4 class="m-0 text-dark">Selamat Datang Di Aplikasi TPP ASN Kota Banjarmasin</h4>
  </div>
</div>
<div class="row">
  <div class="col-lg-12 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-primary"><i class="far fa-user"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Wali Kota Banjarmasin</span>
        <span class="info-box-number">H. Muhammad Yamin HR</span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <h4>PENILAIAN SKP PEGAWAI</h4>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">List Pejabat Di Nilai</h3>

            <div class="card-tools">
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap table-sm">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nama, NIP</th>
                  <th>Periode</th>
                  <th>Penilaian</th>
                </tr>
              </thead>
              @php
              $no =1;
              @endphp
              <tbody>
                @foreach ($data as $key => $item)
                <tr>
                  <td>{{$key+ 1}}</td>
                  <td>
                    {{json_decode($item->pn)->nama}}<br />
                    {{json_decode($item->pn)->jabatan}}
                  </td>
                  <td>{{\Carbon\Carbon::parse($item->mulai)->isoFormat('D MMMM Y')}} s/d
                    {{\Carbon\Carbon::parse($item->sampai)->isoFormat('D MMMM Y')}}</td>

                  <td>
                    <a href="/walikota/nilai-skp/ekspektasi/{{$item->id}}" class="btn btn-xs btn-success">EKSPEKTASI</a>
                    <a href="/walikota/nilai-skp/triwulan/1/{{$item->id}}" class="btn btn-xs btn-success">TRIWULAN 1 :
                      {{$item->nilai_tw1}}</a>
                    <a href="/walikota/nilai-skp/triwulan/2/{{$item->id}}" class="btn btn-xs btn-success">TRIWULAN 2 :
                      {{$item->nilai_tw2}}</a>
                    <a href="/walikota/nilai-skp/triwulan/3/{{$item->id}}" class="btn btn-xs btn-success">TRIWULAN 3 :
                      {{$item->nilai_tw3}}</a>
                    <a href="/walikota/nilai-skp/triwulan/4/{{$item->id}}" class="btn btn-xs btn-success">TRIWULAN 4 :
                      {{$item->nilai_tw4}}</a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')

@endpush