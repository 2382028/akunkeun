<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Fasilitas;
use App\Models\operasional;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AksesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\IKUApiController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\AdminBMNController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\PerjadinController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\AdminOtherController;
use App\Http\Controllers\AkunXRkaklController;
use App\Http\Controllers\NonPegawaiController;
use App\Http\Controllers\RkaklOutputController;
use App\Http\Controllers\RkaklSatkerController;
use App\Http\Controllers\RkaklProgramController;
use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\AdminKegiatanController;
use App\Http\Controllers\AdminPerjadinController;
use App\Http\Controllers\RkaklKegiatanController;
use App\Http\Controllers\RkaklKomponenController;
use App\Http\Controllers\RkaklSuboutputController;
use App\Http\Controllers\RkaklSubkomponenController;
use App\Http\Controllers\AnehController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// route admin

// get ~ admin
Route::get('/aneh', [AnehController::class, 'index']);


Route::get('/pdfprint', [LoginController::class, 'pdf'])->name('user');
Route::get('/administrator', [LoginController::class, 'login'])->name('login')->middleware('guest');
Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard')->middleware('auth:administrator');

// post ~ admin
Route::post('/verifikasi', [LoginController::class, 'authenticate']);
Route::post('/logout-admin', [LoginController::class, 'logout']);


//  route perjadin kegiatan ~ admin

// get
Route::get('/kegiatan-mobilitas/{status}', [AdminKegiatanController::class, 'index'])->name('mobilitas')->middleware('auth:administrator');
Route::get('/kegiatan-assets/{status}', [AdminKegiatanController::class, 'assetIndex'])->name('sapras')->middleware('auth:administrator');
Route::get('/kegiatan-keuangan/{status}', [AdminKegiatanController::class, 'keuanganIndex'])->name('keuangan-kegiatan')->middleware('auth:administrator');
Route::get('/kegiatan-bendahara/{status}', [AdminKegiatanController::class, 'bendaharaIndex'])->name('bendahara-kegiatan')->middleware('auth:administrator');
Route::get('/kegiatan-mobilitas/detail/{id}', [AdminKegiatanController::class, 'detail_mobilitas'])->middleware('auth:administrator');
Route::get('/detail-sapras/{id}', [AdminKegiatanController::class, 'detail_sapras'])->middleware('auth:administrator');
Route::get('/detail-keuangan/{id}', [AdminKegiatanController::class, 'detail_keuangan'])->middleware('auth:administrator');
Route::get('/detail-bendahara/{id}', [AdminKegiatanController::class, 'detail_bendahara'])->middleware('auth:administrator');

// post
Route::post('/c_a_admin_mobilitas', [AdminKegiatanController::class, 'storeMobilitas'])->middleware('auth:administrator');
Route::post('/cu_kegiatan_keuangan', [AdminKegiatanController::class, 'storeKeuangan'])->middleware('auth:administrator');
Route::post('/cu_kegiatan_bendahara', [AdminKegiatanController::class, 'storeBendahara'])->middleware('auth:administrator');

// put
Route::post('/up_peminjaman_sapras', [AdminKegiatanController::class, 'updateSapras'])->middleware('auth:administrator');

// route perjalanan dinas ~admin
Route::get('/perjadin-mobilitas/{status}', [AdminPerjadinController::class, 'index'])->name('mobilitas-perjadin')->middleware('auth:administrator');
Route::get('/perjadin-mobilitas/detail/{id}', [AdminPerjadinController::class, 'detail_mobilitas'])->name('detail-mobilitas-perjadin')->middleware('auth:administrator');
Route::get('/perjadin-mobilitas/detail_mobilitas/{id}', [AdminPerjadinController::class, 'detail_perjadin_BMN'])->name('detail-perjadin-BMN')->middleware('auth:administrator');
Route::get('/perjadin-keuangan/{status}', [AdminPerjadinController::class, 'keuanganIndex'])->name('keuangan-perjadin')->middleware('auth:administrator');
Route::get('/perjadin-keuangan/detail/{id}', [AdminPerjadinController::class, 'detail_perjadin_keuangan'])->middleware('auth:administrator');
// Route::get('/perjadin-getDokumen{filename}', [AdminPerjadinController::class, 'getDokumen'])->middleware('auth:administrator');
Route::get('/perjadin-getDokumen/{filename}', [AdminPerjadinController::class, 'getDokumen'])->middleware('auth:administrator');


Route::get('/perjadin-bendahara/{status}', [AdminPerjadinController::class, 'bendaharaIndex'])->name('bendahara-perjadin')->middleware('auth:administrator');
Route::get('/perjadin-bendahara/detail/{id}', [AdminPerjadinController::class, 'detail_perjadin_bendahara'])->name('bendahara-perjadin-fasilitas')->middleware('auth:administrator');
Route::get('/note-perjadin-admin/{id}', [AdminPerjadinController::class, 'note_perjadin'])->name('note-perjadin-admin')->middleware('auth:administrator');
Route::get('/note-perjadin-laporan/{id}', [AdminOtherController::class, 'note_perjadin_laporan'])->name('note-perjadin-laporan')->middleware('auth:administrator');
Route::get('/perjadin-HKT/{status}', [AdminPerjadinController::class, 'HKTIndex'])->name('HKT-perjadin')->middleware('auth:administrator');
Route::get('/perjadin-HKT/detail/{id}', [AdminPerjadinController::class, 'detail_perjadin_HKT'])->name('detail-HKT-perjadin')->middleware('auth:administrator');
Route::get('/perjadin-HKT/surtug/{id}', [AdminPerjadinController::class, 'surtug_perjadin_HKT'])->name('surtug-HKT-perjadin')->middleware('auth:administrator');
Route::get('/perjadin-HKT/surtug/detail/{id}', [AdminPerjadinController::class, 'detail_surtug_perjadin_HKT'])->name('surtug-detail-HKT-perjadin')->middleware('auth:administrator');
Route::get('/perjadin-HKT/surtug/preview/{id}', [AdminPerjadinController::class, 'ConvertSurtug'])->middleware('auth:administrator');
Route::get('/perjadin-HKT/surtug/edit/{id}', [AdminPerjadinController::class, 'detail_surtug_perjadin_HKT_edit'])->name('surtug-edit')->middleware('auth:administrator');
Route::get('/perjadin-bendahara/sppd/{id}', [AdminPerjadinController::class, 'CetakSPPD'])->middleware('auth:administrator');
// POST
Route::post('/cu_perjadinmobilitas', [AdminPerjadinController::class, 'store'])->middleware('auth:administrator');
Route::post('/c_tambahmobilitas', [AdminPerjadinController::class, 'storeMobilitas'])->middleware('auth:administrator');
Route::delete('/h_mobilitas/{id}', [AdminPerjadinController::class, 'deleteMobilitas']);


Route::post('/cu_perjadin_keuangan', [AdminPerjadinController::class, 'storeKeuangan'])->middleware('auth:administrator');
Route::post('/cu_perjadin_bendahara', [AdminPerjadinController::class, 'storeBendahara'])->middleware('auth:administrator');
Route::post('/cu_perjadin_HKT', [AdminPerjadinController::class, 'storeSurtug'])->middleware('auth:administrator');
Route::post('/c_perjadin_HKT', [AdminPerjadinController::class, 'storeSurtugPDF'])->middleware('auth:administrator');
Route::post('/u_perjadin_HKT', [AdminPerjadinController::class, 'UploadSurtug'])->middleware('auth:administrator');
Route::post('/e_perjadin_HKT', [AdminPerjadinController::class, 'EditSurtug'])->middleware('auth:administrator');
Route::post('/t_perjadin_HKT', [AdminPerjadinController::class, 'TolakPerjadin'])->middleware('auth:administrator');
Route::get('/akses', [AksesController::class, 'index'])->name('login')->middleware('guest');
Route::post('/akses', [AksesController::class, 'authenticate']);
Route::post('/logout', [AksesController::class, 'logout']);
Route::post('/c_fasilitas_bendahara', [AdminPerjadinController::class, 'storeFasilitasBendahara'])->middleware('auth:administrator');
Route::post('/c_fasilitasDetail_bendahara', [AdminPerjadinController::class, 'storeFasilitasDetailBendahara'])->middleware('auth:administrator');
Route::post('/c_fasilitas_BMN', [AdminPerjadinController::class, 'storeFasilitasBMN'])->middleware('auth:administrator');
Route::post('/c_fasilitasDetail_BMN', [AdminPerjadinController::class, 'storeFasilitasDetailBMN'])->middleware('auth:administrator');


// route BMN Referensi ~admin
// get
Route::get('/data_penyedia', [AdminBMNController::class, 'index'])->name('data_penyedia')->middleware('auth:administrator');
Route::get('/data_penyedia/detail/{id}', [AdminBMNController::class, 'detail_penyedia'])->middleware('auth:administrator');
Route::get('/data_kendaraan', [AdminBMNController::class, 'indexKendaraan'])->name('data_kendaraan')->middleware('auth:administrator');
Route::get('/data_kendaraan/detail/{id}', [AdminBMNController::class, 'detail_kendaraan'])->middleware('auth:administrator');
Route::get('/data_assets', [AdminBMNController::class, 'indexAssets'])->name('data_assets')->middleware('auth:administrator');
Route::get('/data_asset/detail/{id}', [AdminBMNController::class, 'detail_asset'])->middleware('auth:administrator');
Route::get('/data_ruangan', [AdminBMNController::class, 'indexRuangan'])->name('data_ruangan')->middleware('auth:administrator');
Route::get('/data_ruangan/detail/{id}', [AdminBMNController::class, 'detail_ruangan'])->middleware('auth:administrator');

// post
Route::post('/c_bmn_penyedia', [AdminBMNController::class, 'storePenyedia'])->middleware('auth:administrator');
Route::post('/c_bmn_kendaraan', [AdminBMNController::class, 'storeKendaraan'])->middleware('auth:administrator');
Route::post('/c_bmn_asset', [AdminBMNController::class, 'storeAsset'])->middleware('auth:administrator');
Route::post('/c_bmn_ruangan', [AdminBMNController::class, 'storeRuangan'])->middleware('auth:administrator');
Route::post('/c_bmn_asset_peminjaman', [AdminBMNController::class, 'storeAssetPeminjaman'])->middleware('auth:administrator');

// update
Route::post('/up_data_penyedia', [AdminBMNController::class, 'updatePenyedia'])->middleware('auth:administrator');
Route::post('/up_data_kendaraan', [AdminBMNController::class, 'updateKendaraan'])->middleware('auth:administrator');
Route::post('/up_data_asset', [AdminBMNController::class, 'updateAsset'])->middleware('auth:administrator');
Route::post('/up_data_ruangan', [AdminBMNController::class, 'updateRuangan'])->middleware('auth:administrator');

// delete
Route::post('/d_penyedia/{id}', [AdminBMNController::class, 'destroyPenyedia'])->middleware('auth:administrator');
Route::post('/d_kendaraan/{id}', [AdminBMNController::class, 'destroyKendaraan'])->middleware('auth:administrator');
Route::post('/d_asset/{id}', [AdminBMNController::class, 'destroyAsset'])->middleware('auth:administrator');
Route::post('/d_ruangan/{id}', [AdminBMNController::class, 'destroyRuangan'])->middleware('auth:administrator');

// route BMN admin Transaksi
// get
Route::get('/peminjaman_asset/{status}', [AdminBMNController::class, 'indexPeminjaman'])->name('peminjaman_asset')->middleware('auth:administrator');
Route::get('/service/asset/{id}', [AdminBMNController::class, 'indexServiceAsset'])->middleware('auth:administrator');
Route::get('/perbaikan_assets/{status}', [AdminBMNController::class, 'indexPerbaikanAsset'])->name('service-assets')->middleware('auth:administrator');
Route::get('/pembayaran_service_assets', [AdminBMNController::class, 'indexPerbaikanAssetPembayaran'])->name('pembayaran_service_assets')->middleware('auth:administrator');
Route::get('/service/penyedia-asset/{id}', [AdminBMNController::class, 'detailPenyediaAsset'])->middleware('auth:administrator');
Route::get('/service/detail-asset/{id}', [AdminBMNController::class, 'detailPerbaikanAsset'])->name('detail_service-asset')->middleware('auth:administrator');

// service kendaraan
Route::get('/service_kendaraan/{id}', [AdminBMNController::class, 'indexServiceKendaraan'])->middleware('auth:administrator');
Route::post('/c_service_kendaraan', [AdminBMNController::class, 'storeServiceKendaraan'])->middleware('auth:administrator');
Route::get('/service_kendaraan_all/{status}', [AdminBMNController::class, 'indexPerbaikanKendaraan'])->name('service-kendaraan')->middleware('auth:administrator');
Route::get('/service/detail_kendaraan/{id}', [AdminBMNController::class, 'detailPerbaikanKendaraan'])->name('service_kendaraan_detail')->middleware('auth:administrator');
Route::post('/c_service_komponen_kendaraan', [AdminBMNController::class, 'storeServiceKendaraanKomponen'])->middleware('auth:administrator');
Route::get('/pembayaran_service_kendaraan', [AdminBMNController::class, 'indexPerbaikanKendaraanPembayaran'])->name('pembayaran_service_kendaraan')->middleware('auth:administrator');
Route::get('/service/penyedia-kendaraan/{id}', [AdminBMNController::class, 'detailPenyediaKendaraan'])->middleware('auth:administrator');
Route::post('/c_konfirmasi_service_kendaraan', [AdminBMNController::class, 'storeKonfirmasiKendaraan'])->middleware('auth:administrator');

// service ruangan
Route::get('/service_ruangan/{id}', [AdminBMNController::class, 'indexServiceRuangan'])->middleware('auth:administrator');
Route::post('/c_service_ruangan', [AdminBMNController::class, 'storeServiceRuangan'])->middleware('auth:administrator');
Route::get('/service_ruangan_all/{status}', [AdminBMNController::class, 'indexPerbaikanRuangan'])->name('service-ruangan')->middleware('auth:administrator');
Route::get('/service/detail_ruangan/{id}', [AdminBMNController::class, 'detailPerbaikanRuangan'])->name('service_ruangan_detail')->middleware('auth:administrator');
Route::post('/c_service_komponen_ruangan', [AdminBMNController::class, 'storeServiceRuanganKomponen'])->middleware('auth:administrator');
Route::post('/c_permohonan_service_ruangan', [AdminBMNController::class, 'storeServiceRuanganPembaharuan'])->middleware('auth:administrator');
Route::get('/pembayaran_service_ruangan', [AdminBMNController::class, 'indexPerbaikanRuanganPembayaran'])->name('pembayaran_service_ruangan')->middleware('auth:administrator');
Route::get('/service/penyedia-ruangan/{id}', [AdminBMNController::class, 'detailPenyediaRuangan'])->middleware('auth:administrator');
Route::post('/c_konfirmasi_service_ruangan', [AdminBMNController::class, 'storeKonfirmasiRuangan'])->middleware('auth:administrator');


// keuangan
Route::get('/service_keuangan/{status}', [AdminBMNController::class, 'indexKeuanganService'])->name('service_keuangan')->middleware('auth:administrator');
Route::get('/service_keuangan_riwayat/{status}', [AdminBMNController::class, 'indexKeuanganServiceRiwayat'])->name('service_keuangan_riwayat')->middleware('auth:administrator');
Route::get('/bmn_keuangan/detail/{id}', [AdminBMNController::class, 'detailKeuanganPerbaikanAsset'])->name('detail_keuangan_service-asset')->middleware('auth:administrator');
Route::get('/bmn_keuangan_riwayat/detail/{id}', [AdminBMNController::class, 'detailKeuanganPerbaikanAsset_riwayat'])->name('detail_keuangan_service-asset')->middleware('auth:administrator');
Route::post('/c_keuangan_service', [AdminBMNController::class, 'storeKeuanganService'])->middleware('auth:administrator');
Route::post('/c_permohonan_service_kendaraan', [AdminBMNController::class, 'storeServiceKendaraanPembaharuan'])->middleware('auth:administrator');


// bendahara
Route::get('/service_bendahara/{status}', [AdminBMNController::class, 'indexBendaharaService'])->name('service_bendahara')->middleware('auth:administrator');
Route::get('/service_bendahara_riwayat/{status}', [AdminBMNController::class, 'indexBendaharaServiceRiwayat'])->name('service_bendahara_riwayat')->middleware('auth:administrator');
Route::get('/bmn_bendahara/detail/{id}', [AdminBMNController::class, 'detailBendaharaPerbaikanAsset'])->middleware('auth:administrator');
Route::get('/bmn_bendahara/riwayat/{id}', [AdminBMNController::class, 'detailBendaharaPerbaikanAssetRiwayat'])->middleware('auth:administrator');
Route::post('/c_bendahara_service', [AdminBMNController::class, 'storeBendaharaService'])->middleware('auth:administrator');

// post service Asset
Route::post('/c_service_asset', [AdminBMNController::class, 'storeServiceAsset'])->middleware('auth:administrator');
Route::post('/c_service_komponen_asset', [AdminBMNController::class, 'storeServiceAssetKomponen'])->middleware('auth:administrator');
Route::post('/c_permohonan_service_asset', [AdminBMNController::class, 'storeServiceAssetPembaharuan'])->middleware('auth:administrator');
Route::post('/c_konfirmasi_service_assets', [AdminBMNController::class, 'storeKonfirmasiAsset'])->middleware('auth:administrator');

// aksi
Route::post('/action_peminjaman/{id}', [AdminBMNController::class, 'updatePeminjaman'])->middleware('auth:administrator');

// delete
Route::post('/d_komponen_service_asset/{id}', [AdminBMNController::class, 'destroykomponenServiceAsset'])->middleware('auth:administrator');

Route::get('/', function () {
    return view('user.beranda', [
        'title' => 'Beranda',
        'active' => 'index',
    ]);
})->middleware('auth:pegawai');

// profile
Route::get('/profile', [AksesController::class, 'profile'])->name('profile')->middleware('auth:pegawai');
Route::get('/profile/ubah-password', [AksesController::class, 'ubah'])->middleware('auth:pegawai');
Route::post('/ubah-password', [AksesController::class, 'ubahPassword'])->middleware('auth:pegawai');


// route perjadin biasa ~user

// get
Route::get('/perjadin', [PerjadinController::class, 'index'])->middleware('auth:pegawai');
Route::get('/perjadin/getDokumen/{filename}', [PerjadinController::class, 'getDokumen'])->middleware('auth:pegawai');
Route::get('/perjadin/{id}', [PerjadinController::class, 'index'])->name('perjadin_step_1')->middleware('auth:pegawai');
Route::get('perjadin_step_2/{id}', [PerjadinController::class, 'step2'])->name('perjadin_step_2')->middleware('auth:pegawai');
Route::get('/perjadin/riwayat/{status}', [PerjadinController::class, 'riwayat'])->name('riwayat')->middleware('auth:pegawai');
Route::get('/detail-perjadin/{id}', [PerjadinController::class, 'detail_perjadin'])->name('detail-perjadin')->middleware('auth:pegawai');
Route::get('/note-perjadin/{id}', [PerjadinController::class, 'note_perjadin'])->name('note-perjadin')->middleware('auth:pegawai');
Route::get('/note-perjadin-user/{id}', [PerjadinController::class, 'previewPerjadinUser'])->name('note-perjadin-user')->middleware('auth:pegawai');

// create
Route::post('/perjadin/store', [PerjadinController::class, 'store'])->middleware('auth:pegawai');
Route::post('/c_peserta', [PerjadinController::class, 'storePeserta'])->middleware('auth:pegawai');
Route::post('/c_non_peserta', [PerjadinController::class, 'storeNonPeserta'])->middleware('auth:pegawai');
Route::post('/c_fasilitas', [PerjadinController::class, 'storeFasilitas'])->middleware('auth:pegawai');
Route::post('/c_perjadin', [PerjadinController::class, 'storePerjadin'])->middleware('auth:pegawai');
Route::post('/note_perjadin', [PerjadinController::class, 'storeLaporanPerjadin'])->middleware('auth:pegawai');

Route::post('/c_pesertaDetail', [PerjadinController::class, 'storePesertaDetail'])->middleware('auth:pegawai');
Route::post('/c_non_pesertaDetail', [PerjadinController::class, 'storeNonPesertaDetail'])->middleware('auth:pegawai');
Route::post('/c_fasilitasDetail', [PerjadinController::class, 'storeFasilitasDetail'])->middleware('auth:pegawai');
Route::post('/la_perjadin', [PerjadinController::class, 'LaporPerjadin'])->middleware('auth:pegawai');

Route::put('/c_status_peserta/{id}', [PerjadinController::class, 'editPeserta'])->middleware('auth:pegawai');
Route::put('/u_perjadin', [PerjadinController::class, 'updatePerjadinDetail'])->middleware('auth:pegawai');

// hapus
Route::delete('/h_peserta/{HPeserta:id}', [PerjadinController::class, 'destroyPeserta'])->middleware('auth:pegawai');
Route::delete('/h_fasilitas_perjadin/{HFasilitasPerjadin:id}', [PerjadinController::class, 'destroyFasilitasPerjadin'])->middleware('auth:pegawai');
Route::post('/h_peserta_peserta_detail/{id}', [PerjadinController::class, 'destroyPesertaDetail'])->middleware('auth:pegawai');
Route::post('/h_peserta_nonpeserta_detail/{id}', [PerjadinController::class, 'destroyNonPesertaDetail'])->middleware('auth:pegawai');
Route::post('/h_fasilitas_detail/{id}', [PerjadinController::class, 'destroyKebutuhanDetail'])->middleware('auth:pegawai');


// route perjadin kegiatan ~user

// get
Route::get('/kegiatan', [KegiatanController::class, 'index'])->middleware('auth:pegawai');
// Route::get('/kegiatan/getDokumen/{filename}', [KegiatanController::class, 'getDokumen'])->middleware('auth:pegawai');
// Route::get('/kegiatan/getDokumen/{filename}', [KegiatanController::class, 'getDokumen'])->middleware('auth:pegawai');
Route::get('/kegiatan/getDokumen/{filename}', [PerjadinController::class, 'getDokumen'])->middleware('auth:pegawai');


Route::get('/kegiatan_step_2/{id}', [KegiatanController::class, 'KegiatanStep2'])->name('kegiatan_step_2')->middleware('auth:pegawai');
Route::get('/kegiatan_step_3/{id}', [KegiatanController::class, 'KegiatanStep3'])->name('kegiatan_step_3')->middleware('auth:pegawai');
Route::get('/kegiatan_step_4/{id}', [KegiatanController::class, 'KegiatanStep4'])->name('kegiatan_step_4')->middleware('auth:pegawai');
Route::get('/kegiatan_step_5/{id}', [KegiatanController::class, 'KegiatanStep5'])->name('kegiatan_step_5')->middleware('auth:pegawai');
Route::get('/kegiatan_step_6/{id}', [KegiatanController::class, 'KegiatanStep6'])->name('kegiatan_step_6')->middleware('auth:pegawai');
Route::get('/kegiatan_step_7/{id}', [KegiatanController::class, 'KegiatanStep7'])->name('kegiatan_step_7')->middleware('auth:pegawai');
Route::get('/kegiatan/riwayat/{status}', [KegiatanController::class, 'riwayat'])->name('riwayat-kegiatan')->middleware('auth:pegawai');
Route::get('/detail-kegiatan/{id}', [KegiatanController::class, 'detail'])->name('detail')->middleware('auth:pegawai');


// create
Route::post('/c_kegiatan', [KegiatanController::class, 'storeKegiatan'])->middleware('auth:pegawai');
Route::post('/c_fasilitasKegiatan', [KegiatanController::class, 'storeFasilitas'])->middleware('auth:pegawai');
Route::post('/c_peserta_kegiatan', [KegiatanController::class, 'storePeserta'])->middleware('auth:pegawai');
Route::post('/c_non_peserta_kegiatan', [KegiatanController::class, 'storeNonPegawai'])->middleware('auth:pegawai');
Route::post('/c_operasional', [KegiatanController::class, 'storeOperasional'])->middleware('auth:pegawai');
Route::post('/c_mobilitas_kegiatan', [KegiatanController::class, 'storeMobilitas'])->middleware('auth:pegawai');
Route::post('/c_sapras_kegiatan', [KegiatanController::class, 'storeSapras'])->middleware('auth:pegawai');
Route::post('/c_dokumen_kegiatan', [KegiatanController::class, 'storeDokumen'])->middleware('auth:pegawai');
Route::post('/cd_fasilitasKegiatan', [KegiatanController::class, 'storeFasilitasDetail'])->middleware('auth:pegawai');
Route::post('/cd_peserta_kegiatan', [KegiatanController::class, 'storePesertaDetail'])->middleware('auth:pegawai');
Route::post('/cd_non_peserta_kegiatan', [KegiatanController::class, 'storeNonPegawaiDetail'])->middleware('auth:pegawai');
Route::post('/cd_operasional', [KegiatanController::class, 'storeOperasionalDetail'])->middleware('auth:pegawai');
Route::post('/cd_mobilitas_kegiatan', [KegiatanController::class, 'storeMobilitasDetail'])->middleware('auth:pegawai');
Route::post('/cd_sapras_kegiatan', [KegiatanController::class, 'storeSaprasDetail'])->middleware('auth:pegawai');
Route::post('/cd_dokumen_kegiatan', [KegiatanController::class, 'storeDokumenDetail'])->middleware('auth:pegawai');

// put
Route::put('/c_kegiatan_all/{id}', [KegiatanController::class, 'storeKegiatanAll'])->middleware('auth:pegawai');
Route::put('/c_detailKegiatan/{id}', [KegiatanController::class, 'updateKegiatan'])->middleware('auth:pegawai')->middleware('auth:pegawai');
Route::put('/u_kegiatan_detail/{id}', [KegiatanController::class, 'updateKegiatanDetail'])->middleware('auth:pegawai')->middleware('auth:pegawai');

// delete
Route::delete('/h_peserta_kegiatan/{id}', [KegiatanController::class, 'destroyPesertaKegiatan'])->middleware('auth:pegawai');
Route::delete('/hd_peserta_kegiatan/{id}', [KegiatanController::class, 'destroyPesertaKegiatanDetail'])->middleware('auth:pegawai');
Route::delete('/h_operasional_kegiatan/{HOperasional:id}', [KegiatanController::class, 'destroyOperasionalKegiatan'])->middleware('auth:pegawai');
Route::delete('/hd_operasional_kegiatan/{HOperasional:id}', [KegiatanController::class, 'destroyOperasionalKegiatanDetail'])->middleware('auth:pegawai');
Route::delete('/h_mobilitas_kegiatan/{id}', [KegiatanController::class, 'destroyMobiltasKegiatan'])->middleware('auth:pegawai');
Route::delete('/hd_mobilitas_kegiatan/{id}', [KegiatanController::class, 'destroyMobiltasKegiatanDetail'])->middleware('auth:pegawai');
Route::delete('/h_sapras/{id}', [KegiatanController::class, 'destroySaprasKegiatan'])->middleware('auth:pegawai');
Route::delete('/hd_sapras/{id}', [KegiatanController::class, 'destroySaprasKegiatanDetail'])->middleware('auth:pegawai');
Route::delete('/h_dokumen_kegiatan/{id}', [KegiatanController::class, 'destroyDokumenKegiatan'])->middleware('auth:pegawai');
Route::delete('/hd_dokumen_kegiatan/{id}', [KegiatanController::class, 'destroyDokumenKegiatanDetail'])->middleware('auth:pegawai');


// Route Fasilitas ~user

// get
Route::get('/fasilitas', [FasilitasController::class, 'index'])->middleware('auth:pegawai');
Route::get('/peminjaman/{id}', [FasilitasController::class, 'peminjaman'])->middleware('auth:pegawai');
Route::get('/riwayat_barang/{status}', [FasilitasController::class, 'riwayat'])->name('riwayat_peminjaman_BMN')->middleware('auth:pegawai');
Route::get('/detail_peminjaman/{id}', [FasilitasController::class, 'detailRiwayat'])->name('detailRiwayat')->middleware('auth:pegawai');

// create
Route::post('/c_peminjaman', [FasilitasController::class, 'store'])->middleware('auth:pegawai');
Route::post('/c_permohonan', [FasilitasController::class, 'storePermohonan'])->middleware('auth:pegawai');

/* route kelola user -admin */
// administrator
Route::resource('/admin', AdminController::class);
// pegawai
Route::resource('/admin-pegawai', PegawaiController::class);
// non-pegawai
Route::resource('/admin-nonpegawai', NonPegawaiController::class,);

/* route rkakl -admin */
// rkakl_satker
Route::resource('/admin-rkakl_satker', RkaklSatkerController::class);
// rkakl_program
Route::resource('/admin-rkakl_program', RkaklProgramController::class);
// rkakl_kegiatan
Route::resource('/admin-rkakl_kegiatan', RkaklKegiatanController::class);
// rkakl_output
Route::resource('/admin-rkakl_output', RkaklOutputController::class);
// rkakl_suboutput
Route::resource('/admin-rkakl_suboutput', RkaklSuboutputController::class);
// rkakl_komponen
Route::resource('/admin-rkakl_komponen', RkaklKomponenController::class);
// rkakl_subkomponen
Route::resource('/admin-rkakl_subkomponen', RkaklSubkomponenController::class);
// rkakl_akun
Route::resource('/admin-rkakl_akun', AkunController::class);
// rkakl_akun_x_rkakl
Route::resource('/admin-akun_x_rkakl', AkunXRkaklController::class);
// iku
Route::get('/admin-iku', [IKUApiController::class, 'indexIKU'])->name('IKU')->middleware('auth:administrator');
Route::get('/detail_iku/{id}', [IKUApiController::class, 'indexDetailIKU'])->middleware('auth:administrator');
Route::post('/c_iku', [IKUApiController::class, 'storeIKU'])->middleware('auth:administrator');
Route::post('/d_iku/{id}', [IKUApiController::class, 'destroyIKU'])->middleware('auth:administrator');
Route::post('/detail_iku/u_iku', [IKUApiController::class, 'updateIKU'])->middleware('auth:administrator');

//SBM
Route::get('/sbm', [AdminOtherController::class, 'indexSBM'])->name('sbm')->middleware('auth:administrator');
Route::get('/detail_sbm/{id}', [AdminOtherController::class, 'indexDetailSBM'])->middleware('auth:administrator');
Route::post('/c_sbm', [AdminOtherController::class, 'storeSBM'])->middleware('auth:administrator');
Route::post('/d_sbm/{id}', [AdminOtherController::class, 'destroySBM'])->middleware('auth:administrator');
Route::post('/detail_sbm/u_sbm', [AdminOtherController::class, 'updateSBM'])->middleware('auth:administrator');


// other
Route::get('/pengaturan', [AdminOtherController::class, 'index'])->name('pengaturan')->middleware('auth:administrator');
Route::post('/c_pengaturan', [AdminOtherController::class, 'storePengaturan'])->middleware('auth:administrator');
Route::post('/set_versi', [AdminOtherController::class, 'setPengaturan'])->middleware('auth:administrator');

Route::get('/laporan', [AdminOtherController::class, 'indexLaporan'])->name('laporan')->middleware('auth:administrator');
Route::post('/ganerate_perjadin', [AdminOtherController::class, 'ganeratePerjadin'])->middleware('auth:administrator');
Route::get('/perjadin/{mulai}/{sampai}', [AdminOtherController::class, 'laporanPerjadin'])->name('laporanPerjadin')->middleware('auth:administrator');
Route::post('/ganerate_kegiatan', [AdminOtherController::class, 'ganerateKegiatan'])->middleware('auth:administrator');
Route::get('/kegiatan/{mulai}/{sampai}', [AdminOtherController::class, 'laporanKegiatan'])->name('laporanKegiatan')->middleware('auth:administrator');
Route::post('/ganerate_bmn', [AdminOtherController::class, 'ganerateBMN'])->middleware('auth:administrator');
Route::get('/BMN/{mulai}/{sampai}', [AdminOtherController::class, 'laporanBMN'])->name('laporanBMN')->middleware('auth:administrator');


Route::post('/cu_spby_perjadin', [AdminOtherController::class, 'updateSpby'])->middleware('auth:administrator');
Route::post('/cu_spby_kegiatan', [AdminOtherController::class, 'updateSpbyKegiatan'])->middleware('auth:administrator');
