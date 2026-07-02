@extends('admin.templates.sidebar')

@section('contain')
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<!-- Awal Dashboard - Kegiatan - Keuangan -->
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 mb-3">
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
                <div class="col-md-10">: {{\Carbon\Carbon::parse($perjadin->tgl_mulai)->format('d-m-Y H:i')}}</div>
            </div>
            <div class="row small">
                <div class="col-md-2">Tanggal Selesai</div>
                <div class="col-md-10">: {{\Carbon\Carbon::parse($perjadin->tgl_selesai)->format('d-m-Y H:i')}}</div>
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
                <div class="col-md-10">: {{\Carbon\Carbon::parse($perjadin->tgl_surat_dibuat)->format('d-m-Y H:i')}}</div>
            </div>
            <div class="row small">
                <div class="col-md-2">Jumlah Hari</div>
                <input type="hidden" value="{{ $perjadin->id }}">
                <div class="col-md-10">: {{$perjadin->jumlah_hari}} Hari</div>
            </div>
              <div class="row small">
                <div class="col-2">Verifikasi Dari BMN dan Keuangan</div>
                <div class="col-10">: <span class="bg-info text-white px-3 py-1">{{$perjadin->is_acceptBMN}} | <span class="bg-info text-white px-3 py-1">{{$perjadin->is_acceptKeu}}</span></div>
              </div>
              <div class="row small">
                <div class="col-2">Status Pengajuan</div>
                <div class="col-10">: {{$perjadin->status_pengajuan}} | <span class="bg-success text-white px-3 py-1">{{$perjadin->is_acceptBend}}</span></div>
              </div>

              <br>
            </div>

            <form action="{{url('/cu_perjadin_bendahara')}}" method="post" id="myForm">
              @csrf
              <input type="hidden" name="statusPerjadin" value="{{$perjadin->is_acceptBend}}">
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
                      </tr>
                    </thead>
                    <tbody>
                      @if (!empty($dokumen) && $dokumen->isNotEmpty())
                      <tr>
                        <td class='text-center'>1</td>
                        <td>Surat Tugas</td>
                        <td class='text-center'>
                          <span>

                          @if ($dokumen->isNotEmpty() && $dokumen[0]->surat_tugas && $dokumen[0]->surat_tugas !== "-")
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
                      </tr>
                      <tr>
                        <td class='text-center'>2</td>
                        <td>Surat Undangan</td>
                        <td class='text-center'>
                        @if ($dokumen->isNotEmpty() && $dokumen[0]->surat_undangan && $dokumen[0]->surat_undangan !== "-")
                          <?php
                          $path = $dokumen[0]->surat_undangan;
                          $filename = basename($path);
                          ?>
                          <a href="{{url('/perjadin-getDokumen/' . $filename)}}" target="_blank" class="btn btn-sm btn-secondary">
                            <i class="fa-solid fa-eye"></i> Lihat Dokumen </a>
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
                            @if ($dokumen[0]->SPPD!= null && $dokumen[0]->SPPD!= "-")
                            <?php
                            $path = $dokumen[0]->SPPD;
                            $filename = basename($path);
                            ?>
                            <!-- <a href="{{asset('/storage/' . $dokumen[0]->SPPD)}}" target="_blank" class="btn btn-sm btn-secondary"><i class="fa-solid fa-eye"></i> Lihat Dokumen</a> -->
                            <a href="{{url('/perjadin-getDokumen/' . $filename)}}" target="_blank" class="btn btn-sm btn-secondary">
                              <i class="fa-solid fa-eye"></i> Lihat Dokumen</a>
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
              @php
                      $numpegawai = 0;
                      $numnonpegawai = 0;
                      @endphp

              @if($perjadin->is_acceptKeu != 'selesai')
              <div class="col-md-12 mb-3" id="divInformasiPeserta">
                <h5 class="fw-bold">Informasi Peserta</h5>
                <div class="table-responsive">
                  <table id="example" class="table table-bordered" style="width: 100%">
                    <thead>
                      <tr class="text-center small">
                        <th >Nama Lengkap</th>
                        <th>Pangkat/Golongan</th>
                        <th >Sebagai</th>
                        <!-- <th>Akun</th>
                        <th>Nominal</th>
                        <th>Total Pembayaran Bersih</th> -->
                      </tr>
                    </thead>
                    @foreach ($fasilitas as $pesertaPegawai)
                    <tr>
                      <td>{{$pesertaPegawai->nama_lengkap}}</td>
                      <td>{{$pesertaPegawai->pangkat}}-{{$pesertaPegawai->golongan}}</td>
                      <td class="text-center">{{$pesertaPegawai->status_pegawai}}</td>
                      <!-- <td>
                          <select class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="akunPegawai_{{$numpegawai}}">
                            @foreach ($akuns as $akun)
                                <option value="{{$akun->idAkun}}"
                                    @if ($pesertaPegawai->akun_x_rkakl == $akun->idAkun) selected @endif>
                                    [{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}
                                </option>
                            @endforeach
                          </select>
                        </td> -->
                    </tr>
                    @endforeach
                    @foreach ($pesertaNonPegawais as $pesertaNonPegawai)
                    <tr>
                      <td>{{$pesertaNonPegawai->nama_lengkap}}</td>
                      <td>{{$pesertaNonPegawai->pangkat}}-{{$pesertaNonPegawai->golongan}}</td>
                      <td class="text-center">{{$pesertaNonPegawai->status_pegawai}}</td>
                    </tr>
                    @endforeach
                  </table>
                </div>
              </div>
              @endif

              <div class="col-md-12 mb-3">
                <!-- sementara gini dulu yang ini -->
                @if(($perjadin->is_acceptKeu == 'selesai') && ($perjadin->is_acceptBend == 'approval-2') || ($perjadin->is_acceptBend == 'selesai'))
                <div class="d-flex justify-content-between">
                  <h5 class="fw-bold">Informasi Peserta</h5>
                </div>
                <div class="table-responsive">
                  <table id="calculationTable1" name="Peserta" class="table table-bordered calculationTable" style="width: 100%">
                    <thead>
                      <tr class="text-center small">
                        <th>Nama Lengkap</th>
                        <th>Pangkat/Golongan</th>
                        <th>Sebagai</th>
                        <th>Akun</th>
                        <!-- <th>SBM</th> -->
                        <th>Uang Harian</th>
                        <th>Uang Harian Halfday/Fullday</th>
                        <th>Uang Harian Fullboard</th>
                        <th>Uang Representasi</th>
                        <th>Total Pembayaran Bersih</th>
                        <th>Tanggal Bayar</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>

                      @foreach ($fasilitas as $pesertaPegawai)
                      <tr>
                        <td style="min-width: 150px">{{$pesertaPegawai->nama_lengkap}} <input type="hidden" name="idPegawai_{{$numpegawai}}" value="{{$pesertaPegawai->idPeserta}}"></td>
                        <td style="min-width: 150px">{{$pesertaPegawai->pangkat}}-{{$pesertaPegawai->golongan}}</td>
                        <td style="min-width: 120px">{{$pesertaPegawai->status_pegawai}}</td>
                        <td>
                          <select class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="akunPegawai_{{$numpegawai}}">
                            @foreach ($akuns as $akun)
                                <option value="{{$akun->idAkun}}"
                                    @if ($pesertaPegawai->akun_x_rkakl == $akun->idAkun) selected @endif>
                                    [{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}
                                </option>
                            @endforeach
                          </select>
                        </td>
                        <style>
                          .hidden-select {
                            display: none;
                          }
                        </style>
                        <td style="min-width: 300px" class="hidden-select">
                          <select class="form-control mySelect" name="sbmPegawai_{{$numpegawai}}">
                            @foreach ($sbms as $sbm)
                            @if ($pesertaPegawai->ref_sbm == $sbm->id)
                            <option value="{{$sbm->id}}" selected>[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                            @endif
                            <option value="{{$sbm->id}}" data-label="{{$sbm->biaya}}">[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                            @endforeach
                          </select>
                        </td>
                        <td style="min-width: 200px">
                          <input type="number" class="form-control num1 prevent-submit" min="0" name="uang_harian{{$numpegawai}}" value="{{$pesertaPegawai->uang_harian}}">
                        </td>
                        <td style="min-width: 200px">
                          <input type="number" class="form-control num2 prevent-submit" min="0" name="uang_harian_fullday{{$numpegawai}}" value="{{$pesertaPegawai->uang_harian_fullday}}">
                        </td>
                        <td style="min-width: 200px">
                          <input type="number" class="form-control num3 prevent-submit" min="0" name="uang_harian_fullboard{{$numpegawai}}" value="{{$pesertaPegawai->uang_harian_fullboard}}">
                        </td>
                        <td style="min-width: 200px">
                          <input type="number" class="form-control num4 prevent-submit" min="0" name="uang_representasi{{$numpegawai}}" value="{{$pesertaPegawai->uang_representasi}}">
                          </td>

                          <td style="min-width: 200px">
                            <input type="number" class="result form-control" name="total_{{$numpegawai}}" value="{{$pesertaPegawai->jumlah_harga}}" readonly>
                          </td>
                        <td style="min-width: 200px">
                          <input type="date" class="result form-control" name="tglbayar_{{$numpegawai}}" value="{{$pesertaPegawai->tgl_bayar}}" required>
                        </td>
                        <td>
                          <select class="form-select" aria-label="Default select example" style="min-width: 150px" name="statuspegawai_{{$numpegawai}}">
                              <option value="" {{ $pesertaPegawai->status == '' ? 'selected' : '' }}>-</option>
                              @if($pesertaPegawai->status != '' && !in_array($pesertaPegawai->status, ['Belum Dibayarkan', 'Tidak Dibayarkan', 'Sudah Dibayarkan']))
                                  <option value="{{ $pesertaPegawai->status }}" selected>{{ $pesertaPegawai->status }}</option>
                              @endif
                              <option value="Belum Dibayarkan" {{ $pesertaPegawai->status == 'Belum Dibayarkan' ? 'selected' : '' }}>Belum Dibayarkan</option>
                              <option value="Tidak Dibayarkan" {{ $pesertaPegawai->status == 'Tidak Dibayarkan' ? 'selected' : '' }}>Tidak Dibayarkan</option>
                              <option value="Sudah Dibayarkan" {{ $pesertaPegawai->status == 'Sudah Dibayarkan' ? 'selected' : '' }}>Sudah Dibayarkan</option>
                          </select>
                        </td>
                      </tr>
                      @php
                      $numpegawai++;
                      @endphp
                      @endforeach
                      @foreach ($pesertaNonPegawais as $pesertaNonPegawai)
                      <tr>
                        <td style="min-width: 150px">{{$pesertaNonPegawai->nama_lengkap}} <input type="hidden" name="idNonPesertaPegawai_{{$numnonpegawai}}" value="{{$pesertaNonPegawai->idData}}"></td>
                        <td style="min-width: 120px">{{$pesertaNonPegawai->pangkat}}-{{$pesertaNonPegawai->golongan}}</td>
                        <td style="min-width: 120px">{{$pesertaNonPegawai->status_pegawai}}</td>
                        <td>
                          <select class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="akunNonPegawai_{{$numnonpegawai}}">
                            @foreach ($akuns as $akun)
                                <option value="{{$akun->idAkun}}"
                                    @if ($pesertaNonPegawai->akun_x_rkakl == $akun->idAkun) selected @endif>
                                    [{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}
                                </option>
                            @endforeach
                          </select>
                        </td>
                        <td class="hidden-select">
                          <select class="form-select mySelect" aria-label="Default select example" style="min-width: 300px" name="sbmNonPegawai_{{$numnonpegawai}}">
                            @foreach ($sbms as $sbm)
                            @if ($pesertaNonPegawai->ref_sbm == $sbm->id)
                            <option value="{{$sbm->id}}" selected>[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                            @endif
                            <option value="{{$sbm->id}}" data-label="{{$sbm->biaya}}">[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                            @endforeach
                          </select>
                        </td>
                        <td style="min-width: 200px">
                          <input type="number" class="form-control num1 prevent-submit" min="0" name="uang_non_harian{{$numnonpegawai}}" value="{{$pesertaNonPegawai->uang_harian}}">
                        </td>
                        <td style="min-width: 200px">
                          <input type="number" class="form-control num2 prevent-submit" min="0" name="uang_non_harian_fullday{{$numnonpegawai}}" value="{{$pesertaNonPegawai->uang_harian_fullday}}">
                        </td>
                        <td style="min-width: 200px">
                          <input type="number" class="form-control num3 prevent-submit" min="0" name="uang_non_harian_fullboard{{$numnonpegawai}}" value="{{$pesertaNonPegawai->uang_harian_fullboard}}">
                        </td>
                        <td style="min-width: 200px">
                          <input type="number" class="form-control nu m4 prevent-submit" min="0" name="uang_non_representasi{{$numnonpegawai}}" value="{{$pesertaNonPegawai->uang_representasi}}">
                          </td>
                        <!-- <td style="min-width: 200px">
                          <input type="number" class="form-control num1 prevent-submit" min="0" name="nominalNon_{{$numnonpegawai}}" value="{{$pesertaNonPegawai->uang_harian}}">
                        </td> -->
                        <td style="min-width: 200px">
                          <input type="number" class="result form-control" name="totalNon_{{$numnonpegawai}}" value="{{$pesertaNonPegawai->jumlah_harga}}">
                        </td>
                        <td style="min-width: 200px">
                          @if (($perjadin->is_acceptKeu == 'selesai'))
                          <input type="date" class="result form-control" name="tglbayarnon_{{$numnonpegawai}}" value="{{$pesertaNonPegawai->tgl_bayar}}">
                          @endif
                        </td>
                        <td>
                          <select class="form-select" aria-label="Default select example" style="min-width: 150px" name="statusnonpegawai_{{$numnonpegawai}}">
                            <option value="" {{ $pesertaNonPegawai->status == '' ? 'selected' : '' }}>-</option>
                            @if($pesertaNonPegawai->status != '' && !in_array($pesertaNonPegawai->status, ['Belum Dibayarkan', 'Tidak Dibayarkan', 'Sudah Dibayarkan']))
                                <option value="{{ $kebutuhan->statusPembayaran }}" selected>{{ $kebutuhan->status }}</option>
                            @endif
                            <option value="Belum Dibayarkan" {{ $pesertaNonPegawai->status == 'Belum Dibayarkan' ? 'selected' : '' }}>Belum Dibayarkan</option>
                            <option value="Tidak Dibayarkan" {{ $pesertaNonPegawai->status == 'Tidak Dibayarkan' ? 'selected' : '' }}>Tidak Dibayarkan</option>
                            <option value="Sudah Dibayarkan" {{ $pesertaNonPegawai->status == 'Sudah Dibayarkan' ? 'selected' : '' }}>Sudah Dibayarkan</option>
                          </select>
                        </td>
                      </tr>
                      @php
                      $numnonpegawai++;
                      @endphp
                      @endforeach
                    <tbody>
                    <tfoot>
                      <tr>
                        <td colspan="9" class="fw-bold text-end">Sub Total</td>
                        <td><input type="number" class="total form-control" readonly></td>
                      </tr>
                    </tfoot>
                    <input type="hidden" name="numPegawai" value="{{$numpegawai}}">
                    <input type="hidden" name="numNonPegawai" value="{{$numnonpegawai}}">
                  </table>
                </div>
                @endif
              </div>
          </div>

          @if (($perjadin->is_acceptBend == 'approval-1'))
          <div class="col-md-12 mb-3">
            <div class="table-responsive">
              <div class="d-flex justify-content-between">
                <div>
                  <h5 class="fw-bold">Informasi Fasilitas <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambah_fasilitas">+ Tambah Fasilitas</button></h5>
                </div>
              </div>
              <table id="calculationTable2" name="Fasilitas" class="table table-bordered calculationTable" style="width: 100%">
                <thead>
                  <tr class="text-center small">
                    <th>No</th>
                    <!-- <th>Nama Peserta</th> -->
                    <th>Nama Fasilitas</th>
                    <th>Jumlah</th>
                    <th>Detail</th>
                    <th>Tipe Pendanaan</th>
                    <th>Pelaksana</th>
                    <th>Keterangan</th>
                    <th>Akun</th>
                    <th>Nominal</th>
                    <th>Total Pembayaran Bersih</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                  $numkebutuhan = 0;
                  @endphp
                  @foreach ($kebutuhans as $kebutuhan)
                  <tr>
                    <td class='text-center' style="min-width: 50px">{{$loop->iteration}} <input type="hidden" name="idKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->idKebutuhan}}"></td>
                    <!-- <td class="text-center" style="min-width: 150px;">{{$kebutuhan->nama_lengkap}}</td> -->
                    <td style="min-width: 150px">{{$kebutuhan->nama}}</td>
                    <td class='text-center' style="min-width: 50px">{{ number_format($kebutuhan->jumlah_frekuensi, 0, ',', '.') }}</td>
                    <td class='text-center' style="min-width: 100px">{{$kebutuhan->satuan}}</td>
                    <td class='text-center' style="min-width: 100px">{{$kebutuhan->tipe_pendanaan}}</td>
                    <td class='text-center' style="min-width: 100px">{{$kebutuhan->pelaksana}}</td>
                    <td class='text-center' style="min-width: 100px">{{$kebutuhan->ket}}</td>
                    <td>
                      <select class="js-example-basic-single-3 form-select akun-dropdown" aria-label="Default select example" style="min-width: 300px" name="akunKebutuhan_{{$numkebutuhan}}">
                        @foreach ($akuns as $akun)
                            <option value="{{$akun->idAkun}}"
                                @if ($kebutuhan->akun_x_rkakl == $akun->idAkun) selected @endif>
                                [{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}
                            </option>
                        @endforeach
                      </select>
                    </td>
                    <td style="min-width: 200px">
                      <input type="number" class="form-control " min="0" name="nominalKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->uang_harian}}" readonly>
                    </td>
                    <td style="min-width: 200px">
                      <input type="number" class="num1 result prevent-submit form-control" min="0" name="totalKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->jumlah_harga}}">
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
                    <td colspan="9" class="fw-bold text-end">Sub Total</td>
                    <td><input type="number" class="total form-control" readonly></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          @endif

          @if (($perjadin->is_acceptBend == 'approval-2') || ($perjadin->is_acceptKeu == 'revisi') || ($perjadin->is_acceptKeu == 'selesai'))
          <div class="col-md-12 mb-3">
            <div class="table-responsive">
              <div class="d-flex justify-content-between">
                  <div>
                      <h5 class="fw-bold">Informasi Fasilitas
                        @if($perjadin->is_acceptKeu == 'selesai')
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambah_fasilitas">+ Tambah Fasilitas</button></h5>
                        @endif
                    </div>
              </div>
              <table id="calculationTable2" name="Fasilitas" class="table table-bordered calculationTable" style="width: 100%">
                <thead>
                  <tr class="text-center small">
                    <th>No</th>
                    <th>Nama Fasilitas</th>
                    <th>Jumlah</th>
                    <th>Detail</th>
                    <th>Tipe Pendanaan</th>
                    <th>Pelaksana</th>
                    <th>Keterangan</th>

                    @if($perjadin->is_acceptKeu == 'selesai')
                        <th>Akun</th>
                        <!-- <th>SBM</th> -->
                        <!-- <th>Nominal</th> -->
                        <th>Total Pembayaran Bersih</th>
                        <th>Tanggal Bayar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  @php
                  $numkebutuhan = 0;
                  @endphp
                  @foreach ($kebutuhans as $kebutuhan)
                  <tr>
                    <td class='text-center' style="min-width: 50px">{{$loop->iteration}} <input type="hidden" name="idKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->idKebutuhan}}"></td>
                    <td style="min-width: 150px">{{$kebutuhan->nama}}</td>
                    <td class='text-center' style="min-width: 50px">{{$kebutuhan->jumlah_frekuensi}}</td>
                    <td class='text-center' style="min-width: 100px">{{$kebutuhan->satuan}}</td>
                    <td class='text-center' style="min-width: 100px">{{$kebutuhan->tipe_pendanaan}}</td>
                    <td class='text-center' style="min-width: 100px">{{$kebutuhan->pelaksana}}</td>
                    <td class='text-center' style="min-width: 100px">{{$kebutuhan->ket}}</td>

                    @if($perjadin->is_acceptKeu == 'selesai')
                        <td>
                        <select class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="akunKebutuhan_{{$numkebutuhan}}">
                            @foreach ($akuns as $akun)
                                <option value="{{$akun->idAkun}}"
                                    @if ($kebutuhan->akun_x_rkakl == $akun->idAkun) selected @endif>
                                    [{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}
                                </option>
                            @endforeach
                        </select>
                        </td>
                        <td class="hidden-select">
                        <select class="form-select mySelect" aria-label="Default select example" style="min-width: 300px" name="sbmKebutuhan_{{$numkebutuhan}}">
                            @foreach ($sbms as $sbm)
                            @if ($kebutuhan->ref_sbm == $sbm->id)
                            <option value="{{$sbm->id}}" selected>[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                            @endif
                            <option value="{{$sbm->id}}" data-label="{{$sbm->biaya}}">[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                            @endforeach
                        </select>
                        </td>
                        <!-- <td style="min-width: 200px">
                        <input type="number" class="form-control num1 prevent-submit" min="0" name="nominalKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->uang_harian}}">
                        </td> -->
                        <td style="min-width: 200px">
                        <input type="number" class="result form-control" name="totalKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->jumlah_harga}}">
                        </td>
                        <td style="min-width: 200px">
                        <input type="date" class="result form-control" name="tglbayarKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->tgl_bayar}}" required>
                        </td>
                        <td>
                        <select class="form-select" aria-label=".form-select-sm example" style="min-width: 150px" name="kesesuaian_{{$numkebutuhan}}">
                        <option value="" {{ $kebutuhan->statusPembayaran == '' ? 'selected' : '' }}>-</option>
                        @if($kebutuhan->statusPembayaran != '' && !in_array($kebutuhan->statusPembayaran, ['Belum Dibayarkan', 'Tidak Dibayarkan', 'Sudah Dibayarkan']))
                            <option value="{{ $kebutuhan->statusPembayaran }}" selected>{{ $kebutuhan->statusPembayaran }}</option>
                        @endif
                        <option value="Belum Dibayarkan" {{ $kebutuhan->statusPembayaran == 'Belum Dibayarkan' ? 'selected' : '' }}>Belum Dibayarkan</option>
                        <option value="Tidak Dibayarkan" {{ $kebutuhan->statusPembayaran == 'Tidak Dibayarkan' ? 'selected' : '' }}>Tidak Dibayarkan</option>
                        <option value="Sudah Dibayarkan" {{ $kebutuhan->statusPembayaran == 'Sudah Dibayarkan' ? 'selected' : '' }}>Sudah Dibayarkan</option>
                        </td>
                        <td>
                            <button type="button" class="text-decoration-none btn btn-danger btn-sm text-white delete-fasilitas" data-id="{{$kebutuhan->idKebutuhan}}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    @endif
                  </tr>
                  @php
                  $numkebutuhan++;
                  @endphp

                  @endforeach
                  <input type="hidden" name="numKebutuhan" value="{{$numkebutuhan}}">
                </tbody>
                @if($perjadin->is_acceptKeu == 'selesai')
                    <tfoot>
                            <tr>
                                <td colspan="8" class="fw-bold text-end">Sub Total</td>
                                <td><input type="number" class="total form-control" readonly></td>
                            </tr>
                    </tfoot>
                @endif
              </table>
            </div>
          </div>
          @endif

          <div class="col-md-5 mb-3 ms-auto">
            <div class="table-responsive">
              <table id="summaryTable" class="table">
                <tbody id="summaryTableBody">
                  <!-- Summary rows will be dynamically generated here -->
                </tbody>
              </table>
            </div>
          </div>

          @if($perjadin->is_acceptKeu == 'selesai' || $perjadin->is_acceptBend == 'approval-1')
            <div class="col-md-12 mb-3">
                <div class="row">
                    <!-- Tombol di kiri -->
                    <div class="col-md-3 text-start">
                        @if (($perjadin->is_acceptBend == 'approval-1') || ($perjadin->is_acceptBend == 'approval-2'))
                        @if ( ($perjadin->is_acceptBend == 'approval-2'))
                        <button class="btn btn-warning text-white submitButton" type="button" data-bs-toggle="modal" data-bs-target="#revisi_verifikator_modal">Revisi ke Verifikator</button>
                        <button class="btn btn-danger submitButton" type="button" data-bs-toggle="modal" data-bs-target="#batalkan_user_modal">Batalkan Perjadin</button>
                        @endif
                        @if ( ($perjadin->is_acceptBend == 'approval-1'))
                        <button class="btn btn-danger submitButton" type="button" data-bs-toggle="modal" data-bs-target="#tolak_user_modal">Tolak</button>
                        <button class="btn btn-warning text-white submitButton" type="button" data-bs-toggle="modal" data-bs-target="#revisi_hkt_modal">Revisi ke HKT</button>
                        @endif
                        @endif
                        <button class="btn btn-success submitButton" type="submit" name="action" value="selesai-tanpa-bayar">Selesaikan Tanpa Pembayaran</button>
                    </div>

                    <!-- Tombol di tengah -->
                            <div class="col-md-6 text-center">
                                <a href="{{url('/perjadin-bendahara/' . 'approval-1')}}" class="btn btn-dark">Batal</a>
                                <button class="btn btn-primary submitButton" type="submit" name="action" value="simpan">Simpan Draf</button>
                                @if (($perjadin->is_acceptBend == 'approval-1') || ($perjadin->is_acceptBend == 'revisi') || ($perjadin->is_acceptBend == 'ditolak'))
                                <button class="btn btn-success submitButton" type="submit" name="action" value="approval">Approval Tahap 1</button>
                                @endif
                                @if ($perjadin->is_acceptBend == 'approval-2')
                                <button class="btn btn-success submitButton" type="submit" name="action" value="approval-2">Approval Tahap 2</button>
                                @endif
                                @if ($perjadin->is_acceptBend == 'selesai')
                                <button class="btn btn-success submitButton" type="submit" name="action" value="approval-2">Perbarui Data</button>
                                @endif
                            </div>


                    <!-- Kosongkan kanan untuk memaksa tombol di tengah -->
                    <div class="col-md-3"></div>
                </div>
                @else
                <div class="row">
                    <div class="col-md-12 text-center">
                        <a href="{{url('/perjadin-bendahara/' . 'approval-1')}}" class="btn btn-dark">Kembali</a>
                    </div>
                @endif

          </div>

          </form>
        </div>

      </div>
    </div>
  </div>
</div>
<!-- Modal Tambah Fasilitas -->
<div class="modal fade" id="tambah_fasilitas" tabindex="-1" aria-labelledby="tambah_fasilitasLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="tambah_fasilitasLabel">Tambah Fasilitas</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{url('/c_fasilitasDetail_bendahara')}}" method="post">
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

<!-- Modal Tolak USER -->
<div class="modal fade" id="tolak_user_modal" tabindex="-1" aria-labelledby="tolak_mobilitasLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="tolak_user"></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{url('/cu_perjadin_bendahara')}}" method="post">
          @csrf
          <input type="hidden" name="statusPerjadin" value="{{$perjadin->is_acceptBend}}">
          <input type="hidden" name="idPerjadin" value="{{$perjadin->id}}">
          <div class="row">
            <div class="col-md-12 mb-3">
              <label for="uraian" class="form-label">Masukkan Alasan Ditolak<span class="text-secondary small"></span><span class="text-danger">*</span></label>
              <textarea id="tolak" name="alasanTolak" class="form-control" placeholder="Alasan Ditolak" required=""></textarea>
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
<!-- Modal Tolak USER -->
<div class="modal fade" id="batalkan_user_modal" tabindex="-1" aria-labelledby="batalkan_mobilitasLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="batalkan_user"></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{url('/cu_perjadin_bendahara')}}" method="post">
          @csrf
          <input type="hidden" name="statusPerjadin" value="{{$perjadin->is_acceptBend}}">
          <input type="hidden" name="idPerjadin" value="{{$perjadin->id}}">
          <div class="row">
            <div class="col-md-12 mb-3">
              <label for="uraian" class="form-label">Masukkan Alasan Dibatalkan<span class="text-secondary small"></span><span class="text-danger">*</span></label>
              <textarea id="batalkan" name="alasanBatalkan" class="form-control" placeholder="Alasan Dibatalkan" required=""></textarea>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary" name="action" value="batal-approval-2">Simpan</button>
      </div>
      </form>
    </div>
  </div>
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
        <form action="{{url('/cu_perjadin_bendahara')}}" method="post">
          @csrf
          <input type="hidden" name="statusPerjadin" value="{{$perjadin->is_acceptBend}}">
          <input type="hidden" name="idPerjadin" value="{{$perjadin->id}}">
          <div class="row">
            <div class="col-md-12 mb-3">
              <label for="uraian" class="form-label">Masukkan Alasan Revisi<span class="text-secondary small"></span><span class="text-danger">*</span></label>
              <textarea id="tolak" name="alasanHKT" class="form-control" placeholder="Alasan Revisi" required=""></textarea>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary" name="action" value="revisi-HKT">Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>
</div>

<!-- Modal Revisi Verifikator -->
<div class="modal fade" id="revisi_verifikator_modal" tabindex="-1" aria-labelledby="tolak_mobilitasLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="revisi_verifikator"></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{url('/cu_perjadin_bendahara')}}" method="post">
          @csrf
          <input type="hidden" name="statusPerjadin" value="{{$perjadin->is_acceptBend}}">
          <input type="hidden" name="idPerjadin" value="{{$perjadin->id}}">
          <div class="row">
            <div class="col-md-12 mb-3">
              <label for="uraian" class="form-label">Masukkan Alasan Revisi<span class="text-secondary small"></span><span class="text-danger">*</span></label>
              <textarea id="tolak" name="alasanVerifikator" class="form-control" placeholder="Alasan Revisi" required=""></textarea>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary" name="action" value="revisi-Verifikator">Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>
</div>

<!-- Akhir Dashboard - Kegiatan - Keuangan -->
<script src="{{asset('public/assets/js/pdfselected.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tambahkan event listener pada tombol trash
        document.querySelectorAll('.delete-fasilitas').forEach(function(button) {
            button.addEventListener('click', function() {
                // Dapatkan ID mobilitas dari atribut data-id
                var fasilitasId = this.getAttribute('data-id');
                var perjadinId = document.querySelector('input[name="idPerjadin"]').value;

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
    $(document).ready(function() {
        // Event listener for the first date input
        $('input[name="tglbayar_0"]').on('change', function() {
            // Get the selected date
            var selectedDate = $(this).val();

            // Iterate over all other date inputs and set the selected date
            $('input[name^="tglbayar_"]').not(this).each(function() {
                $(this).val(selectedDate);
            });
            $('input[name^="tglbayarnon_"]').not(this).each(function() {
                $(this).val(selectedDate);
            });
            $('input[name^="tglbayarKebutuhan_"]').not(this).each(function() {
                $(this).val(selectedDate);
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Event listener for the first date input
        $('input[name="tglbayarKebutuhan_0"]').on('change', function() {
            // Get the selected date
            var selectedDate = $(this).val();

            // Iterate over all other date inputs and set the selected date
            $('input[name^="tglbayarKebutuhan_"]').not(this).each(function() {
                $(this).val(selectedDate);
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
    // Event listener for the first dropdown
    $('select[name="kesesuaian_0"]').on('change', function() {
      // Get the selected value
      var selectedValue = $(this).val();
      // Get the selected text
      var selectedText = $(this).find("option:selected").text();

      // Iterate over all other dropdowns and set the selected value
      $('select[name^="kesesuaian_"]').not(this).each(function() {
        $(this).val(selectedValue).trigger('change');
        $(this).find("option:selected").text(selectedText);
      });
    });
  });
  </script>
<script>
    $(document).ready(function() {
    // Event listener for the first dropdown
    $('select[name="statuspegawai_0"]').on('change', function() {
      // Get the selected value
      var selectedValue = $(this).val();
      // Get the selected text
      var selectedText = $(this).find("option:selected").text();

      // Iterate over all other dropdowns and set the selected value
      $('select[name^="statuspegawai_"]').not(this).each(function() {
        $(this).val(selectedValue).trigger('change');
        $(this).find("option:selected").text(selectedText);
      });
      $('select[name^="statusnonpegawai_"]').not(this).each(function() {
        $(this).val(selectedValue).trigger('change');
        $(this).find("option:selected").text(selectedText);
      });
      $('select[name^="kesesuaian_"]').not(this).each(function() {
        $(this).val(selectedValue).trigger('change');
        $(this).find("option:selected").text(selectedText);
      });
    });
  });
  </script>
<script>
    $(document).ready(function() {
    // Event listener for the first dropdown
    $('select[name="statusnonpegawai_0"]').on('change', function() {
      // Get the selected value
      var selectedValue = $(this).val();
      // Get the selected text
      var selectedText = $(this).find("option:selected").text();

      // Iterate over all other dropdowns and set the selected value
      $('select[name^="statusnonpegawai_"]').not(this).each(function() {
        $(this).val(selectedValue).trigger('change');
        $(this).find("option:selected").text(selectedText);
      });
    });
  });
  </script>

<script>
   $(document).ready(function() {
    // Event listener for the first dropdown
    $('select[name="akunKebutuhan_0"]').on('change', function() {
      // Get the selected value
      var selectedValue = $(this).val();
      // Get the selected text
      var selectedText = $(this).find("option:selected").text();

      // Iterate over all other dropdowns and set the selected value
      $('select[name^="akunKebutuhan_"]').not(this).each(function() {
        $(this).val(selectedValue).trigger('change');
        $(this).find("option:selected").text(selectedText);
      });
    });
  });

  $(document).ready(function() {
    // Event listener for the first dropdown
    $('select[name="akunPegawai_0"]').on('change', function() {
      // Get the selected value
      var selectedValue = $(this).val();
      // Get the selected text
      var selectedText = $(this).find("option:selected").text();

      // Iterate over all other dropdowns and set the selected value
      $('select[name^="akunPegawai_"]').not(this).each(function() {
        $(this).val(selectedValue).trigger('change');
        $(this).find("option:selected").text(selectedText);
      });
      $('select[name^="akunNonPegawai_"]').not(this).each(function() {
        $(this).val(selectedValue).trigger('change');
        $(this).find("option:selected").text(selectedText);
      });
      $('select[name^="akunKebutuhan_0"]').not(this).each(function() {
        $(this).val(selectedValue).trigger('change');
        $(this).find("option:selected").text(selectedText);
      });
    });
  });

  $(document).ready(function() {
    // Event listener for the first dropdown
    $('select[name="sbmPegawai_0"]').on('change', function() {
      // Get the selected value
      var selectedValue = $(this).val();
      // Get the selected text
      var selectedText = $(this).find("option:selected").text();

      // Iterate over all other dropdowns and set the selected value
      $('select[name^="sbmPegawai_"]').not(this).each(function() {
        $(this).val(selectedValue).trigger('change');
        $(this).find("option:selected").text(selectedText);
      });
    });
  });

  $(document).ready(function() {
    // Event listener for the first dropdown
    $('select[name="sbmKebutuhan_0"]').on('change', function() {
      // Get the selected value
      var selectedValue = $(this).val();
      // Get the selected text
      var selectedText = $(this).find("option:selected").text();

      // Iterate over all other dropdowns and set the selected value
      $('select[name^="sbmKebutuhan_"]').not(this).each(function() {
        $(this).val(selectedValue).trigger('change');
        $(this).find("option:selected").text(selectedText);
      });
    });
  });

</script>
@endsection
