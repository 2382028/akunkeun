<?php

namespace App\Http\Controllers;

use App\Models\Versi;
use App\Models\Administrator;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Data_perjadinkegiatan;
use App\Models\Info_perjadinlangsung;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use PDF;
use Illuminate\Support\Facades\Storage;

use App\Models\Pemeliharaan;
use App\Models\Pemesanan;
use App\Models\Kamar;


class LoginController extends Controller
{
        public function login_penyedia(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('penyedia')->attempt($credentials)) {
            return redirect('/penyedia'); // Langsung redirect ke dashboard penyedia
        }

        return back()->with('LoginError', 'Email atau password yang Anda masukkan tidak sesuai');
    }


    public function logout_penyedia(Request $request)
    {
        Auth::guard('penyedia')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/penyedia/login');
    }
    public function login(Request $request)
    {
        
        $role = $request->query('role');
        

        // dd($role);
        if (!config('app.isMaintenance')) {
            return view('admin.login', [
                "versis" => Versi::all()
    
            ]);
        } else {
            if ($role == 'admin') {
                return view('admin.login', [
                    "versis" => Versi::all()
        
                ]);
            } else {
                return view('maintenance_page', [
                    'role' => $role,
                ]);
            }
        }
        
    }
    
    public function pdf()
    {
        return view('admin.pdfprint', [
            'title' => 'printpdf',
        ]);
    }

    
    public function dashboard()
    {
        $countSemua = DB::table('info_perjadinlangsungs')
        ->where('versi_id', session('versi'))
        ->whereIn('status_pengajuan', ['pengajuan','proses','pelaporan','selesai','revisi','ditolak'])
        ->count();

        $countPengajuan = DB::table('info_perjadinlangsungs')
            ->where('versi_id', session('versi'))
            ->whereIn('status_pengajuan_detail', ['Verifikasi-BMN', 'Verifikasi-HKT', 'Verifikasi-HKT<br>(Proses TTE)','Approval-1-Bendahara'])
            ->count();

        $countProses = DB::table('info_perjadinlangsungs')
            ->where('versi_id', session('versi'))
            ->whereIn('status_pengajuan_detail', [
                'approval-2-Bendahara', 'Pelaporan', 'Pelaksanaan Perjadin',
                'verifikasi-2-keuangan', 'verifikasi-1-keuangan', 'verifikasi-2-keu-revisi'
            ])
            ->count();

        $countDitolak = DB::table('info_perjadinlangsungs')
            ->where('versi_id', session('versi'))
            ->where('status_pengajuan', 'ditolak')
            ->count();

        $countSelesai = DB::table('info_perjadinlangsungs')
            ->where('versi_id', session('versi'))
            ->whereIn('status_pengajuan_detail', ['Selesai Dibayarkan', 'selesai', 'Selesai Non Bayar'])
            ->count();

        
        $countSemuaKegiatan = DB::table('data_perjadinkegiatans')
            ->where('versi_id', session('versi'))
            ->whereIn('status_pengajuan', ['pengajuan','proses','pelaporan','selesai','revisi','ditolak'])
            ->count();

        $countPengajuanKegiatan = DB::table('data_perjadinkegiatans')
            ->where('versi_id', session('versi'))
            ->where('status_pengajuan', 'pengajuan')
            ->count();

        $countProsesKegiatan = DB::table('data_perjadinkegiatans')
                ->where(function ($q) {
                    $q->whereIn('status_pengajuan', ['proses', 'pelaporan'])
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('status_pengajuan', 'selesai')
                                ->where('status_pengajuan_detail', '!=', 'Selesai Dibayarkan')
                                ->where('status_pengajuan_detail', '!=', 'Selesai Non Bayar');
                    });
                })
                ->where('versi_id', session('versi'))
                ->count();

        $countDitolakKegiatan = DB::table('data_perjadinkegiatans')
        ->where('versi_id', session('versi'))
            ->where('status_pengajuan', 'ditolak')
            ->count();

        $countSelesaiKegiatan = DB::table('data_perjadinkegiatans')
            ->where('versi_id', session('versi'))
            ->whereIn('status_pengajuan_detail', ['Selesai Dibayarkan', 'selesai', 'Selesai Non Bayar'])
            ->count();
       
        $monthsOrder = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

      
        $perjadinMaster = DB::table('info_perjadinlangsungs')
                            ->where('status_pengajuan', '!=', 'selesai')
                            ->where('versi_id', session('versi'))
                            ->count();

        $kegiatanMaster = DB::table('data_perjadinkegiatans')
                            ->where('status', '!=','selesai')
                            ->where('versi_id', session('versi'))
                            ->count();

        $perjadinBMN = DB::table('info_perjadinlangsungs')
                            ->where('is_acceptBMN', 'pengajuan')
                            ->where('versi_id', session('versi'))
                            ->count();

        $kegiatanBMN = DB::table('data_perjadinkegiatans')
                            ->where('is_acceptBMN', 'pengajuan')
                            ->where('versi_id', session('versi'))
                            ->count();

        $perjadinHKT = DB::table('info_perjadinlangsungs')
                            ->where('is_acceptHKT', 'pengajuan')
                            ->where('versi_id', session('versi'))
                            ->count();

        $kegiatanHKT = DB::table('data_perjadinkegiatans')
                            ->where('is_acceptHKT', 'pengajuan')
                            ->where('versi_id', session('versi'))
                            ->count();
        
        $perjadinBend = DB::table('info_perjadinlangsungs')
                            ->where(function ($query) {
                                $query->where('is_acceptBend', 'approval-1')
                                    ->orWhere('is_acceptBend', 'approval-2');
                            })
                            ->where('versi_id', session('versi'))
                            ->count();

        $kegiatanBend = DB::table('data_perjadinkegiatans')
                            ->where(function ($query) {
                                $query->where('is_acceptBend', 'approval-1')
                                    ->orWhere('is_acceptBend', 'approval-2');
                            })
                            ->where('versi_id', session('versi'))
                            ->count();

        $perjadinKeu = DB::table('info_perjadinlangsungs')
                            ->where('is_acceptKeu', 'verifikasi-2')
                            ->where('versi_id', session('versi'))
                            ->count();

        $kegiatanKeu = DB::table('data_perjadinkegiatans')
                            ->where('is_acceptKeu', 'verifikasi-2')
                            ->where('versi_id', session('versi'))
                            ->count();

        $pengajuanPerBulan = Info_perjadinlangsung::selectRaw('YEAR(tgl_keberangkatan) as year, MONTH(tgl_keberangkatan) as month, COUNT(*) as total_pengajuan')
            ->where('versi_id', session('versi'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $kegiatanPerBulan = Data_perjadinkegiatan::selectRaw('YEAR(tgl_mulai) as year, MONTH(tgl_mulai) as month, COUNT(*) as total_pengajuan')
            ->where('versi_id', session('versi'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $combinedData = collect();

                            foreach ($pengajuanPerBulan as $pengajuan) {
                                $month = Carbon::createFromFormat('m', $pengajuan->month)->format('F'); // Mengambil nama bulan
                                $combinedData->put($month, [
                                    'perjadin' => $pengajuan->total_pengajuan,
                                    'kegiatan' => 0,
                                ]);
                            }
                        
                            foreach ($kegiatanPerBulan as $kegiatan) {
                                $month = Carbon::createFromFormat('m', $kegiatan->month)->format('F'); // Mengambil nama bulan
                                if ($combinedData->has($month)) {
                                    $combinedData->put($month, [
                                        'perjadin' => $combinedData->get($month)['perjadin'],
                                        'kegiatan' => $kegiatan->total_pengajuan,
                                    ]);
                                } else {
                                    $combinedData->put($month, [
                                        'perjadin' => 0,
                                        'kegiatan' => $kegiatan->total_pengajuan,
                                    ]);
                                }
                            }
                        
                          
        $combinedData = $combinedData->sortBy(function ($value, $key) use ($monthsOrder) {
                                return array_search($key, $monthsOrder); 
                            });
                     
        // ==== KPI CARD ====
        $pemeliharaanPengajuan = Pemeliharaan::where('id_ref_status_pemeliharaan', 1)->count();
        $pemeliharaanBerlangsung = Pemeliharaan::whereIn('id_ref_status_pemeliharaan', [6, 9, 12, 15, 18, 3, 8, 10, 13, 14, 4, 16, 19])->count();
        $pemeliharaanSelesai          = Pemeliharaan::where('id_ref_status_pemeliharaan', 21)->count();
        $pemeliharaanDitolak          = Pemeliharaan::whereNotIn('id_ref_status_pemeliharaan', [1, 3, 8, 10, 13, 14, 4, 16, 19, 6, 9, 12, 15, 18, 21])->count();
        $pemesananKpi = [
            'pengajuan'  => Pemesanan::whereIn('status', ['menunggu', 'verifikasi'])->count(),
            'berlangsung' => Pemesanan::where('status', 'diterima')->count(),
            'ditolak/dibatalkan'    => Pemesanan::whereIn('status', ['ditolak','dibatalkan', 'dibatalkan refund'])->count(),
            'selesai'    => Pemesanan::where('status', 'selesai')->count(),
        ];
        // ==== PIE CHART ====
        $pemeliharaanPieData = Pemeliharaan::with('bmn')
            ->get()
            ->groupBy(function ($item) {
                return $item->bmn_type === 'ruangan'
                    ? 'Ruangan'
                    : ($item->bmn->kategori_bmn ?? 'Tidak Diketahui');
            })
            ->map->count();
        // === Bar Chart: tarif vs jumlah malam ===
        $pemesananBarData = Kamar::with(['detailKamar.pemesanan'])->get()
            ->groupBy('harga_per_malam')
            ->map(function ($kamars) {
                $totalNights = 0;
                foreach ($kamars as $kamar) {
                    foreach ($kamar->detailKamar as $detail) {
                        if ($detail->pemesanan) {
                            $checkin  = \Carbon\Carbon::parse($detail->pemesanan->tanggal_checkin);
                            $checkout = \Carbon\Carbon::parse($detail->pemesanan->tanggal_checkout);
                            $totalNights += $checkin->diffInDays($checkout);
                        }
                    }
                }
                return $totalNights;
            });
        // Time series Pemeliharaan per bulan
        $pemeliharaanPerBulan = Pemeliharaan::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Time series Pemesanan per bulan
        $pemesananPerBulan = Pemesanan::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        $monthsOrder = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        // Inisialisasi semua bulan dengan 0
        $timeSeriesBMN = collect();
        foreach ($monthsOrder as $month) {
            $timeSeriesBMN->put($month, [
                'pemeliharaan' => 0,
                'pemesanan' => 0,
            ]);
        }

        // Masukkan data Pemeliharaan
        foreach ($pemeliharaanPerBulan as $item) {
            $month = Carbon::createFromFormat('m', $item->month)->format('F');
            $timeSeriesBMN->put($month, [
                'pemeliharaan' => $item->total,
                'pemesanan' => $timeSeriesBMN->get($month)['pemesanan'], // tetap data pemesanan jika sudah ada
            ]);
        }

        // Masukkan data Pemesanan
        foreach ($pemesananPerBulan as $item) {
            $month = Carbon::createFromFormat('m', $item->month)->format('F');
            $timeSeriesBMN->put($month, [
                'pemeliharaan' => $timeSeriesBMN->get($month)['pemeliharaan'], // tetap data pemeliharaan
                'pemesanan' => $item->total,
            ]);
        }

        // Pastikan urutannya tetap January–December
        $timeSeriesBMN = $timeSeriesBMN->sortBy(function ($value, $key) use ($monthsOrder) {
            return array_search($key, $monthsOrder);
        });   
        return view('admin.dashboard', [
            'title' => 'Dashboard',
            'perjadinMaster' => $perjadinMaster,
            'kegiatanMaster' => $kegiatanMaster,
            'perjadinBMN' => $perjadinBMN,
            'kegiatanBMN' => $kegiatanBMN,
            'perjadinHKT' => $perjadinHKT,
            'kegiatanHKT' => $kegiatanHKT,
            'perjadinBend' => $perjadinBend,
            'kegiatanBend' => $kegiatanBend,
            'perjadinKeu' => $perjadinKeu,
            'kegiatanKeu' => $kegiatanKeu,
            'pengajuanPerBulan' => $pengajuanPerBulan,
            'kegiatanPerBulan' => $kegiatanPerBulan,
            'combinedData' => $combinedData, // Pastikan data ini dikirimkan ke view
            'monthsOrder' => $monthsOrder,
            'countPengajuan' => $countPengajuan,
            'countProses' => $countProses,
            'countDitolak' => $countDitolak,
            'countSelesai' => $countSelesai,
            'countSemua' => $countSemua,
            'countPengajuanKegiatan' => $countPengajuanKegiatan,
            'countProsesKegiatan' => $countProsesKegiatan,
            'countDitolakKegiatan' => $countDitolakKegiatan,
            'countSelesaiKegiatan' => $countSelesaiKegiatan,
            'countSemuaKegiatan' => $countSemuaKegiatan,
            'pemeliharaanKpi' => [
                'pemeliharaanPengajuan' => $pemeliharaanPengajuan,
                'pemeliharaanBerlangsung' => $pemeliharaanBerlangsung,
                'pemeliharaanSelesai' => $pemeliharaanSelesai,
                'pemeliharaanDitolak' => $pemeliharaanDitolak,
            ],
            'pemeliharaanPieData' => $pemeliharaanPieData,
            'pemesananKpi' => $pemesananKpi,
            'pemesananBarData' => $pemesananBarData,
            'timeSeriesBMN' => $timeSeriesBMN,
            'monthsOrder' => $monthsOrder,
        ]);
    }

    public function authenticate(Request $request)
    {
        $credit = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::guard('administrator')->attempt($credit)) {
            $request->session()->regenerate();
            session(['versi' => $request->versi]);
            return redirect('/dashboard');
        }
 
        return back()->with('LoginError', 'Akses masuk salah. Harap periksa kembali!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();

        $request->session()->forget('versi');
    
        return redirect('/administrator')->with('LoginError', 'Berhasil Keluar!');
    }

    public function ConvertSurtug(Request $request)
    {
        // Mendapatkan data yang dikirimkan melalui query string
        $idKegiatan = $request->query('idKegiatan');
        $judul = $request->query('judul');
        $tanggal = $request->query('tanggal');
        $perangkatName = $request->query('perangkat');
        $MAK = $request->query('MAK');
        $pegawaiMaster = json_decode($request->query('pegawaiMaster'), true);
        $pegawaiBendahara = json_decode($request->query('pegawaiBendahara'), true);
        $data = json_decode($request->query('data'), true);
        $subTotal_jumlah = $request->query('subTotal_jumlah');
        $subTotal_pph = $request->query('subTotal_pph');
        $subTotal_nominal = $request->query('subTotal_nominal');

        // Validasi jika data atau perangkatName kosong
        if (!$data || !$perangkatName) {
            return response()->json(['message' => 'Data atau perangkat tidak valid'], 400);
        }


        $perangkat = DB::table('data_perjadinkegiatans as ip')
            ->join('perangkat_acaras as dp', 'dp.data_perjadin_kegiatan', '=', 'ip.id')
            ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
            ->leftJoin('keuangan_perjadinkegiatans as kp', 'dp.id', '=', 'kp.perangkat_acara')
            ->leftJoin('kebutuhans as kb', 'kp.kebutuhan_id', '=', 'kb.id')
            ->join('fasilitas', 'dp.fasilitas_id', '=', 'fasilitas.id')
            ->select(
                'p.id as idPegawai',
                'p.nama_lengkap',
                'p.pangkat',
                'p.golongan',
                'dp.posisi',
                'dp.detail_satuan',
                'dp.satuan',
                'dp.sebagai',
                'dp.status',
                'dp.fasilitas_id',
                'fasilitas.nama_fasilitas',
                DB::raw('MAX(dp.id) as idPerangkatAcara'),
                DB::raw('MAX(kp.id) as idKeuangan'),
                DB::raw('MAX(kp.data_perjadinkegiatan) as idKegiatan'),
                DB::raw('MAX(ip.nama_kegiatan) as NamaKegiatan')
            )
            ->groupBy(
                'p.id',
                'p.nama_lengkap',
                'p.pangkat',
                'p.golongan',
                'dp.posisi',
                'dp.detail_satuan',
                'dp.satuan',
                'dp.sebagai',
                'dp.status',
                'dp.fasilitas_id',
                'fasilitas.nama_fasilitas'
            )
            ->where('fasilitas.nama_fasilitas', $perangkatName)
            ->get();

        // Validasi jika data tidak ditemukan
        if ($perangkat->isEmpty()) {
            abort(404, 'Data perangkat tidak ditemukan.');
        }

        $datas = [
            'idKegiatan' => $idKegiatan,
            'judul' => $judul,
            'tanggal' => $tanggal,
            'perangkat' => $perangkatName,
            'pegawaiMaster' => $pegawaiMaster,
            'pegawaiBendahara' => $pegawaiBendahara,
            'data' => $data,
            'subTotal_jumlah' => $subTotal_jumlah,
            'subTotal_pph' => $subTotal_pph,
            'subTotal_nominal' => $subTotal_nominal,
            'MAK' => $MAK,
        ];


        $namaFasilitas = $perangkat->first()->nama_fasilitas;


        if ($namaFasilitas == 'Panitia' || $namaFasilitas == 'Supir') {
            $view = 'admin.kegiatan.bendahara.pdf_panitia';
        } elseif ($namaFasilitas == 'Narasumber') {
            $view = 'admin.kegiatan.bendahara.pdf_narasumber';
        } elseif ($namaFasilitas == 'Moderator') {
            $view = 'admin.kegiatan.bendahara.pdf_moderator';
        } else {

            return response()->json(['error' => 'View untuk fasilitas tidak ditemukan.'], 404);
        }

        // Buat file PDF menggunakan view yang sesuai
        $pdf = PDF::loadView($view, compact('datas'));
        $pdf->setPaper('A4', 'portrait');

        // Simpan ke penyimpanan
        $filePath = $pdf->output();
        Storage::disk('public')->put('dokumen-kegiatans/surtugKegiatan.pdf', $filePath);

        // Stream PDF ke browser
        return $pdf->stream('Daftar Pembayaran.pdf');
    }

    public function pdf1(Request $request)
    {

        // Mendapatkan data yang dikirimkan melalui query string
        $data = json_decode($request->query('data'), true);
        $perangkatName = $request->query('perangkat');

        // dd($data);
        // Validasi jika data atau perangkatName kosong
        if (!$data || !$perangkatName) {
            return response()->json(['message' => 'Data atau perangkat tidak valid'], 400);
        }

        // Memfilter data_utama berdasarkan perangkat
        $filteredDataUtama = array_filter($data['data_utama'], function ($item) use ($perangkatName) {
            return $item['perangkat'] === $perangkatName;
        });

        // Reset indeks array dan ambil elemen pertama
        $akun_id = null;
        if (!empty($filteredDataUtama)) {
            $filteredDataUtama = array_values($filteredDataUtama);  // Mereset indeks array
            $akun_id = $filteredDataUtama[0]['mak'];  // Mengambil nilai 'mak' dari elemen pertama
        }
        // dd($akun_id);

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
            ->where('akun_x_rkakls.id', $akun_id)
            ->first();

        // dd($akun_id,$akuns, $filteredDataUtama);

        $MAK = $akuns->kode_satker.'.'.$akuns->kode_program.'.'.$akuns->kode_kegiatan.'.'.$akuns->kode_output.'.'.$akuns->kode_sub_output.'.'.$akuns->kode_komponen.'.'.$akuns->kode_sub_kegiatan.'.'.$akuns->kode_akun;
        // dd($MAK);

        // Menghitung total jumlah_honorarium
        $subTotal_jumlah = array_reduce($filteredDataUtama, function ($carry, $item) {
            return $carry + ($item['jumlah_honorarium'] ?? 0);
        }, 0);

        // dd($subTotal_jumlah);

        // Menghitung total pph
        $subTotal_pph = array_reduce($filteredDataUtama, function ($carry, $item) {
            return $carry + ($item['pph'] ?? 0);
        }, 0);

        // Menghitung total nominal_honorarium
        $subTotal_nominal = array_reduce($filteredDataUtama, function ($carry, $item) {
            return $carry + ($item['nominal_honorarium'] ?? 0);
        }, 0);

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

        // dd($perangkatName, $data, $filteredDataUtama);

        $perangkat = DB::table('data_perjadinkegiatans as ip')
            ->join('perangkat_acaras as dp', 'dp.data_perjadin_kegiatan', '=', 'ip.id')  // join perangkat acara dengan data perjadin kegiatan
            ->join('pegawais as p', 'dp.pegawai_id', '=', 'p.id')
            ->leftJoin('keuangan_perjadinkegiatans as kp', 'dp.id', '=', 'kp.perangkat_acara')
            ->leftJoin('kebutuhans as kb', 'kp.kebutuhan_id', '=', 'kb.id')
            ->join('fasilitas', 'dp.fasilitas_id', '=', 'fasilitas.id')  // join dengan fasilitas berdasarkan perangkat acara
            ->select(
                'p.id as idPegawai',
                'p.nama_lengkap',
                'p.pangkat',
                'p.golongan',
                'dp.posisi',
                'dp.detail_satuan',
                'dp.satuan',
                'dp.sebagai',
                'dp.status',
                'dp.fasilitas_id',
                'fasilitas.nama_fasilitas',

                DB::raw('MAX(dp.id) as idPerangkatAcara'),
                DB::raw('MAX(kp.id) as idKeuangan'),
                DB::raw('MAX(kp.data_perjadinkegiatan) as idKegiatan'),
                DB::raw('MAX(ip.nama_kegiatan) as NamaKegiatan')
            )
            ->groupBy(
                'p.id',
                'p.nama_lengkap',
                'p.pangkat',
                'p.golongan',
                'dp.posisi',
                'dp.detail_satuan',
                'dp.satuan',
                'dp.sebagai',
                'dp.status',
                'dp.fasilitas_id',
                'fasilitas.nama_fasilitas'
            )
            ->where('fasilitas.nama_fasilitas', $perangkatName)
            ->get();

        // Pastikan data ditemukan
        if ($perangkat->isEmpty()) {
            abort(404, 'Fasilitas tidak ditemukan.');
        }

       // Check the 'nama_fasilitas' and return different views based on its value
    $namaFasilitas = $perangkat->first()->nama_fasilitas;

    if ($namaFasilitas == 'Panitia' || $namaFasilitas == 'Supir'){
        // Redirect to the 'printpanitia' view for panitia
        return view('admin.kegiatan.bendahara.printpanitia', [
            'title' => 'printpdf',
            'idKegiatan' => $data['id_kegiatan'],
            'judul' => $data['judul'],
            'tanggal' => $data['tanggal'],
            'perangkat' => $perangkatName ,
            'pegawaiMaster' => $pegawaiMaster ,
            'pegawaiBendahara' => $pegawaiBendahara,
            'subTotal_jumlah' => $subTotal_jumlah ,
            'subTotal_pph' => $subTotal_pph,
            'subTotal_nominal' => $subTotal_nominal,
            'MAK' => $MAK,
            'datas' => $filteredDataUtama ,
        ]);
    } elseif ($namaFasilitas == 'Narasumber') {
        // Redirect to the 'printnarasumber' view for narasumber
        return view('admin.kegiatan.bendahara.printnarasumber', [
            'title' => 'printpdf',
            'idKegiatan' => $data['id_kegiatan'],
            'judul' => $data['judul'],
            'tanggal' => $data['tanggal'],
            'perangkat' => $perangkatName ,
            'pegawaiMaster' => $pegawaiMaster ,
            'pegawaiBendahara' => $pegawaiBendahara,
            'subTotal_jumlah' => $subTotal_jumlah ,
            'subTotal_pph' => $subTotal_pph,
            'subTotal_nominal' => $subTotal_nominal,
            'MAK' => $MAK,
            'datas' => $filteredDataUtama ,
        ]);
    } elseif ($namaFasilitas == 'Moderator') {
        // Redirect to the 'printmoderator' view for moderator
        return view('admin.kegiatan.bendahara.printmoderator', [
            'title' => 'printpdf',
            'idKegiatan' => $data['id_kegiatan'],
            'judul' => $data['judul'],
            'tanggal' => $data['tanggal'],
            'perangkat' => $perangkatName ,
            'pegawaiMaster' => $pegawaiMaster ,
            'pegawaiBendahara' => $pegawaiBendahara,
            'subTotal_jumlah' => $subTotal_jumlah ,
            'subTotal_pph' => $subTotal_pph,
            'subTotal_nominal' => $subTotal_nominal,
            'MAK' => $MAK,
            'datas' => $filteredDataUtama ,
        ]);
    } else {
        // If none of the conditions match, return a default view
        return view('admin.kegiatan.bendahara.printdefault', [
            'title' => 'printpdf',
            'idKegiatan' => $data['id_kegiatan'],
            'judul' => $data['judul'],
            'tanggal' => $data['tanggal'],
            'perangkat' => $perangkatName ,
            'pegawaiMaster' => $pegawaiMaster ,
            'pegawaiBendahara' => $pegawaiBendahara,
            'subTotal_jumlah' => $subTotal_jumlah ,
            'subTotal_pph' => $subTotal_pph,
            'subTotal_nominal' => $subTotal_nominal,
            'MAK' => $MAK,
            'datas' => $filteredDataUtama ,
        ]);
    }
    }
}
