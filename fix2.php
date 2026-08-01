<?php
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('resources/views'));
foreach($files as $file) {
    if($file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        if(strpos($content, "route('proses')") !== false || strpos($content, 'route("proses")') !== false) {
            $content = str_replace(["route('proses')", 'route("proses")'], "route('user.calibrations.index')", $content);
            file_put_contents($file->getPathname(), $content);
            echo "Updated: " . $file->getPathname() . "\n";
        }
    }
}
echo "Done!\n";
