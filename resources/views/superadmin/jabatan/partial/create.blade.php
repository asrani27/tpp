<form method="POST" action="/superadmin/skpd/jabatan/{{$skpd_id}}">
    @csrf
    <div class="form-group">
        <select class="form-control select2" name="jabatan_id" style="width: 100%;">
            @if (jabatan($skpd_id)->count() == 0)
                <option value="" selected="selected">Top Level</option>
            @else
                @php
                    $tingkat = $skpd_id == 21 ? 5 : 4;
                @endphp
                @foreach (jabatan($skpd_id)->where('tingkat', '!=', $tingkat) as $item)
                <option value="{{$item->id}}">{{$item->nama}}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="nama" placeholder="nama jabatan" required>
    </div>
    <div class="form-group">
        <select class="form-control select2" name="kelas_id" style="width: 100%;" required>
            <option value="" selected="selected">-Kelas Jabatan-</option>
                @foreach (kelas() as $item)
                <option value="{{$item->id}}">{{$item->nama}}</option>
                @endforeach
        </select>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-block btn-primary">Simpan</button>
    </div>
</form>