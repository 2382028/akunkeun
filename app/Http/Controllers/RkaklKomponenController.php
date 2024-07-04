<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Ref_rkakl_output;
use App\Models\Ref_rkakl_satker;
use App\Models\Ref_rkakl_program;
use App\Models\Ref_rkakl_kegiatan;
use App\Models\Ref_rkakl_komponen;
use App\Models\Ref_rkakl_suboutput;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class RkaklKomponenController extends Controller
{
    // function index untuk tampilkan data
    public function index()
    {
        $rkaklskomponen = DB::table('ref_rkakl_komponens')
        ->select('ref_rkakl_komponens.*', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program', 'ref_rkakl_kegiatans.kode_kegiatan', 'ref_rkakl_outputs.kode_output','ref_rkakl_suboutputs.kode_sub_output')
        ->join('ref_rkakl_suboutputs', 'ref_rkakl_suboutputs.id', '=', 'ref_rkakl_komponens.ref_rkakl_suboutput_id')
        ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
        ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
        ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
        ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
            ->where('ref_rkakl_komponens.versi_id', session('versi'))
            ->get();
        return view('admin.referensi.rkakl.rkakl_komponen', [
            'title' => 'Rkakl Komponen',
            'rkaklsatkers' => Ref_rkakl_satker::all(),
            'rkaklprograms' => Ref_rkakl_program::all(),
            'rkaklkegiatans' => Ref_rkakl_kegiatan::all(),
            'rkakloutputs' => Ref_rkakl_output::all(),
            'rkaklsuboutputs' => Ref_rkakl_suboutput::all(),
            'rkaklkomponens' => $rkaklskomponen 
        ]);
    }

    // function create untuk tambah data
    public function create(): View
    {
        return view('admin.referensi.rkakl.rkakl_komponens', [
            'title' => 'Rkakl Komponen',
        ]);
    }

    // function store untuk proses data
    public function store(Request $request): RedirectResponse
    {
        DB::table('ref_rkakl_komponens')->insertOrIgnore([
            'ref_rkakl_suboutput_id' => $request->id_suboutput,
            'kode_komponen' => $request->kode_komponen,
            'nama_komponen' => $request->nama_komponen,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('admin-rkakl_komponen')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    // function edit untuk find edit data by id
    public function edit(string $id): View
    {

        return view('admin.referensi.rkakl.edit_rkakl_komponen', [
            'title' => 'Rkakl komponen',
            'rkaklsatkers' => Ref_rkakl_satker::all(),
            'rkaklprograms' => Ref_rkakl_program::all(),
            'rkaklkegiatans' => Ref_rkakl_kegiatan::all(),
            'rkakloutputs' => Ref_rkakl_output::all(),
            'rkaklsuboutputs' => Ref_rkakl_suboutput::all(),
            'rkaklkomponen' => Ref_rkakl_komponen::find($id),
        ]);
    }

    // function update untuk update data by id
    public function update(Request $request, $id): RedirectResponse
    {
        $rkaklkomponen = Ref_rkakl_komponen::findOrFail($id);
        $rkaklkomponen->update([
            'ref_rkakl_suboutput_id' => $request->id_suboutput,
            'kode_komponen' => $request->kode_komponen,
            'nama_komponen' => $request->nama_komponen,
            'created_at' => old(),
            'updated_at' => now()
        ]);


        return redirect()->route('admin-rkakl_komponen.index')->with(['success' => 'Data Berhasil Diupdate!']);
    }


    // function destroy untuk hapus data by id
    public function destroy($id): RedirectResponse
    {
        // get by id
        $rkaklkomponen = Ref_rkakl_komponen::findOrFail($id);

        //delete post
        $rkaklkomponen->delete();

        //redirect to index
        return redirect('admin-rkakl_komponen')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
