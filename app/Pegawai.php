<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table ='pegawai';
    protected $guarded = ['id'];

    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'skpd_id');
    }
}
