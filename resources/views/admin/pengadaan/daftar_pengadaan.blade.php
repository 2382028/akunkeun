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
              <a href="{{url('/daftar-pengadaan/' . 'pengadaan-kegiatan')}}" class="page-wrap btn btn-sm btn-dark" style="padding: 8px 13px; border-radius: 5px;">Pengadaan Kegiatan</a>
          @elseif ($status == 'surat-pemesanan')
              <a href="{{url('/daftar-pengadaan/' . 'sdp')}}" class="btn btn-sm btn-dark me-2" style="padding: 8px 13px; border-radius: 5px;">SDP</a>
              <a href="{{url('/daftar-pengadaan/' . 'surat-pemesanan')}}" class="page-wrap btn btn-sm btn-primary" style="padding: 8px 13px; border-radius: 5px;">Surat Pemesanan</a>
              <a href="{{url('/daftar-pengadaan/' . 'pengadaan-kegiatan')}}" class="page-wrap btn btn-sm btn-dark" style="padding: 8px 13px; border-radius: 5px;">Pengadaan Kegiatan</a>
              @elseif ($status == 'pengadaan-kegiatan')
              <a href="{{url('/daftar-pengadaan/' . 'surat-pemesanan')}}" class="page-wrap btn btn-sm btn-dark" style="padding: 8px 13px; border-radius: 5px;">Surat Pemesanan</a>
              <a href="{{url('/daftar-pengadaan/' . 'sdp')}}" class="btn btn-sm btn-dark  me-2" style="padding: 8px 13px; border-radius: 5px;">SDP</a>
              <a href="{{url('/daftar-pengadaan/' . 'pengadaan-kegiatan')}}" class="page-wrap btn btn-sm btn-primary" style="padding: 8px 13px; border-radius: 5px;">Pengadaan Kegiatan</a>
          @endif
          </div>
        </div>
      </div>
    </div>
    <div class="row">
        @if ($status == "sdp")
        <div class="col-md-12 mb-3">
    <div class="card">
        <div class="card-body text-center">
            <h2 class="mb-3">Halaman Dalam Kontruksi</h2>
            <p class="mb-4">Halaman ini sedang dalam pengembangan. Mohon cek kembali nanti.</p>
            <img src="https://img.freepik.com/free-vector/under-construction-concept-illustration_114360-2142.jpg" 
                 alt="Ilustrasi Sedang Dalam Kontruksi" 
                 style="max-width: 100%; height: auto;">
        </div>
    </div>
</div>
    @elseif ($status == "surat-pemesanan")
    <div class="col-md-12 mb-3">
    <div class="card">
        <div class="card-body text-center">
            <h2 class="mb-3">Halaman Dalam Kontruksi</h2>
            <p class="mb-4">Halaman ini sedang dalam pengembangan. Mohon cek kembali nanti.</p>
            <img src="https://img.freepik.com/free-vector/under-construction-concept-illustration_114360-2142.jpg" 
                 alt="Ilustrasi Sedang Dalam Kontruksi" 
                 style="max-width: 100%; height: auto;">
        </div>
    </div>
</div>
    @elseif ($status == "pengadaan-kegiatan")
    <div class="col-md-12 mb-3">
    
        <div class="card">
          <div class="card-body content">
            <div class="d-flex align-items-center mb-2">
                <div class="me-2">
                    <a href="{{url('/daftar-pengadaan/' . 'pengadaan-kegiatan'.'?kwitansi=belum')}}" 
                    class="btn btn {{ $kwitansi == 'belum' ? 'btn-warning text-white' : 'btn-outline-warning' }}">
                    Belum Kwitansi <span class="badge bg-light text-dark"></span>
                    </a>
                </div>
                <div class="">
                <a href="{{url('/daftar-pengadaan/' . 'pengadaan-kegiatan'.'?kwitansi=sudah')}}" 
                    class="btn btn {{ $kwitansi == 'sudah' ? 'btn-success' : 'btn-outline-success' }}">
                    Sudah Kwitansi <span class="badge bg-light text-dark"></span>
                    </a>
              </div>
            </div>
  
  
            <!-- BMN Kendaraan - Pengajuan -->
            <div class="row page_content card-style">
              <div class="table-responsive">
                <table id="example" class="table table-bordered data-table" style="width: 100%">
                  <thead>
                    <tr class="text-center small">
                      <th class="th-sm">No</th>
                      <th class="th-sm">ID Kegiatan</th>
                      <th class="th-lg">Judul Kegiatan</th>
                      <th class="th-sm">Tanggal Kegiatan</th>
                      <th class="th-sm">Pengusul</th>
                      <th class="th-md">Nama Pengadaan</th>
                      <th class="th-md">Keterangan Pengadaan</th>
                      <th class="th-sm">Nominal Pengadaan</th>
                      <th class="th-sm">Aksi</th>
                    </tr>
                  </thead>
                 @foreach ($dataKegiatans as $dataKegiatan)
                  <tr>
                    <td class="text-center"></td>
                    <td class="text-center">{{$dataKegiatan->id_kegiatan}}</td>
                    <td class="text-center">{{$dataKegiatan->nama_kegiatan}} </td>
                    <td class="text-center">{{$dataKegiatan->tanggal_kegiatan}}</td>
                    <td class="text-center">{{$dataKegiatan->nama_pengusul}}</td>
                    <td class="text-center">{{$dataKegiatan->nama_pengadaan}}</td>
                    <td class="text-center">{{$dataKegiatan->ket_pengadaan}}</td>
                    <td class="text-center">Rp {{ number_format($dataKegiatan->nominal_pengadaan, 0, ',', '.') }}</td>
                    <td class="text-center">
                            <a href="{{ route('detail-pengadaan-kegiatan', ['id' => $dataKegiatan->id_kegiatan, 'kebutuhanId' => $dataKegiatan->id_kebutuhan]) }}"
                            class="btn btn-success btn-sm editBtn" data-id="">
                                <i class="fa fa-eye"></i>
                            </a>
                    </td>
                  </tr>
                  @endforeach
                 
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