@extends('admin.templates.sidebar')

@section('contain')


<div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        @if ($akuns->isNotEmpty())
            <h5 id="detailAnggaran">Detail Penggunaan Anggaran
                <br>
                {{ $akuns[0]->kode_satker }}.
                {{ $akuns[0]->kode_program }}.
                {{ $akuns[0]->kode_kegiatan }}.
                {{ $akuns[0]->kode_output }}.
                {{ $akuns[0]->kode_sub_output }}.
                {{ $akuns[0]->kode_komponen }}.
                {{ $akuns[0]->kode_sub_kegiatan }}.
                {{ $akuns[0]->kode_akun }}
            </span>
            <a href="{{url('/monitoring-keuangan')}}" class="btn btn btn-danger">
                Rp {{ number_format($akuns[0]->penggunaan, 0, ',', '.') }}

            </a>
        </h5>

        @else
            <h5>Akun Tidak Ditemukan
            </span>
        </h5>
        @endif
      </div>
    </div>


    <div class="row mb-4">
        <div class="col-md-12">
        <div class=" d-flex justify-content-start gap-2">
            <div class="d-flex justify-content-start gap-2">
                <a href="{{ route('monitoring-keuangan-detail', ['tipe' => 'semua', 'id' => $idAkun]) }}"
                    class="btn {{ $isSemua ? 'btn-primary' : 'btn-light' }}">
                    Semua
                </a>
                <a href="{{ route('monitoring-keuangan-detail', ['tipe' => 'perjadin', 'id' => $idAkun]) }}"
                    class="btn {{ $isPerjadin ? 'btn-primary' : 'btn-light' }}">
                    Perjalanan Dinas
                </a>
                <a href="{{ route('monitoring-keuangan-detail', ['tipe' => 'kegiatan', 'id' => $idAkun]) }}"
                    class="btn {{ $isKegiatan ? 'btn-primary' : 'btn-light' }}">
                    Kegiatan
                </a>
                <a href="{{ route('monitoring-keuangan-detail', ['tipe' => 'bmn', 'id' => $idAkun]) }}"
                    class="btn {{ $isBMN ? 'btn-primary' : 'btn-light' }}">
                    BMN
                </a>
            </div>
        </div>
        </div>
    </div>

    <div class="row">
      <div class="col-md-12 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                @if ($akuns->isNotEmpty())
                <div>

                </div>
                <div class="text-end">
                    <button id="downloadexcel" class="btn btn-success btn-sm"><i class="fa-solid fa-file-excel"></i> Export to Excel</button>
                </div>
                @endif
            </div>
            @if ($akuns->isNotEmpty())
            <div class="table-responsive">

                @if ($tipe == 'bmn')
                    <table id="example" class="table table-bordered" style="width: 100%">
                        <thead>
                        <tr class="text-center small">
                            <th class="th-sm">No</th>
                            <th class="th-sm">ID Ajuan</th>
                            <th class="th-md">Nama Aset</th>
                            <th class="th-md">Alasan Ajuan</th>
                            <th class="th-md">Tanggal Permohonan</th>
                            <th class="th-md">Nama Pengaju</th>
                            <th class="th-md">Status</th>
                            <th class="th-md">Nominal Penggunaan</th>
                        </tr>
                        </thead>
                        @php
                            $totalSeluruhAnggaran = 0;
                        @endphp
                        @foreach ($keuangans as $keuangan)
                        <tr>
                            <td class='text-center'></td>
                            <td class="text-center">{{$keuangan->idService}}</td>
                            <td class="text-center">{{$keuangan->deskripsiAsset}}</td>
                            <td class="text-center">{{$keuangan->alasan_ket}}</td>
                            <td class="text-center th-sm small">
                                {{\Carbon\Carbon::parse($keuangan->tgl_permohonan)->format('d-m-Y')}}
                            </td>
                            <td class="text-center">{{$keuangan->penanggungJawab}}</td>
                            <td class='text-center'>{{ $keuangan->status}}</td>
                            <td class='text-center'>Rp {{ number_format($keuangan->totalPenggunaan, 0, ',', '.') }}</td>
                        </tr>
                        @php
                            $totalSeluruhAnggaran += $keuangan->totalPenggunaan;
                        @endphp
                        @endforeach
                        <tfoot>
                            <tr>
                                <td colspan="7" class="text-right"><strong>Total Seluruh Penggunaan {{ strtoupper($tipe) }}</strong></td>
                                <td class="text-center"><strong>Rp {{ number_format($totalSeluruhAnggaran, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <table id="example" class="table table-bordered" style="width: 100%">
                        <thead>
                        <tr class="text-center small">
                            <th class="th-sm">No</th>
                            <th class="th-sm">ID Ajuan</th>
                            <th class="th-md">Nama Kegiatan</th>
                            <th class="th-md">Tanggal Pelaksanaan</th>
                            <th class="th-md">Tempat</th>
                            <th class="th-md">Surat Tugas</th>
                            <th class="th-md">Status</th>
                            <th class="th-md">Nominal Penggunaan</th>
                        </tr>
                        </thead>
                        @php
                            $totalSeluruhAnggaran = 0;
                        @endphp
                        @foreach ($keuangans as $keuangan)
                        <tr>
                            <td class='text-center'></td>
                            @php
                                $tipe = $keuangan->tipe;
                            @endphp
                            @if ($tipe == 'perjadin')
                                <td class="text-center"
                                    onclick="window.location.href='{{ url('/admin/other-perjadin/detail/'.$keuangan->id.'?tipe=keuangan') }}'"
                                    style="cursor: pointer; text-decoration: underline;"
                                    onmouseover="this.style.backgroundColor='#e9ecef';"
                                    onmouseout="this.style.backgroundColor='';">
                                    @if ($isSemua) 
                                        P-{{$keuangan->id}}
                                    @else
                                        {{$keuangan->id}}
                                    @endif
                                </td>
                            @elseif ($tipe == 'kegiatan')
                                <td class="text-center"
                                    onclick="window.location.href='{{ url('/admin/other-kegiatan/detail/'.$keuangan->id.'?tipe=keuangan') }}'"
                                    style="cursor: pointer; text-decoration: underline;"
                                    onmouseover="this.style.backgroundColor='#e9ecef';"
                                    onmouseout="this.style.backgroundColor='';">
                                    @if ($isSemua)
                                        K-{{$keuangan->id}}
                                    @else
                                        {{$keuangan->id}}
                                    @endif
                                </td>
                            @else
                                <td class="text-center">{{$keuangan->id}}</td>
                            @endif
                            <td class="">{{$keuangan->nama_kegiatan}}</td>
                            <td class="text-center th-sm small">
                                {{\Carbon\Carbon::parse($keuangan->tgl_keberangkatan)->format('d-m-Y')}}
                                s.d <br>{{\Carbon\Carbon::parse($keuangan->tgl_selesai)->format('d-m-Y')}}
                            </td>
                            <td class=''>{{ $keuangan->kabupaten_kota}}, {{$keuangan->provinsi}}</td>
                            <td class='text-center'>{{ $keuangan->kode_surat_tugas }}</td>
                            <td class='text-center'>{{ $keuangan->status_pengajuan_detail}}</td>
                            <td class='text-center'>Rp {{ number_format($keuangan->totalPenggunaan, 0, ',', '.') }}</td>
                        </tr>
                        @php
                            $totalSeluruhAnggaran += $keuangan->totalPenggunaan;
                        @endphp
                        @endforeach
                        <tfoot>
                            <tr>
                                <input type="hidden" id="tipeValue" value="{{ ucfirst($tipe) }}">
                                <th colspan="7" class="text-right"><strong>Total Seluruh Penggunaan {{ ucfirst($tipe) }}</strong></th>
                                <th class="text-center"><strong>Rp {{ number_format($totalSeluruhAnggaran, 0, ',', '.') }}</strong></th>
                            </tr>
                        </tfoot>
                    </table>
                @endif
            </div>
            @else
            <div class="container text-center">
                <img src="{{ asset('public/assets/images/empty.svg') }}" class="mb-3" width="150px" alt=""><br>
                <h3 class="text-center">Akun Tidak Ditemukan Pada Tahun Anggaran ini!</h3><br>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
   <!-- Pemuatan jQuery 3.6.0 -->
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Pemuatan DataTables JS (Versi 1.11.5 yang kompatibel dengan jQuery 3.6.0) -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


<script>
$(document).ready(function () {
    const table = $('#example');

    if ($.fn.DataTable.isDataTable(table)) {
        table.DataTable().clear().destroy(); // benar-benar bersihkan
    }

    table.DataTable({
        destroy: true, // pengaman tambahan agar tidak reinitialise
        order: [], // supaya tidak mengganggu nomor urut
        columnDefs: [{
            targets: 0, // Kolom nomor urut
            searchable: false,
            orderable: false,
        }],
        drawCallback: function (settings) {
            const api = this.api();
            api.column(0, { search: 'applied', order: 'applied', page: 'current' })
                .nodes()
                .each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
        },
        footerCallback: function (row, data, start, end, display) {
            let api = this.api();

            let intVal = function (i) {
                return typeof i === 'string'
                    ? i.replace(/[Rp\s.]/g, '') * 1
                    : typeof i === 'number'
                    ? i
                    : 0;
            };

            let total = api
                .column(7)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);

            let pageTotal = api
                .column(7, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);

            api.column(7).footer().innerHTML =
                'Rp ' + pageTotal.toLocaleString('id-ID');
        }
    });
});
</script>

<script>
    let tipeValue = document.getElementById("tipeValue").value;
document.getElementById('downloadexcel').addEventListener('click', function() {
    let anggaranText = document.getElementById('detailAnggaran').innerText;
    
    // Hapus teks "Detail Penggunaan Anggaran" dari string
    anggaranText = anggaranText.replace("Detail Penggunaan Anggaran", "").trim();
    
    // Hapus newline dan spasi ekstra
    anggaranText = anggaranText.replace(/\s+/g, "");

    // Format tanggal saat ini
    var now = new Date();
    var formattedDate = now.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' }).replace(/\//g, "");
    var formattedTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace(":", "-");

    // Nama file dengan format Monitoring_Keuangan_03052025_21-50.xlsx
    var fileName = `Detail-${tipeValue}_${anggaranText}_${formattedDate}_${formattedTime}.xlsx`;

    // Ambil semua data dari DataTable
    var table = $('#example').DataTable();
    var allData = table.rows().data().toArray();

    // Buat header sesuai dengan struktur tabel
    var header = [
        "No", "ID Ajuan", "Nama Kegiatan", "Tanggal Pelaksanaan", 
        "Tempat", "Surat Tugas", "Status", "Nominal Penggunaan"
    ];

    // Fungsi untuk format Rupiah
    function formatRupiah(angka) {
        return "Rp " + angka.toLocaleString("id-ID");
    }

    // Konversi data ke format array yang sesuai
    var data = allData.map((row, index) => [
        index + 1, 
        row[1],  
        row[2],  
        row[3].replace(/<br\s*\/?>/gi, " ").replace(/\s+/g, " ").trim(), // Hilangkan <br> dan spasi berlebih 
        row[4],  
        row[5],  
        row[6],  
        formatRupiah(parseFloat(row[7].replace("Rp ", "").replace(/\./g, "")))
    ]);

    // Hitung total keseluruhan
    var totalPenggunaan = allData.reduce((sum, row) => sum + parseFloat(row[7].replace("Rp ", "").replace(/\./g, "")), 0);

    // Tambahkan baris footer
    // Buat footer dengan hanya satu sel berisi teks "Total Seluruh Penggunaan", lalu merge
    var footer = [["Total Seluruh Penggunaan " + tipeValue, "", "", "", "", "", "", formatRupiah(totalPenggunaan)]];

    // Buat workbook dan worksheet
    var wb = XLSX.utils.book_new();
    var ws = XLSX.utils.aoa_to_sheet([header, ...data]);

    // Tambahkan footer ke worksheet
    XLSX.utils.sheet_add_aoa(ws, footer, { origin: -1 });

    // Gabungkan sel (merge) dari kolom A sampai G (1-7) pada footer
    ws["!merges"] = [{ s: { r: data.length + 1, c: 0 }, e: { r: data.length + 1, c: 6 } }];

    // Tambahkan worksheet ke workbook
    XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

    // Simpan file Excel
    XLSX.writeFile(wb, fileName);
});
</script>
@endsection
