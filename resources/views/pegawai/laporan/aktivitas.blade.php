@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
LAPORAN AKTIVITAS
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <h4>LAPORAN AKTIVITAS</h4>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"> Aktivitas</h3>

            <div class="card-tools">
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive">
            <table class="table table-hover table-striped text-nowrap table-sm">
              <thead>
                <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif" class="bg-gradient-primary">
                  <th>#</th>
                  <th>Bulan & Tahun</th>
                  <th>Menit Aktivitas</th>
                  <th>Cuti Tahunan</th>
                  <th>Cuti Bersama</th>
                  <th>Diklat/Pelatihan</th>
                  <th>Tugas Luar</th>
                  <th>Covid</th>
                  <th>Kehadiran</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              @php
              $no =1;
              @endphp
              <tbody>

                @foreach ($data as $key => $item)
                
                <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                  <td>{{$no++}}</td>
                  <td>{{\Carbon\Carbon::createFromFormat('m',$item->bulan)->translatedFormat('F')}} {{$item->tahun}}
                  </td>
                  <td>{{$item->menit}} </td>
                  <td>{{$item->cuti_tahunan}} </td>
                  <td>{{$item->cuti_bersama}} </td>
                  <td>{{$item->diklat}} </td>
                  <td>{{$item->tugas_luar}} </td>
                  <td>{{$item->covid}} </td>
                  <td>{{$item->presensi}} </td>
                  
                  <td>
                    <a href="/pegawai/laporan/aktivitas/{{$item->bulan}}/{{$item->tahun}}"
                      class='btn btn-xs btn-primary'>Detail Aktivitas</a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            {{-- <table class="table table-hover text-nowrap table-sm">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Tanggal</th>
                  <th>Jam</th>
                  <th>Menit</th>
                  <th>Aktivitas</th>
                  <th>Status</th>
                </tr>
              </thead>
              @php
              $no =1;
              @endphp
              <tbody>
                @foreach ($data as $key => $item)
                <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif">
                  <td>{{$key+ $data->firstItem()}}</td>
                  <td>{{\Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMMM Y')}}</td>
                  <td>{{\Carbon\Carbon::parse($item->jam_mulai)->format('H:i')}} -
                    {{\Carbon\Carbon::parse($item->jam_selesai)->format('H:i')}}</td>
                  <td>{{$item->menit}}</td>
                  <td>{{$item->deskripsi}}</td>
                  <td>
                    @if ($item->validasi == 0)
                    <span class="badge bg-info"><i class="fas fa-clock"></i> Diproses</span>
                    @elseif ($item->validasi == 1)
                    <span class="badge bg-success">Disetujui</span>

                    @else
                    <span class="badge bg-danger">Ditolak</span>

                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table> --}}
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