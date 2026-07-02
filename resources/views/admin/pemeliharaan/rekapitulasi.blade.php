@extends('admin.templates.sidebar')

@section('contain')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
            <h4>
                Rekapitulasi Pemeliharaan {{ \Carbon\Carbon::parse($mulai)->translatedFormat('d F Y') }} sampai
                {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') }}
            </h4>
            <a href="{{ route('pemeliharaan.rekap.pdf', ['mulai' => $mulai, 'sampai' => $sampai]) }}"
                class="btn btn-danger btn-sm" target="_blank">
                <i class="bi bi-file-earmark-pdf"></i> Buat PDF
            </a>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table table-bordered small">
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
                                    $totalKeseluruhanSetelahPpn = 0; // untuk Total + PPN
                                @endphp

                                @forelse($rekap->groupBy('nomor_surat_pesanan') as $idPesanan => $groupedItems)
                                    @php
                                        $firstItem = $groupedItems->first();
                                        $pesanan = $firstItem->pesanan;
                                        $kelompokBarang = \App\Models\KelompokBarangPesanan::where(
                                            'nomor_surat_pesanan',
                                            $idPesanan,
                                        )->get();

                                        $totalNilai = $kelompokBarang->sum(
                                            fn($barang) => $barang->nilai_disepakati ?? 0,
                                        );
                                        $nilaiPpn = $firstItem->pesanan->pembayaranPemeliharaan?->nilai_ppn ?? 0;
                                        $totalSetelahPpn = $totalNilai + ($totalNilai * $nilaiPpn) / 100;

                                        $totalKeseluruhanSetelahPpn += $totalSetelahPpn;
                                    @endphp

                                    @if ($kelompokBarang->count() > 0)
                                        @foreach ($kelompokBarang as $index => $barang)
                                            <tr>
                                                @if ($index === 0)
                                                    <td class="text-center" rowspan="{{ $kelompokBarang->count() }}">
                                                        {{ $no++ }}</td>
                                                    <td class="text-center" rowspan="{{ $kelompokBarang->count() }}">
                                                        {{ $pesanan->nomor_surat ?? '-' }}</td>
                                                    <td rowspan="{{ $kelompokBarang->count() }}">
                                                        {{ explode('-', $pesanan->perihal, 2)[1] ?? ($pesanan->perihal ?? '-') }}
                                                    </td>
                                                    <td class="text-center" rowspan="{{ $kelompokBarang->count() }}">
                                                        {{ $firstItem->penyedia->nama_CV ?? '-' }}</td>
                                                    <td class="text-center" rowspan="{{ $kelompokBarang->count() }}">
                                                        {{ $firstItem->created_at->translatedFormat('d F Y') }}</td>
                                                    <td class="text-center" rowspan="{{ $kelompokBarang->count() }}">
                                                        {{ $firstItem->updated_at->translatedFormat('d F Y') }}</td>
                                                @endif
                                                <td>
                                                    @php
                                                        // ambil pemeliharaan terkait nomor surat ini
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
                                                        Rp {{ number_format($totalNilai, 0, ',', '.') }}</td>
                                                    <td class="text-center" rowspan="{{ $kelompokBarang->count() }}">
                                                        {{ $nilaiPpn }}%</td>
                                                    <td class="text-end fw-bold" rowspan="{{ $kelompokBarang->count() }}">
                                                        Rp {{ number_format($totalSetelahPpn, 0, ',', '.') }}</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td class="text-center">{{ $pesanan->nomor_surat ?? '-' }}</td>
                                            <td>{{ explode('-', $pesanan->perihal, 2)[1] ?? ($pesanan->perihal ?? '-') }}
                                            </td>
                                            <td class="text-center">{{ $firstItem->penyedia->nama_CV ?? '-' }}</td>
                                            <td class="text-center">{{ $firstItem->created_at->translatedFormat('d F Y') }}
                                            </td>
                                            <td class="text-center">{{ $firstItem->updated_at->translatedFormat('d F Y') }}
                                            </td>
                                            <td colspan="2" class="text-center">Tidak ada BMN</td>
                                            <td class="text-center">{{ $nilaiPpn }}%</td>
                                            <td class="text-end fw-bold">-</td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse

                                @if ($rekap->count() > 0)
                                    <tr>
                                        <td colspan="9" class="text-end fw-bold">TOTAL</td>
                                        <td class="text-end fw-bold">Rp
                                            {{ number_format($totalKeseluruhanSetelahPpn, 0, ',', '.') }}</td>
                                    </tr>
                                @endif

                            </tbody>


                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
