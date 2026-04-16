<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lampiran extends Model
{
    protected $table = 'lampiran';
    protected $guarded = ['id'];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
