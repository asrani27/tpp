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
            </strong><span class="auto-style1"><strong>{{strtoupper(Auth::user()->name)}}</strong></span><strong><br
                    class="auto-style1" />
            </strong><span class="auto-style1"><strong>Bulan : {{convertBulan($bulan)}} {{$tahun}}
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Tanggal Cetak : {{\Carbon\Carbon::now()->format('d-m-Y H:i:s')}}</strong></span></p>
    </header>
    <main>
        <table cellpadding="5" cellspacing="0" border="1">
            <thead>
                <tr>
                    <th rowspan="4">NO</th>
                    <th rowspan="4">NAMA</th>
                    <th rowspan="4">NIP</th>
                    <th rowspan="4">PANGKAT/GOLONGAN</th>
                    <th rowspan="4">JABATAN</th>
                    <th rowspan="4">JENIS JABATAN</th>
                    <th rowspan="4">KELAS</th>
                    <th>Perhitungan</th>
                    <th colspan=9>Pembayaran</th>
                    <th rowspan=4>PPH 21</th>
                    <th rowspan=4>BPJS 1%</th>
                    <th rowspan=4>BPJS 4%</th>
                    <th rowspan=4>TPP Diterima<br />Transfer</th>
                    <th rowspan=4>Tanda Tangan</th>
                </tr>
                <tr>
                    <th rowspan="3">Basic TPP</th>
                    <th colspan="2">Beban Kerja</th>
                    <th rowspan="3">Jumlah <br />Beban Kerja<br />5.1.01.02.01.0001</th>
                    <th colspan="2">Prestasi Kerja</th>
                    <th rowspan="3">Jumlah <br />Prestasi Kerja <br />5.1.01.02.05.0001</th>
                    <th rowspan="3">Kondisi Kerja</th>
                    <th rowspan="3">Jumlah <br />Kondisi Kerja <br />5.1.01.02.03.0001</th>
                    <th rowspan="3">Jumlah <br /> Pembayaran</th>
                </tr>
                <tr>
                    <th>Disiplin Kerja</th>
                    <th>Produktivitas</th>
                    <th>Disiplin Kerja</th>
                    <th>Produktivitas</th>
                </tr>
                <tr>
                    <th>40%</th>
                    <th>60%</th>
                    <th>40%</th>
                    <th>60%</th>
                </tr>
            </thead>
            <tbody>
                @php
                $no = 1;
                @endphp
                @foreach ($data as $item)
                <tr>
                    <td valign="top">{{$no++}}</td>
                    <td valign="top">{{strtoupper($item->nama)}}</td>
                    <td valign="top">NIP.{{$item->nip}}</td>
                    <td align="center" valign="top">{{$item->pangkat}} ({{$item->golongan}})</td>
                    <td align="center" valign="top">{{$item->jabatan}}</td>
                    <td align="center" valign="top">{{$item->jenis_jabatan}}</td>
                    <td align="center" valign="top">{{$item->kelas}}</td>
                    <td align="right" valign="top">{{currency($item->perhitungan_basic_tpp)}}</td>
                    <td align="right" valign="top">{{currency($item->pembayaran_bk_disiplin)}}</td>
                    <td align="right" valign="top">{{currency($item->pembayaran_bk_produktivitas)}}</td>
                    <td align="right" valign="top">{{currency($item->pembayaran_beban_kerja)}}</td>
                    <td align="right" valign="top">{{currency($item->pembayaran_pk_disiplin)}}</td>
                    <td align="right" valign="top">{{currency($item->pembayaran_pk_produktivitas)}}</td>
                    <td align="right" valign="top">{{currency($item->pembayaran_prestasi_kerja)}}</td>
                    <td align="right" valign="top">{{currency($item->pembayaran_kondisi_kerja)}}</td>
                    <td align="right" valign="top">{{currency($item->pembayaran_kondisi_kerja)}}</td>
                    <td align="right" valign="top">{{currency($item->pembayaran)}}</td>
                    <td align="right" valign="top">{{currency($item->potongan_pph21)}}</td>
                    <td align="right" valign="top">{{currency($item->potongan_bpjs_1persen)}}</td>
                    <td align="right" valign="top">{{currency($item->potongan_bpjs_4persen)}}</td>
                    <td align="right" valign="top">{{currency($item->tpp_diterima)}}</td>
                    <td>
                        <br />
                        <br />
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td colspan=10 align="right">Total</td>
                    <td align="right">{{currency($data->sum('pembayaran_beban_kerja'))}}</td>
                    <td></td>
                    <td></td>
                    <td align="right">{{currency($data->sum('pembayaran_prestasi_kerja'))}}</td>
                    <td></td>
                    <td align="right">{{currency($data->sum('pembayaran_kondisi_kerja'))}}</td>
                    <td align="right">{{currency($data->sum('pembayaran'))}}</td>
                    <td align="right">{{currency($data->sum('potongan_pph21'))}}</td>
                    <td align="right">{{currency($data->sum('potongan_bpjs_1persen'))}}</td>
                    <td align="right">{{currency($data->sum('potongan_bpjs_4persen'))}}</td>
                    <td align="right">{{currency($data->sum('tpp_diterima'))}}</td>
                </tr>
            </tbody>
        </table>
    </main>
</body>

</html>