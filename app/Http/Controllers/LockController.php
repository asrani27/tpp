<?php

namespace App\Http\Controllers;

use App\Lock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LockController extends Controller
{
    public function kadis()
    {
        return view('pegawai.kunci');
    }

    public function kadisLock($bulan, $tahun)
    {
        $check = Lock::where('skpd_id', Auth::user()->pegawai->skpd_id)->where('bulan', $bulan)->where('tahun', $tahun)->first();
        if ($check == null) {
            $n = new Lock;
            $n->skpd_id = Auth::user()->pegawai->skpd_id;
            $n->bulan = $bulan;
            $n->tahun = $tahun;
            $n->oleh = 'kadis';
            $n->lock = 1;
            $n->save();
            toastr()->success('Berhasil Di kunci');
        } else {
            $check->update([
                'lock' => 1,
                'oleh' => 'kadis',
            ]);
            toastr()->success('Berhasil Di kunci');
        }
        return back();
    }

    public function kadisUnlock($bulan, $tahun)
    {
        $check = Lock::where('skpd_id', Auth::user()->pegawai->skpd_id)->where('bulan', $bulan)->where('tahun', $tahun)->first();
        if ($check->oleh == 'kadis') {
            $check->update([
                'lock' => null,
                'oleh' => null,
            ]);
            toastr()->success('Berhasil Di Buka');
        } else {
            toastr()->error('Di Kunci Oleh BKD, dan hanya BKD yang bisa membuka');
        }
        return back();
    }
}
