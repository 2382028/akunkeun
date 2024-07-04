@extends('user.templates.template')
@section('content')

<!-- Awal Form Perjadin Kegiatan  -->
<section id="beranda" class=" pb-5 mt-5 pt-5">
    <div class="container">
        <div class="row text-secondary justify-content-center">
            <div class="col-md-10 mb-3">
                <div class="row">
                    <h3 class="fw-bold text-secondary">Pengajuan Kegiatan</h3>
                </div>
                <div class="card shadow-sm rounded-0  border-0 p-3" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    <div class="card-body">
                        <form action="" id="multiphase">
                            <!-- Progress bar -->
                            <div class="progressbar">
                                <div class="progress" id="progress"></div>
                                
                                <div class="progress-step" data-title="Judul Program">1</div>
                                <a href="{{url('/kegiatan_step_2/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary"><div class="progress-step " data-title="Informasi Dasar">2</div></a>
                                <a href="{{url('/kegiatan_step_3/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary"><div class="progress-step" data-title="Informasi Orang">3</div></a>
                                <a href="{{url('/kegiatan_step_4/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary hide-notif"><div class="progress-step progress-step-active" data-title="Fasilitas">4</div></a>
                                <a href="{{url('/kegiatan_step_5/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary hide-notif"><div class="progress-step" data-title="Mobilitas">5</div></a>
                                <a href="{{url('/kegiatan_step_6/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary hide-notif"><div class="progress-step" data-title="Sarana & Prasarana">6</div></a>
                                <a href="{{url('/kegiatan_step_7/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary hide-notif"><div class="progress-step" data-title="Dokumen Pendukung">7</div></a>
                            </div>

                            <!-- Step 4 - Fasilitas -->
                            <div class="mb-3">
                                <div class="mb-3 row text-secondary">
                                    <div class="col-md-12">
                                        <div class="card shadow rounded-0  border-0">
                                            <div class="card-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-4 mb-3">Judul Kegiatan</div>
                                                    <input id="" type="hidden" value="{{ $kegiatan->id }}">
                                                    <div class="col-md-4 mb-3">{{ $kegiatan->nama_kegiatan }}</div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4 mb-3">Tanggal Pelaksanaan</div>
                                                    <div class="col-md-4 mb-3">{{ $kegiatan->tgl_mulai }} s.d {{ $kegiatan->tgl_selesai }}</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">Lokasi</div>
                                                    <div class="col-md-4 mb-3">{{ $kegiatan->alamat }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3 text-secondary">
                                    <div class="col-md-12 mb-3">
                                        <div class="card shadow-sm rounded-0  border-0">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <h6 class="fw-bold text-secondary">Informasi Fasilitas</h6><br>
                                                        </div>
                                                        <div>
                                                            <button class="btn btn-neon text-white mb-3" data-bs-toggle="modal" data-bs-target="#operasional" type="button">
                                                                <i class="fa fa-plus"></i> Tambah Fasilitas
                                                            </button>
                                                        </div>                                            
                                                    </div>
                                                    <table id="example" class="table table-bordered data-table" style="width: 100%">
                                                        <thead>
                                                        <tr class="text-center small">
                                                            <th class="th-sm">No</th>
                                                            <th class="th-md">Uraian Fasilitas</th>
                                                            <th class="th-sm">Jumlah Kebutuhan</th>
                                                            <th class="th-sm">Satuan</th>
                                                            <th class="th-sm">Status</th>
                                                            <th class="th-lg-percent">Aksi</th>
                                                        </tr>
                                                        </thead>
                                                        @foreach ($operasionals as $operasional)
                                                        <tr>
                                                            <td class='text-center'>{{$loop->iteration}}</td>
                                                            <td class=''>{{$operasional->nama}}</td>
                                                            <td class='text-center'>{{$operasional->jumlah_frekuensi}}</td>
                                                            <td class='text-center'>{{$operasional->satuan}}-{{$operasional->detail_satuan}}</td>
                                                            <td class='text-center'>{{$operasional->status}}</td>
                                                            <td class='text-center'>
                                                                <form action="{{url('/h_operasional_kegiatan/' . $operasional->id)}}" method="post">
                                                                    @method('delete')
                                                                    @csrf
                                                                    <input id="" type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                                                    <span>
                                                                        <button class="text-decoration-none btn btn-danger btn-sm" style="color: #FFFFFF; margin-left: 5px;" onclick="return confirm('Hapus Data Fasilitas?')"><i class="fa-solid fa-trash"></i></button>
                                                                    </span>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="btns-group d-flex justify-content-evenly pb-3 mt-5">
                                    <a href="{{url('/kegiatan_step_3/' . $kegiatan->id)}}" class="btn btn-prev btn-warning col-md-5 text-white">Sebelumnya</a>
                                    <a href="{{url('/kegiatan_step_5/' . $kegiatan->id)}}" class="btn btn-next btn-primary col-md-5">Selanjutnya</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Akhir Form Perjadin Kegiatan -->


<!-- Modal Tambah Fasilitas -->
<div class="modal fade" id="operasional" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{url('/c_operasional')}}" method="post">
        @csrf
        <input id="" type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
        <div class="modal-body">
            <div class="mb-3 row">
                <div class="col-md-12">
                    <label for="" class="form-label">Uraian <span class="text-danger">*</span><span class="text-secondary small">(Contoh : Hotel, BBM, Tol, dll)</span></label>
                    <input type="text" name="uraian" id="" class="form-control" required>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-md-12">
                    <label for="" class="form-label">Jumlah Kebutuhan<span class="text-danger">*</span></label>
                    <input type="number" min="0" name="frekuensi" id="" class="form-control" required>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-md-12 mb-3">
                    <label for="" class="form-label">Detail<span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" min="0" aria-label="First name" class="form-control" name="satuan" required>
                        <select class="form-select" aria-label="Default select example" name="detail_satuan">
                            <option value="jam" selected>Jam</option>
                            <option value="hari">Hari</option>
                            <option value="bulan">Bulan</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-md-12">
                    <label for="" class="form-label">Keterangan <span class="text-danger">*</span><span class="text-secondary small">(Contoh : Tempat untuk penginapan)</span></label>
                    <textarea class="form-control" name="keterangan" id="" required></textarea>    
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

@endsection