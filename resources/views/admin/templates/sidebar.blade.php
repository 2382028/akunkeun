<!DOCTYPE html>
<html lang="en">

<head>
<meta name="csrf-token" content="{{ csrf_token() }}">

  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width initial-scale=10" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Logo -->
  <link rel="icon" href="{{asset('/assets/images/icon akunkeun.png')}}" type="image/x-icon">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="{{asset('/assets/css/bootstrap.min.css')}}" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

  <!-- My CSS -->
  <link rel="stylesheet" href="{{asset('/assets/css/style-admin.css')}}" />
  {{-- <link rel="stylesheet" href="{{asset('vanila/main.css')}}" /> --}}

  <!-- Data Tables-->
  <link rel="stylesheet" href="{{asset('/assets/css/dataTables.bootstrap5.min.css')}}" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Select2 -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>
        
  <!-- Table to Excel -->
  <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
  <script src="https://unpkg.com/file-saver"></script>
    <style>
        .page-wrap {
            margin-right: 5px;
            border-radius: 4px;
        }

        .page-wrap-active {
            background-color: black !important;
            color: white !important;
        }

        @media (max-width: 576px) {
            .page-wrap.btn {
                width: 100%;
            }
        }

        #modalTambahKategori.modal {
            z-index: 1060;
        }

        .modal-backdrop+.modal-backdrop {
            z-index: 1055;
        }

        .page-wrap:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .bg-purple {
            background-color: #6f42c1 !important;
            color: #fff !important;
        }

        .bg-teal {
            background-color: #20c997 !important;
            color: #fff !important;
        }

        td,
        th {
            vertical-align: middle !important;
        }

        .image-container {
            display: inline-block;
            /* Menjadikan wadah inline */
            background-color: white;
            /* Warna latar belakang */
            border-radius: 10px;
            /* Membuat latar belakang bulat */
            padding: 2px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            /* Tambahkan bayangan untuk efek kedalaman */
        }

        .image-container img {
            display: block;
            /* Menghindari spasi di bawah gambar */
            border-radius: 10px;
        } 
        .legend-box {
    display: inline-block;
    width: 20px;
    height: 12px;
    border-radius: 2px;
}

    </style>

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
            <a class="" style="text-decoration: none; color: #007bff; border: 2px solid #007bff; border-radius: 5px; padding: 5px 5px; font-weight: bold; transition: all 0.3s ease;">
                {{-- <i class="bi bi-check-circle me-2"></i> <!-- Ikon Checklist --> --}}
                TA-{{ \App\Models\Versi::find(session('versi'))->versi ?? 'Tidak Ada Tahun' }}
            </a>
        <li class="nav-item dropdown">
              <a class="nav-link position-relative" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-bell"></i> <span class="hide-profile">Pemberitahuan</span>
                <span id="notif" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notif d-flex justify-content-center align-items-center">
                  0
                </span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="navbarDropdown" style="width: 400px; max-height: 400px; overflow-y: auto;">
                <!-- Header Notifikasi -->
                <li class="dropdown-header text-center fw-bold text-primary">
                  Notifikasi Terbaru
                </li>
                <div class="" id="notif-list"></div>
                <!-- Footer Notifikasi -->
                @if (auth('administrator')->user()->role != 'Master')
                <li class="dropdown-footer text-center" id="mark-all-read-item" style="display: none;">
                  <a href="#" id="mark-all-read" class="text-primary">Tandai Semua sudah dibaca</a>
                </li>
                @endif
              </ul>
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
        <a class="navbar-brand py-0 d-flex align-items-center" href="{{url('/dashboard')}}">
          <div class="image-container me-2">
            <img src="{{ asset('/assets/images/icon akunkeun.png') }}" alt="" width="50">
          </div>
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
                        <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/ref_data_pajak')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2 "><i class="fa-solid fa-file-invoice-dollar"></i></span>
                          <span>Data Pajak</span>
                        </a>
                        </li>
                        <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/ref_bank')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2 "><i class="fa-solid fa-university"></i></span>
                          <span>Data Bank</span>
                        </a>
                        </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/ref_penandatangan')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                            <span class="me-2 "><i class="fa-solid fa-signature"></i></span>
                          <span>Penandatangan</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/ref_jabatan')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                            <span class="me-2 "><i class="fa-solid fa-briefcase"></i></span>
                          <span>Data Jabatan</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/ref_pokja')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                            <span class="me-2 "><i class="fa-solid fa-users"></i></span>
                          <span>Data Pokja/Fungsi</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/ref_pangkat')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                            <span class="me-2 "><i class="fa-solid fa-ranking-star"></i></span>
                          <span>Data Pangkat</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/jenis_program')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                            <span class="me-2 "><i class="fa-solid fa-list-alt"></i></span>
                          <span>Jenis Program</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/ref_fasilitas')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                            <span class="me-2 "><i class="fa-solid fa-building"></i></span>
                          <span>Data Fasilitas</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/ref_satuan')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                            <span class="me-2 "><i class="fa-solid fa-ruler"></i></span>
                          <span>Satuan</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                                                    <a href="{{ url('/ref-kop-surat') }}"
                                                        class="nav-link px-1 sidebar-link py-1 text-white"
                                                        aria-current="true">
                                                        <span class="me-2 "><i class="fa-solid fa-file"></i></span>
                                                        <span>Kop Surat</span>
                                                    </a>
                                                </li>
                                                <li class=" list-group-item list-group-item-action">
                                                    <a href="{{ url('/ref-nomor-surat') }}"
                                                        class="nav-link px-1 sidebar-link py-1 text-white"
                                                        aria-current="true">
                                                        <span class="me-2 "><i class="fa-solid fa-file"></i></span>
                                                        <span>Nomor Surat</span>
                                                    </a>
                                                </li>
                                                <li class=" list-group-item list-group-item-action">
                                                    <a href="{{ url('/ref-kode-layanan') }}"
                                                        class="nav-link px-1 sidebar-link py-1 text-white"
                                                        aria-current="true">
                                                        <span class="me-2 "><i class="fa-solid fa-file"></i></span>
                                                        <span>Kode Layanan</span>
                                                    </a>
                                                </li>
                                                <li class=" list-group-item list-group-item-action">
                                                    <a href="{{ url('/ref-golongan') }}"
                                                        class="nav-link px-1 sidebar-link py-1 text-white"
                                                        aria-current="true">
                                                        <span class="me-2 "><i class="fa-solid fa-file"></i></span>
                                                        <span>Golongan</span>
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
                        <a href="{{url('/perjadin-keuangan/' . 'verifikasi-2')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
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
                      @if ((auth('administrator')->user()->role == 'HKT') | (auth('administrator')->user()->role == 'Master'))
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/kegiatan-HKT/' . 'pengajuan')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2"><i class="fa-solid fa-envelope"></i></span>
                          <span>HKT</span>
                        </a>
                      </li>
                      @endif
                      @if ((auth('administrator')->user()->role == 'Master') | (auth('administrator')->user()->role == 'Bendahara') | (auth('administrator')->user()->role == 'Keuangan'))
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/kegiatan-keuangan/' . 'verifikasi-2')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
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
                @if ((auth('administrator')->user()->role == 'Master') || (auth('administrator')->user()->role == 'BMN'))
                <div class="accordion-item">
                  <h2 class="accordion-header " id="flush-headingBmn">
                    <a href="" class="accordion-button collapsed text-decoration-none custom-button py-3" data-bs-toggle="collapse" data-bs-target="#flush-collapseBmn" aria-expanded="false" aria-controls="flush-collapseBmn">
                      <span class="me-2"><i class="fa-solid fa-boxes-stacked"></i></span>
                      <span>BMN & Aset</span>
                    </a>
                  </h2>
                  <div id="flush-collapseBmn" class="accordion-collapse collapse" aria-labelledby="flush-headingBmn" data-bs-parent="#accordionFlushExample">
                    <ul class="navbar-nav list-group list-group-flush ps-5">
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/bmn/data')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2 "><i class="fa-solid fa-box"></i></span>
                          <span>Data BMN Kendaraan</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/data_assets')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2 "><i class="fa-solid fa-building-columns"></i></span>
                          <span>Data BMN Aset</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/ruangan/data')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2 "><i class="fa-solid fa-door-open"></i></span>
                          <span>Data Ruangan</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/bmn/rekap')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2 "><i class="fa-solid fa-clipboard-list"></i></span>
                          <span>Rekapitulasi BMN</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/pemeliharaan-admin')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2 "><i class="fa-solid fa-toolbox"></i></span>
                          <span>Pemeliharaan</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/penyewaan_aset/pengajuan')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2 "><i class="fa-solid fa-handshake"></i></span>
                          <span>Penyewaan Aset</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/penyewaan_aset/rekap-sewa')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2 "><i class="fa-solid fa-file-invoice"></i></span>
                          <span>Rekap Penyewaan</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
                @endif
                @if ((auth('administrator')->user()->role == 'Master') || (auth('administrator')->user()->role == 'BMN'))
                <div class="accordion-item">
                  <h2 class="accordion-header " id="flush-headingSix">
                    <a href="" class="accordion-button collapsed text-decoration-none custom-button py-3" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
                      <span class="me-2"><i class="fa-solid fa-folder-open"></i></span>
                      <span>Pengadaan</span>
                    </a>
                  </h2>
                  <div id="flush-collapseSix" class="accordion-collapse collapse" aria-labelledby="flush-headingSix" data-bs-parent="#accordionFlushExample">
                    <ul class="navbar-nav list-group list-group-flush ps-5">
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{ url('buat-pengadaan/sdp') }}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                          <span class="me-2 "><i class="fa-solid fa-pencil-square"></i></span>
                          <span>Buat Dok Pengadaan</span>
                        </a>
                      </li>
                      <li class="list-group-item list-group-item-action">
                        <a href="{{ route('daftar-pengadaan', ['status' => 'sdp']) }}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                            <span class="me-2"><i class="fa-solid fa-file"></i></span>
                            <span>Daftar Pengadaan</span>
                        </a>
                    </li>
                    </ul>
                  </div>
                </div>
                @endif
                @if ((auth('administrator')->user()->role == 'Master') | (auth('administrator')->user()->role == 'BMN'))
                                    <div class="accordion-item">
                    <h2 class="accordion-header ">
                        <a href="{{url('/monitoring')}}" class="accordion-button collapsed text-decoration-none custom-button remove-button py-3">
                        <span class="me-2"><i class="fa-solid fa-gauge active"></i></span>
                        <span>Monitoring Usulan</span>
                        </a>
                    </h2>
                    </div>
                  @endif
                  @if ((auth('administrator')->user()->role == 'Master'))
                  <div class="accordion-item">
                        <h2 class="accordion-header ">
                            <a href="{{url('/monitoring-keuangan')}}" class="accordion-button collapsed text-decoration-none custom-button remove-button py-3">
                            <span class="me-2"><i class="fa-solid fa-chart-line active"></i></span>
                            <span>Monitoring Keuangan</span>
                            </a>
                        </h2>
                    </div>
                  @endif
                @if ((auth('administrator')->user()->role == 'Keuangan') | (auth('administrator')->user()->role == 'Master'))
                <div class="accordion-item">
                  <h2 class="accordion-header " id="flush-headingSeven">
                    <a href="" class="accordion-button collapsed text-decoration-none custom-button py-3" data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven" aria-expanded="false" aria-controls="flush-collapseFive">
                      <span class="me-2"><i class="fa-solid fa-book"></i></span>
                      <span>Jurnal</span>
                    </a>
                  </h2>
                  <div id="flush-collapseSeven" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                    <ul class="navbar-nav list-group list-group-flush ps-5">
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/spby/sudah')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                            <span class="me-2"><i class="fa-solid fa-check-circle"></i></span>
                          <span>Sudah Jurnal</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/spby/belum')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                            <span class="me-2"><i class="fa-solid fa-book-open"></i></span>
                          <span>Belum Jurnal</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/spby/koreksi')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                            <span class="me-2"><i class="fa-solid fa-pen"></i></span>
                          <span>Koreksi Jurnal</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
                @endif
                @if (auth('administrator')->user()->role == 'Master')
                <div class="accordion-item">
                  <h2 class="accordion-header " id="flush-headingEight">
                    <a href="" class="accordion-button collapsed text-decoration-none custom-button py-3" data-bs-toggle="collapse" data-bs-target="#flush-collapseEight" aria-expanded="false" aria-controls="flush-collapseEight">
                        <span class="me-2"><i class="fa-solid fa-pen-to-square"></i></span>
                      <span>Koreksi Data</span>
                    </a>
                  </h2>
                  <div id="flush-collapseEight" class="accordion-collapse collapse" aria-labelledby="flush-headingEight" data-bs-parent="#accordionFlushExample">
                    <ul class="navbar-nav list-group list-group-flush ps-5">
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/koreksi/perjadin')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                            <span class="me-2"><i class="fa-solid fa-plane-departure"></i></span>
                          <span>Perjadin Langsung</span>
                        </a>
                      </li>
                      <li class=" list-group-item list-group-item-action">
                        <a href="{{url('/koreksi/kegiatan')}}" class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                            <span class="me-2"><i class="fa-solid fa-calendar-days"></i></span>
                          <span>Perjadin Kegiatan</span>
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

  @if (session()->has('error'))
  <div aria-live="polite" aria-atomic="true" class="position-relative" style="z-index: 1050;">
    <div class="toast-container end-0 mt-4 pt-4 position-fixed">
      <div class="show toast bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true" id="myToast">
        <div class="toast-header bg-danger text-white">
          <strong class="me-auto">Pesan Sobat Akunkeun</strong>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
          {{ session('error') }}
        </div>
      </div>
    </div>
  </div>
@endif

  <!-- Awal Dashboard -->
  <main class="mt-5 pt-5 @yield('main-class')" style="background: #D9D9D9;">
    @yield('contain')
</main>
  <!-- Akhir Dashboard -->
  @php
            $activeVersi = \App\Models\Versi::where('status', 'aktif')->first() ?? (object) ['id' => '-1','versi' => 'Default Versi'];
        @endphp
  <!-- Awal Footer -->
  <section id="footer" class="bg-white noprint" style="z-index: -2;">
    <div class="container-fluid">
      <div class="text-center py-2 small fw-bold py-3">Copyright &#169; {{$activeVersi->versi}}. All Right Reserved.</div>
    </div>
  </section>
  <!-- Akhir Footer -->


  {{-- script --}}

  <!-- <script src="{{asset('/assets/js/script.js')}}"></script> -->
  <script src="{{asset('/vanila/main.js')}}"></script>

  <script src="{{asset('/assets/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('/assets/js/script1.js')}}"></script>
  <!-- <script src="{{ asset('assets/js/script1.js?v=' . time()) }}"></script> -->
  <script src="{{asset('/assets/js/offcanvas.js')}}"></script>

  <!-- Data Table -->
  <script src="{{asset('/assets/js/jquery-3.5.1.js')}}"></script>
  <script src="{{asset('/assets/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('/assets/js/dataTables.bootstrap5.min.js')}}"></script>
  <script src="{{asset('/assets/js/data-table.js')}}"></script>
  <script>
    $(document).ready(function () {
      var t = $('.data-table-spby').DataTable({
        pageLength: 15, // Menampilkan 15 data per bagian
        lengthMenu: [15, 25, 50, 100],
        columnDefs: [
          {
            searchable: false,
            orderable: false,
            targets: '_all',
            
          },
        ],
      });

      t.on('order.dt search.dt', function () {
        let i = 1;

        t.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
          this.data(i++);
        });
      }).draw();
    });
  </script>
  <script>
    $(document).ready(function () {
      var t = $('.data-table-perakun').DataTable({
        pageLength: -1, // Menampilkan semua data
        columnDefs: [
          {
            searchable: false,
            orderable: false,
            targets: 0,
          },
        ],
      });

      t.on('order.dt search.dt', function () {
        let i = 1;

        t.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
          this.data(i++);
        });
      }).draw();
    });
  </script>

  <script src="{{asset('/assets/js/toast.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="{{asset('/assets/js/sbm.js')}}"></script>
  <script src="{{asset('/assets/js/printpage.js')}}"></script>
  <script src="{{asset('/assets/js/prevent-submit.js')}}"></script>
  <script src="{{asset('/assets/js/shownominal.js')}}"></script>
  <script>
    function calculateRowResult($row) {
  var num1 = parseFloat($row.find('.num1').val()) || 0;
  var num2 = parseFloat($row.find('.num2').val()) || 0;
  var num3 = parseFloat($row.find('.num3').val()) || 0;
  var num4 = parseFloat($row.find('.num4').val()) || 0;
  var num5 = parseFloat($row.find('.num5').val()) || 0;
  var num6 = parseFloat($row.find('.num6').val()) || 0;
  var desimalNum2 = num2 / 100;
  var desimalNum3 = num3 / 100;
  var desimalNum4 = num4 / 100;
  var desimalNum5 = num5 / 100;
  var result = num1 + num2 + num3 +num4 + num5 + num6;

  console.log(num1, num2, num3, num4, num5, result);

  $row.find('.result').val(result);
  calculateTotal($row.closest('.calculationTable'));
  calculateSummaryTotal();
}

function calculateTotal($table) {
  var total = 0;

  $table.find('tbody tr').each(function() {
    var result = parseFloat($(this).find('.result').val()) || 0;
    total += result;
  });

  $table.find('.total').val(total);
}

function calculateSummaryTotal() {
$('#summaryTableBody').empty();

var grandTotal = 0;

$('.calculationTable').each(function() {
    var tableId = $(this).attr('name');
    var total = parseFloat($(this).find('.total').val()) || 0;
    grandTotal += total;

    $('#summaryTableBody').append('<tr><td>' + tableId + '</td><td>' + total + '</td></tr>');
});

$('#summaryTableBody').append('<tr><td><strong>Grand Total:</td><td><strong>' + grandTotal + '</td></tr>');
}




// Event delegation to handle dynamically added elements
$(document).on('change', '.calculationTable .num1, .calculationTable .num2, .calculationTable .num3, .calculationTable .num4, .calculationTable .num5, .calculationTable .num6', function() {
  var $row = $(this).closest('tr');
  calculateRowResult($row);
});

// Calculate totals and summary total on page load
$(document).ready(function() {
  $('.calculationTable').each(function() {
    calculateTotal($(this));
  });

  calculateSummaryTotal();
});
  </script>


  <script>
    $(document).ready(function() {
        let refFasilitasData = [];

        // Ambil data dari Laravel backend
        $.ajax({
            url: '/get-data-fasilitas',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                refFasilitasData = data; // Simpan data ke variabel global
            },
            error: function(error) {
                console.error('Error fetching data:', error);
            }
        });

        // Event listener untuk perubahan nilai select
        $('#uraian').change(function() {
            const selectedValue = $(this).val();

            // Cari data yang sesuai dengan selectedValue
            const selectedFasilitas = refFasilitasData.find(fasilitas => fasilitas.nama_fasilitas === selectedValue);

            // Jika ditemukan, gunakan satuan dari data tersebut
            const satuanValue = selectedFasilitas ? selectedFasilitas.satuan : 'Kali';

            // Bersihkan konten sebelumnya
            $('#conditional_fields').empty();

            // Tambahkan HTML ke #conditional_fields
            $('#conditional_fields').html(`
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="jumlah_tiket" class="detail-fields">Jumlah <span class="text-danger">*</span></label>
                        <div class="input-group">
                        <input type="number" min="0" aria-label="First name" class="form-control" name="jumlah_frekuensi" required>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="satuan" name="satuan" value="${satuanValue}" readonly>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                                <option value="Bayar di awal" selected>Dibayar di Awal</option>
                                <option value="Reimburse">Reimburse</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="" class="form-label">Keterangan Fasilitas <span class="text-danger">*</span><span class="text-secondary small">(Contoh : Perkiraan Harga)</span></label>
                        <input type="text" name="keterangan" id="" class="form-control" required>
                    </div>
                </div>
            `);
        });
    });
    $(document).ready(function() {
        let refFasilitasData = [];

        // Ambil data dari Laravel backend
        $.ajax({
            url: '/get-data-fasilitas',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                refFasilitasData = data; // Simpan data ke variabel global
            },
            error: function(error) {
                console.error('Error fetching data:', error);
            }
        });

        // Event listener untuk perubahan nilai select
        $('#uraian_pelaksana').change(function() {
            const selectedValue = $(this).val();

            // Cari data yang sesuai dengan selectedValue
            const selectedFasilitas = refFasilitasData.find(fasilitas => fasilitas.nama_fasilitas === selectedValue);

            // Jika ditemukan, gunakan satuan dari data tersebut
            const satuanValue = selectedFasilitas ? selectedFasilitas.satuan : 'Kali';

            // Bersihkan konten sebelumnya
            $('#conditional_fields_pelaksana').empty();

            // Tambahkan HTML ke #conditional_fields
            $('#conditional_fields_pelaksana').html(`
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="jumlah_tiket" class="detail-fields">Jumlah <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" min="0" aria-label="First name" class="form-control" name="jumlah_frekuensi" required>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="satuan" name="satuan" value="${satuanValue}" readonly>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                                <option value="Bayar di awal" selected>Dibayar di Awal</option>
                                <option value="Reimburse">Reimburse</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="" class="form-label">Keterangan Fasilitas <span class="text-danger">*</span><span class="text-secondary small">(Contoh : Perkiraan Harga)</span></label>
                        <input type="text" name="keterangan" id="" class="form-control" required>
                    </div>
                </div>
            `);
        });
    });
</script>

  <script>
  $(document).ready(function() {
    function notifAdmin() {
      $.ajax({
        url: "/notifAdmin/{{ auth('administrator')->user()->role }}",
        type: "GET",
        dataType: "json",
        success: function(res) {
          let totalNotif = res.notif;

          if (res.notifData.length > 100){
            $("#notif").text('100+'); // Ini akan bekerja jika elemen #notif ada di DOM
          }else {
            $("#notif").text(totalNotif); // Ini akan bekerja jika elemen #notif ada di DOM
          }

          $('#notif-list').empty();

          if (res.notifData.length > 0) { // Periksa panjang array notifData
            $('#mark-all-read-item').show();
            res.notifData.forEach(function(notif) {
              $('#notif-list').append(
                `
                <li class="dropdown-item p-3 mb-2 border-bottom d-flex align-items-start" data-route="${notif.route}" data-id="${notif.id}">
                  <div class="image-container me-3">
                    <img src="{{ asset('/assets/images/icon akunkeun.png') }}" alt="Icon" width="50" class="rounded-circle">
                  </div>
                  <div class="flex-grow-1">
                    <div class="fw-bold text-dark">${notif.header || 'Notifikasi Test'}</div>
                    <div class="message text-muted" style="max-width: 240px; color: #212529; font-weight: 500; white-space: normal;">
                      ${notif.message || 'Isi pesan penting Anda ditampilkan di sini...'}
                    </div>
                    <div style="font-size: 12px; color: #6c757d;">${new Date(notif.created_at).toLocaleDateString() || '9 Oktober 2024'}</div>
                  </div>
                </li>
                `
              );
            });
          } else { // Jika tidak ada notifikasi
            $('#notif-list').append(
              `
              <li class="dropdown-item p-3 mb-2 border-bottom d-flex align-items-start">
                Tidak ada notifikasi
              </li>
              `
            );
            $('#mark-all-read-item').hide();
          }

          // Menangani klik pada item notifikasi
          $('#notif-list').on('click', '.dropdown-item', function() {
            const route = $(this).data('route');
            const notifId = $(this).data('id');

            // Mengambil role pengguna
            const role = "{{ auth('administrator')->user()->role }}"; // Pastikan menggunakan tanda kutip untuk string

            // Periksa apakah role adalah "Master"
            if (role === 'Master') {
              // Jika role adalah Master, arahkan ke route tanpa mengubah is_read
              const baseUrl = window.location.origin; // Mendapatkan URL dasar
              const finalUrl = `${baseUrl}/${route}`; // Gabungkan dengan route baru
              window.location.href = finalUrl; // Arahkan ke route notifikasi
            } else {
              // Jika bukan Master, lakukan permintaan AJAX untuk mengubah status is_read
              $.ajax({
                url: `/notifAdmin/read/${notifId}`, // Ganti dengan URL yang sesuai
                type: 'POST',
                data: {
                  _token: '{{ csrf_token() }}' // Pastikan CSRF token disertakan
                },
                success: function() {
                  // Ambil hostname
                  const baseUrl = window.location.origin; // Mendapatkan URL dasar
                  const finalUrl = `${baseUrl}/${route}`; // Gabungkan dengan route baru
                  window.location.href = finalUrl; // Arahkan ke route notifikasi
                },
                error: function(xhr, status, error) {
                  console.error("Error marking notification as read:", error);
                }
              });
            }
          });
        },
        error: function(xhr, status, error) {
          console.error("Error fetching notifications:", error);
        }
      });
    }

    notifAdmin(); // Panggil fungsi sekali saat DOM siap
    setInterval(notifAdmin, 10000); // Set interval untuk pemanggilan setiap 10 detik
  });
</script>


<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script>
$(document).ready(function() {
    $('#mark-all-read').click(function(e) {
        e.preventDefault(); // Mencegah link untuk navigasi

        // Mengonfirmasi tindakan
        if (confirm('Apakah Anda yakin ingin menandai semua notifikasi sebagai dibaca?')) {
            $.ajax({
                url: "/mark-all-notif-admin/{{ auth('administrator')->user()->role }}", // Ganti dengan URL endpoint Anda
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}' // Pastikan Anda menyertakan token CSRF
                },
                success: function(response) {
                    // Menangani respons sukses
                    alert('Semua notifikasi telah ditandai sebagai dibaca.');
                    location.reload(); // Memuat ulang halaman untuk memperbarui tampilan
                },
                error: function(xhr) {
                    // Menangani kesalahan
                    alert('Terjadi kesalahan saat menandai notifikasi sebagai dibaca.');
                }
            });
        }
    });
});
</script>


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




  <!-- Modal upload surtug -->
  <div class="modal fade" id="upload_surat" tabindex="-1" aria-labelledby="upload_suratLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="upload_suratLabel">Upload Surat Tugas</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{url('/u_perjadin_HKT')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="idPerjadin" id="upload_id_perjadin" value="">
            <input type="hidden" name="perjadinStatus" id="upload_perjadin_status" value="">
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="uraian" class="form-label">Masukan Nomor Surat Tugas<span class="text-secondary small"></span><span class="text-danger">*</span></label>
                <input type="text" class="form-control mt-1" id="up_no_surtug" name="nomor_surtug" placeholder="Masukan Nomor Surat Tugas" value="">
              </div>
              <div class="col-md-12 mb-3">
                <label for="uraian" class="form-label">Masukan Tanggal Surat<span class="text-secondary small"></span><span class="text-danger">*</span></label>
                <input type="date" class="form-control mt-1" id="up_tgl_surtug" name="tgl_dibuat" value="">
              </div>

              <div class="col-md-12 mb-3">
                <label for="uraian" class="form-label">Masukan Surat Tugas<span class="text-secondary small"></span><span class="text-danger">*</span></label>
                <input type="file" name="surat_tugas" id="fileInput" class="form-control" accept="application/pdf" required="">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal upload TTE -->
  <div class="modal fade" id="upload_tte" tabindex="-1" aria-labelledby="upload_tteLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="upload_tteLabel">Tandai TTE Surtug</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{url('/u_tte_perjadin_HKT')}}" method="post">
            @csrf
            <input type="hidden" name="idPerjadin" id="upload_tte_id_perjadin" value="">
            <input type="hidden" name="perjadinStatus" id="upload_tte_perjadin_status" value="">
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="uraian" class="form-label">Masukan Nomor Surat Tugas<span class="text-secondary small"></span><span class="text-danger">*</span></label>
                <input type="text" class="form-control mt-1" name="nomor_surtug_tte" placeholder="Masukan Nomor Surat Tugas">
              </div>
              <div class="col-md-12 mb-3">
                <label for="uraian" class="form-label">Masukan Tanggal Surat<span class="text-secondary small"></span><span class="text-danger">*</span></label>
                <input type="date" class="form-control mt-1" name="tgl_dibuat_tte">
              </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>


<!-- Modal update dok surtug -->
<div class="modal fade" id="upload_surat_update" tabindex="-1" aria-labelledby="upload_suratLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="upload_suratLabel">Upload Surat Tugas</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{url('/u_dok_perjadin_HKT')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="idPerjadinUpdate" id="upload_update_id_perjadin" value="">
                    <input type="hidden" name="perjadinStatusUpdate" id="upload_update_perjadin_status" value="">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="uraian" class="form-label">Masukan Nomor Surat Tugas<span class="text-secondary small"></span><span class="text-danger">*</span></label>
                            <input type="text" class="form-control mt-1" name="nomor_surtug_update" placeholder="Masukan Nomor Surat Tugas" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="uraian" class="form-label">Masukan Tanggal Surat<span class="text-secondary small"></span><span class="text-danger">*</span></label>
                            <input type="date" class="form-control mt-1" name="tgl_dibuat_update" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="uraian" class="form-label">Masukan Surat Tugas<span class="text-secondary small"></span><span class="text-danger">*</span></label>
                            <input type="file" name="surat_tugas_update" id="fileInput" class="form-control" accept="application/pdf" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

 <!-- Modal Tolak Surat -->
 <div class="modal fade" id="tolak_surat" tabindex="-1" aria-labelledby="tolak_suratLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tolak_suratLabel">Tolak Surat Tugas</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{url('/t_perjadin_HKT')}}" method="post">
                    @csrf
                    <input type="hidden" name="idPerjadin" id="tolak_id_perjadin" value="">
                    <input type="hidden" name="perjadinStatus" id="tolak_perjadin_status" value="">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="uraian" class="form-label">Masukan Alasan<span class="text-secondary small"></span><span class="text-danger">*</span></label>
                            <input type="text" id="tolak" name="alasan" class="form-control" placeholder="Alasan Penolakan" required>
                        </div>
                    </div>
                    <!-- Penutupan form harus di sini -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
  </div>

</body>

</html>
