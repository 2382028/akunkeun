@extends('user.templates.template')

@section('content')

<!-- Awal Form Perjadin Biasa  -->
<section id="beranda" class=" pb-5 mt-5 pt-5">
    <div class="container">
        <div class="row text-secondary justify-content-center">
            <div class="col-md-10 mb-3">
                <div class="row mb-3">
                    <h3 class="fw-bold text-secondary">Pengajuan Perjalanan Dinas</h3>
                </div>
                <div class="card shadow rounded-0  border-0 p-3" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    <div class="card-body">
                        <!-- Progress bar -->
                        <div class="progressbar col-md-6 mx-auto">
                            <div class="progress" id="progress"></div>

                            <div class="progress-step" data-title="Informasi Dasar">1</div>
                            <div class="progress-step progress-step-active" data-title="Informasi Peserta">2</div>
                        </div>

                        <!-- Step 2 -->
                        <div class="mb-3">
                            <div class="mb-3 row text-secondary">
                                <div class="col-md-12">
                                    <div class="card shadow rounded-0  border-0">
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-md-4 mb-3">Judul Surat Undangan</div>
                                                <input id="" type="hidden" value="{{ $perjadin->id }}">
                                                <div class="col-md-4 mb-3"> {{ $perjadin->nama_kegiatan }} </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4 mb-3">Tanggal Pelaksanaan</div>
                                                <div class="col-md-4 mb-3"> {{ $perjadin->tgl_mulai }} </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4 mb-3">Lokasi</div>
                                                <div class="col-md-4 mb-3"> {{ $perjadin->alamat }} </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="container" style="height: 3px; background-color: #fda10d; opacity: 1; border-color: #fda10d">
                            {{-- informasi Peserta --}}
                            <div class="row mb-3 text-secondary">
                                <div class="col-md-12 mb-3">
                                    <div class="card shadow rounded-0  border-0">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h6 class="fw-bold text-secondary">Informasi Peserta</h6><br>
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
                                                            <th class="th-md">Nama (Pegawai)</th>
                                                            <th class="th-md">Pangkat/Golongan</th>
                                                            <th class="th-lg-percent">Status</th>
                                                            <th class="th-md">Fasilitas</th>
                                                            <th class="th-lg-percent">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    @if ($selectPesertas->isNotEmpty())
                                                    @foreach ($selectPesertas as $selectPeserta)
                                                     <tr>
                                                        <td class='text-center'>{{$loop->iteration }}</td>
                                                        <td class=''>{{ $selectPeserta->nama_lengkap }}</td>
                                                        <td class='text-center'>{{ $selectPeserta->golongan }}-{{ $selectPeserta->pangkat }}</td>
                                                        <td class='text-center'>{{$selectPeserta->status_pegawai}}</td>
                                                        <td class='text-center justify-content-center'>
                                                            <div class="d-inline-block">
                                                                <button class="btn btn-primary text-white mb-3 lihat-fasilitas"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#lihat_fasilitas_{{$selectPeserta->id}}"
                                                                    data-nama="{{ $selectPeserta->nama_lengkap }}"
                                                                    data-pegawai-id="{{ $selectPeserta->id }}"
                                                                    type="button">
                                                                    <i class="fa fa-eye"></i>
                                                                </button>
                                                            </div>
                                                            <div class="d-inline-block">
                                                                <button class="btn btn-neon text-white mb-3 tambah-fasilitas"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#tambah_fasilitas_{{$selectPeserta->id}}"
                                                                    data-nama="{{ $selectPeserta->nama_lengkap }}"
                                                                    data-pegawai-id="{{ $selectPeserta->id }}"
                                                                    type="button">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </td>

                                                        <td class='text-center justify-content-center'>

                                                            <form action="{{url('/h_peserta/' . $selectPeserta->id)}}" method="post">
                                                                @method('delete')
                                                                @csrf
                                                                <input id="" type="hidden" value="{{ $perjadin->id }}" name="info_perjadinlangsung">
                                                                <input id="" type="hidden" value="{{ $selectPeserta->pegawai_id }}" name="peserta">
                                                                <span>
                                                                    <button class="text-decoration-none btn btn-danger btn-sm" style="color: #FFFFFF; margin-left: 5px;" onclick="return confirm('Hapus Data Peserta?')"><i class="fa-solid fa-trash"></i></button>
                                                                </span>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    @endif
                                                </table>
                                                <hr class="container" style="height: 3px; background-color: #fda10d; opacity: 1; border-color: #fda10d">
                                                @if ($selectPesertasNonPegawais->isNotEmpty())
                                                <table id="example" class="table table-bordered data-table mb-3" style="width: 100%">
                                                    <thead>
                                                        <tr class="small text-center">
                                                            <th class="th-sm">No</th>
                                                            <th class="th-md">Nama (Non-Pegawai)</th>
                                                            <th class="th-md">Pangkat/Golongan</th>
                                                            <th class="th-lg-percent">Status</th>
                                                            <th class="th-md">Fasilitas</th>
                                                            <th class="th-lg-percent">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    @foreach ($selectPesertasNonPegawais as $selectPesertasNonPegawai)
                                                    <tr>
                                                        <td class='text-center'>{{$loop->iteration }}</td>
                                                        <td class=''>{{ $selectPesertasNonPegawai->nama_lengkap }}</td>
                                                        <td class='text-center'>{{ $selectPesertasNonPegawai->golongan }}-{{ $selectPesertasNonPegawai->pangkat }}</td>
                                                        <td class='text-center'>{{ $selectPesertasNonPegawai->status_pegawai }}</td>
                                                        <td class='text-center justify-content-center'>
                                                            <div class="d-inline-block">
                                                                <button class="btn btn-primary text-white mb-3 lihat-nonPegawai-fasilitas"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#lihat-nonPegawai_fasilitas_{{$selectPesertasNonPegawai->id}}"
                                                                    data-nama="{{ $selectPesertasNonPegawai->nama_lengkap }}"
                                                                    data-pegawai-id="{{ $selectPesertasNonPegawai->id }}"
                                                                    type="button">
                                                                    <i class="fa fa-eye"></i>
                                                                </button>
                                                            </div>
                                                            <div class="d-inline-block">
                                                                <button class="btn btn-neon text-white mb-3 tambah-nonPegawai-fasilitas"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#tambah-nonPegawai_fasilitas_{{$selectPesertasNonPegawai->id}}"
                                                                    data-nama="{{ $selectPesertasNonPegawai->nama_lengkap }}"
                                                                    data-pegawai-id="{{ $selectPesertasNonPegawai->id }}"
                                                                    type="button">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                        <td class='text-center'>
                                                            <form action="{{url('/h_peserta/' . $selectPesertasNonPegawai->id)}}" method="post">
                                                                @method('delete')
                                                                @csrf
                                                                <input id="" type="hidden" value="{{ $perjadin->id }}" name="info_perjadinlangsung">
                                                                <span>
                                                                    <button class="text-decoration-none btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data Peserta?')"><i class="fa-solid fa-trash"></i></button>
                                                                </span>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </table>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="container" style="height: 3px; background-color: #fda10d; opacity: 1; border-color: #fda10d">
                            {{-- informasi Fasilitas --}}
                            <div class="row mb-3 text-secondary">
                                <div class="col-md-12 mb-3">
                                    <div class="card shadow rounded-0  border-0">
                                        <div class="card-body">
                                            @if ($fasilitas->isNotEmpty())
                                            <div class="table-responsive">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h6 class="fw-bold text-secondary">Fasilitas Yang Diperlukan</h6><br>
                                                    </div>
                                                    <div>
                                                        <button class="btn btn-neon text-white mb-3" data-bs-toggle="modal" data-bs-target="#tambah_fasilitas" type="button">
                                                            <i class="fa fa-plus"></i> Tambah Fasilitas
                                                        </button>
                                                    </div>
                                                </div>
                                                <table id="example" class="table table-bordered data-table mb-3" style="width: 100%">
                                                    <thead>
                                                        <tr class="small text-center">
                                                            <th class="th-sm">No</th>
                                                            <th class="th-md">Uraian Fasilitas</th>
                                                            <th class="th-md">Jumlah Kebutuhan</th>
                                                            <th class="th-md">Satuan</th>
                                                            <th class="th-md">Tipe Pendanaan</th>
                                                            <th class="th-md">Keterangan</th>
                                                            <th class="th-lg-percent">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    @foreach ($fasilitas as $fasilita)
                                                    <tr>
                                                        <td class='text-center'>{{ $loop->iteration }}</td>
                                                        <td>{{ $fasilita->nama }}</td>
                                                        <td class='text-center'>{{ $fasilita->jumlah_frekuensi }}</td>
                                                        <td class='text-center'>{{ $fasilita->satuan }}</td>
                                                        <td class='text-center'>{{ $fasilita->tipe_pendanaan }}</td>
                                                        <td class='text-center'>{{ $fasilita->ket }}</td>
                                                        <td class="text-center">
                                                            <form action="{{url('/h_fasilitas_perjadin/' . $fasilita->idKebutuhan)}}" method="post">
                                                                @method('delete')
                                                                @csrf
                                                                <input id="" type="hidden" value="{{ $perjadin->id }}" name="info_perjadinlangsung">
                                                                <span>
                                                                    <button class="text-decoration-none btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data Peserta?')"><i class="fa-solid fa-trash"></i></button>
                                                                </span>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </table>
                                            </div>
                                            @else
                                            <div class="text-center">
                                                <button class="btn btn-neon text-white mb-3" data-bs-toggle="modal" data-bs-target="#tambah_fasilitas" type="button">
                                                    <i class="fa fa-plus"></i> Tambah Fasilitas
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="container" style="height: 3px; background-color: #fda10d; opacity: 1; border-color: #fda10d">
                            {{-- Dokumen Pendukung --}}
                            <form id="myForm" action="{{url('/c_perjadin')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input id="" type="hidden" value="{{ $perjadin->id }}" name="info_perjadinlangsung">
                                <div class="row mb-3 text-secondary">
                                    <div class="col-md-12 mb-3">
                                        <div class="card shadow rounded-0  border-0">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between mb-3">
                                                    <div>
                                                        <h6 class="fw-bold text-secondary">Dokumen Pendukung</h6>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">

                                                    <div class="col-md-4 mb-3">
                                                        <label for="" class="form-label">Unggah Surat Undangan / Memo </span><span class="text-danger">*</span></label>
                                                        <input type="file" name="surat_undangan" id="" class="form-control" accept="application/pdf">
                                                        @error('surat_undangan')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="btns-group d-flex justify-content-evenly pb-3 mt-5">
                                    <a href="javascript:history.back()" class="btn btn-back btn-secondary col-md-5">Kembali</a>
                                    <button class="btn btn-next btn-primary col-md-5" type="submit">Kirim</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah Peserta -->
<div class="modal fade" id="tambah_peserta" tabindex="-1" aria-labelledby="tambah_pesertaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tambah_pesertaLabel">Tambah Peserta</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{url('/c_peserta')}}" method="post" onsubmit="return validateForm()">
                    @csrf
                    <input id="" type="hidden" value="{{ $perjadin->id }}" name="info_perjadinlangsung">
                    <input id="" type="hidden" value="{{ $perjadin->tgl_keberangkatan }}" name="berangkat">
                    <input id="" type="hidden" value="{{ $perjadin->tgl_selesai }}" name="selesai">
                    <div class="row">
                        <div class="col-md-12 mb-3 tab-pane fade show active required-select">
                            <label for="" class="form-label">Nama Pegawai <a href="" data-bs-target="#non_pegawai" data-bs-toggle="modal" data-bs-dismiss="modal" aria-label="Close">[Tambah Non-Pegawai]</a></label>
                            <select class="" name="peserta_pegawai" style="width: 100%;">
                                <option value="">Pilih Pegawai</option>
                                @foreach ($pegawais as $pegawai)

                                <option value="{{ $pegawai->id }}">{{ $pegawai->nama_lengkap }}</option>

                                @endforeach
                            </select>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
                <button type="submit"  name="action"  value="pegawai_id" class="btn btn-primary">Simpan</button>
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
                <form action="{{url('/c_non_peserta')}}" method="post" onsubmit="return validateDropdown()">
                    @csrf
                    <input id="" type="hidden" value="{{ $perjadin->id }}" name="info_perjadinlangsung">
                    <div class="col-md-12 mb-3 required-non">
                        <label for="pegawaiDropdown" class="form-label">Nama Non-Pegawai</label>
                        <select id="pegawaiDropdown" name="peserta_non_pegawai" style="width: 100%;">
                            <option value="">Pilih Non-Pegawai</option>
                            @foreach ($nonpegawais as $nonpegawai)
                            <option value="{{ $nonpegawai->id }}">{{ $nonpegawai->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="buttonContainer" class="mb-3">
                        <button id="triggerButton" class="btn btn-primary btn-sm">Tambah Non-Pegawai</button>
                    </div>
                    <div id="formContainer" class="collapse">
                        <div class="card card-body">
                            <div class="row accordion accordion-flush" id="accordionFlushExample">
                                <div class="accordion-item">
                                    <h5 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                            Tambah Baru
                                        </button>
                                    </h5>
                                    <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body">
                                            {{-- input non pegawai --}}
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label for="" class="form-label">Nama Lengkap</label>
                                                    <input type="text" class="form-control" id="" name="nama_lengkap">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label for="" class="form-label">NIP/NIK</label>
                                                    <input type="text" class="form-control" id="" name="NIP_NIK">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label for="" class="form-label">Pangkat</label>
                                                    <input type="text" class="form-control" name="pangkat">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label for="" class="form-label">Golongan</label>
                                                    <input type="text" class="form-control" name="golongan">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
    </form>
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
                <form action="{{ url('/c_fasilitasDetail') }}" method="post">
                    @csrf
                    <input type="hidden" value="{{ $perjadin->id }}" name="info_perjadinlangsung">
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
                                        <option value="BBM">BBM</option>
                                        <option value="Tiket Kereta">Tiket Kereta</option>
                                        <option value="Tiket Pesawat">Tiket Pesawat</option>
                                        <option value="Tiket Travel">Tiket Travel</option>
                                        <option value="Transportasi Online">Transportasi Online</option>
                                        <option value="Tol">Tol</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <input type="number" class="form-control" id="jumlah_{{ $selectPeserta->id }}" name="jumlah" placeholder="Jumlah" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <input type="text" class="form-control" id="satuan_{{ $selectPeserta->id }}" name="satuan" placeholder="Satuan" readonly>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="pembayaran" class="form-label">Tipe Pembayaran<span class="text-danger">*</span></label>
                                    <select class="form-select" id="pembayaran_{{ $selectPeserta->id }}" name="pembayaran" required>
                                        <option value="Bayar diawal">Bayar diawal</option>
                                        <option value="Reimburse">Reimburse</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="keterangan" class="form-label">Keterangan<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="keterangan_{{ $selectPeserta->id }}" name="keterangan" placeholder="Tambahkan Keterangan" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <button id="addFacilityButton_{{ $selectPeserta->id }}" class="btn btn-neon text-white" type="button">Tambahkan</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div id="facilityTable_{{ $selectPeserta->id }}" class="mt-3">
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
                                        <!-- Tempat untuk menampilkan fasilitas yang ditambahkan -->
                                    </tbody>
                                </table>
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

<!-- Modal Lihat Fasilitas -->
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
                <form action="{{ url('/c_fasilitasDetail') }}" method="post">
                    @csrf
                    <input type="hidden" value="{{ $perjadin->id }}" name="info_perjadinlangsung">
                    <input type="hidden" value="{{ $selectPeserta->id }}" name="pegawai_id">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" readonly>
                        </div>
                        <div class="col-md-12">
                            <div id="facilityTable_{{ $selectPeserta->id }}" class="mt-3">
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
                                        <!-- Tempat untuk menampilkan fasilitas yang ditambahkan -->
                                    </tbody>
                                </table>
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
                <form action="{{ url('/c_fasilitasDetail') }}" method="post">
                    @csrf
                    <input type="hidden" value="{{ $perjadin->id }}" name="info_perjadinlangsung">
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
                                        <option value="BBM">BBM</option>
                                        <option value="Tiket Kereta">Tiket Kereta</option>
                                        <option value="Tiket Pesawat">Tiket Pesawat</option>
                                        <option value="Tiket Travel">Tiket Travel</option>
                                        <option value="Transportasi Online">Transportasi Online</option>
                                        <option value="Tol">Tol</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <input type="number" class="form-control" id="jumlah_{{ $selectPesertasNonPegawai->id }}" name="jumlah" placeholder="Jumlah" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <input type="text" class="form-control" id="satuan_{{ $selectPesertasNonPegawai->id }}" name="satuan" placeholder="Satuan" readonly>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="pembayaran" class="form-label">Tipe Pembayaran<span class="text-danger">*</span></label>
                                    <select class="form-select" id="pembayaran_{{ $selectPesertasNonPegawai->id }}" name="pembayaran" required>
                                        <option value="Bayar diawal">Bayar diawal</option>
                                        <option value="Reimburse">Reimburse</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="keterangan" class="form-label">Keterangan<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="keterangan_{{ $selectPesertasNonPegawai->id }}" name="keterangan" placeholder="Tambahkan Keterangan" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <button id="addFacilityButton_{{ $selectPesertasNonPegawai->id }}" class="btn btn-neon text-white" type="button">Tambahkan</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div id="facilityTable_{{ $selectPesertasNonPegawai->id }}" class="mt-3">
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
                                        <!-- Tempat untuk menampilkan fasilitas yang ditambahkan -->
                                    </tbody>
                                </table>
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
                <form action="{{ url('/c_fasilitasDetail') }}" method="post">
                    @csrf
                    <input type="hidden" value="{{ $perjadin->id }}" name="info_perjadinlangsung">
                    <input type="hidden" value="{{ $selectPesertasNonPegawai->id }}" name="pegawai_id">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" readonly>
                        </div>
                        <div class="col-md-12">
                            <div id="facilityTable_{{ $selectPesertasNonPegawai->id }}" class="mt-3">
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
                                        <!-- Tempat untuk menampilkan fasilitas yang ditambahkan -->
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
    $(document).ready(function () {
        $(document).on('click', '.tambah-fasilitas', function () {
            var namaPegawai = $(this).data('nama');
            var pegawaiId = $(this).data('pegawai-id');
            var fasilitasTerpilih = $(this).data('fasilitas') || [];

            $('#tambah_fasilitas_' + pegawaiId + ' #nama').val(namaPegawai);

            $('#uraian_' + pegawaiId + ' option').prop('disabled', false);

            fasilitasTerpilih.forEach(function(fasilitas) {
                $('#uraian_' + pegawaiId + ' option[value="' + fasilitas.nama_fasilitas + '"]').prop('disabled', true);
            });

            $('#tambah_fasilitas_' + pegawaiId).on('click', '#addFacilityButton_' + pegawaiId, function () {
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
                }
            });

            $('#tambah_fasilitas_' + pegawaiId).on('click', '.deleteFacilityButton', function () {
                var row = $(this).closest('tr');
                var fasilitasName = row.find('td:first').text();
                var uniqueId = row.data('unique-id');

                $('#uraian_' + pegawaiId + ' option:contains("' + fasilitasName + '")').prop('disabled', false);
                row.remove();
                $('#lihat_fasilitas_' + pegawaiId + ' tr[data-unique-id="' + uniqueId + '"]').remove();
            });

            $('#tambah_fasilitas_' + pegawaiId).on('change', '#uraian_' + pegawaiId, function () {
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
    $(document).ready(function () {
        $(document).on('click', '.lihat-fasilitas', function () {
            var namaPegawai = $(this).data('nama');
            var pegawaiId = $(this).data('pegawai-id');
            var fasilitasTerpilih = $(this).data('fasilitas') || [];

            $('#lihat_fasilitas_' + pegawaiId + ' #nama').val(namaPegawai);

            $('#uraian_' + pegawaiId + ' option').prop('disabled', false);

            fasilitasTerpilih.forEach(function(fasilitas) {
                $('#uraian_' + pegawaiId + ' option[value="' + fasilitas.nama_fasilitas + '"]').prop('disabled', true);
            });

            $('#lihat_fasilitas_' + pegawaiId).on('click', '.deleteFacilityButton', function () {
                var row = $(this).closest('tr');
                var fasilitasName = row.find('td:first').text();
                var uniqueId = row.data('unique-id');

                $('#uraian_' + pegawaiId + ' option:contains("' + fasilitasName + '")').prop('disabled', false);
                row.remove();
                $('#tambah_fasilitas_' + pegawaiId + ' tr[data-unique-id="' + uniqueId + '"]').remove();
            });

            $('#lihat_fasilitas_' + pegawaiId).on('change', '#uraian_' + pegawaiId, function () {
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
    $(document).ready(function () {
        $(document).on('click', '.tambah-nonPegawai-fasilitas', function () {
            var namaPegawai = $(this).data('nama');
            var pegawaiId = $(this).data('pegawai-id');
            var fasilitasTerpilih = $(this).data('fasilitas') || [];

            $('#tambah-nonPegawai_fasilitas_' + pegawaiId + ' #nama').val(namaPegawai);

            $('#uraian_' + pegawaiId + ' option').prop('disabled', false);

            fasilitasTerpilih.forEach(function(fasilitas) {
                $('#uraian_' + pegawaiId + ' option[value="' + fasilitas.nama_fasilitas + '"]').prop('disabled', true);
            });

            $('#tambah-nonPegawai_fasilitas_' + pegawaiId).on('click', '#addFacilityButton_' + pegawaiId, function () {
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
                }
            });

            $('#tambah-nonPegawai_fasilitas_' + pegawaiId).on('click', '.deleteFacilityButton', function () {
                var row = $(this).closest('tr');
                var fasilitasName = row.find('td:first').text();
                var uniqueId = row.data('unique-id');

                $('#uraian_' + pegawaiId + ' option:contains("' + fasilitasName + '")').prop('disabled', false);
                row.remove();
                $('#lihat-nonPegawai_fasilitas_' + pegawaiId + ' tr[data-unique-id="' + uniqueId + '"]').remove();
            });

            $('#tambah-nonPegawai_fasilitas_' + pegawaiId).on('change', '#uraian_' + pegawaiId, function () {
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
    $(document).ready(function () {
        $(document).on('click', '.lihat-nonPegawai-fasilitas', function () {
            var namaPegawai = $(this).data('nama');
            var pegawaiId = $(this).data('pegawai-id');
            var fasilitasTerpilih = $(this).data('fasilitas') || [];

            $('#lihat-nonPegawai_fasilitas_' + pegawaiId + ' #nama').val(namaPegawai);

            $('#uraian_' + pegawaiId + ' option').prop('disabled', false);

            fasilitasTerpilih.forEach(function(fasilitas) {
                $('#uraian_' + pegawaiId + ' option[value="' + fasilitas.nama_fasilitas + '"]').prop('disabled', true);
            });

            $('#lihat-nonPegawai_fasilitas_' + pegawaiId).on('click', '.deleteFacilityButton', function () {
                var row = $(this).closest('tr');
                var fasilitasName = row.find('td:first').text();
                var uniqueId = row.data('unique-id');

                $('#uraian_' + pegawaiId + ' option:contains("' + fasilitasName + '")').prop('disabled', false);
                row.remove();
                $('#tambah_fasilitas_' + pegawaiId + ' tr[data-unique-id="' + uniqueId + '"]').remove();
            });

            $('#lihat-nonPegawai_fasilitas_' + pegawaiId).on('change', '#uraian_' + pegawaiId, function () {
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
