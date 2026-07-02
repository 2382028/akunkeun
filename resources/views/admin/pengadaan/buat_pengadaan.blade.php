<?php

use Carbon\Carbon;
?>

@extends('admin.templates.sidebar')



<style>
/* Progressbar */
:root {
  --primary-color: #082A99;
  --line-color: #dcdcdc; /* Warna garis abu */
  --line-thickness: 0px; /* Ketebalan garis */
}

.progressbar {
  position: relative;
  display: flex;
  justify-content: space-between;
  align-items: center; /* Pastikan progress step sejajar */
  counter-reset: step;
  margin: 2rem 0 4rem;
}

.progressbar::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  transform: translateY(-50%);
  height: 1px; /* Ketebalan garis */
  background-color: var(--line-color); /* Warna garis abu */
  z-index: -1;
  width: calc(100% - 2.5rem); /* Menyesuaikan lebar dengan langkah */
}

.progress {
  position: absolute;
  top: 50%;
  left: 0;
  transform: translateY(-50%);
  height: 1px;
  background-color: var(--primary-color); /* Warna progress */
  width: 0%; /* Nilai default 0, nanti diatur dengan JS */
  transition: width 0.3s;
}

.progress-step {
  width: 2.5rem; /* Ukuran step */
  height: 2.5rem;
  background-color: #D9D9D9;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  z-index: 1;
}

.progress-step::after {
  content: attr(data-title);
  position: absolute;
  top: calc(100% + 0.75rem); /* Jarak dari lingkaran */
  font-size: 0.8rem;
  color: #8C8888;
  text-align: center;
  white-space: nowrap;
}

.progress-step-active {
  background-color: var(--primary-color);
  color: #f3f3f3;
}

.progress-step-active-2 {
  background-color: var(--primary-color);
  color: #f3f3f3;
}

select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-color: #f7f7f7;
    padding-right: 30px; /* Spasi untuk icon segitiga */
    background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns%3D"http%3A//www.w3.org/2000/svg" viewBox%3D"0 0 16 10"%3E%3Cpath fill%3D"%23444" d%3D"M8 10L0 0h16z"%3E%3C/path%3E%3C/svg%3E');
    background-repeat: no-repeat;
    background-position: right 10px center; /* Posisi icon di tengah vertikal */
    background-size: 10px;
}


</style>
@section('contain')

<div class="container-fluid px-4 py-4">

  <div class="row">
    @if ($status == 'sdp')
        <div class="col-md-12"><h4>Dokumen Pengadaan / <span class="fw-bold">Buat Dokumen Pengadaan / SDP</span></h4>
    @elseif ($status == 'surat-pemesanan')
        <div class="col-md-12"><h4>Dokumen Pengadaan / <span class="fw-bold">Buat Dokumen Pengadaan / Surat Pemesanan</span></h4>
    @endif
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 mb-3">
      <div class="card border-0 bg-secondary">
        <div class="page wrapper">

        @if ($status == 'sdp')
            <a href="{{url('/buat-pengadaan/' . 'sdp')}}" class="btn btn-sm btn-dark me-2" style="padding: 8px 13px; border-radius: 5px;">SDP</a>
            <a href="{{url('/buat-pengadaan/' . 'surat-pemesanan')}}" class="page-wrap btn btn-sm btn-primary" style="padding: 8px 13px; border-radius: 5px;">Surat Pemesanan</a>
        @elseif ($status == 'surat-pemesanan')
            <a href="{{url('/buat-pengadaan/' . 'sdp')}}" class="btn btn-sm btn-primary me-2" style="padding: 8px 13px; border-radius: 5px;">SDP</a>
            <a href="{{url('/buat-pengadaan/' . 'surat-pemesanan')}}" class="page-wrap btn btn-sm btn-dark" style="padding: 8px 13px; border-radius: 5px;">Surat Pemesanan</a>
        @endif
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    @if ($status == "sdp")

    {{-- STEP 1 SDP --}}
    <div id="step1" class="col-md-12 mb-3">
        <div class="card">

          <div class="card-body content ms-3">
            <div class="col-md-12">
                  <h5 class="text-main fw-bold mt-3  ms-3">Standar Dokumen Pengadaan</h5><br>
              </div>
             <!-- Progress bar -->
             <div class="progressbar col-md-10 mx-auto">
                  <div class="progress" id="progress"></div>

                  <div class="progress-step progress-step-active" data-title="Dokumen Pengadaan">1</div>
                  <div class="progress-step progress-step-active-bef" data-title="Perlengkapan Data Lainnya">2</div>
              </div>

              <div class="mb-3 row">
                  <div class="col-md-3 d-flex align-items-center">
                      <label for="no_dokumen_sdp1" class="form-label fw-bold mb-0">No Dokumen</label>
                  </div>
                  <div class="col-md-3">
                      <input type="text" name="no_dokumen" id="no_dokumen" class="form-control" placeholder="Masukkan nomor dokumen" style="background-color: #f7f7f7;">
                  </div>
              </div>

              <div class="mb-3 row">
                  <div class="col-md-3 d-flex align-items-center">
                      <label for="tgl_dokumen_sdp1" class="form-label fw-bold mb-0">Tanggal</label>
                  </div>
                  <div class="col-md-2">
                      <input type="date" name="tgl_dokumen_sdp1" id="tgl_dokumen_sdp1" class="form-control" placeholder="Pilih tanggal dokumen" style="background-color: #f7f7f7;">
                  </div>
              </div>

              <div class="mb-3 row">
                  <div class="col-md-3 d-flex align-items-center">
                      <label for="nama_pengadaan_sdp1" class="form-label fw-bold mb-0">Nama Pengadaan</label>
                  </div>
                  <div class="col-md-6">
                      <textarea type="text" name="nama_pengadaan_sdp1" id="nama_pengadaan_sdp1" class="form-control" placeholder="Masukkan nama pengadaan" style="background-color: #f7f7f7;"></textarea>
                  </div>
              </div>

              <div class="mb-3 row">
                  <div class="col-md-3 d-flex align-items-center">
                      <label for="kode_rup_sdp1" class="form-label fw-bold mb-0">Kode RUP</label>
                  </div>
                  <div class="col-md-3">
                      <input type="text" name="kode_rup_sdp1" id="kode_rup_sdp1" class="form-control" placeholder="Masukkan kode RUP" style="background-color: #f7f7f7;">
                  </div>
              </div>

              <div class="mb-3 row">
                  <div class="col-md-3 d-flex align-items-center">
                      <label for="satuan_kerja_sdp1" class="form-label fw-bold mb-0">Satuan Kerja</label>
                  </div>
                  <div class="col-md-6">
                      <input type="text" name="satuan_kerja_sdp1" id="satuan_kerja_sdp1" class="form-control"
                             value="Lembaga Layanan Pendidikan Tinggi Wilayah IV Bandung"
                             readonly>
                  </div>
              </div>

              <div class="mb-3 row">
                  <div class="col-md-3 d-flex align-items-center">
                      <label for="alamat_satker_sdp1" class="form-label fw-bold mb-0">Alamat Satker</label>
                  </div>
                  <div class="col-md-6">
                      <input type="text" name="alamat_satker_sdp1" id="alamat_satker_sdp1" class="form-control"
                             value="Jl. Khp Hasan Mustopa No.38, Cikutra, Kec. Cibeunying Kidul, Kota Bandung, Jawa Barat 40124"
                             readonly>
                  </div>
              </div>

              <div class="mb-3 row">
                  <div class="col-md-3 d-flex align-items-center">
                      <label for="metode_pengadaan_sdp1" class="form-label fw-bold mb-0">Metode Pengadaan</label>
                  </div>
                  <div class="col-md-3">
                      <input type="text" name="metode_pengadaan_sdp1" id="metode_pengadaan_sdp1" class="form-control" placeholder="Masukkan metode pengadaan" style="background-color: #f7f7f7;">
                  </div>
              </div>

              <div class="mb-3 row">
                  <div class="col-md-3 d-flex align-items-center">
                      <label for="pejabat_pengadaan_sdp1" class="form-label fw-bold mb-0">Pejabat Pengadaan</label>
                  </div>
                  <div class="col-md-6">
                      <input type="text" name="pejabat_pengadaan_sdp1" id="pejabat_pengadaan_sdp1" class="form-control" placeholder="Masukkan nama pejabat pengadaan" style="background-color: #f7f7f7;">
                  </div>
              </div>

              <div class="mb-3 row">
                  <div class="col-md-3 d-flex align-items-center">
                      <label for="nilai_hps_sdp1" class="form-label fw-bold mb-0">Nilai Total HPS</label>
                  </div>
                  <div class="col-md-6">
                      <input type="text" name="nilai_hps_sdp1" id="nilai_hps_sdp1" class="form-control" placeholder="Masukkan nilai total HPS" style="background-color: #f7f7f7;">
                  </div>
              </div>

              <div class="mb-3 row">
                  <div class="col-md-3 d-flex align-items-center">
                      <label for="terbilang_hps_sdp1" class="form-label fw-bold mb-0">Terbilang Nilai HPS</label>
                  </div>
                  <div class="col-md-6">
                      <input type="text" name="terbilang_hps_sdp1" id="terbilang_hps_sdp1" class="form-control" placeholder="Masukkan nilai HPS dalam bentuk terbilang" style="background-color: #f7f7f7;">
                  </div>
              </div>

            <div class="btns-group d-grid gap-2 col-2 ms-auto pb-3 mt-5" style="margin-right: 80px;">
                <button id="nextBtn" type="button" class="btn btn-sm btn-dark" style="padding: 8px 13px; border-radius: 5px; background-color: #F4C306; border-color: #F4C306; color: white;">Selanjutnya</button>
            </div>

          </div>
        </div>
      </div>

    {{-- STEP 2 SDP --}}
    <div id="step2" class="col-md-12 mb-3" style="display: none;">
        <div class="card">

          <div class="card-body content ms-3">
            <div class="col-md-8">
                  <h5 class="text-main fw-bold mt-3  ms-3">Standar Dokumen Pengadaan</h5><br>
              </div>
             <!-- Progress bar -->
             <div class="progressbar col-md-10 mx-auto">
                  <div class="progress" id="progress2"></div>

                  <div class="progress-step progress-step-active" data-title="Dokumen Pengadaan">1</div>
                  <div class="progress-step progress-step-active" data-title="Perlengkapan Data Lainnya">2</div>
              </div>

              <div class="mb-3 row">
                <div class="col-md-3 d-flex align-items-center">
                    <label for="ikp_sdp2" class="form-label fw-bold mb-0">Instruksi Kepada Penyedia (IKP)</label>
                </div>
                <div class="col-md-4">
                    <label class="btn btn-light d-flex align-items-center justify-content-between file-upload-button"
                           style="background-color: transparent; border: 1px solid #000000; cursor: pointer; min-width: 100px; max-width: 140px; width: 100%;">
                        <!-- Tetapkan min-width untuk ukuran awal kecil dan max-width untuk membatasi lebar awal -->
                        <span class="me-2 file-name" style="color: #808080; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            Upload File IKP
                        </span>
                        <i class="fas fa-upload"></i>
                        <input type="file" name="ikp_sdp2" class="form-control file-input" accept="application/pdf,image/jpeg,image/png" style="display: none;">
                    </label>
                </div>



            </div>

              <div class="mb-3 row">
                  <div class="col-md-3 d-flex align-items-center">
                      <label for="website_sdp2" class="form-label fw-bold mb-0">Website</label>
                  </div>
                  <div class="col-md-2">
                      <input type="text" name="website_sdp2" id="website_sdp2" class="form-control" placeholder="Masukkan nama website" style="background-color: #f7f7f7;"></textarea>
                  </div>
              </div>

            <div class="mb-3 row">
                <div class="col-md-3 d-flex align-items-center">
                    <label for="uraian_pekerjaan_sdp2" class="form-label fw-bold mb-0">Uraian Singkat Pekerjaan</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="uraian_pekerjaan_sdp2" id="uraian_pekerjaan_sdp2" class="form-control" placeholder="Masukkan nama pejabat pengadaan" style="background-color: #f7f7f7;">
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-md-3 d-flex align-items-center">
                    <label for="waktu_pekerjaan_sdp2" class="form-label fw-bold mb-0">Jangka Waktu Penyelesaian Pekerjaan</label>
                </div>
                <div class="col-md-1 d-flex align-items-center" style="margin-right: -40px;">
                    <input type="text" name="waktu_pekerjaan_sdp2" id="waktu_pekerjaan_sdp2" class="form-control" placeholder="0" style="background-color: #f7f7f7; width: 60px; margin-right: 5px;">
                </div>
                <div class="col-md-1 d-flex align-items-center">
                    <label class="form-label fw-bold mb-0">hari kalender</label>
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-md-3 d-flex align-items-center">
                    <label for="masa_penawaran_sdp2" class="form-label fw-bold mb-0">Masa Berlaku Penawaran</label>
                </div>
                <div class="col-md-1 d-flex align-items-center" style="margin-right: -40px;">
                    <input type="text" name="masa_penawaran_sdp2" id="masa_penawaran_sdp2" class="form-control" placeholder="0" style="background-color: #f7f7f7; width: 60px; margin-right: 5px;">
                </div>
                <div class="col-md-1 d-flex align-items-center">
                    <label class="form-label fw-bold mb-0">hari kalender</label>
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-md-3 d-flex align-items-center">
                    <label for="syarat_penyedia_sdp2" class="form-label fw-bold mb-0">Syarat Penyedia</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="syarat_penyedia_sdp2" id="syarat_penyedia_sdp2" class="form-control" placeholder="Masukkan nama pejabat pengadaan" style="background-color: #f7f7f7;">
                </div>
            </div>

              <div class="mb-3 row">
                <div class="col-md-3 d-flex align-items-center">
                    <label for="tgl_akhir_daftar_sdp2" class="form-label fw-bold mb-0">Tanggal Terakhir Pendaftaran</label>
                </div>
                <div class="col-md-2">
                    <input type="date" name="tgl_akhir_daftar_sdp2" id="tgl_akhir_daftar_sdp2" class="form-control" placeholder="Pilih tanggal dokumen" style="background-color: #f7f7f7;">
                </div>
            </div>

            <!-- Tombol untuk membuka modal -->
                <div class="mb-3 row">
                    <div class="col-md-3 d-flex align-items-center">
                        <label for="spesifikasi" class="form-label fw-bold mb-0">Spesifikasi</label>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-light d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#spesifikasiModal" style="background-color: transparent; border: 1px solid #000000;">
                            <i class="fas fa-pen-to-square me-2"></i>
                            <span id="spesifikasi_text" style="color: #808080;">Isi Spesifikasi</span>
                        </button>
                    </div>
                </div>

            <div class="mb-3 row">
                <div class="col-md-3 d-flex align-items-center">
                    <label for="penyedia_sdp2" class="form-label fw-bold mb-0">Pilih Penyedia</label>
                </div>
                <div class="col-md-4">
                    <select name="penyedia_sdp2" id="penyedia_sdp2" class="form-control" style="background-color: #f7f7f7;">
                        <option value="" disabled selected>-Pilih-</option>
                        <option value="PT. Para Bandung Propertindo">PT. Para Bandung Propertindo</option>
                        <option value="PT. Brilliant Sakti Persada/Harris Hotel">PT. Brilliant Sakti Persada/Harris Hotel</option>
                        <option value="CV Bahagia Selalu">CV Bahagia Selalu</option>
                        <option value="CV Maju Jaya">CV Maju Jaya</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center pb-3 mt-5" style="width: 100%;">
                <!-- Tombol Sebelumnya di Kiri -->
                <div class="btns-group">
                    <button id="backBtn" type="submit" class="btn btn-sm btn-dark" style="margin-left: 80px; padding: 8px 13px; border-radius: 5px; background-color: #9747FF; border-color: #9747FF; color: white;">Sebelumnya</button>
                </div>

                <!-- Tombol Simpan di Kanan -->
                <div class="btns-group">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#undanganModal">
                        Buat Undangan
                    </button>
                    <button type="button" class="btn btn secondary" data-bs-toggle="modal" data-bs-target="#undanganModal">Ubah</button>
                    <button id="simpanBtnSDP" type="submit" class="btn btn-sm btn-dark" style="margin-right: 80px; padding: 8px 13px; border-radius: 5px; background-color: #052279; border-color: #052279; color: white;">Simpan</button>
                </div>
            </div>

          </div>
        </div>
      </div>
    @elseif ($status == "surat-pemesanan")
    <div class="col-md-12 mb-3">
        <div class="card">

          <div class="card-body content ms-3">
            <div class="col-md-12">
                  <h5 class="text-main fw-bold mt-3  ms-3">Surat Pemesenanan</h5><br>
              </div>

              <div class="mb-3 row">
                  <div class="col-md-3 d-flex align-items-center">
                      <label for="no_dokumen_sp" class="form-label fw-bold mb-0">No Dokumen</label>
                  </div>
                  <div class="col-md-3">
                      <input type="text" name="no_dokumen_sp" id="no_dokumen_sp" class="form-control" placeholder="Masukkan nomor dokumen" style="background-color: #f7f7f7;">
                  </div>
              </div>

              <div class="mb-3 row">
                  <div class="col-md-3 d-flex align-items-center">
                      <label for="tgl_dokumen_sp" class="form-label fw-bold mb-0">Tanggal</label>
                  </div>
                  <div class="col-md-2">
                      <input type="date" name="tgl_dokumen_sp" id="tgl_dokumen_sp" class="form-control" placeholder="Pilih tanggal dokumen" style="background-color: #f7f7f7;">
                  </div>
              </div>

              <div class="mb-3 row">
                  <div class="col-md-3 d-flex align-items-top">
                      <label for="nama_pengadaan_sp" class="form-label fw-bold mb-0">Nama Pengadaan</label>
                  </div>
                  <div class="col-md-6">
                      <textarea type="text" name="nama_pengadaan_sp" id="nama_pengadaan_sp" class="form-control" placeholder="Masukkan nama pengadaan" style="background-color: #f7f7f7;"></textarea>
                  </div>
              </div>

              <div class="mb-3 row">
                  <div class="col-md-3 d-flex align-items-center">
                      <label for="kode_rup_sp" class="form-label fw-bold mb-0">Kode RUP</label>
                  </div>
                  <div class="col-md-3">
                      <input type="text" name="kode_rup_sp" id="kode_rup_sp" class="form-control" placeholder="Masukkan kode RUP" style="background-color: #f7f7f7;">
                  </div>
              </div>

              <div class="mb-3 row">
                <div class="col-md-3 d-flex align-items-center">
                    <label for="penyedia_sp" class="form-label fw-bold mb-0">Pilih Penyedia</label>
                </div>
                <div class="col-md-4">
                    <select name="penyedia_sp" id="penyedia_sp" class="form-control" style="background-color: #f7f7f7;">
                        <option value="" disabled selected>-Pilih-</option>
                        <option value="PT. Para Bandung Propertindo">PT. Para Bandung Propertindo</option>
                        <option value="PT. Brilliant Sakti Persada/Harris Hotel">PT. Brilliant Sakti Persada/Harris Hotel</option>
                        <option value="CV Bahagia Selalu">CV Bahagia Selalu</option>
                        <option value="CV Maju Jaya">CV Maju Jaya</option>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-md-3 d-flex align-items-center">
                    <label for="waktu_pekerjaan_sp" class="form-label fw-bold mb-0">Jangka Waktu Penyelesaian Pekerjaan</label>
                </div>
                <div class="col-md-1 d-flex align-items-center" style="margin-right: -40px;">
                    <input type="text" name="waktu_pekerjaan_sp" id="waktu_pekerjaan_sp" class="form-control" placeholder="0" style="background-color: #f7f7f7; width: 60px; margin-right: 5px;">
                </div>
                <div class="col-md-1 d-flex align-items-center">
                    <label class="form-label fw-bold mb-0">hari kalender</label>
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-md-3 d-flex align-items-center">
                    <label for="tgl_pelaksanaan_sp" class="form-label fw-bold mb-0">Tanggal Pelaksanaan Pekerjaan</label>
                </div>
                <div class="col-md-2 d-flex align-items-center" style="margin-right: -70px;">
                        <input type="date" name="mulai_tgl_pelaksanaan_sp" id="mulai_tgl_pelaksanaan_sp" class="form-control" style="background-color: #f7f7f7; width: 130px;">
                </div>
                <div class="col-md-1 d-flex align-items-center" style="margin-right: -70px;">
                    <label class="form-label mb-0">s.d</label>
                </div>
                <div class="col-md-2 d-flex align-items-center" >
                    <input type="date" name="selesai_tgl_pelaksanaan_sp" id="selesai_tgl_pelaksanaan_sp" class="form-control" style="background-color: #f7f7f7; width: 130px;">
                </div>
            </div>

              <div class="mb-3 row">
                  <div class="col-md-3 d-flex align-items-center">
                      <label for="nilai_hps_sp" class="form-label fw-bold mb-0">Nilai Total HPS</label>
                  </div>
                  <div class="col-md-6">
                      <input type="text" name="nilai_hps_sp" id="nilai_hps_sp" class="form-control" placeholder="Masukkan nilai total HPS" style="background-color: #f7f7f7;">
                  </div>
              </div>

              <div class="mb-3 row">
                  <div class="col-md-3 d-flex align-items-center">
                      <label for="terbilang_hps_sp" class="form-label fw-bold mb-0">Terbilang Nilai HPS</label>
                  </div>
                  <div class="col-md-6">
                      <input type="text" name="terbilang_hps_sp" id="terbilang_hps_sp" class="form-control" placeholder="Masukkan nilai HPS dalam bentuk terbilang" style="background-color: #f7f7f7;">
                  </div>
              </div>

              <!-- Tombol untuk membuka modal -->
                <div class="mb-3 row">
                    <div class="col-md-3 d-flex align-items-center">
                        <label for="spesifikasi" class="form-label fw-bold mb-0">Spesifikasi</label>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-light d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#spesifikasiModal" style="background-color: transparent; border: 1px solid #000000;">
                            <i class="fas fa-pen-to-square me-2"></i>
                            <span id="spesifikasi_text" style="color: #808080;">Isi Spesifikasi</span>
                        </button>
                    </div>
                </div>


              <div class="btns-group d-grid gap-2 col-1 ms-auto pb-3 mt-5" style="margin-right: 80px;">
                  <button type="submit" class="btn btn-sm btn-dark" style="padding: 8px 13px; border-radius: 5px; background-color: #052279; border-color: #052279; color: white;" onclick="previewSPData()">Simpan</button>
              </div>
          </div>
        </div>
      </div>
    @endif
  </div>


 <!-- Modal Undangan -->
<div class="modal fade" id="undanganModal" tabindex="-1" aria-labelledby="undanganModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="undanganModalLabel">Undangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form undangan -->
                <div class="mb-3 row">
                    <label for="jenisUndangan" class="col-md-3 col-form-label fw-bold">Jenis Undangan</label>
                    <div class="col-md-9">
                        <select class="form-select" id="jenisUndangan">
                            <option selected>- Pilih -</option>
                            <option value="1">Undangan Penawaran</option>
                            <option value="2">Undangan Klarifikasi</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="noSurat" class="col-md-3 col-form-label fw-bold">No Surat</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="noSurat" placeholder="Masukkan No Surat">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="tanggalSurat" class="col-md-3 col-form-label fw-bold">Tanggal Surat</label>
                    <div class="col-md-9">
                        <input type="date" class="form-control" id="tanggalSurat">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="perihal" class="col-md-3 col-form-label fw-bold">Perihal</label>
                    <div class="col-md-9">
                        <textarea class="form-control" id="perihal" rows="3" placeholder="Masukkan Perihal"></textarea>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="tempatPelaksanaan" class="col-md-3 col-form-label fw-bold">Tempat Pelaksanaan</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="tempatPelaksanaan" value="LLDIKTI Wilayah IV, Jl. P.H.H Mustafa No.38 Bandung" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="telepon" class="col-md-3 col-form-label fw-bold">Telepon</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="telepon" value="(022) 7275630" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="website" class="col-md-3 col-form-label fw-bold">Website</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="website" value="www.lldikti4.or.id" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="jadwalPelaksanaan" class="col-md-3 col-form-label fw-bold">Jadwal Pelaksanaan</label>
                    <div class="col-md-9">
                        <button type="button" class="btn btn-outline-secondary" id="bukaJadwal">Buat Jadwal</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
               
                <button type="button" class="btn btn-primary" id="simpanUndangan">Simpan</button>
                <button  id="btnUbah" disabled type="button" class="btn btn-success">Ubah</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Jadwal Pelaksanaan -->
<div class="modal fade" id="jadwalModal" tabindex="-1" aria-labelledby="jadwalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="jadwalModalLabel">Jadwal Pelaksanaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form input kegiatan, tanggal, dan waktu -->
                <div class="mb-3 row">
                    <label for="kegiatan" class="col-md-3 col-form-label fw-bold">Kegiatan</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="kegiatan" placeholder="Masukkan Kegiatan" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="tanggal" class="col-md-3 col-form-label fw-bold">Tanggal</label>
                    <div class="col-md-4">
                        <input type="date" class="form-control" id="tanggal" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="waktu" class="col-md-3 col-form-label fw-bold">Waktu</label>
                    <div class="col-md-4 d-flex align-items-center">
                        <input type="time" class="form-control me-2" id="waktuMulai" required>
                        <span class="mx-2">s.d</span>
                        <input type="time" class="form-control" id="waktuSelesai" required>
                    </div>
                </div>
                <!-- Tombol tambah kegiatan -->
                <div class="mb-3 row">
                    <div class="col-md-12 text-end">
                        <button type="button" class="btn btn-primary" id="tambahKegiatan">+ Tambah</button>
                    </div>
                </div>
                <!-- Tabel jadwal pelaksanaan -->
                <table id="example" class="table table-bordered data-table" style="width: 100%">
                    <thead>
                        <tr class="text-center small">
                            <th class="th-sm">No</th>
                            <th>Kegiatan</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td></td>
                            <td></td>
                            <td></td>                    
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
               
                <button type="button" class="btn btn-primary" id="simpanJadwal">Simpan</button>
                <button  id="btnUbah" disabled type="button" class="btn btn-success">Ubah</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview Undangan -->
<div class="modal fade" id="previewUndanganModal" tabindex="-1" aria-labelledby="previewUndanganModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewUndanganModalLabel">Preview Undangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                       
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                <!-- Template Preview Undangan -->
                <div id="previewTemplate">
                    <div class="row">
                        <div class="col-md-6">
                            <p>Nomor Surat: <span id="previewNoSurat">-</span></p>
                        </div>
                        <div class="col-md-6">
                        <div style="text-align: right;">Tanggal: <span id="previewTanggalSurat">-</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        <p>Lampiran: <span id="previewPerihal">-</span></p>
                        </div>
                    </div>
                    <p>Kepada Yth,</p>
                    <p><span id="previewJenisUndangan">-</span></p>
                    <p>Perihal : -</p>
                    <p>Dengan ini kami mengundang anda untuk mengikuti proses Pengadaan Langsung paket Perkerjaan Barang/Jasa sebagai berikut :  </p>
                    <h6>1. Paket Pekerjaan</h6>
                        <p>Nama Pekerjaan: <span id="previewTempatPelaksanaan">-</span></p>
                        <p>Lingkup Pekerjaan: <span id="previewTelepon">-</span></p>
                        <p>Nilai total HPS: <span id="previewWebsite">-</span></p>
                        <p>Sumber Pendanaan :</p>    
                    <h6>Jadwal Pelaksanaan:</h6>
                    <p>Tempat dan Alamat :</p>
                    <p>Telepon/FAx :</p>
                    <p>Website :</p>
                    <p>Saudara diminta untuk memasukkan penawaran administrasi, teknis dan harga, secara langsung sesuai dengan jadwal pelaksanaan sebagai berikut :</p>
                    <table class="table table-bordered data-table" style="width: 100%">
                        <thead>
                            <tr class="text small">
                                <th>No</th>
                                <th>Kegiatan</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody id="previewJadwalTableBody">
                            <tr class="text small">
                                <td colspan="4" class="text-center">Belum ada jadwal</td>
                            </tr>
                        </tbody>
                    </table>
                    <p>Apabila Saudara membutuhkan keterangan dan penjelasan lebih lanjut, dapat menghubungi kami sesuai alamat tersebut di atas sampai dengan batas akhir pemasukan Dokumen Penawaran.
                    <br>Demikian disampaikan untuk diketahui.</p>
                    <p>Pejabat Pengadaan LLDIKTI Wilayah IV</p>
                    <br>
                    <br>
                    <p>Hevy Pratiwi</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary">Konfirmasi & Simpan</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Spesifikasi -->
<div class="modal fade" id="spesifikasiModal" tabindex="-1" aria-labelledby="spesifikasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> <!-- modal-xl untuk ukuran besar -->
        <div class="modal-content">
            <div class="modal-header ms-5 me-5">
                <h5 class="modal-title" id="spesifikasiModalLabel" style="text-decoration: underline;">Spesifikasi Teknis, Gambar, Daftar Kuantitas dan Harga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body ms-5 me-5">
                <div class="mb-3 row">
                    <div class="col-md-3 d-flex align-items-center">
                        <label for="lingkup_pekerjaan_spek" class="form-label fw-bold mb-0">Lingkup Pekerjaan</label>
                    </div>
                    <div class="col-md-5">
                        <select name="lingkup_pekerjaan_spek" id="lingkup_pekerjaan_spek" class="form-control" style="background-color: #f7f7f7;">
                            <option value="" disabled selected>-Pilih-</option>
                            <option value="Barang dan Jasa">Barang dan Jasa</option>
                            <option value="Konstruksi">Konstruksi</option>
                            <option value="Konsultasi">Konsultasi</option>
                            <option value="Jasa Lainnya">Jasa Lainnya</option>
                        </select>
                    </div>
                </div>

                <div id="additionalFields" style="display: none;">
                    <div class="mb-3 row">
                        <div class="col-md-3 d-flex align-items-top">
                            <label for="nama_barang_spek" class="form-label fw-bold mb-0">Nama Barang dan Spesifikasi</label>
                        </div>
                        <div class="col-md-6">
                            <textarea type="text" name="nama_barang_spek" id="nama_barang_spek" class="form-control" placeholder="Masukkan nama pengadaan" style="background-color: #f7f7f7;"></textarea>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-md-3 d-flex align-items-center">
                            <label for="volume_spek" class="form-label fw-bold mb-0">Volume</label>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="volume_spek" id="volume_spek" class="form-control" placeholder="Masukkan Volume" style="background-color: #f7f7f7;">
                        </div>
                    </div>

                    <div class="mb-3 row">
                      <div class="col-md-3 d-flex align-items-center">
                          <label for="jumlah_spek" class="form-label fw-bold mb-0">Jumlah</label>
                      </div>
                      <div class="col-md-2">
                          <input type="text" name="jumlah_spek" id="jumlah_spek" class="form-control" placeholder="Masukkan Jumlah" style="background-color: #f7f7f7;">
                      </div>
                  </div>

                  <div class="mb-3 row">
                      <div class="col-md-3 d-flex align-items-center">
                          <label for="satuan_spek" class="form-label fw-bold mb-0">Satuan</label>
                      </div>
                      <div class="col-md-2">
                          <input type="text" name="satuan_spek" id="satuan_spek" class="form-control" placeholder="Masukkan Satuan" style="background-color: #f7f7f7;">
                      </div>
                  </div>

                  <div class="mb-3 row">
                      <!-- Harga Satuan dan Ppn -->
                      <div class="col-md-3 d-flex align-items-center">
                          <!-- Label Harga Satuan -->
                          <label for="harga_satuan_spek" class="form-label fw-bold mb-0">Harga Satuan</label>
                      </div>
                      <div class="col-md-2">
                          <div class="input-group">
                              <!-- Teks Rp -->
                              <span class="input-group-text" id="basic-addon1">Rp</span>
                              <!-- Input Harga Satuan -->
                              <input type="text" name="harga_satuan_spek" id="harga_satuan_spek" class="form-control" placeholder="Harga satuan" style="background-color: #f7f7f7;">
                          </div>
                      </div>
                      <div class="col-md-3 d-flex justify-content-end align-items-center">
                          <!-- Label Ppn -->
                          <label for="ppn_spek" class="form-label fw-bold mb-0">Ppn</label>
                      </div>
                      <div class="col-md-2">
                          <div class="input-group">
                              <!-- Teks Rp -->
                              <span class="input-group-text" id="basic-addon2">Rp</span>
                              <!-- Input Ppn -->
                              <input type="text" name="ppn_spek" id="ppn_spek" class="form-control" placeholder="Nilai Ppn" style="background-color: #f7f7f7;">
                          </div>
                      </div>
                  </div>

                  <div class="mb-3 row">
                      <!-- Jumlah dan Jumlah Total -->
                      <div class="col-md-3 d-flex align-items-center">
                          <!-- Label Jumlah -->
                          <label for="jumlah_harga_spek" class="form-label fw-bold mb-0">Jumlah</label>
                      </div>
                      <div class="col-md-2">
                          <div class="input-group">
                              <!-- Teks Rp -->
                              <span class="input-group-text" id="basic-addon3">Rp</span>
                              <!-- Input Jumlah -->
                              <input type="text" name="jumlah_harga_spek" id="jumlah_harga_spek" class="form-control" placeholder="Harga Jumlah" style="background-color: #f7f7f7;">
                          </div>
                      </div>
                      <div class="col-md-3 d-flex justify-content-end align-items-center">
                          <!-- Label Jumlah Total -->
                          <label for="jumlah_total_spek" class="form-label fw-bold mb-0">Jumlah Total</label>
                      </div>
                      <div class="col-md-2">
                          <div class="input-group">
                              <!-- Teks Rp -->
                              <span class="input-group-text" id="basic-addon4">Rp</span>
                              <!-- Input Jumlah Total -->
                              <input type="text" name="jumlah_total_spek" id="jumlah_total_spek" class="form-control" placeholder="Total Harga" style="background-color: #f7f7f7;">
                          </div>
                      </div>
                  </div>

                  <div class="btns-group d-grid gap-2 col-1 ms-auto pb-3 mt-5 me-5" style="margin-right: 80px;">
                      <button id="btnTambah" type="submit" class="btn btn-sm btn-dark d-flex align-items-center justify-content-center"
                              style="padding: 5px; border-radius: 5px; background-color: #052279; border-color: #052279; color: white;">
                          <i class="fas fa-plus me-2"></i> <!-- Ikon plus -->
                          Tambah
                      </button>
                  </div>

                  <div class="container">
                    <div class="mb-5">
                        <table class="table table-bordered" id="dataTable">
                            <thead>
                                <tr class="text-center small">
                                    <th>No</th>
                                    <th>Nama Barang dan Spesifikasi</th>
                                    <th>Volume</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Harga Satuan</th>
                                    <th>Jumlah Harga</th>
                                    <th id="aksiHeader" style="display: none;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                <td colspan="8" class="text-center" id="emptyMessage">Belum Ada Data Ditambahkan</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                </div>
            </div>
            <div class="modal-footer ms-5 me-5">
                <button id="btnSimpanSpek" type="button" class="btn btn-primary">Simpan</button>
                <button  id="btnUbah" disabled type="button" class="btn btn-success">Ubah</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview Undangan surat pemesanan-->

<div class="modal fade" id="previewUndanganSPModal" tabindex="-1" aria-labelledby="previewUndanganSPModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewUndanganSPModalLabel">Preview Surat Pemesanan (SP)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5 class="modal-title text-center" id="">Surat Pemesanan (SP)</h5>
                <h5 class="text-center">Workshop Pengelolaan Sarana dan Prasarana Pembelajaran Perguruan Tinggi</h5>
                <p class="text-center">Nomor: <span id="previewSPNomor"></span></p>
                <p class="text-center">Tanggal: <span id="previewSPTanggal"></span></p>
                <p>Yang Bertandatangan di bawah ini:</p>
                <ul class="list-unstyled">
                    <li>Nama: <span id="previewSPNama1"></span></li>
                    <li>Jabatan: Pembuat Komitmen</li>
                    <li>Instansi: Lembaga Layanan Pendidikan Tinggi Wilayah IV</li>
                    <li>Alamat: Jl. Ph.H. Mustafa No. 38 Bandung</li>
                </ul>
                <p>Bertindak sebagai Pejabat Penandatanganan Surat Pemesanan (SP), selanjutnya disebut PIHAK KESATU,</p>
                <ul class="list-unstyled">
                    <li>Nama: <span id="previewSPNama1"></span></li>
                    <li>Jabatan: Pembuat Komitmen</li>
                    <li>Instansi: Lembaga Layanan Pendidikan Tinggi Wilayah IV</li>
                    <li>Alamat: Jl. Ph.H. Mustafa No. 38 Bandung</li>
                </ul>
                <p>Bertindak sebagai Pejabat Penandatanganan Surat Pemesanan (SP), selanjutnya disebut PIHAK KESATU, beritindak untuk dan atas nama PT. Para Bandung Propertindo/Ibis Bandung Trans Studio Hotel untuk penandatanganan Surat Pemesanan (SP), selanjutnya disebut PIHAK KEDUA,</p>
                <p>PIHAK KESATU menugaskan pemesanan kepada PIHAK KEDUA, dan PIHAK KEDUA sepakat untuk melaksanakan pekerjaan dan ketentuan sebagai berikut:</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="simpanButton" data-bs-dismiss="modal">Simpan</button>
                <button type="button" class="btn btn-success">Ubah</button>
            </div>
        </div>
    </div>
</div>

</div><script>
    document.addEventListener("DOMContentLoaded", function() {
        const lingkupPekerjaan = document.getElementById("lingkup_pekerjaan_spek");
        const additionalFields = document.getElementById("additionalFields");
        const dataTable = document.getElementById("dataTable").getElementsByTagName('tbody')[0];
        const emptyMessage = document.getElementById("emptyMessage");
        const btnUbah = document.getElementById("btnUbah");
        const btnTambah = document.getElementById("btnTambah");
        const aksiHeader = document.getElementById("aksiHeader");
        const spesifikasiText = document.getElementById("spesifikasi_text");
        const btnSimpanSpek = document.getElementById("btnSimpanSpek");
        let rowCount = 0;
        let isEditMode = false;

        lingkupPekerjaan.addEventListener("change", function() {
            if (this.value === "Barang dan Jasa") {
                additionalFields.style.display = "block";
            } else {
                additionalFields.style.display = "none";
            }
        });

        btnTambah.addEventListener("click", function() {
            const namaBarang = document.getElementById("nama_barang_spek").value;
            const volume = document.getElementById("volume_spek").value;
            const jumlah = document.getElementById("jumlah_spek").value;
            const satuan = document.getElementById("satuan_spek").value;
            const hargaSatuan = document.getElementById("harga_satuan_spek").value;
            const jumlahTotal = document.getElementById("jumlah_total_spek").value;

            if (namaBarang && volume && jumlah && satuan && hargaSatuan && jumlahTotal) {
                rowCount++;
                const newRow = dataTable.insertRow(dataTable.rows.length - 1); // Insert before empty message row
                newRow.innerHTML = `
                    <td>${rowCount}</td>
                    <td>${namaBarang}</td>
                    <td>${volume}</td>
                    <td>${jumlah}</td>
                    <td>${satuan}</td>
                    <td>${hargaSatuan}</td>
                    <td>${jumlahTotal}</td>
                    <td class="aksi" style="display: none;"></td>
                `;
                emptyMessage.style.display = "none"; // Hide the empty message
                btnUbah.disabled = false; // Enable the Ubah button
                btnTambah.disabled = isEditMode; // Disable Tambah button in edit mode

                // Clear input fields
                document.getElementById("nama_barang_spek").value = '';
                document.getElementById("volume_spek").value = '';
                document.getElementById("jumlah_spek").value = '';
                document.getElementById("satuan_spek").value = '';
                document.getElementById("harga_satuan_spek").value = '';
                document.getElementById("jumlah_total_spek").value = '';
                document.getElementById("jumlah_harga_spek").value = '';
                document.getElementById("ppn_spek").value = '';
            }
            checkTableEmpty(); // Check if the table is empty
        });

        btnUbah.addEventListener("click", function() {
            isEditMode = !isEditMode; // Toggle edit mode
            aksiHeader.style.display = isEditMode ? "" : "none"; // Show or hide Aksi header
            btnTambah.disabled = isEditMode; // Disable Tambah button in edit mode

            const aksiCells = dataTable.getElementsByClassName("aksi");
            for (let cell of aksiCells) {
                cell.innerHTML = isEditMode
                    ? `<i class="fas fa-trash" style="cursor: pointer;" onclick="deleteRow(this)"></i>`
                    : ""; // Show or hide trash icon
                cell.style.display = isEditMode ? "" : "none"; // Show or hide the Aksi column
            }

            btnUbah.innerText = isEditMode ? "Batalkan Ubah" : "Ubah"; // Change button text
            checkTableEmpty(); // Check if the table is empty
        });

        btnSimpanSpek.addEventListener("click", function() {
            const modal = bootstrap.Modal.getInstance(document.getElementById("spesifikasiModal"));
            if (modal) {
                modal.hide();
            }
        });

        window.deleteRow = function(element) {
            const row = element.parentNode.parentNode; // Get the row of the icon
            row.parentNode.removeChild(row); // Remove the row
            rowCount--;

            // Update row numbers
            updateRowNumbers();

            // Check if there are still rows
            checkTableEmpty(); // Check if the table is empty
        };

        function updateRowNumbers() {
            const rows = dataTable.getElementsByTagName('tr');
            for (let i = 0; i < rows.length - 1; i++) { // -1 to exclude the empty message row
                rows[i].cells[0].innerText = i + 1; // Update row number
            }
        }

        function checkTableEmpty() {
            if (dataTable.rows.length <= 1) {
                emptyMessage.style.display = ""; // Show empty message
                btnUbah.disabled = true; // Disable the Ubah button
                btnTambah.disabled = false; // Enable the Tambah button
                aksiHeader.style.display = "none"; // Hide Aksi header
                btnUbah.innerText = "Ubah";
                spesifikasiText.innerText = "Isi Spesifikasi";
            } else {
                spesifikasiText.innerText = "Spesifikasi Telah Terisi";
                emptyMessage.style.display = "none"; // Hide empty message
                btnUbah.disabled = false; // Enable the Ubah button if there's data
            }
        }
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const fileInput = document.querySelector('.file-input');
        const fileNameDisplay = document.querySelector('.file-name');
        const uploadButton = document.querySelector('.file-upload-button');

        fileInput.addEventListener('change', function(event) {
            const fileName = event.target.files[0]?.name || "Upload File IKP"; // Tampilkan nama file atau teks default
            fileNameDisplay.textContent = fileName;

            if (event.target.files.length > 0) {
                // Setelah file dipilih, biarkan button berkembang sesuai dengan panjang nama file
                uploadButton.style.width = 'auto';  // Lepaskan batasan width awal
                uploadButton.style.maxWidth = 'none';  // Hilangkan max-width
            } else {
                // Jika tidak ada file dipilih, kembalikan button ke kondisi awal
                uploadButton.style.width = '100%';  // Kembalikan ke width awal
                uploadButton.style.maxWidth = '140px';  // Set max-width ke 140px lagi
                fileNameDisplay.textContent = "Upload File IKP";  // Reset teks ke default
            }
        });
    });
</script>

<script>
    document.getElementById('nextBtn').addEventListener('click', function() {
    document.getElementById('step1').style.display = 'none';
    document.getElementById('step2').style.display = 'block';

    const progress2 = document.getElementById('progress2');
    progress2.style.width = `99%`;
    progress2.style.backgroundColor = 'var(--primary-color)';

});
    document.getElementById('backBtn').addEventListener('click', function() {
    document.getElementById('step1').style.display = 'block';
    document.getElementById('step2').style.display = 'none';

});

</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const progress = document.getElementById('progress');
    const progress2 = document.getElementById('progress2');
    const steps = document.querySelectorAll('.progress-step');
    const stepActives = document.querySelectorAll('.progress-step-active');

    // Tentukan jumlah langkah aktif
    const currentStep = Array.from(steps).filter(step =>
        step.classList.contains('progress-step-active') ||
        step.classList.contains('progress-step')
    ).length;
    const totalSteps = steps.length;

    const currentStepActive = Array.from(stepActives).filter(stepActives =>
        stepActives.classList.contains('progress-step-active')
    ).length;
    const totalStepActive = stepActives.length;

    // Jika currentStep == 1, progressWidth harus 0%, jika currentStep == 2, progressWidth harus 50%, dan seterusnya
    const progressWidth = ((currentStep - 1) / (totalSteps - 1)) * 100;
    progress.style.width = `100%`;

    console.log("totalStep: ", totalStepActive);

    // Cek apakah elemen step2 memiliki display: block
    const step2Element = document.querySelector('.progress-step-active');
    const step2IsVisible = window.getComputedStyle(step2Element).display === 'block';

    // Ubah background color dan progress width hanya jika step2 dalam keadaan display: block
    if (totalStepActive > 1) {

        progress2.style.backgroundColor = 'var(--primary-color)';
    }
});
document.getElementById('nextBtn').addEventListener('click', function() {
    // Ambil semua input di step 1
    const noDokumen = document.getElementById('no_dokumen').value.trim();
    const tglDokumen = document.getElementById('tgl_dokumen_sdp1').value.trim();
    const namaPengadaan = document.getElementById('nama_pengadaan_sdp1').value.trim();
    const kodeRup = document.getElementById('kode_rup_sdp1').value.trim();
    const metodePengadaan = document.getElementById('metode_pengadaan_sdp1').value.trim();
    const pejabatPengadaan = document.getElementById('pejabat_pengadaan_sdp1').value.trim();
    const nilaiHps = document.getElementById('nilai_hps_sdp1').value.trim();
    const terbilangHps = document.getElementById('terbilang_hps_sdp1').value.trim();

    // Cek jika ada input yang kosong
    if (!noDokumen || !tglDokumen || !namaPengadaan || !kodeRup || !metodePengadaan || !pejabatPengadaan || !nilaiHps || !terbilangHps) {
        // Tampilkan alert dengan SweetAlert2
        Swal.fire({
            icon: 'warning',
            title: 'Data Belum Lengkap',
            text: 'Harap lengkapi semua data di langkah pertama sebelum melanjutkan.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6',
            timer: 3000 // Alert akan otomatis tertutup setelah 3 detik
        });
        return; // Hentikan proses jika ada data yang belum diisi
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const simpanUndanganBtn = document.getElementById('simpanUndangan');
    
    simpanUndanganBtn.addEventListener('click', function() {
        const jenisUndangan = document.getElementById('jenisUndangan').value;
        const noSurat = document.getElementById('noSurat').value;
        const tanggalSurat = document.getElementById('tanggalSurat').value;
        const perihal = document.getElementById('perihal').value;
        const tempatPelaksanaan = document.getElementById('tempatPelaksanaan').value;
        const telepon = document.getElementById('telepon').value;
        const website = document.getElementById('website').value;

        // Use SweetAlert2 for better user experience
        if (!jenisUndangan || !noSurat || !tanggalSurat || !perihal) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Harap isi semua data yang diperlukan!',
                confirmButtonText: 'Coba Lagi'
            });
            return;
        }

        // Log data or send to server here
        console.log({
            jenisUndangan,
            noSurat,
            tanggalSurat,
            perihal,
            tempatPelaksanaan,
            telepon,
            website
        });

        // Close modal on success
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data undangan berhasil disimpan.',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                const modalUndangan = new bootstrap.Modal(document.getElementById('undanganModal'));
                modalUndangan.hide();
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    let kegiatanCount = 0;
    const buatJadwalBtn = document.getElementById('bukaJadwal');
    const tambahKegiatanBtn = document.getElementById('tambahKegiatan');
    const jadwalTableBody = document.getElementById('jadwalTableBody');
    const simpanJadwalBtn = document.getElementById('simpanJadwal');
    let jadwalData = [];

    // Event listener untuk membuka modal jadwal
    buatJadwalBtn.addEventListener('click', function() {
        const jadwalModal = new bootstrap.Modal(document.getElementById('jadwalModal'));
        jadwalModal.show();
    });

    // Event listener untuk menambah kegiatan ke dalam tabel
    tambahKegiatanBtn.addEventListener('click', function() {
        const kegiatan = document.getElementById('kegiatan').value;
        const tanggal = document.getElementById('tanggal').value;
        const waktu = document.getElementById('waktu').value;

        // Validasi input
        if (!kegiatan || !tanggal || !waktu) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap!',
                text: 'Harap mengisi semua data kegiatan, tanggal, dan waktu.',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Tambah baris baru ke tabel
        kegiatanCount++;
        const newRow = `
            <tr>
                <td>${kegiatanCount}</td>
                <td>${kegiatan}</td>
                <td>${tanggal}</td>
                <td>${waktu}</td>
            </tr>
        `;
        jadwalTableBody.insertAdjacentHTML('beforeend', newRow);

        // Simpan data ke dalam array jadwalData
        jadwalData.push({
            kegiatan: kegiatan,
            tanggal: tanggal,
            waktu: waktu
        });

        // Reset form input setelah data ditambahkan
        document.getElementById('kegiatan').value = '';
        document.getElementById('tanggal').value = '';
        document.getElementById('waktu').value = '';
    });

    // Event listener untuk menyimpan jadwal dan menutup modal
    simpanJadwalBtn.addEventListener('click', function() {
        if (jadwalData.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Jadwal Kosong!',
                text: 'Tidak ada jadwal yang ditambahkan.',
                confirmButtonText: 'OK'
            });
            return;
        }

        console.log("Jadwal Pelaksanaan:", jadwalData);

        // Tutup modal setelah simpan
        const jadwalModal = new bootstrap.Modal(document.getElementById('jadwalModal'));
        jadwalModal.hide();
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const simpanUndanganBtn = document.getElementById('simpanUndangan');
    const jadwalData = []; // Simpan data jadwal jika ada

    // Simpan dan Preview Undangan
    simpanUndanganBtn.addEventListener('click', function() {
        // Ambil data dari form modal undangan
        const jenisUndangan = document.getElementById('jenisUndangan').value || '-';
        const noSurat = document.getElementById('noSurat').value || '-';
        const tanggalSurat = document.getElementById('tanggalSurat').value || '-';
        const perihal = document.getElementById('perihal').value || '-';
        const tempatPelaksanaan = document.getElementById('tempatPelaksanaan').value || '-';
        const telepon = document.getElementById('telepon').value || '-';
        const website = document.getElementById('website').value || '-';

        // Tampilkan data di modal preview
        document.getElementById('previewNoSurat').textContent = noSurat;
        document.getElementById('previewTanggalSurat').textContent = tanggalSurat;
        document.getElementById('previewPerihal').textContent = perihal;
        document.getElementById('previewJenisUndangan').textContent = jenisUndangan;
        document.getElementById('previewTempatPelaksanaan').textContent = tempatPelaksanaan;
        document.getElementById('previewTelepon').textContent = telepon;
        document.getElementById('previewWebsite').textContent = website;

        // Update tabel jadwal pelaksanaan
        const previewJadwalTableBody = document.getElementById('previewJadwalTableBody');
        previewJadwalTableBody.innerHTML = ''; // Kosongkan tabel terlebih dahulu
        if (jadwalData.length > 0) {
            jadwalData.forEach((jadwal, index) => {
                const newRow = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${jadwal.kegiatan}</td>
                        <td>${jadwal.tanggal}</td>
                        <td>${jadwal.waktu}</td>
                    </tr>
                `;
                previewJadwalTableBody.insertAdjacentHTML('beforeend', newRow);
            });
        } else {
            previewJadwalTableBody.innerHTML = '<tr><td colspan="4" class="text-center">Belum ada jadwal</td></tr>';
        }

        // Buka modal preview
        const previewUndanganModal = new bootstrap.Modal(document.getElementById('previewUndanganModal'));
        previewUndanganModal.show();
    });
});

function previewSPData() {
    // Mengambil data dari form Surat Pemesanan
    const nomor = document.getElementById('no_dokumen_sp').value;
    const tanggal = document.getElementById('tgl_dokumen_sp').value;
    const nama1 = document.getElementById('nama_pengadaan_sp').value;

    // Menetapkan data ke dalam modal preview Surat Pemesanan
    document.getElementById('previewSPNomor').textContent = nomor;
    document.getElementById('previewSPTanggal').textContent = tanggal;
    document.getElementById('previewSPNama1').textContent = nama1;

    // Membuka modal preview Surat Pemesanan
    $('#previewUndanganSPModal').modal('show');
}

document.getElementById('simpanButton').addEventListener('click', function() {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil Disimpan!',
        text: 'Data Surat Pemesanan telah berhasil disimpan.',
        confirmButtonText: 'OK'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#previewUndanganSPModal').modal('hide'); // Menutup modal setelah konfirmasi
        }
    });
});

// Fungsi serupa dapat dibuat untuk jenis dokumen atau keperluan lainnya



</script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
