@extends('admin.templates.sidebar')

@section('contain')

<!DOCTYPE html>
<html lang="en">

<head>
    <style>
         table tr td {
            font-size: 10px;
            font-family: 'Times New Roman', Times, serif;
            border: none;
        }

        table tr .judul {
            text-align: center;
            font-size: 10px;
            font-weight: bold;

        }

        table tr .nomor {
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            padding-left: 366px;

        }

        hr {
            display: none;
        }

        .no-print {
            display: none;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid px-4 py-4">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card border-0 bg-secondary ">
                    <div class="row">
                        <div class="col-md-6 d-flex gap-2">
                            @csrf
                            <input type="hidden" name="" value="">
                            <button
                                class="btn btn-dark btn-sm"
                                onClick="window.history.back()">
                                Kembali
                            </button>
                            <a href="{{ url('/kegiatan-pdf/preview?perangkat='.$perangkat.'&idKegiatan='.$idKegiatan.'&judul='.$judul.'&tanggal='.$tanggal.'&MAK='.$MAK.'&pegawaiMaster='.urlencode(json_encode($pegawaiMaster)).'&pegawaiBendahara='.urlencode(json_encode($pegawaiBendahara)).'&data='.urlencode(json_encode($datas)).'&subTotal_jumlah='.$subTotal_jumlah.'&subTotal_pph='.$subTotal_pph.'&subTotal_nominal='.$subTotal_nominal) }}"
                               target="_blank"
                               class="btn btn-primary btn-sm">
                                Cetak
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card ">
                    <div class="card-body content ">

                            <!-- Awal Dashboard -->
                            <center>

                                <table width="500">
                                    <tr>
                                        <hr width="500">
                                    </tr>
                                    <tr>
                                        <td class="nomor">Tahun Anggaran : {{ \App\Models\Versi::find(session('versi'))->versi ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="nomor">Nomor Bukti :  </td>
                                    </tr>
                                    <tr>
                                        <td class="nomor">MAK :  {{$MAK}}</td>
                                    </tr>

                                </table>


                                <table width="500">
                                    <br>
                                    <tr>
                                        <hr width="500">
                                    </tr>
                                    <tr>
                                        <td class="judul">DAFTAR PEMBAYARAN</td>
                                    </tr>
                                    <tr>
                                        <td class="judul">JASA PROFESI MODERATOR</td>
                                    </tr>
                                    <tr>
                                       {{-- Ini kasih judul sesuai nama kegiatannya --}}
                                       <td class="judul"> {{$judul}} </td>

                                    </tr>
                                    <tr>

                                        <td class="judul">Sesuai SK Kuasa Pengguna Anggaran LLDIKTI Wilayah IV Nomor :&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;. Tanggal : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                                    </tr>
                                    <tr>
                                        <td class="judul">Tanggal Pelaksanaan {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM YYYY') }}<span class="long-space"></span></td>
                                    </tr>

                                </table>


                                <table width="500" border="1" cellpadding="5" cellspacing="0"  style="border-collapse: collapse; font-size: 10px; border: 1px solid black;" >
                                    <br>
                                    <tr>
                                        <hr width="500">
                                    </tr>

                                    <thead class="text-center" >
                                        <tr class="text-center small">
                                            <th >No</th>
                                            <th >Nama</th>
                                            <th >Jumlah <br>Kegiatan</th>
                                            <th >Harga <br>Satuan</th>
                                            <th >Jumlah <br>(RP)</th>
                                            <th >PPH</th>
                                            <th >Jumlah <br>dibayar (Rp)</th>
                                            <th >Tanda <br>Tangan</th>

                                    </thead>
                                    <tbody >
                                        @foreach ($datas as $data)
                                          <tr>
                                            <td class="text-center">{{$loop->iteration}}</td>
                                            <td style="min-width: 150px">{{$data['nama']}}</td>
                                            <td>{{$data['jumlah_kegiatan']}}</td>
                                            <td>{{ number_format($data['satuan_honorarium'], 0, ',', '.') }}</td>
                                            <td>{{ number_format($data['jumlah_honorarium'], 0, ',', '.') }}</td>
                                            <td>{{ number_format($data['pph'], 0, ',', '.') }}</td>
                                            <td>{{ number_format($data['nominal_honorarium'], 0, ',', '.') }}</td>
                                            <td>{{$data['data_bank']}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="fw-bold text-end">Sub Total</td>
                                            <td>{{ number_format($subTotal_jumlah, 0, ',', '.') }}</td>
                                            <td>{{ number_format($subTotal_pph, 0, ',', '.') }}</td>
                                            <td>{{ number_format($subTotal_nominal, 0, ',', '.') }}</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <!-- Template 6: Tanda Tangan -->
                                <table width="500">
                                    <br>
                                    <tr>
                                        <td width="10"></td>
                                        <td align="left"><span id=""></span><br>  Setuju dibebankan pada mata anggaran berkenaan, <br>
                                            a.n. Kuasa Pengguna Anggaran <br>
                                            Pejabat Pembuat Komitmen <br>
                                            LLDIKTI Wilayah IV,
                                            <br><br><br><br><br>
                                            {{ $pegawaiMaster->nama_lengkap }}<br>
                                            NIP. {{ $pegawaiMaster->NIP_NIK }}
                                        </td>

                                        <td width="100"></td>
                                        <td align="left"><span id=""></span><br> Bandung, {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM YYYY') }}
                                            <br>Telah dibayar sejumlah
                                            <br>Rp. {{ number_format($subTotal_nominal, 0, ',', '.') }}
                                            <br>Bendahara Pengeluaran,
                                            <br><br><br><br><br><br>
                                            {{ $pegawaiBendahara->nama_lengkap }}<br>
                                            NIP. {{ $pegawaiBendahara->NIP_NIK }}
                                        </td>
                                    </tr>
                                </table>

                            </center>
                            <!-- Akhir Dashboard -->


                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
@endsection
