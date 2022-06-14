<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class G_puskesmas extends Model
{
    protected $table = 'gp';
    protected $guarded = ['id'];

    public $timestamps = false;
}
