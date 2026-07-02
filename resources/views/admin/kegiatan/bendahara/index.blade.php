@extends('admin.templates.sidebar')

@section('contain')
<!-- Awal Dashboard - Kegiatan - bendahara -->
    <div class="container-fluid px-3 py-3">
      <div class="row">
        <div class="col-md-12">
          <h4>Perjadin Kegiatan / <span class="fw-bold">Bendahara</span></h4>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 mb-2">
          <div class="card border-0 bg-secondary">
            <div class="page wrapper">
                <a href="{{url('/kegiatan-bendahara/' . 'approval-1')}}" class="page-wrap btn btn-sm btn-primary">Approval Tahap 1 | Pengajuan</a>
                <a href="{{url('/kegiatan-bendahara/' . 'approval-2')}}" class="page-wrap btn btn-sm btn-warning text-white">Approval Tahap 2 | Pelaporan</a>
                <a href="{{url('/kegiatan-bendahara/' . 'ditolak')}}" class="page-wrap btn btn-sm btn-danger">Ditolak</a>
                <a href="{{url('/kegiatan-bendahara/' . 'selesai')}}" class="page-wrap btn btn-sm btn-success">Selesai</a>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 mb-3">
          <div class="card">
            <div class="card-body content">

              <!-- Kegiatan - bendahara - Pengajuan -->
              <div class="row page_content page_1">
                <div class="table-responsive">
                  <div class="col-md-12 mb-3 text-end">
                    <button id="downloadexcel" class="btn btn-success btn-sm "><i class="fa-solid fa-file-excel"></i> Export to Excel</button>
                  </div>
                  <table id="example" class="table table-bordered data-table" style="width: 100%">
                    <thead>
                      <tr class="text-center small">
                        <th class="th-sm">No</th>
                        <th class="th-sm">ID Kegiatan</th>
                        <th class="th-lg">Nama Kegiatan/Program</th>
                        <th class="th-md">Tanggal Mulai</th>
                        <th class="th-md">Status</th>
                        <th class="th-lg-percent">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>

                      @foreach ($kegiatans as $kegiatan)
                        @if ($status == 'approval-2')
                            <tr>
                                <td class='text-center'>{{$loop->iteration}}</td>
                                <td>{{$kegiatan->id}}</td>
                                <td>{{$kegiatan->nama_kegiatan}}</td>
                                <td class='text-center'>{{$kegiatan->tgl_mulai}}</td>

                                @if ($kegiatan->is_acceptKeu == 'selesai')
                                    <td class='text-center'>{{$kegiatan->status}} | {{$kegiatan->is_acceptBend}}</td>
                                    <td class='text-center'>
                                        <span class="page d-flex justify-content-center align-items-center">
                                            <a href="{{url('/detail-bendahara/' . $kegiatan->id)}}" class="btn btn-primary d-flex"><i class="fa-solid fa-check pt-1"></i> <p class="ps-1  m-0">Approval</p></a>
                                        </span>
                                    </td>
                                @else
                                    <td class='text-center'>Pelaporan/Verifikasi Keuangan</td>
                                    <td class='text-center'>
                                        <span class="page d-flex justify-content-center align-items-center">
                                            <a href="{{url('/detail-bendahara/' . $kegiatan->id)}}" class="btn btn-dark d-flex"><i class="fa-solid fa-eye pt-1"></i> <p class="ps-1  m-0">Detail</p></a>
                                        </span>
                                    </td>
                                @endif
                            </tr>
                        @elseif ($status == 'approval-1')
                            <tr>
                                <td class='text-center'>{{$loop->iteration}}</td>
                                <td>{{$kegiatan->id}}</td>
                                <td>{{$kegiatan->nama_kegiatan}}</td>
                                <td class='text-center'>{{$kegiatan->tgl_mulai}}</td>
                                <td class='text-center'>{{$kegiatan->status}} | {{$kegiatan->is_acceptBend}}</td>
                                <td class='text-center'>
                                    <span class="page d-flex justify-content-center align-items-center">
                                        <a href="{{url('/detail-bendahara/' . $kegiatan->id)}}" class="btn btn-primary d-flex"><i class="fa-solid fa-check pt-1"></i> <p class="ps-1  m-0">Approval</p></a>
                                    </span>
                                </td>
                            </tr>
                        @elseif ($status == 'ditolak')
                            <tr>
                                <td class='text-center'>{{$loop->iteration}}</td>
                                <td>{{$kegiatan->id}}</td>
                                <td>{{$kegiatan->nama_kegiatan}}</td>
                                <td class='text-center'>{{$kegiatan->tgl_mulai}}</td>
                                <td class='text-center'>{{$kegiatan->status}} | {{$kegiatan->is_acceptBend}}</td>
                                <td class='text-center'>
                                    <span class="page d-flex justify-content-center align-items-center">
                                        <a href="{{url('/detail-bendahara/' . $kegiatan->id)}}" class="btn btn-primary d-flex"><i class="fa-solid fa-eye pt-1"></i> <p class="ps-1  m-0"> Detail</p></a>
                                    </span>
                                </td>
                            </tr>
                        @elseif ($status == 'selesai')
                            <tr>
                                <td class='text-center'>{{$loop->iteration}}</td>
                                <td>{{$kegiatan->id}}</td>
                                <td>{{$kegiatan->nama_kegiatan}}</td>
                                <td class='text-center'>{{$kegiatan->tgl_mulai}}</td>
                                <td class='text-center'>{{$kegiatan->status}} | {{$kegiatan->is_acceptBend}}</td>
                                <td class='text-center'>
                                    <div class="row">
                                        <span class="page d-flex justify-content-center align-items-center">
                                            <a href="{{url('/detail-bendahara/' . $kegiatan->id)}}" class="btn btn-primary d-flex me-2"><i class="fa-solid fa-eye pt-1"></i> <p class="ps-1  m-0"> Detail</p></a>
                                            <a href="{{url('/kegiatan-bendahara/rpd/' . $kegiatan->id)}}" target="_blank" class="btn me-2 btn-warning d-flex"><i class="fa-solid fa-print pt-1"></i> <p class="ps-1  m-0"> RPD</p></a>
                                            <div class="dropdown">
                                                <button class="btn btn-warning dropdown-toggle d-flex align-items-center" type="button" id="dropdownRPD" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa-solid fa-print pt-1"></i>
                                                    <p class="ps-1 m-0">RPD Kategori</p>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownRPD">
                                                    <li><a class="dropdown-item" href="{{ url('/kegiatan-bendahara/rpd-kat/' . $kegiatan->id . '/Panitia') }}" target="_blank">Panitia</a></li>
                                                    <li><a class="dropdown-item" href="{{ url('/kegiatan-bendahara/rpd-kat/' . $kegiatan->id . '/Narasumber') }}" target="_blank">Narasumber</a></li>
                                                    <li><a class="dropdown-item" href="{{ url('/kegiatan-bendahara/rpd-kat/' . $kegiatan->id . '/Moderator') }}" target="_blank">Moderator</a></li>
                                                    <li><a class="dropdown-item" href="{{ url('/kegiatan-bendahara/rpd-kat/' . $kegiatan->id . '/Supir') }}" target="_blank">Supir</a></li>
                                                </ul>
                                            </div>

                                        </span>
                                    </div>
                                </td>
                            </tr>

                        @endif

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
          table2excel.export(document.querySelectorAll("#example"), "Daftar Antrian Perjadin Kegiatan - Bendahara");
      });
  </script>
@endsection
