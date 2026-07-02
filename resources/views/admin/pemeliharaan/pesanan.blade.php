<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Pesanan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cedarville+Cursive&family=Edu+NSW+ACT+Cursive:wght@400..700&display=swap"
        rel="stylesheet">

    <style>
        .tanda-tangan {
            font-family: "Cedarville Cursive", cursive;
            font-weight: 400;
            font-style: normal;
            font-size: 32px;
        }

        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #000;
            background: #fff;
            line-height: 1.5;
            padding: 20px;
        }

        /* Perbaikan jarak antar baris untuk nomor & perihal */
        .header-info {
            line-height: 1.1;
            margin-bottom: 4px;
            margin-top: 0%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table th,
        table td {
            border: 1px solid black;
            padding: 6px;
            vertical-align: top;
            word-wrap: break-word;
        }

        table thead {
            background: #f0f0f0;
        }

        .kop {
            text-align: center;
            margin-bottom: 10px;
        }

        .kop img {
            max-height: 120px;
        }

        .footer-ttd {
            text-align: right;
            margin-top: 40px;
        }

        .footer-ttd p {
            margin: 0;
        }
    </style>
</head>
<body>
    {{-- Kop Surat --}}
    @if ($kop && $kop->url_kop)
    <div style="text-align: center;">
        <img src="{{ storage_path('app/public/' . $kop->url_kop) }}" alt="Kop Surat"
            style="width: 100%; object-fit: contain;">
    </div>
    @endif

    {{-- Header Surat --}}
    <table style="width: 100%; margin-bottom: 4px; border: none;">
        <tr style="border: none;">
            <td style="text-align: left; border: none; width: 50%; padding: 0%;">
                Nomor: {{ $nomorSurat }}
            </td>
            <td style="text-align: right; border: none; width: 50%; padding: 0%;">
                Bandung, {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
            </td>
        </tr>
    </table>
    <p class="header-info">Perihal: {{ $perihal }}</p>
    <p>Yth {{ $penyedia->nama_CV }}<br>{{ $penyedia->alamat }}</p>

    {{-- Isi Surat --}}
    <p style="text-align: justify;">{{ $opening }}</p>

    {{-- Tabel BMN --}}
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th style="width: 30%;">Nama Barang</th>
                <th style="width: 30%;">Merk</th>
                <th style="width: 10%;">Jumlah</th>
                <th style="width: 25%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bmnData as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $item['nama_bmn'] }}</td>
                    <td>{{ $item['merk_bmn'] }}</td>
                    <td style="text-align: center;">{{ $item['jumlah'] }}</td>
                    @if ($index === 0)
                        <td rowspan="{{ count($bmnData) }}">{{ $keterangan }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="text-align: justify; margin-top: 15px;">{{ $ending }}</p>

    {{-- Penutup --}}
    <div class="footer-ttd">
        <p>Pejabat Pengadaan</p>
        <p class="tanda-tangan">{{ explode(' ', $penanda->username)[0] }}</p>
        <p>{{ $penanda->username }}</p>
        <p>NIP: {{ $nipNik }}</p>
    </div>
</body>


</html>
