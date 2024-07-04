@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>BMN / Data Ruangan / <span class="fw-bold">{{$ruangan->nama_ruangan}}</span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body content">
                    <form action="{{url('/up_data_ruangan')}}" method="post">
                    @csrf
                    <input type="hidden" name="idRuangan" value="{{$ruangan->id}}">
                    <div class="modal-body col-md-12">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Kode Ruangan</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="kode" value="{{$ruangan->kode_ruangan}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Nama Ruangan</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="nama" value="{{$ruangan->nama_ruangan}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Nama Penanggung Jawab</label>
                            </div>
                            <div class="col-md-8">
                                <select class="js-example-basic-single-3" aria-label="Default select example" style="width: 100%" name="penanggungjawab">
                                    @foreach ($pegawais as $pegawai)
                                    @if ($ruangan->pegawai_id == $pegawai->id)
                                    <option value="{{$pegawai->id}}" selected>{{$pegawai->nama_lengkap}}</option>
                                    @endif
                                    <option value="{{$pegawai->id}}">{{$pegawai->nama_lengkap}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Kondisi</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-select" aria-label="Default select example" name="kondisi">
                                    <option value="{{$ruangan->kondisi}}" selected>{{$ruangan->kondisi}}</option>
                                    <option value="Kondisi Baik">Kondisi Baik</option>
                                    <option value="Kondisi Rusak">Kondisi Rusak</option>
                                    <option value="Tidak Digunakan">Tidak Digunakan</option>
                                    <option value="Sedang Direnovasi">Sedang Direnovasi</option>
                                  </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="d-grid gap-2 d-md-flex justify-content-center">
                            <a href="{{url('/data_ruangan')}}" class="btn btn-dark">Kembali</a>
                            <button class="btn btn-success" type="submit">Perbaharui data</button>
                        </div>
                    </div>
                    </form>
  
                </div>
            </div>
        </div>
    </div>
  </div>

@endsection