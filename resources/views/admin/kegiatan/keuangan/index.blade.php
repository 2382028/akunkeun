@extends('admin.templates.sidebar')

@section('contain')
<!-- Awal Dashboard - Kegiatan - Keuangan -->
    <div class="container-fluid px-3 py-3">
      <div class="row">
        <div class="col-md-12">
          <h4>Perjadin Kegiatan / <span class="fw-bold">Keuangan</span></h4>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 mb-2">
          <div class="card border-0 bg-secondary">
            <div class="page wrapper">
                <a href="{{url('/kegiatan-keuangan/' . 'verifikasi-2')}}" class="page-wrap btn btn-sm btn-warning text-white">Verifikator | Pelaporan</a>
                <a href="{{url('/kegiatan-keuangan/' . 'revisi-2')}}" class="page-wrap btn btn-sm btn-info text-white">Revisi</a>
                <a href="{{url('/kegiatan-keuangan/' . 'ditolak')}}" class="page-wrap btn btn-sm btn-danger">Ditolak</a>
                <a href="{{url('/kegiatan-keuangan/' . 'selesai')}}" class="page-wrap btn btn-sm btn-success">Selesai</a>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 mb-3">
          <div class="card">
            <div class="card-body content">

              <!-- Kegiatan - Keuangan - Pengajuan -->
              <div class="row page_content page_1">
                <div class="table-responsive">
                  <div class="col-md-12 mb-3 text-end">
                    <button id="downloadexcel" class="btn btn-success btn-sm "><i class="fa-solid fa-file-excel"></i> Export to Excel</button>
                  </div>
                  <table id="example" class="table table-bordered data-table" style="width: 100%">
                    <thead>
                      <tr class="text-center small">
                        <th class="th-sm">No</th>
                        <th class="th-sm">ID Kegiatan</th>
                        <th class="th-lg">Nama Kegiatan/Program</th>
                        <th class="th-md">Tanggal Mulai</th>
                        <th class="th-md">Status</th>
                        <th class="th-lg-percent">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($kegiatans as $kegiatan)
                      <tr>
                        <td class='text-center'>{{$loop->iteration}}</td>
                        <td class='text-center'>{{$kegiatan->id}}</td>
                        <td>{{$kegiatan->nama_kegiatan}}</td>
                        <td class='text-center'>{{\Carbon\Carbon::parse($kegiatan->tgl_mulai)->format('d-m-Y H:i')}}</td>
                        <td class='text-center'>{{$kegiatan->status}} | {{$kegiatan->is_acceptKeu}}</td>
                        <td class='text-center'>
                        @if ($kegiatan->is_acceptKeu == 'selesai')
                            <span class="page d-flex justify-content-center align-items-center">
                                <a href="{{url('/detail-keuangan/' . $kegiatan->id)}}" class="me-2 btn btn-primary d-flex"><i class="fa-solid fa-eye pt-1"></i> <p class="ps-1  m-0">Detail</p></a>

                                @if ($kegiatan->is_acceptBend == 'selesai')
                                        <button class="me-2 btn btn-dark d-flex align-items-center" type="button" data-bs-toggle="modal" data-bs-target="#sppd_modal"
                                        data-id-kegiatan="{{ $kegiatan->id }}"
                                        kegiatan-data="{{ json_encode($kegiatan) }}"
                                        ><i class="fa-solid fa-print "></i> SPPD</button>
                                        <a href="{{url('/kegiatan-bendahara/rpd/' . $kegiatan->id)}}" target="_blank" class="btn btn-warning d-flex"><i class="fa-solid fa-print pt-1"></i> <p class="ps-1  m-0"> RPD</p></a>

                                @else
                                    <!-- <a href="{{url('/kegiatan-bendahara/rpd/' . $kegiatan->id)}}" target="_blank" class="btn btn-warning d-flex"><i class="fa-solid fa-print pt-1"></i> <p class="ps-1  m-0"> RPD</p></a> -->
                                @endif
                            </span>
                        @elseif ($kegiatan->is_acceptKeu == 'ditolak' || $kegiatan->is_acceptKeu == 'revisi-2')
                            <span class="page d-flex justify-content-center align-items-center">
                                <a href="{{url('/detail-keuangan/' . $kegiatan->id)}}" class="me-2 btn btn-primary d-flex"><i class="fa-solid fa-eye pt-1"></i> <p class="ps-1  m-0">Detail</p></a>
                            </span>
                        @else
                            @if ($kegiatan->status == 'selesai')
                                <span class="page d-flex justify-content-center align-items-center">
                                    <a href="{{url('/detail-keuangan/' . $kegiatan->id)}}" class="btn btn-primary d-flex"><i class="fa-solid fa-check pt-1"></i> <p class="ps-1  m-0">Verifikasi</p></a>
                                </span>
                            @else
                                <span class="page d-flex justify-content-center align-items-center">
                                    <a href="{{url('/detail-keuangan/' . $kegiatan->id)}}" class="btn btn-dark d-flex"><i class="fa-solid fa-eye pt-1"></i> <p class="ps-1  m-0">Detail</p></a>
                                </span>
                            @endif
                        @endif
                        </td>
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
  <!-- Akhir Dashboard - Kegiatan - Keuangan -->


  <!-- Modal SPPD -->
<div class="modal fade" id="sppd_modal" tabindex="-1" aria-labelledby="sppdModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
        <h1 class="modal-title fs-5" id="sppdModalLabel" name="sppdModalLabel">Cetak SPPD - </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form action="{{url('/update_data_sppd_kegiatan')}}" method="post">
            @csrf
            <div class="mb-3">
            <label for="nPenandatangan" class="form-label" style="font-weight: bold;">Jumlah Penandatangan<span class="text-danger">*</span></label>
            <select class="form-select" id="nPenandatangan" name="nPenandatangan" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
            </div>
            <input type="hidden" class="form-control num1 prevent-submit" id="kegiatan_id" name="kegiatan_id" value="">
            <input type="hidden" class="form-control num1 prevent-submit" id="kegiatanData" name="kegiatanData" value="">

            <div class="row mb-3 align-items-center">
                <div class="col">
                    <label class="form-label"><strong>Tempat Tujuan Baris-I</strong></label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="tempatTujuan_penandatangan_0" id="tempatTujuan_penandatangan_0">
                        <span class="input-group-text">
                            <input class="form-check-input mt-0 me-1" type="checkbox" id="gunakanKabKota">
                            <label for="gunakanKabKota" class="mb-0 ms-1" style="font-size: 0.85rem;">Gunakan kab/kota</label>
                        </span>
                    </div>
                </div>
            </div>



                <div id="penandatangan-container">
            <!-- Dynamic Penandatangan Fields -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Cetak SPPD</button>
        </div>
    </form>
    </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
      const nPenandatangan = document.getElementById('nPenandatangan');
      const container = document.getElementById('penandatangan-container');

      // Ambil data penandatangan dari elemen tombol yang memicu modal

      function toRomawi(angka) {
          const romawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
          return romawi[angka - 1] || angka; // Mengembalikan angka jika tidak ada dalam array
      }

      function renderFields(count) {
          const perjadinData = JSON.parse(document.getElementById('kegiatanData').value);
          container.innerHTML = ''; // Reset fields

          // Ambil nilai tempatTujuan_penandatangan_0, fallback ke kab_kota
          const inputTempatTujuan0 = document.querySelector('input[name="tempatTujuan_penandatangan_0"]');
          const checkboxGunakanKabKota = document.getElementById('gunakanKabKota');

          // Ambil nilai awal dari perjadinData
          let tempatTujuan0 = perjadinData['tempatTujuan_penandatangan0'];
          const kabKota = perjadinData['kab_kota'] || '';

          // Jika tidak ada nilai atau kosong, fallback ke kab_kota
          if (!tempatTujuan0 || tempatTujuan0.trim() === '') {
              tempatTujuan0 = kabKota;
          }

          // Cek apakah checkbox diceklis
          if (inputTempatTujuan0 && checkboxGunakanKabKota) {
              if (!checkboxGunakanKabKota.checked) {
                  // Jika belum diceklis, baru isi default dari perjadinData
                  inputTempatTujuan0.value = tempatTujuan0;
              } else {
                  // Kalau diceklis, pastikan tetap pakai kab_kota
                  inputTempatTujuan0.value = kabKota;
              }

              // Event saat ceklis diubah
              checkboxGunakanKabKota.onchange = () => {
                  if (checkboxGunakanKabKota.checked) {
                      inputTempatTujuan0.value = kabKota;
                  } else {
                      inputTempatTujuan0.value = tempatTujuan0;
                  }
              };
          }


          for (let i = 1; i <= count; i++) {

        /// Menentukan nama, jabatan, dan nip berdasarkan kondisi i
        const namaValue = perjadinData[i === 1 ? 'nama_penandatangan' : `nama_penandatangan${i}`] || '';
        const jabatanValue = perjadinData[i === 1 ? 'jabatan_penandatangan' : `jabatan_penandatangan${i}`] || '';
        const nipValue = perjadinData[i === 1 ? 'nip_penandatangan' : `nip_penandatangan${i}`] || '';
        const tempatTibaValue = perjadinData[i === 1 ? 'tempatTiba_penandatangan' : `tempatTiba_penandatangan${i}`] || '';
        const tempatTujuanValue = perjadinData[i === 1 ? 'tempatTujuan_penandatangan' : `tempatTujuan_penandatangan${i}`] || '';
        const tanggalTibaValue = perjadinData[i === 1 ? 'tanggal_penandatangan' : `tanggal_penandatangan${i}`] || null;
        const tanggalTujuanValue = perjadinData[i === 1 ? 'tanggalTujuan_penandatangan' : `tanggalTujuan_penandatangan${i}`] || null;


        // Menambahkan HTML dinamis ke dalam container
        container.innerHTML += `
          <div class="row g-3 mb-3 align-items-center">
              <div class="col-md-4">
                  <label class="form-label">Nama Penandatangan Baris-${toRomawi(i+1)}<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nama_penandatangan_${i}" value="${namaValue}" required>
              </div>
              <div class="col-md-4">
                  <label class="form-label">Jabatan Baris-${toRomawi(i+1)}<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="jabatan_penandatangan_${i}" value="${jabatanValue}" required>
              </div>
              <div class="col-md-4">
                  <label class="form-label">NIP Baris-${toRomawi(i+1)}<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nip_penandatangan_${i}" value="${nipValue}" required>
              </div>

              <!-- Tempat Tiba dan Tanggal Tiba jadi satu baris -->
              <div class="col-md-6">
                  <label class="form-label">Tempat Tiba Baris-${toRomawi(i+1)}<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="tempatTiba_penandatangan_${i}" value="${tempatTibaValue}" required>
              </div>
              <div class="col-md-6">
                  <label class="form-label">Tanggal Tiba Baris-${toRomawi(i+1)}<span class="text-danger"></span></label>
                  <input type="date" class="form-control" name="tanggalTiba_penandatangan_${i}" value="${tanggalTibaValue}">
              </div>

              <!-- Tempat Tujuan dan Tanggal Berangkat jadi satu baris -->
              <div class="col-md-6">
                  <label class="form-label">Tempat Tujuan Baris-${toRomawi(i+1)}<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="tempatTujuan_penandatangan_${i}" value="${tempatTujuanValue}" required>
              </div>
              <div class="col-md-6">
                  <label class="form-label">Tanggal Berangkat Baris-${toRomawi(i+1)}<span class="text-danger"></span></label>
                  <input type="date" class="form-control" name="tanggalBerangkat_penandatangan_${i}" value="${tanggalTujuanValue}">
              </div>
          </div>`;

        }

      }

      nPenandatangan.addEventListener('change', function () {
        renderFields(this.value);
      });

       // Menambahkan event listener untuk membuka modal
       const modalTriggers = document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target="#sppd_modal"]');  // Seleksi tombol dengan data-bs-toggle untuk modal tertentu

        modalTriggers.forEach(button => {
            button.addEventListener('click', function () {
                // Reset form saat modal dibuka
                container.innerHTML = ''; // Reset container sebelum mengisi kembali
                renderFields(nPenandatangan.value); // Render fields sesuai jumlah yang dipilih
            });
        });

      // Initialize with default value
      renderFields(nPenandatangan.value);
    });
  </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
      var sppdModal = document.getElementById('sppd_modal');
      sppdModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget; // Button that triggered the modal
        var idPerjadin = button.getAttribute('data-id-kegiatan'); // Extract info from data-* attributes
        var perjadinData = button.getAttribute('kegiatan-data'); // Extract info from data-* attributes
        var modal = this;
        modal.querySelector('#kegiatan_id').value = idPerjadin;
        modal.querySelector('#kegiatanData').value = perjadinData;

        // Mengubah judul modal menjadi "Cetak SPPD - id"
      var modalTitle = modal.querySelector('#sppdModalLabel');
      modalTitle.textContent = 'Cetak SPPD - ' + idPerjadin;
      });
    });
</script>

  <script>
    document.getElementById('downloadexcel').addEventListener('click', function() {
          var table2excel = new Table2Excel();
          table2excel.export(document.querySelectorAll("#example"), "Daftar Antrian Perjadin Kegiatan - Keuangan");
      });
  </script>
@endsection
