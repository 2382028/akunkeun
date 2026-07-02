@extends('admin.templates.sidebar')

@section('contain')
<!-- Awal Dashboard -->
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
              <h4>Data Pangkat Golongan</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_data_pangkat">
                          <i class="fa fa-plus"></i> Tambah Data Pangkat
                      </button>        
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table id="example" class="table table-bordered data-table-jabatan" style="width: 100%">
                      <thead>
                        <tr class="text-center small">
                          <th class="th-sm">ID</th>
                          <th class="th-sm">Golongan</th>
                          <th class="th-md">Nama Pangkat</th>
                          <th class="th-sm" style="min-width:50px;">Aksi</th>
                        </tr>
                      </thead>
                      <script>
                        $(document).ready(function () {
                            var t = $('.data-table-jabatan').DataTable({
                                columnDefs: [
                                    {
                                        searchable: false,
                                        orderable: false,
                                        targets: 0,
                                    },
                                ],
                                order: [['asc']],
                            });
                        
                            t.on('order.dt search.dt', function () {
                                let i = 1;
                        
                                t.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
                                    this.data(i++);
                                });
                            }).draw();
                        });
                      </script>
                      @foreach ($pangkats as $pangkat)
                      <tr>
                          <td class='text-center'>{{$pangkat->id}}</td>
                            <td class='text-center'>{{$pangkat->golongan}}</td>
                            <td class='text-center'>{{$pangkat->nama_pangkat}}</td>
                          <td class='text-center'>
                            <span>
                              <!-- Tombol untuk membuka modal -->
                              <button type="button" class="btn btn-warning btn-sm text-white"
                                    data-id-pangkat="{{ $pangkat->id }}"
                                    data-bs-toggle="modal" 
                                    onclick="showConfirmationEdit('{{$pangkat->id}}','{{$pangkat->golongan}}','{{$pangkat->nama_pangkat}}')"
                                    data-bs-target="#edit_data_pangkat">
                                    <i class="fa fa-pen"></i>
                              </button>
                            </span>
                            <span>
                              <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmationDeleteModal" onclick="showConfirmationDelete('{{$pangkat->id}}')"><i class="fa-solid fa-trash"></i></button>
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

<!-- Modal Tambah Data Pangkat -->
<div class="modal fade" id="tambah_data_pangkat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Data Pangkat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/c_pangkat" method="post">
          @csrf
          <div class="row">
              <div class="col-md-12 mb-3 tab-pane fade show active required-select">
              <label for="" class="form-label">Masukkan Golongan <span class="text-danger">*</span></label>
                <select name="golongan" class="form-select text-muted" id="InputJabatan" required>
                    <option selected>Pilih Golongan</option>
                    @foreach ($golongans as $golongan)
                    <option value="{{ $golongan->golongan }}">{{ $golongan->golongan }}</option>
                    @endforeach
                </select>
              </div>
          </div>
          <div class="row">
              <div class="col-md-12 mb-3 tab-pane fade show active required-select">
              <label for="" class="form-label">Masukkan Nama Pangkat <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="" name="nama_pangkat" required>
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

<!-- Modal Edit Data Pangkat -->
<div class="modal fade" id="edit_data_pangkat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Data Jabatan
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="/set_pangkat" method="post">
            @csrf
            <input type="hidden" id="confIdEdit" name="idPangkat_edit" value="">
            <div class="row">
              <div class="col-md-12 mb-3 tab-pane fade show active required-select">
              <label for="" class="form-label">Golongan <span class="text-danger">*</span></label>
                <select name="golongan_edit" class="form-select text-muted" id="golongan_edit" required>
                    @foreach ($golongans as $golongan)
                    <option value="{{ $golongan->golongan }}">{{ $golongan->golongan }}</option>
                    @endforeach
                </select>
              </div>
          </div>
          <div class="row">
              <div class="col-md-12 mb-3 tab-pane fade show active required-select">
              <label for="" class="form-label">Nama Pangkat<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_pangkat_edit" name="nama_pangkat_edit" required>
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

<!-- Modal Delete Pangkat -->
<div class="modal fade" id="confirmationDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="/del_pangkat" method="post">
    @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Penghapusan Data Bank <input type="hidden" id="confIdDelete" name="getIdPangkatDelete" value=""></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin akan menghapus Data Perangkat?</p>
        <p class="fw-bold">Ketik "Ya, Saya yakin akan menghapus Data Perangkat" di bawah ini : </p>
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
  function showConfirmationEdit(id,golongan,nama_pangkat) {
      var input_id = document.getElementById('confIdEdit');
      var input_golongan = document.getElementById('golongan_edit');
      var input_nama_pangkat = document.getElementById('nama_pangkat_edit');
      input_id.setAttribute("value", id);
      input_golongan.value = golongan;
      input_nama_pangkat.setAttribute("value", nama_pangkat);
  }
</script>
@endsection