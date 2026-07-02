@extends('admin.templates.sidebar')

@section('contain')

<main class="pt-3 content" style="background: #D9D9D9;">
 <div class="container-fluid page_content page_2">

  <div class="row">
    <div class="col-md-12">
      <h4>Kelola User / <span class="fw-bold">Pegawai</span> / <span class="fw-bold">Detail</span></h4>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12 mb-3">
      <div class="card">
        <div class="card-body">

          <div class="row mb-3">
              <h5>Informasi Detail Pegawai</h5>
          </div>

          <form action="{{ route('admin-pegawai.update',  $pegawai->id) }}" method="POST">
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
                  <input type="text" class="form-control" id="InputNIPNIK" value="{{ $pegawai->NIP_NIK }}" name="NIP_NIK">
                 </div>
               </div>

                {{-- nama lengkap --}}
               <div class="row mb-3">
                <div class="col-md-3">
                 <label for="InputNamaLengkap" class="form-label">Nama Lengkap</label>
                </div>
                <div class="col-md-9">
                 <input type="text" class="form-control" id="InputNamaLengkap" value="{{ $pegawai->nama_lengkap }}" name="nama_lengkap">
                </div>
               </div>

               {{-- password --}}
               <div class="row mb-3">
                <div class="col-md-3">
                  <label for="InputPassword" class="form-label">
                      Password Baru 
                      <small class="text-muted">(Hanya Jika Ganti Password)</small>
                  </label>
                </div>
                <div class="col-md-9">
                 <input type="password" class="form-control" id="InputPassword" name="password">
                </div>
               </div>

                 {{-- jenis kelamin --}}
                 <div class="row mb-3">
                  <div class="col-md-3">
                   <label for="InputJenisKelamin" class="form-label">Jenis Kelamin</label>
                  </div>
                  <div class="col-md-9">
                  <select name="jenis_kelamin" class="form-select text-muted" id="InputStatus">
                      <option value="Laki-laki" {{ $pegawai->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-Laki</option>
                      <option value="Perempuan" {{ $pegawai->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
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
                    <option value="-" {{ $pegawai->status == '-' ? 'selected' : '' }}>-</option>
                    <option value="PNS" {{ $pegawai->status == 'PNS' ? 'selected' : '' }}>PNS</option>
                    <option value="non PNS" {{ $pegawai->status == 'non PNS' ? 'selected' : '' }}>Non PNS</option>
                    <option value="PPPK" {{ $pegawai->status == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                    <option value="PPNPN" {{ $pegawai->status == 'PPNPN' ? 'selected' : '' }}>PPNPN</option>
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
                    @foreach ($pangkats as $pangkat)
                        @if ($pangkat->golongan == "-")
                          <option value="-" {{ $pegawai->golongan == '-' ? 'selected' : '' }}>-</option>
                        @endif
                      @endforeach
                      @foreach ($pangkats as $pangkat)
                        @if ($pangkat->golongan != "-")
                          <option value="{{ $pangkat->golongan }}" {{ $pegawai->golongan == $pangkat->golongan ? 'selected' : '' }}>
                            {{ $pangkat->golongan }}
                          </option>
                        @endif
                      @endforeach
                      <!-- <option value="-" {{ $pegawai->golongan == '-' ? 'selected' : '' }}>-</option>
                      <option value="II/a" {{ $pegawai->golongan == 'II/a' ? 'selected' : '' }}>II/a</option>
                      <option value="II/b" {{ $pegawai->golongan == 'II/b' ? 'selected' : '' }}>II/b</option>
                      <option value="II/c" {{ $pegawai->golongan == 'II/c' ? 'selected' : '' }}>II/c</option>
                      <option value="II/d" {{ $pegawai->golongan == 'II/d' ? 'selected' : '' }}>II/d</option>
                      <option value="III/a" {{ $pegawai->golongan == 'III/a' ? 'selected' : '' }}>III/a</option>
                      <option value="III/b" {{ $pegawai->golongan == 'III/b' ? 'selected' : '' }}>III/b</option>
                      <option value="III/c" {{ $pegawai->golongan == 'III/c' ? 'selected' : '' }}>III/c</option>
                      <option value="III/d" {{ $pegawai->golongan == 'III/d' ? 'selected' : '' }}>III/d</option>
                      <option value="IV/a" {{ $pegawai->golongan == 'IV/a' ? 'selected' : '' }}>IV/a</option>
                      <option value="IV/b" {{ $pegawai->golongan == 'IV/b' ? 'selected' : '' }}>IV/b</option>
                      <option value="IV/c" {{ $pegawai->golongan == 'IV/c' ? 'selected' : '' }}>IV/c</option>
                      <option value="IV/d" {{ $pegawai->golongan == 'IV/d' ? 'selected' : '' }}>IV/d</option>
                      <option value="IV/e" {{ $pegawai->golongan == 'IV/e' ? 'selected' : '' }}>IV/e</option> -->
                </select>
                 </div>
               </div>
               
               {{-- pangkat --}}
               <div class="row mb-3">
                <div class="col-md-3">
                 <label for="InputPangkat" class="form-label">Pangkat</label>
                </div>
                <div class="col-md-9">
                <select name="pangkat" id="InputPangkat" class="form-select text-muted" style="width: 100%;">
                  @foreach ($pangkats as $pangkat)
                    @if ($pangkat->nama_pangkat == "-")
                      <option value="-" {{ $pegawai->pangkat == '-' ? 'selected' : '' }}>-</option>
                    @endif
                  @endforeach
                  @foreach ($pangkats as $pangkat)
                    @if ($pangkat->nama_pangkat != "-")
                      <option value="{{ $pangkat->nama_pangkat }}" {{ $pegawai->pangkat == $pangkat->nama_pangkat ? 'selected' : '' }}>
                        {{ $pangkat->golongan }} - {{ $pangkat->nama_pangkat }}
                      </option>
                    @endif
                  @endforeach
                </select>
                </div>
               </div>

               {{-- no telpon --}}
               <div class="row mb-3">
                <div class="col-md-3">
                 <label for="InputNoTelp" class="form-label">No. Telpon</label>
                </div>
                <div class="col-md-9">
                 <input type="text" class="form-control" id="InputNoTelp" value="{{ $pegawai->no_telp }}" name="no_telp">
                </div>
               </div>
             
               {{-- email --}}
               <div class="row mb-3">
                <div class="col-md-3">
                 <label for="InputEmail" class="form-label">Email</label>
                </div>
                <div class="col-md-9">
                 <input type="email" class="form-control" id="InputEmail" value="{{ $pegawai->email }}" name="email">
                </div>
               </div>

              {{-- jabatan --}}
               <div class="row mb-3">
                <div class="col-md-3">
                 <label for="InputJabatan" class="form-label">Jabatan</label>
                </div>
                <div class="col-md-9">
                <select id="InputJabatan" name="jabatan_id" class="form-select" id="floatingSelect" aria-label="Floating label select example">
                    @foreach ($jabatans as $jabatan)
                        <option value="{{ $jabatan->id }}" {{ $pegawai->jabatan_id == $jabatan->id ? 'selected' : '' }}>
                            {{ $jabatan->nama_jabatan }}
                        </option>
                    @endforeach
                </select>

                </div>
               </div>

              {{-- pokja --}}
              <div class="row mb-3">
                <div class="col-md-3">
                <label for="InputPokja" class="form-label">Pokja</label>
                </div>
                <div class="col-md-9">
                <select name="fungsi_id" class="form-select" id="floatingSelect" aria-label="Floating label select example">
                    @foreach ($fungsis as $fungsi)
                        <option value="{{ $fungsi->id }}" {{ $pegawai->fungsi_id == $fungsi->id ? 'selected' : '' }}>
                            {{ $fungsi->nama_fungsi }}
                        </option>
                    @endforeach
                </select>
                </div>
              </div>

              {{-- NPWP --}}
               <div class="row mb-3">
                <div class="col-md-3">
                 <label for="InputNPWP" class="form-label">NPWP</label>
                </div>
                <div class="col-md-9">
                 <input type="text" class="form-control" id="InputNPWP" value="{{ $pegawai->npwp }}" name="NPWP">
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
                    <option value="" disabled {{ !$banks->contains('kode_bank', $pegawai->bank) ? 'selected' : '' }}>Pilih Bank</option>
                    @foreach ($banks as $bank)
                        <option value="{{ $bank->kode_bank }}" {{ $pegawai->bank == $bank->kode_bank ? 'selected' : '' }}>
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
                 <input type="text" class="form-control" id="InputRekening" value="{{ $pegawai->no_rekening }}" name="no_rekening">
                </div>
               </div>

               {{-- Nama Rekening --}}
               <div class="row mb-3">
                <div class="col-md-3">
                 <label for="InputNamaRekening" class="form-label">Nama Rekening</label>
                </div>
                <div class="col-md-9">
                 <input type="text" class="form-control" id="InputNamaRekening" value="{{ $pegawai->nama_rekening }}" name="nama_rekening">
                </div>
               </div>

               {{-- status aktif --}}
               <div class="row mb-3">
                <div class="col-md-3">
                 <label for="InputIsAktif" class="form-label">Status Aktif</label>
                </div>
                <div class="col-md-9">
                  <select name="is_aktif" class="form-select text-muted" id="InputStatusAktif">
                      <option value="1" {{ $pegawai->is_aktif == 1 ? 'selected' : '' }}>Aktif</option>
                      <option value="0" {{ $pegawai->is_aktif == 0 ? 'selected' : '' }}>Nonaktif</option>
                  </select>

                </div>
               </div>

              </div>
            </div>

            <div class="modal-footer">
             <a class="btn btn-secondary" href="{{url('/admin-pegawai')}}" role="button">Kembali</a>
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
