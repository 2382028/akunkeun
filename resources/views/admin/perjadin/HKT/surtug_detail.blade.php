<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width initial-scale=1.0" />

    <style>
        table tr td {
            font-size: 15px;
        }

        table tr .judul {
            text-align: center;
            font-size: 17px;
            font-weight: bold;
        }

        table tr .nomor {
            text-align: start;
            font-size: 16px;
            font-weight: bold;
            padding-left: 273px;
            padding-bottom: 10px;
        }

        table tr .template {
            text-align: justify;
            font-size: 15px;
        }

        hr {
            border: 0;
            height: 2px;
            background: #333;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            position: relative;
            padding-bottom: 0px; /* Height of the footer */
            box-sizing: border-box;
        }

        .logo {
            max-width: 130px;
            height: auto;
            position: static;
            top: -5px;
            right: -50px;
            margin-right: -50px;
            margin-left: -20px;
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

        .footer-logo {
            float: left;
            width: 150px;
            height: auto;
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
    <!-- Awal Dashboard -->
    <center>
        <!-- Kop dan Alamat Instansi -->
        <table width="500" >
            <tr>
                <td>
                    <div style="text-align: right;">
                        <img src="https://akunkeun.lldikti4.id/assets/images/logo-tut-wuri.png" class="logo" alt="TUT-WURI"></a>
                    </div>
                </td>
                <td>
                    <center >
                        <font size="4" margin="0">KEMENTERIAN PENDIDIKAN TINGGI, SAINS,</font><br>
                        <font size="4">DAN TEKNOLOGI</font><br>
                        <font size="3" margin="0"><b>LEMBAGA LAYANAN PENDIDIKAN TINGGI WILAYAH IV</b></font><br>
                        <font size="3" style="font-size: 0.95em;">Alamat Jalan Khp Hasan Mustopa Nomor 38 Kota Bandung 40124</font><br>
                        <font size="3" style="font-size: 0.95em;">Telepon (022) 7275630 </font><br>
                        <font size="3" style="font-size: 0.95em;">Laman www.lldikti4.kemdikbud.go.id</font><br>
                    </center>
                </td>
            </tr>
        </table>

        <!-- Judul dan Nomor Surat -->
        <table width="500">
            @foreach($datas['surtugs'] as $surtug)
            <tr>
                <hr width="500"  style="margin-top: -7px;">
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
                <td class="template">{!! $surtug->perihal !!}</td>
            </tr>
            @endforeach
        </table>

        @php
        if (!function_exists('getPrioritasJabatan')) {
                // Fungsi untuk memberikan prioritas pada jabatan
                function getPrioritasJabatan($peserta) {
                    if (isset($peserta->nama_jabatan)) {
                        switch ($peserta->nama_jabatan) {
                            case 'Kepala':
                                return 1; // Urutan paling atas
                            case 'Kepala Bagian Umum':
                                return 2; // Di bawah Kepala
                            case 'Pengemudi':
                                return 5; // Di urutan paling bawah
                            default:
                                return 3; // Pegawai lain selain Pengemudi
                        }
                    } else {
                        // Jika bukan pegawai (non-pegawai), berikan prioritas 4
                        return 4;
                    }
                }
            }

            if (!function_exists('removeDuplicatesById')) {
                // Fungsi untuk menghapus duplikasi berdasarkan ID
                function removeDuplicatesById($array) {
                    $uniqueArray = [];
                    $seenIds = [];

                    foreach ($array as $item) {
                        if (!in_array($item->id, $seenIds)) { // Cek jika ID belum ada
                            $seenIds[] = $item->id; // Tambahkan ID ke daftar yang sudah dilihat
                            $uniqueArray[] = $item; // Tambahkan item ke array unik
                        }
                    }

                    return $uniqueArray;
                }
            }

            // Mengubah Collection menjadi array dengan toArray()
            $pesertaPegawaisArray = $datas['pesertaPegawais']->toArray();
            $pesertaNonPegawaisArray = $datas['pesertaNonPegawais']->toArray();
            $pengemudisArray = $datas['pengemudis']->toArray();

            // Menggabungkan peserta pegawai dan non-pegawai
            $allPeserta = array_merge($pengemudisArray, $pesertaPegawaisArray, $pesertaNonPegawaisArray);

            // Hapus duplikasi berdasarkan ID
            $allPeserta = removeDuplicatesById($allPeserta);

            // Mengurutkan peserta dengan aturan yang telah disebutkan
            usort($allPeserta, function($a, $b) {
                // Urutkan sesuai prioritas jabatan
                $prioritasA = getPrioritasJabatan($a);
                $prioritasB = getPrioritasJabatan($b);

                // Bandingkan prioritas, yang lebih kecil akan berada di atas
                return $prioritasA - $prioritasB;
            });
        @endphp

        @if ($datas['tipeSurtug']->isTable=='1')
        <table width="500" border="1" cellpadding="5" cellspacing="0" style="border-color: black; font-size: 14px;">
            <thead>
                <tr>
                    <th style="text-align: center;">NO</th>
                    <th style="text-align: center;">NAMA</th>
                    <th style="text-align: center;">NIP</th>
                    <th style="text-align: center;">PANGKAT/GOLONGAN</th>
                    <th style="text-align: center;">JABATAN</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allPeserta as $peserta)
                <tr  >
                    <td style="font-size: 14px; text-align: center;">{{$loop->iteration}}</td>
                    <td style="font-size: 14px;">{{$peserta->nama_lengkap}}</td>
                    @if ($peserta->NIP_NIK == "-")
                        <td style="font-size: 14px;">-</td>
                    @else
                        <td style="font-size: 14px;">{{$peserta->NIP_NIK}}</td>
                    @endif
                    @if (($peserta->pangkat == "-") || ($peserta->pangkat == NULL))
                    <td style="font-size: 14px;">-</td>
                    @else
                    <td style="font-size: 14px;">{{$peserta->pangkat}} ({{$peserta->golongan}})</td>
                    @endif
                    @if(isset($peserta->nama_jabatan))
                        <td style="font-size: 14px;">{{$peserta->nama_jabatan}}</td>
                    @else
                        <td style="font-size: 14px;">-</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            @foreach($allPeserta as $peserta)
            <table width="500">
                <tr>
                    @if(count($allPeserta) > 1)
                        <td class="template" width="30">{{$loop->iteration}}</td>
                    @else
                        <td class="template" width="30"></td>
                    @endif
                    <td class="template" width="150"> Nama</td>
                    <td class="template">: {{$peserta->nama_lengkap}}</td>
                </tr>
                <tr>
                    <td class="template" width="30"></td>
                    <td class="template" width="150"> NIP</td>
                    <td class="template">: {{$peserta->NIP_NIK ?? '-'}}</td>
                </tr>
                <tr>
                    <td class="template" width="30"></td>
                    <td class="template" width="150"> Pangkat/Gol. Ruang</td>
                    <td class="template">: {{$peserta->pangkat ?? '-'}} / {{$peserta->golongan ?? '-'}}</td>
                </tr>

                <!-- Hanya tampilkan jabatan jika peserta adalah pegawai -->
                @if(isset($peserta->nama_jabatan))
                <tr>
                    <td class="template" width="30"></td>
                    <td class="template" width="150"> Jabatan</td>
                    <td class="template">: {{$peserta->nama_jabatan ?? '-'}}</td>
                </tr>
                @else
                <tr>
                    <td class="template" width="30"></td>
                    <td class="template" width="150"> Jabatan</td>
                    <td class="template">: -</td>
                </tr>
                @endif
            </table>
        @endforeach
        @endif


        <br>

        <!-- Template 3: Paragraf 1 (Informasi Perjadin) -->
        @foreach($datas['surtugs'] as $surtug)
        <table width="500">
            <tr>
                <td class="template" style="padding-bottom:10px;">{!! $surtug->paragraf_1 !!}</td>
            </tr>
        </table>
        @endforeach

        <!-- Template 4: Paragraf 2 (Biaya DIPA) -->
        @foreach($datas['surtugs'] as $surtugg)
        <table width="500">
            <tr>
                <td class="template" style="padding-bottom:10px;">{!! $surtug->paragraf_2 !!}</td>
            </tr>
        </table>
        @endforeach

        <!-- Template 5: Paragraf 3 (Laporan) -->
        @foreach($datas['surtugs'] as $surtug)
        <table width="500">
            <tr>
                <td class="template" style="padding-bottom:25px;">{!! $surtug->paragraf_3 !!}</td>
            </tr>
        </table>
        @endforeach

        <!-- Template 6: Tanda Tangan -->
        <table width="500">
            <tr>
                <td width="310"></td>
                {{-- <td align="left">{!! \Carbon\Carbon::now()->translatedFormat('j F Y') !!}</td> --}}
            </tr>
            <tr>
                <td width="310"></td>
                <td align="left">Kepala Lembaga Layanan Pendidikan Tinggi Wilayah IV,<br><br><br><br><br><br>{{$datas['pegawaiKepala']->nama_lengkap}}<br>NIP {{$datas['pegawaiKepala']->NIP_NIK}}</td>
            </tr>
        </table>
    </center>
    <!-- Akhir Dashboard -->

</body>

</html>
