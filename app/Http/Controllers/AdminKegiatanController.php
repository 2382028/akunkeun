<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Data_perjadinkegiatan;
use App\Models\Mobilitas_perjadinkegiatan;
use App\Models\Kendaraan;
use App\Models\Fasilitas;
use App\Models\Keuangan_perjadinkegiatan;
use App\Models\Laporan_perjadinkegiatan;
use App\Models\Operasional;
use App\Models\Perangkat_acara;
use App\Models\Ref_sbm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                        ->get();
        return view('admin.kegiatan.mobilitas.index', [
            'title' => 'Mobilitas Kegiatan',
            'mobilitass' => $mobilitas
        ]);
    }

    public function assetIndex($status = 'pengajuan')
    {
        $peminjamansapras = DB::table('peminjaman_sarpras')
                        ->join('data_perjadinkegiatans', 'peminjaman_sarpras.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
                        ->join('pegawais', 'peminjaman_sarpras.pegawai_id', '=', 'pegawais.id')
                        ->join('assets', 'peminjaman_sarpras.asset', '=', 'assets.id')
                        ->select('data_perjadinkegiatans.id as idPeminjaman','pegawais.nama_lengkap', 'assets.nama_barang', 'peminjaman_sarpras.status')
                        ->where('peminjaman_sarpras.status', $status)
                        ->where('data_perjadinkegiatans.versi_id', session('versi'))
                        ->get();
        return view('admin.kegiatan.assets.index', [
            'title' => 'Peminjaman Assets',
            'peminjamans' => $peminjamansapras,
        ]);
    }

    public function bendaharaIndex($status = 'verifikasi-1')
    {
        $kegiatans = DB::table('data_perjadinkegiatans')
                         ->where('is_acceptBend', $status)
                         ->where('versi_id', session('versi'))
                         ->get();
        return view('admin.kegiatan.bendahara.index', [
            'title' => 'Bendahara Kegiatan',
            'kegiatans' => $kegiatans
        ]);
    }

    public function keuanganIndex($status = 'verifikasi-1')
    {
        $kegiatans = DB::table('data_perjadinkegiatans')
                         ->where('is_acceptKeu', $status)
                         ->where('versi_id', session('versi'))
                         ->get();
        return view('admin.kegiatan.keuangan.index', [
            'title' => 'Keuangan Kegiatan',
            'kegiatans' => $kegiatans
        ]);
    }

    public function detail_mobilitas(Request $request, $id)
    {
        $pengemudi = DB::table('pegawais')
                        ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
                        ->select('pegawais.id','pegawais.nama_lengkap', 'jabatans.nama_jabatan')
                        ->where('jabatans.nama_jabatan', 'Pengemudi')
                        ->get();
        $info = DB::table('mobilitas_perjadinkegiatans')
                        ->join('data_perjadinkegiatans', 'mobilitas_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
                        ->select('mobilitas_perjadinkegiatans.data_perjadinkegiatan','data_perjadinkegiatans.nama_kegiatan','data_perjadinkegiatans.tgl_mulai', 'data_perjadinkegiatans.alamat')
                        ->where('mobilitas_perjadinkegiatans.id', $id)
                        ->get();
        $infomobilsupir = DB::table('peminjaman_kendaraan_dinas')
                        ->join('pegawais', 'peminjaman_kendaraan_dinas.pegawai_id', '=', 'pegawais.id')
                        ->join('kendaraans', 'peminjaman_kendaraan_dinas.kendaraan', '=', 'kendaraans.id')
                        ->select('pegawais.nama_lengkap','kendaraans.merek', 'peminjaman_kendaraan_dinas.mobilitas_perjadinkegiatan')
                        ->where('peminjaman_kendaraan_dinas.mobilitas_perjadinkegiatan', $id)
                        ->get();
        $mob = DB::table('mobilitas_perjadinkegiatans')
                        ->select('*')
                        ->where('id', $id)
                        ->get();
        return view('admin.kegiatan.mobilitas.detail', [
            'title' => 'Detail Mobilitas Kegiatan',
            'infoKegiatan' => $info,
            'mobilitass' => $mob,
            'pengemudis' => $pengemudi,
            'penanggungs' => $infomobilsupir,
            'kendaraans' => Kendaraan::where('status', 'Baik')->get(),
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
        $pegawais = DB::table('perangkat_acaras')
                        ->join('fasilitas', 'perangkat_acaras.fasilitas_id', '=', 'fasilitas.id')
                        ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
                        ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
                        ->select('pegawais.id as idPegawai', 'pegawais.nama_lengkap','pegawais.pangkat', 'pegawais.golongan', 'perangkat_acaras.sebagai', 'perangkat_acaras.detail_satuan', 'perangkat_acaras.satuan', 'perangkat_acaras.status', 'perangkat_acaras.fasilitas_id', 'fasilitas.nama_fasilitas', 'perangkat_acaras.id as idPerangkatAcara', 'keuangan_perjadinkegiatans.id as idKeuangan', 'keuangan_perjadinkegiatans.data_perjadinkegiatan as idKegiatan')
                        ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
                        ->get();
        $nonpegawais = DB::table('perangkat_acaras')
                        ->join('fasilitas', 'perangkat_acaras.fasilitas_id', '=', 'fasilitas.id')
                        ->join('non_pegawais', 'perangkat_acaras.non_pegawai_id', '=', 'non_pegawais.id')
                        ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
                        ->select('non_pegawais.id as idNonPegawai', 'non_pegawais.nama_lengkap','non_pegawais.pangkat', 'non_pegawais.golongan','perangkat_acaras.sebagai', 'perangkat_acaras.detail_satuan', 'perangkat_acaras.satuan', 'perangkat_acaras.status', 'fasilitas.nama_fasilitas', 'perangkat_acaras.fasilitas_id', 'perangkat_acaras.id as idPerangkatAcara', 'keuangan_perjadinkegiatans.id as idKeuangan', 'keuangan_perjadinkegiatans.data_perjadinkegiatan as idKegiatan')
                        ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
                        ->get();
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
        return view('admin.kegiatan.keuangan.detail', [
            'title' => 'Detail Keuangan',
            'info' => Data_perjadinkegiatan::find($id),
            'dokumens' => Laporan_perjadinkegiatan::where('data_perjadin_kegiatan', $id)->get(),
            "perangkats" => Fasilitas::where('data_perjadinkegiatan_id', $id)->get(),
            'pegawais' => $pegawais,
            'nonpegawais' => $nonpegawais,
            "operasionals" => $operasionals,
            "keuanganoperasionals" => $keuanganOperasional,
        ]);
    }

    public function detail_bendahara(Request $request, $id)
    {
        $pegawais = DB::table('perangkat_acaras')
                        ->join('fasilitas', 'perangkat_acaras.fasilitas_id', '=', 'fasilitas.id')
                        ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
                        ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
                        ->select('pegawais.id as idPegawai', 'pegawais.nama_lengkap','pegawais.pangkat', 'pegawais.golongan', 'perangkat_acaras.sebagai', 'perangkat_acaras.detail_satuan', 'perangkat_acaras.satuan', 'perangkat_acaras.status', 'perangkat_acaras.fasilitas_id', 'fasilitas.nama_fasilitas', 'perangkat_acaras.id as idPerangkatAcara', 'keuangan_perjadinkegiatans.id as idKeuangan', 'keuangan_perjadinkegiatans.data_perjadinkegiatan as idKegiatan', 'keuangan_perjadinkegiatans.pph22', 'keuangan_perjadinkegiatans.pph23', 'keuangan_perjadinkegiatans.ppn', 'keuangan_perjadinkegiatans.tgl_bayar')
                        ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
                        ->get();
        $nonpegawais = DB::table('perangkat_acaras')
                        ->join('fasilitas', 'perangkat_acaras.fasilitas_id', '=', 'fasilitas.id')
                        ->join('non_pegawais', 'perangkat_acaras.non_pegawai_id', '=', 'non_pegawais.id')
                        ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
                        ->select('non_pegawais.id as idNonPegawai', 'non_pegawais.nama_lengkap','non_pegawais.pangkat', 'non_pegawais.golongan','perangkat_acaras.sebagai', 'perangkat_acaras.detail_satuan', 'perangkat_acaras.satuan', 'perangkat_acaras.status', 'fasilitas.nama_fasilitas', 'perangkat_acaras.fasilitas_id', 'perangkat_acaras.id as idPerangkatAcara', 'keuangan_perjadinkegiatans.id as idKeuangan', 'keuangan_perjadinkegiatans.data_perjadinkegiatan as idKegiatan', 'keuangan_perjadinkegiatans.pph22', 'keuangan_perjadinkegiatans.pph23', 'keuangan_perjadinkegiatans.ppn', 'keuangan_perjadinkegiatans.tgl_bayar')
                        ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
                        ->get();
        $operasionals = DB::table('keuangan_perjadinkegiatans')
                        ->join('operasionals', 'keuangan_perjadinkegiatans.operasional', '=', 'operasionals.id')
                        ->select('operasionals.id', 'operasionals.status', 'operasionals.nama', 'operasionals.jumlah_frekuensi', 'operasionals.satuan', 'operasionals.detail_satuan', 'keuangan_perjadinkegiatans.operasional', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', 'keuangan_perjadinkegiatans.pph22', 'keuangan_perjadinkegiatans.pph23', 'keuangan_perjadinkegiatans.ppn', 'keuangan_perjadinkegiatans.tgl_bayar')
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
                        ->get();
        return view('admin.kegiatan.bendahara.detail', [
            'title' => 'Detail Keuangan',
            'info' => Data_perjadinkegiatan::find($id),
            "perangkats" => Fasilitas::where('data_perjadinkegiatan_id', $id)->get(),
            'pegawais' => $pegawais,
            'nonpegawais' => $nonpegawais,
            "operasionals" => $operasionals,
            "keuanganoperasionals" => $keuanganOperasional,
            'keuangans' => Keuangan_perjadinkegiatan::where('data_perjadinkegiatan', $id)->get(),
            "sbms" => Ref_sbm::all(),
            'akuns' => $akuns
        ]);
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

    public function storeMobilitas(Request $request)
    {
        $selectSupir = DB::table('fasilitas')
                        ->select('id')
                        ->where('data_perjadinkegiatan_id', $request->idKegiatan)
                        ->where('nama_fasilitas','=', 'supir')
                        ->first();
            // dd($selectSupir);

            if ($request->status_aksi == 'pengajuan') {
                db::table('peminjaman_kendaraan_dinas')->insertOrIgnore([
                    'kendaraan' => $request->mobil,
                    'mobilitas_perjadinkegiatan' => $request->idMobilitas,
                    'pegawai_id' => $request->supir,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
 
                db::table('operasionals')->insertOrIgnore([
                    'nama' => $request->tujuan,
                    'jumlah_frekuensi' => '1',
                    'satuan' => $request->satuan,
                    'detail_satuan' => 'hari',
                    'ket' => 'uang bensin dan tol',
                    'status' => 'pengajuan',
                    'fasilitas_id' => '0',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $maxOperasional = Operasional::max('id');
                db::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                    'operasional' => $maxOperasional,
                    'data_perjadinkegiatan' => $request->idKegiatan,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                
                if ($selectSupir == null) {
                    db::table('fasilitas')->insertOrIgnore([
                        'data_perjadinkegiatan_id' => $request->idKegiatan,
                        'nama_fasilitas' => 'supir',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    # code...
                    $maxFasilitas = Fasilitas::max('id');
    
                    db::table('perangkat_acaras')->insertOrIgnore([
                        'pegawai_id' => $request->supir,
                        'sebagai' => 'supir',
                        'status' => 'Menunggu Persetujuan',
                        'detail_satuan' => 'hari',
                        'satuan' => $request->satuan,
                        'fasilitas_id' => $maxFasilitas,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            
                    db::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                        'data_perjadinkegiatan' => $request->idKegiatan,
                        'perangkat_acara' => Perangkat_acara::max('id'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                
                if ($selectSupir != null) {
    
                    db::table('perangkat_acaras')->insertOrIgnore([
                        'pegawai_id' => $request->supir,
                        'sebagai' => 'supir',
                        'status' => 'Menunggu Persetujuan',
                        'detail_satuan' => 'hari',
                        'satuan' => $request->satuan,
                        'fasilitas_id' => $selectSupir->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            
                    db::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                        'data_perjadinkegiatan' => $request->idKegiatan,
                        'perangkat_acara' => Perangkat_acara::max('id'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::table('mobilitas_perjadinkegiatans')
                ->where('id', $request->idMobilitas)
                ->update([
                    'status' => $request->status,
                    'updated_at' => now(),
            ]);
            DB::table('data_perjadinkegiatans')
                    ->where('id', $request->idKegiatan)
                    ->update([
                        'is_acceptBMN' => 'disetujui',
                        'admin_BMN' => auth('administrator')->user()->id,
                        'updated_at' => now(),
            ]);

        return redirect()->route('mobilitas', ['status' => 'proses'])->with('success', 'Data telah diperbaharui!');

    }

    public function storeKeuangan(Request $request) {

        $action = $request->input('action');

        if ($action === 'verifikasi') {
            
            $totaldokumen = $request->numDokumen;
            for ($i=0; $i < $totaldokumen; $i++) { 
                $idDokumen = 'idDokumen_' . $i;
                $statusDokumen = 'statusDokumen_' . $i;
                $keteranganDokumen = 'keteranganDokumen_' . $i;
                DB::table('laporan_perjadinkegiatans')
                ->where('id', $request->$idDokumen)
                ->update([
                    'status' => $request->$statusDokumen,
                    'keterangan' => $request->$keteranganDokumen,
                    'updated_at' => now(),
                ]);
            }

            $totalpegawai = $request->numPegawai;
            for ($i=0; $i < $totalpegawai; $i++) { 
                $idPegawai = 'idPegawai_' . $i;
                $statusPegawai = 'statusPegawai_' . $i;
                DB::table('perangkat_acaras')
                ->where('pegawai_id', $request->$idPegawai)
                ->update([
                    'status' => $request->$statusPegawai,
                    'updated_at' => now(),
                ]);
            }

            $totalnonpegawai = $request->numNonPegawai;
            for ($i=0; $i < $totalnonpegawai; $i++) { 
                $idnonPegawai = 'idNonPegawai_' . $i;
                $statusnonPegawai = 'statusNonPegawai_' . $i;
                DB::table('perangkat_acaras')
                ->where('non_pegawai_id', $request->$idnonPegawai)
                ->update([
                    'status' => $request->$statusnonPegawai,
                    'updated_at' => now(),
                ]);
            }

            $totaloperasional = $request->numOperasional;
            for ($i=0; $i < $totaloperasional; $i++) { 
                $idOperasional = 'idOperasional_' . $i;
                $akun = 'akun_' . $i;
                $harga = 'nominal_' . $i;
                $pajak = 'pajak_' . $i;
                $total = 'total_' . $i;
                $statusOperasonal = 'kesesuaian_' . $i;
                DB::table('keuangan_perjadinkegiatans')
                ->where('operasional', $request->$idOperasional)
                ->update([
                    'harga' => $request->$harga,
                    'persen_pajak' => $request->$pajak,
                    'jumlah_harga' => $request->$total,
                    'akun_x_rkakl' => $request->$akun,
                    'updated_at' => now(),
                ]);
                DB::table('operasionals')
                ->where('id', $request->$idOperasional)
                ->update([
                    'status' => $request->$statusOperasonal,
                    'updated_at' => now(),
                ]);
            }

            DB::table('data_perjadinkegiatans')
                ->where('id', $request->idKegiatan)
                ->update([
                    'status' => 'pengajuan',
                    'is_acceptKeu' => 'verifikasi-2',
                    'is_acceptBend' => 'approval-1',
                    'admin_Keu' => auth('administrator')->user()->id,
                    'updated_at' => now(),
                ]);

            return redirect()->route('keuangan-kegiatan', ['status' => 'verifikasi-2'])->with('success', 'Data telah diperbaharui!');

        } elseif($action === 'tolak') {
            DB::table('data_perjadinkegiatans')
                ->where('id', $request->idKegiatan)
                ->update([
                    'status' => 'ditolak',
                    'is_acceptKeu' => 'ditolak',
                    'admin_Keu' => auth('administrator')->user()->id,
                    'updated_at' => now(),
                ]);
            
            return redirect()->route('keuangan-kegiatan', ['status' => 'ditolak'])->with('success', 'Program Kegiatan telah anda tolak!');
        } elseif($action === 'revisi') {
            $totaldokumen = $request->numDokumen;
            for ($i=0; $i < $totaldokumen; $i++) { 
                $idDokumen = 'idDokumen_' . $i;
                $statusDokumen = 'statusDokumen_' . $i;
                $keteranganDokumen = 'keteranganDokumen_' . $i;
                DB::table('laporan_perjadinkegiatans')
                ->where('id', $request->$idDokumen)
                ->update([
                    'status' => $request->$statusDokumen,
                    'keterangan' => $request->$keteranganDokumen,
                    'updated_at' => now(),
                ]);
            }

            $totalpegawai = $request->numPegawai;
            for ($i=0; $i < $totalpegawai; $i++) { 
                $idPegawai = 'idPegawai_' . $i;
                $statusPegawai = 'statusPegawai_' . $i;
                DB::table('perangkat_acaras')
                ->where('pegawai_id', $request->$idPegawai)
                ->update([
                    'status' => $request->$statusPegawai,
                    'updated_at' => now(),
                ]);
            }

            $totalnonpegawai = $request->numNonPegawai;
            for ($i=0; $i < $totalnonpegawai; $i++) { 
                $idnonPegawai = 'idNonPegawai_' . $i;
                $statusnonPegawai = 'statusNonPegawai_' . $i;
                DB::table('perangkat_acaras')
                ->where('non_pegawai_id', $request->$idnonPegawai)
                ->update([
                    'status' => $request->$statusnonPegawai,
                    'updated_at' => now(),
                ]);
            }

            $totaloperasional = $request->numOperasional;
            for ($i=0; $i < $totaloperasional; $i++) { 
                $idOperasional = 'idOperasional_' . $i;
                $akun = 'akun_' . $i;
                $harga = 'nominal_' . $i;
                $pajak = 'pajak_' . $i;
                $total = 'total_' . $i;
                $statusOperasonal = 'kesesuaian_' . $i;
                DB::table('keuangan_perjadinkegiatans')
                ->where('operasional', $request->$idOperasional)
                ->update([
                    'harga' => $request->$harga,
                    'persen_pajak' => $request->$pajak,
                    'jumlah_harga' => $request->$total,
                    'akun_x_rkakl' => $request->$akun,
                    'updated_at' => now(),
                ]);
                DB::table('operasionals')
                ->where('id', $request->$idOperasional)
                ->update([
                    'status' => $request->$statusOperasonal,
                    'updated_at' => now(),
                ]);
            }

            if ($request->statusKegiatan == 'verifikasi-1') {
                # code...
                DB::table('data_perjadinkegiatans')
                    ->where('id', $request->idKegiatan)
                    ->update([
                        'status' => 'revisi',
                        'is_acceptKeu' => 'revisi-1',
                        'admin_Keu' => auth('administrator')->user()->id,
                        'updated_at' => now(),
                    ]);
                
                return redirect()->route('keuangan-kegiatan', ['status' => 'revisi-1'])->with('success', 'Program Kegiatan telah anda tolak!');
            }
            
            if ($request->statusKegiatan == 'verifikasi-2') {
                # code...
                DB::table('data_perjadinkegiatans')
                    ->where('id', $request->idKegiatan)
                    ->update([
                        'status' => 'revisi',
                        'is_acceptKeu' => 'revisi-2',
                        'admin_Keu' => auth('administrator')->user()->id,
                        'updated_at' => now(),
                    ]);
                
                return redirect()->route('keuangan-kegiatan', ['status' => 'revisi-2'])->with('success', 'Program Kegiatan telah anda tolak!');
            }

        } elseif($action === 'verifikasi-2') {
            $totaldokumen = $request->numDokumen;
            for ($i=0; $i < $totaldokumen; $i++) { 
                $idDokumen = 'idDokumen_' . $i;
                $statusDokumen = 'statusDokumen_' . $i;
                $keteranganDokumen = 'keteranganDokumen_' . $i;
                DB::table('laporan_perjadinkegiatans')
                ->where('id', $request->$idDokumen)
                ->update([
                    'status' => $request->$statusDokumen,
                    'keterangan' => $request->$keteranganDokumen,
                    'updated_at' => now(),
                ]);
            }

            $totalpegawai = $request->numPegawai;
            for ($i=0; $i < $totalpegawai; $i++) { 
                $idPegawai = 'idPegawai_' . $i;
                $statusPegawai = 'statusPegawai_' . $i;
                DB::table('perangkat_acaras')
                ->where('pegawai_id', $request->$idPegawai)
                ->update([
                    'status' => $request->$statusPegawai,
                    'updated_at' => now(),
                ]);
            }

            $totalnonpegawai = $request->numNonPegawai;
            for ($i=0; $i < $totalnonpegawai; $i++) { 
                $idnonPegawai = 'idNonPegawai_' . $i;
                $statusnonPegawai = 'statusNonPegawai_' . $i;
                DB::table('perangkat_acaras')
                ->where('non_pegawai_id', $request->$idnonPegawai)
                ->update([
                    'status' => $request->$statusnonPegawai,
                    'updated_at' => now(),
                ]);
            }

            $totaloperasional = $request->numOperasional;
            for ($i=0; $i < $totaloperasional; $i++) { 
                $idOperasional = 'idOperasional_' . $i;
                $akun = 'akun_' . $i;
                $harga = 'nominal_' . $i;
                $pajak = 'pajak_' . $i;
                $total = 'total_' . $i;
                $statusOperasonal = 'kesesuaian_' . $i;
                DB::table('keuangan_perjadinkegiatans')
                ->where('operasional', $request->$idOperasional)
                ->update([
                    'harga' => $request->$harga,
                    'persen_pajak' => $request->$pajak,
                    'jumlah_harga' => $request->$total,
                    'akun_x_rkakl' => $request->$akun,
                    'updated_at' => now(),
                ]);
                DB::table('operasionals')
                ->where('id', $request->$idOperasional)
                ->update([
                    'status' => $request->$statusOperasonal,
                    'updated_at' => now(),
                ]);
            }

            DB::table('data_perjadinkegiatans')
                ->where('id', $request->idKegiatan)
                ->update([
                    'is_acceptKeu' => 'selesai',
                    'is_acceptBend' => 'approval-2',
                    'admin_Keu' => auth('administrator')->user()->id,
                    'updated_at' => now(),
                ]);

            return redirect()->route('keuangan-kegiatan', ['status' => 'selesai'])->with('success', 'Data telah diperbaharui!');
        }
        

        
    }

    public function storeBendahara(Request $request) {

        $action = $request->input('action');

        if ($action === 'approve-1') {
            
            $totalpegawai = $request->numPegawai;
            for ($i=0; $i < $totalpegawai; $i++) { 
                $idPerangkatAcara = 'idPerangkatPegawai_' . $i;
                $akun = 'akunPegawai_' . $i;
                $sbm = 'sbmPegawai_' . $i;
                $nominal = 'nominalPegawai_' . $i;
                $pajak = 'pajakPegawai_' . $i;
                $pph22 = 'pajakPegawaipph22_' . $i;
                $pph23 = 'pajakPegawaipph23_' . $i;
                $ppn = 'pajakPegawaippn_' . $i;
                $tglbayar = 'tglbayarPegawai_' . $i;
                $total = 'totalPegawai_' . $i;
                $statusPembiyaan = 'statusPembiyaanPegawai_' . $i;
                DB::table('keuangan_perjadinkegiatans')
                ->where('perangkat_acara', $request->$idPerangkatAcara)
                ->update([
                    'harga' => $request->$nominal,
                    'persen_pajak' => $request->$pajak,
                    'pph22' => $request->$pph22,
                    'pph23' => $request->$pph23,
                    'ppn' => $request->$ppn,
                    'tgl_bayar' => $request->$tglbayar,
                    'jumlah_harga' => $request->$total,
                    'ref_sbm' => $request->$sbm,
                    'akun_x_rkakl' => $request->$akun,
                    'status' => $request->$statusPembiyaan,
                    'updated_at' => now(),
                ]);
            }

            $nonPegawaiTotal = $request->numNonPegawai;
            for ($j=0; $j < $nonPegawaiTotal; $j++) { 
                $idPerangkatAcaraNonPegawai = 'idPerangkatNonPegawai_' . $j;
                $akunNonPegawai = 'akunNonPegawai_' . $j;
                $sbmNonPegawai = 'sbmNonPegawai_' . $j;
                $nominalNonPegawai = 'nominalNonPegawai_' . $j;
                $pajakNonPegawai = 'pajakNonPegawai_' . $j;
                $pajakNonPegawaipph22 = 'pajakNonPegawaipph22_' . $j;
                $pajakNonPegawaipph23 = 'pajakNonPegawaipph23_' . $j;
                $pajakNonPegawaippn = 'pajakNonPegawaippn_' . $j;
                $totalNonPegawaitglbayar = 'tglbayarNonPegawai_' . $j;
                $totalNonPegawai = 'totalNonPegawai_' . $j;
                $statusPembiyaanNonPegawai = 'statusPembayaranNonPegawai_' . $j;
                DB::table('keuangan_perjadinkegiatans')
                ->where('perangkat_acara', $request->$idPerangkatAcaraNonPegawai)
                ->update([
                    'harga' => $request->$nominalNonPegawai,
                    'persen_pajak' => $request->$pajakNonPegawai,
                    'pph22' => $request->$pajakNonPegawaipph22,
                    'pph23' => $request->$pajakNonPegawaipph23,
                    'ppn' => $request->$pajakNonPegawaippn,
                    'tgl_bayar' => $request->$totalNonPegawaitglbayar,
                    'jumlah_harga' => $request->$totalNonPegawai,
                    'ref_sbm' => $request->$sbmNonPegawai,
                    'akun_x_rkakl' => $request->$akunNonPegawai,
                    'status' => $request->$statusPembiyaanNonPegawai,
                    'updated_at' => now(),
                ]);
            }

            $numOperasionalBend = $request->numOperasional;
            for ($j=0; $j < $numOperasionalBend; $j++) { 
                $idOperasional = 'idOperasional_' . $j;
                $akunOperasional = 'akunOperasional_' . $j;
                $sbmOperasional = 'sbmOperasional_' . $j;
                $nominalOperasional = 'nominalOperasional_' . $j;
                $pajakOperasional = 'pajakOperasional_' . $j;
                $pajakOperasional22 = 'pajakOperasionalpph22_' . $j;
                $pajakOperasional23 = 'pajakOperasionalpph23_' . $j;
                $pajakOperasionalppn = 'pajakOperasionalppn_' . $j;
                $totalOperasionaltglbayar = 'tglbayarOperasional_' . $j;
                $totalOperasional = 'totalOperasional_' . $j;
                $statusOperasional = 'kesesuaianOperasional_' . $j;
                DB::table('keuangan_perjadinkegiatans')
                ->where('operasional', $request->$idOperasional)
                ->update([
                    'harga' => $request->$nominalOperasional,
                    'persen_pajak' => $request->$pajakOperasional,
                    'pph22' => $request->$pajakOperasional22,
                    'pph23' => $request->$pajakOperasional23,
                    'ppn' => $request->$pajakOperasionalppn,
                    'tgl_bayar' => $request->$totalOperasionaltglbayar,
                    'jumlah_harga' => $request->$totalOperasional,
                    'ref_sbm' => $request->$sbmOperasional,
                    'akun_x_rkakl' => $request->$akunOperasional,
                    'status' => $request->$statusOperasional,
                    'updated_at' => now(),
                ]);
            }

            DB::table('data_perjadinkegiatans')
                ->where('id', $request->idKegiatan)
                ->update([
                    'status' => 'proses',
                    'is_acceptKeu' => 'verifikasi-2',
                    'is_acceptBend' => 'approval-2',
                    'admin_Bend' => auth('administrator')->user()->id,
                    'updated_at' => now(),
                ]);

            return redirect()->route('bendahara-kegiatan', ['status' => 'approval-2'])->with('success', 'Data telah diperbaharui!, anda telah menyetujui kegiatan.');

        } elseif($action === 'approve-2') {

            $totalpegawai = $request->numPegawai;
            for ($i=0; $i < $totalpegawai; $i++) { 
                $idPerangkatAcara = 'idPerangkatPegawai_' . $i;
                $akun = 'akunPegawai_' . $i;
                $sbm = 'sbmPegawai_' . $i;
                $nominal = 'nominalPegawai_' . $i;
                $pajak = 'pajakPegawai_' . $i;
                $pph22 = 'pajakPegawaipph22_' . $i;
                $pph23 = 'pajakPegawaipph23_' . $i;
                $ppn = 'pajakPegawaippn_' . $i;
                $tglbayar = 'tglbayarPegawai_' . $i;
                $total = 'totalPegawai_' . $i;
                $statusPembiyaan = 'statusPembiyaanPegawai_' . $i;
                DB::table('keuangan_perjadinkegiatans')
                ->where('perangkat_acara', $request->$idPerangkatAcara)
                ->update([
                    'harga' => $request->$nominal,
                    'persen_pajak' => $request->$pajak,
                    'pph22' => $request->$pph22,
                    'pph23' => $request->$pph23,
                    'ppn' => $request->$ppn,
                    'tgl_bayar' => $request->$tglbayar,
                    'jumlah_harga' => $request->$total,
                    'ref_sbm' => $request->$sbm,
                    'akun_x_rkakl' => $request->$akun,
                    'status' => $request->$statusPembiyaan,
                    'updated_at' => now(),
                ]);
            }

            $nonPegawaiTotal = $request->numNonPegawai;
            for ($j=0; $j < $nonPegawaiTotal; $j++) { 
                $idPerangkatAcaraNonPegawai = 'idPerangkatNonPegawai_' . $j;
                $akunNonPegawai = 'akunNonPegawai_' . $j;
                $sbmNonPegawai = 'sbmNonPegawai_' . $j;
                $nominalNonPegawai = 'nominalNonPegawai_' . $j;
                $pajakNonPegawai = 'pajakNonPegawai_' . $j;
                $pajakNonPegawaipph22 = 'pajakNonPegawaipph22_' . $j;
                $pajakNonPegawaipph23 = 'pajakNonPegawaipph23_' . $j;
                $pajakNonPegawaippn = 'pajakNonPegawaippn_' . $j;
                $totalNonPegawaitglbayar = 'tglbayarNonPegawai_' . $j;
                $totalNonPegawai = 'totalNonPegawai_' . $j;
                $statusPembiyaanNonPegawai = 'statusPembayaranNonPegawai_' . $j;
                DB::table('keuangan_perjadinkegiatans')
                ->where('perangkat_acara', $request->$idPerangkatAcaraNonPegawai)
                ->update([
                    'harga' => $request->$nominalNonPegawai,
                    'persen_pajak' => $request->$pajakNonPegawai,
                    'pph22' => $request->$pajakNonPegawaipph22,
                    'pph23' => $request->$pajakNonPegawaipph23,
                    'ppn' => $request->$pajakNonPegawaippn,
                    'tgl_bayar' => $request->$totalNonPegawaitglbayar,
                    'jumlah_harga' => $request->$totalNonPegawai,
                    'ref_sbm' => $request->$sbmNonPegawai,
                    'akun_x_rkakl' => $request->$akunNonPegawai,
                    'status' => $request->$statusPembiyaanNonPegawai,
                    'updated_at' => now(),
                ]);
            }

            $numOperasionalBend = $request->numOperasional;
            for ($j=0; $j < $numOperasionalBend; $j++) { 
                $idOperasional = 'idOperasional_' . $j;
                $akunOperasional = 'akunOperasional_' . $j;
                $sbmOperasional = 'sbmOperasional_' . $j;
                $nominalOperasional = 'nominalOperasional_' . $j;
                $pajakOperasional = 'pajakOperasional_' . $j;
                $pajakOperasional22 = 'pajakOperasionalpph22_' . $j;
                $pajakOperasional23 = 'pajakOperasionalpph23_' . $j;
                $pajakOperasionalppn = 'pajakOperasionalppn_' . $j;
                $totalOperasionaltglbayar = 'tglbayarOperasional_' . $j;
                $totalOperasional = 'totalOperasional_' . $j;
                $statusOperasional = 'kesesuaianOperasional_' . $j;
                DB::table('keuangan_perjadinkegiatans')
                ->where('operasional', $request->$idOperasional)
                ->update([
                    'harga' => $request->$nominalOperasional,
                    'persen_pajak' => $request->$pajakOperasional,
                    'pph22' => $request->$pajakOperasional22,
                    'pph23' => $request->$pajakOperasional23,
                    'ppn' => $request->$pajakOperasionalppn,
                    'tgl_bayar' => $request->$totalOperasionaltglbayar,
                    'jumlah_harga' => $request->$totalOperasional,
                    'ref_sbm' => $request->$sbmOperasional,
                    'akun_x_rkakl' => $request->$akunOperasional,
                    'status' => $request->$statusOperasional,
                    'updated_at' => now(),
                ]);
            }

            DB::table('data_perjadinkegiatans')
                ->where('id', $request->idKegiatan)
                ->update([
                    'status' => 'selesai',
                    'is_acceptKeu' => 'selesai',
                    'is_acceptBend' => 'selesai',
                    'admin_Bend' => auth('administrator')->user()->id,
                    'updated_at' => now(),
                ]);

            return redirect()->route('bendahara-kegiatan', ['status' => 'selesai'])->with('success', 'Data telah diperbaharui!, Kegiatan sudah disetujui untuk selesai.');

        } elseif($action === 'simpan') {

            $totalpegawai = $request->numPegawai;
            for ($i=0; $i < $totalpegawai; $i++) { 
                $idPerangkatAcara = 'idPerangkatPegawai_' . $i;
                $akun = 'akunPegawai_' . $i;
                $sbm = 'sbmPegawai_' . $i;
                $nominal = 'nominalPegawai_' . $i;
                $pajak = 'pajakPegawai_' . $i;
                $pph22 = 'pajakPegawaipph22_' . $i;
                $pph23 = 'pajakPegawaipph23_' . $i;
                $ppn = 'pajakPegawaippn_' . $i;
                $tglbayar = 'tglbayarPegawai_' . $i;
                $total = 'totalPegawai_' . $i;
                $statusPembiyaan = 'statusPembiyaanPegawai_' . $i;
                DB::table('keuangan_perjadinkegiatans')
                ->where('perangkat_acara', $request->$idPerangkatAcara)
                ->update([
                    'harga' => $request->$nominal,
                    'persen_pajak' => $request->$pajak,
                    'pph22' => $request->$pph22,
                    'pph23' => $request->$pph23,
                    'ppn' => $request->$ppn,
                    'tgl_bayar' => $request->$tglbayar,
                    'jumlah_harga' => $request->$total,
                    'ref_sbm' => $request->$sbm,
                    'akun_x_rkakl' => $request->$akun,
                    'status' => $request->$statusPembiyaan,
                    'updated_at' => now(),
                ]);
            }

            $nonPegawaiTotal = $request->numNonPegawai;
            for ($j=0; $j < $nonPegawaiTotal; $j++) { 
                $idPerangkatAcaraNonPegawai = 'idPerangkatNonPegawai_' . $j;
                $akunNonPegawai = 'akunNonPegawai_' . $j;
                $sbmNonPegawai = 'sbmNonPegawai_' . $j;
                $nominalNonPegawai = 'nominalNonPegawai_' . $j;
                $pajakNonPegawai = 'pajakNonPegawai_' . $j;
                $pajakNonPegawaipph22 = 'pajakNonPegawaipph22_' . $j;
                $pajakNonPegawaipph23 = 'pajakNonPegawaipph23_' . $j;
                $pajakNonPegawaippn = 'pajakNonPegawaippn_' . $j;
                $totalNonPegawaitglbayar = 'tglbayarNonPegawai_' . $j;
                $totalNonPegawai = 'totalNonPegawai_' . $j;
                $statusPembiyaanNonPegawai = 'statusPembayaranNonPegawai_' . $j;
                DB::table('keuangan_perjadinkegiatans')
                ->where('perangkat_acara', $request->$idPerangkatAcaraNonPegawai)
                ->update([
                    'harga' => $request->$nominalNonPegawai,
                    'persen_pajak' => $request->$pajakNonPegawai,
                    'pph22' => $request->$pajakNonPegawaipph22,
                    'pph23' => $request->$pajakNonPegawaipph23,
                    'ppn' => $request->$pajakNonPegawaippn,
                    'tgl_bayar' => $request->$totalNonPegawaitglbayar,
                    'jumlah_harga' => $request->$totalNonPegawai,
                    'ref_sbm' => $request->$sbmNonPegawai,
                    'akun_x_rkakl' => $request->$akunNonPegawai,
                    'status' => $request->$statusPembiyaanNonPegawai,
                    'updated_at' => now(),
                ]);
            }

            $numOperasionalBend = $request->numOperasional;
            for ($j=0; $j < $numOperasionalBend; $j++) { 
                $idOperasional = 'idOperasional_' . $j;
                $akunOperasional = 'akunOperasional_' . $j;
                $sbmOperasional = 'sbmOperasional_' . $j;
                $nominalOperasional = 'nominalOperasional_' . $j;
                $pajakOperasional = 'pajakOperasional_' . $j;
                $pajakOperasional22 = 'pajakOperasionalpph22_' . $j;
                $pajakOperasional23 = 'pajakOperasionalpph23_' . $j;
                $pajakOperasionalppn = 'pajakOperasionalppn_' . $j;
                $totalOperasionaltglbayar = 'tglbayarOperasional_' . $j;
                $totalOperasional = 'totalOperasional_' . $j;
                $statusOperasional = 'kesesuaianOperasional_' . $j;
                DB::table('keuangan_perjadinkegiatans')
                ->where('operasional', $request->$idOperasional)
                ->update([
                    'harga' => $request->$nominalOperasional,
                    'persen_pajak' => $request->$pajakOperasional,
                    'pph22' => $request->$pajakOperasional22,
                    'pph23' => $request->$pajakOperasional23,
                    'ppn' => $request->$pajakOperasionalppn,
                    'tgl_bayar' => $request->$totalOperasionaltglbayar,
                    'jumlah_harga' => $request->$totalOperasional,
                    'ref_sbm' => $request->$sbmOperasional,
                    'akun_x_rkakl' => $request->$akunOperasional,
                    'status' => $request->$statusOperasional,
                    'updated_at' => now(),
                ]);
            }

            return redirect()->route('bendahara-kegiatan', ['status' => 'approval-2'])->with('success', 'Data telah diperbaharui!, Kegiatan sudah disetujui untuk selesai.');

        } elseif($action === 'revisi') {
            DB::table('data_perjadinkegiatans')
                ->where('id', $request->idKegiatan)
                ->update([
                    'status' => 'revisi',
                    'is_acceptKeu' => 'verifikasi-1',
                    'is_acceptBend' => 'revisi',
                    'admin_Bend' => auth('administrator')->user()->id,
                    'updated_at' => now(),
                ]);

            return redirect()->route('bendahara-kegiatan', ['status' => 'revisi'])->with('success', 'Data telah diperbaharui!, Konfirmasi ulang kebagian keuangan!');
        } elseif($action === 'tolak') {
            DB::table('data_perjadinkegiatans')
                ->where('id', $request->idKegiatan)
                ->update([
                    'status' => 'ditolak',
                    'is_acceptKeu' => 'verifikasi-1',
                    'is_acceptBend' => 'ditolak',
                    'admin_Bend' => auth('administrator')->user()->id,
                    'updated_at' => now(),
                ]);

            return redirect()->route('bendahara-kegiatan', ['status' => 'ditolak'])->with('success', 'Data telah diperbaharui!, Kegiatan telah anda ditolak!');
        }
        
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
                    'updated_at' => now(),
                ]);
            if ($request->$status == 'digunakan') {
                DB::table('assets')
                ->where('id', $request->$idBarang)
                ->update([
                    'status_peminjaman' => 'digunakan',
                    'updated_at' => now(),
                ]);
            }

            if ($request->$status == 'selesai') {
                DB::table('assets')
                ->where('id', $request->$idBarang)
                ->update([
                    'status_peminjaman' => 'tidak digunakan',
                    'updated_at' => now(),
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
}
