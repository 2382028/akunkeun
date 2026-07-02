<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Ref_rkakl_output;
use App\Models\Ref_rkakl_kegiatan;
use App\Models\Ref_rkakl_satker;
use App\Models\Ref_rkakl_program;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class RkaklOutputController extends Controller
{
    // function index untuk tampilkan data
    public function index()
    {
        $rkakloutput = DB::table('ref_rkakl_outputs')
            ->select('ref_rkakl_outputs.*', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program','ref_rkakl_kegiatans.kode_kegiatan')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
            ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
            ->where('ref_rkakl_outputs.versi_id', session('versi'))
            ->get();
        return view('admin.referensi.rkakl.rkakl_output', [
            'title' => 'Rkakl Output',
            'rkaklsatkers' => Ref_rkakl_satker::where('versi_id', session('versi', '-1'))->get(),
            'rkaklprograms' => Ref_rkakl_program::where('versi_id', session('versi', '-1'))->get(),
            'rkaklkegiatans' => Ref_rkakl_kegiatan::where('versi_id', session('versi', '-1'))->get(),
            'rkakloutputs' => $rkakloutput
        ]);
    }

    // function create untuk tambah data
    public function create(): View
    {
        return view('admin.referensi.rkakl.rkakl_output', [
            'title' => 'Rkakl Output',
        ]);
    }

    // function store untuk proses data
    public function store(Request $request): RedirectResponse
    {
        DB::table('ref_rkakl_outputs')->insertOrIgnore([
            'ref_rkakl_kegiatan_id' => $request->id_kegiatan,
            'kode_output' => $request->kode_output,
            'nama_output' => $request->nama_output,
            'versi_id' => session('versi'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('admin-rkakl_output')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    // function edit untuk find edit data by id
    public function edit(string $id): View
    {

        return view('admin.referensi.rkakl.edit_rkakl_output', [
            'title' => 'Rkakl Output',
            'rkaklsatkers' => Ref_rkakl_satker::where('versi_id', session('versi', '-1'))->get(),
            'rkaklprograms' => Ref_rkakl_program::where('versi_id', session('versi', '-1'))->get(),
            'rkaklkegiatans' => Ref_rkakl_kegiatan::where('versi_id', session('versi', '-1'))->get(),
            'rkakloutput' => Ref_rkakl_output::find($id),
        ]);
    }

    // function update untuk update data by id
    public function update(Request $request, $id): RedirectResponse
    {
        $rkakloutput = Ref_rkakl_output::findOrFail($id);
        $rkakloutput->update([
            'ref_rkakl_kegiatan_id' => $request->id_kegiatan,
            'kode_output' => $request->kode_output,
            'nama_output' => $request->nama_output,
            'created_at' => old(),
            'updated_at' => now()
        ]);


        return redirect()->route('admin-rkakl_output.index')->with(['success' => 'Data Berhasil Diupdate!']);
    }

    // function destroy untuk hapus data by id
    public function destroy($id): RedirectResponse
    {
        // get by id
        $rkakloutput = Ref_rkakl_output::findOrFail($id);

        //delete post
        $rkakloutput->delete();

        //redirect to index
        return redirect('admin-rkakl_output')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
