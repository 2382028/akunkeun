@extends('admin.templates.sidebar')

@section('contain')

<div class="container-fluid px-4 py-4">
  <!-- Header Section -->
  <div class="row mb-3">
    <div class="col-md-12">
      <h4 class="mb-0">Monitoring Usulan / 
        <span class="fw-bold">{{ $isPerjadin ? 'Perjalanan Dinas' : 'Kegiatan' }}</span>
      </h4>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col-md-12">
      <div class=" d-flex justify-content-start gap-2">
        <div class=" d-flex justify-content-start gap-2">
          <a href="{{ route('monitoring', ['tipe' => 'perjadin']) }}" 
             class="btn btn {{ $isPerjadin ? 'btn-primary' : 'btn-light' }}">
            Perjalanan Dinas
          </a>
          <a href="{{ route('monitoring', ['tipe' => 'kegiatan']) }}" 
             class="btn btn {{ !$isPerjadin ? 'btn-primary' : 'btn-light' }}">
            Kegiatan
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col-md-12">
      <div class="card border-0 bg-light d-flex justify-content-start gap-2">
        <div class="card-body d-flex justify-content-start gap-2">
          <!-- Tombol untuk status 'Semua' -->
          <a href="{{ route('monitoring', ['status' => 'semua', 'tipe' => $tipe]) }}" 
             class="btn btn {{ $status == 'semua' ? 'btn-dark' : 'btn-outline-dark' }}">
              Semua <span class="badge bg-light text-dark">{{ $countSemua }}</span>
          </a>

          <!-- Tombol untuk status 'Pengajuan' -->
          <a href="{{ route('monitoring', ['status' => 'pengajuan', 'tipe' => $tipe]) }}" 
             class="btn btn {{ $status == 'pengajuan' ? 'btn-primary' : 'btn-outline-primary' }}">
              Pengajuan <span class="badge bg-light text-dark">{{ $countPengajuan }}</span>
          </a>
      
          <!-- Tombol untuk status 'Proses' -->
          <a href="{{ route('monitoring', ['status' => 'proses', 'tipe' => $tipe]) }}" 
             class="btn btn {{ $status == 'proses' ? 'btn-warning text-white' : 'btn-outline-warning' }}">
              Proses <span class="badge bg-light text-dark">{{ $countProses }}</span>
          </a>
      
          <!-- Tombol untuk status 'Selesai' -->
          <a href="{{ route('monitoring', ['status' => 'selesai', 'tipe' => $tipe]) }}" 
             class="btn btn {{ $status == 'selesai' ? 'btn-success' : 'btn-outline-success' }}">
              Selesai <span class="badge bg-light text-dark">{{ $countSelesai }}</span>
          </a>

          <!-- Tombol untuk status 'Ditolak' -->
          <a href="{{ route('monitoring', ['status' => 'ditolak', 'tipe' => $tipe]) }}" 
             class="btn btn {{ $status == 'ditolak' ? 'btn-danger' : 'btn-outline-danger' }}">
              Ditolak <span class="badge bg-light text-dark">{{ $countDitolak }}</span>
          </a>
      </div>
      
  
  
  <div class="card-body row page_content page_1">
    <div class="col-md-12 ">
        @if ($items->isNotEmpty())
        <div class="table-responsive" style="margin-top: 5px;"> 
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div>
                  <h6 class="fw-bold text-secondary">Informasi {{ $isPerjadin ? 'Perjalanan Dinas' : 'Kegiatan' }}</h6>
              </div>
              <button id="exportButton" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#dateRangeModal">
                <i class="fa-solid fa-print"></i> Rekap Usulan
              </button>
          </div>
            <table id="example" class="table table-bordered data-table" style="width: 100%">
                <thead>
                    <tr class="text-center small">
                        <th class="th-sm">No</th>
                        <th>ID</th>
                        <th class="th-md">Judul {{ $isPerjadin ? 'Perjalanan Dinas' : 'Kegiatan' }}</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Tanggal {{ $isPerjadin ? 'Keberangkatan' : 'Mulai' }}</th>
                        <th>Pengusul</th>
                        <th>Status</th>
                        
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->nama_kegiatan }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->tgl_mulai }}</td>
                        <td>{{ $item->nama_pengaju }}</td>
                        <td class="text-center">{!! $item->status_pengajuan_detail !!}</td>
                        
                        <td class="text-center">
                        <a href="{{ $isPerjadin ? route('admin.other.detail-usulan', $item->id) : route('admin.other.detail-usulankegiatan', $item->id) }}" 
                             class="btn btn-primary">
                             {{ $isPerjadin ? 'Detail' : 'Detail' }}
                          </a>     
                      </td>                      
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="container text-center">
            <img src="{{ asset('public/assets/images/empty.svg') }}" class="mb-3" width="150px" alt=""><br>
            <h3 class="text-center">Tidak Ada data yang ditemukan!</h3><br>
        </div>
        @endif
    </div>
</div>


<!-- Modal Excel export -->
<div class="modal fade" id="dateRangeModal" tabindex="-1" aria-labelledby="dateRangeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="dateRangeModalLabel">Masukkan Rentang Tanggal</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form id="dateRangeForm" action="{{ url('/generate-laporan-Usulan') }}" method="post">
                  @csrf
                  <!-- Input untuk menentukan tipe laporan -->
                  <input type="hidden" name="tipe" value="{{ $isPerjadin ? 'perjadin' : 'kegiatan' }}">

                  <div class="mb-3">
                      <label for="status" class="form-label">Pilih Status</label>
                      <select class="form-select" name="status" id="status" required>
                          <option value="semua">Semua</option>
                          <option value="pengajuan">Pengajuan</option>
                          <option value="proses">Proses</option>
                          <option value="selesai">Selesai</option>
                          <option value="ditolak">Ditolak</option>
                      </select>
                  </div>

                  <div class="mb-3">
                      <label for="tanggalDari" class="form-label">Tanggal Dari</label>
                      <input type="date" class="form-control" name="tanggalDari" id="tanggalDari" required>
                  </div>
                  <div class="mb-3">
                      <label for="tanggalSampai" class="form-label">Tanggal Sampai</label>
                      <input type="date" class="form-control" name="tanggalSampai" id="tanggalSampai" required>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-primary">Generate Laporan</button>
              </div>
              </form>
      </div>
  </div>
</div>


</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/table2excel/1.1.0/table2excel.min.js"></script>
@endsection
