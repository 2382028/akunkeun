@extends('admin.templates.sidebar')

@section('contain')

<!-- Modal -->
<div class="modal fade" id="warningModal" tabindex="-1" role="dialog" aria-labelledby="warningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-4 border-0">
            <!-- Header -->
            <div class="modal-header bg-gradient-warning text-white py-3">
                <h5 class="modal-title w-100 text-center fw-bold">
                    <i class="fas fa-exclamation-triangle fa-lg me-2"></i> Peringatan!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body text-center p-4">
                <p class="fs-5 text-dark">
                    Pastikan untuk <b>menyimpan data</b> sebelum berpindah halaman untuk menghindari risiko kehilangan data.
                </p>
                <hr>
                <p class="fs-5 text-danger">
                    <i class="fas fa-exclamation-circle"></i> Jika Anda ingin menyimpan data, pastikan <b>No SPBY</b> dan <b>Tanggal Jurnal</b> telah diisi. <br>
                    Jika data tidak ingin diikutkan dalam penyimpanan, Anda bisa mengabaikannya.
                </p>
            </div>

            <!-- Footer -->
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-lg btn-warning tombol-mengerti" data-bs-dismiss="modal">
                    <i class="fas fa-check-circle"></i> Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Warna gradasi untuk header modal */
.bg-gradient-warning {
    background: linear-gradient(135deg, #FFC107, #FF5722);
}

/* Animasi modal muncul */
.modal.fade .modal-dialog {
    transform: scale(0.9);
    transition: transform 0.3s ease-in-out;
}

.modal.show .modal-dialog {
    transform: scale(1);
}

/* Efek tombol hover */
.tombol-mengerti {
    border-radius: 50px;
    padding: 12px 24px;
    font-size: 1.2rem;
    transition: all 0.3s ease-in-out;
}

.tombol-mengerti:hover {
    background-color: #FF9800;
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

/* Responsif */
@media (max-width: 576px) {
    .modal-body p {
        font-size: 1rem;
    }

    .tombol-mengerti {
        font-size: 1rem;
        padding: 10px 18px;
    }
}
</style>




<div class="container-fluid">


    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h4>Pengisian Jurnal</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body content">
                        <form action="{{url('/spby/update')}}" method="post">
                            @csrf
                            <input type="hidden" id="allData" name="allData">
                            <input type="hidden" name="status" value="{{$status}}">
                            <!-- Form content -->
                            <div class="table-responsive">
                                <table id="myTable" class="table table-bordered data-table-spby" style="width: 100%">
                                    <thead>
                                        <tr class="text-center small">
                                            <th>No</th>
                                            <th>ID Kegiatan</th>
                                            <th>Tanggal Transaksi</th>
                                            <th>Tanggal Bayar</th>
                                            <th>MAK</th>
                                            <th>URAIAN</th>
                                            <th>Nominal Bruto</th>
                                            <th>Nilai Pajak</th>
                                            <th class="th-md">No SPBY</th>
                                            <th>Tgl Jurnal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php 
                                            $numpegawai = 0; 
                                            $spbyNol = "000TD/PB/693206/" . (\App\Models\Versi::find(session('versi'))->versi ?? '0000');
                                        @endphp
                                        @foreach ($datas as $data)
                                        <tr>
                                            <td class="text-center">{{$loop->iteration}}</td>
                                            <td class="text-center">
                                                @if($data->tipe == 'Kegiatan') K-{{$data->dataID}} @else {{$data->dataID}} @endif
                                            </td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($data->tgl_transaksi)->format('d/m/Y') }}</td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($data->tgl_bayar)->format('d/m/Y') }}</td>
                                            <td class="text-center">{{$data->mak}}</td>
                                            <td class="text-left">{{$data->uraian}}</td>
                                            <td class="text-center">{{$data->nominal_bruto ?? 0}}</td>
                                            <td class="text-center">{{$data->potongan ?? 0}}</td>
                                            
                                                @if($data->nominal_bruto == 0)
                                                    <td>
                                                        <input type="hidden" name="tipe_{{$numpegawai}}" value="{{$data->tipe}}">
                                                        <input type="hidden" name="dataID_{{$numpegawai}}" value="{{$data->dataID}}">
                                                        <input type="hidden" name="idKeuangan_{{$numpegawai}}" value="{{$data->IdKeuangan}}">
                                                        <input readonly type="text" class="form-control textInput" name="spby_{{$numpegawai}}" value="{{$spbyNol}}">
                                                    </td>
                                                @else
                                                    <td>
                                                        <input type="hidden" name="tipe_{{$numpegawai}}" value="{{$data->tipe}}">
                                                        <input type="hidden" name="dataID_{{$numpegawai}}" value="{{$data->dataID}}">
                                                        <input type="hidden" name="idKeuangan_{{$numpegawai}}" value="{{$data->IdKeuangan}}">
                                                        <input type="text" class="form-control textInput" name="spby_{{$numpegawai}}" value="{{$data->no_spby}}">
                                                    </td>
                                                @endif
                                            
                                            <td>
                                                <input type="date" class="form-control dateInput" name="tglSpby_{{$numpegawai}}" value="{{$data->tgl_jurnal}}">
                                            </td>
                                        </tr>
                                        @php $numpegawai++; @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <input type="hidden" name="num_pegawai" value="{{$numpegawai}}">
                            <div class="container text-center mt-5 mb-5">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Simpan Pembaharuan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#warningModal').modal('show');
        });
    </script>
    <script>
        let allData = [];

// Fungsi untuk mengupdate data ke allData
function updateData(index, field, value) {
    if (!allData[index]) {
        allData[index] = {
            tipe: document.querySelector(`input[name='tipe_${index}']`).value,
            dataID: document.querySelector(`input[name='dataID_${index}']`).value,
            idKeuangan: document.querySelector(`input[name='idKeuangan_${index}']`).value,
            spby: '',
            tglSpby: ''
        };
    }
    allData[index][field] = value;
    document.getElementById("allData").value = JSON.stringify(allData);
}
    </script>
    <script>
document.addEventListener("DOMContentLoaded", function () {
    

    // Event listener untuk input No SPBY
    document.querySelectorAll("input[name^='spby_']").forEach((input, index) => {
        input.addEventListener("input", function () {
            updateData(index, 'spby', this.value);
        });
    });

    // Event listener untuk input Tanggal SPBY
    document.querySelectorAll("input[name^='tglSpby_']").forEach((input, index) => {
        input.addEventListener("change", function () {
            updateData(index, 'tglSpby', this.value);
        });
    });

    // Inisialisasi data awal
    document.querySelectorAll("input[name^='tipe_']").forEach((input, index) => {
        allData[index] = {
            tipe: input.value,
            dataID: document.querySelector(`input[name='dataID_${index}']`).value,
            idKeuangan: document.querySelector(`input[name='idKeuangan_${index}']`).value,
            spby: document.querySelector(`input[name='spby_${index}']`).value,
            tglSpby: document.querySelector(`input[name='tglSpby_${index}']`).value
        };
    });
    document.getElementById("allData").value = JSON.stringify(allData);
});
</script>

<script>
document.querySelector("form").addEventListener("submit", function (event) {
    // Pastikan allData sudah terupdate
    document.getElementById("allData").value = JSON.stringify(allData);
    console.log('Data yang dikirim:', allData); // Debugging
});
</script>

<script>
$(document).ready(function() {
    var statusValue = $('input[name="status"]').val();
    if (statusValue == 'belum') {
        // Event listener untuk perubahan di tglSpby_0
        $('input[name="tglSpby_0"]').on('change', function() {
            var selectedDate = $(this).val();

            // Update semua input tanggal
            $('input[name^="tglSpby_"]').each(function() {
                $(this).val(selectedDate);

                // Update juga di allData
                let nameAttr = $(this).attr("name");
                let match = nameAttr.match(/\d+/);
                if (match) {
                    let realIndex = parseInt(match[0]);
                    updateData(realIndex, 'tglSpby', selectedDate);
                }
            });

            console.log("Data setelah update:", allData); // Debugging
        });
    }
});
</script>




@endsection

