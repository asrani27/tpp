<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check())
            return redirect('login');

        $user = Auth::user();
        $arrayRole = explode('|', $roles[0]);

        foreach ($arrayRole as $role) {
            if ($user->hasRole($role))
                return $next($request);
        }

        toastr()->error('Anda Tidak Punya Akses Ke halaman Tersebut');
        return back();
    }
}
