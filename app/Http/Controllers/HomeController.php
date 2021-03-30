<?php

namespace App\Http\Controllers;

use App\Skpd;
use App\Jabatan;
use App\Pegawai;
use App\Aktivitas;
use App\Parameter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function superadmin()
    {
        $pegawai = Pegawai::with('jabatan.kelas','pangkat')->get();
        $persentase_tpp = Parameter::first()->persentase_tpp;
        $data    = $pegawai->map(function($item)use($persentase_tpp){
            if($item->jabatan == null){
                $item->nama_jabatan = null;
                $item->nama_kelas = null;
                $item->basic_tpp = 0;
                $item->persentase_tpp = $persentase_tpp;
                $item->tambahan_persen_tpp = 0;
                $item->jumlah_persentase = $persentase_tpp;
                $item->total_pagu = 0;
                $item->persen_disiplin = 100;
                $item->total_disiplin =  0;
                $item->persen_produktivitas = 100;
                $item->total_produktivitas =  0;
                $item->total_tpp =  0;
            }else{
                $item->nama_jabatan = $item->jabatan->nama;
                $item->nama_kelas = $item->jabatan->kelas->nama;
                $item->basic_tpp = $item->jabatan->kelas->nilai;
                $item->persentase_tpp = $persentase_tpp;
                $item->tambahan_persen_tpp = $item->jabatan->tambahan_persen_tpp;
                $item->jumlah_persentase = $persentase_tpp + $item->jabatan->tambahan_persen_tpp;
                $item->total_pagu = $item->basic_tpp * ($persentase_tpp + $item->tambahan_persen_tpp) / 100;
                $item->persen_disiplin = 100;
                $item->total_disiplin =  $item->total_pagu * 40 / 100;
                $item->persen_produktivitas = 100;
                $item->total_produktivitas =  $item->total_pagu * 60 / 100;
                $item->total_tpp =  $item->total_disiplin + $item->total_produktivitas;
            }
            return $item;
        });
        $tpp_pemko = $data->sum('total_tpp');
        $asn = $pegawai->count();
        $skpd = Skpd::get()->count();
        
        return view('superadmin.home',compact('tpp_pemko','asn','skpd'));
    }
    
    public function skpd_id()
    {
        return Auth::user()->skpd->id;
    }

    public function admin()
    {
        $pegawai        = Pegawai::with('jabatan.kelas','pangkat')->where('skpd_id', $this->skpd_id())->orderBy('urutan','ASC')->get();
        $countPegawai   = $pegawai->count();
        $persentase_tpp = (float) Parameter::where('name','persentase_tpp')->first()->value;
        $countJabatan   = DB::table('jabatan')->where('skpd_id',$this->skpd_id())->get()->count();
        
        //return response()->json($pegawai);
        $data = $pegawai->map(function($item)use($persentase_tpp){
            if($item->jabatan == null){
                $item->nama_jabatan = null;
                $item->jenis_jabatan = null;
                $item->nama_kelas = null;
                $item->basic_tpp = 0;
                $item->persentase_tpp = $persentase_tpp;
                $item->tambahan_persen_tpp = 0;
                $item->jumlah_persentase = $persentase_tpp;
                $item->total_pagu = 0;
                $item->persen_disiplin = 100;
                $item->total_disiplin =  0;
                $item->persen_produktivitas = 100;
                $item->total_produktivitas =  0;
                $item->total_tpp =  0;
            }else{
                $item->nama_jabatan = $item->jabatan->nama;
                $item->jenis_jabatan = $item->jabatan->jenis_jabatan;
                $item->nama_kelas = $item->jabatan->kelas->nama;
                $item->basic_tpp = $item->jabatan->kelas->nilai;
                $item->persentase_tpp = $persentase_tpp;
                $item->tambahan_persen_tpp = $item->jabatan->tambahan_persen_tpp;
                $item->jumlah_persentase = $persentase_tpp + $item->jabatan->tambahan_persen_tpp;
                $item->total_pagu = $item->basic_tpp * ($persentase_tpp + $item->tambahan_persen_tpp) / 100;
                $item->persen_disiplin = 100;
                $item->total_disiplin =  $item->total_pagu * 40 / 100;
                $item->persen_produktivitas = 100;
                $item->total_produktivitas =  $item->total_pagu * 60 / 100;
                $item->total_tpp =  $item->total_disiplin + $item->total_produktivitas;
            }
            return $item;
        });
        
        return view('admin.home',compact('data','persentase_tpp','countPegawai','countJabatan'));
    }
    
    public function adminUp($id, $urutan)
    {
        //Pegawai Yang Di Down
        Pegawai::where('skpd_id', $this->skpd_id())->where('urutan',$urutan-1)->first()->update([
            'urutan' => $urutan
        ]);

        //Pegawai Yang Di Up
        Pegawai::find($id)->update([
            'urutan' => $urutan - 1
        ]);

        return redirect('/home/admin');
    }
    
    public function adminDown($id, $urutan)
    {
        //Pegawai Yang Di Up
        Pegawai::where('skpd_id', $this->skpd_id())->where('urutan',$urutan+1)->first()->update([
            'urutan' => $urutan
        ]);

        //Pegawai Yang Di Down
        Pegawai::find($id)->update([
            'urutan' => $urutan + 1
        ]);

        return redirect('/home/admin');
    }

    public function pegawai()
    {
        $pegawai = Pegawai::where('user_id',Auth::user()->id)->get();
        $persentase_tpp = (float) Parameter::where('name','persentase_tpp')->first()->value;
        
        $month = Carbon::now()->month;
        $year  = Carbon::now()->year;

        $aktivitas = Aktivitas::where('pegawai_id', $pegawai->first()->id)->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->get();
        $jmlmenit = $aktivitas->where('validasi',1)->sum('menit');
        
        $acc     = $aktivitas->where('validasi',1)->count();
        $tolak   = $aktivitas->where('validasi',2)->count();
        $pending = $aktivitas->where('validasi',0)->count();

        $data = $pegawai->map(function($item)use($persentase_tpp, $jmlmenit){
            
            if($item->jabatan == null){
                $item->nama_jabatan = null;
                $item->nama_kelas = null;
                $item->basic_tpp = 0;
                $item->persentase_tpp = $persentase_tpp;
                $item->tambahan_persen_tpp = 0;
                $item->jumlah_persentase = $persentase_tpp;
                $item->total_pagu = 0;
                $item->persen_disiplin = 100;
                $item->total_disiplin =  0;
                $item->persen_produktivitas = 100;
                $item->total_produktivitas =  0;
                $item->total_tpp =  0;
                $item->pph21 =  0;
                $item->bpjs =  0;
            }else{
                $item->nama_jabatan = $item->jabatan->nama;
                $item->nama_kelas = $item->jabatan->kelas->nama;
                $item->basic_tpp = $item->jabatan->kelas->nilai;
                $item->persentase_tpp = $persentase_tpp;
                $item->tambahan_persen_tpp = $item->jabatan->tambahan_persen_tpp;
                $item->jumlah_persentase = $persentase_tpp + $item->jabatan->tambahan_persen_tpp;
                $item->total_pagu = $item->basic_tpp * ($persentase_tpp + $item->tambahan_persen_tpp) / 100;
                $item->persen_disiplin = 100;
                $item->total_disiplin =  $item->total_pagu * 40 / 100;
                $item->persen_produktivitas = 100;
                if($jmlmenit >= 6750){
                    $item->total_produktivitas =  $item->total_pagu * 60 / 100;
                }else{
                    $item->total_produktivitas =  0;
                }
                $item->total_tpp =  $item->total_disiplin + $item->total_produktivitas;
                $item->pph21 =  $item->total_tpp * 15 /100;
                $item->bpjs =  0;
            }
            return $item;
        })->first();
        
        return view('pegawai.home',compact('data','acc','tolak','pending','jmlmenit'));    
    }

    public function walikota()
    {
        return view('walikota.home');
    }
}
