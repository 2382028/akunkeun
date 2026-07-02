@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>BMN / <span class="fw-bold">Data Aset</span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body content">
  
                <!-- Data Penyedia - ALl -->
                <div class="row page_content page_1">
                    <div class="table-responsive">
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_asset">
                            <i class="fa fa-plus"></i> Tambah
                        </button>  
                        <table id="example" class="table table-bordered data-table" style="width: 100%">
                            <thead>
                            <tr class="text-center small">
                                <th class="th-sm">No</th>
                                <th class="th-md">Kode Barang</th>
                                <th class="th-sm">NUP</th>
                                <th style="min-width: 250px">Nama Barang</th>
                                <th style="min-width: 250px">Merek</th>
                                <th class="th-md">Tanggal Beli</th>
                                <th class="th-md">Jenis Perawatan</th>
                                <th class="th-md">Kondisi</th>
                                <th class="th-md">Status Peminjaman</th>
                                <th class="th-md">Dapat diPinjami?</th>
                                <th class="" style="min-width: 250px">Aksi</th>
                            </tr>
                            </thead>
                            @foreach ($assets as $asset)
                            <tr class="">
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td class="text-center">{{$asset->kode_barang}}</td>
                                <td class="text-center">{{$asset->NUP}}</td>
                                <td>{{$asset->nama_barang}}</td>
                                <td>{{$asset->nama_merek}}</td>
                                <td class="text-center">{{$asset->tgl_beli}}</td>
                                <td>{{$asset->jenis_perawatan}}</td>
                                <td class="text-center">{{$asset->status_kondisi}}</td>
                                <td class="text-center">{{$asset->status_peminjaman}}</td>
                                <td class="text-center">
                                    @if ($asset->kategori == '1')
                                        Ya
                                    @else
                                        Tidak
                                    @endif
                                </td>
                                <td class='text-center d-flex justify-content-center'>
                                    <span class="p-1">
                                        <a href="{{url('/data_asset/detail/' . $asset->id)}}" class="text-decoration-none btn btn-success"><i class="fa-solid fa-pen-to-square"></i></a>
                                    </span>
                                    <span class="p-1">
                                        <form action="{{url('/d_asset/' . $asset->id)}}" method="post">
                                            @csrf
                                            <button type="submit" class="text-decoration-none btn btn-danger" onclick="return confirm('Hapus Data?')"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </span>
                                    <span class="p-1">
                                        <a href="{{url('/service/asset/' . $asset->id)}}" class="text-decoration-none btn btn-warning text-white"><i class="fa-solid fa-screwdriver-wrench"></i> Ajukan Perbaikan</a>
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
  </div>

  <!-- Awal Modal Tambah Data Penyedia -->
  <div class="modal fade" id="tambah_asset" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{url('/c_bmn_asset')}}" method="post">
            @csrf
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah Data Aset</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body col-md-12">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Kode Barang</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="" name="kode" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Nama Barang</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="" name="nama" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">NUP</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="" name="nup" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Merek</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="" name="merek" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Tanggal Pembelian</label>
                    </div>
                    <div class="col-md-8">
                        <input type="date" class="form-control" id="" name="tgl_beli">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Jenis Perawatan</label>
                    </div>
                    <div class="col-md-8">
                        <select class="form-select text-muted" id="inputGroupSelect01" name="perawatan">
                            <option selected>Pilih Status</option>
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
                            <option selected>Pilih Status</option>
                            <option value="Baik">Baik</option>
                            <option value="Rusak">Rusak</option>
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