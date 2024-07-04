@extends('admin.templates.sidebar')

@section('contain')

<!-- Awal Dashboard Pegawai -->
<main class="pt-3 content" style="background: #D9D9D9;">
    <div class="container-fluid page_content page_1">
        <div class="row">
            <div class="col-md-12">
                <h4>Kelola User / <span class="fw-bold">Pegawai</span></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_pegawai">
                                    <i href="{{ route('admin-pegawai.create') }} class=" fa fa-plus"></i> Tambah
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="example" class="table table-bordered data-table" style="width: 100%">
                                <thead>
                                    <tr class="text-center small">
                                        <th class="th-sm">No</th>
                                        <th class="th-md">NIP/NIK</th>
                                        <th class="th-md">Nama</th>
                                        <th class="th-sm">Golongan</th>
                                        <th class="th-md">Pangkat</th>
                                        <th class="th-md">Pokja</th>
                                        <th class="th-lg-percent">Aksi</th>
                                    </tr>
                                </thead>
                                @foreach ($pegawais as $pegawai)
                                <tr>
                                    <td class='text-center'>{{ $loop->iteration }}</td>
                                    <td class='text-center'>{{ $pegawai->NIP_NIK }}</td>
                                    <td>{{ $pegawai->nama_lengkap }}</td>
                                    <td class='text-center'>{{ $pegawai->golongan }}</td>
                                    <td class='text-center'>{{ $pegawai->pangkat }}</td>
                                    <td class='text-center'>{{ $pegawai->pokja }}</td>
                                    <td class='text-center d-flex justify-content-center'>
                                        <span class="p-1">
                                            <a href="{{ route('admin-pegawai.edit', $pegawai->id) }}" class="btn btn-primary"><i class="fa-regular fa-file-lines"></i></a>
                                        </span>
                                        <span class="p-1">
                                            <form action="{{ route('admin-pegawai.destroy', $pegawai->id) }}" method="post">
                                                @method('delete')
                                                @csrf
                                                <button type="submit" class="text-decoration-none btn btn-danger" onclick="return confirm('Apakah Anda yakin akan menghapus data ini?')"><i class="fa-solid fa-trash"></i></button>
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
</main>
<!-- Akhir Dashboard Pegawai -->

<!-- Awal Modal Tambah Pegawai -->
<div class="modal fade" id="tambah_pegawai" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Pegawai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- start form tambah data --}}
            <form action="{{ route('admin-pegawai.store') }}" method="post">
                @csrf
                <div class="modal-body col-md-12">

                    {{-- nip nik --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="InputNIPNIK" class="form-label">NIP/NIK</label>
                        </div>
                        <div class="col-md-8">
                            <input name="NIP_NIK" type="text" class="form-control" id="InputNIPNIK" required>
                        </div>
                    </div>

                    {{-- nama lengkap --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="InputNamaLengkap" class="form-label">Nama Lengkap</label>
                        </div>
                        <div class="col-md-8">
                            <input name="nama_lengkap" type="text" class="form-control" id="InputNamaLengkap" required>
                        </div>
                    </div>

                    {{-- jenis kelamin --}}
                    <div class=" row">
                            <div class="col-md-4">
                                <label for="InputJenisKelamin" class="form-label">Jenis Kelamin</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group mb-3 submit-select">
                                    <select name="jenis_kelamin" class="form-select text-muted" id="InputJenisKelamin">
                                        <option selected>Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki">Laki-Laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- status pns non pns --}}
                        <div class="row">
                            <div class="col-md-4">
                                <label for="InputStatus" class="form-label">Status</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group mb-3 submit-select">
                                    <select name="status" class="form-select text-muted" id="InputStatus">
                                        <option selected>Pilih Status</option>
                                        <option value="PNS">PNS</option>
                                        <option value="non PNS">Non PNS</option>
                                        <option value="non PNS">PPPK</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- golongan --}}
                        <div class="row">
                            <div class="col-md-4">
                                <label for="InputGolongan" class="form-label">Golongan</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group mb-3 submit-select">
                                    <select name="golongan" id="" class="form-select js-example-basic-single-6" style="width: 100%;">
                                        <option value="-" selected>Pilih Golongan</option>
                                        <option value="-">-</option>
                                        <option value="II/a">II/a</option>
                                        <option value="II/b">II/b</option>
                                        <option value="II/c">II/c</option>
                                        <option value="II/d">II/d</option>
                                        <option value="III/a">III/a</option>
                                        <option value="III/b">III/b</option>
                                        <option value="III/c">III/c</option>
                                        <option value="III/d">III/d</option>
                                        <option value="IV/a">IV/a</option>
                                        <option value="IV/a">IV/b</option>
                                        <option value="IV/c">IV/c</option>
                                        <option value="IV/d">IV/d</option>
                                        <option value="IV/e">IV/e</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- pangkat --}}
                        <div class="row">
                            <div class="col-md-4">
                                <label for="InputPangkat" class="form-label">Pangkat</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group mb-3 submit-select">
                                    <select name="pangkat" id="" class="form-select js-example-basic-single-6" style="width: 100%;">
                                        <option value="-" selected>Pilih Pangkat</option>
                                        <option value="-">-</option>
                                        <option value="IIa - Pengatur Muda">IIa - Pengatur Muda</option>
                                        <option value="IIb - Pengatur Muda">IIb - Pengatur Muda Tingkat 1</option>
                                        <option value="IIc - Pengatur">IIc - Pengatur</option>
                                        <option value="IId - Pengatur Tingkat 1">IId - Pengatur Tingkat 1</option>
                                        <option value="IIIa - Penata Muda">IIIa - Penata Muda</option>
                                        <option value="IIIb - Penata Muda Tingkat I">IIIb - Penata Muda Tingkat I</option>
                                        <option value="IIIc - Penata">IIIc - Penata</option>
                                        <option value="IIId - Penata Tingkat 1">IIId - Penata Tingkat 1</option>
                                        <option value="IVa - Pembina">IVa - Pembina</option>
                                        <option value="IVb - Pembina Tingkat 1">IVa - Pembina Tingkat 1</option>
                                        <option value="IVc - Pembina Utama Muda">IVa - Pembina Utama MUda</option>
                                        <option value="IVd - Pembina Utama Madya">IVd - Pembina Utama Madya</option>
                                        <option value="IVe - Pembina Utama">IVe - Pembina Utama</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- no telpon --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="InputNoTelp" class="form-label">No. Telepon</label>
                            </div>
                            <div class="col-md-8">
                                <input name="no_telp" type="text" class="form-control" id="InputNoTelp" required>
                            </div>
                        </div>

                        {{-- email --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="InputEmail" class="form-label">Email</label>
                            </div>
                            <div class="col-md-8">
                                <input name="email" type="email" class="form-control" id="InputEmail" required>
                            </div>
                        </div>

                        {{-- password --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="InputPassword" class="form-label">Password</label>
                            </div>
                            <div class="col-md-8">
                                <input name="password" type="text" class="form-control" id="InputPassword" required>
                            </div>
                        </div>

                        {{-- jabatan --}}
                        <div class="row">
                            <div class="col-md-4">
                                <label for="InputJabatan" class="form-label">Jabatan</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group mb-3 submit-select">
                                    <select name="jabatan_id" class="form-select text-muted" id="InputJabatan">
                                        <option selected>Pilih Jabatan</option>
                                        @foreach ($jabatans as $jabatan)
                                        <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- pokja --}}
                        <div class="row">
                            <div class="col-md-4">
                                <label for="InputJabatan" class="form-label">Pokja</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group mb-3 submit-select">
                                    <select name="fungsi_id" class="form-select text-muted" id="InputJabatan">
                                        <option selected>Pilih Fungsi</option>
                                        @foreach ($fungsis as $fungsi)
                                        <option value="{{ $fungsi->id }}">{{ $fungsi->nama_fungsi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- no rekening --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="InputNoRekening" class="form-label">No. Rekening</label>
                            </div>
                            <div class="col-md-8">
                                <input name="no_rekening" type="text" class="form-control" id="InputNoRekening" required>
                            </div>
                        </div>

                        {{-- is aktif --}}
                        <div class="row">
                            <div class="col-md-4">
                                <label for="InputStatusAktif" class="form-label">Is Aktif</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group mb-3 submit-select">
                                    <select name="is_aktif" class="form-select text-muted" id="InputStatusAktif">
                                        <option selected>Pilih</option>
                                        <option value="1">Aktif</option>
                                        <option value="0">Nonaktif</option>
                                    </select>
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
<!-- Akhir Modal Tambah Pegawai -->


@endsection