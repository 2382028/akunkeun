@extends('admin.templates.sidebar')

@section('contain')
<!-- Awal Dashboard - Kegiatan - HKT -->
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

                            <div class="col-md-12 mb-3">
                                <h5 class="fw-bold">Informasi Dokumen</h5>
                                <div class="table-responsive">
                                    <table id="example" class="table table-bordered" style="width: 100%">
                                        <thead>
                                            <tr class="text-center small">
                                                <th class="th-sm">No</th>
                                                <th class="th-md">Nama Dokumen</th>
                                                <th class="th-md">Aksi</th>
                                                @if ($perjadin->is_acceptKeu == 'verifikasi-2')
                                                <th class="th-sm">Tanggal Penerimaan Berkas</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($dokumen->isNotEmpty())
                                            <tr>
                                                <td class='text-center'>1</td>
                                                <td>Surat Undangan</td>
                                                <td class='text-center'>
                                                    <?php
                                                    $path = $dokumen[0]->surat_undangan;
                                                    $filename = basename($path);
                                                    ?>

                                                    @if ($dokumen[0]->surat_undangan != null)
                                                    <?php
                                                    $path = $dokumen[0]->surat_undangan;
                                                    $filename = basename($path);
                                                    ?>
                                                    <a href="{{url('/perjadin-getDokumen/' . $filename)}}" target="_blank" class="btn btn-sm btn-secondary">
                                                        <i class="fa-solid fa-eye"></i> Lihat Dokumen
                                                    </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    </div>

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
                    <div class="col-md-12 mb-3" id="divInformasiPeserta">
                        <h5 class="fw-bold">Informasi Pengemudi</h5>
                        <div class="table-responsive">
                            <table id="example" class="table table-bordered" style="width: 100%">
                                <thead>
                                    <tr class="text-center small">
                                        <th class="th-md">Nama Lengkap</th>
                                        <th class="th-md">Sebagai</th>
                                    </tr>
                                </thead>
                                @foreach ($pengemudis as $pengemudi)
                                <tr>
                                    <td>{{$pengemudi->nama_lengkap}}</td>
                                    <td class="text-center">{{$pengemudi->status_pegawai}}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    @if (($perjadin->is_acceptKeu == 'verifikasi-2') | ($perjadin->is_acceptKeu == 'revisi') | ($perjadin->is_acceptKeu == 'selesai'))

                    @endif

                    <div class="col-md-12 mb-3">
                        <div class="d-grid gap-2 d-md-flex justify-content-center">
                            <a href="{{url('/perjadin-HKT/' . 'pengajuan')}}" class="btn btn-dark">Kembali</a>
                            @if (($perjadin->is_acceptKeu == 'verifikasi-1') | ($perjadin->is_acceptKeu == 'verifikasi-2'))
                            <button class="btn btn-danger" type="submit" name="action" value="tolak">Tolak Perjalanan dinas</button>
                            <!-- <button class="btn btn-info text-white" type="submit" name="action" value="revisi">Revisi</button> -->
                            @endif
                        </div>
                    </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
<!-- Akhir Dashboard - Kegiatan - HKT -->
@endsection