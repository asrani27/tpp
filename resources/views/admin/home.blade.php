@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css"
  rel="stylesheet" />
@endpush
@section('title')
ADMIN SKPD {{strtoupper(Auth::user()->name)}}
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="row">
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{$countPegawai}}</h3>

            <p>ASN</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          <a href="/admin/pegawai" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{$countJabatan}}</h3>

            <p>PETA JABATAN</p>
          </div>
          <div class="icon">
            <i class="fas fa-map"></i>
          </div>
          <a href="/admin/jabatan" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-6 col-12">
        <!-- small box -->
        <div class="small-box bg-success">
          <div class="inner">
            <h3>Rp. {{currency($data->sum('tpp_diterima'))}},-</h3>

            <p>Total TPP Bulan {{\Carbon\Carbon::now()->isoFormat("MMMM Y")}}</p>

          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="#" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 text-center">

        <a href="/admin/rekapitulasi" class="btn btn-info"><i class="fas fa-file"></i>Rekap Data</a>
        <a href="/home/admin/persen" class="btn btn-primary"><i class="fas fa-percent"></i> Edit Persen TPP</a>
        {{-- <a href="/admin/presensi" class="btn btn-primary"><i class="fas fa-clock"></i> Edit Presensi</a> --}}
      </div>
    </div>

    <br />
    {{--

    <div class="row">
      <div class="col-12 text-center">
        <strong>DAFTAR TPP ASN<br />
          BULAN {{strtoupper(\Carbon\Carbon::now()->monthName)}}<br />
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
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        @if (Auth::user()->skpd->kode_skpd == '1.02.01.')
        {{$data->links()}}
        @endif
      </div>
    </div> --}}
  </div>
</div>
@endsection

@push('js')

@endpush