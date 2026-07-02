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
                                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                        data-bs-target="#tambah_pegawai">
                                        <i class=" fa fa-plus"></i> Tambah
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
                                                    <button type="button" class="btn btn-primary btn-edit"
                                                        data-bs-toggle="modal" data-bs-target="#tambah_pegawai"
                                                        data-id="{{ $pegawai->id }}" data-nip_nik="{{ $pegawai->NIP_NIK }}"
                                                        data-nama_lengkap="{{ $pegawai->nama_lengkap }}"
                                                        data-jenis_kelamin="{{ $pegawai->jenis_kelamin }}"
                                                        data-status="{{ $pegawai->status }}"
                                                        data-id_golongan="{{ $pegawai->id_golongan ?? '' }}"
                                                        data-no_telp="{{ $pegawai->no_telp }}"
                                                        data-email="{{ $pegawai->email }}"
                                                        data-jabatan_id="{{ $pegawai->jabatan_id }}"
                                                        data-fungsi_id="{{ $pegawai->fungsi_id }}"
                                                        data-npwp="{{ $pegawai->npwp }}" data-bank="{{ $pegawai->bank }}"
                                                        data-no_rekening="{{ $pegawai->no_rekening }}"
                                                        data-nama_rekening="{{ $pegawai->nama_rekening }}"
                                                        data-is_aktif="{{ $pegawai->is_aktif }}">
                                                        <i class="fa-regular fa-file-lines"></i>
                                                    </button>
                                                </span>
                                                <span class="p-1">
                                                    <form action="{{ route('admin-pegawai.destroy', $pegawai->id) }}"
                                                        method="post">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="submit" class="text-decoration-none btn btn-danger"
                                                            onclick="return confirm('Apakah Anda yakin akan menghapus data ini?')"><i
                                                                class="fa-solid fa-trash"></i></button>
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
                                <input name="nama_lengkap" type="text" class="form-control" id="InputNamaLengkap"
                                    required>
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
                                        <option value="PPNPN">PPNPN</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Golongan + Pangkat --}}
                        <div class="row">
                            <div class="col-md-4">
                                <label for="InputGolongan" class="form-label">Golongan</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group mb-3 submit-select">
                                    <select name="id_golongan" class="form-select js-example-basic-single-6"
                                        style="width: 100%;" required>
                                        <option value="">Pilih Golongan</option>
                                        @foreach ($ref_golongan_pangkats as $gol)
                                            <option value="{{ $gol->id_ref_golongan_pangkat }}">{{ $gol->golongan }} -
                                                {{ $gol->pangkat }}</option>
                                        @endforeach
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
                                <label for="InputPokja" class="form-label">Pokja</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group mb-3 submit-select">
                                    <select name="fungsi_id" class="form-select text-muted" id="InputPokja">
                                        <option selected>Pilih Fungsi</option>
                                        @foreach ($fungsis as $fungsi)
                                            <option value="{{ $fungsi->id }}">{{ $fungsi->nama_fungsi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- NPWP --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="InputNPWP" class="form-label">NPWP</label>
                            </div>
                            <div class="col-md-8">
                                <input name="npwp" type="text" class="form-control" id="InputNPWP" required>
                            </div>
                        </div>

                        {{-- Bank --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="InputBank" class="form-label">Bank</label>
                            </div>
                            <div class="col-md-8">
                                <select name="bank" class="form-select text-muted" id="InputBank">
                                    <option selected>Pilih Bank</option>
                                    @foreach ($data_bank as $bank)
                                        <option value="{{ $bank->kode_bank }}">{{ $bank->kode_bank }}
                                            ({{ $bank->nama_bank }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        {{-- no rekening --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="InputNoRekening" class="form-label">No. Rekening</label>
                            </div>
                            <div class="col-md-8">
                                <input name="no_rekening" type="text" class="form-control" id="InputNoRekening"
                                    required>
                            </div>
                        </div>

                        {{-- nama rekening --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="InputNamaRekening" class="form-label">Nama Rekening</label>
                            </div>
                            <div class="col-md-8">
                                <input name="nama_rekening" type="text" class="form-control" id="InputNamaRekening"
                                    required>
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
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('tambah_pegawai');
    const form = modal.querySelector('form');

    // Ambil semua tombol edit
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function () {
            // Isi form modal dengan data dari atribut data-*
            form.action = "{{ route('admin-pegawai.store') }}"; // action default untuk store

            // Kalau ada id, ubah jadi route update dengan method PUT
            const id = this.dataset.id;
            if (id) {
                form.action = `/admin-pegawai/${id}`; // sesuaikan route update jika beda
                const methodInput = form.querySelector('input[name="_method"]');
                if (!methodInput) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = '_method';
                    input.value = 'PUT';
                    form.appendChild(input);
                } else {
                    methodInput.value = 'PUT';
                }
            }

            // Isi field input
            form.querySelector('[name="NIP_NIK"]').value = this.dataset.nip_nik || '';
            form.querySelector('[name="nama_lengkap"]').value = this.dataset.nama_lengkap || '';
            form.querySelector('[name="jenis_kelamin"]').value = this.dataset.jenis_kelamin || '';
            form.querySelector('[name="status"]').value = this.dataset.status || '';
            form.querySelector('[name="id_golongan"]').value = this.dataset.id_golongan || '';
            form.querySelector('[name="no_telp"]').value = this.dataset.no_telp || '';
            form.querySelector('[name="email"]').value = this.dataset.email || '';
            form.querySelector('[name="jabatan_id"]').value = this.dataset.jabatan_id || '';
            form.querySelector('[name="fungsi_id"]').value = this.dataset.fungsi_id || '';
            form.querySelector('[name="npwp"]').value = this.dataset.npwp || '';
            form.querySelector('[name="bank"]').value = this.dataset.bank || '';
            form.querySelector('[name="no_rekening"]').value = this.dataset.no_rekening || '';
            form.querySelector('[name="nama_rekening"]').value = this.dataset.nama_rekening || '';
            form.querySelector('[name="is_aktif"]').value = this.dataset.is_aktif || '';
            
            // Kosongkan password supaya user bisa isi ulang kalau mau ganti
            form.querySelector('[name="password"]').value = '';
        });
    });

    // Reset form modal saat ditutup
    modal.addEventListener('hidden.bs.modal', function () {
        form.reset();
        form.action = "{{ route('admin-pegawai.store') }}";
        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();
    });
});
</script>

    <!-- Akhir Modal Tambah Pegawai -->
@endsection
