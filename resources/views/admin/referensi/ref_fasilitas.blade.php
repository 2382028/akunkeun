@extends('admin.templates.sidebar')

@section('contain')
<!-- Awal Dashboard -->
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
              <h4>Data Fasilitas</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_versi">
                          <i class="fa fa-plus"></i> Tambah Fasilitas Baru
                      </button>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table id="example" class="table table-bordered data-table" style="width: 100%">
                      <thead>
                        <tr class="text-center small">
                          <th class="th-lg-percent">No</th>
                          <th class="th-lg">Nama Fasilitas</th>
                          <th class="th-sm">Satuan</th>
                          <th class="th-sm-percent">Terikat Pelaksana</th>
                          <th class="th-lg-percent">Status</th>
                          <th class="th-lg-percent">Aksi</th>
                        </tr>
                      </thead>
                      @foreach ($refFasilitas as $refFasilitas)
                      <tr>
                          <td class='text-center'>{{$loop->iteration}}</td>
                          <td class='text-center'>{{$refFasilitas->nama_fasilitas}}</td>
                          <td class='text-center'>{{$refFasilitas->satuan}}</td>
                          <td class='text-center'>
                              @if($refFasilitas->terikat_pelaksana == 1)
                              <span class="badge bg-success">YA</span>
                              @else
                              <span class="badge bg-danger">TIDAK</span>
                              @endif
                            </td>
                          <td class='text-center'>
                              @if($refFasilitas->status == 'aktif')
                              <span class="badge bg-success">AKTIF</span>
                              @else
                              <span class="badge bg-danger">NON-AKTIF</span>
                              @endif
                          </td>
                          <td class='text-center'>
                             <!-- Tombol untuk membuka modal -->
                             <button type="button" class="btn btn-warning btn-sm text-white"
                                    data-id-pajak="{{ $refFasilitas->id }}"
                                    data-bs-toggle="modal"
                                    onclick="showConfirmationEdit('{{$refFasilitas->id}}','{{$refFasilitas->nama_fasilitas}}','{{$refFasilitas->satuan}}','{{$refFasilitas->status}}','{{$refFasilitas->terikat_pelaksana}}')"
                                    data-bs-target="#editDataModal">
                                    <i class="fa fa-pen"></i>
                              </button>
                            <span>
                              <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmationDeleteModal" onclick="showConfirmationDelete('{{$refFasilitas->id}}')"><i class="fa-solid fa-trash"></i></button>
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

<!-- Modal Tambah Fasilitas -->
<div class="modal fade" id="tambah_versi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Data Referensi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body col-md-12">
          <form action="/c_refFasilitas" method="post">
          @csrf
          <div class="row mb-3">
              <div class="col-md-4">
            <label for="refFasilitas" class="form-label">Masukkan Nama Fasilitas <span class="text-danger">*</span></label>
              </div>
              <div class="col-md-8">
            <input type="text" class="form-control" id="refFasilitas" name="refFasilitas" required>
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-md-4">
            <label for="satuanFasilitas" class="form-label">Satuan Fasilitas <span class="text-danger">*</span></label>
              </div>
              <div class="col-md-8">
            <input type="text" class="form-control" id="satuanFasilitas" name="satuanFasilitas" required>
              </div>
          </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="terikatPelaksana" class="form-label">Terikat Pelaksana <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-8">
                <select class="form-select" id="terikatPelaksana" name="terikatPelaksana" required>
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
                <small class="form-text text-muted">Jika Fasilitas perlu terikat dengan Pelaksana pilih "Ya"</small>
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

</div>

<!-- Modal Delete Jenis Program -->
<div class="modal fade" id="confirmationDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="/del_refFasilitas" method="post">
    @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Penghapusan Data Fasilitas <input type="hidden" id="confIdDelete" name="getIdRefFasilitasDelete" value=""></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin akan menghapus Data Fasilitas?</p>
        <p class="fw-bold">Ketik "Ya, Saya yakin akan menghapus Data Fasilitas" di bawah ini : </p>
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

<!-- MODAL EDIT FASILITAS -->
<div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Data Fasilitas
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="/set_refFasilitas" method="post">
            @csrf
              <input type="hidden" id="confIdEdit" name="idFasilitas_edit" value="">
          <div class="row">
              <div class="col-md-12 mb-3 tab-pane fade show active required-select">
              <label for="" class="form-label">Nama Fasilitas<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_fasilitas_edit" name="nama_fasilitas_edit">
              </div>
          </div>
          <div class="row">
              <div class="col-md-12 mb-3 tab-pane fade show active required-select">
              <label for="" class="form-label">Satuan Fasilitas<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="satuan_edit" name="satuan_edit">
              </div>
          </div>
          <div class="row">
            <div class="col-md-12 mb-3 tab-pane fade show active required-select">
              <label for="status_edit" class="form-label">Status Fasilitas<span class="text-danger">*</span></label>
              <select class="form-select" id="status_edit" name="status_edit">
                <option value="aktif">Aktif</option>
                <option value="non-aktif">Non-aktif</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 mb-3 tab-pane fade show active required-select">
              <label for="terikatPelaksana_edit" class="form-label">Terikat Pelaksana<span class="text-danger">*</span></label>
              <select class="form-select" id="terikatPelaksana_edit" name="terikatPelaksana_edit">
                <option value="1">Ya</option>
                <option value="0">Tidak</option>
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

<script>
  function showConfirmation(hasil) {
      var input = document.getElementById('confId');
      input.setAttribute("value", hasil);
  }

  function showConfirmationDelete(hasil) {
      var input = document.getElementById('confIdDelete');
      input.setAttribute("value", hasil);
  }

  function showConfirmationEdit(id,nama_fasilitas,satuan,status,terikat_pelaksana) {
      var input_id = document.getElementById('confIdEdit');
      var input_nama_fasilitas = document.getElementById('nama_fasilitas_edit');
      var input_satuan = document.getElementById('satuan_edit');
      var input_status = document.getElementById('status_edit');
      var input_terikat_pelakasana = document.getElementById('terikatPelaksana_edit');
      input_id.setAttribute("value", id);
      input_nama_fasilitas.setAttribute("value", nama_fasilitas);
      input_satuan.setAttribute("value", satuan);
      input_status.value = status;
      input_terikat_pelakasana.value = terikat_pelaksana;
  }
</script>
@endsection
