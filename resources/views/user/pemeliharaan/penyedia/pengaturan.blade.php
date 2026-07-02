@extends('user.pemeliharaan.penyedia.sidebar')
@section('contain')
    <section class="mt-4">
        <div class="container">
            <div class="row justify-content-center">
                <!-- Kop Surat (DIATAS GANTI PASSWORD) -->
                <div class="col-md-8 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">Kop Surat</h5>
                        </div>
                        <div class="card-body">
                            @if ($data->isEmpty())
                                <!-- Jika belum ada data, tampilkan form tambah -->
                                <form action="{{ url('/penyedia/kop-surat/store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="nama_kop" class="form-label">Nama Kop Surat</label>
                                        <input type="text" name="nama_kop" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="url_kop" class="form-label">File Kop Surat</label>
                                        <input type="file" name="url_kop" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            @else
                                @php $item = $data->first(); @endphp
                                <p><strong>Nama:</strong> {{ $item->nama_kop }}</p>
                                <p>
                                    <strong>File:</strong>
                                    @if ($item->url_kop)
                                        <a href="{{ url('/getDokumen/' . basename($item->url_kop)) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            Lihat File
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                                <p><strong>Dibuat:</strong> {{ \Carbon\Carbon::parse($item->updated_at)->translatedFormat('d F Y') }}</p>

                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="{{ $item->id }}">
                                        Edit
                                    </button>
                                    <form action="{{ url('/penyedia/kop-surat/delete/' . $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kop surat ini?')">Delete</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Ganti Password (DI BAWAH KOP SURAT) -->
                <div class="col-md-8 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">Ganti Password</h5>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @elseif (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <form method="POST" action="{{ url('/penyedia/ganti-password') }}">
                                @csrf
                                <div class="mb-3">
                                    <label>Password Lama <span class="text-danger">*</span></label>
                                    <input type="password" name="password_lama" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Password Baru <span class="text-danger">*</span></label>
                                    <input type="password" name="password_baru" class="form-control" required>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Edit Kop Surat -->
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
            $.get(`/penyedia/kop-surat/${id}`, function(data) {
                $('#edit_nama_kop').val(data.nama_kop);
                $('#formEditKop').attr('action', `/penyedia/kop-surat/update/${id}`);
                $('#editModal').modal('show');
            });
        });
    </script>
@endsection
