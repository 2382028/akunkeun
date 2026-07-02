function setInputFasilitas(hasil) {
    var input = document.getElementById("fasilitasId");
    input.setAttribute("value", hasil);

    var input = document.getElementById("fasilitasIdNonPegawai");
    input.setAttribute("value", hasil);
}
$(document).ready(function () {
    $(".js-example-basic-single-nothing").select2({
        placeholder: "Select an option",
    });

    $(".js-example-basic-single-2").select2({
        placeholder: "Select an option",
        dropdownParent: "#tambah_peserta",
    });

    $(".js-example-basic-single-3").select2({
        placeholder: "Select an option",
        dropdownParent: "#tambah_sapras",
    });
});
