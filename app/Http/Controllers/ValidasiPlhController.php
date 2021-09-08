<?php

namespace App\Http\Controllers;

use App\Jabatan;
use App\Aktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidasiPlhController extends Controller
{
    public function user()
    {
        return Auth::user();
    }
    public function index()
    {
        if($this->user()->pegawai->jabatan == null){
            toastr()->info('Tidak bisa melakukan validasi karena anda tidak memiliki jabatan, hub admin SKPD');
            return back();
        }

        $data1 = $this->user()->pegawai->jabatanPlh->bawahan->load('pegawai')->map(function($item){
            if($item->pegawai == null){
                if($item->pegawaiplt == null){
                    $item->pegawai_id     = null;
                    $item->nama_pegawai = null;
                    $item->aktivitas_baru = 0;
                }else{
                    $item->pegawai_id     = $item->pegawaiplt->id;
                    $item->nama_pegawai   = $item->pegawaiplt->nama;
                    $item->aktivitas_baru = $item->pegawaiplt->aktivitas->where('validasi', 0)->count();
                }
            }else{
                $item->pegawai_id     = $item->pegawai->id;
                $item->nama_pegawai   = $item->pegawai->nama;
                $item->aktivitas_baru = $item->pegawai->aktivitas->where('validasi', 0)->count();
            }
            return $item;
        });
        
        if($this->user()->pegawai->jabatanPlh->sekda == 1){
            
            $data2 = Jabatan::where('jabatan_id', null)->where('sekda', null)->get()->map(function($item){
                if($item->pegawai == null){
                    if($item->pegawaiplt == null){
                        $item->nama = $item->nama.', SKPD : '. $item->skpd->nama;
                        $item->pegawai_id     = '-';
                        $item->nama_pegawai   = '-';
                        $item->aktivitas_baru = 0;
                    }else{
                        $item->nama = 'Plt. '.$item->nama.', SKPD : '. $item->skpd->nama;
                        $item->pegawai_id     = $item->pegawaiplt->id;
                        $item->nama_pegawai   = $item->pegawaiplt->nama;
                        $item->aktivitas_baru = $item->pegawaiplt->aktivitas->where('validasi', 0)->count();
                    }
                }else{
                    $item->nama = $item->nama.', SKPD : '. $item->skpd->nama;
                    $item->pegawai_id     = $item->pegawai->id;
                    $item->nama_pegawai   = $item->pegawai->nama;
                    $item->aktivitas_baru = $item->pegawai->aktivitas->where('validasi', 0)->count();
                }
                return $item;
            });
        }else{
            $data2 = collect([]);
        }

        $data = $data1->merge($data2)->whereNotIn('pegawai_id', $this->user()->pegawai->id);
        
        return view('pegawai.validasiplh.index',compact('data'));
    } 
    
    public function view($id)
    {
        $check = Jabatan::find($id);
        if($check->pegawai == null){
            $data    = $check->pegawaiplh->aktivitas()->where('validasi',0)->paginate(10);
            $pegawai = $check->pegawaiplh;
        }else{
            $data    = $check->pegawai->aktivitas()->where('validasi',0)->paginate(10);
            $pegawai = $check->pegawai;
        }
        return view('pegawai.validasiplh.detail',compact('data','pegawai','id'));
    }

    public function accAktivitas($id)
    {
        Aktivitas::findOrFail($id)->update([
            'validasi' => 1,
            'validator' => Auth::user()->pegawai->id,
        ]);
        toastr()->success('Aktivitas Di Setujui');
        return back();
    }

    public function tolakAktivitas($id)
    {
        Aktivitas::findOrFail($id)->update([
            'validasi' => 2,
            'validator' => Auth::user()->pegawai->id,
        ]);
        toastr()->success('Aktivitas Di Tolak');
        return back();
    }

    public function accSemua($id)
    {
        $jabatan_saya = $this->user()->pegawai->jabatanPlh;
        
        $jabatan = Jabatan::with('pegawai.aktivitas')->findOrFail($id);
        if($jabatan->atasan == null){
            $data = $jabatan->pegawai->aktivitas->where('validasi', 0);
            
            $data->map(function($item){
                $item->update([
                    'validasi' => 1,
                    'validator' => Auth::user()->pegawai->id,
                ]);
                return $item;
            });
            toastr()->success('Semua Aktivitas Di Setujui');
            return back();
        }else{
            if($jabatan_saya->id != $jabatan->atasan->id){
                toastr()->error('Tidak Bisa Validasi , bukan bawahan anda','Authorize');
                return back();
            }else{
                $data = $jabatan->pegawai->aktivitas->where('validasi', 0);
                
                $data->map(function($item){
                    $item->update([
                        'validasi' => 1,
                        'validator' => Auth::user()->pegawai->id,
                    ]);
                    return $item;
                });
                toastr()->success('Semua Aktivitas Di Setujui');
                return back();
            }
        }
    }
}
