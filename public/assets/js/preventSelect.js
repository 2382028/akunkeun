function validateSelect() {
    var selectElements = document.getElementsByClassName("required2");
    for (var i = 0; i < selectElements.length; i++) {
    var selectElement = selectElements[i];
    if (selectElement.value === "") {
        alert("Fasilitas tidak boleh kosong!");
        return false;
    }
    }
    return true;
}