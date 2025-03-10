<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhitelistNip extends Model
{
    protected $table = 'whitelist_nip';
    protected $guarded = ['id'];

    public $timestamps = false;
}
