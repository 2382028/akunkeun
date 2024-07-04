@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <h4>Referensi / <span class="fw-bold">SBM</span></h4>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 mb-3">
      <div class="card">
        <div class="card-body content">
          <div class="row mb-3 page_content page_1">
            <div class="table-responsive">
              <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_sbm">
                <i class="fa fa-plus"></i> Tambah
              </button>
              <table id="example" class="table table-bordered data-table" style="width: 100%">
                <thead>
                  <tr class="text-center small">
                    <th class="th-sm">No</th>
                    <th class="th-sm">Kode</th>
                    <th class="th-md">Uraian</th>
                    <th class="th-md">Satuan</th>
                    <th class="th-md">Besaran</th>
                    <th class="th-lg-percent">Aksi</th>
                  </tr>
                </thead>
                @foreach ($sbms as $sbm)
                <tr>
                  <td class='text-center'></td>
                  <td class='text-center'>{{$sbm->kode_sbm}}</td>
                  <td class='text-center'>{{$sbm->uraian}}</td>
                  <td class='text-center'>{{$sbm->satuan}}</td>
                  <td class='text-center'>{{$sbm->biaya}}</td>
                  <td class='d-flex justify-content-center flex-row'>
                    <span class="p-1">
                      <a href="{{url('/detail_sbm/' . $sbm->id)}}" class="btn btn-success"><i class="fa-solid fa-pen-to-square"></i></a>
                    </span>
                    <span class="p-1">
                      <form action="{{url('/d_sbm/' . $sbm->id)}}" method="post">
                        @csrf
                        <button type="submit" class="text-decoration-none btn btn-danger" onclick="return confirm('Apakah Anda yakin akan menghapus data ini?')"><i class="fa-solid fa-trash"></i></button>
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

<!-- Awal Tambah SBM -->
<div class="modal fade" id="tambah_sbm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{'c_sbm'}}" method="post">
        @csrf
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="" class="form-label">Kode SBM</label>
            </div>
            <div class="col-md-8">
              <input type="text" class="form-control" id="" name="kode">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="" class="form-label">Uraian</label>
            </div>
            <div class="form-floating col-md-8">
              <textarea class="form-control" id="floatingTextarea" name="uraian"></textarea>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <label for="" class="form-label">Satuan</label>
            </div>
            <div class="col-md-8">
              <div class="input-group mb-3 submit-select">
                <select class="form-select text-muted" id="" name="satuan">
                  <option selected>Pilih Satuan</option>
                  <option value="OH">OH</option>
                  <option value="OB">OB</option>
                  <option value="OJ">OJ</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <label for="" class="form-label">Besaran</label>
            </div>
            <div class="col-md-8">
              <input type="text" class="form-control" id="" name="nominal">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </div>
    </form>
  </div>
</div>
<!-- Akhir Tambah SBM -->
@endsection