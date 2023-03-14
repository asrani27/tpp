<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RekapReguler extends Model
{
    protected $table = 'rekap_reguler';
    protected $guarded = ['id'];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }
}
