<?php

namespace App\Http\Controllers;

use App\Models\Fungsi;
use Illuminate\Support\Facades\Hash;
use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Models\RefGolonganPangkat;
use App\Models\Karyawan;

class PegawaiController extends Controller
{
        // Tampilkan daftar golongan
    public function indexGolongan()
    {
        $golongans = RefGolonganPangkat::get();
        return view('admin.referensi.ref_golongan', compact('golongans'));
    }

    // Simpan data baru atau update data lama
public function saveGolongan(Request $request, $id = null): RedirectResponse
{
    $request->validate([
        'golongan' => 'required|string|max:255',
        'pangkat' => 'required|string|max:255',
    ]);

    RefGolonganPangkat::updateOrCreate(
        ['id_ref_golongan_pangkat' => $id], // gunakan primary key baru
        [
            'golongan' => $request->golongan,
            'pangkat' => $request->pangkat,
        ]
    );

    $message = $id ? 'Data Golongan Berhasil Diupdate!' : 'Data Golongan Berhasil Disimpan!';
    return redirect()->route('ref-golongan')->with('success', $message);
}



    // Hapus data golongan berdasarkan id
    public function destroyGolongan($id): RedirectResponse
    {
        $golongan = RefGolonganPangkat::findOrFail($id);
        $golongan->delete();

        return redirect()->route('ref-golongan')->with('success', 'Data Golongan Berhasil Dihapus!');
    }
    // function index untuk get all data
    public function index()
    {
        $ref_golongan_pangkats = RefGolonganPangkat::all();

        $pegawais = DB::table('pegawais')
            ->JOIN('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->JOIN('fungsis', 'pegawais.fungsi_id', '=', 'fungsis.id')
            ->SELECT('pegawais.*', 'fungsis.nama_fungsi as pokja')
            ->get();
        $data_bank = DB::table('ref_bank')
            ->get();

        return view(
            'admin.kelola_user.pegawai',
            [
                'title' => 'Data Pegawai LLDIKTI',
                'data_bank' => $data_bank,
                'pegawais' => $pegawais,
                'jabatans' => jabatan::all(),
                'fungsis' => fungsi::all(),
                'ref_golongan_pangkats' => $ref_golongan_pangkats,
            ]
        );
    }
    // function store untuk proses data
    public function save(Request $request, $id = null): RedirectResponse
    {
        $request->validate([
            'NIP_NIK' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            // validasi lainnya
        ]);

        $golongan = RefGolonganPangkat::find($request->id_golongan);

        // Ambil password lama jika edit
        $passwordPegawai = null;
        if ($id) {
            $oldPegawai = Pegawai::find($id);
            $passwordPegawai = $oldPegawai ? $oldPegawai->password : null;
        }

        // Tentukan password yang akan disimpan
        $passwordToSave = $request->filled('password') ? Hash::make($request->password) : $passwordPegawai;

        $pegawai = Pegawai::updateOrCreate(
            ['id' => $id],
            [
                'NIP_NIK' => $request->NIP_NIK,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'status' => $request->status,
                'golongan' => $golongan ? $golongan->golongan : null,
                'pangkat' => $golongan ? $golongan->pangkat : null,
                'no_telp' => $request->no_telp,
                'email' => $request->email,
                'jabatan_id' => $request->jabatan_id,
                'fungsi_id' => $request->fungsi_id,
                'npwp' => $request->npwp,
                'bank' => $request->bank,
                'no_rekening' => $request->no_rekening,
                'nama_rekening' => $request->nama_rekening,
                'is_aktif' => $request->is_aktif,
                'is_dinas' => $request->is_dinas ?? 1,
                'password' => $passwordToSave,
            ]
        );

        // Sama untuk Karyawan
        $passwordKaryawan = null;
        if ($id) {
            $oldKaryawan = Karyawan::where('NIP_NIK', $request->NIP_NIK)->first();
            $passwordKaryawan = $oldKaryawan ? $oldKaryawan->password : null;
        }

        $passwordToSaveKaryawan = $request->filled('password') ? Hash::make($request->password) : $passwordKaryawan;

        $karyawan = Karyawan::updateOrCreate(
            ['NIP_NIK' => $request->NIP_NIK],
            [
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'status' => $request->status,
                'id_ref_golongan_pangkat' => $request->id_golongan,
                'no_telp' => $request->no_telp,
                'email' => $request->email,
                'jabatan_id' => $request->jabatan_id,
                'fungsi_id' => $request->fungsi_id,
                'npwp' => $request->npwp,
                'is_aktif' => $request->is_aktif,
                'is_dinas' => $request->is_dinas ?? 1,
                'password' => $passwordToSaveKaryawan,
            ]
        );

        // Simpan rekening jika ada minimal 1 data valid
        $bank = ($request->bank && $request->bank !== '-') ? $request->bank : null;
        $noRekening = ($request->no_rekening && $request->no_rekening !== '-') ? $request->no_rekening : null;
        $namaRekening = ($request->nama_rekening && $request->nama_rekening !== '-') ? $request->nama_rekening : null;

        if ($bank || $noRekening) {
            $karyawan->rekening()->updateOrCreate(
                ['id_karyawan' => $karyawan->id_karyawan],
                [
                    'bank' => $bank,
                    'no_rekening' => $noRekening,
                ]
            );
        }

        $message = $id ? 'Data Berhasil Diupdate!' : 'Data Berhasil Disimpan!';

        return redirect('admin-pegawai')->with(['success' => $message]);
    }


    public function destroy($id): RedirectResponse
    {
        // Cari data pegawai berdasarkan id
        $pegawai = Pegawai::findOrFail($id);

        // Ambil NIP_NIK dari pegawai tersebut
        $nipNik = $pegawai->NIP_NIK;

        // Hapus data pegawai
        $pegawai->delete();

        // Hapus data karyawan yang NIP_NIK-nya sama
        Karyawan::where('NIP_NIK', $nipNik)->delete();

        // Redirect dengan pesan sukses
        return redirect('admin-pegawai')->with(['success' => 'Data Berhasil Dihapus!']);
    }
};
