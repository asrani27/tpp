<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login()
    {
        return redirect('/home');
    }
    
    public function logout()
    {
        return redirect('/');
    }
}
