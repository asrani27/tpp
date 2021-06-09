<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table ='pegawai';
    protected $guarded = ['id'];

    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'skpd_id');
    }
    
    public function aktivitas()
    {
        return $this->hasMany(Aktivitas::class, 'pegawai_id')->orderBy('id','DESC');
    }

    public function skp_periode()
    {
        return $this->hasMany(Skp_periode::class, 'pegawai_id')->orderBy('id','DESC');
    }

    public function aktivitasToday()
    {
        return $this->hasMany(Aktivitas::class, 'pegawai_id')->where('tanggal','=',Carbon::today()->format('Y-m-d'))->orderBy('id','DESC');
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'pegawai_id');
    }

    public function presensiMonth()
    {
        return $this->hasMany(Presensi::class, 'pegawai_id')->where('bulan', Carbon::today()->format('m'))->where('tahun', Carbon::today()->format('Y'));
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }
    
    public function jabatanPlt()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_plt');
    }

    public function plt()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_plt');
    }

    public function pangkat()
    {
        return $this->belongsTo(Pangkat::class, 'pangkat_id');
    }
    
    public function eselon()
    {
        return $this->belongsTo(Eselon::class, 'eselon_id');
    }
}
