<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Ref_rkakl_output;
use App\Models\Ref_rkakl_satker;
use App\Models\Ref_rkakl_program;
use App\Models\Ref_rkakl_kegiatan;
use Illuminate\Support\Facades\DB;
use App\Models\Ref_rkakl_suboutput;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Models\Versi;

class RkaklSuboutputController extends Controller
{
    // function index untuk tampilkan data
    public function index()
    {
        $rkaklsuboutput = DB::table('ref_rkakl_suboutputs')
            ->select('ref_rkakl_suboutputs.*', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program', 'ref_rkakl_kegiatans.kode_kegiatan', 'ref_rkakl_outputs.kode_output')
            ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
            ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
            ->where('ref_rkakl_suboutputs.versi_id', session('versi'))
            ->get();
        return view('admin.referensi.rkakl.rkakl_suboutput', [
            'title' => 'Rkakl Suboutput',
            'rkaklsatkers' => Ref_rkakl_satker::where('versi_id', session('versi', '-1'))->get(),
            'rkaklprograms' => Ref_rkakl_program::where('versi_id', session('versi', '-1'))->get(),
            'rkaklkegiatans' => Ref_rkakl_kegiatan::where('versi_id', session('versi', '-1'))->get(),
            'rkakloutputs' => Ref_rkakl_output::where('versi_id', session('versi', '-1'))->get(),
            'rkaklsuboutputs' => $rkaklsuboutput
        ]);
    }

    // function create untuk tambah data
    public function create(): View
    {
        return view('admin.referensi.rkakl.rkakl_suboutput', [
            'title' => 'Rkakl Suboutput',
        ]);
    }

    // function store untuk proses data
    public function store(Request $request): RedirectResponse
    {
        DB::table('ref_rkakl_suboutputs')->insertOrIgnore([
            'ref_rkakl_output_id' => $request->id_output,
            'kode_sub_output' => $request->kode_sub_output,
            'nama_sub_output' => $request->nama_sub_output,
            'versi_id' => session('versi'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('admin-rkakl_suboutput')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    // function edit untuk find edit data by id
    public function edit(string $id): View
    {

        return view('admin.referensi.rkakl.edit_rkakl_suboutput', [
            'title' => 'Rkakl Suboutput',
            'rkaklsatkers' => Ref_rkakl_satker::where('versi_id', session('versi', '-1'))->get(),
            'rkaklprograms' => Ref_rkakl_program::where('versi_id', session('versi', '-1'))->get(),
            'rkaklkegiatans' => Ref_rkakl_kegiatan::where('versi_id', session('versi', '-1'))->get(),
            'rkakloutputs' => Ref_rkakl_output::where('versi_id', session('versi', '-1'))->get(),
            'rkaklsuboutput' => Ref_rkakl_suboutput::find($id),
        ]);
    }

    // function update untuk update data by id
    public function update(Request $request, $id): RedirectResponse
    {
        $rkaklsuboutput = Ref_rkakl_suboutput::findOrFail($id);
        $rkaklsuboutput->update([
            'ref_rkakl_output_id' => $request->id_output,
            'kode_suboutput' => $request->kode_suboutput,
            'nama_suboutput' => $request->nama_suboutput,
            'created_at' => old(),
            'updated_at' => now()
        ]);


        return redirect()->route('admin-rkakl_suboutput.index')->with(['success' => 'Data Berhasil Diupdate!']);
    }


    // function destroy untuk hapus data by id
    public function destroy($id): RedirectResponse
    {
        // get by id
        $rkaklsuboutput = Ref_rkakl_suboutput::findOrFail($id);

        //delete post
        $rkaklsuboutput->delete();

        //redirect to index
        return redirect('admin-rkakl_suboutput')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
