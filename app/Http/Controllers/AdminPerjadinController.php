<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use DB;
use App\Models\Dokumen;
use App\Models\Info_perjadinlangsung;
use App\Models\Kendaraan;
use App\Models\Peminjaman_kendaraan_dinas;
use App\Models\Data_perjadinlangsung;
use App\Models\Kebutuhan;
use App\Models\Non_pegawai;
use Illuminate\Support\Facades\Log;
use App\Models\Versi;
use App\Models\Pegawai;
use App\Models\Ref_sbm;
use App\Models\surtug_perjadinlangsung;
use App\Models\Keuangan_perjadinlangsung;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use App\Http\Controllers\AdminOtherController;

// Set locale to Indonesian
setlocale(LC_TIME, 'id_ID');

// Set Carbon locale to Indonesian
Carbon::setLocale('id');

class AdminPerjadinController extends Controller
{

    public function index($status = 'pengajuan')
{

    $mobilitas = DB::table('info_perjadinlangsungs')
    ->join('dokumens', 'info_perjadinlangsungs.id', '=', 'dokumens.info_perjadinlangsung_id')
    // ->join('info_perjadinlangsungs', 'peminjaman_kendaraan_dinas.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
    ->leftJoin('pegawais', 'info_perjadinlangsungs.id_pengaju', '=', 'pegawais.id') // Left join ke pegawais
    ->leftJoin('non_pegawais', 'info_perjadinlangsungs.id_pengaju', '=', 'non_pegawais.id') // Left join ke non_pegawais
    ->leftJoin('administrators', 'info_perjadinlangsungs.id_pengaju', '=', 'administrators.id') // Left join ke administrators
    ->select(
        'info_perjadinlangsungs.id as idPerjadin',
        'info_perjadinlangsungs.id_pengaju',
        'info_perjadinlangsungs.nama_kegiatan',
        'info_perjadinlangsungs.kabupaten_kota',
        'info_perjadinlangsungs.provinsi',
        'info_perjadinlangsungs.tgl_keberangkatan',
        'info_perjadinlangsungs.is_acceptBMN',
        DB::raw('COALESCE(CONCAT("(Admin) ", administrators.username), pegawais.nama_lengkap, non_pegawais.nama_lengkap, "Tidak ditemukan") as nama_pengaju')
        )
    ->distinct()
    ->whereNotNull('dokumens.surat_undangan')
    ->where('info_perjadinlangsungs.versi_id', session('versi'))
    ->where('info_perjadinlangsungs.is_acceptBMN', $status)
    ->get();


    $ids = $mobilitas->pluck('idPerjadin')->toArray();

    $pesertas = DB::table('data_perjadinlangsungs')
    ->leftJoin('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
    ->leftJoin('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
    ->select('data_perjadinlangsungs.info_perjadinlangsung as idPerjadin', 'pegawais.nama_lengkap as pegawai_nama', 'non_pegawais.nama_lengkap as non_pegawai_nama')
    ->whereIn('data_perjadinlangsungs.info_perjadinlangsung', $ids)
    ->get()
    ->groupBy('idPerjadin');

    return view('admin.perjadin.mobilitas.index', [
        'title' => 'Mobilitas Perjalanan Dinas',
        'mobilitass' => $mobilitas,
        'pesertas' => $pesertas
    ]);
}

    public function storeMobilitasOnly(Request $request)
    {
        // Gabungkan tanggal dan waktu keberangkatan
        $keberangkatanDate = $request->tgl_keberangkatan; // Asumsikan '2024-07-22'
        $keberangkatanTime = $request->jam_keberangkatan; // Asumsikan '14:13:00'
        $keberangkatan = $keberangkatanDate . ' ' . substr($keberangkatanTime, 0, 5); // '2024-07-22 14:13'

        $tgl_keberangkatan = Carbon::createFromFormat('Y-m-d H:i', $keberangkatan);

        $selesaiDate = $request->tgl_selesai;
        $selesaiTime = $request->jam_selesai;
        $selesai = $selesaiDate . ' ' . substr($selesaiTime, 0, 5);
        $tgl_selesai = Carbon::createFromFormat('Y-m-d H:i', $selesai);

        if (empty($request->perjadinSebelumnya)) {
            $versi = Versi::where('status', 'aktif')->get();
            DB::table('info_perjadinlangsungs')->insertOrIgnore([
                'nama_kegiatan' => $request->nama_kegiatan,
                'tgl_mulai' => $tgl_keberangkatan,
                'tgl_selesai' => $tgl_selesai,
                'tgl_keberangkatan' => $tgl_keberangkatan,
                'tgl_kepulangan' => $tgl_selesai,
                'provinsi' => $request->provinsi,
                'kabupaten_kota' => $request->kabupaten_kota,
                'alamat' => $request->alamat,
                'mobilitas' => "Kendaraan Dinas",
                'pemberi_undangan' => "-",
                'tanggal_surat' => "-",
                'keterangan_mobilitas' => $request->ket_mobilitas, // Tambahkan ini
                'status_pengajuan' => 'Draf-pengajuan',
                'versi_id' => $versi[0]->id,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

            $perjadin = Info_perjadinlangsung::max('id'); // mengambil nilai id terakhir yang diinputkan
        } else {
            $perjadinSebelumnya = Info_perjadinlangsung::find($request->perjadinSebelumnya);

            // Masukkan data baru dengan beberapa data dari perjadin sebelumnya
            DB::table('info_perjadinlangsungs')->insertOrIgnore([
                'nama_kegiatan' => $request->nama_kegiatan ?? $perjadinSebelumnya->nama_kegiatan,
                'tgl_mulai' => $tgl_keberangkatan,
                'tgl_selesai' => $tgl_selesai,
                'tgl_keberangkatan' => $tgl_keberangkatan,
                'tgl_kepulangan' => $tgl_selesai,
                'provinsi' => $request->provinsi ?? $perjadinSebelumnya->provinsi,
                'kabupaten_kota' => $request->kabupaten_kota ?? $perjadinSebelumnya->kabupaten_kota,
                'alamat' => $request->alamat ?? $perjadinSebelumnya->alamat,
                'mobilitas' => "Kendaraan Dinas",
                'pemberi_undangan' => "-",
                'tanggal_surat' => "-",
                'keterangan_mobilitas' => $request->keterangan_mobilitas ?? $perjadinSebelumnya->keterangan_mobilitas,
                'status_pengajuan' => 'Draf-pengajuan',
                'versi_id' => $perjadinSebelumnya->versi_id,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

            $perjadin = Info_perjadinlangsung::max('id'); // mengambil nilai id dari perjadinSebelumnya
        }

        DB::table('peminjaman_kendaraan_dinas')->insertOrIgnore([
            'info_perjadinlangsung' => $perjadin, //menerima id info terakhir
            'kendaraan' => $request->kendaraan,
            'status' => 'pengajuan',
            'pegawai_id' => $request->pengemudi,
            'ket_mobilitas' => $request->ket_mobilitas,
            'tgl_selesai' => $tgl_selesai,
            'tgl_keberangkatan' => $tgl_keberangkatan,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('dokumens')->insert([
            'info_perjadinlangsung_id' => $perjadin,
            'status_persetujuan' => 'pengajuan',
            'surat_undangan' => $request->surat_undangan ? $request->surat_undangan : '-',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('info_perjadinlangsungs')
            ->where('id', $perjadin)
            ->update([
                'is_acceptHKT' => 'pengajuan',
                'is_acceptBMN' => 'proses',
                'status_pengajuan_detail' => 'Verifikasi-HKT',
                'admin_BMN' => auth('administrator')->user()->id,
                'id_pengaju' => auth('administrator')->user()->id,
                'status_pengajuan'  => 'proses',
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

        DB::table('data_perjadinlangsungs')->insertOrIgnore([
            'status_pegawai' => 'Supir',
            'info_perjadinlangsung' => $perjadin,
            'pegawai_id' => $request->pengemudi,
            'tgl_keberangkatan' => $request->tgl_keberangkatan,
            'tgl_selesai' => $request->tgl_selesai,
            // 'non_pegawai_id' => $request->peserta_non_pegawai,
            'status_persetujuan' => 'Proses Persetujuan',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $data_perjaidinlangsung_max = data_perjadinlangsung::max('id');

        DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
            'info_perjadinlangsung' => $perjadin,
            'data_perjadinlangsungs' => $data_perjaidinlangsung_max,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $dataNotif = [
            'id_kegiatan' => $perjadin,
            'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
            'to' => 0, // ID pengguna yang menerima
            'role' => 'HKT', // Peran pengguna
            'header' => 'Usulan Perjalanan Dinas - '.$perjadin, // Judul notifikasi
            'message' => 'BMN mengusulkan pengemudi dengan Surat Tugas terpisah untuk diverifikasi HKT', // Isi pesan
            'route' => 'perjadin-HKT/detail/'.$perjadin, // Route yang dituju
            'is_read' => 0, // Status belum dibaca
            'versi_id' => session('versi'),
            'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
        ];

        // Melakukan insert ke tabel notifications
        DB::table('notifications')->insert($dataNotif);

        $dataNotifUser = [
            'id_kegiatan' => $perjadin,
            'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
            'to' => $request->pengemudi, // ID pengguna yang menerima
            'role' => null, // Peran pengguna
            'header' => 'Usulan Perjalanan Dinas - '.$perjadin, // Judul notifikasi
            'message' => 'Admin BMN mengusulkan anda untuk Perjalanan Dinas dengan Surat Tugas terpisah, harap tunggu Verifikasi HKT', // Isi pesan
            'route' => 'perjadin/riwayat/Draf-pengajuan', // Route yang dituju
            'is_read' => 0, // Status belum dibaca
            'versi_id' => session('versi'),
            'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
        ];

        // Melakukan insert ke tabel notifications
        DB::table('notifications')->insert($dataNotifUser);

        // Redirect ke URL tertentu
        return redirect()->to(url('/perjadin-mobilitas/pengajuan'));
    }

    function notifAdmin($role):JsonResponse
    {
        if ($role != "Master") {
            $notif = DB::table('notifications')
                ->where('versi_id',session('versi'))  
                ->where('role',$role)  
                ->where('is_read',0)
                ->count();
                $notifData = DB::table('notifications')
                ->join('pegawais', 'notifications.from', '=', 'pegawais.id')
                ->select('pegawais.nama_lengkap AS dari','notifications.*')
                ->where('notifications.versi_id',session('versi'))  
                ->where('role',$role)
                ->where('is_read',0)
                ->orderBy('notifications.created_at', 'desc')
                ->get();
            } else {
                $notif = DB::table('notifications')
                ->where('versi_id',session('versi'))   
                ->where('is_read',0)
                ->whereNotNull('role')
                ->count();
                $notifData = DB::table('notifications')
                ->select('notifications.*')
                ->where('notifications.versi_id',session('versi'))  
                ->where('is_read', 0)
                ->whereNotNull('role')
                ->orderBy('notifications.created_at', 'desc')
                ->get();

            // Menampilkan notifikasi dengan format header yang diinginkan
            foreach ($notifData as $notiFitem) {
                // Membuat format header
                $notiFitem->header = "({$notiFitem->role}) {$notiFitem->header}"; // Ganti $notif->header sesuai kolom yang ada

            }

        }
        $total = 0;

        if($notif > 0){
            $total +=1;
        }

        $res = ['notif'=>$notif, 'notifData'=>$notifData, 'total'=>$total];

        return response()->json($res);

    }

    function markAsReadAdmin($id) {
        // Mengubah status is_read menjadi 1
        DB::table('notifications')   
            ->where('id', $id)
            ->update(['is_read' => 1]);

        return response()->json(['success' => true]);
    }

    public function markAllAdmin($role)
    {

        // Mengupdate notifikasi dengan role tertentu
        DB::table('notifications')
            ->where('versi_id',session('versi'))  
            ->where('role', $role)
            ->update(['is_read' => 1]);

        return response()->json(['message' => 'Notifikasi berhasil diperbarui.']);
    }


    public function showBmnMobilitasOnly()
    {
        // Pengemudi
        $pengemudi = DB::table('pegawais')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->select('pegawais.id', 'pegawais.nama_lengkap', 'jabatans.nama_jabatan')
            ->where('jabatans.nama_jabatan', 'Pengemudi')
            ->distinct()
            ->get();

        // Kendaraan
        $kendaraan = DB::table('kendaraans')
            ->select('kendaraans.*')
            ->where('kendaraans.status', '=', 'baik')
            ->where('kendaraans.tipe', '=', 'Roda Empat')
            ->distinct()
            ->get();

        // Mobilitass
        $mobilitass = DB::table('info_perjadinlangsungs')
            ->leftJoin('peminjaman_kendaraan_dinas', 'info_perjadinlangsungs.id', '=', 'peminjaman_kendaraan_dinas.info_perjadinlangsung')
            ->leftJoin('dokumens', 'dokumens.info_perjadinlangsung_id', '=', 'info_perjadinlangsungs.id')
            ->select('info_perjadinlangsungs.*', 'dokumens.surat_undangan')
            ->where('info_perjadinlangsungs.pemberi_undangan', '!=', '-')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('peminjaman_kendaraan_dinas')
                    ->whereColumn('peminjaman_kendaraan_dinas.info_perjadinlangsung', 'info_perjadinlangsungs.id');
            })
            ->orderByDesc('info_perjadinlangsungs.id')
            ->limit(10)
            ->get();

        return view(
            'admin.perjadin.mobilitas.bmn_mobilitas_only',
            [
                'pengemudis' => $pengemudi,
                'kendaraans' => $kendaraan,
                'mobilitass' => $mobilitass,
            ]
        );
    }

    public function keuanganIndex($status = 'verifikasi-2')
    {
        $perjadin = DB::table('info_perjadinlangsungs')
            ->where('info_perjadinlangsungs.is_acceptKeu', $status)
            ->where('info_perjadinlangsungs.versi_id', session('versi'))
            ->leftJoin('pegawais', 'info_perjadinlangsungs.id_pengaju', '=', 'pegawais.id') // Left join ke pegawais
            ->leftJoin('non_pegawais', 'info_perjadinlangsungs.id_pengaju', '=', 'non_pegawais.id') // Left join ke non_pegawais
            ->leftJoin('administrators', 'info_perjadinlangsungs.id_pengaju', '=', 'administrators.id') // Left join ke administrators
            ->leftJoin('dokumens', 'info_perjadinlangsungs.id', '=', 'dokumens.info_perjadinlangsung_id') // Left join ke administrators
            ->select(
                'info_perjadinlangsungs.*',
                'dokumens.tempatTujuan_penandatangan0',
                'dokumens.nama_penandatangan','dokumens.jabatan_penandatangan','dokumens.nip_penandatangan','dokumens.tempatTiba_penandatangan','dokumens.tempatTujuan_penandatangan','dokumens.tanggal_penandatangan','dokumens.tanggalTujuan_penandatangan',
                'dokumens.nama_penandatangan2','dokumens.jabatan_penandatangan2','dokumens.nip_penandatangan2','dokumens.tempatTiba_penandatangan2','dokumens.tempatTujuan_penandatangan2','dokumens.tanggal_penandatangan2','dokumens.tanggalTujuan_penandatangan2',
                'dokumens.nama_penandatangan3','dokumens.jabatan_penandatangan3','dokumens.nip_penandatangan3','dokumens.tempatTiba_penandatangan3','dokumens.tempatTujuan_penandatangan3','dokumens.tanggal_penandatangan3','dokumens.tanggalTujuan_penandatangan3',
                'dokumens.nama_penandatangan4','dokumens.jabatan_penandatangan4','dokumens.nip_penandatangan4','dokumens.tempatTiba_penandatangan4','dokumens.tempatTujuan_penandatangan4','dokumens.tanggal_penandatangan4','dokumens.tanggalTujuan_penandatangan4',
                DB::raw('COALESCE(CONCAT("(Admin) ", administrators.username), pegawais.nama_lengkap, non_pegawais.nama_lengkap, "Tidak ditemukan") as nama_pengaju'),
                DB::raw("(SELECT GROUP_CONCAT(COALESCE(pegawais.nama_lengkap, non_pegawais.nama_lengkap) SEPARATOR '\n')
                    FROM data_perjadinlangsungs
                    LEFT JOIN pegawais ON data_perjadinlangsungs.pegawai_id = pegawais.id
                    LEFT JOIN non_pegawais ON data_perjadinlangsungs.non_pegawai_id = non_pegawais.id
                    WHERE data_perjadinlangsungs.info_perjadinlangsung = info_perjadinlangsungs.id) AS nama_peserta"),
                )
            ->get();
        // dd($perjadin);

        return view('admin.perjadin.keuangan.index', [
            'title' => 'Keuangan Perjalanan Dinas',
            'perjadins' => $perjadin
        ]);
    }

    public function bendaharaIndex($status = 'approval-1')
{
    $query = DB::table('info_perjadinlangsungs')
        ->leftJoin('pegawais', 'info_perjadinlangsungs.id_pengaju', '=', 'pegawais.id') // Left join ke pegawais
        ->leftJoin('non_pegawais', 'info_perjadinlangsungs.id_pengaju', '=', 'non_pegawais.id') // Left join ke non_pegawais
        ->leftJoin('administrators', 'info_perjadinlangsungs.id_pengaju', '=', 'administrators.id') // Left join ke administrators
        ->leftJoin('dokumens', 'info_perjadinlangsungs.id', '=', 'dokumens.info_perjadinlangsung_id') // Left join ke administrators
        ->where('versi_id', session('versi'))
        ->select(
            'dokumens.*',
            'info_perjadinlangsungs.*',
            DB::raw('COALESCE(CONCAT("(Admin) ", administrators.username), pegawais.nama_lengkap, non_pegawais.nama_lengkap, "Tidak ditemukan") as nama_pengaju'),
            DB::raw("(SELECT GROUP_CONCAT(COALESCE(pegawais.nama_lengkap, non_pegawais.nama_lengkap) SEPARATOR '\n')
                   FROM data_perjadinlangsungs
                   LEFT JOIN pegawais ON data_perjadinlangsungs.pegawai_id = pegawais.id
                   LEFT JOIN non_pegawais ON data_perjadinlangsungs.non_pegawai_id = non_pegawais.id
                   WHERE data_perjadinlangsungs.info_perjadinlangsung = info_perjadinlangsungs.id) AS nama_peserta")

        );

    // if ($status == 'approval-2') {
    //     $query->where('status_pengajuan', 'selesai')
    //           ->where('is_acceptKeu', 'selesai');
    // }

    $query->where('is_acceptBend', $status);

    $perjadin = $query->get();

    return view('admin.perjadin.bendahara.index', [
        'title' => 'Bendahara Perjalanan Dinas',
        'perjadins' => $perjadin
    ]);
}

public function HKTIndex($status = 'pengajuan')
{
    $perjadin = DB::table('info_perjadinlangsungs')
        ->join('dokumens', 'info_perjadinlangsungs.id', '=', 'dokumens.info_perjadinlangsung_id')
        ->leftJoin('surtug_perjadinlangsungs', 'info_perjadinlangsungs.id', '=', 'surtug_perjadinlangsungs.id_perjadinlangsung') // Join dengan surtug_perjadinlangsungs
        ->leftJoin('pegawais', 'info_perjadinlangsungs.id_pengaju', '=', 'pegawais.id') // Left join ke pegawais
        ->leftJoin('non_pegawais', 'info_perjadinlangsungs.id_pengaju', '=', 'non_pegawais.id') // Left join ke non_pegawais
        ->leftJoin('administrators', 'info_perjadinlangsungs.id_pengaju', '=', 'administrators.id') // Left join ke administrators
        ->where('info_perjadinlangsungs.is_acceptHKT', $status)
        ->where('info_perjadinlangsungs.versi_id', session('versi'))
        ->whereNotNull('dokumens.surat_undangan')
        ->select(
            'info_perjadinlangsungs.*',
            'dokumens.surat_undangan',
            'dokumens.surat_tugas',
            'surtug_perjadinlangsungs.nomor_surat', // Menambahkan nomor_surat
            'surtug_perjadinlangsungs.tgl_surat_dibuat', // Menambahkan tgl_surat_dibuat
            DB::raw('COALESCE(CONCAT("(Admin) ", administrators.username), pegawais.nama_lengkap, non_pegawais.nama_lengkap, "Tidak ditemukan") as nama_pengaju'),
        )
        ->get();


    // Ambil semua id_perjadin dari perjadin
    $perjadinIds = $perjadin->pluck('id');

    // Ambil id untuk HKT selesai
    $HKTselesaiId = DB::table('info_perjadinlangsungs')
        ->where('is_acceptHKT', 'selesai')
        ->pluck('id');

    // Ambil id perjadinlangsung dari surtug_perjadinlangsungs
    $surtugExist = DB::table('surtug_perjadinlangsungs')
        ->whereIn('id_perjadinlangsung', $perjadinIds)
        ->pluck('id_perjadinlangsung');

    // Tambahkan dokumen ke setiap item perjadin
    foreach ($perjadin as $info) {
        // Konversi tanggal ke dalam objek Carbon untuk perhitungan
        $tglMulai = \Carbon\Carbon::parse($info->tgl_keberangkatan)->startOfDay();
        $tglSelesai = \Carbon\Carbon::parse($info->tgl_selesai)->endOfDay();

        // Hitung selisih hari
        $jumlahHari = $tglMulai->diffInDays($tglSelesai) + 1;

        // Tambahkan jumlah hari ke objek $info
        $info->jumlah_hari = $jumlahHari;

        // Ambil dokumen yang sesuai untuk setiap perjadin
        $info->dokumen = DB::table('dokumens')
            ->where('info_perjadinlangsung_id', $info->id)
            ->get();
    }

    return view('admin.perjadin.HKT.index', [
        'title' => 'HKT Perjalanan Dinas',
        'perjadins' => $perjadin,
        'surtugs' => DB::table('surtug_perjadinlangsungs')->get(), // Mengambil semua data dari surtug_perjadinlangsungs
        'surtugExist' => $surtugExist,
    ]);
}

public function cekMobilitasAPI(Request $request)
{
    // Parsing tanggal dari request menggunakan Carbon
    $tanggalAwal = Carbon::parse($request->input('tanggal_awal'));
    $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir'));
    $perjadinID = $request->input('perjadinID');

    // Query untuk mendapatkan kendaraan yang tersedia
    $kendaraans = DB::table('kendaraans')
        ->select('kendaraans.*')
        ->where('kendaraans.status', '=', 'baik')
        ->where('kendaraans.tipe', '=', 'Roda Empat')
        ->whereNotExists(function ($query) use ($tanggalAwal, $tanggalAkhir) {
            $query->select(DB::raw(1))
                ->from('peminjaman_kendaraan_dinas')
                ->whereRaw('kendaraans.id = peminjaman_kendaraan_dinas.kendaraan')
                ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                    $subquery
                        ->where('peminjaman_kendaraan_dinas.tgl_keberangkatan', '<=', $tanggalAkhir)
                        ->where('peminjaman_kendaraan_dinas.tgl_selesai', '>=', $tanggalAwal)
                        ->where('peminjaman_kendaraan_dinas.status', '!=', 'ditolak');
                });
        })
        ->distinct()
        ->get();

    // Query untuk mendapatkan pengemudi yang tidak sedang dalam perjalanan dinas
    $pengemudis = DB::table('pegawais')
        ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
        ->select('pegawais.id', 'pegawais.nama_lengkap', 'jabatans.nama_jabatan')
        ->where('jabatans.nama_jabatan', 'Pengemudi')
        ->whereNotExists(function ($query) use ($tanggalAwal, $tanggalAkhir) {
            $query->select(DB::raw(1))
                ->from('peminjaman_kendaraan_dinas')
                ->whereRaw('pegawais.id = peminjaman_kendaraan_dinas.pegawai_id')
                ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                    $subquery
                        ->where('peminjaman_kendaraan_dinas.tgl_keberangkatan', '<=', $tanggalAkhir)
                        ->where('peminjaman_kendaraan_dinas.tgl_selesai', '>=', $tanggalAwal)
                        ->where('peminjaman_kendaraan_dinas.status', '!=', 'ditolak');
                });
        })
        ->distinct()
        ->get();

    $pegawaiPengemudis = [];
    // Jalankan query untuk pegawai pengemudi hanya jika perjadinID ada
    if (!empty($perjadinID)) {
        $pegawaiPengemudis = DB::table('pegawais')
            ->join('data_perjadinlangsungs', 'pegawais.id', '=', 'data_perjadinlangsungs.pegawai_id')
            ->select('pegawais.id', 'pegawais.nama_lengkap', 'jabatans.nama_jabatan')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $perjadinID)
            ->whereNotExists(function ($query) use ($tanggalAwal, $tanggalAkhir) {
                $query->select(DB::raw(1))
                    ->from('peminjaman_kendaraan_dinas')
                    ->whereRaw('pegawais.id = peminjaman_kendaraan_dinas.pegawai_id')
                    ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                        $subquery
                            ->where('peminjaman_kendaraan_dinas.tgl_keberangkatan', '<=', $tanggalAkhir)
                            ->where('peminjaman_kendaraan_dinas.tgl_selesai', '>=', $tanggalAwal)
                            ->where('peminjaman_kendaraan_dinas.status', '!=', 'ditolak');
                    });
            })
            ->distinct()
            ->get();
    }

    // Return data dalam format JSON
    return response()->json([
        'kendaraans' => $kendaraans,
        'pengemudis' => $pengemudis,
        'pegawaiPengemudis' => $pegawaiPengemudis, // Sertakan pegawaiPengemudi dalam respons
    ]);
}




public function detail_mobilitas($id)
{
    // Mengambil data Info Perjadin berdasarkan ID
    $infoPerjadin = Info_perjadinlangsung::find($id);

    if ($infoPerjadin) {
        $tanggalAwal = Carbon::parse($infoPerjadin->tgl_keberangkatan);
        $tanggalAkhir = Carbon::parse($infoPerjadin->tgl_selesai);
    } else {
        return redirect()->back()->withErrors('Data perjalanan dinas tidak ditemukan.');
    }

    // Mendapatkan data peserta pegawai
    $pesertaPegawais = DB::table('data_perjadinlangsungs')
        ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
        ->select('pegawais.id', 'pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai')
        ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
        ->get();

    // Mendapatkan data peserta non-pegawai
    $pesertaNonPegawais = DB::table('data_perjadinlangsungs')
        ->join('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
        ->select('non_pegawais.id', 'non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'data_perjadinlangsungs.status_pegawai')
        ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
        ->get();

    // Mendapatkan semua Non-Pegawai yang tidak terlibat dalam peminjaman kendaraan dalam rentang waktu
    $semuaNonPegawais = DB::table('non_pegawais')
        ->select('non_pegawais.id', 'non_pegawais.nama_lengkap')
        ->whereNotExists(function ($query) use ($tanggalAwal, $tanggalAkhir) {
            $query->select(DB::raw(1))
                ->from('peminjaman_kendaraan_dinas')
                ->whereRaw('non_pegawais.id = peminjaman_kendaraan_dinas.pegawai_id')
                ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                    $subquery->where('peminjaman_kendaraan_dinas.tgl_keberangkatan', '<=', $tanggalAkhir)
                        ->where('peminjaman_kendaraan_dinas.tgl_selesai', '>=', $tanggalAwal);
                });
        })
        ->distinct()
        ->get();

    // Mendapatkan daftar pengemudi yang tidak sedang dalam perjalanan dinas
    $pengemudi = DB::table('pegawais')
    ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
    ->select('pegawais.id', 'pegawais.nama_lengkap', 'jabatans.nama_jabatan')
    ->where('jabatans.nama_jabatan', 'Pengemudi')
    ->whereNotExists(function ($query) use ($tanggalAwal, $tanggalAkhir) {
        // Cek apakah pengemudi sedang digunakan pada kegiatan atau perjadin
        $query->select(DB::raw(1))
            ->from('peminjaman_kendaraan_dinas')
            ->whereRaw('pegawais.id = peminjaman_kendaraan_dinas.pegawai_id')
            ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                $subquery->where('peminjaman_kendaraan_dinas.tgl_keberangkatan', '<=', $tanggalAkhir)
                         ->where('peminjaman_kendaraan_dinas.tgl_selesai', '>=', $tanggalAwal)
                         ->where('peminjaman_kendaraan_dinas.status', '!=', 'ditolak');
            });
    })
    ->distinct()
    ->get();

    $mobilitass = DB::table('peminjaman_kendaraan_dinas')
        ->leftJoin('pegawais','peminjaman_kendaraan_dinas.pegawai_id','=','pegawais.id')
        ->leftJoin('kendaraans','peminjaman_kendaraan_dinas.kendaraan','=','kendaraans.id')
        ->leftJoin('info_perjadinlangsungs','peminjaman_kendaraan_dinas.info_perjadinlangsung','=','info_perjadinlangsungs.id')
        ->select('info_perjadinlangsungs.*','peminjaman_kendaraan_dinas.*','pegawais.id AS id_pegawai','pegawais.nama_lengkap','kendaraans.merek','kendaraans.no_polisi')
        ->where('peminjaman_kendaraan_dinas.info_perjadinlangsung',$id)
        ->get();


    // Mendapatkan kebutuhan keuangan untuk perjalanan dinas
    $kebutuhans = DB::table('keuangan_perjadinlangsungs')
        ->join('kebutuhans', 'keuangan_perjadinlangsungs.kebutuhan_id', '=', 'kebutuhans.id')
        ->select('kebutuhans.id as idKebutuhan', 'kebutuhans.nama', 'kebutuhans.jumlah_frekuensi', 'kebutuhans.satuan', 'kebutuhans.tipe_pendanaan', 'kebutuhans.ket', 'kebutuhans.status', 'keuangan_perjadinlangsungs.kebutuhan_id', 'keuangan_perjadinlangsungs.id as idKeuangan', 'keuangan_perjadinlangsungs.uang_harian', 'keuangan_perjadinlangsungs.persen_pajak', 'keuangan_perjadinlangsungs.jumlah_harga')
        ->where('keuangan_perjadinlangsungs.info_perjadinlangsung', $id)
        ->get();

    // Mendapatkan kendaraan yang tersedia dalam rentang tanggal
    $kendaraan = DB::table('kendaraans')
    ->select('kendaraans.*')
    ->where('kendaraans.status', '=', 'baik')
    ->where('kendaraans.tipe', '=', 'Roda Empat')
    ->whereNotExists(function ($query) use ($tanggalAwal, $tanggalAkhir) {
        // Cek apakah kendaraan sedang digunakan pada kegiatan atau perjadin
        $query->select(DB::raw(1))
            ->from('peminjaman_kendaraan_dinas')
            ->whereRaw('kendaraans.id = peminjaman_kendaraan_dinas.kendaraan')
            ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                $subquery->where('peminjaman_kendaraan_dinas.tgl_keberangkatan', '<=', $tanggalAkhir)
                         ->where('peminjaman_kendaraan_dinas.tgl_selesai', '>=', $tanggalAwal)
                         ->where('peminjaman_kendaraan_dinas.status', '!=', 'ditolak');
            });
    })
    ->distinct()
    ->get();

    // Return view dengan semua data yang dikompilasi
    return view('admin.perjadin.mobilitas.detail', [
        'title' => 'Mobilitas Perjalanan Dinas',
        'pesertaPegawais' => $pesertaPegawais,
        'pesertaNonPegawais' => $pesertaNonPegawais,
        'semuaNonPegawais' => $semuaNonPegawais,
        'dokumen' => Dokumen::where('info_perjadinlangsung_id', $id)->get(),
        'perjadin' => $infoPerjadin,
        'mobilitass' => $mobilitass,
        'pengemudis' => $pengemudi,
        'kendaraans' => $kendaraan,
        'kebutuhans' => $kebutuhans,
    ]);
}

public function edit_mobilitas($id)
{
    // Mengambil data Info Perjadin berdasarkan ID
    $infoPerjadin = Info_perjadinlangsung::find($id);

    if ($infoPerjadin) {
        $tanggalAwal = Carbon::parse($infoPerjadin->tgl_keberangkatan);
        $tanggalAkhir = Carbon::parse($infoPerjadin->tgl_selesai);
    } else {
        return redirect()->back()->withErrors('Data perjalanan dinas tidak ditemukan.');
    }

    // Mendapatkan data peserta pegawai
    $pesertaPegawais = DB::table('data_perjadinlangsungs')
        ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
        ->select('pegawais.id', 'pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai')
        ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
        ->get();

    // Mendapatkan data peserta non-pegawai
    $pesertaNonPegawais = DB::table('data_perjadinlangsungs')
        ->join('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
        ->select('non_pegawais.id', 'non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'data_perjadinlangsungs.status_pegawai')
        ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
        ->get();

    // Mendapatkan semua Non-Pegawai yang tidak terlibat dalam peminjaman kendaraan dalam rentang waktu
    $semuaNonPegawais = DB::table('non_pegawais')
        ->select('non_pegawais.id', 'non_pegawais.nama_lengkap')
        ->whereNotExists(function ($query) use ($tanggalAwal, $tanggalAkhir) {
            $query->select(DB::raw(1))
                ->from('peminjaman_kendaraan_dinas')
                ->whereRaw('non_pegawais.id = peminjaman_kendaraan_dinas.pegawai_id')
                ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                    $subquery->where('peminjaman_kendaraan_dinas.tgl_keberangkatan', '<=', $tanggalAkhir)
                        ->where('peminjaman_kendaraan_dinas.tgl_selesai', '>=', $tanggalAwal);
                });
        })
        ->distinct()
        ->get();

    // Mendapatkan daftar pengemudi yang tidak sedang dalam perjalanan dinas
    $pengemudi = DB::table('pegawais')
    ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
    ->select('pegawais.id', 'pegawais.nama_lengkap', 'jabatans.nama_jabatan')
    ->where('jabatans.nama_jabatan', 'Pengemudi')
    ->whereNotExists(function ($query) use ($tanggalAwal, $tanggalAkhir) {
        // Cek apakah pengemudi sedang digunakan pada kegiatan atau perjadin
        $query->select(DB::raw(1))
            ->from('peminjaman_kendaraan_dinas')
            ->whereRaw('pegawais.id = peminjaman_kendaraan_dinas.pegawai_id')
            ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                $subquery->where('peminjaman_kendaraan_dinas.tgl_keberangkatan', '<=', $tanggalAkhir)
                         ->where('peminjaman_kendaraan_dinas.tgl_selesai', '>=', $tanggalAwal);
            });
    })
    ->distinct()
    ->get();


    // Mendapatkan kebutuhan keuangan untuk perjalanan dinas
    $kebutuhans = DB::table('keuangan_perjadinlangsungs')
        ->join('kebutuhans', 'keuangan_perjadinlangsungs.kebutuhan_id', '=', 'kebutuhans.id')
        ->select('kebutuhans.id as idKebutuhan', 'kebutuhans.nama', 'kebutuhans.jumlah_frekuensi', 'kebutuhans.satuan', 'kebutuhans.tipe_pendanaan', 'kebutuhans.ket', 'kebutuhans.status', 'keuangan_perjadinlangsungs.kebutuhan_id', 'keuangan_perjadinlangsungs.id as idKeuangan', 'keuangan_perjadinlangsungs.uang_harian', 'keuangan_perjadinlangsungs.persen_pajak', 'keuangan_perjadinlangsungs.jumlah_harga')
        ->where('keuangan_perjadinlangsungs.info_perjadinlangsung', $id)
        ->get();

    // Mendapatkan kendaraan yang tersedia dalam rentang tanggal
    $kendaraan = DB::table('kendaraans')
    ->select('kendaraans.*')
    ->where('kendaraans.status', '=', 'baik')
    ->where('kendaraans.tipe', '=', 'Roda Empat')
    ->whereNotExists(function ($query) use ($tanggalAwal, $tanggalAkhir) {
        // Cek apakah kendaraan sedang digunakan pada kegiatan atau perjadin
        $query->select(DB::raw(1))
            ->from('peminjaman_kendaraan_dinas')
            ->whereRaw('kendaraans.id = peminjaman_kendaraan_dinas.kendaraan')
            ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                $subquery->where('peminjaman_kendaraan_dinas.tgl_keberangkatan', '<=', $tanggalAkhir)
                         ->where('peminjaman_kendaraan_dinas.tgl_selesai', '>=', $tanggalAwal);
            });
    })
    ->distinct()
    ->get();

    $mobilitass = DB::table('peminjaman_kendaraan_dinas')
    ->join('pegawais', 'pegawais.id', '=', 'peminjaman_kendaraan_dinas.pegawai_id')
    ->join('kendaraans', 'kendaraans.id', '=', 'peminjaman_kendaraan_dinas.kendaraan') // join dengan tabel kendaraans
    ->where('peminjaman_kendaraan_dinas.info_perjadinlangsung', $id)
    ->select('peminjaman_kendaraan_dinas.*', 'pegawais.nama_lengkap', 'kendaraans.merek', 'kendaraans.no_polisi') // memilih merek dan no_polisi
    ->get();




    // Return view dengan semua data yang dikompilasi
    return view('admin.perjadin.mobilitas.edit', [
        'title' => 'Mobilitas Perjalanan Dinas',
        'pesertaPegawais' => $pesertaPegawais,
        'pesertaNonPegawais' => $pesertaNonPegawais,
        'semuaNonPegawais' => $semuaNonPegawais,
        'dokumen' => Dokumen::where('info_perjadinlangsung_id', $id)->get(),
        'perjadin' => $infoPerjadin,
        'mobilitass' => $mobilitass,
        'pengemudis' => $pengemudi,
        'kendaraans' => $kendaraan,
        'kebutuhans' => $kebutuhans,
    ]);
}


    public function deleteMobilitas(Request $request, $id)
    {
        DB::table('peminjaman_kendaraan_dinas')->where('id', $id)->delete();

        // Mendapatkan ID perjadin dari request JSON
        $id_perjadin = $request->input('info_perjadinlangsung');

        // Mengembalikan response sukses
        return response()->json(['success' => true]);
    }



    public function detail_perjadin_BMN($id)
    {

        $selectPeserta = DB::table('data_perjadinlangsungs')
        ->join('pegawais', 'pegawai_id', '=', 'pegawais.id')
        ->select('data_perjadinlangsungs.id as idPeserta', 'data_perjadinlangsungs.status_persetujuan', 'data_perjadinlangsungs.id', 'data_perjadinlangsungs.status_pegawai', 'pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan')
        ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
        ->get();

    $selectPeserta_nonPegawai = DB::table('data_perjadinlangsungs')
        ->join('non_pegawais', 'non_pegawai_id', '=', 'non_pegawais.id')
        ->select('data_perjadinlangsungs.id as idPeserta', 'data_perjadinlangsungs.status_persetujuan', 'data_perjadinlangsungs.id', 'data_perjadinlangsungs.status_pegawai', 'non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan')
        ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
        ->get();

        $kebutuhans = DB::table('keuangan_perjadinlangsungs')
        ->join('kebutuhans', 'keuangan_perjadinlangsungs.kebutuhan_id', '=', 'kebutuhans.id')
        ->select('kebutuhans.id as idKebutuhan', 'kebutuhans.nama', 'kebutuhans.jumlah_frekuensi', 'kebutuhans.satuan', 'kebutuhans.tipe_pendanaan', 'kebutuhans.ket', 'keuangan_perjadinlangsungs.info_perjadinlangsung', 'keuangan_perjadinlangsungs.kebutuhan_id', 'keuangan_perjadinlangsungs.status')
        ->where('keuangan_perjadinlangsungs.info_perjadinlangsung', $id)
        ->get();
    $mobilitas = DB::table('peminjaman_kendaraan_dinas')
        ->join('pegawais', 'peminjaman_kendaraan_dinas.pegawai_id', '=', 'pegawais.id')
        ->join('kendaraans', 'peminjaman_kendaraan_dinas.kendaraan', '=', 'kendaraans.id')
        ->select('peminjaman_kendaraan_dinas.info_perjadinlangsung', 'pegawais.nama_lengkap', 'kendaraans.merek', 'kendaraans.no_polisi', 'peminjaman_kendaraan_dinas.status')
        ->where('peminjaman_kendaraan_dinas.info_perjadinlangsung', $id)
        ->get();

        $pengemudi = DB::table('data_perjadinlangsungs')
            ->leftJoin('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->leftJoin('non_pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'non_pegawais.id')
            ->join('peminjaman_kendaraan_dinas', 'data_perjadinlangsungs.pegawai_id', '=', 'peminjaman_kendaraan_dinas.pegawai_id')
            ->join('kendaraans', 'peminjaman_kendaraan_dinas.kendaraan', '=', 'kendaraans.id')
            ->select(
                DB::raw('COALESCE(pegawais.nama_lengkap, non_pegawais.nama_lengkap) AS nama_lengkap'),
                DB::raw('COALESCE(pegawais.pangkat, non_pegawais.pangkat) AS pangkat'),
                DB::raw('COALESCE(pegawais.golongan, non_pegawais.golongan) AS golongan'),
                'data_perjadinlangsungs.status_pegawai',
                'kendaraans.merek',
                'kendaraans.no_polisi'
            )
            ->where('peminjaman_kendaraan_dinas.info_perjadinlangsung', $id)
            ->distinct()
            ->get();
        return view('admin.perjadin.mobilitas.detail_mobilitas', [
            'title' => 'Pengajuan Surtug',

            'perjadin' => Info_perjadinlangsung::find($id),
            'mobilitass' => Peminjaman_kendaraan_dinas::where('info_perjadinlangsung', $id)->get(),
            'pengemudis' => $pengemudi,
            'perjadin' => Info_perjadinlangsung::find($id),
            'selectPesertas' => $selectPeserta,
            "fasilitas" => $kebutuhans,
            'mobilitass' => $mobilitas,
            'selectPesertasNonPegawais' => $selectPeserta_nonPegawai,
            'dokumen' => Dokumen::where('info_perjadinlangsung_id', $id)->first()
        ]);
    }

    public function detail_perjadin_keuangan($id)
    {
        $pesertaPegawais = DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->select('pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'data_perjadinlangsungs.id as idPeserta', 'data_perjadinlangsungs.status_persetujuan')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();
        $pesertaNonPegawais = DB::table('data_perjadinlangsungs')
            ->join('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
            ->select('non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'data_perjadinlangsungs.id as idPeserta', 'data_perjadinlangsungs.status_persetujuan')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();
        $kebutuhans = DB::table('keuangan_perjadinlangsungs')
            ->join('kebutuhans', 'keuangan_perjadinlangsungs.kebutuhan_id', '=', 'kebutuhans.id')
            ->select('kebutuhans.id as idKebutuhan', 'kebutuhans.nama', 'kebutuhans.jumlah_frekuensi', 'kebutuhans.satuan', 'kebutuhans.tipe_pendanaan', 'kebutuhans.ket', 'kebutuhans.status', 'keuangan_perjadinlangsungs.kebutuhan_id', 'keuangan_perjadinlangsungs.id as idKeuangan', 'keuangan_perjadinlangsungs.uang_harian', 'keuangan_perjadinlangsungs.uang_harian_fullday', 'keuangan_perjadinlangsungs.uang_harian_fullboard', 'keuangan_perjadinlangsungs.uang_representasi', 'keuangan_perjadinlangsungs.persen_pajak', 'keuangan_perjadinlangsungs.jumlah_harga')
            ->where('keuangan_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();
        $pengemudi = DB::table('pegawais')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->join('peminjaman_kendaraan_dinas', 'peminjaman_kendaraan_dinas.pegawai_id', '=', 'pegawais.id')
            ->join('info_perjadinlangsungs', 'peminjaman_kendaraan_dinas.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('pegawais.id', 'pegawais.nama_lengkap', 'jabatans.nama_jabatan')
            ->where('jabatans.nama_jabatan', 'Pengemudi')
            ->where('peminjaman_kendaraan_dinas.info_perjadinlangsung', $id)
            ->get();
        return view('admin.perjadin.keuangan.detail', [
            'title' => 'Detail Keuangan Perjalanan Dinas',
            'perjadin' => Info_perjadinlangsung::find($id),
            'dokumen' => Dokumen::where('info_perjadinlangsung_id', $id)->get(),
            'pesertaPegawais' => $pesertaPegawais,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'kebutuhans' => $kebutuhans,
            'pengemudis' => $pengemudi,
            'ref_fasilitas' => DB::table('ref_fasilitas')->where('status','aktif')->get(),
        ]);
    }

    public function detail_perjadin_bendahara($id)
    {
        $pesertaPegawais = DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->select('pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'pegawais.NIP_NIK', 'pegawais.id', 'pegawais.nama_lengkap', 'data_perjadinlangsungs.id as idPeserta')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();

            
        $fasilitas = DB::table('data_perjadinlangsungs')
        ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
        ->leftJoin('keuangan_perjadinlangsungs', 'data_perjadinlangsungs.id', '=', 'keuangan_perjadinlangsungs.data_perjadinlangsungs')
        ->select('pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'pegawais.NIP_NIK', 'pegawais.id', 'pegawais.nama_lengkap', 'data_perjadinlangsungs.id as idPeserta', 'keuangan_perjadinlangsungs.id as idKeuangan', 'keuangan_perjadinlangsungs.akun_x_rkakl', 'keuangan_perjadinlangsungs.ref_sbm', 'keuangan_perjadinlangsungs.uang_harian', 'keuangan_perjadinlangsungs.uang_harian_fullday', 'keuangan_perjadinlangsungs.uang_harian_fullboard', 'keuangan_perjadinlangsungs.uang_representasi', 'keuangan_perjadinlangsungs.persen_pajak', 'keuangan_perjadinlangsungs.jumlah_harga', 'keuangan_perjadinlangsungs.status', 'keuangan_perjadinlangsungs.pph22', 'keuangan_perjadinlangsungs.pph23', 'keuangan_perjadinlangsungs.tgl_bayar', 'keuangan_perjadinlangsungs.ppn')
        ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
        ->whereNull('keuangan_perjadinlangsungs.kebutuhan_id')
        ->get();
        
        // dd($fasilitas);

        $pesertaNonPegawais = DB::table('data_perjadinlangsungs')
            ->join('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
            ->join('keuangan_perjadinlangsungs', 'data_perjadinlangsungs.id', '=', 'keuangan_perjadinlangsungs.data_perjadinlangsungs')
            ->select('keuangan_perjadinlangsungs.id  as idKeuangan', 'non_pegawais.id', 'non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'data_perjadinlangsungs.id as idData', 'keuangan_perjadinlangsungs.akun_x_rkakl', 'keuangan_perjadinlangsungs.ref_sbm', 'keuangan_perjadinlangsungs.uang_harian', 'keuangan_perjadinlangsungs.uang_harian_fullday','keuangan_perjadinlangsungs.uang_harian_fullboard','keuangan_perjadinlangsungs.uang_representasi','keuangan_perjadinlangsungs.persen_pajak', 'keuangan_perjadinlangsungs.jumlah_harga', 'keuangan_perjadinlangsungs.status', 'keuangan_perjadinlangsungs.pph22', 'keuangan_perjadinlangsungs.pph23', 'keuangan_perjadinlangsungs.tgl_bayar', 'keuangan_perjadinlangsungs.ppn')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->whereNull('keuangan_perjadinlangsungs.kebutuhan_id')
            ->get();

        $kebutuhans = DB::table('kebutuhans')
            ->join('keuangan_perjadinlangsungs', 'kebutuhans.id', '=', 'keuangan_perjadinlangsungs.kebutuhan_id')
            ->join('data_perjadinlangsungs', 'keuangan_perjadinlangsungs.data_perjadinlangsungs', '=', 'data_perjadinlangsungs.id')
            ->leftJoin('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->leftJoin('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
            ->select(
                'kebutuhans.id as idKebutuhan', 'pegawais.nama_lengkap',
                'kebutuhans.nama',
                'kebutuhans.jumlah_frekuensi',
                'kebutuhans.satuan',
                'kebutuhans.tipe_pendanaan',
                'kebutuhans.ket',
                'kebutuhans.status',
                'keuangan_perjadinlangsungs.kebutuhan_id as idKeuangan',
                'keuangan_perjadinlangsungs.info_perjadinlangsung',
                'keuangan_perjadinlangsungs.uang_harian',
                'keuangan_perjadinlangsungs.persen_pajak',
                'keuangan_perjadinlangsungs.jumlah_harga',
                'keuangan_perjadinlangsungs.akun_x_rkakl',
                'keuangan_perjadinlangsungs.ref_sbm',
                'keuangan_perjadinlangsungs.status as statusPembayaran',
                'keuangan_perjadinlangsungs.pph22',
                'keuangan_perjadinlangsungs.pph23',
                'keuangan_perjadinlangsungs.tgl_bayar',
                'keuangan_perjadinlangsungs.ppn',
                DB::raw('COALESCE(pegawais.nama_lengkap, non_pegawais.nama_lengkap, "Tanpa Terikat Pelaksana") as pelaksana')
                )
            ->where('keuangan_perjadinlangsungs.info_perjadinlangsung', $id)
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
            ->where('akun_x_rkakls.versi_id', session('versi'))
            ->get();

        $dokumen = DB::table('dokumens')
            ->where('info_perjadinlangsung_id', $id)
            ->get();

        return view('admin.perjadin.bendahara.detail', [
            'title' => 'Detail bendahara Perjalanan Dinas',
            'perjadin' => Info_perjadinlangsung::find($id),
            'pesertaPegawais' => $pesertaPegawais,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'kebutuhans' => $kebutuhans,
            'fasilitas' => $fasilitas,
            "sbms" => Ref_sbm::all(),
            'akuns' => $akuns,
            'dokumen' => $dokumen,
            'ref_fasilitas' => DB::table('ref_fasilitas')->where('status','aktif')->get(),
        ]);
    }

    public function detail_perjadin_HKT($id)
    {
        $selectPeserta = DB::table('data_perjadinlangsungs')
        ->join('pegawais', 'pegawai_id', '=', 'pegawais.id')
        ->select('data_perjadinlangsungs.id as idPeserta', 'data_perjadinlangsungs.status_persetujuan', 'data_perjadinlangsungs.id', 'data_perjadinlangsungs.status_pegawai', 'pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan')
        ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
        ->get();
        $selectPeserta_nonPegawai = DB::table('data_perjadinlangsungs')
        ->join('non_pegawais', 'non_pegawai_id', '=', 'non_pegawais.id')
        ->select('data_perjadinlangsungs.id as idPeserta', 'data_perjadinlangsungs.status_persetujuan', 'data_perjadinlangsungs.id', 'data_perjadinlangsungs.status_pegawai', 'non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan')
        ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
        ->get();

        $kebutuhans = DB::table('keuangan_perjadinlangsungs')
        ->join('kebutuhans', 'keuangan_perjadinlangsungs.kebutuhan_id', '=', 'kebutuhans.id')
        ->select('kebutuhans.id as idKebutuhan', 'kebutuhans.nama', 'kebutuhans.jumlah_frekuensi', 'kebutuhans.satuan', 'kebutuhans.tipe_pendanaan', 'kebutuhans.ket', 'keuangan_perjadinlangsungs.info_perjadinlangsung', 'keuangan_perjadinlangsungs.kebutuhan_id', 'keuangan_perjadinlangsungs.status')
        ->where('keuangan_perjadinlangsungs.info_perjadinlangsung', $id)
        ->get();
    $mobilitas = DB::table('peminjaman_kendaraan_dinas')
        ->join('pegawais', 'peminjaman_kendaraan_dinas.pegawai_id', '=', 'pegawais.id')
        ->join('kendaraans', 'peminjaman_kendaraan_dinas.kendaraan', '=', 'kendaraans.id')
        ->select('peminjaman_kendaraan_dinas.info_perjadinlangsung', 'pegawais.nama_lengkap', 'kendaraans.merek', 'kendaraans.no_polisi', 'peminjaman_kendaraan_dinas.status')
        ->where('peminjaman_kendaraan_dinas.info_perjadinlangsung', $id)
        ->get();

        $pengemudi = DB::table('data_perjadinlangsungs')
            ->leftJoin('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->leftJoin('non_pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'non_pegawais.id')
            ->join('peminjaman_kendaraan_dinas', 'data_perjadinlangsungs.pegawai_id', '=', 'peminjaman_kendaraan_dinas.pegawai_id')
            ->join('kendaraans', 'peminjaman_kendaraan_dinas.kendaraan', '=', 'kendaraans.id')
            ->select(
                DB::raw('COALESCE(pegawais.nama_lengkap, non_pegawais.nama_lengkap) AS nama_lengkap'),
                DB::raw('COALESCE(pegawais.pangkat, non_pegawais.pangkat) AS pangkat'),
                DB::raw('COALESCE(pegawais.golongan, non_pegawais.golongan) AS golongan'),
                'data_perjadinlangsungs.status_pegawai',
                'kendaraans.merek',
                'kendaraans.no_polisi'
            )
            ->where('peminjaman_kendaraan_dinas.info_perjadinlangsung', $id)
            ->distinct()
            ->get();

        return view('admin.perjadin.HKT.detail', [
            'title' => 'Pengajuan Surtug',
            'perjadin' => Info_perjadinlangsung::find($id),
            'mobilitass' => Peminjaman_kendaraan_dinas::where('info_perjadinlangsung', $id)->get(),
            'pengemudis' => $pengemudi,
            'perjadin' => Info_perjadinlangsung::find($id),
            'selectPesertas' => $selectPeserta,
            "fasilitas" => $kebutuhans,
            'mobilitass' => $mobilitas,
            'selectPesertasNonPegawais' => $selectPeserta_nonPegawai,
            'dokumen' => Dokumen::where('info_perjadinlangsung_id', $id)->first()
        ]);
    }
    public function surtug_perjadin_HKT($id)
    {
        $surat = DB::table('surtug_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'surtug_perjadinlangsungs.id_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('surtug_perjadinlangsungs.nomor_surat', 'surtug_perjadinlangsungs.perihal', 'surtug_perjadinlangsungs.paragraf_1', 'surtug_perjadinlangsungs.paragraf_2', 'surtug_perjadinlangsungs.paragraf_3')
            ->where('surtug_perjadinlangsungs.id_perjadinlangsung', $id)
            ->get();

        return view('admin.perjadin.HKT.surtug', [
            'title' => 'Pembuatan Surat Tugas',
            'perjadin' => Info_perjadinlangsung::find($id),
            'surtugs' => $surat,

        ]);
    }

    public function detail_surtug_perjadin_HKT($id)
    {
        $pesertaPegawais = DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->select('pegawais.id','pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'pegawais.NIP_NIK', 'jabatans.nama_jabatan')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->where(function ($query) {
                $query->where('data_perjadinlangsungs.status_pegawai', '!=', 'Supir')
                    ->orWhere(function ($query) {
                        $query->where('data_perjadinlangsungs.status_pegawai', '=', 'Supir')
                            ->whereNotExists(function ($query) {
                                $query->select(DB::raw(1))
                                    ->from('data_perjadinlangsungs as dp2')
                                    ->whereColumn('dp2.pegawai_id', 'data_perjadinlangsungs.pegawai_id')
                                    ->where('dp2.status_pegawai', '!=', 'Supir');
                            });
                    });
            })
            ->orderBy('data_perjadinlangsungs.status_pegawai', 'asc')
            ->get();


        $pesertaNonPegawais = DB::table('data_perjadinlangsungs')
            ->join('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
            ->select('non_pegawais.id','non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'non_pegawais.NIP_NIK', 'data_perjadinlangsungs.status_pegawai')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();

        $pengemudi = DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->select('pegawais.id','pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'pegawais.NIP_NIK', 'jabatans.nama_jabatan')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->where('data_perjadinlangsungs.status_pegawai', '=', 'Supir')
            ->orderBy('data_perjadinlangsungs.status_pegawai', 'asc')
            ->get();

        $surat = DB::table('surtug_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'surtug_perjadinlangsungs.id_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('surtug_perjadinlangsungs.nomor_surat', 'surtug_perjadinlangsungs.perihal', 'surtug_perjadinlangsungs.paragraf_1', 'surtug_perjadinlangsungs.paragraf_2', 'surtug_perjadinlangsungs.paragraf_3')
            ->where('surtug_perjadinlangsungs.id_perjadinlangsung', $id)
            ->get();
        $tipeSurtug = DB::table('surtug_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'surtug_perjadinlangsungs.id_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('surtug_perjadinlangsungs.is_table AS isTable')
            ->where('surtug_perjadinlangsungs.id_perjadinlangsung', $id)
            ->first();
            
        $pegawaiKepala = DB::table('ref_penandatangans')
            ->join('pegawais', 'pegawais.id', '=', 'ref_penandatangans.pegawai_id')
            ->select('pegawais.*')
            ->where('ref_penandatangans.posisi_penandatangan', 'Kepala')
            ->where('ref_penandatangans.status_penandatangan', 'aktif')
            ->first();

        return view('admin.perjadin.HKT.preview_surtug', [
            'title' => 'Pembuatan Surat Tugas',
            'pegawaiKepala' => $pegawaiKepala,
            'pesertaPegawais' => $pesertaPegawais,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'perjadin' => Info_perjadinlangsung::find($id),
            'pengemudis' => $pengemudi,
            'surtugs' => $surat,
            'tipeSurtug' => $tipeSurtug
        ]);
    }
    
    public function note_perjadin($id)
    {
        return view(
            'admin.perjadin.keuangan.laporan_perjadin',
            [
                'title' => 'Detail Kegiatanku',
                'perjadin' => Info_perjadinlangsung::find($id),
                'dokumen' => Dokumen::where('info_perjadinlangsung_id', $id)->get(),
            ]
        );
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
    public function updateKendaraan(Request $request)
    {
        $action = $request->input('action');

        if ($action === 'update') {
            $numMobilitas = $request->input('numMobilitas');

            for ($i = 0; $i < $numMobilitas; $i++) {
                $idMobilitas = $request->input('idMobilitas_' . $i);
                $kendaraan = $request->input('mobil_' . $i);

                // Update peminjaman kendaraan dinas
                DB::table('peminjaman_kendaraan_dinas')
                    ->where('id', $idMobilitas)
                    ->update([
                        'kendaraan' => $kendaraan,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);

                // Update kendaraan
                DB::table('kendaraans')
                    ->join('peminjaman_kendaraan_dinas', 'peminjaman_kendaraan_dinas.kendaraan', '=', 'kendaraans.id')
                    ->where('kendaraans.id', $kendaraan)
                    ->update([
                        'kendaraans.updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);


            }

            DB::table('info_perjadinlangsungs')
                ->where('id', $request->input('idPerjadin'))
                ->update([
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

                return redirect()->route('mobilitas-perjadin', ['status' => $request->input('perjadinStatus')])->with('success', 'Data telah diperbaharui!');
        }
    }

    public function store(Request $request)
    {
        $action = $request->input('action');

        $pengusul = DB::table('pegawais')
            ->join('info_perjadinlangsungs', 'pegawais.id', '=', 'info_perjadinlangsungs.id_pengaju')
            ->select('info_perjadinlangsungs.id_pengaju', 'pegawais.nama_lengkap')
            ->where('info_perjadinlangsungs.id',$request->input('idPerjadin'))
            ->first(); // Mengambil hasil pertama


        $dokumen = DB::table('dokumens')
            ->where('dokumens.info_perjadinlangsung_id',$request->input('idPerjadin'))
            ->first();

        $suratUndangan = $dokumen->surat_undangan;

        if ($action === 'proses') {
            $numMobilitas = $request->input('numMobilitas');

            for ($i = 0; $i < $numMobilitas; $i++) {
                $idMobilitas = $request->input('idMobilitas_' . $i);
                $kendaraan = $request->input('mobil_' . $i);
                $namaKegiatan = $request->input('nama_kegiatan_' . $i);
                $provinsi = $request->input('provinsi_' . $i);
                $alamat = $request->input('alamat_' . $i);
                $kabKota = $request->input('kabupaten_kota_' . $i);
                $supir = $request->input('supir_' . $i);
                $status = $request->input('status_' . $i);
                $berangkat = $request->input('tglBerangkat_' . $i);
                $selesai = $request->input('tglSelesai_' . $i);
                $ketMobilitas = $request->input('ket_' . $i);
                $gabungSurtug = $request->input('gabungSurtug_' . $i);

                // Konversi format tanggal
                $berangkat = Carbon::createFromFormat('d-m-Y H:i', $berangkat)->format('Y-m-d H:i:s');
                $selesai = Carbon::createFromFormat('d-m-Y H:i', $selesai)->format('Y-m-d H:i:s');

                // Update kendaraan
                DB::table('kendaraans')
                    ->join('peminjaman_kendaraan_dinas', 'peminjaman_kendaraan_dinas.kendaraan', '=', 'kendaraans.id')
                    ->where('kendaraans.id', $kendaraan)
                    ->update([
                        'kendaraans.updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);

                if(!$gabungSurtug){

                    if($ketMobilitas == "Antar"){
                        $namaKegiatan = "Mengantar Pelaksana Tugas ".$namaKegiatan;
                    } elseif ($ketMobilitas == "Jemput") {
                        $namaKegiatan = "Menjemput Pelaksana Tugas ".$namaKegiatan;
                    } elseif ($ketMobilitas == "Antar-Jemput") {
                        $namaKegiatan = "Mengantar dan Menjemput Pelaksana Tugas ".$namaKegiatan;
                    }


                    $versi = Versi::where('status', 'aktif')->get();
                    DB::table('info_perjadinlangsungs')->insertOrIgnore([
                        'nama_kegiatan' => $namaKegiatan,
                        'tgl_mulai' => $berangkat,
                        'tgl_selesai' => $selesai,
                        'tgl_keberangkatan' => $berangkat,
                        'tgl_kepulangan' => $selesai,
                        'provinsi' => $provinsi,
                        'kabupaten_kota' => $kabKota,
                        'alamat' => $alamat,
                        'mobilitas' => "Kendaraan Dinas",
                        'pemberi_undangan' => "-",
                        'tanggal_surat' => "-",
                        'keterangan_mobilitas' => $ketMobilitas, // Tambahkan ini
                        'status_pengajuan' => 'Draf-pengajuan',
                        'versi_id' => $versi[0]->id,
                        'created_at' => now()->format('Y-m-d H:i:s'),
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);

                    $perjadinBaru = Info_perjadinlangsung::max('id');

                    // Update peminjaman kendaraan dinas
                    DB::table('peminjaman_kendaraan_dinas')
                    ->where('id', $idMobilitas)
                    ->update([
                        'info_perjadinlangsung' => $perjadinBaru,
                        'updated_at' => now()->format('Y-m-d H:i:s')
                    ]);
                } else {
                    // Update peminjaman kendaraan dinas
                    DB::table('peminjaman_kendaraan_dinas')
                    ->where('id', $idMobilitas)
                    ->update([
                        'pegawai_id' => $supir,
                        'status' => $status,
                        'tgl_keberangkatan' => $berangkat,
                        'tgl_selesai' => $selesai,
                        'ket_mobilitas' => $ketMobilitas,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
                }

                $berangkat = $request->input('berangkat_' . $i);
                $selesai = $request->input('selesai_' . $i);
                $berangkat = Carbon::createFromFormat('d-m-Y H:i', $berangkat)->format('Y-m-d H:i:s');
                $selesai = Carbon::createFromFormat('d-m-Y H:i', $selesai)->format('Y-m-d H:i:s');

                // Jika status adalah 'proses'
                if ($status === 'proses') {
                    if ($gabungSurtug) {
                        DB::table('data_perjadinlangsungs')->insertOrIgnore([
                            'status_pegawai' => 'Supir',
                            'info_perjadinlangsung' => $request->input('idPerjadin'),
                            'pegawai_id' => $supir,
                            'tgl_keberangkatan' => $berangkat,
                            'tgl_selesai' => $selesai,
                            'status_persetujuan' => 'Proses Persetujuan',
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);

                        $data_perjaidinlangsung_max = DB::table('data_perjadinlangsungs')->latest()->first();

                        DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
                            'info_perjadinlangsung' => $request->input('idPerjadin'),
                            'data_perjadinlangsungs' => $data_perjaidinlangsung_max->id,
                            'status' => 'Menunggu Persetujuan Bendahara',
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                    } else {
                        DB::table('data_perjadinlangsungs')->insertOrIgnore([
                            'status_pegawai' => 'Supir',
                            'info_perjadinlangsung' => $perjadinBaru,
                            'pegawai_id' => $supir,
                            'tgl_keberangkatan' => $berangkat,
                            'tgl_selesai' => $selesai,
                            'status_persetujuan' => 'Proses Persetujuan',
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);

                        $data_perjaidinlangsung_max = DB::table('data_perjadinlangsungs')->latest()->first();

                        DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
                            'info_perjadinlangsung' => $perjadinBaru,
                            'data_perjadinlangsungs' => $data_perjaidinlangsung_max->id,
                            'status' => 'Menunggu Persetujuan Bendahara',
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);

                        DB::table('dokumens')->insert([
                            'info_perjadinlangsung_id' => $perjadinBaru,
                            'status_persetujuan' => 'pengajuan',
                            'surat_undangan' => $suratUndangan,
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                    }
                }

                if (!$gabungSurtug) {
                    DB::table('info_perjadinlangsungs')
                    ->where('id', $perjadinBaru)
                    ->update([
                        'is_acceptBMN' => 'proses',
                        'is_acceptHKT' => 'pengajuan',
                        'status_pengajuan'  => 'pengajuan',
                        'status_pengajuan_detail' => 'Verifikasi-HKT',
                        'id_pengaju' => auth('administrator')->user()->id,
                        'admin_BMN' => auth('administrator')->user()->id,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
                }

            }
            DB::table('info_perjadinlangsungs')
                    ->where('id', $request->input('idPerjadin'))
                    ->update([
                        'is_acceptBMN' => 'proses',
                        'is_acceptHKT' => 'pengajuan',
                        'status_pengajuan'  => 'pengajuan',
                        'status_pengajuan_detail' => 'Verifikasi-HKT',
                        'admin_BMN' => auth('administrator')->user()->id,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);

            $dataNotif = [
                'id_kegiatan' => $request->input('idPerjadin'),
                'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
                'to' => 0, // ID pengguna yang menerima
                'role' => 'HKT', // Peran pengguna
                'header' => 'Usulan Perjalanan Dinas - '.$request->input('idPerjadin'), // Judul notifikasi
                'message' => 'Usulan '.$request->input('idPerjadin').' telah diverifikasi BMN', // Isi pesan
                'route' => 'perjadin-HKT/detail/'.$request->input('idPerjadin'), // Route yang dituju
                'is_read' => 0, // Status belum dibaca
                'versi_id' => session('versi'),
                'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
            ];

            // Melakukan insert ke tabel notifications
            DB::table('notifications')->insert($dataNotif);

            $dataNotifUser = [
                    'id_kegiatan' => $request->input('idPerjadin'),
                    'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
                    'to' => '0', // ID pengguna yang menerima
                    'role' => null, // Peran pengguna
                    'header' => 'Usulan Perjadin Disetujui BMN - '.$request->input('idPerjadin'), // Judul notifikasi
                    'message' => 'Usulan yang diajukan dengan id '.$request->input('idPerjadin').' telah diproses BMN harap menunggu Verifikasi-HKT', // Isi pesan
                    'route' => 'perjadin/riwayat/proses', // Route yang dituju
                    'is_read' => 0, // Status belum dibaca
                    'versi_id' => session('versi'),
                    'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
                    ];

                // Melakukan insert ke tabel notifications
                DB::table('notifications')->insert($dataNotifUser);


            return redirect()->route('mobilitas-perjadin', ['status' => $request->input('perjadinStatus')])->with('success', 'Data telah diperbaharui!');
        } elseif ($action === 'tolak') {
            DB::table('info_perjadinlangsungs')
                ->where('id', $request->input('idPerjadin'))
                ->update([
                    'status_pengajuan_detail' => 'Verifikasi-BMN-ditolak',
                    'status_pengajuan' => 'ditolak',
                    'is_acceptBMN' => 'ditolak',
                    'alasan_penolakan' =>  $request->input('alasan'),
                    'admin_BMN' => auth('administrator')->user()->id,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

            DB::table('data_perjadinlangsungs')
                ->where('info_perjadinlangsung', $request->input('idPerjadin'))
                ->update([
                    'status_persetujuan' => 'Ditolak',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);
            DB::table('peminjaman_kendaraan_dinas')
                ->where('info_perjadinlangsung', $request->input('idPerjadin'))
                ->update([
                    'status' => 'ditolak',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

            $dataNotif = [
                'id_kegiatan' => $request->input('idPerjadin'),
                'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
                'to' => '0', // ID pengguna yang menerima
                'role' => null, // Peran pengguna
                'header' => 'Usulan Perjalanan Dinas Ditolak - '.$request->input('idPerjadin'), // Judul notifikasi
                'message' => 'Usulan yang diajukan dengan id '.$request->input('idPerjadin').' ditolak oleh BMN', // Isi pesan
                'route' => 'perjadin/riwayat/ditolak', // Route yang dituju
                'is_read' => 0, // Status belum dibaca
                'versi_id' => session('versi'),
                'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
                ];

            // Melakukan insert ke tabel notifications
            DB::table('notifications')->insert($dataNotif);

            return redirect()->route('mobilitas-perjadin', ['status' => $request->input('perjadinStatus')])->with('success', 'Pengajuan Telah Ditolak!');
        }
    }

    public function storeMobilitas(Request $request)
    {
        $gabungSurtug = $request->gabungSurtug;
        // Pengecekan apakah semua data ada
        $requiredFields = [
            'kendaraan',
            'pengemudi',
            'ket_mobilitas',
            'tgl_keberangkatan'
        ];

        foreach ($requiredFields as $field) {
            if (is_null($request->input($field))) {
                return redirect()->route('detail-mobilitas-perjadin', ['id' => $request->idPerjadin])
                                ->with('success', 'Data tidak berhasil ditambahkan, data tidak lengkap');
            }
        }

        db::table('peminjaman_kendaraan_dinas')->insertOrIgnore([
            'info_perjadinlangsung' => $request->idPerjadin,
            'kendaraan' => $request->kendaraan,
            'pegawai_id' => $request->pengemudi,
            'ket_mobilitas' => $request->ket_mobilitas,
            'tgl_keberangkatan' => $request->tgl_keberangkatan,
            'tgl_selesai' => $request->tgl_keberangkatan,
            'status' => "pengajuan",
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);
        if ($gabungSurtug) {
        }

        DB::table('info_perjadinlangsungs')
            ->where('id', $request->idPerjadin)
            ->update([
                'is_acceptBMN' => 'pengajuan',
                'admin_BMN' => auth('administrator')->user()->id,
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

        return redirect()->route('detail-mobilitas-perjadin', ['id' => $request->idPerjadin])->with('success', 'Data telah ditambahkan, silahkan isi supir dan kendaraannya!');
    }

    public function storeKeuangan(Request $request)
    {
        $action = $request->input('action');

        $pengusul = DB::table('pegawais')
            ->join('info_perjadinlangsungs', 'pegawais.id', '=', 'info_perjadinlangsungs.id_pengaju')
            ->select('info_perjadinlangsungs.id_pengaju', 'pegawais.nama_lengkap')
            ->where('info_perjadinlangsungs.id',$request->idPerjadin)
            ->first();

        if ($action === 'verifikasi') {
            $totalpesertapegawai = $request->numPegawai;
            for ($i = 0; $i < $totalpesertapegawai; $i++) {
                $idpesertapegawai = 'idPesertaPegawai_' . $i;
                $statuspersetujuan = 'statuspegawai_' . $i;
                DB::table('data_perjadinlangsungs')
                    ->where('id', $request->$idpesertapegawai)
                    ->update([
                        'status_persetujuan' => 'Disetujui',
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
            }

            $totalnonpesertapegawai = $request->numNonPegawai;
            for ($i = 0; $i < $totalnonpesertapegawai; $i++) {
                $idpesertapegawai = 'idNonPesertaPegawai_' . $i;
                $statuspersetujuan = 'statusnonpegawai_' . $i;
                DB::table('data_perjadinlangsungs')
                    ->where('id', $request->$idpesertapegawai)
                    ->update([
                        'status_persetujuan' => 'Disetujui',
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
            }

            $numkebutuhan = $request->numKebutuhan;
            for ($i = 0; $i < $numkebutuhan; $i++) {
                $idKeuangan = 'idKeuanganKebutuhan_' . $i;
                $idKebutuhan = 'idKebutuhan_' . $i;
                $harga = 'nominalKebutuhan_' . $i;
                $pajak = 'pajakKebutuhan_' . $i;
                $pph22 = 'pajakKebutuhan22_' . $i;
                $pph23 = 'pajakKebutuhan23_' . $i;
                $ppn = 'ppnKebutuhan_' . $i;
                $nominal = 'totalKebutuhan_' . $i;
                $statusKesesuaian = 'kesesuaianKebutuhan_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('id', $request->$idKeuangan)
                    ->update([
                        'harga' => $request->$harga,
                        'persen_pajak' => $request->$pajak,
                        'pph22' => $request->$pph22,
                        'pph23' => $request->$pph23,
                        'ppn' => $request->$ppn,
                        'jumlah_harga' => $request->$nominal,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);

                DB::table('kebutuhans')
                    ->where('id', $request->$idKebutuhan)
                    ->update([
                        'status' => $request->$statusKesesuaian,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
            }

            DB::table('dokumens')
                ->where('id', $request->idDokumen)
                ->update([
                    'ket' => $request->keterangandokumen,
                    'status_persetujuan' => 'sesuai',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

            DB::table('info_perjadinlangsungs')
                ->where('id', $request->idPerjadin)
                ->update([
                    'is_acceptKeu' => 'selesai',
                    'is_acceptBend' => 'approval-2',
                    'status_pengajuan_detail' => 'Approval-2-Bendahara',
                    'admin_Keu' => auth('administrator')->user()->id,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

            return redirect()->route('keuangan-perjadin', ['status' => 'verifikasi-1'])->with('success', 'Data Telah diperbaharui dan telah dikirim ke bagian bendahara!');
        } elseif ($action === 'verifikasi-2') {
            $totalpesertapegawai = $request->numPegawai;
            for ($i = 0; $i < $totalpesertapegawai; $i++) {
                $idpesertapegawai = 'idPesertaPegawai_' . $i;
                $statuspersetujuan = 'statuspegawai_' . $i;
                DB::table('data_perjadinlangsungs')
                    ->where('id', $request->$idpesertapegawai)
                    ->update([
                        'status_persetujuan' => 'Disetujui',
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
            }

            $totalnonpesertapegawai = $request->numNonPegawai;
            for ($i = 0; $i < $totalnonpesertapegawai; $i++) {
                $idpesertapegawai = 'idNonPesertaPegawai_' . $i;
                $statuspersetujuan = 'statusnonpegawai_' . $i;
                DB::table('data_perjadinlangsungs')
                    ->where('id', $request->$idpesertapegawai)
                    ->update([
                        'status_persetujuan' => 'Disetujui',
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
            }

            $numkebutuhan = $request->numKebutuhan;
            for ($i = 0; $i < $numkebutuhan; $i++) {
                $idKeuangan = 'idKeuanganKebutuhan_' . $i;
                $idKebutuhan = 'idKebutuhan_' . $i;
                $harga = 'nominalKebutuhan_' . $i;
                $pajak = 'pajakKebutuhan_' . $i;
                $pph22 = 'pajakKebutuhan22_' . $i;
                $pph23 = 'pajakKebutuhan23_' . $i;
                $ppn = 'ppnKebutuhan_' . $i;
                $nominal = 'totalKebutuhan_' . $i;
                $statusKesesuaian = 'kesesuaianKebutuhan_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('id', $request->$idKeuangan)
                    ->update([
                        // 'uang_harian' => $request->$harga,
                        'persen_pajak' => $request->$pajak,
                        'pph22' => $request->$pph22,
                        'pph23' => $request->$pph23,
                        'ppn' => $request->$ppn,
                        'jumlah_harga' => $request->$nominal,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
            }

            DB::table('dokumens')
                ->where('id', $request->idDokumen)
                ->update([
                    'ket' => $request->keterangandokumen,
                    'tgl_penerimaan_surtug' => $request->tgl_surtug,
                    'tgl_penerimaan_undangan' => $request->tgl_surtug,
                    'tgl_penerimaan_SPPD' => $request->tgl_surtug,
                    'tgl_penerimaan_lap_keu' => $request->tgl_surtug,
                    'tgl_penerimaan_lap_perjadin' => $request->tgl_surtug,
                    'status_persetujuan' => 'sesuai',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

            DB::table('info_perjadinlangsungs')
                ->where('id', $request->idPerjadin)
                ->update([
                    'is_acceptBMN' => 'selesai',
                    'is_acceptKeu' => 'selesai',
                    'is_acceptBend' => 'approval-2',
                    'status_pengajuan_detail' => 'approval-2-Bendahara',
                    'admin_Keu' => auth('administrator')->user()->id,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

            $dataNotif = [
                    'id_kegiatan' => $request->idPerjadin,
                    'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
                    'to' => 0, // ID pengguna yang menerima
                    'role' => 'Bendahara', // Peran pengguna
                    'header' => 'Usulan Perjalanan Dinas Verif-2 - '.$request->idPerjadin, // Judul notifikasi
                    'message' => 'Perjalanan dinas'.$request->idPerjadin.' telah di Verifikasi-2 oleh Keuangan', // Isi pesan
                    'route' => 'perjadin-bendahara/detail/'.$request->idPerjadin, // Route yang dituju
                    'is_read' => 0, // Status belum dibaca
                    'versi_id' => session('versi'),
                    'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
                ];

                // Melakukan insert ke tabel notifications
                DB::table('notifications')->insert($dataNotif);

            $dataNotifUser = [
                    'id_kegiatan' => $request->idPerjadin,
                    'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
                    'to' => '0', // ID pengguna yang menerima
                    'role' => null, // Peran pengguna
                    'header' => 'Pelaporan Perjadin Disetujui Keuangan - '.$request->idPerjadin, // Judul notifikasi
                    'message' => 'Pelaporan yang telah dikirimkan pada Perjadin '.$request->idPerjadin.' telah disetujui Keuangan, silakan menunggu Approval-2 Bendahara', // Isi pesan
                    'route' => 'perjadin/riwayat/selesai', // Route yang dituju
                    'is_read' => 0, // Status belum dibaca
                    'versi_id' => session('versi'),
                    'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
                    ];

            //     // Melakukan insert ke tabel notifications
                DB::table('notifications')->insert($dataNotifUser);

            return redirect()->route('keuangan-perjadin', ['status' => 'verifikasi-2'])->with('success', 'Data Telah diperbaharui dan telah dikirim ke bagian bendahara!');
        } elseif ($action === 'revisi_user') {
            $totalpesertapegawai = $request->numPegawai;
            for ($i = 0; $i < $totalpesertapegawai; $i++) {
                $idpesertapegawai = 'idPesertaPegawai_' . $i;
                $statuspersetujuan = 'statuspegawai_' . $i;
                DB::table('data_perjadinlangsungs')
                    ->where('id', $request->$idpesertapegawai)
                    ->update([
                        'status_persetujuan' => 'Revisi',
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
            }

            $totalnonpesertapegawai = $request->numNonPegawai;
            for ($i = 0; $i < $totalnonpesertapegawai; $i++) {
                $idpesertapegawai = 'idNonPesertaPegawai_' . $i;
                $statuspersetujuan = 'statusnonpegawai_' . $i;
                DB::table('data_perjadinlangsungs')
                    ->where('id', $request->$idpesertapegawai)
                    ->update([
                        'status_persetujuan' => 'Revisi',
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
            }

            $numkebutuhan = $request->numKebutuhan;
            for ($i = 0; $i < $numkebutuhan; $i++) {
                $idKeuangan = 'idKeuanganKebutuhan_' . $i;
                $idKebutuhan = 'idKebutuhan_' . $i;
                $harga = 'nominalKebutuhan_' . $i;
                $pajak = 'pajakKebutuhan_' . $i;
                $pph22 = 'pajakKebutuhan22_' . $i;
                $pph23 = 'pajakKebutuhan23_' . $i;
                $ppn = 'ppnKebutuhan_' . $i;
                $nominal = 'totalKebutuhan_' . $i;
                $statusKesesuaian = 'kesesuaianKebutuhan_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('id', $request->$idKeuangan)
                    ->update([
                        'uang_harian' => $request->$harga,
                        'persen_pajak' => $request->$pajak,
                        'pph22' => $request->$pph22,
                        'pph23' => $request->$pph23,
                        'ppn' => $request->$ppn,
                        'jumlah_harga' => $request->$nominal,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
            }

            DB::table('dokumens')
                ->where('id', $request->idDokumen)
                ->update([
                    'ket' => $request->keterangandokumen,
                    'status_persetujuan' => 'revisi',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);
            if ($request->statusPerjadin == 'verifikasi-2') {
                DB::table('info_perjadinlangsungs')
                    ->where('id', $request->idPerjadin)
                    ->update([
                        'status_pengajuan' => 'revisi',
                        'status_pengajuan_detail' => 'verifikasi-2-keu-revisi',
                        'alasan_penolakan' => $request->alasan_user,
                        'is_acceptKeu' => 'revisi-2',
                        'admin_Keu' => auth('administrator')->user()->id,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
                return redirect()->route('keuangan-perjadin', ['status' => 'revisi-2'])->with('success', 'Data Telah diperbaharui dan telah dikirim ke user untuk direvisi!');
            }
            if ($request->statusPerjadin == 'verifikasi-2') {
                DB::table('info_perjadinlangsungs')
                    ->where('id', $request->idPerjadin)
                    ->update([
                        'status_pengajuan' => 'revisi',
                        'is_acceptKeu' => 'revisi-2',
                        'admin_Keu' => auth('administrator')->user()->id,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
                return redirect()->route('keuangan-perjadin', ['status' => 'revisi-2'])->with('success', 'Data Telah diperbaharui dan telah dikirim ke user untuk direvisi!');
            }
        } elseif ($action === 'revisi_HKT') {
            DB::table('dokumens')
                ->where('id', $request->idDokumen)
                ->update([
                    'ket' => $request->keterangandokumen,
                    'status_persetujuan' => 'revisi',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);
            if ($request->statusPerjadin == 'verifikasi-2') {
                DB::table('info_perjadinlangsungs')
                    ->where('id', $request->idPerjadin)
                    ->update([
                        'status_pengajuan' => 'revisi',
                        'is_acceptKeu' => 'revisi-1',
                        'is_acceptHKT' => 'revisi',
                        'admin_Keu' => auth('administrator')->user()->id,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
                return redirect()->route('keuangan-perjadin', ['status' => 'verifikasi-1'])->with('success', 'Data Telah diperbaharui dan telah dikirim ke HKT untuk direvisi!');
            }
        } elseif ($action === 'tolak') {
            DB::table('info_perjadinlangsungs')
                ->where('id', $request->idPerjadin)
                ->update([
                    'status_pengajuan' => 'ditolak',
                    'status_pengajuan_detail' => 'verifikasi-2-keu-ditolak',
                    'is_acceptKeu' => 'ditolak',
                    'admin_Keu' => auth('administrator')->user()->id,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

                
            DB::table('data_perjadinlangsungs')
                ->where('info_perjadinlangsung', $request->idPerjadin)
                ->update([
                    'status_persetujuan' => 'Ditolak',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

                DB::table('peminjaman_kendaraan_dinas')
                ->where('info_perjadinlangsung', $request->idPerjadin)
                ->update([
                    'status' => 'ditolak',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

            return redirect()->route('keuangan-perjadin', ['status' => 'verifikasi-2'])->with('success', 'Data Telah anda tolak!');
        }
        // ini untuk tombol simpan
        elseif ($action === 'simpan') {
            $totalpesertapegawai = $request->numPegawai;
            for ($i = 0; $i < $totalpesertapegawai; $i++) {
                $idpesertapegawai = 'idPesertaPegawai_' . $i;
                $statuspersetujuan = 'statuspegawai_' . $i;
                DB::table('data_perjadinlangsungs')
                    ->where('id', $request->$idpesertapegawai)
                    ->update([
                        'status_persetujuan' => 'Proses Persetujuan',
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
            }

            $totalnonpesertapegawai = $request->numNonPegawai;
            for ($i = 0; $i < $totalnonpesertapegawai; $i++) {
                $idpesertapegawai = 'idNonPesertaPegawai_' . $i;
                $statuspersetujuan = 'statusnonpegawai_' . $i;
                DB::table('data_perjadinlangsungs')
                    ->where('id', $request->$idpesertapegawai)
                    ->update([
                        'status_persetujuan' => 'Proses Persetujuan',
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
            }

            $numkebutuhan = $request->numKebutuhan;
            for ($i = 0; $i < $numkebutuhan; $i++) {
                $idKeuangan = 'idKeuanganKebutuhan_' . $i;
                $idKebutuhan = 'idKebutuhan_' . $i;
                $harga = 'nominalKebutuhan_' . $i;
                $pajak = 'pajakKebutuhan_' . $i;
                $pph22 = 'pajakKebutuhan22_' . $i;
                $pph23 = 'pajakKebutuhan23_' . $i;
                $ppn = 'ppnKebutuhan_' . $i;
                $nominal = 'totalKebutuhan_' . $i;
                $statusKesesuaian = 'kesesuaianKebutuhan_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('id', $request->$idKeuangan)
                    ->update([
                        'uang_harian' => $request->$harga,
                        'persen_pajak' => $request->$pajak,
                        'pph22' => $request->$pph22,
                        'pph23' => $request->$pph23,
                        'ppn' => $request->$ppn,
                        'jumlah_harga' => $request->$nominal,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
            }

            DB::table('dokumens')
                ->where('id', $request->idDokumen)
                ->update([
                    'ket' => $request->keterangandokumen,
                    'status_persetujuan' => $request->persetujuandokumen,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

            return redirect()->route('keuangan-perjadin', ['status' => 'verifikasi-2'])->with('success', 'Data Telah diperbaharui dan telah dikirim ke user untuk direvisi!');
        }
    }

    public function storeBendahara(Request $request)
    {
        $action = $request->input('action');

        $pengusul = DB::table('pegawais')
            ->join('info_perjadinlangsungs', 'pegawais.id', '=', 'info_perjadinlangsungs.id_pengaju')
            ->select('info_perjadinlangsungs.id_pengaju', 'pegawais.nama_lengkap')
            ->where('info_perjadinlangsungs.id',$request->idPerjadin)
            ->first();

        if ($action === 'approval') {
            $totalpesertapegawai = $request->numPegawai;
            for ($i = 0; $i < $totalpesertapegawai; $i++) {
                $idpesertapegawai = 'idKeuangan_' . $i;
                $uangharian = 'uang_harian' . $i;
                $uangfullday = 'uang_harian_fullday' . $i;
                $uangfullboard = 'uang_harian_fullboard' . $i;
                $uangrepresentasi = 'uang_representasi' . $i;
                $totaluangpeserta = 'total_' . $i;
                $tglbayar = 'tglbayar_' . $i;
                // $refSBM = 'sbmPegawai_' . $i;
                $akunPeserta = 'akunPegawai_' . $i;
                $statuspersetujuan = 'statuspegawai_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('data_perjadinlangsungs', $request->$idpesertapegawai)
                    ->update([
                        'uang_harian' => $request->$uangharian,
                        'uang_harian_fullday' => $request->$uangfullday,
                        'uang_harian_fullboard' => $request->$uangfullboard,
                        'uang_representasi' => $request->$uangrepresentasi,
                        'jumlah_harga' => $request->$totaluangpeserta,
                        'tgl_bayar' => $request->$tglbayar,
                        // 'ref_sbm' => $request->$refSBM,
                        'status' => $request->$statuspersetujuan,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
            }

            $totalnonpesertapegawai = $request->numNonPegawai;
            for ($i = 0; $i < $totalnonpesertapegawai; $i++) {
                $idNonPeserta = 'idNonPesertaPegawai_' . $i;
                $uanghariannon = 'uang_non_harian' . $i;
                $uangfulldaynon = 'uang_non_harian_fullday' . $i;
                $uangfullboardnon = 'uang_non_harian_fullboard' . $i;
                $uangrepresentasinon = 'uang_non_harian_representasi' . $i;
                // $nominalNonPeserta = 'nominalNon_' . $i;
                $totaluangnonpeserta = 'totalNon_' . $i;
                $tglbayar = 'tglbayarnon_' . $i;
                // $refSBMnonpeserta = 'sbmNonPegawai_' . $i;
                $akunnonpeserta = 'akunNonPegawai_' . $i;
                $statuspembayarannonpesera = 'statusnonpegawai_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('data_perjadinlangsungs', $request->$idNonPeserta)
                    ->update([
                        'uang_harian' => $request->$uanghariannon,
                        'uang_harian_fullday' => $request->$uangfulldaynon,
                        'uang_harian_fullboard' => $request->$uangfullboardnon,
                        'uang_representasi' => $request->$uangrepresentasinon,
                        'jumlah_harga' => $request->$totaluangnonpeserta,
                        'tgl_bayar' => $request->$tglbayar,
                        // 'ref_sbm' => $request->$refSBMnonpeserta,
                        'status' => $request->$statuspembayarannonpesera,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
            }

            $numkebutuhan = $request->numKebutuhan;
            for ($i = 0; $i < $numkebutuhan; $i++) {
                $idKebutuhan = 'idKebutuhan_' . $i;
                $harga = 'nominalKebutuhan_' . $i;
                $pajak = 'pajakKebutuhan_' . $i;
                $pph22 = 'pajakKebutuhan22_' . $i;
                $pph23 = 'pajakKebutuhan23_' . $i;
                $ppn = 'ppnkebutuhan_' . $i;
                $nominal = 'totalKebutuhan_' . $i;
                $tglbayar = 'tglbayarKebutuhan_' . $i;
                $sbmKebutuhan = 'sbmKebutuhan_' . $i;
                $akunKebutuhan = 'akunKebutuhan_' . $i;
                $statusKesesuaian = 'kesesuaian_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('kebutuhan_id', $request->$idKebutuhan)
                    ->update([
                        'uang_harian' => $request->$harga,
                        'persen_pajak' => $request->$pajak,
                        'pph22' => $request->$pph22,
                        'pph23' => $request->$pph23,
                        'ppn' => $request->$ppn,
                        'jumlah_harga' => $request->$nominal,
                        'tgl_bayar' => $request->$tglbayar,
                        'ref_sbm' => $request->$sbmKebutuhan,
                        'status' => $request->$statusKesesuaian,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
            }

            DB::table('info_perjadinlangsungs')
                ->where('id', $request->idPerjadin)
                ->update([
                    'is_acceptKeu' => 'verifikasi-2',
                    'is_acceptBend' => 'approval-2',
                    'status_pengajuan' => 'proses',
                    'status_pengajuan_detail' => 'Pelaksanaan Perjadin',
                    'admin_Bend' => auth('administrator')->user()->id,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

            $dataNotif = [
                    'id_kegiatan' => $request->idPerjadin,
                    'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
                    'to' => 0, // ID pengguna yang menerima
                    'role' => 'Keuangan', // Peran pengguna
                    'header' => 'Usulan Perjalanan Dinas - '.$request->idPerjadin, // Judul notifikasi
                    'message' => 'Usulan dengan id '.$request->idPerjadin.' telah Approval 1 Bendahara', // Isi pesan
                    'route' => 'perjadin-keuangan/detail/'.$request->idPerjadin, // Route yang dituju
                    'is_read' => 0, // Status belum dibaca
                    'versi_id' => session('versi'),
                    'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
                ];

                // Melakukan insert ke tabel notifications
                DB::table('notifications')->insert($dataNotif);

            $dataNotifUser = [
                    'id_kegiatan' => $request->idPerjadin,
                    'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
                    'to' => '0', // ID pengguna yang menerima
                    'role' => null, // Peran pengguna
                    'header' => 'Usulan Perjadin disetujui Bendahara - '.$request->idPerjadin, // Judul notifikasi
                    'message' => 'Approval-1 Bendahara telah diberikan untuk Perjalanan dinas '.$request->idPerjadin.', silakan lakukan perjalanan dan Lengkapi laporan setelah selesai', // Isi pesan
                    'route' => 'perjadin/riwayat/proses', // Route yang dituju
                    'is_read' => 0, // Status belum dibaca
                    'versi_id' => session('versi'),
                    'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
                    ];

            //     // Melakukan insert ke tabel notifications
                DB::table('notifications')->insert($dataNotifUser);


            return redirect()->route('bendahara-perjadin', ['status' => 'approval-1'])->with('success', 'Data Telah diperbaharui dan disetujui!');
        } elseif ($action === 'batal-approval-2') {
            // dd($request->all());
            DB::table('info_perjadinlangsungs')
                ->where('id', $request->idPerjadin)
                ->update([
                    'is_acceptKeu' => null,
                    'is_acceptHKT' => null,
                    'is_acceptBend' => 'approval-1',
                    'status_pengajuan' => 'proses',
                    'alasan_penolakan' => $request->alasanBatalkan,
                    'status_pengajuan_detail' => 'Approval-1-Bendahara Kembali',
                    'admin_Bend' => auth('administrator')->user()->id,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);                  

            return redirect()->route('bendahara-perjadin', ['status' => 'approval-1'])->with('success', 'Data Telah diperbarui dan dimundurkan ke Apv-1');
        } elseif ($action === 'approval-2') {
            // Panggil fungsi isiPenggunaan
            $adminOtherController = new AdminOtherController();


                // dd($request);

            $totalpesertapegawai = $request->numPegawai;
            for ($i = 0; $i < $totalpesertapegawai; $i++) {
                    $idpesertapegawai = 'idPegawai_' . $i;
                    $uangharian = 'uang_harian' . $i;
                    $uangfullday = 'uang_harian_fullday' . $i;
                    $uangfullboard = 'uang_harian_fullboard' . $i;
                    $uangrepresentasi = 'uang_representasi' . $i;
                    $pajakPeserta = 'pajak_' . $i;
                    $pph22 = 'pph22_' . $i;
                    $pph23 = 'pph23_' . $i;
                    $ppn = 'ppn_' . $i;
                    $totaluangpeserta = 'total_' . $i;
                    $tglbayar = 'tglbayar_' . $i;
                    // $refSBM = 'sbmPegawai_' . $i;
                    $akunPeserta = 'akunPegawai_' . $i;
                    $statuspersetujuan = 'statuspegawai_' . $i;
                    DB::table('keuangan_perjadinlangsungs')
                        ->where('data_perjadinlangsungs', $request->$idpesertapegawai)
                        ->whereNull('kebutuhan_id')
                        ->update([
                            'uang_harian' => $request->$uangharian,
                            'uang_harian_fullday' => $request->$uangfullday,
                            'uang_harian_fullboard' => $request->$uangfullboard,
                            'uang_representasi' => $request->$uangrepresentasi,
                            'persen_pajak' => $request->$pajakPeserta,
                            'pph22' => $request->$pph22,
                            'pph23' => $request->$pph23,
                            'ppn' => $request->$ppn,
                            'jumlah_harga' => $request->$totaluangpeserta,
                            'tgl_bayar' => $request->$tglbayar,
                            // 'ref_sbm' => $request->$refSBM,
                            'akun_x_rkakl' => $request->$akunPeserta,
                            'status' => $request->$statuspersetujuan,
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                }

                $totalnonpesertapegawai = $request->numNonPegawai;
                for ($i = 0; $i < $totalnonpesertapegawai; $i++) {
                    $idNonPeserta = 'idNonPesertaPegawai_' . $i;
                    $pajakNonPeserta = 'pajakNon_' . $i;
                    $pph22 = 'pphNon22_' . $i;
                    $pph23 = 'pphNon23_' . $i;
                    $ppn = 'ppnNon_' . $i;
                    $uanghariannon = 'uang_non_harian' . $i;
                    $uangfulldaynon = 'uang_non_harian_fullday' . $i;
                    $uangfullboardnon = 'uang_non_harian_fullboard' . $i;
                    $uangrepresentasinon = 'uang_non_harian_representasi' . $i;
                    // $nominalNonPeserta = 'nominalNon_' . $i;
                    $totaluangnonpeserta = 'totalNon_' . $i;
                    $tglbayar = 'tglbayarnon_' . $i;
                    // $refSBMnonpeserta = 'sbmNonPegawai_' . $i;
                    $akunnonpeserta = 'akunNonPegawai_' . $i;
                    $statuspembayarannonpesera = 'statusnonpegawai_' . $i;
                    DB::table('keuangan_perjadinlangsungs')
                        ->where('data_perjadinlangsungs', $request->$idNonPeserta)
                        ->whereNull('kebutuhan_id')
                        ->update([
                            'persen_pajak' => $request->$pajakNonPeserta,
                            'pph22' => $request->$pph22,
                            'pph23' => $request->$pph23,
                            'ppn' => $request->$ppn,
                            'uang_harian' => $request->$uanghariannon,
                            'uang_harian_fullday' => $request->$uangfulldaynon,
                            'uang_harian_fullboard' => $request->$uangfullboardnon,
                            'uang_representasi' => $request->$uangrepresentasinon,
                            'jumlah_harga' => $request->$totaluangnonpeserta,
                            'tgl_bayar' => $request->$tglbayar,
                            // 'ref_sbm' => $request->$refSBMnonpeserta,
                            'akun_x_rkakl' => $request->$akunnonpeserta,
                            'status' => $request->$statuspembayarannonpesera,
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                }

                $numkebutuhan = $request->numKebutuhan;
                for ($i = 0; $i < $numkebutuhan; $i++) {
                    $idKebutuhan = 'idKebutuhan_' . $i;
                    $harga = 'nominalKebutuhan_' . $i;
                    $pajak = 'pajakKebutuhan_' . $i;
                    $pph22 = 'pajakKebutuhan22_' . $i;
                    $pph23 = 'pajakKebutuhan23_' . $i;
                    $ppn = 'ppnkebutuhan_' . $i;
                    $nominal = 'totalKebutuhan_' . $i;
                    $tglbayar = 'tglbayarKebutuhan_' . $i;
                    // $sbmKebutuhan = 'sbmKebutuhan_' . $i;
                    $akunKebutuhan = 'akunKebutuhan_' . $i;
                    $statusKesesuaian = 'kesesuaian_' . $i;
                    DB::table('keuangan_perjadinlangsungs')
                    ->where('kebutuhan_id', $request->$idKebutuhan)
                    ->update([
                        'uang_harian' => $request->$harga,
                        'persen_pajak' => $request->$pajak,
                        'pph22' => $request->$pph22,
                        'pph23' => $request->$pph23,
                            'ppn' => $request->$ppn,
                            'jumlah_harga' => $request->$nominal,
                            'tgl_bayar' => $request->$tglbayar,
                            // 'ref_sbm' => $request->$sbmKebutuhan,
                            'akun_x_rkakl' => $request->$akunKebutuhan,
                            'status' => $request->$statusKesesuaian,
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                }

                $isIsiPenggunaanBerhasil = $adminOtherController->isiPenggunaan();

                if ($isIsiPenggunaanBerhasil) {
                    DB::table('info_perjadinlangsungs')
                        ->where('id', $request->idPerjadin)
                        ->update([
                                'is_acceptKeu' => 'selesai',
                                'is_acceptBend' => 'selesai',
                                'status_pengajuan' => 'selesai',
                                'status_pengajuan_detail' => 'Selesai Dibayarkan',
                                'admin_Bend' => auth('administrator')->user()->id,
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                            ]);
                        DB::table('peminjaman_kendaraan_dinas')
                            ->where('info_perjadinlangsung', $request->idPerjadin)
                            ->update([
                                'status' => 'selesai',
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                            ]);
                        $id = $request->input('idPerjadin'); // 'id' sesuai dengan parameter dalam URL
                        if (!$id) {
                            dd('Data tidak ditemukan.');
                        }



                        $dataNotifUser = [
                            'id_kegiatan' => $request->idPerjadin,
                            'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
                            'to' => '0', // ID pengguna yang menerima
                            'role' => null, // Peran pengguna
                            'header' => 'Pembayaran oleh Bendahara - '.$request->idPerjadin, // Judul notifikasi
                            'message' => 'Bendahara telah melakukan pembayaran untuk Perjalanan Dinas '.$request->idPerjadin.', silakan lakukan cetak RPD atau hubungi Bendahara jika RPD belum tersedia', // Isi pesan
                            'route' => 'perjadin/riwayat/selesai', // Route yang dituju
                            'is_read' => 0, // Status belum dibaca
                            'versi_id' => session('versi'),
                            'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
                        ];

                    //     // Melakukan insert ke tabel notifications
                        DB::table('notifications')->insert($dataNotifUser);
                        
                    return redirect()->route('bendahara-perjadin', ['status' => 'selesai'])->with('success', 'Data Telah diperbaharui dan disetujui dan perjalanan dinas selesai!');
                } else {
                    return redirect()->route('bendahara-perjadin-fasilitas', ['id' => $request->idPerjadin])
                    ->with('error', 'Terjadi kesalahan: Proses Penyimpanan Data Gagal.');
                }

                

                
        } elseif ($action === 'revisi-HKT') {
            DB::table('info_perjadinlangsungs')
                ->where('id', $request->idPerjadin)
                ->update([
                    'status_pengajuan' => 'proses',
                    'is_acceptBend' => 'revisi',
                    'is_acceptHKT' => 'revisi',
                    'status_pengajuan_detail' => 'APV1-revisi-dikembalikan ke HKT',
                    'alasan_penolakan' => $request->alasanHKT,
                    'admin_Bend' => auth('administrator')->user()->id,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

            $dataNotif = [
                    'id_kegiatan' => $request->idPerjadin,
                    'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
                    'to' => 0, // ID pengguna yang menerima
                    'role' => 'HKT', // Peran pengguna
                    'header' => 'Usulan Perjalanan Dinas - '.$request->idPerjadin, // Judul notifikasi
                    'message' => 'Usulan dengan id '.$request->idPerjadin.' diminta revisi oleh HKT, silakan cek detailnya', // Isi pesan
                    'route' => 'perjadin-HKT/detail/'.$request->idPerjadin, // Route yang dituju
                    'is_read' => 0, // Status belum dibaca
                    'versi_id' => session('versi'),
                    'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
                ];

                // Melakukan insert ke tabel notifications
                DB::table('notifications')->insert($dataNotif);

            $dataNotifUser = [
                    'id_kegiatan' => $request->idPerjadin,
                    'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
                    'to' => '0', // ID pengguna yang menerima
                    'role' => null, // Peran pengguna
                    'header' => 'Usulan Perjadin dikembalikan ke HKT - '.$request->idPerjadin, // Judul notifikasi
                    'message' => 'Usulan '.$request->idPerjadin.', dikembalikan ke HKT atas permintaan Bendahara saat Approval-1', // Isi pesan
                    'route' => 'perjadin/riwayat/proses', // Route yang dituju
                    'is_read' => 0, // Status belum dibaca
                    'versi_id' => session('versi'),
                    'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
                    ];

            //     // Melakukan insert ke tabel notifications
                DB::table('notifications')->insert($dataNotifUser);

            return redirect()->route('bendahara-perjadin', ['status' => 'ditolak'])->with('success', 'Data Telah anda tolak!');
        } elseif ($action === 'selesai-tanpa-bayar') {
            DB::table('info_perjadinlangsungs')
                ->where('id', $request->idPerjadin)
                ->update([
                    'status_pengajuan' => 'selesai',
                    'is_acceptKeu' => 'selesai',
                    'is_acceptBend' => 'selesai',
                    'status_pengajuan_detail' => 'Selesai Non Bayar',
                    'admin_Bend' => auth('administrator')->user()->id,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

                $dataNotifUser = [
                    'id_kegiatan' => $request->idPerjadin,
                    'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
                    'to' => '0', // ID pengguna yang menerima
                    'role' => null, // Peran pengguna
                    'header' => 'Usulan Perjadin DIselesaikan Bendahara - '.$request->idPerjadin, // Judul notifikasi
                    'message' => 'Usulan '.$request->idPerjadin.', diselesaikan Bendahara tanpa pembayaran saat Approval-1, silakan cek detailnya', // Isi pesan
                    'route' => 'perjadin/riwayat/selesai', // Route yang dituju
                    'is_read' => 0, // Status belum dibaca
                    'versi_id' => session('versi'),
                    'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
                    ];

            //     // Melakukan insert ke tabel notifications
                DB::table('notifications')->insert($dataNotifUser);

            return redirect()->route('bendahara-perjadin', ['status' => 'selesai'])->with('success', 'Data Telah anda selesaikan tanpa bayar!');
        } elseif ($action === 'tolak') {
            DB::table('info_perjadinlangsungs')
                ->where('id', $request->idPerjadin)
                ->update([
                    'status_pengajuan' => 'ditolak',
                    'is_acceptKeu' => 'ditolak',
                    'is_acceptBend' => 'ditolak',
                    'status_pengajuan_detail' => 'Approval-ditolak',
                    'alasan_penolakan' => $request->alasan,
                    'admin_Bend' => auth('administrator')->user()->id,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

                
            DB::table('data_perjadinlangsungs')
                ->where('info_perjadinlangsung', $request->idPerjadin)
                ->update([
                    'status_persetujuan' => 'Ditolak',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

                DB::table('peminjaman_kendaraan_dinas')
                ->where('info_perjadinlangsung', $request->idPerjadin)
                ->update([
                    'status' => 'ditolak',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

                $dataNotifUser = [
                    'id_kegiatan' => $request->idPerjadin,
                    'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
                    'to' => '0', // ID pengguna yang menerima
                    'role' => null, // Peran pengguna
                    'header' => 'Usulan Perjadin ditolak Bendahara - '.$request->idPerjadin, // Judul notifikasi
                    'message' => 'Usulan '.$request->idPerjadin.', ditolak Bendahara saat Approval-1, silakan cek detailnya', // Isi pesan
                    'route' => 'perjadin/riwayat/ditolak', // Route yang dituju
                    'is_read' => 0, // Status belum dibaca
                    'versi_id' => session('versi'),
                    'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
                    ];

            //     // Melakukan insert ke tabel notifications
                DB::table('notifications')->insert($dataNotifUser);

            return redirect()->route('bendahara-perjadin', ['status' => 'ditolak'])->with('success', 'Data Telah anda tolak!');
        } elseif ($action === 'simpan') {
            $totalpesertapegawai = $request->numPegawai;
            for ($i = 0; $i < $totalpesertapegawai; $i++) {
                $idpesertapegawai = 'idPegawai_' . $i;
                $uangharian = 'uang_harian' . $i;
                $uangfullday = 'uang_harian_fullday' . $i;
                $uangfullboard = 'uang_harian_fullboard' . $i;
                $uangrepresentasi = 'uang_representasi' . $i;
                $pajakPeserta = 'pajak_' . $i;
                $pph22 = 'pph22_' . $i;
                $pph23 = 'pph23_' . $i;
                $ppn = 'ppn_' . $i;
                $totaluangpeserta = 'total_' . $i;
                $tglbayar = 'tglbayar_' . $i;
                // $refSBM = 'sbmPegawai_' . $i;
                $akunPeserta = 'akunPegawai_' . $i;
                $statuspersetujuan = 'statuspegawai_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('data_perjadinlangsungs', $request->$idpesertapegawai)
                    ->update([
                        'uang_harian' => $request->$uangharian,
                        'uang_harian_fullday' => $request->$uangfullday,
                        'uang_harian_fullboard' => $request->$uangfullboard,
                        'uang_representasi' => $request->$uangrepresentasi,
                        'persen_pajak' => $request->$pajakPeserta,
                        'pph22' => $request->$pph22,
                        'pph23' => $request->$pph23,
                        'ppn' => $request->$ppn,
                        'jumlah_harga' => $request->$totaluangpeserta,
                        'tgl_bayar' => $request->$tglbayar,
                        // 'ref_sbm' => $request->$refSBM,
                        'status' => $request->$statuspersetujuan,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
            }

            $totalnonpesertapegawai = $request->numNonPegawai;
            for ($i = 0; $i < $totalnonpesertapegawai; $i++) {
                $idNonPeserta = 'idNonPesertaPegawai_' . $i;
                $pajakNonPeserta = 'pajakNon_' . $i;
                $pph22 = 'pphNon22_' . $i;
                $pph23 = 'pphNon23_' . $i;
                $ppn = 'ppnNon_' . $i;
                $uanghariannon = 'uang_non_harian' . $i;
                $uangfulldaynon = 'uang_non_harian_fullday' . $i;
                $uangfullboardnon = 'uang_non_harian_fullboard' . $i;
                $uangrepresentasinon = 'uang_non_harian_representasi' . $i;
                // $nominalNonPeserta = 'nominalNon_' . $i;
                $totaluangnonpeserta = 'totalNon_' . $i;
                $tglbayar = 'tglbayarnon_' . $i;
                // $refSBMnonpeserta = 'sbmNonPegawai_' . $i;
                $akunnonpeserta = 'akunNonPegawai_' . $i;
                $statuspembayarannonpesera = 'statusnonpegawai_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('data_perjadinlangsungs', $request->$idNonPeserta)
                    ->update([
                        'persen_pajak' => $request->$pajakNonPeserta,
                        'pph22' => $request->$pph22,
                        'pph23' => $request->$pph23,
                        'ppn' => $request->$ppn,
                        'uang_harian' => $request->$uanghariannon,
                        'uang_harian_fullday' => $request->$uangfulldaynon,
                        'uang_harian_fullboard' => $request->$uangfullboardnon,
                        'uang_representasi' => $request->$uangrepresentasinon,
                        'jumlah_harga' => $request->$totaluangnonpeserta,
                        'tgl_bayar' => $request->$tglbayar,
                        // 'ref_sbm' => $request->$refSBMnonpeserta,
                        'status' => $request->$statuspembayarannonpesera,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
            }

            $numkebutuhan = $request->numKebutuhan;
            for ($i = 0; $i < $numkebutuhan; $i++) {
                $idKebutuhan = 'idKebutuhan_' . $i;
                $harga = 'nominalKebutuhan_' . $i;
                $pajak = 'pajakKebutuhan_' . $i;
                $pph22 = 'pajakKebutuhan22_' . $i;
                $pph23 = 'pajakKebutuhan23_' . $i;
                $ppn = 'ppnkebutuhan_' . $i;
                $nominal = 'totalKebutuhan_' . $i;
                $tglbayar = 'tglbayarKebutuhan_' . $i;
                // $sbmKebutuhan = 'sbmKebutuhan_' . $i;
                $akunKebutuhan = 'akunKebutuhan_' . $i;
                $statusKesesuaian = 'kesesuaian_' . $i;
                DB::table('keuangan_perjadinlangsungs')
                    ->where('kebutuhan_id', $request->$idKebutuhan)
                    ->update([
                        'uang_harian' => $request->$harga,
                        'persen_pajak' => $request->$pajak,
                        'pph22' => $request->$pph22,
                        'pph23' => $request->$pph23,
                        'ppn' => $request->$ppn,
                        'jumlah_harga' => $request->$nominal,
                        'tgl_bayar' => $request->$tglbayar,
                        // 'ref_sbm' => $request->$sbmKebutuhan,
                        'status' => $request->$statusKesesuaian,
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);
            }

            return redirect()->route('bendahara-perjadin', ['status' => $request->statusPerjadin])->with('success', 'Data Telah Disimpan!');
        } elseif ($action === 'revisi-Verifikator') {
            DB::table('info_perjadinlangsungs')
                ->where('id', $request->idPerjadin)
                ->update([
                    'status_pengajuan' => 'selesai',
                    'is_acceptKeu' => 'verifikasi-2',
                    'is_acceptBend' => 'approval-2',
                    'status_pengajuan_detail' => 'Approval-revisi-verifikator',
                    'alasan_penolakan' => $request->alasanVerifikator,
                    'admin_Bend' => auth('administrator')->user()->id,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

            return redirect()->route('bendahara-perjadin', ['status' => $request->statusPerjadin])->with('success', 'Data Telah Disimpan!');
        
        }
    }

    public function storeFasilitasBendahara(Request $request)
    {
        DB::table('kebutuhans')->insert([
            'nama' => $request->uraian,
            'status' => 'Pengajuan',
            'jumlah_frekuensi' => $request->jumlah_frekuensi,
            'satuan' => $request->satuan,
            'tipe_pendanaan' => $request->tipe_pendanaan,
            'ket' => $request->keterangan,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $kebutuhan_max = Kebutuhan::max('id');
        DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'kebutuhan_id' => $kebutuhan_max,
            'status' => 'Menunggu Persetujuan Bendahara',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $id = $request->info_perjadinlangsung;
        return redirect()->route('bendahara-perjadin-fasilitas', ['id' => $id])->with('success', 'Fasilitas berhasil ditambahkan!');
    }

    public function storeFasilitasDetailBendahara(Request $request)
    {
        DB::table('kebutuhans')->insert([
            'nama' => $request->uraian,
            'status' => 'Pengajuan',
            'jumlah_frekuensi' => $request->jumlah_frekuensi,
            'satuan' => $request->satuan,
            'tipe_pendanaan' => $request->tipe_pendanaan,
            'ket' => $request->keterangan,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $kebutuhan_max = Kebutuhan::max('id');
        DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'data_perjadinlangsungs' => $request->data_perjadinlangsungs,
            'kebutuhan_id' => $kebutuhan_max,
            'status' => 'Menunggu Persetujuan Bendahara',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $id = $request->info_perjadinlangsung;
        return redirect()->route('bendahara-perjadin-fasilitas', ['id' => $id])->with('success', 'Fasilitas berhasil ditambahkan!');
    }


    public function deleteFasilitasiKeu(Request $request, $id)
    {
        DB::table('kebutuhans')->where('id', $id)->delete();
        DB::table('keuangan_perjadinlangsungs')->where('kebutuhan_id', $id)->delete();

        // Mendapatkan ID perjadin dari request JSON
        $id_perjadin = $request->input('perjadinId');

        // Mengembalikan response sukses
        return response()->json(['success' => true]);
    }

    public function storeFasilitasDetailKeuangan(Request $request){
        if($request->keterangan == null){
            $request->keterangan = "-";
        }
        DB::table('kebutuhans')->insert([
            'nama' => $request->uraian,
            'status' => 'Pengajuan',
            'jumlah_frekuensi' => $request->jumlah_frekuensi,
            'satuan' => $request->satuan,
            'tipe_pendanaan' => $request->tipe_pendanaan,
            'ket' => $request->keterangan,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $kebutuhan_max = Kebutuhan::max('id');
        DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'data_perjadinlangsungs' => $request->data_perjadinlangsungs,
            'kebutuhan_id' => $kebutuhan_max,
            'status' => 'Menunggu Persetujuan Bendahara',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $id = $request->info_perjadinlangsung;
        return redirect()->route('detail-perjadin-keuangan', ['id' => $id])->with('success', 'Fasilitas berhasil ditambahkan!');
    }

    public function storeFasilitasBMN(Request $request)
    {
        DB::table('kebutuhans')->insert([
            'nama' => $request->uraian,
            'status' => 'Pengajuan',
            'jumlah_frekuensi' => $request->jumlah_frekuensi,
            'satuan' => $request->satuan,
            'tipe_pendanaan' => $request->tipe_pendanaan,
            'ket' => $request->keterangan,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $kebutuhan_max = Kebutuhan::max('id');
        DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'kebutuhan_id' => $kebutuhan_max,
            'status' => 'Menunggu Persetujuan BMN',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);


        $id = $request->info_perjadinlangsung;
        return redirect()->route('detail-mobilitas-perjadin', ['id' => $id])->with('success', 'Fasilitas berhasil ditambahkan!');
    }
    public function storeFasilitasDetailBMN(Request $request)
    {
        DB::table('kebutuhans')->insert([
            'nama' => $request->uraian,
            'status' => 'Pengajuan',
            'jumlah_frekuensi' => $request->jumlah_frekuensi,
            'satuan' => $request->satuan,
            'tipe_pendanaan' => $request->tipe_pendanaan,
            'ket' => $request->keterangan,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $kebutuhan_max = Kebutuhan::max('id');
        DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'kebutuhan_id' => $kebutuhan_max,
            'status' => 'Menunggu Persetujuan BMN',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);


        $id = $request->info_perjadinlangsung;
        return redirect()->route('detail-mobilitas-perjadin', ['id' => $id])->with('success', 'Fasilitas berhasil ditambahkan!');
    }


    public function storeSurtug(Request $request)
    {
        // Validasi input yang diterima
        $validatedData = $request->validate([
            'perihal' => 'nullable|string',
            'paragraf1' => 'nullable|string',
            'paragraf2' => 'nullable|string',
            'paragraf3' => 'nullable|string',
        ]);

        $id = $request->input('idPerjadin'); // 'id' sesuai dengan parameter dalam URL

        // Cek apakah surat tugas sudah ada untuk perjalanan dinas ini
        $exists = DB::table('surtug_perjadinlangsungs')->where('id_perjadinlangsung', $id)->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Surat Tugas sudah ada untuk perjalanan dinas ini.');
        }

        if ($request->tipeSurtug == 'true'){
            $isTable = 1;
        } else {
            $isTable = 0;
        }

        // Simpan data ke database
        DB::table('surtug_perjadinlangsungs')->insert([
            'id_perjadinlangsung' => $request->idPerjadin,
            'perihal' => $request->perihal, // Menyimpan HTML apa adanya
            'paragraf_1' => $request->paragraf1, // Menyimpan HTML apa adanya
            'paragraf_2' => $request->paragraf2, // Menyimpan HTML apa adanya
            'paragraf_3' => $request->paragraf3, // Menyimpan HTML apa adanya
            'is_table' => $isTable, // Menyimpan HTML apa adanya
        ]);

        return redirect()->route('surtug-detail-HKT-perjadin', ['id' => $id])->with('success', 'Surat Tugas Berhasil Ditambahkan!');
    }

    public function ConvertSurtug($id)
    {
        if (!$id) {
            // Handle jika data tidak ditemukan
            dd('Data tidak ditemukan.');
        }

        $pesertaPegawais = DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->select('pegawais.id','pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'pegawais.NIP_NIK', 'jabatans.nama_jabatan')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->where(function ($query) {
                $query->where('data_perjadinlangsungs.status_pegawai', '!=', 'Supir')
                    ->orWhere(function ($query) {
                        $query->where('data_perjadinlangsungs.status_pegawai', '=', 'Supir')
                            ->whereNotExists(function ($query) {
                                $query->select(DB::raw(1))
                                    ->from('data_perjadinlangsungs as dp2')
                                    ->whereColumn('dp2.pegawai_id', 'data_perjadinlangsungs.pegawai_id')
                                    ->where('dp2.status_pegawai', '!=', 'Supir');
                            });
                    });
            })
            ->orderBy('data_perjadinlangsungs.status_pegawai', 'asc')
            ->get();


        $pesertaNonPegawais = DB::table('data_perjadinlangsungs')
            ->join('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
            ->select('non_pegawais.id','non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan','non_pegawais.NIP_NIK', 'data_perjadinlangsungs.status_pegawai')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();

        $pengemudi = DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->select('pegawais.id','pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'pegawais.NIP_NIK', 'jabatans.nama_jabatan')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->where('data_perjadinlangsungs.status_pegawai', '=', 'Supir')
            ->orderBy('data_perjadinlangsungs.status_pegawai', 'asc')
            ->get();

        $surat = DB::table('surtug_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'surtug_perjadinlangsungs.id_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('surtug_perjadinlangsungs.nomor_surat', 'surtug_perjadinlangsungs.perihal', 'surtug_perjadinlangsungs.paragraf_1', 'surtug_perjadinlangsungs.paragraf_2', 'surtug_perjadinlangsungs.paragraf_3')
            ->where('surtug_perjadinlangsungs.id_perjadinlangsung', $id)
            ->get();


        $tipeSurtug = DB::table('surtug_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'surtug_perjadinlangsungs.id_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('surtug_perjadinlangsungs.is_table AS isTable')
            ->where('surtug_perjadinlangsungs.id_perjadinlangsung', $id)
            ->first();
            
         $pegawaiKepala = DB::table('ref_penandatangans')
            ->join('pegawais', 'pegawais.id', '=', 'ref_penandatangans.pegawai_id')
            ->select('pegawais.*')
            ->where('ref_penandatangans.posisi_penandatangan', 'Kepala')
            ->where('ref_penandatangans.status_penandatangan', 'aktif')
            ->first();

        $datas = [
            'pesertaPegawais' => $pesertaPegawais,
            'pegawaiKepala' => $pegawaiKepala,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'pengemudis' => $pengemudi,
            'perjadin' => Info_perjadinlangsung::find($id),
            'surtugs' => $surat,
            'tipeSurtug' => $tipeSurtug
        ];
        // Lakukan proses pembuatan file PDF
        $pdf = PDF::loadView('admin.perjadin.HKT.surtug_detail', compact('datas'));
        $pdf->setPaper('A4', 'portrait');

        // Simpan file PDF ke penyimpanan
        $filePath = $pdf->output();
        Storage::disk('public')->put('dokumen-perjadins/surtug.pdf', $filePath);

        // Stream file PDF ke browser
        return $pdf->stream('surtug.pdf');
    }

    public function StoreSurtugPDF(Request $request)
    {
        $id = $request->input('idPerjadin'); // 'id' sesuai dengan parameter dalam URL
        if (!$id) {
            dd('Data tidak ditemukan.');
        }

        $pesertaPegawais = DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->select('pegawais.id','pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'pegawais.NIP_NIK','jabatans.nama_jabatan')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();

        $pesertaNonPegawais = DB::table('data_perjadinlangsungs')
            ->join('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
            ->select('non_pegawais.id','non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'data_perjadinlangsungs.status_pegawai')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();

        $pengemudi = DB::table('pegawais')
            ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
            ->join('peminjaman_kendaraan_dinas', 'peminjaman_kendaraan_dinas.pegawai_id', '=', 'pegawais.id')
            ->join('info_perjadinlangsungs', 'peminjaman_kendaraan_dinas.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('pegawais.id', 'pegawais.nama_lengkap', 'jabatans.nama_jabatan', 'pegawais.NIP_NIK', 'pegawais.pangkat', 'pegawais.golongan')
            ->where('jabatans.nama_jabatan', 'Pengemudi')
            ->where('peminjaman_kendaraan_dinas.info_perjadinlangsung', $id)
            ->get();

        $surat = DB::table('surtug_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'surtug_perjadinlangsungs.id_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('surtug_perjadinlangsungs.is_table as isTable','surtug_perjadinlangsungs.nomor_surat', 'surtug_perjadinlangsungs.perihal', 'surtug_perjadinlangsungs.paragraf_1', 'surtug_perjadinlangsungs.paragraf_2', 'surtug_perjadinlangsungs.paragraf_3')
            ->where('surtug_perjadinlangsungs.id_perjadinlangsung', $id)
            ->get();

        $tipeSurtug = DB::table('surtug_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'surtug_perjadinlangsungs.id_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('surtug_perjadinlangsungs.is_table AS isTable')
            ->where('surtug_perjadinlangsungs.id_perjadinlangsung', $id)
            ->first();
            
        $pegawaiKepala = DB::table('ref_penandatangans')
            ->join('pegawais', 'pegawais.id', '=', 'ref_penandatangans.pegawai_id')
            ->select('pegawais.*')
            ->where('ref_penandatangans.posisi_penandatangan', 'Kepala')
            ->where('ref_penandatangans.status_penandatangan', 'aktif')
            ->first();

        $datas = [
            'pesertaPegawais' => $pesertaPegawais,
            'pegawaiKepala' => $pegawaiKepala,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'perjadin' => Info_perjadinlangsung::find($id),
            'pengemudis' => $pengemudi,
            'surtugs' => $surat,
            'tipeSurtug' => $tipeSurtug
        ];

        // Lakukan proses pembuatan file PDF
        $pdf = PDF::loadView('admin.perjadin.HKT.surtug_detail', compact('datas'));
        $pdf->setPaper('A4', 'portrait');

        // Simpan file PDF ke penyimpanan
        $filePath = $pdf->output();
        Storage::disk('public')->put('dokumen-perjadins/surtug.pdf', $filePath);

        // Simpan data ke tabel 'dokumens'
        DB::table('dokumens')
            ->where('info_perjadinlangsung_id', $id)
            ->update([
                'surat_tugas' => 'dokumen-perjadins/surtug.pdf',
                'status_persetujuan' => 'pengajuan',
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

        return redirect()->route('HKT-perjadin', ['status' => 'pengajuan'])->with('success', 'Surat Tugas telah berhasil dibuat!');
    }

    public function UploadTTE(Request $request)
    {
        $id = $request->input('idPerjadin'); // 'id' sesuai dengan parameter dalam URL
        if (!$id) {
            dd('Data tidak ditemukan.');
        }
        $perjadin = DB::table('info_perjadinlangsungs')->where('id', $id)->first();
        if (!$perjadin) {
            dd('Data tidak ditemukan.');
        }

        DB::table('surtug_perjadinlangsungs')
            ->where('id_perjadinlangsung', $id)
            ->update([
                'nomor_surat' => $request->nomor_surtug_tte,
                'tgl_surat_dibuat' => $request->tgl_dibuat_tte,
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

            DB::table('info_perjadinlangsungs')
            ->where('id', $id)
            ->update([
                'status_pengajuan_detail' => "Verifikasi-HKT<br>(Proses TTE)",
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);


        return redirect()->route('HKT-perjadin', ['status' => 'pengajuan'])->with('success', 'Proses TTE berhasil ditandai. Silakan upload dokumen final setelah TTE selesai!');
    }

    public function ganerateLaporanHKT(Request $request)
    {
        $mulai = $request->tanggalDari;
        $sampai = $request->tanggalSampai;
        return redirect()->route('laporan-HKT', ['mulai' => $mulai, 'sampai' => $sampai])->with('success', 'Data Telah diset!');
    }

    public function getAllDataHKT($mulai, $sampai)
{
    // Ambil semua data dari tabel
    $data = DB::table('info_perjadinlangsungs')
     ->select(
         'info_perjadinlangsungs.id AS id_kegiatan',
         'info_perjadinlangsungs.nama_kegiatan',
         'info_perjadinlangsungs.tgl_keberangkatan',
         'info_perjadinlangsungs.tgl_mulai',
         'info_perjadinlangsungs.tgl_selesai',
         'info_perjadinlangsungs.no_undangan',
         'info_perjadinlangsungs.pemberi_undangan',
         'info_perjadinlangsungs.alamat',
         'info_perjadinlangsungs.kabupaten_kota',
         'info_perjadinlangsungs.provinsi',
         'info_perjadinlangsungs.id_pengaju',
         'surtug_perjadinlangsungs.nomor_surat AS no_surtug',
         'surtug_perjadinlangsungs.tgl_surat_dibuat AS tgl_surtug',
         'info_perjadinlangsungs.is_acceptHKT',
         'info_perjadinlangsungs.status_pengajuan_detail',
         DB::raw('COALESCE(CONCAT("(Admin) ", administrators.username), pegawais.nama_lengkap, non_pegawais.nama_lengkap, "Tidak ditemukan") as nama_pengaju'),
         // Subquery to get the list of participants (nama_peserta)
         DB::raw("(SELECT GROUP_CONCAT(COALESCE(pegawais.nama_lengkap, non_pegawais.nama_lengkap) SEPARATOR '\n')
                   FROM data_perjadinlangsungs
                   LEFT JOIN pegawais ON data_perjadinlangsungs.pegawai_id = pegawais.id
                   LEFT JOIN non_pegawais ON data_perjadinlangsungs.non_pegawai_id = non_pegawais.id
                   WHERE data_perjadinlangsungs.info_perjadinlangsung = info_perjadinlangsungs.id) AS nama_peserta")
     )
     ->leftJoin('surtug_perjadinlangsungs', 'surtug_perjadinlangsungs.id_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
     ->leftJoin('administrators', 'info_perjadinlangsungs.id_pengaju', '=', 'administrators.id')
     ->leftJoin('pegawais', 'info_perjadinlangsungs.id_pengaju', '=', 'pegawais.id')
     ->leftJoin('non_pegawais', 'info_perjadinlangsungs.id_pengaju', '=', 'non_pegawais.id')
     ->whereBetween('info_perjadinlangsungs.tgl_mulai', [$mulai, $sampai])
     ->whereNotNull('info_perjadinlangsungs.is_acceptHKT')
     ->where('info_perjadinlangsungs.is_acceptHKT', '<>', 'ditolak')
     ->get();

    return response()->json($data);
}

    public function laporanHKT($mulai, $sampai)
{

     // Mengambil data menggunakan Query Builder
     $data = DB::table('info_perjadinlangsungs')
     ->select(
         'info_perjadinlangsungs.id AS id_kegiatan',
         'info_perjadinlangsungs.nama_kegiatan',
         'info_perjadinlangsungs.tgl_keberangkatan',
         'info_perjadinlangsungs.tgl_mulai',
         'info_perjadinlangsungs.tgl_selesai',
         'info_perjadinlangsungs.no_undangan',
         'info_perjadinlangsungs.pemberi_undangan',
         'info_perjadinlangsungs.alamat',
         'info_perjadinlangsungs.kabupaten_kota',
         'info_perjadinlangsungs.provinsi',
         'info_perjadinlangsungs.id_pengaju',
         'surtug_perjadinlangsungs.nomor_surat AS no_surtug',
         'surtug_perjadinlangsungs.tgl_surat_dibuat AS tgl_surtug',
         'info_perjadinlangsungs.is_acceptHKT',
         'info_perjadinlangsungs.status_pengajuan_detail',
         DB::raw('COALESCE(CONCAT("(Admin) ", administrators.username), pegawais.nama_lengkap, non_pegawais.nama_lengkap, "Tidak ditemukan") as nama_pengaju'),
         // Subquery to get the list of participants (nama_peserta)
         DB::raw("(SELECT GROUP_CONCAT(COALESCE(pegawais.nama_lengkap, non_pegawais.nama_lengkap) SEPARATOR '\n')
                   FROM data_perjadinlangsungs
                   LEFT JOIN pegawais ON data_perjadinlangsungs.pegawai_id = pegawais.id
                   LEFT JOIN non_pegawais ON data_perjadinlangsungs.non_pegawai_id = non_pegawais.id
                   WHERE data_perjadinlangsungs.info_perjadinlangsung = info_perjadinlangsungs.id) AS nama_peserta")
     )
     ->leftJoin('surtug_perjadinlangsungs', 'surtug_perjadinlangsungs.id_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
     ->leftJoin('administrators', 'info_perjadinlangsungs.id_pengaju', '=', 'administrators.id')
     ->leftJoin('pegawais', 'info_perjadinlangsungs.id_pengaju', '=', 'pegawais.id')
     ->leftJoin('non_pegawais', 'info_perjadinlangsungs.id_pengaju', '=', 'non_pegawais.id')
     ->whereBetween('info_perjadinlangsungs.tgl_mulai', [$mulai, $sampai])
     ->whereNotNull('info_perjadinlangsungs.is_acceptHKT')
     ->where('info_perjadinlangsungs.is_acceptHKT', '<>', 'ditolak')
     ->get();

    return view('admin.perjadin.HKT.laporanHKT', compact('data', 'mulai', 'sampai'));
}

    public function ganerateLaporanBMN(Request $request)
    {
        $mulai = $request->tanggalDari;
        $sampai = $request->tanggalSampai;
        return redirect()->route('laporan-BMN', ['mulai' => $mulai, 'sampai' => $sampai])->with('success', 'Data Telah diset!');
    }

    public function ganerateLaporanBMNv2(Request $request)
    {
        $mulai = $request->tanggalDari;
        $sampai = $request->tanggalSampai;
        return redirect()->route('laporan-BMNv2', ['mulai' => $mulai, 'sampai' => $sampai])->with('success', 'Data Telah diset!');
    }

    public function laporanBMNv2($mulai, $sampai)
{
    // Mengambil data menggunakan Query Builder
    $data = DB::select("
        WITH RECURSIVE DateRange AS (
            SELECT DATE(?) AS tanggal
            UNION ALL
            SELECT DATE_ADD(tanggal, INTERVAL 1 DAY)
            FROM DateRange
            WHERE DATE_ADD(tanggal, INTERVAL 1 DAY) <= DATE(?)
        )
        SELECT
            CASE
                WHEN pkd.info_perjadinlangsung IS NOT NULL THEN pkd.info_perjadinlangsung
                ELSE (
                    SELECT mp.data_perjadinkegiatan
                    FROM mobilitas_perjadinkegiatans mp
                    WHERE mp.id = pkd.mobilitas_perjadinkegiatan
                )
            END AS `id_kegiatan`,

            CASE
                WHEN pkd.info_perjadinlangsung IS NOT NULL THEN 'Perjadin Langsung'
                ELSE 'Perjadin Kegiatan'
            END AS `tipe_kegiatan`,

            CASE
                WHEN pkd.info_perjadinlangsung IS NOT NULL THEN COALESCE(CONCAT('(Admin) ', a.username), p.nama_lengkap, np.nama_lengkap, 'Tidak ditemukan')
                ELSE COALESCE(CONCAT('(Admin) ', a.username), p.nama_lengkap, np.nama_lengkap, 'Tidak ditemukan')
            END AS `nama_pengaju`,

            CASE
                WHEN pkd.info_perjadinlangsung IS NOT NULL THEN ip.nama_kegiatan
                ELSE dpk.nama_kegiatan
            END AS `nama_kegiatan`,

            CASE
                WHEN pkd.info_perjadinlangsung IS NOT NULL THEN
                    (SELECT GROUP_CONCAT(COALESCE(p2.nama_lengkap, np2.nama_lengkap) SEPARATOR '\n')
                    FROM data_perjadinlangsungs dpl
                    LEFT JOIN pegawais p2 ON dpl.pegawai_id = p2.id
                    LEFT JOIN non_pegawais np2 ON dpl.non_pegawai_id = np2.id
                    WHERE dpl.info_perjadinlangsung = ip.id)
                ELSE
                    (SELECT GROUP_CONCAT(COALESCE(p3.nama_lengkap, np3.nama_lengkap) SEPARATOR '\n')
                    FROM perangkat_acaras pa
                    LEFT JOIN pegawais p3 ON pa.pegawai_id = p3.id
                    LEFT JOIN non_pegawais np3 ON pa.non_pegawai_id = np3.id
                    WHERE pa.data_perjadin_kegiatan = mp.data_perjadinkegiatan)
            END AS `nama_peserta`,

            dr.tanggal AS `tgl_keberangkatan`,
            dr.tanggal AS `tgl_selesai`,

            CASE
                WHEN pkd.info_perjadinlangsung IS NOT NULL THEN ip.kabupaten_kota
                ELSE dpk.kab_kota
            END AS `kabupaten_kota`,

            CASE
                WHEN pkd.info_perjadinlangsung IS NOT NULL THEN ip.provinsi
                ELSE dpk.provinsi
            END AS `provinsi`,

            k.merek AS `merek`,
            k.no_polisi AS `no_polisi`,
            ps.nama_lengkap AS `pengemudi`

        FROM
            DateRange dr
        LEFT JOIN
            peminjaman_kendaraan_dinas AS pkd ON dr.tanggal BETWEEN pkd.tgl_keberangkatan AND pkd.tgl_selesai

        LEFT JOIN
            info_perjadinlangsungs AS ip ON pkd.info_perjadinlangsung = ip.id
        LEFT JOIN
            mobilitas_perjadinkegiatans AS mp ON dr.tanggal BETWEEN mp.tgl_mulai AND mp.tgl_selesai

        LEFT JOIN
            data_perjadinkegiatans AS dpk ON mp.data_perjadinkegiatan = dpk.id
        LEFT JOIN
            pegawais AS p ON ip.id_pengaju = p.id OR dpk.id_pengaju = p.id
        LEFT JOIN
            non_pegawais AS np ON ip.id_pengaju = np.id OR dpk.id_pengaju = dpk.id
        LEFT JOIN
            administrators AS a ON ip.id_pengaju = a.id OR dpk.id_pengaju = a.id
        LEFT JOIN
            kendaraans AS k ON pkd.kendaraan = k.id
        LEFT JOIN
            pegawais AS ps ON pkd.pegawai_id = ps.id

        WHERE
            (pkd.tgl_keberangkatan BETWEEN ? AND ?
            OR mp.tgl_mulai BETWEEN ? AND ?)

        ORDER BY
            dr.tanggal ASC
        ", [$mulai, $sampai, $mulai, $sampai, $mulai, $sampai]);

        return view('admin.perjadin.mobilitas.laporanBMNv2', compact('data', 'mulai', 'sampai'));
    }

    public function getAllDataBMNv2($mulai, $sampai)
    {
    // Ambil semua data dari tabel
    $data = DB::select("
        WITH RECURSIVE DateRange AS (
            SELECT DATE(?) AS tanggal
            UNION ALL
            SELECT DATE_ADD(tanggal, INTERVAL 1 DAY)
            FROM DateRange
            WHERE DATE_ADD(tanggal, INTERVAL 1 DAY) <= DATE(?)
        )
        SELECT
            CASE
                WHEN pkd.info_perjadinlangsung IS NOT NULL THEN pkd.info_perjadinlangsung
                ELSE (
                    SELECT mp.data_perjadinkegiatan
                    FROM mobilitas_perjadinkegiatans mp
                    WHERE mp.id = pkd.mobilitas_perjadinkegiatan
                )
            END AS `id_kegiatan`,

            CASE
                WHEN pkd.info_perjadinlangsung IS NOT NULL THEN 'Perjadin Langsung'
                ELSE 'Perjadin Kegiatan'
            END AS `tipe_kegiatan`,

            CASE
                WHEN pkd.info_perjadinlangsung IS NOT NULL THEN COALESCE(CONCAT('(Admin) ', a.username), p.nama_lengkap, np.nama_lengkap, 'Tidak ditemukan')
                ELSE COALESCE(CONCAT('(Admin) ', a.username), p.nama_lengkap, np.nama_lengkap, 'Tidak ditemukan')
            END AS `nama_pengaju`,

            CASE
                WHEN pkd.info_perjadinlangsung IS NOT NULL THEN ip.nama_kegiatan
                ELSE dpk.nama_kegiatan
            END AS `nama_kegiatan`,

            CASE
                WHEN pkd.info_perjadinlangsung IS NOT NULL THEN
                    (SELECT GROUP_CONCAT(COALESCE(p2.nama_lengkap, np2.nama_lengkap) SEPARATOR '\n')
                    FROM data_perjadinlangsungs dpl
                    LEFT JOIN pegawais p2 ON dpl.pegawai_id = p2.id
                    LEFT JOIN non_pegawais np2 ON dpl.non_pegawai_id = np2.id
                    WHERE dpl.info_perjadinlangsung = ip.id)
                ELSE
                    (SELECT GROUP_CONCAT(COALESCE(p3.nama_lengkap, np3.nama_lengkap) SEPARATOR '\n')
                    FROM perangkat_acaras pa
                    LEFT JOIN pegawais p3 ON pa.pegawai_id = p3.id
                    LEFT JOIN non_pegawais np3 ON pa.non_pegawai_id = np3.id
                    WHERE pa.data_perjadin_kegiatan = mp.data_perjadinkegiatan)
            END AS `nama_peserta`,

            dr.tanggal AS `tgl_keberangkatan`,
            dr.tanggal AS `tgl_selesai`,

            CASE
                WHEN pkd.info_perjadinlangsung IS NOT NULL THEN ip.kabupaten_kota
                ELSE dpk.kab_kota
            END AS `kabupaten_kota`,

            CASE
                WHEN pkd.info_perjadinlangsung IS NOT NULL THEN ip.provinsi
                ELSE dpk.provinsi
            END AS `provinsi`,

            k.merek AS `merek`,
            k.no_polisi AS `no_polisi`,
            ps.nama_lengkap AS `pengemudi`

        FROM
            DateRange dr
        LEFT JOIN
            peminjaman_kendaraan_dinas AS pkd ON dr.tanggal BETWEEN pkd.tgl_keberangkatan AND pkd.tgl_selesai

        LEFT JOIN
            info_perjadinlangsungs AS ip ON pkd.info_perjadinlangsung = ip.id
        LEFT JOIN
            mobilitas_perjadinkegiatans AS mp ON pkd.mobilitas_perjadinkegiatan = mp.id
        LEFT JOIN
            data_perjadinkegiatans AS dpk ON mp.data_perjadinkegiatan = dpk.id
        LEFT JOIN
            pegawais AS p ON ip.id_pengaju = p.id OR dpk.id_pengaju = p.id
        LEFT JOIN
            non_pegawais AS np ON ip.id_pengaju = np.id OR dpk.id_pengaju = np.id
        LEFT JOIN
            administrators AS a ON ip.id_pengaju = a.id OR dpk.id_pengaju = a.id
        LEFT JOIN
            kendaraans AS k ON pkd.kendaraan = k.id
        LEFT JOIN
            pegawais AS ps ON pkd.pegawai_id = ps.id

        WHERE
            (pkd.tgl_keberangkatan BETWEEN ? AND ?
            OR mp.tgl_mulai BETWEEN ? AND ?)

        ORDER BY
            dr.tanggal ASC;
        ", [$mulai, $sampai, $mulai, $sampai, $mulai, $sampai]);

        return response()->json($data);
    }

    public function laporanBMN($mulai, $sampai)
    {

        // Mengambil data menggunakan Query Builder
        $data = DB::table('peminjaman_kendaraan_dinas AS pkd')
        ->select(
            DB::raw("
                CASE
                    WHEN pkd.info_perjadinlangsung IS NOT NULL THEN pkd.info_perjadinlangsung
                    ELSE (
                        SELECT mp.data_perjadinkegiatan
                        FROM mobilitas_perjadinkegiatans mp
                        WHERE mp.id = pkd.mobilitas_perjadinkegiatan
                    )
                END AS id_kegiatan,

                CASE
                    WHEN pkd.info_perjadinlangsung IS NOT NULL THEN 'Perjadin Langsung'
                    ELSE 'Perjadin Kegiatan'
                END AS tipe_kegiatan,

                CASE
                    WHEN pkd.info_perjadinlangsung IS NOT NULL THEN COALESCE(CONCAT('(Admin) ', a.username), p.nama_lengkap, np.nama_lengkap, 'Tidak ditemukan')
                    ELSE COALESCE(CONCAT('(Admin) ', a.username), p.nama_lengkap, np.nama_lengkap, 'Tidak ditemukan')
                END AS nama_pengaju,

                CASE
                    WHEN pkd.info_perjadinlangsung IS NOT NULL THEN ip.nama_kegiatan
                    ELSE dpk.nama_kegiatan
                END AS nama_kegiatan,

                CASE
                    WHEN pkd.info_perjadinlangsung IS NOT NULL THEN
                        (SELECT GROUP_CONCAT(COALESCE(p2.nama_lengkap, np2.nama_lengkap) SEPARATOR '\n')
                         FROM data_perjadinlangsungs dpl
                         LEFT JOIN pegawais p2 ON dpl.pegawai_id = p2.id
                         LEFT JOIN non_pegawais np2 ON dpl.non_pegawai_id = np2.id
                         WHERE dpl.info_perjadinlangsung = ip.id)
                    ELSE
                        (SELECT GROUP_CONCAT(COALESCE(p3.nama_lengkap, np3.nama_lengkap) SEPARATOR '\n')
                         FROM perangkat_acaras pa
                         LEFT JOIN pegawais p3 ON pa.pegawai_id = p3.id
                         LEFT JOIN non_pegawais np3 ON pa.non_pegawai_id = np3.id
                         WHERE pa.data_perjadin_kegiatan = mp.data_perjadinkegiatan)
                END AS nama_peserta,

                CASE
                    WHEN pkd.info_perjadinlangsung IS NOT NULL THEN pkd.tgl_keberangkatan
                    ELSE mp.tgl_mulai
                END AS tgl_keberangkatan,

                CASE
                    WHEN pkd.info_perjadinlangsung IS NOT NULL THEN pkd.tgl_selesai
                    ELSE mp.tgl_selesai
                END AS tgl_selesai,

                CASE
                    WHEN pkd.info_perjadinlangsung IS NOT NULL THEN ip.kabupaten_kota
                    ELSE dpk.kab_kota
                END AS kabupaten_kota,

                CASE
                    WHEN pkd.info_perjadinlangsung IS NOT NULL THEN ip.provinsi
                    ELSE dpk.provinsi
                END AS provinsi,

                k.merek AS merek,
                k.no_polisi AS no_polisi,
                ps.nama_lengkap AS pengemudi
            ")
        )
        ->leftJoin('info_perjadinlangsungs AS ip', 'pkd.info_perjadinlangsung', '=', 'ip.id')
        ->leftJoin('mobilitas_perjadinkegiatans AS mp', 'pkd.mobilitas_perjadinkegiatan', '=', 'mp.id')
        ->leftJoin('data_perjadinkegiatans AS dpk', 'mp.data_perjadinkegiatan', '=', 'dpk.id')
        ->leftJoin('pegawais AS p', function($join) {
            $join->on('ip.id_pengaju', '=', 'p.id')
                 ->orOn('dpk.id_pengaju', '=', 'p.id');
        })
        ->leftJoin('non_pegawais AS np', function($join) {
            $join->on('ip.id_pengaju', '=', 'np.id')
                 ->orOn('dpk.id_pengaju', '=', 'np.id');
        })
        ->leftJoin('administrators AS a', function($join) {
            $join->on('ip.id_pengaju', '=', 'a.id')
                 ->orOn('dpk.id_pengaju', '=', 'a.id');
        })
        ->leftJoin('kendaraans AS k', 'pkd.kendaraan', '=', 'k.id')
        ->leftJoin('pegawais AS ps', 'pkd.pegawai_id', '=', 'ps.id')
        ->where(function($query) use ($mulai, $sampai) {
            $query->whereBetween('pkd.tgl_keberangkatan', [$mulai, $sampai])
                  ->orWhereBetween('mp.tgl_mulai', [$mulai, $sampai]);
        })
        ->orderBy('tipe_kegiatan', 'ASC')
        ->get();

        return view('admin.perjadin.mobilitas.laporanBMN', compact('data', 'mulai', 'sampai'));
    }

    public function getAllDataBMN($mulai, $sampai)
{
    // Ambil semua data dari tabel
    $data = DB::table('peminjaman_kendaraan_dinas AS pkd')
        ->select(
            DB::raw("
                CASE
                    WHEN pkd.info_perjadinlangsung IS NOT NULL THEN pkd.info_perjadinlangsung
                    ELSE (
                        SELECT mp.data_perjadinkegiatan
                        FROM mobilitas_perjadinkegiatans mp
                        WHERE mp.id = pkd.mobilitas_perjadinkegiatan
                    )
                END AS id_kegiatan,

                CASE
                    WHEN pkd.info_perjadinlangsung IS NOT NULL THEN 'Perjadin Langsung'
                    ELSE 'Perjadin Kegiatan'
                END AS tipe_kegiatan,

                CASE
                    WHEN pkd.info_perjadinlangsung IS NOT NULL THEN COALESCE(CONCAT('(Admin) ', a.username), p.nama_lengkap, np.nama_lengkap, 'Tidak ditemukan')
                    ELSE COALESCE(CONCAT('(Admin) ', a.username), p.nama_lengkap, np.nama_lengkap, 'Tidak ditemukan')
                END AS nama_pengaju,

                CASE
                    WHEN pkd.info_perjadinlangsung IS NOT NULL THEN ip.nama_kegiatan
                    ELSE dpk.nama_kegiatan
                END AS nama_kegiatan,

                CASE
                    WHEN pkd.info_perjadinlangsung IS NOT NULL THEN
                        (SELECT GROUP_CONCAT(COALESCE(p2.nama_lengkap, np2.nama_lengkap) SEPARATOR '\n')
                        FROM data_perjadinlangsungs dpl
                        LEFT JOIN pegawais p2 ON dpl.pegawai_id = p2.id
                        LEFT JOIN non_pegawais np2 ON dpl.non_pegawai_id = np2.id
                        WHERE dpl.info_perjadinlangsung = ip.id)
                    ELSE
                        (SELECT GROUP_CONCAT(COALESCE(p3.nama_lengkap, np3.nama_lengkap) SEPARATOR '\n')
                        FROM perangkat_acaras pa
                        LEFT JOIN pegawais p3 ON pa.pegawai_id = p3.id
                        LEFT JOIN non_pegawais np3 ON pa.non_pegawai_id = np3.id
                        WHERE pa.data_perjadin_kegiatan = mp.data_perjadinkegiatan)
                END AS nama_peserta,

                CASE
                    WHEN pkd.info_perjadinlangsung IS NOT NULL THEN pkd.tgl_keberangkatan
                    ELSE mp.tgl_mulai
                END AS tgl_keberangkatan,

                CASE
                    WHEN pkd.info_perjadinlangsung IS NOT NULL THEN pkd.tgl_selesai
                    ELSE mp.tgl_selesai
                END AS tgl_selesai,

                CASE
                    WHEN pkd.info_perjadinlangsung IS NOT NULL THEN ip.kabupaten_kota
                    ELSE dpk.kab_kota
                END AS kabupaten_kota,

                CASE
                    WHEN pkd.info_perjadinlangsung IS NOT NULL THEN ip.provinsi
                    ELSE dpk.provinsi
                END AS provinsi,

                k.merek AS merek,
                k.no_polisi AS no_polisi,
                ps.nama_lengkap AS pengemudi
            ")
        )
        ->leftJoin('info_perjadinlangsungs AS ip', 'pkd.info_perjadinlangsung', '=', 'ip.id')
        ->leftJoin('mobilitas_perjadinkegiatans AS mp', 'pkd.mobilitas_perjadinkegiatan', '=', 'mp.id')
        ->leftJoin('data_perjadinkegiatans AS dpk', 'mp.data_perjadinkegiatan', '=', 'dpk.id')
        ->leftJoin('pegawais AS p', function($join) {
            $join->on('ip.id_pengaju', '=', 'p.id')
                ->orOn('dpk.id_pengaju', '=', 'p.id');
        })
        ->leftJoin('non_pegawais AS np', function($join) {
            $join->on('ip.id_pengaju', '=', 'np.id')
                ->orOn('dpk.id_pengaju', '=', 'np.id');
        })
        ->leftJoin('administrators AS a', function($join) {
            $join->on('ip.id_pengaju', '=', 'a.id')
                ->orOn('dpk.id_pengaju', '=', 'a.id');
        })
        ->leftJoin('kendaraans AS k', 'pkd.kendaraan', '=', 'k.id')
        ->leftJoin('pegawais AS ps', 'pkd.pegawai_id', '=', 'ps.id')
        ->where(function($query) use ($mulai, $sampai) {
            $query->whereBetween('pkd.tgl_keberangkatan', [$mulai, $sampai])
                ->orWhereBetween('mp.tgl_mulai', [$mulai, $sampai]);
        })
        ->orderBy('tipe_kegiatan', 'ASC')
        ->get();

    return response()->json($data);
}

    public function UploadSurtug(Request $request)
    {



        $validationData = $request->validate([
            'surat_tugas' => 'required|mimes:pdf|file|max:2048',
        ]);

        if (empty($validationData)) {
            dd('Gagal Upload');
        }

        $id = $request->input('idPerjadin'); // 'id' sesuai dengan parameter dalam URL
        if (!$id) {
            dd('Data tidak ditemukan.');
        }
        $perjadin = DB::table('info_perjadinlangsungs')->where('id', $id)->first();
        if (!$perjadin) {
            dd('Data tidak ditemukan.');
        }
        $pengusul = DB::table('pegawais')
            ->join('info_perjadinlangsungs', 'pegawais.id', '=', 'info_perjadinlangsungs.id_pengaju')
            ->select('info_perjadinlangsungs.id_pengaju', 'pegawais.nama_lengkap')
            ->where('info_perjadinlangsungs.id',$id)
            ->first();

        DB::table('dokumens')
            ->where('info_perjadinlangsung_id', $id)
            ->update([
                'surat_tugas' => $validationData['surat_tugas'] = $request->file('surat_tugas')->store('dokumen-perjadins', 'public'),
                'status_persetujuan' => 'pengajuan',
                'tgl_upload_surtug' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        DB::table('info_perjadinlangsungs')
            ->where('id', $id)
            ->whereNull('is_acceptKeu')
            ->update([
                'is_acceptBend' => 'approval-1',
                'status_pengajuan_detail' => 'Approval-1-Bendahara',
                'kode_surat_tugas' => $request->nomor_surtug,
                'is_acceptHKT' => 'selesai',
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        DB::table('surtug_perjadinlangsungs')
            ->where('id_perjadinlangsung', $id)
            ->update([
                'nomor_surat' => $request->nomor_surtug,
                'tgl_surat_dibuat' => $request->tgl_dibuat,
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

        $dataNotif = [
            'id_kegiatan' => $id,
            'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
            'to' => 0, // ID pengguna yang menerima
            'role' => 'Bendahara', // Peran pengguna
            'header' => 'Usulan Perjalanan Dinas - '.$id, // Judul notifikasi
            'message' => 'Usulan oleh '.$id.' telah diverifikasi HKT dan Surtug sudah tersedia', // Isi pesan
            'route' => 'perjadin-bendahara/detail/'.$id, // Route yang dituju
            'is_read' => 0, // Status belum dibaca
            'versi_id' => session('versi'),
            'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
        ];

        // Melakukan insert ke tabel notifications
        DB::table('notifications')->insert($dataNotif);

    $dataNotifUser = [
            'id_kegiatan' => $id,
            'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
            'to' => '0', // ID pengguna yang menerima
            'role' => null, // Peran pengguna
            'header' => 'Usulan Perjadin Disetujui HKT - '.$id, // Judul notifikasi
            'message' => 'Surat Tugas untuk Perjalanan Dinas '.$id.' telah terbit, silakan menunggu Approval-1 Bendahara', // Isi pesan
            'route' => 'perjadin/riwayat/proses', // Route yang dituju
            'versi_id' => session('versi'),
            'is_read' => 0, // Status belum dibaca
            'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
            ];

    //     // Melakukan insert ke tabel notifications
        DB::table('notifications')->insert($dataNotifUser);


        return redirect()->route('HKT-perjadin', ['status' => 'pengajuan'])->with('success', 'Surat Tugas telah berhasil di Upload!');
    }

    public function UpdateSurtug(Request $request)
    {


        $validationData = $request->validate([
            'surat_tugas_update' => 'required|mimes:pdf|file|max:2048',
        ]);

        if (empty($validationData)) {
            dd('Gagal Upload');
        }

        $id = $request->input('idPerjadinUpdate'); // 'id' sesuai dengan parameter dalam URL
        if (!$id) {
            dd('Data tidak ditemukan.');
        }
        $perjadin = DB::table('info_perjadinlangsungs')->where('id', $id)->first();
        if (!$perjadin) {
            dd('Data tidak ditemukan.');
        }
        $pengusul = DB::table('pegawais')
            ->join('info_perjadinlangsungs', 'pegawais.id', '=', 'info_perjadinlangsungs.id_pengaju')
            ->select('info_perjadinlangsungs.id_pengaju', 'pegawais.nama_lengkap')
            ->where('info_perjadinlangsungs.id',$id)
            ->first();

        DB::table('dokumens')
            ->where('info_perjadinlangsung_id', $id)
            ->update([
                'surat_tugas' => $validationData['surat_tugas_update'] = $request->file('surat_tugas_update')->store('dokumen-perjadins', 'public'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        DB::table('info_perjadinlangsungs')
            ->where('id', $id)
            ->update([
                'kode_surat_tugas' => $request->nomor_surtug_update,
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        DB::table('surtug_perjadinlangsungs')
            ->where('id_perjadinlangsung', $id)
            ->update([
                'nomor_surat' => $request->nomor_surtug_update,
                'tgl_surat_dibuat' => $request->tgl_dibuat_update,
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

            $dataNotifUser = [
                'id_kegiatan' => $id,
                'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
                'to' => '0', // ID pengguna yang menerima
                'role' => null, // Peran pengguna
                'header' => 'Surat Tugas diperbarui HKT - '.$id, // Judul notifikasi
                'message' => 'Surat Tugas untuk Perjalanan Dinas '.$id.' diperbarui HKT, silakan lihat untuk Surtug yang telah diperbarui', // Isi pesan
                'route' => 'perjadin/riwayat/proses', // Route yang dituju
                'is_read' => 0, // Status belum dibaca
                'versi_id' => session('versi'),
                'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
                ];

        //     // Melakukan insert ke tabel notifications
            DB::table('notifications')->insert($dataNotifUser);

        return redirect()->route('HKT-perjadin', ['status' => 'selesai'])->with('success', 'Surat Tugas telah berhasil di Update!');
    }


    // ini function untuk edit saat di button edit di index.blade

    public function detail_surtug_perjadin_HKT_edit($id)
    {
        $surat = DB::table('surtug_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'surtug_perjadinlangsungs.id_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('surtug_perjadinlangsungs.is_table as isTable','surtug_perjadinlangsungs.nomor_surat', 'surtug_perjadinlangsungs.perihal', 'surtug_perjadinlangsungs.paragraf_1', 'surtug_perjadinlangsungs.paragraf_2', 'surtug_perjadinlangsungs.paragraf_3')
            ->where('surtug_perjadinlangsungs.id_perjadinlangsung', $id)
            ->get();

        return view('admin.perjadin.HKT.edit_surtug', [
            'title' => 'Edit Surat Tugas',
            'perjadin' => Info_perjadinlangsung::find($id),
            'surtugs' => $surat,
        ]);
    }


    public function EditSurtug(Request $request)
    {
        // Validasi input yang diterima
        $validatedData = $request->validate([
            'perihal' => 'nullable|string',
            'paragraf1' => 'nullable|string',
            'paragraf2' => 'nullable|string',
            'paragraf3' => 'nullable|string',
        ]);

        $id = $request->input('idPerjadin'); // 'id' sesuai dengan parameter dalam URL
        if (!$id) {
            dd('Data tidak ditemukan.');
        }

        if ($request->tipeSurtug == 'true'){
            $isTable = 1;
        } else {
            $isTable = 0;
        }

        DB::table('surtug_perjadinlangsungs')
            ->where('id_perjadinlangsung', $id)
            ->update([
                'perihal' => $request->perihal,
                'paragraf_1' => $request->paragraf1,
                'paragraf_2' => $request->paragraf2,
                'paragraf_3' => $request->paragraf3,
                'is_table' => $isTable,
            ]);

        return redirect()->route('surtug-detail-HKT-perjadin', ['id' => $id])->with('success', 'Surat Tugas Berhasil Diubah!');
    }

    public function TolakPerjadin(Request $request)
    {
        $pengusul = DB::table('pegawais')
            ->join('info_perjadinlangsungs', 'pegawais.id', '=', 'info_perjadinlangsungs.id_pengaju')
            ->select('info_perjadinlangsungs.id_pengaju', 'pegawais.nama_lengkap')
            ->where('info_perjadinlangsungs.id',$request->idPerjadin)
            ->first();

        DB::table('info_perjadinlangsungs')
            ->where('id', $request->idPerjadin)
            ->update([
                'status_pengajuan' => 'ditolak',
                'status_pengajuan_detail' => 'Verifikasi-HKT-ditolak',
                'is_acceptHKT' => 'ditolak',
                'alasan_penolakan' =>  $request->alasan,
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

            DB::table('peminjaman_kendaraan_dinas')
            ->where('info_perjadinlangsung', $request->idPerjadin)
            ->update([
                'status' => 'ditolak',
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

            DB::table('data_perjadinlangsungs')
            ->where('info_perjadinlangsung', $request->idPerjadin)
            ->where('status_persetujuan', '!=', 'Ditolak')
            ->update([
                'status_persetujuan' => 'Ditolak',
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

            $dataNotifUser = [
                'id_kegiatan' => $request->idPerjadin,
                'from' => auth('administrator')->user()->id, // ID pengguna yang mengirim
                'to' => '0', // ID pengguna yang menerima
                'role' => null, // Peran pengguna
                'header' => 'Perjalan Dinas ditolak HKT - '.$request->idPerjadin, // Judul notifikasi
                'message' => 'Perjalanan dinas '.$request->idPerjadin.' yang diajukan telah ditolak HKT, silakan lihat detailnya', // Isi pesan
                'route' => 'perjadin/riwayat/ditolak', // Route yang dituju
                'is_read' => 0, // Status belum dibaca
                'versi_id' => session('versi'),
                'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
                ];

        //     // Melakukan insert ke tabel notifications
            DB::table('notifications')->insert($dataNotifUser);

        return redirect()->route('HKT-perjadin', ['status' => 'pengajuan'])->with('success', 'Pengajuan Telah Berhasil Ditolak!');
    }

    public function CetakRPD($id)
    {
        if (!$id) {
            // Handle jika data tidak ditemukan
            dd('Data tidak ditemukan.');
        }

        $pesertaPegawais = DB::table('info_perjadinlangsungs as i')
        ->join('data_perjadinlangsungs as dp', 'i.id', '=', 'dp.info_perjadinlangsung')
        ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
        ->join('keuangan_perjadinlangsungs as k', function ($join) {
            $join->on('k.info_perjadinlangsung', '=', 'i.id')
                ->on('k.data_perjadinlangsungs', '=', 'dp.id');
        })
        ->join('akun_x_rkakls as a', 'k.akun_x_rkakl', '=', 'a.id') // Join ke akun_x_rkakls
        ->join('akuns', 'a.akun_id', '=', 'akuns.id')
        ->join('ref_rkakl_sub_komponens', 'a.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
        ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
        ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
        ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
        ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
        ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
        ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
        ->join('surtug_perjadinlangsungs as s', 's.id_perjadinlangsung', '=', 'i.id')
        ->where('i.id', $id)
        ->whereNull('k.kebutuhan_id')
        ->where('dp.status_pegawai', 'Pegawai')
        ->select(
            'i.id as id_info_perjadinlangsungs',
            'p.id',
            'p.nama_lengkap',
            'p.NIP_NIK',
            's.nomor_surat',
            's.tgl_surat_dibuat',
            's.paragraf_1',
            'i.nama_kegiatan',
            'i.tgl_mulai',
            'i.kabupaten_kota',
            'i.provinsi',
            'i.tgl_keberangkatan',
            'i.tgl_selesai',
            'k.uang_harian',
            'k.uang_harian_fullday',
            'k.uang_harian_fullboard',
            'k.uang_representasi',
            'k.jumlah_harga',
            'a.id as idAkun',
            'ref_rkakl_sub_komponens.nama_sub_kegiatan',
            'akuns.uraian',
            'ref_rkakl_satkers.kode_satker',
            'ref_rkakl_programs.kode_program',
            'ref_rkakl_kegiatans.kode_kegiatan',
            'ref_rkakl_outputs.kode_output',
            'ref_rkakl_suboutputs.kode_sub_output',
            'ref_rkakl_komponens.kode_komponen',
            'ref_rkakl_sub_komponens.kode_sub_kegiatan',
            'akuns.kode_akun'
        )
        ->groupBy(
            'i.id',
            'p.id',
            'p.nama_lengkap',
            'p.NIP_NIK',
            's.nomor_surat',
            's.tgl_surat_dibuat',
            's.paragraf_1',
            'i.nama_kegiatan',
            'i.tgl_mulai',
            'i.kabupaten_kota',
            'i.provinsi',
            'i.tgl_keberangkatan',
            'i.tgl_selesai',
            'k.uang_harian',
            'k.uang_harian_fullday',
            'k.uang_harian_fullboard',
            'k.uang_representasi',
            'k.jumlah_harga',
            'a.id',
            'ref_rkakl_sub_komponens.nama_sub_kegiatan',
            'akuns.uraian',
            'ref_rkakl_satkers.kode_satker',
            'ref_rkakl_programs.kode_program',
            'ref_rkakl_kegiatans.kode_kegiatan',
            'ref_rkakl_outputs.kode_output',
            'ref_rkakl_suboutputs.kode_sub_output',
            'ref_rkakl_komponens.kode_komponen',
            'ref_rkakl_sub_komponens.kode_sub_kegiatan',
            'akuns.kode_akun'
        )
        ->distinct()
        ->get();


        $fasilitasPegawais = DB::table('info_perjadinlangsungs as i')
            ->join('data_perjadinlangsungs as dp', 'i.id', '=', 'dp.info_perjadinlangsung')
            ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
            ->join('keuangan_perjadinlangsungs as k', function ($join) {
                $join->on('k.info_perjadinlangsung', '=', 'i.id')
                     ->on('k.data_perjadinlangsungs', '=', 'dp.id');
            })
            ->join('surtug_perjadinlangsungs as s', 's.id_perjadinlangsung', '=', 'i.id')
            ->leftJoin('kebutuhans as kb', 'k.kebutuhan_id', '=', 'kb.id')
            ->where('i.id', $id)
            ->whereNotNull('k.kebutuhan_id')
            ->whereIn('dp.status_pegawai', ['Pegawai', 'Supir'])
            ->select(
                'i.id as id_info_perjadinlangsungs',
                'p.id as pegawai_id',
                'p.nama_lengkap',
                'p.NIP_NIK',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kabupaten_kota',
                'i.provinsi',
                'i.tgl_keberangkatan',
                'i.tgl_selesai',
                'k.jumlah_harga',
                'k.kebutuhan_id',
                'kb.nama as nama_kebutuhan',
                'kb.jumlah_frekuensi',
                'kb.satuan',
                'kb.ket'
            )
            ->groupBy(
                'i.id',
                'p.id',
                'p.nama_lengkap',
                'p.NIP_NIK',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kabupaten_kota',
                'i.provinsi',
                'i.tgl_keberangkatan',
                'i.tgl_selesai',
                'k.jumlah_harga',
                'k.kebutuhan_id',
                'kb.nama',
                'kb.jumlah_frekuensi',
                'kb.satuan',
                'kb.ket'
            )
            ->distinct()
            ->get();


            $pesertaNonPegawais = DB::table('info_perjadinlangsungs as i')
            ->join('data_perjadinlangsungs as dp', 'i.id', '=', 'dp.info_perjadinlangsung')
            ->join('non_pegawais as p', 'dp.non_pegawai_id', '=', 'p.id')
            ->join('keuangan_perjadinlangsungs as k', function ($join) {
                $join->on('k.info_perjadinlangsung', '=', 'i.id')
                    ->on('k.data_perjadinlangsungs', '=', 'dp.id');
            })
            ->join('akun_x_rkakls as a', 'k.akun_x_rkakl', '=', 'a.id') // Join ke akun_x_rkakls
            ->join('akuns', 'a.akun_id', '=', 'akuns.id')
            ->join('ref_rkakl_sub_komponens', 'a.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
            ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
            ->join('surtug_perjadinlangsungs as s', 's.id_perjadinlangsung', '=', 'i.id')
            ->where('i.id', $id)
            ->whereNull('k.kebutuhan_id')
            ->select(
                'i.id as id_info_perjadinlangsungs',
                'p.nama_lengkap',
                'p.NIP_NIK',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kabupaten_kota',
                'i.provinsi',
                'i.tgl_keberangkatan',
                'i.tgl_selesai',
                'k.uang_harian',
                'k.uang_harian_fullday',
                'k.uang_harian_fullboard',
                'k.uang_representasi',
                'k.jumlah_harga',
                'a.id as idAkun',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'ref_rkakl_satkers.kode_satker',
                'ref_rkakl_programs.kode_program',
                'ref_rkakl_kegiatans.kode_kegiatan',
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'akuns.kode_akun'
            )
            ->groupBy(
                'i.id',
                'p.nama_lengkap',
                'p.NIP_NIK',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kabupaten_kota',
                'i.provinsi',
                'i.tgl_keberangkatan',
                'i.tgl_selesai',
                'k.uang_harian',
                'k.uang_harian_fullday',
                'k.uang_harian_fullboard',
                'k.uang_representasi',
                'k.jumlah_harga',
                'a.id',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'ref_rkakl_satkers.kode_satker',
                'ref_rkakl_programs.kode_program',
                'ref_rkakl_kegiatans.kode_kegiatan',
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'akuns.kode_akun'
            )
            ->distinct()
            ->get();


            $pengemudis = DB::table('info_perjadinlangsungs as i')
            ->join('data_perjadinlangsungs as dp', 'i.id', '=', 'dp.info_perjadinlangsung')
            ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
            ->join('keuangan_perjadinlangsungs as k', function ($join) {
                $join->on('k.info_perjadinlangsung', '=', 'i.id')
                    ->on('k.data_perjadinlangsungs', '=', 'dp.id');
            })
            ->join('akun_x_rkakls as a', 'k.akun_x_rkakl', '=', 'a.id') // Join ke akun_x_rkakls
            ->join('akuns', 'a.akun_id', '=', 'akuns.id')
            ->join('ref_rkakl_sub_komponens', 'a.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
            ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
            ->join('surtug_perjadinlangsungs as s', 's.id_perjadinlangsung', '=', 'i.id')
            ->where('i.id', $id)
            ->whereNull('k.kebutuhan_id')
            ->where('dp.status_pegawai', 'Supir')
            ->select(
                'i.id as id_info_perjadinlangsungs',
                'p.nama_lengkap',
                'p.id',
                'p.NIP_NIK',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kabupaten_kota',
                'i.provinsi',
                'i.tgl_keberangkatan',
                'i.tgl_selesai',
                'k.uang_harian',
                'k.uang_harian_fullday',
                'k.uang_harian_fullboard',
                'k.uang_representasi',
                'k.jumlah_harga',
                'a.id as idAkun',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'ref_rkakl_satkers.kode_satker',
                'ref_rkakl_programs.kode_program',
                'ref_rkakl_kegiatans.kode_kegiatan',
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'akuns.kode_akun'
            )
            ->groupBy(
                'i.id',
                'p.id',
                'p.nama_lengkap',
                'p.NIP_NIK',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kabupaten_kota',
                'i.provinsi',
                'i.tgl_keberangkatan',
                'i.tgl_selesai',
                'k.uang_harian',
                'k.uang_harian_fullday',
                'k.uang_harian_fullboard',
                'k.uang_representasi',
                'k.jumlah_harga',
                'a.id',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'ref_rkakl_satkers.kode_satker',
                'ref_rkakl_programs.kode_program',
                'ref_rkakl_kegiatans.kode_kegiatan',
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'akuns.kode_akun'
            )
            ->distinct()
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

        $pegawaiMaster = DB::table('ref_penandatangans')
            ->join('pegawais', 'pegawais.id', '=', 'ref_penandatangans.pegawai_id')
            ->select('pegawais.*')
            ->where('ref_penandatangans.posisi_penandatangan', 'Pejabat Pembuat Komitmen')
            ->where('ref_penandatangans.status_penandatangan', 'aktif')
            ->first();

        $pegawaiBendahara = DB::table('ref_penandatangans')
            ->join('pegawais', 'pegawais.id', '=', 'ref_penandatangans.pegawai_id')
            ->select('pegawais.*')
            ->where('ref_penandatangans.posisi_penandatangan', 'Bendahara')
            ->where('ref_penandatangans.status_penandatangan', 'aktif')
            ->first();


        $datas = [
            'pesertaPegawais' => $pesertaPegawais,
            'fasilitasPegawais' => $fasilitasPegawais,
            'pengemudis' => $pengemudis,
            'pegawaiMaster' => $pegawaiMaster,
            'pegawaiBendahara' => $pegawaiBendahara,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'perjadin' => Info_perjadinlangsung::find($id),
            'akuns' => $akuns,
        ];

        // Lakukan proses pembuatan file PDF
        // Generate PDF
        $pdf = PDF::loadView('admin.perjadin.bendahara.rpd', compact('datas'));
        $pdf->setPaper('A4', 'portrait');

        // Path file PDF
        $filePath = "dokumen-perjadins/rpd_$id.pdf";

        // Cek apakah file sudah ada di penyimpanan
        if (Storage::disk('public')->exists($filePath)) {
            // Hapus file yang sudah ada
            Storage::disk('public')->delete($filePath);
        }

        // Simpan file PDF ke penyimpanan
        Storage::disk('public')->put($filePath, $pdf->output());

        // Stream file PDF ke browser
        return $pdf->stream("rpd_$id.pdf");
    }

    function updateDataSPPD(Request $request) {

        $nPenandatangan = (int) ($request->nPenandatangan);
        // dd($request);

        $tempatTujuan_penandatangan0 = 'tempatTujuan_penandatangan_0';
        DB::table('dokumens')
            ->where('info_perjadinlangsung_id',$request->perjadin_id)
            ->update([
                'tempatTujuan_penandatangan0' => $request->$tempatTujuan_penandatangan0,
        ]);


        for ($i=1; $i <= $nPenandatangan; $i++) {
            $nama_penandatangan = 'nama_penandatangan_' . $i;
            $jabatan_penandatangan = 'jabatan_penandatangan_' . $i;
            $nip_penandatangan = 'nip_penandatangan_' . $i;
            $tempatTiba_penandatangan = 'tempatTiba_penandatangan_' . $i;
            $tempatTujuan_penandatangan = 'tempatTujuan_penandatangan_' . $i;
            $tanggal_penandatangan = 'tanggalTiba_penandatangan_' . $i;
            $tanggalTujuan_penandatangan = 'tanggalBerangkat_penandatangan_' . $i;
            // dd($nPenandatangan,$request->$nama_penandatangan, $request->$jabatan_penandatangan, $request->$nip_penandatangan);
            if ($i == 1) {
                DB::table('dokumens')
                ->where('info_perjadinlangsung_id',$request->perjadin_id)
                ->update([
                    'n_penandatangan' => $nPenandatangan,
                    'nama_penandatangan' => $request->$nama_penandatangan,
                    'jabatan_penandatangan' => $request->$jabatan_penandatangan,
                    'nip_penandatangan' => $request->$nip_penandatangan,
                    'tempatTiba_penandatangan' => $request->$tempatTiba_penandatangan,
                    'tempatTujuan_penandatangan' => $request->$tempatTujuan_penandatangan,
                    'tanggal_penandatangan' => $request->$tanggal_penandatangan,
                    'tanggalTujuan_penandatangan' => $request->$tanggalTujuan_penandatangan,
                ]);
            } else {
                DB::table('dokumens')
                ->where('info_perjadinlangsung_id',$request->perjadin_id)
                ->update([
                    'n_penandatangan' => $nPenandatangan,
                    'nama_penandatangan'.$i => $request->$nama_penandatangan,
                    'jabatan_penandatangan'.$i => $request->$jabatan_penandatangan,
                    'nip_penandatangan'.$i => $request->$nip_penandatangan,
                    'tempatTiba_penandatangan'.$i => $request->$tempatTiba_penandatangan,
                    'tempatTujuan_penandatangan'.$i => $request->$tempatTujuan_penandatangan,
                    'tanggal_penandatangan'.$i => $request->$tanggal_penandatangan,
                    'tanggalTujuan_penandatangan'.$i => $request->$tanggalTujuan_penandatangan,
                ]);
            }
        }

        return $this->CetakSPPD($request->perjadin_id);
    }

    public function CetakSPPD($id)
    {
        if (!$id) {
            // Handle jika data tidak ditemukan
            dd('Data tidak ditemukan.');
        }

        $pesertaPegawais = DB::table('info_perjadinlangsungs as i')
        ->join('data_perjadinlangsungs as dp', 'i.id', '=', 'dp.info_perjadinlangsung')
        ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
        ->join('keuangan_perjadinlangsungs as k', function ($join) {
            $join->on('k.info_perjadinlangsung', '=', 'i.id')
                ->on('k.data_perjadinlangsungs', '=', 'dp.id');
        })
        ->join('akun_x_rkakls as a', 'k.akun_x_rkakl', '=', 'a.id') // Join ke akun_x_rkakls
        ->join('akuns', 'a.akun_id', '=', 'akuns.id')
        ->join('ref_rkakl_sub_komponens', 'a.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
        ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
        ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
        ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
        ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
        ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
        ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
        ->join('surtug_perjadinlangsungs as s', 's.id_perjadinlangsung', '=', 'i.id')
        ->join('jabatans as j', 'j.id', '=', 'p.jabatan_id')
        ->where('i.id', $id)
        ->whereNull('k.kebutuhan_id')
        ->where('dp.status_pegawai', 'Pegawai')
        ->select(
            'i.id as id_info_perjadinlangsungs',
            'p.id',
            'p.nama_lengkap',
            'p.NIP_NIK',
            'p.pangkat',
            'p.golongan',
            'j.nama_jabatan',
            's.nomor_surat',
            's.tgl_surat_dibuat',
            's.paragraf_1',
            'i.nama_kegiatan',
            'i.tgl_mulai',
            'i.kabupaten_kota',
            'i.provinsi',
            'i.tgl_keberangkatan',
            'i.tgl_selesai',
            'k.uang_harian',
            'k.uang_harian_fullday',
            'k.uang_harian_fullboard',
            'k.uang_representasi',
            'k.jumlah_harga',
            'a.id as idAkun',
            'ref_rkakl_sub_komponens.nama_sub_kegiatan',
            'akuns.uraian',
            'ref_rkakl_satkers.kode_satker',
            'ref_rkakl_programs.kode_program',
            'ref_rkakl_kegiatans.kode_kegiatan',
            'ref_rkakl_outputs.kode_output',
            'ref_rkakl_suboutputs.kode_sub_output',
            'ref_rkakl_komponens.kode_komponen',
            'ref_rkakl_sub_komponens.kode_sub_kegiatan',
            'akuns.kode_akun'
        )
        ->groupBy(
            'i.id',
            'p.id',
            'p.nama_lengkap',
            'p.NIP_NIK',
            'p.pangkat',
            'p.golongan',
            'j.nama_jabatan',
            's.nomor_surat',
            's.tgl_surat_dibuat',
            's.paragraf_1',
            'i.nama_kegiatan',
            'i.tgl_mulai',
            'i.kabupaten_kota',
            'i.provinsi',
            'i.tgl_keberangkatan',
            'i.tgl_selesai',
            'k.uang_harian',
            'k.uang_harian_fullday',
            'k.uang_harian_fullboard',
            'k.uang_representasi',
            'k.jumlah_harga',
            'a.id',
            'ref_rkakl_sub_komponens.nama_sub_kegiatan',
            'akuns.uraian',
            'ref_rkakl_satkers.kode_satker',
            'ref_rkakl_programs.kode_program',
            'ref_rkakl_kegiatans.kode_kegiatan',
            'ref_rkakl_outputs.kode_output',
            'ref_rkakl_suboutputs.kode_sub_output',
            'ref_rkakl_komponens.kode_komponen',
            'ref_rkakl_sub_komponens.kode_sub_kegiatan',
            'akuns.kode_akun'
        )
        ->distinct()
        ->get();


        $fasilitasPegawais = DB::table('info_perjadinlangsungs as i')
            ->join('data_perjadinlangsungs as dp', 'i.id', '=', 'dp.info_perjadinlangsung')
            ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
            ->join('keuangan_perjadinlangsungs as k', function ($join) {
                $join->on('k.info_perjadinlangsung', '=', 'i.id')
                     ->on('k.data_perjadinlangsungs', '=', 'dp.id');
            })
            ->join('surtug_perjadinlangsungs as s', 's.id_perjadinlangsung', '=', 'i.id')
            ->leftJoin('kebutuhans as kb', 'k.kebutuhan_id', '=', 'kb.id')
            ->where('i.id', $id)
            ->whereNotNull('k.kebutuhan_id')
            ->whereIn('dp.status_pegawai', ['Pegawai', 'Supir'])
            ->select(
                'i.id as id_info_perjadinlangsungs',
                'p.id as pegawai_id',
                'p.nama_lengkap',
                'p.NIP_NIK',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kabupaten_kota',
                'i.provinsi',
                'i.tgl_keberangkatan',
                'i.tgl_selesai',
                'k.jumlah_harga',
                'k.kebutuhan_id',
                'kb.nama as nama_kebutuhan',
                'kb.jumlah_frekuensi',
                'kb.satuan',
                'kb.ket'
            )
            ->groupBy(
                'i.id',
                'p.id',
                'p.nama_lengkap',
                'p.NIP_NIK',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kabupaten_kota',
                'i.provinsi',
                'i.tgl_keberangkatan',
                'i.tgl_selesai',
                'k.jumlah_harga',
                'k.kebutuhan_id',
                'kb.nama',
                'kb.jumlah_frekuensi',
                'kb.satuan',
                'kb.ket'
            )
            ->distinct()
            ->get();


        $pesertaNonPegawais = DB::table('info_perjadinlangsungs as i')
            ->join('data_perjadinlangsungs as dp', 'i.id', '=', 'dp.info_perjadinlangsung')
            ->join('non_pegawais as p', 'dp.non_pegawai_id', '=', 'p.id')
            ->join('keuangan_perjadinlangsungs as k', function ($join) {
                $join->on('k.info_perjadinlangsung', '=', 'i.id')
                    ->on('k.data_perjadinlangsungs', '=', 'dp.id');
            })
            ->join('akun_x_rkakls as a', 'k.akun_x_rkakl', '=', 'a.id') // Join ke akun_x_rkakls
            ->join('akuns', 'a.akun_id', '=', 'akuns.id')
            ->join('ref_rkakl_sub_komponens', 'a.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
            ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
            ->join('surtug_perjadinlangsungs as s', 's.id_perjadinlangsung', '=', 'i.id')
            ->where('i.id', $id)
            ->whereNull('k.kebutuhan_id')
            ->select(
                'i.id as id_info_perjadinlangsungs',
                'p.nama_lengkap',
                'p.NIP_NIK',
                'p.pangkat',
                'p.golongan',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kabupaten_kota',
                'i.provinsi',
                'i.tgl_keberangkatan',
                'i.tgl_selesai',
                'k.uang_harian',
                'k.uang_harian_fullday',
                'k.uang_harian_fullboard',
                'k.uang_representasi',
                'k.jumlah_harga',
                'a.id as idAkun',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'ref_rkakl_satkers.kode_satker',
                'ref_rkakl_programs.kode_program',
                'ref_rkakl_kegiatans.kode_kegiatan',
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'akuns.kode_akun'
            )
            ->groupBy(
                'i.id',
                'p.nama_lengkap',
                'p.NIP_NIK',
                'p.pangkat',
                'p.golongan',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kabupaten_kota',
                'i.provinsi',
                'i.tgl_keberangkatan',
                'i.tgl_selesai',
                'k.uang_harian',
                'k.uang_harian_fullday',
                'k.uang_harian_fullboard',
                'k.uang_representasi',
                'k.jumlah_harga',
                'a.id',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'ref_rkakl_satkers.kode_satker',
                'ref_rkakl_programs.kode_program',
                'ref_rkakl_kegiatans.kode_kegiatan',
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'akuns.kode_akun'
            )
            ->distinct()
            ->get();


        $pengemudis = DB::table('info_perjadinlangsungs as i')
            ->join('data_perjadinlangsungs as dp', 'i.id', '=', 'dp.info_perjadinlangsung')
            ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
            ->join('keuangan_perjadinlangsungs as k', function ($join) {
                $join->on('k.info_perjadinlangsung', '=', 'i.id')
                    ->on('k.data_perjadinlangsungs', '=', 'dp.id');
            })
            ->join('akun_x_rkakls as a', 'k.akun_x_rkakl', '=', 'a.id') // Join ke akun_x_rkakls
            ->join('akuns', 'a.akun_id', '=', 'akuns.id')
            ->join('ref_rkakl_sub_komponens', 'a.ref_sub_komponen_id', '=', 'ref_rkakl_sub_komponens.id')
            ->join('ref_rkakl_komponens', 'ref_rkakl_sub_komponens.ref_rkakl_komponen_id', '=', 'ref_rkakl_komponens.id')
            ->join('ref_rkakl_suboutputs', 'ref_rkakl_komponens.ref_rkakl_suboutput_id', '=', 'ref_rkakl_suboutputs.id')
            ->join('ref_rkakl_outputs', 'ref_rkakl_suboutputs.ref_rkakl_output_id', '=', 'ref_rkakl_outputs.id')
            ->join('ref_rkakl_kegiatans', 'ref_rkakl_outputs.ref_rkakl_kegiatan_id', '=', 'ref_rkakl_kegiatans.id')
            ->join('ref_rkakl_programs', 'ref_rkakl_kegiatans.ref_rkakl_program_id', '=', 'ref_rkakl_programs.id')
            ->join('ref_rkakl_satkers', 'ref_rkakl_programs.ref_rkakl_satker_id', '=', 'ref_rkakl_satkers.id')
            ->join('surtug_perjadinlangsungs as s', 's.id_perjadinlangsung', '=', 'i.id')
            ->join('jabatans as j', 'j.id', '=', 'p.jabatan_id')
            ->where('i.id', $id)
            ->whereNull('k.kebutuhan_id')
            ->where('dp.status_pegawai', 'Supir')
            ->select(
                'i.id as id_info_perjadinlangsungs',
                'p.nama_lengkap',
                'p.id',
                'p.NIP_NIK',
                'p.pangkat',
                'p.golongan',
                'j.nama_jabatan',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kabupaten_kota',
                'i.provinsi',
                'i.tgl_keberangkatan',
                'i.tgl_selesai',
                'k.uang_harian',
                'k.uang_harian_fullday',
                'k.uang_harian_fullboard',
                'k.uang_representasi',
                'k.jumlah_harga',
                'a.id as idAkun',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'ref_rkakl_satkers.kode_satker',
                'ref_rkakl_programs.kode_program',
                'ref_rkakl_kegiatans.kode_kegiatan',
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'akuns.kode_akun'
            )
            ->groupBy(
                'i.id',
                'p.id',
                'p.nama_lengkap',
                'p.NIP_NIK',
                'p.pangkat',
                'p.golongan',
                'j.nama_jabatan',
                's.nomor_surat',
                's.tgl_surat_dibuat',
                's.paragraf_1',
                'i.nama_kegiatan',
                'i.tgl_mulai',
                'i.kabupaten_kota',
                'i.provinsi',
                'i.tgl_keberangkatan',
                'i.tgl_selesai',
                'k.uang_harian',
                'k.uang_harian_fullday',
                'k.uang_harian_fullboard',
                'k.uang_representasi',
                'k.jumlah_harga',
                'a.id',
                'ref_rkakl_sub_komponens.nama_sub_kegiatan',
                'akuns.uraian',
                'ref_rkakl_satkers.kode_satker',
                'ref_rkakl_programs.kode_program',
                'ref_rkakl_kegiatans.kode_kegiatan',
                'ref_rkakl_outputs.kode_output',
                'ref_rkakl_suboutputs.kode_sub_output',
                'ref_rkakl_komponens.kode_komponen',
                'ref_rkakl_sub_komponens.kode_sub_kegiatan',
                'akuns.kode_akun'
            )
            ->distinct()
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

        $pegawaiMaster = DB::table('administrators')
            ->join('pegawais', 'pegawais.nama_lengkap', '=', 'administrators.username')
            ->select('pegawais.*')
            ->where('administrators.role', 'Master')
            ->first();

        $pegawaiBendahara = DB::table('administrators')
            ->join('pegawais', 'pegawais.nama_lengkap', '=', 'administrators.username')
            ->select('pegawais.*')
            ->where('administrators.role', 'Bendahara')
            ->first();

        $ttdSPPD = DB::table('dokumens')
            ->where('info_perjadinlangsung_id',$id)
            ->select(
                'tempatTujuan_penandatangan0 as tempatTujuan0',
                'nama_penandatangan as nama1','jabatan_penandatangan as jabatan1','nip_penandatangan as nip1', 'tempatTiba_penandatangan as tempatTiba1','tempatTujuan_penandatangan as tempatTujuan1','tanggal_penandatangan as tanggal1',  'tanggalTujuan_penandatangan as tanggalTujuan1',
                'nama_penandatangan2 as nama2','jabatan_penandatangan2 as jabatan2','nip_penandatangan2 as nip2', 'tempatTiba_penandatangan2 as tempatTiba2','tempatTujuan_penandatangan2 as tempatTujuan2','tanggal_penandatangan2 as tanggal2', 'tanggalTujuan_penandatangan2 as tanggalTujuan2',
                'nama_penandatangan3 as nama3','jabatan_penandatangan3 as jabatan3','nip_penandatangan3 as nip3', 'tempatTiba_penandatangan3 as tempatTiba3','tempatTujuan_penandatangan3 as tempatTujuan3','tanggal_penandatangan3 as tanggal3', 'tanggalTujuan_penandatangan3 as tanggalTujuan3',
                'nama_penandatangan4 as nama4','jabatan_penandatangan4 as jabatan4','nip_penandatangan4 as nip4', 'tempatTiba_penandatangan4 as tempatTiba4','tempatTujuan_penandatangan4 as tempatTujuan4','tanggal_penandatangan4 as tanggal4', 'tanggalTujuan_penandatangan4 as tanggalTujuan4',
                'n_penandatangan as nSPPD',
                )
            ->first();

            // dd($ttdSPPD);

        $datas = [
            'pesertaPegawais' => $pesertaPegawais,
            'fasilitasPegawais' => $fasilitasPegawais,
            'pengemudis' => $pengemudis,
            'pegawaiMaster' => $pegawaiMaster,
            'pegawaiBendahara' => $pegawaiBendahara,
            'pesertaNonPegawais' => $pesertaNonPegawais,
            'perjadin' => Info_perjadinlangsung::find($id),
            'ttdSPPD' => $ttdSPPD,
            'akuns' => $akuns,
        ];

        // Lakukan proses pembuatan file PDF
        $pdf = PDF::loadView('admin.perjadin.keuangan.sppd', compact('datas'));
        $pdf->setPaper('F4', 'portrait');

        // Simpan file PDF ke penyimpanan
        $filePath = $pdf->output();
        Storage::disk('public')->put("dokumen-perjadins/sppd_$id.pdf", $filePath);

        // Stream file PDF ke browser
        return $pdf->stream("sppd_$id.pdf");
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getDokumen($filename)
    {
        $path = storage_path('app/public/dokumen-perjadins/' . $filename);
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->file($path);
    }
}
