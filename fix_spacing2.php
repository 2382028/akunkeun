<?php
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('d:/laragon/www/akunkeun.lldikti4.id/resources/views/user'));
foreach ($files as $file) {
    if ($file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        
        $newContent = preg_replace('/<div class="mt-4">\s*<h3/', '<div><h3', $content);
        $newContent = preg_replace('/<section class="mb-5">/', '<section class="pb-5 pt-4">', $newContent);

        if ($content !== $newContent) {
            file_put_contents($file->getPathname(), $newContent);
            echo "Updated: " . $file->getPathname() . "\n";
        }
    }
}
