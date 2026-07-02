@extends('admin.templates.sidebar')

@section('main-class', 'white-background')

@section('contain')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .white-background {
            background: white !important;
        }

        .default-option {
            color: gray;
        }

        .disabled-option {
            background-color: #e9ecef;
            color: gray;
        }
        .small-select {
            width: 150px;
        }
    </style>
</head>
<section class="mt-5">
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h3 class="fw-bold text-secondary">Pengajuan Penggunaan Kendaraan</h3>
                {{-- card --}}
                <div class="card p-3 shadow border-0 rounded-0 mt-3" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    <div class="card-body">
                        <!-- Step 1 -->
                        <form action="{{url('/bmn_mobilitas_only/store')}}" method="post" onsubmit="return validateSelect()">
                            @csrf
                            <div class="col-md-12">
                                <h6 class="text-secondary fw-bold mt-3">Informasi Dasar</h6><br>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <div class="col-md-9">
                                    <label for="perjadinSebelumnya">Ambil dari Perjadin yang ada<span id="perjadinSebelumnyaText" class="text-secondary small d-none"> (Refresh halaman untuk membatalkan)</span><span class="text-danger">*</span></label>
                                    <select class="form-select" id="perjadinSebelumnya" name="perjadinSebelumnya">
                                        <option value="" disabled selected class="default-option">Pilih Perjadin Sebelumnya</option>
                                        @foreach ($mobilitass as $mobilitas)
                                        <option value="{{$mobilitas->id}}"
                                        data-nama_kegiatan="{{$mobilitas->nama_kegiatan}}"
                                        data-surat_undangan="{{$mobilitas->surat_undangan}}"
                                        data-tgl_keberangkatan="{{$mobilitas->tgl_keberangkatan}}"
                                        data-tgl_selesai="{{$mobilitas->tgl_selesai}}"
                                        data-tgl_mulai2="{{$mobilitas->tgl_mulai}}"
                                        data-provinsi="{{$mobilitas->provinsi}}"
                                        data-kabupaten_kota="{{$mobilitas->kabupaten_kota}}"
                                        data-alamat="{{$mobilitas->alamat}}"
                                        >{{$mobilitas->nama_kegiatan}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="ket_mobilitas">Keterangan Mobilitas<span class="text-danger">*</span></label>
                                    <input type="hidden" id="suratUndangan" name="surat_undangan">
                                    <select class="form-select small-select" id="ket_mobilitas" name="ket_mobilitas">
                                        <option value="Antar">Antar</option>
                                        <option value="Jemput">Jemput</option>
                                        <option value="Antar-Jemput">Antar - Jemput</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-12">
                                    <label for="floatingTextarea">Judul Kegiatan<span class="text-danger">*</span></label>
                                    <textarea class="form-control mt-1" id="floatingTextarea" name="nama_kegiatan" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3" id="konfirmasiContainer">
                                    <label  for="konfirmasi" class="form-label">Apakah tanggal keberangkatan sama dengan tanggal selesai?</label>
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
                                    <label for="tgl_keberangkatan" class="form-label">Tanggal Keberangkatan<span class="text-danger">*</span></label>
                                    <input type="date" name="tgl_keberangkatan" id="tgl_keberangkatan" class="form-control" required>
                                    <input type="time" name="jam_keberangkatan" id="jam_keberangkatan" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="tgl_selesai" class="form-label">Tanggal Selesai<span class="text-danger">*</span></label>
                                    <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control" required>
                                    <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="tgl_mulai2" class="form-label">Tanggal Acara</label>
                                    <input type="date" name="tgl_mulai" id="tgl_mulai2" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="provinsi" class="form-label">Provinsi<span class="text-danger">*</span></label>
                                    <input type="text" id="provinsi" name="provinsi" class="form-control" style="text-transform: capitalize" placeholder="Masukkan Provinsi" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="kabupaten_kota" class="form-label">Kota/Kabupaten<span class="text-danger">*</span></label>
                                    <input type="text" name="kabupaten_kota" id="kabupaten_kota" class="form-control" style="text-transform: capitalize" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="desa_kecamatan" class="form-label">Desa/Kecamatan</label>
                                    <input type="text" name="desa_kecamatan" id="desa_kecamatan" class="form-control">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-12">
                                    <label for="alamat">Alamat<span class="text-danger">*</span></label>
                                    <textarea class="form-control mt-1" id="alamat" name="alamat" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="pengemudi" class="form-label">Pengemudi<span class="text-danger">*</span></label>
                                    <select class="form-select required2" aria-label=".form-select-sm example" name="pengemudi" id="pengemudiSelect" disabled>
                                        <!-- Pilihan pengemudi akan diisi setelah cek mobilitas -->
                                    </select>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="kendaraan" class="form-label">Kendaraan<span id="kendaraanDinasText" class="text-secondary small d-none"> (Khusus untuk Kendaraan Dinas)</span><span class="text-danger">*</span></label>
                                    <select class="form-select" aria-label=".form-select-sm example" name="kendaraan" id="kendaraanSelect" disabled>
                                        <!-- Pilihan kendaraan akan diisi setelah cek mobilitas -->
                                    </select>
                                </div>
                            </div>
                            <!-- Tombol "Proses ke HKT" -->
                            <div class="d-flex justify-content-between pb-3 mt-5">
                                <!-- Tombol "Cek Mobilitas" di luar form, tetapi dalam flex row -->
                                <button id="cekMobilitasBtn" class="btn btn-warning text-white col-3" disabled>Cek Mobilitas</button>
                                
                                <!-- Tombol "Proses ke HKT" tetap di dalam form -->
                                <button id="prosesHKTBtn" type="submit" class="btn btn-primary col-3 " disabled>Proses ke HKT</button>
                            </div>
                        </form>
                        
                    </div>
                </div>
                {{-- end card --}}
            </div>
        </div>

    </div>
</section>

<!-- Tambahkan script jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
    const perjadinSelect = $('#perjadinSebelumnya');
    const konfirmasiContainer = $('#konfirmasiContainer');

    // Fungsi untuk mengatur visibilitas konfirmasi
    function toggleKonfirmasi() {
        if (perjadinSelect.val()) {
            konfirmasiContainer.hide(); // Sembunyikan jika ada value
            $('#tidak').prop('checked', true); 
        } else {
            konfirmasiContainer.show(); // Tampilkan jika tidak ada value
        }
    }

    // Panggil fungsi saat halaman dimuat dan saat nilai select berubah
    toggleKonfirmasi();
    perjadinSelect.on('change', toggleKonfirmasi);
});

</script>

<script>
      function toggleCekMobilitas() {
        const tanggalKeberangkatan = $('#tgl_keberangkatan');
        const tanggalSelesai = $('#tgl_selesai');
        const cekMobilitasBtn = $('#cekMobilitasBtn');

        if (tanggalKeberangkatan.val() && tanggalSelesai.val()) {
            cekMobilitasBtn.prop('disabled', false);
        } else {
            cekMobilitasBtn.prop('disabled', true);
        }
    }

    $(document).ready(function() {
    // Ambil elemen yang dibutuhkan
    var tanggalKeberangkatan = $('#tgl_keberangkatan');
    var tanggalSelesai = $('#tgl_selesai');
    var cekMobilitasBtn = $('#cekMobilitasBtn');
    var prosesHKTBtn = $('#prosesHKTBtn');
    var kendaraanSelect = $('#kendaraanSelect');
    var pengemudiSelect = $('#pengemudiSelect');

    toggleCekMobilitas();

    // Disable tombol Cek Mobilitas sampai tanggal diisi
    // function toggleCekMobilitas() {
    //     if (tanggalKeberangkatan.val() && tanggalSelesai.val()) {
    //         cekMobilitasBtn.prop('disabled', false);
    //     } else {
    //         cekMobilitasBtn.prop('disabled', true);
    //     }
    // }

    // Disable tombol Proses ke HKT sampai mobilitas dicek
    function toggleProsesHKT() {
        if (kendaraanSelect.val() && pengemudiSelect.val()) {
            prosesHKTBtn.prop('disabled', false);
        } else {
            prosesHKTBtn.prop('disabled', true);
        }
    }

    // Aktifkan Cek Mobilitas jika tanggal keberangkatan dan selesai diisi
    tanggalKeberangkatan.on('change', toggleCekMobilitas);
    tanggalSelesai.on('change', toggleCekMobilitas);

    // Tombol Cek Mobilitas event handler
    cekMobilitasBtn.on('click', function(event) {
         // Mencegah pengiriman form
        event.preventDefault();

        var tglKeberangkatan = tanggalKeberangkatan.val();
        var tglSelesai = tanggalSelesai.val();

        $.ajax({
            url: '/api/cek-mobilitas',
            type: 'GET',
            data: {
                tanggal_awal: tglKeberangkatan,
                tanggal_akhir: tglSelesai
            },
            success: function(response) {
                // Kosongkan pilihan yang ada sebelumnya
                kendaraanSelect.empty();
                pengemudiSelect.empty();

                // Tambahkan kendaraan yang tersedia
                if (response.kendaraans.length > 0) {
                    $.each(response.kendaraans, function(index, kendaraan) {
                        kendaraanSelect.append('<option value="' + kendaraan.id + '">' + kendaraan.merek + ' [' + kendaraan.no_polisi + ']</option>');
                    });
                } else {
                    kendaraanSelect.append('<option value="">Tidak ada kendaraan yang tersedia</option>');
                }

                // Tambahkan pengemudi yang tersedia
                if (response.pengemudis.length > 0) {
                    $.each(response.pengemudis, function(index, pengemudi) {
                        pengemudiSelect.append('<option value="' + pengemudi.id + '">' + pengemudi.nama_lengkap + '</option>');
                    });
                } else {
                    pengemudiSelect.append('<option value="">Tidak ada pengemudi yang tersedia</option>');
                }

                // Enable select option setelah data berhasil diambil
                kendaraanSelect.prop('disabled', false);
                pengemudiSelect.prop('disabled', false);

                // Aktifkan tombol Proses ke HKT jika pilihan tersedia
                toggleProsesHKT();
            },
            error: function(xhr) {
                alert('Terjadi kesalahan, silakan coba lagi.');
            }
        });
    });

    // Aktifkan tombol Proses ke HKT jika kendaraan dan pengemudi dipilih
    kendaraanSelect.on('change', toggleProsesHKT);
    pengemudiSelect.on('change', toggleProsesHKT);
});

</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const perjadinSebelumnya = document.getElementById('perjadinSebelumnya');
    const suratUndangan = document.getElementById('suratUndangan');
    const judulKegiatan = document.getElementById('floatingTextarea');
    const tglKeberangkatan = document.getElementById('tgl_keberangkatan');
    const jamKeberangkatan = document.getElementById('jam_keberangkatan');
    const tglSelesai = document.getElementById('tgl_selesai');
    const jamSelesai = document.getElementById('jam_selesai');
    const tglMulai = document.getElementById('tgl_mulai2');
    const provinsi = document.getElementById('provinsi');
    const kabupatenKota = document.getElementById('kabupaten_kota');
    const alamat = document.getElementById('alamat');
    const desaKecamatan = document.getElementById('desa_kecamatan');
    const perjadinSebelumnyaText = document.getElementById('perjadinSebelumnyaText');
    const radioButtons = document.getElementsByName('konfirmasi');


    function convertDateFormat(dateStr) {
        if (!dateStr) return '';
        const [year, month, day] = dateStr.split('-');
        return `${year}-${month}-${day}`;
    }

    function splitDateTime(datetime) {
        if (!datetime) return { date: '', time: '' };
        const [date, time] = datetime.split(' ');
        return { date: convertDateFormat(date), time: time };
    }

    function checkDefaultOption() {
        const selectedOption = perjadinSebelumnya.options[perjadinSebelumnya.selectedIndex];

        if (perjadinSebelumnya.value === "") {
            perjadinSebelumnya.disabled =false;

            perjadinSebelumnya.classList.add('disabled-option');
            perjadinSebelumnyaText.classList.add('d-none');
            kendaraanDinasText.classList.remove('d-none');
        } else {
            perjadinSebelumnya.disabled =true;

             perjadinSebelumnya.classList.add('disabled-option');
            perjadinSebelumnyaText.classList.remove('d-none');
            kendaraanDinasText.classList.remove('d-none');

            const tglKeberangkatanData = splitDateTime(selectedOption.getAttribute('data-tgl_keberangkatan'));
            const tglSelesaiData = splitDateTime(selectedOption.getAttribute('data-tgl_selesai'));
            const tglMulaiData = splitDateTime(selectedOption.getAttribute('data-tgl_mulai2'));

            console.log('Keberangkatan Data:', tglKeberangkatanData);
            console.log('Selesai Data:', tglSelesaiData);
            console.log('Mulai Data:', tglMulaiData);

            judulKegiatan.value = selectedOption.getAttribute('data-nama_kegiatan');
            tglKeberangkatan.value = tglKeberangkatanData.date;
            jamKeberangkatan.value = tglKeberangkatanData.time || '';
            tglSelesai.value = tglSelesaiData.date;
            jamSelesai.value = tglSelesaiData.time || '';
            tglMulai.value = tglMulaiData.date;
            provinsi.value = selectedOption.getAttribute('data-provinsi');
            kabupatenKota.value = selectedOption.getAttribute('data-kabupaten_kota');
            alamat.value = selectedOption.getAttribute('data-alamat');
            suratUndangan.value = selectedOption.getAttribute('data-surat_undangan');

            console.log('Surat Undangan:', suratUndangan.value);

            toggleCekMobilitas();
        }
    }
    
    perjadinSebelumnya.addEventListener('change', checkDefaultOption);
    checkDefaultOption();
});

</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');

        form.addEventListener('submit', function (event) {
            const tglKeberangkatan = document.getElementById('tgl_keberangkatan').value;
            const jamKeberangkatan = document.getElementById('jam_keberangkatan').value;
            const tglSelesai = document.getElementById('tgl_selesai').value;
            const jamSelesai = document.getElementById('jam_selesai').value;

            if (!tglKeberangkatan || !jamKeberangkatan || !tglSelesai || !jamSelesai) {
                alert('Semua field tanggal dan waktu harus diisi.');
                event.preventDefault();
                return false;
            }

            const keberangkatan = new Date(`${tglKeberangkatan}T${jamKeberangkatan}`);
            const selesai = new Date(`${tglSelesai}T${jamSelesai}`);

            if (isNaN(keberangkatan.getTime()) || isNaN(selesai.getTime())) {
                alert('Format tanggal atau waktu tidak valid.');
                event.preventDefault();
                return false;
            }

            return true;
        });
    });
    </script>
<script>
    var konfirmasiRadio = document.querySelectorAll('input[name="konfirmasi"]');
    var tglKeberangkatanInput = document.getElementById('tgl_keberangkatan');
    var tglSelesaiInput = document.getElementById('tgl_selesai');
    var tidakRadio = document.getElementById('tidak');

    // Tambahkan event listener untuk setiap radio button
    konfirmasiRadio.forEach(function (radio) {
        radio.addEventListener('change', function () {
            if (radio.value === 'ya') {
                // Jika user memilih 'Ya', salin nilai dari satu input ke input yang lain
                tglSelesaiInput.value = tglKeberangkatanInput.value;
            }
        });
    });

    // Tambahkan event listener untuk input tanggal keberangkatan
    tglKeberangkatanInput.addEventListener('change', function () {
        if (document.querySelector('input[name="konfirmasi"]:checked').value === 'ya') {
            // Jika user memilih 'Ya', salin nilai tanggal keberangkatan ke tanggal mulai acara
            tglSelesaiInput.value = tglKeberangkatanInput.value;
        }
    });

    // Tambahkan event listener untuk input tanggal mulai acara
    tglSelesaiInput.addEventListener('change', function () {
        if (document.querySelector('input[name="konfirmasi"]:checked').value === 'ya') {
            // Jika user memilih 'Ya', salin nilai tanggal mulai acara ke tanggal keberangkatan
            tglKeberangkatanInput.value = tglSelesaiInput.value;
        }
    });

    // Tambahkan event listener untuk radio button 'Tidak'
    tidakRadio.addEventListener('change', function () {
        if (tidakRadio.checked) {
            // Jika user memilih 'Tidak', hapus nilai dari kedua input tanggal
            tglKeberangkatanInput.value = '';
            tglSelesaiInput     .value = '';
        }
    });
</script>


