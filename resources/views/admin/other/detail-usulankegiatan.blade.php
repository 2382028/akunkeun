@extends('admin.templates.sidebar')

@section('contain')
<section id="detail-kegiatan" class="py-4">
    <div class="container">
        <div class="row text-secondary justify-content-center">
            <div class="col-md-12 mb-4">
                <h3 class="fw-bold text-secondary mb-4">Detail Perjalanan Kegiatan</h3>

                <!-- Informasi Program Section -->
                <div class="card shadow-lg rounded-0 border-0 pt-2 pb-2 px-3" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    <h5 class="fw-bold text-secondary mt-2 mb-3">Informasi Kegiatan</h5>
                    <div class="card-body">
                    <div class="row mb-1">
                        <div class="col-md-4 fw-bold ">Judul Program</div>
                        <div class="col-md-8 ">{{ $kegiatan->nama_kegiatan }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4 fw-bold ">Jenis Kegiatan</div>
                        <div class="col-md-8 ">{{ $kegiatan->jenis_kegiatan }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4 fw-bold ">Tanggal Pelaksanaan</div>
                        <div class="col-md-8 ">{{ \Carbon\Carbon::parse($kegiatan->tgl_mulai)->format('d-m-Y H:i') }} s.d {{ \Carbon\Carbon::parse($kegiatan->tgl_selesai)->format('d-m-Y H:i') }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4 fw-bold ">Lokasi</div>
                        <div class="col-md-8 ">{{ $kegiatan->alamat }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4 fw-bold ">Jumlah Peserta</div>
                        <div class="col-md-8 ">{{ $kegiatan->jumlah_peserta ?? '0'}}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4 fw-bold ">Jumlah Kamar</div>
                        <div class="col-md-8 ">{{ $kegiatan->jumlah_kamar ?? '0' }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4 fw-bold ">Status Pengajuan</div>
                        <div class="col-md-8 ">{{ $kegiatan->status_pengajuan }}</div>
                    </div>
                    @if($kegiatan->status_pengajuan == 'ditolak')
                    <div class="row mb-3">
                        <div class="col-md-4">Alasan Penolakan</div>
                        <div class="col-md-8">{{ $kegiatan->alasan_penolakan }}</div>
                    </div>
                    @endif                                    
                </div>

                <!-- Informasi Peserta Section -->
              
                    <h5 class="fw-bold text-secondary mb-3">Informasi Peserta</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="text-center">
                                 <tr class="small text-center">
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Pangkat/Golongan</th>
                                    <th>Sebagai</th>
                                    @if ($tipe=='keuangan' || $tipe=='keuangan-semua')
                                        <th>Nominal Perjadin</th>
                                        <th>Honorarium</th>
                                    @endif
                                    <th>Status</th>
                                </tr>
                            </thead>
                            @php
                                $subTotalPerjadin = 0;
                                $subTotalHonorarium = 0;
                            @endphp
                            <tbody>
                                @foreach ($perangkats as $perangkat)
                                <tr class="small text-center">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $perangkat->nama_lengkap }}</td>
                                    <td>{{ $perangkat->golongan ?? '-' }} - {{ $perangkat->pangkat ?? '-' }}</td>
                                    <td>{{ $perangkat->sebagai }}</td>
                                    @if ($tipe=='keuangan' || $tipe=='keuangan-semua')
                                        @php
                                            $subTotalPerjadin += $perangkat->nominal_perjadin;
                                            $subTotalHonorarium += $perangkat->nominal_honorarium;
                                        @endphp
                                        <td class='text-center akun-tooltip-perjadin' data-id-akun-perjadin="{{ $perangkat->idAkunHarian }}">Rp {{ number_format($perangkat->nominal_perjadin, 0, ',', '.') }}</td>
                                        <td class='text-center akun-tooltip-honorarium' data-id-akun-honorarium="{{ $perangkat->idAkunHonor }}">Rp {{ number_format($perangkat->nominal_honorarium, 0, ',', '.') }}</td>
                                    @endif
                                    <td>{{ $perangkat->status }}</td>
                                </tr>
                                @endforeach
                                @if ($tipe=='keuangan' || $tipe=='keuangan-semua')
                                    <tr>
                                        <td class="text-center" colspan="4">
                                            <strong>Sub Total</strong>
                                        </td>
                                        <td class='text-center'><strong>Rp {{ number_format($subTotalPerjadin, 0, ',', '.') }}</strong></td>
                                        <td class='text-center'><strong>Rp {{ number_format($subTotalHonorarium, 0, ',', '.') }}</strong></td>
                                        <td class="text-center">
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            
                        </table>
                    </div>

                    
                
                    <!-- Informasi Fasilitas -->
                    <div class="mb-4">
                            <h6 class="fw-bold text-secondary">Informasi Fasilitas</h6>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr class="small text-center">
                                            <th>No</th>
                                            <th>Nama Fasilitas</th>
                                            <th>Jumlah Kebutuhan</th>
                                            <th>Satuan</th>
                                            <th>Tipe Pendanaan</th>
                                            @if ($tipe=='keuangan' || $tipe=='keuangan-semua')
                                                <th>Nominal</th>
                                            @endif
                                            <th>Persetujuan</th>
                                        </tr>
                                    </thead>
                                    @php
                                        $subTotalFasilitas = 0; // Menghitung jumlah data dari $selectPesertas
                                    @endphp
                                    <tbody>
                                        @foreach ($fasilitas as $fasilita)
                                        <tr>
                                            <td class='text-center'>{{ $loop->iteration }}</td>
                                            <td>{{ $fasilita->nama }}</td>
                                            <td class='text-center'>{{ $fasilita->jumlah_frekuensi }}</td>
                                            <td class='text-center'>{{ $fasilita->satuan }}</td>
                                            <td class='text-center'>{{ $fasilita->tipe_pendanaan }}</td>
                                            @if ($tipe=='keuangan' || $tipe=='keuangan-semua')
                                                @php $subTotalFasilitas += $fasilita->nominal; @endphp
                                                <td class="text-center akun-tooltip" data-id-akun="{{ $fasilita->idAkunKebutuhan }}">
                                                    Rp {{ number_format($fasilita->nominal, 0, ',', '.') }}
                                                </td>
                                            @endif
                                            <td class='text-center'>{{ $fasilita->status }}</td>
                                        </tr>
                                        @endforeach
                                        @if ($tipe=='keuangan' || $tipe=='keuangan-semua')
                                            <tr>
                                                <td class="text-center" colspan="5">
                                                    <strong>Sub Total</strong>
                                                </td>
                                                <td class='text-center'><strong>Rp {{ number_format($subTotalFasilitas, 0, ',', '.') }}</strong></td>
                                                <td class="text-center">
                                                </td>
                                            </tr>
                                         @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>


                <!-- Informasi Mobilitas Section -->
               
                    <h5 class="fw-bold text-secondary mb-3">Informasi Mobilitas</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="text-center small">
                                 <tr class="small text-center">
                                    <th>No</th>
                                    <th>Mobilitas</th>
                                    <th>Tujuan Penggunaan</th>
                                    <th>Tanggal Digunakan</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mobilities as $mobil)
                                 <tr class="small text-center">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $mobil->mobilitas }}</td>
                                    <td>{{ $mobil->tujuan_penggunaan }}</td>
                                    <td>{{ $mobil->tgl_mulai }}</td>
                                    <td>{{ $mobil->tgl_selesai }}</td>
                                    <td>{{ $mobil->status }}</td>
                                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
            

                <!-- Informasi Sarana Prasarana Section -->
               
                    <h5 class="fw-bold text-secondary mb-3">Informasi Sarana Prasarana</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="text-center small">
                                 <tr class="small text-center">
                                    <th>No</th>
                                    <th>Sarana</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal Digunakan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sapras as $sapra)
                                <tr class="small ">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $sapra->nama_barang }}</td>
                                    <td class="text-center">{{ $sapra->jumlah_asset }}</td>
                                    <td class="text-center">{{ $sapra->tgl_peminjaman }}</td>
                                    <td class="text-center">{{ $sapra->status }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
               

                <!-- Informasi Dokumen Section -->
               
                    <h5 class="fw-bold text-secondary mb-3">Informasi Dokumen</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="text-center small">
                                 <tr class="small text-center">
                                    <th>No</th>
                                    <th>Nama Dokumen</th>
                                    <th>Lampiran</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dokumens as $dokumen)
                                @if ($dokumen->file == null || $dokumen->file == '' || $dokumen->file == '-')
                                    @continue
                                @endif
                                <tr class="small">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $dokumen->nama_dokumen }}</td>
                                    <td class="text-center">
                                        <a href="{{ url('usulankegiatan/getDokumenKegiatan/' . basename($dokumen->file)) }}" target="_blank" class="text-decoration-none text-info">[Lihat Lampiran]</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
               
                <!-- Kembali Button -->
                <div class="btns-group d-flex justify-content-center pb-3">
                    @if ($tipe == 'keuangan')
                        <a href="{{ route('monitoring-keuangan') }}" class="btn btn-warning col-md-2 text-white">Kembali</a>
                    @else 
                        <a href="{{ route('monitoring') }}" class="btn btn-warning col-md-2 text-white">Kembali</a>
                    @endif
                </div>
            </div>
            
    </div>
    
</section>


<div id="akunTooltip" style="display: none; position: absolute; background: #f9f9f9; border: 1px solid #ccc; padding: 10px; font-size: 12px; z-index: 1000; max-width: 300px;"></div>
<div id="akunTooltipPerjadin" style="display: none; position: absolute; background: #f9f9f9; border: 1px solid #ccc; padding: 10px; font-size: 12px; z-index: 1000; max-width: 300px;"></div>
<div id="akunTooltipHonorarium" style="display: none; position: absolute; background: #f9f9f9; border: 1px solid #ccc; padding: 10px; font-size: 12px; z-index: 1000; max-width: 300px;"></div>


<style>
    /* Custom Shepherd Tooltip Style */
    .shepherd-theme-custom .shepherd-content {
        background-color: #fffbe6;
        color: #333;
        border: 2px solid #ffd700;
        border-radius: 12px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        font-family: 'Segoe UI', sans-serif;
        padding: 16px;
        font-size: 14px;
    }

    .shepherd-theme-custom .shepherd-arrow:before {
        border-bottom-color: #ffd700;
    }
</style>

<!-- Tambahkan di head -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/shepherd.js/dist/css/shepherd.css">
<script src="https://cdn.jsdelivr.net/npm/shepherd.js/dist/js/shepherd.min.js"></script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    // Cek apakah sudah pernah ditampilkan
    // if (localStorage.getItem('tooltipPanduanSeen')) return;

    const tour = new Shepherd.Tour({
        defaultStepOptions: {
            classes: 'shepherd-theme-custom',
            scrollTo: false,
            cancelIcon: { enabled: false },
            modalOverlayOpeningPadding: 4
        }
    });
    const tourH = new Shepherd.Tour({
        defaultStepOptions: {
            classes: 'shepherd-theme-custom',
            scrollTo: false,
            cancelIcon: { enabled: false },
            modalOverlayOpeningPadding: 4
        }
    });

    tour.addStep({
        id: 'tooltip-akun',
        // title: 'Tips Cepat 🔍',
        text: 'Arahkan kursor ke nominal untuk melihat <strong>Detail Akun</strong>!',
        attachTo: {
            element: '.akun-tooltip',
            on: 'bottom'
        },
        buttons: [] // tanpa tombol
    });
    tourH.addStep({
        id: 'tooltip-akun',
        // title: 'Tips Cepat 🔍',
        text: 'Arahkan kursor ke nominal untuk melihat <strong>Detail Akun</strong>!',
        attachTo: {
            element: '.akun-tooltip-honorarium',
            on: 'bottom'
        },
        buttons: [] // tanpa tombol
    });

    // Jalankan tour
    tour.start();
    tourH.start();

    // Otomatis hilang setelah 4 detik
    setTimeout(() => {
        tour.complete();
        tourH.complete();
        // localStorage.setItem('tooltipPanduanSeen', 'true');
    }, 4000); // 4000 ms = 4 detik
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tooltip = document.getElementById('akunTooltip');
    let currentTd = null;

    document.body.addEventListener('mouseover', async function (e) {
        const td = e.target.closest('.akun-tooltip');
        if (td && td !== currentTd) {
            currentTd = td;
            const id = td.getAttribute('data-id-akun');
            if (!id) return;

            try {
                const response = await fetch(`/get-akuns-only-json/${id}`);
                const data = await response.json();

                let content = `<strong>Detail Akun:</strong><br>
                    <div style="background:#eee; padding:5px; border-radius:4px; font-family:monospace;">${data}</div>`;

                tooltip.innerHTML = content;
                tooltip.style.display = 'block';
            } catch (error) {
                tooltip.innerHTML = '<span style="color: red;">Gagal mengambil data akun.</span>';
                tooltip.style.display = 'block';
            }
        }
    });

    document.body.addEventListener('mousemove', function (e) {
        const td = e.target.closest('.akun-tooltip');
        if (td) {
            tooltip.style.left = (e.pageX + 10) + 'px';
            tooltip.style.top = (e.pageY + 10) + 'px';
        } else {
            tooltip.style.display = 'none';
            currentTd = null;
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tooltip = document.getElementById('akunTooltipPerjadin');
    let currentTd = null;

    document.body.addEventListener('mouseover', async function (e) {
        const td = e.target.closest('.akun-tooltip-perjadin');
        if (td && td !== currentTd) {
            currentTd = td;
            const id = td.getAttribute('data-id-akun-perjadin');
            if (!id) return;

            try {
                const response = await fetch(`/get-akuns-only-json/${id}`);
                const data = await response.json();

                let content = `<strong>Detail Akun:</strong><br>
                    <div style="background:#eee; padding:5px; border-radius:4px; font-family:monospace;">${data}</div>`;

                tooltip.innerHTML = content;
                tooltip.style.display = 'block';
            } catch (error) {
                tooltip.innerHTML = '<span style="color: red;">Gagal mengambil data akun.</span>';
                tooltip.style.display = 'block';
            }
        }
    });

    document.body.addEventListener('mousemove', function (e) {
        const td = e.target.closest('.akun-tooltip-perjadin');
        if (td) {
            tooltip.style.left = (e.pageX + 10) + 'px';
            tooltip.style.top = (e.pageY + 10) + 'px';
        } else {
            tooltip.style.display = 'none';
            currentTd = null;
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tooltip = document.getElementById('akunTooltipHonorarium');
    let currentTd = null;

    document.body.addEventListener('mouseover', async function (e) {
        const td = e.target.closest('.akun-tooltip-honorarium');
        if (td && td !== currentTd) {
            currentTd = td;
            const id = td.getAttribute('data-id-akun-honorarium');
            if (!id) return;

            try {
                const response = await fetch(`/get-akuns-only-json/${id}`);
                const data = await response.json();

                let content = `<strong>Detail Akun:</strong><br>
                    <div style="background:#eee; padding:5px; border-radius:4px; font-family:monospace;">${data}</div>`;

                tooltip.innerHTML = content;
                tooltip.style.display = 'block';
            } catch (error) {
                tooltip.innerHTML = '<span style="color: red;">Gagal mengambil data akun.</span>';
                tooltip.style.display = 'block';
            }
        }
    });

    document.body.addEventListener('mousemove', function (e) {
        const td = e.target.closest('.akun-tooltip-honorarium');
        if (td) {
            tooltip.style.left = (e.pageX + 10) + 'px';
            tooltip.style.top = (e.pageY + 10) + 'px';
        } else {
            tooltip.style.display = 'none';
            currentTd = null;
        }
    });
});
</script>




<!-- AOS Animation -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init();
</script>
@endsection
