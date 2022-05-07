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
        // $bulan = '02';
        // $tahun = '2022';
        // $bulanTahun = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun)->translatedFormat('F');
        // dd($bulanTahun);
        return view('login');
    }

    public function login(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'g-recaptcha-response' => 'required|captcha',
        ]);

        if ($validator->fails()) {
            toastr()->error('Checklist Capcha');
            return back();
        }

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
