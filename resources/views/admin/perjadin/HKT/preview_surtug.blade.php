@extends('admin.templates.sidebar')

@section('contain')

<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        table tr td {
            font-size: 13px;
            font-family: 'Times New Roman', Times, serif;
        }

        table tr .judul {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
        }

        table tr .nomor {
            text-align: start;
            font-size: 12px;
            font-weight: bold;
            padding-left: 196px;
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

        .logo {
            max-width: 110px;
            height: auto;
            position: static;
            top: -5px;
            right: -50px;
        }
    </style>
</head>

<body>
    <div class="container-fluid px-4 py-4">
        <div class="row">
            <div class="col-md-12">
                <h4>Perjalanan Dinas / <span class="fw-bold">HKT Perjalanan Dinas</span></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card border-0 bg-secondary">
                    <div class="page-wrapper d-flex justify-content-between">
                    <div class="row">
                        <div class="col-4">
                            @csrf <!-- Tambahkan CSRF token jika menggunakan Laravel -->
                            <input type="hidden" name="idPerjadin" value="{{$perjadin->id}}">
                            <a href="{{ url('/perjadin-HKT/surtug/edit/' . $perjadin->id) }}" class="btn btn-dark btn-block">
                                Kembali
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ url('/perjadin-HKT/surtug/preview/' . $perjadin->id) }}" target="_blank" class="btn btn-primary btn-block">
                                Cetak
                            </a>
                        </div>
                        <div class="col-4">
                            <form action="{{ url('/c_perjadin_HKT') }}" method="POST">
                                @csrf <!-- Tambahkan CSRF token jika menggunakan Laravel -->
                                <input type="hidden" name="idPerjadin" value="{{$perjadin->id}}">
                                <input type="hidden" name="perjadinStatus" value="{{$perjadin->is_acceptHKT}}">
                                <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                            </form>
                        </div>
                    </div>


                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body content">
                        <div class="row page_content card-style">
                            <!-- Awal Dashboard -->
                            <center>
                                <!-- Kop dan Alamat Instansi -->
                                <table width="500">
                                    <tr>
                                        <td>
                                            <div style="text-align: right;">
                                            <img src="https://akunkeun.lldikti4.id/assets/images/logo-tut-wuri.png" class="logo" alt="TUT-WURI">
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
                                <table width="500">
                                    @foreach($surtugs as $surtug)
                                    <tr>
                                        <hr width="500">
                                    </tr>
                                    <tr>
                                        <td class="judul">SURAT TUGAS</td>
                                    </tr>
                                    <tr>
                                        <td class="nomor">Nomor: {{$surtug->nomor_surat }} </td>
                                    </tr>
                                    @endforeach
                                </table>
                                <table width="500">
                                    @foreach($surtugs as $surtug)
                                    <tr>
                                    <td class="perihal">Perihal: {!! $surtug->perihal !!} </td>
                                    </tr>
                                    @endforeach
                                </table>
                                <!-- Template 2: Identitas Pegawai -->
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
                                    $pesertaPegawaisArray = $pesertaPegawais->toArray();
                                    $pesertaNonPegawaisArray = $pesertaNonPegawais->toArray();
                                    $pengemudisArray = $pengemudis->toArray();

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

                                @if ($tipeSurtug->isTable==1)
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
                                                
                                                <td style="font-size: 14px;"> {{$peserta->NIP_NIK ?? '-'}}</td>
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



                                <!-- INI YANG SUPIR GK TAU BUTUH ATAU ENGGA KITA LIAT NTR -->
                                <!-- @foreach($pengemudis as $pengemudi)
                                <table width="500">
                                    <tr>
                                        <td class="template" width="30">{{$loop->iteration}} </td>
                                        <td class="template" width="150"> Nama</td>
                                        <td class="template">: {{$pengemudi->nama_lengkap }}</td>
                                    </tr>
                                    <tr>
                                        <td class="template" width="30"> </td>
                                        <td class="template" width="150"> NIP</td>
                                        <td class="template">: {{$pengemudi->NIP_NIK }}</td>
                                    </tr>
                                    <tr>
                                        <td class="template" width="30"> </td>
                                        <td class="template" width="150"> Pangkat/Gol. Ruang</td>
                                        <td class="template">: {{$pengemudi->pangkat }} / {{$pengemudi->golongan }}</td>
                                    </tr>
                                    <tr>
                                        <td class="template" width="30"> </td>
                                        <td class="template" width="150"> Jabatan</td>
                                        <td class="template">: {{$pengemudi->nama_jabatan }}</td>
                                    </tr>
                                </table>
                                @endforeach -->


                                <!-- Template 3: Paragraf 1 (Informasi Perjadin) -->
                                @foreach($surtugs as $surtug)
                                <table width="500">
                                    <tr>
                                        <td class="template">{!! $surtug->paragraf_1 !!}</td>
                                    </tr>
                                    <br>
                                </table>
                                @endforeach
                                <!-- Template 4: Paragraf 2 (Biaya DIPA) -->
                                @foreach($surtugs as $surtug)
                                <table width="500">
                                    <tr>
                                        <td class="template">{!! $surtug->paragraf_2 !!}</td>
                                    </tr>
                                    <br>
                                </table>
                                @endforeach
                                <!-- Template 5: Paragraf 3 (Laporan) -->
                                @foreach($surtugs as $surtug)
                                <table width="500">
                                    <tr>
                                        <td class="template">{!! $surtug->paragraf_3 !!}</td>
                                    </tr>
                                    <br>
                                </table>
                                @endforeach

                                <!-- Template 6: Tanda Tangan -->
                                <table width="500">
                                    <br>
                                    <tr>
                                        <td width="310"></td>
                                        <td align="left"><span id="tanggalNon"></span><br>Kepala Lembaga Layanan Pendidikan Tinggi Wilayah IV,<br><br><br><br><br><br>{{$pegawaiKepala->nama_lengkap}}<br>NIP {{$pegawaiKepala->NIP_NIK}}</td>
                                    </tr>
                                </table>
                            </center>
                            <!-- Akhir Dashboard -->

                            <script>
                                // Mendapatkan tanggal saat ini
                                var tanggalSekarang = new Date();
                                var options = { day: 'numeric', month: 'long', year: 'numeric' };

                                // Mengubah format tanggal sesuai keinginan
                                var tanggalFormat = tanggalSekarang.toLocaleDateString('id-ID', options);

                                // Menampilkan tanggal di dalam elemen dengan id "tanggal"
                                document.getElementById('tanggal').innerHTML = tanggalFormat;
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
@endsection
