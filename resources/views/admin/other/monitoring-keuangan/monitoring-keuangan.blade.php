@extends('admin.templates.sidebar')

@section('contain')

<!-- Loading Indicator -->
<div id="loadingIndicator" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; text-align: center;">
  <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: flex; flex-direction: column; align-items: center;">
    <div id="lottieAnimation" style="width: 300px; height: 300px;"></div>
    <div class="progress" style="width: 400px; height: 20px; background-color: rgba(255, 255, 255, 0.2); border-radius: 10px; overflow: hidden;">
      <div class="progress-bar" id="progressBar" role="progressbar" style="width: 0%; height: 100%; background: linear-gradient(90deg, #4caf50, #8bc34a); background-size: 200% 100%; animation: progressAnimation 1.5s linear infinite, shimmer 3s linear infinite;"></div>
    </div>

    <p class="mt-3 text-white" style="font-size: 1.5rem;">Data Keuangan Sedang Diproses...</p>
  </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
          <h4>Monitoring Kuangan
            <button type="button" id="detailPenggunaanSemuaButton" class="btn btn-gradient-2 btn-sm" data-bs-toggle="modal" data-bs-target="#detail_penggunaan_semua"><i class="fa-solid fa-info-circle"></i> Detail Penggunaan</button>
            <!-- <button type="button" id="detailPenggunaanButton" class="btn btn-gradient-2 btn-sm" data-bs-toggle="modal" data-bs-target="#detail_penggunaan"><i class="fa-solid fa-info-circle"></i> Detail Penggunaan Akun</button> -->
          <button id="generatePenggunaan" class="btn btn-gradient btn-sm"><i class="fa-solid fa-sync-alt"></i> Perbarui Penggunaan</button>
          </h4>
          <style>
            .btn-gradient {
              background: linear-gradient(45deg, rgb(92, 82, 82), #845ef7, #ff922b);
              background-size: 300% 300%;
              animation: gradientAnimation 5s ease infinite;
              color: white;
              border: none;
            }
            .btn-gradient-2 {
              background: linear-gradient(45deg, #1a252f, #2c3e50, #1a252f);
              background-size: 300% 300%;
              animation: gradientAnimation 5s ease infinite;
              color: white;
              border: none;
            }

            @keyframes gradientAnimation {
              0% { background-position: 0% 50%; }
              50% { background-position: 100% 50%; }
              100% { background-position: 0% 50%; }
            }
          </style>
        </div>
      </div>


    <div class="row">
      <div class="col-md-12 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <h6 class="fw-bold text-secondary">Informasi Penggunaan Anggaran </h6>
                </div>
                <div class="text-end">
                    <button id="downloadexcel" class="btn btn-success btn-sm"><i class="fa-solid fa-file-excel"></i> Export to Excel</button>
                </div>
            </div>
            <div class="table-responsive">

              <table id="example" class="table table-bordered" style="width: 100%">
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
                    <td class='text-center'></td>
                    <td class="text-center"
                        onclick="window.location.href='{{ url('/monitoring-keuangan/detail/'.$akunxrkakl->id.'?tipe=semua') }}'"
                        style="cursor: pointer; text-decoration: underline;"
                        onmouseover="this.style.backgroundColor='#e9ecef';"
                        onmouseout="this.style.backgroundColor='';">
                        {{ $akunxrkakl->kode_satker }}.{{ $akunxrkakl->kode_program }}.{{ $akunxrkakl->kode_kegiatan }}.{{ $akunxrkakl->kode_output }}.{{ $akunxrkakl->kode_sub_output }}.{{ $akunxrkakl->kode_komponen }}.{{ $akunxrkakl->kode_sub_kegiatan }}.{{ $akunxrkakl->kode_akun }}
                    </td>
                    <td class=''>{{ $akunxrkakl->uraian }}</td>
                    <td class=''>{{ $akunxrkakl->nama_sub_kegiatan}}</td>
                    <td class="text-center"
                        onclick="window.location.href='{{ url('/admin-akun_x_rkakl/'.$akunxrkakl->id.'/edit') }}'"
                        style="cursor: pointer; text-decoration: underline;"
                        onmouseover="this.style.backgroundColor='#e9ecef';"
                        onmouseout="this.style.backgroundColor='';">Rp {{ number_format($akunxrkakl->nominal, 0, ',', '.') }}</td>
                    <td class='text-center'>Rp {{ number_format($akunxrkakl->penggunaan, 0, ',', '.') }}</td>
                    <td class='text-center'>Rp {{ number_format($akunxrkakl->nominal - $akunxrkakl->penggunaan, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tfoot>
                <tr>
                  <th colspan="4" class="text-center"><strong>Total Keseluruhan</strong></th>
                  <th class="text-center" id="subtotal-penggunaan"></th>
                  <th class="text-center" id="subtotal-penggunaan"></th>
                  <th class="text-center" id="subtotal-penggunaan"></th>
                  <!-- <td class="text-center"><strong>Rp {{ number_format($total[0]->nominal, 0, ',', '.') }}</strong></td>
                  <td class="text-center"><strong>Rp {{ number_format($total[0]->penggunaan, 0, ',', '.') }}</strong></td>
                  <td class="text-center"><strong>Rp {{ number_format($total[0]->nominal - $total[0]->penggunaan, 0, ',', '.') }}</strong></td> -->
                </tr>
              </tfoot>
              </table>
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

  <div class="modal fade" id="detail_penggunaan_semua" tabindex="-1" aria-labelledby="tambah_fasilitasLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="tambah_fasilitasLabel">Detail Penggunaan Anggaran Detail</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form id="detailPenggunaanForm" method="get" action="{{ url('/monitoring-keuangan/semua'.'?tipe=semua') }}">
            @csrf
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="kode_induk_program_semua" class="form-label">Kode Induk Program <span class="text-secondary small"></span><span class="text-danger">*</span></label>
                    <select class="form-select" id="kode_induk_program_semua" name="kode_induk_program_semua" required>
                        <option value="" disabled selected>Pilih Kode Induk Program</option>
                        <!-- Opsi akan diisi oleh JavaScript -->
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="kode_kegiatan_semua" class="form-label">Kegiatan <span class="text-secondary small"></span><span class="text-danger">*</span></label>
                    <select class="form-select" id="kode_kegiatan_semua" name="kode_kegiatan_semua" required>
                        <option value="" disabled selected>Pilih Kegiatan</option>
                        <!-- Opsi akan diisi oleh JavaScript -->
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="kode_output_semua" class="form-label">Output <span class="text-secondary small"></span><span class="text-danger">*</span></label>
                    <select class="form-select" id="kode_output_semua" name="kode_output_semua" required>
                        <option value="" disabled selected>Pilih Output</option>
                        <!-- Opsi akan diisi oleh JavaScript -->
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="kode_sub_output_semua" class="form-label">Sub Output <span class="text-secondary small"></span><span class="text-danger">*</span></label>
                    <select class="form-select" id="kode_sub_output_semua" name="kode_sub_output_semua" required>
                        <option value="" disabled selected>Pilih Sub Output</option>
                        <!-- Opsi akan diisi oleh JavaScript -->
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="kode_komponen_semua" class="form-label">Komponen<span class="text-secondary small"></span><span class="text-danger">*</span></label>
                    <select class="form-select" id="kode_komponen_semua" name="kode_komponen_semua" required>
                        <option value="" disabled selected>Pilih Komponen</option>
                        <!-- Opsi akan diisi oleh JavaScript -->
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="kode_sub_komponen_semua" class="form-label">Sub Komponen<span class="text-secondary small"></span><span class="text-danger">*</span></label>
                    <select class="form-select" id="kode_sub_komponen_semua" name="kode_sub_komponen_semua" required>
                        <option value="" disabled selected>Pilih Sub Komponen</option>
                        <!-- Opsi akan diisi oleh JavaScript -->
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="kode_akun_semua" class="form-label">Kode Akun <span class="text-secondary small"></span><span class="text-danger">*</span></label>
                    <select class="form-select" id="kode_akun_semua" name="kode_akun_semua" required>
                        <option value="" disabled selected>Pilih Kode Akun</option>
                        <!-- Opsi akan diisi oleh JavaScript -->
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="item_semua" class="form-label">Sertakan Item<span class="text-secondary small"></span><span class="text-danger">*</span></label>
                    <select class="form-select" id="item_semua" name="item_semua" required>
                        <option value="" disabled selected>Pilih Sertakan Item</option>
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
                        `<option value="${program.kode_satker}.${program.kode_program}">${program.kode_satker}.${program.kode_program} - ${program.nama_program}</option>`
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


<script>
    $(document).ready(function () {
        // Ambil data program dari route /get-unique-programs-json
        $.ajax({
            url: "{{ route('unique-programs-json') }}",
            method: "GET",
            success: function (response) {
                // Kosongkan select option kode_induk_program
                $('#kode_induk_program_semua').empty();
                $('#kode_induk_program_semua').append('<option value="" disabled selected>Pilih Kode Induk Program</option>');

                // Isi select option kode_induk_program dengan data dari response
                response.forEach(function (program) {
                    $('#kode_induk_program_semua').append(
                        `<option value="${program.kode_satker}.${program.kode_program}">${program.kode_satker}.${program.kode_program} - ${program.nama_program}</option>`
                    );
                });
            },
            error: function (xhr) {
                console.error("Gagal mengambil data program:", xhr.responseText);
            }
        });

        // Ketika kode_induk_program dipilih, ambil data akun berdasarkan kode_satker dan kode_program
        $('#kode_induk_program_semua').on('change', function () {
            let selectedProgram = $(this).val();
            let parts = selectedProgram.split('.'); // Misal: ["693206", "139", "03", "DK"]
            let kode_satker = parts[0]; // Ambil elemen pertama sebagai kode_satker
            let kode_program = parts.length > 2 ? parts.slice(1).join('.') : parts[1] || ''; // Gabungkan jika lebih dari 2 elemen, jika tidak ambil elemen kedua (jika ada)


            // Ambil data akun dari route /get-unique-akuns-json/{kode_satker}/{kode_program}
            $.ajax({
                url: "{{ route('unique-kegiatans-json', ['kode_satker' => ':kode_satker', 'kode_program' => ':kode_program']) }}"
                    .replace(':kode_satker', kode_satker)
                    .replace(':kode_program', kode_program),
                method: "GET",
                success: function (response) {
                    // Kosongkan select option kode_akun
                    $('#kode_kegiatan_semua').empty();
                    $('#kode_kegiatan_semua').append('<option value="" disabled selected>Pilih Kegiatan</option>');
                    $('#kode_kegiatan_semua').append('<option value="null">ALL - Semua Kegiatan</option>');

                    // Isi select option kode_akun dengan data dari response
                    response.forEach(function (kegiatan) {
                        $('#kode_kegiatan_semua').append(
                            `<option value="${kegiatan.kode_kegiatan}">${kegiatan.kode_kegiatan} - ${kegiatan.nama_kegiatan}</option>`
                        );
                    });
                },
                error: function (xhr) {
                    console.error("Gagal mengambil data Kegiatan:", xhr.responseText);
                }
            });
        });

        // Ketika kode_kegiatan_semua dipilih, ambil data akun berdasarkan kode_satker dan kode_program
        $('#kode_kegiatan_semua').on('change', function () {
          let selectedProgram =  $('#kode_induk_program_semua').val();
          let parts = selectedProgram.split('.'); // Misal: ["693206", "139", "03", "DK"]
          let kode_satker = parts[0]; // Ambil elemen pertama sebagai kode_satker
          let kode_program = parts.length > 2 ? parts.slice(1).join('.') : parts[1] || ''; // Gabungkan jika lebih dari 2 elemen, jika tidak ambil elemen kedua (jika ada)

          let kode_kegiatan = $(this).val();



            // Ambil data akun dari route /get-unique-akuns-json/{kode_satker}/{kode_program}
            $.ajax({
                url: "{{ route('unique-outputs-json', ['kode_satker' => ':kode_satker', 'kode_program' => ':kode_program', 'kode_kegiatan' => ':kode_kegiatan']) }}"
                    .replace(':kode_satker', kode_satker)
                    .replace(':kode_program', kode_program)
                    .replace(':kode_kegiatan', kode_kegiatan),
                method: "GET",
                success: function (response) {
                    // Kosongkan select option kode_akun
                    $('#kode_output_semua').empty();
                    $('#kode_output_semua').append('<option value="" disabled selected>Pilih Output</option>');
                    $('#kode_output_semua').append('<option value="null">ALL - Semua Output</option>');

                    // Isi select option kode_akun dengan data dari response
                    response.forEach(function (output) {
                        $('#kode_output_semua').append(
                            `<option value="${output.kode_output}">${output.kode_output} - ${output.nama_output}</option>`
                        );
                    });
                },
                error: function (xhr) {
                    console.error("Gagal mengambil data Output:", xhr.responseText);
                }
            });
        });

        // Ketika kode_output_semua dipilih, ambil data akun berdasarkan kode_satker dan kode_program
        $('#kode_output_semua').on('change', function () {
          let selectedProgram =  $('#kode_induk_program_semua').val();
          let parts = selectedProgram.split('.'); // Misal: ["693206", "139", "03", "DK"]
          let kode_satker = parts[0]; // Ambil elemen pertama sebagai kode_satker
          let kode_program = parts.length > 2 ? parts.slice(1).join('.') : parts[1] || ''; // Gabungkan jika lebih dari 2 elemen, jika tidak ambil elemen kedua (jika ada)

          let kode_kegiatan =  $('#kode_kegiatan_semua').val();
          let kode_output = $(this).val();

            // Ambil data akun dari route /get-unique-akuns-json/{kode_satker}/{kode_program}
            $.ajax({
                url: "{{ route('unique-suboutputs-json', ['kode_satker' => ':kode_satker', 'kode_program' => ':kode_program', 'kode_kegiatan' => ':kode_kegiatan', 'kode_output' => ':kode_output']) }}"
                    .replace(':kode_satker', kode_satker)
                    .replace(':kode_program', kode_program)
                    .replace(':kode_kegiatan', kode_kegiatan)
                    .replace(':kode_output', kode_output),
                method: "GET",
                success: function (response) {
                    // Kosongkan select option kode_akun
                    $('#kode_sub_output_semua').empty();
                    $('#kode_sub_output_semua').append('<option value="" disabled selected>Pilih Sub Output</option>');
                    $('#kode_sub_output_semua').append('<option value="null">ALL - Semua Sub Output</option>');

                    // Isi select option kode_akun dengan data dari response
                    response.forEach(function (subOutput) {
                        $('#kode_sub_output_semua').append(
                            `<option value="${subOutput.kode_subOutput}">${subOutput.kode_subOutput} - ${subOutput.nama_subOutput}</option>`
                        );
                    });
                },
                error: function (xhr) {
                    console.error("Gagal mengambil data Sub Output:", xhr.responseText);
                }
            });
        });

        // Ketika kode_output_semua dipilih, ambil data akun berdasarkan kode_satker dan kode_program
        $('#kode_sub_output_semua').on('change', function () {
          let selectedProgram =  $('#kode_induk_program_semua').val();
          let parts = selectedProgram.split('.'); // Misal: ["693206", "139", "03", "DK"]
          let kode_satker = parts[0]; // Ambil elemen pertama sebagai kode_satker
          let kode_program = parts.slice(1).join('.'); // Gabungkan elemen setelah pertama sebagai kode_program
          
          let kode_kegiatan =  $('#kode_kegiatan_semua').val();
          let kode_output =  $('#kode_output_semua').val();
          let kode_sub_output = $(this).val();

            // Ambil data akun dari route /get-unique-akuns-json/{kode_satker}/{kode_program}
            $.ajax({
                url: "{{ route('unique-komponens-json', ['kode_satker' => ':kode_satker', 'kode_program' => ':kode_program', 'kode_kegiatan' => ':kode_kegiatan', 'kode_output' => ':kode_output', 'kode_sub_output' => ':kode_sub_output']) }}"
                    .replace(':kode_satker', kode_satker)
                    .replace(':kode_program', kode_program)
                    .replace(':kode_kegiatan', kode_kegiatan)
                    .replace(':kode_output', kode_output)
                    .replace(':kode_sub_output', kode_sub_output),
                method: "GET",
                success: function (response) {
                    // Kosongkan select option kode_akun
                    $('#kode_komponen_semua').empty();
                    $('#kode_komponen_semua').append('<option value="" disabled selected>Pilih Komponen</option>');
                    $('#kode_komponen_semua').append('<option value="null">ALL - Semua Komponen</option>');

                    // Isi select option kode_akun dengan data dari response
                    response.forEach(function (komponen) {
                        $('#kode_komponen_semua').append(
                            `<option value="${komponen.kode_komponen}">${komponen.kode_komponen} - ${komponen.nama_komponen}</option>`
                        );
                    });
                },
                error: function (xhr) {
                    console.error("Gagal mengambil data Komponen:", xhr.responseText);
                }
            });
        });

        // Ketika kode_komponen_semua dipilih, ambil data akun berdasarkan kode_satker dan kode_program
        $('#kode_komponen_semua').on('change', function () {
          let selectedProgram =  $('#kode_induk_program_semua').val();
          let parts = selectedProgram.split('.'); // Misal: ["693206", "139", "03", "DK"]
          let kode_satker = parts[0]; // Ambil elemen pertama sebagai kode_satker
          let kode_program = parts.slice(1).join('.'); // Gabungkan elemen setelah pertama sebagai kode_program
          
          let kode_kegiatan =  $('#kode_kegiatan_semua').val();
          let kode_output =  $('#kode_output_semua').val();
          let kode_sub_output = $('#kode_sub_output_semua').val();
          let kode_komponen = $(this).val();

            // Ambil data akun dari route /get-unique-akuns-json/{kode_satker}/{kode_program}
            $.ajax({
                url: "{{ route('unique-subkomponens-json', ['kode_satker' => ':kode_satker', 'kode_program' => ':kode_program', 'kode_kegiatan' => ':kode_kegiatan', 'kode_output' => ':kode_output', 'kode_sub_output' => ':kode_sub_output', 'kode_komponen' => ':kode_komponen']) }}"
                    .replace(':kode_satker', kode_satker)
                    .replace(':kode_program', kode_program)
                    .replace(':kode_kegiatan', kode_kegiatan)
                    .replace(':kode_output', kode_output)
                    .replace(':kode_sub_output', kode_sub_output)
                    .replace(':kode_komponen', kode_komponen),
                method: "GET",
                success: function (response) {
                    // Kosongkan select option kode_akun
                    $('#kode_sub_komponen_semua').empty();
                    $('#kode_sub_komponen_semua').append('<option value="" disabled selected>Pilih Sub Komponen</option>');
                    $('#kode_sub_komponen_semua').append('<option value="null">ALL - Semua Sub Komponen</option>');

                    // Isi select option kode_akun dengan data dari response
                    response.forEach(function (subKomponen) {
                        $('#kode_sub_komponen_semua').append(
                            `<option value="${subKomponen.kode_SubKomponen}">${subKomponen.kode_SubKomponen} - ${subKomponen.nama_SubKomponen}</option>`
                        );
                    });
                },
                error: function (xhr) {
                    console.error("Gagal mengambil data Sub Komponen:", xhr.responseText);
                }
            });
        });

        // Ketika kode_output_semua dipilih, ambil data akun berdasarkan kode_satker dan kode_program
        $('#kode_sub_komponen_semua').on('change', function () {
          let selectedProgram =  $('#kode_induk_program_semua').val();
          let parts = selectedProgram.split('.'); // Misal: ["693206", "139", "03", "DK"]
          let kode_satker = parts[0]; // Ambil elemen pertama sebagai kode_satker
          let kode_program = parts.slice(1).join('.'); // Gabungkan elemen setelah pertama sebagai kode_program
          
          let kode_kegiatan =  $('#kode_kegiatan_semua').val();
          let kode_output =  $('#kode_output_semua').val();
          let kode_sub_output = $('#kode_sub_output_semua').val();
          let kode_komponen = $('#kode_komponen_semua').val();
          let kode_sub_komponen = $(this).val();

            // Ambil data akun dari route /get-unique-akuns-json/{kode_satker}/{kode_program}
            $.ajax({
                url: "{{ route('unique-akuns-semua-json', ['kode_satker' => ':kode_satker', 'kode_program' => ':kode_program', 'kode_kegiatan' => ':kode_kegiatan', 'kode_output' => ':kode_output', 'kode_sub_output' => ':kode_sub_output', 'kode_komponen' => ':kode_komponen', 'kode_sub_komponen' => ':kode_sub_komponen']) }}"
                    .replace(':kode_satker', kode_satker)
                    .replace(':kode_program', kode_program)
                    .replace(':kode_kegiatan', kode_kegiatan)
                    .replace(':kode_output', kode_output)
                    .replace(':kode_sub_output', kode_sub_output)
                    .replace(':kode_komponen', kode_komponen)
                    .replace(':kode_sub_komponen', kode_sub_komponen),
                method: "GET",
                success: function (response) {
                    // Kosongkan select option kode_akun
                    $('#kode_akun_semua').empty();
                    $('#kode_akun_semua').append('<option value="" disabled selected>Pilih Akun</option>');
                    $('#kode_akun_semua').append('<option value="null">ALL - Semua Akun</option>');

                    // Isi select option kode_akun dengan data dari response
                    response.forEach(function (akun) {
                        $('#kode_akun_semua').append(
                            `<option value="${akun.kode_akun}">${akun.kode_akun} - ${akun.uraian}</option>`
                        );
                    });
                },
                error: function (xhr) {
                    console.error("Gagal mengambil data Akun:", xhr.responseText);
                }
            });
        });

        // Ketika kode_output_semua dipilih, ambil data akun berdasarkan kode_satker dan kode_program
        $('#kode_akun_semua').on('change', function () {
          let selectedProgram =  $('#kode_induk_program_semua').val();
          let parts = selectedProgram.split('.'); // Misal: ["693206", "139", "03", "DK"]
          let kode_satker = parts[0]; // Ambil elemen pertama sebagai kode_satker
          let kode_program = parts.slice(1).join('.'); // Gabungkan elemen setelah pertama sebagai kode_program
          
          let kode_kegiatan =  $('#kode_kegiatan_semua').val();
          let kode_output =  $('#kode_output_semua').val();
          let kode_sub_output = $('#kode_sub_output_semua').val();
          let kode_komponen = $('#kode_komponen_semua').val();
          let kode_sub_komponen = $('#kode_sub_komponen_semua').val();
          let kode_akun = $(this).val();

            $('#item_semua').empty();
            $('#item_semua').append('<option value="" disabled selected>Pilih Sertakan Item</option>');
            $('#item_semua').append('<option value="1">YA</option>');
            $('#item_semua').append('<option value="0">TIDAK</option>');
        });
    });
</script>

  <!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.5/lottie.min.js"></script>
<script>
  var animation = lottie.loadAnimation({
    container: document.getElementById('lottieAnimation'),
    renderer: 'svg',
    loop: true,
    autoplay: true,
    path: 'https://lottie.host/9429b6d4-edcb-423f-abed-14058bb80fa5/2xXdBIr7Ba.json' // Replace with your chosen Lottie animation URL
  });

  document.getElementById('generatePenggunaan').addEventListener('click', function() {
    var loadingIndicator = document.getElementById('loadingIndicator');
    var progressBar = document.getElementById('progressBar');
    loadingIndicator.style.display = 'block';

    var progress = 0;
    var interval = setInterval(function() {
      progress += 17;
      progressBar.style.width = progress + '%';
      if (progress >= 100) {
        clearInterval(interval);
      }
    }, 1000);

    setTimeout(function() {
      window.location.href = '/generate-penggunaan';
    }, 7000); // Minimum display time of 10 seconds
  });
</script>

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

                // Total semua halaman
                let total4 = api
                    .column(4)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                let total5 = api
                    .column(5)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                let total6 = api
                    .column(6)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                // Total halaman saat ini (page)
                let pageTotal4 = api
                    .column(4, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                let pageTotal5 = api
                    .column(5, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                let pageTotal6 = api
                    .column(6, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                // Tampilkan hasilnya di footer
                api.column(4).footer().innerHTML =
                    'Rp ' + pageTotal4.toLocaleString('id-ID');
                api.column(5).footer().innerHTML =
                    'Rp ' + pageTotal5.toLocaleString('id-ID');
                api.column(6).footer().innerHTML =
                    'Rp ' + pageTotal6.toLocaleString('id-ID');
            }
        });

    });
</script>

<script>
document.getElementById('downloadexcel').addEventListener('click', function() {
    // Format tanggal saat ini
    var now = new Date();
    var formattedDate = now.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' }).replace(/\//g, "");
    var formattedTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace(":", "-");

    // Nama file dengan format Monitoring_Keuangan_03052025_21-50.xlsx
    var fileName = `Monitoring_Keuangan_${formattedDate}_${formattedTime}.xlsx`;

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
        row[3],  
        formatRupiah(parseFloat(row[4].replace("Rp ", "").replace(/\./g, ""))), 
        formatRupiah(parseFloat(row[5].replace("Rp ", "").replace(/\./g, ""))), 
        formatRupiah(parseFloat(row[6].replace("Rp ", "").replace(/\./g, "")))
    ]);

    // Hitung total keseluruhan
    var totalAnggaran = allData.reduce((sum, row) => sum + parseFloat(row[4].replace("Rp ", "").replace(/\./g, "")), 0);
    var totalPenggunaan = allData.reduce((sum, row) => sum + parseFloat(row[5].replace("Rp ", "").replace(/\./g, "")), 0);
    var totalSisa = allData.reduce((sum, row) => sum + parseFloat(row[6].replace("Rp ", "").replace(/\./g, "")), 0);

    // Tambahkan baris footer
    var footer = ["Total Keseluruhan", "", "", "", formatRupiah(totalAnggaran), formatRupiah(totalPenggunaan), formatRupiah(totalSisa)];

    // Buat workbook dan worksheet untuk Excel
    var wb = XLSX.utils.book_new();
    var ws = XLSX.utils.aoa_to_sheet([header, ...data, footer]); // Tambahkan footer

    // Tambahkan worksheet ke workbook
    XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

    // Simpan file Excel
    XLSX.writeFile(wb, fileName);
});
</script>
@endsection
