@extends('admin.templates.sidebar')

@section('contain')

<main class="content" style="background: #D9D9D9;">
  <div class="container-fluid page_content page_2">
    <div class="row">
      <div class="col-md-12 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <h5 class="fw-bold">Update data Program</h5>
            </div>
            <form action="{{ route('admin-rkakl_program.update',  $rkaklprograms[0]->id) }}" method="POST">
              @csrf
              @method('PUT')
              {{-- start form --}}
              <div class="modal-body col-md-12">
                {{-- kode satker --}}
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="InputKodeSatker" class="form-label">Kode Induk</label>
                  </div>
                  <div class="col-md-8">
                    <select name="kode_satker" class="form-select" id="InputKodeSatker" aria-label="Floating label select example">
                      @foreach ($rkaklsatkers as $rkaklsatker)
                      <option value="{{ $rkaklsatker->id }}">{{ $rkaklsatker->satker }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                {{-- kode program --}}
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="InputKodeProgram" class="form-label">Kode Program</label>
                  </div>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="InputKodeProgram" value="{{ $rkaklprograms[0]->kode_program }}" name="kode_program">
                  </div>
                </div>

                {{-- nama program --}}
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="InputNamaProgram" class="form-label">Nama Program</label>
                  </div>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="InputNamaProgram" value="{{ $rkaklprograms[0]->program }}" name="program">
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