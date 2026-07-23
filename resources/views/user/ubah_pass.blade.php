@extends('user.templates.sidebar')

@section('content')
        <!-- Awal Form Perjadin Kegiatan  -->
        <section id="beranda" class="pb-5 pt-4">
            <div class="container">
                <div class="row text-secondary justify-content-center">
                    <div class="col-md-10 mb-3">
                        <div class="row">
                            <h3 class="fw-bold text-secondary">Password Baru</h3>
                        </div>
                        <div class="card shadow-sm rounded-0  border-0 p-3" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                            <div class="card-body">
                                <form action="{{url('/ubah-password')}}" method="post">
                                    @csrf
                                    <div class="mb-2">
                                        <div class="row mb-3">
                                            <div class="col-md-12 mb-3">
                                                <label for="" class="form-label">Password Baru</label>
                                                <input type="password" name="newPassword" id="" class="form-control" required>
                                                @error('newPassword')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="" class="form-label">Ulangi Password</label>
                                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                                                @error('password_confirmation')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="btns-group d-grid gap-2 col-6 mx-auto pb-3 mt-5">
                                            <button type="submit" class="btn btn-next btn-primary">Ubah Password</button>
                                        </div>
                                    </div>
    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Akhir Form Perjadin Kegiatan -->
@endsection