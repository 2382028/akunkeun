@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <h4>Laporan</h4>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 mb-2">
        <div class="card border-0 bg-secondary">
          <div class="page wrapper">
            <button class="page-wrap btn btn-sm btn-primary active" data-page="page_1">Perjadin Langsung</button>
            <button class="page-wrap btn btn-sm btn-warning text-white" data-page="page_2">Perjadin Kegiatan</button>
            <button class="page-wrap btn btn-sm btn-success" data-page="page_3">Transaksi Service</button>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 mb-3">
        <div class="card">
          <div class="card-body content">

            <!-- Laporan Perjadin Langsung -->
            <div class="row page_content page_1">
              <div class="mb-3 row">
                <h4>Perjadin Langsung</h5>
                </div>
                <form action="{{url('/ganerate_perjadin')}}" method="post">
                @csrf
              <div class="mb-3 row">
                <label for="" class="col-sm-4 col-form-label">Dari Tanggal</label>
                <div class="col-sm-8">
                  <input type="date" class="form-control" id="" name="mulai">
                </div>
              </div>
              <div class="mb-3 row">
                <label for="" class="col-sm-4 col-form-label">Sampai Tanggal</label>
                <div class="col-sm-8">
                  <input type="date" class="form-control" id="" name="sampai">
                </div>
              </div>
              <div class="mb-3 row">
                <div class="col-md-4 mx-auto">
                    <button type="submit" class="btn btn-success btn-sm">Generate</button>
                </div>
              </div>
            </form>
            </div>

            <!-- Laporan Perjadin Kegiatan -->
            <div class="row page_content page_2">
              <div class="mb-3 row">
                <h4>Perjadin Kegiatan</h4>
              </div>
              <form action="{{url('/ganerate_kegiatan')}}" method="post">
              @csrf
              <div class="mb-3 row">
                <label for="" class="col-sm-4 col-form-label">Dari Tanggal</label>
                <div class="col-sm-8">
                  <input type="date" class="form-control" id="" name="mulai">
                </div>
              </div>
              <div class="mb-3 row">
                <label for="" class="col-sm-4 col-form-label">Sampai Tanggal</label>
                <div class="col-sm-8">
                  <input type="date" class="form-control" id="" name="sampai">
                </div>
              </div>
              <div class="mb-3 row">
                <div class="col-md-4 mx-auto">
                  <button type="submit" class="btn btn-success btn-sm">Generate</button>
                </div>
              </div>
              </form>
            </div>

            <!-- Laporan Transaksi Service -->
            <div class="row page_content page_3">
              <div class="mb-3 row">
                <h4>Transaksi Service</h4>
              </div>
              <form action="{{url('/ganerate_bmn')}}" method="post">
              @csrf
              <div class="mb-3 row">
                <label for="" class="col-sm-4 col-form-label">Dari Tanggal</label>
                <div class="col-sm-8">
                  <input type="date" class="form-control" id="" name="mulai">
                </div>
              </div>
              <div class="mb-3 row">
                <label for="" class="col-sm-4 col-form-label">Sampai Tanggal</label>
                <div class="col-sm-8">
                  <input type="date" class="form-control" id="" name="sampai">
                </div>
              </div>
              <div class="mb-3 row">
                <div class="col-md-4 mx-auto">
                  <button type="submit" class="btn btn-success btn-sm">Genarate</button>
                </div>
              </div>
              </form>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection