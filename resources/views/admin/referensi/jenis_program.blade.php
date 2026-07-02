@extends('admin.templates.sidebar')

@section('contain')
<!-- Awal Dashboard -->
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
              <h4>Jenis Program</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_versi">
                          <i class="fa fa-plus"></i> Tambah Jenis Program
                      </button>        
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table id="example" class="table table-bordered data-table" style="width: 100%">
                      <thead>
                        <tr class="text-center small">
                          <th class="th-lg-percent">No</th>
                          <th class="th-md">Nama Jenis Program</th>
                          <th class="th-lg-percent">Status</th>
                          <th class="th-lg-percent">Aksi</th>
                        </tr>
                      </thead>
                      @foreach ($jenisPrograms as $jenisProgram)
                      <tr>
                          <td class='text-center'>{{$loop->iteration}}</td>
                          <td class='text-center'>{{$jenisProgram->nama_program}}</td>
                          <td class='text-center'>{{$jenisProgram->status_program}}</td>
                          <td class='text-center'>
                            @if ($jenisProgram->status_program == 'non-aktif')
                            <span>
                              <button type="button" class="btn btn-primary btn-sm" style="margin-right:62px;" data-bs-toggle="modal" data-bs-target="#confirmationModal" onclick="showConfirmation('{{$jenisProgram->id}}')">Aktifkan</button>
                            </span>
                            @else
                            <span>
                              <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmationModal" onclick="showConfirmation('{{$jenisProgram->id}}')">Jenis Program Aktif</button>
                            </span>
                            @endif
                            <span>
                              <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmationDeleteModal" onclick="showConfirmationDelete('{{$jenisProgram->id}}')"><i class="fa-solid fa-trash"></i></button>
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
<!-- Akhir Dashboard -->

<!-- Modal Tambah Versi -->
<div class="modal fade" id="tambah_versi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Versi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body col-md-12">
          <form action="/c_jenisProgram" method="post">
          @csrf
          <div class="row mb-3">
              <div class="col-md-4">
                  <label for="" class="form-label">Masukkan Jenis Program</label>
              </div>
              <div class="col-md-8">
                  <input type="text" class="form-control" id="" name="jenisProgram">
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

<!-- Modal Aktifkan Jenis Program -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="/set_jenisProgram" method="post">
    @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Perubahan Jenis Program <input type="hidden" id="confId" name="getIdjenisProgram" value=""></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin akan merubah Jenis Program?</p>
        <p class="fw-bold">Ketik "Ya, Saya yakin akan merubah Jenis Program" di bawah ini : </p>
        <input type="text" class="form-control" id="confirmationInput" name="conf">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Konfirmasi</button>
      </div>
    </div>
    </form>
  </div>
</div>

<!-- Modal Delete Jenis Program -->
<div class="modal fade" id="confirmationDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="/del_jenisProgram" method="post">
    @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Penghapusan Jenis Program <input type="hidden" id="confIdDelete" name="getIdjenisProgramDelete" value=""></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin akan menghapus Jenis Program?</p>
        <p class="fw-bold">Ketik "Ya, Saya yakin akan menghapus Jenis Program" di bawah ini : </p>
        <input type="text" class="form-control" id="confirmationInput" name="conf">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Konfirmasi</button>
      </div>
    </div>
    </form>
  </div>
</div>

<script>
  function showConfirmation(hasil) {
      var input = document.getElementById('confId');
      input.setAttribute("value", hasil);
  }

  function showConfirmationDelete(hasil) {
      var input = document.getElementById('confIdDelete');
      input.setAttribute("value", hasil);
  }
</script>
@endsection