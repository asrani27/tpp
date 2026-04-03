@extends('layouts.app')

@section('title')
SUPERADMIN - Export
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <h4>Export Data</h4>
        <div class="row">
            <div class="col-12">
                <br />
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Menu Export Data</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        <h5 class="card-title">Export Pagu TPP Pegawai</h5>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="/superadmin/export/pagu">
                                            @csrf
                                            <div class="form-group">
                                                <label for="bulan">Pilih Bulan</label>
                                                <select class="form-control" id="bulan" name="bulan" required>
                                                    <option value="">-- Pilih Bulan --</option>
                                                    <option value="1">Januari</option>
                                                    <option value="2">Februari</option>
                                                    <option value="3">Maret</option>
                                                    <option value="4">April</option>
                                                    <option value="5">Mei</option>
                                                    <option value="6">Juni</option>
                                                    <option value="7">Juli</option>
                                                    <option value="8">Agustus</option>
                                                    <option value="9">September</option>
                                                    <option value="10">Oktober</option>
                                                    <option value="11">November</option>
                                                    <option value="12">Desember</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="tahun">Pilih Tahun</label>
                                                <select class="form-control" id="tahun" name="tahun" required>
                                                    <option value="">-- Pilih Tahun --</option>
                                                    <option value="2023">2023</option>
                                                    <option value="2024">2024</option>
                                                    <option value="2025">2025</option>
                                                    <option value="2026">2026</option>
                                                    <option value="2027">2027</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fas fa-download"></i> Export Data
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</div>

@endsection