@extends('admin.templates.sidebar')

@section('contain')
    <div class="section mt-5">
        <div class="container">
            <div class="d-flex justify-content-between pb-3">
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-success btn-sm print-pdf" id="generateTable" onclick="showSecondButton()">Generate Table</button>
                        <button class="btn btn-success btn-sm" type="button" onClick="window.print()" id="secondButton" style="display: none;">Generate to PDF</button>
                    </div>
                </div>
                <div class="row">
                    <div class="page-header col-md-7 d-flex justify-content-end align-item-end">
                        <div class="col-md-12">Tahun Anggaran</div>
                        <div class="col">:</div>
                    </div>
                    <div class="page-header col-md-7 d-flex justify-content-end align-item-end">
                        <div class="col-md-12">Nomor Bukti</div>
                        <div class="col">:</div>
                    </div>
                    <div class="page-header col-md-7 d-flex justify-content-end align-item-end">
                        <div class="col-md-12">MAK</div>
                        <div class="col">:</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div id="section">
        <div class="container">
            <div class="col-md-12 text-center pb-3">
                <h5 class="fw-bold">DAFTAR PEMBAYARAN</h5>
                <h5 class="fw-bold">JASA PROFESI MODERATOR</h5>
                <h5 class="fw-bold">Penguatan Pemahaman Instrumen Akreditasi Program Studi  LAMEMBA</h5>
                <h5 class="fw-bold">Di Lingkungan LLDIKTI Wilayah IV Tahun Anggaran 2023</h5>
                <h5 class="fw-bold">Sesuai SK Kuasa Pengguna Anggaran LLDIKTI Wilayah IV Nomor : <span class="long-space"></span>. Tanggal : <span class="long-space"></span></h5>
                <h5 class="fw-bold">Tanggal Pelaksanaan <span class="long-space"></span></h5>
            </div>
        </div>
    </div>
    
    <section id="table-laporan">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mx-auto">
                    <div class="table-responsive">
                        <table id="calculationTable1" class="table table-bordered calculationTable" style="width: 100%">
                            <thead class="text-center"></thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                  <td colspan="5" class="fw-bold text-end">Sub Total</td>
                                  <td><input type="number" class="total form-control" readonly></td>
                                </tr>
                              </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="ttd">
        <div class="container">
            <div class="row pt-3">
                <div class="row mb-5 pb-3 ">
                    <div class="col-md-6">
                        Setuju dibebankan pada mata anggaran berkenan, <br>
                        a.n. Kuasa Pengguna Anggaran <br>
                        Pejabat Pembuat Komitmen <br>
                        LLDIKTI Wilayah IV, <br><br><br><br>
                        Syahrir Lubis <br>
                        NIP. 1981040820091210004
                    </div>
                    <div class="col-md-6 d-flex justify-content-end align-item-end">
                        Bandung, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Maret 2023 <br>
                        Telah dibayar sejumlah, <br>
                        Bendahara Pengeluaran <br>
                        LLDIKTI Wilayah IV, <br><br><br><br>
                        Elfa Yuliatri <br>
                        NIP. 199107212009122001
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="{{asset('/assets/js/pdfprint.js')}}"></script>
@endsection