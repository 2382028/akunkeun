@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>BMN / Data Aset / Formulir Perbaikan <span class="fw-bold">{{$asset->nama_barang}}</span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body content">
                    <form action="{{url('/c_service_asset')}}" method="post">
                    @csrf
                    <input type="hidden" name="idAsset" value="{{$asset->id}}">
                    <div class="modal-body col-md-12">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Kode Barang</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="kode" value="{{$asset->kode_barang}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Nama Barang</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="nama" value="{{$asset->nama_barang}}">
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
                            <a href="{{url('/data_assets')}}" class="btn btn-dark">Kembali</a>
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