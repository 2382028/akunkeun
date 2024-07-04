@extends('admin.templates.sidebar')

@section('contain')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>BMN / <span class="fw-bold">Data Peminjaman Aset</span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mb-2">
          <div class="card border-0 bg-secondary">
            <div class="page wrapper">
                <a href="{{url('/peminjaman_asset/' . 'pengajuan')}}" class="page-wrap btn btn-sm btn-primary">Pengajuan</a>
                <a href="{{url('/peminjaman_asset/' . 'digunakan')}}" class="page-wrap btn btn-sm btn-warning text-white">Sedang Dipinjam</a>
                <a href="{{url('/peminjaman_asset/' . 'penolakan')}}" class="page-wrap btn btn-sm btn-danger">Riwayat Penolakan</a>
                <a href="{{url('/peminjaman_asset/' . 'selesai')}}" class="page-wrap btn btn-sm btn-success">Riwayat Peminjaman</a>
            </div>
          </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body content">
  
                <!-- Data Penyedia - ALl -->
                <div class="row page_content page_1">
                    <div class="table-responsive">
                        <table id="example" class="table table-bordered data-table" style="width: 100%">
                            <thead>
                            <tr class="text-center small">
                                <th class="th-sm">No</th>
                                <th class="th-md">Nama Peminjaman</th>
                                <th class="th-md">Nama Aset</th>
                                <th class="th-md">Tanggal Peminjaman</th>
                                <th class="th-md">Status</th>
                                <th class="th-sm">Aksi</th>
                            </tr>
                            </thead>
                            @foreach ($peminjamans as $peminjaman)
                            <tr>
                                <td  class='text-center'>{{$loop->iteration}}</td>
                                <td>{{$peminjaman->nama_lengkap}}</td>
                                <td>{{$peminjaman->nama_barang}}</td>
                                <td  class='text-center'>{{$peminjaman->tgl_mulai_digunakan}}</td>
                                <td  class='text-center'>{{$peminjaman->status}}</td>
                                <td class='text-center d-flex justify-content-evenly'>
                                <form action="{{url('/action_peminjaman/' . $peminjaman->idPenanggungJawab)}}" method="post">
                                    @csrf
                                    <input type="hidden" name="idAsset" value="{{$peminjaman->idAsset}}">
                                    @if ($peminjaman->status == 'pengajuan')
                                    <button type="submit" class="text-decoration-none btn btn-success" name="action" value="setujui" onclick="return confirm('Konfirmasi, Setujui Peminjaman?')">Setujui</button>
                                    <button type="submit" class="text-decoration-none btn btn-danger" name="action" value="tolak" onclick="return confirm('Konfirmasi, Tolak Peminjaman?')">Tolak</button>
                                    @endif
                                    @if ($peminjaman->status == 'digunakan')
                                    <button type="submit" class="text-decoration-none btn btn-info text-white" name="action" value="selesai" onclick="return confirm('Konfirmasi, Apakah Barang sudah dikembalikan?')">Selesai</button>
                                    @endif
                                    @if ($peminjaman->status == 'selesai')
                                    <a href="{{url('/laporan')}}" class="btn btn-success">Print Laporan</a>
                                    @endif
                                </form>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
  
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection