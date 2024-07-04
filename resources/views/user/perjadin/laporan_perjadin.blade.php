@php
use Carbon\Carbon;
@endphp

@extends('user.templates.template')

@section('content')
<div class="container my-5 pt-5">
    <form action="{{url('/note_perjadin')}}" method="post">
        @csrf
        <input type="hidden" name="perjadin" value="{{$perjadin->id}}">
        <input type="hidden" name="pelaksana" value="{{ $pic ? $pic->nama_lengkap : auth('pegawai')->user()->nama_lengkap }}">
        <div class="col-md-8 mx-auto ">
            <div class="card rounded-0">
                <div class="card-body">
                    <div class="container pt-5 pb-4">
                        <h4 class="fw-bold text-center">LAPORAN PERJALANAN DINAS</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <td class="fw-bold th-sm">Nama Kegiatan</th>
                                <td>{{$perjadin->nama_kegiatan}}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Nomor Surat Tugas</td>
                                <td>{{$surtugs->nomor_surat}}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Pelaksana Kegiatan</td>
                                <td>{{ $pic ? $pic->nama_lengkap : auth('pegawai')->user()->nama_lengkap }}</td>
                                <!-- <td><input id="" type="text" class="form-control custom-input" name="pelaksana" placeholder="Silahkan isi nama PIC" value="{{$dokumen[0]->nama_pelaksana}}"></td> -->
                            </tr>
                            <tr>
                                <td class="fw-bold">Hari/Tanggal</td>
                                <td>{{$perjadin->tgl_mulai}} s.d {{$perjadin->tgl_selesai}}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tempat</td>
                                <td>
                                    <textarea class="form-control" placeholder="Isi Tempat yang dikunjungi" id="floatingTextarea" name="tempat">{{$dokumen[0]->tempat_pelaksanaan}}</textarea>
                                </td>
                            </tr>
                            <tr style="height: 200px">
                                <td colspan="2">
                                    <textarea class="form-control" id="textarea2" placeholder="Leave a comment here" id="floatingTextarea" name="hasil">{{$dokumen[0]->hasil}}</textarea>
                                    <!-- <div name="hasil" id="textarea2" cols="50" rows="10" style="min-height: 200px;"></div> -->
                                </td>
                            </tr>
                        </table>

                        <div class="card border-0">
                            <div class="card-body ms-auto m-0">
                                Bandung, {{Carbon::parse($perjadin->tgl_selesai)->format('d-m-Y') }} <br />Pelaksana Perjalanan Dinas
                            </div>
                            <br />
                            <br />
                            <br />
                            <div class="card-body ms-auto m-0">
                                {{$dokumen[0]->nama_pelaksana}}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
                <!-- Left-aligned button -->
                <span>
                    @if ($dokumen[0]->hasil)
                    <a href="{{url('/note-perjadin-user/' . $perjadin->id)}}" target="_blank" class="btn btn-sm btn-warning"><i class="fa-solid fa-eye"></i> Lihat Laporan</a>
                    @endif
                </span>

                <!-- Right-aligned buttons -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{url('/detail-perjadin/' . $perjadin->id)}}" class="btn btn-secondary btn-sm me-md-2 text-decoration-none text-white" type="button">Kembali</a>
                    <button type="submit" class="btn btn-primary btn-sm text-decoration-none text-white">Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- Akhir Delete Modal Data Ruangan -->
@endsection