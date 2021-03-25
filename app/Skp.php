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
}
