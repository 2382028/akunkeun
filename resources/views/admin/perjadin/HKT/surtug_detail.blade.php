<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width initial-scale=1.0" />

    <style>
        table tr td {
            font-size: 13px;
        }

        table tr .judul {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
        }

        table tr .nomor {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
        }

        table tr .template {
            text-align: justify;
            font-size: 13px;
        }

        hr {
            border: 0;
            height: 2px;
            background: #333;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
        }

        .logo {
            max-width: 150px;
            height: auto;
            position: static;
            top: -5px;
            right: -50px;
        }

    </style>
</head>

<body>
    <!-- Awal Dashboard -->
    <center>
        <!-- Kop dan Alamat Instansi -->
        <table width="500">
            <tr>
                <td>
                    <div style="text-align: right;">
                    <a href="https://ibb.co.com/NV42KhW"><img src="https://i.ibb.co.com/ngWMj2Q/TUT-WURI.jpg" alt="TUT-WURI" class="logo"></a>
                    </div>
                </td>
                <td>
                    <center>
                        <font size="3" margin="0"><b>KEMENTERIAN PENDIDIKAN, KEBUDAYAAN,</b></font><br>
                        <font size="3"><b>RISET DAN TEKNOLOGI</b></font><br>
                        <font size="3"><b>LEMBAGA LAYANAN PENDIDIKAN TINGGI</b></font><br>
                        <font size="3"><b>WILAYAH IV</b></font><br>
                        <font size="2"><b>Jalan Penghulu Haji Hasan Mustafa Nomor 38 Bandung 40124</b></font><br>
                        <font size="2"><b>Telepon (022) 7275630, 7274377, Faksimile (022) 7207812</b></font><br>
                    </center>
                </td>
            </tr>
        </table>
        <!-- Judul dan Nomor Surat -->
        <table width="500">
            @foreach($datas['surtugs'] as $surtug)
            <tr>
                <hr width="500">
            </tr>
            <tr>
                <td class="judul">SURAT TUGAS</td>
            </tr>
            <tr>
                <td class="nomor">Nomor: {{$surtug->nomor_surat}} </td>
            </tr>
            @endforeach
        </table>
        <!-- Template 1: Memberikan Tugas -->
        <table width="500">
            @foreach($datas['surtugs'] as $surtug)
            <tr>
                <td class="template">{{$surtug->perihal}}</td>
            </tr>
            @endforeach
        </table>
        <!-- Template 2: Identitas Pegawai -->
        @foreach($datas['pesertaPegawais'] as $pesertaPegawais)
        <table width="500">
            <tr>
                <td class="template" width="30">{{$loop->iteration}} </td>
                <td class="template" width="150"> Nama</td>
                <td class="template">: {{$pesertaPegawais->nama_lengkap }}</td>
            </tr>
            <tr>
                <td class="template" width="30"> </td>
                <td class="template" width="150"> NIP</td>
                <td class="template">: {{$pesertaPegawais->NIP_NIK }}</td>
            </tr>
            <tr>
                <td class="template" width="30"> </td>
                <td class="template" width="150"> Pangkat/Gol. Ruang</td>
                <td class="template">: {{$pesertaPegawais->pangkat }} / {{$pesertaPegawais->golongan }} </td>
            </tr>
            <tr>
                <td class="template" width="30"> </td>
                <td class="template" width="150"> Jabatan</td>
                <td class="template">: {{$pesertaPegawais->status_pegawai }}</td>
            </tr>
        </table>
        @endforeach
        <br>
        </br>
        <!-- Template 3: Paragraf 1 (Informasi Perjadin) -->
        @foreach($datas['surtugs'] as $surtug)
        <table width="500">
            <tr>
                <td class="template">{{$surtug->paragraf_1}}</td>
            </tr>
            <br>
        </table>
        @endforeach
        <!-- Template 4: Paragraf 2 (Biaya DIPA) -->
        @foreach($datas['surtugs'] as $surtugg)
        <table width="500">
            <tr>
                <td class="template">{{$surtug->paragraf_2}}</td>
            </tr>
            <br>
        </table>
        @endforeach
        <!-- Template 5: Paragraf 3 (Laporan) -->
        @foreach($datas['surtugs'] as $surtug)
        <table width="500">
            <tr>
                <td class="template">{{$surtug->paragraf_3}}</td>
            </tr>
            <br>
        </table>
        @endforeach

        <!-- Template 6: Tanda Tangan -->
        <table width="500">
            <br>
                <td align="left">{!! \Carbon\Carbon::now()->translatedFormat('j F Y') !!}</td>
            <tr>   
                <td width="310"></td>
                <td align="left">Kepala Lembaga Layanan Pendidikan Tinggi Wilayah IV,<br><br><br><br><br><br>M. Samsuri<br>NIP 197901142003121001</td>
            </tr>
        </table>
    </center>
    <!-- Akhir Dashboard -->

    <!-- Awal Footer -->
    <!-- Akhir Footer -->
</body>

</html>