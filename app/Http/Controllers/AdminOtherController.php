<?php

namespace App\Http\Controllers;

use App\Models\Dokumen_permohonan;
use App\Models\Kebutuhan;
use App\Models\Komponen_diperlukan;
use App\Models\Laporan_perjadinkegiatan;
use App\Models\Keuangan_perjadinkegiatan;
use App\Models\Keuangan_perjadinlangsung;
use App\Models\Fasilitas;
use App\Models\Data_perjadinkegiatan;
use App\Models\Ref_sbm;
use App\Models\Info_perjadinlangsung;
use App\Models\Dokumen;
use App\Models\Versi;
use App\Models\JenisProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use App\Models\Ppn;
use App\Models\SuratPemeliharaan;
use App\Models\RefKodeLayananSurat;
use App\Models\Ref_rkakl_satker;
use App\Models\RefKopSurat;

class AdminOtherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexKopSurat()
    {
        $data = RefKopSurat::where('pemilik', 0)->get();
        return view('admin.referensi.ref_kop_surat', compact('data'));
    }

    public function storeKopSurat(Request $request)
    {
        $request->validate([
            'nama_kop' => 'required',
            'url_kop' => 'required|file|mimes:pdf,jpg,jpeg,png'
        ]);
        $file = $request->file('url_kop');
        $filename = 'kop_' . now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('dokumen', $filename, 'public');
        RefKopSurat::create([
            'nama_kop' => $request->nama_kop,
            'pemilik' => 0,
            'url_kop' => $path
        ]);
        return back()->with('success', 'Kop surat ditambahkan.');
    }


    public function editKopSurat($id)
    {
        return RefKopSurat::findOrFail($id);
    }

    public function updateKopSurat(Request $request, $id)
    {
        $kop = RefKopSurat::findOrFail($id);
        $kop->nama_kop = $request->nama_kop;
        if ($request->hasFile('url_kop')) {
            $file = $request->file('url_kop');
            $filename = 'kop_' . now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('dokumen', $filename, 'public');
            $kop->url_kop = $path;
        }
        $kop->save();
        return back()->with('success', 'Kop surat berhasil diupdate.');
    }
    public function aktifkanKop($id)
    {
        // Set semua jadi tidak aktif
        RefKopSurat::query()->update(['is_aktif' => 0]);
        // Aktifkan yang dipilih
        RefKopSurat::where('id', $id)->update(['is_aktif' => 1]);
        return redirect()->back()->with('success', 'Kop surat berhasil diaktifkan.');
    }
    public function destroyKopSurat($id)
    {
        RefKopSurat::destroy($id);
        return back()->with('success', 'Kop surat dihapus.');
    }

    public function getDokumen($filename)
    {
        $path = Storage::disk('public')->path('dokumen/' . $filename);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $mime = mime_content_type($path); // Deteksi tipe file otomatis

        return response()->file($path, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'public, max-age=0',
            'Pragma' => 'public',
        ]);
    }
    public function indexKodeLayanan()
    {
        $data = RefKodeLayananSurat::all();
        return view('admin.referensi.ref_kode_layanan', compact('data'));
    }

    public function storeKodeLayanan(Request $request)
    {
        RefKodeLayananSurat::create($request->only('kode_layanan', 'deskripsi_kode_layanan'));

        return redirect()->back()->with('success', 'Kode layanan berhasil ditambahkan.');
    }

    public function updateKodeLayanan(Request $request, $id)
    {
        $layanan = RefKodeLayananSurat::findOrFail($id);
        $layanan->update($request->only('kode_layanan', 'deskripsi_kode_layanan'));

        return redirect()->back()->with('success', 'Kode layanan berhasil diperbarui.');
    }
    public function destroyKodeLayanan($id)
    {
        $layanan = RefKodeLayananSurat::findOrFail($id);
        $layanan->delete();

        return redirect()->back()->with('success', 'Kode layanan berhasil dihapus.');
    }
    public function index()
    {
        $tab = request('tab', 'versi');
        $ppn = Ppn::first(); // hanya ada 1 row

        return view('admin.other.pengaturan', [
            'title' => 'Pengaturan',
            'activeTab' => $tab,
            'versis' => Versi::all(),
            'ppn' => $ppn,
        ]);
    }
    public function simpanPpn(Request $request)
    {
        $request->validate([
            'nilai_ppn' => 'required|integer|min:0|max:100',
        ]);

        $ppn = Ppn::first();

        if ($ppn) {
            $ppn->update(['nilai_ppn' => $request->nilai_ppn]);
        } else {
            Ppn::create(['nilai_ppn' => $request->nilai_ppn]);
        }

        return back()->with('success', 'Nilai PPN berhasil disimpan.');
    }

    public function indexJenisProgram()
    {
        $jenisProgram = DB::table('jenis_program')
                        ->get();

        return view('admin.referensi.jenis_program', [
            'title' => 'Jenis Program',
            'jenisPrograms' => $jenisProgram
        ]);
    }

    public function storejenisProgram(Request $request)
    {
        db::table('jenis_program')->insertOrIgnore([
            'nama_program' => $request->jenisProgram,
            'status_program' => 'non-aktif',
        ]);

        return redirect()->route('jenis_program')->with('success', 'Versi Telah Ditambahkan');
    }

    public function setjenisProgram(Request $request)
    {
        $status = DB::table('jenis_program')
            ->select('jenis_program.status_program')
                ->where('id', $request->getIdjenisProgram)
                ->first();

        if (($request->conf == "Ya, Saya yakin akan merubah Jenis Program") && ($status->status_program == 'aktif')) {
            DB::table('jenis_program')
            ->where('status_program', 'aktif')
            ->where('id', $request->getIdjenisProgram)
            ->update([
                'status_program' => 'non-aktif',
            ]);

            return redirect()->route('jenis_program')->with('success', 'Jenis Program Telah Diaktifkan');
        } elseif (($request->conf == "Ya, Saya yakin akan merubah Jenis Program") && ($status->status_program == 'non-aktif')) {

            DB::table('jenis_program')
                ->where('id', $request->getIdjenisProgram)
                ->update([
                    'status_program' => 'aktif',
                ]);

            return redirect()->route('jenis_program')->with('success', 'Jenis Program Telah Diaktifkan');

        } else {
            return redirect()->route('jenis_program')->with('success', 'Periksa kembali tulisan konfirmasi untuk ubah Jenis Program, karena ubah Jenis Program adalah hal yang fatal!'); # code...
        }
    }

    public function deleteJenisProgram(Request $request)
    {
        if (($request->conf == "Ya, Saya yakin akan menghapus Jenis Program")) {
            DB::table('jenis_program')
            ->where('id', $request->getIdjenisProgramDelete)
            ->delete();

            return redirect()->route('jenis_program')->with('success', 'Penandatangan Telah Dihapus');
        } else {
            return redirect()->route('jenis_program')->with('success', 'Periksa kembali tulisan konfirmasi untuk ubah Jenis Program, karena ubah Jenis Program adalah hal yang fatal!'); # code...
        }
    }
      
    public function indexRefFasilitas()
    {
        $refFasilitas = DB::table('ref_fasilitas')
                        ->get();

        return view('admin.referensi.ref_fasilitas', [
            'title' => 'Referensi Fasilitas',
            'refFasilitas' => $refFasilitas
        ]);
    }

    public function storeRefFasilitas(Request $request)
    {
        // dd($request);
        db::table('ref_fasilitas')->insertOrIgnore([
            'nama_fasilitas' => $request->refFasilitas,
            'satuan' => $request->satuanFasilitas,
            'terikat_pelaksana' => $request->terikatPelaksana,
            'status' => 'non-aktif',
        ]);

        return redirect()->route('ref_fasilitas')->with('success', 'Fasilitas Telah Ditambahkan');
    }

    public function setRefFasilitas(Request $request)
    {
        // dd($request);
        db::table('ref_fasilitas')
        ->where('id',$request->idFasilitas_edit)
        ->update([
            'nama_fasilitas' => $request->nama_fasilitas_edit,
            'satuan' => $request->satuan_edit,
            'terikat_pelaksana' => $request->terikatPelaksana_edit,
            'status' => $request->status_edit,
        ]);

        return redirect()->route('ref_fasilitas')->with('success', 'Data Fasilitas Berhasil Disesuaikan');
    }

    public function deleteRefFasilitas(Request $request)
    {
        if (($request->conf == "Ya, Saya yakin akan menghapus Data Fasilitas")) {
            DB::table('ref_fasilitas')
            ->where('id', $request->getIdRefFasilitasDelete)
            ->delete();

            return redirect()->route('ref_fasilitas')->with('success', 'Data Fasilitas Telah Dihapus');
        } else {
            return redirect()->route('ref_fasilitas')->with('success', 'Periksa kembali tulisan konfirmasi untuk ubah Data Fasilitas, karena ubah Data Fasilitas adalah hal yang berisiko!'); # code...
        }
    }
      

    public function indexPenandatangan()
    {
        $penandatangans = DB::table('ref_penandatangans')
                        ->leftJoin('pegawais', 'ref_penandatangans.pegawai_id', '=', 'pegawais.id')
                        ->select('ref_penandatangans.id','ref_penandatangans.posisi_penandatangan','pegawais.NIP_NIK','pegawais.nama_lengkap','ref_penandatangans.status_penandatangan')
                        ->get();

        $pegawais = DB::table('pegawais')
                    ->ORDERBY('pegawais.nama_lengkap','ASC')
                    ->get();

        return view('admin.referensi.ref_penandatangan', [
            'title' => 'Ref Penandatangan',
            'penandatangans' => $penandatangans,
            'pegawais' => $pegawais
        ]);
    }

    public function storePenandatangan(Request $request)
    {
        $versi = Versi::where('status', 'aktif')->get();

        db::table('ref_penandatangans')->insertOrIgnore([
            'pegawai_id' => $request->penandatangan,
            'posisi_penandatangan' => $request->posisi_penandatangan,
            'versi_id' => $versi[0]->id,
            'status_penandatangan' => 'non-aktif',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),

        ]);

        return redirect()->route('ref_penandatangan')->with('success', 'Penandatangan Telah Ditambahkan');
    }

    public function setPenandatangan(Request $request)
    {
        $status = DB::table('ref_penandatangans')
            ->select('ref_penandatangans.status_penandatangan')
                ->where('id', $request->getIdPenandatangan)
                ->first();

        if (($request->conf == "Ya, Saya yakin akan merubah status Penandatangan") && ($status->status_penandatangan == 'aktif')) {
            DB::table('ref_penandatangans')
            ->where('status_penandatangan', 'aktif')
            ->where('id', $request->getIdPenandatangan)
            ->update([
                'status_penandatangan' => 'non-aktif',
            ]);

            return redirect()->route('ref_penandatangan')->with('success', 'Penandatangan Telah Diaktifkan');
        } elseif (($request->conf == "Ya, Saya yakin akan merubah status Penandatangan") && ($status->status_penandatangan == 'non-aktif')) {

            DB::table('ref_penandatangans')
                ->where('id', $request->getIdPenandatangan)
                ->update([
                    'status_penandatangan' => 'aktif',
                ]);

            return redirect()->route('ref_penandatangan')->with('success', 'Penandatangan Telah Diaktifkan');

        } else {
            return redirect()->route('ref_penandatangan')->with('success', 'Periksa kembali tulisan konfirmasi untuk ubah Penandatangan, karena ubah Penandatangan adalah hal yang fatal!'); # code...
        }
    }

    public function deletePenandatangan(Request $request)
    {
        if (($request->conf == "Ya, Saya yakin akan menghapus Penandatangan")) {
            DB::table('ref_penandatangans')
            ->where('id', $request->getIdPenandatanganDelete)
            ->delete();

            return redirect()->route('ref_penandatangan')->with('success', 'Penandatangan Telah Dihapus');
        } else {
            return redirect()->route('ref_penandatangan')->with('success', 'Periksa kembali tulisan konfirmasi untuk ubah Jenis Program, karena ubah Jenis Program adalah hal yang fatal!'); # code...
        }
    }

    public function indexSatuan()
    {
        $satuans = DB::table('ref_satuans')
                        ->get();

        return view('admin.referensi.ref_satuan', [
            'title' => 'Ref Satuan',
            'satuans' => $satuans
        ]);
    }

    public function storeSatuan(Request $request)
    {
        db::table('ref_satuans')->insertOrIgnore([
            'kode' => $request->kode_satuan,
            'satuan' => $request->nama_satuan,
            'status' => 'non-aktif',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        return redirect()->route('ref_satuan')->with('success', 'Satuan Telah Ditambahkan');
    }

    public function setSatuan(Request $request)
    {
        $status = DB::table('ref_satuans')
            ->select('ref_satuans.status')
                ->where('id', $request->getIdSatuan)
                ->first();

        if (($request->conf == "Ya, Saya yakin akan merubah status Satuan") && ($status->status == 'aktif')) {
            DB::table('ref_satuans')
            ->where('status', 'aktif')
            ->where('id', $request->getIdSatuan)
            ->update([
                'status' => 'non-aktif',
            ]);

            return redirect()->route('ref_satuan')->with('success', 'Satuan Telah Diaktifkan');
        } elseif (($request->conf == "Ya, Saya yakin akan merubah status Satuan") && ($status->status == 'non-aktif')) {

            DB::table('ref_satuans')
                ->where('id', $request->getIdSatuan)
                ->update([
                    'status' => 'aktif',
                ]);

            return redirect()->route('ref_satuan')->with('success', 'Satuan Telah Diaktifkan');

        } else {
            return redirect()->route('ref_satuan')->with('success', 'Periksa kembali tulisan konfirmasi untuk ubah Jenis Program, karena ubah Jenis Program adalah hal yang fatal!'); # code...
        }
    }

    public function deleteSatuan(Request $request)
    {
        if (($request->conf == "Ya, Saya yakin akan menghapus Satuan")) {
            DB::table('ref_satuans')
            ->where('id', $request->getIdSatuanDelete)
            ->delete();

            return redirect()->route('ref_satuan')->with('success', 'Satuan Telah Dihapus');
        } else {
            return redirect()->route('ref_satuan')->with('success', 'Periksa kembali tulisan konfirmasi untuk ubah Jenis Program, karena ubah Jenis Program adalah hal yang fatal!'); # code...
        }
    }
    public function indexDataPajak()
    {
        $dataPajaks = DB::table('ref_data_pajak')
                        ->get();

        return view('admin.referensi.ref_data_pajak', [
            'title' => 'Ref Data Pajak',
            'dataPajaks' => $dataPajaks
        ]);
    }

    public function setDataPajak(Request $request)
    {
        $tarifPajak = $request->tarifPajak_data_pajak_edit / 100;
        // dd($tarifPajak,$request);

        db::table('ref_data_pajak')
            ->where('no',$request->noPajak_edit)
            ->update([
                'status' => $request->status_data_pajak_edit,
                'golongan' => $request->golongan_data_pajak_edit,
                'tarif_pajak' => $tarifPajak,
        ]);

        return redirect()->route('ref_data_pajak')->with('success', 'Data Pajak Telah Diubah!');
    }

    public function storeDataPajak(Request $request)
    {
        $tarifPajak = $request->tarifPajak_data_pajak / 100;
        // dd($tarifPajak,$request);
        db::table('ref_data_pajak')->insertOrIgnore([
            'status' => $request->status_data_pajak,
            'golongan' => $request->golongan_data_pajak,
            'tarif_pajak' => $tarifPajak,
        ]);

        return redirect()->route('ref_data_pajak')->with('success', 'Data Pajak Telah Ditambahkan');
    }

    public function deleteDataPajak(Request $request)
    {
        // dd($request);
        if (($request->conf == "Ya, Saya yakin akan menghapus Data Pajak")) {
            DB::table('ref_data_pajak')
            ->where('no', $request->getIdSatuanDelete)
            ->delete();

            return redirect()->route('ref_data_pajak')->with('success', 'Data Pajak Telah Dihapus');
        } else {
            return redirect()->route('ref_data_pajak')->with('error', 'Gagal Menghapus Data Pajak, Periksa kembali tulisan konfirmasi untuk menghapus Data Pajak'); # code...
        }
    }

    public function indexRefBank()
    {
        $banks = DB::table('ref_bank')
                        ->get();

        return view('admin.referensi.ref_bank', [
            'title' => 'Data Bank',
            'banks' => $banks
        ]);
    }

    public function storeRefBank(Request $request)
    {
        $existingBank = DB::table('ref_bank')
        ->where('kode_bank', $request->kode_bank)
        ->orWhere('nama_bank', $request->nama_bank)
        ->first();


        // dd($existingBank,$request);

        if (!$existingBank) {
            DB::table('ref_bank')->insertOrIgnore([
            'kode_bank' => $request->kode_bank,
            'nama_bank' => $request->nama_bank,
            ]);
            return redirect()->route('ref_bank')->with('success', 'Data Bank Telah Ditambahkan');
        } else {
            return redirect()->route('ref_bank')->with('error', 'Data Bank Telah Tersedia');
        }
    }

    public function deleteRefBank(Request $request)
    {
        // dd($request);
        if (($request->conf == "Ya, Saya yakin akan menghapus Data Bank")) {
            DB::table('ref_bank')
            ->where('id', $request->getIdBankDelete)
            ->delete();

            return redirect()->route('ref_bank')->with('success', 'Data Bank Berhasil Dihapus');
        } else {
            return redirect()->route('ref_bank')->with('error', 'Gagal menghapus Data Bank, Periksa kembali tulisan konfirmasi untuk menghapus Data Bank'); # code...
        }
    }

    public function indexRefJabatan()
    {
        $jabatans = DB::table('jabatans')
                        ->get();

        return view('admin.referensi.ref_jabatan', [
            'title' => 'Data Jabatan',
            'jabatans' => $jabatans
        ]);
    }

    public function storeRefJabatan(Request $request)
    {
        $existingJabatan = DB::table('jabatans')
        ->where('nama_jabatan', $request->nama_jabatan)
        ->first();


        // dd($existingJabatan,$request);

        if (!$existingJabatan) {
            DB::table('jabatans')->insertOrIgnore([
                'nama_jabatan' => $request->nama_jabatan,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
            return redirect()->route('ref_jabatan')->with('success', 'Data Jabatan Telah Ditambahkan');
        } else {
            return redirect()->route('ref_jabatan')->with('error', 'Data Jabatan Telah Tersedia');
        }
    }

    public function setRefJabatan(Request $request)
    {
        // dd($request);

        db::table('jabatans')
            ->where('id',$request->idJabatan_edit)
            ->update([
                'nama_jabatan' => $request->nama_jabatan_edit,
                'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        return redirect()->route('ref_jabatan')->with('success', 'Data Jabatan Telah Diubah!');
    }

    public function indexRefPokja()
    {
        $fungsis = DB::table('fungsis')
                        ->get();

        return view('admin.referensi.ref_pokja', [
            'title' => 'Data Pokja',
            'fungsis' => $fungsis
        ]);
    }

    public function storeRefPokja(Request $request)
    {
        $existingFungsi = DB::table('fungsis')
        ->where('nama_fungsi', $request->nama_fungsi)
        ->first();


        // dd($existingFungsi,$request);

        if (!$existingFungsi) {
            DB::table('fungsis')->insertOrIgnore([
                'nama_fungsi' => $request->nama_fungsi,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
            return redirect()->route('ref_pokja')->with('success', 'Data Pokja/Fungsi Telah Ditambahkan');
        } else {
            return redirect()->route('ref_pokja')->with('error', 'Data Pokja/Fungsi Telah Tersedia');
        }
    }

    public function setRefPokja(Request $request)
    {
        // dd($request);

        db::table('fungsis')
            ->where('id',$request->idPokja_edit)
            ->update([
                'nama_fungsi' => $request->nama_pokja_edit,
                'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        return redirect()->route('ref_pokja')->with('success', 'Data Pokja/Fungsi Telah Diubah!');
    }

    public function indexRefPangkat()
    {
        $pangkats = DB::table('ref_pangkats')
                        ->get();
        $golongans = DB::table('ref_data_pajak')
                        ->where('status','PNS')
                        ->orWhere('status','default')
                        ->get();

        return view('admin.referensi.ref_pangkat', [
            'title' => 'Data Pangkat',
            'pangkats' => $pangkats,
            'golongans' => $golongans
        ]);
    }

    public function storeRefPangkat(Request $request)
    {
        $existingPangkat = DB::table('ref_pangkats')
            ->where('golongan', $request->golongan)
            ->where('nama_pangkat', $request->nama_pangkat)
            ->first();


        // dd($existingPangkat,$request);

        if (!$existingPangkat) {
            DB::table('ref_pangkats')->insertOrIgnore([
                'golongan' => $request->golongan,
                'nama_pangkat' => $request->nama_pangkat,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
            return redirect()->route('ref_pangkat')->with('success', 'Data Pangkat Telah Ditambahkan');
        } else {
            return redirect()->route('ref_pangkat')->with('error', 'Data Pangkat Telah Tersedia');
        }
    }

    public function setRefPangkat(Request $request)
    {
        // dd($request);

        db::table('ref_pangkats')
            ->where('id',$request->idPangkat_edit)
            ->update([
                'golongan' => $request->golongan_edit,
                'nama_pangkat' => $request->nama_pangkat_edit,
                'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        return redirect()->route('ref_pangkat')->with('success', 'Data Pangkat Telah Diubah!');
    }

    public function deleteRefPangkat(Request $request)
    {
        // dd($request);
        if (($request->conf == "Ya, Saya yakin akan menghapus Data Perangkat")) {
            DB::table('ref_pangkats')
            ->where('id', $request->getIdPangkatDelete)
            ->delete();

            return redirect()->route('ref_pangkat')->with('success', 'Data Pangkat Berhasil Dihapus');
        } else {
            return redirect()->route('ref_pangkat')->with('error', 'Gagal menghapus Data Pangkat, Periksa kembali tulisan konfirmasi untuk menghapus Data Perangkat'); # code...
        }
    }

    public function indexMonitoringUsulan()
    {
        $kegiatans = DB::table('data_perjadinkegiatans')
                        ->get();

        return view('admin.other.monitoring-usulan', [
            'title' => 'Jenis Program',
            'kegiatan' => $kegiatans
        ]);
    }

    public function isiPenggunaan()
    {
        try {
            // Reset kolom penggunaan ke 0
            DB::table('akuns')
                ->where('versi_id', session('versi'))
                ->update(['penggunaan' => 0]);

            // Ambil semua akun_id dari akun_x_rkakls
            $akunXrkakls = DB::table('akun_x_rkakls')
                ->select('id', 'akun_id')
                ->get();

            foreach ($akunXrkakls as $akun) {
                // Hitung total berdasarkan keuangan_perjadinlangsungs
                $totalPenggunaanPerjadin = DB::table('keuangan_perjadinlangsungs')
                            ->join('info_perjadinlangsungs', 'keuangan_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
                            ->where('keuangan_perjadinlangsungs.akun_x_rkakl', $akun->id)
                            ->where('info_perjadinlangsungs.is_acceptBend', 'selesai')
                            ->select(DB::raw('SUM(COALESCE(keuangan_perjadinlangsungs.jumlah_harga, 0)) AS totalPenggunaan'))
                            ->value('totalPenggunaan');

                // Update kolom penggunaan di tabel akuns
                DB::table('akuns')
                ->where('id', $akun->akun_id)
                ->where('versi_id', session('versi'))
                ->increment('penggunaan', intval($totalPenggunaanPerjadin));
            }

            foreach ($akunXrkakls as $akun) {
                // Hitung total berdasarkan keuangan_perjadinlangsungs
                $totalPenggunaanKegiatan = DB::table('keuangan_perjadinkegiatans')
                            ->join('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
                            ->where('keuangan_perjadinkegiatans.akun_x_rkakl', $akun->id)
                            ->where('data_perjadinkegiatans.is_acceptBend', 'selesai')
                            ->select(DB::raw('SUM(COALESCE(keuangan_perjadinkegiatans.jumlah_honorarium, 0) + 
                                                    COALESCE(keuangan_perjadinkegiatans.nominal_perjadin, 0) + 
                                                    COALESCE(keuangan_perjadinkegiatans.harga, 0)) AS totalPenggunaan'))
                            ->value('totalPenggunaan');


                // dd($totalPenggunaan);
                // Update kolom penggunaan di tabel akuns
                DB::table('akuns')
                ->where('id', $akun->akun_id)
                ->where('versi_id', session('versi'))
                ->increment('penggunaan', intval($totalPenggunaanKegiatan));
            }

            foreach ($akunXrkakls as $akun) {
                // Hitung total berdasarkan keuangan_perjadinlangsungs
                $totalPenggunaanBMN = DB::table('permohonans')
                            ->where('akun_x_rkakl_id', $akun->id)
                            ->select(DB::raw('SUM(COALESCE(nominal, 0)) AS totalPenggunaan'))
                            ->value('totalPenggunaan');

                // dd($totalPenggunaan);
                // Update kolom penggunaan di tabel akuns
                DB::table('akuns')
                ->where('id', $akun->akun_id)
                ->where('versi_id', session('versi'))
                ->increment('penggunaan', intval($totalPenggunaanBMN));
            }

            return true; // Berhasil
        } catch (\Exception $e) {
            return false; // Gagal
        }
    }

    public function generatePenggunaan() {
        $status = $this->isiPenggunaan();

        if ($status) {
            return redirect()->route('monitoring-keuangan')->with('success', 'Penggunaan Berhasil Diupdate');
        } else {
            return redirect()->route('monitoring-keuangan')->with('error', 'Penggunaan Gagal Diupdate');
        }
    }

    public function monitoringKeuangan(Request $request)
    {
        $akuns = DB::table('akun_x_rkakls')
                ->select('akun_x_rkakls.*', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program', 'ref_rkakl_kegiatans.kode_kegiatan', 'ref_rkakl_outputs.kode_output', 'ref_rkakl_suboutputs.kode_sub_output','ref_rkakl_komponens.kode_komponen','ref_rkakl_sub_komponens.kode_sub_kegiatan','akuns.kode_akun','ref_rkakl_sub_komponens.nama_sub_kegiatan','akuns.uraian','akuns.nominal','akuns.penggunaan')
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

        $total = DB::table('akuns')
            ->select(
                DB::raw('SUM(COALESCE(nominal, 0)) AS nominal'),
                DB::raw('SUM(COALESCE(penggunaan, 0)) AS penggunaan'),
            )
            ->where('versi_id', session('versi'))
            ->get();



        return view('admin.other.monitoring-keuangan.monitoring-keuangan', [
            'title' => 'Data Administrator',
            'akuns' => $akuns,
            'total' => $total,
        ]);
    }
    public function monitoringKeuanganDetail(Request $request, $id)
{
    $tipe = $request->input('tipe', 'perjadin');

    $akuns = DB::table('akun_x_rkakls')
            ->select('akun_x_rkakls.*', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program', 'ref_rkakl_kegiatans.kode_kegiatan', 'ref_rkakl_outputs.kode_output', 'ref_rkakl_suboutputs.kode_sub_output','ref_rkakl_komponens.kode_komponen','ref_rkakl_sub_komponens.kode_sub_kegiatan','akuns.kode_akun','ref_rkakl_sub_komponens.nama_sub_kegiatan','akuns.uraian','akuns.nominal','akuns.penggunaan')
            ->join('akuns', 'akuns.id', '=', 'akun_x_rkakls.akun_id')
            ->join('ref_rkakl_sub_komponens', 'ref_rkakl_sub_komponens.id', '=', 'akun_x_rkakls.ref_sub_komponen_id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_komponens.id', '=', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_suboutputs.id', '=', 'ref_rkakl_komponens.ref_rkakl_suboutput_id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
            ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
                ->where('akun_x_rkakls.versi_id', session('versi'))
                ->where('akun_x_rkakls.id', $id)
                ->get();

     // Nonaktifkan ONLY_FULL_GROUP_BY
     DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");

     if ($tipe == 'semua') {
        $perjadin = DB::table('keuangan_perjadinlangsungs')
            ->leftJoin('info_perjadinlangsungs', 'keuangan_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select(
                DB::raw('info_perjadinlangsungs.id AS id'),
                'info_perjadinlangsungs.nama_kegiatan',
                'info_perjadinlangsungs.tgl_keberangkatan',
                'info_perjadinlangsungs.tgl_selesai',
                'info_perjadinlangsungs.kabupaten_kota',
                'info_perjadinlangsungs.provinsi',
                'info_perjadinlangsungs.kode_surat_tugas',
                'info_perjadinlangsungs.status_pengajuan_detail',
                DB::raw('SUM(COALESCE(keuangan_perjadinlangsungs.jumlah_harga, 0)) AS totalPenggunaan'),
                DB::raw('"perjadin" as tipe')
            )
            ->where('keuangan_perjadinlangsungs.akun_x_rkakl', $id)
            ->where('info_perjadinlangsungs.is_acceptBend', 'selesai')
            ->groupBy('info_perjadinlangsungs.id');
    
        $kegiatan = DB::table('keuangan_perjadinkegiatans')
            ->leftJoin('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
            ->select(
                DB::raw('data_perjadinkegiatans.id AS id'),
                'data_perjadinkegiatans.nama_kegiatan',
                'data_perjadinkegiatans.tgl_mulai AS tgl_keberangkatan',
                'data_perjadinkegiatans.tgl_selesai',
                'data_perjadinkegiatans.kab_kota AS kabupaten_kota',
                'data_perjadinkegiatans.provinsi',
                'data_perjadinkegiatans.kode_surat_tugas',
                'data_perjadinkegiatans.status_pengajuan_detail',
                DB::raw('SUM(COALESCE(jumlah_honorarium, 0) +
                                    COALESCE(nominal_perjadin, 0) +
                                    COALESCE(harga, 0)) AS totalPenggunaan'),
                DB::raw('"kegiatan" as tipe')
            )
            ->where('keuangan_perjadinkegiatans.akun_x_rkakl', $id)
            ->where('data_perjadinkegiatans.is_acceptBend', 'selesai')
            ->groupBy('data_perjadinkegiatans.id');

        // Gabungkan semua query dengan UNION
        $keuangans = $perjadin
            ->union($kegiatan)
            ->get();
    }  elseif ($tipe == 'perjadin') {
        $keuangans = DB::table('keuangan_perjadinlangsungs')
                ->leftjoin('info_perjadinlangsungs', 'keuangan_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
                ->select(
                    'info_perjadinlangsungs.*',
                    DB::raw('SUM(COALESCE(keuangan_perjadinlangsungs.jumlah_harga, 0)) AS totalPenggunaan'
                    ),
                    DB::raw('"perjadin" as tipe')
                )
                ->where('keuangan_perjadinlangsungs.akun_x_rkakl', $id)
                ->where('info_perjadinlangsungs.is_acceptBend', 'selesai')
                ->groupBy('info_perjadinlangsungs.id')
                ->get();

    } else if ($tipe == 'kegiatan') {
        $keuangans = DB::table('keuangan_perjadinkegiatans')
                ->leftjoin('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
                ->select(
                    'data_perjadinkegiatans.*',
                    'data_perjadinkegiatans.kab_kota AS kabupaten_kota',
                    'data_perjadinkegiatans.tgl_mulai AS tgl_keberangkatan',
                    DB::raw('SUM(COALESCE(jumlah_honorarium, 0) +
                                                COALESCE(nominal_perjadin, 0) +
                                                COALESCE(harga, 0)) AS totalPenggunaan'
                    ),
                    DB::raw('"kegiatan" as tipe')
                )
                ->where('keuangan_perjadinkegiatans.akun_x_rkakl', $id)
                ->where('data_perjadinkegiatans.is_acceptBend', 'selesai')
                ->groupBy('data_perjadinkegiatans.id')
                ->get();
    } else if ($tipe == 'bmn') {
        $keuangans = DB::table('permohonans')
                ->leftJoin('administrators', 'permohonans.admin', '=', 'administrators.id')
                ->leftJoin('assets', 'permohonans.asset_id', '=', 'assets.id')
                ->leftJoin('data_penanggungjawabs', 'permohonans.data_penanggungjawab_id', '=', 'data_penanggungjawabs.id')
                ->leftJoin('pegawais', 'data_penanggungjawabs.pegawai_id', '=', 'pegawais.id')
                ->leftJoin('kendaraans', 'permohonans.kendaraan_id', '=', 'kendaraans.id')
                ->select(
                    'permohonans.id as idService',
                    'permohonans.*',
                    DB::raw("
                        CASE
                            WHEN permohonans.admin IS NOT NULL THEN CONCAT('(Admin) ', administrators.username)
                            WHEN permohonans.data_penanggungjawab_id IS NOT NULL THEN pegawais.nama_lengkap
                            ELSE 'Tidak Diketahui'
                        END AS penanggungJawab
                    "),
                    DB::raw("
                        CASE
                            WHEN permohonans.kendaraan_id IS NULL
                            THEN CONCAT(assets.nama_barang, ' (', assets.nama_merek, ')')
                            ELSE CONCAT('[',kendaraans.no_polisi,'] ', kendaraans.merek)
                        END AS deskripsiAsset
                    "),
                    DB::raw('SUM(COALESCE(permohonans.nominal, 0)) AS totalPenggunaan'),
                    DB::raw('"bmn" as tipe')
                )
                ->where('permohonans.akun_x_rkakl_id', $id)
                ->groupBy('permohonans.id', 'administrators.username', 'pegawais.nama_lengkap', 'assets.nama_barang', 'assets.nama_merek', 'kendaraans.no_polisi', 'kendaraans.merek') // Pastikan grup sesuai dengan select
                ->get();


    } else {
        $akuns = DB::table('akun_x_rkakls')
                ->limit(0)
                ->get();
        $keuangans = NULL;
    }

    // Kembalikan ONLY_FULL_GROUP_BY ke default
    DB::statement("SET SESSION sql_mode=CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY')");

    // dd($keuangans);

    return view('admin.other.monitoring-keuangan.monitoring-keuangan-detail', [
        'title' => 'Data Administrator',
        'idAkun' => $id,
        'akuns' => $akuns,
        'keuangans' => $keuangans,
        'tipe' => $tipe,
        'isSemua' => ($tipe == 'semua'),
        'isPerjadin' => ($tipe == 'perjadin'),
        'isKegiatan' => ($tipe == 'kegiatan'),
        'isBMN' => ($tipe == 'bmn'),
    ]);
}

    public function monitoringKeuanganPerAkun(Request $request)
    {
        // dd($request->kode_induk_program, $request->kode_akun);

        $akuns = DB::table('akun_x_rkakls')
            ->select('akun_x_rkakls.*', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program', 'ref_rkakl_kegiatans.kode_kegiatan', 'ref_rkakl_outputs.kode_output', 'ref_rkakl_suboutputs.kode_sub_output','ref_rkakl_komponens.kode_komponen','ref_rkakl_sub_komponens.kode_sub_kegiatan','akuns.kode_akun','ref_rkakl_sub_komponens.nama_sub_kegiatan','akuns.uraian','akuns.nominal','akuns.penggunaan')
            ->join('akuns', 'akuns.id', '=', 'akun_x_rkakls.akun_id')
            ->join('ref_rkakl_sub_komponens', 'ref_rkakl_sub_komponens.id', '=', 'akun_x_rkakls.ref_sub_komponen_id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_komponens.id', '=', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_suboutputs.id', '=', 'ref_rkakl_komponens.ref_rkakl_suboutput_id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
            ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
            ->where('akun_x_rkakls.versi_id', session('versi'))
            ->where(DB::raw("CONCAT(ref_rkakl_satkers.kode_satker,'.', ref_rkakl_programs.kode_program)"), $request->kode_induk_program)
            ->where('akuns.kode_akun', $request->kode_akun)
            ->get();

        $total = DB::table('akun_x_rkakls')
                ->select(
                    DB::raw('SUM(COALESCE(akuns.nominal, 0)) AS nominal'),
                    DB::raw('SUM(COALESCE(akuns.penggunaan, 0)) AS penggunaan')
                )
                ->join('akuns', 'akuns.id', '=', 'akun_x_rkakls.akun_id')
                ->join('ref_rkakl_sub_komponens', 'ref_rkakl_sub_komponens.id', '=', 'akun_x_rkakls.ref_sub_komponen_id')
                ->join('ref_rkakl_komponens', 'ref_rkakl_komponens.id', '=', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id')
                ->join('ref_rkakl_suboutputs', 'ref_rkakl_suboutputs.id', '=', 'ref_rkakl_komponens.ref_rkakl_suboutput_id')
                ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
                ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
                ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
                ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
                ->where('akun_x_rkakls.versi_id', session('versi'))
                ->where(DB::raw("CONCAT(ref_rkakl_satkers.kode_satker,'.', ref_rkakl_programs.kode_program)"), $request->kode_induk_program)
                ->where('akuns.kode_akun', $request->kode_akun)
                ->first();

        $dataAkuns = DB::table('akun_x_rkakls')
                ->select('akun_x_rkakls.*', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program', 'ref_rkakl_kegiatans.kode_kegiatan', 'ref_rkakl_outputs.kode_output', 'ref_rkakl_suboutputs.kode_sub_output','ref_rkakl_komponens.kode_komponen','ref_rkakl_sub_komponens.kode_sub_kegiatan','akuns.kode_akun','ref_rkakl_sub_komponens.nama_sub_kegiatan','akuns.uraian','akuns.nominal','akuns.penggunaan')
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
                    
        $uraian = DB::table('akuns')
                 ->select('uraian')
                 ->where('kode_akun',$request->kode_akun )
                 ->first();
                 
        // dd($uraian->uraian);

        return view('admin.other.monitoring-keuangan.monitoring-keuangan-per-akun', [
            'title' => 'Monitoring Keuangan Per Akun',
            'akuns' => $akuns,
            'total' => $total,
            'kode_induk_program' => $request->kode_induk_program,
            'kode_akun' => $request->kode_akun,
            'uraian' => $uraian->uraian,
            'dataAkuns' => $dataAkuns,
        ]);
    }

    function getUniqueProgramsJson() {
        $uniquePrograms = [];

        $akuns = DB::table('akun_x_rkakls')
            ->select('akun_x_rkakls.*', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program',  'ref_rkakl_programs.program','ref_rkakl_kegiatans.kode_kegiatan', 'ref_rkakl_outputs.kode_output', 'ref_rkakl_suboutputs.kode_sub_output','ref_rkakl_komponens.kode_komponen','ref_rkakl_sub_komponens.kode_sub_kegiatan','akuns.kode_akun','ref_rkakl_sub_komponens.nama_sub_kegiatan','akuns.uraian','akuns.nominal','akuns.penggunaan')
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
    
        foreach ($akuns as $akunxrkakl) {
            $programKey = $akunxrkakl->kode_satker . '--' . $akunxrkakl->kode_program.'--'. $akunxrkakl->program;
    
            // Cek apakah programKey sudah ada di array uniquePrograms
            if (!in_array($programKey, $uniquePrograms)) {
                $uniquePrograms[] = $programKey;
            }
        }
    
        // Pisahkan kode_satker dan kode_program
        $result = [];
        foreach ($uniquePrograms as $programKey) {
            list($kode_satker, $kode_program, $nama_program) = explode('--', $programKey);
            $result[] = [
                'kode_satker' => $kode_satker,
                'kode_program' => $kode_program,
                'nama_program' => $nama_program,
            ];
        }
    
        // Kembalikan data dalam format JSON
        return response()->json($result);
    }

    function getUniqueAkunsOnlyJson($id_akun) {
        $result = '';

        $akuns = DB::table('akun_x_rkakls')
            ->select('akun_x_rkakls.*', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program',  'ref_rkakl_programs.program','ref_rkakl_kegiatans.kode_kegiatan', 'ref_rkakl_outputs.kode_output', 'ref_rkakl_suboutputs.kode_sub_output','ref_rkakl_komponens.kode_komponen','ref_rkakl_sub_komponens.kode_sub_kegiatan','akuns.kode_akun','ref_rkakl_sub_komponens.nama_sub_kegiatan','akuns.uraian','akuns.nominal','akuns.penggunaan')
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
    
        foreach ($akuns as $akunxrkakl) {
            if ($akunxrkakl->id == $id_akun) {
                // Jika akun_id sama dengan id_akun yang diberikan, ambil kode_akun
                $result = $akunxrkakl->kode_satker .'.'. $akunxrkakl->kode_program.'.'. $akunxrkakl->kode_kegiatan.'.'. $akunxrkakl->kode_output.'.'. $akunxrkakl->kode_sub_output.'.'. $akunxrkakl->kode_komponen.'.'. $akunxrkakl->kode_sub_kegiatan.'.'. $akunxrkakl->kode_akun;
                break; // Keluar dari loop setelah menemukan akun yang sesuai
            } else{ 
                $result = 'Tidak Ditemukan';

            }
        }
    
        // Kembalikan data dalam format JSON
        return response()->json($result);
    }

    function getUniqueKegiatansJson($kode_satker, $kode_program) {
        $uniqueKegiatans = [];

        $akuns = DB::table('akun_x_rkakls')
            ->select('akun_x_rkakls.*', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program', 'ref_rkakl_kegiatans.kode_kegiatan', 'ref_rkakl_kegiatans.nama_kegiatan', 'ref_rkakl_outputs.kode_output', 'ref_rkakl_suboutputs.kode_sub_output','ref_rkakl_komponens.kode_komponen','ref_rkakl_sub_komponens.kode_sub_kegiatan','akuns.kode_akun','ref_rkakl_sub_komponens.nama_sub_kegiatan','akuns.uraian','akuns.nominal','akuns.penggunaan')
            ->join('akuns', 'akuns.id', '=', 'akun_x_rkakls.akun_id')
            ->join('ref_rkakl_sub_komponens', 'ref_rkakl_sub_komponens.id', '=', 'akun_x_rkakls.ref_sub_komponen_id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_komponens.id', '=', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_suboutputs.id', '=', 'ref_rkakl_komponens.ref_rkakl_suboutput_id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
            ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
                ->where('ref_rkakl_satkers.kode_satker', $kode_satker)
                ->where('ref_rkakl_programs.kode_program', $kode_program)
                ->where('akun_x_rkakls.versi_id', session('versi'))
                ->get();

    
        foreach ($akuns as $akunxrkakl) {
            $kegiatanKey = $akunxrkakl->kode_kegiatan . '-0-' . $akunxrkakl->nama_kegiatan;
    
            // Cek apakah programKey sudah ada di array uniquePrograms
            if (!in_array($kegiatanKey, $uniqueKegiatans)) {
                $uniqueKegiatans[] = $kegiatanKey;
            }
        }

        // Pisahkan kode_satker dan kode_program
        $result = [];
        foreach ($uniqueKegiatans as $kegiatanKey) {
            list($kode_kegiatan, $nama_kegiatan) = explode('-0-', $kegiatanKey);
            $result[] = [
                'kode_kegiatan' => $kode_kegiatan,
                'nama_kegiatan' => $nama_kegiatan,
            ];
        }
    
        // Kembalikan data dalam format JSON
        return response()->json($result);
    }
    
    function getUniqueOutputsJson($kode_satker, $kode_program, $kode_kegiatan) {
        $kode_kegiatan = $kode_kegiatan == 'null' ? null : $kode_kegiatan;
        $uniqueOutputs = [];

        $query = DB::table('akun_x_rkakls')
            ->select(
                'akun_x_rkakls.*', 
                'ref_rkakl_satkers.kode_satker', 
                'ref_rkakl_programs.kode_program', 
                'ref_rkakl_kegiatans.kode_kegiatan', 
                'ref_rkakl_kegiatans.nama_kegiatan', 
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_outputs.nama_output', 
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'akuns.kode_akun',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'akuns.nominal',
                'akuns.penggunaan'
            )
            ->join('akuns', 'akuns.id', '=', 'akun_x_rkakls.akun_id')
            ->join('ref_rkakl_sub_komponens', 'ref_rkakl_sub_komponens.id', '=', 'akun_x_rkakls.ref_sub_komponen_id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_komponens.id', '=', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_suboutputs.id', '=', 'ref_rkakl_komponens.ref_rkakl_suboutput_id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
            ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
            ->where('ref_rkakl_satkers.kode_satker', $kode_satker)
            ->where('ref_rkakl_programs.kode_program', $kode_program)
            ->where('akun_x_rkakls.versi_id', session('versi'));

        // Tambahkan filter kode_kegiatan hanya jika tidak null
        if ($kode_kegiatan) {
            $query->where('ref_rkakl_kegiatans.kode_kegiatan', $kode_kegiatan);
        }

        $akuns = $query->get();
    
        foreach ($akuns as $akunxrkakl) {
            $outputKey = $akunxrkakl->kode_output . '-0-' . $akunxrkakl->nama_output;
    
            // Cek apakah programKey sudah ada di array uniquePrograms
            if (!in_array($outputKey, $uniqueOutputs)) {
                $uniqueOutputs[] = $outputKey;
            }
        }

        // Pisahkan kode_satker dan kode_program
        $result = [];
        foreach ($uniqueOutputs as $outputKey) {
            list($kode_output, $nama_output) = explode('-0-', $outputKey);
            $result[] = [
                'kode_output' => $kode_output,
                'nama_output' => $nama_output,
            ];
        }
    
        // Kembalikan data dalam format JSON
        return response()->json($result);
    }

    function getUniqueSubOutputsJson($kode_satker, $kode_program, $kode_kegiatan, $kode_output) {
        $kode_kegiatan = $kode_kegiatan == 'null' ? null : $kode_kegiatan;
        $kode_output = $kode_output == 'null' ? null : $kode_output;
        $uniqueSubOutputs = [];

        $query = DB::table('akun_x_rkakls')
            ->select(
                'akun_x_rkakls.*', 
                'ref_rkakl_satkers.kode_satker', 
                'ref_rkakl_programs.kode_program', 
                'ref_rkakl_kegiatans.kode_kegiatan', 
                'ref_rkakl_kegiatans.nama_kegiatan', 
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_outputs.nama_output', 
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_suboutputs.nama_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'akuns.kode_akun',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'akuns.nominal',
                'akuns.penggunaan'
            )
            ->join('akuns', 'akuns.id', '=', 'akun_x_rkakls.akun_id')
            ->join('ref_rkakl_sub_komponens', 'ref_rkakl_sub_komponens.id', '=', 'akun_x_rkakls.ref_sub_komponen_id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_komponens.id', '=', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_suboutputs.id', '=', 'ref_rkakl_komponens.ref_rkakl_suboutput_id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
            ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
            ->where('ref_rkakl_satkers.kode_satker', $kode_satker)
            ->where('ref_rkakl_programs.kode_program', $kode_program)
            ->where('akun_x_rkakls.versi_id', session('versi'));

        // Tambahkan filter kode_kegiatan hanya jika tidak null
        if ($kode_kegiatan) {
            $query->where('ref_rkakl_kegiatans.kode_kegiatan', $kode_kegiatan);
        }

        if ($kode_kegiatan) {
            $query->where('ref_rkakl_outputs.kode_output', $kode_output);
        }

        $akuns = $query->get();
    
        foreach ($akuns as $akunxrkakl) {
            $subOutputKey = $akunxrkakl->kode_sub_output . '-0-' . $akunxrkakl->nama_sub_output;
    
            // Cek apakah programKey sudah ada di array uniquePrograms
            if (!in_array($subOutputKey, $uniqueSubOutputs)) {
                $uniqueSubOutputs[] = $subOutputKey;
            }
        }

        // Pisahkan kode_satker dan kode_program
        $result = [];
        foreach ($uniqueSubOutputs as $subOutputKey) {
            list($kode_subOutput, $nama_subOutput) = explode('-0-', $subOutputKey);
            $result[] = [
                'kode_subOutput' => $kode_subOutput,
                'nama_subOutput' => $nama_subOutput,
            ];
        }
    
        // Kembalikan data dalam format JSON
        return response()->json($result);
    }

    function getUniqueKomponensJson($kode_satker, $kode_program, $kode_kegiatan, $kode_output, $kode_sub_output) {
        $kode_kegiatan = $kode_kegiatan == 'null' ? null : $kode_kegiatan;
        $kode_output = $kode_output == 'null' ? null : $kode_output;
        $kode_sub_output = $kode_sub_output == 'null' ? null : $kode_sub_output;

        $uniqueKomponens = [];

        $query = DB::table('akun_x_rkakls')
            ->select(
                'akun_x_rkakls.*', 
                'ref_rkakl_satkers.kode_satker', 
                'ref_rkakl_programs.kode_program', 
                'ref_rkakl_kegiatans.kode_kegiatan', 
                'ref_rkakl_kegiatans.nama_kegiatan', 
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_outputs.nama_output', 
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_suboutputs.nama_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_komponens.nama_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'akuns.kode_akun',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'akuns.nominal',
                'akuns.penggunaan'
            )
            ->join('akuns', 'akuns.id', '=', 'akun_x_rkakls.akun_id')
            ->join('ref_rkakl_sub_komponens', 'ref_rkakl_sub_komponens.id', '=', 'akun_x_rkakls.ref_sub_komponen_id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_komponens.id', '=', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_suboutputs.id', '=', 'ref_rkakl_komponens.ref_rkakl_suboutput_id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
            ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
            ->where('ref_rkakl_satkers.kode_satker', $kode_satker)
            ->where('ref_rkakl_programs.kode_program', $kode_program)
            ->where('akun_x_rkakls.versi_id', session('versi'));

        // Tambahkan filter kode_kegiatan hanya jika tidak null
        if ($kode_kegiatan) {
            $query->where('ref_rkakl_kegiatans.kode_kegiatan', $kode_kegiatan);
        }

        if ($kode_output) {
            $query->where('ref_rkakl_outputs.kode_output', $kode_output);
        }
        
        if ($kode_sub_output) {
            $query->where('ref_rkakl_suboutputs.kode_sub_output', $kode_sub_output);
        }

        $akuns = $query->get();
    
        foreach ($akuns as $akunxrkakl) {
            $komponenKey = $akunxrkakl->kode_komponen . '-0-' . $akunxrkakl->nama_komponen;
    
            // Cek apakah programKey sudah ada di array uniquePrograms
            if (!in_array($komponenKey, $uniqueKomponens)) {
                $uniqueKomponens[] = $komponenKey;
            }
        }

        // Pisahkan kode_satker dan kode_program
        $result = [];
        foreach ($uniqueKomponens as $komponenKey) {
            list($kode_komponen, $nama_komponen) = explode('-0-', $komponenKey);
            $result[] = [
                'kode_komponen' => $kode_komponen,
                'nama_komponen' => $nama_komponen,
            ];
        }
    
        // Kembalikan data dalam format JSON
        return response()->json($result);
    }
    function getUniqueSubKomponensJson($kode_satker, $kode_program, $kode_kegiatan, $kode_output, $kode_sub_output, $kode_komponen) {
        $kode_kegiatan = $kode_kegiatan == 'null' ? null : $kode_kegiatan;
        $kode_output = $kode_output == 'null' ? null : $kode_output;
        $kode_sub_output = $kode_sub_output == 'null' ? null : $kode_sub_output;
        $kode_komponen = $kode_komponen == 'null' ? null : $kode_komponen;

        $uniqueSubKomponens = [];

        $query = DB::table('akun_x_rkakls')
            ->select(
                'akun_x_rkakls.*', 
                'ref_rkakl_satkers.kode_satker', 
                'ref_rkakl_programs.kode_program', 
                'ref_rkakl_kegiatans.kode_kegiatan', 
                'ref_rkakl_kegiatans.nama_kegiatan', 
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_outputs.nama_output', 
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_suboutputs.nama_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_komponens.nama_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.kode_akun',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'akuns.nominal',
                'akuns.penggunaan'
            )
            ->join('akuns', 'akuns.id', '=', 'akun_x_rkakls.akun_id')
            ->join('ref_rkakl_sub_komponens', 'ref_rkakl_sub_komponens.id', '=', 'akun_x_rkakls.ref_sub_komponen_id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_komponens.id', '=', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_suboutputs.id', '=', 'ref_rkakl_komponens.ref_rkakl_suboutput_id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
            ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
            ->where('ref_rkakl_satkers.kode_satker', $kode_satker)
            ->where('ref_rkakl_programs.kode_program', $kode_program)
            ->where('akun_x_rkakls.versi_id', session('versi'));

        // Tambahkan filter kode_kegiatan hanya jika tidak null
        if ($kode_kegiatan) {
            $query->where('ref_rkakl_kegiatans.kode_kegiatan', $kode_kegiatan);
        }

        if ($kode_output) {
            $query->where('ref_rkakl_outputs.kode_output', $kode_output);
        }
        
        if ($kode_sub_output) {
            $query->where('ref_rkakl_suboutputs.kode_sub_output', $kode_sub_output);
        }
        
        if ($kode_komponen) {
            $query->where('ref_rkakl_komponens.kode_komponen', $kode_komponen);
        }

        $akuns = $query->get();
    
        foreach ($akuns as $akunxrkakl) {
            $subKomponenKey = $akunxrkakl->kode_sub_kegiatan . '-0-' . $akunxrkakl->nama_sub_kegiatan;
    
            // Cek apakah programKey sudah ada di array uniquePrograms
            if (!in_array($subKomponenKey, $uniqueSubKomponens)) {
                $uniqueSubKomponens[] = $subKomponenKey;
            }
        }

        // Pisahkan kode_satker dan kode_program
        $result = [];
        foreach ($uniqueSubKomponens as $subKomponenKey) {
            list($kode_SubKomponen, $nama_SubKomponen) = explode('-0-', $subKomponenKey);
            $result[] = [
                'kode_SubKomponen' => $kode_SubKomponen,
                'nama_SubKomponen' => $nama_SubKomponen,
            ];
        }
    
        // Kembalikan data dalam format JSON
        return response()->json($result);
    }

    function getUniqueAkunsSemuaJson($kode_satker, $kode_program, $kode_kegiatan, $kode_output, $kode_sub_output, $kode_komponen, $kode_sub_komponen) {
        $kode_kegiatan = $kode_kegiatan == 'null' ? null : $kode_kegiatan;
        $kode_output = $kode_output == 'null' ? null : $kode_output;
        $kode_sub_output = $kode_sub_output == 'null' ? null : $kode_sub_output;
        $kode_komponen = $kode_komponen == 'null' ? null : $kode_komponen;
        $kode_sub_komponen = $kode_sub_komponen == 'null' ? null : $kode_sub_komponen;

        $uniqueAkuns = [];

        $query = DB::table('akun_x_rkakls')
            ->select(
                'akun_x_rkakls.*', 
                'ref_rkakl_satkers.kode_satker', 
                'ref_rkakl_programs.kode_program', 
                'ref_rkakl_kegiatans.kode_kegiatan', 
                'ref_rkakl_kegiatans.nama_kegiatan', 
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_outputs.nama_output', 
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_suboutputs.nama_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_komponens.nama_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.kode_akun',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'akuns.nominal',
                'akuns.penggunaan'
            )
            ->join('akuns', 'akuns.id', '=', 'akun_x_rkakls.akun_id')
            ->join('ref_rkakl_sub_komponens', 'ref_rkakl_sub_komponens.id', '=', 'akun_x_rkakls.ref_sub_komponen_id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_komponens.id', '=', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_suboutputs.id', '=', 'ref_rkakl_komponens.ref_rkakl_suboutput_id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
            ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
            ->where('ref_rkakl_satkers.kode_satker', $kode_satker)
            ->where('ref_rkakl_programs.kode_program', $kode_program)
            ->where('akun_x_rkakls.versi_id', session('versi'));

        // Tambahkan filter kode_kegiatan hanya jika tidak null
        if ($kode_kegiatan) {
            $query->where('ref_rkakl_kegiatans.kode_kegiatan', $kode_kegiatan);
        }

        if ($kode_output) {
            $query->where('ref_rkakl_outputs.kode_output', $kode_output);
        }
        
        if ($kode_sub_output) {
            $query->where('ref_rkakl_suboutputs.kode_sub_output', $kode_sub_output);
        }
        
        if ($kode_komponen) {
            $query->where('ref_rkakl_komponens.kode_komponen', $kode_komponen);
        }

        if ($kode_sub_komponen) {
            $query->where('ref_rkakl_sub_komponens.kode_sub_kegiatan', $kode_sub_komponen);
        }

        $akuns = $query->get();
    
        foreach ($akuns as $akunxrkakl) {
            $akunKey = $akunxrkakl->kode_akun . '-0-' . $akunxrkakl->uraian;
    
            // Cek apakah programKey sudah ada di array uniquePrograms
            if (!in_array($akunKey, $uniqueAkuns)) {
                $uniqueAkuns[] = $akunKey;
            }
        }

        // Pisahkan kode_satker dan kode_program
        $result = [];
        foreach ($uniqueAkuns as $akunKey) {
            list($kode_akun, $uraian) = explode('-0-', $akunKey);
            $result[] = [
                'kode_akun' => $kode_akun,
                'uraian' => $uraian,
            ];
        }
    
        // Kembalikan data dalam format JSON
        return response()->json($result);
    }

    
    public function monitoringKeuanganSemua(Request $request)
    {
        // dd($request->kode_induk_program, $request->kode_akun);
        // dd($request);
        $tipe = $request->input('tipe', 'semua'); // Default ke 'semua' jika tidak ada input tipe

        $kode_induk_program = $request->kode_induk_program_semua == 'null' ? null : $request->kode_induk_program_semua;
        $kode_kegiatan = $request->kode_kegiatan_semua == 'null' ? null : $request->kode_kegiatan_semua;
        $kode_output = $request->kode_output_semua == 'null' ? null : $request->kode_output_semua;
        $kode_sub_output = $request->kode_sub_output_semua == 'null' ? null : $request->kode_sub_output_semua;
        $kode_komponen = $request->kode_komponen_semua == 'null' ? null :  $request->kode_komponen_semua;
        $kode_sub_komponen = $request->kode_sub_komponen_semua == 'null' ? null : $request->kode_sub_komponen_semua;
        $kode_akun = $request->kode_akun_semua == 'null' ? null : $request->kode_akun_semua;
        $item = $request->item_semua == '0' ? false : ($request->item_semua == '1' ? true : null);

        $selectedProgram = explode('.', $kode_induk_program);
        $kode_satker = $selectedProgram[0]; // Ambil elemen pertama sebagai kode_satker
        $kode_program = implode('.', array_slice($selectedProgram, 1)); // Gabungkan elemen setelah pertama sebagai kode_program

        // dd($kode_satker, $kode_program, $kode_kegiatan, $kode_output, $kode_sub_output, $kode_komponen, $kode_sub_komponen, $kode_akun, $item);

        $uniqueAkuns = [];

        $query = DB::table('akun_x_rkakls')
            ->select(
                'akun_x_rkakls.*', 
                'ref_rkakl_satkers.kode_satker', 
                'ref_rkakl_programs.kode_program', 
                'ref_rkakl_kegiatans.kode_kegiatan', 
                'ref_rkakl_kegiatans.nama_kegiatan', 
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_outputs.nama_output', 
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_suboutputs.nama_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_komponens.nama_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.kode_akun',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'akuns.nominal',
                'akuns.penggunaan'
            )
            ->join('akuns', 'akuns.id', '=', 'akun_x_rkakls.akun_id')
            ->join('ref_rkakl_sub_komponens', 'ref_rkakl_sub_komponens.id', '=', 'akun_x_rkakls.ref_sub_komponen_id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_komponens.id', '=', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_suboutputs.id', '=', 'ref_rkakl_komponens.ref_rkakl_suboutput_id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
            ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
            ->where('ref_rkakl_satkers.kode_satker', $kode_satker)
            ->where('ref_rkakl_programs.kode_program', $kode_program)
            ->where('akun_x_rkakls.versi_id', session('versi'));

        // Tambahkan filter kode_kegiatan hanya jika tidak null
        if ($kode_kegiatan) {
            $query->where('ref_rkakl_kegiatans.kode_kegiatan', $kode_kegiatan);
        }

        if ($kode_output) {
            $query->where('ref_rkakl_outputs.kode_output', $kode_output);
        }
        
        if ($kode_sub_output) {
            $query->where('ref_rkakl_suboutputs.kode_sub_output', $kode_sub_output);
        }
        
        if ($kode_komponen) {
            $query->where('ref_rkakl_komponens.kode_komponen', $kode_komponen);
        }

        if ($kode_sub_komponen) {
            $query->where('ref_rkakl_sub_komponens.kode_sub_kegiatan', $kode_sub_komponen);
        }

        if ($kode_akun) {
            $query->where('akuns.kode_akun', $kode_akun);
        }

        $akuns = $query->get();

        $total = (object) [
            'nominal' => $akuns->sum('nominal'),
            'penggunaan' => $akuns->sum('penggunaan'),
        ];

        if($kode_akun) {
            $uraianData = DB::table('akuns')
                ->select('uraian')
                ->where('kode_akun',$kode_akun )
                ->first();
        } else {
            $uraianData = null;
        }

        
        if($uraianData) {
            $uraian = $uraianData->uraian;
        } else {
            $uraian = null;
        }

        if($item) {      
            // Nonaktifkan ONLY_FULL_GROUP_BY
            DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");

            foreach ($akuns as $akunxrkakl) {
                $akunKey = $akunxrkakl->id;
                
                // Cek apakah programKey sudah ada di array uniquePrograms
                if (!in_array($akunKey, $uniqueAkuns)) {
                    $uniqueAkuns[] = $akunKey;
                }
            }

            $uniqueAkuns = array_map('intval', array_values($uniqueAkuns));
            rsort($uniqueAkuns);

            // dd($uniqueAkuns);
            if ($tipe == 'semua') {
                $perjadin = DB::table('keuangan_perjadinlangsungs')
                    ->leftJoin('info_perjadinlangsungs', 'keuangan_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
                    ->join('akun_x_rkakls', 'akun_x_rkakls.id', '=', 'keuangan_perjadinlangsungs.akun_x_rkakl')
                    ->join('akuns', 'akuns.id', '=', 'akun_x_rkakls.akun_id')
                    ->join('ref_rkakl_sub_komponens', 'ref_rkakl_sub_komponens.id', '=', 'akun_x_rkakls.ref_sub_komponen_id')
                    ->join('ref_rkakl_komponens', 'ref_rkakl_komponens.id', '=', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id')
                    ->join('ref_rkakl_suboutputs', 'ref_rkakl_suboutputs.id', '=', 'ref_rkakl_komponens.ref_rkakl_suboutput_id')
                    ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
                    ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
                    ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
                    ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
                    ->select(
                    DB::raw('CONCAT(ref_rkakl_satkers.kode_satker, ".", ref_rkakl_programs.kode_program, ".", ref_rkakl_kegiatans.kode_kegiatan, ".", ref_rkakl_outputs.kode_output, ".", ref_rkakl_suboutputs.kode_sub_output, ".", ref_rkakl_komponens.kode_komponen, ".", ref_rkakl_sub_komponens.kode_sub_kegiatan, ".", akuns.kode_akun) as mak'),
                    DB::raw('info_perjadinlangsungs.id AS id'),
                    'info_perjadinlangsungs.nama_kegiatan',
                    'info_perjadinlangsungs.tgl_keberangkatan',
                    'info_perjadinlangsungs.tgl_selesai',
                    'info_perjadinlangsungs.kabupaten_kota',
                    'info_perjadinlangsungs.provinsi',
                    'info_perjadinlangsungs.kode_surat_tugas',
                    'info_perjadinlangsungs.status_pengajuan_detail',
                    DB::raw('SUM(COALESCE(keuangan_perjadinlangsungs.jumlah_harga, 0)) AS totalPenggunaan'),
                    DB::raw('"perjadin" as tipe')
                    )
                    ->whereIn('keuangan_perjadinlangsungs.akun_x_rkakl', $uniqueAkuns)
                    ->where('info_perjadinlangsungs.is_acceptBend', 'selesai')
                    ->groupBy('info_perjadinlangsungs.id');
                
                $kegiatan = DB::table('keuangan_perjadinkegiatans')
                    ->leftJoin('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
                    ->join('akun_x_rkakls', 'akun_x_rkakls.id', '=', 'keuangan_perjadinkegiatans.akun_x_rkakl')
                    ->join('akuns', 'akuns.id', '=', 'akun_x_rkakls.akun_id')
                    ->join('ref_rkakl_sub_komponens', 'ref_rkakl_sub_komponens.id', '=', 'akun_x_rkakls.ref_sub_komponen_id')
                    ->join('ref_rkakl_komponens', 'ref_rkakl_komponens.id', '=', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id')
                    ->join('ref_rkakl_suboutputs', 'ref_rkakl_suboutputs.id', '=', 'ref_rkakl_komponens.ref_rkakl_suboutput_id')
                    ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
                    ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
                    ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
                    ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
                    ->select(
                    DB::raw('CONCAT(ref_rkakl_satkers.kode_satker, ".", ref_rkakl_programs.kode_program, ".", ref_rkakl_kegiatans.kode_kegiatan, ".", ref_rkakl_outputs.kode_output, ".", ref_rkakl_suboutputs.kode_sub_output, ".", ref_rkakl_komponens.kode_komponen, ".", ref_rkakl_sub_komponens.kode_sub_kegiatan, ".", akuns.kode_akun) as mak'),
                    DB::raw('data_perjadinkegiatans.id AS id'),
                    'data_perjadinkegiatans.nama_kegiatan',
                    'data_perjadinkegiatans.tgl_mulai AS tgl_keberangkatan',
                    'data_perjadinkegiatans.tgl_selesai',
                    'data_perjadinkegiatans.kab_kota AS kabupaten_kota',
                    'data_perjadinkegiatans.provinsi',
                    'data_perjadinkegiatans.kode_surat_tugas',
                    'data_perjadinkegiatans.status_pengajuan_detail',
                    DB::raw('SUM(COALESCE(jumlah_honorarium, 0) +
                                COALESCE(nominal_perjadin, 0) +
                                COALESCE(harga, 0)) AS totalPenggunaan'),
                    DB::raw('"kegiatan" as tipe')
                    )
                    
                    ->whereIn('keuangan_perjadinkegiatans.akun_x_rkakl', $uniqueAkuns)
                    ->where('data_perjadinkegiatans.is_acceptBend', 'selesai')
                    
                    ->groupBy('data_perjadinkegiatans.id');

                    // dd($kegiatan->get());
                    
                    // Gabungkan semua query dengan UNION
                    $keuangans = $perjadin
                    ->union($kegiatan)
                    ->get();
                }    elseif ($tipe == 'perjadin') {
                        $keuangans = DB::table('keuangan_perjadinlangsungs')
                                ->leftjoin('info_perjadinlangsungs', 'keuangan_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
                                ->join('akun_x_rkakls', 'akun_x_rkakls.id', '=', 'keuangan_perjadinlangsungs.akun_x_rkakl')
                                ->join('akuns', 'akuns.id', '=', 'akun_x_rkakls.akun_id')
                                ->join('ref_rkakl_sub_komponens', 'ref_rkakl_sub_komponens.id', '=', 'akun_x_rkakls.ref_sub_komponen_id')
                                ->join('ref_rkakl_komponens', 'ref_rkakl_komponens.id', '=', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id')
                                ->join('ref_rkakl_suboutputs', 'ref_rkakl_suboutputs.id', '=', 'ref_rkakl_komponens.ref_rkakl_suboutput_id')
                                ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
                                ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
                                ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
                                ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
                                ->select(
                                    DB::raw('CONCAT(ref_rkakl_satkers.kode_satker, ".", ref_rkakl_programs.kode_program, ".", ref_rkakl_kegiatans.kode_kegiatan, ".", ref_rkakl_outputs.kode_output, ".", ref_rkakl_suboutputs.kode_sub_output, ".", ref_rkakl_komponens.kode_komponen, ".", ref_rkakl_sub_komponens.kode_sub_kegiatan, ".", akuns.kode_akun) as mak'),
                                    'info_perjadinlangsungs.*',
                                    DB::raw('SUM(COALESCE(keuangan_perjadinlangsungs.jumlah_harga, 0)) AS totalPenggunaan'
                                    ),
                                    DB::raw('"perjadin" as tipe')
                                )
                                ->whereIn('keuangan_perjadinlangsungs.akun_x_rkakl', $uniqueAkuns)
                                ->where('info_perjadinlangsungs.is_acceptBend', 'selesai')
                                ->groupBy('info_perjadinlangsungs.id')
                                ->get();
                
                } else if ($tipe == 'kegiatan') {
                    $keuangans = DB::table('keuangan_perjadinkegiatans')
                            ->leftjoin('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
                            ->join('akun_x_rkakls', 'akun_x_rkakls.id', '=', 'keuangan_perjadinkegiatans.akun_x_rkakl')
                            ->join('akuns', 'akuns.id', '=', 'akun_x_rkakls.akun_id')
                            ->join('ref_rkakl_sub_komponens', 'ref_rkakl_sub_komponens.id', '=', 'akun_x_rkakls.ref_sub_komponen_id')
                            ->join('ref_rkakl_komponens', 'ref_rkakl_komponens.id', '=', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id')
                            ->join('ref_rkakl_suboutputs', 'ref_rkakl_suboutputs.id', '=', 'ref_rkakl_komponens.ref_rkakl_suboutput_id')
                            ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
                            ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
                            ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
                            ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
                            ->select(
                                DB::raw('CONCAT(ref_rkakl_satkers.kode_satker, ".", ref_rkakl_programs.kode_program, ".", ref_rkakl_kegiatans.kode_kegiatan, ".", ref_rkakl_outputs.kode_output, ".", ref_rkakl_suboutputs.kode_sub_output, ".", ref_rkakl_komponens.kode_komponen, ".", ref_rkakl_sub_komponens.kode_sub_kegiatan, ".", akuns.kode_akun) as mak'),
                                'data_perjadinkegiatans.*',
                                'data_perjadinkegiatans.kab_kota AS kabupaten_kota',
                                'data_perjadinkegiatans.tgl_mulai AS tgl_keberangkatan',
                                DB::raw('SUM(COALESCE(jumlah_honorarium, 0) +
                                                            COALESCE(nominal_perjadin, 0) +
                                                            COALESCE(harga, 0)) AS totalPenggunaan'
                                ),
                                DB::raw('"kegiatan" as tipe')
                            )
                            ->whereIn('keuangan_perjadinkegiatans.akun_x_rkakl', $uniqueAkuns)
                            ->where('data_perjadinkegiatans.is_acceptBend', 'selesai')
                            ->groupBy('data_perjadinkegiatans.id')
                            ->get();

                 } else if ($tipe == 'bmn') {
                    $keuangans = DB::table('permohonans')
                            ->leftJoin('administrators', 'permohonans.admin', '=', 'administrators.id')
                            ->leftJoin('assets', 'permohonans.asset_id', '=', 'assets.id')
                            ->leftJoin('data_penanggungjawabs', 'permohonans.data_penanggungjawab_id', '=', 'data_penanggungjawabs.id')
                            ->leftJoin('pegawais', 'data_penanggungjawabs.pegawai_id', '=', 'pegawais.id')
                            ->leftJoin('kendaraans', 'permohonans.kendaraan_id', '=', 'kendaraans.id')
                            ->join('akun_x_rkakls', 'akun_x_rkakls.id', '=', 'permohonans.akun_x_rkakl_id')
                            ->join('akuns', 'akuns.id', '=', 'akun_x_rkakls.akun_id')
                            ->join('ref_rkakl_sub_komponens', 'ref_rkakl_sub_komponens.id', '=', 'akun_x_rkakls.ref_sub_komponen_id')
                            ->join('ref_rkakl_komponens', 'ref_rkakl_komponens.id', '=', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id')
                            ->join('ref_rkakl_suboutputs', 'ref_rkakl_suboutputs.id', '=', 'ref_rkakl_komponens.ref_rkakl_suboutput_id')
                            ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
                            ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
                            ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
                            ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
                            ->select(
                                DB::raw('CONCAT(ref_rkakl_satkers.kode_satker, ".", ref_rkakl_programs.kode_program, ".", ref_rkakl_kegiatans.kode_kegiatan, ".", ref_rkakl_outputs.kode_output, ".", ref_rkakl_suboutputs.kode_sub_output, ".", ref_rkakl_komponens.kode_komponen, ".", ref_rkakl_sub_komponens.kode_sub_kegiatan, ".", akuns.kode_akun) as mak'),
                                'permohonans.id as idService',
                                'permohonans.*',
                                DB::raw("
                                    CASE
                                        WHEN permohonans.admin IS NOT NULL THEN CONCAT('(Admin) ', administrators.username)
                                        WHEN permohonans.data_penanggungjawab_id IS NOT NULL THEN pegawais.nama_lengkap
                                        ELSE 'Tidak Diketahui'
                                    END AS penanggungJawab
                                "),
                                DB::raw("
                                    CASE
                                        WHEN permohonans.kendaraan_id IS NULL
                                        THEN CONCAT(assets.nama_barang, ' (', assets.nama_merek, ')')
                                        ELSE CONCAT('[',kendaraans.no_polisi,'] ', kendaraans.merek)
                                    END AS deskripsiAsset
                                "),
                                DB::raw('SUM(COALESCE(permohonans.nominal, 0)) AS totalPenggunaan'),
                                DB::raw('"bmn" as tipe')
                            )
                            ->whereIn('permohonans.akun_x_rkakl_id', $uniqueAkuns)
                            ->groupBy('permohonans.id', 'administrators.username', 'pegawais.nama_lengkap', 'assets.nama_barang', 'assets.nama_merek', 'kendaraans.no_polisi', 'kendaraans.merek') // Pastikan grup sesuai dengan select
                            ->get();
            
                } else {
                    $akuns = DB::table('akun_x_rkakls')
                            ->limit(0)
                            ->get();
                    $keuangans = NULL;
                }

                // dd($keuangans);
            $total = (object) [
                'penggunaan' => $keuangans->sum('totalPenggunaan'),
            ];

            // Kembalikan ONLY_FULL_GROUP_BY ke default
            DB::statement("SET SESSION sql_mode=CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY')");
            

            $kode_induk_program = $request->kode_induk_program_semua == 'null' ? null : $request->kode_induk_program_semua;
            $kode_kegiatan = $request->kode_kegiatan_semua == 'null' ? null : $request->kode_kegiatan_semua;
            $kode_output = $request->kode_output_semua == 'null' ? null : $request->kode_output_semua;
            $kode_sub_output = $request->kode_sub_output_semua == 'null' ? null : $request->kode_sub_output_semua;
            $kode_komponen = $request->kode_komponen_semua == 'null' ? null :  $request->kode_komponen_semua;
            $kode_sub_komponen = $request->kode_sub_komponen_semua == 'null' ? null : $request->kode_sub_komponen_semua;
            $kode_akun = $request->kode_akun_semua == 'null' ? null : $request->kode_akun_semua;
            $item = $request->item_semua == '0' ? false : ($request->item_semua == '1' ? true : null);

            // dd($tipe);
            // dd($keuangans);
            return view('admin.other.monitoring-keuangan.monitoring-keuangan-semua-detail', [
                'title' => 'Monitoring Keuangan Per Akun',
                'akuns' => $akuns,
                'total' => $total,
                'kode_induk_program' => $kode_induk_program,
                'kode_kegiatan' => $kode_kegiatan,
                'kode_output' => $kode_output,
                'kode_sub_output' => $kode_sub_output,
                'kode_komponen' => $kode_komponen,
                'kode_sub_komponen' => $kode_sub_komponen,
                'kode_akun' => $kode_akun,
                'uraian' => $uraian,
                'tipe' => $tipe,
                'keuangans' => $keuangans,
                'isSemua' => ($tipe == 'semua'),
                'isPerjadin' => ($tipe == 'perjadin'),
                'isKegiatan' => ($tipe == 'kegiatan'),
                'isBMN' => ($tipe == 'bmn'),
                
                'request' => $request,
                // 'kode_induk_program' => $request->kode_induk_program_semua,
                // 'kode_kegiatan' => $request->kode_kegiatan_semua,
                // 'kode_output' => $request->kode_output_semua,
                // 'kode_sub_output' => $request->kode_sub_output_semua,
                // 'kode_komponen' => $request->kode_komponen_semua,
                // 'kode_sub_komponen' => $request->kode_sub_komponen_semua,
                // 'kode_akun' => $request->kode_akun_semua,
                // 'item' => $request->item_semua,
            ]);
        } else {
            return view('admin.other.monitoring-keuangan.monitoring-keuangan-semua', [
                'title' => 'Monitoring Keuangan Per Akun',
                'akuns' => $akuns,
                'total' => $total,
                'kode_induk_program' => $kode_induk_program,
                'kode_kegiatan' => $kode_kegiatan,
                'kode_output' => $kode_output,
                'kode_sub_output' => $kode_sub_output,
                'kode_komponen' => $kode_komponen,
                'kode_sub_komponen' => $kode_sub_komponen,
                'kode_akun' => $kode_akun,
                'uraian' => $uraian,
                // 'kode_induk_program' => $request->kode_induk_program,
                // 'kode_akun' => $request->kode_akun,
                // 'dataAkuns' => $dataAkuns,
            ]);
        }
    }


    function getUniqueAkunsJson($kode_satker, $kode_program) {
        $uniqueAkuns = [];

        $akuns = DB::table('akun_x_rkakls')
            ->select('akun_x_rkakls.*', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program', 'ref_rkakl_kegiatans.kode_kegiatan', 'ref_rkakl_outputs.kode_output', 'ref_rkakl_suboutputs.kode_sub_output','ref_rkakl_komponens.kode_komponen','ref_rkakl_sub_komponens.kode_sub_kegiatan','akuns.kode_akun','ref_rkakl_sub_komponens.nama_sub_kegiatan','akuns.uraian','akuns.nominal','akuns.penggunaan')
            ->join('akuns', 'akuns.id', '=', 'akun_x_rkakls.akun_id')
            ->join('ref_rkakl_sub_komponens', 'ref_rkakl_sub_komponens.id', '=', 'akun_x_rkakls.ref_sub_komponen_id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_komponens.id', '=', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_suboutputs.id', '=', 'ref_rkakl_komponens.ref_rkakl_suboutput_id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
            ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
                ->where('ref_rkakl_satkers.kode_satker', $kode_satker)
                ->where('ref_rkakl_programs.kode_program', $kode_program)
                ->where('akun_x_rkakls.versi_id', session('versi'))
                ->get();

    
        foreach ($akuns as $akunxrkakl) {
            $akunKey = $akunxrkakl->kode_akun . '-0-' . $akunxrkakl->uraian;
    
            // Cek apakah programKey sudah ada di array uniquePrograms
            if (!in_array($akunKey, $uniqueAkuns)) {
                $uniqueAkuns[] = $akunKey;
            }
        }

        // Pisahkan kode_satker dan kode_program
        $result = [];
        foreach ($uniqueAkuns as $akunKey) {
            list($kode_akun, $uraian) = explode('-0-', $akunKey);
            $result[] = [
                'kode_akun' => $kode_akun,
                'uraian' => $uraian,
            ];
        }
    
        // Kembalikan data dalam format JSON
        return response()->json($result);
    }

    public function indexLaporan(Request $request)
{
    return view('admin.other.laporan', [
        'title' => 'Laporan',
        'page' => $request->get('page', 'page_1'), // default page_1
    ]);
}

    public function indexKoreksi($status) {

        if ($status == 'perjadin') {
            return view('admin.other.koreksi.index-perjadin', [
                'status' => $status,
            ]);
        } elseif ($status == 'kegiatan') {
            return view('admin.other.koreksi.index-kegiatan', [
                'status' => $status,
            ]);
        } else{
            return view('admin.other.koreksi.index-404', [
                'status' => $status,
            ]);
        }

    }
    public function detailKoreksi($status, $id) {

        if ($status == 'perjadin') {
            $cekDataPerjadin = DB::table('info_perjadinlangsungs')
                ->where('id', $id)
                ->exists();

            $cekDataVersiPerjadin = DB::table('info_perjadinlangsungs')
                ->where('id', $id)
                ->where('versi_id', session('versi'))
                ->exists();

            $statusPengajuan = DB::table('info_perjadinlangsungs')
                ->where('id', $id)
                ->where('is_acceptBend', 'selesai')
                ->exists();

            $isDataValid = $cekDataPerjadin && $cekDataVersiPerjadin && $statusPengajuan;
            
            // dd($cekDataPerjadin, $cekDataVersiPerjadin, $statusPengajuan, $isDataValid);

            if (!$isDataValid) {
                $alasan = '';
                if (!$cekDataPerjadin) {
                    $alasan .= 'Data Perjadin tidak ditemukan;';
                } else {
                    if (!$cekDataVersiPerjadin) {
                        $alasan .= 'Data Perjadin tidak sesuai dengan versi atau Tahun Anggaran saat ini;';
                    }
                    if (!$statusPengajuan) {
                        $alasan .= 'Ajuan Belum dilakukan pembayaran;';
                    }
                }
                
                return view('admin.other.koreksi.detail-404', [
                    'status' => $status,
                    'id' => $id,
                    'alasan' => $alasan,
                ]);
            } else {
                    $pesertaPegawais = DB::table('data_perjadinlangsungs')
                        ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
                        ->select('pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'pegawais.NIP_NIK', 'pegawais.id', 'pegawais.nama_lengkap', 'data_perjadinlangsungs.id as idPeserta')
                        ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
                        ->get();

                    $fasilitas = DB::table('data_perjadinlangsungs')
                        ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
                        ->join('keuangan_perjadinlangsungs', 'data_perjadinlangsungs.id', '=', 'keuangan_perjadinlangsungs.data_perjadinlangsungs')
                        ->select('pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'pegawais.NIP_NIK', 'pegawais.id', 'pegawais.nama_lengkap', 'data_perjadinlangsungs.id as idPeserta', 'keuangan_perjadinlangsungs.id as idKeuangan', 'keuangan_perjadinlangsungs.akun_x_rkakl', 'keuangan_perjadinlangsungs.ref_sbm', 'keuangan_perjadinlangsungs.uang_harian', 'keuangan_perjadinlangsungs.uang_harian_fullday', 'keuangan_perjadinlangsungs.uang_harian_fullboard', 'keuangan_perjadinlangsungs.uang_representasi', 'keuangan_perjadinlangsungs.persen_pajak', 'keuangan_perjadinlangsungs.jumlah_harga', 'keuangan_perjadinlangsungs.status', 'keuangan_perjadinlangsungs.pph22', 'keuangan_perjadinlangsungs.pph23', 'keuangan_perjadinlangsungs.tgl_bayar', 'keuangan_perjadinlangsungs.ppn')
                        ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
                        ->whereNull('keuangan_perjadinlangsungs.kebutuhan_id')
                        ->get();

                    $pesertaNonPegawais = DB::table('data_perjadinlangsungs')
                        ->join('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
                        ->join('keuangan_perjadinlangsungs', 'data_perjadinlangsungs.id', '=', 'keuangan_perjadinlangsungs.data_perjadinlangsungs')
                        ->select('keuangan_perjadinlangsungs.id  as idKeuangan', 'non_pegawais.id', 'non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'data_perjadinlangsungs.id as idData', 'keuangan_perjadinlangsungs.akun_x_rkakl', 'keuangan_perjadinlangsungs.ref_sbm', 'keuangan_perjadinlangsungs.uang_harian', 'keuangan_perjadinlangsungs.uang_harian_fullday','keuangan_perjadinlangsungs.uang_harian_fullboard','keuangan_perjadinlangsungs.uang_representasi','keuangan_perjadinlangsungs.persen_pajak', 'keuangan_perjadinlangsungs.jumlah_harga', 'keuangan_perjadinlangsungs.status', 'keuangan_perjadinlangsungs.pph22', 'keuangan_perjadinlangsungs.pph23', 'keuangan_perjadinlangsungs.tgl_bayar', 'keuangan_perjadinlangsungs.ppn')
                        ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
                        ->whereNull('keuangan_perjadinlangsungs.kebutuhan_id')
                        ->get();

                    $kebutuhans = DB::table('kebutuhans')
                        ->join('keuangan_perjadinlangsungs', 'kebutuhans.id', '=', 'keuangan_perjadinlangsungs.kebutuhan_id')
                        ->join('data_perjadinlangsungs', 'keuangan_perjadinlangsungs.data_perjadinlangsungs', '=', 'data_perjadinlangsungs.id')
                        ->leftJoin('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
                        ->leftJoin('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
                        ->select(
                            'kebutuhans.id as idKebutuhan', 'pegawais.nama_lengkap',
                            'kebutuhans.nama',
                            'kebutuhans.jumlah_frekuensi',
                            'kebutuhans.satuan',
                            'kebutuhans.tipe_pendanaan',
                            'kebutuhans.ket',
                            'kebutuhans.status',
                            'keuangan_perjadinlangsungs.kebutuhan_id as idKeuangan',
                            'keuangan_perjadinlangsungs.info_perjadinlangsung',
                            'keuangan_perjadinlangsungs.uang_harian',
                            'keuangan_perjadinlangsungs.persen_pajak',
                            'keuangan_perjadinlangsungs.jumlah_harga',
                            'keuangan_perjadinlangsungs.akun_x_rkakl',
                            'keuangan_perjadinlangsungs.ref_sbm',
                            'keuangan_perjadinlangsungs.status as statusPembayaran',
                            'keuangan_perjadinlangsungs.pph22',
                            'keuangan_perjadinlangsungs.pph23',
                            'keuangan_perjadinlangsungs.tgl_bayar',
                            'keuangan_perjadinlangsungs.ppn',
                            DB::raw('COALESCE(pegawais.nama_lengkap, non_pegawais.nama_lengkap, "Tanpa Terikat Pelaksana") as pelaksana')
                            )
                        ->where('keuangan_perjadinlangsungs.info_perjadinlangsung', $id)
                        ->get();

                    $akuns = DB::table('akun_x_rkakls')
                        ->join('akuns', 'akun_x_rkakls.akun_id', '=', 'akuns.id')
                        ->join('ref_rkakl_sub_komponens', 'akun_x_rkakls.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
                        ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
                        ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
                        ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
                        ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
                        ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
                        ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
                        ->select('akun_x_rkakls.id as idAkun', 'ref_rkakl_sub_komponens.nama_sub_kegiatan', 'akuns.uraian', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program', 'ref_rkakl_kegiatans.kode_kegiatan', 'ref_rkakl_outputs.kode_output', 'ref_rkakl_suboutputs.kode_sub_output', 'ref_rkakl_komponens.kode_komponen', 'ref_rkakl_sub_komponens.kode_sub_kegiatan', 'ref_rkakl_sub_komponens.nama_sub_kegiatan', 'akuns.kode_akun', 'akuns.uraian')
                        ->where('akun_x_rkakls.versi_id', session('versi'))
                        ->get();

                    $dokumen = DB::table('dokumens')
                        ->where('info_perjadinlangsung_id', $id)
                        ->get();
                return view('admin.other.koreksi.detail-perjadin', [
                    'status' => $status,
                    'id' => $id,
                    'perjadin' => Info_perjadinlangsung::find($id),
                    'pesertaPegawais' => $pesertaPegawais,
                    'pesertaNonPegawais' => $pesertaNonPegawais,
                    'kebutuhans' => $kebutuhans,
                    'fasilitas' => $fasilitas,
                    "sbms" => Ref_sbm::all(),
                    'akuns' => $akuns,
                    'dokumen' => $dokumen,
                    'ref_fasilitas' => DB::table('ref_fasilitas')->where('status','aktif')->get(),
                ]);
            }
        } elseif ($status == 'kegiatan') {
            $cekDataKegiatan = DB::table('data_perjadinkegiatans')
            ->where('id', $id)
            ->exists();

            $cekDataVersiKegiatan = DB::table('data_perjadinkegiatans')
                ->where('id', $id)
                ->where('versi_id', session('versi'))
                ->exists();

            $statusPengajuan = DB::table('data_perjadinkegiatans')
                ->where('id', $id)
                ->where('is_acceptBend', 'selesai')
                ->exists();

            $isDataValid = $cekDataKegiatan && $cekDataVersiKegiatan && $statusPengajuan;

            if (!$isDataValid) {
                $alasan = '';
                if (!$cekDataKegiatan) {
                    $alasan .= 'Data Kegiatan tidak ditemukan;';
                } else {
                    if (!$cekDataVersiKegiatan) {
                        $alasan .= 'Data Kegiatan tidak sesuai dengan versi atau Tahun Anggaran saat ini;';
                    }
                    if (!$statusPengajuan) {
                        $alasan .= 'Ajuan Belum dilakukan pembayaran;';
                    }
                }
                return view('admin.other.koreksi.detail-404', [
                    'status' => $status,
                    'id' => $id,
                    'alasan' => $alasan,
                ]);
            } else {
                $kegiatanData =  DB::table('data_perjadinkegiatans')
                ->where('id', $id)
                ->first();

                $isPenugasan = $kegiatanData->jenis_program == 'Penugasan';

                // Nonaktifkan ONLY_FULL_GROUP_BY
                DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");
                $pegawais = DB::table('perangkat_acaras')
                                ->join('fasilitas', 'perangkat_acaras.fasilitas_id', '=', 'fasilitas.id')
                                ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
                                ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
                                ->select('pegawais.id as idPegawai','pegawais.status as statusPegawai', 'pegawais.*','perangkat_acaras.posisi', 'perangkat_acaras.detail_satuan', 'perangkat_acaras.satuan','perangkat_acaras.sebagai', 'perangkat_acaras.status', 'perangkat_acaras.fasilitas_id', 'fasilitas.nama_fasilitas', 'perangkat_acaras.id as idPerangkatAcara', 'keuangan_perjadinkegiatans.id as idKeuangan', 'keuangan_perjadinkegiatans.data_perjadinkegiatan as idKegiatan', 'keuangan_perjadinkegiatans.*')
                                ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
                                ->GroupBy('keuangan_perjadinkegiatans.perangkat_acara')
                                ->get();

                // dd($pegawais);

                $nonpegawais = DB::table('perangkat_acaras')
                                ->join('fasilitas', 'perangkat_acaras.fasilitas_id', '=', 'fasilitas.id')
                                ->join('non_pegawais', 'perangkat_acaras.non_pegawai_id', '=', 'non_pegawais.id')
                                ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
                                ->select('non_pegawais.id as idNonPegawai', 'non_pegawais.status as statusNonPegawai','non_pegawais.*','perangkat_acaras.posisi', 'perangkat_acaras.detail_satuan', 'perangkat_acaras.satuan','perangkat_acaras.sebagai', 'perangkat_acaras.status', 'fasilitas.nama_fasilitas', 'perangkat_acaras.fasilitas_id', 'perangkat_acaras.id as idPerangkatAcara', 'keuangan_perjadinkegiatans.id as idKeuangan', 'keuangan_perjadinkegiatans.data_perjadinkegiatan as idKegiatan', 'keuangan_perjadinkegiatans.*')
                                ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
                                ->GroupBy('keuangan_perjadinkegiatans.perangkat_acara')
                                ->get();

                // Kembalikan ONLY_FULL_GROUP_BY ke default
                DB::statement("SET SESSION sql_mode=CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY')");

                $operasionals = DB::table('keuangan_perjadinkegiatans')
                                ->join('operasionals', 'keuangan_perjadinkegiatans.operasional', '=', 'operasionals.id')
                                ->select('operasionals.id', 'operasionals.status', 'operasionals.nama', 'operasionals.jumlah_frekuensi', 'operasionals.satuan', 'operasionals.detail_satuan', 'keuangan_perjadinkegiatans.operasional', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', 'keuangan_perjadinkegiatans.pph22', 'keuangan_perjadinkegiatans.pph23', 'keuangan_perjadinkegiatans.ppn', 'keuangan_perjadinkegiatans.tgl_bayar')
                                ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
                                ->whereNotNull('keuangan_perjadinkegiatans.perangkat_acara')
                                ->get();
                $kebutuhans = DB::table('kebutuhans')
                                ->join('keuangan_perjadinkegiatans', 'kebutuhans.id', '=', 'keuangan_perjadinkegiatans.kebutuhan_id')
                                ->join('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
                                ->leftJoin('perangkat_acaras', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
                                ->leftJoin('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
                                ->leftJoin('non_pegawais', 'perangkat_acaras.non_pegawai_id', '=', 'non_pegawais.id')
                                ->select(
                                    'kebutuhans.id as idKebutuhan',
                                    'kebutuhans.nama',
                                    'kebutuhans.jumlah_frekuensi',
                                    'kebutuhans.satuan',
                                    'kebutuhans.tipe_pendanaan',
                                    'kebutuhans.ket',
                                    'kebutuhans.status',
                                    'keuangan_perjadinkegiatans.kebutuhan_id as idKeuangan',
                                    'keuangan_perjadinkegiatans.*',
                                    DB::raw('COALESCE(pegawais.nama_lengkap, non_pegawais.nama_lengkap, "Tanpa Terikat Pelaksana") as pelaksana')
                                )
                                ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
                                ->get();
                $keuanganOperasional = DB::table('keuangan_perjadinkegiatans')
                                ->join('operasionals', 'keuangan_perjadinkegiatans.operasional', '=', 'operasionals.id')
                                ->select('keuangan_perjadinkegiatans.akun_x_rkakl', 'keuangan_perjadinkegiatans.harga', 'keuangan_perjadinkegiatans.persen_pajak', 'keuangan_perjadinkegiatans.jumlah_harga', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', 'keuangan_perjadinkegiatans.operasional', 'keuangan_perjadinkegiatans.status', 'operasionals.nama', 'operasionals.jumlah_frekuensi', 'keuangan_perjadinkegiatans.pph22', 'keuangan_perjadinkegiatans.pph23', 'keuangan_perjadinkegiatans.ppn', 'keuangan_perjadinkegiatans.tgl_bayar')
                                ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
                                ->get();
                $akuns = DB::table('akun_x_rkakls')
                                ->join('akuns', 'akun_x_rkakls.akun_id', '=', 'akuns.id')
                                ->join('ref_rkakl_sub_komponens', 'akun_x_rkakls.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
                                ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
                                ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
                                ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
                                ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
                                ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
                                ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
                                ->select('akun_x_rkakls.id as idAkun', 'ref_rkakl_sub_komponens.nama_sub_kegiatan', 'akuns.uraian', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program', 'ref_rkakl_kegiatans.kode_kegiatan', 'ref_rkakl_outputs.kode_output', 'ref_rkakl_suboutputs.kode_sub_output', 'ref_rkakl_komponens.kode_komponen', 'ref_rkakl_sub_komponens.kode_sub_kegiatan', 'ref_rkakl_sub_komponens.nama_sub_kegiatan', 'akuns.kode_akun', 'akuns.uraian')
                                ->where('akun_x_rkakls.versi_id', session('versi'))
                                ->get();


                $kegiatanData = DB::table('data_perjadinkegiatans')
                                ->where('id', $id)
                                ->first();

                return view('admin.other.koreksi.detail-kegiatan', [
                    'title' => 'Koreksi Detail Keuangan',
                    'status' => $status,
                    'id' => $id,
                    'info' => Data_perjadinkegiatan::find($id),
                    "perangkats" => Fasilitas::where('data_perjadinkegiatan_id', $id)->get(),
                    'pegawais' => $pegawais,
                    'nonpegawais' => $nonpegawais,
                    "kebutuhans" => $kebutuhans,
                    "operasionals" => $operasionals,
                    "keuanganoperasionals" => $keuanganOperasional,
                    'keuangans' => Keuangan_perjadinkegiatan::where('data_perjadinkegiatan', $id)->get(),
                    "sbms" => Ref_sbm::all(),
                    'akuns' => $akuns,
                    'dokumens' => Laporan_perjadinkegiatan::where('data_perjadin_kegiatan', $id)
                                ->where('nama_dokumen', 'NOT LIKE', "lap-$id-$id-$id%")
                                ->get(),

                ]);
            }

        } else{
            return view('admin.other.koreksi.index-404', [
                'status' => $status,
            ]);
        }

    }

    public function updateKoreksi(Request $request, $status) {
        
        

        if ($status == 'perjadin') {
            $idPerjadin = $request->idPerjadin;    
            // dd($status, $idPerjadin);

            // Handle perjadin status
            DB::table('info_perjadinlangsungs')
                ->where('id', $idPerjadin)
                ->update([
                    'is_acceptKeu' => 'selesai',
                    'is_acceptBend' => 'approval-2',
                    'status_pengajuan' => 'selesai',
                    'status_pengajuan_detail' => 'Koreksi Data pada Approval-2',
                    'is_corrected' => '1',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

            return redirect()->route('koreksi', ['status' => 'perjadin'])->with('success', 'Data Perjadin '.$idPerjadin.' Telah kembali ke Approval-2 Bendahara');
        } elseif ($status == 'kegiatan') {
            // Handle kegiatan status
            $idKegiatan = $request->idKegiatan;    
            // dd($status, $idKegiatan);

            DB::table('data_perjadinkegiatans')
                ->where('id', $idKegiatan)
                ->update([
                    'status' => 'selesai',
                    'status_pengajuan' => 'selesai',
                    'is_acceptKeu' => 'selesai',
                    'is_acceptBend' => 'approval-2',
                    'status_pengajuan_detail' => 'Koreksi Data pada Approval-2',
                    'is_corrected' => '1',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);
            
            return redirect()->route('koreksi', ['status' => 'kegiatan'])->with('success', 'Data Kegiatan '.$idKegiatan.' Telah kembali ke Approval-2 Bendahara');
        } else {
            return redirect()->back()->with('error', 'Status Tipe Ajuan '.$status.' tidak ditemukan atau salah' );
        }
    }

    

    public function indexSPBY($status)
    {
        // dd($status);
        // Nonaktifkan ONLY_FULL_GROUP_BY
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");
        $kegiatanDatas = DB::table('keuangan_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
            ->join('akun_x_rkakls', 'akun_x_rkakls.id', '=', 'keuangan_perjadinkegiatans.akun_x_rkakl')
            ->join('akuns', 'akuns.id', '=', 'akun_x_rkakls.akun_id')
            ->join('ref_rkakl_sub_komponens', 'ref_rkakl_sub_komponens.id', '=', 'akun_x_rkakls.ref_sub_komponen_id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_komponens.id', '=', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_suboutputs.id', '=', 'ref_rkakl_komponens.ref_rkakl_suboutput_id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
            ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
            ->leftJoin('perangkat_acaras', 'perangkat_acaras.id', '=', 'keuangan_perjadinkegiatans.perangkat_acara')
            ->leftJoin('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
            ->leftJoin('non_pegawais', 'perangkat_acaras.non_pegawai_id', '=', 'non_pegawais.id')
            ->select(
            'keuangan_perjadinkegiatans.id as IdKeuangan',
            'keuangan_perjadinkegiatans.data_perjadinkegiatan as dataID',
            DB::raw('"Kegiatan" as tipe'),
            'data_perjadinkegiatans.tgl_mulai as tgl_transaksi',
            'keuangan_perjadinkegiatans.tgl_bayar as tgl_bayar',
            DB::raw('CONCAT(ref_rkakl_satkers.kode_satker, ".", ref_rkakl_programs.kode_program, ".", ref_rkakl_kegiatans.kode_kegiatan, ".", ref_rkakl_outputs.kode_output, ".", ref_rkakl_suboutputs.kode_sub_output, ".", ref_rkakl_komponens.kode_komponen, ".", ref_rkakl_sub_komponens.kode_sub_kegiatan, ".", akuns.kode_akun) as mak'),
            DB::raw('CONCAT(
                "Biaya perjalanan dinas Bandung",
                CASE
                WHEN data_perjadinkegiatans.kab_kota NOT LIKE "%bandung%" THEN CONCAT("-", data_perjadinkegiatans.kab_kota)
                ELSE ""
                END,
                " selama ",
                DATEDIFF(data_perjadinkegiatans.tgl_selesai, data_perjadinkegiatans.tgl_mulai) + 1,
                " hari tanggal ",
                DATE_FORMAT(data_perjadinkegiatans.tgl_mulai, "%d/%m/%Y"),
                " a.n. ",
                COALESCE(pegawais.nama_lengkap, non_pegawais.nama_lengkap),
                " untuk melaksanakan tugas ",
                data_perjadinkegiatans.nama_kegiatan
            ) as uraian'),
            DB::raw('
                CASE 
                    WHEN keuangan_perjadinkegiatans.kode = "harian" 
                        THEN COALESCE(keuangan_perjadinkegiatans.nominal_perjadin, 0) + 
                        (
                            SELECT IFNULL(SUM(k2.harga), 0) 
                            FROM keuangan_perjadinkegiatans k2
                            WHERE k2.kebutuhan_id IS NOT NULL 
                            AND k2.perangkat_acara = keuangan_perjadinkegiatans.perangkat_acara
                        )
                        WHEN keuangan_perjadinkegiatans.kode IS NULL AND keuangan_perjadinkegiatans.kebutuhan_id IS NULL AND keuangan_perjadinkegiatans.operasional IS NOT NULL
                        THEN COALESCE(keuangan_perjadinkegiatans.nominal_perjadin, 0) + 
                        (
                            SELECT IFNULL(SUM(k2.harga), 0) 
                            FROM keuangan_perjadinkegiatans k2
                            WHERE k2.kebutuhan_id IS NOT NULL 
                            AND k2.perangkat_acara = keuangan_perjadinkegiatans.perangkat_acara
                        )
                        WHEN keuangan_perjadinkegiatans.kode = "honor" 
                        THEN COALESCE(keuangan_perjadinkegiatans.jumlah_honorarium, 0)
                    WHEN keuangan_perjadinkegiatans.kode IS NULL AND keuangan_perjadinkegiatans.kebutuhan_id IS NULL 
                        THEN COALESCE(keuangan_perjadinkegiatans.nominal_perjadin, 0) 
                    ELSE 0 
                END as nominal_bruto
            '),
            DB::raw('
                CASE 
                    WHEN keuangan_perjadinkegiatans.kode = "harian" 
                        THEN COALESCE(keuangan_perjadinkegiatans.nilai_pajak, 0) + 
                        (
                            SELECT IFNULL(SUM(k2.nilai_pajak), 0) 
                            FROM keuangan_perjadinkegiatans k2
                            WHERE k2.kebutuhan_id IS NOT NULL 
                            AND k2.perangkat_acara = keuangan_perjadinkegiatans.perangkat_acara
                        )
                    WHEN keuangan_perjadinkegiatans.kode = "honor" 
                        THEN COALESCE(keuangan_perjadinkegiatans.nilai_pajak, 0) 
                    WHEN keuangan_perjadinkegiatans.kode IS NULL AND keuangan_perjadinkegiatans.kebutuhan_id IS NULL 
                        THEN COALESCE(keuangan_perjadinkegiatans.nilai_pajak, 0) 
                    ELSE 0 
                END as potongan
            '),
            'keuangan_perjadinkegiatans.spby as no_spby',
            'keuangan_perjadinkegiatans.tgl_spby as tgl_jurnal',
            )
            ->where('data_perjadinkegiatans.versi_id', session('versi'))
            ->where('data_perjadinkegiatans.is_acceptBend', 'selesai')
            ->where('data_perjadinkegiatans.status_pengajuan', 'selesai')
            ->whereNotNull('keuangan_perjadinkegiatans.perangkat_acara')
            ->whereNull('keuangan_perjadinkegiatans.kebutuhan_id')
            ->when($status == 'sudah', function ($query) {
                return $query->whereNotNull('keuangan_perjadinkegiatans.spby')
                             ->where('data_perjadinkegiatans.is_corrected', 0);
            })
            ->when($status == 'belum', function ($query) {
                return $query->whereNull('keuangan_perjadinkegiatans.spby')
                             ->where('data_perjadinkegiatans.is_corrected', 0);
            })
            ->when($status == 'koreksi', function ($query) {
                return $query->where('data_perjadinkegiatans.is_corrected', 1);
            })
            ->groupBy('keuangan_perjadinkegiatans.id')
            ->get();

        $perjadinDatas = DB::table('keuangan_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'keuangan_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->join('akun_x_rkakls', 'akun_x_rkakls.id', '=', 'keuangan_perjadinlangsungs.akun_x_rkakl')
            ->join('akuns', 'akuns.id', '=', 'akun_x_rkakls.akun_id')
            ->join('ref_rkakl_sub_komponens', 'ref_rkakl_sub_komponens.id', '=', 'akun_x_rkakls.ref_sub_komponen_id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_komponens.id', '=', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_suboutputs.id', '=', 'ref_rkakl_komponens.ref_rkakl_suboutput_id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_outputs.id', '=', 'ref_rkakl_suboutputs.ref_rkakl_output_id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_kegiatans.id', '=', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id')
            ->join('ref_rkakl_programs', 'ref_rkakl_programs.id', '=', 'ref_rkakl_kegiatans.ref_rkakl_program_id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_satkers.id', '=', 'ref_rkakl_programs.ref_rkakl_satker_id')
            ->leftJoin('data_perjadinlangsungs', 'data_perjadinlangsungs.id', '=', 'keuangan_perjadinlangsungs.data_perjadinlangsungs')
            ->leftJoin('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->leftJoin('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
            ->select(
            'keuangan_perjadinlangsungs.id as IdKeuangan',
            'keuangan_perjadinlangsungs.info_perjadinlangsung as dataID',
            DB::raw('"Perjadin" as tipe'),
            'info_perjadinlangsungs.tgl_mulai as tgl_transaksi',
            'keuangan_perjadinlangsungs.tgl_bayar as tgl_bayar',
            DB::raw('CONCAT(ref_rkakl_satkers.kode_satker, ".", ref_rkakl_programs.kode_program, ".", ref_rkakl_kegiatans.kode_kegiatan, ".", ref_rkakl_outputs.kode_output, ".", ref_rkakl_suboutputs.kode_sub_output, ".", ref_rkakl_komponens.kode_komponen, ".", ref_rkakl_sub_komponens.kode_sub_kegiatan, ".", akuns.kode_akun) as mak'),
            DB::raw('CONCAT(
                "Biaya perjalanan dinas Bandung",
                CASE
                WHEN info_perjadinlangsungs.kabupaten_kota NOT LIKE "%bandung%" THEN CONCAT("-", info_perjadinlangsungs.kabupaten_kota)
                ELSE ""
                END,
                " selama ",
                DATEDIFF(info_perjadinlangsungs.tgl_selesai, info_perjadinlangsungs.tgl_mulai) + 1,
                " hari tanggal ",
                DATE_FORMAT(info_perjadinlangsungs.tgl_mulai, "%d/%m/%Y"),
                " a.n. ",
                COALESCE(pegawais.nama_lengkap, non_pegawais.nama_lengkap),
                " untuk melaksanakan tugas ",
                info_perjadinlangsungs.nama_kegiatan
                ) as uraian'),
                DB::raw('
                    COALESCE(
                        keuangan_perjadinlangsungs.jumlah_harga, 0
                    ) + COALESCE((
                        SELECT SUM(k2.jumlah_harga) 
                        FROM keuangan_perjadinlangsungs k2
                        WHERE k2.kebutuhan_id IS NOT NULL 
                        AND k2.data_perjadinlangsungs = keuangan_perjadinlangsungs.data_perjadinlangsungs
                    ), 0) AS nominal_bruto
                '),
                DB::raw('
                    COALESCE(
                        keuangan_perjadinlangsungs.persen_pajak, 0
                    ) + COALESCE((
                        SELECT SUM(k2.persen_pajak) 
                        FROM keuangan_perjadinlangsungs k2
                        WHERE k2.kebutuhan_id IS NOT NULL 
                        AND k2.data_perjadinlangsungs = keuangan_perjadinlangsungs.data_perjadinlangsungs
                    ), 0) AS potongan
                '),

            // 'keuangan_perjadinlangsungs.jumlah_harga as nominal_bruto',
            // 'keuangan_perjadinlangsungs.persen_pajak as potongan',
            'keuangan_perjadinlangsungs.spby as no_spby',
            'keuangan_perjadinlangsungs.tgl_spby as tgl_jurnal',
            )
            ->where('info_perjadinlangsungs.versi_id', session('versi'))
            ->where('info_perjadinlangsungs.is_acceptBend', 'selesai')
            ->where('info_perjadinlangsungs.status_pengajuan', 'selesai')
            ->whereNotNull('keuangan_perjadinlangsungs.data_perjadinlangsungs')
            ->whereNull('keuangan_perjadinlangsungs.kebutuhan_id')
            ->when($status == 'sudah', function ($query) {
                return $query->whereNotNull('keuangan_perjadinlangsungs.spby')
                             ->where('info_perjadinlangsungs.is_corrected', 0);
            })
            ->when($status == 'belum', function ($query) {
                return $query->whereNull('keuangan_perjadinlangsungs.spby')
                             ->where('info_perjadinlangsungs.is_corrected', 0);
            })
            ->when($status == 'koreksi', function ($query) {
                return $query->where('info_perjadinlangsungs.is_corrected', 1);
            })
            ->get();

            // Kembalikan ONLY_FULL_GROUP_BY ke default
        DB::statement("SET SESSION sql_mode=CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY')");

        $preDatas = $perjadinDatas->merge($kegiatanDatas)
            ->sortBy('tgl_bayar')
            ->sortBy('tgl_transaksi');



        if ($status == 'sudah' || $status == 'belum' || $status == 'koreksi') {
            $datas = $preDatas;
        } else {
            $datas = [];
        }


        return view('admin.other.spby', [
            'datas' => $datas,
            'status' => $status,
        ]);
    }

    public function updateDataSPBY(Request $request)
{
    // Dekode data JSON dari hidden input
    $allData = json_decode($request->input('allData'), true);
    $status = $request->status;

    // dd($status, $allData);
    

    // Iterasi melalui setiap baris data
    foreach ($allData as $row) {
        $tipe = $row['tipe'];
        $idKeuangan = $row['idKeuangan'];
        $spby = $row['spby'];
        $tglSpby = $row['tglSpby'];
        $dataID = $row['dataID'];

        // Format tanggal jika tidak null
        $tglSpbyFormated = $tglSpby ? Carbon::createFromFormat('Y-m-d', $tglSpby)->format('Y-m-d') : null;

        // Simpan data ke database berdasarkan tipe
        if ($status == 'koreksi') {
            if ($spby && $tglSpby) {
                if ($tipe == 'Perjadin') {
                    $keuangan = Keuangan_perjadinlangsung::find($idKeuangan);
                    if ($keuangan) {
                        $keuangan->spby = $spby;
                        $keuangan->tgl_spby = $tglSpbyFormated;
                        $keuangan->jurnal = 'Sudah Jurnal';
                        $keuangan->save();
                    }
                    $dataAjuan = Info_perjadinlangsung::find($dataID);
                    if ($dataAjuan) {
                        $dataAjuan->is_corrected = 0;
                        $dataAjuan->save();
                    }
                    
                } elseif ($tipe == 'Kegiatan') {
                    $keuangan = Keuangan_perjadinkegiatan::find($idKeuangan);
                    if ($keuangan) {
                        $keuangan->spby = $spby;
                        $keuangan->tgl_spby = $tglSpbyFormated;
                        $keuangan->jurnal = 'Sudah Jurnal';
                        $keuangan->save();
                    }
                    $dataAjuan = Data_perjadinkegiatan::find($dataID);
                    if ($dataAjuan) {
                        $dataAjuan->is_corrected = 0;
                        $dataAjuan->save();
                    }
                }
            }
        } else {
            if ($spby && $tglSpby) {
                if ($tipe == 'Perjadin') {
                    $keuangan = Keuangan_perjadinlangsung::find($idKeuangan);
                    if ($keuangan) {
                        $keuangan->spby = $spby;
                        $keuangan->tgl_spby = $tglSpbyFormated;
                        $keuangan->jurnal = 'Sudah Jurnal';
                        $keuangan->save();
                    }
                } elseif ($tipe == 'Kegiatan') {
                    $keuangan = Keuangan_perjadinkegiatan::find($idKeuangan);
                    if ($keuangan) {
                        $keuangan->spby = $spby;
                        $keuangan->tgl_spby = $tglSpbyFormated;
                        $keuangan->jurnal = 'Sudah Jurnal';
                        $keuangan->save();
                    }
                }
            }
        }
        
    }

    // Redirect dengan pesan sukses
    return redirect()->route('spby', ['status' => $status])->with('success', 'Data SPBY Telah Diperbaharui!');
}

    public function indexSBM()
    {
        $sbm = DB::table('ref_sbms')
            ->where('ref_sbms.versi_id', session('versi'))
            ->get();
        return view('admin.referensi.sbm', [
            'title' => 'SBM',
            'sbms' => $sbm
        ]);
    }

    

    public function indexDetailSBM($id)
    {
        return view('admin.referensi.detail_sbm', [
            'title' => 'SBM',
            'sbm' => Ref_sbm::find($id)
        ]);
    }

    public function note_perjadin_laporan($id)
    {
        return view(
            'admin.other.laporan_perjadin',
            [
                'title' => 'Laporan Perjalanan Dinas',
                'perjadin' => Info_perjadinlangsung::find($id),
                'dokumen' => Dokumen::where('info_perjadinlangsung_id', $id)->get(),
            ]
        );
    }

    public function laporanPerjadin($mulai, $sampai)
    {
        // Nonaktifkan ONLY_FULL_GROUP_BY
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");

        $perjadin = DB::table('info_perjadinlangsungs as ip')
        ->join('data_perjadinlangsungs as dp', 'dp.info_perjadinlangsung', '=', 'ip.id')
        ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
        ->leftJoin('keuangan_perjadinlangsungs as kp', 'dp.id', '=', 'kp.data_perjadinlangsungs')
        ->join('dokumens as d', 'ip.id', '=', 'd.info_perjadinlangsung_id')
        ->leftJoin('kebutuhans as kb', 'kp.kebutuhan_id', '=', 'kb.id')
        ->leftJoin('surtug_perjadinlangsungs as sp', 'ip.id', '=', 'sp.id_perjadinlangsung')
        ->whereBetween('ip.tgl_mulai', [$mulai, $sampai])
        ->select(
            DB::raw('ip.tgl_mulai, ip.tgl_selesai'),
            'dp.id as dataPerjadinId',
            'ip.id as idPerjadin',
            'ip.kabupaten_kota as Kota',
            DB::raw('CONCAT(DATEDIFF(ip.tgl_selesai, ip.tgl_mulai) + 1, " Hari") as Jumlah_Hari'),
            DB::raw('CONCAT(DATE_FORMAT(STR_TO_DATE(ip.tgl_mulai, "%Y-%m-%d"), "%e %M %Y"), " s.d ", DATE_FORMAT(STR_TO_DATE(ip.tgl_selesai, "%Y-%m-%d"), "%e %M %Y")) as Tanggal_Perjadin'),
            'p.nama_lengkap as Nama',
            'ip.nama_kegiatan as Kegiatan',
            'sp.nomor_surat as No_Surtug',
            DB::raw('COALESCE(SUM(CASE
                    WHEN kb.nama = "BBM" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tol" THEN kp.jumlah_harga
                    WHEN kb.nama = "Akomodasi Hotel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Transportasi Online" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Pesawat" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Kereta" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Travel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Lainnya" THEN kp.jumlah_harga ELSE 0
                END), 0) +
                COALESCE(kp.uang_harian, 0) +
                COALESCE(kp.uang_harian_fullday, 0) +
                COALESCE(kp.uang_harian_fullboard, 0) +
                COALESCE(kp.uang_representasi, 0) as Nominal_Bayar'),
            'd.created_at as Tgl_Terima_Berkas',
            'd.updated_at as Tgl_Berkas_Lengkap',
            'sp.tgl_surat_dibuat as Tgl_Surtug',
            'd.surat_undangan as Undangan',
            'd.surat_tugas as Surat_Tugas',
            'd.sppd as SPPD',
            'd.hasil as Laporan_Perjadin',
            'd.lap_pengeluaran as Bukti_Pengeluaran',
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "BBM" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as BBM'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Tol" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as e_Toll'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Akomodasi Hotel" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Penginapan'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Transportasi Online" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Transportasi'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Tiket Pesawat" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Pesawat'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Tiket Kereta" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Kereta'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Tiket Travel" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Travel'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Lainnya" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Fasilitas_Lainnya'),
            DB::raw('(SELECT CONCAT(rs.kode_satker, ".", rp.kode_program, ".", rk.kode_kegiatan, ".", ro.kode_output, ".", rso.kode_sub_output, ".", rc.kode_komponen, ".", rsc.kode_sub_kegiatan, ".", a.kode_akun)
                FROM akun_x_rkakls axr
                JOIN akuns a ON axr.akun_id = a.id
                JOIN ref_rkakl_sub_komponens rsc ON axr.ref_sub_komponen_id = rsc.id
                JOIN ref_rkakl_komponens rc ON rsc.ref_rkakl_komponen_id = rc.id
                JOIN ref_rkakl_suboutputs rso ON rc.ref_rkakl_suboutput_id = rso.id
                JOIN ref_rkakl_outputs ro ON rso.ref_rkakl_output_id = ro.id
                JOIN ref_rkakl_kegiatans rk ON ro.ref_rkakl_kegiatan_id = rk.id
                JOIN ref_rkakl_programs rp ON rk.ref_rkakl_program_id = rp.id
                JOIN ref_rkakl_satkers rs ON rp.ref_rkakl_satker_id = rs.id
                WHERE axr.id = kp.akun_x_rkakl LIMIT 1) as MAK'),
            DB::raw('DATE_FORMAT(kp.tgl_bayar, "%d-%m-%Y") as Tanggal_Pembayaran'),
            'kp.uang_harian as Uang_Harian',
            'kp.uang_harian_fullday as Uang_Fullday',
            'kp.uang_harian_fullboard as Uang_Fullboard',
            'kp.uang_representasi as Uang_Representasi',
            'kp.persen_pajak as Pph21',
            'kp.pph22 as Pph22',
            'kp.pph23 as Pph23',
            'kp.ppn as PPN',
            DB::raw('COALESCE(SUM(CASE
                    WHEN kb.nama = "BBM" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tol" THEN kp.jumlah_harga
                    WHEN kb.nama = "Akomodasi Hotel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Transportasi Online" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Pesawat" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Kereta" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Travel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Lainnya" THEN kp.jumlah_harga
                    ELSE 0
                END), 0) +
                COALESCE(kp.uang_harian, 0) +
                COALESCE(kp.uang_harian_fullday, 0) +
                COALESCE(kp.uang_harian_fullboard, 0) +
                COALESCE(kp.uang_representasi, 0) as Total_Pembayaran'),
            'kp.status as Status_Pembayaran',
            'd.updated_at AS Tanggal_Update_Laporan',
            'ip.status_pengajuan AS Status_Perjalanan_Dinas',
            'kp.tgl_kwitansi AS Tanggal_Kwitansi',
            'kp.no_kwitansi AS No_Kwitansi',
            'kp.tgl_spby AS Tanggal_SPBY',
            'kp.spby AS SPBY',
            'kp.jurnal AS Jurnal',
            'kp.drpp AS DRPP',
            'kp.id AS IdKeuangan'
        )
        ->groupBy('dp.id')
        ->orderBy('ip.id')
        ->get();

        $perjadinNon = DB::table('info_perjadinlangsungs as ip')
        ->join('data_perjadinlangsungs as dp', 'dp.info_perjadinlangsung', '=', 'ip.id')
        ->join('non_pegawais as p', 'dp.non_pegawai_id', '=', 'p.id')
        ->leftJoin('keuangan_perjadinlangsungs as kp', 'dp.id', '=', 'kp.data_perjadinlangsungs')
        ->join('dokumens as d', 'ip.id', '=', 'd.info_perjadinlangsung_id')
        ->leftJoin('kebutuhans as kb', 'kp.kebutuhan_id', '=', 'kb.id')
        ->leftJoin('surtug_perjadinlangsungs as sp', 'ip.id', '=', 'sp.id_perjadinlangsung')
        ->whereBetween('ip.tgl_mulai', [$mulai, $sampai])
        ->select(
            DB::raw('ip.tgl_mulai, ip.tgl_selesai'),
            'dp.id as dataPerjadinId',
            'ip.id as idPerjadin',
            'ip.kabupaten_kota as Kota',
            DB::raw('CONCAT(DATEDIFF(ip.tgl_selesai, ip.tgl_mulai) + 1, " Hari") as Jumlah_Hari'),
            DB::raw('CONCAT(DATE_FORMAT(STR_TO_DATE(ip.tgl_mulai, "%Y-%m-%d"), "%e %M %Y"), " s.d ", DATE_FORMAT(STR_TO_DATE(ip.tgl_selesai, "%Y-%m-%d"), "%e %M %Y")) as Tanggal_Perjadin'),
            'p.nama_lengkap as Nama',
            'ip.nama_kegiatan as Kegiatan',
            'sp.nomor_surat as No_Surtug',
            DB::raw('COALESCE(SUM(CASE
                    WHEN kb.nama = "BBM" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tol" THEN kp.jumlah_harga
                    WHEN kb.nama = "Akomodasi Hotel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Transportasi Online" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Pesawat" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Kereta" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Travel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Lainnya" THEN kp.jumlah_harga ELSE 0
                END), 0) +
                COALESCE(kp.uang_harian, 0) +
                COALESCE(kp.uang_harian_fullday, 0) +
                COALESCE(kp.uang_harian_fullboard, 0) +
                COALESCE(kp.uang_representasi, 0) as Nominal_Bayar'),
            'd.created_at as Tgl_Terima_Berkas',
            'd.updated_at as Tgl_Berkas_Lengkap',
            'sp.tgl_surat_dibuat as Tgl_Surtug',
            'd.surat_undangan as Undangan',
            'd.surat_tugas as Surat_Tugas',
            'd.sppd as SPPD',
            'd.hasil as Laporan_Perjadin',
            'd.lap_pengeluaran as Bukti_Pengeluaran',
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "BBM" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as BBM'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Tol" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as e_Toll'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Akomodasi Hotel" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Penginapan'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Transportasi Online" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Transportasi'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Tiket Pesawat" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Pesawat'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Tiket Kereta" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Kereta'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Tiket Travel" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Travel'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Lainnya" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Fasilitas_Lainnya'),
            DB::raw('(SELECT CONCAT(rs.kode_satker, ".", rp.kode_program, ".", rk.kode_kegiatan, ".", ro.kode_output, ".", rso.kode_sub_output, ".", rc.kode_komponen, ".", rsc.kode_sub_kegiatan, ".", a.kode_akun)
                FROM akun_x_rkakls axr
                JOIN akuns a ON axr.akun_id = a.id
                JOIN ref_rkakl_sub_komponens rsc ON axr.ref_sub_komponen_id = rsc.id
                JOIN ref_rkakl_komponens rc ON rsc.ref_rkakl_komponen_id = rc.id
                JOIN ref_rkakl_suboutputs rso ON rc.ref_rkakl_suboutput_id = rso.id
                JOIN ref_rkakl_outputs ro ON rso.ref_rkakl_output_id = ro.id
                JOIN ref_rkakl_kegiatans rk ON ro.ref_rkakl_kegiatan_id = rk.id
                JOIN ref_rkakl_programs rp ON rk.ref_rkakl_program_id = rp.id
                JOIN ref_rkakl_satkers rs ON rp.ref_rkakl_satker_id = rs.id
                WHERE axr.id = kp.akun_x_rkakl LIMIT 1) as MAK'),
            DB::raw('DATE_FORMAT(kp.tgl_bayar, "%d-%m-%Y") as Tanggal_Pembayaran'),
            'kp.uang_harian as Uang_Harian',
            'kp.uang_harian_fullday as Uang_Fullday',
            'kp.uang_harian_fullboard as Uang_Fullboard',
            'kp.uang_representasi as Uang_Representasi',
            'kp.persen_pajak as Pph21',
            'kp.pph22 as Pph22',
            'kp.pph23 as Pph23',
            'kp.ppn as PPN',
            DB::raw('COALESCE(SUM(CASE
                    WHEN kb.nama = "BBM" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tol" THEN kp.jumlah_harga
                    WHEN kb.nama = "Akomodasi Hotel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Transportasi Online" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Pesawat" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Kereta" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Travel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Lainnya" THEN kp.jumlah_harga
                    ELSE 0
                END), 0) +
                COALESCE(kp.uang_harian, 0) +
                COALESCE(kp.uang_harian_fullday, 0) +
                COALESCE(kp.uang_harian_fullboard, 0) +
                COALESCE(kp.uang_representasi, 0) as Total_Pembayaran'),
            'kp.status as Status_Pembayaran',
            'd.updated_at AS Tanggal_Update_Laporan',
            'ip.status_pengajuan AS Status_Perjalanan_Dinas',
            'kp.tgl_kwitansi AS Tanggal_Kwitansi',
            'kp.no_kwitansi AS No_Kwitansi',
            'kp.tgl_spby AS Tanggal_SPBY',
            'kp.spby AS SPBY',
            'kp.jurnal AS Jurnal',
            'kp.drpp AS DRPP',
            'kp.id AS IdKeuangan'
        )
        ->groupBy('dp.id')
        ->orderBy('ip.id')
        ->get();

        // dd($perjadin);
        // Kembalikan ONLY_FULL_GROUP_BY ke default
        DB::statement("SET SESSION sql_mode=CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY')");

        foreach ($perjadin as $row) {
            $row->Tanggal_Perjadin = Carbon::parse($row->tgl_mulai)->translatedFormat('d F Y') . ' s.d ' . Carbon::parse($row->tgl_selesai)->translatedFormat('d F Y');
        }
        foreach ($perjadinNon as $row) {
            $row->Tanggal_Perjadin = Carbon::parse($row->tgl_mulai)->translatedFormat('d F Y') . ' s.d ' . Carbon::parse($row->tgl_selesai)->translatedFormat('d F Y');
        }
    // Ambil id untuk HKT selesai
    $HKTselesaiId = DB::table('info_perjadinlangsungs')
        ->where('is_acceptHKT', 'selesai')
        ->pluck('id');

    // Ambil id perjadinlangsung dari surtug_perjadinlangsungs
    $surtugExist = DB::table('surtug_perjadinlangsungs')
        ->whereIn('id_perjadinlangsung', $perjadin->pluck('idPerjadin'))
        ->pluck('id_perjadinlangsung');





        $akuns = DB::table('akun_x_rkakls')
            ->join('akuns', 'akun_x_rkakls.akun_id', '=', 'akuns.id')
            ->join('ref_rkakl_sub_komponens', 'akun_x_rkakls.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
            ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
            ->select('akun_x_rkakls.id as idAkun', 'ref_rkakl_sub_komponens.nama_sub_kegiatan', 'akuns.uraian', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program', 'ref_rkakl_kegiatans.kode_kegiatan', 'ref_rkakl_outputs.kode_output', 'ref_rkakl_suboutputs.kode_sub_output', 'ref_rkakl_komponens.kode_komponen', 'ref_rkakl_sub_komponens.kode_sub_kegiatan', 'ref_rkakl_sub_komponens.nama_sub_kegiatan', 'akuns.kode_akun', 'akuns.uraian')
            ->get();
        $fasilitas = DB::table('kebutuhans')

            ->join('keuangan_perjadinlangsungs', 'kebutuhans.id', '=', 'keuangan_perjadinlangsungs.kebutuhan_id')
            ->select('kebutuhans.id',  'keuangan_perjadinlangsungs.kebutuhan_id','kebutuhans.nama', 'kebutuhans.satuan', 'kebutuhans.jumlah_frekuensi',  'kebutuhans.satuan', 'keuangan_perjadinlangsungs.jumlah_harga', 'keuangan_perjadinlangsungs.info_perjadinlangsung', 'keuangan_perjadinlangsungs.status',  'keuangan_perjadinlangsungs.data_perjadinlangsungs' )

            ->get();

        $kebutuhan = DB::table('keuangan_perjadinlangsungs')
        ->join('kebutuhans', 'keuangan_perjadinlangsungs.kebutuhan_id', '=', 'kebutuhans.id')
        ->select('kebutuhans.id AS idKebutuhan',  'keuangan_perjadinlangsungs.kebutuhan_id','kebutuhans.nama', 'kebutuhans.satuan', 'kebutuhans.jumlah_frekuensi',  'kebutuhans.satuan', 'keuangan_perjadinlangsungs.jumlah_harga', 'keuangan_perjadinlangsungs.info_perjadinlangsung', 'keuangan_perjadinlangsungs.status',  'keuangan_perjadinlangsungs.data_perjadinlangsungs' )
        ->get();

        $dibayarkan = DB::table('keuangan_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'keuangan_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->where('keuangan_perjadinlangsungs.status', 'Sudah Dibayarkan')
            ->whereBetween('info_perjadinlangsungs.tgl_mulai', [$mulai, $sampai])
            ->count();
        $totaldibayarkan = DB::table('keuangan_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'keuangan_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->where('keuangan_perjadinlangsungs.status', 'Sudah Dibayarkan')
            ->whereBetween('info_perjadinlangsungs.tgl_mulai', [$mulai, $sampai])
            ->sum('keuangan_perjadinlangsungs.jumlah_harga');
        $tdkdibayarkan = DB::table('keuangan_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'keuangan_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->where('keuangan_perjadinlangsungs.status', 'Tidak Dibayarkan')
            ->whereBetween('info_perjadinlangsungs.tgl_mulai', [$mulai, $sampai])
            ->count();
        $totaltdkdibayarkan = DB::table('keuangan_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'keuangan_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->where('keuangan_perjadinlangsungs.status', 'Tidak Dibayarkan')
            ->whereBetween('info_perjadinlangsungs.tgl_mulai', [$mulai, $sampai])
            ->sum('keuangan_perjadinlangsungs.jumlah_harga');
        $blmdibayarkan = DB::table('keuangan_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'keuangan_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->where('keuangan_perjadinlangsungs.status','!=', 'Sudah Dibayarkan')
            ->where('keuangan_perjadinlangsungs.status','!=', 'Tidak Dibayarkan')
            ->whereBetween('info_perjadinlangsungs.tgl_mulai', [$mulai, $sampai])
            ->count();
        $totalblmdibayarkan = DB::table('keuangan_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'keuangan_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->where('keuangan_perjadinlangsungs.status','!=', 'Sudah Dibayarkan')
            ->where('keuangan_perjadinlangsungs.status','!=', 'Tidak Dibayarkan')
            ->whereBetween('info_perjadinlangsungs.tgl_mulai', [$mulai, $sampai])
            ->sum('keuangan_perjadinlangsungs.jumlah_harga');
        // dd($perjadin, $fasilitas);
        return view('admin.other.perjadin', [
            'title' => 'Laporan Perjalanan Dinas',
            'perjadins' => $perjadin,
            'perjadinNons' => $perjadinNon,
            'akuns' => $akuns,
            'mulai' => $mulai,
            'sampai'=> $sampai,
            'fasilitas' => $fasilitas,
            'kebutuhan' => $kebutuhan,
            'blmDibayarkan' => $blmdibayarkan,
            'tdkDibayarkan' => $tdkdibayarkan,
            'dibayarkan' => $dibayarkan,
            'totaldibayarkan' => $totaldibayarkan,
            'totaltdkdibayarkan' => $totaltdkdibayarkan,
            'totalblmdibayarkan' => $totalblmdibayarkan,
            'surtugs' => DB::table('surtug_perjadinlangsungs')->get(), // Mengambil semua data dari surtug_perjadinlangsungs
            'surtugExist' => $surtugExist,
        ]);
    }

    public function laporanKegiatan($mulai, $sampai)
    {

        // Nonaktifkan ONLY_FULL_GROUP_BY
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");

        $kegiatans = DB::table('data_perjadinkegiatans as ip')
        ->join('perangkat_acaras as dp', 'dp.data_perjadin_kegiatan', '=', 'ip.id')
        ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
        ->leftJoin('keuangan_perjadinkegiatans as kp', 'dp.id', '=', 'kp.perangkat_acara')
        ->join('laporan_perjadinkegiatans as d', 'ip.id', '=', 'd.data_perjadin_kegiatan')
        ->leftJoin('kebutuhans as kb', 'kp.kebutuhan_id', '=', 'kb.id')
        ->leftJoin('surtug_perjadinkegiatans as sp', 'ip.id', '=', 'sp.data_perjadin_kegiatan')
        ->whereBetween('ip.tgl_mulai', [$mulai, $sampai])
        ->select(
            DB::raw('ip.tgl_mulai, ip.tgl_selesai'),
            'dp.id as dataPerangkatId',
            'ip.id as idKegiatan',
            'ip.kab_kota as Kota',
            DB::raw('CONCAT(DATEDIFF(ip.tgl_selesai, ip.tgl_mulai) + 1, " Hari") as Jumlah_Hari'),
            DB::raw('CONCAT(DATE_FORMAT(STR_TO_DATE(ip.tgl_mulai, "%Y-%m-%d"), "%e %M %Y"), " s.d ", DATE_FORMAT(STR_TO_DATE(ip.tgl_selesai, "%Y-%m-%d"), "%e %M %Y")) as Tanggal_kegiatan'),
            'p.nama_lengkap as Nama',
            'ip.nama_kegiatan as Kegiatan',
            'sp.nomor_surat as No_Surtug',
            DB::raw('COALESCE(SUM(CASE
                    WHEN kb.nama = "BBM" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tol" THEN kp.jumlah_harga
                    WHEN kb.nama = "Akomodasi Hotel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Transportasi Online" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Pesawat" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Kereta" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Travel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Lainnya" THEN kp.jumlah_harga ELSE 0
                END), 0) +
                COALESCE(kp.uang_harian, 0) +
                COALESCE(kp.uang_harian_fullday, 0) +
                COALESCE(kp.uang_harian_fullboard, 0) +
                COALESCE(kp.uang_representasi, 0) as Nominal_Bayar'),
            'd.created_at as Tgl_Terima_Berkas',
            'd.updated_at as Tgl_Berkas_Lengkap',
            DB::raw('CONCAT(dp.posisi, " (" , dp.sebagai,")") as Sebagai'),
            'sp.tgl_surat_dibuat as Tgl_Surtug',
            DB::raw(
                'GROUP_CONCAT(
                    JSON_OBJECT(
                        "nama_dokumen", d.nama_dokumen,
                        "file", d.file
                    ) SEPARATOR ","
                ) AS Dokumen_Pendukung'
            ),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama IS NOT NULL THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Fasilitas_Pendukung'),
            DB::raw('(SELECT CONCAT(rs.kode_satker, ".", rp.kode_program, ".", rk.kode_kegiatan, ".", ro.kode_output, ".", rso.kode_sub_output, ".", rc.kode_komponen, ".", rsc.kode_sub_kegiatan, ".", a.kode_akun)
                FROM akun_x_rkakls axr
                JOIN akuns a ON axr.akun_id = a.id
                JOIN ref_rkakl_sub_komponens rsc ON axr.ref_sub_komponen_id = rsc.id
                JOIN ref_rkakl_komponens rc ON rsc.ref_rkakl_komponen_id = rc.id
                JOIN ref_rkakl_suboutputs rso ON rc.ref_rkakl_suboutput_id = rso.id
                JOIN ref_rkakl_outputs ro ON rso.ref_rkakl_output_id = ro.id
                JOIN ref_rkakl_kegiatans rk ON ro.ref_rkakl_kegiatan_id = rk.id
                JOIN ref_rkakl_programs rp ON rk.ref_rkakl_program_id = rp.id
                JOIN ref_rkakl_satkers rs ON rp.ref_rkakl_satker_id = rs.id
                WHERE axr.id = kp.akun_x_rkakl LIMIT 1) as MAK'),
            DB::raw('DATE_FORMAT(kp.tgl_bayar, "%d-%m-%Y") as Tanggal_Pembayaran'),
            'kp.uang_harian as Uang_Harian',
            'kp.uang_harian_fullday as Uang_Fullday',
            'kp.uang_harian_fullboard as Uang_Fullboard',
            'kp.uang_representasi as Uang_Representasi',
            'kp.persen_pajak as Pph21',
            'kp.pph22 as Pph22',
            'kp.pph23 as Pph23',
            'kp.ppn as PPN',
            DB::raw('COALESCE(SUM(CASE
                    WHEN kb.nama = "BBM" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tol" THEN kp.jumlah_harga
                    WHEN kb.nama = "Akomodasi Hotel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Transportasi Online" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Pesawat" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Kereta" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Travel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Lainnya" THEN kp.jumlah_harga
                    ELSE 0
                END), 0) +
                COALESCE(kp.uang_harian, 0) +
                COALESCE(kp.uang_harian_fullday, 0) +
                COALESCE(kp.uang_harian_fullboard, 0) +
                COALESCE(kp.uang_representasi, 0) as Total_Pembayaran'),
            'kp.status as Status_Pembayaran',
            'd.updated_at AS Tanggal_Update_Laporan',
            'ip.status_pengajuan AS Status_Kegiatan',
            'kp.tgl_kwitansi AS Tanggal_Kwitansi',
            'kp.no_kwitansi AS No_Kwitansi',
            'kp.tgl_spby AS Tanggal_SPBY',
            'kp.spby AS SPBY',
            'kp.jurnal AS Jurnal',
            'kp.drpp AS DRPP',
            'kp.id AS IdKeuangan'
        )
        ->groupBy('dp.id')
        ->orderBy('ip.id')
        ->get();

        $kegiatansNon = DB::table('data_perjadinkegiatans as ip')
        ->join('perangkat_acaras as dp', 'dp.data_perjadin_kegiatan', '=', 'ip.id')
        ->join('non_pegawais as p', 'dp.non_pegawai_id', '=', 'p.id')
        ->leftJoin('keuangan_perjadinkegiatans as kp', 'dp.id', '=', 'kp.perangkat_acara')
        ->join('laporan_perjadinkegiatans as d', 'ip.id', '=', 'd.data_perjadin_kegiatan')
        ->leftJoin('kebutuhans as kb', 'kp.kebutuhan_id', '=', 'kb.id')
        ->leftJoin('surtug_perjadinkegiatans as sp', 'ip.id', '=', 'sp.data_perjadin_kegiatan')
        ->whereBetween('ip.tgl_mulai', [$mulai, $sampai])
        ->select(
            DB::raw('ip.tgl_mulai, ip.tgl_selesai'),
            'dp.id as dataPerangkatId',
            'ip.id as idKegiatan',
            'ip.kab_kota as Kota',
            DB::raw('CONCAT(DATEDIFF(ip.tgl_selesai, ip.tgl_mulai) + 1, " Hari") as Jumlah_Hari'),
            DB::raw('CONCAT(DATE_FORMAT(STR_TO_DATE(ip.tgl_mulai, "%Y-%m-%d"), "%e %M %Y"), " s.d ", DATE_FORMAT(STR_TO_DATE(ip.tgl_selesai, "%Y-%m-%d"), "%e %M %Y")) as Tanggal_kegiatan'),
            'p.nama_lengkap as Nama',
            'ip.nama_kegiatan as Kegiatan',
            'sp.nomor_surat as No_Surtug',
            DB::raw('COALESCE(SUM(CASE
                    WHEN kb.nama = "BBM" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tol" THEN kp.jumlah_harga
                    WHEN kb.nama = "Akomodasi Hotel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Transportasi Online" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Pesawat" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Kereta" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Travel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Lainnya" THEN kp.jumlah_harga ELSE 0
                END), 0) +
                COALESCE(kp.uang_harian, 0) +
                COALESCE(kp.uang_harian_fullday, 0) +
                COALESCE(kp.uang_harian_fullboard, 0) +
                COALESCE(kp.uang_representasi, 0) as Nominal_Bayar'),
            'd.created_at as Tgl_Terima_Berkas',
            'd.updated_at as Tgl_Berkas_Lengkap',
            DB::raw('CONCAT(dp.posisi, " (" , dp.sebagai,")") as Sebagai'),
            'sp.tgl_surat_dibuat as Tgl_Surtug',
            DB::raw(
                'GROUP_CONCAT(
                    JSON_OBJECT(
                        "nama_dokumen", d.nama_dokumen,
                        "file", d.file
                    ) SEPARATOR ","
                ) AS Dokumen_Pendukung'
            ),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama IS NOT NULL THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Fasilitas_Pendukung'),
            DB::raw('(SELECT CONCAT(rs.kode_satker, ".", rp.kode_program, ".", rk.kode_kegiatan, ".", ro.kode_output, ".", rso.kode_sub_output, ".", rc.kode_komponen, ".", rsc.kode_sub_kegiatan, ".", a.kode_akun)
                FROM akun_x_rkakls axr
                JOIN akuns a ON axr.akun_id = a.id
                JOIN ref_rkakl_sub_komponens rsc ON axr.ref_sub_komponen_id = rsc.id
                JOIN ref_rkakl_komponens rc ON rsc.ref_rkakl_komponen_id = rc.id
                JOIN ref_rkakl_suboutputs rso ON rc.ref_rkakl_suboutput_id = rso.id
                JOIN ref_rkakl_outputs ro ON rso.ref_rkakl_output_id = ro.id
                JOIN ref_rkakl_kegiatans rk ON ro.ref_rkakl_kegiatan_id = rk.id
                JOIN ref_rkakl_programs rp ON rk.ref_rkakl_program_id = rp.id
                JOIN ref_rkakl_satkers rs ON rp.ref_rkakl_satker_id = rs.id
                WHERE axr.id = kp.akun_x_rkakl LIMIT 1) as MAK'),
            DB::raw('DATE_FORMAT(kp.tgl_bayar, "%d-%m-%Y") as Tanggal_Pembayaran'),
            'kp.uang_harian as Uang_Harian',
            'kp.uang_harian_fullday as Uang_Fullday',
            'kp.uang_harian_fullboard as Uang_Fullboard',
            'kp.uang_representasi as Uang_Representasi',
            'kp.persen_pajak as Pph21',
            'kp.pph22 as Pph22',
            'kp.pph23 as Pph23',
            'kp.ppn as PPN',
            DB::raw('COALESCE(SUM(CASE
                    WHEN kb.nama = "BBM" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tol" THEN kp.jumlah_harga
                    WHEN kb.nama = "Akomodasi Hotel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Transportasi Online" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Pesawat" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Kereta" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Travel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Lainnya" THEN kp.jumlah_harga
                    ELSE 0
                END), 0) +
                COALESCE(kp.uang_harian, 0) +
                COALESCE(kp.uang_harian_fullday, 0) +
                COALESCE(kp.uang_harian_fullboard, 0) +
                COALESCE(kp.uang_representasi, 0) as Total_Pembayaran'),
            'kp.status as Status_Pembayaran',
            'd.updated_at AS Tanggal_Update_Laporan',
            'ip.status_pengajuan AS Status_Kegiatan',
            'kp.tgl_kwitansi AS Tanggal_Kwitansi',
            'kp.no_kwitansi AS No_Kwitansi',
            'kp.tgl_spby AS Tanggal_SPBY',
            'kp.spby AS SPBY',
            'kp.jurnal AS Jurnal',
            'kp.drpp AS DRPP',
            'kp.id AS IdKeuangan'
        )
        ->groupBy('dp.id')
        ->orderBy('ip.id')
        ->get();

        // Kembalikan ONLY_FULL_GROUP_BY ke default
        DB::statement("SET SESSION sql_mode=CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY')");

        // Decode JSON string menjadi array
        foreach ($kegiatans as $kegiatan) {
            if ($kegiatan->Dokumen_Pendukung) {
                $kegiatan->dokumen_list = json_decode('[' . $kegiatan->Dokumen_Pendukung . ']', true);
            } else {
                $kegiatan->dokumen_list = [];
            }
        }

        // Decode JSON string menjadi array
        foreach ($kegiatansNon as $kegiatan) {
            if ($kegiatan->Dokumen_Pendukung) {
                $kegiatan->dokumen_list = json_decode('[' . $kegiatan->Dokumen_Pendukung . ']', true);
            } else {
                $kegiatan->dokumen_list = [];
            }
        }

            $kegiatanIds = $kegiatans->pluck('id');


        $HKTselesaiId = DB::table('data_perjadinkegiatans')
            ->where('is_acceptHKT', 'selesai')
            ->pluck('id');


        $surtugExist = DB::table('surtug_perjadinkegiatans')
            ->whereIn('data_perjadin_kegiatan', $kegiatanIds)
            ->pluck('data_perjadin_kegiatan');


        foreach ($kegiatans as $info) {

            $tglMulai = \Carbon\Carbon::parse($info->tgl_mulai)->startOfDay();
            $tglSelesai = \Carbon\Carbon::parse($info->tgl_selesai)->endOfDay();

            $jumlahHari = $tglMulai->diffInDays($tglSelesai) + 1;

            $info->jumlah_hari = $jumlahHari;
            $info->dokumen = DB::table('laporan_perjadinkegiatans')
                ->where('data_perjadin_kegiatan', $info->idKegiatan)
                ->get();
        }

        $akuns = DB::table('akun_x_rkakls')
            ->join('akuns', 'akun_x_rkakls.akun_id', '=', 'akuns.id')
            ->join('ref_rkakl_sub_komponens', 'akun_x_rkakls.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
            ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
            ->select('akun_x_rkakls.id as idAkun', 'ref_rkakl_sub_komponens.nama_sub_kegiatan', 'akuns.uraian', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program', 'ref_rkakl_kegiatans.kode_kegiatan', 'ref_rkakl_outputs.kode_output', 'ref_rkakl_suboutputs.kode_sub_output', 'ref_rkakl_komponens.kode_komponen', 'ref_rkakl_sub_komponens.kode_sub_kegiatan', 'ref_rkakl_sub_komponens.nama_sub_kegiatan', 'akuns.kode_akun', 'akuns.uraian')
            ->get();
        $operasionals = DB::table('operasionals')
            ->join('keuangan_perjadinkegiatans', 'operasionals.id', '=', 'keuangan_perjadinkegiatans.operasional')
            ->select('operasionals.nama', 'operasionals.jumlah_frekuensi', 'operasionals.satuan', 'operasionals.detail_satuan', 'keuangan_perjadinkegiatans.jumlah_harga', 'keuangan_perjadinkegiatans.status', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
            ->get();
        $dibayarkan = DB::table('keuangan_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
            ->where('keuangan_perjadinkegiatans.status', 'Sudah Dibayarkan')
            ->whereBetween('data_perjadinkegiatans.tgl_mulai', [$mulai, $sampai])
            ->count();
        $totaldibayarkan = DB::table('keuangan_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
            ->where('keuangan_perjadinkegiatans.status', 'Sudah Dibayarkan')
            ->whereBetween('data_perjadinkegiatans.tgl_mulai', [$mulai, $sampai])
            ->sum('keuangan_perjadinkegiatans.jumlah_harga');
        $blmdibayarkan = DB::table('keuangan_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
            ->where('keuangan_perjadinkegiatans.status', 'Belum Dibayarkan')
            ->whereBetween('data_perjadinkegiatans.tgl_mulai', [$mulai, $sampai])
            ->count();
        $totalblmdibayarkan = DB::table('keuangan_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
            ->where('keuangan_perjadinkegiatans.status', 'Belum Dibayarkan')
            ->whereBetween('data_perjadinkegiatans.tgl_mulai', [$mulai, $sampai])
            ->sum('keuangan_perjadinkegiatans.jumlah_harga');
        $tdkdibayarkan = DB::table('keuangan_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
            ->where('keuangan_perjadinkegiatans.status', 'Tidak Dibayarkan')
            ->whereBetween('data_perjadinkegiatans.tgl_mulai', [$mulai, $sampai])
            ->count();
        $totaltdkdibayarkan = DB::table('keuangan_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
            ->where('keuangan_perjadinkegiatans.status', 'Tidak Dibayarkan')
            ->whereBetween('data_perjadinkegiatans.tgl_mulai', [$mulai, $sampai])
            ->sum('keuangan_perjadinkegiatans.jumlah_harga');

        $kebutuhan = DB::table('keuangan_perjadinkegiatans')
            ->join('kebutuhans', 'keuangan_perjadinkegiatans.kebutuhan_id', '=', 'kebutuhans.id')
            ->select('kebutuhans.id AS idKebutuhan',  'keuangan_perjadinkegiatans.kebutuhan_id','kebutuhans.nama', 'kebutuhans.satuan', 'kebutuhans.jumlah_frekuensi',  'kebutuhans.satuan', 'keuangan_perjadinkegiatans.jumlah_harga', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', 'keuangan_perjadinkegiatans.status',  'keuangan_perjadinkegiatans.perangkat_acara' )
            ->get();
        // ddd($kegiatans);
        return view('admin.other.kegiatan', [
            'title' => 'Laporan Kegiatan',
            'kegiatans' => $kegiatans,
            'kegiatanNons' => $kegiatansNon,
            'akuns' => $akuns,
            'mulai' => $mulai,
            'sampai'=> $sampai,
            'laporans' => Laporan_perjadinkegiatan::all(),
            'operasionals' => $operasionals,
            'dibayarkan' => $dibayarkan,
            'blmdibayarkan' => $blmdibayarkan,
            'tdkdibayarkan' => $tdkdibayarkan,
            'totaldibayarkan' => $totaldibayarkan,
            'totalblmdibayarkan' => $totalblmdibayarkan,
            'totaltdkdibayarkan' => $totaltdkdibayarkan,
            'surtugs' => DB::table('surtug_perjadinkegiatans')->get(),
            'surtugExist' => $surtugExist,
            'kebutuhan' => $kebutuhan
        ]);
    }

    public function laporanBMN($mulai, $sampai)
    {
        $permohonan_admin = DB::table('permohonans')
            ->join('assets', 'permohonans.asset_id', '=', 'assets.id')
            ->join('administrators', 'permohonans.admin', '=', 'administrators.id')
            ->join('data_penyedias', 'permohonans.data_penyedia_id', '=', 'data_penyedias.id')
            ->select('permohonans.id AS idPermohonan', 'permohonans.service_id AS idService', 'assets.nama_barang', 'administrators.username', 'permohonans.akun_x_rkakl_id', 'permohonans.tgl_permohonan', 'permohonans.tgl_pemeriksaan', 'permohonans.tgl_pengerjaan', 'permohonans.tgl_selesai', 'permohonans.nominal', 'permohonans.pph', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn', 'permohonans.tgl_bayar', 'permohonans.total', 'data_penyedias.nama_CV', 'permohonans.status')
            ->whereBetween('permohonans.tgl_selesai', [$mulai, $sampai])
            ->get();
        $permohonan_pegawai = DB::table('permohonans')
            ->join('assets', 'permohonans.asset_id', '=', 'assets.id')
            ->join('pegawais', 'permohonans.data_penanggungjawab_id', '=', 'pegawais.id')
            ->join('data_penyedias', 'permohonans.data_penyedia_id', '=', 'data_penyedias.id')
            ->select('permohonans.id AS idPermohonan', 'permohonans.service_id AS idService', 'assets.nama_barang', 'pegawais.nama_lengkap', 'permohonans.akun_x_rkakl_id', 'permohonans.tgl_permohonan', 'permohonans.tgl_pemeriksaan', 'permohonans.tgl_pengerjaan', 'permohonans.tgl_selesai', 'permohonans.nominal', 'permohonans.pph', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn', 'permohonans.tgl_bayar', 'permohonans.total', 'data_penyedias.nama_CV', 'permohonans.status')
            ->whereBetween('permohonans.tgl_selesai', [$mulai, $sampai])
            ->get();
        $kendaraan = DB::table('permohonans')
            ->join('kendaraans', 'permohonans.kendaraan_id', '=', 'kendaraans.id')
            ->join('administrators', 'permohonans.admin', '=', 'administrators.id')
            ->join('data_penyedias', 'permohonans.data_penyedia_id', '=', 'data_penyedias.id')
            ->select('permohonans.id AS idPermohonan', 'permohonans.service_id AS idService', 'kendaraans.merek', 'kendaraans.no_polisi', 'administrators.username', 'permohonans.akun_x_rkakl_id', 'permohonans.tgl_permohonan', 'permohonans.tgl_pemeriksaan', 'permohonans.tgl_pengerjaan', 'permohonans.tgl_selesai', 'permohonans.nominal', 'permohonans.pph', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn', 'permohonans.tgl_bayar', 'permohonans.total', 'data_penyedias.nama_CV', 'permohonans.status')
            ->whereBetween('permohonans.tgl_selesai', [$mulai, $sampai])
            ->get();
        $ruangan = DB::table('permohonans')
            ->join('ruangans', 'permohonans.ruangan_id', '=', 'ruangans.id')
            ->join('administrators', 'permohonans.admin', '=', 'administrators.id')
            ->join('data_penyedias', 'permohonans.data_penyedia_id', '=', 'data_penyedias.id')
            ->select('permohonans.id AS idPermohonan', 'permohonans.service_id AS idService', 'ruangans.nama_ruangan', 'ruangans.kode_ruangan', 'administrators.username', 'permohonans.akun_x_rkakl_id', 'permohonans.tgl_permohonan', 'permohonans.tgl_pemeriksaan', 'permohonans.tgl_pengerjaan', 'permohonans.tgl_selesai', 'permohonans.nominal', 'permohonans.pph', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn', 'permohonans.tgl_bayar', 'permohonans.total', 'data_penyedias.nama_CV', 'permohonans.status')
            ->whereBetween('permohonans.tgl_selesai', [$mulai, $sampai])
            ->get();
        $akuns = DB::table('akun_x_rkakls')
            ->join('akuns', 'akun_x_rkakls.akun_id', '=', 'akuns.id')
            ->join('ref_rkakl_sub_komponens', 'akun_x_rkakls.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
            ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
            ->select('akun_x_rkakls.id as idAkun', 'ref_rkakl_sub_komponens.nama_sub_kegiatan', 'akuns.uraian', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program', 'ref_rkakl_kegiatans.kode_kegiatan', 'ref_rkakl_outputs.kode_output', 'ref_rkakl_suboutputs.kode_sub_output', 'ref_rkakl_komponens.kode_komponen', 'ref_rkakl_sub_komponens.kode_sub_kegiatan', 'ref_rkakl_sub_komponens.nama_sub_kegiatan', 'akuns.kode_akun', 'akuns.uraian')
            ->get();
        // ddd($kegiatans);
        return view('admin.other.bmn', [
            'title' => 'Laporan BMN (Barang Milik Negara)',
            'komponens' => Komponen_diperlukan::all(),
            'dokumens' => Dokumen_permohonan::all(),
            'akuns' => $akuns,
            'permohonans' => $permohonan_admin,
            'permohonanPegawais' => $permohonan_pegawai,
            'kendaraans' => $kendaraan,
            'ruangans' => $ruangan,
        ]);
    }

    public function ganeratePerjadin(Request $request)
    {
        $mulai = $request->mulai;
        $sampai = $request->sampai;
        return redirect()->route('laporanPerjadin', ['mulai' => $mulai, 'sampai' => $sampai])->with('success', 'Data Telah diset!');
    }

    public function ganerateKegiatan(Request $request)
    {
        $mulai = $request->mulai;
        $sampai = $request->sampai;
        return redirect()->route('laporanKegiatan', ['mulai' => $mulai, 'sampai' => $sampai])->with('success', 'Data Telah diset!');
    }

    public function ganerateBMN(Request $request)
    {
        $mulai = $request->mulai;
        $sampai = $request->sampai;
        return redirect()->route('laporanBMN', ['mulai' => $mulai, 'sampai' => $sampai])->with('success', 'Data Telah diset!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function storePengaturan(Request $request)
    {
        // dd($request->versi);

        db::table('versis')->insertOrIgnore([
            'versi' => $request->versi,
            'status' => 'non-aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        return redirect()->route('pengaturan')->with('success', 'Versi Telah Ditambahkan');
    }


    public function setPengaturan(Request $request)
    {
        if ($request->conf == "Ya, Saya yakin akan merubah versi") {
            DB::table('versis')
                ->where('status', 'aktif')
                ->update([
                    'status' => 'non-aktif',
                    'updated_at' => now(),
                ]);

            DB::table('versis')
                ->where('id', $request->getIdVersi)
                ->update([
                    'status' => 'aktif',
                    'updated_at' => now(),
                ]);

            session(['versi' => $request->getIdVersi]);
            return redirect()->route('pengaturan')->with('success', 'Versi Telah Diaktifkan');
        } else {
            return redirect()->route('pengaturan')->with('success', 'Periksa kembali tulisan konfirmasi untuk ubah versi, karena ubah versi adalah hal yang fatal!'); # code...
        }
    }



    public function storeSBM(Request $request)
    {
        $versi = Versi::where('status', 'aktif')->get();

        db::table('ref_sbms')->insertOrIgnore([
            'kode_sbm' => $request->kode,
            'uraian' => $request->uraian,
            'satuan' => $request->satuan,
            'biaya' => $request->nominal,
            'versi_id' => $versi[0]->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('sbm')->with('success', 'Telah Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function updateSBM(Request $request)
    {
        DB::table('ref_sbms')
            ->where('id', $request->idSbm)
            ->update([
                'kode_sbm' => $request->kode,
                'uraian' => $request->uraian,
                'satuan' => $request->satuan,
                'biaya' => $request->nominal,
                'updated_at' => now(),
            ]);

        return redirect()->route('sbm')->with('success', 'Data Telah Diperbaharui!');
    }

    public function updateSpby(Request $request)
    {

        // dd($request);
        $totalpesertapegawai = $request->numPegawaiLaporan;
        for ($i = 0; $i < $totalpesertapegawai; $i++) {
            $idpesertapegawai = 'idKeuangan_' . $i;
            $tglKwitansi = 'tglkwitansi_' . $i;
            $noKwitansi = 'kwitansi_' . $i;
            $tglSpby = 'tglSpby_' . $i;
            $spby = 'spby_' . $i;
            $drpp = 'drpp_' . $i;
            $jurnal = 'jurnal_' . $i;
            DB::table('keuangan_perjadinlangsungs')
                ->where('id', $request->$idpesertapegawai)
                ->update([
                    'tgl_kwitansi' => $request->$tglKwitansi,
                    'no_kwitansi' => $request->$noKwitansi,
                    'tgl_spby' => $request->$tglSpby,
                    'spby' => $request->$spby,
                    'drpp' => $request->$drpp,
                    'jurnal' => $request->$jurnal,
                    'updated_at' => now(),
                ]);
        }

        $totalpesertapegawaiNon = $request->numNonPegawaiLaporan;
        for ($i = 0; $i < $totalpesertapegawaiNon; $i++) {
            $idpesertapegawai = 'idKeuanganNon_' . $i;
            $tglKwitansi = 'tglkwitansiNon_' . $i;
            $noKwitansi = 'kwitansiNon_' . $i;
            $spby = 'spbyNon_' . $i;
            $tglSpby = 'tglSpbyNon_' . $i;
            $drpp = 'drppNon_' . $i;
            $jurnal = 'jurnalNon_' . $i;
            DB::table('keuangan_perjadinlangsungs')
                ->where('id', $request->$idpesertapegawai)
                ->update([
                    'tgl_kwitansi' => $request->$tglKwitansi,
                    'no_kwitansi' => $request->$noKwitansi,
                    'tgl_spby' => $request->$tglSpby,
                    'spby' => $request->$spby,
                    'drpp' => $request->$drpp,
                    'jurnal' => $request->$jurnal,
                    'updated_at' => now(),
                ]);
        }

        $mulai = $request->mulai;
        $sampai = $request->sampai;
        return redirect()->route('laporanPerjadin', ['mulai' => $mulai, 'sampai' => $sampai])->with('success', 'Data Telah diset!');

    }

    public function updateSpbyKegiatan(Request $request)
    {

        $totalpesertapegawai = $request->numPegawaiLaporan;
        for ($i = 0; $i < $totalpesertapegawai; $i++) {
            $idpesertapegawai = 'idKeuangan_' . $i;
            $tglKwitansi = 'tglkwitansi_' . $i;
            $noKwitansi = 'kwitansi_' . $i;
            $tglSpby = 'tglSpby_' . $i;
            $spby = 'spby_' . $i;
            $drpp = 'drpp_' . $i;
            $jurnal = 'jurnal_' . $i;
            DB::table('keuangan_perjadinkegiatans')
                ->where('id', $request->$idpesertapegawai)
                ->update([
                    'tgl_kwitansi' => $request->$tglKwitansi,
                    'no_kwitansi' => $request->$noKwitansi,
                    'tgl_spby' => $request->$tglSpby,
                    'spby' => $request->$spby,
                    'drpp' => $request->$drpp,
                    'jurnal' => $request->$jurnal,
                    'updated_at' => now(),
                ]);
        }

        $totalpesertapegawaiNon = $request->numNonPegawaiLaporan;
        for ($i = 0; $i < $totalpesertapegawaiNon; $i++) {
            $idpesertapegawai = 'idKeuanganNon_' . $i;
            $tglKwitansi = 'tglkwitansiNon_' . $i;
            $noKwitansi = 'kwitansiNon_' . $i;
            $tglSpby = 'tglSpbyNon_' . $i;
            $spby = 'spbyNon_' . $i;
            $drpp = 'drppNon_' . $i;
            $jurnal = 'jurnalNon_' . $i;
            DB::table('keuangan_perjadinkegiatans')
                ->where('id', $request->$idpesertapegawai)
                ->update([
                    'tgl_kwitansi' => $request->$tglKwitansi,
                    'no_kwitansi' => $request->$noKwitansi,
                    'tgl_spby' => $request->$tglSpby,
                    'spby' => $request->$spby,
                    'drpp' => $request->$drpp,
                    'jurnal' => $request->$jurnal,
                    'updated_at' => now(),
                ]);
        }

        $mulai = $request->mulai;
        $sampai = $request->sampai;
        return redirect()->route('laporanKegiatan', ['mulai' => $mulai, 'sampai' => $sampai])->with('success', 'Data Telah Diperbaharui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function destroySBM(Request $request, string $id)
    {
        Ref_sbm::destroy($id);
        return redirect()->route('sbm')->with('success', 'Data telah dihapus!');
    }

    public function indexUsulanPerjadin(Request $request)
{
    $tipe = $request->input('tipe', 'perjadin');
    $status = $request->input('status', 'semua');


    if ($tipe == 'perjadin') {
        if ($status == 'semua') {
            $countSemua = DB::table('info_perjadinlangsungs')
            ->where('versi_id', session('versi'))
            ->whereIn('status_pengajuan', ['pengajuan','proses','pelaporan','selesai','revisi','ditolak'])
            ->count();

            $countPengajuan = DB::table('info_perjadinlangsungs')
                ->where('versi_id', session('versi'))
                ->whereIn('status_pengajuan_detail', ['Verifikasi-BMN', 'Verifikasi-HKT', 'Verifikasi-HKT<br>(Proses TTE)','Approval-1-Bendahara'])
                ->count();

            $countProses = DB::table('info_perjadinlangsungs')
                ->where('versi_id', session('versi'))
                ->whereIn('status_pengajuan_detail', [
                    'approval-2-Bendahara', 'Pelaporan', 'Pelaksanaan Perjadin',
                    'verifikasi-2-keuangan', 'verifikasi-1-keuangan', 'verifikasi-2-keu-revisi'
                ])
                ->count();

            $countDitolak = DB::table('info_perjadinlangsungs')
                ->where('versi_id', session('versi'))
                ->where('status_pengajuan', 'ditolak')
                ->count();

            $countSelesai = DB::table('info_perjadinlangsungs')
                ->where('versi_id', session('versi'))
                ->whereIn('status_pengajuan_detail', ['Selesai Dibayarkan', 'selesai', 'Selesai Non Bayar'])
                ->count();


            $items = DB::table('info_perjadinlangsungs')
                ->leftJoin('pegawais', 'info_perjadinlangsungs.id_pengaju', '=', 'pegawais.id')
                ->leftJoin('non_pegawais', 'info_perjadinlangsungs.id_pengaju', '=', 'non_pegawais.id')
                ->leftJoin('administrators', 'info_perjadinlangsungs.id_pengaju', '=', 'administrators.id')
                ->select('info_perjadinlangsungs.*',
                    DB::raw('COALESCE(CONCAT("(Admin) ", administrators.username), pegawais.nama_lengkap, non_pegawais.nama_lengkap, "Tidak Ditemukan") as nama_pengaju'))
                ->where(function ($query) use ($tipe, $status) {
                    if ($tipe == 'perjadin') {
                        $query->whereIn('status_pengajuan', ['pengajuan','proses','pelaporan','selesai','revisi','ditolak']);
                        // if ($status == 'pengajuan') {
                        // } elseif ($status == 'proses') {
                        // } elseif ($status == 'selesai') {
                        // } elseif ($status == 'ditolak') {
                        // }
                    }
                })
                ->where('versi_id', session('versi'))
                ->get();

            $selectPeserta = DB::table('data_perjadinlangsungs')
                ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
                ->select('data_perjadinlangsungs.id as idPeserta', 'data_perjadinlangsungs.status_persetujuan', 'data_perjadinlangsungs.status_pegawai', 'pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan')
                ->get();

            $selectPeserta_nonPegawai = DB::table('data_perjadinlangsungs')
                ->join('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
                ->select('data_perjadinlangsungs.id as idPeserta', 'data_perjadinlangsungs.status_persetujuan', 'data_perjadinlangsungs.status_pegawai', 'non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan')
                ->get();
        } else {
            $countSemua = DB::table('info_perjadinlangsungs')
            ->where('versi_id', session('versi'))
            ->whereIn('status_pengajuan', ['pengajuan','proses','pelaporan','selesai','revisi','ditolak'])
            ->count();

            $countPengajuan = DB::table('info_perjadinlangsungs')
            ->where('versi_id', session('versi'))
            ->whereIn('status_pengajuan_detail', ['Verifikasi-BMN', 'Verifikasi-HKT', 'Verifikasi-HKT<br>(Proses TTE)','Approval-1-Bendahara'])
            ->count();

            $countProses = DB::table('info_perjadinlangsungs')
                ->where('versi_id', session('versi'))
                ->whereIn('status_pengajuan_detail', [
                     'approval-2-Bendahara', 'Pelaporan', 'Pelaksanaan Perjadin',
                    'verifikasi-2-keuangan', 'verifikasi-1-keuangan', 'verifikasi-2-keu-revisi'
                ])
                ->count();

            $countDitolak = DB::table('info_perjadinlangsungs')
                ->where('versi_id', session('versi'))
                ->where('status_pengajuan', 'ditolak')
                ->count();

            $countSelesai = DB::table('info_perjadinlangsungs')
                ->where('versi_id', session('versi'))
                ->whereIn('status_pengajuan_detail', ['Selesai Dibayarkan', 'selesai', 'Selesai Non Bayar'])
                ->count();

            $items = DB::table('info_perjadinlangsungs')
            ->leftJoin('pegawais', 'info_perjadinlangsungs.id_pengaju', '=', 'pegawais.id')
            ->leftJoin('non_pegawais', 'info_perjadinlangsungs.id_pengaju', '=', 'non_pegawais.id')
            ->leftJoin('administrators', 'info_perjadinlangsungs.id_pengaju', '=', 'administrators.id')
            ->select('info_perjadinlangsungs.*',
                DB::raw('COALESCE(CONCAT("(Admin) ", administrators.username), pegawais.nama_lengkap, non_pegawais.nama_lengkap, "Tidak Ditemukan") as nama_pengaju'))
            ->where(function ($query) use ($tipe, $status) {
                if ($tipe == 'perjadin') {
                    if ($status == 'pengajuan') {
                        $query->whereIn('status_pengajuan_detail', ['Verifikasi-BMN', 'Verifikasi-HKT','Verifikasi-HKT<br>(Proses TTE)','Approval-1-Bendahara']);
                    } elseif ($status == 'proses') {

                        $query->whereIn('status_pengajuan_detail', [
                            'approval-2-Bendahara', 'Pelaporan', 'Pelaksanaan Perjadin',
                            'verifikasi-2-keuangan', 'verifikasi-1-keuangan','verifikasi-2-keu-revisi'
                        ]);
                    } elseif ($status == 'selesai') {
                        $query->whereIn('status_pengajuan_detail', ['Selesai Dibayarkan', 'selesai','Selesai Non Bayar']);
                    } elseif ($status == 'ditolak') {
                        $query->where('status_pengajuan', ['ditolak']);
                    }
                }
            })
            ->where('versi_id', session('versi'))
            ->get();

            $selectPeserta = DB::table('data_perjadinlangsungs')
                ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
                ->select('data_perjadinlangsungs.id as idPeserta', 'data_perjadinlangsungs.status_persetujuan', 'data_perjadinlangsungs.status_pegawai', 'pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan')
                ->get();

            $selectPeserta_nonPegawai = DB::table('data_perjadinlangsungs')
                ->join('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
                ->select('data_perjadinlangsungs.id as idPeserta', 'data_perjadinlangsungs.status_persetujuan', 'data_perjadinlangsungs.status_pegawai', 'non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan')
                ->get();
        }


    } else {
        if ($status == 'semua') {
            $countSemua = DB::table('data_perjadinkegiatans')
            ->where('versi_id', session('versi'))
            ->whereIn('status_pengajuan', ['pengajuan','proses','pelaporan','selesai','revisi','ditolak'])
            ->count();

            $countPengajuan = DB::table('data_perjadinkegiatans')
            ->where('versi_id', session('versi'))
            ->where('status_pengajuan', 'pengajuan')
            ->count();

            $countProses = DB::table('data_perjadinkegiatans')
                ->where('versi_id', session('versi'))
                ->where(function ($q) {
                    $q->whereIn('status_pengajuan', ['proses', 'pelaporan','revisi'])
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('status_pengajuan', 'selesai')
                                ->where('status_pengajuan_detail', '!=', 'Selesai Dibayarkan')
                                ->where('status_pengajuan_detail', '!=', 'Selesai Non Bayar');
                    });
                })
                ->count();

            $countDitolak = DB::table('data_perjadinkegiatans')
            ->where('versi_id', session('versi'))
            ->where('status_pengajuan', 'ditolak')
            ->count();

            $countSelesai = DB::table('data_perjadinkegiatans')
            ->where('versi_id', session('versi'))
            ->whereIn('status_pengajuan_detail', ['Selesai Dibayarkan', 'selesai', 'Selesai Non Bayar'])
            ->count();

            $items = DB::table('data_perjadinkegiatans')
            ->leftJoin('pegawais', 'data_perjadinkegiatans.id_pengaju', '=', 'pegawais.id')
            ->leftJoin('non_pegawais', 'data_perjadinkegiatans.id_pengaju', '=', 'non_pegawais.id')
            ->leftJoin('administrators', 'data_perjadinkegiatans.id_pengaju', '=', 'administrators.id')
            ->select('data_perjadinkegiatans.*',
                DB::raw('COALESCE(CONCAT("(Admin) ", administrators.username), pegawais.nama_lengkap, non_pegawais.nama_lengkap, "Tidak Ditemukan") as nama_pengaju'))
            ->where(function ($query) use ($tipe, $status) {
                if ($tipe == 'kegiatan') {
                    $query->whereIn('status_pengajuan', ['pengajuan','proses','pelaporan','selesai','revisi','ditolak']);

                    // if ($status == 'pengajuan') {
                        // $query->whereIn('status_pengajuan', []);
                    //     $query->where('status_pengajuan', 'pengajuan');
                    // } elseif ($status == 'proses') {
                    //         'Approval-2-Bendahara', 'Pelaporan', 'Pelaksanaan Kegiatan', 'Revisi Laporan'
                    //     ]);
                    // } elseif ($status == 'selesai') {
                    //     $query->whereIn('status_pengajuan_detail', ['Selesai Dibayarkan', 'selesai']);
                    // } elseif ($status == 'ditolak') {
                    //     $query->where('status_pengajuan', 'ditolak');
                    // }
                }
            })
            ->where('versi_id', session('versi'))
            ->get();


            $selectPeserta = DB::table('perangkat_acaras')
                ->join('pegawais', 'pegawai_id', '=', 'pegawais.id')
                ->select('perangkat_acaras.id as idPeserta', 'perangkat_acaras.status as status_persetujuan', 'pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan')
                ->get();

            $selectPeserta_nonPegawai = DB::table('perangkat_acaras')
                ->join('non_pegawais', 'non_pegawai_id', '=', 'non_pegawais.id')
                ->select('perangkat_acaras.id as idPeserta', 'perangkat_acaras.status as status_persetujuan', 'non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan')
                ->get();
        } else {
            $countSemua = DB::table('data_perjadinkegiatans')
            ->where('versi_id', session('versi'))
            ->whereIn('status_pengajuan', ['pengajuan','proses','pelaporan','selesai','revisi','ditolak'])
            ->count();

            $countPengajuan = DB::table('data_perjadinkegiatans')
            ->where('versi_id', session('versi'))
            ->where('status_pengajuan', 'pengajuan')
            ->count();

            $countProses = DB::table('data_perjadinkegiatans')
                ->where('versi_id', session('versi'))
                ->where(function ($q) {
                    $q->whereIn('status_pengajuan', ['proses', 'pelaporan', 'revisi'])
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('status_pengajuan', 'selesai',)
                                ->where('status_pengajuan_detail', '!=', 'Selesai Dibayarkan')
                                ->where('status_pengajuan_detail', '!=', 'Selesai Non Bayar');
                    });
                })
                ->count();


            $countDitolak = DB::table('data_perjadinkegiatans')
            ->where('versi_id', session('versi'))
            ->where('status_pengajuan', 'ditolak')
            ->count();

            $countSelesai = DB::table('data_perjadinkegiatans')
            ->where('versi_id', session('versi'))
            ->whereIn('status_pengajuan_detail', ['Selesai Dibayarkan', 'selesai',  'Selesai Non Bayar'])
            ->count();

            $items = DB::table('data_perjadinkegiatans')
            ->leftJoin('pegawais', 'data_perjadinkegiatans.id_pengaju', '=', 'pegawais.id')
            ->leftJoin('non_pegawais', 'data_perjadinkegiatans.id_pengaju', '=', 'non_pegawais.id')
            ->leftJoin('administrators', 'data_perjadinkegiatans.id_pengaju', '=', 'administrators.id')
            ->select('data_perjadinkegiatans.*',
                DB::raw('COALESCE(CONCAT("(Admin) ", administrators.username), pegawais.nama_lengkap, non_pegawais.nama_lengkap, "Tidak Ditemukan") as nama_pengaju'))
            ->where(function ($query) use ($tipe, $status) {
                if ($tipe == 'kegiatan') {
                    if ($status == 'pengajuan') {
                        $query->where('status_pengajuan', 'pengajuan');
                    } elseif ($status == 'proses') {
                        $query->where(function ($q) {
                            $q->whereIn('status_pengajuan', ['proses', 'pelaporan', 'revisi'])
                              ->orWhere(function ($subQuery) {
                                  $subQuery->where('status_pengajuan', 'selesai')
                                           ->where('status_pengajuan_detail', '!=', 'Selesai Dibayarkan')
                                           ->where('status_pengajuan_detail', '!=', 'Selesai Non Bayar');
                              });
                        });
                    } elseif ($status == 'selesai') {
                        $query->whereIn('status_pengajuan_detail', ['Selesai Dibayarkan', 'selesai',  'Selesai Non Bayar']);
                    } elseif ($status == 'ditolak') {
                        $query->where('status_pengajuan', 'ditolak');
                    }
                }
            })
            ->where('versi_id', session('versi'))
            ->get();


            $selectPeserta = DB::table('perangkat_acaras')
                ->join('pegawais', 'pegawai_id', '=', 'pegawais.id')
                ->select('perangkat_acaras.id as idPeserta', 'perangkat_acaras.status as status_persetujuan', 'pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan')
                ->get();

            $selectPeserta_nonPegawai = DB::table('perangkat_acaras')
                ->join('non_pegawais', 'non_pegawai_id', '=', 'non_pegawais.id')
                ->select('perangkat_acaras.id as idPeserta', 'perangkat_acaras.status as status_persetujuan', 'non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan')
                ->get();
        }
    }


    return view('admin.other.monitoring', [
        'title' => 'Data Administrator',
        'items' => $items,
        'selectPesertas' => $selectPeserta,
        'selectPesertasNonPegawais' => $selectPeserta_nonPegawai,
        'status' => $status,
        'tipe' => $tipe,
        'isPerjadin' => ($tipe == 'perjadin'),
        'countSemua' => $countSemua ,
        'countPengajuan' => $countPengajuan,
        'countProses' => $countProses,
        'countDitolak' => $countDitolak,
        'countSelesai' => $countSelesai
    ]);
}

public function detailPerjadin(Request $request,$id)
{
    $tipe = $request->input('tipe');

    // dd($tipe);

    // Ambil data dari database seperti sebelumnya
    $selectPeserta = DB::table('data_perjadinlangsungs')
        ->join('pegawais', 'pegawai_id', '=', 'pegawais.id')
        ->join('keuangan_perjadinlangsungs', 'keuangan_perjadinlangsungs.data_perjadinlangsungs', '=', 'data_perjadinlangsungs.id')
        ->select(
            'data_perjadinlangsungs.id as idPeserta',
            'data_perjadinlangsungs.status_persetujuan',
            'data_perjadinlangsungs.status_pegawai',
            'pegawais.nama_lengkap',
            'pegawais.pangkat',
            'pegawais.golongan',
            DB::raw('SUM(COALESCE(keuangan_perjadinlangsungs.jumlah_harga, 0)) as nominal_perjadin'),
            'keuangan_perjadinlangsungs.akun_x_rkakl as idAkunHarian',
        )
        ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
        ->whereNull('keuangan_perjadinlangsungs.kebutuhan_id')
        ->groupBy(
            'data_perjadinlangsungs.id',
            'data_perjadinlangsungs.status_persetujuan',
            'data_perjadinlangsungs.status_pegawai',
            'pegawais.nama_lengkap',
            'pegawais.pangkat',
            'pegawais.golongan',
            'keuangan_perjadinlangsungs.akun_x_rkakl',
        )
        ->get();



    $selectPeserta_nonPegawai = DB::table('data_perjadinlangsungs')
        ->join('non_pegawais', 'non_pegawai_id', '=', 'non_pegawais.id')
        ->join('keuangan_perjadinlangsungs', 'keuangan_perjadinlangsungs.data_perjadinlangsungs', '=', 'data_perjadinlangsungs.id')
        ->select(
            'data_perjadinlangsungs.id as idPeserta',
            'data_perjadinlangsungs.status_persetujuan',
            'data_perjadinlangsungs.id',
            'data_perjadinlangsungs.status_pegawai',
            'non_pegawais.nama_lengkap',
            'non_pegawais.pangkat',
            'non_pegawais.golongan',
            DB::raw('COALESCE(keuangan_perjadinlangsungs.jumlah_harga, 0) as nominal_perjadin'),
            'keuangan_perjadinlangsungs.akun_x_rkakl as idAkunHarian',
        )
        ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
        ->get();

        $kebutuhans = DB::table('keuangan_perjadinlangsungs')
        ->join('kebutuhans', 'keuangan_perjadinlangsungs.kebutuhan_id', '=', 'kebutuhans.id')
        ->select(
            'kebutuhans.id as idKebutuhan',
            'kebutuhans.nama',
            'kebutuhans.jumlah_frekuensi',
            'kebutuhans.satuan',
            'kebutuhans.tipe_pendanaan',
            'kebutuhans.ket',
            'keuangan_perjadinlangsungs.info_perjadinlangsung',
            'keuangan_perjadinlangsungs.kebutuhan_id',
            'keuangan_perjadinlangsungs.status',
            DB::raw('COALESCE(keuangan_perjadinlangsungs.jumlah_harga, 0) as nominal'),
            'keuangan_perjadinlangsungs.akun_x_rkakl as idAkunKebutuhan',
            
        )
        ->where('keuangan_perjadinlangsungs.info_perjadinlangsung', $id)
        ->get();
    $mobilitas = DB::table('peminjaman_kendaraan_dinas')
        ->join('pegawais', 'peminjaman_kendaraan_dinas.pegawai_id', '=', 'pegawais.id')
        ->join('kendaraans', 'peminjaman_kendaraan_dinas.kendaraan', '=', 'kendaraans.id')
        ->select('peminjaman_kendaraan_dinas.info_perjadinlangsung', 'pegawais.nama_lengkap', 'kendaraans.merek', 'kendaraans.no_polisi', 'peminjaman_kendaraan_dinas.status')
        ->where('peminjaman_kendaraan_dinas.info_perjadinlangsung', $id)
        ->get();

    // Ambil data lainnya seperti mobilitas, pembayaran, dll

    return view('admin.other.detail-usulan', [
        'title' => 'Detail Kegiatan Perjadin',
        'active' => 'admin_other_perjadin',
        'perjadin' => Info_perjadinlangsung::find($id),
        'selectPesertas' => $selectPeserta,
        "fasilitas" => $kebutuhans,
        'mobilitass' => $mobilitas,
        'selectPesertasNonPegawais' => $selectPeserta_nonPegawai,
        'tipe' => $tipe,
        'dokumen' => Dokumen::where('info_perjadinlangsung_id', $id)->first(),

    ]);
}

public function detailKegiatan(Request $request, $id)
{
    $tipe = $request->input('tipe');
    
    // Query to fetch the 'kegiatan' data
    $kegiatan = DB::table('data_perjadinkegiatans')->where('id', $id)->first(); // Assuming 'kegiatans' is your table

 // Nonaktifkan ONLY_FULL_GROUP_BY
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");

    // Existing queries
    $perangkatPegawai = DB::table('perangkat_acaras')
    ->join('pegawais', 'pegawai_id', '=', 'pegawais.id')
    ->join('fasilitas', 'fasilitas_id', '=', 'fasilitas.id')
    ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
    ->select(
        'perangkat_acaras.id as idPerangkat',
        'pegawais.nama_lengkap',
        'pegawais.golongan',
        'pegawais.pangkat',
        'perangkat_acaras.status',
        'perangkat_acaras.sebagai',
        'fasilitas.nama_fasilitas',
        'perangkat_acaras.fasilitas_id',
        'keuangan_perjadinkegiatans.data_perjadinkegiatan',
        DB::raw('COALESCE(keuangan_perjadinkegiatans.jumlah_honorarium, 0) as nominal_honorarium'),
        DB::raw('COALESCE(keuangan_perjadinkegiatans.nominal_perjadin, 0) as nominal_perjadin'),
        DB::raw("CASE 
            WHEN keuangan_perjadinkegiatans.kode = 'harian' 
                OR keuangan_perjadinkegiatans.operasional IS NOT NULL 
            THEN keuangan_perjadinkegiatans.akun_x_rkakl 
            ELSE NULL 
        END as idAkunHarian"),
        DB::raw("CASE 
            WHEN keuangan_perjadinkegiatans.kode = 'honor'
            THEN keuangan_perjadinkegiatans.akun_x_rkakl 
            ELSE NULL 
        END as idAkunHonor")
        )
    ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
    ->groupBy('keuangan_perjadinkegiatans.perangkat_acara')
    ->get();
    

    $mobilities = DB::table('mobilitas_perjadinkegiatans')
    ->select(
        'mobilitas_perjadinkegiatans.id as idMobilitas',
        'mobilitas_perjadinkegiatans.mobilitas',
        'mobilitas_perjadinkegiatans.tujuan_penggunaan',
        'mobilitas_perjadinkegiatans.tgl_mulai',
        'mobilitas_perjadinkegiatans.tgl_selesai',
        'mobilitas_perjadinkegiatans.status',
        'mobilitas_perjadinkegiatans.unit'
    )
    ->where('mobilitas_perjadinkegiatans.data_perjadinkegiatan', $id)  // Filtering by kegiatan ID
    ->get();



    $supir = DB::table('perangkat_acaras')
        ->join('pegawais', 'pegawai_id', '=', 'pegawais.id')
        ->join('fasilitas', 'fasilitas_id', '=', 'fasilitas.id')
        ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
        ->join('operasionals', 'keuangan_perjadinkegiatans.operasional', '=', 'operasionals.id')
        ->join('mobilitas_perjadinkegiatans', 'mobilitas_perjadinkegiatans.id', '=', 'operasionals.data_perjadin_kegiatan')
        ->select(
            'perangkat_acaras.id as idPerangkat',
            'pegawais.nama_lengkap',
            'pegawais.golongan',
            'pegawais.pangkat',
            'perangkat_acaras.status',
            'perangkat_acaras.sebagai',
            'fasilitas.nama_fasilitas',
            'perangkat_acaras.fasilitas_id',
            'keuangan_perjadinkegiatans.data_perjadinkegiatan',
            'mobilitas_perjadinkegiatans.tujuan_penggunaan',
            'keuangan_perjadinkegiatans.akun_x_rkakl as idAkun',
        )
        ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
        ->get();

    $perangkatNonPegawai = DB::table('perangkat_acaras')
        ->join('non_pegawais', 'non_pegawai_id', '=', 'non_pegawais.id')
        ->join('fasilitas', 'fasilitas_id', '=', 'fasilitas.id')
        ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
        ->select(
            'perangkat_acaras.id as idPerangkat',
            'non_pegawais.nama_lengkap',
            'non_pegawais.golongan',
            'non_pegawais.pangkat',
            'perangkat_acaras.status',
            'perangkat_acaras.sebagai',
            'fasilitas.nama_fasilitas',
            'perangkat_acaras.fasilitas_id',
            'keuangan_perjadinkegiatans.data_perjadinkegiatan',
            DB::raw('COALESCE(keuangan_perjadinkegiatans.jumlah_honorarium, 0) as nominal_honorarium'),
            DB::raw('COALESCE(keuangan_perjadinkegiatans.nominal_perjadin, 0) as nominal_perjadin'),
            DB::raw("CASE 
                WHEN keuangan_perjadinkegiatans.kode = 'harian' 
                    OR keuangan_perjadinkegiatans.operasional IS NOT NULL 
                THEN keuangan_perjadinkegiatans.akun_x_rkakl 
                ELSE NULL 
            END as idAkunHarian"),
            DB::raw("CASE 
                WHEN keuangan_perjadinkegiatans.kode = 'honor'
                THEN keuangan_perjadinkegiatans.akun_x_rkakl 
                ELSE NULL 
            END as idAkunHonor")
        )
        ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
        ->groupBy('keuangan_perjadinkegiatans.perangkat_acara')
        ->get();

    $operasionals = DB::table('keuangan_perjadinkegiatans')
        ->join('operasionals', 'keuangan_perjadinkegiatans.operasional', '=', 'operasionals.id')
        ->select('operasionals.id', 'operasionals.status', 'operasionals.nama', 'operasionals.jumlah_frekuensi', 'operasionals.satuan', 'operasionals.detail_satuan', 'keuangan_perjadinkegiatans.operasional', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
        ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
        ->get();

    $saprasKegiatan = DB::table('peminjaman_sarpras')
        ->join('assets', 'peminjaman_sarpras.asset', '=', 'assets.id')
        ->select('peminjaman_sarpras.id as idPeminjaman', 'assets.id as IdBarang', 'assets.nama_barang', 'peminjaman_sarpras.jumlah_asset', 'peminjaman_sarpras.tgl_peminjaman', 'peminjaman_sarpras.data_perjadinkegiatan', 'peminjaman_sarpras.status')
        ->where('peminjaman_sarpras.data_perjadinkegiatan', $id)
        ->get();

        $kebutuhans = DB::table('keuangan_perjadinkegiatans')
        ->join('kebutuhans', 'keuangan_perjadinkegiatans.kebutuhan_id', '=', 'kebutuhans.id')
        ->select(
            'kebutuhans.id as idKebutuhan',
            'kebutuhans.nama',
            'kebutuhans.jumlah_frekuensi',
            'kebutuhans.satuan',
            'kebutuhans.tipe_pendanaan',
            'kebutuhans.ket',
            'keuangan_perjadinkegiatans.data_perjadinkegiatan',
            'keuangan_perjadinkegiatans.kebutuhan_id',
            'keuangan_perjadinkegiatans.status',
            DB::raw('COALESCE(keuangan_perjadinkegiatans.harga, 0) as nominal'),
            'keuangan_perjadinkegiatans.akun_x_rkakl as idAkunKebutuhan',
        )
        ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
        ->get();
    $perangkats = $perangkatPegawai->merge($perangkatNonPegawai);

// Kembalikan ONLY_FULL_GROUP_BY ke default
        DB::statement("SET SESSION sql_mode=CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY')");


    // Pass the retrieved $kegiatan to the view
    return view('admin.other.detail-usulankegiatan',[
        'title' => 'Kegiatanku',
        'active' => 'kegiatanku_perjadin',
        "kegiatan" => $kegiatan,  // Pass kegiatan variable here
        "perangkatPegawais" => $perangkatPegawai,
        "supirs" => $supir,
        'perangkats' => $perangkats,
        "perangkatNonPegawais" => $perangkatNonPegawai,
        'mobilities' => $mobilities,
        "operasionals" => $operasionals,
        "sapras" => $saprasKegiatan,
        "fasilitas" => $kebutuhans,
        "dokumens" => Laporan_perjadinkegiatan::where('data_perjadin_kegiatan', $id)->get(),
        'tipe' => $tipe,
    ]);
}

public function AdmingetDokumen($filename)
    {
        $path = storage_path('app/public/dokumen-perjadins/' . $filename);
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->file($path);
    }


    public function getDokumenKegiatan($filename)
    {
        $path = storage_path('app/public/dokumen-kegiatans/' . $filename);
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->file($path);
    }

    public function ganerateLaporanUsulan(Request $request)
    {
        $mulai = $request->tanggalDari;
        $sampai = $request->tanggalSampai;
        $tipe = $request->tipe; // tipe laporan: 'perjadin' atau 'kegiatan'
        $status = $request->status; // status laporan

        // Redirect berdasarkan tipe laporan dan status
        if ($tipe === 'perjadin') {
            return redirect()->route('laporan-Usulan', compact('mulai', 'sampai', 'status'))
                            ->with('success', 'Laporan Perjalanan Dinas berhasil di-generate!');
        } elseif ($tipe === 'kegiatan') {
            return redirect()->route('laporan-UsulanKegiatan', compact('mulai', 'sampai', 'status'))
                            ->with('success', 'Laporan Kegiatan berhasil di-generate!');
        } else {
            return redirect()->back()->withErrors('Tipe laporan tidak valid.');
        }
    }

    public function laporanUsulan($mulai, $sampai, $status = 'semua')
    {
        // Nonaktifkan ONLY_FULL_GROUP_BY
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");

        $perjadin = DB::table('info_perjadinlangsungs as ip')
        ->join('data_perjadinlangsungs as dp', 'dp.info_perjadinlangsung', '=', 'ip.id')
        ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
        ->leftJoin('keuangan_perjadinlangsungs as kp', 'dp.id', '=', 'kp.data_perjadinlangsungs')
        ->join('dokumens as d', 'ip.id', '=', 'd.info_perjadinlangsung_id')
        ->leftJoin('kebutuhans as kb', 'kp.kebutuhan_id', '=', 'kb.id')
        ->leftJoin('surtug_perjadinlangsungs as sp', 'ip.id', '=', 'sp.id_perjadinlangsung')
        ->whereBetween('ip.tgl_mulai', [$mulai, $sampai]);
        if ($status !== 'semua') {
            $perjadin->where('ip.status_pengajuan', $status);
        }

        $perjadin = $perjadin->select(
            DB::raw('ip.tgl_mulai, ip.tgl_selesai'),
            'dp.id as dataPerjadinId',
            'ip.id as idPerjadin',
            'ip.kabupaten_kota as Kota',
            DB::raw('CONCAT(DATEDIFF(ip.tgl_selesai, ip.tgl_mulai) + 1, " Hari") as Jumlah_Hari'),
            DB::raw('CONCAT(DATE_FORMAT(STR_TO_DATE(ip.tgl_mulai, "%Y-%m-%d"), "%e %M %Y"), " s.d ", DATE_FORMAT(STR_TO_DATE(ip.tgl_selesai, "%Y-%m-%d"), "%e %M %Y")) as Tanggal_Perjadin'),
            'p.nama_lengkap as Nama',
            'ip.nama_kegiatan as Kegiatan',
            'sp.nomor_surat as No_Surtug',
            DB::raw('COALESCE(SUM(CASE
                    WHEN kb.nama = "BBM" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tol" THEN kp.jumlah_harga
                    WHEN kb.nama = "Akomodasi Hotel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Transportasi Online" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Pesawat" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Kereta" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Travel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Lainnya" THEN kp.jumlah_harga ELSE 0
                END), 0) +
                COALESCE(kp.uang_harian, 0) +
                COALESCE(kp.uang_harian_fullday, 0) +
                COALESCE(kp.uang_harian_fullboard, 0) +
                COALESCE(kp.uang_representasi, 0) as Nominal_Bayar'),
            'd.created_at as Tgl_Terima_Berkas',
            'd.updated_at as Tgl_Berkas_Lengkap',
            'sp.tgl_surat_dibuat as Tgl_Surtug',
            'd.surat_undangan as Undangan',
            'd.surat_tugas as Surat_Tugas',
            'd.sppd as SPPD',
            'd.hasil as Laporan_Perjadin',
            'd.lap_pengeluaran as Bukti_Pengeluaran',
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "BBM" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as BBM'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Tol" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as e_Toll'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Akomodasi Hotel" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Penginapan'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Transportasi Online" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Transportasi'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Tiket Pesawat" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Pesawat'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Tiket Kereta" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Kereta'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Tiket Travel" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Travel'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Lainnya" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Fasilitas_Lainnya'),
            DB::raw('(SELECT CONCAT(rs.kode_satker, ".", rp.kode_program, ".", rk.kode_kegiatan, ".", ro.kode_output, ".", rso.kode_sub_output, ".", rc.kode_komponen, ".", rsc.kode_sub_kegiatan, ".", a.kode_akun)
                FROM akun_x_rkakls axr
                JOIN akuns a ON axr.akun_id = a.id
                JOIN ref_rkakl_sub_komponens rsc ON axr.ref_sub_komponen_id = rsc.id
                JOIN ref_rkakl_komponens rc ON rsc.ref_rkakl_komponen_id = rc.id
                JOIN ref_rkakl_suboutputs rso ON rc.ref_rkakl_suboutput_id = rso.id
                JOIN ref_rkakl_outputs ro ON rso.ref_rkakl_output_id = ro.id
                JOIN ref_rkakl_kegiatans rk ON ro.ref_rkakl_kegiatan_id = rk.id
                JOIN ref_rkakl_programs rp ON rk.ref_rkakl_program_id = rp.id
                JOIN ref_rkakl_satkers rs ON rp.ref_rkakl_satker_id = rs.id
                WHERE axr.id = kp.akun_x_rkakl LIMIT 1) as MAK'),
            DB::raw('DATE_FORMAT(kp.tgl_bayar, "%d-%m-%Y") as Tanggal_Pembayaran'),
            'kp.uang_harian as Uang_Harian',
            'kp.uang_harian_fullday as Uang_Fullday',
            'kp.uang_harian_fullboard as Uang_Fullboard',
            'kp.uang_representasi as Uang_Representasi',
            'kp.persen_pajak as Pph21',
            'kp.pph22 as Pph22',
            'kp.pph23 as Pph23',
            'kp.ppn as PPN',
            DB::raw('COALESCE(SUM(CASE
                    WHEN kb.nama = "BBM" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tol" THEN kp.jumlah_harga
                    WHEN kb.nama = "Akomodasi Hotel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Transportasi Online" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Pesawat" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Kereta" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Travel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Lainnya" THEN kp.jumlah_harga
                    ELSE 0
                END), 0) +
                COALESCE(kp.uang_harian, 0) +
                COALESCE(kp.uang_harian_fullday, 0) +
                COALESCE(kp.uang_harian_fullboard, 0) +
                COALESCE(kp.uang_representasi, 0) as Total_Pembayaran'),
            'kp.status as Status_Pembayaran',
            'd.updated_at AS Tanggal_Update_Laporan',
            'ip.status_pengajuan AS Status_Perjalanan_Dinas',
            'kp.tgl_kwitansi AS Tanggal_Kwitansi',
            'kp.no_kwitansi AS No_Kwitansi',
            'kp.tgl_spby AS Tanggal_SPBY',
            'kp.spby AS SPBY',
            'kp.jurnal AS Jurnal',
            'kp.drpp AS DRPP',
            'kp.id AS IdKeuangan'
        )
        ->groupBy('dp.id')
        ->orderBy('ip.id')
        ->get();

        $perjadinNon = DB::table('info_perjadinlangsungs as ip')
        ->join('data_perjadinlangsungs as dp', 'dp.info_perjadinlangsung', '=', 'ip.id')
        ->join('non_pegawais as p', 'dp.non_pegawai_id', '=', 'p.id')
        ->leftJoin('keuangan_perjadinlangsungs as kp', 'dp.id', '=', 'kp.data_perjadinlangsungs')
        ->join('dokumens as d', 'ip.id', '=', 'd.info_perjadinlangsung_id')
        ->leftJoin('kebutuhans as kb', 'kp.kebutuhan_id', '=', 'kb.id')
        ->leftJoin('surtug_perjadinlangsungs as sp', 'ip.id', '=', 'sp.id_perjadinlangsung')
        ->whereBetween('ip.tgl_mulai', [$mulai, $sampai]);
         // Tambahkan filter berdasarkan status jika bukan 'semua'
    if ($status !== 'semua') {
        $perjadinNon->where('ip.status_pengajuan', $status);
    }

    $perjadinNon = $perjadinNon->select(
            DB::raw('ip.tgl_mulai, ip.tgl_selesai'),
            'dp.id as dataPerjadinId',
            'ip.id as idPerjadin',
            'ip.kabupaten_kota as Kota',
            DB::raw('CONCAT(DATEDIFF(ip.tgl_selesai, ip.tgl_mulai) + 1, " Hari") as Jumlah_Hari'),
            DB::raw('CONCAT(DATE_FORMAT(STR_TO_DATE(ip.tgl_mulai, "%Y-%m-%d"), "%e %M %Y"), " s.d ", DATE_FORMAT(STR_TO_DATE(ip.tgl_selesai, "%Y-%m-%d"), "%e %M %Y")) as Tanggal_Perjadin'),
            'p.nama_lengkap as Nama',
            'ip.nama_kegiatan as Kegiatan',
            'sp.nomor_surat as No_Surtug',
            DB::raw('COALESCE(SUM(CASE
                    WHEN kb.nama = "BBM" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tol" THEN kp.jumlah_harga
                    WHEN kb.nama = "Akomodasi Hotel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Transportasi Online" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Pesawat" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Kereta" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Travel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Lainnya" THEN kp.jumlah_harga ELSE 0
                END), 0) +
                COALESCE(kp.uang_harian, 0) +
                COALESCE(kp.uang_harian_fullday, 0) +
                COALESCE(kp.uang_harian_fullboard, 0) +
                COALESCE(kp.uang_representasi, 0) as Nominal_Bayar'),
            'd.created_at as Tgl_Terima_Berkas',
            'd.updated_at as Tgl_Berkas_Lengkap',
            'sp.tgl_surat_dibuat as Tgl_Surtug',
            'd.surat_undangan as Undangan',
            'd.surat_tugas as Surat_Tugas',
            'd.sppd as SPPD',
            'd.hasil as Laporan_Perjadin',
            'd.lap_pengeluaran as Bukti_Pengeluaran',
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "BBM" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as BBM'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Tol" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as e_Toll'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Akomodasi Hotel" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Penginapan'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Transportasi Online" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Transportasi'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Tiket Pesawat" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Pesawat'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Tiket Kereta" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Kereta'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Tiket Travel" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Travel'),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama = "Lainnya" THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Fasilitas_Lainnya'),
            DB::raw('(SELECT CONCAT(rs.kode_satker, ".", rp.kode_program, ".", rk.kode_kegiatan, ".", ro.kode_output, ".", rso.kode_sub_output, ".", rc.kode_komponen, ".", rsc.kode_sub_kegiatan, ".", a.kode_akun)
                FROM akun_x_rkakls axr
                JOIN akuns a ON axr.akun_id = a.id
                JOIN ref_rkakl_sub_komponens rsc ON axr.ref_sub_komponen_id = rsc.id
                JOIN ref_rkakl_komponens rc ON rsc.ref_rkakl_komponen_id = rc.id
                JOIN ref_rkakl_suboutputs rso ON rc.ref_rkakl_suboutput_id = rso.id
                JOIN ref_rkakl_outputs ro ON rso.ref_rkakl_output_id = ro.id
                JOIN ref_rkakl_kegiatans rk ON ro.ref_rkakl_kegiatan_id = rk.id
                JOIN ref_rkakl_programs rp ON rk.ref_rkakl_program_id = rp.id
                JOIN ref_rkakl_satkers rs ON rp.ref_rkakl_satker_id = rs.id
                WHERE axr.id = kp.akun_x_rkakl LIMIT 1) as MAK'),
            DB::raw('DATE_FORMAT(kp.tgl_bayar, "%d-%m-%Y") as Tanggal_Pembayaran'),
            'kp.uang_harian as Uang_Harian',
            'kp.uang_harian_fullday as Uang_Fullday',
            'kp.uang_harian_fullboard as Uang_Fullboard',
            'kp.uang_representasi as Uang_Representasi',
            'kp.persen_pajak as Pph21',
            'kp.pph22 as Pph22',
            'kp.pph23 as Pph23',
            'kp.ppn as PPN',
            DB::raw('COALESCE(SUM(CASE
                    WHEN kb.nama = "BBM" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tol" THEN kp.jumlah_harga
                    WHEN kb.nama = "Akomodasi Hotel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Transportasi Online" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Pesawat" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Kereta" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Travel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Lainnya" THEN kp.jumlah_harga
                    ELSE 0
                END), 0) +
                COALESCE(kp.uang_harian, 0) +
                COALESCE(kp.uang_harian_fullday, 0) +
                COALESCE(kp.uang_harian_fullboard, 0) +
                COALESCE(kp.uang_representasi, 0) as Total_Pembayaran'),
            'kp.status as Status_Pembayaran',
            'd.updated_at AS Tanggal_Update_Laporan',
            'ip.status_pengajuan AS Status_Perjalanan_Dinas',
            'kp.tgl_kwitansi AS Tanggal_Kwitansi',
            'kp.no_kwitansi AS No_Kwitansi',
            'kp.tgl_spby AS Tanggal_SPBY',
            'kp.spby AS SPBY',
            'kp.jurnal AS Jurnal',
            'kp.drpp AS DRPP',
            'kp.id AS IdKeuangan'
        )
        ->groupBy('dp.id')
        ->orderBy('ip.id')
        ->get();

        // dd($perjadin);
        // Kembalikan ONLY_FULL_GROUP_BY ke default
        DB::statement("SET SESSION sql_mode=CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY')");

        foreach ($perjadin as $row) {
            $row->Tanggal_Perjadin = Carbon::parse($row->tgl_mulai)->translatedFormat('d F Y') . ' s.d ' . Carbon::parse($row->tgl_selesai)->translatedFormat('d F Y');
        }
        foreach ($perjadinNon as $row) {
            $row->Tanggal_Perjadin = Carbon::parse($row->tgl_mulai)->translatedFormat('d F Y') . ' s.d ' . Carbon::parse($row->tgl_selesai)->translatedFormat('d F Y');
        }
    // Ambil id untuk HKT selesai
    $HKTselesaiId = DB::table('info_perjadinlangsungs')
        ->where('is_acceptHKT', 'selesai')
        ->pluck('id');

    // Ambil id perjadinlangsung dari surtug_perjadinlangsungs
    $surtugExist = DB::table('surtug_perjadinlangsungs')
        ->whereIn('id_perjadinlangsung', $perjadin->pluck('idPerjadin'))
        ->pluck('id_perjadinlangsung');





        $akuns = DB::table('akun_x_rkakls')
            ->join('akuns', 'akun_x_rkakls.akun_id', '=', 'akuns.id')
            ->join('ref_rkakl_sub_komponens', 'akun_x_rkakls.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
            ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
            ->select('akun_x_rkakls.id as idAkun', 'ref_rkakl_sub_komponens.nama_sub_kegiatan', 'akuns.uraian', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program', 'ref_rkakl_kegiatans.kode_kegiatan', 'ref_rkakl_outputs.kode_output', 'ref_rkakl_suboutputs.kode_sub_output', 'ref_rkakl_komponens.kode_komponen', 'ref_rkakl_sub_komponens.kode_sub_kegiatan', 'ref_rkakl_sub_komponens.nama_sub_kegiatan', 'akuns.kode_akun', 'akuns.uraian')
            ->get();
        $fasilitas = DB::table('kebutuhans')

            ->join('keuangan_perjadinlangsungs', 'kebutuhans.id', '=', 'keuangan_perjadinlangsungs.kebutuhan_id')
            ->select('kebutuhans.id',  'keuangan_perjadinlangsungs.kebutuhan_id','kebutuhans.nama', 'kebutuhans.satuan', 'kebutuhans.jumlah_frekuensi',  'kebutuhans.satuan', 'keuangan_perjadinlangsungs.jumlah_harga', 'keuangan_perjadinlangsungs.info_perjadinlangsung', 'keuangan_perjadinlangsungs.status',  'keuangan_perjadinlangsungs.data_perjadinlangsungs' )

            ->get();

        $kebutuhan = DB::table('keuangan_perjadinlangsungs')
        ->join('kebutuhans', 'keuangan_perjadinlangsungs.kebutuhan_id', '=', 'kebutuhans.id')
        ->select('kebutuhans.id AS idKebutuhan',  'keuangan_perjadinlangsungs.kebutuhan_id','kebutuhans.nama', 'kebutuhans.satuan', 'kebutuhans.jumlah_frekuensi',  'kebutuhans.satuan', 'keuangan_perjadinlangsungs.jumlah_harga', 'keuangan_perjadinlangsungs.info_perjadinlangsung', 'keuangan_perjadinlangsungs.status',  'keuangan_perjadinlangsungs.data_perjadinlangsungs' )
        ->get();

        $dibayarkan = DB::table('keuangan_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'keuangan_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->where('keuangan_perjadinlangsungs.status', 'Sudah Dibayarkan')
            ->whereBetween('info_perjadinlangsungs.tgl_mulai', [$mulai, $sampai])
            ->count();
        $totaldibayarkan = DB::table('keuangan_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'keuangan_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->where('keuangan_perjadinlangsungs.status', 'Sudah Dibayarkan')
            ->whereBetween('info_perjadinlangsungs.tgl_mulai', [$mulai, $sampai])
            ->sum('keuangan_perjadinlangsungs.jumlah_harga');
        $tdkdibayarkan = DB::table('keuangan_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'keuangan_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->where('keuangan_perjadinlangsungs.status', 'Tidak Dibayarkan')
            ->whereBetween('info_perjadinlangsungs.tgl_mulai', [$mulai, $sampai])
            ->count();
        $totaltdkdibayarkan = DB::table('keuangan_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'keuangan_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->where('keuangan_perjadinlangsungs.status', 'Tidak Dibayarkan')
            ->whereBetween('info_perjadinlangsungs.tgl_mulai', [$mulai, $sampai])
            ->sum('keuangan_perjadinlangsungs.jumlah_harga');
        $blmdibayarkan = DB::table('keuangan_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'keuangan_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->where('keuangan_perjadinlangsungs.status','!=', 'Sudah Dibayarkan')
            ->where('keuangan_perjadinlangsungs.status','!=', 'Tidak Dibayarkan')
            ->whereBetween('info_perjadinlangsungs.tgl_mulai', [$mulai, $sampai])
            ->count();
        $totalblmdibayarkan = DB::table('keuangan_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'keuangan_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->where('keuangan_perjadinlangsungs.status','!=', 'Sudah Dibayarkan')
            ->where('keuangan_perjadinlangsungs.status','!=', 'Tidak Dibayarkan')
            ->whereBetween('info_perjadinlangsungs.tgl_mulai', [$mulai, $sampai])
            ->sum('keuangan_perjadinlangsungs.jumlah_harga');
        // dd($perjadin, $fasilitas);
        return view('admin.other.LaporanUsulan', [
            'title' => 'Laporan Perjalanan Dinas',
            'perjadins' => $perjadin,
            'perjadinNons' => $perjadinNon,
            'akuns' => $akuns,
            'mulai' => $mulai,
            'sampai'=> $sampai,
            'status' => $status,
            'fasilitas' => $fasilitas,
            'kebutuhan' => $kebutuhan,
            'blmDibayarkan' => $blmdibayarkan,
            'tdkDibayarkan' => $tdkdibayarkan,
            'dibayarkan' => $dibayarkan,
            'totaldibayarkan' => $totaldibayarkan,
            'totaltdkdibayarkan' => $totaltdkdibayarkan,
            'totalblmdibayarkan' => $totalblmdibayarkan,
            'surtugs' => DB::table('surtug_perjadinlangsungs')->get(), // Mengambil semua data dari surtug_perjadinlangsungs
            'surtugExist' => $surtugExist,
        ]);
    }

    public function LaporanUsulanKegiatan($mulai, $sampai, $status = 'semua')
    {

        // Nonaktifkan ONLY_FULL_GROUP_BY
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");

        $kegiatans = DB::table('data_perjadinkegiatans as ip')
        ->join('perangkat_acaras as dp', 'dp.data_perjadin_kegiatan', '=', 'ip.id')
        ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
        ->leftJoin('keuangan_perjadinkegiatans as kp', 'dp.id', '=', 'kp.perangkat_acara')
        ->join('laporan_perjadinkegiatans as d', 'ip.id', '=', 'd.data_perjadin_kegiatan')
        ->leftJoin('kebutuhans as kb', 'kp.kebutuhan_id', '=', 'kb.id')
        ->leftJoin('surtug_perjadinkegiatans as sp', 'ip.id', '=', 'sp.data_perjadin_kegiatan')
        ->whereBetween('ip.tgl_mulai', [$mulai, $sampai]);
        if ($status !== 'semua') {
            $kegiatans->where('ip.status_pengajuan', $status);
        }

        $kegiatans = $kegiatans->select(

            DB::raw('ip.tgl_mulai, ip.tgl_selesai'),
            'dp.id as dataPerangkatId',
            'ip.id as idKegiatan',
            'ip.kab_kota as Kota',
            DB::raw('CONCAT(DATEDIFF(ip.tgl_selesai, ip.tgl_mulai) + 1, " Hari") as Jumlah_Hari'),
            DB::raw('CONCAT(DATE_FORMAT(STR_TO_DATE(ip.tgl_mulai, "%Y-%m-%d"), "%e %M %Y"), " s.d ", DATE_FORMAT(STR_TO_DATE(ip.tgl_selesai, "%Y-%m-%d"), "%e %M %Y")) as Tanggal_kegiatan'),
            'p.nama_lengkap as Nama',
            'ip.nama_kegiatan as Kegiatan',
            'sp.nomor_surat as No_Surtug',
            DB::raw('COALESCE(SUM(CASE
                    WHEN kb.nama = "BBM" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tol" THEN kp.jumlah_harga
                    WHEN kb.nama = "Akomodasi Hotel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Transportasi Online" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Pesawat" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Kereta" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Travel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Lainnya" THEN kp.jumlah_harga ELSE 0
                END), 0) +
                COALESCE(kp.uang_harian, 0) +
                COALESCE(kp.uang_harian_fullday, 0) +
                COALESCE(kp.uang_harian_fullboard, 0) +
                COALESCE(kp.uang_representasi, 0) as Nominal_Bayar'),
            'd.created_at as Tgl_Terima_Berkas',
            'd.updated_at as Tgl_Berkas_Lengkap',
            DB::raw('CONCAT(dp.posisi, " (" , dp.sebagai,")") as Sebagai'),
            'sp.tgl_surat_dibuat as Tgl_Surtug',
            DB::raw(
                'GROUP_CONCAT(
                    JSON_OBJECT(
                        "nama_dokumen", d.nama_dokumen,
                        "file", d.file
                    ) SEPARATOR ","
                ) AS Dokumen_Pendukung'
            ),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama IS NOT NULL THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Fasilitas_Pendukung'),
            DB::raw('(SELECT CONCAT(rs.kode_satker, ".", rp.kode_program, ".", rk.kode_kegiatan, ".", ro.kode_output, ".", rso.kode_sub_output, ".", rc.kode_komponen, ".", rsc.kode_sub_kegiatan, ".", a.kode_akun)
                FROM akun_x_rkakls axr
                JOIN akuns a ON axr.akun_id = a.id
                JOIN ref_rkakl_sub_komponens rsc ON axr.ref_sub_komponen_id = rsc.id
                JOIN ref_rkakl_komponens rc ON rsc.ref_rkakl_komponen_id = rc.id
                JOIN ref_rkakl_suboutputs rso ON rc.ref_rkakl_suboutput_id = rso.id
                JOIN ref_rkakl_outputs ro ON rso.ref_rkakl_output_id = ro.id
                JOIN ref_rkakl_kegiatans rk ON ro.ref_rkakl_kegiatan_id = rk.id
                JOIN ref_rkakl_programs rp ON rk.ref_rkakl_program_id = rp.id
                JOIN ref_rkakl_satkers rs ON rp.ref_rkakl_satker_id = rs.id
                WHERE axr.id = kp.akun_x_rkakl LIMIT 1) as MAK'),
            DB::raw('DATE_FORMAT(kp.tgl_bayar, "%d-%m-%Y") as Tanggal_Pembayaran'),
            'kp.uang_harian as Uang_Harian',
            'kp.uang_harian_fullday as Uang_Fullday',
            'kp.uang_harian_fullboard as Uang_Fullboard',
            'kp.uang_representasi as Uang_Representasi',
            'kp.persen_pajak as Pph21',
            'kp.pph22 as Pph22',
            'kp.pph23 as Pph23',
            'kp.ppn as PPN',
            DB::raw('COALESCE(SUM(CASE
                    WHEN kb.nama = "BBM" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tol" THEN kp.jumlah_harga
                    WHEN kb.nama = "Akomodasi Hotel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Transportasi Online" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Pesawat" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Kereta" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Travel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Lainnya" THEN kp.jumlah_harga
                    ELSE 0
                END), 0) +
                COALESCE(kp.uang_harian, 0) +
                COALESCE(kp.uang_harian_fullday, 0) +
                COALESCE(kp.uang_harian_fullboard, 0) +
                COALESCE(kp.uang_representasi, 0) as Total_Pembayaran'),
            'kp.status as Status_Pembayaran',
            'd.updated_at AS Tanggal_Update_Laporan',
            'ip.status_pengajuan AS Status_Kegiatan',
            'kp.tgl_kwitansi AS Tanggal_Kwitansi',
            'kp.no_kwitansi AS No_Kwitansi',
            'kp.tgl_spby AS Tanggal_SPBY',
            'kp.spby AS SPBY',
            'kp.jurnal AS Jurnal',
            'kp.drpp AS DRPP',
            'kp.id AS IdKeuangan'
        )
        ->groupBy('dp.id')
        ->orderBy('ip.id')
        ->get();

        $kegiatansNon = DB::table('data_perjadinkegiatans as ip')
        ->join('perangkat_acaras as dp', 'dp.data_perjadin_kegiatan', '=', 'ip.id')
        ->join('non_pegawais as p', 'dp.non_pegawai_id', '=', 'p.id')
        ->leftJoin('keuangan_perjadinkegiatans as kp', 'dp.id', '=', 'kp.perangkat_acara')
        ->join('laporan_perjadinkegiatans as d', 'ip.id', '=', 'd.data_perjadin_kegiatan')
        ->leftJoin('kebutuhans as kb', 'kp.kebutuhan_id', '=', 'kb.id')
        ->leftJoin('surtug_perjadinkegiatans as sp', 'ip.id', '=', 'sp.data_perjadin_kegiatan')
        ->whereBetween('ip.tgl_mulai', [$mulai, $sampai]);
        if ($status !== 'semua') {
            $kegiatansNon->where('ip.status_pengajuan', $status);
        }

        $kegiatansNon = $kegiatansNon->select(
            DB::raw('ip.tgl_mulai, ip.tgl_selesai'),
            'dp.id as dataPerangkatId',
            'ip.id as idKegiatan',
            'ip.kab_kota as Kota',
            DB::raw('CONCAT(DATEDIFF(ip.tgl_selesai, ip.tgl_mulai) + 1, " Hari") as Jumlah_Hari'),
            DB::raw('CONCAT(DATE_FORMAT(STR_TO_DATE(ip.tgl_mulai, "%Y-%m-%d"), "%e %M %Y"), " s.d ", DATE_FORMAT(STR_TO_DATE(ip.tgl_selesai, "%Y-%m-%d"), "%e %M %Y")) as Tanggal_kegiatan'),
            'p.nama_lengkap as Nama',
            'ip.nama_kegiatan as Kegiatan',
            'sp.nomor_surat as No_Surtug',
            DB::raw('COALESCE(SUM(CASE
                    WHEN kb.nama = "BBM" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tol" THEN kp.jumlah_harga
                    WHEN kb.nama = "Akomodasi Hotel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Transportasi Online" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Pesawat" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Kereta" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Travel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Lainnya" THEN kp.jumlah_harga ELSE 0
                END), 0) +
                COALESCE(kp.uang_harian, 0) +
                COALESCE(kp.uang_harian_fullday, 0) +
                COALESCE(kp.uang_harian_fullboard, 0) +
                COALESCE(kp.uang_representasi, 0) as Nominal_Bayar'),
            'd.created_at as Tgl_Terima_Berkas',
            'd.updated_at as Tgl_Berkas_Lengkap',
            DB::raw('CONCAT(dp.posisi, " (" , dp.sebagai,")") as Sebagai'),
            'sp.tgl_surat_dibuat as Tgl_Surtug',
            DB::raw(
                'GROUP_CONCAT(
                    JSON_OBJECT(
                        "nama_dokumen", d.nama_dokumen,
                        "file", d.file
                    ) SEPARATOR ","
                ) AS Dokumen_Pendukung'
            ),
            DB::raw('GROUP_CONCAT(CASE WHEN kb.nama IS NOT NULL THEN CONCAT(kb.nama, " (", kb.jumlah_frekuensi, ", ", kb.satuan, ") [Rp ", FORMAT(kp.jumlah_harga, 0, "de_DE"), "]") END SEPARATOR ", ") as Fasilitas_Pendukung'),
            DB::raw('(SELECT CONCAT(rs.kode_satker, ".", rp.kode_program, ".", rk.kode_kegiatan, ".", ro.kode_output, ".", rso.kode_sub_output, ".", rc.kode_komponen, ".", rsc.kode_sub_kegiatan, ".", a.kode_akun)
                FROM akun_x_rkakls axr
                JOIN akuns a ON axr.akun_id = a.id
                JOIN ref_rkakl_sub_komponens rsc ON axr.ref_sub_komponen_id = rsc.id
                JOIN ref_rkakl_komponens rc ON rsc.ref_rkakl_komponen_id = rc.id
                JOIN ref_rkakl_suboutputs rso ON rc.ref_rkakl_suboutput_id = rso.id
                JOIN ref_rkakl_outputs ro ON rso.ref_rkakl_output_id = ro.id
                JOIN ref_rkakl_kegiatans rk ON ro.ref_rkakl_kegiatan_id = rk.id
                JOIN ref_rkakl_programs rp ON rk.ref_rkakl_program_id = rp.id
                JOIN ref_rkakl_satkers rs ON rp.ref_rkakl_satker_id = rs.id
                WHERE axr.id = kp.akun_x_rkakl LIMIT 1) as MAK'),
            DB::raw('DATE_FORMAT(kp.tgl_bayar, "%d-%m-%Y") as Tanggal_Pembayaran'),
            'kp.uang_harian as Uang_Harian',
            'kp.uang_harian_fullday as Uang_Fullday',
            'kp.uang_harian_fullboard as Uang_Fullboard',
            'kp.uang_representasi as Uang_Representasi',
            'kp.persen_pajak as Pph21',
            'kp.pph22 as Pph22',
            'kp.pph23 as Pph23',
            'kp.ppn as PPN',
            DB::raw('COALESCE(SUM(CASE
                    WHEN kb.nama = "BBM" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tol" THEN kp.jumlah_harga
                    WHEN kb.nama = "Akomodasi Hotel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Transportasi Online" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Pesawat" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Kereta" THEN kp.jumlah_harga
                    WHEN kb.nama = "Tiket Travel" THEN kp.jumlah_harga
                    WHEN kb.nama = "Lainnya" THEN kp.jumlah_harga
                    ELSE 0
                END), 0) +
                COALESCE(kp.uang_harian, 0) +
                COALESCE(kp.uang_harian_fullday, 0) +
                COALESCE(kp.uang_harian_fullboard, 0) +
                COALESCE(kp.uang_representasi, 0) as Total_Pembayaran'),
            'kp.status as Status_Pembayaran',
            'd.updated_at AS Tanggal_Update_Laporan',
            'ip.status_pengajuan AS Status_Kegiatan',
            'kp.tgl_kwitansi AS Tanggal_Kwitansi',
            'kp.no_kwitansi AS No_Kwitansi',
            'kp.tgl_spby AS Tanggal_SPBY',
            'kp.spby AS SPBY',
            'kp.jurnal AS Jurnal',
            'kp.drpp AS DRPP',
            'kp.id AS IdKeuangan'
        )
        ->groupBy('dp.id')
        ->orderBy('ip.id')
        ->get();

        // Kembalikan ONLY_FULL_GROUP_BY ke default
        DB::statement("SET SESSION sql_mode=CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY')");

        // Decode JSON string menjadi array
        foreach ($kegiatans as $kegiatan) {
            if ($kegiatan->Dokumen_Pendukung) {
                $kegiatan->dokumen_list = json_decode('[' . $kegiatan->Dokumen_Pendukung . ']', true);
            } else {
                $kegiatan->dokumen_list = [];
            }
        }

        // Decode JSON string menjadi array
        foreach ($kegiatansNon as $kegiatan) {
            if ($kegiatan->Dokumen_Pendukung) {
                $kegiatan->dokumen_list = json_decode('[' . $kegiatan->Dokumen_Pendukung . ']', true);
            } else {
                $kegiatan->dokumen_list = [];
            }
        }

            $kegiatanIds = $kegiatans->pluck('id');


        $HKTselesaiId = DB::table('data_perjadinkegiatans')
            ->where('is_acceptHKT', 'selesai')
            ->pluck('id');


        $surtugExist = DB::table('surtug_perjadinkegiatans')
            ->whereIn('data_perjadin_kegiatan', $kegiatanIds)
            ->pluck('data_perjadin_kegiatan');


        foreach ($kegiatans as $info) {

            $tglMulai = \Carbon\Carbon::parse($info->tgl_mulai)->startOfDay();
            $tglSelesai = \Carbon\Carbon::parse($info->tgl_selesai)->endOfDay();

            $jumlahHari = $tglMulai->diffInDays($tglSelesai) + 1;

            $info->jumlah_hari = $jumlahHari;
            $info->dokumen = DB::table('laporan_perjadinkegiatans')
                ->where('data_perjadin_kegiatan', $info->idKegiatan)
                ->get();
        }

        $akuns = DB::table('akun_x_rkakls')
            ->join('akuns', 'akun_x_rkakls.akun_id', '=', 'akuns.id')
            ->join('ref_rkakl_sub_komponens', 'akun_x_rkakls.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
            ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
            ->select('akun_x_rkakls.id as idAkun', 'ref_rkakl_sub_komponens.nama_sub_kegiatan', 'akuns.uraian', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program', 'ref_rkakl_kegiatans.kode_kegiatan', 'ref_rkakl_outputs.kode_output', 'ref_rkakl_suboutputs.kode_sub_output', 'ref_rkakl_komponens.kode_komponen', 'ref_rkakl_sub_komponens.kode_sub_kegiatan', 'ref_rkakl_sub_komponens.nama_sub_kegiatan', 'akuns.kode_akun', 'akuns.uraian')
            ->get();
        $operasionals = DB::table('operasionals')
            ->join('keuangan_perjadinkegiatans', 'operasionals.id', '=', 'keuangan_perjadinkegiatans.operasional')
            ->select('operasionals.nama', 'operasionals.jumlah_frekuensi', 'operasionals.satuan', 'operasionals.detail_satuan', 'keuangan_perjadinkegiatans.jumlah_harga', 'keuangan_perjadinkegiatans.status', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
            ->get();
        $dibayarkan = DB::table('keuangan_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
            ->where('keuangan_perjadinkegiatans.status', 'Sudah Dibayarkan')
            ->whereBetween('data_perjadinkegiatans.tgl_mulai', [$mulai, $sampai])
            ->count();
        $totaldibayarkan = DB::table('keuangan_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
            ->where('keuangan_perjadinkegiatans.status', 'Sudah Dibayarkan')
            ->whereBetween('data_perjadinkegiatans.tgl_mulai', [$mulai, $sampai])
            ->sum('keuangan_perjadinkegiatans.jumlah_harga');
        $blmdibayarkan = DB::table('keuangan_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
            ->where('keuangan_perjadinkegiatans.status', 'Belum Dibayarkan')
            ->whereBetween('data_perjadinkegiatans.tgl_mulai', [$mulai, $sampai])
            ->count();
        $totalblmdibayarkan = DB::table('keuangan_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
            ->where('keuangan_perjadinkegiatans.status', 'Belum Dibayarkan')
            ->whereBetween('data_perjadinkegiatans.tgl_mulai', [$mulai, $sampai])
            ->sum('keuangan_perjadinkegiatans.jumlah_harga');
        $tdkdibayarkan = DB::table('keuangan_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
            ->where('keuangan_perjadinkegiatans.status', 'Tidak Dibayarkan')
            ->whereBetween('data_perjadinkegiatans.tgl_mulai', [$mulai, $sampai])
            ->count();
        $totaltdkdibayarkan = DB::table('keuangan_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
            ->where('keuangan_perjadinkegiatans.status', 'Tidak Dibayarkan')
            ->whereBetween('data_perjadinkegiatans.tgl_mulai', [$mulai, $sampai])
            ->sum('keuangan_perjadinkegiatans.jumlah_harga');

        $kebutuhan = DB::table('keuangan_perjadinkegiatans')
            ->join('kebutuhans', 'keuangan_perjadinkegiatans.kebutuhan_id', '=', 'kebutuhans.id')
            ->select('kebutuhans.id AS idKebutuhan',  'keuangan_perjadinkegiatans.kebutuhan_id','kebutuhans.nama', 'kebutuhans.satuan', 'kebutuhans.jumlah_frekuensi',  'kebutuhans.satuan', 'keuangan_perjadinkegiatans.jumlah_harga', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', 'keuangan_perjadinkegiatans.status',  'keuangan_perjadinkegiatans.perangkat_acara' )
            ->get();
        // ddd($kegiatans);
        return view('admin.other.LaporanUsulanKegiatan', [
            'title' => 'Laporan Kegiatan',
            'kegiatans' => $kegiatans,
            'kegiatanNons' => $kegiatansNon,
            'akuns' => $akuns,
            'mulai' => $mulai,
            'sampai'=> $sampai,

            'laporans' => Laporan_perjadinkegiatan::all(),
            'operasionals' => $operasionals,
            'dibayarkan' => $dibayarkan,
            'blmdibayarkan' => $blmdibayarkan,
            'tdkdibayarkan' => $tdkdibayarkan,
            'totaldibayarkan' => $totaldibayarkan,
            'totalblmdibayarkan' => $totalblmdibayarkan,
            'totaltdkdibayarkan' => $totaltdkdibayarkan,
            'surtugs' => DB::table('surtug_perjadinkegiatans')->get(),
            'surtugExist' => $surtugExist,
            'kebutuhan' => $kebutuhan
        ]);
    }


    public function getTarifPajak($status, $golongan)
    {
        if (is_null($status) || $status == 'null' || $status == '') {
            $status = '-';
        }

        if (is_null($golongan)) {
            $golongan = '-';
        } else {
            $golongan = str_replace('-', '/', $golongan); // Mengubah IV-E menjadi IV/E
        }

        $default = DB::table('ref_data_pajak')
                ->where('status', 'default')
                ->where('golongan', '-')
                ->first();

        $pajak = DB::table('ref_data_pajak')
            ->where('status', $status)
            ->where('golongan', $golongan)
            ->first();

        // dd($pajak);

        if (!$pajak) {
            $status = 'default';
            $golongan = '-';
            $pajak = DB::table('ref_data_pajak')
                ->where('status', $status)
                ->where('golongan', $golongan)
                ->first();
        }

        if ($pajak) {
            return response()->json([
                'tarif_pajak' => $pajak->tarif_pajak,
                'default' => $default->tarif_pajak,
            ]);
        } else {
            return response()->json([
                'tarif_pajak' => "0",
                'default' => $default->tarif_pajak,]);
        }
    }
}
