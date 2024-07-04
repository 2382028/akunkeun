@extends('admin.templates.sidebar')

@section('contain')

<!-- Awal Dashboard Administrator -->
<main class="pt-3 content" style="background: #D9D9D9;">
  <div class="container-fluid page_content page_1">
    <div class="row">
      <div class="col-md-12">
        <h4>Kelola User / <span class="fw-bold">Administrator<span></h4>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="row ">
              <div class="table-responsive">
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambah_administrator">
                        <i href="{{ route('admin.create') }}" class="fa fa-plus"></i> Tambah
                    </button>        
                </div>
                <hr>
              <table id="example" class="table table-bordered data-table" style="width: 100%">
                <thead>
                  <tr class="text-center small">
                    <th class="th-lg-percent">No</th>
                    <th class="th-md">Nama</th>
                    <th class="th-md">Role</th>
                    <th class="th-lg-percent">Aksi</th>
                  </tr>
                </thead>
                @foreach ($admins as $admin)
                  <tr>
                      <td class='text-center'>{{ $loop->iteration }}</td>
                      <td class='text-center'>{{ $admin->username }}</td>
                      <td class='text-center'>{{ $admin->role }}</td>
                      <td class='text-center'>
                        <div class="btn-group mx-auto gap-2">
                          <span class="p-1">
                            <a href="{{ route('admin.edit', $admin->id) }}" class="btn  btn-primary"><i class="fa-regular fa-file-lines"></i></a>
                          </span>
                          <span class="p-1">
                            <form action="{{ route('admin.destroy', $admin->id) }}" method="post">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="text-decoration-none btn btn-danger " onclick="return confirm('Apakah Anda yakin akan menghapus data ini?')"><i class="fa-solid fa-trash"></i></button>
                            </form>
                          </span>
                        </div>
                      </td>
                  </tr>
                  @endforeach
                </table>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<!-- Akhir Dashboard Administrator -->

<!-- Awal Modal Tambah Administrator -->
<div class="modal fade" id="tambah_administrator" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {{-- start form --}}
        <form action="{{ route('admin.store') }}" method="POST">
          @csrf
          <div class="modal-body col-md-12">
              <div class="row mb-3">
                  <div class="col-md-4">
                      <label for="InputUsername" class="form-label">Username</label>
                  </div>
                  <div class="col-md-8">
                      <input name="username" type="text" class="form-control" id="InputUsername">
                  </div>
              </div>
              <div class="row mb-3">
                  <div class="col-md-4">
                      <label for="InputEmail" class="form-label">Email</label>
                  </div>
                  <div class="col-md-8">
                      <input name="email" type="text" class="form-control" id="InputEmail">
                  </div>
              </div>
              <div class="row mb-3">
                  <div class="col-md-4">
                      <label for="InputPassword" class="form-label">Password</label>
                  </div>
                  <div class="col-md-8">
                      <input name="password" type="password" name="password" class="form-control" id="InputPassword">
                  </div>
              </div>
              <div class="row mb-3">
                  <div class="col-md-4">
                      <label for="InputRole" class="form-label">Role</label>
                  </div>
                  <div class="col-md-8">
                      <select name="role" class="form-select text-muted" id="InputRole">
                        <option selected>Pilih Role</option>
                        <option value="Master">Master Admin</option>
                        <option value="Bendahara">Bendahara</option>
                        <option value="Keuangan">Keuangan</option>
                        <option value="BMN">BMN</option>
                        <option value="HKT">HKT</option>
                      </select>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" onclick="" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
</div>
<!-- Akhir Modal Tambah Administrator -->

          {{-- start button --}}
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
</div>
<!-- Akhir Modal Edit Administrator -->
    
@endsection