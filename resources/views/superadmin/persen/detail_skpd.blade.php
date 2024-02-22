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
    <h4>SKPD PEMERINTAH KOTA BANJARMASIN</h4>
    <div class="row">
      <div class="col-12">

        <div class="card">
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap table-sm">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Jabatan</th>
                  <th>Jenis</th>
                  <th>Kelas</th>
                  {{-- <th>Subkoordinator</th> --}}
                  <th>Beban <br /> Kerja</th>
                  <th>Tambahan <br /> Beban <br /> Kerja</th>
                  <th>Prestasi<br /> Kerja</th>
                  <th>Kondisi<br /> Kerja</th>
                  <th>Kelangkaan <br /> Profesi</th>
                  
                  <th>Aksi</th>
                </tr>
              </thead>
              @php
              $no =1;
              @endphp
              <tbody>

                @foreach ($data as $item)
                <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                  <td>{{$no++}}</td>
                  <td>{{$item->nama}}<br />
                    {{$item->pegawai == null ? $item->pegawaiplt == null ? '-':'(PLT) ' . $item->pegawaiplt->nama
                    :$item->pegawai->nama
                    }}</td>
                  <td>{{$item->jenis_jabatan}}</td>
                  <td>{{$item->kelas->nama}}</td>
                  {{-- <td class="text-center">
                    @if ($item->subkoordinator == null)
                    <a href="/superadmin/persentase/subkoordinator/ya/{{$item->id}}" class="btn btn-xs btn-danger"
                      onclick="return confirm('Ubah Menjadi SubKoordinator?');"><i class="fa fa-times"></i></a>
                    @else
                    <a href="/superadmin/persentase/subkoordinator/tidak/{{$item->id}}" class="btn btn-xs btn-success"
                      onclick="return confirm('Ubah Menjadi Bukan SubKoordinator?');"><i class="fa fa-check"></i></a>
                    @endif
                  </td> --}}
                  <td class="text-center; font-size:16px;">{{$item->persen_beban_kerja == null ? 0:$item->persen_beban_kerja}}</td>
                  <td class="text-center; font-size:16px;">{{$item->persen_tambahan_beban_kerja == null ?
                    0:$item->persen_tambahan_beban_kerja}}</td>
                  <td class="text-center; font-size:16px;">{{$item->persen_prestasi_kerja == null ? 0:$item->persen_prestasi_kerja}}</td>
                  <td class="text-center; font-size:16px;">{{$item->persen_kondisi_kerja == null ? 0:$item->persen_kondisi_kerja}}</td>
                  <td class="text-center; font-size:16px;">{{$item->persen_kelangkaan_profesi == null ?
                    0:$item->persen_kelangkaan_profesi}}</td>
                  <td>

                    <a href="/superadmin/persentase/skpd/{{$id}}/edit/{{$item->id}}" class="btn btn-xs btn-primary"><i
                        class="fas fa-edit"></i>edit</a>
                  </td>
                </tr>
                @endforeach

              </tbody>
            </table>
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