@extends('admin.templates.sidebar')

@section('contain')
<!-- Awal Dashboard -->
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
              <h4>Pengaturan</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_versi">
                          <i class="fa fa-plus"></i> Tambah Versi
                      </button>        
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table id="example" class="table table-bordered data-table" style="width: 100%">
                      <thead>
                        <tr class="text-center small">
                          <th class="th-lg-percent">No</th>
                          <th class="th-md">Nama Versi</th>
                          <th class="th-lg-percent">Status</th>
                          <th class="th-lg-percent">Aksi</th>
                        </tr>
                      </thead>
                      @foreach ($versis as $versi)
                      <tr>
                          <td class='text-center'>{{$loop->iteration}}</td>
                          <td class='text-center'>{{$versi->versi}}</td>
                          <td class='text-center'>{{$versi->status}}</td>
                          <td class='text-center d-flex justify-content-evenly'>
                            @if ($versi->status == 'non-aktif')
                            <span>
                              <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#confirmationModal" onclick="showConfirmation('{{$versi->id}}')">Aktifkan</button>
                            </span>
                            @else
                            <span>
                              <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal">Versi Aktif</button>
                            </span>
                            @endif
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
          <form action="/c_pengaturan" method="post">
          @csrf
          <div class="row mb-3">
              <div class="col-md-4">
                  <label for="" class="form-label">Masukkan Versi</label>
              </div>
              <div class="col-md-8">
                  <input type="text" class="form-control" id="" name="versi">
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

<!-- Modal Aktifkan Versi -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="/set_versi" method="post">
    @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Perubahan Versi <input type="text" id="confId" name="getIdVersi" value=""></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin akan merubah versi?</p>
        <p class="fw-bold">Ketik "Ya, Saya yakin akan merubah versi" di bawah ini : </p>
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
</script>
@endsection