<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lock extends Model
{
    protected $table = 'kunci';
    protected $guarded = ['id'];
}
