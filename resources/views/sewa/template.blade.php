<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Logo -->
    <link rel="icon" href="{{ asset('/assets/images/icon akunkeun.png') }}" type="image/x-icon">

    {{-- select --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        .harga {
            color: black;
        }

        .text-bold-red {
            color: red;
            font-weight: bold;
        }

        .row {
            overflow: visible !important;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        /* Umum (mobile-first) */
        .nomor-kamar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(50px, 1fr));
            /* mobile responsif */
            gap: 0.5rem;
            margin-bottom: 20px;
        }

        .kamar {
            width: 40px;
            height: 30px;
            border: 1px solid #000;
            border-radius: 5px;
            margin: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            cursor: pointer;
            padding: 0.5rem 0;
            font-weight: bold;
        }

        .kamar.terpilih {
            background-color: limegreen;
        }

        /* Tombol Select All di grid */
        #selectAllBtn {
            grid-column: 7 / 8;
            justify-self: end;
            margin-bottom: 0.5rem;
        }

        /* Notifikasi */
        .unread {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .read {
            background-color: #ffffff;
            font-weight: normal;
        }

        /* Mobile styles */
        @media (max-width: 767.98px) {
            .mobile-fixed {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 1050;
                background-color: #fff;
                border-top: 1px solid #ccc;
                padding: 0.75rem 1rem;
            }

            .mobile-dropdown {
                width: 100%;
            }

            .desktop-only {
                display: none !important;
            }

            .dropup .dropdown-menu {
                bottom: 100% !important;
                top: auto !important;
                margin-bottom: 0.5rem;
            }
        }

        /* Desktop styles */
        @media (min-width: 768px) {
            .sticky-desktop {
                position: sticky;
                top: 80px;
                z-index: 1020;
            }

            .mobile-only {
                display: none !important;
            }

            .nomor-kamar {
                grid-template-columns: repeat(7, 1fr);
                /* hanya 7 kolom di desktop */
            }
        }

        /* Panah kiri (prev) jadi hitam */
        .carousel-control-prev-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='black' viewBox='0 0 8 8'%3E%3Cpath d='M4.854 1.646a.5.5 0 0 1 0 .708L2.707 4.5l2.147 2.146a.5.5 0 0 1-.708.708l-2.5-2.5a.5.5 0 0 1 0-.708l2.5-2.5a.5.5 0 0 1 .708 0z'/%3E%3C/svg%3E");
        }

        /* Panah kanan (next) jadi hitam */
        .carousel-control-next-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='black' viewBox='0 0 8 8'%3E%3Cpath d='M3.146 1.646a.5.5 0 0 1 .708 0l2.5 2.5a.5.5 0 0 1 0 .708l-2.5 2.5a.5.5 0 0 1-.708-.708L5.293 4.5 3.146 2.354a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
        }
    </style>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('/assets/css/bootstrap.min.css') }}" />
    <!-- My CSS -->
    <link rel="stylesheet" href="{{ asset('/vanila/main.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/css/style.css') }}" />

    <!-- Data Tables-->
    <link rel="stylesheet" href="{{ asset('/assets/css/dataTables.bootstrap5.min.css') }}" />

    <!-- SCSS -->
    <link rel="stylesheet" href="{{ asset('/assets/css/main.css') }}">

    <!-- AOS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/codemirror.min.css">

    <title>{{ $title ?? 'Sewa' }} | Aplikasi Keuangan dan Urusan Kegiatan</title>

</head>

<body id="{{ $active ?? '' }}" class="bg-white" style="padding-top: 70px;">



    <!-- Awal Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fw-bold small text-secondary fixed-top">
        <div class="container">
            <a class="navbar-brand" 
   href="@auth('akun_penyewa') {{ url('/sewa/index') }} @else {{ url('/dashboard-penyewa') }} @endauth">
    <img src="{{ asset('/assets/images/brand-logo.png') }}" alt="" width="150">
</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 links">
                    <li class="nav-item">
    <a data-active="index" class="nav-link" 
       href="@auth('akun_penyewa') {{ url('/sewa/index') }} @else {{ url('/dashboard-penyewa') }} @endauth">
        Beranda
    </a>
</li>


                    @auth('akun_penyewa')
                        <li class="nav-item">
                            <a data-active="riwayat" class="nav-link" href="{{ route('pesanan.saya') }}">Pesanan Saya</a>
                        </li>
                        <li class="nav-item">
                            <a data-active="panduan" class="nav-link" href="{{ route('panduan') }}">Panduan</a>
                        </li>
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
                                style="width: 400px; max-height: 400px; overflow-y: auto;">
                                <li class="dropdown-header text-center fw-bold text-primary">
                                    Notifikasi Terbaru
                                </li>
                                <div class="" id="notif-list"></div>
                                <li class="dropdown-footer text-center" id="mark-all-read-item" style="display: none;">
                                    <a href="#" id="mark-all-read" class="text-primary">Tandai Semua sudah dibaca</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-active="profile" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-user"></i>
                                <span>{{ auth('akun_penyewa')->user()->penyewa->nama_lengkap }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ url('/profile-penyewa') }}">Profile</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <form action="{{ url('/sewa/logout') }}" method="post">
                                    @csrf
                                    <li>
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fa-solid fa-sign-out-alt me-2"></i>Keluar
                                        </button>
                                    </li>
                                </form>
                            </ul>
                        </li>
                    @endauth

                    @guest('akun_penyewa')
                        <li class="nav-item">
                            <a href="{{ route('penyewa.login') }}" class="nav-link">Login</a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>


    <!-- Main Content -->
    @yield('content')

    {{-- Footer --}}
    <section class="bg-light pt-4">
        <div class="container text-secondary">
            <div class="row">
                {{-- State 1: Logo & Deskripsi --}}
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between">
                        <img src="{{ asset('/assets/images/brand-logo.png') }}" alt="AKUNKEUN" class="mb-3 mb-sm-0"
                            width="120">
                        <img src="{{ asset('/assets/images/LLDIKTI4 final1.png') }}" width="120" alt="LLDIKTI4">
                    </div>
                    <p class="small mt-3 text-center text-lg-start">
                        Akunkeun, atau Aplikasi Kegiatan dan Urusan Keuangan, adalah sebuah aplikasi yang dirancang
                        untuk mempermudah pengelolaan tugas kerja dan proses penyewaan Barang Milik Negara (BMN) di
                        lingkungan LLDIKTI Wilayah IV.
                    </p>
                </div>

                {{-- State 2: Kontak --}}
                <div class="col-lg-6">
                    <h5 class="fw-bold text-center text-lg-start">Informasi Kontak</h5>
                    <div class="row row-cols-1 gy-2 small mt-3">
                        <div class="col d-flex">
                            <i class="fa-solid fa-phone me-2 text-primary"></i>
                            <span>+022 7275630, +022 7274377</span>
                        </div>
                        <div class="col d-flex">
                            <i class="fa-brands fa-whatsapp me-2 text-success"></i>
                            <span>082244121226 <small>(Chat Only)</small></span>
                        </div>
                        <div class="col d-flex">
                            <i class="fa-solid fa-envelope me-2 text-primary"></i>
                            <span>informasi@lldikti4.or.id</span>
                        </div>
                        <div class="col d-flex">
                            <i class="fa-solid fa-location-dot me-2 text-primary"></i>
                            <span>Jalan Penghulu H. Hasan Mustofa No. 38 Bandung 40124</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="container mt-4">
        <div class="container text-center mb-3 small">
            <p>copyright ©2025, Akunkeun - Aplikasi Kegiatan dan Urusan Keuangan</p>
        </div>
    </section>

    <script src="{{ asset('/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/assets/js/navbar.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <!-- AOS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ asset('/assets/js/aos.js') }}"></script>


    {{-- script --}}
    <script src="{{ asset('/assets/js/script1.js') }}"></script>
    <script src="{{ asset('/vanila/main.js') }}"></script>

    <!-- Data Table -->
    <script src="{{ asset('/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('/assets/js/data-table.js') }}"></script>
    <script src="{{ asset('/assets/js/preventSelect2.js') }}"></script>

    <script src="{{ asset('/assets/js/toast.js') }}"></script>
    <script src="{{ asset('/assets/js/preventSurtug.js') }}"></script>
    <script src="{{ asset('/assets/js/preventTable.js') }}"></script>
    <script src="{{ asset('/assets/js/preventDokumen.js') }}"></script>
    <script src="{{ asset('/assets/js/preventSelect.js') }}"></script>

    <script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>
@auth
<script>
        function applyCustomValidation() {
            document.querySelectorAll('input[required], textarea[required], select[required]').forEach(function(el) {
                // Cegah listener ganda
                if (el.dataset.validationAttached) return;

                el.oninvalid = function(e) {
                    e.target.setCustomValidity('');
                    if (!e.target.validity.valid) {
                        e.target.setCustomValidity('Silakan lengkapi isian ini.');
                    }
                };
                el.oninput = function(e) {
                    e.target.setCustomValidity('');
                };
                el.dataset.validationAttached = true;
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            applyCustomValidation();

            // Kalau ada elemen baru dari AJAX atau render ulang
            const observer = new MutationObserver(applyCustomValidation);
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        });
    </script>
    <script>
$(document).ready(function() {
    function notifPenyewa() {
        $.ajax({
            url: "/notifPenyewa/{{ auth('akun_penyewa')->user()->id_penyewa }}",
            type: "GET",
            dataType: "json",
            success: function(res) {
                let totalNotif = res.notif;

                $("#notif").text(totalNotif > 100 ? '100+' : totalNotif);

                $('#notif-list').empty();

                if (res.notifData.length > 0) {
                    $('#mark-all-read-item').show();

                    res.notifData.forEach(function(notif) {
                        $('#notif-list').append(`
<li class="dropdown-item p-3 mb-2 border-bottom d-flex align-items-start" data-route="${notif.route}" data-id="${notif.id}">
  <div class="image-container me-3">
    <img src="{{ asset('/assets/images/icon akunkeun.png') }}" alt="Icon" width="50" class="rounded-circle">
  </div>
  <div class="flex-grow-1">
    <div class="fw-bold text-dark">${notif.header || 'Notifikasi'}</div>
    <div class="message text-muted" style="max-width: 240px; color: #212529; font-weight: 500; white-space: normal;">
      ${notif.message || 'Isi pesan notifikasi...'}
    </div>
    <div style="font-size: 12px; color: #6c757d;">${new Date(notif.created_at).toLocaleDateString()}</div>
  </div>
</li>
                        `);
                    });
                } else {
                    $('#notif-list').append(`
<li class="dropdown-item p-3 mb-2 border-bottom d-flex align-items-start">
    Tidak ada notifikasi
</li>
                    `);
                    $('#mark-all-read-item').hide();
                }

                // Klik item notifikasi → tandai sudah dibaca & redirect
                $('#notif-list').on('click', '.dropdown-item', function() {
                    const route = $(this).data('route');
                    const notifId = $(this).data('id');

                    $.ajax({
                        url: `/notifPenyewa/read/${notifId}`,
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function() {
                            window.location.href = `${window.location.origin}/${route}`;
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

    notifPenyewa(); // panggil saat load
    setInterval(notifPenyewa, 10000); // update tiap 10 detik
});
</script>
<script>
$(document).ready(function() {
    $('#mark-all-read').click(function(e) {
        e.preventDefault();

        if (!confirm('Apakah Anda yakin ingin menandai semua notifikasi sebagai dibaca?')) return;

        $.ajax({
            url: `/mark-all-notif-penyewa/{{ auth('akun_penyewa')->user()->id_penyewa }}`, // sesuaikan dengan route
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert(response.message || 'Semua notifikasi telah ditandai sebagai dibaca.');
                // optional: refresh notifikasi
                notifPenyewa();
            },
            error: function(xhr) {
                alert('Terjadi kesalahan saat menandai notifikasi sebagai dibaca.');
            }
        });
    });
});
</script>
@endauth
</body>

</html>
