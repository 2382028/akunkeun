<?php

namespace App\Http\Controllers;

use App\Services\InvoiceService;
use App\Models\DetailPemesananKamar;
use App\Models\Asset;
use App\Models\Data_penyedia;
use App\Models\Dokumen_permohonan;
use App\Models\Kendaraan;
use App\Models\Komponen_diperlukan;
use App\Models\Pegawai;
use App\Models\Permohonan;
use App\Models\Ref_sbm;
use App\Models\Ruangan;
use Illuminate\Support\Facades\File;
use App\Models\Services;
use App\Models\Versi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AdminOtherController;
use App\Models\InventarisBmn;
use App\Models\RuanganBmn;
use App\Models\RefKopSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DetailKamar;
use App\Models\Pemesanan;
use App\Models\Penolakan;
use App\Models\Pnbp;
use App\Models\Kamar;
use App\Models\PembatalanSewa;
use App\Models\Invoice;
use App\Models\SetoranPnbp;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use App\Models\Notifikasi;

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
                        ->leftJoin('data_penanggungjawabs', 'permohonans.data_penanggungjawab_id', '=', 'data_penanggungjawabs.id')
                        ->leftJoin('pegawais', 'data_penanggungjawabs.pegawai_id', '=', 'pegawais.id')
                        ->leftJoin('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->select(
                            'permohonans.id as idService', 
                            'pegawais.nama_lengkap', 
                            'assets.nama_barang', 
                            'permohonans.status'
                        )
                        ->where('permohonans.versi_id', session('versi'))
                        ->where('permohonans.status', $status)
                        ->get();
    
        // ddd($service_pegawai);
        $service_admin = DB::table('permohonans')
                        ->leftJoin('administrators', 'permohonans.admin', '=', 'administrators.id')
                        ->leftJoin('assets', 'permohonans.asset_id', '=', 'assets.id')
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
                        ->leftJoin('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->leftJoin('pegawais', 'permohonans.data_penanggungjawab_id', '=', 'pegawais.id')
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
                        ->leftJoin('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->leftJoin('pegawais', 'permohonans.data_penanggungjawab_id', '=', 'pegawais.id')
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

    public function getDokumenService($filename)
    {
        $path = storage_path('app/public/dokumen-service/' . $filename);
    if (!File::exists($path)) {
            abort(404);
        }
        return response()->file($path);
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
                        ->leftJoin('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->leftJoin('pegawais', 'permohonans.data_penanggungjawab_id', '=', 'pegawais.id')
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
                        ->leftJoin('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->leftJoin('pegawais', 'permohonans.data_penanggungjawab_id', '=', 'pegawais.id')
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
                        ->leftJoin('assets', 'permohonans.asset_id', '=', 'assets.id')
                        ->leftJoin('pegawais', 'permohonans.data_penanggungjawab_id', '=', 'pegawais.id')
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
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'NPWP' => $request->NPWP,
            'nama_CV' => $request->namaPenyedia,
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
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
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
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
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
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        db::table('assets')
        ->where('id', $request->idAsset)
        ->update([
            'status_peminjaman' => 'pengajuan',
            'updated_at' => now()->format('Y-m-d H:i:s'),
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
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
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
            'tgl_permohonan' => now()->format('Y-m-d H:i:s'),
            'admin' => auth('administrator')->user()->id,
            'is_acceptBMN' => 'pengajuan',
            'versi_id' => $versi[0]->id,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        db::table('assets')
        ->where('id', $request->idAsset)
        ->update([
            'status_kondisi' => 'Proses Pengajuan Service',
            'updated_at' => now()->format('Y-m-d H:i:s'),
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
            'tgl_permohonan' => now()->format('Y-m-d H:i:s'),
            'admin' => auth('administrator')->user()->id,
            'is_acceptBMN' => 'pengajuan',
            'versi_id' => $versi[0]->id,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        db::table('kendaraans')
        ->where('id', $request->idKendaraan)
        ->update([
            'status' => 'Proses Pengajuan Service',
            'updated_at' => now()->format('Y-m-d H:i:s'),
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
            'tgl_permohonan' => now()->format('Y-m-d H:i:s'),
            'admin' => auth('administrator')->user()->id,
            'is_acceptBMN' => 'pengajuan',
            'versi_id' => $versi[0]->id,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        db::table('ruangans')
        ->where('id', $request->idRuangan)
        ->update([
            'kondisi' => 'Proses Pengajuan Perbaikan',
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        return redirect()->route('service-ruangan', ['status' => 'pengajuan'])->with('success', 'Pengajuan perbaikan telah dikirim!');
    }

    public function storeServiceAssetKomponen(Request $request)
    {
        db::table('komponen_diperlukans')->insertOrIgnore([
            'nama_barang' => $request->komponen,
            'frekuensi' => $request->jumlah,
            'permohonan_id' => $request->idPermohonan,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        return redirect()->route('detail_service-asset', ['id' => $request->idPermohonan])->with('success', 'Komponen telah ditambahkan!');
    }

    public function storeServiceKendaraanKomponen(Request $request)
    {
        db::table('komponen_diperlukans')->insertOrIgnore([
            'nama_barang' => $request->komponen,
            'frekuensi' => $request->jumlah,
            'permohonan_id' => $request->idPermohonan,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        return redirect()->route('service_kendaraan_detail', ['id' => $request->idPermohonan])->with('success', 'Komponen telah ditambahkan!');
    }
    
    public function storeServiceRuanganKomponen(Request $request)
    {
        db::table('komponen_diperlukans')->insertOrIgnore([
            'nama_barang' => $request->komponen,
            'frekuensi' => $request->jumlah,
            'permohonan_id' => $request->idPermohonan,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
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
                'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        if ($request->status == 'pembayaran') {
            # code...
            db::table('assets')
            ->where('id', $request->idAsset)
            ->update([
                'status_kondisi' => "Baik",
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
            
            db::table('data_penanggungjawabs')
            ->where('asset_id', $request->idAsset)
            ->update([
                'status' => "digunakan",
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        if ($request->status != 'pembayaran') {
            # code...
            db::table('assets')
            ->where('id', $request->idAsset)
            ->update([
                'status_kondisi' => $request->status,
                'updated_at' => now()->format('Y-m-d H:i:s'),
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
                'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        if ($request->status == 'pembayaran') {
            # code...
            db::table('kendaraans')
            ->where('id', $request->idAsset)
            ->update([
                'status' => "Baik",
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        if ($request->status != 'pembayaran') {
            # code...
            db::table('kendaraans')
            ->where('id', $request->idAsset)
            ->update([
                'status' => $request->status,
                'updated_at' => now()->format('Y-m-d H:i:s'),
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
                'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        if ($request->status == 'pembayaran') {
            # code...
            db::table('ruangans')
            ->where('id', $request->idAsset)
            ->update([
                'kondisi' => "Kondisi Baik",
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        if ($request->status != 'pembayaran') {
            # code...
            db::table('ruangans')
            ->where('id', $request->idAsset)
            ->update([
                'kondisi' => $request->status,
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }
        return redirect()->route('service-ruangan', ['status' => 'pengajuan'])->with('success', 'Data telah diperbaharui!');
    }
    

    public function storeKonfirmasiAsset(Request $request)
    {
        db::table('services')->insertOrIgnore([
            'penyedia_id' => $request->idPenyedia,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $maxService = Services::max('id');

        db::table('dokumen_permohonans')->insertOrIgnore([
            'service_id' => $maxService,
            'nama_dokumen' => 'BAST',
            'file' =>  $request->file('bast')->store('dokumen-service', 'public'),
            'status' => 'pengajuan',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);
        
        db::table('dokumen_permohonans')->insertOrIgnore([
            'service_id' => $maxService,
            'nama_dokumen' => 'BAP',
            'file' => $request->file('bap')->store('dokumen-service', 'public'),
            'status' => 'pengajuan',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
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
                'updated_at' => now()->format('Y-m-d H:i:s'),
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
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->route('pembayaran_service_assets')->with('success', 'Data telah diperbaharui!');
    }

    public function storeKonfirmasiKendaraan(Request $request)
    {
        db::table('services')->insertOrIgnore([
            'penyedia_id' => $request->idPenyedia,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $maxService = Services::max('id');

        db::table('dokumen_permohonans')->insertOrIgnore([
            'service_id' => $maxService,
            'nama_dokumen' => 'BAST',
            'file' =>  $request->file('bast')->store('dokumen-service', 'public'),
            'status' => 'pengajuan',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);
        
        db::table('dokumen_permohonans')->insertOrIgnore([
            'service_id' => $maxService,
            'nama_dokumen' => 'BAP',
            'file' => $request->file('bap')->store('dokumen-service', 'public'),
            'status' => 'pengajuan',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
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
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->route('pembayaran_service_kendaraan')->with('success', 'Data telah diperbaharui!');
    }
    
    public function storeKonfirmasiRuangan(Request $request)
    {
        db::table('services')->insertOrIgnore([
            'penyedia_id' => $request->idPenyedia,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $maxService = Services::max('id');

        db::table('dokumen_permohonans')->insertOrIgnore([
            'service_id' => $maxService,
            'nama_dokumen' => 'BAST',
            'file' =>  $request->file('bast')->store('dokumen-service', 'public'),
            'status' => 'pengajuan',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);
        
        db::table('dokumen_permohonans')->insertOrIgnore([
            'service_id' => $maxService,
            'nama_dokumen' => 'BAP',
            'file' => $request->file('bap')->store('dokumen-service', 'public'),
            'status' => 'pengajuan',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
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
                'updated_at' => now()->format('Y-m-d H:i:s'),
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
                'updated_at' => now()->format('Y-m-d H:i:s'),
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
                'updated_at' => now()->format('Y-m-d H:i:s'),
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
                'updated_at' => now()->format('Y-m-d H:i:s'),
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
                'updated_at' => now()->format('Y-m-d H:i:s'),
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
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->route('service_keuangan', ['status' => 'verifikasi-1'])->with('success', 'Data telah diperbaharui!');
    }

    public function storeBendaharaService(Request $request)
    {
        // Panggil fungsi isiPenggunaan
        $adminOtherController = new AdminOtherController();

        $totalDokumen = $request->numDokumen;
        for ($i=0; $i < $totalDokumen; $i++) {
            $idDokumen = 'idDokumen_' . $i;
            $status = 'statusDokumen_' . $i;
            DB::table('dokumen_permohonans')
            ->where('id', $request->$idDokumen)
            ->update([
                'status' => $request->$status,
                'updated_at' => now()->format('Y-m-d H:i:s'),
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
                'updated_at' => now()->format('Y-m-d H:i:s'),
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
                'updated_at' => now()->format('Y-m-d H:i:s'),
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
                'updated_at' => now()->format('Y-m-d H:i:s'),
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
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        $isIsiPenggunaanBerhasil = $adminOtherController->isiPenggunaan();

                if ($isIsiPenggunaanBerhasil) {
                    return redirect()->route('service_bendahara_riwayat', ['status' => 'selesai'])->with('success', 'Data telah diperbaharui!');
                } else {
                    return redirect()->back()->with('error', 'Terjadi kesalahan: Proses Penyimpanan Data Gagal.');
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

    public function updatePenyedia(Request $request, $id)
    {
        $data = [
            'email' => $request->email,
            'NPWP' => $request->NPWP,
            'nama_CV' => $request->namaPenyedia,
            'penanggung_jawab' => $request->penanggungJawab,
            'jabatan' => $request->jabatan,
            'no_telp' => $request->telps,
            'alamat' => $request->alamat,
            'kategori' => $request->kategori,
            'tahun' => $request->tahun,
            'updated_at' => now(),
        ];
    
        if($request->password) {
            $data['password'] = bcrypt($request->password);
        }
    
        DB::table('data_penyedias')->where('id', $id)->update($data);
    
        return redirect()->route('data_penyedia')->with('success', 'Data berhasil diperbarui!');
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
                // 'status' => $request->status,
                'updated_at' => now()->format('Y-m-d H:i:s'),
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
                'kategori' => $request->dapat_dipinjami,
                'updated_at' => now()->format('Y-m-d H:i:s'),
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
                'updated_at' => now()->format('Y-m-d H:i:s'),
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
                    'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

            db::table('assets')
            ->where('id', $request->idAsset)
            ->update([
                'status_peminjaman' => 'digunakan',
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
            return redirect()->route('peminjaman_asset', ['status' => 'digunakan'])->with('success', 'Peminjaman Telah anda setujui, kirim barang ke peminjam!');
        } elseif($action === 'tolak')
        {
            DB::table('data_penanggungjawabs')
                ->where('id', $id)
                ->update([
                    'status' => 'penolakan',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

            db::table('assets')
            ->where('id', $request->idAsset)
            ->update([
                'status_peminjaman' => 'Tidak Dipakai',
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
            return redirect()->route('peminjaman_asset', ['status' => 'penolakan'])->with('success', 'Peminjaman telah anda tolak!');
        } elseif($action === 'selesai')
        {
            DB::table('data_penanggungjawabs')
                ->where('id', $id)
                ->update([
                    'tgl_selesai' => now()->format('Y-m-d H:i:s'),
                    'status' => 'selesai',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

            db::table('assets')
            ->where('id', $request->idAsset)
            ->update([
                'status_peminjaman' => 'Tidak Dipakai',
                'updated_at' => now()->format('Y-m-d H:i:s'),
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
    public function indexBMN()
    {
        return view('admin.bmn.referensi.data_bmn', [
            'title' => 'Data BMN LLDIKTI',
            'bmns' => InventarisBmn::with('ruangan')->get(),
            'kodeBarangList' => InventarisBmn::distinct()->pluck('kode_bmn'),
            'namaBarangList' => InventarisBmn::distinct()->pluck('nama_bmn'),
            'kategoriList' => InventarisBmn::distinct()->pluck('kategori_bmn'),
            'ruanganList' => RuanganBmn::all()
        ]);
    }
    public function rekap_bmn(Request $request)
    {
        $tanggal = $request->input('tanggal');

        $query = InventarisBmn::with('ruangan');
        if ($tanggal) {
            $query->whereDate('created_at', '<=', $tanggal);
        }

        $bmns = $query->get();

        $kategori = $bmns->groupBy('kategori_bmn')->map->count()->sortKeys();

        $currentYear = now()->year;
        $umurBarang = [
            'Dibawah 5 tahun' => $bmns->filter(fn($item) => $currentYear - $item->tahun_beli < 5)->count(),
            '5 sampai 10 tahun' => $bmns->filter(fn($item) => $currentYear - $item->tahun_beli >= 5 && $currentYear - $item->tahun_beli <= 10)->count(),
            'Lebih dari 10 tahun' => $bmns->filter(fn($item) => $currentYear - $item->tahun_beli > 10)->count(),
        ];

        $ruangan = $bmns->groupBy(fn($item) => optional($item->ruangan)->nama_ruangan)
            ->map->count()
            ->sortKeys();
        $kop = RefKopSurat::where('is_aktif', 1)->latest()->first();

        $pdf = Pdf::loadView('admin.bmn.referensi.rekap_bmn_pdf', compact(
            'kategori',
            'umurBarang',
            'ruangan',
            'tanggal',
            'kop'
        ))->setPaper('A4', 'portrait');

        return $pdf->download('Rekapitulasi_BMN.pdf', compact('kategori', 'umurBarang', 'ruangan', 'tanggal'));
    }
    public function rekapUsia(Request $request)
    {
        $kondisi = $request->query('kondisi');
        $tahun = (int) $request->query('tahun');

        if (!in_array($kondisi, ['gt', 'lt']) || !$tahun) {
            abort(400, 'Parameter tidak valid');
        }

        $currentYear = now()->year;

        $bmns = InventarisBmn::with('ruangan')->get()->filter(function ($item) use ($kondisi, $tahun, $currentYear) {
            $usia = $currentYear - $item->tahun_beli;
            return $kondisi === 'gt' ? $usia > $tahun : $usia < $tahun;
        });

        $filterText = $kondisi === 'gt'
            ? "Lebih dari $tahun tahun"
            : "Kurang dari $tahun tahun";

        $headers = [
            "Content-Type" => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=Rekap_Usia_BMN.xls",
        ];

        $html = '
        <table border="0" style="text-align: center; width: 100%; margin-bottom: 10px;">
            <tr>
                <td colspan="10"><h3>Rekapitulasi BMN Berdasarkan Usia</h3></td>
            </tr>
            <tr>
                <td colspan="10">Filter Usia: ' . $filterText . ' | Tahun Referensi: ' . $currentYear . '</td>
            </tr>
        </table>

        <table border="1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>NUP</th>
                    <th>Nama Barang</th>
                    <th>Merek</th>
                    <th>Kategori</th>
                    <th>Tahun Beli</th>
                    <th>Kode Ruangan</th>
                    <th>Nama Ruangan</th>
                    <th>Usia (tahun)</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($bmns as $i => $item) {
            $html .= '<tr>
    <td>' . ($i + 1) . '</td>
    <td>' . $item->kode_bmn . '</td>
    <td>' . $item->nup_bmn . '</td>
    <td>' . $item->nama_bmn . '</td>
    <td>' . $item->merk_bmn . '</td>
    <td>' . $item->kategori_bmn . '</td>
    <td>' . $item->tahun_beli . '</td>
    <td>' . (optional($item->ruangan)->kode_ruangan ?? '-') . '</td>
    <td>' . (optional($item->ruangan)->nama_ruangan ?? '-') . '</td>
    <td>' . ($currentYear - $item->tahun_beli) . '</td>
</tr>';
        }

        $html .= '</tbody></table>';

        return response($html, 200, $headers);
    }
    public function jsonBMN(Request $request)
    {
        $bmns = InventarisBmn::with('ruangan');

        return response()->json([
            'data' => $bmns->get()
        ]);
    }
    public function saveBMN(Request $request)
    {
        $data = [
            'kode_bmn' => $request->kode,
            'nama_bmn' => $request->nama,
            'nup_bmn' => $request->nup,
            'merk_bmn' => $request->merek,
            'kategori_bmn' => $request->kategori,
            'id_ruangan_bmn' => $request->ruangan,
            'tahun_beli' => $request->tgl_beli,
            'periode_pemeliharaan' => $request->jenis_pemeliharaan == 'rutin' ? $request->periode_pemeliharaan : null,
            'jadwal_pemeliharaan' => $request->jenis_pemeliharaan == 'rutin' ? $request->jadwal_pemeliharaan : null,
        ];

        InventarisBmn::updateOrCreate(
            ['id_inventaris_bmn' => $request->id_inventaris_bmn], 
            $data 
        );

        return redirect()->back()->with('success', 'Data BMN berhasil disimpan.');
    }


    public function destroyBMN($id)
    {
        InventarisBmn::destroy($id);
        return redirect()->back()->with('success', 'BMN berhasil dihapus.');
    }
    public function ruangan()
    {
        return view('admin.bmn.referensi.data_ruangan', [
            'title' => 'Data Ruangan LLDIKTI',
            'ruangan' => RuanganBmn::all()
        ]);
    }
    public function jsonRuangan()
    {
        return response()->json([
            'data' => RuanganBmn::all()
        ]);
    }
public function saveRuangan(Request $request)
{
    $data = [
        'kode_ruangan' => $request->kode_ruangan,
        'nama_ruangan' => $request->nama_ruangan,
    ];

    // Jika ada ID, update; jika tidak, buat baru
    if ($request->filled('id')) {
        RuanganBmn::where('id_ruangan_bmn', $request->id)->update($data);
    } else {
        RuanganBmn::create($data);
    }

    return redirect()->back()->with('success', 'Data ruangan berhasil disimpan.');
}

    public function deleteRuangan($id)
    {
        RuanganBmn::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data ruangan berhasil dihapus.');
    }
    //sewa
    public function indexPenyewaan($status = 'menunggu')
    {
        $admin = Auth::user();
        $admin_roles = $admin->roles->pluck('nama_role')->toArray();
        $kategoriKamar = DB::table('kategori_kamar')->get();
        $fasilitas = DB::table('fasilitas_sewa')->get();

        // Auto-update pemesanan ke 'selesai'
        if ($status === 'selesai') {
            Pemesanan::where('status', 'diterima')
                ->where('tanggal_checkout', '<', now())
                ->update([
                    'status' => 'selesai',
                    'updated_at' => now()
                ]);
        }

        $pemesanan = collect();
        $pnbp_list = collect();

        // Ambil data pemesanan dengan eager load relasi
        if ($status === 'ditolak') {
            $query = Pemesanan::with([
                'setoranPnbp.pnbp',
                'penyewa',
                'penolakan',
                'pembatalanSewa',
                'detailKamar.kamar.kategori'
            ])->whereIn('status', ['ditolak', 'dibatalkan refund']);
        } else {
            $query = Pemesanan::with([
                'setoranPnbp.pnbp',
                'penyewa',
                'penolakan',
                'detailKamar.kamar.kategori'
            ])->where('status', $status);
        }

        // Filter untuk pengajuan PNBP
        if ($status === 'selesai' && in_array(request()->query('pnbp'), ['pengajuan'])) {
            $query->where(function ($q) {
                $q->doesntHave('pnbp') // belum ada data PNBP
                    ->orWhereHas('pnbp', function ($q2) {
                        $q2->where('status_setoran', 'ditolak'); // sudah ada tapi ditolak
                    });
            });
        }

        $pemesanan = $query->orderByDesc('created_at')->get();

        // Counter status
        $counts = [
            'menunggu' => Pemesanan::where('status', 'menunggu')->count(),
            'verifikasi' => Pemesanan::where('status', 'verifikasi')->count(),
            'diterima' => Pemesanan::where('status', 'diterima')->count(),
            'selesai' => Pemesanan::where('status', 'selesai')->count(),
            'ditolak' => Pemesanan::whereIn('status', ['ditolak', 'dibatalkan refund'])->count(),
        ];

        // Tambahan untuk PNBP pengajuan
        $counts['pnbp_pengajuan'] = Pemesanan::where('status', 'selesai')
            ->where(function ($q) {
                $q->doesntHave('pnbp')
                    ->orWhereHas('pnbp', function ($q2) {
                        $q2->where('status_setoran', 'ditolak');
                    });
            })
            ->count();

        // Tambahan untuk Setoran PNBP
        $counts['setoran_pnbp'] = Pnbp::whereIn('status_setoran', ['pengajuan', 'disetujui', 'selesai'])->count();

        // Pengisian bukti setoran PNBP
        if ($status === 'setoran_pnbp') {
            $pnbp_list = Pnbp::with('setoran.pemesanans')
                ->whereIn('status_setoran', ['pengajuan', 'disetujui', 'selesai', 'ditolak'])
                ->get();

            return view('admin.bmn.transaksi.penyewaan', compact(
                'pnbp_list',
                'admin_roles',
                'kategoriKamar',
                'pemesanan',
                'counts',
                'fasilitas'
            ))->with('title', 'Setoran PNBP');
        }

        // 🔹 Tambahkan data kamar tersedia (>= harga kamar lama, status available)
        $kamarTersedia = [];
        foreach ($pemesanan as $p) {
            foreach ($p->detailKamar as $dk) {
                $kamarTersedia[$dk->id_detail_pemesanan_kamar] = Kamar::with('kategori')
                    ->where('status_kamar', 'available')
                    ->where('harga_per_malam', '>=', $dk->kamar->harga_per_malam)
                    ->get();
            }
        }

        return view('admin.bmn.transaksi.penyewaan', compact(
            'pemesanan',
            'admin_roles',
            'kategoriKamar',
            'counts',
            'fasilitas',
            'kamarTersedia' 
        ))->with('title', 'Verifikasi Penyewaan Aset');
    }


    public function getBuktiPembayaran($filename)
    {
        $path = storage_path('app/public/bukti_pembayaran/' . $filename);
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->file($path);
    }

    public function getInvoice($filename)
    {
        $path = storage_path('app/public/invoice/' . $filename);
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->file($path);
    }

    public function setujuiBendahara($id)
    {
        DB::table('pemesanans')->where('kode_pemesanan', $id)->update([
            'status' => 'verifikasi',
            'updated_at' => now()
        ]);
        $pemesanan = Pemesanan::with('penyewa')->where('kode_pemesanan', $id)->first();
                // Buat Notifikasi ke Petugas Mess
        Notifikasi::create([
            'from' => auth('administrator')->id(),
            'role' => "Petugas Mess",
            'header' => 'Pengajuan Penyewaan Baru',
            'message' => "Terdapat pengajuan penyewaan baru dari {$pemesanan->penyewa->nama_lengkap} yang perlu dikonfirmasi.",
            'route' => "penyewaan_aset/verifikasi",
            'is_read' => 0,
            'created_at' => now(),
        ]);
        return back()->with('success', 'Pesanan disetujui oleh bendahara.');
    }

    public function setujuiPetugas($kode_pemesanan)
    {
        // Ambil pemesanan + pembayaran
        $pemesanan = Pemesanan::with(['penyewa', 'penyewa.akun', 'pembayaran'])
            ->where('kode_pemesanan', $kode_pemesanan)
            ->firstOrFail();

        $nama_penyewa = $pemesanan->penyewa->nama_lengkap;
        $checkin = Carbon::parse($pemesanan->tanggal_checkin);

        // Generate invoice baru
        InvoiceService::generate($pemesanan);

        // Update status pemesanan
        $pemesanan->update([
            'status'     => 'diterima',
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Pesanan disetujui, invoice dibuat, dan notifikasi WA terkirim.');
    }


    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:1000',
        ]);

        $pemesanan = Pemesanan::findOrFail($id);

        // Update status pada pemesanan
        $pemesanan->update([
            'status' => 'ditolak',
        ]);

        // Simpan alasan penolakan ke tabel penolakans (morph)
        $pemesanan->penolakan()->create([
            'alasan_penolakan' => $request->alasan_penolakan,
        ]);
        Notifikasi::create([
            'from' => auth('administrator')->id(),
            'role' => "penyewa",
            'to' => $pemesanan->id_penyewa,
            'header' => 'Penolakan Penyewaan Aset',
            'message' => "Pengajuan sewa Anda dengan kode {$pemesanan->kode_pemesanan} telah ditolak petugas, dengan alasan {$request->alasan_penolakan}.",
            'route' => "sewa/pesanan-saya?default_tab=berlangsung",
            'is_read' => 0,
            'created_at' => now(),
        ]);
        return back()->with('success', 'Pemesanan sewa berhasil ditolak.');
    }

    public function storeKamar(Request $request)
    {
        $kamarId = DB::table('kamar')->insertGetId([
            'nomor_kamar' => $request->nomor_kamar,
            'id_kategori_kamar' => $request->id_kategori_kamar,
            'harga_per_malam' => $request->harga_per_malam,
            'status_kamar' => $request->status,
            'lantai' => $request->lantai,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Simpan fasilitas
        if ($request->filled('fasilitas')) {
            foreach ($request->fasilitas as $id_fasilitas => $data) {
                if (isset($data['aktif']) && isset($data['jumlah']) && $data['jumlah'] > 0) {
                    DetailKamar::create([
                        'id_kamar' => $kamarId,
                        'id_fasilitas_sewa' => $id_fasilitas,
                        'jumlah' => $data['jumlah'],
                    ]);
                }
            }
        }


        return redirect()->back()->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_kamar,nama_kategori'
        ]);

        $id = DB::table('kategori_kamar')->insertGetId([
            'nama_kategori' => $request->nama_kategori,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Return response JSON untuk JS
        return response()->json([
            'id_kategori_kamar' => $id,
            'nama_kategori' => $request->nama_kategori
        ]);
    }

    public function storeFasilitas(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
        ]);

        $id = DB::table('fasilitas_sewa')->insertGetId([
            'nama_fasilitas' => $request->nama_fasilitas,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'id_fasilitas_sewa' => $id,
            'nama_fasilitas' => $request->nama_fasilitas
        ]);
    }

    public function getKamarByKategori($id)
    {
        $kamar = DB::table('kamar')
            ->where('id_kategori_kamar', $id)
            ->orderByRaw('CAST(nomor_kamar AS UNSIGNED)')
            ->get();

        return response()->json($kamar);
    }

    public function getDetailKamar($id)
    {
        $kamar = DB::table('kamar')->where('id_kamar', $id)->first();

        $fasilitas = DetailKamar::where('id_kamar', $id)
            ->pluck('jumlah', 'id_fasilitas_sewa');

        return response()->json([
            'kamar' => $kamar,
            'fasilitas' => $fasilitas,
        ]);
    }

    public function updateKamar(Request $request, $id)
    {
        // Update kamar
        DB::table('kamar')->where('id_kamar', $id)->update([
            'nomor_kamar' => $request->nomor_kamar,
            'id_kategori_kamar' => $request->id_kategori_kamar,
            'harga_per_malam' => $request->harga_per_malam,
            'status_kamar' => $request->status,
            'lantai' => $request->lantai,
            'updated_at' => now(),
        ]);

        // Hapus semua fasilitas dulu
        DetailKamar::where('id_kamar', $id)->delete();

        // Simpan ulang fasilitas
        if ($request->filled('fasilitas')) {
            foreach ($request->fasilitas as $id_fasilitas => $data) {
                if (isset($data['aktif']) && isset($data['jumlah']) && $data['jumlah'] > 0) {
                    DetailKamar::create([
                        'id_kamar' => $id,
                        'id_fasilitas_sewa' => $id_fasilitas,
                        'jumlah' => $data['jumlah'],
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function updateKategori(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255'
        ]);

        DB::table('kategori_kamar')->where('id_kategori_kamar', $id)->update([
            'nama_kategori' => $request->nama_kategori,
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Kategori diupdate']);
    }

    public function destroyKategori($id)
    {
        DB::table('kategori_kamar')->where('id_kategori_kamar', $id)->delete();

        return response()->json(['message' => 'Kategori dihapus']);
    }
    public function updateFasilitas(Request $request, $id)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
        ]);

        DB::table('fasilitas_sewa')->where('id_fasilitas_sewa', $id)->update([
            'nama_fasilitas' => $request->nama_fasilitas,
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Fasilitas diupdate']);
    }
    public function destroyFasilitas($id)
    {
        DB::table('fasilitas_sewa')->where('id_fasilitas_sewa', $id)->delete();

        return response()->json(['message' => 'Fasilitas dihapus']);
    }
    public function selesai()
    {
        // ✅ Step 1: Update otomatis semua yang sudah habis masa sewanya
        DB::table('pemesanans')
            ->where('status', 'diterima')
            ->where('tanggal_checkout', '<', now())
            ->update([
                'status' => 'selesai',
                'updated_at' => now()
            ]);

        // ✅ Step 2: Ambil semua pemesanan yang sudah selesai
        $pemesanan = DB::table('pemesanans as p')
            ->join('penyewas as py', 'p.id_penyewa', '=', 'py.id')
            ->leftJoin('detail_pemesanan_kamar as dpk', 'p.id', '=', 'dpk.id_pemesanan')
            ->leftJoin('kamar as k', 'dpk.id_kamar', '=', 'k.id')
            ->leftJoin('kategori_kamar as kk', 'k.id_kategori_kamar', '=', 'kk.id')
            ->select('p.*', 'py.nama_lengkap as nama_penyewa')
            ->where('p.status', 'selesai')
            ->groupBy('p.id')
            ->get();

        return view('admin.penyewaan.selesai', compact('pemesanan'));
    }
    public function ajukanSewa(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string',
            'nik' => 'required|digits:16',
            'no_telepon' => 'required|regex:/^[0-9]+$/',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
            'rooms_json' => 'required|json',
        ]);

        $rooms = json_decode($request->rooms_json, true);
        $start = Carbon::parse($request->start)->startOfDay();
        $end = Carbon::parse($request->end)->startOfDay();
        $nights = $start->diffInDays($end);

        // Hitung total harga
        $totalPrice = 0;
        foreach ($rooms as $room) {
            $totalPrice += $room['price'] * $room['quantity'] * $nights;
        }

        $today = now()->format('Ymd');

        $latest = DB::table('pemesanans')
            ->whereDate('created_at', now()->toDateString())
            ->selectRaw("MAX(CAST(SUBSTRING(kode_pemesanan, -3) AS UNSIGNED)) as last_number")
            ->value('last_number');

        $next = $latest ? $latest + 1 : 1;

        $kodePemesanan = 'MESS-' . $today . '-' . str_pad($next, 3, '0', STR_PAD_LEFT);

        // Simpan / update penyewa
        $existingPenyewa = DB::table('penyewas')->where('nik', $request->nik)->first();
        if ($existingPenyewa) {
            DB::table('penyewas')->where('nik', $request->nik)->update([
                'nama_lengkap' => $request->nama_lengkap,
                'no_telepon' => '62' . ltrim($request->no_telepon, '0'),
                'updated_at' => now(),
            ]);
            $penyewaId = $existingPenyewa->id_penyewa;
        } else {
            $penyewaId = DB::table('penyewas')->insertGetId([
                'nama_lengkap' => $request->nama_lengkap,
                'nik' => $request->nik,
                'no_telepon' => '62' . ltrim($request->no_telepon, '0'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $tanggal_checkin = $start->setTime(13, 0, 0);
        $tanggal_checkout = $end->setTime(13, 0, 0);

        // Buat pemesanan
        DB::table('pemesanans')->insert([
            'kode_pemesanan' => $kodePemesanan,
            'id_penyewa' => $penyewaId,
            'tanggal_checkin' => $tanggal_checkin,
            'tanggal_checkout' => $tanggal_checkout,
            'subtotal' => $totalPrice,
            'status' => 'diterima', // default sudah diterima
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Simpan detail kamar
        foreach ($rooms as $room) {
            $roomIds = $room['ids'];
            $hargaPerMalam = $room['price'];

            foreach ($roomIds as $id_kamar) {
                DB::table('detail_pemesanan_kamar')->insert([
                    'kode_pemesanan' => $kodePemesanan,
                    'id_kamar' => $id_kamar,
                    'subtotal' => $hargaPerMalam * $nights,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Simpan pembayaran (sementara cash, bisa dikembangkan)
        $pembayaranId = DB::table('pembayaran')->insertGetId([
            'metode_pembayaran' => 'cash',
            'url_path' => null,
            'kode_pemesanan' => $kodePemesanan,
        ]);

        $pemesanan = Pemesanan::with(['penyewa', 'pembayaran'])
            ->where('kode_pemesanan', $kodePemesanan)
            ->firstOrFail();

        // Generate invoice
        InvoiceService::generate($pemesanan);

        return redirect()->back()->with('success', 'Sewa berhasil diajukan dan invoice berhasil dibuat.');
    }


    public function pengajuanPNBP(Request $request)
    {
        $data = $request->validate([
            'pemesanans_ids' => 'required|string'
        ]);

        $pemesanansIds = explode(',', $data['pemesanans_ids']);

        // 1 Buat PNBP baru
        $pnbp = Pnbp::create([
            'status_setoran' => 'pengajuan',
            'tanggal_setoran' => now(),
            'total_setoran' => Pemesanan::whereIn('kode_pemesanan', $pemesanansIds)->sum('subtotal'),
            'no_ntb' => null,
            'bukti_pnbp' => null
        ]);

        foreach ($pemesanansIds as $kode) {
            // 2️ Buat SetoranPnbp untuk setiap kode pemesanan
            SetoranPnbp::create([
                'id_pnbp' => $pnbp->id_pnbp,
                'kode_pemesanan' => $kode
            ]);
        }

        return back()->with('success', 'Setoran berhasil disimpan.');
    }


    public function setujuiPNBP($id)
    {
        $pnbp = Pnbp::findOrFail($id);
        $pnbp->update(['status_setoran' => 'disetujui']);

        return back()->with('success', 'PNBP berhasil disetujui.');
    }

    public function tolakPNBP(Request $request, $id)
    {
        $request->validate(['alasan_penolakan' => 'required|string']);

        $pnbp = Pnbp::findOrFail($id);
        $pnbp->update(['status_setoran' => 'ditolak']);

        Penolakan::create([
            'entitas_type' => 'pnbp',
            'entitas_id' => $pnbp->id_pnbp,
            'alasan_penolakan' => $request->alasan_penolakan
        ]);

        return back()->with('success', 'PNBP berhasil ditolak.');
    }
    public function isiBuktiPNBP(Request $request, $id)
    {
        $request->validate([
            'tanggal_setoran' => 'required|date',
            'no_ntb' => 'required|numeric',
            'bukti_pnbp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $pnbp = Pnbp::findOrFail($id);

        if ($request->hasFile('bukti_pnbp')) {
            $file = $request->file('bukti_pnbp');
            $timestamp = now()->format('dmY_His');
            $extension = $file->getClientOriginalExtension();
            $filename = 'bukti_pnbp_' . $id . '_' . $timestamp . '.' . $extension;
            $path = $file->storeAs('public/bukti_pnbp', $filename);
            $pnbp->bukti_pnbp = $path;
        }

        $pnbp->tanggal_setoran = $request->tanggal_setoran;
        $pnbp->no_ntb = $request->no_ntb;
        $pnbp->total_setoran = $request->total_setoran;
        $pnbp->status_setoran = 'selesai';
        $pnbp->save();

        return back()->with('success', 'Bukti PNBP berhasil disimpan.');
    }
    public function lihatBuktiPNBP($filename)
    {
        $path = 'public/bukti_pnbp/' . $filename;

        if (!Storage::exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $file = Storage::get($path);
        $mimeType = Storage::mimeType($path);

        return Response::make($file, 200)->header("Content-Type", $mimeType);
    }
    public function batalkan_refund(Request $request, $kode_pemesanan)
    {
        $validated = $request->validate([
            'alasan_pembatalan' => 'required|string',
            'metode_refund'     => 'required|string|in:Transfer,Cash',
            'bukti_refund'      => 'required|file|max:2048',
        ]);

        // Ambil data pemesanan
        $pemesanan = Pemesanan::where('kode_pemesanan', $kode_pemesanan)->firstOrFail();

        $timestamp = Carbon::now()->format('dmY_His');
        $extension = $request->file('bukti_refund')->getClientOriginalExtension();
        $fileName  = "bukti_refund_{$pemesanan->kode_pemesanan}_{$timestamp}." . $extension;

        // Simpan file dengan nama baru
        $path = $request->file('bukti_refund')->storeAs('bukti_refund', $fileName, 'public');

        // Simpan ke tabel pembatalan_sewas
        PembatalanSewa::create([
            'kode_pemesanan'    => $pemesanan->kode_pemesanan, //  ganti ini
            'alasan_pembatalan' => $validated['alasan_pembatalan'],
            'metode_refund'     => $validated['metode_refund'],
            'bukti_refund'      => $path,
        ]);

        // Update status pemesanan
        $pemesanan->update(['status' => 'dibatalkan refund']);

        return back()->with('success', 'Pemesanan berhasil dibatalkan.');
    }

    public function rekap_sewa(Request $request)
    {
        $startMonth = $request->input('start_date') ?? now()->format('Y-m');
        $endMonth = $request->input('end_date') ?? now()->format('Y-m');

        $start = Carbon::parse($startMonth . '-01')->startOfMonth();
        $end = Carbon::parse($endMonth . '-01')->endOfMonth();
$rekap = Pemesanan::with(['detailKamar.kamar.kategori'])
    ->where('status', 'selesai')
    ->whereBetween('tanggal_checkin', [$start, $end])
    ->get()
    ->flatMap(function ($pemesanan) {
        return $pemesanan->detailKamar->map(function ($detail) use ($pemesanan) {
        
$totalMalam = $detail->subtotal / $detail->harga_per_malam;
            return [
                'bulan' => Carbon::parse($pemesanan->tanggal_checkin)->format('Y-m'),
                'nama_kategori' => $detail->nama_kategori,
                'tarif' => $detail->harga_per_malam,
                'total_malam' => $totalMalam,
                'jumlah' => $detail->subtotal, // konsisten tarif × malam
            ];
        });
    })
    ->groupBy(function ($item) {
        return $item['bulan'] . '_' . $item['nama_kategori'] . '_' . $item['tarif'];
    })
    ->map(function ($group) {
        return [
            'bulan' => $group[0]['bulan'],
            'nama_kategori' => $group[0]['nama_kategori'],
            'tarif' => $group[0]['tarif'],
            'total_malam' => collect($group)->sum('total_malam'),
            'jumlah' => collect($group)->sum('jumlah'),
        ];
    })
    ->values();

        return view('admin.bmn.transaksi.rekap_sewa', compact('rekap', 'start', 'end'));
    }

    public function rekap_sewa_cetakPDF(Request $request)
    {
        $startMonth = $request->input('start_date') ?? now()->format('Y-m');
        $endMonth = $request->input('end_date') ?? now()->format('Y-m');

        $start = Carbon::parse($startMonth . '-01')->startOfMonth();
        $end = Carbon::parse($endMonth . '-01')->endOfMonth();

       $rekap = Pemesanan::with(['detailKamar.kamar.kategori'])
    ->where('status', 'selesai')
    ->whereBetween('tanggal_checkin', [$start, $end])
    ->get()
    ->flatMap(function ($pemesanan) {
        return $pemesanan->detailKamar->map(function ($detail) use ($pemesanan) {
        
$totalMalam = $detail->subtotal / $detail->harga_per_malam;
            return [
                'bulan' => Carbon::parse($pemesanan->tanggal_checkin)->format('Y-m'),
                'nama_kategori' => $detail->nama_kategori,
                'tarif' => $detail->harga_per_malam,
                'total_malam' => $totalMalam,
                'jumlah' => $detail->subtotal, // konsisten tarif × malam
            ];
        });
    })
    ->groupBy(function ($item) {
        return $item['bulan'] . '_' . $item['nama_kategori'] . '_' . $item['tarif'];
    })
    ->map(function ($group) {
        return [
            'bulan' => $group[0]['bulan'],
            'nama_kategori' => $group[0]['nama_kategori'],
            'tarif' => $group[0]['tarif'],
            'total_malam' => collect($group)->sum('total_malam'),
            'jumlah' => collect($group)->sum('jumlah'),
        ];
    })
    ->values();

        $bulanAwal = Carbon::parse($start)->translatedFormat('F');
        $bulanAkhir = Carbon::parse($end)->translatedFormat('F');
        $tahun = Carbon::parse($start)->format('Y');
        $kopSurat = RefKopSurat::where('is_aktif', 1)->latest()->first();

        $pdf = Pdf::loadView('admin.bmn.transaksi.rekap_sewa_pdf', compact(
            'rekap',
            'start',
            'end',
            'bulanAwal',
            'bulanAkhir',
            'tahun',
            'kopSurat'
        ))->setPaper('a4', 'portrait');

        return $pdf->download("Penerimaan Sewa Mess dari $bulanAwal sampai $bulanAkhir $tahun.pdf");
    }


    public function rekap_kamar()
    {
        $kamars = Kamar::with(['fasilitas', 'kategori'])->get();

        return view('admin.bmn.transaksi.rekap_kamar', compact('kamars'));
    }
    public function rekap_kamar_cetakPDF()
    {
        $kamars = Kamar::with(['fasilitas', 'kategori'])->get();
        $kopSurat = RefKopSurat::where('is_aktif', 1)->latest()->first();

        $pdf = Pdf::loadView('admin.bmn.transaksi.rekap_kamar_pdf', [
            'kamars' => $kamars,
            'kopSurat' => $kopSurat ?? null,
        ])->setPaper('A4', 'potrait');

        return $pdf->download('rekap_data_kamar.pdf');
    }
    public function rekap_histori_kamar(Request $request)
    {
        $start = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : now()->startOfMonth();
        $end   = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : now()->endOfMonth();

        // Ambil semua kamar beserta kategori
        $kamars = Kamar::with('kategori')
            ->get()
            ->map(function ($kamar) use ($start, $end) {
                // Hitung total malam berdasarkan relasi detailKamar -> pemesanan
                $totalMalam = $kamar->detailKamar()
                    ->whereHas('pemesanan', function ($q) use ($start, $end) {
                        $q->where('status', 'selesai')
                            ->whereBetween('tanggal_checkin', [$start, $end]);
                    })
                    ->get()
                    ->sum(function ($detail) {
                        return $detail->pemesanan
                            ? \Carbon\Carbon::parse($detail->pemesanan->tanggal_checkout)
                            ->diffInDays(\Carbon\Carbon::parse($detail->pemesanan->tanggal_checkin))
                            : 0;
                    });

                $kamar->jumlah_tersewa = $totalMalam;
                $kamar->total = $totalMalam * $kamar->harga_per_malam;
                return $kamar;
            });

        return view('admin.bmn.transaksi.rekap_histori_kamar', compact('kamars', 'start', 'end'));
    }

    public function rekap_histori_kamar_cetakPDF(Request $request)
    {
        $start = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : now()->startOfMonth();
        $end   = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : now()->endOfMonth();

        $kamars = Kamar::with('kategori')
            ->get()
            ->map(function ($kamar) use ($start, $end) {
                $totalMalam = $kamar->detailKamar()
                    ->whereHas('pemesanan', function ($q) use ($start, $end) {
                        $q->where('status', 'selesai')
                            ->whereBetween('tanggal_checkin', [$start, $end]);
                    })
                    ->get()
                    ->sum(function ($detail) {
                        return $detail->pemesanan
                            ? \Carbon\Carbon::parse($detail->pemesanan->tanggal_checkout)
                            ->diffInDays(\Carbon\Carbon::parse($detail->pemesanan->tanggal_checkin))
                            : 0;
                    });

                $kamar->jumlah_tersewa = $totalMalam;
                $kamar->total = $totalMalam * $kamar->harga_per_malam;
                return $kamar;
            });

        $grandTotal = $kamars->sum('total');
        $kopSurat = RefKopSurat::where('is_aktif', 1)->latest()->first();

        $pdf = Pdf::loadView('admin.bmn.transaksi.rekap_histori_kamar_pdf', compact('kamars', 'start', 'end', 'grandTotal', 'kopSurat'));
        return $pdf->stream('rekap_histori_kamar.pdf');
    }
    public function editUpgrade(Request $request, $kode_pemesanan)
    {
        $pemesanan = Pemesanan::with(['detailKamar.kamar'])->where('kode_pemesanan', $kode_pemesanan)->firstOrFail();

        $malam = Carbon::parse($pemesanan->tanggal_checkin)->diffInDays(Carbon::parse($pemesanan->tanggal_checkout));
        $totalSelisih = 0;

        foreach ($request->upgrade as $idDetail => $idKamarBaru) {
            if (!$idKamarBaru) continue; // skip kalau tidak ada perubahan

            $detail = DetailPemesananKamar::with('kamar')->findOrFail($idDetail);
            $kamarLama = $detail->kamar;
            $kamarBaru = Kamar::findOrFail($idKamarBaru);

            // Validasi: available
            if ($kamarBaru->status_kamar !== 'available') {
                return back()->with('error', "Kamar {$kamarBaru->nomor_kamar} tidak tersedia.");
            }

            // Validasi: tidak boleh downgrade
            if ($kamarBaru->harga_per_malam < $kamarLama->harga_per_malam) {
                return back()->with('error', "Tidak bisa downgrade ke kamar lebih murah.");
            }

            // Hitung selisih harga
            $selisih = ($kamarBaru->harga_per_malam - $kamarLama->harga_per_malam) * $malam;
            $totalSelisih += $selisih;

            // Update detail pemesanan
            $detail->update([
                'id_kamar' => $kamarBaru->id_kamar,
                'subtotal' => $kamarBaru->harga_per_malam * $malam
            ]);

            // Simpan histori
            DB::table('pemesanan_histories')->insert([
                'kode_pemesanan' => $pemesanan->kode_pemesanan,
                'id_kamar_lama' => $kamarLama->id_kamar,
                'id_kamar_baru' => $kamarBaru->id_kamar,
                'selisih' => $selisih,
                'created_at' => now()
            ]);
        }

        // Update subtotal pemesanan
        $pemesanan->subtotal += $totalSelisih;
        $pemesanan->save();

        // Regenerate invoice baru
        InvoiceService::generate($pemesanan);

        return back()->with('success', $totalSelisih > 0
            ? "Upgrade berhasil. Tambahan biaya Rp " . number_format($totalSelisih, 0, ',', '.')
            : "Perubahan kamar berhasil tanpa tambahan biaya.");
    }
}
