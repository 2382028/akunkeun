<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Logo -->
    <link rel="icon" href="{{asset('/assets/icons/akunkeun.png')}}" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- My CSS -->
    <link rel="stylesheet" href="{{asset('/assets/css/style-admin.css')}}">

    <title>Login Admin</title>
  </head>
  <body  style="background-color: #D9D9D9">
    @if (session()->has('LoginError'))
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="z-index: 1050;">
        <div class="toast-container end-0 mt-4 pt-4 position-fixed">
            <div class="show toast" role="alert" aria-live="assertive" aria-atomic="true" id="myToast">
                <div class="toast-header">
                  <strong class="me-auto">Pesan Sobat Akunkeun</strong>
                  <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {{ session('LoginError') }}
                </div>
              </div>
        </div>
    </div>
    @endif

    <section id="login">
    <div class="container my-4">
        <div class="mx-auto text-center">
            <h4 class="fw-bold">Selamat Datang di <br> Aplikasi Keuangan dan Urusan Kegiatan </h4><br>
        </div>
        <div class="card mx-auto" style="max-width: 50rem;">
            <div class="small text-center">
                <img class="card-img-top mx-auto mt-5" src="{{asset('/assets/images/LLDIKTI4 final1.png')}}" style="width: 30%; " alt="Card image cap">
                <hr class="mx-auto" width="75%">
                <p class="text-muted">Silakan masuk terlebih dahulu</p>
                </div>
            <div class="card-body mx-4 small submit">
                <form action="{{url('/verifikasi')}}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="InputEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="InputEmail" placeholder="Masukkan email" name="email" autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="InputPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="InputPassword" placeholder="Masukkan password" name="password">
                    </div>
                    <label for="InputTahun" class="form-label">Tahun</label>
                    <div class="input-group mb-3 submit-select">
                        <select class="form-select text-muted" id="inputGroupSelect01" name="versi" required>
                            <option value="" disabled {{ $versis->where('status', 'aktif')->isEmpty() ? 'selected' : '' }}>Pilih tahun</option>
                            @foreach ($versis as $versi)
                                <option value="{{ $versi->id }}" {{ $versi->status == 'aktif' ? 'selected' : '' }}>
                                    {{ $versi->versi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-grid gap-2 submit-button">
                        <button class="btn btn-primary" type="submit">Masuk</button>
                        <a href="{{ url('/akses') }}" class="btn btn-outline-secondary">Kembali ke Login User</a>
                    </div>
                    <br>
                    <p class="text-center text-muted small">copyright &#169; 2024</p>
                </form>
            </div>
        </div>
    </div>
    </section>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="{{asset('/assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('/assets/js/jquery-3.5.1.js')}}"></script>
    <script src="{{asset('/assets/js/script.js')}}"></script>
    <script src="{{asset('/assets/js/navbar.js')}}"></script>
    <script src="{{asset('/assets/js/toast.js')}}"></script>
  </body>
</html>
