@extends('admin.templates.sidebar')

@section('contain')

<main class="content" style="background: #D9D9D9;">
  <div class="container-fluid page_content page_2">
    <div class="row">
      <div class="col-md-12 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <h5 class="fw-bold">Update Data Suboutput</h5>
            </div>
            <form action="{{ route('admin-rkakl_suboutput.update', $rkaklsuboutput->id) }}" method="POST">
              @csrf
              @method('PUT')
              {{-- start form --}}
              <div class="modal-body col-md-12">
                {{-- kode induk --}}
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="InputKodeOutput" class="form-label">Kode Induk</label>
                  </div>
                  <div class="col-md-8">
                    <select name="id_output" class="form-select text-muted" id="InputKodeOutput">
                      @foreach ($rkakloutputs as $rkakloutput)
                      <option value="{{$rkakloutput->id}}" {{ $rkakloutput->id == $rkaklsuboutput->ref_rkakl_output_id ? 'selected' : '' }}>[{{$rkakloutput->getSatker->kode_satker}}.{{$rkakloutput->getProgram->kode_program}}.{{$rkakloutput->getKegiatan->kode_kegiatan }}.{{$rkakloutput->kode_output }}]{{$rkakloutput->nama_output}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                {{-- kode sub --}}
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="InputKodeSuboutput" class="form-label">Kode Suboutput</label>
                  </div>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="InputKodeSuboutput" value="{{ $rkaklsuboutput->kode_sub_output }}" name="kode_sub_output">
                  </div>
                </div>

                {{-- nama kegiatan --}}
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="InputNamaSuboutput" class="form-label">Nama Suboutput</label>
                  </div>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="InputNamaSuboutput" value="{{ $rkaklsuboutput->nama_sub_output }}" name="nama_sub_output">
                  </div>
                </div>
              </div>
              {{-- start button --}}
              <div class="modal-footer">
                <a class="btn btn-secondary" href="{{url('/admin-rkakl_suboutput')}}" role="button">Kembali</a>
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