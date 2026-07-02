<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Usia BMN</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            font-size: 11px;
            padding: 4px;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2 class="text-center">Rekapitulasi BMN Berdasarkan Usia ({{ $filter }})</h2>
    <table width="100%">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Kode Barang</th>
                <th>NUP</th>
                <th>Nama Barang</th>
                <th>Merek</th>
                <th>Kategori</th>
                <th>Tahun Beli</th>
                <th>Nama Ruangan</th>
                <th>Usia</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bmns as $i => $bmn)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $bmn->kode_bmn }}</td>
                    <td>{{ $bmn->nup_bmn }}</td>
                    <td>{{ $bmn->nama_bmn }}</td>
                    <td>{{ $bmn->merk_bmn }}</td>
                    <td>{{ $bmn->kategori_bmn }}</td>
                    <td class="text-center">{{ $bmn->tahun_beli }}</td>
                    <td>{{ $bmn->ruangan->nama_ruangan ?? '-' }}</td>
                    <td class="text-center">{{ now()->year - $bmn->tahun_beli }} tahun</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
