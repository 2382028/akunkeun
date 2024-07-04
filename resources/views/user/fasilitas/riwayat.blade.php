@extends('user.templates.template')

@section('content')
    <section id="beranda" class="pb-5 mt-5 pt-5">
        <div class="container">
            <div class="row mb-3">
                <h3 class="fw-bold text-secondary">Barang Saya</h3>
            </div>
            <div class="row mb-3">
                <div class="scroll-page">
                    <a href="{{url('/riwayat_barang/' . 'pengajuan')}}" class="page-wrap btn btn-sm mb-3 {{ $status == 'pengajuan' ? 'active-link' : '' }}">Pengajuan</a>
                    <a href="{{url('/riwayat_barang/' . 'digunakan')}}" class="page-wrap btn btn-sm mb-3 {{ $status == 'digunakan' ? 'active-link' : '' }}">Barang Saya</a>
                    <a href="{{url('/riwayat_barang/'. 'diservice')}}" class="page-wrap btn btn-sm mb-3 {{ $status == 'diservice' ? 'active-link' : '' }}">Sedang Service</a>
                    <a href="{{url('/riwayat_barang/' . 'penolakan')}}" class="page-wrap btn btn-sm mb-3 {{ $status == 'penolakan' ? 'active-link' : '' }}">Penolakan</a>
                    <a href="{{url('/riwayat_barang/' . 'selesai')}}" class="page-wrap btn btn-sm mb-3 {{ $status == 'selesai' ? 'active-link' : '' }}">Riwayat Peminjaman</a>
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
                                                <a href="{{asset('/fasilitas')}}" class="btn btn-neon text-white mb-3" type="button">
                                                    <i class="fa fa-plus"></i> Pinjam Barang Baru
                                                </a>
                                            </div>                                            
                                        </div>
                                        <table id="example" class="table table-bordered data-table" style="width: 100%">
                                            <thead>
                                                <tr class="text-center small">
                                                    <th class="th-sm">No</th>
                                                    <th class="th-md">Nama Barang</th>
                                                    <th class="th-sm">Tanggal Peminjaman</th>
                                                    <th class="th-lg">Status</th>
                                                    <th class="th-lg-percent">Aksi</th>
                                                </tr>
                                            </thead>
                                            @foreach ($riwayats as $riwayat)
                                            <tr>
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
                                            <a href="{{url('/fasilitas')}}" class="btn btn-neon text-white mb-3"><i class="fa fa-plus"></i> Ajukan Peminjaman Baru</a>
            
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