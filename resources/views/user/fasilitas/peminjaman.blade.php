@extends('user.templates.sidebar')

@section('content')
<section class="mb-5">
    <div class="container">
        <div class="row">
        <div class="col-lg-10 mx-auto">
            <div style="margin-top: 20px">
                <h3 class="fw-bold text-secondary">Pengajuan Peminjaman Barang</h3>
            </div>
            {{-- card --}}
            <div class="card p-3 shadow border-0 rounded-0 mt-3"  data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                <div class="card-body">

                    <!-- Step 1 -->
                    <form action="{{url('/c_peminjaman')}}" method="post">
                    @csrf
                    <input type="hidden" name="idAsset" value="{{$asset->id}}">
                        <div class="container">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="" class="form-label">Nama Barang</label>
                                </div>
                                <div class="col-md-8">
                                    {{$asset->nama_barang}}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="" class="form-label">Keterangan</label>
                                </div>
                                <div class="col-md-8">
                                    {{$asset->nama_merek}}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="" class="form-label">Periode Peminjaman</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col">
                                            <label for="" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="tgl_peminjaman" required>
                                        </div>
                                        <div class="col" >
                                            <label for="" class="form-label">Tanggal Selesai</label>
                                            <input type="date" class="form-control" name="tgl_selesai">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="" class="form-label">Untuk Keperluan <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="" style="height: 100px" name="keperluan" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="btns-group d-grid gap-2 col-6 mx-auto pb-3 mt-5">
                            <button type="submit" class="btn btn-next btn-primary">Ajukan Peminjaman</button>
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