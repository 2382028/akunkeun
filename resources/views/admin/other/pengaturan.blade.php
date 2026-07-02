@extends('admin.templates.sidebar')

@section('contain')
    <!-- Awal Dashboard -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h4>
                    Pengaturan
                    @if ($activeTab === 'versi')
                        / Versi
                    @elseif ($activeTab === 'ppn')
                        / Nilai PPN
                    @endif
                </h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card border-0 bg-secondary">
                    <div class="page wrapper" style="position: relative;">
                        <div class="btn-group">
                            <a href="{{ url('/pengaturan?tab=versi') }}" class="page-wrap btn btn-sm btn-warning text-white">
                                Versi
                            </a>
                            <a href="{{ url('/pengaturan?tab=ppn') }}" class="page-wrap btn btn-sm btn-primary">
                                Nilai PPN
                            </a>
                        </div>                     
                    </div>
                </div>
            </div>
        </div>
        @if ($activeTab === 'versi')
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                        data-bs-target="#tambah_versi">
                                        <i class="fa fa-plus"></i> Tambah Versi
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="example" class="table table-bordered data-table" style="width: 100%">
                                    <thead>
                                        <tr class="text-center small">
                                            <th class="th-lg-percent">No</th>
                                            <th class="th-md">Nama Versi</th>
                                            <th class="th-lg-percent">Status</th>
                                            <th class="th-lg-percent">Aksi</th>
                                        </tr>
                                    </thead>
                                    @foreach ($versis as $versi)
                                        <tr>
                                            <td class='text-center'>{{ $loop->iteration }}</td>
                                            <td class='text-center'>{{ $versi->versi }}</td>
                                            <td class='text-center'>{{ $versi->status }}</td>
                                            <td class='text-center d-flex justify-content-evenly'>
                                                @if ($versi->status == 'non-aktif')
                                                    <span>
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            data-bs-toggle="modal" data-bs-target="#confirmationModal"
                                                            onclick="showConfirmation('{{ $versi->id }}')">Aktifkan</button>
                                                    </span>
                                                @else
                                                    <span>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            data-bs-toggle="modal">Versi Aktif</button>
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($activeTab === 'ppn')
    <div class="row">
        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="mb-3">Pengaturan Nilai PPN</h5>

                <form action="{{ url('/simpan_ppn') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="nilai_ppn" class="form-label">Nilai PPN (%)</label>
                        <input type="number" name="nilai_ppn" id="nilai_ppn" 
                               class="form-control" 
                               value="{{ old('nilai_ppn', $ppn->nilai_ppn ?? '') }}" 
                               required min="0" max="100">
                    </div>

                    @if ($ppn)
                        <p class="text-muted small">
                            Terakhir diperbarui: <strong>{{ $ppn->updated_at }}</strong>
                        </p>
                        <button type="submit" class="btn btn-primary">Update PPN</button>
                    @else
                        <button type="submit" class="btn btn-success">Simpan PPN</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endif

    </div>
    <!-- Akhir Dashboard -->

    <!-- Modal Tambah Versi -->
    <div class="modal fade" id="tambah_versi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Versi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body col-md-12">
                    <form action="/c_pengaturan" method="post">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Masukkan Versi</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="versi">
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
    </div>

    <!-- Modal Aktifkan Versi -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog"
        aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="/set_versi" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Perubahan Versi <input hidden
                                type="text" id="confId" name="getIdVersi" value=""></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin akan merubah versi?</p>
                        <p class="fw-bold">Ketik "Ya, Saya yakin akan merubah versi" di bawah ini : </p>
                        <input type="text" class="form-control" id="confirmationInput" name="conf">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Konfirmasi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showConfirmation(hasil) {
            var input = document.getElementById('confId');
            input.setAttribute("value", hasil);
        }
    </script>
@endsection
