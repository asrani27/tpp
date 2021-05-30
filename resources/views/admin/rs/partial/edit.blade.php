<form method="POST" action="/admin/jabatan/edit/{{$id}}">
    @csrf
    <div class="form-group">
            {{-- @if ($jabatan->jabatan_id == null)
            <select class="form-control select2" name="jabatan_id" style="width: 100%;" readonly>
                <option value="">Top Level</option>
            </select>
            @else 
            
            <select class="form-control select2" name="jabatan_id" style="width: 100%;">
                @foreach (jabatan($skpd_id)->where('id', '!=', $jabatan->id) as $jab)
                    <option value="{{$jab->id}}" {{$jabatan->jabatan_id == $jab->id ? 'selected' : ''}}>{{$jab->nama}}</option>
                @endforeach
            </select>
            @endif --}}
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="nama" value="{{$jabatan->nama}}" required>
    </div>
    <div class="form-group">
        <select class="form-control select2" name="kelas_id" style="width: 100%;" required>
            <option value="" selected="selected">-Kelas Jabatan-</option>
                @foreach (kelas() as $item)
                <option value="{{$item->id}}" {{$jabatan->kelas_id == $item->id ? 'selected' : ''}}>{{$item->nama}}</option>
                @endforeach
        </select>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-block btn-primary">Update</button>
        <a href="/admin/rspuskesmas/{{$id}}/petajabatan" class="btn btn-block btn-warning">New</a>
    </div>
</form>