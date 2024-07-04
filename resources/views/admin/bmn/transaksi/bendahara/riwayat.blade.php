@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>BMN / Bag.Bendahara / <span class="fw-bold">Riwayat Service</span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mb-2">
          <div class="card border-0 bg-secondary">
            <div class="page wrapper">
                <a href="{{url('/service_bendahara/' . 'approval-1')}}" class="page-wrap btn btn-sm btn-primary">Approval-1</a>
                <a href="{{url('/service_bendahara_riwayat/' . 'selesai')}}" class="page-wrap btn btn-sm btn-warning text-white">Riwayat</a>
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
                                <td class='text-center'>{{$loop->iteration}}</td>
                                <td>{{$penyedia->nama_CV}}</td>
                                <td class='text-center'>{{$penyedia->kategori}}</td>
                                <td>{{$penyedia->nama_barang}}</td>
                                <td class='text-center'>{{$penyedia->is_acceptBend}}</td>
                                <td class='text-center d-flex justify-content-evenly'>
                                <a href="{{url('/bmn_bendahara/riwayat/' . $penyedia->idService)}}" class="btn btn-primary"><i class="fa-solid fa-check pt-1"></i> Approval</a>
                                </td>
                            </tr>
                            @endforeach
                            @foreach ($penyediakendaraans as $penyediakendaraan)
                            <tr>
                                <td class='text-center'>{{$loop->iteration}}</td>
                                <td>{{$penyediakendaraan->nama_CV}}</td>
                                <td class='text-center'>{{$penyediakendaraan->kategori}}</td>
                                <td>{{$penyediakendaraan->merek}} - {{$penyediakendaraan->no_polisi}}</td>
                                <td class='text-center'>{{$penyediakendaraan->is_acceptBend}}</td>
                                <td class='text-center d-flex justify-content-evenly'>
                                <a href="{{url('/bmn_bendahara/riwayat/' . $penyediakendaraan->idService)}}" class="btn btn-primary"><i class="fa-solid fa-check pt-1"></i> Approval</a>
                                </td>
                            </tr>
                            @endforeach
                            @foreach ($penyediaruangans as $penyediaruangan)
                            <tr>
                                <td class='text-center'>{{$loop->iteration}}</td>
                                <td>{{$penyediaruangan->nama_CV}}</td>
                                <td class='text-center'>{{$penyediaruangan->kategori}}</td>
                                <td>{{$penyediaruangan->nama_ruangan}} - {{$penyediaruangan->kode_ruangan}}</td>
                                <td class='text-center'>{{$penyediaruangan->is_acceptBend}}</td>
                                <td class='text-center d-flex justify-content-evenly'>
                                <a href="{{url('/bmn_bendahara/riwayat/' . $penyediaruangan->idService)}}" class="btn btn-primary"><i class="fa-solid fa-check pt-1"></i> Approval</a>
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