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
        <div class="card-body content" id="content">

          <!-- Kegiatan - Keuangan - Pengajuan - Details -->
          <div class="row page_content page_5">
            <div class="col-md-12 mb-3 lh-lg">
              <div class="row">
                <h5 class="fw-bold">Informasi Kegiatan</h5>
              </div>
              <div class="row small">
                <div class="col-md-2">Nama Kegiatan</div>
                <input type="hidden" value="{{ $perjadin->id }}">
                <div class="col-md-10">: {{ $perjadin->nama_kegiatan }}</div>
            </div>
            <div class="row small">
                <div class="col-md-2">Asal Surat Undangan</div>
                <input type="hidden" value="{{ $perjadin->id }}">
                <div class="col-md-10">: {{$perjadin->pemberi_undangan}}</div>
            </div>
            <div class="row small">
                <div class="col-md-2">Tanggal Pelaksanaan</div>
                <div class="col-md-10">: {{ $perjadin->tgl_mulai }}</div>
            </div>
            <div class="row small">
                <div class="col-md-2">Tanggal Selesai</div>
                <div class="col-md-10">: {{ $perjadin->tgl_selesai }}</div>
            </div>
            <div class="row small">
                <div class="col-md-2">Lokasi</div>
                <div class="col-md-10">: {{ $perjadin->alamat }}</div>
            </div>
            <div class="row small">
                <div class="col-md-2">Nomor Surat Tugas</div>
                <div class="col-md-10">: {{ $perjadin->kode_surat_tugas }}</div>
            </div>
            <div class="row small">
                <div class="col-md-2">Tanggal Surat Tugas</div>
                <input type="hidden" value="{{ $perjadin->id }}">
                <div class="col-md-10">: {{$perjadin->tgl_surat_dibuat}}</div>
            </div>
            <div class="row small">
                <div class="col-md-2">Jumlah Hari</div>
                <input type="hidden" value="{{ $perjadin->id }}">
                <div class="col-md-10">: {{$perjadin->jumlah_hari}} Hari</div>
            </div>
              <div class="row small">
                <div class="col-2">Verifikasi Dari BMN dan Bendahara</div>
                <div class="col-10">: <span class="bg-info text-white px-3 py-1">{{$perjadin->is_acceptBMN}} | <span class="bg-info text-white px-3 py-1">{{$perjadin->is_acceptBend}}</span></div>
              </div>
              <div class="row small">
                <div class="col-2">Status Pengaju</div>
                <div class="col-10">: {{$perjadin->status_pengajuan}} | <span class="bg-success text-white px-3 py-1">{{$perjadin->is_acceptKeu}}</span></div>
              </div>
              @if (($perjadin->status_pengajuan == 'revisi') || $perjadin->status_pengajuan == 'ditolak')
                                                <div class="row small">
                                                    <div class="col-2">Alasan Penolakan/Revisi</div>
                                                    <div class="col-10">: <br>
                                                        {!! nl2br(e($perjadin->alasan_penolakan)) !!}
                                                    </div>
                                                </div>
                                            @endif
              
              <br>
            </div>

            <form action="{{url('/cu_perjadin_keuangan')}}" method="post">
              @csrf
              <input type="hidden" name="statusPerjadin" value="{{$perjadin->is_acceptKeu}}">
              <input type="hidden" name="idPerjadin" value="{{$perjadin->id}}">
              <div class="col-md-12 mb-3">
                <h5 class="fw-bold">Informasi Dokumen</h5>
                <div class="table-responsive">
                  <table id="example" class="table table-bordered" style="width: 100%">
                    <thead>
                      <tr class="text-center small">
                        <th class="th-sm">No</th>
                        <th class="th-md">Nama Dokumen</th>
                        <th class="th-md">Aksi</th>
                        @if (($perjadin->is_acceptKeu == 'verifikasi-2') && ($perjadin->status_pengajuan == 'selesai') )
                        <th class="th-sm">Tanggal Penerimaan Berkas</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody>
                      @if ($dokumen->isNotEmpty())
                      <tr>
                        <td class='text-center'>1</td>
                        <td>Surat Tugas</td>
                        <td class='text-center'>
                          <span>
                            @if ($dokumen[0]->surat_tugas)
                            <?php
                            $path = $dokumen[0]->surat_tugas;
                            $filename = basename($path);
                            ?>
                            <a href="{{url('/perjadin-getDokumen/' . $filename)}}" target="_blank" class="btn btn-sm btn-secondary">
                              <i class="fa-solid fa-eye"></i> Lihat Dokumen
                            </a>
                            @endif
                          </span>
                        </td>
                        @if (($perjadin->is_acceptKeu == 'verifikasi-2') && ($perjadin->status_pengajuan == 'selesai') )
                
                        <td>
                          <input type="date" name="tgl_surtug" id="tgl_surtug" class="form-control" required>
                        </td>
                        @endif
                      </tr>
                      <tr>
                        <td class='text-center'>2</td>
                        <td>Surat Undangan</td>
                        <td class='text-center'>
                          @if ($dokumen[0]->surat_undangan != null)
                          <?php
                          $path = $dokumen[0]->surat_undangan;
                          $filename = basename($path);
                          ?>
                          <a href="{{url('/perjadin-getDokumen/' . $filename)}}" target="_blank" class="btn btn-sm btn-secondary">
                            <i class="fa-solid fa-eye"></i> Lihat Dokumen
                          </a>
                          @endif
                          <span>
                          </span>
                        </td>
                      </tr>
                      <tr>
                        <td class='text-center'>3</td>
                        <td>SPPD</td>
                        <td class='text-center'>
                          <span>
                            @if ($dokumen[0]->SPPD!= null)
                            <?php
                            $path = $dokumen[0]->SPPD;
                            $filename = basename($path);
                            ?>

                            <!-- <a href="{{asset('/storage/' . $dokumen[0]->SPPD)}}" target="_blank" class="btn btn-sm btn-secondary"><i class="fa-solid fa-eye"></i> Lihat Dokumen</a> -->
                            <a href="{{url('/perjadin-getDokumen/' . $filename)}}" target="_blank" class="btn btn-sm btn-secondary">
                              <i class="fa-solid fa-eye"></i> Lihat Dokumen
                            </a>

                            @endif
                          </span>
                        </td>
                      </tr>
                      <tr>
                        <td class='text-center'>4</td>
                        <td>Laporan Pengeluaran</td>
                        <td class='text-center'>
                          <span>
                            @if ($dokumen[0]->lap_pengeluaran != null)
                            <?php
                            $path = $dokumen[0]->lap_pengeluaran;
                            $filename = basename($path);
                            ?>
                            <!-- <a href="{{asset('/storage/' . $dokumen[0]->lap_pengeluaran)}}" target="_blank" class="btn btn-sm btn-secondary"><i class="fa-solid fa-eye"></i> Lihat Laporan</a> -->
                            <a href="{{url('/perjadin-getDokumen/' . $filename)}}" target="_blank" class="btn btn-sm btn-secondary"><i class="fa-solid fa-eye"></i> Lihat Laporan </a>
                            @endif
                          </span>
                        </td>
                      </tr>
                      <tr>
                        <td class='text-center'>5</td>
                        <td>Laporan Perjadin</td>
                        <td class='text-center'>
                          <span>
                            @if ($dokumen[0]->lap_perjadin != null)
                            <?php
                            $path = $dokumen[0]->lap_perjadin;
                            $filename = basename($path);
                            ?>
                            <a href="{{url('/perjadin-getDokumen/' . $filename)}}" target="_blank" class="btn btn-sm btn-secondary"><i class="fa-solid fa-eye"></i> Lihat Laporan</a>
                            @endif
                          </span>
                        </td>
                      </tr>
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="col-md-12 mb-3">
                <h5 class="fw-bold">Informasi Peserta</h5>
                <div class="table-responsive">
                  <table id="example" class="table table-bordered" style="width: 100%">
                    <thead>
                      <tr class="text-center small">
                        <th class="th-md">Nama Lengkap</th>
                        <th class="th-sm">Pangkat/Golongan</th>
                        <th class="th-md">Sebagai</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php
                      $numpegawai = 0;
                      $numnonpegawai = 0;
                      @endphp
                      @foreach ($pesertaPegawais as $pesertaPegawai)
                      <tr>
                        <td>{{$pesertaPegawai->nama_lengkap}} <input type="hidden" name="idPesertaPegawai_{{$numpegawai}}" value="{{$pesertaPegawai->idPeserta}}"></td>
                        <td class="text-center">{{$pesertaPegawai->pangkat}}-{{$pesertaPegawai->golongan}}</td>
                        <td class="text-center">{{$pesertaPegawai->status_pegawai}}</td>
                      </tr>
                      @php
                      $numpegawai++;
                      @endphp
                      @endforeach
                      @foreach ($pesertaNonPegawais as $pesertaNonPegawai)
                      <tr>
                        <td>{{$pesertaNonPegawai->nama_lengkap}} <input type="hidden" name="idNonPesertaPegawai_{{$numnonpegawai}}" value="{{$pesertaNonPegawai->idPeserta}}"></td>
                        <td class="text-center">{{$pesertaNonPegawai->pangkat}}-{{$pesertaNonPegawai->golongan}}</td>
                        <td class="text-center">{{$pesertaNonPegawai->status_pegawai}}</td>
                      </tr>
                      @php
                      $numnonpegawai++;
                      @endphp
                      @endforeach
                      <input type="hidden" name="numPegawai" value="{{$numpegawai}}">
                      <input type="hidden" name="numNonPegawai" value="{{$numnonpegawai}}">
                  </table>
                </div>
              </div>

              @if (($perjadin->is_acceptKeu == 'verifikasi-2') | ($perjadin->is_acceptKeu == 'revisi') | ($perjadin->is_acceptKeu == 'selesai' && ($perjadin->is_acceptBend != 'approval-2' && $perjadin->is_acceptBend != 'selesai')))
              <div class="col-md-12 mb-3">
                <div class="table-responsive">
                  <div class="d-flex justify-content-between">
                    <div>
                    @if (($perjadin->is_acceptKeu == 'verifikasi-2') && ($perjadin->status_pengajuan == 'selesai') )
                      <h5 class="fw-bold">Informasi Fasilitas <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambah_fasilitas">+ Tambah Fasilitas</button></h5>
                    @endif
                    </div>
                  </div>
                  <table id="calculationTable1" class="table table-bordered calculationTable" style="width: 100%">
                    <thead>
                      <tr class="text-center small">
                        <th>No</th>
                        <th>Nama Fasilitas</th>
                        <th>Jumlah</th>
                        <th>Detail</th>
                        <th>Nominal Awal</th>
                        <th>Nominal Realisasi</th>
                        <th>Total Realisasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php
                      $numkebutuhan = 0;
                      @endphp
                      @foreach ($kebutuhans as $kebutuhan)
                      <tr>
                        <td class='text-center'>{{$loop->iteration}} <input type="hidden" name="idKeuanganKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->idKeuangan}}"></td>
                        <td style="min-width: 150px">{{$kebutuhan->nama}}</td>
                        <td class='text-center' style="min-width: 50px">{{$kebutuhan->jumlah_frekuensi}}</td>
                        <td class='text-center' style="min-width: 100px">{{$kebutuhan->satuan}}</td>

                        <td>
                          <input type="number" class="form-control" name="nominalKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->jumlah_harga}}" readonly>
                        </td>
                        <td>
                          <input type="number" class="form-control num1 prevent-submit" min="0" name="nominalKebutuhan_{{$numkebutuhan}}">
                        </td>
                        <td>
                          <input type="number" class="result form-control" name="totalKebutuhan_{{$numkebutuhan}}" readonly>
                        </td>
                        <td>
                          <input type="hidden" name="idKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->idKebutuhan}}">
                          <select class="form-select" aria-label="Default select example" name="kesesuaianKebutuhan_{{$numkebutuhan}}">
                            <option value="" {{ $kebutuhan->status == '' ? 'selected' : '' }}>-</option>
                            <option value="sesuai" {{ $kebutuhan->status == 'sesuai' ? 'selected' : '' }}>Sesuai</option>
                            <option value="tidak sesuai" {{ $kebutuhan->status == 'tidak sesuai' ? 'selected' : '' }}>Tidak Sesuai</option>
                        </select>

                        </td>
                        <td class='text-center'>
                                                <input type="hidden" name="perjadinId" value="{{$perjadin->id}}" >
                                                <span>
                                                  @if (($perjadin->is_acceptKeu == 'verifikasi-2') && ($perjadin->status_pengajuan == 'selesai') )
                                                    <button type="button" class="text-decoration-none btn btn-danger btn-sm text-white delete-fasilitas" data-id="{{$kebutuhan->idKebutuhan}}">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                  @else
                                                    <button type="button" class="disabled text-decoration-none btn btn-danger btn-sm text-white delete-fasilitas" data-id="{{$kebutuhan->idKebutuhan}}">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                  @endif
                                                </span>
                                            </td>
                      </tr>
                      @php
                      $numkebutuhan++;
                      @endphp
                      @endforeach
                      <input type="hidden" name="numKebutuhan" value="{{$numkebutuhan}}">
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="5" class="fw-bold text-end">Sub Total</td>
                        <td><input type="number" class="total form-control" readonly></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
              @endif

              <div class="col-md-12 mb-3">
                <div class="d-grid gap-2 d-md-flex justify-content-center">
                  
                  <a href="{{url('/perjadin-keuangan/' . 'verifikasi-2')}}" class="btn btn-dark">Kembali</a>
                  @if (($perjadin->is_acceptKeu == 'verifikasi-2') && ($perjadin->status_pengajuan == 'selesai') )
                    <button class="btn btn-info text-white" type="button" data-bs-toggle="modal" data-bs-target="#revisi_user_modal">Revisi</button>
                    <!-- <button class="btn btn-info text-white" type="submit" name="action" value="revisi">Revisi </button> -->
                    <button class="btn btn-primary" type="submit" name="action" value="simpan">Simpan Draf</button>
                    <button class="btn btn-success" type="submit" name="action" value="verifikasi-2">Verifikasi Tahap 2 dan Kirim Ke-Bendahara</button>
                  @endif
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal Revisi USER -->
<div class="modal fade" id="revisi_user_modal" tabindex="-1" aria-labelledby="tolak_mobilitasLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="revisi_user"></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{url('/cu_perjadin_keuangan')}}" method="post">
          @csrf
          <input type="hidden" name="statusPerjadin" value="{{$perjadin->is_acceptKeu}}">
          <input type="hidden" name="idPerjadin" value="{{$perjadin->id}}">
          <div class="row">
            <div class="col-md-12 mb-3">
              <label for="uraian" class="form-label">Masukkan Alasan Revisi<span class="text-secondary small"></span><span class="text-danger">*</span></label>
              <textarea id="tolak" name="alasan_user" class="form-control" placeholder="Alasan Revisi" required=""></textarea>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary" name="action" value="revisi_user" id="revisi_user_button">Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>
</div>

<div class="modal fade" id="tambah_fasilitas" tabindex="-1" aria-labelledby="tambah_fasilitasLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="tambah_fasilitasLabel">Tambah Fasilitas</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{url('/c_fasilitasDetail_keuangan')}}" method="post">
          @csrf
          <input id="" type="hidden" value="{{ $perjadin->id }}" name="info_perjadinlangsung">
          <div class="row">
            <div class="col-md-12 mb-3">
            <label for="peserta" class="form-label">Nama Peserta</label>
              <select class="form-select mb-2" aria-label="Default select example" name="data_perjadinlangsungs">
                @foreach($pesertaPegawais as $pesertaPegawai)
                <option value="{{$pesertaPegawai->idPeserta}}" selected>{{$pesertaPegawai->nama_lengkap}}</option>
                @endforeach
              </select>
              <label for="uraian" class="form-label">Nama Fasilitas <span class="text-secondary small"></span><span class="text-danger">*</span></label>
              <select class="form-select" id="uraian" name="uraian" required>
                <option value="" disabled selected>Pilih Jenis Fasilitas</option>
                <!-- <option value="Akomodasi Hotel">Akomodasi Hotel</option>
                <option value="BBM">BBM</option>
                <option value="Tiket Kereta">Tiket Kereta</option>
                <option value="Tiket Pesawat">Tiket Pesawat</option>
                <option value="Tiket Travel">Tiket Travel</option>
                <option value="Transportasi Online">Transportasi Online</option>
                <option value="Tol">Tol</option> -->
                @foreach ($ref_fasilitas as $ref_fasilitas)
                    <option value="{{$ref_fasilitas->nama_fasilitas}}">{{$ref_fasilitas->nama_fasilitas}}</option>
                @endforeach
                <option value="Lainnya">Lainnya</option>
              </select>
            </div>
          </div>

          <div id="conditional_fields">
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
      </div>
    </div>
    </form>
  </div>
</div>

<!-- Modal Revisi HKT -->
<div class="modal fade" id="revisi_hkt_modal" tabindex="-1" aria-labelledby="tolak_mobilitasLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="revisi_hkt"></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{url('/cu_perjadin_keuangan')}}" method="post">
          @csrf
          <input type="hidden" name="statusPerjadin" value="{{$perjadin->is_acceptKeu}}">
          <input type="hidden" name="idPerjadin" value="{{$perjadin->id}}">
          <div class="row">
            <div class="col-md-12 mb-3">
              <label for="uraian" class="form-label">Masukkan Alasan Revisi<span class="text-secondary small"></span><span class="text-danger">*</span></label>
              <textarea id="tolak" name="alasan" class="form-control" placeholder="Alasan Revisi" required=""></textarea>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary" name="action" value="revisi_HKT">Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>
</div>

<!-- Akhir Dashboard - Kegiatan - Keuangan -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Tambahkan event listener pada tombol trash
                                document.querySelectorAll('.delete-fasilitas').forEach(function(button) {
                                    button.addEventListener('click', function() {
                                        // Dapatkan ID mobilitas dari atribut data-id
                                        var fasilitasId = this.getAttribute('data-id');
                                        var perjadinId = document.querySelector('input[name="perjadinId"]').value;

                                        if (confirm('Hapus Data Fasilitas?')) {
                                            // Kirim AJAX request ke server untuk menghapus mobilitas
                                            fetch(`/h_fasilitas_keu/${fasilitasId}`, {
                                                    method: 'DELETE',
                                                    headers: {
                                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                                        'Content-Type': 'application/json'
                                                    },
                                                    body: JSON.stringify({
                                                        info_perjadinlangsung: perjadinId
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
                                                        alert('Gagal menghapus data fasilitas.');
                                                    }
                                                })
                                                .catch(error => {
                                                    console.error('Error:', error);
                                                    alert('Terjadi kesalahan saat menghapus data fasilitas.');
                                                });
                                        }
                                    });
                                });
                            });
                        </script>

<script>
        const uraian = document.getElementById('uraian');
        const conditionalFieldsHotel = document.getElementById('conditionalFieldsHotel');
        const conditionalFieldsTiketTransportasi = document.getElementById('conditionalFieldsTiketTransportasi');
        const conditionalFieldsBBM = document.getElementById('conditionalFieldsBBM');
        const conditionalFieldsTol = document.getElementById('conditionalFieldsTol');
        const conditionalFieldsLainnya = document.getElementById('conditionalFieldsLainnya');
        const conditionalFieldsTransportasi_Online = document.getElementById('conditionalFieldsTransportasi_Online');

    $(document).ready(function() {
        // Event listener for the first dropdown
        $('select[name="kesesuaianKebutuhan_0"]').on('change', function() {
          // Get the selected value
          var selectedValue = $(this).val();
          // Get the selected text
          var selectedText = $(this).find("option:selected").text();
    
          // Iterate over all other dropdowns and set the selected value
          $('select[name^="kesesuaianKebutuhan_"]').not(this).each(function() {
            $(this).val(selectedValue).trigger('change');
            $(this).find("option:selected").text(selectedText);
          });
        });
        // Listen for changes in the select element
        $('#uraian').change(function () {
          // Get the selected value
          var selectedValue = $(this).val();
          
          // Clear any existing content in conditional_fields
          $('#conditional_fields').empty();
          
          // Check the selected value and append elements accordingly
          if (selectedValue === 'Akomodasi Hotel') {
            // Append elements for 'Akomodasi Hotel'
            $('#conditional_fields').append(`
              <div class="row">
                <div class="col-md-12 mb-3">
                  <label for="jumlah_kamar" class="form-label">Jumlah Kamar<span class="text-danger">*</span></label>
                  <input type="number" min="0" class="form-control" id="jumlah_kamar" name="jumlah_frekuensi" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Kamar" readonly>
                </div>
                <div class="col-md-12 mb-3">
                  <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                  <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                      <option value="Bayar diawal" selected>Dibayar di Awal</option>
                      <option value="Reimburse">Reimburse</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-12 mb-3">
                  <label for="" class="form-label">Keterangan Fasilitas (opsional) <span class="text-secondary small">(Contoh : Tempat untuk penginapan)</span></label>
                  <input type="text" name="keterangan" id="" class="form-control">
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
                                    <option value="Bayar diawal" selected>Dibayar di Awal</option>
                                    <option value="Reimburse">Reimburse</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Keterangan Fasilitas (opsional) <span class="text-secondary small">(Contoh : Tempat untuk penginapan)</span></label>
                            <input type="text" name="keterangan" id="" class="form-control">
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
                        <option value="Bayar di awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
        </div>
        <div class="col-md-12 mb-3">
            <label for="" class="form-label">Keterangan Fasilitas (opsional) <span class="text-secondary small">(Contoh : Tempat untuk penginapan)</span></label>
            <input type="text" name="keterangan" id="" class="form-control">
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
                        <option value="Bayar di awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
        </div>
        <div class="col-md-12 mb-3">
            <label for="" class="form-label">Keterangan Fasilitas (opsional) <span class="text-secondary small">(Contoh : Tempat untuk penginapan)</span></label>
            <input type="text" name="keterangan" id="" class="form-control" >
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
                        <option value="Bayar di awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
        </div>
        <div class="col-md-12 mb-3">
            <label for="" class="form-label">Keterangan Fasilitas (opsional) <span class="text-secondary small">(Contoh : Tempat untuk penginapan)</span></label>
            <input type="text" name="keterangan" id="" class="form-control" >
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
                        <option value="Bayar di awal" selected>Dibayar di Awal</option>
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
          } else if (selectedValue === 'Lainnya') {
            $('#conditional_fields').append(`
            <div class="row">
            <div class="col-md-12 mb-3">
            <label for="jumlah_tiket" class="detail-fields">Jumlah <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" min="0" aria-label="First name" class="form-control" name="jumlah_frekuensi">
                </div>
            </div>
            <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" required>
                </div>
            <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                        <option value="Bayar di awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <label for="" class="form-label">Keterangan Fasilitas (opsional) <span class="text-secondary small">(Contoh : Tempat untuk penginapan)</span></label>
                <input type="text" name="keterangan" id="" class="form-control" >
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
                        <option value="Bayar di awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <label for="" class="form-label">Keterangan Fasilitas (opsional) <span class="text-secondary small">(Contoh : Tempat untuk penginapan)</span></label>
                <input type="text" name="keterangan" id="" class="form-control" >
            </div>
        </div>
            `);
          }
        });
  });
  </script>

  <script>
    document.getElementById('revisi_user_button').addEventListener('click', function(event) {
        // Hapus atribut required dari input tgl_surtug
        document.getElementById('tgl_surtug').removeAttribute('required');
    });
  </script>
@endsection

