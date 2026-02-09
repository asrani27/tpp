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
                    
                    <div class="alert alert-info">
                        <h5 class="alert-heading">
                            <i class="fas fa-clock mr-2"></i>
                            Perkiraan Waktu Selesai
                        </h5>
                        <hr>
                        <div class="countdown-container">
                            <h2 class="countdown-timer mb-2" id="countdown">--:--:--</h2>
                            <p class="text-muted mb-0">Fitur akan tersedia pada pukul 15:00 (10 Februari 2026)</p>
                        </div>
                    </div>
                    
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

@push('scripts')
<script>
    // Set target date and time: 10 Februari 2026 pukul 15:00
    const targetDate = new Date('2026-02-10T15:00:00').getTime();

    function updateCountdown() {
        const now = new Date().getTime();
        const distance = targetDate - now;

        if (distance < 0) {
            document.getElementById('countdown').innerHTML = 'FITUR TELAH TERSEDIA!';
            return;
        }

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        const formattedHours = hours.toString().padStart(2, '0');
        const formattedMinutes = minutes.toString().padStart(2, '0');
        const formattedSeconds = seconds.toString().padStart(2, '0');

        document.getElementById('countdown').innerHTML = 
            formattedHours + ':' + formattedMinutes + ':' + formattedSeconds;
    }

    // Update countdown every second
    updateCountdown();
    setInterval(updateCountdown, 1000);
</script>
@endpush
