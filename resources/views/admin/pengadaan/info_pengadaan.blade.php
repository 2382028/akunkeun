@extends('admin.templates.sidebar')
<style>
/* CSS */
.progressbar {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin-bottom: 30px;
}

.progress {
    position: absolute;
    top: 50%;
    left: 0;
    width: 100%;
    height: 5px;
    background-color: #e0e0e0;
    transform: translateY(-50%);
    z-index: 0;
    border-radius: 5px;
}

.progress::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 33%; /* Ini untuk langkah pertama, sesuaikan nilai sesuai progres (0%, 33%, 66%, 100%) */
    height: 100%;
    background-color: #007bff;
    transition: width 0.4s ease;
    border-radius: 5px;
}

.progress-step {
    position: relative;
    width: 40px;
    height: 40px;
    background-color: #f0f0f0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    z-index: 1;
    transition: background-color 0.3s ease;
}

.progress-step::before {
    content: attr(data-title);
    position: absolute;
    top: -30px;
    font-size: 12px;
    font-weight: 600;
    color: #6c757d;
    white-space: nowrap;
}

.progress-step-active {
    background-color: #007bff;
    color: #fff;
    transition: background-color 0.3s ease;
}

.progress-step-active ~ .progress-step {
    background-color: #f0f0f0;
}

.progress-step:not(.progress-step-active):hover {
    background-color: #007bff;
    color: #fff;
}


</style>
@section('contain')
<div class="container-fluid px-4 py-4">
    <div class="row">
        <div class="col-md-12">
            <h4>Dokumen Pengadaan / <span class="fw-bold">Daftar Pengadaan / Info Pengadaan</span></h4>
        </div>
    </div>
    <div id="step1" class="col-md-12 mb-3">
    <div class="card shadow rounded-0 border-0 p-3" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
        <div class="card-body px-4 py-4">
           <!-- Progress bar -->
            <div class="progressbar col-md-10 mx-auto">
                <div class="progress" id="progress"></div>
                <div class="progress-step progress-step-active" data-title="Penawaran dan Data Kualifikasi">1</div>
                <div class="progress-step" data-title="Jadwal">2</div>
                <div class="progress-step" data-title="Finalisasi">3</div>
            </div>
        </div>
       
        <div class="mb-3 col-md-10 mx-auto">
            <div class="row">
                <div class="col-12 col-md-2 mt-2 mt-2">
                    <label for="nama" class="form-label">Kode Dokumen Pengadaan</label>
                </div>
                <div class="col-12 col-md-10 mt-2"></div>
            </div>
            <div class="shadow-sm" style="height: 2px; background-color: #ccc;"></div>
            <div class="row">
                <div class="col-12 col-md-2 mt-2">
                    <label for="nama" class="form-label">Tanggal Dokumen</label>
                </div>
                <div class="col-12 col-md-10 mt-2"></div>
            </div>
            <div class="shadow-sm" style="height: 2px; background-color: #ccc;"></div>
            <div class="row">
                <div class="col-12 col-md-2 mt-2">
                    <label for="nama" class="form-label">Nama Pengadaan</label>
                </div>
                <div class="col-12 col-md-10 mt-2"></div>
            </div>
            <div class="shadow-sm" style="height: 2px; background-color: #ccc;"></div>
            <div class="row">
                <div class="col-12 col-md-2 mt-2">
                    <label for="nama" class="form-label">Status Kerja</label>
                </div>
                <div class="col-12 col-md-10 mt-2"></div>
            </div>
            <div class="shadow-sm" style="height: 2px; background-color: #ccc;"></div>
            <div class="row">
                <div class="col-12 col-md-2 mt-2">
                    <label for="nama" class="form-label">Metode Pengadaan</label>
                </div>
                <div class="col-12 col-md-10 mt-2"></div>
            </div>
            <div class="shadow-sm" style="height: 2px; background-color: #ccc;"></div>
            <div class="row">
                <div class="col-12 col-md-2 mt-2">
                    <label for="nama" class="form-label">Tahun Anggaran</label>
                </div>
                <div class="col-12 col-md-10 mt-2"></div>
            </div>
            <div class="shadow-sm" style="height: 2px; background-color: #ccc;"></div>
            <div class="row">
                <div class="col-12 col-md-2 mt-2">
                    <label for="nama" class="form-label">Pejabat Pengadaan</label>
                </div>
                <div class="col-12 col-md-10 mt-2"></div>
            </div>
            <div class="shadow-sm" style="height: 2px; background-color: #ccc;"></div>
            <div class="row">
                <div class="col-12 col-md-2 mt-2">
                    <label for="nama" class="form-label">Nilai HPS</label>
                </div>
                <div class="col-12 col-md-10 mt-2"></div>
            </div>
            <div class="shadow-sm" style="height: 2px; background-color: #ccc;"></div>
            <div class="row">
                <div class="col-12 col-md-2 mt-2">
                    <label for="nama" class="form-label">Jenis Kontrak</label>
                </div>
                <div class="col-12 col-md-10 mt-2"></div>
            </div>
            <div class="shadow-sm" style="height: 2px; background-color: #ccc;"></div>
            <div class="row">
                <div class="col-12 col-md-2 mt-2">
                    <label for="nama" class="form-label">Lokasi Pekerjaan</label>
                </div>
                <div class="col-12 col-md-10 mt-2"></div>
            </div>
            <div class="shadow-sm" style="height: 2px; background-color: #ccc;"></div>
            <div class="row">
                <div class="col-12 col-md-2 mt-2">
                    <label for="nama" class="form-label">Terakhir Pekerjaan</label>
                </div>
                <div class="col-12 col-md-10 mt-2"></div>
            </div>
            <div class="shadow-sm" style="height: 2px; background-color: #ccc;"></div>
            <div class="row">
                <div class="col-12 col-md-2 mt-2">
                    <label for="nama" class="form-label">Terakhir Pendaftaran</label>
                </div>
                <div class="col-12 col-md-10 mt-2">bauabu bauaauua bauauuauauaua babaahaghaha abbababababababa abababbababababab abbabababababa bagagagaggaga aavavavva avvavavavav avvavavavav abbabababa </div>
            </div>
            <div class="shadow-sm" style="height: 2px; background-color: #ccc;"></div>
            <div class="row">
                <div class="col-12 col-md-2 mt-2">
                    <label for="nama" class="form-label">Intruksi Kepala Penyedia</label>
                </div>
                <div class="col-12 col-md-10 mt-2"></div>
            </div>
            <div class="shadow-sm" style="height: 2px; background-color: #ccc;"></div>
            <div class="row">
                <div class="col-12 col-md-2 mt-2 ">
                    <label for="nama" class="form-label">BAPP</label>
                </div>
                <div class="col-12 col-md-2 mt-2">
                    <button type="button" id="toggleButton" class="btn btn-light d-flex align-items-center" style="background-color: transparent; border: 1px solid #000000;">
                        <i class="fas fa-pen-to-square me-2"></i>
                        <span id="bappStatus">Belum Buat</span>
                    </button>
                </div>
                </div>
            </div>
            <div class="shadow-sm" style="height: 2px; background-color: #ccc;"></div>
    </div>
            <div class="col-12 col-md-2 mt-2">
                <button type="button" id="nextStepButton" class="btn btn-success d-none">Next Step</button>
            </div>
            <div id="step1" class="col-md-12 mb-3">
                <div class="container-fluid px-4 py-4">
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Dokumen Pengadaan / <span class="fw-bold">BAPPDP & BAEDP</span></h4>
                        </div>
                    </div>
                    <div class="card shadow rounded-0 border-0 p-3" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                        <div class="card-body px-4 py-4">
                            <!-- Progress bar -->
                            <div class="progressbar col-md-10 mx-auto">
                                <div class="progress" id="progress"></div>
                                <div class="progress-step progress-step-active" data-title="Dokumen Pengadaan">1</div>
                                <div class="progress-step progress-step-active" data-title="BAPPDP & BAEDP">2</div>
                                <div class="progress-step" data-title="BANK & BAHP">3</div>
                            </div>
                        </div>                       
                        <div class="mb-3 col-md-10 mx-auto">
                            <h5>Data Administrasi</h5>
                            <div class="shadow-sm" style="height: 2px; background-color: #ccc;"></div>
                                <div class="row mt-3">
                                    <div class="col-12 col-md-3 mb-2 ">Nama Badan Usaha</div>
                                    <div class="col-12 col-md-9 mb-2">
                                        <input type="" name="" id="" class="form-control bg-light" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-3 mb-2">Nama Badan Usaha</div>
                                    <div class="col-12 col-md-9 mb-2">
                                        <input type="" name="" id="" class="form-control bg-light" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-3 mb-2">Status</div>
                                    <div class="col-12 col-md-9 mb-2">
                                        <input type="" name="" id="" class="form-control bg-light" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-3 mb-2">Alamat Kantor Pusat</div>
                                    <div class="col-12 col-md-9 mb-2">
                                        <input type="" name="" id="" class="form-control bg-light" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-3 mb-2">NO. Telepon/Fax</div>
                                    <div class="col-12 col-md-9 mb-2">
                                        <input type="" name="" id="" class="form-control bg-light" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-3 mb-2">E-Mail</div>
                                    <div class="col-12 col-md-9 mb-2">
                                        <input type="" name="" id="" class="form-control bg-light" required>
                                    </div>
                                </div>
                        </div>
                
                        <!-- Data Penawaran -->
                        <div class="mb-3 col-md-10 mx-auto">
                            <h5>Data Penawaran</h5>
                            <div class="shadow-sm" style="height: 2px; background-color: #ccc;"></div>
                            <table class="table table-bordered table-custom mt-3">
                                <thead>
                                    <tr>
                                        <th>Uraian</th>
                                        <th>Volume</th>
                                        <th>Jumlah</th>
                                        <th>Satuan</th>
                                        <th>Harga Satuan</th>
                                        <th>Jumlah Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            Paket Fullboard (30-31/05/2023)
                                            <ul>
                                                <li>Akomodasi/Penginapan</li>
                                                <li>Makan Pagi (1 kali)</li>
                                                <li>Makan Siang (1 kali)</li>
                                                <li>Makan Malam (1 kali)</li>
                                                <li>Rehat Coffee dan Snack (2 kali)</li>
                                                <li>Ruang Pertemuan dan Fasilitasnya</li>
                                            </ul>
                                        </td>
                                        <td>150</td>
                                        <td>1</td>
                                        <td>Pax</td>
                                        <td>Rp. 800.000</td>
                                        <td>Rp. 120.000.000</td>
                                    </tr>
                                </tbody>
                            </table>
                
                            <!-- Summary -->
                            <div class="row">
                                <div class="col-md-10 text-end">
                                    <p class="summary">Jumlah:</p>
                                    <p class="summary">PPN:</p>
                                    <p class="summary">Jumlah Total:</p>
                                    <p class="summary terbilang">Terbilang Total:</p>
                                </div>
                                <div class="col-md-2 text-end">
                                    <p class="summary-value">Rp. 120.000.000</p>
                                    <p class="summary-value">Rp -</p>
                                    <p class="summary-value">Rp. 120.000.000</p>
                                    <p class="summary-value terbilang">Seratus dua puluh juta rupiah</p>
                                </div>
                            </div>
                
                            <!-- Tombol Selanjutnya -->
                            <button type="button" id="nextStepButton" class="btn btn-success">Selanjutnya</button>
                        </div>
                    </div>
                </div>
                
        </div>
    </div>
</div>

<div class="modal fade" id="pengadaanModal" tabindex="-1" aria-labelledby="pengadaanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pengadaanModalLabel">Berita Acara Penjelasan Pekerjaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form data pengadaan -->
                <form id="bappForm">
                    <div class="mb-3 row">
                        <label for="nomor" class="col-sm-4 col-form-label">Nomor</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="nomor" placeholder="Masukkan nomor" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="tanggalSurat" class="col-sm-4 col-form-label">Tanggal Surat</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="tanggalSurat" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="caraPenyampaian" class="col-sm-4 col-form-label">Cara Penyampaian Dokumen Penawaran</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="caraPenyampaian" placeholder="Masukkan cara penyampaian" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="kelengkapan" class="col-sm-4 col-form-label">Kelengkapan yang Harus Dilampirkan</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="kelengkapan" placeholder="Masukkan kelengkapan" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="metodeEvaluasi" class="col-sm-4 col-form-label">Metode Evaluasi</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="metodeEvaluasi" placeholder="Masukkan metode evaluasi" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="halMenggugurkan" class="col-sm-4 col-form-label">Hal-hal yang Menggugurkan Penawaran</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="halMenggugurkan" placeholder="Masukkan hal-hal" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="jenisKontrak" class="col-sm-4 col-form-label">Jenis Kontrak</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="jenisKontrak" placeholder="Masukkan jenis kontrak" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="namaPerwakilan" class="col-sm-4 col-form-label">Nama Perwakilan dari Penyedia</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="namaPerwakilan" placeholder="Masukkan nama perwakilan" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="jabatan" class="col-sm-4 col-form-label">Jabatan</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="jabatan" placeholder="Masukkan jabatan" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                
                <button type="button" class="btn btn-primary" id="saveBapp">Simpan</button>
                <button type="button" class="btn btn-success" id="ubahBapp">Ubah</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal kedua untuk menampilkan dokumen BAPP yang baru dibuat -->
<div class="modal fade" id="bappDokumenModal" tabindex="-1" aria-labelledby="bappDokumenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bappDokumenModalLabel">Berita Acara Penjelasan Pekerjaan (BAPP)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                <h6 class="fw-bold text-center">PENGADAAN LANGSUNG PENGADAAN JASA AKOMODASI DAN KONSUMSI KEGIATAN WORKSHOP</h6>
                    </div>
                <div class="col-md-6">
                        <h6 class="fw-bold text-center">BERITA ACARA PENJELASAN PEKERJAAN (BAPP) / AANWIJING</h6>
                        <p class="mb-4">Nomor: 661/LL4/LL/2023<br>Tanggal: 17 Mei 2023</p>
                    </div>
                    <div>
                        <p> Pada hari Rabu tanggal tujuh belas bulan Mei tahun dua ribu dua puluh tiga dengan mengambil tempat di
                            Kantor LLDIKTI Wilayah IV, telah mengadakan Rapat Penjelasan Pekerjaan/Aanwijing Pengadaan Langsung
                            Pengadaan Jasa Akomodasi dan Konsumsi Kegiatan Workshop Penyusunan Dokumen Kerja Sama dan Teknis
                            Pelaporan Kerja Sama Perguruan Tinggi pada laman laporankerma.go.id. Tahun 2023.</p>

                        <p> Rapat Penjelasan Pekerjaan/Aanwijing ini dihadiri oleh:</p>
                        <ul>
                            <li>Pejabat Pengadaan Barang/Jasa: Hevy Pratiwi</li>
                            <li>Penyedia Barang/Jasa: PT. Para Bandung Properindo/Ibis Bandung Trans Studio Hotel diwakili oleh Indra Gunawan</li>
                        </ul>

                        <p>Pokok-pokok yang dijelaskan bersama adalah sebagai berikut:</p>

                        <div class="table-responsive">
                            <table class="table table-bordered" style="width: 100%">
                              <thead>
                                <tr class="text-center small">
                                    <th>No</th>
                                    <th>Kriteria Penjelasan</th>
                                    <th>Uraian Penjelasan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center small">
                                    <td>1</td>
                                    <td>Lingkup Pekerjaan</td>
                                    <td>Pengadaan Barang/Jasa</td>
                                </tr>
                                <tr class="text-center small">
                                    <td>2</td>
                                    <td>Harga Perkiraan Sendiri (HPS)</td>
                                    <td>Rp. 120.000.000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveBappDokumen">Simpan</button>
                <button type="button" class="btn btn-success" id="ubahBapp">Ubah</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Variabel untuk menyimpan status apakah BAPP sudah dibuat atau belum
let bappSudahDibuat = false;  // Set ke true jika BAPP sudah dibuat, false jika belum
let bappDokumenDitampilkan = false;  // Status apakah dokumen sudah ditampilkan

// Fungsi untuk membuka modal jika BAPP belum dibuat
function cekStatusBapp() {
    if (!bappSudahDibuat) {
        // Buka modal jika BAPP belum dibuat
        var myModal = new bootstrap.Modal(document.getElementById('pengadaanModal'));
        myModal.show();
    }
}

// Panggil fungsi untuk cek status saat halaman dimuat
window.onload = cekStatusBapp;

// Event listener untuk tombol "Simpan"
document.getElementById('saveBapp').addEventListener('click', function() {
    // Validasi form sebelum menyimpan
    let form = document.getElementById('bappForm');
    if (form.checkValidity()) {
        // Jika form valid, ubah status BAPP menjadi sudah dibuat
        bappSudahDibuat = true;
        document.getElementById('bappStatus').textContent = "Sudah Dibuat";
        document.getElementById('bappStatus').style.color = "#007bff";  // Ubah warna status jadi biru

        // Tutup modal pengadaan BAPP setelah disimpan
        var myModalEl = document.getElementById('pengadaanModal');
        var modal = bootstrap.Modal.getInstance(myModalEl);
        modal.hide();

        // Tampilkan modal dokumen BAPP
        var bappDokumenModal = new bootstrap.Modal(document.getElementById('bappDokumenModal'));
        bappDokumenModal.show();
    } else {
        // Jika form tidak valid, munculkan pesan validasi HTML5
        form.reportValidity();
    }
});

document.getElementById('saveBappDokumen').addEventListener('click', function() {
    // Tandai bahwa dokumen BAPP sudah ditampilkan dan disimpan
    bappDokumenDitampilkan = true;

    // Tutup modal dokumen BAPP
    var bappDokumenModalEl = document.getElementById('bappDokumenModal');
    var bappDokumenModal = bootstrap.Modal.getInstance(bappDokumenModalEl);
    bappDokumenModal.hide();

    // Tampilkan tombol "Next Step" setelah dokumen disimpan
    document.getElementById('nextStepButton').classList.remove('d-none');
    // SweetAlert2 confirmation dialog
    Swal.fire({
            icon: 'info',
            title: 'Konfirmasi Penyimpanan',
            text: 'SDP dan Undangan akan disimpan kemudian dikirim ke penyedia. Lanjutkan?',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            customClass: {
                confirmButton: 'btn btn-primary me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the form
                document.getElementById('nextStepButton').classList.remove('d-none');
            } else {
                // If canceled, show a notification
                Swal.fire({
                    icon: 'error',
                    title: 'Penyimpanan dibatalkan',
                    text: 'Dokumen tidak disimpan.',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            }
        });
    });

// Event listener untuk tombol "Ubah" di modal dokumen BAPP
document.getElementById('ubahBapp').addEventListener('click', function() {
    // Isi ulang form BAPP dengan data yang telah disimpan
    document.getElementById('nomor').value = bappData.nomor;
    document.getElementById('tanggalSurat').value = bappData.tanggalSurat;
    document.getElementById('caraPenyampaian').value = bappData.caraPenyampaian;
    document.getElementById('kelengkapan').value = bappData.kelengkapan;
    document.getElementById('metodeEvaluasi').value = bappData.metodeEvaluasi;
    document.getElementById('halMenggugurkan').value = bappData.halMenggugurkan;
    document.getElementById('jenisKontrak').value = bappData.jenisKontrak;
    document.getElementById('namaPerwakilan').value = bappData.namaPerwakilan;
    document.getElementById('jabatan').value = bappData.jabatan;

    // Tampilkan modal pengisian form BAPP
    var myModal = new bootstrap.Modal(document.getElementById('pengadaanModal'));
    myModal.show();
});

// Event listener untuk tombol "Next Step"
// Event listener untuk tombol "Next Step"
document.getElementById('nextStepButton').addEventListener('click', function() {
    // Tampilkan elemen dengan id="step2"
    document.getElementById('step2').style.display = 'block';
    document.querySelector('.progress::after').style.width = '66%'; // Atur width ke 66% untuk step kedua
    // Sembunyikan tombol Next Step jika sudah di step 2
    this.classList.add('d-none'); // Sembunyikan tombol 'Next Step' setelah diklik
});

// Event listener untuk tombol yang memunculkan modal secara manual
document.getElementById('toggleButton').addEventListener('click', function() {
    if (!bappSudahDibuat) {
        // Jika belum dibuat, buka modal
        var myModal = new bootstrap.Modal(document.getElementById('pengadaanModal'));
        myModal.show();
    } else {
        // Gantikan alert bawaan dengan SweetAlert2
        Swal.fire({
            icon: 'info',
            title: 'BAPP Sudah Dibuat',
            text: 'Berita Acara Penjelasan Pekerjaan telah dibuat sebelumnya.',
            confirmButtonText: 'Oke',
            customClass: {
                confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
        });
    }
});
// Function to clear the content of step1
function clearStep1() {
    const step1Inputs = document.querySelectorAll('#step1 input, #step1 textarea, #step1 select');
    step1Inputs.forEach(input => input.value = ''); // Clear all input values in step1
}

</script>



<!-- Tambahkan SweetAlert2 CSS dan JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
