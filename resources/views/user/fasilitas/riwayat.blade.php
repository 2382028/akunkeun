@extends('user.templates.sidebar')

@section('content')
    <section id="beranda" class="pb-5 pt-4">
        <div class="container">
            <div class="row mb-3">
                <h5 class="fw-bold text-secondary">Pengajuan Peminjaman</h5>
            </div>
            <div class="row mb-4">
                <div class="col-12 d-flex flex-wrap gap-2">
                    <a href="{{url('/riwayat_barang/pengajuan')}}" class="btn rounded-pill px-4 {{ $status == 'pengajuan' ? 'btn-primary shadow-sm' : 'btn-light text-secondary border' }}">
                        <i class="fa-solid fa-file-signature me-1"></i> Pengajuan
                    </a>
                    <a href="{{url('/riwayat_barang/digunakan')}}" class="btn rounded-pill px-4 {{ $status == 'digunakan' ? 'btn-primary shadow-sm' : 'btn-light text-secondary border' }}">
                        <i class="fa-solid fa-box-open me-1"></i> Barang Saya
                    </a>
                    <a href="{{url('/riwayat_barang/diservice')}}" class="btn rounded-pill px-4 {{ $status == 'diservice' ? 'btn-primary shadow-sm' : 'btn-light text-secondary border' }}">
                        <i class="fa-solid fa-screwdriver-wrench me-1"></i> Sedang Service
                    </a>
                    <a href="{{url('/riwayat_barang/penolakan')}}" class="btn rounded-pill px-4 {{ $status == 'penolakan' ? 'btn-primary shadow-sm' : 'btn-light text-secondary border' }}">
                        <i class="fa-solid fa-ban me-1"></i> Penolakan
                    </a>
                    <a href="{{url('/riwayat_barang/selesai')}}" class="btn rounded-pill px-4 {{ $status == 'selesai' ? 'btn-primary shadow-sm' : 'btn-light text-secondary border' }}">
                        <i class="fa-solid fa-clock-rotate-left me-1"></i> Riwayat Peminjaman
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
                                                <td class='text-center'>{{$riwayat->status}}</td>
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