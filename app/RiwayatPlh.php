<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatPlh extends Model
{
    protected $table = 'riwayat_plh';
    protected $guarded = ['id'];

    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'skpd_id');
    }
}
