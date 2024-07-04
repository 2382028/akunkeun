<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Ref_rkakl_program;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Ref_rkakl_satker;
use Illuminate\Http\RedirectResponse;

class RkaklProgramController extends Controller
{
    // function index untuk tampilkan data
    public function index()
    {
        $rkaklprograms = DB::table('ref_rkakl_programs')
            ->SELECT('ref_rkakl_programs.*', 'ref_rkakl_satkers.id as id_satker','ref_rkakl_satkers.kode_satker', 'ref_rkakl_satkers.satker')
            ->JOIN('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
            ->where('ref_rkakl_programs.versi_id', session('versi'))
            ->get();

        return view('admin.referensi.rkakl.rkakl_program', [
            'title' => 'Rkakl Program',
            'rkaklprograms' => $rkaklprograms,
            'rkaklsatkers' => Ref_rkakl_satker::all()
        ]);
    }

    // public function show($id)
    // {
    //     $rkaklprograms = DB::table('ref_rkakl_programs')
    //         ->SELECT('ref_rkakl_programs.*', 'ref_rkakl_satkers.kode_satker')
    //         ->JOIN('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
    //         ->WHERE('ref_rkakl_satkers.id', '=', $id)
    //         ->get();

    //     return view('admin.referensi.rkakl.rkakl_program', [
    //         'title' => 'Rkakl Program',
    //         'rkaklprograms' => $rkaklprograms,
    //     ]);
    // }

    // function create untuk tambah data
    public function create(): View
    {
        return view('admin.referensi.rkakl.rkakl_program', [
            'title' => 'Rkakl Program',
        ]);
    }

    // function store untuk proses data
    public function store(Request $request): RedirectResponse
    {
        DB::table('ref_rkakl_programs')->insertOrIgnore([
            'ref_rkakl_satker_id' => $request->kode_satker,
            'kode_program' => $request->kode_program,
            'program' => $request->program,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('admin-rkakl_program')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    // function edit untuk find edit data by id
    public function edit(string $id): View
    {
        // get pegawai by id
        $rkaklsatkers = Ref_rkakl_satker::all();
        $rkaklprograms = DB::table('ref_rkakl_programs')
            ->SELECT('ref_rkakl_programs.*', 'ref_rkakl_satkers.kode_satker as kode_induk')
            ->JOIN('ref_rkakl_satkers',  'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
            ->WHERE('ref_rkakl_programs.id', '=', $id)
            ->get();

        return view('admin.referensi.rkakl.edit_rkakl_program', ['title' => 'Rkakl Program','rkaklsatkers' => $rkaklsatkers, 'rkaklprograms' => $rkaklprograms])->with(['success' => 'Data Berhasil Disimpan!']);
    }

    // function update untuk update data by id
    public function update(Request $request, $id): RedirectResponse
    {
        $rkaklprogram = Ref_rkakl_program::findOrFail($id);
        $rkaklprogram->update([
            'ref_rkakl_satker_id' => $request->kode_satker,
            'kode_program' => $request->kode_program,
            'program' => $request->program,
            'created_at' => old(),
            'updated_at' => now()
        ]);


        return redirect()->route('admin-rkakl_program.index')->with(['success' => 'Data Berhasil Diupdate!']);
    }

    // function destroy untuk hapus data by id
    public function destroy($id): RedirectResponse
    {
        // get by id
        $rkaklprogram = Ref_rkakl_program::findOrFail($id);

        //delete post
        $rkaklprogram->delete();

        //redirect to index
        return redirect('admin-rkakl_program')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
