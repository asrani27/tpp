@extends('layouts.app')

@push('css')
    
@endpush

@section('title')
    DETAIL PEGAWAI
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="row text-center">
            <div class="col-12">
                
                <div class="btn-group">
                    <a href="/pegawai/verifikasi/detail" class="btn btn-success">Detail Pegawai</a>
                    <a href="/pegawai/verifikasi/jurnal" class="btn btn-default">Jurnal Aktivitas</a>
                </div>        
    
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-12">
                <form class="form-horizontal">
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nama Pegawai</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" placeholder="Annisa" readonly>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kode Jabatan</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" placeholder="1-00001" readonly>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kelas Jabatan</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" placeholder="12" readonly>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Pangkat / Golongan</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" placeholder="Pembina / IV A" readonly>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nama SKPD</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" placeholder="Sekretariat Daerah" readonly>
                        </div>
                      </div>                      
                </form>
            </div>
        </div>
    
    
    </div>
</div>
@endsection

@push('js')

@endpush