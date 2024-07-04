@extends('admin.templates.sidebar')

@section('contain')
<!-- Awal Dashboard - Kegiatan - BMN -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-body content" id="content">

                    <!-- Kegiatan - Keuangan - Pengajuan - Details -->
                    <div class="row page_content page_5">
                        <div class="col-md-12 mb-3 lh-lg">
                            <div class="row">
                                <h5 class="fw-bold">Informasi Kegiatan</h5>
                            </div>
                            <div class="row small">
                                <div class="col-2">ID Kegiatan</div>
                                <div class="col-10">: {{$perjadin->id}}</div>
                            </div>
                            <div class="row small">
                                <div class="col-2">Pemberi Undangan</div>
                                <div class="col-10">: {{$perjadin->pemberi_undangan}}</div>
                            </div>
                            <div class="row small">
                                <div class="col-2">Nama Kegiatan</div>
                                <div class="col-10">: {{$perjadin->nama_kegiatan}}</div>
                            </div>
                            <div class="row small">
                                <div class="col-2">Tanggal Penyelenggaran</div>
                                <div class="col-10">: {{$perjadin->tgl_mulai}} s.d {{$perjadin->tgl_selesai}}</div>
                            </div>
                            <div class="row small">
                                <div class="col-2">Lokasi</div>
                                <div class="col-10">: {{$perjadin->kabupaten_kota}}</div>
                            </div>
                            <br>
                        </div>

                        <form action="{{url('/cu_perjadin_keuangan')}}" method="post">
                            @csrf
                            <input type="hidden" name="statusPerjadin" value="{{$perjadin->is_acceptKeu}}">
                            <input type="hidden" name="idPerjadin" value="{{$perjadin->id}}">

                            <div class="col-md-12 mb-3" id="divInformasiPeserta">
                                <h5 class="fw-bold">Informasi Peserta</h5>
                                <div class="table-responsive">
                                    <table id="example" class="table table-bordered" style="width: 100%">
                                        <thead>
                                            <tr class="text-center small">
                                                <th class="th-md">Nama Lengkap</th>
                                                <th class="th-md">Pangkat/Golongan</th>
                                                <th class="th-md">Sebagai</th>
                                            </tr>
                                        </thead>
                                        @foreach ($pesertaPegawais as $pesertaPegawai)
                                        <tr>
                                            <td>{{$pesertaPegawai->nama_lengkap}}</td>
                                            <td>{{$pesertaPegawai->pangkat}}-{{$pesertaPegawai->golongan}}</td>
                                            <td class="text-center">{{$pesertaPegawai->status_pegawai}}</td>
                                        </tr>
                                        @endforeach
                                        @foreach ($pesertaNonPegawais as $pesertaNonPegawai)
                                        <tr>
                                            <td>{{$pesertaNonPegawai->nama_lengkap}}</td>
                                            <td>{{$pesertaNonPegawai->pangkat}}-{{$pesertaNonPegawai->golongan}}</td>
                                            <td>{{$pesertaNonPegawai->status_pegawai}}</td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="d-grid gap-2 d-md-flex justify-content-center">
                                    <a href="{{url('/perjadin-mobilitas/' . 'pengajuan')}}" class="btn btn-dark">Kembali</a>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Akhir Dashboard - Kegiatan - BMN -->
@endsection