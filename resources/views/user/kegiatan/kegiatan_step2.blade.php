@extends('user.templates.template')

@section('content')



<!-- Awal Form Perjadin Kegiatan  -->
<section id="beranda" class=" pb-5 mt-5 pt-5">
    <div class="container">
        <div class="row text-secondary justify-content-center">
            <div class="col-md-10 mb-3">
                <div class="row">
                    <h3 class="fw-bold text-secondary">Pengajuan Kegiatan</h3>
                </div>
                <div class="card shadow-sm rounded-0  border-0 p-3" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    <div class="card-body">
                        <!-- Progress bar -->
                        <div class="progressbar">
                            <div class="progress" id="progress"></div>
                            
                            <div class="progress-step" data-title="Judul Program">1</div>
                            <a href="{{url('/kegiatan_step_2/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary"><div class="progress-step progress-step-active" data-title="Informasi Dasar">2</div></a>
                            <a href="{{url('/kegiatan_step_3/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary"><div class="progress-step" data-title="Informasi Orang">3</div></a>
                            <a href="{{url('/kegiatan_step_4/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary hide-notif"><div class="progress-step" data-title="Fasilitas">4</div></a>
                            <a href="{{url('/kegiatan_step_5/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary hide-notif"><div class="progress-step" data-title="Mobilitas">5</div></a>
                            <a href="{{url('/kegiatan_step_6/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary hide-notif"><div class="progress-step" data-title="Sarana & Prasarana">6</div></a>
                            <a href="{{url('/kegiatan_step_7/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary hide-notif"><div class="progress-step" data-title="Dokumen Pendukung">7</div></a>

                        </div>
                        
                        <!-- Step 2 - Informasi Kegiatan -->
                        <form action="{{url('/c_detailKegiatan/'. $kegiatan->id)}}" method="post" id="multiphase">
                            @method('PUT')
                            @csrf
                            <div class="mb-3">
                                <div class="col-md-12">
                                    <h6 class="text-secondary">Informasi Dasar</h6><br>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                    <label for="floatingTextarea">Judul Kegiatan</label>
                                    <textarea class="form-control mt-1" id="floatingTextarea" disabled>{{ $kegiatan->nama_kegiatan }}</textarea>
                                    <input type="hidden" name="kegiatan" value="{{ $kegiatan->id }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="form-label">Jenis Kegiatan</label>
                                    <div class="col-md-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="jenis_kegiatan" id="jenis1" value="Luring" checked>
                                            <label for="jenis1" class="form-label">Luring</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="jenis_kegiatan" id="jenis2" value="Daring">
                                            <label for="jenis2" class="form-label">Daring</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="jenis_kegiatan" id="jenis3" value="Hybrid">
                                            <label for="jenis3" class="form-label">Hybrid</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="" class="form-label">Tanggal Mulai Acara <span class="text-danger">*</span></label>
                                        <input type="datetime-local" name="tgl_mulai" id="" class="form-control" required value="{{ $kegiatan->tgl_mulai }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="" class="form-label">Tanggal Selesai Acara <span class="text-danger">*</span></label>
                                        <input type="datetime-local" name="tgl_selesai" id="" class="form-control" required value="{{ $kegiatan->tgl_selesai }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="" class="form-label">Jumlah Peserta</label>
                                        <input type="number" min="0" name="jumlah_peserta" id="" class="form-control" value="{{ $kegiatan->jumlah_peserta }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="" class="form-label">Provinsi <span class="text-danger">*</span></label>
                                        <input type="text" name="provinsi" id="" class="form-control" required value="{{ $kegiatan->provinsi }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="" class="form-label">Kota/Kabupaten <span class="text-danger">*</span></label>
                                        <input type="text" name="kab_kota" id="" class="form-control" required value="{{ $kegiatan->kab_kota }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="" class="form-label">Desa/Kecamatan</label>
                                        <input type="text" name="desa" id="" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                    <label for="floatingTextarea">Alamat <span class="text-danger">*</span></label>
                                    <textarea class="form-control mt-1" id="floatingTextarea" name="alamat" required>{{ $kegiatan->alamat }}</textarea>
                                    </div>
                                </div>
                                <div class="btns-group d-grid gap-2 col-6 mx-auto pb-3 mt-5">
                                    <button onclick="window.history.back();" class="btn btn-primary">Sebelumnya</button>
                                    <button type="submit" class="btn btn-next btn-primary" >Selanjutnya</button>
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