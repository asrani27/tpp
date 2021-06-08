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
        <h4>Transfer Pegawai</h4>
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-body">
                        <form role="form" method="POST" action="/admin/transfer/add">
                            @csrf
                            <div class="row">
                              <div class="col-sm-6">
                                <!-- text input -->
                                <div class="form-group">
                                  <label>Pilih Pegawai Yg Di Pindah</label>
                                  <select class="form-control select2" name="pegawai_id" required>
                                    <option value="">-pilih-</option>
                                    @foreach ($pegawai as $item)
                                    <option value="{{$item->id}}">{{$item->nip}} - {{$item->nama}}</option>                                       
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                              <div class="col-sm-6">
                                <!-- text input -->
                                <div class="form-group">
                                  <label>SKPD Tujuan</label>
                                  <select class="form-control select2" name="skpd_id" required>
                                    <option value="">-pilih-</option>
                                    @foreach ($skpd as $item)
                                    <option value="{{$item->id}}">{{$item->nama}}</option>                                       
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-12">
                                <!-- textarea -->
                                <div class="form-group">
                                  <label>Tanggal Transfer</label>
                                  <input type="date" class="form-control" name="tgl_pensiun" value="{{old('tgl_transfer')}}" required>
                                </div>
                              </div>
                            </div>
          
                            <div class="form-group">
                                <button class="btn btn-block btn-primary"  onclick="return confirm('Yakin ingin di transfer?');">SIMPAN</button>
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
                  <h3 class="card-title">Riwayat Transfer Pegawai</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap table-sm">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Skpd Lama</th>
                        <th>Skpd Baru</th>
                        {{-- <th>Aksi</th> --}}
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
                                <td>{{$item->skpd_lama->nama}}</td>
                                <td>{{$item->skpd_new->nama}}</td>
                                {{-- <td>
                                    <a href="/admin/transfer/delete/{{$item->id}}" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin di hapus?');"> Hapus </a>
                                </td> --}}
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