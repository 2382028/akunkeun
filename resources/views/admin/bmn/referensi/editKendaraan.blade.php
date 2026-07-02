@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>BMN / Data Kendaraan / <span class="fw-bold">{{$kendaraan->merek}}</span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body content">
                    <form action="{{url('/up_data_kendaraan')}}" method="post">
                    @csrf
                    <input type="hidden" name="idKendaraan" value="{{$kendaraan->id}}">
                    <div class="modal-body col-md-12">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Nama Kendaraan</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="merek" value="{{$kendaraan->merek}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">No Polisi</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="no_polisi" value="{{$kendaraan->no_polisi}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">No Mesin</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="no_mesin" value="{{$kendaraan->no_mesin}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">No STNK</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="no_stnk" value="{{$kendaraan->no_stnk}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">No BPKB</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="no_bpkb" value="{{$kendaraan->no_bpkb}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Legalitas (tahunan)</label>
                            </div>
                            <div class="col-md-8">
                                <input type="date" class="form-control" id="" name="legalitas" value="{{$kendaraan->legalitas}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Legalitas (5 tahunan)</label>
                            </div>
                            <div class="col-md-8">
                                <input type="date" class="form-control" id="" name="legalitas5tahun" value="{{$kendaraan->legalitas_5th}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Tipe</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="tipe" value="{{$kendaraan->tipe}}">
                            </div>
                        </div>
                        <!--<div class="row mb-3">-->
                        <!--    <div class="col-md-4">-->
                        <!--        <label for="" class="form-label">Status Kendaraan</label>-->
                        <!--    </div>-->
                        <!--    <div class="col-md-8">-->
                        <!--        <div class="input-group mb-3 submit-select">-->
                        <!--            <select class="form-select text-muted" id="inputGroupSelect01" name="status">-->
                        <!--                <option value="{{$kendaraan->status}}" selected>{{$kendaraan->status}}</option>-->
                        <!--                <option value="baik">Baik</option>-->
                        <!--                <option value="rusak">Rusak</option>-->
                        <!--            </select>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="d-grid gap-2 d-md-flex justify-content-center">
                            <a href="{{url('/data_kendaraan')}}" class="btn btn-dark">Kembali</a>
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