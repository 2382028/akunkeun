@extends('admin.templates.sidebar')

@section('contain')
<!-- Awal Dashboard - Kegiatan - HKT -->
<div class="container-fluid px-4 py-4">
  <div class="row">
    <div class="col-md-12">
      <h4>Perjalanan Dinas / <span class="fw-bold">HKT Perjalanan Dinas</span></h4>
    </div>
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
        <a href="{{url('/perjadin-HKT/' . 'pengajuan')}}" class="page-wrap btn btn-sm btn-primary">Pengajuan</a>
        <a href="{{url('/perjadin-HKT/' . 'revisi')}}" class="page-wrap btn btn-sm btn-info text-white">Revisi</a>
        <a href="{{url('/perjadin-HKT/' . 'ditolak')}}" class="page-wrap btn btn-sm btn-danger">Ditolak</a>
        <a href="{{url('/perjadin-HKT/' . 'selesai')}}" class="page-wrap btn btn-sm btn-success">Selesai</a>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12 mb-3">
    <div class="card">
      <div class="card-body content">
        <!-- BMN Kendaraan - Pengajuan -->
        <div class="row page_content card-style">
          <div class="table-responsive">
            <table id="example" class="table table-bordered data-table" style="width: 100%">
              <thead>
                <tr class="text-center small">
                  <th class="th-sm">No</th>
                  <th class="th-sm">ID Kegiatan</th>
                  <th class="th-sm">Nama Kegiatan</th>
                  <th class="th-sm">Tujuan</th>
                  <th class="th-sm">Asal Undangan</th>
                  <th class="th-sm">Jumlah Hari</th>
                  <th class="th-lg-percent">Aksi</th>
                </tr>
              </thead>

              <tbody>
                @foreach ( $perjadins as $perjadin)
                <tr>
                  <td class="text-center">{{$loop->iteration}}</td>
                  <td>{{$perjadin->id}}</td>
                  <td>{{$perjadin->nama_kegiatan}}</td>
                  <td class="text-center">{{$perjadin->kabupaten_kota}}</td>
                  <td class="text-center">{{$perjadin->pemberi_undangan}}</td>
                  <td class="text-center">{{$perjadin->jumlah_hari}} Hari</td>
                  <td>
                    <div class="">
                      @if ($perjadin->is_acceptHKT == 'pengajuan')
                      <span class="p-1">
                        <a href="{{url('/perjadin-HKT/detail/' . $perjadin->id)}}" class="btn btn-info"><i class="fa-solid fa-eye"></i> Lihat Data</a>
                      </span>
                      <span class="p-1">
                        <a href="{{url('/perjadin-HKT/surtug/' . $perjadin->id)}}" class="btn btn-success"><i class="fa-solid fa-pen-to-square"></i> Buat Surat Tugas</a>
                      </span>
                      <span class="p-1">
                        <a href="{{ url('/perjadin-HKT/surtug/edit/' . $perjadin->id) }}" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i> Edit Surat Tugas</a>
                      </span>
                      <span class="p-1">
                        <a href="{{url('/perjadin-HKT/surtug/preview/' . $perjadin->id) }}" target="_blank" class="btn btn-dark"><i class="fa-solid fa-print"></i> Cetak Surat Tugas</a>
                      </span>
                      <span class="p-1">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#upload_surat">Upload<i class="fa-solid fa-upload"></i></button>
                      </span>
                      <span class="p-1">
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#tolak_surat"><i class="fa-solid fa-exclamation-circle"></i> Tolak Surat</button>
                      </span>
                      @endif
                      @if (($perjadin->is_acceptHKT == 'revisi'))
                      <span class="p-1">
                        <a href="{{ url('/perjadin-HKT/surtug/edit/' . $perjadin->id) }}" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i> Edit Surat Tugas</a>
                      </span>
                      <span class="p-1">
                        <a href="{{url('/perjadin-HKT/surtug/preview/' . $perjadin->id) }}" target="_blank" class="btn btn-dark"><i class="fa-solid fa-print"></i> Cetak Surat Tugas</a>
                      </span>
                      <span class="p-1">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#upload_surat">Upload<i class="fa-solid fa-upload"></i></button>
                      </span>
                      @endif
                      @if (($perjadin->is_acceptHKT == 'selesai'))
                      <!-- Menampilkan pesan atau tindakan ketika is_acceptHKT adalah "Selesai" -->
                      <span class="p-1">
                        <button class="btn btn-secondary" disabled><i class="fa-solid fa-eye"></i> Lihat Data</button>
                      </span>
                      <span class="p-1">
                        <button class="btn btn-secondary" disabled><i class="fa-solid fa-pen-to-square"></i> Buat Surat Tugas</button>
                      </span>
                      <span class="p-1">
                        <a href="{{url('/perjadin-HKT/surtug/preview/' . $perjadin->id) }}" target="_blank" class="btn btn-primary"><i class="fa-solid fa-eye"></i> Lihat Surat Tugas</a>
                      </span>
                      <span class="p-1">
                        <button class="btn btn-secondary" disabled><i class="fa-solid fa-upload"></i> Upload</button>
                      </span>
                      <span class="p-1">
                        <button class="btn btn-secondary" disabled><i class="fa-solid fa-exclamation-circle"></i> Tolak Surat</button>
                      </span>
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

<!-- Modal upload surtug -->
<div class="modal fade" id="upload_surat" tabindex="-1" aria-labelledby="upload_suratLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="upload_suratLabel">Upload Surat Tugas</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{url('/u_perjadin_HKT')}}" method="post" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="idPerjadin" value="{{ isset($perjadin) ? $perjadin->id : '' }}">
          <input type="hidden" name="perjadinStatus" value="{{ isset($perjadin) ? $perjadin->is_acceptHKT : '' }}">
          <div class="row">
            <div class="col-md-12 mb-3">
              <label for="uraian" class="form-label">Masukan Nomor Surat Tugas<span class="text-secondary small"></span><span class="text-danger">*</span></label>
              <input type="text" class="form-control mt-1" name="nomor_surtug" placeholder="Masukan Nomor Surat Tugas">
            </div>
            <div class="col-md-12 mb-3">
              <label for="uraian" class="form-label">Masukan Tanggal Surat<span class="text-secondary small"></span><span class="text-danger">*</span></label>
              <input type="date" class="form-control mt-1" name="tgl_dibuat">
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


<!-- Modal Tolak Surtug -->
<div class="modal fade" id="tolak_surat" tabindex="-1" aria-labelledby="tolak_suratLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="tolak_suratLabel">Tolak Surat Tugas</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{url('/t_perjadin_HKT')}}" method="post">
          @csrf
          <input type="hidden" name="idPerjadin" value="{{ isset($perjadin) ? $perjadin->id : '' }}">
          <input type="hidden" name="perjadinStatus" value="{{ isset($perjadin) ? $perjadin->is_acceptHKT : '' }}">
          <div class="row">
            <div class="col-md-12 mb-3">
              <label for="uraian" class="form-label">Masukan Alasan<span class="text-secondary small"></span><span class="text-danger">*</span></label>
              <input type="text" id="tolak" name="alasan" class="form-control" placeholder="Alasan Penolakan" required="">
            </div>
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

@endsection