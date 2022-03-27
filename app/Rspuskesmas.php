<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rspuskesmas extends Model
{
    protected $table = 'rs_puskesmas';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function jabatan()
    {
        return $this->hasMany(Jabatan::class, 'rs_puskesmas_id');
    }
}
