@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    
@endpush
@section('title')
    ADMIN SKPD {{strtoupper(Auth::user()->name)}}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>{{countPegawaiSkpd(Auth::user()->skpd->id)}}</h3>
  
                  <p>ASN</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="#" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3>{{countJabatanSkpd(Auth::user()->skpd->id)}}</h3>
  
                  <p>PETA JABATAN</p>
                </div>
                <div class="icon">
                    <i class="fas fa-map"></i>
                </div>
                <a href="#" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-6 col-12">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>Rp. 6.342.123.456,-</h3>

                  <p>Total TPP Bulan {{\Carbon\Carbon::now()->isoFormat("MMMM Y")}}</p>
                  
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div> 
        </div>
        
        <div class="row">
            <div class="col-12 text-center">
                <strong>DAFTAR TPP ASN<br/>
                BULAN JANUARI 2021<br/>
                {{strtoupper(Auth::user()->name)}}</strong>
            </div>
        </div>
        <a href="#" class="btn btn-sm btn-primary">
            <i class="fas fa-file-excel"></i>
            Export</a><br/><br/>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                      <table class="table table-sm table-bordered">
                        <thead>
                          <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif" class="text-center bg-gradient-primary">
                            <th rowspan=2 style="width: 10px"></th>
                            <th rowspan=2 style="width: 10px">#</th>
                            <th rowspan=2>Nama /NIP/Pangkat/Golongan</th>
                            <th rowspan=2>Jabatan</th>
                            <th rowspan=2>Jenis Jabatan</th>
                            <th rowspan=2>Kelas</th>
                            <th rowspan=2>Basic TPP</th>
                            <th colspan=4>Beban Kerja</th>
                            <th colspan=2>Disiplin 40%</th>
                            <th colspan=2>Produktivitas 60%</th>
                            <th rowspan=2>TPP ASN</th>
                          </tr>
                          <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif" class="text-center bg-gradient-primary">
                              <th>Persentase TPP</th>
                              <th>Tambahan Persentase TPP</th>
                              <th>Jumlah Persentase</th>
                              <th>Total Pagu</th>
                              <th>%</th>
                              <th>Rp.</th>
                              <th>%</th>
                              <th>Rp.</th>
                          </tr>
                        </thead>
                        @php
                            $no=1;
                        @endphp
                        <tbody>
                          @foreach ($data as $key => $item)
                              
                          <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif">
                          
                            <td>
                              @if ($key == 0)
                                <a href="/home/admin/down/{{$item->id}}/{{$item->urutan}}"><i class="fas fa-caret-down"></i></a>
                              @else
                                <a href="/home/admin/up/{{$item->id}}/{{$item->urutan}}"><i class="fas fa-caret-up"></i></a>
                                <a href="/home/admin/down/{{$item->id}}/{{$item->urutan}}"><i class="fas fa-caret-down"></i></a>
                              @endif
                            </td>
                            <td>{{$no++}}</td>
                            <td>
                                {{$item->nama}} <br/>
                                {{$item->pangkat == null ? '-':$item->pangkat->nama}}<br/>
                                NIP. {{$item->nip}}

                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          @endforeach
                          
                        </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                  </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

@endpush