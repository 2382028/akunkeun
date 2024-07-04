@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>BMN / <span class="fw-bold">Data Ruangan</span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body content">
  
                <!-- Data Penyedia - ALl -->
                <div class="row page_content page_1">
                    <div class="table-responsive">
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_ruangan">
                            <i class="fa fa-plus"></i> Tambah
                        </button>  
                        <table id="example" class="table table-bordered data-table" style="width: 100%">
                            <thead>
                            <tr class="text-center small">
                                <th class="th-sm">No</th>
                                <th class="th-md">Kode Ruangan</th>
                                <th class="th-md">Nama Ruangan</th>
                                <th class="th-md">Penanggung Jawab</th>
                                <th class="th-md">Kondisi</th>
                                <th class="th-lg-percent">Aksi</th>
                            </tr>
                            </thead>
                            @foreach ($ruangans as $ruangan)
                            <tr>
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td class="text-center">{{$ruangan->kode_ruangan}}</td>
                                <td>{{$ruangan->nama_ruangan}}</td>
                                <td>{{$ruangan->nama_lengkap}}</td>
                                <td class="text-center">{{$ruangan->kondisi}}</td>
                                <td class='text-center d-flex justify-content-center'>
                                    <span class="p-1">
                                        <a href="{{url('/data_ruangan/detail/' . $ruangan->id)}}" class="text-decoration-none btn btn-success"><i class="fa-solid fa-pen-to-square"></i></a>
                                    </span>
                                    <span class="p-1">
                                        <form action="{{url('/d_ruangan/' . $ruangan->id)}}" method="post">
                                            @csrf
                                            <button type="submit" class="text-decoration-none btn btn-danger" onclick="return confirm('Hapus Data Ruangan?')"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </span>
                                    <span class="p-1">
                                        <a href="{{url('/service_ruangan/' . $ruangan->id)}}" class="text-decoration-none btn btn-warning text-white"><i class="fa-solid fa-screwdriver-wrench"></i> Ajukan Perbaikan</a>
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
  <div class="modal fade" id="tambah_ruangan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{url('/c_bmn_ruangan')}}" method="post">
            @csrf
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah Data Ruangan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body col-md-12">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Kode Ruangan</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="" name="kode" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Nama Ruangan</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="" name="nama" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Nama Penanggung Jawab</label>
                    </div>
                    <div class="col-md-8">
                        <select class="js-example-basic-single-4" aria-label="Default select example" style="width: 100%" name="penanggungjawab">
                            @foreach ($pegawais as $pegawai)
                            <option value="{{$pegawai->id}}" selected>{{$pegawai->nama_lengkap}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Kondisi</label>
                    </div>
                    <div class="col-md-8">
                        <select class="form-select" aria-label="Default select example" name="kondisi">
                            <option value="Kondisi Baik" selected>Kondisi Baik</option>
                            <option value="Kondisi Rusak">Kondisi Rusak</option>
                            <option value="Tidak Digunakan">Tidak Digunakan</option>
                            <option value="Sedang Direnovasi">Sedang Direnovasi</option>
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