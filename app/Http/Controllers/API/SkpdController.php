<?php

namespace App\Http\Controllers\API;

use App\Skpd;
use App\Jabatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SkpdController extends Controller
{
    public function all()
    {
        $skpd = Skpd::get()->map(function ($item) {
            $item->pimpinan = Jabatan::where('skpd_id', $item->id)->where('jabatan_id', null)->first();
            return $item;
        });
        $data['message_error'] = 200;
        $data['message']       = 'data ditemukan';
        $data['data']          = $skpd;
        $data['jumlah']        = count($skpd);
        return response()->json($data);
    }
}
