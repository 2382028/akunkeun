@extends('admin.templates.sidebar')

@section('contain')
<!-- Awal Dashboard -->
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
              <h4>Data Penandatangan</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_penandatangan">
                          <i class="fa fa-plus"></i> Tambah Penandatangan
                      </button>        
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table id="example" class="table table-bordered data-table" style="width: 100%">
                      <thead>
                        <tr class="text-center small">
                          <th class="th-lg-percent">No</th>
                          <th class="th-lg-percent">NIP</th>
                          <th class="th-md">Nama Pegawai</th>
                          <th class="th-lg-percent">Posisi</th>
                          <th class="th-lg-percent">Status</th>
                          <th class="th-lg-percent" style="min-width:100px;">Aksi</th>
                        </tr>
                      </thead>
                      @foreach ($penandatangans as $penandatangan)
                      <tr>
                          <td class='text-center'>{{$loop->iteration}}</td>
                          <td class='text-center'>{{$penandatangan->NIP_NIK}}</td>
                          <td class='text-center'>{{$penandatangan->nama_lengkap}}</td>
                          <td class='text-center'>{{$penandatangan->posisi_penandatangan}}</td>
                          <td class='text-center'>{{$penandatangan->status_penandatangan}}</td>
                          <td class='text-center'>
                            @if ($penandatangan->status_penandatangan == 'non-aktif')
                            <span>
                              <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#confirmationModal" onclick="showConfirmation('{{$penandatangan->id}}')">Aktifkan</button>
                            </span>
                            @else
                            <span>
                              <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmationModal" onclick="showConfirmation('{{$penandatangan->id}}')">Nonaktifkan</button>
                            </span>
                            @endif
                            <span>
                              <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmationDeleteModal" onclick="showConfirmationDelete('{{$penandatangan->id}}')"><i class="fa-solid fa-trash"></i></button>
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

<!-- Modal Tambah Penandatangan -->
<div class="modal fade" id="tambah_penandatangan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Penandatangan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/c_penandatangan" method="post">
          @csrf
          <div class="row">
              <div class="col-md-12 mb-3 tab-pane fade show active required-select">
              <label for="" class="form-label">Masukkan Penandatangan <span class="text-danger">*</span></label>
                  <select class="form-select" class="" name="penandatangan" style="width: 100%;">
                      <option value="">Pilih Pegawai</option>
                      @foreach ($pegawais as $pegawai)
                          
                          <option value="{{ $pegawai->id }}">{{ $pegawai->nama_lengkap }}</option>
                          
                      @endforeach
                  </select>
              </div>
          </div>
          <div class="row">
              <div class="col-md-12 mb-3 tab-pane fade show active required-select">
              <label for="" class="form-label">Masukkan Posisi Penandatangan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="" name="posisi_penandatangan">
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
    <form action="/set_penandatangan" method="post">
    @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Perubahan Status Penandatangan <input type="hidden" id="confId" name="getIdPenandatangan" value=""></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin akan merubah status Penandatangan?</p>
        <p class="fw-bold">Ketik "Ya, Saya yakin akan merubah status Penandatangan" di bawah ini : </p>
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

<!-- Modal Delete Penandatangan -->
<div class="modal fade" id="confirmationDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="/del_penandatangan" method="post">
    @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Penghapusan Penandatangan <input type="hidden" id="confIdDelete" name="getIdPenandatanganDelete" value=""></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin akan menghapus Penandatangan?</p>
        <p class="fw-bold">Ketik "Ya, Saya yakin akan menghapus Penandatangan" di bawah ini : </p>
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