<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table border=1 cellpadding="10px" cellspacing="0">
        <tr>
            <td>no</td>
            <td>nip</td>
            <td>nama</td>
            <td>kelas</td>
            <td>jabatan</td>
            <td>Kelas Jabatan</td>
            <td>skpd</td>
            <td>status pns</td>
            <td>is aktif</td>
            <td>atasan</td>
        </tr>
        @foreach ($data as $key=>$item)
            <tr>
                <td>{{$key + 1}}</td>
                <td>{{$item['nip']}}</td>
                <td>{{$item['nama']}}</td>
                <td>{{$item['kelas']}}</td>
                <td>{{$item['nama_jabatan']}}</td>
                <td>{{$item['skpd']}}</td>
                <td>{{$item['status_pns']}}</td>
                <td>{{$item['is_aktif']}}</td>
                <td>{{$item['atasan']}}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>