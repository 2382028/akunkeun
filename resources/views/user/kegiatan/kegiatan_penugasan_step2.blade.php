@extends('user.templates.sidebar')

@section('content')

<!-- Awal Form Perjadin Biasa  -->
<section id="beranda" class="pb-5 pt-4">
    <div class="container">
        <div class="row text-secondary justify-content-center">
            <div class="col-md-10 mb-3">
                <div class="row mb-3">
                    <h3 class="fw-bold text-secondary">Pengajuan Kegiatan</h3>
                </div>
                <div class="card shadow rounded-0  border-0 p-3" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    <div class="card-body">
                        <!-- Progress bar -->
                        <div class="progressbar col-md-8 mx-auto mb-4">
                            <div class="progress" id="progress"></div>
                            <div class="progress-step" data-title="Informasi Kegiatan">1</div>
                            <div class="progress-step progress-step-active" data-title="Dokumen Kegiatan">2</div>
                        </div>

                        <!-- Step 2 -->
                        <div class="mb-3">
                            <div class="mb-4 row text-secondary">
                                {{-- <div class="col-md-12">
                                    <div class="card shadow rounded-0  border-0">
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-md-4 mb-3">Judul Surat Undangan</div>
                                                <input id="" type="hidden" value="{{ $kegiatan->id }}">
                                                <div class="col-md-4 mb-3"> {{ $kegiatan->nama_kegiatan }} </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4 mb-3">Tanggal Pelaksanaan</div>
                                                <div class="col-md-4 mb-3"> {{ $kegiatan->tgl_mulai }} </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4 mb-3">Lokasi</div>
                                                <div class="col-md-4 mb-3"> {{ $kegiatan->alamat }} </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                            {{-- <hr class="container" style="height: 3px; background-color: #fda10d; opacity: 1; border-color: #fda10d"> --}}
                            {{-- informasi Peserta --}}
                            <div class="row mb-3 text-secondary">
                                <div class="col-md-12 mb-3">
                                    <div class="card shadow rounded-0  border-0">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h6 class="fw-bold text-secondary">Informasi Peserta<span class="text-danger">*</span></h6><br>
                                                    </div>
                                                    <div>
                                                        <button class="btn btn-neon text-white mb-3" data-bs-toggle="modal" data-bs-target="#tambah_peserta" type="button">
                                                            <i class="fa fa-plus"></i> Tambah Peserta
                                                        </button>
                                                    </div>
                                                </div>
                                                <table id="example" class="table table-bordered data-table mb-3" style="width: 100%">
                                                    <thead>
                                                        <tr class="text-center small">
                                                            <th class="th-sm">No</th>
                                                            {{-- <th hidden class="th-md">Nama (Pegawai)</th> --}}
                                                            <th class="th-md">Nama (Pegawai)</th>
                                                            <th class="th-md">Pangkat/Golongan</th>
                                                            <th class="th-lg-percent">Sebagai</th>
                                                            <th class="th-md">Fasilitas</th>
                                                            <th class="th-lg-percent">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    @php
                                                        $iterasi = 1;
                                                    @endphp
                                                    @if ($selectPesertas->isNotEmpty())
                                                    @foreach ($selectPesertas as $selectPeserta)

                                                    <tr>
                                                        <td class='text-center'>{{$iterasi }}</td>
                                                        {{-- <td hidden class=''></td> --}}
                                                        <td class=''>{{ $selectPeserta->nama_lengkap }}</td>
                                                        <td class='text-center'>{{ $selectPeserta->golongan }}-{{ $selectPeserta->pangkat }}</td>
                                                        <td class='text-center'>{{$selectPeserta->status_pegawai}}</td>
                                                        <td class='text-center justify-content-center'>
                                                            <div class="d-inline-block">
                                                                <button class="btn btn-primary text-white mb-3 lihat-fasilitas" data-bs-toggle="modal" data-bs-target="#lihat_fasilitas_{{$selectPeserta->id}}" data-nama="{{ $selectPeserta->nama_lengkap }}" data-pegawai-id="{{ $selectPeserta->id }}" type="button">
                                                                    <i class="fa fa-eye"></i>
                                                                </button>
                                                            </div>
                                                            <div class="d-inline-block">
                                                                <button class="btn btn-neon text-white mb-3 tambah-fasilitas" data-bs-toggle="modal" data-bs-target="#tambah_fasilitas_{{$selectPeserta->id}}" data-nama="{{ $selectPeserta->nama_lengkap }}" data-pegawai-id="{{ $selectPeserta->id }}" type="button">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </td>

                                                        <td class='text-center justify-content-center'>

                                                            <form action="{{url('/h_peserta_kegiatan/' . $selectPeserta->id)}}" method="post">
                                                                @method('delete')
                                                                @csrf
                                                                <input id="" type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                                                <input id="" type="hidden" value="{{ $selectPeserta->pegawai_id }}" name="peserta">
                                                                <span>
                                                                    <button class="text-decoration-none btn btn-danger btn-sm" style="color: #FFFFFF; margin-left: 5px;" onclick="return confirm('Hapus Data Peserta?')"><i class="fa-solid fa-trash"></i></button>
                                                                </span>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $iterasi++;
                                                    @endphp
                                                    @endforeach
                                                    @endif
                                                    @if ($selectPesertasNonPegawais->isNotEmpty())
                                                        @foreach ($selectPesertasNonPegawais as $selectPesertasNonPegawai)
                                                        <tr>
                                                            <td class='text-center'>{{$iterasi }}</td>
                                                            <td class=''>{{ $selectPesertasNonPegawai->nama_lengkap }}</td>
                                                            <td class='text-center'>{{ $selectPesertasNonPegawai->golongan }}-{{ $selectPesertasNonPegawai->pangkat }}</td>
                                                            <td class='text-center'>{{ $selectPesertasNonPegawai->status_pegawai }}</td>
                                                            <td class='text-center justify-content-center'>
                                                                <div class="d-inline-block">
                                                                    <button class="btn btn-primary text-white mb-3 lihat-nonPegawai-fasilitas" data-bs-toggle="modal" data-bs-target="#lihat-nonPegawai_fasilitas_{{$selectPesertasNonPegawai->id}}" data-nama="{{ $selectPesertasNonPegawai->nama_lengkap }}" data-pegawai-id="{{ $selectPesertasNonPegawai->id }}" type="button">
                                                                        <i class="fa fa-eye"></i>
                                                                    </button>
                                                                </div>
                                                                <div class="d-inline-block">
                                                                    <button class="btn btn-neon text-white mb-3 tambah-nonPegawai-fasilitas" data-bs-toggle="modal" data-bs-target="#tambah-nonPegawai_fasilitas_{{$selectPesertasNonPegawai->id}}" data-nama="{{ $selectPesertasNonPegawai->nama_lengkap }}" data-pegawai-id="{{ $selectPesertasNonPegawai->id }}" type="button">
                                                                        <i class="fa fa-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                            <td class='text-center'>
                                                                <form action="{{url('/h_peserta_kegiatan/' . $selectPesertasNonPegawai->id)}}" method="post">
                                                                    @method('delete')
                                                                    @csrf
                                                                    <input id="" type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                                                    <span>
                                                                        <button class="text-decoration-none btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data Peserta?')"><i class="fa-solid fa-trash"></i></button>
                                                                    </span>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                        @php
                                                        $iterasi++;
                                                    @endphp
                                                        @endforeach
                                                    @endif
                                                </table>
                                                <hr class="container" style="height: 3px; background-color: #fda10d; opacity: 1; border-color: #fda10d">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Mobilitas --}}
                            <div class="row mb-3 text-secondary">
                                <div class="col-md-12 mb-3">
                                    <div class="card shadow rounded-0  border-0">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h6 class="fw-bold text-secondary">Mobilitas</h6><br>
                                                    </div>
                                                    <div>
                                                        @if (!$mobilitasExists)
                                                            <button class="btn btn-neon text-white mb-3" data-bs-toggle="modal" data-bs-target="#tambah_mobilitas" type="button">
                                                                <i class="fa fa-plus"></i> Tambah Mobilitas
                                                            </button>
                                                        @else
                                                            <button class="btn btn-neon text-white mb-3" data-bs-toggle="modal" data-bs-target="#tambah_mobilitas" type="button" disabled>
                                                                <i class="fa fa-plus"></i> Tambah Mobilitas
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                                <table id="example" class="table table-bordered data-table mb-3" style="width: 100%">
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
                                                <hr class="container" style="height: 3px; background-color: #fda10d; opacity: 1; border-color: #fda10d">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Dokumen Pendukung --}}
                            <div class="row mb-3 text-secondary">
                                <div class="col-md-12 mb-3">
                                    <div class="card shadow rounded-0  border-0">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h6 class="fw-bold text-secondary">Dokumen Pendukung<span class="text-danger">*</span></h6><br>
                                                    </div>
                                                    <div>
                                                        <button class="btn btn-neon text-white mb-3" data-bs-toggle="modal" data-bs-target="#tambah_dokumen" type="button">
                                                            <i class="fa fa-plus"></i> Tambah Dokumen
                                                        </button>
                                                    </div>
                                                </div>
                                                <table id="example" class="table table-bordered data-table mb-3" style="width: 100%">
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
                                                <hr class="container" style="height: 3px; background-color: #fda10d; opacity: 1; border-color: #fda10d">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <form action="{{url('/c_kegiatan_all/' . $kegiatan->id)}}" method="post" id="myForm">
                                @method('PUT')
                                @csrf
                                <input type="hidden" name="surtug" value="1">
                                <input id="" type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                <input type="hidden" name="jumlah_kepanitiaan" value="{{ $jumlah_kepanitiaan }}">
                                <input type="hidden" name="tambah_penginapan" value="{{ $jumlahHari }}">
                                <input type="hidden" name="jumlah_kamar" value="0">



                                <div class="btns-group d-flex justify-content-evenly pb-3 mt-5">
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
        </div>
    </div>
</section>

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
                                {{-- <option value="Narasumber">Narasumber</option>
                                <option value="Moderator">Moderator</option> --}}
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

{{-- Modal Tambah Mobilitas --}}
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

{{-- Modal Tambah Dokumen --}}
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

<!-- Modal Tambah Fasilitas -->
@if ($selectPesertas->isNotEmpty())
@foreach ($selectPesertas as $selectPeserta)
<div class="modal fade" id="tambah_fasilitas_{{ $selectPeserta->id }}" tabindex="-1" aria-labelledby="tambah_fasilitasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tambah_fasilitasLabel">Tambah Fasilitas</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('/c_fasilitas') }}" method="post">
                    @csrf
                    <input type="hidden" value="{{ $kegiatan->id }}" name="data_perjadinkegiatan">
                    <input type="hidden" value="{{ $selectPeserta->id }}" name="pegawai_id">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" readonly>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="uraian_{{ $selectPeserta->id }}" class="form-label">Nama Fasilitas <span class="text-secondary small"></span><span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <select class="form-select" id="uraian_{{ $selectPeserta->id }}" name="uraian" required>
                                        <option value="" disabled selected>Pilih Jenis Fasilitas</option>
                                        <option value="Akomodasi Hotel">Akomodasi Hotel</option>
                                        <!-- <option value="BBM">BBM</option> -->
                                        <option value="Tiket Kereta">Tiket Kereta</option>
                                        <option value="Tiket Pesawat">Tiket Pesawat</option>
                                        <option value="Tiket Travel">Tiket Travel</option>
                                        <option value="Transportasi Online">Transportasi Online</option>
                                        <option value="Lainnya">Lainnya</option>
                                        <!-- <option value="Tol">Tol</option> -->
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <input type="number" class="form-control" id="jumlah_{{ $selectPeserta->id }}" name="jumlah_frekuensi" placeholder="Jumlah" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <input type="text" class="form-control" id="satuan_{{ $selectPeserta->id }}" name="satuan" placeholder="Satuan" readonly>
                                    <input type="text" class="form-control mt-2" id="satuan_manual_{{ $selectPeserta->id }}" name="satuan_manual" placeholder="Satuan (Lainnya)" style="display: none;">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="pembayaran" class="form-label">Tipe Pembayaran<span class="text-danger">*</span></label>
                                    <select class="form-select" id="pembayaran_{{ $selectPeserta->id }}" name="tipe_pendanaan" required>
                                        <option value="Bayar diawal">Bayar diawal</option>
                                        <option value="Reimburse">Reimburse</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="keterangan" class="form-label">Keterangan<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="keterangan_{{ $selectPeserta->id }}" name="keterangan" placeholder="Tambahkan Keterangan" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif

<!-- Modal Lihat Fasilitas Pegawai -->
@if ($selectPesertas->isNotEmpty())
@foreach ($selectPesertas as $selectPeserta)
<div class="modal fade" id="lihat_fasilitas_{{ $selectPeserta->id }}" tabindex="-1" aria-labelledby="lihat_fasilitasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="Lihat_fasilitasLabel">Daftar Fasilitas</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                <input type="hidden" value="{{ $selectPeserta->id }}" name="pegawai_id">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" readonly>
                    </div>
                    <div class="col-md-12">
                        <div class="mt-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Fasilitas</th>
                                        <th>Jumlah</th>
                                        <th>Satuan</th>
                                        <th>Tipe Pembayaran</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fasilitas as $fasilita)
                                    @if ($fasilita->data_perjadinkegiatan == $kegiatan->id && $fasilita->perangkat_acara == $selectPeserta->id)
                                    <tr>
                                        <td>{{ $fasilita->nama }}</td>
                                        <td>{{ $fasilita->jumlah_frekuensi }}</td>
                                        <td>{{ $fasilita->satuan }}</td>
                                        <td>{{ $fasilita->tipe_pendanaan }}</td>
                                        <td>{{ $fasilita->ket }}</td>
                                        <td>
                                            <form action="{{ url('/h_fasilitas_kegiatan/' . $fasilita->idKebutuhan) }}" method="post">
                                                @method('delete')
                                                @csrf
                                                <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                                <input type="hidden" value="{{ $fasilita->perangkat_acara }}" name="perangkatacara">
                                                <button type="submit" class="text-decoration-none btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data Fasilitas?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif


<!-- Modal Tambah Fasilitas NonPegawai -->
@if ($selectPesertasNonPegawais->isNotEmpty())
@foreach ($selectPesertasNonPegawais as $selectPesertasNonPegawai)
<div class="modal fade" id="tambah-nonPegawai_fasilitas_{{ $selectPesertasNonPegawai->id }}" tabindex="-1" aria-labelledby="tambah-nonPegawai_fasilitasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tambah-nonPegawai_fasilitasLabel">Tambah Fasilitas</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('/c_fasilitas') }}" method="post">
                    @csrf
                    <input type="hidden" value="{{ $kegiatan->id }}" name="data_perjadinkegiatan">
                    <input type="hidden" value="{{ $selectPesertasNonPegawai->id }}" name="pegawai_id">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" readonly>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="uraian_{{ $selectPesertasNonPegawai->id }}" class="form-label">Nama Fasilitas <span class="text-secondary small"></span><span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <select class="form-select" id="uraian_{{ $selectPesertasNonPegawai->id }}" name="uraian" required>
                                        <option value="" disabled selected>Pilih Jenis Fasilitas</option>
                                        <option value="Akomodasi Hotel">Akomodasi Hotel</option>
                                        <!-- <option value="BBM">BBM</option> -->
                                        <option value="Tiket Kereta">Tiket Kereta</option>
                                        <option value="Tiket Pesawat">Tiket Pesawat</option>
                                        <option value="Tiket Travel">Tiket Travel</option>
                                        <option value="Transportasi Online">Transportasi Online</option>
                                        <option value="Lainnya">Lainnya</option>-
                                        <!-- <option value="Tol">Tol</option> -->
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <input type="number" class="form-control" id="jumlah_{{ $selectPesertasNonPegawai->id }}" name="jumlah_frekuensi" placeholder="Jumlah" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <input type="text" class="form-control" id="satuan_{{ $selectPesertasNonPegawai->id }}" name="satuan" placeholder="Satuan" readonly>
                                    <input type="text" class="form-control mt-2" id="satuan_manual_{{ $selectPesertasNonPegawai->id }}" name="satuan_manual" placeholder="Satuan (Lainnya)" style="display: none;">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="pembayaran" class="form-label">Tipe Pembayaran<span class="text-danger">*</span></label>
                                    <select class="form-select" id="pembayaran_{{ $selectPesertasNonPegawai->id }}" name="tipe_pendanaan" required>
                                        <option value="Bayar diawal">Bayar diawal</option>
                                        <option value="Reimburse">Reimburse</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="keterangan" class="form-label">Keterangan<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="keterangan_{{ $selectPesertasNonPegawai->id }}" name="keterangan" placeholder="Tambahkan Keterangan" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif

<!-- Modal Lihat Fasilitas NonPegawai -->
@if ($selectPesertasNonPegawais->isNotEmpty())
@foreach ($selectPesertasNonPegawais as $selectPesertasNonPegawai)
<div class="modal fade" id="lihat-nonPegawai_fasilitas_{{ $selectPesertasNonPegawai->id }}" tabindex="-1" aria-labelledby="lihat-nonPegawai_fasilitasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="lihat-nonPegawai_fasilitasLabel">Daftar Fasilitas</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <input type="hidden" value="{{ $kegiatan->id }}" name="info_perjadinlangsung">
                <input type="hidden" value="{{ $selectPesertasNonPegawai->id }}" name="pegawai_id">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" readonly>
                    </div>
                    <div class="col-md-12">
                        <div class="mt-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Fasilitas</th>
                                        <th>Jumlah</th>
                                        <th>Satuan</th>
                                        <th>Tipe Pembayaran</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fasilitas as $fasilita)
                                    @if ($fasilita->data_perjadinkegiatan == $kegiatan->id && $fasilita->perangkat_acara == $selectPesertasNonPegawai->id)
                                    <tr>
                                        <td>{{ $fasilita->nama }}</td>
                                        <td>{{ $fasilita->jumlah_frekuensi }}</td>
                                        <td>{{ $fasilita->satuan }}</td>
                                        <td>{{ $fasilita->tipe_pendanaan }}</td>
                                        <td>{{ $fasilita->ket }}</td>
                                        <td>
                                            <form action="{{ url('/h_fasilitas_kegiatan/' . $fasilita->idKebutuhan) }}" method="post">
                                                @method('delete')
                                                @csrf
                                                <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                                <input type="hidden" value="{{ $fasilita->perangkat_acara }}" name="perangkatacara">
                                                <button type="submit" class="text-decoration-none btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data Fasilitas?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif

<script>
    // $(document).ready(function () {
    //     $('#example').DataTable({
    //         "destroy": true, // Hapus inisialisasi sebelumnya jika ada
    //         "order": [[0, "asc"]] // Urutkan kolom pertama secara ascending
    //     });
    // });

</script>


<script>
    $('.js-example-basic-single').select2({
        placeholder: 'Pilih Pegawai',
        dropdownParent: '#tambah_peserta'
    });

    $('.js-example-basic-single-2').select2({
        placeholder: 'Pilih Non-Pegawai',
        dropdownParent: '#non_pegawai'
    });

    $('.js-example-basic-single-3').select2({
        placeholder: 'Select an option',
        dropdownParent: '#tambah_sapras'
    });
</script>
<script>
    document.getElementById("myForm").addEventListener("submit", function(event) {
        var fileInput = document.getElementById("fileInput");
        if (fileInput.files.length > 0) {
            var fileSize = fileInput.files[0].size;
            var maxSize = 3 * 1024 * 1024 * 1024;
            if (fileSize > maxSize) {
                event.preventDefault();
                alert("Ukuran file tidak boleh lebih dari 2MB");
            }
        }
    });
</script>

<script>
    $(document).ready(function() {
        $(document).on('click', '.tambah-fasilitas', function() {
            var namaPegawai = $(this).data('nama');
            var pegawaiId = $(this).data('pegawai-id');
            var fasilitasTerpilih = $(this).data('fasilitas') || [];

            $('#tambah_fasilitas_' + pegawaiId + ' #nama').val(namaPegawai);

            $('#uraian_' + pegawaiId + ' option').prop('disabled', false);

            fasilitasTerpilih.forEach(function(fasilitas) {
                $('#uraian_' + pegawaiId + ' option[value="' + fasilitas.nama_fasilitas + '"]').prop('disabled', true);
            });

            $('#tambah_fasilitas_' + pegawaiId).on('click', '#addFacilityButton_' + pegawaiId, function() {
                var namaFasilitas = $('#uraian_' + pegawaiId + ' option:selected').text();
                var jumlahFasilitas = $('#jumlah_' + pegawaiId).val();
                var satuanFasilitas = $('#satuan_' + pegawaiId).val() || $('#satuan_manual_' + pegawaiId).val();
                var pembayaranFasilitas = $('#pembayaran_' + pegawaiId).val();
                var keteranganFasilitas = $('#keterangan_' + pegawaiId).val();
                var selectedValue = $('#uraian_' + pegawaiId).val();
                var uniqueId = Date.now();

                if (namaFasilitas && jumlahFasilitas && satuanFasilitas && pembayaranFasilitas && keteranganFasilitas) {
                    $('#facilityTable_' + pegawaiId + ' tbody').append(`
                    <tr data-unique-id="${uniqueId}">
                        <td>${namaFasilitas}</td>
                        <td>${jumlahFasilitas}</td>
                        <td>${satuanFasilitas}</td>
                        <td>${pembayaranFasilitas}</td>
                        <td>${keteranganFasilitas}</td>
                        <td>
                            <button class="btn btn-danger btn-sm deleteFacilityButton" style="color: #FFFFFF;" onclick="return confirm('Hapus Data Fasilitas?')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);

                    $('#uraian_' + pegawaiId + ' option[value="' + selectedValue + '"]').prop('disabled', true);
                    $('#uraian_' + pegawaiId).val('');
                    $('#jumlah_' + pegawaiId).val('');
                    $('#pembayaran_' + pegawaiId).val("Bayar diawal");
                    $('#keterangan_' + pegawaiId).val('');
                    $('#satuan_' + pegawaiId).val('');
                    $('#satuan_manual_' + pegawaiId).val('');
                    $('#satuan_manual_' + pegawaiId).hide();
                }
            });

            $('#tambah_fasilitas_' + pegawaiId).on('click', '.deleteFacilityButton', function() {
                var row = $(this).closest('tr');
                var fasilitasName = row.find('td:first').text();
                var uniqueId = row.data('unique-id');

                $('#uraian_' + pegawaiId + ' option:contains("' + fasilitasName + '")').prop('disabled', false);
                row.remove();
                $('#lihat_fasilitas_' + pegawaiId + ' tr[data-unique-id="' + uniqueId + '"]').remove();
            });

            $('#tambah_fasilitas_' + pegawaiId).on('change', '#uraian_' + pegawaiId, function() {
                var selectedValue = $(this).val();
                var satuan = '';

                switch (selectedValue) {
                    case 'Akomodasi Hotel':
                        satuan = 'Kamar';
                        break;
                    case 'BBM':
                        satuan = 'Kali Pengisian';
                        break;
                    case 'Tiket Kereta':
                    case 'Tiket Pesawat':
                    case 'Tiket Travel':
                        satuan = 'Tiket';
                        break;
                    case 'Transportasi Online':
                        satuan = 'Kali Perjalanan';
                        break;
                    case 'Tol':
                        satuan = 'Kali Pengisian';
                        break;
                    case 'Lainnya':
                        satuan = '';
                        $('#satuan_manual_' + pegawaiId).show();
                        $('#satuan_' + pegawaiId).hide();
                        break;
                    default:
                        satuan = '';
                        $('#satuan_manual_' + pegawaiId).hide();
                        $('#satuan_' + pegawaiId).show();
                }

                $('#satuan_' + pegawaiId).val(satuan);
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $(document).on('click', '.lihat-fasilitas', function() {
            var namaPegawai = $(this).data('nama');
            var pegawaiId = $(this).data('pegawai-id');
            var fasilitasTerpilih = $(this).data('fasilitas') || [];

            $('#lihat_fasilitas_' + pegawaiId + ' #nama').val(namaPegawai);

            $('#uraian_' + pegawaiId + ' option').prop('disabled', false);

            fasilitasTerpilih.forEach(function(fasilitas) {
                $('#uraian_' + pegawaiId + ' option[value="' + fasilitas.nama_fasilitas + '"]').prop('disabled', true);
            });

            $('#lihat_fasilitas_' + pegawaiId).on('click', '.deleteFacilityButton', function() {
                var row = $(this).closest('tr');
                var fasilitasName = row.find('td:first').text();
                var uniqueId = row.data('unique-id');

                $('#uraian_' + pegawaiId + ' option:contains("' + fasilitasName + '")').prop('disabled', false);
                row.remove();
                $('#tambah_fasilitas_' + pegawaiId + ' tr[data-unique-id="' + uniqueId + '"]').remove();
            });

            $('#lihat_fasilitas_' + pegawaiId).on('change', '#uraian_' + pegawaiId, function() {
                var selectedValue = $(this).val();
                var satuan = '';

                switch (selectedValue) {
                    case 'Akomodasi Hotel':
                        satuan = 'Kamar';
                        break;
                    case 'BBM':
                        satuan = 'Kali Pengisian';
                        break;
                    case 'Tiket Kereta':
                    case 'Tiket Pesawat':
                    case 'Tiket Travel':
                        satuan = 'Tiket';
                        break;
                    case 'Transportasi Online':
                        satuan = 'Kali Perjalanan';
                        break;
                    case 'Tol':
                        satuan = 'Kali Pengisian';
                        break;
                    default:
                        satuan = '';
                }

                $('#satuan_' + pegawaiId).val(satuan);
            });
        });
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
    function submitDraft() {
        const form = document.getElementById('myForm');
        form.action = "{{ url('/draft_kegiatan_all/' . $kegiatan->id) }}";
        form.submit();
    }
</script>

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
    $(document).ready(function() {
        $(document).on('click', '.tambah-nonPegawai-fasilitas', function() {
            var namaPegawai = $(this).data('nama');
            var pegawaiId = $(this).data('pegawai-id');
            var fasilitasTerpilih = $(this).data('fasilitas') || [];

            $('#tambah-nonPegawai_fasilitas_' + pegawaiId + ' #nama').val(namaPegawai);

            $('#uraian_' + pegawaiId + ' option').prop('disabled', false);

            fasilitasTerpilih.forEach(function(fasilitas) {
                $('#uraian_' + pegawaiId + ' option[value="' + fasilitas.nama_fasilitas + '"]').prop('disabled', true);
            });

            $('#tambah-nonPegawai_fasilitas_' + pegawaiId).on('click', '#addFacilityButton_' + pegawaiId, function() {
                var namaFasilitas = $('#uraian_' + pegawaiId + ' option:selected').text();
                var jumlahFasilitas = $('#jumlah_' + pegawaiId).val();
                var satuanFasilitas = $('#satuan_' + pegawaiId).val();
                var pembayaranFasilitas = $('#pembayaran_' + pegawaiId).val();
                var keteranganFasilitas = $('#keterangan_' + pegawaiId).val();
                var selectedValue = $('#uraian_' + pegawaiId).val();
                var uniqueId = Date.now();

                if (namaFasilitas && jumlahFasilitas && satuanFasilitas && pembayaranFasilitas && keteranganFasilitas) {
                    $('#facilityTable_' + pegawaiId + ' tbody').append(`
                        <tr data-unique-id="${uniqueId}">
                            <td>${namaFasilitas}</td>
                            <td>${jumlahFasilitas}</td>
                            <td>${satuanFasilitas}</td>
                            <td>${pembayaranFasilitas}</td>
                            <td>${keteranganFasilitas}</td>
                            <td>
                                <button class="btn btn-danger btn-sm deleteFacilityButton" style="color: #FFFFFF;" onclick="return confirm('Hapus Data Fasilitas?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `);

                    $('#uraian_' + pegawaiId + ' option[value="' + selectedValue + '"]').prop('disabled', true);
                    $('#uraian_' + pegawaiId).val('');
                    $('#jumlah_' + pegawaiId).val('');
                    $('#pembayaran_' + pegawaiId).val("Bayar diawal");
                    $('#keterangan_' + pegawaiId).val('');
                    $('#satuan_' + pegawaiId).val('');
                    $('#satuan_manual_' + pegawaiId).val('');
                    $('#satuan_manual_' + pegawaiId).hide();
                }
            });

            $('#tambah-nonPegawai_fasilitas_' + pegawaiId).on('click', '.deleteFacilityButton', function() {
                var row = $(this).closest('tr');
                var fasilitasName = row.find('td:first').text();
                var uniqueId = row.data('unique-id');

                $('#uraian_' + pegawaiId + ' option:contains("' + fasilitasName + '")').prop('disabled', false);
                row.remove();
                $('#lihat-nonPegawai_fasilitas_' + pegawaiId + ' tr[data-unique-id="' + uniqueId + '"]').remove();
            });

            $('#tambah-nonPegawai_fasilitas_' + pegawaiId).on('change', '#uraian_' + pegawaiId, function() {
                var selectedValue = $(this).val();
                var satuan = '';

                switch (selectedValue) {
                    case 'Akomodasi Hotel':
                        satuan = 'Kamar';
                        break;
                    case 'BBM':
                        satuan = 'Kali Pengisian';
                        break;
                    case 'Tiket Kereta':
                    case 'Tiket Pesawat':
                    case 'Tiket Travel':
                        satuan = 'Tiket';
                        break;
                    case 'Transportasi Online':
                        satuan = 'Kali Perjalanan';
                        break;
                    case 'Tol':
                        satuan = 'Kali Pengisian';
                        break;
                    case 'Lainnya':
                        satuan = '';
                        $('#satuan_manual_' + pegawaiId).show();
                        $('#satuan_' + pegawaiId).hide();
                        break;
                    default:
                        satuan = '';
                        $('#satuan_manual_' + pegawaiId).hide();
                        $('#satuan_' + pegawaiId).show();
                }

                $('#satuan_' + pegawaiId).val(satuan);
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $(document).on('click', '.lihat-nonPegawai-fasilitas', function() {
            var namaPegawai = $(this).data('nama');
            var pegawaiId = $(this).data('pegawai-id');
            var fasilitasTerpilih = $(this).data('fasilitas') || [];

            $('#lihat-nonPegawai_fasilitas_' + pegawaiId + ' #nama').val(namaPegawai);

            $('#uraian_' + pegawaiId + ' option').prop('disabled', false);

            fasilitasTerpilih.forEach(function(fasilitas) {
                $('#uraian_' + pegawaiId + ' option[value="' + fasilitas.nama_fasilitas + '"]').prop('disabled', true);
            });

            $('#lihat-nonPegawai_fasilitas_' + pegawaiId).on('click', '.deleteFacilityButton', function() {
                var row = $(this).closest('tr');
                var fasilitasName = row.find('td:first').text();
                var uniqueId = row.data('unique-id');

                $('#uraian_' + pegawaiId + ' option:contains("' + fasilitasName + '")').prop('disabled', false);
                row.remove();
                $('#tambah_fasilitas_' + pegawaiId + ' tr[data-unique-id="' + uniqueId + '"]').remove();
            });

            $('#lihat-nonPegawai_fasilitas_' + pegawaiId).on('change', '#uraian_' + pegawaiId, function() {
                var selectedValue = $(this).val();
                var satuan = '';

                switch (selectedValue) {
                    case 'Akomodasi Hotel':
                        satuan = 'Kamar';
                        break;
                    case 'BBM':
                        satuan = 'Kali Pengisian';
                        break;
                    case 'Tiket Kereta':
                    case 'Tiket Pesawat':
                    case 'Tiket Travel':
                        satuan = 'Tiket';
                        break;
                    case 'Transportasi Online':
                        satuan = 'Kali Perjalanan';
                        break;
                    case 'Tol':
                        satuan = 'Kali Pengisian';
                        break;
                    default:
                        satuan = '';
                }

                $('#satuan_' + pegawaiId).val(satuan);
            });
        });
    });
</script>

<script>
    /* Conditional Flihat Fasilitas*/
    const uraian = document.getElementById('uraian');
    const conditionalFieldsHotel = document.getElementById('conditionalFieldsHotel');
    const conditionalFieldsTiketTransportasi = document.getElementById('conditionalFieldsTiketTransportasi');
    const conditionalFieldsBBM = document.getElementById('conditionalFieldsBBM');
    const conditionalFieldsTol = document.getElementById('conditionalFieldsTol');
    const conditionalFieldsTransportasi_Online = document.getElementById('conditionalFieldsTransportasi_Online');

    $(document).ready(function() {


        // Listen for changes in the select element
        $('#uraian').change(function() {
            // Get the selected value
            var selectedValue = $(this).val();

            // Clear any existing content in conditional_fields
            $('#conditional_fields').empty();

            // Check the selected value and append elements accordingly
            if (selectedValue === 'Akomodasi Hotel') {
                // Append elements for 'Akomodasi Hotel'
                $('#conditional_fields').append(`
               <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="jumlah_kamar" class="form-label">Jumlah Kamar<span class="text-danger">*</span></label>
                    <input type="number" min="0" class="form-control" id="jumlah_kamar" name="jumlah_frekuensi" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Kamar" readonly>
                </div>
            </div>
                <div class="col-md-12 mb-3">
                  <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                  <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                      <option value="Dibayar di Awal" selected>Dibayar di Awal</option>
                      <option value="Reimburse">Reimburse</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-12 mb-3">
                  <label for="" class="form-label">Keterangan Fasilitas<span class="text-danger">*</span><span class="text-secondary small">Contoh : Tempat untuk penginapan)</span></label>
                  <input type="text" name="keterangan" id="" class="form-control" required>
                </div>
              </div>
            `);
            } else if (selectedValue === 'BBM') {
                // Append elements for 'BBM'
                $('#conditional_fields').append(`
            <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="jumlah_tiket" class="detail-fields">Jumlah Pengisian <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" min="0" aria-label="First name" class="form-control" name="jumlah_frekuensi">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Kali Pengisian" readonly>
                </div>
                        <div class="col-md-12 mb-3">
                            <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                                    <option value="Dibayar di Awal" selected>Dibayar di Awal</option>
                                    <option value="Reimburse">Reimburse</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Keterangan Fasilitas<span class="text-danger">*</span><span class="text-secondary small">Contoh : Perkiraan Harga)</span></label>
                            <input type="text" name="keterangan" id="" class="form-control" required>
                        </div>
                    </div>
            `);
            } else if (selectedValue === 'Tiket Kereta') {
                $('#conditional_fields').append(`
            <div class="row">
            <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="form-label">Jumlah Tiket<span class="text-danger">*</span></label>
                <input type="number" min="0" class="form-control" id="jumlah_tiket" name="jumlah_frekuensi" required>
            </div>
        </div>
        <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Tiket" readonly>
                </div>
        <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                        <option value="Dibayar di Awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
        </div>
        <div class="col-md-12 mb-3">
            <label for="" class="form-label">Keterangan Fasilitas<span class="text-danger">*</span><span class="text-secondary small">Contoh : Argo Parahyangan ( Gambir - Bandung )</span></label>
            <input type="text" name="keterangan" id="" class="form-control" required>
        </div>
            `);
            } else if (selectedValue === 'Tiket Pesawat') {
                $('#conditional_fields').append(`
            <div class="row">
            <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="form-label">Jumlah Tiket<span class="text-danger">*</span></label>
                <input type="number" min="0" class="form-control" id="jumlah_tiket" name="jumlah_frekuensi" required>
            </div>
        </div>
        <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Tiket" readonly>
                </div>
        <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                        <option value="Dibayar di Awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
        </div>
        <div class="col-md-12 mb-3">
            <label for="" class="form-label">Keterangan Fasilitas<span class="text-danger">*</span><span class="text-secondary small">Contoh : Lion Air ( Ambon - Jakarta )</span></label>
            <input type="text" name="keterangan" id="" class="form-control" required>
        </div>
            `);
            } else if (selectedValue === 'Tiket Travel') {
                $('#conditional_fields').append(`
            <div class="row">
            <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="form-label">Jumlah Tiket<span class="text-danger">*</span></label>
                <input type="number" min="0" class="form-control" id="jumlah_tiket" name="jumlah_frekuensi" required>
            </div>
        </div>
        <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Tiket" readonly>
                </div>
        <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                        <option value="Dibayar di Awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
        </div>
        <div class="col-md-12 mb-3">
            <label for="" class="form-label">Keterangan Fasilitas<span class="text-danger">*</span><span class="text-secondary small">Contoh : Arnes (Bandung - Cirebon)</span></label>
            <input type="text" name="keterangan" id="" class="form-control" required>
        </div>
            `);
            } else if (selectedValue === 'Transportasi Online') {
                $('#conditional_fields').append(`
            <div class="row">
            <div class="col-md-12 mb-3">
            <label for="jumlah_tiket" class="detail-fields">Jumlah Pejalanan <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" min="0" aria-label="First name" class="form-control" name="jumlah_frekuensi">
                </div>
            </div>
            <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Kali Perjalanan" readonly>
                </div>
            <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                        <option value="Dibayar di Awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <label for="" class="form-label">Keterangan Mobilitas<span class="text-danger">*</span><span class="text-secondary small">(Contoh :Bandung - Garut)</span></label>
                <input type="text" name="keterangan" id="" class="form-control" required>
            </div>
        </div>
            `);
            } else if (selectedValue === 'Tol') {
                $('#conditional_fields').append(`
            <div class="row">
            <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="detail-fields">Jumlah Pengisian <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" min="0" aria-label="First name" class="form-control" name="jumlah_frekuensi">
                </div>
            </div>
            <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Kali Pengisian" readonly>
                </div>
            <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                        <option value="Dibayar di Awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <label for="" class="form-label">Keterangan Fasilitas <span class="text-danger">*</span><span class="text-secondary small">(Contoh : Perkiraan Harga)</span></label>
                <input type="text" name="keterangan" id="" class="form-control" required>
            </div>
        </div>
            `);
            }
        });
    });
</script>
@endsection
