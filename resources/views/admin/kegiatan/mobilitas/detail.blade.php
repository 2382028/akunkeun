@extends('admin.templates.sidebar')

@section('contain')
<!-- Awal Dashboard - Kegiatan - Keuangan -->
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12 mb-3">
      <div class="card">

{{-- perulangan number --}}
<div class="container-fluid px-4 py-3">
    <div class="row page_content card-style">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="row">
                  <h5 class="fw-bold">Informasi Kegiatan</h5>
                </div>
                <div class="row small">
                    <div class="col-2">Nama Kegiatan</div>
                    <div class="col-10">: {{$infoKegiatan[0]->nama_kegiatan}}</div>
                </div>
                <div class="row small">
                    <div class="col-2">Tanggal Penyelenggaran</div>
                    <div class="col-10">: {{$infoKegiatan[0]->tgl_mulai}}</div>
                </div>
                <div class="row small">
                    <div class="col-2">Alamat</div>
                    <div class="col-10">: {{$infoKegiatan[0]->alamat}}</div>
                </div>
                <br>
            </div>
        </div>      
      <div class="col-md-12 mb-3">
        <h5 class="fw-bold">Informasi Peminjaman</h5>
        <div class="table-responsive">   
          <form action="{{url('/c_a_admin_mobilitas')}}" method="post">
          @csrf
          <input type="hidden" name="idKegiatan" value="{{$infoKegiatan[0]->data_perjadinkegiatan}}">
          <table id="example" class="table table-bordered" style="width: 100%">
            <thead>
              <tr class="text-center small">
                <th class="th-sm">No</th>
                <th class="th-md">Untuk</th>
                <th class="th-md">Tanggal Keberangkatan</th>
               
                <th class="th-md">Status</th>
                <th class="th-md">Supir</th>
                <th class="th-md">Mobil</th>
                @if ($mobilitass[0]->status == 'pengajuan') 
                <th class="th-sm">Detail Digunakan</th>
                @endif
              </tr>
            </thead>
            @foreach ($mobilitass as $mobilitas)
              <tr>
                  <td class='text-center'>{{$loop->iteration}} <input type="hidden" name="idMobilitas" value="{{$mobilitas->id}}"></td>
                  <td>{{$mobilitas->tujuan_penggunaan}} <input type="hidden" name="tujuan" value="{{$mobilitas->tujuan_penggunaan}}"></td>
                  <td class='text-center'>{{$mobilitas->tgl_mulai}}</td>
           
                  <td class='text-center'>
                    <input type="hidden" name="status_aksi" value="{{$mobilitas->status}}">
                    @if (($mobilitas->status == 'ditolak') | $mobilitas->status == 'selesai')
                    {{$mobilitas->status}}
                    @else
                    <select class="form-select" aria-label="Default select example" name="status">
                      @if ($mobilitas->status == 'pengajuan')
                      <option value="{{$mobilitas->status}}" selected>{{$mobilitas->status}}</option>
                      <option value="proses">Setujui</option>
                      <option value="ditolak">Tolak</option>
                      @endif
                      @if ($mobilitas->status == 'proses')
                      <option value="{{$mobilitas->status}}">{{$mobilitas->status}}</option>
                      <option value="selesai">Selesai</option>
                      @endif
                    </select>
                    @endif
                  </td>
                  <td>
                      @if ($mobilitas->status == 'pengajuan')
                        <select class="form-select" aria-label="Default select example" name="supir">
                          @foreach ($pengemudis as $pengemudi)
                          <option value="{{$pengemudi->id}}">{{$pengemudi->nama_lengkap}}</option>
                          @endforeach
                        </select>
                      @else
                      {{$penanggungs[0]->nama_lengkap}}
                      @endif
                  </td>
                  <td>
                    @if ($mobilitas->status == 'pengajuan')
                    <select class="form-select" aria-label="Default select example" name="mobil">
                      @foreach ($kendaraans as $kendaraan)
                      <option value="{{$kendaraan->id}}">{{$kendaraan->merek}} [{{$kendaraan->no_polisi}}]</option>
                      @endforeach
                    </select>
                    @else
                    {{$penanggungs[0]->merek}}
                    @endif
                  </td>
                  @if ($mobilitas->status == 'pengajuan')
                  <td>
                    <div class="input-group mb-3">
                      <input type="number" class="form-control" min="1" name="satuan">
                      <span class="input-group-text" id="basic-addon2">Hari</span>
                    </div>
                  </td>
                  @endif
              </tr>
            @endforeach
        </table>
        </div>
      </div>

      <div class="col-md-12 mb-3">
        <div class="d-grid gap-2 d-md-flex justify-content-center">
          {{-- <input type="hidden" name="idKegiatan" value="{{$infoKegiatan->id}}"> --}}
          @if (($mobilitass[0]->status == 'selesai') | $mobilitass[0]->status == 'ditolak')
          <a href="{{url('/kegiatan-mobilitas/' . 'pengajuan')}}" class="btn btn-dark">Kembali</a>
          @else
          <a href="{{url('/kegiatan-mobilitas/' . 'pengajuan')}}" class="btn btn-dark">Kembali</a>
          <button class="btn btn-success" type="submit">Perbaharui data</button>
          @endif
        </div>
      </div>
      </form>
    </div> 
</div>  

</div>
</div>
</div>
</div>
</div>
<!-- Akhir Dashboard - Kegiatan - Keuangan -->
@endsection