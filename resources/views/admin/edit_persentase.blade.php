@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
ADMIN SKPD
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <h4>{{detailSkpd(Auth::user()->skpd->id)->nama}}</h4>

        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Edit Persen</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" method="POST" action="/home/admin/persen/edit/{{$id}}">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Jabatan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{$data->nama}}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Jenis Jabatan</label>
                        <div class="col-sm-10">
                            <select name="jenis_jabatan" class="form-control" required>
                                <option value="">-pilih-</option>
                                <option value="JPT Pratama" {{$data->jenis_jabatan == "JPT Pratama" ?
                                    'selected':''}}>JPT Pratama</option>
                                <option value="Administrator" {{$data->jenis_jabatan == "Administrator" ?
                                    'selected':''}}>Administrator</option>
                                <option value="Pengawas" {{$data->jenis_jabatan == "Pengawas" ? 'selected':''}}>Pengawas
                                </option>
                                <option value="jfu" {{$data->jenis_jabatan == "jfu" ? 'selected':''}}>JFU</option>
                                <option value="jft" {{$data->jenis_jabatan == "jft" ? 'selected':''}}>JFT</option>
                                <option value="struktural" {{$data->jenis_jabatan == "struktural" ?
                                    'selected':''}}>Struktural</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Beban Kerja</label>
                        <div class="col-sm-10">
                            <input type="persen_beban_kerja" class="form-control" name="persen_beban_kerja"
                                value="{{$data->persen_beban_kerja == null ? 0:$data->persen_beban_kerja}}"
                                onkeypress="return hanyaAngka(event)">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Tambahan Beban Kerja</label>
                        <div class="col-sm-10">
                            <input type="persen_tambahan_beban_kerja" class="form-control"
                                name="persen_tambahan_beban_kerja" onkeypress="return hanyaAngka(event)"
                                value="{{$data->persen_tambahan_beban_kerja == null ? 0:$data->persen_tambahan_beban_kerja }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Prestasi Kerja</label>
                        <div class="col-sm-10">
                            <input type="persen_prestasi_kerja" class="form-control" name="persen_prestasi_kerja"
                                value="{{$data->persen_prestasi_kerja == null ? 0:$data->persen_prestasi_kerja}}"
                                onkeypress="return hanyaAngka(event)">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Kondisi Kerja</label>
                        <div class="col-sm-10">
                            <input type="persen_kondisi_kerja" class="form-control" name="persen_kondisi_kerja"
                                value="{{$data->persen_kondisi_kerja == null ? 0:$data->persen_kondisi_kerja}}"
                                onkeypress="return hanyaAngka(event)">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Kelangkaan Profesi</label>
                        <div class="col-sm-10">
                            <input type="persen_kelangkaan_profesi" class="form-control"
                                name="persen_kelangkaan_profesi" onkeypress="return hanyaAngka(event)"
                                value="{{$data->persen_kelangkaan_profesi == null ? 0:$data->persen_kelangkaan_profesi}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-10">
                            Gunakan Titik jika memakai koma
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <a href="/home/admin/persen" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-info">Simpan</button>
                </div>
                <!-- /.card-footer -->
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    function hanyaAngka(event) {
        var angka = (event.which) ? event.which : event.keyCode
        if (angka != 46 && angka > 31 && (angka < 48 || angka > 57))
            return false;
        return true;
    }
</script>
@endpush