
<div class="row">
    <div class="col-md-12">
        <a href="/pegawai/new-skp" class="btn btn-sm btn-secondary" type="submit"><i class="fas fa-arrow-left"></i>  Kembali</a><br/><br/>
        <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body p-2 text-center text-sm">
                    <strong>
                    SKP<br/>
                </strong>
                </div>
              </div>
              
              
              <div class="card">
                <div class="card-body p-1">
                    <table class="table table-sm table-bordered">
                    <thead>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px; background-color:rgb(218, 236, 249)">
                            
                            <th colspan="7">HASIL KERJA</th>
                        </tr>
                        <tr class="text-center" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;background-color:rgb(218, 236, 249)">
                            <th>NO</th>
                            <th>RENCANA HASIL KERJA</th>
                            <th>INDIKATOR KINERJA INDIVIDU</th>
                            <th>TARGET</th>
                            <th>PERSPEKTIF</th>
                        </tr>
                        <tr class="text-center" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:8px;background-color:rgb(218, 236, 249);">
                            <th>(1)</th>
                            <th>(2)</th>
                            <th>(3)</th>
                            <th>(4)</th>
                            <th>(5)</th>
                        </tr>
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;background-color:rgb(218, 236, 249);">
                            <th colspan="7">A.UTAMA </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($skp_utama as $key => $item)

                            @php ($first = true) @endphp

                            @foreach ($item->indikator as $key2 => $item2) 
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                @if($first == true)
                                <td rowspan="{{$item->indikator->count()}}" class="text-center">{{$key+1}}</td>
                                <td rowspan="{{$item->indikator->count()}}">{{$item->rhk}}</td>
                                @php ($first = false) @endphp
                                @endif
                                <td>{{$item2->indikator}}</td>
                                <td class="text-center">{{$item2->target}}</td>
                                <td class="text-center">{{$item2->perspektif}}</td>
                            </tr>
                            @endforeach
                        @endforeach
                        <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;background-color:rgb(218, 236, 249);">
                            <th colspan="7">B.TAMBAHAN </th>
                        </tr>

                        @foreach ($skp_tambahan as $key => $item)

                            @php ($first = true) @endphp

                            @foreach ($item->indikator as $key2 => $item2) 
                            <tr style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:10px;">
                                @if($first == true)
                                <td rowspan="{{$item->indikator->count()}}" class="text-center">{{$key+1}}</td>
                                <td rowspan="{{$item->indikator->count()}}">{{$item->rhk}}</td>
                                @php ($first = false) @endphp
                                @endif
                                <td>{{$item2->indikator}}</td>
                                <td class="text-center">{{$item2->target}}</td>
                                <td class="text-center">{{$item2->perspektif}}</td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                    </table>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>