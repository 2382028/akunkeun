@extends('user.templates.template')

@php
    $activeVersi = \App\Models\Versi::where('status', 'aktif')->first() ?? (object) ['id' => '-1','versi' => 'Default Versi'];
@endphp

@section('content')
    {{-- jumbotron --}}
    <section >
      <div class="jumbo top-nav pt-5" style="width: 100%;" >
          {{-- content --}}
          <div class="container center-item">
              {{-- row --}}
              <div class="row align-items-center text-secondary">
                  <div class="col-12 col-md-6 col-lg-6 order-md-2 mb-3 mobile" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                      <img src="{{asset('/assets/images/jumbotron.png')}}" class="img-fluid mw-md-170 mw-lg-150 mb-6 mb-md-0" alt="" style="max-width: 400px; float: right;">
                  </div>
                  <div class="col-12 col-md-6 col-lg-6 order-md-1" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                      <div class="mb-3">
                          <div class="col mb-1">
                              <img src="{{ asset('/assets/images/icon akunkeun.png') }}" style="max-width: 150px;" alt="">
                          </div>
                          <br>
                          <h1 class="text-md-start fw-bold">
                              AKUNKEUN
                            </h1>
                          <p class="lead text-md-start text-muted mb-3 mb-lg-8 small">Aplikasi Kegiatan dan Urusan Keuangan</p>
                      </div>
                      <div class="d-flex mb-3">
                        @if ($activeVersi && ($activeVersi->id != session('versi')))
                            <a href="{{url('/perjadin')}}" class="btn btn-primary btn-sm small fw-bold text-white" onclick="showAlert(event)">Ajukan Perjalanan Dinas!</a>
                            <a href="{{url('/kegiatan')}}" class="btn btn-warning btn-sm small fw-bold text-white mx-1" onclick="showAlert(event)">Buat Program Kegiatan!</a>
                        @else
                            <a href="{{url('/perjadin')}}" class="btn btn-primary btn-sm small fw-bold text-white">Ajukan Perjalanan Dinas!</a>
                            <a href="{{url('/kegiatan')}}" class="btn btn-warning btn-sm small fw-bold text-white mx-1">Buat Program Kegiatan!</a>
                        @endif
                      </div>
                      <div class="mb-3">
                          <img src="{{asset('/assets/images/LLDIKTI4 final1.png')}}" width="150px" alt="">
                          @auth
                            <span class="badge text-bg-success">Hai {{ auth('pegawai')->user()->nama_lengkap }}</span>
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
            @if ($activeVersi && ($activeVersi->id != session('versi')))
                <div class="col-sm-4 mb-3">
                    <a href="{{url('/perjadin')}}" class="nav-link" onclick="showAlert(event)">
                    <div class="card" style="max-width: 21rem; height: 23rem;" data-aos="zoom-in" data-aos-delay="100" data-aos-duration="1000">
                        <img src="{{asset('/assets/images/3 - perjadin (home).svg')}}" class="card-img-top pb-4 mt-3" alt="...">
                        <div class="card-body">
                        <h5 class="card-title fw-bold">Perjalanan Dinas</h5>
                        <p class="card-text small text-justify">Pengajuan perjalanan dinas saat ini dapat dilakukan secara online dan akan diproses secara langsung sehingga keefisiensian waktu bisa dimanfaatkan secara maksimal.</p>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-sm-4 mb-3">
                    <a href="{{url('/kegiatan')}}" class="nav-link" onclick="showAlert(event)">
                    <div class="card" style="max-width: 21rem; height: 23rem;" data-aos="zoom-in" data-aos-delay="100" data-aos-duration="1000">
                    <img src="{{asset('/assets/images/4 - informasi kegiatan.png')}}" class="card-img-top mt-3" alt="...">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Program Kegiatan</h5>
                            <p class="card-text small text-justify">Pengajuan Program Kegiatan saat ini dapat dilakukan secara online hanya dengan melengkapi formulir program yang diajukan akan segera diproses oleh pihak keuangan.</p>
                    </div>
                    </div>
                </a>
                </div>
            @else
                <div class="col-sm-4 mb-3">
                    <a href="{{url('/perjadin')}}" class="nav-link">
                    <div class="card" style="max-width: 21rem; height: 23rem;" data-aos="zoom-in" data-aos-delay="100" data-aos-duration="1000">
                        <img src="{{asset('/assets/images/3 - perjadin (home).svg')}}" class="card-img-top pb-4 mt-3" alt="...">
                        <div class="card-body">
                        <h5 class="card-title fw-bold">Perjalanan Dinas</h5>
                        <p class="card-text small text-justify">Pengajuan perjalanan dinas saat ini dapat dilakukan secara online dan akan diproses secara langsung sehingga keefisiensian waktu bisa dimanfaatkan secara maksimal.</p>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-sm-4 mb-3">
                    <a href="{{url('/kegiatan')}}" class="nav-link">
                    <div class="card" style="max-width: 21rem; height: 23rem;" data-aos="zoom-in" data-aos-delay="100" data-aos-duration="1000">
                    <img src="{{asset('/assets/images/4 - informasi kegiatan.png')}}" class="card-img-top mt-3" alt="...">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Program Kegiatan</h5>
                            <p class="card-text small text-justify">Pengajuan Program Kegiatan saat ini dapat dilakukan secara online hanya dengan melengkapi formulir program yang diajukan akan segera diproses oleh pihak keuangan.</p>
                    </div>
                    </div>
                </a>
                </div>
            @endif

            <div class="col-sm-4 mb-3">
              <a href="{{url('/fasilitas')}}" class="nav-link">
              <div class="card" style="max-width: 21rem; height: 23rem;" data-aos="zoom-in" data-aos-delay="100" data-aos-duration="1000">
                <img src="{{asset('/assets/images/5 - peminjaman asset.png')}}" class="card-img-top mt-3" alt="...">
                <div class="card-body">
                  <h5 class="card-title fw-bold">Peminjaman Aset BMN</h5>
                  <p class="card-text small text-justify">Peminjaman aset BMN saat ini dapat dilakukan secara online, pengaju hanya perlu memilih assets yang akan dipinjam lalu pihak BMN akan segera memprosesnya.</p>
                </div>
              </div>
            </a>
            </div>

            {{-- end --}}
          </div>
        </div>
      </section>

      <!-- Menu Lain-->
      <section id="menu" class="mt-5">
          <div class="container mt-5">
              <div class="row">
                  <div class="col-md-12">
                    <div class="card mb-3 p-3 border-0 text-secondary" >
                      <div class="row d-flex align-items-center" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                        <div class="col-12 col-md-5 col-lg-5 mb-3">
                          <img src="{{asset('/assets/images/beranda-perjadin.png')}}" class="img-fluid rounded-start" alt="">
                        </div>
                        <div class="col-12 col-md-2 col-lg-2 mb-3 align-self-center ">
                          <div class="circle-with-text bg-light fw-bold h3">
                            01
                          </div>
                        </div>
                        <div class="col-12 col-md-5 col-lg-5 mb-3">
                          <h3 class="card-title fw-bold">Perjalanan Dinas</h3>
                          <p class="card-text small">Pengajuan perjalanan dinas sekarang bisa dilakukan secara online dan akan diproses secara langsung sehingga keefisiensian waktu bisa dimanfaatkan secara maksimal, proses pengajuan ada 2 tahap yaitu mengisi surat undangan dan surat tugas lalu mengisi data orang yang akan diajukan melakukan perjalan dinas.</p>
                          <div class="text-center mb-3 small">
                            <figure class="figure text-center">
                              <img src="{{asset('/assets/images/1 - perjadin.png')}}" class="figure-img img-fluid rounded" width="100" alt="...">
                              <figcaption class="figure-caption">1. Informasi Perjadin</figcaption>
                            </figure>
                            <figure class="figure text-center mx-2">
                                <img src="{{asset('/assets/images/4 - informasi kegiatan.png')}}" class="figure-img img-fluid rounded" width="100" alt="...">
                                <figcaption class="figure-caption">2. Informasi Peserta</figcaption>
                            </figure>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="card mb-3 p-3 border-0 text-secondary" data-aos="fade-up" >
                    <div class="row d-flex align-items-center" data-aos-delay="100" data-aos-duration="1000">
                      <div class="col-12 col-md-5 col-lg-5 order-md-3 mb-3">
                        <img src="{{asset('/assets/images/beranda-kegiatan.png')}}" class="img-fluid rounded-start" alt="">
                      </div>
                      <div class="col-12 col-md-2 col-lg-2 order-md-2 mb-3 align-self-center ">
                        <div class="circle-with-text bg-light fw-bold h3">
                          02
                        </div>
                      </div>
                      <div class="col-12 col-md-5 col-lg-5 order-md-1 mb-3">
                        <h3 class="card-title fw-bold ">Program Kegiatan</h3>
                        <p class="card-text small">Pengajuan Program Kegiatan sekarang bisa dilakukan secara online pengaju hanya tinggal melengkapi formulirnya maka program yang diajukan akan diproses oleh pihak keuangan. ada 6 tahap proses pengajuan program seperti ilustrasi dibawah.</p>
                        <div class="text-center mb-3 small">
                          <figure class="figure text-center">
                            <img src="{{asset('/assets/images/1 - perjadin.png')}}" class="figure-img img-fluid rounded" width="100" alt="...">
                            <figcaption class="figure-caption">1. Judul Program</figcaption>
                          </figure>
                          <figure class="figure text-center mx-2">
                              <img src="{{asset('/assets/images/4 - informasi kegiatan.png')}}" class="figure-img img-fluid rounded" width="100" alt="...">
                              <figcaption class="figure-caption">2. Informasi Kegiatan</figcaption>
                          </figure>
                          <figure class="figure text-center mx-2">
                              <img src="{{asset('/assets/images/2 - peserta.png')}}" class="figure-img img-fluid rounded" width="100" alt="...">
                              <figcaption class="figure-caption">3. Informasi Orang</figcaption>
                          </figure>
                          <figure class="figure text-center mx-2">
                              <img src="{{asset('/assets/images/6 - fasilitas.png')}}" class="figure-img img-fluid rounded" width="100" alt="...">
                              <figcaption class="figure-caption">4. Fasilitas</figcaption>
                          </figure>
                          <figure class="figure text-center mx-2">
                              <img src="{{asset('/assets/images/6 - fasilitas.png')}}" class="figure-img img-fluid rounded" width="100" alt="...">
                              <figcaption class="figure-caption">5. Mobilisasi</figcaption>
                          </figure>
                          <figure class="figure text-center mx-2">
                              <img src="{{asset('/assets/images/5 - peminjaman asset.png')}}" class="figure-img img-fluid rounded" width="100" alt="...">
                              <figcaption class="figure-caption">6. Sarana dan Prasarana</figcaption>
                          </figure>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="card mb-3 p-3 border-0 text-secondary" >
                    <div class="row d-flex align-items-center" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                      <div class="col-12 col-md-5 col-lg-5 mb-3" >
                        <img src="{{asset('/assets/images/beranda-bmn.png')}}" class="img-fluid rounded-start" alt="">
                      </div>
                      <div class="col-12 col-md-2 col-lg-2 mb-3 align-self-center ">
                        <div class="circle-with-text bg-light fw-bold h3">
                          03
                        </div>
                      </div>
                      <div class="col-12 col-md-5 col-lg-5 mb-3">
                        <h3 class="card-title fw-bold">Peminjaman Aset BMN</h3>
                        <p class="card-text small">Peminjaman assets BMN sekarang bisa dilakukan secara online, pengaju hanya tinggal memilih assets yang akan dipinjamkan lalu pihak BMN akan memprosesnya. adapun fiktur untuk permohonan perbaikan assets yang sedang dipinjam jika ada masalah yang terdapat pada barang yang dipinjam.</p>
                        <div class="text-center mb-3 small">
                          <figure class="figure text-center">
                            <img src="{{asset('/assets/images/1 - perjadin.png')}}" class="figure-img img-fluid rounded" width="100" alt="...">
                            <figcaption class="figure-caption">1. Formulir Peminjaman</figcaption>
                          </figure>
                          <figure class="figure text-center mx-2">
                              <img src="{{asset('/assets/images/4 - informasi kegiatan.png')}}" class="figure-img img-fluid rounded" width="100" alt="...">
                              <figcaption class="figure-caption">2. Pemilihan Aset</figcaption>
                          </figure>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </section>
@endsection
