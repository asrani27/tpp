@extends('layouts.app')

@push('css')
    
@endpush

@section('title')
    JURNAL PEGAWAI
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="row text-center">
            <div class="col-12">
                
                <div class="btn-group">
                    <a href="/pegawai/verifikasi/detail" class="btn btn-default">Detail Pegawai</a>
                    <a href="/pegawai/verifikasi/jurnal" class="btn btn-success">Jurnal Aktivitas</a>
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
                        <div class="col-8 text-xs">Waktu : 50 Menit</div>
                        <div class="col-4 text-xs"><i class="fas fa-calendar-alt"></i> 3 Des 2020</div>
                    </div>
                
                    <h5><b>Menjadi Mentor</b></h5>

                    <div class="row">
                        <div class="col-8 text-xs"><p>Memberikan Arahan Kepada Bawahan</p></div>
                        <div class="col-4 text-xs"><a href="/pegawai/verifikasi/jurnal/detail" class="btn btn-xs btn-info" style="color:white;text-decoration:none;">verifikasi</a></div>
                    </div>
                    
                </div>
                
                <div class="callout callout-success">
                    <div class="row">
                        <div class="col-8 text-xs">Beban Kerja : 26.1</div>
                        <div class="col-4 text-xs"><i class="fas fa-calendar-alt"></i> 3 Des 2020</div>
                    </div>
                    
                    <h5><b>Survey Lapangan</b></h5>
    
                    <div class="row">
                        <div class="col-8"><p>Survey Lapangan</p></div>
                        <div class="col-4"><a href="/pegawai/verifikasi/jurnal/detail" class="btn btn-xs btn-info" style="color:white;text-decoration:none;">verifikasi</a></div>
                    </div>
                    
                </div>
                
                <div class="callout callout-info">
                    <div class="row">
                        <div class="col-8">Beban Kerja : 66.1</div>
                        <div class="col-4"><i class="fas fa-calendar-alt"></i> 3 Des 2020</div>
                    </div>
                    
                    <h5><b>Memberi Arahan Kepada Bawahan</b></h5>
    
                    <div class="row">
                        <div class="col-8"><p>Oke</p></div>
                        <div class="col-4"><a href="/pegawai/verifikasi/jurnal/detail" class="btn btn-xs btn-info" style="color:white;text-decoration:none;">verifikasi</a></div>
                    </div>
                    
                </div>
                
            </div>
        </div>
        <br />
    
    
    </div>
</div>
@endsection

@push('js')

@endpush