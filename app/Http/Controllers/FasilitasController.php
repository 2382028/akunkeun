<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Versi;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.fasilitas.index', [
            'title' => 'Fasilitas BMN LLDIKTI 4',
            'active' => 'peminjaman',
            'assets' => Asset::whereIn('status_peminjaman', ['Tidak Dipakai', 'Tidak Digunakan'])->get()
        ]);
    }

    public function peminjaman($id)
    {
        return view('user.fasilitas.peminjaman', [
            'title' => 'Form Peminjaman Barang',
            'active' => 'peminjaman',
            'asset' => Asset::find($id)
        ]);

    }

    public function riwayat($status = 'pengajuan')
    {
        $assetPinjam = DB::table('data_penanggungjawabs')
                        ->join('assets', 'data_penanggungjawabs.asset_id', '=', 'assets.id')
                        ->join('pegawais', 'data_penanggungjawabs.pegawai_id', '=', 'pegawais.id')
                        ->select('data_penanggungjawabs.id as idPenanggungJawab', 'assets.nama_barang', 'assets.status_peminjaman', 'data_penanggungjawabs.tgl_mulai_digunakan', 'data_penanggungjawabs.status', 'pegawais.nama_lengkap')
                        ->where('pegawais.id', auth('pegawai')->user()->id)
                        ->where('data_penanggungjawabs.status', $status)
                        ->get();
        return view('user.fasilitas.riwayat', [
            'title' => 'Riwayat Peminjaman Barang',
            'active' => 'barang_saya',
            'status' => $status,
            'riwayats' => $assetPinjam
        ]);

    }

    public function Detailriwayat($id)
    {
        $riwayat = DB::table('data_penanggungjawabs')
                        ->join('assets', 'data_penanggungjawabs.asset_id', '=', 'assets.id')
                        ->join('pegawais', 'data_penanggungjawabs.pegawai_id', '=', 'pegawais.id')
                        ->select('data_penanggungjawabs.id as idPenanggungJawab', 'assets.id as idAsset', 'assets.nama_merek', 'assets.nama_barang', 'assets.status_peminjaman', 'assets.status_kondisi','data_penanggungjawabs.tgl_mulai_digunakan', 'data_penanggungjawabs.status', 'pegawais.nama_lengkap')
                        ->where('data_penanggungjawabs.id', $id)
                        ->get();
        $service = DB::table('permohonans')
                        ->join('data_penanggungjawabs', 'permohonans.data_penanggungjawab_id', '=', 'data_penanggungjawabs.id')
                        ->join('assets', 'data_penanggungjawabs.asset_id', '=', 'assets.id')
                        ->join('pegawais', 'data_penanggungjawabs.pegawai_id', '=', 'pegawais.id')
                        ->select('permohonans.tgl_permohonan', 'permohonans.tgl_pemeriksaan', 'permohonans.tgl_pengerjaan', 'permohonans.status', 'data_penanggungjawabs.asset_id as idAsset', 'data_penanggungjawabs.pegawai_id as idPegawai', 'data_penanggungjawabs.id as idPenanggungjawab', 'assets.nama_barang')
                        ->where('data_penanggungjawabs.id', $id)
                        ->get();
        // ddd($riwayat);
        return view('user.fasilitas.detail', [
            'title' => 'Riwayat Peminjaman Barang',
            'active' => 'barang_saya',
            'riwayats' => $riwayat,
            'services' => $service
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
        DB::table('data_penanggungjawabs')->insertOrIgnore([
            'tgl_mulai_digunakan' => $request->tgl_peminjaman,
            'tgl_selesai' => $request->tgl_selesai,
            'asset_id' => $request->idAsset,
            'pegawai_id' => auth('pegawai')->user()->id,
            'status' => 'pengajuan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        db::table('assets')
        ->where('id', $request->idAsset)
        ->update([
            'status_peminjaman' => 'pengajuan',
            'updated_at' => now(),
        ]);

        return redirect()->route('riwayat_peminjaman_BMN', ['status' => 'pengajuan'])->with('success', 'Permohonan peminjaman telah berhasil  diajukan. Silakan tunggu persetujuan dari pihak BMN!');

    }

    public function storePermohonan(Request $request)
    {
        $versi = Versi::where('status', 'aktif')->get();
        db::table('permohonans')->insertOrIgnore([
            'tgl_permohonan' => now(),
            'alasan_ket' => $request->keterangan,
            'status' => 'pengajuan',
            'versi_id' => $versi[0]->id,
            'asset_id' => $request->idAsset,
            'data_penanggungjawab_id' => $request->idPenanggungJawab,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        db::table('assets')
        ->where('id', $request->idAsset)
        ->update([
            'status_kondisi' => 'Proses Pengajuan Service',
            'updated_at' => now(),
        ]);

        db::table('data_penanggungjawabs')
        ->where('asset_id', $request->idAsset)
        ->update([
            'status' => 'diservice',
            'updated_at' => now(),
        ]);

        return redirect()->route('riwayat_peminjaman_BMN', ['status' => 'diservice'])->with('success', 'Permohonan service telah berhasil diajukan. Silakan tunggu  proses selanjutnya dari pihak BMN!');

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
}
