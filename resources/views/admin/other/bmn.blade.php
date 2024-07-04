@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <h4>Detail Laporan Transaksi Perbaikan Barang Milik Negara (BMN)</h4>
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
                <div class="table-responsive">
                    <table id="myTable" class="table table-bordered data-table" style="width: 100%">
                      <thead>
                        <tr class="text-center small">
                            <th class="th-sm">No</th>
                            <th class="th-sm">Kode Permohonan</th>
                            <th class="th-md">Nama Pengaju</th>
                            <th class="th-md">Nama Barang</th>
                            <th class="th-md">Komponen Tambahan</th>
                            <th class="th-md">MAK</th>
                            <th class="th-md">Tanggal Pengajuan</th>
                            <th class="th-md">Tanggal Pemeriksaan</th>
                            <th class="th-md">Tanggal Pengerjaan</th>
                            <th class="th-md">Tanggal Selesai</th>
                            <th class="th-sm">Nominal</th>
                            <th class="th-sm">PPh</th>
                            <th class="th-sm">Total</th>
                            <th class="th-md">Penyedia</th>
                            <th class="th-md">Dokumen Pendukung</th>
                            <th class="th-md">Status</th>
                        </tr>
                      </thead>
                      <tbody>
                       @foreach ($permohonans as $permohonan)
                           <tr>
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td>{{$permohonan->idPermohonan}}</td>
                                <td>{{$permohonan->username}}</td>
                                <td>{{$permohonan->nama_barang}}</td>
                                <td>
                                    <ul>
                                        @foreach ($komponens as $komponen)
                                            @if ($permohonan->idPermohonan == $komponen->permohonan_id)
                                             <li>{{$komponen->nama_barang}} {{$komponen->frekuensi}}x</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    @foreach ($akuns as $akun)
                                        @if ($akun->idAkun == $permohonan->akun_x_rkakl_id)
                                            {{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{$permohonan->tgl_permohonan}}</td>
                                <td>{{$permohonan->tgl_pemeriksaan}}</td>
                                <td>{{$permohonan->tgl_pengerjaan}}</td>
                                <td>{{$permohonan->tgl_selesai}}</td>
                                <td>{{$permohonan->nominal}}</td>
                                <td>{{$permohonan->pph}} %</td>
                                <td>{{$permohonan->total}}</td>
                                <td>{{$permohonan->nama_CV}}</td>
                                <td>
                                    <ul>
                                        @foreach ($dokumens as $dokumen)
                                            @if ($dokumen->service_id == $permohonan->idService)
                                                <li>{{$dokumen->nama_dokumen}}</li>    
                                            @endif
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{$permohonan->status}}</td>
                           </tr>
                       @endforeach
                       @foreach ($permohonanPegawais as $permohonanPegawai)
                           <tr>
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td>{{$permohonanPegawai->idPermohonan}}</td>
                                <td>{{$permohonanPegawai->nama_lengkap}}</td>
                                <td>{{$permohonanPegawai->nama_barang}}</td>
                                <td>
                                    <ul>
                                        @foreach ($komponens as $komponen)
                                            @if ($permohonanPegawai->idPermohonan == $komponen->permohonan_id)
                                             <li>{{$komponen->nama_barang}} {{$komponen->frekuensi}}x</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    @foreach ($akuns as $akun)
                                        @if ($akun->idAkun == $permohonanPegawai->akun_x_rkakl_id)
                                            {{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{$permohonanPegawai->tgl_permohonan}}</td>
                                <td>{{$permohonanPegawai->tgl_pemeriksaan}}</td>
                                <td>{{$permohonanPegawai->tgl_pengerjaan}}</td>
                                <td>{{$permohonanPegawai->tgl_selesai}}</td>
                                <td>{{$permohonanPegawai->nominal}}</td>
                                <td>{{$permohonanPegawai->pph}} %</td>
                                <td>{{$permohonanPegawai->total}}</td>
                                <td>{{$permohonanPegawai->nama_CV}}</td>
                                <td>
                                    <ul>
                                        @foreach ($dokumens as $dokumen)
                                            @if ($dokumen->service_id == $permohonanPegawai->idService)
                                                <li>{{$dokumen->nama_dokumen}}</li>    
                                            @endif
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{$permohonanPegawai->status}}</td>
                           </tr>
                       @endforeach
                       @foreach ($kendaraans as $kendaraan)
                           <tr>
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td>{{$kendaraan->idPermohonan}}</td>
                                <td>{{$kendaraan->username}}</td>
                                <td>{{$kendaraan->merek}} {{$kendaraan->no_polisi}}</td>
                                <td>
                                    <ul>
                                        @foreach ($komponens as $komponen)
                                            @if ($kendaraan->idPermohonan == $komponen->permohonan_id)
                                             <li>{{$komponen->nama_barang}} {{$komponen->frekuensi}}x</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    @foreach ($akuns as $akun)
                                        @if ($akun->idAkun == $kendaraan->akun_x_rkakl_id)
                                            {{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{$kendaraan->tgl_permohonan}}</td>
                                <td>{{$kendaraan->tgl_pemeriksaan}}</td>
                                <td>{{$kendaraan->tgl_pengerjaan}}</td>
                                <td>{{$kendaraan->tgl_selesai}}</td>
                                <td>{{$kendaraan->nominal}}</td>
                                <td>{{$kendaraan->pph}} %</td>
                                <td>{{$kendaraan->total}}</td>
                                <td>{{$kendaraan->nama_CV}}</td>
                                <td>
                                    <ul>
                                        @foreach ($dokumens as $dokumen)
                                            @if ($dokumen->service_id == $kendaraan->idService)
                                                <li>{{$dokumen->nama_dokumen}}</li>    
                                            @endif
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{$kendaraan->status}}</td>
                           </tr>
                       @endforeach
                       @foreach ($ruangans as $ruangan)
                           <tr>
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td>{{$ruangan->idPermohonan}}</td>
                                <td>{{$ruangan->username}}</td>
                                <td>{{$ruangan->nama_ruangan}} {{$ruangan->kode_ruangan}}</td>
                                <td>
                                    <ul>
                                        @foreach ($komponens as $komponen)
                                            @if ($ruangan->idPermohonan == $komponen->permohonan_id)
                                             <li>{{$komponen->nama_barang}} {{$komponen->frekuensi}}x</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    @foreach ($akuns as $akun)
                                        @if ($akun->idAkun == $ruangan->akun_x_rkakl_id)
                                            {{$akun->kode_satker}}.{{$akun->kode_program}}.{{$akun->kode_kegiatan}}.{{$akun->kode_output}}.{{$akun->kode_sub_output}}.{{$akun->kode_komponen}}.{{$akun->kode_sub_kegiatan}}.{{$akun->kode_akun}}
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{$ruangan->tgl_permohonan}}</td>
                                <td>{{$ruangan->tgl_pemeriksaan}}</td>
                                <td>{{$ruangan->tgl_pengerjaan}}</td>
                                <td>{{$ruangan->tgl_selesai}}</td>
                                <td>{{$ruangan->nominal}}</td>
                                <td>{{$ruangan->pph}} %</td>
                                <td>{{$ruangan->total}}</td>
                                <td>{{$ruangan->nama_CV}}</td>
                                <td>
                                    <ul>
                                        @foreach ($dokumens as $dokumen)
                                            @if ($dokumen->service_id == $ruangan->idService)
                                                <li>{{$dokumen->nama_dokumen}}</li>    
                                            @endif
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{$ruangan->status}}</td>
                           </tr>
                       @endforeach
                      </tbody>
                    </table>
                </div>
            </div>
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
      saveAs(new Blob([excelBuffer], { type: 'application/octet-stream' }), 'Detail Transaksi Service.xlsx');
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