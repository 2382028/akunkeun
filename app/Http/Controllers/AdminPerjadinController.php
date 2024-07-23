<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Info_perjadinlangsung;
use App\Models\Kendaraan;
use App\Models\Peminjaman_kendaraan_dinas;
use App\Models\Kebutuhan;
use App\Models\Non_pegawai;
use Illuminate\Support\Facades\Log;
use App\Models\Versi;
use App\Models\Pegawai;
use App\Models\Ref_sbm;
use App\Models\surtug_perjadinlangsung;
use App\Models\Keuangan_perjadinlangsung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;

// Set locale to Indonesian
setlocale(LC_TIME, 'id_ID');

// Set Carbon locale to Indonesian
Carbon::setLocale('id');

class AdminPerjadinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($status = 'pengajuan')
    {
        $mobilitas = DB::table('peminjaman_kendaraan_dinas')
            ->join('info_perjadinlangsungs', 'peminjaman_kendaraan_dinas.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('info_perjadinlangsungs.id as idPerjadin', 'info_perjadinlangsungs.nama_kegiatan', 'info_perjadinlangsungs.kabupaten_kota', 'info_perjadinlangsungs.tgl_keberangkatan', 'info_perjadinlangsungs.is_acceptBMN')
            ->where('info_perjadinlangsungs.versi_id', session('versi'))
            ->where('info_perjadinlangsungs.is_acceptBMN', $status)
            ->get();


        return view('admin.perjadin.mobilitas.index', [
            'title' => 'Mobilitas Perjalanan Dinas',
            'mobilitass' => $mobilitas,

        ]);
    }

    public function storeMobilitasOnly(Request $request)
{
    // Gabungkan tanggal dan waktu keberangkatan
    $keberangkatanDate = $request->tgl_keberangkatan; // Asumsikan '2024-07-22'
    $keberangkatanTime = $request->jam_keberangkatan; // Asumsikan '14:13:00'
    $keberangkatan = $keberangkatanDate . ' ' . substr($keberangkatanTime, 0, 5); // '2024-07-22 14:13'

    $tgl_keberangkatan = Carbon::createFromFormat('Y-m-d H:i', $keberangkatan);

    $selesaiDate = $request->tgl_selesai;
    $selesaiTime = $request->jam_selesai;
    $selesai = $selesaiDate . ' ' . substr($selesaiTime, 0, 5);
    $tgl_selesai = Carbon::createFromFormat('Y-m-d H:i', $selesai);

    // if (empty($request->perjadinSebelumnya)) {

    // } else {
    //     $perjadin = $request->perjadinSebelumnya; // mengambil nilai id dari perjadinSebelumnya
    // }

    $versi = Versi::where('status', 'aktif')->get();
        DB::table('info_perjadinlangsungs')->insertOrIgnore([
            'nama_kegiatan' => $request->ket_mobilitas . $request->nama_kegiatan,
            'tgl_mulai' => $tgl_keberangkatan,
            'tgl_selesai' => $tgl_selesai,
            'tgl_keberangkatan' => $tgl_keberangkatan,
            'tgl_kepulangan' => $tgl_selesai,
            'provinsi' => $request->provinsi,
            'kabupaten_kota' => $request->kabupaten_kota,
            'alamat' => $request->alamat,
            'mobilitas' => "Kendaraan Dinas",
            'pemberi_undangan' => "-",
            'tanggal_surat' => "-",
            'ket_mobilitas' => $request->ket_mobilitas, // Tambahkan ini
            'status_pengajuan' => 'Draf-pengajuan',
            'versi_id' => $versi[0]->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $perjadin = Info_perjadinlangsung::max('id'); // mengambil nilai id terakhir yang diinputkan

    $status = 'status_' . $perjadin;
    DB::table('peminjaman_kendaraan_dinas')->insertOrIgnore([
        'info_perjadinlangsung' => $perjadin, //menerima id info terakhir
        'kendaraan' => $request->kendaraan,
        'status' => 'pengajuan',
        'pegawai_id' => $request->pengemudi,
        'ket_mobilitas' => $request->ket_mobilitas,
        'tgl_selesai' => $tgl_selesai,
        'tgl_keberangkatan' => $tgl_keberangkatan,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('info_perjadinlangsungs')
        ->where('id', $perjadin)
        ->update([
            'is_acceptHKT' => 'pengajuan',
            'is_acceptBMN' => 'proses',
            'status_pengajuan_detail' => 'Verifikasi-HKT',
            'admin_BMN' => auth('administrator')->user()->id,
            'status_pengajuan'  => 'proses',
            'updated_at' => now(),
        ]);

    DB::table('data_perjadinlangsungs')->insertOrIgnore([
        'status_pegawai' => 'Supir',
        'info_perjadinlangsung' => $perjadin,
        'pegawai_id' => $request->pengemudi,
        'tgl_keberangkatan' => $request->tgl_keberangkatan,
        'tgl_selesai' => $request->tgl_selesai,
        // 'non_pegawai_id' => $request->peserta_non_pegawai,
        'status_persetujuan' => 'Proses Persetujuan',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Redirect ke URL tertentu
    return redirect()->to(url('/perjadin-mobilitas/pengajuan'));
}


    public function showBmnMobilitasOnly()
{
    // Pengemudi
    $pengemudi = DB::table('pegawais')
        ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
        ->select('pegawais.id', 'pegawais.nama_lengkap', 'jabatans.nama_jabatan')
        ->where('jabatans.nama_jabatan', 'Pengemudi')
        ->distinct()
        ->get();

    // Kendaraan
    $kendaraan = DB::table('kendaraans')
        ->select('kendaraans.*')
        ->where('kendaraans.status', '=', 'baik')
        ->where('kendaraans.tipe', '=', 'Roda Empat')
        ->distinct()
        ->get();

    // Mobilitass
    $mobilitass = DB::table('info_perjadinlangsungs')
        ->leftJoin('peminjaman_kendaraan_dinas', function($join) {
            $join->on('info_perjadinlangsungs.id', '=', 'peminjaman_kendaraan_dinas.info_perjadinlangsung')
                ->where('peminjaman_kendaraan_dinas.ket_mobilitas', '!=', 'Antar-Jemput');
        })
        ->whereNull('peminjaman_kendaraan_dinas.id')
        ->orWhere(function($query) {
            $query->whereNotNull('peminjaman_kendaraan_dinas.id')
                  ->where('peminjaman_kendaraan_dinas.ket_mobilitas', '!=', 'Antar-Jemput');
        })
        ->select('info_perjadinlangsungs.*')
        ->orderByDesc('info_perjadinlangsungs.id')
        ->limit(10)
        ->get();

    return view(
        'admin.perjadin.mobilitas.bmn_mobilitas_only',
        [
            'pengemudis' => $pengemudi,
            'kendaraans' => $kendaraan,
            'mobilitass' => $mobilitass,
        ]
    );
}


    public function keuanganIndex($status = 'verifikasi-1')
    {
        $perjadin = DB::table('info_perjadinlangsungs')
            ->where('info_perjadinlangsungs.is_acceptKeu', $status)
            ->where('info_perjadinlangsungs.versi_id', session('versi'))
            ->get();
        return view('admin.perjadin.keuangan.index', [
            'title' => 'Keuangan Perjalanan Dinas',
            'perjadins' => $perjadin
        ]);
    }

    public function bendaharaIndex($status = 'approval-1')
    {
        $perjadin = DB::table('info_perjadinlangsungs')
            ->where('is_acceptBend', $status)
            ->where('versi_id', session('versi'))
            ->get();
        return view('admin.perjadin.bendahara.index', [
            'title' => 'Bendahara Perjalanan Dinas',
            'perjadins' => $perjadin
        ]);
    }

    public function HKTIndex($status = 'pengajuan')
    {
        $perjadin = DB::table('info_perjadinlangsungs')
            ->where('is_acceptHKT', $status)
            ->where('is_acceptBMN', 'proses')
            ->where('versi_id', session('versi'))
            ->get();

        // Ambil semua id_perjadin dari perjadin
        $perjadinIds = $perjadin->pluck('id');

        $surtugExist = DB::table('surtug_perjadinlangsungs')
            ->whereIn('id_perjadinlangsung',  $perjadinIds)
            ->pluck('id_perjadinlangsung');

        // Iterasi melalui hasil query untuk menghitung jumlah hari
        foreach ($perjadin as $info) {
            // Konversi tanggal ke dalam objek Carbon untuk perhitungan
            $tglMulai = \Carbon\Carbon::parse($info->tgl_keberangkatan);
            $tglSelesai = \Carbon\Carbon::parse($info->tgl_selesai);

            // Hitung selisih hari
            $jumlahHari = $tglMulai->diffInDays($tglSelesai);

            // Tambahkan jumlah hari ke objek $info
            $info->jumlah_hari = $jumlahHari;
        }


        return view('admin.perjadin.HKT.index', [
            'title' => 'HKT Perjalanan Dinas',
            'perjadins' => $perjadin,
            'surtugs' => surtug_perjadinlangsung::all(),
            'surtugExist' => $surtugExist
        ]);
    }


    public function detail_mobilitas($id)
    {

        // Menggunakan metode find untuk mengambil data berdasarkan ID
        $infoPerjadin = Info_perjadinlangsung::find($id);

        if ($infoPerjadin) {
            $tanggalAwal = Carbon::parse($infoPerjadin->tgl_keberangkatan);
            $tanggalAkhir = Carbon::parse($infoPerjadin->tgl_selesai);
        } else {
            // Penanganan jika tidak ada record yang ditemukan
            $tanggalAwal = null;
            $tanggalAkhir = null;
        }

        $pesertaPegawais = DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->select('pegawais.id', 'pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();
        $pesertaNonPegawais = DB::table('data_perjadinlangsungs')
            ->join('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
            ->select('non_pegawais.id', 'non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'data_perjadinlangsungs.status_pegawai')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();
        $pengemudi = DB::table('pegawais')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->select('pegawais.id', 'pegawais.nama_lengkap', 'jabatans.nama_jabatan')
            ->where('jabatans.nama_jabatan', 'Pengemudi')
            ->whereNotExists(function ($query) use ($tanggalAwal, $tanggalAkhir) {
                $query->select(DB::raw(1))
                    ->from('data_perjadinlangsungs')
                    ->whereRaw('pegawais.id = data_perjadinlangsungs.pegawai_id')
                    ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                        $subquery->where('data_perjadinlangsungs.tgl_keberangkatan', '<=', $tanggalAkhir->format('Y-m-d'))
                            ->where('data_perjadinlangsungs.tgl_selesai', '>=', $tanggalAwal->format('Y-m-d'));
                    });
            })
            ->distinct()
            ->get();
        $kebutuhans = DB::table('keuangan_perjadinlangsungs')
            ->join('kebutuhans', 'keuangan_perjadinlangsungs.kebutuhan_id', '=', 'kebutuhans.id')
            ->select('kebutuhans.id as idKebutuhan', 'kebutuhans.nama', 'kebutuhans.jumlah_frekuensi', 'kebutuhans.satuan', 'kebutuhans.tipe_pendanaan', 'kebutuhans.ket', 'kebutuhans.status', 'keuangan_perjadinlangsungs.kebutuhan_id', 'keuangan_perjadinlangsungs.id as idKeuangan', 'keuangan_perjadinlangsungs.uang_harian', 'keuangan_perjadinlangsungs.persen_pajak', 'keuangan_perjadinlangsungs.jumlah_harga')
            ->where('keuangan_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();
        $kendaraan = DB::table('kendaraans')
            ->select('kendaraans.*')
            ->where('kendaraans.status', '=', 'baik')
            ->where('kendaraans.tipe', '=', 'Roda Empat')
            ->whereNotExists(function ($query) use ($tanggalAwal, $tanggalAkhir) {
                $query->select(DB::raw(1))
                    ->from('peminjaman_kendaraan_dinas')
                    ->whereRaw('kendaraans.id = peminjaman_kendaraan_dinas.kendaraan')
                    ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                        $subquery->where('peminjaman_kendaraan_dinas.tgl_keberangkatan', '<', $tanggalAkhir->format('Y-m-d'))
                            ->where('peminjaman_kendaraan_dinas.tgl_selesai', '>', $tanggalAwal->format('Y-m-d'));
                    });
            })
            ->distinct()
            ->get();
        return view('admin.perjadin.mobilitas.detail', [
            'title' => 'Mobilitas Perjalanan Dinas',
            'pesertaPegawais' => $pesertaPegawais,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'dokumen' => Dokumen::where('info_perjadinlangsung_id', $id)->get(),
            'perjadin' => Info_perjadinlangsung::find($id),
            'mobilitass' => Peminjaman_kendaraan_dinas::where('info_perjadinlangsung', $id)->get(),
            'pengemudis' => $pengemudi,
            'kendaraans' => $kendaraan,
            'kebutuhans' => $kebutuhans,
        ]);
    }

    public function deleteMobilitas(Request $request, $id)
    {
        DB::table('peminjaman_kendaraan_dinas')->where('id', $id)->delete();

        // Mendapatkan ID perjadin dari request JSON
        $id_perjadin = $request->input('info_perjadinlangsung');

        // Mengembalikan response sukses
        return response()->json(['success' => true]);
    }


    public function detail_perjadin_BMN($id)
    {

        $pesertaPegawais = DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->select('pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai')
            ->where('data_perjadinlangsungs.status_pegawai', '!=', 'Supir')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();
        $pesertaNonPegawais = DB::table('data_perjadinlangsungs')
            ->join('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
            ->select('non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'data_perjadinlangsungs.status_pegawai')
            ->where('data_perjadinlangsungs.status_pegawai', '!=', 'Supir')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();
        $pengemudi = DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->join('peminjaman_kendaraan_dinas', 'pegawais.id', '=', 'peminjaman_kendaraan_dinas.pegawai_id')
            ->join('kendaraans', 'peminjaman_kendaraan_dinas.kendaraan', '=', 'kendaraans.id')
            ->select('pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'kendaraans.merek', 'kendaraans.no_polisi')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();
        return view('admin.perjadin.mobilitas.detail_mobilitas', [
            'title' => 'Pengajuan Surtug',
            'pesertaPegawais' => $pesertaPegawais,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'perjadin' => Info_perjadinlangsung::find($id),
            'mobilitass' => Peminjaman_kendaraan_dinas::where('info_perjadinlangsung', $id)->get(),
            'pengemudis' => $pengemudi,
        ]);
    }

    public function detail_perjadin_keuangan($id)
    {
        $pesertaPegawais = DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->select('pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'data_perjadinlangsungs.id as idPeserta', 'data_perjadinlangsungs.status_persetujuan')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();
        $pesertaNonPegawais = DB::table('data_perjadinlangsungs')
            ->join('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
            ->select('non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'data_perjadinlangsungs.id as idPeserta', 'data_perjadinlangsungs.status_persetujuan')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();
        $kebutuhans = DB::table('keuangan_perjadinlangsungs')
            ->join('kebutuhans', 'keuangan_perjadinlangsungs.kebutuhan_id', '=', 'kebutuhans.id')
            ->select('kebutuhans.id as idKebutuhan', 'kebutuhans.nama', 'kebutuhans.jumlah_frekuensi', 'kebutuhans.satuan', 'kebutuhans.tipe_pendanaan', 'kebutuhans.ket', 'kebutuhans.status', 'keuangan_perjadinlangsungs.kebutuhan_id', 'keuangan_perjadinlangsungs.id as idKeuangan', 'keuangan_perjadinlangsungs.uang_harian', 'keuangan_perjadinlangsungs.uang_harian_fullday', 'keuangan_perjadinlangsungs.uang_harian_fullboard', 'keuangan_perjadinlangsungs.uang_representasi', 'keuangan_perjadinlangsungs.persen_pajak', 'keuangan_perjadinlangsungs.jumlah_harga')
            ->where('keuangan_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();
        $pengemudi = DB::table('pegawais')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->join('peminjaman_kendaraan_dinas', 'peminjaman_kendaraan_dinas.pegawai_id', '=', 'pegawais.id')
            ->join('info_perjadinlangsungs', 'peminjaman_kendaraan_dinas.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('pegawais.id', 'pegawais.nama_lengkap', 'jabatans.nama_jabatan')
            ->where('jabatans.nama_jabatan', 'Pengemudi')
            ->where('peminjaman_kendaraan_dinas.info_perjadinlangsung', $id)
            ->get();
        return view('admin.perjadin.keuangan.detail', [
            'title' => 'Detail Keuangan Perjalanan Dinas',
            'perjadin' => Info_perjadinlangsung::find($id),
            'dokumen' => Dokumen::where('info_perjadinlangsung_id', $id)->get(),
            'pesertaPegawais' => $pesertaPegawais,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'kebutuhans' => $kebutuhans,
            'pengemudis' => $pengemudi,
        ]);
    }

    public function detail_perjadin_bendahara($id)
    {
        $pesertaPegawaiss = DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->select('pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'pegawais.NIP_NIK', 'pegawais.id', 'pegawais.nama_lengkap', 'data_perjadinlangsungs.id as idPeserta')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->where(function ($query) {
                $query->where('data_perjadinlangsungs.status_pegawai', '!=', 'Supir')
                    ->orWhere(function ($query) {
                        $query->where('data_perjadinlangsungs.status_pegawai', '=', 'Supir')
                            ->whereNotExists(function ($query) {
                                $query->select(DB::raw(1))
                                    ->from('data_perjadinlangsungs as dp2')
                                    ->whereColumn('dp2.pegawai_id', 'data_perjadinlangsungs.pegawai_id')
                                    ->where('dp2.status_pegawai', '!=', 'Supir');
                            });
                    });
            })
            ->get();

        $pesertaPegawais = DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->join('keuangan_perjadinlangsungs', 'data_perjadinlangsungs.id', '=', 'keuangan_perjadinlangsungs.data_perjadinlangsungs')
            ->select('pegawais.id', 'keuangan_perjadinlangsungs.id as idKeuangan', 'pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'data_perjadinlangsungs.id as idData', 'keuangan_perjadinlangsungs.akun_x_rkakl', 'keuangan_perjadinlangsungs.ref_sbm', 'keuangan_perjadinlangsungs.uang_harian', 'keuangan_perjadinlangsungs.uang_harian_fullday', 'keuangan_perjadinlangsungs.uang_harian_fullboard', 'keuangan_perjadinlangsungs.uang_representasi', 'keuangan_perjadinlangsungs.persen_pajak', 'keuangan_perjadinlangsungs.jumlah_harga', 'keuangan_perjadinlangsungs.status', 'keuangan_perjadinlangsungs.pph22', 'keuangan_perjadinlangsungs.pph23', 'keuangan_perjadinlangsungs.tgl_bayar', 'keuangan_perjadinlangsungs.ppn')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();

        $pesertaNonPegawais = DB::table('data_perjadinlangsungs')
            ->join('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
            ->join('keuangan_perjadinlangsungs', 'data_perjadinlangsungs.id', '=', 'keuangan_perjadinlangsungs.data_perjadinlangsungs')
            ->select('keuangan_perjadinlangsungs.id  as idKeuangan', 'non_pegawais.id', 'non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'data_perjadinlangsungs.id as idData', 'keuangan_perjadinlangsungs.akun_x_rkakl', 'keuangan_perjadinlangsungs.ref_sbm', 'keuangan_perjadinlangsungs.uang_harian', 'keuangan_perjadinlangsungs.persen_pajak', 'keuangan_perjadinlangsungs.jumlah_harga', 'keuangan_perjadinlangsungs.status', 'keuangan_perjadinlangsungs.pph22', 'keuangan_perjadinlangsungs.pph23', 'keuangan_perjadinlangsungs.tgl_bayar', 'keuangan_perjadinlangsungs.ppn')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();

        $kebutuhans = DB::table('kebutuhans')
            ->join('keuangan_perjadinlangsungs', 'kebutuhans.id', '=', 'keuangan_perjadinlangsungs.kebutuhan_id')
            ->join('data_perjadinlangsungs', 'keuangan_perjadinlangsungs.data_perjadinlangsungs', '=', 'data_perjadinlangsungs.id')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->select('kebutuhans.id as idKebutuhan', 'pegawais.nama_lengkap', 'kebutuhans.nama', 'kebutuhans.jumlah_frekuensi', 'kebutuhans.satuan', 'kebutuhans.tipe_pendanaan', 'kebutuhans.ket', 'kebutuhans.status', 'keuangan_perjadinlangsungs.kebutuhan_id as idKeuangan', 'keuangan_perjadinlangsungs.info_perjadinlangsung', 'keuangan_perjadinlangsungs.uang_harian', 'keuangan_perjadinlangsungs.persen_pajak', 'keuangan_perjadinlangsungs.jumlah_harga', 'keuangan_perjadinlangsungs.akun_x_rkakl', 'keuangan_perjadinlangsungs.ref_sbm', 'keuangan_perjadinlangsungs.status as statusPembayaran', 'keuangan_perjadinlangsungs.pph22', 'keuangan_perjadinlangsungs.pph23', 'keuangan_perjadinlangsungs.tgl_bayar', 'keuangan_perjadinlangsungs.ppn')
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
            ->get();

        $dokumen = DB::table('dokumens')
            ->where('info_perjadinlangsung_id', $id)
            ->get();

        return view('admin.perjadin.bendahara.detail', [
            'title' => 'Detail bendahara Perjalanan Dinas',
            'perjadin' => Info_perjadinlangsung::find($id),
            'pesertaPegawais' => $pesertaPegawais,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'pesertaPegawaiss' => $pesertaPegawaiss,
            'kebutuhans' => $kebutuhans,
            "sbms" => Ref_sbm::all(),
            'akuns' => $akuns,
            'dokumen' => $dokumen
        ]);
    }

    public function detail_perjadin_HKT($id)
    {
        $pesertaPegawais = DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->select('pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->where('data_perjadinlangsungs.status_pegawai', '!=', 'Supir')
            ->get();
        $pesertaNonPegawais = DB::table('data_perjadinlangsungs')
            ->join('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
            ->select('non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'data_perjadinlangsungs.status_pegawai')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->where('data_perjadinlangsungs.status_pegawai', '!=', 'Supir')
            ->get();
        $pengemudi =  DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->select('pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->where('data_perjadinlangsungs.status_pegawai', 'Supir')
            ->get();

        return view('admin.perjadin.HKT.detail', [
            'title' => 'Pengajuan Surtug',
            'pesertaPegawais' => $pesertaPegawais,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'dokumen' => Dokumen::where('info_perjadinlangsung_id', $id)->get(),
            'perjadin' => Info_perjadinlangsung::find($id),
            'mobilitass' => Peminjaman_kendaraan_dinas::where('info_perjadinlangsung', $id)->get(),
            'pengemudis' => $pengemudi,
        ]);
    }
    public function surtug_perjadin_HKT($id)
    {
        $surat = DB::table('surtug_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'surtug_perjadinlangsungs.id_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('surtug_perjadinlangsungs.nomor_surat', 'surtug_perjadinlangsungs.perihal', 'surtug_perjadinlangsungs.paragraf_1', 'surtug_perjadinlangsungs.paragraf_2', 'surtug_perjadinlangsungs.paragraf_3')
            ->where('surtug_perjadinlangsungs.id_perjadinlangsung', $id)
            ->get();

        return view('admin.perjadin.HKT.surtug', [
            'title' => 'Pembuatan Surat Tugas',
            'perjadin' => Info_perjadinlangsung::find($id),
            'surtugs' => $surat,

        ]);
    }

    public function detail_surtug_perjadin_HKT($id)
    {
        $pesertaPegawais =  DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->select('pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'pegawais.NIP_NIK')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->where(function ($query) {
                $query->where('data_perjadinlangsungs.status_pegawai', '!=', 'Supir')
                    ->orWhere(function ($query) {
                        $query->where('data_perjadinlangsungs.status_pegawai', '=', 'Supir')
                            ->whereNotExists(function ($query) {
                                $query->select(DB::raw(1))
                                    ->from('data_perjadinlangsungs as dp2')
                                    ->whereColumn('dp2.pegawai_id', 'data_perjadinlangsungs.pegawai_id')
                                    ->where('dp2.status_pegawai', '!=', 'Supir');
                            });
                    });
            })
            ->orderBy('pegawais.golongan', 'desc')
            ->get();

        $pesertaNonPegawais = DB::table('data_perjadinlangsungs')
            ->join('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
            ->select('non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'data_perjadinlangsungs.status_pegawai')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();
        $pengemudi = DB::table('pegawais')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->join('peminjaman_kendaraan_dinas', 'peminjaman_kendaraan_dinas.pegawai_id', '=', 'pegawais.id')
            ->join('info_perjadinlangsungs', 'peminjaman_kendaraan_dinas.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('pegawais.id', 'pegawais.nama_lengkap', 'jabatans.nama_jabatan', 'pegawais.NIP_NIK', 'pegawais.pangkat', 'pegawais.golongan')
            ->where('jabatans.nama_jabatan', 'Pengemudi')
            ->where('peminjaman_kendaraan_dinas.info_perjadinlangsung', $id)
            ->get();
        $surat = DB::table('surtug_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'surtug_perjadinlangsungs.id_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('surtug_perjadinlangsungs.nomor_surat', 'surtug_perjadinlangsungs.perihal', 'surtug_perjadinlangsungs.paragraf_1', 'surtug_perjadinlangsungs.paragraf_2', 'surtug_perjadinlangsungs.paragraf_3')
            ->where('surtug_perjadinlangsungs.id_perjadinlangsung', $id)
            ->get();
        return view('admin.perjadin.HKT.preview_surtug', [
            'title' => 'Pembuatan Surat Tugas',
            'pesertaPegawais' => $pesertaPegawais,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'perjadin' => Info_perjadinlangsung::find($id),
            'pengemudis' => $pengemudi,
            'surtugs' => $surat
        ]);
    }
    public function note_perjadin($id)
    {
        return view(
            'admin.perjadin.keuangan.laporan_perjadin',
            [
                'title' => 'Detail Kegiatanku',
                'perjadin' => Info_perjadinlangsung::find($id),
                'dokumen' => Dokumen::where('info_perjadinlangsung_id', $id)->get(),
            ]
        );
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

        if ($action === 'proses') {
            $numMobilitas = $request->input('numMobilitas');

            for ($i = 0; $i < $numMobilitas; $i++) {
                $idMobilitas = $request->input('idMobilitas_' . $i);
                $kendaraan = $request->input('mobil_' . $i);
                $supir = $request->input('supir_' . $i);
                $status = $request->input('status_' . $i);
                $berangkat = $request->input('berangkat_' . $i);
                $selesai = $request->input('selesai_' . $i);
                $ketMobilitas = $request->input('ket_' . $i);

                // Konversi format tanggal
                $berangkat = Carbon::createFromFormat('d-m-Y H:i', $berangkat)->format('Y-m-d H:i:s');
                $selesai = Carbon::createFromFormat('d-m-Y H:i', $selesai)->format('Y-m-d H:i:s');

                // Update peminjaman kendaraan dinas
                DB::table('peminjaman_kendaraan_dinas')
                    ->where('id', $idMobilitas)
                    ->update([
                        'kendaraan' => $kendaraan,
                        'pegawai_id' => $supir,
                        'status' => $status,
                        'tgl_keberangkatan' => $berangkat,
                        'tgl_selesai' => $selesai,
                        'ket_mobilitas' => $ketMobilitas,
                        'updated_at' => now(),
                    ]);

                // Update kendaraan
                DB::table('kendaraans')
                    ->join('peminjaman_kendaraan_dinas', 'peminjaman_kendaraan_dinas.kendaraan', '=', 'kendaraans.id')
                    ->where('kendaraans.id', $kendaraan)
                    ->update([
                        'kendaraans.updated_at' => now(),
                    ]);

                // Jika status adalah 'proses'
                if ($status === 'proses') {
                    DB::table('data_perjadinlangsungs')->insertOrIgnore([
                        'status_pegawai' => 'Supir',
                        'info_perjadinlangsung' => $request->input('idPerjadin'),
                        'pegawai_id' => $supir,
                        'status_persetujuan' => 'Proses Persetujuan',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $data_perjaidinlangsung_max = DB::table('data_perjadinlangsungs')->latest()->first();

                    DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
                        'info_perjadinlangsung' => $request->input('idPerjadin'),
                        'data_perjadinlangsungs' => $data_perjaidinlangsung_max->id,
                        'status' => 'Menunggu Persetujuan Bendahara',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::table('info_perjadinlangsungs')
                ->where('id', $request->input('idPerjadin'))
                ->update([
                    'is_acceptBMN' => 'proses',
                    'is_acceptHKT' => 'pengajuan',
                    'status_pengajuan'  => 'proses',
                    'status_pengajuan_detail' => 'Verifikasi-HKT',
                    'admin_BMN' => auth('administrator')->user()->id,
                    'updated_at' => now(),
                ]);

            return redirect()->route('mobilitas-perjadin', ['status' => $request->input('perjadinStatus')])->with('success', 'Data telah diperbaharui!');
        } elseif ($action === 'tolak') {
            DB::table('info_perjadinlangsungs')
                ->where('id', $request->input('idPerjadin'))
                ->update([
                    'status_pengajuan' => 'ditolak',
                    'is_acceptBMN' => 'ditolak',
                    'alasan_penolakan' =>  $request->input('alasan'),
                    'admin_BMN' => auth('administrator')->user()->id,
                    'updated_at' => now(),
                ]);

            return redirect()->route('mobilitas-perjadin', ['status' => $request->input('perjadinStatus')])->with('success', 'Pengajuan Telah Ditolak!');
        }
    }

    public function storeMobilitas(Request $request)
    {
        db::table('peminjaman_kendaraan_dinas')->insertOrIgnore([
            'info_perjadinlangsung' => $request->idPerjadin,
            'status' => "pengajuan",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('info_perjadinlangsungs')
            ->where('id', $request->idPerjadin)
            ->update([
                'is_acceptBMN' => 'pengajuan',
                'admin_BMN' => auth('administrator')->user()->id,
                'updated_at' => now(),
            ]);

        return redirect()->route('detail-mobilitas-perjadin', ['id' => $request->idPerjadin])->with('success', 'Data telah ditambahkan, silahkan isi supir dan kendaraannya!');
    }

    public function storeKeuangan(Request $request)
    {
        $action = $request->input('action');

        if ($action === 'verifikasi') {
            $totalpesertapegawai = $request->numPegawai;
            for ($i = 0; $i < $totalpesertapegawai; $i++) {
                $idpesertapegawai = 'idPesertaPegawai_' . $i;
                $statuspersetujuan = 'statuspegawai_' . $i;
                DB::table('data_perjadinlangsungs')
                    ->where('id', $request->$idpesertapegawai)
                    ->update([
                        'status_persetujuan' => 'Disetujui',
                        'updated_at' => now(),
                    ]);
            }


            $totalnonpesertapegawai = $request->numNonPegawai;
            for ($i = 0; $i < $totalnonpesertapegawai; $i++) {
                $idpesertapegawai = 'idNonPesertaPegawai_' . $i;
                $statuspersetujuan = 'statusnonpegawai_' . $i;
                DB::table('data_perjadinlangsungs')
                    ->where('id', $request->$idpesertapegawai)
                    ->update([
                        'status_persetujuan' => 'Disetujui',
                        'updated_at' => now(),
                    ]);
            }

            $numkebutuhan = $request->numKebutuhan;
            for ($i = 0; $i < $numkebutuhan; $i++) {
                $idKeuangan = 'idKeuanganKebutuhan_' . $i;
                $idKebutuhan = 'idKebutuhan_' . $i;
                $harga = 'nominalKebutuhan_' . $i;
                $pajak = 'pajakKebutuhan_' . $i;
                $pph22 = 'pajakKebutuhan22_' . $i;
                $pph23 = 'pajakKebutuhan23_' . $i;
                $ppn = 'ppnKebutuhan_' . $i;
                $nominal = 'totalKebutuhan_' . $i;
                $statusKesesuaian = 'kesesuaianKebutuhan_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('id', $request->$idKeuangan)
                    ->update([
                        'harga' => $request->$harga,
                        'persen_pajak' => $request->$pajak,
                        'pph22' => $request->$pph22,
                        'pph23' => $request->$pph23,
                        'ppn' => $request->$ppn,
                        'jumlah_harga' => $request->$nominal,
                        'updated_at' => now(),
                    ]);

                DB::table('kebutuhans')
                    ->where('id', $request->$idKebutuhan)
                    ->update([
                        'status' => $request->$statusKesesuaian,
                        'updated_at' => now(),
                    ]);
            }

            DB::table('dokumens')
                ->where('id', $request->idDokumen)
                ->update([
                    'ket' => $request->keterangandokumen,
                    'status_persetujuan' => 'sesuai',
                    'updated_at' => now(),
                ]);

            DB::table('info_perjadinlangsungs')
                ->where('id', $request->idPerjadin)
                ->update([
                    'is_acceptKeu' => 'selesai',
                    'is_acceptBend' => 'approval-2',
                    'status_pengajuan_detail' => 'Approval-2-Bendahara',
                    'admin_Keu' => auth('administrator')->user()->id,
                    'updated_at' => now(),
                ]);

            return redirect()->route('keuangan-perjadin', ['status' => 'verifikasi-1'])->with('success', 'Data Telah diperbaharui dan telah dikirim ke bagian bendahara!');
        } elseif ($action === 'verifikasi-2') {
            $totalpesertapegawai = $request->numPegawai;
            for ($i = 0; $i < $totalpesertapegawai; $i++) {
                $idpesertapegawai = 'idPesertaPegawai_' . $i;
                $statuspersetujuan = 'statuspegawai_' . $i;
                DB::table('data_perjadinlangsungs')
                    ->where('id', $request->$idpesertapegawai)
                    ->update([
                        'status_persetujuan' => 'Disetujui',
                        'updated_at' => now(),
                    ]);
            }

            $totalnonpesertapegawai = $request->numNonPegawai;
            for ($i = 0; $i < $totalnonpesertapegawai; $i++) {
                $idpesertapegawai = 'idNonPesertaPegawai_' . $i;
                $statuspersetujuan = 'statusnonpegawai_' . $i;
                DB::table('data_perjadinlangsungs')
                    ->where('id', $request->$idpesertapegawai)
                    ->update([
                        'status_persetujuan' => 'Disetujui',
                        'updated_at' => now(),
                    ]);
            }

            $numkebutuhan = $request->numKebutuhan;
            for ($i = 0; $i < $numkebutuhan; $i++) {
                $idKeuangan = 'idKeuanganKebutuhan_' . $i;
                $idKebutuhan = 'idKebutuhan_' . $i;
                $harga = 'nominalKebutuhan_' . $i;
                $pajak = 'pajakKebutuhan_' . $i;
                $pph22 = 'pajakKebutuhan22_' . $i;
                $pph23 = 'pajakKebutuhan23_' . $i;
                $ppn = 'ppnKebutuhan_' . $i;
                $nominal = 'totalKebutuhan_' . $i;
                $statusKesesuaian = 'kesesuaianKebutuhan_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('id', $request->$idKeuangan)
                    ->update([
                        'uang_harian' => $request->$harga,
                        'persen_pajak' => $request->$pajak,
                        'pph22' => $request->$pph22,
                        'pph23' => $request->$pph23,
                        'ppn' => $request->$ppn,
                        'jumlah_harga' => $request->$nominal,
                        'updated_at' => now(),
                    ]);
            }


            DB::table('dokumens')
                ->where('id', $request->idDokumen)
                ->update([
                    'ket' => $request->keterangandokumen,
                    'tgl_penerimaan_surtug' => $request->tgl_surtug,
                    'tgl_penerimaan_undangan' => $request->tgl_surtug,
                    'tgl_penerimaan_SPPD' => $request->tgl_surtug,
                    'tgl_penerimaan_lap_keu' => $request->tgl_surtug,
                    'tgl_penerimaan_lap_perjadin' => $request->tgl_surtug,
                    'status_persetujuan' => 'sesuai',
                    'updated_at' => now(),
                ]);

            DB::table('info_perjadinlangsungs')
                ->where('id', $request->idPerjadin)
                ->update([
                    'is_acceptBMN' => 'selesai',
                    'is_acceptKeu' => 'selesai',
                    'is_acceptBend' => 'approval-2',
                    'status_pengajuan_detail' => 'approval-2-Bendahara',
                    'admin_Keu' => auth('administrator')->user()->id,
                    'updated_at' => now(),
                ]);

            return redirect()->route('keuangan-perjadin', ['status' => 'verifikasi-2'])->with('success', 'Data Telah diperbaharui dan telah dikirim ke bagian bendahara!');
        } elseif ($action === 'revisi_user') {
            $totalpesertapegawai = $request->numPegawai;
            for ($i = 0; $i < $totalpesertapegawai; $i++) {
                $idpesertapegawai = 'idPesertaPegawai_' . $i;
                $statuspersetujuan = 'statuspegawai_' . $i;
                DB::table('data_perjadinlangsungs')
                    ->where('id', $request->$idpesertapegawai)
                    ->update([
                        'status_persetujuan' => 'Ditolak',
                        'updated_at' => now(),
                    ]);
            }

            $totalnonpesertapegawai = $request->numNonPegawai;
            for ($i = 0; $i < $totalnonpesertapegawai; $i++) {
                $idpesertapegawai = 'idNonPesertaPegawai_' . $i;
                $statuspersetujuan = 'statusnonpegawai_' . $i;
                DB::table('data_perjadinlangsungs')
                    ->where('id', $request->$idpesertapegawai)
                    ->update([
                        'status_persetujuan' => 'Ditolak',
                        'updated_at' => now(),
                    ]);
            }

            $numkebutuhan = $request->numKebutuhan;
            for ($i = 0; $i < $numkebutuhan; $i++) {
                $idKeuangan = 'idKeuanganKebutuhan_' . $i;
                $idKebutuhan = 'idKebutuhan_' . $i;
                $harga = 'nominalKebutuhan_' . $i;
                $pajak = 'pajakKebutuhan_' . $i;
                $pph22 = 'pajakKebutuhan22_' . $i;
                $pph23 = 'pajakKebutuhan23_' . $i;
                $ppn = 'ppnKebutuhan_' . $i;
                $nominal = 'totalKebutuhan_' . $i;
                $statusKesesuaian = 'kesesuaianKebutuhan_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('id', $request->$idKeuangan)
                    ->update([
                        'uang_harian' => $request->$harga,
                        'persen_pajak' => $request->$pajak,
                        'pph22' => $request->$pph22,
                        'pph23' => $request->$pph23,
                        'ppn' => $request->$ppn,
                        'jumlah_harga' => $request->$nominal,
                        'updated_at' => now(),
                    ]);
            }

            DB::table('dokumens')
                ->where('id', $request->idDokumen)
                ->update([
                    'ket' => $request->keterangandokumen,
                    'status_persetujuan' => 'revisi',
                    'updated_at' => now(),
                ]);
            if ($request->statusPerjadin == 'verifikasi-1') {
                DB::table('info_perjadinlangsungs')
                    ->where('id', $request->idPerjadin)
                    ->update([
                        'status_pengajuan' => 'revisi',
                        'is_acceptKeu' => 'revisi-1',
                        'admin_Keu' => auth('administrator')->user()->id,
                        'updated_at' => now(),
                    ]);
                return redirect()->route('keuangan-perjadin', ['status' => 'revisi-1'])->with('success', 'Data Telah diperbaharui dan telah dikirim ke user untuk direvisi!');
            }
            if ($request->statusPerjadin == 'verifikasi-2') {
                DB::table('info_perjadinlangsungs')
                    ->where('id', $request->idPerjadin)
                    ->update([
                        'status_pengajuan' => 'revisi',
                        'is_acceptKeu' => 'revisi-2',
                        'admin_Keu' => auth('administrator')->user()->id,
                        'updated_at' => now(),
                    ]);
                return redirect()->route('keuangan-perjadin', ['status' => 'revisi-2'])->with('success', 'Data Telah diperbaharui dan telah dikirim ke user untuk direvisi!');
            }
        } elseif ($action === 'revisi_HKT') {
            DB::table('dokumens')
                ->where('id', $request->idDokumen)
                ->update([
                    'ket' => $request->keterangandokumen,
                    'status_persetujuan' => 'revisi',
                    'updated_at' => now(),
                ]);
            if ($request->statusPerjadin == 'verifikasi-1') {
                DB::table('info_perjadinlangsungs')
                    ->where('id', $request->idPerjadin)
                    ->update([
                        'status_pengajuan' => 'revisi',
                        'is_acceptKeu' => 'revisi-1',
                        'is_acceptHKT' => 'revisi',
                        'admin_Keu' => auth('administrator')->user()->id,
                        'updated_at' => now(),
                    ]);
                return redirect()->route('keuangan-perjadin', ['status' => 'verifikasi-1'])->with('success', 'Data Telah diperbaharui dan telah dikirim ke HKT untuk direvisi!');
            }
        } elseif ($action === 'tolak') {
            DB::table('info_perjadinlangsungs')
                ->where('id', $request->idPerjadin)
                ->update([
                    'status_pengajuan' => 'ditolak',
                    'is_acceptKeu' => 'ditolak',
                    'admin_Keu' => auth('administrator')->user()->id,
                    'updated_at' => now(),
                ]);

            return redirect()->route('keuangan-perjadin', ['status' => 'verifikasi-1'])->with('success', 'Data Telah anda tolak!');
        }
        // ini untuk tombol simpan
        elseif ($action === 'simpan') {
            $totalpesertapegawai = $request->numPegawai;
            for ($i = 0; $i < $totalpesertapegawai; $i++) {
                $idpesertapegawai = 'idPesertaPegawai_' . $i;
                $statuspersetujuan = 'statuspegawai_' . $i;
                DB::table('data_perjadinlangsungs')
                    ->where('id', $request->$idpesertapegawai)
                    ->update([
                        'status_persetujuan' => $request->$statuspersetujuan,
                        'updated_at' => now(),
                    ]);
            }

            $totalnonpesertapegawai = $request->numNonPegawai;
            for ($i = 0; $i < $totalnonpesertapegawai; $i++) {
                $idpesertapegawai = 'idNonPesertaPegawai_' . $i;
                $statuspersetujuan = 'statusnonpegawai_' . $i;
                DB::table('data_perjadinlangsungs')
                    ->where('id', $request->$idpesertapegawai)
                    ->update([
                        'status_persetujuan' => $request->$statuspersetujuan,
                        'updated_at' => now(),
                    ]);
            }

            $numkebutuhan = $request->numKebutuhan;
            for ($i = 0; $i < $numkebutuhan; $i++) {
                $idKeuangan = 'idKeuanganKebutuhan_' . $i;
                $idKebutuhan = 'idKebutuhan_' . $i;
                $harga = 'nominalKebutuhan_' . $i;
                $pajak = 'pajakKebutuhan_' . $i;
                $pph22 = 'pajakKebutuhan22_' . $i;
                $pph23 = 'pajakKebutuhan23_' . $i;
                $ppn = 'ppnKebutuhan_' . $i;
                $nominal = 'totalKebutuhan_' . $i;
                $statusKesesuaian = 'kesesuaianKebutuhan_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('id', $request->$idKeuangan)
                    ->update([
                        'uang_harian' => $request->$harga,
                        'persen_pajak' => $request->$pajak,
                        'pph22' => $request->$pph22,
                        'pph23' => $request->$pph23,
                        'ppn' => $request->$ppn,
                        'jumlah_harga' => $request->$nominal,
                        'updated_at' => now(),
                    ]);
            }

            DB::table('dokumens')
                ->where('id', $request->idDokumen)
                ->update([
                    'ket' => $request->keterangandokumen,
                    'status_persetujuan' => $request->persetujuandokumen,
                    'updated_at' => now(),
                ]);

            return redirect()->route('keuangan-perjadin', ['status' => $request->statusPerjadin])->with('success', 'Data Telah diperbaharui dan telah dikirim ke user untuk direvisi!');
        }
    }

    public function storeBendahara(Request $request)

    {

        $action = $request->input('action');

        if ($action === 'approval') {
            $totalpesertapegawai = $request->numPegawai;
            for ($i = 0; $i < $totalpesertapegawai; $i++) {
                $idpesertapegawai = 'idKeuangan_' . $i;
                $uangharian = 'uang_harian' . $i;
                $uangfullday = 'uang_harian_fullday' . $i;
                $uangfullboard = 'uang_harian_fullboard' . $i;
                $uangrepresentasi = 'uang_representasi' . $i;
                $totaluangpeserta = 'total_' . $i;
                $tglbayar = 'tglbayar_' . $i;
                $refSBM = 'sbmPegawai_' . $i;
                $akunPeserta = 'akunPegawai_' . $i;
                $statuspersetujuan = 'statuspegawai_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('data_perjadinlangsungs', $request->$idpesertapegawai)
                    ->update([
                        'uang_harian' => $request->$uangharian,
                        'uang_harian_fullday' => $request->$uangfullday,
                        'uang_harian_fullboard' => $request->$uangfullboard,
                        'uang_representasi' => $request->$uangrepresentasi,
                        'jumlah_harga' => $request->$totaluangpeserta,
                        'tgl_bayar' => $request->$tglbayar,
                        'ref_sbm' => $request->$refSBM,
                        'akun_x_rkakl' => $request->$akunPeserta,
                        'status' => $request->$statuspersetujuan,
                        'updated_at' => now(),
                    ]);
            }

            $totalnonpesertapegawai = $request->numNonPegawai;
            for ($i = 0; $i < $totalnonpesertapegawai; $i++) {
                $idNonPeserta = 'idNonPesertaPegawai_' . $i;
                $nominalNonPeserta = 'nominalNon_' . $i;
                $totaluangnonpeserta = 'totalNon_' . $i;
                $tglbayar = 'tglbayarnon_' . $i;
                $refSBMnonpeserta = 'sbmNonPegawai_' . $i;
                $akunnonpeserta = 'akunNonPegawai_' . $i;
                $statuspembayarannonpesera = 'statusnonpegawai_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('data_perjadinlangsungs', $request->$idNonPeserta)
                    ->update([
                        'uang_harian' => $request->$nominalNonPeserta,
                        'jumlah_harga' => $request->$totaluangnonpeserta,
                        'tgl_bayar' => $request->$tglbayar,
                        'ref_sbm' => $request->$refSBMnonpeserta,
                        'akun_x_rkakl' => $request->$akunnonpeserta,
                        'status' => $request->$statuspembayarannonpesera,
                        'updated_at' => now(),
                    ]);
            }

            $numkebutuhan = $request->numKebutuhan;
            for ($i = 0; $i < $numkebutuhan; $i++) {
                $idKebutuhan = 'idKebutuhan_' . $i;
                $harga = 'nominalKebutuhan_' . $i;
                $pajak = 'pajakKebutuhan_' . $i;
                $pph22 = 'pajakKebutuhan22_' . $i;
                $pph23 = 'pajakKebutuhan23_' . $i;
                $ppn = 'ppnkebutuhan_' . $i;
                $nominal = 'totalKebutuhan_' . $i;
                $tglbayar = 'tglbayarKebutuhan_' . $i;
                $sbmKebutuhan = 'sbmKebutuhan_' . $i;
                $akunKebutuhan = 'akunKebutuhan_' . $i;
                $statusKesesuaian = 'kesesuaian_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('kebutuhan_id', $request->$idKebutuhan)
                    ->update([
                        'uang_harian' => $request->$harga,
                        'persen_pajak' => $request->$pajak,
                        'pph22' => $request->$pph22,
                        'pph23' => $request->$pph23,
                        'ppn' => $request->$ppn,
                        'jumlah_harga' => $request->$nominal,
                        'tgl_bayar' => $request->$tglbayar,
                        'ref_sbm' => $request->$sbmKebutuhan,
                        'akun_x_rkakl' => $request->$akunKebutuhan,
                        'status' => $request->$statusKesesuaian,
                        'updated_at' => now(),
                    ]);
            }

            DB::table('info_perjadinlangsungs')
                ->where('id', $request->idPerjadin)
                ->update([
                    'is_acceptKeu' => 'verifikasi-2',
                    'is_acceptBend' => 'approval-2',
                    'status_pengajuan' => 'proses',
                    'admin_Bend' => auth('administrator')->user()->id,
                    'updated_at' => now(),
                ]);


            return redirect()->route('bendahara-perjadin', ['status' => 'approval-1'])->with('success', 'Data Telah diperbaharui dan disetujui!');
        } elseif ($action === 'approval-2') {
            $totalpesertapegawai = $request->numPegawai;
            for ($i = 0; $i < $totalpesertapegawai; $i++) {
                $idpesertapegawai = 'idKeuangan_' . $i;
                $uangharian = 'uang_harian' . $i;
                $uangfullday = 'uang_harian_fullday' . $i;
                $uangfullboard = 'uang_harian_fullboard' . $i;
                $uangrepresentasi = 'uang_representasi' . $i;
                $pajakPeserta = 'pajak_' . $i;
                $pph22 = 'pph22_' . $i;
                $pph23 = 'pph23_' . $i;
                $ppn = 'ppn_' . $i;
                $totaluangpeserta = 'total_' . $i;
                $tglbayar = 'tglbayar_' . $i;
                $refSBM = 'sbmPegawai_' . $i;
                $akunPeserta = 'akunPegawai_' . $i;
                $statuspersetujuan = 'statuspegawai_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('data_perjadinlangsungs', $request->$idpesertapegawai)
                    ->update([
                        'uang_harian' => $request->$uangharian,
                        'uang_harian_fullday' => $request->$uangfullday,
                        'uang_harian_fullboard' => $request->$uangfullboard,
                        'uang_representasi' => $request->$uangrepresentasi,
                        'persen_pajak' => $request->$pajakPeserta,
                        'pph22' => $request->$pph22,
                        'pph23' => $request->$pph23,
                        'ppn' => $request->$ppn,
                        'jumlah_harga' => $request->$totaluangpeserta,
                        'tgl_bayar' => $request->$tglbayar,
                        'ref_sbm' => $request->$refSBM,
                        'akun_x_rkakl' => $request->$akunPeserta,
                        'status' => $request->$statuspersetujuan,
                        'updated_at' => now(),
                    ]);
            }

            $totalnonpesertapegawai = $request->numNonPegawai;
            for ($i = 0; $i < $totalnonpesertapegawai; $i++) {
                $idNonPeserta = 'idNonPesertaPegawai_' . $i;
                $nominalNonPeserta = 'nominalNon_' . $i;
                $pajakNonPeserta = 'pajakNon_' . $i;
                $pph22 = 'pphNon22_' . $i;
                $pph23 = 'pphNon23_' . $i;
                $ppn = 'ppnNon_' . $i;
                $totaluangnonpeserta = 'totalNon_' . $i;
                $tglbayar = 'tglbayarnon_' . $i;
                $refSBMnonpeserta = 'sbmNonPegawai_' . $i;
                $akunnonpeserta = 'akunNonPegawai_' . $i;
                $statuspembayarannonpesera = 'statusnonpegawai_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('data_perjadinlangsungs', $request->$idNonPeserta)
                    ->update([
                        'uang_harian' => $request->$nominalNonPeserta,
                        'persen_pajak' => $request->$pajakNonPeserta,
                        'pph22' => $request->$pph22,
                        'pph23' => $request->$pph23,
                        'ppn' => $request->$ppn,
                        'jumlah_harga' => $request->$totaluangnonpeserta,
                        'tgl_bayar' => $request->$tglbayar,
                        'ref_sbm' => $request->$refSBMnonpeserta,
                        'akun_x_rkakl' => $request->$akunnonpeserta,
                        'status' => $request->$statuspembayarannonpesera,
                        'updated_at' => now(),
                    ]);
            }

            $numkebutuhan = $request->numKebutuhan;
            for ($i = 0; $i < $numkebutuhan; $i++) {
                $idKebutuhan = 'idKebutuhan_' . $i;
                $harga = 'nominalKebutuhan_' . $i;
                $pajak = 'pajakKebutuhan_' . $i;
                $pph22 = 'pajakKebutuhan22_' . $i;
                $pph23 = 'pajakKebutuhan23_' . $i;
                $ppn = 'ppnkebutuhan_' . $i;
                $nominal = 'totalKebutuhan_' . $i;
                $tglbayar = 'tglbayarKebutuhan_' . $i;
                $sbmKebutuhan = 'sbmKebutuhan_' . $i;
                $akunKebutuhan = 'akunKebutuhan_' . $i;
                $statusKesesuaian = 'kesesuaian_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('kebutuhan_id', $request->$idKebutuhan)
                    ->update([
                        'uang_harian' => $request->$harga,
                        'persen_pajak' => $request->$pajak,
                        'pph22' => $request->$pph22,
                        'pph23' => $request->$pph23,
                        'ppn' => $request->$ppn,
                        'jumlah_harga' => $request->$nominal,
                        'tgl_bayar' => $request->$tglbayar,
                        'ref_sbm' => $request->$sbmKebutuhan,
                        'akun_x_rkakl' => $request->$akunKebutuhan,
                        'status' => $request->$statusKesesuaian,
                        'updated_at' => now(),
                    ]);
            }

            DB::table('info_perjadinlangsungs')
                ->where('id', $request->idPerjadin)
                ->update([
                    'is_acceptKeu' => 'selesai',
                    'is_acceptBend' => 'selesai',
                    'status_pengajuan' => 'selesai',
                    'admin_Bend' => auth('administrator')->user()->id,
                    'updated_at' => now(),
                ]);
            DB::table('peminjaman_kendaraan_dinas')
                ->where('info_perjadinlangsung', $request->idPerjadin)
                ->update([
                    'status' => 'selesai',
                    'updated_at' => now(),
                ]);
            $id = $request->input('idPerjadin'); // 'id' sesuai dengan parameter dalam URL
            if (!$id) {
                dd('Data tidak ditemukan.');
            }

            return redirect()->route('bendahara-perjadin', ['status' => 'selesai'])->with('success', 'Data Telah diperbaharui dan disetujui dan perjalanan dinas selesai!');
        } elseif ($action === 'tolak') {
            DB::table('info_perjadinlangsungs')
                ->where('id', $request->idPerjadin)
                ->update([
                    'status_pengajuan' => 'ditolak',
                    'is_acceptKeu' => 'ditolak',
                    'is_acceptBend' => 'ditolak',
                    'admin_Bend' => auth('administrator')->user()->id,
                    'updated_at' => now(),
                ]);

            return redirect()->route('bendahara-perjadin', ['status' => 'ditolak'])->with('success', 'Data Telah anda tolak!');
        } elseif ($action === 'simpan') {
            $totalpesertapegawai = $request->numPegawai;
            for ($i = 0; $i < $totalpesertapegawai; $i++) {
                $idpesertapegawai = 'idKeuangan_' . $i;
                $nominalPeserta = 'nominal_' . $i;
                $pajakPeserta = 'pajak_' . $i;
                $pph22 = 'pph22_' . $i;
                $pph23 = 'pph23_' . $i;
                $ppn = 'ppn_' . $i;
                $totaluangpeserta = 'total_' . $i;
                $tglbayar = 'tglbayar_' . $i;
                $refSBM = 'sbmPegawai_' . $i;
                $akunPeserta = 'akunPegawai_' . $i;
                $statuspersetujuan = 'statuspegawai_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('data_perjadinlangsungs', $request->$idpesertapegawai)
                    ->update([
                        'uang_harian' => $request->$nominalPeserta,
                        'persen_pajak' => $request->$pajakPeserta,
                        'pph22' => $request->$pph22,
                        'pph23' => $request->$pph23,
                        'ppn' => $request->$ppn,
                        'jumlah_harga' => $request->$totaluangpeserta,
                        'tgl_bayar' => $request->$tglbayar,
                        'ref_sbm' => $request->$refSBM,
                        'akun_x_rkakl' => $request->$akunPeserta,
                        'status' => $request->$statuspersetujuan,
                        'updated_at' => now(),
                    ]);
            }

            $totalnonpesertapegawai = $request->numNonPegawai;
            for ($i = 0; $i < $totalnonpesertapegawai; $i++) {
                $idNonPeserta = 'idNonPesertaPegawai_' . $i;
                $nominalNonPeserta = 'nominalNon_' . $i;
                $pajakNonPeserta = 'pajakNon_' . $i;
                $pph22 = 'pphNon22_' . $i;
                $pph23 = 'pphNon23_' . $i;
                $ppn = 'ppnNon_' . $i;
                $totaluangnonpeserta = 'totalNon_' . $i;
                $tglbayar = 'tglbayarnon_' . $i;
                $refSBMnonpeserta = 'sbmNonPegawai_' . $i;
                $akunnonpeserta = 'akunNonPegawai_' . $i;
                $statuspembayarannonpesera = 'statusnonpegawai_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('data_perjadinlangsungs', $request->$idNonPeserta)
                    ->update([
                        'uang_harian' => $request->$nominalNonPeserta,
                        'persen_pajak' => $request->$pajakNonPeserta,
                        'pph22' => $request->$pph22,
                        'pph23' => $request->$pph23,
                        'ppn' => $request->$ppn,
                        'jumlah_harga' => $request->$totaluangnonpeserta,
                        'tgl_bayar' => $request->$tglbayar,
                        'ref_sbm' => $request->$refSBMnonpeserta,
                        'akun_x_rkakl' => $request->$akunnonpeserta,
                        'status' => $request->$statuspembayarannonpesera,
                        'updated_at' => now(),
                    ]);
            }

            $numkebutuhan = $request->numKebutuhan;
            for ($i = 0; $i < $numkebutuhan; $i++) {
                $idKebutuhan = 'idKebutuhan_' . $i;
                $harga = 'nominalKebutuhan_' . $i;
                $pajak = 'pajakKebutuhan_' . $i;
                $pph22 = 'pajakKebutuhan22_' . $i;
                $pph23 = 'pajakKebutuhan23_' . $i;
                $ppn = 'ppnkebutuhan_' . $i;
                $nominal = 'totalKebutuhan_' . $i;
                $tglbayar = 'tglbayarKebutuhan_' . $i;
                $sbmKebutuhan = 'sbmKebutuhan_' . $i;
                $akunKebutuhan = 'akunKebutuhan_' . $i;
                $statusKesesuaian = 'kesesuaian_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('kebutuhan_id', $request->$idKebutuhan)
                    ->update([
                        'uang_harian' => $request->$harga,
                        'persen_pajak' => $request->$pajak,
                        'pph22' => $request->$pph22,
                        'pph23' => $request->$pph23,
                        'ppn' => $request->$ppn,
                        'jumlah_harga' => $request->$nominal,
                        'tgl_bayar' => $request->$tglbayar,
                        'ref_sbm' => $request->$sbmKebutuhan,
                        'akun_x_rkakl' => $request->$akunKebutuhan,
                        'status' => $request->$statusKesesuaian,
                        'updated_at' => now(),
                    ]);
            }

            return redirect()->route('bendahara-perjadin', ['status' => $request->statusPerjadin])->with('success', 'Data Telah Disimpan!');
        }
    }
    public function storeFasilitasBendahara(Request $request)
    {
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

        $kebutuhan_max = Kebutuhan::max('id');
        DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'kebutuhan_id' => $kebutuhan_max,
            'status' => 'Menunggu Persetujuan Bendahara',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $id = $request->info_perjadinlangsung;
        return redirect()->route('bendahara-perjadin-fasilitas', ['id' => $id])->with('success', 'Fasilitas berhasil ditambahkan!');
    }

    public function storeFasilitasDetailBendahara(Request $request)
    {
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

        $kebutuhan_max = Kebutuhan::max('id');
        DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'data_perjadinlangsungs' => $request->data_perjadinlangsungs,
            'kebutuhan_id' => $kebutuhan_max,
            'status' => 'Menunggu Persetujuan Bendahara',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $id = $request->info_perjadinlangsung;
        return redirect()->route('bendahara-perjadin-fasilitas', ['id' => $id])->with('success', 'Fasilitas berhasil ditambahkan!');
    }
    public function storeFasilitasBMN(Request $request)
    {
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

        $kebutuhan_max = Kebutuhan::max('id');
        DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'kebutuhan_id' => $kebutuhan_max,
            'status' => 'Menunggu Persetujuan BMN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        $id = $request->info_perjadinlangsung;
        return redirect()->route('detail-mobilitas-perjadin', ['id' => $id])->with('success', 'Fasilitas berhasil ditambahkan!');
    }
    public function storeFasilitasDetailBMN(Request $request)
    {
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

        $kebutuhan_max = Kebutuhan::max('id');
        DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'kebutuhan_id' => $kebutuhan_max,
            'status' => 'Menunggu Persetujuan BMN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        $id = $request->info_perjadinlangsung;
        return redirect()->route('detail-mobilitas-perjadin', ['id' => $id])->with('success', 'Fasilitas berhasil ditambahkan!');
    }


    public function storeSurtug(Request $request)
    {
        $id = $request->input('idPerjadin'); // 'id' sesuai dengan parameter dalam URL

        $exists = DB::table('surtug_perjadinlangsungs')->where('id_perjadinlangsung', $id)->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Surat Tugas sudah ada untuk perjalanan dinas ini.');
        }

        DB::table('surtug_perjadinlangsungs')->Insert([
            'id_perjadinlangsung' => $request->idPerjadin,
            'perihal' => $request->perihal,
            'paragraf_1' => $request->paragraf1,
            'paragraf_2' => $request->paragraf2,
            'paragraf_3' => $request->paragraf3,
        ]);
        $id = $request->idPerjadin;
        return redirect()->route('surtug-detail-HKT-perjadin', ['id' => $id])->with('success', 'Surat Tugas Berhasil Ditambahkan!');
    }


    public function ConvertSurtug($id)
    {
        if (!$id) {
            // Handle jika data tidak ditemukan
            dd('Data tidak ditemukan.');
        }

        $pesertaPegawais = DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->select('pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'pegawais.NIP_NIK')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->where(function ($query) {
                $query->where('data_perjadinlangsungs.status_pegawai', '!=', 'Supir')
                    ->orWhere(function ($query) {
                        $query->where('data_perjadinlangsungs.status_pegawai', '=', 'Supir')
                            ->whereNotExists(function ($query) {
                                $query->select(DB::raw(1))
                                    ->from('data_perjadinlangsungs as dp2')
                                    ->whereColumn('dp2.pegawai_id', 'data_perjadinlangsungs.pegawai_id')
                                    ->where('dp2.status_pegawai', '!=', 'Supir');
                            });
                    });
            })
            ->orderBy('data_perjadinlangsungs.status_pegawai', 'asc')
            ->get();

        $pesertaNonPegawais = DB::table('data_perjadinlangsungs')
            ->join('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
            ->select('non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'data_perjadinlangsungs.status_pegawai')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();
        $pengemudi = DB::table('pegawais')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->join('peminjaman_kendaraan_dinas', 'peminjaman_kendaraan_dinas.pegawai_id', '=', 'pegawais.id')
            ->join('info_perjadinlangsungs', 'peminjaman_kendaraan_dinas.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('pegawais.id', 'pegawais.nama_lengkap', 'jabatans.nama_jabatan', 'pegawais.NIP_NIK', 'pegawais.pangkat', 'pegawais.golongan')
            ->where('jabatans.nama_jabatan', 'Pengemudi')
            ->where('peminjaman_kendaraan_dinas.info_perjadinlangsung', $id)
            ->get();
        $surat = DB::table('surtug_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'surtug_perjadinlangsungs.id_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('surtug_perjadinlangsungs.nomor_surat', 'surtug_perjadinlangsungs.perihal', 'surtug_perjadinlangsungs.paragraf_1', 'surtug_perjadinlangsungs.paragraf_2', 'surtug_perjadinlangsungs.paragraf_3')
            ->where('surtug_perjadinlangsungs.id_perjadinlangsung', $id)
            ->get();

        $datas = [
            'pesertaPegawais' => $pesertaPegawais,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'perjadin' => Info_perjadinlangsung::find($id),
            'surtugs' => $surat,
        ];
        // Lakukan proses pembuatan file PDF
        $pdf = PDF::loadView('admin.perjadin.HKT.surtug_detail', compact('datas'));
        $pdf->setPaper('A4', 'portrait');

        // Simpan file PDF ke penyimpanan
        $filePath = $pdf->output();
        Storage::disk('public')->put('dokumen-perjadins/surtug.pdf', $filePath);

        // Stream file PDF ke browser
        return $pdf->stream('surtug.pdf');
    }

    public function StoreSurtugPDF(Request $request)
    {
        $id = $request->input('idPerjadin'); // 'id' sesuai dengan parameter dalam URL
        if (!$id) {
            dd('Data tidak ditemukan.');
        }

        $pesertaPegawais = DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->select('pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'pegawais.NIP_NIK')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();

        $pesertaNonPegawais = DB::table('data_perjadinlangsungs')
            ->join('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
            ->select('non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'data_perjadinlangsungs.status_pegawai')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();

        $pengemudi = DB::table('pegawais')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->join('peminjaman_kendaraan_dinas', 'peminjaman_kendaraan_dinas.pegawai_id', '=', 'pegawais.id')
            ->join('info_perjadinlangsungs', 'peminjaman_kendaraan_dinas.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('pegawais.id', 'pegawais.nama_lengkap', 'jabatans.nama_jabatan', 'pegawais.NIP_NIK', 'pegawais.pangkat', 'pegawais.golongan')
            ->where('jabatans.nama_jabatan', 'Pengemudi')
            ->where('peminjaman_kendaraan_dinas.info_perjadinlangsung', $id)
            ->get();

        $surat = DB::table('surtug_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'surtug_perjadinlangsungs.id_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('surtug_perjadinlangsungs.nomor_surat', 'surtug_perjadinlangsungs.perihal', 'surtug_perjadinlangsungs.paragraf_1', 'surtug_perjadinlangsungs.paragraf_2', 'surtug_perjadinlangsungs.paragraf_3')
            ->where('surtug_perjadinlangsungs.id_perjadinlangsung', $id)
            ->get();

        $datas = [
            'pesertaPegawais' => $pesertaPegawais,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'perjadin' => Info_perjadinlangsung::find($id),
            'pengemudis' => $pengemudi,
            'surtugs' => $surat,
        ];

        // Lakukan proses pembuatan file PDF
        $pdf = PDF::loadView('admin.perjadin.HKT.surtug_detail', compact('datas'));
        $pdf->setPaper('A4', 'portrait');

        // Simpan file PDF ke penyimpanan
        $filePath = $pdf->output();
        Storage::disk('public')->put('dokumen-perjadins/surtug.pdf', $filePath);

        // Simpan data ke tabel 'dokumens'
        DB::table('dokumens')
            ->where('info_perjadinlangsung_id', $id)
            ->update([
                'surat_tugas' => 'dokumen-perjadins/surtug.pdf',
                'status_persetujuan' => 'pengajuan',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        return redirect()->route('HKT-perjadin', ['status' => 'pengajuan'])->with('success', 'Surat Tugas telah berhasil dibuat!');
    }

    public function UploadSurtug(Request $request)
    {


        $validationData = $request->validate([
            'surat_tugas' => 'required|mimes:pdf|file|max:2048',
        ]);

        if (empty($validationData)) {
            dd('Gagal Upload');
        }

        $id = $request->input('idPerjadin'); // 'id' sesuai dengan parameter dalam URL
        if (!$id) {
            dd('Data tidak ditemukan.');
        }
        $perjadin = DB::table('info_perjadinlangsungs')->where('id', $id)->first();
        if (!$perjadin) {
            dd('Data tidak ditemukan.');
        }
        DB::table('dokumens')
            ->where('info_perjadinlangsung_id', $id)
            ->update([
                'surat_tugas' => $validationData['surat_tugas'] = $request->file('surat_tugas')->store('dokumen-perjadins', 'public'),
                'status_persetujuan' => 'pengajuan',
                'updated_at' => now(),
            ]);
        DB::table('info_perjadinlangsungs')
            ->where('id', $id)
            ->whereNull('is_acceptKeu')
            ->update([
                'is_acceptBend' => 'approval-1',
                'status_pengajuan_detail' => 'Approval-1-Bendahara',
                'kode_surat_tugas' => $request->nomor_surtug,
                'is_acceptHKT' => 'selesai',
                'updated_at' => now(),
            ]);
        DB::table('surtug_perjadinlangsungs')
            ->where('id_perjadinlangsung', $id)
            ->update([
                'nomor_surat' => $request->nomor_surtug,
                'tgl_surat_dibuat' => $request->tgl_dibuat,
                'updated_at' => now(),
            ]);


        return redirect()->route('HKT-perjadin', ['status' => 'pengajuan'])->with('success', 'Surat Tugas telah berhasil di Upload!');
    }


    // ini function untuk edit saat di button edit di index.blade

    public function detail_surtug_perjadin_HKT_edit($id)
    {
        $surat = DB::table('surtug_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'surtug_perjadinlangsungs.id_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('surtug_perjadinlangsungs.nomor_surat', 'surtug_perjadinlangsungs.perihal', 'surtug_perjadinlangsungs.paragraf_1', 'surtug_perjadinlangsungs.paragraf_2', 'surtug_perjadinlangsungs.paragraf_3')
            ->where('surtug_perjadinlangsungs.id_perjadinlangsung', $id)
            ->get();

        return view('admin.perjadin.HKT.edit_surtug', [
            'title' => 'Edit Surat Tugas',
            'perjadin' => Info_perjadinlangsung::find($id),
            'surtugs' => $surat,
        ]);
    }


    public function EditSurtug(Request $request)
    {
        $id = $request->input('idPerjadin'); // 'id' sesuai dengan parameter dalam URL
        if (!$id) {
            dd('Data tidak ditemukan.');
        }
        DB::table('surtug_perjadinlangsungs')
            ->where('id_perjadinlangsung', $id)
            ->update([
                'perihal' => $request->perihal,
                'paragraf_1' => $request->paragraf1,
                'paragraf_2' => $request->paragraf2,
                'paragraf_3' => $request->paragraf3,
            ]);

        return redirect()->route('surtug-detail-HKT-perjadin', ['id' => $id])->with('success', 'Surat Tugas Berhasil Diubah!');
    }

    public function TolakPerjadin(Request $request)
    {
        DB::table('info_perjadinlangsungs')
            ->where('id', $request->idPerjadin)
            ->update([
                'status_pengajuan' => 'ditolak',
                'is_acceptHKT' => 'ditolak',
                'alasan_penolakan' =>  $request->alasan,
                'updated_at' => now(),
            ]);

        return redirect()->route('HKT-perjadin', ['status' => 'pengajuan'])->with('success', 'Pengajuan Telah Berhasil Ditolak!');
    }




    public function CetakSPPD($id)
    {
        if (!$id) {
            // Handle jika data tidak ditemukan
            dd('Data tidak ditemukan.');
        }

        $pesertaPegawais = DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->select('pegawais.id', 'pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'pegawais.NIP_NIK')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();
        $pesertaNonPegawais = DB::table('data_perjadinlangsungs')
            ->join('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
            ->select('non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'data_perjadinlangsungs.status_pegawai')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();
        $pengemudi = DB::table('pegawais')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->join('peminjaman_kendaraan_dinas', 'peminjaman_kendaraan_dinas.pegawai_id', '=', 'pegawais.id')
            ->join('info_perjadinlangsungs', 'peminjaman_kendaraan_dinas.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('pegawais.id', 'pegawais.nama_lengkap', 'jabatans.nama_jabatan', 'pegawais.NIP_NIK', 'pegawais.pangkat', 'pegawais.golongan')
            ->where('jabatans.nama_jabatan', 'Pengemudi')
            ->where('peminjaman_kendaraan_dinas.info_perjadinlangsung', $id)
            ->get();
        $surat = DB::table('surtug_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'surtug_perjadinlangsungs.id_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->join('keuangan_perjadinlangsungs', 'info_perjadinlangsungs.id', '=', 'keuangan_perjadinlangsungs.info_perjadinlangsung') // Memperbaiki klausa ON dengan menggunakan kolom yang sesuai
            ->select('surtug_perjadinlangsungs.nomor_surat', 'surtug_perjadinlangsungs.perihal', 'surtug_perjadinlangsungs.tgl_surat_dibuat', 'info_perjadinlangsungs.tanggal_surat', 'info_perjadinlangsungs.tgl_mulai', 'surtug_perjadinlangsungs.paragraf_1', 'surtug_perjadinlangsungs.paragraf_2', 'surtug_perjadinlangsungs.paragraf_3', 'info_perjadinlangsungs.provinsi', 'info_perjadinlangsungs.kabupaten_kota', 'keuangan_perjadinlangsungs.uang_harian_fullday',  'keuangan_perjadinlangsungs.uang_harian', 'keuangan_perjadinlangsungs.tgl_bayar', 'keuangan_perjadinlangsungs.jumlah_harga', 'keuangan_perjadinlangsungs.akun_x_rkakl') // Mengubah pemilihan kolom agar sesuai dengan join yang dilakukan
            ->where('surtug_perjadinlangsungs.id_perjadinlangsung', $id)
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

        $datas = [
            'pesertaPegawais' => $pesertaPegawais,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'perjadin' => Info_perjadinlangsung::find($id),
            'sppd' => $surat,
            'akuns' => $akuns,


        ];

        // Lakukan proses pembuatan file PDF
        $pdf = PDF::loadView('admin.perjadin.bendahara.sppd', compact('datas'));
        $pdf->setPaper('A4', 'portrait');

        // Simpan file PDF ke penyimpanan
        $filePath = $pdf->output();
        Storage::disk('public')->put('dokumen-perjadins/sppd.pdf', $filePath);

        // Stream file PDF ke browser
        return $pdf->stream('sppd.pdf');
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getDokumen($filename)
    {
        $path = storage_path('app/public/dokumen-perjadins/' . $filename);
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->file($path);
    }
}
