<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            if (
                $request->is('admin*') ||
                $request->is('administrator*') ||
                $request->is('dashboard*') ||
                $request->is('perjadin-*') ||
                $request->is('kegiatan-*') ||
                $request->is('bmn*') ||
                $request->is('pemeliharaan-admin*') ||
                $request->is('penyewaan_aset*') ||
                $request->is('ref*') ||
                $request->is('spby*') ||
                $request->is('koreksi*') ||
                $request->is('buat-pengadaan*') ||
                $request->is('daftar-pengadaan*') ||
                $request->is('sbm*') ||
                $request->is('monitoring*') ||
                $request->is('laporan*') ||
                $request->is('jenis_program*')
            ) {
                return route('administrator.login');
            }
            return route('login');
        }
        return null;
    }
}
