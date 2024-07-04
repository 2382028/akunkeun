@extends('admin.templates.sidebar')

@section('contain')
    <div class="container my-5">
      <form action="{{url('/note_perjadin')}}" method="post">
      @csrf
        <input type="hidden" name="perjadin" value="{{$perjadin->id}}">
      <div class="col-md-8 mx-auto ">
        <div class="card rounded-0 border-0">
          <div class="card-body">
            <div class="container pt-5 pb-4">
              <h4 class="fw-bold text-center">LAPORAN PERJALANAN DINAS</h4>
            </div>
            <div class="table-responsive" >
              <table class="table table-bordered">
                <tr>
                  <td class="fw-bold th-sm">Nama Kegiatan</th>
                  <td>{{$perjadin->nama_kegiatan}}</td>
                </tr>
                <tr>
                  <td class="fw-bold">Nomor Surat Tugas</td>
                  <td>{{$perjadin->kode_surat_tugas}}</td>
                </tr>
                <tr>
                  <td class="fw-bold">Pelaksana Kegiatan</td>
                  <td>{{$dokumen[0]->nama_pelaksana}}</td>
                </tr>
                <tr>
                  <td class="fw-bold">Hari/Tanggal</td>
                  <td>{{$perjadin->tgl_mulai}} s.d {{$perjadin->tgl_selesai}}</td>
                </tr>
                <tr>
                  <td class="fw-bold">Tempat</td>
                  <td>                
                  {{$dokumen[0]->tempat_pelaksanaan}}
                  </td>
                </tr>
                <tr style="height: 200px">
                  <td colspan="2">
                    {!! html_entity_decode($dokumen[0]->hasil) !!}
                  </td>
                </tr>
              </table>
    
              <div class="card border-0">
                <div class="card-body ms-auto m-0">
                  Bandung, ............................... 20... <br />Pelaksana Perjalanan Dinas
                </div>
                <br />
                <br />
                <br />
                <div class="card-body ms-auto m-0">
                {{$dokumen[0]->nama_pelaksana}}
                </div>
              </div>
    
            </div>
          </div>
        </div>
        <div class="d-grid mt-3 gap-2 d-md-flex justify-content-md-end mb-2 noprint">
            <a href="{{url('/perjadin-keuangan/detail/' . $perjadin->id)}}" class="btn btn-secondary btn-sm me-md-2 text-decoration-none text-white" type="button">Kembali</a>
            <a onclick="printPage()" class="btn btn-primary btn-sm me-md-2 text-decoration-none text-white" type="button"><i class="fa-solid fa-print"></i> Print</a>
        </div>

      </div>
      
      </form>
    </div>
    <!-- Akhir Delete Modal Data Ruangan -->
    @endsection