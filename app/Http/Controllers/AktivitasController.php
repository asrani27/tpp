<?php

namespace App\Http\Controllers;
use App\Skp;

use App\Jabatan;
use App\Aktivitas;
use Carbon\Carbon;
use App\Skp_periode;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AktivitasController extends Controller
{

    public function user()
    {
        return Auth::user();
    }
    public function index()
    {
        if($this->user()->pegawai->jabatan == null)
        {
            toastr()->info('Tidak bisa melakukan aktivitas, Karena Tidak memiliki jabatan,untuk melihat riwayat silahkan ke menu laporan aktivitas');
            return back();
        }
        $person = $this->user()->pegawai;

        //cek atasan apakah PLT atau Bukan
        $check = $this->user()->pegawai->jabatan->atasan == null ? Jabatan::where('sekda',1)->first():$this->user()->pegawai->jabatan->atasan;
        if($check->pegawai == null){
            //Jika Pegawai kosong, Check Lagi Apakah ada PLT atau Tidak
            if($check->pegawaiPlt == null){
                $atasan = $check;
            }else{
                // Cek Lagi Apakah yang memPLT atasan adalah bawahan langsung, menghindari aktifitas menilai diri sendiri
                if($person->id == $check->pegawaiPlt->id){
                    //cek lagi, jika sekretaris memPLT Kadis, maka pejabat penilai adalah SEKDA
                    if($check->atasan == null){
                        $atasan = Jabatan::where('sekda', 1)->first();
                    }else{
                        $atasan = $check->atasan;
                    }
                }else{
                    $atasan = $check;
                }
            }
        }else{
            //Jika Pegawai Ada berarti atasannya adalan jabatan definitif
            $atasan = $check;
        }
        
        //dd($person->id, $atasan->pegawai);
        $data = $this->user()->pegawai->aktivitas()->paginate(10);
        
        return view('pegawai.aktivitas.index',compact('data','atasan', 'person'));
    }
    
    public function add()
    {
        $tahun = Carbon::now()->year;
        if($this->user()->pegawai->skp_periode->count() == 0){
            toastr()->info('Harap isi SKP dulu');
            return back();
        }
        
        if($this->user()->pegawai->skp_periode->where('is_aktif',1)->first() == null){
            toastr()->info('Aktifkan SKP Anda Terlebih dahulu');
            return back();
        }
        
        $skp = $this->user()->pegawai->skp_periode->where('is_aktif',1)->first()->skp;
        
        $data = Aktivitas::where('pegawai_id', $this->user()->pegawai->id)->latest('id')->first();
        
        if($data == null){
            $tanggal   = Carbon::now()->format('Y-m-d');
            $jam_mulai = Carbon::parse('08:01')->format('H:i');
            $jam_selesai = Carbon::parse('09:00')->format('H:i');
        }else{
            $tanggal = $data->tanggal;
            $jam_mulai = Carbon::parse($data->jam_selesai)->addMinute()->format('H:i');
            $jam_selesai = Carbon::parse($data->jam_selesai)->addHour()->format('H:i');
        }
        return view('pegawai.aktivitas.create',compact('skp','tanggal','jam_mulai','jam_selesai'));
    }
    
    public function edit($id)
    {  
        $aktivitas = Aktivitas::find($id);
        if($this->user()->pegawai->id != $aktivitas->pegawai_id){
            toastr()->error('Aktivitas tidak bisa di edit, bukan milik anda','Authorize');
            return back();
        }else{
            $tahun  = Carbon::now()->year;
            $skp    = $this->user()->pegawai->skp_periode->where('is_aktif',1)->first()->skp;
            $data   = $aktivitas;
            return view('pegawai.aktivitas.edit',compact('skp','data'));
        }
    }
    
    public function delete($id)
    {
        $aktivitas =Aktivitas::find($id);
        if($this->user()->pegawai->id != $aktivitas->pegawai_id){
            toastr()->error('Aktivitas tidak bisa di hapus, bukan milik anda','Authorize');
            return back();
        }else{
            $aktivitas->delete();
            toastr()->success('Aktivitas berhasil Di Hapus');
            return back();
        }
        
    }

    public function checkdate($param)
    {
        $day1 = Carbon::today()->subDays(1)->format('Y-m-d');
        $day2 = Carbon::today()->format('Y-m-d');
        
        if($param == $day1){
            return true;
        }elseif($param == $day2){
            return true;
        }else{
            return false;
        }
    }

    public function store(Request $req)
    {
        
        $data = Aktivitas::where('tanggal', $req->tanggal)->where('pegawai_id', $this->user()->pegawai->id)->get()
        ->map(function($item)use($req){
            if($req->jam_mulai.':00' >= $item->jam_mulai && $req->jam_mulai.':00' <= $item->jam_selesai){
                $item->status_jam_mulai = true;
            }else{
                $item->status_jam_mulai = false;
            }
            
            if($req->jam_selesai.':00' >= $item->jam_mulai && $req->jam_selesai.':00' <= $item->jam_selesai){
                $item->status_jam_selesai = true;
            }else{
                $item->status_jam_selesai = false;
            }
            return $item;
        });
        $status_jam_mulai = $data->where('status_jam_mulai', true)->first();
        $status_jam_selesai = $data->where('status_jam_selesai', true)->first();
        
        if($status_jam_mulai != null || $status_jam_selesai != null){
            toastr()->error('Jam ini telah di gunakan');
            $req->flash();
            return back();
        }else{
            $skp = Skp_periode::where('pegawai_id', $this->user()->pegawai->id)->where('is_aktif', 1)->first();
            $skpMulai = $skp->mulai;
            $skpSampai = $skp->sampai;
            $tgl = $req->tanggal;
            if(Carbon::parse($tgl) >= Carbon::parse($skpMulai) && Carbon::parse($tgl) <= Carbon::parse($skpSampai) ){
                
                $attr = $req->all();
                $attr['pegawai_id'] = $this->user()->pegawai->id;
                if(strtotime($req->jam_selesai) > strtotime($req->jam_mulai)){        
                    $menit = (strtotime($req->jam_selesai) - strtotime($req->jam_mulai)) / 60;
                    $attr['menit'] = $menit;
                    Aktivitas::create($attr);
                    toastr()->success('Aktivitas berhasil Di Simpan');
                    return redirect('pegawai/aktivitas/harian');
                }else{
                    toastr()->error('Jam Selesai Tidak Bisa Kurang Dari Jam Mulai');
                    $req->flash();
                    return back();
                }
            }else{
                toastr()->error('Tanggal Berada di luar Periode SKP yang di aktifkan');
                $req->flash();
                return back();
            }   
        } 
    }

    public function update(Request $req, $id)
    {
        $attr = $req->all();
        $attr['pegawai_id'] = Auth::user()->pegawai->id;

        if(strtotime($req->jam_selesai) > strtotime($req->jam_mulai)){        
            $menit = (strtotime($req->jam_selesai) - strtotime($req->jam_mulai)) / 60;
            $attr['menit'] = $menit;
            Aktivitas::find($id)->update($attr);
            toastr()->success('Aktivitas berhasil Di Update');
            return redirect('pegawai/aktivitas/harian');
        }else{
            toastr()->error('Jam Selesai Tidak Bisa Kurang Dari Jam Mulai');
            $req->flash();
            return back();
        }
    }

    public function keberatan()
    {
        
        $data = Aktivitas::where('pegawai_id',$this->user()->pegawai->id)->where('validasi',2)->paginate(10);
        return view('pegawai.aktivitas.keberatan',compact('data'));
    }
}
