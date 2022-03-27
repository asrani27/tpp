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
    <a href="/admin/rekapitulasi" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-alt-circle-left"></i>
      Kembali</a>
    <br /><br />
    <div class="row">
      <div class="col-12">
        <div class="card">
          <!-- /.card-header -->
          <form method="POST" action="/home/admin/persen" enctype="multipart/form-data">
            @csrf
            <div class="card-body table-responsive p-0">
              <table class="table table-sm table-bordered">
                <thead>
                  <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif"
                    class="text-center bg-gradient-primary">
                    <th style="width: 10px">#</th>
                    <th>Nama /NIP/Pangkat/Golongan</th>
                    <th>Jenis <br />Jabatan</th>
                    <th>Beban <br />Kerja</th>
                    <th>Tambahan<br /> Beban Kerja</th>
                    <th>Prestasi<br /> Kerja</th>
                    <th>Kondisi<br /> Kerja</th>
                    <th>Kelangkaan<br /> Profesi</th>
                    <th>Persentasi TPP<br />(BK+TBK+PK)</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                @php
                $no=1;
                $count = $data->count();
                @endphp

                <tbody>
                  @foreach ($data as $key => $item)
                  <tr style="font-size:11px; font-family:Arial, Helvetica, sans-serif">
                    <td>{{$no++}}</td>
                    <td>
                      <input type="hidden" name="jabatan_id[]" value="{{$item->id}}">
                      {{$item->nama}} <br />
                      {{$item->pegawai == null ? '' : $item->pegawai->nama}} <br />
                      {{$item->pegawai == null ? '' : $item->pegawai->nip}}
                    </td>
                    <td class="text-center">
                      {{$item->jenis_jabatan}}
                    </td>
                    <td class="text-center">
                      {{$item->persen_beban_kerja == null ? 0 :$item->persen_beban_kerja}}
                    </td>
                    <td class="text-center">
                      {{$item->persen_tambahan_beban_kerja == null ? 0 :$item->persen_tambahan_beban_kerja}}
                    </td>
                    <td class="text-center">
                      {{$item->persen_prestasi_kerja == null ? 0 :$item->persen_prestasi_kerja}}
                    </td>
                    <td class="text-center">
                      {{$item->persen_kondisi_kerja == null ? 0 :$item->persen_kondisi_kerja}}
                    </td>
                    <td class="text-center">
                      {{$item->persen_kelangkaan_profesi == null ? 0 :$item->persen_kelangkaan_profesi}}
                    </td>
                    <td class="text-center">
                      {{$item->persentase_tpp == null ? 0 :$item->persentase_tpp}}
                    </td>
                    <td class="text-center">
                      <a href="/home/admin/persen/edit/{{$item->id}}" class="btn btn-xs btn-primary"
                        class="btn btn-xs btn-primary"><i class="fas fa-edit"></i>edit</a>
                    </td>
                  </tr>
                  @endforeach

                </tbody>

              </table>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')

@endpush