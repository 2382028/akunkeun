@extends('sewa.template') {{-- kalau kamu pakai layout utama --}}

@section('title', 'Panduan Pemesanan Kamar')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Panduan Pemesanan Kamar</h4>
        </div>
        <div class="card-body">
            <ol class="list-group list-group-numbered">
                <li class="list-group-item">
                    <b>Pilih Kamar, </b>
                    buka menu <i>Mess</i> lalu pilih tanggal dan kamar yang tersedia sesuai kebutuhan.
                </li>
                <li class="list-group-item">
                    <b>Ajukan Sewa, </b>
                    Klik tombol <i>Ajukan Sewa</i> pada kamar yang dipilih.
                </li>
                <li class="list-group-item">
                    <b>Isi Formulir, </b>
                    lengkapi data penyewa dengan mengisi metode pembayaran lalu klik <i>Buat Pesanan</i>.
                </li>
                <li class="list-group-item">
                    <b>Konfirmasi, </b>
                    buka menu <i>Pesanan Saya</i>, klik tombol <i>Kirim</i> pada pesanan yang diajukan.
                </li>
                <li class="list-group-item">
                    <b>Selesai, </b>
                    Jika disetujui, Anda akan menerima konfirmasi dan kamar bisa digunakan sesuai jadwal.
                </li>
            </ol>
        </div>
    </div>
</div>
@endsection
