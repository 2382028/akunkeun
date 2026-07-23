<?php

$filePath = 'resources/views/user/kegiatan/kegiatan_step2.blade.php';
$content = file_get_contents($filePath);

// 1. Replace Ringkasan Section
$pattern1 = '/<!-- RINGKASAN SECTION -->.*?<button type="submit" id="btnAjukanKegiatan" class="btn btn-primary col-md-6" form="myForm">Ajukan Kegiatan<\/button>\s*<\/div>\s*<\/div>/s';
$replacement1 = <<<EOT
<div class="d-flex justify-content-evenly mt-5 border-top pt-4">
                        <div class="d-flex col-md-6">
                            <a href="{{ url('/kegiatan/' . \$kegiatan->id) }}" class="btn btn-warning text-white col-md-6">Kembali</a>
                        </div>
                        <div class="d-flex col-md-6">
                            <button type="button" id="btnSimpanDraft" class="btn btn-secondary col-md-6 me-2" onclick="submitDraft()">Simpan Draft</button>
                            <button type="button" id="btnBukaRingkasan" class="btn btn-primary col-md-6" data-bs-toggle="modal" data-bs-target="#modalRingkasanPengajuan">
                                <i class="fa fa-paper-plane me-1"></i> Ajukan Kegiatan
                            </button>
                        </div>
                    </div>
EOT;
$content = preg_replace($pattern1, $replacement1, $content);

// 2. Replace informasiPerangkatModal with new Modal
$pattern2 = '/<div class="modal fade" id="informasiPerangkatModal".*?<div class="modal-footer">\s*<button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Tutup<\/button>\s*<\/div>\s*<\/div>\s*<\/div>\s*<\/div>/s';
$replacement2 = <<<EOT
<!-- MODAL RINGKASAN PENGAJUAN -->
<div class="modal fade" id="modalRingkasanPengajuan" tabindex="-1" aria-labelledby="modalRingkasanPengajuanLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold" id="modalRingkasanPengajuanLabel">Ringkasan Detail Pengajuan Kegiatan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="alert alert-info border-0 shadow-sm rounded-3 mb-4">
                    <i class="fa fa-info-circle me-2"></i> Pastikan seluruh data di bawah ini sudah benar sebelum Anda mengajukan kegiatan.
                </div>
                
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold text-primary mb-3"><i class="fa fa-users me-2"></i>Kepanitiaan</h6>
                        <div id="tabel-kepanitiaan-container">
                            @include('user.kegiatan.partials.table_kepanitiaan')
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold text-primary mb-3"><i class="fa fa-car me-2"></i>Mobilitas</h6>
                        <div id="tabel-mobilitas-container">
                            @include('user.kegiatan.partials.table_mobilitas')
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold text-primary mb-3"><i class="fa fa-box me-2"></i>Fasilitas Tambahan</h6>
                        <div id="tabel-fasilitas-container">
                            @include('user.kegiatan.partials.table_fasilitas')
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold text-primary mb-3"><i class="fa fa-building me-2"></i>Sarana dan Prasarana</h6>
                        <div id="tabel-sapras-container">
                            @include('user.kegiatan.partials.table_sapras')
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold text-primary mb-3"><i class="fa fa-file-alt me-2"></i>Dokumen Pendukung</h6>
                        <div id="tabel-dokumen-container">
                            @include('user.kegiatan.partials.table_dokumen')
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-white border-top-0 py-3">
                <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">Kembali Cek Data</button>
                <button type="submit" id="btnAjukanKegiatanFinal" class="btn btn-primary px-4 rounded-pill" form="myForm">
                    <i class="fa fa-paper-plane me-1"></i> Konfirmasi & Ajukan
                </button>
            </div>
        </div>
    </div>
</div>
EOT;
$content = preg_replace($pattern2, $replacement2, $content);

// 3. Remove old modals for Lihat
$pattern3 = '/<!-- Modal Lihat Data Fasilitas -->.*?<!-- Modal Tambah Peserta -->/s';
$content = preg_replace($pattern3, '<!-- Modal Tambah Peserta -->', $content);

file_put_contents($filePath, $content);

echo "PHP Replacement script executed successfully.\n";
?>
