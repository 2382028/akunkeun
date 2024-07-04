@extends('user.templates.template')

@section('content')

<!-- Awal Form kegiatan Kegiatan  -->
<section id="beranda" class=" pb-5 mt-5 pt-5">
    <div class="container">
        <div class="row text-secondary justify-content-center">
            <div class="col-md-10 mb-3">
                <div class="row">
                    <h3 class="fw-bold text-secondary">Pengajuan Kegiatan</h3>
                </div>
                <div class="card shadow-sm rounded-0  border-0 p-3" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    <div class="card-body">
                            <!-- Progress bar -->
                            <div class="progressbar">
                                <div class="progress" id="progress"></div>
                                
                                <div class="progress-step" data-title="Judul Program">1</div>
                                <a href="{{url('/kegiatan_step_2/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary"><div class="progress-step" data-title="Informasi Dasar">2</div></a>
                                <a href="{{url('/kegiatan_step_3/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary"><div class="progress-step progress-step-active" data-title="Informasi Orang">3</div></a>
                                <a href="{{url('/kegiatan_step_4/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary hide-notif"><div class="progress-step" data-title="Fasilitas">4</div></a>
                                <a href="{{url('/kegiatan_step_5/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary hide-notif"><div class="progress-step" data-title="Mobilitas">5</div></a>
                                <a href="{{url('/kegiatan_step_6/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary hide-notif"><div class="progress-step" data-title="Sarana & Prasarana">6</div></a>
                                <a href="{{url('/kegiatan_step_7/' . $kegiatan->id)}}" class="link-offset-2 text-decoration-none text-secondary hide-notif"><div class="progress-step" data-title="Dokumen Pendukung">7</div></a>
                            </div>

                            <!-- Step 3 - Informasi Orang -->
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
                                
                                @foreach ($perangkats as $perangkat)
                                <div class="row mb-3 text-secondary">
                                    <div class="col-md-12 mb-3">
                                        <div class="card shadow rounded-0  border-0">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <h6 class="fw-bold text-secondary">Informasi {{$perangkat->nama_fasilitas}}</h6><br>
                                                        </div>
                                                        <div>
                                                            <button class="btn btn-neon text-white mb-3" data-bs-toggle="modal" data-bs-target="#tambah_peserta" type="button" onclick="setInputFasilitas('{{$perangkat->id}}')">
                                                                <i class="fa fa-plus"></i> Tambah {{$perangkat->nama_fasilitas}} Baru
                                                            </button>
                                                        </div>                                            
                                                    </div>
                                                    
                                                    <table id="perangkatOrang" class="table table-bordered" style="width: 100%">
                                                        <thead>
                                                            <tr class="text-center small">
                                                                <th class="th-md">Nama Lengkap</th>
                                                                <th class="th-md">Pangkat/Golongan</th>
                                                                <th class="th-md">Sebagai</th>
                                                                <th class="th-md">Status</th>
                                                                <th class="th-lg-percent">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                    @if ($perangkatPegawais->isNotEmpty())
                                                        @foreach ($perangkatPegawais as $perangkatPegawai)
                                                            @if ($perangkatPegawai->fasilitas_id == $perangkat->id)
                                                                <tr>
                                                                    <td class='' name="field1">{{$perangkatPegawai->nama_lengkap}}</td>
                                                                    <td class=''>{{$perangkatPegawai->pangkat}} - {{$perangkatPegawai->golongan}}</td>
                                                                    <td class='text-center'>{{$perangkatPegawai->sebagai}}</td>
                                                                    <td class='text-center'>{{$perangkatPegawai->status}}</td>
                                                                    <td class='text-center'>
                                                                        <form action="{{url('/h_peserta_kegiatan/'. $perangkatPegawai->idPerangkat)}}" method="post">
                                                                            @method('DELETE')
                                                                            @csrf
                                                                            <input id="" type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                                                            <span>
                                                                                <button class="text-decoration-none btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data Peserta?')"><i class="fa-solid fa-trash"></i></button>
                                                                            </span>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                    @if ($perangkatNonPegawais->isNotEmpty())
                                                        @foreach ($perangkatNonPegawais as $perangkatNonPegawai)
                                                            @if ($perangkatNonPegawai->fasilitas_id == $perangkat->id)
                                                                <tr>
                                                                    <td class=''>{{$perangkatNonPegawai->nama_lengkap}}</td>
                                                                    <td class=''>{{$perangkatNonPegawai->pangkat}} - {{$perangkatNonPegawai->golongan}}</td>
                                                                    <td class='text-center'>{{$perangkatNonPegawai->sebagai}}</td>
                                                                    <td class='text-center'>{{$perangkatNonPegawai->status}}</td>
                                                                    <td class='text-center'>
                                                                        <form action="{{url('/h_peserta_kegiatan/' . $perangkatNonPegawai->idPerangkat)}}" method="post">
                                                                            @method('delete')
                                                                            @csrf
                                                                            <input id="" type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                                                            <span>
                                                                                <button class="text-decoration-none btn btn-danger btn-sm" style="color: #FFFFFF; margin-left: 5px;" onclick="return confirm('Hapus Data Peserta?')"><i class="fa-solid fa-trash"></i></button>
                                                                            </span>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    </table>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <div class="d-grid gap-2 col-6 mx-auto mt-5">
                                    <button class="btn btn-neon text-white mb-3" data-bs-toggle="modal" data-bs-target="#tambah_kebutuhan" type="button">
                                        <i class="fa fa-plus"></i> Tambah Perangkat Orang
                                    </button>
                                </div>  
                                <div class="btns-group d-flex justify-content-evenly pb-3 mt-5">
                                    <a href="{{url('/kegiatan_step_2/' . $kegiatan->id)}}" class="btn btn-prev btn-warning col-md-5 text-white">Sebelumnya</a>
                                    <a href="{{url('/kegiatan_step_4/' . $kegiatan->id)}}" class="btn btn-next btn-primary col-md-5">Selanjutnya</a>
                                </div>                                          
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Akhir Form kegiatan Kegiatan -->



<!-- Modal Tambah Kebutuhan -->
<div class="modal fade" id="tambah_kebutuhan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Kebutuhan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('/c_fasilitasKegiatan')}}" method="post">
                @csrf
                <input id="" type="hidden" value="{{ $kegiatan->id }}" name="kegiatan_id">
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


<!-- Modal Tambah Peserta -->
<div class="modal fade" id="tambah_peserta" tabindex="" aria-labelledby="tambah_pesertaLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="tambah_pesertaLabel">Tambah Peserta</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{url('/c_peserta_kegiatan')}}" method="post">
                @csrf
                <input id="" type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
            <div class="row">
                      <div class="col-md-12 mb-3 tab-pane fade show active" >
                        <input type="hidden" class="form-control" id="fasilitasId" name="id_fasilitas">
                          <label for="" class="form-label">Nama Pegawai <a href="" data-bs-target="#non_pegawai" data-bs-toggle="modal" data-bs-dismiss="modal" aria-label="Close"><span class="text-danger">*</span>[Tambah Non-Pegawai]</a></label>
                          <select class="js-example-basic-single-peserta" name="peserta_pegawai" style="width: 100%;">
                            <option value="0" selected>Pilih Pegawai</option>
                            @foreach ($pegawais as $pegawai)
                                <option value="{{ $pegawai->id }}">{{ $pegawai->nama_lengkap }}</option>
                            @endforeach  
                          </select>
                      </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="" class="form-label">Posisi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="" name="sebagai" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="" class="form-label">Detail <span class="text-danger">*</span></label>
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
<div class="modal fade" id="non_pegawai" tabindex="" aria-labelledby="tambah_pesertaLabelNon" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="tambah_pesertaLabel">Tambah Peserta</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{url('/c_non_peserta_kegiatan')}}" method="post">
            @csrf
            <input id="" type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
            <div class="col-md-12 mb-3">
                <input type="hidden" class="form-control" id="fasilitasIdNonPegawai" name="fasilitasIdNonPegawai">
                <label for="" class="form-label">Nama Non Pegawai</label>
                <select class="js-example-basic-single-2" name="peserta_non_pegawai" style="width: 100%;">
                    <option value="0" selected>Pilih Non-Pegawai</option>
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
            <hr>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="" class="form-label">Posisi<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="" name="sebagai" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="" class="form-label">Detail <span class="text-danger">*</span></label>
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
        </div>
            <div class="modal-footer">
                      <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
                      <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
        </form>
    </div>
</div>

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

@endsection