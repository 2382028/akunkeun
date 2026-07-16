@extends('user.templates.sidebar')

@section('content')

    <div style="background-color: #f4f7fe; min-height: calc(100vh - 100px); padding-bottom: 1rem; display: flex; flex-direction: column;">
    {{-- Dashboard Cards --}}
    <section class="pt-4">
        <div class="container-fluid px-4">
            <div class="row g-3">

                {{-- Kartu 1: Program Kegiatan --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="dashboard-card" style="border-top: 4px solid #456dee; background: linear-gradient(to bottom right, #ffffff, #f5f8ff); padding: 1.2rem;">
                        <div class="row align-items-center h-100">
                            {{-- Kiri --}}
                            <div class="col-6 border-end pe-3 text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="dashboard-card-icon" style="width: 32px; height: 32px; background: linear-gradient(135deg, #082A99, #456dee); color: #ffffff; box-shadow: 0 4px 10px rgba(69, 109, 238, 0.3);">
                                        <i class="fa-solid fa-calendar-check" style="font-size: 0.85rem;"></i>
                                    </div>
                                    <h6 class="fw-bold text-secondary mb-0 ms-2" style="font-size: 0.8rem; text-align: left; line-height: 1.1;">Program Kegiatan</h6>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ url('/perjadin/riwayat/pengajuan') }}" class="text-decoration-none d-block">
                                        <span class="dashboard-total text-dark" style="font-size: 2.4rem; line-height: 1; cursor: pointer;">{{ $kegiatanTotal ?? 0 }}</span>
                                        <div class="text-muted" style="font-size: 0.7rem; margin-top: 2px;">Total Kegiatan</div>
                                    </a>
                                </div>
                                <div class="mt-2 text-success fw-bold" style="font-size: 0.75rem;">
                                    <a href="{{ url('/perjadin/riwayat/selesai') }}" class="text-success text-decoration-none">
                                        <i class="fa-solid fa-check-circle me-1"></i>{{ $kegiatanSelesai ?? 0 }} Selesai
                                    </a>
                                </div>
                            </div>
                            {{-- Kanan --}}
                            <div class="col-6 ps-3">
                                <div class="d-flex flex-column gap-2" style="font-size: 0.75rem;">
                                    <a href="{{ url('/perjadin/riwayat/revisi') }}" class="text-decoration-none d-flex justify-content-between align-items-center bg-white border rounded p-1 px-2 shadow-sm">
                                        <span class="text-muted"><i class="fa-solid fa-file-pen me-1" style="color: #b8860b;"></i> Draf/Revisi:</span>
                                        <span class="fw-bold text-dark">{{ ($kegiatanDraf ?? 0) + ($kegiatanRevisi ?? 0) }}</span>
                                    </a>
                                    <a href="{{ url('/perjadin/riwayat/proses') }}" class="text-decoration-none d-flex justify-content-between align-items-center bg-white border rounded p-1 px-2 shadow-sm">
                                        <span class="text-muted"><i class="fa-solid fa-spinner me-1" style="color: #0275d8;"></i> Proses:</span>
                                        <span class="fw-bold text-dark">{{ $kegiatanProses ?? 0 }}</span>
                                    </a>
                                    <a href="{{ url('/perjadin/riwayat/pelaporan') }}" class="text-decoration-none d-flex justify-content-between align-items-center bg-white border rounded p-1 px-2 shadow-sm">
                                        <span class="text-muted"><i class="fa-solid fa-file-invoice me-1" style="color: #0dcaf0;"></i> Pelaporan:</span>
                                        <span class="fw-bold text-dark">{{ $kegiatanPelaporan ?? 0 }}</span>
                                    </a>
                                    <a href="{{ url('/perjadin/riwayat/ditolak') }}" class="text-decoration-none d-flex justify-content-between align-items-center bg-white border rounded p-1 px-2 shadow-sm" style="background-color: #fcf6f6 !important; border-color: #f5c2c7 !important;">
                                        <span class="text-danger"><i class="fa-solid fa-ban me-1"></i> Ditolak:</span>
                                        <span class="fw-bold text-danger">{{ $kegiatanDitolak ?? 0 }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kartu 2: Perjalanan Dinas --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="dashboard-card" style="border-top: 4px solid #fda10d; background: linear-gradient(to bottom right, #ffffff, #fffaf0); padding: 1.2rem;">
                        <div class="row align-items-center h-100">
                            {{-- Kiri --}}
                            <div class="col-6 border-end pe-3 text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="dashboard-card-icon" style="width: 32px; height: 32px; background: linear-gradient(135deg, #f35515, #fda10d); color: #ffffff; box-shadow: 0 4px 10px rgba(253, 161, 13, 0.3);">
                                        <i class="fa-solid fa-plane-departure" style="font-size: 0.85rem;"></i>
                                    </div>
                                    <h6 class="fw-bold text-secondary mb-0 ms-2" style="font-size: 0.8rem; text-align: left; line-height: 1.1;">Perjalanan Dinas</h6>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ url('/perjadin/riwayat/pengajuan') }}" class="text-decoration-none d-block">
                                        <span class="dashboard-total text-dark" style="font-size: 2.4rem; line-height: 1; cursor: pointer;">{{ $perjadinTotal ?? 0 }}</span>
                                        <div class="text-muted" style="font-size: 0.7rem; margin-top: 2px;">Total Perjalanan</div>
                                    </a>
                                </div>
                                <div class="mt-2 text-success fw-bold" style="font-size: 0.75rem;">
                                    <a href="{{ url('/perjadin/riwayat/selesai') }}" class="text-success text-decoration-none">
                                        <i class="fa-solid fa-check-circle me-1"></i>{{ $perjadinSelesai ?? 0 }} Selesai
                                    </a>
                                </div>
                            </div>
                            {{-- Kanan --}}
                            <div class="col-6 ps-3">
                                <div class="d-flex flex-column gap-2" style="font-size: 0.75rem;">
                                    <a href="{{ url('/perjadin/riwayat/revisi') }}" class="text-decoration-none d-flex justify-content-between align-items-center bg-white border rounded p-1 px-2 shadow-sm">
                                        <span class="text-muted"><i class="fa-solid fa-file-pen me-1" style="color: #b8860b;"></i> Draf/Revisi:</span>
                                        <span class="fw-bold text-dark">{{ ($perjadinDraf ?? 0) + ($perjadinRevisi ?? 0) }}</span>
                                    </a>
                                    <a href="{{ url('/perjadin/riwayat/proses') }}" class="text-decoration-none d-flex justify-content-between align-items-center bg-white border rounded p-1 px-2 shadow-sm">
                                        <span class="text-muted"><i class="fa-solid fa-spinner me-1" style="color: #0275d8;"></i> Proses:</span>
                                        <span class="fw-bold text-dark">{{ $perjadinProses ?? 0 }}</span>
                                    </a>
                                    <a href="{{ url('/perjadin/riwayat/pelaporan') }}" class="text-decoration-none d-flex justify-content-between align-items-center bg-white border rounded p-1 px-2 shadow-sm">
                                        <span class="text-muted"><i class="fa-solid fa-file-invoice me-1" style="color: #0dcaf0;"></i> Pelaporan:</span>
                                        <span class="fw-bold text-dark">{{ $perjadinPelaporan ?? 0 }}</span>
                                    </a>
                                    <a href="{{ url('/perjadin/riwayat/ditolak') }}" class="text-decoration-none d-flex justify-content-between align-items-center bg-white border rounded p-1 px-2 shadow-sm" style="background-color: #fcf6f6 !important; border-color: #f5c2c7 !important;">
                                        <span class="text-danger"><i class="fa-solid fa-ban me-1"></i> Ditolak:</span>
                                        <span class="fw-bold text-danger">{{ $perjadinDitolak ?? 0 }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kartu 3: Pengajuan Pemeliharaan --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="dashboard-card" style="border-top: 4px solid #2CCE82; background: linear-gradient(to bottom right, #ffffff, #f2fcf7); padding: 1.2rem;">
                        <div class="row align-items-center h-100">
                            {{-- Kiri --}}
                            <div class="col-6 border-end pe-3 text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="dashboard-card-icon" style="width: 32px; height: 32px; background: linear-gradient(135deg, #1fa867, #2CCE82); color: #ffffff; box-shadow: 0 4px 10px rgba(44, 206, 130, 0.3);">
                                        <i class="fa-solid fa-screwdriver-wrench" style="font-size: 0.85rem;"></i>
                                    </div>
                                    <h6 class="fw-bold text-secondary mb-0 ms-2" style="font-size: 0.8rem; text-align: left; line-height: 1.1;">Pengajuan Pemeliharaan</h6>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ url('/pemeliharaan-pegawai') }}" class="text-decoration-none d-block">
                                        <span class="dashboard-total text-dark" style="font-size: 2.4rem; line-height: 1; cursor: pointer;">{{ $pemeliharaanTotal ?? 0 }}</span>
                                        <div class="text-muted" style="font-size: 0.7rem; margin-top: 2px;">Total Pengajuan</div>
                                    </a>
                                </div>
                                <div class="mt-2 text-success fw-bold" style="font-size: 0.75rem;">
                                    <a href="{{ url('/pemeliharaan-pegawai') }}" class="text-success text-decoration-none">
                                        <i class="fa-solid fa-check-circle me-1"></i>{{ $pemeliharaanSelesai ?? 0 }} Selesai
                                    </a>
                                </div>
                            </div>
                            {{-- Kanan --}}
                            <div class="col-6 ps-3 d-flex flex-column justify-content-center h-100">
                                <div class="d-flex flex-column gap-2" style="font-size: 0.75rem;">
                                    <a href="{{ url('/pemeliharaan-pegawai') }}" class="text-decoration-none d-flex justify-content-between align-items-center bg-white border rounded p-1 px-2 shadow-sm">
                                        <span class="text-muted"><i class="fa-solid fa-spinner me-1" style="color: #0275d8;"></i> Proses:</span>
                                        <span class="fw-bold text-dark">{{ $pemeliharaanProses ?? 0 }}</span>
                                    </a>
                                    <a href="{{ url('/pemeliharaan-pegawai') }}" class="text-decoration-none d-flex justify-content-between align-items-center bg-white border rounded p-1 px-2 shadow-sm opacity-0">
                                        <span class="text-muted">-</span>
                                        <span class="fw-bold">-</span>
                                    </a>
                                    <a href="{{ url('/pemeliharaan-pegawai') }}" class="text-decoration-none d-flex justify-content-between align-items-center bg-white border rounded p-1 px-2 shadow-sm opacity-0">
                                        <span class="text-muted">-</span>
                                        <span class="fw-bold">-</span>
                                    </a>
                                    <a href="{{ url('/pemeliharaan-pegawai') }}" class="text-decoration-none d-flex justify-content-between align-items-center bg-white border rounded p-1 px-2 shadow-sm opacity-0">
                                        <span class="text-muted">-</span>
                                        <span class="fw-bold">-</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Riwayat Pengajuan Terbaru --}}
    <section class="mt-3" style="flex: 1; display: flex; flex-direction: column; min-height: 0;">
        <div class="container-fluid px-4" style="display: flex; flex-direction: column; height: 100%;">
            <div class="dashboard-table-wrapper p-3" style="display: flex; flex-direction: column; flex: 1; min-height: 0;">
                <div class="d-flex align-items-center justify-content-between mb-2" style="flex-shrink: 0;">
                    <h6 class="fw-bold text-secondary mb-0" style="font-size: 0.85rem;">
                        <i class="fa-solid fa-clock-rotate-left me-2" style="color: #082A99;"></i>Riwayat Pengajuan Terbaru
                    </h6>
                    <span id="riwayat-counter" class="text-muted" style="font-size: 0.7rem;"></span>
                </div>
                <div class="table-responsive" style="flex: 1; min-height: 0; overflow: hidden;" id="riwayat-table-container">
                    <table class="table table-hover mb-0" style="font-size: 0.72rem;" id="riwayat-table">
                        <thead id="riwayat-thead">
                            <tr style="background-color: #f8f9fb;">
                                <th class="text-muted fw-semibold py-1 ps-2" style="width: 5%;">No</th>
                                <th class="text-muted fw-semibold py-1" style="width: 35%;">Nama Kegiatan</th>
                                <th class="text-muted fw-semibold py-1" style="width: 20%;">Kategori</th>
                                <th class="text-muted fw-semibold py-1" style="width: 20%;">Status</th>
                                <th class="text-muted fw-semibold py-1" style="width: 20%;">Terakhir Diperbarui</th>
                            </tr>
                        </thead>
                        <tbody id="riwayat-tbody">
                            @if(isset($recentActivity) && count($recentActivity) > 0)
                                @foreach($recentActivity as $index => $item)
                                    @php
                                        $kategoriColor = match($item->kategori ?? '') {
                                            'Perjalanan Dinas' => '#fda10d',
                                            'Perjadin Kegiatan' => '#456dee',
                                            'Pemeliharaan' => '#2CCE82',
                                            default => '#6c757d',
                                        };
                                        $statusRaw = strtolower($item->status ?? '');
                                        $statusLabel = ucfirst($item->status ?? '-');
                                        $statusStyle = match(true) {
                                            str_contains($statusRaw, 'draf') => 'background: #fff3cd; color: #856404;',
                                            str_contains($statusRaw, 'pengajuan') || str_contains($statusRaw, 'usulan') || str_contains($statusRaw, 'menunggu') => 'background: #cfe2ff; color: #084298;',
                                            str_contains($statusRaw, 'proses') || str_contains($statusRaw, 'disetujui') || str_contains($statusRaw, 'pengecekan') || str_contains($statusRaw, 'diterima') => 'background: #d1e7dd; color: #0f5132;',
                                            str_contains($statusRaw, 'revisi') => 'background: #fff3cd; color: #856404;',
                                            str_contains($statusRaw, 'tolak') || str_contains($statusRaw, 'batal') => 'background: #f8d7da; color: #842029;',
                                            str_contains($statusRaw, 'selesai') => 'background: #d1e7dd; color: #0f5132;',
                                            str_contains($statusRaw, 'pelaporan') || str_contains($statusRaw, 'pemeriksaan') => 'background: #e2e3f1; color: #3d3d6b;',
                                            default => 'background: #e9ecef; color: #495057;',
                                        };
                                    @endphp
                                    <tr class="riwayat-row" data-index="{{ $index }}">
                                        <td class="ps-2 py-2">{{ $index + 1 }}</td>
                                        <td class="py-2 fw-semibold text-dark">{{ Str::limit($item->nama ?? '-', 55) }}</td>
                                        <td class="py-2">
                                            <span class="badge rounded-pill" style="background-color: {{ $kategoriColor }}15; color: {{ $kategoriColor }}; font-weight: 600; padding: 3px 8px; font-size: 0.65rem;">
                                                {{ $item->kategori ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="py-2">
                                            <span class="badge rounded-pill" style="{{ $statusStyle }} font-weight: 600; padding: 3px 8px; font-size: 0.65rem;">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td class="py-2 text-muted">
                                            {{ \Carbon\Carbon::parse($item->updated_at)->translatedFormat('d M Y, H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-inbox mb-1" style="font-size: 1.5rem; color: #c5c9d3;"></i>
                                        <p class="mb-0 mt-1" style="font-size: 0.75rem;">Belum ada riwayat pengajuan.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                {{-- Tombol lihat semua --}}
                <div id="riwayat-footer" class="text-center mt-2" style="flex-shrink: 0; display: none;">
                    <a href="javascript:void(0)" id="toggle-riwayat-btn" class="text-decoration-none" style="font-size: 0.72rem; color: #082A99;">
                        <i id="riwayat-more-icon" class="fa-solid fa-angles-down me-1"></i>
                        <span id="riwayat-more-text"></span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var isExpanded = false;
    var btn = document.getElementById('toggle-riwayat-btn');
    if(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            isExpanded = !isExpanded;
            adjustRiwayatRows();
        });
    }

    function adjustRiwayatRows() {
        var allRows = document.querySelectorAll('#riwayat-tbody .riwayat-row');
        if (allRows.length === 0) return;

        var container = document.getElementById('riwayat-table-container');
        var wrapper   = container.closest('.dashboard-table-wrapper');
        var header    = wrapper.querySelector('.d-flex.align-items-center');
        var footerEl  = document.getElementById('riwayat-footer');
        var thead     = document.getElementById('riwayat-thead');
        var moreText  = document.getElementById('riwayat-more-text');
        var moreIcon  = document.getElementById('riwayat-more-icon');
        var counter   = document.getElementById('riwayat-counter');

        // Hitung ruang yang tersedia dari awal layar (mengabaikan scroll sementara)
        // Kita kunci perhitungan berdasarkan tinggi layar dan elemen lain, bukan posisi relatif yang berubah saat scroll
        var headerH  = header ? header.offsetHeight + 8 : 0; // +mb-2
        var theadH   = thead ? thead.offsetHeight : 0;
        var footerH  = 28; // perkiraan tinggi footer link
        var paddingV = 24; // padding wrapper (p-3 = 16px top + 16px bottom)
        var marginBot = 16; // margin section bawah

        // Secara default, wrapper tabel di dashboard ini mulai sekitar 450px dari atas layar 
        // (setelah navbar dan 3 card kotak)
        var estimatedTopOffset = 450; 
        // Coba deteksi aslinya jika page tidak discroll, kalau tidak pakai fallback 450
        var scrollY = window.pageYOffset || document.documentElement.scrollTop;
        var absoluteTop = wrapper.getBoundingClientRect().top + scrollY;
        if (absoluteTop > 100 && absoluteTop < 800) {
            estimatedTopOffset = absoluteTop;
        }

        var availH = window.innerHeight - estimatedTopOffset - headerH - theadH - footerH - paddingV - marginBot;
        availH = Math.max(availH, 60); // minimal tampil 1 baris

        // Ukur tinggi 1 baris (pakai baris pertama)
        var firstRow = allRows[0];
        var rowH = firstRow.offsetHeight || 34; // fallback 34px

        var maxVisible = Math.floor(availH / rowH);
        maxVisible = Math.max(maxVisible, 1); // minimal 1

        if (isExpanded) {
            // Tampilkan semua data, biarkan tabel memanjang ke bawah (dropdown)
            allRows.forEach(function(r) { r.style.display = ''; });
            container.style.maxHeight = 'none';
            container.style.overflowY = 'visible';
            if (moreText) moreText.textContent = 'Sembunyikan';
            if (moreIcon) {
                moreIcon.classList.remove('fa-angles-down');
                moreIcon.classList.add('fa-angles-up');
            }
            if (counter) counter.textContent = 'Menampilkan semua ' + allRows.length + ' data';
            return;
        }

        // Jika tidak expand (Tutup)
        allRows.forEach(function(r) { r.style.display = ''; });
        container.style.overflowY = 'hidden';
        container.style.maxHeight = 'none';

        // Terapkan: sembunyikan row di luar batas
        var hidden = 0;
        allRows.forEach(function(row, idx) {
            if (idx >= maxVisible) {
                row.style.display = 'none';
                hidden++;
            }
        });

        var totalRows = allRows.length;
        var showing   = Math.min(maxVisible, totalRows);
        
        if (counter) counter.textContent = 'Menampilkan ' + showing + ' dari ' + totalRows + ' data';

        if (hidden > 0) {
            footerEl.style.display = '';
            if (moreText) moreText.textContent = 'Lihat ' + hidden + ' data lainnya di bawah';
            if (moreIcon) {
                moreIcon.classList.remove('fa-angles-up');
                moreIcon.classList.add('fa-angles-down');
            }
        } else {
            footerEl.style.display = 'none';
        }
    }

    // Jalankan saat load & saat resize
    adjustRiwayatRows();
    window.addEventListener('resize', adjustRiwayatRows);
});
</script>
@endsection
