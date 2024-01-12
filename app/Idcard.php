<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Idcard extends Model
{
    use HasFactory;
    protected $table = 'idcard';
    protected $guarded = ['id'];
}
