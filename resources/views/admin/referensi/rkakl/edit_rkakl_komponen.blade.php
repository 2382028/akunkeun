@extends('admin.templates.sidebar')

@section('contain')

<main class="content" style="background: #D9D9D9;">
  <div class="container-fluid page_content page_2">
    <div class="row">
      <div class="col-md-12 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <h5 class="fw-bold">Update Data Komponen</h5>
            </div>
            <form action="{{ route('admin-rkakl_komponen.update', $rkaklkomponen->id) }}" method="POST">
              @csrf
              @method('PUT')
              {{-- start form --}}
              <div class="modal-body col-md-12">
                {{-- kode induk --}}
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="InputKodeSuboutput" class="form-label">Kode Induk</label>
                  </div>
                  <div class="col-md-8">
                    <select name="id_suboutput" class="form-select text-muted" id="InputKodeSuboutput">
                      @foreach ($rkaklsuboutputs as $rkaklsuboutput)
                      <option value="{{$rkaklsuboutput->id}}" {{ $rkaklsuboutput->id == $rkaklkomponen->ref_rkakl_suboutput_id ? 'selected' : '' }}>[{{$rkaklsuboutput->getSatker->kode_satker}}.{{$rkaklsuboutput->getProgram->kode_program}}.{{$rkaklsuboutput->getKegiatan->kode_kegiatan }}.{{$rkaklsuboutput->getOutput->kode_output }}.{{$rkaklsuboutput->kode_sub_output }}] {{$rkaklsuboutput->nama_sub_output}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                {{-- kode komponen --}}
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="InputKodeKomponen" class="form-label">Kode Komponen</label>
                  </div>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="InputKodeKomponen" value="{{ $rkaklkomponen->kode_komponen }}" name="kode_komponen">
                  </div>
                </div>

                {{-- nama komponen --}}
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="InputNamaKomponen" class="form-label">Nama Komponen</label>
                  </div>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="InputNamaKomponen" value="{{ $rkaklkomponen->nama_komponen }}" name="nama_komponen">
                  </div>
                </div>
              </div>
              {{-- start button --}}
              <div class="modal-footer">
                <a class="btn btn-secondary" href="{{url('/admin-rkakl_komponen')}}" role="button">Kembali</a>
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