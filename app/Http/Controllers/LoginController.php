<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $req)
    {
        if (Auth::attempt(['username' => $req->username, 'password' => $req->password])) {
            if (Auth::user()->hasRole('superadmin')) {
                return redirect('/home/superadmin');

            } elseif(Auth::user()->hasRole('admin')) {
                return redirect('/home/admin');

            } elseif(Auth::user()->hasRole('pegawai')) {
                return redirect('/home/pegawai');

            } elseif(Auth::user()->hasRole('walikota')) {
                return redirect('/home/walikota');
            }
        } else {
            toastr()->error('Username / Password Tidak Ditemukan');
            $req->flash();
            return back();
        }
    }
    
    public function logout()
    {
        return redirect('/');
    }
}
