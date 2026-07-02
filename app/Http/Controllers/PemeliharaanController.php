<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\pegawai;
use App\Models\Karyawan;
use App\Models\RuanganBmn;
use App\Models\Pemeliharaan;
use App\Models\Administrator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Data_penyedia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use App\Models\PembayaranPemeliharaan;
use App\Models\LampiranPembayaranPemeliharaan;
use App\Models\Notifikasi;
use App\Models\Ref_rkakl_satker;
use App\Models\RefKodeLayananSurat;
use App\Models\SuratPemeliharaan;
use App\Models\KelompokBarangPesanan;
use App\Models\BuktiPengembalian;
use App\Models\RefKopSurat;
use App\Models\InventarisBmn;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Penolakan;
use App\Models\Ppn;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;


class PemeliharaanController extends Controller
{
    public function indexSuratPemeliharaan()
{
    $datas = SuratPemeliharaan::all();
    return view('admin.pemeliharaan.surat_pemeliharaan', compact('datas'));
}
public function storeSuratPemeliharaan(Request $request)
{
    $request->validate([
        'nomor_surat' => 'required|string',
        'perihal' => 'required|string',
        'tanggal' => 'required|date',
        'tipe' => 'required|string',
        'url_surat' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
    ]);

    $filePath = null;
    if ($request->hasFile('url_surat')) {
        // ubah format nama file: tipe_nomor_surat.pdf
        $safeNomor = str_replace(['/', ' '], ['-', '_'], $request->nomor_surat); 
        $fileName = strtoupper($request->tipe) . '-' . $safeNomor . '.' . $request->url_surat->extension();
        $filePath = $request->file('url_surat')->storeAs('dokumen', $fileName, 'public');
        $filePath = '/storage/' . $filePath;
    }

    SuratPemeliharaan::create([
        'nomor_surat' => $request->nomor_surat,
        'perihal' => $request->tipe . '-' . $request->perihal,
        'created_at' => $request->tanggal,
        'url_surat' => $filePath,
    ]);

    return redirect()->back()->with('success', 'Surat pemeliharaan berhasil ditambahkan.');
}

public function updateSuratPemeliharaan(Request $request, $nomor_surat)
{
    $nomor_surat = urldecode($nomor_surat);
    $surat = SuratPemeliharaan::findOrFail($nomor_surat);

    $path = $surat->url_surat; // default file lama

    if ($request->hasFile('url_surat_edit')) {
        $file = $request->file('url_surat_edit');
        $safeNomor = str_replace(['/', ' '], ['-', '_'], $request->nomor_surat_edit);
        $filename = strtoupper($request->tipe_edit) . '-' . $safeNomor . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('dokumen', $filename, 'public');
        $path = '/storage/' . $path;
    } elseif ($request->filled('old_url_surat')) {
        $path = $request->old_url_surat;
    }

    $surat->update([
        'nomor_surat' => $request->nomor_surat_edit,
        'perihal' => $request->tipe_edit . '-' . $request->perihal_edit,
        'created_at' => $request->tanggal_edit,
        'url_surat' => $path,
    ]);

    return redirect()->back()->with('success', 'Surat pemeliharaan berhasil diperbarui.');
}
public function destroySuratPemeliharaan($nomor_surat)
{
    $nomor_surat = urldecode($nomor_surat);
    $surat = SuratPemeliharaan::findOrFail($nomor_surat);
    $surat->delete();

    return redirect()->back()->with('success', 'Surat pemeliharaan berhasil dihapus.');
}
    public function pengaturanPenyedia()
{
    $pemilik = auth('penyedia')->id();
    $data = RefKopSurat::where('pemilik', $pemilik)->get();
    return view('user.pemeliharaan.penyedia.pengaturan', compact('data'));
}
    public function storeKopPenyedia(Request $request)
    {
        $file = $request->file('url_kop');
        $filename = 'kop_' . now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('dokumen', $filename, 'public');
        $pemilik = auth('penyedia')->id();

        RefKopSurat::create([
            'nama_kop' => $request->nama_kop,
            'pemilik' => $pemilik,
            'is_aktif' => 1,
            'url_kop' => $path
        ]);

        return back()->with('success', 'Kop surat ditambahkan.');
    }
    public function editKopPenyedia($id)
    {
        return RefKopSurat::findOrFail($id);
    }

    public function updateKopPenyedia(Request $request, $id)
    {
        $kop = RefKopSurat::findOrFail($id);
        $kop->nama_kop = $request->nama_kop;

        if ($request->hasFile('url_kop')) {
            $file = $request->file('url_kop');
            $filename = 'kop_' . now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('dokumen', $filename, 'public');
            $kop->url_kop = $path;
        }
        $kop->save();
        return back()->with('success', 'Kop surat berhasil diupdate.');
    }
    public function destroyKopPenyedia($id)
    {
        RefKopSurat::destroy($id);
        return back()->with('success', 'Kop surat dihapus.');
    }
    // Ambil notifikasi Penyedia
    public function notifPenyedia($idPenyedia): JsonResponse
    {
        $penyedia = auth('penyedia')->id();

        $notifQuery = DB::table('notifications')
            ->where('role', 'penyedia')
            ->where('to', $penyedia) // notifikasi khusus penyedia
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

    // Tandai satu notifikasi Penyedia sebagai dibaca
    public function markAsReadPenyedia($id)
    {
        DB::table('notifications')
            ->where('id', $id)
            ->update(['is_read' => 1]);

        return response()->json(['success' => true]);
    }

    // Tandai semua notifikasi Penyedia sebagai dibaca
    public function markAllPenyedia($idPenyedia)
    {
        DB::table('notifications')
            ->where('to', $idPenyedia)
            ->update(['is_read' => 1]);

        return response()->json(['message' => 'Semua notifikasi telah ditandai sebagai dibaca.']);
    }
    public function notifUser(): JsonResponse
    {
        $pegawai = auth('pegawai')->user(); // ambil pegawai yang login

        $notifQuery = DB::table('notifications')
            ->where('role', 'pegawai')
            ->where('to', $pegawai->id) // notifikasi khusus pegawai
            ->orderBy('created_at', 'desc');

        $notifData = $notifQuery->get();

        $notifDataUnread = $notifData->where('is_read', 0)->values();
        $notifDataRead = $notifData->where('is_read', 1)->take(5)->values();

        return response()->json([
            'notif' => $notifDataUnread->count(),
            'notifDataUnread' => $notifDataUnread,
            'notifDataRead' => $notifDataRead,
            'total' => $notifDataUnread->count() > 0 ? 1 : 0,
        ]);
    }

    public function markAsReadUser($id)
    {
        DB::table('notifications')
            ->where('id', $id)
            ->update(['is_read' => 1]);

        return response()->json(['success' => true]);
    }

    public function markAllUser(Request $request, $idUser)
    {
        DB::table('notifications')
            ->where('to', $idUser)
            ->where('versi_id', session('versi'))
            ->update(['is_read' => 1]);

        return response()->json(['message' => 'Semua notifikasi telah ditandai sebagai dibaca.']);
    }
    public function getKelompokBarang($nomor_surat_pesanan)
    {
        $kelompok = KelompokBarangPesanan::where('nomor_surat_pesanan', $nomor_surat_pesanan)->get();
        return response()->json($kelompok);
    }
    public function getBmnByRuangan($id_ruangan)
    {
        $bmn = InventarisBmn::where('id_ruangan_bmn', $id_ruangan) // fix FK
            ->whereNotIn('id_inventaris_bmn', function ($query) {
                $query->select('bmn_id')
                    ->from('pemeliharaans')
                    ->whereNotIn('id_ref_status_pemeliharaan', [2, 5, 21]);
            })
            ->select('id_inventaris_bmn', 'nup_bmn', 'nama_bmn', 'kode_bmn')
            ->get();

        return response()->json($bmn);
    }

    public function getAllBmn()
    {
        $bmn = InventarisBmn::whereNotIn('id_inventaris_bmn', function ($query) {
            $query->select('bmn_id')
                ->from('pemeliharaans')
                ->whereNotIn('id_ref_status_pemeliharaan', [2, 5, 21]);
        })
            ->select('id_inventaris_bmn', 'nup_bmn', 'nama_bmn', 'kode_bmn')
            ->get();

        return response()->json($bmn);
    }

    public function getBmnName($id_bmn)
    {
        $bmn = InventarisBmn::select('nama_bmn', 'kode_bmn', 'nup_bmn')
            ->where('id_inventaris_bmn', $id_bmn)
            ->first();

        if ($bmn) {
            return response()->json([
                'nama_bmn' => $bmn->nama_bmn,
                'kode_bmn' => $bmn->kode_bmn,
                'nup_bmn' => $bmn->nup_bmn
            ]);
        } else {
            return response()->json([
                'nama_bmn' => 'Nama tidak ditemukan',
                'kode_bmn' => '-',
                'nup_bmn' => '-'
            ]);
        }
    }

    // Pegawai
    public function index_pegawai()
    {
        $riwayat = Pemeliharaan::with(['bmn', 'status', 'penyedia', 'penolakan'])
            ->where('id_karyawan', Auth::user()->id)
            ->get();
        return view('user.pemeliharaan.pegawai.index', [
            'riwayat' => $riwayat,
            'title' => 'Riwayat Pemeliharaan',
            'active' => 'kegiatanku_perjadin'
        ]);
    }
    public function pengajuan_pegawai()
    {
        $namaPegawai = Karyawan::where('is_aktif', '1')
            ->select('id_karyawan', 'nama_lengkap')
            ->get();

        $ruangan = RuanganBmn::get();

        $idRuanganTerpakai = Pemeliharaan::where('bmn_type', 'ruangan')
            ->whereNotIn('id_ref_status_pemeliharaan', [19, 2])
            ->pluck('bmn_id')
            ->unique()
            ->toArray();

        $ruanganTersedia = RuanganBmn::whereNotIn('id_ruangan_bmn', $idRuanganTerpakai)->get();

        return view('user.pemeliharaan.pegawai.pengajuan', [
            'title' => 'Pemeliharaan BMN',
            'active' => 'pemeliharaan',
            'namaPegawai' => $namaPegawai,
            'ruangan' => $ruangan,
            'ruanganTersedia' => $ruanganTersedia,
        ]);
    }
    public function store_pengajuan_pegawai(Request $request)
    {
        $tanggal = now()->format('dmY');
        $jenisBmnFormatted = ucfirst($request->jenis_bmn);

        if ($jenisBmnFormatted === 'Inventaris') {
            foreach ($request->id_bmn as $id_bmn) {
                $keterangan = $request->keterangan_bmn[$id_bmn] ?? '-';

                // Hitung nomor urut hari ini untuk jenis yang sama
                $countToday = Pemeliharaan::whereDate('created_at', now())
                    ->where('bmn_type', 'inventaris')
                    ->count() + 1;

                $idPemeliharaan = "PM-{$tanggal}-{$jenisBmnFormatted}-{$countToday}";

                Pemeliharaan::create([
                    'id_pemeliharaan' => $idPemeliharaan,
                    'id_karyawan' => $request->id_pegawai,
                    'bmn_id' => $id_bmn,
                    'bmn_type' => 'inventaris', // morphMap alias
                    'id_ref_status_pemeliharaan' => 1,
                    'keterangan' => $keterangan,
                ]);
            }
        } elseif ($jenisBmnFormatted === 'Ruangan') {
            foreach ($request->id_ruangan_ruangan as $id_ruangan) {
                $keterangan = $request->keterangan_ruangan[$id_ruangan] ?? '-';

                $countToday = Pemeliharaan::whereDate('created_at', now())
                    ->where('bmn_type', 'ruangan')
                    ->count() + 1;

                $idPemeliharaan = "PM-{$tanggal}-{$jenisBmnFormatted}-{$countToday}";

                Pemeliharaan::create([
                    'id_pemeliharaan' => $idPemeliharaan,
                    'id_karyawan' => $request->id_pegawai,
                    'bmn_id' => $id_ruangan,
                    'bmn_type' => 'ruangan', // morphMap alias
                    'id_ref_status_pemeliharaan' => 1,
                    'keterangan' => $keterangan,
                ]);
            }
        }
        $pegawai = Karyawan::find($request->id_pegawai);
        Notifikasi::create([
            'from' => $request->id_pegawai,
            'role' => "Pejabat Pemeliharaan",
            'header' => 'Pengajuan Pemeliharaan Baru',
            'message' => "Terdapat pengajuan pemeliharaan baru dari {$pegawai->nama_lengkap}.",
            'route' => "pemeliharaan-admin",
            'is_read' => 0,
            'created_at' => now(),
        ]);
        return redirect('/pemeliharaan-pegawai')->with('success', 'Pengajuan pemeliharaan berhasil dikirim.');
    }


    // Admin
    public function index_admin()
    {
        $roles = auth('administrator')->user()->roles->pluck('nama_role')->toArray(); // Ambil array nama role
        // Default tab berdasarkan role
        $defaultTab = in_array('Bendahara Pemeliharaan', $roles) ? 'pembayaran' : 'pengajuan';

        $tab = request('tab', $defaultTab);

        $riwayat = Pemeliharaan::with([
            'bmn',
            'status',
            'penyedia',
            'pegawai',
            'penolakan',
            'pesanan.penolakan',
            'pesanan.kelompokBarangPesanan',
            'pesanan.buktiPengembalians',
            'pesanan.pembayaranPemeliharaan',
            'pesanan.pembayaranPemeliharaan.penolakan',
        ])->get();

        $dataPenyedia = Data_penyedia::select('id', 'nama_CV')->get();

        $viewData = [
            'riwayat' => $riwayat,
            'dataPenyedia' => $dataPenyedia,
            'title' => 'Pemeliharaan',
            'active' => 'admin_pemeliharaan',
            'activeTab' => $tab,
            'userRole' => $roles,
        ];

        // Cek apakah memiliki salah satu role yang diizinkan
        $allowedRoles = ['Pejabat Pemeliharaan', 'Pejabat Pengadaan', 'PPK', 'Bendahara Pemeliharaan'];

        return count(array_intersect($roles, $allowedRoles)) > 0
            ? view('admin.pemeliharaan.index', $viewData)
            : response('Anda tidak memiliki akses ke halaman ini.', 403);
    }

    public function pengajuan_admin(Request $request)
    {
        $namaPegawai = pegawai::where('is_aktif', '1')->select('id', 'nama_lengkap')->get();
        $ruangan = RuanganBmn::all();

        $idRuanganTerpakai = Pemeliharaan::where('bmn_type', 'ruangan')
            ->whereNotIn('id_ref_status_pemeliharaan', [19, 2])
            ->pluck('bmn_id')
            ->unique()
            ->toArray();

        $ruanganTersedia = RuanganBmn::whereNotIn('id_ruangan_bmn', $idRuanganTerpakai)->get();

        $dataPenyedia = Data_penyedia::select('id', 'nama_CV')->get();

        return view('admin.pemeliharaan.pengajuan', [
            'title' => 'Pemeliharaan BMN',
            'active' => 'pemeliharaan',
            'namaPegawai' => $namaPegawai,
            'ruangan' => $ruangan,
            'ruanganTersedia' => $ruanganTersedia,
            'dataPenyedia' => $dataPenyedia,
        ]);
    }

    public function store_pengajuan_admin(Request $request)
    {
        $jenis = $request->jenis_bmn;

        if ($jenis === 'inventaris') {
            foreach ($request->id_bmn as $id_bmn) {
                $keterangan = $request->keterangan_bmn[$id_bmn] ?? '-';

                Pemeliharaan::create([
                    'id_karyawan' => $request->id_admin,
                    'bmn_id' => $id_bmn,
                    'bmn_type' => 'inventaris', // pakai alias morphMap
                    'id_ref_status_pemeliharaan' => 3,
                    'keterangan' => $keterangan,
                ]);
            }
        } elseif ($jenis === 'ruangan') {
            foreach ($request->id_ruangan_ruangan as $id_ruangan) {
                $keterangan = $request->keterangan_ruangan[$id_ruangan] ?? '-';

                Pemeliharaan::create([
                    'id_karyawan' => $request->id_admin,
                    'bmn_id' => $id_ruangan,
                    'bmn_type' => 'ruangan', // morphMap alias
                    'id_ref_status_pemeliharaan' => 3,
                    'keterangan' => $keterangan,
                ]);
            }
        }
        return redirect('/pemeliharaan-admin')->with('success', 'Pengajuan pemeliharaan berhasil dikirim.');
    }
    public function terima_bmn(Request $request)
    {
        DB::beginTransaction();
        try {
            $files = $request->file('url_bukti');
        if(!$files || count($files) === 0) {
            return back()->with('error', 'Tidak ada file yang dipilih.');
        }
        
        foreach ($files as $i => $file) {
            if(!$file) continue; // skip jika input file kosong
            $originalName = $request->nama_file[$i];
            $timestamp = now()->format('YmdHis');
            $extension = $file->getClientOriginalExtension();
            $slugified = Str::slug($originalName, '_');
            $filename = "pengembalian_{$slugified}_{$timestamp}.{$extension}";
            $path = $file->storeAs('dokumen', $filename, 'public');
        
            BuktiPengembalian::create([
                'nomor_surat_pesanan' => $request->nomor_surat_pesanan,
                'nama_file' => $originalName,
                'url_bukti' => $path
            ]);
        }

            // Waktu update sekarang
            $now = now();

            // Update semua pemeliharaan terkait
            $pemeliharaans = Pemeliharaan::where('nomor_surat_pesanan', $request->nomor_surat_pesanan)->get();
            foreach ($pemeliharaans as $pemeliharaan) {
                $pemeliharaan->id_ref_status_pemeliharaan = 15;
                $pemeliharaan->updated_at = $now;
                $pemeliharaan->save();

                // Cek jika bmn_type = 'inventaris' dan id_Data_penyedia == 0
                if ($pemeliharaan->bmn_type === 'inventaris' && $pemeliharaan->id_karyawan == 0 && $pemeliharaan->bmn) {
                    $bmn = $pemeliharaan->bmn;
                    $periode = (int) $bmn->periode_pemeliharaan;
                    $bmn->jadwal_pemeliharaan = $now->copy()->addMonths($periode);
                    $bmn->save();
                }
            }
            $firstPemeliharaan = $pemeliharaan->first();
            Notifikasi::create([
                'from'      => auth('administrator')->id(),
                'to'        => $firstPemeliharaan->id_karyawan,
                'role'      => 'pegawai',
                'header'    => 'Pemeliharaan Selesai',
                'message'   => "Pengajuan pemeliharaan anda telah selesai diperbaiki.",
                'route'     => 'pemeliharaan-pegawai',
                'is_read'   => 0,
                'created_at' => now(),
            ]);
            Notifikasi::create([
                'from'      => auth('administrator')->id(),
                'to'        => $firstPemeliharaan->id_data_penyedia,
                'role'      => 'penyedia',
                'header'    => 'Pemeliharaan Berhasil Dikonfirmasi',
                'message'   => "Pemeliharaan BMN berhasil dikonfirmasi pengembalian/penyelesaian oleh instansi.",
                'route'     => 'penyedia?tab=monitor',
                'is_read'   => 0,
                'created_at' => now(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Bukti berhasil dikirim & pengembalian dikonfirmasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan bukti.');
        }
    }

    // Pejabat Pemeliharaan
    public function pphSetujuiPengajuan(Request $request)
    {
        Pemeliharaan::where('id_pemeliharaan', $request->id)->update([
            'id_ref_status_pemeliharaan' => 3,
            'tgl_pemeriksaan' => $request->tgl_pemeriksaan ?? now()->format('Y-m-d'),
        ]);

        Notifikasi::create([
            'from' => auth('administrator')->id(),
            'role' => "Pejabat Pengadaan",
            'header' => 'Pengajuan Pemeliharaan Baru',
            'message' => "Terdapat pengajuan pemeliharaan yang siap dibuatkan surat pesanan.",
            'route' => "pemeliharaan-admin",
            'is_read' => 0,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan berhasil disetujui.');
    }
    public function tolak_pengajuan_pegawai(Request $request)
    {
        $ids = $request->selected_ids ?? [];
        $alasan = $request->alasan ?? [];
        $userRoles = explode(',', $request->user_role ?? '');

        if (empty($ids)) {
            return back()->with('error', 'Tidak ada item yang dipilih.');
        }

        $pegawaiNotifikasi = [];

        foreach ($ids as $id) {
            $pemeliharaan = Pemeliharaan::find($id);
            if (!$pemeliharaan) continue;

            // Ubah status berdasarkan role
            if (in_array('PPK', $userRoles)) {
                $pemeliharaan->id_ref_status_pemeliharaan = 5;
            } else {
                $pemeliharaan->id_ref_status_pemeliharaan = 2; // ditolak biasa
            }
            $pemeliharaan->save();

            $now = now();

            // Update jadwal pemeliharaan jika perlu
            if ($pemeliharaan->bmn_type === 'inventaris' && $pemeliharaan->id_karyawan == 0 && $pemeliharaan->bmn) {
                $bmn = $pemeliharaan->bmn;
                $periode = (int) $bmn->periode_pemeliharaan;
                $bmn->jadwal_pemeliharaan = $now->copy()->addMonths($periode);
                $bmn->save();
            }

            // Simpan alasan penolakan
            $pemeliharaan->penolakan()->create([
                'alasan_penolakan' => $alasan[$id] ?? ($pemeliharaan->penolakan->last()?->alasan_penolakan ?? 'Ditolak tanpa alasan'),
                'entitas_type'     => 'per_bmn',
                'entitas_id'       => $pemeliharaan->id_pemeliharaan,
                'created_at'       => now(),
            ]);

            // Kumpulkan ID pegawai unik untuk notifikasi
            if ($pemeliharaan->id_karyawan) {
                $pegawaiNotifikasi[$pemeliharaan->id_karyawan] = $pemeliharaan->id_karyawan;
            }
        }

        // Loop pegawai unik dan kirim notifikasi
        foreach ($pegawaiNotifikasi as $idKaryawan) {
            Notifikasi::create([
                'from'       => auth('administrator')->id(),
                'to'         => $idKaryawan,
                'role'       => 'pegawai',
                'header'     => 'Penolakan Pengajuan Pemeliharaan',
                'message'    => 'Pengajuan pemeliharaan anda ditolak.',
                'route'      => 'pemeliharaan-pegawai',
                'is_read'    => 0,
                'created_at' => now(),
            ]);
        }

        return back()->with('success', 'Penolakan berhasil disimpan.');
    }
    public function buat_pesanan(Request $request)
    {
        $authAdmin = auth('administrator')->user();
        $pegawai = Karyawan::where('nama_lengkap', $authAdmin->username)->first();
        $nipNik = $pegawai?->NIP_NIK ?? '-';

        $ids = $request->selected_ids ?? [];
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal 1 item terlebih dahulu.');
        }

        // Ambil data surat terakhir
        $currentYear = now()->year;
        // Data lainnya
        $ketBmn = Pemeliharaan::with('bmn')
            ->whereIn('id_pemeliharaan', $ids)
            ->get()
            ->map(function ($pemeliharaan) {
                return [
                    'nama_bmn' => $pemeliharaan->bmn->nama_bmn ?? $pemeliharaan->bmn->nama_ruangan,
                    'kategori_bmn' => $pemeliharaan->bmn->kategori_bmn ?? 'Ruangan',
                    'keterangan' => $pemeliharaan->keterangan,
                    'merk_bmn' => $pemeliharaan->bmn->merk_bmn ?? '-',
                ];
            })
            ->values()
            ->toArray();

        $kategoriBmn = collect($ketBmn)->pluck('kategori_bmn')->unique()->values()->toArray();
        $penyedias = Data_penyedia::all();
        $admins = Administrator::all();
        $kodeInstansi = Ref_rkakl_satker::all();
        $kodeLayanan = RefKodeLayananSurat::all();
        $kopSurat = RefKopSurat::where('is_aktif', 1)
            ->where('pemilik', 0)
            ->latest()
            ->first();

        return view('admin.pemeliharaan.buat_pesanan', compact(
            'penyedias',
            'admins',
            'kategoriBmn',
            'ketBmn',
            'ids',
            'kodeInstansi',
            'kodeLayanan',
            'kopSurat',
            'nipNik'
        ) + [
            'title' => 'Buat Pesanan',
            'active' => 'admin_buat_pesanan',
            'selected_ids' => $ids
        ]);
    }

    public function store_pesanan(Request $request)
    {
        $kopSuratPath = RefKopSurat::where('is_aktif', 1)
            ->where('pemilik', 0)
            ->latest()
            ->first();

        // ambil nomorSuratFinal dari request
        $nomorSurat = $request->nomorSuratFinal;

        $filename = 'pesanan_' . str_replace(['/', ' '], '_', $nomorSurat) . '.pdf';

        $bmnData = collect(json_decode($request->bmn_list_json ?? '[]', true));
        $penyedia = Data_penyedia::find($request->pihak_kedua);
        $penanda = Administrator::find($request->penanda_tangan);
        $pegawai = Karyawan::where('nama_lengkap', $penanda->username)->first();
        $nipNik = $pegawai?->NIP_NIK ?? '-';

        $kategoriBmn = $bmnData->pluck('nama_bmn')->unique();

        $pdf = Pdf::loadView('admin.pemeliharaan.pesanan', [
            'kop' => $kopSuratPath,
            'nomorSurat' => $nomorSurat,
            'tanggal' => $request->tanggal,
            'perihal' => $request->perihal,
            'penyedia' => $penyedia,
            'penanda' => $penanda,
            'bmnData' => $bmnData, // berisi nama_bmn + merk_bmn + jumlah
            'keterangan' => $request->keterangan_bmn,
            'opening' => $request->opening,
            'ending' => $request->ending,
            'kategoriBmn' => $kategoriBmn,
            'nipNik' => $nipNik,
        ])->setOption('fontDir', public_path('/assets/fonts'));

        $path = 'dokumen/' . $filename;
        Storage::disk('public')->put($path, $pdf->output());
        $urlSurat = Storage::url($path);

        // Simpan ke SuratPemeliharaan
        SuratPemeliharaan::create([
            'nomor_surat' => $nomorSurat,
            'perihal' => 'PESANAN-' . $request->perihal,
            'url_surat' => $urlSurat,
            'created_at' => $request->tanggal,
        ]);

        // Simpan KelompokBarangPesanan
        foreach ($bmnData as $item) {
            KelompokBarangPesanan::create([
                'nomor_surat_pesanan' => $nomorSurat,
                'nama_bmn' => $item['nama_bmn'],
                'merk_bmn' => $item['merk_bmn'], // tambahkan merk_bmn
                'jumlah_bmn' => $item['jumlah'],
            ]);
        }

        // Update Pemeliharaan
        Pemeliharaan::whereIn('id_pemeliharaan', $request->selected_ids ?? [])->update([
            'nomor_surat_pesanan' => $nomorSurat,
            'id_ref_status_pemeliharaan' => 4,
            'id_data_penyedia' => $request->pihak_kedua,
        ]);

        // Notifikasi
        Notifikasi::create([
            'from' => auth('administrator')->id(),
            'role' => "PPK",
            'header' => 'Pengajuan Pemeliharaan Baru',
            'message' => "Terdapat surat pesanan yang siap diperiksa.",
            'route' => "pemeliharaan-admin?tab=monitor",
            'is_read' => 0,
            'created_at' => now(),
        ]);
        return response()->json(['success' => true, 'link' => $urlSurat]);
    }

    public function previewPesanan(Request $request)
    {
        $kopSurat = RefKopSurat::where('is_aktif', 1)
            ->where('pemilik', 0)
            ->latest()
            ->first();
        $nomorSurat = '13/LL4/LL/2025';
        $tanggal = now()->toDateString();
        $perihal = 'Perawatan Barang';
        $penyedia = Data_penyedia::first(); // contoh data penyedia
        $penanda = Administrator::first();
        $pegawai = Karyawan::where('nama_lengkap', $penanda->username)->first();
        $nipNik = $pegawai?->NIP_NIK ?? '-';

        $bmnData = collect([
            ['nama_bmn' => 'Laptop', 'jumlah' => 2],
            ['nama_bmn' => 'Printer', 'jumlah' => 1],
        ]);

        return view('admin.pemeliharaan.pesanan', [
            'kop' => $kopSurat, // ← kembalikan sebagai object, bukan string path
            'nomorSurat' => $nomorSurat,
            'tanggal' => $tanggal,
            'perihal' => $perihal,
            'penyedia' => $penyedia,
            'penanda' => $penanda,
            'bmnData' => $bmnData,
            'keterangan' => 'Contoh keterangan untuk tiap barang',
            'opening' => 'Dengan ini kami meminta Saudara dapat melaksanakan perawatan...',
            'ending' => 'Jika ada pertanyaan lebih lanjut, hubungi kami di...',
            'kategoriBmn' => $bmnData->pluck('nama_bmn')->unique(),
            'nipNik' => $nipNik,
        ]);
    }


    //  Bendahara
    public function bendaharaSelesai(Request $request)
    {
        $idPesananList = $request->input('nomor_surat_pesanan');
        $nomor = $request->input('nomor_perintah_bayar');

        if (!is_array($idPesananList) || empty($idPesananList) || !$nomor) {
            return response()->json(['error' => 'Data tidak lengkap.'], 400);
        }

        // Update status pesanan menjadi 21 (selesai bayar)
        Pemeliharaan::whereIn('nomor_surat_pesanan', $idPesananList)
            ->update(['id_ref_status_pemeliharaan' => 21]);

        // Ambil salah satu id_pembayaran_pemeliharaan dari relasi pesanan
        $pembayaran = Pemeliharaan::whereIn('nomor_surat_pesanan', $idPesananList)
            ->with('pesanan.pembayaranPemeliharaan')
            ->get()
            ->pluck('pesanan')
            ->filter()
            ->pluck('pembayaranPemeliharaan')
            ->filter()
            ->first();

        if ($pembayaran) {
            $pembayaran->update(['nomor_perintah_bayar' => $nomor]);
        }
        $pemeliharaan = Pemeliharaan::with('penyedia')
            ->whereIn('nomor_surat_pesanan', $idPesananList)
            ->first();

        Notifikasi::create([
            'from'      => auth('administrator')->id(),
            'to'        => $pemeliharaan->id_data_penyedia,
            'role'      => 'penyedia',
            'header'    => 'Pembayaran Pemeliharaan Selesai',
            'message'   => "Pengajuan pembayaran pemeliharaan anda telah selesai diproses dengan nomor surat perintah membayar: $nomor.",
            'route'     => 'penyedia?tab=pembayaran',
            'is_read'   => 0,
            'created_at' => now(),
        ]);
        return response()->json(['success' => 'Status & nomor perintah bayar berhasil disimpan.']);
    }

    public function bendahara_tolak_pengajuan_bayar(Request $request)
    {
        $pesananIds = json_decode($request->pesanan_ids, true);

        if (!is_array($pesananIds) || empty($pesananIds)) {
            return back()->with('error', 'Data pesanan tidak valid.');
        }

        // Ambil id pembayaran dari salah satu pesanan
        $pembayaranId = SuratPemeliharaan::whereIn('nomor_surat', $pesananIds)
            ->value('id_pembayaran_pemeliharaan');

        if (!$pembayaranId) {
            return back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        // Simpan penolakan
        Penolakan::create([
            'alasan_penolakan' => $request->alasan_penolakan,
            'entitas_type' => 'pengajuan_pembayaran',
            'entitas_id' => $pembayaranId,
            'created_at' => now(),
        ]);

        // Update status pemeliharaan jadi 20
        Pemeliharaan::whereIn('nomor_surat_pesanan', $pesananIds)->update([
            'id_ref_status_pemeliharaan' => 20,
            'updated_at' => now(),
        ]);
        $pemeliharaan = Pemeliharaan::with('penyedia')
            ->whereIn('nomor_surat_pesanan', $pesananIds)
            ->first();

        Notifikasi::create([
            'from'      => auth('administrator')->id(),
            'to'        => $pemeliharaan->id_data_penyedia,
            'role'      => 'penyedia',
            'header'    => 'Penolakan Pengajuan Pembayaran oleh Bendahara',
            'message'   => "Pengajuan pembayaran pemeliharaan anda ditolak oleh bendahara, silakan membuat ulang pengajuan pembayaran.",
            'route'     => 'penyedia?tab=pembayaran',
            'is_read'   => 0,
            'created_at' => now(),
        ]);
        return back()->with('success', 'Pengajuan pembayaran berhasil ditolak oleh Bendahara.');
    }


    // Pejabat Pengadaan
    public function ppgKonfirmasiPengambilan($nomor_surat_pesanan)
    {
        Pemeliharaan::where('nomor_surat_pesanan', $nomor_surat_pesanan)
            ->update([
                'id_ref_status_pemeliharaan' => 9,
                'updated_at' => now(),
            ]);
        $pemeliharaan = Pemeliharaan::with('penyedia')
            ->where('nomor_surat_pesanan', $nomor_surat_pesanan)
            ->first();

        Notifikasi::create([
            'from' => auth('penyedia')->id(),
            'to' => $pemeliharaan->id_data_penyedia,
            'role' => "penyedia",
            'header' => 'Pengambilan BMN Telah Dikonfirmasi',
            'message' => "Pengambilan BMN berdasarkan surat pesanan nomor $nomor_surat_pesanan telah dikonfirmasi oleh LLDIKTI IV.",
            'route' => "penyedia?tab=monitor",
            'is_read' => 0,
            'created_at' => now(),
        ]);
        return response()->json([
            'success' => true,
            'message' => "Pengambilan pesanan dengan ID $nomor_surat_pesanan berhasil dikonfirmasi."
        ]);
    }
    public function tolakPenawaranPP(Request $request)
    {

        DB::transaction(function () use ($request) {
            // Update semua pemeliharaan terkait pesanan ini
            Pemeliharaan::where('nomor_surat_pesanan', $request->nomor_surat_pesanan)
                ->update(['id_ref_status_pemeliharaan' => 11]);

            // Simpan penolakan dengan relasi polymorphic ke SuratPemeliharaan
            Penolakan::create([
                'entitas_type' => 'pesanan_bap',
                'entitas_id' => $request->nomor_surat_pesanan,
                'alasan_penolakan' => $request->alasan_penolakan,
                'created_at' => now(),
            ]);
        });
        $pemeliharaan = Pemeliharaan::with('penyedia')
            ->where('nomor_surat_pesanan', $request->nomor_surat_pesanan)
            ->first();

        Notifikasi::create([
            'from' => auth('administrator')->id(),
            'to' => $pemeliharaan->id_data_penyedia,
            'role' => "penyedia",
            'header' => 'Penawaran Harga Pemeliharaan Gagal',
            'message' => "Penawaran harga surat pesanan dengan nomor $request->nomor_surat_pesanan dibatalkan.",
            'route' => "penyedia",
            'is_read' => 0,
            'created_at' => now(),
        ]);
        return response()->json(['message' => 'Penawaran berhasil ditolak.']);
    }

    public function kirimPenawaranPP(Request $request)
    {
        foreach ($request->nilai_nego_pp as $id => $nilai) {
            $cleanValue = (int) str_replace('.', '', $nilai);
            KelompokBarangPesanan::where('id_kelompok_barang_pesanan', $id)->update([
                'nilai_nego_pp' => $cleanValue
            ]);
        }

        Pemeliharaan::where('nomor_surat_pesanan', $request->nomor_surat_pesanan)
            ->update(['id_ref_status_pemeliharaan' => 9]);

        return response()->json(['message' => 'Penawaran berhasil dikirim.']);
    }

    public function setujuiPenawaranPP(Request $request)
    {
        $barangList = KelompokBarangPesanan::where('nomor_surat_pesanan', $request->nomor_surat_pesanan)->get();

        foreach ($barangList as $barang) {
            KelompokBarangPesanan::where('id_kelompok_barang_pesanan', $barang->id_kelompok_barang_pesanan)
                ->update(['nilai_disepakati' => $barang->nilai_nego_penyedia]);
        }

        Pemeliharaan::where('nomor_surat_pesanan', $request->nomor_surat_pesanan)
            ->update(['id_ref_status_pemeliharaan' => 12]);

        $pemeliharaan = Pemeliharaan::with('penyedia')
            ->where('nomor_surat_pesanan', $request->nomor_surat_pesanan)
            ->first();

        Notifikasi::create([
            'from' => auth('administrator')->id(),
            'to' => $pemeliharaan->id_data_penyedia,
            'role' => "penyedia",
            'header' => 'Penawaran Harga',
            'message' => "Pejabat pengadaan telah menyetujui penawaran harga pemeliharaan.",
            'route' => "penyedia?tab=monitor",
            'is_read' => 0,
            'created_at' => now(),
        ]);
        return response()->json(['message' => 'Penawaran berhasil disetujui.']);
    }

    // PPK
    public function ppk_tolak_pengajuan_bayar(Request $request)
    {
        $pesananIds = json_decode($request->pesanan_ids, true);

        // Ambil id pembayaran dari salah satu pesanan
        $pembayaranId = SuratPemeliharaan::whereIn('nomor_surat', $pesananIds)
            ->value('id_pembayaran_pemeliharaan');

        if (!$pembayaranId) {
            return back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        Penolakan::create([
            'alasan_penolakan' => $request->alasan_penolakan,
            'entitas_type' => 'pengajuan_pembayaran',
            'entitas_id' => $pembayaranId,
            'created_at' => now(),
        ]);

        // Update status pemeliharaan (jika perlu)
        Pemeliharaan::whereIn('nomor_surat_pesanan', $pesananIds)->update([
            'id_ref_status_pemeliharaan' => 17,
            'updated_at' => now(),
        ]);
        $pemeliharaan = Pemeliharaan::with('penyedia')
            ->whereIn('nomor_surat_pesanan', $pesananIds)
            ->first();
        Notifikasi::create([
            'from'      => auth('administrator')->id(),
            'to'        => $pemeliharaan->id_data_penyedia,
            'role'      => 'penyedia',
            'header'    => 'Pengajuan Pembayaran Ditolak PPK',
            'message'   => "Pengajuan pembayaran anda ditolak oleh PPK, silakan melakukan pengajuan ulang.",
            'route'     => 'penyedia?tab=pembayaran',
            'is_read'   => 0,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan pembayaran berhasil ditolak.');
    }
    public function ppkSetujuiPesanan($nomor_surat_pesanan)
    {
        Pemeliharaan::where('nomor_surat_pesanan', $nomor_surat_pesanan)
            ->update([
                'id_ref_status_pemeliharaan' => 6,
                'updated_at' => now(),
            ]);
        $pemeliharaan = Pemeliharaan::with('penyedia')
            ->where('nomor_surat_pesanan', $nomor_surat_pesanan)
            ->first();

        Notifikasi::create([
            'from' => auth('administrator')->id(),
            'to' => $pemeliharaan->id_data_penyedia,
            'role' => "penyedia",
            'header' => 'Pengajuan Pemeliharaan Baru',
            'message' => "Terdapat pengajuan pemeliharaan baru dengan nomor surat pesanan $nomor_surat_pesanan.",
            'route' => "penyedia",
            'is_read' => 0,
            'created_at' => now(),
        ]);
        return response()->json([
            'success' => true,
            'message' => "Pesanan dengan nomor $nomor_surat_pesanan berhasil disetujui."
        ]);
    }
    public function buatBAP(Request $request)
    {
        $pesananIds = $request->input('pesanan');
        if (is_string($pesananIds)) {
            $pesananIds = explode(',', $pesananIds);
        }

        $pesananList = SuratPemeliharaan::with(['kelompokBarangPesanan', 'pembayaranPemeliharaan'])
            ->whereIn('nomor_surat', $pesananIds)
            ->get();
        $nilai_pekerjaan = $pesananList->mapWithKeys(function ($pesanan) {
            return [$pesanan->nomor_surat => $pesanan->kelompokBarangPesanan->sum('nilai_disepakati')];
        });
        $perihal = $pesananList->pluck('perihal')->unique()->implode(', ');

        $firstPesanan = $pesananList->first();

        $penyedia = Pemeliharaan::where('nomor_surat_pesanan', $firstPesanan->nomor_surat)
            ->with('penyedia')
            ->first()?->penyedia;

        $ppn = Ppn::first();
        $nilaiPPN = $ppn?->nilai_ppn ?? 0;

        $pegawai = null;
        $adminPPK = Administrator::all()->first(fn($admin) => $admin->hasRole('PPK'));

        if ($adminPPK) {
            $pegawai = Karyawan::where('nama_lengkap', $adminPPK->username)->first();
        }

        $username = $pegawai->nama_lengkap;
        $kopSurat = RefKopSurat::where('is_aktif', 1)
            ->where('pemilik', 0)
            ->latest()
            ->first();

        return view('admin.pemeliharaan.buat_bap', compact(
            'perihal',
            'pesananList',
            'nilai_pekerjaan',
            'penyedia',
            'pegawai',
            'username',
            'ppn',
            'kopSurat'
        ));
    }
    public function kirimBAP(Request $request)
    {
        DB::beginTransaction();

        try {
            $idPesanans = $request->input('pesanan');
            if (!is_array($idPesanans) || empty($idPesanans)) {
                throw new \Exception("ID Pesanan tidak valid.");
            }

            // Decode PDF
            $bastContent = base64_decode($request->input('pdf_bast'));
            $bapContent  = base64_decode($request->input('pdf_bap'));
            if (!$bastContent || !$bapContent) {
                throw new \Exception("Gagal mengkonversi PDF, file kosong atau rusak.");
            }

            // Cari id_pembayaran_pemeliharaan
            $idPembayaran = SuratPemeliharaan::whereIn('nomor_surat', $idPesanans)
                ->whereNotNull('id_pembayaran_pemeliharaan')
                ->value('id_pembayaran_pemeliharaan');

            if (!$idPembayaran) {
                throw new \Exception("Tidak ditemukan id_pembayaran_pemeliharaan terkait dengan pesanan.");
            }

            // Nama file pakai nomor surat (replace "/" jadi "-")
            $nomorBastFile = str_replace('/', '-', $request->nomor_bast);
            $nomorBapFile  = str_replace('/', '-', $request->nomor_bap);

            $pathBast = "dokumen/BAST-{$nomorBastFile}.pdf";
            $pathBap  = "dokumen/BAP-{$nomorBapFile}.pdf";

            Storage::disk('public')->put($pathBast, $bastContent);
            Storage::disk('public')->put($pathBap, $bapContent);

            // Buat SuratPemeliharaan untuk BAST
            SuratPemeliharaan::create([
                'nomor_surat' => $request->nomor_bast,
                'perihal' => 'BAST-' . Carbon::now()->format('dmY'),
                'id_pembayaran_pemeliharaan' => $idPembayaran,
                'url_surat' => Storage::url($pathBast),
                'created_at' => $request->tanggal_bast,
            ]);

            // Buat SuratPemeliharaan untuk BAP
            SuratPemeliharaan::create([
                'nomor_surat' => $request->nomor_bap,
                'perihal' => 'BAP-' . Carbon::now()->format('dmY'),
                'id_pembayaran_pemeliharaan' => $idPembayaran,
                'url_surat' => Storage::url($pathBap),
                'created_at' => $request->tanggal_bap,
            ]);

            // Update status pemeliharaan jadi 18
            Pemeliharaan::whereIn('nomor_surat_pesanan', $idPesanans)
                ->update(['id_ref_status_pemeliharaan' => 18]);

            DB::commit();
            $pemeliharaan = Pemeliharaan::with('penyedia')
                ->whereIn('nomor_surat_pesanan', $idPesanans)
                ->first();

            Notifikasi::create([
                'from'      => auth('administrator')->id(),
                'to'        => $pemeliharaan->id_data_penyedia,
                'role'      => 'penyedia',
                'header'    => 'Tanda Tangan BAP',
                'message'   => "Berita Acara Pembayaran dan Berita Acara Serah Terima telah dibuat, silakan ditanda tangani untuk melanjutkan proses pembayaran.",
                'route'     => 'pemeliharaan-pegawai',
                'is_read'   => 0,
                'created_at' => now(),
            ]);
            return response()->json([
                'success' => true,
                'message' => 'BAST & BAP berhasil ditautkan ke semua pembayaran terkait.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan dokumen: ' . $e->getMessage()
            ], 500);
        }
    }
    //Penyedia
    public function index_penyedia()
    {
        $tab = request('tab', 'pengajuan'); // default: pengajuan 
        $riwayat = Pemeliharaan::with([
            'bmn',
            'status',
            'penyedia',
            'pegawai',
            'penolakan',
            'pesanan.penolakan',
            'pesanan.kelompokBarangPesanan',
            'pesanan.buktiPengembalians',
            'pesanan.pembayaranPemeliharaan',
            'pesanan.pembayaranPemeliharaan.penolakan',
        ])->where('id_data_penyedia', Auth::guard('penyedia')->id())
            ->get();
        return view('user.pemeliharaan.penyedia.index', [
            'riwayat' => $riwayat,
            'title' => 'Pemeliharaan',
            'active' => 'penyedia_pemeliharaan',
            'activeTab' => $tab,
        ]);
    }
    public function penyedia_terima_pesanan($nomor_surat_pesanan)
    {
        $semuaRuangan = Pemeliharaan::where('nomor_surat_pesanan', $nomor_surat_pesanan)
            ->pluck('bmn_type')
            ->every(fn($type) => $type === 'ruangan');

        $newStatus = $semuaRuangan ? 9 : 8;

        Pemeliharaan::where('nomor_surat_pesanan', $nomor_surat_pesanan)
            ->where('id_ref_status_pemeliharaan', 6)
            ->update([
                'id_ref_status_pemeliharaan' => $newStatus,
                'updated_at' => now(),
            ]);
        $penyedia = auth('penyedia')->user()->nama_CV;
        Notifikasi::create([
            'from' => auth('penyedia')->id(),
            'role' => "Pejabat Pengadaan",
            'header' => 'Surat Pesanan Diterima',
            'message' => "Surat pesanan nomor $nomor_surat_pesanan telah diterima oleh $penyedia.",
            'route' => "pemeliharaan-admin?tab=monitor",
            'is_read' => 0,
            'created_at' => now(),
        ]);
        return response()->json(['success' => true]);
    }
    public function penyedia_tolak(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string|max:1000',
        ]);

        // Ambil semua entri pemeliharaan dengan nomor_surat_pesanan yang sama
        $pemeliharaan = Pemeliharaan::with('penyedia')
            ->where('nomor_surat_pesanan', $id)
            ->get();

        if ($pemeliharaan->isEmpty()) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Update status semua pemeliharaan
        foreach ($pemeliharaan as $item) {
            $item->update(['id_ref_status_pemeliharaan' => 7]);
        }

        // Simpan hanya satu entri penolakan untuk satu entitas pesanan
        Penolakan::create([
            'entitas_type' => 'pesanan_bap',
            'entitas_id' => $id,
            'alasan_penolakan' => $request->alasan,
            'created_at' => now(),
        ]);
        $firstPemeliharaan = $pemeliharaan->first();
        $penyedia = $firstPemeliharaan->penyedia->nama_CV;
        Notifikasi::create([
            'from' => $firstPemeliharaan->id_data_penyedia,
            'role' => "Pejabat Pengadaan",
            'header' => 'Pengajuan Pemeliharaan Ditolak Penyedia',
            'message' => "Pengajuan pemeliharaan dengan surat pesanan nomor $firstPemeliharaan->nomor_surat_pesanan ditolak oleh $penyedia.",
            'route' => "pemeliharaan-admin?tab=monitor",
            'is_read' => 0,
            'created_at' => now(),
        ]);
        return response()->json(['message' => 'Pengajuan ditolak dan alasan berhasil disimpan.']);
    }
    public function penyediaTawarkanHarga(Request $request)
    {
        $idPesanan = $request->nomor_surat_pesanan;

        foreach ($request->nilai_nego as $id => $nilai) {
            // Bersihkan format "1.000.000" menjadi integer murni
            $nilaiInt = (int) str_replace('.', '', $nilai);

            KelompokBarangPesanan::where('id_kelompok_barang_pesanan', $id)
                ->update(['nilai_nego_penyedia' => $nilaiInt]);
        }

        // Update status semua pemeliharaan yang terkait
        Pemeliharaan::where('nomor_surat_pesanan', $idPesanan)
            ->update(['id_ref_status_pemeliharaan' => 10]);

        $penyedia = auth('penyedia')->user()->nama_CV;
        Notifikasi::create([
            'from' => auth('penyedia')->id(),
            'role' => "Pejabat Pengadaan",
            'header' => 'Penawaran Harga',
            'message' => "$penyedia telah mengirimkan penawaran harga pemeliharaan.",
            'route' => "pemeliharaan-admin?tab=monitor",
            'is_read' => 0,
            'created_at' => now(),
        ]);
        return response()->json(['message' => 'Tawaran harga berhasil dikirim.']);
    }
    // Tambahan untuk Setujui Penawaran oleh Penyedia
    public function penyediaSetujuiPenawaran(Request $request)
    {
        $idPesanan = $request->nomor_surat_pesanan;

        // Ambil nilai_nego_pp yang tersedia
        $kelompok = KelompokBarangPesanan::where('nomor_surat_pesanan', $idPesanan)->get();

        foreach ($kelompok as $item) {
            if ($item->nilai_nego_pp) {
                $item->update(['nilai_disepakati' => $item->nilai_nego_pp]);
            }
        }

        Pemeliharaan::where('nomor_surat_pesanan', $idPesanan)
            ->update(['id_ref_status_pemeliharaan' => 12]);

        $penyedia = auth('penyedia')->user()->nama_CV;

        Notifikasi::create([
            'from' => auth('penyedia')->id(),
            'role' => "Pejabat Pengadaan",
            'header' => 'Penawaran Harga',
            'message' => "$penyedia telah menyetujui penawaran harga pemeliharaan.",
            'route' => "pemeliharaan-admin?tab=monitor",
            'is_read' => 0,
            'created_at' => now(),
        ]);

        return response()->json(['message' => 'Penawaran berhasil disetujui.']);
    }

    public function kembalikanPemeliharaan($id)
    {
        try {
            $pemeliharaan = Pemeliharaan::with('penyedia')
                ->where('nomor_surat_pesanan', $id)
                ->get();

            $allRuangan = $pemeliharaan->pluck('bmn_type')->unique()->count() === 1
                && $pemeliharaan->first()->bmn_type === 'ruangan';

            $newStatus = $allRuangan ? 14 : 13;
            $message = $allRuangan
                ? 'Status pemeliharaan sedang menunggu konfirmasi selesai.'
                : 'Status pemeliharaan sedang dikembalikan.';

            Pemeliharaan::where('nomor_surat_pesanan', $id)->update([
                'id_ref_status_pemeliharaan' => $newStatus,
                'updated_at' => now()
            ]);
            $penyedia = auth('penyedia')->user()->nama_CV;
            Notifikasi::create([
                'from' => auth('penyedia')->id(),
                'role' => "Pejabat Pengadaan",
                'header' => 'Pengembalian/Penyelesaian Pemeliharaan',
                'message' => "$penyedia telah menyelesaikan pemeliharaan dan menunggu konfirmasi pengembalian/penyelesaian.",
                'route' => "pemeliharaan-admin?tab=monitor",
                'is_read' => 0,
                'created_at' => now(),
            ]);
            return response()->json(['message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan.'], 500);
        }
    }
    public function penyedia_pengajuan_pembayaran(Request $request)
    {
        $ids = $request->input('nomor_surat_pesanan', []);
        $pemilik = auth('penyedia')->id();
        $kopSurat = RefKopSurat::where('is_aktif', 1)
                    ->where('pemilik', $pemilik)
                    ->latest()
                    ->first();
        $data = SuratPemeliharaan::with('kelompokBarangPesanan')
            ->whereIn('nomor_surat', $ids)
            ->get()
            ->map(function ($pesanan) {
                return [
                    'nomor_surat_pesanan' => $pesanan->nomor_surat,
                    'perihal' => $pesanan->perihal,
                    'pesanan' => $pesanan->url_surat,
                    'nilai_kontrak' => $pesanan->kelompokBarangPesanan->sum('nilai_disepakati')
                ];
            });

        return view('user.pemeliharaan.penyedia.buat_pengajuan_bayar', compact('data', 'kopSurat'));
    }
    public function penyediaGantiPassword(Request $request)
    {
        $penyedia = Data_penyedia::find(Auth::guard('penyedia')->id());

        if (!Hash::check($request->password_lama, $penyedia->password)) {
            return back()->with('error', 'Password lama anda salah.');
        }


        $penyedia->password = bcrypt($request->password_baru);
        $penyedia->save();

        return back()->with('success', 'Password berhasil diperbarui.');
    }
    public function penyedia_simpan_pengajuan(Request $request)
    {
        // Simpan PDF utama
        $timestamp = now()->format('Ymd_His');
        $filename = 'pengajuan_bayar_' . preg_replace('/[^A-Za-z0-9]/', '_', $request->nomor_surat) . '_' . $timestamp . '.pdf';
        $path = $request->file('pdf_file')->storeAs('dokumen', $filename, 'public');
        $link = Storage::url($path);

        // Ambil nilai PPN (selalu ambil row pertama)
        $ppn = Ppn::first()?->nilai_ppn ?? 0;

        // Buat entri pembayaran_pemeliharaans
        $pembayaran = PembayaranPemeliharaan::create([
            'total_nilai_pekerjaan'   => $request->grandTotal,
            'url_pengajuan_pembayaran' => $link,
            'nilai_ppn'               => $ppn,
        ]);

        // Simpan semua pesanan ke tabel pesanan_pembayarans
        foreach ($request->pesanan as $nomor_surat_pesanan) {
            // Update status pemeliharaan
            Pemeliharaan::where('nomor_surat_pesanan', $nomor_surat_pesanan)->update([
                'id_ref_status_pemeliharaan' => 16,
                'updated_at' => now(),
            ]);

            // Tambahkan juga ke SuratPemeliharaan
            SuratPemeliharaan::where('nomor_surat', $nomor_surat_pesanan)->update([
                'id_pembayaran_pemeliharaan' => $pembayaran->id_pembayaran_pemeliharaan,
            ]);
        }

        // Simpan lampiran (jika ada)
        if ($request->has('lampiran_nama') && $request->hasFile('lampiran_file')) {
            foreach ($request->lampiran_nama as $index => $nama_file) {
                $file = $request->file('lampiran_file')[$index];
                if ($file && $nama_file) {
                    $ext = $file->getClientOriginalExtension();
                    $sanitizedName = preg_replace('/[^A-Za-z0-9]/', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                    $timestamp = now()->format('Ymd_His');
                    $finalName = 'lampiran_pemb_' . $sanitizedName . '_' . $timestamp . '.' . $ext;

                    $lampiranPath = $file->storeAs('dokumen', $finalName, 'public');
                    $urlLampiran = Storage::url($lampiranPath);

                    LampiranPembayaranPemeliharaan::create([
                        'id_pembayaran_pemeliharaan' => $pembayaran->id_pembayaran_pemeliharaan,
                        'nama_file' => $nama_file,
                        'url_lampiran' => $urlLampiran,
                    ]);
                }
            }
        }
        Notifikasi::create([
            'from' => auth('penyedia')->id(),
            'role' => "PPK",
            'header' => 'Pemeriksaan Pengajuan Pembayaran Pemeliharaan',
            'message' => "Terdapat pengajuan pembayaran pemeliharaan yang siap diperiksa.",
            'route' => "pemeliharaan-admin?tab=pembayaran",
            'is_read' => 0,
            'created_at' => now(),
        ]);
        return response()->json(['message' => 'Pengajuan berhasil disimpan.']);
    }
    public function ttdBAP(Request $request)
    {
        $pesananIds = $request->input('pesanan');
        if (is_string($pesananIds)) {
            $pesananIds = explode(',', $pesananIds);
        }

        $pesananList = SuratPemeliharaan::with([
            'kelompokBarangPesanan',
        ])
            ->whereIn('nomor_surat', $pesananIds)
            ->get();

        $pembayaran = $pesananList->first()->pembayaranPemeliharaan;

        // Ambil dokumen BAP dan BAST berdasarkan perihal dan id pembayaran
        $bap = SuratPemeliharaan::where('id_pembayaran_pemeliharaan', $pembayaran->id_pembayaran_pemeliharaan)
            ->where('perihal', 'like', 'BAP%')
            ->first();

        $bast = SuratPemeliharaan::where('id_pembayaran_pemeliharaan', $pembayaran->id_pembayaran_pemeliharaan)
            ->where('perihal', 'like', 'BAST%')
            ->first();

        $pegawai = null;
        $adminPPK = Administrator::all()->first(fn($admin) => $admin->hasRole('PPK'));

        if ($adminPPK) {
            $pegawai = Karyawan::where('nama_lengkap', $adminPPK->username)->first();
        }
        $username = $pegawai->nama_lengkap;

        $nilai_pekerjaan = $pesananList->mapWithKeys(function ($pesanan) {
            return [$pesanan->nomor_surat => $pesanan->kelompokBarangPesanan->sum('nilai_disepakati')];
        });
        $perihal = $pesananList->pluck('perihal')->unique()->implode(', ');

        $firstPesanan = $pesananList->first();

        $penyedia = Pemeliharaan::where('nomor_surat_pesanan', $firstPesanan->nomor_surat)
            ->with('penyedia')
            ->first()?->penyedia;

        $kopSurat = RefKopSurat::where('is_aktif', 1)
            ->where('pemilik', 0)
            ->latest()
            ->first();
        $nomorBAST = $bast
            ? "{$bast->nomor_surat}"
            : '-';
        $nomorBAP = $bap
            ? "{$bap->nomor_surat}"
            : '-';
        $ppn = Ppn::first();
        $nilaiPPN = $ppn?->nilai_ppn ?? 0;
        return view('user.pemeliharaan.penyedia.buat_bap', compact(
            'perihal',
            'pesananList',
            'nilai_pekerjaan',
            'penyedia',
            'pegawai',
            'username',
            'kopSurat',
            'bap',
            'bast',
            'nomorBAST',
            'nomorBAP',
            'ppn'
        ));
    }
    public function ttdStoreBAP(Request $request)
    {
        $pesananIds = explode(',', $request->pesanan);

        $bastContent = base64_decode($request->input('pdf_bast'));
        $bapContent  = base64_decode($request->input('pdf_bap'));

        if (!$bastContent || !$bapContent) {
            return back()->withErrors(['pdf' => 'Gagal mengkonversi PDF, file kosong atau rusak.']);
        }

        // Ambil salah satu nomor_surat_pesanan
        $firstPesananId = $pesananIds[0] ?? null;

        if ($firstPesananId) {
            // Ambil id_pembayaran_pemeliharaan & nomor_surat
            $surat = SuratPemeliharaan::where('nomor_surat', $firstPesananId)->first();
            $idPembayaran = $surat?->id_pembayaran_pemeliharaan;
            $nomorSurat   = $surat?->nomor_surat;

            if ($idPembayaran && $nomorSurat) {
                // Ganti "/" jadi "-" biar aman sebagai nama file
                $nomorSuratFile = str_replace('/', '-', $nomorSurat);

                // Format nama file: BAST-{nomorSurat}, BAP-{nomorSurat}
                $pathBast = "dokumen/BAST-{$nomorSuratFile}.pdf";
                $pathBap  = "dokumen/BAP-{$nomorSuratFile}.pdf";

                Storage::disk('public')->put($pathBast, $bastContent);
                Storage::disk('public')->put($pathBap, $bapContent);

                // Update untuk BAST
                SuratPemeliharaan::where('id_pembayaran_pemeliharaan', $idPembayaran)
                    ->where('perihal', 'like', 'BAST%')
                    ->update(['url_surat' => Storage::url($pathBast)]);

                // Update untuk BAP
                SuratPemeliharaan::where('id_pembayaran_pemeliharaan', $idPembayaran)
                    ->where('perihal', 'like', 'BAP%')
                    ->update(['url_surat' => Storage::url($pathBap)]);
            }
        }

        // Update status pemeliharaan yang terkait
        Pemeliharaan::whereIn('nomor_surat_pesanan', $pesananIds)
            ->update(['id_ref_status_pemeliharaan' => 19]);
        Notifikasi::create([
            'from' => auth('penyedia')->id(),
            'role' => "Bendahara Pemeliharaan",
            'header' => 'Pemeriksaan Pengajuan Pembayaran Pemeliharaan',
            'message' => "Terdapat pengajuan pembayaran pemeliharaan yang siap diproses oleh Bendahara.",
            'route' => "pemeliharaan-admin?tab=pembayaran",
            'is_read' => 0,
            'created_at' => now(),
        ]);
        return redirect('/penyedia?tab=pembayaran')
            ->with('success', 'Dokumen BAST & BAP berhasil ditandatangani dan disimpan.');
    }

    public function rekapPemeliharaan(Request $request)
    {
        $request->validate([
            'mulai' => 'required|date',
            'sampai' => 'required|date',
        ]);

        $mulai = Carbon::parse($request->mulai)->startOfDay();
        $sampai = Carbon::parse($request->sampai)->endOfDay();

        $rekap = Pemeliharaan::with(['bmn', 'penyedia', 'status', 'pegawai', 'pesanan.pembayaranPemeliharaan'])
            ->where('id_ref_status_pemeliharaan', 21)
            ->whereBetween('updated_at', [$mulai, $sampai])
            ->get();

        return view('admin.pemeliharaan.rekapitulasi', [
            'rekap' => $rekap,
            'title' => 'Rekap Pemeliharaan',
            'mulai' => $request->mulai,
            'sampai' => $request->sampai,
        ]);
    }
    public function rekapPemeliharaanPdf(Request $request)
    {
        $request->validate([
            'mulai' => 'required|date',
            'sampai' => 'required|date',
        ]);

        $mulai = Carbon::parse($request->mulai)->startOfDay();
        $sampai = Carbon::parse($request->sampai)->endOfDay();

        $rekap = Pemeliharaan::with(['bmn', 'penyedia', 'status', 'pegawai', 'pesanan.pembayaranPemeliharaan'])
            ->where('id_ref_status_pemeliharaan', 21)
            ->whereBetween('updated_at', [$mulai, $sampai])
            ->get();
        $kopSuratPath = RefKopSurat::where('is_aktif', 1)
            ->where('pemilik', 0)
            ->latest()
            ->first();
        $pdf = Pdf::loadView('admin.pemeliharaan.pdf_rekap', [
            'rekap' => $rekap,
            'mulai' => $request->mulai,
            'sampai' => $request->sampai,
            'kop' => $kopSuratPath,
        ])->setPaper('A4', 'landscape');

        return $pdf->download('rekap_pemeliharaan.pdf');
    }
}
