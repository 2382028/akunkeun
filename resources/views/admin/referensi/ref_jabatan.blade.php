@extends('admin.templates.sidebar')

@section('contain')
<!-- Awal Dashboard -->
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
              <h4>Data Jabatan</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_data_jabatan">
                          <i class="fa fa-plus"></i> Tambah Data Jabatan
                      </button>        
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table id="example" class="table table-bordered data-table-jabatan" style="width: 100%">
                      <thead>
                        <tr class="text-center small">
                          <th class="th-sm">ID</th>
                          <th class="th-md">Nama Jabatan</th>
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
                      @foreach ($jabatans as $jabatan)
                      <tr>
                          <td class='text-center'>{{$jabatan->id}}</td>
                            <td class='text-center'>{{$jabatan->nama_jabatan}}</td>
                          <td class='text-center'>
                            <span>
                              <!-- Tombol untuk membuka modal -->
                              <button type="button" class="btn btn-warning btn-sm text-white"
                                    data-id-jabatan="{{ $jabatan->id }}"
                                    data-bs-toggle="modal" 
                                    onclick="showConfirmationEdit('{{$jabatan->id}}','{{$jabatan->nama_jabatan}}')"
                                    data-bs-target="#edit_data_jabatan">
                                    <i class="fa fa-pen"></i>
                              </button>
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
<div class="modal fade" id="tambah_data_jabatan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Data Jabatan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/c_jabatan" method="post">
          @csrf
          <div class="row">
              <div class="col-md-12 mb-3 tab-pane fade show active required-select">
              <label for="" class="form-label">Masukkan Nama Jabatan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="" name="nama_jabatan">
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

<!-- Modal Edit Data Jabatan -->
<div class="modal fade" id="edit_data_jabatan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Data Jabatan
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="/set_jabatan" method="post">
            @csrf
          <div class="row">
              <div class="col-md-12 mb-3 tab-pane fade show active required-select">
              <label for="" class="form-label">ID Jabatan<span class="text-danger">*</span></label>
                <input type="text" id="confIdEdit" class="form-control"name="idJabatan_edit" value="" readonly>
              </div>
          </div>
          <div class="row">
              <div class="col-md-12 mb-3 tab-pane fade show active required-select">
              <label for="" class="form-label">Nama Jabatan<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_jabatan_edit" name="nama_jabatan_edit">
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
  function showConfirmationEdit(id,nama_jabatan) {
      var input_id = document.getElementById('confIdEdit');
      var input_nama_jabatan = document.getElementById('nama_jabatan_edit');
      input_id.setAttribute("value", id);
      input_nama_jabatan.setAttribute("value", nama_jabatan);
  }
</script>
@endsection