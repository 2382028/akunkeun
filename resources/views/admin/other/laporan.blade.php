@extends('admin.templates.sidebar')

@section('contain')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8 mb-3">
                <h4>Laporan</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 mb-3">
                <div class="card border-0 bg-secondary">
                    <div class="page wrapper">
                        <button class="page-wrap btn btn-sm btn-primary active" data-page="page_1">Perjadin Langsung</button>
                        <button class="page-wrap btn btn-sm btn-warning text-white" data-page="page_2">Perjadin
                            Kegiatan</button>
                        <!--<button class="page-wrap btn btn-sm btn-success" data-page="page_3">Transaksi Service</button>-->
                        <button class="page-wrap btn btn-sm btn-dark text-white" data-page="page_4">
                            Pemeliharaan
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 mb-3">

                <div class="card">
                    <div class="card-body content">

                        <!-- Laporan Perjadin Langsung -->
                        <div class="row page_content page_1">
                            <div class="mb-3 row">
                                <h4>Perjadin Langsung</h5>
                            </div>
                            <form action="{{ url('/ganerate_perjadin') }}" method="post">
                                @csrf
                                <div class="mb-3 row">
                                    <label for="" class="col-sm-4 col-form-label">Dari Tanggal</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control" id="" name="mulai" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="" class="col-sm-4 col-form-label">Sampai Tanggal</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control" id="" name="sampai" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <div class="col-sm-8 offset-sm-4 text-end">
                                        <button type="submit" class="btn btn-success btn-sm">Generate</button>
                                    </div>
                                </div>

                            </form>
                        </div>

                        <!-- Laporan Perjadin Kegiatan -->
                        <div class="row page_content page_2" style="display: none;">
                            <div class="mb-3 row">
                                <h4>Perjadin Kegiatan</h4>
                            </div>
                            <form action="{{ url('/ganerate_kegiatan') }}" method="post">
                                @csrf
                                <div class="mb-3 row">
                                    <label for="" class="col-sm-4 col-form-label">Dari Tanggal</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control" id="" name="mulai" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="" class="col-sm-4 col-form-label">Sampai Tanggal</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control" id="" name="sampai" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <div class="col-sm-8 offset-sm-4 text-end">
                                        <button type="submit" class="btn btn-success btn-sm">Generate</button>
                                    </div>
                                </div>

                            </form>
                        </div>

                        <!-- Laporan Transaksi Service -->
                        <!--<div class="row page_content page_3" style="display: none;">-->
                        <!--    <div class="mb-3 row">-->
                        <!--        <h4>Transaksi Service</h4>-->
                        <!--    </div>-->
                        <!--    <form action="{{ url('/ganerate_bmn') }}" method="post">-->
                        <!--        @csrf-->
                        <!--        <div class="mb-3 row">-->
                        <!--            <label for="" class="col-sm-4 col-form-label">Dari Tanggal</label>-->
                        <!--            <div class="col-sm-8">-->
                        <!--                <input type="date" class="form-control" id="" name="mulai" required>-->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!--        <div class="mb-3 row">-->
                        <!--            <label for="" class="col-sm-4 col-form-label">Sampai Tanggal</label>-->
                        <!--            <div class="col-sm-8">-->
                        <!--                <input type="date" class="form-control" id="" name="sampai" required>-->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!--        <div class="mb-3 row">-->
                        <!--            <div class="col-sm-8 offset-sm-4 text-end">-->
                        <!--                <button type="submit" class="btn btn-success btn-sm">Generate</button>-->
                        <!--            </div>-->
                        <!--        </div>-->

                        <!--    </form>-->
                        <!--</div>-->
                        
                        <!-- Rekap Pemeliharaan -->
                        <div class="row page_content page_4" style="display: none;">
                            <div class="mb-3 row">
                                <h4>Pemeliharaan</h4>
                            </div>
                            <form action="{{ url('/rekap_pemeliharaan') }}" method="post">
                                @csrf
                                <div class="mb-3 row">
                                    <label class="col-sm-4 col-form-label">Dari Tanggal</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control" name="mulai" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-4 col-form-label">Sampai Tanggal</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control" name="sampai" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <div class="col-sm-8 offset-sm-4 text-end">
                                        <button type="submit" class="btn btn-success btn-sm">Generate</button>
                                    </div>
                                </div>

                            </form>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const buttons = document.querySelectorAll(".page-wrap");
        const contents = document.querySelectorAll(".page_content");

        function showContent(page) {
            contents.forEach(content => content.style.display = "none");
            document.querySelector(`.page_content.${page}`).style.display = "block";
        }

        buttons.forEach(button => {
            button.addEventListener("click", function() {
                const page = this.getAttribute("data-page");
                showContent(page);
            });
        });
        let initialPage = @json($page);
        showContent(initialPage);
    });
</script>

@endsection
