<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RekapReguler extends Model
{
    protected $table = 'rekap_reguler';
    protected $guarded = ['id'];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'skpd_id');
    }
}
