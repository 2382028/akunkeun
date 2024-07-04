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
                          <th class="th-sm">No Kegiatan</th>
                          <th class="th-lg">Kegiatan</th>
                          <th class="th-md">Nama</th>
                          <th class="th-md">Sebagai</th>
                          <th class="th-md">MAK</th>
                          <th class="th-md">Uang Harian</th>
                          <th class="th-sm">Pph21 [%]</th>
                          <th class="th-sm">Pph22 [%]</th>
                          <th class="th-sm">Pph23 [%]</th>
                          <th class="th-sm">Ppn [%]</th>
                          <th class="th-md">Uang yang dibayarkan</th>
                          <th class="th-md">Tanggal Pembayaran</th>
                          <th class="th-md">Status Pembayaran</th>
                          <th class="th-md">Tanggal Acara</th>
                          <th class="th-lg">Lokasi</th>
                          <th class="th-md">Dokumen Pendukung</th>
                          <th class="th-lg">Fasilitas Pendukung</th>
                          <th class="th-md">Status Kegiatan</th>
                          <th class="th-md">Tanggal Kwitansi</th>
                          <th class="th-md">No Kwitansi</th>
                          <th class="th-md">Tanggal SPBY</th>
                          <th class="th-md">SPBY</th>
                          <th class="th-md">DRPP</th>
                          <th class="th-md">Jurnal</th>
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
                            <td>{{$kegiatan->nama_kegiatan}}</td>
                            <td>{{$kegiatan->nama_lengkap}}</td>
                            <td>{{$kegiatan->sebagai}}</td>
                            <td>
                              @foreach ($akuns as $akun)
                                  @if ($akun->idAkun == $kegiatan->MAK)
                                  {{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}
                                  @endif
                              @endforeach
                            </td>
                            <td>{{$kegiatan->harga}}</td>
                            <td>{{$kegiatan->persen_pajak}}</td>
                            <td>{{$kegiatan->pph22}}</td>
                            <td>{{$kegiatan->pph23}}</td>
                            <td>{{$kegiatan->ppn}}</td>
                            <td>{{$kegiatan->jumlah_harga}}</td>
                            <td>{{$kegiatan->transaksi}}</td>
                            <td>{{$kegiatan->status_pembayaran}}</td>
                            <td>{{$kegiatan->tgl_mulai}} s.d {{$kegiatan->tgl_selesai}}</td>
                            <td>{{$kegiatan->alamat}}</td>
                            <td>
                              <ul>
                                @foreach ($laporans as $laporan)
                                  @if ($kegiatan->idKegiatan == $laporan->data_perjadin_kegiatan)
                                    <li>{{$laporan->nama_dokumen}} [{{$laporan->status}}]</li>
                                  @endif
                                @endforeach
                              </ul>
                            </td>
                            <td>
                              <ul>
                                @foreach ($operasionals as $operasional)
                                  @if ($kegiatan->idKegiatan == $operasional->data_perjadinkegiatan)
                                    <li>{{$operasional->nama}} [{{$operasional->jumlah_frekuensi}}x - {{$operasional->satuan}}/{{$operasional->detail_satuan}}, {{$operasional->jumlah_harga}}] ({{$operasional->status}})</li>
                                  @endif
                                @endforeach
                              </ul>
                            </td>
                            <td>{{$kegiatan->status_kegiatan}}</td>
                            <td><input type="date" class="form-control dateInput" id="exampleInputEmail1" name="tglkwitansi_{{$numpegawai}}" value="{{$kegiatan->tgl_kwitansi}}"></td>
                            <td><input type="text" class="form-control textInput" id="exampleInputEmail1" name="kwitansi_{{$numpegawai}}" value="{{$kegiatan->no_kwitansi}}"></td>
                            <td>{{$kegiatan->tgl_mulai}}</td>
                            <td>
                              <input type="text" class="form-control textInput" id="exampleInputEmail1" name="spby_{{$numpegawai}}" value="{{$kegiatan->spby}}">
                              <input type="hidden" name="idKeuangan_{{$numpegawai}}" value="{{$kegiatan->IdKeuangan}}">
                            </td>
                            <td>
                              <input type="text" class="form-control textInput" id="exampleInputEmail1" name="drpp_{{$numpegawai}}" value="{{$kegiatan->drpp}}">
                            </td>
                            <td>
                            <select class="form-select" aria-label="Default select example" name="jurnal_{{$numpegawai}}">
                              <option value="{{$kegiatan->jurnal}}">{{$kegiatan->jurnal}}</option>
                              <option value="Belum Jurnal">Belum Jurnal</option>
                              <option value="Sudah Jurnal">Sudah Jurnal</option>
                            </select>
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
                            <td>{{$kegiatanNon->nama_kegiatan}}</td>
                            <td>{{$kegiatanNon->nama_lengkap}}</td>
                            <td>{{$kegiatanNon->sebagai}}</td>
                            <td>
                              @foreach ($akuns as $akun)
                                  @if ($akun->idAkun == $kegiatanNon->MAK)
                                  {{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}
                                  @endif
                              @endforeach
                            </td>
                            <td>{{$kegiatanNon->harga}}</td>
                            <td>{{$kegiatanNon->persen_pajak}}</td>
                            <td>{{$kegiatanNon->pph22}}</td>
                            <td>{{$kegiatanNon->pph23}}</td>
                            <td>{{$kegiatanNon->ppn}}</td>
                            <td>{{$kegiatanNon->jumlah_harga}}</td>
                            <td>{{$kegiatanNon->transaksi}}</td>
                            <td>{{$kegiatanNon->status_pembayaran}}</td>
                            <td>{{$kegiatanNon->tgl_mulai}} s.d {{$kegiatanNon->tgl_selesai}}</td>
                            <td>{{$kegiatanNon->alamat}}</td>
                            <td>
                              <ul>
                                @foreach ($laporans as $laporan)
                                    @if ($kegiatanNon->idKegiatan == $laporan->data_perjadin_kegiatan)
                                      <li>{{$laporan->nama_dokumen}} [{{$laporan->status}}]</li>
                                    @endif
                                @endforeach
                              </ul>
                            </td>
                            <td>
                              <ul>
                                @foreach ($operasionals as $operasional)
                                  @if ($kegiatanNon->idKegiatan == $operasional->data_perjadinkegiatan)
                                    <li>{{$operasional->nama}} [{{$operasional->jumlah_frekuensi}}x - {{$operasional->satuan}}/{{$operasional->detail_satuan}}, {{$operasional->jumlah_harga}}] ({{$operasional->status}})</li>
                                  @endif
                                @endforeach
                              </ul>
                            </td>
                            <td>{{$kegiatanNon->status_kegiatan}}</td>
                            <td><input type="date" class="form-control dateInput" id="exampleInputEmail1" name="tglkwitansiNon_{{$numnonpegawai}}" value="{{$kegiatanNon->tgl_kwitansi}}"></td>
                            <td><input type="text" class="form-control textInput" id="exampleInputEmail1" name="kwitansiNon_{{$numnonpegawai}}" value="{{$kegiatanNon->no_kwitansi}}"></td>
                            <td>{{$kegiatanNon->tgl_mulai}}</td>
                            <td>
                              <input type="text" class="form-control textInput" id="exampleInputEmail1" name="spbyNon_{{$numnonpegawai}}" value="{{$kegiatanNon->spby}}">
                              <input type="hidden" name="idKeuanganNon_{{$numpegawai}}" value="{{$kegiatan->IdKeuangan}}">
                            </td>
                            <td>
                              <input type="text" class="form-control textInput" id="exampleInputEmail1" name="drppNon_{{$numnonpegawai}}" value="{{$kegiatanNon->drpp}}">
                            </td>
                            <td>
                            <select class="form-select" aria-label="Default select example" name="jurnalNon_{{$numnonpegawai}}">
                              <option value="{{$kegiatanNon->jurnal}}">{{$kegiatanNon->jurnal}}</option>
                              <option value="Belum Jurnal">Belum Jurnal</option>
                              <option value="Sudah Jurnal">Sudah Jurnal</option>
                            </select>
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