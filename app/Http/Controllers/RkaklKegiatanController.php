<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Ref_rkakl_kegiatan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Ref_rkakl_program;
use App\Models\Ref_rkakl_satker;
use Illuminate\Http\RedirectResponse;

class RkaklKegiatanController extends Controller
{
    // function index untuk tampilkan data
    public function index()
    {
        $rkaklkegiatans = DB::table('ref_rkakl_kegiatans')
            ->select('ref_rkakl_kegiatans.*', 'ref_rkakl_programs.kode_program', 'ref_rkakl_satkers.kode_satker')
            ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
            ->where('ref_rkakl_kegiatans.versi_id', session('versi'))
            ->get();

        return view('admin.referensi.rkakl.rkakl_kegiatan', [
            'title' => 'Rkakl Kegiatan',
            'rkaklsatkers' => Ref_rkakl_satker::all(),
            'rkaklprograms' => Ref_rkakl_program::all(),
            'rkaklkegiatans' => $rkaklkegiatans
        ]);
    }

    // untuk ambil data by id
    // public function show($id)
    // {
    //     $rkaklkegiatans = DB::table('ref_rkakl_kegiatans')
    //         ->SELECT('ref_rkakl_kegiatans.*', 'ref_rkakl_satkers.id', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program')
    //         ->JOIN('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
    //         ->JOIN('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
    //         ->WHERE('ref_rkakl_programs.id', '=', $id)
    //         ->get();

    //     return view('admin.referensi.rkakl.rkakl_kegiatan', [
    //         'title' => 'Rkakl Program',
    //         'rkaklkegiatans' => $rkaklkegiatans,
    //         'idsatker' => $id,
    //         'rkaklprogram' => Ref_rkakl_program::where('id', $id)->get()
    //     ]);
    // }

    // function create untuk tambah data
    public function create(): View
    {
        return view('admin.referensi.rkakl.rkakl_kegiatan', [
            'title' => 'Rkakl Kegiatan',
        ]);
    }

    // function store untuk proses data
    public function store(Request $request): RedirectResponse
    {
        DB::table('ref_rkakl_kegiatans')->insertOrIgnore([
            'ref_rkakl_program_id' => $request->id_program,
            'kode_kegiatan' => $request->kode_kegiatan,
            'nama_kegiatan' => $request->nama_kegiatan,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('admin-rkakl_kegiatan')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    // function edit untuk find edit data by id
    public function edit(string $id): View
    {
        // get kegiatan by id
        $rkaklkegiatans = DB::table('ref_rkakl_kegiatans')
            ->SELECT('ref_rkakl_kegiatans.*', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.id as id_program', 'ref_rkakl_programs.kode_program', 'ref_rkakl_programs.program')
            ->JOIN('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
            ->JOIN('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
            ->WHERE('ref_rkakl_kegiatans.id', '=', $id)
            ->get();

        return view('admin.referensi.rkakl.edit_rkakl_kegiatan', [
            'rkaklsatkers' => Ref_rkakl_satker::all(),
            'rkaklprograms' => Ref_rkakl_program::all(),
            'rkaklkegiatans' => $rkaklkegiatans,
            'title' => 'Rkakl Kegiatan',
        ])->with(['success' => 'Data Berhasil Disimpan!']);
    }

    // function update untuk update data by id
    public function update(Request $request, $id): RedirectResponse
    {
        $rkaklkegiatan = Ref_rkakl_kegiatan::findOrFail($id);
        $rkaklkegiatan->update([
            'ref_rkakl_program_id' => $request->id_program,
            'kode_kegiatan' => $request->kode_kegiatan,
            'nama_kegiatan' => $request->nama_kegiatan,
            'created_at' => old(),
            'updated_at' => now()
        ]);


        return redirect()->route('admin-rkakl_kegiatan.index')->with(['success' => 'Data Berhasil Diupdate!']);
    }

    // function destroy untuk hapus data by id
    public function destroy($id): RedirectResponse
    {
        // get by id
        $rkaklkegiatan = Ref_rkakl_kegiatan::findOrFail($id);

        //delete post
        $rkaklkegiatan->delete();

        //redirect to index
        return redirect('admin-rkakl_kegiatan')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
