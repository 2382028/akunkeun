<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width initial-scale=10" />

    <!-- Logo -->
    <link rel="icon" href="{{ asset('/assets/images/icon akunkeun.png') }}" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('/assets/css/bootstrap.min.css') }}" />
    <!-- My CSS -->
    <link rel="stylesheet" href="{{ asset('/assets/css/style-admin.css') }}" />
    {{--
  <link rel="stylesheet" href="{{asset('vanila/main.css')}}" /> --}}

    <!-- Data Tables-->
    <link rel="stylesheet" href="{{ asset('/assets/css/dataTables.bootstrap5.min.css') }}" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Table to Excel -->
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
    <script src="https://unpkg.com/file-saver"></script>

    <style>
        .legend-box {
    display: inline-block;
    width: 20px;
    height: 12px;
    border-radius: 2px;
}

        .bg-purple {
            background-color: #6f42c1 !important;
            color: #fff !important;
        }

        .bg-teal {
            background-color: #20c997 !important;
            color: #fff !important;
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
            /* Menjadikan gambar juga bulat */
        }

        td,
        th {
            vertical-align: middle !important;
        }
    </style>

    <title> | Aplikasi Keuangan dan Urusan Kegiatan</title>
</head>

<body id="index">
    <!-- Awal Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-white shadow-sm noprint" style="color: black">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"
                aria-controls="offcanvasExample">
                <span class="navbar-toggler-icon" data-bs-target="#offcanvasExample"></span>
            </button>
            <a class="navbar-brand py-0 noprint" href="{{ url('/penyedia') }}">
                <img src="{{ asset('/assets/images/icon akunkeun.png') }}" alt="" width="50">
                <h5 class="d-inline-block align-text-top fw-bold pt-1">AKUNKEUN</h5>
            </a>
            <button class="btn btn-white hide-btn" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                <span class="navbar-toggler-icon" data-bs-target="#offcanvasExample"></span>
            </button>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavBar"
                aria-controls="topNavBar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="topNavBar">
                <ul class="navbar-nav ms-auto">
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
                            <!-- Header Notifikasi -->
                            <li class="dropdown-header text-center fw-bold text-primary">
                                Notifikasi Terbaru
                            </li>
                            <div class="" id="notif-list"></div>
                            <!-- Footer Notifikasi -->
                            <li class="dropdown-footer text-center" id="mark-all-read-item" style="display: none;">
                                <a href="#" id="mark-all-read" class="text-primary">Tandai Semua sudah
                                    dibaca</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle small" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-user"></i> {{ auth('penyedia')->user()->email }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ url('/penyedia/pengaturan') }}">
                                    <i class="fa fa-cog me-1"></i> Pengaturan
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <form action="{{ url('/penyedia/logout') }}" method="post">
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
        <div class="offcanvas offcanvas-start sidebar-nav" tabindex="-1" id="offcanvasExample"
            aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <a class="navbar-brand py-0 d-flex align-items-center" href="{{ url('/penyedia') }}">
                    <div class="image-container me-2">
                        <img src="{{ asset('/assets/images/icon akunkeun.png') }}" alt="" width="50">
                    </div>
                    <h5 class="d-inline-block align-text-top fw-bold pt-1 text-white">AKUNKEUN</h5>
                </a>
                <button type="button" class="btn-close text-reset text-white" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <nav class="navbar-dark">
                    <ul class="navbar-nav">
                        <li>
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header ">
                                        <a href="{{ url('/penyedia') }}"
                                            class="accordion-button collapsed text-decoration-none custom-button remove-button py-3">
                                            <span class="me-2"><i
                                                    class="fa-solid fa-solid fa-screwdriver-wrench active"></i></span>
                                            <span>Pemeliharaan</span>
                                        </a>
                                    </h2>
                                </div>
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
                        <button type="button" class="btn-close" data-bs-dismiss="toast"
                            aria-label="Close"></button>
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
                <div class="show toast bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true"
                    id="myToast">
                    <div class="toast-header bg-danger text-white">
                        <strong class="me-auto">Pesan Sobat Akunkeun</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"
                            aria-label="Close"></button>
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

    <!-- Awal Footer -->
    <section id="footer" class="bg-white noprint" style="z-index: -2;">
        <div class="container-fluid">
            <div class="text-center py-2 small fw-bold py-3">Copyright &#169; 2025. All Right Reserved.</div>
        </div>
    </section>
    <!-- Akhir Footer -->


    {{-- script --}}
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
    function notifPenyedia() {
        $.ajax({
            url: "/notifPenyedia/{{ auth('penyedia')->id() }}",
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
                        url: `/notifPenyedia/read/${notifId}`,
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

    notifPenyedia(); // panggil saat load
    setInterval(notifPenyedia, 10000); // update tiap 10 detik
});
</script>
<script>
$(document).ready(function() {
    $('#mark-all-read').click(function(e) {
        e.preventDefault();

        if (!confirm('Apakah Anda yakin ingin menandai semua notifikasi sebagai dibaca?')) return;

        const idPenyedia = '{{ auth("penyedia")->id() }}'; // ambil id penyedia yang login

        $.ajax({
            url: `/mark-all-notif-penyedia/${idPenyedia}`, // sesuaikan dengan route
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert(response.message || 'Semua notifikasi telah ditandai sebagai dibaca.');
                // optional: refresh notifikasi
                notifPenyedia();
            },
            error: function(xhr) {
                alert('Terjadi kesalahan saat menandai notifikasi sebagai dibaca.');
            }
        });
    });
});
</script>



    <!-- <script src="{{ asset('/assets/js/script.js') }}"></script> -->
    <script src="{{ asset('/vanila/main.js') }}"></script>

    <script src="{{ asset('/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/assets/js/script1.js') }}"></script>
    <!-- <script src="{{ asset('assets/js/script1.js?v=' . time()) }}"></script> -->
    <script src="{{ asset('/assets/js/offcanvas.js') }}"></script>

    <!-- Data Table -->
    <script src="{{ asset('/assets/js/jquery-3.5.1.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('/assets/js/data-table.js') }}"></script>

    <script src="{{ asset('/assets/js/toast.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('/assets/js/sbm.js') }}"></script>
    <script src="{{ asset('/assets/js/printpage.js') }}"></script>
    <script src="{{ asset('/assets/js/prevent-submit.js') }}"></script>
    <script src="{{ asset('/assets/js/shownominal.js') }}"></script>

</body>

</html>
