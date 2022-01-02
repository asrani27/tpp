@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
SUPERADMIN
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <a href="/superadmin/pegawai">
            <div class="card card-widget widget-user">
                <div class="widget-user-header bg-info">
                    <h3 class="widget-user-username">TOTAL PEGAWAI</h3>
                    <h2 class="widget-user-desc">{{$total}}<br />
                        <i class="fas fa-users"></i>
                    </h2>
                </div>
            </div>
        </a>
        <h4 class="mt-4 mb-2">BERDASARKAN JENIS KELAMIN</h4>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <a href="/superadmin/rekapitulasi/pns/data/laki">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas fa-mars"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Laki Laki</span>
                            <span class="info-box-number">{{$l}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </a>
            </div>
            <!-- /.col -->
            <div class="col-md-4 col-sm-6 col-12">
                <a href="/superadmin/rekapitulasi/pns/data/perempuan">
                    <div class="info-box bg-gradient-success">
                        <span class="info-box-icon"><i class="fas fa-venus"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Perempuan</span>
                            <span class="info-box-number">{{$p}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-4 col-sm-6 col-12">
                <a href="/superadmin/rekapitulasi/pns/data/jkel">
                    <div class="info-box bg-gradient-danger">
                        <span class="info-box-icon"><i class="fas fa-question"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Belum Mengisi</span>
                            <span class="info-box-number">{{$total - $l - $p}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
        <h4 class="mt-4 mb-2">BERDASARKAN GOLONGAN DARAH</h4>
        <div class="row">
            <div class="col-md-2 col-sm-6 col-12">
                <a href="/superadmin/rekapitulasi/pns/data/darah_a">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas fa-burn"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">A</span>
                            <span class="info-box-number">{{$darah_a}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>
            <div class="col-md-2 col-sm-6 col-12">
                <a href="/superadmin/rekapitulasi/pns/data/darah_b">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas fa-burn"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">B</span>
                            <span class="info-box-number">{{$darah_b}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>
            <div class="col-md-2 col-sm-6 col-12">
                <a href="/superadmin/rekapitulasi/pns/data/darah_ab">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas fa-burn"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">AB</span>
                            <span class="info-box-number">{{$darah_ab}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>
            <div class="col-md-2 col-sm-6 col-12">
                <a href="/superadmin/rekapitulasi/pns/data/darah_o">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas fa-burn"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">O</span>
                            <span class="info-box-number">{{$darah_o}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>

            <div class="col-md-4 col-sm-6 col-12">
                <div class="info-box bg-gradient-danger">
                    <span class="info-box-icon"><i class="fas fa-question"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Belum Mengisi</span>
                        <span class="info-box-number">{{$total - $darah_a - $darah_b - $darah_ab - $darah_o}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>

        <h4 class="mt-4 mb-2">BERDASARKAN JENJANG PENDIDIKAN</h4>
        <div class="row">
            <div class="col-md-2 col-sm-6 col-12">
                <a href="/superadmin/rekapitulasi/pns/data/sma">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas fa-graduation-cap"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">SMA</span>
                            <span class="info-box-number">{{$sma}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>
            <div class="col-md-2 col-sm-6 col-12">
                <a href="/superadmin/rekapitulasi/pns/data/d3">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas fa-graduation-cap"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">D3</span>
                            <span class="info-box-number">{{$d3}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>
            <div class="col-md-2 col-sm-6 col-12">
                <a href="/superadmin/rekapitulasi/pns/data/s1">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas fa-graduation-cap"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">S1</span>
                            <span class="info-box-number">{{$s1}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>
            <div class="col-md-2 col-sm-6 col-12">
                <a href="/superadmin/rekapitulasi/pns/data/s2">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas fa-graduation-cap"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">S2</span>
                            <span class="info-box-number">{{$s2}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>

            <div class="col-md-2 col-sm-6 col-12">
                <a href="/superadmin/rekapitulasi/pns/data/s3">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas fa-graduation-cap"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">S3</span>
                            <span class="info-box-number">{{$s3}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>
            <div class="col-md-2 col-sm-6 col-12">
                <div class="info-box bg-gradient-danger">
                    <span class="info-box-icon"><i class="fas fa-question"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Belum Mengisi</span>
                        <span class="info-box-number">{{$total - $sma - $d3 - $s1 - $s2 - $s3}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>

        <h4 class="mt-4 mb-2">BERDASARKAN ESELON</h4>
        <div class="row">
            @foreach ($eselon as $item)
            <div class="col-md-2 col-sm-6 col-12">
                <a href="/superadmin/rekapitulasi/pns/eselon/{{$item->id}}">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas fa-users"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">{{$item->nama}}</span>
                            <span class="info-box-number">{{$item->total}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>
            @endforeach
            <!-- /.col -->
            <div class="col-md-2 col-sm-6 col-12">
                <div class="info-box bg-gradient-danger">
                    <span class="info-box-icon"><i class="fas fa-question"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Belum Mengisi</span>
                        <span class="info-box-number">{{$total - $eselon->sum('total')}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>

        <h4 class="mt-4 mb-2">BERDASARKAN GOLONGAN</h4>
        <div class="row">
            @foreach ($pangkat as $item)
            <div class="col-md-2 col-sm-6 col-12">
                <a href="/superadmin/rekapitulasi/pns/golongan/{{$item->id}}">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas fa-users"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">{{$item->golongan}}</span>
                            <span class="info-box-number">{{$item->total}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>
            @endforeach
            <!-- /.col -->
            <div class="col-md-2 col-sm-6 col-12">
                <div class="info-box bg-gradient-danger">
                    <span class="info-box-icon"><i class="fas fa-question"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Belum Mengisi</span>
                        <span class="info-box-number">{{$total - $pangkat->sum('total')}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>

        {{-- <h4 class="mt-4 mb-2">BERDASARKAN KELAS JABATAN</h4>
        <div class="row">
            @foreach ($kelas as $item)
            <div class="col-md-2 col-sm-6 col-12">
                <div class="info-box bg-gradient-info">
                    <span class="info-box-icon"><i class="fas fa-users"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Kelas {{$item->nama}}</span>
                        <span class="info-box-number">{{$item->total}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            @endforeach
            <!-- /.col -->
            <div class="col-md-2 col-sm-6 col-12">
                <div class="info-box bg-gradient-danger">
                    <span class="info-box-icon"><i class="fas fa-question"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Belum Mengisi</span>
                        <span class="info-box-number">{{$total - $kelas->sum('total')}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div> --}}
    </div>
</div>

@endsection

@push('js')


@endpush