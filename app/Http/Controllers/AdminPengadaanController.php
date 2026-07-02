<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
// return type redirectResponse

// import model administrator
use App\Models\Administrator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Laporan_perjadinkegiatan;

class AdminPengadaanController extends Controller
{
    // function index untuk get all data
    public function buatPengadaan($status = 'sdp')
{


    return view('admin.pengadaan.buat_pengadaan', [
        'title' => 'Buat Dokumen Pengadaan '.$status,
        'status' => $status,
    ]);
}

    public function daftarPengadaan(Request $request, $status = 'sdp')
    {
        $kwitansi = $request->input('kwitansi', 'belum'); // Default 'belum' jika tidak ada parameter 'kwitansi'
    
    if ($status == 'pengadaan-kegiatan') {
        $dataKegiatans = DB::table('data_perjadinkegiatans')
            ->leftJoin('pegawais', 'data_perjadinkegiatans.id_pengaju', '=', 'pegawais.id')
            ->leftJoin('non_pegawais', 'data_perjadinkegiatans.id_pengaju', '=', 'non_pegawais.id')
            ->leftJoin('administrators', 'data_perjadinkegiatans.id_pengaju', '=', 'administrators.id')
            ->leftJoin('keuangan_perjadinkegiatans', 'data_perjadinkegiatans.id', '=', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
            ->leftJoin('kebutuhans', 'keuangan_perjadinkegiatans.kebutuhan_id', '=', 'kebutuhans.id')
            ->select(
                'data_perjadinkegiatans.id as id_kegiatan',
                'data_perjadinkegiatans.nama_kegiatan',
                'kebutuhans.ket as ket_pengadaan',
                'kebutuhans.id as id_kebutuhan',
                DB::raw('CONCAT(DATE_FORMAT(data_perjadinkegiatans.tgl_mulai, "%d-%m-%Y %H:%i"), " s.d ", DATE_FORMAT(data_perjadinkegiatans.tgl_selesai, "%d-%m-%Y %H:%i")) as tanggal_kegiatan'),
                DB::raw('COALESCE(CONCAT("(Admin) ", administrators.username), pegawais.nama_lengkap, non_pegawais.nama_lengkap, "Tidak Ditemukan") as nama_pengusul'),
                DB::raw('CONCAT(kebutuhans.nama, " (", kebutuhans.jumlah_frekuensi, " ", kebutuhans.satuan, ")") as nama_pengadaan'),
                'keuangan_perjadinkegiatans.jumlah_harga as nominal_pengadaan'
            )
            ->where('kebutuhans.nama', 'Konsumsi')
            ->where('versi_id', session('versi'));

            
        // Filter berdasarkan kwitansi
        if ($kwitansi == 'sudah') {
            $dataKegiatans->whereNotNull('keuangan_perjadinkegiatans.no_kwitansi');
        } elseif ($kwitansi == 'belum') {
            $dataKegiatans->whereNull('keuangan_perjadinkegiatans.no_kwitansi');
        }

        $dataKegiatans = $dataKegiatans->get();

                // $kwitansi_name = '['.$id.'] Kwitansi '. $dataPengadaan->nama_pengadaan.' - '.$dataPengadaan->ket_pengadaan;
    
        // // Filter berdasarkan kwitansi
        // if ($kwitansi == 'sudah') {
        //     $dataKegiatans->where('laporan_perjadinkegiatans.nama_dokumen',$kwitansi_name);
        // } elseif ($kwitansi == 'belum') {
        //     $dataKegiatans->where('laporan_perjadinkegiatans.nama_dokumen',$kwitansi_name);
        // }

    } else {
        $dataKegiatans = DB::table('data_perjadinkegiatans')
            ->leftJoin('pegawais', 'data_perjadinkegiatans.id_pengaju', '=', 'pegawais.id')
            ->leftJoin('non_pegawais', 'data_perjadinkegiatans.id_pengaju', '=', 'non_pegawais.id')
            ->leftJoin('administrators', 'data_perjadinkegiatans.id_pengaju', '=', 'administrators.id')
            ->leftJoin('keuangan_perjadinkegiatans', 'data_perjadinkegiatans.id', '=', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
            ->leftJoin('kebutuhans', 'keuangan_perjadinkegiatans.kebutuhan_id', '=', 'kebutuhans.id')
            ->select(
                'data_perjadinkegiatans.id as id_kegiatan',
                'data_perjadinkegiatans.nama_kegiatan',
                'kebutuhans.ket as ket_pengadaan',
                'kebutuhans.id as id_kebutuhan',
                DB::raw('CONCAT(DATE_FORMAT(data_perjadinkegiatans.tgl_mulai, "%d-%m-%Y %H:%i"), " s.d ", DATE_FORMAT(data_perjadinkegiatans.tgl_selesai, "%d-%m-%Y %H:%i")) as tanggal_kegiatan'),
                DB::raw('COALESCE(CONCAT("(Admin) ", administrators.username), pegawais.nama_lengkap, non_pegawais.nama_lengkap, "Tidak Ditemukan") as nama_pengusul'),
                DB::raw('CONCAT(kebutuhans.nama, " (", kebutuhans.jumlah_frekuensi, " ", kebutuhans.satuan, ")") as nama_pengadaan'),
                'keuangan_perjadinkegiatans.jumlah_harga as nominal_pengadaan'
            )
            ->where('versi_id', session('versi'))
            ->get();
    
        }


        return view('admin.pengadaan.daftar_pengadaan', [
            'title' => 'Daftar Dokumen Pengadaan '.$status,
            'status' => $status,
            'kwitansi' => $kwitansi,
            'dataKegiatans' => $dataKegiatans,
        ]);
    }

public function storePengadaan(Request $request)
{
    // Validasi input data
    $validatedData = $request->validate([
        'no_dokumen' => 'required|string|max:255',
        'tgl_dokumen' => 'required|date',
        'nama_pengadaan' => 'required|string',
        'kode_rup' => 'required|string|max:255',
        'metode_pengadaan' => 'required|string|max:255',
        'pejabat_pengadaan' => 'required|string|max:255',
        'nilai_hps' => 'required|numeric',
        'terbilang_hps' => 'required|string|max:255',
    ]);

    // Menyimpan data ke dalam database
    DB::table('dokumen_pengadaan')->insert([
        'no_dokumen' => $validatedData['no_dokumen'],
        'tgl_dokumen' => $validatedData['tgl_dokumen'],
        'nama_pengadaan' => $validatedData['nama_pengadaan'],
        'kode_rup' => $validatedData['kode_rup'],
        'metode_pengadaan' => $validatedData['metode_pengadaan'],
        'pejabat_pengadaan' => $validatedData['pejabat_pengadaan'],
        'nilai_hps' => $validatedData['nilai_hps'],
        'terbilang_hps' => $validatedData['terbilang_hps'],
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Redirect ke halaman sukses dengan pesan berhasil
    return redirect()->back()->with('success', 'Data berhasil disimpan!');
}

// function create untuk tambah data
    public function create(): View
    {
        return view('admin.kelola_user.administrator', ['title' => 'Data Administrator',]);
    }

    // function store untuk proses data
    public function store(Request $request): RedirectResponse
    {

        DB::table('administrators')->insertOrIgnore([
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('admin')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    // function edit untuk find edit data by id
    public function edit(string $id): View
    {
        // get admin by id
        return view('admin.kelola_user.detail_administrator', [
            'admin' => Administrator::findOrFail($id),
            'title' => 'Data Administrator',
        ]);
    }

    // function update untuk update data by id
    public function update(Request $request, $id): RedirectResponse
    {
        $admin = Administrator::findOrFail($id);
        $admin->update([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
            'updated_at' => now()
        ]);

        return redirect()->route('admin.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function infoPengadaan(){
        
        return view('admin.pengadaan.info_pengadaan', [
            'title' => 'Buat Dokumen Pengadaan '
        ]);
        }
    // function destroy untuk hapus data by id
    public function destroy($id): RedirectResponse
    {
        // get by id
        $admin = Administrator::findOrFail($id);

        //delete post
        $admin->delete();

        //redirect to index
        return redirect('admin')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function detailPengadaanKegiatan($id,$kebutuhanId)
{
    // Query to fetch the 'kegiatan' data
    $kegiatan = DB::table('data_perjadinkegiatans')->where('id', $id)->first(); // Assuming 'kegiatans' is your table

    // Existing queries
    $perangkatPegawai = DB::table('perangkat_acaras')
    ->join('pegawais', 'pegawai_id', '=', 'pegawais.id')
    ->join('fasilitas', 'fasilitas_id', '=', 'fasilitas.id')
    ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
    ->select('perangkat_acaras.id as idPerangkat', 'pegawais.nama_lengkap', 'pegawais.golongan', 'pegawais.pangkat', 'perangkat_acaras.status', 'perangkat_acaras.sebagai', 'fasilitas.nama_fasilitas', 'perangkat_acaras.fasilitas_id', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
    ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
    ->where('keuangan_perjadinkegiatans.kode', 'honor')
    ->get();

    $mobilities = DB::table('mobilitas_perjadinkegiatans')
    ->select(
        'mobilitas_perjadinkegiatans.id as idMobilitas',
        'mobilitas_perjadinkegiatans.mobilitas',
        'mobilitas_perjadinkegiatans.tujuan_penggunaan',
        'mobilitas_perjadinkegiatans.tgl_mulai',
        'mobilitas_perjadinkegiatans.tgl_selesai',
        'mobilitas_perjadinkegiatans.status',
        'mobilitas_perjadinkegiatans.unit'
    )
    ->where('mobilitas_perjadinkegiatans.data_perjadinkegiatan', $id)  // Filtering by kegiatan ID
    ->get();



    $supir = DB::table('perangkat_acaras')
        ->join('pegawais', 'pegawai_id', '=', 'pegawais.id')
        ->join('fasilitas', 'fasilitas_id', '=', 'fasilitas.id')
        ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
        ->join('operasionals', 'keuangan_perjadinkegiatans.operasional', '=', 'operasionals.id')
        ->join('mobilitas_perjadinkegiatans', 'mobilitas_perjadinkegiatans.id', '=', 'operasionals.data_perjadin_kegiatan')
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
            'mobilitas_perjadinkegiatans.tujuan_penggunaan'
        )
        ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
        ->get();

    $perangkatNonPegawai = DB::table('perangkat_acaras')
        ->join('non_pegawais', 'non_pegawai_id', '=', 'non_pegawais.id')
        ->join('fasilitas', 'fasilitas_id', '=', 'fasilitas.id')
        ->join('keuangan_perjadinkegiatans', 'keuangan_perjadinkegiatans.perangkat_acara', '=', 'perangkat_acaras.id')
        ->select('perangkat_acaras.id as idPerangkat', 'non_pegawais.nama_lengkap', 'non_pegawais.golongan', 'non_pegawais.pangkat', 'perangkat_acaras.status', 'perangkat_acaras.sebagai', 'fasilitas.nama_fasilitas', 'perangkat_acaras.fasilitas_id', 'keuangan_perjadinkegiatans.data_perjadinkegiatan')
        ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)
        ->where('keuangan_perjadinkegiatans.kode', 'honor')
        ->get();

    $perangkats = $perangkatPegawai->merge($perangkatNonPegawai);

    $dataPengadaan = DB::table('kebutuhans')
    // ->leftJoin('data_perjadinkegiatans', 'keuangan_perjadinkegiatans.data_perjadinkegiatan', '=', 'data_perjadinkegiatans.id')
    ->leftJoin('keuangan_perjadinkegiatans', 'kebutuhans.id', '=', 'keuangan_perjadinkegiatans.kebutuhan_id')
    ->select(
        DB::raw('CONCAT(kebutuhans.nama, " (", kebutuhans.jumlah_frekuensi, " ", kebutuhans.satuan, ")") as nama_pengadaan'),
        'kebutuhans.ket as ket_pengadaan',
        'keuangan_perjadinkegiatans.jumlah_harga as nominal_pengadaan',
        'keuangan_perjadinkegiatans.no_kwitansi',
        'keuangan_perjadinkegiatans.tgl_kwitansi',
        'keuangan_perjadinkegiatans.id as id_keuangan',
    )
    ->where('keuangan_perjadinkegiatans.data_perjadinkegiatan', $id)  // Pastikan $id sudah didefinisikan
    ->where('kebutuhans.id', $kebutuhanId)  // Pastikan $id sudah didefinisikan
    // ->where('data_perjadinkegiatans.versi_id', session('versi'))  // Pastikan session('versi') tersedia
    ->first();

    $kwitansi_name = '['.$id.'] Kwitansi '. $dataPengadaan->nama_pengadaan.' - '.$dataPengadaan->ket_pengadaan;
    $isKwitansiExists = DB::table('laporan_perjadinkegiatans')
        ->where('nama_dokumen', $kwitansi_name)
        ->exists();

        // dd($isKwitansiExists);
    
    // Pass the retrieved $kegiatan to the view
    return view('admin.pengadaan.detail_pengadaan-kegiatan',[
        'title' => 'Kegiatanku',
        'active' => 'kegiatanku_perjadin',
        "kegiatan" => $kegiatan,  // Pass kegiatan variable here
        "perangkatPegawais" => $perangkatPegawai,
        "supirs" => $supir,
        'perangkats' => $perangkats,
        "perangkatNonPegawais" => $perangkatNonPegawai,
       
        'isKwitansiExists' => $isKwitansiExists,
        'dataPengadaan' => $dataPengadaan,
        "dokumens" => Laporan_perjadinkegiatan::where('data_perjadin_kegiatan', $id)->get()
    ]);
}

public function storeDokPengadaanKegiatan(Request $request)
    {
        $validationData = $request->validate([
            'file' => 'required|mimes:pdf|file|max:2048',
        ]);

        $dokumenExists = DB::table('laporan_perjadinkegiatans')
            ->where('nama_dokumen', $request->nama_dokumen)  // Pastikan $id sudah didefinisikan
            ->first();

            // dd(empty($dokumenExists));

        if (empty($dokumenExists)) {
            DB::table('laporan_perjadinkegiatans')->insertOrIgnore([
                'nama_dokumen' => $request->nama_dokumen,
                'file' => $validationData['file'] = $request->file('file')->store('dokumen-kegiatans', 'public'),
                'data_perjadin_kegiatan' => $request->kegiatanId,
                'status' => 'diajukan',
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

            DB::table('keuangan_perjadinkegiatans')
                ->where('id', $request->keuanganId)
                ->update([
                    'no_kwitansi' => $request->no_kwitansi,
                    'tgl_kwitansi' => $request->tgl_kwitansi,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

            return redirect()->route('daftar-pengadaan', ['status' => 'pengadaan-kegiatan'])
            ->with('success', 'Data berhasil disimpan!');
        } else {
            DB::table('laporan_perjadinkegiatans')
            ->where('id', $dokumenExists->id)
            ->update([
                'nama_dokumen' => $request->nama_dokumen,
                'file' => $validationData['file'] = $request->file('file')->store('dokumen-kegiatans', 'public'),
                'data_perjadin_kegiatan' => $request->kegiatanId,
                'status' => 'diajukan',
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

            DB::table('keuangan_perjadinkegiatans')
                ->where('id', $request->keuanganId)
                ->update([
                    'no_kwitansi' => $request->no_kwitansi,
                    'tgl_kwitansi' => $request->tgl_kwitansi,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]);

            return redirect()->route('daftar-pengadaan', ['status' => 'pengadaan-kegiatan'])
            ->with('success', 'Data berhasil disimpan!');
        }
    }
}
