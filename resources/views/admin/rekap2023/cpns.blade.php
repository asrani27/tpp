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
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-2">
                        @include('admin.rekap2023.menu')
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar TPP CPNS Bulan
                    {{convertBulan($bulan)}} {{$tahun}}</h3>
            </div>
            <div class="card-body p-2">
                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/excel" target="_blank"
                    class="btn btn-xs btn-primary">Export Excel</a>
                {{-- <a href="/home/admin/persen" class="btn btn-xs btn-danger">Pengaturan Persen TPP</a> --}}
                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/masukkanpegawai" class="btn btn-xs btn-primary"
                    onclick="return confirm('Yakin Ingin Memasukkan Semua Pegawai Pada Bulan Ini?');">Masukkan
                    Semua Pegawai</a>

                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/perhitungan" class="btn btn-xs btn-warning"
                    onclick="return confirm('Proses ini memakan beberapa waktu, harap di tunggu?');">Perhitungan</a>
                <a href="/admin/rekapitulasi/{{$bulan}}/{{$tahun}}/pembayaran" class="btn btn-xs btn-success"
                    onclick="return confirm('Proses ini memakan beberapa waktu, harap di tunggu?');">Pembayaran</a>
                <br /><br />

                
                {{-- Isi NIP dan Jabatan Lama di bawah ini, Jika Yang bersangkutan sudah pindah/promosi ke skpd lain dan
                yang
                membayarkan SKPD lama
                <form method="post" action="/admin/rekapitulasi/tambahpegawai">
                    @csrf

                    <input type="text" name="nip" class="form-control-sm" placeholder="nip" required>
                    @if (Auth::user()->skpd->id == 34)
                    <select name="jabatan" class="form-control-sm select2" required>
                        <option value="">-Pilih Kelas | jabatan (Sebelum Pindah)-</option>
                        @foreach ($jabatan as $item)
                        <option value="{{$item['id']}}">{{$item['kelas']}} | {{$item['nama']}}</option>
                        @endforeach
                    </select>
                    @else
                    <select name="jabatan" class="form-control-sm select2" required>
                        <option value="">-Pilih Kelas | jabatan (Sebelum Pindah)-</option>
                        @foreach (jabatan(Auth::user()->skpd->id) as $item)
                        <option value="{{$item->id}}">{{$item->kelas->nama}} | {{$item->nama}}</option>
                        @endforeach
                    </select>
                    @endif


                    <input type="hidden" name="bulan" value="{{$bulan}}" class="form-control-sm" placeholder="bulan">
                    <input type="hidden" name="tahun" value="{{$tahun}}" class="form-control-sm" placeholder="tahun">
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </form> --}}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-bpjs" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="/admin/rekapitulasi/bpjs/" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-gradient-success" style="padding:10px">
                    <h4 class="modal-title text-sm">BPJS</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    Nama<input type="text" id="id_nama" class="form-control" readonly><br />
                    BPJS 1% <input type="text" id="id_1persen" class="form-control" name="satu_persen"
                        onkeypress="return hanyaAngka(event)" required><br />
                    BPJS 4% <input type="text" id="id_4persen" class="form-control" name="empat_persen"
                        onkeypress="return hanyaAngka(event)" required>
                    <input type="hidden" id="id_rekap" name="id_rekap">
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-block btn-success"><i class="fas fa-paper-plane"></i>
                        Simpan</button>
                </div>
            </form>
        </div>
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