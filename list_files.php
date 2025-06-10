<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar File</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f0f2f5;
            padding-top: 40px;
            padding-bottom: 40px;
        }
        .container { max-width: 800px; }
        .card {
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-radius: 12px;
        }
        .file-icon { font-size: 1.5rem; }
        .list-group-item {
            transition: background-color 0.2s ease-in-out;
        }
        
  
        .btn-primary {
            background-color: #20c997;
            border-color: #20c997;
        }
        .btn-primary:hover {
            background-color: #198754;
            border-color: #198754;
        }


        .btn-primary:focus, .btn-primary:active {
            background-color: #198754 !important; 
            border-color: #198754 !important;
            box-shadow: 0 0 0 0.25rem rgba(32, 201, 151, 0.5) !important;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card p-4">
        <h2 class="card-title text-center mb-4">Daftar File yang Telah Diunggah</h2>

        <?php

        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $files = array_diff(scandir($uploadDir), ['.', '..']);
        if (empty($files)) {
            echo '<div class="alert alert-info text-center">Belum ada file yang diunggah.</div>';
        } else {
            echo '<ul class="list-group list-group-flush">';
            foreach ($files as $file) {
                $filePath = $uploadDir . $file;
                $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                $fileIcon = '📁';
                switch ($fileExtension) {
                    case 'pdf': $fileIcon = '📄'; break;
                    case 'jpg': case 'jpeg': case 'png': case 'gif': $fileIcon = '🖼️'; break;
                    case 'doc': case 'docx': $fileIcon = '📝'; break;
                    case 'zip': case 'rar': $fileIcon = '📦'; break;
                    case 'txt': $fileIcon = '📃'; break;
                    case 'csv': case 'xls': case 'xlsx': $fileIcon = '📊'; break;
                }
                echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                echo '  <div class="d-flex align-items-center">';
                echo '      <span class="file-icon me-3">' . $fileIcon . '</span>';
                echo '      <span>' . htmlspecialchars($file) . '</span>';
                echo '  </div>';
                echo '  <div>';
                echo '      <a class="btn btn-success btn-sm me-2" href="' . htmlspecialchars($filePath) . '" download>Unduh</a>';
                echo '      <a class="btn btn-danger btn-sm" href="#" onclick="event.preventDefault(); konfirmasiHapus(\'' . urlencode($file) . '\')">Hapus</a>';
                echo '  </div>';
                echo '</li>';
            }
            echo '</ul>';
        }
        ?>
        <div class="text-center mt-4">
            <a href="index.html" class="btn btn-primary">Unggah File Baru</a>
        </div>
    </div>
</div>


<?php
if (isset($_SESSION['flash_message'])) {
    $flash = $_SESSION['flash_message'];
    $type = $flash['type'];
    $message = addslashes($flash['message']);
    $title = ($type === 'success') ? 'Berhasil!' : 'Terjadi Kesalahan!';
    unset($_SESSION['flash_message']);
    $confirmButtonColor = ($type === 'success') ? '#20c997' : '#d33';
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '{$type}',
                title: '{$title}',
                text: '{$message}',
                confirmButtonColor: '{$confirmButtonColor}'
            });
        });
    </script>";
}
?>
<script>
function konfirmasiHapus(fileName) {
    const namaFileDecoded = decodeURIComponent(fileName.replace(/\+/g, ' '));
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `File "${namaFileDecoded}" akan dihapus secara permanen!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'delete.php?file=' + fileName;
        }
    });
}
</script>

</body>
</html>