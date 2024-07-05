<?php

use Carbon\Carbon;
?>
@extends('admin.templates.sidebar')

@section('contain')
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
                <div class="col-2">Nama Kegiatan</div>
                <div class="col-10">: {{$perjadin->nama_kegiatan}}</div>
              </div>
              <div class="row small">
                <div class="col-2">Tanggal Penyelenggaran</div>
                <div class="col-10">: {{ Carbon::parse($perjadin->tgl_mulai)->format('d-m-Y H:i') }} s.d {{ Carbon::parse($perjadin->tgl_selesai)->format('d-m-Y H:i') }}</div>
              </div>
              <div class="row small">
                <div class="col-2">Lokasi</div>
                <div class="col-10">: {{$perjadin->kabupaten_kota}}</div>
              </div>
              <div class="row small">
                <div class="col-2">Verivikasi Dari BMN dan Bendahara</div>
                <div class="col-10">: <span class="bg-info text-white px-3 py-1">{{$perjadin->is_acceptBMN}} | <span class="bg-info text-white px-3 py-1">{{$perjadin->is_acceptBend}}</span></div>
              </div>
              <div class="row small">
                <div class="col-2">Status Pengaju</div>
                <div class="col-10">: {{$perjadin->status_pengajuan}} | <span class="bg-success text-white px-3 py-1">{{$perjadin->is_acceptKeu}}</span></div>
              </div>
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
                        @if ($perjadin->is_acceptKeu == 'verifikasi-2')
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
                        @if ($perjadin->is_acceptKeu == 'verifikasi-2')
                        <td>
                        <input type="datetime-local" name="tgl_surtug" id="tgl_surtug" class="form-control" required>
                        </td>
                        @endif
                      </tr>
                      <tr>
                        <td class='text-center'>2</td>
                        <td>Surat Undangan</td>
                        <td class='text-center'>
                          
                          <!-- @if ($dokumen[0]->surat_undangan != null)
                          <?php
                              $path = $dokumen[0]->surat_undangan;
                              $filename = basename($path);
                          ?>
                           <a href="{{asset('/storage/' . $dokumen[0]->surat_undangan)}}" target="_blank" class="btn btn-sm btn-secondary"><i class="fa-solid fa-eye"></i> Lihat Dokumen</a> 
                          <a href="{{url('/storage/perjadin/getdokumen' . $filename[0])}}" target="_blank" class="btn btn-sm btn-secondary"><i class="fa-solid fa-eye"></i> Lihat Dokumen</a>
                          @endif                       -->


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

              @if (($perjadin->is_acceptKeu == 'verifikasi-2') | ($perjadin->is_acceptKeu == 'revisi') | ($perjadin->is_acceptKeu == 'selesai'))
              <div class="col-md-12 mb-3">
                <div class="table-responsive">
                  <div class="d-flex justify-content-between">
                    <div>
                      <h5 class="fw-bold">Informasi Fasilitas <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambah_fasilitas">+ Tambah Fasilitas</button></h5>
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
                          <select class="form-select" aria-label=".form-select-sm example" name="kesesuaianKebutuhan_{{$numkebutuhan}}">
                            <option value="{{$kebutuhan->status}}" selected>-</option>
                            <option value="sesuai">Sesuai</option>
                            <option value="tidak sesuai">Tidak Sesuai</option>
                          </select>
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

                  @if (($perjadin->is_acceptKeu == 'verifikasi-1'))
                  <button class="btn btn-danger" type="submit" name="action" value="tolak">Tolak Perjalanan dinas</button>
                  <button class="btn btn-info text-white" type="button" data-bs-toggle="modal" data-bs-target="#revisi_user_modal">Revisi User</button>
                  <button class="btn btn-info text-white" type="button" data-bs-toggle="modal" data-bs-target="#revisi_hkt_modal">Revisi HKT</button>

                  <a href="{{url('/perjadin-keuangan/' . 'verifikasi-1')}}" class="btn btn-dark">Kembali</a>
                  @endif
                  @if (($perjadin->is_acceptKeu == 'verifikasi-2'))
                  <button class="btn btn-info text-white" type="submit" name="action" value="revisi">Revisi </button>
                  <button class="btn btn-primary" type="submit" name="action" value="simpan">Simpan Draf</button>
                  @endif
                  @if (($perjadin->is_acceptKeu == 'verifikasi-1') | ($perjadin->is_acceptKeu == 'revisi') | ($perjadin->is_acceptKeu == 'ditolak'))
                  <input type="hidden" name="persetujuandokumen" value="sesuai">
                  <button class="btn btn-success" type="submit" name="action" value="verifikasi">Verifikasi Tahap 1 dan Kirim Ke-Bendahara</button>
                  @endif
                  @if ($perjadin->is_acceptKeu == 'verifikasi-2')
                  <button class="btn btn-success" type="submit" name="action" value="verifikasi-2">Verifikasi Tahap 2 dan Kirim Ke-Bendahara</button>
                  <a href="{{url('/perjadin-keuangan/' . 'verifikasi-2')}}" class="btn btn-dark">Kembali</a>
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
              <textarea id="tolak" name="alasan" class="form-control" placeholder="Alasan Revisi" required=""></textarea>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary" name="action" value="revisi_user">Simpan</button>
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
          <form action="" method="post">
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
@endsection