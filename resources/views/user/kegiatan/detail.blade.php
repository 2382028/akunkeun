@extends('user.templates.sidebar')

@section('content')
<style>
    .btn-outline-secondary {
    color: #5c636a;
    border-color: #5c636a;
}

</style>
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
                                                <div class="col-3">Metode Kegiatan</div>
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
                                                <div class="col-3">Status Pengajuan</div><div class="col-9">
                                                    <span class="bg-neon p-2 text-white">{{ str_replace('<br>', ' - ', $kegiatan->status_pengajuan_detail) }}</span>
                                                </div>
                                            </div>

                                            @if (($kegiatan->status_pengajuan == 'revisi') || $kegiatan->status_pengajuan == 'ditolak')
                                                <div class="row mb-3">
                                                    <div class="col-3">Alasan Penolakan/Revisi</div>
                                                    <div class="col-9">
                                                        {!! nl2br(e($kegiatan->alasan_penolakan)) !!}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-11">
                                    <div class="card shadow-sm rounded-0  border-0">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs wrapper" id="myTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="btn btn-sm btn-outline-primary active me-2" id="orang-tab" data-bs-toggle="tab" data-bs-target="#orang" type="button" role="tab" aria-controls="orang" aria-selected="true">Informasi Orang</button>
                                                  </li>
                                                  <li class="nav-item" role="presentation">
                                                    <button class="btn btn-sm btn-outline-primary me-2" id="fasilitas-tab" data-bs-toggle="tab" data-bs-target="#fasilitas" type="button" role="tab" aria-controls="fasilitas" aria-selected="false">Informasi Fasilitas</button>
                                                  </li>
                                                  <li class="nav-item" role="presentation">
                                                    <button class="btn btn-sm btn-outline-primary me-2" id="mobilitas-tab" data-bs-toggle="tab" data-bs-target="#mobilitas" type="button" role="tab" aria-controls="mobilitas" aria-selected="false">Informasi Mobilitas</button>
                                                  </li>
                                                  <li class="nav-item" role="presentation">
                                                    <button class="btn btn-sm btn-outline-primary me-2" id="sarpras-tab" data-bs-toggle="tab" data-bs-target="#sarpras" type="button" role="tab" aria-controls="sarpras" aria-selected="false">Informasi Sarpras</button>
                                                  </li>
                                                  <li class="nav-item" role="presentation">
                                                    <button class="btn btn-sm btn-outline-primary" id="dokumen-tab" data-bs-toggle="tab" data-bs-target="#dokumen" type="button" role="tab" aria-controls="dokumen" aria-selected="false">Kelengkapan Dokumen</button>
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

                                                        </div>

                                                        <table id="example" class="table table-bordered data-table" style="width: 100%">
                                                            <thead>
                                                            <tr class="text-center small">
                                                                <th class="th-md">Nama Lengkap</th>
                                                                <th class="th-md">Pangkat/Golongan</th>
                                                                <th class="th-md">Sebagai</th>
                                                                <th class="th-md">Status</th>
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
                                                                </tr>
                                                            @endif
                                                            @endforeach
                                                        @endif
                                                        </table>
                                                    </div>
                                                    <hr>
                                                    @endforeach
                                                    <div class="d-grid gap-2 col-6 mx-auto mt-5">
                                                        @if (($kegiatan->status_pengajuan == 'pengajuan') | ($kegiatan->status_pengajuan == 'revisi') | ($kegiatan->status_pengajuan == 'Draf-pengajuan'))

                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="fasilitas" role="tabpanel" aria-labelledby="fasilitas-tab">
                                                    <div class="table-responsive">
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                <h6 class="fw-bold text-secondary">Informasi Fasilitas</h6><br>
                                                            </div>
                                                        </div>
                                                        <table id="example" class="table table-bordered data-table" style="width: 100%">
                                                            <thead>
                                                            <tr class="text-center small">
                                                                <th class="th-sm">No</th>
                                                                <th class="th-md">Uraian Fasilitas</th>
                                                                <th class="th-md">Jumlah Kebutuhan</th>
                                                                <th class="th-md">Keterangan</th>
                                                                <th class="th-md">Status</th>
                                                            </tr>
                                                            </thead>
                                                            @foreach ($operasionals as $operasional)
                                                            <tr>
                                                                <td class='text-center'>{{$loop->iteration}}</td>
                                                                <td class=''>{{$operasional->nama}}</td>
                                                                <td class='text-center'>{{$operasional->jumlah_frekuensi}} {{$operasional->satuan}}</td>
                                                                <td class='text-center'>{{$operasional->ket}}</td>
                                                                <td class='text-center'>{{$operasional->status}}</td>

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
                                                        </div>
                                                        <table id="example" class="table table-bordered data-table" style="width: 100%">
                                                            <thead>
                                                            <tr class="text-center small">
                                                                <th class="th-sm">No</th>
                                                                <th class="th-md">Mobilitas</th>
                                                                <th class="th-md">Tanggal Digunakan</th>
                                                                <th class="th-sm">Kegunaan</th>
                                                                <th class="th-sm">Tujuan</th>
                                                            </tr>
                                                            </thead>
                                                            @foreach ($mobilitas as $mobil)
                                                            <tr>
                                                                <td class='text-center'>{{$loop->iteration}}</td>
                                                                <td class=''>{{$mobil->mobilitas}}</td>
                                                                <td class='text-center'>{{$mobil->tgl_mulai}}</td>
                                                                <td class=''>{{$mobil->tujuan_penggunaan}}</td>
                                                                <td class=''>{{$kegiatan->kab_kota}}</td>

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
                                                        </div>
                                                        <table id="example" class="table table-bordered data-table" style="width: 100%">
                                                            <thead>
                                                            <tr class="text-center small">
                                                                <th class="th-sm">No</th>
                                                                <th class="th-md">Sarana</th>
                                                                <th class="th-md">Jumlah</th>
                                                                <th class="th-md">Tanggal Digunakan</th>
                                                                <th class="th-sm">Status</th>

                                                            </tr>
                                                            </thead>
                                                            @foreach ($sapras as $sapra)
                                                            <tr>
                                                                <td class='text-center'>{{$loop->iteration}}</td>
                                                                <td class=''>{{$sapra->nama_barang}}</td>
                                                                <td class='text-center'>{{$sapra->jumlah_asset}}</td>
                                                                <td class='text-center'>{{$sapra->tgl_peminjaman}}</td>
                                                                <td class='text-center'>{{$sapra->status}}</td>

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
                                                                @if (($kegiatan->status_pengajuan == 'pelaporan'))
                                                                    @if ($kegiatan->jenis_program == 'Penugasan')
                                                                        <a href="{{ url('/note-penugasan-kegiatan/' . $kegiatan->id) }}" target="_blank" class="btn me-2 btn-warning text-black mb-3"><i class="fa fa-pen"></i> Laporan Penugasan</a>
                                                                    @endif
                                                                @endif
                                                                <button class="btn me-2 btn-primary text-white mb-3" data-bs-toggle="modal" data-bs-target="#modal_template" type="button">
                                                                    <i class="fa fa-file"></i> Template Laporan
                                                                </button>
                                                                @if (($kegiatan->status_pengajuan == 'pelaporan'))
                                                                    <button class="btn btn-neon text-white mb-3" data-bs-toggle="modal" data-bs-target="#tambah_dokumen" type="button">
                                                                        <i class="fa fa-plus"></i> Tambah Dokumen
                                                                    </button>
                                                                @else
                                                                    <button disabled class="btn btn-neon text-white mb-3" data-bs-toggle="modal" data-bs-target="#tambah_dokumen" type="button">
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
                                                                <th class="th-md">Tanggal Upload</th>
                                                                <th class="th-md">Lampiran</th>
                                                                <th class="th-sm">Aksi</th>
                                                            </tr>
                                                            </thead>
                                                            @foreach ($dokumens as $dokumen)
                                                            <tr>
                                                                <td class='text-center'>{{$loop->iteration}}</td>
                                                                <td class=''>{{$dokumen->nama_dokumen}}</td>
                                                                <td class='text-center'>
                                                                    {!! \Carbon\Carbon::parse($dokumen->updated_at)->translatedFormat('d F Y') !!}
                                                                    ({{ \Carbon\Carbon::parse($dokumen->updated_at)->format('H:i') }})
                                                                
                                                                </td>
                                                                <td class='text-center'><a href="{{ url('kegiatan/getDokumen/' . basename($dokumen->file)) }}" target="_blank">[Lihat Lampiran]</a></td>
                                                                @if ($dokumen->isSurtug)
                                                                    <td class='text-center'>
                                                                        <form action="{{url('/hd_dokumen_kegiatan/' . $dokumen->id)}}" method="post">
                                                                            @method('delete')
                                                                            @csrf
                                                                            <input id="" type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                                                            <span>
                                                                                <button disabled class="text-decoration-none btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Dokumen?')"><i class="fa-solid fa-trash"></i></button>
                                                                            </span>
                                                                        </form>
                                                                    </td>
                                                                @else
                                                                    @if ($kegiatan->status_pengajuan == 'pelaporan')
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
                                                                    @else
                                                                        <td class='text-center'>
                                                                            <form action="{{url('/hd_dokumen_kegiatan/' . $dokumen->id)}}" method="post">
                                                                                @method('delete')
                                                                                @csrf
                                                                                <input id="" type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                                                                                <span>
                                                                                    <button disabled class="text-decoration-none btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Dokumen?')"><i class="fa-solid fa-trash"></i></button>
                                                                                </span>
                                                                            </form>
                                                                        </td>
                                                                    @endif
                                                                @endif
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
                        <input type="hidden" name="statusKegiatan" value="{{$kegiatan->status_pengajuan}}">
                        <input type="hidden" name="statusKegiatanKeuangan" value="{{$kegiatan->is_acceptKeu}}">
                            <div class="btns-group d-flex justify-content-evenly pb-3 mt-5">
                                @if (($kegiatan->status_pengajuan == 'pelaporan'))
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

    <!-- Modal Tambah Dokumen -->
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

    <!-- Modal Template Laporan -->
    <div class="modal fade" id="modal_template" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Template Dokumen Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Tulisan Deskripsi Template 1 -->
                    <p><strong>Format Laporan Kegiatan Workshop Bimtek</strong></p>
                    <p>Unduh template untuk laporan kegiatan workshop Bimtek yang dapat digunakan sebagai acuan dalam pembuatan laporan.</p>

                    <!-- Tombol Download Template 1 -->
                    <a href="{{ url('/kegiatan/getTemplateDokumen/workshop_bimtek_template.docx') }}" class="btn btn-outline-secondary mb-3">
                        <i class="fa fa-download"></i> Download Template Workshop Bimtek
                    </a>

                    <hr>

                    <!-- Tulisan Deskripsi Template 2 -->
                    <p><strong>Format Laporan Kegiatan FGD</strong></p>
                    <p>Unduh template untuk laporan kegiatan FGD yang dapat digunakan sebagai acuan dalam pembuatan laporan.</p>

                    <!-- Tombol Download Template 2 -->
                    <a href="{{ url('/kegiatan/getTemplateDokumen/fgd_template.docx') }}" class="btn btn-outline-secondary mb-3">
                        <i class="fa fa-download"></i> Download Template FGD
                    </a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>


    <!-- JavaScript untuk membuka tab yang sesuai -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabToOpen = '{{ $tab ?? 'orang' }}';  // Mendapatkan nilai tab dari controller

            if (tabToOpen === 'dokumen') {
                const dokumenTabButton = document.getElementById('dokumen-tab');
                if (dokumenTabButton) {
                    const tab = new bootstrap.Tab(dokumenTabButton);
                    tab.show();  // Membuka tab 'dokumen'
                }
            }
        });
    </script>


@endsection
