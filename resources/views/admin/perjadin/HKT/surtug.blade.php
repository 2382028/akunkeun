@extends('admin.templates.sidebar')

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
              </div>
              <div class="col-md-6">
                <div class="page_content card-style">
                  <label for="paragraf2" class="form-label">Perihal<span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text">Masukkan Perihal Surat Tugas</span>
                    <textarea class="form-control" aria-label="With textarea" name="perihal" id="perihal " placeholder=""></textarea>
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
                    <textarea class="form-control mt-1" id="paragraf1" placeholder="Adalah keterangan rinci melaksanakan tugas, defaultnya diambil dari kalimat sebelumnya dan dari tanggal pelaksanaan tugas, silahkan lengkapi jika mau atau anda hanya mengganti tanda * dengan tempat pelaksanaan tugas" readonly></textarea>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="page_content card-style">
                    <label for="paragraf1input" class="form-label">Paragraf 1 <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <span class="input-group-text">Masukan Paragraf 1</span>
                      <textarea class="form-control" aria-label="With textarea" name="paragraf1" id="paragraf1" placeholder="">Untuk melaksanakan tugas {!! $perjadin->nama_kegiatan !!} pada hari {!! \Carbon\Carbon::parse($perjadin->tgl_mulai)->translatedFormat('l') !!}, tanggal {!! \Carbon\Carbon::parse($perjadin->tgl_mulai)->translatedFormat('d F Y') !!} sampai dengan {!! \Carbon\Carbon::parse($perjadin->tgl_selesai)->translatedFormat('l') !!}, tanggal {!! \Carbon\Carbon::parse($perjadin->tgl_selesai)->translatedFormat('d F Y') !!} bertempat di {!! $perjadin->alamat !!}, perjalanan dengan menggunakan {!! $perjadin->mobilitas !!}. Segala biaya yang berhubungan dengan pelaksanaan tugas ini dibebankan kepada DIPA LLDIKTI Wilayah IV tahun 2024
                      </textarea>

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
                        <label for="paragraf3" class="form-label">Paragraf 2 <span class="text-danger">*</span></label>
                        <textarea class="form-control mt-1" id="paragraf2" placeholder="Adalah keterangan penggunaan DIPA, silahkan ganti jika tidak sesuai" readonly></textarea>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="page_content card-style">
                        <label for="paragraf2" class="form-label">Paragraf 2 <span class="text-danger">*</span></label>
                        <div class="input-group">
                          <span class="input-group-text">Masukan Paragraf 2</span>
                          <textarea class="form-control" aria-label="With textarea" name="paragraf2" id="paragraf2" placeholder="">Dalam upaya mewujudkan Zona Integritas di LLDIKTI Wilayah IV, pelaksana tugas tidak diperkenankan untuk menerima gratifikasi dalam bentuk apapun.</textarea>
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
                              <textarea class="form-control mt-1" id="paragraf3" placeholder="Adalah Kalimat penutup pada surat tugas, silahkan ganti apabila tidak sesuai." readonly></textarea>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="page_content card-style">
                              <label for="paragraf3" class="form-label">Paragraf 3 <span class="text-danger">*</span></label>
                              <div class="input-group">
                                <span class="input-group-text">Masukan Paragraf 3</span>
                                <textarea class="form-control" aria-label="With textarea" name="paragraf3" id="paragraf3" placeholder="">Surat tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab, dan diwajibkan menyampaikan laporan secara tertulis paling lambat 3 hari setelah selesai melaksanakan tugas</textarea>
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