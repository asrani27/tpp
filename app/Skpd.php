<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skpd extends Model
{
    protected $table ='skpd';
    protected $guarded = ['id'];

    public function jabatan()
    {
        return $this->hasMany(Jabatan::class, 'skpd_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
