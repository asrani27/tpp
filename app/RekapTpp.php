<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RekapTpp extends Model
{
    protected $table = 'rekap_tpp';
    protected $guarded = ['id'];

    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'skpd_id');
    }
}
