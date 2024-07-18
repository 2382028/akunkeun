<?php

use Carbon\Carbon;
?>

@extends('admin.templates.sidebar')

@section('contain')
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<!-- Awal Dashboard - Kegiatan - Keuangan -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card">

                {{-- perulangan number --}}
                <div class="container-fluid px-4 py-3">
                    <div class="row page_content card-style">

                        <div class="row">
                            <div class="col-md-6 mb-3 lh-lg">
                                <div class="row">
                                    <h5 class="fw-bold">Informasi Kegiatan</h5>
                                </div>
                                <div class="row small">
                                    <div class="col-4">Nama Kegiatan</div>
                                    <div class="col-8">: {{$perjadin->nama_kegiatan}}</div>
                                </div>
                                <div class="row small">
                                    <div class="col-4">Tanggal Penyelenggaran</div>
                                    <div class="col-8">: {{ Carbon::parse($perjadin->tgl_mulai)->format('d-m-Y H:i') }}</div>
                                </div>
                                <div class="row small">
                                    <div class="col-4">Tanggal Berangkat</div>
                                    <div class="col-8">: {{ Carbon::parse($perjadin->tgl_keberangkatan)->format('d-m-Y H:i') }}</div>
                                </div>
                                <div class="row small">
                                    <div class="col-4">Tanggal Selesai</div>
                                    <div class="col-8">: {{ Carbon::parse($perjadin->tgl_selesai)->format('d-m-Y H:i') }}</div>
                                </div>
                                <div class="row small">
                                    <div class="col-4">Tujuan</div>
                                    <div class="col-8">: {{$perjadin->kabupaten_kota}}</div>
                                </div>
                                <div class="row small">
                                    <div class="col-4">Keterangan Diantar</div>
                                    <div class="col-8">: {{$perjadin->keterangan_mobilitas}}</div>
                                </div>
                                <br>
                            </div>
                        </div>

                        {{-- AWAL SURAT UNDANGAN --}}
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

                                                <!-- @if ($dokumen[0]->surat_undangan != null)
                                      <?php
                                        $path = $dokumen[0]->surat_undangan;
                                        $filename = basename($path);
                                        ?>
                                       <a href="{{asset('/storage/' . $dokumen[0]->surat_undangan)}}" target="_blank" class="btn btn-sm btn-secondary"><i class="fa-solid fa-eye"></i> Lihat Dokumen</a>
                                      <a href="{{url('/storage/perjadin/getdokumen' . $filename[0])}}" target="_blank" class="btn btn-sm btn-secondary"><i class="fa-solid fa-eye"></i> Lihat Dokumen</a>
                                      @endif                       -->


                                                @if ($dokumen[0]->surat_undangan != null)
                                                <?php
                                                $path = $dokumen[0]->surat_undangan;
                                                $filename = basename($path);
                                                ?>
                                                <a href="{{url('/perjadin-getDokumen/' . $filename)}}" target="_blank" class="btn btn-sm btn-secondary">
                                                    <i class="fa-solid fa-eye"></i> Lihat Dokumen
                                                </a>
                                                @endif


                                                <span>
                                                </span>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- AKHIR SURAT UNDANGAN --}}

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
                            <div class="table-responsive">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="fw-bold">Informasi Fasilitas</h5>
                                    </div>
                                </div>
                                <table id="calculationTable2" name="Fasilitas" class="table table-bordered calculationTable" style="width: 100%">
                                    <thead>
                                        <tr class="text-center small">
                                            <th>No</th>
                                            <th>Nama Fasilitas</th>
                                            <th>Jumlah</th>
                                            <th>Detail</th>
                                            <th>Tipe Pendanaan</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $numkebutuhan = 0;
                                        @endphp
                                        @foreach ($kebutuhans as $kebutuhan)
                                        <tr>
                                            <td class='text-center' style="min-width: 50px">{{$loop->iteration}} <input type="hidden" name="idKebutuhan_{{$numkebutuhan}}" value="{{$kebutuhan->idKebutuhan}}"></td>
                                            <td style="min-width: 150px">{{$kebutuhan->nama}}</td>
                                            <td class='text-center' style="min-width: 50px">{{$kebutuhan->jumlah_frekuensi}}</td>
                                            <td class='text-center' style="min-width: 100px">{{$kebutuhan->satuan}}</td>
                                            <td class='text-center' style="min-width: 100px">{{$kebutuhan->tipe_pendanaan}}</td>
                                            <td class='text-center' style="min-width: 100px">{{$kebutuhan->ket}}</td>
                                        </tr>
                                        @php
                                        $numkebutuhan++;
                                        @endphp

                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="d-grid gap-2 d-md-flex justify-content-center">
                                <button class="btn btn-success" type="submit" id="btnSetujui">Setujui</button>
                                <a href="#" id="btnTolak" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#tolak_mobilitas">Tolak</a>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3" id="divInformasiPeminjaman" style="display: none;">
                            <form action="{{url('/c_tambahmobilitas')}}" method="post">
                                @csrf
                                <input type="hidden" name="idPerjadin" value="{{$perjadin->id}}">
                                <h5 class="fw-bold">Informasi Peminjaman <button type="submit" class="btn btn-primary">+ Tambah Mobilitas</button></h5>
                            </form>
                            <div class="table-responsive">
                                <form id="mobilitasForm" action="{{url('/cu_perjadinmobilitas')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="idPerjadin" value="{{$perjadin->id}}">
                                    <input type="hidden" name="perjadinStatus" value="{{$perjadin->is_acceptBMN}}">

                                    <table id="example" class="table table-bordered" style="width: 100%">
                                        <thead>
                                            <tr class="text-center small">
                                                <th class="th-sm">No</th>
                                                <th class="th-md">Pengemudi</th>
                                                <th class="th-md">Mobil</th>
                                                <th class="th-lg-percent">Tanggal</th>
                                                <th class="th-lg-percent">Keterangan</th>
                                                <th class="th-lg-percent">Aksi</th>
                                            </tr>
                                        </thead>
                                        @php
                                        $nummobilitas = 0;
                                        @endphp
                                        @foreach ($mobilitass as $mobilitas)
                                        <tr>
                                            <td>{{$loop->iteration}} <input type="hidden" name="idMobilitas_{{$nummobilitas}}" value="{{$mobilitas->id}}"></td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" name="supir_{{$nummobilitas}}">
                                                    @foreach($pesertaPegawais as $pesertaPegawai)
                                                    <option value="{{$pesertaPegawai->id}}" selected>{{$pesertaPegawai->nama_lengkap}}</option>
                                                    @endforeach
                                                    @foreach($pesertaNonPegawais as $pesertaNonPegawai)
                                                    <option value="{{$pesertaNonPegawai->id}}" selected>{{$pesertaNonPegawai->nama_lengkap}}</option>

                                                    @endforeach
                                                    @foreach ($pengemudis as $pengemudi)
                                                    @if ($pengemudi->id == $mobilitas->pegawai_id)
                                                    <option value="{{$pengemudi->id}}" selected>{{$pengemudi->nama_lengkap}}</option>
                                                    @endif
                                                    <option value="{{$pengemudi->id}}">{{$pengemudi->nama_lengkap}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" name="mobil_{{$nummobilitas}}">
                                                    @foreach ($kendaraans as $kendaraan)
                                                    @if ($kendaraan->id == $mobilitas->kendaraan)
                                                    <option value="{{$kendaraan->id}}" selected>{{$kendaraan->merek}} [{{$kendaraan->no_polisi}}]</option>
                                                    @endif
                                                    <option value="{{$kendaraan->id}}" selected>{{$kendaraan->merek}} [{{$kendaraan->no_polisi}}]</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class='text-center' style="min-width: 100px" id="tanggal_{{$nummobilitas}}">{{ Carbon::parse($perjadin->tgl_keberangkatan)->format('d-m-Y H:i') }}</td>
                                            <td>
                                                <select class="form-select keterangan-dropdown" aria-label="Default select example" name="ket_mobilitas" data-index="{{$nummobilitas}}">
                                                    <option value="Antar">Antar</option>
                                                    <option value="Jemput">Jemput</option>
                                                </select>
                                            </td>
                                            <td>
                                                <span>
                                                    <button type="button" class="text-decoration-none btn btn-danger btn-sm text-white delete-button" data-id="{{ $mobilitas->id }}"><i class="fa-solid fa-trash"></i></button>
                                                </span>
                                            </td>
                                            <input type="hidden" name="status_{{$nummobilitas}}" value="proses">
                                            <input id="berangkat_{{$nummobilitas}}" type="hidden" value="{{ Carbon::parse($perjadin->tgl_keberangkatan)->format('d-m-Y H:i') }}" name="berangkat_{{$nummobilitas}}">
                                            <input id="selesai_{{$nummobilitas}}" type="hidden" value="{{ Carbon::parse($perjadin->tgl_selesai)->format('d-m-Y H:i') }}" name="selesai_{{$nummobilitas}}">
                                        </tr>
                                        @php
                                        $nummobilitas++;
                                        @endphp
                                        @endforeach
                                        <input type="hidden" name="numMobilitas" value="{{$nummobilitas}}">
                                    </table>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="d-grid gap-2 d-md-flex justify-content-center">
                                    <a href="{{url('/perjadin-mobilitas/' . 'pengajuan')}}" class="btn btn-dark">Batal</a>
                                    <button class="btn btn-success" type="submit" name="action" value="proses">Proses</button>
                                </div>
                            </div>
                            </form>
                        </div>




                        <!-- Modal Tolak Mobilitas -->
                        <div class="modal fade" id="tolak_mobilitas" tabindex="-1" aria-labelledby="tolak_mobilitasLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="tolak_mobilitasLabel">Tolak Surat Tugas</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{url('/cu_perjadinmobilitas')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="idPerjadin" value="{{ isset($perjadin) ? $perjadin->id : '' }}">
                                            <input type="hidden" name="perjadinStatus" value="{{ isset($perjadin) ? $perjadin->is_acceptBMN : '' }}">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label for="uraian" class="form-label">Masukan Alasan<span class="text-secondary small"></span><span class="text-danger">*</span></label>
                                                    <textarea id="tolak" name="alasan" class="form-control" placeholder="Alasan Penolakan" required=""></textarea>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary" name="action" value="tolak">Simpan</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', (event) => {
                                    document.querySelectorAll('.keterangan-dropdown').forEach(dropdown => {
                                        dropdown.addEventListener('change', (e) => {
                                            let index = e.target.getAttribute('data-index');
                                            let selectedValue = e.target.value;
                                            let tanggalElement = document.getElementById('tanggal_' + index);
                                            let berangkatValue = document.getElementById('berangkat_' + index).value;
                                            let selesaiValue = document.getElementById('selesai_' + index).value;

                                            if (selectedValue === 'Antar') {
                                                tanggalElement.textContent = berangkatValue;
                                            } else if (selectedValue === 'Jemput') {
                                                tanggalElement.textContent = selesaiValue;
                                            }
                                        });
                                    });

                                    document.querySelectorAll('.delete-button').forEach(button => {
                                        button.addEventListener('click', (e) => {
                                            e.preventDefault();
                                            let mobilitasId = e.target.closest('.delete-button').getAttribute('data-id');
                                            if (confirm('Apakah Anda yakin ingin menghapus mobilitas ini?')) {
                                                fetch(`/h_mobilitas/${mobilitasId}`, {
                                                    method: 'DELETE',
                                                    headers: {
                                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                                        'Content-Type': 'application/json'
                                                    },
                                                }).then(response => {
                                                    if (response.ok) {
                                                        alert('Mobilitas berhasil dihapus');
                                                        location.reload(); // Refresh halaman setelah penghapusan berhasil
                                                    } else {
                                                        alert('Terjadi kesalahan saat menghapus mobilitas');
                                                    }
                                                }).catch(error => {
                                                    console.error('Error:', error);
                                                    alert('Terjadi kesalahan saat menghapus mobilitas');
                                                });
                                            }
                                        });
                                    });
                                });

                            </script>


                        <script>
                            // Tangani klik tombol "Setujui"
                            document.getElementById("btnSetujui").addEventListener("click", function() {
                                // Sembunyikan tombol "Setujui" dan "Tolak"
                                document.getElementById("btnSetujui").style.display = "none";
                                document.querySelector("a.btn-danger").style.display = "none";
                                // Tampilkan "divInformasiPeminjaman"
                                document.getElementById("divInformasiPeminjaman").style.display = "block";
                            });
                        </script>



                        <script src="{{asset('public/assets/js/pdfselected.js')}}"></script>


                        <!-- Akhir Dashboard - Kegiatan - Keuangan -->
                        @endsection
