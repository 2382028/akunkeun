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
            clear:both;
            display:flex;
            width: 500;              
            background-color:#000000;
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


<body style="padding: 1px">
    <!-- Awal Dashboard -->
        <center>
        <!-- Judul dan Nomor Surat -->
        <table width="550">
       
                <tbody>
                    <tr>
                        <td width="40%">&nbsp;</td>
                        <td width="100">
                            Tahun Anggaran  <br>
                            No. Bukti       <br>
                            Mata Anggaran   <br>


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
        @foreach($datas['sppd'] as $sppd)
        @endforeach
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
                    Tanggal       <br>


                </td>
                <td width="150">
                    : {{ $sppd->nomor_surat }}              <br>
                    : {{ \Carbon\Carbon::parse($sppd->tgl_surat_dibuat)->translatedFormat('d F Y') }}<br>
                </td>
            </tr>
        </table>


        <table class="rincian" width="10">
            <thead class="rincian">
              <tr>
               
                <th class="rincian" width="10">NO</th>
                <th class="rincian" width="150">RINCIAN BIAYA</th>
                <th class="rincian" width="50">JUMLAH</th>
                <th class="rincian" width="300">KETERANGAN</th>
              </tr>
            </thead>
            <tbody>
                 
                <tr>
                  <td class="rincian">1</td>
                  <td class="rincian"> Bandung - {{ $sppd->kabupaten_kota }} {{ $sppd->provinsi }}
                <br>
                  Uang Harian Fullday = Rp. {{ number_format($sppd->uang_harian_fullday, 0, ',', '.') }}
                  <td class="rincian">Rp. {{ number_format($sppd->uang_harian_fullday, 0, ',', '.') }}</td>
                  <td class="rincian baris-tiga">{{ $sppd->paragraf_1 }}</td>
                  </tr>

                  
 <?php
            function terbilang($angka){
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

            // Contoh penggunaan
            $jumlah = $sppd->uang_harian_fullday;
            $terbilang = terbilang($jumlah);
            ?>
            <tr>
            <td class="rincian"></td>
                  <td class="rincian">Jumlah</td>
                  <td class="rincian">Rp. <?php echo number_format($sppd->uang_harian_fullday, 0, ',', '.'); ?></td>
                  <td class="rincian"></td>
                </tr>
                <tr>
                <td class="rincian"></td>
                <td class="rincian" colspan="3">Terbilang : <?php echo ucwords($terbilang); ?> rupiah</td>
         </td>
                </tr>
                </tr>
                </tbody>
             
            </table>
          
            <table width="650">
            </td></td></td>
                <tr>
                @foreach($datas['pesertaPegawais'] as $pesertaPegawais)
                    <td width="200">
                        <br>Telah dibayar sejumlah
                        <br>Rp. {{ number_format($sppd->uang_harian_fullday, 0, ',', '.') }}
                        <br>Bendahara Pengeluaran,
                        <br><br><br><br><br><br>
                        Elfa Yuliatri
                        <br>
                        NIP 199107212009122001
                    </td>
                    <td width="200">
                       
                    </td>
                    <td width="1000">
                    Bandung,  {{ \Carbon\Carbon::parse($sppd->tgl_mulai)->translatedFormat('d F Y') }}
                        <br>Telah menerima jumlah
                        <br>uang sebesar Rp. {{ number_format($sppd->uang_harian_fullday, 0, ',', '.') }}
                        <br>Yang Menerima,
                        <br><br><br><br><br><br>
                       
                       
                        {{$pesertaPegawais->nama_lengkap }}<br>
                        {{$pesertaPegawais->NIP_NIK }}
                    </td>
                    
                    </td>   
                    @endforeach           
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
                    Ditetapkan sejumlah  <br>
                    Yang telah dibayarkan semula       <br>
                    Sisa kurang / lebih   <br>


                </td>
                <td width="325">
                    Rp. <br>
                    Rp. {{ number_format($sppd->uang_harian_fullday, 0, ',', '.') }}<br>
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
   
    <!-- Akhir Dashboard -->


    <!-- Awal Footer -->
    <!-- Akhir Footer -->
</body>


 <!-- Awal Dashboard --> 
 <center>
        <!-- Judul dan Nomor Surat -->
        <table width="550">
                   
                <tbody>
                    <tr>
                        <td width="40%">&nbsp;</td>
                        <td width="100">
                            Tahun Anggaran  <br>
                            No. Bukti       <br>
                            Mata Anggaran   <br>
                        </td>
                        <td width="200">
                            : 2024 <br>
                            :  <br>
                            :   @foreach ($datas['akuns'] as $akun)
                                @endforeach
                                {{ $akun->kode_satker }}.{{ $akun->kode_program }}.{{ $akun->kode_kegiatan }}.{{ $akun->kode_output }}.{{ $akun->kode_sub_output }}.{{ $akun->kode_komponen }}.{{ $akun->kode_sub_kegiatan }}.{{ $akun->kode_akun }} 
                            
                        </td>
                    </tr>
                </tbody>
              
        </table>
        @foreach($datas['sppd'] as $sppd)
        @endforeach
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
                    Tanggal       <br>


                </td>
                <td width="150">
                    :  {{ $sppd->nomor_surat }}              <br>
                    : {{ \Carbon\Carbon::parse($sppd->tgl_surat_dibuat)->translatedFormat('d F Y') }}<br>
                </td>
            </tr>
        </table>


        <table class="rincian" width="10">
            <thead class="rincian">
              <tr>
               
                <th class="rincian" width="10">NO</th>
                <th class="rincian" width="150">RINCIAN BIAYA</th>
                <th class="rincian" width="50">JUMLAH</th>
                <th class="rincian" width="300">KETERANGAN</th>
              </tr>
            </thead>
            <tbody>
                 
                <tr>
                  <td class="rincian">1</td>
                  <td class="rincian"> Bandung - {{ $sppd->kabupaten_kota }} {{ $sppd->provinsi }}
                <br>
                  Uang Harian = Rp. {{ number_format($sppd->uang_harian_fullday, 0, ',', '.') }}
                  <td class="rincian">Rp. {{ number_format($sppd->uang_harian_fullday, 0, ',', '.') }}</td>
                  <td class="rincian baris-tiga">{{ $sppd->paragraf_1 }}</td>
                  </tr>
                <tr class = "baris-dua"> 
                    <td class="rincian">2</td>
                    <td class="rincian"> Biaya BBM </td>
                    <td class="rincian"> Rp. </td>
                    <td> </td>
                </tr>   
                <tr class = "baris-tiga">
                    <td class="rincian">3</td>
                    <td class="rincian"> Biaya E-tol </td>
                    <td class="rincian"> Rp. </td>
                    <td></td>
                </tr>
            <?php
            $jumlah = $sppd->uang_harian_fullday;
            $terbilang = terbilang($jumlah);
            ?>
            <tr>
            <td class="rincian"></td>
                  <td class="rincian">Jumlah</td>
                  <td class="rincian">Rp. <?php echo number_format($sppd->uang_harian_fullday, 0, ',', '.'); ?></td>
                  <td class="rincian"></td>
                </tr>
                <tr>
                <td class="rincian"></td>
                <td class="rincian" colspan="3">Terbilang : <?php echo ucwords($terbilang); ?> rupiah</td>
         </td>
                </tr>
                </tbody>
             
            </table>
          
            <table width="650">
            </td></td></td>
                <tr>
               
                    <td width="200">
                        <br>Telah dibayar sejumlah
                        <br>Rp. {{ number_format($sppd->uang_harian_fullday, 0, ',', '.') }}
                        <br>Bendahara Pengeluaran,
                        <br><br><br><br><br><br>
                        Elfa Yuliatri
                        <br>
                        NIP 199107212009122001
                    </td>
                    <td width="200">
                       
                    </td>
                    @foreach($datas['pesertaPegawais'] as $pesertaPegawais)
                    @endforeach 
                    <td width="1000">
                        Bandung,  {{ \Carbon\Carbon::parse($sppd->tgl_mulai)->translatedFormat('d F Y') }}
                        <br>Telah menerima jumlah
                        <br>uang sebesar Rp. {{ number_format($sppd->uang_harian_fullday, 0, ',', '.') }}
                        <br>Yang Menerima,
                        <br><br><br><br><br><br>
                       
                       
                        {{$pesertaPegawais->nama_lengkap}}<br>
                        {{$pesertaPegawais->NIP_NIK }}
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
                    Ditetapkan sejumlah  <br>
                    Yang telah dibayarkan semula       <br>
                    Sisa kurang / lebih   <br>


                </td>
                <td width="325">
                    Rp. <br>
                    Rp. {{ number_format($sppd->uang_harian_fullday, 0, ',', '.') }}<br>
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
   
    <!-- Akhir Dashboard -->


    <!-- Awal Footer -->
    <!-- Akhir Footer -->
</body>


</html>