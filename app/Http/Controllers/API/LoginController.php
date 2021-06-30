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
        if($req->username == null || $req->password == null){
            $data['message_error'] = 101;
            $data['message']       = 'username atau password kosong';
            $data['data']          = null;
            return response()->json($data);
        }else{
            if (Auth::attempt(['username' => $req->username, 'password' => $req->password])) {
                Auth::user()->update([
                    'api_token' => Hash::make(Str::random(20))
                ]);

                return response()->json(Auth::user());
                // if (Auth::user()->hasRole('superadmin')) {
                //     return response()->json('superadmin');
    
                // } elseif(Auth::user()->hasRole('admin')) {
                //     return response()->json('admin');
    
                // } elseif(Auth::user()->hasRole('pegawai')) {
                //     return response()->json('pegawai');
    
                // } elseif(Auth::user()->hasRole('walikota')) {
                //     return response()->json('walikota');
                // }
            } else {
                $data['message_error'] = 201;
                $data['message']       = 'username atau password anda tidak ditemukan';
                $data['data']          = null;
                return response()->json($data);
            }
        } 
    }
}
