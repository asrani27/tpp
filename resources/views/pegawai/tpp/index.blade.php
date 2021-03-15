@extends('layouts.app')

@push('css')
    
@endpush

@section('title')
    TPP Produktivitas
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="row text-center">
            <div class="col-12">
                
                <div class="btn-group">
                    <a href="/pegawai/tpp" class="btn btn-success">DATA TPP</a>
                    <a href="/pegawai/tpp/grafik" class="btn btn-default">GRAFIK TPP</a>
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
                <div class="card">
                    <div class="card-body">
                    Pokok TPP :
                    <h5><b>Rp. 5.567.000,-</b></h5>
                    
                    Tingkat Kedisiplinan :
                    <div class="row">
                        <div class="col-7"><b>10 %</b></div>
                        <div class="col-5 text-right"><b>Rp. 7.450.000,-</b></div>
                    </div>
                    
                    Nilai Aktivitas :
                    <div class="row">
                        <div class="col-7"><b>0.07</b></div>
                        <div class="col-5 text-right"><b>Rp. 450.000,-</b></div>
                    </div>

                    Nilai SKP :
                    <div class="row">
                        <div class="col-7"><b>82.77</b></div>
                        <div class="col-5 text-right"><b>Rp. 1.450.000,-</b></div>
                    </div>
                    
                    Nilai SAKIP :
                    <div class="row">
                        <div class="col-7"><b>1.77</b></div>
                        <div class="col-5 text-right"><b>Rp. 2.250.000,-</b></div>
                    </div>
                    <hr>

                    Jumlah TPP Bruto
                    <div class="row">
                        <div class="col-7"><b></b></div>
                        <div class="col-5 text-right"><b>Rp. 12.250.000,-</b></div>
                    </div>

                    PPH 21
                    <div class="row">
                        <div class="col-7"><b>15 %</b></div>
                        <div class="col-5 text-right"><b>Rp. 3.250.000,-</b></div>
                    </div>
                    <hr>
                    TPP Diterima
                    <div class="row">
                        <div class="col-7"><b></b></div>
                        <div class="col-5 text-right"><b>Rp. 9.000.000,-</b></div>
                    </div>
                    
                    </div>
                </div>
            </div>
        </div>
    
    </div>
</div>
@endsection

@push('js')

@endpush