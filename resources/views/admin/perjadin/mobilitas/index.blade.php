<?php

use Carbon\Carbon;
?>

@extends('admin.templates.sidebar')


@section('contain')
<style>
    .tambah-mobilitas-btn {
    width: auto; /* Adjust the width to fit the content */
}

</style>
<div class="container-fluid px-4 py-4">
  <div class="row">
    <div class="col-md-12">
      <h4>Perjalanan Dinas / <span class="fw-bold">BMN Mobilitas</span></h4>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 mb-3">
      <div class="card border-0 bg-secondary">
        <div class="page wrapper" style="position: relative;">
          <div class="btn-group">
            <a href="{{url('/perjadin-mobilitas/' . 'pengajuan')}}" class="page-wrap btn btn-sm btn-primary">Pengajuan</a>
            <a href="{{url('/perjadin-mobilitas/' . 'proses')}}" class="page-wrap btn btn-sm btn-warning text-white">Proses</a>
            <a href="{{url('/perjadin-mobilitas/' . 'selesai')}}" class="page-wrap btn btn-sm btn-success">Selesai</a>
            <a href="{{url('/perjadin-mobilitas/' . 'ditolak')}}" class="page-wrap btn btn-sm btn-danger">Ditolak</a>
          </div>
          <button id="exportButton" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#dateRangeModal" style="position: absolute; right: 0;">
                <i class="fa-solid fa-print"></i> Rekap Perjalanan
          </button>
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
            <div class="d-flex justify-content-between mb-3">
                <div></div> <!-- Empty div to fill the left side -->
                
                <a href="{{ route('bmn_mobilitas_only') }}" class="btn btn-primary tambah-mobilitas-btn">+ Tambah Mobilitas</a>
            </div>
            <div class="table-responsive">
            
              <table id="example" class="table table-bordered data-table" style="width: 100%">
                <thead>
                  <tr class="text-center small">
                    <th class="th-sm">No</th>
                    <th class="th-sm">ID Perjadin</th>
                    <th class="th-md">Nama Pengusul</th>
                    <th class="th-md">Nama Peserta</th>
                    <th class="th-md">Nama Kegiatan</th>
                    <th class="th-md">Tujuan</th>
                    <th class="th-md">Berangkat</th>
                    <th class="th-md">Status</th>
                    <th class="th-md">Aksi</th>
                  </tr>
                </thead>
                @foreach ($mobilitass as $mobilitas)
                <tr>
                  <td class="text-center">{{$loop->iteration}}</td>
                  <td class="text-center">{{$mobilitas->idPerjadin}}</td>
                  <td class="text-center">{{$mobilitas->nama_pengaju}}</td>
                  <td class="text-center"> @if(isset($pesertas[$mobilitas->idPerjadin]) && $pesertas[$mobilitas->idPerjadin]->isNotEmpty())
      @php
        $peserta = $pesertas[$mobilitas->idPerjadin]->first();
        $nama = $peserta->pegawai_nama ?? $peserta->non_pegawai_nama;
      @endphp
      {{ $nama }}
    @else
      -
    @endif</td>
                  <td>{{$mobilitas->nama_kegiatan}}</td>
                  <td class="text-center">{{$mobilitas->kabupaten_kota}}</td>
                  <td class="text-center">{{ Carbon::parse($mobilitas->tgl_keberangkatan)->format('d-m-Y H:i') }}</td>
                  <td class="text-center">{{$mobilitas->is_acceptBMN}}</td>
                  <td class="text-center">
                    <span class="page d-flex justify-content-center align-items-center">
                      @if ($mobilitas->is_acceptBMN == 'proses' || $mobilitas->is_acceptBMN == 'selesai' || $mobilitas->is_acceptBMN == 'ditolak')
                      <a href="{{url('/perjadin-mobilitas/detail_mobilitas/' . $mobilitas->idPerjadin)}}" class="btn btn-primary me-2">
                          <i class="fa-solid fa-eye"></i> Lihat
                      </a>
                        @if ($mobilitas->is_acceptBMN == 'proses')
                        <a href="{{url('/perjadin-mobilitas/edit/' . $mobilitas->idPerjadin)}}" class="btn btn-warning" >
                            <i class="fa-solid fa-pencil-alt"></i> Edit
                        </a>
                        @endif
                      @else
                      <a href="{{ url('/perjadin-mobilitas/detail/' . $mobilitas->idPerjadin) }}" class="btn btn-primary d-flex">
                        <i class="fa-solid fa-check pt-1"></i>
                        <p class="ps-1 m-0">Verifikasi</p>
                      </a>
                      @endif
                    </span>
                  </td>

                </tr>
                @endforeach
                <tbody>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

</div>

<!-- Modal Excel export -->
  <!-- Modal untuk Memilih Rentang Tanggal -->
  <div class="modal fade" id="dateRangeModal" tabindex="-1" aria-labelledby="dateRangeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dateRangeModalLabel">Masukkan Rentang Tanggal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="dateRangeForm" action="{{url('/generate-laporan-BMN')}}" method="post">
                @csrf
                    <div class="mb-3">
                        <label for="tanggalDari" class="form-label">Tanggal Dari</label>
                        <input type="date" class="form-control" name="tanggalDari" id="tanggalDari" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggalSampai" class="form-label">Tanggal Sampai</label>
                        <input type="date" class="form-control" name="tanggalSampai" id="tanggalSampai" required>
                    </div>
                    <div class="mb-3">
                        <label for="versiLaporan" class="form-label">Versi Rekapitulasi</label>
                        <select class="form-select" aria-label="Default select example" name="versiLaporan" id="versiLaporan"  required>
                          <option value="v1" selected>Rekapitulasi Normal</option>
                          <option value="v2" selected>Rekapitulasi Per Hari</option>
                        </select>
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

<script>
    document.getElementById('submitDateRange').addEventListener('click', function() {
        var select = document.getElementById('versiLaporan');
        var form = document.getElementById('dateRangeForm');
        var tanggalDari = document.getElementById('tanggalDari').value;
        var tanggalSampai = document.getElementById('tanggalSampai').value;
        
        // Validasi tanggal
        if (!tanggalDari || !tanggalSampai) {
            alert("Harap isi kedua tanggal!");
            return; // Hentikan eksekusi jika salah satu tanggal tidak diisi
        }
        
        // Ubah action form berdasarkan pilihan
        if (select.value === 'v1') {
            form.action = "{{ url('/generate-laporan-BMN') }}";
        } else if (select.value === 'v2') {
            form.action = "{{ url('/generate-laporan-BMNv2') }}";
        }
        
        // Submit form
        form.submit();
    });
</script>
@endsection
