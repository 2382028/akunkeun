@extends('admin.templates.sidebar')

@section('contain')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h4>BMN / <span class="fw-bold">Data Penyedia</span></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body content">

                        <!-- Data Penyedia - ALl -->
                        <div class="row page_content page_1">
                            <div class="table-responsive">
                                <div class="d-flex justify-content-between mb-3">
                                    <!-- Button Tambah (kiri) -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#tambah_datapenyedia">
                                        <i class="fa fa-plus"></i> Tambah
                                    </button>

                                    <!-- Button Lihat Daftar Hitam (kanan) -->
                                    <a href="https://daftar-hitam.inaproc.id/" target="_blank" class="btn btn-danger">
                                        <i class="fa fa-exclamation-triangle"></i> Lihat Daftar Hitam
                                    </a>
                                </div>

                                <table id="example" class="table table-bordered data-table" style="width: 100%">
                                    <thead>
                                        <tr class="text-center small">
                                            <th class="th-sm">No</th>
                                            <th class="th-md">NPWP</th>
                                            <th class="th-md">Penanggung Jawab</th>
                                            <th class="th-md">Nama CV</th>
                                            <th class="th-md">No Telepon</th>
                                            <th class="th-sm">Kategori</th>
                                            <th class="th-md">Tahun Bergabung</th>
                                            <th class="th-lg-percent">Aksi</th>
                                        </tr>
                                    </thead>
                                    @foreach ($penyedias as $penyedia)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $penyedia->NPWP }}</td>
                                            <td>{{ $penyedia->penanggung_jawab }}</td>
                                            <td>{{ $penyedia->nama_CV }}</td>
                                            <td class="text-center">{{ $penyedia->no_telp }}</td>
                                            <td class="text-center">{{ $penyedia->kategori }}</td>
                                            <td class="text-center">{{ $penyedia->tahun }}</td>
                                            <td class='text-center d-flex justify-content-center'>
                                            <!-- Tombol Edit -->
                                            <span class="p-1">
                                                <button type="button" class="btn btn-success btn-edit-penyedia" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#edit_datapenyedia"
                                                    data-id="{{ $penyedia->id }}"
                                                    data-email="{{ $penyedia->email }}"
                                                    data-NPWP="{{ $penyedia->NPWP }}"
                                                    data-nama="{{ $penyedia->nama_CV }}"
                                                    data-penanggung="{{ $penyedia->penanggung_jawab }}"
                                                    data-jabatan="{{ $penyedia->jabatan }}"
                                                    data-telp="{{ $penyedia->no_telp }}"
                                                    data-alamat="{{ $penyedia->alamat }}"
                                                    data-kategori="{{ $penyedia->kategori }}"
                                                    data-tahun="{{ $penyedia->tahun }}">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                            </span>
                                        
                                            <!-- Tombol Hapus -->
                                            <span class="p-1">
                                                <form action="{{ url('/d_penyedia/' . $penyedia->id) }}" method="post">
                                                    @csrf
                                                    <button type="submit" class="text-decoration-none btn btn-danger" onclick="return confirm('Hapus Data Penyedia?')">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </span>
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
    </div>

    <!-- Awal Modal Tambah Data Penyedia -->
    <div class="modal fade" id="tambah_datapenyedia" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ url('/c_bmn_penyedia') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Data Penyedia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body col-md-12">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Email</label>
                            </div>
                            <div class="col-md-8">
                                <input type="email" class="form-control" id="" name="email" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Password</label>
                            </div>
                            <div class="col-md-8">
                                <input type="password" class="form-control" id="" name="password" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">NPWP</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="NPWP">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Nama Penyedia</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="namaPenyedia" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Nama Penanggung Jawab</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="penanggungJawab"
                                    required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Jabatan</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="jabatan" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">No. Telepon</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="telps" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Alamat</label>
                            </div>
                            <div class="col-md-8">
                                <textarea class="form-control" id="" rows="3" name="alamat" required></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Kategori</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="kategori" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Tahun Gabung</label>
                            </div>
                            <div class="col-md-8">
                                <input type="date" class="form-control" id="" name="tahun" required>
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
    <!-- Akhir Modal Tambah Data Penyedia -->
    <div class="modal fade" id="edit_datapenyedia" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="formEditPenyedia" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data Penyedia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body col-md-12">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Email</label>
                        </div>
                        <div class="col-md-8">
                            <input type="email" class="form-control" name="email" id="edit_email" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Password</label>
                        </div>
                        <div class="col-md-8">
                            <input type="password" class="form-control" name="password" id="edit_password" placeholder="Isi jika ingin mengganti">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><label class="form-label">NPWP</label></div>
                        <div class="col-md-8"><input type="text" class="form-control" name="NPWP" id="edit_NPWP"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><label class="form-label">Nama Penyedia</label></div>
                        <div class="col-md-8"><input type="text" class="form-control" name="namaPenyedia" id="edit_nama" required></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><label class="form-label">Penanggung Jawab</label></div>
                        <div class="col-md-8"><input type="text" class="form-control" name="penanggungJawab" id="edit_penanggung" required></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><label class="form-label">Jabatan</label></div>
                        <div class="col-md-8"><input type="text" class="form-control" name="jabatan" id="edit_jabatan" required></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><label class="form-label">No. Telepon</label></div>
                        <div class="col-md-8"><input type="text" class="form-control" name="telps" id="edit_telp" required></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><label class="form-label">Alamat</label></div>
                        <div class="col-md-8"><textarea class="form-control" rows="3" name="alamat" id="edit_alamat" required></textarea></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><label class="form-label">Kategori</label></div>
                        <div class="col-md-8"><input type="text" class="form-control" name="kategori" id="edit_kategori" required></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><label class="form-label">Tahun Gabung</label></div>
                        <div class="col-md-8"><input type="date" class="form-control" name="tahun" id="edit_tahun" required></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.btn-edit-penyedia');
    const form = document.getElementById('formEditPenyedia');

    editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            form.action = '/update_penyedia/' + id;

            document.getElementById('edit_id').value = id;
            document.getElementById('edit_email').value = this.dataset.email;
            document.getElementById('edit_NPWP').value = this.dataset.npwp;
            document.getElementById('edit_nama').value = this.dataset.nama;
            document.getElementById('edit_penanggung').value = this.dataset.penanggung;
            document.getElementById('edit_jabatan').value = this.dataset.jabatan;
            document.getElementById('edit_telp').value = this.dataset.telp;
            document.getElementById('edit_alamat').value = this.dataset.alamat;
            document.getElementById('edit_kategori').value = this.dataset.kategori;
            document.getElementById('edit_tahun').value = this.dataset.tahun;

            document.getElementById('edit_password').value = ''; // kosongkan password
        });
    });
});
</script>

@endsection
