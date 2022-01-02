<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pangkat extends Model
{
    protected $table = 'pangkat';
    protected $guarded = ['id'];
    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'pangkat_id');
    }
}
