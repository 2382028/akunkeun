<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Hash;
use App\Models\Ref_ss_iku_programkerja;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;


class IKUApiController extends Controller
{
    //function untuk  index get all data
    //function untuk  index get all data
    public function indexIKU()
    {
        $iku = DB::table('ref_ss_iku_programkerjas')
            ->where('versi_id', session('versi'))
            ->get();

        return view('admin.referensi.iku', [
            'title' => 'IKU',
            'IKU' =>  $iku
        ]);
    }



    public function indexDetailIKU($id)
    {

        return view('admin.referensi.detail_iku', [
            'title' => 'IKU',
            'IKU' => Ref_ss_iku_programkerja::find($id)
        ]);
    }


    // function create untuk tambah data


    // function store untuk proses data
    public function storeIKU(Request $request): RedirectResponse
    {
        DB::table('ref_ss_iku_programkerjas')->insertOrIgnore([
            'tahun' => '2023',
            'kode_ss' => $request->kode_ss,
            'nama_ss' => $request->nama_ss,
            'kode_iku' => $request->kode_iku,
            'nama_iku' => $request->nama_iku,
            'pokja' => $request->pokja,
            'nama_program_kerja' => $request->nama_program_kerja,
        ]);

        return redirect('admin-iku')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    // function update untuk update data by id
    public function updateIKU(Request $request)
    {
        DB::table('ref_ss_iku_programkerjas')
            ->where('id', $request->idIKU)
            ->update([
                'tahun' => '2023',
                'kode_ss' => $request->kode_ss,
                'nama_ss' => $request->nama_ss,
                'kode_iku' => $request->kode_iku,
                'nama_iku' => $request->nama_iku,
                'pokja' => $request->pokja,
                'nama_program_kerja' => $request->nama_program_kerja,
            ]);

        return redirect('admin-iku')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    // function destroy untuk hapus data by id
    public function destroyIKU($id): RedirectResponse
    {
        // get by id
        $IKU = Ref_ss_iku_programkerja::findOrFail($id);

        //delete post
        $IKU->delete();

        //redirect to index
        return redirect('admin-iku')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
