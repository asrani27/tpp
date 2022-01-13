<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BulanTahun extends Model
{
    protected $table = 'bulan_tahun';
    protected $guarded = ['id'];

    public $timestamp = false;
}
