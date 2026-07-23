const fs = require('fs');

const filePath = 'resources/views/user/kegiatan/kegiatan_step2.blade.php';
let content = fs.readFileSync(filePath, 'utf-8');

// 1. Reduce top padding
content = content.replace('<section id="beranda" class=" pb-5 mt-5 pt-5">', '<section id="beranda" class="pb-5 mt-2 pt-2">');

// 2. Wrap left column and right column
// First, find where form inputs start
const formStart = '<!-- Jumlah Peserta -->';
const middlePoint = '<div class="mb-3 row align-items-start">\n                            <label for="mobilitas" class="col-md-4 col-form-label">Fasilitas Tambahan</label>';
const formEndRegex = /<div class="d-flex justify-content-evenly mt-5 border-top pt-4">/;

// Inject opening wrappers for Left Column
content = content.replace(formStart, `
                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-lg-6 pe-lg-4">
                                <!-- Jumlah Peserta -->
`);

// Close Left Column and open Right Column before Fasilitas Tambahan
content = content.replace(middlePoint, `
                            </div>
                            <!-- Kolom Kanan -->
                            <div class="col-lg-6 ps-lg-4 border-start">
                                <div class="mb-3 row align-items-start">
                                    <label for="mobilitas" class="col-md-4 col-form-label">Fasilitas Tambahan</label>
`);

// Close Right Column before the action buttons
content = content.replace(formEndRegex, `
                            </div>
                        </div>
                        <div class="d-flex justify-content-evenly mt-5 border-top pt-4">
`);

fs.writeFileSync(filePath, content, 'utf-8');
console.log("Layout restructured successfully.");
