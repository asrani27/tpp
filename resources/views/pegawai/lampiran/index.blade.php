@extends('layouts.app')

@push('css')

@endpush

@section('title')
LAMPIRAN WFH
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-12">
                <a href="/pegawai/lampiran/wfh/create" class="btn btn-success btn-block"><i class="fas fa-plus"></i> Tambah
                    Lampiran WFH</a>
            </div>
        </div>
        <br />

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Lampiran WFH</h3>

                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>URL Google Drive</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $item)
                                <tr style="font-size:12px; font-family:Arial, Helvetica, sans-serif">
                                    <td>{{$key + $data->firstItem()}}</td>
                                    <td>{{\Carbon\Carbon::parse($item->tanggal)->isoFormat('dddd')}}, {{\Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMMM Y')}}</td>
                                    <td>
                                        <a href="{{$item->url_google_drive}}" target="_blank" class="text-primary">
                                            <i class="fas fa-external-link-alt"></i> Buka Link
                                        </a>
                                    </td>
                                    <td>
                                        <a href="/pegawai/lampiran/wfh/edit/{{$item->id}}"
                                            class="btn btn-xs btn-success text-white" data-toggle="tooltip"
                                            title="edit data"><i class="fas fa-edit"></i></a>
                                        <a href="/pegawai/lampiran/wfh/delete/{{$item->id}}"
                                            class="btn btn-xs btn-danger text-white" data-toggle="tooltip"
                                            title="hapus data" onclick="return confirm('Yakin ingin di hapus?');"><i
                                                class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{$data->links()}}
            </div>
        </div>
        <br />

    </div>
</div>
@endsection

@push('js')

@endpush
