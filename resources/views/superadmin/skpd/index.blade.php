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
        <a href="/superadmin/skpd/add" class="btn btn-sm btn-primary"><i class="fas fa-university"></i> Tambah SKPD</a>
        <a href="/superadmin/skpd/createuser" class="btn btn-sm bg-purple"
          onclick="return confirm('Yakin Mengcreate User Dan Pass?');"><i class="fas fa-key"></i> Create User & Pass
          SKPD</a>
        <a href="/superadmin/skpd/deleteuser" class="btn btn-sm btn-danger"
          onclick="return confirm('Akan menghapus Semua User AdminSKPD, Yakin?');"><i class="fas fa-trash"></i> Delete
          User & Pass SKPD</a>

        <a href="/superadmin/skpd/createsuperadmin" class="btn btn-sm btn-primary"><i class="fas fa-key"></i> Pass
          Superadmin</a>
        <br /><br />
        <div class="card">
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>#</th>
                  <th></th>
                  <th>Kode</th>
                  <th>Nama</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              @php
              $no =1;
              @endphp
              <tbody>
                @foreach (skpd() as $item)
                <tr>
                  <td>{{$no++}}</td>
                  <td><img src="/login_tpp/images/icons/logo.png" width="40px"></td>
                  <td><strong>{{$item->kode_skpd}}</strong>
                    <br />
                    @if ($item->user_id == null)
                    <a href="/superadmin/skpd/createuser/{{$item->id}}" class="btn btn-sm btn-secondary"><i
                        class="fas fa-key"></i> Create User</a>
                    @else
                    <a href="/superadmin/skpd/resetpassword/{{$item->id}}" class="btn btn-sm btn-secondary"
                      onclick="return confirm('Yakin ingin reset Password?');"><i class="fas fa-key"></i> Reset
                      Password</a>

                    @endif
                  </td>
                  <td>
                    <strong>{{$item->nama}}</strong><br />
                    <a href="/superadmin/skpd/pegawai/{{$item->id}}" class="btn btn-sm btn-info"><i
                        class="fas fa-users"></i> ASN : {{$item->pegawai->count()}}</a>
                    <a href="/superadmin/skpd/tpp" class="btn btn-sm btn-success"><i class="fas fa-money-bill"></i> TPP
                      : Rp. 0-.,</a>
                    <a href="/superadmin/skpd/jabatan/{{$item->id}}" class="btn btn-sm bg-purple"><i
                        class="fas fa-university"></i> PETA JABATAN : {{$item->jabatan->count()}}</a>
                    <a href="/superadmin/skpd/login/{{$item->id}}" target="_blank" class="btn btn-sm bg-danger">LOG IN
                      <i class="fas fa-arrow-right"></i> </a>

                  </td>
                  <td>

                    <a href="/superadmin/skpd/edit/{{$item->id}}" class="btn btn-sm btn-warning"><i
                        class="fas fa-edit"></i></a>
                    <a href="/superadmin/skpd/delete/{{$item->id}}" class="btn btn-sm btn-danger"
                      onclick="return confirm('Yakin ingin di hapus?');"><i class="fas fa-trash"></i></a>
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