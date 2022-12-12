<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skp2023JptIndikator extends Model
{
    protected $table = 'skp2023_jpt_indikator';
    protected $guarded = ['id'];

    public function jpt()
    {
        return $this->belongsTo(Skp2023Jpt::class, 'skp2023_jpt_id');
    }
}
