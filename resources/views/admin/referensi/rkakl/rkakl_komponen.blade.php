@extends('admin.templates.sidebar')

@section('contain')

    <!-- Awal Dashboard RKAKL Komponen -->
    <main class="" style="background: #D9D9D9;">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <h4>Referensi / <span class="fw-bold">RKAKL</span> / <span class="fw-bold">Komponen</span></h4>
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
                    <a href="{{url('/admin-rkakl_komponen')}}" class="page-wrap page-wrap-active text-decoration-none btn btn-warning btn-sm text-white">Komponen</a>
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
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_komponen">
                        <i class="fa fa-plus"></i> Tambah
                    </button>
                    <table id="example" class="table table-bordered data-table" style="width: 100%">
                        <thead>
                        <tr class="text-center small">
                            <th class="th-sm">No</th>
                            <th class="th-sm">Kode Induk</th>
                            <th class="th-md">Kode Komponen</th>
                            <th class="">Nama Komponen</th>
                            <th class="th-lg-percent">Aksi</th>
                        </tr>
                        </thead>
                        @foreach ($rkaklkomponens as $rkaklkomponen)
                        <tr>
                            <td class='text-center'></td>
                            <td class='text-center'>{{ $rkaklkomponen->kode_satker }}.
                                {{ $rkaklkomponen->kode_program }}.{{ $rkaklkomponen->kode_kegiatan }}.{{ $rkaklkomponen->kode_output }}.{{ $rkaklkomponen->kode_sub_output }}</td>
                            <td class='text-center'>{{ $rkaklkomponen->kode_komponen }}</td>
                            <td class=''>{{ $rkaklkomponen->nama_komponen }}</td>
                            <td class='d-flex justify-content-center flex-row'>
                                <span class="p-1">
                                    <a href="{{ route('admin-rkakl_komponen.edit', $rkaklkomponen->id) }}" class="text-decoration-none btn btn-success"><i class="fa-regular fa-pen-to-square"></i></a>
                                </span>
                                <span class="p-1">
                                    <form action="{{ route('admin-rkakl_komponen.destroy', $rkaklkomponen->id) }}" method="post">
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
    <!-- Akhir Dashboard RKAKL Komponen -->

    
    <!-- Aawal Tambah Komponen-->
    <div class="modal fade" id="tambah_komponen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- start form tambah output --}}
            <form action="{{ route('admin-rkakl_komponen.store') }}" method="post">
            @csrf
            <div class="modal-body small">
                <div class="row">
                    <div class="col-md-4">
                        <label for="kodeSuboutput" class="form-label">Kode Induk</label>
                    </div>
                    <div class="col-md-8">
                        <div class="input-group mb-3 submit-select">
                            <select name="id_suboutput" class="form-select text-muted" id="kodeSuboutput">
                                @foreach ($rkaklsuboutputs as $rkaklsuboutput)
                                <option value="{{$rkaklsuboutput->id}}">[{{$rkaklsuboutput->getSatker->kode_satker}}.{{$rkaklsuboutput->getProgram->kode_program}}.{{$rkaklsuboutput->getKegiatan->kode_kegiatan}}.{{$rkaklsuboutput->getOutput->kode_output}}.{{$rkaklsuboutput->kode_sub_output}}] {{$rkaklsuboutput->nama_sub_output}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- kode komponen --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="kodeKomponen" class="form-label">Kode Komponen</label>
                    </div>
                    <div class="col-md-8">
                        <input name="kode_komponen" type="text" class="form-control" id="kodeKomponen">
                    </div>
                </div>

                {{-- nama komponen --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="namaKomponen" class="form-label">Uraian</label>
                    </div>
                    <div class="form-floating col-md-8">
                        <textarea name="nama_komponen" class="form-control" id="namaKomponen"></textarea>
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
    <!-- Akhir Tambah Komponen -->

@endsection