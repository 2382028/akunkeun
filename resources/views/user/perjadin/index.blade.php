@extends('user.templates.template')

@section('content')
<section class="mb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div style="margin-top: 100px">
                    <h3 class="fw-bold text-secondary">Pengajuan Perjalanan Dinas</h3>
                </div>
                {{-- card --}}
                <div class="card p-3 shadow border-0 rounded-0 mt-3" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    <div class="card-body">
                        <!-- Progress bar -->
                        <div class="progressbar col-md-6 mx-auto">
                            <div class="progress" id="progress"></div>

                            <div class="progress-step progress-step-active" data-title="Informasi Dasar">1</div>
                            <div class="progress-step" data-title="Informasi Peserta">2</div>
                        </div>

                        <!-- Step 1 -->
                        <form action="{{url('/perjadin/store')}}" method="post" onsubmit="return validateSelect()">
                            @csrf
                            <div class="col-md-12">
                                <h6 class="text-secondary fw-bold mt-3">Informasi Dasar</h6><br>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-12">
                                    <label for="floatingTextarea">Judul Kegiatan (Sesuai dengan Surat Undangan)<span class="text-danger">*</span></label>
                                    <textarea class="form-control mt-1" id="floatingTextarea" name="nama_kegiatan" required></textarea>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-12">
                                    <label for="" class="form-label">Pemberi Surat Undangan</label>
                                    <input type="text" name="pemberi_undangan" id="" class="form-control">
                                </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="" class="form-label">Tanggal Surat Undangan<span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_surat" id="tgl_surat" class="form-control" required>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="konfirmasi" class="form-label">Apakah tanggal keberangkatan sama dengan tanggal mulai acara?</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="konfirmasi" id="ya" value="ya" checked>
                                        <label class="form-check-label" for="ya">Ya</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="konfirmasi" id="tidak" value="tidak">
                                        <label class="form-check-label" for="tidak">Tidak</label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="" class="form-label">Tanggal Keberangkatan<span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="tgl_keberangkatan" id="tgl_keberangkatan" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="" class="form-label">Tanggal Mulai Acara<span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="tgl_mulai" id="tgl_mulai" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="" class="form-label">Tanggal Selesai Acara<span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="tgl_selesai" id="tgl_selesai" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="provinsi" class="form-label">Provinsi<span class="text-danger">*</span></label>
                                    <input type="text" id="provinsi" name="provinsi" class="form-control" style="text-transform: capitalize" placeholder="Masukkan Provinsi" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="" class="form-label">Kota/Kabupaten<span class="text-danger">*</span></label>
                                    <input type="text" name="kabupaten_kota" id="" class="form-control" style="text-transform: capitalize" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="" class="form-label">Desa/Kecamatan</label>
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-12">
                                    <label for="floatingTextarea">Alamat<span class="text-danger">*</span></label>
                                    <textarea class="form-control mt-1" id="floatingTextarea" name="alamat" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="" class="form-label">Mobilitas<span class="text-danger">*</span></label>
                                    <select class="form-select required2" aria-label=".form-select-sm example" name="fasilitas_perjadin">
                                        <option value="">Pilih Mobilitas</option>
                                        <option value="Kendaraan Dinas">Kendaraan Dinas</option>
                                        <option value="Transportasi Publik">Transportasi Publik</option>
                                        <option value="Kendaraan Dinas dan Transportasi Publik">Kendaraan Dinas dan Transportasi Publik</option>
                                        <option value="Kendaraan Pribadi">Kendaraan Pribadi</option>
                                    </select>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="" class="form-label">Keterangan Mobilitas<span class="text-danger"><span class="text-secondary small"> (Khusus untuk Kendaraan Dinas)</span>*</span></label>
                                    <select class="form-select" aria-label=".form-select-sm example" name="keterangan_mobilitas" id="keteranganSelect">
                                        <option value="">Pilih Keterangan</option>
                                        <option value="Antar">Antar</option>
                                        <option value="Jemput">Jemput</option>
                                        <option value="Antar-Jemput">Antar-Jemput</option>
                                        <option value="Tidak Menggunakan Kendaraan Dinas">Tidak Menggunakan Kendaraan Dinas</option>
                                    </select>
                                </div>
                            </div>
                            <div class="btns-group d-grid gap-2 col-6 mx-auto pb-3 mt-5">
                                <button type="submit" class="btn btn-next btn-primary">Selanjutnya</button>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- end card --}}
            </div>
        </div>

    </div>
</section>
@endsection