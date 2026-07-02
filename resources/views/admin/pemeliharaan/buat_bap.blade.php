@extends('admin.templates.sidebar')

@section('contain')
    <link rel="preload" href="/assets/fonts/CedarvilleCursive-Regular.ttf" as="font" type="font/ttf"
        crossorigin="anonymous">

    <style>
        /* === Elemen Preview PDF === */
        #preview-bast,
        #preview-bap,
        #pdf-bast,
        #pdf-bap {
            width: 210mm;
            min-height: 297mm;
            padding: 1mm 10mm 10mm 10mm;
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
            text-align: justify;
        }
    </style>
    <section class="mb-5">
        <div class="container">
            <h4 class="mt-4">Pembuatan Berita Acara Serah Terima & Pembayaran</h4>
            <form id="form-bap" method="POST" enctype="multipart/form-data"
    action="{{ url('/pemeliharaan-admin/store-bap') }}">
    @csrf
    <input type="hidden" name="pengajuan" value="{{ request()->pengajuan }}">
    <input type="file" name="pdf_bap" id="input_pdf_bap" hidden>
    <input type="file" name="pdf_bast" id="input_pdf_bast" hidden>
    <input type="hidden" name="pesanan" id="pesanan"
        value="{{ implode(',', $pesananList->pluck('nomor_surat')->toArray()) }}">
    <div class="card mt-3">
        <div class="card-body content">
            <div class="row mb-3">
    <!-- BAST -->
    <div class="col-md-6">
        <label class="form-label">Nomor BAST</label>
        <div class="d-flex">
            <input type="text" name="nomor_bast" id="nomor_bast" class="form-control" required>
            <input type="date" name="tanggal_bast" id="tanggal_bast" class="form-control ms-2" 
                   value="{{ date('Y-m-d') }}" required>
        </div>
    </div>

    <!-- BAP -->
    <div class="col-md-6">
        <label class="form-label">Nomor BAP</label>
        <div class="d-flex">
            <input type="text" name="nomor_bap" id="nomor_bap" class="form-control" required>
            <input type="date" name="tanggal_bap" id="tanggal_bap" class="form-control ms-2" 
                   value="{{ date('Y-m-d') }}" required>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end mt-3">
    <button id="submitDokumen" class="btn btn-success">Kirim Dokumen</button>
</div>
        </div>
    </div>
    <h5 class="mt-5">Preview Dokumen</h5>
    <div class="d-flex flex-column flex-md-row justify-content-between">
        <div id="preview-bast" class="me-md-3 mb-4"></div>
        <div id="preview-bap" class="mb-4"></div>
    </div>
</form>

        </div>
        <div id="pdf-bast" style="visibility:hidden; position:fixed; top:-9999px;"></div>
        <div id="pdf-bap" style="visibility:hidden; position:fixed; top:-9999px;"></div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>     
        const perihal = @json($perihal);
        const pesananList = @json($pesananList);
        const nilai_pekerjaan = @json($nilai_pekerjaan);
        const penyedia = @json($penyedia);
        const pegawai = @json($pegawai);
        const username = @json($username);
        const nilaiPPN = @json($ppn->nilai_ppn ?? 0); // misal 11 untuk 11%

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }

        function capitalizeEachWord(text) {
            return text.replace(/\b\w/g, char => char.toUpperCase());
        }

        function terbilang(nilai) {
            const satuan = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan"];
            const tingkat = ["", "ribu", "juta", "miliar", "triliun"];
            let hasil = "";
            let angka = nilai.toString().split("").reverse().join("");
            let kelompok = angka.match(/\d{1,3}/g);
            for (let i = 0; i < kelompok.length; i++) {
                let k = parseInt(kelompok[i].split("").reverse().join(""));
                if (k === 0) continue;
                let str = "";
                const ratus = Math.floor(k / 100);
                const puluh = Math.floor((k % 100) / 10);
                const satu = k % 10;
                if (ratus > 0) str += (ratus === 1 ? "seratus" : satuan[ratus] + " ratus") + " ";
                if (puluh === 1) {
                    if (satu === 0) str += "sepuluh";
                    else if (satu === 1) str += "sebelas";
                    else str += satuan[satu] + " belas";
                } else {
                    if (puluh > 1) str += satuan[puluh] + " puluh ";
                    if (satu > 0) str += satuan[satu];
                }
                hasil = str + " " + tingkat[i] + " " + hasil;
            }
            return hasil.trim() + " rupiah";
        }

        function tahunTerbilang(tahun) {
            const satuan = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan"];
            return "dua ribu " + (tahun % 100 < 10 ?
                satuan[tahun % 10] :
                (tahun % 100 < 20 ? ["sepuluh", "sebelas", "dua belas", "tiga belas", "empat belas", "lima belas",
                        "enam belas",
                        "tujuh belas", "delapan belas", "sembilan belas"
                    ][tahun % 100 - 10] :
                    satuan[Math.floor((tahun % 100) / 10)] + " puluh " + satuan[tahun % 10]));
        }


        function tanggalTerbilang(dateStr) {
            const bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];
            const tgl = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh",
                "sebelas", "dua belas", "tiga belas", "empat belas", "lima belas", "enam belas", "tujuh belas",
                "delapan belas", "sembilan belas", "dua puluh", "dua puluh satu", "dua puluh dua", "dua puluh tiga",
                "dua puluh empat", "dua puluh lima", "dua puluh enam", "dua puluh tujuh", "dua puluh delapan",
                "dua puluh sembilan", "tiga puluh", "tiga puluh satu"
            ];
            const d = new Date(dateStr);
            return `${tgl[d.getDate()]} ${bulan[d.getMonth()]} ${tahunTerbilang(d.getFullYear())}`;
        }

        function updatePreview() {
            const jumlah = Object.values(nilai_pekerjaan).reduce((a, b) => a + b, 0);
            const ppnValue = Math.round(jumlah * nilaiPPN / 100);
            const totalAkhir = jumlah + ppnValue;
            let kopUrl = {!! json_encode($kopSurat ? url('/getDokumen/' . basename($kopSurat->url_kop)) : '') !!};
            let kopHtml = kopUrl
    ? `<div style="text-align:center; margin-bottom:1rem;">
          <img src="${kopUrl}" style="width:100%; object-fit:contain;">
       </div>`
    : '';


            const nomorBAST = document.getElementById('nomor_bast').value || '-';
            const nomorBAP = document.getElementById('nomor_bap').value || '-';
            const tanggalBAST = document.getElementById('tanggal_bast').value || new Date().toISOString().slice(0, 10);
            const bulanNama = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];
            const tanggalBAP = document.getElementById('tanggal_bap').value || new Date().toISOString().slice(0, 10);
            const dBAST = new Date(tanggalBAST);
            const tanggalBiasa = `${String(dBAST.getDate()).padStart(2, '0')} ${bulanNama[dBAST.getMonth()]} ${dBAST.getFullYear()}`;
            const dBAP = new Date(tanggalBAP);
            const tanggalBiasaBAP = `${String(dBAP.getDate()).padStart(2, '0')} ${bulanNama[dBAP.getMonth()]} ${dBAP.getFullYear()}`;
            
            const tahun = dBAST.getFullYear();
            const tanggalStr = capitalizeEachWord(tanggalTerbilang(tanggalBAST));
            const tanggalStrBAP = capitalizeEachWord(tanggalTerbilang(tanggalBAP));

            const nilaiTotalTerbilang = capitalizeEachWord(terbilang(totalAkhir));
            const listPerihal = Array.isArray(perihal) ? perihal : perihal.split(', ');

            // === Preview Serah Terima ===
            let preview1 = `
            ${kopHtml}
        <p class="text-center" style="margin-bottom: 1rem;"><strong>BERITA ACARA SERAH TERIMA</strong></p>
<div class="tabel-custom-nontabel" style="display: table; margin: 0 auto; margin-bottom: 1rem;">
  <div class="row">
    <div class="label">Nomor</div>
    <div class="separator">:</div>
    <div class="value">${nomorBAST ?? '-'}</div>
  </div>
  <div class="row">
    <div class="label">Tanggal</div>
    <div class="separator">:</div>
    <div class="value">${tanggalBiasa ?? '-'}</div>
  </div>
</div>

        <p>Pada hari ini, Tanggal ${tanggalStr}, kami yang bertanda tangan di bawah ini:</p>
<div class="tabel-custom-nontabel">
  <div class="row">
    <div class="label">Nama</div>
    <div class="separator">:</div>
    <div class="value">${username ?? '-'}</div>
  </div>
  <div class="row">
    <div class="label">NIP</div>
    <div class="separator">:</div>
    <div class="value">${pegawai.NIP_NIK ?? '-'}</div>
  </div>
  <div class="row">
    <div class="label">Jabatan</div>
    <div class="separator">:</div>
    <div class="value">Pejabat Pembuat Komitmen</div>
  </div>
  <div class="row">
    <div class="label">Alamat</div>
    <div class="separator">:</div>
    <div class="value">Jalan P.H. Hasan Mustafa Nomor 38 Bandung</div>
  </div>
</div>
        <p>Bertindak untuk dan atas nama Lembaga Layanan Pendidikan Tinggi Wilayah IV, selanjutnya disebut Pihak Kesatu.</p>
        <div class="tabel-custom-nontabel">
  <div class="row">
    <div class="label">Nama</div>
    <div class="separator">:</div>
    <div class="value">${penyedia.penanggung_jawab ?? '-'}</div>
  </div>
  <div class="row">
    <div class="label">Jabatan</div>
    <div class="separator">:</div>
    <div class="value">${penyedia.jabatan ?? '-'}</div>
  </div>
  <div class="row">
    <div class="label">Alamat</div>
    <div class="separator">:</div>
    <div class="value">${penyedia.alamat ?? '-'}</div>
  </div>
</div>
        <p>Dalam hal ini bertindak untuk dan atas nama ${penyedia.nama_CV ?? '-'} yang selanjutnya disebut Pihak Kedua.
            Dengan ini Kedua Belah Pihak setuju dan sepakat melakukan serah terima pekerjaan di Lingkungan Lembaga Layanan Pendidikan Tinggi Wilayah IV Tahun Anggaran ${tahun} dengan ketentuan sebagai berikut:</p>
        <p class="mt-1" style="text-align: center;">Pasal 1</p>
        <p>Pihak Kedua telah menyerahkan pekerjaan kepada Pihak Kesatu dan Pihak Kesatu menerima:</p>
        <table border="1" width="100%" cellspacing="0" cellpadding="5">
            <thead><tr><th>No.</th><th>Pekerjaan</th><th>Berdasarkan</th><th>Nilai Pekerjaan</th></tr></thead>
<tbody>
  ${listPerihal.map((p, i) => {
    const data = pesananList[i];
    const rawDate = data?.created_at ?? null;
    const berdasarkan = data ? `${data.nomor_surat}` : '-';
    const dateFormatted = rawDate
        ? new Date(rawDate).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        })
        : '-';
    const perihalText = p.split('-')[1] ?? p;

    const nilaiPekerjaan = nilai_pekerjaan[data.nomor_surat] ?? 0;

    return `
<tr>
  <td style="text-align:center;">${i + 1}</td>
  <td>${perihalText}</td>
  <td>Surat Pesanan nomor ${berdasarkan} tanggal ${dateFormatted}</td>
  <td>${formatRupiah(nilaiPekerjaan)}</td>
</tr>
`;
}).join('')}

<tr>
  <td colspan="3" style="text-align:right;">Jumlah: </td>
  <td>${formatRupiah(jumlah)}</td>
</tr>
<tr>
  <td colspan="3" style="text-align:right;">PPN ${nilaiPPN}%: </td>
  <td>${formatRupiah(ppnValue)}</td>
</tr>
<tr>
  <td colspan="3" style="text-align:right;"><strong>Total: </strong></td>
  <td><strong>${formatRupiah(totalAkhir)}</strong></td>
</tr>
</tbody>
</table>

<p style="text-align: center;">Pasal 2</p>
<p>Pihak kedua berhak mendapatkan pembayaran sebesar 100% x Rp ${totalAkhir.toLocaleString()} = Rp ${totalAkhir.toLocaleString()} (${nilaiTotalTerbilang})</p>

<p class="mt-1" style="text-align: center">Pasal 3</p>
<p>Pembayaran yang dilakukan saat berita acara ini dibuat merupakan pembayaran pelunasan keseluruhan nilai pekerjaan.
Demikian Berita Acara Serah Terima ini dibuat dan ditandatangani di Bandung dalam rangkap secukupnya untuk dipergunakan sebagaimana mestinya.</p>
<div style="display:flex; justify-content:space-between; margin-top:40px;">
  <div>
    <p>Pihak Kedua:</p><br><br><br><br>
    <p>${penyedia.penanggung_jawab ?? '-'}<br>
        ${penyedia.jabatan ?? '-'}</p>
  </div>
  <div style="text-align:right;">
    <p>Pihak Kesatu:<br>
    Pejabat Pembuat Komitmen<br>
    LLDIKTI Wilayah IV,</p>
    <span class="tanda-tangan">${username.split(' ')[0]}</span>
    <br>
    <p>${username}</p>
    <p>NIP: ${pegawai.NIP_NIK ?? '-'}</p>
  </div>
</div>
`;

            // === Preview Pembayaran ===
            let preview2 = `
            ${kopHtml}
<p class="text-center" style="margin-bottom: 1rem;"><strong>BERITA ACARA PEMBAYARAN</strong></p>
<div class="tabel-custom-nontabel" style="display: table; margin: 0 auto; margin-bottom: 1rem;">
  <div class="row">
    <div class="label">Nomor</div>
    <div class="separator">:</div>
    <div class="value">${nomorBAP ?? '-'}</div>
  </div>
  <div class="row">
    <div class="label">Tanggal</div>
    <div class="separator">:</div>
    <div class="value">${tanggalBiasaBAP ?? '-'}</div>
  </div>
</div>
<p>Pada hari ini, Tanggal ${tanggalStrBAP}, kami yang bertanda tangan dibawah ini:</p>
<div class="tabel-custom-nontabel">
  <div class="row">
    <div class="label">Nama</div>
    <div class="separator">:</div>
    <div class="value">${username ?? '-'}</div>
  </div>
  <div class="row">
    <div class="label">NIP</div>
    <div class="separator">:</div>
    <div class="value">${pegawai.NIP_NIK ?? '-'}</div>
  </div>
  <div class="row">
    <div class="label">Jabatan</div>
    <div class="separator">:</div>
    <div class="value">Pejabat Pembuat Komitmen</div>
  </div>
  <div class="row">
    <div class="label">Alamat</div>
    <div class="separator">:</div>
    <div class="value">Jalan P.H. Hasan Mustafa Nomor 38 Bandung</div>
  </div>
</div>
<p>Bertindak untuk dan atas nama Lembaga Layanan Pendidikan Tinggi Wilayah IV, selanjutnya disebut Pihak Kesatu.</p>
<div class="tabel-custom-nontabel">
  <div class="row">
    <div class="label">Nama</div>
    <div class="separator">:</div>
    <div class="value">${penyedia.penanggung_jawab ?? '-'}</div>
  </div>
  <div class="row">
    <div class="label">Jabatan</div>
    <div class="separator">:</div>
    <div class="value">${penyedia.jabatan ?? '-'}</div>
  </div>
  <div class="row">
    <div class="label">Alamat</div>
    <div class="separator">:</div>
    <div class="value">${penyedia.alamat ?? '-'}</div>
  </div>
</div>


<p>Dalam hal ini bertindak untuk dan atas nama ${penyedia.nama_CV ?? '-'} yang selanjutnya disebut Pihak Kedua. Dengan ini menyatakan, Kedua Belah Pihak setuju dan bahwa untuk:</p>
<table border="1" width="100%" cellspacing="0" cellpadding="5">
  <thead>
    <tr><th>No.</th><th>Pekerjaan</th><th>Berdasarkan</th><th>Nilai Pekerjaan</th></tr>
  </thead>
  <tbody>
  ${listPerihal.map((p, i) => {
    const data = pesananList[i];
    const rawDate = data?.created_at ?? null;
    const berdasarkan = data ? `${data.nomor_surat}` : '-';
    const dateFormatted = rawDate
        ? new Date(rawDate).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        })
        : '-';
    const perihalText = p.split('-')[1] ?? p;

    const nilaiPekerjaan = nilai_pekerjaan[data.nomor_surat] ?? 0;

    return `
<tr>
  <td style="text-align:center;">${i + 1}</td>
  <td>${perihalText}</td>
  <td>Surat Pesanan nomor ${berdasarkan} tanggal ${dateFormatted}</td>
  <td>${formatRupiah(nilaiPekerjaan)}</td>
</tr>
`;
}).join('')}

<tr>
  <td colspan="3" style="text-align:right;">Jumlah: </td>
  <td>${formatRupiah(jumlah)}</td>
</tr>
<tr>
  <td colspan="3" style="text-align:right;">PPN ${nilaiPPN}%: </td>
  <td>${formatRupiah(ppnValue)}</td>
</tr>
<tr>
  <td colspan="3" style="text-align:right;"><strong>Total: </strong></td>
  <td><strong>${formatRupiah(totalAkhir)}</strong></td>
</tr>
  </tbody>
</table>

<p>Berdasarkan Surat Pesanan tersebut di atas, maka Pihak Kedua telah berhak menerima dari Pihak Pertama sejumlah 100% x Rp ${totalAkhir.toLocaleString()} = Rp ${totalAkhir.toLocaleString()} (${nilaiTotalTerbilang}). Demikian Berita Acara Pembayaran ini dibuat dan ditandatangani di Bandung dalam rangkap secukupnya untuk dipergunakan sebagaimana mestinya.</p>

<div style="display:flex; justify-content:space-between; margin-top:40px;">
  <div>
    <p>Pihak Kedua:</p><br><br><br><br>
    <p>${penyedia.penanggung_jawab ?? '-'}<br>
        ${penyedia.jabatan ?? '-'}</p>
  </div>
  <div style="text-align:right;">
    <p>Pihak Kesatu:<br>
    Pejabat Pembuat Komitmen<br>
    LLDIKTI Wilayah IV,</p>
    <span class="tanda-tangan">${username.split(' ')[0]}</span>
<br>
    <p>${username}</p>
    <p>NIP: ${pegawai.NIP_NIK ?? '-'}</p>
  </div>
</div>
`;

            document.getElementById('preview-bast').innerHTML = preview1;
            document.getElementById('preview-bap').innerHTML = preview2;
        }
document.getElementById('submitDokumen').addEventListener('click', async function(e) {
    e.preventDefault();

    const bastElement = document.getElementById('preview-bast');
    const bapElement  = document.getElementById('preview-bap');

    const opt = {
        filename: 'temp.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
    };

    function cleanBase64(dataUri) {
        return dataUri.replace(/^data:application\/pdf(;filename=.*)?;base64,/, '');
    }

    try {
        // Generate PDF base64 dari preview
        const [bastPdf, bapPdf] = await Promise.all([
            html2pdf().from(bastElement).set(opt).outputPdf('datauristring'),
            html2pdf().from(bapElement).set(opt).outputPdf('datauristring')
        ]);

        const formData = {
        nomor_bast: document.getElementById('nomor_bast').value,
        tanggal_bast: document.getElementById('tanggal_bast').value,
        nomor_bap: document.getElementById('nomor_bap').value,
        tanggal_bap: document.getElementById('tanggal_bap').value,
        pesanan: document.getElementById('pesanan').value.split(','),
        pdf_bast: cleanBase64(bastPdf),
        pdf_bap: cleanBase64(bapPdf),
        _token: document.querySelector('input[name="_token"]').value
        };
        const response = await fetch('/pemeliharaan-admin/kirim-bap', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': formData._token
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();
        if (response.ok) {
            alert('Dokumen berhasil disimpan');
            window.location.href = '/pemeliharaan-admin?tab=pembayaran';
        } else {
            alert('Gagal: ' + data.message);
        }
    } catch (error) {
        console.error(error);
        alert('Terjadi kesalahan sistem.');
    }
});

        document.querySelectorAll('#form-bap input').forEach(el =>
            el.addEventListener('input', updatePreview)
        );
        document.querySelectorAll('#form-bap select').forEach(el =>
            el.addEventListener('change', updatePreview)
        );

        window.addEventListener('DOMContentLoaded', updatePreview);
    </script>
@endsection
