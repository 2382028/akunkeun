<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

$tanggalAwal = Carbon::parse('2023-10-10');
$tanggalAkhir = Carbon::parse('2023-10-15');

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

echo "Pegawai Sibuk di Program Kegiatan:\n";
print_r($occupiedPegawaiIds);

$occupiedPegawaiLangsungs = DB::table('data_perjadinlangsungs')
    ->join('info_perjadinlangsungs', 'data_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
    ->where('data_perjadinlangsungs.status_persetujuan', '!=', 'Ditolak')
    ->whereNotNull('data_perjadinlangsungs.pegawai_id')
    ->where('info_perjadinlangsungs.status_pengajuan', '!=', 'ditolak')
    ->where(function ($q) use ($tanggalAwal, $tanggalAkhir) {
        $q->whereRaw('COALESCE(data_perjadinlangsungs.tgl_keberangkatan, info_perjadinlangsungs.tgl_keberangkatan) <= ?', [$tanggalAkhir])
          ->whereRaw('COALESCE(data_perjadinlangsungs.tgl_selesai, info_perjadinlangsungs.tgl_selesai) >= ?', [$tanggalAwal]);
    })
    ->pluck('data_perjadinlangsungs.pegawai_id')
    ->toArray();

echo "\nPegawai Sibuk di Perjalanan Dinas:\n";
print_r($occupiedPegawaiLangsungs);
