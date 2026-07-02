@extends('admin.templates.sidebar')

@section('contain')
    <!-- Awal Dashboard -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h4>Data Kop Surat</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                    data-bs-target="#tambah_kode_layanan">
                                    <i class="fa fa-plus"></i> Tambah Kop Surat
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="example" class="table table-bordered data-table" style="width: 100%">
                                <thead>
                                    <tr class="text-center small">
                                        <th class="th-lg-percent">No</th>
                                        <th class="th-lg-percent">Nama</th>
                                        <th class="th-lg-percent">File</th>
                                        <th class="th-lg-percent">Status</th>
                                        <th class="th-lg-percent">Dibuat Tanggal</th>
                                        <th class="th-lg-percent" style="min-width:100px;">Aksi</th>
                                    </tr>
                                </thead>
                                @foreach ($data as $item)
                                    <tr>
                                        <td class='text-center'>{{ $loop->iteration }}</td>
                                        <td class='text-center'>{{ $item->nama_kop }}</td>
                                        <td class='text-center'>
                                            @if ($item->url_kop)
                                                <a href="{{ url('/getDokumen/' . basename($item->url_kop)) }}"
                                                    target="_blank" class="btn btn-sm btn-outline-primary">
                                                    Lihat File
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class='text-center'>
                                            @if ($item->is_aktif != '1')
                                                Nonaktif
                                            @else
                                                Aktif
                                            @endif
                                        </td>

                                        <td class='text-center'>
                                            {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</td>
                                        <td class='text-center'>
                                            @if ($item->is_aktif != '1')
                                                <form action="{{ route('kopsurat.aktifkan', $item->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary btn-sm"
                                                        onclick="return confirm('Aktifkan kop surat ini?')">
                                                        Aktifkan
                                                    </button>
                                                </form>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-warning edit-btn"
                                                data-id="{{ $item->id }}">
                                                Edit
                                            </button>
                                            <form action="{{ url('/ref-kop-surat/delete/' . $item->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin ingin menghapus kop surat ini?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Tambah Kop Surat -->
    <div class="modal fade" id="tambah_kode_layanan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ url('/ref-kop-surat/store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Kop Surat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_kop" class="form-label">Nama Kop Surat</label>
                            <input type="text" name="nama_kop" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="url_kop" class="form-label">File Kop Surat</label>
                            <input type="file" name="url_kop" class="form-control" accept=".pdf,.jpg,.jpeg,.png"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formEditKop" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Kop Surat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kop Surat</label>
                            <input type="text" name="nama_kop" id="edit_nama_kop" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">File Baru (opsional)</label>
                            <input type="file" name="url_kop" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            $.get(`/ref-kop-surat/${id}`, function(data) {
                $('#edit_nama_kop').val(data.nama_kop);
                $('#formEditKop').attr('action', `/ref-kop-surat/update/${id}`);
                $('#editModal').modal('show');
            });
        });
    </script>
@endsection
