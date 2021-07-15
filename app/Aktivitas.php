<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aktivitas extends Model
{
    protected $table ='aktivitas';
    protected $guarded = ['id'];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function penilai()
    {
        return $this->belongsTo(Pegawai::class, 'validator');
    }
}
