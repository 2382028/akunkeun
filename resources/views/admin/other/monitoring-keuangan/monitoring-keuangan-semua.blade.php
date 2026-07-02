@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
          <h4>Monitoring Keuangan 
            <input type="hidden" id="indukValue" value="{{$kode_induk_program}}....{{$kode_akun}}">
            <button type="button" id="detailPenggunaanButton" class="btn btn-dark btn-md" data-bs-toggle="modal" data-bs-target="#detail_penggunaan">
                {{ $kode_induk_program ?? '...' }}.{{ $kode_kegiatan ?? ' ... ' }}.{{ $kode_output ?? ' ... ' }}.{{ $kode_sub_output ?? ' ... ' }}.{{ $kode_komponen ?? ' ... ' }}.{{ $kode_sub_komponen ?? ' ... ' }}.{{ $kode_akun ?? ' ... ' }} - {{ $uraian ?? '...' }}
            </button>
          </h4>
        </div>
    </div>

    <div class="row">
      <div class="col-md-12 mb-3">
        <div class="card">
          <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="detail-tab" data-bs-toggle="tab" data-bs-target="#detail" type="button" role="tab" aria-controls="detail" aria-selected="true">
                  <i class="fa-solid fa-list"></i> Detail Penggunaan
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="chart-tab" data-bs-toggle="tab" data-bs-target="#chart" type="button" role="tab" aria-controls="chart" aria-selected="false">
                  <i class="fa-solid fa-chart-bar"></i> Chart Penggunaan
                </button>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <div>
                    <h6 class="fw-bold text-secondary">Informasi Penggunaan Anggaran Detail</h6>
                  </div>
                  <div class="text-end">
                    <button id="downloadexcel" class="btn btn-success btn-sm"><i class="fa-solid fa-file-excel"></i> Export to Excel</button>
                  </div>
                </div>
                <div class="table-responsive">
                  <table id="example" class="table table-bordered data-table-perakun" style="width: 100%">
                    <thead>
                      <tr class="text-center small">
                        <th class="th-sm">No</th>
                        <th class="th-md">Kode Induk</th>
                        <th class="th-md">Uraian Akun</th>
                        <th class="th-md">Uraian Komponen</th>
                        <th class="th-md">Nominal Anggaran</th>
                        <th class="th-md">Nominal Penggunaan</th>
                        <th class="th-md">Sisa Anggaran</th>
                      </tr>
                    </thead>
                    @foreach ($akuns as $akunxrkakl)
                    <tr>
                      <td class='text-center'>{{ $loop->iteration }}</td>
                      <td class="text-center">
                        {{ $akunxrkakl->kode_satker }}.{{ $akunxrkakl->kode_program }}.{{ $akunxrkakl->kode_kegiatan }}.{{ $akunxrkakl->kode_output }}.{{ $akunxrkakl->kode_sub_output }}.{{ $akunxrkakl->kode_komponen }}.{{ $akunxrkakl->kode_sub_kegiatan }}.{{ $akunxrkakl->kode_akun }}
                      </td>
                      <td class=''>{{ $akunxrkakl->uraian }}</td>
                      <td class=''>{{ $akunxrkakl->nama_sub_kegiatan}}</td>
                      <td class="text-center">Rp {{ number_format($akunxrkakl->nominal, 0, ',', '.') }}</td>
                      <td class='text-center'>Rp {{ number_format($akunxrkakl->penggunaan, 0, ',', '.') }}</td>
                      <td class='text-center'>Rp {{ number_format($akunxrkakl->nominal - $akunxrkakl->penggunaan, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tfoot>
                      <tr>
                        <td colspan="4" class="text-center"><strong>Total Keseluruhan</strong></td>
                        <td class="text-center"><strong>Rp {{ number_format($total->nominal, 0, ',', '.') }}</strong></td>
                        <td class="text-center"><strong>Rp {{ number_format($total->penggunaan, 0, ',', '.') }}</strong></td>
                        <td class="text-center"><strong>Rp {{ number_format($total->nominal - $total->penggunaan, 0, ',', '.') }}</strong></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
              <div class="tab-pane fade" id="chart" role="tabpanel" aria-labelledby="chart-tab">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <div>
                    <h6 class="fw-bold text-secondary">Chart Penggunaan Anggaran</h6>
                  </div>
                </div>
                <div class="chart-container" style="position: relative; height:60vh; width:100%">
                  <canvas id="usageChart"></canvas>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                  var ctx = document.getElementById('usageChart').getContext('2d');

                  var labels = @json($akuns->map(fn($akun) => "{$akun->kode_kegiatan}.{$akun->kode_output}.{$akun->kode_sub_output}.{$akun->kode_komponen}.{$akun->kode_sub_kegiatan}"));

                  var namaSubKegiatan = @json($akuns->pluck('nama_sub_kegiatan')); // Ambil nama sub kegiatan untuk tooltip

                  var usageChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                      labels: labels, // Label tetap tanpa nama_sub_kegiatan
                      datasets: [
                        {
                          label: 'Nominal Anggaran',
                          data: @json($akuns->pluck('nominal')),
                          backgroundColor: 'rgba(75, 192, 192, 0.2)',
                          borderColor: 'rgba(75, 192, 192, 1)',
                          borderWidth: 1
                        },
                        {
                          label: 'Nominal Penggunaan',
                          data: @json($akuns->pluck('penggunaan')),
                          backgroundColor: 'rgba(54, 162, 235, 0.2)',
                          borderColor: 'rgba(54, 162, 235, 1)',
                          borderWidth: 1
                        }
                      ]
                    },
                    options: {
                      responsive: true,
                      maintainAspectRatio: false,
                      scales: {
                        y: {
                          beginAtZero: true,
                          ticks: {
                            callback: function(value) {
                              return 'Rp ' + value.toLocaleString();
                            }
                          }
                        }
                      },
                      plugins: {
                        legend: {
                          display: true,
                          position: 'top',
                        },
                        tooltip: {
                          callbacks: {
                            title: function(context) {
                              let index = context[0].dataIndex; // Dapatkan index data yang sedang di-hover
                              return labels[index] + " - " + namaSubKegiatan[index]; // Tambahkan nama_sub_kegiatan ke tooltip
                            },
                            label: function(context) {
                              let label = context.dataset.label || '';
                              if (label) {
                                label += ': ';
                              }
                              label += 'Rp ' + context.raw.toLocaleString();
                              return label;
                            }
                          }
                        }
                      }
                    }
                  });
                });
              </script>



              </div>
            </div>
            <div class="text-center mt-4">
              <a href="{{url('/monitoring-keuangan')}}" class="btn btn-dark btn-lg">Kembali</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Detail Penggunaan -->
  <div class="modal fade" id="detail_penggunaan" tabindex="-1" aria-labelledby="tambah_fasilitasLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="tambah_fasilitasLabel">Detail Penggunaan Anggaran</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form id="detailPenggunaanForm" method="get" action="{{ url('/monitoring-keuangan/detail/per-akun') }}">
            @csrf
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="kode_induk_program" class="form-label">Kode Induk Program <span class="text-secondary small"></span><span class="text-danger">*</span></label>
                    <select class="form-select" id="kode_induk_program" name="kode_induk_program" required>
                        <option value="" disabled selected>Pilih Kode Induk Program</option>
                        <!-- Opsi akan diisi oleh JavaScript -->
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="kode_akun" class="form-label">Kode Akun <span class="text-secondary small"></span><span class="text-danger">*</span></label>
                    <select class="form-select" id="kode_akun" name="kode_akun" required>
                        <option value="" disabled selected>Pilih Kode Akun</option>
                        <!-- Opsi akan diisi oleh JavaScript -->
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Lihat Penggunaan</button>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Tambahkan jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Ambil data program dari route /get-unique-programs-json
        $.ajax({
            url: "{{ route('unique-programs-json') }}",
            method: "GET",
            success: function (response) {
                // Kosongkan select option kode_induk_program
                $('#kode_induk_program').empty();
                $('#kode_induk_program').append('<option value="" disabled selected>Pilih Kode Induk Program</option>');

                // Isi select option kode_induk_program dengan data dari response
                response.forEach(function (program) {
                    $('#kode_induk_program').append(
                        `<option value="${program.kode_satker}.${program.kode_program}">${program.kode_satker}.${program.kode_program}</option>`
                    );
                });
            },
            error: function (xhr) {
                console.error("Gagal mengambil data program:", xhr.responseText);
            }
        });

        // Ketika kode_induk_program dipilih, ambil data akun berdasarkan kode_satker dan kode_program
        $('#kode_induk_program').on('change', function () {
            let selectedProgram = $(this).val();
            let parts = selectedProgram.split('.'); // Misal: ["693206", "139", "03", "DK"]
            let kode_satker = parts[0]; // Ambil elemen pertama sebagai kode_satker
            let kode_program = parts.slice(1).join('.'); // Gabungkan elemen setelah pertama sebagai kode_program


            // Ambil data akun dari route /get-unique-akuns-json/{kode_satker}/{kode_program}
            $.ajax({
                url: "{{ route('unique-akuns-json', ['kode_satker' => ':kode_satker', 'kode_program' => ':kode_program']) }}"
                    .replace(':kode_satker', kode_satker)
                    .replace(':kode_program', kode_program),
                method: "GET",
                success: function (response) {
                    // Kosongkan select option kode_akun
                    $('#kode_akun').empty();
                    $('#kode_akun').append('<option value="" disabled selected>Pilih Kode Akun</option>');

                    // Isi select option kode_akun dengan data dari response
                    response.forEach(function (akun) {
                        $('#kode_akun').append(
                            `<option value="${akun.kode_akun}">${akun.kode_akun} - ${akun.uraian}</option>`
                        );
                    });
                },
                error: function (xhr) {
                    console.error("Gagal mengambil data akun:", xhr.responseText);
                }
            });
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    let indukValue = document.getElementById("indukValue").value;
    document.getElementById('downloadexcel').addEventListener('click', function() {
   
        // Format tanggal saat ini
        var now = new Date();
        var formattedDate = now.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' }).replace(/\//g, "");
        var formattedTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace(":", "-");

        // Nama file dengan format Monitoring_Keuangan_03052025_21-50.xlsx
        var fileName = `DetailAkun_${indukValue}_${formattedDate}_${formattedTime}.xlsx`;

        // Ambil semua data dari DataTable
        var table = $('#example').DataTable();
        var allData = table.rows().data().toArray();

        // Buat header sesuai dengan struktur tabel
        var header = [
            "No", "Kode Induk", "Uraian Akun", "Uraian Komponen", 
            "Nominal Anggaran", "Nominal Penggunaan", "Sisa Anggaran"
        ];

        // Fungsi untuk format Rupiah
        function formatRupiah(angka) {
            return "Rp " + angka.toLocaleString("id-ID");
        }

        // Konversi data ke format array yang sesuai
        var data = allData.map((row, index) => [
            index + 1, 
            String(row[1]).replace(/\s+/g, ""),  // Hapus semua spasi dan newline  
            row[2],  
            row[3], // Hilangkan <br> dan spasi berlebih  
            parseFloat(row[4].replace("Rp ", "").replace(/\./g, "")),  // Perbaiki koma di sini
            parseFloat(row[5].replace("Rp ", "").replace(/\./g, "")),  // Perbaiki koma di sini
            parseFloat(row[6].replace("Rp ", "").replace(/\./g, ""))   // Perbaiki koma di sini
        ]);

        // Hitung total keseluruhan
        var totalAnggaran = allData.reduce((sum, row) => sum + parseFloat(row[4].replace("Rp ", "").replace(/\./g, "")), 0);
        var totalPenggunaan = allData.reduce((sum, row) => sum + parseFloat(row[5].replace("Rp ", "").replace(/\./g, "")), 0);
        var totalSisa = allData.reduce((sum, row) => sum + parseFloat(row[6].replace("Rp ", "").replace(/\./g, "")), 0);

        // Tambahkan baris footer
        // Buat footer dengan hanya satu sel berisi teks "Total Seluruh Penggunaan", lalu merge
        var footer = [["Total Keseluruhan", "", "", "", totalAnggaran, totalPenggunaan, totalSisa]];

        // Buat workbook dan worksheet
        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.aoa_to_sheet([header, ...data]);

        // Tambahkan footer ke worksheet
        XLSX.utils.sheet_add_aoa(ws, footer, { origin: -1 });

        // Gabungkan sel (merge) dari kolom A sampai G (1-7) pada footer
        ws["!merges"] = [{ s: { r: data.length + 1, c: 0 }, e: { r: data.length + 1, c: 3 } }];

        // Tambahkan worksheet ke workbook
        XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

        // Simpan file Excel
        XLSX.writeFile(wb, fileName);
    });
</script>

@endsection