<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Logo -->
    <link rel="icon" href="{{ asset('/assets/images/icon akunkeun.png') }}" type="image/x-icon">

    {{-- select --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('/assets/css/bootstrap.min.css')}}" />    
    <!-- My CSS -->
    <link rel="stylesheet" href="{{asset('/vanila/main.css')}}">
    <link rel="stylesheet" href="{{asset('/assets/css/style.css')}}" />

    <!-- Data Tables-->
    <link rel="stylesheet" href="{{asset('/assets/css/dataTables.bootstrap5.min.css')}}" />

    <!-- SCSS -->
    <link rel="stylesheet" href="{{asset('/assets/css/main.css')}}">

    <!-- AOS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/codemirror.min.css">

    <title>{{ $title }} | Aplikasi Keuangan dan Urusan Kegiatan</title>
</head>
<body id="{{ $active }}" class="bg-white">
    <!-- Awal Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fw-bold small text-secondary fixed-top">
      <div class="container">
          <a class="navbar-brand" href="{{url('/')}}">
            <img src="{{asset('/assets/images/brand-logo.png')}}" alt="" width="150">
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0 links">
            <li class="nav-item">
              <a data-active="index" class="nav-link" aria-current="page" href="{{url('/')}}">Beranda</a>
            </li>
            <li class="nav-item">
              <a data-active="perjadin_biasa" class="nav-link" href="{{url('/perjadin')}}">Perjalanan Dinas</a>
            </li>
            <li class="nav-item">
              <a data-active="perjadin_kegiatan" class="nav-link" href="{{url('/kegiatan')}}">Buat Kegiatan</a>
            </li>
            <li class="nav-item">
              <a data-active="fasilitas" class="nav-link" href="{{url('/fasilitas')}}">Fasilitas</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-active="kegiatanku_perjadin" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Kegiatanku
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li class="nav-item">
                  <a class="nav-link dropdown-item" href="{{url('/perjadin/riwayat/' . 'pengajuan')}}">Perjalanan Dinas</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link dropdown-item" href="{{url('/kegiatan/riwayat/' . 'pengajuan')}}">Program Kegiatan</a>
                </li>
              </ul>
            </li>
          </ul>
        
          @auth
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0 links">
            <li class="nav-item">
              <a data-active="barang_saya" class="nav-link" aria-current="page" href="{{url('/riwayat_barang/' . 'pengajuan')}}"><i class="fa-solid fa-box-open"></i> <span class="hide-profile">Barang Saya</span></a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link position-relative" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-bell"></i> <span class="hide-profile">Pemberitahuan</span> 
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notif">
                    99+
                </span>
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li class="nav-item">
                  <a data-active="" class="nav-link" href=""></a>
                </li>
                <li class="nav-item">
                  <a data-active="" class="nav-link" href=""></a>
                </li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-active="profile" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-user"></i> <span class="">{{ auth('pegawai')->user()->nama_lengkap }}</span>
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{url('/profile')}}">Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <form action="{{ url('/logout') }}" method="post">
                  @csrf
                  <li><button type="submit" class="dropdown-item">Keluar</button></li>
                </form>
              </ul>
            </li>
          </ul>
          @else
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0 links">
            <li class="nav-item">
              <a data-active="barang_saya" class="btn btn-warning btn-sm small fw-bold text-secondary mx-1" aria-current="page" href="{{url('/akses')}}"><i class="fa-solid fa-box-open"></i> <span class="hide-profile">Akses Masuk</span></a>
            </li>
          </ul>
          @endauth
        </div>
      </div>
    </nav>
    @if (session()->has('success'))
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container end-0 mt-4 pt-4 position-fixed">
            <div class="show toast" role="alert" aria-live="assertive" aria-atomic="true" id="myToast">
                <div class="toast-header">
                  <strong class="me-auto">Pesan Sobat Akunkeun</strong>
                  <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    </div>
  @endif
    <!-- Akhir Navbar -->
    <div class="top-nav">
        @yield('content')
    </div>


    {{-- Footer --}}
    <section>
        <div class="container text-secondary">
            <div class="row">
              {{-- state1 --}}
              <div class="col-lg-6">
                <div class="row">
                  <div class="col-sm-6">
                      <div class="d-flex align-items-center justify-content-between footer-img">
                          <img src="{{asset('/assets/images/brand-logo.png')}}" alt="AKUNKEUN" width="120">
                          <img src="{{asset('/assets/images/LLDIKTI4 final1.png')}}" width="120" alt="LLDIKTI4">
                      </div>
                      <p class="small mt-3">Akunkeun atau Aplikasi Kegiatan dan Urusan Keuangan adalah sebuah aplikasi yang dirancang untuk memudahkan proses pelaksanaan perjalanan dinas dan pemeliharaan barang milik negara di lingkungan LLDIKTI Wilayah IV.</p>
                  </div>
                  <div class="col-sm-6 mb-3">
                      <h5 class="fw-bold">Layanan</h5>
                      <ul class="list-group list-group-flush text-secondary mt-3">
                          <li class="list-group-item small small"><a href="{{url('/perjadin')}}" class="nav-link">Perjalanan Dinas</a></li>
                          <li class="list-group-item small"><a href="{{url('/kegiatan')}}" class="nav-link">Program Kegiatan</a></li>
                          <li class="list-group-item small"><a href="{{url('/fasilitas')}}" class="nav-link">Peminjaman Assets BMN</a></li>
                          <li class="list-group-item small"><a href="{{url('/riwayat_barang/'.'digunakan')}}" class="nav-link">Permohonan Service</a></li>
                        </ul>
                  </div>
                </div>
              </div>
              {{-- state2 --}}
              <div class="col-lg-6">
                <div class="row">
                  <div class="col-sm-5 mb-3">
                      <h5 class="fw-bold">Link</h5>
                      <ul class="list-group list-group-flush text-secondary mt-3">
                          <li class="list-group-item small small"><a href="" class="nav-link">LLDIKTI4</a></li>
                          <li class="list-group-item small small"><a href="" class="nav-link">Simojang</a></li>
                          <li class="list-group-item small small"><a href="" class="nav-link">SIPPA</a></li>
                      </ul>
                  </div>

                  <div class="col-sm-7 mb-3">
                    <h5 class="fw-bold">Informasi Kontak</h5>
                    <div class="row row-cols-2  small">
                      <div class="col-2 text-end">
                        <i class="fa-solid fa-phone"></i>
                      </div>
                      <div class="col-md-10 ">
                        <p>+022 7275630, +022 7274377</p>
                      </div>
                      <div class="col-2 text-end">
                        <i class="fa-solid fa-location-dot"></i>
                      </div>
                      <div class="col-md-10">
                        <p>informasi@lldikti4.or.id</p>
                      </div>
                      <div class="col-2 text-end">
                        <i class="fa-solid fa-envelope"></i>
                      </div>
                      <div class="col-md-10">
                        <p>Jalan Penghulu H. Hasan Mustofa No. 38 Bandung 40124</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <hr class="container mt-5">
        <div class="container mb-3 text-center">
            <p class="small">copyright @2023, Akunkeun - Aplikasi Kegiatan dan Urusan Keuangan</p>
        </div>
    </section>
    
    <script src="{{asset('/assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('/assets/js/navbar.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <!-- AOS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{asset('/assets/js/aos.js')}}"></script>

    
    {{-- script --}}
    <script src="{{asset('/assets/js/script1.js')}}"></script>
    <script src="{{asset('/vanila/main.js')}}"></script>
    
    <!-- Data Table -->
    <script src="{{asset('/assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('/assets/js/dataTables.bootstrap5.min.js')}}"></script>
    <script src="{{asset('/assets/js/data-table.js')}}"></script>
    <script src="{{asset('/assets/js/preventSelect2.js')}}"></script>

    <script src="{{asset('/assets/js/toast.js')}}"></script>
    <script src="{{asset('/assets/js/preventSurtug.js')}}"></script>
    <script src="{{asset('/assets/js/preventTable.js')}}"></script>
    <script src="{{asset('/assets/js/preventDokumen.js')}}"></script>
    <script src="{{asset('/assets/js/nonPegawai.js')}}"></script>
    <script src="{{asset('/assets/js/preventSelect.js')}}"></script>

    <script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>
    <script>
    ClassicEditor
      .create(document.querySelector('#textarea2'))
      .catch(error => {
        console.error(error);
      });
    </script>


    <script>
      $(window).on('load', function() {
        $('#template').modal('show');
      });

      $(document).on('click', '#unduhTemplate', function() {
        $('#template').modal('show');
      });
    </script>

    
    
</body>
</html>