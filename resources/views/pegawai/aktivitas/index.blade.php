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
            <div class="col-lg-6 col-12">
                <div class="card card-widget widget-user-2">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-gradient-blue">
                        <div class="widget-user-image">
                            <img class="img-circle elevation-2" src="/login_tpp/images/icons/logo.png"
                                alt="User Avatar">
                        </div>
                        <!-- /.widget-user-image -->
                        @if (Auth::user()->pegawai->jabatan->sekda == 1)
                        <h3 class="widget-user-username">WALIKOTA</h3>
                        <h5 class="widget-user-desc">KOTA BANJARMASIN</h5>
                        @elseif (Auth::user()->pegawai->jabatan->sekolah_id != null)
                        <h3 class="widget-user-username">Kepala Sekolah</h3>
                        <h5 class="widget-user-desc">{{Auth::user()->pegawai->jabatan->sekolah->nama}}</h5>
                        @else
                        <h3 class="widget-user-username">
                            {{checkAtasan($atasan, $person)['nama']}}
                        </h3>
                        <h5 class="widget-user-desc">
                            {{checkAtasan($atasan, $person)['jabatan']}}
                        </h5>
                        @endif
                    </div>

                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="card card-widget widget-user-2">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-gradient-purple">
                        <div class="widget-user-image">
                            <img class="img-circle elevation-2" src="/login_tpp/images/icons/logo.png"
                                alt="User Avatar">
                        </div>
                        <!-- /.widget-user-image -->
                        <h3 class="widget-user-username">{{$person->nama}}</h3>
                        <h5 class="widget-user-desc">{{$person->jabatan->nama}} <br />
                            {{$person->jabatanPlt == null ? '': 'Plt. '.$person->jabatanPlt->nama}}
                            {{$person->jabatanPlh == null ? '': 'Plh. '.$person->jabatanPlh->nama}}
                        </h5>
                    </div>

                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-12">
                <a href="/pegawai/aktivitas/add" class="btn btn-success btn-block"><i class="fas fa-plus"></i> Tambah
                    Aktivitas</a>
            </div>
        </div>
        <br />

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Aktivitas Belum Di Nilai</h3>

                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Menit</th>
                                    <th>Aktivitas</th>
                                    <th>Status</th>
                                    <th>Di nilai Oleh</th>
                                    <th></th>
                                </tr>
                            </thead>
                            @php
                            $no =1;
                            @endphp
                            <tbody>
                                @foreach ($aktivitasBelumDinilai as $key => $item)
                                <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif">
                                    <td>{{$key+ $aktivitasBelumDinilai->firstItem()}}</td>
                                    <td>{{\Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMMM Y')}}</td>
                                    <td>{{\Carbon\Carbon::parse($item->jam_mulai)->format('H:i')}} -
                                        {{\Carbon\Carbon::parse($item->jam_selesai)->format('H:i')}}</td>
                                    <td>{{$item->menit}}</td>
                                    <td>{!!wordwrap($item->deskripsi,100,"<br>")!!}</td>
                                    <td>
                                        @if ($item->validasi == 0)
                                        <span class="badge bg-info"><i class="fas fa-clock"></i> Diproses</span>
                                        @elseif ($item->validasi == 1)
                                        <span class="badge bg-success"><i class="fas fa-check"></i> Disetujui</span>

                                        @else
                                        <span class="badge bg-danger"><i class="fas fa-times"></i> Ditolak</span>

                                        @endif
                                    </td>
                                    <td>{{$item->validator == null ? null : $item->penilai->nama}}</td>
                                    <td>
                                        @if ($item->validasi == 0)
                                        <a href="/pegawai/aktivitas/harian/edit/{{$item->id}}"
                                            class="btn btn-xs btn-success text-white" data-toggle="tooltip"
                                            title="edit data"><i class="fas fa-edit"></i></a>
                                        <a href="/pegawai/aktivitas/harian/delete/{{$item->id}}"
                                            class="btn btn-xs btn-danger text-white" data-toggle="tooltip"
                                            title="hapus data" onclick="return confirm('Yakin ingin di hapus?');"><i
                                                class="fas fa-trash"></i></a>
                                        @else
                                        @if (Auth::user()->username == '198709162010012005')
                                        <a href="/pegawai/aktivitas/harian/delete/{{$item->id}}"
                                            class="btn btn-xs btn-danger text-white" data-toggle="tooltip"
                                            title="hapus data" onclick="return confirm('Yakin ingin di hapus?');"><i
                                                class="fas fa-trash"></i></a>
                                        @endif
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{$aktivitasBelumDinilai->links()}}
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Rekap Aktivitas Perbulan</h3>

                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Bulan Tahun</th>
                                    <th>Jumlah Aktivitas</th>
                                    <th>Jumlah Menit (Disetujui)</th>
                                    <th></th>
                                </tr>
                            </thead>
                            @php
                            $no =1;
                            @endphp
                            <tbody>
                                @foreach (bulanTahun() as $key => $item)
                                <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                                    <td>{{$no++}}</td>
                                    <td>{{\Carbon\Carbon::createFromFormat('m',$item->bulan)->translatedFormat('F')}}
                                        {{$item->tahun}}
                                    </td>
                                    <td>{{totalAktivitas($item->bulan, $item->tahun)}} Aktivitas</td>
                                    <td>{{totalMenit($item->bulan, $item->tahun)}} Menit</td>
                                    <td>
                                        <a href="/pegawai/aktivitas/harian/detail/{{$item->bulan}}/{{$item->tahun}}"
                                            class='btn btn-xs btn-primary'>Detail Aktivitas</a>
                                    </td>
                                </tr>
                                @endforeach
                                {{-- @foreach ($data as $key => $item)
                                <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif">
                                    <td>{{$key+ $data->firstItem()}}</td>
                                    <td>{{\Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMMM Y')}}</td>
                                    <td>{{\Carbon\Carbon::parse($item->jam_mulai)->format('H:i')}} -
                                        {{\Carbon\Carbon::parse($item->jam_selesai)->format('H:i')}}</td>
                                    <td>{{$item->menit}}</td>
                                    <td>{!!wordwrap($item->deskripsi,100,"<br>")!!}</td>
                                    <td>
                                        @if ($item->validasi == 0)
                                        <span class="badge bg-info"><i class="fas fa-clock"></i> Diproses</span>
                                        @elseif ($item->validasi == 1)
                                        <span class="badge bg-success"><i class="fas fa-check"></i> Disetujui</span>

                                        @else
                                        <span class="badge bg-danger"><i class="fas fa-times"></i> Ditolak</span>

                                        @endif
                                    </td>
                                    <td>{{$item->validator == null ? null : $item->penilai->nama}}</td>
                                    <td>
                                        @if ($item->validasi == 0)
                                        <a href="/pegawai/aktivitas/harian/edit/{{$item->id}}"
                                            class="btn btn-xs btn-success text-white" data-toggle="tooltip"
                                            title="edit data"><i class="fas fa-edit"></i></a>
                                        <a href="/pegawai/aktivitas/harian/delete/{{$item->id}}"
                                            class="btn btn-xs btn-danger text-white" data-toggle="tooltip"
                                            title="hapus data" onclick="return confirm('Yakin ingin di hapus?');"><i
                                                class="fas fa-trash"></i></a>
                                        @else
                                        @if (Auth::user()->username == '198709162010012005')
                                        <a href="/pegawai/aktivitas/harian/delete/{{$item->id}}"
                                            class="btn btn-xs btn-danger text-white" data-toggle="tooltip"
                                            title="hapus data" onclick="return confirm('Yakin ingin di hapus?');"><i
                                                class="fas fa-trash"></i></a>
                                        @endif
                                        @endif
                                    </td>
                                </tr>
                                @endforeach --}}
                            </tbody>
                        </table>
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