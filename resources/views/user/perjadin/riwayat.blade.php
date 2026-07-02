@extends('user.templates.template')
@php
    $activeVersi = \App\Models\Versi::where('status', 'aktif')->first() ?? (object) ['id' => '-1','versi' => 'Default Versi'];
@endphp
<style>
.btn-status {
    min-width: 100px; /* Lebar minimum yang lebih kecil */
    height: 30px;     /* Tinggi tombol lebih kecil */
    padding: 4px 8px; /* Padding yang lebih kecil */
    text-align: center; /* Pusatkan teks di tengah */
    font-size: 12px;   /* Ukuran teks lebih kecil */
    border-radius: 6px; /* Sudut tombol yang lebih halus dan kecil */
}

.badge-count {
    font-size: 10px; /* Ukuran teks badge lebih kecil */
    padding: 3px 6px; /* Padding badge yang lebih kecil */
    margin-left: 4px; /* Jarak kecil antara teks dan badge */
    border-radius: 6px; /* Sudut badge yang lebih halus dan kecil */
}

</style>
@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@elseif (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<!-- Awal Form Perjalanan Dinas Biasa  -->
<section id="beranda" class="pb-5 mt-5 pt-5">
    <div class="container">
        <div class="row mb-3">
            <h3 class="fw-bold text-secondary">Kegiatanku | Perjalanan Dinas</h3>
        </div>
        <div class="row mb-3">
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <!-- Draf: Kuning dengan teks hitam -->
                    <a href="{{url('/perjadin/riwayat/' . 'Draf-pengajuan')}}" class="btn btn-warning btn-status btn-sm mx-2" style="color: black;">
                        Draf <span class="badge badge-count" style="color: black;">{{ $countDraf }}</span>
                    </a>

                    <!-- Pengajuan: Biru Tua dengan teks putih -->
                    <a href="{{url('/perjadin/riwayat/' . 'pengajuan')}}" class="btn btn-primary btn-status btn-sm mx-2" style="background-color: #004085; color: white;">
                        Pengajuan <span class="badge badge-count" style="color: white;">{{ $countPengajuan }}</span>
                    </a>

                    <!-- Pelaksanaan: Biru Muda dengan teks hitam -->
                    <a href="{{url('/perjadin/riwayat/' . 'proses')}}" class="btn btn-info btn-status btn-sm mx-2" style="background-color: #87CEEB; color: black;">
                        Pelaksanaan <span class="badge badge-count" style="color: black;">{{ $countProses }}</span>
                    </a>

                    <!-- Pelaporan: Hijau Muda dengan teks hitam -->
                    <a href="{{url('/perjadin/riwayat/' . 'pelaporan')}}" class="btn btn-light-green btn-status btn-sm mx-2" style="background-color: #90EE90; color: black;">
                        Pelaporan <span class="badge badge-count" style="color: black;">{{ $countPelaporan }}</span>
                    </a>

                    <!-- Selesai: Hijau Tua dengan teks putih -->
                    <a href="{{url('/perjadin/riwayat/' . 'selesai')}}" class="btn btn-dark-green btn-status btn-sm mx-2" style="background-color: #006400; color: white;">
                        Selesai <span class="badge badge-count" style="color: white;">{{ $countSelesai }}</span>
                    </a>

                    <!-- Revisi: Oranye dengan teks hitam -->
                    <a href="{{url('/perjadin/riwayat/' . 'revisi')}}" class="btn btn-orange btn-status btn-sm mx-2" style="background-color: #FFA500; color: black;">
                        Revisi <span class="badge badge-count" style="color: black;">{{ $countRevisi }}</span>
                    </a>

                    <!-- Ditolak: Merah dengan teks putih -->
                    <a href="{{url('/perjadin/riwayat/' . 'ditolak')}}" class="btn btn-danger btn-status btn-sm mx-2" style="color: white;">
                        Ditolak <span class="badge badge-count" style="color: white;">{{ $countDitolak }}</span>
                    </a>
                </div>

                <div>
                    @if ($activeVersi && ($activeVersi->id != session('versi')))
                        <!-- Ajukan Perjalanan Dinas Baru -->
                        <a href="{{url('/perjadin')}}" class="btn btn-status btn-neon text-white mb-3 btn-sm mx-2" onclick="showAlert(event)"><i class="fa fa-plus"></i> Ajukan Perjalanan Dinas Baru</a>
                    @else
                        <!-- Ajukan Perjalanan Dinas Baru -->
                        <a href="{{url('/perjadin')}}" class="btn btn-status btn-neon text-white mb-3 btn-sm mx-2"><i class="fa fa-plus"></i> Ajukan Perjalanan Dinas Baru</a>
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
                            @if ($perjadins->isNotEmpty())
                                <div class="table-responsive">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="fw-bold text-secondary">Informasi Perjalanan</h6><br>
                                        </div>

                                    </div>
                                    <table id="example" class="table table-bordered data-table" style="width: 100%">
                                        <thead>
                                        <tr class="text-center small">
                                            <th class="th-sm">No</th>
                                            <th class="th-sm">ID</th>
                                            <th class="th-md">Judul Kegiatan</th>
                                            <th class="">Tanggal Keberangkatan</th>
                                            <th class="th-sm">Status Berlangsung</th>
                                            @if ($status === 'ditolak')
                                                <th class="th-sm">Alasan Ditolak</th>
                                             @endif
                                            <th class="th-lg-percent">Aksi</th>
                                        </tr>
                                        </thead>
                                        @foreach ($perjadins as $perjadin)
                                        <tr>
                                            <td class='text-center'>{{ $loop->iteration }}</td>
                                            <td>{{ $perjadin->idPerjadin }}</td>
                                            <td class=''>{{ $perjadin->nama_kegiatan }}</td>
                                            <td class=''>{{ $perjadin->tgl_keberangkatan }}</td>
                                            <td class='text-center'>{!! $perjadin->status_pengajuan_detail !!}</td>
                                            @if ($status === 'ditolak')
                                                <td class='text-center'>{!! $perjadin->alasan_penolakan !!}</td>
                                             @endif
                                            @if($perjadin->status_pengajuan == 'Draf-pengajuan')
                                                <td class='text-center'>
                                                    <a href="{{ url('/perjadin_step_2/' . $perjadin->idPerjadin) }}" class="page-wrap btn btn-primary btn-sm">Edit</a>
                                                    <form action="{{ route('perjadin.delete', $perjadin->idPerjadin) }}" method="POST" onsubmit="return confirm('Hapus Data Perjalan Dinas?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-decoration-none btn btn-danger btn-sm text-white">Hapus</button>
                                                    </form>
                                                </td>
                                                @elseif($perjadin->status_pengajuan == 'pengajuan')
                                                <td class='text-center'>
                                                    <a href="{{ url('/detail-perjadin/' . $perjadin->idPerjadin) }}" class="page-wrap btn btn-primary btn-sm">Detail</a>
                                                </td>
                                            @elseif($perjadin->status_pengajuan == 'proses')
                                                <td class='text-center'>
                                                    <a href="{{ url('/detail-perjadin/' . $perjadin->idPerjadin) }}" class="page-wrap btn btn-primary btn-sm">Detail</a>
                                                    @if($perjadin->is_acceptBend == 'approval-2')  <!-- Cek jika sudah di-accept oleh bendahara -->
                                                        <a href="{{ url('/note-perjadin/' . $perjadin->idPerjadin) }}" class="btn btn-warning btn-sm">Pelaporan</a>
                                                    @else
                                                        <button type="button" class="btn btn-secondary btn-sm" onclick="showModal()">Pelaporan</button>
                                                    @endif
                                                </td>
                                                @elseif($perjadin->status_pengajuan == 'ditolak')
                                                <td class='text-center'>
                                                    <a href="{{ url('/detail-perjadin/' . $perjadin->idPerjadin) }}" class="page-wrap btn btn-primary btn-sm">Detail</a>
                                                    <a href="{{ url('/perjadin/Ajukan-Ulang/' . $perjadin->idPerjadin) }}" class="page-wrap btn btn-neon btn-sm">Ajukan Ulang</a>
                                                    <form action="{{ route('perjadin.delete', $perjadin->idPerjadin) }}" method="POST" onsubmit="return confirm('Hapus Data Perjalan Dinas?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-decoration-none btn btn-danger btn-sm text-white">Hapus</button>
                                                    </form>
                                                </td>
                                                @elseif($perjadin->status_pengajuan == 'selesai')
                                                <td class='text-center'>
                                                    <a href="{{ url('/detail-perjadin/' . $perjadin->idPerjadin) }}" class="page-wrap btn btn-primary btn-sm">Detail</a>
                                                    <!-- <a href="{{ url('/detail-perjadin/' . $perjadin->idPerjadin) }}#dokumen-section" class="page-wrap btn btn-success btn-sm">Perbaharui Dokumen</a>                                   -->
                                                </td>
                                            @else
                                                <td class='text-center'>
                                                    <a href="{{ url('/detail-perjadin/' . $perjadin->idPerjadin) }}" class="page-wrap btn btn-primary btn-sm">Detail</a>
                                                    @if($perjadin->hasil)
                                                    <a href="{{ url('/note-perjadin/' . $perjadin->idPerjadin) }}" target="_blank" class="btn btn-orange btn-sm" style="background-color: #FFA500;"><i class="fa fa-pen"></i> Edit LPD</a>
                                                    <a href="{{ url('/note-perjadin-user/' . $perjadin->idPerjadin) }}" target="_blank" class="btn btn-warning btn-sm"><i class="fa fa-eye"></i> Lihat LPD</a>
                                                @else
                                                    <a href="{{ url('/note-perjadin/' . $perjadin->idPerjadin) }}" class="btn btn-warning btn-sm">Buat LPD</a>
                                                @endif
                                                    <a href="{{ url('/detail-perjadin/' . $perjadin->idPerjadin) }}#dokumen-section" class="page-wrap btn btn-success btn-sm">Unggah</a>
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
