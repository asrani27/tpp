<div class="row">
<!-- /.col -->
<div class="col-12 col-sm-6 col-md-6">
    <div class="info-box mb-3">
    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-male"></i></span>

    <div class="info-box-content">
        <span class="info-box-text">Laki-Laki</span>
        <span class="info-box-number">{{$l}}</span>
    </div>
    <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
</div>
<!-- /.col -->

<!-- fix for small devices only -->
<div class="clearfix hidden-md-up"></div>

<div class="col-12 col-sm-6 col-md-6">
    <div class="info-box mb-3">
    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-female"></i></span>

    <div class="info-box-content">
        <span class="info-box-text">Perempuan</span>
        <span class="info-box-number">{{$p}}</span>
    </div>
    <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
</div>
<!-- /.col -->
<!-- /.col -->
</div>
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Data ASN Berdasarkan Jenis Kelamin</h3>

          <div class="card-tools">
            <form method="get" action="/superadmin/pegawai/search">
            <div class="input-group input-group-sm" style="width: 300px;">
              <input type="text" name="search" class="form-control input-sm float-right" value="{{old('search')}}" placeholder="Cari NIP / Nama">

              <div class="input-group-append">
                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
              </div>
            </div>
            </form>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>#</th>
                <th>NIP / Username</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
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
                    <td>
                        @if ($item->jkel == NULL)
                            
                        @else
                            
                        {{$item->jkel == 'L' ? 'Laki-Laki': 'Perempuan'}}
                        @endif
                    </td>
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