@extends('user.pemeliharaan.penyedia.sidebar')
@section('contain')
    <link rel="preload" href="/assets/fonts/CedarvilleCursive-Regular.ttf" as="font" type="font/ttf"
        crossorigin="anonymous">

    <style>
        /* === Elemen Preview PDF === */
        #pdfPreview {
            width: 210mm;
            min-height: 297mm;
            padding: 10mm;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            overflow: visible !important;
            white-space: normal !important;
            word-wrap: break-word;
            max-width: 100% !important;
            max-height: none !important;
            font-family: 'Times New Roman', serif !important;
        }

        @font-face {
            font-family: 'Cedarville Cursive';
            src: url('/assets/fonts/CedarvilleCursive-Regular.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        .tanda-tangan {
            font-family: "Cedarville Cursive", cursive;
            font-size: 32px;
            font-weight: 400;
            font-style: normal;
        }

        /* === Tabel Utama === */
        table {
            background-color: transparent !important;
            border: 1px solid black;
            margin-bottom: 0.5rem !important;
            border-collapse: collapse;
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

        /* === Tabel Non-Tabel (dengan titik dua sejajar) === */
        .tabel-custom-nontabel .row {
            display: flex;
            margin-bottom: 2px;
            line-height: 1.2;
            font-family: 'Times New Roman', serif;
        }

        .tabel-custom-nontabel {
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .tabel-custom-nontabel .label {
            width: 70px;
            white-space: nowrap;
        }

        .tabel-custom-nontabel .separator {
            width: 10px;
        }

        .tabel-custom-nontabel .value {
            flex: 1;
        }

        /* === Umum === */
        p {
            margin-bottom: 0;
            line-height: 1.1;
        }
    </style>
    <section class="mb-5">
        <div class="container">
            <h3 class="fw-bold text-secondary mt-4">Buat Pengajuan Pembayaran</h3>
            <div class="row">
                <!-- Form Input -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form id="formPengajuan" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label>Nomor Surat <span class="text-danger">*</span></label>
                                    <input type="text" name="nomor_surat" class="form-control" required>
                                </div>

                                @foreach ($data as $item)
                                    <input type="hidden" name="pesanan[]" value="{{ $item['nomor_surat_pesanan'] }}">
                                    <input type="hidden" name="nilai_kontrak[]" value="{{ $item['nilai_kontrak'] }}">
                                @endforeach

                                <div class="mb-3">
                                    <label>Lampiran <span class="text-danger">*</span></label>
                                    <div id="lampiran-container">
                                        <div class="row mb-2 lampiran-item">
                                            <div class="col-5">
                                                <input type="text" name="lampiran_nama[]" class="form-control"
                                                    placeholder="Nama Lampiran" required>
                                            </div>
                                            <div class="col-5">
                                                <input type="file" name="lampiran_file[]" class="form-control" required>
                                            </div>
                                            <div class="col-2 d-flex align-items-center">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm btn-remove">Hapus</button>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-sm btn-secondary" id="tambahLampiran">+ Tambah
                                        Lampiran</button>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Kirim</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <!-- Preview PDF -->
                <div class="col-md-6">
                    <div class="border p-3" style="min-height: 400px;" id="pdfPreview">
                        <h5 class="text-muted text-center">Preview PDF akan muncul di sini</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            let kopUrl = {!! json_encode($kopSurat ? url('/getDokumen/' . basename($kopSurat->url_kop)) : '') !!};
            let kopHtml = kopUrl
    ? `<div style="text-align:center; margin-bottom:1rem;">
          <img src="${kopUrl}" style="width:100%; object-fit:contain;">
       </div>`
    : 'Kop surat tidak ditemukan, mohon unggah kop surat di halaman pengaturan terlebih dahulu';
            let grandTotal = 0;
            $('#tambahLampiran').on('click', function() {
                const newItem = `
        <div class="row mb-2 lampiran-item">
            <div class="col-5">
                <input type="text" name="lampiran_nama[]" class="form-control" placeholder="Nama Lampiran" required>
            </div>
            <div class="col-5">
                <input type="file" name="lampiran_file[]" class="form-control" required>
            </div>
            <div class="col-2 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm btn-remove">Hapus</button>
            </div>
        </div>`;
                $('#lampiran-container').append(newItem);
            });

            $(document).on('click', '.btn-remove', function() {
                $(this).closest('.lampiran-item').remove();
            });

            const pesananData = @json($data);

            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(angka);
            }

            function renderPreview() {
                const nomorSurat = $('input[name="nomor_surat"]').val() || '-';
                const now = new Date();
                const tanggal = now.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });

                const nama = "{{ auth('penyedia')->user()->penanggung_jawab }}";
                const jabatan = "Pimpinan {{ auth('penyedia')->user()->nama_CV }}";
                const alamat = `{{ auth('penyedia')->user()->alamat }}`;

                let rows = '';
                let total = 0;

                $('input[name="nilai_kontrak[]"]').each(function(i) {
                    const rawVal = $(this).val().replace(/[^\d]/g, '');
                    const numeric = parseInt(rawVal || '0');
                    total += numeric;

                    const formatted = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(numeric);

                    const pesanan = pesananData[i]?.pesanan || '';
                    const nomor = pesanan.match(/pesanan_(.*?)\.pdf/);
                    const nomorPesanan = nomor ? nomor[1].replaceAll('_', '/') : '-';
                    const rawPerihal = pesananData[i]?.perihal || '-';
                    const perihal = rawPerihal.includes('-') ? rawPerihal.split('-')[1].trim() : rawPerihal;

                    rows += `<tr>
            <td>${i + 1}</td>
            <td>Nomor: ${nomorPesanan}</td>
            <td>${perihal}</td>
            <td>${formatted}</td>
        </tr>`;
                });

                // Ambil nilai_ppn dari backend (misal variabel global di-blade)
                const nilaiPpn = {{ \App\Models\Ppn::first()->nilai_ppn ?? 0 }};
                const jumlahFormatted = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(total);
                const ppnValue = Math.round((total * nilaiPpn) / 100);
                const ppnFormatted = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(ppnValue);
                grandTotal = total + ppnValue;
                const grandTotalFormatted = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(grandTotal);

                // Row Jumlah
                rows += `<tr>
        <td colspan="3" style="text-align:right;">Jumlah</td>
        <td>${jumlahFormatted}</td>
    </tr>`;

                // Row PPN
                rows += `<tr>
        <td colspan="3" style="text-align:right;">PPN ${nilaiPpn}%</td>
        <td>${ppnFormatted}</td>
    </tr>`;

                // Row Total (Jumlah + PPN)
                rows += `<tr>
        <td colspan="3" style="text-align:right; font-weight:bold;">Total</td>
        <td style="font-weight:bold;">${grandTotalFormatted}</td>
    </tr>`;

                // Fungsi untuk ubah angka ke teks Indonesia (terbilang)
                function toTerbilang(n) {
                    const satuan = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan",
                        "sembilan"
                    ];
                    const tingkat = ["", "ribu", "juta", "miliar", "triliun"];
                    if (n === 0) return "nol rupiah";

                    function convertTri(n) {
                        let ratus = Math.floor(n / 100);
                        let puluh = Math.floor((n % 100) / 10);
                        let satu = n % 10;
                        let str = "";

                        if (ratus > 0) str += (ratus === 1 ? "seratus" : satuan[ratus] + " ratus") + " ";
                        if (puluh > 1) {
                            str += satuan[puluh] + " puluh ";
                            if (satu > 0) str += satuan[satu] + " ";
                        } else if (puluh === 1) {
                            if (satu === 0) str += "sepuluh ";
                            else if (satu === 1) str += "sebelas ";
                            else str += satuan[satu] + " belas ";
                        } else if (satu > 0) {
                            str += satuan[satu] + " ";
                        }
                        return str.trim();
                    }

                    let result = "",
                        i = 0;
                    while (n > 0) {
                        let tri = n % 1000;
                        if (tri > 0) {
                            let prefix = convertTri(tri);
                            if (i === 1 && tri === 1) {
                                prefix = "seribu";
                            }
                            result = prefix + " " + tingkat[i] + " " + result;
                        }
                        n = Math.floor(n / 1000);
                        i++;
                    }

                    return result.trim() + " rupiah";
                }

                const totalTerbilang = toTerbilang(grandTotal);

                $('#pdfPreview').html(`
                ${kopHtml}
        <div style="padding:20px;color:#000;">
            <p>Kepada Yth,<br>Pejabat Pembuat Komitmen<br>LLDIKTI Wilayah IV<br>Jl. PH. Hasan Mustafa No.38 Bandung</p>
                    <div class="tabel-custom-nontabel">
  <div class="row">
    <div class="label">Nomor</div>
    <div class="separator">:</div>
    <div class="value">${nomorSurat ?? '-'}</div>
  </div>
  <div class="row">
    <div class="label">Perihal</div>
    <div class="separator">:</div>
    <div class="value">Permohonan Pembayaran</div>
  </div>
</div>
            <p>Dengan hormat, kami yang bertanda tangan di bawah ini:</p>
                    <div class="tabel-custom-nontabel">
  <div class="row">
    <div class="label">Nama</div>
    <div class="separator">:</div>
    <div class="value">${nama ?? '-'}</div>
  </div>
  <div class="row">
    <div class="label">Jabatan</div>
    <div class="separator">:</div>
    <div class="value">${jabatan ?? '-'}</div>
  </div>
  <div class="row">
    <div class="label">Alamat</div>
    <div class="separator">:</div>
    <div class="value">${alamat ?? '-'}</div>
  </div>
</div>
            <p>Sesuai dengan:</p>
            <table border="1" cellpadding="5" cellspacing="0" width="100%">
                <thead>
                    <tr><th>No</th><th>Surat Pesanan</th><th>Perihal</th><th>Nilai Kontrak</th></tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>
            <p>Total terbilang: ${totalTerbilang}</p>
            <p style="margin-top:10px;">Maka kami memohon untuk diberikan pembayaran karena pekerjaan tersebut sudah 100% dilaksanakan. Demikian surat permohonan ini kami sampaikan atas kebijaksanaannya kami ucapkan terima kasih.</p>
            <p style="text-align:right;">
            Bandung, ${tanggal}<br>
            Hormat kami,<br>
            Pemohon,<br><br>
            <span class="tanda-tangan">${nama.split(' ')[0]}</span><br><br>
            ${nama}
        </p>

        </div>
    `);
            }


            // Jalankan saat awal
            renderPreview();

            // Perbarui saat input berubah
            $('input[name="nomor_surat"], input[name="nilai_kontrak[]"]').on('input', renderPreview);


            // Kirim
            $('#formPengajuan').on('submit', function(e) {
                e.preventDefault();
                const element = document.getElementById('pdfPreview');
                const opt = {
                    margin: 0,
                    filename: 'pengajuan_temp.pdf',
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2,
                        useCORS: true,
                        scrollY: 0,
                        scrollX: 0,
                        letterRendering: true
                    },
                    jsPDF: {
                        unit: 'mm',
                        format: 'a4',
                        orientation: 'portrait'
                    }
                };

                html2pdf().set(opt).from(element).outputPdf('blob').then(function(pdfBlob) {
                    const pdfFile = new File([pdfBlob], 'pengajuan_temp.pdf', {
                        type: 'application/pdf'
                    });

                    const formData = new FormData();
                    formData.append('nomor_surat', $('input[name="nomor_surat"]').val());
                    $('input[name="pesanan[]"]').each(function() {
                        formData.append('pesanan[]', $(this).val());
                    });
                    $('input[name="nilai_kontrak[]"]').each(function() {
                        formData.append('nilai_kontrak[]', $(this).val());
                    });
                    formData.append('grandTotal', Math.round(grandTotal));
                    formData.append('pdf_file', pdfFile);

                    // Tambahkan lampiran dinamis (jika ada)
                    $('input[name="lampiran_nama[]"]').each(function(index) {
                        const nama = $(this).val();
                        const fileInput = $('input[name="lampiran_file[]"]')[index];
                        const file = fileInput?.files[0];
                        if (nama && file) {
                            formData.append('lampiran_nama[]', nama);
                            formData.append('lampiran_file[]', file);
                        }
                    });
                    const csrfToken = document.querySelector('input[name="_token"]').value;

                    fetch('/penyedia/pengajuan-pembayaran/store', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: formData
                        }).then(res => res.json())
                        .then(data => {
                            alert(data.message || 'Berhasil');
                            window.location.href = '/penyedia?tab=monitor';
                        }).catch(() => alert('Gagal mengirim.'));
                });
            });


            $('.rupiah').on('input', function() {
                const val = $(this).val().replace(/[^\d]/g, '');
                $(this).val(new Intl.NumberFormat('id-ID').format(val));
                renderPreview();
            });
        });
    </script>
@endsection
