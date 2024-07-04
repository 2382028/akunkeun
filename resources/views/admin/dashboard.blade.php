@extends('admin.templates.sidebar')

@section('contain')
<!-- Awal Dashboard -->
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <h4>Dashboard, {{auth('administrator')->user()->username}} [{{auth('administrator')->user()->role}}]</h4>
          </div>
        </div>
        <div class="row text-white justify-content-between">
          <div class="col-md-12 mb-3">
            <div class="card" style="height: min-content">
              <div class="card-body">
                <div class="row row row-cols-1 row-cols-md-3 g-4">
                  <div class="col">
                    <div class="card" style="max-width: 37rem; background: #082A99">
                      <div class="row g-0">
                        <div class="col-md-4">
                          <img src="{{asset('/assets/images/file.png')}}" class="img-fluid rounded-start" alt="...">
                        </div>
                        <div class="col-md-8">
                          <div class="card-body">
                            <h1 class="card-title">{{$perjadin}}</h1>
                            <p class="card-text">Pengajuan - Perjalanan Dinas Langsung</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col">
                    <div class="card" style="max-width: 37rem; background: #082A99">
                      <div class="row g-0">
                        <div class="col-md-4">
                          <img src="{{asset('/assets/images/file.png')}}" class="img-fluid rounded-start" alt="...">
                        </div>
                        <div class="col-md-8">
                          <div class="card-body">
                            <h1 class="card-title">{{$kegiatan}}</h1>
                            <p class="card-text">Pengajuan - Perjalanan Dinas Kegiatan</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <!-- Akhir Dashboard -->
@endsection