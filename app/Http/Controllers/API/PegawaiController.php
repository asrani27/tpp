<?php

namespace App\Http\Controllers\API;

use App\Skpd;
use App\Pegawai;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PegawaiController extends Controller
{
    public function pegawai($nip)
    {
        $pegawai = Pegawai::where('nip', $nip)->first();
        if($pegawai == null){
            $data['message_error'] = 101;
            $data['message']       = 'username atau password kosong';
            $data['data']          = null;
            return response()->json($data);
        }else{
            $data['message_error'] = 200;
            $data['message']       = 'data ditemukan';
            $data['data']          = $pegawai;
            return response()->json($data);
        }
    }

    public function pegawaiSkpd($id)
    {
        $skpd_id = Skpd::where('kode_skpd', $id)->first()->id;
        $pegawai = Pegawai::where('skpd_id', $skpd_id)->get();
        $data['message_error'] = 200;
        $data['message']       = 'data ditemukan';
        $data['data']          = $pegawai;
        return response()->json($data);
    }
}
