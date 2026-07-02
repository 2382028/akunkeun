@extends('admin.templates.sidebar')

@section('contain')
<!-- Awal Form Perjadin Biasa -->
<section id="beranda" class="py-4">
    <div class="container">
        <div class="row text-secondary justify-content-center">
            <div class="col-md-12 mb-3">
                <h3 class="fw-bold text-secondary mb-3">Detail Perjalanan Dinas</h3>
                <div class="card shadow-lg rounded-0 border-0 pt-2 pb-2 px-3" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    <div class="card-body">
                        <!-- Step 2: Informasi Perjadin -->
                        <div class="mb-3">
                            <h5 class="fw-bold text-secondary">Informasi Perjadin</h5>
                            <div class="card shadow-sm rounded-0 mt-3 p-3 border-0">
                                <div class="card-body lh-1">
                                    <div class="row mb-3">
                                        <div class="col-md-4">Judul Surat Undangan</div>
                                        <input type="hidden" value="{{ $perjadin->id }}">
                                        <div class="col-md-8">{{ $perjadin->nama_kegiatan }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">Tanggal Pelaksanaan</div>
                                        <div class="col-md-8">{{ \Carbon\Carbon::parse($perjadin->tgl_mulai)->format('d-m-Y H:i:s') }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">Lokasi</div>
                                        <div class="col-md-8">{{ $perjadin->alamat }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">Nomor Surat Tugas</div>
                                        <div class="col-md-8">{{ $perjadin->kode_surat_tugas }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">Status Pengajuan</div>
                                        <div class="col-md-8">{{ $perjadin->status_pengajuan_detail }}</div>
                                    </div>
                                    @if($perjadin->status_pengajuan == 'ditolak')
                                    <div class="row mb-3">
                                        <div class="col-md-4">Alasan Penolakan</div>
                                        <div class="col-md-8">{{ $perjadin->alasan_penolakan }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Peserta -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-secondary">Informasi Peserta</h6>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr class="small text-center">
                                            <th>No</th>
                                            <th>Nama (Pegawai)</th>
                                            <th>Pangkat/Golongan</th>
                                            <th>Status</th>
                                            @if ($tipe=='keuangan' || $tipe=='keuangan-semua')
                                                <th>Nominal Perjadin</th>
                                            @endif
                                            <th>Persetujuan</th>
                                            @if (in_array($perjadin->status_pengajuan, ['Draf-pengajuan', 'pengajuan', 'revisi']))
                                            <th>Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $offset = count($selectPesertas); // Menghitung jumlah data dari $selectPesertas
                                            $subTotalPeserta = 0; // Menghitung jumlah data dari $selectPesertas
                                        @endphp
                                        @foreach ($selectPesertas as $selectPeserta)
                                        <tr>
                                            <td class='text-center'>{{ $loop->iteration }}</td>
                                            <td>{{ $selectPeserta->nama_lengkap }}</td>
                                            <td class='text-center'>{{ $selectPeserta->golongan }}-{{ $selectPeserta->pangkat }}</td>
                                            <td class='text-center'>{{ $selectPeserta->status_pegawai }}</td>
                                            @if ($tipe=='keuangan' || $tipe=='keuangan-semua')
                                                @php $subTotalPeserta += $selectPeserta->nominal_perjadin; @endphp 
                                                <td class='text-center akun-tooltip-perjadin' data-id-akun-perjadin="{{ $selectPeserta->idAkunHarian }}">Rp {{ number_format($selectPeserta->nominal_perjadin, 0, ',', '.') }}</td>
                                            @endif
                                            <td class='text-center'>{{ $selectPeserta->status_persetujuan }}</td>
                                            @if (in_array($perjadin->status_pengajuan, ['Draf-pengajuan', 'pengajuan', 'revisi']))
                                            <td class='text-center'>
                                                <form action="{{url('/h_peserta_peserta_detail/' . $selectPeserta->idPeserta)}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="info_perjadinlangsung" value="{{ $perjadin->id }}">
                                                    <button disabled type="submit" class="btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data Peserta?')" @if (in_array($perjadin->status_pengajuan, ['pengajuan', 'proses'])) disabled @endif>
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                        @foreach ($selectPesertasNonPegawais as $selectPeserta)
                                        <tr>
                                            <td class='text-center'>{{ $loop->iteration + $offset }}</td>
                                            <td>{{ $selectPeserta->nama_lengkap }}</td>
                                            <td class='text-center'>{{ $selectPeserta->golongan }}-{{ $selectPeserta->pangkat }}</td>
                                            <td class='text-center'>{{ $selectPeserta->status_pegawai }}</td>
                                            @if ($tipe=='keuangan' || $tipe=='keuangan-semua')
                                                @php $subTotalPeserta += $selectPeserta->nominal_perjadin; @endphp
                                                <td class='text-center akun-tooltip-perjadin' data-id-akun-perjadin="{{ $selectPeserta->idAkunHarian }}">Rp {{ number_format($selectPeserta->nominal_perjadin, 0, ',', '.') }}</td>
                                            @endif
                                            <td class='text-center'>{{ $selectPeserta->status_persetujuan }}</td>
                                            @if (in_array($perjadin->status_pengajuan, ['Draf-pengajuan', 'pengajuan', 'revisi']))
                                            <td class='text-center'>
                                                <form action="{{url('/h_peserta_peserta_detail/' . $selectPeserta->idPeserta)}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="info_perjadinlangsung" value="{{ $perjadin->id }}">
                                                    <button disabled type="submit" class="btn btn-danger btn-sm text-white" onclick="return confirm('Hapus Data Peserta?')" @if (in_array($perjadin->status_pengajuan, ['pengajuan', 'proses'])) disabled @endif>
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                        @if ($tipe=='keuangan' || $tipe=='keuangan-semua')
                                            <tr>
                                                <td class="text-center" colspan="4">
                                                    <strong>Sub Total</strong>
                                                </td>
                                                <td class='text-center'><strong>Rp {{ number_format($subTotalPeserta, 0, ',', '.') }}</strong></td>
                                                <td class="text-center">
                                                </td>
                                            </tr>
                                         @endif
                                    </tbody>
                                </table>
                            </div>
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

                        <!-- Informasi Mobilitas -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-secondary">Informasi Mobilitas</h6>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr class="small text-center">
                                            <th>No</th>
                                            <th>Pengemudi</th>
                                            <th>Mobil</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mobilitass as $mobilitas)
                                        <tr>
                                            <td class='text-center'>{{ $loop->iteration }}</td>
                                            <td>{{ $mobilitas->nama_lengkap }}</td>
                                            <td class='text-center'>{{ $mobilitas->merek }} [{{ $mobilitas->no_polisi }}]</td>
                                            <td class='text-center'>{{ $mobilitas->status }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Kelengkapan Dokumen -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-secondary">Kelengkapan Dokumen</h6>
                            <div class="alert alert-success">
                                Status Dokumen: 
                                @if ($dokumen) 
                                {{$dokumen->status_persetujuan}}
                                @else Belum Diupload 
                                @endif
                            </div>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr class="text-center small">
                                        <th>No</th>
                                        <th>Nama Dokumen</th>
                                        <th>Lampiran</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class='text-center'>1</td>
                                        <td>{{ $perjadin->nama_kegiatan }}</td>
                                        <td class='text-center'>
                                            @if ($dokumen && $dokumen->surat_undangan)
                                        <a href="{{ url('/usulanperjadin-AdmingetDokumen/'.basename($dokumen->surat_undangan)) }}" target="_blank" class="text-decoration-none text-info">[Lihat Lampiran]</a>
                                            @else Laporan Belum Diunggah @endif</td>
                                       
                                    </tr>
                                    <tr>
                                        <td class='text-center'>2</td>
                                        <td>Surat Tugas</td>
                                        <td class='text-center'>
                                            @if ($dokumen && $dokumen->surat_tugas)
                                        <a href="{{ url('/usulanperjadin-AdmingetDokumen/'.basename($dokumen->surat_tugas)) }}" target="_blank" class="text-decoration-none text-info">[Lihat Lampiran]</a>
                                            @else Laporan Belum Diunggah @endif</td>
                                    </tr>
                                    <tr>
                                        <td class='text-center'>3</td>
                                        <td>Laporan Perjalanan Dinas</td>
                                        <td class='text-center'>
                                            @if ($dokumen && $dokumen->lap_perjadin)
                                        <a href="{{ url('/usulanperjadin-AdmingetDokumen/'.basename($dokumen->lap_perjadin)) }}" target="_blank" class="text-decoration-none text-info">[Lihat Lampiran]</a>
                                            @else Laporan Belum Diunggah @endif</td>
                                    </tr>
                                    <tr>
                                        <td class='text-center'>4</td>
                                        <td>Laporan Pengeluaran Perjalan Dinas</td>
                                        <td class='text-center'>
                                            @if ($dokumen && $dokumen->lap_pengeluaran)
                                        <a href="{{ url('/usulanperjadin-AdmingetDokumen/'.basename($dokumen->lap_pengeluaran)) }}" target="_blank" class="text-decoration-none text-info">[Lihat Lampiran]</a>
                                            @else Laporan Belum Diunggah @endif</td>
                                    </tr>
                                    <!-- Dokumen lainnya seperti Surat Tugas, SPPD, Laporan Pengeluaran -->
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="btns-group d-flex justify-content-center pb-3">
                            @if ($tipe == 'keuangan')
                                <a href="{{ route('monitoring-keuangan') }}" class="btn btn-warning col-md-2 text-white">Kembali</a>
                            @elseif ($tipe == 'keuangan-semua')
                                <a href="javascript:history.back()" class="btn btn-warning col-md-2 text-white">Kembali</a>
                            @else 
                                <a href="{{ route('monitoring') }}" class="btn btn-warning col-md-2 text-white">Kembali</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="akunTooltip" style="display: none; position: absolute; background: #f9f9f9; border: 1px solid #ccc; padding: 10px; font-size: 12px; z-index: 1000; max-width: 300px;"></div>
<div id="akunTooltipPerjadin" style="display: none; position: absolute; background: #f9f9f9; border: 1px solid #ccc; padding: 10px; font-size: 12px; z-index: 1000; max-width: 300px;"></div>


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
    const tourP = new Shepherd.Tour({
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
    tourP.addStep({
        id: 'tooltip-akun',
        // title: 'Tips Cepat 🔍',
        text: 'Arahkan kursor ke nominal untuk melihat <strong>Detail Akun</strong>!',
        attachTo: {
            element: '.akun-tooltip-perjadin',
            on: 'bottom'
        },
        buttons: [] // tanpa tombol
    });

    // Jalankan tour
    tour.start();
    tourP.start();

    // Otomatis hilang setelah 4 detik
    setTimeout(() => {
        tour.complete();
        tourP.complete();
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

@endsection
