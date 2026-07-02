@extends('admin.templates.sidebar')

@section('contain')

    <!-- Awal Dashboard - Non Pegawai -->
    <main class="pt-3 content" style="background: #D9D9D9;">
      <div class="container-fluid page_content page_1">
        <div class="row">
          <div class="col-md-12">
            <h4>Kelola User / <span class="fw-bold">Non Pegawai</span></h4>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 mb-3">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_nonpegawai">
                        <i href="{{ route('admin-nonpegawai.create') }}" class="fa fa-plus"></i> Tambah
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
                        <th class="th-lg-percent">Aksi</th>
                      </tr>
                    </thead>
                    @foreach ($nonpegawais as $nonpegawai)
                      <tr>
                          <td class='text-center'>{{ $loop->iteration }}</td>
                          <td class=''>{{ $nonpegawai->NIP_NIK }}</td>
                          <td>{{ $nonpegawai->nama_lengkap }}</td>
                          <td class='text-center'>{{ $nonpegawai->golongan }}</td>
                          <td class='text-center  d-flex justify-content-evenly'>
                            <div class="btn-group mx-auto gap-2">
                              <span class="p-1">
                                <a href="{{ route('admin-nonpegawai.edit', $nonpegawai->id) }}" class="btn  btn-primary"><i class="fa-regular fa-file-lines"></i></a>
                              </span>
                              <span class="p-1">
                                <form action="{{ route('admin-nonpegawai.destroy', $nonpegawai->id) }}" method="post">
                                  @method('delete')
                                  @csrf
                                  <button type="submit" class="text-decoration-none btn btn-danger " onclick="return confirm('Apakah Anda yakin akan menghapus data ini?')"><i class="fa-solid fa-trash"></i></button>
                                </form>
                              </span>
                            </div>
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
    <!-- Akhir Dashboard - Non Pegawai -->

    <!-- Awal Modal Tambah Non Pegawai -->
    <div class="modal fade" id="tambah_nonpegawai" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin-nonpegawai.store') }}" method="POST">
            @csrf
            <div class="modal-body col-md-12">
              {{-- nip dan nik --}}
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
                <div class="row mb-3">
                  <div class="col-md-4">
                      <label for="InputJenisKelamin" class="form-label">Jenis Kelamin</label>
                  </div>
                  <div class="col-md-8">
                    <select name="jenis_kelamin" class="form-select text-muted" id="InputJenisKelamin" required>
                      <option value="Laki-laki">Laki-laki</option>
                      <option value="Perempuan">Perempuan</option>
                  </select>
                  </div>
              </div>

              {{-- golongan --}}
              <div class="row mb-3">
                <div class="col-md-4">
                    <label for="InputGolongan" class="form-label">Golongan</label>
                </div>
                <div class="col-md-8">
                  <select name="" id="" class="form-select" style="width: 100%;">
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

            {{-- pangkat --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="InputPangkat" class="form-label">Pangkat</label>
                </div>
                <div class="col-md-8">
                    <select name="pangkat" id="" class="form-select" style="width: 100%;">
                        <option value="-" selected>Pilih Pangkat</option>
                        <option value="-">-</option>
                        <option value="Pengatur Muda">II/a - Pengatur Muda</option>
                        <option value="Pengatur Muda Tingkat 1">II/b - Pengatur Muda Tingkat 1</option>
                        <option value="Pengatur">II/c - Pengatur</option>
                        <option value="Pengatur Tingkat 1">II/d - Pengatur Tingkat 1</option>
                        <option value="Penata Muda">III/a - Penata Muda</option>
                        <option value="Penata Muda Tingkat I">III/b - Penata Muda Tingkat I</option>
                        <option value="Penata">III/c - Penata</option>
                        <option value="Penata Tingkat 1">III/d - Penata Tingkat 1</option>
                        <option value="Pembina">IV/a - Pembina</option>
                        <option value="Pembina Tingkat 1">IV/b - Pembina Tingkat 1</option>
                        <option value="Pembina Utama Muda">IV/c - Pembina Utama Muda</option>
                        <option value="Pembina Utama Madya">IV/d - Pembina Utama Madya</option>
                        <option value="Pembina Utama">IV/e - Pembina Utama</option>
                    </select>
                </div>
            </div>

              {{-- status --}}
              <div class="row mb-3">
                <div class="col-md-4">
                    <label for="InputStatus" class="form-label">Status</label>
                </div>
                <div class="col-md-8">
                    <select name="status" class="form-select text-muted" id="InputStatus">
                        <option selected>Pilih Status</option>
                        <option value="PNS">PNS</option>
                        <option value="NON PNS">Non PNS</option>
                        <option value="NON PNS">PPPK</option>
                        <option value="PPNPN">PPNPN</option>
                    </select>
                </div>
              </div>



              {{-- alamat --}}
              <div class="row mb-3">
                <div class="col-md-4">
                    <label for="InputAlamat" class="form-label">Alamat</label>
                </div>
                <div class="col-md-8">
                  <input type="textarea" name="alamat" class="form-control" id="InputAlamat" required>
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

                {{-- no_telp --}}
                <div class="row mb-3">
                  <div class="col-md-4">
                      <label for="InputNoTelp" class="form-label">No. Telp</label>
                  </div>
                  <div class="col-md-8">
                      <input name="no_telp" type="text" class="form-control" id="InputNoTelp" required>
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
                        <option readonly selected>Pilih Bank</option>
                        @foreach ($data_bank as $bank)
                            <option value="{{ $bank->kode_bank }}">{{$bank->kode_bank}} ({{ $bank->nama_bank }})</option>
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
                    <input name="no_rekening" type="text" class="form-control" id="InputNoRekening" required>
                </div>
            </div>

            {{-- nama rekening --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="InputNamaRekening" class="form-label">Nama Rekening</label>
                </div>
                <div class="col-md-8">
                    <input name="nama_rekening" type="text" class="form-control" id="InputNamaRekening" required>
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
                            <option disabled selected>Pilih</option>
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
    <!-- Akhir Modal Tambah Non Pegawai -->
@endsection
