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
                <div class="card shadow rounded-0  border-0 p-3" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    <div class="card-body">
                        <!-- Progress bar -->
                        <div class="progressbar">
                            <div class="progress" id="progress"></div>

                            <div class="progress-step progress-step-active" data-title="Judul Program">1</div>
                            <div class="progress-step" data-title="Informasi Dasar">2</div>
                            <div class="progress-step" data-title="Informasi Orang">3</div>
                            <div class="progress-step show-notif hide-notif" data-title="Fasilitas">4</div>
                            <div class="progress-step show-notif hide-notif" data-title="Mobilitas">5</div>
                            <div class="progress-step show-notif hide-notif" data-title="Sarana & Prasarana">6</div>
                            <div class="progress-step show-notif hide-notif" data-title="Dokumen Pendukung">7</div>

                        </div>


                        <!-- Step 1 - Judul Program -->
                        <form action="{{url('/c_kegiatan')}}" method="post">
                            @csrf
                            <div class="mb-2">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <h6 class="text-secondary">Judul Program</span></h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="">Program Kerja <span class="text-danger">*</span></label>
                                        <select class="form-select" id="dropdown" name="uraian">
                                            <option value="0" selected>Pilih Program Kerja</option>
                                            @foreach ($ikuresult as $data)
                                            <option value="{{$data['nama_program_kerja']}}" data-label="{{ $data['nama_ss'] }} - {{ $data['nama_iku'] }}">
                                                {{$data['nama_program_kerja']}} - {{$data['nama_ss']}} - {{$data['nama_iku']}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="">Sasaran Strategis <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="sasaran_strategis_input" name="sasaran_strategis" readonly>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="">Indikator Kegiatan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="indikator_kegiatan_input" name="program_kerja" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="nama_kegiatan" class="form-label">Judul Kegiatan <span class="text-danger">*</span></label>
                                        <textarea class="form-control mt-1" id="nama_kegiatan" name="nama_kegiatan" required></textarea>
                                    </div>
                                </div>
                                <div class="btns-group d-grid gap-2 col-6 mx-auto pb-3 mt-5">
                                    <button type="submit" class="btn btn-next btn-primary" id="myBtn">Selanjutnya</button>
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
<script>
    const dropdown = document.getElementById('dropdown');
    const sasaranStrategis = document.getElementById('sasaran_strategis_input');
    const indikatorKegiatan = document.getElementById('indikator_kegiatan_input');

    $(dropdown).select2();

    $(dropdown).on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const label = selectedOption.data('label');

        const labels = label.split(' - ');
        sasaranStrategis.value = labels[0];
        indikatorKegiatan.value = labels[1];
    });
</script>
@endsection