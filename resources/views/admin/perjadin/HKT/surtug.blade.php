@extends('admin.templates.sidebar')

    @php
        use Carbon\Carbon;

        // Konversi tanggal ke dalam objek Carbon untuk perhitungan
        $tglMulai = Carbon::parse($perjadin->tgl_keberangkatan)->startOfDay();
        $tglSelesai = Carbon::parse($perjadin->tgl_selesai)->endOfDay();

        // Hitung selisih hari
        $jumlahHari = $tglMulai->diffInDays($tglSelesai) + 1;
    @endphp


@section('contain')
<div class="container-fluid px-4 py-4">
  <div class="row">
    <div class="col-md-12">
      <h4>Perjalanan Dinas / <span class="fw-bold">HKT Perjalanan Dinas</span></h4>
    </div>
  </div>
</div>

<div class="row">
  <form action="{{ url('/cu_perjadin_HKT') }}" method="POST">
    @csrf
    <input type="hidden" name="idPerjadin" value="{{$perjadin->id}}">
    <div class="row">
      <div class="col-md-12 mb-3">
        <div class="card">
          <div class="card-body content">
            <div class="row page_content card-style">
                <div class="col-md-6">
                    <label for="tipeSurtug" class="form-label">Tipe Surtug<span class="text-danger">*</span></label>
                    <select class="form-select" id="ket_mobilitas" name="tipeSurtug" style="width: auto; max-width: 150px; min-width: 150px;">
                        <option value="false">Standar</option>
                        <option value="true">Tabel</option>
                    </select>
                </div>

              <div class="col-md-6">
                <div class="page_content card-style">
                  <label for="perihal" class="form-label">Perihal<span class="text-danger">*</span></label>
                  <div class="input-group">
                        <span class="input-group-text">Masukkan Perihal Surat Tugas</span>
                        <div id="editorPerihal" style="height: 80px;">
                            <!-- Isi default dari paragraf 2 -->
                            @if ($perjadin->no_undangan != NULL)
                            Sehubungan dengan surat dari {!! $perjadin->pemberi_undangan !!} Nomor {!! $perjadin->no_undangan !!} Tanggal {!! \Carbon\Carbon::parse($perjadin->tanggal_surat)->translatedFormat('d F Y') !!}.  Kepala Lembaga Layanan Pendidikan Tinggi Wilayah IV dengan ini
    memberikan tugas kepada:
                            @else
                            Kepala Lembaga Layanan Pendidikan Tinggi Wilayah IV dengan ini memberikan tugas kepada:
                            @endif
                        </div>
                        <input type="hidden" name="perihal" id="perihal">
                    </div>

                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12 mb-3">
          <div class="card">
            <div class="card-body content">
              <div class="row page_content card-style">
                <div class="col-md-6">
                  <div class="page_content card-style">
                    <label for="paragraf1" class="form-label">Paragraf 1 <span class="text-danger">*</span></label>
                    <textarea class="form-control mt-1" id="paragraf1Place" placeholder="Adalah keterangan rinci melaksanakan tugas, defaultnya diambil dari kalimat sebelumnya dan dari tanggal pelaksanaan tugas, silahkan lengkapi jika mau atau anda hanya mengganti tanda * dengan tempat pelaksanaan tugas" readonly></textarea>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="page_content card-style">
                    <label for="paragraf1input" class="form-label">Paragraf 1 <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Masukan Paragraf 1</span>
                        <div id="editorP1" style="height: 80px;">
                            <!-- Isi default dari paragraf 2 -->
                            @if ($jumlahHari > 1)
                            untuk melaksanakan tugas {!! $perjadin->nama_kegiatan !!} pada hari {!! \Carbon\Carbon::parse($perjadin->tgl_mulai)->translatedFormat('l') !!}, tanggal {!! \Carbon\Carbon::parse($perjadin->tgl_keberangkatan)->translatedFormat('d F Y') !!} s.d hari {!! \Carbon\Carbon::parse($perjadin->tgl_selesai)->translatedFormat('l') !!}, tanggal {!! \Carbon\Carbon::parse($perjadin->tgl_selesai)->translatedFormat('d F Y') !!} bertempat di {!! $perjadin->alamat !!}, perjalanan dengan menggunakan {!! $perjadin->mobilitas !!}. Segala biaya yang berhubungan dengan pelaksanaan tugas ini dibebankan kepada DIPA LLDIKTI Wilayah IV tahun {{ \App\Models\Versi::find(session('versi'))->versi ?? '' }}
                            @else
                            untuk melaksanakan tugas {!! $perjadin->nama_kegiatan !!} pada hari {!! \Carbon\Carbon::parse($perjadin->tgl_mulai)->translatedFormat('l') !!}, tanggal {!! \Carbon\Carbon::parse($perjadin->tgl_keberangkatan)->translatedFormat('d F Y') !!} bertempat di {!! $perjadin->alamat !!}, perjalanan dengan menggunakan {!! $perjadin->mobilitas !!}. Segala biaya yang berhubungan dengan pelaksanaan tugas ini dibebankan kepada DIPA LLDIKTI Wilayah IV tahun {{ \App\Models\Versi::find(session('versi'))->versi ?? '' }}
                            @endif
                        </div>
                        <input type="hidden" name="paragraf1" id="paragraf1">
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 mb-3">
              <div class="card">
                <div class="card-body content">
                  <div class="row page_content card-style">
                    <div class="col-md-6">
                      <div class="page_content card-style">
                        <label for="paragraf2place" class="form-label">Paragraf 2 <span class="text-danger">*</span></label>
                        <textarea class="form-control mt-1" id="paragraf2place" placeholder="Adalah keterangan penggunaan DIPA, silahkan ganti jika tidak sesuai" readonly></textarea>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="page_content card-style">
                        <label for="paragraf2" class="form-label">Paragraf 2 <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Masukan Paragraf 2</span>
                            <div id="editorP2" style="height: 80px;">
                                <!-- Isi default dari paragraf 2 -->
                                Dalam upaya mewujudkan Zona Integritas di LLDIKTI Wilayah IV, pelaksana tugas tidak diperkenankan untuk menerima gratifikasi dalam bentuk apapun.
                            </div>
                            <input type="hidden" name="paragraf2" id="paragraf2">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12 mb-3">
                    <div class="card">
                      <div class="card-body content">
                        <div class="row page_content card-style">
                          <div class="col-md-6">
                            <div class="page_content card-style">
                              <label for="paragraf3" class="form-label">Paragraf 3 <span class="text-danger">*</span></label>
                              <textarea class="form-control mt-1" id="paragraf3place" placeholder="Adalah Kalimat penutup pada surat tugas, silahkan ganti apabila tidak sesuai." readonly></textarea>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="page_content card-style">
                              <label for="paragraf3place" class="form-label">Paragraf 3 <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Masukan Paragraf 3</span>
                                    <div id="editorP3" style="height: 60px;">
                                        <!-- Isi default dari paragraf 3 -->
                                        Surat tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab, dan diwajibkan menyampaikan laporan secara tertulis paling lambat 3 hari setelah selesai melaksanakan tugas
                                    </div>
                                    <input type="hidden" name="paragraf3" id="paragraf3">
                                </div>

                            </div>
                          </div>
                        </div>
                        <div class="col-md-12 mb-3 mt-5">
                          <div class="d-grid gap-2 d-md-flex justify-content-center">
                            <a href="{{url('/perjadin-HKT/' . 'pengajuan')}}" class="btn btn-dark">Kembali</a>
                            <button type=submit class="btn btn-next btn-primary">Simpan</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </form>
  <div>

  </div>


  <!-- Tambahkan di bagian <head> -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <!-- Tambahkan di bagian <body> sebelum tag </body> -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        // Inisialisasi Quill editor
        var quillPerihal = new Quill('#editorPerihal', {
            theme: 'snow'
        });
        var quillP1 = new Quill('#editorP1', {
            theme: 'snow'
        });
        var quillP2 = new Quill('#editorP2', {
            theme: 'snow'
        });
        var quillP3 = new Quill('#editorP3', {
            theme: 'snow'
        });

        function removeParagraphTags(content) {
            // Menggunakan regex untuk menghapus semua <p> dan </p>
            return content.replace(/<p>/g, '').replace(/<\/p>/g, '').trim();
        }

        // Fungsi untuk mengisi input hidden dengan konten dari Quill
        function updateHiddenInputs() {
            document.querySelector('#perihal').value = removeParagraphTags(quillPerihal.root.innerHTML);
            document.querySelector('#paragraf1').value = removeParagraphTags(quillP1.root.innerHTML);
            document.querySelector('#paragraf2').value = removeParagraphTags(quillP2.root.innerHTML);
            document.querySelector('#paragraf3').value = removeParagraphTags(quillP3.root.innerHTML);
        }

        // Panggil fungsi updateHiddenInputs saat halaman dibuka
        updateHiddenInputs();

        // Selalu perbarui nilai input hidden setiap kali konten Quill berubah
        quillPerihal.on('text-change', function() {
            updateHiddenInputs();
        });
        quillP1.on('text-change', function() {
            updateHiddenInputs();
        });
        quillP2.on('text-change', function() {
            updateHiddenInputs();
        });
        quillP3.on('text-change', function() {
            updateHiddenInputs();
        });
    </script>




  <script>
    function setDynamicTextareaHeight(textareaId) {
      var textarea = document.getElementById(textareaId);
      textarea.style.height = 'auto';
      textarea.style.height = textarea.scrollHeight + 'px';
    }

    function initializeTextarea(textareaId) {
      var textarea = document.getElementById(textareaId);
      setDynamicTextareaHeight(textareaId);
      textarea.addEventListener('input', function() {
        setDynamicTextareaHeight(textareaId);
      });
    }

    initializeTextarea('paragraf1');
    initializeTextarea('paragraf1input');
    initializeTextarea('paragraf2input');
    initializeTextarea('paragraf3input');
  </script>

  @endsection
