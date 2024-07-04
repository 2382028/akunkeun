@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>BMN / <span class="fw-bold">Data Service Aset</span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mb-2">
          <div class="card border-0 bg-secondary">
            <div class="page wrapper">
                <a href="{{url('/perbaikan_assets/' . 'pengajuan')}}" class="page-wrap btn btn-sm btn-primary">Pengajuan</a>
                <a href="{{url('/perbaikan_assets/' . 'pemeriksaan')}}" class="page-wrap btn btn-sm btn-warning text-white">Pemeriksaan</a>
                <a href="{{url('/perbaikan_assets/' . 'pengerjaan')}}" class="page-wrap btn btn-sm btn-info text-white">Pengerjaan</a>
                <a href="{{url('/pembayaran_service_assets')}}" class="page-wrap btn btn-sm btn-success">Pembayaran</a>
                <a href="{{url('/perbaikan_assets/' . 'riwayat')}}" class="page-wrap btn btn-sm btn-orange">Riwayat Service</a>
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
                                <th class="th-md">Nama Aset</th>
                                <th class="th-md">Status</th>
                                <th class="th-sm">Aksi</th>
                            </tr>
                            </thead>
                            @foreach ($servicePegawais as $servicePegawai)
                            <tr>
                                <td  class='text-center'>{{$loop->iteration}}</td>
                                <td>{{$servicePegawai->nama_lengkap}}</td>
                                <td>{{$servicePegawai->nama_barang}}</td>
                                <td  class='text-center'>{{$servicePegawai->status}}</td>
                                <td class='text-center d-flex justify-content-evenly'>
                                <a href="{{url('/service/detail-asset/' . $servicePegawai->idService)}}" class="btn btn-primary"><i class="fa-solid fa-check pt-1"></i> Verifikasi</a>
                                </td>
                            </tr>
                            @endforeach
                            @foreach ($serviceAdmins as $serviceAdmin)
                            <tr>
                                <td  class='text-center'>{{$loop->iteration}}</td>
                                <td>{{$serviceAdmin->username}}</td>
                                <td>{{$serviceAdmin->nama_barang}}</td>
                                <td  class='text-center'>{{$serviceAdmin->status}}</td>
                                <td class='text-center d-flex justify-content-evenly'>
                                <a href="{{url('/service/detail-asset/' . $serviceAdmin->idService)}}" class="btn btn-primary"><i class="fa-solid fa-check pt-1"></i> Verifikasi</a>
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