@extends('layouts.app')

@push('css')
    
@endpush

@section('title')
    JURNAL AKTIVITAS
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-12">
                <div class="input-group input-group">
                    <input type="text" class="form-control" placeholder="search">
                    <span class="input-group-append">
                      <button type="button" class="btn btn-info btn-flat"><i class="fas fa-search"></i></button>
                    </span>
                  </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-6">Bulan
                <select name="bulan" class="form-control">
                    <option value="">Januari</option>
                    <option value="">Februari</option>
                    <option value="">Maret</option>
                    <option value="">April</option>
                    <option value="">Mei</option>
                    <option value="">Juni</option>
                    <option value="">Juli</option>
                    <option value="">Agustus</option>
                    <option value="">September</option>
                    <option value="">Oktober</option>
                    <option value="">November</option>
                    <option value="">Desember</option>
                </select>
            </div>
            <div class="col-6">
                Tahun
                <select name="tahun" class="form-control">
                    <option value="">2021</option>
                    <option value="">2022</option>
                    <option value="">2023</option>
                </select>
            </div>            
        </div>
        <br />
        <div class="row">
            <div class="col-12">
                <div class="callout callout-danger">
                    <div class="row">
                        <div class="col-8 text-xs">Beban Kerja : 56.1</div>
                        <div class="col-4 text-xs"><i class="fas fa-calendar-alt"></i> 3 Des 2020</div>
                    </div>
                
                <h5><b>Menjadi Mentor</b></h5>

                <p>Memberikan Arahan Kepada Bawahan</p>
                </div>
                
                <div class="callout callout-success">
                    <div class="row">
                        <div class="col-8 text-xs">Beban Kerja : 46.1</div>
                        <div class="col-4 text-xs"><i class="fas fa-calendar-alt"></i> 3 Des 2020</div>
                    </div>
                    
                    <h5><b>Survey Lapangan</b></h5>
    
                    <p>Survey Lapangan </p>
                </div>
                
                <div class="callout callout-info">
                    <div class="row">
                        <div class="col-8 text-xs">Beban Kerja : 66.1</div>
                        <div class="col-4 text-xs"><i class="fas fa-calendar-alt"></i> 3 Des 2020</div>
                    </div>
                    
                    <h5><b>Memberi Arahan Kepada Bawahan</b></h5>
    
                    <p>OK </p>
                </div>
                <a href="/pegawai/aktivitas/add" class="btn btn-primary btn-block"><i class="fas fa-plus"></i> Tambah Aktivitas</a>
            </div>
        </div>
        <br />
        
    </div>
</div>
@endsection

@push('js')

@endpush