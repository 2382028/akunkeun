@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>BMN / Data Penyedia Service / <span class="fw-bold">{{$penyedia->nama_CV}}</span></h4>
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
                  
                    <form action="{{url('/c_konfirmasi_service_kendaraan')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="idPenyedia" value="{{$penyedia->id}}">
                  <div class="col-md-12 mb-3">
                    <h5 class="fw-bold">Informasi Perbaikan</h5>
                    <div class="table-responsive">                        
                      <table id="example" class="table table-bordered" style="width: 100%">
                        <thead>
                          <tr class="text-center small">
                            <th class="th-sm">No</th>
                            <th class="th-md">Nama Pengaju</th>
                            <th class="th-md">Nama Barang</th>
                            <th class="th-md">Tanggal Selesai</th>
                            <th class="th-lg-percent">Aksi</th>
                          </tr>
                        </thead>
                        @php
                            $numpermohonanAdmin = 0;
                            $numpermohonanPegawai = 0;
                        @endphp
                        @foreach ($permohonans as $permohonan)
                            <tr>
                                <td class='text-center'>{{$loop->iteration}}</td>
                                <td>{{$permohonan->username}}</td>
                                <td>{{$permohonan->merek}} - {{$permohonan->no_polisi}}</td>
                                <td class='text-center'>{{$permohonan->tgl_selesai}}</td>
                                <td class='text-center'>
                                <span class="">
                                    <input type="hidden" name="idPermohonanAdmin_{{$numpermohonanAdmin}}" value="{{$permohonan->idPermohonan}}">
                                    {{-- <input class="form-check-input" type="checkbox" value="verifikasi-1" id="" name="statusAdmin_{{$numpermohonanAdmin}}"> --}}
                                    <select class="form-select" aria-label="Default select example" name="statusAdmin_{{$numpermohonanAdmin}}">
                                        <option value="pembayaran" selected>Tunggu</option>
                                        <option value="verifikasi-1">Konfirmasi</option>
                                      </select>
                                </span>
                                </td>
                            </tr>
                            @php
                                $numpermohonanAdmin++;
                            @endphp
                        @endforeach
                    </table>
                    </div>
                  </div>
                  <input type="hidden" name="numPermohonanAdmin" value="{{$numpermohonanAdmin}}">

                  <div class="col-md-12 mb-3">
                    <h5 class="fw-bold">Upload Dokumen</h5>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="" class="form-label">BAST</label>
                        </div>
                        <div class="col-md-3">
                            <input type="file" name="bast" id="" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="" class="form-label">BAP</label>
                        </div>
                        <div class="col-md-3">
                            <input type="file" name="bap" id="" required>
                        </div>
                    </div>
                  </div>

                  <div class="col-md-12 mb-3">
                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                        <a href="{{url('/pembayaran_service_assets')}}" class="btn btn-dark">Kembali</a>
                        <button class="btn btn-success" type="submit">Perbaharui dan kirim ke keuangan untuk pemeriksaan dokumen dan pembayaran</button>
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