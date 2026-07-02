@extends('admin.templates.sidebar')

@section('contain')
    <section>
        <div class="container-fluid px-4 py-4">
            <div class="row">
                <div class="col-md-12">
                    <h4>
                        BMN / <span class="fw-bold">Pemeliharaan</span>
                        @if ($activeTab === 'pengajuan')
                            / Pengajuan Baru
                        @elseif ($activeTab === 'monitor')
                            / Monitor Surat Pesanan
                        @elseif ($activeTab === 'pembayaran')
                            / Pengajuan Pembayaran
                        @endif
                    </h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="card border-0 bg-secondary">
                        <div class="page wrapper p-2">

                            @php
                                $jumlahPengajuan = $riwayat
                                    ->when(
                                        auth('administrator')->user()->hasRole('Pejabat Pengadaan'),
                                        fn($q) => $q->whereIn('id_ref_status_pemeliharaan', [3]),
                                        fn($q) => $q->whereIn('id_ref_status_pemeliharaan', [1]),
                                    )
                                    ->count();

                                $jumlahMonitor = $riwayat
                                    ->filter(fn($item) => $item->id_ref_status_pemeliharaan > 3)
                                    ->unique('nomor_surat_pesanan')
                                    ->count();

                                $jumlahPembayaran = $riwayat
                                    ->filter(function ($item) {
                                        return $item->id_ref_status_pemeliharaan > 15 &&
                                            $item->pesanan &&
                                            $item->pesanan->pembayaranPemeliharaan &&
                                            $item->pesanan->pembayaranPemeliharaan->url_pengajuan_pembayaran;
                                    })
                                    ->groupBy(function ($item) {
                                        // Gabungkan URL dengan status supaya grup berbeda per status
                                        return $item->pesanan->pembayaranPemeliharaan->url_pengajuan_pembayaran .
                                            '-' .
                                            $item->id_ref_status_pemeliharaan;
                                    })
                                    ->filter(fn($group, $key) => !empty($key))
                                    ->count();

                            @endphp

                            <div class="d-flex flex-wrap gap-2 justify-content-between">
                                <div class="d-flex flex-wrap gap-2">
                                    @if (auth('administrator')->user()->hasRole('Bendahara Pemeliharaan'))
                                        <a href="{{ url('/pemeliharaan-admin?tab=pembayaran') }}"
                                            class="page-wrap btn btn-sm {{ request()->get('tab') == 'pembayaran' ? 'btn-dark fw-bold' : 'btn-primary' }}">
                                            Pengajuan Pembayaran{{ $jumlahPembayaran > 0 ? " [$jumlahPembayaran]" : '' }}
                                        </a>
                                    @else
                                        <a href="{{ url('/pemeliharaan-admin?tab=pengajuan') }}"
                                            class="page-wrap btn btn-sm {{ request()->get('tab') == 'pengajuan' ? 'btn-dark fw-bold' : 'btn-primary' }}">
                                            Pengajuan Baru{{ $jumlahPengajuan > 0 ? " [$jumlahPengajuan]" : '' }}
                                        </a>
                                        <a href="{{ url('/pemeliharaan-admin?tab=monitor') }}"
                                            class="page-wrap btn btn-sm {{ request()->get('tab') == 'monitor' ? 'btn-dark fw-bold' : 'btn-primary' }}">
                                            Monitor Pesanan{{ $jumlahMonitor > 0 ? " [$jumlahMonitor]" : '' }}
                                        </a>
                                        <a href="{{ url('/pemeliharaan-admin?tab=pembayaran') }}"
                                            class="page-wrap btn btn-sm {{ request()->get('tab') == 'pembayaran' ? 'btn-dark fw-bold' : 'btn-primary' }}">
                                            Pengajuan Pembayaran{{ $jumlahPembayaran > 0 ? " [$jumlahPembayaran]" : '' }}
                                        </a>
                                    @endif
                                </div>

                                <a href="{{ route('laporan', ['page' => 'page_4']) }}"
                                    class="page-wrap btn btn-sm {{ request()->get('page') == 'page_4' ? 'btn-dark fw-bold' : 'btn-secondary' }}">
                                    <i class="fa-solid fa-print"></i> Rekapitulasi
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <h6 class="fw-bold mb-1">Indikator Status</h6>
                    <div class="d-flex flex-wrap gap-4">

                        <div class="d-flex align-items-center gap-2">
                            <span class="legend-box" style="background-color:#0d6efd;"></span>
                            <span>Pejabat Pemeliharaan</span>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <span class="legend-box" style="background-color:#ffc107;"></span>
                            <span>Pejabat Pengadaan</span>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <span class="legend-box" style="background-color:#198754;"></span>
                            <span>PPK</span>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <span class="legend-box" style="background-color:#6f42c1;"></span>
                            <span>Penyedia</span>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <span class="legend-box" style="background-color:#20c997;"></span>
                            <span>Bendahara</span>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <span class="legend-box" style="background-color:#0dcaf0;"></span>
                            <span>Selesai</span>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <span class="legend-box" style="background-color:#dc3545;"></span>
                            <span>Penolakan</span>
                        </div>
                    </div>
                </div>
            </div>

            @if ($activeTab === 'pengajuan')
                @php
                    $no = 1;
                    $pengajuanBaru = $riwayat->when(
                        auth('administrator')->user()->hasRole('Pejabat Pengadaan'),
                        fn($q) => $q->whereIn('id_ref_status_pemeliharaan', [3]),
                        fn($q) => $q->whereIn('id_ref_status_pemeliharaan', [1]),
                    );

                    // Riwayat Penolakan
                    $riwayatPenolakan = $riwayat->whereIn('id_ref_status_pemeliharaan', [2, 5, 7, 11]);
                    // dd($riwayatPenolakan);
                @endphp

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-body content">
                                <div class="table-responsive">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                                        <div class="d-flex gap-2 align-items-center">
                                            @if (auth('administrator')->user()->hasRole('Pejabat Pengadaan'))
                                                <button type="button" class="btn btn-success buat-pesanan-btn">
                                                    <i class="fa fa-file-alt"></i> Buat Surat Pesanan
                                                </button>
                                            @endif
                                        </div>
                                        @if (auth('administrator')->user()->hasRole('Pejabat Pemeliharaan'))
                                            {{-- <a href="{{ url('/pemeliharaan-admin/pengajuan') }}" class="btn btn-primary">
                                                <i class="fa fa-plus-square"></i> Ajukan Pemeliharaan Baru
                                            </a> --}}
                                        @endif
                                        <!-- Toggle Riwayat Penolakan di ujung kanan -->
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="toggleRiwayatPenolakan">
                                            <label class="form-check-label" for="toggleRiwayatPenolakan">Tampilkan
                                                Riwayat Penolakan</label>
                                        </div>
                                    </div>

                                    <table id="example" class="table table-bordered data-table" style="width: 100%">
                                        <thead>
                                            <tr class="small text-center">
                                                <th>No.</th>
                                                @if (auth('administrator')->user()->hasRole('Pejabat Pengadaan'))
                                                    <th><input type="checkbox" id="checkAllPegawai"></th>
                                                @endif
                                                <th>ID Pengajuan</th>
                                                <th>Pengaju</th>
                                                <th>Nama BMN</th>
                                                <th>Kategori</th>
                                                <th>Kode</th>
                                                <th>NUP</th>
                                                <th>Status</th>
                                                <th>Keterangan</th>
                                                @if (auth('administrator')->user()->hasRole('Pejabat Pemeliharaan'))
                                                    <th>Aksi</th>
                                                @endif
                                                <th>Tanggal Diajukan</th>
                                                <th>Tanggal Pemeriksaan</th>
                                                <th>Terakhir Diperbarui</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($riwayatPenolakan as $item)
                                                @php
                                                    $isRuangan = $item->bmn_type === 'ruangan';
                                                @endphp
                                                <tr class="riwayat-penolakan">
                                                    <td class="text-center nomor-barang"></td>
                                                    @if (auth('administrator')->user()->hasRole('Pejabat Pengadaan'))
                                                        @if (in_array($item->id_ref_status_pemeliharaan, [7, 11]))
                                                            <td class="text-center">
                                                                <input type="checkbox" class="check-item-pegawai"
                                                                    value="{{ $item->id_pemeliharaan }}">
                                                            </td>
                                                        @else
                                                            <td class="text-center">-
                                                            </td>
                                                        @endif
                                                    @endif
                                                    <td class="text-center">{{ $item->id_pemeliharaan ?? '-' }}</td>

                                                    <td>{{ optional($item->pegawai)->nama_lengkap ?? 'Sistem Akunkeun' }}
                                                    </td>
                                                    <td>{{ $item->bmn->nama_bmn ?? ($item->bmn->nama_ruangan ?? '-') }}
                                                    </td>
                                                    <td>{{ $item->bmn_type === 'ruangan' ? 'Ruangan' : $item->bmn->kategori_bmn ?? '-' }}
                                                    </td>
                                                    <td>{{ $item->bmn_type === 'ruangan' ? $item->bmn->kode_ruangan ?? '-' : $item->bmn->kode_bmn ?? '-' }}
                                                    </td>
                                                    <td>{{ $item->bmn_type === 'ruangan' ? '-' : $item->bmn->nup_bmn ?? '-' }}
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge bg-danger text-white">{{ $item->status->deskripsi_status }}</span>
                                                        <br>
                                                        <small class="text-danger">
                                                            Alasan:
                                                            @if (in_array($item->id_ref_status_pemeliharaan, [7, 11]))
                                                                {{ $item->pesanan?->penolakan()->latest()->first()?->alasan_penolakan ?? '-' }}
                                                            @else
                                                                {{ $item->penolakan()->latest()->first()?->alasan_penolakan ?? '-' }}
                                                            @endif
                                                        </small>
                                                    </td>
                                                    <td>{{ $item->keterangan ?? '-' }}</td>
                                                    @if (auth('administrator')->user()->hasRole('Pejabat Pemeliharaan'))
                                                        <td class="text-center">
                                                            -
                                                        </td>
                                                    @endif
                                                    <td class="text-center">
                                                        {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->translatedFormat('d F Y') }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $item->tgl_pemeriksaan
                                                            ? \Carbon\Carbon::parse($item->tgl_pemeriksaan)->locale('id')->translatedFormat('d F Y')
                                                            : '-' }}
                                                    </td>

                                                    <td class="text-center">
                                                        {{ $item->updated_at != $item->created_at ? \Carbon\Carbon::parse($item->updated_at)->locale('id')->translatedFormat('d F Y') : '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach

                                            @if ($pengajuanBaru->count() > 0)
                                                @foreach ($pengajuanBaru as $item)
                                                    @php
                                                        $status = $item->id_ref_status_pemeliharaan;

                                                        $colorClass = match (true) {
                                                            $status === 1
                                                                => 'bg-primary text-white', // Biru - Pejabat Pemeliharaan
                                                            in_array($status, [3, 8, 10, 13, 14])
                                                                => 'bg-warning text-dark', // Oranye - Pejabat Pengadaan
                                                            in_array($status, [4, 16])
                                                                => 'bg-success text-white', // Hijau - PPK
                                                            in_array($status, [6, 9, 12, 15, 18])
                                                                => 'bg-purple text-white', // Ungu - Penyedia
                                                            $status === 19 => 'bg-teal text-white', // Teal - Bendahara
                                                            $status === 21 => 'bg-info text-dark', // Selesai
                                                            default
                                                                => 'bg-danger text-white', // Merah - Status tak terdefinisi
                                                        };
                                                        $isRuangan = $item->bmn_type === 'ruangan';
                                                        $bmn = $item->bmn;
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center nomor-barang"></td>
                                                        @if (auth('administrator')->user()->hasRole('Pejabat Pengadaan'))
                                                            <td class="text-center">
                                                                <input type="checkbox" class="check-item-pegawai"
                                                                    value="{{ $item->id_pemeliharaan }}">
                                                            </td>
                                                        @endif
                                                        <td class="text-center">{{ $item->id_pemeliharaan ?? '-' }}</td>

                                                        <td>{{ optional($item->pegawai)->nama_lengkap ?? 'Sistem Akunkeun' }}
                                                        </td>
                                                        <td>{{ $bmn->nama_bmn ?? ($bmn->nama_ruangan ?? '-') }}</td>
                                                        <td>{{ $isRuangan ? 'Ruangan' : $bmn->kategori_bmn ?? '-' }}</td>
                                                        <td>{{ $isRuangan ? $bmn->kode_ruangan ?? '-' : $bmn->kode_bmn ?? '-' }}
                                                        </td>
                                                        <td>{{ $isRuangan ? '-' : $bmn->nup_bmn ?? '-' }}</td>

                                                        <td class="text-center">
                                                            <span class="badge {{ $colorClass }}">
                                                                {{ $item->status->deskripsi_status ?? '-' }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $item->keterangan ?? '-' }}</td>
                                                        @if (auth('administrator')->user()->hasRole('Pejabat Pemeliharaan'))
                                                            <td class="text-center">
                                                                <button type="button"
                                                                    class="btn btn-success btn-sm setujui-btn"
                                                                    data-id="{{ $item->id_pemeliharaan }}">
                                                                    <i class="fa fa-check"></i> Setujui
                                                                </button>


                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm tolak-btn"
                                                                    data-id="{{ $item->id_pemeliharaan }}"
                                                                    data-nama="{{ $bmn->nama_bmn ?? ($bmn->nama_ruangan ?? 'BMN') }}"
                                                                    data-kode="{{ $isRuangan ? $bmn->kode_ruangan ?? '-' : $bmn->kode_bmn ?? '-' }}"
                                                                    data-nup="{{ $isRuangan ? '-' : $bmn->nup_bmn ?? '-' }}">
                                                                    <i class="fa fa-times"></i> Tolak
                                                                </button>
                                                            </td>
                                                        @endif

                                                        <td class="text-center">
                                                            {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->translatedFormat('d F Y') }}
                                                        </td>
                                                        <td class="text-center">
                                                        {{ $item->tgl_pemeriksaan
                                                            ? \Carbon\Carbon::parse($item->tgl_pemeriksaan)->locale('id')->translatedFormat('d F Y')
                                                            : '-' }}
                                                    </td>
                                                        <td class="text-center">
                                                            {{ $item->updated_at != $item->created_at
                                                                ? \Carbon\Carbon::parse($item->updated_at)->locale('id')->translatedFormat('d F Y')
                                                                : '-' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @elseif ($riwayatPenolakan->count() === 0)
                                                <tr>
                                                    <td colspan="12" class="text-center">Tidak ada data yang tersedia
                                                        dalam tabel</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if ($activeTab === 'monitor')
                @php
                    $monitor = $riwayat
                        ->filter(fn($item) => $item->id_ref_status_pemeliharaan > 3)
                        ->sortBy('id_ref_status_pemeliharaan')
                        ->unique('nomor_surat_pesanan');
                @endphp
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-body content">
                                <div class="table-responsive">
                                    <table id="example3" class="table table-bordered data-table" style="width: 100%">
                                        <thead>
                                            <tr class="small text-center">
                                                <th class="th-sm">No.</th>
                                                <th class="th-lg">Perihal</th>
                                                <th class="th-sm">Status</th>
                                                <th class="th-sm">Penyedia</th>
                                                <th class="th-sm">Tanggal Diajukan</th>
                                                <th class="th-sm">Terakhir Diperbarui</th>
                                                <th class="th-sm">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($monitor->count() > 0)
                                                @foreach ($monitor as $item)
                                                    <tr>
                                                        <td class='text-center'>{{ $loop->iteration }}</td>
                                                        </td>
                                                        <td>
                                                            @php
$pesanan = $item->pesanan;
$perihal = $pesanan?->perihal;

$hasil = $perihal
    ? (explode('-', $perihal, 2)[1] ?? $perihal)
    : sprintf(
        '(Tidak ada perihal) ID Pengajuan: %s %s %s %s',
        $item->id_pemeliharaan ?? '-',
        $item->bmn?->nama_bmn ?? '-',
        $item->bmn?->kode_bmn ?? '-',
        $item->bmn?->nup_bmn ?? '-'
    );
@endphp

{{ $hasil }}

                                                        </td>

                                                        <td class="text-center"
                                                            data-order="{{ $item->id_ref_status_pemeliharaan }}">
                                                            @php
                                                                $status = $item->id_ref_status_pemeliharaan;

                                                                $colorClass = match (true) {
                                                                    $status === 1
                                                                        => 'bg-primary text-white', // Biru - Pejabat Pemeliharaan
                                                                    in_array($status, [3, 8, 10, 13, 14])
                                                                        => 'bg-warning text-dark', // Oranye - Pejabat Pengadaan
                                                                    in_array($status, [4, 16])
                                                                        => 'bg-success text-white', // Hijau - PPK
                                                                    in_array($status, [6, 9, 12, 15, 18])
                                                                        => 'bg-purple text-white', // Ungu - Penyedia
                                                                    $status === 19
                                                                        => 'bg-teal text-white', // Teal - Bendahara
                                                                    $status === 21
                                                                        => 'bg-info text-dark', // Abu-abu - Selesai
                                                                    default
                                                                        => 'bg-danger text-white', // Merah - Status tak terdefinisi
                                                                };
                                                            @endphp
                                                            <span class="badge {{ $colorClass }}">
                                                                {{ $item->status->deskripsi_status ?? '-' }}
                                                            </span>
                                                            @if (in_array($item->id_ref_status_pemeliharaan, [5, 7, 11]))
                                                                <br>
                                                                <small class="text-danger">
                                                                    Alasan:
                                                                    {{ $item->pesanan?->penolakan()->latest()->first()?->alasan_penolakan ?? '' }}
																	{{ $item->penolakan()->latest()->first()?->alasan_penolakan ?? '' }}
                                                                </small>
                                                            @endif
                                                        </td>

                                                        <td class="text-center align-middle">
                                                            {{ $item->penyedia->nama_CV }}
                                                        </td>
                                                        <td>{{ $item->pesanan?->created_at?->locale('id')->translatedFormat('d F Y') ?? '-' }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->updated_at)->locale('id')->translatedFormat('d F Y') }}
                                                        </td>
                                                        <td class="text-center">
                                                            <div
                                                                class="d-flex justify-content-center align-items-center gap-1">
                                                                @include(
                                                                    'admin.pemeliharaan.partials._lihat_file_btn',
                                                                    [
                                                                        'pengajuan' =>
                                                                            $item->pesanan->pembayaranPemeliharaan ?? '',
                                                                        'item' => $item,
                                                                        'ids' => isset($item->id_pemeliharaan)
                                                                            ? collect([$item->id_pemeliharaan])
                                                                            : collect(),
                                                                        'buktiPengembalians' =>
                                                                            $item->pesanan->buktiPengembalians ??
                                                                            collect(),
                                                                    ]
                                                                )
                                                                @if (auth('administrator')->user()->hasRole('PPK') && $item->id_ref_status_pemeliharaan == 4)
                                                                    <button class="btn btn-success ppk-setujui-pesanan-btn"
                                                                        data-pesanan="{{ $item->nomor_surat_pesanan }}"><i
                                                                            class="fa fa-check"></i> Setuju</button>
                                                                    <button class="btn btn-danger ppk-tolak-btn-pesanan"
                                                                        data-pesanan="{{ $item->nomor_surat_pesanan }}"><i
                                                                            class="fa fa-times"></i> Tolak</button>
                                                                @endif
                                                                @if ($item->id_ref_status_pemeliharaan == 8)
                                                                    <button
                                                                        class="btn btn-success ppg-konfirmasi-pengambilan-btn"
                                                                        data-pesanan="{{ $item->nomor_surat_pesanan }}">Konfirmasi
                                                                        Pengambilan</button>
                                                                @endif
                                                                @if (auth('administrator')->user()->hasRole('Pejabat Pengadaan') && $item->id_ref_status_pemeliharaan == 10)
                                                                    <button class="btn btn-success ppg-tawar-btn"
                                                                        data-pesanan="{{ $item->nomor_surat_pesanan }}">Penawaran
                                                                        Biaya</button>
                                                                @endif
                                                                @if ($item->id_ref_status_pemeliharaan == 13)
                                                                    <button class="btn btn-success konfirmasi_pengembalian"
                                                                        data-pesanan="{{ $item->nomor_surat_pesanan }}">Konfirmasi
                                                                        Pengembalian</button>
                                                                @endif
                                                                @if ($item->id_ref_status_pemeliharaan == 14)
                                                                    <button class="btn btn-success konfirmasi_penyelesaian"
                                                                        data-pesanan="{{ $item->nomor_surat_pesanan }}">Konfirmasi
                                                                        Penyelesaian</button>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="10" class="text-center">Tidak ada data yang tersedia
                                                        dalam tabel</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($activeTab === 'pembayaran')
                @php
                    $pengajuanPembayaran = $riwayat
                        ->filter(
                            fn($item) => $item->id_ref_status_pemeliharaan > 15 &&
                                optional($item->pesanan)->pembayaranPemeliharaan &&
                                optional($item->pesanan->pembayaranPemeliharaan)->url_pengajuan_pembayaran,
                        )
                        ->groupBy(
                            fn($item) => optional($item->pesanan->pembayaranPemeliharaan)->url_pengajuan_pembayaran .
                                '-' .
                                $item->id_ref_status_pemeliharaan,
                        );

                @endphp

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-header d-flex justify-content-end">
                                <a href="https://sakti.kemenkeu.go.id/" target="_blank" class="btn btn-primary">
                                    Link SAKTI
                                </a>
                            </div>
                            <div class="card-body content">
                                <div class="table-responsive">
                                    <table id="example2" class="table table-bordered data-table" style="width: 100%">
                                        <thead>
                                            <tr class="small text-center">
                                                <th class="th-sm">No.</th>
                                                <th class="th-lg">Perihal</th>
                                                <th class="th-sm">Status</th>
                                                <th class="th-sm">Penyedia</th>
                                                <th class="th-sm">Tanggal Diajukan</th>
                                                <th class="th-sm">Terakhir Diperbarui</th>
                                                <th class="th-sm">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($pengajuanPembayaran->count() > 0)
                                                @foreach ($pengajuanPembayaran as $pengajuan => $items)
                                                    @php
                                                        $firstItem = $items->first();
                                                        $pembayaran = optional($firstItem->pesanan)
                                                            ->pembayaranPemeliharaan;
                                                        $idPembayaran = $firstItem->pesanan->id_pembayaran_pemeliharaan;

                                                        $urlBast = \App\Models\SuratPemeliharaan::where(
                                                            'id_pembayaran_pemeliharaan',
                                                            $idPembayaran,
                                                        )
                                                            ->where('perihal', 'like', 'BAST%')
                                                            ->value('url_surat');

                                                        $urlBap = \App\Models\SuratPemeliharaan::where(
                                                            'id_pembayaran_pemeliharaan',
                                                            $idPembayaran,
                                                        )
                                                            ->where('perihal', 'like', 'BAP%')
                                                            ->value('url_surat');

                                                        $perihals = $items
                                                            ->map(fn($i) => $i->pesanan->perihal)
                                                            ->unique()
                                                            ->values();

                                                        $idPesananList = $items
                                                            ->map(fn($i) => $i->nomor_surat_pesanan)
                                                            ->unique()
                                                            ->values()
                                                            ->all();

                                                        $buktiPengembalians = $items
                                                            ->pluck('pesanan')
                                                            ->filter()
                                                            ->flatMap(
                                                                fn($pesanan) => $pesanan->buktiPengembalians ??
                                                                    collect(),
                                                            )
                                                            ->unique('nomor_surat_pesanan')
                                                            ->values();

                                                        $status = $firstItem->id_ref_status_pemeliharaan;
                                                        $badgeColorClass = match (true) {
                                                            $status === 1 => 'bg-primary text-white',
                                                            in_array($status, [3, 8, 10, 13, 14])
                                                                => 'bg-warning text-dark',
                                                            in_array($status, [4, 16]) => 'bg-success text-white',
                                                            in_array($status, [6, 9, 12, 15, 18])
                                                                => 'bg-purple text-white',
                                                            $status === 19 => 'bg-teal text-white',
                                                            $status === 21 => 'bg-info text-dark',
                                                            default => 'bg-danger text-white',
                                                        };
                                                        $pesananList = $items
                                                            ->pluck('pesanan.url_surat')
                                                            ->filter()
                                                            ->unique()
                                                            ->values();

                                                    @endphp

                                                    <tr>
                                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                        <td class="align-middle">
                                                            {!! $perihals->map(fn($perihal) => explode('-', $perihal, 2)[1] ?? $perihal)->implode('<br>') !!}
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <span class="badge {{ $badgeColorClass }}">
                                                                {{ $firstItem->status->deskripsi_status ?? '-' }}
                                                            </span>
                                                            @if ($firstItem->id_ref_status_pemeliharaan == 21 && $pembayaran?->nomor_perintah_bayar)
                                                                <br>
                                                                Nomor Surat Perintah Membayar:
                                                                {{ $pembayaran->nomor_perintah_bayar }}
                                                            @endif
                                                            @php
                                                                $penolakanTerakhir = $firstItem->pesanan->pembayaranPemeliharaan
                                                                    ?->penolakan()
                                                                    ->latest()
                                                                    ->first();
                                                            @endphp

                                                            @if (in_array($firstItem->id_ref_status_pemeliharaan, [17, 20]) &&
                                                                    $penolakanTerakhir?->entitas_type === 'pengajuan_pembayaran')
                                                                <br>
                                                                <small class="text-danger">
                                                                    Alasan:
                                                                    {{ $penolakanTerakhir->alasan_penolakan ?? '-' }}
                                                                </small>
                                                            @endif

                                                        </td>
                                                        <td class="text-center align-middle">
                                                            {{ $firstItem->penyedia->nama_CV ?? '-' }}</td>
                                                        <td class="text-center align-middle">
                                                            {{ \Carbon\Carbon::parse($pembayaran->created_at)->locale('id')->translatedFormat('d F Y') }}
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            {{ \Carbon\Carbon::parse($pembayaran->updated_at)->locale('id')->translatedFormat('d F Y') }}
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            @include(
                                                                'admin.pemeliharaan.partials._lihat_file_btn',
                                                                [
                                                                    'pengajuan' =>
                                                                        $pembayaran->url_pengajuan_pembayaran ??
                                                                        '',
                                                                    'item' => $firstItem,
                                                                    'ids' => collect($items)->pluck('id'),
                                                                    'lampiranList' =>
                                                                        $pembayaran->lampiran ?? collect(),
                                                                    'buktiPengembalians' => $buktiPengembalians,
                                                                    'urlBast' => $urlBast ?? null,
                                                                    'urlBap' => $urlBap ?? null,
                                                                    'pesananList' => $pesananList,
                                                                ]
                                                            )

                                                            @if (auth('administrator')->user()->hasRole('PPK') && $firstItem->id_ref_status_pemeliharaan == 16)
                                                                <button class="btn btn-success ppk-buat-bap"
                                                                    data-pesanan='@json($idPesananList)'>
                                                                    <i class="fa fa-check"></i> Setuju
                                                                </button>
                                                                <button class="btn btn-danger ppk-tolak-pengajuan-bayar"
                                                                    data-pesanan='@json($idPesananList)'
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modalTolakPembayaran">
                                                                    <i class="fa fa-times"></i> Tolak
                                                                </button>
                                                            @endif

                                                            @if (auth('administrator')->user()->hasRole('Bendahara Pemeliharaan') && $firstItem->id_ref_status_pemeliharaan == 19)
                                                                <button class="btn btn-success bendahara-selesai-btn"
                                                                    data-pesanan='@json($idPesananList)'>
                                                                    <i class="fa fa-check"></i> Selesai Bayar
                                                                </button>
                                                                <button class="btn btn-danger bendahara-tolak-btn"
                                                                    data-pesanan='@json($idPesananList)'>
                                                                    <i class="fa fa-times"></i> Tolak
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5" class="text-center">Tidak ada data yang tersedia
                                                        dalam tabel</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
        {{-- Modal Input Tanggal Pemeriksaan --}}
        <div class="modal fade" id="setujuiModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ url('/pemeliharaan-admin/pph-setujui') }}">
                    @csrf
                    <input type="hidden" name="id" id="setujuiId">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Setujui Pengajuan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="tgl_pemeriksaan" class="form-label">Tanggal Pemeriksaan</label>
                                <input type="date" class="form-control" id="tgl_pemeriksaan" name="tgl_pemeriksaan"
                                    value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Setujui</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Input Nomor Perintah Bayar -->
        <div class="modal fade" id="modalPerintahBayar" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form id="formPerintahBayar">
                    @csrf
                    <input type="hidden" name="nomor_surat_pesanan" id="input_nomor_surat_pesanan">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Referensi SAKTI</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <label for="nomor_perintah_bayar" class="form-label">Nomor Perintah Membayar</label>
                            <input type="text" name="nomor_perintah_bayar" id="nomor_perintah_bayar"
                                class="form-control" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Tolak Pengajuan Pembayaran -->
        <div class="modal fade" id="modalTolakPembayaran" tabindex="-1" aria-labelledby="modalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form id="formTolakPengajuanPembayaran" method="POST"
                    action="{{ url('/pemeliharaan-admin/ppk-tolak-pengajuan-pembayaran') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tolak Pengajuan Pembayaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="pesanan_ids" id="pesanan_ids">
                            <div class="mb-3">
                                <label for="alasan" class="form-label">Alasan Penolakan</label>
                                <textarea name="alasan_penolakan" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Modal Tolak Pengajuan Pembayaran Bendahara -->
        <div class="modal fade" id="modalTolakPembayaranBendahara" tabindex="-1" aria-labelledby="modalLabelBendahara"
            aria-hidden="true">
            <div class="modal-dialog">
                <form id="formTolakPengajuanPembayaranBendahara" method="POST"
                    action="{{ url('/pemeliharaan-admin/bendahara-tolak') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tolak Pengajuan Pembayaran (Bendahara)</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="pesanan_ids" id="pesanan_ids_bendahara">
                            <div class="mb-3">
                                <label for="alasan" class="form-label">Alasan Penolakan</label>
                                <textarea name="alasan_penolakan" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Tolak Per BMN -->
        <div class="modal fade" id="tolakModal" tabindex="-1" aria-labelledby="tolakModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="tolakForm" method="POST"
                        action="{{ url('/pemeliharaan-admin/tolak-pengajuan-pegawai') }}">
                        @csrf
                        <input type="hidden" name="user_role" value="{{ implode(',', $userRole) }}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tolakModalLabel">Konfirmasi Penolakan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div id="tolakItems"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="tawarHargaModal" tabindex="-1" aria-labelledby="tawarHargaLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form id="formTawarHarga">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Penawaran Biaya Pemeliharaan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body" id="tawarHargaBody">
                            <!-- Isian dinamis -->
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="nomor_surat_pesanan" id="inputIdPesanan">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Modal Bukti Pengembalian -->
        <div class="modal fade" id="modalBuktiPengembalian" tabindex="-1" aria-labelledby="modalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form id="formBuktiPengembalian" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="nomor_surat_pesanan" id="idPesananBukti">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Upload Bukti Pengembalian</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div id="buktiWrapper">
                                <div class="row bukti-item mb-2">
                                    <div class="col-md-6">
                                        <input type="text" name="nama_file[]" class="form-control"
                                            placeholder="Nama Bukti" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="url_bukti[]" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-secondary" id="tambahBukti">+ Tambah
                                Bukti</button>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $.fn.dataTable.ext.errMode = 'none';

            function updateNomorTabel() {
                $('#example tbody tr:visible').each(function(index) {
                    $(this).find('.nomor-barang').text(index + 1);
                });
            }
            var table; // deklarasi global

            if ($('#example').length > 0) {
                if (!$.fn.DataTable.isDataTable('#example')) {
                    table = $('#example').DataTable({
                        order: [
                            [8, 'asc']
                        ],
                        drawCallback: function() {
                            updateNomorTabel();
                        }
                    });
                } else {
                    table = $('#example').DataTable();
                }

                updateNomorTabel();

                // Tambahkan filter HANYA setelah table terdefinisi
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    if (!table) return true; // kalau belum ada table, jangan error
                    var rowNode = table.row(dataIndex).node();

                    if (!$('#toggleRiwayatPenolakan').is(':checked')) {
                        if ($(rowNode).hasClass('riwayat-penolakan')) {
                            return false;
                        }
                    }
                    return true;
                });
            }


            // Toggle redraw (jika tidak ada riwayat, tidak bikin error)
            $('#toggleRiwayatPenolakan').change(function() {
                table.draw();
            });


            if ($('#example3 tbody tr').length > 0 && $('#example3 tbody tr').not(':has(td[colspan])').length > 0) {
                // Inisialisasi DataTable
                $('#example3').DataTable({
                    order: [
                        [2, 'asc']
                    ] // urut berdasarkan kolom Status (index 2)
                });
            }
            if ($('#example2 tbody tr').length > 0 && $('#example2 tbody tr').not(':has(td[colspan])').length > 0) {
                // Inisialisasi DataTable
                $('#example2').DataTable({
                    order: [
                        [2, 'asc']
                    ] // urut berdasarkan kolom Status (index 2)
                });
            }
            $(document).on('click', '.setujui-btn', function() {
                let id = $(this).data('id');
                $('#setujuiId').val(id);
                $('#setujuiModal').modal('show');
            });

            $(document).on('click', '.tolak-btn', function() {
                const id = $(this).data('id');
                const nama = $(this).data('nama');
                const kode = $(this).data('kode');
                const nup = $(this).data('nup');

                $('#tolakItems').html(`
        <div class="mb-3 border-bottom pb-2">
            <label class="form-label">
                <strong>${nama}</strong><br>
                <small>Kode: ${kode} | NUP: ${nup}</small>
            </label>
            <input type="hidden" name="selected_ids[]" value="${id}">
            <textarea name="alasan[${id}]" class="form-control" placeholder="Isi alasan penolakan" required></textarea>
        </div>
    `);

                $('#tolakModal').modal('show');
            });

            $('.bendahara-tolak-btn').click(function() {
                const pesanan = $(this).data('pesanan'); // array of id pesanan
                $('#pesanan_ids_bendahara').val(JSON.stringify(pesanan));
                $('#modalTolakPembayaranBendahara').modal('show');
            });

            let pesananToSubmit = [];

            $(document).on('click', '.bendahara-selesai-btn', function(e) {
                e.preventDefault();
                const idPesananList = $(this).data('pesanan');

                if (!Array.isArray(idPesananList) || idPesananList.length === 0) {
                    alert('Data pesanan tidak valid.');
                    return;
                }

                pesananToSubmit = idPesananList;
                $('#input_nomor_surat_pesanan').val(JSON.stringify(idPesananList));
                $('#modalPerintahBayar').modal('show');
            });

            $('#formPerintahBayar').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ url('/pemeliharaan-admin/bendahara-selesai') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        nomor_surat_pesanan: pesananToSubmit,
                        nomor_perintah_bayar: $('#nomor_perintah_bayar').val(),
                    },
                    success: function(res) {
                        alert(res.success);
                        location.reload();
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.error || 'Terjadi kesalahan.');
                    }
                });
            });


            document.querySelectorAll('.ppk-buat-bap').forEach(button => {
                button.addEventListener('click', function() {
                    const pesanan = this.dataset.pesanan; // json array string
                    const pesananIds = JSON.parse(pesanan).join(',');
                    window.location.href = `pemeliharaan-admin/buat-bap?pesanan=${pesananIds}`;
                });
            });

            $('.ppk-tolak-pengajuan-bayar').click(function() {
                const pesanan = $(this).data('pesanan'); // array of id pesanan
                $('#pesanan_ids').val(JSON.stringify(pesanan));
            });

            function toggleApprovalButtons() {
                const isChecked = $('.check-item-pegawai:checked').length > 0;
                $('.proses-tolak-btn, .buat-pesanan-btn').prop('disabled', !isChecked);
            }

            // Inisialisasi saat page load
            toggleApprovalButtons();

            // Trigger saat checkbox pegawai diubah
            $(document).on('change', '.check-item-pegawai, #checkAllPegawai', function() {
                toggleApprovalButtons();
            });

            $(document).on('click', '.konfirmasi_pengembalian, .konfirmasi_penyelesaian', function() {
                const idPesanan = $(this).data('pesanan');
                $('#idPesananBukti').val(idPesanan);
                $('#modalBuktiPengembalian').modal('show');
            });

            $('#tambahBukti').on('click', function() {
                const newItem = `
    <div class="row bukti-item mb-2">
        <div class="col-md-6">
            <input type="text" name="nama_file[]" class="form-control" placeholder="Nama Bukti" required>
        </div>
        <div class="col-md-6">
            <input type="file" name="url_bukti[]" class="form-control" required>
        </div>
    </div>`;
                $('#buktiWrapper').append(newItem);
            });

            $('#formBuktiPengembalian').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this); // Ambil semua file yang sudah dipilih

                $.ajax({
                    url: '/pemeliharaan-admin/terima-bmn',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: res => {
                        alert(res.message || 'Berhasil dikonfirmasi.');
                        $('#modalBuktiPengembalian').modal('hide');
                        location.reload();
                    },
                    error: () => {
                        alert('Gagal mengunggah bukti.');
                    }
                });
            });


            $(document).on('click', '.ppg-tawar-btn', function() {
                const idPesanan = $(this).data('pesanan');
                $('#inputIdPesanan').val(idPesanan);

                $.get(`/kelompok-barang/${idPesanan}`, function(data) {
                    let html = `<table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center align-middle">Nama BMN</th>
                    <th class="text-center align-middle">Jumlah</th>
                    <th class="text-center align-middle">Penawaran (Penyedia)</th>
                    <th class="text-center align-middle">Penawaran (LLDIKTI IV)</th>
                </tr>
            </thead><tbody>`;

                    data.forEach(item => {
                        const nilaiPenyedia = item.nilai_nego_penyedia ? formatRupiah(item
                            .nilai_nego_penyedia.toString()) : '';
                        const nilaiPP = item.nilai_nego_pp ? formatRupiah(item.nilai_nego_pp
                                .toString()) :
                            (item.nilai_nego_penyedia ? formatRupiah(item
                                .nilai_nego_penyedia.toString()) : '');

                        html += `<tr>
                <td>${item.nama_bmn}</td>
                <td class="text-center align-middle">${item.jumlah_bmn}</td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" class="form-control nilai-penyedia" data-id="${item.id_kelompok_barang_pesanan}" value="${nilaiPenyedia}" disabled>
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" name="nilai_nego_pp[${item.id_kelompok_barang_pesanan}]" class="form-control format-rupiah nilai-nego-pp" value="${nilaiPP}" required>
                    </div>
                </td>
            </tr>`;
                    });

                    html += `<tr>
            <td colspan="2" class="text-end fw-bold">Total</td>
            <td>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" id="totalPenyedia" class="form-control" readonly>
                </div>
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" id="totalPP" class="form-control" readonly>
                </div>
            </td>
        </tr>`;

                    html += `</tbody></table>                    

            <div class="d-flex justify-content-between gap-2">
                <button type="button" class="btn btn-danger" id="btnTolakPenawaran">Tolak</button>
                <div>
                    <button type="button" class="btn btn-primary" id="btnKirimPenawaran">Kirim Penawaran</button>
                    <button type="button" class="btn btn-success" id="btnSetujuiPenawaran">Setujui Penawaran Penyedia</button>
                </div>
            </div>

            <div id="alasanPenolakanWrapper" class="mt-3 d-none">
                <label for="inputAlasanPenolakan" class="form-label">Alasan Penolakan</label>
                <textarea class="form-control" id="inputAlasanPenolakan" rows="3"></textarea>
                <button type="button" class="btn btn-outline-danger mt-2" id="btnKonfirmasiPenolakan">Kirim Penolakan</button>
            </div>
        `;

                    $('#tawarHargaBody').html(html);
                    hitungTotal();
                    $('#tawarHargaModal').modal('show');
                });
            });

            // Format dan bersihkan rupiah
            function formatRupiah(angka) {
                return angka.replace(/[^\d]/g, '')
                    .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            $(document).on('input', '.format-rupiah', function() {
                const rawValue = $(this).val().replace(/\./g, '');
                $(this).val(formatRupiah(rawValue));
                hitungTotal(); // ← tambahkan ini
            });

            function hitungTotal() {
                let totalPenyedia = 0;
                let totalPP = 0;

                $('#tawarHargaBody table tbody tr').each(function() {
                    const penyediaVal = $(this).find('td:eq(2) input[disabled]').val();
                    const ppVal = $(this).find('td:eq(3) input').val();

                    totalPenyedia += parseRupiah(penyediaVal);
                    totalPP += parseRupiah(ppVal);
                });

                $('#totalPenyedia').val(formatRupiah(totalPenyedia.toString()));
                $('#totalPP').val(formatRupiah(totalPP.toString()));
            }



            function parseRupiah(rp) {
                return parseInt((rp || '').replace(/\./g, '')) || 0;
            }

            // Input formatter
            $(document).on('input', '.format-rupiah', function() {
                const rawValue = $(this).val().replace(/\./g, '');
                $(this).val(formatRupiah(rawValue));
            });

            // Kirim Penawaran
            $(document).on('click', '#btnKirimPenawaran', function(e) {
                e.preventDefault();

                // Ambil form parent tombol (atau tentukan ID form)
                const form = $(this).closest('form')[0];

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                // Validasi apakah ada nilai_nego_pp yang berbeda dengan nilai_nego_penyedia
                let adaPerubahan = false;

                $('.nilai-nego-pp').each(function() {
                    const id = $(this).attr('name').match(/\d+/)[0];
                    const nilaiPP = parseRupiah($(this).val());

                    const nilaiPenyediaInput = $(`.nilai-penyedia[data-id="${id}"]`);
                    const nilaiPenyedia = parseRupiah(nilaiPenyediaInput.val());

                    if (nilaiPP !== nilaiPenyedia) {
                        adaPerubahan = true;
                        return false; // break loop early
                    }
                });

                if (!adaPerubahan) {
                    alert('Ubah setidaknya 1 nilai untuk mengirim penawaran');
                    return;
                }

                const idPesanan = $('#inputIdPesanan').val();
                const data = {};

                $('[name^="nilai_nego_pp"]').each(function() {
                    const id = $(this).attr('name').match(/\d+/)[0];
                    const nilai = parseRupiah($(this).val());
                    if (nilai > 0) {
                        data[id] = nilai;
                    }
                });

                $.ajax({
                    url: '/pemeliharaan-admin/kirim-penawaran',
                    method: 'POST',
                    data: {
                        nomor_surat_pesanan: idPesanan,
                        nilai_nego_pp: data,
                        _token: '{{ csrf_token() }}'
                    },
                    success: res => {
                        alert(res.message);
                        $('#tawarHargaModal').modal('hide');
                        location.reload();
                    },
                    error: () => alert('Gagal mengirim penawaran.')
                });
            });

            // Tampilkan input alasan
            $(document).on('click', '#btnTolakPenawaran', function() {
                $('#alasanPenolakanWrapper').removeClass('d-none');
            });

            // Kirim penolakan
            $(document).on('click', '#btnKonfirmasiPenolakan', function(e) {
                e.preventDefault();

                const alasanTextarea = $('#inputAlasanPenolakan');
                const alasan = alasanTextarea.val().trim();

                if (!alasan) {
                    alert('Silakan lengkapi alasan penolakan.');
                    alasanTextarea.focus();
                    return;
                }

                const idPesanan = $('#inputIdPesanan').val();

                $.ajax({
                    url: '/pemeliharaan-admin/tolak-penawaran',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        nomor_surat_pesanan: idPesanan,
                        alasan_penolakan: alasan
                    },
                    success: res => {
                        alert(res.message);
                        $('#tawarHargaModal').modal('hide');
                        location.reload();
                    },
                    error: () => alert('Gagal menolak penawaran.')
                });
            });




            // Setujui Penawaran
            $(document).on('click', '#btnSetujuiPenawaran', function() {
                const idPesanan = $('#inputIdPesanan').val();

                $.post('/pemeliharaan-admin/setujui-penawaran', {
                    nomor_surat_pesanan: idPesanan,
                    _token: '{{ csrf_token() }}'
                }, res => {
                    alert(res.message);
                    $('#tawarHargaModal').modal('hide');
                    location.reload();
                }).fail(() => {
                    alert('Gagal menyetujui penawaran.');
                });
            });

            $(document).on('click', '.ppg-konfirmasi-pengambilan-btn', function() {
                const idPesanan = $(this).data('pesanan');
                if (!confirm('Apakah anda yakin BMN ini sudah diambil?')) return;

                $.ajax({
                    url: '/pemeliharaan-admin/ppg-konfirmasi-pengambilan/' + encodeURIComponent(
                        idPesanan),

                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        alert(res.message);
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Terjadi Kesalahaan Saat Menyetujui.');
                    }
                });
            });
            $(document).on('click', '.ppk-setujui-pesanan-btn', function() {
                const idPesanan = $(this).data('pesanan');
                if (!confirm('Apakah anda yakin ingin menyetujui pesanan ini?')) return;

                $.ajax({
                    url: '/pemeliharaan-admin/ppk-setujui-pesanan/' + encodeURIComponent(idPesanan),
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        alert(res.message);
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Terjadi Kesalahan Saat Konfirmasi Pengambilan.');
                    }
                });
            });
            // Tombol Buat Pesanan
            $('.buat-pesanan-btn').on('click', function() {
                const selected = $('.check-item-pegawai:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selected.length === 0) {
                    alert('Pilih minimal satu pengajuan untuk dibuatkan pesanan.');
                    return;
                }

                // Ambil keterangan dari baris yang dicentang
                const keteranganList = $('.check-item-pegawai:checked').closest('tr').map(function() {
                    // Ambil isi kolom keterangan (misal kolom ke-10 sesuai tabel Anda)
                    return $(this).find('td').eq(9).text().trim();
                }).get();

                // Gabungkan dengan koma
                const gabunganKeterangan = keteranganList.filter(k => k !== '-').join(', ');

                // Buat form dinamis
                const form = $('<form>', {
                    method: 'GET',
                    action: '/pemeliharaan-admin/buat-pesanan'
                });

                form.append('@csrf'); // Blade akan render token

                // Masukkan selected IDs
                selected.forEach(id => {
                    form.append(`<input type="hidden" name="selected_ids[]" value="${id}">`);
                });

                // Masukkan gabungan keterangan ke input hidden
                form.append(`<input type="hidden" name="keterangan_bmn" value="${gabunganKeterangan}">`);

                $('body').append(form);
                form.submit();
            });

            // Centang semua checkbox monitor
            $('#checkAllMonitor').on('change', function() {
                $('.check-monitor').prop('checked', this.checked);
            });
            $('#checkAll').on('change', function() {
                $('.checkItem').prop('checked', $(this).is(':checked'));
            });

            // Check/uncheck semua item pegawai
            $('#checkAllPegawai').on('change', function() {
                $('.check-item-pegawai').prop('checked', this.checked);
            });

            // Sync checkAll jika semua dicentang manual
            $(document).on('change', '.check-item-pegawai', function() {
                const total = $('.check-item-pegawai').length;
                const checked = $('.check-item-pegawai:checked').length;
                $('#checkAllPegawai').prop('checked', total === checked);
            });
            // Tolak modal
            $('.proses-tolak-btn').on('click', function() {
                const selectedIds = $('.check-item-pegawai:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    alert('Pilih minimal 1 item.');
                    $('#tolakModal').modal('hide');
                    return false;
                }

                $('#tolakItems').empty();
                selectedIds.forEach(function(id) {
                    const $row = $(`.check-item-pegawai[value="${id}"]`).closest('tr');
                    const nama = $row.find('td').eq(4).text();
                    const kode = $row.find('td').eq(6).text();
                    const nup = $row.find('td').eq(7).text();

                    $('#tolakItems').append(`
                <div class="mb-3 border-bottom pb-2">
                    <label class="form-label">
                        <strong>${nama}</strong><br>
                        <small>Kode: ${kode} | NUP: ${nup}</small>
                    </label>
                    <input type="hidden" name="selected_ids[]" value="${id}">
                    <textarea name="alasan[${id}]" class="form-control" placeholder="Isi alasan penolakan" required></textarea>
                </div>
            `);
                });
            });
            $('.ppk-tolak-btn-pesanan').on('click', function() {
                const idPesanan = $(this).data('pesanan');
                const pemeliharaanItems = @json($riwayat);

                const selectedItems = pemeliharaanItems.filter(item => item.nomor_surat_pesanan ==
                    idPesanan);
                if (selectedItems.length === 0) {
                    alert('Tidak ada item yang bisa ditolak.');
                    return;
                }

                $('#tolakItems').empty();
                selectedItems.forEach(function(item) {
                    const idItem = item.bmn.id_inventaris_bmn ?? item.bmn.id_ruangan_bmn ?? '-';
                    $('#tolakItems').append(`
            <div class="mb-3 border-bottom pb-2">
                <label class="form-label">
                    <strong>${item.bmn?.nama_bmn ?? item.bmn?.nama_ruangan ?? 'BMN'} | Kode: ${item.bmn?.kode_bmn ?? item.bmn?.kode_ruangan ?? '-'}</strong><br>
                    <small>ID: ${idItem} | NUP: ${item.bmn?.nup_bmn ?? '-'}</small>
                </label>
                <input type="hidden" name="selected_ids[]" value="${item.id_pemeliharaan}">
                <textarea name="alasan[${item.id_pemeliharaan}]" class="form-control" placeholder="Isi alasan penolakan" required></textarea>
            </div>
        `);
                });

                $('#tolakModal').modal('show');
            });

            $(document).on('click', '.lihat-file-btn', function(e) {
                e.preventDefault();
                $('.popover').remove(); // Tutup popover lain

                const $btn = $(this);
                let content = '';

                // =======================
                // 1. Section: PESANAN
                // =======================
                let index = 1;
                let foundPesanan = false;
                let pesananContent = '';

                while ($btn[0].hasAttribute(`data-pesanan${index}`)) {
                    const pesanan = $btn.data(`pesanan${index}`);
                    if (pesanan) {
                        pesananContent += `
                <a href="/getDokumen/${pesanan}" target="_blank"
                   class="btn btn-sm btn-primary d-block mb-2">
                    <i class="fa fa-file-pdf"></i> Surat Pesanan ${index}
                </a>`;
                        foundPesanan = true;
                    }
                    index++;
                }

                if (!foundPesanan) {
                    const pesanan = $btn.data('pesanan');
                    if (pesanan) {
                        pesananContent += `
                <a href="/getDokumen/${pesanan}" target="_blank"
                   class="btn btn-sm btn-primary d-block mb-2">
                    <i class="fa fa-file-pdf"></i> Surat Pesanan
                </a>`;
                    }
                }

                // Bukti Pengembalian
                const buktiList = $btn.data('bukti');
                if (Array.isArray(buktiList) && buktiList.length > 0) {
                    buktiList.forEach((bukti, idx) => {
                        pesananContent += `
                <a href="/getDokumen/${bukti.file_url}" target="_blank"
                   class="btn btn-sm btn-dark d-block mb-2">
                    <i class="fa fa-clipboard-check"></i> ${bukti.nama_file || 'Bukti ' + (idx + 1)}
                </a>`;
                    });
                }

                if (pesananContent) {
                    content += `<strong>Pesanan</strong><hr class="my-1">${pesananContent}`;
                }

                // =======================
                // 2. Section: PENGAJUAN PEMBAYARAN
                // =======================
                let pengajuanContent = '';
                const pengajuan = $btn.data('pengajuan_pembayaran');
                if (pengajuan) {
                    pengajuanContent += `
            <a href="/getDokumen/${pengajuan}" target="_blank"
               class="btn btn-sm btn-success d-block mb-2">
                <i class="fa fa-file-pdf"></i> Pengajuan Pembayaran
            </a>`;
                }

                const lampiranList = $btn.data('lampiran');
                if (Array.isArray(lampiranList) && lampiranList.length > 0) {
                    lampiranList.forEach((lampiran, idx) => {
                        pengajuanContent += `
                <a href="/getDokumen/${lampiran.file_url}" target="_blank"
                   class="btn btn-sm btn-secondary d-block mb-2">
                    <i class="fa fa-file"></i> ${lampiran.nama_file || 'Lampiran ' + (idx + 1)}
                </a>`;
                    });
                }

                if (pengajuanContent) {
                    content += `<strong>Pengajuan Pembayaran</strong><hr class="my-1">${pengajuanContent}`;
                }

                // =======================
                // 3. Section: BAST - BAP
                // =======================
                let bastBapContent = '';
                const bast = $btn.data('bast');
                const bap = $btn.data('bap');

                if (bast) {
                    bastBapContent += `
            <a href="/getDokumen/${bast}" target="_blank"
               class="btn btn-sm btn-success d-block mb-2">
                <i class="fa fa-file-pdf"></i> BAST
            </a>`;
                }

                if (bap) {
                    bastBapContent += `
            <a href="/getDokumen/${bap}" target="_blank"
               class="btn btn-sm btn-warning d-block mb-2">
                <i class="fa fa-file-pdf"></i> BAP
            </a>`;
                }

                if (bastBapContent) {
                    content += `<strong>BAST - BAP</strong><hr class="my-1">${bastBapContent}`;
                }

                // =======================
                // 4. Section: Alasan Penolakan (jika ada)
                // =======================
                const keterangan = $btn.data('keterangan');
                if (keterangan) {
                    content += `
            <div class="alert alert-danger mt-2 small">
                <i class="fa fa-info-circle"></i> Alasan penolakan:<br>
                <em>${keterangan}</em>
            </div>`;
                }

                // =======================
                // POPPER SETUP
                // =======================
                const offset = $btn.offset();
                const $popover = $(`
        <div class="popover bs-popover-start show" style="position: absolute; z-index: 1050;">
            <div class="popover-arrow" style="top: 10px;"></div>
            <div class="popover-body">${content}</div>
        </div>
    `).appendTo('body');
                const popoverWidth = $popover.outerWidth();
                $popover.css({
                    top: offset.top,
                    left: offset.left - popoverWidth - 10 // 10px gap ke kiri
                });

                // Tutup jika klik di luar
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.lihat-file-btn, .popover').length) {
                        $('.popover').remove();
                    }
                });
            });
        });
    </script>
@endsection
