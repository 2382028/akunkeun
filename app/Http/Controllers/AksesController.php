<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Versi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AksesController extends Controller
{
   
    public function index(Request $request) {
        DB::table('notifications')
        ->where('created_at', '<', now()->subMonth())
        ->delete();

        $role = $request->query('role');
        

        // dd($role);
        if (!config('app.isMaintenance')) {
            return view('akses', [
                "versis" => Versi::all(),
                'title' => 'Akses Masuk',
                'active' => 'index',
            ]);
        } else {
            if ($role == 'admin') {
                return view('akses', [
                    "versis" => Versi::all(),
                    'title' => 'Akses Masuk',
                    'active' => 'index',
                ]);
            } else {
                return view('maintenance_page', [
                    'role' => $role,
                ]);
            }
        }
    }

    public function authenticate(Request $request)
    {
        $credit = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('pegawai')->attempt($credit)) {
            $request->session()->regenerate();
            session(['versi' => $request->versi]);
            return redirect('/');
        }

        return back()->with('LoginError', 'Akses masuk salah. Harap periksa kembali!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect('/akses');
    }

    public function profile() {
        $pokja = DB::table('pegawais')
                     
                        ->join('fungsis', 'pegawais.fungsi_id', '=', 'fungsis.id')
                        ->select('pegawais.id as idPegawai', 'pegawais.nama_lengkap', 'fungsis.nama_fungsi')
                        ->where('pegawais.id', auth('pegawai')->user()->id)
                        ->get();
        $tpengajuan = DB::table('info_perjadinlangsungs')
                        ->join('data_perjadinlangsungs', 'data_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
                        ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
                        ->select('info_perjadinlangsungs.id')
                        ->where('info_perjadinlangsungs.status_pengajuan', 'pengajuan')
                        ->where('pegawais.id', auth('pegawai')->user()->id)
                        ->count();
        $prosesPerjadin = DB::table('info_perjadinlangsungs')
                        ->join('data_perjadinlangsungs', 'data_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
                        ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
                        ->select('info_perjadinlangsungs.id')
                        ->where('info_perjadinlangsungs.status_pengajuan', 'proses')
                        ->where('pegawais.id', auth('pegawai')->user()->id)
                        ->count();
        $selesaiperjadin = DB::table('info_perjadinlangsungs')
                        ->join('data_perjadinlangsungs', 'data_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
                        ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
                        ->select('info_perjadinlangsungs.id')
                        ->where('info_perjadinlangsungs.status_pengajuan', 'selesai')
                        ->where('pegawais.id', auth('pegawai')->user()->id)
                        ->count();
        // kegiatan
        $kpengajuan = DB::table('data_perjadinkegiatans')
                        ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
                        ->join('perangkat_acaras', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
                        ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
                        ->select('data_perjadinkegiatans.id as idKegiatan', 'data_perjadinkegiatans.nama_kegiatan', 'data_perjadinkegiatans.jenis_kegiatan', 'data_perjadinkegiatans.tgl_mulai', 'data_perjadinkegiatans.status', 'perangkat_acaras.pegawai_id', 'pegawais.nama_lengkap', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
                        ->where('pegawais.id', auth('pegawai')->user()->id)
                        ->where('data_perjadinkegiatans.status', 'pengajuan')
                        ->count();
        $kproses = DB::table('data_perjadinkegiatans')
                        ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
                        ->join('perangkat_acaras', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
                        ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
                        ->select('data_perjadinkegiatans.id as idKegiatan', 'data_perjadinkegiatans.nama_kegiatan', 'data_perjadinkegiatans.jenis_kegiatan', 'data_perjadinkegiatans.tgl_mulai', 'data_perjadinkegiatans.status', 'perangkat_acaras.pegawai_id', 'pegawais.nama_lengkap', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
                        ->where('pegawais.id', auth('pegawai')->user()->id)
                        ->where('data_perjadinkegiatans.status', 'proses')
                        ->count();
        $kselesai = DB::table('data_perjadinkegiatans')
                        ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
                        ->join('perangkat_acaras', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
                        ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
                        ->select('data_perjadinkegiatans.id as idKegiatan', 'data_perjadinkegiatans.nama_kegiatan', 'data_perjadinkegiatans.jenis_kegiatan', 'data_perjadinkegiatans.tgl_mulai', 'data_perjadinkegiatans.status', 'perangkat_acaras.pegawai_id', 'pegawais.nama_lengkap', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
                        ->where('pegawais.id', auth('pegawai')->user()->id)
                        ->where('data_perjadinkegiatans.status', 'selesai')
                        ->count();
        // barang saya
        $fpengajuan = DB::table('data_penanggungjawabs')
                        ->join('assets', 'data_penanggungjawabs.asset_id', '=', 'assets.id')
                        ->join('pegawais', 'data_penanggungjawabs.pegawai_id', '=', 'pegawais.id')
                        ->select('data_penanggungjawabs.id as idPenanggungJawab', 'assets.nama_barang', 'assets.status_peminjaman', 'data_penanggungjawabs.tgl_mulai_digunakan', 'data_penanggungjawabs.status', 'pegawais.nama_lengkap')
                        ->where('pegawais.id', auth('pegawai')->user()->id)
                        ->where('data_penanggungjawabs.status', 'pengajuan')
                        ->count();
        $fdigunakan = DB::table('data_penanggungjawabs')
                        ->join('assets', 'data_penanggungjawabs.asset_id', '=', 'assets.id')
                        ->join('pegawais', 'data_penanggungjawabs.pegawai_id', '=', 'pegawais.id')
                        ->select('data_penanggungjawabs.id as idPenanggungJawab', 'assets.nama_barang', 'assets.status_peminjaman', 'data_penanggungjawabs.tgl_mulai_digunakan', 'data_penanggungjawabs.status', 'pegawais.nama_lengkap')
                        ->where('pegawais.id', auth('pegawai')->user()->id)
                        ->where('data_penanggungjawabs.status', 'digunakan')
                        ->count();
        $fselesai = DB::table('data_penanggungjawabs')
                        ->join('assets', 'data_penanggungjawabs.asset_id', '=', 'assets.id')
                        ->join('pegawais', 'data_penanggungjawabs.pegawai_id', '=', 'pegawais.id')
                        ->select('data_penanggungjawabs.id as idPenanggungJawab', 'assets.nama_barang', 'assets.status_peminjaman', 'data_penanggungjawabs.tgl_mulai_digunakan', 'data_penanggungjawabs.status', 'pegawais.nama_lengkap')
                        ->where('pegawais.id', auth('pegawai')->user()->id)
                        ->where('data_penanggungjawabs.status', 'selesai')
                        ->count();

        return view('user.profile', [
            'title' => 'Profile Saya',
            'active' => 'profile',
            'pokja' => $pokja,
            'tpengajuan' => $tpengajuan,
            'tproses' => $prosesPerjadin,
            'tselesai' => $selesaiperjadin,
            'kpengajuan' => $kpengajuan,
            'kproses' => $kproses,
            'kselesai' => $kselesai,
            'fpengajuan' => $fpengajuan,
            'fdigunakan' => $fdigunakan,
            'fselesai' => $fselesai,
        ]);
    }

    public function ubah() {
        return view('user.ubah_pass', [
            'title' => 'Ubah password',
            'active' => 'profile',
        ]);
    }

    public function ubahPassword(Request $request)
    {
        $request->validate([
            'newPassword' => 'required|string|min:8',
            'password_confirmation' => 'required|string|min:8|same:newPassword'
        ]);

        DB::table('pegawais')
        ->where('id', auth('pegawai')->user()->id)
        ->update([
            'password' => bcrypt($request->newPassword),
            'updated_at' => now(),
        ]);

        return redirect()->route('profile')->with('success', 'Password diperbaharui!');

    }
}
