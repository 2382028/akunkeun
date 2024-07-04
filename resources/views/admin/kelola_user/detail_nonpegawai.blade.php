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

                  {{-- status --}}
                  <div class="row mb-3">
                    <div class="col-md-3">
                      <label for="InputStatus" class="form-label">Status</label>
                    </div>
                    <div class="col-md-9">
                      <select name="status" class="form-select text-muted" id="InputStatus">
                        <option selected>{{ $nonpegawai->status }}</option>
                        <option value="PNS">PNS</option>
                        <option value="Non PNS">Non PNS</option>
                      </select>
                    </div>
                  </div>

                  {{-- golongan --}}
                  <div class="row mb-3">
                    <div class="col-md-3">
                      <label for="InputGolongan" class="form-label">Golongan</label>
                    </div>
                    <div class="col-md-9">
                      <input type="text" class="form-control" id="InputGolongan" value="{{ $nonpegawai->golongan }}" name="golongan">
                    </div>
                  </div>

                  {{-- pangkat --}}
                  <div class="row mb-3">
                    <div class="col-md-3">
                      <label for="InputPangkat" class="form-label">Pangkat</label>
                    </div>
                    <div class="col-md-9">
                      <input type="text" class="form-control" id="InputPangkat" value="{{ $nonpegawai->pangkat }}" name="pangkat">
                    </div>
                  </div>

                  {{-- alamat --}}
                  <div class="row mb-3">
                    <div class="col-md-3">
                      <label for="InputAlamat" class="form-label">Alamat</label>
                    </div>
                    <div class="col-md-9">
                      <input type="text" class="form-control" id="InputAlamat" value="{{ $nonpegawai->alamat }}" name="pangkat">
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