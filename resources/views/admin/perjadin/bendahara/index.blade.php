<?php

use Carbon\Carbon;
?>
@extends('admin.templates.sidebar')

@section('contain')
<!-- Awal Dashboard - Kegiatan - Keuangan -->
<div class="container-fluid px-3 py-3">
  <div class="row">
    <div class="col-md-12">
      <h4>Perjadin Dinas / <span class="fw-bold">Bendahara</span></h4>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 mb-2">
      <div class="card border-0 bg-secondary">
        <div class="page wrapper">
          <a href="{{url('/perjadin-bendahara/' . 'approval-1')}}" class="page-wrap btn btn-sm btn-primary">Approval Tahap 1 | Pengajuan</a>
          <a href="{{url('/perjadin-bendahara/' . 'approval-2')}}" class="page-wrap btn btn-sm btn-warning text-white">Approval Tahap 2 | Pelaporan</a>
          <a href="{{url('/perjadin-bendahara/' . 'revisi')}}" class="page-wrap btn btn-sm btn-info text-white">Revisi</a>
          <a href="{{url('/perjadin-bendahara/' . 'ditolak')}}" class="page-wrap btn btn-sm btn-danger">Ditolak</a>
          <a href="{{url('/perjadin-bendahara/' . 'selesai')}}" class="page-wrap btn btn-sm btn-success">Selesai</a>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 mb-3">
      <div class="card">
        <div class="card-body content">

          <!-- Kegiatan - Keuangan - Pengajuan -->
          <div class="row page_content page_1">
            <div class="table-responsive">
              <div class="col-md-12 mb-3 text-end">
                <button id="downloadexcel" class="btn btn-success btn-sm "><i class="fa-solid fa-file-excel"></i> Export to Excel</button>
              </div>
              <table id="example" class="table table-bordered data-table" style="width: 100%">
                <thead>
                  <tr class="text-center small">
                    <th class="th-sm">No</th>
                    <th class="th-sm">Nomor Surat Tugas</th>
                    <th class="th-lg">Nama Kegiatan</th>
                    <th class="th-md">Tanggal Mulai</th>
                    <th class="th-md">Status</th>
                    <th class="th-lg-percent">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($perjadins as $perjadin)
                  <tr>
                    <td class="text-center">{{$loop->iteration}}</td>
                    <td>{{$perjadin->kode_surat_tugas}}</td>
                    <td>{{$perjadin->nama_kegiatan}}</td>
                    <td class="text-center">{{Carbon::parse($perjadin->tgl_mulai)->format('d-m-Y') }}</td>
                    <td class="text-center">{{$perjadin->status_pengajuan}} | {{$perjadin->is_acceptBend}}</td>
                    <td>
                      @if ($perjadin->status_pengajuan == 'selesai')
                      <span class="p-1">
                        <a href="{{url('/perjadin-bendahara/detail/' . $perjadin->id)}}" class="btn btn-info"><i class="fa-solid fa-eye"></i> Lihat Data</a>
                      </span>
                      <span class="p-1">
                        <a href="{{url('/perjadin-bendahara/sppd/' . $perjadin->id) }}" target="_blank" class="btn btn-dark"><i class="fa-solid fa-print"></i> Cetak RPD</a>
                      </span>
                      @elseif ($perjadin->status_pengajuan != 'selesai')
                      <span class="page d-flex justify-content-center align-items-center">
                        <a href="{{url('/perjadin-bendahara/detail/' . $perjadin->id)}}" class="page-wrap btn btn-primary d-flex"><i class="fa-solid fa-check pt-1"></i>
                          <p class="ps-1  m-0">Verifikasi</p>
                        </a>
                      </span>
                      @endif
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
</div>
<!-- Akhir Dashboard - Kegiatan - Keuangan -->
<script>
  document.getElementById('downloadexcel').addEventListener('click', function() {
    var table2excel = new Table2Excel();
    table2excel.export(document.querySelectorAll("#example"), "Daftar Antrian Perjalanan Dinas - Bendahara");
  });
</script>
@endsection