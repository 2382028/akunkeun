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
                                <a href="{{url('/kegiatan_step_2/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary">
                                    <div class="progress-step " data-title="Informasi Dasar">2</div>
                                </a>
                                <a href="{{url('/kegiatan_step_3/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary">
                                    <div class="progress-step" data-title="Informasi Orang">3</div>
                                </a>
                                <a href="{{url('/kegiatan_step_4/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary hide-notif">
                                    <div class="progress-step" data-title="Fasilitas">4</div>
                                </a>
                                <a href="{{url('/kegiatan_step_5/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary hide-notif">
                                    <div class="progress-step progress-step-active" data-title="Mobilitas">5</div>
                                </a>
                                <a href="{{url('/kegiatan_step_6/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary hide-notif">
                                    <div class="progress-step" data-title="Sarana & Prasarana">6</div>
                                </a>
                                <a href="{{url('/kegiatan_step_7/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary hide-notif">
                                    <div class="progress-step" data-title="Dokumen Pendukung">7</div>
                                </a>
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
                                                            <h6 class="fw-bold text-secondary">Informasi Mobilitas</h6><br>
                                                        </div>
                                                        <div>
                                                            <button class="btn btn-neon text-white mb-3" data-bs-toggle="modal" data-bs-target="#tambah_mobilitas" type="button">
                                                                <i class="fa fa-plus"></i> Tambah Mobilitas
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <table id="example" class="table table-bordered data-table" style="width: 100%">
                                                        <thead>
                                                            <tr class="text-center small">
                                                                <th class="th-sm">No</th>
                                                                <th class="th-md">Mobilitas</th>
                                                                <th class="th-md">Tanggal Digunakan</th>
                                                                <th class="th-sm">Kegunaan</th>
                                                                <th class="th-sm">Alamat</th>
                                                                <th class="th-lg-percent">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        @foreach ($mobilitas as $mobil)
                                                        <tr>
                                                            <td class='text-center'>{{$loop->iteration}}</td>
                                                            <td class=''>{{$mobil->mobilitas}}</td>
                                                            <td class=''>{{$mobil->tgl_mulai}}</td>
                                                            <td class=''>{{$mobil->tujuan_penggunaan}}</td>
                                                            <td class=''>{{$mobil->alamat}}</td>
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
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="btns-group d-flex justify-content-evenly pb-3 mt-5">
                                    <a href="{{url('/kegiatan_step_4/' . $kegiatan->id)}}" class="btn btn-prev btn-warning col-md-5 text-white">Sebelumnya</a>
                                    <a href="{{url('/kegiatan_step_6/' . $kegiatan->id)}}" class="btn btn-next btn-primary col-md-5">Selanjutnya</a>
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


<!-- Modal Tambah Mobilitas -->
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
                            <input type="text" name="tujuan" id="" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label for="" class="form-label">Tanggal Digunakan <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="tgl_digunakan" id="" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tgl_selesai" id="" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label for="" class="form-label">Unit<span class="text-danger">*</span></label>
                            <input type="text" name="unit" id="" class="form-control" required>
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

@endsection