
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

        <style>
        .bg-purple {
            background-color: #6f42c1 !important;
            color: #fff !important;
        }

        .bg-teal {
            background-color: #20c997 !important;
            color: #fff !important;
        }
        .unread {
            background-color: #f8f9fa;
            /* Warna latar untuk notifikasi belum dibaca */
            font-weight: bold;
            /* Bikin teks lebih tebal untuk notifikasi belum dibaca */
        }

        .read {
            background-color: #ffffff;
            /* Warna latar untuk notifikasi sudah dibaca */
            font-weight: normal;
            /* Teks normal untuk notifikasi yang sudah dibaca */
        }
    </style>

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
<body id="{{ $active }}" class="bg-white d-flex flex-column min-vh-100">


<!-- Awal Navbar -->
    @php
        $isLoginPegawai = request()->is('akses');
    @endphp

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fw-bold small text-secondary fixed-top">
        <div class="container-fluid px-md-5">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('/assets/images/brand-logo.png') }}" alt="" width="130">
                @if (session('versi') != null)
                    <span class="ms-2 badge rounded-pill" style="background-color: #e9ecef; color: #495057; font-size: 0.7rem; font-weight: 600; padding: 5px 12px; letter-spacing: 0.5px;">TA {{ \App\Models\Versi::find(session('versi'))->versi ?? '-' }}</span>
                @endif
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                {{-- Sembunyikan menu jika sedang di page login pegawai --}}
                @unless ($isLoginPegawai)
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 links">
                        @if(isset($active) && $active == 'index')
                            <li class="nav-item">
                                <a data-active="index" class="nav-link active" aria-current="page"
                                    href="{{ url('/') }}">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Dashboard
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ url('/') }}"><i class="fa-solid fa-home me-2"></i>Kembali ke Dashboard</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ url('/kegiatan/riwayat/pengajuan') }}">Kegiatanku Perjadin Kegiatan</a></li>
                                    <li><a class="dropdown-item" href="{{ url('/perjadin/riwayat/pengajuan') }}">Kegiatanku Perjalanan Dinas</a></li>
                                    <li><a class="dropdown-item" href="{{ url('/pemeliharaan-pegawai') }}">Kegiatanku Pemeliharaan</a></li>
                                </ul>
                            </li>
                        @endif
                        @php
                            $activeVersi =
                                \App\Models\Versi::where('status', 'aktif')->first() ??
                                (object) ['id' => '-1', 'versi' => 'Default Versi'];
                        @endphp

                        @if ($activeVersi && $activeVersi->id != session('versi'))
                            <li class="nav-item">
                                <a data-active="perjadin_biasa" class="nav-link" href="#" aria-disabled="true"
                                    onclick="showAlert(event)">Perjalanan Dinas</a>
                            </li>
                            <li class="nav-item">
                                <a data-active="perjadin_kegiatan" class="nav-link " href="#" aria-disabled="true"
                                    onclick="showAlert(event)">Buat Kegiatan</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a data-active="perjadin_biasa" class="nav-link" href="{{ url('/perjadin') }}">Perjalanan
                                    Dinas</a>
                            </li>
                            <li class="nav-item">
                                <a data-active="perjadin_kegiatan" class="nav-link" href="{{ url('/kegiatan') }}">Buat
                                    Kegiatan</a>
                            </li>
                        @endif

                        {{-- <li class="nav-item">
              <a data-active="fasilitas" class="nav-link" href="{{url('/fasilitas')}}">Fasilitas</a>
            </li> --}}
                        <li class="nav-item">
                            <a data-active="pemeliharaan" class="nav-link"
                                href="{{ url('/pemeliharaan-pegawai/pengajuan') }}">Pengajuan Pemeliharaan</a>
                        </li>
                    </ul>
                @endunless

                @auth
                    @unless ($isLoginPegawai)
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 links">
                            <li class="nav-item">
                                <a data-active="barang_saya" class="nav-link" aria-current="page"
                                    href="{{ url('/riwayat_barang/' . 'pengajuan') }}"><i class="fa-solid fa-box-open"></i>
                                    <span class="hide-profile">Barang Saya</span></a>
                            </li>
                        @endunless
                        <li class="nav-item dropdown">
                            <a class="nav-link position-relative" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-bell"></i> <span class="hide-profile">Pemberitahuan</span>
                                <span id="notif"
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notif d-flex justify-content-center align-items-center">
                                    0
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="navbarDropdown"
                                style="width: 450px; max-height: 400px; overflow-y: auto;">
                                <!-- Header Notifikasi -->
                                <li class="dropdown-header text-center fw-bold text-primary">
                                    Notifikasi Terbaru
                                </li>
                                <div class="" id="notif-list"></div>
                                <div class="" id="notif-list-zero"></div>
                                <!-- Footer Notifikasi -->
                                <li class="dropdown-footer text-center" id="mark-all-read-item" style="display: none;">
                                    <a href="#" id="mark-all-read" class="text-primary">Tandai Semua sudah
                                        dibaca</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" data-active="profile" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                @php
                                    $namaLengkap = auth('pegawai')->user()->nama_lengkap;
                                    $namaParts = explode(',', $namaLengkap);
                                    $namaDepan = $namaParts[0];
                                    $namaBersih = preg_replace('/\b(Dr\.|Drs\.|Ir\.|Prof\.|H\.|Hj\.|Dr|Drs|Ir|Prof|H|Hj)\b/i', '', $namaDepan);
                                    $namaBersih = trim(preg_replace('/\s+/', ' ', $namaBersih));
                                @endphp
                                <i class="fa-solid fa-circle-user fs-4 me-2" style="color: #082A99;"></i>
                                <span class="">{{ $namaBersih }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">

                                <li><a class="dropdown-item" href="{{ url('/profile') }}"><i class="fa-solid fa-user me-2"></i>Profile</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <form action="{{ url('/logout') }}" method="post">
                                    @csrf
                                    <li>
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fa-solid fa-sign-out-alt me-2"></i>Keluar
                                        </button>
                                    </li>
                                </form>
                            </ul>
                        </li>
                    </ul>
                @else
                    @unless ($isLoginPegawai)
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 links">
                            <li class="nav-item">
                                <a data-active="barang_saya" class="btn btn-warning btn-sm small fw-bold text-secondary mx-1"
                                    aria-current="page" href="{{ url('/akses') }}"><i class="fa-solid fa-box-open"></i>
                                    <span class="hide-profile">Akses Masuk</span></a>
                            </li>
                        </ul>
                    @endunless
                    @if ($isLoginPegawai)
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 links">
                            <li class="nav-item">
                                <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary btn-sm ms-2">
                                    Halaman Admin
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/penyedia') }}" class="btn btn-outline-secondary btn-sm ms-2">
                                    Halaman Penyedia
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/login-penyewa') }}" class="btn btn-outline-secondary btn-sm ms-2">
                                    Halaman Penyewaan
                                </a>
                            </li>
                        </ul>
                    @endif
                @endauth
            </div>
        </div>
    </nav>


    <!-- Modal Versi-->
<div class="modal fade" id="versionAlertModal" tabindex="-1" aria-labelledby="versionAlertModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="versionAlertModalLabel">Akses Tidak Diperbolehkan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Anda tidak dapat mengakses halaman ini karena Tahun Anggaran yang sedang Anda pilih tidak sesuai dengan Tahun Anggaran yang aktif.</p>
          <p><strong>Tahun Anggaran yang dipilih: </strong><span id="currentVersion"></span></p>
          <p><strong>Tahun Anggaran yang diaktifkan: </strong><span id="requiredVersion"></span></p>
          <p>Silakan login ulang dengan Tahun Anggaran yang sesuai untuk melanjutkan.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>


    <!-- Akhir Navbar -->
    <div class="top-nav">
        @yield('content')
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
  @if (session()->has('error'))
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container end-0 mt-4 pt-4 position-fixed">
            <div class="show toast text-bg-danger" role="alert" aria-live="assertive" aria-atomic="true" id="myToast">
                <div class="toast-header">
                    <strong class="me-auto">Kesalahan</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {{ session('error') }}
                </div>
            </div>
        </div>
    </div>
    @endif
    @if ($errors->any())
      <div aria-live="polite" aria-atomic="true" class="position-relative">
          <div class="toast-container end-0 mt-4 pt-4 position-fixed">
              <div class="show toast text-bg-danger" role="alert" aria-live="assertive" aria-atomic="true" id="myToast">
                  <div class="toast-header">
                      <strong class="me-auto">Kesalahan Validasi</strong>
                      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                  </div>
                  <div class="toast-body">
                      @foreach ($errors->all() as $error)
                          <p>{{ $error }}</p>
                      @endforeach
                  </div>
              </div>
          </div>
      </div>
  @endif

    </div>


    {{-- Footer --}}
    <section class="footer pt-4 pb-4 bg-white mt-auto border-top">
        <div class="container text-center">
            @php
                $activeVersi =
                    \App\Models\Versi::where('status', 'aktif')->first() ??
                    (object) ['id' => '-1', 'versi' => 'Default Versi'];
            @endphp
            <p class="small text-muted mb-0">copyright {{$activeVersi->versi}}, Akunkeun - Aplikasi Kegiatan dan Urusan Keuangan</p>
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
        $(document).ready(function() {
            @if(auth('pegawai')->check())
                var iduser = {{ auth('pegawai')->user()->id }};
            @else
                var iduser = 0;
            @endif

            function notifUser() {
                $.ajax({
                    url: "/notifUser/" + iduser,
                    type: "GET",
                    dataType: "json",
                    success: function(res) {
                        let totalNotif = res.notif;

                        if (res.notifDataUnread.length > 100){
                          $("#notif").text('100+'); // Ini akan bekerja jika elemen #notif ada di DOM
                        }else {
                          $("#notif").text(totalNotif); // Ini akan bekerja jika elemen #notif ada di DOM
                        }

                        $('#notif-list').empty();

                        // Menampilkan notifikasi yang belum dibaca
                        if (res.notifDataUnread.length > 0) {
                            $('#mark-all-read-item').show();
                            res.notifDataUnread.forEach(function(notif) {
                                let readClass = notif.is_read ? 'read' : 'unread';
                                $('#notif-list').append(
                                    `
                                    <li class="dropdown-item p-3 mb-2 border-bottom d-flex align-items-start"
                                        data-route="${notif.route}"
                                        data-id="${notif.id}"
                                        style="background-color: ${notif.is_read ? '#ffffff' : '#e7f3fe'}; color: ${notif.is_read ? '#212529' : '#31708f'}; font-weight: ${notif.is_read ? 'normal' : 'bold'};">
                                        <div class="image-container me-3">
                                            <img src="{{ asset('/assets/images/icon akunkeun.png') }}" alt="Icon" width="50" class="rounded-circle">
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-dark">${notif.header || 'Notifikasi Baru'}</div>
                                            <div class="message text-muted" style="max-width: 350px; color: #212529; font-weight: 500; white-space: normal;">
                                                ${notif.message || 'Isi pesan penting Anda ditampilkan di sini...'}
                                            </div>
                                            <div style="font-size: 12px; color: #6c757d;">${new Date(notif.created_at).toLocaleDateString()}</div>
                                        </div>
                                    </li>
                                    `
                                );
                            });
                        }

                        // Menampilkan notifikasi yang sudah dibaca (maksimal 5)
                        if (res.notifDataRead.length > 0) {
                            $('#notif-list').append('<div style="font-size: 14px; color: #6c757d; margin-bottom: 5px;">Notifikasi telah dibaca</div>');
                            res.notifDataRead.forEach(function(notif) {
                                let readClass = notif.is_read ? 'read' : 'unread';
                                $('#notif-list').append(
                                    `
                                    <li class="dropdown-item p-3 mb-2 border-bottom d-flex align-items-start ${readClass}" data-route="${notif.route}" data-id="${notif.id}">
                                        <div class="image-container me-3">
                                            <img src="{{ asset('/assets/images/icon akunkeun.png') }}" alt="Icon" width="50" class="rounded-circle">
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-dark">${notif.header || 'Notifikasi Lama'}</div>
                                            <div class="message text-muted" style="max-width: 350px; color: #212529; font-weight: 500; white-space: normal;">
                                                ${notif.message || 'Isi pesan penting Anda ditampilkan di sini...'}
                                            </div>
                                            <div style="font-size: 12px; color: #6c757d;">${new Date(notif.created_at).toLocaleDateString()}</div>
                                        </div>
                                    </li>
                                    `
                                );
                            });
                        }

                        // Jika tidak ada notifikasi sama sekali
                        if (res.notifDataUnread.length === 0 && res.notifDataRead.length === 0) {
                            if ($('#notif-list-zero li.dropdown-item.text-center').length === 0) {
                                $('#notif-list-zero').append(
                                    `<li class="dropdown-item p-3 mb-2 text-center" style="color: #6c757d;">Tidak Ada Notifikasi</li>`
                                );
                            }
                        }


                        // Menangani klik pada item notifikasi
                        $('#notif-list').on('click', '.dropdown-item', function() {
                            const route = $(this).data('route');
                            const notifId = $(this).data('id');

                            $.ajax({
                                url: `/notifUser/read/${notifId}`,
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function() {
                                    const baseUrl = window.location.origin;
                                    const finalUrl = `${baseUrl}/${route}`;
                                    window.location.href = finalUrl;
                                },
                                error: function(xhr, status, error) {
                                    console.error("Error marking notification as read:", error);
                                }
                            });
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching notifications:", error);
                    }
                });
            }

            notifUser();
            setInterval(notifUser, 10000);
        });
      </script>

<script>
$(document).ready(function() {
  @if(auth('pegawai')->check())
                var iduser = {{ auth('pegawai')->user()->id }};
            @else
                var iduser = 0;
            @endif
    $('#mark-all-read').click(function(e) {

        e.preventDefault(); // Mencegah link untuk navigasi

        // Mengonfirmasi tindakan
        if (confirm('Apakah Anda yakin ingin menandai semua notifikasi sebagai dibaca?')) {
            $.ajax({
                url: "/mark-all-notif-user/" + iduser, // Ganti dengan URL endpoint Anda
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
$(document).ready(function() {
    @if (auth('pegawai')->check())
        var iduserPemeliharaan = {{ auth('pegawai')->user()->id }};
    @else
        var iduserPemeliharaan = 0;
    @endif
    function notifUserPemeliharaan() {
        $.ajax({
            url: "/notifPemeliharaanUser/" + iduserPemeliharaan,
            type: "GET",
            dataType: "json",
            success: function(res) {
                let totalNotif = res.notif;
                if (res.notifDataUnread.length > 100) {
                    $("#notif").text('100+');
                } else {
                    $("#notif").text(totalNotif);
                }

                $('#notif-list').empty();

                // Notifikasi belum dibaca
                res.notifDataUnread.forEach(function(notif) {
                    $('#notif-list').append(
                        `<li class="dropdown-item p-3 mb-2 border-bottom d-flex align-items-start" 
                             data-route="${notif.route}" data-id="${notif.id}"
                             style="background-color: #e7f3fe; font-weight: bold;">
                            <div class="image-container me-3">
                                <img src="{{ asset('/assets/images/icon akunkeun.png') }}" width="50" class="rounded-circle">
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark">${notif.header || 'Notifikasi Baru'}</div>
                                <div class="message text-muted" style="max-width: 350px;">
                                    ${notif.message || ''}
                                </div>
                                <div style="font-size: 12px; color: #6c757d;">
                                    ${new Date(notif.created_at).toLocaleDateString()}
                                </div>
                            </div>
                        </li>`
                    );
                });

                // Notifikasi sudah dibaca (maksimal 5)
                if (res.notifDataRead.length > 0) {
                    $('#notif-list').append(
                        '<div style="font-size: 14px; color: #6c757d; margin-bottom: 5px;">Notifikasi telah dibaca</div>'
                    );
                    res.notifDataRead.forEach(function(notif) {
                        $('#notif-list').append(
                            `<li class="dropdown-item p-3 mb-2 border-bottom d-flex align-items-start read" 
                                 data-route="${notif.route}" data-id="${notif.id}">
                                <div class="image-container me-3">
                                    <img src="{{ asset('/assets/images/icon akunkeun.png') }}" width="50" class="rounded-circle">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-dark">${notif.header || 'Notifikasi Lama'}</div>
                                    <div class="message text-muted" style="max-width: 350px;">
                                        ${notif.message || ''}
                                    </div>
                                    <div style="font-size: 12px; color: #6c757d;">
                                        ${new Date(notif.created_at).toLocaleDateString()}
                                    </div>
                                </div>
                            </li>`
                        );
                    });
                }

                // Klik notifikasi
                $('#notif-list').on('click', '.dropdown-item', function() {
                    const route = $(this).data('route');
                    const notifId = $(this).data('id');

                    $.ajax({
                        url: `/notifPemeliharaanUser/read/${notifId}`,
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function() {
                            window.location.href = `${window.location.origin}/${route}`;
                        }
                    });
                });
            }
        });
    }

    notifUserPemeliharaan();
    setInterval(notifUserPemeliharaan, 10000);

    // Mark all read
    $('#mark-all-read-pemeliharaan').click(function(e) {
        e.preventDefault();
        if (confirm('Apakah Anda yakin ingin menandai semua notifikasi sebagai dibaca?')) {
            $.ajax({
                url: "/mark-all-notif-pemeliharaan-user/" + iduserPemeliharaan,
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function() { location.reload(); }
            });
        }
    });
});
</script>
<script>
    function showAlert(event) {
        event.preventDefault(); // Mencegah aksi default link
        // Set the versions in the modal
        document.getElementById('currentVersion').innerText = {{ \App\Models\Versi::find(session('versi'))->versi ?? 'Tidak Ada Tahun' }}; // Menampilkan versi yang sedang aktif
        document.getElementById('requiredVersion').innerText = '{{ $activeVersi->versi }}'; // Versi yang diperlukan

        // Show the modal
        var myModal = new bootstrap.Modal(document.getElementById('versionAlertModal'));
        myModal.show();
    }
</script>


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
