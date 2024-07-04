<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Akun_x_rkakl;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Models\Ref_rkakl_sub_komponen;
use App\Models\Akun;
use App\Models\Ref_rkakl_output;
use App\Models\Ref_rkakl_satker;
use App\Models\Ref_rkakl_program;
use App\Models\Ref_rkakl_kegiatan;
use App\Models\Ref_rkakl_komponen;

use App\Models\Ref_rkakl_suboutput;


class AkunXRkaklController extends Controller
{
    // function index untuk tampilkan data
    public function index()
    {
        $akunxrkakl = DB::table('akun_x_rkakls')
        ->select('akun_x_rkakls.*', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program', 'ref_rkakl_kegiatans.kode_kegiatan', 'ref_rkakl_outputs.kode_output', 'ref_rkakl_suboutputs.kode_sub_output','ref_rkakl_komponens.kode_komponen','ref_rkakl_sub_komponens.kode_sub_kegiatan','akuns.kode_akun','ref_rkakl_sub_komponens.nama_sub_kegiatan','akuns.uraian','akuns.nominal')
        ->join('akuns', 'akuns.id', '=', 'akun_x_rkakls.akun_id')
        ->join('ref_rkakl_sub_komponens', 'ref_rkakl_sub_komponens.id', '=', 'akun_x_rkakls.ref_sub_komponen_id')
        ->join('ref_rkakl_komponens', 'ref_rkakl_komponens.id', '=', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id')
        ->join('ref_rkakl_suboutputs', 'ref_rkakl_suboutputs.id', '=', 'ref_rkakl_komponens.ref_rkakl_suboutput_id')
        ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
        ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
        ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
        ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
            ->where('akun_x_rkakls.versi_id', session('versi'))
            ->get();
        return view('admin.referensi.rkakl.rkaklxakun', [
            'title' => 'Akun x Rkakl',
            'rkaklsatkers' => Ref_rkakl_satker::all(),
            'rkaklprograms' => Ref_rkakl_program::all(),
            'rkaklkegiatans' => Ref_rkakl_kegiatan::all(),
            'rkakloutputs' => Ref_rkakl_output::all(),
            'rkaklsuboutputs' => Ref_rkakl_suboutput::all(),
            'rkaklkomponens' => Ref_rkakl_komponen::all(),
            'akunxrkakls' => $akunxrkakl,
            'rkaklsubkomponens' => Ref_rkakl_sub_komponen::all(),
            'akuns' => Akun::all(),
        ]);
    }

    // function create untuk tambah data
    public function create(): View
    {
        return view('admin.referensi.rkakl.rkaklxakun', [
            'title' => 'Akun',
        ]);
    }

    // function store untuk proses data
    public function store(Request $request): RedirectResponse
    {
        // Insert data into 'akuns' table
        $akunId = DB::table('akuns')->insertGetId([
            'kode_akun' => $request->kode_akun,
            'uraian' => $request->uraian,
            'nominal' => $request->nominal,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Insert data into 'akun_x_rkakls' table with 'ref_sub_komponen_id'
        DB::table('akun_x_rkakls')->insertOrIgnore([
            'akun_id' => $akunId, // Use the newly inserted 'akun' ID
            'ref_sub_komponen_id' => $request->sub_komponen_id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('admin-akun_x_rkakl')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    // function edit untuk find edit data by id
    public function edit(string $id): View
    {

        return view('admin.referensi.rkakl.edit_akun_x_rkakl', [
            'title' => 'Akun',
            'rkaklsatkers' => Ref_rkakl_satker::all(),
            'rkaklprograms' => Ref_rkakl_program::all(),
            'rkaklkegiatans' => Ref_rkakl_kegiatan::all(),
            'rkakloutputs' => Ref_rkakl_output::all(),
            'rkaklsuboutputs' => Ref_rkakl_suboutput::all(),
            'rkaklkomponens' => Ref_rkakl_komponen::all(),
            'akunxrkakl' => Akun_x_rkakl::find($id),
            'akuns' => Akun::all(),
            'rkaklsubkomponens' => Ref_rkakl_sub_komponen::all(),
        ]);
    }

    // function update untuk update data by id
    public function update(Request $request, $id): RedirectResponse
    {
        // Get the Akun_x_rkakl record by id
        $akunxrkakl = Akun_x_rkakl::findOrFail($id);

        // Update the Akun_x_rkakl record
        $akunxrkakl->update([
            'ref_sub_komponen_id' => $request->ref_sub_komponen_id,
            'updated_at' => now()
        ]);

        // Get the Akun record associated with Akun_x_rkakl
        $akun = Akun::findOrFail($akunxrkakl->akun_id);

        // Update the Akun record
        $akun->update([
            'kode_akun' => $request->kode_akun,
            'uraian' => $request->uraian,
            'nominal' => $request->nominal,
            'nama_sub_kegiatan' => $request->nama_sub_kegiatan,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('admin-akun_x_rkakl.index')->with(['success' => 'Data Berhasil Diupdate!']);
    }


    // function destroy untuk hapus data by id
    public function destroy($id): RedirectResponse
    {
        // Get the Akun_x_rkakl record by id
        $akunxrkakl = Akun_x_rkakl::findOrFail($id);

        // Get the Akun record associated with Akun_x_rkakl
        $akun = Akun::findOrFail($akunxrkakl->akun_id);

        // Delete the Akun_x_rkakl record
        $akunxrkakl->delete();

        // Delete the Akun record
        $akun->delete();

        // Redirect to index
        return redirect('admin-akun_x_rkakl')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}