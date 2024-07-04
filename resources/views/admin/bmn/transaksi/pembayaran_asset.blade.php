@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>BMN / <span class="fw-bold">Data Service</span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mb-2">
          <div class="card border-0 bg-secondary">
            <div class="page wrapper">
                <a href="{{url('/perbaikan_assets/' . 'pengajuan')}}" class="page-wrap btn btn-sm btn-primary">Pengajuan</a>
                <a href="{{url('/perbaikan_assets/' . 'pemeriksaan')}}" class="page-wrap btn btn-sm btn-warning text-white">Pemeriksaan</a>
                <a href="{{url('/perbaikan_assets/' . 'pengerjaan')}}" class="page-wrap btn btn-sm btn-info text-white">Pengerjaan</a>
                <a href="{{url('/perbaikan_serice_asset')}}" class="page-wrap btn btn-sm btn-success">Pembayaran</a>
                <a href="{{url('/perbaikan_assets/' . 'selesai')}}" class="page-wrap btn btn-sm btn-success">Riwayat Service</a>
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
                                <th class="th-md">Nama Penyedia</th>
                                <th class="th-md">Kategori</th>
                                <th class="th-md">Nama Asset</th>
                                <th class="th-md">Status</th>
                                <th class="th-sm">Aksi</th>
                            </tr>
                            </thead>
                            @foreach ($penyedias as $penyedia)
                            <tr>
                                <td  class='text-center'>{{$loop->iteration}}</td>
                                <td>{{$penyedia->nama_CV}}</td>
                                <td  class='text-center'>{{$penyedia->kategori}}</td>
                                <td>{{$penyedia->nama_barang}}</td>
                                <td  class='text-center'>{{$penyedia->status}}</td>
                                <td class='text-center d-flex justify-content-evenly'>
                                <a href="{{url('/service/penyedia-asset/' . $penyedia->idPenyedia)}}" class="btn btn-primary"><i class="fa-solid fa-check pt-1"></i> Verifikasi</a>
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