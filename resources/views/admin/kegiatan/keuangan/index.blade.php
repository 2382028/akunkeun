@extends('admin.templates.sidebar')

@section('contain')
<!-- Awal Dashboard - Kegiatan - Keuangan -->
    <div class="container-fluid px-3 py-3">
      <div class="row">
        <div class="col-md-12">
          <h4>Perjadin Kegiatan / <span class="fw-bold">Keuangan</span></h4>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 mb-2">
          <div class="card border-0 bg-secondary">
            <div class="page wrapper">
                <a href="{{url('/kegiatan-keuangan/' . 'verifikasi-1')}}" class="page-wrap btn btn-sm btn-primary">Verifikator Tahap 1 | Pengajuan</a>
                <a href="{{url('/kegiatan-keuangan/' . 'verifikasi-2')}}" class="page-wrap btn btn-sm btn-warning text-white">Verifikator Tahap 2 | Pelaporan</a>
                <a href="{{url('/kegiatan-keuangan/' . 'revisi-1')}}" class="page-wrap btn btn-sm btn-info text-white">Revisi - 1</a>
                <a href="{{url('/kegiatan-keuangan/' . 'revisi-2')}}" class="page-wrap btn btn-sm btn-info text-white">Revisi - 2</a>
                <a href="{{url('/kegiatan-keuangan/' . 'ditolak')}}" class="page-wrap btn btn-sm btn-danger">Ditolak</a>
                <a href="{{url('/kegiatan-keuangan/' . 'selesai')}}" class="page-wrap btn btn-sm btn-success">Selesai</a>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 mb-3">
          <div class="card">
            <div class="card-body content">

              <!-- Kegiatan - Keuangan - Pengajuan -->
              <div class="row page_content page_1">
                <div class="table-responsive">
                  <div class="col-md-12 mb-3 text-end">
                    <button id="downloadexcel" class="btn btn-success btn-sm "><i class="fa-solid fa-file-excel"></i> Export to Excel</button>
                  </div>
                  <table id="example" class="table table-bordered data-table" style="width: 100%">
                    <thead>
                      <tr class="text-center small">
                        <th class="th-sm">No</th>
                        <th class="th-lg">Nama Kegiatan/Program</th>
                        <th class="th-md">Tanggal Mulai</th>
                        <th class="th-md">Status</th>
                        <th class="th-lg-percent">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($kegiatans as $kegiatan)
                      <tr>
                        <td class='text-center'>{{$loop->iteration}}</td>
                        <td>{{$kegiatan->nama_kegiatan}}</td>
                        <td class='text-center'>{{$kegiatan->tgl_mulai}}</td>
                        <td class='text-center'>{{$kegiatan->status}} | {{$kegiatan->is_acceptKeu}}</td>
                        <td class='text-center'>
                          <span class="page d-flex justify-content-center align-items-center">
                            <a href="{{url('/detail-keuangan/' . $kegiatan->id)}}" class="btn btn-primary d-flex"><i class="fa-solid fa-check pt-1"></i> <p class="ps-1  m-0">Verifikasi</p></a>
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
  <!-- Akhir Dashboard - Kegiatan - Keuangan -->

  <script>
    document.getElementById('downloadexcel').addEventListener('click', function() {
          var table2excel = new Table2Excel();
          table2excel.export(document.querySelectorAll("#example"), "Daftar Antrian Perjadin Kegiatan - Keuangan");
      });
  </script>
@endsection