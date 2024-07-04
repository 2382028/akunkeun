@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>BMN / <span class="fw-bold">Data Service Ruangan</span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mb-2">
          <div class="card border-0 bg-secondary">
            <div class="page wrapper">
                <a href="{{url('/service_ruangan_all/' . 'pengajuan')}}" class="page-wrap btn btn-sm btn-primary">Pengajuan</a>
                <a href="{{url('/service_ruangan_all/' . 'pemeriksaan')}}" class="page-wrap btn btn-sm btn-warning text-white">Pemeriksaan</a>
                <a href="{{url('/service_ruangan_all/' . 'pengerjaan')}}" class="page-wrap btn btn-sm btn-info text-white">Pengerjaan</a>
                <a href="{{url('/pembayaran_service_ruangan')}}" class="page-wrap btn btn-sm btn-success">Pembayaran</a>
                <a href="{{url('/service_ruangan_all/' . 'riwayat')}}" class="page-wrap btn btn-sm btn-success">Riwayat Service</a>
            </div>
          </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body content">
  
                <!-- Data Penyedia - ALl -->
                <div class="row page_content page_1">
                    <div class="table-responsive">
                        <table id="example" class="table table-bordered data-table" style="width: 100%">
                            <thead>
                            <tr class="text-center small">
                                <th class="th-sm">No</th>
                                <th class="th-md">Nama Pengaju</th>
                                <th class="th-md">Nama Ruangan</th>
                                <th class="th-md">Status</th>
                                <th class="th-sm">Aksi</th>
                            </tr>
                            </thead>
                            @foreach ($serviceRuangans as $serviceRuangan)
                            <tr>
                                <td  class='text-center'>{{$loop->iteration}}</td>
                                <td>{{$serviceRuangan->username}}</td>
                                <td>{{$serviceRuangan->nama_ruangan}} - {{$serviceRuangan->kode_ruangan}}</td>
                                <td  class='text-center'>{{$serviceRuangan->status}}</td>
                                <td class='text-center d-flex justify-content-evenly'>
                                <a href="{{url('/service/detail_ruangan/' . $serviceRuangan->idService)}}" class="btn btn-primary"><i class="fa-solid fa-check pt-1"></i> Verifikasi</a>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
  
                </div>
            </div>
        </div>
    </div>
</div>
@endsection