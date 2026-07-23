@extends('user.templates.sidebar')

@section('content')
<section class="pb-5 pt-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div>
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
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="floatingTextarea" class="form-label">Judul Kegiatan (Sesuai dengan Surat Undangan)<span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="floatingTextarea" name="nama_kegiatan" rows="2" required></textarea>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="" class="form-label">Pemberi Surat Undangan<span class="text-danger">*</span></label>
                                    <input type="text" name="pemberi_undangan" id="" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="" class="form-label">Nomor Surat Undangan<span class="text-danger">*</span></label>
                                    <input type="text" name="no_undangan" id="no_undangan" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="" class="form-label">Tanggal Surat Undangan<span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_surat" id="tgl_surat" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <label for="konfirmasi" class="form-label d-block">Apakah tanggal keberangkatan sama dengan tanggal mulai acara?</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="konfirmasi" id="ya" value="ya" checked>
                                        <label class="form-check-label" for="ya">Ya</label>
                                    </div>
                                    <div class="form-check form-check-inline">
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
                                    <label for="tempat_kegiatan" class="form-label">Tempat Kegiatan<span class="text-danger">*</span></label>
                                    <input type="text" name="tempat_kegiatan" id="tempat_kegiatan" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="floatingTextarea" class="form-label">Alamat<span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="floatingTextarea" name="alamat" rows="2" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="" class="form-label">Mobilitas<span class="text-danger">*</span></label>
                                    <select class="form-select required2" aria-label=".form-select-sm example" name="fasilitas_perjadin" id="mobilitasSelect">
                                        <option value="">Pilih Mobilitas</option>
                                        <option value="Kendaraan Dinas">Kendaraan Dinas</option>
                                        <option value="Transportasi Publik">Transportasi Publik</option>
                                        <option value="Kendaraan Dinas dan Transportasi Publik">Kendaraan Dinas dan Transportasi Publik</option>
                                        <option value="Kendaraan Pribadi">Kendaraan Pribadi</option>
                                    </select>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="" class="form-label">Keterangan Mobilitas<span class="text-danger"><span class="text-secondary small"> (Khusus untuk Kendaraan Dinas)</span>*</span></label>
                                    <select class="form-select" aria-label=".form-select-sm example" name="keterangan_mobilitas" id="keteranganSelect" disabled>
                                        <option value="">Pilih Keterangan</option>
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
    const keberangkatanInput = document.getElementById('tgl_keberangkatan');
    const mulaiInput = document.getElementById('tgl_mulai');
    const selesaiInput = document.getElementById('tgl_selesai');

    function validateTanggal() {
        const keberangkatan = new Date(keberangkatanInput.value);
        const mulai = new Date(mulaiInput.value);
        const selesai = new Date(selesaiInput.value);

        // Aturan 1: Keberangkatan ≤ Mulai
        if (mulai < keberangkatan) {
            alert('Tanggal Mulai tidak boleh lebih awal dari Tanggal Keberangkatan!');
            mulaiInput.value = keberangkatanInput.value;
        }

        // Aturan 2: Keberangkatan ≤ Selesai
        if (selesai < keberangkatan) {
            alert('Tanggal Selesai tidak boleh lebih awal dari Tanggal Keberangkatan!');
            selesaiInput.value = keberangkatanInput.value;
        }

        // Aturan 3: Mulai ≤ Selesai
        if (selesai < mulai) {
            alert('Tanggal Selesai tidak boleh lebih awal dari Tanggal Mulai!');
            selesaiInput.value = mulaiInput.value;
        }
    }

    // Jalankan saat salah satu tanggal diubah
    keberangkatanInput.addEventListener('change', validateTanggal);
    mulaiInput.addEventListener('change', validateTanggal);
    selesaiInput.addEventListener('change', validateTanggal);

    // Cek ulang saat submit form
    const form = keberangkatanInput.closest('form');
    form.addEventListener('submit', function(e) {
        const keberangkatan = new Date(keberangkatanInput.value);
        const mulai = new Date(mulaiInput.value);
        const selesai = new Date(selesaiInput.value);

        let errorMessage = '';

        if (mulai < keberangkatan) {
            errorMessage += '- Tanggal Mulai tidak boleh lebih awal dari Keberangkatan.\n';
        }
        if (selesai < keberangkatan) {
            errorMessage += '- Tanggal Selesai tidak boleh lebih awal dari Keberangkatan.\n';
        }
        if (selesai < mulai) {
            errorMessage += '- Tanggal Selesai tidak boleh lebih awal dari Tanggal Mulai.\n';
        }

        if (errorMessage !== '') {
            alert('Perbaiki data berikut sebelum menyimpan:\n' + errorMessage);
            e.preventDefault();
        }
    });
});
</script>


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
