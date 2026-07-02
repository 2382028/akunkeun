<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\Dokumen;
use App\Http\Controllers\Controller;
use App\Models\Mobilitas_perjadinkegiatan;
use App\Models\Kendaraan;
use App\Models\Kebutuhan;
use App\Models\Fasilitas;
use App\Models\Data_perjadinkegiatan;
use App\Models\Keuangan_perjadinkegiatan;
use App\Models\Laporan_perjadinkegiatan;
use App\Models\Ref_sbm;
use App\Models\Versi;
use App\Models\Operasional;
use App\Models\Perangkat_acara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Http\Controllers\AdminOtherController;

class AdminKegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($status = 'pengajuan')
    {
        $mobilitas = DB::table('mobilitas_perjadinkegiatans')
                        ->join('data_perjadinkegiatans', 'mobilitas_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
                        ->join('versis', 'mobilitas_perjadinkegiatans.versi_id', '=', 'versis.id')
                        ->select('data_perjadinkegiatans.id as idKegiatan', 'mobilitas_perjadinkegiatans.id as idMobilitas','data_perjadinkegiatans.nama_kegiatan', 'mobilitas_perjadinkegiatans.tujuan_penggunaan', 'mobilitas_perjadinkegiatans.tgl_mulai', 'mobilitas_perjadinkegiatans.status')
                        ->where('data_perjadinkegiatans.versi_id', session('versi'))
                        ->where('mobilitas_perjadinkegiatans.status', $status)
                        ->whereNotNull('data_perjadinkegiatans.is_acceptBMN')
                        ->get();
        return view('admin.kegiatan.mobilitas.index', [
            'title' => 'Mobilitas Kegiatan',
            'mobilitass' => $mobilitas
        ]);
    }

    public function HKTIndex($status = 'pengajuan')
    {
        // Nonaktifkan ONLY_FULL_GROUP_BY
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");

        // Ambil data kegiatan dan dokumen yang relevan
        $kegiatan = DB::table('data_perjadinkegiatans')
        ->leftjoin('laporan_perjadinkegiatans', 'data_perjadinkegiatans.id', '=', 'laporan_perjadinkegiatans.data_perjadin_kegiatan')
        ->leftJoin('surtug_perjadinkegiatans', 'data_perjadinkegiatans.id', '=', 'surtug_perjadinkegiatans.data_perjadin_kegiatan') // Join dengan tabel surtug_perjadinkegiatans
        ->leftJoin('pegawais', 'data_perjadinkegiatans.id_pengaju', '=', 'pegawais.id') // Left join ke pegawais
        ->leftJoin('non_pegawais', 'data_perjadinkegiatans.id_pengaju', '=', 'non_pegawais.id') // Left join ke non_pegawais
        ->leftJoin('administrators', 'data_perjadinkegiatans.id_pengaju', '=', 'administrators.id') // Left join ke administrators
        ->where('data_perjadinkegiatans.is_acceptHKT', $status)
        ->where('data_perjadinkegiatans.versi_id', session('versi'))
        ->whereNotNull('laporan_perjadinkegiatans.file')
        ->select(
            'data_perjadinkegiatans.*',
            'laporan_perjadinkegiatans.file',
            'surtug_perjadinkegiatans.nomor_surat',      // Kolom dari surtug_perjadinkegiatans
            'surtug_perjadinkegiatans.perihal',
            'surtug_perjadinkegiatans.paragraf_1',
            'surtug_perjadinkegiatans.paragraf_2',
            'surtug_perjadinkegiatans.paragraf_3',
            'surtug_perjadinkegiatans.tgl_surat_dibuat',
            DB::raw('COALESCE(CONCAT("(Admin) ", administrators.username), pegawais.nama_lengkap, non_pegawais.nama_lengkap, "Tidak ditemukan") as nama_pengaju')

        )
        ->GroupBy('data_perjadinkegiatans.id')
        ->get();

        // Kembalikan ONLY_FULL_GROUP_BY ke default
        DB::statement("SET SESSION sql_mode=CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY')");



            $kegiatanIds = $kegiatan->pluck('id');
            // Ambil semua id_kegiatan dari kegiatan

        // Ambil id untuk HKT selesai
        $HKTselesaiId = DB::table('data_perjadinkegiatans')
            ->where('is_acceptHKT', 'selesai')
            ->pluck('id');

        // Ambil id perjadinkegiatan dari surtug_perjadinkegiatans
        $surtugExist = DB::table('surtug_perjadinkegiatans')
            ->whereIn('data_perjadin_kegiatan', $kegiatanIds)
            ->pluck('data_perjadin_kegiatan');

        // Tambahkan dokumen ke setiap item kegiatan
        foreach ($kegiatan as $info) {
            // Konversi tanggal ke dalam objek Carbon untuk perhitungan
            $tglMulai = \Carbon\Carbon::parse($info->tgl_mulai)->startOfDay();
            $tglSelesai = \Carbon\Carbon::parse($info->tgl_selesai)->endOfDay();

            // Hitung selisih hari
            $jumlahHari = $tglMulai->diffInDays($tglSelesai) + 1;

            // Tambahkan jumlah hari ke objek $info
            $info->jumlah_hari = $jumlahHari;

            // Ambil dokumen yang sesuai untuk setiap perjadin
            $info->dokumen = DB::table('laporan_perjadinkegiatans')
                ->where('data_perjadin_kegiatan', $info->id)
                ->get();
        }

        return view('admin.kegiatan.HKT.index', [
            'title' => 'HKT Perjadin Kegiatan',
            'kegiatans' => $kegiatan,
            'surtugs' => DB::table('surtug_perjadinkegiatans')->get(), // Mengambil semua data dari surtug_perjadinkegiatans
            'surtugExist' => $surtugExist,
        ]);
    }
    public function detail_kegiatan_HKT($id)
    {
        $pesertaPegawais = DB::table('perangkat_acaras')
            ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
            ->select('pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'perangkat_acaras.posisi')
            ->where('perangkat_acaras.data_perjadin_kegiatan', $id)
            ->where('perangkat_acaras.posisi', '!=', 'Supir')
            ->get();
        $pesertaNonPegawais = DB::table('perangkat_acaras')
            ->join('non_pegawais', 'perangkat_acaras.non_pegawai_id', '=', 'non_pegawais.id')
            ->select('non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'perangkat_acaras.posisi')
            ->where('perangkat_acaras.data_perjadin_kegiatan', $id)
            ->where('perangkat_acaras.posisi', '!=', 'Supir')
            ->get();
        $pengemudi =  DB::table('perangkat_acaras')
            ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
            ->select('pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'perangkat_acaras.posisi')
            ->where('perangkat_acaras.data_perjadin_kegiatan', $id)
            ->where('perangkat_acaras.posisi', 'Supir')
            ->get();
        $pengemudi =  DB::table('perangkat_acaras')
            ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
            ->select('pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'perangkat_acaras.posisi')
            ->where('perangkat_acaras.data_perjadin_kegiatan', $id)
            ->where('perangkat_acaras.posisi', 'Supir')
            ->get();
        $dokumen =  DB::table('laporan_perjadinkegiatans')
            ->select('*')
            ->where('laporan_perjadinkegiatans.data_perjadin_kegiatan', $id)
            ->get();

        return view('admin.kegiatan.HKT.detail', [
            'title' => 'Pengajuan Surtug',
            'pesertaPegawais' => $pesertaPegawais,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'dokumen' => $dokumen,
            'kegiatan' => Data_perjadinkegiatan::find($id),
            'mobilitass' => Mobilitas_perjadinkegiatan::where('data_perjadinkegiatan', $id)->get(),
            'pengemudis' => $pengemudi,
        ]);
    }



    // ini function untuk edit saat di button edit di index.blade

    public function detail_surtug_kegiatan_HKT_edit($id)
    {
        $surat = DB::table('surtug_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'surtug_perjadinkegiatans.data_perjadin_kegiatan', '=', 'data_perjadinkegiatans.id')
            ->select('surtug_perjadinkegiatans.is_table as isTable','surtug_perjadinkegiatans.nomor_surat', 'surtug_perjadinkegiatans.perihal', 'surtug_perjadinkegiatans.paragraf_1', 'surtug_perjadinkegiatans.paragraf_2', 'surtug_perjadinkegiatans.paragraf_3')
            ->where('surtug_perjadinkegiatans.data_perjadin_kegiatan', $id)
            ->get();

        return view('admin.kegiatan.HKT.edit_surtug', [
            'title' => 'Edit Surat Tugas',
            'kegiatan' => Data_perjadinkegiatan::find($id),
            'surtugs' => $surat,
        ]);
    }

    public function surtug_kegiatan_HKT($id)
    {
        $surat = DB::table('surtug_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'surtug_perjadinkegiatans.data_perjadin_kegiatan', '=', 'data_perjadinkegiatans.id')
            ->select('surtug_perjadinkegiatans.nomor_surat', 'surtug_perjadinkegiatans.perihal', 'surtug_perjadinkegiatans.paragraf_1', 'surtug_perjadinkegiatans.paragraf_2', 'surtug_perjadinkegiatans.paragraf_3')
            ->where('surtug_perjadinkegiatans.data_perjadin_kegiatan', $id)
            ->get();

        return view('admin.kegiatan.HKT.surtug', [
            'title' => 'Pembuatan Surat Tugas',
            'kegiatan' => Data_perjadinkegiatan::find($id),
            'surtugs' => $surat,

        ]);
    }

    public function detail_surtug_kegiatan_HKT($id)
    {
        $pesertaPegawais = DB::table('perangkat_acaras')
            ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->select('pegawais.id','pegawais.NIP_NIK','pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'perangkat_acaras.posisi', 'pegawais.NIP_NIK', 'jabatans.nama_jabatan')
            ->where('perangkat_acaras.data_perjadin_kegiatan', $id)
            ->where(function ($query) {
                $query->where('perangkat_acaras.posisi', '!=', 'Supir')
                    ->orWhere(function ($query) {
                        $query->where('perangkat_acaras.posisi', '=', 'Supir')
                            ->whereNotExists(function ($query) {
                                $query->select(DB::raw(1))
                                    ->from('perangkat_acaras as dp2')
                                    ->whereColumn('dp2.pegawai_id', 'perangkat_acaras.pegawai_id')
                                    ->where('dp2.posisi', '!=', 'Supir');
                            });
                    });
            })
            ->orderBy('perangkat_acaras.posisi', 'asc')
            ->get();


        $pesertaNonPegawais = DB::table('perangkat_acaras')
            ->join('non_pegawais', 'perangkat_acaras.non_pegawai_id', '=', 'non_pegawais.id')
            ->select('non_pegawais.id','non_pegawais.NIP_NIK','non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'perangkat_acaras.posisi')
            ->where('perangkat_acaras.data_perjadin_kegiatan', $id)
            ->get();

        $pengemudi = DB::table('perangkat_acaras')
            ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->select('pegawais.id','pegawais.NIP_NIK','pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'perangkat_acaras.posisi', 'pegawais.NIP_NIK', 'jabatans.nama_jabatan')
            ->where('perangkat_acaras.data_perjadin_kegiatan', $id)
            ->where('perangkat_acaras.posisi', '=', 'Supir')
            ->orderBy('perangkat_acaras.posisi', 'asc')
            ->get();

        $surat = DB::table('surtug_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'surtug_perjadinkegiatans.data_perjadin_kegiatan', '=', 'data_perjadinkegiatans.id')
            ->select('surtug_perjadinkegiatans.nomor_surat', 'surtug_perjadinkegiatans.perihal', 'surtug_perjadinkegiatans.paragraf_1', 'surtug_perjadinkegiatans.paragraf_2', 'surtug_perjadinkegiatans.paragraf_3')
            ->where('surtug_perjadinkegiatans.data_perjadin_kegiatan', $id)
            ->get();

        $tipeSurtug = DB::table('surtug_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'surtug_perjadinkegiatans.data_perjadin_kegiatan', '=', 'data_perjadinkegiatans.id')
            ->select('surtug_perjadinkegiatans.is_table AS isTable')
            ->where('surtug_perjadinkegiatans.data_perjadin_kegiatan', $id)
            ->first();
            
         $pegawaiKepala = DB::table('ref_penandatangans')
            ->join('pegawais', 'pegawais.id', '=', 'ref_penandatangans.pegawai_id')
            ->select('pegawais.*')
            ->where('ref_penandatangans.posisi_penandatangan', 'Kepala')
            ->where('ref_penandatangans.status_penandatangan', 'aktif')
            ->first();
            
        return view('admin.kegiatan.HKT.preview_surtug', [
            'title' => 'Pembuatan Surat Tugas',
            'pegawaiKepala' => $pegawaiKepala,
            'pesertaPegawais' => $pesertaPegawais,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'kegiatan' => Data_perjadinkegiatan::find($id),
            'pengemudis' => $pengemudi,
            'surtugs' => $surat,
            'tipeSurtug' => $tipeSurtug
        ]);
    }

    public function ConvertSurtug($id)
    {
        if (!$id) {
            // Handle jika data tidak ditemukan
            dd('Data tidak ditemukan.');
        }

        $pesertaPegawais = DB::table('perangkat_acaras')
            ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->select('pegawais.id','pegawais.NIP_NIK','pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'perangkat_acaras.posisi', 'pegawais.NIP_NIK', 'jabatans.nama_jabatan')
            ->where('perangkat_acaras.data_perjadin_kegiatan', $id)
            ->where(function ($query) {
                $query->where('perangkat_acaras.posisi', '!=', 'Supir')
                    ->orWhere(function ($query) {
                        $query->where('perangkat_acaras.posisi', '=', 'Supir')
                            ->whereNotExists(function ($query) {
                                $query->select(DB::raw(1))
                                    ->from('perangkat_acaras as dp2')
                                    ->whereColumn('dp2.pegawai_id', 'perangkat_acaras.pegawai_id')
                                    ->where('dp2.posisi', '!=', 'Supir');
                            });
                    });
            })
            ->orderBy('perangkat_acaras.posisi', 'asc')
            ->get();


        $pesertaNonPegawais = DB::table('perangkat_acaras')
            ->join('non_pegawais', 'perangkat_acaras.non_pegawai_id', '=', 'non_pegawais.id')
            ->select('non_pegawais.id','non_pegawais.NIP_NIK','non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'perangkat_acaras.posisi')
            ->where('perangkat_acaras.data_perjadin_kegiatan', $id)
            ->get();

        $pengemudi = DB::table('perangkat_acaras')
            ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->select('pegawais.id','pegawais.NIP_NIK','pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'perangkat_acaras.posisi', 'pegawais.NIP_NIK', 'jabatans.nama_jabatan')
            ->where('perangkat_acaras.data_perjadin_kegiatan', $id)
            ->where('perangkat_acaras.posisi', '=', 'Supir')
            ->orderBy('perangkat_acaras.posisi', 'asc')
            ->get();

        $surat = DB::table('surtug_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'surtug_perjadinkegiatans.data_perjadin_kegiatan', '=', 'data_perjadinkegiatans.id')
            ->select('surtug_perjadinkegiatans.nomor_surat', 'surtug_perjadinkegiatans.perihal', 'surtug_perjadinkegiatans.paragraf_1', 'surtug_perjadinkegiatans.paragraf_2', 'surtug_perjadinkegiatans.paragraf_3')
            ->where('surtug_perjadinkegiatans.data_perjadin_kegiatan', $id)
            ->get();

        $tipeSurtug =DB::table('surtug_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'surtug_perjadinkegiatans.data_perjadin_kegiatan', '=', 'data_perjadinkegiatans.id')
            ->select('surtug_perjadinkegiatans.is_table AS isTable')
            ->where('surtug_perjadinkegiatans.data_perjadin_kegiatan', $id)
            ->first();
            
        $pegawaiKepala = DB::table('ref_penandatangans')
            ->join('pegawais', 'pegawais.id', '=', 'ref_penandatangans.pegawai_id')
            ->select('pegawais.*')
            ->where('ref_penandatangans.posisi_penandatangan', 'Kepala')
            ->where('ref_penandatangans.status_penandatangan', 'aktif')
            ->first();

        
        $datas = [
            'pesertaPegawais' => $pesertaPegawais,
            'pengemudis' => $pengemudi,
            'pegawaiKepala' => $pegawaiKepala,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'kegiatan' => Data_perjadinkegiatan::find($id),
            'surtugs' => $surat,
            'tipeSurtug' => $tipeSurtug
        ];
        // Lakukan proses pembuatan file PDF
        $pdf = PDF::loadView('admin.kegiatan.HKT.surtug_detail', compact('datas'));
        $pdf->setPaper('A4', 'portrait');

        // Simpan file PDF ke penyimpanan
        $filePath = $pdf->output();
        Storage::disk('public')->put('dokumen-kegiatans/surtugKegiatan.pdf', $filePath);

        // Stream file PDF ke browser
        return $pdf->stream('surtugKegiatan.pdf');
    }

    public function storeSurtug(Request $request)
    {
        // Validasi input yang diterima
        $validatedData = $request->validate([
            'perihal' => 'nullable|string',
            'paragraf1' => 'nullable|string',
            'paragraf2' => 'nullable|string',
            'paragraf3' => 'nullable|string',
        ]);

        $id = $request->input('idKegiatan'); // 'id' sesuai dengan parameter dalam URL

        $exists = DB::table('surtug_perjadinkegiatans')->where('data_perjadin_kegiatan', $id)->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Surat Tugas sudah ada untuk perjalanan dinas ini.');
        }

        
        if ($request->tipeSurtug == 'true'){
            $isTable = 1;
        } else {
            $isTable = 0;
        }

        DB::table('surtug_perjadinkegiatans')->Insert([
            'data_perjadin_kegiatan' => $request->idKegiatan,
            'perihal' => $request->perihal,
            'paragraf_1' => $request->paragraf1,
            'paragraf_2' => $request->paragraf2,
            'paragraf_3' => $request->paragraf3,
            'is_table' => $isTable, // Menyimpan HTML apa adanya
        ]);
        $id = $request->idKegiatan;
        return redirect()->route('surtug-detail-HKT-kegiatan', ['id' => $id])->with('success', 'Surat Tugas Berhasil Ditambahkan!');
    }

    public function StoreSurtugPDF(Request $request)
    {
        $id = $request->input('idKegiatan'); // 'id' sesuai dengan parameter dalam URL
        if (!$id) {
            dd('Data tidak ditemukan.');
        }

        $pesertaPegawais = DB::table('perangkat_acaras')
            ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->select('pegawais.id','pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'perangkat_acaras.posisi', 'pegawais.NIP_NIK','jabatans.nama_jabatan')
            ->where('perangkat_acaras.data_perjadin_kegiatan', $id)
            ->get();

        $pesertaNonPegawais = DB::table('perangkat_acaras')
            ->join('non_pegawais', 'perangkat_acaras.non_pegawai_id', '=', 'non_pegawais.id')
            ->select('non_pegawais.id','non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'perangkat_acaras.posisi')
            ->where('perangkat_acaras.data_perjadin_kegiatan', $id)
            ->get();

        $pengemudi = DB::table('pegawais')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->join('peminjaman_kendaraan_dinas', 'peminjaman_kendaraan_dinas.pegawai_id', '=', 'pegawais.id')
            ->join('mobilitas_perjadinkegiatans', 'peminjaman_kendaraan_dinas.mobilitas_perjadinkegiatan', '=', 'mobilitas_perjadinkegiatans.id')
            ->select('pegawais.id', 'pegawais.nama_lengkap', 'jabatans.nama_jabatan', 'pegawais.NIP_NIK', 'pegawais.pangkat', 'pegawais.golongan')
            ->where('jabatans.nama_jabatan', 'Pengemudi')
            ->where('peminjaman_kendaraan_dinas.mobilitas_perjadinkegiatan', $id)
            ->get();

        $surat = DB::table('surtug_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'surtug_perjadinkegiatans.data_perjadin_kegiatan', '=', 'data_perjadinkegiatans.id')
            ->select('surtug_perjadinkegiatans.nomor_surat', 'surtug_perjadinkegiatans.perihal', 'surtug_perjadinkegiatans.paragraf_1', 'surtug_perjadinkegiatans.paragraf_2', 'surtug_perjadinkegiatans.paragraf_3')
            ->where('surtug_perjadinkegiatans.data_perjadin_kegiatan', $id)
            ->get();

        $tipeSurtug = DB::table('surtug_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'surtug_perjadinkegiatans.data_perjadin_kegiatan', '=', 'data_perjadinkegiatans.id')
            ->select('surtug_perjadinkegiatans.is_table AS isTable')
            ->where('surtug_perjadinkegiatans.data_perjadin_kegiatan', $id)
            ->first();
            
         $pegawaiKepala = DB::table('ref_penandatangans')
            ->join('pegawais', 'pegawais.id', '=', 'ref_penandatangans.pegawai_id')
            ->select('pegawais.*')
            ->where('ref_penandatangans.posisi_penandatangan', 'Kepala')
            ->where('ref_penandatangans.status_penandatangan', 'aktif')
            ->first();

        $datas = [
            'pesertaPegawais' => $pesertaPegawais,
            'pegawaiKepala' => $pegawaiKepala,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'kegiatan' => Data_perjadinkegiatan::find($id),
            'pengemudis' => $pengemudi,
            'surtugs' => $surat,
            'tipeSurtug' => $tipeSurtug
        ];

        // Lakukan proses pembuatan file PDF
        $pdf = PDF::loadView('admin.kegiatan.HKT.surtug_detail', compact('datas'));
        $pdf->setPaper('A4', 'portrait');

        // Simpan file PDF ke penyimpanan
        $filePath = $pdf->output();
        Storage::disk('public')->put('dokumen-kegiatans/surtugKegiatan.pdf', $filePath);

        // Simpan data ke tabel 'dokumens'
        DB::table('data_perjadinkegiatans')
            ->where('id', $id)
            ->update([
                'surat_tugas' => 'dokumen-kegiatans/surtugKegiatan.pdf',
                'is_acceptHKT' => 'pengajuan',
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

        return redirect()->route('HKT-kegiatan', ['status' => 'pengajuan'])->with('success', 'Surat Tugas telah berhasil dibuat!');
    }

    public function UpdateSurtug(Request $request)
    {


        $validationData = $request->validate([
            'surat_tugas_update' => 'required|mimes:pdf|file|max:2048',
        ]);

        if (empty($validationData)) {
            dd('Gagal Upload');
        }

        $id = $request->input('idKegiatanUpdate'); // 'id' sesuai dengan parameter dalam URL
        if (!$id) {
            dd('Data tidak ditemukan.');
        }
        $kegiatan = DB::table('data_perjadinkegiatans')->where('id', $id)->first();
        if (!$kegiatan) {
            dd('Data tidak ditemukan.');
        }
        $surtugName = 'Surat Tugas '. $id;
        DB::table('laporan_perjadinkegiatans')
            ->where('nama_dokumen', $surtugName)
            ->update([
                'file' => $validationData['surat_tugas_update'] = $request->file('surat_tugas_update')->store('dokumen-kegiatans', 'public'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        DB::table('data_perjadinkegiatans')
            ->where('id', $id)
            ->update([
                'surat_tugas' => $validationData['surat_tugas_update'] = $request->file('surat_tugas_update')->store('dokumen-kegiatans', 'public'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        DB::table('data_perjadinkegiatans')
            ->where('id', $id)
            ->update([
                'kode_surat_tugas' => $request->nomor_surtug_update,
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        DB::table('surtug_perjadinkegiatans')
            ->where('data_perjadin_kegiatan', $id)
            ->update([
                'nomor_surat' => $request->nomor_surtug_update,
                'tgl_surat_dibuat' => $request->tgl_dibuat_update,
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);


        return redirect()->route('HKT-kegiatan', ['status' => 'selesai'])->with('success', 'Surat Tugas telah berhasil di Update!');
    }

    public function UploadSurtug(Request $request)
    {



        $validationData = $request->validate([
            'surat_tugas' => 'required|mimes:pdf|file|max:2048',
        ]);

        if (empty($validationData)) {
            dd('Gagal Upload');
        }

        $id = $request->input('idKegiatan'); // 'id' sesuai dengan parameter dalam URL
        if (!$id) {
            dd('Data tidak ditemukan.');
        }
        $perjadin = DB::table('data_perjadinkegiatans')->where('id', $id)->first();
        if (!$perjadin) {
            dd('Data tidak ditemukan.');
        }
        $pengusul = DB::table('pegawais')
            ->join('data_perjadinkegiatans', 'pegawais.id', '=', 'data_perjadinkegiatans.id_pengaju')
            ->select('data_perjadinkegiatans.id_pengaju', 'pegawais.nama_lengkap')
            ->where('data_perjadinkegiatans.id',$id)
            ->first();


        DB::table('data_perjadinkegiatans')
            ->where('id', $id)
            ->whereNull('is_acceptKeu')
            ->update([
                'is_acceptBend' => 'approval-1',
                'status_pengajuan_detail' => 'Approval-1-Bendahara',
                'kode_surat_tugas' => $request->nomor_surtug,
                'is_acceptHKT' => 'selesai',
                'surat_tugas' => $validationData['surat_tugas'] = $request->file('surat_tugas')->store('dokumen-kegiatans', 'public'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        DB::table('surtug_perjadinkegiatans')
            ->where('data_perjadin_kegiatan', $id)
            ->update([
                'nomor_surat' => $request->nomor_surtug,
                'tgl_surat_dibuat' => $request->tgl_dibuat,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

            db::table('laporan_perjadinkegiatans')->insertOrIgnore([
                'nama_dokumen' => 'Surat Tugas '.$id,
                'file' => $validationData['surat_tugas'] = $request->file('surat_tugas')->store('dokumen-kegiatans', 'public'),
                'data_perjadin_kegiatan' => $id,
                'status' => 'diajukan',
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),

            ]);

        $dataNotif = [
            'id_kegiatan' => $id,
            'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
            'to' => 0, // ID pengguna yang menerima
            'role' => 'Bendahara', // Peran pengguna
            'header' => 'Usulan Perjalanan Dinas - '.$id, // Judul notifikasi
            'message' => 'Usulan oleh '.$id.' telah diverifikasi HKT dan Surtug sudah tersedia', // Isi pesan
            'route' => 'perjadin-bendahara/detail/'.$id, // Route yang dituju
            'is_read' => 0, // Status belum dibaca
            'versi_id' => session('versi'),
            'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
        ];

        // Melakukan insert ke tabel notifications
        DB::table('notifications')->insert($dataNotif);

    $dataNotifUser = [
            'id_kegiatan' => $id,
            'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
            'to' => '0', // ID pengguna yang menerima
            'role' => null, // Peran pengguna
            'header' => 'Usulan Perjadin Disetujui HKT - '.$id, // Judul notifikasi
            'message' => 'Surat Tugas untuk Perjalanan Dinas '.$id.' telah terbit, silakan menunggu Approval-1 Bendahara', // Isi pesan
            'route' => 'perjadin/riwayat/proses', // Route yang dituju
            'is_read' => 0, // Status belum dibaca
            'versi_id' => session('versi'),
            'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
            ];

    //     // Melakukan insert ke tabel notifications
        DB::table('notifications')->insert($dataNotifUser);


        return redirect()->route('HKT-kegiatan', ['status' => 'pengajuan'])->with('success', 'Surat Tugas telah berhasil di Upload!');
    }


    public function EditSurtug(Request $request)
    {
        // Validasi input yang diterima
        $validatedData = $request->validate([
            'perihal' => 'nullable|string',
            'paragraf1' => 'nullable|string',
            'paragraf2' => 'nullable|string',
            'paragraf3' => 'nullable|string',
        ]);

        $id = $request->input('idKegiatan'); // 'id' sesuai dengan parameter dalam URL
        if (!$id) {
            dd('Data tidak ditemukan.');
        }

        if ($request->tipeSurtug == 'true'){
            $isTable = 1;
        } else {
            $isTable = 0;
        }

        DB::table('surtug_perjadinkegiatans')
            ->where('data_perjadin_kegiatan', $id)
            ->update([
                'perihal' => $request->perihal,
                'paragraf_1' => $request->paragraf1,
                'paragraf_2' => $request->paragraf2,
                'paragraf_3' => $request->paragraf3,
                'is_table' => $isTable,
            ]);

        return redirect()->route('surtug-detail-HKT-kegiatan', ['id' => $id])->with('success', 'Surat Tugas Berhasil Diubah!');
    }

    //

    public function assetIndex($status = 'pengajuan')
    {
        $peminjamansapras = DB::table('peminjaman_sarpras')
                        ->join('data_perjadinkegiatans', 'peminjaman_sarpras.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
                        ->join('pegawais', 'peminjaman_sarpras.pegawai_id', '=', 'pegawais.id')
                        ->join('assets', 'peminjaman_sarpras.asset', '=', 'assets.id')
                        ->select('data_perjadinkegiatans.id as idPeminjaman','pegawais.nama_lengkap', 'assets.nama_barang', 'peminjaman_sarpras.status')
                        ->where('peminjaman_sarpras.status', $status)
                        ->where('data_perjadinkegiatans.is_acceptAset', 'pengajuan')
                        ->where('data_perjadinkegiatans.versi_id', session('versi'))
                        ->get();
        return view('admin.kegiatan.assets.index', [
            'title' => 'Peminjaman Assets',
            'peminjamans' => $peminjamansapras,
        ]);
    }

    public function bendaharaIndex($status = 'approval-1')
    {
        $kegiatans = DB::table('data_perjadinkegiatans')
                         ->where('is_acceptBend', $status)
                         ->where('versi_id', session('versi'))
                         ->get();

        return view('admin.kegiatan.bendahara.index', [
            'title' => 'Bendahara Kegiatan',
            'kegiatans' => $kegiatans,
            'status' => $status
        ]);
    }

    public function keuanganIndex($status = 'verifikasi-1')
    {
        $kegiatans = DB::table('data_perjadinkegiatans')
             ->leftJoin('laporan_perjadinkegiatans', function($join) {
                 $join->on('data_perjadinkegiatans.id', '=', 'laporan_perjadinkegiatans.data_perjadin_kegiatan')
                  ->where('laporan_perjadinkegiatans.nama_dokumen', 'LIKE', DB::raw("CONCAT('SPPD Kegiatan ', data_perjadinkegiatans.id)"));
             })
             ->where('data_perjadinkegiatans.is_acceptKeu', $status)
             ->where('data_perjadinkegiatans.versi_id', session('versi'))
             ->select('data_perjadinkegiatans.*', 'laporan_perjadinkegiatans.file as sppd_file',
                    'laporan_perjadinkegiatans.tempatTujuan_penandatangan0',
                    'laporan_perjadinkegiatans.nama_penandatangan','laporan_perjadinkegiatans.jabatan_penandatangan','laporan_perjadinkegiatans.nip_penandatangan','laporan_perjadinkegiatans.tempatTiba_penandatangan','laporan_perjadinkegiatans.tempatTujuan_penandatangan','laporan_perjadinkegiatans.tanggal_penandatangan','laporan_perjadinkegiatans.tanggalTujuan_penandatangan',
                    'laporan_perjadinkegiatans.nama_penandatangan2','laporan_perjadinkegiatans.jabatan_penandatangan2','laporan_perjadinkegiatans.nip_penandatangan2','laporan_perjadinkegiatans.tempatTiba_penandatangan2','laporan_perjadinkegiatans.tempatTujuan_penandatangan2','laporan_perjadinkegiatans.tanggal_penandatangan2', 'laporan_perjadinkegiatans.tanggalTujuan_penandatangan2',
                    'laporan_perjadinkegiatans.nama_penandatangan3','laporan_perjadinkegiatans.jabatan_penandatangan3','laporan_perjadinkegiatans.nip_penandatangan3','laporan_perjadinkegiatans.tempatTiba_penandatangan3','laporan_perjadinkegiatans.tempatTujuan_penandatangan3','laporan_perjadinkegiatans.tanggal_penandatangan3', 'laporan_perjadinkegiatans.tanggalTujuan_penandatangan3',
                    'laporan_perjadinkegiatans.nama_penandatangan4','laporan_perjadinkegiatans.jabatan_penandatangan4','laporan_perjadinkegiatans.nip_penandatangan4','laporan_perjadinkegiatans.tempatTiba_penandatangan4','laporan_perjadinkegiatans.tempatTujuan_penandatangan4','laporan_perjadinkegiatans.tanggal_penandatangan4', 'laporan_perjadinkegiatans.tanggalTujuan_penandatangan4',
                )
             ->get();

        return view('admin.kegiatan.keuangan.index', [
            'title' => 'Keuangan Kegiatan',
            'kegiatans' => $kegiatans
        ]);
    }

    public function detail_mobilitas(Request $request, $id)
{
    $infoKegiatan = DB::table('mobilitas_perjadinkegiatans')
                ->select('data_perjadinkegiatan')
                ->where('mobilitas_perjadinkegiatans.id', $id)
                ->first();
    $infoKegiatan = $infoKegiatan->data_perjadinkegiatan;
    $info = DB::table('mobilitas_perjadinkegiatans')
        ->join('data_perjadinkegiatans', 'mobilitas_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
        ->select(
            'mobilitas_perjadinkegiatans.data_perjadinkegiatan',
            'mobilitas_perjadinkegiatans.tujuan_penggunaan',
            'data_perjadinkegiatans.nama_kegiatan',
            'data_perjadinkegiatans.tgl_mulai',
            'data_perjadinkegiatans.tgl_selesai',
            'data_perjadinkegiatans.alamat',
            'data_perjadinkegiatans.provinsi',
            'data_perjadinkegiatans.is_acceptBMN',
            'data_perjadinkegiatans.kab_kota',
            'data_perjadinkegiatans.jumlah_kamar',
            'data_perjadinkegiatans.jumlah_peserta',
            'data_perjadinkegiatans.tambah_penginapan'
        )
        ->where('mobilitas_perjadinkegiatans.id', $id)
        ->first();

    // Parse dates for use in data fetching
    $tanggalMulai = Carbon::parse($info->tgl_mulai);
    $tanggalSelesai = Carbon::parse($info->tgl_selesai);

    $pesertaPegawais = DB::table('perangkat_acaras')
    ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
    ->select(
        'pegawais.nama_lengkap',
        'pegawais.pangkat',
        'pegawais.golongan',
        'perangkat_acaras.sebagai as sebagai',
        'perangkat_acaras.posisi as posisi',
        'perangkat_acaras.status as status_pegawai' // Ambil status dari perangkat_acaras
    )
    ->where('perangkat_acaras.data_perjadin_kegiatan', $infoKegiatan)
    ->get();

// Fetch Non-Pegawai Participants
$pesertaNonPegawais = DB::table('perangkat_acaras')
    ->join('non_pegawais', 'perangkat_acaras.non_pegawai_id', '=', 'non_pegawais.id')
    ->select(
        'non_pegawais.nama_lengkap',
        'non_pegawais.pangkat',
        'non_pegawais.golongan',
        'perangkat_acaras.posisi as posisi',
        'perangkat_acaras.sebagai as sebagai',
        'perangkat_acaras.status as status_pegawai' // Ambil status dari perangkat_acaras
    )
    ->where('perangkat_acaras.data_perjadin_kegiatan', $infoKegiatan)
    ->get();

$mobilitass = DB::table('peminjaman_kendaraan_dinas')
    ->leftJoin('pegawais','peminjaman_kendaraan_dinas.pegawai_id','=','pegawais.id')
    ->leftJoin('kendaraans','peminjaman_kendaraan_dinas.kendaraan','=','kendaraans.id')
    ->leftJoin('mobilitas_perjadinkegiatans','peminjaman_kendaraan_dinas.mobilitas_perjadinkegiatan','=','mobilitas_perjadinkegiatans.id')
    ->leftJoin('data_perjadinkegiatans','mobilitas_perjadinkegiatans.data_perjadinkegiatan','=','data_perjadinkegiatans.id')
    ->select('peminjaman_kendaraan_dinas.id AS id_peminjaman','data_perjadinkegiatans.*','peminjaman_kendaraan_dinas.*','pegawais.id AS id_pegawai','pegawais.nama_lengkap','kendaraans.merek','kendaraans.no_polisi')
    ->where('peminjaman_kendaraan_dinas.mobilitas_perjadinkegiatan',$id)
    ->get();

    return view('admin.kegiatan.mobilitas.detail', [
        'title' => 'Detail Mobilitas Kegiatan',
        'infoKegiatan' => $info,
        'kegiatan' => $infoKegiatan,
        'mobilitass' => $mobilitass,
        'pesertaNonPegawais' => $pesertaNonPegawais,
        'mobilitasID' => $id,
        'pesertaPegawais' => $pesertaPegawais,
        'dokumens' => Laporan_perjadinkegiatan::where('data_perjadin_kegiatan', $infoKegiatan)
                            ->where('nama_dokumen', 'NOT LIKE', "lap-$infoKegiatan-$infoKegiatan-$infoKegiatan%")
                            ->whereNotNull('laporan_perjadinkegiatans.file')
                            ->get(),
    ]);
}

public function cekMobilitasAPI(Request $request)
{
    // Parsing tanggal dari request menggunakan Carbon
    $tanggalAwal = Carbon::parse($request->input('tanggal_awal'));
    $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir'));
    $kegiatanID = $request->input('kegiatanID');

    // Query untuk mendapatkan kendaraan yang tersedia
    $kendaraans = DB::table('kendaraans')
        ->select('kendaraans.*')
        ->where('kendaraans.status', '=', 'baik')
        ->where('kendaraans.tipe', '=', 'Roda Empat')
        ->whereNotExists(function ($query) use ($tanggalAwal, $tanggalAkhir) {
            $query->select(DB::raw(1))
                ->from('peminjaman_kendaraan_dinas')
                ->whereRaw('kendaraans.id = peminjaman_kendaraan_dinas.kendaraan')
                ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                    $subquery
                        ->where('peminjaman_kendaraan_dinas.tgl_keberangkatan', '<=', $tanggalAkhir)
                        ->where('peminjaman_kendaraan_dinas.tgl_selesai', '>=', $tanggalAwal)
                        ->where('peminjaman_kendaraan_dinas.status', '!=', 'ditolak');
                });
        })
        ->distinct()
        ->get();

    // Query untuk mendapatkan pengemudi yang tidak sedang dalam perjalanan dinas
    $pengemudis = DB::table('pegawais')
        ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
        ->select('pegawais.id', 'pegawais.nama_lengkap', 'jabatans.nama_jabatan')
        ->where('jabatans.nama_jabatan', 'Pengemudi')
        ->whereNotExists(function ($query) use ($tanggalAwal, $tanggalAkhir) {
            $query->select(DB::raw(1))
                ->from('peminjaman_kendaraan_dinas')
                ->whereRaw('pegawais.id = peminjaman_kendaraan_dinas.pegawai_id')
                ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                    $subquery
                        ->where('peminjaman_kendaraan_dinas.tgl_keberangkatan', '<=', $tanggalAkhir)
                        ->where('peminjaman_kendaraan_dinas.tgl_selesai', '>=', $tanggalAwal)
                        ->where('peminjaman_kendaraan_dinas.status', '!=', 'ditolak');
                });
        })
        ->distinct()
        ->get();

    $pegawaiPengemudis = [];
    // Jalankan query untuk pegawai pengemudi hanya jika perjadinID ada
    if (!empty($kegiatanID)) {
        $pegawaiPengemudis = DB::table('pegawais')
            ->join('perangkat_acaras', 'pegawais.id', '=', 'perangkat_acaras.pegawai_id')
            ->select('pegawais.id', 'pegawais.nama_lengkap', 'jabatans.nama_jabatan')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->where('perangkat_acaras.data_perjadin_kegiatan', $kegiatanID)
            // ->whereNotExists(function ($query) use ($tanggalAwal, $tanggalAkhir) {
            //     $query->select(DB::raw(1))
            //         ->from('peminjaman_kendaraan_dinas')
            //         ->whereRaw('pegawais.id = peminjaman_kendaraan_dinas.pegawai_id')
            //         ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
            //             $subquery
            //                 ->where('peminjaman_kendaraan_dinas.tgl_keberangkatan', '<=', $tanggalAkhir)
            //                 ->where('peminjaman_kendaraan_dinas.tgl_selesai', '>=', $tanggalAwal)
            //                 ->where('peminjaman_kendaraan_dinas.status', '!=', 'ditolak');
            //         });
            // })
            ->distinct()
            ->get();
    }

    // Return data dalam format JSON
    return response()->json([
        'kendaraans' => $kendaraans,
        'pengemudis' => $pengemudis,
        'pegawaiPengemudis' => $pegawaiPengemudis, // Sertakan pegawaiPengemudi dalam respons
    ]);
}


    public function detail_sapras(Request $request, $id)
    {
        $info = DB::table('peminjaman_sarpras')
                        ->join('data_perjadinkegiatans', 'peminjaman_sarpras.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
                        ->select('data_perjadinkegiatans.nama_kegiatan','data_perjadinkegiatans.tgl_mulai', 'data_perjadinkegiatans.alamat', 'peminjaman_sarpras.data_perjadinkegiatan')
                        ->where('data_perjadinkegiatans.id', $id)
                        ->get();
        $peminjaman = DB::table('peminjaman_sarpras')
                        ->join('pegawais', 'peminjaman_sarpras.pegawai_id', '=', 'pegawais.id')
                        ->join('assets', 'peminjaman_sarpras.asset', '=', 'assets.id')
                        ->join('data_perjadinkegiatans', 'peminjaman_sarpras.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
                        ->select('peminjaman_sarpras.id as idPeminjaman', 'pegawais.nama_lengkap','assets.id as idBarang', 'assets.nama_barang', 'peminjaman_sarpras.jumlah_asset', 'peminjaman_sarpras.keterangan', 'peminjaman_sarpras.status', 'data_perjadinkegiatans.id')
                        ->where('data_perjadinkegiatans.id', $id)
                        ->where('peminjaman_sarpras.versi_id', session('versi'))
                        ->get();
        return view('admin.kegiatan.assets.detail', [
            'title' => 'Detail Sapras Kegiatan',
            'info' => $info,
            'peminjamans' => $peminjaman,
        ]);
    }

    public function detail_keuangan(Request $request, $id)
    {

            // Nonaktifkan ONLY_FULL_GROUP_BY
            DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");
        $pegawais = DB::table('perangkat_acaras')
                    ->join('fasilitas', 'perangkat_acaras.fasilitas_id', '=', 'fasilitas.id')
                    ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
                    ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
                    ->select(
                        'pegawais.id as idPegawai',
                        'pegawais.nama_lengkap',
                        'pegawais.pangkat',
                        'pegawais.golongan',
                        'perangkat_acaras.posisi',
                        'perangkat_acaras.detail_satuan',
                        'perangkat_acaras.satuan',
                        'perangkat_acaras.sebagai',
                        'perangkat_acaras.status',
                        'perangkat_acaras.fasilitas_id',
                        'fasilitas.nama_fasilitas',
                        'keuangan_perjadinkegiatans.kode',
                        DB::raw('MAX(perangkat_acaras.id) as idPerangkatAcara'),
                        DB::raw('MAX(keuangan_perjadinkegiatans.id) as idKeuangan'),
                        DB::raw('MAX(keuangan_perjadinkegiatans.data_perjadinkegiatan) as idKegiatan')
                    )
                    ->GroupBy('keuangan_perjadinkegiatans.perangkat_acara')
                    ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
                    ->get();


                    $nonpegawais = DB::table('perangkat_acaras')
                    ->join('fasilitas', 'perangkat_acaras.fasilitas_id', '=', 'fasilitas.id')
                        ->join('non_pegawais', 'perangkat_acaras.non_pegawai_id', '=', 'non_pegawais.id')
                        ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
                        ->select('non_pegawais.id as idNonPegawai', 'non_pegawais.nama_lengkap','non_pegawais.pangkat', 'non_pegawais.golongan','perangkat_acaras.posisi', 'perangkat_acaras.detail_satuan','perangkat_acaras.sebagai', 'perangkat_acaras.satuan', 'perangkat_acaras.status', 'fasilitas.nama_fasilitas', 'perangkat_acaras.fasilitas_id', 'perangkat_acaras.id as idPerangkatAcara', 'keuangan_perjadinkegiatans.id as idKeuangan', 'keuangan_perjadinkegiatans.data_perjadinkegiatan as idKegiatan')
                        ->GroupBy('keuangan_perjadinkegiatans.perangkat_acara')
                            ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
                            ->get();
                            // Kembalikan ONLY_FULL_GROUP_BY ke default
                            DB::statement("SET SESSION sql_mode=CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY')");

        $operasionals = DB::table('keuangan_perjadinkegiatans')
                        ->join('operasionals', 'keuangan_perjadinkegiatans.operasional', '=', 'operasionals.id')
                        ->select('operasionals.id', 'operasionals.status', 'operasionals.nama', 'operasionals.jumlah_frekuensi', 'operasionals.satuan', 'operasionals.detail_satuan', 'keuangan_perjadinkegiatans.operasional', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
                        ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
                        ->get();
        $keuanganOperasional = DB::table('keuangan_perjadinkegiatans')
                        ->join('operasionals', 'keuangan_perjadinkegiatans.operasional', '=', 'operasionals.id')
                        ->select('keuangan_perjadinkegiatans.akun_x_rkakl', 'keuangan_perjadinkegiatans.harga', 'keuangan_perjadinkegiatans.persen_pajak', 'keuangan_perjadinkegiatans.jumlah_harga', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', 'keuangan_perjadinkegiatans.operasional', 'operasionals.nama', 'operasionals.jumlah_frekuensi')
                        ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
                        ->get();
        $keuangaKebutuhans = DB::table('keuangan_perjadinkegiatans')
                        ->join('kebutuhans', 'keuangan_perjadinkegiatans.kebutuhan_id', '=', 'kebutuhans.id')
                        ->select('keuangan_perjadinkegiatans.akun_x_rkakl', 'keuangan_perjadinkegiatans.harga', 'keuangan_perjadinkegiatans.persen_pajak', 'keuangan_perjadinkegiatans.jumlah_harga', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', 'keuangan_perjadinkegiatans.kebutuhan_id', 'kebutuhans.nama', 'kebutuhans.jumlah_frekuensi')
                        ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
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
                            'keuangan_perjadinkegiatans.harga',
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
        $nama_dokumen = 'lap-'.$id.'-'.$id.'-'.$id;
        $dokumens =  Laporan_perjadinkegiatan::select(
                                'laporan_perjadinkegiatans.*'
                        )
                        ->where('laporan_perjadinkegiatans.data_perjadin_kegiatan', $id)
                        ->where('laporan_perjadinkegiatans.nama_dokumen','!=', $nama_dokumen)
                        ->whereNotNull('laporan_perjadinkegiatans.file')
                        ->get();
        return view('admin.kegiatan.keuangan.detail', [
            'title' => 'Detail Keuangan',
            'info' => Data_perjadinkegiatan::find($id),
            'dokumens' => $dokumens,
            "perangkats" => Fasilitas::where('data_perjadinkegiatan_id', $id)->get(),
            'pegawais' => $pegawais,
            'nonpegawais' => $nonpegawais,
            "operasionals" => $operasionals,
            "keuangankebutuhans" => $keuangaKebutuhans,
            "keuanganoperasionals" => $keuanganOperasional,
            "kebutuhans" => $kebutuhans,
            'ref_fasilitas' => DB::table('ref_fasilitas')->where('status','aktif')->where('terikat_pelaksana',0)->get(),
            'ref_fasilitas_pelaksana' => DB::table('ref_fasilitas')->where('status','aktif')->where('terikat_pelaksana',1)->get(),
        ]);
    }

    public function detail_bendahara(Request $request, $id)
    {
        $kegiatanData =  DB::table('data_perjadinkegiatans')
            ->where('id', $id)
            ->first();

        $isPenugasan = $kegiatanData->jenis_program == 'Penugasan';

        // if ($isPenugasan){
            // $pesertaPegawais = DB::table('perangkat_acaras')
            // ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
            // ->select('pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'perangkat_acaras.sebagai as status_pegawai', 'pegawais.NIP_NIK', 'pegawais.id', 'pegawais.nama_lengkap', 'perangkat_acaras.id as idPeserta')
            // ->where('perangkat_acaras.data_perjadin_kegiatan', $id)
            // ->get();

            // $fasilitas = DB::table('perangkat_acaras')
            //     ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
            //     ->join('keuangan_perjadinkegiatans', 'perangkat_acaras.id', '=', 'keuangan_perjadinkegiatans.perangkat_acara')
            //     ->select('pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'perangkat_acaras.sebagai as status_pegawai', 'pegawais.NIP_NIK', 'pegawais.id', 'pegawais.nama_lengkap', 'perangkat_acaras.id as idPeserta', 'keuangan_perjadinkegiatans.id as idKeuangan', 'keuangan_perjadinkegiatans.akun_x_rkakl', 'keuangan_perjadinkegiatans.ref_sbm', 'keuangan_perjadinkegiatans.uang_harian', 'keuangan_perjadinkegiatans.uang_harian_fullday', 'keuangan_perjadinkegiatans.uang_harian_fullboard', 'keuangan_perjadinkegiatans.uang_representasi', 'keuangan_perjadinkegiatans.persen_pajak', 'keuangan_perjadinkegiatans.jumlah_harga', 'keuangan_perjadinkegiatans.status', 'keuangan_perjadinkegiatans.pph22', 'keuangan_perjadinkegiatans.pph23', 'keuangan_perjadinkegiatans.tgl_bayar', 'keuangan_perjadinkegiatans.ppn')
            //     ->where('perangkat_acaras.data_perjadin_kegiatan', $id)
            //     ->whereNull('keuangan_perjadinkegiatans.kebutuhan_id')
            //     ->get();

            // $pesertaNonPegawais = DB::table('perangkat_acaras')
            //     ->join('non_pegawais', 'perangkat_acaras.non_pegawai_id', '=', 'non_pegawais.id')
            //     ->join('keuangan_perjadinkegiatans', 'perangkat_acaras.id', '=', 'keuangan_perjadinkegiatans.perangkat_acara')
            //     ->select('keuangan_perjadinkegiatans.id  as idKeuangan', 'non_pegawais.id', 'non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'perangkat_acaras.sebagai as status_pegawai', 'perangkat_acaras.id as idData', 'keuangan_perjadinkegiatans.akun_x_rkakl', 'keuangan_perjadinkegiatans.ref_sbm', 'keuangan_perjadinkegiatans.uang_harian', 'keuangan_perjadinkegiatans.uang_harian_fullday','keuangan_perjadinkegiatans.uang_harian_fullboard','keuangan_perjadinkegiatans.uang_representasi','keuangan_perjadinkegiatans.persen_pajak', 'keuangan_perjadinkegiatans.jumlah_harga', 'keuangan_perjadinkegiatans.status', 'keuangan_perjadinkegiatans.pph22', 'keuangan_perjadinkegiatans.pph23', 'keuangan_perjadinkegiatans.tgl_bayar', 'keuangan_perjadinkegiatans.ppn')
            //     ->where('perangkat_acaras.data_perjadin_kegiatan', $id)
            //     ->get();

            // $kebutuhans = DB::table('kebutuhans')
            //     ->join('keuangan_perjadinkegiatans', 'kebutuhans.id', '=', 'keuangan_perjadinkegiatans.kebutuhan_id')
            //     ->join('perangkat_acaras', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
            //     ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
            //     ->select('kebutuhans.id as idKebutuhan', 'pegawais.nama_lengkap', 'kebutuhans.nama', 'kebutuhans.jumlah_frekuensi', 'kebutuhans.satuan', 'kebutuhans.tipe_pendanaan', 'kebutuhans.ket', 'kebutuhans.status', 'keuangan_perjadinkegiatans.kebutuhan_id as idKeuangan', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', 'keuangan_perjadinkegiatans.uang_harian', 'keuangan_perjadinkegiatans.persen_pajak', 'keuangan_perjadinkegiatans.jumlah_harga', 'keuangan_perjadinkegiatans.akun_x_rkakl', 'keuangan_perjadinkegiatans.ref_sbm', 'keuangan_perjadinkegiatans.status as statusPembayaran', 'keuangan_perjadinkegiatans.pph22', 'keuangan_perjadinkegiatans.pph23', 'keuangan_perjadinkegiatans.tgl_bayar', 'keuangan_perjadinkegiatans.ppn')
            //     ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
            //     ->get();

            // $akuns = DB::table('akun_x_rkakls')
            //     ->join('akuns', 'akun_x_rkakls.akun_id', '=', 'akuns.id')
            //     ->join('ref_rkakl_sub_komponens', 'akun_x_rkakls.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
            //     ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
            //     ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
            //     ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
            //     ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
            //     ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
            //     ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
            //     ->select('akun_x_rkakls.id as idAkun', 'ref_rkakl_sub_komponens.nama_sub_kegiatan', 'akuns.uraian', 'ref_rkakl_satkers.kode_satker', 'ref_rkakl_programs.kode_program', 'ref_rkakl_kegiatans.kode_kegiatan', 'ref_rkakl_outputs.kode_output', 'ref_rkakl_suboutputs.kode_sub_output', 'ref_rkakl_komponens.kode_komponen', 'ref_rkakl_sub_komponens.kode_sub_kegiatan', 'ref_rkakl_sub_komponens.nama_sub_kegiatan', 'akuns.kode_akun', 'akuns.uraian')
            //     ->get();

            // return view('admin.kegiatan.bendahara.detail-penugasan', [
            //     'title' => 'Detail bendahara Perjalanan Dinas',
            //     'kegiatan' => Data_perjadinkegiatan::find($id),
            //     'pesertaPegawais' => $pesertaPegawais,
            //     'pesertaNonPegawais' => $pesertaNonPegawais,
            //     'kebutuhans' => $kebutuhans,
            //     'fasilitas' => $fasilitas,
            //     "sbms" => Ref_sbm::all(),
            //     'akuns' => $akuns,
            //     'dokumens' => Laporan_perjadinkegiatan::where('data_perjadin_kegiatan', $id)->get(),
            // ]);
        // } else {
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

            if (($kegiatanData->is_acceptBend == 'approval-1') || ($kegiatanData->is_acceptBend == 'approval-2' && $kegiatanData->is_acceptKeu == 'selesai')) {
                return view('admin.kegiatan.bendahara.detail', [
                    'title' => 'Detail Keuangan',
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
                                ->whereNotNull('laporan_perjadinkegiatans.file')
                                ->get(),
                    'ref_fasilitas' => DB::table('ref_fasilitas')->where('status','aktif')->where('terikat_pelaksana',0)->get(),
                    'ref_fasilitas_pelaksana' => DB::table('ref_fasilitas')->where('status','aktif')->where('terikat_pelaksana',1)->get(),

                ]);
            } else {
                return view('admin.kegiatan.bendahara.detail_only', [
                    'title' => 'Detail Keuangan',
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
                                ->whereNotNull('laporan_perjadinkegiatans.file')
                                ->get(),

                ]);
            }
        // }
    }

    public function CetakRPD($id)
    {
        if (!$id) {
            // Handle jika data tidak ditemukan
            dd('Data tidak ditemukan.');
        }

        $pesertaPegawais = DB::table('data_perjadinkegiatans as i')
        ->join('perangkat_acaras as dp', 'i.id', '=', 'dp.data_perjadin_kegiatan')
        ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
        ->join('keuangan_perjadinkegiatans as k', function ($join) {
            $join->on('k.data_perjadinkegiatan', '=', 'i.id')
                ->on('k.perangkat_acara', '=', 'dp.id');
        })
        ->join('akun_x_rkakls as a', 'k.akun_x_rkakl', '=', 'a.id') // Join ke akun_x_rkakls
        ->join('akuns', 'a.akun_id', '=', 'akuns.id')
        ->join('ref_rkakl_sub_komponens', 'a.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
        ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
        ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
        ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
        ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
        ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
        ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
        ->join('surtug_perjadinkegiatans as s', 's.data_perjadin_kegiatan', '=', 'i.id')
        ->where('i.id', $id)
        ->whereNull('k.kebutuhan_id')
        ->where('dp.posisi', 'Panitia')
        ->where('k.kode', 'harian')
        ->select(
            'i.id as id_data_perjadinkegiatan',
            'p.id',
            'p.nama_lengkap',
            'p.NIP_NIK',
            's.nomor_surat',
            's.tgl_surat_dibuat',
            's.paragraf_1',
            'i.nama_kegiatan',
            'i.tgl_mulai',
            'i.kab_kota',
            'i.provinsi',
            'i.tgl_selesai',
            'k.uang_harian',
            'k.uang_harian_fullday',
            'k.uang_harian_fullboard',
            'k.uang_representasi',
            'k.nominal_perjadin',
            'a.id as idAkun',
            'ref_rkakl_sub_komponens.nama_sub_kegiatan',
            'akuns.uraian',
            'ref_rkakl_satkers.kode_satker',
            'ref_rkakl_programs.kode_program',
            'ref_rkakl_kegiatans.kode_kegiatan',
            'ref_rkakl_outputs.kode_output',
            'ref_rkakl_suboutputs.kode_sub_output',
            'ref_rkakl_komponens.kode_komponen',
            'ref_rkakl_sub_komponens.kode_sub_kegiatan',
            'akuns.kode_akun'
        )
        ->groupBy(
            'i.id',
            'p.id',
            'p.nama_lengkap',
            'p.NIP_NIK',
            's.nomor_surat',
            's.tgl_surat_dibuat',
            's.paragraf_1',
            'i.nama_kegiatan',
            'i.tgl_mulai',
            'i.kab_kota',
            'i.provinsi',
            'i.tgl_selesai',
            'k.uang_harian',
            'k.uang_harian_fullday',
            'k.uang_harian_fullboard',
            'k.uang_representasi',
            'k.nominal_perjadin',
            'a.id',
            'ref_rkakl_sub_komponens.nama_sub_kegiatan',
            'akuns.uraian',
            'ref_rkakl_satkers.kode_satker',
            'ref_rkakl_programs.kode_program',
            'ref_rkakl_kegiatans.kode_kegiatan',
            'ref_rkakl_outputs.kode_output',
            'ref_rkakl_suboutputs.kode_sub_output',
            'ref_rkakl_komponens.kode_komponen',
            'ref_rkakl_sub_komponens.kode_sub_kegiatan',
            'akuns.kode_akun'
        )
        ->distinct()
        ->get();


        $fasilitasPegawais = DB::table('data_perjadinkegiatans as i')
            ->join('perangkat_acaras as dp', 'i.id', '=', 'dp.data_perjadin_kegiatan')
            ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
            ->join('keuangan_perjadinkegiatans as k', function ($join) {
                $join->on('k.data_perjadinkegiatan', '=', 'i.id')
                     ->on('k.perangkat_acara', '=', 'dp.id');
            })
            ->join('surtug_perjadinkegiatans as s', 's.data_perjadin_kegiatan', '=', 'i.id')
            ->leftJoin('kebutuhans as kb', 'k.kebutuhan_id', '=', 'kb.id')
            ->where('i.id', $id)
            ->whereNotNull('k.kebutuhan_id')
            ->whereIn('dp.posisi', ['Panitia', 'Supir'])
            ->select(
                'i.id as id_data_perjadinkegiatan',
                'p.id as pegawai_id',
                'p.nama_lengkap',
                'p.NIP_NIK',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kab_kota',
                'i.provinsi',
                'i.tgl_selesai',
                'k.jumlah_harga',
                'k.kebutuhan_id',
                'kb.nama as nama_kebutuhan',
                'kb.jumlah_frekuensi',
                'kb.satuan',
                'kb.ket'
            )
            ->groupBy(
                'i.id',
                'p.id',
                'p.nama_lengkap',
                'p.NIP_NIK',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kab_kota',
                'i.provinsi',
                'i.tgl_selesai',
                'k.jumlah_harga',
                'k.kebutuhan_id',
                'kb.nama',
                'kb.jumlah_frekuensi',
                'kb.satuan',
                'kb.ket'
            )
            ->get();

        $fasilitasNonPegawais = DB::table('data_perjadinkegiatans as i')
        ->join('perangkat_acaras as dp', 'i.id', '=', 'dp.data_perjadin_kegiatan')
        ->join('non_pegawais as p', 'dp.non_pegawai_id', '=', 'p.id')
        ->join('keuangan_perjadinkegiatans as k', function ($join) {
            $join->on('k.data_perjadinkegiatan', '=', 'i.id')
                 ->on('k.perangkat_acara', '=', 'dp.id');
        })
        ->join('surtug_perjadinkegiatans as s', 's.data_perjadin_kegiatan', '=', 'i.id')
        ->leftJoin('kebutuhans as kb', 'k.kebutuhan_id', '=', 'kb.id')
        ->where('i.id', $id)
        ->whereNotNull('k.kebutuhan_id')
        ->select(
            'i.id as id_data_perjadinkegiatan',
            'p.id as pegawai_id',
            'p.nama_lengkap',
            'p.NIP_NIK',
            's.nomor_surat',
            's.tgl_surat_dibuat',
            's.paragraf_1',
            'i.nama_kegiatan',
            'i.tgl_mulai',
            'i.kab_kota',
            'i.provinsi',
            'i.tgl_selesai',
            'k.jumlah_harga',
            'k.kebutuhan_id',
            'kb.nama as nama_kebutuhan',
            'kb.jumlah_frekuensi',
            'kb.satuan',
            'kb.ket'
        )
        ->groupBy(
            'i.id',
            'p.id',
            'p.nama_lengkap',
            'p.NIP_NIK',
            's.nomor_surat',
            's.tgl_surat_dibuat',
            's.paragraf_1',
            'i.nama_kegiatan',
            'i.tgl_mulai',
            'i.kab_kota',
            'i.provinsi',
            'i.tgl_selesai',
            'k.jumlah_harga',
            'k.kebutuhan_id',
            'kb.nama',
            'kb.jumlah_frekuensi',
            'kb.satuan',
            'kb.ket'
        )
        ->get();


        // dd($fasilitasNonPegawais);
        $pesertaNonPegawais = DB::table('data_perjadinkegiatans as i')
            ->join('perangkat_acaras as dp', 'i.id', '=', 'dp.data_perjadin_kegiatan')
            ->join('non_pegawais as p', 'dp.non_pegawai_id', '=', 'p.id')
            ->join('keuangan_perjadinkegiatans as k', function ($join) {
                $join->on('k.data_perjadinkegiatan', '=', 'i.id')
                    ->on('k.perangkat_acara', '=', 'dp.id');
            })
            ->join('akun_x_rkakls as a', 'k.akun_x_rkakl', '=', 'a.id') // Join ke akun_x_rkakls
            ->join('akuns', 'a.akun_id', '=', 'akuns.id')
            ->join('ref_rkakl_sub_komponens', 'a.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
            ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
            ->join('surtug_perjadinkegiatans as s', 's.data_perjadin_kegiatan', '=', 'i.id')
            ->where('i.id', $id)
            ->whereNull('k.kebutuhan_id')
            ->where('dp.posisi', 'Panitia')
            ->where('k.kode', 'harian')
            ->select(
                'i.id as id_data_perjadinkegiatan',
                'p.id',
                'p.nama_lengkap',
                'p.NIP_NIK',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kab_kota',
                'i.provinsi',
                'i.tgl_selesai',
                'k.uang_harian',
                'k.uang_harian_fullday',
                'k.uang_harian_fullboard',
                'k.uang_representasi',
                'k.nominal_perjadin',
                'a.id as idAkun',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'ref_rkakl_satkers.kode_satker',
                'ref_rkakl_programs.kode_program',
                'ref_rkakl_kegiatans.kode_kegiatan',
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'akuns.kode_akun'
            )
            ->groupBy(
                'i.id',
                'p.id',
                'p.nama_lengkap',
                'p.NIP_NIK',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kab_kota',
                'i.provinsi',
                'i.tgl_selesai',
                'k.uang_harian',
                'k.uang_harian_fullday',
                'k.uang_harian_fullboard',
                'k.uang_representasi',
                'k.nominal_perjadin',
                'a.id',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'ref_rkakl_satkers.kode_satker',
                'ref_rkakl_programs.kode_program',
                'ref_rkakl_kegiatans.kode_kegiatan',
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'akuns.kode_akun'
            )
            ->distinct()
            ->get();


        $pengemudis = DB::table('data_perjadinkegiatans as i')
            ->join('perangkat_acaras as dp', 'i.id', '=', 'dp.data_perjadin_kegiatan')
            ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
            ->join('keuangan_perjadinkegiatans as k', function ($join) {
                $join->on('k.data_perjadinkegiatan', '=', 'i.id')
                    ->on('k.perangkat_acara', '=', 'dp.id');
            })
            ->join('akun_x_rkakls as a', 'k.akun_x_rkakl', '=', 'a.id') // Join ke akun_x_rkakls
            ->join('akuns', 'a.akun_id', '=', 'akuns.id')
            ->join('ref_rkakl_sub_komponens', 'a.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
            ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
            ->join('surtug_perjadinkegiatans as s', 's.data_perjadin_kegiatan', '=', 'i.id')
            ->where('i.id', $id)
            ->whereNull('k.kebutuhan_id')
            ->where('dp.posisi', 'Supir')
            ->select(
                'i.id as id_data_perjadinkegiatan',
                'p.nama_lengkap',
                'p.id',
                'p.NIP_NIK',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kab_kota',
                'i.provinsi',
                'i.tgl_selesai',
                'k.uang_harian',
                'k.uang_harian_fullday',
                'k.uang_harian_fullboard',
                'k.uang_representasi',
                'k.nominal_perjadin',
                'k.jumlah_harga',
                'a.id as idAkun',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'ref_rkakl_satkers.kode_satker',
                'ref_rkakl_programs.kode_program',
                'ref_rkakl_kegiatans.kode_kegiatan',
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'akuns.kode_akun'
            )
            ->groupBy(
                'i.id',
                'p.id',
                'p.nama_lengkap',
                'p.NIP_NIK',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kab_kota',
                'i.provinsi',
                'i.tgl_selesai',
                'k.uang_harian',
                'k.uang_harian_fullday',
                'k.uang_harian_fullboard',
                'k.uang_representasi',
                'k.nominal_perjadin',
                'k.jumlah_harga',
                'a.id',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'ref_rkakl_satkers.kode_satker',
                'ref_rkakl_programs.kode_program',
                'ref_rkakl_kegiatans.kode_kegiatan',
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'akuns.kode_akun'
            )
            ->distinct()
            ->get();

        $fasilitasPengemudis = DB::table('data_perjadinkegiatans as i')
            ->join('perangkat_acaras as dp', 'i.id', '=', 'dp.data_perjadin_kegiatan')
            ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
            ->join('keuangan_perjadinkegiatans as k', function ($join) {
                $join->on('k.data_perjadinkegiatan', '=', 'i.id')
                    ->on('k.perangkat_acara', '=', 'dp.id');
            })
            ->join('akun_x_rkakls as a', 'k.akun_x_rkakl', '=', 'a.id') // Join ke akun_x_rkakls
            ->join('akuns', 'a.akun_id', '=', 'akuns.id')
            ->join('ref_rkakl_sub_komponens', 'a.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
            ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
            ->join('surtug_perjadinkegiatans as s', 's.data_perjadin_kegiatan', '=', 'i.id')
            ->where('i.id', $id)
            ->whereNull('k.kebutuhan_id')
            ->where('dp.posisi', 'Supir')
            ->select(
                'i.id as id_data_perjadinkegiatan',
                'p.nama_lengkap',
                'p.id',
                'p.NIP_NIK',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kab_kota',
                'i.provinsi',
                'i.tgl_selesai',
                'k.jumlah_harga',
                'a.id as idAkun',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'ref_rkakl_satkers.kode_satker',
                'ref_rkakl_programs.kode_program',
                'ref_rkakl_kegiatans.kode_kegiatan',
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'akuns.kode_akun'
            )
            ->groupBy(
                'i.id',
                'p.id',
                'p.nama_lengkap',
                'p.NIP_NIK',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kab_kota',
                'i.provinsi',
                'i.tgl_selesai',
                'k.jumlah_harga',
                'a.id',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'ref_rkakl_satkers.kode_satker',
                'ref_rkakl_programs.kode_program',
                'ref_rkakl_kegiatans.kode_kegiatan',
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'akuns.kode_akun'
            )
            ->distinct()
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

        $pegawaiMaster = DB::table('ref_penandatangans')
            ->join('pegawais', 'pegawais.id', '=', 'ref_penandatangans.pegawai_id')
            ->select('pegawais.*')
            ->where('ref_penandatangans.posisi_penandatangan', 'Pejabat Pembuat Komitmen')
            ->where('ref_penandatangans.status_penandatangan', 'aktif')
            ->first();

        $pegawaiBendahara = DB::table('ref_penandatangans')
            ->join('pegawais', 'pegawais.id', '=', 'ref_penandatangans.pegawai_id')
            ->select('pegawais.*')
            ->where('ref_penandatangans.posisi_penandatangan', 'Bendahara')
            ->where('ref_penandatangans.status_penandatangan', 'aktif')
            ->first();


        $datas = [
            'pesertaPegawais' => $pesertaPegawais,
            'fasilitasPegawais' => $fasilitasPegawais,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'fasilitasNonPegawais' => $fasilitasNonPegawais,
            'pengemudis' => $pengemudis,
            'fasilitasPengemudis' => $fasilitasPengemudis,
            'pegawaiMaster' => $pegawaiMaster,
            'pegawaiBendahara' => $pegawaiBendahara,
            'kegitan' => Data_perjadinkegiatan::find($id),
            'akuns' => $akuns,
        ];

        // dd($datas);

        // dd($datas);
        // Lakukan proses pembuatan file PDF
        $pdf = PDF::loadView('admin.kegiatan.bendahara.rpd', compact('datas'));
        $pdf->setPaper('A4', 'portrait');

        // Simpan file PDF ke penyimpanan
        $fileName = "dokumen-kegiatans/rpd_kegiatan_$id.pdf";
        Storage::disk('public')->put($fileName, $pdf->output());

        // Periksa apakah RPD sudah ada di tabel
        $exists = DB::table('laporan_perjadinkegiatans')
            ->where('data_perjadin_kegiatan', $id)
            ->where('nama_dokumen', 'RPD Kegiatan '.$id)
            ->exists();

        // Jika belum ada, tambahkan entri baru
        if (!$exists) {
            DB::table('laporan_perjadinkegiatans')->insert([
                'nama_dokumen' => 'RPD Kegiatan '.$id,
                'file' => $fileName,
                'data_perjadin_kegiatan' => $id,
                'status' => 'sesuai',
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        } else {
            DB::table('laporan_perjadinkegiatans')
                ->where('data_perjadin_kegiatan', $id)
                ->where('nama_dokumen', 'RPD Kegiatan '.$id)
                ->update([
                    'nama_dokumen' => 'RPD Kegiatan '.$id,
                    'file' => $fileName,
                    'data_perjadin_kegiatan' => $id,
                    'status' => 'sesuai',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        // Stream file PDF ke browser
        return $pdf->stream("rpd_kegiatan_$id.pdf");
    }

    public function CetakRPDKat($id, $kategori)
    {
        if (!$id) {
            // Handle jika data tidak ditemukan
            dd('Data tidak ditemukan.');
        }

       

        $pesertaPegawais = DB::table('data_perjadinkegiatans as i')
        ->join('perangkat_acaras as dp', 'i.id', '=', 'dp.data_perjadin_kegiatan')
        ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
        ->join('keuangan_perjadinkegiatans as k', function ($join) {
            $join->on('k.data_perjadinkegiatan', '=', 'i.id')
                ->on('k.perangkat_acara', '=', 'dp.id');
        })
        ->join('akun_x_rkakls as a', 'k.akun_x_rkakl', '=', 'a.id') // Join ke akun_x_rkakls
        ->join('akuns', 'a.akun_id', '=', 'akuns.id')
        ->join('ref_rkakl_sub_komponens', 'a.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
        ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
        ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
        ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
        ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
        ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
        ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
        ->join('surtug_perjadinkegiatans as s', 's.data_perjadin_kegiatan', '=', 'i.id')
        ->where('i.id', $id)
        ->whereNull('k.kebutuhan_id')
        ->where('dp.posisi', $kategori)
        ->where('k.kode', 'harian')
        ->select(
            'i.id as id_data_perjadinkegiatan',
            'p.id',
            'p.nama_lengkap',
            'p.NIP_NIK',
            // 's.nomor_surat',
            // 's.tgl_surat_dibuat',
            // 's.paragraf_1',
            'i.nama_kegiatan',
            'i.tgl_mulai',
            'i.kab_kota',
            'i.provinsi',
            'i.tgl_selesai',
            'k.uang_harian',
            'k.uang_harian_fullday',
            'k.uang_harian_fullboard',
            'k.uang_representasi',
            'k.nominal_perjadin',
            'a.id as idAkun',
            'ref_rkakl_sub_komponens.nama_sub_kegiatan',
            'akuns.uraian',
            'ref_rkakl_satkers.kode_satker',
            'ref_rkakl_programs.kode_program',
            'ref_rkakl_kegiatans.kode_kegiatan',
            'ref_rkakl_outputs.kode_output',
            'ref_rkakl_suboutputs.kode_sub_output',
            'ref_rkakl_komponens.kode_komponen',
            'ref_rkakl_sub_komponens.kode_sub_kegiatan',
            'akuns.kode_akun'
        )
        ->groupBy(
            'i.id',
            'p.id',
            'p.nama_lengkap',
            'p.NIP_NIK',
            // 's.nomor_surat',
            // 's.tgl_surat_dibuat',
            // 's.paragraf_1',
            'i.nama_kegiatan',
            'i.tgl_mulai',
            'i.kab_kota',
            'i.provinsi',
            'i.tgl_selesai',
            'k.uang_harian',
            'k.uang_harian_fullday',
            'k.uang_harian_fullboard',
            'k.uang_representasi',
            'k.nominal_perjadin',
            'a.id',
            'ref_rkakl_sub_komponens.nama_sub_kegiatan',
            'akuns.uraian',
            'ref_rkakl_satkers.kode_satker',
            'ref_rkakl_programs.kode_program',
            'ref_rkakl_kegiatans.kode_kegiatan',
            'ref_rkakl_outputs.kode_output',
            'ref_rkakl_suboutputs.kode_sub_output',
            'ref_rkakl_komponens.kode_komponen',
            'ref_rkakl_sub_komponens.kode_sub_kegiatan',
            'akuns.kode_akun'
        )
        ->distinct()
        ->get();


        $fasilitasPegawais = DB::table('data_perjadinkegiatans as i')
            ->join('perangkat_acaras as dp', 'i.id', '=', 'dp.data_perjadin_kegiatan')
            ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
            ->join('keuangan_perjadinkegiatans as k', function ($join) {
                $join->on('k.data_perjadinkegiatan', '=', 'i.id')
                     ->on('k.perangkat_acara', '=', 'dp.id');
            })
            ->join('surtug_perjadinkegiatans as s', 's.data_perjadin_kegiatan', '=', 'i.id')
            ->leftJoin('kebutuhans as kb', 'k.kebutuhan_id', '=', 'kb.id')
            ->where('i.id', $id)
            ->whereNotNull('k.kebutuhan_id')
            ->whereIn('dp.posisi', [$kategori])
            ->select(
                'i.id as id_data_perjadinkegiatan',
                'p.id as pegawai_id',
                'p.nama_lengkap',
                'p.NIP_NIK',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kab_kota',
                'i.provinsi',
                'i.tgl_selesai',
                'k.jumlah_harga',
                'k.kebutuhan_id',
                'kb.nama as nama_kebutuhan',
                'kb.jumlah_frekuensi',
                'kb.satuan',
                'kb.ket'
            )
            ->groupBy(
                'i.id',
                'p.id',
                'p.nama_lengkap',
                'p.NIP_NIK',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kab_kota',
                'i.provinsi',
                'i.tgl_selesai',
                'k.jumlah_harga',
                'k.kebutuhan_id',
                'kb.nama',
                'kb.jumlah_frekuensi',
                'kb.satuan',
                'kb.ket'
            )
            ->get();

        $fasilitasNonPegawais = DB::table('data_perjadinkegiatans as i')
        ->join('perangkat_acaras as dp', 'i.id', '=', 'dp.data_perjadin_kegiatan')
        ->join('non_pegawais as p', 'dp.non_pegawai_id', '=', 'p.id')
        ->join('keuangan_perjadinkegiatans as k', function ($join) {
            $join->on('k.data_perjadinkegiatan', '=', 'i.id')
                 ->on('k.perangkat_acara', '=', 'dp.id');
        })
        ->join('surtug_perjadinkegiatans as s', 's.data_perjadin_kegiatan', '=', 'i.id')
        ->leftJoin('kebutuhans as kb', 'k.kebutuhan_id', '=', 'kb.id')
        ->where('i.id', $id)
        ->whereNotNull('k.kebutuhan_id')
        ->select(
            'i.id as id_data_perjadinkegiatan',
            'p.id as pegawai_id',
            'p.nama_lengkap',
            'p.NIP_NIK',
            's.nomor_surat',
            's.tgl_surat_dibuat',
            's.paragraf_1',
            'i.nama_kegiatan',
            'i.tgl_mulai',
            'i.kab_kota',
            'i.provinsi',
            'i.tgl_selesai',
            'k.jumlah_harga',
            'k.kebutuhan_id',
            'kb.nama as nama_kebutuhan',
            'kb.jumlah_frekuensi',
            'kb.satuan',
            'kb.ket'
        )
        ->groupBy(
            'i.id',
            'p.id',
            'p.nama_lengkap',
            'p.NIP_NIK',
            's.nomor_surat',
            's.tgl_surat_dibuat',
            's.paragraf_1',
            'i.nama_kegiatan',
            'i.tgl_mulai',
            'i.kab_kota',
            'i.provinsi',
            'i.tgl_selesai',
            'k.jumlah_harga',
            'k.kebutuhan_id',
            'kb.nama',
            'kb.jumlah_frekuensi',
            'kb.satuan',
            'kb.ket'
        )
        ->get();


        // dd($fasilitasNonPegawais);
        $pesertaNonPegawais = DB::table('data_perjadinkegiatans as i')
            ->join('perangkat_acaras as dp', 'i.id', '=', 'dp.data_perjadin_kegiatan')
            ->join('non_pegawais as p', 'dp.non_pegawai_id', '=', 'p.id')
            ->join('keuangan_perjadinkegiatans as k', function ($join) {
                $join->on('k.data_perjadinkegiatan', '=', 'i.id')
                    ->on('k.perangkat_acara', '=', 'dp.id');
            })
            ->join('akun_x_rkakls as a', 'k.akun_x_rkakl', '=', 'a.id') // Join ke akun_x_rkakls
            ->join('akuns', 'a.akun_id', '=', 'akuns.id')
            ->join('ref_rkakl_sub_komponens', 'a.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
            ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
            // ->join('surtug_perjadinkegiatans as s', 's.data_perjadin_kegiatan', '=', 'i.id')
            ->where('i.id', $id)
            ->whereNull('k.kebutuhan_id')
            ->where('dp.posisi', $kategori)
            // ->where('k.kode', 'harian')
            ->select(
                'i.id as id_data_perjadinkegiatan',
                'p.id',
                'p.nama_lengkap',
                'p.NIP_NIK',
                // 's.nomor_surat',
                // 's.tgl_surat_dibuat',
                // 's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kab_kota',
                'i.provinsi',
                'i.tgl_selesai',
                'k.uang_harian',
                'k.uang_harian_fullday',
                'k.uang_harian_fullboard',
                'k.uang_representasi',
                'k.nominal_perjadin',
                'a.id as idAkun',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'ref_rkakl_satkers.kode_satker',
                'ref_rkakl_programs.kode_program',
                'ref_rkakl_kegiatans.kode_kegiatan',
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'akuns.kode_akun'
            )
            ->groupBy(
                'i.id',
                'p.id',
                'p.nama_lengkap',
                'p.NIP_NIK',
                // 's.nomor_surat',
                // 's.tgl_surat_dibuat',
                // 's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kab_kota',
                'i.provinsi',
                'i.tgl_selesai',
                'k.uang_harian',
                'k.uang_harian_fullday',
                'k.uang_harian_fullboard',
                'k.uang_representasi',
                'k.nominal_perjadin',
                'a.id',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'ref_rkakl_satkers.kode_satker',
                'ref_rkakl_programs.kode_program',
                'ref_rkakl_kegiatans.kode_kegiatan',
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'akuns.kode_akun'
            )
            ->distinct()
            ->get();

        
        if($kategori == 'Supir') {
            $pengemudis = DB::table('data_perjadinkegiatans as i')
                ->join('perangkat_acaras as dp', 'i.id', '=', 'dp.data_perjadin_kegiatan')
                ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
                ->join('keuangan_perjadinkegiatans as k', function ($join) {
                    $join->on('k.data_perjadinkegiatan', '=', 'i.id')
                        ->on('k.perangkat_acara', '=', 'dp.id');
                })
                ->join('akun_x_rkakls as a', 'k.akun_x_rkakl', '=', 'a.id') // Join ke akun_x_rkakls
                ->join('akuns', 'a.akun_id', '=', 'akuns.id')
                ->join('ref_rkakl_sub_komponens', 'a.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
                ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
                ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
                ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
                ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
                ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
                ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
                ->join('surtug_perjadinkegiatans as s', 's.data_perjadin_kegiatan', '=', 'i.id')
                ->where('i.id', $id)
                ->whereNull('k.kebutuhan_id')
                ->where('dp.posisi', 'Supir')
                ->select(
                    'i.id as id_data_perjadinkegiatan',
                    'p.nama_lengkap',
                    'p.id',
                    'p.NIP_NIK',
                    's.nomor_surat',
                    's.tgl_surat_dibuat',
                    's.paragraf_1',
                    'i.nama_kegiatan',
                    'i.tgl_mulai',
                    'i.kab_kota',
                    'i.provinsi',
                    'i.tgl_selesai',
                    'k.uang_harian',
                    'k.uang_harian_fullday',
                    'k.uang_harian_fullboard',
                    'k.uang_representasi',
                    'k.nominal_perjadin',
                    'k.jumlah_harga',
                    'a.id as idAkun',
                    'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                    'akuns.uraian',
                    'ref_rkakl_satkers.kode_satker',
                    'ref_rkakl_programs.kode_program',
                    'ref_rkakl_kegiatans.kode_kegiatan',
                    'ref_rkakl_outputs.kode_output',
                    'ref_rkakl_suboutputs.kode_sub_output',
                    'ref_rkakl_komponens.kode_komponen',
                    'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                    'akuns.kode_akun'
                )
                ->groupBy(
                    'i.id',
                    'p.id',
                    'p.nama_lengkap',
                    'p.NIP_NIK',
                    's.nomor_surat',
                    's.tgl_surat_dibuat',
                    's.paragraf_1',
                    'i.nama_kegiatan',
                    'i.tgl_mulai',
                    'i.kab_kota',
                    'i.provinsi',
                    'i.tgl_selesai',
                    'k.uang_harian',
                    'k.uang_harian_fullday',
                    'k.uang_harian_fullboard',
                    'k.uang_representasi',
                    'k.nominal_perjadin',
                    'k.jumlah_harga',
                    'a.id',
                    'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                    'akuns.uraian',
                    'ref_rkakl_satkers.kode_satker',
                    'ref_rkakl_programs.kode_program',
                    'ref_rkakl_kegiatans.kode_kegiatan',
                    'ref_rkakl_outputs.kode_output',
                    'ref_rkakl_suboutputs.kode_sub_output',
                    'ref_rkakl_komponens.kode_komponen',
                    'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                    'akuns.kode_akun'
                )
                ->distinct()
                ->get();

            $fasilitasPengemudis = DB::table('data_perjadinkegiatans as i')
                ->join('perangkat_acaras as dp', 'i.id', '=', 'dp.data_perjadin_kegiatan')
                ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
                ->join('keuangan_perjadinkegiatans as k', function ($join) {
                    $join->on('k.data_perjadinkegiatan', '=', 'i.id')
                        ->on('k.perangkat_acara', '=', 'dp.id');
                })
                ->join('akun_x_rkakls as a', 'k.akun_x_rkakl', '=', 'a.id') // Join ke akun_x_rkakls
                ->join('akuns', 'a.akun_id', '=', 'akuns.id')
                ->join('ref_rkakl_sub_komponens', 'a.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
                ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
                ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
                ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
                ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
                ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
                ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
                ->join('surtug_perjadinkegiatans as s', 's.data_perjadin_kegiatan', '=', 'i.id')
                ->where('i.id', $id)
                ->whereNull('k.kebutuhan_id')
                ->where('dp.posisi', 'Supir')
                ->select(
                    'i.id as id_data_perjadinkegiatan',
                    'p.nama_lengkap',
                    'p.id',
                    'p.NIP_NIK',
                    's.nomor_surat',
                    's.tgl_surat_dibuat',
                    's.paragraf_1',
                    'i.nama_kegiatan',
                    'i.tgl_mulai',
                    'i.kab_kota',
                    'i.provinsi',
                    'i.tgl_selesai',
                    'k.jumlah_harga',
                    'a.id as idAkun',
                    'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                    'akuns.uraian',
                    'ref_rkakl_satkers.kode_satker',
                    'ref_rkakl_programs.kode_program',
                    'ref_rkakl_kegiatans.kode_kegiatan',
                    'ref_rkakl_outputs.kode_output',
                    'ref_rkakl_suboutputs.kode_sub_output',
                    'ref_rkakl_komponens.kode_komponen',
                    'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                    'akuns.kode_akun'
                )
                ->groupBy(
                    'i.id',
                    'p.id',
                    'p.nama_lengkap',
                    'p.NIP_NIK',
                    's.nomor_surat',
                    's.tgl_surat_dibuat',
                    's.paragraf_1',
                    'i.nama_kegiatan',
                    'i.tgl_mulai',
                    'i.kab_kota',
                    'i.provinsi',
                    'i.tgl_selesai',
                    'k.jumlah_harga',
                    'a.id',
                    'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                    'akuns.uraian',
                    'ref_rkakl_satkers.kode_satker',
                    'ref_rkakl_programs.kode_program',
                    'ref_rkakl_kegiatans.kode_kegiatan',
                    'ref_rkakl_outputs.kode_output',
                    'ref_rkakl_suboutputs.kode_sub_output',
                    'ref_rkakl_komponens.kode_komponen',
                    'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                    'akuns.kode_akun'
                )
                ->distinct()
                ->get();
            } else {
                $pengemudis = collect();
                $fasilitasPengemudis = collect();;
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

        $pegawaiMaster = DB::table('ref_penandatangans')
            ->join('pegawais', 'pegawais.id', '=', 'ref_penandatangans.pegawai_id')
            ->select('pegawais.*')
            ->where('ref_penandatangans.posisi_penandatangan', 'Pejabat Pembuat Komitmen')
            ->where('ref_penandatangans.status_penandatangan', 'aktif')
            ->first();

        $pegawaiBendahara = DB::table('ref_penandatangans')
            ->join('pegawais', 'pegawais.id', '=', 'ref_penandatangans.pegawai_id')
            ->select('pegawais.*')
            ->where('ref_penandatangans.posisi_penandatangan', 'Bendahara')
            ->where('ref_penandatangans.status_penandatangan', 'aktif')
            ->first();

        $cekSurtug = DB::table('surtug_perjadinkegiatans')
                ->where('data_perjadin_kegiatan', $id)
                ->first();

        if (!$cekSurtug) {
            $dataTambahan = DB::table('data_perjadinkegiatans')->where('id', $id)->first();

            // Format tanggal mulai dan selesai
            $formatedTglMulai = Carbon::parse($dataTambahan->tgl_mulai)->translatedFormat('l, d F Y'); // contoh: Selasa, 02 Juli 2025
            $formatedTglSelesai = Carbon::parse($dataTambahan->tgl_selesai)->translatedFormat('l, d F Y');

            $paragraf = "untuk melaksanakan tugas {$dataTambahan->nama_kegiatan} pada hari {$formatedTglMulai} s.d {$formatedTglSelesai}";
            $nomorSurat = 'Tanpa Surat Tugas';
            $tglSuratDibuat = now()->format('Y-m-d');

            // Fungsi untuk menambahkan atribut ke setiap item Collection
            $addDefaultSurat = function ($item) use ($paragraf, $nomorSurat, $tglSuratDibuat) {
                $item->nomor_surat = $nomorSurat;
                $item->tgl_surat_dibuat = $tglSuratDibuat;
                $item->paragraf_1 = $paragraf;
                return $item;
            };

            // Tambahkan ke setiap Collection
            $pesertaNonPegawais = $pesertaNonPegawais->map($addDefaultSurat);
            $pesertaPegawais = $pesertaPegawais->map($addDefaultSurat);
        } else {
            $paragraf =  $cekSurtug->paragraf_1;
            $nomorSurat = $cekSurtug->nomor_surat;
            $tglSuratDibuat = $cekSurtug->tgl_surat_dibuat;

            // Fungsi untuk menambahkan atribut ke setiap item Collection
            $addDefaultSurat = function ($item) use ($paragraf, $nomorSurat, $tglSuratDibuat) {
                $item->nomor_surat = $nomorSurat;
                $item->tgl_surat_dibuat = $tglSuratDibuat;
                $item->paragraf_1 = $paragraf;
                return $item;
            };

            // Tambahkan ke setiap Collection
            $pesertaNonPegawais = $pesertaNonPegawais->map($addDefaultSurat);
            $pesertaPegawais = $pesertaPegawais->map($addDefaultSurat);
        }

        $datas = [
            'pesertaPegawais' => $pesertaPegawais,
            'fasilitasPegawais' => $fasilitasPegawais,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'fasilitasNonPegawais' => $fasilitasNonPegawais,
            'pengemudis' => $pengemudis,
            'fasilitasPengemudis' => $fasilitasPengemudis,
            'pegawaiMaster' => $pegawaiMaster,
            'pegawaiBendahara' => $pegawaiBendahara,
            'kegitan' => Data_perjadinkegiatan::find($id),
            'akuns' => $akuns,
        ];

        // dd($datas);

        // dd($datas);
        // Lakukan proses pembuatan file PDF
        $pdf = PDF::loadView('admin.kegiatan.bendahara.rpd', compact('datas'));
        $pdf->setPaper('A4', 'portrait');

        // Simpan file PDF ke penyimpanan
        $fileName = "dokumen-kegiatans/rpd_kegiatan_$id.pdf";
        Storage::disk('public')->put($fileName, $pdf->output());

        // Periksa apakah RPD sudah ada di tabel
        $exists = DB::table('laporan_perjadinkegiatans')
            ->where('data_perjadin_kegiatan', $id)
            ->where('nama_dokumen', 'RPD Kegiatan '.$id)
            ->exists();

        // Jika belum ada, tambahkan entri baru
        if (!$exists) {
            DB::table('laporan_perjadinkegiatans')->insert([
                'nama_dokumen' => 'RPD Kegiatan '.$id,
                'file' => $fileName,
                'data_perjadin_kegiatan' => $id,
                'status' => 'sesuai',
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        } else {
            DB::table('laporan_perjadinkegiatans')
                ->where('data_perjadin_kegiatan', $id)
                ->where('nama_dokumen', 'RPD Kegiatan '.$id)
                ->update([
                    'nama_dokumen' => 'RPD Kegiatan '.$id,
                    'file' => $fileName,
                    'data_perjadin_kegiatan' => $id,
                    'status' => 'sesuai',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        // Stream file PDF ke browser
        return $pdf->stream("rpd_kegiatan_$id.pdf");
    }

    public function storeFasilitasDetailBendahara(Request $request)
    {
        // dd($request);
        // DB::table('kebutuhans')->insert([
        //     'nama' => $request->uraian,
        //     'status' => 'Pengajuan',
        //     'jumlah_frekuensi' => $request->jumlah_frekuensi,
        //     'satuan' => $request->satuan,
        //     'tipe_pendanaan' => $request->tipe_pendanaan,
        //     'ket' => $request->keterangan,
        //     'created_at' => now()->format('Y-m-d H:i:s'),
        //     'updated_at' => now()->format('Y-m-d H:i:s'),
        // ]);
        // Validasi data, jika diperlukan
            $request->validate([
                'uraian' => 'required',
                // Validasi lainnya
            ]);

            // Simpan data ke database
            DB::table('kebutuhans')->insert([
                'nama' => $request->uraian,
                'status' => 'Pengajuan',
                'jumlah_frekuensi' => $request->jumlah_frekuensi,
                'satuan' => $request->satuan,
                'tipe_pendanaan' => $request->tipe_pendanaan,
                'ket' => $request->keterangan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Dapatkan ID kebutuhan terbaru
            $kebutuhan_max = DB::table('kebutuhans')->max('id');

            // Simpan data ke tabel keuangan
            DB::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                'data_perjadinkegiatan' => $request->data_perjadinkegiatans,
                'perangkat_acara' => $request->perangkat_acara,
                'kebutuhan_id' => $kebutuhan_max,
                'status' => 'Menunggu Persetujuan Bendahara',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Redirect ke halaman detail-bendahara
            return redirect()->route('bendahara-kegiatan-fasilitas', ['id' => $request->data_perjadinkegiatans])
                ->with('success', 'Data berhasil disimpan!');
        }

        public function storeFasilitasDetailKeuangan(Request $request)
    {
        // dd($request);
        // DB::table('kebutuhans')->insert([
        //     'nama' => $request->uraian,
        //     'status' => 'Pengajuan',
        //     'jumlah_frekuensi' => $request->jumlah_frekuensi,
        //     'satuan' => $request->satuan,
        //     'tipe_pendanaan' => $request->tipe_pendanaan,
        //     'ket' => $request->keterangan,
        //     'created_at' => now()->format('Y-m-d H:i:s'),
        //     'updated_at' => now()->format('Y-m-d H:i:s'),
        // ]);
        // Validasi data, jika diperlukan
            $request->validate([
                'uraian' => 'required',
                // Validasi lainnya
            ]);

            // Simpan data ke database
            DB::table('kebutuhans')->insert([
                'nama' => $request->uraian,
                'status' => 'Pengajuan',
                'jumlah_frekuensi' => $request->jumlah_frekuensi,
                'satuan' => $request->satuan,
                'tipe_pendanaan' => $request->tipe_pendanaan,
                'ket' => $request->keterangan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Dapatkan ID kebutuhan terbaru
            $kebutuhan_max = DB::table('kebutuhans')->max('id');

            // Simpan data ke tabel keuangan
            DB::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                'perangkat_acara' => $request->perangkat_acara,
                'data_perjadinkegiatan' => $request->data_perjadinkegiatans,
                'kebutuhan_id' => $kebutuhan_max,
                'status' => 'Menunggu Persetujuan Bendahara',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Redirect ke halaman detail-bendahara
            return redirect()->route('detail_keuangan', ['id' => $request->data_perjadinkegiatans])
                ->with('success', 'Data berhasil disimpan!');
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
        $action = $request->input('action');

        $pengusul = DB::table('pegawais')
            ->join('data_perjadinkegiatans', 'pegawais.id', '=', 'data_perjadinkegiatans.id_pengaju')
            ->select('data_perjadinkegiatans.id_pengaju', 'pegawais.nama_lengkap')
            ->where('data_perjadinkegiatans.id',$request->input('idKegiatan'))
            ->first(); // Mengambil hasil pertama


        $dokumen = DB::table('laporan_perjadinkegiatans')
            ->where('laporan_perjadinkegiatans.data_perjadin_kegiatan',$request->input('idKegiatan'))
            ->first();



        if ($action === 'proses') {
            $numMobilitas = $request->input('numMobilitas');

            for ($i = 0; $i < $numMobilitas; $i++) {
                $idMobilitas = $request->input('idMobilitas_' . $i);
                $kendaraan = $request->input('mobil_' . $i);
                $namaKegiatan = $request->input('nama_kegiatan_' . $i);
                $provinsi = $request->input('provinsi_' . $i);
                $alamat = $request->input('alamat_' . $i);
                $kabKota = $request->input('kabupaten_kota_' . $i);
                $supir = $request->input('supir_' . $i);
                $status = $request->input('status_' . $i);
                $berangkat = $request->input('tglBerangkat_' . $i);
                $selesai = $request->input('tglSelesai_' . $i);
                $ketMobilitas = $request->input('ket_' . $i);
                $gabungSurtug = $request->input('gabungSurtug_' . $i);

                // Konversi format tanggal
                $berangkat = Carbon::createFromFormat('d-m-Y H:i', $berangkat)->format('Y-m-d H:i:s');
                $selesai = Carbon::createFromFormat('d-m-Y H:i', $selesai)->format('Y-m-d H:i:s');

                // Update kendaraan
                DB::table('kendaraans')
                    ->join('peminjaman_kendaraan_dinas', 'peminjaman_kendaraan_dinas.kendaraan', '=', 'kendaraans.id')
                    ->where('kendaraans.id', $kendaraan)
                    ->update([
                        'kendaraans.updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);

                if(!$gabungSurtug){
                    if($ketMobilitas == "Antar"){
                        $namaKegiatan = "Mengantar Pelaksana Tugas ".$namaKegiatan;
                    } elseif ($ketMobilitas == "Jemput") {
                        $namaKegiatan = "Menjemput Pelaksana Tugas ".$namaKegiatan;
                    } elseif ($ketMobilitas == "Antar-Jemput") {
                        $namaKegiatan = "Mengantar dan Menjemput Pelaksana Tugas ".$namaKegiatan;
                    }


                    $versi = Versi::where('status', 'aktif')->get();
                    DB::table('data_perjadinkegiatans')->insertOrIgnore([
                        'nama_kegiatan' => $namaKegiatan,
                        'tgl_mulai' => $berangkat,
                        'tgl_selesai' => $selesai,
                        'is_acceptAset' => '-',
                        'provinsi' => $provinsi,
                        'kab_kota' => $kabKota,
                        'jumlah_peserta' => 0,
                        'jumlah_kepanitiaan' => 0,
                        'jumlah_kamar' => 0,
                        'tambah_penginapan' => 0,
                        'alamat' => $alamat,
                        'status' => 'pengajuan',
                        'status_pengajuan' => 'Draf-pengajuan',
                        'versi_id' => $versi[0]->id,
                        'created_at' => now()->format('Y-m-d H:i:s'),
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);

                    $kegiatanBaru = Data_perjadinkegiatan::max('id');

                    DB::table('laporan_perjadinkegiatans')->insertOrIgnore([
                        'nama_dokumen' => $dokumen->nama_dokumen,
                        'file' => $dokumen->file,
                        'status' => $dokumen->status,
                        'data_perjadin_kegiatan' => $kegiatanBaru,
                        'keterangan' => 'Bukan Dokumen Induk',
                        // 'versi_id' => $versi[0]->id,
                        'created_at' => now()->format('Y-m-d H:i:s'),
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);

                    DB::table('mobilitas_perjadinkegiatans')->insertOrIgnore([
                        'mobilitas' => 'Kendaraan Dinas LLDIKTI',
                        'tujuan_penggunaan' => $ketMobilitas,
                        'tgl_mulai' => $berangkat,
                        'tgl_selesai' => $selesai,
                        'status' => 'selesai',
                        'unit' => 1,
                        'data_perjadinkegiatan' => $kegiatanBaru,
                        'versi_id' => $versi[0]->id,
                        'created_at' => now()->format('Y-m-d H:i:s'),
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);

                    $mobilitasBaru = Mobilitas_perjadinkegiatan::max('id');

                    // Update peminjaman kendaraan dinas
                    DB::table('peminjaman_kendaraan_dinas')
                    ->where('id', $idMobilitas)
                    ->update([
                        'mobilitas_perjadinkegiatan' => $mobilitasBaru,
                        'updated_at' => now()->format('Y-m-d H:i:s')
                    ]);


                } else {
                    // Update peminjaman kendaraan dinas
                    DB::table('peminjaman_kendaraan_dinas')
                    ->where('id', $idMobilitas)
                    ->update([
                        'pegawai_id' => $supir,
                        'status' => $status,
                        'tgl_keberangkatan' => $berangkat,
                        'tgl_selesai' => $selesai,
                        'ket_mobilitas' => $ketMobilitas,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
                }

                $berangkat = $request->input('berangkat_' . $i);
                $selesai = $request->input('selesai_' . $i);
                $berangkat = Carbon::createFromFormat('d-m-Y H:i', $berangkat)->format('Y-m-d H:i:s');
                $selesai = Carbon::createFromFormat('d-m-Y H:i', $selesai)->format('Y-m-d H:i:s');

                // Jika status adalah 'proses'
                if ($status === 'proses') {
                    if ($gabungSurtug) {
                        $fasilitasExists = DB::table('fasilitas')
                            ->where('data_perjadinkegiatan_id',  $request->input('idKegiatan'))
                            ->where('nama_fasilitas', 'Supir')
                            ->exists();

                        if (!$fasilitasExists) {
                            DB::table('fasilitas')->insert([
                                'data_perjadinkegiatan_id' => $request->input('idKegiatan'),
                                'nama_fasilitas' => 'Supir',
                                'created_at' => now()->format('Y-m-d H:i:s'),
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                            ]);

                        }
                        $fasilitas_max = Fasilitas::max('id');

                        DB::table('perangkat_acaras')->insertOrIgnore([
                            'sebagai' => 'Supir',
                            'posisi' => 'Supir',
                            'data_perjadin_kegiatan' => $request->input('idKegiatan'),
                            'pegawai_id' => $supir,
                            'fasilitas_id' => $fasilitas_max,
                            // 'tgl_keberangkatan' => $berangkat,
                            // 'tgl_selesai' => $selesai,
                            'status' => 'Diproses',
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);

                        $perangkat_acaras_max = Perangkat_acara::max('id');

                        $mobilitasLama = DB::table('mobilitas_perjadinkegiatans')
                            ->where('data_perjadinkegiatan', $request->input('idKegiatan'))
                            ->first();

                        DB::table('operasionals')->insertOrIgnore([
                            'nama' => $ketMobilitas,
                            'jumlah_frekuensi' => '1',
                            'ket' => 'Keperluan Operasional',
                            'status' => 'pengajuan',
                            'data_perjadin_kegiatan' => $mobilitasLama->id,
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);

                        $operasionals_max = Operasional::max('id');

                        DB::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                            'data_perjadinkegiatan' => $request->input('idKegiatan'),
                            'perangkat_acara' => $perangkat_acaras_max,
                            'operasional' => $operasionals_max,
                            'status' => 'Menunggu Persetujuan Bendahara',
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);



                    } else {
                        DB::table('fasilitas')->insert([
                            'data_perjadinkegiatan_id' => $kegiatanBaru,
                            'nama_fasilitas' => 'Supir',
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);

                        $fasilitas_new_max = Fasilitas::max('id');

                        DB::table('perangkat_acaras')->insertOrIgnore([
                            'sebagai' => 'Supir',
                            'posisi' => 'Supir',
                            'data_perjadin_kegiatan' => $kegiatanBaru,
                            'pegawai_id' => $supir,
                            'fasilitas_id' => $fasilitas_new_max,
                            // 'tgl_keberangkatan' => $berangkat,
                            // 'tgl_selesai' => $selesai,
                            'status' => 'Diproses',
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);

                        $perangkat_acaras_new_max = Perangkat_acara::max('id');

                        DB::table('operasionals')->insertOrIgnore([
                            'nama' => $ketMobilitas,
                            'jumlah_frekuensi' => '1',
                            'ket' => 'Keperluan Operasional',
                            'status' => 'pengajuan',
                            'data_perjadin_kegiatan' => $mobilitasBaru,
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);

                        $operasionals_new_max = Operasional::max('id');

                        DB::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                            'data_perjadinkegiatan' => $kegiatanBaru,
                            'perangkat_acara' => $perangkat_acaras_new_max,
                            'operasional' => $operasionals_new_max,
                            'status' => 'Menunggu Persetujuan Bendahara',
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                    }
                }

                if (!$gabungSurtug) {
                    DB::table('data_perjadinkegiatans')
                    ->where('id', $kegiatanBaru)
                    ->update([
                        'is_acceptBMN' => 'selesai',
                        'is_acceptHKT' => 'pengajuan',
                        'status_pengajuan'  => 'pengajuan',
                        'status' => 'pengajuan',
                        'status_pengajuan_detail' => 'Verifikasi-HKT',
                        'id_pengaju' => auth('administrator')->user()->id,
                        'admin_BMN' => auth('administrator')->user()->id,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
                }

            }
            DB::table('data_perjadinkegiatans')
                    ->where('id', $request->input('idKegiatan'))
                    ->update([
                        'is_acceptBMN' => 'selesai',
                        'is_acceptHKT' => 'pengajuan',
                        'status_pengajuan'  => 'pengajuan',
                        'status' => 'pengajuan',
                        'status_pengajuan_detail' => 'Verifikasi-HKT',
                        'admin_BMN' => auth('administrator')->user()->id,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);

                    DB::table('mobilitas_perjadinkegiatans')
                    ->where('data_perjadinkegiatan', $request->input('idKegiatan'))
                    ->update([
                        'status' => 'selesai',
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);



            $dataNotif = [
                'id_kegiatan' => $request->input('idKegiatan'),
                'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
                'to' => 0, // ID pengguna yang menerima
                'role' => 'HKT', // Peran pengguna
                'header' => 'Usulan Kegiatan - '.$request->input('idKegiatan'), // Judul notifikasi
                'message' => 'Usulan '.$request->input('idKegiatan').' telah diverifikasi BMN', // Isi pesan
                'route' => 'kegiatan-HKT/detail/'.$request->input('idKegiatan'), // Route yang dituju
                'is_read' => 0, // Status belum dibaca
                'versi_id' => session('versi'),
                'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
            ];

            // Melakukan insert ke tabel notifications
            DB::table('notifications')->insert($dataNotif);

            $dataNotifUser = [
                    'id_kegiatan' => $request->input('idKegiatan'),
                    'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
                    'to' => '0', // ID pengguna yang menerima
                    'role' => null, // Peran pengguna
                    'header' => 'Usulan Kegiatan Disetujui BMN - '.$request->input('idKegiatan'), // Judul notifikasi
                    'message' => 'Usulan yang diajukan dengan id '.$request->input('idKegiatan').' telah diproses BMN harap menunggu Verifikasi-HKT', // Isi pesan
                    'route' => 'kegiatan/riwayat/proses', // Route yang dituju
                    'is_read' => 0, // Status belum dibaca
                    'versi_id' => session('versi'),
                    'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
                    ];

                // Melakukan insert ke tabel notifications
                DB::table('notifications')->insert($dataNotifUser);


            return redirect()->route('mobilitas', ['status' => $request->input('kegiatanStatus')])->with('success', 'Data telah diperbaharui!');
        } elseif ($action === 'tolak') {
            DB::table('data_perjadinkegiatans')
                ->where('id', $request->input('idKegiatan'))
                ->update([
                    'status_pengajuan_detail' => 'Verifikasi-BMN-ditolak',
                    'status_pengajuan' => 'ditolak',
                    'is_acceptBMN' => 'ditolak',
                    'alasan_penolakan' =>  $request->input('alasan'),
                    'admin_BMN' => auth('administrator')->user()->id,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

            DB::table('mobilitas_perjadinkegiatans')
                ->where('data_perjadinkegiatan', $request->input('idKegiatan'))
                ->update([
                    'status' => 'ditolak',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);



                // DB::table('peminjaman_kendaraan_dinas')
                // ->where('id', $request->input('idKegiatan'))
                // ->update([
                //     'status_pengajuan_detail' => 'Verifikasi-BMN-ditolak',
                //     'status_pengajuan' => 'ditolak',
                //     'is_acceptBMN' => 'ditolak',
                //     'alasan_penolakan' =>  $request->input('alasan'),
                //     'admin_BMN' => auth('administrator')->user()->id,
                //     'updated_at' => now()->format('Y-m-d H:i:s'),
                // ]);

            DB::table('perangkat_acaras')
                ->where('data_perjadin_kegiatan', $request->input('idKegiatan'))
                ->update([
                    'status' => 'Ditolak',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

            $dataNotif = [
                'id_kegiatan' => $request->input('idKegiatan'),
                'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
                'to' => '0', // ID pengguna yang menerima
                'role' => null, // Peran pengguna
                'header' => 'Usulan Kegiatan Ditolak - '.$request->input('idKegiatan'), // Judul notifikasi
                'message' => 'Usulan yang diajukan dengan id '.$request->input('idKegiatan').' ditolak oleh BMN', // Isi pesan
                'route' => 'kegiatan/riwayat/ditolak', // Route yang dituju
                'is_read' => 0, // Status belum dibaca
                'versi_id' => session('versi'),
                'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
                ];

            // Melakukan insert ke tabel notifications
            DB::table('notifications')->insert($dataNotif);

            return redirect()->route('mobilitas', ['status' => $request->input('kegiatanStatus')])->with('success', 'Pengajuan Telah Ditolak!');
        }
    }


    public function storeMobilitas(Request $request)
    {
        // $gabungSurtug = $request->gabungSurtug;
        // Pengecekan apakah semua data ada
        $requiredFields = [
            'kendaraan',
            'pengemudi',
            'ket_mobilitas',
            'tgl_keberangkatan'
        ];

        foreach ($requiredFields as $field) {
            if (is_null($request->input($field))) {
                return redirect()->route('detail-mobilitas-kegiatan', ['id' => $request->idMobilitas])
                                ->with('success', 'Data tidak berhasil ditambahkan, data tidak lengkap');
            }
        }

        db::table('peminjaman_kendaraan_dinas')->insertOrIgnore([
            'mobilitas_perjadinkegiatan' => $request->idMobilitas,
            'kendaraan' => $request->kendaraan,
            'pegawai_id' => $request->pengemudi,
            'ket_mobilitas' => $request->ket_mobilitas,
            'tgl_keberangkatan' => $request->tgl_keberangkatan,
            'tgl_selesai' => $request->tgl_keberangkatan,
            'status' => "pengajuan",
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('data_perjadinkegiatans')
            ->where('id', $request->idKegiatan)
            ->update([
                'is_acceptBMN' => 'pengajuan',
                'admin_BMN' => auth('administrator')->user()->id,
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

        return redirect()->route('detail-mobilitas-kegiatan', ['id' => $request->idMobilitas])->with('success', 'Data telah ditambahkan, silahkan isi supir dan kendaraannya!');
    }

    public function storeKeuangan(Request $request) {

        $action = $request->input('action');

        if($action === 'revisi') {
            // Dekode JSON data_tambahan dan masukkan ke dalam properti request
           $dataTambahan = json_decode($request->data_tambahan, true);

           // Masukkan data tambahan ke dalam request
            if (is_array($dataTambahan)) {
                foreach ($dataTambahan as $key => $value) {
                    // Ubah string kosong jadi null
                    $request->merge([$key => $value === '' ? null : $value]);
                }
            }        

            // dd($request);
            $totaldokumen = $request->numDokumen;
            // dd($request);
            for ($i=0; $i < $totaldokumen; $i++) {
                $idDokumen = 'idDokumen_' . $i;
                $statusDokumen = 'statusDokumen_' . $i;
                $keteranganDokumen = 'keteranganDokumen_' . $i;
                DB::table('laporan_perjadinkegiatans')
                ->where('id', $request->$idDokumen)
                ->update([
                    'status' => $request->$statusDokumen,
                    'keterangan' => $request->$keteranganDokumen,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);
            }

            $totalpegawai = $request->numPegawai;
            for ($i=0; $i < $totalpegawai; $i++) {
                $idPegawai = 'idPegawai_' . $i;
                $statusPegawai = 'direvisi';
                DB::table('perangkat_acaras')
                ->where('pegawai_id', $request->$idPegawai)
                ->update([
                    'status' => $statusPegawai,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);
            }

            $totalnonpegawai = $request->numNonPegawai;
            for ($i=0; $i < $totalnonpegawai; $i++) {
                $idnonPegawai = 'idNonPegawai_' . $i;
                $statusnonPegawai = 'direvisi';
                DB::table('perangkat_acaras')
                ->where('non_pegawai_id', $request->$idnonPegawai)
                ->update([
                    'status' => $statusnonPegawai,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);
            }

            $totaloperasional = $request->numOperasional;
            for ($i=0; $i < $totaloperasional; $i++) {
                $idOperasional = 'idOperasional_' . $i;
                $akun = 'akun_' . $i;
                $harga = (int) ($request->input('nominal_' . $i) ?? 0);
                $pajak = (int) ($request->input('pajak_' . $i) ?? 0);
                $total = (int) ($request->input('total_' . $i) ?? 0);
                $statusOperasonal = 'kesesuaian_' . $i;
                DB::table('keuangan_perjadinkegiatans')
                ->where('operasional', $request->$idOperasional)
                ->update([
                    'harga' => $request->$harga,
                    'persen_pajak' => $request->$pajak,
                    'jumlah_harga' => $request->$total,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);
                DB::table('operasionals')
                ->where('id', $request->$idOperasional)
                ->update([
                    'status' => $request->$statusOperasonal,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);
            }
            if ($request->statusKegiatan == 'verifikasi-2') {
                # code...
                DB::table('data_perjadinkegiatans')
                    ->where('id', $request->idKegiatan)
                    ->update([
                        'status' => 'revisi',
                        'status_pengajuan' => 'revisi',
                        'alasan_penolakan' => $request->alasanRevisi,
                        'status_pengajuan_detail' => 'Revisi Laporan',
                        'is_acceptKeu' => 'revisi-2',
                        'admin_Keu' => auth('administrator')->user()->id,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);

                return redirect()->route('keuangan-kegiatan', ['status' => 'revisi-2'])->with('success', 'Program Kegiatan telah anda tolak!');
            }

        } elseif($action === 'tolak') {
            // dd($request);
                $idMobilitas = DB::table('mobilitas_perjadinkegiatans')
                        ->where('data_perjadinkegiatan', $request->idKegiatan)
                        ->select('id')
                        ->first();

                // dd($idMobilitas);

                if($idMobilitas) {
                    DB::table('mobilitas_perjadinkegiatans')
                                ->where('data_perjadinkegiatan', $request->idKegiatan)
                                ->update([
                                    'status' => 'ditolak',
                                    'updated_at' => now()->format('Y-m-d H:i:s'),
                                ]);

                                DB::table('peminjaman_kendaraan_dinas')
                                            ->where('mobilitas_perjadinkegiatan', $idMobilitas->id)
                                            ->update([
                                                'status' => 'ditolak',
                                                'updated_at' => now()->format('Y-m-d H:i:s'),
                                            ]);
                }



                DB::table('perangkat_acaras')
                ->where('data_perjadin_kegiatan', $request->idKegiatan)
                ->update([
                    'status' => 'ditolak',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);


                DB::table('data_perjadinkegiatans')
                    ->where('id', $request->idKegiatan)
                    ->update([
                        'status' => 'ditolak',
                        'status_pengajuan' => 'ditolak',
                        'is_acceptKeu' => 'ditolak',
                        'is_acceptBend' => 'ditolak',
                        'status_pengajuan_detail' => 'Verifikator-2-ditolak',
                        'alasan_penolakan' => $request->alasan_penolakan,
                        'admin_Keu' => auth('administrator')->user()->id,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);

                    return redirect()->route('keuangan-kegiatan', ['status' => 'ditolak'])->with('success', 'Program Kegiatan telah anda tolak!');
           
        } elseif($action === 'verifikasi-2') {

            // dd($request);

            $totaldokumen = $request->numDokumen;
            if ($totaldokumen > 0) {
                for ($i = 0; $i < $totaldokumen; $i++) {
                    $idDokumen = $request->{'idDokumen_' . $i};

                    $dataUpdate = [
                        'status' => $request->{'statusDokumen_' . $i},
                        'keterangan' => $request->{'keteranganDokumen_' . $i},
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ];

                    // Update tabel laporan_perjadinkegiatans jika id dokumen ada
                    DB::table('laporan_perjadinkegiatans')
                        ->where('id', $idDokumen)
                        ->update($dataUpdate);
                }
            }

             // Batch update untuk pegawai
            $totalpegawai = $request->numPegawai;
            if ($totalpegawai > 0) {
                for ($i = 0; $i < $totalpegawai; $i++) {
                    $idNonPegawai = $request->{'idPegawai_' . $i};

                    $dataUpdate = [
                        'status' => 'Disetujui',
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ];

                    // Update tabel perangkat_acaras jika fasilitas_id ada
                    DB::table('perangkat_acaras')
                        ->where('fasilitas_id', $idNonPegawai)
                        ->update($dataUpdate);
                }
            }

            // Batch update untuk non-pegawai
            $totalnonpegawai = $request->numNonPegawai;
            if ($totalnonpegawai > 0) {
                for ($i = 0; $i < $totalnonpegawai; $i++) {
                    $idNonPegawai = $request->{'idNonPegawai_' . $i};

                    $dataUpdate = [
                        'status' => 'Disetujui',
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ];

                    // Update tabel perangkat_acaras jika fasilitas_id ada
                    DB::table('perangkat_acaras')
                        ->where('fasilitas_id', $idNonPegawai)
                        ->update($dataUpdate);
                }
            }

            // --------------------------------------------------

            if($request->numOperasional != 0){

                // Batch update untuk operasional
                $totaloperasional = $request->numOperasional;
                if ($totaloperasional > 0) {
                    for ($i = 0; $i < $totaloperasional; $i++) {
                        $idOperasional = $request->{'idOperasional_' . $i};

                        $dataUpdateKeuangan = [
                            'harga' => $request->{'nominal_' . $i},
                            'persen_pajak' => $request->{'pajak_mobilitas_' . $i},
                            'jumlah_harga' => $request->{'total_mobilitas_' . $i},
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ];

                        $dataUpdateOperasionals = [
                            'status' => $request->{'kesesuaian_' . $i},
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ];

                        // Update tabel keuangan_perjadinkegiatans jika operasional ada
                        DB::table('keuangan_perjadinkegiatans')
                            ->where('operasional', $idOperasional)
                            ->update($dataUpdateKeuangan);

                        // Update tabel operasionals jika id ada
                        DB::table('operasionals')
                            ->where('id', $idOperasional)
                            ->update($dataUpdateOperasionals);
                    }
                }
            }

            // Batch update untuk kebutuhan operasional
                $numkebutuhan = $request->numKebutuhan;
                if ($numkebutuhan > 0) {
                    for ($i = 0; $i < $numkebutuhan; $i++) {
                        $idKebutuhan = $request->{'idKebutuhan_' . $i};
                        $dataUpdateKeuangan = [
                            'harga' => $request->{'nominalKebutuhan_' . $i},
                            'persen_pajak' => $request->{'pajak_fasilitas_' . $i},
                            'nilai_pajak' => $request->{'nominalPajakKebutuhan_' . $i},
                            'jumlah_harga' => $request->{'totalKebutuhan_' . $i},
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ];

                        $dataUpdateKebutuhans = [
                            'status' => $request->{'statusKebutuhan_' . $i},
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ];

                        // Update tabel keuangan_perjadinkegiatans jika kebutuhan_id ada
                        DB::table('keuangan_perjadinkegiatans')
                            ->where('kebutuhan_id', $idKebutuhan)
                            ->update($dataUpdateKeuangan);

                        // Update tabel kebutuhans jika id ada
                        DB::table('kebutuhans')
                            ->where('id', $idKebutuhan)
                            ->update($dataUpdateKebutuhans);
                    }
                }

            DB::table('data_perjadinkegiatans')
                            ->where('id', $request->idKegiatan)
                            ->update([
                                'is_acceptKeu' => 'selesai',
                                'status' => 'selesai',
                                'status_pengajuan' => 'selesai',
                                'status_pengajuan_detail' => 'Approval-2-Bendahara',
                                'is_acceptBend' => 'approval-2',
                                'admin_Keu' => auth('administrator')->user()->id,
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                            ]);
            return redirect()->route('keuangan-kegiatan', ['status' => 'selesai'])->with('success', 'Data telah diperbaharui!');
        }
    }

        public function storeBendahara(Request $request) {

            $action = $request->input('action');
            // dd(count(request()->all()));
            // dd($request);   

            
            if ($action === 'approve-1') {
                $jenisProgram = DB::table('data_perjadinkegiatans')
                    ->where('id', $request->idKegiatan)
                    ->select('jenis_program')
                    ->first();
                $jenisProgram = $jenisProgram->jenis_program;
                // dd($jenisProgram);


                $totalpegawai = $request->numPegawai;
                $cekSupir = 'nama_perangkat_'.$totalpegawai;
                if ($request->$cekSupir == 'Supir') {
                    $totalpegawai = $totalpegawai + 1;
                } else {
                    $totalpegawai = $request->numPegawai;
                }
                // dd($totalpegawai);
                for ($i=0; $i < $totalpegawai; $i++) {
                    $perangkat = 'nama_perangkat_'.$i;

                    // dd($request->$perangkat);
                    if ($request->$perangkat == 'Supir') {
                        // dd($request->$perangkat);
                        $idPerangkatAcara = 'idPerangkatPegawai_' . $i;
                        $akun = 'akunPegawai_' . $i;
                        $satuanSupir = 'satuan_supir_' . $i;
                        $jumlahSupir = 'jumlah_supir_' . $i;
                        DB::table('keuangan_perjadinkegiatans')
                        ->where('perangkat_acara', $request->$idPerangkatAcara)
                        ->whereNull('kebutuhan_id')
                        ->update([
                            'uang_harian' => $request->$satuanSupir,
                            'nominal_perjadin' => $request->$jumlahSupir,
                            'akun_x_rkakl' => $request->$akun,
                            'status' => 'Menunggu Persetujuan Bendahara',
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                    } else {
                        if($jenisProgram == 'Penugasan') {
                            $idPerangkatAcara = 'idPerangkatPegawai_' . $i;
                            $akun = 'akunPegawai_' . $i;
                            $satuanHonorarium = 'satuan_honorarium_' . $i;
                            $jumlahHonorarium = 'jumlah_honorarium_' . $i;
                            DB::table('keuangan_perjadinkegiatans')
                            ->where('perangkat_acara', $request->$idPerangkatAcara)
                            ->where('kode', 'harian')
                            ->update([
                                'uang_harian' => $request->$satuanHonorarium,
                                'nominal_perjadin' => $request->$jumlahHonorarium,
                                'akun_x_rkakl' => $request->$akun,
                                'status' => 'Menunggu Persetujuan Bendahara',
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                            ]);
                        } else {
                            $idPerangkatAcara = 'idPerangkatPegawai_' . $i;
                            $akun = 'akunPegawai_' . $i;
                            $satuanHonorarium = 'satuan_honorarium_' . $i;
                            $jumlahHonorarium = 'jumlah_honorarium_' . $i;
                            DB::table('keuangan_perjadinkegiatans')
                            ->where('perangkat_acara', $request->$idPerangkatAcara)
                            ->where('kode', 'honor')
                            ->update([
                                'honorarium' => $request->$satuanHonorarium,
                                'jumlah_honorarium' => $request->$jumlahHonorarium,
                                'akun_x_rkakl' => $request->$akun,
                                'status' => 'Menunggu Persetujuan Bendahara',
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                            ]);

                        }
                    }

                }

                $nonPegawaiTotal = $request->numNonPegawai;
                for ($j=0; $j < $nonPegawaiTotal; $j++) {
                    if($jenisProgram == 'Penugasan') {
                        $idPerangkatAcaraNonPegawai = 'idPerangkatNonPegawai_' . $j;
                        $akunNonPegawai = 'akunNonPegawai_' . $j;
                        $satuanHonorariumNon = 'satuan_honorarium_non_' . $j;
                        $jumlahHonorariumNon = 'jumlah_honorarium_non_' . $j;
                        DB::table('keuangan_perjadinkegiatans')
                        ->where('perangkat_acara', $request->$idPerangkatAcaraNonPegawai)
                        ->where('kode', 'harian')
                        ->update([
                            'uang_harian' => $request->$satuanHonorariumNon,
                            'nominal_perjadin' => $request->$jumlahHonorariumNon,
                            'akun_x_rkakl' => $request->$akunNonPegawai,
                            'status' => 'Menunggu Persetujuan Bendahara',
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                    } else {
                        $idPerangkatAcaraNonPegawai = 'idPerangkatNonPegawai_' . $j;
                        $akunNonPegawai = 'akunNonPegawai_' . $j;
                        $satuanHonorariumNon = 'satuan_honorarium_non_' . $j;
                        $jumlahHonorariumNon = 'jumlah_honorarium_non_' . $j;
                        DB::table('keuangan_perjadinkegiatans')
                        ->where('perangkat_acara', $request->$idPerangkatAcaraNonPegawai)
                        ->where('kode', 'honor')
                        ->update([
                            'honorarium' => $request->$satuanHonorariumNon,
                            'jumlah_honorarium' => $request->$jumlahHonorariumNon,
                            'akun_x_rkakl' => $request->$akunNonPegawai,
                            'status' => 'Menunggu Persetujuan Bendahara',
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);

                    }
                }

                $numOperasionalBend = $request->numOperasional;
                for ($j=0; $j < $numOperasionalBend; $j++) {
                    $idOperasional = 'idOperasional_' . $j;
                    $akunOperasional = 'akunOperasional_' . $j;
                    $nominalOperasional = 'nominalOperasional_' . $j;
                    $totalOperasional = 'totalOperasional_' . $j;
                    DB::table('keuangan_perjadinkegiatans')
                    ->where('operasional', $request->$idOperasional)
                    ->update([
                        'harga' => $request->$nominalOperasional,
                        'jumlah_harga' => $request->$totalOperasional,
                        'akun_x_rkakl' => $request->$akunOperasional,
                        'status' => 'Menunggu Persetujuan Bendahara',
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
                }
                $totalKebutuhan = $request->numKebutuhan;

                for ($k = 0; $k < $totalKebutuhan; $k++) {
                    $idKebutuhan = 'idKebutuhan_' . $k;
                    $akunKebutuhan = 'akunKebutuhan_' . $k;
                    $nominalKebutuhan = 'nominalKebutuhan_' . $k;
                    $pajakFasilitas = 'pajak_fasilitas_' . $k;
                    $nominalPajak = 'nominalPajakKebutuhan_' . $k;
                    $totalKebutuhanValue = 'totalKebutuhan_' . $k;

                    // Melakukan insert atau update data kebutuhan ke tabel keuangan_perjadinkegiatans
                    DB::table('keuangan_perjadinkegiatans')->updateOrInsert(
                        [
                            'kebutuhan_id' => $request->$idKebutuhan,
                            'data_perjadinkegiatan' => $request->idKegiatan,
                        ],
                        [
                            'harga' => $request->$nominalKebutuhan,
                            'jumlah_harga' => $request->$totalKebutuhanValue,
                            'persen_pajak' => $request->$pajakFasilitas,
                            'nilai_pajak' => $request->$nominalPajak,
                            'akun_x_rkakl' => $request->$akunKebutuhan,
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]
                    );
                }

                DB::table('perangkat_acaras')
                    ->where('data_perjadin_kegiatan', $request->idKegiatan)
                    ->update([
                        'status' => 'Disetujui',
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);

                DB::table('data_perjadinkegiatans')
                    ->where('id', $request->idKegiatan)
                    ->update([
                        'status' => 'proses',
                        'status_pengajuan' => 'proses',
                        'status_pengajuan_detail' => 'Pelaksanaan Kegiatan',
                        'is_acceptKeu' => 'verifikasi-2',
                        'is_acceptBend' => 'approval-2',
                        'admin_Bend' => auth('administrator')->user()->id,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);

                return redirect()->route('bendahara-kegiatan', ['status' => 'approval-1'])->with('success', 'Data telah diperbaharui!, anda telah menyetujui kegiatan.');

            } elseif ($action === 'simpan-draft-apv1') {
                $totalpegawai = $request->numPegawai;
                $cekSupir = 'nama_perangkat_'.$totalpegawai;
                // dd($request->$cekSupir);
                if ($request->$cekSupir == 'Supir') {
                    $totalpegawai = $totalpegawai + 1;
                } else {
                    $totalpegawai = $request->numPegawai;
                }
                // dd($totalpegawai);
                for ($i=0; $i < $totalpegawai; $i++) {
                    $perangkat = 'nama_perangkat_'.$i;

                    // dd($request->$perangkat);
                    if ($request->$perangkat == 'Supir') {
                        // dd($request);
                        $idPerangkatAcara = 'idPerangkatPegawai_' . $i;
                        $akun = 'akunPegawai_' . $i;
                        $satuanSupir = 'satuan_supir_' . $i;
                        $jumlahSupir = 'jumlah_supir_' . $i;
                        DB::table('keuangan_perjadinkegiatans')
                        ->where('perangkat_acara', $request->$idPerangkatAcara)
                        ->update([
                            'uang_harian' => $request->$satuanSupir,
                            'nominal_perjadin' => $request->$jumlahSupir,
                            'akun_x_rkakl' => $request->$akun,
                            'status' => 'Menunggu Persetujuan Bendahara',
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                    } else {
                        $idPerangkatAcara = 'idPerangkatPegawai_' . $i;
                        $akun = 'akunPegawai_' . $i;
                        $satuanHonorarium = 'satuan_honorarium_' . $i;
                        $jumlahHonorarium = 'jumlah_honorarium_' . $i;
                        DB::table('keuangan_perjadinkegiatans')
                        ->where('perangkat_acara', $request->$idPerangkatAcara)
                        ->update([
                            'honorarium' => $request->$satuanHonorarium,
                            'jumlah_honorarium' => $request->$jumlahHonorarium,
                            'akun_x_rkakl' => $request->$akun,
                            'status' => 'Menunggu Persetujuan Bendahara',
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                    }

                }

                $nonPegawaiTotal = $request->numNonPegawai;
                for ($j=0; $j < $nonPegawaiTotal; $j++) {
                    $idPerangkatAcaraNonPegawai = 'idPerangkatNonPegawai_' . $j;
                    $akunNonPegawai = 'akunNonPegawai_' . $j;
                    $satuanHonorariumNon = 'satuan_honorarium_non_' . $j;
                    $jumlahHonorariumNon = 'jumlah_honorarium_non_' . $j;
                    DB::table('keuangan_perjadinkegiatans')
                    ->where('perangkat_acara', $request->$idPerangkatAcaraNonPegawai)
                    ->update([
                        'honorarium' => $request->$satuanHonorariumNon,
                        'jumlah_honorarium' => $request->$jumlahHonorariumNon,
                        'akun_x_rkakl' => $request->$akunNonPegawai,
                        'status' => 'Menunggu Persetujuan Bendahara',
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
                }

                $numOperasionalBend = $request->numOperasional;
                for ($j=0; $j < $numOperasionalBend; $j++) {
                    $idOperasional = 'idOperasional_' . $j;
                    $akunOperasional = 'akunOperasional_' . $j;
                    $nominalOperasional = 'nominalOperasional_' . $j;
                    $totalOperasional = 'totalOperasional_' . $j;
                    DB::table('keuangan_perjadinkegiatans')
                    ->where('operasional', $request->$idOperasional)
                    ->update([
                        'harga' => $request->$nominalOperasional,
                        'jumlah_harga' => $request->$totalOperasional,
                        'akun_x_rkakl' => $request->$akunOperasional,
                        'status' => 'Menunggu Persetujuan Bendahara',
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
                }
                $totalKebutuhan = $request->numKebutuhan;

                for ($k = 0; $k < $totalKebutuhan; $k++) {
                    $idKebutuhan = 'idKebutuhan_' . $k;
                    $akunKebutuhan = 'akunKebutuhan_' . $k;
                    $nominalKebutuhan = 'nominalKebutuhan_' . $k;
                    $pajakFasilitas = 'pajak_fasilitas_' . $k;
                    $nominalPajak = 'nominalPajakKebutuhan_' . $k;
                    $totalKebutuhanValue = 'totalKebutuhan_' . $k;

                    // Melakukan insert atau update data kebutuhan ke tabel keuangan_perjadinkegiatans
                    DB::table('keuangan_perjadinkegiatans')->updateOrInsert(
                        [
                            'kebutuhan_id' => $request->$idKebutuhan,
                            'data_perjadinkegiatan' => $request->idKegiatan,
                        ],
                        [
                            'harga' => $request->$nominalKebutuhan,
                            'jumlah_harga' => $request->$totalKebutuhanValue,
                            'persen_pajak' => $request->$pajakFasilitas,
                            'nilai_pajak' => $request->$nominalPajak,
                            'akun_x_rkakl' => $request->$akunKebutuhan,
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]
                    );
                }


                return redirect()->route('bendahara-kegiatan', ['status' => 'approval-1'])->with('success', 'Data telah disimpan!');

            } elseif ($action === 'selesai-tanpa-bayar') {
                DB::table('data_perjadinkegiatans')
                    ->where('id', $request->idKegiatan)
                    ->update([
                        'status' => 'selesai',
                        'status_pengajuan' => 'selesai',
                        'status_pengajuan_detail' => 'Pelaksanaan Kegiatan',
                        'is_acceptKeu' => 'selesai',
                        'is_acceptBend' => 'selesai',
                        'status_pengajuan_detail' => 'Selesai Non Bayar',
                        'admin_Bend' => auth('administrator')->user()->id,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

                DB::table('perangkat_acaras')
                    ->where('data_perjadin_kegiatan', $request->idKegiatan)
                    ->update([
                        'status' => 'Selesai',
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

                // Ambil semua ID dari mobilitas_perjadinkegiatans yang sesuai
                $ids = DB::table('mobilitas_perjadinkegiatans')
                    ->where('data_perjadinkegiatan', $request->idKegiatan)
                    ->pluck('id'); // Mengambil kolom 'id' sebagai array

                // Perbarui semua data terkait secara langsung
                DB::table('peminjaman_kendaraan_dinas')
                    ->whereIn('mobilitas_perjadinkegiatan', $ids) // Menggunakan whereIn untuk banyak ID
                    ->update([
                        'status' => 'Selesai',
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

                return redirect()->route('bendahara-kegiatan', ['status' => 'selesai'])->with('success', 'Data telah diperbaharui!, Kegiatan sudah disetujui untuk selesai.');
            } elseif($action === 'kembali_verifikator') {
                DB::table('data_perjadinkegiatans')
                ->where('id', $request->idKegiatan)
                ->update([
                    'status' => 'selesai',
                    'status_pengajuan' => 'selesai',
                    'status_pengajuan_detail' => 'Verifikasi-Ulang-Keuangan',
                    'is_acceptKeu' => 'verifikasi-2',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);
                return redirect()->route('bendahara-kegiatan', ['status' => 'approval-2'])->with('success', 'Data telah dikembalikan ke Verifikator!');
           

            } elseif($action === 'approve-2') {

                // dd($request);
                // Panggil fungsi isiPenggunaan
                $adminOtherController = new AdminOtherController();

                $totalpegawai = $request->numPegawai;
                for ($i=0; $i < $totalpegawai; $i++) {
                    $perangkat = 'nama_perangkat_'.$i;
                    // dd($perangkat);

                    if ($request->$perangkat == 'Panitia') {
                        // MAIN PEGAWAI
                        $idPerangkatPegawai = 'idPerangkatPegawai_'.$i;
                        $tglbayarPegawai = 'tglbayarPegawai_'.$i;
                        $statusPembiyaanPegawai = 'statusPembiyaanPegawai_'.$i;

                        // HONOR PEGAWAI
                        $idKeuanganHonorPegawai = 'idKeuanganHonorPegawai_'.$i;
                        $akunPegawai = 'akunPegawai_'.$i;
                        $satuan_honorarium = 'satuan_honorarium_'.$i;
                        $jumlah_honorarium = 'jumlah_honorarium_'.$i;
                        $pph = 'pph_'.$i;
                        $nilai_pph = 'nilai_pph_'.$i;
                        $nominal_honorarium = 'nominal_honorarium_'.$i;

                        // HARIAN PEGAWAI
                        $idKeuanganHarianPegawai = 'idKeuanganHarianPegawai_'.$i;
                        $akunHarianPegawai = 'akunHarianPegawai_'.$i;
                        $harianPegawai = 'harianPegawai_'.$i;
                        $hariandayPegawai = 'hariandayPegawai_'.$i;
                        $representasiPegawai = 'representasiPegawai_'.$i;
                        $nominalPerjadinPegawai = 'nominalPerjadinPegawai_'.$i;

                        DB::table('keuangan_perjadinkegiatans')
                            ->where('id', $request->$idKeuanganHonorPegawai)
                            ->update([
                                'akun_x_rkakl' => $request->$akunPegawai,
                                'honorarium' => $request->$satuan_honorarium,
                                'jumlah_honorarium' => $request->$jumlah_honorarium,
                                'persen_pajak' => $request->$pph,
                                'nilai_pajak' => $request->$nilai_pph,
                                'nominal_honorarium' => $request->$nominal_honorarium,
                                'tgl_bayar' => $request->$tglbayarPegawai,
                                'status' => $request->$statusPembiyaanPegawai,
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);

                        DB::table('keuangan_perjadinkegiatans')
                            ->where('id', $request->$idKeuanganHarianPegawai)
                            ->update([
                                'akun_x_rkakl' => $request->$akunHarianPegawai,
                                'uang_harian' => $request->$harianPegawai,
                                'uang_harian_fullday' => $request->$hariandayPegawai,
                                'uang_representasi' => $request->$representasiPegawai,
                                'nominal_perjadin' => $request->$nominalPerjadinPegawai,
                                'tgl_bayar' => $request->$tglbayarPegawai,
                                'status' => $request->$statusPembiyaanPegawai,
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                    } elseif($request->$perangkat == 'Supir') {
                        // MAIN SUPIR
                        $idPerangkatPegawai = 'idPerangkatPegawai_'.$i;
                        $akunHarianPegawai = 'akunHarianPegawai_'.$i;
                        $harianPegawai = 'harianPegawai_'.$i;
                        $hariandayPegawai = 'hariandayPegawai_'.$i;
                        $representasiPegawai = 'representasiPegawai_'.$i;
                        $totalPegawai = 'totalPegawai_'.$i;
                        $tglbayarPegawai = 'tglbayarPegawai_'.$i;
                        $statusPembiyaanPegawai = 'statusPembiyaanPegawai_'.$i;
                        DB::table('keuangan_perjadinkegiatans')
                            ->where('perangkat_acara', $request->$idPerangkatPegawai)
                            ->whereNull('kebutuhan_id') 
                            ->update([
                                'akun_x_rkakl' => $request->$akunHarianPegawai,
                                'uang_harian' => $request->$harianPegawai,
                                'uang_harian_fullday' => $request->$hariandayPegawai,
                                'uang_representasi' => $request->$representasiPegawai,
                                'nominal_perjadin' => $request->$totalPegawai,
                                'tgl_bayar' => $request->$tglbayarPegawai,
                                'status' => $request->$statusPembiyaanPegawai,
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                    } else {
                        // MAIN PEGAWAI
                        $idPerangkatPegawai = 'idPerangkatPegawai_'.$i;
                        $tglbayarPegawai = 'tglbayarPegawai_'.$i;
                        $statusPembiyaanPegawai = 'statusPembiyaanPegawai_'.$i;

                        // HONOR PEGAWAI
                        $idKeuanganHonorPegawai = 'idKeuanganHonorPegawai_'.$i;
                        $akunPegawai = 'akunPegawai_'.$i;
                        $satuan_honorarium = 'satuan_honorarium_'.$i;
                        $jumlah_honorarium = 'jumlah_honorarium_'.$i;
                        $pph = 'pph_'.$i;
                        $nilai_pph = 'nilai_pph_'.$i;
                        $nominal_honorarium = 'nominal_honorarium_'.$i;
                        DB::table('keuangan_perjadinkegiatans')
                            ->where('id', $request->$idKeuanganHonorPegawai)
                            ->where('perangkat_acara', $request->$idPerangkatPegawai)
                            ->update([
                                'akun_x_rkakl' => $request->$akunPegawai,
                                'honorarium' => $request->$satuan_honorarium,
                                'jumlah_honorarium' => $request->$jumlah_honorarium,
                                'persen_pajak' => $request->$pph,
                                'nilai_pajak' => $request->$nilai_pph,
                                'nominal_honorarium' => $request->$nominal_honorarium,
                                'tgl_bayar' => $request->$tglbayarPegawai,
                                'status' => $request->$statusPembiyaanPegawai,
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                    }
                }

                $nonPegawaiTotal = $request->numNonPegawai;
                for ($j=0; $j < $nonPegawaiTotal; $j++) {
                    $perangkat = 'nama_perangkat_non_'.$j;
                    // dd($request->$perangkat);

                    if ($request->$perangkat == 'Panitia') {
                        // MAIN NON PEGAWAI
                        $idPerangkatNonPegawai = 'idPerangkatNonPegawai_'.$j;
                        $tglbayarNonPegawai = 'tglbayarNonPegawai_'.$j;
                        $statusPembayaranNonPegawai = 'statusPembayaranNonPegawai_'.$j;

                        // HONOR NON PEGAWAI
                        $idKeuanganHonorNonPegawai = 'idKeuanganHonorNonPegawai_'.$j;
                        $akunNonPegawai = 'akunNonPegawai_'.$j;
                        $satuan_honorarium_non = 'satuan_honorarium_non_'.$j;
                        $jumlah_honorarium_non = 'jumlah_honorarium_non_'.$j;
                        $pph_non = 'pph_non_'.$j;
                        $nilai_pph_non = 'nilai_pph_non_'.$j;
                        $nominal_honorarium_non = 'nominal_honorarium_non_'.$j;
                        DB::table('keuangan_perjadinkegiatans')
                            ->where('id', $request->$idKeuanganHonorNonPegawai)
                            ->where('perangkat_acara', $request->$idPerangkatNonPegawai)
                            ->update([
                                'akun_x_rkakl' => $request->$akunNonPegawai,
                                'honorarium' => $request->$satuan_honorarium_non,
                                'jumlah_honorarium' => $request->$jumlah_honorarium_non,
                                'persen_pajak' => $request->$pph_non,
                                'nilai_pajak' => $request->$nilai_pph_non,
                                'nominal_honorarium' => $request->$nominal_honorarium_non,
                                'tgl_bayar' => $request->$tglbayarNonPegawai,
                                'status' => $request->$statusPembayaranNonPegawai,
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);

                        // HARIAN NON PEGAWAI
                        $idKeuanganHarianNonPegawai = 'idKeuanganHarianNonPegawai_'.$j;
                        $akunHarianNonPegawai = 'akunHarianNonPegawai_'.$j;
                        $harianNonPegawai = 'harianNonPegawai_'.$j;
                        $hariandayNonPegawai = 'hariandayNonPegawai_'.$j;
                        $representasiNonPegawai = 'representasiNonPegawai_'.$j;
                        $nominalPerjadinNonPegawai = 'nominalPerjadinNonPegawai_'.$j;
                        DB::table('keuangan_perjadinkegiatans')
                            ->where('id', $request->$idKeuanganHarianNonPegawai)
                            ->where('perangkat_acara', $request->$idPerangkatNonPegawai)
                            ->update([
                                'akun_x_rkakl' => $request->$akunHarianNonPegawai,
                                'uang_harian' => $request->$harianNonPegawai,
                                'uang_harian_fullday' => $request->$hariandayNonPegawai,
                                'uang_representasi' => $request->$representasiNonPegawai,
                                'nominal_perjadin' => $request->$nominalPerjadinNonPegawai,
                                'tgl_bayar' => $request->$tglbayarNonPegawai,
                                'status' => $request->$statusPembayaranNonPegawai,
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                    } else {
                        // MAIN NON PEGAWAI
                        $idPerangkatNonPegawai = 'idPerangkatNonPegawai_'.$j;
                        $tglbayarNonPegawai = 'tglbayarNonPegawai_'.$j;
                        $statusPembayaranNonPegawai = 'statusPembayaranNonPegawai_'.$j;

                        // HONOR NON PEGAWAI
                        $idKeuanganHonorNonPegawai = 'idKeuanganHonorNonPegawai_'.$j;
                        $akunNonPegawai = 'akunNonPegawai_'.$j;
                        $satuan_honorarium_non = 'satuan_honorarium_non_'.$j;
                        $jumlah_honorarium_non = 'jumlah_honorarium_non_'.$j;
                        $pph_non = 'pph_non_'.$j;
                        $nilai_pph_non = 'nilai_pph_non_'.$j;
                        $nominal_honorarium_non = 'nominal_honorarium_'.$j;
                        DB::table('keuangan_perjadinkegiatans')
                            ->where('id', $request->$idKeuanganHonorNonPegawai)
                            ->where('perangkat_acara', $request->$idPerangkatNonPegawai)
                            ->update([
                                'akun_x_rkakl' => $request->$akunNonPegawai,
                                'honorarium' => $request->$satuan_honorarium_non,
                                'jumlah_honorarium' => $request->$jumlah_honorarium_non,
                                'persen_pajak' => $request->$pph_non,
                                'nilai_pajak' => $request->$nilai_pph_non,
                                'nominal_honorarium' => $request->$nominal_honorarium_non,
                                'tgl_bayar' => $request->$tglbayarNonPegawai,
                                'status' => $request->$statusPembayaranNonPegawai,
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                    }
                }

                $numOperasionalBend = $request->numOperasional;
                for ($k=0; $k < $numOperasionalBend; $k++) {
                    $idOperasional = 'idOperasional_' . $k;
                    $akunOperasional = 'akunOperasional_' . $k;
                    // $sbmOperasional = 'sbmOperasional_' . $k;
                    $nominalOperasional = 'nominalOperasional_' . $k;
                    $pajakOperasional = 'pajakOperasional_' . $k;
                    $totalOperasional = 'totalOperasional_' . $k;
                    $totalOperasionaltglbayar = 'tglbayarOperasional_' . $k;
                    $statusOperasional = 'kesesuaianOperasional_' . $k;
                    DB::table('keuangan_perjadinkegiatans')
                    ->where('operasional', $request->$idOperasional)
                    ->update([
                        'harga' => $request->$nominalOperasional,
                        'persen_pajak' => $request->$pajakOperasional,
                        'tgl_bayar' => $request->$totalOperasionaltglbayar,
                        'jumlah_harga' => $request->$totalOperasional,
                        // 'ref_sbm' => $request->$sbmOperasional,
                        'akun_x_rkakl' => $request->$akunOperasional,
                        'status' => $request->$statusOperasional,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
                }

                $totalKebutuhan = $request->numKebutuhan;
                for ($l = 0; $l < $totalKebutuhan; $l++) {
                    $idKebutuhan = 'idKebutuhan_' . $l;
                    $akunKebutuhan = 'akunKebutuhan_' . $l;
                    $nominalKebutuhan = 'nominalKebutuhan_' . $l;
                    $pajakFasilitas = 'pajak_fasilitas_' . $l;
                    $nominalPajak = 'nominalPajakKebutuhan_' . $l;
                    $totalKebutuhanValue = 'totalKebutuhan_' . $l;

                    // Melakukan insert atau update data kebutuhan ke tabel keuangan_perjadinkegiatans
                    DB::table('keuangan_perjadinkegiatans')->updateOrInsert(
                        [
                            'kebutuhan_id' => $request->$idKebutuhan,
                            'data_perjadinkegiatan' => $request->idKegiatan,
                        ],
                        [
                            'harga' => $request->$nominalKebutuhan,
                            'jumlah_harga' => $request->$totalKebutuhanValue,
                            'persen_pajak' => $request->$pajakFasilitas,
                            'nilai_pajak' => $request->$nominalPajak,
                            'akun_x_rkakl' => $request->$akunKebutuhan,
                            'status' => 'Sudah Dibayarkan',
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]
                    );
                }

                $isIsiPenggunaanBerhasil = $adminOtherController->isiPenggunaan();

                if ($isIsiPenggunaanBerhasil) {
                    DB::table('data_perjadinkegiatans')
                        ->where('id', $request->idKegiatan)
                        ->update([
                            'status' => 'selesai',
                            'is_acceptKeu' => 'selesai',
                            'is_acceptBend' => 'selesai',
                            'status_pengajuan' => 'selesai',
                            'status_pengajuan_detail' => 'Selesai Dibayarkan',
                            'admin_Bend' => auth('administrator')->user()->id,
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                    DB::table('perangkat_acaras')
                        ->where('data_perjadin_kegiatan', $request->idKegiatan)
                        ->update([
                            'status' => 'Selesai',
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);

                    // Ambil semua ID dari mobilitas_perjadinkegiatans yang sesuai
                    $ids = DB::table('mobilitas_perjadinkegiatans')
                        ->where('data_perjadinkegiatan', $request->idKegiatan)
                        ->pluck('id'); // Mengambil kolom 'id' sebagai array

                    // Perbarui semua data terkait secara langsung
                    DB::table('peminjaman_kendaraan_dinas')
                        ->whereIn('mobilitas_perjadinkegiatan', $ids) // Menggunakan whereIn untuk banyak ID
                        ->update([
                            'status' => 'Selesai',
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);

                    return redirect()->route('bendahara-kegiatan', ['status' => 'selesai'])->with('success', 'Data telah diperbaharui!, Perhitungan Keuangan telah diperbarui.');
                } else {
                    return redirect()->route('bendahara-kegiatan', ['status' => 'approval-2'])->with('error', 'Gagal Memperbarui Data. Silakan Coba Lagi!');
                }




            } elseif($action === 'simpan-draft-apv2') {

                // dd($request);
                // Panggil fungsi isiPenggunaan
                $adminOtherController = new AdminOtherController();

                $totalpegawai = $request->numPegawai;
                for ($i=0; $i < $totalpegawai; $i++) {
                    $perangkat = 'nama_perangkat_'.$i;
                    // dd($perangkat);

                    if ($request->$perangkat == 'Panitia') {
                        // MAIN PEGAWAI
                        $idPerangkatPegawai = 'idPerangkatPegawai_'.$i;
                        $tglbayarPegawai = 'tglbayarPegawai_'.$i;
                        $statusPembiyaanPegawai = 'statusPembiyaanPegawai_'.$i;

                        // HONOR PEGAWAI
                        $idKeuanganHonorPegawai = 'idKeuanganHonorPegawai_'.$i;
                        $akunPegawai = 'akunPegawai_'.$i;
                        $satuan_honorarium = 'satuan_honorarium_'.$i;
                        $jumlah_honorarium = 'jumlah_honorarium_'.$i;
                        $pph = 'pph_'.$i;
                        $nilai_pph = 'nilai_pph_'.$i;
                        $nominal_honorarium = 'nominal_honorarium_'.$i;

                        // HARIAN PEGAWAI
                        $idKeuanganHarianPegawai = 'idKeuanganHarianPegawai_'.$i;
                        $akunHarianPegawai = 'akunHarianPegawai_'.$i;
                        $harianPegawai = 'harianPegawai_'.$i;
                        $hariandayPegawai = 'hariandayPegawai_'.$i;
                        $representasiPegawai = 'representasiPegawai_'.$i;
                        $nominalPerjadinPegawai = 'nominalPerjadinPegawai_'.$i;

                        DB::table('keuangan_perjadinkegiatans')
                            ->where('id', $request->$idKeuanganHonorPegawai)
                            ->update([
                                'akun_x_rkakl' => $request->$akunPegawai,
                                'honorarium' => $request->$satuan_honorarium,
                                'jumlah_honorarium' => $request->$jumlah_honorarium,
                                'persen_pajak' => $request->$pph,
                                'nilai_pajak' => $request->$nilai_pph,
                                'nominal_honorarium' => $request->$nominal_honorarium,
                                'tgl_bayar' => $request->$tglbayarPegawai,
                                'status' => $request->$statusPembiyaanPegawai,
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);

                        DB::table('keuangan_perjadinkegiatans')
                            ->where('id', $request->$idKeuanganHarianPegawai)
                            ->update([
                                'akun_x_rkakl' => $request->$akunHarianPegawai,
                                'uang_harian' => $request->$harianPegawai,
                                'uang_harian_fullday' => $request->$hariandayPegawai,
                                'uang_representasi' => $request->$representasiPegawai,
                                'nominal_perjadin' => $request->$nominalPerjadinPegawai,
                                'tgl_bayar' => $request->$tglbayarPegawai,
                                'status' => $request->$statusPembiyaanPegawai,
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                    } elseif($request->$perangkat == 'Supir') {
                        // MAIN SUPIR
                        $idPerangkatPegawai = 'idPerangkatPegawai_'.$i;
                        $akunHarianPegawai = 'akunHarianPegawai_'.$i;
                        $harianPegawai = 'harianPegawai_'.$i;
                        $hariandayPegawai = 'hariandayPegawai_'.$i;
                        $representasiPegawai = 'representasiPegawai_'.$i;
                        $totalPegawai = 'totalPegawai_'.$i;
                        $tglbayarPegawai = 'tglbayarPegawai_'.$i;
                        $statusPembiyaanPegawai = 'statusPembiyaanPegawai_'.$i;
                        DB::table('keuangan_perjadinkegiatans')
                            ->where('perangkat_acara', $request->$idPerangkatPegawai)
                            ->update([
                                'akun_x_rkakl' => $request->$akunHarianPegawai,
                                'uang_harian' => $request->$harianPegawai,
                                'uang_harian_fullday' => $request->$hariandayPegawai,
                                'uang_representasi' => $request->$representasiPegawai,
                                'nominal_perjadin' => $request->$totalPegawai,
                                'tgl_bayar' => $request->$tglbayarPegawai,
                                'status' => $request->$statusPembiyaanPegawai,
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                    } else {
                        // MAIN PEGAWAI
                        $idPerangkatPegawai = 'idPerangkatPegawai_'.$i;
                        $tglbayarPegawai = 'tglbayarPegawai_'.$i;
                        $statusPembiyaanPegawai = 'statusPembiyaanPegawai_'.$i;

                        // HONOR PEGAWAI
                        $idKeuanganHonorPegawai = 'idKeuanganHonorPegawai_'.$i;
                        $akunPegawai = 'akunPegawai_'.$i;
                        $satuan_honorarium = 'satuan_honorarium_'.$i;
                        $jumlah_honorarium = 'jumlah_honorarium_'.$i;
                        $pph = 'pph_'.$i;
                        $nilai_pph = 'nilai_pph_'.$i;
                        $nominal_honorarium = 'nominal_honorarium_'.$i;
                        DB::table('keuangan_perjadinkegiatans')
                            ->where('id', $request->$idKeuanganHonorPegawai)
                            ->where('perangkat_acara', $request->$idPerangkatPegawai)
                            ->update([
                                'akun_x_rkakl' => $request->$akunPegawai,
                                'honorarium' => $request->$satuan_honorarium,
                                'jumlah_honorarium' => $request->$jumlah_honorarium,
                                'persen_pajak' => $request->$pph,
                                'nilai_pajak' => $request->$nilai_pph,
                                'nominal_honorarium' => $request->$nominal_honorarium,
                                'tgl_bayar' => $request->$tglbayarPegawai,
                                'status' => $request->$statusPembiyaanPegawai,
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                    }
                }

                $nonPegawaiTotal = $request->numNonPegawai;
                for ($j=0; $j < $nonPegawaiTotal; $j++) {
                    $perangkat = 'nama_perangkat_non_'.$j;
                    // dd($request->$perangkat);

                    if ($request->$perangkat == 'Panitia') {
                        // MAIN NON PEGAWAI
                        $idPerangkatNonPegawai = 'idPerangkatNonPegawai_'.$j;
                        $tglbayarNonPegawai = 'tglbayarNonPegawai_'.$j;
                        $statusPembayaranNonPegawai = 'statusPembayaranNonPegawai_'.$j;

                        // HONOR NON PEGAWAI
                        $idKeuanganHonorNonPegawai = 'idKeuanganHonorNonPegawai_'.$j;
                        $akunNonPegawai = 'akunNonPegawai_'.$j;
                        $satuan_honorarium_non = 'satuan_honorarium_non_'.$j;
                        $jumlah_honorarium_non = 'jumlah_honorarium_non_'.$j;
                        $pph_non = 'pph_non_'.$j;
                        $nilai_pph_non = 'nilai_pph_non_'.$j;
                        $nominal_honorarium_non = 'nominal_honorarium_non_'.$j;
                        DB::table('keuangan_perjadinkegiatans')
                            ->where('id', $request->$idKeuanganHonorNonPegawai)
                            ->where('perangkat_acara', $request->$idPerangkatNonPegawai)
                            ->update([
                                'akun_x_rkakl' => $request->$akunNonPegawai,
                                'honorarium' => $request->$satuan_honorarium_non,
                                'jumlah_honorarium' => $request->$jumlah_honorarium_non,
                                'persen_pajak' => $request->$pph_non,
                                'nilai_pajak' => $request->$nilai_pph_non,
                                'nominal_honorarium' => $request->$nominal_honorarium_non,
                                'tgl_bayar' => $request->$tglbayarNonPegawai,
                                'status' => $request->$statusPembayaranNonPegawai,
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);

                        // HARIAN NON PEGAWAI
                        $idKeuanganHarianNonPegawai = 'idKeuanganHarianNonPegawai_'.$j;
                        $akunHarianNonPegawai = 'akunHarianNonPegawai_'.$j;
                        $harianNonPegawai = 'harianNonPegawai_'.$j;
                        $hariandayNonPegawai = 'hariandayNonPegawai_'.$j;
                        $representasiNonPegawai = 'representasiNonPegawai_'.$j;
                        $nominalPerjadinNonPegawai = 'nominalPerjadinNonPegawai_'.$j;
                        DB::table('keuangan_perjadinkegiatans')
                            ->where('id', $request->$idKeuanganHarianNonPegawai)
                            ->where('perangkat_acara', $request->$idPerangkatNonPegawai)
                            ->update([
                                'akun_x_rkakl' => $request->$akunHarianNonPegawai,
                                'uang_harian' => $request->$harianNonPegawai,
                                'uang_harian_fullday' => $request->$hariandayNonPegawai,
                                'uang_representasi' => $request->$representasiNonPegawai,
                                'nominal_perjadin' => $request->$nominalPerjadinNonPegawai,
                                'tgl_bayar' => $request->$tglbayarNonPegawai,
                                'status' => $request->$statusPembayaranNonPegawai,
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                    } else {
                        // MAIN NON PEGAWAI
                        $idPerangkatNonPegawai = 'idPerangkatNonPegawai_'.$j;
                        $tglbayarNonPegawai = 'tglbayarNonPegawai_'.$j;
                        $statusPembayaranNonPegawai = 'statusPembayaranNonPegawai_'.$j;

                        // HONOR NON PEGAWAI
                        $idKeuanganHonorNonPegawai = 'idKeuanganHonorNonPegawai_'.$j;
                        $akunNonPegawai = 'akunNonPegawai_'.$j;
                        $satuan_honorarium_non = 'satuan_honorarium_non_'.$j;
                        $jumlah_honorarium_non = 'jumlah_honorarium_non_'.$j;
                        $pph_non = 'pph_non_'.$j;
                        $nilai_pph_non = 'nilai_pph_non_'.$j;
                        $nominal_honorarium_non = 'nominal_honorarium_'.$j;
                        DB::table('keuangan_perjadinkegiatans')
                            ->where('id', $request->$idKeuanganHonorNonPegawai)
                            ->where('perangkat_acara', $request->$idPerangkatNonPegawai)
                            ->update([
                                'akun_x_rkakl' => $request->$akunNonPegawai,
                                'honorarium' => $request->$satuan_honorarium_non,
                                'jumlah_honorarium' => $request->$jumlah_honorarium_non,
                                'persen_pajak' => $request->$pph_non,
                                'nilai_pajak' => $request->$nilai_pph_non,
                                'nominal_honorarium' => $request->$nominal_honorarium_non,
                                'tgl_bayar' => $request->$tglbayarNonPegawai,
                                'status' => $request->$statusPembayaranNonPegawai,
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                    }
                }

                $numOperasionalBend = $request->numOperasional;
                for ($k=0; $k < $numOperasionalBend; $k++) {
                    $idOperasional = 'idOperasional_' . $k;
                    $akunOperasional = 'akunOperasional_' . $k;
                    // $sbmOperasional = 'sbmOperasional_' . $k;
                    $nominalOperasional = 'nominalOperasional_' . $k;
                    $pajakOperasional = 'pajakOperasional_' . $k;
                    $totalOperasional = 'totalOperasional_' . $k;
                    $totalOperasionaltglbayar = 'tglbayarOperasional_' . $k;
                    $statusOperasional = 'kesesuaianOperasional_' . $k;
                    DB::table('keuangan_perjadinkegiatans')
                    ->where('operasional', $request->$idOperasional)
                    ->update([
                        'harga' => $request->$nominalOperasional,
                        'persen_pajak' => $request->$pajakOperasional,
                        'tgl_bayar' => $request->$totalOperasionaltglbayar,
                        'jumlah_harga' => $request->$totalOperasional,
                        // 'ref_sbm' => $request->$sbmOperasional,
                        'akun_x_rkakl' => $request->$akunOperasional,
                        'status' => $request->$statusOperasional,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
                }

                $totalKebutuhan = $request->numKebutuhan;
                for ($l = 0; $l < $totalKebutuhan; $l++) {
                    $idKebutuhan = 'idKebutuhan_' . $l;
                    $akunKebutuhan = 'akunKebutuhan_' . $l;
                    $nominalKebutuhan = 'nominalKebutuhan_' . $l;
                    $pajakFasilitas = 'pajak_fasilitas_' . $l;
                    $nominalPajak = 'nominalPajakKebutuhan_' . $l;
                    $totalKebutuhanValue = 'totalKebutuhan_' . $l;

                    // Melakukan insert atau update data kebutuhan ke tabel keuangan_perjadinkegiatans
                    DB::table('keuangan_perjadinkegiatans')->updateOrInsert(
                        [
                            'kebutuhan_id' => $request->$idKebutuhan,
                            'data_perjadinkegiatan' => $request->idKegiatan,
                        ],
                        [
                            'harga' => $request->$nominalKebutuhan,
                            'jumlah_harga' => $request->$totalKebutuhanValue,
                            'persen_pajak' => $request->$pajakFasilitas,
                            'nilai_pajak' => $request->$nominalPajak,
                            'akun_x_rkakl' => $request->$akunKebutuhan,
                            'status' => 'Sudah Dibayarkan',
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]
                    );
                }

                    return redirect()->route('bendahara-kegiatan', ['status' => 'approval-2'])->with('error', 'Data Draft Berhasil Disimpan. Silakan Coba Lagi!');
            } elseif($action === 'revisi') {
                DB::table('data_perjadinkegiatans')
                    ->where('id', $request->idKegiatan)
                    ->update([
                        'status' => 'revisi',
                        'status_pengajuan' => 'revisi',
                        'is_acceptKeu' => 'revisi',
                        'is_acceptBend' => 'revisi',
                        'admin_Bend' => auth('administrator')->user()->id,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);

                return redirect()->route('bendahara-kegiatan', ['status' => 'revisi'])->with('success', 'Data telah diperbaharui!, Konfirmasi ulang kebagian keuangan!');
            } elseif($action === 'tolak') {

                // dd($request);
                $idMobilitas = DB::table('mobilitas_perjadinkegiatans')
                        ->where('data_perjadinkegiatan', $request->idKegiatan)
                        ->select('id')
                        ->first();

                // dd($idMobilitas);

                if($idMobilitas) {
                    DB::table('mobilitas_perjadinkegiatans')
                                ->where('data_perjadinkegiatan', $request->idKegiatan)
                                ->update([
                                    'status' => 'ditolak',
                                    'updated_at' => now()->format('Y-m-d H:i:s'),
                                ]);

                                DB::table('peminjaman_kendaraan_dinas')
                                            ->where('mobilitas_perjadinkegiatan', $idMobilitas->id)
                                            ->update([
                                                'status' => 'ditolak',
                                                'updated_at' => now()->format('Y-m-d H:i:s'),
                                            ]);
                }



                DB::table('perangkat_acaras')
                ->where('data_perjadin_kegiatan', $request->idKegiatan)
                ->update([
                    'status' => 'ditolak',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);


                DB::table('data_perjadinkegiatans')
                    ->where('id', $request->idKegiatan)
                    ->update([
                        'status' => 'ditolak',
                        'status_pengajuan' => 'ditolak',
                        'is_acceptKeu' => 'ditolak',
                        'is_acceptBend' => 'ditolak',
                        'status_pengajuan_detail' => 'Aproval-1-ditolak',
                        'alasan_penolakan' => $request->alasan_penolakan,
                        'admin_Bend' => auth('administrator')->user()->id,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);

                return redirect()->route('bendahara-kegiatan', ['status' => 'ditolak'])->with('success', 'Data telah diperbaharui!, Kegiatan telah anda ditolak!');
            } else {
                return redirect()->route('bendahara-kegiatan', ['status' => 'approval-1'])->with('error', 'Data gagal diperbaharui!!');
            }

        }

        public function destroyFasilitasKegiatan(Request $request, $id, $admin)
    {

        $kegiatanId = $request->kegiatanId;

        // dd($request);

        $kegiatanData =  DB::table('data_perjadinkegiatans')
            ->where('id', $kegiatanId)
            ->first();

        $isPenugasan = $kegiatanData->jenis_program == 'Penugasan';
        if ($admin == 'bendahara' || $admin == 'keuangan') {
            if (!$isPenugasan) {
                DB::table('keuangan_perjadinkegiatans')
                    ->where('data_perjadinkegiatan', $kegiatanId)
                    ->where('kebutuhan_id', $id)
                    ->delete();

                Kebutuhan::destroy($id);

                if ($admin == 'keuangan'){
                    return redirect()->route('detail_keuangan', ['id' => $kegiatanId])
                        ->with('success', 'Data fasilitas berhasil dihapus!');
                    } else if ($admin == 'bendahara') {
                    return redirect()->route('detail_bendahara', ['id' => $kegiatanId])
                        ->with('success', 'Data fasilitas berhasil dihapus!');
                }
            } else {
                $perangkatAcara = $request->perangkat_acara;

                DB::table('keuangan_perjadinkegiatans')
                    ->where('data_perjadinkegiatan', $kegiatanId)
                    ->where('kebutuhan_id', $id)
                    ->delete();

                Kebutuhan::destroy($id);

                if ($admin == 'keuangan'){
                    return redirect()->route('detail_keuangan', ['id' => $kegiatanId])
                        ->with('success', 'Data fasilitas berhasil dihapus!');
                    } else if ($admin == 'bendahara') {
                    return redirect()->route('detail_bendahara', ['id' => $kegiatanId])
                        ->with('success', 'Data fasilitas berhasil dihapus!');
                }
            }
        } else {
            return redirect()->route('dashboard')
                    ->with('error', 'Anda Tidak Memiliki akses!');
        }
    }

    public function AdmingetDokumen($filename)
    {
        $path = storage_path('app/public/dokumen-kegiatans/' . $filename);
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->file($path);
    }

    public function updateSapras(Request $request)
    {
        $total = $request->total;
        for ($i=0; $i < $total; $i++) {
            $idPeminjaman = 'idPeminjaman_' . $i;
            $idBarang = 'idBarang_' . $i;
            $status = 'status_' . $i;
            DB::table('peminjaman_sarpras')
                ->where('id', $request->$idPeminjaman)
                ->update([
                    'status' => $request->$status,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);
            if ($request->$status == 'digunakan') {
                DB::table('assets')
                ->where('id', $request->$idBarang)
                ->update([
                    'status_peminjaman' => 'digunakan',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);
            }

            if ($request->$status == 'selesai') {
                DB::table('assets')
                ->where('id', $request->$idBarang)
                ->update([
                    'status_peminjaman' => 'tidak digunakan',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);
            }
        }
        return redirect()->route('sapras', ['status' => 'proses'])->with('success', 'Data telah diperbaharui!');

    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function tolakKegiatan(Request $request)
{
    // Validasi input
    $request->validate([
        'alasan' => 'required|string|max:255',
        'idKegiatan' => 'required|exists:data_perjadinkegiatans,id',
    ]);

    // dd($request);

    try {
        DB::beginTransaction();

        // Ambil data mobilitas
        $idMobilitas = DB::table('mobilitas_perjadinkegiatans')
            ->where('data_perjadinkegiatan', $request->idKegiatan)
            ->select('id')
            ->first();

        // Hanya lakukan update jika $idMobilitas tidak null
        if ($idMobilitas) {
            DB::table('peminjaman_kendaraan_dinas')
                ->where('mobilitas_perjadinkegiatan', $idMobilitas->id)
                ->update([
                    'status' => 'ditolak',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

            DB::table('mobilitas_perjadinkegiatans')
                ->where('data_perjadinkegiatan', $request->idKegiatan)
                ->update([
                    'status' => 'ditolak',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);
        }

        // Update status kegiatan menjadi ditolak
        DB::table('data_perjadinkegiatans')
            ->where('id', $request->idKegiatan)
            ->update([
                'status' => 'ditolak',
                'status_pengajuan' => 'ditolak',
                'status_pengajuan_detail' => 'Verifikasi-HKT-ditolak',
                'is_acceptHKT' => 'ditolak',
                'alasan_penolakan' => $request->alasan,
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

        // Update status perangkat_acaras menjadi ditolak
        DB::table('perangkat_acaras')
            ->where('data_perjadin_kegiatan', $request->idKegiatan)
            ->update([
                'status' => 'ditolak',
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

        DB::commit();
        return redirect()->route('HKT-kegiatan', ['status' => 'pengajuan'])->with('success', 'Pengajuan Telah Berhasil Ditolak!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Gagal menolak pengajuan: ' . $e->getMessage());
    }

}


public function uploadSuratKegiatan(Request $request)
{
    // Validasi input
    $request->validate([
        'surat_tugas' => 'required|mimes:pdf|file|max:2048', // Harus file PDF
    ]);

    // Ambil ID kegiatan
    $id = $request->input('idKegiatan');

    if (!$id) {
        return back()->with('error', 'Data kegiatan tidak ditemukan.');
    }

    // Cari data kegiatan
    $kegiatan = DB::table('data_perjadinkegiatans')->where('id', $id)->first();
    if (!$kegiatan) {
        return back()->with('error', 'Data kegiatan tidak ditemukan.');
    }

    try {
        DB::beginTransaction();

        // Simpan file PDF surat tugas
        $filePath = $request->file('surat_tugas')->store('dokumens-kegiatans', 'public');

        // Update tabel dengan path surat tugas baru
        DB::table('data_perjadinkegiatans')
            ->where('id', $id)
            ->update([
                'surat_tugas' => $filePath,
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

        DB::commit();
        return redirect()->route('HKT-kegiatan', ['status' => 'selesai'])->with('success', 'Surat Tugas telah berhasil diupload!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Gagal mengupload surat tugas: ' . $e->getMessage());
    }
}

public function updateSuratKegiatan(Request $request)
{
    // Validasi input surat tugas
    $request->validate([
        'surat_tugas_update' => 'required|mimes:pdf|file|max:2048',
        'nomor_surtug_update' => 'required',
        'tgl_dibuat_update' => 'required|date',
    ]);

    // Ambil ID kegiatan dari input
    $id = $request->input('idKegiatanUpdate');
    if (!$id) {
        return back()->with('error', 'Data kegiatan tidak ditemukan.');
    }

    // Cari kegiatan berdasarkan ID
    $kegiatan = DB::table('data_perjadinkegiatans')->where('id', $id)->first();
    if (!$kegiatan) {
        return back()->with('error', 'Data kegiatan tidak ditemukan.');
    }

    try {
        DB::beginTransaction();

        // Simpan file PDF surat tugas
        $filePath = $request->file('surat_tugas_update')->store('dokumen-kegiatans', 'public');

        // Update data dokumen dan kegiatan di database
        DB::table('dokumens')
            ->where('data_perjadinkegiatan_id', $id)
            ->update([
                'surat_tugas' => $filePath,
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

        DB::table('data_perjadinkegiatans')
            ->where('id', $id)
            ->update([
                'kode_surat_tugas' => $request->nomor_surtug_update,
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

        DB::table('perangkat_acaras')
            ->where('data_perjadinkegiatan_id', $id)
            ->update([
                'nomor_surat' => $request->nomor_surtug_update,
                'tgl_surat_dibuat' => $request->tgl_dibuat_update,
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

        DB::commit();
        return redirect()->route('HKT-kegiatan', ['status' => 'selesai'])->with('success', 'Surat Tugas telah berhasil diupdate!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Gagal mengupdate surat tugas: ' . $e->getMessage());
    }
}

public function deleteMobilitas(Request $request, $id)
    {
        // dd($request);
        DB::table('peminjaman_kendaraan_dinas')->where('id', $id)->delete();

        // Mendapatkan ID perjadin dari request JSON
        $id_kegiatan = $request->input('mobilitas_perjadinkegiatan');

        // Mengembalikan response sukses
        return response()->json(['success' => true]);
    }

public function uploadTTEPerjadinKegiatan(Request $request)
{
    $id = $request->input('idKegiatan'); // 'id' sesuai dengan parameter dalam URL
        if (!$id) {
            dd('Data tidak ditemukan.');
        }
        $perjadin = DB::table('data_perjadinkegiatans')->where('id', $id)->first();
        if (!$perjadin) {
            dd('Data tidak ditemukan.');
        }

        DB::table('surtug_perjadinkegiatans')
            ->where('data_Perjadin_kegiatan', $id)
            ->update([
                'nomor_surat' => $request->nomor_surtug_tte,
                'tgl_surat_dibuat' => $request->tgl_dibuat_tte,
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

        DB::table('data_perjadinkegiatans')
            ->where('id', $id)
            ->update([
                'status_pengajuan_detail' => 'Verifikasi-HKT<br>(Proses TTE)',
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);


        return redirect()->route('HKT-kegiatan', ['status' => 'pengajuan'])->with('success', 'Surat Tugas telah berhasil di Upload!');
}

public function generateLaporanHKT(Request $request)
    {
        $mulai = $request->tanggalDari;
        $sampai = $request->tanggalSampai;
        return redirect()->route('laporan-HKT-kegiatan', ['mulai' => $mulai, 'sampai' => $sampai])->with('success', 'Data Telah diset!');
    }

    public function getAllDataHKT($mulai, $sampai)
{
    // Ambil semua data dari tabel
    $data = DB::table('data_perjadinkegiatans')
        ->select(
            'data_perjadinkegiatans.id AS id_kegiatan',
            'data_perjadinkegiatans.nama_kegiatan',
            'data_perjadinkegiatans.tgl_mulai',
            'data_perjadinkegiatans.tgl_selesai',
            'data_perjadinkegiatans.alamat',
            'data_perjadinkegiatans.kab_kota',
            'data_perjadinkegiatans.provinsi',
            'data_perjadinkegiatans.id_pengaju',
            'surtug_perjadinkegiatans.nomor_surat AS no_surtug',
            'surtug_perjadinkegiatans.tgl_surat_dibuat AS tgl_surtug',
            'data_perjadinkegiatans.is_acceptHKT',
            'data_perjadinkegiatans.status_pengajuan_detail',
            DB::raw('COALESCE(CONCAT("(Admin) ", administrators.username), pegawais.nama_lengkap, non_pegawais.nama_lengkap, "Tidak ditemukan") as nama_pengaju'),
            // Subquery to get the list of participants (nama_peserta)
            DB::raw("(SELECT GROUP_CONCAT(COALESCE(pegawais.nama_lengkap, non_pegawais.nama_lengkap) SEPARATOR '\n')
                    FROM perangkat_acaras
                    LEFT JOIN pegawais ON perangkat_acaras.pegawai_id = pegawais.id
                    LEFT JOIN non_pegawais ON perangkat_acaras.non_pegawai_id = non_pegawais.id
                    WHERE perangkat_acaras.data_perjadin_kegiatan = data_perjadinkegiatans.id) AS nama_peserta")
        )
        ->leftJoin('perangkat_acaras', 'perangkat_acaras.data_perjadin_kegiatan', '=', 'data_perjadinkegiatans.id')
        ->leftJoin('surtug_perjadinkegiatans', 'surtug_perjadinkegiatans.data_perjadin_kegiatan', '=', 'data_perjadinkegiatans.id')
        ->leftJoin('administrators', 'data_perjadinkegiatans.id_pengaju', '=', 'administrators.id')
        ->leftJoin('pegawais', 'data_perjadinkegiatans.id_pengaju', '=', 'pegawais.id')
        ->leftJoin('non_pegawais', 'data_perjadinkegiatans.id_pengaju', '=', 'non_pegawais.id')
        ->whereBetween('data_perjadinkegiatans.tgl_mulai', [$mulai, $sampai])
        ->whereNotNull('data_perjadinkegiatans.is_acceptHKT')
        ->where('data_perjadinkegiatans.is_acceptHKT', '<>', 'ditolak')
        ->groupBy(
            'data_perjadinkegiatans.id',
            'data_perjadinkegiatans.nama_kegiatan',
            'data_perjadinkegiatans.tgl_mulai',
            'data_perjadinkegiatans.tgl_selesai',
            'data_perjadinkegiatans.alamat',
            'data_perjadinkegiatans.kab_kota',
            'data_perjadinkegiatans.provinsi',
            'data_perjadinkegiatans.id_pengaju',
            'surtug_perjadinkegiatans.nomor_surat',
            'surtug_perjadinkegiatans.tgl_surat_dibuat',
            'data_perjadinkegiatans.is_acceptHKT',
            'data_perjadinkegiatans.status_pengajuan_detail',
            'administrators.username',
            'pegawais.nama_lengkap',
            'non_pegawais.nama_lengkap'
        )
        ->get();

    return response()->json($data);

}


    public function laporanHKT($mulai, $sampai)
{
    // Mengambil data kegiatan yang relevan dengan join ke tabel surtug_perjadinkegiatans
    $data = DB::table('data_perjadinkegiatans')
        ->select(
            'data_perjadinkegiatans.id AS id_kegiatan',
            'data_perjadinkegiatans.nama_kegiatan',
            'data_perjadinkegiatans.tgl_mulai',
            'data_perjadinkegiatans.tgl_selesai',
            'data_perjadinkegiatans.alamat',
            'data_perjadinkegiatans.kab_kota',
            'data_perjadinkegiatans.provinsi',
            'data_perjadinkegiatans.kode_surat_tugas AS no_surtug',
            // Mengambil tgl_surat_dibuat dari tabel surtug_perjadinkegiatans
            'surtug_perjadinkegiatans.tgl_surat_dibuat AS tgl_surtug',
            'data_perjadinkegiatans.is_acceptHKT',
            'data_perjadinkegiatans.status_pengajuan_detail',
            DB::raw('COALESCE(pegawais.nama_lengkap, "Tidak ditemukan") as nama_pengaju'),
            DB::raw("(SELECT GROUP_CONCAT(COALESCE(pegawais.nama_lengkap, non_pegawais.nama_lengkap) SEPARATOR '\n')
                   FROM perangkat_acaras
                   LEFT JOIN pegawais ON perangkat_acaras.pegawai_id = pegawais.id
                   LEFT JOIN non_pegawais ON perangkat_acaras.non_pegawai_id = non_pegawais.id
                   WHERE perangkat_acaras.data_perjadin_kegiatan = data_perjadinkegiatans.id) AS nama_peserta")

        )
        ->leftJoin('perangkat_acaras', 'perangkat_acaras.data_perjadin_kegiatan', '=', 'data_perjadinkegiatans.id')
        ->leftJoin('surtug_perjadinkegiatans', 'surtug_perjadinkegiatans.data_perjadin_kegiatan', '=', 'data_perjadinkegiatans.id')
        ->leftJoin('administrators', 'data_perjadinkegiatans.id_pengaju', '=', 'administrators.id')
        ->leftJoin('pegawais', 'data_perjadinkegiatans.id_pengaju', '=', 'pegawais.id')
        ->leftJoin('non_pegawais', 'data_perjadinkegiatans.id_pengaju', '=', 'non_pegawais.id')
        ->whereBetween('data_perjadinkegiatans.tgl_mulai', [$mulai, $sampai])
        ->whereNotNull('data_perjadinkegiatans.is_acceptHKT')
        ->where('data_perjadinkegiatans.is_acceptHKT', '<>', 'ditolak')
        ->groupBy(
            'data_perjadinkegiatans.id',
            'data_perjadinkegiatans.nama_kegiatan',
            'data_perjadinkegiatans.tgl_mulai',
            'data_perjadinkegiatans.tgl_selesai',
            'data_perjadinkegiatans.alamat',
            'data_perjadinkegiatans.kab_kota',
            'data_perjadinkegiatans.provinsi',
            'data_perjadinkegiatans.kode_surat_tugas',
            'surtug_perjadinkegiatans.tgl_surat_dibuat',
            'data_perjadinkegiatans.is_acceptHKT',
            'data_perjadinkegiatans.status_pengajuan_detail',
            'pegawais.nama_lengkap'
        )
        ->get();

    return view('admin.kegiatan.HKT.LaporanHKT', compact('data', 'mulai', 'sampai'));
}

function updateDataSPPD(Request $request) {

    $nPenandatangan = (int) ($request->nPenandatangan);
    // dd($request);

    $existingLaporan = DB::table('laporan_perjadinkegiatans')
        ->where('nama_dokumen', 'SPPD Kegiatan ' . $request->kegiatan_id)
        ->first();

    if ($existingLaporan) {
        $idLaporan = $existingLaporan->id;
    } else {
        $idLaporan = DB::table('laporan_perjadinkegiatans')->insertGetId([
            'nama_dokumen' => 'SPPD Kegiatan ' . $request->kegiatan_id,
            'data_perjadin_kegiatan' => $request->kegiatan_id,
            'status' => 'selesai',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);
    }

    
    $tempatTujuan_penandatangan0 = 'tempatTujuan_penandatangan_0';
    DB::table('laporan_perjadinkegiatans')
        ->where('id',$idLaporan)
        ->where('data_perjadin_kegiatan',$request->kegiatan_id)
        ->update([
            'tempatTujuan_penandatangan0' => $request->$tempatTujuan_penandatangan0,
    ]);

    for ($i=1; $i <= $nPenandatangan; $i++) {
        $nama_penandatangan = 'nama_penandatangan_' . $i;
        $jabatan_penandatangan = 'jabatan_penandatangan_' . $i;
        $nip_penandatangan = 'nip_penandatangan_' . $i;
        $tempatTiba_penandatangan = 'tempatTiba_penandatangan_' . $i;
        $tempatTujuan_penandatangan = 'tempatTujuan_penandatangan_' . $i;
        $tanggal_penandatangan = 'tanggalTiba_penandatangan_' . $i;
        $tanggalTujuan_penandatangan = 'tanggalBerangkat_penandatangan_' . $i;
        // dd($nPenandatangan,$request->$nama_penandatangan, $request->$jabatan_penandatangan, $request->$nip_penandatangan);
        if ($i == 1) {
            DB::table('laporan_perjadinkegiatans')
            ->where('id',$idLaporan)
            ->where('data_perjadin_kegiatan',$request->kegiatan_id)
            ->update([
                'n_penandatangan' => $nPenandatangan,
                'nama_penandatangan' => $request->$nama_penandatangan,
                'jabatan_penandatangan' => $request->$jabatan_penandatangan,
                'nip_penandatangan' => $request->$nip_penandatangan,
                'tempatTiba_penandatangan' => $request->$tempatTiba_penandatangan,
                'tempatTujuan_penandatangan' => $request->$tempatTujuan_penandatangan,
                'tanggal_penandatangan' => $request->$tanggal_penandatangan,
                'tanggalTujuan_penandatangan' => $request->$tanggalTujuan_penandatangan,
            ]);
        } else {
            DB::table('laporan_perjadinkegiatans')
            ->where('id',$idLaporan)
            ->where('data_perjadin_kegiatan',$request->kegiatan_id)
            ->update([
                'n_penandatangan' => $nPenandatangan,
                'nama_penandatangan'.$i => $request->$nama_penandatangan,
                'jabatan_penandatangan'.$i => $request->$jabatan_penandatangan,
                'nip_penandatangan'.$i => $request->$nip_penandatangan,
                'tempatTiba_penandatangan'.$i => $request->$tempatTiba_penandatangan,
                'tempatTujuan_penandatangan'.$i => $request->$tempatTujuan_penandatangan,
                'tanggal_penandatangan'.$i => $request->$tanggal_penandatangan,
                'tanggalTujuan_penandatangan'.$i => $request->$tanggalTujuan_penandatangan,
            ]);
        }
    }

    // dd($idLaporan);

    return $this->CetakSPPD($request->kegiatan_id,$idLaporan);
}

    public function CetakSPPD($id,$idLaporan)
    {
        // dd($id,$idLaporan);
        if (!$id) {
            // Handle jika data tidak ditemukan
            dd('Data tidak ditemukan.');
        }

        $pesertaPegawais = DB::table('data_perjadinkegiatans as i')
            ->join('perangkat_acaras as dp', 'i.id', '=', 'dp.data_perjadin_kegiatan')
            ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
            ->join('keuangan_perjadinkegiatans as k', function ($join) {
                $join->on('k.data_perjadinkegiatan', '=', 'i.id')
                    ->on('k.perangkat_acara', '=', 'dp.id');
            })
            ->join('akun_x_rkakls as a', 'k.akun_x_rkakl', '=', 'a.id') // Join ke akun_x_rkakls
            ->join('akuns', 'a.akun_id', '=', 'akuns.id')
            ->join('ref_rkakl_sub_komponens', 'a.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
            ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
            ->join('surtug_perjadinkegiatans as s', 's.data_perjadin_kegiatan', '=', 'i.id')
            ->join('jabatans as j', 'j.id', '=', 'p.jabatan_id')
            ->where('i.id', $id)
            ->whereNull('k.kebutuhan_id')
            ->where('dp.posisi', 'Panitia')
            ->select(
                'i.id as id_data_perjadinkegiatans',
                'p.id',
                'p.nama_lengkap',
                'p.NIP_NIK',
                'p.pangkat',
                'p.golongan',
                'j.nama_jabatan',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kab_kota',
                'i.provinsi',
                'i.tgl_selesai',
                'a.id as idAkun',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'ref_rkakl_satkers.kode_satker',
                'ref_rkakl_programs.kode_program',
                'ref_rkakl_kegiatans.kode_kegiatan',
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'akuns.kode_akun'
            )
            ->groupBy(
                'i.id',
                'p.id',
                'p.nama_lengkap',
                'p.NIP_NIK',
                'p.pangkat',
                'p.golongan',
                'j.nama_jabatan',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kab_kota',
                'i.provinsi',
                'i.tgl_selesai',
                'a.id',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'ref_rkakl_satkers.kode_satker',
                'ref_rkakl_programs.kode_program',
                'ref_rkakl_kegiatans.kode_kegiatan',
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'akuns.kode_akun'
            )
            ->distinct()
            ->get();

        $pesertaNonPegawais = DB::table('data_perjadinkegiatans as i')
        ->join('perangkat_acaras as dp', 'i.id', '=', 'dp.data_perjadin_kegiatan')
        ->join('non_pegawais as p', 'dp.non_pegawai_id', '=', 'p.id')
        ->join('keuangan_perjadinkegiatans as k', function ($join) {
            $join->on('k.data_perjadinkegiatan', '=', 'i.id')
                ->on('k.perangkat_acara', '=', 'dp.id');
        })
        ->join('akun_x_rkakls as a', 'k.akun_x_rkakl', '=', 'a.id') // Join ke akun_x_rkakls
        ->join('akuns', 'a.akun_id', '=', 'akuns.id')
        ->join('ref_rkakl_sub_komponens', 'a.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
        ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
        ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
        ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
        ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
        ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
        ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
        ->join('surtug_perjadinkegiatans as s', 's.data_perjadin_kegiatan', '=', 'i.id')
        ->where('i.id', $id)
        ->whereNull('k.kebutuhan_id')
        ->where('dp.posisi', 'Panitia')
        ->select(
            'i.id as id_data_perjadinkegiatans',
            'p.id',
            'p.nama_lengkap',
            'p.NIP_NIK',
            'p.pangkat',
            'p.golongan',
            's.nomor_surat',
            's.tgl_surat_dibuat',
            's.paragraf_1',
            'i.nama_kegiatan',
            'i.tgl_mulai',
            'i.kab_kota',
            'i.provinsi',
            'i.tgl_selesai',
            'a.id as idAkun',
            'ref_rkakl_sub_komponens.nama_sub_kegiatan',
            'akuns.uraian',
            'ref_rkakl_satkers.kode_satker',
            'ref_rkakl_programs.kode_program',
            'ref_rkakl_kegiatans.kode_kegiatan',
            'ref_rkakl_outputs.kode_output',
            'ref_rkakl_suboutputs.kode_sub_output',
            'ref_rkakl_komponens.kode_komponen',
            'ref_rkakl_sub_komponens.kode_sub_kegiatan',
            'akuns.kode_akun'
        )
        ->groupBy(
            'i.id',
            'p.id',
            'p.nama_lengkap',
            'p.NIP_NIK',
            'p.pangkat',
            'p.golongan',
            's.nomor_surat',
            's.tgl_surat_dibuat',
            's.paragraf_1',
            'i.nama_kegiatan',
            'i.tgl_mulai',
            'i.kab_kota',
            'i.provinsi',
            'i.tgl_selesai',
            'a.id',
            'ref_rkakl_sub_komponens.nama_sub_kegiatan',
            'akuns.uraian',
            'ref_rkakl_satkers.kode_satker',
            'ref_rkakl_programs.kode_program',
            'ref_rkakl_kegiatans.kode_kegiatan',
            'ref_rkakl_outputs.kode_output',
            'ref_rkakl_suboutputs.kode_sub_output',
            'ref_rkakl_komponens.kode_komponen',
            'ref_rkakl_sub_komponens.kode_sub_kegiatan',
            'akuns.kode_akun'
        )
        ->distinct()
        ->get();


        $pengemudis =  DB::table('data_perjadinkegiatans as i')
        ->join('perangkat_acaras as dp', 'i.id', '=', 'dp.data_perjadin_kegiatan')
        ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
        ->join('keuangan_perjadinkegiatans as k', function ($join) {
            $join->on('k.data_perjadinkegiatan', '=', 'i.id')
                ->on('k.perangkat_acara', '=', 'dp.id');
        })
        ->join('akun_x_rkakls as a', 'k.akun_x_rkakl', '=', 'a.id') // Join ke akun_x_rkakls
        ->join('akuns', 'a.akun_id', '=', 'akuns.id')
        ->join('ref_rkakl_sub_komponens', 'a.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
        ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
        ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
        ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
        ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
        ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
        ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
        ->join('surtug_perjadinkegiatans as s', 's.data_perjadin_kegiatan', '=', 'i.id')
        ->join('jabatans as j', 'j.id', '=', 'p.jabatan_id')
        ->where('i.id', $id)
        ->whereNull('k.kebutuhan_id')
        ->where('dp.posisi', 'Supir')
        ->select(
            'i.id as id_data_perjadinkegiatans',
            'p.id',
            'p.nama_lengkap',
            'p.NIP_NIK',
            'p.pangkat',
            'p.golongan',
            'j.nama_jabatan',
            's.nomor_surat',
            's.tgl_surat_dibuat',
            's.paragraf_1',
            'i.nama_kegiatan',
            'i.tgl_mulai',
            'i.kab_kota',
            'i.provinsi',
            'i.tgl_selesai',
            'a.id as idAkun',
            'ref_rkakl_sub_komponens.nama_sub_kegiatan',
            'akuns.uraian',
            'ref_rkakl_satkers.kode_satker',
            'ref_rkakl_programs.kode_program',
            'ref_rkakl_kegiatans.kode_kegiatan',
            'ref_rkakl_outputs.kode_output',
            'ref_rkakl_suboutputs.kode_sub_output',
            'ref_rkakl_komponens.kode_komponen',
            'ref_rkakl_sub_komponens.kode_sub_kegiatan',
            'akuns.kode_akun'
        )
        ->groupBy(
            'i.id',
            'p.id',
            'p.nama_lengkap',
            'p.NIP_NIK',
            'p.pangkat',
            'p.golongan',
            'j.nama_jabatan',
            's.nomor_surat',
            's.tgl_surat_dibuat',
            's.paragraf_1',
            'i.nama_kegiatan',
            'i.tgl_mulai',
            'i.kab_kota',
            'i.provinsi',
            'i.tgl_selesai',
            'a.id',
            'ref_rkakl_sub_komponens.nama_sub_kegiatan',
            'akuns.uraian',
            'ref_rkakl_satkers.kode_satker',
            'ref_rkakl_programs.kode_program',
            'ref_rkakl_kegiatans.kode_kegiatan',
            'ref_rkakl_outputs.kode_output',
            'ref_rkakl_suboutputs.kode_sub_output',
            'ref_rkakl_komponens.kode_komponen',
            'ref_rkakl_sub_komponens.kode_sub_kegiatan',
            'akuns.kode_akun'
        )
        ->distinct()
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

        $pegawaiMaster = DB::table('ref_penandatangans')
            ->join('pegawais', 'pegawais.id', '=', 'ref_penandatangans.pegawai_id')
            ->select('pegawais.*')
            ->where('ref_penandatangans.posisi_penandatangan', 'Pejabat Pembuat Komitmen')
            ->where('ref_penandatangans.status_penandatangan', 'aktif')
            ->first();

        $pegawaiBendahara = DB::table('ref_penandatangans')
            ->join('pegawais', 'pegawais.id', '=', 'ref_penandatangans.pegawai_id')
            ->select('pegawais.*')
            ->where('ref_penandatangans.posisi_penandatangan', 'Bendahara')
            ->where('ref_penandatangans.status_penandatangan', 'aktif')
            ->first();



        $ttdSPPD = DB::table('laporan_perjadinkegiatans')
            ->where('id',$idLaporan)
            ->where('data_perjadin_kegiatan',$id)
            ->select(
                'tempatTujuan_penandatangan0  as tempatTujuan0',
                'nama_penandatangan as nama1','jabatan_penandatangan as jabatan1','nip_penandatangan as nip1', 'tempatTiba_penandatangan as tempatTiba1','tempatTujuan_penandatangan as tempatTujuan1','tanggal_penandatangan as tanggal1', 'tanggalTujuan_penandatangan as tanggalTujuan1',
                'nama_penandatangan2 as nama2','jabatan_penandatangan2 as jabatan2','nip_penandatangan2 as nip2', 'tempatTiba_penandatangan2 as tempatTiba2','tempatTujuan_penandatangan2 as tempatTujuan2','tanggal_penandatangan2 as tanggal2', 'tanggalTujuan_penandatangan2 as tanggalTujuan2',
                'nama_penandatangan3 as nama3','jabatan_penandatangan3 as jabatan3','nip_penandatangan3 as nip3', 'tempatTiba_penandatangan3 as tempatTiba3','tempatTujuan_penandatangan3 as tempatTujuan3','tanggal_penandatangan3 as tanggal3', 'tanggalTujuan_penandatangan3 as tanggalTujuan3',
                'nama_penandatangan4 as nama4','jabatan_penandatangan4 as jabatan4','nip_penandatangan4 as nip4', 'tempatTiba_penandatangan4 as tempatTiba4','tempatTujuan_penandatangan4 as tempatTujuan4','tanggal_penandatangan4 as tanggal4', 'tanggalTujuan_penandatangan4 as tanggalTujuan4',
                'n_penandatangan as nSPPD',
                )
            ->first(); ;

        // dd($pesertaPegawais, $pengemudis, $pegawaiMaster, $pegawaiBendahara, $pesertaNonPegawais, $ttdSPPD, $akuns);

        $datas = [
            'pesertaPegawais' => $pesertaPegawais,
            'pengemudis' => $pengemudis,
            'pegawaiMaster' => $pegawaiMaster,
            'pegawaiBendahara' => $pegawaiBendahara,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'kegiatan' => Data_perjadinkegiatan::find($id),
            'ttdSPPD' => $ttdSPPD,
            'akuns' => $akuns,
        ];

        // Lakukan proses pembuatan file PDF
        $pdf = PDF::loadView('admin.kegiatan.keuangan.sppd', compact('datas'));
        $pdf->setPaper('F4', 'portrait');

        // Simpan file PDF ke penyimpanan
        $filePath = $pdf->output();
        Storage::disk('public')->put("dokumen-kegiatans/sppd_kegiatan_$id.pdf", $filePath);

        // Stream file PDF ke browser
        return $pdf->stream("sppd_kegiatan_$id.pdf");
    }
}
