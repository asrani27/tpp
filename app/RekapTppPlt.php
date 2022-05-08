<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RekapTppPlt extends Model
{
    protected $table = 'rekap_tpp_plt';
    protected $guarded = ['id'];

    public function plt()
    {
        return $this->hasOne(RekapTppPlt::class, 'rekap_tpp_plt_id');
    }

    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'skpd_id');
    }

    public function puskesmas()
    {
        return $this->belongsTo(Rspuskesmas::class, 'puskesmas_id');
    }

    public function pangkat()
    {
        return $this->belongsTo(Pangkat::class, 'pangkat_id');
    }

    public function potonganPPH21()
    {
        return $this->belongsTo(Pangkat::class, 'pangkat_id');
    }

    public function persenjabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }
}
