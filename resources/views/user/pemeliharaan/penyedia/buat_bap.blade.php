@extends('user.pemeliharaan.penyedia.sidebar')

@section('contain')
    <link rel="preload" href="/assets/fonts/CedarvilleCursive-Regular.ttf" as="font" type="font/ttf"
        crossorigin="anonymous">

    <style>
        /* === Elemen Preview PDF === */
        #preview-bast,
        #preview-bap, {
            width: 210mm;
            min-height: 297mm;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            overflow: visible !important;
            white-space: normal !important;
            word-wrap: break-word;
            max-width: 100% !important;
            max-height: none !important;
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
<section class="pb-5 pt-4">
    <div class="container">
        <form method="POST" action="{{ url('/penyedia/ttd-store-bap') }}" enctype="multipart/form-data" id="bapForm">
            @csrf
            <input type="hidden" name="pesanan" value="{{ implode(',', $pesananList->pluck('nomor_surat')->toArray()) }}">
            <input type="hidden" name="pdf_bast" id="pdf_bast_input">
            <input type="hidden" name="pdf_bap" id="pdf_bap_input">

            <!-- Card untuk tombol submit -->
            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
    <h4 class="mb-0">Tanda Tangan Dokumen BAST & BAP</h4>
    <button type="submit" class="btn btn-primary">
        Konfirmasi Tanda Tangan
    </button>
</div>


            <h5 class="mb-3 text-center">Preview Dokumen</h5>
            <!-- Card untuk preview dokumen -->
            <div class="card p-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch gap-3">
                    <div id="preview-bast" class="flex-fill rounded pe-3"></div>

                    <!-- Pembatas vertikal untuk desktop -->
                    <div class="d-none d-md-block" style="width:1px; background:#ccc;"></div>

                    <div id="preview-bap" class="flex-fill rounded pe-3"></div>
                </div>
            </div>
        </form>
    </div>
</section>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        const perihal = @json($perihal);
        const pesananList = @json($pesananList);
        const nilai_pekerjaan = @json($nilai_pekerjaan);
        const penyedia = @json($penyedia);
        const pegawai = @json($pegawai);
        const username = @json($username);
        const nilaiPPN = @json($ppn->nilai_ppn ?? 0);

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
    ? `<div style="text-align:center;">
           <img src="${kopUrl}" style="width:100%; height:auto; margin-bottom:20px;">
       </div>`
    : '';


            const nomorBAST = @json($nomorBAST);
            const nomorBAP = @json($nomorBAP);
            const tanggal = new Date().toISOString().slice(0, 10);
            const d = new Date(tanggal);
            const tahun = d.getFullYear();
            const nilaiTotalTerbilang = capitalizeEachWord(terbilang(totalAkhir));
            const bulanNama = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];
            const tanggalBiasa = `${d.getDate()} ${bulanNama[d.getMonth()]} ${d.getFullYear()}`;
            const tanggalStr = capitalizeEachWord(tanggalTerbilang(tanggal));
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
        <table border="1" width="100%" cellspacing="0" cellpadding="5" style="margin-top: 0.5rem;">
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
<p>Pihak kedua berhak mendapatkan pembayaran sebesar 100% x Rp ${totalAkhir.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })} = Rp ${totalAkhir.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })} (${nilaiTotalTerbilang})</p>

<p class="mt-1" style="text-align: center">Pasal 3</p>
<p>Pembayaran yang dilakukan saat berita acara ini dibuat merupakan pembayaran pelunasan keseluruhan nilai pekerjaan.
Demikian Berita Acara Serah Terima ini dibuat dan ditandatangani di Bandung dalam rangkap secukupnya untuk dipergunakan sebagaimana mestinya.</p>
<div style="display:flex; justify-content:space-between; margin-top:40px;">
  <div>
    <p>Pihak Kedua:</p><br>
    <span class="tanda-tangan">${penyedia.penanggung_jawab.split(' ')[0]}</span>
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
    <div class="value">${tanggalBiasa ?? '-'}</div>
  </div>
</div>
<p>Pada hari ini, Tanggal ${tanggalStr}, kami yang bertanda tangan dibawah ini:</p>
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
<table border="1" width="100%" cellspacing="0" cellpadding="5" style="margin-top: 0.5rem;">
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

<p>Berdasarkan Surat Pesanan tersebut di atas, maka Pihak Kedua telah berhak menerima dari Pihak Pertama sejumlah 100% x Rp ${totalAkhir.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })} = Rp ${totalAkhir.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })} (${nilaiTotalTerbilang}). Demikian Berita Acara Pembayaran ini dibuat dan ditandatangani di Bandung dalam rangkap secukupnya untuk dipergunakan sebagaimana mestinya.</p>

<div style="display:flex; justify-content:space-between; margin-top:40px;">
  <div>
    <p>Pihak Kedua:</p><br>
    <span class="tanda-tangan">${penyedia.penanggung_jawab.split(' ')[0]}</span>
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

document.getElementById('bapForm').addEventListener('submit', function(e) {
    e.preventDefault();

const bastElement = document.getElementById('preview-bast');
const bapElement = document.getElementById('preview-bap');

    const opt = {
        filename: 'temp.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
    };

    function cleanBase64(dataUri) {
        return dataUri.replace(/^data:application\/pdf(;filename=.*)?;base64,/, '');
    }

    Promise.all([
        html2pdf().from(bastElement).set(opt).outputPdf('datauristring'),
        html2pdf().from(bapElement).set(opt).outputPdf('datauristring')
    ]).then(([bastPdf, bapPdf]) => {
        document.getElementById('pdf_bast_input').value = cleanBase64(bastPdf);
        document.getElementById('pdf_bap_input').value = cleanBase64(bapPdf);
        e.target.submit();
    });
});
        window.addEventListener('DOMContentLoaded', updatePreview);
    </script>
@endsection
