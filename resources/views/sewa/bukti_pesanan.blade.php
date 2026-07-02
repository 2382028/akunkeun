<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bukti Pemesanan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        .header { display: flex; justify-content: space-between; align-items: center; }
        .title { text-align: center; font-size: 18px; font-weight: bold; margin-top: 20px; }
        .info { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <img src="{{ public_path('assets/images/LLDIKTI4 final1.png') }}" alt="Logo" style="width: 100px;">
        </div>
        <div style="text-align: right;">
            <strong>Gedung Diklat LLDIKTI IV</strong><br>
            Jl. Raya Bandung<br>
            Palimanan km 20,5 <br>
            Cikeruh, Jatinangor<br>
            Sumedang, 45363<br>
        </div>
    </div>

    <div class="title">BUKTI PEMESANAN</div>

    <div class="info">
        <table style="border-collapse: collapse; width: 100%;">
            <tr>
                <td><strong>Kode Pemesanan</strong></td>
                <td>{{ $pemesanan->kode_pemesanan }}</td>
                <td><strong>No Bukti</strong></td>
                <td>{{ pathinfo($namaBukti, PATHINFO_FILENAME) }}</td>
            </tr>
            <tr>
                <td><strong>Nama</strong></td>
                <td>{{ $pemesanan->penyewa->nama_lengkap }}</td>
                <td><strong>Tanggal Masuk</strong></td>
                <td>{{ \Carbon\Carbon::parse($pemesanan->tanggal_checkin)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td><strong>Hari/Tanggal</strong></td>
                <td>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</td>
                <td><strong>Tanggal Keluar</strong></td>
                <td>{{ \Carbon\Carbon::parse($pemesanan->tanggal_checkout)->format('d-m-Y') }}</td>
            </tr>
        </table>
    </div>

    <div class="info">
        <p>Berikut adalah rincian pemesanan Anda:</p>
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
            @foreach($groupedRooms as $roomType => $items)
                @php $first = true; @endphp
                @foreach($items as $item)
                    @php $grandTotal += $item['subtotal']; @endphp
                    <tr>
                        <td>
                            @if($first)
                                {{ $roomType }}
                                @php $first = false; @endphp
                            @endif
                        </td>
                        <td>{{ $item['lantai'] }}</td>
                        <td>{{ $item['nomor_kamar'] }}</td>      
                        <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>                    
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

    <div class="info">
        <p><strong>TUNJUKAN BUKTI PEMESANAN INI KE PETUGAS DI LOKASI</strong></p>
        <p>Terima kasih telah melakukan pemesanan di Gedung Diklat LLDIKTI IV.</p>
    </div>
</body>
</html>
