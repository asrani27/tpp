<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table ='jabatan';
    protected $guarded = ['id'];

    public function atasan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'jabatan_id');
    }

    public function bawahan()
    {
        return $this->hasMany(Jabatan::class, 'jabatan_id');
    }
    
    public function skp()
    {
        return $this->hasMany(Skp::class, 'jabatan_id')->orderBy('id','DESC');
    }
}
