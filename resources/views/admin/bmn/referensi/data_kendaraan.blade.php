@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>BMN / <span class="fw-bold">Data Kendaraan</span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body content">
  
                <!-- Data Penyedia - ALl -->
                <div class="row page_content page_1">
                    <div class="table-responsive">
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_kendaraan">
                            <i class="fa fa-plus"></i> Tambah
                        </button>  
                        <table id="example" class="table table-bordered data-table" style="width: 100%">
                            <thead>
                            <tr class="text-center small">
                                <th>No</th>
                                <th>Kendaraan</th>
                                <th>Nomor Polisi</th>
                                <th>Nomor Mesin</th>
                                <th>STNK</th>
                                <th>BPKB</th>
                                <th>Legalitas</th>
                                <th>Legalitas 5 Tahun</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            @foreach ($kendaraans as $kendaraan)
                            <tr>
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td>{{$kendaraan->merek}}</td>
                                <td class="text-center">{{$kendaraan->no_polisi}}</td>
                                <td class="text-center">{{$kendaraan->no_mesin}}</td>
                                <td class="text-center">{{$kendaraan->no_stnk}}</td>
                                <td class="text-center">{{$kendaraan->no_bpkb}}</td>
                                <td class="text-center">{{$kendaraan->legalitas}}</td>
                                <td class="text-center">{{$kendaraan->legalitas_5th}}</td>
                                <td class="text-center">{{$kendaraan->tipe}}</td>
                                <td class="text-center">{{$kendaraan->status}}</td>
                                <td class='text-center d-flex justify-content-center'>
                                    <span class="p-1">
                                        <a href="{{url('/data_kendaraan/detail/' . $kendaraan->id)}}" class="text-decoration-none btn btn-success"><i class="fa-solid fa-pen-to-square"></i></a>
                                    </span>
                                    <span class="p-1">
                                        <form action="{{url('/d_kendaraan/' . $kendaraan->id)}}" method="post">
                                            @csrf
                                            <button type="submit" class="text-decoration-none btn btn-danger" onclick="return confirm('Hapus Data?')"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </span>
                                    <span class="p-1">
                                        <a href="{{url('/service_kendaraan/' . $kendaraan->id)}}" class="text-decoration-none btn btn-warning text-white"><i class="fa-solid fa-screwdriver-wrench"></i> Ajukan Perbaikan</a>
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
  <div class="modal fade" id="tambah_kendaraan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{url('/c_bmn_kendaraan')}}" method="post">
            @csrf
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah Data Kendaraan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body col-md-12">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Nama Kendaraan</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="" name="merek" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">No Polisi</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="" name="no_polisi" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">No Mesin</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="" name="no_mesin" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">No STNK</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="" name="no_stnk" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">No BPKB</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="" name="no_bpkb" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Legalitas (tahunan)</label>
                    </div>
                    <div class="col-md-8">
                        <input type="date" class="form-control" id="" name="legalitas" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Legalitas (5 tahunan)</label>
                    </div>
                    <div class="col-md-8">
                        <input type="date" class="form-control" id="" name="legalitas5tahun" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Tipe</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="" name="tipe" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Status Kendaraan</label>
                    </div>
                    <div class="col-md-8">
                        <div class="input-group mb-3 submit-select">
                            <select class="form-select text-muted" id="inputGroupSelect01" name="status">
                                <option selected>Pilih Status</option>
                                <option value="baik">Baik</option>
                                <option value="rusak">Rusak</option>
                            </select>
                        </div>
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