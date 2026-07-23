<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// ==========================================
// 1. SETUP DUMMY DATA FOR TESTING
// ==========================================
$pegawai_id = 2; // Anggap ID 2 adalah Abdul
$tanggalAwalTest = Carbon::parse('2027-01-01');
$tanggalAkhirTest = Carbon::parse('2027-01-05');

// Buat dummy Program Kegiatan
$kegiatanId = DB::table('data_perjadinkegiatans')->insertGetId([
    'nama_kegiatan' => 'TEST KEGIATAN OVERLAP',
    'tgl_mulai' => $tanggalAwalTest->format('Y-m-d H:i:s'),
    'tgl_selesai' => $tanggalAkhirTest->format('Y-m-d H:i:s'),
    'status_pengajuan' => 'pengajuan',
    'provinsi' => 'Jawa Barat',
    'status' => '1',
    'created_at' => now(),
    'updated_at' => now(),
]);

// Masukkan Abdul (ID 2) ke Program Kegiatan
$perangkatId = DB::table('perangkat_acaras')->insertGetId([
    'pegawai_id' => $pegawai_id,
    'data_perjadin_kegiatan' => $kegiatanId,
    'posisi' => 'Panitia',
    'status' => 'pengajuan', // Not Ditolak
    'created_at' => now(),
    'updated_at' => now(),
]);

echo "[SETUP] Berhasil membuat Kegiatan Dummy ID: $kegiatanId\n";
echo "[SETUP] Berhasil memasukkan Pegawai ID $pegawai_id ke Kegiatan tersebut.\n\n";

// ==========================================
// 2. SIMULASI USER MEMBUAT PERJALANAN DINAS (TGL OVERLAP)
// ==========================================
// User mencoba membuat Perjadin dari tgl 03 s/d 04 Januari 2027
$tanggalAwalPerjadin = Carbon::parse('2027-01-03');
$tanggalAkhirPerjadin = Carbon::parse('2027-01-04');

echo "[SIMULASI] User mencoba membuat Perjadin di tgl 2027-01-03 sampai 2027-01-04...\n";
echo "[SIMULASI] Mengecek jadwal bentrok...\n\n";

$occupiedPegawaiIds = DB::table('perangkat_acaras')
    ->join('data_perjadinkegiatans', 'perangkat_acaras.data_perjadin_kegiatan', '=', 'data_perjadinkegiatans.id')
    ->where('perangkat_acaras.status', '!=', 'Ditolak')
    ->whereNotNull('perangkat_acaras.pegawai_id')
    ->where('data_perjadinkegiatans.status_pengajuan', '!=', 'ditolak')
    ->where(function ($q) use ($tanggalAwalPerjadin, $tanggalAkhirPerjadin) {
        $q->whereRaw('COALESCE(perangkat_acaras.tgl_mulai, data_perjadinkegiatans.tgl_mulai) <= ?', [$tanggalAkhirPerjadin])
          ->whereRaw('COALESCE(perangkat_acaras.tgl_selesai, data_perjadinkegiatans.tgl_selesai) >= ?', [$tanggalAwalPerjadin]);
    })
    ->pluck('perangkat_acaras.pegawai_id')
    ->toArray();

// ==========================================
// 3. HASIL TESTING
// ==========================================
if (in_array($pegawai_id, $occupiedPegawaiIds)) {
    echo ">> [SUKSES] Pegawai ID $pegawai_id terdeteksi sibuk!\n";
    echo ">> Pegawai ini AKAN DISEMBUNYIKAN dari dropdown Perjalanan Dinas.\n";
} else {
    echo ">> [GAGAL] Pegawai ID $pegawai_id TIDAK terdeteksi sibuk!\n";
}

// ==========================================
// 4. CLEANUP DUMMY DATA
// ==========================================
DB::table('perangkat_acaras')->where('id', $perangkatId)->delete();
DB::table('data_perjadinkegiatans')->where('id', $kegiatanId)->delete();
echo "\n[CLEANUP] Dummy data dihapus.\n";
