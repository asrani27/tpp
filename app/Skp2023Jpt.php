<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skp2023Jpt extends Model
{
    protected $table = 'skp2023_jpt';
    protected $guarded = ['id'];

    public function skp()
    {
        return $this->belongsTo(Skp2023::class, 'skp2023_id');
    }
    
    public function indikator()
    {
        return $this->hasMany(Skp2023JptIndikator::class, 'skp2023_jpt_id');
    }
}
