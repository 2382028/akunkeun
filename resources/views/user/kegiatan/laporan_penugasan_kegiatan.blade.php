@php
use Carbon\Carbon;
@endphp

@extends('user.templates.sidebar')

@section('content')
<div class="container my-5 pt-5">
    <form action="{{url('/note_penugasan')}}" method="post">
        @csrf
        <input type="hidden" name="kegiatan" value="{{ $kegiatan->id}}">
        <input type="hidden" name="pelaksana" value="{{ $pic ? $pic->nama_lengkap : auth('pegawai')->user()->nama_lengkap }}">
        <div class="col-md-8 mx-auto ">
            <div class="card rounded-0">
                <div class="card-body">
                    <div class="container pt-5 pb-4">
                        <h4 class="fw-bold text-center">LAPORAN PENUGASAN KEGIATAN</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <td class="fw-bold th-sm">Nama Kegiatan</th>
                                <td>{{$kegiatan->nama_kegiatan}}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Nomor Surat Tugas</td>
                                <td>{{$surtugs->nomor_surat}}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Pelaksana Kegiatan</td>
                                <td>{{ auth('pegawai')->user()->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Hari/Tanggal</td>
                                <td>{{ \Carbon\Carbon::parse($kegiatan->tgl_mulai)->translatedFormat('l, d F Y') }} s.d {{ \Carbon\Carbon::parse($kegiatan->tgl_selesai)->translatedFormat('l, d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tempat</td>
                                <td>
                                    <textarea class="form-control" placeholder="Isi Tempat yang dikunjungi" id="floatingTextarea" name="tempat">{{$dokumen->tempat_pelaksanaan}}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Nama Penandatangan SPPD</td>
                                <td>
                                    <textarea rows="1" class="form-control" placeholder="Isi Nama Penandatangan SPPD Anda" id="floatingTextarea" name="nama_penandatangan">{{$dokumen->nama_penandatangan}}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Jabatan Penandatangan SPPD</td>
                                <td>
                                    <textarea rows="1" class="form-control" placeholder="Isi Jabatan Penandatangan SPPD Anda" id="floatingTextarea" name="jabatan_penandatangan">{{$dokumen->jabatan_penandatangan}}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">NIP Penandatangan SPPD</td>
                                <td>
                                    <textarea  rows="1" class="form-control" placeholder="Isi NIP Penandatangan SPPD Anda" id="floatingTextarea" name="nip_penandatangan">{{$dokumen->nip_penandatangan}}</textarea>
                                </td>
                            </tr>
                            <tr style="height: 200px">
                                <td colspan="2">
                                    <textarea class="form-control" id="textarea2" placeholder="Isikan Rincian Kegiatan Anda" id="floatingTextarea" name="hasil">{{$dokumen->hasil}}</textarea>
                                </td>
                            </tr>
                        </table>

                        <div class="card border-0">
                            <div class="card-body ms-auto m-0">
                                Bandung, {{ \Carbon\Carbon::parse($kegiatan->tgl_selesai)->translatedFormat('d F Y') }} <br />Pelaksana Tugas Kegiatan
                            </div>
                            <br />
                            <br />
                            <br />
                            <div class="card-body ms-auto m-0">
                                {{$penandatangan->nama_lengkap}}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-3 mb-2">
                <!-- Both buttons aligned to the right -->
                <a href="{{url('/kegiatan/riwayat/'. $kegiatan->status_pengajuan)}}" class="btn btn-secondary btn-sm me-md-2 text-decoration-none text-white" type="button">Kembali</a>
                <button type="submit" class="btn btn-primary btn-sm text-decoration-none text-white">Simpan</button>
            </div>
        </div>
    </form>
</div>
<!-- Akhir Delete Modal Data Ruangan -->
@endsection
