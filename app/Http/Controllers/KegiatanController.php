<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Data_perjadinkegiatan;
use App\Models\Dokumen;
use App\Models\Pegawai;
use App\Models\Non_pegawai;
use App\Models\Fasilitas;
use App\Models\Keuangan_perjadinkegiatan;
use App\Models\Laporan_perjadinkegiatan;
use App\Models\Mobilitas_perjadinkegiatan;
use App\Models\Operasional;
use App\Models\Peminjaman_kendaraan_dinas;
use App\Models\Peminjaman_sarpras;
use App\Models\Perangkat_acara;
use App\Models\Versi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Storage;
use App\Models\Ref_ss_iku_programkerja;

// memanggil function method dari controller ikuapi
use App\Http\Controllers\IKUApiController;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        // Panggil fungsi getData() untuk mendapatkan data kegiatan
        $getData = Ref_ss_iku_programkerja::all();

        return view('user.kegiatan.index', [
            'title' => 'Pengajuan Kegiatan',
            'active' => 'perjadin_kegiatan',
            'ikuresult' => $getData,
        ]);
    }

    public function getData($data)
    {
        $result = [];

        // Loop melalui setiap data dalam respons
        foreach ($data as $item) {
            $nama_program_kerja = $item['nama_program_kerja'];
            $nama_ss = $item['nama_ss'];
            $nama_iku = $item['nama_iku'];
        
            // Tambahkan data yang diambil ke dalam array hasil
            $result[] = [
                'nama_program_kerja' => $nama_program_kerja,
                'nama_ss' => $nama_ss,
                'nama_iku' => $nama_iku,
            ];
        }

        return $result;
    }

    public function kegiatanStep2($id)
    {
        return view('user.kegiatan.kegiatan_step2', [
            'title' => 'Pengajuan Kegiatan',
            'active' => 'perjadin_kegiatan',
            "kegiatan" => Data_perjadinkegiatan::find($id)
        ]);
    }

    public function kegiatanStep3($id)
    {

        $perangkatPegawai = DB::table('perangkat_acaras')
            ->join('pegawais', 'pegawai_id', '=', 'pegawais.id')
            ->join('fasilitas', 'fasilitas_id', '=', 'fasilitas.id')
            ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
            ->select('perangkat_acaras.id as idPerangkat', 'pegawais.nama_lengkap', 'pegawais.golongan', 'pegawais.pangkat', 'perangkat_acaras.status', 'perangkat_acaras.sebagai', 'fasilitas.nama_fasilitas', 'perangkat_acaras.fasilitas_id', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
            ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
            ->get();
        $perangkatNonPegawai = DB::table('perangkat_acaras')
            ->join('non_pegawais', 'non_pegawai_id', '=', 'non_pegawais.id')
            ->join('fasilitas', 'fasilitas_id', '=', 'fasilitas.id')
            ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
            ->select('perangkat_acaras.id as idPerangkat', 'non_pegawais.nama_lengkap', 'non_pegawais.golongan', 'non_pegawais.pangkat', 'perangkat_acaras.status', 'perangkat_acaras.sebagai', 'fasilitas.nama_fasilitas', 'perangkat_acaras.fasilitas_id', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
            ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
            ->get();


        return view('user.kegiatan.kegiatan_step3', [
            'title' => 'Pengajuan Kegiatan',
            'active' => 'perjadin_kegiatan',
            "kegiatan" => Data_perjadinkegiatan::find($id),
            "perangkats" => Fasilitas::where('data_perjadinkegiatan_id', $id)->get(),
            "pegawais" => Pegawai::all(),
            "nonpegawais" => Non_pegawai::all(),
            "perangkatPegawais" => $perangkatPegawai,
            "perangkatNonPegawais" => $perangkatNonPegawai,
        ]);
    }

    public function kegiatanStep4($id)
    {
        $cek = Keuangan_perjadinkegiatan::where('data_perjadinkegiatan', $id)->get();
        if ($cek->isEmpty()) {
            return redirect()->route('kegiatan_step_3', ['id' => $id])->with('success', 'Perangkat orang tidak ditemukan. Anda belum memasukan 1 orangpun, masukkan peserta atau orang untuk kegiatan!');
        }

        $operasionals = DB::table('keuangan_perjadinkegiatans')
            ->join('operasionals', 'keuangan_perjadinkegiatans.operasional', '=', 'operasionals.id')
            ->select('operasionals.id', 'operasionals.status', 'operasionals.nama', 'operasionals.jumlah_frekuensi', 'operasionals.satuan', 'operasionals.detail_satuan', 'keuangan_perjadinkegiatans.operasional', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
            ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
            ->get();

        return view('user.kegiatan.kegiatan_step4', [
            'title' => 'Pengajuan Kegiatan',
            'active' => 'perjadin_kegiatan',
            "kegiatan" => Data_perjadinkegiatan::find($id),
            "operasionals" => $operasionals
        ]);
    }

    public function kegiatanStep5($id)
    {
        return view('user.kegiatan.kegiatan_step5', [
            'title' => 'Pengajuan Kegiatan',
            'active' => 'perjadin_kegiatan',
            "kegiatan" => Data_perjadinkegiatan::find($id),
            "mobilitas" => Mobilitas_perjadinkegiatan::where('data_perjadinkegiatan', $id)->get()
        ]);
    }

    public function kegiatanStep6($id)
    {
        $saprasKegiatan = DB::table('peminjaman_sarpras')
            ->join('assets', 'peminjaman_sarpras.asset', '=', 'assets.id')
            ->select('peminjaman_sarpras.id as idPeminjaman', 'assets.id as IdBarang', 'assets.nama_barang', 'peminjaman_sarpras.jumlah_asset', 'peminjaman_sarpras.tgl_peminjaman', 'peminjaman_sarpras.data_perjadinkegiatan', 'peminjaman_sarpras.status')
            ->where('peminjaman_sarpras.data_perjadinkegiatan', $id)
            ->get();
        return view('user.kegiatan.kegiatan_step6', [
            'title' => 'Pengajuan Kegiatan',
            'active' => 'perjadin_kegiatan',
            "kegiatan" => Data_perjadinkegiatan::find($id),
            "saranas" => Asset::where('status_peminjaman', 'Tidak Dipakai')->get(),
            "sapras" => $saprasKegiatan,
        ]);
    }

    public function kegiatanStep7($id)
    {
        return view('user.kegiatan.kegiatan_step7', [
            'title' => 'Pengajuan Kegiatan',
            'active' => 'perjadin_kegiatan',
            "kegiatan" => Data_perjadinkegiatan::find($id),
            "dokumens" => Laporan_perjadinkegiatan::where('data_perjadin_kegiatan', $id)->get()
        ]);
    }

    public function riwayat($status = 'pengajuan')
    {
        //
        // $kegiatans = Data_perjadinkegiatan::where('status', $status)->get();
        $riwayatKegiatans = DB::table('data_perjadinkegiatans')
            ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
            ->join('perangkat_acaras', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
            ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
            ->select('data_perjadinkegiatans.id as idKegiatan', 'data_perjadinkegiatans.nama_kegiatan', 'data_perjadinkegiatans.jenis_kegiatan', 'data_perjadinkegiatans.tgl_mulai', 'data_perjadinkegiatans.status', 'perangkat_acaras.pegawai_id', 'pegawais.nama_lengkap', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
            ->where('pegawais.id', auth('pegawai')->user()->id)
            ->where('data_perjadinkegiatans.status', $status)
            ->get();
        return view(
            'user.kegiatan.riwayat',
            [
                'title' => 'Kegiatanku',
                'active' => 'kegiatanku_perjadin',
                'status' => $status,
                "kegiatans" => $riwayatKegiatans
            ]
        );
    }

    public function detail($id)
    {
        $perangkatPegawai = DB::table('perangkat_acaras')
            ->join('pegawais', 'pegawai_id', '=', 'pegawais.id')
            ->join('fasilitas', 'fasilitas_id', '=', 'fasilitas.id')
            ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
            ->select('perangkat_acaras.id as idPerangkat', 'pegawais.nama_lengkap', 'pegawais.golongan', 'pegawais.pangkat', 'perangkat_acaras.status', 'perangkat_acaras.sebagai', 'fasilitas.nama_fasilitas', 'perangkat_acaras.fasilitas_id', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
            ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
            ->get();
        $perangkatNonPegawai = DB::table('perangkat_acaras')
            ->join('non_pegawais', 'non_pegawai_id', '=', 'non_pegawais.id')
            ->join('fasilitas', 'fasilitas_id', '=', 'fasilitas.id')
            ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
            ->select('perangkat_acaras.id as idPerangkat', 'non_pegawais.nama_lengkap', 'non_pegawais.golongan', 'non_pegawais.pangkat', 'perangkat_acaras.status', 'perangkat_acaras.sebagai', 'fasilitas.nama_fasilitas', 'perangkat_acaras.fasilitas_id', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
            ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
            ->get();
        $operasionals = DB::table('keuangan_perjadinkegiatans')
            ->join('operasionals', 'keuangan_perjadinkegiatans.operasional', '=', 'operasionals.id')
            ->select('operasionals.id', 'operasionals.status', 'operasionals.nama', 'operasionals.jumlah_frekuensi', 'operasionals.satuan', 'operasionals.detail_satuan', 'keuangan_perjadinkegiatans.operasional', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
            ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
            ->get();
        $saprasKegiatan = DB::table('peminjaman_sarpras')
            ->join('assets', 'peminjaman_sarpras.asset', '=', 'assets.id')
            ->select('peminjaman_sarpras.id as idPeminjaman', 'assets.id as IdBarang', 'assets.nama_barang', 'peminjaman_sarpras.jumlah_asset', 'peminjaman_sarpras.tgl_peminjaman', 'peminjaman_sarpras.data_perjadinkegiatan', 'peminjaman_sarpras.status')
            ->where('peminjaman_sarpras.data_perjadinkegiatan', $id)
            ->get();
        return view('user.kegiatan.detail', [
            'title' => 'Kegiatanku',
            'active' => 'kegiatanku_perjadin',
            'kegiatan' => Data_perjadinkegiatan::find($id),
            "perangkats" => Fasilitas::where('data_perjadinkegiatan_id', $id)->get(),
            "perangkatPegawais" => $perangkatPegawai,
            "perangkatNonPegawais" => $perangkatNonPegawai,
            "pegawais" => Pegawai::all(),
            "nonpegawais" => Non_pegawai::all(),
            "operasionals" => $operasionals,
            "mobilitas" => Mobilitas_perjadinkegiatan::where('data_perjadinkegiatan', $id)->get(),
            "saranas" => Asset::where('status_peminjaman', '=', 'Tidak Dipakai')->get(),
            "sapras" => $saprasKegiatan,
            "dokumens" => Laporan_perjadinkegiatan::where('data_perjadin_kegiatan', $id)->get()
        ]);
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
        //
    }

    public function storeKegiatan(Request $request)
    {
        $validationData = $request->validate([
            'nama_kegiatan' => 'required',
        ]);

        $versi = Versi::where('status', 'aktif')->get();
        DB::table('data_perjadinkegiatans')->insertOrIgnore([
            'uraian' => $request->uraian,
            'program_kerja' => $request->program_kerja,
            'nama_kegiatan' => $request->nama_kegiatan,
            'status' => 'Draf-pengajuan',
            'versi_id' => $versi[0]->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $kegiatan = Data_perjadinkegiatan::max('id'); // mengambil nilai id terakhir yang diinputkan

        return redirect()->route('kegiatan_step_2', ['id' => $kegiatan])->with('success', 'Permohonan kegiatan berhasil dibuat. Silakan isi detail kegiatan!');
    }

    public function storeFasilitas(Request $request)
    {
        db::table('fasilitas')->insertOrIgnore([
            'data_perjadinkegiatan_id' => $request->kegiatan_id,
            'nama_fasilitas' => $request->fasilitas,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('kegiatan_step_3', ['id' => $request->kegiatan_id])->with('success', 'Perangkat orang berhasil ditambahkan. Silakan tambahkan partisipan!');
    }

    public function storeFasilitasDetail(Request $request)
    {
        db::table('fasilitas')->insertOrIgnore([
            'data_perjadinkegiatan_id' => $request->kegiatan_id,
            'nama_fasilitas' => $request->fasilitas,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('detail', ['id' => $request->kegiatan_id])->with('success', 'Perangkat orang berhasil ditambahkan. Silakan tambahkan partisipan!');
    }

    public function storePeserta(Request $request)
    {
        // $kegiatanberlangsung = DB::table('perangkat_acaras')
        //     ->select('')

        db::table('perangkat_acaras')->insertOrIgnore([
            'pegawai_id' => $request->peserta_pegawai,
            'sebagai' => $request->sebagai,
            'status' => 'Menunggu Persetujuan',
            'detail_satuan' => $request->detail_satuan,
            'satuan' => $request->satuan,
            'fasilitas_id' => $request->id_fasilitas,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $maxPerangkat = Perangkat_acara::max('id');

        db::table('keuangan_perjadinkegiatans')->insertOrIgnore([
            'data_perjadinkegiatan' => $request->kegiatanId,
            'perangkat_acara' => $maxPerangkat,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('kegiatan_step_3', ['id' => $request->kegiatanId])->with('success', 'Data berhasil ditambahkan!');
    }

    public function storePesertaDetail(Request $request)
    {
        db::table('perangkat_acaras')->insertOrIgnore([
            'pegawai_id' => $request->peserta_pegawai,
            'sebagai' => $request->sebagai,
            'status' => 'Menunggu Persetujuan',
            'detail_satuan' => $request->detail_satuan,
            'satuan' => $request->satuan,
            'fasilitas_id' => $request->id_fasilitas,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $maxPerangkat = Perangkat_acara::max('id');

        db::table('keuangan_perjadinkegiatans')->insertOrIgnore([
            'data_perjadinkegiatan' => $request->kegiatanId,
            'perangkat_acara' => $maxPerangkat,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('detail', ['id' => $request->kegiatanId])->with('success', 'Data berhasil ditambahkan!');
    }

    public function storeNonPegawai(Request $request)
    {
        $non_pegawai_tersedia = $request->peserta_non_pegawai;
        if ($non_pegawai_tersedia != null) {
            db::table('perangkat_acaras')->insertOrIgnore([
                'non_pegawai_id' => $request->peserta_non_pegawai,
                'sebagai' => $request->sebagai,
                'status' => 'Menunggu Persetujuan',
                'detail_satuan' => $request->detail_satuan,
                'satuan' => $request->satuan,
                'fasilitas_id' => $request->fasilitasIdNonPegawai,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $maxPerangkat = Perangkat_acara::max('id');

            db::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                'data_perjadinkegiatan' => $request->kegiatanId,
                'perangkat_acara' => $maxPerangkat,
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

            db::table('perangkat_acaras')->insertOrIgnore([
                'non_pegawai_id' => $id_non_pegawai_new,
                'sebagai' => $request->sebagai,
                'status' => 'Menunggu Persetujuan',
                'detail_satuan' => $request->detail_satuan,
                'satuan' => $request->satuan,
                'fasilitas_id' => $request->fasilitasIdNonPegawai,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $maxPerangkat = Perangkat_acara::max('id');

            db::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                'data_perjadinkegiatan' => $request->kegiatanId,
                'perangkat_acara' => $maxPerangkat,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('kegiatan_step_3', ['id' => $request->kegiatanId])->with('success', 'Data berhasil ditambahkan!');
    }

    public function storeNonPegawaiDetail(Request $request)
    {
        $non_pegawai_tersedia = $request->peserta_non_pegawai;
        if ($non_pegawai_tersedia != null) {
            db::table('perangkat_acaras')->insertOrIgnore([
                'non_pegawai_id' => $request->peserta_non_pegawai,
                'sebagai' => $request->sebagai,
                'status' => 'Menunggu Persetujuan',
                'detail_satuan' => $request->detail_satuan,
                'satuan' => $request->satuan,
                'fasilitas_id' => $request->fasilitasIdNonPegawai,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $maxPerangkat = Perangkat_acara::max('id');

            db::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                'data_perjadinkegiatan' => $request->kegiatanId,
                'perangkat_acara' => $maxPerangkat,
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

            db::table('perangkat_acaras')->insertOrIgnore([
                'non_pegawai_id' => $id_non_pegawai_new,
                'sebagai' => $request->sebagai,
                'status' => 'Menunggu Persetujuan',
                'detail_satuan' => $request->detail_satuan,
                'satuan' => $request->satuan,
                'fasilitas_id' => $request->fasilitasIdNonPegawai,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $maxPerangkat = Perangkat_acara::max('id');

            db::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                'data_perjadinkegiatan' => $request->kegiatanId,
                'perangkat_acara' => $maxPerangkat,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('detail', ['id' => $request->kegiatanId])->with('success', 'Data berhasil ditambahkan!');
    }

    public function storeOperasional(Request $request)
    {
        db::table('operasionals')->insertOrIgnore([
            'nama' => $request->uraian,
            'jumlah_frekuensi' => $request->frekuensi,
            'detail_satuan' => $request->detail_satuan,
            'satuan' => $request->satuan,
            'ket' => $request->keterangan,
            'status' => 'Belum Disetujui',
            'fasilitas_id' => '0',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $opsMax = Operasional::max('id');

        db::table('keuangan_perjadinkegiatans')->insertOrIgnore([
            'data_perjadinkegiatan' => $request->kegiatanId,
            'operasional' => $opsMax,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('kegiatan_step_4', ['id' => $request->kegiatanId])->with('success', 'Data berhasil ditambahkan!');
    }

    public function storeOperasionalDetail(Request $request)
    {
        db::table('operasionals')->insertOrIgnore([
            'nama' => $request->uraian,
            'jumlah_frekuensi' => $request->frekuensi,
            'detail_satuan' => $request->detail_satuan,
            'satuan' => $request->satuan,
            'ket' => $request->keterangan,
            'status' => 'Belum Disetujui',
            'fasilitas_id' => '0',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $opsMax = Operasional::max('id');

        db::table('keuangan_perjadinkegiatans')->insertOrIgnore([
            'data_perjadinkegiatan' => $request->kegiatanId,
            'operasional' => $opsMax,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('detail', ['id' => $request->kegiatanId])->with('success', 'Data berhasil ditambahkan!');
    }

    public function storeMobilitas(Request $request)
    {
        $versi = Versi::where('status', 'aktif')->get();
        db::table('mobilitas_perjadinkegiatans')->insertOrIgnore([
            'mobilitas' => $request->mobilitas,
            'tujuan_penggunaan' => $request->tujuan,
            'tgl_mulai' => $request->tgl_digunakan,
            'tgl_selesai' => $request->tgl_selesai,
            'unit' => $request->unit,
            'data_perjadinkegiatan' => $request->kegiatanId,
            'status' => 'pengajuan',
            'versi_id' => $versi[0]->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $maxMibilitas = Mobilitas_perjadinkegiatan::max('id');

        db::table('peminjaman_kendaraan_dinas')->insertOrIgnore([
            'mobilitas_perjadinkegiatan' => $maxMibilitas
        ]);

        return redirect()->route('kegiatan_step_5', ['id' => $request->kegiatanId])->with('success', 'Data berhasil ditambahkan!');
    }

    public function storeMobilitasDetail(Request $request)
    {
        $versi = Versi::where('status', 'aktif')->get();
        db::table('mobilitas_perjadinkegiatans')->insertOrIgnore([
            'mobilitas' => $request->mobilitas,
            'tujuan_penggunaan' => $request->tujuan,
            'tgl_mulai' => $request->tgl_digunakan,
            'tgl_selesai' => $request->tgl_selesai,
            'provinsi' => $request->provinsi,
            'kab_kota' => $request->kab_kota,
            'alamat' => $request->alamat,
            'data_perjadinkegiatan' => $request->kegiatanId,
            'status' => 'pengajuan',
            'versi_id' => $versi[0]->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $maxMibilitas = Mobilitas_perjadinkegiatan::max('id');

        db::table('peminjaman_kendaraan_dinas')->insertOrIgnore([
            'mobilitas_perjadinkegiatan' => $maxMibilitas
        ]);

        return redirect()->route('detail', ['id' => $request->kegiatanId])->with('success', 'Data berhasil ditambahkan!');
    }

    public function storeSapras(Request $request)
    {
        $versi = Versi::where('status', 'aktif')->get();
        db::table('peminjaman_sarpras')->insertOrIgnore([
            'jumlah_asset' => $request->jumlah,
            'tgl_peminjaman' => $request->tgl_peminjaman,
            'tgl_pengembalian' => $request->tgl_selesai,
            'data_perjadinkegiatan' => $request->kegiatanId,
            'pegawai_id' => auth('pegawai')->user()->id,
            'asset' => $request->sapras,
            'status' => 'pengajuan',
            'keterangan' => $request->keterangan,
            'versi_id' => $versi[0]->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        db::table('assets')
            ->where('id', $request->sapras)
            ->update([
                'status_peminjaman' => 'pengajuan',
                'updated_at' => now(),
            ]);

        return redirect()->route('kegiatan_step_6', ['id' => $request->kegiatanId])->with('success', 'Data berhasil ditambahkan!');
    }

    public function storeSaprasDetail(Request $request)
    {
        $versi = Versi::where('status', 'aktif')->get();
        db::table('peminjaman_sarpras')->insertOrIgnore([
            'jumlah_asset' => $request->jumlah,
            'tgl_peminjaman' => $request->tgl_peminjaman,
            'tgl_pengembalian' => $request->tgl_selesai,
            'data_perjadinkegiatan' => $request->kegiatanId,
            'pegawai_id' => auth('pegawai')->user()->id,
            'asset' => $request->sapras,
            'status' => 'pengajuan',
            'versi_id' => $versi[0]->id,
            'keterangan' => $request->keterangan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        db::table('assets')
            ->where('id', $request->sapras)
            ->update([
                'status_peminjaman' => 'pengajuan',
                'updated_at' => now(),
            ]);

        return redirect()->route('detail', ['id' => $request->kegiatanId])->with('success', 'Data berhasil ditambahkan!');
    }

    public function storeDokumen(Request $request)
    {
        $validationData = $request->validate([
            'file' => 'required|mimes:pdf|file|max:2048',
        ]);
        
        db::table('laporan_perjadinkegiatans')->insertOrIgnore([
            'nama_dokumen' => $request->nama_dokumen,
            'file' => $validationData['file'] = $request->file('file')->store('dokumen-kegiatans', 'public'),
            'data_perjadin_kegiatan' => $request->kegiatanId,
            'status' => 'diajukan',
            'created_at' => now(),
            'updated_at' => now(),
            
        ]);

        return redirect()->route('kegiatan_step_7', ['id' => $request->kegiatanId])->with('success', 'Data berhasil ditambahkan!');
    }

    public function storeDokumenDetail(Request $request)
    {
        $validationData = $request->validate([
            'file' => 'required|mimes:pdf|file|max:2048',
        ]);

        db::table('laporan_perjadinkegiatans')->insertOrIgnore([
            'nama_dokumen' => $request->nama_dokumen,
            'file' => $validationData['file'] = $request->file('file')->store('dokumen-kegiatans', 'public'),
            'data_perjadin_kegiatan' => $request->kegiatanId,
            'status' => 'diajukan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('detail', ['id' => $request->kegiatanId])->with('success', 'Data berhasil ditambahkan!');
    }

    public function storeKegiatanAll(Request $request, $id)
    {
        $cekDokumen = Laporan_perjadinkegiatan::where('data_perjadin_kegiatan', $id)->get();
        if ($cekDokumen == null) {
            return redirect()->route('kegiatan_step_7', ['id' => $request->kegiatanId])->with('success', 'Dokumen pendukung belum dimasukkan. Masukkan surat tugas atau 1 dokumen pendukung lainnya!');
        }

        db::table('data_perjadinkegiatans')
            ->where('id', $id)
            ->update([
                'is_acceptKeu' => 'verifikasi-1', 
                'status' => 'pengajuan',
                'updated_at' => now(),
            ]);

        return redirect()->route('riwayat-kegiatan', ['status' => 'pengajuan'])->with('success', 'Program berhasil dibuat. Silakan tunggu persetujuan dari Keuangan!');
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

    public function updateKegiatan(Request $request, $id)
    {
        DB::table('data_perjadinkegiatans')
            ->where('id', $id)
            ->update([
                'jenis_kegiatan' => $request->jenis_kegiatan,
                'jumlah_peserta' => $request->jumlah_peserta,
                'tgl_mulai' => $request->tgl_mulai,
                'tgl_selesai' => $request->tgl_selesai,
                'provinsi' => $request->provinsi,
                'kab_kota' => $request->kab_kota,
                'alamat' => $request->alamat,
                'updated_at' => now(),
            ]);

        return redirect()->route('kegiatan_step_3', ['id' => $id])->with('success', 'Permohonan kegiatan berhasil dibuat. Silakan isi informasi partisipan!');
    }

    public function updateKegiatanDetail(Request $request, $id)
    {

        if ($request->statusKegiatan == 'Draf-pengajuan') {
            DB::table('data_perjadinkegiatans')
                ->where('id', $id)
                ->update([
                    'status' => 'pengajuan',
                    'status' => 'verifikasi-1',
                    'updated_at' => now(),
                ]);
    
            return redirect()->route('riwayat-kegiatan', ['status' => 'pengajuan'])->with('success', 'Program telah diperbaharui!, Silahkan tunggu persetujuan dari keuangan!');
        }
        
        if ($request->statusKegiatan == 'proses') {
            DB::table('data_perjadinkegiatans')
                ->where('id', $id)
                ->update([
                    'status' => 'proses',
                    'updated_at' => now(),
                ]);
    
            return redirect()->route('riwayat-kegiatan', ['status' => 'proses'])->with('success', 'Program telah diperbaharui!, Silahkan tunggu persetujuan dari keuangan!');
        }
        
        if (($request->statusKegiatan == 'revisi') AND ($request->statusKegiatanKeuangan == 'revisi-1')) {
            DB::table('data_perjadinkegiatans')
                ->where('id', $id)
                ->update([
                    'status' => 'pengajuan',
                    'is_acceptKeu' => 'verifikasi-1',
                    'updated_at' => now(),
                ]);
    
            return redirect()->route('riwayat-kegiatan', ['status' => 'pengajuan'])->with('success', 'Program telah diperbaharui!, Silahkan tunggu persetujuan dari keuangan!');
        }
        
        if (($request->statusKegiatan == 'revisi') AND ($request->statusKegiatanKeuangan == 'revisi-2')) {
            DB::table('data_perjadinkegiatans')
                ->where('id', $id)
                ->update([
                    'status' => 'proses',
                    'is_acceptKeu' => 'verifikasi-2',
                    'updated_at' => now(),
                ]);
    
            return redirect()->route('riwayat-kegiatan', ['status' => 'proses'])->with('success', 'Program telah diperbaharui!, Silahkan tunggu persetujuan dari keuangan!');
        }

        DB::table('data_perjadinkegiatans')
        ->where('id', $id)
        ->update([
            'status' => 'pengajuan',
            'updated_at' => now(),
        ]);

        return redirect()->route('riwayat-kegiatan', ['status' => 'pengajuan'])->with('success', 'Program telah diperbaharui!, Silahkan tunggu persetujuan dari keuangan!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function destroyPesertaKegiatan(Request $request, $id)
    {
        // Perangkat_acara::where('id', $id)->delete();
        Keuangan_perjadinkegiatan::where('perangkat_acara', $id)->delete();
        Perangkat_acara::destroy($id);
        return redirect()->route('kegiatan_step_3', ['id' => $request->kegiatanId])->with('success', 'Data Telah Dihapus!');
    }

    public function destroyPesertaKegiatanDetail(Request $request, $id)
    {
        // Perangkat_acara::destroy($id);
        perangkat_acara::where('id', '=', $id)->delete();
        Keuangan_perjadinkegiatan::where('perangkat_acara', '=', $id)->delete();
        return redirect()->route('detail', ['id' => $request->kegiatanId])->with('success', 'Data berhasil dihapus!');
    }

    public function destroyOperasionalKegiatan(Request $request, $id)
    {
        Keuangan_perjadinkegiatan::where('operasional', $id)->delete();
        Operasional::destroy($id);
        return redirect()->route('kegiatan_step_4', ['id' => $request->kegiatanId])->with('success', 'Data berhasil dihapus!');
    }

    public function destroyOperasionalKegiatanDetail(Request $request, $id)
    {
        Keuangan_perjadinkegiatan::where('operasional', $id)->delete();
        Operasional::destroy($id);
        return redirect()->route('detail', ['id' => $request->kegiatanId])->with('success', 'Data berhasil dihapus!');
    }

    public function destroyMobiltasKegiatan(Request $request, $id)
    {
        Mobilitas_perjadinkegiatan::destroy($id);
        Peminjaman_kendaraan_dinas::where('mobilitas_perjadinkegiatan', $id)->delete();
        $kegiatanId = $request->kegiatanId;
        return redirect()->route('kegiatan_step_5', ['id' => $kegiatanId])->with('success', 'Mobilitas berhasil dihapus!');
    }

    public function destroyMobiltasKegiatanDetail(Request $request, $id)
    {
        Mobilitas_perjadinkegiatan::destroy($id);
        Peminjaman_kendaraan_dinas::where('mobilitas_perjadinkegiatan', $id)->delete();
        $kegiatanId = $request->kegiatanId;
        return redirect()->route('detail', ['id' => $kegiatanId])->with('success', 'Mobilitas berhasil dihapus!');
    }

    public function destroySaprasKegiatan(Request $request, $id)
    {
        Peminjaman_sarpras::destroy($id);
        $kegiatanId = $request->kegiatanId;

        db::table('assets')
            ->where('id', $request->idAsset)
            ->update([
                'status_peminjaman' => 'Tidak Dipakai',
                'updated_at' => now(),
            ]);
        return redirect()->route('kegiatan_step_6', ['id' => $kegiatanId])->with('success', 'Sapras berhasil dihapus!');
    }

    public function destroySaprasKegiatanDetail(Request $request, $id)
    {
        Peminjaman_sarpras::destroy($id);
        $kegiatanId = $request->kegiatanId;
        db::table('assets')
            ->where('id', $request->idAsset)
            ->update([
                'status_peminjaman' => 'Tidak Dipakai',
                'updated_at' => now(),
            ]);
        return redirect()->route('detail', ['id' => $kegiatanId])->with('success', 'Sapras berhasil dihapus!');
    }

    public function destroyDokumenKegiatan(Request $request, $id)
    {
        $data = Laporan_perjadinkegiatan::find($id);
        Storage::delete($data->file);
        $data->delete();
        $kegiatanId = $request->kegiatanId;
        return redirect()->route('kegiatan_step_7', ['id' => $kegiatanId])->with('success', 'Dokumen berhasil dihapus!');
    }

    public function destroyDokumenKegiatanDetail(Request $request, $id)
    {
        $data = Laporan_perjadinkegiatan::find($id);
        Storage::delete($data->file);
        $data->delete();
        $kegiatanId = $request->kegiatanId;
        return redirect()->route('detail', ['id' => $kegiatanId])->with('success', 'Dokumen berhasil dihapus!');
    }

    // public function getDokumen($filename){
    //     $path = storage_path('app/public/dokumen-kegiatans/' . $filename);
    //     return response()->file($path);
    // }

    public function getDokumen($filename){
        $path = storage_path('app/public/dokumen-kegiatans/' . $filename);
        if (!file_exists($path)) {
            abort(404);
        }
        $file = File::get($path);

        //     return response()->file($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
    }
    
    
    
}
