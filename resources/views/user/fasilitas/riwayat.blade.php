@extends('user.templates.sidebar')

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
</style>

@section('content')
    <section id="beranda" class="pb-5 pt-4">
        <div class="container">
            <div class="row mb-3">
                <h5 class="fw-bold text-secondary">Pengajuan Peminjaman</h5>
            </div>
            <div class="row mb-3">
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <!-- Pengajuan: Biru Tua dengan teks putih -->
                    <a href="{{url('/riwayat_barang/pengajuan')}}" class="btn btn-primary btn-status btn-sm {{ $status === 'pengajuan' ? 'status-active' : '' }}" style="background-color: #004085; color: white;">
                        Pengajuan <span class="badge badge-count" style="color: white;">{{ $countPengajuan ?? 0 }}</span>
                    </a>

                    <!-- Barang Saya: Biru Muda dengan teks hitam -->
                    <a href="{{url('/riwayat_barang/digunakan')}}" class="btn btn-info btn-status btn-sm {{ $status === 'digunakan' ? 'status-active' : '' }}" style="background-color: #87CEEB; color: black;">
                        Barang Saya <span class="badge badge-count" style="color: black;">{{ $countDigunakan ?? 0 }}</span>
                    </a>

                    <!-- Sedang Service: Oranye dengan teks hitam -->
                    <a href="{{url('/riwayat_barang/diservice')}}" class="btn btn-orange btn-status btn-sm {{ $status === 'diservice' ? 'status-active' : '' }}" style="background-color: #FFA500; color: black;">
                        Sedang Service <span class="badge badge-count" style="color: black;">{{ $countDiservice ?? 0 }}</span>
                    </a>

                    <!-- Penolakan: Merah dengan teks putih -->
                    <a href="{{url('/riwayat_barang/penolakan')}}" class="btn btn-danger btn-status btn-sm {{ $status === 'penolakan' ? 'status-active' : '' }}" style="color: white;">
                        Penolakan <span class="badge badge-count" style="color: white;">{{ $countPenolakan ?? 0 }}</span>
                    </a>

                    <!-- Riwayat Peminjaman: Hijau Tua dengan teks putih -->
                    <a href="{{url('/riwayat_barang/selesai')}}" class="btn btn-dark-green btn-status btn-sm {{ $status === 'selesai' ? 'status-active' : '' }}" style="background-color: #006400; color: white;">
                        Riwayat Peminjaman <span class="badge badge-count" style="color: white;">{{ $countSelesai ?? 0 }}</span>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm rounded-0 border-0 ">
                        <div class="card-body content">
    
                            <!-- Pengajuan -->
                            <div class="row page_content page_1">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <div class="d-flex justify-content-between">
                                            @if ($riwayats->isNotEmpty())
                                            <div>
                                                <h6 class="fw-bold text-secondary">Pengajuan Peminjaman Barang</h6><br>
                                            </div>
                                            <div>
                                                <a href="{{asset('/fasilitas')}}" class="btn btn-neon text-white btn-sm mb-3" type="button">
                                                    <i class="fa fa-plus"></i> Pinjam Barang Baru
                                                </a>
                                            </div>                                            
                                        </div>
                                        <table id="example" class="table table-bordered table-sm data-table" style="width: 100%; font-size: 0.95rem;">
                                            <thead>
                                                <tr class="text-center small align-middle">
                                                    <th class="th-sm">No</th>
                                                    <th style="min-width: 200px;">Nama Barang</th>
                                                    <th style="min-width: 150px;">Tanggal Peminjaman</th>
                                                    <th style="min-width: 150px;">Status</th>
                                                    <th style="width: 120px; text-align: center;">Aksi</th>
                                                </tr>
                                            </thead>
                                            @foreach ($riwayats as $riwayat)
                                            <tr class="align-middle">
                                                <td class='text-center'>{{$loop->iteration}}</td>
                                                <td class=''>{{$riwayat->nama_barang}}</td>
                                                <td class=''>{{$riwayat->tgl_mulai_digunakan}}</td>
                                                <td class='text-center'>
                                                    @if($riwayat->status == 'pengajuan')
                                                        <span class="badge" style="background-color: #004085; color: white;">Pengajuan</span>
                                                    @elseif($riwayat->status == 'digunakan')
                                                        <span class="badge" style="background-color: #87CEEB; color: black;">Barang Saya</span>
                                                    @elseif($riwayat->status == 'diservice')
                                                        <span class="badge" style="background-color: #FFA500; color: black;">Sedang Service</span>
                                                    @elseif($riwayat->status == 'penolakan')
                                                        <span class="badge bg-danger text-white">Penolakan</span>
                                                    @elseif($riwayat->status == 'selesai')
                                                        <span class="badge" style="background-color: #006400; color: white;">Selesai</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($riwayat->status) }}</span>
                                                    @endif
                                                </td>
                                                <td class='text-center'>
                                                    <span class="page details">
                                                        <a href="{{url('/detail_peminjaman/' . $riwayat->idPenanggungJawab)}}" class="page-wrap btn btn-primary btn-sm">Detail</a>
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </table>
                                        @else
                                        <div class="container text-center">
                                            <img src="{{asset('public/assets/images/empty.svg')}}" class="mb-3" width="150px" alt=""><br>
                                            <h3>Tidak Ada data yang ditemukan!</h3><br>
                                            <a href="{{url('/fasilitas')}}" class="btn btn-neon text-white btn-sm mb-3"><i class="fa fa-plus"></i> Ajukan Peminjaman Baru</a>
            
                                        </div>
                                        @endif
                                    </div>
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