@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>BMN / Data Service Ruangan / <span class="fw-bold">{{$asset[0]->nama_ruangan}} - {{$asset[0]->kode_ruangan}}</span></h4>
        </div>
    </div>
    <form action="{{url('/c_permohonan_service_ruangan')}}" method="post">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body content">
                
                    <input type="hidden" name="idPermohonan" value="{{$permohonan->id}}">
                    <input type="hidden" name="idAsset" value="{{$asset[0]->id}}">
                    <div class="row">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="row">
                                </div>
                                <div class="row small mb-3">
                                    <div class="col-3">Kode Permohonan</div>
                                    <div class="col-9">
                                        <span class="me-1">:</span>
                                        
                                    </div>
                                </div>
                                <div class="row small mb-2">
                                    <div class="col-3">Tanggal Pengajuan</div>
                                    <div class="col-3 d-flex">
                                        <span class="me-1">:</span>
                                        <input type="datetime-local" class="form-control" value="{{ $permohonan->tgl_permohonan }}" name="tgl_pengajuan">
                                    </div>
                                </div>
                                <div class="row small mb-2">
                                    <div class="col-3">Tanggal Pemeriksaan</div>
                                    <div class="col-3 d-flex">
                                        <span class="me-1">:</span>
                                        <input type="datetime-local" class="form-control" value="{{ $permohonan->tgl_pemeriksaan }}" name="tgl_pemeriksaan">
                                    </div>
                                </div>
                                <div class="row small mb-2">
                                    <div class="col-3">Tanggal Pengerjaan</div>
                                    <div class="col-3 d-flex">
                                        <span class="me-1">:</span>
                                        <input type="datetime-local" class="form-control" value="{{ $permohonan->tgl_pengerjaan }}" name="tgl_pengerjaan">
                                    </div>
                                </div>
                                <div class="row small mb-2">
                                    <div class="col-3">Tanggal Selesai</div>
                                    <div class="col-3 d-flex">
                                        <span class="me-1">:</span>
                                        <input type="datetime-local" class="form-control" value="{{ $permohonan->tgl_selesai }}" name="tgl_selesai">
                                    </div>
                                </div>
                                <div class="row small mb-3">
                                    <div class="col-3">Nama Ruangan</div>
                                    <div class="col-9">: {{$asset[0]->nama_ruangan}} - {{$asset[0]->kode_ruangan}}</div>
                                </div>
                                <div class="row small mb-3">
                                    <div class="col-3">Alasan Kerusakan</div>
                                    <div class="col-9">: {{$permohonan->alasan_ket}}</div>
                                </div>
                                <div class="row small mb-3">
                                    <div class="col-3">Nama Penyedia</div>
                                    <div class="col-3 d-flex">
                                        <span class="me-1">:</span>
                                        <select class="js-example-basic-single-3" style="width: 100%" aria-label=".form-select-sm example" name="penyedia">
                                            @foreach ($penyedias as $penyedia)
                                            @if ($penyedia->id == $permohonan->data_penyedia_id)
                                            <option value="{{$penyedia->id}}" selected>{{$penyedia->nama_CV}} [{{$penyedia->kategori}}]</option>
                                            @endif
                                            <option value="{{$penyedia->id}}">{{$penyedia->nama_CV}} [{{$penyedia->kategori}}]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row small mb-3">
                                    <div class="col-3">Status</div>
                                    <div class="col-3 d-flex">
                                        <span class="me-1">:</span>
                                        <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="status">
                                            <option value="{{$permohonan->status}}" selected>{{$permohonan->status}}</option>
                                            <option value="pemeriksaan">Pemeriksaan</option>
                                            <option value="pengerjaan">Pengerjaan</option>
                                            <option value="pembayaran">Selesai</option>
                                        </select>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                      
                      <div class="col-md-12 mb-3">
                        <h5 class="fw-bold">Informasi Komponen Yang Diperlukan</h5>
                        <button type="button" class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#tambah_kebutuhan">
                          <i class="fa fa-plus"></i> Tambah
                        </button>
                        <div class="table-responsive">                        
                          <table id="example" class="table table-bordered" style="width: 100%">
                            <thead>
                              <tr class="text-center small">
                                <th class="th-md">No</th>
                                <th class="th-md">Nama Komponen</th>
                                <th class="th-sm">Jumlah</th>
                                {{-- <th class="th-lg-percent">Aksi</th> --}}
                              </tr>
                            </thead>
                            @foreach ($komponens as $komponen)
                                <tr>
                                    <td class='text-center'>{{$loop->iteration}}</td>
                                    <td class='text-center'>{{$komponen->nama_barang}}</td>
                                    <td class='text-center'>{{$komponen->frekuensi}}</td>
                                    {{-- <td class='text-center'>
                                        <form action="/d_komponen_service_asset/{{$komponen->id}}" method="post">
                                            @csrf
                                            <input type="hidden" name="idPermohonan" value="{{$permohonan->id}}">
                                            <button type="submit" class="text-decoration-none btn btn-danger" onclick="return confirm('Hapus Data?')"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </table>
                        </div>
                      </div>
    
                      <div class="col-md-12 mb-3">
                        <div class="d-grid gap-2 d-md-flex justify-content-center">
                            <a href="{{url('/service_ruangan_all/' . $permohonan->status)}}" class="btn btn-dark">Kembali</a> 
                            <button class="btn btn-success" type="submit">Perbaharui data</button>
                        </div>
                      </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    </form>
</div>

  <!-- Awal Modal Tambah Data Penyedia -->
  <div class="modal fade" id="tambah_kebutuhan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{url('/c_service_komponen_ruangan')}}" method="post">
            @csrf
            <input type="hidden" name="idPermohonan" value="{{$permohonan->id}}">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah Komponen yang diperlukan </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body col-md-12">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Nama Komponen</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="" name="komponen">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="" class="form-label">Jumlah yang diperlukan</label>
                    </div>
                    <div class="col-md-8">
                        <input type="number" min="1" class="form-control" id="" name="jumlah" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
        </div>
    </div>
</div>
<!-- Akhir Modal Tambah Data Penyedia -->
@endsection