@extends('user.templates.sidebar')
@php
    $activeVersi = \App\Models\Versi::where('status', 'aktif')->first() ?? (object) ['id' => '-1','versi' => 'Default Versi'];
@endphp
<style>
.btn-status {
    padding: 4px 10px;
    text-align: center;
    font-size: 12px;
    border-radius: 6px;
    white-space: nowrap;
    transition: all 0.2s ease;
    position: relative;
    font-weight: 500;
}

.btn-status:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

.btn-status.status-active {
    transform: translateY(-1px);
    box-shadow: 0 0 0 3px rgba(255,255,255,0.6), 0 0 0 5px currentColor;
    font-weight: 700;
    z-index: 1;
    outline: 3px solid rgba(0,0,0,0.25);
    outline-offset: 2px;
}

.badge-count {
    font-size: 11px;
    padding: 2px 5px;
    margin-left: 4px;
    border-radius: 4px;
}

.status-indicator-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    border-radius: 6px;
    background: #f0f4ff;
    border-left: 4px solid #4a90d9;
    font-size: 12.5px;
    color: #333;
    margin-bottom: 8px;
}

.status-indicator-bar .status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    display: inline-block;
    flex-shrink: 0;
}
</style>
@section('content')

<!-- Awal Form Perjalanan Dinas Biasa  -->
<section id="beranda" class="pb-5 pt-4">
    <div class="container">
        <div class="row mb-3">
            <h4 class="fw-bold text-secondary">Riwayat Perjalanan Dinas & Program Kegiatan</h4>
        </div>
        <div class="row mb-3">
            <div class="d-flex flex-wrap gap-2 mb-2">
                <!-- Draf: Kuning dengan teks hitam -->
                <a href="{{url('/perjadin/riwayat/' . 'Draf-pengajuan')}}" class="btn btn-warning btn-status btn-sm {{ $status === 'Draf-pengajuan' ? 'status-active' : '' }}" style="color: black;">
                    Draf <span class="badge badge-count" style="color: black;">{{ $countDraf }}</span>
                </a>

                <!-- Pengajuan: Biru Tua dengan teks putih -->
                <a href="{{url('/perjadin/riwayat/' . 'pengajuan')}}" class="btn btn-primary btn-status btn-sm {{ $status === 'pengajuan' ? 'status-active' : '' }}" style="background-color: #004085; color: white;">
                    Pengajuan <span class="badge badge-count" style="color: white;">{{ $countPengajuan }}</span>
                </a>

                <!-- Pelaksanaan: Biru Muda dengan teks hitam -->
                <a href="{{url('/perjadin/riwayat/' . 'proses')}}" class="btn btn-info btn-status btn-sm {{ $status === 'proses' ? 'status-active' : '' }}" style="background-color: #87CEEB; color: black;">
                    Pelaksanaan <span class="badge badge-count" style="color: black;">{{ $countProses }}</span>
                </a>

                <!-- Pelaporan: Hijau Muda dengan teks hitam -->
                <a href="{{url('/perjadin/riwayat/' . 'pelaporan')}}" class="btn btn-light-green btn-status btn-sm {{ $status === 'pelaporan' ? 'status-active' : '' }}" style="background-color: #90EE90; color: black;">
                    Pelaporan <span class="badge badge-count" style="color: black;">{{ $countPelaporan }}</span>
                </a>

                <!-- Selesai: Hijau Tua dengan teks putih -->
                <a href="{{url('/perjadin/riwayat/' . 'selesai')}}" class="btn btn-dark-green btn-status btn-sm {{ $status === 'selesai' ? 'status-active' : '' }}" style="background-color: #006400; color: white;">
                    Selesai <span class="badge badge-count" style="color: white;">{{ $countSelesai }}</span>
                </a>

                <!-- Revisi: Oranye dengan teks hitam -->
                <a href="{{url('/perjadin/riwayat/' . 'revisi')}}" class="btn btn-orange btn-status btn-sm {{ $status === 'revisi' ? 'status-active' : '' }}" style="background-color: #FFA500; color: black;">
                    Revisi <span class="badge badge-count" style="color: black;">{{ $countRevisi }}</span>
                </a>

                <!-- Ditolak: Merah dengan teks putih -->
                <a href="{{url('/perjadin/riwayat/' . 'ditolak')}}" class="btn btn-danger btn-status btn-sm {{ $status === 'ditolak' ? 'status-active' : '' }}" style="color: white;">
                    Ditolak <span class="badge badge-count" style="color: white;">{{ $countDitolak }}</span>
                </a>
            </div>

            @php
                $statusLabels = [
                    'semua'          => ['label' => 'Semua Status', 'color' => '#6c757d'],
                    'Draf-pengajuan' => ['label' => 'Draf', 'color' => '#ffc107'],
                    'pengajuan'      => ['label' => 'Pengajuan', 'color' => '#004085'],
                    'proses'         => ['label' => 'Pelaksanaan', 'color' => '#87CEEB'],
                    'pelaporan'      => ['label' => 'Pelaporan', 'color' => '#90EE90'],
                    'selesai'        => ['label' => 'Selesai', 'color' => '#006400'],
                    'revisi'         => ['label' => 'Revisi', 'color' => '#FFA500'],
                    'ditolak'        => ['label' => 'Ditolak', 'color' => '#dc3545'],
                ];
                $currentLabel = $statusLabels[$status] ?? ['label' => ucfirst($status), 'color' => '#6c757d'];
            @endphp
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm rounded-0  border-0">
                    <div class="card-body content">
                        <!-- Pengajuan -->
                        <div class="row page_content page_1">
                            <div class="col-md-12">
                            @if ($perjadins->isNotEmpty())
                                <div class="table-responsive">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="fw-bold text-secondary">
                                                Detail Pengajuan
                                                @if ($status !== 'semua')
                                                    &mdash; <span style="color: {{ $currentLabel['color'] }}; font-size: 13px;">{{ $currentLabel['label'] }}</span>
                                                @else
                                                    &mdash; <span style="color: #6c757d; font-size: 13px;">Semua Status</span>
                                                @endif
                                            </h6><br>
                                        </div>

                                    </div>
                                    <table id="example" class="table table-bordered table-sm data-table align-middle" style="width: 100%; font-size: 13px;">
                                        <thead>
                                        <tr class="text-center small">
                                            <th class="th-sm">No</th>
                                            <th class="th-sm">ID</th>
                                            <th style="width: 35%; min-width: 250px;">Judul Kegiatan</th>
                                            <th style="min-width: 100px;">Jenis Kegiatan</th>
                                            <th style="min-width: 100px;">Metode Kegiatan</th>
                                            <th class="">Tanggal Keberangkatan</th>
                                            <th class="th-sm">Status Berlangsung</th>
                                            @if ($status === 'ditolak')
                                                <th class="th-sm">Alasan Ditolak</th>
                                             @endif
                                            <th style="width: 12%; min-width: 120px;">Aksi</th>
                                        </tr>
                                        </thead>
                                        @foreach ($perjadins as $perjadin)
                                        @php
                                            $isKeg = (isset($perjadin->tipe) && $perjadin->tipe == 'Program Kegiatan');
                                            $tipeName = $perjadin->tipe ?? 'Perjalanan Dinas';
                                            $editUrl = $isKeg ? url('/kegiatan_step_2/' . $perjadin->idPerjadin) : url('/perjadin_step_2/' . $perjadin->idPerjadin);
                                            $detailUrl = $isKeg ? url('/detail-kegiatan/' . $perjadin->idPerjadin) : url('/detail-perjadin/' . $perjadin->idPerjadin);
                                            $deleteRoute = $isKeg ? route('kegiatan.delete', $perjadin->idPerjadin) : route('perjadin.delete', $perjadin->idPerjadin);
                                            $pelaporanUrl = $isKeg ? url('/note-penugasan-kegiatan/' . $perjadin->idPerjadin) : url('/note-perjadin/' . $perjadin->idPerjadin);
                                            $ajukanUlangUrl = $isKeg ? url('/kegiatanAjukanUlang/' . $perjadin->idPerjadin) : url('/perjadin/Ajukan-Ulang/' . $perjadin->idPerjadin);
                                            $lihatLpdUrl = $isKeg ? url('/note-penugasan-kegiatan-user/' . $perjadin->idPerjadin) : url('/note-perjadin-user/' . $perjadin->idPerjadin);
                                        @endphp
                                        <tr>
                                            <td class='text-center'>{{ $loop->iteration }}</td>
                                            <td>{{ $perjadin->idPerjadin }}</td>
                                            <td class=''>{{ $perjadin->nama_kegiatan }}</td>
                                            <td class=''>
                                                @if($tipeName == 'Program Kegiatan')
                                                    <span class="badge" style="background-color: #456dee; color: white;">{{ $tipeName }}</span>
                                                @else
                                                    <span class="badge" style="background-color: #fda10d; color: white;">{{ $tipeName }}</span>
                                                @endif
                                            </td>
                                            <td class=''>{{ $perjadin->jenis_kegiatan ?? '-' }}</td>
                                            <td class=''>{{ $perjadin->tgl_keberangkatan }}</td>
                                            <td class='text-center'>{!! $perjadin->status_pengajuan_detail !!}</td>
                                            @if ($status === 'ditolak')
                                                <td class='text-center'>{!! $perjadin->alasan_penolakan !!}</td>
                                             @endif
                                            @if($perjadin->status_pengajuan == 'Draf-pengajuan')
                                                <td class='text-center'>
                                                    <a href="{{ $editUrl }}" class="page-wrap btn btn-primary btn-sm">Edit</a>
                                                    <form action="{{ $deleteRoute }}" method="POST" onsubmit="return confirm('Hapus Data Kegiatan/Perjalanan Dinas?')" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-decoration-none btn btn-danger btn-sm text-white">Hapus</button>
                                                    </form>
                                                </td>
                                                @elseif($perjadin->status_pengajuan == 'pengajuan')
                                                <td class='text-center'>
                                                    <a href="{{ $detailUrl }}" class="page-wrap btn btn-primary btn-sm">Detail</a>
                                                </td>
                                            @elseif($perjadin->status_pengajuan == 'proses')
                                                <td class='text-center'>
                                                    <a href="{{ $detailUrl }}" class="page-wrap btn btn-primary btn-sm">Detail</a>
                                                    @if($isKeg)
                                                        <a href="{{ $pelaporanUrl }}" class="btn btn-warning btn-sm">Pelaporan</a>
                                                    @else
                                                        @if($perjadin->is_acceptBend == 'approval-2')
                                                            <a href="{{ $pelaporanUrl }}" class="btn btn-warning btn-sm">Pelaporan</a>
                                                        @else
                                                            <button type="button" class="btn btn-secondary btn-sm" onclick="showModal()">Pelaporan</button>
                                                        @endif
                                                    @endif
                                                </td>
                                                @elseif($perjadin->status_pengajuan == 'ditolak')
                                                <td class='text-center'>
                                                    <a href="{{ $detailUrl }}" class="page-wrap btn btn-primary btn-sm">Detail</a>
                                                    <a href="{{ $ajukanUlangUrl }}" class="page-wrap btn btn-neon btn-sm">Ajukan Ulang</a>
                                                    <form action="{{ $deleteRoute }}" method="POST" onsubmit="return confirm('Hapus Data Kegiatan/Perjalanan Dinas?')" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-decoration-none btn btn-danger btn-sm text-white">Hapus</button>
                                                    </form>
                                                </td>
                                                @elseif($perjadin->status_pengajuan == 'selesai')
                                                <td class='text-center'>
                                                    <a href="{{ $detailUrl }}" class="page-wrap btn btn-primary btn-sm">Detail</a>
                                                </td>
                                            @else
                                                <td class='text-center'>
                                                    <a href="{{ $detailUrl }}" class="page-wrap btn btn-primary btn-sm">Detail</a>
                                                    @if($perjadin->hasil)
                                                        <a href="{{ $pelaporanUrl }}" target="_blank" class="btn btn-orange btn-sm" style="background-color: #FFA500;"><i class="fa fa-pen"></i> Edit LPD</a>
                                                        <a href="{{ $lihatLpdUrl }}" target="_blank" class="btn btn-warning btn-sm"><i class="fa fa-eye"></i> Lihat LPD</a>
                                                    @else
                                                        <a href="{{ $pelaporanUrl }}" class="btn btn-warning btn-sm">Buat LPD</a>
                                                    @endif
                                                    <a href="{{ $detailUrl }}#dokumen-section" class="page-wrap btn btn-success btn-sm">Unggah</a>
                                                </td>
                                            @endif
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

<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-exclamation-triangle-fill mb-3" viewBox="0 0 16 16">
                    <path d="M8 16a2 2 0 0 1-1.732-1H1.78a2 2 0 0 1-1.732-3H14.5a2 2 0 0 1 1.732 3h-4.48A2 2 0 0 1 8 16zm.93-12.54L14 13H2L7.07 3.46a1 1 0 0 1 1.86 0zM5.002 6a.502.502 0 0 0-.53.47v3.06a.502.502 0 0 0 .53.47h6a.502.502 0 0 0 .53-.47V6.47a.502.502 0 0 0-.53-.47h-6zM8 10a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                </svg>
                <p>Aksi tidak bisa dilakukan karena belum mendapatkan Approval-1 oleh Bendahara.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function showModal() {
    var alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
    alertModal.show();
}
</script>

@endsection
