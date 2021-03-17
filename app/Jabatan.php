<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table ='jabatan';
    protected $guarded = ['id'];

    public function atasan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function bawahan()
    {
        return $this->hasMany(Jabatan::class, 'jabatan_id');
    }
}
