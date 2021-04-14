<div class="row">
    <div class="col-12">
        <div class="card card-info">
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/superadmin/rekapitulasi/pns/tingkat-pendidikan/search">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-sm-6">
                        <select name="jenjang" class="form-control">
                            <option value="">-Jenjang Pendidikan-</option>
                            @foreach ($pendidikan as $item)
                                <option value="{{$item}}" {{old('jenjang') == $item ? 'selected':''}}>{{$item}}</option>
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
          <h3 class="card-title">Data ASN Berdasarkan Tingkat Pendidikan (Total : {{$data->total()}})</h3>
        
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap table-sm">
            <thead>
              <tr>
                <th>#</th>
                <th>NIP / Username</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Jenjang Pendidikan</th>
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
                    <td>{{$item->jenjang_pendidikan}}</td>
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