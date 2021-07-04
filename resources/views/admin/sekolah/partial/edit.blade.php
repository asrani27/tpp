<form method="POST" action="/admin/sekolah/{{$id}}/petajabatan/{{$data->id}}/edit">
    @csrf
    
    <div class="form-group">
        <input type="text" class="form-control" name="nama" value="{{$data->nama}}" required>
    </div>
    <div class="form-group">
        <select class="form-control select2" name="kelas_id" style="width: 100%;" required>
            <option value="" selected="selected">-Kelas Jabatan-</option>
                @foreach (kelas() as $item)
                <option value="{{$item->id}}" {{$data->kelas_id == $item->id ? 'selected' : ''}}>{{$item->nama}}</option>
                @endforeach
        </select>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-block btn-primary">Update</button>
        <a href="/admin/sekolah/{{$id}}/petajabatan" class="btn btn-block btn-warning">New</a>
    </div>
</form>