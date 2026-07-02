@extends('admin.templates.sidebar')

@section('contain')
<style>
    @font-face {
        font-family: 'CedarvilleCursive';
        src: url('{{ asset(' assets/fonts/CedarvilleCursive-Regular.ttf') }}') format('truetype');
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
                <div class="card mt-3">
                    <div class="card-body">
                        <form id="formSurat">
                            @csrf
                            <input type="hidden" id="keterangan_bmn" name="keterangan_bmn" value="{{ old('keterangan_bmn', '') }}">

                            @foreach ($selected_ids as $id)
                            <input type="hidden" name="selected_ids[]" value="{{ $id }}">
                            @endforeach
                            @foreach ($ketBmn as $item)
                            <input type="hidden" name="keterangan_bmn[{{ $loop->index }}]"
                                data-label="{{ $item['nama_bmn'] }}" value="">
                            @endforeach

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Nomor Surat <span class="text-danger">*</span></label>
                                    <input type="text" required class="form-control" id="nomor_surat_final" name="nomor_surat_final">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Perihal <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="perihal" id="perihal" placeholder="Isi perihal" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Surat <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tanggal" id="tanggal" 
                                    value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Penyedia <span class="text-danger">*</span></label>
                                <select class="form-select select2" name="pihak_kedua" id="pihak_kedua" required>
                                    <option value="">-- Pilih Penyedia --</option>
                                    @foreach ($penyedias as $cv)
                                    <option value="{{ $cv->id }}">{{ $cv->nama_CV }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Penanda Tangan <span class="text-danger">*</span></label>
                                <select class="form-select select2" name="penanda_tangan" id="penanda_tangan" required>
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
                                <label class="form-label">Pembuka <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="opening" id="opening" required>Dengan ini kami meminta Saudara dapat melaksanakan pekerjaan Perawatan/Pemeliharaan Barang Milik Negara (BMN) berupa {{ implode(', ', $kategoriBmn) ?: '{kategori}' }} dengan memperbaiki atau membeli barang (spare part) yang diperlukan sebagai berikut:</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea id="keteranganTextArea" name="keterangan_bmn" class="form-control" rows="3"
                                    placeholder="(opsional) Isi keterangan untuk masing-masing barang"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Penutup <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="ending" id="ending" required>Selanjutnya apabila Saudara membutuhkan keterangan dan penjelasan lebih lanjut terkait dengan perbaikan ini, Saudara dapat menghubungi kami di Layanan Tata usaha dan BMN, Bagian Umum LLDIKTI Wilayah IV.</textarea>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary" id="kirim">Simpan Dokumen</button>
                            </div>

                        </form>
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div> <!-- end col-md-6 -->

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
        let keteranganGabungan = @json($ketBmn)
            .map(item => item.keterangan ?? '') // ambil keterangan masing-masing item
            .filter(k => k.trim() !== '') // buang yang kosong
            .join(', '); // gabungkan dengan koma
        console.log('Keterangan Gabungan:', keteranganGabungan);
        // Isi textarea utama dan input hidden
        $('#keterangan_bmn').val(keteranganGabungan);
        $('#keteranganTextArea').val(keteranganGabungan);
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
            if (this.id === 'keteranganTextArea') {
                $('#keterangan_bmn').val($(this).val());
            }
            renderPreview();
        });


        const ketBmn = @json($ketBmn);
        const nipNik = @json($nipNik);
        // Hitung jumlah berdasarkan nama_bmn + merk_bmn
        let bmnCount = {};
        ketBmn.forEach(item => {
            let merk = item.merk_bmn || '-';
            let key = `${item.nama_bmn}||${merk}`; // gunakan '||' sebagai pemisah unik
            if (!bmnCount[key]) {
                bmnCount[key] = {
                    nama_bmn: item.nama_bmn,
                    merk_bmn: merk,
                    jumlah: 0
                };
            }
            bmnCount[key].jumlah += 1;
        });

        function renderPreview() {
            let kopUrl = {!! json_encode($kopSurat ? url('/getDokumen/' . basename($kopSurat->url_kop)) : '') !!};

            let kopHtml = kopUrl ?
                `<div style="text-align:center;"><img src="${kopUrl}" style="max-height:120px;"></div>` :
                '';
            let nomorSurat = $('#nomor_surat_final').val();
            let nomorSuratFinal = nomorSurat ?
                nomorSurat : '-';
            let perihal = $('#perihal').val();
            let tanggal = $('#tanggal').val();
            let pihakKedua = $('#pihak_kedua option:selected').text();
            let opening = $('#opening').val();
            let ending = $('#ending').val();
            let penanda = $('#penanda_tangan option:selected').text();
            let penandaText = penanda?.trim() || '-';
            let firstWord = penandaText !== '-' ? penandaText.split(/\s+/)[0] : '-';

            // Ambil keterangan dari input hidden gabungan
            let keterangan = $('#keterangan_bmn').val() || '-';

            // Buat baris tabel dari ketBmn
            let rows = '';
            Object.values(bmnCount).forEach((item, i) => {
                rows += `<tr>
        <td style="width:5%;">${i + 1}</td>
        <td style="width:30%;">${item.nama_bmn}</td>
        <td style="width:20%;">${item.merk_bmn}</td>
        <td style="width:10%; text-align:center;">${item.jumlah}</td>
        ${i === 0 ? `<td style="width:40%;" rowspan="${Object.values(bmnCount).length}">${keterangan}</td>` : ''}
    </tr>`;
            });


            $('#pdfPreview').html(`
        ${kopHtml}
        <div style="background: #fff; color: #000; padding: 20px;">
            <div class="p-3">
                <div class="d-flex justify-content-between">
                    <div>Nomor: ${nomorSuratFinal || '-'}</div>
                    <div>Bandung, ${new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }).format(new Date(tanggal))}</div>
                </div>
                Perihal: ${perihal || '-'}<br>
                Yth ${pihakKedua || '-'}<br>
                ${alamatCV || ''}
                <p style="text-align: justify;">${opening || ''}</p>
                <table border="1" cellpadding="5" cellspacing="0" width="100%" style="table-layout: fixed;">
                    <thead>
                        <tr>
    <th style="width:5%;">No.</th>
    <th style="width:20%;">Nama Barang</th>
    <th style="width:30%;">Merk</th>
    <th style="width:10%;">Jumlah</th>
    <th style="width:35%;">Keterangan</th>
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

        $('#formSurat').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
const tanggalSurat = $('#tanggal').val();
    formData.append('tanggal', tanggalSurat);
            let nomorSuratFinal = $('#nomor_surat_final').val();

            formData.append('nomorSuratFinal', nomorSuratFinal);

            // Kirim bmn_list_json lengkap dengan merk_bmn
            const bmnList = Object.values(bmnCount).map(item => ({
                nama_bmn: item.nama_bmn,
                merk_bmn: item.merk_bmn || '-',
                jumlah: item.jumlah
            }));
            formData.append('bmn_list_json', JSON.stringify(bmnList));

            $.ajax({
    url: '{{ url('/pemeliharaan-admin/store-pesanan') }}',
    method: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    success: function(res) {
        alert('Pesanan berhasil dikirim!');
        window.location.href = '{{ url('/pemeliharaan-admin') }}';
    },
    error: function(xhr, status, error) {
        let responseText = xhr.responseText;
        try {
            let json = JSON.parse(responseText);
            alert('Terjadi kesalahan: ' + (json.message || error));
            console.error('Detail:', json);
        } catch (e) {
            alert('Terjadi kesalahan pada server:\n' + responseText);
            console.error('Raw response:', responseText);
        }
    }
});

        });


    });
</script>
@endsection