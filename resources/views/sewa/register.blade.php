@extends('sewa.template')

@section('content')
    <section id="register" style="margin-top: 20vh; margin-bottom: 20vh">
        <div class="container">
            <div class="card mx-auto shadow-sm border-0 rounded-0" style="max-width: 450px">
                <div class="card-body">
                    <h5 class="text-center mb-4">Registrasi Penyewa</h5>
                    <form action="{{ url('/register-penyewa') }}" method="post">
                        @csrf

                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama_lengkap" required
                                value="{{ old('nama_lengkap') }}">
                            @error('nama_lengkap')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="no_telepon" class="form-label">No. Telepon</label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="tel" pattern="[0-9]+" minlength="8" maxlength="13" class="form-control"
                                    name="no_telepon" inputmode="numeric" title="Hanya angka yang diperbolehkan setelah +62"
                                    required value="{{ old('no_telepon') }}">
                            </div>
                            @error('no_telepon')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" pattern="\d{16}" maxlength="16" class="form-control" name="nik"
                                required value="{{ old('nik') }}" title="NIK harus 16 digit angka">
                            @error('nik')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                name="email" required value="{{ old('email') }}">
                            @error('email')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required minlength="8">
                            @error('password')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Daftar</button>
                        </div>
                    </form>
                    <p class="text-center mt-3">Sudah punya akun? <a href="{{ route('penyewa.login') }}">Login di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
