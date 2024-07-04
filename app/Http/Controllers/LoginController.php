<?php

namespace App\Http\Controllers;

use App\Models\Versi;
use App\Models\Administrator;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function login()
    {
        return view('admin.login', [
            "versis" => Versi::all()

        ]);
    }
    
    public function pdf()
    {
        return view('admin.pdfprint', [
            'title' => 'printpdf',
        ]);
    }

    public function dashboard()
    {
        $perjadin = DB::table('info_perjadinlangsungs')
                        ->where('status_pengajuan', 'pengajuan')
                        ->where('versi_id', session('versi'))
                        ->count();
        $kegiatan = DB::table('data_perjadinkegiatans')
                        ->where('status', 'pengajuan')
                        ->where('versi_id', session('versi'))
                        ->count();
        return view('admin.dashboard', [
            'title' => 'Dashboard',
            'perjadin' => $perjadin,
            'kegiatan' => $kegiatan,
        ]);
    }

    public function authenticate(Request $request)
    {
        $credit = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::guard('administrator')->attempt($credit)) {
            $request->session()->regenerate();
            session(['versi' => $request->versi]);
            return redirect('/dashboard');
        }
 
        return back()->with('LoginError', 'Akses masuk salah. Harap periksa kembali!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();

        $request->session()->forget('versi');
    
        return redirect('/administrator')->with('LoginError', 'Berhasil Keluar!');
    }
}
