<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

$pa = DB::table('perangkat_acaras')
    ->join('data_perjadinkegiatans', 'perangkat_acaras.data_perjadin_kegiatan', '=', 'data_perjadinkegiatans.id')
    ->whereNotNull('perangkat_acaras.pegawai_id')
    ->whereNotNull('data_perjadinkegiatans.tgl_mulai')
    ->select('perangkat_acaras.pegawai_id', 'data_perjadinkegiatans.tgl_mulai', 'data_perjadinkegiatans.tgl_selesai')
    ->first();

if ($pa) {
    echo 'TESTING WITH EXISTING PEGAWAI ID: ' . $pa->pegawai_id . "\n";
    echo 'TGL MULAI: ' . $pa->tgl_mulai . "\n";
    echo 'TGL SELESAI: ' . $pa->tgl_selesai . "\n";
    
    $tanggalAwal = Carbon::parse($pa->tgl_mulai);
    $tanggalAkhir = Carbon::parse($pa->tgl_selesai);
    
    $occupiedPegawaiIds = DB::table('perangkat_acaras')
        ->join('data_perjadinkegiatans', 'perangkat_acaras.data_perjadin_kegiatan', '=', 'data_perjadinkegiatans.id')
        ->where('perangkat_acaras.status', '!=', 'Ditolak')
        ->whereNotNull('perangkat_acaras.pegawai_id')
        ->where('data_perjadinkegiatans.status_pengajuan', '!=', 'ditolak')
        ->where(function ($q) use ($tanggalAwal, $tanggalAkhir) {
            $q->whereRaw('COALESCE(perangkat_acaras.tgl_mulai, data_perjadinkegiatans.tgl_mulai) <= ?', [$tanggalAkhir])
              ->whereRaw('COALESCE(perangkat_acaras.tgl_selesai, data_perjadinkegiatans.tgl_selesai) >= ?', [$tanggalAwal]);
        })
        ->pluck('perangkat_acaras.pegawai_id')
        ->toArray();
        
    if (in_array($pa->pegawai_id, $occupiedPegawaiIds)) {
        echo '>> BERHASIL: Pegawai terdeteksi sibuk dan akan di-exclude dari Select2!' . "\n";
    } else {
        echo '>> GAGAL: Pegawai tidak terdeteksi.' . "\n";
    }
} else {
    echo 'TIDAK ADA DATA UNTUK DITEST' . "\n";
}
