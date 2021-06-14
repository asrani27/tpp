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
        <h4>PLT Jabatan</h4>
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-body">
                        <form role="form" method="POST" action="/admin/plt/add">
                            @csrf
                            <div class="row">
                              <div class="col-sm-6">
                                <!-- text input -->
                                <div class="form-group">
                                  <label>Jabatan Kosong</label>
                                  <select class="form-control select2" name="jabatan_plt" required>
                                    <option value="">-pilih-</option>
                                    @foreach ($jabatanTersedia as $item)
                                    <option value="{{$item->id}}" {{old('jabatan_plt') == $item->id ? 'selected':''}}>{{$item->nama}}</option>                                       
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                              <div class="col-sm-6">
                                <div class="form-group">
                                  <label>NIP Pegawai</label>
                                  <input type="text" class="form-control" name="nip" value="{{old('nip')}}" maxlength="18" onkeypress="return hanyaAngka(event)"/ required>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-6">
                                <!-- textarea -->
                                <div class="form-group">
                                  <label>Tanggal SK Mulai</label>
                                  <input type="date" class="form-control" name="tgl_mulai_sk" value="{{old('tgl_mulai_sk')}}" required>
                                </div>
                              </div>
                              <div class="col-sm-6">
                                <div class="form-group">
                                  <label>Tanggal SK Selesai</label>
                                  <input type="date" class="form-control" name="tgl_selesai_sk" value="{{old('tgl_selesai_sk')}}" required>
                                </div>
                              </div>
                            </div>
          
                            <div class="form-group">
                                <button class="btn btn-block btn-primary">SIMPAN</button>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">PLT sedang Berjalan</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap table-sm">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Jabatan di PLT</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    @php
                        $no =1;
                    @endphp
                    <tbody>
                        @foreach ($dataPlt as $item)
                            <tr>
                                <td>{{$no++}}</td>
                                <td>{{$item->pegawaiplt->nip}}</td>
                                <td>{{$item->pegawaiplt->nama}}<br/>
                                    {{$item->pegawaiplt->jabatan == null ? '-': $item->pegawaiplt->jabatan->nama}}
                                </td>
                                <td>{{$item->nama}}</td>
                                <td>
                                    <a href="/admin/plt/delete/{{$item->id}}" class="btn btn-sm btn-danger"onclick="return confirm('Yakin ingin di hapus?');"> Hapus </a>
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

        
        <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Riwayat PLT</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap table-sm">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Jabatan di PLT</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    @php
                        $no =1;
                    @endphp
                    <tbody>
                        @foreach ($riwayat as $item)
                            <tr>
                                <td>{{$no++}}</td>
                                <td>{{$item->nip}}</td>
                                <td>{{$item->nama}}</td>
                                <td>{{$item->jabatan}}</td>
                                <td>{{$item->tgl_mulai_sk}}</td>
                                <td>{{$item->tgl_selesai_sk}}</td>
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
<script>
    function hanyaAngka(evt) {
      var charCode = (evt.which) ? evt.which : event.keyCode
       if (charCode > 31 && (charCode < 48 || charCode > 57))

        return false;
      return true;
    }
</script>
@endpush