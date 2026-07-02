@extends('sewa.template') {{-- Menyesuaikan dengan template yang digunakan penyewa --}}

@section('content')
<section id="login" style="margin-top: 20vh; margin-bottom: 20vh">
    @if (session()->has('LoginError'))
        <div class="alert alert-danger text-center">{{ session('LoginError') }}</div>
    @endif

    <div class="container">
        <div class="card mx-auto shadow-sm border-0 rounded-0" style="max-width: 450px">
            <div class="card-body">
                <h5 class="text-center mb-4">Login Penyewa</h5>
                <form action="{{ url('/login-penyewa') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" name="email" required autofocus value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Masuk</button>
                    </div>
                </form>
                <p class="text-center mt-3">Belum punya akun? <a href="{{ route('penyewa.register') }}">Daftar di sini</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
