<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cedarville+Cursive&display=swap" rel="stylesheet">

    <style>
        .tanda-tangan {
            font-family: "Cedarville Cursive", cursive;
            font-weight: 400;
            font-style: normal;
            font-size: 32px;
        }

        .ttd-wrapper {
            width: 250px;
            text-align: right;
            margin-top: 50px;
            margin-left: auto;
            margin-right: 0;
        }

        .ttd-wrapper p {
            margin: 2px 0;
        }

        .merged-cell {
            text-align: center;
            vertical-align: middle;
        }

        body {
            font-family: Arial, sans-serif;
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
            padding: 6px;
        }

        th {
            text-align: center;
        }

        td {
            text-align: left;
        }


        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        .info {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    @if ($kopSurat && $kopSurat->url_kop)
        <div style="text-align: center;">
            <img src="{{ storage_path('app/public/' . $kopSurat->url_kop) }}" alt="Kop Surat"
                style="width: 100%; max-height: 150px; object-fit: contain;">
        </div>
    @endif
    <div class="title">INVOICE PNBP</div>

    <div class="info">
        <table style="border-collapse: collapse; width: 100%;">
            <tr>
                <td><strong>Kode Pemesanan</strong></td>
                <td>{{ $pemesanan->kode_pemesanan }}</td>
                <td><strong>No Invoice</strong></td>
                <td>{{ pathinfo($namaInvoice, PATHINFO_FILENAME) }}</td>
            </tr>
            <tr>
                <td><strong>Nama</strong></td>
                <td>{{ $nama_penyewa }}</td>
                <td><strong>Tanggal Masuk</strong></td>
                <td>{{ \Carbon\Carbon::parse($pemesanan->tanggal_checkin)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td><strong>Hari/Tanggal:</strong> </td>
                <td>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</td>
                <td><strong>Tanggal Keluar</strong></td>
                <td>{{ \Carbon\Carbon::parse($pemesanan->tanggal_checkout)->format('d-m-Y') }}</td>
            </tr>
        </table>
    </div>
    <div class="info">
        <p>Rincian Pesanan:</p>
    </div>
    @php
        $grandTotal = 0;
    @endphp

    <table>
        <thead>
            <tr>
                <th>Tipe</th>
                <th>Lantai</th>
                <th>Nomor Kamar</th>
                <th>Harga/malam</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach ($groupedRooms as $roomType => $items)
                @php
                    $rowspan = count($items);
                @endphp
                @foreach ($items as $index => $item)
                    @php $grandTotal += $item['subtotal']; @endphp
                    <tr>
                        @if ($index === 0)
                            <td class="merged-cell" rowspan="{{ $rowspan }}">{{ $roomType }}</td>
                        @endif
                        <td class="text-center">{{ $item['lantai'] }}</td>
                        <td class="text-center">{{ $item['nomor_kamar'] }}</td>
                        <td class="text-center">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                        <td class="text-center">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right"><strong>Total Harga:</strong></td>
                <td><strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
    <div class="title">LUNAS</div>

    <div class="ttd-wrapper">
        <p>Petugas,</p>
        <p class="tanda-tangan">{{ $tandaTangan }}</p>
        <p><strong>{{ $namaPetugas }}</strong></p>
        <p>NIP: {{ $nip }}</p>
    </div>
</body>

</html>
