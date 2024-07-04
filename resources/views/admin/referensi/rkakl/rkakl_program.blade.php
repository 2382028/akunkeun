@extends('admin.templates.sidebar')

@section('contain')

<!-- Awal Dashboard RKAKL Program -->
<main class="" style="background: #D9D9D9;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h4>Referensi / <span class="fw-bold">RKAKL</span> / <span class="fw-bold">Program</span></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="card border-0 bg-secondary">
                <div class="wrapper page">
                        <a href="{{url('/admin-rkakl_satker')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white ">Satker</a>
                        <a href="{{url('/admin-rkakl_program')}}" class="page-wrap page-wrap-active text-decoration-none btn btn-warning btn-sm text-white">Program</a>
                        <a href="{{url('/admin-rkakl_kegiatan')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white">Kegiatan</a>
                        <a href="{{url('/admin-rkakl_output')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white">Output</a>
                        <a href="{{url('/admin-rkakl_suboutput')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white">Sub Output</a>
                        <a href="{{url('/admin-rkakl_komponen')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white">Komponen</a>
                        <a href="{{url('/admin-rkakl_subkomponen')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white">Sub Komponen</a>
                         <!--<a href="{{url('/admin-rkakl_akun')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white">Akun</a>-->
                        <a href="{{url('/admin-akun_x_rkakl')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white">Akun</a>    
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_program">
                                <i class="fa fa-plus"></i> Tambah
                            </button>
                            <hr>
                            <table id="example" class="table table-bordered data-table" style="width: 100%">
                                <thead>
                                    <tr class="text-center small">
                                        <th class="th-sm">No</th>
                                        <th class="th-sm">Kode Induk</th>
                                        <th class="th-sm">Kode Program</th>
                                        <th class="">Nama Program</th>
                                        <th class="th-lg-percent">Aksi</th>
                                    </tr>
                                </thead>
                                @foreach ($rkaklprograms as $rkaklprogram)
                                <tr>
                                    <td class='text-center'></td>
                                    <td class='text-center'>{{ $rkaklprogram->kode_satker }}</td>
                                    <td class='text-center'>{{ $rkaklprogram->kode_program }}</td>
                                    <td class=''>{{ $rkaklprogram->program }}</td>
                                    <td class='d-flex justify-content-center flex-row'>

                                        {{-- <span>
                                    <a href="/admin-rkakl_kegiatan/{{ $rkaklprogram->id }}" class="text-decoration-none btn btn-primary btn-sm">
                                        <i class="fa-regular fa-file-lines"></i>
                                        </a>
                                        </span> --}}

                                        <span class="p-1">
                                            <a href="{{ route('admin-rkakl_program.edit', $rkaklprogram->id) }}" class="text-decoration-none btn btn-success"><i class="fa-regular fa-pen-to-square"></i></a>
                                        </span>
                                        <span class="p-1">
                                            <form action="{{ route('admin-rkakl_program.destroy', $rkaklprogram->id) }}" method="post">
                                                @method('delete')
                                                @csrf
                                                <button type="submit" class="text-decoration-none btn btn-danger" onclick="return confirm('Apakah Anda yakin akan menghapus data ini?')"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </span>
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
</main>
<!-- Akhir Dashboard RKAKL Program -->

<!-- Aawal Tambah Program-->
<div class="modal fade" id="tambah_program" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- start form --}}
            <form action="{{ route('admin-rkakl_program.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    {{-- kode induk --}}
                    <div class="row">
                        <div class="col-md-4">
                            <label for="kodeSatker" class="form-label">Kode Induk</label>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group mb-3 submit-select">
                                <select name="kode_satker" class="form-select text-muted" id="kodeSatker">
                                    <option selected>Pilih Kode Induk</option>
                                    @foreach ($rkaklsatkers as $rkaklsatker)
                                    <option value="{{ $rkaklsatker->id }}">[{{ $rkaklsatker->kode_satker }}] {{ $rkaklsatker->satker }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- kode program --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="kodeProgram" class="form-label">Kode Program</label>
                        </div>
                        <div class="col-md-8">
                            <input name="kode_program" type="text" class="form-control" id="kodeProgram">
                        </div>
                    </div>

                    {{-- nama program --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="namaProgram" class="form-label">Nama Program</label>
                        </div>
                        <div class="form-floating col-md-8">
                            <textarea name="program" class="form-control" id="namaProgram"></textarea>
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
<!-- Akhir Tambah Output -->

@endsection