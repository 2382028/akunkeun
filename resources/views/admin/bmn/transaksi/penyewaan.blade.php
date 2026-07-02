@extends('admin.templates.sidebar')

@section('contain')
<style>
    /* Modal Tambah Kategori tampil di atas Modal Tambah Kamar */
    #modalTambahKategori {
        z-index: 1060;
    }

    .modal-backdrop.show:nth-of-type(2) {
        z-index: 1055;
    }

    /* Padding untuk list fasilitas dan kategori */
    #listFasilitasWrapper li,
    #listKategoriWrapper li {
        padding: 0.5rem 1rem;
    }

    /* Warna background bergantian */
    #listFasilitasWrapper li:nth-child(odd),
    #listKategoriWrapper li:nth-child(odd) {
        background-color: #ffffff; /* putih */
    }

    #listFasilitasWrapper li:nth-child(even),
    #listKategoriWrapper li:nth-child(even) {
        background-color: #f8f9fa; /* abu muda seperti baris genap DataTable */
    }

    /* Hover effect */
    #listFasilitasWrapper li:hover,
    #listKategoriWrapper li:hover {
        background-color: #e2e6ea; /* sedikit gelap saat hover */
    }
</style>


    @php use Carbon\Carbon; @endphp

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-md-12 d-flex justify-content-between align-items-center">
                <h4>BMN / <span class="fw-bold">Data Penyewaan Aset</span></h4>

                @if (array_intersect(['Petugas Mess', 'Admin'], $admin_roles) || in_array('Petugas Mess', $admin_roles))
                    <div class="d-flex gap-2">
                        @if (array_intersect(['Petugas Mess', 'Admin'], $admin_roles))
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kelolaKamarModal">
                                <i class="bi bi-gear me-1"></i> Kelola Kamar
                            </button>
                        @endif

                        @if (in_array('Petugas Mess', $admin_roles))
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajukanSewaModal">
                                <i class="bi bi-plus-circle me-1"></i> Ajukan Sewa
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="card border-0 bg-secondary">
                    <div class="page wrapper d-flex flex-wrap align-items-center gap-1">

                        @if (in_array('Bendahara Penyewaan', $admin_roles))
                            <a href="{{ route('penyewaan_aset', 'menunggu') }}"
                                class="page-wrap btn btn-sm {{ request()->segment(2) == 'menunggu' ? 'btn-dark fw-bold' : 'btn-primary' }}">
                                Verifikasi <span class="badge bg-light text-dark">{{ $counts['menunggu'] ?? 0 }}</span>
                            </a>
                        @endif
                        @if (in_array('Petugas Mess', $admin_roles))
                            <a href="{{ route('penyewaan_aset', 'verifikasi') }}"
                                class="page-wrap btn btn-sm {{ request()->segment(2) == 'verifikasi' ? 'btn-dark fw-bold' : 'btn-warning text-white' }}">
                                Pengajuan Sewa <span
                                    class="badge bg-light text-dark">{{ $counts['verifikasi'] ?? 0 }}</span>
                            </a>
                            </a>
                            <a href="{{ route('penyewaan_aset', 'diterima') }}"
                                class="page-wrap btn btn-sm {{ request()->segment(2) == 'diterima' ? 'btn-dark fw-bold' : 'btn-sm btn-success' }}">
                                Sedang Berlangsung <span
                                    class="badge bg-light text-dark">{{ $counts['diterima'] ?? 0 }}</span>
                            </a>
                        @endif
                        @if (array_intersect(['Petugas Mess', 'Admin'], $admin_roles))
                            <a href="{{ route('penyewaan_aset', 'selesai') }}"
                                class="page-wrap btn btn-sm {{ request()->segment(2) == 'selesai' && request()->query('pnbp') != 'pengajuan' ? 'btn-dark fw-bold' : 'btn-secondary' }}">
                                Riwayat Sewa <span class="badge bg-light text-dark">{{ $counts['selesai'] ?? 0 }}</span>
                            </a>
                        @endif

                        @if (in_array('Bendahara Penyewaan', $admin_roles))
                            <a href="{{ route('penyewaan_aset', 'selesai') }}?pnbp=pengajuan"
                                class="page-wrap btn btn-sm {{ request()->segment(2) == 'selesai' && request()->query('pnbp') == 'pengajuan' ? 'btn-dark fw-bold' : 'btn-secondary' }}">
                                Pengajuan PNBP <span
                                    class="badge bg-light text-dark">{{ $counts['pnbp_pengajuan'] ?? 0 }}</span>
                            </a>
                        @endif
                        @if (array_intersect(['Bendahara Penyewaan', 'Admin'], $admin_roles))
                            <a href="{{ route('penyewaan_aset', 'setoran_pnbp') }}"
                                class="page-wrap btn btn-sm {{ request()->segment(2) == 'setoran_pnbp' ? 'btn-dark fw-bold' : 'btn-secondary' }}">
                                Setoran PNBP <span
                                    class="badge bg-light text-dark">{{ $counts['setoran_pnbp'] ?? 0 }}</span>
                            </a>
                        @endif
                        <a href="{{ route('penyewaan_aset', 'ditolak') }}"
                            class="page-wrap btn btn-sm {{ request()->segment(2) == 'ditolak' ? 'btn-dark fw-bold' : 'btn-danger' }}">
                            Riwayat Penolakan & Pembatalan <span
                                class="badge bg-light text-dark">{{ $counts['ditolak'] ?? 0 }}</span>
                        </a>
                        <button type="button" class="btn btn-sm btn-info text-dark ms-auto" data-bs-toggle="modal"
                            data-bs-target="#rekapModal">
                            <i class="fas fa-chart-bar me-1"></i> Rekapitulasi
                        </button>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-2">
                @if (request()->segment(2) == 'setoran_pnbp' && array_intersect(['Bendahara Penyewaan', 'Admin'], $admin_roles))
                    <div class="card mt-3">
                        <div class="card-body">
                            <table id="example-pnbp" class="table table-bordered data-table" style="width: 100%">
                                <thead class="text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Pemesanan</th>
                                        <th>Total Harga</th>
                                        <th>Total Setoran</th>
                                        <th>Status</th>
                                        <th>No NTB</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pnbp_list as $i => $pnbp)
                                        @php
                                            $pemesanans = $pnbp->pemesanans; // relasi hasManyThrough Pemesanan
                                            $kode_pemesanans = $pemesanans->pluck('kode_pemesanan')->toArray();
                                            $subtotals = $pemesanans->pluck('subtotal')->toArray();
                                            $total_harga = array_sum($subtotals);
                                            $total_setoran = $pnbp->total_setoran ?? 0;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $i + 1 }}</td>
                                            <td>
                                                <ul class="mb-0 pl-3">
                                                    @foreach ($kode_pemesanans as $kode)
                                                        <li>{{ $kode }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>
                                                <ul class="mb-0 pl-3">
                                                    @foreach ($subtotals as $subtotal)
                                                        <li>Rp {{ number_format($subtotal, 0, ',', '.') }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>Rp {{ number_format($total_setoran, 0, ',', '.') }}</td>
                                            <td>
                                                {{ ucfirst($pnbp->status_setoran) }}
                                                @if ($pnbp->status_setoran === 'ditolak' && $pnbp->penolakan)
                                                    <br>
                                                    <small class="text-danger">Alasan:
                                                        {{ $pnbp->penolakan->alasan_penolakan }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $pnbp->no_ntb ?? '-' }}</td>
                                            <td class="text-center">
                                                @if (in_array('Admin', $admin_roles) && $pnbp->status_setoran === 'pengajuan')
                                                    <!-- tombol Setujui dan Tolak -->
                                                    <form action="{{ route('pnbp.setujui', $pnbp->id_pnbp) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button class="btn btn-success btn-sm">Setujui</button>
                                                    </form>
                                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#modalTolakPNBP{{ $pnbp->id_pnbp }}">
                                                        Tolak
                                                    </button>

                                                    <!-- Modal Tolak -->
                                                    <div class="modal fade" id="modalTolakPNBP{{ $pnbp->id_pnbp }}"
                                                        tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <form action="{{ route('pnbp.tolak', $pnbp->id_pnbp) }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-danger text-white">
                                                                        <h5 class="modal-title">Alasan Penolakan PNBP</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <textarea name="alasan_penolakan" class="form-control" required placeholder="Masukkan alasan penolakan..."></textarea>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-danger">Kirim
                                                                            Penolakan</button>
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Batal</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @elseif ($pnbp->status_setoran === 'disetujui' && in_array('Bendahara Penyewaan', $admin_roles))
                                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#modalSetorPNBP{{ $pnbp->id_pnbp }}">
                                                        Submit Bukti PNBP
                                                    </button>

                                                    <!-- Modal Setor -->
                                                    <div class="modal fade" id="modalSetorPNBP{{ $pnbp->id_pnbp }}"
                                                        tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <form action="{{ route('pnbp.isi_bukti', $pnbp->id_pnbp) }}"
                                                                method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-warning text-white">
                                                                        <h5 class="modal-title">Submit Bukti Setoran PNBP
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="total_setoran"
                                                                            value="{{ $total_setoran }}">

                                                                        <div class="row mb-2">
                                                                            <div class="col-md-6">
                                                                                <label>Tanggal Setoran</label>
                                                                                <input type="date"
                                                                                    name="tanggal_setoran"
                                                                                    class="form-control" required>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label>No NTB</label>
                                                                                <input type="text" name="no_ntb"
                                                                                    class="form-control" required
                                                                                    pattern="\d+"
                                                                                    title="Hanya angka yang diperbolehkan">
                                                                            </div>
                                                                        </div>

                                                                        <div class="mb-2">
                                                                            <label>Bukti PNBP</label>
                                                                            <input type="file" name="bukti_pnbp"
                                                                                class="form-control"
                                                                                accept=".pdf,.jpg,.jpeg,.png" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <button type="submit"
                                                                            class="btn btn-warning">Simpan</button>
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Batal</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @elseif ($pnbp->status_setoran === 'selesai')
                                                    @if (!empty($pnbp->bukti_pnbp))
                                                        <a href="{{ route('pnbp.lihat_bukti', basename($pnbp->bukti_pnbp)) }}"
                                                            target="_blank" class="btn btn-primary btn-sm">
                                                            Lihat File
                                                        </a>
                                                    @else
                                                        <span>-</span>
                                                    @endif
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data PNBP.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body content">
                            <div class="mb-3">
                                @if (request()->segment(2) == 'selesai' &&
                                        request()->query('pnbp') == 'pengajuan' &&
                                        in_array('Bendahara Penyewaan', $admin_roles))
                                    <button id="btn-setorkan" class="btn btn-success fw-bold" disabled
                                        data-bs-toggle="modal" data-bs-target="#modalSetor">
                                        Ajukan Setoran PNBP
                                    </button>
                                @endif
                            </div>

                            <div class="row">
                                <div class="table-responsive">
                                    <table id="example" class="table table-bordered data-table" style="width: 100%">
                                        <thead>
                                            <tr class="text-center small">
                                                <th class="th-sm">No</th>
                                                @if (request()->segment(2) == 'selesai' &&
                                                        request()->query('pnbp') == 'pengajuan' &&
                                                        in_array('Bendahara Penyewaan', $admin_roles))
                                                    <th><input type="checkbox" id="select-all"></th>
                                                @endif
                                                <th class="th-md">Kode Pemesanan</th>
                                                <th class="th-md">Nama Penyewa</th>
                                                <th class="th-md">No Telepon</th>
                                                <th class="th-md">Jadwal</th>
                                                <th class="th-md">Kamar</th>
                                                <th class="th-md">Harga</th>
                                                <th class="th-md">Metode Pembayaran</th>
                                                <th class="th-md">Bukti Pesan/Bayar</th>
                                                @if (in_array(request()->segment(2), ['diterima', 'selesai']))
                                                    <th class="th-md">Invoice</th>
                                                @endif
                                                @if (in_array(request()->segment(2), ['menunggu', 'verifikasi', 'diterima']))
                                                    <th class="th-sm">Aksi</th>
                                                @endif
                                                @if (request()->segment(2) == 'ditolak')
                                                    <th class="th-md">Alasan Penolakan/Pembatalan</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($pemesanan as $i => $p)
                                                @php
                                                    $checkin = Carbon::parse($p->tanggal_checkin);
                                                    $checkout = Carbon::parse($p->tanggal_checkout);
                                                    $malam = $checkin->diffInDays($checkout);
                                                @endphp
                                                <tr>
                                                    <td>{{ $i + 1 }}</td>
                                                    @if (request()->segment(2) == 'selesai' &&
                                                            in_array(request()->query('pnbp'), ['pengajuan']) &&
                                                            in_array('Bendahara Penyewaan', $admin_roles))
                                                        <td class="text-center"><input type="checkbox"
                                                                class="row-checkbox" value="{{ $p->kode_pemesanan }}"
                                                                data-subtotal="{{ $p->subtotal }}"></td>
                                                    @endif
                                                    <td>{{ $p->kode_pemesanan }}</td>
                                                    <td>{{ $p->penyewa->nama_lengkap }}</td>
                                                    <td>{{ $p->penyewa->no_telepon }}</td>
                                                    <td>{{ $checkin->format('d-m-Y') }} - {{ $checkout->format('d-m-Y') }}
                                                        ({{ $malam }} malam)
                                                    </td>
                                                    <td>
                                                        <ul>
                                                            @foreach ($p->detailKamar as $dk)
                                                                <li>{{ $dk->kamar->kategori->nama_kategori }} - Kamar No:
                                                                    {{ $dk->kamar->nomor_kamar }} Lantai {{ $dk->lantai }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                    <td>Rp {{ number_format($p->subtotal, 0, ',', '.') }}</td>
                                                    <td>{{ optional($p->pembayaran)->metode_pembayaran ?? '-' }}</td>
                                                    <td>
                                                        @if ($p->pembayaran && $p->pembayaran->metode_pembayaran === 'cash' && $p->pembayaran->url_path)
                                                            <a href="{{ route('bukti.download', $p->kode_pemesanan) }}"
                                                                target="_blank" class="btn btn-sm btn-success">
                                                                <i class="fa fa-file-pdf"></i> Bukti Pemesanan
                                                            </a>
                                                        @elseif ($p->pembayaran && !empty($p->pembayaran->url_path) && $p->pembayaran->url_path !== '-')
                                                            <a href="{{ url('/penyewaan_aset-getBuktiPembayaran/' . basename($p->pembayaran->url_path)) }}"
                                                                target="_blank" class="btn btn-sm btn-primary">
                                                                <i class="fa fa-file-pdf"></i> Lihat Bukti Transfer
                                                            </a>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    @if (in_array(request()->segment(2), ['diterima', 'selesai']))
                                                        <td>
                                                            @if ($p->pembayaran && $p->pembayaran->invoice)
                                                                <a href="{{ url('/penyewaan_aset-getInvoice/' . basename($p->pembayaran->invoice->url_invoice)) }}"
                                                                    target="_blank" class="btn btn-info btn-sm">
                                                                    <i class="fa fa-file-invoice"></i> Lihat Invoice
                                                                </a>
                                                            @else
                                                                <span class="text-muted">Belum tersedia</span>
                                                            @endif
                                                        </td>
                                                    @endif

                                                    @if (
                                                        (in_array(request()->segment(2), ['menunggu']) && in_array('Bendahara Penyewaan', $admin_roles)) ||
                                                            (in_array(request()->segment(2), ['verifikasi']) && in_array('Petugas Mess', $admin_roles)))
                                                        @php
                                                            $segment = request()->segment(2);
                                                            $routeName =
                                                                $segment == 'verifikasi'
                                                                    ? 'penyewaan.setujui_petugas'
                                                                    : 'penyewaan.setujui_bendahara';
                                                        @endphp
                                                        <td>
                                                            <form action="{{ route($routeName, $p->kode_pemesanan) }}"
                                                                method="POST" class="form-approve d-inline">
                                                                @csrf
                                                                <button type="button"
                                                                    class="btn btn-success btn-sm btn-approve">Setujui</button>
                                                            </form>

                                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                                data-bs-target="#modalTolak{{ $p->kode_pemesanan }}">
                                                                Tolak
                                                            </button>

                                                            <div class="modal fade"
                                                                id="modalTolak{{ $p->kode_pemesanan }}" tabindex="-1"
                                                                aria-labelledby="modalTolakLabel{{ $p->kode_pemesanan }}"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <form
                                                                        action="{{ route('penyewaan.tolak', $p->kode_pemesanan) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-danger text-white">
                                                                                <h5 class="modal-title"
                                                                                    id="modalTolakLabel{{ $p->kode_pemesanan }}">
                                                                                    Alasan Penolakan</h5>
                                                                                <button type="button" class="btn-close"
                                                                                    data-bs-dismiss="modal"
                                                                                    aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <textarea name="alasan_penolakan" class="form-control" rows="3" required
                                                                                    placeholder="Masukkan alasan penolakan..."></textarea>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="submit"
                                                                                    class="btn btn-danger">Kirim
                                                                                    Penolakan</button>
                                                                                <button type="button"
                                                                                    class="btn btn-secondary"
                                                                                    data-bs-dismiss="modal">Batal</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    @endif
                                                    @if (in_array(request()->segment(2), ['diterima']) && in_array('Petugas Mess', $admin_roles))
                                                        <td class="text-center">
                                                            <div class="d-inline-flex gap-1">
                                                                <button class="btn btn-danger btn-sm"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modalBatalkan{{ $p->kode_pemesanan }}">
                                                                    Batalkan
                                                                </button>
                                                                <button class="btn btn-primary btn-sm"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modalEdit{{ $p->kode_pemesanan }}">
                                                                    Edit
                                                                </button>
                                                            </div>
                                                            <!-- Modal Edit / Upgrade Kamar -->
                                                            <div class="modal fade" id="modalEdit{{ $p->kode_pemesanan }}" tabindex="-1"
                                                                aria-labelledby="modalEditLabel{{ $p->kode_pemesanan }}" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg">
                                                                    <form action="{{ route('penyewaan.edit.upgrade', $p->kode_pemesanan) }}" method="POST">
                                                                        @csrf
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-primary text-white">
                                                                                <h5 class="modal-title">Edit / Upgrade Kamar</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                                    aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                @foreach ($p->detailKamar as $dk)
                                                                                    <div class="mb-3">
                                                                                        <label class="form-label">
                                                                                            Kamar Lama: {{ $dk->kamar->nomor_kamar }} ({{ $dk->kamar->kategori->nama_kategori }})
                                                                                        </label>
                                                                                        <select name="upgrade[{{ $dk->id_detail_pemesanan_kamar }}]"
                                                                                                class="form-select upgrade-select"
                                                                                                data-detail-id="{{ $dk->id_detail_pemesanan_kamar }}"
                                                                                                data-checkin="{{ $p->tanggal_checkin }}"
                                                                                                data-checkout="{{ $p->tanggal_checkout }}">
                                                                                            <option value="">-- Memuat kamar tersedia... --</option>
                                                                                        </select>
                                                                                    </div>
                                                                                @endforeach
                                                                                <small class="text-muted">Pilih kamar baru hanya jika ingin mengganti/upgrade.</small>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>

                                                            <div class="modal fade"
                                                                id="modalBatalkan{{ $p->kode_pemesanan }}" tabindex="-1"
                                                                aria-labelledby="modalBatalkanLabel{{ $p->kode_pemesanan }}"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <form
                                                                        action="{{ route('penyewaan.batalkan.refund', $p->kode_pemesanan) }}"
                                                                        method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-warning text-dark">
                                                                                <h5 class="modal-title"
                                                                                    id="modalBatalkanLabel{{ $p->kode_pemesanan }}">
                                                                                    Form Pembatalan Sewa</h5>
                                                                                <button type="button" class="btn-close"
                                                                                    data-bs-dismiss="modal"
                                                                                    aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="id_pemesanan"
                                                                                    value="{{ $p->kode_pemesanan }}">

                                                                                <div class="mb-3">
                                                                                    <label
                                                                                        for="alasan_pembatalan{{ $p->kode_pemesanan }}"
                                                                                        class="form-label">Alasan
                                                                                        Pembatalan</label>
                                                                                    <input type="text"
                                                                                        name="alasan_pembatalan"
                                                                                        id="alasan_pembatalan{{ $p->kode_pemesanan }}"
                                                                                        class="form-control" required>
                                                                                </div>


                                                                                <div class="mb-3">
                                                                                    <label
                                                                                        class="form-label d-block">Metode
                                                                                        Refund</label>
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="metode_refund"
                                                                                            id="refundTransfer{{ $p->kode_pemesanan }}"
                                                                                            value="Transfer" required>
                                                                                        <label class="form-check-label"
                                                                                            for="refundTransfer{{ $p->kode_pemesanan }}">Transfer</label>
                                                                                    </div>
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="metode_refund"
                                                                                            id="refundCash{{ $p->kode_pemesanan }}"
                                                                                            value="Cash" required>
                                                                                        <label class="form-check-label"
                                                                                            for="refundCash{{ $p->kode_pemesanan }}">Cash</label>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                    <label
                                                                                        for="bukti_refund{{ $p->kode_pemesanan }}"
                                                                                        class="form-label">Bukti Refund
                                                                                        (file)</label>
                                                                                    <input type="file"
                                                                                        name="bukti_refund"
                                                                                        id="bukti_refund{{ $p->kode_pemesanan }}"
                                                                                        class="form-control"
                                                                                        accept="image/*,application/pdf"
                                                                                        required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="submit"
                                                                                    class="btn btn-warning">Kirim
                                                                                    Pembatalan</button>
                                                                                <button type="button"
                                                                                    class="btn btn-secondary"
                                                                                    data-bs-dismiss="modal">Batal</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    @endif



                                                    @if (request()->segment(2) == 'ditolak')
                                                        <td>
                                                            @if ($p->status === 'ditolak')
                                                                {{ $p->penolakan->alasan_penolakan ?? '-' }}
                                                            @elseif ($p->status === 'dibatalkan refund' && $p->pembatalanSewa)
                                                                <div class="d-flex flex-column">
                                                                    {{ $p->pembatalanSewa->alasan_pembatalan }}
                                                                    <a href="{{ route('refund.lihat', $p->kode_pemesanan) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-primary">
                                                                        <i class="fa fa-eye"></i> Lihat Bukti Refund
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    @endif


                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="11" class="text-center">Tidak ada data.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
            </div>
            @endif
        </div>
    </div>
    <!-- Modal Rekapitulasi -->
    <div class="modal fade" id="rekapModal" tabindex="-1" aria-labelledby="rekapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="rekapModalLabel">Pilih Jenis Rekapitulasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="d-grid gap-2">

                        <a href="{{ route('penyewaan_aset.rekapitulasi') }}" class="btn btn-outline-primary">
                            <i class="fas fa-file-invoice-dollar me-2"></i> Rekap Penyewaan
                        </a>

                        <a href="{{ route('kamar.rekapitulasi') }}" class="btn btn-outline-success">
                            <i class="fas fa-bed me-2"></i> Data Kamar
                        </a>

                        <a href="{{ route('histori_kamar.rekapitulasi') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-history me-2"></i> Histori Kamar
                        </a>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Ajukan Sewa Offline -->
    <div class="modal fade" id="ajukanSewaModal" tabindex="-1" aria-labelledby="ajukanSewaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('admin.ajukan.sewa') }}" onsubmit="syncRoomJsonToInput()">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Ajukan Sewa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <!-- Baris 1: Nama & NIK -->
                        <div class="col-md-6">
                            <label class="form-label">Nama Penyewa</label>
                            <input type="text" name="nama_lengkap" class="form-control" required>
                        </div>
                        <!-- NIK -->
                        <div class="col-md-6">
                            <label class="form-label">NIK</label>
                            <input type="text" name="nik" class="form-control" pattern="\d{16}" maxlength="16"
                                inputmode="numeric" title="NIK harus terdiri dari 16 digit angka" required>
                        </div>

                        <!-- No Telepon -->
                        <div class="col-md-6">
                            <label class="form-label">No Telepon</label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="tel" name="no_telepon" class="form-control" pattern="[0-9]{1,12}"
                                    inputmode="numeric" title="Nomor telepon maksimal 12 digit setelah +62"
                                    maxlength="12" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Menginap</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="date" name="start" id="startAdmin" class="form-control" required>
                                <span class="mx-1">sampai</span>
                                <input type="date" name="end" id="endAdmin" class="form-control" required
                                    disabled>
                            </div>
                        </div>

                        <!-- Pilih Kamar -->
                        <div class="col-12 mt-3">
                            <label class="form-label">Pilih Kamar Tersedia</label>
                            <div id="availableRoomsAdmin" class="row gy-2"></div>
                        </div>

                        <!-- Hidden input -->
                        <input type="hidden" name="rooms_json" id="roomsJsonInputAdmin">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary fw-bold">Ajukan Sewa</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <!-- Modal Kelola Kamar -->
    <div class="modal fade" id="kelolaKamarModal" tabindex="-1" aria-labelledby="kelolaKamarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="kelolaKamarModalLabel">Kelola Kamar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body text-center py-4">
                    <!-- Tombol trigger modal form tambah -->
                    <button class="btn btn-success w-75 mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahKamar">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Kamar
                    </button>
                    <button class="btn btn-warning w-75 text-white" data-bs-toggle="modal"
                        data-bs-target="#modalPilihKategoriEdit">
                        <i class="bi bi-pencil-square me-1"></i> Update Kamar
                    </button>
                    <button class="btn btn-secondary w-75 mt-3" data-bs-toggle="modal"
                        data-bs-target="#modalKelolaKategori">
                        <i class="bi bi-folder2-open me-1"></i> Update Kategori
                    </button>
                    <button class="btn btn-info w-75 mt-3 text-white" data-bs-toggle="modal"
                        data-bs-target="#modalKelolaFasilitas">
                        <i class="bi bi-tools me-1"></i> Update Fasilitas
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kamar -->
    <div class="modal fade" id="modalTambahKamar" tabindex="-1" aria-labelledby="modalTambahKamarLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="formTambahKamar" action="{{ route('kamar.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahKamarLabel">Tambah Kamar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Nomor Kamar -->
                        <div class="mb-3">
                            <label for="nomor_kamar" class="form-label">Nomor Kamar</label>
                            <input type="text" class="form-control" id="nomor_kamar" name="nomor_kamar" required>
                        </div>

                        <div class="mb-3">
                            <label for="lantai" class="form-label">Lantai Kamar</label>
                            <input type="text" class="form-control" id="lantai" name="lantai" required>
                        </div>

                        <!-- Kategori Kamar -->
                        <div class="mb-3">
                            <label for="id_kategori_kamar"
                                class="form-label d-flex justify-content-between align-items-center">
                                <span>Kategori Kamar</span>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalTambahKategori"
                                    class="text-success" title="Tambah Kategori">
                                    <i class="bi bi-plus-circle-fill"></i>
                                </a>
                            </label>
                            <select class="form-select" id="id_kategori_kamar" name="id_kategori_kamar" required>
                                <option value="" disabled selected>Pilih Kategori</option>
                                @foreach ($kategoriKamar as $kategori)
                                    <option value="{{ $kategori->id_kategori_kamar }}">{{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Harga per Malam -->
                        <div class="mb-3">
                            <label for="harga_per_malam" class="form-label">Harga per Malam (Rp)</label>
                            <input type="number" class="form-control" id="harga_per_malam" name="harga_per_malam"
                                min="0" required>
                        </div>

                        <!-- Status Kamar -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Kamar</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="" disabled selected>Pilih Status</option>
                                <option value="available">Available</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>

                        <!-- Fasilitas -->
                        <div class="mb-3">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                <span>Fasilitas</span>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalTambahFasilitas"
                                    class="text-success" title="Tambah Fasilitas">
                                    <i class="bi bi-plus-circle-fill"></i>
                                </a>
                            </label>
                            <div class="row fasilitas-container">
                                @foreach ($fasilitas as $f)
                                    <div class="col-12 mb-2">
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <input class="form-check-input mt-0" type="checkbox"
                                                    name="fasilitas[{{ $f->id_fasilitas_sewa }}][aktif]" value="1"
                                                    aria-label="Checkbox {{ $f->id_fasilitas_sewa }}">
                                            </div>
                                            <span class="input-group-text">{{ $f->nama_fasilitas }}</span>
                                            <input type="number" class="form-control"
                                                name="fasilitas[{{ $f->id_fasilitas_sewa }}][jumlah]"
                                                placeholder="Jumlah" min="1">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Kategori -->
    <div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-labelledby="modalTambahKategoriLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <form action="{{ route('kategori.store') }}" method="POST" id="formTambahKategori">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahKategoriLabel">Tambah Kategori Kamar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Tambah Fasilitas -->
    <div class="modal fade" id="modalTambahFasilitas" tabindex="-1" aria-labelledby="modalTambahFasilitasLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <form id="formTambahFasilitas">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahFasilitasLabel">Tambah Fasilitas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_fasilitas" class="form-label">Nama Fasilitas</label>
                            <input type="text" class="form-control" id="nama_fasilitas" name="nama_fasilitas"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalKelolaFasilitas" tabindex="-1" aria-labelledby="modalKelolaFasilitasLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header ">
                    <h5 class="modal-title">Kelola Fasilitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <!-- Tambah Fasilitas -->
                    <form id="formTambahFasilitasFull" class="d-flex mb-3">
                        @csrf
                        <input type="text" class="form-control me-2" name="nama_fasilitas"
                            placeholder="Nama fasilitas baru..." required>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-plus-circle"></i>
                        </button>
                    </form>

                    <!-- List Fasilitas -->
                    <ul class="list-group" id="listFasilitasWrapper">
                        @foreach ($fasilitas as $f)
                            <li class="d-flex justify-content-between align-items-center">
                                <span class="fasilitas-nama"
                                    data-id="{{ $f->id_fasilitas_sewa }}">{{ $f->nama_fasilitas }}</span>
                                <div>
                                    <button
                                        class="btn btn-sm btn-outline-primary btn-edit-fasilitas me-1 text-success"
                                        data-id="{{ $f->id_fasilitas_sewa }}" data-nama="{{ $f->nama_fasilitas }}">
                                        <i class="bi bi-pencil "></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger btn-hapus-fasilitas"
                                        data-id="{{ $f->id_fasilitas_sewa }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Pilih Kategori (Untuk Update Kamar) -->
    <div class="modal fade" id="modalPilihKategoriEdit" tabindex="-1" aria-labelledby="modalPilihKategoriEditLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalPilihKategoriEditLabel">Pilih Kategori Kamar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="d-grid gap-2">
                        @foreach ($kategoriKamar as $kategori)
                            <button class="btn btn-outline-primary pilih-kategori-btn"
                                data-id="{{ $kategori->id_kategori_kamar }}" data-nama="{{ $kategori->nama_kategori }}"
                                data-bs-dismiss="modal">
                                {{ $kategori->nama_kategori }}
                            </button>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal List Kamar Berdasarkan Kategori -->
    <div class="modal fade" id="modalListKamarKategori" tabindex="-1" aria-labelledby="modalListKamarKategoriLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center" id="modalListKamarKategoriLabel">
                        <button type="button" class="btn btn-sm btn-outline-secondary me-2" id="btnKembaliKategori">
                            <i class="bi bi-arrow-left"></i>
                        </button>
                        List Kamar
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div id="listKamarWrapper">
                        <p class="text-muted">Memuat data kamar...</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Kelola Kategori -->
    <div class="modal fade" id="modalKelolaKategori" tabindex="-1" aria-labelledby="modalKelolaKategoriLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kelola Kategori Kamar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <!-- Tambah Kategori -->
                    <form id="formTambahKategoriFull" class="d-flex mb-3">
                        @csrf
                        <input type="text" class="form-control me-2" name="nama_kategori"
                            placeholder="Nama kategori baru..." required>
                        <button type="submit" class="btn btn-success"><i class="bi bi-plus-circle"></i></button>
                    </form>

                    <!-- List Kategori -->
                    <ul class="list-group" id="listKategoriWrapper">
                        @foreach ($kategoriKamar as $kategori)
                            <li class="d-flex justify-content-between align-items-center">
                                <span class="kategori-nama"
                                    data-id="{{ $kategori->id_kategori_kamar }}">{{ $kategori->nama_kategori }}</span>
                                <div>
                                    <button class="btn btn-sm border-white btn-outline-primary btn-edit-kategori me-1"
                                        data-id="{{ $kategori->id_kategori_kamar }}"
                                        data-nama="{{ $kategori->nama_kategori }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm border-white btn-outline-danger btn-hapus-kategori"
                                        data-id="{{ $kategori->id_kategori_kamar }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalSetor" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('pnbp.pengajuan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Setor PNBP</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Pemesanan</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody id="setor-rows"></tbody>
                        </table>
                        <div class="mb-3"><strong>Jumlah Setor:</strong> Rp <span id="total-setor">0</span></div>
                        <input type="hidden" name="pemesanans_ids" id="pemesanans-ids">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan Setoran</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ============================ SETORKAN ============================
            const btnSetorkan = document.getElementById('btn-setorkan');
            const checkAll = document.getElementById('select-all');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');

            function updateButtonState() {
                const anyChecked = Array.from(rowCheckboxes).some(r => r.checked);
                btnSetorkan.disabled = !anyChecked;
            }

            checkAll?.addEventListener('change', e => {
                rowCheckboxes.forEach(r => r.checked = e.target.checked);
                updateButtonState();
            });

            rowCheckboxes.forEach(r => r.addEventListener('change', updateButtonState));

            btnSetorkan?.addEventListener('click', () => {
                const selected = document.querySelectorAll('.row-checkbox:checked');
                const tbody = document.getElementById('setor-rows');
                const idsInput = document.getElementById('pemesanans-ids');
                let total = 0;
                const ids = [];
                tbody.innerHTML = '';

                selected.forEach((r, idx) => {
                    const id = r.value;
                    const kode = document.querySelector(`tr input[value="${id}"]`).closest('tr')
                        .children[2].innerText;
                    const harga = parseInt(r.dataset.subtotal);
                    total += harga;
                    ids.push(id);
                    tbody.innerHTML +=
                        `<tr><td>${idx + 1}</td><td>${kode}</td><td>Rp ${harga.toLocaleString('id-ID')}</td></tr>`;
                });

                document.getElementById('total-setor').innerText = total.toLocaleString('id-ID');
                idsInput.value = ids.join(',');
            });

            // ============================ CHECK KAMAR TERSEDIA ============================
            const startInputAdmin = document.getElementById('startAdmin');
            const endInputAdmin = document.getElementById('endAdmin');
            const roomsJsonInputAdmin = document.getElementById('roomsJsonInputAdmin');
            const roomContainerAdmin = document.getElementById('availableRoomsAdmin');
            let selectedRoomsAdmin = [];

            function syncRoomJsonToInput() {
                roomsJsonInputAdmin.value = JSON.stringify(selectedRoomsAdmin);
            }

            function fetchAvailableRoomsAdmin() {
                const start = startInputAdmin.value;
                const end = endInputAdmin.value;
                if (!start || !end) return;

                fetch(`/sewa/cek-kamar-tersedia?start=${start}&end=${end}`)
                    .then(res => res.json())
                    .then(data => {
                        roomContainerAdmin.innerHTML = '';
                        selectedRoomsAdmin = [];

                        Object.entries(data).forEach(([kategoriId, hargaGroup]) => {
                            Object.entries(hargaGroup).forEach(([harga, info]) => {
                                if (info.count > 0) {
                                    const selectId = `room-${kategoriId}-${harga}`;
                                    const fasilitasHtml = (info.fasilitas ?? []).length ?
                                        `<div class="d-flex flex-wrap gap-2">${info.fasilitas.map(f =>
                                    `<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>${f.jumlah} ${f.nama}</span>`).join('')}</div>` :
                                        '<div><i class="bi bi-dash-circle me-1"></i>Tidak ada fasilitas</div>';

                                    const label =
                                        `Kategori ${info.nama_kategori} - Rp ${parseInt(harga).toLocaleString()}`;
                                    let options =
                                        '<option value="0">Pilih jumlah kamar</option>';
                                    for (let i = 1; i <= info.count; i++) {
                                        options += `<option value="${i}">${i}</option>`;
                                    }

                                    roomContainerAdmin.innerHTML += `
                                <div class="col-md-6">
                                    <div class="border p-3 rounded">
                                        <label class="form-label fw-bold">${label}</label>
                                        <small class="text-muted d-block mb-2">${fasilitasHtml}</small>
                                        <select class="form-select kamar-qty-admin"
                                            data-kategori-id="${kategoriId}"
                                            data-harga="${harga}"
                                            data-nama-kategori="${info.nama_kategori}"
                                            data-ids='${JSON.stringify(info.ids)}'
                                            id="${selectId}">
                                            ${options}
                                        </select>
                                        <small class="text-muted">/ ${info.count} tersedia</small>
                                    </div>
                                </div>
                            `;
                                }
                            });
                        });

                        document.querySelectorAll('.kamar-qty-admin').forEach(select => {
                            select.addEventListener('change', function() {
                                const categoryId = this.dataset.kategoriId;
                                const namaKategori = this.dataset.namaKategori;
                                const price = parseInt(this.dataset.harga);
                                const ids = JSON.parse(this.dataset.ids);
                                const qty = parseInt(this.value) || 0;
                                const key = `${categoryId}-${price}`;
                                const idx = selectedRoomsAdmin.findIndex(r => r.key === key);

                                if (qty > 0) {
                                    const roomObj = {
                                        key,
                                        categoryId,
                                        name: namaKategori,
                                        price,
                                        quantity: qty,
                                        ids: ids.slice(0, qty)
                                    };
                                    if (idx !== -1) selectedRoomsAdmin[idx] = roomObj;
                                    else selectedRoomsAdmin.push(roomObj);
                                } else if (idx !== -1) {
                                    selectedRoomsAdmin.splice(idx, 1);
                                }

                                syncRoomJsonToInput();
                            });
                        });
                    });
            }

            // Tanggal hari ini
            const today = new Date();
            const todayStr = today.toISOString().split('T')[0];
            startInputAdmin.min = todayStr;

            // Input tanggal
            startInputAdmin.addEventListener('change', () => {
                if (!startInputAdmin.value) {
                    endInputAdmin.disabled = true;
                    endInputAdmin.value = '';
                    return;
                }

                const startDate = new Date(startInputAdmin.value);
                const minEndDate = new Date(startDate);
                minEndDate.setDate(minEndDate.getDate() + 1);
                const maxEndDate = new Date(startDate);
                maxEndDate.setDate(startDate.getDate() + 5);

                endInputAdmin.disabled = false;
                endInputAdmin.min = minEndDate.toISOString().split('T')[0];
                endInputAdmin.max = maxEndDate.toISOString().split('T')[0];
                endInputAdmin.value = minEndDate.toISOString().split('T')[0];

                fetchAvailableRoomsAdmin();
            });

            endInputAdmin.addEventListener('change', () => {
                const startDate = new Date(startInputAdmin.value);
                const endDate = new Date(endInputAdmin.value);
                const diff = Math.floor((endDate - startDate) / (1000 * 60 * 60 * 24));

                if (diff > 5) {
                    alert("Maksimal durasi menginap adalah 5 malam.");
                    const maxDate = new Date(startDate);
                    maxDate.setDate(startDate.getDate() + 5);
                    endInputAdmin.value = maxDate.toISOString().split('T')[0];
                }

                fetchAvailableRoomsAdmin();
            });

            // ============================ APPROVAL ============================
            document.querySelectorAll('.btn-approve').forEach(btn => {
                btn.addEventListener('click', () => {
                    const form = btn.closest('form');
                    Swal.fire({
                        title: 'Apakah anda yakin ingin menyetujui pesanan ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, setujui!'
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

            // ============================ TAMBAH KATEGORI ============================
            const formKategori = document.getElementById('formTambahKategori');
            if (formKategori) {
                formKategori.addEventListener('submit', e => {
                    e.preventDefault();
                    const nama = document.getElementById('nama_kategori').value;
                    const token = document.querySelector('input[name="_token"]').value;

                    fetch("{{ route('kategori.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify({
                                nama_kategori: nama
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            const select = document.getElementById('id_kategori_kamar');
                            const option = new Option(data.nama_kategori, data.id_kategori_kamar, true,
                                true);
                            select.appendChild(option);
                            bootstrap.Modal.getInstance(document.getElementById('modalTambahKategori'))
                                .hide();
                            formKategori.reset();
                        })
                        .catch(() => alert('Terjadi kesalahan saat menyimpan kategori.'));
                });
            }

            // ============================ TAMBAH FASILITAS ============================
            const formFasilitas = document.getElementById('formTambahFasilitas');
            if (formFasilitas) {
                formFasilitas.addEventListener('submit', e => {
                    e.preventDefault();
                    const nama = document.getElementById('nama_fasilitas').value;
                    const token = document.querySelector('input[name="_token"]').value;

                    fetch("{{ route('fasilitas.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify({
                                nama_fasilitas: nama
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            const container = document.querySelector('.fasilitas-container');
                            container.insertAdjacentHTML('beforeend', `
                    <div class="col-12 mb-2">
                        <div class="input-group">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" name="fasilitas[${data.id_fasilitas_sewa}][aktif]" value="1" checked>
                            </div>
                            <span class="input-group-text">${nama}</span>
                            <input type="number" class="form-control" name="fasilitas[${data.id_fasilitas_sewa}][jumlah]" placeholder="Jumlah" min="1" value="1">
                        </div>
                    </div>
                `);
                            bootstrap.Modal.getInstance(document.getElementById('modalTambahFasilitas'))
                                .hide();
                            formFasilitas.reset();
                        })
                        .catch(() => alert('Gagal menyimpan fasilitas.'));
                });
            }

            // Update Kamar
            // Tombol kembali ke modal kategori
            const btnKembali = document.getElementById('btnKembaliKategori');
            if (btnKembali) {
                btnKembali.addEventListener('click', function() {
                    const modalList = bootstrap.Modal.getInstance(document.getElementById(
                        'modalListKamarKategori'));
                    modalList.hide();

                    // Tampilkan kembali modal kategori
                    setTimeout(() => {
                        new bootstrap.Modal(document.getElementById('modalPilihKategoriEdit'))
                            .show();
                    }, 300);
                });
            }
            // Saat klik kategori
            document.querySelectorAll('.pilih-kategori-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const kategoriId = this.dataset.id;
                    const kategoriNama = this.dataset.nama;

                    fetch(`/kamar/by-kategori/${kategoriId}`)
                        .then(res => res.json())
                        .then(kamarList => {
                            let html =
                                `<h6 class="mb-3">Kamar untuk kategori <strong>${kategoriNama}</strong>:</h6>`;

                            if (kamarList.length === 0) {
                                html +=
                                    `<p class="text-muted">Tidak ada kamar dalam kategori ini.</p>`;
                            } else {
                                html += `<div class="list-group">`;
                                kamarList.forEach(kamar => {
                                    html += `
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>No. ${kamar.nomor_kamar}</strong> - Lantai ${kamar.lantai} - Rp ${parseInt(kamar.harga_per_malam).toLocaleString()}
                                </div>
                                <button class="btn btn-sm btn-primary btn-edit-kamar" data-id="${kamar.id_kamar}">
                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                </button>
                            </div>`;
                                });
                                html += `</div>`;
                            }

                            document.getElementById('listKamarWrapper').innerHTML = html;

                            // ⬅️ Tambah di sini
                            document.querySelectorAll('.btn-edit-kamar').forEach(btn => {
                                btn.addEventListener('click', function() {
                                    const kamarId = this.dataset.id;

                                    fetch(`/kamar/detail/${kamarId}`)
                                        .then(res => res.json())
                                        .then(data => {
                                            const kamar = data.kamar;
                                            const fasilitas = data
                                                .fasilitas;

                                            document.getElementById(
                                                    'nomor_kamar').value =
                                                kamar.nomor_kamar;
                                            document.getElementById(
                                                    'lantai').value = kamar
                                                .lantai;
                                            document.getElementById(
                                                    'harga_per_malam')
                                                .value = kamar
                                                .harga_per_malam;
                                            document.getElementById(
                                                    'status').value = kamar
                                                .status_kamar;
                                            document.getElementById(
                                                    'id_kategori_kamar')
                                                .value = kamar
                                                .id_kategori_kamar;

                                            // Reset semua fasilitas dulu
                                            document.querySelectorAll(
                                                'input[type=checkbox][name^="fasilitas"]'
                                            ).forEach(cb => cb
                                                .checked = false);
                                            document.querySelectorAll(
                                                'input[type=number][name^="fasilitas"]'
                                            ).forEach(input => input
                                                .value = '');

                                            // Tandai fasilitas aktif
                                            for (let id in fasilitas) {
                                                const checkbox = document
                                                    .querySelector(
                                                        `input[name="fasilitas[${id}][aktif]"]`
                                                    );
                                                const jumlahInput = document
                                                    .querySelector(
                                                        `input[name="fasilitas[${id }][jumlah]"]`
                                                    );

                                                if (checkbox &&
                                                    jumlahInput) {
                                                    checkbox.checked = true;
                                                    jumlahInput.value =
                                                        fasilitas[id];
                                                }
                                            }

                                            // Ubah form menjadi edit mode
                                            const form = document
                                                .getElementById(
                                                    'formTambahKamar');
                                            form.action =
                                                `/kamar/update/${kamar.id_kamar}`;

                                            // Ganti judul
                                            document.getElementById(
                                                    'modalTambahKamarLabel')
                                                .textContent = 'Edit Kamar';

                                            // Tutup modal list kamar
                                            const modalList = bootstrap
                                                .Modal.getInstance(document
                                                    .getElementById(
                                                        'modalListKamarKategori'
                                                    ));
                                            if (modalList) modalList.hide();

                                            // Tampilkan modal form kamar
                                            setTimeout(() => {
                                                new bootstrap.Modal(
                                                        document
                                                        .getElementById(
                                                            'modalTambahKamar'
                                                        ))
                                                    .show();
                                            }, 300);
                                        });
                                });
                            });

                            new bootstrap.Modal(document.getElementById(
                                'modalListKamarKategori')).show();
                        });

                });
            });
            // ============================ UPDATE KATEGORI (FULL LIST) ============================
            const formTambahKategoriFull = document.getElementById('formTambahKategoriFull');
            const listWrapper = document.getElementById('listKategoriWrapper');

            formTambahKategoriFull?.addEventListener('submit', e => {
                e.preventDefault();
                const formData = new FormData(formTambahKategoriFull);
                const token = formTambahKategoriFull.querySelector('[name="_token"]').value;

                fetch("{{ route('kategori.store') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        const li = document.createElement('li');
                        li.className =
                            "d-flex justify-content-between align-items-center";
                        li.innerHTML = `
                <span class="kategori-nama" data-id="${data.id_kategori_kamar }">${data.nama_kategori}</span>
                <div>
                    <button class="btn btn-sm btn-outline-primary btn-edit-kategori me-1" data-id="${data.id_kategori_kamar }" data-nama="${data.nama_kategori}">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger btn-hapus-kategori" data-id="${data.id_kategori_kamar }">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
                        listWrapper.appendChild(li);
                        formTambahKategoriFull.reset();
                    })
                    .catch(() => alert('Gagal menambah kategori.'));
            });

            listWrapper?.addEventListener('click', e => {
                if (e.target.closest('.btn-edit-kategori')) {
                    const btn = e.target.closest('.btn-edit-kategori');
                    const id = btn.dataset.id;
                    const oldNama = btn.dataset.nama;
                    const newNama = prompt("Edit nama kategori:", oldNama);

                    if (newNama?.trim()) {
                        fetch(`/kategori/update/${id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .content
                                },
                                body: JSON.stringify({
                                    nama_kategori: newNama
                                })
                            })
                            .then(res => res.json())
                            .then(() => {
                                btn.closest('li').querySelector('.kategori-nama').textContent = newNama;
                                btn.dataset.nama = newNama;
                            })
                            .catch(() => alert('Gagal update kategori.'));
                    }
                }

                if (e.target.closest('.btn-hapus-kategori')) {
                    const btn = e.target.closest('.btn-hapus-kategori');
                    const id = btn.dataset.id;
                    if (confirm("Yakin ingin menghapus kategori ini?")) {
                        fetch(`/kategori/delete/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .content
                                }
                            })
                            .then(() => btn.closest('li').remove())
                            .catch(() => alert('Gagal hapus kategori.'));
                    }
                }
            });
            const formTambahFasilitasFull = document.getElementById('formTambahFasilitasFull');
            const listFasilitasWrapper = document.getElementById('listFasilitasWrapper');

            // ============ Tambah Fasilitas ============
            formTambahFasilitasFull?.addEventListener('submit', e => {
                e.preventDefault();
                const formData = new FormData(formTambahFasilitasFull);
                const token = formTambahFasilitasFull.querySelector('[name="_token"]').value;

                fetch("{{ route('fasilitas.store') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        const li = document.createElement('li');
                        li.className =
                            "d-flex justify-content-between align-items-center";
                        li.innerHTML = `
                <span class="fasilitas-nama" data-id="${data.id_fasilitas_sewa}">${data.nama_fasilitas}</span>
                <div>
                    <button class="btn btn-sm btn-outline-primary btn-edit-fasilitas me-1 text-success"
                        data-id="${data.id_fasilitas_sewa}" data-nama="${data.nama_fasilitas}">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger btn-hapus-fasilitas"
                        data-id="${data.id_fasilitas_sewa}">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
                        listFasilitasWrapper.appendChild(li);
                        formTambahFasilitasFull.reset();
                    })
                    .catch(() => alert('Gagal menambah fasilitas.'));
            });

            // ============ Edit / Hapus Fasilitas ============
            listFasilitasWrapper?.addEventListener('click', e => {
                // Edit
                if (e.target.closest('.btn-edit-fasilitas')) {
                    const btn = e.target.closest('.btn-edit-fasilitas');
                    const id = btn.dataset.id;
                    const oldNama = btn.dataset.nama;
                    const newNama = prompt("Edit nama fasilitas:", oldNama);

                    if (newNama?.trim()) {
                        fetch(`/fasilitas/update/${id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .content
                                },
                                body: JSON.stringify({
                                    nama_fasilitas: newNama
                                })
                            })
                            .then(res => res.json())
                            .then(() => {
                                btn.closest('li').querySelector('.fasilitas-nama').textContent =
                                    newNama;
                                btn.dataset.nama = newNama;
                            })
                            .catch(() => alert('Gagal update fasilitas.'));
                    }
                }

                // Hapus
                if (e.target.closest('.btn-hapus-fasilitas')) {
                    const btn = e.target.closest('.btn-hapus-fasilitas');
                    const id = btn.dataset.id;
                    if (confirm("Yakin ingin menghapus fasilitas ini?")) {
                        fetch(`/fasilitas/delete/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .content
                                }
                            })
                            .then(() => btn.closest('li').remove())
                            .catch(() => alert('Gagal hapus fasilitas.'));
                    }
                }
            });
        });
    </script>
    <script>
document.addEventListener('DOMContentLoaded', () => {
    const modals = document.querySelectorAll('[id^="modalEdit"]');

    modals.forEach(modal => {
        modal.addEventListener('show.bs.modal', function () {
            const selects = modal.querySelectorAll('.upgrade-select');

            selects.forEach(select => {
const start = new Date(select.dataset.checkin).toISOString().split('T')[0];
const end = new Date(select.dataset.checkout).toISOString().split('T')[0];

                select.innerHTML = '<option value="">-- Memuat kamar tersedia... --</option>';

                fetch(`/sewa/cek-kamar-tersedia?start=${start}&end=${end}`)

                    .then(res => res.json())
                    .then(data => {
                        select.innerHTML = '<option value="">-- Tetap --</option>';

                        Object.entries(data).forEach(([kategoriId, hargaGroup]) => {
                            Object.entries(hargaGroup).forEach(([harga, info]) => {
                                if (info.count > 0) {
                                    info.kamar.forEach(k => {
                                        const option = document.createElement('option');
                                        option.value = k.id;
                                        option.textContent = `Kamar ${k.nomor} (${info.nama_kategori}) - Rp${parseInt(harga).toLocaleString()}`;
                                        select.appendChild(option);
                                    });
                                }
                            });
                        });

                        if (select.options.length === 1) {
                            select.innerHTML = '<option value="">Tidak ada kamar tersedia</option>';
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        select.innerHTML = '<option value="">Gagal memuat kamar</option>';
                    });
            });
        });
    });
});
</script>


@endsection
