@extends('user.templates.template')

@section('content')
    <!-- Barang Saya - Details -->
    <div class="container mt-5 mb-5 pt-3">
        @foreach ($riwayats as $riwayat)
            
        <div class="row page_content page_7 justify-content-center">
            <div class="col-md-11">
                <h6 class="text-secondary fw-bold">Informasi Peminjaman Barang</h6><br>
                <div class="card shadow-sm rounded-0 border-0  mb-3">
                    <div class="card-body">
                        <div class="row mb-3">
                            <h6 class="fw-bold text-secondary">Informasi Barang</h6><br>
                        </div>
                        <div class="row mb-3">
                            <div class="col-3">Nama Barang</div>
                            <div class="col-9">{{$riwayat->nama_barang}}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-3">Keterangan</div>
                            <div class="col-9">{{$riwayat->nama_merek}}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-3">Tanggal Peminjaman</div>
                            <div class="col-9">{{$riwayat->tgl_mulai_digunakan}}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-3">Kondisi Barang</div>
                            <div class="col-9">{{$riwayat->status_kondisi}}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-3">Status</div>
                            <div class="col-9"> <span class="bg-neon p-2 text-white">{{$riwayat->status}}</span> </div>
                        </div>
                    </div>
                </div>
                @if ($riwayat->status == 'digunakan')
                <div class="card border-0">
                    <div class="d-grid gap-2 col-6 mx-auto pb-3 mt-4">
                        <button class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#tambah_perbaikan" type="button">Ajukan Permohonan Perbaikan</button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach

    @if ($services->isNotEmpty())
    @foreach ($services as $service)
    {{-- informasi Service --}}
    <div class="container">
        <div class="row page_content page_10 justify-content-center">
            <div class="col-md-11">
                <div class="card shadow-sm rounded-0 border-0  mb-3">
                    <div class="card-body">
                        <div class="row mb-3">
                            <h6 class="fw-bold text-secondary">Informasi Service</h6><br>
                        </div>
                        <div class="row mb-3">
                            <div class="col-3">Status</div>
                            <div class="col-9"> <span class="bg-primary p-2 text-white">{{$service->status}}</span> </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="" class="form-label">Tanggal Pengajuan</label>
                                <input type="text" name="pengajuan" id="" class="form-control" value="{{$service->tgl_permohonan}}" readonly>
                            </div>
                            <div class="col-sm-4">
                                <label for="" class="form-label">Tanggal Pemeriksaan</label>
                                <input type="text" name="pemeriksaan" id="" class="form-control" value="{{$service->tgl_pemeriksaan}}" readonly>
                            </div>
                            <div class="col-sm-4">
                                <label for="" class="form-label">Tanggal Pengerjaan</label>
                                <input type="text" name="pengerjaan" id="" class="form-control" value="{{$service->tgl_pengerjaan}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
    @endforeach
    @endif

    <!-- Modal Perbaikan Barang -->
    @foreach ($riwayats as $riwayat)
    <form action="{{url('/c_permohonan')}}" method="post">
    @csrf
    <input type="hidden" name="idPenanggungJawab" value="{{$riwayat->idPenanggungJawab}}">
    <input type="hidden" name="idAsset" value="{{$riwayat->idAsset}}">
    <div class="modal fade" id="tambah_perbaikan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Permohonan Perbaikan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Nama Barang</label>
                    </div>
                    <div class="col-md-8">
                        {{$riwayat->nama_barang}}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Keterangan</label>
                    </div>
                    <div class="col-md-8">
                        {{$riwayat->nama_merek}}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Tanggal Peminjaman</label>
                    </div>
                    <div class="col-md-8">
                        {{$riwayat->tgl_mulai_digunakan}}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Kerusakan</label>
                    </div>
                    <div class="col-md-8">
                        <textarea class="form-control" id="" style="height: 100px" name="keterangan"></textarea>
                    </div>
                </div>
            </div>
                <div class="modal-footer">
                    <div class="d-grid gap-2 col-6 mx-auto">
                        <button class="btn btn-primary" type="submit">Ajukan Permohonan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
    @endforeach
    @endsection