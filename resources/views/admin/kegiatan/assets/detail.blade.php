@extends('admin.templates.sidebar')

@section('contain')
      <div class="container-fluid py-3">
        <div class="row">
          <div class="col-md-12">
            <h4>Perjadin Kegiatan / <span class="fw-bold">BMN Asset</span></h4>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 mb-3">
            <div class="card">
              <div class="card-body content">
                <!-- BMN Aset - Pengajuan - Details -->
                <div class="row page_content page_5">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="row">
                              <h5 class="fw-bold">Informasi Kegiatan</h5>
                            </div>
                            <div class="row small">
                                <div class="col-4">Nama Kegiatan</div>
                                <div class="col-8">: {{$info[0]->nama_kegiatan}}</div>
                            </div>
                            <div class="row small">
                                <div class="col-4">Tanggal Penyelenggaran</div>
                                <div class="col-8">: {{$info[0]->tgl_mulai}}</div>
                            </div>
                            <div class="row small">
                                <div class="col-4">Alamat</div>
                                <div class="col-8">: {{$info[0]->alamat}}</div>
                            </div>
                            <br>
                        </div>
                    </div>
                  
                  <div class="col-md-12 mb-3">
                    <h5 class="fw-bold">Informasi Asset</h5>
                    <div class="table-responsive"> 
                    <form action="{{url('/up_peminjaman_sapras')}}" method="post">
                    @csrf                       
                      <table id="example" class="table table-bordered" style="width: 100%">
                        <thead>
                          <tr class="text-center small">
                            <th class="th-sm">No</th>
                            <th class="th-md">Nama Peminjman</th>
                            <th class="th-md">Barang</th>
                            <th class="th-sm">Jumlah</th>
                            <th class="th-sm">Keterangan</th>
                            <th class="th-sm">Status</th>
                          </tr>
                        </thead>
                        <tbody>
                            @php
                                $num = 0;
                            @endphp
                            @foreach ($peminjamans as $peminjaman)
                            <tr>
                                <input type="hidden" name="idPeminjaman_{{$num}}" value="{{$peminjaman->idPeminjaman}}">
                                <input type="hidden" name="idBarang_{{$num}}" value="{{$peminjaman->idBarang}}">
                                <td class='text-center'>{{$loop->iteration}}</td>
                                <td>{{$peminjaman->nama_lengkap}}</td>
                                <td>{{$peminjaman->nama_barang}}</td>
                                <td class='text-center'>{{$peminjaman->jumlah_asset}}</td>
                                <td>{{$peminjaman->keterangan}}</td>
                                <td class='text-center'>
                                        @if (($peminjaman->status == 'ditolak') | ($peminjaman->status == 'selesai'))
                                        <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="status_{{$num}}">
                                            <option value="{{$peminjaman->status}}" selected>{{$peminjaman->status}}</option>
                                        </select>
                                        @else
                                        <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="status_{{$num}}">
                                            @if ($peminjaman->status == 'pengajuan')
                                            <option value="{{$peminjaman->status}}" selected>{{$peminjaman->status}}</option>
                                            <option value="digunakan">Setujui</option>
                                            <option value="ditolak">Tolak</option>
                                            @endif
                                            @if ($peminjaman->status == 'digunakan')
                                            <option value="{{$peminjaman->status}}" selected>{{$peminjaman->status}}</option>
                                            <option value="selesai">Selesai</option>
                                            @endif
                                        </select>
                                        @endif
                                </td>
                            </tr>
                            @php
                                $num++;
                            @endphp
                            @endforeach
                            <input type="hidden" name="total" value="{{$num}}">
                        </tbody>
                    </table>
                    </div>
                  </div>

                  <div class="col-md-12 mb-3">
                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                        <a href="{{url('/kegiatan-mobilitas/' . 'pengajuan')}}" class="btn btn-dark">Kembali</a>
                        <button class="btn btn-success" type="submit">Perbaharui data</button>
                    </div>
                  </div>
                </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    <!-- Akhir Dashboard - Kegiatan - BMN Asset -->
@endsection