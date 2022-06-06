<?php

namespace App\Http\Controllers;

use App\Skpd;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->hasRole('superadmin')) {
                return redirect('/home/superadmin');
            } elseif (Auth::user()->hasRole('admin')) {
                return redirect('/home/admin');
            } elseif (Auth::user()->hasRole('pegawai')) {
                return redirect('/home/pegawai');
            } elseif (Auth::user()->hasRole('puskesmas')) {
                return redirect('/home/puskesmas');
            } elseif (Auth::user()->hasRole('walikota')) {
                return redirect('/home/walikota');
            }
        }

        $a1 = rand(10, 50);
        $a2 = rand(10, 50);
        return view('login', compact('a1', 'a2'));
    }

    public function login(Request $req)
    {
        // $validator = Validator::make($req->all(), [
        //     'g-recaptcha-response' => 'required|captcha',
        // ]);

        // if ($validator->fails()) {
        //     toastr()->error('Checklist Capcha');
        //     return back();
        // }

        if (Auth::attempt(['username' => $req->username, 'password' => $req->password])) {
            Session::forget('superadmin');
            if (Auth::user()->hasRole('superadmin')) {
                return redirect('/home/superadmin');
            } elseif (Auth::user()->hasRole('admin')) {
                return redirect('/home/admin');
            } elseif (Auth::user()->hasRole('pegawai')) {
                return redirect('/home/pegawai');
            } elseif (Auth::user()->hasRole('puskesmas')) {
                return redirect('/home/puskesmas');
            } elseif (Auth::user()->hasRole('walikota')) {
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
        Auth::logout();
        return redirect('/');
    }

    public function redirectLogin()
    {
        return redirect('/');
    }
}
