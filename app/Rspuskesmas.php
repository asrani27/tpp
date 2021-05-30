<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rspuskesmas extends Model
{
    protected $table = 'rs_puskesmas';
    protected $guarded = ['id'];
    public $timestamps = false;
}
