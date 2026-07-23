const fs = require('fs');

const filePath = 'resources/views/user/kegiatan/kegiatan_step2.blade.php';
let content = fs.readFileSync(filePath, 'utf-8');

// 1. Redesign Buttons (Kepanitiaan -> Dokumen)
const patternButtons = /<!-- Kepanitiaan -->[\s\S]*?<label for="surtug" class="col-md-4 col-form-label">Apakah diPerlukan Surtug\?<span class="text-danger">\*<\/span><\/label>/;
const newButtons = `<!-- Kepanitiaan -->
                        <div class="mb-3 row align-items-start">
                            <label for="kepanitian" class="col-md-4 col-form-label">Kepanitiaan<span class="text-danger">*</span></label>
                            <div class="col-md-8" id="summary-kepanitiaan">
                                <div class="d-flex justify-content-between mb-2">
                                    <input type="number" name="jumlah_kepanitiaan" class="form-control text-muted input-abuk" placeholder="Jumlah Kepanitiaan" value="{{ $jumlah_kepanitiaan }}">
                                    <div>
                                        <span class="input-group-text">Orang</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between p-2 border rounded bg-light">
                                    <div class="small text-secondary">
                                        @php $totalPanitia = $perangkatPegawais->count() + $perangkatNonPegawais->count(); @endphp
                                        @if($totalPanitia > 0)
                                            <i class="fa fa-check-circle text-success me-1"></i> <span class="fw-bold">{{ $totalPanitia }} Orang</span> terdaftar
                                        @else
                                            <i class="fa fa-info-circle me-1"></i> Belum ada data
                                        @endif
                                    </div>
                                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambah_peserta" type="button">
                                        <i class="fa fa-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 row align-items-start">
                            <label for="mobilitas" class="col-md-4 col-form-label">Mobilitas</label>
                            <div class="col-md-8" id="summary-mobilitas">
                                <div class="d-flex align-items-center justify-content-between p-2 border rounded bg-light">
                                    <div class="small text-secondary">
                                        @if($mobilitasExists)
                                            <i class="fa fa-check-circle text-success me-1"></i> <span class="fw-bold">{{ $mobilitas->first()->mobilitas }}</span> terdaftar
                                        @else
                                            <i class="fa fa-info-circle me-1"></i> Belum ada data
                                        @endif
                                    </div>
                                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambah_mobilitas" type="button" {{ $mobilitasExists ? 'disabled' : '' }}>
                                        <i class="fa fa-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row align-items-start">
                            <label for="mobilitas" class="col-md-4 col-form-label">Fasilitas Tambahan</label>
                            <div class="col-md-8" id="summary-fasilitas">
                                <div class="d-flex align-items-center justify-content-between p-2 border rounded bg-light">
                                    <div class="small text-secondary">
                                        @if($kebutuhans->count() > 0)
                                            <i class="fa fa-check-circle text-success me-1"></i> <span class="fw-bold">{{ $kebutuhans->count() }} Fasilitas</span> terdaftar
                                        @else
                                            <i class="fa fa-info-circle me-1"></i> Belum ada data
                                        @endif
                                    </div>
                                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#pilihTipeFasilitas" type="button">
                                        <i class="fa fa-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Sarana dan Prasarana -->
                        <div class="mb-3 row align-items-start">
                            <label for="sapras" class="col-md-4 col-form-label">Sarana dan Prasarana</label>
                            <div class="col-md-8" id="summary-sapras">
                                <div class="d-flex align-items-center justify-content-between p-2 border rounded bg-light">
                                    <div class="small text-secondary">
                                        @if($sapras->count() > 0)
                                            <i class="fa fa-check-circle text-success me-1"></i> <span class="fw-bold">{{ $sapras->count() }} Sarana</span> dipinjam
                                        @else
                                            <i class="fa fa-info-circle me-1"></i> Belum ada data
                                        @endif
                                    </div>
                                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambah_sapras" type="button">
                                        <i class="fa fa-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Dokumen -->
                        <div class="mb-3 row align-items-start">
                            <label for="dokumen" class="col-md-4 col-form-label">Dokumen<span class="text-danger">*</span></label>
                            <div class="col-md-8" id="summary-dokumen">
                                <div class="d-flex align-items-center justify-content-between p-2 border rounded bg-light">
                                    <div class="small text-secondary">
                                        @if($dokumens->count() > 0)
                                            <i class="fa fa-check-circle text-success me-1"></i> <span class="fw-bold">{{ $dokumens->count() }} Dokumen</span> terlampir
                                        @else
                                            <i class="fa fa-info-circle me-1"></i> Belum ada data
                                        @endif
                                    </div>
                                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambah_dokumen" type="button">
                                        <i class="fa fa-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row align-items-start">
                            <label for="surtug" class="col-md-4 col-form-label">Apakah diPerlukan Surtug?<span class="text-danger">*</span></label>`;
content = content.replace(patternButtons, newButtons);


// 2. Replace Ringkasan Section
const patternRingkasan = /<!-- RINGKASAN SECTION -->[\s\S]*?<button type="submit" id="btnAjukanKegiatan" class="btn btn-primary col-md-6" form="myForm">Ajukan Kegiatan<\/button>\s*<\/div>\s*<\/div>/;
const newRingkasan = `<div class="d-flex justify-content-evenly mt-5 border-top pt-4">
                        <div class="d-flex col-md-6">
                            <a href="{{ url('/kegiatan/' . $kegiatan->id) }}" class="btn btn-warning text-white col-md-6">Kembali</a>
                        </div>
                        <div class="d-flex col-md-6">
                            <button type="button" id="btnSimpanDraft" class="btn btn-secondary col-md-6 me-2" onclick="submitDraft()">Simpan Draft</button>
                            <button type="button" id="btnBukaRingkasan" class="btn btn-primary col-md-6" data-bs-toggle="modal" data-bs-target="#modalRingkasanPengajuan">
                                <i class="fa fa-paper-plane me-1"></i> Ajukan Kegiatan
                            </button>
                        </div>
                    </div>`;
content = content.replace(patternRingkasan, newRingkasan);


// 3. Replace informasiPerangkatModal with new Modal
const patternInfoModal = /<div class="modal fade" id="informasiPerangkatModal"[\s\S]*?<div class="modal-footer">\s*<button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Tutup<\/button>\s*<\/div>\s*<\/div>\s*<\/div>\s*<\/div>/;
const newModal = `<!-- MODAL RINGKASAN PENGAJUAN -->
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
</div>`;
content = content.replace(patternInfoModal, newModal);


// 4. Remove old modals for Lihat but keep terdaftarPegawaiIds
const patternLihatModals = /<!-- Modal Lihat Data Fasilitas -->[\s\S]*?(?=@php\s*\$terdaftarPegawaiIds)/;
content = content.replace(patternLihatModals, '');

// 5. Add summary updating to reloadTables()
const patternReloadTables = /function reloadTables\(html\) {[\s\S]*?$('#tabel-dokumen-container')\.html\(newDom\.find\('#tabel-dokumen-container'\)\.html\(\)\);/;
const newReloadTables = `function reloadTables(html) {
        var newDom = $(html);
        $('#tabel-kepanitiaan-container').html(newDom.find('#tabel-kepanitiaan-container').html());
        $('#tabel-mobilitas-container').html(newDom.find('#tabel-mobilitas-container').html());
        $('#tabel-fasilitas-container').html(newDom.find('#tabel-fasilitas-container').html());
        $('#tabel-sapras-container').html(newDom.find('#tabel-sapras-container').html());
        $('#tabel-dokumen-container').html(newDom.find('#tabel-dokumen-container').html());
        
        // Update form summary buttons
        $('#summary-kepanitiaan').html(newDom.find('#summary-kepanitiaan').html());
        $('#summary-mobilitas').html(newDom.find('#summary-mobilitas').html());
        $('#summary-fasilitas').html(newDom.find('#summary-fasilitas').html());
        $('#summary-sapras').html(newDom.find('#summary-sapras').html());
        $('#summary-dokumen').html(newDom.find('#summary-dokumen').html());`;
content = content.replace(patternReloadTables, newReloadTables);


fs.writeFileSync(filePath, content, 'utf-8');
console.log("Refactoring successful.");
