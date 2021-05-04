<?php

namespace App\Http\Controllers;

use App\Pegawai;
use App\Aktivitas;
use App\Parameter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\View_aktivitas_pegawai;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;

class RekapitulasiController extends Controller
{
    public function index()
    {
        $tampil = false;
        return view('admin.rekapitulasi.index',compact('tampil'));
    }

    public function skpd_id()
    {
        return Auth::user()->skpd->id;
    }

    public function cetaktpp()
    {
        $month = request()->get('bulan');
        $year = request()->get('tahun');
        $button= request()->get('button');
        $bulantahun = Carbon::createFromFormat('m/Y', $month.'/'.$year)->isoformat('MMMM Y');
        
            //tampilkan
        $pegawai        = Pegawai::with('jabatan.kelas','pangkat')->where('skpd_id', $this->skpd_id())->orderBy('urutan','ASC')->get();
        $countPegawai   = $pegawai->count();
        $persentase_tpp = (float) Parameter::where('name','persentase_tpp')->first()->value;
        
        // $month = Carbon::now()->month;
        // $year  = Carbon::now()->year;
        $view_aktivitas = View_aktivitas_pegawai::where('tahun', $year)->where('bulan', $month)->get();
        if(count($view_aktivitas) == 0){
            $tpp = false;
        }else{
            $tpp = true;
        }
        $capaianMenit = Parameter::where('name','menit')->first()->value;
        $data = $pegawai->map(function($item)use($view_aktivitas, $capaianMenit){
            if($item->jabatan == null){
                $item->nama_jabatan   = null;
                $item->jenis_jabatan  = null;
                $item->nama_kelas     = null;
                $item->nama_pangkat   = null;
                $item->basic_tpp      = 0;
                $item->persentase_tpp = 0;
                $item->tambahan_persen_tpp  =  0;
                $item->jumlah_persentase    =  $item->persentase_tpp + $item->tambahan_persen_tpp;
                $item->total_pagu           =  0;
                $item->persen_disiplin      =  0;
                $item->total_disiplin       =  0;
                $item->persen_produktivitas =  0;
                $item->total_produktivitas  =  0;
                $item->total_tpp            =  0;
                $item->pph                  =  0;
                $item->pph_angka            =  0;
                $item->hukuman              =  0;
                $item->hukuman_angka        =  0;
                $item->tpp_diterima         =  0;
            }else{
                $item->nama_jabatan     = $item->jabatan->nama;
                $item->jenis_jabatan    = $item->jabatan->jenis_jabatan;
                $item->nama_pangkat     = $item->pangkat == null ? null:$item->pangkat->nama.' ('.$item->pangkat->golongan.')';
                $item->nama_kelas       = $item->jabatan->kelas->nama;
                $item->basic_tpp        = $item->jabatan->kelas->nilai;
                $item->persentase_tpp   = $item->jabatan->persentase_tpp == null ? 0:$item->jabatan->persentase_tpp;
                $item->tambahan_persen_tpp  = $item->jabatan->tambahan_persen_tpp;
                $item->jumlah_persentase    = $item->persentase_tpp + $item->jabatan->tambahan_persen_tpp;
                $item->total_pagu           = ceil($item->basic_tpp * ($item->persentase_tpp + $item->tambahan_persen_tpp) / 100);
                $item->persen_disiplin      = $item->presensiMonth->first() == null ? 0:$item->presensiMonth->first()->persen;
                $item->total_disiplin       =  $item->total_pagu * ((40 / 100) * $item->persen_disiplin / 100);
                $item->persen_produktivitas = $view_aktivitas->where('pegawai_id', $item->id)->first() == null ? 0 : (int) $view_aktivitas->where('pegawai_id', $item->id)->first()->jumlah_menit;
                if($item->persen_produktivitas < $capaianMenit)
                {
                    $item->total_produktivitas =  0;
                }else
                {
                    $item->total_produktivitas =  $item->total_pagu * 60 / 100;
                }
                $item->total_tpp =  $item->total_disiplin + $item->total_produktivitas;
                
                if($item->pangkat == null){
                    $item->pph   = 0;
                    $item->pph_angka =  0;
                }else{
                    $item->pph   = $item->pangkat->pph;
                    $item->pph_angka =  $item->total_tpp * $item->pph /100;
                }
                
                $item->hukuman              =  $item->presensiMonth->first() == null ? 0:$item->presensiMonth->first()->hukuman;
                $item->hukuman_angka        =  $item->hukuman * $item->total_tpp / 100;
                $item->tpp_diterima         =  $item->total_tpp - $item->pph_angka - $item->hukuman_angka;
            }
            return $item;
        });
        
        $tampil = true;
        if($button == 1){
            request()->flash();
            return view('admin.rekapitulasi.index',compact('data','persentase_tpp','month','year','capaianMenit','tampil','bulantahun','tpp'));
        }else{
            
            $pdf = PDF::loadView('admin.rekapitulasi.cetak',compact('data','persentase_tpp','month','year','capaianMenit','tampil','bulantahun','tpp'))->setPaper('legal','landscape');
            return $pdf->stream();
        }
    }
}
