<?php

namespace App\Http\Controllers;


use Illuminate\Http\JsonResponse;
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

    public function indexEdit($id)
    {
        $perjadin = DB::table('info_perjadinlangsungs')
                    ->where('id',$id)
                    ->first();
        $alamat = $perjadin->alamat;
        $alamatParts = explode(',', $alamat, 2);
        $tempat_kegiatan = trim($alamatParts[0]); // Mengambil bagian sebelum tanda koma
        $alamat_detail = isset($alamatParts[1]) ? trim($alamatParts[1]) : '';
        $tgl_surat = Carbon::parse($perjadin->tanggal_surat)->format('Y-m-d');
        return view('user.perjadin.index_edit', [
            'title' => 'Edit Pengajuan Kegiatan',
            'active' => 'perjadin_biasa',
            'tempat_kegiatan' => $tempat_kegiatan,
            'alamat_detail' => $alamat_detail,
            'tgl_surat' => $tgl_surat,
            "perjadin" => $perjadin
        ]);
    }

    public function indexAjukan($id)
    {
        $perjadin = DB::table('info_perjadinlangsungs')
                    ->where('id',$id)
                    ->first();
        $alamat = $perjadin->alamat;
        $alamatParts = explode(',', $alamat, 2);
        $tempat_kegiatan = trim($alamatParts[0]); // Mengambil bagian sebelum tanda koma
        $alamat_detail = isset($alamatParts[1]) ? trim($alamatParts[1]) : '';
        $tgl_surat = Carbon::parse($perjadin->tanggal_surat)->format('Y-m-d');
        return view('user.perjadin.ajukan-ulang', [
            'title' => 'Ajukan Pengajuan Kegiatan',
            'active' => 'perjadin_biasa',
            'tempat_kegiatan' => $tempat_kegiatan,
            'alamat_detail' => $alamat_detail,
            'tgl_surat' => $tgl_surat,
            "perjadin" => $perjadin
        ]);
    }

    public function step2($id)
    {
        $infoPerjadin = Info_perjadinlangsung::find($id);

    if ($infoPerjadin) {
        $tanggalAwal = Carbon::parse($infoPerjadin->tgl_keberangkatan);
        $tanggalAkhir = Carbon::parse($infoPerjadin->tgl_selesai);

       
    } else {
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
            ->select('kebutuhans.id as idKebutuhan', 'kebutuhans.nama', 'kebutuhans.jumlah_frekuensi', 'kebutuhans.satuan', 'kebutuhans.tipe_pendanaan', 'kebutuhans.ket', 'keuangan_perjadinlangsungs.info_perjadinlangsung',  'keuangan_perjadinlangsungs.kebutuhan_id', 'keuangan_perjadinlangsungs.data_perjadinlangsungs', 'keuangan_perjadinlangsungs.kebutuhan_id', 'keuangan_perjadinlangsungs.status')
            ->where('keuangan_perjadinlangsungs.info_perjadinlangsung', $id)
            ->groupBy('kebutuhans.id', 'kebutuhans.nama', 'kebutuhans.jumlah_frekuensi', 'kebutuhans.satuan', 'kebutuhans.tipe_pendanaan', 'kebutuhans.ket', 'keuangan_perjadinlangsungs.info_perjadinlangsung', 'keuangan_perjadinlangsungs.kebutuhan_id', 'keuangan_perjadinlangsungs.data_perjadinlangsungs', 'keuangan_perjadinlangsungs.status')
            ->get();
            $pegawais = DB::table('pegawais')
            ->select('pegawais.id', 'pegawais.nama_lengkap')
            ->whereNotExists(function ($query) use ($id, $tanggalAwal, $tanggalAkhir) {
               
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
        
              
                $query->orWhereExists(function ($query) use ($tanggalAwal, $tanggalAkhir) {
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
                });
            })
            ->where('pegawais.jabatan_id', '!=', 14)
            ->distinct()
            ->get();
        

        $nonPegawais = DB::table('non_pegawais')
            ->select('non_pegawais.id', 'non_pegawais.nama_lengkap')
            ->whereNotExists(function ($query) use ($id, $tanggalAwal, $tanggalAkhir) {
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
                    $query->orWhereExists(function ($query) use ($tanggalAwal, $tanggalAkhir) {
                        $query->select(DB::raw(1))
                            ->from('perangkat_acaras')
                            ->whereRaw('non_pegawais.id = perangkat_acaras.non_pegawai_id')
                            ->where('perangkat_acaras.status', '!=', 'Ditolak')
                            ->where(function ($subquery) use ($tanggalAwal, $tanggalAkhir) {
                                $subquery->whereBetween('perangkat_acaras.tgl_mulai', [$tanggalAwal, $tanggalAkhir])
                                    ->orWhereBetween('perangkat_acaras.tgl_selesai', [$tanggalAwal, $tanggalAkhir])
                                    ->orWhere(function ($subquery2) use ($tanggalAwal, $tanggalAkhir) {
                                        $subquery2->where('perangkat_acaras.tgl_mulai', '<=', $tanggalAwal)
                                            ->where('perangkat_acaras.tgl_selesai', '>=', $tanggalAkhir);
                                    });
                            });
                    });
                })
            ->where('non_pegawais.id', '!=', 14) // Menyaring hanya non-pegawai dengan ID valid
            ->distinct()
            ->get();

          
        return view(
            'user.perjadin.perjadin_step2',
            [
                'title' => 'Perjalanan Dinas',
                'active' => 'perjadin_biasa',
                "pegawais" =>  $pegawais,
                "nonpegawais" => $nonPegawais,
                "perjadin" => Info_perjadinlangsung::find($id),
                "selectPesertas" => $selectPeserta,
                "selectPesertasNonPegawais" => $selectPeserta_nonPegawai,
                "fasilitas" => $kebutuhans
            ]
        );
    }

    public function riwayat($status = 'semua')
    {
        $userId = auth('pegawai')->user()->id;
        $versiId = session('versi');

        // ==== COUNTS PERJALANAN DINAS ====
        $countDrafPerjadin = DB::table('info_perjadinlangsungs')
            ->where('id_pengaju', $userId)
            ->where('status_pengajuan', 'Draf-pengajuan')
            ->where('versi_id', $versiId)->count();

        $countPengajuanPerjadin = DB::table('info_perjadinlangsungs')
            ->join('data_perjadinlangsungs', 'info_perjadinlangsungs.id', '=', 'data_perjadinlangsungs.info_perjadinlangsung')
            ->where('data_perjadinlangsungs.pegawai_id', $userId)
            ->where('info_perjadinlangsungs.status_pengajuan', 'pengajuan')
            ->where('versi_id', $versiId)->count();

        $countProsesPerjadin = DB::table('info_perjadinlangsungs')
            ->join('data_perjadinlangsungs', 'info_perjadinlangsungs.id', '=', 'data_perjadinlangsungs.info_perjadinlangsung')
            ->where('data_perjadinlangsungs.pegawai_id', $userId)
            ->where('info_perjadinlangsungs.status_pengajuan', 'proses')
            ->where('versi_id', $versiId)->count();

        $countRevisiPerjadin = DB::table('info_perjadinlangsungs')
            ->join('data_perjadinlangsungs', 'info_perjadinlangsungs.id', '=', 'data_perjadinlangsungs.info_perjadinlangsung')
            ->where('data_perjadinlangsungs.pegawai_id', $userId)
            ->where('info_perjadinlangsungs.status_pengajuan', 'revisi')
            ->where('versi_id', $versiId)->count();

        $countDitolakPerjadin = DB::table('info_perjadinlangsungs')
            ->join('data_perjadinlangsungs', 'info_perjadinlangsungs.id', '=', 'data_perjadinlangsungs.info_perjadinlangsung')
            ->where('data_perjadinlangsungs.pegawai_id', $userId)
            ->where('info_perjadinlangsungs.status_pengajuan', 'ditolak')
            ->where('versi_id', $versiId)->count();

        $countPelaporanPerjadin = DB::table('info_perjadinlangsungs')
            ->join('data_perjadinlangsungs', 'info_perjadinlangsungs.id', '=', 'data_perjadinlangsungs.info_perjadinlangsung')
            ->where('data_perjadinlangsungs.pegawai_id', $userId)
            ->where('info_perjadinlangsungs.status_pengajuan', 'pelaporan')
            ->where('versi_id', $versiId)->count();

        $countSelesaiPerjadin = DB::table('info_perjadinlangsungs')
            ->join('data_perjadinlangsungs', 'info_perjadinlangsungs.id', '=', 'data_perjadinlangsungs.info_perjadinlangsung')
            ->where('data_perjadinlangsungs.pegawai_id', $userId)
            ->where('info_perjadinlangsungs.status_pengajuan', 'selesai')
            ->where('versi_id', $versiId)->count();


        // ==== COUNTS PROGRAM KEGIATAN ====
        $countDrafKegiatan = DB::table('data_perjadinkegiatans')
            ->where(function ($query) use ($userId) {
                $query->where('data_perjadinkegiatans.id_pengaju', $userId)
                    ->orWhereExists(function ($q) use ($userId) {
                        $q->select(DB::raw(1))->from('perangkat_acaras')
                          ->whereColumn('perangkat_acaras.data_perjadin_kegiatan', 'data_perjadinkegiatans.id')
                          ->where('perangkat_acaras.pegawai_id', $userId);
                    });
            })
            ->where('data_perjadinkegiatans.versi_id', $versiId)
            ->where(function($q){
                $q->where('data_perjadinkegiatans.status_pengajuan', 'Draf-pengajuan')
                  ->orWhereNull('data_perjadinkegiatans.status_pengajuan');
            })->count();

        $countPengajuanKegiatan = DB::table('data_perjadinkegiatans')
            ->where(function ($query) use ($userId) {
                $query->where('data_perjadinkegiatans.id_pengaju', $userId)
                    ->orWhereExists(function ($q) use ($userId) {
                        $q->select(DB::raw(1))->from('perangkat_acaras')
                          ->whereColumn('perangkat_acaras.data_perjadin_kegiatan', 'data_perjadinkegiatans.id')
                          ->where('perangkat_acaras.pegawai_id', $userId);
                    });
            })
            ->where('data_perjadinkegiatans.versi_id', $versiId)
            ->where('data_perjadinkegiatans.status_pengajuan', 'pengajuan')->count();

        $countProsesKegiatan = DB::table('data_perjadinkegiatans')
            ->where(function ($query) use ($userId) {
                $query->where('data_perjadinkegiatans.id_pengaju', $userId)
                    ->orWhereExists(function ($q) use ($userId) {
                        $q->select(DB::raw(1))->from('perangkat_acaras')
                          ->whereColumn('perangkat_acaras.data_perjadin_kegiatan', 'data_perjadinkegiatans.id')
                          ->where('perangkat_acaras.pegawai_id', $userId);
                    });
            })
            ->where('data_perjadinkegiatans.versi_id', $versiId)
            ->where('data_perjadinkegiatans.status_pengajuan', 'proses')->count();

        $countRevisiKegiatan = DB::table('data_perjadinkegiatans')
            ->where(function ($query) use ($userId) {
                $query->where('data_perjadinkegiatans.id_pengaju', $userId)
                    ->orWhereExists(function ($q) use ($userId) {
                        $q->select(DB::raw(1))->from('perangkat_acaras')
                          ->whereColumn('perangkat_acaras.data_perjadin_kegiatan', 'data_perjadinkegiatans.id')
                          ->where('perangkat_acaras.pegawai_id', $userId);
                    });
            })
            ->where('data_perjadinkegiatans.versi_id', $versiId)
            ->where('data_perjadinkegiatans.status_pengajuan', 'revisi')->count();

        $countDitolakKegiatan = DB::table('data_perjadinkegiatans')
            ->where(function ($query) use ($userId) {
                $query->where('data_perjadinkegiatans.id_pengaju', $userId)
                    ->orWhereExists(function ($q) use ($userId) {
                        $q->select(DB::raw(1))->from('perangkat_acaras')
                          ->whereColumn('perangkat_acaras.data_perjadin_kegiatan', 'data_perjadinkegiatans.id')
                          ->where('perangkat_acaras.pegawai_id', $userId);
                    });
            })
            ->where('data_perjadinkegiatans.versi_id', $versiId)
            ->where('data_perjadinkegiatans.status_pengajuan', 'ditolak')->count();

        $countPelaporanKegiatan = DB::table('data_perjadinkegiatans')
            ->where(function ($query) use ($userId) {
                $query->where('data_perjadinkegiatans.id_pengaju', $userId)
                    ->orWhereExists(function ($q) use ($userId) {
                        $q->select(DB::raw(1))->from('perangkat_acaras')
                          ->whereColumn('perangkat_acaras.data_perjadin_kegiatan', 'data_perjadinkegiatans.id')
                          ->where('perangkat_acaras.pegawai_id', $userId);
                    });
            })
            ->where('data_perjadinkegiatans.versi_id', $versiId)
            ->where('data_perjadinkegiatans.status_pengajuan', 'pelaporan')->count();

        $countSelesaiKegiatan = DB::table('data_perjadinkegiatans')
            ->where(function ($query) use ($userId) {
                $query->where('data_perjadinkegiatans.id_pengaju', $userId)
                    ->orWhereExists(function ($q) use ($userId) {
                        $q->select(DB::raw(1))->from('perangkat_acaras')
                          ->whereColumn('perangkat_acaras.data_perjadin_kegiatan', 'data_perjadinkegiatans.id')
                          ->where('perangkat_acaras.pegawai_id', $userId);
                    });
            })
            ->where('data_perjadinkegiatans.versi_id', $versiId)
            ->where('data_perjadinkegiatans.status_pengajuan', 'selesai')->count();


        // ==== TOTAL COUNTS ====
        $countDraf = $countDrafPerjadin + $countDrafKegiatan;
        $countPengajuan = $countPengajuanPerjadin + $countPengajuanKegiatan;
        $countProses = $countProsesPerjadin + $countProsesKegiatan;
        $countRevisi = $countRevisiPerjadin + $countRevisiKegiatan;
        $countDitolak = $countDitolakPerjadin + $countDitolakKegiatan;
        $countPelaporan = $countPelaporanPerjadin + $countPelaporanKegiatan;
        $countSelesai = $countSelesaiPerjadin + $countSelesaiKegiatan;

        // ==== FETCH DATA ====
        $riwayatPerjadinDraf = collect();
        $riwayatPerjadinOther = collect();
        
        if ($status == 'semua' || $status == 'Draf-pengajuan') {
            $riwayatPerjadinDraf = DB::table('info_perjadinlangsungs')
                ->select('info_perjadinlangsungs.id as idPerjadin', 'info_perjadinlangsungs.nama_kegiatan', 'info_perjadinlangsungs.tgl_keberangkatan', 'info_perjadinlangsungs.status_pengajuan', 'info_perjadinlangsungs.status_pengajuan_detail', 'info_perjadinlangsungs.is_acceptBend', 'info_perjadinlangsungs.alasan_penolakan', DB::raw('NULL as hasil'), DB::raw('"Perjalanan Dinas" as tipe'), DB::raw('NULL as jenis_kegiatan'))
                ->where('info_perjadinlangsungs.id_pengaju', $userId)
                ->where('info_perjadinlangsungs.status_pengajuan', 'Draf-pengajuan')
                ->where('versi_id', $versiId)
                ->get();
        }

        if ($status == 'semua' || $status != 'Draf-pengajuan') {
            $query = DB::table('info_perjadinlangsungs')
                ->join('data_perjadinlangsungs', 'data_perjadinlangsungs.info_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
                ->leftJoin('dokumens', 'dokumens.info_perjadinlangsung_id', '=', 'info_perjadinlangsungs.id')
                ->select('info_perjadinlangsungs.id as idPerjadin', 'info_perjadinlangsungs.nama_kegiatan', 'info_perjadinlangsungs.tgl_keberangkatan', 'info_perjadinlangsungs.status_pengajuan', 'info_perjadinlangsungs.status_pengajuan_detail', 'info_perjadinlangsungs.is_acceptBend', 'info_perjadinlangsungs.alasan_penolakan', 'dokumens.hasil', DB::raw('"Perjalanan Dinas" as tipe'), DB::raw('NULL as jenis_kegiatan'))
                ->where('data_perjadinlangsungs.pegawai_id', $userId)
                ->where('info_perjadinlangsungs.status_pengajuan', '!=', 'Draf-pengajuan')
                ->where(function ($q) {
                    $q->where('data_perjadinlangsungs.status_pegawai', '!=', 'Supir')
                        ->orWhere(function ($q2) {
                            $q2->where('data_perjadinlangsungs.status_pegawai', '=', 'Supir')
                                ->whereNotExists(function ($q3) {
                                    $q3->select(DB::raw(1))
                                        ->from('data_perjadinlangsungs as dp2')
                                        ->whereColumn('dp2.pegawai_id', 'data_perjadinlangsungs.pegawai_id')
                                        ->where('dp2.status_pegawai', '!=', 'Supir');
                                });
                        });
                })
                ->where('versi_id', $versiId);
                
            if ($status != 'semua') {
                $query->where('info_perjadinlangsungs.status_pengajuan', $status);
            }
            $riwayatPerjadinOther = $query->get();
        }

        $riwayatKegiatan = collect();
        $queryKeg = DB::table('data_perjadinkegiatans')
            ->select(
                'data_perjadinkegiatans.id as idPerjadin', 
                'data_perjadinkegiatans.nama_kegiatan', 
                'data_perjadinkegiatans.tgl_mulai as tgl_keberangkatan', 
                DB::raw('COALESCE(data_perjadinkegiatans.status_pengajuan, "Draf-pengajuan") as status_pengajuan'), 
                'data_perjadinkegiatans.status_pengajuan_detail', 
                DB::raw('NULL as is_acceptBend'), 
                'data_perjadinkegiatans.alasan_penolakan', 
                DB::raw('NULL as hasil'), 
                DB::raw('"Program Kegiatan" as tipe'),
                'data_perjadinkegiatans.jenis_kegiatan'
            )
            ->where(function ($query) use ($userId) {
                $query->where('data_perjadinkegiatans.id_pengaju', $userId)
                    ->orWhereExists(function ($q) use ($userId) {
                        $q->select(DB::raw(1))->from('perangkat_acaras')
                          ->whereColumn('perangkat_acaras.data_perjadin_kegiatan', 'data_perjadinkegiatans.id')
                          ->where('perangkat_acaras.pegawai_id', $userId);
                    });
            })
            ->where('data_perjadinkegiatans.versi_id', $versiId);

        if ($status != 'semua') {
            if ($status == 'Draf-pengajuan') {
                $queryKeg->where(function($q){
                    $q->where('data_perjadinkegiatans.status_pengajuan', 'Draf-pengajuan')
                      ->orWhereNull('data_perjadinkegiatans.status_pengajuan');
                });
            } else {
                $queryKeg->where('data_perjadinkegiatans.status_pengajuan', $status);
            }
        }
        $riwayatKegiatan = $queryKeg->get();

        // Merge all
        $allRiwayat = $riwayatPerjadinDraf->concat($riwayatPerjadinOther)->concat($riwayatKegiatan);
        
        // Sort by date descending
        $allRiwayat = $allRiwayat->sortByDesc('tgl_keberangkatan')->values();

        return view(
            'user.perjadin.riwayat',
            [
                'title' => 'Kegiatanku',
                'active' => 'riwayat_pengajuan',
                'status' => $status,
                "perjadins" => $allRiwayat,
                'countDraf' => $countDraf,
                'countPengajuan' => $countPengajuan,
                'countProses' => $countProses,
                'countRevisi' => $countRevisi,
                'countDitolak' => $countDitolak,
                'countPelaporan' => $countPelaporan,
                'countSelesai' => $countSelesai
            ]
        );
    }

    public function detail_perjadin($id)
    {
        $selectPeserta = DB::table('data_perjadinlangsungs')
            ->join('pegawais', 'pegawai_id', '=', 'pegawais.id')
            ->select('data_perjadinlangsungs.id as idPeserta', 'data_perjadinlangsungs.status_persetujuan', 'data_perjadinlangsungs.id', 'data_perjadinlangsungs.status_pegawai', 'pegawais.nama_lengkap', 'pegawais.pangkat', 'pegawais.golongan')
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
        $pembayaran = DB::table('keuangan_perjadinlangsungs')
            ->select('keuangan_perjadinlangsungs.tgl_bayar')
            ->where('keuangan_perjadinlangsungs.info_perjadinlangsung',$id)
            ->first();

        

        // Mengecek apakah file ada
        $filePath = "dokumen-perjadins/rpd_$id.pdf";

        if (Storage::disk('public')->exists($filePath)) {
            // File exists, lakukan sesuatu
            $RPDExists = true;
        } else {
            // File tidak ada, lakukan sesuatu yang lain
            $RPDExists = false;
        }
            
        $surtug = DB::table('surtug_perjadinlangsungs')
            ->join('info_perjadinlangsungs', 'surtug_perjadinlangsungs.id_perjadinlangsung', '=', 'info_perjadinlangsungs.id')
            ->select('surtug_perjadinlangsungs.updated_at')
            ->where('surtug_perjadinlangsungs.id_perjadinlangsung', $id)
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
                'surtug' => $surtug->first(),
                "selectPesertasNonPegawais" => $selectPeserta_nonPegawai,
                "dokumen" => Dokumen::where('info_perjadinlangsung_id', $id)->first(),
                "fasilitas" => $kebutuhans,
                'mobilitass' => $mobilitas,
                'pembayaran' => $pembayaran,
                'RPDExists' => $RPDExists
            ]
        );
    }

    public function CetakRPDuser($id)
{
    // Path file di storage publik
    $filePath = "dokumen-perjadins/rpd_$id.pdf";

    // Periksa apakah file ada di storage publik
    if (!Storage::disk('public')->exists($filePath)) {
        abort(404, 'File tidak ditemukan');
    }

    // Stream file dengan header untuk menampilkan di browser
    return response()->stream(
        function () use ($filePath) {
            // Mendapatkan file stream
            $fileStream = Storage::disk('public')->getDriver()->readStream($filePath);

            // Menulis stream ke response
            fpassthru($fileStream);

            // Menutup stream
            fclose($fileStream);
        },
        200, // HTTP Status code
        [
            'Content-Type' => 'application/pdf', // Tipe konten PDF
            'Content-Disposition' => 'inline; filename="rpd_' . $id . '.pdf"', // Menampilkan file di browser
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
        $penandatangan  = DB::table('pegawais')
            ->where('id',auth('pegawai')->user()->id)
            ->select('pegawais.nama_lengkap')
            ->first();

        $pic = $pesertaPegawais->firstWhere('status_pegawai', 'PIC');
        return view(
            'user.perjadin.laporan_perjadin',
            [
                'title' => 'Detail Kegiatanku',
                'active' => 'kegiatanku_perjadin',
                'penandatangan' => $penandatangan,
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
    $request->validate([
        'tempat_kegiatan' => 'required|string|max:255',
        'alamat' => 'required|string|max:255',
        'keterangan_mobilitas' => 'required|string|max:255',
       
    ]);

   
    $alamatLengkap = $request->tempat_kegiatan . ' , ' . $request->alamat;

    // Cek apakah keterangan_mobilitas tidak kosong
    if (!empty($request->keterangan_mobilitas)) {
        $versi = Versi::where('status', 'aktif')->get();
        DB::table('info_perjadinlangsungs')->insertOrIgnore([
            'id_pengaju' => auth('pegawai')->user()->id,
            'nama_kegiatan' => $request->nama_kegiatan,
            'no_undangan' => $request->no_undangan,
            'pemberi_undangan' => $request->pemberi_undangan,
            'tanggal_surat' => $request->tanggal_surat,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'tgl_keberangkatan' => $request->tgl_keberangkatan,
            'provinsi' => $request->provinsi,
            'kabupaten_kota' => $request->kabupaten_kota,
            'alamat' => $alamatLengkap,
            'mobilitas' => $request->fasilitas_perjadin,
            'keterangan_mobilitas' => $request->keterangan_mobilitas,
            'status_pengajuan' => 'Draf-pengajuan',
            'versi_id' => $versi[0]->id,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $perjadin = Info_perjadinlangsung::max('id'); // mengambil nilai id terakhir yang diinputkan
        

        $select_tranfortasi = $request->fasilitas_perjadin;
        if (($select_tranfortasi == 'Kendaraan Dinas') || ($select_tranfortasi == 'Kendaraan Dinas dan Transportasi Publik')) {
            DB::table('peminjaman_kendaraan_dinas')->insertOrIgnore([
                'info_perjadinlangsung' => $perjadin, //menerima id info terakhir
                'kendaraan' => $request->fasilitas_perjadinn,
                'status' => 'pengajuan',
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
                
        } elseif ($select_tranfortasi == 'Transportasi Publik' || $select_tranfortasi == 'Kendaraan Pribadi') {
            // Jika jenis fasilitas adalah 2 atau 4, update info_perjadinlangsungs yang sesuai
            DB::table('info_perjadinlangsungs')
                ->where('id', $perjadin)
                ->update([
                    'status_pengajuan_detail' => 'Pengisian Step 2 Tanpa Kendaraan Dinas',
                    'status_pengajuan' => 'Draf-pengajuan',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);
            
            }
            
        return redirect()->route('perjadin_step_2', ['id' => $perjadin])->with('success', 'Permohonan Perjalanan Dinas berhasil dibuat. Silakan masukkan nama peserta!');
    }

    // Jika keterangan_mobilitas kosong, Anda bisa mengatur redirect atau respons lain di sini
    return redirect()->back()->with('error', 'Keterangan mobilitas tidak boleh kosong!');
}

public function editPerjadin(Request $request)
{
    $request->validate([
        'tempat_kegiatan' => 'required|string|max:255',
        'alamat' => 'required|string|max:255',
        'keterangan_mobilitas' => 'required|string|max:255',
       
    ]);

   
    $alamatLengkap = $request->tempat_kegiatan . ' , ' . $request->alamat;

    // Cek apakah keterangan_mobilitas tidak kosong
    if (!empty($request->keterangan_mobilitas)) {
        
        $perjadin = $request->idPerjadin; // mengambil nilai id terakhir yang diinputkan
        $select_tranfortasi = $request->fasilitas_perjadin;

        $versi = Versi::where('status', 'aktif')->get();
        DB::table('info_perjadinlangsungs')
        ->where('id',$perjadin)
        ->update([
            'id_pengaju' => auth('pegawai')->user()->id,
            'nama_kegiatan' => $request->nama_kegiatan,
            'no_undangan' => $request->no_undangan,
            'pemberi_undangan' => $request->pemberi_undangan,
            'tanggal_surat' => $request->tanggal_surat,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'tgl_keberangkatan' => $request->tgl_keberangkatan,
            'provinsi' => $request->provinsi,
            'kabupaten_kota' => $request->kabupaten_kota,
            'alamat' => $alamatLengkap,
            'mobilitas' => $request->fasilitas_perjadin,
            'keterangan_mobilitas' => $request->keterangan_mobilitas,
            'status_pengajuan' => 'Draf-pengajuan',
            'versi_id' => $versi[0]->id,
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        
        // Cek apakah kendaraan sudah ada di peminjaman
        $kendaraanExist = DB::table('peminjaman_kendaraan_dinas')
            ->where('info_perjadinlangsung', $perjadin)
            ->first(); // Gunakan first() untuk mendapatkan satu baris

        if (($select_tranfortasi == 'Kendaraan Dinas') || ($select_tranfortasi == 'Kendaraan Dinas dan Transportasi Publik')) {
            if ($kendaraanExist) {
                DB::table('peminjaman_kendaraan_dinas')
                ->where('info_perjadinlangsung',$perjadin)
                ->update([
                    'kendaraan' => $request->fasilitas_perjadinn,
                    'status' => 'pengajuan',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);
            } else {
                DB::table('peminjaman_kendaraan_dinas')->insertOrIgnore([
                    'info_perjadinlangsung' => $perjadin, //menerima id info terakhir
                    'kendaraan' => $request->fasilitas_perjadinn,
                    'status' => 'pengajuan',
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);
            }
                
        } elseif ($select_tranfortasi == 'Transportasi Publik' || $select_tranfortasi == 'Kendaraan Pribadi') {
            // Jika jenis fasilitas adalah 2 atau 4, update info_perjadinlangsungs yang sesuai
            DB::table('info_perjadinlangsungs')
                ->where('id', $perjadin)
                ->update([
                    'is_acceptHKT' => 'pengajuan',
                    'is_acceptBMN' => 'proses',
                    'status_pengajuan_detail' => 'Verifikasi-HKT',
                    'status_pengajuan' => 'proses',
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);
            
            }
            
        return redirect()->route('perjadin_step_2', ['id' => $perjadin])->with('success', 'Permohonan Perjalanan Dinas berhasil dibuat. Silakan masukkan nama peserta!');
    }

    // Jika keterangan_mobilitas kosong, Anda bisa mengatur redirect atau respons lain di sini
    return redirect()->back()->with('error', 'Keterangan mobilitas tidak boleh kosong!');
}


    // lagi di edit
    public function storePeserta(Request $request)
    {
        DB::table('data_perjadinlangsungs')->insertOrIgnore([
            'status_pegawai' => 'Pegawai',
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'pegawai_id' => $request->peserta_pegawai,
            'tgl_keberangkatan' => $request->berangkat,
            'tgl_selesai' => $request->selesai,
            // 'non_pegawai_id' => $request->peserta_non_pegawai,
            'status_persetujuan' => 'Proses Persetujuan',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $data_perjaidinlangsung_max = data_perjadinlangsung::max('id');

        DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'data_perjadinlangsungs' => $data_perjaidinlangsung_max,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
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
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $data_perjaidinlangsung_max = data_perjadinlangsung::max('id');

        DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'data_perjadinlangsungs' => $data_perjaidinlangsung_max,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $id = $request->info_perjadinlangsung;
        return redirect()->route('detail-perjadin', ['id' => $id])->with('success', 'Peserta baru berhasil ditambahkan!');
    }

    public function storeNonPeserta(Request $request)
    {
        // Ambil data perjalanan dinas untuk tanggal keberangkatan dan selesai
        $infoPerjadin = DB::table('info_perjadinlangsungs')->where('id', $request->info_perjadinlangsung)->first();
        if (!$infoPerjadin) {
            return redirect()->back()->with('error', 'Data perjalanan dinas tidak ditemukan.');
        }
    
        // Inisialisasi variabel untuk menampung ID non-pegawai yang akan disimpan
        $nonPegawaiId = $request->peserta_non_pegawai;
    
        // Cek apakah non-pegawai baru atau yang sudah ada
        if ($request->nama_lengkap != null) {
            // Tambahkan non-pegawai baru ke tabel non_pegawais
            DB::table('non_pegawais')->insertOrIgnore([
                'NIP_NIK' => $request->NIP_NIK,
                'nama_lengkap' => $request->nama_lengkap,
                'golongan' => $request->golongan,
                'pangkat' => $request->pangkat,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
    
            // Ambil ID non-pegawai yang baru ditambahkan
            $nonPegawaiId = DB::table('non_pegawais')->max('id');
        }
    
        // Cek apakah non-pegawai sudah terdaftar dalam perjalanan dinas lain pada rentang tanggal yang sama
        $isAlreadyAssigned = DB::table('data_perjadinlangsungs')
            ->where('non_pegawai_id', $nonPegawaiId)
            ->where(function ($query) use ($infoPerjadin) {
                $query->whereBetween('tgl_keberangkatan', [$infoPerjadin->tgl_keberangkatan, $infoPerjadin->tgl_selesai])
                      ->orWhereBetween('tgl_selesai', [$infoPerjadin->tgl_keberangkatan, $infoPerjadin->tgl_selesai])
                      ->orWhere(function ($query) use ($infoPerjadin) {
                          $query->where('tgl_keberangkatan', '<=', $infoPerjadin->tgl_keberangkatan)
                                ->where('tgl_selesai', '>=', $infoPerjadin->tgl_selesai);
                      });
            })
            ->exists();
    
        if ($isAlreadyAssigned) {
            return redirect()->back()->with('error', 'Non-Pegawai ini sudah terdaftar dalam perjalanan dinas lain di rentang tanggal tersebut.');
        }
    
        // Menyimpan data non-pegawai di data_perjadinlangsungs
        DB::table('data_perjadinlangsungs')->insertOrIgnore([
            'status_pegawai' => 'Non-Pegawai',
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'non_pegawai_id' => $nonPegawaiId,
            'tgl_keberangkatan' => $infoPerjadin->tgl_keberangkatan,
            'tgl_selesai' => $infoPerjadin->tgl_selesai,
            'status_persetujuan' => 'Proses Persetujuan',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);
    
        // Mendapatkan ID terakhir yang dimasukkan di data_perjadinlangsungs untuk tabel keuangan_perjadinlangsungs
        $dataPerjadinlangsungId = DB::table('data_perjadinlangsungs')->max('id');
        
        // Menyimpan data di keuangan_perjadinlangsungs
        DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'data_perjadinlangsungs' => $dataPerjadinlangsungId,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);
    
        return redirect()->route('perjadin_step_2', ['id' => $request->info_perjadinlangsung])
            ->with('success', 'Non-Pegawai berhasil ditambahkan!');
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
            DB::table('data_perjadinlangsungs')->insertOrIgnore([
                'status_pegawai' => 'Pegawai',
                'info_perjadinlangsung' => $request->info_perjadinlangsung,
                'non_pegawai_id' => $id_non_pegawai_new,
                'status_persetujuan' => 'Proses Persetujuan',
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        $data_perjaidinlangsung_max = data_perjadinlangsung::max('id');

        DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
            'info_perjadinlangsung' => $request->info_perjadinlangsung,
            'data_perjadinlangsungs' => $data_perjaidinlangsung_max,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
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
        return redirect()->route('perjadin_step_2', ['id' => $id])->with('success', 'Fasilitas berhasil ditambahkan!');
    }

    public function storeFasilitasDetail(Request $request)
    {
        $satuan = $request->satuan ? $request->satuan : $request->satuan_manual;

        DB::table('kebutuhans')->insert([
            'nama' => $request->uraian,
            'status' => 'Pengajuan',
            'jumlah_frekuensi' => $request->jumlah_frekuensi,
            'satuan' => $satuan,
            'tipe_pendanaan' => $request->tipe_pendanaan,
            'ket' => $request->keterangan,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
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
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

            $id = $request->info_perjadinlangsung;

            return redirect()->route('perjadin_step_2', ['id' => $id])->with('success', 'Fasilitas berhasil ditambahkan!');
        } else {
            return redirect()->route('perjadin_step_2')->with('error', 'Tidak ada data perjadin langsung dengan status pegawai PIC.');
        }
    }

    public function storePerjadin(Request $request)
{
    // Validasi file yang diunggah
    $validationData = $request->validate([
        'surat_undangan' => 'required|mimes:pdf,jpg,jpeg,png|max:2048', // 2MB = 2048KB
    ], [
        'surat_undangan.required' => 'Dokumen surat undangan wajib diunggah.',
        'surat_undangan.mimes' => 'Format file harus berupa PDF, JPG, JPEG, atau PNG.',
        'surat_undangan.max' => 'Ukuran file terlalu besar. Maksimal 2MB.',
    ]);

    $id = $request->info_perjadinlangsung;
    $cek_peserta = data_perjadinlangsung::where('info_perjadinlangsung', $id)->get();
    if ($cek_peserta->isEmpty()) {
        return redirect()->route('perjadin_step_2', ['id' => $id])
            ->with('error', 'Peserta tidak boleh kosong. Mohon isi data peserta yang akan mengikuti perjalanan dinas!')
            ->withInput();

    }
    
    $cek_dokumen = DB::table('dokumens')->where('info_perjadinlangsung_id', $id)->get();
    if ($cek_dokumen->isEmpty()) {
        DB::table('dokumens')->insert([
            'info_perjadinlangsung_id' => $request->info_perjadinlangsung,
            'surat_undangan' => $validationData['surat_undangan'] = $request->file('surat_undangan')->store('dokumen-perjadins', 'public'),
            'status_persetujuan' => 'pengajuan',
            'tgl_upload_undangan' => now()->format('Y-m-d H:i:s'),
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);
    } else {
        DB::table('dokumens')
        ->where('info_perjadinlangsung_id',$request->info_perjadinlangsung)
        ->update([
            'surat_undangan' => $validationData['surat_undangan'] = $request->file('surat_undangan')->store('dokumen-perjadins', 'public'),
            'status_persetujuan' => 'pengajuan',
            'tgl_upload_undangan' => now()->format('Y-m-d H:i:s'),
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);
    }

    $perjadinData = Info_perjadinlangsung::find($id);

    if ($perjadinData->keterangan_mobilitas == 'Tidak Menggunakan Kendaraan Dinas') {
        DB::table('info_perjadinlangsungs')
            ->where('id', $id)
            ->whereNull('is_acceptBMN')
            ->whereNull('is_acceptHKT')
            ->update([
                'status_pengajuan' => 'pengajuan',
                'is_acceptBMN' => 'selesai',
                'is_acceptHKT' => 'pengajuan',
                'status_pengajuan_detail' => 'Verifikasi-HKT',
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
    } else {
        DB::table('info_perjadinlangsungs')
            ->where('id', $id)
            ->whereNull('is_acceptBMN')
            ->whereNull('is_acceptHKT')
            ->update([
                'status_pengajuan' => 'pengajuan',
                'is_acceptBMN' => 'pengajuan',
                'status_pengajuan_detail' => 'Verifikasi-BMN',
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        
    }
    $cek_mobilDinas = DB::table('peminjaman_kendaraan_dinas')
            ->where('info_perjadinlangsung',$id)
            ->get();

    $pengusul = DB::table('pegawais')
            ->join('info_perjadinlangsungs', 'pegawais.id', '=', 'info_perjadinlangsungs.id_pengaju')
            ->select('info_perjadinlangsungs.id_pengaju', 'pegawais.nama_lengkap')    
            ->where('info_perjadinlangsungs.id', $id)
            ->first(); // Mengambil hasil pertama
        
    if ($cek_mobilDinas->isEmpty()) {
        if ($pengusul) {
            // Data yang ingin dimasukkan
            $dataNotif = [
                'id_kegiatan' => $id,
                'from' => $pengusul->id_pengaju, // ID pengguna yang mengirim
                'to' => 0, // ID pengguna yang menerima
                'role' => 'HKT', // Peran pengguna
                'header' => 'Usulan Perjalanan Dinas - '.$id, // Judul notifikasi
                'message' => $pengusul->nama_lengkap.' melakukan Pengajuan Perjadin Langsung tanpa Kendaraan Dinas', // Isi pesan
                'route' => 'perjadin-HKT/detail/'.$id, // Route yang dituju
                'is_read' => 0, // Status belum dibaca
                'versi_id' => session('versi'),
                'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
            ];
            
            // Melakukan insert ke tabel notifications
            DB::table('notifications')->insert($dataNotif);
        }
    } else {
        if ($pengusul) {
            // Data yang ingin dimasukkan
            $dataNotif = [
                'id_kegiatan' => $id, // ID pengguna yang menerima
                'from' => $pengusul->id_pengaju, // ID pengguna yang mengirim
                'to' => 0, // ID pengguna yang menerima
                'role' => 'BMN', // Peran pengguna
                'header' => 'Usulan Perjalanan Dinas - '.$id, // Judul notifikasi
                'message' => $pengusul->nama_lengkap.' melakukan Pengajuan Perjadin Langsung dengan Kendaraan Dinas', // Isi pesan
                'route' => 'perjadin-mobilitas/detail/'.$id, // Route yang dituju
                'is_read' => 0, // Status belum dibaca
                'versi_id' => session('versi'),
                'created_at' => now()->format('Y-m-d H:i:s'), // Waktu saat dibuat
            ];
            
            // Melakukan insert ke tabel notifications
            DB::table('notifications')->insert($dataNotif);
        }
    }

    DB::table('peminjaman_kendaraan_dinas')
    ->where('info_perjadinlangsung',$id)
    ->delete();

    return redirect()->route('riwayat', ['status' => 'pengajuan'])->with('success', 'Perjalanan dinas berhasil diajukan. Silakan tunggu persetujuan dari pihak Keuangan!');
}

public function ajukanPerjadin(Request $request)
{
    $request->validate([
        'tempat_kegiatan' => 'required|string|max:255',
        'alamat' => 'required|string|max:255',
        'keterangan_mobilitas' => 'required|string|max:255',
    ]);

    $alamatLengkap = $request->tempat_kegiatan . ' , ' . $request->alamat;

    if (!empty($request->keterangan_mobilitas)) {
        $select_tranfortasi = $request->fasilitas_perjadin;
        $versi = Versi::where('status', 'aktif')->first();

        // Ambil data perjalanan dinas lama
        $oldPerjadin = DB::table('info_perjadinlangsungs')->where('id', $request->idPerjadin)->first();

        // Buat entri baru di tabel info_perjadinlangsungs
        $newPerjadinId = DB::table('info_perjadinlangsungs')->insertGetId([
            'id_pengaju' => auth('pegawai')->user()->id,
            'nama_kegiatan' => $request->nama_kegiatan ?? $oldPerjadin->nama_kegiatan,
            'no_undangan' => $request->no_undangan ?? $oldPerjadin->no_undangan,
            'pemberi_undangan' => $request->pemberi_undangan ?? $oldPerjadin->pemberi_undangan,
            'tanggal_surat' => $request->tanggal_surat ?? $oldPerjadin->tanggal_surat,
            'tgl_mulai' => $request->tgl_mulai ?? $oldPerjadin->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai ?? $oldPerjadin->tgl_selesai,
            'tgl_keberangkatan' => $request->tgl_keberangkatan ?? $oldPerjadin->tgl_keberangkatan,
            'provinsi' => $request->provinsi ?? $oldPerjadin->provinsi,
            'kabupaten_kota' => $request->kabupaten_kota ?? $oldPerjadin->kabupaten_kota,
            'alamat' => $alamatLengkap,
            'mobilitas' => $select_tranfortasi,
            'keterangan_mobilitas' => $request->keterangan_mobilitas,
            'status_pengajuan' => 'Draf-pengajuan',
            'versi_id' => $versi->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Ambil peserta lama kecuali yang berstatus 'sopir' dan join dengan tabel kebutuhan
        $oldPeserta = DB::table('data_perjadinlangsungs')
            ->join('keuangan_perjadinlangsungs', 'data_perjadinlangsungs.id', '=', 'keuangan_perjadinlangsungs.data_perjadinlangsungs')
            ->join('kebutuhans', 'keuangan_perjadinlangsungs.kebutuhan_id', '=', 'kebutuhans.id')
            ->where('data_perjadinlangsungs.info_perjadinlangsung', $request->idPerjadin)
            ->where('data_perjadinlangsungs.status_pegawai', '!=', 'Supir') // Filter untuk mengecualikan sopir
            ->select('data_perjadinlangsungs.*', 'keuangan_perjadinlangsungs.kebutuhan_id', 'kebutuhans.*') // Pilih kolom yang diperlukan
            ->get();

            foreach ($oldPeserta as $peserta) {
                // Buat entri peserta baru dan dapatkan ID-nya
                $newDataPerjadinId = DB::table('data_perjadinlangsungs')->insertGetId([
                    'info_perjadinlangsung' => $newPerjadinId,
                    'pegawai_id' => $peserta->pegawai_id,
                    'status_pegawai' => $peserta->status_pegawai,
                    'tgl_keberangkatan' => $peserta->tgl_keberangkatan,
                    'tgl_selesai' => $peserta->tgl_selesai,
                    'status_persetujuan' => 'Proses Persetujuan',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            
                // Buat entri baru untuk setiap kebutuhan terkait peserta baru
                $newKebutuhanId = DB::table('kebutuhans')->insertGetId([
                    'nama' => $request->input('nama.' . $peserta->kebutuhan_id, $peserta->nama),
                    'jumlah_frekuensi' => $request->input('jumlah.' . $peserta->kebutuhan_id, $peserta->jumlah_frekuensi),
                    'satuan' => $request->input('satuan.' . $peserta->kebutuhan_id, $peserta->satuan),
                    'tipe_pendanaan' => $request->input('tipe_pendanaan.' . $peserta->kebutuhan_id, $peserta->tipe_pendanaan),
                    'ket' => $request->input('ket.' . $peserta->kebutuhan_id, $peserta->ket),
                    'status' => 'Pengajuan', 
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);                
            
                // Hubungkan kebutuhan baru ke peserta baru di tabel keuangan_perjadinlangsungs
                DB::table('keuangan_perjadinlangsungs')->insertOrIgnore([
                    'info_perjadinlangsung' => $newPerjadinId,
                    'data_perjadinlangsungs' => $newDataPerjadinId, // ID peserta baru
                    'kebutuhan_id' => $newKebutuhanId, // ID kebutuhan baru
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }            

        return redirect()->route('perjadin_step_2', ['id' => $newPerjadinId])
            ->with('success', 'Permohonan Perjalanan Dinas berhasil dibuat. Silakan masukkan nama peserta!');
    }

    return redirect()->back()->with('error', 'Keterangan mobilitas tidak boleh kosong!');
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
    DB::table('info_perjadinlangsungs')
        ->where('id', $request->perjadin)
        ->update([
            'status_pengajuan' => 'pelaporan',
            'status_pengajuan_detail' => 'Pelaporan', // Optional, tambahkan detail status
            'updated_at' => now(), // Perbarui timestamp
        ]);

    return redirect()->route('riwayat', ['status' => 'pelaporan'])
        ->with('success', 'Laporan berhasil disimpan dan diarahkan ke Riwayat Pelaporan.');
}


    function notifUser($idUser): JsonResponse
    {
        // Ambil id_kegiatans dari tabel data_perjadinlangsungs
            $id_kegiatans = DB::table('data_perjadinlangsungs')
                ->select('info_perjadinlangsung')
                ->where('pegawai_id', $idUser)
                ->pluck('info_perjadinlangsung'); // Menggunakan pluck agar hasilnya berupa array sederhana

        // Hitung notifikasi yang belum dibaca
        $notif = DB::table('notifications')
            ->where(function ($query) use ($idUser, $id_kegiatans) {
                $query->where('to', $idUser)
                    ->orWhereIn('id_kegiatan', $id_kegiatans);
            })
            ->where('versi_id',session('versi'))  
            ->where('is_read', 0)
            ->whereNull('notifications.role')
            ->count();

        // Ambil data notifikasi yang belum dibaca
        $notifDataUnread = DB::table('notifications')
            ->join('administrators', 'notifications.from', '=', 'administrators.id')
            ->select('administrators.username AS dari', 'notifications.*')
            ->where(function ($query) use ($idUser, $id_kegiatans) {
                $query->where('to', $idUser)
                    ->orWhereIn('id_kegiatan', $id_kegiatans);
            })
            ->where('notifications.versi_id',session('versi'))  
            ->where('is_read', 0)
            ->whereNull('notifications.role')
            ->orderBy('notifications.created_at', 'desc')
            ->get();

        // Ambil data notifikasi yang sudah dibaca, batas maksimal 5 terbaru
        $notifDataRead = DB::table('notifications')
            ->join('administrators', 'notifications.from', '=', 'administrators.id')
            ->select('administrators.username AS dari', 'notifications.*')
            ->where(function ($query) use ($idUser, $id_kegiatans) {
                $query->where('to', $idUser)
                    ->orWhereIn('id_kegiatan', $id_kegiatans);
            })
            ->where('notifications.versi_id',session('versi'))  
            ->where('is_read', 1)
            ->whereNull('notifications.role')
            ->orderBy('notifications.created_at', 'desc')
            ->limit(5)
            ->get();

        // Total notifikasi
        $total = $notif > 0 ? 1 : 0;

        $res = [
            'notif' => $notif,
            'notifDataUnread' => $notifDataUnread,
            'notifDataRead' => $notifDataRead,
            'total' => $total
        ];

        return response()->json($res);
    }

    public function markAllUser($idUser)
    {

        // Query Update
        DB::table('notifications')
            ->where(function ($query) use ($idUser) {
                $query->where('to', $idUser)
                      ->orWhereIn('id_kegiatan', function($subquery) use ($idUser) {
                          $subquery->select('info_perjadinlangsung')
                                    ->from('data_perjadinlangsungs')
                                    ->where('pegawai_id', $idUser);
                      });
            })
            ->where('versi_id',session('versi'))  
            ->where('is_read', 0)
            ->whereNull('role')
            ->update(['is_read' => 1]);
        
        


        return response()->json(['message' => 'Notifikasi berhasil diperbarui.']);
    }

    function markAsReadUser($id) {
        // Mengubah status is_read menjadi 1
        DB::table('notifications')
            ->where('id', $id)
            ->update(['is_read' => 1]);

        return response()->json(['success' => true]);
    }

    public function previewPerjadinUser($id)
    {
        $penandatangan  = DB::table('pegawais')
            ->where('id',auth('pegawai')->user()->id)
            ->select('pegawais.nama_lengkap')
            ->first();
        return view(
            'user.perjadin.preview_lap_perjadin',
            [
                'title' => 'Detail Kegiatanku',
                'active' => 'kegiatanku_perjadin',
                'penandatangan' => $penandatangan,
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
                'surat_undangan' => 'mimes:pdf,jpg,jpeg,png|file|max:2048',
                'surat_tugas' => 'mimes:pdf,jpg,jpeg,png|file|max:2048',
                'SPPD' => 'mimes:pdf|file|max:2048',
                'lap_pengeluaran' => 'mimes:pdf,jpg,jpeg,png|file|max:2048',
                'lap_perjadin' => 'mimes:pdf,jpg,jpeg,png|file|max:2048',
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
                        'status_pengajuan' => 'selesai',
                        'is_acceptKeu' => 'verifikasi-2'
                    ]);
                return redirect()->route('riwayat', ['status' => 'selesai'])->with('success', 'Data Perjalanan dinas telah diperbaharui, tunggu konfirmasi dari pihak keuangan');
            }

            if ($request->status_pejadin == 'pelaporan') {
                DB::table('info_perjadinlangsungs')
                    ->where('id', $request->info_perjadinlangsung)
                    ->update([
                        'status_pengajuan' => 'selesai',
                        'is_acceptKeu' => 'verifikasi-2',
                        'status_pengajuan_detail' => 'verifikasi-2-keuangan'
                    ]);

                // Simpan file SPPD
                if ($request->hasFile('SPPD') && $request->file('SPPD')->isValid()) {
                    $SPPDPath = $request->file('SPPD')->store('dokumen-perjadins', 'public');
                    DB::table('dokumens')
                        ->where('info_perjadinlangsung_id', $request->info_perjadinlangsung)
                        ->update([
                            'SPPD' => $SPPDPath,
                            'tgl_upload_SPPD' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                }

                // Simpan file lap_pengeluaran
                if ($request->hasFile('lap_pengeluaran') && $request->file('lap_pengeluaran')->isValid()) {
                    $lapPengeluaranPath = $request->file('lap_pengeluaran')->store('dokumen-perjadins', 'public');
                    DB::table('dokumens')
                        ->where('info_perjadinlangsung_id', $request->info_perjadinlangsung)
                        ->update([
                            'lap_pengeluaran' => $lapPengeluaranPath,
                            'tgl_upload_lap_keu' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                }

                // Simpan file lap_perjadin
                if ($request->hasFile('lap_perjadin') && $request->file('lap_perjadin')->isValid()) {
                    $lapPerjadinPath = $request->file('lap_perjadin')->store('dokumen-pejadins', 'public');
                    DB::table('dokumens')
                        ->where('info_perjadinlangsung_id', $request->info_perjadinlangsung)
                        ->update([
                            'lap_perjadin' => $lapPerjadinPath,
                            'tgl_upload_lap_perjadin' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ]);
                }

                return redirect()->route('riwayat', ['status' => $request->status_pejadin])->with('success', 'Data Perjalanan dinas telah diperbaharui, tunggu konfirmasi dari pihak keuangan');
            }
        } elseif ($action === 'selesai') {
            DB::table('info_perjadinlangsungs')
                ->where('id', $request->info_perjadinlangsung)
                ->update([
                    'status_pengajuan' => 'pelaporan',
                    'status_pengajuan_detail' => 'Pelaporan'
                ]);
            return redirect()->route('riwayat', ['status' => $request->status_pejadin])->with('success', 'Perjalanan Dinas Selesai, Silahkan Membuat Laporan');
        }
      
    $action = $request->input('action');
    $id = $request->info_perjadinlangsung;
    $dokumen = Dokumen::where('info_perjadinlangsung_id', $id)->first();

    // Define validation rules with conditional required for 'lap_perjadin'
    $validationData = $request->validate([
        'surat_undangan' => 'nullable|mimes:pdf,jpg,jpeg,png|file|max:2048',
        'surat_tugas' => 'nullable|mimes:pdf,jpg,jpeg,png|file|max:2048',
        'SPPD' => 'nullable|mimes:pdf|file|max:2048',
        'lap_pengeluaran' => 'nullable|mimes:pdf,jpg,jpeg,png|file|max:2048',
        'lap_perjadin' => ($dokumen && $dokumen->lap_perjadin) ? 'nullable|mimes:pdf,jpg,jpeg,png|file|max:2048' : 'required|mimes:pdf,jpg,jpeg,png|file|max:2048',
    ]);

    // Process each document and update or delete as necessary
    $documentFields = ['surat_undangan', 'surat_tugas', 'SPPD', 'lap_pengeluaran', 'lap_perjadin'];
    foreach ($documentFields as $field) {
        if ($request->hasFile($field)) {
            $oldFileKey = 'old' . ucfirst($field);
            if ($request->$oldFileKey) {
                Storage::delete($request->$oldFileKey);
            }
            $validationData[$field] = $request->file($field)->store('dokumen-perjadins', 'public');
        }
    }

    // Update the Dokumen model
    Dokumen::where('info_perjadinlangsung_id', $id)
        ->update($validationData);

    // Update the status_pengajuan if necessary based on status_pejadin and other fields
    DB::table('info_perjadinlangsungs')
        ->where('id', $id)
        ->update(['status_pengajuan' => $request->status_pejadin]);

      // Define validation rules with conditional required for 'lap_perjadin'
      $validationData = $request->validate([
        'surat_undangan' => 'nullable|mimes:pdf,jpg,jpeg,png|file|max:2048',
        'surat_tugas' => 'nullable|mimes:pdf,jpg,jpeg,png|file|max:2048',
        'SPPD' => 'nullable|mimes:pdf|file|max:2048',
        'lap_pengeluaran' => 'nullable|mimes:pdf,jpg,jpeg,png|file|max:2048',
        'lap_perjadin' => ($dokumen && $dokumen->lap_perjadin) ? 'nullable|mimes:pdf,jpg,jpeg,png|file|max:2048' : 'required|mimes:pdf,jpg,jpeg,png|file|max:2048',
    ]);

    // Process each document and update or delete as necessary
    $fields = ['surat_undangan', 'surat_tugas', 'SPPD', 'lap_pengeluaran', 'lap_perjadin'];
    foreach ($fields as $field) {
        if ($request->hasFile($field)) {
            // Debugging: Log the file name and type
            \Log::info("Processing file upload for: $field");

            $oldFileKey = 'old' . ucfirst($field);
            if ($request->$oldFileKey) {
                Storage::delete($request->$oldFileKey);
            }
            // Store file and add to validated data array
            $validationData[$field] = $request->file($field)->store('dokumen-perjadins', 'public');
        }
    }

    // Update the Dokumen model
    Dokumen::where('info_perjadinlangsung_id', $id)->update($validationData);

    // Update the status_pengajuan if necessary
    DB::table('info_perjadinlangsungs')
        ->where('id', $id)
        ->update(['status_pengajuan' => $request->status_pejadin]);
    

    // Update the Dokumen model


    // Redirect to /riwayat/perjadin after saving
    return redirect()->route('riwayat', ['status' => $request->status_pejadin])->with('success', 'Data Perjalanan dinas telah diperbaharui.');
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

     public function destroy($id)
     {
        try {
            // Hapus data dari tabel terkait terlebih dahulu
            Keuangan_perjadinlangsung::where('info_perjadinlangsung', $id)->delete();
            Data_perjadinlangsung::where('info_perjadinlangsung', $id)->delete();

            // Hapus data utama
            Info_perjadinlangsung::findOrFail($id)->delete();

            // Redirect kembali dengan pesan sukses
            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }   
     }

    public function destroyPeserta(Request $request, $id)
{
    $id_perjadin = $request->info_perjadinlangsung;

    // Update status pegawai
    DB::table('pegawais')
        ->where('id', $request->peserta)
        ->update([
            'is_dinas' => '1'
        ]);

    // Hapus data di tabel Data_perjadinlangsung
    Data_perjadinlangsung::destroy($id);

    // Ambil semua kebutuhan_id dari Keuangan_perjadinlangsung yang akan dihapus
    $kebutuhan_ids = Keuangan_perjadinlangsung::where('data_perjadinlangsungs', $id)->pluck('kebutuhan_id');

    // Hapus data di tabel Keuangan_perjadinlangsung
    Keuangan_perjadinlangsung::where('data_perjadinlangsungs', $id)->delete();

    // Hapus data di tabel kebutuhans berdasarkan kebutuhan_id
    DB::table('kebutuhans')->whereIn('id', $kebutuhan_ids)->delete();

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

        // $id_perjadin = $request->info_perjadinlangsung;
        // return redirect()->route('perjadin_step_2', ['id' => $id_perjadin])->with('success', 'Fasilitas berhasil dihapus!');
        $info_perjadinlangsung = $request->info_perjadinlangsung;
        $data_perjadinlangsungs = $request->data_perjadinlangsungs;

        DB::table('keuangan_perjadinlangsungs')
            ->where('info_perjadinlangsung', $info_perjadinlangsung)
            ->where('data_perjadinlangsungs', $data_perjadinlangsungs)
            ->where('kebutuhan_id', $id)
            ->delete();

        Kebutuhan::destroy($id);

        return redirect()->route('perjadin_step_2', ['id' => $info_perjadinlangsung])
            ->with('success', 'Data fasilitas berhasil dihapus!');
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

