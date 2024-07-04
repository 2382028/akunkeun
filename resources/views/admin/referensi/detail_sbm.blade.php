@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>Referensi SBM</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body content">
                    <form action="{{'u_sbm'}}" method="post">
                        @csrf
                        <input type="hidden" name="idSbm" value="{{$sbm->id}}">
                        <div class="modal-body col-md-12">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="" class="form-label">Kode SBM</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="" name="kode" value="{{$sbm->kode_sbm}}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="" class="form-label">Uraian</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="uraian">{{$sbm->uraian}}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="" class="form-label">Satuan</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group mb-3 submit-select">
                                        <select class="form-select text-muted" id="" name="satuan">
                                            <option value="{{$sbm->satuan}}" selected>{{$sbm->satuan}}</option>
                                            <option value="OH">OH</option>
                                            <option value="OB">OB</option>
                                            <option value="OJ">OJ</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="" class="form-label">Biaya</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="" name="nominal" value="{{$sbm->biaya}}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="d-grid gap-2 d-md-flex justify-content-center">
                                <a href="{{url('/sbm')}}" class="btn btn-dark">Kembali</a>
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