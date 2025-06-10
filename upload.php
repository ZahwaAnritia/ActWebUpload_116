<?php


header('Content-Type: application/json');
$response = ['status' => 'error', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Akses tidak sah.';
    echo json_encode($response);
    exit();
}
$target_dir = "uploads/";
$allowedTypes = ['image/jpeg', 'image/png','application/pdf'];
$maxSize = 5 * 1024 * 1024; 

if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

if (!isset($_FILES["fileToUpload"])) {
    $response['message'] = 'Tidak ada file yang dipilih.';
    echo json_encode($response);
    exit();
}


$uploadErrors = [
    UPLOAD_ERR_INI_SIZE => 'File terlalu besar (melebihi batas server)',
    UPLOAD_ERR_FORM_SIZE => 'File terlalu besar (melebihi batas form)',
    UPLOAD_ERR_PARTIAL => 'File hanya terunggah sebagian',
    UPLOAD_ERR_NO_FILE => 'Tidak ada file yang diunggah',
    UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary tidak ada',
    UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
    UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh ekstensi PHP'
];

if ($_FILES["fileToUpload"]["error"] !== UPLOAD_ERR_OK) {
    $response['message'] = $uploadErrors[$_FILES["fileToUpload"]["error"]] ?? 'Error upload tidak diketahui.';
    echo json_encode($response);
    exit();
}

$fileType = mime_content_type($_FILES["fileToUpload"]["tmp_name"]);
if (!in_array($fileType, $allowedTypes)) {
    $response['message'] = 'Jenis file tidak diizinkan. Hanya JPEG, PNG, dan PDF yang diterima.';
    echo json_encode($response);
    exit();
}

if ($_FILES["fileToUpload"]["size"] > $maxSize) {
    $response['message'] = 'Ukuran file terlalu besar. Maksimal 5MB.';
    echo json_encode($response);
    exit();
}


$originalFilename = basename($_FILES["fileToUpload"]["name"]);

$target_file = $target_dir . $originalFilename;


if (file_exists($target_file)) {
    $response['message'] = 'Gagal! File dengan nama "' . htmlspecialchars($originalFilename) . '" sudah ada.';
    echo json_encode($response);
    exit();
}

if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    $response['status'] = 'success';
    $response['message'] = 'File berhasil diunggah!';
    

    $response['filename'] = $originalFilename; 

} else {
    $response['message'] = 'Gagal menyimpan file.';
}

echo json_encode($response);
exit();
?>