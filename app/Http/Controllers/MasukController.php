<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;   // ← untuk Auth
use Illuminate\Support\Facades\Hash;   // ← untuk Hash
use App\Models\Penyewa;
use App\Models\AkunPenyewa;

class MasukController extends Controller
{
    public function index()
    {
        return view('sewa.masuk', [
            'title' => 'Login',
            'active' => 'login'
        ]);
    }

public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::guard('akun_penyewa')->attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();

        return redirect('/sewa/index'); // arahkan ke halaman penyewa
    }

    return back()->with('LoginError', 'Email atau password salah')->withInput();
}


    public function logout(Request $request)
    {
        Auth::guard('akun_penyewa')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login-penyewa');
    }

    public function showRegisterForm()
    {
        return view('sewa.register', [
            'title' => 'Register',
            'active' => 'register'
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_telepon' => 'required|digits_between:10,15|numeric',
            'nik' => 'required|digits:16',
            'email' => 'required|email|unique:akun_penyewas,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Buat data penyewa
        $penyewa = Penyewa::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'no_telepon' => '62' . ltrim($validated['no_telepon'], '0'),
            'nik' => $validated['nik'],
        ]);

        // Buat akun penyewa
        $akun = AkunPenyewa::create([
            'id_penyewa' => $penyewa->id_penyewa,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Login otomatis
        Auth::guard('akun_penyewa')->login($akun);

        return redirect('/dashboard-penyewa');
    }

    public function edit()
    {
        $user = Auth::guard('akun_penyewa')->user();

        $penyewa = Penyewa::findOrFail($user->id_penyewa);

        return view('sewa.profile', compact('user', 'penyewa'));
    }
    public function update(Request $request)
    {
        $user = Auth::guard('akun_penyewa')->user();

        // normalisasi nomor telepon (selalu diawali 62)
        $noTelepon = '62' . ltrim($request->no_telepon, '0');

        // Update penyewa
        $penyewa = Penyewa::findOrFail($user->id_penyewa);
        $penyewa->update([
            'nama_lengkap' => $request->nama_lengkap,
            'nik'          => $request->nik,
            'no_telepon'   => $noTelepon,
        ]);

        // Update akun_penyewa
        $dataAkun = ['email' => $request->email];
        if ($request->filled('password')) {
            $dataAkun['password'] = Hash::make($request->password);
        }
        $user->update($dataAkun);

        return back()->with('success', 'Profil berhasil diperbarui');
    }
}
