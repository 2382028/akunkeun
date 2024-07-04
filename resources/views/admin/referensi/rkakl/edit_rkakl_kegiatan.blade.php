@extends('admin.templates.sidebar')

@section('contain')

<main class="content" style="background: #D9D9D9;">
  <div class="container-fluid page_content page_2">
    <div class="row">
      <div class="col-md-12 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <h5 class="fw-bold">Update data Kegiatan</h5>
            </div>
            <form action="{{ route('admin-rkakl_kegiatan.update',  $rkaklkegiatans[0]->id) }}" method="POST">
              @csrf
              @method('PUT')
              {{-- start form --}}
              <div class="modal-body col-md-12">
                {{-- kode induk --}}
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="InputKodeProgram" class="form-label">Kode Induk</label>
                  </div>
                  <div class="col-md-8">
                    <select name="id_program" class="form-select text-muted" id="kodeProgram">
                      @foreach ($rkaklprograms as $rkaklprogram)
                      <option value="{{$rkaklprogram->id}}" {{ $rkaklprogram->id == $rkaklkegiatans[0]->ref_rkakl_program_id ? 'selected' : '' }}>[{{$rkaklsatkers[0]->kode_satker}}.{{$rkaklprogram->kode_program}}] {{$rkaklprogram->program}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                {{-- kode kegiatan --}}
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="InputKodeKegiatan" class="form-label">Kode Kegiatan</label>
                  </div>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="InputKodeKegiatan" value="{{ $rkaklkegiatans[0]->kode_kegiatan }}" name="kode_kegiatan">
                  </div>
                </div>

                {{-- nama kegiatan --}}
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="InputNamaKegiatan" class="form-label">Nama Program</label>
                  </div>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="InputNamaKegiatan" value="{{ $rkaklkegiatans[0]->nama_kegiatan }}" name="nama_kegiatan">
                  </div>
                </div>
              </div>
              {{-- start button --}}
              <div class="modal-footer">
                <a class="btn btn-secondary" href="{{url('/admin-rkakl_program')}}" role="button">Kembali</a>
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