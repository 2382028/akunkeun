@extends('admin.templates.sidebar')

@section('contain')

<main class="pt-3 content" style="background: #D9D9D9;">
    <div class="container-fluid page_content page_2">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h5 class="fw-bold">Informasi Detail Administrator</h5>
                        </div>
                        <form action="{{ route('admin.update',  $admin->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            {{-- start form --}}
                            <div class="modal-body col-md-12">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="InputUsername" class="form-label">Username</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="InputUsername" value="{{ $admin->username }}" name="username">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="InputEmail" class="form-label">Email</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="InputEmail" value="{{ $admin->email }}" name="email">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="InputPassword" class="form-label">Password</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="InputPassword" placeholder="Masukkan Password Baru" name="password">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="InputRole" class="form-label">Role</label>
                                    </div>
                                    <div class="col-md-8">
                                        <select name="role" class="form-select text-muted" id="InputRole">
                                            <option selected>-</option>
                                            <option value="Master">Master</option>
                                            <option value="Keuangan">Keuangan</option>
                                            <option value="Bendahara">Bendahara</option>
                                            <option value="BMN">BMN</option>
                                            <option value="HKT">HKT</option>
                                        </select>
                                        <!-- <input type="text" class="form-control" id="InputRole" value="{{ $admin->role }}" name="role"> -->
                                    </div>
                                </div>
                            </div>
                            {{-- start button --}}
                            <div class="modal-footer">
                                <a class="btn btn-secondary" href="{{url('/admin')}}" role="button">Kembali</a>
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