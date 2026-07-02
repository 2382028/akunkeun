<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    @if ($kopSurat && $kopSurat->url_kop)
        <div style="text-align: center;">
            <img src="{{ storage_path('app/public/' . $kopSurat->url_kop) }}" alt="Kop Surat"
                style="width: 100%; object-fit: contain;">
        </div>
    @endif

    <h4 style="text-align: center;">
        Rekapitulasi Penerimaan Sewa Mess dari {{ $bulanAwal }} sampai dengan {{ $bulanAkhir }} tahun {{ $tahun }}
    </h4>

    <table>
        <thead class="text-center">
            <tr>
                <th>No</th>
                <th>Bulan</th>
                <th>Kategori Kamar</th>
                <th>Tarif per malam</th>
                <th>Jumlah Tersewa (malam)</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekap as $i => $row)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::createFromFormat('Y-m', $row['bulan'])->translatedFormat('F Y') }}</td>
                    <td>{{ $row['nama_kategori'] }}</td>
                    <td>Rp {{ number_format($row['tarif'], 0, ',', '.') }}</td>
                    <td class="text-center">{{ $row['total_malam'] }}</td>
                    <td>Rp {{ number_format($row['jumlah'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="5" style="text-align: right;">Total</th>
                <th>Rp {{ number_format(collect($rekap)->sum('jumlah'), 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>

    <p style="margin-top: 30px;">
        Penerimaan Sewa Mess dari {{ $bulanAwal }} sampai dengan {{ $bulanAkhir }} tahun {{ $tahun }} adalah sebagai berikut:
    </p>
    <ol>
        @foreach ($rekap as $row)
            <li>
                Kategori {{ $row['nama_kategori'] }} dengan tarif Rp {{ number_format($row['tarif'], 0, ',', '.') }}
                disewa selama {{ $row['total_malam'] }} malam
                dengan jumlah Rp {{ number_format($row['jumlah'], 0, ',', '.') }}
            </li>
        @endforeach
    </ol>
    <p>Total: Rp {{ number_format(collect($rekap)->sum('jumlah'), 0, ',', '.') }}</p>
</body>
</html>
