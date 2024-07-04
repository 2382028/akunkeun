@extends('user.templates.template')

@section('content')
    <div class="container mt-5 mb-5 pt-5">
        <div class="row mb-3">
            <h3 class="fw-bold text-secondary">Barang Milik Negara (BMN) | LLDIKTI 4</h3>
        </div>
        <div class="card shadow-sm rounded-0  border-0">
            <div class="card-body content">
                <!-- Pengajuan -->
                <div class="row page_content page_1">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="example" class="table table-bordered data-table" style="width: 100%">
                                <thead>
                                    <tr class="text-center small">
                                        <th class="th-sm">No</th>
                                        <th class="th-sm">Kode Barang</th>
                                        <th class="th-md">Nama Barang</th>
                                        <th class="">Merek Barang</th>
                                        <th class="th-sm">Status</th>
                                        <th class="th-lg-percent">Aksi</th>
                                    </tr>
                                </thead>
                                @foreach ($assets as $asset)
                                <tr>
                                    <td class='text-center'>{{$loop->iteration}}</td>
                                    <td class='text-center'>{{$asset->kode_barang}}</td>
                                    <td class=''>{{$asset->nama_barang}}</td>
                                    <td class=''>{{$asset->nama_merek}}</td>
                                    <td class='text-center'>{{$asset->status_peminjaman}}</td>
                                    <td class='text-center'>
                                        <span class="page details">
                                            <a href="{{url('/peminjaman/' . $asset->id)}}" class="page-wrap btn btn-primary btn-sm">Pinjam</a>
                                        </span>
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
@endsection