<!DOCTYPE html>
<html>

<head>
    <title>Rekap Pemeliharaan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    @if ($kop && $kop->url_kop)
        <div style="text-align: center;">
            <img src="{{ storage_path('app/public/' . $kop->url_kop) }}" alt="Kop Surat"
                style="width: 100%; object-fit: contain;">
        </div>
    @endif
    <h3 style="text-align: center;">Rekapitulasi Pemeliharaan
        {{ \Carbon\Carbon::parse($mulai)->translatedFormat('d F Y') }} -
        {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') }}</h3>

    <table>
        <thead class="text-center">
            <tr>
                <th style="width:5%;">No.</th>
                <th style="width:10%;">Nomor Surat Pesanan</th>
                <th style="width:15%;">Perihal</th>
                <th style="width:10%;">Penyedia</th>
                <th style="width:10%;">Tanggal Diajukan</th>
                <th style="width:10%;">Tanggal Selesai</th>
                <th style="width:15%;">BMN</th>
                <th style="width:15%;">Total Nilai Pekerjaan</th>
                <th style="width:5%;">PPN (%)</th>
                <th style="width:15%;">Total + PPN</th>
            </tr>
        </thead>

        <tbody>
            @php
                $no = 1;
                $totalKeseluruhanSetelahPpn = 0;
            @endphp

            @forelse($rekap->groupBy('nomor_surat_pesanan') as $idPesanan => $groupedItems)
                @php
                    $firstItem = $groupedItems->first();
                    $pesanan = $firstItem->pesanan;
                    $kelompokBarang = \App\Models\KelompokBarangPesanan::where(
                        'nomor_surat_pesanan',
                        $idPesanan,
                    )->get();

                    $totalNilai = $kelompokBarang->sum(fn($barang) => $barang->nilai_disepakati ?? 0);
                    $nilaiPpn = $firstItem->pesanan->pembayaranPemeliharaan?->nilai_ppn ?? 0;
                    $totalSetelahPpn = $totalNilai + ($totalNilai * $nilaiPpn) / 100;

                    $totalKeseluruhanSetelahPpn += $totalSetelahPpn;
                @endphp

                @if ($kelompokBarang->count() > 0)
                    @foreach ($kelompokBarang as $index => $barang)
                        <tr>
                            @if ($index === 0)
                                <td rowspan="{{ $kelompokBarang->count() }}">{{ $no++ }}</td>
                                <td rowspan="{{ $kelompokBarang->count() }}">{{ $pesanan->nomor_surat ?? '-' }}</td>
                                <td rowspan="{{ $kelompokBarang->count() }}">
                                    {{ explode('-', $pesanan->perihal, 2)[1] ?? ($pesanan->perihal ?? '-') }}
                                </td>

                                <td rowspan="{{ $kelompokBarang->count() }}">{{ $firstItem->penyedia->nama_CV ?? '-' }}
                                </td>
                                <td rowspan="{{ $kelompokBarang->count() }}">
                                    {{ $firstItem->created_at->translatedFormat('d F Y') }}</td>
                                <td rowspan="{{ $kelompokBarang->count() }}">
                                    {{ $firstItem->updated_at->translatedFormat('d F Y') }}</td>
                            @endif

                            <td>
                                @php
                                    $pemeliharaan = \App\Models\Pemeliharaan::where(
                                        'nomor_surat_pesanan',
                                        $barang->nomor_surat_pesanan,
                                    )->first();
                                    $bmnType = $pemeliharaan?->bmn_type ?? '';
                                @endphp
                                @if ($bmnType === 'inventaris')
                                    {{ ($barang->jumlah_bmn ?? 0) . ' ' . $barang->nama_bmn }}
                                @else
                                    {{ $barang->nama_bmn ?? '-' }}
                                @endif
                            </td>

                            @if ($index === 0)
                                <td class="text-end fw-bold" rowspan="{{ $kelompokBarang->count() }}">
                                    Rp {{ number_format($totalNilai, 0, ',', '.') }}
                                </td>
                                <td class="text-center" rowspan="{{ $kelompokBarang->count() }}">
                                    {{ $nilaiPpn }}%
                                </td>
                                <td class="text-end fw-bold" rowspan="{{ $kelompokBarang->count() }}">
                                    Rp {{ number_format($totalSetelahPpn, 0, ',', '.') }}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $pesanan->nomor_surat ?? '-' }}</td>
                        <td>{{ explode('-', $pesanan->perihal, 2)[1] ?? ($pesanan->perihal ?? '-') }}
                        <td>{{ $firstItem->penyedia->nama_CV ?? '-' }}</td>
                        <td>{{ $firstItem->created_at->translatedFormat('d F Y') }}</td>
                        <td>{{ $firstItem->updated_at->translatedFormat('d F Y') }}</td>
                        <td colspan="2">Tidak ada BMN</td>
                        <td class="text-center">{{ $nilaiPpn }}%</td>
                        <td>-</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="10">Tidak ada data</td>
                </tr>
            @endforelse

            @if ($rekap->count() > 0)
                <tr>
                    <td colspan="9" class="text-end fw-bold">TOTAL</td>
                    <td class="text-end fw-bold">Rp {{ number_format($totalKeseluruhanSetelahPpn, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>

</html>
