@extends('admin.templates.sidebar')

@section('contain')
    @php $tab = request('tab', 'akun'); @endphp

    <main class="pt-3 content" style="background: #D9D9D9;">
        <div class="container-fluid page_content page_1">
            <div class="row">
                <div class="col-md-12">
                    <h4>Kelola User / <span class="fw-bold">Administrator</span></h4>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="btn-group">
                        <div class="wrapper page mb-3">
                            <a href="?tab=akun"
                                class="page-wrap text-decoration-none btn btn-warning btn-sm text-white {{ $tab === 'akun' ? 'page-wrap-active' : '' }}">
                                Akun Administrator
                            </a>
                            <a href="?tab=role"
                                class="page-wrap text-decoration-none btn btn-warning btn-sm text-white {{ $tab === 'role' ? 'page-wrap-active' : '' }}">
                                Role
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">

                    @if ($tab === 'akun')
                        {{-- Tabel Akun Administrator --}}
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                        data-bs-target="#tambah_administrator">
                                        <i class="fa fa-plus"></i> Tambah
                                    </button>
                                    <table id="example" class="table table-bordered data-table" style="width: 100%">
                                        <thead>
                                            <tr class="text-center small">
                                                <th>No</th>
                                                <th>Nama Lengkap</th>
                                                <th>Role</th>
                                                <th>Role Tambahan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($admins as $admin)
                                                <tr>
                                                    <td class='text-center'>{{ $loop->iteration }}</td>
                                                    <td>{{ $admin->username }}</td>
                                                    <td class='text-center'>{{ $admin->role }}</td>
                                                    <td class='text-center'>
                                                        {{-- Ambil nama role tambahan dan pisahkan dengan koma --}}
                                                        {{ $admin->roles->isNotEmpty() ? $admin->roles->pluck('nama_role')->join(', ') : '-' }}

                                                    </td>
                                                    <td class='text-center'>
                                                        <div class="btn-group mx-auto gap-2">
                                                            <button class="btn btn-primary btn-edit-admin"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editAdministratorModal"
                                                                data-id="{{ $admin->id }}"
                                                                data-username="{{ $admin->username }}"
                                                                data-email="{{ $admin->email }}"
                                                                data-role="{{ $admin->role }}"
                                                                data-roles="{{ $admin->roles->pluck('id')->implode(',') }}">
                                                                <i class="fa-solid fa-pen"></i>

                                                            </button>
                                                            <form action="{{ route('admin.destroy', $admin->id) }}"
                                                                method="post"
                                                                onsubmit="return confirm('Apakah Anda yakin akan menghapus data ini?')">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="btn btn-danger"><i
                                                                        class="fa-solid fa-trash"></i></button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($tab === 'role')
                        {{-- Tabel Role --}}
                        <div class="card">
                            <div class="card-body">
                                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_role">
                                    <i class="fa fa-plus"></i> Tambah
                                </button>
                                <div class="table-responsive">
                                    <table id="example2" class="table table-bordered data-table" style="width: 100%">
                                        <thead>
                                            <tr class="text-center small">
                                                <th>No</th>
                                                <th>Role</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (\App\Models\RefAdminRole::all() as $role)
                                                <tr>
                                                    <td class='text-center'>{{ $loop->iteration }}</td>
                                                    <td>{{ $role->nama_role }}</td>
                                                    <td class='text-center'>
                                                        <a href="#" class="btn btn-sm btn-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#edit_role_{{ $role->id }}">
                                                            <i class="fa-solid fa-pen"></i>
                                                        </a>
                                                        <form action="{{ route('ref_admin_roles.destroy', $role->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Hapus role?')">
                                                            @csrf @method('DELETE')
                                                            <button class="btn btn-sm btn-danger">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @push('modals')
                                                    <div class="modal fade" id="edit_role_{{ $role->id }}">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <form action="{{ route('ref_admin_roles.update', $role->id) }}"
                                                                method="POST" class="modal-content">
                                                                @csrf @method('PUT')
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Edit Role</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input name="nama_role" class="form-control"
                                                                        value="{{ $role->nama_role }}" required>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Batal</button>
                                                                    <button class="btn btn-primary">Simpan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endpush
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                        {{-- Modal Tambah Role --}}
                        <div class="modal fade" id="tambah_role">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="{{ route('ref_admin_roles.store') }}" method="POST" class="modal-content">
                                    @csrf
                                    <div class="modal-header">
                                        <h5>Tambah Role</h5>
                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input name="nama_role" class="form-control" placeholder="Nama Role" required>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    @stack('modals')
                </div>
            </div>
        </div>
    </main>

    {{-- Modal Tambah Administrator --}}
    <div class="modal fade" id="tambah_administrator" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form action="{{ route('admin.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <label for="InputUsername" class="col-md-4 col-form-label">Nama Lengkap</label>
                        <div class="col-md-8">
                            <input name="username" type="text" class="form-control" id="InputUsername" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="InputEmail" class="col-md-4 col-form-label">Email</label>
                        <div class="col-md-8">
                            <input name="email" type="email" class="form-control" id="InputEmail" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="InputPassword" class="col-md-4 col-form-label">Password</label>
                        <div class="col-md-8">
                            <input name="password" type="password" class="form-control" id="InputPassword" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="InputRole" class="col-md-4 col-form-label">Role</label>
                        <div class="col-md-8">
                            <select name="role" class="form-select" id="InputRole" required>
                                <option selected disabled>Pilih Role</option>
                                <option value="Master">Master Admin</option>
                                <option value="Bendahara">Bendahara</option>
                                <option value="Keuangan">Keuangan</option>
                                <option value="BMN">BMN</option>
                                <option value="HKT">HKT</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-4">Hak Akses Tambahan</label>
                        <div class="col-md-8">
                            @foreach (\App\Models\RefAdminRole::all() as $rrole)
                                <div class="form-check">
                                    <input type="checkbox" name="roles[]" value="{{ $rrole->id }}"
                                        class="form-check-input" id="role_{{ $rrole->id }}">
                                    <label class="form-check-label"
                                        for="role_{{ $rrole->id }}">{{ $rrole->nama_role }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Administrator --}}
    <div class="modal fade" id="editAdministratorModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form id="editAdminForm" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Administrator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editAdminId" name="id">

                    <div class="row mb-3">
                        <label for="editUsername" class="col-md-4 col-form-label">Username</label>
                        <div class="col-md-8">
                            <input type="text" name="username" id="editUsername" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="editEmail" class="col-md-4 col-form-label">Email</label>
                        <div class="col-md-8">
                            <input type="email" name="email" id="editEmail" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="editPassword" class="col-md-4 col-form-label">Password</label>
                        <div class="col-md-8">
                            <input type="password" name="password" id="editPassword" class="form-control"
                                placeholder="Masukkan Password Baru jika ingin mengganti">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="editRole" class="col-md-4 col-form-label">Role</label>
                        <div class="col-md-8">
                            <select name="role" id="editRole" class="form-select" required>
                                <option value="Master">Master</option>
                                <option value="Bendahara">Bendahara</option>
                                <option value="Keuangan">Keuangan</option>
                                <option value="BMN">BMN</option>
                                <option value="HKT">HKT</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4">Hak Akses Tambahan</label>
                        <div class="col-md-8" id="editAdditionalRoles">
                            @foreach (\App\Models\RefAdminRole::all() as $rrole)
                                <div class="form-check">
                                    <input type="checkbox" name="roles[]" value="{{ $rrole->id }}"
                                        class="form-check-input" id="edit_role_{{ $rrole->id }}">
                                    <label class="form-check-label"
                                        for="edit_role_{{ $rrole->id }}">{{ $rrole->nama_role }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>


@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.btn-edit-admin');
        const form = document.getElementById('editAdminForm');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const username = this.getAttribute('data-username');
                const email = this.getAttribute('data-email');
                const role = this.getAttribute('data-role');
                // Tambahkan atribut data-roles yang berisi array ID role tambahan (contoh: "1,3,5")
                const rolesStr = this.getAttribute('data-roles') || '';
                const rolesArr = rolesStr.split(',').filter(r => r !== '');

                form.action = `/admin/${id}`;
                document.getElementById('editAdminId').value = id;
                document.getElementById('editUsername').value = username;
                document.getElementById('editEmail').value = email;
                document.getElementById('editPassword').value = '';
                document.getElementById('editRole').value = role;

                // Kosongkan semua checkbox terlebih dahulu
                const checkboxes = document.querySelectorAll(
                    '#editAdditionalRoles input[type="checkbox"]');
                checkboxes.forEach(cb => cb.checked = false);

                // Centang yang sesuai data roles
                rolesArr.forEach(rid => {
                    const cb = document.querySelector(
                        '#editAdditionalRoles input[value="' + rid + '"]');
                    if (cb) cb.checked = true;
                });
            });
        });
    });
</script>
