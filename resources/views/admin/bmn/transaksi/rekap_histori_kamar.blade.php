@extends('admin.templates.sidebar')

@section('contain')
@php
    use Carbon\Carbon;
    Carbon::setLocale('id');
@endphp

<div class="container">
    <h4 class="text-center">Rekapitulasi Histori Penyewaan Kamar</h4>

    {{-- Filter Tanggal --}}
    <div class="card card-body mb-4">
        <form method="GET" class="row g-2 d-flex align-items-end">
            <div class="col-auto">
                <label for="start_date">Dari</label>
                <input type="date" id="start_date" name="start_date" value="{{ request('start_date', $start->format('Y-m-d')) }}" class="form-control">
            </div>
            <div class="col-auto">
                <label for="end_date">Sampai</label>
                <input type="date" id="end_date" name="end_date" value="{{ request('end_date', $end->format('Y-m-d')) }}" class="form-control">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </div>
            <div class="col-auto ms-auto">
    <a href="{{ route('histori_kamar.rekapitulasi.pdf', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" target="_blank" class="btn btn-outline-danger">
        <i class="fas fa-file-pdf"></i> Cetak PDF
    </a>
</div>

        </form>
    </div>

    {{-- Tabel Data --}}
    <div class="card card-body">
        <table class="table table-bordered">
            <thead class="text-center">
                <tr>
                    <th>No</th>
                    <th>Nomor Kamar</th>
                    <th>Kategori</th>
                    <th>Tarif per Malam</th>
                    <th>Jumlah Tersewa (malam)</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach ($kamars as $index => $kamar)
                    @php $grandTotal += $kamar->total; @endphp
                    <tr class="text-center">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $kamar->nomor_kamar }}</td>
                        <td>{{ $kamar->kategori->nama_kategori ?? '-' }}</td>
                        <td>Rp {{ number_format($kamar->harga_per_malam, 0, ',', '.') }}</td>
                        <td>{{ $kamar->jumlah_tersewa }}</td>
                        <td>Rp {{ number_format($kamar->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-end">Total Keseluruhan</th>
                    <th class="text-center">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <div class="mt-4">
            <p><strong>Rekap ini menampilkan histori penyewaan kamar dari tanggal {{ $start->translatedFormat('d F Y') }} sampai {{ $end->translatedFormat('d F Y') }}.</strong></p>
            <p><strong>Total penerimaan berdasarkan histori penyewaan kamar: Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></p>
        </div>
    </div>
</div>
@endsection
