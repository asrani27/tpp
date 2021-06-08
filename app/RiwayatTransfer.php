<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatTransfer extends Model
{
    protected $table = 'riwayat_transfer';
    protected $guarded = ['id'];

    public function skpd_lama()
    {
        return $this->belongsTo(Skpd::class, 'skpd_asal');
    }
    
    public function skpd_new()
    {
        return $this->belongsTo(Skpd::class, 'skpd_baru');
    }
}
