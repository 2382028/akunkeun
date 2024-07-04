function validateForm() {
  var selectContainers = document.querySelectorAll("#tambah_peserta .required-select");
  for (var i = 0; i < selectContainers.length; i++) {
    var selectContainer = selectContainers[i];
    var selectElement = $(selectContainer).find("select");
    var selectedOption = selectElement.val();

    if (!selectedOption) {
      alert("Pegawai harus dipilih!");
      return false;
    }
  }
  return true;
}

$(".required-select select").select2({
    dropdownParent: $('#tambah_peserta')
  });

