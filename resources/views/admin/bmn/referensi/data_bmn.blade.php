@extends('admin.templates.sidebar')

@section('contain')
    <style>
        .form-label {
            display: flex;
            align-items: center;
            height: 100%;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h4>BMN / <span class="fw-bold">Data Inventaris</span></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body content">
                        <div class="row page_content page_1">
                            <div class="table-responsive">
                                <div class="d-flex mb-3">
                                    <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal"
                                        data-bs-target="#tambah_bmn">
                                        <i class="fa fa-plus"></i> Tambah
                                    </button>
                                    <button type="button" class="btn btn-warning ms-auto" data-bs-toggle="modal"
                                        data-bs-target="#rekapitulasiModal">
                                        <i class="fa fa-file-pdf"></i> Rekapitulasi
                                    </button>
                                </div>



                                <table id="example" class="table table-bordered" style="width: 100%">
                                    <thead>
                                        <tr class="text-center small">
                                            <th>No</th>
                                            <th>Kode Barang</th>
                                            <th>NUP</th>
                                            <th>Nama Barang</th>
                                            <th>Merek</th>
                                            <th>Kategori</th>
                                            <th>Tahun Beli</th>
                                            <th>Kode Ruangan</th>
                                            <th>Nama Ruangan</th>
                                            <th>Jenis Pemeliharaan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="rekapitulasiModal" tabindex="-1" aria-labelledby="rekapitulasiLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="rekapitulasiForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rekapitulasiLabel">Pilih Jenis Rekapitulasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="rekapType" class="form-label">Jenis Rekapitulasi</label>
                            <select id="rekapType" class="form-select">
                                <option value="">Pilih jenis</option>
                                <option value="umum">Data Umum BMN</option>
                                <option value="usia">Data Usia BMN</option>
                            </select>
                        </div>

                        <div class="mb-3" id="tanggalInput" style="display: none;">
                            <label for="tanggalRekap" class="form-label">Tanggal Maksimal</label>
                            <input type="date" class="form-control" id="tanggalRekap">
                        </div>
                        <!-- Tambahkan di dalam .modal-body -->
                        <div id="usiaFilter" style="display: none;">
    <div class="row mb-3">
        <div class="col-md-6">
            <select id="usiaKondisi" class="form-select">
                <option value="" disabled selected>Pilih kondisi usia</option>
                <option value="lt">Kurang dari</option>
                <option value="gt">Lebih dari</option>
            </select>
        </div>
        <div class="col-md-6">
            <input type="number" id="usiaTahun" class="form-control" min="0" placeholder="Usia (tahun)">
        </div>
    </div>
</div>



                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Lanjut</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tambah_bmn" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ url('/bmn/save') }}" method="post">
                    @csrf
                    <input type="hidden" name="id_inventaris_bmn" id="bmn_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Data Aset</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body col-md-12">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Kode Barang</label>
                            </div>
                            <div class="col-md-8">
                                {{-- Kode Barang --}}
                                <select class="form-select select2-tag" name="kode" required>
                                    <option></option>
                                    @foreach ($kodeBarangList as $kode)
                                        <option value="{{ $kode }}">{{ $kode }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Nama Barang</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-select select2-tag" name="nama" required>
                                    <option></option>
                                    @foreach ($namaBarangList as $nama)
                                        <option value="{{ $nama }}">{{ $nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">NUP</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="nup" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Merek</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="" name="merek" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Kategori</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-select select2-tag" name="kategori" required>
                                    <option></option>
                                    @foreach ($kategoriList as $kategori)
                                        <option value="{{ $kategori }}">{{ $kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Ruangan</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-select select2" name="ruangan" required>
                                    <option></option>
                                    @foreach ($ruanganList as $ruangan)
                                        <option value="{{ $ruangan->id_ruangan_bmn }}">{{ $ruangan->nama_ruangan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="" class="form-label">Tahun Pembelian</label>
                            </div>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="tgl_beli" min="1900"
                                    max="{{ now()->year }}" step="1" placeholder="Mis. 2020" required>

                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Jenis Pemeliharaan</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-select" name="jenis_pemeliharaan" id="jenis_pemeliharaan" required>
                                    <option value="">Pilih</option>
                                    <option value="insidental">Insidental</option>
                                    <option value="rutin">Rutin</option>
                                </select>
                            </div>
                        </div>

                        <div id="rutin_fields" style="display: none;">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Periode Pemeliharaan (bulan)</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="number" name="periode_pemeliharaan" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Jadwal Pemeliharaan</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="date" name="jadwal_pemeliharaan" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Menampilkan input tanggal jika pilih "Data Umum BMN"
            $('#rekapType').on('change', function() {
                if ($(this).val() === 'umum') {
                    $('#tanggalInput').show();
                    $('#usiaFilter').hide();
                } else if ($(this).val() === 'usia') {
                    $('#tanggalInput').hide();
                    $('#usiaFilter').show();
                    $('#tanggalRekap').val('');
                } else {
                    $('#tanggalInput').hide();
                    $('#usiaFilter').hide();
                }
            });

            $('#rekapitulasiForm').on('submit', function(e) {
                e.preventDefault();

                const type = $('#rekapType').val();

                if (type === 'umum') {
                    const tanggal = $('#tanggalRekap').val();
                    if (!tanggal) {
                        alert('Silakan pilih tanggal.');
                        return;
                    }
                    window.open(`/bmn/rekap?tanggal=${tanggal}`, '_blank');
                } else if (type === 'usia') {
                    const kondisi = $('#usiaKondisi').val();
                    const tahun = $('#usiaTahun').val();

                    if (!kondisi || !tahun) {
                        alert('Lengkapi filter usia.');
                        return;
                    }

                    window.open(`/bmn/rekap-usia?kondisi=${kondisi}&tahun=${tahun}`, '_blank');
                }
            });


            $(document).on('click', '.edit-btn', function() {
                const data = $(this).data('item');
                $('input[name="id_inventaris_bmn"]').val(data.id_inventaris_bmn);
                $('select[name="kode"]').val(data.kode_bmn).trigger('change');
                $('select[name="nama"]').val(data.nama_bmn).trigger('change');
                $('input[name="nup"]').val(data.nup_bmn);
                $('input[name="merek"]').val(data.merk_bmn);
                $('select[name="kategori"]').val(data.kategori_bmn).trigger('change');
                $('select[name="ruangan"]').val(data.id_ruangan_bmn).trigger('change');
                $('input[name="tgl_beli"]').val(new Date(data.tahun_beli).getFullYear());
                $('select[name="jenis_pemeliharaan"]').val(data.jenis_pemeliharaan).trigger('change');
                $('input[name="periode_pemeliharaan"]').val(data.periode_pemeliharaan);
                $('input[name="jadwal_pemeliharaan"]').val(data.jadwal_pemeliharaan);
            });

            $('#jenis_pemeliharaan').on('change', function() {
                if ($(this).val() === 'rutin') {
                    $('#rutin_fields').show();
                } else {
                    $('#rutin_fields').hide();
                    // Kosongkan nilai jika bukan rutin (yaitu insidental)
                    $('input[name="periode_pemeliharaan"]').val('');
                    $('input[name="jadwal_pemeliharaan"]').val('');
                }
            });


            $('.select2').select2({
                placeholder: "Pilih opsi...",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#tambah_bmn'),
            });

            $('.select2-tag').select2({
                tags: true,
                placeholder: "Ketik atau pilih...",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#tambah_bmn')
            });
            let table = $('#example').DataTable({
                ajax: "{{ url('/bmn/json') }}",
                columns: [{
                        data: null,
                        render: (data, type, row, meta) => meta.row + 1
                    },
                    {
                        data: 'kode_bmn'
                    },
                    {
                        data: 'nup_bmn'
                    },
                    {
                        data: 'nama_bmn'
                    },
                    {
                        data: 'merk_bmn'
                    },
                    {
                        data: 'kategori_bmn'
                    },
                    {
                        data: 'tahun_beli'
                    },
                    {
                        data: 'ruangan.kode_ruangan',
                        defaultContent: '-'
                    },
                    {
                        data: 'ruangan.nama_ruangan',
                        defaultContent: '-'
                    },
                    {
                        data: 'jadwal_pemeliharaan',
                        render: function(data, type, row) {
                            return data ? 'Rutin' : 'Insidental';
                        }
                    },
                    {
                        data: 'id_inventaris_bmn',
                        render: function(data, type, row) {
                            return `
                <div class='d-flex justify-content-center'>
                    <a href="#" class="btn btn-success me-1 edit-btn" data-bs-toggle="modal" data-bs-target="#tambah_bmn"
                        data-item='${JSON.stringify(row)}'>
                        <i class='fa-solid fa-pen-to-square'></i>
                    </a>
                    <form action='/bmn/delete/${data}' method='POST' onsubmit="return confirm('Hapus Data?')">
                        @csrf
                        @method('DELETE')
                        <button type='submit' class='btn btn-danger me-1'>
                            <i class='fa-solid fa-trash'></i>
                        </button>
                    </form>
                </div>`;
                        }
                    }
                ]

            });
        });
    </script>
@endsection
