<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="en-us" http-equiv="Content-Language" />
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>TPP ASN</title>
<style type="text/css">
.auto-style1 {
	font-size: small;
}
.auto-style2 {
	text-align: center;
}
.auto-style3 {
	border: 1px solid #000000;
	font-family: Arial, Helvetica, sans-serif;

	font-size: xx-small;
	text-align: center;
}
.auto-style5 {
	border: 1px solid #000000;
	font-size: xx-small;
	
}
.auto-style4 {
	font-size: x-small;
}
</style>
</head>

<body>

<p class="auto-style2"><strong><span class="auto-style1">DAFTAR TPP ASN
</span><br class="auto-style1" />
<span class="auto-style1">BULAN {{strtoupper($bulantahun)}} </span><br class="auto-style1" />
<span class="auto-style1">{{strtoupper(Auth::user()->name)}}</span></strong></p>
<table style="width: 100%" cellpadding="2" cellspacing="0" >
	<tr>
		<td class="auto-style3" rowspan="2"><strong>NO</strong></td>
		<td class="auto-style3" rowspan="2"><strong>Nama/NIP/Pangkat/Golongan</strong></td>
		<td class="auto-style3" rowspan="2"><strong>Jabatan</strong></td>
		<td class="auto-style3" rowspan="2"><strong>Jenis Jabatan</strong></td>
		<td class="auto-style3" rowspan="2"><strong>Kelas</strong></td>
		<td class="auto-style3" rowspan="2"><strong>Basic TPP</strong></td>
		<td class="auto-style3" colspan="4" style="height: 26px"><strong>BEBAN KERJA</strong></td>
		<td class="auto-style3" colspan="2" style="height: 26px"><strong>Disiplin 40%</strong></td>
		<td class="auto-style3" colspan="2" style="height: 26px"><strong>Produktivitas 60%</strong></td>
		<td class="auto-style3" rowspan="2"><strong>TPP ASN</strong></td>
		<td class="auto-style3" rowspan="2"><strong>PPH21</strong></td>
		<td class="auto-style3" rowspan="2"><strong>Hukuman Disiplin</strong></td>
		<td class="auto-style3" rowspan="2"><strong>Potongan BPJS</strong></td>
		<td class="auto-style3" rowspan="2" style="height: 26px"><strong>TPP Diterima</strong></td>
		<td class="auto-style3" rowspan="2"><strong>TTD</strong></td>
	</tr>
	<tr>
		<td class="auto-style3"><strong>Persentase TPP</strong></td>
		<td class="auto-style3"><strong>Tambahan Persentase TPP</strong></td>
		<td class="auto-style3"><strong>Jumlah Persentase</strong></td>
		<td class="auto-style3"><strong>Total Pagu</strong></td>
		<td class="auto-style3"><strong>%</strong></td>
		<td class="auto-style3"><strong>Rp.</strong></td>
		<td class="auto-style3"><strong>Menit</strong></td>
		<td class="auto-style3"><strong>Rp.</strong></td>
	</tr>
        @if ($tpp == true) 
        @php
            $no=1;
            $count = $data->count();
        @endphp
        
        <tbody>
            @foreach ($data as $key => $item)   
            <tr style="font-size:10px; font-family:Arial, Helvetica, sans-serif">
            <td class="auto-style5">{{$no++}}</td>
            <td class="auto-style5">
                {{$item->nama}} <br/>
                @if ($item->nama_pangkat == null)
                <a href="#" data-toggle="tooltip" data-placement="top" title="Pangkat Kosong!"> <span class="text-danger"><i class="fas fa-exclamation-triangle"></i></span></a>
                @else  
                    {{$item->nama_pangkat}}<br/>
                @endif
                NIP.{{$item->nip}}

            </td>
            <td class="text-center auto-style5">
                {{$item->nama_jabatan}}
            </td>
            <td class="text-center auto-style5">
                {{$item->jenis_jabatan}}
            </td>
            <td class="text-center auto-style5">
                {{$item->nama_kelas}}
            </td>
            <td class="text-right auto-style5">
                {{currency($item->basic_tpp)}}
            </td>
            <td class="text-center auto-style5">
                {{$item->persentase_tpp}} %
            </td>
            <td class="text-center auto-style5">
                {{$item->tambahan_persen_tpp == null ? 0: $item->tambahan_persen_tpp}} %
            </td>
            <td class="text-center auto-style5">
                {{$item->jumlah_persentase}} %
            </td>
            <td class="text-right auto-style5">
                {{currency($item->total_pagu)}}
            </td>
            <td class="text-right auto-style5">
                {{$item->persen_disiplin}}
            </td>
            <td class="text-right auto-style5">
                {{currency($item->total_disiplin)}}
            </td>
            <td class="text-right auto-style5">
                {{$item->persen_produktivitas}} m
            </td>
            <td class="text-right auto-style5">
                {{currency($item->total_produktivitas)}}
            </td>
            <td class="text-right auto-style5">
                {{currency($item->total_tpp)}}
            </td> 
            <td class="text-right auto-style5">
                {{$item->pph}} % <br>
                {{$item->pph_angka == 0 ? '':'-'}}{{currency($item->pph_angka)}}
            </td> 
            <td class="text-right auto-style5">
                {{$item->hukuman}} % <br>
                {{$item->hukuman_angka == 0 ? '':'-'}}{{currency($item->hukuman_angka)}}
            </td> 
            <td class="text-right auto-style5">
                0
            </td> 
            <td class="text-right auto-style5">
                {{currency($item->tpp_diterima)}}
            </td> 
            <td class="auto-style5"> </td>
            </tr>
            @endforeach    
        </tbody>
        <tfoot>
            <tr>
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
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Total</td>
            <td>{{currency($data->sum('tpp_diterima'))}}</td>
            </tr>
        </tfoot>
        @endif
</table>
<br/>


</body>

</html>
