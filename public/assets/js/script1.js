/* Page Active */
$(".content .page_content").hide();
$(".content .page_content:first-child").show();

$(".page-wrap").click(function(){
  var current_raido = $(this).attr("data-page");
  $(".content .page_content").hide();
  $("."+current_raido).show();
})

/* Tab Active */
var page = document.querySelectorAll(".page button");

page.forEach (button => {
    button.addEventListener("click",()=> {
        resetLinks();
        button.classList.add("active");
    })
})

function resetLinks() {
    page.forEach(button => {
        button.classList.remove("active")
    })
}

    /* Conditional Form: Tambah Fasilitas*/
    const uraian = document.getElementById('uraian');
        const conditionalFieldsHotel = document.getElementById('conditionalFieldsHotel');
        const conditionalFieldsTiketTransportasi = document.getElementById('conditionalFieldsTiketTransportasi');
        const conditionalFieldsBBM = document.getElementById('conditionalFieldsBBM');
        const conditionalFieldsKonsumsi = document.getElementById('conditionalFieldsKonsumsi');
        const conditionalFieldsTol = document.getElementById('conditionalFieldsTol');
        const conditionalFieldsLainnya = document.getElementById('conditionalFieldsLainnya');
        const conditionalFieldsTransportasi_Online = document.getElementById('conditionalFieldsTransportasi_Online');

    $(document).ready(function () {
        // Listen for changes in the select element
        $('#uraian').change(function () {
          // Get the selected value
          var selectedValue = $(this).val();

          // Clear any existing content in conditional_fields
          $('#conditional_fields').empty();

          // Check the selected value and append elements accordingly
          if (selectedValue === 'Akomodasi Hotel') {
            // Append elements for 'Akomodasi Hotel'
            $('#conditional_fields').append(`
              <div class="row">
                <div class="col-md-12 mb-3">
                  <label for="jumlah_kamar" class="form-label">Jumlah Kamar<span class="text-danger">*</span></label>
                  <input type="number" min="0" class="form-control" id="jumlah_kamar" name="jumlah_frekuensi" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Kamar" readonly>
                </div>
                <div class="col-md-12 mb-3">
                  <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                  <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                      <option value="Bayar diawal" selected>Dibayar di Awal</option>
                      <option value="Reimburse">Reimburse</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-12 mb-3">
                  <label for="" class="form-label">Keterangan Fasilitas<span class="text-danger">*</span><span class="text-secondary small">(Contoh : Tempat untuk penginapan)</span></label>
                  <input type="text" name="keterangan" id="" class="form-control" required>
                </div>
              </div>
            `);
          } else if (selectedValue === 'BBM') {
            // Append elements for 'BBM'
            $('#conditional_fields').append(`
            <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="jumlah_tiket" class="detail-fields">Jumlah Pengisian <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" min="0" aria-label="First name" class="form-control" name="jumlah_frekuensi">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Kali Pengisian" readonly>
                </div>
                        <div class="col-md-12 mb-3">
                            <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                                    <option value="Bayar diawal" selected>Dibayar di Awal</option>
                                    <option value="Reimburse">Reimburse</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Keterangan Fasilitas<span class="text-danger">*</span><span class="text-secondary small">(Contoh : Perkiraan Harga)</span></label>
                            <input type="text" name="keterangan" id="" class="form-control" required>
                        </div>
                    </div>
            `);
          } else if (selectedValue === 'Konsumsi') {
            // Append elements for 'Konsumsi'
            $('#conditional_fields').append(`
            <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="jumlah_konsumsi" class="detail-fields">Jumlah Konsumsi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" min="0" aria-label="First name" class="form-control" name="jumlah_frekuensi">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Pax" readonly>
                </div>
                        <div class="col-md-12 mb-3">
                            <label for="jumlah_konsumsi" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                                    <option value="Bayar diawal" selected>Dibayar di Awal</option>
                                    <option value="Reimburse">Reimburse</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Keterangan Fasilitas<span class="text-danger">*</span><span class="text-secondary small">(Contoh : Keperluan Konsumsi Makan Siang)</span></label>
                            <input type="text" name="keterangan" id="" class="form-control" required>
                        </div>
                    </div>
            `);
          } else if (selectedValue === 'Tiket Kereta') {
            $('#conditional_fields').append(`
            <div class="row">
            <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="form-label">Jumlah Tiket<span class="text-danger">*</span></label>
                <input type="number" min="0" class="form-control" id="jumlah_tiket" name="jumlah_frekuensi" required>
            </div>
        </div>
        <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Tiket" readonly>
                </div>
        <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                        <option value="Bayar di awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
        </div>
        <div class="col-md-12 mb-3">
            <label for="" class="form-label">Keterangan Fasilitas<span class="text-danger">*</span><span class="text-secondary small">(Contoh : Tiket Kereta,....)</span></label>
            <input type="text" name="keterangan" id="" class="form-control" required>
        </div>
            `);
        } else if (selectedValue === 'Tiket Pesawat') {
            $('#conditional_fields').append(`
            <div class="row">
            <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="form-label">Jumlah Tiket<span class="text-danger">*</span></label>
                <input type="number" min="0" class="form-control" id="jumlah_tiket" name="jumlah_frekuensi" required>
            </div>
        </div>
        <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Tiket" readonly>
                </div>
        <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                        <option value="Bayar di awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
        </div>
        <div class="col-md-12 mb-3">
            <label for="" class="form-label">Keterangan Fasilitas<span class="text-danger">*</span><span class="text-secondary small">(Contoh : Tiket Kereta,....)</span></label>
            <input type="text" name="keterangan" id="" class="form-control" required>
        </div>
            `);
        } else if (selectedValue === 'Tiket Travel') {
            $('#conditional_fields').append(`
            <div class="row">
            <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="form-label">Jumlah Tiket<span class="text-danger">*</span></label>
                <input type="number" min="0" class="form-control" id="jumlah_tiket" name="jumlah_frekuensi" required>
            </div>
        </div>
        <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Tiket" readonly>
                </div>
        <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                        <option value="Bayar di awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
        </div>
        <div class="col-md-12 mb-3">
            <label for="" class="form-label">Keterangan Fasilitas<span class="text-danger">*</span><span class="text-secondary small">(Contoh : Tiket Kereta,....)</span></label>
            <input type="text" name="keterangan" id="" class="form-control" required>
        </div>
            `);
          } else if (selectedValue === 'Transportasi Online') {
            $('#conditional_fields').append(`
            <div class="row">
            <div class="col-md-12 mb-3">
            <label for="jumlah_tiket" class="detail-fields">Jumlah Pejalanan <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" min="0" aria-label="First name" class="form-control" name="jumlah_frekuensi">
                </div>
            </div>
            <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Kali Perjalanan" readonly>
                </div>
            <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                        <option value="Bayar di awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <label for="" class="form-label">Keterangan Mobilitas<span class="text-danger">*</span><span class="text-secondary small">(Contoh :Bandung - Garut)</span></label>
                <input type="text" name="keterangan" id="" class="form-control" required>
            </div>
        </div>
            `);
          } else if (selectedValue === 'Lainnya') {
            $('#conditional_fields').append(`
            <div class="row">
            <div class="col-md-12 mb-3">
            <label for="jumlah_tiket" class="detail-fields">Jumlah <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" min="0" aria-label="First name" class="form-control" name="jumlah_frekuensi">
                </div>
            </div>
            <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" required>
                </div>
            <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                        <option value="Bayar di awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <label for="" class="form-label">Keterangan Fasilitas<span class="text-danger">*</span><span class="text-secondary small">(Contoh :Bandung - Garut)</span></label>
                <input type="text" name="keterangan" id="" class="form-control" required>
            </div>
        </div>
            `);
          } else if (selectedValue === 'Tol') {
            $('#conditional_fields').append(`
            <div class="row">
            <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="detail-fields">Jumlah Pengisian <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" min="0" aria-label="First name" class="form-control" name="jumlah_frekuensi">
                </div>
            </div>
            <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Kali Pengisian" readonly>
                </div>
            <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                        <option value="Bayar di awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <label for="" class="form-label">Keterangan Fasilitas <span class="text-danger">*</span><span class="text-secondary small">(Contoh : Perkiraan Harga)</span></label>
                <input type="text" name="keterangan" id="" class="form-control" required>
            </div>
        </div>
            `);
          }
        });
    });

    /* Conditional Form: Tambah Fasilitas*/
    const uraian_pelaksana = document.getElementById('uraian_pelaksana');
        const conditionalFieldsHotel_pelaksana = document.getElementById('conditionalFieldsHotel_pelaksana');
        const conditionalFieldsTiketTransportasi_pelaksana = document.getElementById('conditionalFieldsTiketTransportasi');
        const conditionalFieldsBBM_pelaksana = document.getElementById('conditionalFieldsBBM');
        const conditionalFieldsKonsumsi_pelaksana = document.getElementById('conditionalFieldsKonsumsi');
        const conditionalFieldsTol_pelaksana = document.getElementById('conditionalFieldsTol');
        const conditionalFieldsLainnya_pelaksana = document.getElementById('conditionalFieldsLainnya');
        const conditionalFieldsTransportasi_Online_pelaksana = document.getElementById('conditionalFieldsTransportasi_Online');

    $(document).ready(function () {
        // Listen for changes in the select element
        $('#uraian_pelaksana').change(function () {
          // Get the selected value
          var selectedValue = $(this).val();

          // Clear any existing content in conditional_fields
          $('#conditional_fields_pelaksana').empty();

          // Check the selected value and append elements accordingly
          if (selectedValue === 'Akomodasi Hotel') {
            // Append elements for 'Akomodasi Hotel'
            $('#conditional_fields_pelaksana').append(`
              <div class="row">
                <div class="col-md-12 mb-3">
                  <label for="jumlah_kamar" class="form-label">Jumlah Kamar<span class="text-danger">*</span></label>
                  <input type="number" min="0" class="form-control" id="jumlah_kamar" name="jumlah_frekuensi" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Kamar" readonly>
                </div>
                <div class="col-md-12 mb-3">
                  <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                  <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                      <option value="Bayar diawal" selected>Dibayar di Awal</option>
                      <option value="Reimburse">Reimburse</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-12 mb-3">
                  <label for="" class="form-label">Keterangan Fasilitas<span class="text-danger">*</span><span class="text-secondary small">(Contoh : Tempat untuk penginapan)</span></label>
                  <input type="text" name="keterangan" id="" class="form-control" required>
                </div>
              </div>
            `);
          } else if (selectedValue === 'BBM') {
            // Append elements for 'BBM'
            $('#conditional_fields_pelaksana').append(`
            <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="jumlah_tiket" class="detail-fields">Jumlah Pengisian <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" min="0" aria-label="First name" class="form-control" name="jumlah_frekuensi">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Kali Pengisian" readonly>
                </div>
                        <div class="col-md-12 mb-3">
                            <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                                    <option value="Bayar diawal" selected>Dibayar di Awal</option>
                                    <option value="Reimburse">Reimburse</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Keterangan Fasilitas<span class="text-danger">*</span><span class="text-secondary small">(Contoh : Perkiraan Harga)</span></label>
                            <input type="text" name="keterangan" id="" class="form-control" required>
                        </div>
                    </div>
            `);
          } else if (selectedValue === 'Konsumsi') {
            // Append elements for 'Konsumsi'
            $('#conditional_fields_pelaksana').append(`
            <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="jumlah_konsumsi" class="detail-fields">Jumlah Konsumsi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" min="0" aria-label="First name" class="form-control" name="jumlah_frekuensi">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Pax" readonly>
                </div>
                        <div class="col-md-12 mb-3">
                            <label for="jumlah_konsumsi" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                                    <option value="Bayar diawal" selected>Dibayar di Awal</option>
                                    <option value="Reimburse">Reimburse</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Keterangan Fasilitas<span class="text-danger">*</span><span class="text-secondary small">(Contoh : Keperluan Konsumsi Makan Siang)</span></label>
                            <input type="text" name="keterangan" id="" class="form-control" required>
                        </div>
                    </div>
            `);
          } else if (selectedValue === 'Tiket Kereta') {
            $('#conditional_fields_pelaksana').append(`
            <div class="row">
            <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="form-label">Jumlah Tiket<span class="text-danger">*</span></label>
                <input type="number" min="0" class="form-control" id="jumlah_tiket" name="jumlah_frekuensi" required>
            </div>
        </div>
        <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Tiket" readonly>
                </div>
        <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                        <option value="Bayar di awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
        </div>
        <div class="col-md-12 mb-3">
            <label for="" class="form-label">Keterangan Fasilitas<span class="text-danger">*</span><span class="text-secondary small">(Contoh : Tiket Kereta,....)</span></label>
            <input type="text" name="keterangan" id="" class="form-control" required>
        </div>
            `);
        } else if (selectedValue === 'Tiket Pesawat') {
            $('#conditional_fields_pelaksana').append(`
            <div class="row">
            <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="form-label">Jumlah Tiket<span class="text-danger">*</span></label>
                <input type="number" min="0" class="form-control" id="jumlah_tiket" name="jumlah_frekuensi" required>
            </div>
        </div>
        <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Tiket" readonly>
                </div>
        <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                        <option value="Bayar di awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
        </div>
        <div class="col-md-12 mb-3">
            <label for="" class="form-label">Keterangan Fasilitas<span class="text-danger">*</span><span class="text-secondary small">(Contoh : Tiket Kereta,....)</span></label>
            <input type="text" name="keterangan" id="" class="form-control" required>
        </div>
            `);
        } else if (selectedValue === 'Tiket Travel') {
            $('#conditional_fields_pelaksana').append(`
            <div class="row">
            <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="form-label">Jumlah Tiket<span class="text-danger">*</span></label>
                <input type="number" min="0" class="form-control" id="jumlah_tiket" name="jumlah_frekuensi" required>
            </div>
        </div>
        <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Tiket" readonly>
                </div>
        <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                        <option value="Bayar di awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
        </div>
        <div class="col-md-12 mb-3">
            <label for="" class="form-label">Keterangan Fasilitas<span class="text-danger">*</span><span class="text-secondary small">(Contoh : Tiket Kereta,....)</span></label>
            <input type="text" name="keterangan" id="" class="form-control" required>
        </div>
            `);
          } else if (selectedValue === 'Transportasi Online') {
            $('#conditional_fields_pelaksana').append(`
            <div class="row">
            <div class="col-md-12 mb-3">
            <label for="jumlah_tiket" class="detail-fields">Jumlah Pejalanan <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" min="0" aria-label="First name" class="form-control" name="jumlah_frekuensi">
                </div>
            </div>
            <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Kali Perjalanan" readonly>
                </div>
            <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                        <option value="Bayar di awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <label for="" class="form-label">Keterangan Mobilitas<span class="text-danger">*</span><span class="text-secondary small">(Contoh :Bandung - Garut)</span></label>
                <input type="text" name="keterangan" id="" class="form-control" required>
            </div>
        </div>
            `);
          } else if (selectedValue === 'Lainnya') {
            $('#conditional_fields_pelaksana').append(`
            <div class="row">
            <div class="col-md-12 mb-3">
            <label for="jumlah_tiket" class="detail-fields">Jumlah <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" min="0" aria-label="First name" class="form-control" name="jumlah_frekuensi">
                </div>
            </div>
            <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" required>
                </div>
            <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                        <option value="Bayar di awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <label for="" class="form-label">Keterangan Fasilitas<span class="text-danger">*</span><span class="text-secondary small">(Contoh :Bandung - Garut)</span></label>
                <input type="text" name="keterangan" id="" class="form-control" required>
            </div>
        </div>
            `);
          } else if (selectedValue === 'Tol') {
            $('#conditional_fields_pelaksana').append(`
            <div class="row">
            <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="detail-fields">Jumlah Pengisian <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" min="0" aria-label="First name" class="form-control" name="jumlah_frekuensi">
                </div>
            </div>
            <div class="col-md-12 mb-3">
                    <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="Kali Pengisian" readonly>
                </div>
            <div class="col-md-12 mb-3">
                <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                        <option value="Bayar di awal" selected>Dibayar di Awal</option>
                        <option value="Reimburse">Reimburse</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <label for="" class="form-label">Keterangan Fasilitas <span class="text-danger">*</span><span class="text-secondary small">(Contoh : Perkiraan Harga)</span></label>
                <input type="text" name="keterangan" id="" class="form-control" required>
            </div>
        </div>
            `);
          }
        });
    });

    $(document).ready(function() {
        let refFasilitasData = [];

        // Ambil data dari Laravel backend
        $.ajax({
            url: '/get-data-fasilitas',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                refFasilitasData = data; // Simpan data ke variabel global
            },
            error: function(error) {
                console.error('Error fetching data:', error);
            }
        });

        // Event listener untuk perubahan nilai select
        $('#uraian').change(function() {
            const selectedValue = $(this).val();

            // Cari data yang sesuai dengan selectedValue
            const selectedFasilitas = refFasilitasData.find(fasilitas => fasilitas.nama_fasilitas === selectedValue);

            // Jika ditemukan, gunakan satuan dari data tersebut
            const satuanValue = selectedFasilitas ? selectedFasilitas.satuan : 'Kali';

            // Bersihkan konten sebelumnya
            $('#conditional_fields').empty();

            // Tambahkan HTML ke #conditional_fields
            $('#conditional_fields').html(`
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="jumlah_tiket" class="detail-fields">Jumlah <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" min="0" aria-label="First name" class="form-control" name="jumlah_frekuensi" required>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="satuan" name="satuan" value="${satuanValue}" readonly>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                                <option value="Bayar di awal" selected>Dibayar di Awal</option>
                                <option value="Reimburse">Reimburse</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="" class="form-label">Keterangan Fasilitas <span class="text-danger">*</span><span class="text-secondary small">(Contoh : Perkiraan Harga)</span></label>
                        <input type="text" name="keterangan" id="" class="form-control" required>
                    </div>
                </div>
            `);
        });
    });

    $(document).ready(function() {
        let refFasilitasData = [];

        // Ambil data dari Laravel backend
        $.ajax({
            url: '/get-data-fasilitas',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                refFasilitasData = data; // Simpan data ke variabel global
            },
            error: function(error) {
                console.error('Error fetching data:', error);
            }
        });

        // Event listener untuk perubahan nilai select
        $('#uraian_pelaksana').change(function() {
            const selectedValue = $(this).val();

            // Cari data yang sesuai dengan selectedValue
            const selectedFasilitas = refFasilitasData.find(fasilitas => fasilitas.nama_fasilitas === selectedValue);

            // Jika ditemukan, gunakan satuan dari data tersebut
            const satuanValue = selectedFasilitas ? selectedFasilitas.satuan : 'Kali';

            // Bersihkan konten sebelumnya
            $('#conditional_fields_pelaksana').empty();

            // Tambahkan HTML ke #conditional_fields
            $('#conditional_fields_pelaksana').html(`
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="jumlah_tiket" class="detail-fields">Jumlah <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" min="0" aria-label="First name" class="form-control" name="jumlah_frekuensi" required>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="satuan" name="satuan" value="${satuanValue}" readonly>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="jumlah_tiket" class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select class="form-select" aria-label="Default select example" name="tipe_pendanaan">
                                <option value="Bayar di awal" selected>Dibayar di Awal</option>
                                <option value="Reimburse">Reimburse</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="" class="form-label">Keterangan Fasilitas <span class="text-danger">*</span><span class="text-secondary small">(Contoh : Perkiraan Harga)</span></label>
                        <input type="text" name="keterangan" id="" class="form-control" required>
                    </div>
                </div>
            `);
        });
    });

    // Control Tanggal Keberangkatan & Tanggal Mulai Acara di Perjadin Langsung/
    // Menangkap elemen-elemen yang diperlukan
    var konfirmasiRadio = document.querySelectorAll('input[name="konfirmasi"]');
    var tglKeberangkatanInput = document.getElementById('tgl_keberangkatan');
    var tglMulaiInput = document.getElementById('tgl_mulai');
    var tidakRadio = document.getElementById('tidak');

    // Tambahkan event listener untuk setiap radio button
    konfirmasiRadio.forEach(function (radio) {
        radio.addEventListener('change', function () {
            if (radio.value === 'ya') {
                // Jika user memilih 'Ya', salin nilai dari satu input ke input yang lain
                tglMulaiInput.value = tglKeberangkatanInput.value;
            }
        });
    });

    // Tambahkan event listener untuk input tanggal keberangkatan
    tglKeberangkatanInput.addEventListener('change', function () {
        if (document.querySelector('input[name="konfirmasi"]:checked').value === 'ya') {
            // Jika user memilih 'Ya', salin nilai tanggal keberangkatan ke tanggal mulai acara
            tglMulaiInput.value = tglKeberangkatanInput.value;
        }
    });

    // Tambahkan event listener untuk input tanggal mulai acara
    tglMulaiInput.addEventListener('change', function () {
        if (document.querySelector('input[name="konfirmasi"]:checked').value === 'ya') {
            // Jika user memilih 'Ya', salin nilai tanggal mulai acara ke tanggal keberangkatan
            tglKeberangkatanInput.value = tglMulaiInput.value;
        }
    });

    // Tambahkan event listener untuk radio button 'Tidak'
    tidakRadio.addEventListener('change', function () {
        if (tidakRadio.checked) {
            // Jika user memilih 'Tidak', hapus nilai dari kedua input tanggal
            tglKeberangkatanInput.value = '';
            tglMulaiInput.value = '';
        }
    });
