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
                        <div class="col-2">Verivikasi Dari BMN dan Bendahara</div>
                        <div class="col-10">: <span class="bg-info text-white px-3 py-1">{{$info->is_acceptBMN}}</span> | <span class="bg-info text-white px-3 py-1">{{$info->is_acceptBend}}</span></div>
                      </div>
                      <div class="row small">
                          <div class="col-2">Status Pengaju</div>
                          <div class="col-10">: {{$info->status}} | <span class="bg-success text-white px-3 py-1">{{$info->is_acceptKeu}}</span></div>
                      </div>
                      <br>
                  </div>
                
                  <form action="{{url('/cu_kegiatan_keuangan')}}" method="post">
                  @csrf
                  <input type="hidden" name="idKegiatan" value="{{$info->id}}">
                  <input type="hidden" name="statusKegiatan" value="{{$info->is_acceptKeu}}">
                  <div class="col-md-12 mb-3">
                      <h5 class="fw-bold">Informasi Dokumen</h5>
                      <div class="table-responsive">                        
                          <table id="example" class="table table-bordered" style="width: 100%">
                              <thead>
                                  <tr class="text-center small">
                                      <th class="th-sm">No</th>
                                      <th class="th-lg">Nama Dokumen</th>
                                      <th class="th-md">Aksi</th>
                                      <th class="th-md">Status</th>
                                      <th class="th-md">Keterangan</th>
                                  </tr>
                              </thead>
                              <tbody>
                                @php
                                    $numDokumen = 0;
                                @endphp
                                @foreach ($dokumens as $dokumen)
                                <tr>
                                    <td class='text-center'>{{$loop->iteration}} <input type="hidden" name="idDokumen_{{$numDokumen}}" value="{{$dokumen->id}}"></td>
                                    <td>{{$dokumen->nama_dokumen}}</td>
                                    <td class='text-center'>
                                    <td class=''><a href="{{ url('kegiatan/getDokumen/' . basename($dokumen->file)) }}" target="_blank" class="btn btn-secondary btn-sm"><i class="fa-solid fa-eye"></i> Lihat</a></td>    
                                    <!-- <a href="{{asset('/storage/' . $dokumen->file)}}" target="_blank" class="btn btn-secondary btn-sm"><i class="fa-solid fa-eye"></i> Lihat</a> -->
                                    </td>    
                                      <td class='text-center'>
                                          <span>
                                              <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="statusDokumen_{{$numDokumen}}">
                                                  <option value="{{$dokumen->status}}">{{$dokumen->status}}</option>
                                                  <option value="sesuai">Sesuai</option>
                                                  <option value="revisi">Tidak Sesuai - Revisi</option>
                                                 </select>
                                            </span>
                                        </td>
                                        <td>
                                          @if ($dokumen->keterangan == null)
                                          <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="keteranganDokumen_{{$numDokumen}}"></textarea>
                                          @else
                                          <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="keteranganDokumen_{{$numDokumen}}">{{$dokumen->keterangan}}</textarea>
                                          @endif
                                        </td>
                                    </tr>
                                    @php
                                        $numDokumen++;
                                    @endphp
                                    @endforeach
                                    <input type="hidden" name="numDokumen" value="{{$numDokumen}}">
                              </tbody>
                          </table>
                      </div>
                  </div>

                  @php
                      $numpegawai = 0;
                      $numnonpegawai = 0;
                  @endphp
                  @foreach ($perangkats as $perangkat)
                  <div class="col-md-12 mb-3">
                    <div class="table-responsive">
                      <div class="d-flex justify-content-between">
                          <div>
                              <h5 class="fw-bold">Informasi {{$perangkat->nama_fasilitas}}</h5>
                          </div>                                           
                      </div>
                      <table id="example" class="table table-bordered" style="width: 100%">
                          <thead>
                          <tr class="text-center small">
                              <th>Nama Lengkap</th>
                              <th>Pangkat/Golongan</th>
                              <th>Sebagai</th>
                              <th>Status</th>
                          </tr>
                          </thead>
                          @if ($pegawais->isNotEmpty())
                              @foreach ($pegawais as $pegawai)
                                @if ($pegawai->fasilitas_id == $perangkat->id)
                                <tr>
                                  <td>{{$pegawai->nama_lengkap}}</td>
                                  <td  class='text-center'>{{$pegawai->pangkat}} | {{$pegawai->golongan}}</td>
                                  <td class='text-center'>{{$pegawai->sebagai}}</td>
                                  <td>
                                    <input type="hidden" name="idPegawai_{{$numpegawai}}" value="{{$pegawai->idPegawai}}">
                                    <select class="form-select" aria-label="Default select example" name="statusPegawai_{{$numpegawai}}">
                                      <option value="{{$pegawai->status}}">{{$pegawai->status}}</option>
                                      <option value="disetujui">Setujui</option>
                                      <option value="ditolak">Tolak</option>
                                    </select>
                                  </td>
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
                                  <td>{{$nonpegawai->nama_lengkap}}</td>
                                    <td>{{$nonpegawai->pangkat}} | {{$nonpegawai->golongan}}</td>
                                    <td>{{$nonpegawai->sebagai}}</td>
                                    <td>
                                      <input type="hidden" name="idNonPegawai_{{$numnonpegawai}}" value="{{$nonpegawai->idNonPegawai}}">
                                      <select class="form-select" aria-label="Default select example" name="statusNonPegawai_{{$numnonpegawai}}">
                                        <option value="{{$nonpegawai->status}}">{{$nonpegawai->status}}</option>
                                        <option value="disetujui">Setujui</option>
                                        <option value="ditolak">Tolak</option>
                                      </select>
                                    </td>
                                  </tr>
                                  @php
                                    $numnonpegawai++;
                                @endphp
                              @endif
                              @endforeach
                            @endif
                          </table>
                        </div>
                      </div>
                      @endforeach
                      <input type="hidden" name="numPegawai" value="{{$numpegawai}}">
                      <input type="hidden" name="numNonPegawai" value="{{$numnonpegawai}}">

                  @if ($info->is_acceptKeu != 'verifikasi-1')
                    @if ($operasionals->isNotEmpty())
                    <div class="col-md-12 mb-3">
                      <div class="table-responsive">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="fw-bold">Informasi Fasilitas</h5>
                            </div>                                           
                        </div>
                        <table id="calculationTable1" class="table table-bordered calculationTable" style="width: 100%">
                          <thead>
                                <tr class="text-center small">
                                    <th>No</th>
                                    <th>Nama Fasilitas</th>
                                    <th>Jumlah</th>
                                    <th>Detail</th>
                                    <th>Nominal</th>
                                    <th>PPh</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                              @php
                                  $numoperasional = 0;
                              @endphp
                              @foreach ($operasionals as $operasional)
                                <tr>
                                    <td class='text-center'>{{$loop->iteration}} <input type="hidden" name="idOperasional_{{$numoperasional}}" value="{{$operasional->id}}"> </td>
                                    <td>{{$operasional->nama}}</td>
                                    <td>{{$operasional->jumlah_frekuensi}}</td>
                                    <td>{{$operasional->satuan}}-{{$operasional->detail_satuan}}</td>
                                    @if ($keuanganoperasionals->isNotEmpty())
                                      @foreach ($keuanganoperasionals as $keuanganoperasional)
                                        @if ($keuanganoperasional->operasional == $operasional->id)
                                          <td>
                                            <input type="number" class="form-control num1 prevent-submit" name="nominal_{{$numoperasional}}" value="{{$keuanganoperasional->harga}}">
                                          </td>
                                          <td>
                                            <div class="input-group mb-3">
                                              <input type="number" class="form-control num2 prevent-submit" name="pajak_{{$numoperasional}}" value="{{$keuanganoperasional->persen_pajak}}">
                                              <span class="input-group-text" id="basic-addon2">%</span>
                                            </div>
                                          </td>
                                          <td>
                                            <input type="number" class="result form-control" name="total_{{$numoperasional}}" value="{{$keuanganoperasional->jumlah_harga}}" readonly>
                                          </td>
                                        @endif
                                      @endforeach
                                    @else
                                      <td>
                                        <input type="number" class="form-control num1 prevent-submit" name="nominal_{{$numoperasional}}">
                                      </td>
                                      <td>
                                        <div class="input-group mb-3">
                                          <input type="number" class="form-control num2 prevent-submit" name="pajak_{{$numoperasional}}">
                                          <span class="input-group-text" id="basic-addon2">%</span>
                                        </div>
                                      </td>
                                      <td>
                                        <input type="number" class="result form-control" name="total_{{$numoperasional}}" readonly>
                                      </td>
                                    @endif
                                    <td>
                                        <select class="form-select" aria-label=".form-select-sm example" name="kesesuaian_{{$numoperasional}}">
                                          @if ($operasional->status == null)
                                          <option value="sesuai">Sesuai</option>
                                          <option value="tidak sesuai">Tidak Sesuai</option>
                                          @else
                                          <option value="{{$operasional->status}}">{{$operasional->status}}</option>
                                          <option value="sesuai">Sesuai</option>
                                          <option value="tidak sesuai">Tidak Sesuai</option>
                                          @endif
                                        </select>
                                    </td>
                                  </tr>
                                @php
                                    $numoperasional++;
                                @endphp
                              @endforeach
                              <input type="hidden" name="numOperasional" value="{{$numoperasional}}">
                            </tbody>
                            <tfoot>
                              <tr>
                                <td colspan="6" class="fw-bold text-end">Sub Total</td>
                                <td><input type="number" class="total form-control" readonly></td>
                              </tr>
                            </tfoot>
                        </table>
                      </div>
                    </div>
                    @endif
                  @endif


                  <div class="col-md-12 mb-3">
                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                  @if (($info->is_acceptKeu == 'verifikasi-1') | ($info->is_acceptKeu == 'verifikasi-2') | ($info->is_acceptKeu == 'revisi'))
                      <a href="{{url('/kegiatan-keuangan/' . 'verifikasi-1')}}" class="btn btn-dark">Kembali</a>
                      <button class="btn btn-danger" type="submit" name="action" value="tolak">Tolak Program Kegiatan</button>
                      <button class="btn btn-info text-white" type="submit" name="action" value="revisi">Revisi</button>
                    @if ($info->is_acceptKeu == 'verifikasi-1')
                      <button class="btn btn-success" type="submit" name="action" value="verifikasi">Verifikasi Tahap 1 dan Kirim Ke-Bendahara</button>
                    @endif
                    @if ($info->is_acceptKeu == 'verifikasi-2')
                      <button class="btn btn-success" type="submit" name="action" value="verifikasi-2">Verifikasi Tahap 2 dan Kirim Ke-Bendahara</button>
                    @endif
                  @else
                      <a href="{{url('/kegiatan-keuangan/' . 'verifikasi-1')}}" class="btn btn-dark">Kembali</a>
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
@endsection