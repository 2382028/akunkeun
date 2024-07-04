<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Models\Akun_x_rkakl;

class AkunController extends Controller
{
    // function index untuk tampilkan data
    public function index()
    {
        // $nominals = Akun::select('nominal')->all();
        // $nominals = DB::table('akuns')->select('nominal')->get();
        return view('admin.referensi.rkakl.rkakl_akun', [
            'title' => 'Akun',
            'akuns' => Akun::all(),
        ]);
    }

    // function create untuk tambah data
    public function create(): View
    {
        return view('admin.referensi.rkakl.rkakl_akun', [
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

    // edit find by id
    public function edit(string $id): View
    {
        return view('admin.referensi.rkakl.edit_rkakl_akun', [
            'title' => 'Akun',
            'akun' => Akun::find($id),
        ]);
    }

    // update data by id
    public function update(Request $request, $id): RedirectResponse
    {
        $akun = Akun::findOrFail($id);
        $akun->update([
            'kode_akun' => $request->kode_akun,
            'uraian' => $request->uraian,
            'nominal' => $request->nominal,
            'created_at' => old(),
            'updated_at' => now()
        ]);


        return redirect()->route('admin-rkakl_akun.index')->with(['success' => 'Data Berhasil Diupdate!']);
    }

    // function destroy untuk hapus data by id
    public function destroy($id): RedirectResponse
    {
        // Get the Akun record by id
        $akun = Akun::findOrFail($id);

        // Get the associated akun_x_rkakls record(s)
        $akunXrkakls = Akun_x_rkakl::where('akun_id', $akun->id)->get();

        // Delete the associated akun_x_rkakls records
        foreach ($akunXrkakls as $akunXrkakl) {
            $akunXrkakl->delete();
        }

        // Delete the Akun record
        $akun->delete();

        // Redirect to index
        return redirect('admin-rkakl_akun')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
