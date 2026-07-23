<?php
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('d:/laragon/www/akunkeun.lldikti4.id/resources/views/user'));
foreach ($files as $file) {
    if ($file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        
        // 1. Ganti <section class=" pb-5 mt-5 pt-5">
        $newContent = preg_replace('/class=(["\'])\s*pb-5\s+mt-[0-9]\s+pt-[0-9]\s*(["\'])/', 'class=$1pb-5 pt-4$2', $content);
        
        // 2. Ganti <section class="pb-5 mt-5 pt-5">
        $newContent = preg_replace('/class=(["\'])\s*pb-5\s+mt-[0-9]\s+pt-[0-9]\s*(["\'])/', 'class=$1pb-5 pt-4$2', $newContent);

        // 3. Juga cari yang form index.blade.php perjadin yang ada <div class="mt-4">
        if (strpos($file->getPathname(), 'perjadin\index.blade.php') !== false || 
            strpos($file->getPathname(), 'perjadin\index_edit.blade.php') !== false ||
            strpos($file->getPathname(), 'perjadin\ajukan-ulang.blade.php') !== false) {
            $newContent = preg_replace('/<div class="mt-4">/', '<div>', $newContent);
            $newContent = preg_replace('/<section class="mb-5">/', '<section class="pb-5 pt-4">', $newContent);
        }

        if ($content !== $newContent) {
            file_put_contents($file->getPathname(), $newContent);
            echo "Updated: " . $file->getPathname() . "\n";
        }
    }
}
