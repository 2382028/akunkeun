@extends('user.templates.template')

@section('content')
<section id="login" style="margin-top: 20vh; margin-bottom: 20vh">
    @if (session()->has('LoginError'))
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container end-0 mt-4 pt-4 position-fixed">
            <div class="show toast" role="alert" aria-live="assertive" aria-atomic="true" id="myToast">
                <div class="toast-header">
                  {{-- <img src="assets/images/Logo Akunkeun 1.png" class="rounded me-2" alt="akunkeun"> --}}
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
    <div class="container my-5">
        <div class="card mx-auto shadow-sm border-0 rounded-0" style="max-width: 450px">
            <div class="small text-center">
                <img class="card-img-top mx-auto mt-3" src="{{asset('/assets/images/logo akunkeun.png')}}" style="max-width: 200px" alt="Card image cap">
            </div>
            <div class="card-body small ">
                <form action="{{url('/akses')}}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label text-muted">Email / NIP</label>
                        <input type="email" class="form-control" id="" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label text-muted">Password</label>
                        <input type="password" class="form-control" id="" name="password" required>
                    </div>
                    <div class="d-grid gap-2 submit-button">
                        <button class="btn btn-primary fw-bold" type="submit">Masuk</button>
                    </div>
                    <br>
                    <p class="text-center text-muted small">Bantuan!</p>
                </form>
            </div>
        </div>
    </div>
    </section>
@endsection