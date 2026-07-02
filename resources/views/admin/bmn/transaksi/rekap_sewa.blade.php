@extends('admin.templates.sidebar')

@section('contain')
    @php
        use Carbon\Carbon;
        Carbon::setLocale('id');
        $bulanAwal = Carbon::parse($start)->translatedFormat('F');
        $bulanAkhir = Carbon::parse($end)->translatedFormat('F');
        $tahun = Carbon::parse($start)->format('Y');
    @endphp

    <div class="container">
        <h4>Rekapitulasi Pemesanan Sewa Mess</h4>

        {{-- Card untuk Form Filter --}}
        <div class="card card-body mb-4">
            <form method="GET" class="row g-2 d-flex align-items-end">
                <div class="col-auto">
                    <label>Dari</label>
                    <input type="month" name="start_date" value="{{ request('start_date', date('Y-m')) }}"
                        class="form-control">
                </div>
                <div class="col-auto">
                    <label>Sampai</label>
                    <input type="month" name="end_date" value="{{ request('end_date', date('Y-m')) }}"
                        class="form-control">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                </div>
                <div class="col-auto ms-auto">
                    <a href="{{ route('penyewaan_aset.rekapitulasi.pdf', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                        class="btn btn-danger">
                        Cetak PDF
                    </a>
                </div>
            </form>
        </div>

        {{-- Card untuk Tabel dan Deskripsi --}}
        <div class="card card-body">
<table class="table table-bordered">
    <thead class="text-center">
        <tr>
            <th>No</th>
            <th>Bulan</th>
            <th>Kategori Kamar</th>
            <th>Tarif per malam</th>
            <th>Jumlah Tersewa (malam)</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rekap as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">
                    {{ Carbon::createFromFormat('Y-m', $row['bulan'])->translatedFormat('F Y') }}
                </td>
                <td>{{ $row['nama_kategori'] }}</td>
                <td>Rp {{ number_format($row['tarif'], 0, ',', '.') }}</td>
                <td class="text-center">{{ $row['total_malam'] }}</td>
                <td>Rp {{ number_format($row['jumlah'], 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5" class="text-end">Total</th>
            <th>Rp {{ number_format($rekap->sum('jumlah'), 0, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>

            <div class="mt-4">
    <p><strong>Penerimaan Sewa Mess dari {{ $bulanAwal }} sampai dengan {{ $bulanAkhir }} tahun {{ $tahun }} adalah sebagai berikut:</strong></p>
    <ol>
        @foreach ($rekap as $row)
            <li>
                Kategori <strong>{{ $row['nama_kategori'] }}</strong> dengan tarif <strong>Rp {{ number_format($row['tarif'], 0, ',', '.') }}</strong>
                disewa selama <strong>{{ $row['total_malam'] }}</strong> malam
                dengan jumlah <strong>Rp {{ number_format($row['jumlah'], 0, ',', '.') }}</strong>
            </li>
        @endforeach
    </ol>
    <p>
        <strong>Total Penerimaan Sewa Mess dari {{ $bulanAwal }} sampai dengan {{ $bulanAkhir }} tahun {{ $tahun }} adalah sebesar Rp {{ number_format(collect($rekap)->sum('jumlah'), 0, ',', '.') }}</strong>
    </p>
</div>

        </div>
    </div>
@endsection
