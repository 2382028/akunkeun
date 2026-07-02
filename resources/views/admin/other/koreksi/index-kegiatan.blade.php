@extends('admin.templates.sidebar')

@section('main-class', 'custom-background')
@section('contain')
<div class="container-fluid" style="background: #f8f9fa; min-height: 100vh;">
    <!-- Bagian Peringatan (Menggantikan Modal) -->
    <div class="warning-container shadow-lg rounded-4 border-0 p-4 mt-4">
        <!-- Header -->
        <div class="warning-header bg-gradient-warning text-white py-3 text-center fw-bold">
            <i class="fas fa-exclamation-triangle fa-lg me-2"></i> Perhatian!
        </div>
        
        <!-- Body -->
        <div class="warning-body text-center p-4">
            <p class="fs-5 text-dark">
                Pastikan untuk <b>memasukkan ID {{ strtoupper($status) }}</b> yang benar untuk melakukan koreksi data.
            </p>
            <hr>
            <p class="fs-5 text-danger">
                <i class="fas fa-exclamation-circle"></i> Masukkan ID yang valid untuk melanjutkan. <br>
                ID hanya dapat berupa angka.
            </p>
            <div class="form-group mt-3">
                <input type="text" id="inputId" class="form-control" placeholder="Masukkan ID" pattern="\d*" required>
            </div>
        </div>

        <!-- Footer -->
        <div class="warning-footer d-flex justify-content-center">
            <button type="button" class="btn btn-lg btn-warning tombol-mengerti" id="cekDataButton">
                <i class="fas fa-check-circle"></i> Cek Data
            </button>
        </div>
    </div>
</div>

<!-- Tambahkan SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    let status = "{{$status}}";
    let cekDataButton = document.getElementById("cekDataButton");
    let inputId = document.getElementById("inputId");

    function cekData() {
        let idValue = inputId.value.trim();
        if (!/^\d+$/.test(idValue)) {
            Swal.fire({
                icon: "error",
                title: "Input Tidak Valid!",
                text: "ID hanya boleh berupa angka.",
                confirmButtonColor: "#ff9800",
                confirmButtonText: "Mengerti",
                backdrop: true // Ini akan membuat overlay penuh
            });

            return;
        }
        window.location.href = `/koreksi-detail/${status}/${idValue}`;
    }

    cekDataButton.addEventListener("click", cekData);
    inputId.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            cekData();
        }
    });
});
</script>

<style>
.warning-container {
    max-width: 600px;
    margin: 0 auto;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.warning-header {
    background: linear-gradient(135deg, #FFC107, #FF5722);
    padding: 15px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

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
.swal2-container {
    z-index: 2000 !important; /* Pastikan SweetAlert di atas elemen lain */
}

</style>
@endsection
<style>
    .custom-background {
    background: #f8f9fa !important;
    margin-top: 0 !important;
    padding-top: 0 !important;
    min-height: 100vh;
}

</style>