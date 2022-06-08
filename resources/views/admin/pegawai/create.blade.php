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
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card card-widget widget-user-2">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-info">
                        <div class="widget-user-image">
                            <img class="img-circle elevation-2" src="/login_tpp/images/icons/logo.png"
                                alt="User Avatar">
                        </div>
                        <!-- /.widget-user-image -->
                        <h3 class="widget-user-username">{{detailSkpd(Auth::user()->skpd->id)->nama}}</h3>
                        <h5 class="widget-user-desc">Kode Skpd: {{detailSkpd(Auth::user()->skpd->id)->kode_skpd}}</h5>
                    </div>

                </div>
            </div>
        </div>
        <a href="/admin/pegawai" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-alt-circle-left"></i>
            Kembali</a><br /><br />
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"></h3>
                        Tambah Data ASN
                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 300px;">
                                {{-- <input type="text" name="table_search" class="form-control float-right"
                                    placeholder="Cari NIP / Nama">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <form class="form-horizontal" method="POST" action="/admin/pegawai/add">

                            @csrf
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">SKPD</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="{{Auth::user()->skpd->nama}}"
                                            readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">NIP</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="nip" value="{{ $data['nip'] }}"
                                            required minlength="18" maxlength="18">
                                        @if ($errors->has('nip'))
                                        <span class="text-danger">{{ $errors->first('nip') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label">Nama ASN</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="nama"
                                            value="{{ $data['nm_lengkap'] }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label">Tanggal lahir</label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" name="tanggal_lahir"
                                            value="{{ old('tanggal_lahir') }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label">Eselon</label>
                                    <div class="col-sm-10">
                                        <select name="eselon_id" class="form-control">
                                            <option value="">-pilih-</option>
                                            @foreach (eselon() as $item)
                                            <option value="{{$item->id}}">{{$item->nama}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label">Pangkat</label>
                                    <div class="col-sm-10">
                                        <select name="pangkat_id" class="form-control">
                                            <option value="">-pilih-</option>
                                            @foreach (pangkat() as $item)
                                            <option value="{{$item->id}}">{{$item->nama}} ({{$item->golongan}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{--
                                @if (Auth::user()->skpd->id == 34)
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label">256 Jabatan
                                        Tersedia</label>
                                    <div class="col-sm-10">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Kelas</th>
                                                    <th>Nama</th>
                                                    <th>Atasan</th>
                                                    <th>Check</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($jabatan as $item)
                                                <tr>
                                                    <td>{{$item->kelas->nama}}</td>
                                                    <td>{{$item->nama}}</td>
                                                    <td>{{$item->atasan == null ?
                                                        '-':'Atasan
                                                        : '.$item->atasan->nama}}</td>
                                                    <td>
                                                        <input type="radio" name="jabatan_id">
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {{$jabatan->links()}}
                                    </div>
                                </div>

                                @else --}}

                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label">Jabatan Tersedia</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2" name="jabatan_id">
                                            <option value="">-pilih-</option>
                                            @foreach ($jabatan as $item)
                                            @if ($item->sekolah_id == null)
                                            <option value="{{$item->id}}">{{$item->nama}} ({{$item->rs == null ?
                                                $item->skpd->nama:$item->rs->nama}}) - {{$item->atasan == null ?
                                                '-':'Atasan
                                                : '.$item->atasan->nama}}</option>
                                            @else

                                            <option value="{{$item->id}}">{{$item->nama}} - Atasan : Kepsek
                                                {{$item->sekolah->nama}}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- @endif --}}

                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-info">Simpan</button>
                            </div>
                            <!-- /.card-footer -->
                        </form>
                    </div>

                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

<script src="/theme/plugins/select2/js/select2.full.min.js"></script>
<script>
    $(function () {
      //Initialize Select2 Elements
      $('.select2').select2()
  
      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })
    })
</script>
@endpush