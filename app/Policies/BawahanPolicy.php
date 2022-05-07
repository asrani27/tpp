<?php

namespace App\Policies;

use App\User;
use App\Pegawai;
use Illuminate\Auth\Access\HandlesAuthorization;

class BawahanPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function isBawahanSaya(User $user, Pegawai $bawahan)
    {
        return $user->pegawai->jabatan->bawahan->where('id', $bawahan->jabatan->id)->count() != 0;
    }
}
