const fs = require('fs');

const filePath = 'resources/views/user/kegiatan/kegiatan_step2.blade.php';
let content = fs.readFileSync(filePath, 'utf-8');

// 1. Reduce top spacing
content = content.replace('<section id="beranda" class=" pb-5 mt-5 pt-5">', '<section id="beranda" class="pb-5 mt-3 pt-3">');

// 2. Reduce card padding
content = content.replace('<div class="card shadow-sm rounded-0 border-0 p-4"', '<div class="card shadow-sm rounded-0 border-0 p-3"');

// 3. Reduce progressbar margin
content = content.replace('<div class="progressbar col-md-8 mx-auto mb-4">', '<div class="progressbar col-md-8 mx-auto mb-2" style="transform: scale(0.9);">');

// 4. Reduce margin bottom of rows from mb-3 to mb-2 globally inside the form
// We only want to target the rows inside the main form.
const formStartRegex = /<form action=".*?c_kegiatan_all.*?id="myForm">/;
const formEndRegex = /<div class="d-flex justify-content-evenly mt-5 border-top pt-4">/;

let formMatch = content.match(formStartRegex);
let endMatch = content.match(formEndRegex);

if (formMatch && endMatch) {
    let before = content.substring(0, formMatch.index);
    let formContent = content.substring(formMatch.index, endMatch.index);
    let after = content.substring(endMatch.index);

    // Replace mb-3 with mb-1
    formContent = formContent.replace(/mb-3/g, 'mb-1');
    // Replace form-control with form-control-sm
    formContent = formContent.replace(/class="form-control"/g, 'class="form-control form-control-sm"');
    formContent = formContent.replace(/class="form-control text-muted input-abuk"/g, 'class="form-control form-control-sm text-muted input-abuk"');
    // Add small font to labels
    formContent = formContent.replace(/class="col-md-4 col-form-label"/g, 'class="col-md-4 col-form-label" style="font-size: 0.85rem;"');

    content = before + formContent + after;
}

// 5. Reduce spacing for the action buttons at the bottom
content = content.replace('<div class="d-flex justify-content-evenly mt-5 border-top pt-4">', '<div class="d-flex justify-content-evenly mt-3 border-top pt-3">');

fs.writeFileSync(filePath, content, 'utf-8');
console.log("Compacted layout successfully.");
