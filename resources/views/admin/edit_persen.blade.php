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
                    <th>Jenis Jabatan</th>
                    <th>Persentase<br /> TPP<br />%</th>
                    <th>Kondisi <br />Kerja<br />%</th>
                    <th>Beban <br />Kerja<br />%</th>
                    <th>Prestasi <br />Kerja<br />%</th>
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
                      <select name="jenis_jabatan[]" class="form-control form-control-sm">
                        <option value="">-pilih-</option>
                        <option value="struktural" {{$item->jenis_jabatan == 'struktural' ? 'selected':''}}>Struktural
                        </option>
                        <option value="jfu" {{$item->jenis_jabatan == 'jfu' ? 'selected':''}}>JFU</option>
                        <option value="jft" {{$item->jenis_jabatan == 'jft' ? 'selected':''}}>JFT</option>
                        <option value="JPT Pratama" {{$item->jenis_jabatan == 'JPT Pratama' ? 'selected':''}}>JPT
                          Pratama
                        </option>
                        <option value="Administrator" {{$item->jenis_jabatan == 'Administrator' ? 'selected':''}}>
                          Administrator
                        </option>
                        <option value="Pengawas" {{$item->jenis_jabatan == 'Pengawas' ? 'selected':''}}>
                          Pengawas
                        </option>
                      </select>
                    </td>
                    <td class="text-center">
                      <input type="text" name="persentase_tpp[]" class="form-control form-control-sm"
                        value="{{$item->persentase_tpp == null ? 0 : $item->persentase_tpp}}">
                    </td>
                    <td class="text-center">
                      <input type="text" name="tambahan_persen_tpp[]" class="form-control form-control-sm"
                        value="{{$item->tambahan_persen_tpp == null ? 0 : $item->tambahan_persen_tpp}}">
                    </td>
                    <td class="text-center">
                      <input type="text" name="persen_beban_kerja[]" class="form-control form-control-sm"
                        value="{{$item->persen_beban_kerja == 0 ? 37.5 : $item->persen_beban_kerja}}">
                    </td>
                    <td class="text-center">
                      <input type="text" name="persen_prestasi_kerja[]" class="form-control form-control-sm"
                        value="{{$item->persen_prestasi_kerja == 0 ? 50 : $item->persen_prestasi_kerja}}">
                    </td>
                  </tr>
                  @endforeach

                </tbody>

              </table>
            </div>
            <button type="submit" class="btn btn-block btn-success"><i class="fas fa-save"></i> Simpan</button>
            <!-- /.card-body -->
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')

@endpush