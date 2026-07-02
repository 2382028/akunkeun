@extends('admin.templates.sidebar')

@section('contain')
<section id="detail-kegiatan" class="py-4">
    <div class="container">
        <div class="row text-secondary justify-content-center">
            <div class="col-md-12 mb-4">
                <h3 class="fw-bold text-secondary mb-4">Detail Perjalanan Kegiatan</h3>

                <!-- Informasi Program Section -->
                <div class="card shadow-lg rounded-0 border-0 pt-2 pb-2 px-3" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    <h5 class="fw-bold text-secondary mt-2 mb-3">Informasi Kegiatan</h5>
                    <div class="card-body">
                    <div class="row mb-1">
                        <div class="col-md-4 fw-bold ">Judul Program</div>
                        <div class="col-md-8 ">{{ $kegiatan->nama_kegiatan }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4 fw-bold ">Jenis Kegiatan</div>
                        <div class="col-md-8 ">{{ $kegiatan->jenis_kegiatan }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4 fw-bold ">Tanggal Pelaksanaan</div>
                        <div class="col-md-8 ">{{ \Carbon\Carbon::parse($kegiatan->tgl_mulai)->format('d-m-Y H:i') }} s.d {{ \Carbon\Carbon::parse($kegiatan->tgl_selesai)->format('d-m-Y H:i') }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4 fw-bold ">Lokasi</div>
                        <div class="col-md-8 ">{{ $kegiatan->alamat }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4 fw-bold ">Jumlah Peserta</div>
                        <div class="col-md-8 ">{{ $kegiatan->jumlah_peserta }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4 fw-bold ">Jumlah Kamar</div>
                        <div class="col-md-8 ">{{ $kegiatan->jumlah_kamar }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4 fw-bold ">Status Pengajuan</div>
                        <div class="col-md-8 ">{{ $kegiatan->status_pengajuan }}</div>
                    </div>
                    @if($kegiatan->status_pengajuan == 'ditolak')
                    <div class="row mb-3">
                        <div class="col-md-4">Alasan Penolakan</div>
                        <div class="col-md-8">{{ $kegiatan->alasan_penolakan }}</div>
                    </div>
                    @endif                                    
                </div>

                <!-- Informasi Peserta Section -->
              
                    <h5 class="fw-bold text-secondary mb-3">Informasi Peserta</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="text-center">
                                 <tr class="small text-center">
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Pangkat/Golongan</th>
                                    <th>Sebagai</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($perangkats as $perangkat)
                                <tr class="small text-center">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $perangkat->nama_lengkap }}</td>
                                    <td>{{ $perangkat->golongan ?? '-' }} - {{ $perangkat->pangkat ?? '-' }}</td>
                                    <td>{{ $perangkat->sebagai }}</td>
                                    <td>{{ $perangkat->status }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                

                
            

                <!-- Informasi Sarana Prasarana Section -->
               
                    <h5 class="fw-bold text-secondary mb-3">Informasi Pengadaan</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="text-center small">
                                 <tr class="small text-center">
                                    <th>No</th>
                                    <th>Nama Pengadaan</th>
                                    <th>Keterangan Pengadaan</th>
                                    <th>Nominal</th>
                                    <th>No Kwitansi</th>
                                    <th>Tgl Kwitansi</th>
                                    <th>Aksi</th>

                                </tr>
                            </thead>
                            <tbody>
                                
                            <tr class="small">
                                <td class="text-center">1</td>
                                <td class="text-center">{{ $dataPengadaan->nama_pengadaan }}</td>
                                <td class="text-center">{{ $dataPengadaan->ket_pengadaan }}</td>
                                <td class="text-center">Rp {{ number_format($dataPengadaan->nominal_pengadaan, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $dataPengadaan->no_kwitansi }}</td>
                                <td class="text-center">{{ $dataPengadaan->tgl_kwitansi }}</td>
                                <!-- Menambahkan data-bs-toggle dan data-bs-target pada kolom ini -->
                                <td class="text-center" >
                                    @if($isKwitansiExists)
                                        <button class="btn btn-primary text-white mb-3" data-bs-toggle="modal" data-bs-target="#tambah_dokumen" type="button">
                                            <i class="fa fa-file"></i> Perbarui Kwitansi
                                        </button>    
                                    @else
                                        <button class="btn btn-primary text-white mb-3" data-bs-toggle="modal" data-bs-target="#tambah_dokumen" type="button">
                                            <i class="fa fa-file"></i> Lengkapi Kwitansi
                                        </button>    
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
               <!-- Modal Tambah Dokumen -->
                <div class="modal fade" id="tambah_dokumen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Lengkapi Detail Kwitansi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{url('/cd_dok_pengadaan_kegiatan')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="kegiatanId" value="{{$kegiatan->id}}">
                        <input type="hidden" name="keuanganId" value="{{$dataPengadaan->id_keuangan}}">
                        <div class="modal-body">
                            <div class="mb-3 row">
                                <div class="col-md-12">
                                    <label for="" class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_dokumen" id="" class="form-control" readonly value="[{{$kegiatan->id}}] Kwitansi {{ $dataPengadaan->nama_pengadaan }} - {{ $dataPengadaan->ket_pengadaan }}">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label for="" class="form-label">No Kwitansi <span class="text-danger">*</span></label>
                                    <input type="text" name="no_kwitansi" id="" class="form-control" required value="{{ $dataPengadaan->no_kwitansi }}">
                                </div>
                                <div class="col-md-6">
                                <label for="" class="form-label">Tanggal Kwitansi <span class="text-danger">*</span></label>
                                <input type="date" name="tgl_kwitansi" id="" class="form-control" required value="{{ $dataPengadaan->tgl_kwitansi }}">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-12">
                                    <label for="" class="form-label">Upload Dokumen <span class="text-danger">*</span></label>
                                    <input type="file" name="file" id="" class="form-control" required>
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

                <!-- Informasi Dokumen Section -->
               
                    <h5 class="fw-bold text-secondary mb-3">Informasi Dokumen</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="text-center small">
                                 <tr class="small text-center">
                                    <th>No</th>
                                    <th>Nama Dokumen</th>
                                    <th>Lampiran</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dokumens as $dokumen)
                                <tr class="small">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $dokumen->nama_dokumen }}</td>
                                    <td class="text-center">
                                        <a href="{{ url('usulankegiatan/getDokumenKegiatan/' . basename($dokumen->file)) }}" target="_blank" class="text-decoration-none text-info">[Lihat Lampiran]</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
               
                <!-- Kembali Button -->
                <div class="d-flex justify-content-center pb-3">
                    <a href="{{url('/daftar-pengadaan/' . 'pengadaan-kegiatan')}}"  class="btn btn-warning btn-lg text-white">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- AOS Animation -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init();
</script>
@endsection
