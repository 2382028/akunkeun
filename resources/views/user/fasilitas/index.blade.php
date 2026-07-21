@extends('user.templates.sidebar')
@php
    $activeVersi = \App\Models\Versi::where('status', 'aktif')->first() ?? (object) ['id' => '-1','versi' => 'Default Versi'];
@endphp

@section('content')
    <div class="container mt-4 mb-5">
        <div class="row mb-3">
            <h5 class="fw-bold text-secondary">Barang Milik Negara (BMN) | LLDIKTI 4</h5>
        </div>
        <div class="card shadow-sm rounded-0  border-0">
            <div class="card-body content">
                <!-- Pengajuan -->
                <div class="row page_content page_1">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="example" class="table table-bordered table-sm data-table" style="width: 100%; font-size: 0.95rem;">
                                <thead>
                                    <tr class="text-center small align-middle">
                                        <th class="th-sm">No</th>
                                        <th class="th-sm">Kode Barang</th>
                                        <th class="th-md">Nama Barang</th>
                                        <th class="">Merek Barang</th>
                                        <th class="th-sm">Status</th>
                                        <th style="width: 120px; text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                @foreach ($assets as $asset)
                                <tr class="align-middle">
                                    <td class='text-center'>{{$loop->iteration}}</td>
                                    <td class='text-center'>{{$asset->kode_barang}}</td>
                                    <td class=''>{{$asset->nama_barang}}</td>
                                    <td class=''>{{$asset->nama_merek}}</td>
                                    <td class='text-center'>{{$asset->status_peminjaman}}</td>
                                    <td class="text-center">
                                        @if ($asset->kategori == 1 && ($asset->status_peminjaman === 'Tidak Dipakai' || $asset->status_peminjaman === 'Tidak Digunakan'))
                                            @if ($activeVersi && ($activeVersi->id != session('versi')))
                                                <a href="{{ url('/peminjaman/' . $asset->id) }}" class="btn btn-primary btn-sm" onclick="showAlert(event)">Pinjam</a>
                                            @else
                                                <a href="{{ url('/peminjaman/' . $asset->id) }}" class="btn btn-primary btn-sm">Pinjam</a>
                                            @endif
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled>Tidak Dapat Dipinjam</button>
                                        @endif
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
