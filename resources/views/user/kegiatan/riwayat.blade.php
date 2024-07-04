@extends('user.templates.template')

@section('content')

<!-- Awal Form Perjalanan Dinas Biasa  -->
<section id="beranda" class="pb-5 mt-5 pt-5">
    <div class="container">
        <div class="row mb-3">
            <h3 class="fw-bold text-secondary">Kegiatanku | Perjadin Kegiatan</h3>
        </div>
        <div class="row mb-3">
            <div class="scroll-page">
                <a href="{{url('/kegiatan/riwayat/' . 'Draf-pengajuan')}}" class="page-wrap btn btn-sm mb-3 {{ $status == 'Draf-pengajuan' ? 'active-link' : 'Draf-pengajuan' }}">Draf</a>
                <a href="{{url('/kegiatan/riwayat/' . 'pengajuan')}}" class="page-wrap btn btn-sm mb-3 {{ $status == 'pengajuan' ? 'active-link' : '' }}">Pengajuan</a>
                <a href="{{url('/kegiatan/riwayat/' . 'revisi')}}" class="page-wrap btn btn-sm mb-3 {{ $status == 'revisi' ? 'active-link' : '' }}">Revisi</a>
                <a href="{{url('/kegiatan/riwayat/'. 'proses')}}" class="page-wrap btn btn-sm mb-3 {{ $status == 'proses' ? 'active-link' : '' }}">Sedang Berlangsung</a>
                <a href="{{url('/kegiatan/riwayat/' . 'ditolak')}}" class="page-wrap btn btn-sm mb-3 {{ $status == 'ditolak' ? 'active-link' : '' }}">Penolakan</a>
                <a href="{{url('/kegiatan/riwayat/' . 'selesai')}}" class="page-wrap btn btn-sm mb-3 {{ $status == 'selesai' ? 'active-link' : '' }}">Selesai</a>
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
                                        <div>
                                            <a href="{{url('/kegiatan')}}" class="btn btn-neon text-white mb-3"><i class="fa fa-plus"></i> Ajukan Kegiatan Baru</a>
                                        </div>                                            
                                    </div>
                                    <table id="example" class="table table-bordered data-table" style="width: 100%">
                                        <thead>
                                        <tr class="text-center small">
                                            <th class="th-sm">No</th>
                                            <th class="th-md">Judul Kegiatan</th>
                                            <th class="th-md">Jenis Kegiatan</th>
                                            <th class="th-md">Tanggal Keberangkatan</th>
                                            <th class="th-md">Status</th>
                                            <th class="th-lg-percent">Aksi</th>
                                        </tr>
                                        </thead>
                                        @foreach ($kegiatans as $kegiatan)
                                        <tr>
                                            <td class='text-center'>{{ $loop->iteration }}</td>
                                            <td class=''>{{ $kegiatan->nama_kegiatan }}</td>
                                            <td class=''>{{ $kegiatan->jenis_kegiatan }}</td>
                                            <td class=''>{{ $kegiatan->tgl_mulai }}</td>
                                            <td class='text-center'>{{ $kegiatan->status }}</td>
                                            <td class='text-center'>
                                                <span class="page details">
                                                    <a href="{{url('/detail-kegiatan/' . $kegiatan->idKegiatan)}}" class="page-wrap btn btn-primary btn-sm">Detail</a>
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                            @else
                            <div class="container text-center">
                                <img src="{{asset('public/assets/images/empty.svg')}}" class="mb-3" width="150px" alt=""><br>
                                <h3>Tidak Ada data yang ditemukan!</h3><br>
                                <a href="{{url('/kegiatan')}}" class="btn btn-neon text-white mb-3"><i class="fa fa-plus"></i> Ajukan Program Kegiatan Baru</a>

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