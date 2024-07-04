@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>BMN / Data Penyedia / <span class="fw-bold">{{$penyedia->nama_CV}}</span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body content">
                    <form action="{{url('/up_data_penyedia')}}" method="post">
                    @csrf
                    <input type="hidden" name="idPenyedia" value="{{$penyedia->id}}">
                    <div class="modal-body col-md-12">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">NPWP</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="NPWP" value="{{$penyedia->NPWP}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Nama CV</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="namaCV" value="{{$penyedia->nama_CV}}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Nama Penanggung Jawab</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="penanggungJawab" value="{{$penyedia->penanggung_jawab}}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Jabatan</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="jabatan" value="{{$penyedia->jabatan}}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">No. Telepon</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="telps" value="{{$penyedia->no_telp}}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Alamat</label>
                            </div>
                            <div class="col-md-8">
                                <textarea class="form-control" id="" rows="3" name="alamat" required>{{$penyedia->alamat}}</textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Kategori</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="kategori" value="{{$penyedia->kategori}}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Tahun Gabung</label>
                            </div>
                            <div class="col-md-8">
                                <input type="date" class="form-control" id="" name="tahun" value="{{$penyedia->tahun}}" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="d-grid gap-2 d-md-flex justify-content-center">
                            <a href="{{url('/data_penyedia')}}" class="btn btn-dark">Kembali</a>
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