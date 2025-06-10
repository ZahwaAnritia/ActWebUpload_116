<?php

session_start();

if (!isset($_GET['file'])) {
    header("Location: list_files.php");
    exit();
}

$uploadDir = 'uploads/';
$fileName = basename($_GET['file']);
$filePath = $uploadDir . $fileName;

if (file_exists($filePath) && strpos(realpath($filePath), realpath($uploadDir)) === 0) {
    if (unlink($filePath)) {        
        $_SESSION['flash_message'] = [
            'type' => 'success',
            'message' => "File '" . htmlspecialchars($fileName) . "' berhasil dihapus!"
        ];
    } else {
        $_SESSION['flash_message'] = [
            'type' => 'error',
            'message' => 'Gagal menghapus file. Periksa izin akses server.'
        ];
    }
} else {
    $_SESSION['flash_message'] = [
        'type' => 'error',
        'message' => 'File tidak ditemukan atau akses tidak valid.'
    ];
}

header("Location: list_files.php");
exit();
?>