@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>BMN / Bag.Keuangan / <span class="fw-bold">Data Service</span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mb-2">
          <div class="card border-0 bg-secondary">
            <div class="page wrapper">
                <a href="{{url('/service_keuangan/' . 'verifikasi-1')}}" class="page-wrap btn btn-sm btn-primary">Verifikasi-1</a>
                <a href="{{url('/service_keuangan_riwayat/' . 'selesai')}}" class="page-wrap btn btn-sm btn-warning text-white">Riwayat</a>
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
                                <th class="th-md">Nama Aset</th>
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
                                <td class='text-center'>{{$penyedia->is_acceptKeu}}</td>
                                <td class='text-center d-flex justify-content-evenly'>
                                <a href="{{url('/bmn_keuangan_riwayat/detail/' . $penyedia->idService)}}" class="btn btn-primary"><i class="fa-solid fa-check pt-1"></i> Verifikasi</a>
                                </td>
                            </tr>
                            @endforeach
                            @foreach ($penyediaKendaraans as $penyediaKendaraan)
                            <tr>
                                <td class='text-center'>{{$loop->iteration}}</td>
                                <td>{{$penyediaKendaraan->nama_CV}}</td>
                                <td class='text-center'>{{$penyediaKendaraan->kategori}}</td>
                                <td>{{$penyediaKendaraan->merek}} - {{$penyediaKendaraan->no_polisi}}</td>
                                <td class='text-center'>{{$penyediaKendaraan->is_acceptKeu}}</td>
                                <td class='text-center d-flex justify-content-evenly'>
                                <a href="{{url('/bmn_keuangan_riwayat/detail/' . $penyediaKendaraan->idService)}}" class="btn btn-primary"><i class="fa-solid fa-check pt-1"></i> Verifikasi</a>
                                </td>
                            </tr>
                            @endforeach
                            @foreach ($penyediaRuangans as $penyediaRuangan)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$penyediaRuangan->nama_CV}}</td>
                                <td>{{$penyediaRuangan->kategori}}</td>
                                <td>{{$penyediaRuangan->nama_ruangan}} - {{$penyediaRuangan->kode_ruangan}}</td>
                                <td>{{$penyediaRuangan->is_acceptKeu}}</td>
                                <td class='text-center d-flex justify-content-evenly'>
                                <a href="{{url('/bmn_keuangan_riwayat/detail/' . $penyediaRuangan->idService)}}" class="btn btn-primary"><i class="fa-solid fa-check pt-1"></i> Verifikasi</a>
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