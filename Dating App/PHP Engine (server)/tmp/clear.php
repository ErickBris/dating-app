<?php

/*!
 * ifsoft.co.uk v1.0
 *
 * http://ifsoft.com.ua, http://ifsoft.co.uk
 * qascript@ifsoft.co.uk
 *
 * Copyright 2012-2016 Demyanchuk Dmitry (https://vk.com/dmitry.demyanchuk)
 */

include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");
include_once($_SERVER['DOCUMENT_ROOT']."/config/api.inc.php");

$files = glob('*.jpg'); // get all file names

foreach($files as $file){ // iterate files

    if (is_file($file)) {

        echo $file."<br>";

        unlink($file); // delete file
    }
}

$fi = new FilesystemIterator(__DIR__, FilesystemIterator::SKIP_DOTS);
printf("There were %d Files", iterator_count($fi));

$cleaner = new cleaner($dbo);

print_r($cleaner->cleanPhotos());

print_r($cleaner->cleanGallery());

unset($cleaner);