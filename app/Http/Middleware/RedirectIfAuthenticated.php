<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null, 'pegawai', 'administrator', 'penyedia', 'akun_penyewa'] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($guard === 'administrator') {
                    return redirect('/dashboard');
                } elseif ($guard === 'penyedia') {
                    return redirect('/penyedia');
                } elseif ($guard === 'akun_penyewa') {
                    return redirect('/dashboard-penyewa');
                }
                return redirect('/');
            }
        }

        return $next($request);
    }
}
