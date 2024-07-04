@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>Referensi IKU</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body content">
                    <form action="{{'u_iku'}}" method="post">
                        @csrf
                        <input type="hidden" name="idIKU" value="{{$IKU->id}}">
                        <div class="modal-body col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="" class="form-label">Kode Sasaran Strategis</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group mb-3 submit-select">
                                        <select class="form-select text-muted" id="" name="kode_ss">
                                            <option value="{{$IKU->kode_ss}}" selected>{{$IKU->kode_ss}}</option>
                                            <option value="S1">S1</option>
                                            <option value="S2">S2 </option>
                                            <option value="S3">S3 </option>
                                            <option value="S4">S4 </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="" class="form-label">Nama Sasaran Strategis</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="" name="nama_ss" value="{{$IKU->nama_ss}}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="" class="form-label">Kode IKU</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="" name="kode_iku" value="{{$IKU->kode_iku}}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="" class="form-label">Nama IKU</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="nama_iku">{{$IKU->nama_iku}}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="" class="form-label">Pokja</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group mb-3 submit-select">
                                        <select class="form-select text-muted" id="" name="pokja">
                                            <option value="{{$IKU->pokja}}" selected>{{$IKU->pokja}}</option>
                                            <option value="FA">Fungsi Akademik, Penelitian, dan Pengabdian pada Masyarakat</option>
                                            <option value="FD">Fungsi Pusat Data dan Sistem Informasi </option>
                                            <option value="FK">Fungsi Perencanaan, Keuangan dan Barang Milik Negara </option>
                                            <option value="FH">Fungsi Hukum Masyarakat, Tata Usaha and Kerjasama </option>
                                            <option value="FM">Fungsi Kemahasiswaan, dan Tracer Study </option>
                                            <option value="FT">Fungsi Hukum, Kepegawaian dan Tata Laksana </option>
                                            <option value="FL">Fungsi Kelembagaan, Sarana dan Prasarana </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="" class="form-label">Program Kerja</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="floatingTextarea" name="nama_program_kerja">{{$IKU->nama_program_kerja}}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="d-grid gap-2 d-md-flex justify-content-center">
                                <a href="{{url('/admin-iku')}}" class="btn btn-dark">Kembali</a>
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