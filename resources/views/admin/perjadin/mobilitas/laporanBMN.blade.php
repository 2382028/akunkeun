@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <h4>Laporan Perjalanan Dinas Langsung - BMN</h4>
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
            
                <div class="table-responsive">
                    
                    <table id="myTable" class="table table-bordered data-table" style="width: 100%">
                    <thead>
                                    <tr class="text-center small">
                                        <th class="th-sm">No</th>
                                        <th class="th-sm">ID Kegiatan</th>
                                        <th class="th-sm">Tipe Kegiatan</th>
                                        <th class="th-md">Pengusul</th>
                                        <th class="th-md">Nama Kegiatan</th>
                                        <th class="th-md">Nama Peserta</th>
                                        <th class="th-md">Keberangkatan</th>
                                        <th class="th-md">Selesai</th>
                                        <th class="th-md">Kab/Kota</th>
                                        <th class="th-md">Provinsi</th>
                                        <th class="th-md">Kendaaraan</th>
                                        <th class="th-md">Nomor Polisi</th>
                                        <th class="th-md">Supir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $item->id_kegiatan }}</td>
                                            <td>{{ $item->tipe_kegiatan }}</td>
                                            <td>{{ $item->nama_pengaju }}</td>
                                            <td>{{ $item->nama_kegiatan }}</td>
                                            <td>{!! nl2br(e($item->nama_peserta)) !!}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tgl_keberangkatan)->translatedFormat('d F Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tgl_selesai)->translatedFormat('d F Y') }}</td><td>{{ $item->kabupaten_kota }}</td>
                                            <td>{{ $item->provinsi }}</td>
                                            <td>{{ $item->merek }}</td>
                                            <td>{{ $item->no_polisi }}</td>
                                            <td>{{ $item->pengemudi }}</td>
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
   // Mendapatkan nilai variabel PHP di JavaScript dari Blade
   var mulai = "{{ $mulai }}";  // Dari Blade template
  var sampai = "{{ $sampai }}";  // Dari Blade template

  // Mengambil data dari server
  fetch(`/laporanBMN/data/${mulai}/${sampai}`)
    .then(response => response.json())
    .then(data => {
      var tableData = [];

      // Menyusun data ke dalam format array of arrays
      tableData.push([
        'No',
        'ID Kegiatan',
        'Tipe Kegiatan',
        'Pengusul',
        'Nama Kegiatan',
        'Nama Peserta',
        'Keberangkatan',
        'Selesai',
        'Kab/Kota',
        'Provinsi',
        'Kendaaraan',
        'Nomor Polisi',
        'Supir'                           
]);

// Iterasi data
data.forEach((item, index) => {
  tableData.push([
    index + 1, // Nomor
    item.id_kegiatan,
    item.tipe_kegiatan,
    item.nama_pengaju,
    item.nama_kegiatan,
    item.nama_peserta ? item.nama_peserta.replace(/\n/g, '\n') : '', // Gabungkan nama peserta dengan newline
    item.tgl_keberangkatan,
    item.tgl_selesai,
    item.kabupaten_kota,
    item.provinsi,
    item.merek, // Nama pengaju
    item.no_polisi,
    item.pengemudi
  ]);
});


      // Create a workbook and worksheet
      var workbook = XLSX.utils.book_new();
      var worksheet = XLSX.utils.aoa_to_sheet(tableData);

      // Add the worksheet to the workbook
      XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet 1');

      // Convert the workbook to Excel buffer
      var excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });

      // Save the Excel buffer as a file
      saveAs(new Blob([excelBuffer], { type: 'application/octet-stream' }), 'table_data.xlsx');
    })
    .catch(error => console.error('Error fetching data:', error));
});

  </script>
@endsection