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
                          <div class="col-10">: {{$info->nama_kegiatan}}</div>
                      </div>
                      <div class="row small">
                          <div class="col-2">Tanggal Penyelenggaran</div>
                          <div class="col-10">: {{$info->tgl_mulai}}</div>
                      </div>
                      <div class="row small">
                          <div class="col-2">Lokasi</div>
                          <div class="col-10">: {{$info->alamat}}</div>
                      </div>
                      <div class="row small">
                          <div class="col-2">Verivikasi Dari BMN dan Keuangan</div>
                          <div class="col-10">: <span class="bg-info text-white px-3 py-1">{{$info->is_acceptBMN}}</span> | <span class="bg-info text-white px-3 py-1">{{$info->is_acceptKeu}}</span></div>
                      </div>
                      <div class="row small">
                          <div class="col-2">Status Pengaju</div>
                          <div class="col-10">: {{$info->status}} | <span class="bg-success text-white px-3 py-1">{{$info->is_acceptBend}}</span></div>
                      </div>
                      <br>
                  </div>
                
                  <form action="{{url('/cu_kegiatan_bendahara')}}" method="post">
                  @csrf
                  <input type="hidden" name="idKegiatan" value="{{$info->id}}">
                  @php
                      $numpegawai = 0;
                      $numnonpegawai = 0;
                      $numperangkat = 1;
                  @endphp
                  @foreach ($perangkats as $perangkat)
                  <div class="col-md-12 mb-3">
                    <div class="table-responsive">
                      <div class="d-flex justify-content-between">
                        <h5 class="fw-bold">Informasi {{$perangkat->nama_fasilitas}}</h5>
                        <a class="btn btn-success btn-sm mb-3" href="{{url('/pdfprint')}}">Print to PDF</a>                                   
                      </div>
                      <table id="calculationTable{{$numperangkat}}" name="{{$perangkat->nama_fasilitas}}" class="table table-bordered calculationTable" style="width: 100%">
                          <thead>
                          <tr class="text-center small">
                              <th>Nama Lengkap</th>
                              <th>Pangkat/Golongan</th>
                              <th>Sebagai</th>
                              <th>Detail</th>
                              <th>No Akun</th>
                              <th>SBM</th>
                              <th>Nominal</th>
                              <th>PPh21</th>
                              <th>PPh22</th>
                              <th>PPh23</th>
                              <th>PPn</th>
                              <th>Total</th>
                              <th>Tanggal Pembayaran</th>
                              <th>Status Pembiayaan</th>
                          </tr>
                          </thead>
                          <tbody>
                          @if ($pegawais->isNotEmpty())
                              @foreach ($pegawais as $pegawai)
                                @if ($pegawai->fasilitas_id == $perangkat->id)
                                <tr>
                                  <td style="min-width: 150px">{{$pegawai->nama_lengkap}} <input type="hidden" name="idPerangkatPegawai_{{$numpegawai}}" value="{{$pegawai->idPerangkatAcara}}"></td>
                                  <td class='text-center' style="min-width: 120px">{{$pegawai->pangkat}} | {{$pegawai->golongan}}</td>
                                  <td class='text-center' style="min-width: 120px">{{$pegawai->sebagai}}</td>
                                  <td class='text-center' style="min-width: 50px">{{$pegawai->satuan}}-{{$pegawai->detail_satuan}}</td>
                                  @foreach ($keuangans as $keuangan)
                                  @if ($keuangan->perangkat_acara == $pegawai->idPerangkatAcara)
                                    <td>
                                      <select class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="akunPegawai_{{$numpegawai}}">
                                        @foreach ($akuns as $akun)
                                        @if ($keuangan->akun_x_rkakl == $akun->idAkun)
                                        <option value="{{$akun->idAkun}}" selected>[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                        @endif
                                          <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                        @endforeach
                                      </select>
                                    </td>
                                    <td>
                                      <select class="js-example-basic-single-3 form-select mySelect" aria-label="Default select example" style="min-width: 300px" name="sbmPegawai_{{$numpegawai}}">
                                        @foreach ($sbms as $sbm)
                                        @if ($keuangan->ref_sbm == $sbm->id)
                                        <option value="{{$sbm->id}}" data-label="{{$sbm->biaya}}" selected>[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                                        @endif
                                        <option value="{{$sbm->id}}" data-label="{{$sbm->biaya}}" >[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                                        @endforeach
                                      </select>
                                    </td>
                                    <td style="min-width: 200px">
                                      <input type="number" class="form-control num1 prevent-submit" min="0" name="nominalPegawai_{{$numpegawai}}" value="{{$keuangan->harga}}">
                                    </td>
                                    <td style="min-width: 100px">
                                      <div class="input-group mb-3">
                                        <input type="number" class="form-control num2 prevent-submit" min="0" name="pajakPegawai_{{$numpegawai}}" value="{{$keuangan->persen_pajak}}">
                                        <span class="input-group-text" id="basic-addon2">%</span>
                                      </div>
                                    </td>
                                    <td style="min-width: 100px">
                                      <div class="input-group mb-3">
                                        <input type="number" class="form-control num3 prevent-submit" min="0" name="pajakPegawaipph22_{{$numpegawai}}" value="{{$keuangan->pph22}}">
                                        <span class="input-group-text" id="basic-addon2">%</span>
                                      </div>
                                    </td>
                                    <td style="min-width: 100px">
                                      <div class="input-group mb-3">
                                        <input type="number" class="form-control num4 prevent-submit" min="0" name="pajakPegawaipph23_{{$numpegawai}}" value="{{$keuangan->pph23}}">
                                        <span class="input-group-text" id="basic-addon2">%</span>
                                      </div>
                                    </td>
                                    <td style="min-width: 100px">
                                      <div class="input-group mb-3">
                                        <input type="number" class="form-control num5 prevent-submit" min="0" name="pajakPegawaippn_{{$numpegawai}}" value="{{$keuangan->ppn}}">
                                        <span class="input-group-text" id="basic-addon2">%</span>
                                      </div>
                                    </td>
                                    <td style="min-width: 200px">
                                      <input type="number" class="result form-control" name="totalPegawai_{{$numpegawai}}" value="{{$keuangan->jumlah_harga}}" readonly>
                                    </td>
                                    <td style="min-width: 200px">
                                      <input type="datetime-local" class="result form-control" name="tglbayarPegawai_{{$numpegawai}}" value="{{$keuangan->tgl_bayar}}">
                                    </td>
                                    <td style="min-width: 150px">
                                      <input type="hidden" name="idPegawai_{{$numpegawai}}" value="{{$pegawai->idPegawai}}">
                                      <select class="form-select" aria-label="Default select example" name="statusPembiyaanPegawai_{{$numpegawai}}">
                                        <option value="{{$keuangan->status}}">{{$keuangan->status}}</option>
                                        <option value="Belum Dibayarkan">Belum Dibayarkan</option>
                                        <option value="Tidak Dibayarkan">Tidak Dibayarkan</option>
                                        <option value="Sudah Dibayarkan">Sudah Dibayarkan</option>
                                      </select>
                                    </td>
                                  @endif
                                  @endforeach
                                </tr>
                                @php
                                    $numpegawai++;
                                @endphp
                                @endif
                              @endforeach
                              @endif
                              
                              @if ($nonpegawais->isNotEmpty())
                                @foreach ($nonpegawais as $nonpegawai)     
                                  @if ($nonpegawai->fasilitas_id == $perangkat->id)
                                    <tr>
                                        <td style="min-width: 150px">{{$nonpegawai->nama_lengkap}} <input type="hidden" name="idPerangkatNonPegawai_{{$numnonpegawai}}" value="{{$nonpegawai->idPerangkatAcara}}"> </td>
                                        <td style="min-width: 120px">{{$nonpegawai->pangkat}} | {{$nonpegawai->golongan}}</td>
                                        <td style="min-width: 120px">{{$nonpegawai->sebagai}}</td>
                                        <td style="min-width: 50px">{{$nonpegawai->satuan}}-{{$nonpegawai->detail_satuan}}</td>
                                        @foreach ($keuangans as $keuangan)
                                        @if ($keuangan->perangkat_acara == $nonpegawai->idPerangkatAcara)
                                          <td>
                                            <select class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px;" name="akunNonPegawai_{{$numnonpegawai}}">
                                              @foreach ($akuns as $akun)
                                              @if ($keuangan->akun_x_rkakl == $akun->idAkun)
                                                <option value="{{$akun->idAkun}}" selected>[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                              @endif
                                                <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                              @endforeach
                                            </select>
                                          </td>
                                          <td>
                                            <select class="form-select mySelect" aria-label="Default select example" style="min-width: 300px" name="sbmNonPegawai_{{$numnonpegawai}}">
                                              @foreach ($sbms as $sbm)
                                              @if ($keuangan->ref_sbm == $sbm->id)
                                              <option value="{{$sbm->id}}" data-label="{{$sbm->biaya}}" selected>[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                                              @endif
                                              <option value="{{$sbm->id}}" data-label="{{$sbm->biaya}}">[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                                              @endforeach
                                            </select>
                                          </td>
                                          <td style="min-width: 200px">
                                            <input type="number" class="form-control num1 prevent-submit" min="0" name="nominalNonPegawai_{{$numnonpegawai}}" value="{{$keuangan->harga}}">
                                          </td>
                                          <td style="min-width: 100px">
                                            <div class="input-group mb-3">
                                              <input type="number" class="form-control num2 prevent-submit" min="0" name="pajakNonPegawai_{{$numnonpegawai}}" value="{{$keuangan->persen_pajak}}">
                                              <span class="input-group-text" id="basic-addon2">%</span>
                                            </div>
                                          </td>
                                          <td style="min-width: 100px">
                                            <div class="input-group mb-3">
                                              <input type="number" class="form-control num3 prevent-submit" min="0" name="pajakNonPegawaipph22_{{$numnonpegawai}}" value="{{$keuangan->pph22}}">
                                              <span class="input-group-text" id="basic-addon2">%</span>
                                            </div>
                                          </td>
                                          <td style="min-width: 100px">
                                            <div class="input-group mb-3">
                                              <input type="number" class="form-control num4 prevent-submit" min="0" name="pajakNonPegawaipph23_{{$numnonpegawai}}" value="{{$keuangan->pph23}}">
                                              <span class="input-group-text" id="basic-addon2">%</span>
                                            </div>
                                          </td>
                                          <td style="min-width: 100px">
                                            <div class="input-group mb-3">
                                              <input type="number" class="form-control num5 prevent-submit" min="0" name="pajakNonPegawaippn_{{$numnonpegawai}}" value="{{$keuangan->ppn}}">
                                              <span class="input-group-text" id="basic-addon2">%</span>
                                            </div>
                                          </td>
                                          <td style="min-width: 200px">
                                            <input type="number" class="result form-control" name="totalNonPegawai_{{$numnonpegawai}}" value="{{$keuangan->jumlah_harga}}" readonly>
                                          </td>
                                          <td style="min-width: 200px">
                                            <input type="datetime-local" class="result form-control" name="tglbayarNonPegawai_{{$numnonpegawai}}" value="{{$keuangan->tgl_bayar}}">
                                          </td>
                                          <td style="min-width: 150px">
                                            <input type="hidden" name="idNonPegawai_{{$numnonpegawai}}" value="{{$nonpegawai->idNonPegawai}}">
                                            <select class="form-select" aria-label="Default select example" name="statusPembayaranNonPegawai_{{$numnonpegawai}}">
                                              <option value="{{$keuangan->status}}">{{$keuangan->status}}</option>
                                              <option value="Belum Dibayarkan">Belum Dibayarkan</option>
                                              <option value="Tidak Dibayarkan">Tidak Dibayarkan</option>
                                              <option value="Sudah Dibayarkan">Sudah Dibayarkan</option>
                                            </select>
                                          </td>
                                        @endif
                                        @endforeach
                                      </tr>
                                      @php
                                        $numnonpegawai++;
                                    @endphp
                                      @endif
                                @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                              <tr>
                                <td colspan="11" class="fw-bold text-end">Sub Total</td>
                                <td><input type="number" class="total form-control" readonly></td>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                      @php
                          $numperangkat++;
                      @endphp
                      @endforeach
                      <input type="hidden" name="numPegawai" value="{{$numpegawai}}">
                      <input type="hidden" name="numNonPegawai" value="{{$numnonpegawai}}">

                    @if ($operasionals->isNotEmpty())
                    <div class="col-md-12 mb-3">
                      <div class="table-responsive">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="fw-bold">Informasi Fasilitas</h5>
                            </div>                                           
                        </div>
                        <table id="calculationTable3" name="Fasilitas" class="table table-bordered calculationTable" style="width: 100%">
                          <thead>
                                <tr class="text-center small">
                                    <th>No</th>
                                    <th>Nama Fasilitas</th>
                                    <th>Jumlah</th>
                                    <th>Detail</th>
                                    <th>No Akun</th>
                                    <th>SBM</th>
                                    <th>Nominal</th>
                                    <th>PPh21 [%]</th>
                                    <th>PPh22 [%]</th>
                                    <th>PPh23 [%]</th>
                                    <th>PPn [%]</th>
                                    <th>Total</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                              @php
                                  $numoperasional = 0;
                              @endphp
                              @foreach ($operasionals as $operasional)
                                <tr>
                                    <td class='text-center' style="min-width: 30px">{{$loop->iteration}} <input type="hidden" name="idOperasional_{{$numoperasional}}" value="{{$operasional->id}}"> </td>
                                    <td style="min-width: 300px">{{$operasional->nama}}</td>
                                    <td class='text-center' style="min-width: 50px">{{$operasional->jumlah_frekuensi}}</td>
                                    <td class='text-center' style="min-width: 100px">{{$operasional->satuan}}-{{$operasional->detail_satuan}}</td>
                                    @if ($keuanganoperasionals->isNotEmpty())
                                      @foreach ($keuanganoperasionals as $keuanganoperasional)
                                        @if ($keuanganoperasional->operasional == $operasional->id)
                                          <td>
                                            <select class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="akunOperasional_{{$numoperasional}}">
                                              @foreach ($akuns as $akun)
                                              <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                              @endforeach
                                            </select>
                                          </td>
                                          <td>
                                            <select class="form-select mySelect" aria-label="Default select example" style="min-width: 300px; height: 50px; white-space: pre-wrap;" name="sbmOperasional_{{$numoperasional}}">
                                              @foreach ($sbms as $sbm)
                                              <option value="{{$sbm->id}}" data-label="{{$sbm->biaya}}">[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                                              @endforeach
                                            </select>
                                          </td>
                                          <td style="min-width: 200px">
                                            <input type="number" class="form-control num1 prevent-submit" min="0" name="nominalOperasional_{{$numoperasional}}" value="{{$keuanganoperasional->harga}}">
                                          </td>
                                          <td style="min-width: 100px">
                                            <div class="input-group mb-3">
                                              <input type="number" class="form-control num2 prevent-submit" min="0" name="pajakOperasional_{{$numoperasional}}" value="{{$keuanganoperasional->persen_pajak}}">
                                              <span class="input-group-text" id="basic-addon2">%</span>
                                            </div>
                                          </td>
                                          <td style="min-width: 100px">
                                            <div class="input-group mb-3">
                                              <input type="number" class="form-control num3 prevent-submit" min="0" name="pajakOperasionalpph22_{{$numoperasional}}" value="{{$keuanganoperasional->pph22}}">
                                              <span class="input-group-text" id="basic-addon2">%</span>
                                            </div>
                                          </td>
                                          <td style="min-width: 100px">
                                            <div class="input-group mb-3">
                                              <input type="number" class="form-control num4 prevent-submit" min="0" name="pajakOperasionalpph23_{{$numoperasional}}" value="{{$keuanganoperasional->pph23}}">
                                              <span class="input-group-text" id="basic-addon2">%</span>
                                            </div>
                                          </td>
                                          <td style="min-width: 100px">
                                            <div class="input-group mb-3">
                                              <input type="number" class="form-control num5 prevent-submit" min="0" name="pajakOperasionalppn_{{$numoperasional}}" value="{{$keuanganoperasional->ppn}}">
                                              <span class="input-group-text" id="basic-addon2">%</span>
                                            </div>
                                          </td>
                                          <td style="min-width: 200px">
                                            <input type="number" class="result form-control" name="totalOperasional_{{$numoperasional}}" value="{{$keuanganoperasional->jumlah_harga}}" readonly>
                                          </td>
                                          <td style="min-width: 200px">
                                            <input type="datetime-local" class="result form-control" name="tglbayarOperasional_{{$numoperasional}}" value="{{$keuanganoperasional->tgl_bayar}}" >
                                          </td>
                                          <td style="min-width: 150px">
                                              <select class="form-select" aria-label=".form-select-sm example" name="kesesuaianOperasional_{{$numoperasional}}">
                                                <option value="{{$keuanganoperasional->status}}">{{$keuanganoperasional->status}}</option>
                                                <option value="Belum Dibayarkan">Belum Dibayarkan</option>
                                                  <option value="Tidak Dibayarkan">Tidak Dibayarkan</option>
                                                  <option value="Sudah Dibayarkan">Sudah Dibayarkan</option>
                                              </select>
                                          </td>
                                        @endif
                                      @endforeach
                                    @endif
                                  </tr>
                                @php
                                    $numoperasional++;
                                @endphp
                              @endforeach
                              <input type="hidden" name="numOperasional" value="{{$numoperasional}}">
                            </tbody>
                            <tfoot>
                              <tr>
                                <td colspan="11" class="fw-bold text-end">Sub Total</td>
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
                      @if (($info->is_acceptBend == 'approval-1') | ($info->is_acceptBend == 'approval-2') | ($info->is_acceptBend == 'revisi'))
                      <a href="{{url('/kegiatan-bendahara/' . 'approval-1')}}" class="btn btn-dark">Kembali</a>
                      <button class="btn btn-danger" type="submit" name="action" value="tolak">Tolak Program Kegiatan</button>
                      <!-- <button class="btn btn-info text-white" type="submit" name="action" value="revisi">Revisi</button> -->
                      @if ($info->is_acceptBend == 'approval-1')
                      <button class="btn btn-success" type="submit" name="action" value="approve-1">Approv Kegiatan</button>
                      @endif
                      @if ($info->is_acceptBend == 'approval-2')
                      <button class="btn btn-success" type="submit" name="action" value="approve-2">Approv Kegiatan dan selesaikan</button>
                      @endif
                      @else
                      <a href="{{url('/kegiatan-bendahara/' . 'approval-1')}}" class="btn btn-dark">Kembali</a>
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
  <!-- Akhir Dashboard - Kegiatan - Keuangan -->

  <script src="{{asset('assets/js/pdfselected.js')}}"></script>
@endsection