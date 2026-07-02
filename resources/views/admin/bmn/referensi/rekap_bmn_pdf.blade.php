<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekapitulasi BMN</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            font-size: 12px;
            padding: 4px;
        }
        h4 {
            margin-bottom: 4px;
        }
        .text-center {
            text-align: center;
        }
        .fw-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
            @if ($kop && $kop->url_kop)
        <div style="text-align: center;">
            <img src="{{ storage_path('app/public/' . $kop->url_kop) }}" alt="Kop Surat"
                style="width: 100%; max-height: 150px; object-fit: contain;">
        </div>
    @endif
<h2 class="text-center">
    Rekapitulasi Data BMN {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('j F Y') }}
</h2>
    <!-- 1. Jumlah Barang per Kategori -->
    <h4 class="text-center">Jumlah Barang per Kategori</h4>
    <table width="100%">
        <tr>
            <th class="text-center">No</th>
            <th>Kategori</th>
            <th class="text-center">Jumlah</th>
        </tr>
        @php $totalKategori = 0; @endphp
        @foreach($kategori as $key => $val)
            @php $totalKategori += $val; @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $key ?: 'Tidak memiliki keterangan kategori' }}</td>
                <td class="text-center">{{ $val }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2" class="text-center fw-bold">Total</td>
            <td class="text-center fw-bold">{{ $totalKategori }}</td>
        </tr>
    </table>

    <!-- 2. Jumlah Barang per Umur Barang -->
    <h4 class="text-center">Jumlah Barang per Umur Barang</h4>
    <table width="100%">
        <tr>
            <th class="text-center">No</th>
            <th>Kelompok Umur</th>
            <th class="text-center">Jumlah</th>
        </tr>
        @php $i = 1; $totalUmur = 0; @endphp
        @foreach($umurBarang as $key => $val)
            @php $totalUmur += $val; @endphp
            <tr>
                <td class="text-center">{{ $i++ }}</td>
                <td>{{ $key }}</td>
                <td class="text-center">{{ $val }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2" class="text-center fw-bold">Total</td>
            <td class="text-center fw-bold">{{ $totalUmur }}</td>
        </tr>
    </table>

    <!-- 3. Jumlah Barang per Ruangan -->
    <h4 class="text-center">Jumlah Barang per Ruangan</h4>
    <table width="100%">
        <tr>
            <th class="text-center">No</th>
            <th>Nama Ruangan</th>
            <th class="text-center">Jumlah</th>
        </tr>
        @php $totalRuangan = 0; @endphp
        @foreach($ruangan as $key => $val)
            @php $totalRuangan += $val; @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $key ?: 'Tidak memiliki keterangan ruangan' }}</td>
                <td class="text-center">{{ $val }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2" class="text-center fw-bold">Total</td>
            <td class="text-center fw-bold">{{ $totalRuangan }}</td>
        </tr>
    </table>
</body>
</html>
