@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <h4>Detail Laporan Perjalanan Dinas Langsung</h4>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 mb-3">
        <div class="card">
          <div class="card-body content">

            <!-- Laporan Perjadin Langsung -->

            <div class="row">
                <div class="col-md-12 mb-3">
                    <!-- Tombol Export di luar Form -->
                    <div class="d-inline float-end">
                        <button id="exportButton" class="btn btn-success me-2">
                            <i class="fa-solid fa-print"></i> Export to Excel
                        </button>
                    </div>

                    <!-- Form dengan tombol Simpan -->
                    {{-- <button type="submit" class="btn btn-success">Simpan Perubahan</button> --}}

                </div>
                <form action="{{url('/cu_spby_perjadin')}}" method="post" style="display: inline;">
                    @csrf
                <div class="table-responsive">
                    <table id="myTable" class="table table-bordered data-table" style="width: 100%">
                      <thead>
                        <tr class="text-center small">
                          <th class="th-sm">No</th>
                          <th class="th-sm">ID Kegiatan</th>
                          <th class="th-md">Kota</th>
                          <th class="th-sm">Jumlah Hari</th>
                          <th class="th-md">Tanggal Perjadin</th>
                          <th class="th-md">Nama</th>
                          <th class="th-lg">Kegiatan</th>
                          <th class="th-md">No Surtug</th>
                          <th class="th-md">Nominal Bayar</th>
                          <th class="th-md">Tgl Terima Berkas</th>
                          <th class="th-md">Tgl Berkas Lengkap</th>
                          <th class="th-md">Tgl Surtug</th>
                          <th class="th-sm">Undangan</th>
                          <th class="th-sm">Surat Tugas</th>
                          <th class="th-sm">SPPD</th>
                          <th class="th-sm">Laporan Perjadin</th>
                          <th class="th-sm">Bukti Pengeluaran</th>
                          <th class="th-md">BBM</th>
                          <th class="th-md">e-Toll</th>
                          <th  class="th-md">Penginapan</th>
                          <th class="th-md">Transportasi</th>
                          <th class="th-md">Pesawat</th>
                          <th class="th-md">Kereta</th>
                          <th class="th-md">Travel</th>
                          <th class="th-md">Fasilitas Lainnya</th>
                          <th class="th-md">MAK</th>
                          <th class="th-md">Tanggal Pembayaran</th>
                          <th class="th-md">Uang Harian</th>
                          <th class="th-md">Uang Fullday</th>
                          <th class="th-md">Uang Fullboard</th>
                          <th class="th-md">Uang Reprentasi</th>
                          <th class="th-sm">Pph21 [%]</th>
                          <th class="th-sm">Pph22 [%]</th>
                          <th class="th-sm">Pph23 [%]</th>
                          <th class="th-sm">PPN [%]</th>

                          <th class="th-sm">Total Pembayaran</th>
                          <th class="th-md">Status Pembayaran</th>
                          <th class="th-md">Tanggal Update Laporan</th>
                          <th class="th-md">Status Perjalanan Dinas</th>
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
                      @foreach ($perjadins as $perjadin)

                      <tr>
                          <td class="text-center">{{$loop->iteration}}</td>
                          <td class="text-center">{{$perjadin->idPerjadin}}</td>
                          <td> Bandung - {{$perjadin->Kota}}</td>
                          <td class="text-center th-sm small">{{$perjadin->Jumlah_Hari}}</td>
                          <td>{{$perjadin->Tanggal_Perjadin}}</td>
                          <td>{{$perjadin->Nama}}</td>
                          <td>{{$perjadin->Kegiatan}}</td>
                          <td>{{$perjadin->No_Surtug}}</td>
                          <td>{{ number_format($perjadin->Nominal_Bayar, 0, ',', '.') }}</td>
                          <td>{{$perjadin->Tgl_Terima_Berkas}}</td>
                          <td>{{$perjadin->Tgl_Berkas_Lengkap}}</td>
                          <td>{{$perjadin->Tgl_Surtug}}</td>
                          <td class="text-center">
                            @if ($perjadin->Undangan != null)
                              <span>&#x2713;</span><br>
                              <a href="{{asset('public/storage/' . $perjadin->Undangan)}}" target="_blank"></a>
                            @else
                              <span>&#x2717;</span>
                            @endif
                          </td>
                          <td class="text-center">
                            @if ($perjadin->Surat_Tugas != null)
                              <span>&#x2713;</span><br>
                              <a href="{{asset('public/storage/' . $perjadin->Surat_Tugas)}}" target="_blank"></a>
                            @else
                              <span>&#x2717;</span>
                            @endif
                          </td>
                          <td class="text-center">
                            @if ($perjadin->SPPD != null)
                              <span>&#x2713;</span><br>
                              <a href="{{asset('public/storage/' . $perjadin->SPPD)}}" target="_blank"></a>
                            @else
                              <span>&#x2717;</span>
                            @endif
                          </td>
                          <td class="text-center">
                            @if ($perjadin->Laporan_Perjadin != null)
                              <span>&#x2713;</span><br>
                              <a href="{{asset('/note-perjadin-laporan/' . $perjadin->idPerjadin)}}" target="_blank"></a>
                            @else
                              <span>&#x2717;</span>
                            @endif
                          </td>
                          <td class="text-center">
                            @if ($perjadin->Bukti_Pengeluaran != null)
                              <span>&#x2713;</span><br>
                              <a href="{{asset('public/storage/' . $perjadin->Bukti_Pengeluaran)}}" target="_blank"></a>
                            @else
                              <span>&#x2717;</span>
                            @endif
                          </td>

                          <td>{{$perjadin->BBM}}</td>
                          <td>{{$perjadin->e_Toll}}</td>
                          <td>{{$perjadin->Penginapan}}</td>
                          <td>{{$perjadin->Transportasi}}</td>
                          <td>{{$perjadin->Pesawat}}</td>
                          <td>{{$perjadin->Kereta}}</td>
                          <td>{{$perjadin->Travel}}</td>
                          <td>{{$perjadin->Fasilitas_Lainnya}}</td>
                          <td>{{$perjadin->MAK}}</td>
                          <td>{{$perjadin->Tanggal_Pembayaran}}</td>
                          <td>{{ number_format($perjadin->Uang_Harian, 0, ',', '.') }}</td>
                          <td>{{ number_format($perjadin->Uang_Fullday, 0, ',', '.') }}</td>
                          <td>{{ number_format($perjadin->Uang_Fullboard, 0, ',', '.') }}</td>
                          <td>{{ number_format($perjadin->Uang_Representasi, 0, ',', '.') }}</td>
                          <td>{{$perjadin->Pph21}}</td>
                          <td>{{$perjadin->Pph22}}</td>
                          <td>{{$perjadin->Pph23}}</td>
                          <td>{{$perjadin->PPN}}</td>
                          <td>{{ number_format($perjadin->Total_Pembayaran, 0, ',', '.') }}</td>
                          <td>{{$perjadin->Status_Pembayaran}}</td>
                          <td>{{$perjadin->Tanggal_Update_Laporan}}</td>
                          <td>{{$perjadin->Status_Perjalanan_Dinas}}</td>

                          <td><input type="date" class="form-control dateInput" id="exampleInputEmail1" name="tglkwitansi_{{$numpegawai}}" value="{{$perjadin->Tanggal_Kwitansi}}"></td>
                          <td><input type="text" class="form-control textInput" id="exampleInputEmail1" name="kwitansi_{{$numpegawai}}" value="{{$perjadin->No_Kwitansi}}"></td>
                          <td><input type="date" class="form-control dateInput" id="exampleInputEmail1" name="tglSpby_{{$numpegawai}}" value="{{$perjadin->Tanggal_SPBY}}"></td>

                          <td>
                            <input type="text" class="form-control textInput" id="exampleInputEmail1" name="spby_{{$numpegawai}}" value="{{$perjadin->SPBY}}">
                            <input type="hidden" name="idKeuangan_{{$numpegawai}}" value="{{$perjadin->IdKeuangan}}">
                          </td>

                          <td>
                            @php
                                $jurnalValue = $perjadin->Jurnal === 'Sudah Jurnal' ? 'Sudah Jurnal' : 'Belum Jurnal';
                            @endphp

                            <select class="form-select jurnalSelect" aria-label="Default select example" name="jurnal_{{$numpegawai}}">
                                <option value="Belum Jurnal" @if($jurnalValue === 'Belum Jurnal') selected @endif>Belum Jurnal</option>
                                <option value="Sudah Jurnal" @if($jurnalValue === 'Sudah Jurnal') selected @endif>Sudah Jurnal</option>
                            </select>

                          </td>
                          <td>
                            <input type="text" class="form-control textInput" id="exampleInputEmail1" name="drpp_{{$numpegawai}}" value="{{$perjadin->DRPP}}">
                          </td>
                      </tr>
                        @php
                          $numpegawai++;
                        @endphp
                      @endforeach

                      @foreach ($perjadinNons as $perjadinNon)

                      <tr>
                          <td class="text-center">{{$loop->iteration}}</td>
                          <td class="text-center">{{$perjadinNon->idPerjadin}}</td>
                          <td> Bandung - {{$perjadinNon->Kota}}</td>
                          <td class="text-center th-sm small">{{$perjadinNon->Jumlah_Hari}}</td>
                          <td>{{$perjadinNon->Tanggal_Perjadin}}</td>
                          <td>{{$perjadinNon->Nama}}</td>
                          <td>{{$perjadinNon->Kegiatan}}</td>
                          <td>{{$perjadinNon->No_Surtug}}</td>
                          <td>{{ number_format($perjadinNon->Nominal_Bayar, 0, ',', '.') }}</td>
                          <td>{{$perjadinNon->Tgl_Terima_Berkas}}</td>
                          <td>{{$perjadinNon->Tgl_Berkas_Lengkap}}</td>
                          <td>{{$perjadinNon->Tgl_Surtug}}</td>
                          <td class="text-center">
                            @if ($perjadinNon->Undangan != null)
                              <span>&#x2713;</span><br>
                              <a href="{{asset('public/storage/' . $perjadinNon->Undangan)}}" </a>
                            @else
                              <span>&#x2717;</span>
                            @endif
                          </td>
                          <td class="text-center">
                            @if ($perjadinNon->Surat_Tugas != null)
                              <span>&#x2713;</span><br>
                              <a href="{{asset('public/storage/' . $perjadinNon->Surat_Tugas)}}" target="_blank"></a>
                            @else
                              <span>&#x2717;</span>
                            @endif
                          </td>
                          <td class="text-center">
                            @if ($perjadinNon->SPPD != null)
                              <span>&#x2713;</span><br>
                              <a href="{{asset('public/storage/' . $perjadinNon->SPPD)}}" target="_blank"></a>
                            @else
                              <span>&#x2717;</span>
                            @endif
                          </td>
                          <td class="text-center">
                            @if ($perjadinNon->Laporan_Perjadin != null)
                              <span>&#x2713;</span><br>
                              <a href="{{asset('/note-perjadin-laporan/' . $perjadinNon->idPerjadin)}}" target="_blank"></a>
                            @else
                              <span>&#x2717;</span>
                            @endif
                          </td>
                          <td class="text-center">
                            @if ($perjadinNon->Bukti_Pengeluaran != null)
                              <span>&#x2713;</span><br>
                              <a href="{{asset('public/storage/' . $perjadinNon->Bukti_Pengeluaran)}}" target="_blank"></a>
                            @else
                              <span>&#x2717;</span>
                            @endif
                          </td>

                          <td>{{$perjadinNon->BBM}}</td>
                          <td>{{$perjadinNon->e_Toll}}</td>
                          <td>{{$perjadinNon->Penginapan}}</td>
                          <td>{{$perjadinNon->Transportasi}}</td>
                          <td>{{$perjadinNon->Pesawat}}</td>
                          <td>{{$perjadinNon->Kereta}}</td>
                          <td>{{$perjadinNon->Travel}}</td>
                          <td>{{$perjadinNon->Fasilitas_Lainnya}}</td>
                          <td>{{$perjadinNon->MAK}}</td>
                          <td>{{$perjadinNon->Tanggal_Pembayaran}}</td>
                          <td>{{ number_format($perjadinNon->Uang_Harian, 0, ',', '.') }}</td>
                          <td>{{ number_format($perjadinNon->Uang_Fullday, 0, ',', '.') }}</td>
                          <td>{{ number_format($perjadinNon->Uang_Fullboard, 0, ',', '.') }}</td>
                          <td>{{ number_format($perjadinNon->Uang_Representasi, 0, ',', '.') }}</td>
                          <td>{{$perjadinNon->Pph21}}</td>
                          <td>{{$perjadinNon->Pph22}}</td>
                          <td>{{$perjadinNon->Pph23}}</td>
                          <td>{{$perjadinNon->PPN}}</td>
                          <td>{{ number_format($perjadinNon->Total_Pembayaran, 0, ',', '.') }}</td>
                          <td>{{$perjadinNon->Status_Pembayaran}}</td>
                          <td>{{$perjadinNon->Tanggal_Update_Laporan}}</td>
                          <td>{{$perjadinNon->Status_Perjalanan_Dinas}}</td>

                          <td><input type="date" class="form-control dateInput" id="exampleInputEmail1" name="tglkwitansiNon_{{$numnonpegawai}}" value="{{$perjadinNon->Tanggal_Kwitansi}}"></td>
                          <td><input type="text" class="form-control textInput" id="exampleInputEmail1" name="kwitansiNon_{{$numnonpegawai}}" value="{{$perjadinNon->No_Kwitansi}}"></td>

                          <td><input type="date" class="form-control dateInput" id="exampleInputEmail1" name="tglSpbyNon_{{$numnonpegawai}}" value="{{$perjadinNon->Tanggal_SPBY}}"></td>
                          <td>
                            <input type="text" class="form-control textInput" id="exampleInputEmail1" name="spbyNon_{{$numnonpegawai}}" value="{{$perjadinNon->SPBY}}">
                            <input type="hidden" name="idKeuanganNon_{{$numnonpegawai}}" value="{{$perjadinNon->IdKeuangan}}">
                          </td>

                          <td>
                            @php
                            $jurnalValue = $perjadinNon->Jurnal === 'Sudah Jurnal' ? 'Sudah Jurnal' : 'Belum Jurnal';
                        @endphp

                        <select class="form-select jurnalSelect" aria-label="Default select example" name="jurnalNon_{{$numnonpegawai}}">
                            <option value="Belum Jurnal" @if($jurnalValue === 'Belum Jurnal') selected @endif>Belum Jurnal</option>
                            <option value="Sudah Jurnal" @if($jurnalValue === 'Sudah Jurnal') selected @endif>Sudah Jurnal</option>
                        </select>

                          </td>
                          <td>
                            <input type="text" class="form-control textInput" id="exampleInputEmail1" name="drppNon_{{$numnonpegawai}}" value="{{$perjadinNon->DRPP}}">
                          </td>
                      </tr>
                        @php
                          $numnonpegawai++;
                        @endphp
                      @endforeach

                      </tbody>
                    
                    </table>
                </div>
                <div class="container text-center mt-5 mb-5">
                  <input type="hidden" name="mulai" value="{{$mulai}}">
                  <input type="hidden" name="sampai" value="{{$sampai}}">
                  <input type="hidden" name="numPegawaiLaporan" value="{{$numpegawai}}">
                  <input type="hidden" name="numNonPegawaiLaporan" value="{{$numnonpegawai}}">
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

          if (cell.querySelector('.jurnalSelect')) {
            var selectInput = cell.querySelector('.jurnalSelect');
            var selectedValue = selectInput.value;

            rowData.push(selectedValue);
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
      saveAs(new Blob([excelBuffer], { type: 'application/octet-stream' }), 'table_data.xlsx');
    });
  </script>
@endsection
