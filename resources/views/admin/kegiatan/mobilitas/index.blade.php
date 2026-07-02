@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid px-4 py-4">
        <div class="row">
          <div class="col-md-12">
            <h4>Perjadin Kegiatan / <span class="fw-bold">BMN Kendaraan</span></h4>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 mb-3">
            <div class="card border-0 bg-secondary">
              <div class="page wrapper">
                <a href="{{url('/kegiatan-mobilitas/' . 'pengajuan')}}" class="page-wrap btn btn-sm btn-primary active">Pengajuan</a>
                <a href="{{url('/kegiatan-mobilitas/' . 'proses')}}" class="page-wrap btn btn-sm btn-warning text-white">Proses</a>
                <a href="{{url('/kegiatan-mobilitas/' . 'ditolak')}}" class="page-wrap btn btn-sm btn-danger">Ditolak</a>
                <a href="{{url('/kegiatan-mobilitas/' . 'selesai')}}" class="page-wrap btn btn-sm btn-success">Selesai</a>
            </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 mb-3">
            <div class="card">
              <div class="card-body content">
  
                          <!-- BMN Kendaraan - Pengajuan -->
                  <div class="row page_content card-style">
                            <div class="table-responsive">
                              <table id="example" class="table table-bordered data-table" style="width: 100%">
                                <thead>
                                  <tr class="text-center small">
                                    <th class="th-sm">No</th>
                                    <th class="th-lg-percent">ID Kegiatan</th>
                                    <th class="th-md">Nama Kegiatan</th>
                                    <th class="th-md">Untuk</th>
                                    <th class="th-md">Berangkat</th>
                                    <th class="th-md">Status</th>
                                    <th class="th-lg-percent">Aksi</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach ($mobilitass as $mobilitas)
                                  <tr>
                                    <td class="text-center">{{$loop->iteration}}</td>
                                    <td class="text-center">{{$mobilitas->idKegiatan}}</td>
                                    <td>{{$mobilitas->nama_kegiatan}}</td>
                                    <td class="text-center">{{$mobilitas->tujuan_penggunaan}}</td>
                                    <td class='text-center'>{{\Carbon\Carbon::parse($mobilitas->tgl_mulai)->format('d-m-Y H:i')}}</td>
                                    <td class="text-center">{{$mobilitas->status}}</td>
                                    <td>
                                      @if($mobilitas->status == 'pengajuan')
                                      <span class="page d-flex justify-content-center align-items-center">
                                        <a href="{{url('/kegiatan-mobilitas/detail/' . $mobilitas->idMobilitas)}}" class="btn btn btn-primary d-flex">
                                          <i class="fa-solid fa-check pt-1"></i> <p class="ps-1  m-0">Verifikasi</p>
                                        </a>
                                      </span>
                                      @else
                                      <span class="page d-flex justify-content-center align-items-center">
                                        <a href="{{url('/kegiatan-mobilitas/detail/' . $mobilitas->idMobilitas)}}" class="btn btn btn-primary d-flex">
                                          <i class="fa-solid fa-eye pt-1"></i> 
                                        </a>
                                      </span>
                                      @endif
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
@endsection