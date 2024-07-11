<?php

namespace App\Http\Controllers;

use App\Models\Data_perjadinlangsung;
use App\Models\Dokumen;
use App\Models\Info_perjadinlangsung;
use App\Models\Kebutuhan;
use App\Models\Keuangan_perjadinlangsung;
use App\Models\Non_pegawai;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Versi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\File;


class PerjadinController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        //
        return view('user.perjadin.index', [
            'title' => 'Perjalanan Dinas',
            'active' => 'perjadin_biasa',
        ]);
    }

    public function step1($id)
    {
        return view('user.perjadin.index', [
            'title' => 'Pengajuan Kegiatan',
            'active' => 'perjadin_kegiatan',
            "perjadin" => Data_perjadinlangsung::find($id)
        ]);
    }

    public function step2($id)
    {
        // Menggunakan metode find untuk mengambil data berdasarkan ID
        $infoPerjadin = Info_perjadinlangsung::find($id);

        if ($infoPerjadin) {
            $tanggalAwal = Carbon::parse($infoPerjadin->tgl_keberangkatan);
            $tanggalAkhir = Carbon::parse($infoPerjadin->tgl_selesai);
        } else {
            // Penanganan jika tidak ada record yang ditemukan
            $tanggalAwal = null;
            $tanggalAkhir = null;
        }

        $selectPeserta = DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'pegawai_id', '=', 'pegawais.id')
            ->join('info_perjadinlangsungs', 'info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('data_perjadinlangsungs.id', 'data_perjadinlangsungs.pegawai_id', 'data_perjadinlangsungs.status_pegawai', 'pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();

        $selectPeserta_nonPegawai = DB::table('data_perjadinlangsungs')
            ->join('non_pegawais', 'non_pegawai_id', '=', 'non_pegawais.id')
            ->select('data_perjadinlangsungs.id', 'data_perjadinlangsungs.status_pegawai', 'non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();

        $kebutuhans = DB::table('keuangan_perjadinlangsungs')
            ->join('kebutuhans', 'keuangan_perjadinlangsungs.kebutuhan_id', '=', 'kebutuhans.id')
            ->select('kebutuhans.id as idKebutuhan', 'kebutuhans.nama', 'kebutuhans.jumlah_frekuensi', 'kebutuhans.satuan', 'kebutuhans.tipe_pendanaan', 'kebutuhans.ket', 'keuangan_perjadinlangsungs.info_perjadinlangsung',  'keuangan_perjadinlangsungs.kebutuhan_id', 'keuangan_perjadinlangsungs.data_perjadinlangsungs','keuangan_perjadinlangsungs.kebutuhan_id', 'keuangan_perjadinlangsungs.status')
            ->where('keuangan_perjadinlangsungs.info_perjadinlangsung', $id)
            ->groupBy('kebutuhans.id', 'kebutuhans.nama', 'kebutuhans.jumlah_frekuensi', 'kebutuhans.satuan', 'kebutuhans.tipe_pendanaan', 'kebutuhans.ket', 'keuangan_perjadinlangsungs.info_perjadinlangsung', 'keuangan_perjadinlangsungs.kebutuhan_id', 'keuangan_perjadinlangsungs.data_perjadinlangsungs','keuangan_perjadinlangsungs.status')
            ->get();

        $pegawais = DB::table('pegawais')
            ->select('pegawais.id', 'pegawais.nama_lengkap')
            ->whereNotExists(function ($query) use ($tanggalAwal, $tanggalAkhir) {
                $query->select(DB::raw(1))
                    ->from('data_perjadinlangsungs')
                    ->whereRaw('pegawais.id = data_perjadinlangsungs.pegawai_id')
                    ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                        $subquery->where('data_perjadinlangsungs.tgl_keberangkatan', '<=', $tanggalAkhir->format('Y-m-d'))
                            ->where('data_perjadinlangsungs.tgl_selesai', '>=', $tanggalAwal->format('Y-m-d'));
                    });
            })
            ->distinct()
            ->get();
        // dd($pegawais);
        return view(
            'user.perjadin.perjadin_step2',
            [
                'title' => 'Perjalanan Dinas',
                'active' => 'perjadin_biasa',
                "pegawais" =>  $pegawais,
                "nonpegawais" => Non_pegawai::all(),
                "perjadin" => Info_perjadinlangsung::find($id),
                "selectPesertas" => $selectPeserta,
                "selectPesertasNonPegawais" => $selectPeserta_nonPegawai,
                "fasilitas" => $kebutuhans
            ]
        );
    }

    public function riwayat($status = 'pengajuan')
    {
        //
        // $perjadins = Info_perjadinlangsung::where('status_pengajuan', $status)->get();
        $riwayatPerjadin = DB::table('info_perjadinlangsungs')
            ->join('data_perjadinlangsungs', 'data_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->select('info_perjadinlangsungs.id as idPerjadin', 'info_perjadinlangsungs.nama_kegiatan', 'info_perjadinlangsungs.tgl_keberangkatan', 'info_perjadinlangsungs.status_pengajuan', 'info_perjadinlangsungs.status_pengajuan_detail', 'data_perjadinlangsungs.pegawai_id', 'pegawais.id')
            ->where('pegawais.id', auth('pegawai')->user()->id)
            ->where('info_perjadinlangsungs.status_pengajuan', $status)
            ->get();

        return view(
            'user.perjadin.riwayat',
            [
                'title' => 'Kegiatanku',
                'active' => 'kegiatanku_perjadin',
                'status' => $status,
                "perjadins" => $riwayatPerjadin
            ]
        );
    }

    public function detail_perjadin($id)
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
        return view(
            'user.perjadin.detail',
            [
                'title' => 'Detail Kegiatanku',
                'active' => 'kegiatanku_perjadin',
                "pegawais" => Pegawai::all(),
                "nonpegawais" => Non_pegawai::all(),
                "perjadin" => Info_perjadinlangsung::find($id),
                "selectPesertas" => $selectPeserta,
                "selectPesertasNonPegawais" => $selectPeserta_nonPegawai,
                "dokumen" => Dokumen::where('info_perjadinlangsung_id', $id)->first(),
                "fasilitas" => $kebutuhans,
                'mobilitass' => $mobilitas
            ]
        );
    }

    public function note_perjadin($id)
    {
        $pesertaPegawais = DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
            ->select('pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'data_perjadinlangsungs.status_pegawai', 'pegawais.NIP_NIK')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();
        $pesertaNonPegawais = DB::table('data_perjadinlangsungs')
            ->join('non_pegawais', 'data_perjadinlangsungs.non_pegawai_id', '=', 'non_pegawais.id')
            ->select('non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'data_perjadinlangsungs.status_pegawai')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $id)
            ->get();
        $surat = DB::table('surtug_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'surtug_perjadinlangsungs.id_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('surtug_perjadinlangsungs.nomor_surat', 'surtug_perjadinlangsungs.perihal', 'surtug_perjadinlangsungs.paragraf_1', 'surtug_perjadinlangsungs.paragraf_2', 'surtug_perjadinlangsungs.paragraf_3')
            ->where('surtug_perjadinlangsungs.id_perjadinlangsung', $id)
            ->get();

        $pic = $pesertaPegawais->firstWhere('status_pegawai', 'PIC');
        return view(
            'user.perjadin.laporan_perjadin',
            [
                'title' => 'Detail Kegiatanku',
                'active' => 'kegiatanku_perjadin',
                'perjadin' => Info_perjadinlangsung::find($id),
                'dokumen' => Dokumen::where('info_perjadinlangsung_id', $id)->get(),
                'surtugs' => $surat->first(),
                'pesertaPegawais' => $pesertaPegawais,
                'pesertaNonPegawais' => $pesertaNonPegawais,
                'pic' => $pic
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
    public function store(Request $request)
    {
        $versi = Versi::where('status', 'aktif')->get();
        DB::table('info_perjadinlangsungs')->insertOrIgnore([
            'nama_kegiatan' => $request->nama_kegiatan,
            'pemberi_undangan' => $request->pemberi_undangan,
            'tanggal_surat' => $request->tanggal_surat,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'tgl_keberangkatan' => $request->tgl_keberangkatan,
            'provinsi' => $request->provinsi,
            'kabupaten_kota' => $request->kabupaten_kota,
            'alamat' => $request->alamat,
            'mobilitas' => $request->fasilitas_perjadin,
            'keterangan_mobilitas' => $request->keterangan_mobilitas,
            'status_pengajuan' => 'Draf-pengajuan',
            'versi_id' => $versi[0]->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $perjadin = Info_perjadinlangsung::max('id'); // mengambil nilai id terakhir yang diinputkan
        // pemilihan fasilitas tranfortasi
        $select_tranfortasi = $request->fasilitas_perjadin;
        if (($select_tranfortasi == 'Kendaraan Dinas') | ($select_tranfortasi == 'Kendaraan Dinas dan Transportasi Publik')) {
            DB::table('peminjaman_kendaraan_dinas')->insertOrIgnore([
                'info_perjadinlangsung' => $perjadin, //menerima id info terakhir
                'kendaraan' => $request->fasilitas_perjadinn,
                'status' => 'pengajuan',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } elseif ($select_tranfortasi == 'Transportasi Publik' || $select_tranfortasi == 'Kendaraan Pribadi') {
            // Jika jenis fasilitas adalah 2 atau 4, update info_perjadinlangsungs yang sesuai
            DB::table('info_perjadinlangsungs')
                ->where('id', $perjadin)
                ->update([
                    'is_acceptHKT' => 'pengajuan',
                    'is_acceptBMN' => 'proses',
                    'status_pengajuan_detail' => 'Verifikasi-HKT',
                    'status_pengajuan' => 'pengajuan',
                    'updated_at' => now(),
                ]);
        }
        return redirect()->route('perjadin_step_2', ['id' => $perjadin])->with('success', 'Permohonan Perjalanan Dinas berhasil dibuat. Silakan masukkan nama peserta!');
    }

    // lagi di edit
    public function storePeserta(Request $request)
    {
        // $perjadinberlangsung = DB::table('data_perjadinlangsungs')
        //     ->select('info_perjadinlangsungs.nama_kegiatan', 'info_perjadinlangsungs.tgl_mulai', 'info_perjadinlangsungs.tgl_selesai', 'pegawais.nama_lengkap', 'pegawais.id')
        //     ->join('info_perjadinlangsungs', 'data_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
        //     ->join('pegawais', 'data_perjadinlangsungs.pegawai_id', '=', 'pegawais.id')
        //     ->where('pegawais.id', '=', $request->peserta_pegawai)
        //     ->first();

        DB::table('data_perjadinlangsungs')->insertOrIgnore([
            'status_pegawai' => 'Pegawai',
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'pegawai_id' => $request->peserta_pegawai,
            'tgl_keberangkatan' => $request->berangkat,
            'tgl_selesai' => $request->selesai,
            // 'non_pegawai_id' => $request->peserta_non_pegawai,
            'status_persetujuan' => 'Proses Persetujuan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $data_perjaidinlangsung_max = data_perjadinlangsung::max('id');

        DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'data_perjadinlangsungs' => $data_perjaidinlangsung_max,
            'created_at' => now(),
            'updated_at' => now(),
        ]);



        $id = $request->info_perjadinlangsung;
        return redirect()->route('perjadin_step_2', ['id' => $id])->with('success', 'Peserta baru berhasil ditambahkan!');
    }

    public function storePesertaDetail(Request $request)
    {

        DB::table('data_perjadinlangsungs')->insertOrIgnore([
            'status_pegawai' => 'Pegawai',
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'pegawai_id' => $request->peserta_pegawai,
            // 'non_pegawai_id' => $request->peserta_non_pegawai,
            'status_persetujuan' => 'Proses Persetujuan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $data_perjaidinlangsung_max = data_perjadinlangsung::max('id');

        DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'data_perjadinlangsungs' => $data_perjaidinlangsung_max,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $id = $request->info_perjadinlangsung;
        return redirect()->route('detail-perjadin', ['id' => $id])->with('success', 'Peserta baru berhasil ditambahkan!');
    }

    public function storeNonPeserta(Request $request)
    {
        $non_pegawai_tersedia = $request->peserta_non_pegawai;
        if ($non_pegawai_tersedia != null) {
            DB::table('data_perjadinlangsungs')->insertOrIgnore([
                'status_pegawai' => 'Pegawai',
                'info_perjadinlangsung' => $request->info_perjadinlangsung,
                'non_pegawai_id' => $request->peserta_non_pegawai,
                'status_persetujuan' => 'Proses Persetujuan',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($request->nama_lengkap != null) {
            DB::table('non_pegawais')->insertOrIgnore([
                'NIP_NIK' => $request->NIP_NIK,
                'nama_lengkap' => $request->nama_lengkap,
                'golongan' => $request->golongan,
                'pangkat' => $request->pangkat,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $id_non_pegawai_new = Non_pegawai::max('id'); // mengambil id non pegawai yang baru diinput
            DB::table('data_perjadinlangsungs')->insertOrIgnore([
                'status_pegawai' => 'Pegawai',
                'info_perjadinlangsung' => $request->info_perjadinlangsung,
                'non_pegawai_id' => $id_non_pegawai_new,
                'status_persetujuan' => 'Proses Persetujuan',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $data_perjaidinlangsung_max = data_perjadinlangsung::max('id');

        DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'data_perjadinlangsungs' => $data_perjaidinlangsung_max,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        $id = $request->info_perjadinlangsung;
        return redirect()->route('perjadin_step_2', ['id' => $id])->with('success', 'Peserta baru berhasil ditambahkan!');
    }

    public function storeNonPesertaDetail(Request $request)
    {
        $non_pegawai_tersedia = $request->peserta_non_pegawai;
        if ($non_pegawai_tersedia != null) {
            DB::table('data_perjadinlangsungs')->insertOrIgnore([
                'status_pegawai' => 'Pegawai',
                'info_perjadinlangsung' => $request->info_perjadinlangsung,
                'non_pegawai_id' => $request->peserta_non_pegawai,
                'status_persetujuan' => 'Proses Persetujuan',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($request->nama_lengkap != null) {
            DB::table('non_pegawais')->insertOrIgnore([
                'NIP_NIK' => $request->NIP_NIK,
                'nama_lengkap' => $request->nama_lengkap,
                'golongan' => $request->golongan,
                'pangkat' => $request->pangkat,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $id_non_pegawai_new = Non_pegawai::max('id'); // mengambil id non pegawai yang baru diinput
            DB::table('data_perjadinlangsungs')->insertOrIgnore([
                'status_pegawai' => 'Pegawai',
                'info_perjadinlangsung' => $request->info_perjadinlangsung,
                'non_pegawai_id' => $id_non_pegawai_new,
                'status_persetujuan' => 'Proses Persetujuan',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $data_perjaidinlangsung_max = data_perjadinlangsung::max('id');

        DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'data_perjadinlangsungs' => $data_perjaidinlangsung_max,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        $id = $request->info_perjadinlangsung;
        return redirect()->route('perjadin_step_2', ['id' => $id])->with('success', 'Peserta baru berhasil ditambahkan!');
    }

    public function storeFasilitas(Request $request)
    {
        DB::table('kebutuhans')->insert([
            'nama' => $request->uraian,
            'status' => 'Pengajuan',
            'jumlah_frekuensi' => $request->jumlah_frekuensi,
            'satuan' => $request->satuan,
            'tipe_pendanaan' => $request->tipe_pendanaan,
            'ket' => $request->keterangan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $kebutuhan_max = Kebutuhan::max('id');
        DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'kebutuhan_id' => $kebutuhan_max,
            'status' => 'Menunggu Persetujuan Bendahara',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $id = $request->info_perjadinlangsung;
        return redirect()->route('perjadin_step_2', ['id' => $id])->with('success', 'Fasilitas berhasil ditambahkan!');
    }

    public function storeFasilitasDetail(Request $request)
    {
        DB::table('kebutuhans')->insert([
            'nama' => $request->uraian,
            'status' => 'Pengajuan',
            'jumlah_frekuensi' => $request->jumlah_frekuensi,
            'satuan' => $request->satuan,
            'tipe_pendanaan' => $request->tipe_pendanaan,
            'ket' => $request->keterangan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $kebutuhan_max = Kebutuhan::max('id');

        $data_perjadinlangsung = DB::table('data_perjadinlangsungs')
            ->where('id', $request->pegawai_id)
            ->first();;

        if ($data_perjadinlangsung) {
            DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
                'info_perjadinlangsung' => $request->info_perjadinlangsung,
                'data_perjadinlangsungs' => $data_perjadinlangsung->id, // Assuming 'data_perjadinlangsungs' has 'id' column
                'kebutuhan_id' => $kebutuhan_max,
                'status' => 'Menunggu Persetujuan Bendahara',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $id = $request->info_perjadinlangsung;

            return redirect()->route('perjadin_step_2', ['id' => $id])->with('success', 'Fasilitas berhasil ditambahkan!');
        } else {
            return redirect()->route('perjadin_step_2')->with('error', 'Tidak ada data perjadin langsung dengan status pegawai PIC.');
        }
    }

    public function storePerjadin(Request $request)
    {

        $validationData = $request->validate([
            'surat_undangan' => 'required|mimes:pdf|file|max:2048',
        ]);
        $id = $request->info_perjadinlangsung;
        $cek_peserta = data_perjadinlangsung::where('info_perjadinlangsung', $id)->get();
        if ($cek_peserta->isEmpty()) {
            return redirect()->route('perjadin_step_2', ['id' => $id])->with('success', 'Peserta tidak boleh kosong. Mohon Isi data peserta yang akan mengikuti perjalanan dinas!');
        }
        DB::table('dokumens')->insert([
            'info_perjadinlangsung_id' => $request->info_perjadinlangsung,
            'surat_undangan' => $validationData['surat_undangan'] = $request->file('surat_undangan')->store('dokumen-perjadins', 'public'),
            'status_persetujuan' => 'pengajuan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        DB::table('info_perjadinlangsungs')
            ->where('id', $id)
            ->whereNull('is_acceptBMN')
            ->whereNull('is_acceptHKT')
            ->update([
                'status_pengajuan' => 'pengajuan',
                'is_acceptBMN' => 'pengajuan',
                'status_pengajuan_detail' => 'Verifikasi-BMN',
                'updated_at' => now(),
            ]);

        return redirect()->route('riwayat', ['status' => 'pengajuan'])->with('success', 'Perjalanan dinas berhasil diajukan. Silakan tunggu persetujuan dari pihak Keuangan!');
    }

    public function storeLaporanPerjadin(Request $request)
    {
        DB::table('dokumens')
            ->where('info_perjadinlangsung_id', $request->perjadin)
            ->update([
                'nama_pelaksana' => $request->pelaksana,
                'tempat_pelaksanaan' => $request->tempat,
                'hasil' => $request->hasil,
            ]);

        return redirect()->route('detail-perjadin', ['id' => $request->perjadin])->with('success', 'Laporan berhasil dibuat, tunggu konfirmasi dari pihak keuangan!');
    }

    public function previewPerjadinUser($id)
    {
        return view(
            'user.perjadin.preview_lap_perjadin',
            [
                'title' => 'Detail Kegiatanku',
                'active' => 'kegiatanku_perjadin',
                'perjadin' => Info_perjadinlangsung::find($id),
                'dokumen' => Dokumen::where('info_perjadinlangsung_id', $id)->get(),
            ]
        );
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

    public function updatePerjadinDetail(Request $request)
    {
        $action = $request->input('action');
        if ($action === 'update') {
            $validationData = $request->validate([
                'surat_undangan' => 'mimes:pdf|file|max:2048',
                'surat_tugas' => 'mimes:pdf|file|max:2048',
                'SPPD' => 'mimes:pdf|file|max:2048',
                'lap_pengeluaran' => 'mimes:pdf|file|max:2048',
                'lap_perjadin' => 'mimes:pdf|file|max:2048',
            ]);

            if ($request->surat_undangan) {
                if ($request->oldSuratUndangan) {
                    Storage::delete($request->oldSuratUndangan);
                }
                $validationData['surat_undangan'] = $request->file('surat_undangan')->store('dokumen-perjadins', 'public');
            }

            if ($request->surat_tugas) {
                if ($request->oldSuratTugas) {
                    Storage::delete($request->oldSuratTugas);
                }
                $validationData['surat_tugas'] = $request->file('surat_tugas')->store('dokumen-perjadins', 'public');
            }

            if ($request->SPPD) {
                if ($request->oldSppd) {
                    Storage::delete($request->oldSppd);
                }
                $validationData['SPPD'] = $request->file('SPPD')->store('dokumen-perjadins', 'public');
            }

            if ($request->lap_pengeluaran) {
                if ($request->oldlap_pengeluaran) {
                    Storage::delete($request->oldlap_pengeluaran);
                }
                $validationData['lap_pengeluaran'] = $request->file('lap_pengeluaran')->store('dokumen-perjadins', 'public');
            }

            if ($request->lap_perjadin) {
                if ($request->oldLap_perjadin) {
                    Storage::delete($request->oldLap_perjadin);
                }
                $validationData['lap_perjadin'] = $request->file('lap_perjadin')->store('dokumen-perjadins', 'public');
            }

            Dokumen::where('info_perjadinlangsung_id', $request->info_perjadinlangsung)->update($validationData);
            if ($request->status_pejadin == 'proses') {
                DB::table('info_perjadinlangsungs')
                    ->where('id', $request->info_perjadinlangsung)
                    ->update(['status_pengajuan' => 'proses']);
                return redirect()->route('riwayat', ['status' => 'proses'])->with('success', 'Data Perjalanan dinas telah diperbaharui, tunggu konfirmasi dari pihak keuangan');
            }

            if ($request->status_pejadin == 'Draf-pengajuan') {
                DB::table('info_perjadinlangsungs')
                    ->where('id', $request->info_perjadinlangsung)
                    ->update(['status_pengajuan' => 'pengajuan']);
                return redirect()->route('riwayat', ['status' => 'pengajuan'])->with('success', 'Data Perjalanan dinas telah diperbaharui, tunggu konfirmasi dari pihak keuangan');
            }

            if (($request->status_pejadin == 'revisi') and ($request->status_pejadin_keuangan == 'revisi-1')) {
                DB::table('info_perjadinlangsungs')
                    ->where('id', $request->info_perjadinlangsung)
                    ->update([
                        'status_pengajuan' => 'pengajuan',
                        'is_acceptKeu' => 'verifikasi-1'
                    ]);
                return redirect()->route('riwayat', ['status' => 'pengajuan'])->with('success', 'Data Perjalanan dinas telah diperbaharui, tunggu konfirmasi dari pihak keuangan');
            }

            if (($request->status_pejadin == 'revisi') and ($request->status_pejadin_keuangan == 'revisi-2')) {
                DB::table('info_perjadinlangsungs')
                    ->where('id', $request->info_perjadinlangsung)
                    ->update([
                        'status_pengajuan' => 'proses',
                        'is_acceptKeu' => 'verifikasi-2'
                    ]);
                return redirect()->route('riwayat', ['status' => 'proses'])->with('success', 'Data Perjalanan dinas telah diperbaharui, tunggu konfirmasi dari pihak keuangan');
            }

            if ($request->status_pejadin == 'pelaporan') {
                DB::table('info_perjadinlangsungs')
                    ->where('id', $request->info_perjadinlangsung)
                    ->update([
                        'status_pengajuan' => 'selesai',
                        'is_acceptKeu' => 'verifikasi-2'
                    ]);

                // Simpan file SPPD
                if ($request->hasFile('SPPD') && $request->file('SPPD')->isValid()) {
                    $SPPDPath = $request->file('SPPD')->store('dokumen-perjadins', 'public');
                    DB::table('dokumens')
                        ->where('info_perjadinlangsung_id', $request->info_perjadinlangsung)
                        ->update(['SPPD' => $SPPDPath]);
                }

                // Simpan file lap_pengeluaran
                if ($request->hasFile('lap_pengeluaran') && $request->file('lap_pengeluaran')->isValid()) {
                    $lapPengeluaranPath = $request->file('lap_pengeluaran')->store('dokumen-perjadins', 'public');
                    DB::table('dokumens')
                        ->where('info_perjadinlangsung_id', $request->info_perjadinlangsung)
                        ->update(['lap_pengeluaran' => $lapPengeluaranPath]);
                }

                // Simpan file lap_perjadin
                if ($request->hasFile('lap_perjadin') && $request->file('lap_perjadin')->isValid()) {
                    $lapPerjadinPath = $request->file('lap_perjadin')->store('dokumen-pejadins', 'public');
                    DB::table('dokumens')
                        ->where('info_perjadinlangsung_id', $request->info_perjadinlangsung)
                        ->update(['lap_perjadin' => $lapPerjadinPath]);
                }

                return redirect()->route('riwayat', ['status' => $request->status_pejadin])->with('success', 'Data Perjalanan dinas telah diperbaharui, tunggu konfirmasi dari pihak keuangan');
            }
        } elseif ($action === 'selesai') {
            DB::table('info_perjadinlangsungs')
                ->where('id', $request->info_perjadinlangsung)
                ->update([
                    'status_pengajuan' => 'pelaporan',
                    'status_pengajuan_detail' => 'verifikasi-2-Keuangan'
                ]);
            return redirect()->route('riwayat', ['status' => $request->status_pejadin])->with('success', 'Perjalanan Dinas Selesai, Silahkan Membuat Laporan');
        }
    }

    public function editPeserta(Request $request, $id)
    {
        DB::table('data_perjadinlangsungs')
            ->where('id', $id)
            ->update(['status_pegawai' => 'PIC']);
        // data_perjadinlangsung::where('id', $id)->update('status_pegawai', '=', 'PIC');

        $perjadin = $request->info_perjadinlangsung;
        return redirect()->route('perjadin_step_2', ['id' => $perjadin])->with('success', 'PIC berhasil dipilih');
    }




    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        //
    }

    public function destroyPeserta(Request $request, $id)
    {
        $id_perjadin = $request->info_perjadinlangsung;
        DB::table('pegawais')
            ->where('id', $request->peserta)
            ->update([
                'is_dinas' => '1'
            ]);

        Data_perjadinlangsung::destroy($id);
        Keuangan_perjadinlangsung::where('data_perjadinlangsungs', $id)->delete();

        return redirect()->route('perjadin_step_2', ['id' => $id_perjadin])->with('success', 'Data peserta berhasil dihapus!');
    }


    public function destroyPesertaDetail(Request $request, $id)
    {
        Data_perjadinlangsung::destroy($id);
        Keuangan_perjadinlangsung::where('data_perjadinlangsungs', $id)->delete();
        $id_perjadin = $request->info_perjadinlangsung;
        return redirect()->route('detail-perjadin', ['id' => $id_perjadin])->with('success', 'Data peserta berhasil dihapus!');
    }

    public function destroyNonPesertaDetail(Request $request, $id)
    {
        Data_perjadinlangsung::destroy($id);
        Keuangan_perjadinlangsung::where('data_perjadinlangsungs', $id)->delete();
        $id_perjadin = $request->info_perjadinlangsung;
        return redirect()->route('detail-perjadin', ['id' => $id_perjadin])->with('success', 'Data peserta berhasil dihapus!');
    }

    public function destroyKebutuhanDetail(Request $request, $id)
    {
        Kebutuhan::destroy($id);
        Keuangan_perjadinlangsung::where('kebutuhan_id', $id)->delete();
        $id_perjadin = $request->info_perjadinlangsung;
        return redirect()->route('detail-perjadin', ['id' => $id_perjadin])->with('success', 'Data fasilitas berhasil dihapus!');
    }

    public function destroyFasilitasPerjadin(Request $request, $id)
    {
        Kebutuhan::destroy($id);
        $id_perjadin = $request->info_perjadinlangsung;
        return redirect()->route('perjadin_step_2', ['id' => $id_perjadin])->with('success', 'Fasilitas berhasil dihapus!');
    }


    public function getDokumen($filename)
    {
        $path = storage_path('app/public/dokumen-perjadins/' . $filename);
        if (!File::exists($path)) {
            abort(404);
        }
        return response()->file($path);
    }
}
