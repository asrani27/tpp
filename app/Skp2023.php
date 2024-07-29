<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skp2023 extends Model
{
    protected $table = 'skp2023';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
    public function jpt()
    {
        return $this->hasMany(Skp2023Jpt::class, 'skp2023_id');
    }
    public function rencana_aksi()
    {
        return $this->hasMany(RencanaAksi::class, 'skp2023_id');
    }


    public function ekspektasi1()
    {
        return $this->hasMany(Skp2023Ekspektasi::class, 'skp2023_id')->where('pkid', 1)->where('jenis', null);
    }

    public function ekspektasi2()
    {
        return $this->hasMany(Skp2023Ekspektasi::class, 'skp2023_id')->where('pkid', 2)->where('jenis', null);
    }

    public function ekspektasi3()
    {
        return $this->hasMany(Skp2023Ekspektasi::class, 'skp2023_id')->where('pkid', 3)->where('jenis', null);
    }

    public function ekspektasi4()
    {
        return $this->hasMany(Skp2023Ekspektasi::class, 'skp2023_id')->where('pkid', 4)->where('jenis', null);
    }

    public function ekspektasi5()
    {
        return $this->hasMany(Skp2023Ekspektasi::class, 'skp2023_id')->where('pkid', 5)->where('jenis', null);
    }

    public function ekspektasi6()
    {
        return $this->hasMany(Skp2023Ekspektasi::class, 'skp2023_id')->where('pkid', 6)->where('jenis', null);
    }

    public function ekspektasi7()
    {
        return $this->hasMany(Skp2023Ekspektasi::class, 'skp2023_id')->where('pkid', 7)->where('jenis', null);
    }
}
