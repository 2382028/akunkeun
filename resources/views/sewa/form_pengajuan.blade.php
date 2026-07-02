@extends('sewa.template')
@section('content')
    <section class="py-5">
        <div class="container">
            <div class="card mx-auto" style="max-width:600px">
                <div class="card-body">
                    <h4 class="mb-4">Form Detail Pemesanan</h4>
                    <form action="{{ url('/sewa/pengajuan/submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label>Nama Penyewa</label>
                            <input type="text" class="form-control" value="{{ $penyewa->penyewa->nama_lengkap }}"
                                readonly>
                        </div>
                        <div class="mb-3">
                            <label>No Telepon</label>
                            <input type="text" class="form-control" value="{{ $penyewa->penyewa->no_telepon }}"
                                readonly>
                        </div>
                        <div class="mb-3">
                            <label>Jadwal</label>
                            <input type="text" class="form-control"
                                value="{{ \Carbon\Carbon::parse($start)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($end)->format('d-m-Y') }} ({{ $nights }} malam)"
                                readonly>
                            <div class="form-text">
                                Check-in mulai pukul <strong>14.00 WIB</strong> dan check-out maksimal pukul <strong>13.00
                                    WIB</strong>.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Detail Kamar</label>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tipe</th>
                                            <th>Jumlah</th>
                                            <th>Harga/malam</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($groupedRooms as $roomType => $details)
                                            @php $firstRow = true; @endphp
                                            @foreach ($details as $item)
                                                <tr>
                                                    <td>
                                                        @if ($firstRow)
                                                            {{ $roomType }}
                                                            @php $firstRow = false; @endphp
                                                        @endif
                                                    </td>
                                                    <td>{{ $item['quantity'] }}</td>
                                                    <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Total Harga</label>
                            <input type="text" class="form-control fs-5 text-success fw-bold"
                                value="Rp {{ number_format($totalPrice, 0, ',', '.') }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pilih Metode Pembayaran</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metode_pembayaran" id="cashOption" value="cash" required>
                                <label class="form-check-label" for="cashOption">
                                    Cash
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metode_pembayaran" id="transferOption" value="transfer" required>
                                <label class="form-check-label" for="transferOption">
                                    Transfer
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Buat Pesanan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
