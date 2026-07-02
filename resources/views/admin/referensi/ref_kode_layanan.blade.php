@extends('admin.templates.sidebar')

@section('contain')
    <!-- Awal Dashboard -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h4>Data Kode Layanan</h4>
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
                                    <i class="fa fa-plus"></i> Tambah Kode Layanan
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="example" class="table table-bordered data-table" style="width: 100%">
                                <thead>
                                    <tr class="text-center small">
                                        <th class="th-lg-percent">No</th>
                                        <th class="th-lg-percent">Kode Layanan</th>
                                        <th class="th-lg-percent">Deskripsi</th>
                                        <th class="th-lg-percent">Dibuat Tanggal</th>
                                        <th class="th-lg-percent" style="min-width:100px;">Aksi</th>
                                    </tr>
                                </thead>
                                @foreach ($data as $item)
                                    <tr>
                                        <td class='text-center'>{{ $loop->iteration }}</td>
                                        <td class='text-center'>{{ $item->kode_layanan }}</td>
                                        <td class='text-center'>{{ $item->deskripsi_kode_layanan }}</td>
                                        <td class='text-center'>
                                            {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</td>
                                        <td class='text-center'>
                                            <button type="button" class="btn btn-sm btn-warning edit-btn"
                                                data-id="{{ $item->id }}" data-kode="{{ $item->kode_layanan }}"
                                                data-deskripsi="{{ $item->deskripsi_kode_layanan }}"
                                                data-action="{{ route('kode.layanan.update', $item->id) }}"
                                                data-bs-toggle="modal" data-bs-target="#tambah_kode_layanan">
                                                Edit
                                            </button>

                                            <form action="{{ route('kode.layanan.destroy', $item->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin ingin menghapus kode layanan ini?')">Delete</button>
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
    <!-- Akhir Dashboard -->

    <!-- Modal Tambah Kode Layanan -->
    <div class="modal fade" id="tambah_kode_layanan" tabindex="-1" aria-labelledby="modalKodeLayanan" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKodeLayanan">Tambah Kode Layanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="kodeLayananForm" action="{{ route('kode.layanan.store') }}" method="post">
                        @csrf
                        <input type="hidden" id="editMethod" name="_method" value="">
                        <div class="mb-3">
                            <label for="id_kode_layanan" class="form-label">Kode Layanan</label>
                            <input type="text" id="inputKode" name="kode_layanan" class="form-control" required>

                        </div>
                        <div class="mb-3">
                            <label for="deskripsi_kode_layanan" class="form-label">Deskripsi</label>
                            <input type="text" id="inputDeskripsi" name="deskripsi_kode_layanan" class="form-control" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script>
    const modal = document.getElementById('tambah_kode_layanan');
    const form = document.getElementById('kodeLayananForm');
    const inputKode = document.getElementById('inputKode');
    const inputDeskripsi = document.getElementById('inputDeskripsi');
    const editMethod = document.getElementById('editMethod');

    // Saat klik tombol edit
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            form.action = this.dataset.action;
            inputKode.value = this.dataset.kode;
            inputDeskripsi.value = this.dataset.deskripsi;
            editMethod.value = 'POST';
            form.insertAdjacentHTML('beforeend', '@method("PUT")');
        });
    });

    // Saat modal dibuka ulang dengan tombol tambah, reset isi
    modal.addEventListener('show.bs.modal', function (event) {
        const trigger = event.relatedTarget;
        if (!trigger.classList.contains('edit-btn')) {
            form.action = "{{ route('kode.layanan.store') }}";
            inputKode.value = '';
            inputDeskripsi.value = '';
            editMethod.removeAttribute('value');
            // Hapus method PUT jika sebelumnya ada
            const hiddenMethod = form.querySelector('input[name="_method"][value="PUT"]');
            if (hiddenMethod) hiddenMethod.remove();
        }
    });
</script>

@endsection
