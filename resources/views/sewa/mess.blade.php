@extends('sewa.template')

@section('content')
    <section>
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8">
                    <div class="gambar mb-3">
                        <strong>Preview Kamar dan Lobby</strong>
                        <div id="kamarCarousel" class="carousel slide mt-3" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-indicators">
                                    <button type="button" data-bs-target="#kamarCarousel" data-bs-slide-to="0"
                                        class="active" aria-current="true" aria-label="Slide 1"></button>
                                    <button type="button" data-bs-target="#kamarCarousel" data-bs-slide-to="1"
                                        aria-label="Slide 2"></button>
                                    <button type="button" data-bs-target="#kamarCarousel" data-bs-slide-to="2"
                                        aria-label="Slide 3"></button>
                                    <button type="button" data-bs-target="#kamarCarousel" data-bs-slide-to="3"
                                        aria-label="Slide 4"></button>
                                    <button type="button" data-bs-target="#kamarCarousel" data-bs-slide-to="4"
                                        aria-label="Slide 5"></button>
                                </div>

                                <div class="carousel-item active">
                                    <img src="{{ asset('assets/images/kamar/gedung.jpg') }}" class="d-block w-100"
                                        alt="Kamar 1">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('assets/images/kamar/lobby1.jpg') }}" class="d-block w-100"
                                        alt="Kamar 2">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('assets/images/kamar/lobby2.jpg') }}" class="d-block w-100"
                                        alt="Kamar 3">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('assets/images/kamar/kamarstandar.jpg') }}" class="d-block w-100"
                                        alt="Kamar 3">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('assets/images/kamar/kamarpremium.jpg') }}" class="d-block w-100"
                                        alt="Kamar 3">
                                </div>
                            </div>

                            <!-- Controls -->
                            <button class="carousel-control-prev" type="button" data-bs-target="#kamarCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Sebelumnya</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#kamarCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Selanjutnya</span>
                            </button>
                        </div>
                    </div>

                    <div class="alamat mb-3">
                        <strong>Alamat Gedung Diklat LLDIKTI IV</strong>
                        <p>Jalan Raya Bandung Palimanan (KM 20,5), Cikeruh, Jatinangor, Sumedang</p>
                    </div>

                    <div class="fasilitas mb-3">
                        <strong>Fasilitas Umum </strong>
                        <p>Tersedia Lobby dan Dapur Bersama</p>
                    </div>

                    <div class="jadwal mb-4">
                        <strong>Pilih Tanggal</strong><br>
                        <input type="date" id="start" name="start" class="form-control d-inline-block w-auto">
                        sampai
                        <input type="date" id="end" name="end" disabled
                            class="form-control d-inline-block w-auto">
                    </div>

                    <div class="row" id="room-list">
                        @foreach ($roomCategories as $kategori)
                            <div class="col-12 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary">{{ $kategori->nama_kategori }}</h5>

                                        @php
                                            $groupedKamars = $kategori->kamars->groupBy('harga_per_malam');
                                        @endphp

                                        @foreach ($groupedKamars as $harga => $kamars)
                                            @php
                                                $fasilitas = $kamars->first()?->fasilitas ?? collect();
                                            @endphp

                                            <hr>
                                            {{-- <div class="mb-3 room-option" data-category-id="{{ $kategori->id }}"
                                                data-category-name="{{ $kategori->nama_kategori }}"
                                                data-base-price="{{ $harga }}"> --}}
                                            <div class="mb-3 room-option" data-category-id="{{ $kategori->id_kategori_kamar }}"
                                                data-room-ids="" data-category-name="{{ $kategori->nama_kategori }}"
                                                data-base-price="{{ $harga }}">

                                                <span class="text-success">
                                                    <strong>Rp {{ number_format($harga, 0, ',', '.') }}/malam</strong>
                                                    <span class="available-rooms text-bold-red"
                                                        data-category-id="{{ $kategori->id_kategori_kamar }}"
                                                        data-base-price="{{ $harga }}">
                                                        <span class="jumlah-kamar">{{ $kamars->count() }}</span> kamar
                                                        tersedia
                                                    </span>
                                                    <div class="d-flex flex-wrap gap-2 small text-muted my-2">
                                                        @foreach ($fasilitas as $f)
                                                            <div><i
                                                                    class="bi bi-check-circle me-1"></i>{{ $f->pivot->jumlah }}
                                                                {{ ucfirst($f->nama_fasilitas) }}</div>
                                                        @endforeach
                                                    </div>

                                                    <div class="dropdown">
                                                        <button
                                                            class="btn btn-outline-primary dropdown-toggle room-dropdown-btn"
                                                            type="button"
                                                            id="dropdown{{ $kategori->id_kategori_kamar }}_{{ $harga }}"
                                                            data-bs-toggle="dropdown" aria-expanded="false"
                                                            {{ $kamars->count() === 0 ? 'disabled' : '' }}>
                                                            Pesan Kamar
                                                        </button>
                                                        <ul class="dropdown-menu"
                                                            id="dropdownMenu{{ $kategori->id_kategori_kamar }}_{{ $harga }}"
                                                            aria-labelledby="dropdown{{ $kategori->id_kategori_kamar }}_{{ $harga }}"
                                                            style="max-height:200px;overflow-y:auto">
                                                            <!-- Konten akan diisi oleh JavaScript -->
                                                        </ul>
                                                        <input type="hidden"
                                                            name="jumlah_{{ $kategori->id_kategori_kamar }}_{{ $harga }}"
                                                            value="">
                                                    </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-12 col-md-4 desktop-only">
                    <div class="sticky-top pt-md-4" style="top: 2cm;">
                        <div class="card shadow-sm p-3 mb-3">
                            <h5 class="card-title">Kamar yang Dipilih:</h5>
                            <ul id="selectedRoomsDesktop" class="list-group list-group-flush mt-2">
                                <li class="list-group-item text-muted" id="noRoomsSelectedDesktop">Belum ada kamar yang
                                    dipilih.</li>
                            </ul>
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                <strong class="fs-5">Total Harga:</strong>
                                <span id="totalPriceDesktop" class="text-primary fs-5">Rp 0</span>
                            </div>
                            <a href="#" id="ajukanSewaBtn"
                                class="ajukan btn btn-primary fw-bold text-white w-100 mt-3">Ajukan Sewa</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="mobile-fixed mobile-only">
            <div class="d-flex justify-content-between align-items-center mb-2 px-3">
                <span class="text-white">Total Kamar: <strong id="totalRoomsMobileCount">0</strong></span>
                <span class="text-white">Total Harga: <strong id="totalPriceMobile">Rp 0</strong></span>
            </div>
            <div class="dropdown mb-2 w-100">
                <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Lihat Detail Kamar
                </button>
                <ul id="selectedRoomsMobileDetails" class="dropdown-menu w-100 px-2">
                    <li><span class="dropdown-item kamar-option text-muted" id="noRoomsSelectedMobile">Belum ada kamar
                            yang
                            dipilih.</span></li>
                </ul>
            </div>
            <button class="ajukan btn btn-primary fw-bold text-white w-100">Ajukan Sewa</button>
        </div>

    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isLoggedInAsPenyewa = @json(Auth::guard('akun_penyewa')->check());
            const startInput = document.getElementById('start');
            const endInput = document.getElementById('end');

            const selectedRoomsDesktop = document.getElementById('selectedRoomsDesktop');
            const totalPriceDesktop = document.getElementById('totalPriceDesktop');
            const noRoomsSelectedDesktop = document.getElementById('noRoomsSelectedDesktop');

            const totalRoomsMobileCount = document.getElementById('totalRoomsMobileCount');
            const totalPriceMobile = document.getElementById('totalPriceMobile');
            const selectedRoomsMobileDetails = document.getElementById('selectedRoomsMobileDetails');
            const noRoomsSelectedMobile = document.getElementById('noRoomsSelectedMobile');

            const selectedRooms = {};

            function getDurationInNights() {
                const startDate = new Date(startInput.value);
                const endDate = new Date(endInput.value);
                if (!startInput.value || !endInput.value) return 0;
                const diffTime = endDate - startDate;
                return Math.max(0, Math.floor(diffTime / (1000 * 60 * 60 * 24)));
            }

            function numberFormat(value) {
                return new Intl.NumberFormat('id-ID').format(value);
            }

            function updateSelectedRoomsDisplay() {
                const duration = getDurationInNights();
                if (duration === 0 && Object.values(selectedRooms).filter(room => room.quantity > 0).length === 0) {
                    selectedRoomsDesktop.innerHTML = '';
                    selectedRoomsDesktop.appendChild(noRoomsSelectedDesktop);
                    selectedRoomsMobileDetails.innerHTML = '';
                    selectedRoomsMobileDetails.appendChild(noRoomsSelectedMobile);
                    totalPriceDesktop.textContent = 'Rp 0';
                    totalRoomsMobileCount.textContent = '0';
                    totalPriceMobile.textContent = 'Rp 0';
                    return;
                }

                let totalOverallPrice = 0;
                let totalOverallRooms = 0;

                selectedRoomsDesktop.innerHTML = '';
                selectedRoomsMobileDetails.innerHTML = '';

                const activeSelections = Object.values(selectedRooms).filter(room => room.quantity > 0);

                if (activeSelections.length === 0) {
                    selectedRoomsDesktop.appendChild(noRoomsSelectedDesktop);
                    selectedRoomsMobileDetails.appendChild(noRoomsSelectedMobile);
                } else {
                    noRoomsSelectedDesktop.style.display = 'none';
                    noRoomsSelectedMobile.style.display = 'none';

                    activeSelections.forEach(room => {
                        const itemPrice = room.price * room.quantity * duration;
                        totalOverallPrice += itemPrice;
                        totalOverallRooms += room.quantity;

                        const liDesktop = document.createElement('li');
                        liDesktop.className =
                            'list-group-item d-flex justify-content-between align-items-center';
                        liDesktop.innerHTML =
                            `<div>${room.name} (${room.quantity} Kamar)</div><strong>Rp ${numberFormat(itemPrice)}</strong>`;
                        selectedRoomsDesktop.appendChild(liDesktop);

                        const liMobile = document.createElement('li');
                        liMobile.innerHTML =
                            `<span class="dropdown-item kamar-option">${room.name}: ${room.quantity} Kamar (Rp ${numberFormat(itemPrice)})</span>`;
                        selectedRoomsMobileDetails.appendChild(liMobile);
                    });
                }

                totalPriceDesktop.textContent = `Rp ${numberFormat(totalOverallPrice)}`;
                totalRoomsMobileCount.textContent = totalOverallRooms;
                totalPriceMobile.textContent = `Rp ${numberFormat(totalOverallPrice)}`;
            }

            function updatePesanKamarButtonsState() {
                const isValid = startInput.value && endInput.value;
                document.querySelectorAll('.room-dropdown-btn').forEach(button => {
                    button.disabled = !isValid;
                });
            }

            document.addEventListener('click', function(e) {
                if (e.target.matches('.kamar-option')) {
                    e.preventDefault();

                    const selectedValue = e.target.getAttribute('data-value');
                    const button = e.target.closest('.dropdown').querySelector('.dropdown-toggle');
                    const hiddenInput = e.target.closest('.dropdown').querySelector('input[type="hidden"]');
                    const roomOption = e.target.closest('.room-option');
                    const roomIds = roomOption.getAttribute('data-room-ids').split(',');

                    const categoryId = roomOption.getAttribute('data-category-id');
                    const categoryName = roomOption.getAttribute('data-category-name');
                    const basePrice = parseInt(roomOption.getAttribute('data-base-price'));

                    const quantitySelected = parseInt(selectedValue) || 0;
                    button.textContent = quantitySelected ? `${quantitySelected} Kamar` : 'Pesan Kamar';
                    if (hiddenInput) hiddenInput.value = quantitySelected;

                    const uniqueKey = `${categoryId}_${basePrice}`;

                    if (quantitySelected > 0) {
                        selectedRooms[uniqueKey] = {
                            ids: roomIds.slice(0, quantitySelected),
                            categoryId: categoryId,
                            name: `${categoryName}`,
                            price: basePrice,
                            quantity: quantitySelected
                        };
                    } else {
                        delete selectedRooms[uniqueKey];
                    }
                    updateSelectedRoomsDisplay();
                }
            });

            const today = new Date();
            const todayStr = today.toISOString().split('T')[0];
            startInput.min = todayStr;

            const threeMonthsLater = new Date();
            threeMonthsLater.setMonth(threeMonthsLater.getMonth() + 3);
            const maxStartStr = threeMonthsLater.toISOString().split('T')[0];
            startInput.max = maxStartStr;

            startInput.addEventListener('change', () => {
                const startDate = new Date(startInput.value);
                if (!startInput.value) {
                    endInput.disabled = true;
                    endInput.value = '';
                    for (const key in selectedRooms) delete selectedRooms[key];
                    updateSelectedRoomsDisplay();
                    updatePesanKamarButtonsState();
                    return;
                }

                const autoEndDate = new Date(startDate);
                autoEndDate.setDate(autoEndDate.getDate() + 1);

                const maxEndDate = new Date(startDate);
                maxEndDate.setDate(startDate.getDate() + 5);

                endInput.disabled = false;
                endInput.min = autoEndDate.toISOString().split('T')[0];
                endInput.max = maxEndDate.toISOString().split('T')[0];
                endInput.value = autoEndDate.toISOString().split('T')[0];

                updateSelectedRoomsDisplay();
                updatePesanKamarButtonsState();

                // Trigger availability check
                if (endInput.value) {
                    $.ajax({
                        url: "{{ route('cek.kamar.tersedia') }}",
                        method: 'GET',
                        data: {
                            start: startInput.value,
                            end: endInput.value
                        },
                        success: function(data) {
                            $('[data-category-id]').each(function() {
                                const categoryId = $(this).data('category-id');
                                const price = $(this).data('base-price');
                                const info = (data[categoryId] && data[categoryId][
                                    price
                                ]) || {
                                    count: 0,
                                    ids: []
                                };
                                const tersedia = info.count;
                                const availableRoomIds = info.ids;


                                const dropdownBtn = $(
                                    `#dropdown${categoryId}_${price}`);
                                const dropdownMenu = $(
                                    `#dropdownMenu${categoryId}_${price}`);
                                const availableRoomsSpan = $(
                                    `.available-rooms[data-category-id="${categoryId}"][data-base-price="${price}"]`
                                );

                                availableRoomsSpan.find('.jumlah-kamar').text(tersedia);


                                $(`[data-category-id="${categoryId}"][data-base-price="${price}"]`)
                                    .attr('data-room-ids', availableRoomIds.join(','));

                                dropdownMenu.empty();
                                if (tersedia > 0) {
                                    dropdownBtn.prop('disabled', false);
                                    dropdownBtn.text('Pesan Kamar');

                                    dropdownMenu.append(
                                        '<li><a class="dropdown-item kamar-option" href="#" data-value="">Kosongkan Pilihan</a></li>'
                                    );
                                    dropdownMenu.append(
                                        '<li><hr class="dropdown-divider"></li>');

                                    for (let i = 1; i <= tersedia; i++) {
                                        dropdownMenu.append(
                                            `<li><a class="dropdown-item kamar-option" href="#" data-value="${i}">${i}</a></li>`
                                        );
                                    }
                                } else {
                                    dropdownBtn.prop('disabled', true);
                                    dropdownBtn.text('Tidak Tersedia');
                                }

                            });
                        }

                    });
                }
            });

            endInput.addEventListener('change', () => {
                const startDate = new Date(startInput.value);
                const endDate = new Date(endInput.value);
                if (getDurationInNights() > 5) {
                    alert('Durasi maksimum adalah 5 malam.');
                    const maxEndDate = new Date(startDate);
                    maxEndDate.setDate(startDate.getDate() + 5);
                    endInput.value = maxEndDate.toISOString().split('T')[0];
                }
                updateSelectedRoomsDisplay();
                updatePesanKamarButtonsState();

                // Trigger availability check
                if (startInput.value && endInput.value) {
                    $.ajax({
                        url: "{{ route('cek.kamar.tersedia') }}",
                        method: 'GET',
                        data: {
                            start: startInput.value,
                            end: endInput.value
                        },
                        success: function(data) {
                            $('[data-category-id]').each(function() {
                                const categoryId = $(this).data('category-id');
                                const price = $(this).data('base-price');
                                const info = (data[categoryId] && data[categoryId][
                                    price
                                ]) || {
                                    count: 0,
                                    ids: []
                                };
                                const tersedia = info.count;
                                const availableRoomIds = info.ids;

                                const dropdownBtn = $(
                                    `#dropdown${categoryId}_${price}`);
                                const dropdownMenu = $(
                                    `#dropdownMenu${categoryId}_${price}`);
                                const availableRoomsSpan = $(
                                    `.available-rooms[data-category-id="${categoryId}"][data-base-price="${price}"]`
                                );
                                availableRoomsSpan.find('.jumlah-kamar').text(tersedia);

                                $(`[data-category-id="${categoryId}"][data-base-price="${price}"]`)
                                    .attr('data-room-ids', availableRoomIds.join(','));

                                dropdownMenu.empty();
                                if (tersedia > 0) {
                                    dropdownBtn.prop('disabled', false);
                                    dropdownBtn.text('Pesan Kamar');

                                    dropdownMenu.append(
                                        '<li><a class="dropdown-item kamar-option" href="#" data-value="">Kosongkan Pilihan</a></li>'
                                    );
                                    dropdownMenu.append(
                                        '<li><hr class="dropdown-divider"></li>');

                                    for (let i = 1; i <= tersedia; i++) {
                                        dropdownMenu.append(
                                            `<li><a class="dropdown-item kamar-option" href="#" data-value="${i}">${i} Kamar</a></li>`
                                        );
                                    }
                                } else {
                                    dropdownBtn.prop('disabled', true);
                                    dropdownBtn.text('Tidak Tersedia');
                                }

                            });
                        }

                    });
                }
            });

            document.querySelectorAll('.ajukan').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (!isLoggedInAsPenyewa) {
                        Swal.fire({
                            title: "Silakan Login atau Register",
                            text: "Untuk mengajukan sewa, Anda harus login sebagai penyewa terlebih dahulu.",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#aaa",
                            confirmButtonText: "Login",
                            cancelButtonText: "Batal"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('penyewa.login') }}";
                            }
                        });
                        return;
                    }

                    const selected = Object.values(selectedRooms).filter(r => r.quantity > 0);
                    const startDate = startInput.value;
                    const endDate = endInput.value;

                    if (!selected.length || !startDate || !endDate) {
                        alert(
                            'Silakan pilih kamar dan tentukan tanggal check-in/out terlebih dahulu.'
                        );
                        return;
                    }

                    const data = {
                        rooms: selected,
                        start: startDate,
                        end: endDate
                    };
                    const queryString = encodeURIComponent(JSON.stringify(data));
                    window.location.href = `/sewa/form?data=${queryString}`;
                });
            });

            // Inisialisasi awal
            updateSelectedRoomsDisplay();
            updatePesanKamarButtonsState();
        });
    </script>
@endsection
