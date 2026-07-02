<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Data_perjadinkegiatan;
use App\Models\Dokumen;
use App\Models\Pegawai;
use App\Models\Non_pegawai;
use App\Models\Fasilitas;
use App\Models\Keuangan_perjadinkegiatan;
use App\Models\Kebutuhan;
use App\Models\Laporan_perjadinkegiatan;
use App\Models\Mobilitas_perjadinkegiatan;
use App\Models\Operasional;
use App\Models\Peminjaman_kendaraan_dinas;
use App\Models\Peminjaman_sarpras;
use App\Models\Perangkat_acara;
use App\Models\Versi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Storage;
use App\Models\Ref_ss_iku_programkerja;
use Carbon\Carbon;

use setasign\Fpdi\Fpdi;

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

        $jenisProgram = DB::table('jenis_program')
            ->where('status_program','aktif')
            ->get();

        return view('user.kegiatan.index', [
            'title' => 'Pengajuan Kegiatan',
            'jenisPrograms' => $jenisProgram,
            'active' => 'perjadin_kegiatan',
            'ikuresult' => $getData,
        ]);
    }
    public function index2($id)
    {

        // Panggil fungsi getData() untuk mendapatkan data kegiatan
        $getData = Ref_ss_iku_programkerja::all();

        $kode_iku = DB::table('data_perjadinkegiatans')
        ->select('data_perjadinkegiatans.id_iku')
        ->where('data_perjadinkegiatans.id', $id)
        ->first();

        DB::table('data_perjadinkegiatans')
        ->where('id',$id)
        ->update([
            'status' => 'Draf-pengajuan',
            'status_pengajuan_detail' => 'Pengeditan-Step1',
        ]);

        // Pastikan $kode_iku tidak null sebelum menggunakannya
        if ($kode_iku) {
            $id_iku_string = (string) $kode_iku->id_iku;

            // Mengambil nama_iku berdasarkan id_iku
            $indikator = DB::table('data_perjadinkegiatans')
                ->join('ref_ss_iku_programkerjas', 'data_perjadinkegiatans.id_iku', '=', 'ref_ss_iku_programkerjas.kode_iku')
                ->where('data_perjadinkegiatans.id_iku', $id_iku_string)
                ->select('ref_ss_iku_programkerjas.nama_iku')
                ->limit(1)
                ->first(); // Ambil satu hasil pertama

            
        }


        return view('user.kegiatan.indexEdit', [
            'title' => 'Pengajuan Kegiatan',
            'active' => 'perjadin_kegiatan',
            'indikator' => $indikator,
            'ikuresult' => $getData,
            "kegiatan" => Data_perjadinkegiatan::find($id)
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

    public function KegiatanPenugasanStep2($id)
    {


        $saprasKegiatan = DB::table('peminjaman_sarpras')
        ->join('assets', 'peminjaman_sarpras.asset', '=', 'assets.id')
        ->select('peminjaman_sarpras.id as idPeminjaman', 'assets.id as IdBarang', 'assets.nama_barang', 'peminjaman_sarpras.jumlah_asset', 'peminjaman_sarpras.tgl_peminjaman', 'peminjaman_sarpras.data_perjadinkegiatan', 'peminjaman_sarpras.status')
        ->where('peminjaman_sarpras.data_perjadinkegiatan', $id)
        ->get();

        $pesertaExists = DB::table('data_perjadinkegiatans')
                ->where('id', $id)
                ->selectRaw('CASE WHEN jumlah_peserta IS NULL THEN FALSE ELSE TRUE END AS jumlah_peserta_status')
                ->value('jumlah_peserta_status');



        $mobilitasExists = DB::table('mobilitas_perjadinkegiatans')
            ->where('data_perjadinkegiatan', $id)
            ->first();


        $kebutuhans = DB::table('kebutuhans')
                ->join('keuangan_perjadinkegiatans', 'kebutuhans.id', '=', 'keuangan_perjadinkegiatans.kebutuhan_id')
                ->join('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
                ->select(
                    'kebutuhans.id as idKebutuhan',
                    'kebutuhans.nama',
                    'kebutuhans.jumlah_frekuensi',
                    'kebutuhans.satuan',
                    'kebutuhans.tipe_pendanaan',
                    'kebutuhans.ket',
                    'kebutuhans.status',
                    'keuangan_perjadinkegiatans.kebutuhan_id as idKeuangan',
                    'keuangan_perjadinkegiatans.data_perjadinkegiatan',
                    'keuangan_perjadinkegiatans.akun_x_rkakl',
                )
                ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
                ->get();

        $kegiatan = DB::table('data_perjadinkegiatans')
            ->where('id', $id)
            ->first();

        if ($kegiatan && $kegiatan->tgl_mulai && $kegiatan->tgl_selesai) {
            // Mengubah tgl_mulai dan tgl_selesai menjadi objek Carbon
            $tglMulai = Carbon::parse($kegiatan->tgl_mulai);
            $tglSelesai = Carbon::parse($kegiatan->tgl_selesai);

            // Menghitung selisih hari
            $jumlahHari = $tglMulai->diffInDays($tglSelesai) + 1;
        } else {
            $jumlahHari = null; // atau nilai default lain jika salah satu tanggal tidak ada
        }

        // --------------------------------------------------------------------------
        // Menggunakan metode find untuk mengambil data berdasarkan ID
        $nKepanitiaan = DB::table('keuangan_perjadinkegiatans')
            ->join('perangkat_acaras', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
            ->join('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
            ->where('data_perjadinkegiatans.id', $id)
            ->count();

        $infoKegiatan = Data_perjadinkegiatan::find($id);

        if ($infoKegiatan) {
            $tanggalAwal = Carbon::parse($infoKegiatan->tgl_mulai);
            $tanggalAkhir = Carbon::parse($infoKegiatan->tgl_selesai);
        } else {
            // Penanganan jika tidak ada record yang ditemukan
            $tanggalAwal = null;
            $tanggalAkhir = null;
        }

        $selectPeserta = DB::table('perangkat_acaras AS pa1')
            ->join('pegawais', 'pa1.pegawai_id', '=', 'pegawais.id')
            ->join('data_perjadinkegiatans AS dp', 'pa1.data_perjadin_kegiatan', '=', 'dp.id')
            ->select(
                'pa1.id',
                'pa1.pegawai_id',
                'pa1.sebagai AS status_pegawai',
                'pegawais.nama_lengkap',
                'pegawais.pangkat',
                'pegawais.golongan'
            )
            ->where('pa1.data_perjadin_kegiatan', $id)
            ->get();


        $selectPeserta_nonPegawai = DB::table('perangkat_acaras')
            ->join('non_pegawais', 'non_pegawai_id', '=', 'non_pegawais.id')
            ->select('perangkat_acaras.id', 'perangkat_acaras.sebagai AS status_pegawai', 'non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan')
            ->where('perangkat_acaras.data_perjadin_kegiatan', $id)
            ->get();

        $kebutuhans = DB::table('keuangan_perjadinkegiatans')
            ->join('kebutuhans', 'keuangan_perjadinkegiatans.kebutuhan_id', '=', 'kebutuhans.id')
            ->select('kebutuhans.id as idKebutuhan', 'kebutuhans.nama', 'kebutuhans.jumlah_frekuensi', 'kebutuhans.satuan', 'kebutuhans.tipe_pendanaan', 'kebutuhans.ket', 'keuangan_perjadinkegiatans.data_perjadinkegiatan',  'keuangan_perjadinkegiatans.kebutuhan_id', 'keuangan_perjadinkegiatans.perangkat_acara', 'keuangan_perjadinkegiatans.kebutuhan_id', 'keuangan_perjadinkegiatans.status')
            ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
            ->groupBy('kebutuhans.id', 'kebutuhans.nama', 'kebutuhans.jumlah_frekuensi', 'kebutuhans.satuan', 'kebutuhans.tipe_pendanaan', 'kebutuhans.ket', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', 'keuangan_perjadinkegiatans.kebutuhan_id', 'keuangan_perjadinkegiatans.perangkat_acara', 'keuangan_perjadinkegiatans.status')
            ->get();

            $pegawais = DB::table('pegawais')
            ->select('pegawais.id', 'pegawais.nama_lengkap')
            ->whereNotExists(function ($query) use ($id, $tanggalAwal, $tanggalAkhir) {
                $query->select(DB::raw(1))
                    ->from('perangkat_acaras')
                    ->whereRaw('pegawais.id = perangkat_acaras.pegawai_id')
                    ->where('perangkat_acaras.status', '!=', 'Ditolak')
                    ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                        $subquery->whereBetween('perangkat_acaras.tgl_mulai', [$tanggalAwal, $tanggalAkhir])
                            ->orWhereBetween('perangkat_acaras.tgl_selesai', [$tanggalAwal, $tanggalAkhir])
                            ->orWhere(function ($subquery2) use ($tanggalAwal, $tanggalAkhir) {
                                $subquery2->where('perangkat_acaras.tgl_mulai', '<=', $tanggalAwal)
                                          ->where('perangkat_acaras.tgl_selesai', '>=', $tanggalAkhir);
                            });
                    });
                    $query->orWhereExists(function ($query) use ($tanggalAwal, $tanggalAkhir) {
                        $query->select(DB::raw(1))
                            ->from('data_perjadinlangsungs')
                            ->whereRaw('pegawais.id = data_perjadinlangsungs.pegawai_id')
                            ->where('data_perjadinlangsungs.status_persetujuan', '!=', 'Ditolak')
                            ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                                $subquery->whereBetween('data_perjadinlangsungs.tgl_keberangkatan', [$tanggalAwal, $tanggalAkhir])
                                    ->orWhereBetween('data_perjadinlangsungs.tgl_selesai', [$tanggalAwal, $tanggalAkhir])
                                    ->orWhere(function ($subquery2) use ($tanggalAwal, $tanggalAkhir) {
                                        $subquery2->where('data_perjadinlangsungs.tgl_keberangkatan', '<=', $tanggalAwal)
                                            ->where('data_perjadinlangsungs.tgl_selesai', '>=', $tanggalAkhir);
                                    });
                            });
                    });
            })
            ->where('pegawais.jabatan_id', '!=', 14)
            ->distinct()
            ->get();


        $nonPegawais = DB::table('non_pegawais')
            ->select('non_pegawais.id', 'non_pegawais.nama_lengkap')
            ->whereNotExists(function ($query) use ($id, $tanggalAwal, $tanggalAkhir) {
                $query->select(DB::raw(1))
                    ->from('perangkat_acaras')
                    ->whereRaw('non_pegawais.id = perangkat_acaras.non_pegawai_id')
                    ->where('perangkat_acaras.status', '!=', 'Ditolak')
                    ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                        // Pengecekan apakah tanggal kegiatan tumpang tindih
                        $subquery->whereBetween('perangkat_acaras.tgl_mulai', [$tanggalAwal, $tanggalAkhir])
                            ->orWhereBetween('perangkat_acaras.tgl_selesai', [$tanggalAwal, $tanggalAkhir])
                            ->orWhere(function ($subquery2) use ($tanggalAwal, $tanggalAkhir) {
                                $subquery2->where('perangkat_acaras.tgl_mulai', '<=', $tanggalAwal)
                                          ->where('perangkat_acaras.tgl_selesai', '>=', $tanggalAkhir);
                            });
                    });
                    $query->orWhereExists(function ($query) use ($tanggalAwal, $tanggalAkhir) {
                        $query->select(DB::raw(1))
                            ->from('data_perjadinlangsungs')
                            ->whereRaw('non_pegawais.id = data_perjadinlangsungs.non_pegawai_id')
                            ->where('data_perjadinlangsungs.status_persetujuan', '!=', 'Ditolak')
                            ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                                $subquery->whereBetween('data_perjadinlangsungs.tgl_keberangkatan', [$tanggalAwal, $tanggalAkhir])
                                    ->orWhereBetween('data_perjadinlangsungs.tgl_selesai', [$tanggalAwal, $tanggalAkhir])
                                    ->orWhere(function ($subquery2) use ($tanggalAwal, $tanggalAkhir) {
                                        $subquery2->where('data_perjadinlangsungs.tgl_keberangkatan', '<=', $tanggalAwal)
                                            ->where('data_perjadinlangsungs.tgl_selesai', '>=', $tanggalAkhir);
                                    });
                            });
                    });
            })
            ->where('non_pegawais.id', '!=', 14) // Menyaring hanya non-pegawai dengan ID valid
            ->distinct()
            ->get();

            $data_bank = DB::table('ref_bank')
            ->get();



        return view(
            'user.kegiatan.kegiatan_penugasan_step2',
            [
                'title' => 'Pengajuan Kegiatan',
                'active' => 'perjadin_kegiatan',
                'mobilitasExists' => $mobilitasExists,
                "pegawais" =>  $pegawais,
                'data_bank' => $data_bank,
                "nonpegawais" => $nonPegawais,
                "jumlahHari" =>  $jumlahHari,
                "nonpegawais" => Non_pegawai::all(),
                'jumlah_kepanitiaan' => $nKepanitiaan,
                "dokumens" => Laporan_perjadinkegiatan::where('data_perjadin_kegiatan', $id)
                ->whereNotNull('file')->get(),
                "kegiatan" => Data_perjadinkegiatan::find($id),
                "mobilitas" => Mobilitas_perjadinkegiatan::where('data_perjadinkegiatan', $id)->get(),
                "selectPesertas" => $selectPeserta,
                "selectPesertasNonPegawais" => $selectPeserta_nonPegawai,
                "fasilitas" => $kebutuhans
            ]
        );
    }



    public function kegiatanStep2($id)
    {
        $infoKegiatan = Data_perjadinkegiatan::find($id);
        if ($infoKegiatan) {
            $tanggalAwal = Carbon::parse($infoKegiatan->tgl_mulai);
            $tanggalAkhir = Carbon::parse($infoKegiatan->tgl_selesai);

        } else {
            $tanggalAwal = null;
            $tanggalAkhir = null;
        }

        $response = $this->getKegiatanData($id);

        // Jika ingin mengakses jumlah_peserta dari JSON response:
        $cekJumlahPeserta = $response->getData()->jumlah_peserta;
        $maksPanitia = $response->getData()->maks_panitia;

        $nKepanitiaan = DB::table('keuangan_perjadinkegiatans')
            ->join('perangkat_acaras', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
            ->join('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
            ->where('data_perjadinkegiatans.id', $id)
            ->where('keuangan_perjadinkegiatans.kode', 'honor')
            ->count();

        $perangkatPegawai = DB::table('perangkat_acaras')
            ->join('pegawais', 'pegawai_id', '=', 'pegawais.id')
            ->join('fasilitas', 'fasilitas_id', '=', 'fasilitas.id')
            ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
            ->select('perangkat_acaras.id as idPerangkat', 'pegawais.nama_lengkap', 'pegawais.golongan', 'pegawais.pangkat', 'perangkat_acaras.status', 'perangkat_acaras.posisi', 'perangkat_acaras.sebagai', 'fasilitas.nama_fasilitas', 'perangkat_acaras.fasilitas_id', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
            ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
            ->distinct()
            ->get();

        $perangkatNonPegawai = DB::table('perangkat_acaras')
            ->join('non_pegawais', 'non_pegawai_id', '=', 'non_pegawais.id')
            ->join('fasilitas', 'fasilitas_id', '=', 'fasilitas.id')
            ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
            ->select('perangkat_acaras.id as idPerangkat', 'non_pegawais.nama_lengkap', 'non_pegawais.golongan', 'non_pegawais.pangkat', 'perangkat_acaras.status','perangkat_acaras.posisi', 'perangkat_acaras.sebagai', 'fasilitas.nama_fasilitas', 'perangkat_acaras.fasilitas_id', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
            ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
            ->distinct()
            ->get();

        $saprasKegiatan = DB::table('peminjaman_sarpras')
        ->join('assets', 'peminjaman_sarpras.asset', '=', 'assets.id')
        ->select('peminjaman_sarpras.id as idPeminjaman', 'assets.id as IdBarang', 'assets.nama_barang', 'peminjaman_sarpras.jumlah_asset', 'peminjaman_sarpras.tgl_peminjaman', 'peminjaman_sarpras.data_perjadinkegiatan', 'peminjaman_sarpras.status')
        ->where('peminjaman_sarpras.data_perjadinkegiatan', $id)
        ->get();

        $pesertaExists = DB::table('data_perjadinkegiatans')
                ->where('id', $id)
                ->selectRaw('CASE WHEN jumlah_peserta IS NULL THEN FALSE ELSE TRUE END AS jumlah_peserta_status')
                ->value('jumlah_peserta_status');

        $kamarExists = DB::table('data_perjadinkegiatans')
                ->where('id', $id)
                ->selectRaw('CASE WHEN jumlah_kamar IS NULL THEN FALSE ELSE TRUE END AS jumlah_kamar_status')
                ->value('jumlah_kamar_status');

        $mobilitasExists = DB::table('mobilitas_perjadinkegiatans')
            ->where('data_perjadinkegiatan', $id)
            ->first();

        $tambahPenginapanExists = DB::table('data_perjadinkegiatans')
                ->where('id', $id)
                ->selectRaw('CASE WHEN tambah_penginapan IS NULL THEN FALSE ELSE TRUE END AS tambah_penginapan_status')
                ->value('tambah_penginapan_status');

        $kebutuhans = DB::table('kebutuhans')
                ->join('keuangan_perjadinkegiatans', 'kebutuhans.id', '=', 'keuangan_perjadinkegiatans.kebutuhan_id')
                ->join('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
                ->leftJoin('perangkat_acaras', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
                ->leftJoin('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
                ->leftJoin('non_pegawais', 'perangkat_acaras.non_pegawai_id', '=', 'non_pegawais.id')
                ->select(
                    'kebutuhans.id as idKebutuhan',
                    'kebutuhans.nama',
                    'kebutuhans.jumlah_frekuensi',
                    'kebutuhans.satuan',
                    'kebutuhans.tipe_pendanaan',
                    'kebutuhans.ket',
                    'kebutuhans.status',
                    'keuangan_perjadinkegiatans.kebutuhan_id as idKeuangan',
                    'keuangan_perjadinkegiatans.data_perjadinkegiatan',
                    'keuangan_perjadinkegiatans.akun_x_rkakl',
                    DB::raw('COALESCE(pegawais.nama_lengkap, non_pegawais.nama_lengkap, "Tanpa Terikat Pelaksana") as pelaksana')
                )
                ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
                ->get();

            $pegawais = DB::table('pegawais')
                ->select('pegawais.id', 'pegawais.nama_lengkap')
                ->whereNotExists(function ($query) use ($id, $tanggalAwal, $tanggalAkhir) {
                    $query->select(DB::raw(1))
                        ->from('perangkat_acaras')
                        ->whereRaw('pegawais.id = perangkat_acaras.pegawai_id')
                        ->where('perangkat_acaras.status', '!=', 'Ditolak')
                        ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                            $subquery->whereBetween('perangkat_acaras.tgl_mulai', [$tanggalAwal, $tanggalAkhir])
                                ->orWhereBetween('perangkat_acaras.tgl_selesai', [$tanggalAwal, $tanggalAkhir])
                                ->orWhere(function ($subquery2) use ($tanggalAwal, $tanggalAkhir) {
                                    $subquery2->where('perangkat_acaras.tgl_mulai', '<=', $tanggalAwal)
                                              ->where('perangkat_acaras.tgl_selesai', '>=', $tanggalAkhir);
                                });
                        });
                        $query->orWhereExists(function ($query) use ($tanggalAwal, $tanggalAkhir) {
                            $query->select(DB::raw(1))
                                ->from('data_perjadinlangsungs')
                                ->whereRaw('pegawais.id = data_perjadinlangsungs.pegawai_id')
                                ->where('data_perjadinlangsungs.status_persetujuan', '!=', 'Ditolak')
                                ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                                    $subquery->whereBetween('data_perjadinlangsungs.tgl_keberangkatan', [$tanggalAwal, $tanggalAkhir])
                                        ->orWhereBetween('data_perjadinlangsungs.tgl_selesai', [$tanggalAwal, $tanggalAkhir])
                                        ->orWhere(function ($subquery2) use ($tanggalAwal, $tanggalAkhir) {
                                            $subquery2->where('data_perjadinlangsungs.tgl_keberangkatan', '<=', $tanggalAwal)
                                                ->where('data_perjadinlangsungs.tgl_selesai', '>=', $tanggalAkhir);
                                        });
                                });
                        });
                })
                ->where('pegawais.jabatan_id', '!=', 14)
                ->distinct()
                ->get();


            $nonPegawais = DB::table('non_pegawais')
                ->select('non_pegawais.id', 'non_pegawais.nama_lengkap')
                ->whereNotExists(function ($query) use ($id, $tanggalAwal, $tanggalAkhir) {
                    $query->select(DB::raw(1))
                        ->from('perangkat_acaras')
                        ->whereRaw('non_pegawais.id = perangkat_acaras.non_pegawai_id')
                        ->where('perangkat_acaras.status', '!=', 'Ditolak')
                        ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                            // Pengecekan apakah tanggal kegiatan tumpang tindih
                            $subquery->whereBetween('perangkat_acaras.tgl_mulai', [$tanggalAwal, $tanggalAkhir])
                                ->orWhereBetween('perangkat_acaras.tgl_selesai', [$tanggalAwal, $tanggalAkhir])
                                ->orWhere(function ($subquery2) use ($tanggalAwal, $tanggalAkhir) {
                                    $subquery2->where('perangkat_acaras.tgl_mulai', '<=', $tanggalAwal)
                                              ->where('perangkat_acaras.tgl_selesai', '>=', $tanggalAkhir);
                                });
                        });
                        $query->orWhereExists(function ($query) use ($tanggalAwal, $tanggalAkhir) {
                            $query->select(DB::raw(1))
                                ->from('data_perjadinlangsungs')
                                ->whereRaw('non_pegawais.id = data_perjadinlangsungs.non_pegawai_id')
                                ->where('data_perjadinlangsungs.status_persetujuan', '!=', 'Ditolak')
                                ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                                    $subquery->whereBetween('data_perjadinlangsungs.tgl_keberangkatan', [$tanggalAwal, $tanggalAkhir])
                                        ->orWhereBetween('data_perjadinlangsungs.tgl_selesai', [$tanggalAwal, $tanggalAkhir])
                                        ->orWhere(function ($subquery2) use ($tanggalAwal, $tanggalAkhir) {
                                            $subquery2->where('data_perjadinlangsungs.tgl_keberangkatan', '<=', $tanggalAwal)
                                                ->where('data_perjadinlangsungs.tgl_selesai', '>=', $tanggalAkhir);
                                        });
                                });
                        });
                })
                ->where('non_pegawais.id', '!=', 14) // Menyaring hanya non-pegawai dengan ID valid
                ->distinct()
                ->get();

        $panitias = $perangkatPegawai->filter(fn($item) => $item->sebagai == 'Panitia');
        $narasumbers = $perangkatPegawai->filter(fn($item) => $item->sebagai == 'Narasumber');
        $moderators = $perangkatPegawai->filter(fn($item) => $item->sebagai == 'Moderator');

        $kegiatan = DB::table('data_perjadinkegiatans')
        ->where('id', $id)
        ->first();

        $data_bank = DB::table('ref_bank')
        ->get();

        if ($kegiatan && $kegiatan->tgl_mulai && $kegiatan->tgl_selesai) {
            // Mengubah tgl_mulai dan tgl_selesai menjadi objek Carbon
            $tglMulai = Carbon::parse($kegiatan->tgl_mulai);
            $tglSelesai = Carbon::parse($kegiatan->tgl_selesai);

            // Menghitung selisih hari
            $jumlahHari = $tglMulai->diffInDays($tglSelesai) + 1;
        } else {
            $jumlahHari = null; // atau nilai default lain jika salah satu tanggal tidak ada
        }


        return view('user.kegiatan.kegiatan_step2', [
            'cekJumlahPeserta' => $cekJumlahPeserta,
            'maksPanitia' => $maksPanitia,
            'pesertaExists' => $pesertaExists,
            'kamarExists' => $kamarExists,
            'data_bank' => $data_bank,
            'tambahPenginapanExists' => $tambahPenginapanExists,
            "perangkats" => Fasilitas::where('data_perjadinkegiatan_id', $id)->get(),
            "pegawais" => Pegawai::all(),
            'mobilitasExists' => $mobilitasExists,
            "nonpegawais" => Non_pegawai::all(),
            "perangkatPegawais" => $perangkatPegawai,
            "perangkatNonPegawais" => $perangkatNonPegawai,
            "jumlahHari" => $jumlahHari,
            'title' => 'Pengajuan Kegiatan',
            'active' => 'perjadin_kegiatan',
            "kegiatan" => $kegiatan,
            "panitias" => $panitias,
            "pegawais" => $pegawais,
            "nonpegawais" => $nonPegawais,
            "narasumbers" => $narasumbers,
            "moderators" => $moderators,
            'sapras' => $saprasKegiatan,
            'jumlah_kepanitiaan' => $nKepanitiaan,
            "saranas" => Asset::where('status_peminjaman', 'Tidak Dipakai')->get(),
            "mobilitas" => Mobilitas_perjadinkegiatan::where('data_perjadinkegiatan', $id)->get(),
            "dokumens" => Laporan_perjadinkegiatan::where('data_perjadin_kegiatan', $id)
            ->whereNotNull('file')->get(),
            "kegiatan" => Data_perjadinkegiatan::find($id),
            "kebutuhans" => $kebutuhans,
            'ref_fasilitas' => DB::table('ref_fasilitas')->where('status','aktif')->where('terikat_pelaksana',0)->get(),
            'ref_fasilitas_pelaksana' => DB::table('ref_fasilitas')->where('status','aktif')->where('terikat_pelaksana',1)->get(),
        ]);
    }

    public function storeBatasPanitia(Request $request, $id)
    {
        $peserta = $request->jumlah_peserta;
        $hari = $request->tambah_penginapan;
        $kamar = $request->jumlah_kamar;

        $maksPanitia = round($peserta * 0.10);


        $jumlahPeserta = DB::table('data_perjadinkegiatans')
            ->where('id', $id)
            ->select('jumlah_peserta')
            ->first();

        $jumlahPanitia = DB::table('perangkat_acaras')
            ->where('data_perjadin_kegiatan', $id)
            ->where('posisi','Panitia')
            ->count();

            if (is_null($peserta) || is_null($hari) || is_null($kamar) || $peserta < 0 || $hari < 0 || $kamar < 0 || $peserta == -1) {
                return redirect()->route('kegiatan_step_2', ['id' => $id])
                    ->with(['error' => 'Lengkapi Jumlah Peserta, Kamar, dan Jumlah Hari Penginapan dengan benar']);
            } else if (($jumlahPanitia > $maksPanitia) && ($peserta != 0)) {
                return redirect()->route('kegiatan_step_2', ['id' => $id])
                    ->with(['error' => 'Jumlah Panitia anda tidak boleh lebih dari 10% Jumlah Peserta']);
            } else if ((!is_null($jumlahPeserta->jumlah_peserta) && $jumlahPeserta->jumlah_peserta == 0) && ($jumlahPanitia > $maksPanitia) && ($jumlahPeserta->jumlah_peserta != 0)) {
                return redirect()->route('kegiatan_step_2', ['id' => $id])
                    ->with(['error' => 'Jumlah Panitia anda tidak boleh lebih dari 10% Jumlah Peserta, silakan kurangi panitia']);
            }



        // Lanjutkan proses lainnya jika semua variabel tidak kosong
            DB::table('data_perjadinkegiatans')->where('id', $id)->update([
                'is_acceptBMN' => null,
                'is_acceptAset' => null,
                'is_acceptHKT' => null,
                'status' => 'Draf-pengajuan',
                'status_pengajuan' => 'Draf-pengajuan',
                'status_pengajuan_detail' => 'Draf-pengajuan',
                'jumlah_kamar' => $request->jumlah_kamar,
                'tambah_penginapan' => $request->tambah_penginapan,
                'jumlah_peserta' => $request->jumlah_peserta,
                'jumlah_kepanitiaan' => $request->jumlah_kepanitiaan,
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        return redirect()->route('kegiatan_step_2', ['id' => $id])->with('success', 'Berhasil, Silakan Inputkan Data Lainnya');

    }

    public function riwayat($status = 'pengajuan')
    {
        $userId = auth('pegawai')->user()->id;

    // Hitung status kegiatan seperti perjadin
    // $countDraf = DB::table('data_perjadinkegiatans')
    //     ->join('perangkat_acaras', 'data_perjadinkegiatans.id', '=', 'perangkat_acaras.data_perjadin_kegiatan')
    //     ->where('perangkat_acaras.pegawai_id', $userId)
    //     ->where('data_perjadinkegiatans.status_pengajuan', 'Draf-pengajuan')
    //     ->where('data_perjadinkegiatans.versi_id', session('versi'))
    //     ->distinct()
    //     ->count('data_perjadinkegiatans.id');

    // $countPengajuan = DB::table('data_perjadinkegiatans')
    //     ->join('perangkat_acaras', 'data_perjadinkegiatans.id', '=', 'perangkat_acaras.data_perjadin_kegiatan')
    //     ->where('perangkat_acaras.pegawai_id', $userId)
    //     ->where('data_perjadinkegiatans.status_pengajuan', 'pengajuan')
    //     ->where('data_perjadinkegiatans.versi_id', session('versi'))
    //     ->distinct()
    //     ->count('data_perjadinkegiatans.id');

    // $countProses = DB::table('data_perjadinkegiatans')
    //     ->join('perangkat_acaras', 'data_perjadinkegiatans.id', '=', 'perangkat_acaras.data_perjadin_kegiatan')
    //     ->where('perangkat_acaras.pegawai_id', $userId)
    //     ->where('data_perjadinkegiatans.status_pengajuan', 'proses')
    //     ->where('data_perjadinkegiatans.versi_id', session('versi'))
    //     ->distinct()
    //     ->count('data_perjadinkegiatans.id'); // Hitung hanya data_perjadinkegiatans.id yang unik

    // $countPelaporan = DB::table('data_perjadinkegiatans')
    //     ->join('perangkat_acaras', 'data_perjadinkegiatans.id', '=', 'perangkat_acaras.data_perjadin_kegiatan')
    //     ->where('perangkat_acaras.pegawai_id', $userId)
    //     ->where('data_perjadinkegiatans.status_pengajuan', 'pelaporan')
    //     ->where('data_perjadinkegiatans.versi_id', session('versi'))
    //     ->distinct()
    //     ->count('data_perjadinkegiatans.id');

    // $countRevisi = DB::table('data_perjadinkegiatans')
    //     ->join('perangkat_acaras', 'data_perjadinkegiatans.id', '=', 'perangkat_acaras.data_perjadin_kegiatan')
    //     ->where('perangkat_acaras.pegawai_id', $userId)
    //     ->where('data_perjadinkegiatans.status_pengajuan', 'revisi')
    //     ->where('data_perjadinkegiatans.versi_id', session('versi'))
    //     ->distinct()
    //     ->count('data_perjadinkegiatans.id');

    // $countDitolak = DB::table('data_perjadinkegiatans')
    //     ->join('perangkat_acaras', 'data_perjadinkegiatans.id', '=', 'perangkat_acaras.data_perjadin_kegiatan')
    //     ->where('perangkat_acaras.pegawai_id', $userId)
    //     ->where('data_perjadinkegiatans.status_pengajuan', 'ditolak')
    //     ->where('data_perjadinkegiatans.versi_id', session('versi'))
    //     ->distinct()
    //     ->count('data_perjadinkegiatans.id');

    // $countSelesai = DB::table('data_perjadinkegiatans')
    //     ->join('perangkat_acaras', 'data_perjadinkegiatans.id', '=', 'perangkat_acaras.data_perjadin_kegiatan')
    //     ->where('perangkat_acaras.pegawai_id', $userId)
    //     ->where('data_perjadinkegiatans.status_pengajuan', 'selesai')
    //     ->where('data_perjadinkegiatans.versi_id', session('versi'))
    //     ->select('data_perjadinkegiatans.id', 'data_perjadinkegiatans.*')  // Pilih kolom tertentu
    //     ->distinct()
    //     ->count('data_perjadinkegiatans.id');

        if ($status == 'Draf-pengajuan') {
            $riwayatKegiatans = DB::table('data_perjadinkegiatans')
            ->leftJoin('perangkat_acaras', 'data_perjadinkegiatans.id', '=', 'perangkat_acaras.data_perjadin_kegiatan')
            ->leftJoin('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
            ->select(
            'data_perjadinkegiatans.id as idKegiatan',
            DB::raw('MAX(data_perjadinkegiatans.nama_kegiatan) as nama_kegiatan'),
            DB::raw('MAX(data_perjadinkegiatans.jenis_kegiatan) as jenis_kegiatan'),
            DB::raw('MAX(data_perjadinkegiatans.jenis_program) as jenis_program'),
            DB::raw('MAX(data_perjadinkegiatans.tgl_mulai) as tgl_mulai'),
            DB::raw('MAX(data_perjadinkegiatans.status_pengajuan_detail) as status'),
            DB::raw('MAX(data_perjadinkegiatans.status_pengajuan) as status_pengajuan'),
            DB::raw('MAX(data_perjadinkegiatans.status) as status_draf'),
            DB::raw('MAX(data_perjadinkegiatans.status_pengajuan_detail) as status_pengajuan_detail'),
            DB::raw('MAX(data_perjadinkegiatans.id_pengaju) as id_pengaju'),
            DB::raw('MAX(data_perjadinkegiatans.alasan_penolakan) as alasan_penolakan'),
            DB::raw('MAX(pegawais.nama_lengkap) as nama_lengkap')
            )
            ->where('data_perjadinkegiatans.id_pengaju', $userId)
            ->where('data_perjadinkegiatans.status', $status) // status menggunakan variabel $status
            ->where(function ($query) {
                $query->where('data_perjadinkegiatans.status_pengajuan', null)
                      ->orWhere('data_perjadinkegiatans.status_pengajuan', 'Draf-pengajuan');
            }) // status menggunakan variabel $statu
            ->where('data_perjadinkegiatans.versi_id', session('versi'))
            ->groupBy('data_perjadinkegiatans.id') // Kelompokkan berdasarkan ID Kegiatan
            ->get();
        } else {
            $riwayatKegiatans = DB::table('data_perjadinkegiatans')
                ->leftJoin('perangkat_acaras', 'data_perjadinkegiatans.id', '=', 'perangkat_acaras.data_perjadin_kegiatan')
                ->leftJoin('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
                ->select(
                'data_perjadinkegiatans.id as idKegiatan',
                DB::raw('MAX(data_perjadinkegiatans.nama_kegiatan) as nama_kegiatan'),
                DB::raw('MAX(data_perjadinkegiatans.jenis_kegiatan) as jenis_kegiatan'),
                DB::raw('MAX(data_perjadinkegiatans.jenis_program) as jenis_program'),
                DB::raw('MAX(data_perjadinkegiatans.tgl_mulai) as tgl_mulai'),
                DB::raw('MAX(data_perjadinkegiatans.status_pengajuan_detail) as status'),
                DB::raw('MAX(data_perjadinkegiatans.status) as status_draf'),
                DB::raw('MAX(data_perjadinkegiatans.status_pengajuan) as status_pengajuan'),
                DB::raw('MAX(data_perjadinkegiatans.status_pengajuan_detail) as status_pengajuan_detail'),
                DB::raw('MAX(data_perjadinkegiatans.id_pengaju) as id_pengaju'),
                DB::raw('MAX(data_perjadinkegiatans.alasan_penolakan) as alasan_penolakan'),
                DB::raw('MAX(pegawais.nama_lengkap) as nama_lengkap')
                )
                ->where(function ($query) use ($userId) {
                    $query->where('data_perjadinkegiatans.id_pengaju', $userId)
                        ->orWhere('perangkat_acaras.pegawai_id', $userId);
                })
                ->where('data_perjadinkegiatans.status_pengajuan', $status) // Ubah status_pengajuan menjadi 'selesai'
                ->where('data_perjadinkegiatans.versi_id', session('versi'))
                ->groupBy('data_perjadinkegiatans.id') // Kelompokkan berdasarkan ID Kegiatan
                ->get();
        }
            $countDrafNew = DB::table('data_perjadinkegiatans')
                ->leftJoin('perangkat_acaras', 'data_perjadinkegiatans.id', '=', 'perangkat_acaras.data_perjadin_kegiatan')
                ->leftJoin('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
                ->where('data_perjadinkegiatans.id_pengaju', $userId)
                ->where('data_perjadinkegiatans.status', "Draf-pengajuan") // status menggunakan variabel $status
                ->where(function ($query) {
                    $query->where('data_perjadinkegiatans.status_pengajuan', null)
                          ->orWhere('data_perjadinkegiatans.status_pengajuan', 'Draf-pengajuan');
                }) // status menggunakan variabel $status
                ->where('data_perjadinkegiatans.versi_id', session('versi'))
                ->distinct('data_perjadinkegiatans.id') // Kelompokkan berdasarkan ID Kegiatan
                ->count(); // Menghitung jumlah data dengan status 'selesai'

            $countPengajuanNew = DB::table('data_perjadinkegiatans')
                ->leftJoin('perangkat_acaras', 'data_perjadinkegiatans.id', '=', 'perangkat_acaras.data_perjadin_kegiatan')
                ->leftJoin('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
                ->where(function ($query) use ($userId) {
                    $query->where('data_perjadinkegiatans.id_pengaju', $userId)
                        ->orWhere('perangkat_acaras.pegawai_id', $userId);
                })
                ->where('data_perjadinkegiatans.status_pengajuan', 'pengajuan') // Ubah status_pengajuan menjadi 'selesai'
                ->where('data_perjadinkegiatans.versi_id', session('versi'))
                ->distinct('data_perjadinkegiatans.id')
                ->count(); // Menghitung jumlah data dengan status 'selesai'

            $countProsesNew = DB::table('data_perjadinkegiatans')
                ->leftJoin('perangkat_acaras', 'data_perjadinkegiatans.id', '=', 'perangkat_acaras.data_perjadin_kegiatan')
                ->leftJoin('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
                ->where(function ($query) use ($userId) {
                    $query->where('data_perjadinkegiatans.id_pengaju', $userId)
                        ->orWhere('perangkat_acaras.pegawai_id', $userId);
                })
                ->where('data_perjadinkegiatans.status_pengajuan', 'proses') // Ubah status_pengajuan menjadi 'selesai'
                ->where('data_perjadinkegiatans.versi_id', session('versi'))
                ->distinct('data_perjadinkegiatans.id')
                ->count(); // Menghitung jumlah data dengan status 'selesai'

            $countPelaporanNew = DB::table('data_perjadinkegiatans')
                ->leftJoin('perangkat_acaras', 'data_perjadinkegiatans.id', '=', 'perangkat_acaras.data_perjadin_kegiatan')
                ->leftJoin('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
                ->where(function ($query) use ($userId) {
                    $query->where('data_perjadinkegiatans.id_pengaju', $userId)
                        ->orWhere('perangkat_acaras.pegawai_id', $userId);
                })
                ->where('data_perjadinkegiatans.status_pengajuan', 'pelaporan') // Ubah status_pengajuan menjadi 'selesai'
                ->where('data_perjadinkegiatans.versi_id', session('versi'))
                ->distinct('data_perjadinkegiatans.id')
                ->count(); // Menghitung jumlah data dengan status 'selesai'

            $countRevisiNew = DB::table('data_perjadinkegiatans')
                ->leftJoin('perangkat_acaras', 'data_perjadinkegiatans.id', '=', 'perangkat_acaras.data_perjadin_kegiatan')
                ->leftJoin('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
                ->where(function ($query) use ($userId) {
                    $query->where('data_perjadinkegiatans.id_pengaju', $userId)
                        ->orWhere('perangkat_acaras.pegawai_id', $userId);
                })
                ->where('data_perjadinkegiatans.status_pengajuan', 'revisi') // Ubah status_pengajuan menjadi 'selesai'
                ->where('data_perjadinkegiatans.versi_id', session('versi'))
                ->distinct('data_perjadinkegiatans.id')
                ->count(); // Menghitung jumlah data dengan status 'selesai'

            $countDitolakNew = DB::table('data_perjadinkegiatans')
                ->leftJoin('perangkat_acaras', 'data_perjadinkegiatans.id', '=', 'perangkat_acaras.data_perjadin_kegiatan')
                ->leftJoin('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
                ->where(function ($query) use ($userId) {
                    $query->where('data_perjadinkegiatans.id_pengaju', $userId)
                        ->orWhere('perangkat_acaras.pegawai_id', $userId);
                })
                ->where('data_perjadinkegiatans.status_pengajuan', 'ditolak') // Ubah status_pengajuan menjadi 'selesai'
                ->where('data_perjadinkegiatans.versi_id', session('versi'))
                ->distinct('data_perjadinkegiatans.id')
                ->count(); // Menghitung jumlah data dengan status 'selesai'

                // dd($countDitolakNew);

            $countSelesaiNew = DB::table('data_perjadinkegiatans')
                ->leftJoin('perangkat_acaras', 'data_perjadinkegiatans.id', '=', 'perangkat_acaras.data_perjadin_kegiatan')
                ->leftJoin('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
                ->where(function ($query) use ($userId) {
                    $query->where('data_perjadinkegiatans.id_pengaju', $userId)
                        ->orWhere('perangkat_acaras.pegawai_id', $userId);
                })
                ->where('data_perjadinkegiatans.status_pengajuan', 'selesai') // Ubah status_pengajuan menjadi 'selesai'
                ->where('data_perjadinkegiatans.versi_id', session('versi'))
                ->distinct('data_perjadinkegiatans.id')
                ->count(); // Menghitung jumlah data dengan status 'selesai'




        return view('user.kegiatan.riwayat', [
            'title' => 'Kegiatanku',
            'active' => 'kegiatanku_perjadin',
            'kegiatans' => $riwayatKegiatans,
            'countDraf' => $countDrafNew,
            'countPengajuan' => $countPengajuanNew,
            'countPelaporan' => $countPelaporanNew,
            'countProses' => $countProsesNew,
            'countRevisi' => $countRevisiNew,
            'countDitolak' => $countDitolakNew,
            'countSelesai' => $countSelesaiNew,
            'status' => $status,
        ]);
    }

    public function previewNotePenugasanKegiatan($id)
    {
        $penandatangan  = DB::table('pegawais')
            ->where('id',auth('pegawai')->user()->id)
            ->select('pegawais.nama_lengkap')
            ->first();
        $nama_dokumen = 'lap-'.$id.'-'.$id.'-'.$id;
        $dokumen  = DB::table('laporan_perjadinkegiatans')
            ->where('data_perjadin_kegiatan',$id)
            ->where('nama_dokumen',$nama_dokumen)
            ->first();

        return view(
            'user.kegiatan.preview_lap_penugasan',
            [
                'title' => 'Detail Kegiatanku',
                'active' => 'kegiatanku_perjadin',
                'penandatangan' => $penandatangan,
                'kegiatan' => Data_perjadinkegiatan::find($id),
                'dokumen' => $dokumen,
            ]
        );
    }

    public function storeLaporanPenugasan(Request $request)
    {
        $nama_dokumen = 'lap-'.$request->kegiatan.'-'.$request->kegiatan.'-'.$request->kegiatan;

        DB::table('laporan_perjadinkegiatans')
            ->where('data_perjadin_kegiatan',$request->kegiatan)
            ->where('nama_dokumen',$nama_dokumen)
            ->update([
                'data_perjadin_kegiatan' => $request->kegiatan,
                'nama_pelaksana' => $request->pelaksana,
                'tempat_pelaksanaan' => $request->tempat,
                'file' => '-',
                'status' => '-',
                'hasil' => $request->hasil,
                'nama_penandatangan' => $request->nama_penandatangan,
                'jabatan_penandatangan' => $request->jabatan_penandatangan,
                'nip_penandatangan' => $request->nip_penandatangan,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);


            return $this-> previewNotePenugasanKegiatan($request->kegiatan);
            // return redirect()->route('detail', ['id' => $request->kegiatan, 'tab' => 'dokumen'])
            // ->with('success', 'Laporan berhasil disimpan!');
    }

    public function notePenugasanKegiatan($id)
    {

        $pesertaPegawais = DB::table('perangkat_acaras')
            ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
            ->select('pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan', 'perangkat_acaras.status as status_pegawai', 'pegawais.NIP_NIK')
            ->where('perangkat_acaras.data_perjadin_kegiatan', $id)
            ->get();
        $pesertaNonPegawais = DB::table('perangkat_acaras')
            ->join('non_pegawais', 'perangkat_acaras.non_pegawai_id', '=', 'non_pegawais.id')
            ->select('non_pegawais.nama_lengkap', 'non_pegawais.pangkat', 'non_pegawais.golongan', 'perangkat_acaras.status as status_pegawai')
            ->where('perangkat_acaras.data_perjadin_kegiatan', $id)
            ->get();
        $surat = DB::table('surtug_perjadinkegiatans')
            ->join('data_perjadinkegiatans', 'surtug_perjadinkegiatans.data_perjadin_kegiatan', '=', 'data_perjadinkegiatans.id')
            ->select('surtug_perjadinkegiatans.nomor_surat', 'surtug_perjadinkegiatans.perihal', 'surtug_perjadinkegiatans.paragraf_1', 'surtug_perjadinkegiatans.paragraf_2', 'surtug_perjadinkegiatans.paragraf_3')
            ->where('surtug_perjadinkegiatans.data_perjadin_kegiatan', $id)
            ->get();
        $penandatangan  = DB::table('pegawais')
            ->where('id',auth('pegawai')->user()->id)
            ->select('pegawais.nama_lengkap')
            ->first();

        $pic = $pesertaPegawais->firstWhere('status_pegawai', 'PIC');

        $nama_dokumen = 'lap-'.$id.'-'.$id.'-'.$id;

        $dokumenExist  = DB::table('laporan_perjadinkegiatans')
            ->where('data_perjadin_kegiatan',$id)
            ->where('nama_dokumen',$nama_dokumen)
            ->select('*')
            ->exists();

        if (!$dokumenExist) {
            DB::table('laporan_perjadinkegiatans')
            ->insert([
                'nama_dokumen' => $nama_dokumen,
                'data_perjadin_kegiatan' => $id,
                'file' => '-',
                'status' => '-',
            ]);
        }

        $dokumen  = DB::table('laporan_perjadinkegiatans')
            ->where('data_perjadin_kegiatan',$id)
            ->where('nama_dokumen',$nama_dokumen)
            ->select('*')
            ->first();

        return view(
            'user.kegiatan.laporan_penugasan_kegiatan',
            [
                'title' => 'Detail Kegiatanku',
                'active' => 'kegiatanku_perjadin',
                'penandatangan' => $penandatangan,
                'kegiatan' => Data_perjadinkegiatan::find($id),
                'dokumen' => $dokumen,
                'surtugs' => $surat->first(),
                'pesertaPegawais' => $pesertaPegawais,
                'pesertaNonPegawais' => $pesertaNonPegawais,
                'pic' => $pic
            ]
        );
    }


    public function detail($id)
    {
        $kegiatan = Data_perjadinkegiatan::find($id);

        $tab = request()->query('tab'); // Tangkap nilai tab
        if(($tab == 'dokumen') && ($kegiatan->is_acceptKeu != 'selesai')) {
            DB::table('data_perjadinkegiatans')
            ->where('id', $id)
            ->update([
                'status' => 'pelaporan',
                'status_pengajuan' => 'pelaporan',
                'status_pengajuan_detail' => 'Pelaporan',
            ]);
        }

    if ($kegiatan->status === 'Draf-pengajuan') {
        return redirect()->route('kegiatan_step_2', ['id' => $id]);
    }
        $perangkatPegawai = DB::table('perangkat_acaras')
            ->join('pegawais', 'pegawai_id', '=', 'pegawais.id')
            ->join('fasilitas', 'fasilitas_id', '=', 'fasilitas.id')
            ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
            ->select('perangkat_acaras.id as idPerangkat', 'pegawais.nama_lengkap', 'pegawais.golongan', 'pegawais.pangkat', 'perangkat_acaras.status', 'perangkat_acaras.sebagai', 'fasilitas.nama_fasilitas', 'perangkat_acaras.fasilitas_id', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
            ->groupBY(
                'perangkat_acaras.id',
                'pegawais.nama_lengkap',
                'pegawais.golongan',
                'pegawais.pangkat',
                'perangkat_acaras.status',
                'perangkat_acaras.sebagai',
                'fasilitas.nama_fasilitas',
                'perangkat_acaras.fasilitas_id',
                'keuangan_perjadinkegiatans.data_perjadinkegiatan')
            ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
            ->get();

        $supir = DB::table('perangkat_acaras')
        ->join('pegawais', 'pegawai_id', '=', 'pegawais.id')
        ->join('fasilitas', 'fasilitas_id', '=', 'fasilitas.id')
        ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
        ->join('operasionals', 'keuangan_perjadinkegiatans.operasional', '=', 'operasionals.id')  // Menghubungkan dengan operasionals
        ->join('mobilitas_perjadinkegiatans', 'mobilitas_perjadinkegiatans.id', '=', 'operasionals.data_perjadin_kegiatan')  // Menghubungkan dengan mobilitas_perjadinkegiatans
        ->select(
            'perangkat_acaras.id as idPerangkat',
            'pegawais.nama_lengkap',
            'pegawais.golongan',
            'pegawais.pangkat',
            'perangkat_acaras.status',
            'perangkat_acaras.sebagai',
            'fasilitas.nama_fasilitas',
            'perangkat_acaras.fasilitas_id',
            'keuangan_perjadinkegiatans.data_perjadinkegiatan',
            'mobilitas_perjadinkegiatans.tujuan_penggunaan'  // Menambahkan kolom tujuan_penggunaan dari mobilitas_perjadinkegiatans
        )
        ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
        ->get();

        $perangkatNonPegawai = DB::table('perangkat_acaras')
            ->join('non_pegawais', 'non_pegawai_id', '=', 'non_pegawais.id')
            ->join('fasilitas', 'fasilitas_id', '=', 'fasilitas.id')
            ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
            ->select('perangkat_acaras.id as idPerangkat', 'non_pegawais.nama_lengkap', 'non_pegawais.golongan', 'non_pegawais.pangkat', 'perangkat_acaras.status', 'perangkat_acaras.sebagai', 'fasilitas.nama_fasilitas', 'perangkat_acaras.fasilitas_id', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
            ->groupBY(
                'perangkat_acaras.id',
                'non_pegawais.nama_lengkap',
                'non_pegawais.golongan',
                'non_pegawais.pangkat',
                'perangkat_acaras.status',
                'perangkat_acaras.sebagai',
                'fasilitas.nama_fasilitas',
                'perangkat_acaras.fasilitas_id',
                'keuangan_perjadinkegiatans.data_perjadinkegiatan')
            ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
            ->get();

            $operasionals = DB::table('kebutuhans')
                ->join('keuangan_perjadinkegiatans', 'kebutuhans.id', '=', 'keuangan_perjadinkegiatans.kebutuhan_id')
                ->join('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
                ->select(
                    'kebutuhans.id as idKebutuhan',
                    'kebutuhans.nama',
                    'kebutuhans.jumlah_frekuensi',
                    'kebutuhans.satuan',
                    'kebutuhans.tipe_pendanaan',
                    'kebutuhans.ket',
                    'kebutuhans.status',
                    'keuangan_perjadinkegiatans.kebutuhan_id as idKeuangan',
                    'keuangan_perjadinkegiatans.data_perjadinkegiatan',
                    'keuangan_perjadinkegiatans.akun_x_rkakl',
                )
                ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)

                ->get();
        $operasionalss = DB::table('keuangan_perjadinkegiatans')
            ->join('operasionals', 'keuangan_perjadinkegiatans.operasional', '=', 'operasionals.id')
            ->select('operasionals.id', 'operasionals.status', 'operasionals.nama', 'operasionals.jumlah_frekuensi', 'operasionals.satuan', 'operasionals.detail_satuan', 'keuangan_perjadinkegiatans.operasional', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
            ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
            ->get();
        $saprasKegiatan = DB::table('peminjaman_sarpras')
            ->join('assets', 'peminjaman_sarpras.asset', '=', 'assets.id')
            ->select('peminjaman_sarpras.id as idPeminjaman', 'assets.id as IdBarang', 'assets.nama_barang', 'peminjaman_sarpras.jumlah_asset', 'peminjaman_sarpras.tgl_peminjaman', 'peminjaman_sarpras.data_perjadinkegiatan', 'peminjaman_sarpras.status')
            ->where('peminjaman_sarpras.data_perjadinkegiatan', $id)
            ->get();

        $nama_dokumen = 'lap-'.$id.'-'.$id.'-'.$id;
        $dokumens =  Laporan_perjadinkegiatan::select(
                'laporan_perjadinkegiatans.*',
                DB::raw("IF(laporan_perjadinkegiatans.file = data_perjadinkegiatans.surat_tugas, TRUE, FALSE) AS isSurtug")
            )
            ->join('data_perjadinkegiatans', 'laporan_perjadinkegiatans.data_perjadin_kegiatan', '=', 'data_perjadinkegiatans.id')
            ->where('laporan_perjadinkegiatans.data_perjadin_kegiatan', $id)
            ->where('laporan_perjadinkegiatans.nama_dokumen','!=', $nama_dokumen)
            ->whereNotNull('laporan_perjadinkegiatans.file')
            ->get();
        return view('user.kegiatan.detail', [
            'title' => 'Kegiatanku',
            'active' => 'kegiatanku_perjadin',
            'kegiatan' => Data_perjadinkegiatan::find($id),
            "perangkats" => Fasilitas::where('data_perjadinkegiatan_id', $id)->get(),
            "perangkatPegawais" => $perangkatPegawai,
            "supirs" => $supir,
            "perangkatNonPegawais" => $perangkatNonPegawai,
            "pegawais" => Pegawai::all(),
            "nonpegawais" => Non_pegawai::all(),
            "operasionals" => $operasionals,
            "mobilitas" => Mobilitas_perjadinkegiatan::where('data_perjadinkegiatan', $id)->get(),
            "saranas" => Asset::where('status_peminjaman', '=', 'Tidak Dipakai')->get(),
            "sapras" => $saprasKegiatan,
            "dokumens" => $dokumens,
            'tab' => $tab,
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
    
    public function draftKegiatanAll(Request $request, $id)
    {
        DB::table('data_perjadinkegiatans')->where('id', $id)->update([
                'is_acceptBMN' => null,
                'is_acceptAset' => null,
                'is_acceptHKT' => null,
                'status' => 'Draf-pengajuan',
                'status_pengajuan' => 'Draf-pengajuan',
                'jumlah_kamar' => $request->jumlah_kamar,
                'tambah_penginapan' => $request->tambah_penginapan,
                'jumlah_peserta' => $request->jumlah_peserta,
                'jumlah_kepanitiaan' => $request->jumlah_kepanitiaan,
                'updated_at' => now()->format('Y-m-d H:i:s')    ,
                'id_pengaju' => auth('pegawai')->user()->id // Tambahkan ID pengaju yang login
            ]);

        return redirect()->route('riwayat-kegiatan', ['status' => 'Draf-pengajuan'])
            ->with('success', 'Draf pengajuan kegiatan berhasil disimpan.');
    }

    public function storeKegiatan(Request $request)
    {

        $versi = Versi::where('status', 'aktif')->first();
        DB::table('data_perjadinkegiatans')->insertOrIgnore([
            'uraian' => $request->uraian,
            'id_pengaju' => auth('pegawai')->user()->id,
            'program_kerja' => $request->program_kerja,
            'nama_kegiatan' => $request->nama_kegiatan,
            'id_iku' => $request->kode_iku,
            'status' => 'Draf-pengajuan',
            'status_pengajuan' => 'Draf-pengajuan',
            'status_pengajuan_detail' => 'Pengisian-Step2',
            'jumlah_peserta' => $request->jumlah_peserta,
            'tgl_mulai' => $request->tgl_mulai,
            'tempat_kegiatan' => $request->tempat_kegiatan,
            'tgl_selesai' => $request->tgl_selesai,
            'versi_id' => $versi->id,
            'jenis_kegiatan' => $request->jenis_kegiatan,
            'provinsi' => $request->provinsi,
            'kab_kota' => $request->kab_kota,
            'alamat' => $request->alamat,
            'jenis_program' => $request->jenis_program,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        // Mengambil nilai ID terakhir yang diinputkan
        $kegiatanId = Data_perjadinkegiatan::max('id');


        $kegiatanData =  DB::table('data_perjadinkegiatans')
            ->where('id', $kegiatanId)
            ->first();

        $isPenugasan = $kegiatanData->jenis_program == 'Penugasan';

        if ($isPenugasan){
            if ($kegiatanData && $kegiatanData->tgl_mulai && $kegiatanData->tgl_selesai) {
                // Mengubah tgl_mulai dan tgl_selesai menjadi objek Carbon
                $tglMulai = Carbon::parse($kegiatanData->tgl_mulai);
                $tglSelesai = Carbon::parse($kegiatanData->tgl_selesai);

                // Menghitung selisih hari
                $jumlahHari = $tglMulai->diffInDays($tglSelesai) + 1;
            } else {
                $jumlahHari = null; // atau nilai default lain jika salah satu tanggal tidak ada
            }

            DB::table('data_perjadinkegiatans')
                ->where('id',$kegiatanId)
                ->update([
                'jumlah_peserta' => 0,
                'tambah_penginapan' => $jumlahHari,
            ]);
        }

        if (!$isPenugasan) {
            // Redirect ke route berikutnya dengan ID yang didapat
            return redirect()->route('kegiatan_step_2', ['id' => $kegiatanId])->with('success', 'Permohonan kegiatan berhasil dibuat. Silakan isi detail kegiatan!');
        } else {
            // Redirect ke route berikutnya dengan ID yang didapat
            return redirect()->route('kegiatan_penugasan_step_2', ['id' => $kegiatanId])->with('success', 'Permohonan kegiatan berhasil dibuat. Silakan isi detail kegiatan!');

        }
    }

    public function updateKegiatan(Request $request, $id)
    {
            DB::table('data_perjadinkegiatans')
            ->where('id',$id)
            ->update([
                'uraian' => $request->uraian,
                'program_kerja' => $request->program_kerja,
                'nama_kegiatan' => $request->nama_kegiatan,
                'id_iku' => $request->kode_iku,
                'status' => 'Draf-pengajuan',
                'status_pengajuan_detail' => 'Pengisian-Step2',
                'tgl_mulai' => $request->tgl_mulai,
                'tempat_kegiatan' => $request->tempat_kegiatan,
                'tgl_selesai' => $request->tgl_selesai,
                'jenis_kegiatan' => $request->jenis_kegiatan,
                'provinsi' => $request->provinsi,
                'kab_kota' => $request->kab_kota,
                'alamat' => $request->alamat,
                'jenis_program' => $request->jenis_program,
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);



        $isPenugasan = $request->jenis_program == 'Penugasan';

        if (!$isPenugasan) {
            // Redirect ke route berikutnya dengan ID yang didapat
            return redirect()->route('kegiatan_step_2', ['id' => $id])->with('success', 'Permohonan kegiatan berhasil diperbarui. Silakan isi detail kegiatan!');
        } else {
            // Redirect ke route berikutnya dengan ID yang didapat
            return redirect()->route('kegiatan_penugasan_step_2', ['id' => $id])->with('success', 'Permohonan kegiatan berhasil diperbarui. Silakan isi detail kegiatan!');
        }
    }

    public function destroyFasilitasKegiatan(Request $request, $id)
    {

        $kegiatanId = $request->kegiatanId;

        $kegiatanData =  DB::table('data_perjadinkegiatans')
            ->where('id', $kegiatanId)
            ->first();

        $isPenugasan = $kegiatanData->jenis_program == 'Penugasan';

        if (!$isPenugasan) {
            DB::table('keuangan_perjadinkegiatans')
                ->where('data_perjadinkegiatan', $kegiatanId)
                ->where('kebutuhan_id', $id)
                ->delete();

            Kebutuhan::destroy($id);

            return redirect()->route('kegiatan_step_2', ['id' => $kegiatanId])
                ->with('success', 'Data fasilitas berhasil dihapus!');
        } else {
            $perangkatAcara = $request->perangkat_acara;

            DB::table('keuangan_perjadinkegiatans')
                ->where('data_perjadinkegiatan', $kegiatanId)
                ->where('perangkat_acara', $perangkatAcara)
                ->where('kebutuhan_id', $id)
                ->delete();

            Kebutuhan::destroy($id);

            return redirect()->route('kegiatan_penugasan_step_2', ['id' => $kegiatanId])
                ->with('success', 'Data fasilitas berhasil dihapus!');
        }
    }

    public function storeFasilitas(Request $request)
    {
        // dd($request->all());
        $kegiatanData =  DB::table('data_perjadinkegiatans')
            ->where('id', $request->data_perjadinkegiatan)
            ->first();

        $isPenugasan = $kegiatanData->jenis_program == 'Penugasan';

        if (!$isPenugasan) {
            // Validasi input
            $request->validate([
                'uraian' => 'required|string|max:255',
                'jumlah_frekuensi' => 'required|integer|min:1',
                'satuan' => 'required|string|max:50',
                'tipe_pendanaan' => 'required|string',
                'keterangan' => 'nullable|string',
                'data_perjadinkegiatan' => 'required|integer', // Pastikan ini divalidasi
            ]);

            // Simpan data ke dalam tabel 'kebutuhans'
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

            // Ambil id kebutuhan terakhir yang ditambahkan
            $kebutuhan_max = Kebutuhan::max('id');


            // Simpan data ke dalam 'keuangan_perjadinkegiatans'
            DB::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                'perangkat_acara' => $request->perangkat_acara,
                'data_perjadinkegiatan' => $request->data_perjadinkegiatan,
                'kebutuhan_id' => $kebutuhan_max,
                'status' => 'Menunggu Persetujuan Bendahara',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Redirect kembali ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Fasilitas berhasil ditambahkan!');
        } else {
            // Validasi input
            $request->validate([
                'uraian' => 'required|string|max:255',
                'jumlah_frekuensi' => 'required|integer|min:1',
                'satuan' => 'required|string|max:50',
                'tipe_pendanaan' => 'required|string',
                'keterangan' => 'nullable|string',
                'data_perjadinkegiatan' => 'required|integer', // Pastikan ini divalidasi
            ]);

            // Simpan data ke dalam tabel 'kebutuhans'
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

            // Ambil id kebutuhan terakhir yang ditambahkan
            $kebutuhan_max = Kebutuhan::max('id');

            $perangkat_acara = DB::table('perangkat_acaras')
                ->where('id', $request->pegawai_id)
                ->first();

            // Simpan data ke dalam 'keuangan_perjadinkegiatans'
            DB::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                'perangkat_acara' => $request->perangkat_acara,
                'data_perjadinkegiatan' => $request->data_perjadinkegiatan,
                'perangkat_acara' => $perangkat_acara->id,
                'kebutuhan_id' => $kebutuhan_max,
                'status' => 'Menunggu Persetujuan Bendahara',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Redirect kembali ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Fasilitas berhasil ditambahkan!');
        }
    }

    public function storeFasilitasDraft(Request $request)
    {
        db::table('fasilitas')->insertOrIgnore([
            'data_perjadinkegiatan_id' => $request->kegiatan_id,
            'nama_fasilitas' => $request->fasilitas,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        return redirect()->route('kegiatan_step_2', ['id' => $request->kegiatan_id])->with('success', 'Perangkat orang berhasil ditambahkan. Silakan tambahkan partisipan!');
    }

    public function storeFasilitasDetail(Request $request)
    {
        $satuan = $request->satuan ? $request->satuan : $request->satuan_manual;

        DB::table('kebutuhans')->insert([
            'nama' => $request->nama,
            'status' => 'Menunggu Persetujuan Bendahara',
            'jumlah_frekuensi' => $request->jumlah_frekuensi,
            'satuan' => $satuan,
            'tipe_pendanaan' => $request->tipe_pendanaan,
            'ket' => $request->keterangan,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $kebutuhan_max = Kebutuhan::max('id');
                db::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                    'data_perjadinkegiatan' => $request->kegiatanId,
                    'kebutuhan_id' => $kebutuhan_max,
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

                return redirect()->route('detail', ['id' => $request->kegiatanId])->with('success', 'Data berhasil ditambahkan!');
            }



            public function storePeserta(Request $request)
            {

                $response = $this->getKegiatanData($request->info_kegiatan);

                $maksPanitia = $response->getData()->maks_panitia;

                $jumlahPanitia = DB::table('perangkat_acaras')
                    ->where('data_perjadin_kegiatan', $request->info_kegiatan)
                    ->where('posisi','Panitia')
                    ->count();

                $kegiatanData =  DB::table('data_perjadinkegiatans')
                    ->where('id', $request->info_kegiatan)
                    ->first();

                $isPenugasan = $kegiatanData->jenis_program == 'Penugasan';

                if ($jumlahPanitia >= $maksPanitia && $request->posisi == 'Panitia' && $maksPanitia != -1) {
                    return redirect()->route('kegiatan_step_2', ['id' => $request->info_kegiatan])
                                     ->with(['error' => 'Maksimal Panitia ada 10% dari peserta yaitu: '. $maksPanitia. ' orang']);
                } else {
                    $exists = DB::table('fasilitas')
                        ->where('data_perjadinkegiatan_id', $request->info_kegiatan)
                        ->where('nama_fasilitas', $request->posisi)
                        ->exists();

                    if (!$exists) {
                        DB::table('fasilitas')->insert([
                            'data_perjadinkegiatan_id' => $request->info_kegiatan,
                            'nama_fasilitas' => $request->posisi,
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                    }
                    $fasilitas_id = DB::table('fasilitas')
                        ->where('data_perjadinkegiatan_id', $request->info_kegiatan)
                        ->where('nama_fasilitas', $request->posisi)
                        ->value('id');

                    db::table('perangkat_acaras')->insertOrIgnore([
                        'pegawai_id' => $request->peserta_pegawai,
                        'data_perjadin_kegiatan' => $request->info_kegiatan,
                        'posisi' => $request->posisi,
                        'sebagai' => $request->penugasan,
                        'tgl_mulai' => $kegiatanData->tgl_mulai,
                        'tgl_selesai' => $kegiatanData->tgl_selesai,
                        'status' => 'Diproses',
                        'detail_satuan' => $request->detail_satuan,
                        'tgl_mulai' => $request->mulai,
                        'tgl_selesai' => $request->selesai,
                        'satuan' => $request->satuan,
                        'fasilitas_id' => $fasilitas_id,
                        'created_at' => now()->format('Y-m-d H:i:s'),
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);

                    $maxPerangkat = Perangkat_acara::max('id');

                    if (!$isPenugasan) {

                        db::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                            'data_perjadinkegiatan' => $request->info_kegiatan,
                            'kode' => 'honor',
                            'perangkat_acara' => $maxPerangkat,
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                        
                        if ($request->posisi == 'Panitia'){
                            db::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                                'data_perjadinkegiatan' => $request->info_kegiatan,
                                'kode' => 'harian',
                                'perangkat_acara' => $maxPerangkat,
                                'created_at' => now()->format('Y-m-d H:i:s'),
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                            ]);
                        }
                    } else {
                        if ($request->posisi == 'Panitia'){
                            db::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                                'data_perjadinkegiatan' => $request->info_kegiatan,
                                'kode' => 'harian',
                                'perangkat_acara' => $maxPerangkat,
                                'created_at' => now()->format('Y-m-d H:i:s'),
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                            ]);
                        }
                    }

                    if (!$isPenugasan){
                        return redirect()->route('kegiatan_step_2', ['id' => $request->info_kegiatan])->with('success', 'Data berhasil ditambahkan!');
                    } else {
                        return redirect()->route('kegiatan_penugasan_step_2', ['id' => $request->info_kegiatan])->with('success', 'Data berhasil ditambahkan!');
                    }
                    $kegiatanData = DB::table('data_perjadinkegiatans')
                    ->where('id', $request->info_kegiatan)
                    ->first();

                    $tanggalAwal = Carbon::parse($request->tgl_mulai);
                    $tanggalAkhir = Carbon::parse($request->tgl_selesai);

                    $pegawaiOverlap = DB::table('perangkat_acaras')
                        ->join('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
                        ->where('perangkat_acaras.status', '!=', 'Ditolak')
                        ->where('perangkat_acaras.pegawai_id', '!=', null)
                        ->where(function ($query) use ($tanggalAwal, $tanggalAkhir) {
                            $query->whereBetween('perangkat_acaras.tgl_mulai', [$tanggalAwal, $tanggalAkhir])
                                ->orWhereBetween('perangkat_acaras.tgl_selesai', [$tanggalAwal, $tanggalAkhir])
                                ->orWhere(function ($subQuery2) use ($tanggalAwal, $tanggalAkhir) {
                                    $subQuery2->where('perangkat_acaras.tgl_mulai', '<=', $tanggalAwal)
                                        ->where('perangkat_acaras.tgl_selesai', '>=', $tanggalAkhir);
                                });
                        })
                        ->where('perangkat_acaras.data_perjadin_kegiatan', $request->info_kegiatan)
                        ->exists();

                    if ($pegawaiOverlap) {
                        // If there's an overlap, show error message
                        session()->flash('error', 'Pegawai tumpang tindih dengan kegiatan lain pada tanggal yang sama.');
                        return back(); // or another appropriate redirect
                    }

                }

            }

            public function storePesertaMany(Request $request)
            {
                $response = $this->getKegiatanData($request->info_kegiatan);
                $maksPanitia = $response->getData()->maks_panitia;

                $kegiatanData = DB::table('data_perjadinkegiatans')
                    ->where('id', $request->info_kegiatan)
                    ->first();

                $isPenugasan = $kegiatanData->jenis_program == 'Penugasan';

                // Pastikan fasilitas tersedia
                $fasilitas_id = DB::table('fasilitas')
                    ->where('data_perjadinkegiatan_id', $request->info_kegiatan)
                    ->where('nama_fasilitas', $request->posisi)
                    ->value('id');

                if (!$fasilitas_id) {
                    $fasilitas_id = DB::table('fasilitas')->insertGetId([
                        'data_perjadinkegiatan_id' => $request->info_kegiatan,
                        'nama_fasilitas' => $request->posisi,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $tanggalAwal = Carbon::parse($request->mulai);
                $tanggalAkhir = Carbon::parse($request->selesai);

                $pegawaiIds = $request->peserta_pegawai; // Ini array dari select multiple
                $jumlahDitambahkan = 0;
                $pesanOverlap = [];

                foreach ($pegawaiIds as $pegawaiId) {

                    // Cek batas maksimal panitia
                    $jumlahPanitia = DB::table('perangkat_acaras')
                        ->where('data_perjadin_kegiatan', $request->info_kegiatan)
                        ->where('posisi', 'Panitia')
                        ->count();

                    if ($request->posisi == 'Panitia' && $maksPanitia != -1 && $jumlahPanitia >= $maksPanitia) {
                        continue; // Lewatkan jika sudah melebihi batas
                    }

                    // Cek overlap
                    $pegawaiOverlap = DB::table('perangkat_acaras')
                        ->where('perangkat_acaras.status', '!=', 'Ditolak')
                        ->where('perangkat_acaras.pegawai_id', $pegawaiId)
                        ->where(function ($query) use ($tanggalAwal, $tanggalAkhir) {
                            $query->whereBetween('perangkat_acaras.tgl_mulai', [$tanggalAwal, $tanggalAkhir])
                                ->orWhereBetween('perangkat_acaras.tgl_selesai', [$tanggalAwal, $tanggalAkhir])
                                ->orWhere(function ($subQuery2) use ($tanggalAwal, $tanggalAkhir) {
                                    $subQuery2->where('perangkat_acaras.tgl_mulai', '<=', $tanggalAwal)
                                            ->where('perangkat_acaras.tgl_selesai', '>=', $tanggalAkhir);
                                });
                        })
                        ->exists();

                    if ($pegawaiOverlap) {
                        // Simpan nama pegawai yang overlap untuk ditampilkan pesan nanti
                        $namaPegawai = DB::table('pegawais')->where('id', $pegawaiId)->value('nama_lengkap');
                        $pesanOverlap[] = $namaPegawai;
                        continue; // Skip pegawai ini
                    }

                    // Simpan ke perangkat_acaras
                    DB::table('perangkat_acaras')->insertOrIgnore([
                        'pegawai_id' => $pegawaiId,
                        'data_perjadin_kegiatan' => $request->info_kegiatan,
                        'posisi' => $request->posisi,
                        'sebagai' => $request->penugasan,
                        'tgl_mulai' => $request->mulai,
                        'tgl_selesai' => $request->selesai,
                        'status' => 'Diproses',
                        'detail_satuan' => $request->detail_satuan,
                        'satuan' => $request->satuan,
                        'fasilitas_id' => $fasilitas_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $maxPerangkat = Perangkat_acara::max('id');

                    // Insert keuangan
                    if (!$isPenugasan) {
                        DB::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                            'data_perjadinkegiatan' => $request->info_kegiatan,
                            'kode' => 'honor',
                            'perangkat_acara' => $maxPerangkat,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        if ($request->posisi == 'Panitia') {
                            DB::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                                'data_perjadinkegiatan' => $request->info_kegiatan,
                                'kode' => 'harian',
                                'perangkat_acara' => $maxPerangkat,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    } else {
                        if ($request->posisi == 'Panitia') {
                            DB::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                                'data_perjadinkegiatan' => $request->info_kegiatan,
                                'kode' => 'harian',
                                'perangkat_acara' => $maxPerangkat,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }

                    $jumlahDitambahkan++;
                }

                // Siapkan flash message
                $pesan = '';
                if ($jumlahDitambahkan > 0) {
                    $pesan .= $jumlahDitambahkan . " peserta berhasil ditambahkan.";
                }
                if (count($pesanOverlap) > 0) {
                    $pesan .= " Tidak ditambahkan karena tumpang tindih jadwal: " . implode(", ", $pesanOverlap);
                }

                if (!$isPenugasan) {
                    return redirect()->route('kegiatan_step_2', ['id' => $request->info_kegiatan])->with('success', $pesan);
                } else {
                    return redirect()->route('kegiatan_penugasan_step_2', ['id' => $request->info_kegiatan])->with('success', $pesan);
                }
            }


            public function storeNonPegawai(Request $request)
            {

                $response = $this->getKegiatanData($request->info_kegiatan);

                $maksPanitia = $response->getData()->maks_panitia;

                $jumlahPanitia = DB::table('perangkat_acaras')
                    ->where('data_perjadin_kegiatan', $request->info_kegiatan)
                    ->where('posisi','Panitia')
                    ->count();


                $kegiatanData =  DB::table('data_perjadinkegiatans')
                    ->where('id', $request->info_kegiatan)
                    ->first();

                $isPenugasan = $kegiatanData->jenis_program == 'Penugasan';


                if ($jumlahPanitia >= $maksPanitia && $request->posisi == 'Panitia' && $maksPanitia != -1) {
                    return redirect()->route('kegiatan_step_2', ['id' => $request->info_kegiatan])
                    ->with(['error' => 'Maksimal Panitia ada 10% dari peserta yaitu: '. $maksPanitia. ' orang']);
                } else {
                    // Ubah nilai NIP_NIK, golongan, dan pangkat menjadi '-' jika tidak diisi
                    $request->NIP_NIK = $request->NIP_NIK ?: '-';
                    $request->golongan = $request->golongan ?: '-';
                    $request->pangkat = $request->pangkat ?: '-';
                    $request->npwp = $request->npwp ?: '-';
                    $request->no_rekening = $request->no_rekening ?: '-';
                    $request->email = $request->email ?: '-';
                    $request->nama_rekening = $request->nama_rekening ?: '-';

                    // dd($request->all());


                    if ($request->nama_lengkap && $request->NIP_NIK) {
                        // Cek apakah NIP_NIK bukan '-'
                        if ($request->NIP_NIK !== '-') {
                            // Jika NIP_NIK bukan '-', periksa apakah sudah ada di database
                            $existingNonPegawai = DB::table('non_pegawais')
                                ->where('NIP_NIK', $request->NIP_NIK)
                                ->first();

                            if ($existingNonPegawai) {
                                // Jika ada, kembalikan error
                                return redirect()->back()->withErrors(['NIP_NIK' => 'NIP/NIK sudah terdaftar.']);
                            }
                        }

                        // Lakukan penyisipan
                        DB::table('non_pegawais')->insert([
                            'NIP_NIK' => $request->NIP_NIK,
                            'nama_lengkap' => $request->nama_lengkap,
                            'golongan' => $request->golongan,
                            'pangkat' => $request->pangkat,
                            'status' => $request->status,
                            'email' => $request->email,
                            'npwp' => $request->npwp,
                            'bank' => $request->bank,
                            'no_rekening' => $request->no_rekening,
                            'nama_rekening' => $request->nama_rekening,
                            'is_aktif' => 1,
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                        $id_non_pegawai_new = DB::table('non_pegawais')->max('id');
                }


                // Lanjutkan proses seperti sebelumnya
                $non_pegawai_tersedia = $request->peserta_non_pegawai ?: $id_non_pegawai_new;

                if ($non_pegawai_tersedia) {
                    $exists = DB::table('fasilitas')
                        ->where('data_perjadinkegiatan_id', $request->info_kegiatan)
                        ->where('nama_fasilitas', $request->posisi)
                        ->exists();

                    if (!$exists) {
                        DB::table('fasilitas')->insert([
                            'data_perjadinkegiatan_id' => $request->info_kegiatan,
                            'nama_fasilitas' => $request->posisi,
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                    }

                    $fasilitasIdNonPegawai = DB::table('fasilitas')
                        ->where('data_perjadinkegiatan_id', $request->info_kegiatan)
                        ->where('nama_fasilitas', $request->posisi)
                        ->value('id');



                    DB::table('perangkat_acaras')->insertOrIgnore([
                        'non_pegawai_id' => $non_pegawai_tersedia,
                        'data_perjadin_kegiatan' => $request->info_kegiatan,
                        'posisi' => $request->posisi,
                        'sebagai' => $request->penugasan,
                        'status' => 'Diproses',
                        'detail_satuan' => $request->detail_satuan,
                        'satuan' => $request->satuan,
                        'tgl_mulai' => $request->mulai,
                        'tgl_selesai' => $request->selesai,


                        'fasilitas_id' => $fasilitasIdNonPegawai,
                        'created_at' => now()->format('Y-m-d H:i:s'),
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);

                    $maxPerangkat = DB::table('perangkat_acaras')->max('id');

                    DB::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                        'data_perjadinkegiatan' => $request->info_kegiatan,
                        'kode' => 'honor',
                        'perangkat_acara' => $maxPerangkat,
                        'created_at' => now()->format('Y-m-d H:i:s'),
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ]);

                    if ($request->posisi == 'Panitia') {
                        DB::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                            'data_perjadinkegiatan' => $request->info_kegiatan,
                            'kode' => 'harian',
                            'perangkat_acara' => $maxPerangkat,
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                    }
                }

                if (!$isPenugasan) {
                    return redirect()->route('kegiatan_step_2', [
                        'id' => $request->info_kegiatan,

                        ])->with('success', 'Data berhasil ditambahkan!');
                } else {
                    return redirect()->route('kegiatan_penugasan_step_2', [
                        'id' => $request->info_kegiatan,

                        ])->with('success', 'Data berhasil ditambahkan!');
                }


            }
        }

    public function storePesertaDetail(Request $request)
    {
        db::table('perangkat_acaras')->insertOrIgnore([
            'pegawai_id' => $request->peserta_pegawai,
            'sebagai' => $request->sebagai,
            'status' => 'Diproses',
            'detail_satuan' => $request->detail_satuan,
            'satuan' => $request->satuan,
            'fasilitas_id' => $request->id_fasilitas,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $maxPerangkat = Perangkat_acara::max('id');

        db::table('keuangan_perjadinkegiatans')->insertOrIgnore([
            'data_perjadinkegiatan' => $request->kegiatanId,
            'perangkat_acara' => $maxPerangkat,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        return redirect()->route('detail', ['id' => $request->kegiatanId])->with('success', 'Data berhasil ditambahkan!');
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
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

            $maxPerangkat = Perangkat_acara::max('id');

            db::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                'data_perjadinkegiatan' => $request->kegiatanId,
                'perangkat_acara' => $maxPerangkat,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        if ($request->nama_lengkap != null) {
            DB::table('non_pegawais')->insertOrIgnore([
                'NIP_NIK' => $request->NIP_NIK,
                'nama_lengkap' => $request->nama_lengkap,
                'golongan' => $request->golongan,
                'pangkat' => $request->pangkat,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

            $id_non_pegawai_new = Non_pegawai::max('id'); // mengambil id non pegawai yang baru diinput

            db::table('perangkat_acaras')->insertOrIgnore([
                'non_pegawai_id' => $id_non_pegawai_new,
                'sebagai' => $request->sebagai,
                'status' => 'Menunggu Persetujuan',
                'detail_satuan' => $request->detail_satuan,
                'satuan' => $request->satuan,
                'fasilitas_id' => $request->fasilitasIdNonPegawai,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

            $maxPerangkat = Perangkat_acara::max('id');

            db::table('keuangan_perjadinkegiatans')->insertOrIgnore([
                'data_perjadinkegiatan' => $request->kegiatanId,
                'perangkat_acara' => $maxPerangkat,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
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
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $opsMax = Operasional::max('id');

        db::table('keuangan_perjadinkegiatans')->insertOrIgnore([
            'data_perjadinkegiatan' => $request->kegiatanId,
            'operasional' => $opsMax,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
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
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $opsMax = Operasional::max('id');

        db::table('keuangan_perjadinkegiatans')->insertOrIgnore([
            'data_perjadinkegiatan' => $request->kegiatanId,
            'operasional' => $opsMax,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        return redirect()->route('detail', ['id' => $request->kegiatanId])->with('success', 'Data berhasil ditambahkan!');
    }

    public function storeMobilitas(Request $request)
    {
        $versi = Versi::where('status', 'aktif')->first(); // Ambil versi aktif

        $dataKegiatan = DB::table('data_perjadinkegiatans')
            ->where('id',$request->kegiatanId)
            ->first();

            // Insert data ke tabel mobilitas_perjadinkegiatans
            DB::table('mobilitas_perjadinkegiatans')->insertOrIgnore([
                'mobilitas' => $request->mobilitas,
                'tujuan_penggunaan' => $request->tujuan,
                'tgl_mulai' => $dataKegiatan->tgl_mulai,
                'tgl_selesai' => $dataKegiatan->tgl_selesai,
                'data_perjadinkegiatan' => $request->kegiatanId,
                'status' => 'pengajuan',
                'unit' => 0,
                'versi_id' => $versi->id,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

            // Ambil ID maksimum setelah semua data diinsert
            // $maxMobilitas = Mobilitas_perjadinkegiatan::max('id');

            // DB::table('peminjaman_kendaraan_dinas')->insertOrIgnore([
            //     'mobilitas_perjadinkegiatan' => $maxMobilitas,
            //     'ket_mobilitas' => $request->tujuan,
            //     'tgl_keberangkatan' => $request->tgl_digunakan,
            //     'tgl_selesai' => $request->tgl_selesai,
            //     'status' => 'pengajuan',
            //     'created_at' => now()->format('Y-m-d H:i:s'),
            //     'updated_at' => now()->format('Y-m-d H:i:s'),
            // ]);

            $kegiatanId = $request->kegiatanId;

            $kegiatanData =  DB::table('data_perjadinkegiatans')
                ->where('id', $kegiatanId)
                ->first();

            $isPenugasan = $kegiatanData->jenis_program == 'Penugasan';

            if (!$isPenugasan) {
                return redirect()->route('kegiatan_step_2', ['id' => $request->kegiatanId])->with('success', 'Data berhasil ditambahkan!');
            } else {
                return redirect()->route('kegiatan_penugasan_step_2', ['id' => $request->kegiatanId])->with('success', 'Data berhasil ditambahkan!');
            }
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
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $maxMibilitas = Mobilitas_perjadinkegiatan::max('id');

        db::table('peminjaman_kendaraan_dinas')->insertOrIgnore([
            'mobilitas_perjadinkegiatan' => $maxMibilitas,
            'ket_mobilitas' => $request->tujuan,
            'tgl_keberangkatan' => $request->tgl_digunakan,
            'tgl_selesai' => $request->tgl_selesai,
            'status' => 'pengajuan',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
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
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        db::table('assets')
            ->where('id', $request->sapras)
            ->update([
                'status_peminjaman' => 'pengajuan',
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

        return redirect()->route('kegiatan_step_2', ['id' => $request->kegiatanId])->with('success', 'Data berhasil ditambahkan!');
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
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        db::table('assets')
            ->where('id', $request->sapras)
            ->update([
                'status_peminjaman' => 'pengajuan',
                'updated_at' => now()->format('Y-m-d H:i:s'),
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
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),

        ]);

        $kegiatanId = $request->kegiatanId;

        $kegiatanData =  DB::table('data_perjadinkegiatans')
            ->where('id', $kegiatanId)
            ->first();

        $isPenugasan = $kegiatanData->jenis_program == 'Penugasan';

        if (!$isPenugasan) {
            return redirect()->route('kegiatan_step_2', ['id' => $request->kegiatanId])->with('success', 'Data berhasil ditambahkan!');
        } else {
            return redirect()->route('kegiatan_penugasan_step_2', ['id' => $request->kegiatanId])->with('success', 'Data berhasil ditambahkan!');
        }
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
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        return redirect()->route('detail', ['id' => $request->kegiatanId, 'tab' => 'dokumen'])
        ->with('success', 'Data berhasil ditambahkan!');
    }

    public function storeKegiatanAll(Request $request, $id)
    {
        $request->validate([
            'surtug' => 'required|boolean', 
        ]);
        
        // Cek apakah dokumen pendukung telah dimasukkan
        $cekDokumen = DB::table('laporan_perjadinkegiatans')->where('data_perjadin_kegiatan', $id)->get();
        $cekPanitia = DB::table('perangkat_acaras')->where('data_perjadin_kegiatan', $id)->get();
        $cekMobilitas = DB::table('mobilitas_perjadinkegiatans')->where('data_perjadinkegiatan', $id)->get();
        $cekAset = DB::table('peminjaman_sarpras')->where('data_perjadinkegiatan', $id)->get();
        $kegiatanData = DB::table('data_perjadinkegiatans')->where('id', $id)->first();
        $isPenugasan = $kegiatanData->jenis_program == 'Penugasan';
    
        // Validasi dokumen dan kepanitiaan
        if (!$isPenugasan) {
            if ($cekDokumen->isEmpty()) {
                return redirect()->route('kegiatan_step_2', ['id' => $id])->with(['error' => 'Lengkapi Setidaknya Satu Dokumen Pendukung']);
            }
    
            if ($cekPanitia->isEmpty()) {
                return redirect()->route('kegiatan_step_2', ['id' => $id])->with(['error' => 'Lengkapi Kepanitiaan Kegiatan']);
            }
        } else {
            if ($cekDokumen->isEmpty()) {
                return redirect()->route('kegiatan_penugasan_step_2', ['id' => $id])->with(['error' => 'Lengkapi Setidaknya Satu Dokumen Pendukung']);
            }
    
            if ($cekPanitia->isEmpty()) {
                return redirect()->route('kegiatan_penugasan_step_2', ['id' => $id])->with(['error' => 'Lengkapi Kepanitiaan Kegiatan']);
            }
        }
    
      
        $surtug = $request->input('surtug');
        
        // Menentukan status dan tindakan berdasarkan nilai surtug
        if ($surtug) {
            // Jika surtug true, kita cek mobilitas
            if ($cekMobilitas->isNotEmpty()) {
                // Ada mobilitas, set ke 'pengajuan' BMN
                $statusPengajuanDetail = 'Verifikasi-BMN';
                $isAcceptBMN = 'pengajuan';
                $isAcceptHKT = null; // Tidak perlu set HKT
            } else {
                // Jika tidak ada mobilitas, langsung ke HKT
                $statusPengajuanDetail = 'Verifikasi-HKT';
                $isAcceptBMN = null; // Tidak perlu set BMN
                $isAcceptHKT = 'pengajuan';
            }
            // Set status umum
            $statusPengajuan = 'pengajuan';
            $status = 'pengajuan';
        } else {
            // Jika surtug false, langsung ke Bendahara
            $statusPengajuanDetail = 'Approval-1-Bendahara';
            $isAcceptBMN = null;  // Tidak perlu set BMN
            $isAcceptHKT = null;  // Tidak perlu set HKT
            $isAcceptBend = 'approval-1';
            $statusPengajuan = 'pengajuan'; // Status pengajuan untuk bendahara
            $status = 'pengajuan';
        }
        
        // Menentukan logika untuk pembaruan berdasarkan kondisi mobilitas dan aset
        $dataToUpdate = [
            'is_acceptBMN' => $isAcceptBMN ?? '-',
            'is_acceptAset' => $cekAset->isNotEmpty() ? 'pengajuan' : '-',
            'is_acceptHKT' => $isAcceptHKT ?? '-',
            'is_acceptBend' => $isAcceptBend ?? null,  // Set jika surtug false
            'status' => $status,
            'status_pengajuan' => $statusPengajuan,
            'status_pengajuan_detail' => $statusPengajuanDetail,
            'jumlah_kamar' => $request->jumlah_kamar,
            'tambah_penginapan' => $request->tambah_penginapan,
            'jumlah_peserta' => $request->jumlah_peserta,
            'jumlah_kepanitiaan' => $request->jumlah_kepanitiaan,
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ];
        
        // Update data perjadinkegiatans
        DB::table('data_perjadinkegiatans')->where('id', $id)->update($dataToUpdate);
        
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

    public function updateLamaKegiatan(Request $request, $id)
    {
        $cekDokumen = Laporan_perjadinkegiatan::where('data_perjadin_kegiatan', $id)->get();
        if ($cekDokumen == null) {
            return redirect()->route('kegiatan_step_2', ['id' => $request->kegiatanId])->with('success', 'Dokumen pendukung belum dimasukkan. Masukkan surat tugas atau 1 dokumen pendukung lainnya!');
        }

        DB::table('data_perjadinkegiatans')
            ->where('id', $id)
            ->update([
                'jenis_kegiatan' => $request->jenis_kegiatan,
                'provinsi' => $request->provinsi,
                'kab_kota' => $request->kab_kota,
                'alamat' => $request->alamat,
                'jenis_program' => $request->jenis_program,
                'jumlah_kapasitas' => $request->jumlah_kapasitas,
                'jumlah_kamar' => $request->jumlah_kamar,
                'tambah_penginapan' => $request->tambah_penginapan,
                'layout_ruangan' => $request->layout_ruangan,
                'ket_kegiatan' => $request->ket_kegiatan,
                'is_acceptKeu' => 'verifikasi-1',
                'status' => 'pengajuan',
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);





        return redirect()->route('riwayat-kegiatan', ['status' => 'pengajuan'])->with('success', 'Program berhasil dibuat. Silakan tunggu persetujuan dari Keuangan!');
    }

    public function updateKegiatanDetail(Request $request, $id)
    {

        $kegiatan = Data_perjadinkegiatan::find($id);

        if ($kegiatan->status_pengajuan == 'pelaporan') {
            DB::table('data_perjadinkegiatans')
                ->where('id', $id)
                ->update([
                    'status' => 'selesai',
                    'status_pengajuan' => 'selesai',
                    'status_pengajuan_detail' => 'Verifikasi-Keuangan',
                    'is_acceptKeu' => 'verifikasi-2',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

            return redirect()->route('riwayat-kegiatan', ['status' => 'selesai'])->with('success', 'Program telah diperbaharui!, Silahkan tunggu persetujuan dari keuangan!');
        }

        if (($kegiatan->status_pengajuan == 'revisi') and ($kegiatan->is_acceptKeu == 'revisi')) {
            DB::table('data_perjadinkegiatans')
                ->where('id', $id)
                ->update([
                    'status' => 'selesai',
                    'status_pengajuan' => 'selesai',
                    'status_pengajuan_detail' => 'Verifikasi-revisi-keuangan',
                    'is_acceptKeu' => 'verifikasi-2',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

            return redirect()->route('riwayat-kegiatan', ['status' => 'proses'])->with('success', 'Program telah diperbaharui!, Silahkan tunggu persetujuan dari keuangan!');
        }

        return redirect()->route('riwayat-kegiatan', ['status' => 'pengajuan'])->with('success', 'Program telah diperbaharui!, Silahkan tunggu persetujuan dari keuangan!');
    }

    public function getKegiatanData($id)
    {
        $jumlahPeserta = DB::table('data_perjadinkegiatans')
                ->where('id',$id)
                ->select('jumlah_peserta')
                ->first();

        $jumlahPeserta = $jumlahPeserta->jumlah_peserta ?? -1;

        if ($jumlahPeserta > 0) {
            $maksPanitia = round($jumlahPeserta * 0.10);
        } else {
            $maksPanitia = -1;
        }
        // if ($jumlahKepanitiaan >= $batasKepanitiaan) {
        //     return redirect()->back()->withErrors('Jumlah kepanitiaan tidak boleh lebih dari 10% dari jumlah peserta.');
        // }

        // Cek jika jumlah_peserta null, ganti dengan -1
        return response()->json([
            'jumlah_peserta'=>$jumlahPeserta,
            'maks_panitia' => $maksPanitia
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */

     public function destroy($id)
    {
        try {
            // Hapus tabel yang berelasi dulu sebelum menghapus data utama
            Perangkat_acara::where('data_perjadin_kegiatan', $id)->delete();
            Laporan_perjadinkegiatan::where('data_perjadin_kegiatan', $id)->delete();
            Peminjaman_sarpras::where('data_perjadinkegiatan', $id)->delete();
            
            // Cek apakah Mobilitas_perjadinkegiatan memiliki data
            $mobilitasIds = Mobilitas_perjadinkegiatan::where('data_perjadinkegiatan', $id)->pluck('id')->toArray();
            // dd($mobilitasIds);
            if (!empty($mobilitasIds)) {
                Operasional::whereIn('data_perjadin_kegiatan', $mobilitasIds)->delete(); // Pastikan kolomnya benar
                Mobilitas_perjadinkegiatan::where('data_perjadinkegiatan', $id)->delete();
            }

            // Cek apakah Keuangan_perjadinkegiatan memiliki kebutuhan_id
            $kebutuhanIds = Keuangan_perjadinkegiatan::where('data_perjadinkegiatan', $id)
                ->whereNotNull('kebutuhan_id')
                ->pluck('kebutuhan_id')
                ->toArray();
            if (!empty($kebutuhanIds)) {
                Kebutuhan::whereIn('id', $kebutuhanIds)->delete();
            }

            // Hapus Keuangan_perjadinkegiatan
            Keuangan_perjadinkegiatan::where('data_perjadinkegiatan', $id)->delete();

            // Hapus data utama, pastikan ada sebelum menghapus
            $dataPerjadin = Data_perjadinkegiatan::find($id);
            if ($dataPerjadin) {
                $dataPerjadin->delete();
            }

            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

     

    public function destroyPesertaKegiatan(Request $request, $id)
    {

        $kegiatanData =  DB::table('data_perjadinkegiatans')
        ->where('id', $request->kegiatanId)
        ->first();

        $isPenugasan = $kegiatanData->jenis_program == 'Penugasan';
        // Perangkat_acara::where('id', $id)->delete();

        Keuangan_perjadinkegiatan::where('perangkat_acara', $id)->delete();
        Perangkat_acara::destroy($id);

        if (!$isPenugasan) {
            return redirect()->route('kegiatan_step_2', ['id' => $request->kegiatanId])->with('success', 'Data Telah Dihapus!');
        } else {
            return redirect()->route('kegiatan_penugasan_step_2', ['id' => $request->kegiatanId])->with('success', 'Data Telah Dihapus!');
        }
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

        $cekMobilitas = DB::table('mobilitas_perjadinkegiatans')->where('data_perjadinkegiatan', $kegiatanId)->get();

        if ($cekMobilitas->isEmpty()) {
            // Jika $cekMobilitas dan $cekAset tidak ada
            DB::table('data_perjadinkegiatans')->where('id', $kegiatanId)->update([
                'is_acceptBMN' => '-',
                'status_pengajuan_detail' => 'Edit-pengajuan',
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        $kegiatanData =  DB::table('data_perjadinkegiatans')
            ->where('id', $kegiatanId)
            ->first();

        $isPenugasan = $kegiatanData->jenis_program == 'Penugasan';

        if (!$isPenugasan) {
            return redirect()->route('kegiatan_step_2', ['id' => $kegiatanId])->with('success', 'Mobilitas berhasil dihapus!');
        } else {
            return redirect()->route('kegiatan_penugasan_step_2', ['id' => $kegiatanId])->with('success', 'Mobilitas berhasil dihapus!');
        }

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
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

        $cekAset = DB::table('peminjaman_sarpras')->where('data_perjadinkegiatan', $kegiatanId)->get();


        if ($cekAset->isEmpty()) {
            // Jika $cekMobilitas dan $cekAset tidak ada
            DB::table('data_perjadinkegiatans')->where('id', $kegiatanId)->update([
                'is_acceptAset' => '-',
                'status_pengajuan_detail' => 'Edit-pengajuan',
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }
        return redirect()->route('kegiatan_step_2', ['id' => $kegiatanId])->with('success', 'Sapras berhasil dihapus!');
    }

    public function destroySaprasKegiatanDetail(Request $request, $id)
    {
        Peminjaman_sarpras::destroy($id);
        $kegiatanId = $request->kegiatanId;
        db::table('assets')
            ->where('id', $request->idAsset)
            ->update([
                'status_peminjaman' => 'Tidak Dipakai',
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        return redirect()->route('detail', ['id' => $kegiatanId])->with('success', 'Sapras berhasil dihapus!');
    }

    public function destroyDokumenKegiatan(Request $request, $id)
    {
        $data = Laporan_perjadinkegiatan::find($id);
        Storage::delete($data->file);
        $data->delete();
        $kegiatanId = $request->kegiatanId;

        $kegiatanData =  DB::table('data_perjadinkegiatans')
            ->where('id', $kegiatanId)
            ->first();

        $isPenugasan = $kegiatanData->jenis_program == 'Penugasan';

        if (!$isPenugasan) {
            return redirect()->route('kegiatan_step_2', ['id' => $kegiatanId])->with('success', 'Dokumen berhasil dihapus!');
        } else {
            return redirect()->route('kegiatan_penugasan_step_2', ['id' => $kegiatanId])->with('success', 'Dokumen berhasil dihapus!');
        }
    }


    public function destroyDokumenKegiatanDetail(Request $request, $id)
    {
        $data = Laporan_perjadinkegiatan::find($id);
        Storage::delete($data->file);
        $data->delete();
        $kegiatanId = $request->kegiatanId;

        return redirect()->route('detail', ['id' => $kegiatanId, 'tab' => 'dokumen'])->with('success', 'Dokumen berhasil dihapus!');
    }

    // public function getDokumen($filename){
    //     $path = storage_path('app/public/dokumen-kegiatans/' . $filename);
    //     return response()->file($path);
    // }


    public function getDokumen($filename)
    {
        $path = storage_path('app/public/dokumen-kegiatans/' . $filename);
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->file($path);
    }

    public function getTemplateDokumen($filename)
    {
        $path = storage_path('app/public/dokumens-template/' . $filename);
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->download($path);
    }
    public function ajukanUlang(Request $request, $id)
    {

        $existingKegiatan = DB::table('data_perjadinkegiatans')->where('id', $id)->first();


        $newId = DB::table('data_perjadinkegiatans')->insertGetId([
            'id_pengaju' => auth('pegawai')->user()->id,
            'uraian' => $request->uraian ?? $existingKegiatan->uraian,
            'program_kerja' => $request->program_kerja ?? $existingKegiatan->program_kerja,
            'nama_kegiatan' => $request->nama_kegiatan ?? $existingKegiatan->nama_kegiatan,
            'id_iku' => $request->kode_iku ?? $existingKegiatan->id_iku,
            'status' => 'Draf-pengajuan',
            'status_pengajuan_detail' => 'Pengisian-Step2',
            'jumlah_kepanitiaan' => $request->jumlah_kepanitiaan ?? $existingKegiatan->jumlah_kepanitiaan,
            'jumlah_peserta' => $request->jumlah_peserta ?? $existingKegiatan->jumlah_peserta,
            'jumlah_kamar' => $request->jumlah_kamar ?? $existingKegiatan->jumlah_kamar,
            'tambah_penginapan' => $request->tambah_penginapan ?? $existingKegiatan->tambah_penginapan,
            'tgl_mulai' => $request->tgl_mulai ?? $existingKegiatan->tgl_mulai,
            'tempat_kegiatan' => $request->tempat_kegiatan ?? $existingKegiatan->tempat_kegiatan,
            'tgl_selesai' => $request->tgl_selesai ?? $existingKegiatan->tgl_selesai,
            'jenis_kegiatan' => $request->jenis_kegiatan ?? $existingKegiatan->jenis_kegiatan,
            'provinsi' => $request->provinsi ?? $existingKegiatan->provinsi,
            'kab_kota' => $request->kab_kota ?? $existingKegiatan->kab_kota,
            'alamat' => $request->alamat ?? $existingKegiatan->alamat,
            'jenis_program' => $request->jenis_program ?? $existingKegiatan->jenis_program,
            'versi_id' => $request->versi_id ?? $existingKegiatan->versi_id,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        
        $newKegiatan = Data_perjadinkegiatan::find($newId);
        $isPenugasan = $newKegiatan->jenis_program == 'Penugasan';
       

          // Nonaktifkan ONLY_FULL_GROUP_BY
          DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");

            
        $oldPeserta = DB::table('perangkat_acaras')
            ->join('fasilitas', 'perangkat_acaras.fasilitas_id', '=', 'fasilitas.id')
            ->leftJoin('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
            ->leftJoin('non_pegawais', 'perangkat_acaras.non_pegawai_id', '=', 'non_pegawais.id')
            ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
            ->select(
                DB::raw('COALESCE(pegawais.nama_lengkap, non_pegawais.nama_lengkap) AS nama'),
                'perangkat_acaras.id as idPerangkat',
                'perangkat_acaras.*',
                'keuangan_perjadinkegiatans.*',
                'fasilitas.nama_fasilitas'
            )
            ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
            ->groupBy('perangkat_acaras.id')
            ->get();
            // Kembalikan ONLY_FULL_GROUP_BY ke default
            DB::statement("SET SESSION sql_mode=CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY')");
        // dd($oldPeserta);

        // dd($oldPeserta);

        foreach ($oldPeserta as $peserta) {
            if ($peserta->posisi == 'Supir') {
            continue; // Skip the iteration if the position is 'Supir'
            }
        $oldFasilitas = DB::table('fasilitas')
            ->where('id', $peserta->fasilitas_id)
            ->first();

        // Cek apakah fasilitas dengan nama_fasilitas yang sama sudah ada untuk data_perjadinkegiatan_id baru
        $existingFasilitas = DB::table('fasilitas')
            ->where('data_perjadinkegiatan_id', $newId)
            ->where('nama_fasilitas', $oldFasilitas->nama_fasilitas)
            ->first();

        if ($existingFasilitas) {
            // Jika sudah ada, ambil ID-nya
            $newFasilitasId = $existingFasilitas->id;
        } else {
            // Jika belum ada, insert dan ambil ID baru
            $newFasilitasId = DB::table('fasilitas')->insertGetId([
            'data_perjadinkegiatan_id' => $newId,
            'nama_fasilitas' => $oldFasilitas->nama_fasilitas,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        $newDataPerjadinId = DB::table('perangkat_acaras')->insertGetId([
            'data_perjadin_kegiatan' => $newId,
            'pegawai_id' => $peserta->pegawai_id,
            'non_pegawai_id' => $peserta->non_pegawai_id,
            'posisi' => $peserta->posisi,
            'sebagai' => $peserta->sebagai,
            'fasilitas_id' => $newFasilitasId,
            'satuan' => $peserta->satuan,
            'detail_satuan' => $peserta->detail_satuan,
            'status' => 'Diproses',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        if ($peserta->posisi == 'Panitia') {

            if (!$isPenugasan) {
                DB::table('keuangan_perjadinkegiatans')->insert([
                    'data_perjadinkegiatan' => $newId,
                    'perangkat_acara' => $newDataPerjadinId,
                    'kode' => 'honor',
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);
            }
            DB::table('keuangan_perjadinkegiatans')->insert([
                'data_perjadinkegiatan' => $newId,
                'perangkat_acara' => $newDataPerjadinId,
                'kode' => 'harian',
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        } elseif ($peserta->posisi == 'Supir') {
            DB::table('keuangan_perjadinkegiatans')->insert([
                'data_perjadinkegiatan' => $newId,
                'perangkat_acara' => $newDataPerjadinId,
                'kode' => null,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        } else {
            DB::table('keuangan_perjadinkegiatans')->insert([
                'data_perjadinkegiatan' => $newId,
                'perangkat_acara' => $newDataPerjadinId,
                'kode' => 'honor',
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }
        $pesertaMapping[] = [
            'perangkat_old_id' => $peserta->idPerangkat,
            'perangkat_new_id' => $newDataPerjadinId
        ];
    }
        // dd($pesertaMapping);

        $oldMobilitas = DB::table('mobilitas_perjadinkegiatans')
        ->where('data_perjadinkegiatan', $id)
        ->get();

        foreach ($oldMobilitas as $mobilitas) {
            DB::table('mobilitas_perjadinkegiatans')->insertGetId([
                'mobilitas' => $mobilitas->mobilitas,
                'tujuan_penggunaan' => $mobilitas->tujuan_penggunaan,
                'tgl_mulai' => $mobilitas->tgl_mulai,
                'tgl_selesai' => $mobilitas->tgl_selesai,
                'unit' => $mobilitas->unit,
                'status' => 'pengajuan',
                'data_perjadinkegiatan' => $newId,
                'versi_id' => $mobilitas->versi_id,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

        }
        $oldKebutuhan = DB::table('keuangan_perjadinkegiatans')
        ->join('kebutuhans', 'keuangan_perjadinkegiatans.kebutuhan_id', '=', 'kebutuhans.id')
        ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
        ->select('kebutuhans.*', 'keuangan_perjadinkegiatans.*')
        ->get();

    foreach ($oldKebutuhan as $kebutuhan) {

        $matchingPeserta = collect($pesertaMapping)->firstWhere('perangkat_old_id', $kebutuhan->perangkat_acara);
        $idPerangkat_new = $matchingPeserta['perangkat_new_id'] ?? null;
        
        // dd($idPerangkat_new, $kebutuhan);
        
        $newKebutuhanId = DB::table('kebutuhans')->insertGetId([
            'nama' => $kebutuhan->nama,
            'jumlah_frekuensi' => $kebutuhan->jumlah_frekuensi,
            'satuan' => $kebutuhan->satuan,
            'tipe_pendanaan' => $kebutuhan->tipe_pendanaan,
            'ket' => $kebutuhan->ket,
            'status' => 'Pengajuan',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('keuangan_perjadinkegiatans')->insertOrIgnore([
            'data_perjadinkegiatan' => $newId,
            'kebutuhan_id' => $newKebutuhanId,
            'perangkat_acara' => $idPerangkat_new,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);
    }


    $oldSapras = DB::table('peminjaman_sarpras')
        ->where('data_perjadinkegiatan',$id)
        ->get();

    foreach ($oldSapras as $sapras) {

        DB::table('peminjaman_sarpras')->insertGetId([
            'jumlah_asset' => $sapras->jumlah_asset,
            'tgl_peminjaman' => $sapras->tgl_peminjaman,
            'tgl_pengembalian' => $sapras->tgl_pengembalian,
            'data_perjadinkegiatan' => $newId,
            'pegawai_id' => $sapras->pegawai_id,
            'asset' => $sapras->asset,
            'status' => 'pengajuan',
            'keterangan' => $sapras->keterangan,
            'versi_id' => $sapras->versi_id,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);
    }

    $oldDokumen = DB::table('laporan_perjadinkegiatans')
    ->where('data_perjadin_kegiatan', $id)
    ->get();

        foreach ($oldDokumen as $dokumen) {
            if (in_array($dokumen->nama_dokumen, ['Surat Tugas '.$id, 'RPD Kegiatan '.$id, 'lap-'.$id.'-'.$id.'-'.$id])) {
                continue; // Skip these specific documents
            }
            DB::table('laporan_perjadinkegiatans')->insertGetId([
                'nama_dokumen' => $dokumen->nama_dokumen,
                'file' => $dokumen->file,
                'status' => 'diajukan',
                'data_perjadin_kegiatan' => $newId,
                'keterangan' => $dokumen->keterangan,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }


        if ($isPenugasan) {
            return redirect()->route('kegiatan_penugasan_step_2', ['id' => $newId])
                ->with('success', 'Permohonan kegiatan berhasil diajukan ulang. Silakan isi detail kegiatan!');
        } else {
            return redirect()->route('kegiatan_step_2', ['id' => $newId])
                ->with('success', 'Permohonan kegiatan berhasil diajukan ulang. Silakan isi detail kegiatan!');
        }
    }
    
    public function indexAjukan($id)
    {

        // Panggil fungsi getData() untuk mendapatkan data kegiatan
        $getData = Ref_ss_iku_programkerja::all();

        $kode_iku = DB::table('data_perjadinkegiatans')
        ->select('data_perjadinkegiatans.id_iku')
        ->where('data_perjadinkegiatans.id', $id)
        ->first();
        $riwayatKegiatans = DB::table('data_perjadinkegiatans')
        ->leftJoin('perangkat_acaras', 'data_perjadinkegiatans.id', '=', 'perangkat_acaras.data_perjadin_kegiatan')
        ->leftJoin('pegawais', 'perangkat_acaras.pegawai_id', '=', 'pegawais.id')
        ->select(
            'data_perjadinkegiatans.id as idKegiatan',
            DB::raw('MAX(data_perjadinkegiatans.nama_kegiatan) as nama_kegiatan'),
            DB::raw('MAX(data_perjadinkegiatans.jenis_kegiatan) as jenis_kegiatan'),
            DB::raw('MAX(data_perjadinkegiatans.jenis_program) as jenis_program'),
            DB::raw('MAX(data_perjadinkegiatans.tgl_mulai) as tgl_mulai'),
            DB::raw('MAX(data_perjadinkegiatans.status_pengajuan_detail) as status'),
            DB::raw('MAX(data_perjadinkegiatans.status_pengajuan) as status_pengajuan'),
            DB::raw('MAX(data_perjadinkegiatans.status_pengajuan_detail) as status_pengajuan_detail'),
            DB::raw('MAX(data_perjadinkegiatans.id_pengaju) as id_pengaju'),
            DB::raw('MAX(data_perjadinkegiatans.alasan_penolakan) as alasan_penolakan'),
            DB::raw('MAX(pegawais.nama_lengkap) as nama_lengkap')
        );
        DB::table('data_perjadinkegiatans')
        ->where('id',$id)
        ->update([
            'status' => 'Draf-pengajuan',
            'status_pengajuan_detail' => 'ditolak',
        ]);

        // Pastikan $kode_iku tidak null sebelum menggunakannya
        if ($kode_iku) {
            $id_iku_string = (string) $kode_iku->id_iku;

            // Mengambil nama_iku berdasarkan id_iku
            $indikator = DB::table('data_perjadinkegiatans')
                ->join('ref_ss_iku_programkerjas', 'data_perjadinkegiatans.id_iku', '=', 'ref_ss_iku_programkerjas.kode_iku')
                ->where('data_perjadinkegiatans.id_iku', $id_iku_string)
                ->select('ref_ss_iku_programkerjas.nama_iku')
                ->limit(1)
                ->first(); // Ambil satu hasil pertama
        }


        return view('user.kegiatan.ajukan', [
            'title' => 'Pengajuan Kegiatan',
            'active' => 'perjadin_kegiatan',
            'indikator' => $indikator,
            'ikuresult' => $getData,
            "kegiatan" => Data_perjadinkegiatan::find($id)
        ]);
    }

}
