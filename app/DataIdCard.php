<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataIdCard extends Model
{
    use HasFactory;
    protected $table = 'idcard';
    protected $guarded = ['id'];
}
