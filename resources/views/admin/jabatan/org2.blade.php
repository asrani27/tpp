<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>STRUKTUR ORGANISASI</title>

    <style>
        *,
        *:before,
        *:after {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        body {
            min-width: 1200px;
            margin: 0;
            padding: 50px;
            color: #eee9dc;
            font: 11px Verdana, sans-serif;
            background: #2e6ba7;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        #wrapper {
            position: relative;
        }

        .branch {
            position: relative;
            margin-left: 250px;
        }

        .branch:before {
            content: "";
            width: 50px;
            border-top: 2px solid #eee9dc;
            position: absolute;
            left: -100px;
            top: 50%;
            margin-top: 1px;
        }

        .entry {
            position: relative;
            min-height: 60px;
        }

        .entry:before {
            content: "";
            height: 100%;
            border-left: 2px solid #eee9dc;
            position: absolute;
            left: -50px;
        }

        .entry:after {
            content: "";
            width: 50px;
            border-top: 2px solid #eee9dc;
            position: absolute;
            left: -50px;
            top: 50%;
            margin-top: 1px;
        }

        .entry:first-child:before {
            width: 10px;
            height: 50%;
            top: 50%;
            margin-top: 2px;
            border-radius: 10px 0 0 0;
        }

        .entry:first-child:after {
            height: 10px;
            border-radius: 10px 0 0 0;
        }

        .entry:last-child:before {
            width: 10px;
            height: 50%;
            border-radius: 0 0 0 10px;
        }

        .entry:last-child:after {
            height: 10px;
            border-top: none;
            border-bottom: 2px solid #eee9dc;
            border-radius: 0 0 0 10px;
            margin-top: -9px;
        }

        .entry.sole:before {
            display: none;
        }

        .entry.sole:after {
            width: 50px;
            height: 0;
            margin-top: 1px;
            border-radius: 0;
        }

        .label {
            display: block;
            min-width: 150px;
            padding: 3px 10px;
            line-height: 20px;
            text-align: left;
            border: 2px solid #eee9dc;
            border-radius: 5px;
            position: absolute;
            left: 0;
            top: 50%;
            margin-top: -15px;
        }
    </style>
</head>

<body>

    <div id="wrapper"><span class="label">{{strtoupper($jabatan->nama)}} <br />{{$jabatan->pegawai == null ? '-':
            $jabatan->pegawai->nama}}</span>
        <div class="branch lv1">
            @if ($jabatan->bawahan->count() != 0)
            @foreach ($jabatan->bawahan as $item)
            <div class="entry"><span class="label">{{strtoupper($item->nama)}}<br />{{$item->pegawai == null ? '-':
                    $item->pegawai->nama}}</span>
                <div class="branch lv2">
                    @if ($item->bawahan->count() != 0)
                    @foreach ($item->bawahan as $item2)
                    <div class="entry"><span class="label">{{strtoupper($item2->nama)}}<br />{{$item2->pegawai == null ?
                            '-':
                            $item2->pegawai->nama}}</span>
                        {{-- <div class="branch lv3">
                            <div class="entry"><span class="label">Entry-1-1-1</span>
                                <div class="branch lv4">
                                    <div class="entry"><span class="label">Entry-1-1-1-1</span></div>
                                    <div class="entry"><span class="label">Entry-1-1-1-1</span></div>
                                </div>
                            </div>
                            <div class="entry"><span class="label">Entry-1-1-1</span> </div>
                        </div> --}}
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</body>

</html>