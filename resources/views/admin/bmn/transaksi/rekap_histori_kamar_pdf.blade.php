<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Histori Kamar</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 5px; text-align: center; }
    </style>
</head>
<body>
    @if ($kopSurat && $kopSurat->url_kop)
        <div style="text-align: center;">
            <img src="{{ storage_path('app/public/' . $kopSurat->url_kop) }}" alt="Kop Surat"
                style="width: 100%; object-fit: contain;">
        </div>
    @endif

    <div style="text-align: center; margin-top: 10px;">
        <h4>Rekapitulasi Histori Penyewaan Kamar</h4>
        <p>Periode: {{ \Carbon\Carbon::parse($start)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($end)->translatedFormat('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Kamar</th>
                <th>Kategori</th>
                <th>Tarif per Malam</th>
                <th>Jumlah Tersewa</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kamars as $i => $kamar)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $kamar->nomor_kamar }}</td>
                <td>{{ $kamar->kategori->nama_kategori ?? '-' }}</td>
                <td>Rp {{ number_format($kamar->harga_per_malam, 0, ',', '.') }}</td>
                <td>{{ $kamar->jumlah_tersewa }}</td>
                <td>Rp {{ number_format($kamar->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <th colspan="5" style="text-align:right;">Total Keseluruhan</th>
                <th>Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>
            <div class="mt-4">
            <p>Rekap ini menampilkan histori penyewaan kamar dari tanggal {{ $start->translatedFormat('d F Y') }} sampai {{ $end->translatedFormat('d F Y') }}.</p>
            <p>Total penerimaan berdasarkan histori penyewaan kamar: Rp {{ number_format($grandTotal, 0, ',', '.') }}</p>
        </div>
</body>
</html>
