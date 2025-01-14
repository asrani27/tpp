<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KunciTpp extends Model
{
    use HasFactory;
    protected $table = 'kunci_tpp';
    protected $guarded = ['id'];

    public $timestamps = false;
}
