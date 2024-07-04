@extends('user.templates.template')

@section('content')
    <!-- User Profile-->
    <div class="container pt-5 mt-5">
        <div class="row">
            <div class="col-sm-4 user-side">
                <ol class="navbar-nav list-group list-group-flush small ">
                    <li class="nav-item list-group-item d-flex justify-content-between align-items-start mb-3">
                        <div class="ms-2 me-auto text-secondaryi">
                            <div class="fw-bold mb-2">Biodata</div>
                            <a data-active="user_profile" class="nav-link mb-2" aria-current="page" href="{{url('/profile')}}"><i class="fa-regular fa-user"></i> Profile Saya</a>
                        </div>
                    </li>
                    <li class="nav-item list-group-item d-flex justify-content-between align-items-start mb-3">
                        <div class="ms-2 me-auto text-secondary">
                            <div class="fw-bold mb-2">Kegiatanku</div>
                            <a data-active="kegiatanku_perjadin" class="nav-link mb-2" aria-current="page" href="{{url('/perjadin/riwayat/'. 'pengajuan')}}"><i class="fa-solid fa-car"></i> Perjalanan Dinas</a>
                            <a data-active="kegiatanku_program" class="nav-link mb-2" aria-current="page" href="{{url('/kegiatan/riwayat/' . 'pengajuan')}}"><i class="fa-regular fa-calendar"></i> Program Kegiatan</a>
                        </div>
                    </li>
                    <li class="nav-item list-group-item d-flex justify-content-between align-items-start mb-3">
                        <div class="ms-2 me-auto text-secondary">
                            <div class="fw-bold mb-2">Barangku</div>
                            <a data-active="" class="nav-link mb-2" aria-current="page" href="{{url('/riwayat_barang/' . 'digunakan')}}"><i class="fa-solid fa-box"></i> Barang Saya</a>
                            <a data-active="" class="nav-link mb-2" aria-current="page" href="{{url('/fasilitas')}}"><i class="fa-solid fa-file-signature"></i> Buat Permohonan Peminjaman</a>
                            <a data-active="" class="nav-link mb-2" aria-current="page" href="{{url('/riwayat_barang/' . 'diservice')}}"><i class="fa-solid fa-gear"></i>   Service Barang</a>
                        </div>
                    </li>
                    <li class="nav-item list-group-item d-flex justify-content-between align-items-start mb-3">
                        <div class="ms-2 me-auto text-secondary">
                            <div class="fw-bold mb-2">Pengaturan</div>
                            <a data-active="" class="nav-link mb-2" aria-current="page" href="{{url('/profile/ubah-password')}}"><i class="fa-solid fa-key"></i>   Ubah Password</a>
                            <form action="{{url('/logout')}}" method="post">
                                @csrf
                                <li><button type="submit" class="btn nav-link mb-2"><i class="fa-solid fa-power-off"></i> Keluar</button></li>
                            </form>
                        </div>
                    </li>
                </ol>
            </div>
            <div class="col-sm-8 content center-item">
                <div class="row page_content page_1">
                    <div class="col-sm-12">
                        <div class="card mb-3 text-secondary border-0">
                            <div class="card-body">
                                <div class="position-relative mb-3 ">
                                    <img src="{{asset('public/assets/images/profile - header.jpeg')}}" class="card-img-top" alt="...">
                                    <img src="{{asset('public/assets/images/user-profile.png')}}" width="90px" class="bottomleft user-image" alt="">    
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <div><h5 class="card-title fw-bold">{{ auth('pegawai')->user()->nama_lengkap }}</h5></div>
                                </div>
                                <div class="row small">
                                    <div class="col-md-4 mb-3">
                                    NIP/NIK : {{ auth('pegawai')->user()->NIP_NIK }}
                                    </div>
                                    <div class="col-md-4 mb-3">
                                    Pangkat/Golongan : {{ auth('pegawai')->user()->pangkat }} / {{ auth('pegawai')->user()->golongan }}
                                    </div>
                                    <div class="col-md-4 mb-3">
                                    Pokja : {{$pokja[0]->nama_fungsi}}
                                    </div>
                                </div>
                                <hr>
                                <div class="row small user">
                                    <div class="col-md-6 mb-3 user-card-1">
                                        <div class="card shadow-sm rounded-0">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12"><i class="fa-solid fa-car"></i> Perjalanan Dinas</div>
                                                </div>
                                                <div class="row text-center">
                                                    <div class="col-md-4 ">
                                                        <p class="fw-bold display-5">{{$tpengajuan}}</p>
                                                        <p class="small">Pengajuan</p>
                                                    </div>
                                                    <div class="col-md-4 ">
                                                        <p class="fw-bold display-5">{{$tproses}}</p>
                                                        <p class="small">Proses</p>
                                                    </div>
                                                    <div class="col-md-4 ">
                                                        <p class="fw-bold display-5">{{$tselesai}}</p>
                                                        <p class="small">Selesai</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3 user-card-2">
                                        <div class="card shadow-sm rounded-0">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12"><i class="fa-regular fa-calendar"></i> Program Kegiatan</div>
                                                </div>
                                                <div class="row text-center">
                                                    <div class="col-md-4">
                                                        <p class="fw-bold display-5">{{$kpengajuan}}</p>
                                                        <p class="small">Pengajuan</p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="fw-bold display-5">{{$kproses}}</p>
                                                        <p class="small">Proses</p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="fw-bold display-5">{{$kselesai}}</p>
                                                        <p class="small">Selesai</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row small">
                                    <div class="col-md-6 mb-3 user-card-3">
                                        <div class="card shadow-sm rounded-0">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12"><i class="fa-solid fa-box"></i> Barang Saya</div>
                                                </div>
                                                <div class="row text-center">
                                                    <div class="col-md-4">
                                                        <p class="fw-bold display-5">{{$fpengajuan}}</p>
                                                        <p class="small">Pengajuan</p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="fw-bold display-5">{{$fdigunakan}}</p>
                                                        <p class="small">Barang Saya</p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="fw-bold display-5">{{$fselesai}}</p>
                                                        <p class="small">Dikembalikan</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection