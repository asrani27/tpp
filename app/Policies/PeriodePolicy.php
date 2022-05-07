<?php

namespace App\Policies;

use App\Skp_periode;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PeriodePolicy
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

    public function periodeIsMine(User $user, Skp_periode $skp_periode)
    {
        return $user->pegawai->id === $skp_periode->pegawai_id;
    }
}
