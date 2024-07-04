@extends('admin.templates.sidebar')

@section('contain')

<main class="content" style="background: #D9D9D9;">
  <div class="container-fluid page_content page_2">
    <div class="row">
      <div class="col-md-12 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <h5 class="fw-bold">Update Data Akun</h5>
            </div>
            <form action="{{ route('admin-akun_x_rkakl.update',  $akunxrkakl->id) }}" method="POST">
              @csrf
              @method('PUT')
              {{-- start form --}}
              <div class="modal-body col-md-12">
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="kodeRkakl" class="form-label">Kode Induk</label>
                  </div>
                  <div class="col-md-8">
                    <select name="ref_sub_komponen_id" class="form-select text-muted" id="kodeRkakl">
                      @foreach ($rkaklsubkomponens as $rkaklsubkomponen)
                      <option value="{{$rkaklsubkomponen->id}}" {{ $rkaklsubkomponen->id == $akunxrkakl->ref_sub_komponen_id ? 'selected' : '' }}>[{{ $rkaklsubkomponen->getSatker->kode_satker }}.{{ $rkaklsubkomponen->getProgram->kode_program }}.{{ $rkaklsubkomponen->getKegiatan->kode_kegiatan }}.{{ $rkaklsubkomponen->getOutput->kode_output }}.{{ $rkaklsubkomponen->getSuboutput->kode_sub_output }}.{{ $rkaklsubkomponen->getKomponen->kode_komponen }}.{{ $rkaklsubkomponen->kode_sub_kegiatan }}] {{ $rkaklsubkomponen->nama_sub_kegiatan }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="" class="form-label">Kode Akun</label>
                  </div>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="" value="{{ $akunxrkakl->getAkun->kode_akun }}" name="kode_akun">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="" class="form-label">Nama Akun</label>
                  </div>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="" value="{{$akunxrkakl->getAkun->uraian }}" name="uraian">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="" class="form-label">Nominal</label>
                  </div>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="" value="{{$akunxrkakl->getAkun->nominal }}" name="nominal">
                  </div>
                </div>
              </div>
              {{-- start button --}}
              <div class="modal-footer">
                <a class="btn btn-secondary" href="{{url('/admin-akun_x_rkakl')}}" role="button">Kembali</a>
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