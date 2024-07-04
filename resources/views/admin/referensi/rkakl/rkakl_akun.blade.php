@extends('admin.templates.sidebar')

@section('contain')

    <!-- Awal Dashboard RKAKL Akun -->
    <main class="" style="background: #D9D9D9;">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <h4>Referensi / <span class="fw-bold">RKAKL</span> / <span class="fw-bold">Akun</span></h4>
          </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-2">
              <div class="card border-0 bg-secondary">
                <div class="wrapper page">
                    <a href="{{url('/admin-rkakl_satker')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white ">Satker</a>
                    <a href="{{url('/admin-rkakl_program')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white">Program</a>
                    <a href="{{url('/admin-rkakl_kegiatan')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white">Kegiatan</a>
                    <a href="{{url('/admin-rkakl_output')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white">Output</a>
                    <a href="{{url('/admin-rkakl_suboutput')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white">Sub Output</a>
                    <a href="{{url('/admin-rkakl_komponen')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white">Komponen</a>
                    <a href="{{url('/admin-rkakl_subkomponen')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white">Sub Komponen</a>
                    <a href="{{url('/admin-rkakl_akun')}}" class="page-wrap page-wrap-active text-decoration-none btn btn-warning btn-sm text-white">Akun</a>
                    <a href="{{url('/admin-akun_x_rkakl')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white">Akun x Rkakl</a>    
                </div>
              </div>
            </div>
          </div>
        <div class="row">
          <div class="col-md-12 mb-3">
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_akun">
                        <i class="fa fa-plus"></i> Tambah
                    </button>
                    <table id="example" class="table table-bordered data-table" style="width: 100%">
                        <thead>
                        <tr class="text-center small">
                            <th class="th-sm">No</th>
                            <th class="th-sm">Kode Akun</th>
                            <th class="th-md">Uraian</th>
                            <th class="th-md">Nominal</th>
                            <th class="th-lg-percent">Aksi</th>
                        </tr>
                        </thead>
                        @foreach ($akuns as $akun)
                        <tr>
                            <td class='text-center'></td>
                            <td class='text-center'>{{ $akun->kode_akun }}</td>
                            <td class=''>{{ $akun->uraian }}</td>
                            <td class=''>Rp. {{ $akun->nominal }}</td>
                            <td class=' flex-row d-flex justify-content-center'>
                                <span class="p-1">
                                    <a href="{{ route('admin-rkakl_akun.edit', $akun->id) }}" class="text-decoration-none btn btn-success"><i class="fa-solid fa-pen-to-square"></i></a>
                                </span>
                                <span class="p-1">
                                    <form action="{{ route('admin-rkakl_akun.destroy', $akun->id) }}" method="post">
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
    <!-- Akhir Dashboard RKAKL Akun -->
    
    <!-- Aawal Tambah Akun-->
    <div class="modal fade" id="tambah_akun" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- start form tambah output --}}
            <form action="{{ route('admin-rkakl_akun.store') }}" method="post">
            @csrf
            <div class="modal-body small">
                {{-- input kode akun --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="InputKodeAkun" class="form-label">Kode Akun</label>
                    </div>
                    <div class="col-md-8">
                        <input name="kode_akun" type="text" class="form-control" id="InputKodeAkun">
                    </div>
                </div>

                {{-- input uraian --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="InputNamaAkun" class="form-label">Uraian</label>
                    </div>
                    <div class="form-floating col-md-8">
                        <textarea name="uraian" class="form-control" id="InputNamaAkun"></textarea>
                    </div>
                </div>

                {{-- input nominal --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="InputNominal" class="form-label">Nominal</label>
                    </div>
                    <div class="form-floating col-md-8">
                        <input name="nominal" inputmode="numeric" class="form-control" id="InputNominal">
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
    <!-- Akhir Tambah Akun -->


@endsection