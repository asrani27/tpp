<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skp_periode extends Model
{
    protected $table = 'skp_periode';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function skp()
    {
        return $this->hasMany(Skp::class, 'skp_periode_id');
    }
}
