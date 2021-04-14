<div class="row">
    <div class="col-12">
        <div class="card card-info">
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/superadmin/rekapitulasi/pns/kelas-jabatan/search">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-sm-6">
                        <select name="kelas_id" class="form-control">
                            <option value="">-Kelas Jabatan-</option>
                            @foreach ($kelas as $item)
                                <option value="{{$item->id}}" {{old('kelas_id') == $item->id ? 'selected':''}}>{{$item->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6 text-left">
                        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Tampilkan</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Data ASN Berdasarkan Kelas Jabatan (Total : {{$data->total()}})</h3>

          {{-- <div class="card-tools">
            <form method="get" action="/superadmin/pegawai/search">
            <div class="input-group input-group-sm" style="width: 300px;">
              <input type="text" name="search" class="form-control input-sm float-right" value="{{old('search')}}" placeholder="Cari NIP / Nama">

              <div class="input-group-append">
                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
              </div>
            </div>
            </form>
          </div> --}}
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>#</th>
                <th>NIP / Username</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Kelas</th>
              </tr>
            </thead>
            @php
                $no =1;
            @endphp
            <tbody>
            @foreach ($data as $key => $item)
                  <tr>
                    <td>{{$key+ $data->firstItem()}}</td>
                    <td>{{$item->nip}}</td>
                    <td>{{$item->nama}}</td>
                    <td>{{$item->jabatan == null ? '': $item->jabatan->nama}}</td>
                    <td>{{$item->jabatan == null ? '': $item->jabatan->kelas->nama}}</td>
                  </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    {{$data->links()}} 
    </div>
</div>