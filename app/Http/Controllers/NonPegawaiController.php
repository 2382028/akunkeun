<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Non_pegawai;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class NonPegawaiController extends Controller
{
    // function index untuk get all data
    public function index()
    {
        return view('admin.kelola_user.nonpegawai', [
            'nonpegawais' => Non_pegawai::all(),
            'title' => 'Data Non-Pegawai LLDIKTI',
        ]);
    }

    public function create(): View
    {
        return view('admin.kelola_user.nonpegawai',['title' => 'Data Non-Pegawai LLDIKTI',]);
    }

    // function store untuk proses data
    public function store(Request $request): RedirectResponse
    {

        DB::table('non_pegawais')->insertOrIgnore([
            'NIP_NIK' => $request->NIP_NIK,
            'nama_lengkap' => $request->nama_lengkap,
            'status' => $request->status,
            'golongan' => $request->golongan,
            'pangkat' => $request->pangkat,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('admin-nonpegawai')->with(['success' => 'Data Berhasil Disimpan!']);
    }


    // function edit untuk find edit data by id
    public function edit(string $id): View
    {
        // get admin by id
        return view('admin.kelola_user.detail_nonpegawai', [
            'nonpegawai' => Non_pegawai::findOrFail($id),
            'title' => 'Data Non-Pegawai LLDIKTI',
        ]);
    }

        // function update untuk update data by id
        public function update(Request $request, $id): RedirectResponse
        {
            $nonpegawai = Non_pegawai::findOrFail($id);
            $nonpegawai->update([
                'NIP_NIK' => $request->NIP_NIK,
                'nama_lengkap' => $request->nama_lengkap,
                'status' => $request->status,
                'golongan' => $request->golongan,
                'pangkat' => $request->pangkat,
                'alamat' => $request->alamat,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'created_at' => $nonpegawai->created_at,
                'updated_at' => now()
            ]);
    
    
            return redirect()->route('admin-nonpegawai.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }

    // function destroy untuk hapus data by id
    public function destroy($id): RedirectResponse
    {
        // get by id
        $nonpegawai = Non_pegawai::findOrFail($id);

        //delete post
        $nonpegawai->delete();

        //redirect to index
        return redirect('admin-nonpegawai')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
