@extends('layouts.app')

@section('title')
Rekap TPP - Sedang Dalam Pengembangan
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tools mr-2"></i>
                    Halaman Sedang Dalam Pengembangan
                </h3>
            </div>
            <div class="card-body text-center">
                <div class="py-5">
                    <i class="fas fa-cogs fa-5x text-warning mb-4"></i>
                    <h3 class="text-muted mb-3">Mohon Maaf</h3>
                    <p class="text-muted mb-4">
                        Halaman <strong>Rekapitulasi TPP</strong> saat ini sedang dalam tahap pengembangan.
                    </p>
                    <p class="text-muted mb-4">
                        Fitur ini akan segera tersedia. Silakan cembali lagi nanti atau hubungi administrator untuk informasi lebih lanjut.
                    </p>
                    <a href="/home/admin" class="btn btn-warning">
                        <i class="fas fa-home mr-2"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>
            <div class="card-footer text-center">
                <small class="text-muted">
                    <i class="fas fa-info-circle mr-1"></i>
                    Untuk informasi lebih lanjut, silakan hubungi tim IT.
                </small>
            </div>
        </div>
    </div>
</div>
@endsection