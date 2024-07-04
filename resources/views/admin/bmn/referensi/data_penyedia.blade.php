@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>BMN / <span class="fw-bold">Data Penyedia</span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body content">
  
                <!-- Data Penyedia - ALl -->
                <div class="row page_content page_1">
                    <div class="table-responsive">
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_datapenyedia">
                            <i class="fa fa-plus"></i> Tambah
                        </button>  
                        <table id="example" class="table table-bordered data-table" style="width: 100%">
                            <thead>
                            <tr class="text-center small">
                                <th class="th-sm">No</th>
                                <th class="th-md">NPWP</th>
                                <th class="th-md">Penanggung Jawab</th>
                                <th class="th-md">Nama CV</th>
                                <th class="th-md">No Telepon</th>
                                <th class="th-sm">Kategori</th>
                                <th class="th-md">Tahun Bergabung</th>
                                <th class="th-lg-percent">Aksi</th>
                            </tr>
                            </thead>
                            @foreach ($penyedias as $penyedia)
                            <tr>
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td class="text-center">{{$penyedia->NPWP}}</td>
                                <td>{{$penyedia->penanggung_jawab}}</td>
                                <td>{{$penyedia->nama_CV}}</td>
                                <td class="text-center">{{$penyedia->no_telp}}</td>
                                <td class="text-center">{{$penyedia->kategori}}</td>
                                <td class="text-center">{{$penyedia->tahun}}</td>
                                <td class='text-center d-flex justify-content-center'>
                                    <span class="p-1">
                                        <a href="{{url('/data_penyedia/detail/' . $penyedia->id)}}" class="text-decoration-none btn btn-success"><i class="fa-solid fa-pen-to-square"></i></a>
                                    </span>
                                    <span class="p-1">
                                        <form action="{{url('/d_penyedia/' . $penyedia->id)}}" method="post">
                                            @csrf
                                            <button type="submit" class="text-decoration-none btn btn-danger" onclick="return confirm('Hapus Data Penyedia?')"><i class="fa-solid fa-trash"></i></button>
                                        </form>
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
  <div class="modal fade" id="tambah_datapenyedia" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{url('/c_bmn_penyedia')}}" method="post">
            @csrf
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah Data Penyedia</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body col-md-12">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">NPWP</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="" name="NPWP">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Nama CV</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="" name="namaCV" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Nama Penanggung Jawab</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="" name="penanggungJawab" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Jabatan</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="" name="jabatan" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">No. Telepon</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="" name="telps" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Alamat</label>
                    </div>
                    <div class="col-md-8">
                        <textarea class="form-control" id="" rows="3" name="alamat" required></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Kategori</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="" name="kategori" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Tahun Gabung</label>
                    </div>
                    <div class="col-md-8">
                        <input type="date" class="form-control" id="" name="tahun" required>
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