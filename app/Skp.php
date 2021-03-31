<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skp extends Model
{
    protected $table = 'skp';
    protected $guarded = ['id'];

    public function aktivitas()
    {
        return $this->hasMany(Aktivitas::class,'skp_id');
    }

    public function skp_periode()
    {
        return $this->belongsTo(Skp_periode::class, 'skp_periode_id');
    }
}
