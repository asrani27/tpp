<form method="POST" action="/admin/rspuskesmas/{{$id}}/petajabatan">
    @csrf
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-12 col-form-label">Atasan langsung :</label>
        <select class="form-control select2" name="jabatan_id" style="width: 100%;">
            @foreach ($merge as $item)
            <option value="{{$item->id}}">{{$item->nama}}</option>                
            @endforeach
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