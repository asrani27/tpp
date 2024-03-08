<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiKeluar extends Model
{
    use HasFactory;
    protected $table = 'riwayat_mutasikeluar';
    protected $guarded = ['id'];
}
