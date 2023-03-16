@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
ADMIN PUSKESMAS
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card card-widget widget-user-2">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-info">
                        <div class="widget-user-image">
                            <img class="img-circle elevation-2" src="/login_tpp/images/icons/logo.png"
                                alt="User Avatar">
                        </div>
                        <!-- /.widget-user-image -->
                        <h3 class="widget-user-username">Laporan TPP {{Auth::user()->name}}</h3>
                        <h5 class="widget-user-desc">Menampilkan data Laporan TPP</h5>
                    </div>

                </div>
            </div>
        </div>
        
        @include('puskesmas.rekapitulasi.menu')
    </div>
</div>


@endsection

@push('js')

<script>
    $(document).on('click', '.editbpjs', function() {
       $('#id_nama').val($(this).data('nama'));
       $('#id_rekap').val($(this).data('id'));
       $('#id_1persen').val($(this).data('1persen'));
       $('#id_4persen').val($(this).data('4persen'));
       $("#modal-bpjs").modal();
    });
</script>
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
<script>
    function hanyaAngka(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
 
    return false;
    return true;
}
</script>
@endpush