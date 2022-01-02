<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eselon extends Model
{
    protected $table = 'eselon';
    protected $guarded = ['id'];

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'eselon_id');
    }
}
