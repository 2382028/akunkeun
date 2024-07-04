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
            <form action="{{ route('admin-rkakl_subkomponen.update', $rkaklsubkomponen->id) }}" method="POST">
              @csrf
              @method('PUT')
              {{-- start form --}}
              <div class="modal-body col-md-12">
                {{-- kode induk --}}
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="InputKodeKomponen" class="form-label">Kode Induk</label>
                  </div>
                  <div class="col-md-8">
                    <select name="id_komponen" class="form-select text-muted" id="InputKodeKomponen">
                      @foreach ($rkaklkomponens as $rkaklkomponen)
                      <option value="{{$rkaklkomponen->id}}" {{ $rkaklkomponen->id == $rkaklsubkomponen->ref_rkakl_komponen_id ? 'selected' : '' }}>[{{$rkaklkomponen->getSatker->kode_satker}}.{{$rkaklkomponen->getProgram->kode_program}}.{{$rkaklkomponen->getKegiatan->kode_kegiatan }}.{{$rkaklkomponen->getOutput->kode_output }}.{{$rkaklkomponen->getSuboutput->kode_sub_output }}.{{$rkaklkomponen->kode_komponen }}] {{$rkaklkomponen->nama_komponen}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                {{-- kode subkomponen --}}
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="InputKodeSubkomponen" class="form-label">Kode Subkomponen</label>
                  </div>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="InputKodeSubkomponen" value="{{ $rkaklsubkomponen->kode_sub_kegiatan }}" name="kode_sub_kegiatan">
                  </div>
                </div>

                {{-- nama komponen --}}
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="InputNamaSubkomponen" class="form-label">Nama Subkomponen</label>
                  </div>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="InputNamaSubkomponen" value="{{ $rkaklsubkomponen->nama_sub_kegiatan }}" name="nama_sub_kegiatan">
                  </div>
                </div>
              </div>
              {{-- start button --}}
              <div class="modal-footer">
                <a class="btn btn-secondary" href="{{url('/admin-rkakl_subkomponen')}}" role="button">Kembali</a>
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