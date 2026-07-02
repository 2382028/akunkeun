<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Ref_rkakl_output;
use App\Models\Ref_rkakl_satker;
use App\Models\Ref_rkakl_program;
use App\Models\Ref_rkakl_kegiatan;
use App\Models\Ref_rkakl_komponen;
use Illuminate\Support\Facades\DB;
use App\Models\Ref_rkakl_suboutput;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Models\Ref_rkakl_sub_komponen;
use App\Models\Versi;

class RkaklSubkomponenController extends Controller
{
    // function index untuk tampilkan data
    public function index()
    {
        $rkaklssubkomponen = DB::table('ref_rkakl_sub_komponens')
            ->select('ref_rkakl_sub_komponens.*', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program', 'ref_rkakl_kegiatans.kode_kegiatan', 'ref_rkakl_outputs.kode_output', 'ref_rkakl_suboutputs.kode_sub_output','ref_rkakl_komponens.kode_komponen')
            ->join('ref_rkakl_komponens', 'ref_rkakl_komponens.id', '=', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_suboutputs.id', '=', 'ref_rkakl_komponens.ref_rkakl_suboutput_id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
            ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
            ->where('ref_rkakl_sub_komponens.versi_id', session('versi'))
            ->get();
        return view('admin.referensi.rkakl.rkakl_subkomponen', [
            'title' => 'Rkakl Komponen',
            'rkaklsatkers' => Ref_rkakl_satker::where('versi_id', session('versi', '-1'))->get(),
            'rkaklprograms' => Ref_rkakl_program::where('versi_id', session('versi', '-1'))->get(),
            'rkaklkegiatans' => Ref_rkakl_kegiatan::where('versi_id', session('versi', '-1'))->get(),
            'rkakloutputs' => Ref_rkakl_output::where('versi_id', session('versi', '-1'))->get(),
            'rkaklsuboutputs' => Ref_rkakl_suboutput::where('versi_id', session('versi', '-1'))->get(),
            'rkaklkomponens' => Ref_rkakl_komponen::where('versi_id', session('versi', '-1'))->get(),
            'rkaklsubkomponens' => $rkaklssubkomponen
        ]);
    }

    // function create untuk tambah data
    public function create(): View
    {
        return view('admin.referensi.rkakl.rkakl_subkomponen', [
            'title' => 'Rkakl Komponen',
        ]);
    }

    // function store untuk proses data
    public function store(Request $request): RedirectResponse
    {
        $versi = Versi::where('status', 'aktif')->get();
        DB::table('ref_rkakl_sub_komponens')->insertOrIgnore([
            'ref_rkakl_komponen_id' => $request->id_komponen,
            'kode_sub_kegiatan' => $request->kode_sub_kegiatan,
            'nama_sub_kegiatan' => $request->nama_sub_kegiatan,
            'versi_id' => session('versi'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('admin-rkakl_subkomponen')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    // function edit untuk find edit data by id
    public function edit(string $id): View
    {
        // dd($id);
        return view('admin.referensi.rkakl.edit_rkakl_subkomponen', [
            'title' => 'Rkakl komponen',
            'rkaklsatkers' => Ref_rkakl_satker::where('versi_id', session('versi', '-1'))->get(),
            'rkaklprograms' => Ref_rkakl_program::where('versi_id', session('versi', '-1'))->get(),
            'rkaklkegiatans' => Ref_rkakl_kegiatan::where('versi_id', session('versi', '-1'))->get(),
            'rkakloutputs' => Ref_rkakl_output::where('versi_id', session('versi', '-1'))->get(),
            'rkaklsuboutputs' => Ref_rkakl_suboutput::where('versi_id', session('versi', '-1'))->get(),
            'rkaklkomponens' => Ref_rkakl_komponen::where('versi_id', session('versi', '-1'))->get(),
            'rkaklsubkomponen' => Ref_rkakl_sub_komponen::find($id),
        ]);
    }

    // function update untuk update data by id
    public function update(Request $request, $id): RedirectResponse
    {
        // dd($id);

        // dd($request->all());
        
        $rkaklsubkomponen = Ref_rkakl_sub_komponen::findOrFail($id);
        // dd($rkaklsubkomponen);
        // dd($request->nama_sub_kegiatan);
        $rkaklsubkomponen->update([
            'ref_rkakl_komponen_id' => $request->id_komponen,
            'kode_sub_kegiatan' => $request->kode_sub_kegiatan,
            'nama_sub_kegiatan' => $request->nama_sub_kegiatan,
            'created_at' => old(),
            'updated_at' => now()
        ]);
        

        return redirect()->route('admin-rkakl_subkomponen.index')->with(['success' => 'Data Berhasil Diupdate!']);
    }


    // function destroy untuk hapus data by id
    public function destroy($id): RedirectResponse
    {
        // get by id
        $rkaklsubkomponen = Ref_rkakl_sub_komponen::findOrFail($id);

        //delete post
        $rkaklsubkomponen->delete();

        //redirect to index
        return redirect('admin-rkakl_subkomponen')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
