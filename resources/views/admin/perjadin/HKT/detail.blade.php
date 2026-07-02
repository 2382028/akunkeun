@extends('admin.templates.sidebar')

@section('contain')
<!-- Awal Form Perjadin Biasa -->
<section id="beranda" class="py-4">
    <div class="container">
        <div class="row text-secondary justify-content-center">
            <div class="col-md-12 mb-3">
                <h3 class="fw-bold text-secondary mb-3">Detail Perjalanan Dinas</h3>
                <div class="card shadow-lg rounded-0 border-0 pt-2 pb-2 px-3" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    <div class="card-body">
                        <!-- Step 2: Informasi Perjadin -->
                        <div class="mb-3">
                            <h5 class="fw-bold text-secondary">Informasi Perjadin</h5>
                            <div class="card shadow-sm rounded-0 mt-3 p-3 border-0">
                                <div class="card-body lh-1">
                                    <div class="row mb-3">
                                        <div class="col-md-4">Judul Surat Undangan</div>
                                        <input type="hidden" value="{{ $perjadin->id }}">
                                        <div class="col-md-8">{{ $perjadin->nama_kegiatan }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">Asal Surat Undangan</div>
                                        <input type="hidden" value="{{ $perjadin->id }}">
                                        <div class="col-md-8">{{$perjadin->pemberi_undangan}}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">Tanggal Pelaksanaan</div>
                                        <div class="col-md-8">{{ $perjadin->tgl_mulai }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">Tanggal Selesai</div>
                                        <div class="col-md-8">{{ $perjadin->tgl_selesai }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">Lokasi</div>
                                        <div class="col-md-8">{{ $perjadin->alamat }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">Nomor Surat Tugas</div>
                                        <div class="col-md-8">{{ $perjadin->kode_surat_tugas }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">Tanggal Surat Tugas</div>
                                        <input type="hidden" value="{{ $perjadin->id }}">
                                        <div class="col-md-8">{{$perjadin->tgl_surat_dibuat}}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">Jumlah Hari</div>
                                        <input type="hidden" value="{{ $perjadin->id }}">
                                        <div class="col-md-8">{{$perjadin->jumlah_hari}} Hari</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">Status Pengajuan</div>
                                        <div class="col-md-8">{{ $perjadin->status_pengajuan_detail }}</div>
                                    </div>
                                    @if (($perjadin->is_acceptHKT == 'revisi') || $perjadin->is_acceptHKT == 'ditolak')
                                                <div class="row mb-3">
                                                    <div class="col-md-4">Alasan Penolakan/Revisi</div>
                                                    <div class="col-md-8">
                                                        {!! nl2br(e($perjadin->alasan_penolakan)) !!}
                                                    </div>
                                                </div>
                                            @endif
                                </div>
                            </div>
                        </div>

                     
                             

                        <!-- Informasi Peserta -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-secondary">Informasi Peserta</h6>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr class="small text-center">
                                            <th>No</th>
                                            <th>Nama (Pegawai)</th>
                                            <th>Pangkat/Golongan</th>
                                            <th>Status</th>
                                            <th>Persetujuan</th>
                                            @if (in_array($perjadin->status_pengajuan, ['Draf-pengajuan', 'pengajuan', 'revisi']))
                                            <th>Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    @php
                                        $iteration = 1; // Inisialisasi penghitung
                                    @endphp
                                    <tbody>
                                        @foreach ($selectPesertas as $selectPeserta)
                                        <tr>
                                            <td class='text-center'>{{ $iteration }}</td>
                                            <td>{{ $selectPeserta->nama_lengkap }}</td>
                                            <td class='text-center'>{{ $selectPeserta->golongan }}-{{ $selectPeserta->pangkat }}</td>
                                            <td class='text-center'>{{ $selectPeserta->status_pegawai }}</td>
                                            <td class='text-center'>{{ $selectPeserta->status_persetujuan }}</td>
                                            @if (in_array($perjadin->status_pengajuan, ['Draf-pengajuan', 'pengajuan', 'revisi']))
                                            <td class='text-center'>
                                                <form action="{{url('/h_peserta_peserta_detail/' . $selectPeserta->idPeserta)}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="info_perjadinlangsung" value="{{ $perjadin->id }}">
                                                    <button disabled type="submit" class="btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data Peserta?')" @if (in_array($perjadin->status_pengajuan, ['pengajuan', 'proses'])) disabled @endif>
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            @endif
                                        </tr>
                                        @php
                                            $iteration++; // Increment penghitung
                                        @endphp
                                        @endforeach
                                        @foreach ($selectPesertasNonPegawais as $selectPeserta)
                                        <tr>
                                            <td class='text-center'>{{ $iteration }}</td>
                                            <td>{{ $selectPeserta->nama_lengkap }}</td>
                                            <td class='text-center'>{{ $selectPeserta->golongan }}-{{ $selectPeserta->pangkat }}</td>
                                            <td class='text-center'>{{ $selectPeserta->status_pegawai }}</td>
                                            <td class='text-center'>{{ $selectPeserta->status_persetujuan }}</td>
                                            @if (in_array($perjadin->status_pengajuan, ['Draf-pengajuan', 'pengajuan', 'revisi']))
                                            <td class='text-center'>
                                                <form action="{{url('/h_peserta_peserta_detail/' . $selectPeserta->idPeserta)}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="info_perjadinlangsung" value="{{ $perjadin->id }}">
                                                    <button disabled type="submit" class="btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data Peserta?')" @if (in_array($perjadin->status_pengajuan, ['pengajuan', 'proses'])) disabled @endif>
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            @endif
                                        </tr>
                                        @php
                                            $iteration++; // Increment penghitung
                                        @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Informasi Fasilitas -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-secondary">Informasi Fasilitas</h6>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr class="small text-center">
                                            <th>No</th>
                                            <th>Nama Fasilitas</th>
                                            <th>Jumlah Kebutuhan</th>
                                            <th>Satuan</th>
                                            <th>Tipe Pendanaan</th>
                                            <th>Persetujuan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($fasilitas as $fasilita)
                                        <tr>
                                            <td class='text-center'>{{ $loop->iteration }}</td>
                                            <td>{{ $fasilita->nama }}</td>
                                            <td class='text-center'>{{ $fasilita->jumlah_frekuensi }}</td>
                                            <td class='text-center'>{{ $fasilita->satuan }}</td>
                                            <td class='text-center'>{{ $fasilita->tipe_pendanaan }}</td>
                                            <td class='text-center'>{{ $fasilita->status }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Informasi Mobilitas -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-secondary">Informasi Mobilitas</h6>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr class="small text-center">
                                            <th>No</th>
                                            <th>Pengemudi</th>
                                            <th>Mobil</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mobilitass as $mobilitas)
                                        <tr>
                                            <td class='text-center'>{{ $loop->iteration }}</td>
                                            <td>{{ $mobilitas->nama_lengkap }}</td>
                                            <td class='text-center'>{{ $mobilitas->merek }} [{{ $mobilitas->no_polisi }}]</td>
                                            <td class='text-center'>{{ $mobilitas->status }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Kelengkapan Dokumen -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-secondary">Kelengkapan Dokumen</h6>
                            <div class="alert alert-success">
                                Dokumen: @if ($dokumen) {{$dokumen->status_persetujuan}} - {{$dokumen->ket}} @else Belum Diupload @endif
                            </div>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr class="text-center small">
                                        <th>No</th>
                                        <th>Nama Dokumen</th>
                                        <th>Lampiran</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class='text-center'>1</td>
                                        <td>{{ $perjadin->nama_kegiatan }}</td>
                                        <td class='text-center'>
                                            @if ($dokumen && $dokumen->surat_undangan)
                                                @if ($dokumen->surat_undangan != "-")
                                                    <a href="{{ url('/usulanperjadin-AdmingetDokumen/'.basename($dokumen->surat_undangan)) }}" target="_blank" class="text-decoration-none text-info">[Lihat Lampiran]</a>
                                                @else
                                                    Tanpa Surat Undangan    
                                                @endif
                                            @else 
                                                Laporan Belum Diunggah 
                                            @endif</td>
                                       
                                    </tr>
                                    <!-- Dokumen lainnya seperti Surat Tugas, SPPD, Laporan Pengeluaran -->
                                </tbody>
                            </table>
                        </div>

                        <div class="btns-group d-flex justify-content-center pb-3">
                            <a href="{{url('/perjadin-HKT/' . 'pengajuan')}}" class="btn btn-dark">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
