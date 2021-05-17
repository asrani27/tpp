@extends('layouts.app')

@push('css')
  <link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
    ADMIN SKPD
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <h4>Peta Jabatan</h4>
        
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card card-widget widget-user-2">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-info">
                      <div class="widget-user-image">
                        <img class="img-circle elevation-2" src="/login_tpp/images/icons/logo.png" alt="User Avatar">
                      </div>
                      <!-- /.widget-user-image -->
                      <h3 class="widget-user-username">{{detailSkpd($skpd_id)->nama}}</h3>
                      <h5 class="widget-user-desc">Kode Skpd: {{detailSkpd($skpd_id)->kode_skpd}}</h5>
                    </div>
                    
                  </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-12">
                <div class="card">
                    <div class="card-body">
                        @if ($edit == true)
                            @include('admin.jabatan.partial.edit')
                        @else
                            @include('admin.jabatan.partial.create')
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-12">
                
            <ul>
                @foreach (jabatan($skpd_id)->where('jabatan_id', null) as $item)
                
                <li>
                    <div class="callout callout-info text-sm" style="padding:5px;">
                        {{$item->kelas == null ? '-':$item->kelas->nama}} |
                        <strong>{{$item->nama}}</strong> 
                            <a href="/admin/jabatan/edit/{{$item->id}}" class="btn btn-tool" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="/admin/jabatan/delete/{{$item->id}}" class="btn btn-tool"  data-toggle="tooltip" title="Hapus" onclick="return confirm('Yakin ingin di hapus?');"><i class="fas fa-times"></i></a>
                            
                    </div>           
                    <ul>
                        @foreach ($item->bawahan as $item2)
                        <li>
                            <div class="callout callout-warning text-sm" style="padding:5px;">
                                {{$item2->kelas == null ? '-':$item2->kelas->nama}} |
                                <strong>{{$item2->nama}}</strong>
                                <a href="/admin/jabatan/edit/{{$item2->id}}" class="btn btn-tool" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="/admin/jabatan/delete/{{$item2->id}}" class="btn btn-tool"  data-toggle="tooltip" title="Hapus" onclick="return confirm('Yakin ingin di hapus?');"><i class="fas fa-times"></i></a>
                            </div>
                            <ul>
                                
                            @foreach ($item2->bawahan as $item3)
                            <li>
                                <div class="callout callout-danger text-sm" style="padding:5px;">
                                    {{$item3->kelas == null ? '-':$item3->kelas->nama}} |
                                    <strong>{{$item3->nama}}</strong>
                                    <a href="/admin/jabatan/edit/{{$item3->id}}" class="btn btn-tool" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="/admin/jabatan/delete/{{$item3->id}}" class="btn btn-tool"  data-toggle="tooltip" title="Hapus" onclick="return confirm('Yakin ingin di hapus?');"><i class="fas fa-times"></i></a>
                                </div>
                                <ul>
                                    @foreach ($item3->bawahan as $item4)
                                        <li>
                                            <div class="callout callout-danger text-sm" style="padding:5px;">
                                                {{$item4->kelas == null ? '-':$item4->kelas->nama}} |
                                                <strong>{{$item4->nama}}</strong>
                                                <a href="/admin/jabatan/edit/{{$item4->id}}" class="btn btn-tool" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
                                                <a href="/admin/jabatan/delete/{{$item4->id}}" class="btn btn-tool"  data-toggle="tooltip" title="Hapus" onclick="return confirm('Yakin ingin di hapus?');"><i class="fas fa-times"></i></a>
                                            </div>
                                            <ul>
                                                @foreach ($item4->bawahan as $item5)
                                                    <li>
                                                        <div class="callout callout-danger text-sm" style="padding:5px;">
                                                            {{$item5->kelas == null ? '-':$item5->kelas->nama}} |
                                                            <strong>{{$item5->nama}}</strong>
                                                            <a href="/admin/jabatan/edit/{{$item5->id}}" class="btn btn-tool" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
                                                            <a href="/admin/jabatan/delete/{{$item5->id}}" class="btn btn-tool"  data-toggle="tooltip" title="Hapus" onclick="return confirm('Yakin ingin di hapus?');"><i class="fas fa-times"></i></a>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            @endforeach
                            </ul>
                        </li>
                        @endforeach
                    </ul>
                </li>     
                @endforeach  
            </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<!-- Select2 -->
<script src="/theme/plugins/select2/js/select2.full.min.js"></script>
<script>
    $(function () {
      //Initialize Select2 Elements
      $('.select2').select2()
  
      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })
    })
</script>  
@endpush