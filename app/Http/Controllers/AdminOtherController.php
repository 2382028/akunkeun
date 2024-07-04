<?php

namespace App\Http\Controllers;

use App\Models\Dokumen_permohonan;
use App\Models\Kebutuhan;
use App\Models\Komponen_diperlukan;
use App\Models\Laporan_perjadinkegiatan;
use App\Models\Info_perjadinlangsung;
use App\Models\Dokumen;
use App\Models\Ref_sbm;
use App\Models\Versi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOtherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.other.pengaturan', [
            'title' => 'Pengaturan Versi',
            'versis' => Versi::all()
        ]);
    }

    public function indexLaporan()
    {
        return view('admin.other.laporan', [
            'title' => 'Laporan',
        ]);
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
        $perjadin = DB::table('info_perjadinlangsungs')
            ->join('data_perjadinlangsungs', 'data_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->join('keuangan_perjadinlangsungs', 'data_perjadinlangsungs.id', '=', 'keuangan_perjadinlangsungs.data_perjadinlangsungs')
            ->join('dokumens', 'info_perjadinlangsungs.id', '=', 'dokumens.info_perjadinlangsung_id')
            ->select('info_perjadinlangsungs.id AS idPerjadin', 'pegawais.nama_lengkap',  'info_perjadinlangsungs.nama_kegiatan', 'info_perjadinlangsungs.tgl_mulai', 'info_perjadinlangsungs.tgl_selesai', 'info_perjadinlangsungs.alamat', 'keuangan_perjadinlangsungs.akun_x_rkakl AS MAK', 'keuangan_perjadinlangsungs.id as IdKeuangan', 'keuangan_perjadinlangsungs.uang_harian', 'keuangan_perjadinlangsungs.persen_pajak', 'keuangan_perjadinlangsungs.pph22', 'keuangan_perjadinlangsungs.pph23', 'keuangan_perjadinlangsungs.ppn', 'keuangan_perjadinlangsungs.jumlah_harga', 'keuangan_perjadinlangsungs.tgl_kwitansi', 'keuangan_perjadinlangsungs.no_kwitansi', 'keuangan_perjadinlangsungs.spby', 'keuangan_perjadinlangsungs.status as status_pembayaran', 'keuangan_perjadinlangsungs.tgl_bayar as transaksi', 'dokumens.surat_undangan', 'dokumens.surat_tugas', 'dokumens.SPPD', 'dokumens.hasil as lap_perjadin', 'dokumens.lap_pengeluaran', 'dokumens.updated_at AS tanggal_dokumen', 'info_perjadinlangsungs.status_pengajuan', 'keuangan_perjadinlangsungs.drpp', 'keuangan_perjadinlangsungs.jurnal')
            ->whereBetween('info_perjadinlangsungs.tgl_mulai', [$mulai, $sampai])
            ->get();
        $perjadinNon = DB::table('info_perjadinlangsungs')
            ->join('data_perjadinlangsungs', 'data_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->join('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
            ->join('keuangan_perjadinlangsungs', 'data_perjadinlangsungs.id', '=', 'keuangan_perjadinlangsungs.data_perjadinlangsungs')
            ->join('dokumens', 'info_perjadinlangsungs.id', '=', 'dokumens.info_perjadinlangsung_id')
            ->select('info_perjadinlangsungs.id AS idPerjadin', 'non_pegawais.nama_lengkap',  'info_perjadinlangsungs.nama_kegiatan', 'info_perjadinlangsungs.tgl_mulai', 'info_perjadinlangsungs.tgl_selesai', 'info_perjadinlangsungs.alamat', 'keuangan_perjadinlangsungs.akun_x_rkakl AS MAK', 'keuangan_perjadinlangsungs.id as IdKeuangan', 'keuangan_perjadinlangsungs.uang_harian', 'keuangan_perjadinlangsungs.persen_pajak', 'keuangan_perjadinlangsungs.pph22', 'keuangan_perjadinlangsungs.pph23', 'keuangan_perjadinlangsungs.ppn', 'keuangan_perjadinlangsungs.jumlah_harga', 'keuangan_perjadinlangsungs.tgl_kwitansi', 'keuangan_perjadinlangsungs.no_kwitansi', 'keuangan_perjadinlangsungs.spby', 'keuangan_perjadinlangsungs.status as status_pembayaran', 'keuangan_perjadinlangsungs.tgl_bayar as transaksi', 'dokumens.surat_undangan', 'dokumens.surat_tugas', 'dokumens.SPPD', 'dokumens.hasil as lap_perjadin', 'dokumens.lap_pengeluaran', 'dokumens.updated_at AS tanggal_dokumen', 'info_perjadinlangsungs.status_pengajuan', 'keuangan_perjadinlangsungs.drpp', 'keuangan_perjadinlangsungs.jurnal')
            ->whereBetween('info_perjadinlangsungs.tgl_mulai', [$mulai, $sampai])
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
        $fasilitas = DB::table('kebutuhans')
            ->join('keuangan_perjadinlangsungs', 'kebutuhans.id', '=', 'keuangan_perjadinlangsungs.kebutuhan_id')
            ->select('kebutuhans.id', 'kebutuhans.nama', 'kebutuhans.satuan', 'kebutuhans.jumlah_frekuensi',  'kebutuhans.satuan', 'keuangan_perjadinlangsungs.jumlah_harga', 'keuangan_perjadinlangsungs.info_perjadinlangsung', 'keuangan_perjadinlangsungs.status')
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
            ->where('keuangan_perjadinlangsungs.status', 'Belum Dibayarkan')
            ->whereBetween('info_perjadinlangsungs.tgl_mulai', [$mulai, $sampai])
            ->count();
        $totalblmdibayarkan = DB::table('keuangan_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'keuangan_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->where('keuangan_perjadinlangsungs.status', 'Belum Dibayarkan')
            ->whereBetween('info_perjadinlangsungs.tgl_mulai', [$mulai, $sampai])
            ->sum('keuangan_perjadinlangsungs.jumlah_harga');
        // ddd($perjadin);
        return view('admin.other.perjadin', [
            'title' => 'Laporan Perjalanan Dinas',
            'perjadins' => $perjadin,
            'perjadinNons' => $perjadinNon,
            'akuns' => $akuns,
            'fasilitas' => $fasilitas,
            'blmDibayarkan' => $blmdibayarkan,
            'tdkDibayarkan' => $tdkdibayarkan,
            'dibayarkan' => $dibayarkan,
            'totaldibayarkan' => $totaldibayarkan,
            'totaltdkdibayarkan' => $totaltdkdibayarkan,
            'totalblmdibayarkan' => $totalblmdibayarkan,
        ]);
    }

    public function laporanKegiatan($mulai, $sampai)
    {
        $kegiatans = DB::table('data_perjadinkegiatans')
            ->join('keuangan_perjadinkegiatans', 'data_perjadinkegiatans.id', '=', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
            ->join('perangkat_acaras', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
            ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
            ->select('data_perjadinkegiatans.id AS idKegiatan', 'data_perjadinkegiatans.nama_kegiatan', 'pegawais.nama_lengkap', 'perangkat_acaras.sebagai', 'keuangan_perjadinkegiatans.akun_x_rkakl as MAK', 'keuangan_perjadinkegiatans.harga', 'keuangan_perjadinkegiatans.persen_pajak', 'keuangan_perjadinkegiatans.pph22', 'keuangan_perjadinkegiatans.pph23', 'keuangan_perjadinkegiatans.ppn', 'keuangan_perjadinkegiatans.id as IdKeuangan', 'keuangan_perjadinkegiatans.jumlah_harga', 'keuangan_perjadinkegiatans.tgl_bayar as transaksi', 'keuangan_perjadinkegiatans.status as status_pembayaran', 'keuangan_perjadinkegiatans.tgl_kwitansi', 'keuangan_perjadinkegiatans.no_kwitansi', 'keuangan_perjadinkegiatans.spby', 'data_perjadinkegiatans.tgl_mulai', 'data_perjadinkegiatans.tgl_selesai', 'data_perjadinkegiatans.alamat', 'data_perjadinkegiatans.status as status_kegiatan', 'keuangan_perjadinkegiatans.drpp', 'keuangan_perjadinkegiatans.jurnal')
            ->whereBetween('data_perjadinkegiatans.tgl_mulai', [$mulai, $sampai])
            ->get();
        $kegiatansNon = DB::table('data_perjadinkegiatans')
            ->join('keuangan_perjadinkegiatans', 'data_perjadinkegiatans.id', '=', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
            ->join('perangkat_acaras', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
            ->join('non_pegawais', 'perangkat_acaras.non_pegawai_id', '=', 'non_pegawais.id')
            ->select('data_perjadinkegiatans.id AS idKegiatan', 'data_perjadinkegiatans.nama_kegiatan', 'non_pegawais.nama_lengkap', 'perangkat_acaras.sebagai', 'keuangan_perjadinkegiatans.akun_x_rkakl as MAK', 'keuangan_perjadinkegiatans.harga', 'keuangan_perjadinkegiatans.persen_pajak', 'keuangan_perjadinkegiatans.pph22', 'keuangan_perjadinkegiatans.pph23', 'keuangan_perjadinkegiatans.ppn', 'keuangan_perjadinkegiatans.id as IdKeuangan', 'keuangan_perjadinkegiatans.jumlah_harga', 'keuangan_perjadinkegiatans.updated_at as transaksi', 'keuangan_perjadinkegiatans.status as status_pembayaran', 'keuangan_perjadinkegiatans.tgl_kwitansi', 'keuangan_perjadinkegiatans.no_kwitansi', 'keuangan_perjadinkegiatans.spby', 'data_perjadinkegiatans.tgl_mulai', 'data_perjadinkegiatans.tgl_selesai', 'data_perjadinkegiatans.alamat', 'data_perjadinkegiatans.status as status_kegiatan', 'keuangan_perjadinkegiatans.drpp', 'keuangan_perjadinkegiatans.jurnal')
            ->whereBetween('data_perjadinkegiatans.tgl_mulai', [$mulai, $sampai])
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
        // ddd($kegiatans);
        return view('admin.other.kegiatan', [
            'title' => 'Laporan Kegiatan',
            'kegiatans' => $kegiatans,
            'kegiatanNons' => $kegiatansNon,
            'akuns' => $akuns,
            'laporans' => Laporan_perjadinkegiatan::all(),
            'operasionals' => $operasionals,
            'dibayarkan' => $dibayarkan,
            'blmdibayarkan' => $blmdibayarkan,
            'tdkdibayarkan' => $tdkdibayarkan,
            'totaldibayarkan' => $totaldibayarkan,
            'totalblmdibayarkan' => $totalblmdibayarkan,
            'totaltdkdibayarkan' => $totaltdkdibayarkan,
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

        $totalpesertapegawai = $request->numPegawaiLaporan;
        for ($i = 0; $i < $totalpesertapegawai; $i++) {
            $idpesertapegawai = 'idKeuangan_' . $i;
            $tglKwitansi = 'tglkwitansi_' . $i;
            $noKwitansi = 'kwitansi_' . $i;
            $spby = 'spby_' . $i;
            $drpp = 'drpp_' . $i;
            $jurnal = 'jurnal_' . $i;
            DB::table('keuangan_perjadinlangsungs')
                ->where('id', $request->$idpesertapegawai)
                ->update([
                    'tgl_kwitansi' => $request->$tglKwitansi,
                    'no_kwitansi' => $request->$noKwitansi,
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
            $spby = 'spby_' . $i;
            $drpp = 'drppNon_' . $i;
            $jurnal = 'jurnalNon_' . $i;
            DB::table('keuangan_perjadinlangsungs')
                ->where('id', $request->$idpesertapegawai)
                ->update([
                    'tgl_kwitansi' => $request->$tglKwitansi,
                    'no_kwitansi' => $request->$noKwitansi,
                    'spby' => $request->$spby,
                    'drpp' => $request->$drpp,
                    'jurnal' => $request->$jurnal,
                    'updated_at' => now(),
                ]);
        }


        return redirect()->route('laporan')->with('success', 'Data Telah Diperbaharui!');
    }

    public function updateSpbyKegiatan(Request $request)
    {

        $totalpesertapegawai = $request->numPegawaiLaporan;
        for ($i = 0; $i < $totalpesertapegawai; $i++) {
            $idpesertapegawai = 'idKeuangan_' . $i;
            $tglKwitansi = 'tglkwitansi_' . $i;
            $noKwitansi = 'kwitansi_' . $i;
            $spby = 'spby_' . $i;
            $drpp = 'drpp_' . $i;
            $jurnal = 'jurnal_' . $i;
            DB::table('keuangan_perjadinkegiatans')
                ->where('id', $request->$idpesertapegawai)
                ->update([
                    'tgl_kwitansi' => $request->$tglKwitansi,
                    'no_kwitansi' => $request->$noKwitansi,
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
            $drpp = 'drppNon_' . $i;
            $jurnal = 'jurnalNon_' . $i;
            DB::table('keuangan_perjadinkegiatans')
                ->where('id', $request->$idpesertapegawai)
                ->update([
                    'tgl_kwitansi' => $request->$tglKwitansi,
                    'no_kwitansi' => $request->$noKwitansi,
                    'spby' => $request->$spby,
                    'drpp' => $request->$drpp,
                    'jurnal' => $request->$jurnal,
                    'updated_at' => now(),
                ]);
        }


        return redirect()->route('laporan')->with('success', 'Data Telah Diperbaharui!');
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
}
