<?php

require_once( 'uploadtoaws.php' );

ini_set('max_execution_time', 600);
ini_set('memory_limit', '1024M');

// AWS access info
define('awsAccessKey', 'XXXXXXXX');
define('awsSecretKey', 'YYYYYYYYYYY');

$zip_file = 'file-backup-' . date("Ymd-His", time()) . '.zip';

// Get real path for our folder
$rootPath = 'PATH';



// Initialize archive object
$zip = new ZipArchive();
$zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);

// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file) {
    // Skip directories (they would be added automatically)
    if (!$file->isDir()) {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        // Add current file to archive
        $zip->addFile($filePath, $relativePath);
    }
}

// Zip archive will be created only after closing object
$zip->close();

try {
    $abs_path = "LOCALPATH";
    S3::setAuth(awsAccessKey, awsSecretKey);
    $bucketName = 'jrbackupprojects';
    //save to s3 bucket
    file_put_contents("PATH");    
    unlink($abs_path . '/' . $zip_file);
} catch (Exception $e) {
    //echo $e->getMessage();
    return false;
}