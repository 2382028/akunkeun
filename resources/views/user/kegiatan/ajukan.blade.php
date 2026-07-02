@extends('user.templates.template')

@section('content')

<section id="beranda" class="pb-5 mt-5 pt-5">
    <div class="container">
        <div class="row text-secondary justify-content-center">
            <div class="col-md-10 mb-3">
                <div class="row">
                    <h3 class="fw-bold text-secondary">Pengajuan Kegiatan</h3>
                </div>
                <div class="card shadow rounded-0 border-0 p-4" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    <div class="card-body">
                        <div class="progressbar col-md-8 mx-auto mb-4">
                            <div class="progress" id="progress"></div>
                            <div class="progress-step progress-step-active" data-title="Informasi Kegiatan">1</div>
                            <div class="progress-step" data-title="Dokumen Kegiatan">2</div>
                        </div>
                        <form action="{{ url('/detailKegiatanajukan/' . $kegiatan->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-body">
                                    <h6 class="text-secondary mb-3">Informasi Kegiatan</h6>
                                    <div class="mb-1 row">
                                        <label for="jenis_program" class="col-md-4 col-form-label">Jenis Program<span class="text-danger">*</span></label>
                                        <div class="col-md-8">
                                            <select class="form-select" name="jenis_program" required>
                                                <option value="Fullday" {{ $kegiatan->jenis_program == 'Fullday' ? 'selected' : '' }}>Fullday</option>
                                                <option value="Halfday" {{ $kegiatan->jenis_program == 'Halfday' ? 'selected' : '' }}>Halfday</option>
                                                <option value="Fullboard" {{ $kegiatan->jenis_program == 'Fullboard' ? 'selected' : '' }}>Fullboard</option>
                                                <option value="Sharing Cost" {{ $kegiatan->jenis_program == 'Sharing Cost' ? 'selected' : '' }}>Sharing Cost</option>
                                                <option value="Penugasan" {{ $kegiatan->jenis_program == 'Penugasan' ? 'selected' : '' }}>Penugasan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Program Kerja -->
                                    <div class="mb-1 row">
                                        <label for="dropdown" class="col-md-4 col-form-label">Program Kerja <span class="text-danger">*</span></label>
                                        <div class="col-md-8">
                                            <select class="form-select select2" id="dropdown" name="program_kerja_id" required>
                                            @foreach ($ikuresult as $data)
                                                <option value="{{ $data['id'] }}"
                                                    data-kode-iku="{{ $data['kode_iku'] }}"
                                                    data-nama-proker="{{ $data['nama_program_kerja'] }}"
                                                    data-nama-ss="{{ $data['nama_ss'] }}"
                                                    data-label="{{ $data['nama_ss'] }} - {{ $data['nama_iku'] }}"
                                                    {{ $kegiatan->program_kerja == $data['nama_program_kerja'] ? 'selected' : '' }}>
                                                    {{$data['nama_program_kerja']}} 
                                                </option>
                                            @endforeach

                                            </select>
                                            <input type="hidden" id="id_iku_input" name="id_iku" value="{{ old('id_iku', $kegiatan->id_iku) }}">
                                            <input type="hidden" id="kode_iku_input" name="kode_iku" value="{{ old('kode_iku', $kegiatan->id_iku) }}">
                                            <input type="hidden" id="uraian_input" name="uraian" value="{{ old('uraian', $kegiatan->uraian) }}">
                                            <input type="hidden" id="proker_input" name="program_kerja" value="{{ old('program_kerja', $kegiatan->program_kerja) }}">

                                        </div>
                                    </div>
                                    
                                    <!-- Sasaran Strategis & Indikator -->
                                    <div class="mb-1 row">
                                        <label for="sasaran_strategis_input" class="col-md-4 col-form-label">Sasaran Strategis <span class="text-danger">*</span></label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="sasaran_strategis_input" name="sasaran_strategis" value="{{ $kegiatan->uraian }}" readonly>
                                        </div>
                                    </div>
                                    <div class="mb-1 row">
                                        <label for="indikator_kegiatan_input" class="col-md-4 col-form-label">Indikator Kegiatan <span class="text-danger">*</span></label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="indikator_kegiatan_input" name="indikator" value="{{ $indikator->nama_iku }}" readonly>
                                        </div>
                                    </div>

                                    <!-- Judul Kegiatan -->
                                    <div class="mb-1 row">
                                        <label for="nama_kegiatan" class="col-md-4 col-form-label">Judul Kegiatan <span class="text-danger">*</span></label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" id="nama_kegiatan" name="nama_kegiatan" required>{{ $kegiatan->nama_kegiatan }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Metode Kegiatan -->
                                    <div class="mb-1 row">
                                        <label class="col-md-4 col-form-label">Metode Kegiatan</label>
                                        <div class="col-md-8">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="jenis_kegiatan" id="jenis1" value="Luring" 
                                                    {{ $kegiatan->jenis_kegiatan == 'Luring' ? 'checked' : '' }}>
                                                <label for="jenis1" class="form-label">Luring</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="jenis_kegiatan" id="jenis2" value="Daring"
                                                    {{ $kegiatan->jenis_kegiatan == 'Daring' ? 'checked' : '' }}>
                                                <label for="jenis2" class="form-label">Daring</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="jenis_kegiatan" id="jenis3" value="Hybrid"
                                                    {{ $kegiatan->jenis_kegiatan == 'Hybrid' ? 'checked' : '' }}>
                                                <label for="jenis3" class="form-label">Hybrid</label>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Tanggal Kegiatan -->
                                    <div class="mb-1 row">
                                        <label class="col-md-4 col-form-label">Tanggal Kegiatan</label>
                                        <div class="col-md-4 mb-3">
                                            <label for="tgl_mulai" class="form-label">Mulai <span class="text-danger">*</span></label>
                                            <input type="datetime-local" name="tgl_mulai" class="form-control" value="{{ \Carbon\Carbon::parse($kegiatan->tgl_mulai)->format('Y-m-d\TH:i') }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="tgl_selesai" class="form-label">Selesai <span class="text-danger">*</span></label>
                                            <input type="datetime-local" name="tgl_selesai" class="form-control" value="{{ \Carbon\Carbon::parse($kegiatan->tgl_selesai)->format('Y-m-d\TH:i') }}"required>
                                        </div>
                                    </div>

                                    <!-- Lokasi Kegiatan -->
                                    <h6 class="text-secondary mb-3">Lokasi Kegiatan</h6>
                                    <div class="mb-1 row">
                                        <label for="tempat_kegiatan" class="col-md-4 col-form-label">Tempat Kegiatan <span class="text-danger">*</span></label>
                                        <div class="col-md-8">
                                            <select class="form-select" id="tempat_kegiatan" name="tempat_kegiatan" required>
                                                <option value="Hotel" {{ $kegiatan->tempat_kegiatan == 'Hotel' ? 'selected' : '' }}>Hotel</option>
                                                <option value="Diklat Jatinangor" {{ $kegiatan->tempat_kegiatan == 'Diklat Jatinangor' ? 'selected' : '' }}>Diklat Jatinangor</option>
                                                <option value="PTS" {{ $kegiatan->tempat_kegiatan == 'PTS' ? 'selected' : '' }}>PTS</option>
                                                <option value="Kantor LLDikti IV" {{ $kegiatan->tempat_kegiatan == 'Kantor LLDikti IV' ? 'selected' : '' }}>Kantor LLDikti IV</option>
                                                <option value="Lainnya" {{ $kegiatan->tempat_kegiatan == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            
                                                <!-- <option value="0" selected disabled>Pilih Tempat Kegiatan</option>
                                                <option value="Hotel">Hotel</option>
                                                <option value="Diklat Jatinangor">Diklat Jatinangor</option>
                                                <option value="PTS">PTS</option>
                                                <option value="Lainnya">Lainnya</option> -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-1 row">
                                        <label for="provinsi" class="col-md-4 col-form-label">Provinsi</label>
                                        <div class="col-md-8">
                                            <input type="text" name="provinsi" id="provinsi" class="form-control" value="{{ $kegiatan->provinsi }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-1 row">
                                        <label for="kab_kota" class="col-md-4 col-form-label">Kota/Kabupaten</label>
                                        <div class="col-md-8">
                                            <input type="text" name="kab_kota" id="kab_kota" class="form-control" value="{{ $kegiatan->kab_kota }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-1 row">
                                        <label for="alamat" class="col-md-4 col-form-label">Alamat <span class="text-danger">*</span></label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" id="alamat" name="alamat" required>{{ $kegiatan->alamat }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary px-4">Selanjutnya</button>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Mengambil elemen dari DOM
    const dropdown = document.getElementById('dropdown');
    const sasaranStrategis = document.getElementById('sasaran_strategis_input');
    const indikatorKegiatan = document.getElementById('indikator_kegiatan_input');
    const idIkuInput = document.getElementById('id_iku_input');
    const kodeIkuInput = document.getElementById('kode_iku_input');
    const uraianInput = document.getElementById('uraian_input');
    const prokerInput = document.getElementById('proker_input'); // Pastikan elemen ini ada di HTML

    // Inisialisasi Select2
    $(dropdown).select2();

    // Event listener saat dropdown berubah
    $(dropdown).on('change', function() {
        const selectedOption = $(this).find('option:selected');

        // Mengambil nilai dan data dari opsi yang dipilih
        const label = selectedOption.data('label');
        const idIku = selectedOption.val();

        // Memecah label menjadi sasaran strategis dan indikator kegiatan
        const labels = label.split(' - ');
        sasaranStrategis.value = labels[0] || ''; // Mengatur default jika kosong
        indikatorKegiatan.value = labels[1] || ''; // Mengatur default jika kosong
        idIkuInput.value = idIku || ''; // Mengatur default jika kosong

        // Mengambil data tambahan dari atribut data
        kodeIkuInput.value = selectedOption.data('kode-iku') || ''; // Pastikan elemen ini ada di HTML
        uraianInput.value = selectedOption.data('nama-ss') || ''; // Pastikan elemen ini ada di HTML
        prokerInput.value = selectedOption.data('nama-proker') || ''; // Pastikan elemen ini ada di HTML

        // Menampilkan informasi di konsol
        });
</script>


@endsection
