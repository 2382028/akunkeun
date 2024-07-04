<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Ref_rkakl_satker;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class RkaklSatkerController extends Controller
{
    // function index untuk tampilkan data
    public function index()
    {
        $ref_satker = DB::table('ref_rkakl_satkers')
        ->where('ref_rkakl_satkers.versi_id', session('versi'))
        ->get();
        return view('admin.referensi.rkakl.rkakl_satker', [
            'title' => 'Rkakl Satker',
            'rkaklsatkers' => $ref_satker
        ]);
    }

    // function create untuk tambah data
    public function create(): View
    {
        return view('admin.referensi.rkakl.rkakl_satker', ['title' => 'Rkakl Satker',]);
    }

    // function store untuk proses data
    public function store(Request $request): RedirectResponse
    {

        DB::table('ref_rkakl_satkers')->insertOrIgnore([
            'kode_satker' => $request->kode_satker,
            'satker' => $request->satker,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('admin-rkakl_satker')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    // function edit untuk find edit data by id
    public function edit(string $id): View
    {
        // get admin by id
        return view('admin.referensi.rkakl.edit_rkakl_satker', [
            'rkaklsatker' => Ref_rkakl_satker::findOrFail($id),
            'title' => 'Rkakl Satker',
        ]);
    }

    // function update untuk update data by id
    public function update(Request $request, $id): RedirectResponse
    {
        $rkaklsatker = Ref_rkakl_satker::findOrFail($id);
        $rkaklsatker->update([
            'kode_satker' => $request->kode_satker,
            'satker' => $request->satker,
            'created_at' => old(),
            'updated_at' => now()
        ]);


        return redirect()->route('admin-rkakl_satker.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    // function destroy untuk hapus data by id
    public function destroy($id): RedirectResponse
    {
        // get by id
        $rkaklsatker = Ref_rkakl_satker::findOrFail($id);

        //delete post
        $rkaklsatker->delete();

        //redirect to index
        return redirect('admin-rkakl_satker')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
