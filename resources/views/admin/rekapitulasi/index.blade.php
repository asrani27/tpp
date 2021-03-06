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
    <div class="row">
      <div class="col-lg-12 col-12">
        <div class="card card-widget widget-user-2">
          <!-- Add the bg color to the header using any of the bg-* classes -->
          <div class="widget-user-header bg-info">
            <div class="widget-user-image">
              <img class="img-circle elevation-2" src="/login_tpp/images/icons/logo.png" alt="User Avatar">
            </div>
            <!-- /.widget-user-image -->
            <h3 class="widget-user-username">Riwayat TPP {{Auth::user()->skpd->nama}}</h3>
            <h5 class="widget-user-desc">Menampilkan data riwayat tpp</h5>
          </div>

        </div>
      </div>
    </div>
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Pilih Tahun Dan Bulan</h3>
      </div>
      <div class="card-body">
        <table class="table table-hover table-striped text-nowrap table-sm">
          <thead>
            <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif" class="bg-gradient-info">
              <th>#</th>
              <th>Bulan</th>
              <th>Tahun</th>
              <th>Aksi</th>
            </tr>
          </thead>
          @php
          $no =1;
          @endphp
          <tbody>

            @foreach (bulanTahun() as $key => $item)
            <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif">
              <td>{{$no++}}</td>
              <td>{{convertBulan($item->bulan)}}</td>
              <td>{{$item->tahun}}</td>
              <td><a href="/admin/rekapitulasi/{{$item->bulan}}/{{$item->tahun}}" class="btn btn-xs btn-primary"><i
                    class="fas fa-eye"></i> Detail</a></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    @if (Auth::user()->username == '1.01.01.')
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Pilih Tahun Dan Bulan (TU)</h3>
      </div>
      <div class="card-body">
        <table class="table table-hover table-striped text-nowrap table-sm">
          <thead>
            <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif" class="bg-gradient-info">
              <th>#</th>
              <th>Bulan</th>
              <th>Tahun</th>
              <th>Aksi</th>
            </tr>
          </thead>
          @php
          $no =1;
          @endphp
          <tbody>

            @foreach (bulanTahun() as $key => $item)
            <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif">
              <td>{{$no++}}</td>
              <td>{{convertBulan($item->bulan)}}</td>
              <td>{{$item->tahun}}</td>
              <td><a href="/admin/rekapitulasi/tu/{{$item->bulan}}/{{$item->tahun}}" class="btn btn-xs btn-primary"><i
                    class="fas fa-eye"></i> Detail</a></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif
    {{-- @if ($tampil == true)

    <div class="row">
      <div class="col-12 text-center">
        <strong>DAFTAR TPP ASN<br />
          BULAN {{strtoupper($bulantahun)}}<br />
          {{strtoupper(Auth::user()->name)}}</strong>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-sm table-bordered">
              <thead>
                <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif"
                  class="text-center bg-gradient-primary">
                  <th rowspan=2 style="width: 10px"></th>
                  <th rowspan=2 style="width: 10px">#</th>
                  <th rowspan=2>Nama /NIP/Pangkat/Golongan</th>
                  <th rowspan=2>Jabatan</th>
                  <th rowspan=2>Jenis Jabatan</th>
                  <th rowspan=2>Kelas</th>
                  <th rowspan=2>Basic TPP</th>
                  <th colspan=4>Beban Kerja</th>
                  <th colspan=2>Disiplin 40%</th>
                  <th colspan=2>Produktivitas 60%</th>
                  <th rowspan=2>TPP ASN</th>
                  <th rowspan=2>PPH 21</th>
                  <th rowspan=2>Hukuman Disiplin</th>
                  <th rowspan=2>Potongan BPJS</th>
                  <th rowspan=2>TPP DIterima</th>
                </tr>
                <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif"
                  class="text-center bg-gradient-primary">
                  <th>Persentase TPP</th>
                  <th>Tambahan Persentase TPP</th>
                  <th>Jumlah Persentase</th>
                  <th>Total Pagu</th>
                  <th>%</th>
                  <th>Rp.</th>
                  <th>{{$capaianMenit}} menit</th>
                  <th>Rp.</th>
                </tr>
              </thead>
              @if ($tpp == true)
              @php
              $no=1;
              $count = $data->count();
              @endphp

              <tbody>
                @foreach ($data as $key => $item)
                <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif">

                  <td>
                    @if ($key == 0)
                    <a href="/home/admin/down/{{$item->id}}/{{$item->urutan}}"><i class="fas fa-caret-down"></i></a>
                    @elseif($key == $count-1)
                    <a href="/home/admin/up/{{$item->id}}/{{$item->urutan}}"><i class="fas fa-caret-up"></i></a>
                    @else
                    <a href="/home/admin/up/{{$item->id}}/{{$item->urutan}}"><i class="fas fa-caret-up"></i></a>
                    <a href="/home/admin/down/{{$item->id}}/{{$item->urutan}}"><i class="fas fa-caret-down"></i></a>
                    @endif
                  </td>
                  <td>{{$no++}}</td>
                  <td>
                    {{$item->nama}} <br />
                    @if ($item->nama_pangkat == null)
                    <a href="#" data-toggle="tooltip" data-placement="top" title="Pangkat Kosong!"> <span
                        class="text-danger"><i class="fas fa-exclamation-triangle"></i></span></a>
                    @else
                    {{$item->nama_pangkat}}<br />
                    @endif
                    NIP.{{$item->nip}}

                  </td>
                  <td class="text-center">
                    {{$item->nama_jabatan}}
                  </td>
                  <td class="text-center">
                    {{$item->jenis_jabatan}}
                  </td>
                  <td class="text-center">
                    {{$item->nama_kelas}}
                  </td>
                  <td class="text-right">
                    {{currency($item->basic_tpp)}}
                  </td>
                  <td class="text-center">
                    {{$item->persentase_tpp}} %
                  </td>
                  <td class="text-center">
                    {{$item->tambahan_persen_tpp == null ? 0: $item->tambahan_persen_tpp}} %
                  </td>
                  <td class="text-center">
                    {{$item->jumlah_persentase}} %
                  </td>
                  <td class="text-right">
                    {{currency($item->total_pagu)}}
                  </td>
                  <td>{{$item->persen_disiplin}}</td>
                  <td class="text-right">
                    {{currency($item->total_disiplin)}}
                  </td>
                  <td>{{$item->persen_produktivitas}} m</td>
                  <td class="text-right">
                    {{currency($item->total_produktivitas)}}
                  </td>
                  <td class="text-right">
                    {{currency($item->total_tpp)}}
                  </td>
                  <td class="text-right">
                    {{$item->pph}} % <br>
                    {{$item->pph_angka == 0 ? '':'-'}}{{currency($item->pph_angka)}}
                  </td>
                  <td class="text-right">
                    {{$item->hukuman}} % <br>
                    {{$item->hukuman_angka == 0 ? '':'-'}}{{currency($item->hukuman_angka)}}
                  </td>
                  <td class="text-right">
                    0
                  </td>
                  <td class="text-right">
                    {{currency($item->tpp_diterima)}}
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td>Total</td>
                  <td>{{currency($data->sum('tpp_diterima'))}}</td>
                </tr>
              </tfoot>
              @endif
            </table>
          </div>
          <!-- /.card-body -->
        </div>
      </div>
    </div>
    @endif --}}

  </div>
</div>

@endsection

@push('js')

@endpush