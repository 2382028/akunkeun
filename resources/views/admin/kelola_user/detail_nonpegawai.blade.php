@extends('admin.templates.sidebar')

@section('contain')
<main class="pt-3 content" style="background: #D9D9D9;">
  {{-- start detail data non pegawai --}}
  <div class="container-fluid page_content page_2">
    <div class="row">
      <div class="col-md-12">
        <h4>Kelola User / <span class="fw-bold">Non Pegawai</span> / <span class="fw-bold">Detail</span></h4>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <h5>Informasi Detail Pegawai</h5>
            </div>

            <form action="{{ route('admin-nonpegawai.update',  $nonpegawai->id) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="d-flex justify-content-between mt-3">
                <div class="row detail-information">

                  {{-- nip dan nik --}}
                  <div class="row mb-3">
                    <div class="col-md-3">
                      <label for="InputNIPNIK" class="form-label">NIP/NIK</label>
                    </div>
                    <div class="col-md-9">
                      <input type="text" class="form-control" id="InputNIPNIK" value="{{ $nonpegawai->NIP_NIK }}" name="NIP_NIK">
                    </div>
                  </div>

                  {{-- nama lengkap --}}
                  <div class="row mb-3">
                    <div class="col-md-3">
                      <label for="InputNamaLengkap" class="form-label">Nama Lengkap</label>
                    </div>
                    <div class="col-md-9">
                      <input type="text" class="form-control" id="InputNamaLengkap" value="{{ $nonpegawai->nama_lengkap }}" name="nama_lengkap">
                    </div>
                  </div>

                  {{-- jenis kelamin --}}
                 <div class="row mb-3">
                  <div class="col-md-3">
                   <label for="InputJenisKelamin" class="form-label">Jenis Kelamin</label>
                  </div>
                  <div class="col-md-9">
                  <select name="jenis_kelamin" class="form-select text-muted" id="InputStatus">
                      <option value="Laki-laki" {{ $nonpegawai->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-Laki</option>
                      <option value="Perempuan" {{ $nonpegawai->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                  </select>

                  </div>
                </div>

                {{-- status pns/non pns --}}
                <div class="row mb-3">
                 <div class="col-md-3">
                  <label for="InputStatus" class="form-label">Status</label>
                 </div>
                 <div class="col-md-9">
                 <select name="status" class="form-select text-muted" id="InputStatus">
                    <option value="-" {{ $nonpegawai->status == '-' ? 'selected' : '' }}>-</option>
                    <option value="PNS" {{ $nonpegawai->status == 'PNS' ? 'selected' : '' }}>PNS</option>
                    <option value="non PNS" {{ $nonpegawai->status == 'non PNS' ? 'selected' : '' }}>Non PNS</option>
                    <option value="PPPK" {{ $nonpegawai->status == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                    <option value="PPNPN" {{ $nonpegawai->status == 'PPNPN' ? 'selected' : '' }}>PPNPN</option>
                </select>


                 </div>
                </div>

                {{-- golongan --}}
                <div class="row mb-3">
                 <div class="col-md-3">
                  <label for="InputGolongan" class="form-label">Golongan</label>
                 </div>
                 <div class="col-md-9">
                 <select name="golongan" id="InputGolongan" class="form-select text-muted" >
                      <option value="-" {{ $nonpegawai->golongan == '-' ? 'selected' : '' }}>-</option>
                      <option value="II/a" {{ $nonpegawai->golongan == 'II/a' ? 'selected' : '' }}>II/a</option>
                      <option value="II/b" {{ $nonpegawai->golongan == 'II/b' ? 'selected' : '' }}>II/b</option>
                      <option value="II/c" {{ $nonpegawai->golongan == 'II/c' ? 'selected' : '' }}>II/c</option>
                      <option value="II/d" {{ $nonpegawai->golongan == 'II/d' ? 'selected' : '' }}>II/d</option>
                      <option value="III/a" {{ $nonpegawai->golongan == 'III/a' ? 'selected' : '' }}>III/a</option>
                      <option value="III/b" {{ $nonpegawai->golongan == 'III/b' ? 'selected' : '' }}>III/b</option>
                      <option value="III/c" {{ $nonpegawai->golongan == 'III/c' ? 'selected' : '' }}>III/c</option>
                      <option value="III/d" {{ $nonpegawai->golongan == 'III/d' ? 'selected' : '' }}>III/d</option>
                      <option value="IV/a" {{ $nonpegawai->golongan == 'IV/a' ? 'selected' : '' }}>IV/a</option>
                      <option value="IV/b" {{ $nonpegawai->golongan == 'IV/b' ? 'selected' : '' }}>IV/b</option>
                      <option value="IV/c" {{ $nonpegawai->golongan == 'IV/c' ? 'selected' : '' }}>IV/c</option>
                      <option value="IV/d" {{ $nonpegawai->golongan == 'IV/d' ? 'selected' : '' }}>IV/d</option>
                      <option value="IV/e" {{ $nonpegawai->golongan == 'IV/e' ? 'selected' : '' }}>IV/e</option>
                </select>
                 </div>
               </div>
               
               {{-- pangkat --}}
               <div class="row mb-3">
                <div class="col-md-3">
                 <label for="InputPangkat" class="form-label">Pangkat</label>
                </div>
                <div class="col-md-9">
                <select name="pangkat" id="InputPangkat" class="form-select text-muted">
                    <option value="-" {{ $nonpegawai->pangkat == '-' ? 'selected' : '' }}>-</option>
                    <option value="Pengatur Muda" {{ $nonpegawai->pangkat == 'Pengatur Muda' ? 'selected' : '' }}>II/a - Pengatur Muda</option>
                    <option value="Pengatur Muda Tingkat 1" {{ $nonpegawai->pangkat == 'Pengatur Muda Tingkat 1' ? 'selected' : '' }}>II/b - Pengatur Muda Tingkat 1</option>
                    <option value="Pengatur" {{ $nonpegawai->pangkat == 'Pengatur' ? 'selected' : '' }}>II/c - Pengatur</option>
                    <option value="Pengatur Tingkat 1" {{ $nonpegawai->pangkat == 'Pengatur Tingkat 1' ? 'selected' : '' }}>II/d - Pengatur Tingkat 1</option>
                    <option value="Penata Muda" {{ $nonpegawai->pangkat == 'Penata Muda' ? 'selected' : '' }}>III/a - Penata Muda</option>
                    <option value="Penata Muda Tingkat I" {{ $nonpegawai->pangkat == 'Penata Muda Tingkat I' ? 'selected' : '' }}>III/b - Penata Muda Tingkat I</option>
                    <option value="Penata" {{ $nonpegawai->pangkat == 'Penata' ? 'selected' : '' }}>III/c - Penata</option>
                    <option value="Penata Tingkat 1" {{ $nonpegawai->pangkat == 'Penata Tingkat 1' ? 'selected' : '' }}>III/d - Penata Tingkat 1</option>
                    <option value="Pembina" {{ $nonpegawai->pangkat == 'Pembina' ? 'selected' : '' }}>IV/a - Pembina</option>
                    <option value="Pembina Tingkat 1" {{ $nonpegawai->pangkat == 'Pembina Tingkat 1' ? 'selected' : '' }}>IV/b - Pembina Tingkat 1</option>
                    <option value="Pembina Utama Muda" {{ $nonpegawai->pangkat == 'Pembina Utama Muda' ? 'selected' : '' }}>IV/c - Pembina Utama Muda</option>
                    <option value="Pembina Utama Madya" {{ $nonpegawai->pangkat == 'Pembina Utama Madya' ? 'selected' : '' }}>IV/d - Pembina Utama Madya</option>
                    <option value="Pembina Utama" {{ $nonpegawai->pangkat == 'Pembina Utama' ? 'selected' : '' }}>IV/e - Pembina Utama</option>
                </select>
                </div>
               </div>

               {{-- alamat --}}
                  <div class="row mb-3">
                    <div class="col-md-3">
                      <label for="InputAlamat" class="form-label">Alamat</label>
                    </div>
                    <div class="col-md-9">
                      <input type="text" class="form-control" id="InputAlamat" value="{{ $nonpegawai->alamat }}" name="alamat">
                    </div>
                  </div>

                  {{-- email --}}
                  <div class="row mb-3">
                    <div class="col-md-3">
                      <label for="InputEmail" class="form-label">Email</label>
                    </div>
                    <div class="col-md-9">
                      <input type="text" class="form-control" id="InputEmail" value="{{ $nonpegawai->email }}" name="email">
                    </div>
                  </div>

                  {{-- no_telp --}}
                  <div class="row mb-3">
                    <div class="col-md-3">
                      <label for="InputNoTelp" class="form-label">No. Telp</label>
                    </div>
                    <div class="col-md-9">
                      <input type="text" class="form-control" id="InputNoTelp" value="{{ $nonpegawai->no_telp }}" name="no_telp">
                    </div>
                  </div>


              {{-- NPWP --}}
               <div class="row mb-3">
                <div class="col-md-3">
                 <label for="InputNPWP" class="form-label">NPWP</label>
                </div>
                <div class="col-md-9">
                 <input type="text" class="form-control" id="InputNPWP" value="{{ $nonpegawai->npwp }}" name="NPWP">
                </div>
               </div>

               {{-- pokja --}}
              <div class="row mb-3">
                <div class="col-md-3">
                <label for="InputPokja" class="form-label">Bank</label>
                </div>
                <div class="col-md-9">
                <select name="bank" class="form-select" id="floatingSelect" aria-label="Floating label select example">
                    <!-- Tambahkan opsi default "Pilih Bank" -->
                    <option value="" readonly {{ !$banks->contains('kode_bank', $nonpegawai->bank) ? 'selected' : '' }}>Pilih Bank</option>
                    @foreach ($banks as $bank)
                        <option value="{{ $bank->kode_bank }}" {{ $nonpegawai->bank == $bank->kode_bank ? 'selected' : '' }}>
                            {{ $bank->kode_bank }} ({{ $bank->nama_bank }})
                        </option>
                    @endforeach
                </select>

                </div>
              </div>

               {{-- no rekening --}}
               <div class="row mb-3">
                <div class="col-md-3">
                 <label for="InputRekening" class="form-label">No Rekening</label>
                </div>
                <div class="col-md-9">
                 <input type="text" class="form-control" id="InputRekening" value="{{ $nonpegawai->no_rekening }}" name="no_rekening">
                </div>
               </div>

               {{-- Nama Rekening --}}
               <div class="row mb-3">
                <div class="col-md-3">
                 <label for="InputNamaRekening" class="form-label">Nama Rekening</label>
                </div>
                <div class="col-md-9">
                 <input type="text" class="form-control" id="InputNamaRekening" value="{{ $nonpegawai->nama_rekening }}" name="nama_rekening">
                </div>
               </div>

               {{-- status aktif --}}
               <div class="row mb-3">
                <div class="col-md-3">
                 <label for="InputIsAktif" class="form-label">Status Aktif</label>
                </div>
                <div class="col-md-9">
                  <select name="is_aktif" class="form-select text-muted" id="InputStatusAktif">
                      <option value="1" {{ $nonpegawai->is_aktif == 1 ? 'selected' : '' }}>Aktif</option>
                      <option value="0" {{ $nonpegawai->is_aktif == 0 ? 'selected' : '' }}>Nonaktif</option>
                  </select>

                </div>
               </div>

                  

                </div>
              </div>

              <div class="d-grid gap-2 d-md-flex justify-content-md-end mx-4">
                <a class="btn btn-secondary" href="{{url('/admin-nonpegawai')}}" role="button">Kembali</a>
                <button type="submit" class="btn btn-primary">Update</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection