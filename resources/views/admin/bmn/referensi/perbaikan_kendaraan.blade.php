@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>BMN / Data Kendaraan / Formulir Perbaikan <span class="fw-bold">{{$kendaraan->merek}} - {{$kendaraan->no_polisi}}</span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body content">
                    <form action="{{url('/c_service_kendaraan')}}" method="post">
                    @csrf
                    <input type="hidden" name="idKendaraan" value="{{$kendaraan->id}}">
                    <div class="modal-body col-md-12">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Nomor Kendaraan / Nomor Polisi</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="kode" value="{{$kendaraan->no_polisi}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Nama Kendaraan</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="nama" value="{{$kendaraan->merek}}">
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
                            <a href="{{url('/data_kendaraan')}}" class="btn btn-dark">Kembali</a>
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