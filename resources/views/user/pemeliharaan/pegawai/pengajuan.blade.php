@extends('user.templates.sidebar')

@section('content')
    <section class="mb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="mt-4">
                        <h3 class="fw-bold text-secondary">Pengajuan Pemeliharaan BMN</h3>
                    </div>
                    <!-- Pilihan Jenis Pengajuan -->
                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">Jenis Pengajuan <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis_bmn" id="jenisInventaris"
                                    value="inventaris" checked>
                                <label class="form-check-label" for="jenisInventaris">Inventaris</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis_bmn" id="jenisRuangan"
                                    value="ruangan">
                                <label class="form-check-label" for="jenisRuangan">Ruangan</label>
                            </div>
                        </div>
                    </div>
                    {{-- card --}}
                    <!-- Form Inventaris -->
                    <div id="formInventaris">
                        <div class="card p-3 shadow border-0 rounded-0 mt-3" data-aos="fade-up" data-aos-delay="100"
                            data-aos-duration="1000">
                            <div class="card-body">

                                <form action="{{ url('/pemeliharaan-pegawai/store-pengajuan') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="jenis_bmn" value="inventaris">
                                    <div class="mb-1 row">
                                        <!-- Pengaju -->
                                        <input type="hidden" name="id_pegawai" value="{{ Auth::user()->id }}">

                                        <!-- Ruangan -->
                                        <label for="ruanganDropdown" class="col-md-4 col-form-label">Filter Ruangan</label>
                                        <div class="col-md-8">
                                            <select class="form-select select2" id="ruanganDropdown" name="id_ruangan">
                                                <option value="">Pilih Ruangan</option>
                                                @foreach ($ruanganTersedia as $data)
                                                    <option value="{{ $data->id_ruangan_bmn }}">{{ $data->nama_ruangan }}
                                                        ({{ $data->kode_ruangan }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- BMN (multi select) -->
                                        <label for="bmnDropdown" class="col-md-4 col-form-label">Nama BMN<span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-8">
                                            <select class="form-select select2" id="bmnDropdown"" multiple="multiple"
                                                style="width: 100%">
                                            </select>
                                        </div>
                                        <!-- Daftar BMN terpilih -->
                                        <div id="selectedBmnList" class="col-md-12 mt-3">
                                            <div id="bmnKeteranganContainer"></div>
                                            <!-- Items will be rendered here -->
                                        </div>
                                    </div>
                            </div>
                            <div class="btns-group d-grid gap-2 col-6 mx-auto pb-3 mt-5">
                                <button type="submit" class="btn btn-next btn-primary">Kirim Pengajuan</button>
                            </div>
                            </form>
                        </div>
                    </div>
                    <!-- Form Ruangan -->
                    <div id="formRuangan" style="display: none;">
                        <form action="{{ url('/pemeliharaan-pegawai/store-pengajuan') }}" method="POST">
                            @csrf
                            <input type="hidden" name="jenis_bmn" value="ruangan">
                            <div class="card p-3 shadow border-0">
                                <div class="card-body">
                                    <input type="hidden" name="id_pegawai" value="{{ Auth::user()->id }}">

                                    <!-- Ruangan -->
                                    <div class="mb-3 row">
                                        <label class="col-md-4 col-form-label">Ruangan <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-8">
                                            <select class="form-select select2" id="ruanganMultiSelect" multiple>
                                                <option value="">Pilih Ruangan</option>
                                                @foreach ($ruanganTersedia as $data)
                                                    <option value="{{ $data->id_ruangan_bmn }}">{{ $data->nama_ruangan }}
                                                        ({{ $data->kode_ruangan }})
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <!-- Keterangan Ruangan -->
                                    <div id="ruanganKeteranganContainer" class="row mt-3"></div>


                                    <!-- Tombol Submit -->
                                    <div class="d-grid gap-2 col-6 mx-auto mt-4">
                                        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @php
        $ruanganDataJs = $ruangan->map(function ($item) {
            return [
                'id_ruangan_bmn' => $item->id_ruangan_bmn,
                'nama_ruangan' => $item->nama_ruangan,
                'kode_ruangan' => $item->kode_ruangan,
            ];
        });
    @endphp
    <script>
        $(document).ready(function() {
            const ruanganData = @json($ruanganDataJs);

            let selectedRuangans = {};

            function renderRuanganKeterangan() {
                const $container = $('#ruanganKeteranganContainer');
                $container.empty();

                if (Object.keys(selectedRuangans).length === 0) {
                    $container.append('<div class="text-muted">Tidak ada ruangan yang dipilih.</div>');
                    return;
                }

                for (const [id, data] of Object.entries(selectedRuangans)) {
                    $container.append(`
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <label class="form-label fw-semibold">${data.nama_ruangan}
                            <span class="text-muted">(${data.kode_ruangan})</span>
                        </label>
                        <input type="hidden" name="id_ruangan_ruangan[]" value="${id}">
                        <input type="text" class="form-control ruangan-keterangan mt-2"
                            name="keterangan_ruangan[${id}]"
                            value="${data.keterangan || ''}"
                            placeholder="Masukkan keterangan untuk ${data.nama_ruangan}"
                            data-id="${id}"
                            required>
                    </div>
                </div>
            `);
                }

                $('.ruangan-keterangan').on('input', function() {
                    const id = $(this).data('id');
                    if (selectedRuangans[id]) {
                        selectedRuangans[id].keterangan = $(this).val();
                    }
                });
            }

            $('#ruanganMultiSelect').on('change', function() {
                const selected = $(this).val() || [];
                selected.forEach(id => {
                    if (!(id in selectedRuangans)) {
                        const ruangan = ruanganData.find(r => r.id_ruangan_bmn == id);

                        selectedRuangans[id] = {
                            nama_ruangan: ruangan.nama_ruangan,
                            kode_ruangan: ruangan.kode_ruangan,
                            keterangan: ''
                        };
                    }
                });

                for (const id in selectedRuangans) {
                    if (!selected.includes(id)) {
                        delete selectedRuangans[id];
                    }
                }

                renderRuanganKeterangan();
            });

            $('input[name="jenis_bmn"]').on('change', function() {
                const jenis = $(this).val();
                $('#formInventaris').toggle(jenis === 'inventaris');
                $('#formRuangan').toggle(jenis === 'ruangan');
            });

            $('.select2').select2({
                placeholder: "Pilih Opsi",
                allowClear: true,
                width: '100%'
            });

            let selectedItems = {}; // { id: {nama_bmn, kode_bmn, nup_bmn, keterangan} }

            function renderSelectedList() {
                const $container = $('#bmnKeteranganContainer');
                $container.empty();

                if (Object.keys(selectedItems).length === 0) {
                    $container.append('<div class="text-muted">Tidak ada BMN yang dipilih.</div>');
                    return;
                }

                for (const [id, item] of Object.entries(selectedItems)) {
                    $container.append(`
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <label class="form-label fw-semibold">${item.nama_bmn} 
                                <span class="text-muted">(Kode: ${item.kode_bmn}) (NUP: ${item.nup_bmn})</span>
                            </label>
                            <input type="hidden" name="id_bmn[]" value="${id}">
                            <input type="text" class="form-control bmn-keterangan mt-2"
                                name="keterangan_bmn[${id}]"
                                value="${item.keterangan || ''}"
                                placeholder="Masukkan keterangan untuk ${item.nama_bmn}"
                                data-id="${id}"
                                required>
                        </div>
                    </div>
                `);
                }

                // Tangkap perubahan inputan keterangan
                $('.bmn-keterangan').on('input', function() {
                    const id = $(this).data('id');
                    if (selectedItems[id]) {
                        selectedItems[id].keterangan = $(this).val();
                    }
                });
            }

            function loadBmnOptions(idRuangan = null) {
                const url = idRuangan ? `/get-bmn-by-ruangan/${idRuangan}` : `/get-bmn-all`;

                $.get(url, function(data) {
                    const $dropdown = $('#bmnDropdown');
                    $dropdown.empty();

                    let idRuanganBmn = {}; // Buat set ID dari ruangan terpilih
                    data.forEach(item => {
                        idRuanganBmn[item.id_inventaris_bmn] = item; // fix PK
                    });

                    // Gabungkan semua data: dari filter ruangan + selectedItems
                    const combinedItems = {
                        ...idRuanganBmn
                    };

                    // Tambahkan BMN yang terpilih tapi tidak ada di hasil filter
                    for (const id in selectedItems) {
                        if (!(id in combinedItems)) {
                            combinedItems[id] = selectedItems[id];
                        }
                    }

                    // Render ulang seluruh opsi dropdown
                    for (const id in combinedItems) {
                        const item = combinedItems[id];
                        $dropdown.append(
                            `<option value="${id}">${item.nama_bmn} (Kode: ${item.kode_bmn}) (NUP: ${item.nup_bmn})</option>`
                        );
                    }

                    $dropdown.val(Object.keys(selectedItems)).trigger('change.select2');
                });
            }
            loadBmnOptions();

            $('#ruanganDropdown').on('change', function() {
                loadBmnOptions($(this).val());
            });

            $('#bmnDropdown').on('change', function() {
                const selectedValues = $(this).val() || [];

                // Hapus BMN yang tidak lagi dipilih
                for (const id in selectedItems) {
                    if (!selectedValues.includes(id)) {
                        delete selectedItems[id];
                    }
                }

                // Tambahkan BMN baru yang belum ada di selectedItems
                selectedValues.forEach(id => {
                    if (!(id in selectedItems)) {
                        $.get(`/get-bmn-name/${id}`, function(data) {
                            selectedItems[id] = {
                                nama_bmn: data.nama_bmn,
                                kode_bmn: data.kode_bmn,
                                nup_bmn: data.nup_bmn,
                                keterangan: ""
                            };
                            renderSelectedList();
                        });
                    }
                });

                // Render ulang (jaga-jaga kalau hanya unselect tanpa select baru)
                renderSelectedList();
            });
        });
    </script>
@endsection
