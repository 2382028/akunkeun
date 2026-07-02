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


                                                @if ($dokumen[0]->surat_undangan != null && $dokumen[0]->surat_undangan != '-')
                                                <?php
                                                $path = $dokumen[0]->surat_undangan;
                                                $filename = basename($path);
                                                ?>
                                                <a href="{{url('/perjadin-getDokumen/' . $filename)}}" target="_blank" class="btn btn-sm btn-secondary">
                                                    <i class="fa-solid fa-eye"></i> Lihat Dokumen
                                                </a>
                                                @else
                                                Tidak ada undangan
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

                        

                        <div class="col-md-12 mb-3" id="divInformasiPeminjaman" >
                            <!-- Form untuk menambah mobilitas -->
                            <form action="{{url('/c_tambahmobilitas')}}" method="post">
                                @csrf
                                <input type="hidden" name="idPerjadin" value="{{$perjadin->id}}">
                                <h5 class="fw-bold">Informasi Peminjaman </h5>
                            </form>

                            <div class="table-responsive">
                                <!-- Form utama untuk mengupdate mobilitas -->
                                <form id="mobilitasForm" action="{{url('/update_perjadinmobilitas')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="idPerjadin" value="{{$perjadin->id}}">
                                    <input type="hidden" name="perjadinStatus" value="{{$perjadin->is_acceptBMN}}">

                                    <table id="example" class="table table-bordered" style="width: 100%">
                                        <thead>
                                            <tr class="text-center small">
                                                <th class="th-sm">No</th>
                                                <th class="th-md">Pengemudi</th>
                                                <th class="th-md">Mobil</th>
                                                <th class="th-md" style="min-width: 300px;">Tanggal Berangkat</th>
                                                <th class="th-md" style="min-width: 300px;">Tanggal Selesai</th>
                                                <th class="th-lg-percent">Keterangan</th>
                                            </tr>
                                        </thead>
                                        @php
                                        $nummobilitas = 0;
                                        @endphp
                                        @foreach ($mobilitass as $mobilitas)
                                        <tr>
                                            <td>{{$loop->iteration}} <input type="hidden" name="idMobilitas_{{$nummobilitas}}" value="{{$mobilitas->id}}"></td>
                                            <td>
                                                <select disabled class="form-select" aria-label="Default select example" name="supir_{{$nummobilitas}}">
                                                    <option value="{{$mobilitas->nama_lengkap}}" selected>{{$mobilitas->nama_lengkap}}</option>
                                                    
                                                </select>
                                            </td>
                                            <td>
                                            <select class="form-select" aria-label="Default select example" name="mobil_{{$nummobilitas}}">
                                                @foreach ($kendaraans as $kendaraan)
                                                    @if ($kendaraan->id == $mobilitas->kendaraan)
                                                        {{-- Option default, kendaraan yang sudah dipilih --}}
                                                        <option value="{{$kendaraan->id}}" selected>{{$kendaraan->merek}} [{{$kendaraan->no_polisi}}]</option>
                                                    @else
                                                        {{-- Option untuk kendaraan lain, tidak menampilkan kendaraan yang sudah dipilih --}}
                                                        <option value="{{$kendaraan->id}}">{{$kendaraan->merek}} [{{$kendaraan->no_polisi}}]</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            </td>
                                            <td class='text-center' style="min-width: 100px" id="tanggal_{{$nummobilitas}}">{{ Carbon::parse($perjadin->tgl_keberangkatan)->format('d-m-Y H:i') }}</td>
                                            <td class='text-center' style="min-width: 100px" id="tanggal_selesai_{{$nummobilitas}}">{{ Carbon::parse($perjadin->tgl_selesai)->format('d-m-Y H:i') }}</td>
                                            <td>
                                                <select disabled class="form-select keterangan-dropdown disabled" aria-label="Default select example" name="ket_{{$nummobilitas}}" data-index="{{$nummobilitas}}">
                                                    <option  value="{{$mobilitas->ket_mobilitas}}">{{$mobilitas->ket_mobilitas}}</option>
                                                </select>
                                            </td>
                                            <input type="hidden" name="status_{{$nummobilitas}}" value="proses">
                                            <input id="" type="hidden" value="{{ Carbon::parse($perjadin->tgl_keberangkatan)->format('d-m-Y H:i') }}" name="berangkat_{{$nummobilitas}}">
                                            <input id="" type="hidden" value="{{ Carbon::parse($perjadin->tgl_selesai)->format('d-m-Y H:i') }}" name="selesai_{{$nummobilitas}}">
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
                                    <a href="{{url('/perjadin-mobilitas/' . 'proses')}}" class="btn btn-dark">Batal</a>
                                    <button class="btn btn-success" type="submit" name="action" value="update" >Update</button>
                                </div>
                            </div>
                            </form>
                        </div>




                        <!-- Modal Tolak Mobilitas -->
                        <div class="modal fade" id="tolak_mobilitas" tabindex="-1" aria-labelledby="tolak_mobilitasLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="tolak_mobilitasLabel">Tolak Ajuan</h1>
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
                            document.addEventListener('DOMContentLoaded', function() {
                                // Tambahkan event listener pada tombol trash
                                document.querySelectorAll('.delete-mobilitas').forEach(function(button) {
                                    button.addEventListener('click', function() {
                                        // Dapatkan ID mobilitas dari atribut data-id
                                        var mobilitasId = this.getAttribute('data-id');
                                        var perjadinId = document.querySelector('input[name="info_perjadinlangsung"]').value;

                                        if (confirm('Hapus Data Mobilitas?')) {
                                            // Kirim AJAX request ke server untuk menghapus mobilitas
                                            fetch(`/h_mobilitas/${mobilitasId}`, {
                                                    method: 'DELETE',
                                                    headers: {
                                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                                        'Content-Type': 'application/json'
                                                    },
                                                    body: JSON.stringify({
                                                        info_perjadinlangsung: perjadinId
                                                    })
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data.success) {
                                                        // Jika sukses, hapus row dari tabel
                                                        // this.closest('tr').remove();
                                                        // Refresh halaman
                                                        window.location.reload();
                                                    } else {
                                                        alert('Gagal menghapus data mobilitas.');
                                                    }
                                                })
                                                .catch(error => {
                                                    console.error('Error:', error);
                                                    alert('Terjadi kesalahan saat menghapus data mobilitas.');
                                                });
                                        }
                                    });
                                });
                            });
                        </script>

                        <script>
                            // Ambil semua dropdown keterangan
                            var keteranganDropdowns = document.querySelectorAll('.keterangan-dropdown');

                            // Loop melalui setiap dropdown
                            keteranganDropdowns.forEach(function(dropdown) {
                                dropdown.addEventListener('change', function() {
                                    var index = dropdown.getAttribute('data-index');
                                    var selectedOption = dropdown.value;
                                    var tanggalElement = document.getElementById('tanggal_' + index);

                                    // Setel nilai tanggal berdasarkan pilihan dropdown
                                    if (selectedOption === 'Antar') {
                                        tanggalElement.textContent = "{{ Carbon::parse($perjadin->tgl_keberangkatan)->format('d-m-Y H:i') }}";
                                    } else if (selectedOption === 'Jemput') {
                                        tanggalElement.textContent = "{{ Carbon::parse($perjadin->tgl_selesai)->format('d-m-Y H:i') }}";
                                    } else if (selectedOption === 'Antar-Jemput' || selectedOption === 'Lainnya') {
                                        tanggalElement.textContent = "{{ Carbon::parse($perjadin->tgl_keberangkatan)->format('d-m-Y H:i') }} s.d {{ Carbon::parse($perjadin->tgl_selesai)->format('d-m-Y H:i') }}";
                                    }
                                });
                            });
                        </script>


                        <script src="{{asset('public/assets/js/pdfselected.js')}}"></script>

                        <!-- Akhir Dashboard - Kegiatan - Keuangan -->
                        @endsection