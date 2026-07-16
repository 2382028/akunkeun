<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Fasilitas;
use App\Models\operasional;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
use App\Http\Controllers\DataBalikController;
use App\Http\Controllers\AdminPengadaanController;
use App\Http\Controllers\PdfUploadController;

use App\Http\Controllers\MasukController;
use App\Http\Controllers\PemeliharaanController;
use App\Http\Controllers\SewaController;

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
// =================== Umum ===================
Route::get('/preview-pesanan', [PemeliharaanController::class, 'previewPesanan'])->name('preview.pesanan');
Route::get('/kelompok-barang/{id_pesanan}', [PemeliharaanController::class, 'getKelompokBarang'])
->where('id_pesanan', '.*');
Route::get('/getDokumen/{filename}', [AdminOtherController::class, 'getDokumen']);

Route::get('/cek-jadwal-pemeliharaan', [PemeliharaanController::class, 'cekJadwalPemeliharaan']);

Route::get('/get-bmn-by-ruangan/{id_ruangan}', [PemeliharaanController::class, 'getBmnByRuangan']);
Route::get('/get-bmn-all', [PemeliharaanController::class, 'getAllBmn']);
Route::get('/get-bmn-name/{id_bmn}', [PemeliharaanController::class, 'getBmnName']);
Route::get('/get-alamat-cv/{id}', fn($id) => response()->json(['alamat' => \App\Models\Data_penyedia::find($id)->alamat ?? '']));

// Notifikasi Penyedia
Route::get('/notifPenyedia/{idPenyedia}', [PemeliharaanController::class, 'notifPenyedia'])->middleware('auth:penyedia');
Route::post('/notifPenyedia/read/{id}', [PemeliharaanController::class, 'markAsReadPenyedia'])->middleware('auth:penyedia');
Route::post('/mark-all-notif-penyedia/{idPenyedia}', [PemeliharaanController::class, 'markAllPenyedia'])->middleware('auth:penyedia');

// Notifikasi User Pemeliharaan
Route::post('/notifPemeliharaanUser/read/{id}', [PemeliharaanController::class, 'markAsReadUser']);
Route::get('/notifPemeliharaanUser/{idUser}', [PemeliharaanController::class, 'notifUser'])->middleware('auth:pegawai');
Route::post('/mark-all-notif-pemeliharaan-user/{idUser}', [PemeliharaanController::class, 'markAllUser']);

// BMN & Ruangan
Route::prefix('bmn')->group(function () {
    Route::get('/data', [AdminBMNController::class, 'indexBMN']);
    Route::get('/rekap', [AdminBMNController::class, 'rekap_bmn']);
    Route::get('/rekap-usia', [AdminBMNController::class, 'rekapUsia']);

    Route::get('/json', [AdminBMNController::class, 'jsonBMN']);
    Route::post('/save', [AdminBMNController::class, 'saveBMN']);
    Route::delete('/delete/{id}', [AdminBMNController::class, 'destroyBMN']);
});

Route::post('/rekap_pemeliharaan', [PemeliharaanController::class, 'rekapPemeliharaan'])->name('rekap_pemeliharaan');
Route::get('/rekap-pemeliharaan/pdf', [PemeliharaanController::class, 'rekapPemeliharaanPdf'])
    ->name('pemeliharaan.rekap.pdf');

// =================== Auth: Administrator ===================
Route::middleware('auth:administrator')->group(function () {

    // Referensi Golongan
    Route::prefix('ref-golongan')->group(function () {
        Route::get('/', [PegawaiController::class, 'indexGolongan'])->name('ref-golongan');
        // Tambah baru
        Route::post('/c_golongan', [PegawaiController::class, 'saveGolongan'])->name('c_golongan');
        
        // Update
        Route::put('/c_golongan/{id}', [PegawaiController::class, 'saveGolongan'])->name('update_golongan');

        Route::put('/{id}', [PegawaiController::class, 'saveGolongan'])->name('update_golongan');
        Route::delete('/{id}', [PegawaiController::class, 'destroyGolongan'])->name('destroy_golongan');
    });

    // Referensi Kop Surat
    Route::prefix('ref-kop-surat')->group(function () {
        Route::get('/', [AdminOtherController::class, 'indexKopSurat']);
        Route::get('/{id}', [AdminOtherController::class, 'editKopSurat']);
        Route::post('/store', [AdminOtherController::class, 'storeKopSurat']);
        Route::put('/update/{id}', [AdminOtherController::class, 'updateKopSurat']);
        Route::delete('/delete/{id}', [AdminOtherController::class, 'destroyKopSurat']);
        Route::post('/aktifkan/{id}', [AdminOtherController::class, 'aktifkanKop'])->name('kopsurat.aktifkan');
    });

Route::prefix('ref-surat-pemeliharaan')->name('nomor_surat.')->group(function () {
    Route::get('/', [PemeliharaanController::class, 'indexSuratPemeliharaan'])->name('index');
    Route::post('/store', [PemeliharaanController::class, 'storeSuratPemeliharaan'])->name('store');
    Route::put('/update/{nomor_surat}', [PemeliharaanController::class, 'updateSuratPemeliharaan'])
        ->where('nomor_surat', '.*')
        ->name('update');
    Route::delete('/delete/{nomor_surat}', [PemeliharaanController::class, 'destroySuratPemeliharaan'])
        ->where('nomor_surat', '.*')
        ->name('destroy');
});

    // Referensi Kode Layanan
    Route::prefix('ref-kode-layanan')->name('kode.layanan.')->group(function () {
        Route::get('/', [AdminOtherController::class, 'indexKodeLayanan'])->name('index');
        Route::post('/store', [AdminOtherController::class, 'storeKodeLayanan'])->name('store');
        Route::put('/update/{id}', [AdminOtherController::class, 'updateKodeLayanan'])->name('update');
        Route::delete('/{id}', [AdminOtherController::class, 'destroyKodeLayanan'])->name('destroy');
    });



    Route::prefix('ruangan')->group(function () {
        Route::get('/data', [AdminBMNController::class, 'ruangan']);
        Route::get('/json', [AdminBMNController::class, 'jsonRuangan']);
        Route::post('/save', [AdminBMNController::class, 'saveRuangan']);
        Route::delete('/delete/{id}', [AdminBMNController::class, 'deleteRuangan']);
    });
});

// =================== Auth: Pegawai ===================
Route::prefix('pemeliharaan-pegawai')->middleware('auth:pegawai')->group(function () {
    Route::get('/', [PemeliharaanController::class, 'index_pegawai']);
    Route::get('/pengajuan', [PemeliharaanController::class, 'pengajuan_pegawai']);
    Route::post('/store-pengajuan', [PemeliharaanController::class, 'store_pengajuan_pegawai']);
});

// =================== Auth: Administrator (Pemeliharaan) ===================
Route::prefix('pemeliharaan-admin')->middleware('auth:administrator')->group(function () {
    Route::get('/', [PemeliharaanController::class, 'index_admin']);
    Route::get('/pengajuan', [PemeliharaanController::class, 'pengajuan_admin']);
    Route::post('/store-pengajuan', [PemeliharaanController::class, 'store_pengajuan_admin']);
    Route::post('/pph-setujui', [PemeliharaanController::class, 'pphSetujuiPengajuan']);
    Route::post('/tolak-pengajuan-pegawai', [PemeliharaanController::class, 'tolak_pengajuan_pegawai']);
    Route::get('/buat-pesanan', [PemeliharaanController::class, 'buat_pesanan']);
    Route::post('/store-pesanan', [PemeliharaanController::class, 'store_pesanan']);
    Route::post('/ppk-setujui-pesanan/{nomor_surat_pesanan}', [PemeliharaanController::class, 'ppkSetujuiPesanan'])
        ->where('nomor_surat_pesanan', '.*');
    Route::post('/ppg-konfirmasi-pengambilan/{nomor_surat_pesanan}', [PemeliharaanController::class, 'ppgKonfirmasiPengambilan'])
        ->where('nomor_surat_pesanan', '.*');

    Route::post('/kirim-penawaran', [PemeliharaanController::class, 'kirimPenawaranPP']);
    Route::post('/setujui-penawaran', [PemeliharaanController::class, 'setujuiPenawaranPP']);
    Route::post('/tolak-penawaran', [PemeliharaanController::class, 'tolakPenawaranPP']);

    Route::post('/terima-bmn', [PemeliharaanController::class, 'terima_bmn']);

    Route::get('/buat-bap', [PemeliharaanController::class, 'buatBAP']);
    Route::post('/kirim-bap', [PemeliharaanController::class, 'kirimBAP']);

    Route::post('/ppk-tolak-pengajuan-pembayaran', [PemeliharaanController::class, 'ppk_tolak_pengajuan_bayar']);
    Route::post('/bendahara-selesai', [PemeliharaanController::class, 'bendaharaSelesai']);
    Route::post('/bendahara-tolak', [PemeliharaanController::class, 'bendahara_tolak_pengajuan_bayar']);
});

// =================== Auth: Penyedia ===================
Route::prefix('penyedia')->group(function () {
    // Login & Logout
    Route::view('/login', 'user.pemeliharaan.penyedia.login')->name('penyedia.login')->middleware('guest:penyedia');
    Route::post('/login', [LoginController::class, 'login_penyedia']);
    Route::post('/logout', [LoginController::class, 'logout_penyedia'])->name('penyedia.logout');

    // Hanya untuk penyedia yang login
    Route::middleware('auth:penyedia')->group(function () {
        Route::get('/', [PemeliharaanController::class, 'index_penyedia']);
        Route::post('/terima-pesanan/{pesanan}', [PemeliharaanController::class, 'penyedia_terima_pesanan'])
            ->where('pesanan', '.*');

        Route::post('/tolak-pesanan/{pesanan}', [PemeliharaanController::class, 'penyedia_tolak'])
            ->where('pesanan', '.*');
        Route::post('/tawarkan-harga', [PemeliharaanController::class, 'penyediaTawarkanHarga']);
        Route::post('/pengembalian-pemeliharaan/{pesanan}', [PemeliharaanController::class, 'kembalikanPemeliharaan'])
            ->where('pesanan', '.*');
        Route::get('/pengajuan-pembayaran/form', [PemeliharaanController::class, 'penyedia_pengajuan_pembayaran']);
        Route::post('/pengajuan-pembayaran/store', [PemeliharaanController::class, 'penyedia_simpan_pengajuan']);
        Route::get('/ttd-bap', [PemeliharaanController::class, 'ttdBAP']);
        Route::post('/ttd-store-bap', [PemeliharaanController::class, 'ttdStoreBAP']);
        Route::post('/setujui-penawaran', [PemeliharaanController::class, 'penyediaSetujuiPenawaran']);
        Route::get('/pengaturan', [PemeliharaanController::class, 'pengaturanPenyedia']);
        Route::post('/ganti-password', [PemeliharaanController::class, 'penyediaGantiPassword']);
        Route::get('/kop-surat/{id}', [PemeliharaanController::class, 'editKopPenyedia']);
        Route::post('/kop-surat/store', [PemeliharaanController::class, 'storeKopPenyedia']);
        Route::put('/kop-surat/update/{id}', [PemeliharaanController::class, 'updateKopPenyedia']);
        Route::delete('/kop-surat/delete/{id}', [PemeliharaanController::class, 'destroyKopPenyedia']);
    });
});

// penyewaan
Route::get('/notifPenyewa/{idPenyewa}', [SewaController::class, 'notifPenyewa'])->middleware('auth:akun_penyewa');
Route::post('/notifPenyewa/read/{id}', [SewaController::class, 'markAsReadPenyewa'])->middleware('auth:akun_penyewa');
Route::post('/mark-all-notif-penyewa/{idPenyewa}', [SewaController::class, 'markAllPenyewa'])->middleware('auth:akun_penyewa');

Route::middleware('auth:akun_penyewa')->group(function () {
    Route::get('/profile-penyewa', [MasukController::class, 'edit'])->name('profile.edit');
    Route::post('/profile-penyewa', [MasukController::class, 'update'])->name('profile.update');
});

//login sewa
Route::middleware('guest:akun_penyewa')->group(function () {
    Route::get('/login-penyewa', [MasukController::class, 'index'])->name('penyewa.login');
    Route::post('/login-penyewa', [MasukController::class, 'login']);

    Route::get('/register-penyewa', [MasukController::class, 'showRegisterForm'])->name('penyewa.register');
    Route::post('/register-penyewa', [MasukController::class, 'register']);
});

Route::get('/dashboard-penyewa', function () {
    return view('sewa.index', ['title' => 'Beranda']);
});

Route::get('/invoice/{id}/download', [SewaController::class, 'downloadInvoice'])->name('invoice.download');
Route::get('/download/bukti/{id}', [SewaController::class, 'downloadBukti'])->name('bukti.download');


Route::get('sewa/mess', [SewaController::class, 'mess'])->name('mess');
Route::get('sewa/cek-kamar-tersedia', [SewaController::class, 'cekKamarTersedia'])->name('cek.kamar.tersedia');

// sewa
Route::prefix('sewa')->middleware('auth:akun_penyewa')->group(function () {
    Route::post('/logout', [MasukController::class, 'logout']);
    Route::get('/form', [SewaController::class, 'showForm'])->name('sewa.form');
    Route::post('/form', [SewaController::class, 'storePengajuan'])->name('sewa.store');

    Route::get('/template', [SewaController::class, 'template_sewa'])->name('template');
    Route::get('/index', [SewaController::class, 'index_sewa'])->name('index');
    Route::post('/pengajuan/submit', [SewaController::class, 'submitPengajuan']);

    Route::get('/pesanan-saya', [SewaController::class, 'pesananSaya'])->name('pesanan.saya');

    Route::post('/pesanan/{id}/upload', [SewaController::class, 'uploadBukti'])->name('pesanan.upload');
    Route::delete('/pesanan/{id}/batalkan', [SewaController::class, 'batalkanPesanan'])->name('pesanan.batalkan');
    Route::post('/pesanan/{id}/konfirmasi-cash', [SewaController::class, 'konfirmasiCash'])->name('pesanan.konfirmasi.cash');
});
Route::get('/refund/bukti/{id}', [SewaController::class, 'lihatBukti'])->name('refund.lihat');
Route::get('/sewa/panduan', function () {
    return view('sewa.panduan');
})->name('panduan');


Route::get('/tes-wa', [AdminBMNController::class, 'tes_wa']);

// route admin
//penyewaan BMN admin
Route::put('/fasilitas/update/{id}', [AdminBMNController::class, 'updateFasilitas']);
Route::delete('/fasilitas/delete/{id}', [AdminBMNController::class, 'destroyFasilitas']);

Route::get('/penyewaan_aset/rekap-sewa', [AdminBMNController::class, 'rekap_sewa'])->name('penyewaan_aset.rekapitulasi')->middleware('auth:administrator');
Route::get('/penyewaan_aset/rekap-sewa/pdf', [AdminBMNController::class, 'rekap_sewa_cetakPDF'])->name('penyewaan_aset.rekapitulasi.pdf')->middleware('auth:administrator');
Route::get('/penyewaan_aset/rekap-kamar', [AdminBMNController::class, 'rekap_kamar'])->name('kamar.rekapitulasi')->middleware('auth:administrator');
Route::get('/penyewaan_aset/rekap-kamar/pdf', [AdminBMNController::class, 'rekap_kamar_cetakPDF'])->name('kamar.rekapitulasi.pdf')->middleware('auth:administrator');
Route::get('/penyewaan_aset/rekap-histori-kamar', [AdminBMNController::class, 'rekap_histori_kamar'])->name('histori_kamar.rekapitulasi')->middleware('auth:administrator');
Route::get('/penyewaan_aset/rekap-histori-kamar/pdf', [AdminBMNController::class, 'rekap_histori_kamar_cetakPDF'])->name('histori_kamar.rekapitulasi.pdf')->middleware('auth:administrator');

Route::get('/penyewaan_aset/{status}', [AdminBMNController::class, 'indexPenyewaan'])
    ->name('penyewaan_aset')
    ->middleware('auth:administrator');

Route::post('/penyewaan-aset/setujui-bendahara/{id}', [AdminBMNController::class, 'setujuiBendahara'])
    ->name('penyewaan.setujui_bendahara')
    ->middleware('auth:administrator');
Route::post('/penyewaan-aset/setujui-petugas/{id}', [AdminBMNController::class, 'setujuiPetugas'])
    ->name('penyewaan.setujui_petugas')
    ->middleware('auth:administrator');

Route::post('/penyewaan_aset/tolak/{id}', [AdminBMNController::class, 'tolak'])
    ->name('penyewaan.tolak')
    ->middleware('auth:administrator');

Route::get('/penyewaan_aset-getBuktiPembayaran/{filename}', [AdminBMNController::class, 'getBuktiPembayaran']);
Route::get('/penyewaan_aset-getInvoice/{filename}', [AdminBMNController::class, 'getInvoice']); // atau auth lain sesuai kebutuhan
Route::post('/penyewaan_aset/batalkan-refund/{id}', [AdminBMNController::class, 'batalkan_refund'])->name('penyewaan.batalkan.refund');


Route::post('/kamar/store', [AdminBMNController::class, 'storeKamar'])->name('kamar.store');
Route::post('/kategori/store', [AdminBMNController::class, 'storeKategori'])->name('kategori.store');
Route::post('/fasilitas/store', [AdminBMNController::class, 'storeFasilitas'])->name('fasilitas.store');
Route::get('/kamar/by-kategori/{id}', [AdminBMNController::class, 'getKamarByKategori']);
Route::post('/kamar/update/{id}', [AdminBMNController::class, 'updateKamar'])->name('kamar.update');
Route::get('/kamar/detail/{id}', [AdminBMNController::class, 'getDetailKamar']);
Route::put('/kategori/update/{id}', [AdminBMNController::class, 'updateKategori']);
Route::delete('/kategori/delete/{id}', [AdminBMNController::class, 'destroyKategori']);


Route::post('/admin/ajukan-sewa', [AdminBMNController::class, 'ajukanSewa'])->name('admin.ajukan.sewa')->middleware('auth:administrator');
Route::post('/pnbp/pengajuan', [AdminBMNController::class, 'pengajuanPNBP'])->name('pnbp.pengajuan');
Route::post('/pnbp/{id}/setujui', [AdminBMNController::class, 'setujuiPNBP'])->name('pnbp.setujui');
Route::post('/pnbp/{id}/tolak', [AdminBMNController::class, 'tolakPNBP'])->name('pnbp.tolak');
Route::post('/pnbp/{id}/isi-bukti', [AdminBMNController::class, 'isiBuktiPNBP'])->name('pnbp.isi_bukti');
Route::get('/pnbp/lihat-bukti/{filename}', [AdminBMNController::class, 'lihatBuktiPNBP'])->name('pnbp.lihat_bukti');
Route::post('/penyewaan/{kode_pemesanan}/edit-upgrade',
    [AdminBMNController::class, 'editUpgrade'])
    ->name('penyewaan.edit.upgrade')
    ->middleware('auth:administrator');

// get ~ admin
Route::get('/aneh', [AnehController::class, 'index']);

Route::get('/check-upload-limits', function () {
    echo 'upload_max_filesize: ' . ini_get('upload_max_filesize') . '<br>';
    echo 'post_max_size: ' . ini_get('post_max_size');
});
Route::get('/check-phpinfo', function () {
    phpinfo();
});
Route::get('/pdfprint', [LoginController::class, 'pdf'])->name('user');
Route::get('/administrator', [LoginController::class, 'login'])->name('administrator.login')->middleware('guest:administrator');
Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard')->middleware('auth:administrator');

// post ~ admin
Route::post('/verifikasi', [LoginController::class, 'authenticate']);
Route::post('/logout-admin', [LoginController::class, 'logout']);


//  route perjadin kegiatanBMNatan ~ admin

//HKT
Route::get('/kegiatan-HKT/{status}', [AdminKegiatanController::class, 'HKTIndex'])->name('HKT-kegiatan')->middleware('auth:administrator');
Route::get('/kegiatan-HKT/detail/{id}', [AdminKegiatanController::class, 'detail_kegiatan_HKT'])->name('detail-HKT-kegiatan')->middleware('auth:administrator');
Route::get('/kegiatan-HKT/surtug/{id}', [AdminKegiatanController::class, 'surtug_kegiatan_HKT'])->name('surtug-HKT-kegiatan')->middleware('auth:administrator');
Route::get('/kegiatan-HKT/surtug/detail/{id}', [AdminKegiatanController::class, 'detail_surtug_kegiatan_HKT'])->name('surtug-detail-HKT-kegiatan')->middleware('auth:administrator');
Route::get('/kegiatan-HKT/surtug/preview/{id}', [AdminKegiatanController::class, 'ConvertSurtug'])->middleware('auth:administrator');
Route::get('/kegiatan-HKT/surtug/edit/{id}', [AdminKegiatanController::class, 'detail_surtug_kegiatan_HKT_edit'])->name('surtug-edit')->middleware('auth:administrator');
Route::get('/kegiatan-getDokumen/{filename}', [AdminKegiatanController::class, 'AdmingetDokumen'])->name('kegiatan-getDokumen')->middleware('auth:administrator');


Route::post('/cu_kegiatan_HKT', [AdminKegiatanController::class, 'storeSurtug'])->middleware('auth:administrator');
Route::post('/c_kegiatan_HKT', [AdminKegiatanController::class, 'storeSurtugPDF'])->middleware('auth:administrator');
Route::post('/u_kegiatan_HKT', [AdminKegiatanController::class, 'UploadSurtug'])->middleware('auth:administrator');
Route::post('/e_kegiatan_HKT', [AdminKegiatanController::class, 'EditSurtug'])->middleware('auth:administrator');
Route::post('/t_kegiatan_HKT', [AdminKegiatanController::class, 'TolakKegiatan'])->middleware('auth:administrator');
Route::post('/u_dok_kegiatan_HKT', [AdminKegiatanController::class, 'UpdateSurtug'])->middleware('auth:administrator');

// RPD KEGIATAN
Route::get('/kegiatan-bendahara/rpd/{id}', [AdminKegiatanController::class, 'CetakRPD'])->middleware('auth:administrator');
Route::get('/kegiatan-bendahara/rpd-kat/{id}/{kategori}', [AdminKegiatanController::class, 'CetakRPDKat'])->middleware('auth:administrator');


// get
Route::get('/kegiatan-mobilitas/{status}', [AdminKegiatanController::class, 'index'])->name('mobilitas')->middleware('auth:administrator');
Route::get('/kegiatan-assets/{status}', [AdminKegiatanController::class, 'assetIndex'])->name('sapras')->middleware('auth:administrator');
Route::get('/kegiatan-keuangan/{status}', [AdminKegiatanController::class, 'keuanganIndex'])->name('keuangan-kegiatan')->middleware('auth:administrator');
Route::get('/kegiatan-bendahara/{status}', [AdminKegiatanController::class, 'bendaharaIndex'])->name('bendahara-kegiatan')->middleware('auth:administrator');
Route::get('/kegiatan-mobilitas/detail/{id}', [AdminKegiatanController::class, 'detail_mobilitas'])->name('detail-mobilitas-kegiatan')->middleware('auth:administrator');
Route::get('/detail-sapras/{id}', [AdminKegiatanController::class, 'detail_sapras'])->middleware('auth:administrator');
Route::get('/detail-keuangan/{id}', [AdminKegiatanController::class, 'detail_keuangan'])->name('detail_keuangan')->middleware('auth:administrator');
Route::get('/detail-bendahara/{id}', [AdminKegiatanController::class, 'detail_bendahara'])->name('detail_bendahara')->middleware('auth:administrator');

Route::get('/adminkegiatan/getDokumen/{filename}', [AdminKegiatanController::class, 'AdmingetDokumen'])->middleware('auth:administrator');

Route::delete('/h_fasilitas_kegiatan_admin/{HFasilitasKegiatan:id}/{admin}', [AdminKegiatanController::class, 'destroyFasilitasKegiatan'])->middleware('auth:administrator');
Route::delete('/h_mobilitas/kegiatan/{id}', [AdminKegiatanController::class, 'deleteMobilitas'])->middleware('auth:administrator');


//Fasilitas Keuangan
Route::post('/c_fasilitasdetail_keuangan', [AdminKegiatanController::class, 'storeFasilitasDetailKeuangan'])->middleware('auth:administrator');


// post
Route::post('/cu_kegiatanmobilitas', [AdminKegiatanController::class, 'store'])->middleware('auth:administrator');
Route::post('/c_a_admin_mobilitas', [AdminKegiatanController::class, 'storeMobilitas'])->middleware('auth:administrator');
Route::post('/cu_kegiatan_keuangan', [AdminKegiatanController::class, 'storeKeuangan'])->middleware('auth:administrator');
Route::post('/cu_kegiatan_bendahara', [AdminKegiatanController::class, 'storeBendahara'])->name('cu_kegiatan_bendahara')->middleware('auth:administrator');

// put
Route::post('/up_peminjaman_sapras', [AdminKegiatanController::class, 'updateSapras'])->middleware('auth:administrator');

// route perjalanan dinas ~admin
Route::get('/bmn_mobilitas_only', [AdminPerjadinController::class, 'showBmnMobilitasOnly'])->name('bmn_mobilitas_only');
Route::post('/bmn_mobilitas_only/store', [AdminPerjadinController::class, 'storeMobilitasOnly'])->name('store_mobilitas_only');

Route::get('/perjadin-mobilitas/{status}', [AdminPerjadinController::class, 'index'])->name('mobilitas-perjadin')->middleware('auth:administrator');
Route::get('/perjadin-mobilitas/edit/{id}', [AdminPerjadinController::class, 'edit_mobilitas'])->name('edit-mobilitas-perjadin')->middleware('auth:administrator');
Route::get('/perjadin-mobilitas/detail/{id}', [AdminPerjadinController::class, 'detail_mobilitas'])->name('detail-mobilitas-perjadin')->middleware('auth:administrator');
Route::get('/perjadin-mobilitas/detail_mobilitas/{id}', [AdminPerjadinController::class, 'detail_perjadin_BMN'])->name('detail-perjadin-BMN')->middleware('auth:administrator');
Route::get('/perjadin-keuangan/{status}', [AdminPerjadinController::class, 'keuanganIndex'])->name('keuangan-perjadin')->middleware('auth:administrator');
Route::get('/perjadin-keuangan/detail/{id}', [AdminPerjadinController::class, 'detail_perjadin_keuangan'])->name('detail-perjadin-keuangan')->middleware('auth:administrator');
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
Route::get('/perjadin-bendahara/rpd/{id}', [AdminPerjadinController::class, 'CetakRPD'])->middleware('auth:administrator');
// POST
Route::post('/update_perjadinmobilitas', [AdminPerjadinController::class, 'updateKendaraan'])->middleware('auth:administrator');
Route::post('/cu_perjadinmobilitas', [AdminPerjadinController::class, 'store'])->middleware('auth:administrator');
Route::post('/c_tambahmobilitas', [AdminPerjadinController::class, 'storeMobilitas'])->middleware('auth:administrator');
Route::delete('/h_mobilitas/{id}', [AdminPerjadinController::class, 'deleteMobilitas'])->middleware('auth:administrator');


Route::delete('/h_fasilitas_keu/{id}', [AdminPerjadinController::class, 'deleteFasilitasiKeu'])->middleware('auth:administrator');
Route::post('/cu_perjadin_keuangan', [AdminPerjadinController::class, 'storeKeuangan'])->middleware('auth:administrator');
Route::post('/cu_perjadin_bendahara', [AdminPerjadinController::class, 'storeBendahara'])->middleware('auth:administrator');

//HKT
Route::post('/generate-laporan-HKT', [AdminPerjadinController::class, 'ganerateLaporanHKT'])->middleware('auth:administrator');
Route::get('/laporan-HKT/{mulai}/{sampai}', [AdminPerjadinController::class, 'laporanHKT'])->name('laporan-HKT')->middleware('auth:administrator');
// Rute di web.php
Route::get('/laporanHKT/data/{mulai}/{sampai}', [AdminPerjadinController::class, 'getAllDataHKT'])->middleware('auth:administrator');

Route::post('/cu_perjadin_HKT', [AdminPerjadinController::class, 'storeSurtug'])->middleware('auth:administrator');
Route::post('/c_perjadin_HKT', [AdminPerjadinController::class, 'storeSurtugPDF'])->middleware('auth:administrator');
Route::post('/u_dok_perjadin_HKT', [AdminPerjadinController::class, 'UpdateSurtug'])->middleware('auth:administrator');
Route::post('/u_tte_perjadin_HKT', [AdminPerjadinController::class, 'UploadTTE'])->middleware('auth:administrator');
Route::post('/u_perjadin_HKT', [AdminPerjadinController::class, 'UploadSurtug'])->middleware('auth:administrator');
Route::post('/e_perjadin_HKT', [AdminPerjadinController::class, 'EditSurtug'])->middleware('auth:administrator');
Route::post('/t_perjadin_HKT', [AdminPerjadinController::class, 'TolakPerjadin'])->middleware('auth:administrator');

Route::get('/akses', [AksesController::class, 'index'])->name('login')->middleware('guest');
Route::post('/akses', [AksesController::class, 'authenticate']);
Route::post('/logout', [AksesController::class, 'logout']);
Route::post('/c_fasilitas_bendahara', [AdminPerjadinController::class, 'storeFasilitasBendahara'])->middleware('auth:administrator');
Route::post('/c_fasilitasDetail_bendahara', [AdminPerjadinController::class, 'storeFasilitasDetailBendahara'])->middleware('auth:administrator');
Route::post('/c_fasilitasDetail_keuangan', [AdminPerjadinController::class, 'storeFasilitasDetailKeuangan'])->middleware('auth:administrator');
Route::post('/c_fasilitas_BMN', [AdminPerjadinController::class, 'storeFasilitasBMN'])->middleware('auth:administrator');
Route::post('/c_fasilitasDetail_BMN', [AdminPerjadinController::class, 'storeFasilitasDetailBMN'])->middleware('auth:administrator');

//sppd
Route::get('/cetakSPPD/{id}', [AdminPerjadinController::class, 'cetakSPPD'])->middleware('auth:administrator');
Route::post('/update_data_sppd', [AdminPerjadinController::class, 'updateDataSPPD'])->middleware('auth:administrator');

// sppd kegiatan
Route::get('/cetakSPPD/kegiatan/{id}/{idLaporan}', [AdminKegiatanController::class, 'cetakSPPD'])->middleware('auth:administrator');
Route::post('/update_data_sppd_kegiatan', [AdminKegiatanController::class, 'updateDataSPPD'])->middleware('auth:administrator');

// Notifikasi Admin
Route::post('/notifAdmin/read/{id}', [AdminController::class, 'markAsReadAdmin']);
Route::get('/notifAdmin/{role}', [AdminController::class, 'notifAdmin'])->middleware('auth:administrator');
Route::post('/mark-all-notif-admin/{role}', [AdminController::class, 'markAllAdmin']);

// Notifikasi User
Route::post('/notifUser/read/{id}', [PerjadinController::class, 'markAsReadUser']);
Route::get('/notifUser/{idUser}', [PerjadinController::class, 'notifUser'])->middleware('auth:pegawai');
Route::post('/mark-all-notif-user/{idUser}', [PerjadinController::class, 'markAllUser']);

//BMN
Route::get('/laporan-BMN/{mulai}/{sampai}', [AdminPerjadinController::class, 'laporanBMN'])->name('laporan-BMN')->middleware('auth:administrator');
Route::post('/generate-laporan-BMN', [AdminPerjadinController::class, 'ganerateLaporanBMN'])->middleware('auth:administrator');
// Rute di web.php
Route::get('/laporanBMN/data/{mulai}/{sampai}', [AdminPerjadinController::class, 'getAllDataBMN'])->middleware('auth:administrator');

Route::post('/generate-laporan-BMNv2', [AdminPerjadinController::class, 'ganerateLaporanBMNv2'])->middleware('auth:administrator');
Route::get('/laporan-BMNv2/{mulai}/{sampai}', [AdminPerjadinController::class, 'laporanBMNv2'])->name('laporan-BMNv2')->middleware('auth:administrator');
// Rute di web.php
Route::get('/laporanBMNv2/data/{mulai}/{sampai}', [AdminPerjadinController::class, 'getAllDataBMNv2'])->middleware('auth:administrator');


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
Route::post('/update_penyedia/{id}', [AdminBMNController::class, 'updatePenyedia'])->middleware('auth:administrator');
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
Route::get('/getDokumenService/{filename}', [AdminBMNController::class, 'getDokumenService'])->middleware('auth:administrator');


// aksi
Route::post('/action_peminjaman/{id}', [AdminBMNController::class, 'updatePeminjaman'])->middleware('auth:administrator');

// delete
Route::post('/d_komponen_service_asset/{id}', [AdminBMNController::class, 'destroykomponenServiceAsset'])->middleware('auth:administrator');

Route::get('/', function () {
    $userId = auth('pegawai')->user()->id;
    $versiId = session('versi');

    // === Kartu 1: Perjadin Kegiatan (Program Kegiatan) ===
    $kegiatanTotal = DB::table('data_perjadinkegiatans')
        ->where(function ($query) use ($userId) {
            $query->where('data_perjadinkegiatans.id_pengaju', $userId)
                ->orWhereExists(function ($q) use ($userId) {
                    $q->select(DB::raw(1))
                      ->from('perangkat_acaras')
                      ->whereColumn('perangkat_acaras.data_perjadin_kegiatan', 'data_perjadinkegiatans.id')
                      ->where('perangkat_acaras.pegawai_id', $userId);
                });
        })
        ->where('data_perjadinkegiatans.versi_id', $versiId)
        ->count();
    $kegiatanDraf = DB::table('data_perjadinkegiatans')
        ->where('data_perjadinkegiatans.id_pengaju', $userId)
        ->where('data_perjadinkegiatans.versi_id', $versiId)
        ->where('data_perjadinkegiatans.status_pengajuan', 'Draf-pengajuan')
        ->count();
    $kegiatanRevisi = DB::table('data_perjadinkegiatans')
        ->where(function ($query) use ($userId) {
            $query->where('data_perjadinkegiatans.id_pengaju', $userId)
                ->orWhereExists(function ($q) use ($userId) {
                    $q->select(DB::raw(1))
                      ->from('perangkat_acaras')
                      ->whereColumn('perangkat_acaras.data_perjadin_kegiatan', 'data_perjadinkegiatans.id')
                      ->where('perangkat_acaras.pegawai_id', $userId);
                });
        })
        ->where('data_perjadinkegiatans.versi_id', $versiId)
        ->where('data_perjadinkegiatans.versi_id', $versiId)
        ->where('data_perjadinkegiatans.status_pengajuan', 'revisi')
        ->count();
    $kegiatanProses = DB::table('data_perjadinkegiatans')
        ->where(function ($query) use ($userId) {
            $query->where('data_perjadinkegiatans.id_pengaju', $userId)
                ->orWhereExists(function ($q) use ($userId) {
                    $q->select(DB::raw(1))
                      ->from('perangkat_acaras')
                      ->whereColumn('perangkat_acaras.data_perjadin_kegiatan', 'data_perjadinkegiatans.id')
                      ->where('perangkat_acaras.pegawai_id', $userId);
                });
        })
        ->where('data_perjadinkegiatans.versi_id', $versiId)
        ->whereIn('data_perjadinkegiatans.status_pengajuan', ['pengajuan', 'proses', 'pelaksanaan', 'pengecekan', 'disetujui'])
        ->count();
    $kegiatanPelaporan = DB::table('data_perjadinkegiatans')
        ->where(function ($query) use ($userId) {
            $query->where('data_perjadinkegiatans.id_pengaju', $userId)
                ->orWhereExists(function ($q) use ($userId) {
                    $q->select(DB::raw(1))
                      ->from('perangkat_acaras')
                      ->whereColumn('perangkat_acaras.data_perjadin_kegiatan', 'data_perjadinkegiatans.id')
                      ->where('perangkat_acaras.pegawai_id', $userId);
                });
        })
        ->where('data_perjadinkegiatans.versi_id', $versiId)
        ->where('data_perjadinkegiatans.status_pengajuan', 'pelaporan')
        ->count();
    $kegiatanDitolak = DB::table('data_perjadinkegiatans')
        ->where(function ($query) use ($userId) {
            $query->where('data_perjadinkegiatans.id_pengaju', $userId)
                ->orWhereExists(function ($q) use ($userId) {
                    $q->select(DB::raw(1))
                      ->from('perangkat_acaras')
                      ->whereColumn('perangkat_acaras.data_perjadin_kegiatan', 'data_perjadinkegiatans.id')
                      ->where('perangkat_acaras.pegawai_id', $userId);
                });
        })
        ->where('data_perjadinkegiatans.versi_id', $versiId)
        ->where('data_perjadinkegiatans.status_pengajuan', 'ditolak')
        ->count();
    $kegiatanSelesai = DB::table('data_perjadinkegiatans')
        ->where(function ($query) use ($userId) {
            $query->where('data_perjadinkegiatans.id_pengaju', $userId)
                ->orWhereExists(function ($q) use ($userId) {
                    $q->select(DB::raw(1))
                      ->from('perangkat_acaras')
                      ->whereColumn('perangkat_acaras.data_perjadin_kegiatan', 'data_perjadinkegiatans.id')
                      ->where('perangkat_acaras.pegawai_id', $userId);
                });
        })
        ->where('data_perjadinkegiatans.versi_id', $versiId)
        ->where('data_perjadinkegiatans.status_pengajuan', 'selesai')
        ->count();

    // === Kartu 2: Perjalanan Dinas ===
    $perjadinTotal = DB::table('info_perjadinlangsungs')
        ->join('data_perjadinlangsungs', 'info_perjadinlangsungs.id', '=', 'data_perjadinlangsungs.info_perjadinlangsung')
        ->where('data_perjadinlangsungs.pegawai_id', $userId)
        ->where('info_perjadinlangsungs.versi_id', $versiId)
        ->count();
    $perjadinDraf = DB::table('info_perjadinlangsungs')
        ->where('info_perjadinlangsungs.id_pengaju', $userId)
        ->where('info_perjadinlangsungs.status_pengajuan', 'Draf-pengajuan')
        ->where('versi_id', $versiId)
        ->count();
    $perjadinRevisi = DB::table('info_perjadinlangsungs')
        ->join('data_perjadinlangsungs', 'info_perjadinlangsungs.id', '=', 'data_perjadinlangsungs.info_perjadinlangsung')
        ->where('data_perjadinlangsungs.pegawai_id', $userId)
        ->where('info_perjadinlangsungs.status_pengajuan', 'revisi')
        ->where('versi_id', $versiId)
        ->count();
    $perjadinProses = DB::table('info_perjadinlangsungs')
        ->join('data_perjadinlangsungs', 'info_perjadinlangsungs.id', '=', 'data_perjadinlangsungs.info_perjadinlangsung')
        ->where('data_perjadinlangsungs.pegawai_id', $userId)
        ->whereIn('info_perjadinlangsungs.status_pengajuan', ['pengajuan', 'proses', 'pelaksanaan', 'pengecekan', 'disetujui'])
        ->where('versi_id', $versiId)
        ->count();
    $perjadinPelaporan = DB::table('info_perjadinlangsungs')
        ->join('data_perjadinlangsungs', 'info_perjadinlangsungs.id', '=', 'data_perjadinlangsungs.info_perjadinlangsung')
        ->where('data_perjadinlangsungs.pegawai_id', $userId)
        ->where('info_perjadinlangsungs.status_pengajuan', 'pelaporan')
        ->where('versi_id', $versiId)
        ->count();
    $perjadinDitolak = DB::table('info_perjadinlangsungs')
        ->join('data_perjadinlangsungs', 'info_perjadinlangsungs.id', '=', 'data_perjadinlangsungs.info_perjadinlangsung')
        ->where('data_perjadinlangsungs.pegawai_id', $userId)
        ->where('info_perjadinlangsungs.status_pengajuan', 'ditolak')
        ->where('versi_id', $versiId)
        ->count();
    $perjadinSelesai = DB::table('info_perjadinlangsungs')
        ->join('data_perjadinlangsungs', 'info_perjadinlangsungs.id', '=', 'data_perjadinlangsungs.info_perjadinlangsung')
        ->where('data_perjadinlangsungs.pegawai_id', $userId)
        ->where('info_perjadinlangsungs.status_pengajuan', 'selesai')
        ->where('versi_id', $versiId)
        ->count();

    // === Kartu 3: Pemeliharaan ===
    $pemeliharaanTotal = DB::table('pemeliharaans')
        ->join('karyawans', 'pemeliharaans.id_karyawan', '=', 'karyawans.id_karyawan')
        ->join('pegawais', 'karyawans.NIP_NIK', '=', 'pegawais.NIP_NIK')
        ->where('pegawais.id', $userId)
        ->count();
    $pemeliharaanProses = DB::table('pemeliharaans')
        ->join('karyawans', 'pemeliharaans.id_karyawan', '=', 'karyawans.id_karyawan')
        ->join('pegawais', 'karyawans.NIP_NIK', '=', 'pegawais.NIP_NIK')
        ->where('pegawais.id', $userId)
        ->whereNotIn('pemeliharaans.id_ref_status_pemeliharaan', [8, 9])
        ->count();
    $pemeliharaanSelesai = DB::table('pemeliharaans')
        ->join('karyawans', 'pemeliharaans.id_karyawan', '=', 'karyawans.id_karyawan')
        ->join('pegawais', 'karyawans.NIP_NIK', '=', 'pegawais.NIP_NIK')
        ->where('pegawais.id', $userId)
        ->whereIn('pemeliharaans.id_ref_status_pemeliharaan', [8, 9])
        ->count();

    // === Riwayat Terbaru (Gabungan) ===
    $recentPerjadin = DB::table('info_perjadinlangsungs')
        ->join('data_perjadinlangsungs', 'info_perjadinlangsungs.id', '=', 'data_perjadinlangsungs.info_perjadinlangsung')
        ->where('data_perjadinlangsungs.pegawai_id', $userId)
        ->where('info_perjadinlangsungs.versi_id', $versiId)
        ->select(
            'info_perjadinlangsungs.nama_kegiatan as nama',
            'info_perjadinlangsungs.status_pengajuan as status',
            'info_perjadinlangsungs.updated_at',
            DB::raw("'Perjalanan Dinas' as kategori")
        );

    $recentKegiatan = DB::table('data_perjadinkegiatans')
        ->where('data_perjadinkegiatans.id_pengaju', $userId)
        ->where('data_perjadinkegiatans.versi_id', $versiId)
        ->select(
            'data_perjadinkegiatans.nama_kegiatan as nama',
            'data_perjadinkegiatans.status_pengajuan as status',
            'data_perjadinkegiatans.updated_at',
            DB::raw("'Perjadin Kegiatan' as kategori")
        );

    $recentPemeliharaan = DB::table('pemeliharaans')
        ->join('karyawans', 'pemeliharaans.id_karyawan', '=', 'karyawans.id_karyawan')
        ->join('pegawais', 'karyawans.NIP_NIK', '=', 'pegawais.NIP_NIK')
        ->leftJoin('ref_status_pemeliharaans', 'pemeliharaans.id_ref_status_pemeliharaan', '=', 'ref_status_pemeliharaans.id_ref_status_pemeliharaan')
        ->where('pegawais.id', $userId)
        ->select(
            DB::raw("COALESCE(pemeliharaans.keterangan, 'Pengajuan Pemeliharaan BMN') as nama"),
            'ref_status_pemeliharaans.deskripsi_status as status',
            'pemeliharaans.updated_at',
            DB::raw("'Pemeliharaan' as kategori")
        );

    $recentActivity = $recentPerjadin->unionAll($recentKegiatan)->unionAll($recentPemeliharaan)
        ->orderBy('updated_at', 'desc')
        ->limit(20)
        ->get();

    return view('user.beranda', [
        'title' => 'Dashboard',
        'active' => 'index',
        'kegiatanTotal' => $kegiatanTotal,
        'kegiatanDraf' => $kegiatanDraf,
        'kegiatanRevisi' => $kegiatanRevisi,
        'kegiatanProses' => $kegiatanProses,
        'kegiatanPelaporan' => $kegiatanPelaporan,
        'kegiatanDitolak' => $kegiatanDitolak,
        'kegiatanSelesai' => $kegiatanSelesai,
        'perjadinTotal' => $perjadinTotal,
        'perjadinDraf' => $perjadinDraf,
        'perjadinRevisi' => $perjadinRevisi,
        'perjadinProses' => $perjadinProses,
        'perjadinPelaporan' => $perjadinPelaporan,
        'perjadinDitolak' => $perjadinDitolak,
        'perjadinSelesai' => $perjadinSelesai,
        'pemeliharaanTotal' => $pemeliharaanTotal,
        'pemeliharaanProses' => $pemeliharaanProses,
        'pemeliharaanSelesai' => $pemeliharaanSelesai,
        'recentActivity' => $recentActivity,
    ]);
})->middleware('auth:pegawai');

// profile
Route::get('/profile', [AksesController::class, 'profile'])->name('profile')->middleware('auth:pegawai');
Route::get('/profile/ubah-password', [AksesController::class, 'ubah'])->middleware('auth:pegawai');
Route::post('/ubah-password', [AksesController::class, 'ubahPassword'])->middleware('auth:pegawai');

//Ajukan Ulang
Route::delete('/perjadin/{id}/delete', [PerjadinController::class, 'destroy'])->name('perjadin.delete')->middleware('auth:pegawai');
Route::get('/perjadin/Ajukan-Ulang/{id}', [PerjadinController::class, 'indexAjukan'])->middleware('auth:pegawai');
Route::post('/perjadin/ajukan', [PerjadinController::class, 'ajukanPerjadin'])->middleware('auth:pegawai');

// route perjadin biasa ~user

// get
Route::get('/perjadin/rpd/{id}', [PerjadinController::class, 'CetakRPDuser'])->middleware('auth:pegawai');
Route::get('/perjadin', [PerjadinController::class, 'index'])->middleware('auth:pegawai');
Route::get('/perjadin/getDokumen/{filename}', [PerjadinController::class, 'getDokumen'])->middleware('auth:pegawai');
Route::get('/perjadin/{id}', [PerjadinController::class, 'indexEdit'])->name('perjadin_edit')->middleware('auth:pegawai');
Route::get('perjadin_step_2/{id}', [PerjadinController::class, 'step2'])->name('perjadin_step_2')->middleware('auth:pegawai');
Route::get('/perjadin/riwayat/{status}', [PerjadinController::class, 'riwayat'])->name('riwayat')->middleware('auth:pegawai');
Route::get('/detail-perjadin/{id}', [PerjadinController::class, 'detail_perjadin'])->name('detail-perjadin')->middleware('auth:pegawai');
Route::get('/note-perjadin/{id}', [PerjadinController::class, 'note_perjadin'])->name('note-perjadin')->middleware('auth:pegawai');
Route::get('/note-perjadin-user/{id}', [PerjadinController::class, 'previewPerjadinUser'])->name('note-perjadin-user')->middleware('auth:pegawai');

// create
Route::post('/perjadin/edit', [PerjadinController::class, 'editPerjadin'])->middleware('auth:pegawai');
Route::post('/perjadin/store', [PerjadinController::class, 'store'])->middleware('auth:pegawai');
Route::post('/c_peserta', [PerjadinController::class, 'storePeserta'])->middleware('auth:pegawai');
Route::post('/c_peserta_kegiatan_many', [KegiatanController::class, 'storePesertaMany'])->middleware('auth:pegawai');
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
Route::get('/kegiatan/{id}', [KegiatanController::class, 'index2'])->middleware('auth:pegawai');
Route::get('/kegiatan/data/{id}', [KegiatanController::class, 'getKegiatanData'])->middleware('auth:pegawai');
Route::put('/kegiatan/batas-panitia/{id}', [KegiatanController::class, 'storeBatasPanitia'])->middleware('auth:pegawai');
// Route::get('/kegiatan/getDokumen/{filename}', [KegiatanController::class, 'getDokumen'])->middleware('auth:pegawai');
// Route::get('/kegiatan/getDokumen/{filename}', [KegiatanController::class, 'getDokumen'])->middleware('auth:pegawai');
Route::get('/kegiatan/getDokumen/{filename}', [KegiatanController::class, 'getDokumen'])->middleware('auth:pegawai');
Route::get('/kegiatan/getTemplateDokumen/{filename}', [KegiatanController::class, 'getTemplateDokumen'])->middleware('auth:pegawai');
Route::get('/note-penugasan-kegiatan/{id}', [KegiatanController::class, 'notePenugasanKegiatan'])->name('note-penugasan-kegiatan')->middleware('auth:pegawai');
Route::post('/note_penugasan', [KegiatanController::class, 'storeLaporanPenugasan'])->middleware('auth:pegawai');
Route::delete('/h_fasilitas_kegiatan/{HFasilitasKegiatan:id}', [KegiatanController::class, 'destroyFasilitasKegiatan'])->middleware('auth:pegawai');



Route::post('/store-fasilitas/{id}', [KegiatanController::class, 'storeFasilitasDetail'])->middleware('auth:pegawai');
Route::post('/c_fasilitas', [KegiatanController::class, 'storeFasilitas'])->middleware('auth:pegawai');

// Delete Kegiatan
Route::delete('/kegiatan/{id}/delete', [KegiatanController::class, 'destroy'])->name('kegiatan.delete')->middleware('auth:pegawai');

Route::get('/kegiatan_penugasan_step_2/{id}', [KegiatanController::class, 'KegiatanPenugasanStep2'])->name('kegiatan_penugasan_step_2')->middleware('auth:pegawai');
Route::get('/kegiatan_step_2/{id}', [KegiatanController::class, 'KegiatanStep2'])->name('kegiatan_step_2')->middleware('auth:pegawai');
Route::get('/kegiatan_step_3/{id}', [KegiatanController::class, 'KegiatanStep3'])->name('kegiatan_step_3')->middleware('auth:pegawai');
Route::get('/kegiatan_step_4/{id}', [KegiatanController::class, 'KegiatanStep4'])->name('kegiatan_step_4')->middleware('auth:pegawai');
Route::get('/kegiatan_step_5/{id}', [KegiatanController::class, 'KegiatanStep5'])->name('kegiatan_step_5')->middleware('auth:pegawai');
Route::get('/kegiatan_step_6/{id}', [KegiatanController::class, 'KegiatanStep6'])->name('kegiatan_step_6')->middleware('auth:pegawai');
Route::get('/kegiatan_step_7/{id}', [KegiatanController::class, 'KegiatanStep7'])->name('kegiatan_step_7')->middleware('auth:pegawai');
Route::get('/kegiatan/riwayat/{status}', [KegiatanController::class, 'riwayat'])->name('riwayat-kegiatan')->middleware('auth:pegawai');
Route::get('/detail-kegiatan/{id}', [KegiatanController::class, 'detail'])->name('detail')->middleware('auth:pegawai');
Route::get('/kegiatanAjukanUlang/{id}', [KegiatanController::class, 'indexAjukan'])->middleware('auth:pegawai');
Route::put('/detailKegiatanajukan/{id}', [KegiatanController::class, 'ajukanUlang'])->middleware('auth:pegawai');


// create

Route::post('/c_drafkegiatan/{id}', [KegiatanController::class, 'draftKegiatanAll'])->middleware('auth:pegawai');
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
Route::get('/kegiatan_edit_2/{id}', [KegiatanController::class, 'updateKegiatan'])->name('kegiatan_updtate')->middleware('auth:pegawai');

// put
Route::put('/draft_kegiatan_all/{id}', [KegiatanController::class, 'draftKegiatanAll'])->name('draft-kegiatan')->middleware('auth:pegawai');
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
Route::resource('admin', AdminController::class);
Route::resource('ref_admin_roles', RefAdminRoleController::class)->except(['show']);
// pegawai
Route::post('/admin-pegawai', [PegawaiController::class, 'save'])->name('admin-pegawai.store');
Route::put('/admin-pegawai/{id}', [PegawaiController::class, 'save'])->name('admin-pegawai.update');
Route::delete('/admin-pegawai/{id}', [PegawaiController::class, 'destroy'])->name('admin-pegawai.destroy');
Route::get('/admin-pegawai', [PegawaiController::class, 'index'])->name('admin-pegawai.index');
// non-pegawai
Route::resource('/admin-nonpegawai', NonPegawaiController::class,);

//Fasilitas
Route::get('/get-data-fasilitas', [AdminController::class, 'getFasilitas']);

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

//Monitoring Usulan
Route::get('/monitoring', [AdminOtherController::class, 'indexUsulanPerjadin'])
    ->middleware('auth:administrator')
    ->name('monitoring');

//Monitoring Keuangan
Route::get('/monitoring-keuangan', [AdminOtherController::class, 'monitoringKeuangan'])
    ->middleware('auth:administrator')
    ->name('monitoring-keuangan');

// GET JSON KEUANGAN
Route::get('/get-unique-programs-json', [AdminOtherController::class, 'getUniqueProgramsJson'])
    ->middleware('auth:administrator')
    ->name('unique-programs-json');

Route::get('/get-unique-kegiatans-json/{kode_satker}/{kode_program}', [AdminOtherController::class, 'getUniqueKegiatansJson'])
    ->middleware('auth:administrator')
    ->name('unique-kegiatans-json');

Route::get('/get-unique-outputs-json/{kode_satker}/{kode_program}/{kode_kegiatan}', [AdminOtherController::class, 'getUniqueOutputsJson'])
    ->middleware('auth:administrator')
    ->name('unique-outputs-json');

Route::get('/get-unique-suboutputs-json/{kode_satker}/{kode_program}/{kode_kegiatan}/{kode_output}', [AdminOtherController::class, 'getUniqueSubOutputsJson'])
    ->middleware('auth:administrator')
    ->name('unique-suboutputs-json');

Route::get('/get-unique-komponens-json/{kode_satker}/{kode_program}/{kode_kegiatan}/{kode_output}/{kode_sub_output}', [AdminOtherController::class, 'getUniqueKomponensJson'])
    ->middleware('auth:administrator')
    ->name('unique-komponens-json');

Route::get('/get-unique-subkomponens-json/{kode_satker}/{kode_program}/{kode_kegiatan}/{kode_output}/{kode_sub_output}/{kode_komponen}', [AdminOtherController::class, 'getUniqueSubKomponensJson'])
    ->middleware('auth:administrator')
    ->name('unique-subkomponens-json');

    
Route::get('/get-unique-akuns-semua-json/{kode_satker}/{kode_program}/{kode_kegiatan}/{kode_output}/{kode_sub_output}/{kode_komponen}/{kode_sub_komponen}', [AdminOtherController::class, 'getUniqueAkunsSemuaJson'])
->middleware('auth:administrator')
->name('unique-akuns-semua-json');

Route::get('/get-akuns-only-json/{id_akun}', [AdminOtherController::class, 'getUniqueAkunsOnlyJson'])
->middleware('auth:administrator')
->name('unique-akuns-only-json');

Route::get('/get-unique-akuns-json/{kode_satker}/{kode_program}', [AdminOtherController::class, 'getUniqueAkunsJson'])
    ->middleware('auth:administrator')
    ->name('unique-akuns-json');

//Monitoring Keuangan
Route::get('/monitoring-keuangan/detail/per-akun', [AdminOtherController::class, 'monitoringKeuanganPerAkun'])
    ->middleware('auth:administrator')
    ->name('monitoring-keuangan-per-akun');

Route::get('/monitoring-keuangan/semua', [AdminOtherController::class, 'monitoringKeuanganSemua'])
    ->middleware('auth:administrator')
    ->name('monitoring-keuangan-semua');

//Monitoring Keuangan Detail
Route::get('/monitoring-keuangan/detail/{id}', [AdminOtherController::class, 'monitoringKeuanganDetail'])
    ->middleware('auth:administrator')
    ->name('monitoring-keuangan-detail');

//
Route::get('/isi-penggunaan', [AdminOtherController::class, 'isiPenggunaan']);
Route::get('/generate-penggunaan', [AdminOtherController::class, 'generatePenggunaan']);

Route::get('/admin/other-perjadin/detail/{id}', [AdminOtherController::class, 'detailPerjadin'])->name('admin.other.detail-usulan');
Route::get('/admin/other-kegiatan/detail/{id}', [AdminOtherController::class, 'detailKegiatan'])->name('admin.other.detail-usulankegiatan');
Route::get('/usulanperjadin-AdmingetDokumen/{filename}', [AdminOtherController::class, 'AdmingetDokumen'])->middleware('auth:administrator');
Route::get('/usulankegiatan/getDokumenKegiatan/{filename}', [AdminOtherController::class, 'getDokumenKegiatan'])->middleware('auth:administrator');
   

//SBM
Route::get('/sbm', [AdminOtherController::class, 'indexSBM'])->name('sbm')->middleware('auth:administrator');
Route::get('/detail_sbm/{id}', [AdminOtherController::class, 'indexDetailSBM'])->middleware('auth:administrator');
Route::post('/c_sbm', [AdminOtherController::class, 'storeSBM'])->middleware('auth:administrator');
Route::post('/d_sbm/{id}', [AdminOtherController::class, 'destroySBM'])->middleware('auth:administrator');
Route::post('/detail_sbm/u_sbm', [AdminOtherController::class, 'updateSBM'])->middleware('auth:administrator');

// Ref Jenis Program
Route::get('/jenis_program', [AdminOtherController::class, 'indexJenisProgram'])->name('jenis_program')->middleware('auth:administrator');
Route::post('/c_jenisProgram', [AdminOtherController::class, 'storeJenisProgram'])->middleware('auth:administrator');
Route::post('/set_jenisProgram', [AdminOtherController::class, 'setJenisProgram'])->middleware('auth:administrator');
Route::post('/del_jenisProgram', [AdminOtherController::class, 'deleteJenisProgram'])->middleware('auth:administrator');

// Ref Fasilitas
Route::get('/ref_fasilitas', [AdminOtherController::class, 'indexRefFasilitas'])->name('ref_fasilitas')->middleware('auth:administrator');
Route::post('/c_refFasilitas', [AdminOtherController::class, 'storeRefFasilitas'])->middleware('auth:administrator');
Route::post('/set_refFasilitas', [AdminOtherController::class, 'setRefFasilitas'])->middleware('auth:administrator');
Route::post('/del_refFasilitas', [AdminOtherController::class, 'deleteRefFasilitas'])->middleware('auth:administrator');

// Ref Penandatangan
Route::get('/ref_penandatangan', [AdminOtherController::class, 'indexPenandatangan'])->name('ref_penandatangan')->middleware('auth:administrator');
Route::post('/c_penandatangan', [AdminOtherController::class, 'storePenandatangan'])->middleware('auth:administrator');
Route::post('/set_penandatangan', [AdminOtherController::class, 'setPenandatangan'])->middleware('auth:administrator');
Route::post('/del_penandatangan', [AdminOtherController::class, 'deletePenandatangan'])->middleware('auth:administrator');

// Ref Satuan
Route::get('/ref_satuan', [AdminOtherController::class, 'indexSatuan'])->name('ref_satuan')->middleware('auth:administrator');
Route::post('/c_satuan', [AdminOtherController::class, 'storeSatuan'])->middleware('auth:administrator');
Route::post('/set_satuan', [AdminOtherController::class, 'setSatuan'])->middleware('auth:administrator');
Route::post('/del_satuan', [AdminOtherController::class, 'deleteSatuan'])->middleware('auth:administrator');

// Ref Data Pajak
Route::get('/ref_data_pajak', [AdminOtherController::class, 'indexDataPajak'])->name('ref_data_pajak')->middleware('auth:administrator');
Route::post('/c_data_pajak', [AdminOtherController::class, 'storeDataPajak'])->middleware('auth:administrator');
Route::post('/set_data_pajak', [AdminOtherController::class, 'setDataPajak'])->middleware('auth:administrator');
Route::post('/del_data_pajak', [AdminOtherController::class, 'deleteDataPajak'])->middleware('auth:administrator');

// Ref Data Bank
Route::get('/ref_bank', [AdminOtherController::class, 'indexRefBank'])->name('ref_bank')->middleware('auth:administrator');
Route::post('/c_bank', [AdminOtherController::class, 'storeRefBank'])->middleware('auth:administrator');
Route::post('/del_bank', [AdminOtherController::class, 'deleteRefBank'])->middleware('auth:administrator');

// Ref Data Jabatan
Route::get('/ref_jabatan', [AdminOtherController::class, 'indexRefJabatan'])->name('ref_jabatan')->middleware('auth:administrator');
Route::post('/c_jabatan', [AdminOtherController::class, 'storeRefJabatan'])->middleware('auth:administrator');
Route::post('/set_jabatan', [AdminOtherController::class, 'setRefJabatan'])->middleware('auth:administrator');

// Ref Data Pokja
Route::get('/ref_pokja', [AdminOtherController::class, 'indexRefPokja'])->name('ref_pokja')->middleware('auth:administrator');
Route::post('/c_pokja', [AdminOtherController::class, 'storeRefPokja'])->middleware('auth:administrator');
Route::post('/set_pokja', [AdminOtherController::class, 'setRefPokja'])->middleware('auth:administrator');

// Ref Data Pangkat
Route::get('/ref_pangkat', [AdminOtherController::class, 'indexRefPangkat'])->name('ref_pangkat')->middleware('auth:administrator');
Route::post('/c_pangkat', [AdminOtherController::class, 'storeRefPangkat'])->middleware('auth:administrator');
Route::post('/set_pangkat', [AdminOtherController::class, 'setRefPangkat'])->middleware('auth:administrator');
Route::post('/del_pangkat', [AdminOtherController::class, 'deleteRefPangkat'])->middleware('auth:administrator');

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

// JURNAL SPBY
Route::get('/spby/{status}', [AdminOtherController::class, 'indexSPBY'])->name('spby')->middleware('auth:administrator');
Route::get('/spby-koreksi', [AdminOtherController::class, 'indexSPBYKoreksi'])->name('spby-koreksi')->middleware('auth:administrator');
Route::post('/spby/update', [AdminOtherController::class, 'updateDataSPBY'])->name('update-data-spby')->middleware('auth:administrator');

Route::post('/cu_spby_perjadin', [AdminOtherController::class, 'updateSpby'])->middleware('auth:administrator');
Route::post('/cu_spby_kegiatan', [AdminOtherController::class, 'updateSpbyKegiatan'])->middleware('auth:administrator');

// KOREKSI
Route::get('/koreksi/{status}', [AdminOtherController::class, 'indexKoreksi'])->name('koreksi')->middleware('auth:administrator');
Route::get('/koreksi-detail/{status}/{id}', [AdminOtherController::class, 'detailKoreksi'])->name('detail-koreksi')->middleware('auth:administrator');
Route::post('/koreksi/update/{status}', [AdminOtherController::class, 'updateKoreksi'])->name('update-koreksi')->middleware('auth:administrator');

Route::post('/cu_spby_perjadin', [AdminOtherController::class, 'updateSpby'])->middleware('auth:administrator');
Route::post('/cu_spby_kegiatan', [AdminOtherController::class, 'updateSpbyKegiatan'])->middleware('auth:administrator');

Route::post('/u_kegiatan_HKT', [AdminKegiatanController::class, 'uploadSurtug'])->name('upload-surat-HKT');
Route::post('/update_kegiatan_HKT', [AdminKegiatanController::class, 'updateSuratKegiatan'])->name('update-surat-HKT');
Route::post('/u_tte_kegiatan_HKT', [AdminKegiatanController::class, 'uploadTTEPerjadinKegiatan'])->name('proses-TTE-HKT');
Route::post('/tolak_surat_HKT', [AdminKegiatanController::class, 'tolakKegiatan'])->name('tolak-surat-HKT');
Route::get('/laporan-kegiatan-HKT/{mulai}/{sampai}', [AdminKegiatanController::class, 'laporanHKT'])->name('laporan-HKT-kegiatan');
Route::get('/laporanKegiatanHKT/data/{mulai}/{sampai}', [AdminKegiatanController::class, 'getAllDataHKT'])->middleware('auth:administrator');
Route::post('/generate-laporan-kegiatan-HKT', [AdminKegiatanController::class, 'generateLaporanHKT'])->middleware('auth:administrator');

//Fasilitas Bendahara
Route::post('/c_fasilitasdetail_bendahara', [AdminKegiatanController::class, 'storeFasilitasDetailBendahara'])->middleware('auth:administrator');
Route::post('/detail-bendahara/{id}', [AdminKegiatanController::class, 'detail_bendahara'])
    ->name('bendahara-kegiatan-fasilitas')
    ->middleware('auth:administrator');

//Data Balik
Route::get('/data-balik', [DataBalikController::class, 'index']);
Route::post('/data-balik-asset', [DataBalikController::class, 'processUpload'])->name('post-dataBalik');
Route::post('/data-balik-kendaraan', [DataBalikController::class, 'processUploadKendaraan'])->name('kendaraan-dataBalik');

Route::post('/generate-laporan-Usulan', [AdminOtherController::class, 'ganerateLaporanUsulan'])->middleware('auth:administrator');
Route::get('/laporan-usulan/{mulai}/{sampai}/{status?}', [AdminOtherController::class, 'laporanUsulan'])
    ->name('laporan-Usulan');
Route::get('/laporan-usulan-kegiatan/{mulai}/{sampai}/{status?}', [AdminOtherController::class, 'laporanUsulanKegiatan'])
    ->name('laporan-UsulanKegiatan');


// Pengadaan
Route::get('/buat-pengadaan/{status}', [AdminPengadaanController::class, 'buatPengadaan'])->name('buat-pengadaan')->middleware('auth:administrator');
Route::get('/daftar-pengadaan/{status}', [AdminPengadaanController::class, 'daftarPengadaan'])->name('daftar-pengadaan')->middleware('auth:administrator');
Route::get('/info-pengadaan', [AdminPengadaanController::class, 'infoPengadaan'])->name('info-pengadaan')->middleware('auth:administrator');
Route::get('/detail/pengadaan-kegiatan/{id}/{kebutuhanId}', [AdminPengadaanController::class, 'detailPengadaanKegiatan'])->name('detail-pengadaan-kegiatan')->middleware('auth:administrator');
Route::post('/cd_dok_pengadaan_kegiatan', [AdminPengadaanController::class, 'storeDokPengadaanKegiatan'])->middleware('auth:administrator');

Route::get('/get-tarif-pajak/{status}/{golongan}', [AdminOtherController::class, 'getTarifPajak'])->name('get-tarif-pajak');

//
Route::get('/printpdf', [LoginController::class, 'pdf1'])->name('user');
Route::get('/kegiatan-pdf/preview', [LoginController::class, 'ConvertSurtug'])->middleware('auth:administrator');

// MANUAL BOOK
Route::get('/manual_book_user', function () {
    $path = storage_path('app/public/dokumens-template/AKUNKEUN_MANUAL_USER.pdf');

    if (!file_exists($path)) {
        abort(404, 'File tidak ditemukan');
    }

    return response()->file($path, [
        'Content-Type' => 'application/pdf',
    ]);
});

Route::get('/clear-config', function() {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    return 'Config cleared';
});


// TEST LEBIH DARI 2 MB
Route::get('/upload-tes', function () {
    return view('user.upload-tes');
});
Route::get('/upload-tes', [PdfUploadController::class, 'index'])->name('upload.form');
Route::post('/upload-chunk', [PdfUploadController::class, 'uploadChunk'])->name('upload.chunk');
Route::post('/merge-chunks', [PdfUploadController::class, 'mergeChunks'])->name('upload.merge');


Route::get('/test-error', function () {
    abort(404); // Memicu error 500
});