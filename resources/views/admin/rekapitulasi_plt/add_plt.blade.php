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
                            <img class="img-circle elevation-2" src="/login_tpp/images/icons/logo.png"
                                alt="User Avatar">
                        </div>
                        <!-- /.widget-user-image -->
                        <h3 class="widget-user-username">Laporan TPP PLT {{Auth::user()->skpd->nama}}</h3>
                        <h5 class="widget-user-desc">Menampilkan data Laporan TPP PLT</h5>
                    </div>

                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">

                    <a href="/admin/rekapitulasi/plt/{{$bulan}}/{{$tahun}}" class="btn btn-sm btn-secondary">Kembali</a>
                    TPP PLT Bulan
                    {{convertBulan($bulan)}} {{$tahun}}
                </h3>
            </div>
            <div class="card-body">

                <div class="alert alert-default alert-dismissible text-sm">
                    <h5><i class="icon fas fa-info"></i> Informasi</h5>
                    1. Masukkan NIP<br />

                    2. Pilih Jabatan Yg Di PLT kemudian SIMPAN<br />

                </div>

                <div class="row">
                    <div class="col-md-12">
                        <form role="form" method="POST" action="/admin/rekapitulasi/plt/{{$bulan}}/{{$tahun}}/create">
                            @csrf
                            <div class="form-group">
                                <label>NIP Pegawai</label>
                                <input type="text" class="form-control" name="nip" value="{{old('nip')}}" maxlength="18"
                                    onkeypress="return hanyaAngka(event)" / required>
                            </div>
                            <div class="form-group">
                                <label>Jabatan Di PLT</label>
                                <select name="jabatan_plt_id" class="form-control select2bs4 " style="width: 100%;"
                                    required>
                                    <option value="">-pilih-</option>
                                    @foreach ($jabatanPlt as $item)
                                    <option value="{{$item->id}}">Kelas:{{$item->kelas->nama}}, {{$item->nama}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Jenis</label>
                                <select name="jenis_plt" class="form-control select2" required>
                                    <option value="">-pilih-</option>
                                    <option value="1">Kelas Jabatan Di PLT lebih tinggi</option>
                                    <option value="2">Kelas Jabatan Di PLT setara (+20%)</option>
                                    <option value="3">Kelas Jabatan Di PLT lebih tinggi namun Eselon setara (+20%)
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-primary">SIMPAN</button>
                            </div>
                        </form>
                    </div>
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
<script>
    function hanyaAngka(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
 
    return false;
    return true;
}
</script>
@endpush