<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    protected $table = 'cuti';
    protected $guarded = ['id'];

    public function jenisketerangan()
    {
        return $this->belongsTo(JenisKeterangan::class, 'jenis_keterangan_id');
    }
}
