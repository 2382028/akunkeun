<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width initial-scale=10" />

  <!-- Logo -->
  <link rel="icon" href="{{asset('/assets/images/icon akunkeun.png')}}" type="image/x-icon">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="{{asset('/assets/css/bootstrap.min.css')}}" />

  <!-- My CSS -->
  <link rel="stylesheet" href="{{asset('/assets/css/style-admin.css')}}" />
  {{-- <link rel="stylesheet" href="{{asset('vanila/main.css')}}" /> --}}

  <!-- Data Tables-->
  <link rel="stylesheet" href="{{asset('/assets/css/dataTables.bootstrap5.min.css')}}" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Select2 -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <!-- Table to Excel -->
  <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
  <script src="https://unpkg.com/file-saver"></script>


  <title> | Aplikasi Keuangan dan Urusan Kegiatan</title>
</head>

<body id="index">
  <!-- Awal Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-white shadow-sm noprint" style="color: black">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
        <span class="navbar-toggler-icon" data-bs-target="#offcanvasExample"></span>
      </button>
      <a class="navbar-brand py-0 noprint" href="{{url('/dashboard')}}">
        <img src="{{asset('/assets/images/icon akunkeun.png')}}" alt="" width="50">
        <h5 class="d-inline-block align-text-top fw-bold pt-1">AKUNKEUN</h5>
      </a>
      <button class="btn btn-white hide-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
        <span class="navbar-toggler-icon" data-bs-target="#offcanvasExample"></span>
      </button>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavBar" aria-controls="topNavBar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="topNavBar">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item mb-2 pt-2">
            <a class="nav-link py-0 small" href="#">
              <i class="fa-solid fa-bell"></i> Pemberitahuan
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle small" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fa-solid fa-user"></i> {{ auth('administrator')->user()->email }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              @if (auth('administrator')->user()->role == 'Master')
              <li><a class="dropdown-item" href="{{url('/pengaturan')}}">Pengaturan</a></li>
              @endif
              <li>
                <hr class="dropdown-divider">
              </li>
              <form action="{{url('/logout-admin')}}" method="post">
                @csrf
                <li><button type="submit" class="dropdown-item">Keluar</button></li>
              </form>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- Akhir Navbar -->

  <!-- Sidebar -->
  <div class="container-fluid noprint">
    <div class="offcanvas offcanvas-start sidebar-nav" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
      <div class="offcanvas-header">
        <a class="navbar-brand py-0" href="{{url('/dashboard')}}">
          <img src="{{asset('/images/Tut-Wuri-Handayani.png.crdownload')}}" alt="" width="50">
          <h5 class="d-inline-block align-text-top fw-bold pt-1 text-white">AKUNKEUN</h5>
        </a>
        <button type="button" class="btn-close text-reset text-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <nav class="navbar-dark">
          <ul class="navbar-nav">
            <li>
              <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                  <h2 class="accordion-header ">
                    <a href="{{url('/dashboard')}}" class="accordion-button collapsed text-decoration-none custom-button remove-button py-3">
                      <span class="me-2"><i class="fa-solid fa-gauge active"></i></span>
                      <span>Dashboard</span>
                    </a>
                  </h2>
                </div>
                @if ((auth('administrator')->user()->role == 'Master') | (auth('administrator')->user()->role == 'Bendahara'))
                <div class="accordion-item">
                  <h2 class="accordion-header " id="flush-headingOne">
                    <a href="" class="accordion-button collapsed text-decoration-none custom-button py-3" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                      <span class="me-2"><i class="fa-solid fa-table-columns"></i></span>
                      <span>Referensi</span>
                    </a>
                  </h2>
                  <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                    <ul class="navbar-nav list-group list-group-flush ps-5">
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{ url('admin-iku') }}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2 "><i class="fa-solid fa-file"></i></span>
                          <span>IKU</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/sbm')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2 "><i class="fa-solid fa-file"></i></span>
                          <span>SBM</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/admin-rkakl_satker')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2 "><i class="fa-solid fa-file"></i></span>
                          <span>RKAKL</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
                @endif
                <div class="accordion-item">
                  <h2 class="accordion-header " id="flush-headingTwo">
                    <a href="" class="accordion-button collapsed text-decoration-none custom-button py-3" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                      <span class="me-2"><i class="fa-solid fa-train-subway"></i></span>
                      <span>Perjadin Langsung</span>
                    </a>
                  </h2>
                  <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                    <ul class="navbar-nav list-group list-group-flush ps-5">
                      @if ((auth('administrator')->user()->role == 'BMN') | (auth('administrator')->user()->role == 'Master'))
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/perjadin-mobilitas/' . 'pengajuan')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-car-side"></i></span>
                          <span>BMN Kendaraan</span>
                        </a>
                      </li>
                      @endif
                      @if ((auth('administrator')->user()->role == 'HKT') | (auth('administrator')->user()->role == 'Master'))
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/perjadin-HKT/' . 'pengajuan')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-envelope"></i></span>
                          <span>HKT</span>
                        </a>
                      </li>
                      @endif
                      @if ((auth('administrator')->user()->role == 'Keuangan') | (auth('administrator')->user()->role == 'Master'))
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/perjadin-keuangan/' . 'verifikasi-1')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-sack-dollar"></i></span>
                          <span>Keuangan</span>
                        </a>
                      </li>
                      @endif
                      @if ((auth('administrator')->user()->role == 'Bendahara') | (auth('administrator')->user()->role == 'Master'))
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/perjadin-bendahara/' . 'approval-1')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-money-bill-wave"></i></span>
                          <span>Bendahara</span>
                        </a>
                      </li>
                      @endif

                    </ul>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header " id="flush-headingThree">
                    <a href="" class="accordion-button collapsed text-decoration-none custom-button py-3" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                      <span class="me-2"><i class="fa-regular fa-calendar-days"></i></span>
                      <span>Perjadin Kegiatan</span>
                    </a>
                  </h2>
                  <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                    <ul class="navbar-nav list-group list-group-flush ps-5">
                      @if ((auth('administrator')->user()->role == 'Master') | (auth('administrator')->user()->role == 'BMN'))
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/kegiatan-mobilitas/' . 'pengajuan')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-car-side"></i></span>
                          <span>BMN Kendaraan</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/kegiatan-assets/' . 'pengajuan')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-building-columns"></i></span>
                          <span>BMN Aset</span>
                        </a>
                      </li>
                      @endif
                      @if ((auth('administrator')->user()->role == 'Master') | (auth('administrator')->user()->role == 'Bendahara') | (auth('administrator')->user()->role == 'Keuangan'))
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/kegiatan-keuangan/' . 'verifikasi-1')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-sack-dollar"></i></span>
                          <span>Keuangan</span>
                        </a>
                      </li>
                      @endif
                      @if ((auth('administrator')->user()->role == 'Master') | (auth('administrator')->user()->role == 'Bendahara'))
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/kegiatan-bendahara/' . 'approval-1')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-money-bill-wave"></i></span>
                          <span>Bendahara</span>
                        </a>
                      </li>
                      @endif
                    </ul>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header " id="flush-headingFour">
                    <a href="" class="accordion-button collapsed text-decoration-none custom-button py-3" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                      <span class="me-2"><i class="fa-solid fa-box-archive"></i></span>
                      <span>BMN</span>
                    </a>
                  </h2>
                  <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                    <ul class="navbar-nav list-group list-group-flush ps-5">
                      @if ((auth('administrator')->user()->role == 'Master') | (auth('administrator')->user()->role == 'BMN'))
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/data_penyedia')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-database"></i></span>
                          <span>Data Penyedia</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/data_kendaraan')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-database"></i></span>
                          <span>Data Kendaraan</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/data_ruangan')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-database"></i></span>
                          <span>Data Ruangan</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/data_assets')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-database"></i></span>
                          <span>Data Aset</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/peminjaman_asset/' . 'pengajuan')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-arrows-spin"></i></span>
                          <span>Peminjaman Aset</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/service_kendaraan_all/' . 'pengajuan')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-screwdriver-wrench"></i></span>
                          <span>Perbaikan Kendaraan</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/perbaikan_assets/' . 'pengajuan')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-screwdriver-wrench"></i></span>
                          <span>Perbaikan Aset</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/service_ruangan_all/' . 'pengajuan')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-screwdriver-wrench"></i></span>
                          <span>Perbaikan Ruangan</span>
                        </a>
                      </li>
                      @endif
                      @if ((auth('administrator')->user()->role == 'Master') | (auth('administrator')->user()->role == 'Bendahara') | auth('administrator')->user()->role == 'Keuangan')
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/service_keuangan/' . 'verifikasi-1')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-sack-dollar"></i></span>
                          <span>Keuangan</span>
                        </a>
                      </li>
                      @endif
                      @if ((auth('administrator')->user()->role == 'Master') | (auth('administrator')->user()->role == 'Bendahara'))
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/service_bendahara/' . 'approval-1')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-money-bill-wave"></i></span>
                          <span>Bendahara</span>
                        </a>
                      </li>
                      @endif
                    </ul>
                  </div>
                </div>
                @if (auth('administrator')->user()->role == 'Master')
                <div class="accordion-item">
                  <h2 class="accordion-header " id="flush-headingFive">
                    <a href="" class="accordion-button collapsed text-decoration-none custom-button py-3" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                      <span class="me-2"><i class="fa-solid fa-users-gear"></i></span>
                      <span>Kelola User</span>
                    </a>
                  </h2>
                  <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                    <ul class="navbar-nav list-group list-group-flush ps-5">
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/admin-pegawai')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-database"></i></span>
                          <span>Data Pegawai</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/admin-nonpegawai')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-database"></i></span>
                          <span>Data Non Pegawai</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/admin')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-user-lock"></i></span>
                          <span>Administrator</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
                @endif
                <div class="accordion-item">
                  <h2 class="accordion-header ">
                    <a href="{{url('/laporan')}}" class="accordion-button collapsed text-decoration-none custom-button remove-button py-3">
                      <span class="me-2"><i class="fa-solid fa-file-circle-exclamation"></i></span>
                      <span>Laporan</span>
                    </a>
                  </h2>
                </div>
                @if (auth('administrator')->user()->role == 'Master')
                <div class="accordion-item">
                  <h2 class="accordion-header ">
                    <a href="{{url('/pengaturan')}}" class="accordion-button collapsed text-decoration-none custom-button remove-button py-3">
                      <span class="me-2"><i class="fa-solid fa-gear"></i></span>
                      <span>Pengaturan</span>
                    </a>
                  </h2>
                </div>
                @endif
              </div>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>

  @if (session()->has('success'))
  <div aria-live="polite" aria-atomic="true" class="position-relative" style="z-index: 1050;">
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
  <!-- Awal Dashboard -->
  <main class="mt-5 pt-5" style="background: #D9D9D9;">
    @yield('contain')
  </main>
  <!-- Akhir Dashboard -->

  <!-- Awal Footer -->
  <section id="footer" class="bg-white noprint" style="z-index: -2;">
    <div class="container-fluid">
      <div class="text-center py-2 small fw-bold py-3">Copyright &#169; 2024. All Right Reserved.</div>
    </div>
  </section>
  <!-- Akhir Footer -->


  {{-- script --}}
  <script src="{{asset('/assets/js/script.js')}}"></script>
  <script src="{{asset('/vanila/main.js')}}"></script>

  <script src="{{asset('/assets/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('/assets/js/jquery-3.5.1.js')}}"></script>
  <script src="{{asset('/assets/js/script1.js')}}"></script>
  <script src="{{asset('/assets/js/offcanvas.js')}}"></script>

  <!-- Data Table -->
  <script src="{{asset('/assets/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('/assets/js/dataTables.bootstrap5.min.js')}}"></script>
  <script src="{{asset('/assets/js/data-table.js')}}"></script>

  <script src="{{asset('/assets/js/toast.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="{{asset('/assets/js/sbm.js')}}"></script>
  <script src="{{asset('/assets/js/printpage.js')}}"></script>
  <script src="{{asset('/assets/js/prevent-submit.js')}}"></script>
  <script src="{{asset('/assets/js/shownominal.js')}}"></script>

  <script>
    $('.js-example-basic-single-3').select2({
      placeholder: 'Select an option'
    });
    $('.js-example-basic-single-4').select2({
      placeholder: 'Select an option',
      dropdownParent: '#tambah_ruangan'
    });
    $('.js-example-basic-single-5').select2({
      placeholder: 'Select an option',
      dropdownParent: '#pinjam'
    });
    $('.js-example-basic-single-6').select2({
      placeholder: 'Select an option',
      dropdownParent: '#tambah_pegawai'
    });
    $('.js-example-basic-single-7').select2({
      placeholder: 'Select an option',
      dropdownParent: '#tambah_nonpegawai'
    });
  </script>

</body>

</html>