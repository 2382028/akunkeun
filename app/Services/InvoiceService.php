<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\RefKopSurat;
use App\Models\Pegawai;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public static function generate($pemesanan)
    {
        $checkin = Carbon::parse($pemesanan->tanggal_checkin);

        // Buat nama invoice unik
        $jumlahSebelumnya = Invoice::count();
        $urutan  = str_pad($jumlahSebelumnya + 1, 2, '0', STR_PAD_LEFT);
        $tanggal = $checkin->format('dm');
        $tahun   = $checkin->format('Y');
        $namaInvoice = $urutan . $tanggal . '-' . $tahun . '.pdf';

        // Ambil kamar yang dipesan
        $detailKamar = DB::table('detail_pemesanan_kamar as dpk')
            ->join('kamar as k', 'dpk.id_kamar', '=', 'k.id_kamar')
            ->join('kategori_kamar as kk', 'k.id_kategori_kamar', '=', 'kk.id_kategori_kamar')
            ->select(
                'kk.nama_kategori',
                'k.nomor_kamar',
                'k.lantai',
                'k.harga_per_malam',
                'dpk.subtotal'
            )
            ->where('dpk.kode_pemesanan', $pemesanan->kode_pemesanan)
            ->get();

        // Grouping kamar per kategori
        $groupedRooms = [];
        foreach ($detailKamar as $item) {
            $kategori = $item->nama_kategori;
            if (!isset($groupedRooms[$kategori])) {
                $groupedRooms[$kategori] = [];
            }
            $groupedRooms[$kategori][] = [
                'price' => $item->harga_per_malam,
                'quantity' => 1,
                'subtotal' => $item->subtotal,
                'nomor_kamar' => $item->nomor_kamar,
                'lantai' => $item->lantai,
            ];
        }

        $kopSurat = RefKopSurat::where('is_aktif', 1)
        ->where('pemilik', 0)
        ->latest()
        ->first();
        $user     = Auth::user();
        $pegawai  = Pegawai::where('email', $user->email)->first();
        $namaDepan = explode(' ', $user->username)[0];
        $nipNik   = $pegawai->NIP_NIK ?? '-';

        // Buat PDF
        $pdf = Pdf::loadView('admin.bmn.sewa_invoices', [
            'nama_penyewa' => $pemesanan->penyewa->nama_lengkap,
            'pemesanan'    => $pemesanan,
            'groupedRooms' => $groupedRooms,
            'namaInvoice'  => $namaInvoice,
            'kopSurat'     => $kopSurat ?? null,
            'namaPetugas'  => $user->username,
            'tandaTangan'  => $namaDepan,
            'nip'          => $nipNik
        ])->setOption('fontDir', public_path('/assets/fonts'));

        $pathInvoice = 'invoice/' . $namaInvoice;
        Storage::disk('public')->put($pathInvoice, $pdf->output());

        // Simpan / update ke tabel invoice
        $existingInvoice = Invoice::where('id_pembayaran', $pemesanan->pembayaran->id_pembayaran)->first();

        if ($existingInvoice) {
            $existingInvoice->update([
                'url_invoice' => $pathInvoice
            ]);
        } else {
            Invoice::create([
                'url_invoice'   => $pathInvoice,
                'id_pembayaran' => $pemesanan->pembayaran->id_pembayaran,
            ]);
        }

        return $pathInvoice;
    }
}
