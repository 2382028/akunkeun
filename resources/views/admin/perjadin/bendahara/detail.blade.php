@extends('admin.templates.sidebar')

@section('contain')
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
                <div class="col-2">Nama Kegiatan</div>
                <div class="col-10">: {{$perjadin->nama_kegiatan}}</div>
              </div>
              <div class="row small">
                <div class="col-2">Tanggal Penyelenggaran</div>
                <div class="col-10">: {{$perjadin->tgl_mulai}} s.d {{$perjadin->tgl_selesai}}</div>
              </div>
              <div class="row small">
                <div class="col-2">Lokasi</div>
                <div class="col-10">: {{$perjadin->kabupaten_kota}}</div>
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
                        @if ($perjadin->is_acceptKeu == 'verifikasi-2')
                        <th class="th-sm">Tanggal Penerimaan Berkas</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody>
                    @if (!empty($dokumen) && $dokumen->isNotEmpty())
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
                          @if ($dokumen[0]->surat_undangan != null)
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
                            @if ($dokumen[0]->SPPD!= null)
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
                            @if ($dokumen[0]->hasil)
                            <a href="{{url('/note-perjadin-admin/' . $perjadin->id)}}" target="_blank" class="btn btn-sm btn-secondary"><i class="fa-solid fa-eye"></i> Lihat Laporan</a>
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
                @unless($perjadin->is_acceptKeu == 'verifikasi-2')
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
                        <th>SBM</th>
                        <th>Uang Harian</th>
                        <th>Uang Harian Fullday</th>
                        <th>Uang Harian Fullboard</th>
                        <th>Uang Representasi</th>
                        <th>Total Pembayaran Bersih</th>
                        <th>Tanggal Bayar</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php
                      $numpegawai = 0;
                      $numnonpegawai = 0;
                      @endphp
                      @foreach ($pesertaPegawais as $pesertaPegawai)
                      <tr>
                        <td style="min-width: 150px">{{$pesertaPegawai->nama_lengkap}} <input type="hidden" name="idKeuangan_{{$numpegawai}}" value="{{$pesertaPegawai->idData}}"></td>
                        <td style="min-width: 150px">{{$pesertaPegawai->pangkat}}-{{$pesertaPegawai->golongan}}</td>
                        <td style="min-width: 120px">{{$pesertaPegawai->status_pegawai}}</td>
                        <td>
                          <select class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="akunPegawai_{{$numpegawai}}">
                            @foreach ($akuns as $akun)
                            @if ($pesertaPegawai->akun_x_rkakl == $akun->idAkun)
                            <option value="{{$akun->idAkun}}" selected>[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                            @endif
                            <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                            @endforeach
                          </select>
                        </td>
                        <td style="min-width: 300px">
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
                            <option value="{{$pesertaPegawai->status}}">{{$pesertaPegawai->status}}</option>
                            <option value="Belum Dibayarkan">Belum Dibayarkan</option>
                            <option value="Tidak Dibayarkan">Tidak Dibayarkan</option>
                            <option value="Sudah Dibayarkan">Sudah Dibayarkan</option>
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
                            @if ($pesertaNonPegawai->akun_x_rkakl == $akun->idAkun)
                            <option value="{{$akun->idAkun}}" selected>[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                            @endif
                            <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                            @endforeach
                          </select>
                        </td>
                        <td>
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
                          <input type="number" class="form-control num1 prevent-submit" min="0" name="nominalNon_{{$numnonpegawai}}" value="{{$pesertaNonPegawai->uang_harian}}">
                        </td>
                        <td style="min-width: 200px">
                          <input type="number" class="result form-control" name="totalNon_{{$numnonpegawai}}" value="{{$pesertaNonPegawai->jumlah_harga}}">
                        </td>
                        <td style="min-width: 200px">
                          @if (($perjadin->is_acceptKeu== 'verifikasi-1'))
                          <input type="date" class="result form-control" name="tglbayarnon_{{$numnonpegawai}}" value="{{$pesertaNonPegawai->tgl_bayar}}" readonly>
                          @endif
                        </td>
                        <td>
                          <select class="form-select" aria-label="Default select example" style="min-width: 150px" name="statusnonpegawai_{{$numnonpegawai}}">
                            <option value="{{$pesertaNonPegawai->status}}">{{$pesertaNonPegawai->status}}</option>
                            <option value="Belum Dibayarkan">Belum Dibayarkan</option>
                            <option value="Tidak Dibayarkan">Tidak Dibayarkan</option>
                            <option value="Sudah Dibayarkan">Sudah Dibayarkan</option>
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
                @endunless
              </div>
            </div>

          @if (($perjadin->is_acceptKeu == 'verifikasi-2') || ($perjadin->is_acceptKeu == 'revisi') || ($perjadin->is_acceptKeu == 'selesai'))
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
                        <th>Nama Fasilitas</th>
                        <th>Jumlah</th>
                        <th>Detail</th>
                        <th>Tipe Pendanaan</th>
                        <th>Keterangan</th>
                        <th>Akun</th>
                        <th>SBM</th>
                        <th>Nominal</th>
                        <th>Total Pembayaran Bersih</th>
                        <th>Tanggal Bayar</th>
                        <th>Status</th>
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
                        <td class='text-center' style="min-width: 100px">{{$kebutuhan->ket}}</td>
                        <td>
                          <select class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="akunKebutuhan_{{$numkebutuhan}}">
                            @foreach ($akuns as $akun)
                            @if ($kebutuhan->akun_x_rkakl == $akun->idAkun)
                            <option value="{{$akun->idAkun}}" selected>[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                            @endif
                            <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                            @endforeach
                          </select>
                        </td>
                        <td>
                          <select class="form-select mySelect" aria-label="Default select example" style="min-width: 300px" name="sbmKebutuhan_{{$numkebutuhan}}">
                            @foreach ($sbms as $sbm)
                            @if ($kebutuhan->ref_sbm == $sbm->id)
                            <option value="{{$sbm->id}}" selected>[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                            @endif
                            <option value="{{$sbm->id}}" data-label="{{$sbm->biaya}}">[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                            @endforeach
                          </select>
                        </td>
                        <td style="min-width: 200px">
                          <input type="number" class="form-control num1 prevent-submit" min="0" name="nominalKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->uang_harian}}">
                        </td>
                        <td style="min-width: 200px">
                          <input type="number" class="result form-control" name="totalKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->jumlah_harga}}">
                        </td>
                        <td style="min-width: 200px">
                          <input type="date" class="result form-control" name="tglbayarKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->tgl_bayar}}" required>
                        </td>
                        <td>
                          <select class="form-select" aria-label=".form-select-sm example" style="min-width: 150px" name="kesesuaian_{{$numkebutuhan}}">
                            <option value="{{$kebutuhan->statusPembayaran}}" selected>{{$kebutuhan->statusPembayaran}}</option>
                            <option value="Belum Dibayarkan">Belum Dibayarkan</option>
                            <option value="Tidak Dibayarkan">Tidak Dibayarkan</option>
                            <option value="Sudah Dibayarkan">Sudah Dibayarkan</option>
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
                        <td colspan="7" class="fw-bold text-end">Sub Total</td>
                        <td><input type="number" class="total form-control" readonly></td>
                      </tr>
                    </tfoot>
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

              <div class="col-md-12 mb-3">
                <div class="d-grid gap-2 d-md-flex justify-content-center">
                  <a href="{{url('/perjadin-bendahara/' . 'approval-1')}}" class="btn btn-dark">Kembali</a>
                  @if (($perjadin->is_acceptBend == 'approval-1') | ($perjadin->is_acceptBend == 'approval-2'))
                  <button class="btn btn-danger submitButton" type="submit" name="action" value="tolak">Tolak Perjalanan dinas</button>
                  <button class="btn btn-primary submitButton" type="submit" name="action" value="simpan">Simpan Draf</button>
                  @endif
                  @if (($perjadin->is_acceptBend == 'approval-1') | ($perjadin->is_acceptBend == 'revisi') | ($perjadin->is_acceptBend == 'ditolak'))
                  <button class="btn btn-success submitButton" type="submit" name="action" value="approval">Approval Tahap 1</button>
                  @endif
                  @if ($perjadin->is_acceptBend == 'approval-2')
                  <button class="btn btn-success submitButton" type="submit" name="action" value="approval-2">Approval Tahap 2</button>
                  @endif
                </div>
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
  <!-- Akhir Dashboard - Kegiatan - Keuangan -->
  <script src="{{asset('public/assets/js/pdfselected.js')}}"></script>
  @endsection