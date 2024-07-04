@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>BMN / Approval / <span class="fw-bold">{{$penyedia->nama_CV}}</span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body content">
                
                    <!-- Perbaikan Asset - Pembayaran - Details -->
                <div class="row page_content page_9">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="row">
                              <h5 class="fw-bold">Informasi Penyedia</h5>
                            </div>
                            <div class="row small mb-2">
                                <div class="col-3">Penanggung Jawab</div>
                                <div class="col-9">: {{$penyedia->penanggung_jawab}}</div>
                            </div>
                            <div class="row small mb-2">
                                <div class="col-3">Nama CV</div>
                                <div class="col-9">: {{$penyedia->nama_CV}}</div>
                            </div>
                            <div class="row small mb-2">
                                <div class="col-3">Nomor Telepon</div>
                                <div class="col-9">: {{$penyedia->no_telp}}</div>
                            </div>
                            <div class="row small mb-2">
                                <div class="col-3">Kategori</div>
                                <div class="col-9">: {{$penyedia->kategori}}</div>
                            </div>
                            <div class="row small mb-2">
                                <div class="col-3">Alamat</div>
                                <div class="col-9">: {{$penyedia->alamat}}</div>
                            </div>
                            <br>
                        </div>
                    </div>

                    <form action="{{url('/c_bendahara_service')}}" method="post">
                        @csrf
                        <input type="hidden" name="idPenyedia" value="{{$penyedia->id}}">
                    @php
                        $numpermohonanAdmin = 0;
                        $numpermohonanPegawai = 0;
                        $numpermohonanKendaraan = 0;
                        $numpermohonanRuangan = 0;
                        $numdokumen = 0;
                    @endphp
                    <div class="col-md-12 mb-3">
                        <h5 class="fw-bold">Informasi Dokumen</h5>
                        <div class="table-responsive">                        
                          <table id="example" class="table table-bordered" style="width: 100%">
                            <thead>
                              <tr class="text-center small">
                                <th class="th-sm">No</th>
                                <th class="th-md">Nama Dokumen</th>
                                <th class="th-md">Lampiran</th>
                              </tr>
                            </thead>
                            @foreach ($dokumens as $dokumen)
                                <tr>
                                    <td class='text-center'>{{$loop->iteration}}</td>
                                    <td>{{$dokumen->nama_dokumen}}</td>
                                    <td class='text-center'>
                                        <a href="{{asset('public/storage/' . $dokumen->file)}}" target="_blank" class="btn btn-secondary btn-sm"><i class="fa-solid fa-eye"></i> Lihat</a>
                                    </td>
                                </tr>
                                @php
                                    $numdokumen++;
                                @endphp
                            @endforeach
                        </table>
                        </div>
                      </div>
                  
                  <div class="col-md-12 mb-3">
                    <h5 class="fw-bold">Informasi Perbaikan</h5>
                    <div class="table-responsive">                        
                      <table id="calculationTable1" class="table table-bordered calculationTable" name="{{$penyedia->nama_CV}}" style="width: 100%">
                        <thead>
                          <tr class="text-center small">
                            <th class="th-sm">No</th>
                            <th class="th-md">Nama Pengaju</th>
                            <th class="th-md">Nama Barang</th>
                            <th class="th-md">Tanggal Selesai</th>
                            <th class="th-md">Akun</th>
                            <th class="th-md">SBM</th>
                            <th class="th-md">Nominal</th>
                            <th class="th-sm">PPh</th>
                            <th class="th-sm">PPh22</th>
                            <th class="th-sm">PPh23</th>
                            <th class="th-sm">PPn</th>
                            <th class="th-md">Total</th>
                            <th class="th-md">Tanggal Pembayaran</th>
                            <th class="th-md">Status</th>
                          </tr>
                        </thead>
                        <tbody>
                        @foreach ($permohonans as $permohonan)
                            <tr>
                                <td class='text-center'>{{$loop->iteration}} <input type="hidden" name="idPermohonan_{{$numpermohonanAdmin}}" value="{{$permohonan->idPermohonan}}"></td>
                                <td style="min-width: 150px">{{$permohonan->username}}</td>
                                <td style="min-width: 200px">
                                    {{$permohonan->nama_barang}}
                                    <ul>
                                    @foreach ($komponens as $komponen)
                                        @if ($permohonan->idPermohonan == $komponen->permohonan_id)
                                        <li>{{$komponen->nama_barang}} - {{$komponen->frekuensi}}x</li>
                                        @endif
                                    @endforeach
                                    </ul>
                                </td>
                                <td class='text-center' style="min-width: 150px">{{$permohonan->tgl_selesai}}</td>
                                <td>
                                    <select class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 320px" name="akunPermohonan_{{$numpermohonanAdmin}}">
                                      @foreach ($akuns as $akun)
                                      <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                        @if ($akun->idAkun == $permohonan->akun_x_rkakl_id)
                                        <option value="{{$akun->idAkun}}" selected>[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                        @endif
                                      @endforeach
                                    </select>
                                </td>
                                <td>
                                        <select class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="sbmPermohonan_{{$numpermohonanAdmin}}">
                                        @foreach ($sbms as $sbm)
                                        <option value="{{$sbm->id}}">[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                                            @if ($sbm->id == $permohonan->ref_sbm_id)
                                            <option value="{{$sbm->id}}" selected>[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                </td>
                                  <td style="min-width: 200px">
                                    <input type="number" class="form-control num1" name="nominalPermohonan_{{$numpermohonanAdmin}}" value="{{$permohonan->nominal}}">
                                  </td>
                                  <td style="min-width: 100px">
                                    <div class="input-group mb-3">
                                      <input type="number" class="form-control num2" name="pajakPermohonan22_{{$numpermohonanAdmin}}" value="{{$permohonan->pph22}}">
                                      <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                                  </td>
                                  <td style="min-width: 100px">
                                    <div class="input-group mb-3">
                                      <input type="number" class="form-control num3" name="pajakPermohonan23_{{$numpermohonanAdmin}}" value="{{$permohonan->pph23}}">
                                      <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                                  </td>
                                  <td style="min-width: 100px">
                                    <div class="input-group mb-3">
                                      <input type="number" class="form-control num4" name="pajakPermohonanppn_{{$numpermohonanAdmin}}" value="{{$permohonan->ppn}}">
                                      <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                                  </td>
                                  <td style="min-width: 100px">
                                    <div class="input-group mb-3">
                                      <input type="number" class="form-control num5" name="pajakPermohonan_{{$numpermohonanAdmin}}" value="{{$permohonan->pph}}">
                                      <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                                  </td>
                                  <td style="min-width: 200px">
                                    <input type="number" class="result form-control" name="totalPermohonan_{{$numpermohonanAdmin}}" value="{{$permohonan->total}}" readonly>
                                  </td>
                                  <td style="min-width: 200px">
                                    <input type="datetime-local" class="result form-control" name="tglPermohonan_{{$numpermohonanAdmin}}" value="{{$permohonan->tgl_bayar}}">
                                  </td>
                                  <td>
                                    <select class="form-select" aria-label="Default select example" style="min-width: 150px" name="statusPembayaran_{{$numpermohonanAdmin}}">
                                        <option value="Belum Dibayarkan">Belum Dibayarkan</option>
                                        <option value="Tidak Dibayarkan">Tidak Dibayarkan</option>
                                        <option value="Difa">Difa</option>
                                        <option value="Surplus">Surplus</option>
                                        <option value="Sudah Dibayarkan">Sudah Dibayarkan</option>
                                        <option value="{{$permohonan->status_pembayaran}}" selected>{{$permohonan->status_pembayaran}}</option>
                                    </select>
                                </td>
                            </tr>
                            @php
                                $numpermohonanAdmin++;
                            @endphp
                        @endforeach
                        @foreach ($permohonanPegawais as $permohonanPegawai)
                            <tr>
                                <td class='text-center'>{{$loop->iteration}} <input type="hidden" name="idPermohonanPegawai_{{$numpermohonanPegawai}}" value="{{$permohonanPegawai->idPermohonan}}"></td>
                                <td>{{$permohonanPegawai->nama_lengkap}}</td>
                                <td>
                                    {{$permohonanPegawai->nama_barang}}
                                    <ul>
                                        {{$permohonanPegawai->nama_barang}}
                                        @foreach ($komponens as $komponen)
                                            @if ($permohonanPegawai->idPermohonan == $komponen->permohonan_id)
                                            <li>{{$komponen->nama_barang}} - {{$komponen->frekuensi}}x</li>
                                            @endif
                                        @endforeach
                                        </ul>
                                </td>
                                <td class='text-center'>{{$permohonanPegawai->tgl_selesai}}</td>
                                <td>
                                    <select class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="akunPermohonanPegawai_{{$numpermohonanPegawai}}">
                                      @foreach ($akuns as $akun)
                                        <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                        @if ($akun->idAkun == $permohonanPegawai->akun_x_rkakl_id)
                                        <option value="{{$akun->idAkun}}" selected>[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                        @endif
                                      @endforeach
                                    </select>
                                </td>
                                <td>
                                        <select class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="sbmPermohonanPegawai_{{$numpermohonanPegawai}}">
                                        @foreach ($sbms as $sbm)
                                        <option value="{{$sbm->id}}">[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                                            @if ($sbm->id == $permohonanPegawai->ref_sbm_id)
                                            <option value="{{$sbm->id}}" selected>[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                </td>
                                  <td style="min-width: 200px">
                                    <input type="number" class="form-control num1" name="nominalPermohonanPegawai_{{$numpermohonanPegawai}}" value="{{$permohonanPegawai->nominal}}">
                                  </td>
                                  <td style="min-width: 100px">
                                    <div class="input-group mb-3">
                                      <input type="number" class="form-control num2" name="pajakPermohonanPegawai_{{$numpermohonanPegawai}}" value="{{$permohonanPegawai->pph}}">
                                      <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                                  </td>
                                  <td style="min-width: 100px">
                                    <div class="input-group mb-3">
                                      <input type="number" class="form-control num3" name="pajakPermohonanPegawai22_{{$numpermohonanPegawai}}" value="{{$permohonanPegawai->pph22}}">
                                      <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                                  </td>
                                  <td style="min-width: 100px">
                                    <div class="input-group mb-3">
                                      <input type="number" class="form-control num4" name="pajakPermohonanPegawai23_{{$numpermohonanPegawai}}" value="{{$permohonanPegawai->pph23}}">
                                      <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                                  </td>
                                  <td style="min-width: 100px">
                                    <div class="input-group mb-3">
                                      <input type="number" class="form-control num5" name="pajakPermohonanPegawaippn_{{$numpermohonanPegawai}}" value="{{$permohonanPegawai->ppn}}">
                                      <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                                  </td>
                                  <td style="min-width: 200px">
                                    <input type="number" class="result form-control" name="totalPermohonanPegawai_{{$numpermohonanPegawai}}" value="{{$permohonanPegawai->total}}" readonly>
                                  </td>
                                  <td style="min-width: 200px">
                                    <input type="datetime-local" class="result form-control" name="tglPermohonanPegawai_{{$numpermohonanPegawai}}" value="{{$permohonanPegawai->tgl_bayar}}">
                                  </td>
                                  <td>
                                    <select class="form-select" aria-label="Default select example" style="min-width: 150px" name="statusPembayaranPegawai_{{$numpermohonanPegawai}}">
                                        <option value="Belum Dibayarkan">Belum Dibayarkan</option>
                                        <option value="Tidak Dibayarkan">Tidak Dibayarkan</option>
                                        <option value="Difa">Difa</option>
                                        <option value="Surplus">Surplus</option>
                                        <option value="Sudah Dibayarkan">Sudah Dibayarkan</option>
                                        <option value="{{$permohonanPegawai->status_pembayaran}}" selected>{{$permohonanPegawai->status_pembayaran}}</option>
                                    </select>
                                </td>
                            </tr>
                            @php
                                $numpermohonanPegawai++;
                            @endphp
                        @endforeach
                        @foreach ($permohonanKendaraans as $permohonanKendaraan)
                            <tr>
                                <td class='text-center'>{{$loop->iteration}} <input type="hidden" name="idPermohonanKendaraan_{{$numpermohonanKendaraan}}" value="{{$permohonanKendaraan->idPermohonan}}"></td>
                                <td style="min-width: 150px">{{$permohonanKendaraan->username}}</td>
                                <td style="min-width: 200px">
                                    {{$permohonanKendaraan->merek}} - {{$permohonanKendaraan->no_polisi}}
                                    <ul>
                                    @foreach ($komponens as $komponen)
                                        @if ($permohonanKendaraan->idPermohonan == $komponen->permohonan_id)
                                        <li>{{$komponen->nama_barang}} - {{$komponen->frekuensi}}x</li>
                                        @endif
                                    @endforeach
                                    </ul>
                                </td>
                                <td class='text-center' style="min-width: 150px">{{$permohonanKendaraan->tgl_selesai}}</td>
                                <td>
                                    <select class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 320px" name="akunPermohonanKendaraan_{{$numpermohonanKendaraan}}">
                                      @foreach ($akuns as $akun)
                                      <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                        @if ($akun->idAkun == $permohonanKendaraan->akun_x_rkakl_id)
                                        <option value="{{$akun->idAkun}}" selected>[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                        @endif
                                      @endforeach
                                    </select>
                                </td>
                                <td>
                                        <select class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="sbmPermohonanKendaraan_{{$numpermohonanKendaraan}}">
                                        @foreach ($sbms as $sbm)
                                        <option value="{{$sbm->id}}">[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                                            @if ($sbm->id == $permohonanKendaraan->ref_sbm_id)
                                            <option value="{{$sbm->id}}" selected>[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                </td>
                                  <td style="min-width: 200px">
                                    <input type="number" class="form-control num1" name="nominalPermohonanKendaraan_{{$numpermohonanKendaraan}}" value="{{$permohonanKendaraan->nominal}}">
                                  </td>
                                  <td style="min-width: 100px">
                                    <div class="input-group mb-3">
                                      <input type="number" class="form-control num2" name="pajakPermohonanKendaraan_{{$numpermohonanKendaraan}}" value="{{$permohonanKendaraan->pph}}">
                                      <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                                  </td>
                                  <td style="min-width: 100px">
                                    <div class="input-group mb-3">
                                      <input type="number" class="form-control num3" name="pajakPermohonanKendaraan22_{{$numpermohonanKendaraan}}" value="{{$permohonanKendaraan->pph22}}">
                                      <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                                  </td>
                                  <td style="min-width: 100px">
                                    <div class="input-group mb-3">
                                      <input type="number" class="form-control num4" name="pajakPermohonanKendaraan23_{{$numpermohonanKendaraan}}" value="{{$permohonanKendaraan->pph23}}">
                                      <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                                  </td>
                                  <td style="min-width: 100px">
                                    <div class="input-group mb-3">
                                      <input type="number" class="form-control num5" name="pajakPermohonanKendaraan_{{$numpermohonanKendaraan}}" value="{{$permohonanKendaraan->ppn}}">
                                      <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                                  </td>
                                  <td style="min-width: 200px">
                                    <input type="number" class="result form-control" name="totalPermohonanKendaraan_{{$numpermohonanKendaraan}}" value="{{$permohonanKendaraan->total}}" readonly>
                                  </td>
                                  <td style="min-width: 200px">
                                    <input type="datetime-local" class="result form-control" name="tglPermohonanKendaraan_{{$numpermohonanKendaraan}}" value="{{$permohonanKendaraan->tgl_bayar}}" readonly>
                                  </td>
                                  <td>
                                    <select class="form-select" aria-label="Default select example" style="min-width: 150px" name="statusPembayaranKendaraan_{{$numpermohonanKendaraan}}">
                                        <option value="Belum Dibayarkan">Belum Dibayarkan</option>
                                        <option value="Tidak Dibayarkan">Tidak Dibayarkan</option>
                                        <option value="Difa">Difa</option>
                                        <option value="Surplus">Surplus</option>
                                        <option value="Sudah Dibayarkan">Sudah Dibayarkan</option>
                                        <option value="{{$permohonanKendaraan->status_pembayaran}}" selected>{{$permohonanKendaraan->status_pembayaran}}</option>
                                    </select>
                                </td>
                            </tr>
                            @php
                                $numpermohonanKendaraan++;
                            @endphp
                        @endforeach
                        @foreach ($permohonanRuangans as $permohonanRuangan)
                            <tr>
                                <td class='text-center'>{{$loop->iteration}} <input type="hidden" name="idPermohonanRuangan_{{$numpermohonanRuangan}}" value="{{$permohonanRuangan->idPermohonan}}"></td>
                                <td style="min-width: 150px">{{$permohonanRuangan->username}}</td>
                                <td style="min-width: 200px">
                                    {{$permohonanRuangan->nama_ruangan}} - {{$permohonanRuangan->kode_ruangan}}
                                    <ul>
                                    @foreach ($komponens as $komponen)
                                        @if ($permohonanRuangan->idPermohonan == $komponen->permohonan_id)
                                        <li>{{$komponen->nama_barang}} - {{$komponen->frekuensi}}x</li>
                                        @endif
                                    @endforeach
                                    </ul>
                                </td>
                                <td class='text-center' style="min-width: 150px">{{$permohonanRuangan->tgl_selesai}}</td>
                                <td>
                                    <select class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 320px" name="akunPermohonanRuangan_{{$numpermohonanRuangan}}">
                                      @foreach ($akuns as $akun)
                                      <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                        @if ($akun->idAkun == $permohonanRuangan->akun_x_rkakl_id)
                                        <option value="{{$akun->idAkun}}" selected>[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                        @endif
                                      @endforeach
                                    </select>
                                </td>
                                <td>
                                        <select class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="sbmPermohonanRuangan_{{$numpermohonanRuangan}}">
                                        @foreach ($sbms as $sbm)
                                        <option value="{{$sbm->id}}">[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                                            @if ($sbm->id == $permohonanRuangan->ref_sbm_id)
                                            <option value="{{$sbm->id}}" selected>[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                </td>
                                  <td style="min-width: 200px">
                                    <input type="number" class="form-control num1" name="nominalPermohonanRuangan_{{$numpermohonanRuangan}}" value="{{$permohonanRuangan->nominal}}">
                                  </td>
                                  <td style="min-width: 100px">
                                    <div class="input-group mb-3">
                                      <input type="number" class="form-control num2" name="pajakPermohonanRuangan_{{$numpermohonanRuangan}}" value="{{$permohonanRuangan->pph}}">
                                      <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                                  </td>
                                  <td style="min-width: 100px">
                                    <div class="input-group mb-3">
                                      <input type="number" class="form-control num3" name="pajakPermohonanRuangan22_{{$numpermohonanRuangan}}" value="{{$permohonanRuangan->pph22}}">
                                      <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                                  </td>
                                  <td style="min-width: 100px">
                                    <div class="input-group mb-3">
                                      <input type="number" class="form-control num4" name="pajakPermohonanRuangan23_{{$numpermohonanRuangan}}" value="{{$permohonanRuangan->pph23}}">
                                      <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                                  </td>
                                  <td style="min-width: 100px">
                                    <div class="input-group mb-3">
                                      <input type="number" class="form-control num5" name="pajakPermohonanRuangan_{{$numpermohonanRuangan}}" value="{{$permohonanRuangan->ppn}}">
                                      <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                                  </td>
                                  <td style="min-width: 200px">
                                    <input type="number" class="result form-control" name="totalPermohonanRuangan_{{$numpermohonanRuangan}}" value="{{$permohonanRuangan->total}}" readonly>
                                  </td>
                                  <td style="min-width: 200px">
                                    <input type="datetime-local" class="result form-control" name="tglPermohonanRuangan_{{$numpermohonanRuangan}}" value="{{$permohonanRuangan->tgl_bayar}}">
                                  </td>
                                  <td>
                                    <select class="form-select" aria-label="Default select example" style="min-width: 150px" name="statusPembayaranRuangan_{{$numpermohonanRuangan}}">
                                        <option value="Belum Dibayarkan">Belum Dibayarkan</option>
                                        <option value="Tidak Dibayarkan">Tidak Dibayarkan</option>
                                        <option value="Difa">Difa</option>
                                        <option value="Surplus">Surplus</option>
                                        <option value="Sudah Dibayarkan">Sudah Dibayarkan</option>
                                        <option value="{{$permohonanRuangan->status_pembayaran}}" selected>{{$permohonanRuangan->status_pembayaran}}</option>
                                    </select>
                                </td>
                            </tr>
                            @php
                                $numpermohonanRuangan++;
                            @endphp
                        @endforeach
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
                  <input type="hidden" name="numPermohonanAdmin" value="{{$numpermohonanAdmin}}">
                  <input type="hidden" name="numPermohonanPegawai" value="{{$numpermohonanPegawai}}">
                  <input type="hidden" name="numPermohonanKendaraan" value="{{$numpermohonanKendaraan}}">
                  <input type="hidden" name="numPermohonanRuangan" value="{{$numpermohonanRuangan}}">
                  <input type="hidden" name="numDokumen" value="{{$numdokumen}}">
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
                        <a href="{{url('/service_bendahara/' . 'approval-1')}}" class="btn btn-dark">Kembali</a>
                        <button class="btn btn-success" type="submit">Approv dan Bayarkan</button>
                    </div>
                  </div>
                </div>
                </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection