<?php

use Carbon\Carbon;
?>

@extends('admin.templates.sidebar')

@section('contain')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<!-- Awal Dashboard - Kegiatan - Keuangan -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="container-fluid px-4 py-3">
                    <div class="row page_content card-style">
                        <!-- Informasi Kegiatan -->
                        <div class="row">
                            <div class="col-md-6 mb-3 lh-lg">
                                <h5 class="fw-bold">Informasi Kegiatan</h5>
                                <div class="row small">
                                    <div class="col-4">ID Kegiatan</div>
                                    <div class="col-8">: {{$kegiatan}}</div>
                                </div>
                                <div class="row small">
                                    <div class="col-4">Nama Kegiatan</div>
                                    <div class="col-8">: {{$infoKegiatan->nama_kegiatan}}</div>
                                </div>
                                <div class="row small">
                                    <div class="col-4">Tanggal Penyelenggaraan</div>
                                    <div class="col-8">: {{\Carbon\Carbon::parse($infoKegiatan->tgl_mulai)->format('d-m-Y H:i')}}</div>
                                </div>
                                <div class="row small">
                                    <div class="col-4">Tanggal Selesai</div>
                                    <div class="col-8">:  {{\Carbon\Carbon::parse($infoKegiatan->tgl_selesai)->format('d-m-Y H:i')}}</div>
                                </div>
                                <div class="row small">
                                    <div class="col-4">Lokasi</div>
                                    <div class="col-8">: {{$infoKegiatan->alamat}}</div>
                                </div>
                                <div class="row small">
                                    <div class="col-4">Keterangan Diantar</div>
                                    <div class="col-8">: {{$infoKegiatan->tujuan_penggunaan}}</div>
                                </div>
                                <br>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <h5 class="fw-bold">Informasi Dokumen</h5>
                            <div class="table-responsive">
                                <table id="example" class="table table-bordered" style="width: 100%">
                                    <thead>
                                        <tr class="text-center small">
                                            <th class="th-sm">No</th>
                                            <th class="th-md">Nama Dokumen</th>
                                            <th class="th-md">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dokumens as $dokumen)
                                                <tr>
                                                    <td class='text-center'>{{$loop->iteration}}</td>
                                                    <td>{{ $dokumen->nama_dokumen }}</td>
                                                    <td class='text-center'>
                                                        @if (!empty($dokumen->file) && $dokumen->file !== '-')
                                                            <a href="{{ url('/kegiatan-getDokumen/' . basename($dokumen->file)) }}" target="_blank" class="btn btn-sm btn-secondary">
                                                                <i class="fa-solid fa-eye"></i> Lihat Dokumen
                                                            </a>
                                                        @else
                                                            Tidak ada dokumen
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3" id="divInformasiPeserta">
                          <h5 class="fw-bold">Informasi Peserta</h5>
                          <div class="table-responsive">
                              <table id="example" class="table table-bordered" style="width: 100%">
                                  <thead>
                                      <tr class="text-center small">
                                          <th class="th-sm" style="max-width:5px;">No</th>
                                          <th>Nama Lengkap</th>
                                          <th>Sebagai/Golongan</th>
                                          <th>Posisi</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                    <tbody>
                                        @php
                                            $num = 0;
                                        @endphp
                                        @foreach ($pesertaPegawais as $pesertaPegawai)
                                            @php
                                                $num++;
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{$num}}</td>
                                                <td>{{ $pesertaPegawai->nama_lengkap }}</td>
                                                @if ($pesertaPegawai->golongan == '-')
                                                    <td class="text-center"> {{ $pesertaPegawai->sebagai }}</td>
                                                @else
                                                    <td class="text-center"> {{ $pesertaPegawai->sebagai }} - {{ $pesertaPegawai->golongan }}</td>
                                                @endif
                                                <td class="text-center">{{ $pesertaPegawai->posisi}}</td>
                                            </tr>
                                        @endforeach
                                        @foreach ($pesertaNonPegawais as $pesertaNonPegawai)
                                            @php
                                                $num++;
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{$num}}</td>
                                                <td>{{ $pesertaNonPegawai->nama_lengkap }}</td>
                                                @if ($pesertaNonPegawai->golongan == '-')
                                                    <td class="text-center"> {{ $pesertaNonPegawai->sebagai }}</td>
                                                @else
                                                    <td class="text-center"> {{ $pesertaNonPegawai->sebagai }} - {{ $pesertaNonPegawai->golongan }}</td>
                                                @endif
                                                <td class="text-center"> {{ $pesertaNonPegawai->posisi }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                  </tbody>
                              </table>
                          </div>
                      </div>

                        <!-- Informasi Fasilitas -->
                        <div class="col-md-12 mb-3">
                            <h5 class="fw-bold">Informasi Fasilitas</h5>
                            <div class="table-responsive">
                                <table id="calculationTable2" class="table table-bordered" style="width: 100%">
                                    <thead>
                                        <tr class="text-center small">
                                            <th>Jumlah Peserta</th>
                                            <th>Jumlah Kamar</th>
                                            <th>Penginapan</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                      <tr class="text-center small">
                                        @if ($infoKegiatan->jumlah_peserta == 0)
                                            <td>Tanpa Peserta</td>
                                        @else
                                            <td>{{ $infoKegiatan->jumlah_peserta ?? 'N/A' }} Orang</td>
                                        @endif
                                          <td>{{ $infoKegiatan->jumlah_kamar ?? 'N/A' }} Kamar</td>
                                          <td class="text-center">{{ $infoKegiatan->tambah_penginapan ?? 'N/A' }} Hari</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($infoKegiatan->is_acceptBMN != 'selesai'  && $infoKegiatan->is_acceptBMN != 'ditolak' )
                          <div class="col-md-12 mb-3" id="divInformasiPeminjaman" style="display: none;">
                        @else
                          <div class="col-md-12 mb-3" id="divInformasiPeminjaman" style="display: block;">
                        @endif

                        <h5 class="fw-bold">Informasi Peminjaman
                            @if ($infoKegiatan->is_acceptBMN != 'selesai'  && $infoKegiatan->is_acceptBMN != 'ditolak')
                            <button  data-bs-toggle="modal" data-bs-target="#tambah_mobilitas" type="button"  class="btn btn-primary">+ Tambah Mobilitas</button>
                            @endif
                        </h5>
                          <div class="table-responsive">
                              <!-- Form utama untuk mengupdate mobilitas -->
                              <form id="mobilitasForm" action="{{url('/cu_kegiatanmobilitas')}}" method="post">
                                  @csrf
                                  <input type="hidden" name="idKegiatan" value="{{$kegiatan}}">
                                  <input type="hidden" name="kegiatanStatus" value="{{$infoKegiatan->is_acceptBMN}}">

                                  <table id="example" class="table table-bordered" style="width: 100%">
                                      <thead>
                                          <tr class="text-center small">
                                              <th class="th-sm">No</th>
                                              <th class="th-md">Pengemudi</th>
                                              <th class="th-md">Mobil</th>
                                              <th class="th-md" style="min-width: 270px;">Tanggal</th>
                                              <th class="th-lg-percent" style="min-width: 120px;">Keterangan</th>
                                              <th class="th-lg-percent">Gabung Surtug</th>
                                              <th class="th-lg-percent">Aksi</th>
                                          </tr>
                                      </thead>
                                      @php
                                        $nummobilitas = 0;
                                        @endphp
                                        @foreach ($mobilitass as $mobilitas)
                                        <tr>
                                            <input type="hidden" name="kabupaten_kota_{{$nummobilitas}}" value="{{$mobilitas->kab_kota}}">
                                            <input type="hidden" name="provinsi_{{$nummobilitas}}" value="{{$mobilitas->provinsi}}">
                                            <input type="hidden" name="alamat_{{$nummobilitas}}" value="{{$mobilitas->alamat}}">
                                            <input type="hidden" name="nama_kegiatan_{{$nummobilitas}}" value="{{$mobilitas->nama_kegiatan}}">
                                            <input type="hidden" name="supir_{{$nummobilitas}}" value="{{$mobilitas->id_pegawai}}">

                                            <td class='text-center'>{{$loop->iteration}} <input type="hidden" name="idMobilitas_{{$nummobilitas}}" value="{{$mobilitas->id}}"></td>
                                            <td class='text-center'>
                                                {{$mobilitas->nama_lengkap}}
                                            </td>
                                            <td class='text-center'>
                                                {{$mobilitas->merek}} [{{$mobilitas->no_polisi}}]
                                            </td>
                                            <td class='text-center' style="min-width: 100px" id="tanggal_{{$nummobilitas}}">
                                                <input id="" type="hidden" value="{{ Carbon::parse($mobilitas->tgl_keberangkatan)->format('d-m-Y H:i') }}" name="tglBerangkat_{{$nummobilitas}}">
                                                <input id="" type="hidden" value="{{ Carbon::parse($mobilitas->tgl_selesai)->format('d-m-Y H:i') }}" name="tglSelesai_{{$nummobilitas}}">

                                                {{ Carbon::parse($mobilitas->tgl_keberangkatan)->format('d-m-Y') }} s.d {{ Carbon::parse($mobilitas->tgl_selesai)->format('d-m-Y') }}</td>
                                            <td class='text-center'>
                                                <input type="hidden" name="ket_{{$nummobilitas}}" value="{{$mobilitas->ket_mobilitas}}">
                                                {{$mobilitas->ket_mobilitas}}
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="d-flex align-items-center justify-content-center" style="transform: scale(1.5);">
                                                    <!-- Teks 'Tidak' di kiri switch dengan ukuran kecil -->
                                                    <span class="me-1" style="font-size: 0.6rem;">Tidak</span>

                                                    <!-- Switch checkbox -->
                                                    @if($infoKegiatan->is_acceptBMN != 'selesai'  && $infoKegiatan->is_acceptBMN != 'ditolak')
                                                      <div class="form-check form-switch">
                                                          <input class="form-check-input" type="checkbox" id="gabungSurtug" name="gabungSurtug_{{$nummobilitas}}">
                                                      </div>
                                                    @else
                                                      <div class="form-check form-switch">
                                                          <input disabled class="form-check-input" type="checkbox" id="gabungSurtug" name="gabungSurtug_{{$nummobilitas}}">
                                                      </div>
                                                    @endif

                                                    <!-- Teks 'Ya' di kanan switch dengan ukuran kecil -->
                                                    <span style="font-size: 0.6rem; margin-left:-4px;">Ya</span>
                                                </div>
                                            </td>

                                            <td class='text-center'>
                                                <input type="hidden" name="mobilitas_perjadinkegiatan" value="{{ $mobilitasID }}">
                                                <span>
                                                    @if($infoKegiatan->is_acceptBMN != 'selesai' && $infoKegiatan->is_acceptBMN != 'ditolak')
                                                      <button type="button" class="text-decoration-none btn btn-danger btn-sm text-white delete-mobilitas" data-id="{{$mobilitas->id_peminjaman}}">
                                                          <i class="fa-solid fa-trash"></i>
                                                      </button>
                                                    @else
                                                      <button type="button" class="disabled text-decoration-none btn btn-danger btn-sm text-white delete-mobilitas" data-id="{{$mobilitas->id_peminjaman}}">
                                                          <i class="fa-solid fa-trash"></i>
                                                      </button>
                                                    @endif
                                                </span>
                                            </td>
                                            <input type="hidden" name="status_{{$nummobilitas}}" value="proses">
                                            <input id="" type="hidden" value="{{ Carbon::parse($infoKegiatan->tgl_mulai)->format('d-m-Y H:i') }}" name="berangkat_{{$nummobilitas}}">
                                            <input id="" type="hidden" value="{{ Carbon::parse($infoKegiatan->tgl_selesai)->format('d-m-Y H:i') }}" name="selesai_{{$nummobilitas}}">
                                        </tr>
                                        @php
                                        $nummobilitas++;
                                        @endphp
                                        @endforeach
                                        <input type="hidden" name="numMobilitas" value="{{$nummobilitas}}">
                                  </table>
                                  <div class="col-md-12 mb-3">
                                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                                      @if ($infoKegiatan->is_acceptBMN != 'selesai'  && $infoKegiatan->is_acceptBMN != 'ditolak')
                                        <a href="{{url('/kegiatan-mobilitas/' . 'pengajuan')}}" class="btn btn-dark">Batal</a>
                                        <button class="btn btn-success" type="submit" name="action" value="proses">Proses</button>
                                        @else
                                        <a href="{{url('/kegiatan-mobilitas/' . 'selesai')}}" class="btn white-text btn-warning">Kembali</a>
                                      @endif
                                      </div>
                                </div>
                                </div>
                            </form>
                            </div>
                        </div>
                      </div>
                      @if($infoKegiatan->is_acceptBMN != 'selesai'  && $infoKegiatan->is_acceptBMN != 'ditolak')
                          <div class="col-md-12 mb-3">
                            <div class="d-grid gap-2 d-md-flex justify-content-center">
                                <button class="btn btn-success" type="submit" id="btnSetujui">Setujui</button>
                                <a href="#" id="btnTolak" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#tolak_mobilitas">Tolak</a>
                            </div>
                        </div>
                      @endif

                        <input type="hidden" id="idMobilitas" name="idMobilitas" value="{{$mobilitasID}}">
                        <input type="hidden" id="idKegiatan" name="idKegiatan" value="{{$kegiatan}}">
                        <input type="hidden" id="IDKEGIATAN" value="{{$kegiatan}}">

                        <!-- Modal Tambah Mobilitas -->
                        <div class="modal fade" id="tambah_mobilitas" tabindex="-1" aria-labelledby="tambah_mobilitasLabel" aria-hidden="true">
                          <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <h1 class="modal-title fs-5" id="tambah_mobilitasLabel">Tambah Mobilitas</h1>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                      <form id="formTambahMobilitas" action="{{ url('/c_a_admin_mobilitas') }}" method="POST">
                                          @csrf
                                            <input type="hidden" id="idMobilitas" name="idMobilitas" value="{{$mobilitasID}}">
                                            <input type="hidden" id="idKegiatan" name="idKegiatan" value="{{$kegiatan}}">
                                          <div class="col-md-12">
                                              <h6 class="text-secondary fw-bold mt-3">Informasi Dasar</h6><br>
                                          </div>
                                          <div class="mb-3 row align-items-center">
                                                  <div class="col-md-9">
                                                      <label for="floatingTextarea">Judul Kegiatan<span class="text-danger">*</span></label>
                                                      <textarea class="form-control mt-1" id="floatingTextarea" name="nama_kegiatan" required>{{$infoKegiatan->nama_kegiatan}}</textarea>
                                                  </div>
                                              <div class="col-md-3">
                                                  <label for="ket_mobilitas">Keterangan Mobilitas<span class="text-danger">*</span></label>
                                                  <input type="hidden" id="suratUndangan" name="surat_undangan">
                                                  <select class="form-select small-select" id="ket_mobilitas" name="ket_mobilitas">
                                                      <option value="Antar">Antar</option>
                                                      <option value="Jemput">Jemput</option>
                                                      <option value="Antar-Jemput">Antar - Jemput</option>
                                                  </select>
                                              </div>
                                          </div>

                                          <div class="row">

                                              <div class="col-md-4 mb-3">
                                                  <label for="tgl_keberangkatan" class="form-label">Tanggal Keberangkatan<span class="text-danger">*</span></label>
                                                  <input type="date" name="tgl_keberangkatan" id="tgl_keberangkatan" class="form-control"  value="{{ \Carbon\Carbon::parse($infoKegiatan->tgl_mulai)->format('Y-m-d') }}">
                                                  <input type="time" name="jam_keberangkatan" id="jam_keberangkatan" class="form-control"  value="{{ \Carbon\Carbon::parse($infoKegiatan->tgl_mulai)->format('H:i') }}">
                                              </div>
                                              <div class="col-md-4 mb-3">
                                                  <label for="tgl_selesai" class="form-label">Tanggal Selesai<span class="text-danger">*</span></label>
                                                  <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control"  value="{{ \Carbon\Carbon::parse($infoKegiatan->tgl_selesai)->format('Y-m-d') }}">
                                                  <input type="time" name="jam_selesai" id="jam_selesai" class="form-control"  value="{{ \Carbon\Carbon::parse($infoKegiatan->tgl_selesai)->format('H:i') }}">
                                              </div>
                                              <div class="col-md-4 mb-3">
                                                  <label for="tgl_mulai2" class="form-label">Tanggal Acara</label>
                                                  <input type="date" name="tgl_mulai" id="tgl_mulai2" class="form-control" value="{{ \Carbon\Carbon::parse($infoKegiatan->tgl_mulai)->format('Y-m-d') }}" readonly>
                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col-md-4 mb-3">
                                                  <label for="provinsi" class="form-label">Provinsi<span class="text-danger">*</span></label>
                                                  <input type="text" id="provinsi" name="provinsi" class="form-control" style="text-transform: capitalize" placeholder="Masukkan Provinsi"  value="{{$infoKegiatan->provinsi}}">
                                              </div>
                                              <div class="col-md-4 mb-3">
                                                  <label for="kabupaten_kota" class="form-label">Kota/Kabupaten<span class="text-danger">*</span></label>
                                                  <input type="text" name="kabupaten_kota" id="kabupaten_kota" class="form-control" style="text-transform: capitalize" value="{{$infoKegiatan->kab_kota}}">
                                              </div>
                                              <div class="col-md-4 mb-3">
                                              </div>
                                          </div>
                                          <div class="mb-3 row">
                                              <div class="col-md-12">
                                                  <label for="alamat">Alamat<span class="text-danger">*</span></label>
                                                  <textarea class="form-control mt-1" id="alamat" name="alamat" >{{$infoKegiatan->alamat}}</textarea>
                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col-md-4 mb-3">
                                                  <label for="pengemudi" class="form-label">Pengemudi<span class="text-danger">*</span></label>
                                                  <select class="form-select required2" aria-label=".form-select-sm example" name="pengemudi" id="pengemudiSelect" disabled>
                                                      <!-- Pilihan pengemudi akan diisi setelah cek mobilitas -->
                                                  </select>
                                              </div>
                                              <div class="col-md-8 mb-3">
                                                  <label for="kendaraan" class="form-label">Kendaraan<span id="kendaraanDinasText" class="text-secondary small d-none"> (Khusus untuk Kendaraan Dinas)</span><span class="text-danger">*</span></label>
                                                  <select class="form-select" aria-label=".form-select-sm example" name="kendaraan" id="kendaraanSelect" disabled>
                                                      <!-- Pilihan kendaraan akan diisi setelah cek mobilitas -->
                                                </select>
                                              </div>
                                          </div>
                                          <!-- Tombol "Proses ke HKT" -->
                                          <div class="d-flex justify-content-between pb-3 mt-5">
                                              <!-- Tombol "Cek Mobilitas" di luar form, tetapi dalam flex row -->
                                              <button type="button" id="cekMobilitasBtn" class="btn btn-warning text-white col-3" >Cek Mobilitas</button>
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


                      <!-- Modal Tolak Mobilitas -->
                      <div class="modal fade" id="tolak_mobilitas" tabindex="-1" aria-labelledby="tolak_mobilitasLabel" aria-hidden="true">
                          <div class="modal-dialog">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <h1 class="modal-title fs-5" id="tolak_mobilitasLabel">Tolak Ajuan</h1>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                      <form action="{{url('/cu_kegiatanmobilitas')}}" method="post">
                                          @csrf
                                            <input type="hidden" name="idKegiatan" value="{{$kegiatan}}">
                                            <input type="hidden" name="kegiatanStatus" value="{{$infoKegiatan->is_acceptBMN}}">
                                          <div class="row">
                                              <div class="col-md-12 mb-3">
                                                  <label for="uraian" class="form-label">Masukan Alasan<span class="text-secondary small"></span><span class="text-danger">*</span></label>
                                                  <textarea id="tolak" name="alasan" class="form-control" placeholder="Alasan Penolakan" required=""></textarea>
                                              </div>
                                          </div>
                                  </div>
                                  <div class="modal-footer">
                                      <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
                                      <button type="submit" class="btn btn-primary" name="action" value="tolak">Simpan</button>
                                  </div>
                                  </form>
                              </div>
                          </div>
                      </div>


                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tambahkan event listener pada tombol trash
        document.querySelectorAll('.delete-mobilitas').forEach(function(button) {
            button.addEventListener('click', function() {
                // Dapatkan ID mobilitas dari atribut data-id
                var mobilitasId = this.getAttribute('data-id');
                var mobilitas_perjadinkegiatan = document.querySelector('input[name="mobilitas_perjadinkegiatan"]').value;

                if (confirm('Hapus Data Mobilitas?')) {
                    // Kirim AJAX request ke server untuk menghapus mobilitas
                    fetch(`/h_mobilitas/kegiatan/${mobilitasId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                mobilitas_perjadinkegiatan: mobilitas_perjadinkegiatan
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Jika sukses, hapus row dari tabel
                                // this.closest('tr').remove();
                                // Refresh halaman
                                window.location.reload();
                            } else {
                                alert('Gagal menghapus data mobilitas.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat menghapus data mobilitas.');
                        });
                }
            });
        });
    });
</script>


<script>
    // Tangani klik tombol "Setujui"
    document.getElementById("btnSetujui").addEventListener("click", function() {
        // Sembunyikan tombol "Setujui" dan "Tolak"
        document.getElementById("btnSetujui").style.display = "none";
        document.querySelector("a.btn-danger").style.display = "none";
        // Tampilkan "divInformasiPeminjaman"
        document.getElementById("divInformasiPeminjaman").style.display = "block";
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
const ketMobilitasSelect = document.getElementById('ket_mobilitas');
const tglKeberangkatanInput = document.getElementById('tgl_keberangkatan');
const tglSelesaiInput = document.getElementById('tgl_selesai');
const namaKegiatanTextarea = document.getElementById('floatingTextarea'); // Mengambil textarea untuk nama kegiatan

// Fungsi untuk mengupdate nilai input berdasarkan pilihan ket_mobilitas
function updateValues() {
const selectedValue = ketMobilitasSelect.value;

// Ambil nilai dari $perjadin
const tglKeberangkatan = '{{ \Carbon\Carbon::parse($infoKegiatan->tgl_mulai)->format('Y-m-d') }}';
const tglSelesai = '{{ \Carbon\Carbon::parse($infoKegiatan->tgl_selesai)->format('Y-m-d') }}';
const namaKegiatan = '{{ $infoKegiatan->nama_kegiatan }}'; // Ambil nama kegiatan

if (selectedValue === 'Jemput') {
tglKeberangkatanInput.value = tglSelesai; // set tgl_keberangkatan dengan tgl_selesai
tglSelesaiInput.value = tglSelesai; // set tgl_selesai dengan tgl_selesai
namaKegiatanTextarea.value = 'Menjemput Pelaksana Tugas ' + namaKegiatan; // update nama kegiatan
} else if (selectedValue === 'Antar') {
tglKeberangkatanInput.value = tglKeberangkatan; // set tgl_keberangkatan dengan tgl_keberangkatan
tglSelesaiInput.value = tglKeberangkatan; // set tgl_selesai dengan tgl_keberangkatan
namaKegiatanTextarea.value = 'Mengantar Pelaksana Tugas ' + namaKegiatan; // update nama kegiatan
} else if (selectedValue === 'Antar-Jemput') {
tglKeberangkatanInput.value = tglKeberangkatan; // set tgl_keberangkatan dengan tgl_keberangkatan
tglSelesaiInput.value = tglSelesai; // set tgl_selesai dengan tgl_selesai
namaKegiatanTextarea.value = 'Mengantar dan Menjemput Pelaksana Tugas ' + namaKegiatan; // reset nama kegiatan
}
}

// Update nilai saat modal pertama kali dibuka
updateValues();

ketMobilitasSelect.addEventListener('change', function () {
updateValues(); // Panggil fungsi untuk memperbarui nilai saat terjadi perubahan
});
});

</script>
<script>
document.getElementById("cekMobilitasBtn").addEventListener("click", function() {
console.log("Button Cek Mobilitas diklik!");

// Ambil nilai tanggal dari input
const tglKeberangkatan = document.getElementById("tgl_keberangkatan").value;
const tglSelesai = document.getElementById("tgl_selesai").value;
const kegiatanID = document.getElementById("IDKEGIATAN").value;

$.ajax({
url: '/api/cek-mobilitas/kegiatan', // Ganti dengan URL API Anda
type: 'GET', // atau 'POST' tergantung pada API Anda
data: {
tanggal_awal: tglKeberangkatan,
tanggal_akhir: tglSelesai,
kegiatanID: kegiatanID
},
dataType: 'json',
success: function(response) {
const kendaraanSelect = $('#kendaraanSelect');
const pengemudiSelect = $('#pengemudiSelect');

// Kosongkan pilihan yang ada sebelumnya
kendaraanSelect.empty();
pengemudiSelect.empty();

if (response.kendaraans.length > 0) {
$.each(response.kendaraans, function(index, kendaraan) {
kendaraanSelect.append(`<option value="${kendaraan.id}">${kendaraan.merek} [${kendaraan.no_polisi}]</option>`);
});
} else {
kendaraanSelect.append('<option value="">Tidak ada kendaraan yang tersedia</option>');
}

if (response.pengemudis.length > 0) {
$.each(response.pegawaiPengemudis, function(index, pegawaiPengemudi) {
pengemudiSelect.append(`<option value="${pegawaiPengemudi.id}">${pegawaiPengemudi.nama_lengkap}</option>`);
});
$.each(response.pengemudis, function(index, pengemudi) {
pengemudiSelect.append(`<option value="${pengemudi.id}">${pengemudi.nama_lengkap}</option>`);
});
} else {
pengemudiSelect.append('<option value="">Tidak ada pengemudi yang tersedia</option>');
}

// Enable select option setelah data berhasil diambil
kendaraanSelect.prop('disabled', false);
pengemudiSelect.prop('disabled', false);

},
error: function(xhr, status, error) {
console.error('Error fetching data: ', error);
}
});
});

</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
const form = document.querySelector('form');

form.addEventListener('submit', function (event) {
const tglKeberangkatan = document.getElementById('tgl_keberangkatan').value;
const jamKeberangkatan = document.getElementById('jam_keberangkatan').value;
const tglSelesai = document.getElementById('tgl_selesai').value;
const jamSelesai = document.getElementById('jam_selesai').value;

if (!tglKeberangkatan || !jamKeberangkatan || !tglSelesai || !jamSelesai) {
alert('Semua field tanggal dan waktu harus diisi.');
event.preventDefault();
return false;
}

const keberangkatan = new Date(`${tglKeberangkatan}T${jamKeberangkatan}`);
const selesai = new Date(`${tglSelesai}T${jamSelesai}`);

if (isNaN(keberangkatan.getTime()) || isNaN(selesai.getTime())) {
alert('Format tanggal atau waktu tidak valid.');
event.preventDefault();
return false;
}

return true;
});
});
</script>

@endsection
