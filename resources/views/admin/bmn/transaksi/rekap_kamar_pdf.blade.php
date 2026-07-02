<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Data Kamar</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th,
        td {
            border: 1px solid #000;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        ul {
            padding-left: 1.2rem;
            text-align: left;
        }
    </style>
</head>

<body>
    @if ($kopSurat && $kopSurat->url_kop)
        <div style="text-align: center;">
            <img src="{{ storage_path('app/public/' . $kopSurat->url_kop) }}" alt="Kop Surat"
                style="width: 100%; object-fit: contain;">
        </div>
    @endif

    <h3 style="text-align: center; margin-top: 1rem;">Rekapitulasi Data Kamar</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Kamar</th>
                <th>Lantai</th>
                <th>Kategori</th>
                <th>Tarif per Malam</th>
                <th>Fasilitas</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kamars as $index => $kamar)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $kamar->nomor_kamar }}</td>
                    <td>{{ $kamar->lantai }}</td>
                    <td>{{ $kamar->kategori->nama_kategori ?? '-' }}</td>
                    <td>Rp {{ number_format($kamar->harga_per_malam, 0, ',', '.') }}</td>
                    <td>
                        <ul>
                            @foreach ($kamar->fasilitas as $fasilitas)
                                <li>{{ $fasilitas->nama_fasilitas }} ({{ $fasilitas->pivot->jumlah }})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ ucfirst($kamar->status_kamar) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 0.5rem;">
        <p><strong>Ringkasan:</strong><br>
            Total kamar yang terdaftar: <strong>{{ $kamars->count() }}</strong><br>
            Jumlah kamar available: <strong>{{ $kamars->where('status_kamar', 'tersedia')->count() }}</strong><br>
            Jumlah kamar maintenance: <strong>{{ $kamars->where('status_kamar', 'terisi')->count() }}</strong><br>
        </p>
            <p>
        Data di atas memberikan gambaran kondisi terkini dari seluruh kamar mess yang tersedia, lengkap dengan kategori, fasilitas, serta status penggunaannya.
    </p>
    </div>
</body>

</html>
