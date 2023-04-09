<a href="/admin/rekapitulasi" class="btn btn-sm btn-flat btn-secondary">KEMBALI</a>
<a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/reguler" class="btn btn-sm btn-flat btn-primary">REGULER</a>
<a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/cpns" class="btn btn-sm btn-flat btn-primary">CPNS</a>
<a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/plt" class="btn btn-sm btn-flat btn-primary">PLT</a>
@if (Auth::user()->skpd->id == 1)
<a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/tu" class="btn btn-sm btn-flat btn-primary">TU</a>
@endif
@if (Auth::user()->username == '4.01.03.')
    
<a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/reguler/excel/setda" class="btn btn-sm btn-flat btn-primary"><i class="fas fa-file-excel"></i> EXPORT EXCEL</a>
@else
<a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/reguler/excel" class="btn btn-sm btn-flat btn-primary"><i class="fas fa-file-excel"></i> EXPORT EXCEL</a>

@endif