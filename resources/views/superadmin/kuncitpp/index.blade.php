@extends('layouts.app')

@section('content')

<div class="row">
    <!-- Left col -->
    <section class="col-lg-6 connectedSortable">
        <!-- Custom tabs (Charts with tabs)-->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-key mr-1"></i>
                    Kunci TPP DINAS
                </h3>
                <div class="card-tools">

                </div>
            </div><!-- /.card-header -->
            <div class="card-body">
                <form method="post" action="/superadmin/kuncitpp/dinas">
                    @csrf
                    <select class="form-control" name="skpd_id">
                        @foreach (Skpd() as $item)
                        <option value="{{$item->id}}">{{$item->nama}}</option>
                        @endforeach
                    </select>
                    <select class="form-control" name="bulan">
                        <option value="01">Januari</option>
                        <option value="02">Februari</option>
                        <option value="03">Maret</option>
                        <option value="04">April</option>
                        <option value="05">Mei</option>
                        <option value="06">Juni</option>
                        <option value="07">Juli</option>
                        <option value="08">Agustus</option>
                        <option value="09">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                    <select class="form-control" name="tahun">
                        <option value="2025">2025</option>
                    </select>
                    <button type="submit" class="btn btn-md btn-block btn-primary">Buka</button>
                </form>
            </div><!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.Left col -->
    <!-- right col (We are only adding the ID to make the widgets sortable)-->
    <section class="col-lg-6 connectedSortable">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-key mr-1"></i>
                    Kunci TPP Puskesmas
                </h3>
                <div class="card-tools">

                </div>
            </div><!-- /.card-header -->
            <div class="card-body">
                <form method="post" action="/superadmin/kuncitpp/puskesmas">
                    @csrf
                    <select class="form-control" name="puskesmas_id">
                        @foreach (Puskesmas() as $item)
                        <option value="{{$item->id}}">{{$item->nama}}</option>
                        @endforeach
                    </select>
                    <select class="form-control" name="bulan">
                        <option value="01">Januari</option>
                        <option value="02">Februari</option>
                        <option value="03">Maret</option>
                        <option value="04">April</option>
                        <option value="05">Mei</option>
                        <option value="06">Juni</option>
                        <option value="07">Juli</option>
                        <option value="08">Agustus</option>
                        <option value="09">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                    <select class="form-control" name="tahun">
                        <option value="2025">2025</option>
                    </select>
                    <button type="submit" class="btn btn-md btn-block btn-primary">Buka</button>
                </form>
            </div><!-- /.card-body -->
        </div>
    </section>
    <!-- right col -->
</div>
@endsection