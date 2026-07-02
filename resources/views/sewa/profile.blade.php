@extends('sewa.template')

@section('content')
    <div class="container">
        <h3>Profil Saya</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control"
                    value="{{ old('nama_lengkap', $penyewa->nama_lengkap) }}" required>
            </div>

            <div class="mb-3">
                <label>NIK</label>
                <input type="text" name="nik" class="form-control" value="{{ old('nik', $penyewa->nik) }}"
                    maxlength="16" required>
            </div>

            <div class="mb-3">
                <label>No Telepon</label>
                <div class="input-group">
                    <span class="input-group-text">+62</span>
                    <input type="tel" name="no_telepon" class="form-control"
                        value="{{ old('no_telepon', ltrim($penyewa->no_telepon, '62')) }}" pattern="[0-9]{1,12}"
                        inputmode="numeric" title="Nomor telepon maksimal 12 digit setelah +62" maxlength="12" required>
                </div>
            </div>


            <hr>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="mb-3">
                <label>Password Baru (kosongkan jika tidak ingin ganti)</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Update Profil</button>
        </form>
    </div>
@endsection
