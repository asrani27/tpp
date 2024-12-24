<table>
    <thead>
        <tr>
            <th>NO</th>
            <th>NAMA</th>
            <th>NIP</th>
            <th>JABATAN</th>
            <th>KELAS JABATAN</th>
            <th>ATASAN PENILAI</th>
            <th>SKPD</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $key => $item)
        <tr>
            <td>{{$key + 1}}</td>
            <td>{{ $item->nama }}</td>
            <td>'{{ $item->nip }}</td>
            <td>{{ $item->jabatan == null ? '': $item->jabatan->nama }}</td>
            <td>{{ ($item->jabatan == null ? '': $item->jabatan->nama) == null ? '' : $item->jabatan->kelas->nama }}
            </td>
            <td>
                @if ($item->jabatan == null)
                -
                @else

                @if ($item->jabatan->sekda == 1)
                WALIKOTA
                @elseif ($item->jabatan->sekolah_id != null)
                Kepala Sekolah
                {{$item->jabatan->sekolah->nama}}
                @else
                <h3 class="widget-user-username">
                    {{checkAtasan($item->namaatasan, $item)['nama']}}
                </h3>
                @endif
                @endif
            </td>
            <td>{{ $item->skpd == null ? '': $item->skpd->nama }}</td>
        </tr>
        @endforeach
    </tbody>
</table>