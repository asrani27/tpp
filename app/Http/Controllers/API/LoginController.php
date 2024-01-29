<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $req)
    {
        if ($req->username == null || $req->password == null) {
            $data['message_error'] = 101;
            $data['message']       = 'username atau password kosong';
            $data['data']          = null;
            return response()->json($data);
        } else {
            if (Auth::attempt(['username' => $req->username, 'password' => $req->password])) {
                Auth::user()->update([
                    'api_token' => Hash::make(Str::random(100))
                ]);

                return response()->json(Auth::user());
            } else {
                $data['message_error'] = 201;
                $data['message']       = 'username atau password anda tidak ditemukan';
                $data['data']          = null;
                return response()->json($data);
            }
        }
    }
    public function login_m(Request $req)
    {
        if ($req->username == null || $req->password == null) {
            $data['message']       = 'username atau password kosong';
            return response()->json($data);
        } else {
            if (Auth::attempt(['username' => $req->username, 'password' => $req->password])) {
                $pegawai = Auth::user()->pegawai;

                $data['nip'] = $pegawai->nip;
                $data['nama'] = $pegawai->nama;
                $data['jabatan'] = $pegawai->jabatan == null ? null : $pegawai->jabatan->nama;
                $data['skpd'] = $pegawai->skpd == null ? null : $pegawai->skpd->nama;
                return response()->json($data);
            } else {
                $data['message']       = 'username atau password anda tidak ditemukan';
                return response()->json($data);
            }
        }
    }
}
