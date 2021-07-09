<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatPLT extends Model
{
    protected $table = 'riwayat_plt';
    protected $guarded = ['id'];

    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'skpd_id');
    }
}
