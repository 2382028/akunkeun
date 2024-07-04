<?php

namespace App\Http\Controllers;

use App\Models\Fungsi;
use Illuminate\Support\Facades\Hash;
use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class PegawaiController extends Controller
{
    // function index untuk get all data
    public function index()
    {
        
        $jabatans = Jabatan::all();
        $fungsis = Fungsi::all();
        $pegawais = DB::table('pegawais')
            ->SELECT('pegawais.*', 'fungsis.nama_fungsi as pokja')
            ->JOIN('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->JOIN('fungsis', 'jabatans.fungsi_id', '=', 'fungsis.id')
            ->get();

        return view(
            'admin.kelola_user.pegawai',
            [
                'title' => 'Data Pegawai LLDIKTI',
                'pegawais' => $pegawais,
                'jabatans' => jabatan::all(),
                'fungsis' => fungsi::all(),
                
            
            ]
        );
    }

    // function create untuk tambah data
    public function create(): View
    {
         // get pegawai by id
         $jabatans = Jabatan::all();
         $fungsis = Fungsi::all();
         $pegawai = DB::table('pegawais')
             ->SELECT('pegawais.*', 'jabatans.nama_jabatan as jabatan', 'fungsis.nama_fungsi as pokja')
             ->JOIN('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
             ->JOIN('fungsis', 'jabatans.fungsi_id', '=', 'fungsis.id')
             ->WHERE('pegawais.id', '=', $id)
             ->get();
             
        return view('admin.kelola_user.pegawai');
    }
    
    // function store untuk proses data
    public function store(Request $request): RedirectResponse
    {
        
        Pegawai::create([
            'NIP_NIK' => $request->NIP_NIK,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'status' => $request->status,
            'golongan' => $request->golongan,
            'pangkat' => $request->pangkat,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jabatan_id' => $request->jabatan_id,
            'no_rekening' => $request->no_rekening,
            'is_aktif' => $request->is_aktif,
            'is_dinas' => $request->is_dinas,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('admin-pegawai')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    // function edit untuk find edit data by id
    public function edit(string $id): View
    {
        {$pegawai = Pegawai::findOrFail($id);
        $jabatans = Jabatan::all();
        $fungsis = Fungsi::all();
        return view('admin.kelola_user.detail_pegawai', compact('pegawai', 'jabatans', 'fungsis'));
        
        // get pegawai by id
        $jabatans = Jabatan::all();
        $fungsis = Fungsi::all();
        $pegawai = DB::table('pegawais')
            ->SELECT('pegawais.*', 'jabatans.nama_jabatan as jabatan', 'fungsis.nama_fungsi as pokja')
            ->JOIN('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->JOIN('fungsis', 'jabatans.fungsi_id', '=', 'fungsis.id')
            ->WHERE('pegawais.id', '=', $id)
            ->get();
        }
        return view('admin.kelola_user.detail_pegawai', ['title' => 'Data Pegawai LLDIKTI', 'pegawai' => $pegawai, 'jabatans' => $jabatans, 'fungsis' => $fungsis])->with(['success' => 'Data Berhasil Disimpan!']);
    }

    // function update untuk update data by id
    public function update(Request $request, $id): RedirectResponse
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->update([
            'NIP_NIK' => $request->NIP_NIK,
            'password' => Hash::make($request->password),
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'status' => $request->status,
            'golongan' => $request->golongan,
            'pangkat' => $request->pangkat,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'no_rekening' => $request->no_rekening,
            'is_aktif' => $request->is_aktif,
            'jabatan_id' => $request->jabatan_id,
            'pokja' => $request->fungsi_id,
            'created_at' => $pegawai->created_at,
            'updated_at' => now()
        ]);


        return redirect()->route('admin-pegawai.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    // function destroy untuk hapus data by id
    public function destroy($id): RedirectResponse
    {
        // get by id
        $pegawai = Pegawai::findOrFail($id);

        //delete post
        $pegawai->delete();

        //redirect to index
        return redirect('admin-pegawai')->with(['success' => 'Data Berhasil Dihapus!']);
    }
};