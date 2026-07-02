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
                          <div class="col-10">: {{$info->tgl_mulai}}</div>
                      </div>

                    <div class="row small">
                        <div class="col-2">Tanggal Selesai</div>
                        <div class="col-10">: {{ ($info->tgl_selesai) }}</div>
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
                      <br>
                  </div>

                  <form action="{{url('/cu_kegiatan_bendahara')}}" method="post">
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
                  @csrf
                  <input type="hidden" name="idKegiatan" value="{{$info->id}}">
                  @php
                      $numpegawai = 0;
                      $numnonpegawai = 0;
                      $numperangkat = 1;
                  @endphp

                  @if ($info->is_acceptBend == 'approval-1')
                        @foreach ($perangkats as $perangkat)
                            <div class="col-md-12 mb-3">
                                <div class="table-responsive">
                                <div class="d-flex justify-content-between">
                                    <h5 class="fw-bold">Informasi {{$perangkat->nama_fasilitas}}</h5>

                                    <!-- Tombol Print to PDF dengan data yang telah dienkode -->
                                    {{-- <a class="btn btn-success btn-sm mb-3" href="{{ url('/printpdf?data=' . $jsonData) }}">Print to PDF</a> --}}
                                </div>
                                <table id="calculationTable{{$numperangkat}}" name="{{$perangkat->nama_fasilitas}}" class="table table-bordered calculationTable" style="width: 100%">
                                    <thead>

                                        <tr class="text-center small">
                                                <th>Nama Lengkap</th>
                                                <th>Pangkat/Golongan</th>
                                                <th>Sebagai</th>
                                                <th>Detail</th>
                                                <th class="th-md" >No Akun</th>
                                                <th>Nominal</th>
                                                <th>Total dibayar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($pegawais->isNotEmpty())
                                            @foreach ($pegawais as $pegawai)
                                                @if ($pegawai->fasilitas_id == $perangkat->id && $pegawai->kode == 'honor')
                                                    <tr>
                                                        <input id="nama_perangkat_{{$numpegawai}}"
                                                            name="perangkat_{{$numpegawai}}"
                                                            type="hidden"
                                                            value="{{$perangkat->nama_fasilitas}}">
                                                        <input id="perangkat_{{$numpegawai}}"
                                                            name="perangkat_{{$numpegawai}}"
                                                            type="hidden"
                                                            value="{{$numperangkat}}">
                                                        {{-- NAMA PEGAWAI --}}
                                                        <td style="min-width: 150px">{{$pegawai->nama_lengkap}}
                                                            <input type="hidden"
                                                            name="idPerangkatPegawai_{{$numpegawai}}"
                                                            value="{{$pegawai->idPerangkatAcara}}"
                                                            class="forPDF"
                                                            data-head="nama"></td>

                                                        {{-- PANGKAT PEGAWAI --}}
                                                        <td class='text-center' style="min-width: 120px">{{$pegawai->pangkat}} | {{$pegawai->golongan}}</td>

                                                        {{-- SEBAGAI PEGAWAI --}}
                                                        <td class='text-center' style="min-width: 120px">{{$pegawai->sebagai}}</td>

                                                        {{-- DETAIL PEGAWAI --}}
                                                        <input id="satuan_{{$numpegawai}}"
                                                            type="hidden"
                                                            value="{{$pegawai->satuan}}">
                                                        <td class='text-center' style="min-width: 50px">{{$pegawai->satuan}}-{{$pegawai->detail_satuan}}</td>

                                                        {{-- NO AKUN PEGAWAI --}}
                                                        <td >
                                                            <select disabled class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="akunPegawai_{{$numpegawai}}">
                                                                @foreach ($akuns as $akun)
                                                                    <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        {{-- SATUAN HONORARIUM PEGAWAI --}}
                                                        @if ($perangkat->nama_fasilitas == 'Panitia')
                                                        <td style="min-width: 200px">
                                                                <input readonly id="input_satuan_honorarium_panitia_{{$numpegawai}}"
                                                                    type="number"
                                                                    class="form-control input-satuan-honorarium-panitia prevent-submit"
                                                                    min="0"
                                                                    name="satuan_honorarium_{{$numpegawai}}"
                                                                    value="{{$pegawai->honorarium}}">
                                                            </td>
                                                        @else
                                                            <td style="min-width: 200px">
                                                                <input readonly id="input_satuan_honorarium_{{$numpegawai}}"
                                                                    type="number"
                                                                    class="form-control input-satuan-honorarium prevent-submit"
                                                                    min="0"
                                                                    name="satuan_honorarium_{{$numpegawai}}"
                                                                    value="{{$pegawai->honorarium}}">
                                                            </td>
                                                        @endif

                                                        {{-- JUMLAH HONORARIUM PEGAWAI --}}
                                                        <td style="min-width: 200px">
                                                            <input readonly id="input_jumlah_honorarium_{{$numpegawai}}"
                                                                type="number"
                                                                class="form-control totalSeluruhApv1_{{$numperangkat}} input-jumlah-honorarium prevent-submit"
                                                                min="0"
                                                                name="jumlah_honorarium_{{$numpegawai}}"
                                                                value="{{$pegawai->honorarium}}"
                                                                readonly>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $numpegawai++;
                                                    @endphp
                                                @elseif ($pegawai->fasilitas_id == $perangkat->id && $pegawai->sebagai == 'Supir')
                                                    <tr>
                                                        <input id="nama_perangkat_{{$numpegawai}}"
                                                            name="nama_perangkat_{{$numpegawai}}"
                                                            type="hidden"
                                                            value="{{$perangkat->nama_fasilitas}}">
                                                        <input id="perangkat_{{$numpegawai}}"
                                                            name="perangkat_{{$numpegawai}}"
                                                            type="hidden"
                                                            value="{{$numperangkat}}">
                                                        {{-- NAMA SUPIR --}}
                                                        <td style="min-width: 150px">{{$pegawai->nama_lengkap}} <input type="hidden" name="idPerangkatPegawai_{{$numpegawai}}" value="{{$pegawai->idPerangkatAcara}}"></td>

                                                        {{-- PANGKAT SUPIR --}}
                                                        <td class='text-center' style="min-width: 120px">{{$pegawai->pangkat}} | {{$pegawai->golongan}}</td>

                                                        {{-- SEBAGAI SUPIR --}}
                                                        <td class='text-center' style="min-width: 120px">{{$pegawai->sebagai}}</td>

                                                        {{-- DETAIL SUPIR --}}
                                                        <input id="satuan_{{$numpegawai}}"
                                                            type="hidden"
                                                            value="{{$pegawai->satuan}}">
                                                        <td class='text-center' style="min-width: 50px">{{$pegawai->satuan}}-{{$pegawai->detail_satuan}}</td>

                                                        {{-- NO AKUN SUPIR --}}
                                                        <td >
                                                            <select disabled class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="akunPegawai_{{$numpegawai}}">
                                                                @foreach ($akuns as $akun)
                                                                    <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        {{-- SATUAN HONORARIUM SUPIR --}}
                                                        <td style="min-width: 200px">
                                                            <input readonly id="input_satuan_supir_{{$numpegawai}}"
                                                                type="number"
                                                                class="form-control input-satuan-supir prevent-submit"
                                                                min="0"
                                                                name="satuan_supir_{{$numpegawai}}"
                                                                value="{{$pegawai->uang_harian}}">
                                                        </td>

                                                        {{-- JUMLAH HONORARIUM SUPIR --}}
                                                        <td style="min-width: 200px">
                                                            <input readonly id="input_jumlah_supir_{{$numpegawai}}"
                                                                type="number"
                                                                class="form-control totalSeluruhApv1_{{$numperangkat}} input-jumlah-supir prevent-submit"
                                                                min="0"
                                                                name="jumlah_supir_{{$numpegawai}}"
                                                                value="{{$pegawai->nominal_perjadin}}"
                                                                readonly>
                                                        </td>
                                                    </tr>
                                                @elseif ($pegawai->fasilitas_id == $perangkat->id && $info->jenis_program == 'Penugasan' && $pegawai->kode == 'harian')
                                                    <tr>
                                                        <input id="nama_perangkat_{{$numpegawai}}"
                                                            name="perangkat_{{$numpegawai}}"
                                                            type="hidden"
                                                            value="{{$perangkat->nama_fasilitas}}">
                                                        <input id="perangkat_{{$numpegawai}}"
                                                            name="perangkat_{{$numpegawai}}"
                                                            type="hidden"
                                                            value="{{$numperangkat}}">
                                                        {{-- NAMA PEGAWAI --}}
                                                        <td style="min-width: 150px">{{$pegawai->nama_lengkap}}
                                                            <input type="hidden"
                                                            name="idPerangkatPegawai_{{$numpegawai}}"
                                                            value="{{$pegawai->idPerangkatAcara}}"
                                                            class="forPDF"
                                                            data-head="nama"></td>

                                                        {{-- PANGKAT PEGAWAI --}}
                                                        <td class='text-center' style="min-width: 120px">{{$pegawai->pangkat}} | {{$pegawai->golongan}}</td>

                                                        {{-- SEBAGAI PEGAWAI --}}
                                                        <td class='text-center' style="min-width: 120px">{{$pegawai->sebagai}}</td>

                                                        {{-- DETAIL PEGAWAI --}}
                                                        <input id="satuan_{{$numpegawai}}"
                                                            type="hidden"
                                                            value="{{$pegawai->satuan}}">
                                                        <td class='text-center' style="min-width: 50px">{{$pegawai->satuan}}-{{$pegawai->detail_satuan}}</td>

                                                        {{-- NO AKUN PEGAWAI --}}
                                                        <td >
                                                            <select disabled class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="akunPegawaiPenugasan_{{$numpegawai}}">
                                                                @foreach ($akuns as $akun)
                                                                    <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        {{-- SATUAN HONORARIUM PEGAWAI --}}
                                                        @if ($perangkat->nama_fasilitas == 'Panitia')
                                                        <td style="min-width: 200px">
                                                                <input readonly id="input_satuan_honorarium_panitia_{{$numpegawai}}"
                                                                    type="number"
                                                                    class="form-control input-satuan-honorarium-panitia prevent-submit"
                                                                    min="0"
                                                                    name="satuan_honorarium_{{$numpegawai}}"
                                                                    value="{{$pegawai->honorarium}}">
                                                            </td>
                                                        @else
                                                            <td style="min-width: 200px">
                                                                <input readonly id="input_satuan_honorarium_{{$numpegawai}}"
                                                                    type="number"
                                                                    class="form-control input-satuan-honorarium prevent-submit"
                                                                    min="0"
                                                                    name="satuan_honorarium_{{$numpegawai}}"
                                                                    value="{{$pegawai->honorarium}}">
                                                            </td>
                                                        @endif

                                                        {{-- JUMLAH HONORARIUM PEGAWAI --}}
                                                        <td style="min-width: 200px">
                                                            <input id="input_jumlah_honorarium_{{$numpegawai}}"
                                                                type="number"
                                                                class="form-control totalSeluruhApv1_{{$numperangkat}} input-jumlah-honorarium prevent-submit"
                                                                min="0"
                                                                name="jumlah_honorarium_{{$numpegawai}}"
                                                                value="{{$pegawai->honorarium}}"
                                                                readonly>
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
                                                @if ($nonpegawai->fasilitas_id == $perangkat->id && $nonpegawai->kode == 'honor')
                                                    <tr>
                                                        <input id="perangkat_non_{{$numnonpegawai}}"
                                                            name="perangkat_non_{{$numnonpegawai}}"
                                                            type="hidden"
                                                            value="{{$perangkat->nama_fasilitas}}">
                                                        {{-- NAMA PEGAWAI --}}
                                                        <td style="min-width: 150px">{{$nonpegawai->nama_lengkap}} <input type="hidden" name="idPerangkatNonPegawai_{{$numnonpegawai}}" value="{{$nonpegawai->idPerangkatAcara}}"></td>

                                                        {{-- PANGKAT PEGAWAI --}}
                                                        <td class='text-center' style="min-width: 120px">{{$nonpegawai->pangkat}} | {{$nonpegawai->golongan}}</td>

                                                        {{-- SEBAGAI PEGAWAI --}}
                                                        <td class='text-center' style="min-width: 120px">{{$nonpegawai->sebagai}}</td>

                                                        {{-- DETAIL PEGAWAI --}}
                                                        <input id="satuan_non_{{$numnonpegawai}}"
                                                            type="hidden"
                                                            value="{{$nonpegawai->satuan}}">
                                                        <td class='text-center' style="min-width: 50px">{{$nonpegawai->satuan}}-{{$nonpegawai->detail_satuan}}</td>

                                                        {{-- NO AKUN PEGAWAI --}}
                                                        <td >
                                                            <select class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="akunNonPegawai_{{$numnonpegawai}}">
                                                                @foreach ($akuns as $akun)
                                                                    <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        {{-- SATUAN HONORARIUM NON PEGAWAI --}}
                                                        @if ($perangkat->nama_fasilitas == 'Panitia')
                                                            <td style="min-width: 200px">
                                                                <input id="input_satuan_honorarium_panitia_non_{{$numnonpegawai}}"
                                                                        type="number"
                                                                        class="form-control input-satuan-honorarium-panitia-non prevent-submit"
                                                                        min="0"
                                                                        name="satuan_honorarium_non_{{$numnonpegawai}}"
                                                                        value="">
                                                            </td>
                                                        @else
                                                            <td style="min-width: 200px">
                                                                <input id="input_satuan_honorarium_non_{{$numnonpegawai}}"
                                                                        type="number"
                                                                        class="form-control input-satuan-honorarium-non prevent-submit"
                                                                        min="0"
                                                                        name="satuan_honorarium_non_{{$numnonpegawai}}"
                                                                        value="">
                                                            </td>
                                                        @endif

                                                        {{-- JUMLAH HONORARIUM NON PEGAWAI --}}
                                                        <td style="min-width: 200px">
                                                            <input id="input_jumlah_honorarium_non_{{$numnonpegawai}}"
                                                                    type="number"
                                                                    class="form-control totalSeluruhApv1_{{$numperangkat}} input-jumlah-honorarium-non prevent-submit"
                                                                    min="0"
                                                                    name="jumlah_honorarium_non_{{$numnonpegawai}}"
                                                                    value=""
                                                                    readonly>
                                                        </td>

                                                    </tr>
                                                    @php
                                                        $numnonpegawai++;
                                                    @endphp

                                                @elseif ($nonpegawai->fasilitas_id == $perangkat->id && $info->jenis_program == 'Penugasan' && $nonpegawai->kode == 'harian')
                                                <tr>
                                                        <input id="perangkat_non_{{$numnonpegawai}}"
                                                            name="perangkat_non_{{$numnonpegawai}}"
                                                            type="hidden"
                                                            value="{{$perangkat->nama_fasilitas}}">
                                                        {{-- NAMA PEGAWAI --}}
                                                        <td style="min-width: 150px">{{$nonpegawai->nama_lengkap}} <input type="hidden" name="idPerangkatNonPegawai_{{$numnonpegawai}}" value="{{$nonpegawai->idPerangkatAcara}}"></td>

                                                        {{-- PANGKAT PEGAWAI --}}
                                                        <td class='text-center' style="min-width: 120px">{{$nonpegawai->pangkat}} | {{$nonpegawai->golongan}}</td>

                                                        {{-- SEBAGAI PEGAWAI --}}
                                                        <td class='text-center' style="min-width: 120px">{{$nonpegawai->sebagai}}</td>

                                                        {{-- DETAIL PEGAWAI --}}
                                                        <input id="satuan_non_{{$numnonpegawai}}"
                                                            type="hidden"
                                                            value="{{$nonpegawai->satuan}}">
                                                        <td class='text-center' style="min-width: 50px">{{$nonpegawai->satuan}}-{{$nonpegawai->detail_satuan}}</td>

                                                        {{-- NO AKUN PEGAWAI --}}
                                                        <td >
                                                            <select class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="akunNonPegawai_{{$numnonpegawai}}">
                                                                @foreach ($akuns as $akun)
                                                                    <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        {{-- SATUAN HONORARIUM NON PEGAWAI --}}
                                                        @if ($perangkat->nama_fasilitas == 'Panitia')
                                                            <td style="min-width: 200px">
                                                                <input id="input_satuan_honorarium_panitia_non_{{$numnonpegawai}}"
                                                                        type="number"
                                                                        class="form-control input-satuan-honorarium-panitia-non prevent-submit"
                                                                        min="0"
                                                                        name="satuan_honorarium_non_{{$numnonpegawai}}"
                                                                        value="">
                                                            </td>
                                                        @else
                                                            <td style="min-width: 200px">
                                                                <input id="input_satuan_honorarium_non_{{$numnonpegawai}}"
                                                                        type="number"
                                                                        class="form-control input-satuan-honorarium-non prevent-submit"
                                                                        min="0"
                                                                        name="satuan_honorarium_non_{{$numnonpegawai}}"
                                                                        value="">
                                                            </td>
                                                        @endif

                                                        {{-- JUMLAH HONORARIUM NON PEGAWAI --}}
                                                        <td style="min-width: 200px">
                                                            <input id="input_jumlah_honorarium_non_{{$numnonpegawai}}"
                                                                    type="number"
                                                                    class="form-control totalSeluruhApv1_{{$numperangkat}} input-jumlah-honorarium-non prevent-submit"
                                                                    min="0"
                                                                    name="jumlah_honorarium_non_{{$numnonpegawai}}"
                                                                    value=""
                                                                    readonly>
                                                        </td>

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
                                        <td colspan="6" class="fw-bold text-end">Sub Total</td>
                                        <td><input type="number" class="subTotalPerangkatApv1_{{$numperangkat}} total-ok form-control" value="" readonly></td>
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

                  @elseif ($info->is_acceptBend == 'approval-2')

                    @foreach ($perangkats as $perangkat)
                            @if ($perangkat->nama_fasilitas == 'Supir')
                                <div class="col-md-12 mb-3">
                                    <div class="table-responsive">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="fw-bold">Informasi {{$perangkat->nama_fasilitas}}</h5>
                                        </div>

                                        <table id="calculationTable{{$numperangkat}}" name="{{$perangkat->nama_fasilitas}}" class="table table-bordered calculationTable" style="width: 100%">
                                            <thead>
                                                <tr class="text-center small">
                                                        <!--<th>Nama Lengkap</th>-->
                                                        <th>Pangkat/Golongan</th>
                                                        <th>Sebagai</th>
                                                        <th>Detail</th>
                                                        <th>No Akun</th>
                                                        <th>Uang Harian</th>
                                                        <th>Uang Harian Halfday/Fullday/Fullboard</th>
                                                        {{-- <th>Uang Harian Fullboard</th> --}}
                                                        <th>Uang Representasi</th>
                                                        <th>Total dibayar</th>
                                                        <th>No Rekening</th>
                                                        <th>Tanggal Pembayaran</th>
                                                        <th>Status Pembiayaan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($pegawais->isNotEmpty())
                                                    @foreach ($pegawais as $pegawai)
                                                        @if ($pegawai->fasilitas_id == $perangkat->id )
                                                            <tr>
                                                                <input id="nama_perangkat_{{$numpegawai}}"
                                                                    name="nama_perangkat_{{$numpegawai}}"
                                                                    type="hidden"
                                                                    value="{{$perangkat->nama_fasilitas}}">
                                                                <input id="perangkat_{{$numpegawai}}"
                                                                    name="perangkat_{{$numpegawai}}"
                                                                    type="hidden"
                                                                    value="{{$numperangkat}}">

                                                                {{-- NAMA PEGAWAI --}}
                                                                <td style="min-width: 150px">{{$pegawai->nama_lengkap}}
                                                                    <input type="hidden" name="idPerangkatPegawai_{{$numpegawai}}" value="{{$pegawai->idPerangkatAcara}}">
                                                                    <input type="hidden" name="namaPegawai_{{$numpegawai}}" value="{{$pegawai->nama_lengkap}}">
                                                                </td>

                                                                {{-- PANGKAT PEGAWAI --}}
                                                                <td class='text-center' style="min-width: 120px">{{$pegawai->pangkat}} | {{$pegawai->golongan}}</td>

                                                                {{-- SEBAGAI PEGAWAI --}}
                                                                <td class='text-center' style="min-width: 120px">{{$pegawai->sebagai}}
                                                                    <input type="hidden" name="sebagaiPegawai_{{$numpegawai}}" value="{{$pegawai->sebagai}}">
                                                                </td>

                                                                {{-- DETAIL PEGAWAI --}}
                                                                <input id="satuan_{{$numpegawai}}"
                                                                        type="hidden"
                                                                        value="{{$pegawai->satuan}}">
                                                                <td class='text-center' style="min-width: 50px">{{$pegawai->satuan}}-{{$pegawai->detail_satuan}}</td>

                                                                @foreach ($keuangans as $keuangan)
                                                                    @if ($keuangan->perangkat_acara == $pegawai->idPerangkatAcara  && $keuangan->kebutuhan_id != NULL)
                                                                        {{-- AKUN 2 PANITIA --}}
                                                                        <td >
                                                                            <select disabled class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="akunHarianPegawai_{{$numpegawai}}">
                                                                                @foreach ($akuns as $akun)
                                                                                    @if ($keuangan->akun_x_rkakl == $akun->idAkun)
                                                                                        <option value="{{$akun->idAkun}}" selected>[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                                                    @endif
                                                                                        <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>

                                                                        {{-- NOMINAL HONORARIUM PEGAWAI SUPIR HIDDEN--}}
                                                                        <input id="input_nominal_honorarium_{{$numpegawai}}"
                                                                            type="hidden"
                                                                            class="form-control input-nominal-honorarium prevent-submit"
                                                                            min="0"
                                                                            name="nominal_honorarium_{{$numpegawai}}"
                                                                            value="0">

                                                                            {{-- PAJAK PEGAWAI SUPIR HIDDEN--}}
                                                                            <input id="input_pph_{{$numpegawai}}"
                                                                                type="hidden"
                                                                                class="form-control input-pph pajak prevent-submit"
                                                                                min="0"
                                                                                name="pph_{{$numpegawai}}"
                                                                                step="0.1"
                                                                                value=""
                                                                                data-perangkat="{{ $perangkat->nama_fasilitas }}"
                                                                                data-status="{{ $pegawai->statusPegawai }}"
                                                                                data-golongan="{{ $pegawai->golongan }}"
                                                                            >


                                                                        {{-- HARIAN PEGAWAI SUPIR --}}
                                                                        <td style="min-width: 200px">
                                                                            <input readonly type="number" id="harianPegawai_{{$numpegawai}}" class="form-control harian-pegawai  prevent-submit" min="0" name="harianPegawai_{{$numpegawai}}" value="{{$keuangan->uang_harian}}">
                                                                        </td>

                                                                        {{-- HARIAN LAINNYA PEGAWAI SUPIR --}}
                                                                        <td style="min-width: 200px">
                                                                            <input readonly type="number" id="hariandayPegawai_{{$numpegawai}}" class="form-control  harianday-pegawai prevent-submit" min="0" name="hariandayPegawai_{{$numpegawai}}" value="{{$keuangan->uang_harian_fullday}}">
                                                                        </td>

                                                                        {{-- REPRESENTASI PEGAWAI SUPIR --}}
                                                                        <td style="min-width: 200px">
                                                                            <input  readonly type="number" id="representasiPegawai_{{$numpegawai}}" class="form-control representasi-pegawai  prevent-submit" min="0" name="representasiPegawai_{{$numpegawai}}" value="{{$keuangan->uang_harian_representasi}}">
                                                                        </td>

                                                                        {{-- TOTAL DIBAYAR PEGAWAI --}}
                                                                        <td style="min-width: 200px">
                                                                            <input  type="number" id="totalDibayar_pegawai_{{$numpegawai}}" class="totalSeluruh_{{$numperangkat}} form-control" name="totalPegawai_{{$numpegawai}}" value="{{$keuangan->jumlah_honorarium + $keuangan->nominal_perjadin}}" readonly>
                                                                        </td>

                                                                        {{-- NO REKENING PEGAWAI --}}
                                                                        <td style="min-width: 200px">
                                                                            <div style="display: flex; gap: 5px;">
                                                                                <input type="text" class="form-control  prevent-submit" min="0" name="bank_{{$numpegawai}}" value="{{$pegawai->bank}}"  style="flex: 1;" readonly>
                                                                                <input type="text" class="form-control  prevent-submit" min="0" name="no_rekening_{{$numpegawai}}" value="{{$pegawai->no_rekening}}"  style="flex: 2;" readonly>
                                                                            </div>
                                                                        </td>

                                                                        {{-- TGL BAYAR PEGAWAI --}}
                                                                        <td style="min-width: 200px">
                                                                        <input readonly type="datetime-local" class="result form-control" name="tglbayarPegawai_{{$numpegawai}}" value="{{$keuangan->tgl_bayar}}">
                                                                        </td>

                                                                        {{-- STATUS PEMBAYARAN PEGAWAI --}}
                                                                        <td style="min-width: 150px">
                                                                            <input type="hidden" id="idPegawai_{{$numpegawai}}" name="idPegawai_{{$numpegawai}}" value="{{$pegawai->idPegawai}}">
                                                                            <select disabled class="form-select" aria-label="Default select example" name="statusPembiyaanPegawai_{{$numpegawai}}">
                                                                                @php
                                                                                    $statusOptions = [
                                                                                        'Belum Dibayarkan',
                                                                                        'Tidak Dibayarkan',
                                                                                        'Sudah Dibayarkan',
                                                                                    ];
                                                                                    $selectedStatus = $keuangan->status ?? 'Belum Dibayarkan'; // Default jika null
                                                                                @endphp

                                                                                @foreach ($statusOptions as $option)
                                                                                    <option value="{{ $option }}" {{ $selectedStatus === $option ? 'selected' : '' }}>
                                                                                        {{ $option }}
                                                                                    </option>
                                                                                @endforeach
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

                                            </tbody>
                                            <tfoot>
                                                <tr>

                                                    <td colspan="8" class="fw-bold  text-end">Sub Total</td>

                                                    <td><input type="number" class="subTotalPerangkat_{{$numperangkat}} total-ok form-control" value="" readonly></td>
                                                </tr>

                                                </tfoot>
                                        </table>

                                    </div>
                                </div>
                            @else
                                <div class="col-md-12 mb-3">
                                    <div class="table-responsive">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="fw-bold">Informasi {{$perangkat->nama_fasilitas}}</h5>

                                    <!-- Tombol untuk Print to PDF yang akan diubah href-nya secara dinamis -->
                                    <a id="printPdfLink" class="btn btn-success printPdfLink btn-sm mb-3" data-perangkat="{{ $perangkat->nama_fasilitas }}" href="#">Print to PDF</a>
                                    </div>
                                    <table id="calculationTable{{$numperangkat}}" name="{{$perangkat->nama_fasilitas}}" class="table table-bordered calculationTable" style="width: 100%">
                                        <thead>

                                        <tr class="text-center small">
                                                <th>Nama Lengkap</th>
                                                <th>Pangkat/Golongan</th>
                                                <th>Sebagai</th>
                                                <th>Detail</th>
                                                @if ($info->jenis_program != 'Penugasan')
                                                    <th >No Akun</th>
                                                    {{-- <th>SBM</th> --}}
                                                    <th>Satuan Honorarium</th>
                                                    <th>Jumlah Honorarium</th>
                                                    <th>PPh21</th>
                                                    <th>Nilai PPh</th>
                                                    <th>Nominal Honorarium</th>
                                                @endif
                                                @if ($perangkat->nama_fasilitas == 'Panitia'|| $perangkat->nama_fasilitas == 'Supir')
                                                    <th>No Akun</th>
                                                    <th>Uang Harian</th>
                                                    <th>Uang Harian Halfday/Fullday/Fullboard</th>
                                                    {{-- <th>Uang Harian Fullboard</th> --}}
                                                    <th>Uang Representasi</th>
                                                    <th>Nominal Perjadin</th>
                                                @endif
                                                <th>Total dibayar</th>
                                                <th>No Rekening</th>
                                                <th>Tanggal Pembayaran</th>
                                                <th>Status Pembiayaan</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if ($pegawais->isNotEmpty())
                                            @foreach ($pegawais as $pegawai)
                                                @if ($pegawai->fasilitas_id == $perangkat->id  )
                                                <tr>
                                                    <input id="nama_perangkat_{{$numpegawai}}"
                                                            name="nama_perangkat_{{$numpegawai}}"
                                                            type="hidden"
                                                            value="{{$perangkat->nama_fasilitas}}">
                                                        <input id="perangkat_{{$numpegawai}}"
                                                            name="perangkat_{{$numpegawai}}"
                                                            type="hidden"
                                                            value="{{$numperangkat}}">

                                                            {{-- NAMA PEGAWAI --}}
                                                            <td style="min-width: 150px">{{$pegawai->nama_lengkap}}
                                                                <input type="hidden" name="idPerangkatPegawai_{{$numpegawai}}" value="{{$pegawai->idPerangkatAcara}}">
                                                                <input type="hidden" name="namaPegawai_{{$numpegawai}}" value="{{$pegawai->nama_lengkap}}">
                                                            </td>

                                                            {{-- PANGKAT PEGAWAI --}}
                                                            <td class='text-center' style="min-width: 120px">{{$pegawai->pangkat}} | {{$pegawai->golongan}}</td>

                                                            {{-- SEBAGAI PEGAWAI --}}
                                                            <td class='text-center' style="min-width: 120px">{{$pegawai->sebagai}}
                                                                <input type="hidden" name="sebagaiPegawai_{{$numpegawai}}" value="{{$pegawai->sebagai}}">
                                                            </td>

                                                    {{-- DETAIL PEGAWAI --}}
                                                    <input id="satuan_{{$numpegawai}}"
                                                            type="hidden"
                                                            value="{{$pegawai->satuan}}">
                                                <td class='text-center' style="min-width: 50px">{{$pegawai->satuan}}-{{$pegawai->detail_satuan}}</td>

                                                @foreach ($keuangans as $keuangan)
                                                    @if ($keuangan->perangkat_acara == $pegawai->idPerangkatAcara && $keuangan->kode == 'honor')
                                                        {{-- NO AKUN PEGAWAI --}}
                                                        <td >
                                                            <input type="hidden" name="idKeuanganHonorPegawai_{{$numpegawai}}" value="{{$keuangan->id}}">
                                                            <select disabled class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="akunPegawai_{{$numpegawai}}">
                                                                @foreach ($akuns as $akun)
                                                                @if ($keuangan->akun_x_rkakl == $akun->idAkun)
                                                                <option value="{{$akun->idAkun}}" selected>[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                                @endif
                                                                <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>


                                                        {{-- SATUAN HONORARIUM PEGAWAI --}}
                                                        @if ($perangkat->nama_fasilitas == 'Panitia')

                                                            <td style="min-width: 200px">
                                                                <input readonly id="input_satuan_honorarium_panitia_{{$numpegawai}}"
                                                                    type="number"
                                                                    class="form-control input-satuan-honorarium-panitia prevent-submit"
                                                                    min="0"
                                                                    name="satuan_honorarium_{{$numpegawai}}"
                                                                    value="{{$pegawai->honorarium}}">
                                                            </td>
                                                        @else
                                                            <td style="min-width: 200px">
                                                                <input readonly id="input_satuan_honorarium_{{$numpegawai}}"
                                                                    type="number"
                                                                    class="form-control input-satuan-honorarium prevent-submit"
                                                                    min="0"
                                                                    name="satuan_honorarium_{{$numpegawai}}"
                                                                    value="{{$pegawai->honorarium}}">
                                                            </td>
                                                        @endif

                                                        {{-- <td>
                                                            <select class="form-select mySelect" aria-label="Default select example" style="min-width: 300px" name="sbmPegawai_{{$numpegawai}}">
                                                            @foreach ($sbms as $sbm)
                                                            @if ($keuangan->ref_sbm == $sbm->id)
                                                            <option value="{{$sbm->id}}" data-label="{{$sbm->biaya}}" selected>[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                                                            @endif
                                                            <option value="{{$sbm->id}}" data-label="{{$sbm->biaya}}" >[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                                                            @endforeach
                                                            </select>
                                                        </td> --}}

                                                        {{-- JUMLAH HONORARIUM PEGAWAI --}}
                                                        <td style="min-width: 200px">
                                                            <input readonly id="input_jumlah_honorarium_{{$numpegawai}}"
                                                                type="number"
                                                                class="form-control input-jumlah-honorarium prevent-submit"
                                                                min="0"
                                                                name="jumlah_honorarium_{{$numpegawai}}"
                                                                value="{{$pegawai->jumlah_honorarium}}"
                                                                readonly>
                                                        </td>

                                                        {{-- PPH21 --}}
                                                        <td style="min-width: 120px">
                                                            <div class="input-group mb-3">
                                                                <input readonly id="input_pph_{{$numpegawai}}"
                                                                    type="number"
                                                                    class="form-control input-pph pajak prevent-submit"
                                                                    min="0"
                                                                    name="pph_{{$numpegawai}}"
                                                                    step="0.1"
                                                                    value=""
                                                                    data-perangkat="{{ $perangkat->nama_fasilitas }}"
                                                                    data-status="{{ $pegawai->statusPegawai }}"
                                                                    data-golongan="{{ $pegawai->golongan }}"
                                                                >
                                                                <span class="input-group-text" id="basic-addon2">%</span>
                                                            </div>
                                                        </td>

                                                        {{-- NILAI PPH PEGAWAI --}}
                                                        <td style="min-width: 200px">
                                                            <input id="input_nilai_pph_{{$numpegawai}}"
                                                                type="number"
                                                                class="form-control input-nilai-pph prevent-submit"
                                                                min="0"
                                                                name="nilai_pph_{{$numpegawai}}"
                                                                value=""
                                                                readonly>
                                                        </td>

                                                        {{-- NOMINAL HONORARIUM PEGAWAI --}}
                                                        <td style="min-width: 200px">
                                                            <input id="input_nominal_honorarium_{{$numpegawai}}"
                                                                type="number"
                                                                class="form-control input-nominal-honorarium prevent-submit"
                                                                min="0"
                                                                name="nominal_honorarium_{{$numpegawai}}"
                                                                value=""
                                                                readonly>
                                                        </td>
                                                    @endif
                                                    @if ($keuangan->perangkat_acara == $pegawai->idPerangkatAcara && $keuangan->kode == 'harian')
                                                        {{-- AKUN 2 PANITIA --}}
                                                            <input type="hidden" name="kode_{{$numpegawai}}" value="{{$info->jenis_program}}">
                                                        <td >
                                                            <input type="hidden" name="idKeuanganHarianPegawai_{{$numpegawai}}" value="{{$keuangan->id}}">
                                                            <select disabled class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="akunHarianPegawai_{{$numpegawai}}">
                                                                @foreach ($akuns as $akun)
                                                                    @if ($keuangan->akun_x_rkakl == $akun->idAkun)
                                                                        <option value="{{$akun->idAkun}}" selected>[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                                    @endif
                                                                        <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        {{-- HARIAN PEGAWAI --}}
                                                        <td style="min-width: 200px">
                                                            <input readonly type="number" id="harianPegawai_{{$numpegawai}}" class="form-control harian-pegawai  prevent-submit" min="0" name="harianPegawai_{{$numpegawai}}" value="{{$keuangan->uang_harian}}">
                                                        </td>

                                                        {{-- HARIAN LAINNYA PEGAWAI --}}
                                                        <td style="min-width: 200px">
                                                            <input readonly type="number" id="hariandayPegawai_{{$numpegawai}}" class="form-control  harianday-pegawai prevent-submit" min="0" name="hariandayPegawai_{{$numpegawai}}" value="{{$keuangan->uang_harian_fullday}}">
                                                        </td>

                                                        {{-- REPRESENTASI PEGAWAI --}}
                                                        <td style="min-width: 200px">
                                                            <input readonly type="number" id="representasiPegawai_{{$numpegawai}}" class="form-control representasi-pegawai  prevent-submit" min="0" name="representasiPegawai_{{$numpegawai}}" value="{{$keuangan->uang_harian_representasi}}">
                                                        </td>

                                                        {{-- NOMINAL PERJADIN PEGAWAI --}}
                                                        <td style="min-width: 200px">
                                                            <input  type="number" id="nominalPerjadinPegawai_{{$numpegawai}}" class="form-control nominal-perjadin-pegawai  prevent-submit" min="0" name="nominalPerjadinPegawai_{{$numpegawai}}" value="{{$keuangan->nominal_perjadin}}" readonly>
                                                        </td>
                                                    @endif

                                                @endforeach

                                                            {{-- TOTAL DIBAYAR PEGAWAI --}}
                                                            <td style="min-width: 200px">
                                                                <input type="number" id="totalDibayar_pegawai_{{$numpegawai}}" class="totalSeluruh_{{$numperangkat}} form-control" name="totalPegawai_{{$numpegawai}}" value="{{$keuangan->jumlah_honorarium + $keuangan->nominal_perjadin}}" readonly>
                                                            </td>

                                                            {{-- NO REKENING PEGAWAI --}}
                                                            <td style="min-width: 200px">
                                                                <div style="display: flex; gap: 5px;">
                                                                    <input type="text"  class="form-control  prevent-submit" min="0" name="bank_{{$numpegawai}}" value="{{$pegawai->bank}}"  style="flex: 1;" readonly>
                                                                    <input type="text" class="form-control  prevent-submit" min="0" name="no_rekening_{{$numpegawai}}" value="{{$pegawai->no_rekening}}"  style="flex: 2;" readonly>
                                                                </div>
                                                            </td>

                                                            {{-- TGL BAYAR PEGAWAI --}}
                                                            <td style="min-width: 200px">
                                                            <input readonly type="datetime-local" class="result form-control" name="tglbayarPegawai_{{$numpegawai}}" value="{{$keuangan->tgl_bayar}}">
                                                            </td>

                                                            {{-- STATUS PEMBAYARAN PEGAWAI --}}
                                                            <td style="min-width: 150px">
                                                                <input type="hidden" id="idPegawai_{{$numpegawai}}" name="idPegawai_{{$numpegawai}}" value="{{$pegawai->idPegawai}}">
                                                                <select disabled class="form-select" aria-label="Default select example" name="statusPembiyaanPegawai_{{$numpegawai}}">
                                                                    @php
                                                                        $statusOptions = [
                                                                            'Belum Dibayarkan',
                                                                            'Tidak Dibayarkan',
                                                                            'Sudah Dibayarkan',
                                                                        ];
                                                                        $selectedStatus = $keuangan->status ?? 'Belum Dibayarkan'; // Default jika null
                                                                    @endphp

                                                                    @foreach ($statusOptions as $option)
                                                                        <option value="{{ $option }}" {{ $selectedStatus === $option ? 'selected' : '' }}>
                                                                            {{ $option }}
                                                                        </option>
                                                                    @endforeach
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
                                                @if ($nonpegawai->fasilitas_id == $perangkat->id )
                                                    <tr>
                                                        <tr>
                                                            <input id="nama_perangkat_non_{{$numnonpegawai}}"
                                                            name="nama_perangkat_non_{{$numnonpegawai}}"
                                                            type="hidden"
                                                            value="{{$perangkat->nama_fasilitas}}">
                                                        <input id="perangkat_non_{{$numnonpegawai}}"
                                                            name="perangkat_non_{{$numnonpegawai}}"
                                                            type="hidden"
                                                            value="{{$numperangkat}}">

                                                        {{-- NAMA PEGAWAI --}}
                                                        <td style="min-width: 150px">{{$nonpegawai->nama_lengkap}}
                                                            <input type="hidden" name="idPerangkatNonPegawai_{{$numnonpegawai}}" value="{{$nonpegawai->idPerangkatAcara}}">
                                                            <input type="hidden" name="namaNonPegawai_{{$numnonpegawai}}" value="{{$nonpegawai->nama_lengkap}}">
                                                        </td>

                                                        {{-- PANGKAT PEGAWAI --}}
                                                        <td style="min-width: 120px" class="text-center">{{$nonpegawai->pangkat}} | {{$nonpegawai->golongan}}</td>

                                                        {{-- SEBAGAI PEGAWAI --}}
                                                        <td style="min-width: 120px" class="text-center">{{$nonpegawai->sebagai}}
                                                            <input type="hidden" name="sebagaiNonPegawai_{{$numnonpegawai}}" value="{{$nonpegawai->sebagai}}">
                                                        </td>

                                                        <input id="satuan_non_{{$numnonpegawai}}"
                                                        type="hidden"
                                                        value="{{$nonpegawai->satuan}}">
                                                        <td style="min-width: 50px" class="text-center">{{$nonpegawai->satuan}}-{{$nonpegawai->detail_satuan}}</td>
                                                        @foreach ($keuangans as $keuangan)
                                                        @if ($keuangan->perangkat_acara == $nonpegawai->idPerangkatAcara && $keuangan->kode == 'honor')
                                                        {{-- NO AKUN NON PEGAWAI --}}
                                                            <input type="hidden" name="idKeuanganHonorNonPegawai_{{$numnonpegawai}}" value="{{$keuangan->id}}">
                                                            <td>
                                                                <select disabled class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px;" name="akunNonPegawai_{{$numnonpegawai}}">
                                                                    @foreach ($akuns as $akun)
                                                                        @if ($keuangan->akun_x_rkakl == $akun->idAkun)
                                                                            <option value="{{$akun->idAkun}}" selected>[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                                        @endif
                                                                            <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>

                                                            {{-- SATUAN HONORARIUM NON PEGAWAI --}}
                                                            @if ($perangkat->nama_fasilitas == 'Panitia')

                                                                <td style="min-width: 200px">
                                                                    <input readonly id="input_satuan_honorarium_panitia_non_{{$numnonpegawai}}"
                                                                            type="number"
                                                                            class="form-control input-satuan-honorarium-panitia-non prevent-submit"
                                                                            min="0"
                                                                            name="satuan_honorarium_non_{{$numnonpegawai}}"
                                                                            value="{{$nonpegawai->honorarium}}">
                                                                </td>
                                                            @else
                                                                <td style="min-width: 200px">
                                                                    <input readonly id="input_satuan_honorarium_non_{{$numnonpegawai}}"
                                                                            type="number"
                                                                            class="form-control input-satuan-honorarium-non prevent-submit"
                                                                            min="0"
                                                                            name="satuan_honorarium_non_{{$numnonpegawai}}"
                                                                            value="{{$nonpegawai->honorarium}}">
                                                                </td>
                                                            @endif

                                                            {{-- JUMLAH HONORARIUM NON PEGAWAI --}}
                                                            <td style="min-width: 200px">
                                                            <input id="input_jumlah_honorarium_non_{{$numnonpegawai}}"
                                                                    type="number"
                                                                    class="form-control input-jumlah-honorarium-non prevent-submit"
                                                                    min="0"
                                                                    name="jumlah_honorarium_non_{{$numnonpegawai}}"
                                                                    value="{{$nonpegawai->jumlah_honorarium}}"
                                                                    readonly>
                                                            </td>


                                                            {{-- PAJAK NON PEGAWAI --}}
                                                            <td style="min-width: 120px">
                                                                <div class="input-group mb-3">
                                                                    <input readonly id="input_pph_non_{{$numnonpegawai}}"
                                                                        type="number"
                                                                        step="0.1"
                                                                        class="form-control input-pph-non pajak prevent-submit"
                                                                        min="0"
                                                                        name="pph_non_{{$numnonpegawai}}"
                                                                        value=""
                                                                        data-perangkat="{{ $perangkat->nama_fasilitas }}"
                                                                        data-status="{{ $nonpegawai->statusNonPegawai }}"
                                                                        data-golongan="{{ $nonpegawai->golongan }}"
                                                                    >
                                                                    <span class="input-group-text" id="basic-addon2">%</span>
                                                                </div>
                                                            </td>

                                                            {{-- NILAI PPH NON PEGAWAI --}}
                                                            <td style="min-width: 200px">
                                                            <input readonly id="input_nilai_pph_non_{{$numnonpegawai}}"
                                                                    type="number"
                                                                    class="form-control input-nilai-pph-non prevent-submit"
                                                                    min="0"
                                                                    name="nilai_pph_non_{{$numnonpegawai}}"
                                                                    value=""
                                                                    readonly>
                                                            </td>

                                                            {{-- NOMINAL HONORARIUM NON PEGAWAI --}}
                                                            <td style="min-width: 200px">
                                                            <input id="input_nominal_honorarium_non_{{$numnonpegawai}}"
                                                                    type="number"
                                                                    class="form-control input-nominal-honorarium-non prevent-submit"
                                                                    min="0"
                                                                    name="nominal_honorarium_non_{{$numnonpegawai}}"
                                                                    value=""
                                                                    readonly>
                                                            </td>

                                                        @endif

                                                        @if ($keuangan->perangkat_acara == $nonpegawai->idPerangkatAcara && $keuangan->kode == 'harian')
                                                            {{-- NO AKUN 2 NON PEGAWAI --}}
                                                            <td style="min-width: 200px">
                                                                <input type="hidden" name="idKeuanganHarianNonPegawai_{{$numnonpegawai}}" value="{{$keuangan->id}}">
                                                                <select disabled class="js-example-basic-single-3 form-select akun-dropdown" aria-label="Default select example" style="min-width: 300px" name="akunHarianNonPegawai_{{$numnonpegawai}}">
                                                                    @foreach ($akuns as $akun)
                                                                        @if ($keuangan->akun_x_rkakl == $akun->idAkun)
                                                                            <option value="{{$akun->idAkun}}" selected>[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                                        @endif
                                                                            <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            {{-- UANG HARIAN NON PEGAWAI --}}
                                                            <td style="min-width: 200px">
                                                                <input readonly type="number" id="harianNonPegawai_{{$numnonpegawai}}" class="form-control harian-nonpegawai prevent-submit" min="0" name="harianNonPegawai_{{$numnonpegawai}}" value="{{$keuangan->uang_harian}}">
                                                            </td>

                                                            {{-- UANG HARIAN LAINNYA NON PEGAWAI --}}
                                                            <td style="min-width: 200px">
                                                                <input readonly type="number" id="hariandayNonPegawai_{{$numnonpegawai}}" class="form-control harianday-nonpegawai prevent-submit" min="0" name="hariandayNonPegawai_{{$numnonpegawai}}" value="{{$keuangan->uang_harian_fullday}}">
                                                            </td>

                                                            {{-- UANG REPRESENTASI NON PEGAWAI --}}
                                                            <td style="min-width: 200px">
                                                                <input readonly type="number" id="representasiNonPegawai_{{$numnonpegawai}}" class="form-control representasi-nonpegawai prevent-submit" min="0" name="representasiNonPegawai_{{$numnonpegawai}}" value="{{$keuangan->uang_harian_representasi}}">
                                                            </td>

                                                            {{-- NOMINAL PERJADIN NON PEGAWAI --}}
                                                            <td style="min-width: 200px">
                                                                <input  type="number" id="nominalPerjadinNonPegawai_{{$numnonpegawai}}" class="form-control nominal-perjadin-non-pegawai  prevent-submit" min="0" name="nominalPerjadinNonPegawai_{{$numnonpegawai}}" value="{{$keuangan->nominal_perjadin}}" readonly>
                                                            </td>
                                                        @endif

                                                        @endforeach
                                                        {{-- TOTAL DIBAYAR NON PEGAWAI --}}
                                                        <td style="min-width: 200px">
                                                            <input type="number" class="totalSeluruh_{{$numperangkat}} form-control" name="totalNonPegawai_{{$numnonpegawai}}" value="{{$keuangan->jumlah_honorarium + $keuangan->nominal_perjadin}}" readonly>
                                                        </td>

                                                        {{-- NO REKENING NON PEGAWAI --}}
                                                        <td style="min-width: 200px">
                                                            <div style="display: flex; gap: 5px;">
                                                                <input type="text" class="form-control  prevent-submit" min="0" name="bank_non_{{$numnonpegawai}}" value="{{$nonpegawai->bank}}"  style="flex: 1;" readonly>
                                                                <input type="text" class="form-control  prevent-submit" min="0" name="no_rekening_non_{{$numnonpegawai}}" value="{{$nonpegawai->no_rekening}}"  style="flex: 2;" readonly>
                                                            </div>
                                                        </td>

                                                        {{-- TGL BAYAR NON PEGAWAI --}}
                                                        <td style="min-width: 200px">
                                                            <input  readonly type="datetime-local" class="result form-control" name="tglbayarNonPegawai_{{$numnonpegawai}}" value="{{$keuangan->tgl_bayar}}">
                                                        </td>

                                                        {{-- STATUS PEMBAYARAN NON PEGAWAI --}}
                                                        <td style="min-width: 150px">
                                                            <input type="hidden" id="idNonPegawai_{{$numnonpegawai}}" name="idNonPegawai_{{$numnonpegawai}}" value="{{$nonpegawai->idNonPegawai}}">
                                                            <select disabled class="form-select" aria-label="Default select example" name="statusPembayaranNonPegawai_{{$numnonpegawai}}">
                                                                @php
                                                                    $statusOptions = [
                                                                        'Belum Dibayarkan',
                                                                        'Tidak Dibayarkan',
                                                                        'Sudah Dibayarkan',
                                                                    ];
                                                                    $selectedStatus = $keuangan->status ?? 'Belum Dibayarkan'; // Default jika null
                                                                @endphp

                                                                @foreach ($statusOptions as $option)
                                                                    <option value="{{ $option }}" {{ $selectedStatus === $option ? 'selected' : '' }}>
                                                                        {{ $option }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
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
                                                @if ($info->jenis_program != 'Penugasan')
                                                    @if ($perangkat->nama_fasilitas == 'Panitia' || $perangkat->nama_fasilitas == 'Supir')
                                                    <td colspan="15" class="fw-bold  text-end">Sub Total</td>
                                                    @elseif ($perangkat->nama_fasilitas == 'Narasumber' || $perangkat->nama_fasilitas == 'Moderator')
                                                    <td colspan="10" class="fw-bold  text-end">Sub Total</td>
                                                    @endif
                                                @else
                                                    @if ($perangkat->nama_fasilitas == 'Panitia' || $perangkat->nama_fasilitas == 'Supir')
                                                        <td colspan="9" class="fw-bold  text-end">Sub Total</td>
                                                    @endif
                                                @endif
                                                <td><input type="number" class="subTotalPerangkat_{{$numperangkat}} total-ok form-control" value="" readonly></td>
                                            </tr>

                                            </tfoot>
                                        </table>
                                        </div>
                                    </div>
                                    @php
                                        $numperangkat++;
                                    @endphp
                            @endif
                    @endforeach
                    <input type="hidden" name="numPegawai" value="{{$numpegawai}}">
                    <input type="hidden" name="numNonPegawai" value="{{$numnonpegawai}}">

                  @else
                  @foreach ($perangkats as $perangkat)
                  @if ($perangkat->nama_fasilitas == 'Supir')
                      <div class="col-md-12 mb-3">
                          <div class="table-responsive">
                              <div class="d-flex justify-content-between">
                                  <h5 class="fw-bold">Informasi {{$perangkat->nama_fasilitas}}</h5>
                              </div>

                              <table id="calculationTable{{$numperangkat}}" name="{{$perangkat->nama_fasilitas}}" class="table table-bordered calculationTable" style="width: 100%">
                                  <thead>
                                      <tr class="text-center small">
                                              <th>Nama Lengkap</th>
                                              <th>Pangkat/Golongan</th>
                                              <th>Sebagai</th>
                                              <th>Detail</th>
                                              <th>No Akun</th>
                                              <th>Uang Harian</th>
                                              <th>Uang Harian Halfday/Fullday/Fullboard</th>
                                              {{-- <th>Uang Harian Fullboard</th> --}}
                                              <th>Uang Representasi</th>
                                              <th>Total dibayar</th>
                                              <th>No Rekening</th>
                                              <th>Tanggal Pembayaran</th>
                                              <th>Status Pembiayaan</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @if ($pegawais->isNotEmpty())
                                          @foreach ($pegawais as $pegawai)
                                              @if ($pegawai->fasilitas_id == $perangkat->id)
                                                  <tr>
                                                      <input id="nama_perangkat_{{$numpegawai}}"
                                                          name="nama_perangkat_{{$numpegawai}}"
                                                          type="hidden"
                                                          value="{{$perangkat->nama_fasilitas}}">
                                                      <input id="perangkat_{{$numpegawai}}"
                                                          name="perangkat_{{$numpegawai}}"
                                                          type="hidden"
                                                          value="{{$numperangkat}}">

                                                      {{-- NAMA PEGAWAI --}}
                                                      <td style="min-width: 150px">{{$pegawai->nama_lengkap}}
                                                          <input type="hidden" name="idPerangkatPegawai_{{$numpegawai}}" value="{{$pegawai->idPerangkatAcara}}">
                                                          <input type="hidden" name="namaPegawai_{{$numpegawai}}" value="{{$pegawai->nama_lengkap}}">
                                                      </td>

                                                      {{-- PANGKAT PEGAWAI --}}
                                                      <td class='text-center' style="min-width: 120px">{{$pegawai->pangkat}} | {{$pegawai->golongan}}</td>

                                                      {{-- SEBAGAI PEGAWAI --}}
                                                      <td class='text-center' style="min-width: 120px">{{$pegawai->sebagai}}
                                                          <input type="hidden" name="sebagaiPegawai_{{$numpegawai}}" value="{{$pegawai->sebagai}}">
                                                      </td>

                                                      {{-- DETAIL PEGAWAI --}}
                                                      <input id="satuan_{{$numpegawai}}"
                                                              type="hidden"
                                                              value="{{$pegawai->satuan}}">
                                                      <td class='text-center' style="min-width: 50px">{{$pegawai->satuan}}-{{$pegawai->detail_satuan}}</td>

                                                      @foreach ($keuangans as $keuangan)
                                                          @if ($keuangan->perangkat_acara == $pegawai->idPerangkatAcara  && $keuangan->operasional != NULL)
                                                              {{-- AKUN 2 PANITIA --}}
                                                              <td >
                                                                  <select disabled class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="akunHarianPegawai_{{$numpegawai}}">
                                                                      @foreach ($akuns as $akun)
                                                                          @if ($keuangan->akun_x_rkakl == $akun->idAkun)
                                                                              <option value="{{$akun->idAkun}}" selected>[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                                          @endif
                                                                              <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                                      @endforeach
                                                                  </select>
                                                              </td>

                                                              {{-- NOMINAL HONORARIUM PEGAWAI SUPIR HIDDEN--}}
                                                              <input id="input_nominal_honorarium_{{$numpegawai}}"
                                                                  type="hidden"
                                                                  class="form-control input-nominal-honorarium prevent-submit"
                                                                  min="0"
                                                                  name="nominal_honorarium_{{$numpegawai}}"
                                                                  value="0">

                                                                  {{-- PAJAK PEGAWAI SUPIR HIDDEN--}}
                                                                  <input id="input_pph_{{$numpegawai}}"
                                                                      type="hidden"
                                                                      class="form-control input-pph pajak prevent-submit"
                                                                      min="0"
                                                                      name="pph_{{$numpegawai}}"
                                                                      step="0.1"
                                                                      value=""
                                                                      data-perangkat="{{ $perangkat->nama_fasilitas }}"
                                                                      data-status="{{ $pegawai->statusPegawai }}"
                                                                      data-golongan="{{ $pegawai->golongan }}"
                                                                  >


                                                              {{-- HARIAN PEGAWAI SUPIR --}}
                                                              <td style="min-width: 200px">
                                                                  <input readonly type="number" id="harianPegawai_{{$numpegawai}}" class="form-control harian-pegawai  prevent-submit" min="0" name="harianPegawai_{{$numpegawai}}" value="{{$keuangan->uang_harian}}">
                                                              </td>

                                                              {{-- HARIAN LAINNYA PEGAWAI SUPIR --}}
                                                              <td style="min-width: 200px">
                                                                  <input readonly type="number" id="hariandayPegawai_{{$numpegawai}}" class="form-control  harianday-pegawai prevent-submit" min="0" name="hariandayPegawai_{{$numpegawai}}" value="{{$keuangan->uang_harian_fullday}}">
                                                              </td>

                                                              {{-- REPRESENTASI PEGAWAI SUPIR --}}
                                                              <td style="min-width: 200px">
                                                                  <input  readonly type="number" id="representasiPegawai_{{$numpegawai}}" class="form-control representasi-pegawai  prevent-submit" min="0" name="representasiPegawai_{{$numpegawai}}" value="{{$keuangan->uang_harian_representasi}}">
                                                              </td>

                                                              {{-- TOTAL DIBAYAR PEGAWAI --}}
                                                              <td style="min-width: 200px">
                                                                  <input  type="number" id="totalDibayar_pegawai_{{$numpegawai}}" class="totalSeluruh_{{$numperangkat}} form-control" name="totalPegawai_{{$numpegawai}}" value="{{$keuangan->jumlah_honorarium + $keuangan->nominal_perjadin}}" readonly>
                                                              </td>

                                                              {{-- NO REKENING PEGAWAI --}}
                                                              <td style="min-width: 200px">
                                                                  <div style="display: flex; gap: 5px;">
                                                                      <input type="text" class="form-control  prevent-submit" min="0" name="bank_{{$numpegawai}}" value="{{$pegawai->bank}}"  style="flex: 1;" readonly>
                                                                      <input type="text" class="form-control  prevent-submit" min="0" name="no_rekening_{{$numpegawai}}" value="{{$pegawai->no_rekening}}"  style="flex: 2;" readonly>
                                                                  </div>
                                                              </td>

                                                              {{-- TGL BAYAR PEGAWAI --}}
                                                              <td style="min-width: 200px">
                                                              <input readonly type="datetime-local" class="result form-control" name="tglbayarPegawai_{{$numpegawai}}" value="{{$keuangan->tgl_bayar}}">
                                                              </td>

                                                              {{-- STATUS PEMBAYARAN PEGAWAI --}}
                                                              <td style="min-width: 150px">
                                                                  <input type="hidden" id="idPegawai_{{$numpegawai}}" name="idPegawai_{{$numpegawai}}" value="{{$pegawai->idPegawai}}">
                                                                  <select disabled class="form-select" aria-label="Default select example" name="statusPembiyaanPegawai_{{$numpegawai}}">
                                                                      @php
                                                                          $statusOptions = [
                                                                              'Belum Dibayarkan',
                                                                              'Tidak Dibayarkan',
                                                                              'Sudah Dibayarkan',
                                                                          ];
                                                                          $selectedStatus = $keuangan->status ?? 'Belum Dibayarkan'; // Default jika null
                                                                      @endphp

                                                                      @foreach ($statusOptions as $option)
                                                                          <option value="{{ $option }}" {{ $selectedStatus === $option ? 'selected' : '' }}>
                                                                              {{ $option }}
                                                                          </option>
                                                                      @endforeach
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

                                  </tbody>
                                  <tfoot>
                                      <tr>

                                          <td colspan="8" class="fw-bold  text-end">Sub Total</td>

                                          <td><input type="number" class="subTotalPerangkat_{{$numperangkat}} total-ok form-control" value="" readonly></td>
                                      </tr>

                                      </tfoot>
                              </table>

                          </div>
                      </div>
                  @else
                      <div class="col-md-12 mb-3">
                          <div class="table-responsive">
                          <div class="d-flex justify-content-between">
                              <h5 class="fw-bold">Informasi {{$perangkat->nama_fasilitas}}</h5>

                          <!-- Tombol untuk Print to PDF yang akan diubah href-nya secara dinamis -->
                          <a id="printPdfLink" class="btn btn-success printPdfLink btn-sm mb-3" data-perangkat="{{ $perangkat->nama_fasilitas }}" href="#">Print to PDF</a>
                          </div>
                          <table id="calculationTable{{$numperangkat}}" name="{{$perangkat->nama_fasilitas}}" class="table table-bordered calculationTable" style="width: 100%">
                              <thead>

                              <tr class="text-center small">
                                      <th>Nama Lengkap</th>
                                      <th>Pangkat/Golongan</th>
                                      <th>Sebagai</th>
                                      <th>Detail</th>
                                      @if ($info->jenis_program != 'Penugasan')
                                          <th >No Akun</th>
                                          {{-- <th>SBM</th> --}}
                                          <th>Satuan Honorarium</th>
                                          <th>Jumlah Honorarium</th>
                                          <th>PPh21</th>
                                          <th>Nilai PPh</th>
                                          <th>Nominal Honorarium</th>
                                      @endif
                                      @if ($perangkat->nama_fasilitas == 'Panitia'|| $perangkat->nama_fasilitas == 'Supir')
                                          <th>No Akun</th>
                                          <th>Uang Harian</th>
                                          <th>Uang Harian Halfday/Fullday/Fullboard</th>
                                          {{-- <th>Uang Harian Fullboard</th> --}}
                                          <th>Uang Representasi</th>
                                          <th>Nominal Perjadin</th>
                                      @endif
                                      <th>Total dibayar</th>
                                      <th>No Rekening</th>
                                      <th>Tanggal Pembayaran</th>
                                      <th>Status Pembiayaan</th>
                              </tr>
                              </thead>
                              <tbody>
                              @if ($pegawais->isNotEmpty())
                                  @foreach ($pegawais as $pegawai)
                                      @if ($pegawai->fasilitas_id == $perangkat->id  )
                                      <tr>
                                          <input id="nama_perangkat_{{$numpegawai}}"
                                                  name="nama_perangkat_{{$numpegawai}}"
                                                  type="hidden"
                                                  value="{{$perangkat->nama_fasilitas}}">
                                              <input id="perangkat_{{$numpegawai}}"
                                                  name="perangkat_{{$numpegawai}}"
                                                  type="hidden"
                                                  value="{{$numperangkat}}">

                                                  {{-- NAMA PEGAWAI --}}
                                                  <td style="min-width: 150px">{{$pegawai->nama_lengkap}}
                                                      <input type="hidden" name="idPerangkatPegawai_{{$numpegawai}}" value="{{$pegawai->idPerangkatAcara}}">
                                                      <input type="hidden" name="namaPegawai_{{$numpegawai}}" value="{{$pegawai->nama_lengkap}}">
                                                  </td>

                                                  {{-- PANGKAT PEGAWAI --}}
                                                  <td class='text-center' style="min-width: 120px">{{$pegawai->pangkat}} | {{$pegawai->golongan}}</td>

                                                  {{-- SEBAGAI PEGAWAI --}}
                                                  <td class='text-center' style="min-width: 120px">{{$pegawai->sebagai}}
                                                      <input type="hidden" name="sebagaiPegawai_{{$numpegawai}}" value="{{$pegawai->sebagai}}">
                                                  </td>

                                          {{-- DETAIL PEGAWAI --}}
                                          <input id="satuan_{{$numpegawai}}"
                                                  type="hidden"
                                                  value="{{$pegawai->satuan}}">
                                      <td class='text-center' style="min-width: 50px">{{$pegawai->satuan}}-{{$pegawai->detail_satuan}}</td>

                                      @foreach ($keuangans as $keuangan)
                                          @if ($keuangan->perangkat_acara == $pegawai->idPerangkatAcara && $keuangan->kode == 'honor')
                                              {{-- NO AKUN PEGAWAI --}}
                                              <td >
                                                  <input type="hidden" name="idKeuanganHonorPegawai_{{$numpegawai}}" value="{{$keuangan->id}}">
                                                  <select disabled class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="akunPegawai_{{$numpegawai}}">
                                                      @foreach ($akuns as $akun)
                                                      @if ($keuangan->akun_x_rkakl == $akun->idAkun)
                                                      <option value="{{$akun->idAkun}}" selected>[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                      @endif
                                                      <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                      @endforeach
                                                  </select>
                                              </td>


                                              {{-- SATUAN HONORARIUM PEGAWAI --}}
                                              @if ($perangkat->nama_fasilitas == 'Panitia')

                                                  <td style="min-width: 200px">
                                                      <input readonly id="input_satuan_honorarium_panitia_{{$numpegawai}}"
                                                          type="number"
                                                          class="form-control input-satuan-honorarium-panitia prevent-submit"
                                                          min="0"
                                                          name="satuan_honorarium_{{$numpegawai}}"
                                                          value="{{$pegawai->honorarium}}">
                                                  </td>
                                              @else
                                                  <td style="min-width: 200px">
                                                      <input readonly id="input_satuan_honorarium_{{$numpegawai}}"
                                                          type="number"
                                                          class="form-control input-satuan-honorarium prevent-submit"
                                                          min="0"
                                                          name="satuan_honorarium_{{$numpegawai}}"
                                                          value="{{$pegawai->honorarium}}">
                                                  </td>
                                              @endif

                                              {{-- <td>
                                                  <select class="form-select mySelect" aria-label="Default select example" style="min-width: 300px" name="sbmPegawai_{{$numpegawai}}">
                                                  @foreach ($sbms as $sbm)
                                                  @if ($keuangan->ref_sbm == $sbm->id)
                                                  <option value="{{$sbm->id}}" data-label="{{$sbm->biaya}}" selected>[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                                                  @endif
                                                  <option value="{{$sbm->id}}" data-label="{{$sbm->biaya}}" >[{{$sbm->kode_sbm}} | {{$sbm->satuan}}] {{$sbm->uraian}}</option>
                                                  @endforeach
                                                  </select>
                                              </td> --}}

                                              {{-- JUMLAH HONORARIUM PEGAWAI --}}
                                              <td style="min-width: 200px">
                                                  <input readonly id="input_jumlah_honorarium_{{$numpegawai}}"
                                                      type="number"
                                                      class="form-control input-jumlah-honorarium prevent-submit"
                                                      min="0"
                                                      name="jumlah_honorarium_{{$numpegawai}}"
                                                      value="{{$pegawai->jumlah_honorarium}}"
                                                      readonly>
                                              </td>

                                              {{-- PPH21 --}}
                                              <td style="min-width: 120px">
                                                  <div class="input-group mb-3">
                                                      <input readonly id="input_pph_{{$numpegawai}}"
                                                          type="number"
                                                          class="form-control input-pph pajak prevent-submit"
                                                          min="0"
                                                          name="pph_{{$numpegawai}}"
                                                          step="0.1"
                                                          value=""
                                                          data-perangkat="{{ $perangkat->nama_fasilitas }}"
                                                          data-status="{{ $pegawai->statusPegawai }}"
                                                          data-golongan="{{ $pegawai->golongan }}"
                                                      >
                                                      <span class="input-group-text" id="basic-addon2">%</span>
                                                  </div>
                                              </td>

                                              {{-- NILAI PPH PEGAWAI --}}
                                              <td style="min-width: 200px">
                                                  <input id="input_nilai_pph_{{$numpegawai}}"
                                                      type="number"
                                                      class="form-control input-nilai-pph prevent-submit"
                                                      min="0"
                                                      name="nilai_pph_{{$numpegawai}}"
                                                      value=""
                                                      readonly>
                                              </td>

                                              {{-- NOMINAL HONORARIUM PEGAWAI --}}
                                              <td style="min-width: 200px">
                                                  <input id="input_nominal_honorarium_{{$numpegawai}}"
                                                      type="number"
                                                      class="form-control input-nominal-honorarium prevent-submit"
                                                      min="0"
                                                      name="nominal_honorarium_{{$numpegawai}}"
                                                      value=""
                                                      readonly>
                                              </td>
                                          @endif
                                          @if ($keuangan->perangkat_acara == $pegawai->idPerangkatAcara && $keuangan->kode == 'harian')
                                              {{-- AKUN 2 PANITIA --}}
                                                  <input type="hidden" name="kode_{{$numpegawai}}" value="{{$info->jenis_program}}">
                                              <td >
                                                  <input type="hidden" name="idKeuanganHarianPegawai_{{$numpegawai}}" value="{{$keuangan->id}}">
                                                  <select disabled class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px" name="akunHarianPegawai_{{$numpegawai}}">
                                                      @foreach ($akuns as $akun)
                                                          @if ($keuangan->akun_x_rkakl == $akun->idAkun)
                                                              <option value="{{$akun->idAkun}}" selected>[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                          @endif
                                                              <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                      @endforeach
                                                  </select>
                                              </td>

                                              {{-- HARIAN PEGAWAI --}}
                                              <td style="min-width: 200px">
                                                  <input readonly type="number" id="harianPegawai_{{$numpegawai}}" class="form-control harian-pegawai  prevent-submit" min="0" name="harianPegawai_{{$numpegawai}}" value="{{$keuangan->uang_harian}}">
                                              </td>

                                              {{-- HARIAN LAINNYA PEGAWAI --}}
                                              <td style="min-width: 200px">
                                                  <input readonly type="number" id="hariandayPegawai_{{$numpegawai}}" class="form-control  harianday-pegawai prevent-submit" min="0" name="hariandayPegawai_{{$numpegawai}}" value="{{$keuangan->uang_harian_fullday}}">
                                              </td>

                                              {{-- REPRESENTASI PEGAWAI --}}
                                              <td style="min-width: 200px">
                                                  <input readonly type="number" id="representasiPegawai_{{$numpegawai}}" class="form-control representasi-pegawai  prevent-submit" min="0" name="representasiPegawai_{{$numpegawai}}" value="{{$keuangan->uang_harian_representasi}}">
                                              </td>

                                              {{-- NOMINAL PERJADIN PEGAWAI --}}
                                              <td style="min-width: 200px">
                                                  <input  type="number" id="nominalPerjadinPegawai_{{$numpegawai}}" class="form-control nominal-perjadin-pegawai  prevent-submit" min="0" name="nominalPerjadinPegawai_{{$numpegawai}}" value="{{$keuangan->nominal_perjadin}}" readonly>
                                              </td>
                                          @endif

                                      @endforeach

                                                  {{-- TOTAL DIBAYAR PEGAWAI --}}
                                                  <td style="min-width: 200px">
                                                      <input type="number" id="totalDibayar_pegawai_{{$numpegawai}}" class="totalSeluruh_{{$numperangkat}} form-control" name="totalPegawai_{{$numpegawai}}" value="{{$keuangan->jumlah_honorarium + $keuangan->nominal_perjadin}}" readonly>
                                                  </td>

                                                  {{-- NO REKENING PEGAWAI --}}
                                                  <td style="min-width: 200px">
                                                      <div style="display: flex; gap: 5px;">
                                                          <input type="text"  class="form-control  prevent-submit" min="0" name="bank_{{$numpegawai}}" value="{{$pegawai->bank}}"  style="flex: 1;" readonly>
                                                          <input type="text" class="form-control  prevent-submit" min="0" name="no_rekening_{{$numpegawai}}" value="{{$pegawai->no_rekening}}"  style="flex: 2;" readonly>
                                                      </div>
                                                  </td>

                                                  {{-- TGL BAYAR PEGAWAI --}}
                                                  <td style="min-width: 200px">
                                                  <input readonly type="datetime-local" class="result form-control" name="tglbayarPegawai_{{$numpegawai}}" value="{{$keuangan->tgl_bayar}}">
                                                  </td>

                                                  {{-- STATUS PEMBAYARAN PEGAWAI --}}
                                                  <td style="min-width: 150px">
                                                      <input type="hidden" id="idPegawai_{{$numpegawai}}" name="idPegawai_{{$numpegawai}}" value="{{$pegawai->idPegawai}}">
                                                      <select disabled class="form-select" aria-label="Default select example" name="statusPembiyaanPegawai_{{$numpegawai}}">
                                                          @php
                                                              $statusOptions = [
                                                                  'Belum Dibayarkan',
                                                                  'Tidak Dibayarkan',
                                                                  'Sudah Dibayarkan',
                                                              ];
                                                              $selectedStatus = $keuangan->status ?? 'Belum Dibayarkan'; // Default jika null
                                                          @endphp

                                                          @foreach ($statusOptions as $option)
                                                              <option value="{{ $option }}" {{ $selectedStatus === $option ? 'selected' : '' }}>
                                                                  {{ $option }}
                                                              </option>
                                                          @endforeach
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
                                      @if ($nonpegawai->fasilitas_id == $perangkat->id )
                                          <tr>
                                              <tr>
                                                  <input id="nama_perangkat_non_{{$numnonpegawai}}"
                                                  name="nama_perangkat_non_{{$numnonpegawai}}"
                                                  type="hidden"
                                                  value="{{$perangkat->nama_fasilitas}}">
                                              <input id="perangkat_non_{{$numnonpegawai}}"
                                                  name="perangkat_non_{{$numnonpegawai}}"
                                                  type="hidden"
                                                  value="{{$numperangkat}}">

                                              {{-- NAMA PEGAWAI --}}
                                              <td style="min-width: 150px">{{$nonpegawai->nama_lengkap}}
                                                  <input type="hidden" name="idPerangkatNonPegawai_{{$numnonpegawai}}" value="{{$nonpegawai->idPerangkatAcara}}">
                                                  <input type="hidden" name="namaNonPegawai_{{$numnonpegawai}}" value="{{$nonpegawai->nama_lengkap}}">
                                              </td>

                                              {{-- PANGKAT PEGAWAI --}}
                                              <td style="min-width: 120px" class="text-center">{{$nonpegawai->pangkat}} | {{$nonpegawai->golongan}}</td>

                                              {{-- SEBAGAI PEGAWAI --}}
                                              <td style="min-width: 120px" class="text-center">{{$nonpegawai->sebagai}}
                                                  <input type="hidden" name="sebagaiNonPegawai_{{$numnonpegawai}}" value="{{$nonpegawai->sebagai}}">
                                              </td>

                                              <input id="satuan_non_{{$numnonpegawai}}"
                                              type="hidden"
                                              value="{{$nonpegawai->satuan}}">
                                              <td style="min-width: 50px" class="text-center">{{$nonpegawai->satuan}}-{{$nonpegawai->detail_satuan}}</td>
                                              @foreach ($keuangans as $keuangan)
                                              @if ($keuangan->perangkat_acara == $nonpegawai->idPerangkatAcara && $keuangan->kode == 'honor')
                                              {{-- NO AKUN NON PEGAWAI --}}
                                                  <input type="hidden" name="idKeuanganHonorNonPegawai_{{$numnonpegawai}}" value="{{$keuangan->id}}">
                                                  <td>
                                                      <select disabled class="js-example-basic-single-3 form-select" aria-label="Default select example" style="min-width: 300px;" name="akunNonPegawai_{{$numnonpegawai}}">
                                                          @foreach ($akuns as $akun)
                                                              @if ($keuangan->akun_x_rkakl == $akun->idAkun)
                                                                  <option value="{{$akun->idAkun}}" selected>[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                              @endif
                                                                  <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                          @endforeach
                                                      </select>
                                                  </td>

                                                  {{-- SATUAN HONORARIUM NON PEGAWAI --}}
                                                  @if ($perangkat->nama_fasilitas == 'Panitia')

                                                      <td style="min-width: 200px">
                                                          <input readonly id="input_satuan_honorarium_panitia_non_{{$numnonpegawai}}"
                                                                  type="number"
                                                                  class="form-control input-satuan-honorarium-panitia-non prevent-submit"
                                                                  min="0"
                                                                  name="satuan_honorarium_non_{{$numnonpegawai}}"
                                                                  value="{{$nonpegawai->honorarium}}">
                                                      </td>
                                                  @else
                                                      <td style="min-width: 200px">
                                                          <input readonly id="input_satuan_honorarium_non_{{$numnonpegawai}}"
                                                                  type="number"
                                                                  class="form-control input-satuan-honorarium-non prevent-submit"
                                                                  min="0"
                                                                  name="satuan_honorarium_non_{{$numnonpegawai}}"
                                                                  value="{{$nonpegawai->honorarium}}">
                                                      </td>
                                                  @endif

                                                  {{-- JUMLAH HONORARIUM NON PEGAWAI --}}
                                                  <td style="min-width: 200px">
                                                  <input id="input_jumlah_honorarium_non_{{$numnonpegawai}}"
                                                          type="number"
                                                          class="form-control input-jumlah-honorarium-non prevent-submit"
                                                          min="0"
                                                          name="jumlah_honorarium_non_{{$numnonpegawai}}"
                                                          value="{{$nonpegawai->jumlah_honorarium}}"
                                                          readonly>
                                                  </td>


                                                  {{-- PAJAK NON PEGAWAI --}}
                                                  <td style="min-width: 120px">
                                                      <div class="input-group mb-3">
                                                          <input readonly id="input_pph_non_{{$numnonpegawai}}"
                                                              type="number"
                                                              step="0.1"
                                                              class="form-control input-pph-non pajak prevent-submit"
                                                              min="0"
                                                              name="pph_non_{{$numnonpegawai}}"
                                                              value=""
                                                              data-perangkat="{{ $perangkat->nama_fasilitas }}"
                                                              data-status="{{ $nonpegawai->statusNonPegawai }}"
                                                              data-golongan="{{ $nonpegawai->golongan }}"
                                                          >
                                                          <span class="input-group-text" id="basic-addon2">%</span>
                                                      </div>
                                                  </td>

                                                  {{-- NILAI PPH NON PEGAWAI --}}
                                                  <td style="min-width: 200px">
                                                  <input readonly id="input_nilai_pph_non_{{$numnonpegawai}}"
                                                          type="number"
                                                          class="form-control input-nilai-pph-non prevent-submit"
                                                          min="0"
                                                          name="nilai_pph_non_{{$numnonpegawai}}"
                                                          value=""
                                                          readonly>
                                                  </td>

                                                  {{-- NOMINAL HONORARIUM NON PEGAWAI --}}
                                                  <td style="min-width: 200px">
                                                  <input id="input_nominal_honorarium_non_{{$numnonpegawai}}"
                                                          type="number"
                                                          class="form-control input-nominal-honorarium-non prevent-submit"
                                                          min="0"
                                                          name="nominal_honorarium_non_{{$numnonpegawai}}"
                                                          value=""
                                                          readonly>
                                                  </td>

                                              @endif

                                              @if ($keuangan->perangkat_acara == $nonpegawai->idPerangkatAcara && $keuangan->kode == 'harian')
                                                  {{-- NO AKUN 2 NON PEGAWAI --}}
                                                  <td style="min-width: 200px">
                                                      <input type="hidden" name="idKeuanganHarianNonPegawai_{{$numnonpegawai}}" value="{{$keuangan->id}}">
                                                      <select disabled class="js-example-basic-single-3 form-select akun-dropdown" aria-label="Default select example" style="min-width: 300px" name="akunHarianNonPegawai_{{$numnonpegawai}}">
                                                          @foreach ($akuns as $akun)
                                                              @if ($keuangan->akun_x_rkakl == $akun->idAkun)
                                                                  <option value="{{$akun->idAkun}}" selected>[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                              @endif
                                                                  <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                                          @endforeach
                                                      </select>
                                                  </td>
                                                  {{-- UANG HARIAN NON PEGAWAI --}}
                                                  <td style="min-width: 200px">
                                                      <input readonly type="number" id="harianNonPegawai_{{$numnonpegawai}}" class="form-control harian-nonpegawai prevent-submit" min="0" name="harianNonPegawai_{{$numnonpegawai}}" value="{{$keuangan->uang_harian}}">
                                                  </td>

                                                  {{-- UANG HARIAN LAINNYA NON PEGAWAI --}}
                                                  <td style="min-width: 200px">
                                                      <input readonly type="number" id="hariandayNonPegawai_{{$numnonpegawai}}" class="form-control harianday-nonpegawai prevent-submit" min="0" name="hariandayNonPegawai_{{$numnonpegawai}}" value="{{$keuangan->uang_harian_fullday}}">
                                                  </td>

                                                  {{-- UANG REPRESENTASI NON PEGAWAI --}}
                                                  <td style="min-width: 200px">
                                                      <input readonly type="number" id="representasiNonPegawai_{{$numnonpegawai}}" class="form-control representasi-nonpegawai prevent-submit" min="0" name="representasiNonPegawai_{{$numnonpegawai}}" value="{{$keuangan->uang_harian_representasi}}">
                                                  </td>

                                                  {{-- NOMINAL PERJADIN NON PEGAWAI --}}
                                                  <td style="min-width: 200px">
                                                      <input  type="number" id="nominalPerjadinNonPegawai_{{$numnonpegawai}}" class="form-control nominal-perjadin-non-pegawai  prevent-submit" min="0" name="nominalPerjadinNonPegawai_{{$numnonpegawai}}" value="{{$keuangan->nominal_perjadin}}" readonly>
                                                  </td>
                                              @endif

                                              @endforeach
                                              {{-- TOTAL DIBAYAR NON PEGAWAI --}}
                                              <td style="min-width: 200px">
                                                  <input type="number" class="totalSeluruh_{{$numperangkat}} form-control" name="totalNonPegawai_{{$numnonpegawai}}" value="{{$keuangan->jumlah_honorarium + $keuangan->nominal_perjadin}}" readonly>
                                              </td>

                                              {{-- NO REKENING NON PEGAWAI --}}
                                              <td style="min-width: 200px">
                                                  <div style="display: flex; gap: 5px;">
                                                      <input type="text" class="form-control  prevent-submit" min="0" name="bank_non_{{$numnonpegawai}}" value="{{$nonpegawai->bank}}"  style="flex: 1;" readonly>
                                                      <input type="text" class="form-control  prevent-submit" min="0" name="no_rekening_non_{{$numnonpegawai}}" value="{{$nonpegawai->no_rekening}}"  style="flex: 2;" readonly>
                                                  </div>
                                              </td>

                                              {{-- TGL BAYAR NON PEGAWAI --}}
                                              <td style="min-width: 200px">
                                                  <input  readonly type="datetime-local" class="result form-control" name="tglbayarNonPegawai_{{$numnonpegawai}}" value="{{$keuangan->tgl_bayar}}">
                                              </td>

                                              {{-- STATUS PEMBAYARAN NON PEGAWAI --}}
                                              <td style="min-width: 150px">
                                                  <input type="hidden" id="idNonPegawai_{{$numnonpegawai}}" name="idNonPegawai_{{$numnonpegawai}}" value="{{$nonpegawai->idNonPegawai}}">
                                                  <select disabled class="form-select" aria-label="Default select example" name="statusPembayaranNonPegawai_{{$numnonpegawai}}">
                                                      @php
                                                          $statusOptions = [
                                                              'Belum Dibayarkan',
                                                              'Tidak Dibayarkan',
                                                              'Sudah Dibayarkan',
                                                          ];
                                                          $selectedStatus = $keuangan->status ?? 'Belum Dibayarkan'; // Default jika null
                                                      @endphp

                                                      @foreach ($statusOptions as $option)
                                                          <option value="{{ $option }}" {{ $selectedStatus === $option ? 'selected' : '' }}>
                                                              {{ $option }}
                                                          </option>
                                                      @endforeach
                                                  </select>
                                              </td>
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
                                      @if ($info->jenis_program != 'Penugasan')
                                          @if ($perangkat->nama_fasilitas == 'Panitia' || $perangkat->nama_fasilitas == 'Supir')
                                          <td colspan="15" class="fw-bold  text-end">Sub Total</td>
                                          @elseif ($perangkat->nama_fasilitas == 'Narasumber' || $perangkat->nama_fasilitas == 'Moderator')
                                          <td colspan="10" class="fw-bold  text-end">Sub Total</td>
                                          @endif
                                      @else
                                          @if ($perangkat->nama_fasilitas == 'Panitia' || $perangkat->nama_fasilitas == 'Supir')
                                              <td colspan="9" class="fw-bold  text-end">Sub Total</td>
                                          @endif
                                      @endif
                                      <td><input type="number" class="subTotalPerangkat_{{$numperangkat}} total-ok form-control" value="" readonly></td>
                                  </tr>

                                  </tfoot>
                              </table>
                              </div>
                          </div>
                          @php
                              $numperangkat++;
                          @endphp
                  @endif
          @endforeach
          <input type="hidden" name="numPegawai" value="{{$numpegawai}}">
          <input type="hidden" name="numNonPegawai" value="{{$numnonpegawai}}">
                  @endif



                    {{-- @if ($operasionals->isNotEmpty())
                    <div class="col-md-12 mb-3">
                      <div class="table-responsive">
                        <div class="d-flex justify-content-between">
                            <div>
                              <h5 class="fw-bold">Informasi Mobilitas</h5>
                            </div>
                        </div>
                        <table id="calculationTable3" name="Mobilitas" class="table table-bordered calculationTable" style="width: 100%">
                          <thead>
                                <tr class="text-center small">
                                    <th>No</th>
                                    <th>Ket Mobilitas</th>
                                    <th>Jumlah</th>
                                    <th>Detail</th>
                                    <th>No Akun</th>
                                    <th>Nominal</th>
                                    @if (($info->is_acceptBend == 'approval-2'))
                                      <th>PPh21 [%]</th>
                                    @endif
                                    <th>Total</th>
                                    @if (($info->is_acceptBend == 'approval-2'))
                                      <th>Tanggal Bayar</th>
                                      <th>Status</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                              @php
                                  $numoperasional = 0;
                              @endphp
                              @foreach ($operasionals as $operasional)
                                <tr>
                                    <td class='text-center' style="min-width: 30px">{{$loop->iteration}} <input type="hidden" name="idOperasional_{{$numoperasional}}" value="{{$operasional->id}}"> </td>
                                    <td style="min-width: 300px">{{$operasional->nama}} (BBM + Tol)</td>
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
                                          <td style="min-width: 200px">
                                            <input type="number"
                                            id="nominal_operasional_{{$numoperasional}}"
                                            class="form-control nominal-operasional pevent-submit" min="0"
                                            name="nominalOperasional_{{$numoperasional}}" value="{{$keuanganoperasional->harga}}">
                                          </td>
                                          @if (($info->is_acceptBend == 'approval-2'))
                                            <td style="min-width: 100px">
                                              <div class="input-group mb-3">
                                                <input type="number"
                                                id="pajak_perasional_{{$numoperasional}}"
                                                step="0.1"
                                                class="form-control pajak-operasional prevent-submit" min="0"
                                                name="pajakOperasional_{{$numoperasional}}" value="{{$keuanganoperasional->persen_pajak}}">
                                                <span class="input-group-text" id="basic-addon2">%</span>
                                              </div>
                                          @endif
                                          <td style="min-width: 200px">
                                            <input type="number"
                                                idl="totalOperasional_{{$numoperasional}}"
                                                class="result form-control"
                                                name="totalOperasional_{{$numoperasional}}"
                                                value="{{$keuanganoperasional->jumlah_harga}}" readonly>
                                          </td>
                                          @if (($info->is_acceptBend == 'approval-2'))
                                            <td style="min-width: 200px">
                                              <input required type="datetime-local" class="result form-control" name="tglbayarOperasional_{{$numoperasional}}" value="{{$keuanganoperasional->tgl_bayar}}" >
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
                              @if (($info->is_acceptBend == 'approval-2'))
                                <tr>
                                  <td colspan="7" class="fw-bold text-end">Sub Total</td>
                                  <td><input type="number" class="subtotalOperasional form-control" readonly></td>
                                </tr>
                              @else
                                <tr>
                                  <td colspan="6" class="fw-bold text-end">Sub Total</td>
                                  <td><input type="number" class="subtotalOperasional form-control" readonly></td>
                                </tr>
                              @endif
                            </tfoot>
                        </table>
                      </div>
                    </div>
                    @endif --}}
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
                              <th>Pelaksana</th>
                              <th>Keterangan</th>
                              <th>Akun</th>
                              <th>Harga Satuan</th>
                              <th>Nominal Kebutuhan</th>
                              <th>Pajak</th>
                              <th>Nominal Pajak</th>
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
                              <td style="min-width: 150px">{{$kebutuhan->nama}}</td>
                              <input id="jumlah_frekuensi_{{$numkebutuhan}}"
                                    type="hidden"
                                    value="{{$kebutuhan->jumlah_frekuensi}}">

                              <td class='text-center' style="min-width: 50px">{{ number_format($kebutuhan->jumlah_frekuensi, 0, ',', '.') }}</td>
                              <td class='text-center' style="min-width: 100px">{{$kebutuhan->satuan}}</td>
                              <td class='text-center' style="min-width: 100px">{{$kebutuhan->tipe_pendanaan}}</td>
                              <td class='text-center' style="min-width: 100px">{{$kebutuhan->pelaksana}}</td>
                              <td class='text-center' style="min-width: 100px">{{$kebutuhan->ket}}</td>
                              <td>
                                <select class="readonly js-example-basic-single-3 form-select akun-dropdown" aria-label="Default select example" style="min-width: 300px" name="akunKebutuhan_{{$numkebutuhan}}" disabled>
                                  @foreach ($akuns as $akun)
                                  @if ($kebutuhan->akun_x_rkakl == $akun->idAkun)
                                  <option value="{{$akun->idAkun}}" selected>[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                  @endif
                                  <option value="{{$akun->idAkun}}">[{{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}] {{$akun->nama_sub_kegiatan}} - {{$akun->uraian}}</option>
                                  @endforeach
                                </select>
                              </td>
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
                            </tr>
                            @php
                            $numkebutuhan++;
                            @endphp

                            @endforeach
                            <input type="hidden" name="numKebutuhan" value="{{$numkebutuhan}}">
                          </tbody>
                          <tfoot>
                            <tr>
                              <td colspan="12" class="fw-bold text-end">Sub Total</td>
                              <td><input type="number" class="totalKebutuhan total-ok form-control" readonly></td>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
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
                        <a href="{{url('/kegiatan-bendahara/' . 'approval-1')}}" class="btn btn-dark">Kembali</a>
                  </div>
                  </form>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

  <script src="{{asset('assets/js/pdfselected.js')}}"></script>
  <script src="{{asset('public/assets/js/pdfselected.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>




<!-- Skrip JavaScript -->
<script>
    // document.getElementById('tolak_button').addEventListener('click', function() {
    //   // Ambil alasan revisi yang dimasukkan user
    //   var alasanTolak = document.getElementById('tolak').value;

    //   // Mengambil semua elemen form yang memiliki atribut name
    //   var formData = {alasanTolak: alasanTolak,};
    //   var formElements = document.querySelectorAll('[name]');

    //   formElements.forEach(function(element) {
    //     var name = element.name;
    //     var value = element.value;

    //     // Masukkan ke dalam object formData
    //     formData[name] = value;
    //   });

    //   // Tambahkan data alasanRevisi dan data tambahan lainnya ke dalam formData
    //   var dataTambahan = {
    //     alasanTolak: alasanTolak,
    //     additionalData: "Informasi tambahan lainnya"
    //   };

    //   // Gabungkan formData dan dataTambahan
    //   var finalData = {
    //     formData: formData,
    //     dataTambahan: dataTambahan
    //   };

    //   // Mengubah finalData menjadi format JSON untuk disimpan di hidden input
    //   document.getElementById('dataTambahan').value = JSON.stringify(formData);

    // });
  </script>


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
                form.attr('action', '/h_fasilitas_kegiatan_admin/' + kebutuhanId + '/bendahara');
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
    $(document).ready(function () {
        // Menghitung nilai nominalKebutuhan, nominalPajakKebutuhan, dan totalKebutuhan saat input satuanKebutuhan berubah
        $('.nominal-operasional').on('input', function () {
            const numoperasional = $(this).attr('id').split('_')[2]; // Mengambil nomor kebutuhan dari ID

            // Mengambil nilai dari input satuan kebutuhan dan jumlah frekuensi
            const nominaloperasional = parseFloat($('#nominal_operasional_' + numoperasional).val()) || 0;

            // Mengambil nilai pajak fasilitas
            const pajakOperasional = parseFloat($('#pajak_perasional_' + numoperasional).val()) || 0;

            // Menghitung nominal pajak kebutuhan
            const nominalPajakOperasional = (nominaloperasional * pajakOperasional) / 100;

            // Menghitung total kebutuhan
            const totalOperasional = nominaloperasional - nominalPajakOperasional;
            $('input[name="totalOperasional_' + numoperasional + '"]').val(totalOperasional.toFixed(0));

             // Update subtotal
             updateSubtotalOperasional();
        });

        // Menghitung nilai nominalPajakKebutuhan dan totalKebutuhan saat input pajak fasilitas berubah
        $('.pajak-operasional').on('input', function () {
            const numoperasional = $(this).attr('id').split('_')[2]; // Mengambil nomor kebutuhan dari ID

            // Mengambil nilai dari input satuan kebutuhan dan jumlah frekuensi
            const nominaloperasional = parseFloat($('#nominal_operasional_' + numoperasional).val()) || 0;

            // Mengambil nilai pajak fasilitas
            const pajakOperasional = parseFloat($('#pajak_perasional_' + numoperasional).val()) || 0;

            // Menghitung nominal pajak kebutuhan
            const nominalPajakOperasional = (nominaloperasional * pajakOperasional) / 100;

            // Menghitung total kebutuhan
            const totalOperasional = nominaloperasional - nominalPajakOperasional;
            $('input[name="totalOperasional_' + numoperasional + '"]').val(totalOperasional.toFixed(0));

             // Update subtotal
             updateSubtotalOperasional();
        });

        // Fungsi untuk menghitung subtotal
        function updateSubtotalOperasional() {
            let subtotal = 0;

            // Loop melalui semua input dengan nama yang sesuai pola
            $('input[name^="totalOperasional_"]').each(function () {
                const value = parseFloat($(this).val()) || 0;
                subtotal += value;
            });

            // Set nilai subtotal ke elemen input di tfoot
            $('.subtotalOperasional').val(subtotal.toFixed(0));
        }

        // Panggil updateSubtotal saat halaman pertama kali dimuat
         // Memastikan subtotal diperbarui saat input kehilangan fokus
        //  $('input').on('blur', function () {
        //     updateSubtotalOperasional();
        // });
    });
</script>


<script>
    $(document).ready(function() {


        function updateTotal(numpegawai) {
            // Ambil nilai dari setiap input
            const harianPegawai = parseFloat($('#harianPegawai_' + numpegawai).val()) || 0;
            const hariandayPegawai = parseFloat($('#hariandayPegawai_' + numpegawai).val()) || 0;
            const representasiPegawai = parseFloat($('#representasiPegawai_' + numpegawai).val()) || 0;
            const nominalHonorarium = parseFloat($('#input_nominal_honorarium_' + numpegawai).val()) || 0;
            const nominalPerjadinPegawai = parseFloat($('#nominalPerjadinPegawai_' + numpegawai).val()) || 0;
            const satuan = parseFloat($('#satuan_' + numpegawai).val()) || 1;

            // Hitung total dibayar
            const totalDibayar = (harianPegawai * satuan) + (hariandayPegawai * satuan) + (representasiPegawai * satuan) + nominalHonorarium;
            const nominalPerjadin = (harianPegawai * satuan) + (hariandayPegawai * satuan) + (representasiPegawai * satuan);
            const numperangkat = parseFloat($('#perangkat_' + numpegawai).val()) || 0;

            // Set nilai total ke input
            $('input[name="totalPegawai_' + numpegawai + '"]').val(totalDibayar.toFixed(0));
            $('input[name="nominalPerjadinPegawai_' + numpegawai + '"]').val(nominalPerjadin.toFixed(0));

            let subtotal = 0;

            // Loop melalui semua input dengan nama yang sesuai pola
            $('.totalSeluruh_'+numperangkat).each(function () {
                const value = parseFloat($(this).val()) || 0;
                subtotal += value;
            });

            // Set nilai subtotal ke elemen input di tfoot
            $('.subTotalPerangkat_'+numperangkat).val(subtotal.toFixed(0));
            let subtotalApv1 = 0;
            // Loop melalui semua input dengan nama yang sesuai pola
            $('.totalSeluruhApv1_'+numperangkat).each(function () {
                const valueApv1 = parseFloat($(this).val()) || 0;
                subtotalApv1 += valueApv1;
            });

            // Set nilai subtotal ke elemen input di tfoot
            $('.subTotalPerangkatApv1_'+numperangkat).val(subtotalApv1.toFixed(0));
            updateJsonDataJS();
        }

        $('.harian-pegawai').on('input', function() {
            const numpegawai = $(this).attr('id').split('_')[1]; // Ambil nomor pegawai dari ID
            updateTotal(numpegawai);
        });
        $('.harianday-pegawai').on('input', function() {
            const numpegawai = $(this).attr('id').split('_')[1]; // Ambil nomor pegawai dari ID
            updateTotal(numpegawai);
        });
        $('.representasi-pegawai').on('input', function() {
            const numpegawai = $(this).attr('id').split('_')[1]; // Ambil nomor pegawai dari ID
            updateTotal(numpegawai);
        });

        // Menghitung jumlah honorarium
        $('.input-satuan-honorarium').on('input', function() {
            const numpegawai = $(this).attr('id').split('_')[3]; // Mengambil nomor pegawai

            // Mengambil nilai satuan honorarium dan nilai satuan dari pegawai
            const satuanHonorarium = parseFloat($('#input_satuan_honorarium_' + numpegawai).val()) || 0;
            const satuan = parseFloat($('#satuan_' + numpegawai).val()) || 0;

            // Menghitung jumlah honorarium
            const jumlahHonorarium = satuanHonorarium * satuan;
            $('#input_jumlah_honorarium_' + numpegawai).val(jumlahHonorarium.toFixed(0));

            // Menghitung nilai PPH
            const pphValue = parseFloat($('#input_pph_' + numpegawai).val()) || 0; // Ambil nilai PPH yang diinputkan
            const nilaiPPH = (jumlahHonorarium * pphValue) / 100;
            $('#input_nilai_pph_' + numpegawai).val(nilaiPPH.toFixed(0));

            // Menghitung nominal honorarium
            const nominalHonorarium = jumlahHonorarium - nilaiPPH;
            $('#input_nominal_honorarium_' + numpegawai).val(nominalHonorarium.toFixed(0));
            updateTotal(numpegawai);
        });

        // Menghitung jumlah honorarium
        $('.input-satuan-honorarium-panitia').on('input', function() {
            const numpegawai = $(this).attr('id').split('_')[4]; // Mengambil nomor pegawai

            // Mengambil nilai satuan honorarium dan nilai satuan dari pegawai
            const satuanHonorarium = parseFloat($('#input_satuan_honorarium_panitia_' + numpegawai).val()) || 0;
            const satuan = parseFloat($('#satuan_' + numpegawai).val()) || 0;

            // Menghitung jumlah honorarium
            const jumlahHonorarium = satuanHonorarium * 1;
            $('#input_jumlah_honorarium_' + numpegawai).val(jumlahHonorarium.toFixed(0));

            // Menghitung nilai PPH
            const pphValue = parseFloat($('#input_pph_' + numpegawai).val()) || 0; // Ambil nilai PPH yang diinputkan
            const nilaiPPH = (jumlahHonorarium * pphValue) / 100;
            $('#input_nilai_pph_' + numpegawai).val(nilaiPPH.toFixed(0));

            // Menghitung nominal honorarium
            const nominalHonorarium = jumlahHonorarium - nilaiPPH;
            $('#input_nominal_honorarium_' + numpegawai).val(nominalHonorarium.toFixed(0));
            updateTotal(numpegawai);
        });

        // Menghitung nilai PPH ketika ada perubahan nilai pajak
        $('.input-pph').on('input', function() {
            const numpegawai = $(this).attr('id').split('_')[2]; // Dapatkan nomor pegawai

            // Ambil nilai jumlah honorarium
            const jumlahHonorarium = parseFloat($('#input_jumlah_honorarium_' + numpegawai).val()) || 0;

            // Ambil nilai PPH dari input
            const pphValue = parseFloat($(this).val()) || 0;

            // Hitung nilai PPH
            const nilaiPPH = (jumlahHonorarium * pphValue) / 100;
            $('#input_nilai_pph_' + numpegawai).val(nilaiPPH.toFixed(0));

            // Menghitung nominal honorarium
            const nominalHonorarium = jumlahHonorarium - nilaiPPH;
            $('#input_nominal_honorarium_' + numpegawai).val(nominalHonorarium.toFixed(0));
            updateTotal(numpegawai);
        });

        // Menghitung jumlah honorarium
        $('.input-satuan-supir').on('input', function() {
            const numpegawai = $(this).attr('id').split('_')[3]; // Mengambil nomor pegawai

            // Mengambil nilai satuan honorarium dan nilai satuan dari pegawai
            const satuanHonorarium = parseFloat($('#input_satuan_supir_' + numpegawai).val()) || 0;


            // Menghitung jumlah honorarium
            const jumlahHonorarium = satuanHonorarium;
            $('#input_jumlah_supir_' + numpegawai).val(jumlahHonorarium.toFixed(0));

            // Menghitung nilai PPH
            const pphValue = parseFloat($('#input_pph_' + numpegawai).val()) || 0; // Ambil nilai PPH yang diinputkan
            const nilaiPPH = (jumlahHonorarium * pphValue) / 100;
            $('#input_nilai_pph_' + numpegawai).val(nilaiPPH.toFixed(0));

            // Menghitung nominal honorarium
            const nominalHonorarium = jumlahHonorarium - nilaiPPH;
            $('#input_nominal_honorarium_' + numpegawai).val(nominalHonorarium.toFixed(0));
            updateTotal(numpegawai);
        });
    });

    $(document).ready(function() {
        function updateTotalNon(numnonpegawai) {
            // Ambil nilai dari setiap input
            const harianNonPegawai = parseFloat($('#harianNonPegawai_' + numnonpegawai).val()) || 0;
            const hariandayNonPegawai = parseFloat($('#hariandayNonPegawai_' + numnonpegawai).val()) || 0;
            const representasiNonPegawai = parseFloat($('#representasiNonPegawai_' + numnonpegawai).val()) || 0;
            const nominalNonHonorarium = parseFloat($('#input_nominal_honorarium_non_' + numnonpegawai).val()) || 0;
            const nominalPerjadinNonPegawai = parseFloat($('#nominalPerjadinNonPegawai_' + numnonpegawai).val()) || 0;
            const satuan = parseFloat($('#satuan_non_' + numnonpegawai).val()) || 0;

            // Hitung total dibayar
            const totalDibayar = (harianNonPegawai * satuan) + (hariandayNonPegawai * satuan) + (representasiNonPegawai * satuan) + nominalNonHonorarium;
            const nominalPerjadin = (harianNonPegawai * satuan) + (hariandayNonPegawai * satuan) + (representasiNonPegawai * satuan);
            const numperangkat = parseFloat($('#perangkat_non_' + numnonpegawai).val()) || 0;


            // Set nilai total ke input
            $('input[name="totalNonPegawai_' + numnonpegawai + '"]').val(totalDibayar.toFixed(0));
            $('input[name="nominalPerjadinNonPegawai_' + numnonpegawai + '"]').val(nominalPerjadin.toFixed(0));


            let subtotal = 0;

            // Loop melalui semua input dengan nama yang sesuai pola
            $('.totalSeluruh_'+numperangkat).each(function () {
                const value = parseFloat($(this).val()) || 0;
                subtotal += value;
            });

            // Set nilai subtotal ke elemen input di tfoot
            $('.subTotalPerangkat_'+numperangkat).val(subtotal.toFixed(0));

            let subtotalApv1 = 0;
            // Loop melalui semua input dengan nama yang sesuai pola
            $('.totalSeluruhApv1_'+numperangkat).each(function () {
                const valueApv1 = parseFloat($(this).val()) || 0;
                subtotalApv1 += valueApv1;
            });

            // Set nilai subtotal ke elemen input di tfoot
            $('.subTotalPerangkatApv1_'+numperangkat).val(subtotalApv1.toFixed(0));
            updateJsonDataJS();
        }

        $('.harian-nonpegawai').on('input', function() {
            const numnonpegawai = $(this).attr('id').split('_')[1]; // Ambil nomor pegawai dari ID
            updateTotalNon(numnonpegawai);
        });
        $('.harianday-nonpegawai').on('input', function() {
            const numnonpegawai = $(this).attr('id').split('_')[1]; // Ambil nomor pegawai dari ID
            updateTotalNon(numnonpegawai);
        });
        $('.representasi-nonpegawai').on('input', function() {
            const numnonpegawai = $(this).attr('id').split('_')[1]; // Ambil nomor pegawai dari ID
            updateTotalNon(numnonpegawai);
        });

        // Menghitung jumlah honorarium
        $('.input-satuan-honorarium-non').on('input', function() {
            const numnonpegawai = $(this).attr('id').split('_')[4]; // Mengambil nomor pegawai

            // Mengambil nilai satuan honorarium dan nilai satuan dari pegawai
            const satuanHonorarium = parseFloat($('#input_satuan_honorarium_non_' + numnonpegawai).val()) || 0;
            const satuan = parseFloat($('#satuan_non_' + numnonpegawai).val()) || 0;

            // Menghitung jumlah honorarium
            const jumlahHonorarium = satuanHonorarium * satuan;
            $('#input_jumlah_honorarium_non_' + numnonpegawai).val(jumlahHonorarium.toFixed(0));

            // Menghitung nilai PPH
            const pphValue = parseFloat($('#input_pph_non_' + numnonpegawai).val()) || 0; // Ambil nilai PPH yang diinputkan
            const nilaiPPH = (jumlahHonorarium * pphValue) / 100;
            $('#input_nilai_pph_non_' + numnonpegawai).val(nilaiPPH.toFixed(0));

            // Menghitung nominal honorarium
            const nominalHonorarium = jumlahHonorarium - nilaiPPH;
            $('#input_nominal_honorarium_non_' + numnonpegawai).val(nominalHonorarium.toFixed(0));
            updateTotalNon(numnonpegawai);
        });

        // Menghitung jumlah honorarium panitia
        $('.input-satuan-honorarium-panitia-non').on('input', function() {
            const numnonpegawai = $(this).attr('id').split('_')[5]; // Mengambil nomor pegawai

            // Mengambil nilai satuan honorarium dan nilai satuan dari pegawai
            const satuanHonorarium = parseFloat($('#input_satuan_honorarium_panitia_non_' + numnonpegawai).val()) || 0;
            const satuan = parseFloat($('#satuan_non_' + numnonpegawai).val()) || 0;

            // Menghitung jumlah honorarium
            const jumlahHonorarium = satuanHonorarium * 1;
            $('#input_jumlah_honorarium_non_' + numnonpegawai).val(jumlahHonorarium.toFixed(0));

            // Menghitung nilai PPH
            const pphValue = parseFloat($('#input_pph_non_' + numnonpegawai).val()) || 0; // Ambil nilai PPH yang diinputkan
            const nilaiPPH = (jumlahHonorarium * pphValue) / 100;
            $('#input_nilai_pph_non_' + numnonpegawai).val(nilaiPPH.toFixed(0));

            // Menghitung nominal honorarium
            const nominalHonorarium = jumlahHonorarium - nilaiPPH;
            $('#input_nominal_honorarium_non_' + numnonpegawai).val(nominalHonorarium.toFixed(0));
            updateTotalNon(numnonpegawai);
        });

        // Menghitung nilai PPH ketika ada perubahan nilai pajak
        $('.input-pph-non').on('input', function() {
            const numnonpegawai = $(this).attr('id').split('_')[3]; // Dapatkan nomor pegawai

            // Ambil nilai jumlah honorarium
            const jumlahHonorarium = parseFloat($('#input_jumlah_honorarium_non_' + numnonpegawai).val()) || 0;

            // Ambil nilai PPH dari input
            const pphValue = parseFloat($(this).val()) || 0;

            // Hitung nilai PPH
            const nilaiPPH = (jumlahHonorarium * pphValue) / 100;
            $('#input_nilai_pph_non_' + numnonpegawai).val(nilaiPPH.toFixed(0));

            // Menghitung nominal honorarium
            const nominalHonorarium = jumlahHonorarium - nilaiPPH;
            $('#input_nominal_honorarium_non_' + numnonpegawai).val(nominalHonorarium.toFixed(0));
            updateTotalNon(numnonpegawai);
        });
    });
</script>

<script>
    let jsonDataJS = {
        id_kegiatan: "{{ $info->id }}",
        judul: "{{ $info->nama_kegiatan }}",
        tanggal: "{{ $info->tgl_mulai }}",
        data_utama: []
    };

    function updateJsonDataJS() {
        jsonDataJS.data_utama = [];

        $('input[name^="idPegawai_"]').each(function() {
            let numpegawai = $(this).attr('id').split('_')[1]; // Mengambil num pegawai
            let perangkat = $('#nama_perangkat_' + numpegawai).val();
            let bank = $('input[name="bank_' + numpegawai + '"]').val() || '';  // Mengambil nilai bank
            let rekening = $('input[name="no_rekening_' + numpegawai + '"]').val() || '';  // Mengambil nilai rekening

            // let akun = $('select[name="akunPegawai_' + numpegawai + '"]').val() || '';  // Mengambil nilai rekening
            let akun;
            let kode = $('input[name="kode_' + numpegawai + '"]').val() || '';  // Mengambil nilai bank
            if (kode == 'Penugasan') {
                akun =$('select[name="akunHarianPegawai_' + numpegawai + '"]').val() || '';  // Mengambil nilai rekening
            } else {
                akun = $('select[name="akunPegawai_' + numpegawai + '"]').val() || '';  // Mengambil nilai rekening
            }

            // let akun = kode;

            // Jika bank atau rekening berisi tanda minus atau kosong, maka anggap kosong
            bank = (bank === '-' || bank === '') ? '' : bank;
            rekening = (rekening === '-' || rekening === '') ? '' : rekening;

            // Jika keduanya tidak kosong, gabungkan dengan ' - '. Jika salah satu kosong, kosongkan data_bank.
            let data_bank = (bank && rekening) ? bank + ' - ' + rekening : (bank || rekening);

            if (perangkat == 'Panitia') {
                jsonDataJS.data_utama.push({
                    perangkat: perangkat,
                    id: $('input[name="idPegawai_' + numpegawai + '"]').val(),
                    nama: $('input[name="namaPegawai_' + numpegawai + '"]').val(),
                    sebagai: $('input[name="sebagaiPegawai_' + numpegawai + '"]').val(),
                    jumlah_kegiatan: '1',
                    satuan_honorarium: $('input[name="satuan_honorarium_' + numpegawai + '"]').val() || 0,
                    jumlah_honorarium: $('input[name="jumlah_honorarium_' + numpegawai + '"]').val() || 0,
                    pph: $('input[name="nilai_pph_' + numpegawai + '"]').val() || 0,
                    nominal_honorarium: $('input[name="nominal_honorarium_' + numpegawai + '"]').val() || 0,
                    data_bank: data_bank,
                    mak: akun,
                });
            } else if (perangkat != 'Supir') {
                jsonDataJS.data_utama.push({
                    perangkat: perangkat,
                    id: $('input[name="idPegawai_' + numpegawai + '"]').val(),
                    nama: $('input[name="namaPegawai_' + numpegawai + '"]').val(),
                    sebagai: $('input[name="sebagaiPegawai_' + numpegawai + '"]').val(),
                    jumlah_kegiatan: $('#satuan_' + numpegawai).val(),
                    satuan_honorarium: $('input[name="satuan_honorarium_' + numpegawai + '"]').val() || 0,
                    jumlah_honorarium: $('input[name="jumlah_honorarium_' + numpegawai + '"]').val() || 0,
                    pph: $('input[name="nilai_pph_' + numpegawai + '"]').val() || 0,
                    nominal_honorarium: $('input[name="nominal_honorarium_' + numpegawai + '"]').val() || 0,
                    data_bank: data_bank,
                    mak: akun,
                });

            }

        });

        $('input[name^="idNonPegawai_"]').each(function() {
            let numnonpegawai = $(this).attr('id').split('_')[1]; // Mengambil num pegawai
            let perangkat = $('#nama_perangkat_non_' + numnonpegawai).val();
            let bank = $('input[name="bank_non_' + numnonpegawai + '"]').val();
            let rekening = $('input[name="no_rekening_non_' + numnonpegawai + '"]').val();
            let akun = $('select[name="akunNonPegawai_' + numnonpegawai + '"]').val() || '';

            // Jika bank atau rekening berisi tanda minus atau kosong, maka anggap kosong
            bank = (bank === '-' || bank === '') ? '' : bank;
            rekening = (rekening === '-' || rekening === '') ? '' : rekening;

            // Jika keduanya tidak kosong, gabungkan dengan ' - '. Jika salah satu kosong, kosongkan data_bank.
            let data_bank = (bank && rekening) ? bank + ' - ' + rekening : (bank || rekening);


            if (perangkat == 'Panitia') {
                jsonDataJS.data_utama.push({
                    perangkat: perangkat,
                    id: $('input[name="idNonPegawai_' + numnonpegawai + '"]').val(),
                    nama: $('input[name="namaNonPegawai_' + numnonpegawai + '"]').val(),
                    sebagai: $('input[name="sebagaiNonPegawai_' + numnonpegawai + '"]').val(),
                    jumlah_kegiatan: '1',
                    satuan_honorarium: $('input[name="satuan_honorarium_non_' + numnonpegawai + '"]').val() || 0,
                    jumlah_honorarium: $('input[name="jumlah_honorarium_non_' + numnonpegawai + '"]').val() || 0,
                    pph: $('input[name="nilai_pph_non_' + numnonpegawai + '"]').val() || 0,
                    nominal_honorarium: $('input[name="nominal_honorarium_non_' + numnonpegawai + '"]').val() || 0,
                    data_bank: data_bank,
                    mak: akun,
                });
            } else {
                jsonDataJS.data_utama.push({
                    perangkat: perangkat,
                    id: $('input[name="idNonPegawai_' + numnonpegawai + '"]').val(),
                    nama: $('input[name="namaNonPegawai_' + numnonpegawai + '"]').val(),
                    sebagai: $('input[name="sebagaiNonPegawai_' + numnonpegawai + '"]').val(),
                    jumlah_kegiatan: $('#satuan_non_' + numnonpegawai).val(),
                    satuan_honorarium: $('input[name="satuan_honorarium_non_' + numnonpegawai + '"]').val() || 0,
                    jumlah_honorarium: $('input[name="jumlah_honorarium_non_' + numnonpegawai + '"]').val() || 0,
                    pph: $('input[name="nilai_pph_non_' + numnonpegawai + '"]').val() || 0,
                    nominal_honorarium: $('input[name="nominal_honorarium_non_' + numnonpegawai + '"]').val() || 0,
                    data_bank,
                    mak: akun,
                });

            }

        });


        // Mengonversi jsonDataJS ke dalam URL yang dikodekan
        let jsonDataUrl = encodeURIComponent(JSON.stringify(jsonDataJS));

        // Perbarui semua tombol Print to PDF
        $('.printPdfLink').each(function() {
            let perangkatName = $(this).data('perangkat');
            $(this).attr('href', "/printpdf?data=" + jsonDataUrl + "&perangkat=" + perangkatName);
        });
    }

    // Menangani perubahan input secara dinamis
    $('input').on('input', function() {
        updateJsonDataJS();
    });

    // Memperbarui link pertama kali saat halaman dimuat
    updateJsonDataJS();
</script>



<script>
    $(document).ready(function () {
        function updateTotal(numpegawai) {
            // Ambil nilai dari setiap input
            const harianPegawai = parseFloat($('#harianPegawai_' + numpegawai).val()) || 0;
            const hariandayPegawai = parseFloat($('#hariandayPegawai_' + numpegawai).val()) || 0;
            const representasiPegawai = parseFloat($('#representasiPegawai_' + numpegawai).val()) || 0;
            const nominalHonorarium = parseFloat($('#input_nominal_honorarium_' + numpegawai).val()) || 0;
            const nominalPerjadinPegawai = parseFloat($('#nominalPerjadinPegawai_' + numpegawai).val()) || 0;
            const satuan = parseFloat($('#satuan_' + numpegawai).val()) || 1;

            // Hitung total dibayar
            const totalDibayar = (harianPegawai * satuan) + (hariandayPegawai * satuan) + (representasiPegawai * satuan) + nominalHonorarium;
            const nominalPerjadin = (harianPegawai * satuan) + (hariandayPegawai * satuan) + (representasiPegawai * satuan);
            const numperangkat = parseFloat($('#perangkat_' + numpegawai).val()) || 0;

            // Set nilai total ke input
            $('input[name="totalPegawai_' + numpegawai + '"]').val(totalDibayar.toFixed(0));
            $('input[name="nominalPerjadinPegawai_' + numpegawai + '"]').val(nominalPerjadin.toFixed(0));

            let subtotal = 0;

            // Loop melalui semua input dengan nama yang sesuai pola
            $('.totalSeluruh_'+numperangkat).each(function () {
                const value = parseFloat($(this).val()) || 0;
                subtotal += value;
            });

            // Set nilai subtotal ke elemen input di tfoot
            $('.subTotalPerangkat_'+numperangkat).val(subtotal.toFixed(0));

            let subtotalApv1 = 0;
            // Loop melalui semua input dengan nama yang sesuai pola
            $('.totalSeluruhApv1_'+numperangkat).each(function () {
                const valueApv1 = parseFloat($(this).val()) || 0;
                subtotalApv1 += valueApv1;
            });

            // Set nilai subtotal ke elemen input di tfoot
            $('.subTotalPerangkatApv1_'+numperangkat).val(subtotalApv1.toFixed(0));
            updateJsonDataJS();
        }

        function updateTotalNon(numnonpegawai) {
            // Ambil nilai dari setiap input
            const harianNonPegawai = parseFloat($('#harianNonPegawai_' + numnonpegawai).val()) || 0;
            const hariandayNonPegawai = parseFloat($('#hariandayNonPegawai_' + numnonpegawai).val()) || 0;
            const representasiNonPegawai = parseFloat($('#representasiNonPegawai_' + numnonpegawai).val()) || 0;
            const nominalNonHonorarium = parseFloat($('#input_nominal_honorarium_non_' + numnonpegawai).val()) || 0;
            const nominalPerjadinNonPegawai = parseFloat($('#nominalPerjadinNonPegawai_' + numnonpegawai).val()) || 0;
            const satuan = parseFloat($('#satuan_non_' + numnonpegawai).val()) || 0;

            // Hitung total dibayar
            const totalDibayar = (harianNonPegawai * satuan) + (hariandayNonPegawai * satuan) + (representasiNonPegawai * satuan) + nominalNonHonorarium;
            const nominalPerjadin = (harianNonPegawai * satuan) + (hariandayNonPegawai * satuan) + (representasiNonPegawai * satuan);
            const numperangkat = parseFloat($('#perangkat_non_' + numnonpegawai).val()) || 0;


            // Set nilai total ke input
            $('input[name="totalNonPegawai_' + numnonpegawai + '"]').val(totalDibayar.toFixed(0));
            $('input[name="nominalPerjadinNonPegawai_' + numnonpegawai + '"]').val(nominalPerjadin.toFixed(0));


            let subtotal = 0;

            // Loop melalui semua input dengan nama yang sesuai pola
            $('.totalSeluruh_'+numperangkat).each(function () {
                const value = parseFloat($(this).val()) || 0;
                subtotal += value;
            });

            // Set nilai subtotal ke elemen input di tfoot
            $('.subTotalPerangkat_'+numperangkat).val(subtotal.toFixed(0));

            let subtotalApv1 = 0;
            // Loop melalui semua input dengan nama yang sesuai pola
            $('.totalSeluruhApv1_'+numperangkat).each(function () {
                const valueApv1 = parseFloat($(this).val()) || 0;
                subtotalApv1 += valueApv1;
            });

            // Set nilai subtotal ke elemen input di tfoot
            $('.subTotalPerangkatApv1_'+numperangkat).val(subtotalApv1.toFixed(0));
            updateJsonDataJS();
        }

        function updateSubtotalOperasional() {
            let subtotal = 0;

            // Loop melalui semua input dengan nama yang sesuai pola
            $('input[name^="totalOperasional_"]').each(function () {
                const value = parseFloat($(this).val()) || 0;
                subtotal += value;
            });

            // Set nilai subtotal ke elemen input di tfoot
            $('.subtotalOperasional').val(subtotal.toFixed(0));
        }

        // Loop melalui setiap input untuk mengisi nilai berdasarkan data
        $('.pajak').each(function () {
            const $input = $(this);
            const status = $input.data('status') || '-';
            const perangkat = $input.data('perangkat');
            let golongan = $input.data('golongan'); // Ubah const menjadi let karena akan diubah

            // Ganti "/" menjadi "-"
            golongan = golongan.replace(/\//g, '-');

            // Kirimkan AJAX request ke route
            $.ajax({
                url: `/get-tarif-pajak/${status}/${golongan}`,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.tarif_pajak) {
                        // Konversi tarif pajak dari desimal ke persentase
                        const tarifPersen = response.tarif_pajak * 100;

                        if (perangkat == 'Panitia') {
                            $input.val(tarifPersen);

                            // Jalankan fungsi untuk setiap elemen input saat halaman dimuat
                            const numpegawai = $input.attr('id').split('_')[2]; // Gunakan $input, bukan $(this)

                            // Ambil nilai jumlah honorarium
                            const jumlahHonorarium = parseFloat($('#input_jumlah_honorarium_' + numpegawai).val()) || 0;

                            // Ambil nilai PPH dari input
                            const pphValue = parseFloat($input.val()) || 0;

                            // Hitung nilai PPH
                            const nilaiPPH = (jumlahHonorarium * pphValue) / 100;
                            $('#input_nilai_pph_' + numpegawai).val(nilaiPPH.toFixed(0));

                            // Menghitung nominal honorarium
                            const nominalHonorarium = jumlahHonorarium - nilaiPPH;
                            $('#input_nominal_honorarium_' + numpegawai).val(nominalHonorarium.toFixed(0));

                            updateTotal(numpegawai);


                            // NON PEGAWAI
                            const numnonpegawai = $input.attr('id').split('_')[3]; // Dapatkan nomor pegawai

                            // Ambil nilai jumlah honorarium
                            const jumlahHonorariumNon = parseFloat($('#input_jumlah_honorarium_non_' + numnonpegawai).val()) || 0;

                            // Ambil nilai PPH dari input
                            const pphValueNon = parseFloat($input.val()) || 0;

                            // Hitung nilai PPH
                            const nilaiPPHNon = (jumlahHonorariumNon * pphValueNon) / 100;
                            $('#input_nilai_pph_non_' + numnonpegawai).val(nilaiPPHNon.toFixed(0));

                            // Menghitung nominal honorarium
                            const nominalHonorariumNon = jumlahHonorariumNon - nilaiPPHNon;
                            $('#input_nominal_honorarium_non_' + numnonpegawai).val(nominalHonorariumNon.toFixed(0));
                            updateTotalNon(numnonpegawai);
                            updateSubtotalOperasional();

                        } else {
                            if (response.default == response.tarif_pajak) {
                                $input.val(0);
                                // Jalankan fungsi untuk setiap elemen input saat halaman dimuat
                                const numpegawai = $input.attr('id').split('_')[2]; // Gunakan $input, bukan $(this)

                                // Ambil nilai jumlah honorarium
                                const jumlahHonorarium = parseFloat($('#input_jumlah_honorarium_' + numpegawai).val()) || 0;

                                // Ambil nilai PPH dari input
                                const pphValue = parseFloat($input.val()) || 0;

                                // Hitung nilai PPH
                                const nilaiPPH = (jumlahHonorarium * pphValue) / 100;
                                $('#input_nilai_pph_' + numpegawai).val(nilaiPPH.toFixed(0));

                                // Menghitung nominal honorarium
                                const nominalHonorarium = jumlahHonorarium - nilaiPPH;
                                $('#input_nominal_honorarium_' + numpegawai).val(nominalHonorarium.toFixed(0));

                                updateTotal(numpegawai);

                                // NON PEGAWAI
                                const numnonpegawai = $input.attr('id').split('_')[3]; // Dapatkan nomor pegawai

                                // Ambil nilai jumlah honorarium
                                const jumlahHonorariumNon = parseFloat($('#input_jumlah_honorarium_non_' + numnonpegawai).val()) || 0;

                                // Ambil nilai PPH dari input
                                const pphValueNon = parseFloat($input.val()) || 0;

                                // Hitung nilai PPH
                                const nilaiPPHNon = (jumlahHonorariumNon * pphValueNon) / 100;
                                $('#input_nilai_pph_non_' + numnonpegawai).val(nilaiPPHNon.toFixed(0));

                                // Menghitung nominal honorarium
                                const nominalHonorariumNon = jumlahHonorariumNon - nilaiPPHNon;
                                $('#input_nominal_honorarium_non_' + numnonpegawai).val(nominalHonorariumNon.toFixed(0));
                                updateTotalNon(numnonpegawai);
                                updateSubtotalOperasional();
                            } else {
                                $input.val(tarifPersen);
                                // Jalankan fungsi untuk setiap elemen input saat halaman dimuat
                                const numpegawai = $input.attr('id').split('_')[2]; // Gunakan $input, bukan $(this)

                                // Ambil nilai jumlah honorarium
                                const jumlahHonorarium = parseFloat($('#input_jumlah_honorarium_' + numpegawai).val()) || 0;

                                // Ambil nilai PPH dari input
                                const pphValue = parseFloat($input.val()) || 0;

                                // Hitung nilai PPH
                                const nilaiPPH = (jumlahHonorarium * pphValue) / 100;
                                $('#input_nilai_pph_' + numpegawai).val(nilaiPPH.toFixed(0));

                                // Menghitung nominal honorarium
                                const nominalHonorarium = jumlahHonorarium - nilaiPPH;
                                $('#input_nominal_honorarium_' + numpegawai).val(nominalHonorarium.toFixed(0));

                                updateTotal(numpegawai);

                                // NON PEGAWAI
                                const numnonpegawai = $input.attr('id').split('_')[3]; // Dapatkan nomor pegawai

                                // Ambil nilai jumlah honorarium
                                const jumlahHonorariumNon = parseFloat($('#input_jumlah_honorarium_non_' + numnonpegawai).val()) || 0;

                                // Ambil nilai PPH dari input
                                const pphValueNon = parseFloat($input.val()) || 0;

                                // Hitung nilai PPH
                                const nilaiPPHNon = (jumlahHonorariumNon * pphValueNon) / 100;
                                $('#input_nilai_pph_non_' + numnonpegawai).val(nilaiPPHNon.toFixed(0));

                                // Menghitung nominal honorarium
                                const nominalHonorariumNon = jumlahHonorariumNon - nilaiPPHNon;
                                $('#input_nominal_honorarium_non_' + numnonpegawai).val(nominalHonorariumNon.toFixed(0));
                                updateTotalNon(numnonpegawai);
                                updateSubtotalOperasional();
                            }
                        }

                    }
                },
                error: function (xhr) {
                    console.error(`Error fetching tarif pajak for ${status} and ${golongan}:`, xhr.responseText);
                },
            });
        });

    });
</script>

<script>
    $(document).ready(function() {
        // Event listener for the first date input
        $('input[name="tglbayarPegawai_0"]').on('change', function() {
            // Get the selected date
            var selectedDate = $(this).val();

            // Iterate over all other date inputs and set the selected date
            $('input[name^="tglbayarPegawai_"]').not(this).each(function() {
                $(this).val(selectedDate);
            });
            $('input[name^="tglbayarNonPegawai_"]').not(this).each(function() {
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
        // Event listener for the first date input
        $('input[name="tglbayarOperasional_0"]').on('change', function() {
            // Get the selected date
            var selectedDate = $(this).val();

            // Iterate over all other date inputs and set the selected date
            $('input[name^="tglbayarOperasional_"]').not(this).each(function() {
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
    $('select[name="statusPembiyaanPegawai_0"]').on('change', function() {
      // Get the selected value
      var selectedValue = $(this).val();
      // Get the selected text
      var selectedText = $(this).find("option:selected").text();

      // Iterate over all other dropdowns and set the selected value
      $('select[name^="statusPembiyaanPegawai_"]').not(this).each(function() {
        $(this).val(selectedValue).trigger('change');
        $(this).find("option:selected").text(selectedText);
      });
      $('select[name^="statusPembayaranNonPegawai_"]').not(this).each(function() {
        $(this).val(selectedValue).trigger('change');
        $(this).find("option:selected").text(selectedText);
      });
    });
  });
  </script>
<script>
    $(document).ready(function() {
    // Event listener for the first dropdown
    $('select[name="kesesuaianOperasional_0"]').on('change', function() {
      // Get the selected value
      var selectedValue = $(this).val();
      // Get the selected text
      var selectedText = $(this).find("option:selected").text();

      // Iterate over all other dropdowns and set the selected value
      $('select[name^="kesesuaianOperasional_"]').not(this).each(function() {
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
    $('select[name="sbmNonPegawai_0"]').on('change', function() {
      // Get the selected value
      var selectedValue = $(this).val();
      // Get the selected text
      var selectedText = $(this).find("option:selected").text();

      // Iterate over all other dropdowns and set the selected value
      $('select[name^="sbmNonPegawai_"]').not(this).each(function() {
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

<script>
    function calculateSummaryTotal() {
        console.log('calculateSummaryTotal function called');
        $('#summaryTableBody').empty();

        var grandTotal = 0;

        $('.calculationTable').each(function() {
            var tableId = $(this).attr('name');
            var total = parseFloat($(this).find('.total-ok').val()) || 0;
            console.log('Table ID:', tableId, 'Total:', total);
            grandTotal += total;

            $('#summaryTableBody').append('<tr><td>' + tableId + '</td><td>' + total + '</td></tr>');
        });

        $('#summaryTableBody').append('<tr><td><strong>Grand Total:</td><td><strong>' + grandTotal + '</td></tr>');
        console.log('Grand Total:', grandTotal);
    }

    // Call the function after the document is ready and after any data updates
    $(document).ready(function() {
        calculateSummaryTotal();

        // Call the function after any data updates
        $('input').on('input', function() {
            calculateSummaryTotal();
        });
    });
</script>
@endsection
