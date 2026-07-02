@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <h4>Laporan Perjalanan Dinas Langsung - HKT</h4>
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
                                        <th class="th-md">Nama Kegiatan</th>
                                        <th class="th-md">Keberangkatan</th>
                                        <th class="th-md">Mulai Kegiatan</th>
                                        <th class="th-md">Selesai Kegiatan</th>
                                        <th class="th-md">No Undangan</th>
                                        <th class="th-md">Asal Undangan</th>
                                        <th class="th-md">Alamat</th>
                                        <th class="th-md">Kab/Kota</th>
                                        <th class="th-md">Provinsi</th>
                                        <th class="th-md">Pengusul</th>
                                        <th class="th-md">Nama Peserta</th>
                                        <th class="th-md">No Surtug</th>
                                        <th class="th-md">Tgl Surtug</th>
                                        <th class="th-md">Status HKT</th>
                                        <th class="th-md">Status Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $item->id_kegiatan }}</td>
                                            <td>{{ $item->nama_kegiatan }}</td>
                                            <td>{{ $item->tgl_keberangkatan }}</td>
                                            <td>{{ $item->tgl_mulai }}</td>
                                            <td>{{ $item->tgl_selesai }}</td>
                                            <td>{{ $item->no_undangan }}</td>
                                            <td>{{ $item->pemberi_undangan }}</td>
                                            <td>{{ $item->alamat }}</td>
                                            <td>{{ $item->kabupaten_kota }}</td>
                                            <td>{{ $item->provinsi }}</td>
                                            <td>{{ $item->nama_pengaju }}</td>
                                            <td>{!! nl2br(e($item->nama_peserta)) !!}</td>
                                            <td>{{ $item->no_surtug }}</td>
                                            <td>{{ $item->tgl_surtug }}</td>
                                            <td>{{ $item->is_acceptHKT }}</td>
                                            <td>{{ $item->status_pengajuan_detail }}</td>
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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

  <script>
    document.getElementById('exportButton').addEventListener('click', function() {
   // Mendapatkan nilai variabel PHP di JavaScript dari Blade
   var mulai = "{{ $mulai }}";  // Dari Blade template
  var sampai = "{{ $sampai }}";  // Dari Blade template

  // Mengambil data dari server
  fetch(`/laporanHKT/data/${mulai}/${sampai}`)
    .then(response => response.json())
    .then(data => {
      var tableData = [];

      // Menyusun data ke dalam format array of arrays
      tableData.push([
  'No', // Tidak ada di data, tambahkan nanti saat perulangan atau urutkan secara manual di Excel.
  'ID Kegiatan',
  'Nama Kegiatan',
  'Keberangkatan',
  'Mulai Kegiatan',
  'Selesai Kegiatan',
  'No Undangan',
  'Asal Undangan',
  'Alamat',
  'Kab/Kota',
  'Provinsi',
  'Pengusul',  // Nama pengaju
  'Nama Peserta', // Gabungkan beberapa nama peserta dengan '\n'
  'No Surtug',
  'Tgl Surtug',
  'Status HKT',
  'Status Detail'
]);

// Iterasi data
data.forEach((item, index) => {
  tableData.push([
    index + 1, // Nomor
    item.id_kegiatan,
    item.nama_kegiatan,
    item.tgl_keberangkatan,
    item.tgl_mulai,
    item.tgl_selesai,
    item.no_undangan,
    item.pemberi_undangan,
    item.alamat,
    item.kabupaten_kota,
    item.provinsi,
    item.nama_pengaju, // Nama pengaju
    item.nama_peserta ? item.nama_peserta.replace(/\n/g, '\n') : '', // Gabungkan nama peserta dengan newline
    item.no_surtug,
    item.tgl_surtug,
    item.is_acceptHKT,
    item.status_pengajuan_detail
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