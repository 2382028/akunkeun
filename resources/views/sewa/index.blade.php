@extends('sewa.template')

@section('content')
    {{-- jumbotron --}}
    <section>
        <div class="jumbo pt-5" style="width: 100%;">
            {{-- content --}}
            <div class="container center-item">
                {{-- row --}}
                <div class="row align-items-center text-secondary">
                    <div class="col-12 col-md-6 col-lg-6 order-md-2 mb-3 mobile" data-aos="fade-up" data-aos-delay="100"
                        data-aos-duration="1000">
                        <img src="{{ asset('/assets/images/jumbotron.png') }}"
                            class="img-fluid mw-md-170 mw-lg-150 mb-6 mb-md-0" alt=""
                            style="max-width: 400px; float: right;">
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 order-md-1" data-aos="fade-up" data-aos-delay="100"
                        data-aos-duration="1000">
                        <div class="mb-3">
                            <div class="col mb-1">
                                <img src="{{ asset('/assets/images/icon akunkeun.png') }}" style="max-width: 150px;"
                                    alt="">
                            </div>
                            <br>
                            <h1 class="text-md-start fw-bold">
                                Sewa BMN LLDIKTI IV
                            </h1>
                            <p class="lead text-md-start text-muted mb-3 mb-lg-8 small">Sewa mess, gor dan lapangan futsal
                                kini tersedia secara online</p>
                        </div>
                        <div class="mb-3">
                            <img src="{{ asset('/assets/images/LLDIKTI4 final1.png') }}" width="150px" alt="">
                            @auth('akun_penyewa')
                                <span class="badge text-bg-success">Hai
                                    {{ auth('akun_penyewa')->user()->penyewa->nama_lengkap }}</span>
                            @endauth

                        </div>
                    </div>
                </div>
                {{-- end row --}}
            </div>
        </div>
    </section>

    <!-- Fitur Aplikasi Akunkeun -->
    <section>
        <div class="container mt-5">
            <h3 class="fw-bold text-secondary">Fitur Aplikasi Akunkeun</h3>
            <div class="row mt-3">
                {{-- start --}}
                <div class="row mt-3">
                    @php
                        $cards = [
                            [
                                'title' => 'Mess',
                                'route' => route('mess'),
                                'image' => asset('/assets/images/mess-ikon.svg'),
                                'desc' => 'Mess LLDIKTI IV kini dapat disewa secara online dengan mudah.',
                            ],
                            [
                                'title' => 'Gor Badminton (Coming soon)',
                                'route' => url('/sewa'),
                                'image' => asset('/assets/images/3 - perjadin (home).svg'),
                                'desc' => 'Lapangan badminton indoor tersedia untuk disewa harian.',
                            ],
                            [
                                'title' => 'Lapangan Futsal (Coming soon)',
                                'route' => url('/sewa'),
                                'image' => asset('/assets/images/3 - perjadin (home).svg'),
                                'desc' => 'Lapangan futsal dengan permukaan berkualitas siap digunakan.',
                            ],
                        ];
                    @endphp

                    @foreach ($cards as $card)
                        <div class="col-12 col-md-4 mb-4 d-flex">
                            <a href="{{ $card['route'] }}" class="nav-link w-100">
                                <div class="card h-100 shadow-sm" data-aos="zoom-in" data-aos-delay="100"
                                    data-aos-duration="1000">
                                    <img src="{{ $card['image'] }}" class="card-img-top img-fluid p-4"
                                        alt="{{ $card['title'] }}">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold">{{ $card['title'] }}</h5>
                                        <p class="card-text small">{{ $card['desc'] }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- end --}}
            </div>
        </div>
    </section>
@endsection
