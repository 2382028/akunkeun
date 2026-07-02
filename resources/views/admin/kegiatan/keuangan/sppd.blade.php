<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="widht=device-widht, initial-scale=10" />
    <style>
        @page {
            size: 210mm 330mm; /* Ukuran F4 */
            margin: 27px;
        }

        .logo {
            max-width: 150px;
            height: auto;
            position: static;
            top: -5px;
            margin-right: -40px;
            margin-left: -20px;
            margin-bottom: 20px;
        }
        .rincian {
            border: 1px solid black;
            border-collapse: collapse;
            width: 550px;
        }

        /* Menambahkan padding 1px untuk semua sel dalam tabel rincian */
        .rincian td, .rincian th {
            padding: 10px;
            border: 1px solid black;
        }

        .belakangSPPD {
            border: 0px solid black;
            border-collapse: collapse;
            width: 700px;
        }

        /* Menambahkan padding 1px untuk semua sel dalam tabel rincian */
        .belakangSPPD td, .belakangSPPD th {
            padding-top: 5px;
            padding-left: 5px;
            border: 0px solid black;
        }
        .noPrint {
            color: transparent !important; /* Membuat teks transparan */
            background-color: transparent !important; /* Menghapus background */
            border: none !important; /* Menghilangkan border */
        }

        table tr td {
            font-size: 13px;
        }

        table tr th {
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
            font-size: 5px;
        }

        hr {
            border: 0;
            height: 2px;
            background: #333;
            width: 100%; /* Garis memenuhi lebar penuh */
            margin: 0; /* Menghapus margin untuk garis penuh */
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            max-width: fit-content;
            width: 210mm; /* Lebar F4 */
            height: 600mm; /* Tinggi F4 */
            margin: 0; /* Menghapus margin default */
            padding: 1px; /* Memastikan padding tetap minimal */
        }

        .baris-tiga {
            border-bottom: none;
        }
    </style>
</head>

<?php
function terbilang($angka)
{
    $angka = abs($angka);
    $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $hasil = "";
    if ($angka < 12) {
        $hasil = " " . $huruf[$angka];
    } else if ($angka < 20) {
        $hasil = terbilang($angka - 10) . " belas";
    } else if ($angka < 100) {
        $hasil = terbilang($angka / 10) . " puluh" . terbilang($angka % 10);
    } else if ($angka < 200) {
        $hasil = " seratus" . terbilang($angka - 100);
    } else if ($angka < 1000) {
        $hasil = terbilang($angka / 100) . " ratus" . terbilang($angka % 100);
    } else if ($angka < 2000) {
        $hasil = " seribu" . terbilang($angka - 1000);
    } else if ($angka < 1000000) {
        $hasil = terbilang($angka / 1000) . " ribu" . terbilang($angka % 1000);
    } else if ($angka < 1000000000) {
        $hasil = terbilang($angka / 1000000) . " juta" . terbilang($angka % 1000000);
    } else if ($angka < 1000000000000) {
        $hasil = terbilang($angka / 1000000000) . " miliar" . terbilang(fmod($angka, 1000000000));
    } else if ($angka < 1000000000000000) {
        $hasil = terbilang($angka / 1000000000000) . " triliun" . terbilang(fmod($angka, 1000000000000));
    }
    return $hasil;
}
?>



<!-- SPPD PEGAWAI -->
@foreach($datas['pesertaPegawais'] as $pesertaPegawai)
@php
$keberangkatan = \Carbon\Carbon::parse($pesertaPegawai->tgl_mulai);
$selesai = \Carbon\Carbon::parse($pesertaPegawai->tgl_selesai);
$hariPerjalanan = $selesai->diffInDays($keberangkatan) + 1;
$terbilangHari = trim(terbilang($hariPerjalanan));
$numRow = 2;
@endphp

<body style="padding: 1px">
    <!-- Awal Dashboard -->
    <center>
        <table width="550" style="margin-bottom: -10px;">
            <tr>
                <td>
                    <div style="text-align: right;">
                        
                        <img src="https://akunkeun.lldikti4.id/assets/images/logo-tut-wuri.png" class="logo" alt="TUT-WURI">
                        <!-- <a href="https://ibb.co.com/NV42KhW"><img src="https://i.ibb.co.com/ngWMj2Q/TUT-WURI.jpg" alt="TUT-WURI" class="logo"></a> -->
                    </div>
                </td>
                <td>
                    <center>
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
        <table width="550">
            <tr>
                <td>
                    <hr> <!-- Menggunakan hr untuk garis pemisah -->
                </td>
            </tr>
            <tr></tr>
            <tr>
                <td class="judul">SURAT PERINTAH PERJALANAN DINAS</td>
            </tr>
        </table>

        <!-- Tabel Rincian -->
        <table width="550" class="rincian" border="1" style="max-width: 550px; margin-top:10px;">
            <tr>
                <td width="20" align="center">1</td>
                <td width="170">Pejabat berwenang yang memberikan perintah</td>
                <td colspan="2" width="320">Kuasa Pengguna Anggaran</td>
            </tr>
            <tr>
                <td align="center">2</td>
                <td>Nama/Nip Pegawai yang melaksanakan perjalanan dinas</td>
                <td colspan="2">{{ $pesertaPegawai->nama_lengkap }}</td>
            </tr>
            <tr>
                <td align="center">3</td>
                <td>a. Pangkat dan Golongan<br>b. Jabatan/Instansi<br>c. Tingkat Biaya Perjalanan Dinas</td>
                <td colspan="2">a. {{ $pesertaPegawai->golongan }}<br>b. {{ $pesertaPegawai->nama_jabatan }}<br>c. E</td>
            </tr>
            <tr>
                <td align="center">4</td>
                <td>Maksud Perjalanan Dinas</td>
                <td colspan="2">{{ $pesertaPegawai->nama_kegiatan }}</td>
            </tr>
            <tr>
                <td align="center">5</td>
                <td>Alat angkutan yang dipergunakan</td>
                <td colspan="2">-</td>
            </tr>
            <tr>
                <td align="center">6</td>
                <td>a. Tempat berangkat<br>b. Tempat tujuan</td>
                <td colspan="2">a. Bandung<br>b. {{ $pesertaPegawai->kab_kota }}</td>
            </tr>
            <tr>
                <td align="center">7</td>
                <td>a. Lamanya perjalanan dinas<br>b. Tanggal berangkat<br>c. Tanggal harus kembali/tiba di tempat baru *)</td>
                <td colspan="2" style="vertical-align: top;">a. {{ $hariPerjalanan }} ({{ $terbilangHari }}) hari<br>b. {{ \Carbon\Carbon::parse($pesertaPegawai->tgl_mulai)->translatedFormat('d F Y') }}<br>c. {{ \Carbon\Carbon::parse($pesertaPegawai->tgl_selesai)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td align="center">8</td>
                <td>Pengikut: Nama
                    <br>1.
                    <br>2.
                    <br>3.
                    <br>4.
                    <br>5.
                </td>
                <td style="vertical-align: top;" width="40">Umur</td>
                <td style="vertical-align: top;" width="255">Hubungan<br>keluarga/keterangan</td>
            </tr>
            <tr>
                <td align="center">9</td>
                <td>Pembebanan anggaran:</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td align="center"></td>
                <td>a. Instansi<br>b. Akun</td>
                <td colspan="2">
                    a. Kantor Lembaga Layanan Pendidikan Tinggi Wilayah IV
                    <br>b. {{ $pesertaPegawai->kode_kegiatan }}.{{ $pesertaPegawai->kode_output }}.{{ $pesertaPegawai->kode_sub_output }}.{{ $pesertaPegawai->kode_komponen }}.{{ $pesertaPegawai->kode_sub_kegiatan }}.{{ $pesertaPegawai->kode_akun }}
                </td>
            </tr>
            <tr>
                <td align="center">10</td>
                <td>Keterangan lain-lain</td>
                <td colspan="2"></td>
            </tr>

        </table>
    </center>
    <p style="font-size: 12px; text-align: start; padding-left: 55px !important;">
        Coret yang tidak perlu
    </p>

    <center>
        <table width="550">
            <br>
            <tr>
                <td width="360"></td>
                <td align="left">
                    Dikeluarkan di Bandung
                    <br>Pada Tanggal: {{ \Carbon\Carbon::parse($pesertaPegawai->tgl_mulai)->translatedFormat('d F Y') }}
                    <br>...................................................................
                    <br>Pejabat Pembuat Komitmen
                    <br><br><br><br><br><br>
                    {{ $datas['pegawaiMaster']->nama_lengkap }} <br>
                    NIP {{ $datas['pegawaiMaster']->NIP_NIK }}
                </td>
            </tr>
        </table>

    </center>
</body>
@endforeach

<!-- SPPD NONPEGAWAI -->
@foreach($datas['pesertaNonPegawais'] as $pesertaNonPegawai)
@php
$keberangkatan = \Carbon\Carbon::parse($pesertaNonPegawai->tgl_mulai);
$selesai = \Carbon\Carbon::parse($pesertaNonPegawai->tgl_selesai);
$hariPerjalanan = $selesai->diffInDays($keberangkatan)+1;
$terbilangHari = trim(terbilang($hariPerjalanan));
@endphp

<body style="padding: 1px">
    <!-- Awal Dashboard -->
    <center>
        <table width="550" style="margin-bottom: -10px;">
            <tr>
                <td>
                    <div style="text-align: right;">
                        <a href="https://ibb.co.com/NV42KhW"><img src="https://i.ibb.co.com/ngWMj2Q/TUT-WURI.jpg" alt="TUT-WURI" class="logo"></a>
                    </div>
                </td>
                <td>
                    <center>
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
        <table width="550">
            <tr>
                <td>
                    <hr> <!-- Menggunakan hr untuk garis pemisah -->
                </td>
            </tr>
            <tr></tr>
            <tr>
                <td class="judul">SURAT PERINTAH PERJALANAN DINAS</td>
            </tr>
        </table>

        <!-- Tabel Rincian -->
        <table width="550" class="rincian" border="1" style="max-width: 550px; margin-top:10px;">
            <tr>
                <td width="20" align="center">1</td>
                <td width="170">Pejabat berwenang yang memberikan perintah</td>
                <td colspan="2" width="320">Kuasa Pengguna Anggaran</td>
            </tr>
            <tr>
                <td align="center">2</td>
                <td>Nama/Nip Pegawai yang melaksanakan perjalanan dinas</td>
                <td colspan="2">{{ $pesertaNonPegawai->nama_lengkap }}</td>
            </tr>
            <tr>
                <td align="center">3</td>
                <td>a. Pangkat dan Golongan<br>b. Jabatan/Instansi<br>c. Tingkat Biaya Perjalanan Dinas</td>
                <td colspan="2">a. {{ $pesertaNonPegawai->golongan }}<br>b. -<br>c. -</td>
            </tr>
            <tr>
                <td align="center">4</td>
                <td>Maksud Perjalanan Dinas</td>
                <td colspan="2">{{ $pesertaNonPegawai->nama_kegiatan }}</td>
            </tr>
            <tr>
                <td align="center">5</td>
                <td>Alat angkutan yang dipergunakan</td>
                <td colspan="2">-</td>
            </tr>
            <tr>
                <td align="center">6</td>
                <td>a. Tempat berangkat<br>b. Tempat tujuan</td>
                <td colspan="2">a. Bandung<br>b. {{ $pesertaNonPegawai->kab_kota }}</td>
            </tr>
            <tr>
                <td align="center">7</td>
                <td>a. Lamanya perjalanan dinas<br>b. Tanggal berangkat<br>c. Tanggal harus kembali/tiba di tempat baru *)</td>
                <td colspan="2" style="vertical-align: top;">a. {{ $hariPerjalanan }} ({{ $terbilangHari }}) hari<br>b. {{ \Carbon\Carbon::parse($pesertaNonPegawai->tgl_mulai)->translatedFormat('d F Y') }}<br>c. {{ \Carbon\Carbon::parse($pesertaNonPegawai->tgl_selesai)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td align="center">8</td>
                <td>Pengikut: Nama
                    <br>1.
                    <br>2.
                    <br>3.
                    <br>4.
                    <br>5.
                </td>
                <td style="vertical-align: top;" width="40">Umur</td>
                <td style="vertical-align: top;" width="255">Hubungan<br>keluarga/keterangan</td>
            </tr>
            <tr>
                <td align="center">9</td>
                <td>Pembebanan anggaran:</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td align="center"></td>
                <td>a. Instansi<br>b. Akun</td>
                <td colspan="2">
                    a. Kantor Lembaga Layanan Pendidikan Tinggi Wilayah IV
                    <br>b. {{ $pesertaNonPegawai->kode_kegiatan }}.{{ $pesertaNonPegawai->kode_output }}.{{ $pesertaNonPegawai->kode_sub_output }}.{{ $pesertaNonPegawai->kode_komponen }}.{{ $pesertaNonPegawai->kode_sub_kegiatan }}.{{ $pesertaNonPegawai->kode_akun }}
                </td>
            </tr>
            <tr>
                <td align="center">10</td>
                <td>Keterangan lain-lain</td>
                <td colspan="2"></td>
            </tr>

        </table>
    </center>
    <p style="font-size: 12px; text-align: start; padding-left: 55px !important;">
        Coret yang tidak perlu
    </p>

    <center>
        <table width="550">
            <br>
            <tr>
                <td width="360"></td>
                <td align="left">
                    Dikeluarkan di Bandung
                    <br>Pada Tanggal: {{ \Carbon\Carbon::parse($pesertaNonPegawai->tgl_mulai)->translatedFormat('d F Y') }}
                    <br>...................................................................
                    <br>Pejabat Pembuat Komitmen
                    <br><br><br><br><br><br>
                    {{ $datas['pegawaiMaster']->nama_lengkap }} <br>
                    NIP {{ $datas['pegawaiMaster']->NIP_NIK }}
                </td>
            </tr>
        </table>

    </center>
</body>
@endforeach


<!-- SPPD PENGEMUDI -->
@foreach($datas['pengemudis'] as $pengemudi)
@php
$keberangkatan = \Carbon\Carbon::parse($pengemudi->tgl_mulai);
$selesai = \Carbon\Carbon::parse($pengemudi->tgl_selesai);
$hariPerjalanan = $selesai->diffInDays($keberangkatan)+1;
$terbilangHari = trim(terbilang($hariPerjalanan));
$numRow=2;
@endphp

<body style="padding: 1px">
    <!-- Awal Dashboard -->
    <center>
        <table width="550" style="margin-bottom: -10px;">
            <tr>
                <td>
                    <div style="text-align: right;">
                        <a href="https://ibb.co.com/NV42KhW"><img src="https://i.ibb.co.com/ngWMj2Q/TUT-WURI.jpg" alt="TUT-WURI" class="logo"></a>
                    </div>
                </td>
                <td>
                    <center>
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
        <table width="550">
            <tr>
                <td>
                    <hr> <!-- Menggunakan hr untuk garis pemisah -->
                </td>
            </tr>
            <tr></tr>
            <tr>
                <td class="judul">SURAT PERINTAH PERJALANAN DINAS</td>
            </tr>
        </table>

        <!-- Tabel Rincian -->
        <table width="550" class="rincian" border="1" style="max-width: 550px; margin-top:10px;">
            <tr>
                <td width="20" align="center">1</td>
                <td width="170">Pejabat berwenang yang memberikan perintah</td>
                <td colspan="2" width="320">Kuasa Pengguna Anggaran</td>
            </tr>
            <tr>
                <td align="center">2</td>
                <td>Nama/Nip Pegawai yang melaksanakan perjalanan dinas</td>
                <td colspan="2">{{ $pengemudi->nama_lengkap }}</td>
            </tr>
            <tr>
                <td align="center">3</td>
                <td>a. Pangkat dan Golongan<br>b. Jabatan/Instansi<br>c. Tingkat Biaya Perjalanan Dinas</td>
                <td colspan="2">a. {{ $pengemudi->golongan }}<br>b. {{ $pengemudi->nama_jabatan }}<br>c. F</td>
            </tr>
            <tr>
                <td align="center">4</td>
                <td>Maksud Perjalanan Dinas</td>
                <td colspan="2">{{ $pengemudi->nama_kegiatan }}</td>
            </tr>
            <tr>
                <td align="center">5</td>
                <td>Alat angkutan yang dipergunakan</td>
                <td colspan="2">-</td>
            </tr>
            <tr>
                <td align="center">6</td>
                <td>a. Tempat berangkat<br>b. Tempat tujuan</td>
                <td colspan="2">a. Bandung<br>b. {{ $pengemudi->kab_kota }}</td>
            </tr>
            <tr>
                <td align="center">7</td>
                <td>a. Lamanya perjalanan dinas<br>b. Tanggal berangkat<br>c. Tanggal harus kembali/tiba di tempat baru *)</td>
                <td colspan="2" style="vertical-align: top;">a. {{ $hariPerjalanan }} ({{ $terbilangHari }}) hari<br>b. {{ \Carbon\Carbon::parse($pengemudi->tgl_mulai)->translatedFormat('d F Y') }}<br>c. {{ \Carbon\Carbon::parse($pengemudi->tgl_selesai)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td align="center">8</td>
                <td>Pengikut: Nama
                    <br>1.
                    <br>2.
                    <br>3.
                    <br>4.
                    <br>5.
                </td>
                <td style="vertical-align: top;" width="40">Umur</td>
                <td style="vertical-align: top;" width="255">Hubungan<br>keluarga/keterangan</td>
            </tr>
            <tr>
                <td align="center">9</td>
                <td>Pembebanan anggaran:</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td align="center"></td>
                <td>a. Instansi<br>b. Akun</td>
                <td colspan="2">
                    a. Kantor Lembaga Layanan Pendidikan Tinggi Wilayah IV
                    <br>b. {{ $pengemudi->kode_kegiatan }}.{{ $pengemudi->kode_output }}.{{ $pengemudi->kode_sub_output }}.{{ $pengemudi->kode_komponen }}.{{ $pengemudi->kode_sub_kegiatan }}.{{ $pengemudi->kode_akun }}
                </td>
            </tr>
            <tr>
                <td align="center">10</td>
                <td>Keterangan lain-lain</td>
                <td colspan="2"></td>
            </tr>

        </table>
    </center>
    <p style="font-size: 12px; text-align: start; padding-left: 55px !important;">
        Coret yang tidak perlu
    </p>

    <center>
        <table width="550">
            <br>
            <tr>
                <td width="360"></td>
                <td align="left">
                    Dikeluarkan di Bandung
                    <br>Pada Tanggal: {{ \Carbon\Carbon::parse($pengemudi->tgl_mulai)->translatedFormat('d F Y') }}
                    <br>...................................................................
                    <br>Pejabat Pembuat Komitmen
                    <br><br><br><br><br><br>
                    {{ $datas['pegawaiMaster']->nama_lengkap }} <br>
                    NIP {{ $datas['pegawaiMaster']->NIP_NIK }}
                </td>
            </tr>
        </table>

    </center>
</body>
@endforeach

@php
    $barisSPPD = $datas['ttdSPPD']->nSPPD;
@endphp

@if ($barisSPPD > 4)
    @php
        $barisSPPD = 4;
    @endphp
@endif

<!-- SPPD BELAKANG -->
<body style="padding: 1px; display: flex; justify-content: center; margin-top: 80px;">
    <!-- Awal Dashboard -->
    <center>
    <table class="belakangSPPD" style="table-layout: fixed; border-collapse: collapse; margin: 17px;">

        <!-- BARIS 1 -->
        <tr>
            <td width="50%" ></td>
            <td width="50%"style="text-align: left; padding:0;">
                <span style="display: inline-block; margin-right: 5px;" class="noPrint">I.</span>
                <span style="display: inline-block;" class="noPrint">Berangkat dari </span><span style="display: inline-block; margin-left: 45px;" class="noPrint">: Bandung</span><br>
                <span style="display: inline-block; margin-left: 15px;" class="noPrint">(Tempat Kedudukan)</span><br>
                <span style="display: inline-block; margin-left: 15px;" class="noPrint">Ke </span><span style="display: inline-block; margin-left: 130px;"> {{ $datas['ttdSPPD']->tempatTujuan0 }}</span><br>
                <span style="display: inline-block; margin-left: 15px;" class="noPrint">Pada Tanggal </span><span style="display: inline-block; margin-left: 74px;"> {{ \Carbon\Carbon::parse($datas['kegiatan']->tgl_mulai)->translatedFormat('d F Y') }}</span><br>
                <span style="margin-left: 75px;">
                Pejabat Pembuat Komitmen</span><br>
                <br><br><br><br>
                <span style="margin-left: 75px;">{{ $datas['pegawaiMaster']->nama_lengkap }}</span><br>
                <span style="margin-left: 75px;">NIP {{ $datas['pegawaiMaster']->NIP_NIK }}
                </span>
            </td>
        </tr>

        <br>
        @if($barisSPPD >= 1)
        <!-- BARIS 2 -->
        <tr>
            <td   style="text-align: left;  ">
                <span class="noPrint" style="display: inline-block; margin-right: 15px;">II.</span>
                <span class="noPrint" style="display: inline-block;">Tiba di </span><span style="display: inline-block; margin-left: 70px;">: {{ $datas['ttdSPPD']->tempatTiba1 ?? $datas['kegiatan']->kab_kota }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 30px;">Pada Tanggal </span><span style="display: inline-block; margin-left: 36px;">: {{ \Carbon\Carbon::parse($datas['ttdSPPD']->tanggal1 ?? $datas['kegiatan']->tgl_mulai)->translatedFormat('d F Y') }}</span><br>
                <span  class="noPrint" style="display: inline-block; margin-left: 30px;">Kepala </span><span style="display: inline-block; margin-left: 70px;">: {{ $datas['ttdSPPD']->jabatan1 }}</span><br>
                <br><br>
                <span style="display: inline-block; margin-left: 30px; max-width: 200px; text-align: center; display: block;">{{ $datas['ttdSPPD']->nama1 }}</span>
                <br>
                <span class="noPrint" style="display: inline-block; margin-left: 50px;">NIP</span><span style="display: inline-block; margin-left: 10px;"> {{ $datas['ttdSPPD']->nip1 }}</span><br>

            </td>
            <td   style="text-align: left;  ">
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Berangkat dari </span><span style="display: inline-block; margin-left: 44px;">: {{ $datas['ttdSPPD']->tempatTiba1 ?? $datas['kegiatan']->kab_kota }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Ke </span><span style="display: inline-block; margin-left: 105px;">: {{ $datas['ttdSPPD']->tempatTujuan1 ?? $datas['kegiatan']->kab_kota }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Pada Tanggal </span><span style="display: inline-block; margin-left: 49px;">: {{ \Carbon\Carbon::parse($datas['ttdSPPD']->tanggalTujuan1 ?? $datas['kegiatan']->tgl_mulai)->translatedFormat('d F Y') }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Kepala </span><span style="display: inline-block; margin-left: 83px;">: {{ $datas['ttdSPPD']->jabatan1 }}</span><br>
                <br>
                <span style="display: inline-block; margin-left: 15px; max-width: 200px; text-align: center; display: block;">{{ $datas['ttdSPPD']->nama1 }}</span>
                <br>
                <span class="noPrint" style="display: inline-block; margin-left: 20px;">NIP</span><span style="display: inline-block; margin-left: 10px;"> {{ $datas['ttdSPPD']->nip1 }} </span><br>
            </td>
        </tr>
        @endif


        <br>
        @if($barisSPPD < 2)
        <!-- BARIS 3 -->
        <tr class="noPrint">
            <td style="text-align: left;  ">
                <span class="noPrint" style="display: inline-block; margin-right: 10px;">III.</span>
                <span class="noPrint" style="display: inline-block;">Tiba di </span><span style="display: inline-block; margin-left: 70px;">: {{ $datas['ttdSPPD']->tempatTiba2 }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 30px;">Pada Tanggal </span><span style="display: inline-block; margin-left: 36px;">: {{ \Carbon\Carbon::parse($datas['ttdSPPD']->tanggal2)->translatedFormat('d F Y') }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 30px;">Kepala </span><span style="display: inline-block; margin-left: 70px;">: {{ $datas['ttdSPPD']->jabatan2 }}</span><br>
                <br><br><br>
                <span style="display: inline-block; margin-left: 30px; max-width: 200px; text-align: center; display: block;">{{ $datas['ttdSPPD']->nama2 }}</span>
                <span class="noPrint" style="display: inline-block; margin-left: 50px;">NIP</span><span style="display: inline-block; margin-left: 10px;"> {{ $datas['ttdSPPD']->nip2 }}</span><br>

            </td>
            <td   style="text-align: left;  ">
                <span class="noPrint"style="display: inline-block; margin-left: 15px;">Berangkat dari </span><span style="display: inline-block; margin-left: 44px;">: {{ $datas['kegiatan']->kab_kota }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Ke </span><span style="display: inline-block; margin-left: 105px;">: Bandung</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Pada Tanggal </span><span style="display: inline-block; margin-left: 49px;">: {{ \Carbon\Carbon::parse($datas['kegiatan']->tgl_mulai)->translatedFormat('d F Y') }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Kepala </span><span style="display: inline-block; margin-left: 83px;">: {{ $datas['ttdSPPD']->jabatan1 }}</span><br>
                <br><br>
                <span style="display: inline-block; margin-left: 15px; max-width: 200px; text-align: center; display: block;">{{ $datas['ttdSPPD']->nama1 }}</span>
                <span class="noPrint" style="display: inline-block; margin-left: 20px;">NIP</span><span style="display: inline-block; margin-left: 10px;"> {{ $datas['ttdSPPD']->nip1 }} </span><br>

            </td>
        </tr>
        @else
        <tr>
            <td   style="text-align: left;  ">
                <span class="noPrint" style="display: inline-block; margin-right: 15px;">II.</span>
                <span class="noPrint" style="display: inline-block;">Tiba di </span><span style="display: inline-block; margin-left: 70px;">: {{ $datas['ttdSPPD']->tempatTiba2 ?? $datas['kegiatan']->kab_kota }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 30px;">Pada Tanggal </span><span style="display: inline-block; margin-left: 36px;">: {{ \Carbon\Carbon::parse($datas['ttdSPPD']->tanggal2 ?? $datas['kegiatan']->tgl_mulai)->translatedFormat('d F Y') }}</span><br>
                <span  class="noPrint" style="display: inline-block; margin-left: 30px;">Kepala </span><span style="display: inline-block; margin-left: 70px;">: {{ $datas['ttdSPPD']->jabatan2 }}</span><br>
                <br><br><br>
                <span style="display: inline-block; margin-left: 30px; max-width: 200px; text-align: center; display: block;">{{ $datas['ttdSPPD']->nama2 }}</span>
                <span class="noPrint" style="display: inline-block; margin-left: 50px;">NIP</span><span style="display: inline-block; margin-left: 10px;"> {{ $datas['ttdSPPD']->nip2 }}</span><br>

            </td>
            <td   style="text-align: left;  ">
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Berangkat dari </span><span style="display: inline-block; margin-left: 44px;">: {{ $datas['ttdSPPD']->tempatTiba2 ?? $datas['kegiatan']->kab_kota }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Ke </span><span style="display: inline-block; margin-left: 105px;">: {{ $datas['ttdSPPD']->tempatTujuan2 ?? $datas['kegiatan']->kab_kota }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Pada Tanggal </span><span style="display: inline-block; margin-left: 49px;">: {{ \Carbon\Carbon::parse($datas['ttdSPPD']->tanggalTujuan2 ?? $datas['kegiatan']->tgl_mulai)->translatedFormat('d F Y') }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Kepala </span><span style="display: inline-block; margin-left: 83px;">: {{ $datas['ttdSPPD']->jabatan2 }}</span><br>
                <br><br>
                <span style="display: inline-block; margin-left: 15px; max-width: 200px; text-align: center; display: block;">{{ $datas['ttdSPPD']->nama2 }}</span>
                <span class="noPrint" style="display: inline-block; margin-left: 20px;">NIP</span><span style="display: inline-block; margin-left: 10px;"> {{ $datas['ttdSPPD']->nip2 }} </span><br>
            </td>
        </tr>
        @endif

        @if($barisSPPD < 3)
        <!-- BARIS 4 -->
        <tr  class="noPrint">
            <td   style="text-align: left;  ">
                <span class="noPrint" style="display: inline-block; margin-right: 10px;">IV.</span>
                <span class="noPrint" style="display: inline-block;">Tiba di </span><span style="display: inline-block; margin-left: 70px;">: {{ $datas['ttdSPPD']->tempatTiba3 }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 30px;">Pada Tanggal </span><span style="display: inline-block; margin-left: 36px;">: {{ \Carbon\Carbon::parse($datas['ttdSPPD']->tanggal3)->translatedFormat('d F Y') }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 30px;">Kepala </span><span style="display: inline-block; margin-left: 70px;">: {{ $datas['ttdSPPD']->jabatan3 }}</span><br>
                <br><br>
                <span style="display: inline-block; margin-left: 30px; max-width: 200px; text-align: center; display: block;">{{ $datas['ttdSPPD']->nama3 }}</span>
                <span class="noPrint" style="display: inline-block; margin-left: 50px;">NIP</span><span style="display: inline-block; margin-left: 10px;"> {{ $datas['ttdSPPD']->nip3 }}</span><br>

            </td>
            <td   style="text-align: left;  ">
                <span style="display: inline-block; margin-left: 15px;">Berangkat dari </span><span style="display: inline-block; margin-left: 44px;">: {{ $datas['kegiatan']->kab_kota }}</span><br>
                <span style="display: inline-block; margin-left: 15px;">Ke </span><span style="display: inline-block; margin-left: 105px;">: Bandung</span><br>
                <span style="display: inline-block; margin-left: 15px;">Pada Tanggal </span><span style="display: inline-block; margin-left: 49px;">: {{ \Carbon\Carbon::parse($datas['kegiatan']->tgl_mulai)->translatedFormat('d F Y') }}</span><br>
                <span style="display: inline-block; margin-left: 15px;">Kepala </span><span style="display: inline-block; margin-left: 83px;">: {{ $datas['ttdSPPD']->jabatan1 }}</span><br>
                <br>
                <span style="display: inline-block; margin-left: 15px; max-width: 200px; text-align: center; display: block;">{{ $datas['ttdSPPD']->nama1 }}</span>
                <span class="noPrint" style="display: inline-block; margin-left: 20px;">NIP</span><span style="display: inline-block; margin-left: 10px;"> {{ $datas['ttdSPPD']->nip1 }} </span><br>

            </td>
        </tr>
        @else
        <tr>
            <td   style="text-align: left;  ">
                <span class="noPrint" style="display: inline-block; margin-right: 15px;">II.</span>
                <span class="noPrint" style="display: inline-block;">Tiba di </span><span style="display: inline-block; margin-left: 70px;">: {{ $datas['ttdSPPD']->tempatTiba3 ?? $datas['kegiatan']->kab_kota }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 30px;">Pada Tanggal </span><span style="display: inline-block; margin-left: 36px;">: {{ \Carbon\Carbon::parse($datas['ttdSPPD']->tanggal3)->translatedFormat('d F Y') }}</span><br>
                <span  class="noPrint" style="display: inline-block; margin-left: 30px;">Kepala </span><span style="display: inline-block; margin-left: 70px;">: {{ $datas['ttdSPPD']->jabatan3 }}</span><br>
                <br><br>
                <span style="display: inline-block; margin-left: 30px; max-width: 200px; text-align: center; display: block;">{{ $datas['ttdSPPD']->nama3 }}</span>
                <span class="noPrint" style="display: inline-block; margin-left: 50px;">NIP</span><span style="display: inline-block; margin-left: 10px;"> {{ $datas['ttdSPPD']->nip3 }}</span><br>

            </td>
            <td   style="text-align: left;  ">
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Berangkat dari </span><span style="display: inline-block; margin-left: 44px;">: {{ $datas['ttdSPPD']->tempatTiba3 ?? $datas['kegiatan']->kab_kota }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Ke </span><span style="display: inline-block; margin-left: 105px;">: {{ $datas['ttdSPPD']->tempatTujuan3 ?? $datas['kegiatan']->kab_kota }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Pada Tanggal </span><span style="display: inline-block; margin-left: 49px;">: {{ \Carbon\Carbon::parse($datas['ttdSPPD']->tanggalTujuan3 ?? $datas['kegiatan']->tgl_mulai)->translatedFormat('d F Y') }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Kepala </span><span style="display: inline-block; margin-left: 83px;">: {{ $datas['ttdSPPD']->jabatan3 }}</span><br>
                <br>
                <span style="display: inline-block; margin-left: 15px; max-width: 200px; text-align: center; display: block;">{{ $datas['ttdSPPD']->nama3 }}</span>
                <span class="noPrint" style="display: inline-block; margin-left: 20px;">NIP</span><span style="display: inline-block; margin-left: 10px;"> {{ $datas['ttdSPPD']->nip3 }} </span><br>
            </td>
        </tr>
        @endif

        @if($barisSPPD < 4)
        <!-- BARIS 5 -->
        <tr class="noPrint">
            <td   style="text-align: left;  ">
                <span class="noPrint" style="display: inline-block; margin-right: 15px;">V.</span>
                <span class="noPrint" style="display: inline-block;">Tiba di </span><span style="display: inline-block; margin-left: 70px;">: {{ $datas['kegiatan']->kab_kota }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 30px;">Pada Tanggal </span><span style="display: inline-block; margin-left: 36px;">: {{ \Carbon\Carbon::parse($datas['kegiatan']->tgl_mulai)->translatedFormat('d F Y') }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 30px;">Kepala </span><span style="display: inline-block; margin-left: 70px;">: {{ $datas['ttdSPPD']->jabatan4 }}</span><br>
                <br><br><br>
                <span style="display: inline-block; margin-left: 30px; max-width: 200px; text-align: center; display: block;">{{ $datas['ttdSPPD']->nama4 }}</span>
                <span class="noPrint" style="display: inline-block; margin-left: 50px;">NIP</span><span style="display: inline-block; margin-left: 10px;"> {{ $datas['ttdSPPD']->nip4 }}</span><br>

            </td>
            <td   style="text-align: left;  ">
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Berangkat dari </span><span style="display: inline-block; margin-left: 44px;">: {{ $datas['kegiatan']->kab_kota }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Ke </span><span style="display: inline-block; margin-left: 105px;">: Bandung</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Pada Tanggal </span><span style="display: inline-block; margin-left: 49px;">: {{ \Carbon\Carbon::parse($datas['kegiatan']->tgl_mulai)->translatedFormat('d F Y') }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Kepala </span><span style="display: inline-block; margin-left: 83px;">: {{ $datas['ttdSPPD']->jabatan1 }}</span><br>
                <br><br>
                <span style="display: inline-block; margin-left: 15px; max-width: 200px; text-align: center; display: block;">{{ $datas['ttdSPPD']->nama1 }}</span>
                <span class="noPrint" style="display: inline-block; margin-left: 20px;">NIP</span><span style="display: inline-block; margin-left: 10px;"> {{ $datas['ttdSPPD']->nip1 }} </span><br>

            </td>
        </tr>
        @else
        <br>
        <tr>
            <td   style="text-align: left;  ">
                <span class="noPrint" style="display: inline-block; margin-right: 15px;">II.</span>
                <span class="noPrint" style="display: inline-block;">Tiba di </span><span style="display: inline-block; margin-left: 70px;">: {{ $datas['ttdSPPD']->tempatTiba4 ?? $datas['kegiatan']->kab_kota }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 30px;">Pada Tanggal </span><span style="display: inline-block; margin-left: 36px;">: {{ \Carbon\Carbon::parse($datas['ttdSPPD']->tanggal4 ?? $datas['kegiatan']->tgl_mulai)->translatedFormat('d F Y') }}</span><br>
                <span  class="noPrint" style="display: inline-block; margin-left: 30px;">Kepala </span><span style="display: inline-block; margin-left: 70px;">: {{ $datas['ttdSPPD']->jabatan4 }}</span><br>
                <br><br><br>
                <span style="display: inline-block; margin-left: 30px; max-width: 200px; text-align: center; display: block;">{{ $datas['ttdSPPD']->nama4 }}</span>
                <span class="noPrint" style="display: inline-block; margin-left: 50px;">NIP</span><span style="display: inline-block; margin-left: 10px;"> {{ $datas['ttdSPPD']->nip4 }}</span><br>

            </td>
            <td   style="text-align: left;  ">
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Berangkat dari </span><span style="display: inline-block; margin-left: 44px;">: {{ $datas['ttdSPPD']->tempatTiba4 ?? $datas['kegiatan']->kab_kota }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Ke </span><span style="display: inline-block; margin-left: 105px;">: {{ $datas['ttdSPPD']->tempatTujuan4 ?? $datas['kegiatan']->kab_kota }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Pada Tanggal </span><span style="display: inline-block; margin-left: 49px;">: {{ \Carbon\Carbon::parse($datas['ttdSPPD']->tanggalTujuan4 ?? $datas['kegiatan']->tgl_mulai)->translatedFormat('d F Y') }}</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">Kepala </span><span style="display: inline-block; margin-left: 83px;">: {{ $datas['ttdSPPD']->jabatan4 }}</span><br>
                <br><br>
                <span style="display: inline-block; margin-left: 15px; max-width: 200px; text-align: center; display: block;">{{ $datas['ttdSPPD']->nama4 }}</span>
                <span class="noPrint" style="display: inline-block; margin-left: 20px;">NIP</span><span style="display: inline-block; margin-left: 10px;"> {{ $datas['ttdSPPD']->nip4 }} </span><br>
            </td>
        </tr>
        @endif


        <br><br>
        <!-- BARIS 6 -->
        <tr>
            <td   style="text-align: left;  ">
                <span class="noPrint" style="display: inline-block; margin-right: 10px; margin-top:-20px;">VI.</span>
                <span class="noPrint" style="display: inline-block; margin-top:-20px;">Tiba kembali di Bandung </span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 30px; margin-top:-20px;">(tempat kedudukan)</span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 30px; margin-top:-20px;">Pada tanggal </span><br>
                <div style="text-align: right; margin-top:-30px;">
                    <span style="display: inline-block; margin-right: 125px;">{{ \Carbon\Carbon::parse($datas['kegiatan']->tgl_selesai)->translatedFormat('d F Y') }}</span>
                </div>

                <span style="margin-left: 75px; margin-top:-30px;">
                Pejabat Pembuat Komitmen</span><br>
                <br><br><br>
                <span style="margin-left: 75px;">{{ $datas['pegawaiMaster']->nama_lengkap }}</span><br>
                <span style="margin-left: 75px;">NIP {{ $datas['pegawaiMaster']->NIP_NIK }}
                </span>
            </td>
            <td   style="text-align: left;  ">
                <span class="noPrint" style="display: inline-block; margin-left: 15px;">
                    Telah diperiksa dengan keterangan bahwa perjalanan tersebut atas perintahnya
                    dan semata-mata untuk kepentingan jabatan dalam waktu yang sesingkat-singkatnya.
                </span>
                <span style="margin-left: 75px;">
                Pejabat Pembuat Komitmen</span><br>
                <br><br><br>
                <span style="margin-left: 75px;">{{ $datas['pegawaiMaster']->nama_lengkap }}</span><br>
                <span style="margin-left: 75px;">NIP {{ $datas['pegawaiMaster']->NIP_NIK }}
                </span>
            </td>
        </tr>
        <!-- BARIS 7 -->
        <tr>
            <td   style="text-align: left;">
                <span class="noPrint" style="display: inline-block; margin-right: 15px;">VII.</span>
                <span class="noPrint" style="display: inline-block;">Catatan Lain-lain </span><br>

            </td>
            <td   style="text-align: left;">

            </td>
        </tr>
        <!-- BARIS 8 -->
        <tr>
            <td colspan="2"  style="text-align: left; ">
                <span class="noPrint" style="display: inline-block; margin-right: 10px;">VIII.</span>
                <span class="noPrint" style="display: inline-block;">PERHATIAN: </span><br>
                <span class="noPrint" style="display: inline-block; margin-left: 40px; font-size:14px;">
                    PPK yang menerbitkan SPPD, pegawai yang melakukan perjalanan dinas, para pejabat yang
                    mengesahkan tanggal berangkat/tiba, serta bendahara pengeluaran bertanggung jawab berdasarkan
                    peraturan-peraturan Keuangan Negara apabila Negara menderita rugi akibat kesalahan, kelalaian, dan kealpaannya.
                </span>

            </td>

        </tr>

        </table>
    </center>
</body>
</body>
</html>
