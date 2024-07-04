@extends('admin.templates.sidebar')

@section('contain')
<!-- Awal Dashboard - Kegiatan - BMN Asset -->
    <div class="container-fluid py-3 px5">
      <div class="row">
        <div class="col-md-12">
          <h4>Perjadin Kegiatan / <span class="fw-bold">BMN Asset</span></h4>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 mb-2">
          <div class="card border-0 bg-secondary">
            <div class="page wrapper">
              <a href="{{url('/kegiatan-assets/' . 'pengajuan')}}" class="page-wrap btn btn-sm btn-primary active">Pengajuan</a>
              <a href="{{url('/kegiatan-assets/' . 'digunakan')}}" class="page-wrap btn btn-sm btn-warning text-white">Proses</a>
              <a href="{{url('/kegiatan-assets/' . 'ditolak')}}" class="page-wrap btn btn-sm btn-danger">Ditolak</a>
              <a href="{{url('/kegiatan-assets/'. 'selesai')}}" class="page-wrap btn btn-sm btn-success">Selesai</a>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 mb-3">
          <div class="card">
            <div class="card-body content">

              <!-- BMN Aset - Pengajuan -->
              <div class="row page_content page_1">
                <div class="table-responsive">
                  <table id="example" class="table table-bordered data-table" style="width: 100%">
                    <thead>
                      <tr class="small text-center">
                        <th class="th-sm">No</th>
                        <th class="th-md">Nama Pengaju</th>
                        <th class="th-md">Barang</th>
                        <th class="th-md">Status</th>
                        <th class="th-lg-percent">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($peminjamans as $peminjaman)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td>{{$peminjaman->nama_lengkap}}</td>
                            <td>{{$peminjaman->nama_barang}}</td>
                            <td class='text-center'>{{$peminjaman->status}}</td>
                            <td>
                              <span class="page d-flex justify-content-center align-items-center">
                                <a href="{{url('/detail-sapras/' . $peminjaman->idPeminjaman)}}" class="btn btn-primary d-flex"><i class="fa-solid fa-check pt-1"></i> <p class="ps-1  m-0">Verifikasi</p></a>
                              </span>
                          </td>
                        </tr>
                        @endforeach
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
<!-- Akhir Dashboard - Kegiatan - BMN Asset -->
@endsection