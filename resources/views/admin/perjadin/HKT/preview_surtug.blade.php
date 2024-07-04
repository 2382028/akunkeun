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
                                    <td class="perihal">Perihal: {{$surtug->perihal }} </td>
                                    </tr>
                                    @endforeach
                                </table>
                                <!-- Template 2: Identitas Pegawai -->
                                @foreach($pesertaPegawais as $pesertaPegawai)
                                <table width="500">
                                    <tr>
                                        <td class="template" width="30">{{$loop->iteration}} </td>
                                        <td class="template" width="150"> Nama</td>
                                        <td class="template">: {{$pesertaPegawai->nama_lengkap }}</td>
                                    </tr>
                                    <tr>
                                        <td class="template" width="30"> </td>
                                        <td class="template" width="150"> NIP</td>
                                        <td class="template">: {{$pesertaPegawai->NIP_NIK }}</td>
                                    </tr>
                                    <tr>
                                        <td class="template" width="30"> </td>
                                        <td class="template" width="150"> Pangkat/Gol. Ruang</td>
                                        <td class="template">: {{$pesertaPegawai->pangkat }} / {{$pesertaPegawai->golongan }} </td>
                                    </tr>
                                    <tr>
                                        <td class="template" width="30"> </td>
                                        <td class="template" width="150"> Jabatan</td>
                                        <td class="template">: {{$pesertaPegawai->status_pegawai }}</td>
                                    </tr>
                                </table>
                                @endforeach

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
                                        <td class="template">{{$surtug->paragraf_1}}</td>
                                    </tr>
                                    <br>
                                </table>
                                @endforeach 
                                <!-- Template 4: Paragraf 2 (Biaya DIPA) -->
                                @foreach($surtugs as $surtug)
                                <table width="500">
                                    <tr>
                                        <td class="template">{{$surtug->paragraf_2}}</td>
                                    </tr>
                                    <br>
                                </table>
                                @endforeach
                                <!-- Template 5: Paragraf 3 (Laporan) -->
                                @foreach($surtugs as $surtug)
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
                                    <tr>
                                        <td width="310"></td>
                                        <td align="left"><span id="tanggal"></span><br>Kepala Lembaga Layanan Pendidikan Tinggi Wilayah IV,<br><br><br><br><br><br>M. Samsuri<br>NIP 197901142003121001</td>
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