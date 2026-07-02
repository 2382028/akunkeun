@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>BMN / Data Aset / <span class="fw-bold">{{$asset->nama_barang}}</span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body content">
                    <form action="{{url('/up_data_asset')}}" method="post">
                    @csrf
                    <input type="hidden" name="idAsset" value="{{$asset->id}}">
                    <div class="modal-body col-md-12">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Kode Barang</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="kode" value="{{$asset->kode_barang}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Nama Barang</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="nama" value="{{$asset->nama_barang}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">NUP</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="nup" value="{{$asset->NUP}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Merek</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="merek" value="{{$asset->nama_merek}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Tanggal Pembelian</label>
                            </div>
                            <div class="col-md-8">
                                <input type="date" class="form-control" id="" name="tgl_beli" value="{{$asset->tgl_beli}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Jenis Perawatan</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-select text-muted" id="inputGroupSelect01" name="perawatan">
                                    <option value="{{$asset->jenis_perawatan}}" selected>{{$asset->jenis_perawatan}}</option>
                                    <option value="Berkala">Berkala</option>
                                    <option value="Sekali-kali">Sekali-kali</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Kondisi Aset</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-select text-muted" id="inputGroupSelect01" name="kondisi">
                                    <option value="{{$asset->status_kondisi}}" selected>{{$asset->status_kondisi}}</option>
                                    <option value="Baik">Baik</option>
                                    <option value="Rusak">Rusak</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="dapat_dipinjami" class="form-label">Dapat Dipinjami?</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-select" name="dapat_dipinjami" id="dapat_dipinjami">
                                    <option value="1" {{ $asset->kategori == '1' ? 'selected' : '' }}>Ya</option>
                                    <option value="0" {{ $asset->kategori == '0' ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="d-grid gap-2 d-md-flex justify-content-center">
                            <a href="{{url('/data_assets')}}" class="btn btn-dark">Kembali</a>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pinjam">Pinjamkan Asset</button>  
                            <button class="btn btn-success" type="submit">Perbaharui data</button>
                        </div>
                    </div>
                    </form>
  
                </div>
            </div>
        </div>
    </div>
  </div>

    <!-- Awal Modal Tambah Data Penyedia -->
    <div class="modal fade" id="pinjam" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{url('/c_bmn_asset_peminjaman')}}" method="post">
                @csrf
                <input type="hidden" class="form-control" id="" name="idAsset" value="{{$asset->id}}" readonly>
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Formulir Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body col-md-12">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="" class="form-label">Kode Barang</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="" name="kode" value="{{$asset->kode_barang}}" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="" class="form-label">Nama Barang</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="" name="nama" value="{{$asset->nama_barang}}" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="" class="form-label">Tanggal Peminjaman</label>
                        </div>
                        <div class="col-md-8">
                            <input type="date" class="form-control" id="" name="mulai" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="" class="form-label">Tanggal Selesai</label>
                        </div>
                        <div class="col-md-8">
                            <input type="date" class="form-control" id="" name="selesai">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="" class="form-label">Nama Penanggung Jawab</label>
                        </div>
                        <div class="col-md-8">
                            <select class="js-example-basic-single-5" aria-label="Default select example" style="width: 100%" name="penanggungjawab" required>
                                @foreach ($pegawais as $pegawai)
                                <option value="{{$pegawai->id}}" selected>{{$pegawai->nama_lengkap}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
            </div>
        </div>
    </div>
    <!-- Akhir Modal Tambah Data Penyedia -->

@endsection