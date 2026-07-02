@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <h4>Data Surat Pemeliharaan</h4>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12 mb-3">
      <div class="card">
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-4">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambah_data_surat">
                <i class="fa fa-plus"></i> Tambah Data Surat
              </button>
            </div>
          </div>
          <div class="row mb-3 page_content page_1">

            <div class="table-responsive">
              <table id="data-table-surat" class="table table-bordered data-table" style="width:100%">
                <thead>
                  <tr class="text-center small">
                    <th>No.</th>
                    <th>Nomor Surat</th>
                    <th>Tipe</th>
                    <th>Perihal</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($datas as $index => $data)
                  @php
                  $parts = explode('-', $data->perihal, 2);
                  $tipe = $parts[0] ?? '';
                  $perihal = $parts[1] ?? '';
                  @endphp
                  <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $data->nomor_surat }}</td>
                    <td class="text-center">{{ $tipe }}</td>
                    <td>{{ $perihal }}</td>
                    <td class="text-center">
                      {{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y') }}
                    </td>

                    <td class="text-center">
                      @if($data->url_surat)
                      <a href="{{ url('/getDokumen/' . basename($data->url_surat)) }}" target="_blank" class="btn btn-info btn-sm">
                        <i class="fa fa-file"></i> Lihat File
                      </a>
                      @endif
                      <button type="button" class="btn btn-warning btn-sm edit-btn"
                        data-id="{{ $data->nomor_surat }}"
                        data-nomor="{{ $data->nomor_surat }}"
                        data-tanggal="{{ \Carbon\Carbon::parse($data->created_at)->format('Y-m-d') }}"
                        data-tipe="{{ $tipe }}"
                        data-perihal="{{ $perihal }}"
                        data-url-surat="{{ $data->url_surat }}"
                        data-bs-toggle="modal" data-bs-target="#edit_data_surat">
                        <i class="fa fa-pen"></i>
                      </button>

                      <form action="{{ route('nomor_surat.destroy', urlencode($data->nomor_surat)) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus surat ini?')">
                          <i class="fa-solid fa-trash"></i>
                        </button>
                      </form>
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

<!-- Modal Tambah Data Surat -->
<div class="modal fade" id="tambah_data_surat" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('nomor_surat.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Data Surat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nomor Surat <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="nomor_surat" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
            <input type="date" class="form-control" name="tanggal" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Tipe <span class="text-danger">*</span></label>
            <select class="form-control" name="tipe" required>
              <option value="PESANAN">PESANAN</option>
              <option value="BAST">BAST</option>
              <option value="BAP">BAP</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Perihal <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="perihal" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Upload File</label>
            <input type="file" class="form-control" name="url_surat" accept=".pdf,.doc,.docx">
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Data Surat -->
<div class="modal fade" id="edit_data_surat" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formEditSurat" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Data Surat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="idSurat_edit" id="idSurat_edit">
          <div class="mb-3">
            <label class="form-label">Nomor Surat <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="nomor_surat_edit" id="nomor_surat_edit" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
            <input type="date" class="form-control" name="tanggal_edit" id="tanggal_edit" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Tipe <span class="text-danger">*</span></label>
            <select class="form-control" name="tipe_edit" id="tipe_edit" required>
              <option value="PESANAN">PESANAN</option>
              <option value="BAST">BAST</option>
              <option value="BAP">BAP</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Perihal <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="perihal_edit" id="perihal_edit" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Upload File (opsional)</label>
            <input type="file" class="form-control" name="url_surat_edit" accept=".pdf,.doc,.docx">
            <input type="hidden" name="old_url_surat" id="old_url_surat">
            <small id="currentFile" class="text-muted"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  $(document).on('click', '.edit-btn', function() {
    let nomor = $(this).data('id');
    let urlSurat = $(this).data('url-surat');

    $('#idSurat_edit').val(nomor);
    $('#nomor_surat_edit').val($(this).data('nomor'));
    $('#tanggal_edit').val($(this).data('tanggal'));
    $('#tipe_edit').val($(this).data('tipe'));
    $('#perihal_edit').val($(this).data('perihal'));

    if (urlSurat) {
      let relativePath = urlSurat.replace('/storage/dokumen/', '');
      $('#currentFile').html(
        `File saat ini: <a href="/getDokumen/${relativePath}" target="_blank">Lihat</a>`
      );
      $('#old_url_surat').val(urlSurat);
    } else {
      $('#currentFile').html('<span class="text-muted">Tidak ada file</span>');
      $('#old_url_surat').val('');
    }

    $('#formEditSurat').attr('action', '/ref-surat-pemeliharaan/update/' + encodeURIComponent(nomor));
  });
</script>
@endsection