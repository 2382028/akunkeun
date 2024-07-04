@extends('user.templates.template')

@section('content')
    <!-- Awal Form Perjalanan Dinas Biasa  -->
    <section id="beranda" class="pb-5 mt-5 pt-5">
        <div class="container">
            <div class="row mb-3">
                <h3 class="fw-bold text-secondary">Kegiatanku | Program Kegiatan</h3>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm rounded-0  border-0">
                        <div class="card-body content">
    
                            <!-- Details -->
                            <div class="row page_content page_6 justify-content-center">
                                <div class="col-md-11 mb-3">
                                    <div class="card shadow-sm rounded-0  border-0">
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <h6 class="fw-bold text-secondary">Informasi Program</h6><br>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-3">Judul Program</div>
                                                <div class="col-9">{{$kegiatan->nama_kegiatan}}</div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-3">Jenis Kegiatan</div>
                                                <div class="col-9">{{$kegiatan->jenis_kegiatan}}</div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-3">Tanggal Pelaksanaan</div>
                                                <div class="col-9">{{$kegiatan->tgl_mulai}} s.d {{$kegiatan->tgl_selesai}}</div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-3">Lokasi</div>
                                                <div class="col-9">{{$kegiatan->alamat}}</div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-3">Status Pengajuan</div>
                                                <div class="col-9"> <span class="bg-neon p-2 text-white">{{$kegiatan->status}}</span> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-11">
                                    <div class="card shadow-sm rounded-0  border-0">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs wrapper" id="myTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                  <button class="btn btn-sm active" id="orang-tab" data-bs-toggle="tab" data-bs-target="#orang" type="button" role="tab" aria-controls="orang" aria-selected="false">Informasi Orang</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                  <button class="btn btn-sm" id="fasilitas-tab" data-bs-toggle="tab" data-bs-target="#fasilitas" type="button" role="tab" aria-controls="fasilitas" aria-selected="false">Informasi Fasilitas</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                  <button class="btn btn-sm" id="mobilitas-tab" data-bs-toggle="tab" data-bs-target="#mobilitas" type="button" role="tab" aria-controls="mobilitas" aria-selected="false">Informasi Mobilitas</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                  <button class="btn btn-sm" id="sarpras-tab" data-bs-toggle="tab" data-bs-target="#sarpras" type="button" role="tab" aria-controls="sarpras" aria-selected="false">Informasi Sarpras</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                  <button class="btn btn-sm" id="dokumen-tab" data-bs-toggle="tab" data-bs-target="#dokumen" type="button" role="tab" aria-controls="dokumen" aria-selected="false">Kelengkapan Dokumen</button>
                                                </li>
                                            </ul>
                                            <div class="tab-content pt-3" id="myTabContent">
                                                <div class="tab-pane fade show active" id="orang" role="tabpanel" aria-labelledby="orang-tab">
                                                    @foreach ($perangkats as $perangkat)
                                                    <div class="table-responsive">
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                <h6 class="fw-bold text-secondary">Informasi {{$perangkat->nama_fasilitas}}</h6><br>
                                                            </div>
                                                            <div>
                                                                @if (($kegiatan->status == 'pengajuan') | ($kegiatan->status == 'revisi') | ($kegiatan->status == 'Draf-pengajuan'))
                                                                <button class="btn btn-neon text-white mb-3" data-bs-toggle="modal" data-bs-target="#tambah_peserta" type="button" onclick="setInputFasilitas('{{$perangkat->id}}')">
                                                                    <i class="fa fa-plus"></i> Tambah {{$perangkat->nama_fasilitas}} Baru
                                                                </button>
                                                                @endif
                                                            </div>                                            
                                                        </div>
                                
                                                        <table id="example" class="table table-bordered data-table" style="width: 100%">
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
                                                                    <td class=''>{{$perangkatPegawai->nama_lengkap}}</td>
                                                                    <td class=''>{{$perangkatPegawai->pangkat}} - {{$perangkatPegawai->golongan}}</td>
                                                                    <td class='text-center'>{{$perangkatPegawai->sebagai}}</td>
                                                                    <td class='text-center'>{{$perangkatPegawai->status}}</td>
                                                                    <td class='text-center'>
                                                                        <form action="{{url('/hd_peserta_kegiatan/' . $perangkatPegawai->idPerangkat)}}" method="post">
                                                                            @method('delete')
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
                                                                        <form action="{{url('/hd_peserta_kegiatan/' . $perangkatNonPegawai->idPerangkat)}}" method="post">
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
                                                    <hr>
                                                    @endforeach
                                                    <div class="d-grid gap-2 col-6 mx-auto mt-5">
                                                        @if (($kegiatan->status == 'pengajuan') | ($kegiatan->status == 'revisi') | ($kegiatan->status == 'Draf-pengajuan'))
                                                        <button class="btn btn-neon text-white mb-3" data-bs-toggle="modal" data-bs-target="#tambah_kebutuhan" type="button">
                                                            <i class="fa fa-plus"></i> Tambah Kebutuhan Orang
                                                        </button>
                                                        @endif
                                                    </div>  
                                                </div>
                                                <div class="tab-pane fade" id="fasilitas" role="tabpanel" aria-labelledby="fasilitas-tab">
                                                    <div class="table-responsive">
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                <h6 class="fw-bold text-secondary">Informasi Fasilitas</h6><br>
                                                            </div>
                                                            <div>
                                                                @if (($kegiatan->status == 'pengajuan') | ($kegiatan->status == 'revisi') | ($kegiatan->status == 'Draf-pengajuan'))
                                                                <button class="btn btn-neon text-white mb-3" data-bs-toggle="modal" data-bs-target="#operasional" type="button">
                                                                    <i class="fa fa-plus"></i> Tambah Fasilitas
                                                                </button>
                                                                @endif
                                                            </div>                                            
                                                        </div>
                                                        <table id="example" class="table table-bordered data-table" style="width: 100%">
                                                            <thead>
                                                            <tr class="text-center small">
                                                                <th class="th-sm">No</th>
                                                                <th class="th-md">Uraian Fasilitas</th>
                                                                <th class="th-md">Jumlah Kebutuhan</th>
                                                                <th class="th-md">Satuan</th>
                                                                <th class="th-md">Status</th>
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
                                                                    <form action="{{url('/hd_operasional_kegiatan/' . $operasional->id)}}" method="post">
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
                                                <div class="tab-pane fade" id="mobilitas" role="tabpanel" aria-labelledby="mobilitas-tab">
                                                    <div class="table-responsive">
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                <h6 class="fw-bold text-secondary">Informasi Mobilitas</h6><br>
                                                            </div>
                                                            <div>
                                                                @if (($kegiatan->status == 'pengajuan') | ($kegiatan->status == 'revisi') | ($kegiatan->status == 'Draf-pengajuan'))
                                                                <button class="btn btn-neon text-white mb-3" data-bs-toggle="modal" data-bs-target="#tambah_mobilitas" type="button">
                                                                    <i class="fa fa-plus"></i> Tambah Mobilitas
                                                                </button>
                                                                @endif
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
                                                                <td class='text-center'>{{$mobil->tgl_mulai}}</td>
                                                                <td class=''>{{$mobil->tujuan_penggunaan}}</td>
                                                                <td class=''>{{$mobil->alamat}}</td>
                                                                <td class='text-center'>
                                                                    <form action="{{url('/hd_mobilitas_kegiatan/' . $mobil->id)}}" method="post">
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
                                                <div class="tab-pane fade" id="sarpras" role="tabpanel" aria-labelledby="sarpras-tab">
                                                    <div class="table-responsive">
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                <h6 class="fw-bold text-secondary">Informasi Sarana dan Prasarana</h6><br>
                                                            </div>
                                                            <div>
                                                                @if (($kegiatan->status == 'pengajuan') | ($kegiatan->status == 'revisi') | ($kegiatan->status == 'Draf-pengajuan'))
                                                                <button class="btn btn-neon text-white mb-3" data-bs-toggle="modal" data-bs-target="#tambah_sapras" type="button">
                                                                    <i class="fa fa-plus"></i> Tambah Sapras
                                                                </button>
                                                                @endif
                                                            </div>                                            
                                                        </div>
                                                        <table id="example" class="table table-bordered data-table" style="width: 100%">
                                                            <thead>
                                                            <tr class="text-center small">
                                                                <th class="th-sm">No</th>
                                                                <th class="th-md">Sarana</th>
                                                                <th class="th-md">Jumlah</th>
                                                                <th class="th-md">Tanggal Digunakan</th>
                                                                <th class="th-sm">Status</th>
                                                                <th class="th-lg-percent">Aksi</th>
                                                            </tr>
                                                            </thead>
                                                            @foreach ($sapras as $sapra)
                                                            <tr>
                                                                <td class='text-center'>{{$loop->iteration}}</td>
                                                                <td class=''>{{$sapra->nama_barang}}</td>
                                                                <td class='text-center'>{{$sapra->jumlah_asset}}</td>
                                                                <td class='text-center'>{{$sapra->tgl_peminjaman}}</td>
                                                                <td class='text-center'>{{$sapra->status}}</td>
                                                                <td class='text-center'>
                                                                    <form action="{{url('/hd_sapras/' . $sapra->idPeminjaman)}}" method="post">
                                                                        @method('delete')
                                                                        @csrf
                                                                        <input id="" type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                                                        <input id="" type="hidden" value="{{ $kegiatan->IdBarang }}" name="idAsset">
                                                                        <span>
                                                                            <button class="text-decoration-none btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data?')"><i class="fa-solid fa-trash"></i></button>
                                                                        </span>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="dokumen" role="tabpanel" aria-labelledby="dokumen-tab">
                                                    <div class="table-responsive">
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                <h6 class="fw-bold text-secondary">Kelengkapan Dokumen</h6><br>
                                                            </div>
                                                            <div>
                                                                @if (($kegiatan->status == 'pengajuan') | ($kegiatan->status == 'revisi') | ($kegiatan->status == 'proses') | ($kegiatan->status == 'Draf-pengajuan'))
                                                                <button class="btn btn-neon text-white mb-3" data-bs-toggle="modal" data-bs-target="#tambah_dokumen" type="button">
                                                                    <i class="fa fa-plus"></i> Tambah Dokumen
                                                                </button>
                                                                @endif
                                                            </div>                                            
                                                        </div>
                                                        <table id="example" class="table table-bordered data-table" style="width: 100%">
                                                            <thead>
                                                            <tr class="text-center small">
                                                                <th class="th-sm">No</th>
                                                                <th class="th-md">Nama Dokumen</th>
                                                                <th class="th-md">Lampiran</th>
                                                                <th class="th-sm">Aksi</th>
                                                            </tr>
                                                            </thead>
                                                            @foreach ($dokumens as $dokumen)
                                                            <tr> 
                                                                <td class='text-center'>{{$loop->iteration}}</td>
                                                                <td class=''>{{$dokumen->nama_dokumen}}</td>
                                                                <!-- <td class=''><a href="{{asset('storage/'. $dokumen->file)}}" target="_blank">[Lihat Lampiran]</a></td> -->
                                                                <!-- <td class=''><a href="{{asset('public/storage/'. $dokumen->file)}}" target="_blank">[Lihat Lampiran]</a></td> -->
                                                                <td class=''><a href="{{ url('kegiatan/getDokumen/' . basename($dokumen->file)) }}" target="_blank">[Lihat Lampiran]</a></td>                                                                                                                          
                                                                   <td class='text-center'>
                                                                    <form action="{{url('/hd_dokumen_kegiatan/' . $dokumen->id)}}" method="post">
                                                                        @method('delete')
                                                                        @csrf
                                                                        <input id="" type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                                                        <span>
                                                                            <button class="text-decoration-none btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Dokumen?')"><i class="fa-solid fa-trash"></i></button>
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
                                </div>
                            </div>
                            <!-- end Details -->
                        </div>
                        {{-- aksei --}}
                        <form action="{{url('/u_kegiatan_detail/' . $kegiatan->id)}}" method="post">
                            @method('PUT')
                            @csrf
                        <input type="hidden" name="statusKegiatan" value="{{$kegiatan->status}}">
                        <input type="hidden" name="statusKegiatanKeuangan" value="{{$kegiatan->is_acceptKeu}}">
                            <div class="btns-group d-flex justify-content-evenly pb-3 mt-5">
                                @if (($kegiatan->status == 'pengajuan') | ($kegiatan->status == 'revisi') | ($kegiatan->status == 'proses') | ($kegiatan->status == 'Draf-pengajuan'))
                                <a href="{{url('/kegiatan/riwayat/' . 'pengajuan')}}" class="btn btn-prev btn-warning col-md-5 text-white">Kembali</a>    
                                <button class="btn btn-next btn-primary col-md-5" type="submit">Perbaharui dan Simpan</button>
                                @else
                                <a href="{{url('/kegiatan/riwayat/' . 'pengajuan')}}" class="btn btn-prev btn-warning col-md-5 text-white">Kembali</a>    
                                @endif
                            </div>
                        </form>
                        {{-- end aksi --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Akhir Form Perjalanan Dinas Biasa -->



    <!-- Modal Tambah Kebutuhan -->
<div class="modal fade" id="tambah_kebutuhan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('/cd_fasilitasKegiatan')}}" method="post">
                @csrf
                <input id="" type="hidden" value="{{ $kegiatan->id }}" name="kegiatan_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Tambah Kebutuhan Perangkat Orang</label>
                            <input type="text" class="form-control" id="" name="fasilitas">
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
<div class="modal fade" id="tambah_peserta" tabindex="-1" aria-labelledby="tambah_pesertaLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="tambah_pesertaLabel">Tambah Peserta</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{url('/cd_peserta_kegiatan')}}" method="post">
                @csrf
                <input id="" type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
            <div class="row">
                      <div class="col-md-12 mb-3 tab-pane fade show active" >
                        <input type="hidden" class="form-control" id="fasilitasId" name="id_fasilitas">
                          <label for="" class="form-label">Nama Pegawai <span class="text-danger">*</span><a href="" data-bs-target="#non_pegawai" data-bs-toggle="modal" data-bs-dismiss="modal" aria-label="Close">[Tambah Peserta Baru]</a></label>
                          <select class="js-example-basic-single" name="peserta_pegawai" style="width: 100%;">
                            <option value="" selected>Pilih Peserta</option>
                            @foreach ($pegawais as $pegawai)
                                <option value="{{ $pegawai->id }}">{{ $pegawai->nama_lengkap }}</option>
                            @endforeach  
                          </select>
                      </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="" class="form-label">Posisi<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="" name="sebagai" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="" class="form-label">Detail<span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" aria-label="First name" class="form-control" name="satuan" required>
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
<div class="modal fade" id="non_pegawai" tabindex="-1" aria-labelledby="tambah_pesertaLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="tambah_pesertaLabel">Tambah Peserta</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{url('/cd_non_peserta_kegiatan')}}" method="post">
            @csrf
            <input id="" type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
            <div class="col-md-12 mb-3">
                <input type="hidden" class="form-control" id="fasilitasIdNonPegawai" name="fasilitasIdNonPegawai">
                <label for="" class="form-label">Nama Peserta Non Pegawai <span class="text-danger">*</span></label>
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
                                    <input type="text" class="form-control" id="" name="nama_lengkap" >
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
                                    <label for="" class="form-label">Golongan</span></label>
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
                    <label for="" class="form-label">Detail<span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" aria-label="First name" class="form-control" name="satuan"  required>
                        <select class="form-select" aria-label="Default select example" name="detail_satuan">
                            <option value="jam" selected>Per-jam</option>
                            <option value="hari">Per-hari</option>
                            <option value="bulan">Per-bulan</option>
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


<!-- Modal Tambah Fasilitas -->
<div class="modal fade" id="operasional" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{url('/cd_operasional')}}" method="post">
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
                    <input type="text" name="frekuensi" id="" class="form-control" required>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-md-12 mb-3">
                    <label for="" class="form-label">Detail<span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" aria-label="First name" class="form-control" name="satuan" required>
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

<!-- Modal Tambah Mobilitas -->
<div class="modal fade" id="tambah_mobilitas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{url('/cd_mobilitas_kegiatan')}}" method="post">
            @csrf
            <input id="" type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
            <div class="mb-3 row">
                <div class="col-md-6">
                    <label for="" class="form-label">Mobilitas<span class="text-danger">*</span></label>
                    <select class="form-select form-select" aria-label=".form-select-sm example" name="mobilitas">
                        <option value="Kendaraan Dinas LLDIKTI" selected>Kendaraan Dinas LLDIKTI</option>
                        <option value="Kendaraan Umum">Tranfortasi Publik</option>
                        <option value="Kendaraan Umum dan Dinas">Kendaraan dinas dan tranfortasi publik</option>
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
                    <input type="date" name="tgl_selesai" id="" class="form-control">
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-md-4">
                    <label for="" class="form-label">Provinsi <span class="text-danger">*</span></label>
                    <input type="text" name="provinsi" id="" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="" class="form-label">Kabupaten/Kota <span class="text-danger">*</span></label>
                    <input type="text" name="kab_kota" id="" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="" class="form-label">Desa/Kecamatan</label>
                    <input type="text" name="desa" id="" class="form-control">
                </div>
            </div>
            <div class="col-md-12">
                <label for="" class="form-label">Alamat <span class="text-danger">*</span></label>
                <textarea class="form-control" name="alamat" id="" required></textarea>    
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

<!-- Modal Tambah Sapras -->
<div class="modal fade" id="tambah_sapras" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{url('/cd_sapras_kegiatan')}}" method="post">
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
                    <input type="date" name="tgl_peminjaman" id="" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="" class="form-label">Tanggal Selesai</label>
                    <input type="date" name="tgl_selesai" id="" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="" class="form-label">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah" id="" class="form-control" required>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-md-12">
                <label for="" class="form-label">Keterangan  <span class="text-danger">*</span><span class="text-secondary small">(Contoh : Untuk dokumentasi)</span></label> 
                    <textarea  name="keterangan" id="" class="form-control" required></textarea>
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

<!-- Modal Tambah Sapras -->
<div class="modal fade" id="tambah_dokumen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{url('/cd_dokumen_kegiatan')}}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="kegiatanId" value="{{$kegiatan->id}}">
        <div class="modal-body">
            <div class="mb-3 row">
                <div class="col-md-12">
                    <label for="" class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                    <input type="text" name="nama_dokumen" id="" class="form-control" required>
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

<script>
    $('.js-example-basic-single-3').select2({
        placeholder: 'Select an option',
        dropdownParent:'#tambah_sapras'
    });
</script>

<script>
    function setInputFasilitas(hasil) {
        var input = document.getElementById('fasilitasId');
        input.setAttribute("value", hasil);

        var input = document.getElementById('fasilitasIdNonPegawai');
        input.setAttribute("value", hasil);
    }
</script>
@endsection