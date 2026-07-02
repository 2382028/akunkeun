@extends('admin.templates.sidebar')

@section('contain')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="text-center flex-grow-1 mb-0">Rekapitulasi Data Kamar</h4>
    <a href="{{ route('kamar.rekapitulasi.pdf') }}" class="btn btn-danger btn-sm ms-3">
        <i class="fas fa-file-pdf me-1"></i> Cetak PDF
    </a>
</div>


    <div class="card card-body">
        <table class="table table-bordered">
            <thead class="text-center">
                <tr>
                    <th>No</th>
                    <th>Nomor Kamar</th>
                    <th>Lantai</th>
                    <th>Kategori</th>
                    <th>Tarif per Malam</th>
                    <th>Fasilitas</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kamars as $index => $kamar)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $kamar->nomor_kamar }}</td>
                        <td class="text-center">{{ $kamar->lantai }}</td>
                        <td>{{ $kamar->kategori->nama_kategori ?? '-' }}</td>
                        <td>Rp {{ number_format($kamar->harga_per_malam, 0, ',', '.') }}</td>
                        <td>
                            <ul class="mb-0 ps-3">
                                @foreach ($kamar->fasilitas as $fasilitas)
                                    <li>{{ $fasilitas->nama_fasilitas }} ({{ $fasilitas->pivot->jumlah }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="text-center">
                            @if ($kamar->status_kamar === 'available')
                                <span class="badge bg-success">Available</span>
                            @elseif ($kamar->status_kamar === 'maintenance')
                                <span class="badge bg-danger">Maintenance</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($kamar->status_kamar) }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
    <p><strong>Ringkasan Data Kamar:</strong></p>
    <p>
        Total kamar yang terdaftar: <strong>{{ $kamars->count() }}</strong><br>
        Jumlah kamar available: <strong>{{ $kamars->where('status_kamar', 'available')->count() }}</strong><br>
        Jumlah kamar maintenance: <strong>{{ $kamars->where('status_kamar', 'maintenance')->count() }}</strong><br>
</p>
    <p>
        Data di atas memberikan gambaran kondisi terkini dari seluruh kamar mess yang tersedia, lengkap dengan kategori, fasilitas, serta status penggunaannya.
    </p>
</div>

    </div>
</div>
@endsection
