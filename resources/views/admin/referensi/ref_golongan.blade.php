@extends('admin.templates.sidebar')

@section('contain')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h4>Referensi / <span class="fw-bold">Golongan & Pangkat</span></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body content">
                        <div class="row mb-3 page_content page_1">
                            <div class="table-responsive">
                                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                    data-bs-target="#tambah_golongan">
                                    <i class="fa fa-plus"></i> Tambah
                                </button>
                                <table id="example" class="table table-bordered data-table" style="width: 100%">
                                    <thead>
                                        <tr class="text-center small">
                                            <th class="th-sm">No</th>
                                            <th class="th-md">Golongan</th>
                                            <th class="th-sm">Pangkat</th>
                                            <th class="th-lg-percent">Aksi</th>
                                        </tr>
                                    </thead>
                                    @foreach ($golongans as $golongan)
                                        <tr>
                                            <td class='text-center'>{{ $loop->iteration }}</td>
                                            <td class='text-center'>{{ $golongan->golongan }}</td>
                                            <td class='text-center'>{{ $golongan->pangkat }}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-primary btn-edit"
                                                    data-bs-toggle="modal" data-bs-target="#tambah_golongan"
                                                    data-id="{{ $golongan->id_ref_golongan_pangkat }}"
                                                    data-golongan="{{ $golongan->golongan }}"
                                                    data-pangkat="{{ $golongan->pangkat }}">
                                                    <i class="fa fa-edit"></i> Edit
                                                </button>


                                                <form action="{{ route('destroy_golongan', $golongan->id_ref_golongan_pangkat) }}" method="post"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('Yakin hapus data?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
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

        <!-- Awal Tambah golongan -->
        <div class="modal fade" id="tambah_golongan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formGolongan" method="post" action="{{ route('c_golongan') }}">
                        @csrf
                        <input type="hidden" name="_method" value="POST" id="methodInput">
                        <input type="hidden" name="id" id="golonganId">
                        <div class="modal-body">
                            <div class="col-md-8">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="golongan" class="form-label">Golongan</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="golonganInput" name="golongan">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="pangkat" class="form-label">Pangkat</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="pangkatInput" name="pangkat">
                                    </div>
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
            document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('tambah_golongan');
            const form = document.getElementById('formGolongan');
            const idInput = document.getElementById('golonganId');
            const golonganInput = document.getElementById('golonganInput');
            const pangkatInput = document.getElementById('pangkatInput');
            const methodInput = document.getElementById('methodInput');
        
            document.querySelectorAll('.btn-edit').forEach(button => {
               button.addEventListener('click', function() {
                const id = this.dataset.id;
                const golongan = this.dataset.golongan;
                const pangkat = this.dataset.pangkat;
            
                // gunakan route update
                form.action = `/ref-golongan/c_golongan/${id}`;
                methodInput.value = 'PUT'; // ganti method
                idInput.value = id;
                golonganInput.value = golongan;
                pangkatInput.value = pangkat;
            });

            });
        
            modal.addEventListener('hidden.bs.modal', function() {
                form.action = '{{ route('c_golongan') }}';
                methodInput.value = 'POST';
                idInput.value = '';
                golonganInput.value = '';
                pangkatInput.value = '';
            });
        });

        </script>
    @endsection
