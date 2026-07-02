@extends('admin.templates.sidebar')

@section('contain')
    <style>
        @font-face {
            font-family: 'CedarvilleCursive';
            src: url('{{ asset('assets/fonts/CedarvilleCursive-Regular.ttf') }}') format('truetype');
        }

        table tbody tr:nth-child(even) td {
            background-color: #dcdcdc !important;
        }

        .tanda-tangan {
            font-family: 'CedarvilleCursive', cursive;
            font-size: 36px;
        }

        /* === Tabel Utama === */
        table {
            background-color: transparent !important;
            border: 1px solid black;
            margin-bottom: 0.5rem !important;
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            background-color: transparent !important;
            border: 1px solid black;
            padding: 0.5rem;
            line-height: 1.1;
            font-weight: normal;
        }

        th {
            text-align: center;
        }

        table tbody td:first-child,
        table thead th:first-child {
            text-align: center;
        }

        #pdfPreview {
            width: 100%;
            max-width: 794px;
            /* A4 width in px */
            min-height: 1123px;
            background-color: white;
            overflow-wrap: break-word;
            word-break: break-word;
        }
    </style>

    <section class="mb-5">
        <div class="container">
            <h3 class="fw-bold text-secondary mt-4">Buat Surat Pesanan</h3>
            <div class="row">
                <!-- Form Input -->
                <div class="col-md-6">
                    <form id="formSurat">
                        @csrf
                        @foreach ($selected_ids as $id)
                            <input type="hidden" name="selected_ids[]" value="{{ $id }}">
                        @endforeach
                        @foreach ($ketBmn as $item)
                            <input type="hidden" name="keterangan_bmn[{{ $loop->index }}]"
                                data-label="{{ $item['nama_bmn'] }}" value="">
                        @endforeach


                        <div class="row">
                            <!-- Nomor Surat -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nomor Surat</label>
                                <input type="text" readonly class="form-control" id="nomor_surat_int"
                                    name="nomor_surat_int" value="{{ $nextNomor }}">
                            </div>

                            <!-- Kode Instansi -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Kode Instansi</label>
                                <select class="form-select" id="kode_instansi" name="kode_instansi">
                                    <option value="">-- Pilih Instansi --</option>
                                    @foreach ($kodeInstansi as $item)
                                        @if ($item->kode_instansi)
                                            <option value="{{ $item->kode_instansi }}">{{ $item->kode_instansi }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <!-- Kode Layanan -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Kode Layanan</label>
                                <select class="form-select" id="kode_layanan" name="kode_layanan">
                                    <option value="">-- Pilih Layanan --</option>
                                    @foreach ($kodeLayanan as $item)
                                        @if ($item->kode_layanan)
                                            <option value="{{ $item->kode_layanan }}">{{ $item->kode_layanan }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Perihal (lebih lebar) -->
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Perihal</label>
                                <input type="text" class="form-control" name="perihal" id="perihal"
                                    placeholder="Isi perihal">
                            </div>

                            <!-- Tanggal (lebih kecil) -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tanggal</label>
                                <input type="date" class="form-control" name="tanggal" id="tanggal"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                        </div>


                        <div class="row">
                            <!-- Kop Surat -->
                            <div class="col-md-4 mb-3">
                                <label for="kop_surat" class="form-label">Pilih Kop Surat</label>
                                <select name="kop_surat" id="kop_surat" class="form-select">
                                    <option value="">-- Pilih Kop Surat --</option>
                                    @foreach ($kopSurats as $kop)
                                        <option value="{{ url('/getDokumen/' . basename($kop->url_kop)) }}">
                                            {{ $kop->nama_kop }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Penyedia -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Penyedia</label>
                                <select class="form-select select2" name="pihak_kedua" id="pihak_kedua">
                                    <option value="">-- Pilih Penyedia --</option>
                                    @foreach ($penyedias as $cv)
                                        <option value="{{ $cv->id }}">{{ $cv->nama_CV }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Penanda Tangan -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Penanda Tangan</label>
                                <select class="form-select select2" name="penanda_tangan" id="penanda_tangan">
                                    <option value="">Pilih Penanda Tangan</option>
                                    @foreach ($admins as $admin)
                                        <option value="{{ $admin->id }}"
                                            {{ $admin->id == auth('administrator')->user()->id ? 'selected' : '' }}>
                                            {{ $admin->username }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pembuka</label>
                            <textarea class="form-control" name="opening" id="opening">Dengan ini kami meminta Saudara dapat melaksanakan pekerjaan Perawatan/Pemeliharaan Barang Milik Negara (BMN) berupa {{ implode(', ', $kategoriBmn) ?: '{kategori}' }} dengan memperbaiki atau membeli barang (spare part) yang diperlukan sebagai berikut:</textarea>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea id="keteranganTextArea" name="keterangan_bmn" class="form-control" rows="3"
                                placeholder="Isi keterangan untuk semua barang"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Penutup</label>
                            <textarea class="form-control" name="ending" id="ending">Selanjutnya apabila Saudara membutuhkan keterangan dan penjelasan lebih lanjut terkait dengan perbaikan ini, Saudara dapat menghubungi kami di Layanan Tata usaha dan BMN, Bagian Umum LLDIKTI Wilayah IV.</textarea>
                        </div>

                        <button type="button" class="btn btn-primary" id="kirim">Kirim</button>

                    </form>
                </div>

                <!-- Preview PDF -->
                <div class="col-md-6">
                    <div id="pdfPreview" style="min-height: 400px; background: white;">
                        <h5 class="text-muted text-center">Preview PDF akan muncul di sini</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            let alamatCV = '';

            $('#pihak_kedua').on('change', function() {
                let id = $(this).val();
                if (id) {
                    $.get(`/get-alamat-cv/${id}`, function(data) {
                        alamatCV = data.alamat || '';
                        renderPreview();
                    });
                } else {
                    alamatCV = '';
                    renderPreview();
                }
            });

            $('#formSurat').on('input change', 'input, textarea, select', function() {
                renderPreview();
            });

            const ketBmn = @json($ketBmn);
            const nipNik = @json($nipNik);
            let bmnCount = ketBmn.reduce((acc, curr) => {
                acc[curr.nama_bmn] = (acc[curr.nama_bmn] || 0) + 1;
                return acc;
            }, {});

            function renderPreview() {
                let kopUrl = $('#kop_surat').val();
                let kopHtml = kopUrl ?
                    `<div style="text-align:center;"><img src="${kopUrl}" style="max-height:120px;"></div>` :
                    '';
                let nomorInt = $('#nomor_surat_int').val();
                let instansi = $('#kode_instansi').val();
                let layanan = $('#kode_layanan').val();
                let tahun = new Date().getFullYear();
                let nomorSuratFinal = nomorInt && instansi && layanan ?
                    `${nomorInt}/${instansi}/${layanan}/${tahun}` :
                    '-';
                let perihal = $('#perihal').val();
                let tanggal = $('#tanggal').val();
                let pihakKedua = $('#pihak_kedua option:selected').text();
                let opening = $('#opening').val();
                let ending = $('#ending').val();
                let penanda = $('#penanda_tangan option:selected').text();
                let penandaText = penanda?.trim() || '-';
                let firstWord = penandaText !== '-' ? penandaText.split(/\s+/)[0] : '-';
                // Format tanggal ke Indonesia
                let formattedTanggal = '-';
                if (tanggal) {
                    const dateObj = new Date(tanggal);
                    formattedTanggal = new Intl.DateTimeFormat('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    }).format(dateObj);
                }

                // Ambil daftar nama_bmn unik dari data ketBmn (variabel JS yang berisi array objek {nama_bmn, kategori_bmn})
                let barang = ketBmn.map(item => item.nama_bmn);

                // Ambil nilai keterangan dari textarea khusus untuk keterangan (merge di tabel)
                let keterangan = $('#keteranganTextArea').val() || '-';

                // Buat baris tabel dengan rowspan pada kolom Keterangan
                let rows = '';
                Object.entries(bmnCount).forEach(([nama, jumlah], i) => {
                    rows += `<tr>
        <td style="width:5%;">${i + 1}</td>
        <td style="width:35%;">${nama}</td>
        <td style="width:10%; text-align:center;">${jumlah}</td>
        ${i === 0 ? `<td style="width:50%;" rowspan="${Object.keys(bmnCount).length}">${keterangan}</td>` : ''}
    </tr>`;
                });

                $('#pdfPreview').html(`
                ${kopHtml}
                <div style="background: #fff; color: #000; padding: 20px;">
                    <div class="p-3">
                        <div class="d-flex justify-content-between">
                            <div>Nomor: ${nomorSuratFinal || '-'}</div>
                            <div>Bandung, ${formattedTanggal}</div>
                        </div>
                        Perihal: ${perihal || '-'}<br>
                        Yth ${pihakKedua || '-'}<br>
                        ${alamatCV || ''}
                        <hr>
                        <p style="text-align: justify;">${opening || ''}</p>
                        <table border="1" cellpadding="5" cellspacing="0" width="100%" style="table-layout: fixed;">
                            <thead>
                                <tr>
                                    <th style="width:8%;">No.</th>
                                    <th style="width:35%;">Nama Barang</th>
                                    <th style="width:15%;">Jumlah</th>
                                    <th style="width:42%;">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${rows}
                            </tbody>
                        </table>

                        <p style="text-align: justify; margin-top: 10px;">${ending || ''}</p>
                        <div style="text-align: right;">
                        Pejabat Pengadaan<br>
                        <span class="tanda-tangan">${firstWord}</span><br>
                        ${penandaText}<br>
                        NIP: ${nipNik}
                    </div>
                    </div>
                </div>
            `);
            }
            renderPreview();

            $('#kirim').on('click', function(e) {
                e.preventDefault()
                const element = document.getElementById('pdfPreview');

                const opt = {
                    margin: 0,
                    filename: 'pesanan.pdf',
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2,
                        useCORS: true,
                        scrollY: 0,
                        scrollX: 0,
                        letterRendering: true,
                        backgroundColor: null // ← penting agar tidak pakai background default
                    },
                    jsPDF: {
                        unit: 'mm',
                        format: 'a4',
                        orientation: 'portrait'
                    }
                };


                html2pdf().set(opt).from(element).outputPdf('blob').then(function(pdfBlob) {
                    const pdfFile = new File([pdfBlob], 'pesanan.pdf', {
                        type: 'application/pdf'
                    });
                    const formData = new FormData($('#formSurat')[0]);
                    formData.append('pdf_file', pdfFile);
                    formData.append('bmn_list_json', JSON.stringify(
                        Object.entries(bmnCount).map(([nama_bmn, jumlah]) => ({
                            nama_bmn,
                            jumlah
                        }))
                    ));
                    $.ajax({
                        url: '{{ url('/pemeliharaan-admin/store-pesanan') }}',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function() {
                            html2pdf().set(opt).from(element).save();
                            alert('Pesanan berhasil dikirim!');
                            window.location.href = '{{ url('/pemeliharaan-admin') }}';
                        },
                        error: function() {
                            alert('Terjadi kesalahan saat mengirim data.');
                        }
                    });
                });
            });
        });
    </script>
@endsection
