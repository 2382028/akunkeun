const fs = require('fs');

const filePath = 'resources/views/user/kegiatan/kegiatan_step2.blade.php';
let content = fs.readFileSync(filePath, 'utf-8');

// Isolate form content to not affect modals
let formStartIdx = content.indexOf('<form action="{{url(\'/c_kegiatan_all/\'');
let formEndIdx = content.indexOf('</form>', formStartIdx);

if (formStartIdx !== -1 && formEndIdx !== -1) {
    let before = content.substring(0, formStartIdx);
    let formContent = content.substring(formStartIdx, formEndIdx);
    let after = content.substring(formEndIdx);

    // Make inputs smaller
    formContent = formContent.replace(/class="form-control"/g, 'class="form-control form-control-sm"');
    formContent = formContent.replace(/class="form-control text-muted input-abuk"/g, 'class="form-control form-control-sm text-muted input-abuk"');
    
    // Reduce margins
    formContent = formContent.replace(/mb-3 row align-items-center/g, 'mb-2 row align-items-center');
    formContent = formContent.replace(/mb-3 row align-items-start/g, 'mb-2 row align-items-center');
    
    // Make labels smaller
    formContent = formContent.replace(/class="col-md-4 col-form-label"/g, 'class="col-md-4 col-form-label" style="font-size: 0.9rem;"');

    content = before + formContent + after;
    fs.writeFileSync(filePath, content, 'utf-8');
    console.log("Form compactness applied.");
} else {
    console.log("Form not found.");
}
