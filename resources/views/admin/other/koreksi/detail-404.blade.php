@extends('admin.templates.sidebar')

@section('main-class', 'custom-background')

@section('contain')
<div class="container-fluid d-flex justify-content-center align-items-center" style="background: #f8f9fa; min-height: 60vh;">
    <div class="warning-container shadow-lg rounded-4 border-0 p-4 text-center">
        <!-- Header -->
        <div class="warning-header text-white py-3">
            <i class="fas fa-exclamation-triangle fa-lg me-2"></i> Perhatian!
        </div>
        
        <!-- Body -->
        <div class="warning-body p-4">
            <h3 class="text-danger">Ajuan pada ID {{ ucwords($status) }} "{{ $id }}" tidak dapat dilakukan koreksi</h3>
            <p class="fs-5 text-dark mt-3">
                Berikut adalah alasan mengapa koreksi tidak bisa dilakukan:
            </p>

            <ul class="text-start list-group list-group-flush">
                @foreach(array_filter(explode(';', $alasan)) as $item)
                    <li class="list-group-item">
                        <i class="fas fa-times-circle text-danger"></i> {{ trim($item) }}
                    </li>
                @endforeach
            </ul>

        </div>

        <!-- Footer -->
        <div class="warning-footer d-flex justify-content-center mt-4">
            <a href="{{ url()->previous() }}" class="btn btn-lg btn-warning tombol-kembali">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<style>
.warning-container {
    max-width: 600px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    padding: 20px;
}

.warning-header {
    background: linear-gradient(135deg, #FF5733, #C70039);
    padding: 15px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    font-weight: bold;
}

.tombol-kembali {
    border-radius: 50px;
    padding: 12px 24px;
    font-size: 1.2rem;
    background-color: #FFC107;
    transition: all 0.3s ease-in-out;
}

.tombol-kembali:hover {
    background-color: #FF9800;
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
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
