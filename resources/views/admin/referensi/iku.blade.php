@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <h4>Referensi / <span class="fw-bold">IKU</span></h4>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 mb-3">
      <div class="card">
        <div class="card-body content">
          <div class="row mb-3 page_content page_1">
            <div class="table-responsive">
              <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_iku">
                <i class="fa fa-plus"></i> Tambah
              </button>
              <table id="example" class="table table-bordered data-table" style="width: 100%">
                <thead>
                  <tr class="text-center small">
                    <th class="th-sm">No</th>
                    <th class="th-md">Nama Sasaran Strategis</th>
                    <th class="th-sm">Kode IKU</th>
                    <th class="th-md">Nama IKU</th>
                    <th class="th-md">Pokja</th>
                    <th class="th-md">Program Kerja</th>
                    <th class="th-lg-percent">Aksi</th>
                  </tr>
                </thead>
                @foreach ($IKU as $IKU)
                <tr>
                  <td class='text-center'>{{$IKU->id}}</td>
                  <td class='text-center'>{{$IKU->nama_ss}}</td>
                  <td class='text-center'>{{$IKU->kode_iku}}</td>
                  <td class='text-center'>{{$IKU->nama_iku}}</td>
                  <td class='text-center'>{{$IKU->pokja}}</td>
                  <td class='text-center'>{{$IKU->nama_program_kerja}}</td>
                  <td>
                    <div class='d-flex'>
                      <span class="p-1">
                        <a href="{{url('/detail_iku/' . $IKU->id)}}" class="btn btn-success"><i class="fa-solid fa-pen-to-square"></i></a>
                      </span>
                      <span class="p-1">
                        <form action="{{url('/d_iku/' . $IKU->id)}}" method="post">
                          @csrf
                          <button type="submit" class="text-decoration-none btn btn-danger" onclick="return confirm('Apakah Anda yakin akan menghapus data ini?')"><i class="fa-solid fa-trash"></i></button>
                        </form>
                      </span>
                  </td>
            </div>
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

<!-- Awal Tambah IKU -->
<div class="modal fade" id="tambah_iku" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{'c_iku'}}" method="post">
        @csrf
        <div class="modal-body">

            <div class="col-md-4">
              <label for="" class="form-label">Kode Sasaran Strategis</label>
            </div>
            <div class="col-md-8">
              <div class="input-group mb-3 submit-select">
                <select class="form-select text-muted" id="" name="kode_ss">
                  <option selected>Pilih Kode</option>
                  <option value="S1">S1</option>
                  <option value="S2">S2 </option>
                  <option value="S3">S3 </option>
                  <option value="S4">S4 </option>
                </select>
              </div>
              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="" class="form-label">Nama Sasaran Strategis</label>
                </div>
                <div class="form-floating col-md-8">
                  <textarea class="form-control" id="floatingTextarea" name="nama_ss"></textarea>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="" class="form-label">Kode IKU</label>
                </div>
                <div class="col-md-8">
                  <input type="text" class="form-control" id="floatingTextarea" name="kode_iku">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="" class="form-label">Nama IKU</label>
                </div>
                <div class="form-floating col-md-8">
                  <textarea class="form-control" id="floatingTextarea" name="nama_iku"></textarea>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="" class="form-label">Pokja</label>
                </div>
                <div class="col-md-8">
                  <div class="input-group mb-3 submit-select">
                    <select class="form-select text-muted" id="" name="pokja">
                      <option selected>Pilih Satuan</option>
                      <option value="FA">Fungsi Akademik, Penelitian, dan Pengabdian pada Masyarakat</option>
                      <option value="FD">Fungsi Pusat Data dan Sistem Informasi </option>
                      <option value="FK">Fungsi Perencanaan, Keuangan dan Barang Milik Negara </option>
                      <option value="FH">Fungsi Hukum Masyarakat, Tata Usaha and Kerjasama </option>
                      <option value="FM">Fungsi Kemahasiswaan, dan Tracer Study </option>
                      <option value="FT">Fungsi Hukum, Kepegawaian dan Tata Laksana </option>
                      <option value="FL">Fungsi Kelembagaan, Sarana dan Prasarana </option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="" class="form-label">Program Kerja</label>
                </div>
                <div class="col-md-8">
                  <textarea class="form-control" id="floatingTextarea" name="nama_program_kerja"></textarea>
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
  <!-- Akhir Tambah IKU -->
  @endsection