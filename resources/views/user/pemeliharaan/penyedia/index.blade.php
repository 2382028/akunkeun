@extends('user.pemeliharaan.penyedia.sidebar')
@section('contain')

    <section>
        <div class="container-fluid px-4 py-4">
            <div class="row">
                <div class="col-md-12">
                    <h4>
                        <span class="fw-bold">Pemeliharaan</span>
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
                        <div class="page wrapper" style="position: relative;">
                            <div class="btn-group">
                                @php
                                    $jumlahPengajuan = $riwayat
                                        ->whereIn('id_ref_status_pemeliharaan', [6, 8, 9, 10])
                                        ->unique('nomor_surat_pesanan')
                                        ->count();
                                    $jumlahMonitor = $riwayat
                                        ->where('id_ref_status_pemeliharaan', '>', 10)
                                        ->unique('nomor_surat_pesanan')
                                        ->count();
                                    $jumlahPembayaran = $riwayat
                                        ->filter(
                                            fn($item) => $item->id_ref_status_pemeliharaan > 15 &&
                                                optional($item->pesanan->pembayaranPemeliharaan)
                                                    ->url_pengajuan_pembayaran,
                                        )
                                        ->groupBy(
                                            fn($item) => $item->pesanan->pembayaranPemeliharaan
                                                ->url_pengajuan_pembayaran .
                                                '-' .
                                                $item->id_ref_status_pemeliharaan,
                                        )
                                        ->filter(fn($group, $key) => !empty($key))
                                        ->count();
                                @endphp

                                <a href="{{ url('/penyedia?tab=pengajuan') }}"
                                    class="page-wrap btn btn-sm {{ request()->get('tab') == 'pengajuan' ? 'btn-dark fw-bold' : 'btn-primary' }}">
                                    Pengajuan Baru{{ $jumlahPengajuan > 0 ? " [$jumlahPengajuan]" : '' }}
                                </a>

                                <a href="{{ url('/penyedia?tab=monitor') }}"
                                    class="page-wrap btn btn-sm {{ request()->get('tab') == 'monitor' ? 'btn-dark fw-bold' : 'btn-primary' }}">
                                    Monitor Pesanan{{ $jumlahMonitor > 0 ? " [$jumlahMonitor]" : '' }}
                                </a>

                                <a href="{{ url('/penyedia?tab=pembayaran') }}"
                                    class="page-wrap btn btn-sm {{ request()->get('tab') == 'pembayaran' ? 'btn-dark fw-bold' : 'btn-primary' }}">
                                    Pengajuan Pembayaran{{ $jumlahPembayaran > 0 ? " [$jumlahPembayaran]" : '' }}
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
                    $pengajuanBaru = $riwayat
                        ->whereIn('id_ref_status_pemeliharaan', [6, 8, 9, 10])
                        ->unique('nomor_surat_pesanan');
                @endphp

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-body content">
                                <div class="table-responsive">
                                    <table id="example" class="table table-bordered data-table" style="width: 100%">
                                        <thead>
                                            <tr class="small text-center">
                                                <th class="th-sm">No.</th>
                                                <th class="th-lg">Perihal</th>
                                                <th class="th-sm">Status</th>
                                                <th class="th-sm">Tanggal Diajukan</th>
                                                <th class="th-sm">Terakhir Diperbarui</th>
                                                <th class="th-sm">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($pengajuanBaru->count() > 0)
                                                @foreach ($pengajuanBaru as $i => $item)
                                                    @php
                                                        $status = $item->id_ref_status_pemeliharaan;
                                                        $badgeClass = match (true) {
                                                            $status === 1 => 'bg-primary text-white', // PP - Biru
                                                            in_array($status, [3, 8, 10, 13, 14])
                                                                => 'bg-warning text-dark', // PPG - Oranye
                                                            in_array($status, [4, 16])
                                                                => 'bg-success text-white', // PPK - Hijau
                                                            in_array($status, [6, 9, 12, 15, 18])
                                                                => 'bg-purple text-white', // Penyedia - Ungu
                                                            $status === 19 => 'bg-teal text-white', // Bendahara - Teal
                                                            $status === 21
                                                                => 'bg-info text-dark', // Bendahara - Abu-abu
                                                            default => 'bg-danger text-white', // Lainnya - Merah
                                                        };
                                                    @endphp
                                                    <tr>
                                                        <td class='text-center'>{{ $loop->iteration }}</td>
                                                        <td>
                                                            {{ explode('-', $item->pesanan->perihal, 2)[1] ?? $item->pesanan->perihal }}
                                                        </td>
                                                        <td class="text-center"
                                                            data-order="{{ $item->id_ref_status_pemeliharaan }}">
                                                            <span class="badge {{ $badgeClass }}">
                                                                {{ $item->status->deskripsi_status ?? '-' }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            {{ \Carbon\Carbon::parse($item->pesanan->created_at)->locale('id')->translatedFormat('d F Y') }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ \Carbon\Carbon::parse($item->updated_at)->locale('id')->translatedFormat('d F Y') }}
                                                        </td>
                                                        <td class="text-center">
                                                            <div
                                                                class="d-flex justify-content-center align-items-center gap-1">
                                                                @include(
                                                                    'admin.pemeliharaan.partials._lihat_file_btn',
                                                                    ['item' => $item]
                                                                )

                                                                @if ($item->id_ref_status_pemeliharaan === 6)
                                                                    <button class="btn btn-success setujui-pesanan"
                                                                        data-pesanan="{{ $item->nomor_surat_pesanan }}"><i
                                                                            class="fa fa-check"></i> Terima</button>
                                                                    <button class="btn btn-danger tolak-pesanan"
                                                                        data-pesanan="{{ $item->nomor_surat_pesanan }}"><i
                                                                            class="fa fa-times"></i> Tolak</button>
                                                                @endif
                                                                @if ($item->id_ref_status_pemeliharaan === 9)
                                                                    <button
                                                                        class="btn btn-warning btn-sm tawarkan-harga-btn"
                                                                        data-pesanan="{{ $item->nomor_surat_pesanan }}">
                                                                        Penawaran Biaya
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="10" class="text-center">Tidak ada data yang tersedia dalam tabel</td>
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
            @php
                $dipelihara = $riwayat->where('id_ref_status_pemeliharaan', '>', 10)->unique('nomor_surat_pesanan');
                // Membuat array grouping nomor_surat_pesanan dengan semua bmn_type-nya
                $groupedBmnType = $riwayat->groupBy('nomor_surat_pesanan')->map(function ($group) {
                    return $group->pluck('bmn_type')->unique()->values();
                });
            @endphp

            @if ($activeTab === 'monitor')
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-body content">
                                <div class="d-flex gap-2 mb-3">
                                    <button class="btn btn-primary btn-sm" id="ajukan" disabled>
                                        <i class="fa fa-paper-plane"></i> Ajukan Pembayaran
                                    </button>
                                </div>

                                <div class="table-responsive">
                                    <table id="example2" class="table table-bordered data-table" style="width: 100%">
                                        <thead>
                                            <tr class="small text-center">
                                                <th class="th-sm">No.</th>
                                                <th><input type="checkbox" id="checkAll">
                                                </th>
                                                <th class="th-lg">Perihal</th>
                                                <th class="th-md">Status</th>
                                                <th class="th-md">Tanggal Diajukan</th>
                                                <th class="th-md">Terakhir Diperbarui</th>
                                                <th class="th-sm">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($dipelihara->count() > 0)
                                                @foreach ($dipelihara as $item)
                                                    @php
                                                        $allRuangan =
                                                            isset($groupedBmnType[$item->nomor_surat_pesanan]) &&
                                                            $groupedBmnType[$item->nomor_surat_pesanan]->count() ===
                                                                1 &&
                                                            $groupedBmnType[$item->nomor_surat_pesanan][0] ===
                                                                'ruangan';
                                                    @endphp
                                                    <tr class="row-pemeliharaan" data-id="{{ $item->nomor_surat_pesanan }}"
                                                        data-status="{{ $item->id_ref_status_pemeliharaan }}">
                                                        <td class='text-center'>{{ $loop->iteration }}</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" class="check-item"
                                                                value="{{ $item->nomor_surat_pesanan }}"
                                                                data-status="{{ $item->id_ref_status_pemeliharaan }}">
                                                        </td>
                                                        <td>
                                                            {{ explode('-', $item->pesanan->perihal, 2)[1] ?? $item->pesanan->perihal }}
                                                        </td>

                                                        <td class="text-center"
                                                            data-order="{{ $item->id_ref_status_pemeliharaan }}">
                                                            @php
                                                                $status = $item->id_ref_status_pemeliharaan;
                                                                $badgeClass = match (true) {
                                                                    $status === 1
                                                                        => 'bg-primary text-white', // PP (Biru)
                                                                    in_array($status, [3, 8, 10, 13, 14])
                                                                        => 'bg-warning text-dark', // PPG (Oranye)
                                                                    in_array($status, [4, 16])
                                                                        => 'bg-success text-white', // PPK (Hijau)
                                                                    in_array($status, [6, 9, 12, 15, 18])
                                                                        => 'bg-purple text-white', // Penyedia (Ungu)
                                                                    $status === 19
                                                                        => 'bg-teal text-white', // Bendahara (Teal)
                                                                    $status === 21
                                                                        => 'bg-info text-dark', // Bendahara (Abu-abu)
                                                                    default
                                                                        => 'bg-danger text-white', // Selainnya (Merah)
                                                                };
                                                            @endphp
                                                            <span class="badge {{ $badgeClass }}">
                                                                {{ $item->status->deskripsi_status ?? '-' }}
                                                            </span>
                                                            @if (in_array($item->id_ref_status_pemeliharaan, [7, 11]))
                                                                @php
                                                                    $penolakanTerakhir = $item->pesanan
                                                                        ->penolakan()
                                                                        ->latest()
                                                                        ->first();
                                                                @endphp
                                                                @if ($penolakanTerakhir)
                                                                    <br>
                                                                    <small class="text-danger">
                                                                        Alasan: {{ $penolakanTerakhir->alasan_penolakan }}
                                                                    </small>
                                                                @endif
                                                            @endif

                                                        </td>
                                                        <td class="text-center">
                                                            {{ \Carbon\Carbon::parse($item->pesanan->created_at)->locale('id')->translatedFormat('d F Y') }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ \Carbon\Carbon::parse($item->updated_at)->locale('id')->translatedFormat('d F Y') }}
                                                        </td>
                                                        <td>
                                                            <div
                                                                class="d-flex justify-content-center align-items-center gap-1">
                                                                @include(
                                                                    'admin.pemeliharaan.partials._lihat_file_btn',
                                                                    [
                                                                        'pesananList' =>
                                                                            isset($item->pesanan) &&
                                                                            isset($item->pesanan->pesanan)
                                                                                ? collect([
                                                                                    $item->pesanan->pesanan,
                                                                                ])
                                                                                : collect(),
                                                                        'pengajuan' =>
                                                                            $item->pesanan->pembayaran->pengajuan_pembayaran ?? '',
                                                                        'item' => $item,
                                                                        'ids' => isset($item->id)
                                                                            ? collect([$item->id])
                                                                            : collect(),
                                                                        'buktiPengembalians' =>
                                                                            $item->pesanan->buktiPengembalians ??
                                                                            collect(),
                                                                    ]
                                                                )
                                                                @if ($item->id_ref_status_pemeliharaan == 12)
                                                                    <button class="btn btn-success btn-kembalikan"
                                                                        data-id="{{ $item->nomor_surat_pesanan }}">
                                                                        <i class="fa fa-reply" aria-hidden="true"></i>
                                                                        {{ $allRuangan ? 'Selesai' : 'Kembalikan' }}
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                        </tbody>
                                    @else
                                        <tr>
                                            <td colspan="10" class="text-center">Tidak ada data yang tersedia dalam tabel</td>
                                        </tr>
            @endif
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
                            optional($item->pesanan->pembayaranPemeliharaan)->url_pengajuan_pembayaran,
                    )
                    ->groupBy(
                        fn($item) => $item->pesanan->pembayaranPemeliharaan->url_pengajuan_pembayaran .
                            '-' .
                            $item->id_ref_status_pemeliharaan,
                    );
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
                                            <th class="th-sm">Tanggal Diajukan</th>
                                            <th class="th-sm">Terakhir Diperbarui</th>
                                            <th class="th-sm">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($pengajuanPembayaran->count() > 0)
                                            @foreach ($pengajuanPembayaran as $pengajuan => $items)
                                                @php
                                                    $perihals = $items
                                                        ->map(fn($i) => $i->pesanan->perihal)
                                                        ->unique()
                                                        ->values();
                                                    $firstItem = $items->first();
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

                                                    $buktiPengembalians = $items
                                                        ->pluck('pesanan')
                                                        ->filter()
                                                        ->flatMap(
                                                            fn($pesanan) => $pesanan->buktiPengembalians ?? collect(),
                                                        )
                                                        ->unique('nomor_surat_pesanan')
                                                        ->values();

                                                    $idPesananList = $items
                                                        ->map(fn($i) => $i->nomor_surat_pesanan)
                                                        ->unique()
                                                        ->values()
                                                        ->all();
                                                    $status = $firstItem->id_ref_status_pemeliharaan;
                                                    $badgeClass = match (true) {
                                                        $status === 1 => 'bg-primary text-white',
                                                        in_array($status, [3, 8, 10, 13, 14]) => 'bg-warning text-dark',
                                                        in_array($status, [4, 16]) => 'bg-success text-white',
                                                        in_array($status, [6, 9, 12, 15, 18]) => 'bg-purple text-white',
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
                                                    <td>
                                                        {!! $perihals->map(fn($perihal) => explode('-', $perihal, 2)[1] ?? $perihal)->implode('<br>') !!}
                                                    </td>

                                                    <td class="text-center align-middle">
                                                        <span class="badge {{ $badgeClass }}">
                                                            {{ $firstItem->status->deskripsi_status ?? '-' }}
                                                        </span>
                                                        @if ($firstItem->id_ref_status_pemeliharaan == 21)
                                                            <br>
                                                            Nomor Surat Perintah Membayar:
                                                            {{ $firstItem->pesanan->pembayaranPemeliharaan?->nomor_perintah_bayar }}
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
                                                                Alasan: {{ $penolakanTerakhir->alasan_penolakan ?? '-' }}
                                                            </small>
                                                        @endif

                                                    </td>
                                                    <td class="text-center align-middle">
                                                        {{ \Carbon\Carbon::parse($firstItem->pesanan->pembayaranPemeliharaan->created_at)->locale('id')->translatedFormat('d F Y') }}
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        {{ \Carbon\Carbon::parse($firstItem->pesanan->pembayaranPemeliharaan->updated_at)->locale('id')->translatedFormat('d F Y') }}
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        @include(
                                                            'admin.pemeliharaan.partials._lihat_file_btn',
                                                            [
                                                                'pengajuan' =>
                                                                    $firstItem->pesanan->pembayaranPemeliharaan->url_pengajuan_pembayaran ?? '',
                                                                'item' => $firstItem,
                                                                'ids' => collect($items)->pluck('id'),
                                                                'lampiranList' =>
                                                                    $firstItem->pesanan->pembayaranPemeliharaan->lampiran ?? collect(),
                                                                'buktiPengembalians' => $buktiPengembalians,
                                                                'urlBast' => $urlBast ?? null,
                                                                'urlBap' => $urlBap ?? null,
                                                                'pesananList' => $pesananList,
                                                            ]
                                                        )

                                                        @if ($firstItem->id_ref_status_pemeliharaan == 18)
                                                            <button class="btn btn-primary ttd-btn"
                                                                data-pesanan='@json($idPesananList)'>
                                                                <i class="fas fa-pen-nib me-1"></i> Tanda Tangan
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="10" class="text-center">Tidak ada data yang tersedia dalam tabel</td>
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
        <!-- Modal Alasan Tolak -->
        <div class="modal fade" id="alasanModal" tabindex="-1" aria-labelledby="alasanModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="alasanModalLabel">Isi Alasan Penolakan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <form id="formTolakPesanan">
                        <div class="modal-body">
                            <textarea class="form-control" name="alasan" id="alasanTolakInput" placeholder="Tuliskan alasan..." required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fileModalLabel">Dokumen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body" id="fileModalBody">
                        <!-- Tombol file dinamis akan diisi di sini -->
                    </div>
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

    </section>
    <script>
        $(document).ready(function() {

            $.fn.dataTable.ext.errMode = 'none';
            if ($('#example2 tbody tr').length > 0 && $('#example2 tbody tr').not(':has(td[colspan])').length > 0) {
                // Inisialisasi DataTable
                $('#example2').DataTable({
                    order: [
                        [3, 'asc']
                    ] // urut berdasarkan kolom Status (index 2)
                });
            }
            if ($('#example tbody tr').length > 0 && $('#example tbody tr').not(':has(td[colspan])').length > 0) {
                // Inisialisasi DataTable
                $('#example').DataTable({
                    order: [
                        [2, 'asc']
                    ] // urut berdasarkan kolom Status (index 2)
                });
            }
            if ($('#example3 tbody tr').length > 0 && $('#example3 tbody tr').not(':has(td[colspan])').length > 0) {
                // Inisialisasi DataTable
                $('#example3').DataTable({
                    order: [
                        [2, 'asc']
                    ] // urut berdasarkan kolom Status (index 2)
                });
            }
            document.querySelectorAll('.ttd-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const pesanan = this.dataset.pesanan; // json array string
                    const pesananIds = JSON.parse(pesanan).join(',');
                    window.location.href = `penyedia/ttd-bap?pesanan=${pesananIds}`;
                });
            });
            let pesananId = null;

            $('.tolak-pesanan').click(function() {
                pesananId = $(this).data('pesanan');
                $('#alasanModal').modal('show');
            });

            $('#formTolakPesanan').submit(function(e) {
                e.preventDefault();

                const alasan = $('#alasanTolakInput').val().trim();

                fetch(`/penyedia/tolak-pesanan/${encodeURIComponent(pesananId)}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            alasan
                        }),
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.message);
                        location.reload();
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Terjadi kesalahan saat memproses.');
                    });
            });
            $(document).on('click', '.tawarkan-harga-btn', function() {
                const idPesanan = $(this).data('pesanan');
                $('#inputIdPesanan').val(idPesanan);

                $.get(`/kelompok-barang/${encodeURIComponent(idPesanan)}`, function(data) {
                    let html = `<table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center align-middle">Nama BMN</th>
                        <th class="text-center align-middle">Jumlah</th>
                        <th class="text-center align-middle">Penawaran (Penyedia)</th>
                        <th class="text-center align-middle">Penawaran (LLDIKTI IV)</th>
                    </tr>
                </thead><tbody>`;

                    let semuaAdaPP = true;

                    data.forEach(item => {
                        const nilaiPenyedia = item.nilai_nego_penyedia ? formatRupiah(item
                            .nilai_nego_penyedia.toString()) : '';
                        const nilaiPP = item.nilai_nego_pp ? formatRupiah(item.nilai_nego_pp
                            .toString()) : '';

                        if (!item.nilai_nego_pp) semuaAdaPP = false;

                        html += `<tr class="row-data">
                            <td>${item.nama_bmn}</td>
                            <td class="text-center align-middle">${item.jumlah_bmn}</td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" name="nilai_nego[${item.id_kelompok_barang_pesanan}]" class="form-control format-rupiah" value="${nilaiPenyedia}" required>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" value="${nilaiPP}" readonly>
                                </div>
                            </td>
                        </tr>`;
                    });

                    html += `<tr>
                <td colspan="2" class="text-end fw-bold">Total</td>
                <td><div class="input-group"><span class="input-group-text">Rp</span><input type="text" id="totalPenyedia" class="form-control" readonly></div></td>
                <td><div class="input-group"><span class="input-group-text">Rp</span><input type="text" id="totalPP" class="form-control" readonly></div></td>
            </tr></tbody></table>`;

                    html += `<div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-primary">Kirim Penawaran</button>
                ${semuaAdaPP ? '<button type="button" class="btn btn-success" id="btnSetujuiPenawaran">Setujui Penawaran LLDIKTI IV</button>' : ''}
            </div>`;

                    $('#tawarHargaBody').html(html);
                    hitungTotal();
                    $('#tawarHargaModal').modal('show');
                });
            });

            function formatRupiah(angka) {
                return angka.replace(/[^\d]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function parseRupiah(str) {
                return parseInt((str || '').replace(/\./g, '')) || 0;
            }

            $(document).on('input', '.format-rupiah', function() {
                const raw = $(this).val().replace(/\./g, '');
                $(this).val(formatRupiah(raw));
                hitungTotal();
            });

            function hitungTotal() {
                let totalPenyedia = 0;
                let totalPP = 0;

                $('#tawarHargaBody table tbody tr.row-data').each(function() {
                    const penyediaVal = $(this).find('td:eq(2) input').val();
                    const ppVal = $(this).find('td:eq(3) input').val();

                    totalPenyedia += parseRupiah(penyediaVal);
                    totalPP += parseRupiah(ppVal);
                });

                $('#totalPenyedia').val(formatRupiah(totalPenyedia.toString()));
                $('#totalPP').val(formatRupiah(totalPP.toString()));
            }


            // Submit Kirim Penawaran
            $('#formTawarHarga').on('submit', function(e) {
                e.preventDefault();
                const idPesanan = $('#inputIdPesanan').val();
                const data = {};
                let adaPerubahan = false;

                $('[name^="nilai_nego"]').each(function() {
                    const name = $(this).attr('name'); // contoh: "nilai_nego[1/LL4/PM/2025]"
                    const id = name.match(/\[(.*)\]/)[1]; // ambil isi dalam []

                    const nilaiInput = parseRupiah($(this).val());

                    // Cari nilai PP dari kolom readonly di baris yang sama
                    const nilaiPP = parseRupiah($(this).closest('tr').find('td:eq(3) input').val());

                    if (nilaiInput !== nilaiPP) {
                        adaPerubahan = true;
                    }

                    if (nilaiInput > 0) data[id] = nilaiInput;
                });


                if (!adaPerubahan) {
                    alert('Ubah setidaknya 1 nilai untuk mengirim penawaran');
                    return;
                }

                $.ajax({
                    url: '/penyedia/tawarkan-harga',
                    method: 'POST',
                    data: {
                        nomor_surat_pesanan: idPesanan,
                        nilai_nego: data,
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


            // Submit Setujui Penawaran
            $(document).on('click', '#btnSetujuiPenawaran', function() {
                const idPesanan = $('#inputIdPesanan').val();
                $.post('/penyedia/setujui-penawaran', {
                    nomor_surat_pesanan: idPesanan,
                    _token: '{{ csrf_token() }}'
                }, res => {
                    alert(res.message);
                    $('#tawarHargaModal').modal('hide');
                    location.reload();
                }).fail(() => alert('Gagal menyetujui penawaran.'));
            });

            $(document).on('click', '.setujui-pesanan', function() {
                const idPesanan = $(this).data('pesanan');
                if (!confirm('Terima pesanan ini?')) return;

                $.ajax({
                    url: '/penyedia/terima-pesanan/' + encodeURIComponent(idPesanan),
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        alert('Pesanan berhasil disetujui!');
                        location.reload();
                    },
                    error: function() {
                        alert('Gagal menyetujui pesanan.');
                    }
                });
            });
            // Popover lihat file
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



            // Tutup popover saat klik di luar
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.lihat-file-btn, .popover').length) {
                    $('.popover').remove();
                }
            });

            $(document).on('click', '.btn-terima', function() {
                const id = $(this).data('id');
                $.post(`/penyedia/terima/${id}`, {
                    _token: '{{ csrf_token() }}'
                }, function() {
                    alert('Pesanan diterima.');
                    $('#fileModal').modal('hide');
                    location.reload();
                }).fail(function(xhr) {
                    alert('Gagal mengirim terima. Status: ' + xhr.status);
                });
            });
            $(document).on('change', '.check-item', function() {
                updateAjukanButtonState();
            });
            // Fungsi untuk update status tombol
            function updateAjukanButtonState() {
                const checkedCheckboxes = $('.check-item:checked').toArray();

                if (checkedCheckboxes.length === 0) {
                    // Tidak ada yang dicentang → disable
                    $('#ajukan').prop('disabled', true);
                    return;
                }

                // Cek apakah semua status termasuk [15, 17, 20]
                const allAllowed = checkedCheckboxes.every(checkbox => {
                    const status = parseInt($(checkbox).data('status'));
                    return [15, 17, 20].includes(status);
                });

                $('#ajukan').prop('disabled', !allAllowed);
            }

            // Jalankan saat halaman load
            updateAjukanButtonState();

            // Saat checkAll diklik
            $('#checkAll').on('change', function() {
                $('.check-item').prop('checked', this.checked);
                updateAjukanButtonState();
            });

            // Tombol Ajukan ditekan
            $('#ajukan').on('click', function() {
                const selectedIds = $('.row-pemeliharaan').toArray().map(row => {
                    const status = parseInt($(row).data('status'));
                    const id = $(row).data('id');
                    return [15, 17, 20].includes(status) ? id : null;
                }).filter(Boolean);

                if (selectedIds.length === 0) {
                    alert('Tidak ada pemeliharaan yang siap diajukan.');
                    return;
                }

                const query = selectedIds.map(id => `nomor_surat_pesanan[]=${id}`).join('&');
                window.location.href = `/penyedia/pengajuan-pembayaran/form?${query}`;
            });



            $(document).on('click', '.btn-kembalikan', function() {
                const id = $(this).data('id');
                if (!confirm('Apakah anda yakin ingin mengembalikan pemeliharaan ini?')) return;

                $.ajax({
                    url: '/penyedia/pengembalian-pemeliharaan/' + encodeURIComponent(id),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        alert(res.message || 'Berhasil dikembalikan.');
                        location.reload();
                    },
                    error: function() {
                        alert('Gagal mengembalikan pemeliharaan.');
                    }
                });
            });
            updateAjukanButtonState();
        });
    </script>
@endsection
