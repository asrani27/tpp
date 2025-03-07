<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIP</th>
                <th>NAMA</th>
                <th>NILAI TPP</th>
                <th>NILAI BPJS 1%</th>
                <th>NILAI BPJS 4%</th>
                <th>PAJAK</th>
                <th>TPP DIBAYARKAN</th>
                <th>SKPD</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reguler as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ strtoupper($item->pegawai == null ? null : $item->pegawai->nama) }}</td>
                <td>{{ strtoupper($item->pegawai == null ? null : "'".$item->pegawai->nip) }}</td>
                <td>{{ strtoupper($item->nama) }}</td>
                <td>{{ strtoupper($item->jenis_jabatan) }}</td>
                <td>{{ strtoupper($item->persen_beban_kerja) }}</td>
                <td>{{ strtoupper($item->persen_tambahan_beban_kerja) }}</td>
                <td>{{ strtoupper($item->persen_prestasi_kerja) }}</td>
                <td>{{ strtoupper($item->persen_kondisi_kerja) }}</td>
                <td>{{ strtoupper($item->persen_kelangkaan_profesi) }}</td>
                <td>{{ strtoupper($item->atasan == null ? null : $item->atasan->nama) }}</td>
                <td>{{ strtoupper($item->skpd == null ? null : $item->skpd->nama) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>