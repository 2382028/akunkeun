@extends('admin.templates.sidebar')

@section('contain')
<!-- Awal Dashboard - Kegiatan - HKT -->
<div class="container-fluid px-4 py-4">
  <div class="row">
    <div class="col-md-12">
      <h4>Kegiatan / <span class="fw-bold">HKT Kegiatan</span></h4>
    </div>
  </div>

  @if(session('error'))
  <div class="alert alert-danger">
    {{ session('error') }}
  </div>
  @endif

  <div class="row">
    <div class="col-md-12 mb-3">
      <div class="card border-0 bg-secondary">
        <div class="page wrapper">
          <a href="{{url('/kegiatan-HKT/pengajuan')}}" class="page-wrap btn btn-sm btn-primary">Pengajuan</a>
          <a href="{{url('/kegiatan-HKT/revisi')}}" class="page-wrap btn btn-sm btn-info text-white">Revisi</a>
          <a href="{{url('/kegiatan-HKT/ditolak')}}" class="page-wrap btn btn-sm btn-danger">Ditolak</a>
          <a href="{{url('/kegiatan-HKT/selesai')}}" class="page-wrap btn btn-sm btn-success">Selesai</a>
        </div>
      </div>
    </div>
  </div>

<!-- Modal update dokumen kegiatan -->
<div class="modal fade" id="upload_surat_update" tabindex="-1" aria-labelledby="upload_suratLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="upload_suratLabel">Upload Surat Tugas</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{url('/u_dok_kegiatan_HKT')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="idKegiatanUpdate" id="upload_update_id_kegiatan" value="">
                    <input type="hidden" name="kegiatanStatusUpdate" id="upload_update_kegiatan_status" value="">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="uraian" class="form-label">Masukan Nomor Surat Tugas<span class="text-secondary small"></span><span class="text-danger">*</span></label>
                            <input type="text" class="form-control mt-1" id="update_no_surtug" name="nomor_surtug_update" placeholder="Masukan Nomor Surat Tugas" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="uraian" class="form-label">Masukan Tanggal Surat<span class="text-secondary small"></span><span class="text-danger">*</span></label>
                            <input type="date" class="form-control mt-1" id="update_tgl_surtug" name="tgl_dibuat_update" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="uraian" class="form-label">Masukan Surat Tugas<span class="text-secondary small"></span><span class="text-danger">*</span></label>
                            <input type="file" name="surat_tugas_update" id="fileInput" class="form-control" accept="application/pdf" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tolak Surat -->
<div class="modal fade" id="tolak_surat" tabindex="-1" aria-labelledby="tolak_suratLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tolak_suratLabel">Tolak Surat Tugas</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{url('/t_kegiatan_HKT')}}" method="post">
                    @csrf
                    <input type="hidden" name="idKegiatan" id="tolak_id_kegiatan" value="">
                    <input type="hidden" name="kegiatanStatus" id="tolak_kegiatan_status" value="">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="uraian" class="form-label">Masukan Alasan<span class="text-secondary small"></span><span class="text-danger">*</span></label>
                            <input type="text" id="tolak" name="alasan" class="form-control" placeholder="Alasan Penolakan" required>
                        </div>
                    </div>
                    <!-- Penutupan form harus di sini -->
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                  </div>
                </form>
        </div>
    </div>
</div>

<!-- Tabel Data Kegiatan -->
<div class="row">
  <div class="col-md-12 mb-3">
    <div class="card">
      <div class="card-body content">
        <div class="row page_content card-style">
          <div class="table-responsive">
            <div class="col-md-12 mb-3 text-end">
              <button id="exportButton" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#dateRangeModal">
                <i class="fa-solid fa-print"></i> Rekap Kegiatan
              </button>
            </div>
            <table id="example" class="table table-bordered data-table" style="width: 100%">
              <thead>
                <tr class="text-center small">
                  <th class="th-sm small">No</th>
                  <th class="th-sm small">ID Kegiatan</th>
                  <th class="th-sm small">Tgl Usulan</th>
                  <th class="th-sm small">Pengusul</th>
                  <th class="th-sm small">Nama Kegiatan</th>
                  <th class="th-sm small">Tujuan</th>
                  <th class="th-sm small">Tanggal <br>Pelaksanaan</th>
                  <th class="th-sm small">No Surtug</th>
                  <th class="th-sm small">Tanggal Surtug</th>
                  <th class="th-md" style="min-width: 150px;">Aksi</th>
                </tr>
              </thead>

              <tbody>
                @foreach ($kegiatans as $kegiatan)
                <tr>
                    <td class="text-center small">{{$loop->iteration}}</td>
                    <td class="th-sm small">{{$kegiatan->id}}</td>
                    <td class="th-sm small"> {{\Carbon\Carbon::parse($kegiatan->created_at)->format('d-m-Y H:i')}}</td>
                    <td class="th-sm small">{{$kegiatan->nama_pengaju}}</td>
                    <td class="th-sm small">{{$kegiatan->nama_kegiatan}}</td>
                    <td class="text-center th-sm small">{{$kegiatan->alamat}}</td>
                    <td class="text-center th-sm small"> {{\Carbon\Carbon::parse($kegiatan->tgl_mulai)->format('d-m-Y H:i')}} s.d <br>  {{\Carbon\Carbon::parse($kegiatan->tgl_selesai)->format('d-m-Y H:i')}}</td>
                    <td class="text-center th-sm small">{{$kegiatan->nomor_surat}}</td>
                    <td class="text-center th-sm small"> {{\Carbon\Carbon::parse($kegiatan->tgl_surat_dibuat)->format('d-m-Y H:i')}}</td>
                    <td class="th-sm small">
                        <div class="">
                            @if ($kegiatan->is_acceptHKT == 'pengajuan')
                            @php
                            // Cek apakah id_kegiatan ada di dalam koleksi surtugExist
                            $exists = $surtugExist->contains($kegiatan->id);
                            @endphp
                            @if (!$exists)
                            <!-- Tombol untuk menampilkan aksi -->
                            <span class="p-1">
                                <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#actionsCollapse_{{$kegiatan->id}}" aria-expanded="false" aria-controls="actionsCollapse_{{$kegiatan->id}}">
                                    <i class="fa-solid fa-bars"></i> Lihat Aksi
                                </button>
                            </span>

                            <!-- Elemen-elemen tersembunyi (aksi) -->
                            <div class="collapse mt-2" id="actionsCollapse_{{$kegiatan->id}}">
                                <div class="p-1">
                                    <a href="{{url('/kegiatan-HKT/detail/' . $kegiatan->id)}}" class="btn btn-info btn-sm">
                                        <i class="fa-solid fa-eye"></i> Lihat Data
                                    </a>
                                </div>
                                <div class="p-1">
                                    <a href="{{url('/kegiatan-HKT/surtug/' . $kegiatan->id)}}" class="btn btn-success btn-sm">
                                        <i class="fa-solid fa-pen-to-square"></i> Buat Surat Tugas
                                    </a>
                                </div>
                                <div class="p-1">
                                    <button class="btn btn-secondary btn-sm" disabled>
                                        <i class="fa-solid fa-upload"></i> Upload
                                    </button>
                                </div>
                                <div class="p-1">
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#tolak_surat" data-id-kegiatan-tolak="{{ $kegiatan->id }}">
                                        <i class="fa-solid fa-exclamation-circle"></i> Tolak Surat
                                    </button>
                                </div>
                            </div>


                            @else
                            <!-- Tombol untuk menampilkan aksi -->
                            <span class="p-1">
                                <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#actionsCollapse2_{{$kegiatan->id}}" aria-expanded="false" aria-controls="actionsCollapse2_{{$kegiatan->id}}">
                                    <i class="fa-solid fa-bars"></i> Lihat Aksi
                                </button>
                            </span>

                            <!-- Elemen-elemen tersembunyi (aksi) -->
                            <div class="collapse mt-2" id="actionsCollapse2_{{$kegiatan->id}}">
                                <div class="p-1">
                                    <a href="{{url('/kegiatan-HKT/detail/' . $kegiatan->id)}}" class="btn btn-info btn-sm">
                                        <i class="fa-solid fa-eye"></i> Lihat Data
                                    </a>
                                </div>
                                <div class="p-1">
                                    <a href="{{ url('/kegiatan-HKT/surtug/edit/' . $kegiatan->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit Surat Tugas
                                    </a>
                                </div>
                                <div class="p-1">
                                    <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#upload_tte" data-id-kegiatan-tte="{{ $kegiatan->id }}">
                                        <i class="fa-solid fa-file me-1"></i> Proses TTE
                                    </button>
                                </div>
                                <div class="p-1">
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#upload_surat" data-id-kegiatan="{{ $kegiatan->id }}" data-no-surtug="{{ $kegiatan->nomor_surat }}" data-tgl-surtug="{{ $kegiatan->nomor_surat }}" data-tgl-surtug="{{ $kegiatan->tgl_surat_dibuat }}">
                                        Upload <i class="fa-solid fa-upload"></i>
                                    </button>
                                </div>
                                <div class="p-1">
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#tolak_surat" data-id-kegiatan-tolak="{{ $kegiatan->id }}">
                                        <i class="fa-solid fa-exclamation-circle"></i> Tolak Surat
                                    </button>
                                </div>
                            </div>

                            @endif
                            @endif
                            @if (($kegiatan->is_acceptHKT == 'revisi'))
                            <!-- Tombol untuk menampilkan aksi -->
                            <span class="p-1">
                                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#actionsCollapse3_{{$kegiatan->id}}" aria-expanded="false" aria-controls="actionsCollapse3_{{$kegiatan->id}}">
                                    <i class="fa-solid fa-bars"></i> Lihat Aksi
                                </button>
                            </span>

                            <!-- Elemen-elemen tersembunyi (aksi) -->
                            <div class="collapse mt-2" id="actionsCollapse3_{{$kegiatan->id}}">
                                <div class="p-1">
                                    <a href="{{url('/kegiatan-HKT/detail/' . $kegiatan->id)}}" class="btn btn-info">
                                        <i class="fa-solid fa-eye"></i> Lihat Data
                                    </a>
                                </div>
                                <div class="p-1">
                                    <a href="{{ url('/kegiatan-HKT/surtug/edit/' . $kegiatan->id) }}" class="btn btn-warning">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit Surat Tugas
                                    </a>
                                </div>
                                <div class="p-1">
                                    <a href="{{url('/kegiatan-HKT/surtug/preview/' . $kegiatan->id) }}" target="_blank" class="btn btn-dark">
                                        <i class="fa-solid fa-print"></i> Cetak Surat Tugas
                                    </a>
                                </div>
                                <div class="p-1">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#upload_surat" data-id-kegiatan="{{ $kegiatan->id }}">
                                        Upload <i class="fa-solid fa-upload"></i>
                                    </button>
                                </div>
                            </div>

                            @endif
                            @if($kegiatan->is_acceptHKT == 'ditolak')
                            <span class="p-1">
                                <a href="{{url('/kegiatan-HKT/detail/' . $kegiatan->id)}}" class="btn btn-info btn-sm"><i class="fa-solid fa-eye"></i> Lihat Data</a>
                            </span>
                            @endif
                            @if (($kegiatan->is_acceptHKT == 'selesai'))
                            <!-- Tombol untuk menampilkan aksi -->
                            <span class="p-1">
                                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#actionsCollapse4_{{$kegiatan->id}}" aria-expanded="false" aria-controls="actionsCollapse4_{{$kegiatan->id}}">
                                    <i class="fa-solid fa-bars"></i> Lihat Aksi
                                </button>
                            </span>

                            <!-- Elemen-elemen tersembunyi (aksi) -->
                            <div class="collapse mt-2" id="actionsCollapse4_{{$kegiatan->id}}">
                                <div class="p-1">
                                    <a href="{{url('/kegiatan-HKT/detail/' . $kegiatan->id)}}" class="btn btn-info btn-sm">
                                        <i class="fa-solid fa-eye"></i> Lihat Data
                                    </a>
                                </div>
                                <div class="p-1">
                                  <?php
                                      $path = $kegiatan->surat_tugas;
                                      $filename = basename($path);
                                      ?>
                                      <a href="{{url('/kegiatan-getDokumen/' . $filename) }}" target="_blank" class="btn btn-dark">
                                          <i class="fa-solid fa-print"></i> Surat Tugas
                                      </a>
                                  </div>
                                <div class="p-1">
                                  <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#upload_surat_update" data-id-kegiatan-update="{{ $kegiatan->id }}" data-no-surtug-update="{{ $kegiatan->nomor_surat }}" data-tgl-surtug-update="{ $perjadin->nomor_surat }}" data-tgl-surtug="{{ $kegiatan->tgl_surat_dibuat }}">
                                    Update <i class="fa-solid fa-upload"></i>
                                  </button>
                                </div>

                            </div>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>

            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var uploadUpdateModal = document.getElementById('upload_surat_update');
    uploadUpdateModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget; // Button that triggered the modal
      var idKegiatanUpdate = button.getAttribute('data-id-kegiatan-update'); // Extract info from data-* attributes
      var modal = this;
      modal.querySelector('#upload_update_id_kegiatan').value = idKegiatanUpdate;
    });

   
  
    var uploadModal = document.getElementById('upload_surat');
    uploadModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget; // Button that triggered the modal
        var idKegiatan = button.getAttribute('data-id-kegiatan'); // ID Kegiatan
        var noSurtug = button.getAttribute('data-no-surtug'); // Nomor Surat
        var tglSurtug = button.getAttribute('data-tgl-surtug'); // Tanggal Surat
        
        var modal = this;
        modal.querySelector('#upload_id_kegiatan').value = idKegiatan;
        modal.querySelector('#up_no_surtug').value = noSurtug; // Isi otomatis
        modal.querySelector('#up_tgl_surtug').value = tglSurtug; // Isi otomatis
    });


    var updateModal = document.getElementById('upload_surat_update');
    updateModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget; // Button that triggered the modal
      var idKegiatan = button.getAttribute('data-id-kegiatan-update'); // Extract info from data-* attributes
      var noSurtug = button.getAttribute('data-no-surtug-update'); // Extract info from data-* attributes
      var tglSurtug = button.getAttribute('data-tgl-surtug-update'); // Extract info from data-* attributes
      var modal = this;
      modal.querySelector('#upload_update_id_kegiatan').value = idKegiatan;
      modal.querySelector('#update_no_surtug').value = noSurtug;
      modal.querySelector('#update_tgl_surtug').value = tglSurtug;
    });
    
    var tteModal = document.getElementById('upload_tte');
    tteModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget; // Button that triggered the modal
      var idKegiatan = button.getAttribute('data-id-kegiatan-tte'); // Extract info from data-* attributes
      var modal = this;
      modal.querySelector('#upload_tte_id_kegiatan').value = idKegiatan;
    });

    var tolakModal = document.getElementById('tolak_surat');
    tolakModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget; // Button that triggered the modal
      var idKegiatan = button.getAttribute('data-id-kegiatan-tolak'); // Extract info from data-* attributes
      var modal = this;
      modal.querySelector('#tolak_id_kegiatan').value = idKegiatan;
    });
  });
</script>

<!-- Modal Excel export -->
<div class="modal fade" id="dateRangeModal" tabindex="-1" aria-labelledby="dateRangeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="dateRangeModalLabel">Masukkan Rentang Tanggal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="dateRangeForm" action="{{url('/generate-laporan-kegiatan-HKT')}}" method="post">
          @csrf
          <div class="mb-3">
            <label for="tanggalDari" class="form-label">Tanggal Dari</label>
            <input type="date" class="form-control" name="tanggalDari" id="tanggalDari" required>
          </div>
          <div class="mb-3">
            <label for="tanggalSampai" class="form-label">Tanggal Sampai</label>
            <input type="date" class="form-control" name="tanggalSampai" id="tanggalSampai" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="submitDateRange">Export</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal upload surat tugas -->
<div class="modal fade" id="upload_surat" tabindex="-1" aria-labelledby="upload_suratLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="upload_suratLabel">Upload Surat Tugas</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{url('/u_kegiatan_HKT')}}" method="post" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="idKegiatan" id="upload_id_kegiatan" value="">
          <input type="hidden" name="kegiatanStatus" id="upload_kegiatan_status" value="">
          <div class="row">
            <div class="col-md-12 mb-3">
              <label for="uraian" class="form-label">Masukan Nomor Surat Tugas<span class="text-secondary small"></span><span class="text-danger">*</span></label>
              <input type="text" class="form-control mt-1" id="up_no_surtug" name="nomor_surtug" placeholder="Masukan Nomor Surat Tugas" value="">
            </div>
            <div class="col-md-12 mb-3">
              <label for="uraian" class="form-label">Masukan Tanggal Surat<span class="text-secondary small"></span><span class="text-danger">*</span></label>
              <input type="date" class="form-control mt-1" id="up_tgl_surtug" name="tgl_dibuat" value="">
            </div>

            <div class="col-md-12 mb-3">
              <label for="uraian" class="form-label">Masukan Surat Tugas<span class="text-secondary small"></span><span class="text-danger">*</span></label>
              <input type="file" name="surat_tugas" id="fileInput" class="form-control" accept="application/pdf" required="">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal upload TTE -->
<div class="modal fade" id="upload_tte" tabindex="-1" aria-labelledby="upload_tteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="upload_tteLabel">Tandai TTE Surtug</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{url('/u_tte_kegiatan_HKT')}}" method="post">
          @csrf
          <input type="hidden" name="idKegiatan" id="upload_tte_id_kegiatan" value="">
          <input type="hidden" name="kegiatanStatus" id="upload_tte_kegiatan_status" value="">
          <div class="row">
            <div class="col-md-12 mb-3">
              <label for="uraian" class="form-label">Masukan Nomor Surat Tugas<span class="text-secondary small"></span><span class="text-danger">*</span></label>
              <input type="text" class="form-control mt-1" name="nomor_surtug_tte" placeholder="Masukan Nomor Surat Tugas">
            </div>
            <div class="col-md-12 mb-3">
              <label for="uraian" class="form-label">Masukan Tanggal Surat<span class="text-secondary small"></span><span class="text-danger">*</span></label>
              <input type="date" class="form-control mt-1" name="tgl_dibuat_tte">
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
