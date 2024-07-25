@extends('user.templates.template')

@section('content')
<!-- Awal Form Perjadin Biasa  -->
<section id="beranda" class=" pb-5 mt-5 pt-5">
    <div class="container">
        <div class="row text-secondary justify-content-center">
            <div class="col-lg-12 mb-3">
                <div class="row mb-3">
                    <h3 class="fw-bold text-secondary">Kegiatanku | {{ $perjadin->status_pengajuan }} Perjalanan Dinas</h3>
                </div>
                <div class="card shadow-sm rounded-0  border-0 p-3" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    <div class="card-body">
                        <!-- Step 2 -->
                        <div class="mb-3">
                            <div class="mb-3 row text-secondary">
                                <div class="col-md-12">
                                    <div class="card shadow rounded-0  border-0">
                                        <div class="card-body lh-1">
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
                                            <div class="row mb-3">
                                                <div class="col-md-4 mb-3">Nomor Surat Tugas</div>
                                                <div class="col-md-4 mb-3"> {{ $perjadin->kode_surat_tugas }} </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4 mb-3">Status Pengajuan</div>
                                                <div class="col-md-4 mb-3"> {{ $perjadin->status_pengajuan_detail }} </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($mobilitass->isNotEmpty())
                            {{-- informasi mobilitas --}}
                            <div class="row mb-3 text-secondary">
                                <div class="col-md-12 mb-3">
                                    <div class="card shadow rounded-0  border-0">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h6 class="fw-bold text-secondary">Informasi Mobilitas</h6><br>
                                                    </div>
                                                </div>
                                                <table id="example" class="table table-bordered data-table mb-3" style="width: 100%">
                                                    <thead>
                                                        <tr class="small text-center">
                                                            <th class="th-sm">No</th>
                                                            <th class="th-md">Pengemudi</th>
                                                            <th class="">Mobil</th>
                                                            <th class="th-lg">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($mobilitass as $mobilitas)
                                                        <tr>
                                                            <td class='text-center'>{{$loop->iteration}}</td>
                                                            <td>{{$mobilitas->nama_lengkap}}</td>
                                                            <td>{{$mobilitas->merek}} [{{$mobilitas->no_polisi}}]</td>
                                                            <td class='text-center'>{{$mobilitas->status}}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
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
                                                        @if (($perjadin->status_pengajuan == 'Draf-pengajuan') | ($perjadin->status_pengajuan == 'pengajuan') | ($perjadin->status_pengajuan == 'revisi'))
                                                        <button class="btn btn-neon text-white mb-3" data-bs-toggle="modal" data-bs-target="#tambah_peserta" type="button" @if ($perjadin->status_pengajuan == 'pengajuan' || $perjadin->status_pengajuan == 'proses') disabled @endif>
                                                            <i class="fa fa-plus"></i> Tambah Peserta
                                                        </button>
                                                        @endif
                                                    </div>
                                                </div>
                                                <table id="example" class="table table-bordered data-table mb-3" style="width: 100%">
                                                    <thead>
                                                        <tr class="small text-center">
                                                            <th class="th-sm">No</th>
                                                            <th class="th-md">Nama (Pegawai)</th>
                                                            <th class="">Pangkat/Golongan</th>
                                                            <th class="th-sm">Status</th>
                                                            <th class="th-lg-percent">Persetujuan</th>
                                                            @if (($perjadin->status_pengajuan == 'Draf-pengajuan') | ($perjadin->status_pengajuan == 'pengajuan') | ($perjadin->status_pengajuan == 'revisi'))
                                                            <th class="th-lg-percent">Aksi</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    @foreach ($selectPesertas as $selectPeserta)
                                                    <tr>
                                                        <td class='text-center'>{{$loop->iteration }}</td>
                                                        <td class=''>{{ $selectPeserta->nama_lengkap }}</td>
                                                        <td class='text-center'>{{ $selectPeserta->golongan }}-{{ $selectPeserta->pangkat }}</td>
                                                        <td class='text-center'>{{ $selectPeserta->status_pegawai }}</td>
                                                        <td class='text-center'>{{ $selectPeserta->status_persetujuan }}</td>
                                                        @if (($perjadin->status_pengajuan == 'Draf-pengajuan') | ($perjadin->status_pengajuan == 'pengajuan') | ($perjadin->status_pengajuan == 'revisi'))
                                                        <td class='text-center'>
                                                            <form action="{{url('/h_peserta_peserta_detail/' . $selectPeserta->idPeserta)}}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="info_perjadinlangsung" value="{{ $perjadin->id }}">
                                                                <span>
                                                                    <button type="submit" class="text-decoration-none btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data Peserta?')" @if ($perjadin->status_pengajuan == 'pengajuan' || $perjadin->status_pengajuan == 'proses') disabled @endif><i class="fa-solid fa-trash"></i></button>
                                                                </span>
                                                            </form>
                                                        </td>
                                                        @endif
                                                    </tr>
                                                    @endforeach
                                                </table>
                                                <hr class="container" style="height: 2px; background-color: #fda10d; opacity: 1; border-color: #fda10d">
                                                @if ($selectPesertasNonPegawais->isNotEmpty())
                                                <table id="example" class="table table-bordered data-table mb-3" style="width: 100%">
                                                    <thead>
                                                        <tr class="small text-center">
                                                            <th class="th-sm">No</th>
                                                            <th class="th-md">Nama (Non-Pegawai)</th>
                                                            <th class="th-md">Pangkat/Golongan</th>
                                                            <th class="th-md">Status</th>
                                                            <th class="th-lg-percent">Persetujuan</th>
                                                            @if (($perjadin->status_pengajuan == 'Draf-pengajuan') | ($perjadin->status_pengajuan == 'pengajuan') | ($perjadin->status_pengajuan == 'revisi'))
                                                            <th class="th-lg-percent">Aksi</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    @foreach ($selectPesertasNonPegawais as $selectPesertasNonPegawai)
                                                    <tr>
                                                        <td class='text-center'>{{$loop->iteration }}</td>
                                                        <td class=''>{{ $selectPesertasNonPegawai->nama_lengkap }}</td>
                                                        <td class='text-center'>{{ $selectPesertasNonPegawai->golongan }}-{{ $selectPesertasNonPegawai->pangkat }}</td>
                                                        <td class='text-center'>{{ $selectPesertasNonPegawai->status_pegawai }}</td>
                                                        <td class='text-center'>{{ $selectPesertasNonPegawai->status_persetujuan }}</td>
                                                        @if (($perjadin->status_pengajuan == 'Draf-pengajuan') | ($perjadin->status_pengajuan == 'pengajuan') | ($perjadin->status_pengajuan == 'revisi'))
                                                        <td class='text-center'>
                                                            <form action="{{url('/h_peserta_nonpeserta_detail/' . $selectPesertasNonPegawai->idPeserta)}}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="info_perjadinlangsung" value="{{ $perjadin->id }}">
                                                                <span>
                                                                    <button type="submit" class="text-decoration-none btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data Peserta?')" @if ($perjadin->status_pengajuan == 'pengajuan' || $perjadin->status_pengajuan == 'proses') disabled @endif><i class="fa-solid fa-trash"></i></button>
                                                                </span>
                                                            </form>
                                                        </td>
                                                        @endif
                                                    </tr>
                                                    @endforeach
                                                </table>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- informasi Fasilitas --}}
                            @if (($perjadin->status_pengajuan == 'Draf-pengajuan') | ($perjadin->status_pengajuan == 'pengajuan') | ($perjadin->status_pengajuan == 'revisi'))
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
                                                        @if (($perjadin->status_pengajuan == 'Draf-pengajuan') | ($perjadin->status_pengajuan == 'pengajuan') | ($perjadin->status_pengajuan == 'revisi'))
                                                        <button class="btn btn-neon text-white mb-3" data-bs-toggle="modal" data-bs-target="#tambah_fasilitas" type="button">
                                                            <i class="fa fa-plus"></i> Tambah Fasilitas
                                                        </button>
                                                        @endif
                                                    </div>
                                                </div>
                                                <table id="example" class="table table-bordered data-table mb-3" style="width: 100%">
                                                    <thead>
                                                        <tr class="small text-center">
                                                            <th class="th-sm">No</th>
                                                            <th class="th-md">Nama Fasilitas</th>
                                                            <th class="th-sm">Jumlah Kebutuhan</th>
                                                            <th class="th-sm">Satuan</th>
                                                            <th class="th-sm">Tipe Pendanaan</th>
                                                            <th class="th-lg-percent">Persetujuan</th>
                                                            @if ($perjadin->status_pengajuan == 'Draf-pengajuan' || $perjadin->status_pengajuan == 'proses')
                                                            <th class="th-lg-percent">Aksi</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    @foreach ($fasilitas as $fasilita)
                                                    <tr>
                                                        <td class='text-center'>{{ $loop->iteration }}</td>
                                                        <td>{{ $fasilita->nama }}</td>
                                                        <td class='text-center'>{{ $fasilita->jumlah_frekuensi }}</td>
                                                        <td class='text-center'>{{ $fasilita->satuan}}</td>
                                                        <td class='text-center'>{{ $fasilita->tipe_pendanaan}}</td>
                                                        <td class='text-center'>{{ $fasilita->status}}</td>
                                                        @if ($perjadin->status_pengajuan == 'Draf-pengajuan' || $perjadin->status_pengajuan == 'proses')
                                                        <td class='text-center'>
                                                            <form action="{{url('/h_fasilitas_detail/' . $fasilita->idKebutuhan)}}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="info_perjadinlangsung" value="{{ $perjadin->id }}">
                                                                <span>
                                                                    <button type="submit" class="text-decoration-none btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data Peserta?')" @if ($perjadin->status_pengajuan == 'pengajuan' || $perjadin->status_pengajuan == 'proses') disabled @endif>
                                                                        <i class="fa-solid fa-trash"></i>
                                                                    </button>
                                                                </span>
                                                            </form>
                                                        </td>
                                                        @endif
                                                    </tr>
                                                    @endforeach
                                                </table>

                                            </div>
                                            @else
                                            <div class="text-center">
                                                <button class="btn btn-neon text-white mb-3" data-bs-toggle="modal" data-bs-target="#tambah_fasilitas" type="button" @if ($perjadin->status_pengajuan == 'pengajuan' || $perjadin->status_pengajuan == 'proses') disabled @endif>
                                                    <i class="fa fa-plus"></i> Tambah Fasilitas
                                                </button>
                                            </div>

                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Dokumen Pendukung --}}
                            <form action="{{url('/u_perjadin')}}" method="POST" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <input id="" type="hidden" value="{{ $perjadin->id }}" name="info_perjadinlangsung">
                                <input id="" type="hidden" value="{{ $perjadin->status_pengajuan }}" name="status_pejadin">
                                <input id="" type="hidden" value="{{ $perjadin->is_acceptKeu }}" name="status_pejadin_keuangan">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <div>
                                            <h6 class="fw-bold text-secondary">Kelengkapan Dokumen</h6><br>
                                            <div class="alert alert-success" role="alert">
                                                Dokumen
                                                @if ($dokumen != null)
                                                @if ($dokumen->status_persetujuan == null)
                                                Belum Diupload
                                                @else
                                                {{$dokumen->status_persetujuan}} - {{$dokumen->ket}}
                                                @endif
                                                @endif

                                            </div>
                                        </div>
                                        <table id="example" class="table table-bordered data-table" style="width: 100%">
                                            <thead>
                                                <tr class="text-center small">
                                                    <th class="th-sm">No</th>
                                                    <th class="th-md">Nama Dokumen</th>
                                                    <th class="th-md">Lampiran</th>
                                                    <th class="th-md">Unggah Dokumen</th>
                                                </tr>
                                            </thead>

                                            <tr>
                                                <td class='text-center'>1</td>
                                                <td class=''>Surat Undangan</td>
                                                <td class=''>
                                                    @if ($dokumen != null && $dokumen->surat_undangan != null)
                                                    <?php
                                                    $path = $dokumen->surat_undangan;
                                                    $filename = basename($path);
                                                    ?>
                                                    <a href="{{ url('/perjadin/getDokumen/'.$filename) }}" target="_blank">[Lihat Lampiran]</a>
                                                    @else
                                                    Laporan Belum Diunggah
                                                    @endif
                                                </td>
                                                <td class='text-center'>
                                                    @if ($dokumen != null && $dokumen->surat_undangan != null)
                                                    <input type="hidden" name="oldSuratUndangan" value="{{ $dokumen->surat_undangan }}">
                                                    @else
                                                    <input type="file" class="form-control" name="surat_undangan">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class='text-center'>2</td>
                                                <td class=''>Surat Tugas</td>
                                                <td class=''>
                                                    @if ($dokumen != null && $dokumen->surat_tugas != null)
                                                    <?php
                                                    $path = $dokumen->surat_tugas;
                                                    $filename = basename($path);
                                                    ?>
                                                    <a href="{{ url('/perjadin/getDokumen/'.$filename) }}" target="_blank">[Lihat Lampiran]</a>
                                                    @else
                                                    Laporan Belum Diunggah
                                                    @endif
                                                </td>
                                                <td class='text-center'>
                                                    @if ($dokumen != null && $dokumen->surat_tugas != null)
                                                    <input type="hidden" name="oldSuratTugas" value="{{ $dokumen->surat_tugas }}">
                                                    @else
                                                    <input type="file" class="form-control" name="surat_tugas">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class='text-center'>3</td>
                                                <td class=''>SPPD</td>
                                                <td class=''>
                                                    @if ($dokumen != null && $dokumen->SPPD != null)
                                                    <?php
                                                    $path = $dokumen->SPPD;
                                                    $filename = basename($path);
                                                    ?>
                                                    <a href="{{ url('/perjadin-getDokumen/'.$filename) }}" target="_blank">[Lihat Lampiran]</a>
                                                    @else
                                                    Laporan Belum Diunggah
                                                    @endif
                                                </td>
                                                <td class='text-center'>
                                                    @if ($dokumen != null && $dokumen->SPPD != null)
                                                    <input type="hidden" name="oldSppd" value="{{ $dokumen->SPPD }}">
                                                    @else
                                                    <input type="file" class="form-control" name="SPPD">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class='text-center'>4</td>
                                                <td class=''>Laporan Pengeluaran</td>
                                                <td class=''>
                                                    @if ($dokumen != null && $dokumen->lap_pengeluaran != null)
                                                    <?php
                                                    $path = $dokumen->lap_pengeluaran;
                                                    $filename = basename($path);
                                                    ?>
                                                    <a href="{{ url('/perjadin-getDokumen/'.$filename) }}" target="_blank">[Lihat Lampiran]</a>
                                                    @else
                                                    Laporan Belum Diunggah
                                                    @endif
                                                </td>
                                                <td class='text-center'>
                                                    @if ($dokumen != null && $dokumen->lap_pengeluaran != null)
                                                    <input type="hidden" name="oldlap_pengeluaran" value="{{ $dokumen->lap_pengeluaran }}">
                                                    @else
                                                    <input type="file" class="form-control" name="lap_pengeluaran" required>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class='text-center'>5</td>
                                                <td class=''>Laporan Perjalanan</td>
                                                <td class=''>
                                                    @if ($dokumen != null && $dokumen->lap_perjadin != null)
                                                    <?php
                                                    $path = $dokumen->lap_perjadin;
                                                    $filename = basename($path);
                                                    ?>
                                                    <a href="{{ url('/perjadin-getDokumen/'.$filename) }}" target="_blank">[Lihat Lampiran]</a>
                                                    @else
                                                    Laporan Belum Diunggah
                                                    @endif
                                                </td>
                                                <td class='text-center'>
                                                @if ($dokumen != null && $dokumen->lap_perjadin != null)
                                                    <input type="hidden" name="oldLap_perjadin" value="{{ $dokumen->lap_perjadin}}">
                                                    @else
                                                    <input type="file" class="form-control" name="lap_perjadin">
                                                    @endif
                                                </td>
                                            </tr>
                                            @if (($perjadin->status_pengajuan == 'pelaporan') && ($perjadin->status_pengajuan == 'revisi'))
                                            <tr>
                                                <td class='text-center'>5</td>
                                                <td class=''>Laporan Perjadin</td>
                                                <td class=''><button type="button" class="btn btn-primary" id="unduhTemplate">Buat Laporan</button></td>
                                                <td class='text-center'></td>

                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                                <div class="btns-group d-flex justify-content-evenly pb-3 mt-5">
                                    @if (($perjadin->status_pengajuan == 'Draf-pengajuan') | ($perjadin->status_pengajuan == 'revisi') | ($perjadin->status_pengajuan == 'pelaporan'))
                                    <a href="{{url('/perjadin/riwayat/' . $perjadin->status_pengajuan)}}" class="btn btn-prev btn-warning col-md-2 text-white">Kembali</a>
                                    <div class="col-md-30">
                                        <button class="btn btn-neon btn-warning text-white" type="submit" name="action" value="update">Perbaharui dan Simpan</button>
                                    </div>
                                    @elseif (($perjadin->status_pengajuan == 'proses'))
                                    <a href="{{url('/perjadin/riwayat/' . $perjadin->status_pengajuan)}}" class="btn btn-prev btn-warning col-md-2 text-white">Kembali</a>
                                    @if (($perjadin->is_acceptBend == 'approval-2'))
                                    <button class="btn btn-neon btn-warning text-white col-md-2" type="submit" name="action" value="selesai">Selesai</button>
                                    @else
                                    <button class="btn btn-neon text-white col-md-2" type="button" data-bs-toggle="modal" data-bs-target="#alertModal">Selesai</button>
                                    @endif
                            </form>
                            @elseif (($perjadin->status_pengajuan == 'pengajuan'))
                            <a href="{{url('/perjadin/riwayat/' . $perjadin->status_pengajuan)}}" class="btn btn-prev btn-warning col-md-5 text-white">Kembali</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</section>

<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-exclamation-triangle-fill mb-3" viewBox="0 0 16 16">
                    <path d="M8 16a2 2 0 0 1-1.732-1H1.78a2 2 0 0 1-1.732-3H14.5a2 2 0 0 1 1.732 3h-4.48A2 2 0 0 1 8 16zm.93-12.54L14 13H2L7.07 3.46a1 1 0 0 1 1.86 0zM5.002 6a.502.502 0 0 0-.53.47v3.06a.502.502 0 0 0 .53.47h6a.502.502 0 0 0 .53-.47V6.47a.502.502 0 0 0-.53-.47h-6zM8 10a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                </svg>
                <p>Aksi tidak bisa dilakukan karena belum mendapatkan verifikasi-1 oleh Bendahara.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Peserta -->
<div class="modal fade" id="tambah_peserta" tabindex="-1" aria-labelledby="tambah_pesertaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tambah_pesertaLabel">Tambah Peserta</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeButton"></button>
            </div>
            <div class="modal-body">
                <form action="{{url('/c_pesertaDetail')}}" method="post">
                    @csrf
                    <input id="" type="hidden" value="{{ $perjadin->tgl_mulai }}" name="mulai">
                    <input id="" type="hidden" value="{{ $perjadin->tgl_selesai }}" name="selesai">
                    <input id="" type="hidden" value="{{ $perjadin->id }}" name="info_perjadinlangsung">
                    <div class="row">
                        <div class="col-md-12 mb-3 tab-pane fade show active">
                            <label for="" class="form-label">Nama Pegawai <a href="" data-bs-target="#non_pegawai" data-bs-toggle="modal" data-bs-dismiss="modal" aria-label="Close">[Tambah Non-Pegawai]</a></label>
                            <select class="js-example-basic-single" name="peserta_pegawai" style="width: 100%;">
                                <option value="0" selected>Pilih Pegawai</option>
                                @foreach ($pegawais as $pegawai)
                                <option value="{{ $pegawai->id }}">{{ $pegawai->nama_lengkap }}</option>
                                @endforeach
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

<!-- Modal Tambah Peserta non pegawai -->
<div class="modal fade" id="non_pegawai" tabindex="-1" aria-labelledby="tambah_pesertaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tambah_pesertaLabel">Tambah Peserta</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{url('/c_non_pesertaDetail')}}" method="post">
                    @csrf
                    <input id="" type="hidden" value="{{ $perjadin->id }}" name="info_perjadinlangsung">
                    <div class="col-md-12 mb-3">
                        <label for="" class="form-label">Nama Non Pegawai</label>
                        <select class="js-example-basic-single-2" name="peserta_non_pegawai" style="width: 100%;">
                            <option value="" selected>Pilih Peserta</option>
                            @foreach ($nonpegawais as $nonpegawai)
                            <option value="{{ $nonpegawai->id }}">{{ $nonpegawai->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <hr class="container">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
    </form>
</div>

<!-- Modal Tambah Fasilitas -->
<div class="modal fade" id="tambah_fasilitas" tabindex="-1" aria-labelledby="tambah_fasilitasLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tambah_fasilitasLabel">Tambah Fasilitas</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{url('/c_fasilitasDetail')}}" method="post">
                    @csrf
                    <input id="" type="hidden" value="{{ $perjadin->id }}" name="info_perjadinlangsung">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="uraian" class="form-label">Nama Fasilitas <span class="text-secondary small"></span><span class="text-danger">*</span></label>
                            <select class="form-select" id="uraian" name="uraian" required>
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
                    </div>

                    <div id="conditional_fields">

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

<!-- Modal Untuk Pembuatan Laporan -->

@if (($perjadin->status_pengajuan == 'pelaporan') || ($perjadin->status_pengajuan == 'revisi'))
<div class="modal fade" id="template" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div style="font-size: 5rem;" class="text-center text-warning">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-center">Laporan Perjalanan Dinas</h4>
                    <p class="text-center">Silakan buat laporan perjalanan dinas!</p>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-sm btn-warning text-decoration-none text-white mr-2" data-bs-dismiss="modal">Sudah Membuat</button>
                <a href="{{url('/note-perjadin/' . $perjadin->id)}}" class="btn btn-sm btn-danger text-decoration-none text-white mr-2"><i class="fa-solid fa-file-pen"></i> Buat Laporan</a>
            </div>
        </div>
    </div>
</div>
@endif

<script>
    function showModal() {
        var alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
        alertModal.show();
    }
</script>
@endsection