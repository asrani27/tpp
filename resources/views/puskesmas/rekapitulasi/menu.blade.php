<a href="/home/puskesmas" class="btn btn-sm btn-flat btn-secondary">KEMBALI</a>
<a href="/puskesmas/rekapitulasi/{{$bulan}}/{{$tahun}}/reguler" class="btn btn-sm btn-flat btn-primary">REGULER</a>
@if (Auth::user()->username == '1.02.01.8')
<a href="/puskesmas/rekapitulasi/{{$bulan}}/{{$tahun}}/plt" class="btn btn-sm btn-flat btn-primary">PLT</a>
@endif
<a href="/puskesmas/rekapitulasi/{{$bulan}}/{{$tahun}}/cpns" class="btn btn-sm btn-flat btn-primary">CPNS</a>
<a href="/puskesmas/rekapitulasi/{{$bulan}}/{{$tahun}}/reguler/excel" class="btn btn-sm btn-flat btn-primary"><i
        class="fas fa-file-excel"></i> EXPORT EXCEL</a>