@extends('sewa.template')

@section('content')
    <style>
        .filter-btn {
            background: none !important;
            border: none;
            padding: 0.5rem 1rem;
            margin: 0 0.5rem;
            font-size: 1rem;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.3s ease;
            color: inherit;
        }

        .filter-btn,
        .filter-btn:hover,
        .filter-btn:focus,
        .filter-btn.active {
            background-color: transparent !important;
            color: inherit !important;
            box-shadow: none !important;
        }

        .filter-btn-group {
            display: flex;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .filter-btn.active {
            border-bottom: 2px solid #0d6efd;
        }

        .filter-btn:focus {
            outline: none;
        }
    </style>
    @php
        $defaultTab = request('default_tab', session('default_tab', 'berlangsung'));
    @endphp

    @if (session('success'))
        <!-- Modal -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="successModalLabel">Berhasil!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        {{ session('success') }}<br>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('pesanan.saya') }}?default_tab={{ session('default_tab', 'berlangsung') }}"
                            class="btn btn-primary">OK</a>
                    </div>
                </div>
            </div>
        </div>
        <script>
            window.addEventListener('load', function() {
                var myModal = new bootstrap.Modal(document.getElementById('successModal'));
                myModal.show();
            });
        </script>
    @endif

    <div class="container py-4">
        <h3>Pesanan Saya</h3>
        <div class="alert alert-warning" role="alert">
            <strong>Perhatian :</strong> Check-in mulai pukul 14.00 WIB dan check-out maksimal pukul
            13.00 WIB.
        </div>
        <div class="filter-btn-group" role="group" aria-label="Filter Status">
            <button type="button" class="filter-btn" id="btn-draft">Draft</button>
            <button type="button" class="filter-btn" id="btn-berlangsung">Berlangsung</button>
            <button type="button" class="filter-btn" id="btn-selesai">Selesai</button>
            <button type="button" class="filter-btn" id="btn-dibatalkan">Dibatalkan</button>
        </div>
        @foreach ($pesanan as $p)
            @php
                $isSelesai = in_array($p->status, ['selesai', 'ditolak']);
            @endphp


            <div
    class="card mb-4 shadow-sm
    @if ($p->status === 'draft' || $p->status === 'verifikasi' || $p->status === 'menunggu') status-draft
    @elseif ($p->status === 'dibatalkan') status-dibatalkan
    @elseif ($p->status === 'dibatalkan refund') status-dibatalkan-refund
    @elseif ($isSelesai) status-selesai
    @else status-berlangsung @endif">


                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <strong class="me-auto">Kode: {{ $p->kode_pemesanan }}</strong>

                    <span class="badge bg-info text-capitalize mx-3">
                        {{ $p->status }}
                    </span>

                    <a href="#detail-{{ $p->kode_pemesanan }}" data-bs-toggle="collapse"
                       class="small text-decoration-none text-primary fw-bold">
                        Detail <i class="bi bi-chevron-down"></i>
                    </a>
                </div>

                <div class="card-body" style="padding-top: 0%;">
                    @if ($p->status === 'dibatalkan refund')
                        @php
                            $pembatalan = $p->pembatalanSewa; // pastikan relasi sudah didefinisikan
                        @endphp

                        @if ($pembatalan && $pembatalan->bukti_refund)
                            <a href="{{ route('refund.lihat', $p->kode_pemesanan) }}" target="_blank" class="btn btn-sm btn-primary"
                                style="margin-top: 0.7em">
                                <i class="fa fa-eye"></i> Lihat Bukti Refund
                            </a>
                            <p class="text-muted small">
                                <strong>Alasan Pembatalan:</strong> {{ $p->pembatalanSewa->alasan_pembatalan }}
                            </p>
                        @endif
                    @endif

                    @if ($p->status === 'diterima' && $p->pembayaran->invoice)
                        <a href="{{ route('invoice.download', $p->kode_pemesanan) }}" class="btn btn-sm btn-primary"
                            style="margin-top: 0.7em">
                            <i class="fa fa-file-invoice"></i> Unduh Invoice
                        </a>
                    @elseif($p->status === 'verifikasi' && $p->pembayaran->url_path)
                        <a href="{{ route('bukti.download', $p->kode_pemesanan) }}" class="btn btn-sm btn-secondary"
                            style="margin-top: 0.7em">
                            <i class="fa fa-file-alt"></i> Unduh Bukti Pemesanan
                        </a>
                    @endif

                    @if ($p->status === 'draft')
                        @php
                            $expiredAt = \Carbon\Carbon::parse($p->created_at)->addHours(24);
                        @endphp

                        <div class="mt-2 text-danger fw-bold small">
                            <strong>Konfirmasi pesanan sebelum :</strong>
                            <span id="countdown-{{ $p->kode_pemesanan }}" data-expired="{{ $expiredAt->timestamp }}"></span>
                        </div>
                    @endif


                    <p><strong>Periode:</strong> {{ \Carbon\Carbon::parse($p->tanggal_checkin)->format('d-m-Y') }} s/d
                        {{ \Carbon\Carbon::parse($p->tanggal_checkout)->format('d-m-Y') }}
                        ({{ \Carbon\Carbon::parse($p->tanggal_checkin)->diffInDays(\Carbon\Carbon::parse($p->tanggal_checkout)) }}
                        malam)
                    </p>
                    <p><strong>Total Harga:</strong> Rp {{ number_format($p->subtotal, 0, ',', '.') }}</p>
                    <p><strong>Metode Pembayaran:</strong> {{ ucfirst($p->pembayaran->metode_pembayaran ?? '-') }}</p>
                    @if ($p->status === 'ditolak' && $p->penolakan)
                        <div class="alert alert-danger mt-3">
                            <strong>Alasan Penolakan:</strong> {{ $p->penolakan->alasan_penolakan }}
                        </div>
                    @endif

                    <div class="collapse mt-3" id="detail-{{ $p->kode_pemesanan }}">
                        <ul>
                            @foreach ($p->getRelation('detailKamar') as $dk)
                                <li>
                                    {{ $dk->nama_kategori }} - Kamar No: {{ $dk->nomor_kamar }}
                                    (Rp {{ number_format($dk->subtotal, 0, ',', '.') }})
                                </li>
                            @endforeach
                        </ul>


                        @if ($p->status === 'draft')
                            @if ($p->pembayaran->metode_pembayaran === 'transfer')
                                @if (!$p->bukti_transfer)
                                    <p><strong>Silakan transfer ke rekening A/N xxx</strong></p>
                                    <form id="uploadForm-{{ $p->kode_pemesanan }}"
                                        action="{{ route('pesanan.upload', $p->kode_pemesanan) }}" method="POST"
                                        enctype="multipart/form-data" class="mb-3">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Upload Bukti Pembayaran</label>
                                            <input type="file" name="bukti_transfer"
                                                id="buktiTransfer-{{ $p->kode_pemesanan }}" class="form-control" required
                                                accept=".jpg,.jpeg,.png,.pdf">
                                            <div class="text-danger small" id="fileError-{{ $p->kode_pemesanan }}"></div>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-success">Kirim</button>
                                    </form>


                                    <form action="{{ route('pesanan.batalkan', $p->kode_pemesanan) }}" method="POST"
                                        class="form-batalkan" data-id="{{ $p->kode_pemesanan }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-batalkan"
                                            data-id="{{ $p->kode_pemesanan }}">
                                            Batalkan
                                        </button>
                                    </form>

                    </div>
                @else
                    <p><strong>Bukti pembayaran telah diunggah.</strong></p>
        @endif
        @elseif ($p->pembayaran->metode_pembayaran === 'cash')
        <p><strong>Silakan melakukan pembayaran kepada petugas di lokasi</strong></p>

        <div class="d-flex gap-2">
            <!-- Tombol Buka Modal -->
            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                data-bs-target="#confirmModal-{{ $p->kode_pemesanan }}">
                Kirim
            </button>

            <!-- Tombol Batalkan -->
            <form action="{{ route('pesanan.batalkan', $p->kode_pemesanan) }}" method="POST"
                onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Batalkan</button>
            </form>
        </div>

        <!-- Modal Konfirmasi -->
        <div class="modal fade" id="confirmModal-{{ $p->kode_pemesanan }}" tabindex="-1"
            aria-labelledby="confirmModalLabel-{{ $p->kode_pemesanan }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalLabel-{{ $p->kode_pemesanan }}">
                            Konfirmasi Pesanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Nama:</strong> {{ $p->nama_penyewa ?? '-' }}</p>
                        <p><strong>Periode:</strong>
                            {{ \Carbon\Carbon::parse($p->tanggal_checkin)->format('d-m-Y') }} s/d
                            {{ \Carbon\Carbon::parse($p->tanggal_checkout)->format('d-m-Y') }}</p>
                        <p><strong>Total Harga:</strong> Rp
                            {{ number_format($p->subtotal, 0, ',', '.') }}</p>
                        <p>Apakah Anda yakin ingin mengkonfirmasi pesanan ini dengan metode
                            pembayaran <strong>cash</strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('pesanan.konfirmasi.cash', $p->kode_pemesanan) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Kirim Pesanan</button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endif
    </div>
    </div>
    </div>
    @endforeach
    </div>



    <script>
document.addEventListener('DOMContentLoaded', function () {

    // === Upload Bukti Transfer Validation ===
    document.querySelectorAll('form[id^="uploadForm-"]').forEach(form => {
        const input = form.querySelector('input[type="file"]');
        const errorDiv = form.querySelector('[id^="fileError-"]');

        form.addEventListener('submit', function (e) {
            const file = input?.files[0];
            if (file) {
                const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                const maxSize = 2 * 1024 * 1024; // 2MB

                if (!allowedTypes.includes(file.type)) {
                    e.preventDefault();
                    errorDiv.innerText = 'Format file harus JPG, PNG, atau PDF.';
                    return;
                }

                if (file.size > maxSize) {
                    e.preventDefault();
                    errorDiv.innerText = 'Ukuran file maksimal 2MB.';
                    return;
                }

                errorDiv.innerText = ''; // clear error
            }
        });
    });

    // === Batalkan Pesanan (SweetAlert) ===
    const batalkanButtons = document.querySelectorAll('.btn-batalkan');
    batalkanButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const form = document.querySelector(`.form-batalkan[data-id="${id}"]`);

            Swal.fire({
                title: "Yakin ingin membatalkan pesanan ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, batalkan!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // === Filter Buttons ===
    const btnDraft = document.getElementById('btn-draft');
    const btnBerlangsung = document.getElementById('btn-berlangsung');
    const btnSelesai = document.getElementById('btn-selesai');
    const btnDibatalkan = document.getElementById('btn-dibatalkan');
    const cards = document.querySelectorAll('.card');

function filterStatus(status) {
    cards.forEach(card => {
        if (status === 'dibatalkan') {
            const isDibatalkan = card.classList.contains('status-dibatalkan')
                || card.classList.contains('status-dibatalkan-refund');
            card.style.display = isDibatalkan ? 'block' : 'none';
        } else if (status === 'draft') {
            // draft, verifikasi, menunggu disatukan jadi status-draft
            const isDraftLike = card.classList.contains('status-draft');
            card.style.display = isDraftLike ? 'block' : 'none';
        } else {
            card.style.display = card.classList.contains(`status-${status}`) ? 'block' : 'none';
        }
    });

    // Toggle active class
    document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
    if (status === 'draft') btnDraft?.classList.add('active');
    else if (status === 'berlangsung') btnBerlangsung?.classList.add('active');
    else if (status === 'selesai') btnSelesai?.classList.add('active');
    else if (status === 'dibatalkan') btnDibatalkan?.classList.add('active');
}


    // Default tab dari backend
    filterStatus('{{ $defaultTab }}');

    // Event listeners
    btnDraft?.addEventListener('click', () => filterStatus('draft'));
    btnBerlangsung?.addEventListener('click', () => filterStatus('berlangsung'));
    btnSelesai?.addEventListener('click', () => filterStatus('selesai'));
    btnDibatalkan?.addEventListener('click', () => filterStatus('dibatalkan'));

    // === Countdown Pesanan Draft ===
    document.querySelectorAll('[id^="countdown-"]').forEach(function (el) {
        const deadline = parseInt(el.dataset.expired) * 1000;

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = deadline - now;

            if (distance < 0) {
                el.innerHTML = "Waktu habis - pesanan akan dibatalkan.";
                return;
            }

            const hours = Math.floor((distance / (1000 * 60 * 60)) % 24);
            const minutes = Math.floor((distance / (1000 * 60)) % 60);
            const seconds = Math.floor((distance / 1000) % 60);

            el.innerHTML = `${hours}j ${minutes}m ${seconds}d`;
        }

        setInterval(updateCountdown, 1000);
        updateCountdown();
    });

});
</script>


@endsection
