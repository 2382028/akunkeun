<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="widht=device-widht, initial-scale=10" />
    <style>
        .rincian {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 1px;
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
            clear: both;
            display: flex;
            width: 500;
            background-color: #000000;
            height: 1px;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            max-width: fit-content;
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

<!-- RPD PEGAWAI -->
@foreach($datas['pesertaPegawais'] as $pesertaPegawai)
@php
$keberangkatan = \Carbon\Carbon::parse($pesertaPegawai->tgl_keberangkatan);
$selesai = \Carbon\Carbon::parse($pesertaPegawai->tgl_selesai);
$hariPerjalanan = $selesai->diffInDays($keberangkatan)+1;
$terbilangHari = trim(terbilang($hariPerjalanan));
$numRow=2;
@endphp

<body style="padding: 1px">
    <!-- Awal Dashboard -->
    <center>
        <!-- Judul dan Nomor Surat -->
        <table width="550">
            <tbody>
                <tr>
                    <td width="40%">&nbsp;</td>
                    <td width="100">
                        Tahun Anggaran <br>
                        No. Bukti <br>
                        Mata Anggaran <br>
                    </td>
                    <td width="200">
                        : 2024 <br>
                        : <br>
                        : @foreach ($datas['akuns'] as $akun)
                        @endforeach
                        {{ $akun->kode_satker }}.{{ $akun->kode_program }}.{{ $akun->kode_kegiatan }}.{{ $akun->kode_output }}.{{ $akun->kode_sub_output }}.{{ $akun->kode_komponen }}.{{ $akun->kode_sub_kegiatan }}.{{ $akun->kode_akun }} <br>
                    </td>
                </tr>
            </tbody>
        </table>
        <table width="500">
            <br>
            <tr>
                <td class="judul">RINCIAN PERJALANAN DINAS</td>
            </tr>
        </table>

        <table width="650">
            <tr>
                <td width="50">
                    Lampiran SPD Nomor <br>
                    Tanggal <br>
                </td>
                <td width="150">
                    : {{ $pesertaPegawai->nomor_surat }} <br>
                    : {{ \Carbon\Carbon::parse($pesertaPegawai->tgl_surat_dibuat)->translatedFormat('d F Y') }}<br>
                </td>
            </tr>
        </table>


        <table class="rincian" width="10">
            <thead class="rincian">
                <tr>
                    <th class="rincian th-lg-percent" width="10">NO</th>
                    <th class="rincian th-lg-percent" width="150">RINCIAN BIAYA</th>
                    <th class="rincian th-lg-percent" width="62">JUMLAH</th>
                    <th class="rincianth-lg-percent" width="290">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="rincian">
                        <center>1</center>
                    </td>
                    <td class="rincian">Bandung - {{ $pesertaPegawai->kabupaten_kota }} {{ $pesertaPegawai->provinsi }}
                        <br>
                        @if($pesertaPegawai->uang_harian != 0)
                        Uang Harian = Rp. {{ number_format($pesertaPegawai->uang_harian, 0, ',', '.') }}<br>
                        @endif

                        @if($pesertaPegawai->uang_harian_fullday != 0)
                        Uang Harian Fullday = Rp. {{ number_format($pesertaPegawai->uang_harian_fullday, 0, ',', '.') }}<br>
                        @endif

                        @if($pesertaPegawai->uang_harian_fullboard != 0)
                        Uang Harian Fullboard = Rp. {{ number_format($pesertaPegawai->uang_harian_fullboard, 0, ',', '.') }}<br>
                        @endif

                        @if($pesertaPegawai->uang_representasi != 0)
                        Uang Representasi = Rp. {{ number_format($pesertaPegawai->uang_representasi, 0, ',', '.') }}<br>
                        @endif
                    </td>
                    <td class="rincian">&nbsp;Rp. {{ number_format($pesertaPegawai->jumlah_harga, 0, ',', '.') }}</td>
                    <td class="rincian baris-tiga">
                        Mengikuti {{ $pesertaPegawai->nama_kegiatan }}
                        selama {{ $hariPerjalanan }} ({{$terbilangHari}}) hari
                    </td>
                </tr>
                @php
                $jumlah = $pesertaPegawai->jumlah_harga;

                $groupedFasilitas = [];
                foreach($datas['fasilitasPegawais'] as $fasilitasPegawai) {
                if($fasilitasPegawai->pegawai_id == $pesertaPegawai->id) {
                if(!isset($groupedFasilitas[$fasilitasPegawai->nama_kebutuhan])) {
                $groupedFasilitas[$fasilitasPegawai->nama_kebutuhan] = [
                'jumlah_frekuensi' => 0,
                'jumlah_harga' => 0,
                'satuan' => $fasilitasPegawai->satuan,
                'ket' => ''
                ];
                }
                $groupedFasilitas[$fasilitasPegawai->nama_kebutuhan]['jumlah_frekuensi'] += $fasilitasPegawai->jumlah_frekuensi;
                $groupedFasilitas[$fasilitasPegawai->nama_kebutuhan]['jumlah_harga'] += $fasilitasPegawai->jumlah_harga;

                // Tambahkan jumlah_harga ke total
                $jumlah += $fasilitasPegawai->jumlah_harga;

                // Gabungkan 'ket' dengan tanda koma jika tidak kosong
                if (!empty($fasilitasPegawai->ket)) {
                if (!empty($groupedFasilitas[$fasilitasPegawai->nama_kebutuhan]['ket'])) {
                $groupedFasilitas[$fasilitasPegawai->nama_kebutuhan]['ket'] .= ', ';
                }
                $groupedFasilitas[$fasilitasPegawai->nama_kebutuhan]['ket'] .= $fasilitasPegawai->ket;
                }
                }
                }

                $terbilang = terbilang($jumlah);
                @endphp

                @foreach($groupedFasilitas as $nama_kebutuhan => $fasilitas)
                <tr>
                    <td class="rincian">
                        <center>{{ $numRow }}</center>
                    </td>
                    <td class="rincian">
                        {{ $nama_kebutuhan }}, {{ $fasilitas['jumlah_frekuensi'] }} {{ $fasilitas['satuan'] }}
                    </td>
                    <td class="rincian">&nbsp;Rp. {{ number_format($fasilitas['jumlah_harga'], 0, ',', '.') }}</td>
                    <td class="rincian baris-tiga">
                        {{ $fasilitas['ket'] }}
                    </td>
                </tr>
                @php
                $numRow++;
                @endphp
                @endforeach

                <tr>
                    <td class="rincian"></td>
                    <td class="rincian"><b>Jumlah</b></td>
                    <td class="rincian"><b>&nbsp;Rp. <?php echo number_format($jumlah, 0, ',', '.'); ?></b></td>
                    <td class="rincian"></td>
                </tr>
                <tr>
                    <td class="rincian"></td>
                    <td class="rincian" colspan="3">Terbilang : <?php echo ucwords($terbilang); ?> Rupiah</td>
                    </td>
                </tr>
                </tr>
            </tbody>
        </table>

        <table width="650">
            <tr>
                <td width="200">
                    <br>Telah dibayar sejumlah
                    <br>Rp. {{ number_format($jumlah, 0, ',', '.') }}
                    <br>Bendahara Pengeluaran,
                    <br><br><br><br><br><br>
                    Elfa Yuliatri
                    <br>
                    NIP 199107212009122001
                </td>
                <td width="200"></td>
                <td width="1000">
                    Bandung, {{ \Carbon\Carbon::parse($pesertaPegawai->tgl_mulai)->translatedFormat('d F Y') }}
                    <br>Telah menerima jumlah
                    <br>uang sebesar Rp. {{ number_format($jumlah, 0, ',', '.') }}
                    <br>Yang Menerima,
                    <br><br><br><br><br><br>
                    {{$pesertaPegawai->nama_lengkap }}<br>
                    {{$pesertaPegawai->NIP_NIK }}
                </td>
                </td>
            </tr>

        </table>

        <hr>

        <table width="500">
            <tr>
                <td class="judul">PERHITUNGAN SPPD RAMPUNG</td>
            </tr>
        </table>

        <table width="650">
            <td width="325">
                Ditetapkan sejumlah <br>
                Yang telah dibayarkan semula <br>
                Sisa kurang / lebih <br>
            </td>
            <td width="325">
                Rp. <br>
                Rp. {{ number_format($jumlah, 0, ',', '.') }}<br>
                Rp. <br>
            </td>
            <br>
        </table>


        <table width="650">
            <br>
            <tr>
                <td width="325"></td>
                <td align="left">
                    Setuju dibebankan pada mata anggaran berkenaan,
                    <br>a.n. Kuasa Pengguna Anggaran
                    <br>Pejabat Pembuat Komitmen
                    <br><br><br><br><br><br>
                    Syahrir Lubis, S.T, M.Kom.<br>
                    NIP 198104082009121004
                </td>
            </tr>
        </table>

    </center>

</body>
@endforeach

<!-- RPD NONPEGAWAI -->
@foreach($datas['pesertaNonPegawais'] as $pesertaNonPegawai)
@php
$keberangkatan = \Carbon\Carbon::parse($pesertaPegawai->tgl_keberangkatan);
$selesai = \Carbon\Carbon::parse($pesertaPegawai->tgl_selesai);
$hariPerjalanan = $selesai->diffInDays($keberangkatan)+1;
$terbilangHari = trim(terbilang($hariPerjalanan));
@endphp

<body style="padding: 1px">
    <!-- Awal Dashboard -->
    <center>
        <!-- Judul dan Nomor Surat -->
        <table width="550">
            <tbody>
                <tr>
                    <td width="40%">&nbsp;</td>
                    <td width="100">
                        Tahun Anggaran <br>
                        No. Bukti <br>
                        Mata Anggaran <br>
                    </td>
                    <td width="200">
                        : 2024 <br>
                        : <br>
                        : @foreach ($datas['akuns'] as $akun)
                        @endforeach
                        {{ $akun->kode_satker }}.{{ $akun->kode_program }}.{{ $akun->kode_kegiatan }}.{{ $akun->kode_output }}.{{ $akun->kode_sub_output }}.{{ $akun->kode_komponen }}.{{ $akun->kode_sub_kegiatan }}.{{ $akun->kode_akun }} <br>
                    </td>
                </tr>
            </tbody>
        </table>
        <table width="500">
            <br>
            <tr>
                <td class="judul">RINCIAN PERJALANAN DINAS</td>
            </tr>
        </table>
        <table width="650">
            <tr>
                <td width="50">
                    Lampiran SPD Nomor <br>
                    Tanggal <br>
                </td>
                <td width="150">
                    : {{ $pesertaNonPegawai->nomor_surat }} <br>
                    : {{ \Carbon\Carbon::parse($pesertaNonPegawai->tgl_surat_dibuat)->translatedFormat('d F Y') }}<br>
                </td>
            </tr>
        </table>
        <table class="rincian" width="10">
            <thead class="rincian">
                <tr>
                    <th class="rincian" width="10">NO</th>
                    <th class="rincian" width="150">RINCIAN BIAYA</th>
                    <th class="rincian" width="62">JUMLAH</th>
                    <th class="rincian" width="290">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="rincian">1</td>
                    <td class="rincian">Bandung - {{ $pesertaNonPegawai->kabupaten_kota }} {{ $pesertaNonPegawai->provinsi }}
                        <br>
                        @if($pesertaNonPegawai->uang_harian != 0)
                        Uang Harian = Rp. {{ number_format($pesertaNonPegawai->uang_harian, 0, ',', '.') }}<br>
                        @endif

                        @if($pesertaNonPegawai->uang_harian_fullday != 0)
                        Uang Harian Fullday = Rp. {{ number_format($pesertaNonPegawai->uang_harian_fullday, 0, ',', '.') }}<br>
                        @endif

                        @if($pesertaNonPegawai->uang_harian_fullboard != 0)
                        Uang Harian Fullboard = Rp. {{ number_format($pesertaNonPegawai->uang_harian_fullboard, 0, ',', '.') }}<br>
                        @endif

                        @if($pesertaNonPegawai->uang_representasi != 0)
                        Uang Representasi = Rp. {{ number_format($pesertaNonPegawai->uang_representasi, 0, ',', '.') }}<br>
                        @endif
                    </td>
                    <td class="rincian">Rp. {{ number_format($pesertaNonPegawai->jumlah_harga, 0, ',', '.') }}</td>
                    <td class="rincian baris-tiga">
                        Mengikuti {{ $pesertaNonPegawai->nama_kegiatan }}
                        selama {{ $hariPerjalanan }} ({{$terbilangHari}}) hari
                    </td>
                </tr>

                <?php
                // Contoh penggunaan
                $jumlah = $pesertaNonPegawai->jumlah_harga;
                $terbilang = terbilang($jumlah);
                ?>
                <tr>
                    <td class="rincian"></td>
                    <td class="rincian">Jumlah</td>
                    <td class="rincian">&nbsp;Rp. <?php echo number_format($pesertaNonPegawai->jumlah_harga, 0, ',', '.'); ?></td>
                    <td class="rincian"></td>
                </tr>
                <tr>
                    <td class="rincian"></td>
                    <td class="rincian" colspan="3">Terbilang : <?php echo ucwords($terbilang); ?> Rupiah</td>
                    </td>
                </tr>
                </tr>
            </tbody>

        </table>

        <table width="650">
            <tr>
                <td width="200">
                    <br>Telah dibayar sejumlah
                    <br>Rp. {{ number_format($pesertaNonPegawai->jumlah_harga, 0, ',', '.') }}
                    <br>Bendahara Pengeluaran,
                    <br><br><br><br><br><br>
                    Elfa Yuliatri
                    <br>
                    NIP 199107212009122001
                </td>
                <td width="200">
                </td>
                <td width="1000">
                    Bandung, {{ \Carbon\Carbon::parse($pesertaNonPegawai->tgl_mulai)->translatedFormat('d F Y') }}
                    <br>Telah menerima jumlah
                    <br>uang sebesar Rp. {{ number_format($jumlah, 0, ',', '.') }}
                    <br>Yang Menerima,
                    <br><br><br><br><br><br>
                    {{$pesertaNonPegawai->nama_lengkap }}<br>
                    {{$pesertaNonPegawai->NIP_NIK }}
                </td>
            </tr>
        </table>

        <hr>

        <table width="500">
            <tr>
                <td class="judul">PERHITUNGAN SPPD RAMPUNG</td>
            </tr>
        </table>

        <table width="650">
            <td width="325">
                Ditetapkan sejumlah <br>
                Yang telah dibayarkan semula <br>
                Sisa kurang / lebih <br>
            </td>
            <td width="325">
                Rp. <br>
                Rp. {{ number_format($jumlah, 0, ',', '.') }}<br>
                Rp. <br>
            </td>
            <br>
        </table>

        <table width="650">
            <br>
            <tr>
                <td width="325"></td>
                <td align="left">
                    Setuju dibebankan pada mata anggaran berkenaan,
                    <br>a.n. Kuasa Pengguna Anggaran
                    <br>Pejabat Pembuat Komitmen
                    <br><br><br><br><br><br>
                    Syahrir Lubis, S.T, M.Kom.<br>
                    NIP 198104082009121004
                </td>
            </tr>
        </table>
    </center>
</body>
@endforeach


<!-- RPD PENGEMUDI -->
@foreach($datas['pengemudis'] as $pengemudi)
@php
$keberangkatan = \Carbon\Carbon::parse($pengemudi->tgl_keberangkatan);
$selesai = \Carbon\Carbon::parse($pengemudi->tgl_selesai);
$hariPerjalanan = $selesai->diffInDays($keberangkatan)+1;
$terbilangHari = trim(terbilang($hariPerjalanan));
$numRow=2;
@endphp

<body style="padding: 1px">
    <!-- Awal Dashboard -->
    <center>
        <!-- Judul dan Nomor Surat -->
        <table width="550">
            <tbody>
                <tr>
                    <td width="40%">&nbsp;</td>
                    <td width="100">
                        Tahun Anggaran <br>
                        No. Bukti <br>
                        Mata Anggaran <br>
                    </td>
                    <td width="200">
                        : 2024 <br>
                        : <br>
                        : @foreach ($datas['akuns'] as $akun)
                        @endforeach
                        {{ $akun->kode_satker }}.{{ $akun->kode_program }}.{{ $akun->kode_kegiatan }}.{{ $akun->kode_output }}.{{ $akun->kode_sub_output }}.{{ $akun->kode_komponen }}.{{ $akun->kode_sub_kegiatan }}.{{ $akun->kode_akun }} <br>
                    </td>
                </tr>
            </tbody>
        </table>
        <table width="500">
            <br>
            <tr>
                <td class="judul">RINCIAN PERJALANAN DINAS</td>
            </tr>
        </table>


        <table width="650">
            <tr>
                <td width="50">
                    Lampiran SPD Nomor <br>
                    Tanggal <br>


                </td>
                <td width="150">
                    : {{ $pengemudi->nomor_surat }} <br>
                    : {{ \Carbon\Carbon::parse($pengemudi->tgl_surat_dibuat)->translatedFormat('d F Y') }}<br>
                </td>
            </tr>
        </table>


        <table class="rincian" width="10">
            <thead class="rincian">
                <tr>

                    <th class="rincian th-lg-percent" width="10">NO</th>
                    <th class="rincian th-lg-percent" width="150">RINCIAN BIAYA</th>
                    <th class="rincian th-lg-percent" width="62">JUMLAH</th>
                    <th class="rincianth-lg-percent" width="290">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td class="rincian">
                        <center>1</center>
                    </td>
                    <td class="rincian">Bandung - {{ $pengemudi->kabupaten_kota }} {{ $pengemudi->provinsi }}
                        <br>
                        @if($pengemudi->uang_harian != 0)
                        Uang Harian = Rp. {{ number_format($pengemudi->uang_harian, 0, ',', '.') }}<br>
                        @endif

                        @if($pengemudi->uang_harian_fullday != 0)
                        Uang Harian Fullday = Rp. {{ number_format($pengemudi->uang_harian_fullday, 0, ',', '.') }}<br>
                        @endif

                        @if($pengemudi->uang_harian_fullboard != 0)
                        Uang Harian Fullboard = Rp. {{ number_format($pengemudi->uang_harian_fullboard, 0, ',', '.') }}<br>
                        @endif

                        @if($pengemudi->uang_representasi != 0)
                        Uang Representasi = Rp. {{ number_format($pengemudi->uang_representasi, 0, ',', '.') }}<br>
                        @endif
                    </td>
                    <td class="rincian">&nbsp;Rp. {{ number_format($pengemudi->jumlah_harga, 0, ',', '.') }}</td>
                    <td class="rincian baris-tiga">
                        Mengikuti {{ $pengemudi->nama_kegiatan }}
                        selama {{ $hariPerjalanan }} ({{$terbilangHari}}) hari
                    </td>
                </tr>
                @php
                $jumlah = $pengemudi->jumlah_harga;

                $groupedFasilitas = [];
                foreach($datas['fasilitasPegawais'] as $fasilitasPegawai) {
                if($fasilitasPegawai->pegawai_id == $pengemudi->id) {
                if(!isset($groupedFasilitas[$fasilitasPegawai->nama_kebutuhan])) {
                $groupedFasilitas[$fasilitasPegawai->nama_kebutuhan] = [
                'jumlah_frekuensi' => 0,
                'jumlah_harga' => 0,
                'satuan' => $fasilitasPegawai->satuan,
                'ket' => ''
                ];
                }
                $groupedFasilitas[$fasilitasPegawai->nama_kebutuhan]['jumlah_frekuensi'] += $fasilitasPegawai->jumlah_frekuensi;
                $groupedFasilitas[$fasilitasPegawai->nama_kebutuhan]['jumlah_harga'] += $fasilitasPegawai->jumlah_harga;

                // Tambahkan jumlah_harga ke total
                $jumlah += $fasilitasPegawai->jumlah_harga;

                // Gabungkan 'ket' dengan tanda koma jika tidak kosong
                if (!empty($fasilitasPegawai->ket)) {
                if (!empty($groupedFasilitas[$fasilitasPegawai->nama_kebutuhan]['ket'])) {
                $groupedFasilitas[$fasilitasPegawai->nama_kebutuhan]['ket'] .= ', ';
                }
                $groupedFasilitas[$fasilitasPegawai->nama_kebutuhan]['ket'] .= $fasilitasPegawai->ket;
                }
                }
                }

                $terbilang = terbilang($jumlah);
                @endphp

                @foreach($groupedFasilitas as $nama_kebutuhan => $fasilitas)
                <tr>
                    <td class="rincian">
                        <center>{{ $numRow }}</center>
                    </td>
                    <td class="rincian">
                        {{ $nama_kebutuhan }}, {{ $fasilitas['jumlah_frekuensi'] }} {{ $fasilitas['satuan'] }}
                    </td>
                    <td class="rincian">&nbsp;Rp. {{ number_format($fasilitas['jumlah_harga'], 0, ',', '.') }}</td>
                    <td class="rincian baris-tiga">
                        {{ $fasilitas['ket'] }}
                    </td>
                </tr>
                @php
                $numRow++;
                @endphp
                @endforeach







                <tr>
                    <td class="rincian"></td>
                    <td class="rincian"><b>Jumlah</b></td>
                    <td class="rincian">&nbsp;<b>Rp. <?php echo number_format($jumlah, 0, ',', '.'); ?></b></td>
                    <td class="rincian"></td>
                </tr>
                <tr>
                    <td class="rincian"></td>
                    <td class="rincian" colspan="3">Terbilang : <?php echo ucwords($terbilang); ?> Rupiah</td>
                    </td>
                </tr>
                </tr>
            </tbody>

        </table>

        <table width="650">
            </td>
            </td>
            </td>
            <tr>

                <td width="200">
                    <br>Telah dibayar sejumlah
                    <br>Rp. {{ number_format($jumlah, 0, ',', '.') }}
                    <br>Bendahara Pengeluaran,
                    <br><br><br><br><br><br>
                    Elfa Yuliatri
                    <br>
                    NIP 199107212009122001
                </td>
                <td width="200">

                </td>
                <td width="1000">
                    Bandung, {{ \Carbon\Carbon::parse($pengemudi->tgl_mulai)->translatedFormat('d F Y') }}
                    <br>Telah menerima jumlah
                    <br>uang sebesar Rp. {{ number_format($jumlah, 0, ',', '.') }}
                    <br>Yang Menerima,
                    <br><br><br><br><br><br>


                    {{$pengemudi->nama_lengkap }}<br>
                    {{$pengemudi->NIP_NIK }}
                </td>

                </td>
            </tr>

        </table>

        <hr>

        <table width="500">
            <tr>
                <td class="judul">PERHITUNGAN SPPD RAMPUNG</td>
            </tr>
        </table>

        <table width="650">
            <td width="325">
                Ditetapkan sejumlah <br>
                Yang telah dibayarkan semula <br>
                Sisa kurang / lebih <br>


            </td>
            <td width="325">
                Rp. <br>
                Rp. {{ number_format($jumlah, 0, ',', '.') }}<br>
                Rp. <br>


            </td>
            <br>
        </table>


        <table width="650">
            <br>
            <tr>
                <td width="325"></td>
                <td align="left">
                    Setuju dibebankan pada mata anggaran berkenaan,
                    <br>a.n. Kuasa Pengguna Anggaran
                    <br>Pejabat Pembuat Komitmen
                    <br><br><br><br><br><br>



                    Syahrir Lubis, S.T, M.Kom.<br>
                    NIP 198104082009121004

            </tr>
        </table>

    </center>

</body>
@endforeach
</body>
</html>