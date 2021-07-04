<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JabatanSekolah extends Model
{
    protected $table ='jabatan_sekolah';
    protected $guarded = ['id'];
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
