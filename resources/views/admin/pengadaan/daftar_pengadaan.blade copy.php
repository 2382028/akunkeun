@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid px-4 py-4">

    <div class="row">
      @if ($status == 'sdp')
          <div class="col-md-12"><h4>Daftar Pengadaan / SDP</span></h4>
      @elseif ($status == 'surat-pemesanan')
          <div class="col-md-12"><h4>Daftar Pengadaan / Surat Pemesanan</span></h4>
      @elseif ($status == 'pengadaan-kegiatan')
          <div class="col-md-12"><h4>Daftar Pengadaan / Pengadaan Kegiatan</span></h4>
      @endif
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 mb-3">
        <div class="card border-0 bg-secondary">
          <div class="page wrapper">
  
          @if ($status == 'sdp')
              <a href="{{url('/daftar-pengadaan/' . 'sdp')}}" class="btn btn-sm btn-primary me-2" style="padding: 8px 13px; border-radius: 5px;">SDP</a>
              <a href="{{url('/daftar-pengadaan/' . 'surat-pemesanan')}}" class="page-wrap btn btn-sm btn-dark" style="padding: 8px 13px; border-radius: 5px;">Surat Pemesanan</a>
              <a href="{{url('/daftar-pengadaan/' . 'pengadaan-kegiatan')}}" class="page-wrap btn btn-sm btn-dark" style="padding: 8px 13px; border-radius: 5px;">Surat Pemesanan</a>
          @elseif ($status == 'surat-pemesanan')
              <a href="{{url('/daftar-pengadaan/' . 'sdp')}}" class="btn btn-sm btn-dark me-2" style="padding: 8px 13px; border-radius: 5px;">SDP</a>
              <a href="{{url('/daftar-pengadaan/' . 'surat-pemesanan')}}" class="page-wrap btn btn-sm btn-primary" style="padding: 8px 13px; border-radius: 5px;">Surat Pemesanan</a>
              <a href="{{url('/daftar-pengadaan/' . 'pengadaan-kegiatan')}}" class="page-wrap btn btn-sm btn-dark" style="padding: 8px 13px; border-radius: 5px;">Surat Pemesanan</a>
              @elseif ($status == 'pengadaan-kegiatan')
              <a href="{{url('/daftar-pengadaan/' . 'surat-pemesanan')}}" class="page-wrap btn btn-sm btn-dark" style="padding: 8px 13px; border-radius: 5px;">Surat Pemesanan</a>
              <a href="{{url('/daftar-pengadaan/' . 'sdp')}}" class="btn btn-sm btn-dark  me-2" style="padding: 8px 13px; border-radius: 5px;">SDP</a>
              <a href="{{url('/daftar-pengadaan/' . 'pengadaan-kegiatan')}}" class="page-wrap btn btn-sm btn-primary" style="padding: 8px 13px; border-radius: 5px;">Surat Pemesanan</a>
          @endif
          </div>
        </div>
      </div>
    </div>
    <div class="row">
        @if ($status == "sdp")
        
<div class="col-md-12 mb-3">
            <div class="card">
      
              <div class="card-body content">
      
      
                <!-- BMN Kendaraan - Pengajuan -->
                <div class="row page_content card-style">
                  <div class="d-flex justify-content-between mb-3">
                      <div></div> <!-- Empty div to fill the left side -->
                      <a href="" class="btn btn-primary tambah-mobilitas-btn">+ Tambah</a>
                  </div>
                  <div class="table-responsive">
                    <table id="example" class="table table-bordered data-table" style="width: 100%">
                      <thead>
                        <tr class="text-center small">
                          <th class="th-sm">No</th>
                          <th class="th-sm">Kode Pengadaan</th>
                          <th class="th-md">Nama Pengadaan</th>
                          <th class="th-md">Jenis Pengadaan</th>
                          <th class="th-md">Aksi</th>
                        </tr>
                      </thead>
                     
                      <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                          <a href="{{ route('info-pengadaan') }}" class="btn btn-success btn-sm editBtn" data-id="">
                            <i class="fa fa-pencil-alt"></i>
                        </a>
                        </td>
                      </tr>
                     
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
      
              </div>
            </div>
          </div>
        </div>
      
    </div>
    @elseif ($status == "surat-pemesanan")
    <div class="col-md-12 mb-3">
        <div class="card">
  
          <div class="card-body content">
  
  
            <!-- BMN Kendaraan - Pengajuan -->
            <div class="row page_content card-style">
              <div class="d-flex justify-content-between mb-3">
                  <div></div> <!-- Empty div to fill the left side -->
                  <a href="" class="btn btn-primary tambah-mobilitas-btn">+ Tambah</a>
              </div>
              <div class="table-responsive">
                <table id="example" class="table table-bordered data-table" style="width: 100%">
                  <thead>
                    <tr class="text-center small">
                      <th class="th-sm">No</th>
                      <th class="th-sm">Kode Pengadaan</th>
                      <th class="th-md">Nama Pengadaan</th>
                      <th class="th-md">Jenis Pengadaan</th>
                      <th class="th-md">Aksi</th>
                    </tr>
                  </thead>
                 
                  <tr>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center">
                            <a href="{{ route('info-pengadaan') }}" class="btn btn-success btn-sm editBtn" data-id="">
                                <i class="fa fa-pencil-alt"></i>
                            </a>
                    </td>
                  </tr>
                 
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
  
          </div>
        </div>
      </div>
    </div>
    @elseif ($status == "pengadaan-kegiatan")
    <div class="col-md-12 mb-3">
        <div class="card">
  
          <div class="card-body content">
  
  
            <!-- BMN Kendaraan - Pengajuan -->
            <div class="row page_content card-style">
              <div class="d-flex justify-content-between mb-3">
                  <div></div> <!-- Empty div to fill the left side -->
                  <a href="" class="btn btn-primary tambah-mobilitas-btn">+ Tambah</a>
              </div>
              <div class="table-responsive">
                <table id="example" class="table table-bordered data-table" style="width: 100%">
                  <thead>
                    <tr class="text-center small">
                      <th class="th-sm">No</th>
                      <th class="th-sm">Kode Pengadaan</th>
                      <th class="th-md">Nama Pengadaan</th>
                      <th class="th-md">Jenis Pengadaan</th>
                      <th class="th-md">Aksi</th>
                    </tr>
                  </thead>
                 
                  <tr>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center">
                            <a href="{{ route('info-pengadaan') }}" class="btn btn-success btn-sm editBtn" data-id="">
                                <i class="fa fa-pencil-alt"></i>
                            </a>
                    </td>
                  </tr>
                 
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
  
          </div>
        </div>
      </div>
    </div>
    @endif
</div>


  
@endsection