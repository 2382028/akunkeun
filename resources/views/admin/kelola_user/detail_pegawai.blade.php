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
                 <label for="InputPassword" class="form-label">Password</label>
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
                      <option selected>{{ $pegawai->jenis_kelamin }}</option>
                      <option value="Laki-laki">Laki-Laki</option>
                      <option value="Perempuan">Perempuan</option>
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
                    <option selected>{{ $pegawai->status }}</option>
                    <option value="PNS">PNS</option>
                    <option value="non PNS">Non PNS</option>
                    <option value="non PNS">PPPK</option>
                </select>
                 </div>
                </div>

                {{-- golongan --}}
                <div class="row mb-3">
                 <div class="col-md-3">
                  <label for="InputGolongan" class="form-label">Golongan</label>
                 </div>
                 <div class="col-md-9">
                  <input type="text" class="form-control" id="InputGolongan" value="{{ $pegawai->golongan }}" name="golongan">
                 </div>
               </div>
               
               {{-- pangkat --}}
               <div class="row mb-3">
                <div class="col-md-3">
                 <label for="InputPangkat" class="form-label">Pangkat</label>
                </div>
                <div class="col-md-9">
                 <input type="text" class="form-control" id="InputPangkat" value="{{ $pegawai->pangkat }}" name="pangkat">
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
                    <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
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
                    <option value="{{ $fungsi->id }}">{{ $fungsi->nama_fungsi }}</option>
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

               {{-- status aktif --}}
               <div class="row mb-3">
                <div class="col-md-3">
                 <label for="InputIsAktif" class="form-label">Status Aktif</label>
                </div>
                <div class="col-md-9">
                  <select name="is_aktif" class="form-select text-muted" id="InputStatusAktif">
                    <option selected>{{ $pegawai->is_aktif }}</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
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
