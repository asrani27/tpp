<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatan';
    protected $guarded = ['id'];

    public function atasan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function bawahan()
    {
        return $this->hasMany(Jabatan::class, 'jabatan_id');
    }

    public function bawahanblud($param)
    {
        return $this->hasMany(Jabatan::class, 'jabatan_id')->where('rs_puskesmas_id', $param)->get();
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'jabatan_id');
    }

    public function pegawaiplt()
    {
        return $this->hasOne(Pegawai::class, 'jabatan_plt');
    }

    public function pegawaiplh()
    {
        return $this->hasOne(Pegawai::class, 'jabatan_plh');
    }

    public function skp()
    {
        return $this->hasMany(Skp::class, 'jabatan_id')->orderBy('id', 'DESC');
    }

    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'skpd_id');
    }

    public function rs()
    {
        return $this->belongsTo(Rspuskesmas::class, 'rs_puskesmas_id');
    }

    public function puskesmas()
    {
        return $this->belongsTo(Rspuskesmas::class, 'rs_puskesmas_id');
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }
    public function kadis()
    {
        return $this->hasMany(Jabatan::class, 'skpd_id');
    }
}
