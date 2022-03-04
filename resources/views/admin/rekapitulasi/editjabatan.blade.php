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
        <h4>Edit Jabatan</h4>
        <div class="card card-info">
            <form class="form-horizontal" method="POST"
                action="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/editjabatan/{{$id}}">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">LAPORAN TPP BULAN :</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{$bulan}}-{{$tahun}}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">NIP</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{$data->nip}}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">NAMA</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{$data->nama}}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">JABATAN SEKARANG</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{$data->jabatan}}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">GANTI KELAS | JABATAN</label>
                        <div class="col-sm-10">
                            <select name="jabatan_id" class="form-control select2">
                                @foreach ($jabatan as $item)
                                <option value="{{$item->id}}">{{$item->kelas->nama}} | {{$item->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-10">
                            <span class="badge badge-primary">*mengganti jabatan di laporan tidak akan mengubah jabatan
                                yang ada.</span>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
                <!-- /.card-footer -->
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<!-- Select2 -->
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