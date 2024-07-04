@extends('admin.templates.sidebar')

@section('contain')

<main class="content" style="background: #D9D9D9;">
  <div class="container-fluid page_content page_2">
    <div class="row">
      <div class="col-md-12 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <h5 class="fw-bold">Update Data Output</h5>
            </div>
            <form action="{{ route('admin-rkakl_output.update', $rkakloutput->id) }}" method="POST">
              @csrf
              @method('PUT')
              {{-- start form --}}
              <div class="modal-body col-md-12">
                {{-- kode induk --}}
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="InputKodeKegiatan" class="form-label">Kode Induk</label>
                  </div>
                  <div class="col-md-8">
                    <select name="id_kegiatan" class="form-select text-muted" id="kodeKegiatan">
                      @foreach ($rkaklkegiatans as $rkaklkegiatan)
                      <option value="{{$rkaklkegiatan->id}}" {{ $rkaklkegiatan->id == $rkakloutput->ref_rkakl_kegiatan_id ? 'selected' : '' }}>[{{$rkaklkegiatan->getSatker->kode_satker}}.{{$rkaklkegiatan->getProgram->kode_program}}.{{$rkaklkegiatan->kode_kegiatan }}]{{$rkaklkegiatan->nama_kegiatan}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                {{-- kode kegiatan --}}
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="InputKodeOutput" class="form-label">Kode Output</label>
                  </div>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="InputKodeOutput" value="{{ $rkakloutput->kode_output }}" name="kode_output">
                  </div>
                </div>

                {{-- nama kegiatan --}}
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="InputNamaOutput" class="form-label">Nama Output</label>
                  </div>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="InputNamaOutput" value="{{ $rkakloutput->nama_output }}" name="nama_output">
                  </div>
                </div>
              </div>
              {{-- start button --}}
              <div class="modal-footer">
                <a class="btn btn-secondary" href="{{url('/admin-rkakl_output')}}" role="button">Kembali</a>
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