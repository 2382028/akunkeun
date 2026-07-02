@extends('admin.templates.sidebar')

@section('contain')
<!-- Awal Dashboard -->
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
              <h4>Satuan</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_satuan">
                          <i class="fa fa-plus"></i> Tambah Satuan
                      </button>        
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table id="example" class="table table-bordered data-table" style="width: 100%">
                      <thead>
                        <tr class="text-center small">
                          <th class="th-lg-percent">No</th>
                          <th class="th-lg-percent">Kode</th>
                          <th class="th-lg-percent">Nama Satuan</th>
                          <th class="th-lg-percent">Status</th>
                          <th class="th-md  " style="min-width:150px;">Aksi</th>
                        </tr>
                      </thead>
                      @foreach ($satuans as $satuan)
                      <tr>
                          <td class='text-center'>{{$loop->iteration}}</td>
                          <td class='text-center'>{{$satuan->kode}}</td>
                          <td class='text-center'>{{$satuan->satuan}}</td>
                          <td class='text-center'>{{$satuan->status}}</td>
                          <td class='text-center'>
                            @if ($satuan->status == 'non-aktif')
                            <span>
                              <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#confirmationModal" onclick="showConfirmation('{{$satuan->id}}')">Aktifkan</button>
                            </span>
                            @else
                            <span>
                              <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmationModal" onclick="showConfirmation('{{$satuan->id}}')">Nonaktifkan</button>
                            </span>
                            @endif
                            <span>
                              <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmationDeleteModal" onclick="showConfirmationDelete('{{$satuan->id}}')"><i class="fa-solid fa-trash"></i></button>
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

<!-- Modal Tambah Satuan -->
<div class="modal fade" id="tambah_satuan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Penandatangan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/c_satuan" method="post">
          @csrf
          <div class="row">
              <div class="col-md-12 mb-3 tab-pane fade show active required-select">
              <label for="" class="form-label">Masukkan Kode Satuan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="" name="kode_satuan">
              </div>
          </div>
          <div class="row">
              <div class="col-md-12 mb-3 tab-pane fade show active required-select">
              <label for="" class="form-label">Masukkan Nama Satuan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="" name="nama_satuan">
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

<!-- Modal Aktifkan Satuan -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="/set_satuan" method="post">
    @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Perubahan Status Satuan <input type="hidden" id="confId" name="getIdSatuan" value=""></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin akan merubah status Satuan?</p>
        <p class="fw-bold">Ketik "Ya, Saya yakin akan merubah status Satuan" di bawah ini : </p>
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

<!-- Modal Delete Satuan -->
<div class="modal fade" id="confirmationDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="/del_satuan" method="post">
    @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Penghapusan Satuan <input type="hidden" id="confIdDelete" name="getIdSatuanDelete" value=""></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin akan menghapus Satuan?</p>
        <p class="fw-bold">Ketik "Ya, Saya yakin akan menghapus Satuan" di bawah ini : </p>
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