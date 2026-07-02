@extends('admin.templates.sidebar')

@section('contain')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto mt-5">
                    <h3 class="fw-bold text-secondary mb-4">Pengajuan Pemeliharaan BMN</h3>

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

                    <!-- Form Inventaris -->
                    <div id="formInventaris">
                        <form action="{{ url('/pemeliharaan-admin/store-pengajuan') }}" method="POST">
                            @csrf
                            <input type="hidden" name="jenis_bmn" value="inventaris">
                            <div class="card p-3 shadow border-0">
                                <div class="card-body">
                                    <!-- Pengaju -->
                                    <div class="mb-3 row">
                                        <label class="col-md-4 col-form-label">Pengaju <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-8">
                                            <select class="form-select select2" name="id_admin" required>
                                                <option value="">Pilih Nama Pegawai</option>
                                                @foreach ($namaPegawai as $pegawai)
                                                    <option value="{{ $pegawai->id }}"
                                                        {{ Auth::user()->username == $pegawai->nama_lengkap ? 'selected' : '' }}>
                                                        {{ $pegawai->nama_lengkap }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Filter Ruangan -->
                                    <div class="mb-3 row">
                                        <label class="col-md-4 col-form-label">Filter Ruangan</label>
                                        <div class="col-md-8">
                                            <select class="form-select select2" id="ruanganDropdown" name="id_ruangan">
                                                <option value="">Filter Ruangan</option>
                                                @foreach ($ruangan as $data)
                                                    <option value="{{ $data->id }}">{{ $data->nama_ruangan }}
                                                        ({{ $data->kode_ruangan }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Pilih BMN -->
                                    <div class="mb-3 row">
                                        <label class="col-md-4 col-form-label">Nama BMN <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-8">
                                            <select class="form-select select2" id="bmnDropdown" multiple
                                                style="width: 100%"></select>
                                        </div>
                                    </div>

                                    <!-- Keterangan BMN -->
                                    <div id="selectedBmnList" class="mt-3">
                                        <div id="bmnKeteranganContainer" class="row"></div>
                                    </div>

                                    <!-- Tombol Submit -->
                                    <div class="d-grid gap-2 col-6 mx-auto mt-4">
                                        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Form Ruangan -->
                    <div id="formRuangan" style="display: none;">
                        <form action="{{ url('/pemeliharaan-admin/store-pengajuan') }}" method="POST">
                            @csrf
                            <input type="hidden" name="jenis_bmn" value="ruangan">
                            <div class="card p-3 shadow border-0">
                                <div class="card-body">

                                    <!-- Pengaju -->
                                    <div class="mb-3 row">
                                        <label class="col-md-4 col-form-label">Pengaju <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-8">
                                            <select class="form-select select2" name="id_admin" required>
                                                <option value="">Pilih Nama Pegawai</option>
                                                @foreach ($namaPegawai as $pegawai)
                                                    <option value="{{ $pegawai->id }}"
                                                        {{ Auth::user()->username == $pegawai->nama_lengkap ? 'selected' : '' }}>
                                                        {{ $pegawai->nama_lengkap }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Ruangan -->
                                    <div class="mb-3 row">
                                        <label class="col-md-4 col-form-label">Ruangan <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-8">
                                            <select class="form-select select2" id="ruanganMultiSelect" multiple>
                                                <option value="">Pilih Ruangan</option>
                                                @foreach ($ruanganTersedia as $data)
                                                    <option value="{{ $data->id }}">{{ $data->nama_ruangan }}
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

    <!-- SCRIPT -->
    <script>
        $(document).ready(function() {
            const ruanganData = @json($ruangan);
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
                            data-id="${id}">
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
                        const ruangan = ruanganData.find(r => r.id == id);
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

            let selectedItems = {};

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
                                data-id="${id}">
                        </div>
                    </div>
                `);
                }

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

                    data.forEach(item => {
                        if (!(item.id in selectedItems)) {
                            $dropdown.append(
                                `<option value="${item.id}">${item.nama_bmn} (Kode: ${item.kode_bmn}) (NUP: ${item.nup_bmn})</option>`
                            );
                        }
                    });

                    for (const id in selectedItems) {
                        const item = selectedItems[id];
                        if (!$dropdown.find(`option[value="${id}"]`).length) {
                            $dropdown.append(
                                `<option value="${id}">${item.nama_bmn} (Kode: ${item.kode_bmn}) (NUP: ${item.nup_bmn})</option>`
                            );
                        }
                    }

                    $dropdown.val(Object.keys(selectedItems)).trigger('change.select2');
                });
            }

            loadBmnOptions();

            $('#ruanganDropdown').on('change', function() {
                const idRuangan = $(this).val();
                loadBmnOptions(idRuangan);
            });

            $('#bmnDropdown').on('change', function() {
                const selectedValues = $(this).val() || [];

                selectedValues.forEach(id => {
                    if (!(id in selectedItems)) {
                        $.get(`/get-bmn-name/${id}`, function(data) {
                            selectedItems[id] = {
                                nama_bmn: data.nama_bmn,
                                kode_bmn: data.kode_bmn,
                                nup_bmn: data.nup_bmn,
                                keterangan: ''
                            };
                            renderSelectedList();
                            loadBmnOptions($('#ruanganDropdown').val());
                        });
                    }
                });

                for (const id in selectedItems) {
                    if (!selectedValues.includes(id)) {
                        delete selectedItems[id];
                    }
                }

                renderSelectedList();
            });
        });
    </script>
@endsection
