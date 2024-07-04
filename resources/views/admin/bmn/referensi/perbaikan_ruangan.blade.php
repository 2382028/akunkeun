@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>BMN / Data Ruangan / Formulir Perbaikan <span class="fw-bold">{{$ruangan->nama_ruangan}} - {{$ruangan->kode_ruangan}}</span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body content">
                    <form action="{{url('/c_service_ruangan')}}" method="post">
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
                                <label for="" class="form-label">Nama Kendaraan</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="nama" value="{{$ruangan->nama_ruangan}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Alasan Perbaikan</label>
                            </div>
                            <div class="col-md-8">
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="keterangan" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="d-grid gap-2 d-md-flex justify-content-center">
                            <a href="{{url('/data_ruangan')}}" class="btn btn-dark">Kembali</a>
                            <button class="btn btn-success" type="submit">Ajukan Perbaikan</button>
                        </div>
                    </div>
                    </form>
  
                </div>
            </div>
        </div>
    </div>
  </div>

@endsection