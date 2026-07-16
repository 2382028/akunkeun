@extends('user.templates.sidebar')
@php
    $activeVersi = \App\Models\Versi::where('status', 'aktif')->first() ?? (object) ['id' => '-1','versi' => 'Default Versi'];
@endphp
<style>
.btn-status {
    padding: 3px 8px;
    text-align: center;
    font-size: 12px;
    border-radius: 4px;
    white-space: nowrap;
}

.badge-count {
    font-size: 11px;
    padding: 2px 5px;
    margin-left: 4px;
    border-radius: 4px;
}
</style>
@section('content')

<!-- Awal Form Perjalanan Dinas Biasa  -->
<section id="beranda" class="pb-5 mt-4 pt-4">
    <div class="container">
        <div class="row mb-3">
            <h4 class="fw-bold text-secondary">Kegiatanku | Perjadin Kegiatan</h4>
        </div>
        <div class="row mb-3">
            <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
                <div class="d-flex flex-wrap gap-2">
                    <!-- Draf: Kuning dengan teks hitam -->
                    <a href="{{ url('/kegiatan/riwayat/' . 'Draf-pengajuan') }}" class="btn btn-warning btn-status btn-sm " style="color: black;">
                        Draf <span class="badge badge-count" style="color: black;">{{ $countDraf }}</span>
                    </a>

                    <!-- Pengajuan: Biru Tua dengan teks putih -->
                    <a href="{{ url('/kegiatan/riwayat/' . 'pengajuan') }}" class="btn btn-primary btn-status btn-sm" style="background-color: #004085; color: white;">
                        Pengajuan <span class="badge badge-count" style="color: white;">{{ $countPengajuan }}</span>
                    </a>

                    <!-- Pelaksanaan: Biru Muda dengan teks hitam -->
                    <a href="{{ url('/kegiatan/riwayat/' . 'proses') }}" class="btn btn-info btn-status btn-sm" style="background-color: #87CEEB; color: black;">
                        Pelaksanaan <span class="badge badge-count" style="color: black;">{{ $countProses }}</span>
                    </a>

                    <!-- Pelaporan: Hijau Muda dengan teks hitam -->
                    <a href="{{url('/kegiatan/riwayat/' . 'pelaporan')}}" class="btn btn-light-green btn-status btn-sm" style="background-color: #90EE90; color: black;">
                        Pelaporan <span class="badge badge-count" style="color: black;">{{ $countPelaporan }}</span>
                    </a>

                    <!-- Selesai: Hijau Tua dengan teks putih -->
                    <a href="{{ url('/kegiatan/riwayat/' . 'selesai') }}" class="btn btn-dark-green btn-status btn-sm" style="background-color: #006400; color: white;">
                        Selesai <span class="badge badge-count" style="color: white;">{{ $countSelesai }}</span>
                    </a>

                    <!-- Revisi: Oranye dengan teks hitam -->
                    <a href="{{ url('/kegiatan/riwayat/' . 'revisi') }}" class="btn btn-orange btn-status btn-sm" style="background-color: #FFA500; color: black;">
                        Revisi <span class="badge badge-count" style="color: black;">{{ $countRevisi }}</span>
                    </a>

                    <!-- Ditolak: Merah dengan teks putih -->
                    <a href="{{ url('/kegiatan/riwayat/' . 'ditolak') }}" class="btn btn-danger btn-status btn-sm" style="color: white;">
                        Ditolak <span class="badge badge-count" style="color: white;">{{ $countDitolak }}</span>
                    </a>
                </div>

                <div>
                    @if ($activeVersi && ($activeVersi->id != session('versi')))
                        <!-- Tambah Kegiatan Baru -->
                        <a  href="{{url('/kegiatan')}}" class="btn btn-status btn-neon text-white mb-3 btn-sm" onclick="showAlert(event)">
                            <i class="fa fa-plus"></i> Ajukan Kegiatan Baru
                        </a>
                    @else
                        <!-- Tambah Kegiatan Baru -->
                        <a href="{{url('/kegiatan')}}" class="btn btn-status btn-neon text-white mb-3 btn-sm">
                            <i class="fa fa-plus"></i> Ajukan Kegiatan Baru
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm rounded-0  border-0">
                    <div class="card-body content">

                        <!-- Pengajuan -->
                        <div class="row page_content page_1">
                            <div class="col-md-12">
                            @if ($kegiatans->isNotEmpty())
                                <div class="table-responsive">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="fw-bold text-secondary">Informasi Perjalanan</h6><br>
                                        </div>
                                    </div>
                                    <table id="example" class="table table-bordered table-sm data-table align-middle" style="width: 100%; font-size: 13px;">
                                        <thead>
                                        <tr class="text-center small">
                                            <th class="th-sm">No</th>
                                            <th class="th-sm">ID Kegiatan</th>
                                            <th style="width: 35%; min-width: 250px;">Judul Kegiatan</th>
                                            <th style="min-width: 100px;">Jenis Kegiatan</th>
                                            <th class="th-md">Tanggal Keberangkatan</th>
                                            <th class="th-md">Status Berlangsung</th>
                                            @if ($status === 'revisi')
                                                <th class="th-md">Alasan Revisi</th>
                                            @elseif ($status === 'ditolak')
                                                <th class="th-md">Alasan Ditolak</th>
                                            @endif
                                            <th style="width: 12%; min-width: 120px;">Aksi</th>
                                        </tr>
                                        </thead>
                                        @foreach ($kegiatans as $kegiatan)
                                        <tr>
                                            <td class='text-center'>{{ $loop->iteration }}</td>
                                            <td class='text-center'>{{ $kegiatan->idKegiatan }}</td>
                                            <td class=''>{{ $kegiatan->nama_kegiatan }}</td>
                                            <td class=''>{{ $kegiatan->jenis_kegiatan }}</td>
                                            <td class=''>{{ $kegiatan->tgl_mulai }}</td>
                                            <td class='text-center'>{!! $kegiatan->status !!}</td>
                                            @if ( $kegiatan->status_pengajuan === 'revisi' || $kegiatan->status_pengajuan === 'ditolak')
                                                <td class=''>{!! nl2br(e($kegiatan->alasan_penolakan)) !!}</td>
                                            @endif
                                            <td class='text-center'>
                                            <span class="">
                                                   
                                                    @if ($kegiatan->status_pengajuan === 'ditolak')
                                                        <a href="{{url('/detail-kegiatan/' . $kegiatan->idKegiatan)}}" class="page-wrap btn btn-primary btn-sm">Detail</a>
                                                        <a href="{{ url('/kegiatanAjukanUlang/' . $kegiatan->idKegiatan) }}" class="page-wrap btn btn-success btn-sm" style="color:rgb(0, 0, 0);">Ajukan Ulang</a>
                                                        <form action="{{ route('kegiatan.delete', $kegiatan->idKegiatan) }}" method="POST" onsubmit="return confirm('Hapus Data Kegiatan?')">
                                                                @csrf
                                                                @method('DELETE')
                                                            <button type="submit" class="text-decoration-none btn btn-danger btn-sm text-white">Hapus</button>
                                                        </form>
                                                    @else
                                                        @if ($kegiatan->status_draf === 'Draf-pengajuan')
                                                            @if($kegiatan->jenis_program === 'Penugasan')
                                                                <a href="{{url('/kegiatan_penugasan_step_2/' . $kegiatan->idKegiatan)}}" class="page-wrap btn btn-warning btn-sm">Edit</a>
                                                                <form action="{{ route('kegiatan.delete', $kegiatan->idKegiatan) }}" method="POST" onsubmit="return confirm('Hapus Data Kegiatan?')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                    <button type="submit" class="text-decoration-none btn btn-danger btn-sm text-white">Hapus</button>
                                                                </form>
                                                            @else
                                                                <a href="{{url('/kegiatan_step_2/' . $kegiatan->idKegiatan)}}" class="page-wrap btn btn-warning btn-sm" >Edit</a>
                                                                <form action="{{ route('kegiatan.delete', $kegiatan->idKegiatan) }}" method="POST" onsubmit="return confirm('Hapus Data Kegiatan?')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                    <button type="submit" class="text-decoration-none btn btn-danger btn-sm text-white">Hapus</button>
                                                                </form>
                                                            @endif
                                                        @else
                                                            @if ($kegiatan->status_pengajuan === 'proses')
                                                                <a href="{{url('/detail-kegiatan/' . $kegiatan->idKegiatan. '?tab=orang')}}" class="page-wrap btn btn-primary btn-sm">Detail</a>
                                                                <a href="{{url('/detail-kegiatan/' . $kegiatan->idKegiatan. '?tab=dokumen')}}" class="btn btn-warning btn-sm" style="color:rgb(0, 0, 0);">Ajukan Pelaporan</a>
                                                                @else
                                                                    <a href="{{url('/detail-kegiatan/' . $kegiatan->idKegiatan. '?tab=orang')}}" class="page-wrap btn btn-primary btn-sm">Detail</a>
                                                                    @if ($kegiatan->status_pengajuan === 'pelaporan')
                                                                        <a href="{{url('/detail-kegiatan/' . $kegiatan->idKegiatan. '?tab=dokumen')}}" class="btn btn-warning btn-sm" style="color:rgb(0, 0, 0);"><i class="fa fa-pen"></i> Pelaporan</a>
                                                                    @elseif ($kegiatan->status_pengajuan === 'revisi')
                                                                        <a href="{{url('/detail-kegiatan/' . $kegiatan->idKegiatan. '?tab=dokumen')}}" class="btn btn-warning btn-sm" style="color:rgb(0, 0, 0);"><i class="fa fa-file"></i> Pelaporan Ulang</a>
                                                                    @endif
                                                            @endif
                                                        @endif
                                                    @endif
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                            @else
                            <div class="container text-center">

                                <h3>Tidak Ada data yang ditemukan!</h3><br>
                            </div>
                            @endif
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
 </section>
<!-- Akhir Form Perjalanan Dinas Biasa -->

@endsection
