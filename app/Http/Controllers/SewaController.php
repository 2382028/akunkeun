<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomCategories;
use App\Models\PengajuanSewa;
use App\Models\Pemesanan;
use App\Models\PembatalanSewa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Notifikasi;
use Illuminate\Http\JsonResponse;
class SewaController extends Controller
{
    public function template_sewa()
    {
        return view('sewa.template', [
            'title' => 'Template Sewa',
            'active' => 'template',
        ]);
    }

    public function index_sewa()
    {
        return view('sewa.index', [
            'title' => 'Sewa',
            'active' => 'sewa',
        ]);
    }
public function mess()
{
    $roomCategories = RoomCategories::whereHas('kamars', function ($q) {
            $q->where('status_kamar', 'available');
        })
        ->with(['kamars' => function ($q) {
            $q->where('status_kamar', 'available') // hanya ambil kamar yang available
              ->with('fasilitas');
        }])
        ->get();

    return view('sewa.mess', [
        'title' => 'Sewa Mess',
        'active' => 'mess',
        'roomCategories' => $roomCategories,
    ]);
}

    public function showForm(Request $request)
    {
        $jsonData = $request->query('data');
        $decoded = json_decode($jsonData, true);

        if (!$decoded) {
            return redirect()->route('mess')->with('error', 'Data sewa tidak valid.');
        }

        $user = Auth::guard('akun_penyewa')->user(); // AkunPenyewa

        $start = $decoded['start'];
        $end = $decoded['end'];
        $startDate = new \DateTime($start);
        $endDate = new \DateTime($end);
        $nights = $startDate->diff($endDate)->days;

        $rooms = $decoded['rooms'];
        $totalPrice = 0;
        $groupedRooms = [];

        foreach ($rooms as &$room) {
            $room['subtotal'] = $room['price'] * $room['quantity'] * $nights;
            $totalPrice += $room['subtotal'];

            $type = $room['name'];
            $price = $room['price'];

            if (!isset($groupedRooms[$type])) {
                $groupedRooms[$type] = [];
            }

            if (!isset($groupedRooms[$type][$price])) {
                $groupedRooms[$type][$price] = [
                    'quantity' => 0,
                    'price' => $price,
                    'subtotal' => 0
                ];
            }

            $groupedRooms[$type][$price]['quantity'] += $room['quantity'];
            $groupedRooms[$type][$price]['subtotal'] += $room['subtotal'];
        }

        foreach ($groupedRooms as $type => $items) {
            $groupedRooms[$type] = array_values($items);
        }

        session([
            'start' => $start,
            'end' => $end,
            'nights' => $nights,
            'rooms' => $rooms,
            'totalPrice' => $totalPrice
        ]);

        return view('sewa.form_pengajuan', [
            'title' => 'Form Pengajuan Sewa',
            'active' => 'form_pengajuan',
            'penyewa' => $user, // Ini tetap akun_penyewa
            'rooms' => $rooms,
            'groupedRooms' => $groupedRooms,
            'start' => $start,
            'end' => $end,
            'nights' => $nights,
            'totalPrice' => $totalPrice
        ]);
    }

    public function submitPengajuan(Request $request)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:cash,transfer'
        ]);

        $start = session('start');
        $end = session('end');
        $nights = session('nights');
        $rooms = session('rooms');
        $totalPrice = session('totalPrice');

        $today = now()->format('Ymd');

        $lastPemesanan = DB::table('pemesanans')
            ->where('kode_pemesanan', 'like', "MESS-$today-%")
            ->orderByDesc('kode_pemesanan')
            ->first();

        if ($lastPemesanan) {
            $lastNumber = (int) substr($lastPemesanan->kode_pemesanan, -3); // ambil nomor terakhir
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $kodePemesanan = 'MESS-' . $today . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $penyewa = Auth::guard('akun_penyewa')->user();
        $tanggal_checkin = Carbon::parse($start)->setTime(13, 0, 0);
        $tanggal_checkout = Carbon::parse($end)->setTime(13, 0, 0);

        // Insert ke tabel pemesanans (tidak pakai metode_pembayaran lagi)
        DB::table('pemesanans')->insert([
            'kode_pemesanan'   => $kodePemesanan,
            'id_penyewa'       => $penyewa->id_penyewa,
            'tanggal_checkin'  => $tanggal_checkin,
            'tanggal_checkout' => $tanggal_checkout,
            'subtotal'         => $totalPrice,
            'status'           => 'draft',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        //  Insert ke tabel pembayaran
        DB::table('pembayaran')->insert([
            'metode_pembayaran' => $request->metode_pembayaran,
            'url_path'          => null, // Kosongkan dulu
            'kode_pemesanan'    => $kodePemesanan,
        ]);

        // Insert ke tabel detail_pemesanan_kamar
        foreach ($rooms as $room) {
            $roomIds = $room['ids'];
            $hargaPerMalam = $room['price'];

            foreach ($roomIds as $id_kamar) {
                DB::table('detail_pemesanan_kamar')->insert([
                    'kode_pemesanan' => $kodePemesanan, // pastikan kolomnya sekarang pakai kode_pemesanan kalau relasi berubah
                    'id_kamar'       => $id_kamar,
                    'subtotal'       => $hargaPerMalam * $nights,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }
        }

        // 4️ Pesan notifikasi
        if ($request->metode_pembayaran === 'cash') {
            $message = 'Pesanan Berhasil Dibuat, buka menu Pesanan Saya untuk mengirim pengajuan';
        } else {
            $message = 'Pesanan Berhasil Dibuat, buka menu Pesanan Saya untuk mengunggah bukti pembayaran';
        }

        return redirect()->route('pesanan.saya')->with([
            'success' => $message,
            'default_tab' => 'draft'
        ]);
    }

    public function cekKamarTersedia(Request $request)
    {
        $start = Carbon::parse($request->query('start'))->startOfDay();
        $end = Carbon::parse($request->query('end'))->startOfDay();

        if (!$start || !$end) {
            return response()->json([]);
        }

        // Ambil semua kombinasi kategori & harga dari kamar
        $kamarGroups = DB::table('kamar')
            ->join('kategori_kamar', 'kamar.id_kategori_kamar', '=', 'kategori_kamar.id_kategori_kamar')
            ->select('kamar.id_kategori_kamar', 'kategori_kamar.nama_kategori', 'kamar.harga_per_malam')
            ->groupBy('kamar.id_kategori_kamar', 'kategori_kamar.nama_kategori', 'kamar.harga_per_malam')
            ->get();

        $result = [];

        foreach ($kamarGroups as $group) {
            $kategoriId = $group->id_kategori_kamar;
            $harga = $group->harga_per_malam;

            // Ambil semua kamar yang aktif dan cocok dengan kategori + harga
            $totalKamar = DB::table('kamar')
                ->where('id_kategori_kamar', $kategoriId)
                ->where('harga_per_malam', $harga)
                ->whereRaw('LOWER(status_kamar) = ?', ['available'])
                ->pluck('id_kamar');

            // Cek berapa kamar yang bentrok dengan rentang waktu
            $kamarDipakai = DB::table('detail_pemesanan_kamar')
                ->join('pemesanans', 'detail_pemesanan_kamar.kode_pemesanan', '=', 'pemesanans.kode_pemesanan')
                ->whereIn('detail_pemesanan_kamar.id_kamar', $totalKamar)
                ->whereIn('pemesanans.status', ['draft', 'verifikasi', 'menunggu', 'diterima'])
                ->where(function ($q) use ($start, $end) {
                    $q->whereDate('pemesanans.tanggal_checkin', '<', $end->toDateString())
                        ->whereDate('pemesanans.tanggal_checkout', '>', $start->toDateString());
                })
                ->pluck('detail_pemesanan_kamar.id_kamar')
                ->unique();

            $availableKamarCount = $totalKamar->diff($kamarDipakai)->count();

            // Masukkan hasil ke dalam array response
            $availableKamarIds = $totalKamar->diff($kamarDipakai)->values();
            // Ambil fasilitas dari kamar pertama yang tersedia
            $fasilitas = collect();

            if ($availableKamarIds->isNotEmpty()) {
                $firstKamarId = $availableKamarIds->first();
                $fasilitas = DB::table('detail_kamar')
                    ->join('fasilitas_sewa', 'detail_kamar.id_fasilitas_sewa', '=', 'fasilitas_sewa.id_fasilitas_sewa')
                    ->where('detail_kamar.id_kamar', $firstKamarId)
                    ->select('fasilitas_sewa.nama_fasilitas as nama', 'detail_kamar.jumlah')
                    ->get();
            }

        // Ambil data kamar lengkap (id + nomor)
        $availableKamar = DB::table('kamar')
            ->whereIn('id_kamar', $availableKamarIds)
            ->select('id_kamar as id', 'nomor_kamar as nomor')
            ->get();
            $result[$kategoriId][$harga] = [
                'count' => $availableKamarIds->count(),
                'ids' => $availableKamarIds,
                'kamar' => $availableKamar,
                'nama_kategori' => $group->nama_kategori,
                'fasilitas' => $fasilitas
            ];
        }

        return response()->json($result);
    }
    public function pesananSaya()
    {
        // Ambil user yang sedang login (AkunPenyewa)
        $akun = Auth::guard('akun_penyewa')->user();

        // Cek jika relasi ke Penyewa ada
        if (!$akun || !$akun->penyewa) {
            return redirect()->route('masuk')->with('error', 'Akses ditolak. Silakan login kembali.');
        }

        $penyewa = $akun->penyewa;

        // Update otomatis draft yang kadaluarsa
        Pemesanan::where('status', 'draft')
            ->where('created_at', '<', now()->subHours(24))
            ->update([
                'status' => 'dibatalkan',
                'updated_at' => now()
            ]);

        // Ambil data pesanan + relasi penolakan
        $pesanan = Pemesanan::with(['penolakan', 'pembayaran', 'detailKamar'])
            ->where('id_penyewa', $penyewa->id_penyewa)
            ->orderByDesc('created_at')
            ->get();


        // Ambil detail kamar & nama penyewa
        foreach ($pesanan as $p) {
            $p->detailKamar = DB::table('detail_pemesanan_kamar')
                ->join('kamar', 'detail_pemesanan_kamar.id_kamar', '=', 'kamar.id_kamar')
                ->join('kategori_kamar', 'kamar.id_kategori_kamar', '=', 'kategori_kamar.id_kategori_kamar')
                ->where('detail_pemesanan_kamar.kode_pemesanan', $p->id_penyewa)
                ->select('kategori_kamar.nama_kategori', 'kamar.nomor_kamar', 'detail_pemesanan_kamar.subtotal')
                ->get();

            $p->nama_penyewa = $penyewa->nama_lengkap;
        }

        return view('sewa.pesanan_saya', compact('pesanan'));
    }
    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'bukti_transfer.required' => 'Bukti transfer wajib diunggah.',
            'bukti_transfer.mimes'    => 'Format file harus JPG, PNG, atau PDF.',
            'bukti_transfer.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        // Ambil data pemesanan + relasi pembayaran
        $pemesanan = Pemesanan::with(['pembayaran', 'penyewa'])->where('kode_pemesanan', $id)->first();

        if (!$pemesanan) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
        }

        if (!$pemesanan->pembayaran) {
            return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        // Simpan file
        $file = $request->file('bukti_transfer');
        $timestamp = now()->format('YmdHis');
        $extension = $file->getClientOriginalExtension();
        $filename = 'bukti-transfer-' . $pemesanan->kode_pemesanan . '-' . $timestamp . '.' . $extension;

        // Simpan ke storage/public/bukti_pembayaran
        $path = $file->storeAs('bukti_pembayaran', $filename, 'public');

        // Update tabel pembayaran (kolom url_path)
        $pemesanan->pembayaran->update([
            'url_path'   => $path,
            'updated_at' => now(),
        ]);

        // Update status pemesanan
        $pemesanan->update([
            'status'     => 'menunggu',
            'updated_at' => now(),
        ]);
Notifikasi::create([
            'from' => $pemesanan->id_penyewa,
            'role' => "Bendahara Penyewaan",
            'header' => 'Pengajuan Penyewaan Baru',
            'message' => "Terdapat pengajuan penyewaan baru dari {$pemesanan->penyewa->nama_lengkap}.",
            'route' => "penyewaan_aset/menunggu",
            'is_read' => 0,
            'created_at' => now(),
        ]);
        return redirect()->route('pesanan.saya')->with('success', 'Bukti pembayaran berhasil diunggah.');
    }


    public function batalkanPesanan($id)
    {
        $pemesanan = DB::table('pemesanans')->where('kode_pemesanan', $id)->first();

        if (!$pemesanan || $pemesanan->status !== 'draft') {
            return redirect()->route('pesanan.saya')->with('error', 'Pesanan tidak dapat dibatalkan.');
        }
        DB::table('pemesanans')->where('kode_pemesanan', $id)->update([
            'status' => 'dibatalkan',
            'updated_at' => now()
        ]);

        return redirect()->route('pesanan.saya')->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function konfirmasiCash($id)
    {
        $pemesanan = Pemesanan::with(['penyewa', 'pembayaran'])
            ->where('kode_pemesanan', $id)
            ->first();

        if ($pemesanan->pembayaran->metode_pembayaran !== 'cash') {
            return redirect()->route('pesanan.saya')->with('error', 'Pesanan ini bukan metode cash.');
        }


        if (!$pemesanan) {
            return redirect()->route('pesanan.saya')->with('error', 'Pesanan tidak ditemukan.');
        }

        if ($pemesanan->status !== 'draft') {
            return redirect()->route('pesanan.saya')->with('error', 'Pesanan tidak dapat dikirim.');
        }


        // Buat PDF bukti pemesanan
        $checkin = Carbon::parse($pemesanan->tanggal_checkin);
        $jumlahSebelumnya = DB::table('pemesanans')
            ->whereYear('tanggal_checkin', $checkin->year)
            ->whereMonth('tanggal_checkin', $checkin->month)
            ->count();

        $urutan = str_pad($jumlahSebelumnya + 1, 2, '0', STR_PAD_LEFT);
        $tanggal = $checkin->format('dm');
        $tahun = $checkin->format('Y');

        $namaBukti = 'buktipesan-' . $urutan . $tanggal . '-' . $tahun . '.pdf';

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

        // Buat dan simpan PDF
        $pdf = Pdf::loadView('sewa.bukti_pesanan', [
            'pemesanan' => $pemesanan,
            'groupedRooms' => $groupedRooms,
            'namaBukti' => $namaBukti
        ]);

        Storage::disk('public')->put('bukti_pemesanan/' . $namaBukti, $pdf->output());

        // Update status dan simpan nama bukti di database
        $pemesanan = Pemesanan::where('kode_pemesanan', $id)->first();

        $pemesanan->update([
            'status' => 'verifikasi',
            'updated_at' => now()
        ]);

        $pemesanan->pembayaran()->update([
            'url_path' => $namaBukti,
            'updated_at' => now()
        ]);

        return redirect()->route('pesanan.saya')->with('success', 'Pesanan cash berhasil dikirim dan bukti pemesanan berhasil dibuat.');
    }

    public function downloadBukti($id)
    {
        $pesanan = DB::table('pembayaran')->where('kode_pemesanan', $id)->first();

        if (!$pesanan || !$pesanan->url_path) {
            return back()->with('error', 'Bukti pemesanan tidak ditemukan.');
        }

        $filePath = storage_path('app/public/bukti_pemesanan/' . $pesanan->url_path);
        return response()->download($filePath);
    }


    public function downloadInvoice($id)
    {
        $pemesanan = Pemesanan::with(['pembayaran.invoice'])
            ->where('kode_pemesanan', $id)
            ->first();
    
        if (!$pemesanan || $pemesanan->status !== 'diterima' || !$pemesanan->pembayaran || !$pemesanan->pembayaran->invoice) {
            abort(404);
        }
    
        $filePath = storage_path('app/public/' . $pemesanan->pembayaran->invoice->url_invoice);
    
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }
    
        return response()->download($filePath, 'Invoice-' . $pemesanan->kode_pemesanan . '.pdf');
        }
    
        public function lihatBukti($id)
        {
            $pembatalan = PembatalanSewa::where('kode_pemesanan', $id)->firstOrFail();
    
            if (!$pembatalan->bukti_refund || !Storage::disk('public')->exists($pembatalan->bukti_refund)) {
                abort(404, 'Bukti refund tidak ditemukan.');
            }
    
            return response()->file(storage_path('app/public/' . $pembatalan->bukti_refund));
        }
                public function notifPenyewa($idPenyewa): JsonResponse
    {
        $penyewa = auth('akun_penyewa')->user()->id_penyewa;

        $notifQuery = DB::table('notifications')
            ->where('is_read', 0)
            ->where('role', 'penyewa')
            ->where('to', $penyewa) // notifikasi khusus penyewa
            ->orderBy('created_at', 'desc');

        $notifData = $notifQuery->get();

        $notifDataUnread = $notifData->where('is_read', 0)->values();
        $notifDataRead = $notifData->where('is_read', 1)->take(5)->values();

        return response()->json([
            'notif' => $notifDataUnread->count(),
            'notifData' => $notifData,           // gabungan semua notifikasi
            'notifDataUnread' => $notifDataUnread,
            'notifDataRead' => $notifDataRead,
        ]);
    }

    // Tandai satu notifikasi Penyewa sebagai dibaca
    public function markAsReadPenyewa($id)
    {
        DB::table('notifications')
            ->where('id', $id)
            ->update(['is_read' => 1]);

        return response()->json(['success' => true]);
    }

    // Tandai semua notifikasi Penyewa sebagai dibaca
    public function markAllPenyewa($idPenyewa)
    {
        DB::table('notifications')
            ->where('to', $idPenyewa)
            ->update(['is_read' => 1]);

        return response()->json(['message' => 'Semua notifikasi telah ditandai sebagai dibaca.']);
    }
    }
