<?php

namespace App\Http\Controllers\API;

use App\Skpd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SkpdController extends Controller
{
    public function all()
    {
        $skpd = Skpd::get();
        $data['message_error'] = 200;
        $data['message']       = 'data ditemukan';
        $data['data']          = $skpd;
        $data['jumlah']        = count($skpd);
        return response()->json($data);
    }
}
