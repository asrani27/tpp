<?php

use App\Skpd;
use App\Kelas;
use App\Eselon;
use App\Jabatan;
use App\Pangkat;
use App\Pegawai;
use App\Parameter;

function terbilang($angka) {
    $angka=abs($angka);
    $baca =array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
  
    $terbilang="";
     if ($angka < 12){
         $terbilang= " " . $baca[$angka];
     }
     else if ($angka < 20){
         $terbilang= terbilang($angka - 10) . " belas";
     }
     else if ($angka < 100){
         $terbilang= terbilang($angka / 10) . " puluh" . terbilang($angka % 10);
     }
     else if ($angka < 200){
         $terbilang= " seratus" . terbilang($angka - 100);
     }
     else if ($angka < 1000){
         $terbilang= terbilang($angka / 100) . " ratus" . terbilang($angka % 100);
     }
     else if ($angka < 2000){
         $terbilang= " seribu" . terbilang($angka - 1000);
     }
     else if ($angka < 1000000){
         $terbilang= terbilang($angka / 1000) . " ribu" . terbilang($angka % 1000);
     }
     else if ($angka < 1000000000){
        $terbilang= terbilang($angka / 1000000) . " juta" . terbilang($angka % 1000000);
     }
        return $terbilang;
 }

 function skpd()
 {
     return Skpd::get();
 }

 function countSkpd()
 {
     return Skpd::get()->count();
 }

 function countPegawai()
 {
     return Pegawai::get()->count();
 }
 
 function countPegawaiSkpd($id)
 {
     return Pegawai::where('skpd_id', $id)->get()->count();
 }

 function countJabatanSkpd($id)
 {
    return Jabatan::where('skpd_id', $id)->get()->count();
 }

 function grafikSkpd()
 {
     return Skpd::get()->pluck('kode_skpd');
 }

 function pangkat()
 {
     return Pangkat::get();
 }

 function eselon()
 {
     return Eselon::get();
 }
 
 function kelas()
 {
     return Kelas::orderBy('id','DESC')->get();
 }
 function pegawai()
 {      
     return Pegawai::orderBy('nama','ASC')->paginate(10);
 }

 function detailSkpd($id)
 {
     return Skpd::find($id);
 }

 function jabatan($skpd_id)
 {
    return Jabatan::where('skpd_id', $skpd_id)->where('sekolah_id', null)->get();
 }

 function pegawaiSkpd($id)
 {
     return Pegawai::where('skpd_id', $id)->paginate(10);
 }

 function parameter()
 {
     return Parameter::get();
 }

 function currency($value)
 {
    $hasil=number_format($value,0,',','.');
    return $hasil;
 }
 
 function persentase_tpp()
 {
     return Parameter::first()->persentase_tpp;
 }