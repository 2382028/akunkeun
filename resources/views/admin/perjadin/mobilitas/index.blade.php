<?php

use Carbon\Carbon;
?>

@extends('admin.templates.sidebar')


@section('contain')
<div class="container-fluid px-4 py-4">
  <div class="row">
    <div class="col-md-12">
      <h4>Perjalanan Dinas / <span class="fw-bold">BMN Mobilitas</span></h4>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 mb-3">
      <div class="card border-0 bg-secondary">
        <div class="page wrapper">
          <a href="{{url('/perjadin-mobilitas/' . 'pengajuan')}}" class="page-wrap btn btn-sm btn-primary">Pengajuan</a>
          <a href="{{url('/perjadin-mobilitas/' . 'proses')}}" class="page-wrap btn btn-sm btn-warning text-white">Proses</a>
          <a href="{{url('/perjadin-mobilitas/' . 'ditolak')}}" class="page-wrap btn btn-sm btn-danger">Ditolak</a>
          <a href="{{url('/perjadin-mobilitas/' . 'selesai')}}" class="page-wrap btn btn-sm btn-success">Selesai</a>
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
                    <th class="th-md">Nama Kegiatan</th>
                    <th class="th-md">Tujuan</th>
                    <th class="th-md">Berangkat</th>
                    <th class="th-md">Status</th>
                    <th class="th-lg-percent">Aksi</th>
                  </tr>
                </thead>
                @foreach ($mobilitass as $mobilitas)
                <tr>
                  <td class="text-center">{{$loop->iteration}}</td>
                  <td>{{$mobilitas->nama_kegiatan}}</td>
                  <td class="text-center">{{$mobilitas->kabupaten_kota}}</td>
                  <td class="text-center">{{ Carbon::parse($mobilitas->tgl_keberangkatan)->format('d-m-Y H:i') }}</td>
                  <td class="text-center">{{$mobilitas->is_acceptBMN}}</td>
                  <td class="text-center">
                    <span class="page d-flex justify-content-center align-items-center">
                      @if ($mobilitas->is_acceptBMN == 'proses' || $mobilitas->is_acceptBMN == 'selesai' || $mobilitas->is_acceptBMN == 'ditolak')
                      <a href="{{url('/perjadin-mobilitas/detail_mobilitas/' . $mobilitas->idPerjadin)}}" class="btn btn-info"><i class="fa-solid fa-eye"></i> Lihat Data</a>
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
@endsection