<?php

namespace App\Providers;
use Carbon\Carbon;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\InventarisBmn;
use App\Models\Pemeliharaan;
use Illuminate\Support\Facades\Auth;
use App\Models\Notifikasi;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id'); // Mengatur locale ke Bahasa Indonesia
    Relation::morphMap([
            'inventaris' => \App\Models\InventarisBmn::class,
            'ruangan' => \App\Models\RuanganBmn::class,
            'pesanan_bap' => \App\Models\SuratPemeliharaan::class,
            'pengajuan_pembayaran' => \App\Models\PembayaranPemeliharaan::class,
            'per_bmn' => \App\Models\Pemeliharaan::class,
            'sewa' => \App\Models\Pemesanan::class,
            'pnbp' => \App\Models\Pnbp::class,
        ]);

        if (app()->runningInConsole()) {
            return;
        }

        // === Otomatis selesaikan pemesanan ===
        if (!Cache::has('last_pemesanan_check') || now()->diffInSeconds(Cache::get('last_pemesanan_check')) >= 60) {

            // 1️⃣ Update pemesanan diterima -> selesai jika checkout lewat
            Pemesanan::where('status', 'diterima')
                ->where('tanggal_checkout', '<', now())
                ->update([
                    'status' => 'selesai',
                    'updated_at' => now()
                ]);

            // 2️⃣ Update pemesanan draft -> dibatalkan jika dibuat >24 jam lalu
            Pemesanan::where('status', 'draft')
                ->where('created_at', '<', now()->subHours(24))
                ->update([
                    'status' => 'dibatalkan',
                    'updated_at' => now()
                ]);

            Cache::put('last_pemesanan_check', now(), 120);
        }

        // === Cek jadwal pemeliharaan BMN ===
        if (!Cache::has('last_pemeliharaan_check') || now()->diffInSeconds(Cache::get('last_pemeliharaan_check')) >= 60) {

            $now = now();
            $bmnList = InventarisBmn::whereNotNull('jadwal_pemeliharaan')
                ->whereDate('jadwal_pemeliharaan', '<=', $now->toDateString())
                ->get();

            foreach ($bmnList as $bmn) {
                $sudahAdaPemeliharaan = Pemeliharaan::where('bmn_id', $bmn->id_inventaris_bmn)
                    ->where('bmn_type', 'inventaris')
                    ->where('created_at', '>', $bmn->jadwal_pemeliharaan)
                    ->exists();

                if ($sudahAdaPemeliharaan) continue;

                $sudahAdaNotifikasi = Notifikasi::where('id_kegiatan', $bmn->id_inventaris_bmn)
                    ->whereDate('created_at', '<=', $bmn->jadwal_pemeliharaan)
                    ->exists();

                if (!$sudahAdaNotifikasi) {
                    Notifikasi::insert([
                        'id_kegiatan' => $bmn->id_inventaris_bmn,
                        'from' => 0,
                        'to' => 0,
                        'role' => 'Pejabat Pemeliharaan',
                        'header' => 'Pemeliharaan BMN Rutin',
                        'message' => sprintf(
                            'Waktunya pemeliharaan rutin untuk %s (Kode: %s, NUP: %s)',
                            $bmn->nama_bmn,
                            $bmn->kode_bmn,
                            $bmn->nup_bmn
                        ),
                        'route' => 'pemeliharaan-admin',
                        'is_read' => 0,
                        'created_at' => now(),
                    ]);
                }

                // 🔑 Generate ID Pemeliharaan
                $tanggal = now()->format('dmY');
                $jenisBmnFormatted = 'Inventaris'; // karena disini khusus inventaris
                $countToday = Pemeliharaan::whereDate('created_at', now())
                    ->where('bmn_type', 'inventaris')
                    ->count() + 1;

                $idPemeliharaan = "PM-{$tanggal}-{$jenisBmnFormatted}-{$countToday}";

                Pemeliharaan::create([
                    'id_pemeliharaan' => $idPemeliharaan,
                    'id_karyawan' => 0,
                    'bmn_id' => $bmn->id_inventaris_bmn,
                    'bmn_type' => 'inventaris',
                    'id_ref_status_pemeliharaan' => 1,
                    'keterangan' => 'Siap untuk pemeliharaan rutin',
                ]);
            }

            Cache::put('last_pemeliharaan_check', now(), 120);
        }
        
    }
}
