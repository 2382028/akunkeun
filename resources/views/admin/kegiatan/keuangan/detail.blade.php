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
                    <div class="col-2">ID Kegiatan</div>
                    <div class="col-10">: {{$info->id}}</div>
                </div>
                  <div class="row small">
                      <div class="col-2">Nama Kegiatan</div>
                      <div class="col-10">: {{$info->nama_kegiatan}}</div>
                  </div>
                  <div class="row small">
                      <div class="col-2">Tanggal Penyelenggaran</div>
                      <div class="col-10">: {{\Carbon\Carbon::parse($info->tgl_mulai)->format('d-m-Y H:i')}}</div>
                  </div>

                <div class="row small">
                    <div class="col-2">Tanggal Selesai</div>
                    <div class="col-10">: {{\Carbon\Carbon::parse($info->tgl_selesai)->format('d-m-Y H:i')}}</div>
                </div>
                <div class="row small">
                    <div class="col-2">Lokasi</div>
                    <div class="col-10">: {{$info->alamat}}</div>
                </div>
                <div class="row small">
                    <div class="col-2">Keterangan Diantar</div>
                    <div class="col-10">: {{$info->tujuan_penggunaan}}</div>
                </div>
                  <div class="row small">
                      <div class="col-2">Verifikasi Dari BMN dan Keuangan</div>
                      <div class="col-10">: <span class="bg-info text-white px-3 py-1">{{$info->is_acceptBMN}}</span> | <span class="bg-info text-white px-3 py-1">{{$info->is_acceptKeu}}</span></div>
                  </div>
                  <div class="row small">
                      <div class="col-2">Status Pengaju</div>
                      <div class="col-10">: {{$info->status}} | <span class="bg-success text-white px-3 py-1">{{$info->is_acceptBend}}</span></div>
                  </div>
                    @if (($info->is_acceptKeu == 'revisi-2') || $info->is_acceptKeu == 'ditolak')
                        <div class="row small">
                            <div class="col-2">Alasan Penolakan/Revisi</div>
                            <div class="col-10">:<br>{!! nl2br(e($info->alasan_penolakan)) !!}
                            </div>
                        </div>
                    @endif

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
                                      @if (($info->is_acceptKeu == 'verifikasi-2' && $info->status == 'selesai'))
                                        <th class="th-md">Status</th>
                                        {{-- <th class="th-md">Keterangan</th> --}}
                                      @endif
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
                                    <td class='text-center'><a href="{{ url('adminkegiatan/getDokumen/' . basename($dokumen->file)) }}" target="_blank" class="btn btn-secondary btn-sm"><i class="fa-solid fa-eye"></i> Lihat</a></td>
                                    <!-- <a href="{{asset('/storage/' . $dokumen->file)}}" target="_blank" class="btn btn-secondary btn-sm"><i class="fa-solid fa-eye"></i> Lihat</a> -->
                                    </td>
                                    @if (($info->is_acceptKeu == 'verifikasi-2' && $info->status == 'selesai'))
                                        <td class='text-center'>
                                            <span>
                                                <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="statusDokumen_{{$numDokumen}}">
                                                    @if ($dokumen->status === 'sesuai')
                                                        <option value="sesuai" selected>Sesuai</option>
                                                        <option value="revisi">Tidak Sesuai - Revisi</option>
                                                    @elseif ($dokumen->status === 'revisi')
                                                        <option value="sesuai">Sesuai</option>
                                                        <option value="revisi" selected>Tidak Sesuai - Revisi</option>
                                                    @else
                                                        <option value="" selected disabled>Pilih Opsi</option>
                                                        <option value="sesuai">Sesuai</option>
                                                        <option value="revisi">Tidak Sesuai - Revisi</option>
                                                    @endif
                                                </select>

                                                </span>
                                        </td>
                                            {{-- <td>
                                            @if ($dokumen->keterangan == null)
                                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="2" name="keteranganDokumen_{{$numDokumen}}"></textarea>
                                            @else
                                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="2" name="keteranganDokumen_{{$numDokumen}}">{{$dokumen->keterangan}}</textarea>
                                            @endif
                                            </td> --}}
                                    @endif
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
                      $iteration = 1;
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
                              <th class="th-sm">No</th>
                              <th class="th-lg">Nama Lengkap</th>
                              <th class="th-lg">Pangkat/Golongan</th>
                              <th class="th-lg">Sebagai</th>
                          </tr>
                          </thead>
                          @if ($pegawais->isNotEmpty())
                              @foreach ($pegawais as $pegawai)
                                @if ($pegawai->fasilitas_id == $perangkat->id)
                                    <input id="" type="hidden" value="{{ $perangkat->id }}" name="idPegawai_{{$numpegawai}}">
                                    <tr>
                                        <td class='text-center' >{{$iteration}}</td>
                                        <td>{{$pegawai->nama_lengkap}}</td>
                                        <td  class='text-center'>{{$pegawai->pangkat}} | {{$pegawai->golongan}}</td>
                                        <td class='text-center'>{{$pegawai->sebagai}}</td>
                                    </tr>
                                    @php
                                        $iteration++;
                                        $numpegawai++;
                                    @endphp
                                @endif
                              @endforeach
                              @endif

                              @if ($nonpegawais->isNotEmpty())
                              @foreach ($nonpegawais as $nonpegawai)
                              @if ($nonpegawai->fasilitas_id == $perangkat->id)
                                <input id="" type="hidden" value="{{ $perangkat->id }}" name="idNonPegawai_{{$numnonpegawai}}">
                                    <tr>
                                        <td class='text-center'>{{$iteration}}</td>
                                        <td>{{$nonpegawai->nama_lengkap}}</td>
                                        <td class='text-center'>{{$nonpegawai->pangkat}} | {{$nonpegawai->golongan}}</td>
                                        <td class='text-center'>{{$nonpegawai->sebagai}}</td>
                                    </tr>
                                    @php
                                        $iteration++;
                                        $numnonpegawai++;
                                    @endphp
                              @endif
                              @endforeach
                            @endif
                          </table>
                        </div>
                      </div>
                      @php
                          $iteration = 1;
                      @endphp
                      @endforeach
                      <input type="hidden" name="numPegawai" value="{{$numpegawai}}">
                      <input type="hidden" name="numNonPegawai" value="{{$numnonpegawai}}">
                  {{-- @if ($info->is_acceptKeu != 'verifikasi-1') --}}
                    {{-- @if ($operasionals->isNotEmpty()) --}}
                    {{-- <div class="col-md-12 mb-3">
                      <div class="table-responsive">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="fw-bold">Informasi Mobilitas</h5>
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
                                  $subTotalMobilitas = 0;
                              @endphp
                              @foreach ($operasionals as $operasional)
                                <tr>
                                    <td class='text-center'>{{$loop->iteration}} <input type="hidden" name="idOperasional_{{$numoperasional}}" value="{{$operasional->id}}"> </td>
                                    <td>Mobil {{$operasional->nama}}</td>
                                    <td>{{$operasional->jumlah_frekuensi}}</td>
                                    <td>{{$operasional->satuan}}-{{$operasional->detail_satuan}}</td>
                                    @if (($info->is_acceptKeu == 'verifikasi-2' && $info->status == 'selesai'))
                                        @if ($keuanganoperasionals->isNotEmpty())
                                            @foreach ($keuanganoperasionals as $keuanganoperasional)
                                                @if ($keuanganoperasional->operasional == $operasional->id)
                                                <td>
                                                    <input type="hidden" class="form-control num1 prevent-submit" name="akun_{{$numoperasional}}" value="{{$keuanganoperasional->akun_x_rkakl}}">
                                                    <input
                                                        id="input_nominal_mobilitas_{{$numoperasional}}"
                                                        type="number"
                                                        class="form-control input-nominal-mobilitas prevent-submit"
                                                        min="0"
                                                        step="0.1"
                                                        name="nominal_{{$numoperasional}}"
                                                        value="{{$keuanganoperasional->harga}}">
                                                </td>

                                                {{-- PPH21 --}}
                                                {{-- <td>
                                                    <div class="input-group mb-3">
                                                    <input id="input_pajak_mobilitas_{{$numoperasional}}"
                                                        type="number"
                                                        class="form-control input-pajak-mobilitas prevent-submit"
                                                        step="0.1"
                                                        min="0"
                                                        name="pajak_mobilitas_{{$numoperasional}}"
                                                        value="{{ $keuanganoperasional->persen_pajak ?? 0 }}">
                                                    <span class="input-group-text" id="basic-addon2">%</span>
                                                    </div>
                                                </td> --}}

                                                {{-- TOTAL MOBILITAS --}}
                                                {{-- <td style="min-width: 200px">
                                                    <input id="input_total_mobilitas_{{$numoperasional}}"
                                                        type="number"
                                                        class="form-control input-total-mobilitas prevent-submit"
                                                        min="0"
                                                        name="total_mobilitas_{{$numoperasional}}"
                                                        value="{{$keuanganoperasional->jumlah_harga}}"
                                                        readonly>
                                                </td> --}}
                                                {{-- @endif
                                            @endforeach
                                        @else --}}
                                            {{-- <td>
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
                                                <option value="{{$operasional->status}}">{{ ucwords($operasional->status) }}</option>
                                                <option value="sesuai">Sesuai</option>
                                                <option value="tidak sesuai">Tidak Sesuai</option>
                                            @endif
                                            </select>
                                        </td> --}}
                                    {{-- @else
                                        @if ($keuanganoperasionals->isNotEmpty())
                                            @foreach ($keuanganoperasionals as $keuanganoperasional)
                                                @if ($keuanganoperasional->operasional == $operasional->id)
                                                <td>
                                                    <input type="hidden" class="form-control num1 prevent-submit" name="akun_{{$numoperasional}}" value="{{$keuanganoperasional->akun_x_rkakl}}">
                                                    <input type="number" class="form-control num1 prevent-submit" name="nominal_{{$numoperasional}}" value="{{$keuanganoperasional->harga}}" readonly>
                                                </td>
                                                <td>
                                                    <div class="input-group mb-3">
                                                    <input type="number" class="form-control num2 prevent-submit" name="pajak_{{$numoperasional}}" value="{{$keuanganoperasional->persen_pajak}}" readonly>
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
                                                <input type="number" class="form-control num1 prevent-submit" name="nominal_{{$numoperasional}}" readonly>
                                            </td>
                                            <td>
                                                <div class="input-group mb-3">
                                                <input type="number" class="form-control num2 prevent-submit" name="pajak_{{$numoperasional}}" readonly>
                                                <span class="input-group-text" id="basic-addon2">%</span>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" class="result form-control" name="total_{{$numoperasional}}" readonly>
                                            </td>
                                            @endif
                                            <td>
                                                <select disabled class="form-select" aria-label=".form-select-sm example" name="kesesuaian_{{$numoperasional}}">
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
                                        @endif --}}
                                        {{-- </tr> --}}
                                {{-- @php
                                    $subTotalMobilitas += $keuanganoperasional->jumlah_harga;
                                    $numoperasional++;
                                @endphp
                              @endforeach
                              <input type="hidden" name="numOperasional" value="{{$numoperasional}}">
                            </tbody>
                            <tfoot>
                              <tr>
                                <td colspan="6" class="fw-bold text-end">Sub Total</td>
                                <td><input type="number" class="subTotalMobilitasOnly form-control" value="{{$subTotalMobilitas}}" readonly></td>
                              </tr>
                            </tfoot>
                        </table>
                      </div>
                    </div>
                    @endif
                  @endif --}}

                    @if ($info->is_acceptKeu == 'verifikasi-2' && $info->status == 'selesai')
                        <div class="col-md-12 mb-3">
                            <div class="table-responsive">
                            <div class="d-flex justify-content-between">
                                <div>
                                <h5 class="fw-bold">Informasi Fasilitas Tambahan<br>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                +Tambah
                                        </button>
                                        <ul class="dropdown-menu">
                                          <li><a class="dropdown-item text-primary fw-bold" href="#" data-bs-toggle="modal" data-bs-target="#tambah_fasilitas_pelaksana">+ Fasilitas untuk Pelaksana</a></li>
                                          <li><a class="dropdown-item text-success fw-bold" href="#" data-bs-toggle="modal" data-bs-target="#tambah_fasilitas_tambahan">+ Fasilitas Tambahan Lainnya</a></li>
                                        </ul>
                                    </div>

                                </div>
                            </div>

                                    <table id="calculationTable2" name="Fasilitas Tambahan" class="table table-bordered calculationTable" style="width: 100%">
                                        <thead>
                                        <tr class="text-center small">
                                            <th>No</th>
                                            <th>Nama Fasilitas</th>
                                            <th>Jumlah</th>
                                            <th>Detail</th>
                                            <th>Tipe Pendanaan</th>
                                            <th>Pelaksana</th>
                                            <th>Keterangan</th>
                                            <th>Harga Satuan</th>
                                            <th>Nominal Kebutuhan</th>
                                            <th>Pajak</th>
                                            <th>Nominal Pajak</th>
                                            <th>Total Pembayaran Bersih</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                        </thead>
                                        @if ($kebutuhans->isNotEmpty())
                                        <tbody>
                                        @php
                                        $numkebutuhan = 0;
                                        @endphp
                                        @foreach ($kebutuhans as $kebutuhan)
                                        <tr>
                                            <td class='text-center' style="min-width: 50px">{{$loop->iteration}} <input type="hidden" name="idKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->idKebutuhan}}"></td>
                                            <td style="min-width: 150px">{{$kebutuhan->nama}}</td>
                                            <input id="jumlah_frekuensi_{{$numkebutuhan}}"
                                                type="hidden"
                                                value="{{$kebutuhan->jumlah_frekuensi}}">

                                            <td class='text-center' style="min-width: 50px">{{ number_format($kebutuhan->jumlah_frekuensi, 0, ',', '.') }}</td>
                                            <td class='text-center' style="min-width: 100px">{{$kebutuhan->satuan}}</td>
                                            <td class='text-center' style="min-width: 100px">{{$kebutuhan->tipe_pendanaan}}</td>
                                            <td class='text-center' style="">{{$kebutuhan->pelaksana}}</td>
                                            <td class='text-center' style="min-width: 100px">{{$kebutuhan->ket}}</td>

                                            @php
                                            $satuanKebutuhan = $kebutuhan->harga / $kebutuhan->jumlah_frekuensi;
                                            @endphp
                                            <td style="min-width: 200px">
                                            <input type="number" id="satuanKebutuhan_{{$numkebutuhan}}" class="satuan-kebutuhan form-control " min="0" name="satuanKebutuhan_{{$numkebutuhan}}" value="{{$satuanKebutuhan}}" >
                                            </td>
                                            <td style="min-width: 200px">
                                            <input readonly type="number" id="nominalKebutuhan_{{$numkebutuhan}}" class="nominal-kebutuhan form-control " min="0" name="nominalKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->harga}}" >
                                            </td>
                                            <td style="min-width: 100px">
                                            <div class="input-group mb-3">
                                                <input id="input_pajak_fasilitas_{{$numkebutuhan}}"
                                                    type="number"
                                                    class="form-control input-pajak-fasilitas pajak-fasilitas prevent-submit"
                                                    min="0"
                                                    name="pajak_fasilitas_{{$numkebutuhan}}"
                                                    value="{{$kebutuhan->persen_pajak}}"

                                                >
                                                <span class="input-group-text" id="basic-addon2">%</span>
                                            </div>
                                        </td>
                                            <td style="min-width: 200px">
                                            <input type="number" class="form-control " min="0" name="nominalPajakKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->nilai_pajak}}" readonly>
                                            </td>
                                            <td style="min-width: 200px">
                                            <input type="number" class=" result prevent-submit form-control" min="0" name="totalKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->jumlah_harga}}" readonly>
                                            </td>
                                            <td style="min-width: 200px">
                                                <select class="form-select" aria-label=".form-select-sm example" name="statusKebutuhan_{{$numkebutuhan}}">
                                                    @if ($kebutuhan->status === 'Sesuai')
                                                        <option value="Sesuai" selected>Sesuai</option>
                                                        <option value="Revisi">Tidak Sesuai - Revisi</option>
                                                    @elseif ($kebutuhan->status === 'revisi')
                                                        <option value="Sesuai">Sesuai</option>
                                                        <option value="Revisi" selected>Tidak Sesuai - Revisi</option>
                                                    @else
                                                        <option value="" selected disabled>Pilih Opsi</option>
                                                        <option value="Sesuai">Sesuai</option>
                                                        <option value="Revisi">Tidak Sesuai - Revisi</option>
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <input type="hidden" value="{{ $info->id }}" name="kegiatanId">
                                                <!-- Tombol untuk membuka modal -->
                                                <button type="button" class="btn btn-danger btn-sm text-white"
                                                    data-kebutuhan="{{ $kebutuhan->idKebutuhan }}"
                                                    data-bs-toggle="modal" data-bs-target="#modal_hapus">
                                                    <i class="fa fa-trash"></i>
                                                </button>

                                            </td>
                                        </tr>
                                        @php
                                        $numkebutuhan++;
                                        @endphp

                                        @endforeach
                                        <input type="hidden" name="numKebutuhan" value="{{$numkebutuhan}}">
                                        </tbody>
                                        @endif
                                        <tfoot>
                                        <tr>
                                            <td colspan="11" class="fw-bold text-end">Sub Total</td>
                                            <td><input type="number" class="totalKebutuhan total form-control" readonly></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                    @else
                    <div class="col-md-12 mb-3">
                        <div class="table-responsive">
                        <div class="d-flex justify-content-between">
                            <div>
                            <h5 class="fw-bold">Informasi Fasilitas Tambahan<br>

                            </div>
                        </div>

                                <table id="calculationTable2" name="Fasilitas Tambahan" class="table table-bordered calculationTable" style="width: 100%">
                                    <thead>
                                    <tr class="text-center small">
                                        <th>No</th>
                                        <th>Nama Fasilitas</th>
                                        <th>Jumlah</th>
                                        <th>Detail</th>
                                        <th>Tipe Pendanaan</th>
                                        <th>Keterangan</th>
                                        <th>Harga Satuan</th>
                                        <th>Nominal Kebutuhan</th>
                                        <th>Pajak</th>
                                        <th>Nominal Pajak</th>
                                        <th>Total Pembayaran Bersih</th>
                                        @if ($info->is_acceptKeu == 'verifikasi-2' && $info->status == 'selesai')
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    @php
                                    $subtotalKebutuhan = 0;
                                    $numkebutuhan = 0;
                                    @endphp
                                    @if ($kebutuhans->isNotEmpty())
                                    <tbody>
                                    @foreach ($kebutuhans as $kebutuhan)
                                    <tr>
                                        <td class='text-center' style="min-width: 50px">{{$loop->iteration}} <input type="hidden" name="idKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->idKebutuhan}}"></td>
                                        <td style="min-width: 150px">{{$kebutuhan->nama}}</td>
                                        <input id="jumlah_frekuensi_{{$numkebutuhan}}"
                                            type="hidden"
                                            value="{{$kebutuhan->jumlah_frekuensi}}">

                                        <td class='text-center' style="min-width: 50px">{{ number_format($kebutuhan->jumlah_frekuensi, 0, ',', '.') }}</td>
                                        <td class='text-center' style="min-width: 100px">{{$kebutuhan->satuan}}</td>
                                        <td class='text-center' style="min-width: 100px">{{$kebutuhan->tipe_pendanaan}}</td>
                                        <td class='text-center' style="min-width: 100px">{{$kebutuhan->ket}}</td>

                                        @php
                                        $satuanKebutuhan = $kebutuhan->harga / $kebutuhan->jumlah_frekuensi;
                                        @endphp
                                        <td style="min-width: 200px">
                                        <input readonly type="number" id="satuanKebutuhan_{{$numkebutuhan}}" class="satuan-kebutuhan form-control " min="0" name="satuanKebutuhan_{{$numkebutuhan}}" value="{{$satuanKebutuhan}}" >
                                        </td>
                                        <td style="min-width: 200px">
                                        <input readonly type="number" id="nominalKebutuhan_{{$numkebutuhan}}" class="nominal-kebutuhan form-control " min="0" name="nominalKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->harga}}" >
                                        </td>
                                        <td style="min-width: 100px">
                                        <div class="input-group mb-3">
                                            <input readonly id="input_pajak_fasilitas_{{$numkebutuhan}}"
                                                type="number"
                                                class="form-control input-pajak-fasilitas pajak-fasilitas prevent-submit"
                                                min="0"
                                                name="pajak_fasilitas_{{$numkebutuhan}}"
                                                value="{{$kebutuhan->persen_pajak}}"

                                            >
                                            <span class="input-group-text" id="basic-addon2">%</span>
                                        </div>
                                    </td>
                                        <td style="min-width: 200px">
                                        <input readonly type="number" class="form-control " min="0" name="nominalPajakKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->nilai_pajak}}" readonly>
                                        </td>
                                        <td style="min-width: 200px">
                                        <input readonly type="number" class=" result prevent-submit form-control" min="0" name="totalKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->jumlah_harga}}" readonly>
                                        </td>
                                        <td style="min-width: 200px">
                                            <select disabled class="form-select" aria-label=".form-select-sm example" name="statusKebutuhan_{{$numkebutuhan}}">
                                                @if ($kebutuhan->status === 'Sesuai')
                                                    <option value="Sesuai" selected>Sesuai</option>
                                                    <option value="Revisi">Tidak Sesuai - Revisi</option>
                                                @elseif ($kebutuhan->status === 'revisi')
                                                    <option value="Sesuai">Sesuai</option>
                                                    <option value="Revisi" selected>Tidak Sesuai - Revisi</option>
                                                @else
                                                    <option value="{{$kebutuhan->status}}" selected>{{$kebutuhan->status}}</option>
                                                    <option value="Sesuai">Sesuai</option>
                                                    <option value="Revisi">Tidak Sesuai - Revisi</option>
                                                @endif
                                            </select>
                                        </td>
                                        <td>
                                            <input type="hidden" value="{{ $info->id }}" name="kegiatanId">
                                            <!-- Tombol untuk membuka modal -->
                                            <button disabled type="button" class="btn btn-danger btn-sm text-white"
                                                data-kebutuhan="{{ $kebutuhan->idKebutuhan }}"
                                                data-bs-toggle="modal" data-bs-target="#modal_hapus">
                                                <i class="fa fa-trash"></i>
                                            </button>

                                        </td>
                                    </tr>
                                    @php
                                        $subtotalKebutuhan += $kebutuhan->jumlah_harga;
                                        $numkebutuhan++;
                                    @endphp

                                    @endforeach
                                    <input type="hidden" name="numKebutuhan" value="{{$numkebutuhan}}">
                                    </tbody>
                                    @endif
                                    <tfoot>
                                    <tr>
                                        <td colspan="10" class="fw-bold text-end">Sub Total</td>
                                        <td><input type="number" class="totalKebutuhan form-control"  value="{{ $subtotalKebutuhan }}" readonly></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @endif


                  <div class="col-md-12 mb-3">
                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                    @if ($info->is_acceptKeu == 'verifikasi-2')
                        <a href="{{url('/kegiatan-keuangan/' . 'verifikasi-2')}}" class="btn btn-dark">Kembali</a>
                        @if($info->status == 'selesai')
                            <button class="btn btn-info text-white" type="button" data-bs-toggle="modal" data-bs-target="#revisi_user_modal">Revisi</button>
                            <!-- Tombol untuk membuka modal -->
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#alasanPenolakanModal">
                                Tolak Kegiatan
                            </button>
                            <button class="btn btn-success" type="submit" name="action" value="verifikasi-2">Verifikasi dan Kirim Ke-Bendahara</button>
                        @endif
                    @elseif ($info->is_acceptKeu == 'revisi-2')
                        <a href="{{url('/kegiatan-keuangan/' . 'revisi-2')}}" class="btn btn-dark">Kembali</a>
                    @elseif ($info->is_acceptKeu == 'ditolak')
                        <a href="{{url('/kegiatan-keuangan/' . 'ditolak')}}" class="btn btn-dark">Kembali</a>
                    @elseif ($info->is_acceptKeu == 'selesai')
                        <a href="{{url('/kegiatan-keuangan/' . 'selesai')}}" class="btn btn-dark">Kembali</a>
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

  <!-- MODAL TOLAK -->
  <div class="modal fade" id="alasanPenolakanModal" tabindex="-1" aria-labelledby="tolak_mobilitasLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="revisi_user">Tolak Kegiatan {{$info->id}}</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{url('/cu_kegiatan_keuangan')}}" method="post">
            @csrf
            <input type="hidden" name="idKegiatan" value="{{$info->id}}">
            <!-- Hidden Input untuk Data Tambahan -->
          <input type="hidden" id="dataTambahan" name="data_tambahan" value="">
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="uraian" class="form-label">Masukkan Alasan Penolakan<span class="text-secondary small"></span><span class="text-danger">*</span></label>
                <textarea id="tolak" name="alasan_penolakan" class="form-control" placeholder="Alasan Penolakan" required=""></textarea>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary text-white" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger" name="action" value="tolak" id="tolak_button">Kirim</button>
        </div>
        </form>
      </div>
    </div>
  </div>
  </div>

    <!-- Modal untuk konfirmasi hapus -->
    <div class="modal fade" id="modal_hapus" tabindex="-1" aria-labelledby="modal_hapusLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_hapusLabel">Konfirmasi Penghapusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus fasilitas ini?</p>
                </div>
                <div class="modal-footer">
                    <!-- Form untuk hapus fasilitas -->
                    <form id="hapusFasilitasForm" action="{{ url('/h_fasilitas_kegiatan_admin') }}" method="post">
                        @method('delete')
                        @csrf
                        <input type="hidden" id="kegiatanId" name="kegiatanId" value="{{ $info->id }}">
                        <input type="hidden" id="kebutuhanId" name="kebutuhanId"> <!-- input hidden untuk kebutuhanId -->
                        <button type="submit" class="btn btn-danger">Hapus</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

     {{-- Modal Tambah Fasilitas Pelaksana --}}
<div class="modal fade" id="tambah_fasilitas_pelaksana" tabindex="-1" aria-labelledby="tambah_fasilitasLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="tambah_fasilitasLabel">Tambah Fasilitas</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{url('/c_fasilitasdetail_keuangan')}}" method="post">
            @csrf
            <input id="" type="hidden" value="{{ $info->id }}" name="data_perjadinkegiatans">
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="peserta" class="form-label">Nama Pelaksana</label>
                <select class="form-select mb-2" aria-label="Default select example" name="perangkat_acara">
                    <option value="" disabled selected>Pilih Pelaksana</option>
                    @php
                        $uniqueNames = [];
                    @endphp
                    @foreach($pegawais as $pesertaPegawai)
                        @if(!in_array($pesertaPegawai->nama_lengkap, $uniqueNames))
                            <option value="{{$pesertaPegawai->idPerangkatAcara}}">{{$pesertaPegawai->nama_lengkap}}</option>
                            @php
                                $uniqueNames[] = $pesertaPegawai->nama_lengkap;
                            @endphp
                        @endif
                    @endforeach
                    @foreach($nonpegawais as $nonPegawai)
                        @if(!in_array($nonPegawai->nama_lengkap, $uniqueNames))
                            <option value="{{$nonPegawai->idPerangkatAcara}}">{{$nonPegawai->nama_lengkap}}</option>
                            @php
                                $uniqueNames[] = $nonPegawai->nama_lengkap;
                            @endphp
                        @endif
                    @endforeach
                </select>
                <label for="uraian" class="form-label">Nama Fasilitas <span class="text-secondary small"></span><span class="text-danger">*</span></label>
                <select class="form-select" id="uraian_pelaksana" name="uraian" required>
                  <option value="" disabled selected>Pilih Jenis Fasilitas</option>
                  <!-- <option value="Akomodasi Hotel">Akomodasi Hotel</option>
                  <option value="BBM">BBM</option>
                  <option value="Tiket Kereta">Tiket Kereta</option>
                  <option value="Tiket Pesawat">Tiket Pesawat</option>
                  <option value="Tiket Travel">Tiket Travel</option>
                  <option value="Transportasi Online">Transportasi Online</option>
                  <option value="Tol">Tol</option> -->
                  @foreach ($ref_fasilitas_pelaksana as $ref_fasilitass)
                          <option value="{{$ref_fasilitass->nama_fasilitas}}">{{$ref_fasilitass->nama_fasilitas}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div id="conditional_fields_pelaksana">
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


  {{-- Modal Tambah Fasilitas Lainnya --}}
  <div class="modal fade" id="tambah_fasilitas_tambahan" tabindex="-1" aria-labelledby="tambah_fasilitasLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="tambah_fasilitasLabel">Tambah Fasilitas Lainnya</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

<form action="{{ url('/c_fasilitasdetail_keuangan') }}" method="post">
    @csrf
    <input type="hidden" value="{{ $info->id }}" name="data_perjadinkegiatans">
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="uraian" class="form-label">Nama Fasilitas <span class="text-secondary small"></span><span class="text-danger">*</span></label>
                <select class="form-select" id="uraian" name="uraian" required>
                  <option value="" disabled selected>Pilih Jenis Fasilitas</option>
                  <!-- <option value="Konsumsi">Konsumsi</option>
                  <option value="Akomodasi Hotel">Akomodasi Hotel</option>
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
          </form>
        </div>
      </div>
    </div>
  </div>



  <!-- Modal Revisi USER -->
<div class="modal fade" id="revisi_user_modal" tabindex="-1" aria-labelledby="tolak_mobilitasLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="revisi_user">Revisi Kegiatan {{$info->id}}</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{url('/cu_kegiatan_keuangan')}}" method="post">
            @csrf
            <input type="hidden" name="statusKegiatan" value="{{$info->is_acceptKeu}}">
            <input type="hidden" name="idKegiatan" value="{{$info->id}}">
            <!-- Hidden Input untuk Data Tambahan -->
          <input type="hidden" id="dataTambahan" name="data_tambahan" value="">
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="uraian" class="form-label">Masukkan Alasan Revisi<span class="text-secondary small"></span><span class="text-danger">*</span></label>
                <textarea id="tolak" name="alasan_user" class="form-control" placeholder="Alasan Revisi" required=""></textarea>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" name="action" value="revisi" id="revisi_user_button">Simpan</button>
        </div>
        </form>
      </div>
    </div>
  </div>
  </div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<!-- Skrip JavaScript -->
<script>
    $(document).ready(function () {
        // Event listener untuk membuka modal
        $('#modal_hapus').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Tombol yang mengaktifkan modal
            var kebutuhanId = button.data('kebutuhan'); // Mengambil data-kebutuhan dari tombol

            // Debugging dengan alert untuk melihat apakah data berhasil diteruskan
            // alert('Kebutuhan ID: ' + kebutuhanId); // Cek apakah data muncul

            // Debugging dengan console log
            console.log('Kebutuhan ID:', kebutuhanId);

            // Pastikan jika kebutuhanId ada sebelum melanjutkan
            if (kebutuhanId) {
                var modal = $(this);
                modal.find('#kebutuhanId').val(kebutuhanId);

                // Mengupdate action URL form dengan kebutuhanId
                var form = modal.find('#hapusFasilitasForm');
                form.attr('action', '/h_fasilitas_kegiatan_admin/' + kebutuhanId + '/keuangan');
            } else {
                console.error('Kebutuhan ID tidak ditemukan pada tombol.');
            }
        });
    });
</script>


<script>
    $(document).ready(function () {
        // Menghitung nilai nominalKebutuhan, nominalPajakKebutuhan, dan totalKebutuhan saat input satuanKebutuhan berubah
        $('.satuan-kebutuhan').on('input', function () {
            const numkebutuhan = $(this).attr('id').split('_')[1]; // Mengambil nomor kebutuhan dari ID

            // Mengambil nilai dari input satuan kebutuhan dan jumlah frekuensi
            const satuanKebutuhan = parseFloat($('#satuanKebutuhan_' + numkebutuhan).val()) || 0;
            const jumlahFrekuensi = parseFloat($('#jumlah_frekuensi_' + numkebutuhan).val()) || 0;

            // Menghitung nominal kebutuhan
            const nominalKebutuhan = satuanKebutuhan * jumlahFrekuensi;
            $('#nominalKebutuhan_' + numkebutuhan).val(nominalKebutuhan.toFixed(0));

            // Mengambil nilai pajak fasilitas
            const pajakFasilitas = parseFloat($('#input_pajak_fasilitas_' + numkebutuhan).val()) || 0;

            // Menghitung nominal pajak kebutuhan
            const nominalPajakKebutuhan = (nominalKebutuhan * pajakFasilitas) / 100;
            $('input[name="nominalPajakKebutuhan_' + numkebutuhan + '"]').val(nominalPajakKebutuhan.toFixed(0));

            // Menghitung total kebutuhan
            const totalKebutuhan = nominalKebutuhan - nominalPajakKebutuhan;
            $('input[name="totalKebutuhan_' + numkebutuhan + '"]').val(totalKebutuhan.toFixed(0));

             // Update subtotal
             updateSubtotal();
        });

        // Menghitung nilai nominalPajakKebutuhan dan totalKebutuhan saat input pajak fasilitas berubah
        $('.input-pajak-fasilitas').on('input', function () {
            const numkebutuhan = $(this).attr('id').split('_')[3]; // Mengambil nomor kebutuhan dari ID

            // Mengambil nilai dari input nominal kebutuhan
            const nominalKebutuhan = parseFloat($('#nominalKebutuhan_' + numkebutuhan).val()) || 0;

            // Mengambil nilai pajak fasilitas
            const pajakFasilitas = parseFloat($(this).val()) || 0;

            // Menghitung nominal pajak kebutuhan
            const nominalPajakKebutuhan = (nominalKebutuhan * pajakFasilitas) / 100;
            $('input[name="nominalPajakKebutuhan_' + numkebutuhan + '"]').val(nominalPajakKebutuhan.toFixed(0));

            // Menghitung total kebutuhan
            const totalKebutuhan = nominalKebutuhan - nominalPajakKebutuhan;
            $('input[name="totalKebutuhan_' + numkebutuhan + '"]').val(totalKebutuhan.toFixed(0));

             // Update subtotal
             updateSubtotal();
        });

        // Fungsi untuk menghitung subtotal
        function updateSubtotal() {
            let subtotal = 0;

            // Loop melalui semua input dengan nama yang sesuai pola
            $('input[name^="totalKebutuhan_"]').each(function () {
                const value = parseFloat($(this).val()) || 0;
                subtotal += value;
            });

            // Set nilai subtotal ke elemen input di tfoot
            $('.totalKebutuhan').val(subtotal.toFixed(0));
        }

        // Panggil updateSubtotal saat halaman pertama kali dimuat
         // Memastikan subtotal diperbarui saat input kehilangan fokus
         $('input').on('blur', function () {
            updateSubtotal();
        });
    });
</script>

<script>
    $(document).ready(function() {
        function updateTotalOperasional() {
            // Hitung subtotal dari semua total mobilitas
            let subtotal = 0;
            $('.input-total-mobilitas').each(function() {
                const value = parseFloat($(this).val()) || 0;
                subtotal += value;
            });

            // Set subtotal ke input subtotal mobilitas
            $('.subTotalMobilitas').val(subtotal.toFixed(0));
        }

        function calculateAndUpdate(numoperasional) {
            // Pastikan elemen pajak ada
            const pajakMobilitasElement = $('#input_pajak_mobilitas_' + numoperasional);
            if (pajakMobilitasElement.length === 0) {
                console.error('Elemen #input_pajak_mobilitas_' + numoperasional + ' tidak ditemukan.');
                return;
            }

            // Ambil nilai nominal dan pajak
            const nominalMobilitas = parseFloat($('#input_nominal_mobilitas_' + numoperasional).val()) || 0;
            const pajakMobilitas = parseFloat(pajakMobilitasElement.val()) || 0;

            // Ambil elemen total mobilitas
            const totalMobilitasElement = $('#input_total_mobilitas_' + numoperasional);

            // Hitung nilai pajak dan total mobilitas
            const nilaiPajak = (nominalMobilitas * pajakMobilitas) / 100;
            const totalMobilitasValue = nominalMobilitas - nilaiPajak;

            // Update nilai ke input total mobilitas
            totalMobilitasElement.val(totalMobilitasValue.toFixed(0))
        }

        // Jalankan perhitungan saat input nominal atau pajak berubah
        $('.input-nominal-mobilitas, .input-pajak-mobilitas').on('input', function() {
            const numoperasional = $(this).attr('id').split('_')[3]; // Mengambil nomor operasional
            calculateAndUpdate(numoperasional);
            updateTotalOperasional();
        });

        // Jalankan logika saat pertama kali halaman dimuat
        $('.input-nominal-mobilitas').each(function() {
            const numoperasional = $(this).attr('id').split('_')[3]; // Mengambil nomor operasional
            calculateAndUpdate(numoperasional);
        });

        // Update total operasional setelah semua perhitungan selesai
        updateTotalOperasional();
    });

    $(document).ready(function() {
        // Event listener for the first dropdown
        $('select[name="statusDokumen_0"]').on('change', function() {
            // Get the selected value
            var selectedValue = $(this).val();
            // Get the selected text
            var selectedText = $(this).find("option:selected").text();

            // Iterate over all other dropdowns and set the selected value
            $('select[name^="statusDokumen_"]').not(this).each(function() {
                $(this).val(selectedValue).trigger('change');
                $(this).find("option:selected").text(selectedText);
            });
        });
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
        $('select[name="statusKebutuhan_0"]').on('change', function() {
            // Get the selected value
            var selectedValue = $(this).val();
            // Get the selected text
            var selectedText = $(this).find("option:selected").text();

            // Iterate over all other dropdowns and set the selected value
            $('select[name^="statusKebutuhan_"]').not(this).each(function() {
                $(this).val(selectedValue).trigger('change');
                $(this).find("option:selected").text(selectedText);
            });
        });
    });
</script>




  <script>
    document.getElementById('revisi_user_button').addEventListener('click', function() {
      // Ambil alasan revisi yang dimasukkan user
      var alasanRevisi = document.getElementById('tolak').value;

      // Mengambil semua elemen form yang memiliki atribut name
      var formData = {alasanRevisi: alasanRevisi,};
      var formElements = document.querySelectorAll('[name]');

      formElements.forEach(function(element) {
        var name = element.name;
        var value = element.value;

        // Masukkan ke dalam object formData
        formData[name] = value;
      });

      // Tambahkan data alasanRevisi dan data tambahan lainnya ke dalam formData
      var dataTambahan = {
        alasanRevisi: alasanRevisi,
        additionalData: "Informasi tambahan lainnya"
      };

      // Gabungkan formData dan dataTambahan
      var finalData = {
        formData: formData,
        dataTambahan: dataTambahan
      };

      // Mengubah finalData menjadi format JSON untuk disimpan di hidden input
      document.getElementById('dataTambahan').value = JSON.stringify(formData);

    });
  </script>

<script>
    $(document).ready(function() {
        let refFasilitasData = [];

        // Ambil data dari Laravel backend
        $.ajax({
            url: '/get-data-fasilitas',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                refFasilitasData = data; // Simpan data ke variabel global
            },
            error: function(error) {
                console.error('Error fetching data:', error);
            }
        });

        // Event listener untuk perubahan nilai select
        $('#uraian').change(function() {
            const selectedValue = $(this).val();

            // Cari data yang sesuai dengan selectedValue
            const selectedFasilitas = refFasilitasData.find(fasilitas => fasilitas.nama_fasilitas === selectedValue);

            // Jika ditemukan, gunakan satuan dari data tersebut
            const satuanValue = selectedFasilitas ? selectedFasilitas.satuan : 'Kali';

            // Bersihkan konten sebelumnya
            $('#conditional_fields').empty();

            // Tambahkan HTML ke #conditional_fields
            $('#conditional_fields').html(`
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="jumlah_tiket" class="detail-fields">Jumlah <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" min="0" aria-label="First name" class="form-control" name="jumlah_frekuensi">
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="satuan" name="satuan" value="${satuanValue}" readonly>
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
                        <label for="" class="form-label">Keterangan Fasilitas <span class="text-danger">*</span><span class="text-secondary small">(Contoh : Perkiraan Harga)</span></label>
                        <input type="text" name="keterangan" id="" class="form-control" required>
                    </div>
                </div>
            `);
        });
    });
</script>
@endsection
