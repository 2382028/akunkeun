@extends('admin.templates.sidebar')

@section('contain')
    <style>
        .form-label {
            display: flex;
            align-items: center;
            height: 100%;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h4>BMN / <span class="fw-bold">Data Ruangan</span></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body content">
                        <div class="row page_content page_1">
                            <div class="table-responsive">
                                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                    data-bs-target="#tambah_ruangan">
                                    <i class="fa fa-plus"></i> Tambah
                                </button>
                                <table id="example" class="table table-bordered" style="width: 100%">
                                    <thead>
                                        <tr class="text-center small">
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Nama Ruangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="tambah_ruangan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ url('/ruangan/save') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" id="ruangan_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Data Ruangan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body col-md-12">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Kode Ruangan</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="kode_ruangan" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Nama Ruangan</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="nama_ruangan" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.edit-btn', function() {
                const data = $(this).data('item');
                $('input[name="id"]').val(data.id_ruangan_bmn);
                $('input[name="kode_ruangan"]').val(data.kode_ruangan);
                $('input[name="nama_ruangan"]').val(data.nama_ruangan);
            });


            let table = $('#example').DataTable({
                ajax: "{{ url('/ruangan/json') }}",
                columns: [{
                        data: null,
                        render: (data, type, row, meta) => meta.row + 1
                    },
                    {
                        data: 'kode_ruangan'
                    },
                    {
                        data: 'nama_ruangan'
                    },
                    {
                        data: 'id_ruangan_bmn',
                        render: function(data, type, row) {
                            return `
                <div class='d-flex justify-content-center'>
                    <a href="#" class="btn btn-success me-1 edit-btn" data-bs-toggle="modal" data-bs-target="#tambah_ruangan"
                        data-item='${JSON.stringify(row)}'>
                        <i class='fa-solid fa-pen-to-square'></i>
                    </a>
                    <form action='/ruangan/delete/${data}' method='POST' onsubmit="return confirm('Hapus Data?')">
                        @csrf
                        @method('DELETE')
                        <button type='submit' class='btn btn-danger me-1'>
                            <i class='fa-solid fa-trash'></i>
                        </button>
                    </form>
                </div>`;
                        }
                    }
                ]
            });
        });
    </script>
@endsection
