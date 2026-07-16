@extends('user.templates.sidebar')

@section('content')

<style>
    .input-abuk {
    background-color: #f0f0f0; /* Warna abu-abu */
    text-align: center; /* Memusatkan teks */
}

</style>
<!-- Awal Form Perjadin Kegiatan  -->
<section id="beranda" class=" pb-5 mt-5 pt-5">
    <div class="container">
        <div class="row text-secondary justify-content-center">
            <div class="col-md-10 mb-3">
                <div class="row">
                    <h3 class="fw-bold text-secondary">Pengajuan Kegiatan</h3>
                </div>
                <div class="card shadow-sm rounded-0 border-0 p-4" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    <!-- Progress bar -->
                    <div class="progressbar col-md-8 mx-auto mb-4">
                        <div class="progress" id="progress"></div>
                        <div class="progress-step" data-title="Informasi Kegiatan">1</div>
                        <div class="progress-step progress-step-active" data-title="Dokumen Kegiatan">2</div>
                    </div>

                    <form action="{{url('/c_kegiatan_all/' . $kegiatan->id)}}" method="post" id="myForm">
                        @method('PUT')
                        @csrf

                        <!-- Jumlah Peserta -->
                        <div class="mb-3 row align-items-center">
                            <label for="jumlah_peserta" class="col-md-4 col-form-label">Jumlah Peserta<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="number" id="jumlah_peserta" name="jumlah_peserta" class="form-control" placeholder="Masukkan Jumlah Peserta" required>
                                    <span class="input-group-text">Orang</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 row align-items-start">
                            <label for="jumlah_kamar" class="col-md-4 col-form-label">Jumlah Kamar<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="input-group">
                                            <input type="number" id="jumlah_kamar" name="jumlah_kamar" class="form-control" placeholder="Masukan Jumlah Kamar" required>
                                            <span class="input-group-text">Kamar</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="input-group">
                                            <input type="number" id="tambah_penginapan" name="tambah_penginapan" class="form-control" placeholder="Masukan Jumlah Hari" required>
                                            <span class="input-group-text">Hari</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Baris untuk Maksimal Panitia dan Cek Batas Panitia -->
                                <div class="d-flex justify-content-between align-items-center">
                                    @if ($cekJumlahPeserta != '-1' && $maksPanitia != '-1')
                                        <span class="text-muted me-3">
                                            <strong>Maksimal Panitia:</strong> {{ $maksPanitia }} orang
                                        </span>
                                    @endif
                                    <button type="button" id="btnBatasPanitia" class="btn btn-outline-secondary" onclick="batasPanitia()">
                                        Cek/Validasi
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Kepanitiaan -->
                        <div class="mb-3 row align-items-start">
                            <label for="kepanitian" class="col-md-4 col-form-label">Kepanitiaan<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <div class="d-flex justify-content-between mb-2">
                                    <input type="number" name="jumlah_kepanitiaan" class="form-control text-muted input-abuk" placeholder="Jumlah Kepanitiaan" value="{{ $jumlah_kepanitiaan }}">
                                    <div>
                                        <span class="input-group-text">Orang</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#tambah_peserta" type="button">
                                            <i class="fa fa-plus"></i> Tambah Kepanitiaan
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#informasiPerangkatModal" type="button">
                                            <i class="fa fa-eye"></i> Lihat Kepanitiaan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 row align-items-start">
                            <label for="mobilitas" class="col-md-4 col-form-label">Mobilitas</label>
                            <div class="col-md-8">
                                <div class="row">
                                    @if (!$mobilitasExists)
                                        <div class="col-md-6">
                                            <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#tambah_mobilitas" type="button">
                                                <i class="fa fa-plus"></i> Tambah Mobilitas
                                            </button>
                                        </div>
                                    @else
                                        <div class="col-md-6">
                                            <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#tambah_mobilitas" type="button" disabled>
                                                <i class="fa fa-plus"></i> Tambah Mobilitas
                                            </button>
                                        </div>
                                    @endif
                                    <div class="col-md-6">
                                        <button class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#lihatMobilitasModal" type="button">
                                            <i class="fa fa-eye"></i> Lihat Mobilitas
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row align-items-start">
                            <label for="mobilitas" class="col-md-4 col-form-label">Fasilitas Tambahan</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#pilihTipeFasilitas" type="button">
                                            <i class="fa fa-plus"></i> Tambah Fasilitas
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                       <button class="btn btn-outline-secondary w-100" data-bs-toggle="modal"  data-bs-target="#lihatFasilitasModal" type="button">
                                            <i class="fa fa-eye"></i> Lihat Fasilitas
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sarana dan Prasarana -->
                        <div class="mb-3 row align-items-start">
                            <label for="sapras" class="col-md-4 col-form-label">Sarana dan Prasarana</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#tambah_sapras" type="button">
                                            <i class="fa fa-plus"></i> Tambah Sapras
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#lihatSaprasModal" type="button">
                                            <i class="fa fa-eye"></i> Lihat Sapras
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dokumen -->
                        <div class="mb-3 row align-items-start">
                            <label for="dokumen" class="col-md-4 col-form-label">Dokumen<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#tambah_dokumen" type="button">
                                            <i class="fa fa-plus"></i> Tambah Dokumen
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#lihatDokumenModal" type="button">
                                            <i class="fa fa-eye"></i> Lihat Dokumen
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row align-items-start">
                            <label for="surtug" class="col-md-4 col-form-label">Apakah diPerlukan Surtug?<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-9"></div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center" style="transform: scale(1.5);">
                                    <!-- Teks 'Tidak' di kiri switch dengan ukuran kecil -->
                                    <span class="me-1" style="font-size: 0.6rem;">Tidak</span>

                                    <!-- Switch checkbox -->
                                    <div class="form-check form-switch">
                                        <!-- Hidden input to send '0' if checkbox is not checked -->
                                        <input type="hidden" name="surtug" value="0">
                                        <!-- Checkbox to toggle true/false, default to checked (Ya) -->
                                        <input class="form-check-input" type="checkbox" id="surtug" name="surtug" value="1" {{ old('surtug', '1') == '1' ? 'checked' : '' }}>
                                    </div>

                                    <!-- Teks 'Ya' di kanan switch dengan ukuran kecil -->
                                    <span style="font-size: 0.6rem; margin-left:-4px;">Ya</span>
                                </div>
                            </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-evenly mt-5">
                            <div class="d-flex col-md-6">
                            <a href="{{ url('/kegiatan/' . $kegiatan->id) }}" class="btn btn-warning text-white col-md-6">Kembali</a>
                            </div>
                            <div class="d-flex col-md-6">
                            <button type="button" id="btnSimpanDraft" class="btn btn-secondary col-md-6 me-2" onclick="submitDraft()">Simpan Draft</button>
                                <button type="submit" id="btnAjukanKegiatan" class="btn btn-primary col-md-6">Ajukan Kegiatan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Akhir Form Perjadin Kegiatan -->


<div class="modal fade" id="informasiPerangkatModal" tabindex="-1" aria-labelledby="informasiPerangkatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-secondary" id="informasiPerangkatModalLabel">Informasi Panitia/Pelaksana Tugas, Narasumber, dan Moderator</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <!-- Informasi Panitia (Pegawai dan Non-Pegawai disatukan) -->
                    <h6 class="fw-bold text-secondary mb-3">Panitia</h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center small">
                                <th>Nama Lengkap</th>
                                <th>Pangkat/Golongan</th>
                                <th>Sebagai</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Pegawai yang bertindak sebagai Panitia -->
                            @foreach ($perangkatPegawais as $perangkatPegawai)
                            @if (strtolower($perangkatPegawai->posisi) == 'panitia')
                            <tr>
                                <td class="text-center">{{ $perangkatPegawai->nama_lengkap }}</td>
                                <td class="text-center">{{ $perangkatPegawai->pangkat }} - {{ $perangkatPegawai->golongan }}</td>
                                <td class="text-center">{{ $perangkatPegawai->sebagai }}</td>
                                <td class="text-center">{{ $perangkatPegawai->status }}</td>
                                <td class="text-center">
                                    <form action="{{url('/h_peserta_kegiatan/'. $perangkatPegawai->idPerangkat)}}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                        <button class="btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data Peserta?')"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endif
                            @endforeach

                            <!-- Non-Pegawai yang bertindak sebagai Panitia -->
                            @foreach ($perangkatNonPegawais as $perangkatNonPegawai)
                            @if (strtolower($perangkatNonPegawai->posisi) == 'panitia')
                            <tr>
                                <td class="text-center">{{ $perangkatNonPegawai->nama_lengkap }}</td>
                                <td class="text-center">{{ $perangkatNonPegawai->pangkat }} - {{ $perangkatNonPegawai->golongan }}</td>
                                <td class="text-center">{{ $perangkatNonPegawai->sebagai }}</td>
                                <td class="text-center">{{ $perangkatNonPegawai->status }}</td>
                                <td class="text-center">
                                    <form action="{{url('/h_peserta_kegiatan/' . $perangkatNonPegawai->idPerangkat)}}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                        <button class="btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data Peserta?')"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Informasi Narasumber -->
                    <h6 class="fw-bold text-secondary mb-3 mt-5">Narasumber</h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center small">
                                <th>Nama Lengkap</th>
                                <th>Pangkat/Golongan</th>
                                <th>Sebagai</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($perangkatPegawais as $perangkatPegawai)
                            @if ($perangkatPegawai->posisi == 'Narasumber')
                            <tr>
                                <td class="text-center">{{ $perangkatPegawai->nama_lengkap }}</td>
                                <td class="text-center">{{ $perangkatPegawai->pangkat }} - {{ $perangkatPegawai->golongan }}</td>
                                <td class="text-center">{{ $perangkatPegawai->sebagai }}</td>
                                <td class="text-center">{{ $perangkatPegawai->status }}</td>
                                <td class="text-center">
                                    <form action="{{url('/h_peserta_kegiatan/'. $perangkatPegawai->idPerangkat)}}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                        <button class="btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data Peserta?')"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endif
                            @endforeach

                            @foreach ($perangkatNonPegawais as $perangkatNonPegawai)
                            @if ($perangkatNonPegawai->posisi == 'Narasumber')
                            <tr>
                                <td class="text-center">{{ $perangkatNonPegawai->nama_lengkap }}</td>
                                <td class="text-center">{{ $perangkatNonPegawai->pangkat }} - {{ $perangkatNonPegawai->golongan }}</td>
                                <td class="text-center">{{ $perangkatNonPegawai->sebagai }}</td>
                                <td class="text-center">{{ $perangkatNonPegawai->status }}</td>
                                <td class="text-center">
                                    <form action="{{url('/h_peserta_kegiatan/' . $perangkatNonPegawai->idPerangkat)}}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                        <button class="btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data Peserta?')"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Informasi Moderator -->
                    <h6 class="fw-bold text-secondary mb-3 mt-5">Moderator</h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center small">
                                <th>Nama Lengkap</th>
                                <th>Pangkat/Golongan</th>
                                <th>Sebagai</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($perangkatPegawais as $perangkatPegawai)
                            @if ($perangkatPegawai->posisi == 'Moderator')
                            <tr>
                                <td class="text-center">{{ $perangkatPegawai->nama_lengkap }}</td>
                                <td class="text-center">{{ $perangkatPegawai->pangkat }} - {{ $perangkatPegawai->golongan }}</td>
                                <td class="text-center">{{ $perangkatPegawai->sebagai }}</td>
                                <td class="text-center">{{ $perangkatPegawai->status }}</td>
                                <td class="text-center">
                                    <form action="{{url('/h_peserta_kegiatan/'. $perangkatPegawai->idPerangkat)}}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                        <button class="btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data Peserta?')"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endif
                            @endforeach

                            @foreach ($perangkatNonPegawais as $perangkatNonPegawai)
                            @if ($perangkatNonPegawai->posisi == 'Moderator')
                            <tr>
                                <td class="text-center">{{ $perangkatNonPegawai->nama_lengkap }}</td>
                                <td class="text-center">{{ $perangkatNonPegawai->pangkat }} - {{ $perangkatNonPegawai->golongan }}</td>
                                <td class="text-center">{{ $perangkatNonPegawai->sebagai }}</td>
                                <td class="text-center">{{ $perangkatNonPegawai->status }}</td>
                                <td class="text-center">
                                    <form action="{{url('/h_peserta_kegiatan/' . $perangkatNonPegawai->idPerangkat)}}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                        <button class="btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data Peserta?')"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pilih Tipe Fasilitas -->
<div class="modal fade" id="pilihTipeFasilitas" tabindex="-1" aria-labelledby="pilihTipeFasilitasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-4 border-0">
            <div class="modal-header bg-gradient-primary text-white py-3">
                <h5 class="modal-title fw-bold text-center w-100" id="pilihTipeFasilitasLabel">Pilih Tipe Fasilitas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="text-muted">Silakan pilih tipe fasilitas yang ingin Anda tambahkan:</p>
                <div class="d-grid gap-3">
                    <button class="btn btn-warning btn-lg tombol-fasilitas"
                        data-bs-toggle="modal" data-bs-target="#tambahFasilitasPelaksanaModal" data-bs-dismiss="modal">
                        <i class="fa fa-user fa-lg text-dark"></i> <span class="fw-bold">Fasilitas untuk Pelaksana</span>
                    </button>
                    <button class="btn btn-danger btn-lg tombol-fasilitas"
                        data-bs-toggle="modal" data-bs-target="#tambahFasilitasModal" data-bs-dismiss="modal">
                        <i class="fa fa-plus-circle fa-lg text-light"></i> <span class="fw-bold">Fasilitas Tambahan Lainnya</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Warna gradasi untuk modal header */
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff, #6f42c1);
}

/* Efek hover untuk tombol */
.tombol-fasilitas {
    border-radius: 50px;
    transition: all 0.3s ease-in-out;
    padding: 14px 20px;
    font-size: 1.2rem;
}

.tombol-fasilitas:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

/* Ukuran tombol dan modal agar lebih responsif */
@media (max-width: 768px) {
    .tombol-fasilitas {
        font-size: 1rem;
        padding: 10px 16px;
    }

    .modal-content {
        width: 95%;
        margin: auto;
    }
}

@media (max-width: 480px) {
    .tombol-fasilitas {
        font-size: 0.9rem;
        padding: 8px 14px;
    }

    .modal-content {
        width: 90%;
        margin: auto;
    }
}
</style>





  {{-- Modal Tambah Fasilitas Pelaksana --}}
  <div class="modal fade" id="tambahFasilitasPelaksanaModal" tabindex="-1" aria-labelledby="tambahFasilitasModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="tambah_fasilitasLabel">Tambah Fasilitas</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{url('/c_fasilitas')}}" method="post">
            @csrf
                <input type="hidden" name="data_perjadinkegiatan" value="{{ $kegiatan->id }}">

            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="peserta" class="form-label">Nama Pelaksana</label>
                <select class="form-select mb-2" aria-label="Default select example" name="perangkat_acara">
                    <option value="" disabled selected>Pilih Pelaksana</option>
                    @php
                        $uniqueNames = [];
                    @endphp
                    @foreach($perangkatPegawais as $pesertaPegawai)
                        @if(!in_array($pesertaPegawai->nama_lengkap, $uniqueNames))
                            <option value="{{$pesertaPegawai->idPerangkat}}">{{$pesertaPegawai->nama_lengkap}}</option>
                            @php
                                $uniqueNames[] = $pesertaPegawai->nama_lengkap;
                            @endphp
                        @endif
                    @endforeach
                    @foreach($perangkatNonPegawais as $nonPegawai)
                        @if(!in_array($nonPegawai->nama_lengkap, $uniqueNames))
                            <option value="{{$nonPegawai->idPerangkat}}">{{$nonPegawai->nama_lengkap}}</option>
                            @php
                                $uniqueNames[] = $nonPegawai->nama_lengkap;
                            @endphp
                        @endif
                    @endforeach
                </select>
                <label for="uraian" class="form-label">Nama Fasilitas <span class="text-secondary small"></span><span class="text-danger">*</span></label>
                <select class="form-select" id="uraian_pelaksana" name="uraian" required>
                  <option value="" disabled selected>Pilih Jenis Fasilitas</option>
                  <!-- <option value="Akomodasi Hotel">Akomodasi Hotel</option>
                  <option value="BBM">BBM</option>
                  <option value="Tiket Kereta">Tiket Kereta</option>
                  <option value="Tiket Pesawat">Tiket Pesawat</option>
                  <option value="Tiket Travel">Tiket Travel</option>
                  <option value="Transportasi Online">Transportasi Online</option>
                  <option value="Tol">Tol</option> -->
                  @foreach ($ref_fasilitas_pelaksana as $ref_fasilitass)
                          <option value="{{$ref_fasilitass->nama_fasilitas}}">{{$ref_fasilitass->nama_fasilitas}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div id="conditional_fields_pelaksana">
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
      </div>
      </form>
    </div>
  </div>


  {{-- Modal Tambah Fasilitas Lainnya --}}
  <div class="modal fade" id="tambahFasilitasModal" tabindex="-1" aria-labelledby="tambahFasilitasModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="tambah_fasilitasLabel">Tambah Fasilitas Lainnya</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

    <form id="formTambahFasilitas" action="{{ url('/c_fasilitas') }}" method="post">
    @csrf <!-- Laravel CSRF protection -->

    <input type="hidden" name="data_perjadinkegiatan" value="{{ $kegiatan->id }}">
    <input type="hidden" name="perangkat_acara" value="null">
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="uraian" class="form-label">Nama Fasilitas <span class="text-secondary small"></span><span class="text-danger">*</span></label>
                <select class="form-select" id="uraian" name="uraian" required>
                  <option value="" disabled selected>Pilih Jenis Fasilitas</option>
                  <!-- <option value="Konsumsi">Konsumsi</option>
                  <option value="Akomodasi Hotel">Akomodasi Hotel</option>
                  <option value="BBM">BBM</option>
                  <option value="Tiket Kereta">Tiket Kereta</option>
                  <option value="Tiket Pesawat">Tiket Pesawat</option>
                  <option value="Tiket Travel">Tiket Travel</option>
                  <option value="Transportasi Online">Transportasi Online</option>
                  <option value="Tol">Tol</option> -->
                    @foreach ($ref_fasilitas as $ref_fasilitas)
                        <option value="{{$ref_fasilitas->nama_fasilitas}}">{{$ref_fasilitas->nama_fasilitas}}</option>
                    @endforeach
                </select>
              </div>
            </div>

            <div id="conditional_fields">
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>




<!-- Modal Lihat Data Fasilitas -->
<div class="modal fade" id="lihatFasilitasModal" tabindex="-1" aria-labelledby="lihatFasilitasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lihatFasilitasModalLabel">Lihat Data Fasilitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center small">
                                <th>No</th>
                                <th>Jenis Fasilitas</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Tipe Pembayaran</th>
                                <th>Keterangan</th>
                                <th>Pelaksana</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                                @foreach($kebutuhans as $item)
                                    <tr class="text-center small">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->jumlah_frekuensi }}</td>
                                        <td>{{ $item->satuan }}</td>
                                        <td>{{ $item->tipe_pendanaan }}</td>
                                        <td>{{ $item->ket }}</td>
                                        <td>{{ $item->pelaksana }}</td>
                                        <td>
                                            <form action="{{ url('/h_fasilitas_kegiatan/' . $item->idKebutuhan) }}" method="post">
                                                @method('delete')
                                                @csrf
                                                <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                                <button type="submit" class="text-decoration-none btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data Fasilitas?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Lihat Mobilitas -->
<div class="modal fade" id="lihatMobilitasModal" tabindex="-1" aria-labelledby="lihatMobilitasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-secondary" id="lihatMobilitasLabel">Informasi Mobilitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center small">
                                <th>No</th>
                                <th>Mobilitas</th>
                                <th>Tanggal Digunakan</th>
                                <th>Kegunaan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mobilitas as $mobil)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $mobil->mobilitas }}</td>
                                <td class='text-center'>{{$mobil->tgl_mulai}}</td>
                                <td class='text-center'>{{$mobil->tujuan_penggunaan}}</td>
                                <td class='text-center'>
                                <form action="{{url('/h_mobilitas_kegiatan/' . $mobil->id)}}" method="post">
                                     @method('delete')
                                    @csrf
                                    <input id="" type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                    <span>
                                    <button class="text-decoration-none btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data Mobilitas?')"><i class="fa-solid fa-trash"></i></button>
                                    </span>
                                </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Lihat Sapras -->
<div class="modal fade" id="lihatSaprasModal" tabindex="-1" aria-labelledby="lihatSaprasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-secondary" id="lihatSaprasLabel">Informasi Sarana dan Prasarana</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center small">
                                <th>No</th>
                                <th>Sarana</th>
                                <th>Jumlah</th>
                                <th>Tanggal Digunakan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sapras as $sapra)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $sapra->nama_barang }}</td>
                                <td class="text-center">{{ $sapra->jumlah_asset }}</td>
                                <td class="text-center">{{ $sapra->tgl_peminjaman }}</td>
                                <td class="text-center">{{ $sapra->status }}</td>
                                <td class="text-center">
                                    <form action="{{url('/h_sapras/' . $sapra->idPeminjaman)}}" method="post">
                                        @method('delete')
                                        @csrf
                                        <input id="" type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                        <input id="" type="hidden" value="{{ $sapra->IdBarang }}" name="idAsset">
                                        <button class="btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Lihat Dokumen -->
<div class="modal fade" id="lihatDokumenModal" tabindex="-1" aria-labelledby="lihatDokumenLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-secondary" id="lihatDokumenLabel">Dokumen Pendukung</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center small">
                                <th>No</th>
                                <th>Nama Dokumen</th>
                                <th>Lampiran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dokumens as $dokumen)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $dokumen->nama_dokumen }}</td>
                                <td class="text-center">
                                    <a href="{{ url('kegiatan/getDokumen/' . basename($dokumen->file)) }}" target="_blank">[Lihat Lampiran]</a>
                                </td>
                                <td class="text-center">
                                    <form action="{{url('/h_dokumen_kegiatan/' . $dokumen->id)}}" method="post">
                                        @method('delete')
                                        @csrf
                                        <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                        <button class="btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@php
$terdaftarPegawaiIds = session('terdaftarPegawaiIds', []);  // Jika variabel tidak ada, gunakan array kosong
@endphp
<!-- Modal Tambah Peserta -->
<div class="modal fade" id="tambah_peserta" tabindex="-1" aria-labelledby="tambah_pesertaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tambah_pesertaLabel">Tambah Peserta</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{url('/c_peserta_kegiatan_many')}}" method="post" onsubmit="return validateForm()">
                    @csrf
                    <input id="" type="hidden" value="{{ $kegiatan->id }}" name="info_kegiatan">
                    <input id="" type="hidden" value="{{ $kegiatan->tgl_mulai }}" name="mulai">
                    <input id="" type="hidden" value="{{ $kegiatan->tgl_selesai }}" name="selesai">
                    
                    <div class="row">
                        <div class="col-md-12 mb-3 tab-pane fade show active required-select">
                            <label for="" class="form-label">Posisi<span class="text-danger">*</span></label>
                            <select class="form-select" aria-label=".form-select-sm example" name="posisi" id="posisiSelect" style="width: 100%;" onchange="updatePenugasanOptions()">
                                <option value="Panitia">Panitia</option>
                                <option value="Narasumber">Narasumber</option>
                                <option value="Moderator">Moderator</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3 tab-pane fade show active required-select">
                            <label for="" class="form-label">Penugasan<span class="text-danger">*</span></label>
                            <select class="" name="penugasan" id="penugasanSelect" style="width: 100%;">
                                <!-- Opsi penugasan akan di-update oleh JavaScript -->
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3 tab-pane fade show active required-select">
                            <label for="" class="form-label">Nama Pegawai <span class="text-danger">*</span> <a href="" data-bs-target="#non_pegawai" data-bs-toggle="modal" data-bs-dismiss="modal" aria-label="Close">[Tambah Non-Pegawai]</a></label>
                            <select class="form-select js-example-basic-multiple" name="peserta_pegawai[]" multiple style="width: 100%;">
                                @foreach ($pegawais as $pegawai)
                                    @if(!in_array($pegawai->id, $terdaftarPegawaiIds))
                                        <option value="{{ $pegawai->id }}">{{ $pegawai->nama_lengkap }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Durasi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" min="0" aria-label="First name" class="form-control" name="satuan"  required>
                                <select class="form-select" aria-label="Default select example" name="detail_satuan">
                                    <option value="menit" selected>Menit</option>
                                    <option value="jam" selected>Jam</option>
                                    <option value="hari">Hari</option>
                                    <option value="bulan">Bulan</option>
                                </select>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="action" value="pegawai_id" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Tambah Peserta non pegawai -->
<div class="modal fade" id="non_pegawai" tabindex="-1" aria-labelledby="tambah_pesertaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tambah_pesertaLabel">Tambah Peserta</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('/c_non_peserta_kegiatan') }}" method="post">
                    @csrf
                    <input type="hidden" value="{{ $kegiatan->id }}" name="info_kegiatan">
                    <input id="" type="hidden" value="{{ $kegiatan->tgl_mulai }}" name="mulai">
                    <input id="" type="hidden" value="{{ $kegiatan->tgl_selesai }}" name="selesai">
                    <!-- Nama Non-Pegawai -->
                    <div class="col-md-12 mb-3">
                        <label for="pegawaiDropdown" class="form-label">Nama Non-Pegawai</label>
                        <select id="pegawaiDropdown" name="peserta_non_pegawai" style="width: 100%;">
                            <option value="">Pilih Non-Pegawai</option>
                            @foreach ($nonpegawais as $nonpegawai)

                                <option value="{{ $nonpegawai->id }}">{{ $nonpegawai->nama_lengkap }}</option>

                            @endforeach
                        </select>
                    </div>

                    <!-- Form Tambah Non-Pegawai Baru -->
                    <div id="buttonContainer" class="mb-3">
                        <button id="triggerButton" class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#formContainer">Tambah Non-Pegawai</button>
                    </div>

                    <div id="formContainer" class="collapse">
                        <div class="card card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nama_lengkap">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="InputJenisKelamin" class="form-label">Jenis Kelamin<span class="text-danger">*</span></label>
                                    <select name="jenis_kelamin" class="form-select text-muted" id="InputJenisKelamin">
                                        <option selected>Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki">Laki-Laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control" name="email">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="NIP_NIK" class="form-label">NIP/NIK</label>
                                    <input type="text" class="form-control" name="NIP_NIK">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="InputStatus" class="form-label">Status<span class="text-danger">*</span></label>
                                    <select name="status" class="form-select text-muted" id="InputStatus">
                                        <option selected>Pilih Status</option>
                                        <option value="PNS">PNS</option>
                                        <option value="non PNS">Non PNS</option>
                                        <option value="non PNS">PPPK</option>
                                        <option value="PPNPN">PPNPN</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="pangkat" class="form-label">Pangkat<span class="text-danger">*</span></label>
                                    <select name="pangkat" id="" class="form-select js-example-basic-single-6" style="width: 100%;">
                                        <option value="-" selected>Pilih Pangkat</option>
                                        <option value="-">-</option>
                                        <option value="Pengatur Muda">II/a - Pengatur Muda</option>
                                        <option value="Pengatur Muda Tingkat 1">II/b - Pengatur Muda Tingkat 1</option>
                                        <option value="Pengatur">II/c - Pengatur</option>
                                        <option value="Pengatur Tingkat 1">II/d - Pengatur Tingkat 1</option>
                                        <option value="Penata Muda">III/a - Penata Muda</option>
                                        <option value="Penata Muda Tingkat I">III/b - Penata Muda Tingkat I</option>
                                        <option value="Penata">III/c - Penata</option>
                                        <option value="Penata Tingkat 1">III/d - Penata Tingkat 1</option>
                                        <option value="Pembina">IV/a - Pembina</option>
                                        <option value="Pembina Tingkat 1">IV/b - Pembina Tingkat 1</option>
                                        <option value="Pembina Utama Muda">IV/c - Pembina Utama Muda</option>
                                        <option value="Pembina Utama Madya">IV/d - Pembina Utama Madya</option>
                                        <option value="Pembina Utama">IV/e - Pembina Utama</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="golongan" class="form-label">Golongan<span class="text-danger">*</span></label>
                                    <select name="golongan" id="" class="form-select js-example-basic-single-6" style="width: 100%;">
                                        <option value="-" selected>Pilih Golongan</option>
                                        <option value="-">-</option>
                                        <option value="II/a">II/a</option>
                                        <option value="II/b">II/b</option>
                                        <option value="II/c">II/c</option>
                                        <option value="II/d">II/d</option>
                                        <option value="III/a">III/a</option>
                                        <option value="III/b">III/b</option>
                                        <option value="III/c">III/c</option>
                                        <option value="III/d">III/d</option>
                                        <option value="IV/a">IV/a</option>
                                        <option value="IV/a">IV/b</option>
                                        <option value="IV/c">IV/c</option>
                                        <option value="IV/d">IV/d</option>
                                        <option value="IV/e">IV/e</option>
                                    </select>
                                </div>
                                {{-- NPWP --}}
                                <div class="col-md-12 mb-3">
                                    <label for="InputNPWP" class="form-label">NPWP</label>
                                    <input name="npwp" type="text" class="form-control" id="InputNPWP" >
                                </div>
                                {{-- Bank dan No Rekening --}}
                                <div class="col-md-12 mb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="InputBank" class="form-label">Bank</label>
                                            <select name="bank" class="form-select text-muted" id="InputBank">
                                                <option selected>Pilih Bank</option>
                                                <option value="-">-</option>
                                                @foreach ($data_bank as $bank)
                                                    <option value="{{ $bank->kode_bank }}">{{$bank->kode_bank}} ({{ $bank->nama_bank }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="golongan" class="form-label">No Rekening</label>
                                            <input type="text" class="form-control" name="no_rekening">
                                        </div>
                                    </div>
                                </div>

                                {{-- Nama Rekening --}}
                                <div class="col-md-12 mb-3">
                                    <label for="golongan" class="form-label">Nama Rekening</label>
                                    <input type="text" class="form-control" name="nama_rekening">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Posisi -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="posisiSelectNonPegawai" class="form-label">Posisi</label>
                            <select class="form-select" name="posisi" id="posisiSelectNonPegawai" style="width: 100%;" onchange="updatePenugasanOptionsNonPegawai()">
                                <option value="Panitia">Panitia</option>
                                <option value="Narasumber">Narasumber</option>
                                <option value="Moderator">Moderator</option>
                            </select>
                        </div>
                    </div>

                    <!-- Penugasan -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="penugasanSelectNonPegawai" class="form-label">Penugasan</label>
                            <select class="form-select" name="penugasan" id="penugasanSelectNonPegawai" style="width: 100%;">
                                <!-- Opsi penugasan akan di-update oleh JavaScript -->
                            </select>
                        </div>
                    </div>

                    <!-- Durasi -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="satuan" class="form-label">Durasi</label>
                            <div class="input-group">
                                <input type="number" min="0" class="form-control" name="satuan" id='satuanNon' required>
                                <select class="form-select" name="detail_satuan" id="detailSatuanNon">
                                    <option value="menit" selected>Menit</option>
                                    <option value="jam" selected>Jam</option>
                                    <option value="hari">Hari</option>
                                    <option value="bulan">Bulan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal Tambah Kebutuhan -->
<div class="modal fade" id="tambah_kebutuhan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Kebutuhan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('/c_fasilitasKegiatan_draft')}}" method="post">
                @csrf
                <input id="" type="hidden" value="" name="kegiatan_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Perangkat Orang<span class="text-danger">*</span> <span class="text-secondary small">(Contoh : Panitia, Narasumber, Moderator)</span></label>
                            <input type="text" class="form-control" id="" name="fasilitas" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Tambah Sapras -->
<div class="modal fade" id="tambah_sapras" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('/c_sapras_kegiatan')}}" method="post">
                @csrf
                <input type="hidden" name="kegiatanId" value="{{$kegiatan->id}}">
                <div class="modal-body">
                    <div class="mb-3 row">
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Sarana dan Prasarana <span class="text-danger">*</span></label>
                            <select class="js-example-basic-single-3" id="sapras" name="sapras" style="width: 100%;" required>
                                <option value="" selected>Pilih Barang</option>
                                @foreach ($saranas as $sarana)
                                <option value="{{ $sarana->id }}">{{ $sarana->nama_barang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-4">
                            <label for="" class="form-label">Tanggal Digunakan <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="tgl_peminjaman" id="" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="" class="form-label">Tanggal Selesai</label>
                            <input type="datetime-local" name="tgl_selesai" id="" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="" class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" min="0" name="jumlah" id="" class="form-control" value="1" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-12">
                            <label for="" class="form-label">Keterangan <span class="text-danger">*</span><span class="text-secondary small">(Contoh : Untuk dokumentasi)</span></label>
                            <textarea name="keterangan" id="" class="form-control" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="tambah_mobilitas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{url('/c_mobilitas_kegiatan')}}" method="post">
                    @csrf
                    <input id="" type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label for="" class="form-label">Mobilitas <span class="text-danger">*</span></label>
                            <select class="form-select form-select" aria-label=".form-select-sm example" name="mobilitas">
                                <option value="Kendaraan Dinas LLDIKTI" selected>Kendaraan Dinas LLDIKTI</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="form-label">Kegunaan <span class="text-danger">*</span></label>
                            <select class="form-select" name="tujuan" required>
                                <option value="0" selected disabled>Pilih Penggunaan</option>
                                <option value="Antar">Antar</option>
                                <option value="Jemput">Jemput</option>
                                <option value="Antar-Jemput">Antar-Jemput</option>

                            </select>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
        </div>
        </form>
    </div>
</div>

<div class="modal fade" id="tambah_dokumen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Dokumen Pendukung</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('/c_dokumen_kegiatan')}}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="kegiatanId" value="{{$kegiatan->id}}">
                <div class="modal-body">
                    <div class="mb-3 row">
                        <div class="col-md-12">
                            <label for="" class="form-label">Nama Dokumen<span class="text-danger">*</span></label>
                            <input type="text" name="nama_dokumen" id="" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-12">
                            <label for="" class="form-label">Upload Dokumen (Maksimal 2MB)<span class="text-danger">*</span></label>
                            <input type="file" name="file" id="" class="form-control" accept="application/pdf" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
        </div>
        </form>
    </div>
</div>

<script>

    // Ambil nilai dari localStorage jika ada dan lakukan pengaturan saat DOM sudah siap
document.addEventListener("DOMContentLoaded", function() {
    const jumlahPesertaInput = document.getElementById('jumlah_peserta');
    const jumlahKamarInput = document.getElementById('jumlah_kamar');
    const jumlahHariInput = document.getElementById('tambah_penginapan');

    // Ambil variabel dari server-side (controller)
    let pesertaExists = {{ json_encode($pesertaExists) }};
    let kamarExists = {{ json_encode($kamarExists) }};
    let tambahPenginapanExists = {{ json_encode($tambahPenginapanExists) }};

    // Ambil nilai dari objek $kegiatan
    let jumlahPeserta = {{ json_encode($kegiatan->jumlah_peserta) }};
    let jumlahKamar = {{ json_encode($kegiatan->jumlah_kamar) }};
    let tambahPenginapan = {{ json_encode($kegiatan->tambah_penginapan) }};

    // Set nilai input berdasarkan kondisi exists
    if (pesertaExists) {
        jumlahPesertaInput.value = jumlahPeserta; // Isi dengan nilai dari $kegiatan
    }
    if (kamarExists) {
        jumlahKamarInput.value = jumlahKamar; // Isi dengan nilai dari $kegiatan
    }
    if (tambahPenginapanExists) {
        jumlahHariInput.value = tambahPenginapan; // Isi dengan nilai dari $kegiatan
    }

    // Simpan nilai ke localStorage saat diubah, hanya jika data tersebut ada (tidak NULL)
    if (pesertaExists) {
        jumlahPesertaInput.addEventListener('input', function() {
            localStorage.setItem('jumlah_peserta', jumlahPesertaInput.value);
        });
    }

    if (kamarExists) {
        jumlahKamarInput.addEventListener('input', function() {
            localStorage.setItem('jumlah_kamar', jumlahKamarInput.value);
        });
    }

    if (tambahPenginapanExists) {
        jumlahHariInput.addEventListener('input', function() {
            localStorage.setItem('tambah_penginapan', jumlahHariInput.value);
        });
    }
});


document.getElementById('btnAjukanKegiatan').addEventListener('click', function() {
    localStorage.removeItem('jumlah_peserta');
    localStorage.removeItem('jumlah_kamar');
    localStorage.removeItem('tambah_penginapan');
});
document.getElementById('btnSimpanDraft').addEventListener('click', function() {
    localStorage.removeItem('jumlah_peserta');
    localStorage.removeItem('jumlah_kamar');
    localStorage.removeItem('tambah_penginapan');
});
function submitDraft() {
const form = document.getElementById('myForm');
form.action = "{{ url('/draft_kegiatan_all/' . $kegiatan->id) }}";
form.submit();
}
function batasPanitia() {
    const form = document.getElementById('myForm');
    form.action = "{{ url('/kegiatan/batas-panitia/' . $kegiatan->id) }}";
    form.submit();
    }
</script>

<script>
    $('.js-example-basic-single-3').select2({
        placeholder: 'Select an option',
        dropdownParent: '#tambah_sapras'
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Initialize penugasan options when the page loads
    updatePenugasanOptions();
});

function updatePenugasanOptions() {
    var posisiSelect = document.querySelector('select[name="posisi"]');
    var penugasanSelect = document.querySelector('select[name="penugasan"]');
    var satuanInput = document.querySelector('input[name="satuan"]'); // Pastikan ini merujuk ke elemen input yang benar
    var detailSatuanSelect = document.querySelector('select[name="detail_satuan"]');


    var posisi = posisiSelect ? posisiSelect.value : null;

    const jumlahHari = {{ $jumlahHari ?? 0 }};

    // Clear existing options in penugasan
    penugasanSelect.innerHTML = "";

    if (posisi === "Panitia") {
        satuanInput.value = jumlahHari;
        if (jumlahHari >= 30) {
            detailSatuanSelect.value = 'bulan';
        } else {
            detailSatuanSelect.value = 'hari';
        }

        addOption(penugasanSelect, "Penanggung Jawab", "Penanggung Jawab");
        addOption(penugasanSelect, "Anggota", "Anggota");
        addOption(penugasanSelect, "Ketua", "Ketua");
        addOption(penugasanSelect, "Sekretaris", "Sekretaris");
    } else if (posisi === "Narasumber") {
        satuanInput.value = '';
        detailSatuanSelect.value = 'jam';
        addOption(penugasanSelect, "Narasumber", "Narasumber");
    } else if (posisi === "Moderator") {
        satuanInput.value = '';
        detailSatuanSelect.value = 'jam';
        addOption(penugasanSelect, "Moderator", "Moderator");
    }
}

function addOption(selectElement, text, value) {
    var option = document.createElement("option");
    option.text = text;
    option.value = value;
    selectElement.add(option);
}

// Call updatePenugasanOptions when the posisi changes
document.querySelector('select[name="posisi"]').addEventListener("change", updatePenugasanOptions);

</script>

<!-- JavaScript Section -->



<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize penugasan options when the page loads for non-pegawai
        updatePenugasanOptionsNonPegawai();
    });

    function updatePenugasanOptionsNonPegawai() {
        var posisiSelect = document.getElementById("posisiSelectNonPegawai");
        var penugasanSelect = document.getElementById("penugasanSelectNonPegawai");
        var satuanInput = document.getElementById("satuanNon");
        var detailSatuanSelect = document.getElementById("detailSatuanNon");
        var posisi = posisiSelect ? posisiSelect.value : null;

        const jumlahHari = {{ $jumlahHari ?? 0 }};

        // Clear existing options
        penugasanSelect.innerHTML = "";

        if (posisi === "Panitia") {
            satuanInput.value = jumlahHari;
            if (jumlahHari >= 30) {
                detailSatuanSelect.value = 'bulan';
            } else {
                detailSatuanSelect.value = 'hari';
            }

            addOption(penugasanSelect, "Penanggung Jawab", "Penanggung Jawab");
            addOption(penugasanSelect, "Anggota", "Anggota");
            addOption(penugasanSelect, "Ketua", "Ketua");
            addOption(penugasanSelect, "Sekretaris", "Sekretaris");
        } else if (posisi === "Narasumber") {
            satuanInput.value = '';
            detailSatuanSelect.value = 'jam';
            addOption(penugasanSelect, "Narasumber", "Narasumber");
        } else if (posisi === "Moderator") {
            satuanInput.value = '';
            detailSatuanSelect.value = 'jam';
            addOption(penugasanSelect, "Moderator", "Moderator");
        }
    }

    function addOption(selectElement, text, value) {
        var option = document.createElement("option");
        option.text = text;
        option.value = value;
        selectElement.add(option);
    }

    // Call updatePenugasanOptionsNonPegawai when the posisi changes
    document.getElementById("posisiSelectNonPegawai").addEventListener("change", updatePenugasanOptionsNonPegawai);
</script>

<script>
    function setInputFasilitas(hasil) {
        var input = document.getElementById('fasilitasId');
        input.setAttribute("value", hasil);

        var input = document.getElementById('fasilitasIdNonPegawai');
        input.setAttribute("value", hasil);
    }

    $(".js-example-basic-single-peserta").select2({
        placeholder: "Select an option",
        dropdownParent: "#tambah_peserta",
    });
</script>

<script>
    $('.js-example-basic-single-3').select2({
        placeholder: 'Select an option',
        dropdownParent: '#tambah_sapras'
    });
</script>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Ambil nilai cekJumlahPeserta dari PHP
        const cekJumlahPeserta = {{ $cekJumlahPeserta }};

        // Jika cekJumlahPeserta adalah -1, disable tombol yang memiliki data-bs-toggle="modal"
        if (cekJumlahPeserta === -1) {
            // Seleksi semua tombol dengan data-bs-toggle="modal"
            const modalButtons = document.querySelectorAll('[data-bs-toggle="modal"]');

            modalButtons.forEach(button => {
                button.disabled = true; // Set tombol menjadi disabled
            });
        }
    });
</script>


<script>
    function setInputFasilitas(hasil) {
        var input = document.getElementById('fasilitasId');
        input.setAttribute("value", hasil);

        var input = document.getElementById('fasilitasIdNonPegawai');
        input.setAttribute("value", hasil);
    }

    $(".js-example-basic-single-peserta").select2({
        placeholder: "Select an option",
        dropdownParent: "#tambah_peserta",
    });
</script>
<script>
    function submitDraft() {
        const form = document.getElementById('myForm');
        form.action = "{{ url('/draft_kegiatan_all/' . $kegiatan->id) }}";
        form.submit();
        }
    function batasPanitia() {
        const form = document.getElementById('myForm');
        form.action = "{{ url('/kegiatan/batas-panitia/' . $kegiatan->id) }}";
        form.submit();
        }
</script>
<script>
   document.addEventListener('DOMContentLoaded', function () {
    const tambahKepanitiaanButton = document.querySelector('button[data-bs-target="#tambah_peserta"]');
    const jumlahPesertaInput = document.getElementById('jumlah_peserta');
    const jumlahKepanitiaanInput = document.getElementById('jumlah_kepanitiaan'); // Element hidden yang menyimpan jumlah kepanitiaan saat ini.

    function checkKepanitiaanLimit() {
        const jumlahPeserta = parseInt(jumlahPesertaInput.value) || 0;
        const jumlahKepanitiaan = parseInt(jumlahKepanitiaanInput.value) || 0;
        const batasKepanitiaan = Math.floor(jumlahPeserta * 0.10); // 10% dari peserta

        if (jumlahKepanitiaan >= batasKepanitiaan) {
            // Tampilkan peringatan menggunakan SweetAlert jika batas tercapai
            Swal.fire({
                icon: 'warning',
                title: 'Batas Kepanitiaan Terlampaui!',
                text: `Jumlah kepanitiaan tidak boleh lebih dari 10% dari jumlah peserta. Batas maksimal: ${batasKepanitiaan} orang.`,
                confirmButtonText: 'OK'
            });

            // Disable button untuk menambahkan kepanitiaan
            tambahKepanitiaanButton.disabled = true;
        } else {
            // Enable button jika masih di bawah batas
            tambahKepanitiaanButton.disabled = false;
        }
    }

    // Cek batasan setiap kali jumlah peserta atau jumlah kepanitiaan berubah
    jumlahPesertaInput.addEventListener('input', checkKepanitiaanLimit);
    jumlahKepanitiaanInput.addEventListener('input', checkKepanitiaanLimit);

    // Panggil fungsi saat halaman dimuat pertama kali untuk validasi awal
    checkKepanitiaanLimit();
});

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $(document).ready(function() {
    // Event listener untuk dropdown "Jenis Fasilitas"
    $('#jenisFasilitas').on('change', function() {
        var selectedValue = $(this).val();
        var satuanSelect = $('#satuan');
        var keteranganLabel = $('label[for="keterangan"]');

        // Kosongkan opsi sebelumnya
        satuanSelect.empty();

        // Tambahkan opsi satuan sesuai dengan pilihan "Jenis Fasilitas"
        if (selectedValue === 'Tiket Pesawat' || selectedValue === 'Tiket Kereta' || selectedValue === 'Tiket Travel') {
            satuanSelect.append('<option value="Tiket">Tiket</option>');

            // Ubah label keterangan
            keteranganLabel.html(
            'Keterangan <span class="text-secondary small">(Contoh : Argo Parahyangan Eksekutif, Garuda Indonesia Ekonomi)</span>'
            );
        } else if (selectedValue === 'BBM') {
            satuanSelect.append('<option value="Liter">Liter</option>');

            // Ubah label keterangan
            keteranganLabel.html(
            'Keterangan <span class="text-secondary small">(Contoh : Keperluan Isi Bensin di SPBU)</span>'
            );
        } else if (selectedValue === 'Akomodasi Hotel') {
            satuanSelect.append('<option value="Malam">Malam</option>');

            // Ubah label keterangan
            keteranganLabel.html(
            'Keterangan <span class="text-secondary small">(Contoh : Hotel Aston)</span>'
            );
        } else if (selectedValue === 'Transportasi Online') {
            satuanSelect.append('<option value="Perjalanan">Perjalanan</option>');

            // Ubah label keterangan
            keteranganLabel.html(
            'Keterangan <span class="text-secondary small">(Contoh : Ojek Online dari Stasiun ke Hotel)</span>'
            );
        } else if (selectedValue === 'Konsumsi') {
            satuanSelect.append('<option value="pax">Pax</option>');

            // Ubah label keterangan
            keteranganLabel.html(
            'Keterangan <span class="text-secondary small">(Contoh : Makan Siang Peserta, Snack Panitia)</span>'
            );
        } else {
            satuanSelect.append('<option value="">Pilih Satuan</option>'); // Opsi default

             // Kembalikan label ke format default
            keteranganLabel.html('Keterangan');
        }
    });
});

</script>
@endsection
