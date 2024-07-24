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
<section class="mb-5">
    <div class="container">
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
                                    <select class="form-select small-select" id="ket_mobilitas" name="ket_mobilitas">
                                        <option value="Antar">Antar</option>
                                        <option value="Jemput">Jemput</option>
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
                                <div class="col-md-12 mb-3">
                                    <label for="konfirmasi" class="form-label">Apakah tanggal keberangkatan sama dengan tanggal selesai?</label>
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
                                    <select class="form-select required2" aria-label=".form-select-sm example" name="pengemudi">
                                        @foreach ($pengemudis as $pengemudi)
                                            <option value="{{$pengemudi->id}}">{{$pengemudi->nama_lengkap}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="kendaraan" class="form-label">Kendaraan<span id="kendaraanDinasText" class="text-secondary small d-none"> (Khusus untuk Kendaraan Dinas)</span><span class="text-danger">*</span></label>
                                    <select class="form-select" aria-label=".form-select-sm example" name="kendaraan" id="kendaraanSelect">
                                        @foreach ($kendaraans as $kendaraan)
                                            <option value="{{$kendaraan->id}}">{{$kendaraan->merek}} [{{$kendaraan->no_polisi}}]</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="btns-group d-grid gap-2 col-6 mx-auto pb-3 mt-5">
                                <button type="submit" class="btn btn-next btn-primary">Proses ke HKT</button>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- end card --}}
            </div>
        </div>

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const perjadinSebelumnya = document.getElementById('perjadinSebelumnya');
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
            judulKegiatan.disabled = false;
            tglKeberangkatan.disabled = false;
            jamKeberangkatan.disabled = false;
            tglSelesai.disabled = false;
            jamSelesai.disabled = false;
            tglMulai.disabled = false;
            provinsi.disabled = false;
            kabupatenKota.disabled = false;
            alamat.disabled = false;
            desaKecamatan.disabled = false;
            radioButtons.forEach(rb => rb.disabled = false);

            perjadinSebelumnya.classList.add('disabled-option');
            perjadinSebelumnyaText.classList.add('d-none');
            kendaraanDinasText.classList.remove('d-none');
        } else {
            judulKegiatan.disabled = true;
            // tglKeberangkatan.disabled = true;
            // jamKeberangkatan.disabled = true;
            // tglSelesai.disabled = true;
            // jamSelesai.disabled = true;
            tglMulai.disabled = true;
            provinsi.disabled = true;
            kabupatenKota.disabled = true;
            alamat.disabled = true;
            desaKecamatan.disabled = true;

             // Disable radio buttons
             radioButtons.forEach(rb => rb.disabled = true);

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


