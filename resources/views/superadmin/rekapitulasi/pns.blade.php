@extends('layouts.app')

@push('css')
  <link rel="stylesheet" href="/theme/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="/theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush
@section('title')
    SUPERADMIN
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <h4>ASN PEMERINTAH KOTA BANJARMASIN</h4>
        
        <a href="/superadmin/rekapitulasi/pns/jkel" class="btn btn-sm {{$jeniscari == 'jkel' ? 'btn-danger':'btn-info'}}"><i class="fas fa-users"></i> ASN Berdasarkan Jenis Kelamin</a>
        <a href="/superadmin/rekapitulasi/pns/kelas-jabatan" class="btn btn-sm {{$jeniscari == 'kelas' ? 'btn-danger':'btn-info'}}"><i class="fas fa-users"></i> ASN Berdasarkan Kelas Jabatan</a>
        <a href="/superadmin/rekapitulasi/pns/tingkat-pendidikan" class="btn btn-sm btn-info"><i class="fas fa-users"></i> ASN Berdasarkan Tingkat Pendidikan</a>
        <a href="/superadmin/rekapitulasi/pns/eselon" class="btn btn-sm btn-info"><i class="fas fa-users"></i> ASN Berdasarkan Eselon</a>
        <a href="/superadmin/rekapitulasi/pns/pangkat" class="btn btn-sm btn-info"><i class="fas fa-users"></i> ASN Berdasarkan Pangkat & Golongan</a>
        <br/>
        <br/>
        <a href="/superadmin/rekapitulasi/pns/aktivitas" class="btn btn-sm bg-info"><i class="fas fa-users"></i> Aktivitas ASN</a>
        <br/><br/>
        @if ($jeniscari == null)
            
        @else
            @include('superadmin.rekapitulasi.partials.'.$jeniscari)
        @endif
    </div>
</div>

@endsection

@push('js')


@endpush