<?php

namespace App\Http\Controllers\API;

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
}
