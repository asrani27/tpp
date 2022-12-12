<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skp2023JfIndikator extends Model
{
    protected $table = 'skp2023_jf_indikator';
    protected $guarded = ['id'];

    public function jf()
    {
        return $this->belongsTo(Skp2023Jf::class, 'skp2023_jf_id');
    }
}
