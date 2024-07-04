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
            <form action="{{ route('admin-rkakl_akun.update',  $akun->id) }}" method="POST">
              @csrf
              @method('PUT')
              {{-- start form --}}
              <div class="modal-body col-md-12">
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="KodeAkun" class="form-label">Kode Satker</label>
                  </div>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="KodeAkun" value="{{ $akun->kode_akun }}" name="kode_akun">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="NamaAkun" class="form-label">Nama Akun</label>
                  </div>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="NamaAkun" value="{{ $akun->uraian }}" name="uraian">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="Nominal" class="form-label">Nominal</label>
                  </div>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="Nominal" value="{{ $akun->nominal }}" name="nominal">
                  </div>
                </div>
              </div>
              {{-- start button --}}
              <div class="modal-footer">
                <a class="btn btn-secondary" href="{{url('/admin-rkakl_akun')}}" role="button">Kembali</a>
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