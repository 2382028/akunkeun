<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width initial-scale=1.0" />

    <style>
        table tr td {
            font-size: 12px;
        }

        table tr .judul {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
        }

        table tr .nomor {
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            padding-left: 400px;
        }

        table tr .template {
            text-align: center;
            font-size: 12px;
        }

        hr {
            border: 0;
            height: 2px;
            background: #333;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            position: relative;
            padding-bottom: 0px;
            box-sizing: border-box;
        }


        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            font-size: 9px;
            border-top: 1px solid #333;
            padding: 10px;
            background: #fff;
        }



        .footer-notes {
            float: left;
            margin-left: 20px;
            width: calc(100% - 180px);
        }

        .footer-notes p {
            margin: 0;
        }
    </style>
</head>

<body>

    <center>
        <table width="800">
            <tr>
                <td class="nomor">Tahun Anggaran&nbsp;:
                    {{ \App\Models\Versi::find(session('versi'))->versi ?? '' }}
                </td>
            </tr>
            <tr>
                <td class="nomor">Nomor Bukti
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                </td>
            </tr>
            <tr>
                <td class="nomor">MAK
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{$datas['MAK']}}
                </td>
            </tr>

        </table>

            <table width="500">
                <br>
                <tr>
                    <td class="judul">DAFTAR PEMBAYARAN</td>
                </tr>
                <tr>
                    <td class="judul">JASA PROFESI MODERATOR</td>
                </tr>
                <tr>
                   {{-- Ini kasih judul sesuai nama kegiatannya --}}
                    <td class="judul">{{$datas['judul']}}</td>

                </tr>
                <tr>
                    <td class="judul">Sesuai SK Kuasa Pengguna Anggaran LLDIKTI Wilayah IV Nomor : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Tanggal : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                </tr>
                <tr>
                    <td class="judul">Tanggal Pelaksanaan {{ \Carbon\Carbon::parse($datas['tanggal'])->locale('id')->isoFormat('D MMMM YYYY') }}<span class="long-space"></span></td>
                </tr>
            </table>

            <br>
            <table width="500" border="1" cellpadding="5" cellspacing="0"  style="border-collapse: collapse; font-size: 10px; border: 1px solid black;" >

                <thead class="text-center" >
                    <tr class="text-center small">
                        <th >No</th>
                        <th >Nama</th>
                        <th >Jumlah <br>Jam</th>
                        <th >Harga <br>Satuan</th>
                        <th >Jumlah <br>(RP)</th>
                        <th >PPH</th>
                        <th >Jumlah <br>dibayar (Rp)</th>
                        <th >Tanda <br>Tangan</th>
                    </tr>
                </thead>
                <tbody >
                    @foreach ($datas['data'] as $data)
                        <tr class="template">
                        <td class="template">{{$loop->iteration}}</td>
                        <td class="" style="min-width: 150px">{{$data['nama']}}</td>
                        <td class="template">{{$data['jumlah_kegiatan']}}</td>
                        <td class="template">{{ number_format($data['satuan_honorarium'], 0, ',', '.') }}</td>
                        <td class="template">{{ number_format($data['jumlah_honorarium'], 0, ',', '.') }}</td>
                        <td class="template">{{ number_format($data['pph'], 0, ',', '.') }}</td>
                        <td class="template">{{ number_format($data['nominal_honorarium'], 0, ',', '.') }}</td>
                        <td class="text-center">{{$data['data_bank']}}</td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="4" class="fw-bold text-end" style="">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <strong>Jumlah</strong>
                        </td>
                        <td class="template"><strong>{{ number_format($datas['subTotal_jumlah'], 0, ',', '.') }}</strong></td>
                        <td class="template"><strong>{{ number_format($datas['subTotal_pph'], 0, ',', '.') }}</strong></td>
                        <td class="template"><strong>{{ number_format($datas['subTotal_nominal'], 0, ',', '.') }}</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            </table>
        <br>

        <!-- Template 6: Tanda Tangan -->
        <table width="500">
            <br>
            <tr>
                <td width="10"></td>
                <td align="left"><span id=""></span><br>  Setuju dibebankan pada mata anggaran berkenaan, <br>
                    a.n. Kuasa Pengguna Anggaran <br>
                    Pejabat Pembuat Komitmen <br>
                    LLDIKTI Wilayah IV,
                    <br><br><br><br><br><br>
                    {{ $datas['pegawaiMaster']['nama_lengkap'] }}<br>
                    NIP. {{ $datas['pegawaiMaster']['NIP_NIK'] }}
                </td>

                <td width="100"></td>
                <td align="left"><span id=""></span><br> Bandung,  {{ \Carbon\Carbon::parse($datas['tanggal'])->locale('id')->isoFormat('D MMMM YYYY') }}
                    <br>Telah dibayar sejumlah
                    <br>Rp. {{ number_format($datas['subTotal_nominal'], 0, ',', '.') }}
                    <br>Bendahara Pengeluaran,
                    <br><br><br><br><br><br>
                    {{ $datas['pegawaiBendahara']['nama_lengkap'] }}<br>
                    NIP. {{ $datas['pegawaiBendahara']['NIP_NIK'] }}
                </td>
            </tr>
        </table>
    </center>

</body>

</html>
