<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Data_penyedia;
use App\Models\Dokumen_permohonan;
use App\Models\Kendaraan;
use App\Models\Komponen_diperlukan;
use App\Models\Pegawai;
use App\Models\Permohonan;
use App\Models\Ref_sbm;
use App\Models\Ruangan;
use App\Models\Services;
use App\Models\Versi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminBMNController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.bmn.referensi.data_penyedia', [
            'title' => 'Data Mitra Penyedia',
            'penyedias' => Data_penyedia::all()
        ]);
    }

    public function detail_penyedia($id)
    {
        return view('admin.bmn.referensi.editPenyedia', [
            'title' => 'Data Mitra Penyedia',
            'penyedia' => Data_penyedia::find($id)
        ]);
    }

    public function indexKendaraan($status = 'tersedia')
    {
        return view('admin.bmn.referensi.data_kendaraan', [
            'title' => 'Data Kendaraan',
            'kendaraans' => Kendaraan::all()
        ]);
    }

    public function detail_kendaraan($id)
    {
        return view('admin.bmn.referensi.editKendaraan', [
            'title' => 'Detail Data Kendaraan',
            'kendaraan' => Kendaraan::find($id)
        ]);
    }

    public function indexRuangan()
    {
        $ruangans = DB::table('ruangans')
                        ->join('pegawais', 'ruangans.pegawai_id', '=', 'pegawais.id')
                        ->select('ruangans.id', 'ruangans.kode_ruangan', 'ruangans.nama_ruangan', 'pegawais.nama_lengkap', 'ruangans.kondisi')
                        ->get();
        return view('admin.bmn.referensi.data_ruangan', [
            'title' => 'Data Ruangan LLDIKTI',
            'ruangans' => $ruangans,
            'pegawais' => Pegawai::all()
        ]);
    }

    public function detail_ruangan($id)
    {
        return view('admin.bmn.referensi.editRuangan', [
            'title' => 'Detail Data Ruangan',
            'ruangan' => Ruangan::find($id),
            'pegawais' => Pegawai::all()
        ]);
    }

    public function indexAssets()
    {
        return view('admin.bmn.referensi.data_assets', [
            'title' => 'Data Assets LLDIKTI',
            'assets' => Asset::all()
        ]);
    }

    public function detail_asset($id)
    {
        return view('admin.bmn.referensi.editAsset', [
            'title' => 'Detail Data Asset',
            'asset' => Asset::find($id),
            'pegawais' => Pegawai::all()
        ]);
    }

    public function indexPeminjaman($status = 'pengajuan')
    {
        $peminjaman = DB::table('data_penanggungjawabs')
                        ->join('pegawais', 'data_penanggungjawabs.pegawai_id', '=', 'pegawais.id')
                        ->join('assets', 'data_penanggungjawabs.asset_id', '=', 'assets.id')
                        ->select('data_penanggungjawabs.id as idPenanggungJawab', 'assets.id as idAsset', 'pegawais.nama_lengkap', 'assets.nama_barang', 'data_penanggungjawabs.tgl_mulai_digunakan', 'data_penanggungjawabs.status')
                        ->where('data_penanggungjawabs.status', $status)
                        ->get();
        return view('admin.bmn.transaksi.peminjaman', [
            'title' => 'Data Peminjaman Asset LLDIKTI',
            'peminjamans' => $peminjaman
        ]);
    }

    public function indexServiceAsset($id)
    {
        return view('admin.bmn.referensi.perbaikan_asset', [
            'title' => 'Formulir Perbaikan Asset LLDIKTI',
            'asset' => Asset::find($id),
            'pegawais' => Pegawai::all(),
        ]);
    }

    public function indexServiceKendaraan($id)
    {
        return view('admin.bmn.referensi.perbaikan_kendaraan', [
            'title' => 'Formulir Perbaikan kendaraan LLDIKTI',
            'kendaraan' => Kendaraan::find($id),
        ]);
    }
    
    public function indexServiceRuangan($id)
    {
        return view('admin.bmn.referensi.perbaikan_ruangan', [
            'title' => 'Formulir Perbaikan Ruangan LLDIKTI',
            'ruangan' => Ruangan::find($id),
        ]);
    }

    public function indexPerbaikanAsset($status = 'pengajuan')
    {
        $service_pegawai = DB::table('permohonans')
                        ->join('pegawais', 'permohonans.data_penanggungjawab_id', '=', 'pegawais.id')
                        ->join('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->select('permohonans.id as idService', 'pegawais.nama_lengkap', 'assets.nama_barang', 'permohonans.status')
                        ->where('permohonans.versi_id', session('versi'))
                        ->where('permohonans.status', $status)
                        ->get();
        // ddd($service_pegawai);
        $service_admin = DB::table('permohonans')
                        ->join('administrators', 'permohonans.admin', '=', 'administrators.id')
                        ->join('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->select('permohonans.id as idService', 'administrators.username', 'assets.nama_barang', 'permohonans.status')
                        ->where('permohonans.versi_id', session('versi'))
                        ->where('permohonans.status', $status)
                        ->get();
        return view('admin.bmn.transaksi.service_assets', [
            'title' => 'Services Asset LLDIKTI',
            'servicePegawais' => $service_pegawai,
            'serviceAdmins' => $service_admin,
        ]);
    }

    public function indexPerbaikanKendaraan($status = 'pengajuan')
    {
        $service_admin_Kendaraan = DB::table('permohonans')
                        ->join('administrators', 'permohonans.admin', '=', 'administrators.id')
                        ->join('kendaraans', 'permohonans.kendaraan_id', '=', 'kendaraans.id')
                        ->select('permohonans.id as idService', 'administrators.username', 'kendaraans.merek', 'kendaraans.no_polisi', 'permohonans.status')
                        ->where('permohonans.versi_id', session('versi'))
                        ->where('permohonans.status', $status)
                        ->get();
        return view('admin.bmn.transaksi.kendaraan.index', [
            'title' => 'Services Kendaraan LLDIKTI',
            'serviceKendaraans' => $service_admin_Kendaraan
        ]);
    }

    public function indexPerbaikanRuangan($status = 'pengajuan')
    {
        $ruangan = DB::table('permohonans')
                        ->join('administrators', 'permohonans.admin', '=', 'administrators.id')
                        ->join('ruangans', 'permohonans.ruangan_id', '=', 'ruangans.id')
                        ->select('permohonans.id as idService', 'administrators.username', 'ruangans.nama_ruangan', 'ruangans.kode_ruangan', 'permohonans.status')
                        ->where('permohonans.versi_id', session('versi'))
                        ->where('permohonans.status', $status)
                        ->get();
        return view('admin.bmn.transaksi.ruangan.index', [
            'title' => 'Services Ruangan LLDIKTI',
            'serviceRuangans' => $ruangan
        ]);
    }

    public function detailPerbaikanAsset($id)
    {
        $asset = DB::table('permohonans')
                        ->join('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->select('assets.id', 'assets.nama_barang')
                        ->where('permohonans.id', $id)
                        ->get();
        $komponens = DB::table('komponen_diperlukans')
                        ->select('*')
                        ->where('permohonan_id', $id)
                        ->get();
        return view('admin.bmn.transaksi.detail_asset', [
            'title' => 'Services Asset LLDIKTI',
            'permohonan' => Permohonan::find($id),
            'penyedias' => Data_penyedia::all(),
            'asset' => $asset,
            'komponens' => $komponens
        ]);
    }

    public function detailPerbaikanKendaraan($id)
    {
        $asset = DB::table('permohonans')
                        ->join('kendaraans', 'permohonans.kendaraan_id', '=', 'kendaraans.id')
                        ->select('kendaraans.id', 'kendaraans.merek', 'kendaraans.no_polisi')
                        ->where('permohonans.id', $id)
                        ->get();
        $komponens = DB::table('komponen_diperlukans')
                        ->select('*')
                        ->where('permohonan_id', $id)
                        ->get();
        return view('admin.bmn.transaksi.kendaraan.detail', [
            'title' => 'Services Asset LLDIKTI',
            'permohonan' => Permohonan::find($id),
            'penyedias' => Data_penyedia::all(),
            'asset' => $asset,
            'komponens' => $komponens
        ]);
    }
    
    public function detailPerbaikanRuangan($id)
    {
        $asset = DB::table('permohonans')
                        ->join('ruangans', 'permohonans.ruangan_id', '=', 'ruangans.id')
                        ->select('ruangans.id', 'ruangans.nama_ruangan', 'ruangans.kode_ruangan')
                        ->where('permohonans.id', $id)
                        ->get();
        $komponens = DB::table('komponen_diperlukans')
                        ->select('*')
                        ->where('permohonan_id', $id)
                        ->get();
        return view('admin.bmn.transaksi.ruangan.detail', [
            'title' => 'Services Asset LLDIKTI',
            'permohonan' => Permohonan::find($id),
            'penyedias' => Data_penyedia::all(),
            'asset' => $asset,
            'komponens' => $komponens
        ]);
    }

    public function indexPerbaikanAssetPembayaran()
    {
        $penyedia = DB::table('permohonans')
                        ->join('data_penyedias', 'permohonans.data_penyedia_id', '=', 'data_penyedias.id')
                        ->join('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->select('data_penyedias.id as idPenyedia', 'data_penyedias.nama_CV', 'data_penyedias.kategori', 'assets.nama_barang', 'permohonans.status')
                        ->where('permohonans.status', 'pembayaran')
                        // ->groupBy('data_penyedias.id')
                        ->get();
        return view('admin.bmn.transaksi.pembayaran_asset', [
            'title' => 'Services Asset Pelaporan LLDIKTI',
            'penyedias' => $penyedia
        ]);
    }

    public function indexPerbaikanKendaraanPembayaran()
    {
        $penyedia = DB::table('permohonans')
                        ->join('data_penyedias', 'permohonans.data_penyedia_id', '=', 'data_penyedias.id')
                        ->join('kendaraans', 'permohonans.kendaraan_id', '=', 'kendaraans.id')
                        ->select('data_penyedias.id as idPenyedia', 'data_penyedias.nama_CV', 'data_penyedias.kategori', 'kendaraans.merek', 'kendaraans.no_polisi', 'permohonans.status')
                        ->where('permohonans.status', 'pembayaran')
                        // ->groupBy('data_penyedias.id')
                        ->get();
        return view('admin.bmn.transaksi.kendaraan.pembayaran', [
            'title' => 'Services Kendaraan Pelaporan LLDIKTI',
            'penyedias' => $penyedia
        ]);
    }
    
    public function indexPerbaikanRuanganPembayaran()
    {
        $penyedia = DB::table('permohonans')
                        ->join('data_penyedias', 'permohonans.data_penyedia_id', '=', 'data_penyedias.id')
                        ->join('ruangans', 'permohonans.ruangan_id', '=', 'ruangans.id')
                        ->select('data_penyedias.id as idPenyedia', 'data_penyedias.nama_CV', 'data_penyedias.kategori', 'ruangans.nama_ruangan', 'ruangans.kode_ruangan', 'permohonans.status')
                        ->where('permohonans.status', 'pembayaran')
                        // ->groupBy('data_penyedias.id')
                        ->get();
        return view('admin.bmn.transaksi.ruangan.pembayaran', [
            'title' => 'Services Kendaraan Pelaporan LLDIKTI',
            'penyedias' => $penyedia
        ]);
    }
    
    public function detailPenyediaAsset($id)
    {
        $permohonan_admin = DB::table('permohonans')
                        ->join('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->join('administrators', 'permohonans.admin', '=', 'administrators.id')
                        ->select('permohonans.id as idPermohonan', 'assets.nama_barang', 'administrators.username', 'permohonans.tgl_selesai', 'permohonans.status')
                        ->where('permohonans.status', 'pembayaran')
                        ->where('permohonans.data_penyedia_id', $id)
                        ->get();
        $permohonan_pegawai = DB::table('permohonans')
                        ->join('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->join('pegawais', 'permohonans.data_penanggungjawab_id', '=', 'pegawais.id')
                        ->select('permohonans.id as idPermohonan', 'assets.nama_barang', 'pegawais.nama_lengkap', 'permohonans.tgl_selesai', 'permohonans.status')
                        ->where('permohonans.status', 'pembayaran')
                        ->where('permohonans.data_penyedia_id', $id)
                        ->get();
        return view('admin.bmn.transaksi.penyedia_asset', [
            'title' => 'Services Asset Pelaporan LLDIKTI',
            'penyedia' => Data_penyedia::find($id),
            'permohonans' => $permohonan_admin,
            'permohonanPegawais' => $permohonan_pegawai,
        ]);
    }

    public function detailPenyediaKendaraan($id)
    {
        $permohonan = DB::table('permohonans')
                        ->join('kendaraans', 'permohonans.kendaraan_id', '=', 'kendaraans.id')
                        ->join('administrators', 'permohonans.admin', '=', 'administrators.id')
                        ->select('permohonans.id as idPermohonan', 'kendaraans.merek', 'kendaraans.no_polisi', 'administrators.username', 'permohonans.tgl_selesai', 'permohonans.status')
                        ->where('permohonans.status', 'pembayaran')
                        ->where('permohonans.data_penyedia_id', $id)
                        ->get();
        return view('admin.bmn.transaksi.kendaraan.detail_penyedia', [
            'title' => 'Services Kendaraan Pelaporan LLDIKTI',
            'penyedia' => Data_penyedia::find($id),
            'permohonans' => $permohonan,
        ]);
    }
    
    public function detailPenyediaRuangan($id)
    {
        $permohonan = DB::table('permohonans')
                        ->join('ruangans', 'permohonans.ruangan_id', '=', 'ruangans.id')
                        ->join('administrators', 'permohonans.admin', '=', 'administrators.id')
                        ->select('permohonans.id as idPermohonan', 'ruangans.nama_ruangan', 'ruangans.kode_ruangan', 'administrators.username', 'permohonans.tgl_selesai', 'permohonans.status')
                        ->where('permohonans.status', 'pembayaran')
                        ->where('permohonans.data_penyedia_id', $id)
                        ->get();
        return view('admin.bmn.transaksi.ruangan.detail_penyedia', [
            'title' => 'Services Kendaraan Pelaporan LLDIKTI',
            'penyedia' => Data_penyedia::find($id),
            'permohonans' => $permohonan,
        ]);
    }

    public function indexKeuanganService($status = 'verifikasi-1')
    {
        $penyedia = DB::table('permohonans')
                        ->join('data_penyedias', 'permohonans.data_penyedia_id', '=', 'data_penyedias.id')
                        ->join('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->select('data_penyedias.id as idPenyedia', 'data_penyedias.nama_CV', 'data_penyedias.kategori', 'assets.nama_barang', 'permohonans.is_acceptKeu', 'permohonans.service_id as idService')
                        ->where('permohonans.is_acceptKeu', $status)
                        // ->groupBy('data_penyedias.id')
                        ->get();
        $penyediaKendaraan = DB::table('permohonans')
                        ->join('data_penyedias', 'permohonans.data_penyedia_id', '=', 'data_penyedias.id')
                        ->join('kendaraans', 'permohonans.kendaraan_id', '=', 'kendaraans.id')
                        ->select('data_penyedias.id as idPenyedia', 'data_penyedias.nama_CV', 'data_penyedias.kategori', 'kendaraans.merek', 'kendaraans.no_polisi', 'permohonans.is_acceptKeu', 'permohonans.service_id as idService')
                        ->where('permohonans.is_acceptKeu', $status)
                        // ->groupBy('data_penyedias.id')
                        ->get();
        $penyediaRuangan = DB::table('permohonans')
                        ->join('data_penyedias', 'permohonans.data_penyedia_id', '=', 'data_penyedias.id')
                        ->join('ruangans', 'permohonans.ruangan_id', '=', 'ruangans.id')
                        ->select('data_penyedias.id as idPenyedia', 'data_penyedias.nama_CV', 'data_penyedias.kategori', 'ruangans.nama_ruangan', 'ruangans.kode_ruangan', 'permohonans.is_acceptKeu', 'permohonans.service_id as idService')
                        ->where('permohonans.is_acceptKeu', $status)
                        // ->groupBy('data_penyedias.id')
                        ->get();
        return view('admin.bmn.transaksi.keuangan.index', [
            'title' => 'Keuangan Services Asset Pelaporan LLDIKTI',
            'penyedias' => $penyedia,
            'penyediaKendaraans' => $penyediaKendaraan,
            'penyediaRuangans' => $penyediaRuangan
        ]);
    }
    public function indexKeuanganServiceRiwayat($status = 'riwayat')
    {
        $penyedia = DB::table('permohonans')
                        ->join('data_penyedias', 'permohonans.data_penyedia_id', '=', 'data_penyedias.id')
                        ->join('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->select('data_penyedias.id as idPenyedia', 'data_penyedias.nama_CV', 'data_penyedias.kategori', 'assets.nama_barang', 'permohonans.is_acceptKeu', 'permohonans.service_id as idService', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn', 'permohonans.tgl_bayar',)
                        ->where('permohonans.is_acceptKeu', $status)
                        // ->groupBy('data_penyedias.id')
                        ->get();
        $penyediaKendaraan = DB::table('permohonans')
                        ->join('data_penyedias', 'permohonans.data_penyedia_id', '=', 'data_penyedias.id')
                        ->join('kendaraans', 'permohonans.kendaraan_id', '=', 'kendaraans.id')
                        ->select('data_penyedias.id as idPenyedia', 'data_penyedias.nama_CV', 'data_penyedias.kategori', 'kendaraans.merek', 'kendaraans.no_polisi', 'permohonans.is_acceptKeu', 'permohonans.service_id as idService', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn', 'permohonans.tgl_bayar',)
                        ->where('permohonans.is_acceptKeu', $status)
                        // ->groupBy('data_penyedias.id')
                        ->get();
        $penyediaRuangan = DB::table('permohonans')
                        ->join('data_penyedias', 'permohonans.data_penyedia_id', '=', 'data_penyedias.id')
                        ->join('ruangans', 'permohonans.ruangan_id', '=', 'ruangans.id')
                        ->select('data_penyedias.id as idPenyedia', 'data_penyedias.nama_CV', 'data_penyedias.kategori', 'ruangans.nama_ruangan', 'ruangans.kode_ruangan', 'permohonans.is_acceptKeu', 'permohonans.service_id as idService', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn', 'permohonans.tgl_bayar',)
                        ->where('permohonans.is_acceptKeu', $status)
                        // ->groupBy('data_penyedias.id')
                        ->get();
        return view('admin.bmn.transaksi.keuangan.riwayat', [
            'title' => 'Keuangan Services Asset Pelaporan LLDIKTI',
            'penyedias' => $penyedia,
            'penyediaKendaraans' => $penyediaKendaraan,
            'penyediaRuangans' => $penyediaRuangan
        ]);
    }

    public function detailKeuanganPerbaikanAsset($id)
    {
        
        $penyedia = DB::table('services')
                        ->join('data_penyedias', 'services.penyedia_id', '=', 'data_penyedias.id')
                        ->select('data_penyedias.*', 'services.id')
                        ->where('services.id', $id)
                        ->get();
        $dokumen = DB::table('dokumen_permohonans')
                        ->join('services', 'dokumen_permohonans.service_id', '=', 'services.id')
                        ->select('dokumen_permohonans.id as idDokumen', 'dokumen_permohonans.nama_dokumen', 'dokumen_permohonans.file', 'dokumen_permohonans.status', 'services.id')
                        ->where('services.id', $id)
                        ->get();
        $permohonan_admin = DB::table('permohonans')
                        ->join('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->join('administrators', 'permohonans.admin', '=', 'administrators.id')
                        ->select('permohonans.id as idPermohonan', 'assets.nama_barang', 'administrators.username', 'permohonans.tgl_selesai', 'permohonans.status', 'permohonans.nominal', 'permohonans.pph', 'permohonans.total', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn')
                        ->where('permohonans.service_id', $id)
                        ->where('permohonans.is_acceptKeu', 'verifikasi-1')
                        ->get();
        $permohonan_pegawai = DB::table('permohonans')
                        ->join('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->join('pegawais', 'permohonans.data_penanggungjawab_id', '=', 'pegawais.id')
                        ->select('permohonans.id as idPermohonan', 'assets.nama_barang', 'pegawais.nama_lengkap', 'permohonans.tgl_selesai', 'permohonans.status', 'permohonans.nominal', 'permohonans.pph', 'permohonans.total', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn')
                        ->where('permohonans.service_id', $id)
                        ->where('permohonans.is_acceptKeu', 'verifikasi-1')
                        ->get();
        $permohonan_kendaraan = DB::table('permohonans')
                        ->join('kendaraans', 'permohonans.kendaraan_id', '=', 'kendaraans.id')
                        ->join('administrators', 'permohonans.admin', '=', 'administrators.id')
                        ->select('permohonans.id as idPermohonan', 'kendaraans.merek', 'kendaraans.no_polisi', 'administrators.username', 'permohonans.tgl_selesai', 'permohonans.status', 'permohonans.nominal', 'permohonans.pph', 'permohonans.total', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn')
                        ->where('permohonans.service_id', $id)
                        ->where('permohonans.is_acceptKeu', 'verifikasi-1')
                        ->get();
        $permohonan_ruangan = DB::table('permohonans')
                        ->join('ruangans', 'permohonans.ruangan_id', '=', 'ruangans.id')
                        ->join('administrators', 'permohonans.admin', '=', 'administrators.id')
                        ->select('permohonans.id as idPermohonan', 'ruangans.nama_ruangan', 'ruangans.kode_ruangan', 'administrators.username', 'permohonans.tgl_selesai', 'permohonans.status', 'permohonans.nominal', 'permohonans.pph', 'permohonans.total', 'permohonans.is_acceptKeu', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn')
                        ->where('permohonans.service_id', $id)
                        ->where('permohonans.is_acceptKeu', 'verifikasi-1')
                        ->get();
        return view('admin.bmn.transaksi.keuangan.detail', [
            'title' => 'Keuangan Services Asset Pelaporan LLDIKTI',
            'penyedia' => $penyedia[0],
            'permohonans' => $permohonan_admin,
            'permohonanPegawais' => $permohonan_pegawai,
            'permohonanKendaraans' => $permohonan_kendaraan,
            'permohonanRuangans' => $permohonan_ruangan,
            'dokumens' => $dokumen,
            'komponens' => Komponen_diperlukan::all(),
        ]);
    }
    
    public function detailKeuanganPerbaikanAsset_riwayat($id)
    {
        $penyedia = DB::table('services')
                        ->join('data_penyedias', 'services.penyedia_id', '=', 'data_penyedias.id')
                        ->select('data_penyedias.*', 'services.id')
                        ->where('services.id', $id)
                        ->get();        
        $dokumen = DB::table('dokumen_permohonans')
                        ->join('services', 'dokumen_permohonans.service_id', '=', 'services.id')
                        ->select('dokumen_permohonans.id as idDokumen', 'dokumen_permohonans.nama_dokumen', 'dokumen_permohonans.file', 'dokumen_permohonans.status', 'services.id')
                        ->where('services.id', $id)
                        ->get();
        $permohonan_admin = DB::table('permohonans')
                        ->join('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->join('administrators', 'permohonans.admin', '=', 'administrators.id')
                        ->select('permohonans.id as idPermohonan', 'assets.nama_barang', 'administrators.username', 'permohonans.tgl_selesai', 'permohonans.status', 'permohonans.nominal', 'permohonans.pph', 'permohonans.total', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn')
                        ->where('permohonans.service_id', $id)
                        ->where('permohonans.is_acceptKeu', 'selesai')
                        ->get();
        $permohonan_pegawai = DB::table('permohonans')
                        ->join('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->join('pegawais', 'permohonans.data_penanggungjawab_id', '=', 'pegawais.id')
                        ->select('permohonans.id as idPermohonan', 'assets.nama_barang', 'pegawais.nama_lengkap', 'permohonans.tgl_selesai', 'permohonans.status', 'permohonans.nominal', 'permohonans.pph', 'permohonans.total', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn')
                        ->where('permohonans.service_id', $id)
                        ->where('permohonans.is_acceptKeu', 'selesai')
                        ->get();
        $permohonan_kendaraan = DB::table('permohonans')
                        ->join('kendaraans', 'permohonans.kendaraan_id', '=', 'kendaraans.id')
                        ->join('administrators', 'permohonans.admin', '=', 'administrators.id')
                        ->select('permohonans.id as idPermohonan', 'kendaraans.merek', 'kendaraans.no_polisi', 'administrators.username', 'permohonans.tgl_selesai', 'permohonans.status', 'permohonans.nominal', 'permohonans.pph', 'permohonans.total', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn')
                        ->where('permohonans.service_id', $id)
                        ->where('permohonans.is_acceptKeu', 'selesai')
                        ->get();
        $permohonan_ruangan = DB::table('permohonans')
                        ->join('ruangans', 'permohonans.ruangan_id', '=', 'ruangans.id')
                        ->join('administrators', 'permohonans.admin', '=', 'administrators.id')
                        ->select('permohonans.id as idPermohonan', 'ruangans.nama_ruangan', 'ruangans.kode_ruangan', 'administrators.username', 'permohonans.tgl_selesai', 'permohonans.status', 'permohonans.nominal', 'permohonans.pph', 'permohonans.total', 'permohonans.is_acceptKeu', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn')
                        ->where('permohonans.service_id', $id)
                        ->where('permohonans.is_acceptKeu', 'selesai')
                        ->get();
        return view('admin.bmn.transaksi.keuangan.detail_riwayat', [
            'title' => 'Keuangan Services Asset Pelaporan LLDIKTI',
            'penyedia' => $penyedia[0],
            'permohonans' => $permohonan_admin,
            'permohonanPegawais' => $permohonan_pegawai,
            'permohonanKendaraans' => $permohonan_kendaraan,
            'permohonanRuangans' => $permohonan_ruangan,
            'dokumens' => $dokumen,
            'komponens' => Komponen_diperlukan::all(),
        ]);
    }

    public function indexBendaharaService($status = 'approval-1')
    {
        $penyedia = DB::table('permohonans')
                        ->join('data_penyedias', 'permohonans.data_penyedia_id', '=', 'data_penyedias.id')
                        ->join('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->select('data_penyedias.id as idPenyedia', 'data_penyedias.nama_CV', 'data_penyedias.kategori', 'assets.nama_barang', 'permohonans.is_acceptBend', 'permohonans.service_id as idService', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn', 'permohonans.tgl_bayar',)
                        ->where('permohonans.is_acceptBend', $status)
                        // ->groupBy('data_penyedias.id')
                        ->get();
        $penyediaKendaraan = DB::table('permohonans')
                        ->join('data_penyedias', 'permohonans.data_penyedia_id', '=', 'data_penyedias.id')
                        ->join('kendaraans', 'permohonans.kendaraan_id', '=', 'kendaraans.id')
                        ->select('data_penyedias.id as idPenyedia', 'data_penyedias.nama_CV', 'data_penyedias.kategori', 'kendaraans.merek', 'kendaraans.no_polisi', 'permohonans.is_acceptBend', 'permohonans.service_id as idService', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn', 'permohonans.tgl_bayar',)
                        ->where('permohonans.is_acceptBend', $status)
                        // ->groupBy('data_penyedias.id')
                        ->get();
        $penyediaRuangan = DB::table('permohonans')
                        ->join('data_penyedias', 'permohonans.data_penyedia_id', '=', 'data_penyedias.id')
                        ->join('ruangans', 'permohonans.ruangan_id', '=', 'ruangans.id')
                        ->select('data_penyedias.id as idPenyedia', 'data_penyedias.nama_CV', 'data_penyedias.kategori', 'ruangans.nama_ruangan', 'ruangans.kode_ruangan', 'permohonans.is_acceptBend', 'permohonans.service_id as idService', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn', 'permohonans.tgl_bayar',)
                        ->where('permohonans.is_acceptBend', $status)
                        // ->groupBy('data_penyedias.id')
                        ->get();
        return view('admin.bmn.transaksi.bendahara.index', [
            'title' => 'Bendahara Services Asset Pelaporan LLDIKTI',
            'penyedias' => $penyedia,
            'penyediakendaraans' => $penyediaKendaraan,
            'penyediaruangans' => $penyediaRuangan,
        ]);
    }    
    public function indexBendaharaServiceRiwayat($status = 'selesai')
    {
        $penyedia = DB::table('permohonans')
                        ->join('data_penyedias', 'permohonans.data_penyedia_id', '=', 'data_penyedias.id')
                        ->join('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->select('data_penyedias.id as idPenyedia', 'data_penyedias.nama_CV', 'data_penyedias.kategori', 'assets.nama_barang', 'permohonans.is_acceptBend', 'permohonans.service_id as idService', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn', 'permohonans.tgl_bayar',)
                        ->where('permohonans.is_acceptBend', $status)
                        // ->groupBy('data_penyedias.id')
                        ->get();
        $penyediaKendaraan = DB::table('permohonans')
                        ->join('data_penyedias', 'permohonans.data_penyedia_id', '=', 'data_penyedias.id')
                        ->join('kendaraans', 'permohonans.kendaraan_id', '=', 'kendaraans.id')
                        ->select('data_penyedias.id as idPenyedia', 'data_penyedias.nama_CV', 'data_penyedias.kategori', 'kendaraans.merek', 'kendaraans.no_polisi', 'permohonans.is_acceptBend', 'permohonans.service_id as idService', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn', 'permohonans.tgl_bayar',)
                        ->where('permohonans.is_acceptBend', $status)
                        // ->groupBy('data_penyedias.id')
                        ->get();
        $penyediaRuangan = DB::table('permohonans')
                        ->join('data_penyedias', 'permohonans.data_penyedia_id', '=', 'data_penyedias.id')
                        ->join('ruangans', 'permohonans.ruangan_id', '=', 'ruangans.id')
                        ->select('data_penyedias.id as idPenyedia', 'data_penyedias.nama_CV', 'data_penyedias.kategori', 'ruangans.nama_ruangan', 'ruangans.kode_ruangan', 'permohonans.is_acceptBend', 'permohonans.service_id as idService', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn', 'permohonans.tgl_bayar',)
                        ->where('permohonans.is_acceptBend', $status)
                        // ->groupBy('data_penyedias.id')
                        ->get();
        return view('admin.bmn.transaksi.bendahara.riwayat', [
            'title' => 'Bendahara Services Asset Pelaporan LLDIKTI',
            'penyedias' => $penyedia,
            'penyediakendaraans' => $penyediaKendaraan,
            'penyediaruangans' => $penyediaRuangan,
        ]);
    }    

    public function detailBendaharaPerbaikanAsset($id)
    {
        $penyedia = DB::table('services')
                        ->join('data_penyedias', 'services.penyedia_id', '=', 'data_penyedias.id')
                        ->select('data_penyedias.*', 'services.id')
                        ->where('services.id', $id)
                        ->get(); 
        $dokumen = DB::table('dokumen_permohonans')
                        ->join('services', 'dokumen_permohonans.service_id', '=', 'services.id')
                        ->select('dokumen_permohonans.id as idDokumen', 'dokumen_permohonans.nama_dokumen', 'dokumen_permohonans.file', 'dokumen_permohonans.status', 'services.id')
                        ->where('services.id', $id)
                        ->get();
        $permohonan_admin = DB::table('permohonans')
                        ->join('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->join('administrators', 'permohonans.admin', '=', 'administrators.id')
                        ->select('permohonans.id as idPermohonan', 'assets.nama_barang', 'administrators.username', 'permohonans.tgl_selesai', 'permohonans.status', 'permohonans.nominal', 'permohonans.pph', 'permohonans.total', 'permohonans.akun_x_rkakl_id', 'permohonans.ref_sbm_id', 'permohonans.status_pembayaran', 'permohonans.is_acceptBend', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn', 'permohonans.tgl_bayar')
                        ->where('permohonans.service_id', $id)
                        ->where('permohonans.is_acceptBend', 'approval-1')
                        ->get();
        $permohonan_pegawai = DB::table('permohonans')
                        ->join('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->join('pegawais', 'permohonans.data_penanggungjawab_id', '=', 'pegawais.id')
                        ->select('permohonans.id as idPermohonan', 'assets.nama_barang', 'pegawais.nama_lengkap', 'permohonans.tgl_selesai', 'permohonans.status', 'permohonans.nominal', 'permohonans.pph', 'permohonans.total', 'permohonans.akun_x_rkakl_id', 'permohonans.ref_sbm_id', 'permohonans.status_pembayaran', 'permohonans.is_acceptBend', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn', 'permohonans.tgl_bayar')
                        ->where('permohonans.service_id', $id)
                        ->where('permohonans.is_acceptBend', 'approval-1')
                        ->get();
        $permohonan_kendaraan = DB::table('permohonans')
                        ->join('kendaraans', 'permohonans.kendaraan_id', '=', 'kendaraans.id')
                        ->join('administrators', 'permohonans.admin', '=', 'administrators.id')
                        ->select('permohonans.id as idPermohonan', 'kendaraans.merek', 'kendaraans.no_polisi', 'administrators.username', 'permohonans.tgl_selesai', 'permohonans.status', 'permohonans.nominal', 'permohonans.pph', 'permohonans.total', 'permohonans.akun_x_rkakl_id', 'permohonans.ref_sbm_id', 'permohonans.status_pembayaran', 'permohonans.is_acceptBend', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn', 'permohonans.tgl_bayar')
                        ->where('permohonans.service_id', $id)
                        ->where('permohonans.is_acceptBend', 'approval-1')
                        ->get();
        $permohonan_ruangan = DB::table('permohonans')
                        ->join('ruangans', 'permohonans.ruangan_id', '=', 'ruangans.id')
                        ->join('administrators', 'permohonans.admin', '=', 'administrators.id')
                        ->select('permohonans.id as idPermohonan', 'ruangans.nama_ruangan', 'ruangans.kode_ruangan', 'administrators.username', 'permohonans.tgl_selesai', 'permohonans.status', 'permohonans.nominal', 'permohonans.pph', 'permohonans.total', 'permohonans.akun_x_rkakl_id', 'permohonans.ref_sbm_id', 'permohonans.status_pembayaran', 'permohonans.is_acceptBend', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn', 'permohonans.tgl_bayar')
                        ->where('permohonans.service_id', $id)
                        ->where('permohonans.is_acceptBend', 'approval-1')
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
        return view('admin.bmn.transaksi.bendahara.detail', [
            'title' => 'Bendahara Services Asset Pelaporan LLDIKTI',
            'penyedia' => $penyedia[0],
            'permohonans' => $permohonan_admin,
            'permohonanPegawais' => $permohonan_pegawai,
            'permohonanKendaraans' => $permohonan_kendaraan,
            'permohonanRuangans' => $permohonan_ruangan,
            'dokumens' => $dokumen,
            'komponens' => Komponen_diperlukan::all(),
            "sbms" => Ref_sbm::all(),
            'akuns' => $akuns
        ]);
    }
    
    public function detailBendaharaPerbaikanAssetRiwayat($id)
    {
        $penyedia = DB::table('services')
                        ->join('data_penyedias', 'services.penyedia_id', '=', 'data_penyedias.id')
                        ->select('data_penyedias.*', 'services.id')
                        ->where('services.id', $id)
                        ->get();         
        $dokumen = DB::table('dokumen_permohonans')
                        ->join('services', 'dokumen_permohonans.service_id', '=', 'services.id')
                        ->select('dokumen_permohonans.id as idDokumen', 'dokumen_permohonans.nama_dokumen', 'dokumen_permohonans.file', 'dokumen_permohonans.status', 'services.id')
                        ->where('services.id', $id)
                        ->get();
        $permohonan_admin = DB::table('permohonans')
                        ->join('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->join('administrators', 'permohonans.admin', '=', 'administrators.id')
                        ->select('permohonans.id as idPermohonan', 'assets.nama_barang', 'administrators.username', 'permohonans.tgl_selesai', 'permohonans.status', 'permohonans.nominal', 'permohonans.pph', 'permohonans.total', 'permohonans.akun_x_rkakl_id', 'permohonans.ref_sbm_id', 'permohonans.status_pembayaran', 'permohonans.is_acceptBend', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn', 'permohonans.tgl_bayar')
                        ->where('permohonans.service_id', $id)
                        ->where('permohonans.is_acceptBend', 'selesai')
                        ->get();
        $permohonan_pegawai = DB::table('permohonans')
                        ->join('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->join('pegawais', 'permohonans.data_penanggungjawab_id', '=', 'pegawais.id')
                        ->select('permohonans.id as idPermohonan', 'assets.nama_barang', 'pegawais.nama_lengkap', 'permohonans.tgl_selesai', 'permohonans.status', 'permohonans.nominal', 'permohonans.pph', 'permohonans.total', 'permohonans.akun_x_rkakl_id', 'permohonans.ref_sbm_id', 'permohonans.status_pembayaran', 'permohonans.is_acceptBend', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn', 'permohonans.tgl_bayar')
                        ->where('permohonans.service_id', $id)
                        ->where('permohonans.is_acceptBend', 'selesai')
                        ->get();
        $permohonan_kendaraan = DB::table('permohonans')
                        ->join('kendaraans', 'permohonans.kendaraan_id', '=', 'kendaraans.id')
                        ->join('administrators', 'permohonans.admin', '=', 'administrators.id')
                        ->select('permohonans.id as idPermohonan', 'kendaraans.merek', 'kendaraans.no_polisi', 'administrators.username', 'permohonans.tgl_selesai', 'permohonans.status', 'permohonans.nominal', 'permohonans.pph', 'permohonans.total', 'permohonans.akun_x_rkakl_id', 'permohonans.ref_sbm_id', 'permohonans.status_pembayaran', 'permohonans.is_acceptBend', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn', 'permohonans.tgl_bayar')
                        ->where('permohonans.service_id', $id)
                        ->where('permohonans.is_acceptBend', 'selesai')
                        ->get();
        $permohonan_ruangan = DB::table('permohonans')
                        ->join('ruangans', 'permohonans.ruangan_id', '=', 'ruangans.id')
                        ->join('administrators', 'permohonans.admin', '=', 'administrators.id')
                        ->select('permohonans.id as idPermohonan', 'ruangans.nama_ruangan', 'ruangans.kode_ruangan', 'administrators.username', 'permohonans.tgl_selesai', 'permohonans.status', 'permohonans.nominal', 'permohonans.pph', 'permohonans.total', 'permohonans.akun_x_rkakl_id', 'permohonans.ref_sbm_id', 'permohonans.status_pembayaran', 'permohonans.is_acceptBend', 'permohonans.pph22', 'permohonans.pph23', 'permohonans.ppn', 'permohonans.tgl_bayar')
                        ->where('permohonans.service_id', $id)
                        ->where('permohonans.is_acceptBend', 'selesai')
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
        return view('admin.bmn.transaksi.bendahara.detail_riwayat', [
            'title' => 'Bendahara Riwayat Services Asset Pelaporan LLDIKTI',
            'penyedia' => $penyedia[0],
            'permohonans' => $permohonan_admin,
            'permohonanPegawais' => $permohonan_pegawai,
            'permohonanKendaraans' => $permohonan_kendaraan,
            'permohonanRuangans' => $permohonan_ruangan,
            'dokumens' => $dokumen,
            'komponens' => Komponen_diperlukan::all(),
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

    public function storePenyedia(Request $request)
    {
        db::table('data_penyedias')->insertOrIgnore([
            'NPWP' => $request->NPWP,
            'nama_CV' => $request->namaCV,
            'penanggung_jawab' => $request->penanggungJawab,
            'jabatan' => $request->jabatan,
            'no_telp' => $request->telps,
            'alamat' => $request->alamat,
            'kategori' => $request->kategori,
            'tahun' => $request->tahun,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('data_penyedia')->with('success', 'Data telah ditambahkan!');
    }

    public function storeKendaraan(Request $request)
    {
        db::table('kendaraans')->insertOrIgnore([
            'merek' => $request->merek,
            'no_polisi' => $request->no_polisi,
            'no_mesin' => $request->no_mesin,
            'no_stnk' => $request->no_stnk,
            'no_bpkb' => $request->no_bpkb,
            'legalitas' => $request->legalitas,
            'legalitas_5th' => $request->legalitas5tahun,
            'tipe' => $request->tipe,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('data_kendaraan')->with('success', 'Data telah ditambahkan!');
    }

    public function storeAsset(Request $request)
    {
        db::table('assets')->insertOrIgnore([
            'kode_barang' => $request->kode,
            'nama_barang' => $request->nama,
            'NUP' => $request->nup,
            'nama_merek' => $request->merek,
            'tgl_beli' => $request->tgl_beli,
            'jenis_perawatan' => $request->perawatan,
            'status_kondisi' => $request->kondisi,
            'status_peminjaman' => 'Tidak Dipakai',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('data_assets')->with('success', 'Data telah ditambahkan!');
    }

    public function storeAssetPeminjaman(Request $request)
    {
        DB::table('data_penanggungjawabs')->insertOrIgnore([
            'tgl_mulai_digunakan' => $request->mulai,
            'tgl_selesai' => $request->selesai,
            'asset_id' => $request->idAsset,
            'pegawai_id' => $request->penanggungjawab,
            'status' => 'pengajuan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        db::table('assets')
        ->where('id', $request->idAsset)
        ->update([
            'status_peminjaman' => 'pengajuan',
            'updated_at' => now(),
        ]);

        return redirect()->route('peminjaman_asset', ['status' => 'pengajuan'])->with('success', 'Data telah ditambahkan!');
    }

    public function storeRuangan(Request $request)
    {
        db::table('ruangans')->insertOrIgnore([
            'kode_ruangan' => $request->kode,
            'nama_ruangan' => $request->nama,
            'pegawai_id' => $request->penanggungjawab,
            'kondisi' => $request->kondisi,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('data_ruangan')->with('success', 'Data telah ditambahkan!');
    }

    public function storeServiceAsset(Request $request)
    {
        $versi = Versi::where('status', 'aktif')->get();
        db::table('permohonans')->insertOrIgnore([
            'asset_id' => $request->idAsset,
            'alasan_ket' => $request->keterangan,
            'status' => 'pengajuan',
            'tgl_permohonan' => now(),
            'admin' => auth('administrator')->user()->id,
            'is_acceptBMN' => 'pengajuan',
            'versi_id' => $versi[0]->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        db::table('assets')
        ->where('id', $request->idAsset)
        ->update([
            'status_kondisi' => 'Proses Pengajuan Service',
            'updated_at' => now(),
        ]);

        return redirect()->route('service-assets', ['status' => 'pengajuan'])->with('success', 'Pengajuan perbaikan telah dikirim!');
    }

    public function storeServiceKendaraan(Request $request)
    {
        $versi = Versi::where('status', 'aktif')->get();
        db::table('permohonans')->insertOrIgnore([
            'kendaraan_id' => $request->idKendaraan,
            'alasan_ket' => $request->keterangan,
            'status' => 'pengajuan',
            'tgl_permohonan' => now(),
            'admin' => auth('administrator')->user()->id,
            'is_acceptBMN' => 'pengajuan',
            'versi_id' => $versi[0]->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        db::table('kendaraans')
        ->where('id', $request->idKendaraan)
        ->update([
            'status' => 'Proses Pengajuan Service',
            'updated_at' => now(),
        ]);

        return redirect()->route('service-kendaraan', ['status' => 'pengajuan'])->with('success', 'Pengajuan perbaikan telah dikirim!');
    }

    public function storeServiceRuangan(Request $request)
    {
        $versi = Versi::where('status', 'aktif')->get();
        db::table('permohonans')->insertOrIgnore([
            'ruangan_id' => $request->idRuangan,
            'alasan_ket' => $request->keterangan,
            'status' => 'pengajuan',
            'tgl_permohonan' => now(),
            'admin' => auth('administrator')->user()->id,
            'is_acceptBMN' => 'pengajuan',
            'versi_id' => $versi[0]->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        db::table('ruangans')
        ->where('id', $request->idRuangan)
        ->update([
            'kondisi' => 'Proses Pengajuan Perbaikan',
            'updated_at' => now(),
        ]);

        return redirect()->route('service-ruangan', ['status' => 'pengajuan'])->with('success', 'Pengajuan perbaikan telah dikirim!');
    }

    public function storeServiceAssetKomponen(Request $request)
    {
        db::table('komponen_diperlukans')->insertOrIgnore([
            'nama_barang' => $request->komponen,
            'frekuensi' => $request->jumlah,
            'permohonan_id' => $request->idPermohonan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('detail_service-asset', ['id' => $request->idPermohonan])->with('success', 'Komponen telah ditambahkan!');
    }

    public function storeServiceKendaraanKomponen(Request $request)
    {
        db::table('komponen_diperlukans')->insertOrIgnore([
            'nama_barang' => $request->komponen,
            'frekuensi' => $request->jumlah,
            'permohonan_id' => $request->idPermohonan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('service_kendaraan_detail', ['id' => $request->idPermohonan])->with('success', 'Komponen telah ditambahkan!');
    }
    
    public function storeServiceRuanganKomponen(Request $request)
    {
        db::table('komponen_diperlukans')->insertOrIgnore([
            'nama_barang' => $request->komponen,
            'frekuensi' => $request->jumlah,
            'permohonan_id' => $request->idPermohonan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('service_ruangan_detail', ['id' => $request->idPermohonan])->with('success', 'Komponen telah ditambahkan!');
    }

    public function storeServiceAssetPembaharuan(Request $request)
    {
        DB::table('permohonans')
            ->where('id', $request->idPermohonan)
            ->update([
                'tgl_permohonan' => $request->tgl_pengajuan,
                'tgl_pemeriksaan' => $request->tgl_pemeriksaan,
                'tgl_pengerjaan' => $request->tgl_pengerjaan,
                'tgl_selesai' => $request->tgl_selesai,
                'status' => $request->status,
                'data_penyedia_id' => $request->penyedia,
                'admin_BMN' => auth('administrator')->user()->id,
                'is_acceptBMN' => $request->status,
                'updated_at' => now(),
        ]);

        if ($request->status == 'pembayaran') {
            # code...
            db::table('assets')
            ->where('id', $request->idAsset)
            ->update([
                'status_kondisi' => "Baik",
                'updated_at' => now(),
            ]);
            
            db::table('data_penanggungjawabs')
            ->where('asset_id', $request->idAsset)
            ->update([
                'status' => "digunakan",
                'updated_at' => now(),
            ]);
        }

        if ($request->status != 'pembayaran') {
            # code...
            db::table('assets')
            ->where('id', $request->idAsset)
            ->update([
                'status_kondisi' => $request->status,
                'updated_at' => now(),
            ]);
        }

        if ($request->status == 'selesai') {
            # code...
            return redirect()->route('pembayaran_service_assets')->with('success', 'Data telah diperbaharui!');
        } else {
            # code...
            return redirect()->route('service-assets', ['status' => $request->status])->with('success', 'Data telah diperbaharui!');
        }
    }

    public function storeServiceKendaraanPembaharuan(Request $request)
    {
        DB::table('permohonans')
            ->where('id', $request->idPermohonan)
            ->update([
                'tgl_permohonan' => $request->tgl_pengajuan,
                'tgl_pemeriksaan' => $request->tgl_pemeriksaan,
                'tgl_pengerjaan' => $request->tgl_pengerjaan,
                'tgl_selesai' => $request->tgl_selesai,
                'status' => $request->status,
                'data_penyedia_id' => $request->penyedia,
                'admin_BMN' => auth('administrator')->user()->id,
                'is_acceptBMN' => $request->status,
                'updated_at' => now(),
        ]);

        if ($request->status == 'pembayaran') {
            # code...
            db::table('kendaraans')
            ->where('id', $request->idAsset)
            ->update([
                'status' => "Baik",
                'updated_at' => now(),
            ]);
        }

        if ($request->status != 'pembayaran') {
            # code...
            db::table('kendaraans')
            ->where('id', $request->idAsset)
            ->update([
                'status' => $request->status,
                'updated_at' => now(),
            ]);
        }
        return redirect()->route('service-kendaraan', ['status' => 'pengajuan'])->with('success', 'Data telah diperbaharui!');
    }
    
    public function storeServiceRuanganPembaharuan(Request $request)
    {
        DB::table('permohonans')
            ->where('id', $request->idPermohonan)
            ->update([
                'tgl_permohonan' => $request->tgl_pengajuan,
                'tgl_pemeriksaan' => $request->tgl_pemeriksaan,
                'tgl_pengerjaan' => $request->tgl_pengerjaan,
                'tgl_selesai' => $request->tgl_selesai,
                'status' => $request->status,
                'data_penyedia_id' => $request->penyedia,
                'admin_BMN' => auth('administrator')->user()->id,
                'is_acceptBMN' => $request->status,
                'updated_at' => now(),
        ]);

        if ($request->status == 'pembayaran') {
            # code...
            db::table('ruangans')
            ->where('id', $request->idAsset)
            ->update([
                'kondisi' => "Kondisi Baik",
                'updated_at' => now(),
            ]);
        }

        if ($request->status != 'pembayaran') {
            # code...
            db::table('ruangans')
            ->where('id', $request->idAsset)
            ->update([
                'kondisi' => $request->status,
                'updated_at' => now(),
            ]);
        }
        return redirect()->route('service-ruangan', ['status' => 'pengajuan'])->with('success', 'Data telah diperbaharui!');
    }
    

    public function storeKonfirmasiAsset(Request $request)
    {
        db::table('services')->insertOrIgnore([
            'penyedia_id' => $request->idPenyedia,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $maxService = Services::max('id');

        db::table('dokumen_permohonans')->insertOrIgnore([
            'service_id' => $maxService,
            'nama_dokumen' => 'BAST',
            'file' =>  $request->file('bast')->store('dokumen-service', 'public'),
            'status' => 'pengajuan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        db::table('dokumen_permohonans')->insertOrIgnore([
            'service_id' => $maxService,
            'nama_dokumen' => 'BAP',
            'file' => $request->file('bap')->store('dokumen-service', 'public'),
            'status' => 'pengajuan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $totalPermohonanAdmin = $request->numPermohonanAdmin;
        for ($i=0; $i < $totalPermohonanAdmin; $i++) { 
            $idPermohonan = 'idPermohonanAdmin_' . $i;
            $status = 'statusAdmin_' . $i;
            DB::table('permohonans')
            ->where('id', $request->$idPermohonan)
            ->update([
                'status' => 'riwayat',
                'is_acceptKeu' => $request->$status,
                'service_id' => $maxService,
                'admin_BMN' => auth('administrator')->user()->id,
                'is_acceptBMN' => 'selesai',
                'updated_at' => now(),
            ]);
        }

        $totalPermohonanPegawai = $request->numPermohonanPegawai;
        for ($i=0; $i < $totalPermohonanPegawai; $i++) { 
            $idPermohonan = 'idPermohonan_' . $i;
            $status = 'statusPegawai_' . $i;
            DB::table('permohonans')
            ->where('id', $request->$idPermohonan)
            ->update([
                'status' => 'riwayat',
                'is_acceptKeu' => 'verifikasi-1',
                'service_id' => $maxService,
                'admin_BMN' => auth('administrator')->user()->id,
                'is_acceptBMN' => 'selesai',
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('pembayaran_service_assets')->with('success', 'Data telah diperbaharui!');
    }

    public function storeKonfirmasiKendaraan(Request $request)
    {
        db::table('services')->insertOrIgnore([
            'penyedia_id' => $request->idPenyedia,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $maxService = Services::max('id');

        db::table('dokumen_permohonans')->insertOrIgnore([
            'service_id' => $maxService,
            'nama_dokumen' => 'BAST',
            'file' =>  $request->file('bast')->store('dokumen-service', 'public'),
            'status' => 'pengajuan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        db::table('dokumen_permohonans')->insertOrIgnore([
            'service_id' => $maxService,
            'nama_dokumen' => 'BAP',
            'file' => $request->file('bap')->store('dokumen-service', 'public'),
            'status' => 'pengajuan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $totalPermohonanAdmin = $request->numPermohonanAdmin;
        for ($i=0; $i < $totalPermohonanAdmin; $i++) { 
            $idPermohonan = 'idPermohonanAdmin_' . $i;
            $status = 'statusAdmin_' . $i;
            DB::table('permohonans')
            ->where('id', $request->$idPermohonan)
            ->update([
                'status' => 'riwayat',
                'is_acceptKeu' => $request->$status,
                'service_id' => $maxService,
                'admin_BMN' => auth('administrator')->user()->id,
                'is_acceptBMN' => 'selesai',
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('pembayaran_service_kendaraan')->with('success', 'Data telah diperbaharui!');
    }
    
    public function storeKonfirmasiRuangan(Request $request)
    {
        db::table('services')->insertOrIgnore([
            'penyedia_id' => $request->idPenyedia,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $maxService = Services::max('id');

        db::table('dokumen_permohonans')->insertOrIgnore([
            'service_id' => $maxService,
            'nama_dokumen' => 'BAST',
            'file' =>  $request->file('bast')->store('dokumen-service', 'public'),
            'status' => 'pengajuan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        db::table('dokumen_permohonans')->insertOrIgnore([
            'service_id' => $maxService,
            'nama_dokumen' => 'BAP',
            'file' => $request->file('bap')->store('dokumen-service', 'public'),
            'status' => 'pengajuan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $totalPermohonanAdmin = $request->numPermohonanAdmin;
        for ($i=0; $i < $totalPermohonanAdmin; $i++) { 
            $idPermohonan = 'idPermohonanAdmin_' . $i;
            $status = 'statusAdmin_' . $i;
            DB::table('permohonans')
            ->where('id', $request->$idPermohonan)
            ->update([
                'status' => 'riwayat',
                'is_acceptKeu' => $request->$status,
                'service_id' => $maxService,
                'admin_BMN' => auth('administrator')->user()->id,
                'is_acceptBMN' => 'selesai',
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('pembayaran_service_ruangan')->with('success', 'Data telah diperbaharui!');
    }

    public function storeKeuanganService(Request $request)
    {
               
        $totalDokumen = $request->numDokumen;
        for ($i=0; $i < $totalDokumen; $i++) { 
            $idDokumen = 'idDokumen_' . $i;
            $status = 'statusDokumen_' . $i;
            DB::table('dokumen_permohonans')
            ->where('id', $request->$idDokumen)
            ->update([
                'status' => $request->$status,
                'updated_at' => now(),
            ]);
        }

        $totalPermohonanAdmin = $request->numPermohonanAdmin;
        for ($i=0; $i < $totalPermohonanAdmin; $i++) { 
            $idPermohonan = 'idPermohonan_' . $i;
            $nominal = 'nominalPermohonan_' . $i;
            $pph = 'pajakPermohonan_' . $i;
            $pph22 = 'pajakPermohonan22_' . $i;
            $pph23 = 'pajakPermohonan23_' . $i;
            $ppn = 'pajakPermohonanppn_' . $i;
            $total = 'totalPermohonan_' . $i;
            DB::table('permohonans')
            ->where('id', $request->$idPermohonan)
            ->update([
                'nominal' => $request->$nominal,
                'pph' => $request->$pph,
                'pph22' => $request->$pph22,
                'pph23' => $request->$pph23,
                'ppn' => $request->$ppn,
                'total' => $request->$total,
                'is_acceptKeu' => 'selesai',
                'is_acceptBend' => 'approval-1',
                'admin_Keu' => auth('administrator')->user()->id,
                'updated_at' => now(),
            ]);
        }

        $totalPermohonanPegawai = $request->numPermohonanPegawai;
        for ($i=0; $i < $totalPermohonanPegawai; $i++) { 
            $idPermohonan = 'idPermohonanPegawai_' . $i;
            $nominal = 'nominalPermohonanPegawai_' . $i;
            $pph = 'pajakPermohonanPegawai_' . $i;
            $pph22 = 'pajakPermohonanPegawai22_' . $i;
            $pph23 = 'pajakPermohonanPegawai23_' . $i;
            $ppn = 'pajakPermohonanPegawaippn_' . $i;
            $total = 'totalPermohonanPegawai_' . $i;
            DB::table('permohonans')
            ->where('id', $request->$idPermohonan)
            ->update([
                'nominal' => $request->$nominal,
                'pph' => $request->$pph,
                'pph22' => $request->$pph22,
                'pph23' => $request->$pph23,
                'ppn' => $request->$ppn,
                'total' => $request->$total,
                'is_acceptKeu' => 'selesai',
                'is_acceptBend' => 'approval-1',
                'admin_Keu' => auth('administrator')->user()->id,
                'updated_at' => now(),
            ]);
        }

        $totalPermohonanKendaraan = $request->numPermohonanKendaraan;
        for ($i=0; $i < $totalPermohonanKendaraan; $i++) { 
            $idPermohonanKendaraan = 'idPermohonanKendaraan_' . $i;
            $nominal = 'nominalPermohonanKendaraan_' . $i;
            $pph = 'pajakPermohonanKendaraan_' . $i;
            $pph22 = 'pajakPermohonanKendaraan22_' . $i;
            $pph23 = 'pajakPermohonanKendaraan23_' . $i;
            $ppn = 'pajakPermohonanKendaraanppn_' . $i;
            $total = 'totalPermohonanKendaraan_' . $i;
            DB::table('permohonans')
            ->where('id', $request->$idPermohonanKendaraan)
            ->update([
                'nominal' => $request->$nominal,
                'pph' => $request->$pph,
                'pph22' => $request->$pph22,
                'pph23' => $request->$pph23,
                'ppn' => $request->$ppn,
                'total' => $request->$total,
                'is_acceptKeu' => 'selesai',
                'is_acceptBend' => 'approval-1',
                'admin_Keu' => auth('administrator')->user()->id,
                'updated_at' => now(),
            ]);
        }
        
        $totalPermohonanRuangan = $request->numPermohonanRuangan;
        for ($i=0; $i < $totalPermohonanRuangan; $i++) { 
            $idPermohonanKendaraan = 'idPermohonanRuangan_' . $i;
            $nominal = 'nominalPermohonanRuangan_' . $i;
            $pph = 'pajakPermohonanRuangan_' . $i;
            $pph22 = 'pajakPermohonanRuangan22_' . $i;
            $pph23 = 'pajakPermohonanRuangan23_' . $i;
            $ppn = 'pajakPermohonanRuanganppn_' . $i;
            $total = 'totalPermohonanRuangan_' . $i;
            DB::table('permohonans')
            ->where('id', $request->$idPermohonanKendaraan)
            ->update([
                'nominal' => $request->$nominal,
                'pph' => $request->$pph,
                'pph22' => $request->$pph22,
                'pph23' => $request->$pph23,
                'ppn' => $request->$ppn,
                'total' => $request->$total,
                'is_acceptKeu' => 'selesai',
                'is_acceptBend' => 'approval-1',
                'admin_Keu' => auth('administrator')->user()->id,
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('service_keuangan', ['status' => 'selesai'])->with('success', 'Data telah diperbaharui!');
    }

    public function storeBendaharaService(Request $request)
    {
               
        $totalDokumen = $request->numDokumen;
        for ($i=0; $i < $totalDokumen; $i++) { 
            $idDokumen = 'idDokumen_' . $i;
            $status = 'statusDokumen_' . $i;
            DB::table('dokumen_permohonans')
            ->where('id', $request->$idDokumen)
            ->update([
                'status' => $request->$status,
                'updated_at' => now(),
            ]);
        }

        $totalPermohonanAdmin = $request->numPermohonanAdmin;
        for ($i=0; $i < $totalPermohonanAdmin; $i++) { 
            $idPermohonan = 'idPermohonan_' . $i;
            $akun = 'akunPermohonan_' . $i;
            $sbm = 'sbmPermohonan_' . $i;
            $nominal = 'nominalPermohonan_' . $i;
            $pph = 'pajakPermohonan_' . $i;
            $pph22 = 'pajakPermohonan22_' . $i;
            $pph23 = 'pajakPermohonan23_' . $i;
            $ppn = 'pajakPermohonanppn_' . $i;
            $tglbayar = 'tglPermohonan_' . $i;
            $total = 'totalPermohonan_' . $i;
            $status_pembayaran = 'statusPembayaran_' . $i;
            DB::table('permohonans')
            ->where('id', $request->$idPermohonan)
            ->update([
                'nominal' => $request->$nominal,
                'pph' => $request->$pph,
                'pph22' => $request->$pph22,
                'pph23' => $request->$pph23,
                'ppn' => $request->$ppn,
                'tgl_bayar' => $request->$tglbayar,
                'total' => $request->$total,
                'akun_x_rkakl_id' => $request->$akun,
                'ref_sbm_id' => $request->$sbm,
                'status_pembayaran' => $request->$status_pembayaran,
                'is_acceptBend' => 'selesai',
                'admin_Keu' => auth('administrator')->user()->id,
                'updated_at' => now(),
            ]);
        }

        $totalPermohonanPegawai = $request->numPermohonanPegawai;
        for ($i=0; $i < $totalPermohonanPegawai; $i++) { 
            $idPermohonan = 'idPermohonanPegawai_' . $i;
            $akun1 = 'akunPermohonanPegawai_' . $i;
            $sbm1 = 'sbmPermohonanPegawai_' . $i;
            $nominal = 'nominalPermohonanPegawai_' . $i;
            $pph = 'pajakPermohonanPegawai_' . $i;
            $pph22 = 'pajakPermohonanPegawai22_' . $i;
            $pph23 = 'pajakPermohonanPegawai23_' . $i;
            $ppn = 'pajakPermohonanPegawaippn_' . $i;
            $tglbayar = 'tglPermohonanPegawai_' . $i;
            $total = 'totalPermohonanPegawai_' . $i;
            $status_pembayaran1 = 'statusPembayaranPegawai_' . $i;
            DB::table('permohonans')
            ->where('id', $request->$idPermohonan)
            ->update([
                'nominal' => $request->$nominal,
                'pph' => $request->$pph,
                'pph22' => $request->$pph22,
                'pph23' => $request->$pph23,
                'ppn' => $request->$ppn,
                'tgl_bayar' => $request->$tglbayar,
                'total' => $request->$total,
                'akun_x_rkakl_id' => $request->$akun1,
                'ref_sbm_id' => $request->$sbm1,
                'status_pembayaran' => $request->$status_pembayaran1,
                'is_acceptBend' => 'selesai',
                'admin_Bend' => auth('administrator')->user()->id,
                'updated_at' => now(),
            ]);
        }

        $totalPermohonanKendaraan = $request->numPermohonanKendaraan;
        for ($i=0; $i < $totalPermohonanKendaraan; $i++) { 
            $idPermohonan = 'idPermohonanKendaraan_' . $i;
            $akun = 'akunPermohonanKendaraan_' . $i;
            $sbm = 'sbmPermohonanKendaraan_' . $i;
            $nominal = 'nominalPermohonanKendaraan_' . $i;
            $pph = 'pajakPermohonanKendaraan_' . $i;
            $pph22 = 'pajakPermohonanKendaraan22_' . $i;
            $pph23 = 'pajakPermohonanKendaraan23_' . $i;
            $ppn = 'pajakPermohonanKendaraanppn_' . $i;
            $tglbayar = 'tglPermohonanKendaraan_' . $i;
            $total = 'totalPermohonanKendaraan_' . $i;
            $status_pembayaran = 'statusPembayaranKendaraan_' . $i;
            DB::table('permohonans')
            ->where('id', $request->$idPermohonan)
            ->update([
                'nominal' => $request->$nominal,
                'pph' => $request->$pph,
                'pph22' => $request->$pph22,
                'pph23' => $request->$pph23,
                'ppn' => $request->$ppn,
                'tgl_bayar' => $request->$tglbayar,
                'total' => $request->$total,
                'akun_x_rkakl_id' => $request->$akun,
                'ref_sbm_id' => $request->$sbm,
                'status_pembayaran' => $request->$status_pembayaran,
                'is_acceptBend' => 'selesai',
                'admin_Keu' => auth('administrator')->user()->id,
                'updated_at' => now(),
            ]);
        }
        
        $totalPermohonanRuangan = $request->numPermohonanRuangan;
        for ($i=0; $i < $totalPermohonanRuangan; $i++) { 
            $idPermohonan = 'idPermohonanRuangan_' . $i;
            $akun = 'akunPermohonanRuangan_' . $i;
            $sbm = 'sbmPermohonanRuangan_' . $i;
            $nominal = 'nominalPermohonanRuangan_' . $i;
            $pph = 'pajakPermohonanRuangan_' . $i;
            $pph22 = 'pajakPermohonanRuangan22_' . $i;
            $pph23 = 'pajakPermohonanRuangan23_' . $i;
            $ppn = 'pajakPermohonanRuanganppn_' . $i;
            $tglbayar = 'tglPermohonanRuangan_' . $i;
            $total = 'totalPermohonanRuangan_' . $i;
            $status_pembayaran = 'statusPembayaranRuangan_' . $i;
            DB::table('permohonans')
            ->where('id', $request->$idPermohonan)
            ->update([
                'nominal' => $request->$nominal,
                'pph' => $request->$pph,
                'pph22' => $request->$pph22,
                'pph23' => $request->$pph23,
                'ppn' => $request->$ppn,
                'tgl_bayar' => $request->$tglbayar,
                'total' => $request->$total,
                'akun_x_rkakl_id' => $request->$akun,
                'ref_sbm_id' => $request->$sbm,
                'status_pembayaran' => $request->$status_pembayaran,
                'is_acceptBend' => 'selesai',
                'admin_Keu' => auth('administrator')->user()->id,
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('service_bendahara_riwayat', ['status' => 'selesai'])->with('success', 'Data telah diperbaharui!');
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

    public function updatePenyedia(Request $request)
    {
        DB::table('data_penyedias')
            ->where('id', $request->idPenyedia)
            ->update([
                'NPWP' => $request->NPWP,
                'nama_CV' => $request->namaCV,
                'penanggung_jawab' => $request->penanggungJawab,
                'jabatan' => $request->jabatan,
                'no_telp' => $request->telps,
                'alamat' => $request->alamat,
                'kategori' => $request->kategori,
                'tahun' => $request->tahun,
                'updated_at' => now(),
        ]);
        return redirect()->route('data_penyedia')->with('success', 'Data telah diperbaharui!');
    }

    public function updateKendaraan(Request $request)
    {
        DB::table('kendaraans')
            ->where('id', $request->idKendaraan)
            ->update([
                'merek' => $request->merek,
                'no_polisi' => $request->no_polisi,
                'no_mesin' => $request->no_mesin,
                'no_stnk' => $request->no_stnk,
                'no_bpkb' => $request->no_bpkb,
                'legalitas' => $request->legalitas,
                'legalitas_5th' => $request->legalitas5tahun,
                'tipe' => $request->tipe,
                'status' => $request->status,
                'updated_at' => now(),
        ]);
        return redirect()->route('data_kendaraan')->with('success', 'Data telah diperbaharui!');
    }

    public function updateAsset(Request $request)
    {
        DB::table('assets')
            ->where('id', $request->idAsset)
            ->update([
                'kode_barang' => $request->kode,
                'nama_barang' => $request->nama,
                'NUP' => $request->nup,
                'nama_merek' => $request->merek,
                'tgl_beli' => $request->tgl_beli,
                'jenis_perawatan' => $request->perawatan,
                'status_kondisi' => $request->kondisi,
                'status_peminjaman' => 'Tidak Digunakan',
                'updated_at' => now(),
        ]);
        return redirect()->route('data_assets')->with('success', 'Data telah diperbaharui!');
    }

    public function updateRuangan(Request $request)
    {
        DB::table('ruangans')
            ->where('id', $request->idRuangan)
            ->update([
                'kode_ruangan' => $request->kode,
                'nama_ruangan' => $request->nama,
                'pegawai_id' => $request->penanggungjawab,
                'kondisi' => $request->kondisi,
                'updated_at' => now(),
        ]);
        return redirect()->route('data_ruangan')->with('success', 'Data telah diperbaharui!');
    }

    public function updatePeminjaman(Request $request, $id)
    {
        $action = $request->input('action');

        if ($action === 'setujui') 
        {
            DB::table('data_penanggungjawabs')
                ->where('id', $id)
                ->update([
                    'status' => 'digunakan',
                    'updated_at' => now(),
            ]);

            db::table('assets')
            ->where('id', $request->idAsset)
            ->update([
                'status_peminjaman' => 'digunakan',
                'updated_at' => now(),
            ]);
            return redirect()->route('peminjaman_asset', ['status' => 'digunakan'])->with('success', 'Peminjaman Telah anda setujui, kirim barang ke peminjam!');
        } elseif($action === 'tolak')
        {
            DB::table('data_penanggungjawabs')
                ->where('id', $id)
                ->update([
                    'status' => 'penolakan',
                    'updated_at' => now(),
            ]);

            db::table('assets')
            ->where('id', $request->idAsset)
            ->update([
                'status_peminjaman' => 'Tidak Dipakai',
                'updated_at' => now(),
            ]);
            return redirect()->route('peminjaman_asset', ['status' => 'penolakan'])->with('success', 'Peminjaman telah anda tolak!');
        } elseif($action === 'selesai')
        {
            DB::table('data_penanggungjawabs')
                ->where('id', $id)
                ->update([
                    'tgl_selesai' => now(),
                    'status' => 'selesai',
                    'updated_at' => now(),
            ]);

            db::table('assets')
            ->where('id', $request->idAsset)
            ->update([
                'status_peminjaman' => 'Tidak Dipakai',
                'updated_at' => now(),
            ]);
            return redirect()->route('peminjaman_asset', ['status' => 'selesai'])->with('success', 'Periksa barang dan simpan dengan baik kembali!');
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function destroyPenyedia(string $id)
    {
        Data_penyedia::destroy($id);
        return redirect()->route('data_penyedia')->with('success', 'Data telah dihapus!');
    }

    public function destroyKendaraan(string $id)
    {
        Kendaraan::destroy($id);
        return redirect()->route('data_kendaraan')->with('success', 'Data telah dihapus!');
    }

    public function destroyAsset(string $id)
    {
        Asset::destroy($id);
        return redirect()->route('data_assets')->with('success', 'Data telah dihapus!');
    }

    public function destroyRuangan(string $id)
    {
        Ruangan::destroy($id);
        return redirect()->route('data_ruangan')->with('success', 'Data telah dihapus!');
    }

    public function destroykomponenServiceAsset(Request $request, string $id)
    {
        Komponen_diperlukan::destroy($id);
        return redirect()->route('detail_service-asset', ['id' => $request->idPermohonan])->with('success', 'Data telah dihapus!');
    }
}
