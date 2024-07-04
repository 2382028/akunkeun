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
            <div class="col-md-12 mb-3 text-end">
                      <button id="exportButton" class="btn btn-success"><i class="fa-solid fa-print"></i> Export to Excel</button>
                    </div>
            <form action="{{url('/cu_spby_perjadin')}}" method="post">
            @csrf
                <div class="table-responsive">
                    
                    <table id="myTable" class="table table-bordered data-table" style="width: 100%">
                      <thead>
                        <tr class="text-center small">
                          <th class="th-sm">No</th>
                          <th class="th-sm">No Kegiatan</th>
                          <th class="th-md">Nama</th>
                          <th class="th-lg">Kegiatan</th>
                          <th class="th-md">MAK</th>
                          <th class="th-md">Uang Harian</th>
                          <th class="th-sm">Pph21 [%]</th>
                          <th class="th-sm">Pph22 [%]</th>
                          <th class="th-sm">Pph23 [%]</th>
                          <th class="th-sm">PPN [%]</th>
                          <th class="th-md">Uang yang dibayarkan</th>
                          <th class="th-md">Tanggal Pembayaran</th>
                          <th class="th-md">Status Pembayaran</th>
                          <th class="th-sm">Undangan</th>
                          <th class="th-sm">Surat Tugas</th>
                          <th class="th-sm">SPPD</th>
                          <th class="th-sm">Laporan Perjadin</th>
                          <th class="th-sm">Lap. Pengeluaran</th>
                          <th class="th-md">Tanggal Update Laporan</th>
                          <th class="th-md">Fasilitas</th>
                          <th class="th-md">Status Perjalanan Dinas</th>
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
                      @foreach ($perjadins as $perjadin)
                      <tr>
                          <td class="text-center">{{$loop->iteration}}</td>
                          <td class="text-center">{{$perjadin->idPerjadin}}</td>
                          <td>{{$perjadin->nama_lengkap}}</td>
                          <td>{{$perjadin->nama_kegiatan}}, {{$perjadin->tgl_mulai}} s.d {{$perjadin->tgl_selesai}}, di {{$perjadin->alamat}}</td>
                          <td>
                            @foreach ($akuns as $akun)
                              @if ($perjadin->MAK == $akun->idAkun)
                              {{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}
                              @endif
                            @endforeach
                          </td>
                          <td>{{$perjadin->uang_harian}}</td>
                          <td>{{$perjadin->persen_pajak}}</td>
                          <td>{{$perjadin->pph22}}</td>
                          <td>{{$perjadin->pph23}}</td>
                          <td>{{$perjadin->ppn}}</td>
                          <td>{{$perjadin->jumlah_harga}}</td>
                          <td>{{$perjadin->transaksi}}</td>
                          <td>{{$perjadin->status_pembayaran}}</td>
                          <td class="text-center">
                            @if ($perjadin->surat_undangan != null)
                              <span>&#x2713;</span><br>
                              <a href="{{asset('public/storage/' . $perjadin->surat_undangan)}}" target="_blank">[Lihat Dokumen]</a>
                            @else
                              <span>&#x2717;</span>
                            @endif
                          </td>
                          <td class="text-center">
                            @if ($perjadin->surat_tugas != null)
                              <span>&#x2713;</span><br>
                              <a href="{{asset('public/storage/' . $perjadin->surat_tugas)}}" target="_blank">[Lihat Dokumen]</a>
                            @else
                              <span>&#x2717;</span>
                            @endif
                          </td>
                          <td class="text-center">
                            @if ($perjadin->SPPD != null)
                              <span>&#x2713;</span><br>
                              <a href="{{asset('public/storage/' . $perjadin->SPPD)}}" target="_blank">[Lihat Dokumen]</a>
                            @else
                              <span>&#x2717;</span>
                            @endif
                          </td>
                          <td class="text-center">
                            @if ($perjadin->lap_perjadin != null)
                              <span>&#x2713;</span><br>
                              <a href="{{asset('/note-perjadin-laporan/' . $perjadin->idPerjadin)}}" target="_blank">[Lihat Dokumen]</a>
                            @else
                              <span>&#x2717;</span>
                            @endif
                          </td>
                          <td class="text-center">
                            @if ($perjadin->lap_pengeluaran != null)
                              <span>&#x2713;</span><br>
                              <a href="{{asset('public/storage/' . $perjadin->lap_pengeluaran)}}" target="_blank">[Lihat Dokumen]</a>
                            @else
                              <span>&#x2717;</span>
                            @endif
                          </td>
                          <td>{{$perjadin->tanggal_dokumen}}</td>
                          <td>
                            <ul>
                              {{-- {{$perjadin->}} --}}
                              @foreach ($fasilitas as $item)
                                @if ($perjadin->idPerjadin == $item->info_perjadinlangsung)
                                  <li>{{$item->nama}} ({{$item->jumlah_frekuensi}}x, {{$item->satuan}}-{{$item->satuan}}) [Rp. {{$item->jumlah_harga}} - {{$item->status}}]</li>
                                @endif
                              @endforeach
                            </ul>
                          </td>
                          <td>{{$perjadin->status_pengajuan}}</td>
                          <td><input type="date" class="form-control dateInput" id="exampleInputEmail1" name="tglkwitansi_{{$numpegawai}}" value="{{$perjadin->tgl_kwitansi}}"></td>
                          <td><input type="text" class="form-control textInput" id="exampleInputEmail1" name="kwitansi_{{$numpegawai}}" value="{{$perjadin->no_kwitansi}}"></td>
                          <td>{{$perjadin->tgl_mulai}}</td>
                          <td>
                            <input type="text" class="form-control textInput" id="exampleInputEmail1" name="spby_{{$numpegawai}}" value="{{$perjadin->spby}}">
                            <input type="hidden" name="idKeuangan_{{$numpegawai}}" value="{{$perjadin->IdKeuangan}}">
                          </td>
                          <td>
                            <input type="text" class="form-control textInput" id="exampleInputEmail1" name="drpp_{{$numpegawai}}" value="{{$perjadin->drpp}}">
                          </td>
                          <td>
                          <select class="form-select jurnalSelect" aria-label="Default select example" name="jurnal_{{$numpegawai}}">
                            <option value="{{$perjadin->jurnal}}">{{$perjadin->jurnal}}</option>
                            <option value="Belum Jurnal">Belum Jurnal</option>
                            <option value="Sudah Jurnal">Sudah Jurnal</option>
                          </select>
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
                          <td>{{$perjadinNon->nama_lengkap}}</td>
                          <td>{{$perjadinNon->nama_kegiatan}}, {{$perjadinNon->tgl_mulai}} s.d {{$perjadinNon->tgl_selesai}}, di {{$perjadinNon->alamat}}</td>
                          <td>
                            @foreach ($akuns as $akun)
                              @if ($perjadinNon->MAK == $akun->idAkun)
                              {{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}
                              @endif
                            @endforeach
                          </td>
                          <td>{{$perjadinNon->uang_harian}}</td>
                          <td>{{$perjadinNon->persen_pajak}}</td>
                          <td>{{$perjadinNon->pph22}}</td>
                          <td>{{$perjadinNon->pph23}}</td>
                          <td>{{$perjadinNon->ppn}}</td>
                          <td>{{$perjadinNon->jumlah_harga}}</td>
                          <td>{{$perjadinNon->transaksi}}</td>
                          <td>{{$perjadinNon->status_pembayaran}}</td>
                          <td class="text-center">
                            @if ($perjadinNon->surat_undangan != null)
                              <span>&#x2713;</span><br>
                              <a href="{{asset('public/storage/' . $perjadinNon->surat_undangan)}}" target="_blank">[Lihat Dokumen]</a>
                            @else
                              <span>&#x2717;</span>
                            @endif
                          </td>
                          <td class="text-center">
                            @if ($perjadinNon->surat_tugas != null)
                              <span>&#x2713;</span><br>
                              <a href="{{asset('public/storage/' . $perjadinNon->surat_tugas)}}" target="_blank">[Lihat Dokumen]</a>
                            @else
                              <span>&#x2717;</span>
                            @endif
                          </td>
                          <td class="text-center">
                            @if ($perjadinNon->SPPD != null)
                              <span>&#x2713;</span><br>
                              <a href="{{asset('public/storage/' . $perjadinNon->SPPD)}}" target="_blank">[Lihat Dokumen]</a>
                            @else
                              <span>&#x2717;</span>
                            @endif
                          </td>
                          <td class="text-center">
                            @if ($perjadinNon->lap_perjadin != null)
                              <span>&#x2713;</span><br>
                              <a href="{{asset('/note-perjadin-laporan/' . $perjadinNon->idPerjadin)}}" target="_blank">[Lihat Dokumen]</a>
                            @else
                              <span>&#x2717;</span>
                            @endif
                          </td>
                          <td class="text-center">
                            @if ($perjadinNon->lap_pengeluaran != null)
                              <span>&#x2713;</span><br>
                              <a href="{{asset('public/storage/' . $perjadinNon->lap_pengeluaran)}}" target="_blank">[Lihat Dokumen]</a>
                            @else
                              <span>&#x2717;</span>
                            @endif
                          </td>
                          <td>{{$perjadinNon->tanggal_dokumen}}</td>
                          <td >
                            <ul>
                              {{-- {{$perjadinNon->}} --}}
                              @foreach ($fasilitas as $item)
                                @if ($perjadinNon->idPerjadin == $item->info_perjadinlangsung)
                                  <li>{{$item->nama}} ({{$item->jumlah_frekuensi}}x, {{$item->satuan}}-{{$item->satuan}}) [Rp. {{$item->jumlah_harga}} - {{$item->status}}]</li>
                                @endif
                              @endforeach
                            </ul>
                          </td>
                          <td>{{$perjadinNon->status_pengajuan}}</td>
                          <td><input type="date" class="form-control dateInput" id="exampleInputEmail1" name="tglkwitansiNon_{{$numnonpegawai}}" value="{{$perjadinNon->tgl_kwitansi}}"></td>
                          <td><input type="text" class="form-control textInput" id="exampleInputEmail1" name="kwitansiNon_{{$numnonpegawai}}" value="{{$perjadinNon->no_kwitansi}}"></td>
                          <td>{{$perjadinNon->tgl_mulai}}</td>
                          <td>
                            <input type="text" class="form-control textInput" id="exampleInputEmail1" name="spbyNon_{{$numnonpegawai}}" value="{{$perjadinNon->spby}}">
                            <input type="hidden" name="idKeuanganNon_{{$numpegawai}}" value="{{$perjadinNon->IdKeuangan}}">
                          </td>
                          <td>
                            <input type="text" class="form-control textInput" id="exampleInputEmail1" name="drppNon_{{$numnonpegawai}}" value="{{$perjadinNon->drpp}}">
                          </td>
                          <td>
                          <select class="form-select jurnalSelect" aria-label="Default select example" name="jurnalNon_{{$numnonpegawai}}">
                            <option value="{{$perjadinNon->jurnal}}">{{$perjadinNon->jurnal}}</option>
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
                            <td colspan="4">Transaksi Sudah Dibayar</td>
                            <td class="text-center">{{$dibayarkan}}</td>
                            <td class="text-center"><b>{{$totaldibayarkan}}</b></td>
                        </tr>
                        <tr id="totalRow">
                            <td colspan="4">Transaksi Belum Dibayar</td>
                            <td class="text-center">{{$blmDibayarkan}}</td>
                            <td class="text-center"><b>{{$totalblmdibayarkan}}</b></td>
                        </tr>
                        <tr id="totalRow">
                            <td colspan="4">Transaksi Tidak Dibayar</td>
                            <td class="text-center">{{$tdkDibayarkan}}</td>
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