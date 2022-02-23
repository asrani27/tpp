@php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=tpp.xls");
@endphp
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta content="en-us" http-equiv="Content-Language" />
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>Untitled 1</title>

</head>

<body>
    <header>
        <p><span class="auto-style1"><strong>LAPORAN DAFTAR TPP ASN</strong></span><strong><br class="auto-style1" />
            </strong><span class="auto-style1"><strong>{{strtoupper($skpd->nama)}}</strong></span><strong><br
                    class="auto-style1" />
            </strong><span class="auto-style1"><strong>Bulan : {{\Carbon\Carbon::parse('m',
                    $bulan)->format('M')}} {{$tahun}}
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Tanggal Cetak : {{\Carbon\Carbon::now()->format('d-m-Y H:i:s')}}</strong></span></p>
    </header>
    <main>
        <table cellpadding="5" cellspacing="0" border="1">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nama<br />NIP<br />Pangkat/Golongan</th>
                    <th rowspan="2">Jabatan</th>
                    <th rowspan="2">Kelas</th>
                    <th rowspan="2">Basic TPP</th>
                    <th colspan="4">Beban Kerja</th>
                    <th colspan="2">Disiplin 40%</th>
                    <th colspan="2">Produktivitas 60%</th>
                    <th rowspan="2">TPP ASN</th>
                    <th rowspan="2">PPH21</th>
                    <th rowspan="2">TPP Diterima</th>
                </tr>
                <tr>
                    <th>Persentase <br />TPP</th>
                    <th>Tambahan<br />Persentase<br />TPP</th>
                    <th>Jumlah<br />Persentase</th>
                    <th>Total<br />Pagu</th>
                    <th>%</th>
                    <th>Rp.</th>
                    <th>Menit</th>
                    <th>Rp.</th>
                </tr>
            </thead>
            <tbody>
                @php
                $no = 1;
                @endphp
                @foreach ($data as $item)
                <tr>
                    <td valign="top">{{$no++}}</td>
                    <td>
                        {{$item->nama}} <br />
                        {{$item->pangkat}} ({{$item->golongan}})<br />
                        NIP.{{$item->nip}}
                    </td>
                    <td valign="top">{{$item->jabatan}}</td>
                    <td valign="top" style="text-align: center">{{$item->kelas}}</td>
                    <td valign="top">{{currency($item->basic_tpp)}}</td>
                    <td valign="top" style="text-align: center">{{$item->persen}}</td>
                    <td valign="top" style="text-align: center">{{$item->tambahan_persen}}</td>
                    <td valign="top" style="text-align: center">{{$item->jumlah_persen}}</td>
                    <td valign="top">{{currency($item->total_pagu)}}</td>
                    <td valign="top">{{$item->absensi}}</td>
                    <td valign="top">{{currency($item->total_absensi)}}</td>
                    <td valign="top">{{$item->aktivitas}}</td>
                    <td valign="top">{{currency($item->total_aktivitas)}}</td>
                    <td valign="top">{{currency($item->total_absensi + $item->total_aktivitas)}}</td>
                    <td valign="top" style="text-align: right">{{$item->pph21}}<br />{{currency($item->total_pph21)}}
                    </td>
                    <td valign="top">{{currency($item->total_absensi + $item->total_aktivitas - $item->total_pph21)}}
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td colspan=13>Total </td>
                    <td>{{currency($data->sum('total_absensi') + $data->sum('total_aktivitas'))}}</td>
                    <td>{{currency($data->sum('total_pph21'))}}</td>
                    <td>{{currency($data->sum('total_absensi') + $data->sum('total_aktivitas') -
                        $data->sum('total_pph21'))}}</td>
                </tr>
            </tbody>
        </table>
    </main>
</body>

</html>