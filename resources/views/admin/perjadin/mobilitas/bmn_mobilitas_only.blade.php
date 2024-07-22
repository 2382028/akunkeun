@extends('admin.templates.sidebar')

@section('main-class', 'white-background')

@section('contain')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
         .white-background {
            background: white !important;
        }
    </style>
</head>
<section class="mb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h3 class="fw-bold text-secondary">Pengajuan Penggunaan Kendaraan</h3>
                {{-- card --}}
                <div class="card p-3 shadow border-0 rounded-0 mt-3" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    <div class="card-body">
                        <!-- Step 1 -->
                        <form action="{{url('/bmn_mobilitas_only/store')}}" method="post" onsubmit="return validateSelect()">
                            @csrf
                            <div class="col-md-12">
                                <h6 class="text-secondary fw-bold mt-3">Informasi Dasar</h6><br>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-12">
                                    <label for="floatingTextarea">Judul Kegiatan<span class="text-danger">*</span></label>
                                    <textarea class="form-control mt-1" id="floatingTextarea" name="nama_kegiatan" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="konfirmasi" class="form-label">Apakah tanggal keberangkatan sama dengan tanggal selesai?</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="konfirmasi" id="ya" value="ya" checked>
                                        <label class="form-check-label" for="ya">Ya</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="konfirmasi" id="tidak" value="tidak">
                                        <label class="form-check-label" for="tidak">Tidak</label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="" class="form-label">Tanggal Keberangkatan<span class="text-danger">*</span></label>
                                    <input type="date" name="tgl_keberangkatan" id="tgl_keberangkatan" class="form-control" required>
                                    <input type="time" name="jam_keberangkatan" id="jam_keberangkatan" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="" class="form-label">Tanggal Selesai<span class="text-danger">*</span></label>
                                    <input type="date" name="tgl_selesai" id="tgl_mulai" class="form-control" required>
                                    <input type="time" name="jam_selesai" id="jam_mulai" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="" class="form-label">Tanggal Acara</label>
                                    <input type="text" name="tgl_mulai" id="tgl_acara" value="-"  class="form-control" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="provinsi" class="form-label">Provinsi<span class="text-danger">*</span></label>
                                    <input type="text" id="provinsi" name="provinsi" class="form-control" style="text-transform: capitalize" placeholder="Masukkan Provinsi" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="" class="form-label">Kota/Kabupaten<span class="text-danger">*</span></label>
                                    <input type="text" name="kabupaten_kota" id="" class="form-control" style="text-transform: capitalize" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="" class="form-label">Desa/Kecamatan</label>
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-12">
                                    <label for="floatingTextarea">Alamat<span class="text-danger">*</span></label>
                                    <textarea class="form-control mt-1" id="floatingTextarea" name="alamat" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="" class="form-label">Pengemudi<span class="text-danger">*</span></label>
                                    <select class="form-select required2" aria-label=".form-select-sm example" name="pengemudi">
                                        @foreach ($pengemudis as $pengemudi)
                                        <option value="{{$pengemudi->id}}">{{$pengemudi->nama_lengkap}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="" class="form-label">Kendaraan<span class="text-danger"><span class="text-secondary small"> (Khusus untuk Kendaraan Dinas)</span>*</span></label>
                                    <select class="form-select" aria-label=".form-select-sm example" name="kendaraan" id="keteranganSelect">
                                        @foreach ($kendaraans as $kendaraan)
                                                    <option value="{{$kendaraan->id}}" selected>{{$kendaraan->merek}} [{{$kendaraan->no_polisi}}]</option>
                                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="btns-group d-grid gap-2 col-6 mx-auto pb-3 mt-5">
                                <button type="submit" class="btn btn-next btn-primary">Proses ke HKT</button>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- end card --}}
            </div>
        </div>

    </div>
</section>
@endsection
