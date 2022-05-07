<?php

namespace App\Policies;

use App\Skp;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SkpPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    public function skpIsMine(User $user, Skp $skp)
    {
        return $user->pegawai->skp_periode->where('id', $skp->skp_periode_id)->count() != 0;
    }
}
