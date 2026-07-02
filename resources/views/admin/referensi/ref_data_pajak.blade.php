@extends('admin.templates.sidebar')

@section('contain')
<!-- Awal Dashboard -->
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
              <h4>Data Pajak</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_data_pajak">
                          <i class="fa fa-plus"></i> Tambah Data Pajak
                      </button>        
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table id="example" class="table table-bordered data-table" style="width: 100%">
                      <thead>
                        <tr class="text-center small">
                          <th class="th-lg-percent">No</th>
                          <th class="th-lg-percent">Status</th>
                          <th class="th-lg-percent">Golongan</th>
                          <th class="th-lg-percent">Tarif Pajak</th>
                          <th class="th-sm" style="min-width:50px;">Aksi</th>
                        </tr>
                      </thead>
                      @foreach ($dataPajaks as $dataPajak)
                      <tr>
                          <td class='text-center'>{{$loop->iteration}}</td>
                          <td class='text-center'>{{$dataPajak->status}}</td>
                            <td class='text-center'>{{$dataPajak->golongan}}</td>
                            <td class='text-center'>
                            <div class="d-flex justify-content-center align-items-center">
                              <span class="fw-bold">{{$dataPajak->tarif_pajak * 100}} %</span>
                            </div>
                            </td>
                          <td class='text-center'>
                            <span>
                              <!-- Tombol untuk membuka modal -->
                              <button type="button" class="btn btn-warning btn-sm text-white"
                                    data-id-pajak="{{ $dataPajak->no }}"
                                    data-bs-toggle="modal" 
                                    onclick="showConfirmationEdit('{{$dataPajak->no}}','{{$dataPajak->status}}','{{$dataPajak->golongan}}','{{$dataPajak->tarif_pajak}}')"
                                    data-bs-target="#edit_data_pajak">
                                    <i class="fa fa-pen"></i>
                              </button>
                            </span>
                            <span>
                              <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmationDeleteModal" onclick="showConfirmationDelete('{{$dataPajak->no}}')"><i class="fa-solid fa-trash"></i></button>
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

<!-- Modal Tambah Data Pajak -->
<div class="modal fade" id="tambah_data_pajak" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Data Pajak</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/c_data_pajak" method="post">
          @csrf
          <div class="row">
              <div class="col-md-12 mb-3 tab-pane fade show active required-select">
              <label for="" class="form-label">Masukkan Status <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="" name="status_data_pajak">
              </div>
          </div>
          <div class="row">
              <div class="col-md-12 mb-3 tab-pane fade show active required-select">
              <label for="" class="form-label">Masukkan Golongan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="" name="golongan_data_pajak">
              </div>
          </div>
            <div class="row">
              <div class="col-md-12 mb-3 tab-pane fade show active required-select">
              <label for="" class="form-label">Masukkan Tarif Pajak <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="number"
                step="0.1"
                class="form-control" id="" name="tarifPajak_data_pajak">
                <span class="input-group-text" id="basic-addon2">%</span>
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

<!-- Modal Edit Data Pajak -->
<div class="modal fade" id="edit_data_pajak" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Data Pajak
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="/set_data_pajak" method="post">
            @csrf
              <input type="hidden" id="confIdEdit" name="noPajak_edit" value="">
          <div class="row">
              <div class="col-md-12 mb-3 tab-pane fade show active required-select">
              <label for="" class="form-label">Edit Status <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="status_data_pajak_edit" name="status_data_pajak_edit">
              </div>
          </div>
          <div class="row">
              <div class="col-md-12 mb-3 tab-pane fade show active required-select">
              <label for="" class="form-label">Edit Golongan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="golongan_data_pajak_edit" name="golongan_data_pajak_edit">
              </div>
          </div>

          <div class="row">
              <div class="col-md-12 mb-3 tab-pane fade show active required-select">
              <label for="" class="form-label">Edit Tarif Pajak <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="number"
                step="0.1"
                class="form-control" id="tarifPajak_data_pajak_edit" name="tarifPajak_data_pajak_edit">
                <span class="input-group-text" id="basic-addon2">%</span>
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


<!-- Modal Delete Data Pajak -->
<div class="modal fade" id="confirmationDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="/del_data_pajak" method="post">
    @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Penghapusan Satuan 
          <input type="hidden" id="confIdDelete" name="getIdSatuanDelete" value="">
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin akan menghapus Data Pajak?</p>
        <p class="fw-bold">Ketik "Ya, Saya yakin akan menghapus Data Pajak" di bawah ini : </p>
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
  function showConfirmationEdit(id,status,gol,tarif_pajak) {
      var input_id = document.getElementById('confIdEdit');
      var input_status = document.getElementById('status_data_pajak_edit');
      var input_gol = document.getElementById('golongan_data_pajak_edit');
      var input_tarif_pajak = document.getElementById('tarifPajak_data_pajak_edit');
      input_id.setAttribute("value", id);
      input_status.setAttribute("value", status);
      input_gol.setAttribute("value", gol);
      input_tarif_pajak.setAttribute("value", tarif_pajak*100);
  }
</script>
@endsection