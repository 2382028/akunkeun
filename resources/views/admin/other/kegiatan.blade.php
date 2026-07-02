@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <h4>Detail Laporan Program Kegiatan</h4>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 mb-3">
        <div class="card">
          <div class="card-body content">

            <!-- Laporan Perjadin Kegiatan -->
            <div class="row">
            <div class="col-md-12 mb-3 text-end">
                <a href="" id="exportButton"  class="btn btn-success"><i class="fa-solid fa-print"></i> Export Excel</a>
            </div>
            <form action="{{url('/cu_spby_kegiatan')}}" method="post">
            @csrf

                <div class="table-responsive">
                    <table id="myTable" class="table table-bordered data-table" style="width: 100%">
                      <thead>
                        <tr class="text-center small">
                          <th class="th-sm">No</th>
                          <th class="th-sm">ID Kegiatan</th>
                          <th class="th-md">Kota</th>
                          <th class="th-sm">Jumlah Hari</th>
                          <th class="th-md">Tanggal Kegiatan</th>
                          <th class="th-md">Nama</th>
                          <th class="th-lg">Kegiatan</th>
                          <th class="th-md">Sebagai</th>
                          <th class="th-md">No Surtug</th>
                          <th class="th-md">Nominal Bayar</th>
                          <th class="th-md">Tgl Terima Berkas</th>
                          <th class="th-md">Tgl Berkas Lengkap</th>
                          <th class="th-md">Tgl Surtug</th>
                          <th class="th-lg">Dokumen Pendukung</th>
                          <th class="th-lg">Fasilitas Pendukung</th>
                          <th class="th-md">MAK</th>

                          <th class="th-md">Uang Harian</th>
                          <th class="th-md">Uang Fullday</th>
                          <th class="th-md">Uang Fullboard</th>
                          <th class="th-md">Uang Reprentasi</th>
                          <th class="th-sm">Pph21 [%]</th>
                          <th class="th-sm">Pph22 [%]</th>
                          <th class="th-sm">Pph23 [%]</th>
                          <th class="th-sm">Ppn [%]</th>
                          <th class="th-md">Uang yang dibayarkan</th>
                          <th class="th-md">Tanggal Pembayaran</th>
                          <th class="th-md">Status Pembayaran</th>
                          <th class="th-md">Status Kegiatan</th>
                          <th class="th-md">Tanggal Kwitansi</th>
                          <th class="th-md">No Kwitansi</th>
                          <th class="th-md">Tanggal SPBY</th>
                          <th class="th-md">SPBY</th>
                          <th class="th-md">Jurnal</th>
                          <th class="th-md">DRPP</th>
                        </tr>
                      </thead>
                      <tbody>
                      @php
                        $numpegawai = 0;
                        $numnonpegawai = 0;
                      @endphp
                        @foreach ($kegiatans as $kegiatan)

                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td class="text-center">{{$kegiatan->idKegiatan}}</td>
                            <td> Bandung - {{$kegiatan->Kota}}</td>
                            <td class="text-center th-sm small">{{$kegiatan->Jumlah_Hari}}</td>
                            <td>{{$kegiatan->Tanggal_kegiatan}}</td>
                            <td>{{$kegiatan->Nama}}</td>
                            <td>{{$kegiatan->Kegiatan}}</td>
                            <td>{{$kegiatan->Sebagai}}</td>
                            <td>{{$kegiatan->No_Surtug}}</td>
                            <td>{{ number_format($kegiatan->Nominal_Bayar, 0, ',', '.') }}</td>
                            <td>{{\Carbon\Carbon::parse($kegiatan->Tgl_Terima_Berkas)->format('d-m-Y H:i')}}</td>
                            <td>{{\Carbon\Carbon::parse($kegiatan->Tgl_Berkas_Lengkap)->format('d-m-Y H:i')}}</td>
                            <td>{{\Carbon\Carbon::parse($kegiatan->Tgl_Surtug)->format('d-m-Y H:i')}}</td>

                            <td>
                                <ul>
                                    @foreach ($kegiatan->dokumen_list as $dokumen)
                                        <li>
                                            @if (!empty($dokumen['file']))
                                                {{ $dokumen['nama_dokumen'] }}:
                                                @php
                                                    // Mengambil hanya bagian setelah "/dokumen-kegiatans/" dari file path
                                                    $filename = str_replace('dokumen-kegiatans/', '', $dokumen['file']);
                                                @endphp
                                                <a href="{{ route('kegiatan-getDokumen', ['filename' => $filename]) }}" target="_blank">Lihat Dokumen</a>
                                            @else
                                                {{ $dokumen['nama_dokumen'] }}
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{$kegiatan->Fasilitas_Pendukung}}</td>
                            <td>{{$kegiatan->MAK}}</td>
                            <td>{{ number_format($kegiatan->Uang_Harian, 0, ',', '.') }}</td>
                            <td>{{ number_format($kegiatan->Uang_Fullday, 0, ',', '.') }}</td>
                            <td>{{ number_format($kegiatan->Uang_Fullboard, 0, ',', '.') }}</td>
                            <td>{{ number_format($kegiatan->Uang_Representasi, 0, ',', '.') }}</td>
                            <td>{{$kegiatan->Pph21}}</td>
                            <td>{{$kegiatan->Pph22}}</td>
                            <td>{{$kegiatan->Pph23}}</td>
                            <td>{{$kegiatan->PPN}}</td>
                            <td>{{ number_format($kegiatan->Total_Pembayaran, 0, ',', '.') }}</td>
                            <td>{{$kegiatan->Tanggal_Pembayaran}}</td>
                            <td>{{$kegiatan->Status_Pembayaran}}</td>
                            {{-- <td>{{$kegiatan->Tanggal_Update_Laporan}}</td> --}}
                            <td class="text-center">{{$kegiatan->Status_Kegiatan}}</td>

                            <td><input type="date" class="form-control dateInput" id="exampleInputEmail1" name="tglkwitansi_{{$numpegawai}}" value="{{$kegiatan->Tanggal_Kwitansi}}"></td>
                            <td><input type="text" class="form-control textInput" id="exampleInputEmail1" name="kwitansi_{{$numpegawai}}" value="{{$kegiatan->No_Kwitansi}}"></td>
                            <td><input type="date" class="form-control dateInput" id="exampleInputEmail1" name="tglSpby_{{$numpegawai}}" value="{{$kegiatan->Tanggal_SPBY}}" readonly></td>

                            <td>
                            <input type="text" class="form-control textInput" id="exampleInputEmail1" name="spby_{{$numpegawai}}" value="{{$kegiatan->SPBY}}" readonly>
                            <input type="hidden" name="idKeuangan_{{$numpegawai}}" value="{{$kegiatan->IdKeuangan}}">
                            </td>

                            <td>
                            @php
                                $jurnalValue = $kegiatan->Jurnal === 'Sudah Jurnal' ? 'Sudah Jurnal' : 'Belum Jurnal';
                            @endphp

                            <select class="form-select jurnalSelect" aria-label="Default select example" name="jurnal_{{$numpegawai}}" disabled>
                                <option value="Belum Jurnal" @if($jurnalValue === 'Belum Jurnal') selected @endif>Belum Jurnal</option>
                                <option value="Sudah Jurnal" @if($jurnalValue === 'Sudah Jurnal') selected @endif>Sudah Jurnal</option>
                            </select>

                            </td>
                            <td>
                            <input type="text" class="form-control textInput" id="exampleInputEmail1" name="drpp_{{$numpegawai}}" value="{{$kegiatan->DRPP}}">
                            </td>
                        </tr>
                        @php
                            $numpegawai++;
                        @endphp
                        @endforeach 

                        @foreach ($kegiatanNons as $kegiatanNon)

                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td class="text-center">{{$kegiatanNon->idKegiatan}}</td>
                            <td> Bandung - {{$kegiatanNon->Kota}}</td>
                            <td class="text-center th-sm small">{{$kegiatanNon->Jumlah_Hari}}</td>
                            <td>{{$kegiatanNon->Tanggal_kegiatan}}</td>
                            <td>{{$kegiatanNon->Nama}}</td>
                            <td>{{$kegiatanNon->Kegiatan}}</td>
                            <td>{{$kegiatanNon->Sebagai}}</td>
                            <td>{{$kegiatanNon->No_Surtug}}</td>
                            <td>{{ number_format($kegiatanNon->Nominal_Bayar, 0, ',', '.') }}</td>
                            <td>{{$kegiatanNon->Tgl_Terima_Berkas}}</td>
                            <td>{{$kegiatanNon->Tgl_Berkas_Lengkap}}</td>
                            <td>{{$kegiatanNon->Tgl_Surtug}}</td>

                            <td>
                                <ul>
                                    @foreach ($kegiatanNon->dokumen_list as $dokumen)
                                        <li>
                                            @if (!empty($dokumen['file']))
                                                {{ $dokumen['nama_dokumen'] }}:
                                                @php
                                                    // Mengambil hanya bagian setelah "/dokumen-kegiatans/" dari file path
                                                    $filename = str_replace('dokumen-kegiatans/', '', $dokumen['file']);
                                                @endphp
                                                <a href="{{ route('kegiatan-getDokumen', ['filename' => $filename]) }}" target="_blank">Lihat Dokumen</a>
                                            @else
                                                {{ $dokumen['nama_dokumen'] }}
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{$kegiatanNon->Fasilitas_Pendukung}}</td>
                            <td>{{$kegiatanNon->MAK}}</td>
                            <td>{{ number_format($kegiatanNon->Uang_Harian, 0, ',', '.') }}</td>
                            <td>{{ number_format($kegiatanNon->Uang_Fullday, 0, ',', '.') }}</td>
                            <td>{{ number_format($kegiatanNon->Uang_Fullboard, 0, ',', '.') }}</td>
                            <td>{{ number_format($kegiatanNon->Uang_Representasi, 0, ',', '.') }}</td>
                            <td>{{$kegiatanNon->Pph21}}</td>
                            <td>{{$kegiatanNon->Pph22}}</td>
                            <td>{{$kegiatanNon->Pph23}}</td>
                            <td>{{$kegiatanNon->PPN}}</td>
                            <td>{{ number_format($kegiatanNon->Total_Pembayaran, 0, ',', '.') }}</td>
                            <td>{{$kegiatanNon->Tanggal_Pembayaran}}</td>
                            <td>{{$kegiatanNon->Status_Pembayaran}}</td>
                            {{-- <td>{{$kegiatanNon->Tanggal_Update_Laporan}}</td> --}}
                            <td class="text-center">{{$kegiatanNon->Status_Kegiatan}}</td>

                            <td><input type="date" class="form-control dateInput" id="exampleInputEmail1" name="tglkwitansiNon_{{$numnonpegawai}}" value="{{$kegiatanNon->Tanggal_Kwitansi}}"></td>
                            <td><input type="text" class="form-control textInput" id="exampleInputEmail1" name="kwitansiNon_{{$numnonpegawai}}" value="{{$kegiatanNon->No_Kwitansi}}"></td>
                            <td><input type="date" class="form-control dateInput" id="exampleInputEmail1" name="tglSpbyNon_{{$numnonpegawai}}" value="{{$kegiatanNon->Tanggal_SPBY}}" readonly></td>

                            <td>
                            <input type="text" class="form-control textInput" id="exampleInputEmail1" name="spbyNon_{{$numnonpegawai}}" value="{{$kegiatanNon->SPBY}}" readonly>
                            <input type="hidden" name="idKeuanganNon_{{$numnonpegawai}}" value="{{$kegiatanNon->IdKeuangan}}">
                            </td>

                            <td>
                            @php
                                $jurnalValue = $kegiatanNon->Jurnal === 'Sudah Jurnal' ? 'Sudah Jurnal' : 'Belum Jurnal';
                            @endphp

                            <select class="form-select jurnalSelect" aria-label="Default select example" name="jurnalNon_{{$numnonpegawai}}" disabled>
                                <option value="Belum Jurnal" @if($jurnalValue === 'Belum Jurnal') selected @endif>Belum Jurnal</option>
                                <option value="Sudah Jurnal" @if($jurnalValue === 'Sudah Jurnal') selected @endif>Sudah Jurnal</option>
                            </select>

                            </td>
                            <td>
                            <input type="text" class="form-control textInput" id="exampleInputEmail1" name="drppNon_{{$numnonpegawai}}" value="{{$kegiatanNon->DRPP}}">
                            </td>
                        </tr>
                        @php
                            $numnonpegawai++;
                        @endphp
                        @endforeach 
                      </tbody>
                      <tfoot>
                        <tr id="totalRow">
                            <td colspan="3">Transaksi Sudah Dibayar</td>
                            <td class="text-center">{{$dibayarkan}}</td>
                            <td class="text-center"><b>{{$totaldibayarkan}}</b></td>
                        </tr>
                        <tr id="totalRow">
                            <td colspan="3">Transaksi Belum Dibayar</td>
                            <td class="text-center">{{$blmdibayarkan}}</td>
                            <td class="text-center"><b>{{$totalblmdibayarkan}}</b></td>
                        </tr>
                        <tr id="totalRow">
                            <td colspan="3">Transaksi Tidak Dibayar</td>
                            <td class="text-center">{{$tdkdibayarkan}}</td>
                            <td class="text-center"><b>{{$totaltdkdibayarkan}}</b></td>
                        </tr>
                      </tfoot>
                    </table>
                </div>
                <div class="container text-center mt-5 mb-5">
                  <input type="hidden" name="mulai" value="{{$mulai}}">
                  <input type="hidden" name="sampai" value="{{$sampai}}">
                  <input type="hidden" name="numPegawaiLaporan" value="{{$numpegawai}}">
                  <input type="hidden" name="numNonPegawaiLaporan" value="{{$numnonpegawai}}">
                  <button type="submit" class="btn btn-success">Simpan Pembaharuan</button>
                </div>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('exportButton').addEventListener('click', function() {
      var table = document.getElementById('myTable');
      var rows = table.rows;
      var data = [];

      for (var i = 0; i < rows.length; i++) {
        var rowData = [];
        var cells = rows[i].cells;

        for (var j = 0; j < cells.length; j++) {
          var cell = cells[j];

          if (cell.querySelector('.dateInput')) {
            var dateInput = cell.querySelector('.dateInput');
            var enteredDate = dateInput.value;

            // Format the entered date as desired (e.g., dd-mm-yyyy)
            var formattedDate = formatDate(enteredDate);

            rowData.push(formattedDate);
          } else if (cell.querySelector('.textInput')) {
            var textInput = cell.querySelector('.textInput');
            var enteredText = textInput.value;

            rowData.push(enteredText);
          } else {
            rowData.push(cell.innerText);
          }
        }

        data.push(rowData);
      }

      // Create a workbook and worksheet
      var workbook = XLSX.utils.book_new();
      var worksheet = XLSX.utils.aoa_to_sheet(data);

      // Add the worksheet to the workbook
      XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet 1');

      // Convert the workbook to Excel buffer
      var excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });

      // Save the Excel buffer as a file
      saveAs(new Blob([excelBuffer], { type: 'application/octet-stream' }), 'Detail Perjalanan Dinas Kegiatan.xlsx');
    });

    // Function to format the date as dd-mm-yyyy
    function formatDate(date) {
      var parts = date.split('-');
      var day = parts[2];
      var month = parts[1];
      var year = parts[0];

      return day + '-' + month + '-' + year;
    }
  </script>
@endsection
