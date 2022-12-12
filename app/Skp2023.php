<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skp2023 extends Model
{
    protected $table = 'skp2023';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function jpt()
    {
        return $this->hasMany(Skp2023Jpt::class, 'skp2023_id');
    }
}
