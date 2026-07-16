@extends('user.templates.sidebar')

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
                        <form action="{{url('/perjadin/ajukan')}}" method="post" onsubmit="return validateSelect()">
                            @csrf
                            <div class="col-md-12">
                                <h6 class="text-secondary fw-bold mt-3">Informasi Dasar</h6><br>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-12">
                                    <label for="floatingTextarea">Judul Kegiatan (Sesuai dengan Surat Undangan)<span class="text-danger">*</span></label>
                                    <textarea class="form-control mt-1" id="floatingTextarea" name="nama_kegiatan" required>{{$perjadin->nama_kegiatan}}</textarea>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-12">
                                    <label for="" class="form-label">Pemberi Surat Undangan<span class="text-danger">*</span></label>
                                    <input type="text" name="pemberi_undangan" id="" class="form-control" value="{{$perjadin->pemberi_undangan}}" required> 
                                    <input type="hidden" name="idPerjadin" value="{{$perjadin->id}}"> 
                                </div>
                                </div>
                                <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="" class="form-label">Nomor Surat Undangan<span class="text-danger">*</span></label>
                                    <input type="text" name="no_undangan" id="no_undangan" class="form-control" value="{{$perjadin->no_undangan}}" required> 
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="" class="form-label">Tanggal Surat Undangan<span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_surat" id="tgl_surat" class="form-control" value="{{$tgl_surat}}" required>
                                </div>
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
                                    <input type="datetime-local" name="tgl_keberangkatan" id="tgl_keberangkatan" class="form-control" value="{{$perjadin->tgl_keberangkatan}}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="" class="form-label">Tanggal Mulai Acara<span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="tgl_mulai" id="tgl_mulai" class="form-control"  value="{{$perjadin->tgl_mulai}}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="" class="form-label">Tanggal Selesai Acara<span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="tgl_selesai" id="tgl_selesai" class="form-control"  value="{{$perjadin->tgl_selesai}}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="provinsi" class="form-label">Provinsi<span class="text-danger">*</span></label>
                                    <input type="text" id="provinsi" name="provinsi" class="form-control" style="text-transform: capitalize" placeholder="Masukkan Provinsi" value="{{$perjadin->provinsi}}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="" class="form-label">Kota/Kabupaten<span class="text-danger">*</span></label>
                                    <input type="text" name="kabupaten_kota" id="" class="form-control" style="text-transform: capitalize" value="{{$perjadin->kabupaten_kota}}" required> 
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="tempat_kegiatan" class="form-label">Tempat Kegiatan<span class="text-danger">*</span></label>
                                    <input type="text" name="tempat_kegiatan" id="tempat_kegiatan" class="form-control" value="{{ $tempat_kegiatan }}" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-12">
                                    <label for="floatingTextarea">Alamat<span class="text-danger">*</span></label>
                                    <textarea class="form-control mt-1" id="floatingTextarea" name="alamat" required>{{$alamat_detail }}</textarea>                                   
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="" class="form-label">Mobilitas<span class="text-danger">*</span></label>
                                    <select class="form-select required2" aria-label=".form-select-sm example" name="fasilitas_perjadin" id="mobilitasSelect">
                                        <option value="">Pilih Mobilitas</option>
                                        <option value="Kendaraan Dinas" {{ $perjadin->mobilitas == 'Kendaraan Dinas' ? 'selected' : '' }}>Kendaraan Dinas</option>
                                        <option value="Transportasi Publik" {{ $perjadin->mobilitas == 'Transportasi Publik' ? 'selected' : '' }}>Transportasi Publik</option>
                                        <option value="Kendaraan Dinas dan Transportasi Publik" {{ $perjadin->mobilitas == 'Kendaraan Dinas dan Transportasi Publik' ? 'selected' : '' }}>Kendaraan Dinas dan Transportasi Publik</option>
                                        <option value="Kendaraan Pribadi" {{ $perjadin->mobilitas == 'Kendaraan Pribadi' ? 'selected' : '' }}>Kendaraan Pribadi</option>
                                    </select>

                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="" class="form-label">Keterangan Mobilitas<span class="text-danger"><span class="text-secondary small"> (Khusus untuk Kendaraan Dinas)</span>*</span></label>
                                    <select class="form-select" aria-label=".form-select-sm example" name="keterangan_mobilitas" id="keteranganSelect" disabled>
                                        <option value="">Pilih Keterangan</option>
                                        @if($perjadin->keterangan_mobilitas)
                                            <option value="{{ $perjadin->keterangan_mobilitas }}" selected>{{ $perjadin->keterangan_mobilitas }}</option>
                                        @endif
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobilitasSelect = document.getElementById('mobilitasSelect');
        const keteranganSelect = document.getElementById('keteranganSelect');

        const optionsMapping = {
            'Kendaraan Dinas': ['Antar', 'Jemput', 'Antar-Jemput'],
            'Kendaraan Dinas dan Transportasi Publik': ['Antar', 'Jemput', 'Antar-Jemput'],
            'Transportasi Publik': ['Tidak Menggunakan Kendaraan Dinas'],
            'Kendaraan Pribadi': ['Tidak Menggunakan Kendaraan Dinas']
        };

        // Function to initialize the selects based on existing values
        function initializeSelects() {
            const existingMobilitas = mobilitasSelect.value;
            const existingKeterangan = keteranganSelect.value;

            if (existingMobilitas) {
                keteranganSelect.disabled = false; // Enable the keterangan dropdown
                const newOptions = optionsMapping[existingMobilitas] || [];
                newOptions.forEach(option => {
                    const optionElement = new Option(option, option);
                    keteranganSelect.appendChild(optionElement);
                });

                // Add a placeholder option but disable it
                const placeholderOption = new Option('Pilih Keterangan', '');
                placeholderOption.disabled = true;
                keteranganSelect.insertBefore(placeholderOption, keteranganSelect.firstChild);
                keteranganSelect.value = existingKeterangan || ''; // Set value to existing keterangan if available

                // Disable the "Pilih Mobilitas" option
                mobilitasSelect.querySelector('option[value=""]').disabled = true;
            }

            // Disable keteranganSelect if there is no existing value
            if (!existingKeterangan) {
                keteranganSelect.disabled = true; // Disable the keterangan dropdown
                keteranganSelect.appendChild(new Option('Pilih Keterangan', ''));
            }
        }

        // Call the initialize function on page load
        initializeSelects();

        mobilitasSelect.addEventListener('change', function() {
            const selectedMobilitas = mobilitasSelect.value;
            keteranganSelect.innerHTML = ''; // Clear existing options
            if (selectedMobilitas) {
                const newOptions = optionsMapping[selectedMobilitas] || [];
                keteranganSelect.disabled = false; // Enable the dropdown
                newOptions.forEach(option => {
                    const optionElement = new Option(option, option);
                    keteranganSelect.appendChild(optionElement);
                });
                // Add a placeholder option but disable it
                const placeholderOption = new Option('Pilih Keterangan', '');
                placeholderOption.disabled = true;
                keteranganSelect.insertBefore(placeholderOption, keteranganSelect.firstChild);
                keteranganSelect.value = ''; // Set the value to empty to select the placeholder

                // Disable the "Pilih Mobilitas" option
                mobilitasSelect.querySelector('option[value=""]').disabled = true;
            } else {
                keteranganSelect.disabled = true; // Disable the dropdown
                keteranganSelect.appendChild(new Option('Pilih Keterangan', ''));
            }
        });

        keteranganSelect.addEventListener('change', function() {
            if (keteranganSelect.value !== '') {
                keteranganSelect.querySelector('option[value=""]').disabled = true;
            }
        });
    });
</script>

