@extends('admin.templates.sidebar')

@section('contain')

<!-- Awal Dashboard RKAKL Akun x Rkakl -->
<main class="" style="background: #D9D9D9;">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <h4>Referensi / <span class="fw-bold">RKAKL</span> / <span class="fw-bold">Akun X RKAKL</span></h4>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 mb-2">
        <div class="card border-0 bg-secondary">
          <div class="wrapper page">
            <a href="{{url('/admin-rkakl_satker')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white ">Satker</a>
            <a href="{{url('/admin-rkakl_program')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white">Program</a>
            <a href="{{url('/admin-rkakl_kegiatan')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white">Kegiatan</a>
            <a href="{{url('/admin-rkakl_output')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white">Output</a>
            <a href="{{url('/admin-rkakl_suboutput')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white">Sub Output</a>
            <a href="{{url('/admin-rkakl_komponen')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white">Komponen</a>
            <a href="{{url('/admin-rkakl_subkomponen')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white">Sub Komponen</a>
            <!--<a href="{{url('/admin-rkakl_akun')}}" class="page-wrap text-decoration-none btn btn-warning btn-sm text-white">Akun</a>-->
            <a href="{{url('/admin-akun_x_rkakl')}}" class="page-wrap page-wrap-active text-decoration-none btn btn-warning btn-sm text-white">Akun</a>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_akunxrkakl">
                <i class="fa fa-plus"></i> Tambah
              </button>
              <table id="example" class="table table-bordered data-table" style="width: 100%">
                <thead>
                  <tr class="text-center small">
                    <th class="th-sm">No</th>
                    <th class="th-md">Kode Induk</th>
                    <th class="th-md">Uraian Akun</th>
                    <th class="th-md">Uraian Komponen</th>
                    <th class="th-md">Nominal</th>
                    <th class="th-lg-percent">Aksi</th>
                  </tr>
                </thead>
                @foreach ($akunxrkakls as $akunxrkakl)
                <tr>
                <td class='text-center'></td>
                  <td class='text-center'>{{ $akunxrkakl->kode_satker }}.
                    {{ $akunxrkakl->kode_program }}.
                    {{ $akunxrkakl->kode_kegiatan }}.
                    {{ $akunxrkakl->kode_output }}.
                    {{ $akunxrkakl->kode_sub_output }}.
                    {{ $akunxrkakl->kode_komponen }}.
                    {{ $akunxrkakl->kode_sub_kegiatan }}.
                    {{ $akunxrkakl->kode_akun }} 
                  </td>
                  <td class=''>{{ $akunxrkakl->uraian }}</td>
                  <td class=''>{{ $akunxrkakl->nama_sub_kegiatan}}</td>
                  <td class='text-center'>{{ $akunxrkakl->nominal }}</td>
                  

                  <td class='d-flex justify-content-center flex-row'>
                    <span class="p-1">
                      <a href="{{ route('admin-akun_x_rkakl.edit', $akunxrkakl->id) }}" class="text-decoration-none btn btn-success"><i class="fa-regular fa-pen-to-square"></i></a>
                    </span>
                    <span class="p-1">
                      <form action="{{ route('admin-akun_x_rkakl.destroy', $akunxrkakl->id) }}" method="post">
                        @method('delete')
                        @csrf
                        <button type="submit" class="text-decoration-none btn btn-danger" onclick="return confirm('Apakah Anda yakin akan menghapus data ini??')"><i class="fa-solid fa-trash"></i></button>
                      </form>
                    </span>
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
</main>
<!-- Akhir Dashboard RKAKL Satker -->

<!-- Aawal Tambah Akun-->
<div class="modal fade" id="tambah_akunxrkakl" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      {{-- start form tambah output --}}
      <form action="{{ route('admin-akun_x_rkakl.store') }}" method="post">
        @csrf
        <div class="modal-body small">
          <div class="row">
            <div class="col-md-4">
              <label for="sub_komponen_dropdown" class="form-label">Kode Induk</label>
            </div>
            <div class="col-md-8">
              <div class="input-group mb-3 submit-select">
                <select name="sub_komponen_id" class="form-select text-muted" id="sub_komponen_dropdown">
                  <option value="" disabled selected>Pilih Kode Induk</option>
                  @foreach ($rkaklsubkomponens as $rkaklsubkomponen)
                  <option value="{{ $rkaklsubkomponen->id }}">{{ $rkaklsubkomponen->getSatker->kode_satker }}.
                    {{ $rkaklsubkomponen->getProgram->kode_program }}.
                    {{ $rkaklsubkomponen->getKegiatan->kode_kegiatan }}.
                    {{ $rkaklsubkomponen->getOutput->kode_output }}.
                    {{ $rkaklsubkomponen->getSuboutput->kode_sub_output }}.
                    {{ $rkaklsubkomponen->getKomponen->kode_komponen }}.
                    {{ $rkaklsubkomponen->kode_sub_kegiatan }}.
                    {{ $rkaklsubkomponen->nama_sub_kegiatan }}
                  </option>
                  @endforeach
                </select>

              </div>
            </div>
          </div>
          {{-- input kode akun --}}
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="InputKodeAkun" class="form-label">Kode Akun</label>
            </div>
            <div class="col-md-8">
              <input name="kode_akun" type="text" class="form-control" id="InputKodeAkun">
            </div>
          </div>

          {{-- input uraian --}}
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="InputNamaAkun" class="form-label">Uraian</label>
            </div>
            <div class="form-floating col-md-8">
              <textarea name="uraian" class="form-control" id="InputNamaAkun"></textarea>
            </div>
          </div>

          {{-- input nominal --}}
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="InputNominal" class="form-label">Nominal</label>
            </div>
            <div class="form-floating col-md-8">
              <input name="nominal" inputmode="numeric" class="form-control" id="InputNominal">
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
<!-- Akhir Tambah Akun -->


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    // Menangani perubahan pada dropdown
    $("#sub_komponen_dropdown").change(function() {
      // Mengambil nilai yang dipilih oleh pengguna
      var selectedValue = $(this).val();

      // Mengatur nilai input tersembunyi ref_sub_komponen_id
      $("input[name='ref_sub_komponen_id']").val(selectedValue);
    });
  });
</script>

@endsection